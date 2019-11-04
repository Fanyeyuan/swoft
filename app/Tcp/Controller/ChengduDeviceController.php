<?php declare(strict_types=1);


namespace App\Tcp\Controller;

use App\Tcp\Device\DeviceInfo;
use App\Tcp\Redis\RedisDeviceInfo;
use App\Tcp\Redis\RedisDeviceTree;
use ReflectionException;
use Swoft\Db\Query\Builder;
use Swoft\Log\Helper\CLog;
use App\Tcp\Db\dbHj212Data;
use Swoft\Db\Eloquent\Model;
use Swoft\Redis\Exception\RedisException;
use Swoft\Tcp\Server\Response;
use Swoft\Tcp\Server\Request;
use App\Tcp\Db\dbHj212DeviceInfo;
use App\Tcp\Db\dbHj212DeviceType;
use Swoft\Db\Exception\DbException;
use Swoft\Stdlib\Helper\JsonHelper;
use App\Tcp\Device\ChengDuDeviceInfo;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Tcp\Server\Annotation\Mapping\TcpController;
use Swoft\Tcp\Server\Annotation\Mapping\TcpMapping;

/**
 * Class ChengduDeviceController
 *
 * @TcpController()
 */
class ChengduDeviceController
{
    /**
     * @TcpMapping("id", root=true)
     *
     * @param Request $request
     * @param Response $response
     * @throws ContainerException
     * @throws DbException
     * @throws RedisException
     * @throws ReflectionException
     */
    public function deviceId(Request $request, Response $response): void
    {
        $response->setSent(true);       //不返回数据

        $fd = $request->getFd();
        $id = $request->getPackage()->getData();
        $info = $this->getHj212DeviceInfo($id);
        if(!is_null($info))
        {
            $type = $this->getHj212DeviceType($info->getDeviceType());

            $deviceInfo = $info->getDeviceInfo();
            $deviceInfo = is_null($deviceInfo)?JsonHelper::decode('{"MN":null,"PW":null,"secret":null}', true):JsonHelper::decode($deviceInfo, true);

            $device = ChengDuDeviceInfo::new([
                'isCreated' => true,
                'id' => $info->getFacId(),
                'fd' => $fd,
//                'online' => true,
//                'status' => 0,
//                'errMsg' => '',
                'lastOnlineTime' => time(),
//                'lastUpdateTime' => null,
//                'lastData' => null,
//                'lastOffLimeTime' => null,
                'name' => $info->getName(),
                'type' => $type->getType(),
                'modifyTime' => $info->getModifyTime(),
                'createTime' => $info->getCreateTime(),
                'coefficient' => JsonHelper::decode($info->getParam(),true),
                'mn' => $deviceInfo['MN'],
                'pw' => $deviceInfo['PW'] ?? '123456',
                'secret' => $deviceInfo['secret'] ?? 'cdcs@123',
//                'rtdInterval' => $deviceInfo['RtdInterval'] ?? 30,
//                'minInterval' => $deviceInfo ?? 1,
//                'overTime' => $deviceInfo['OverTime'] ?? 10,
//                'reNumber' => $deviceInfo['ReNumber'] ?? 5,
            ]);

            RedisDeviceTree::set($device->getMN(),$fd,(string)$id);
            RedisDeviceInfo::setDevice($device);
            CLog::info("Client #{$fd} upload device id :{$id}");
        }
        else
        {
            CLog::info("Client #{$fd} upload device id :{$id}, but the device isn't found on database!");
        }

    }

    /**
     * @TcpMapping("time", root=true)
     *
     * @param Request $request
     * @param Response $response
     */
    public function deviceTime(Request $request, Response $response): void
    {
        $response->setSent(true);       //不返回数据

        $fd = $request->getFd();
        $status = $request->getPackage()->getData();

        CLog::info("Client #{$fd} 设置时间完成 {$status}");
        $response->setSent(true);
    }

    /**
     * @TcpMapping("jk", root=true)
     *
     * @param Request $request
     * @param Response $response
     */
    public function deviceJk(Request $request, Response $response): void
    {
        $response->setSent(true);       //不返回数据

        $fd = $request->getFd();
        $status = $request->getPackage()->getData();

        CLog::info("客户端#{$fd} 继电器控制命令成功，当前 JK{$status['jk']} 是 {$status['status']}");
    }

    /**
     * @TcpMapping("info", root=true)
     *
     * @param Request $request
     * @param Response $response
     */
    public function deviceInfo(Request $request, Response $response): void
    {
        $response->setSent(true);       //不返回数据

        $fd = $request->getFd();
        $status = $request->getPackage()->getData();

        CLog::info("客户端#{$fd} 设备设置参数成功 {$status}");
    }

    /**
     * @TcpMapping("data", root=true)
     *
     * @param Request $request
     * @param Response $response
     */
    public function deviceData(Request $request, Response $response): void
    {
        $response->setSent(true);       //不返回数据

        $fd = $request->getFd();
        $data = $request->getPackage()->getData();

        CLog::info("客户端{$fd} 更新实时数据，内容为：".JsonHelper::encode($data));
    }

    /**
     * @TcpMapping("2011", root=true)
     *
     * @param Request $request
     * @param Response $response
     * @throws ContainerException
     * @throws RedisException
     * @throws ReflectionException
     */
    public function deviceRtdData(Request $request, Response $response): void
    {
        $response->setSent(true);       //不返回数据

        $fd = $request->getFd();
        $data = $request->getPackage()->getData();
        $deviceTime = $data['DataTime'];
        $data = JsonHelper::encode($data);
        $mn = $request->getPackage()->getExtValue('MN');

        $device = RedisDeviceInfo::getDevice($mn);
        if($device instanceof  ChengDuDeviceInfo)
        {
            $device->setOnline(true);
            $device->setLastData($data);
            $device->setLastRawData($request->getRawData());
            $device->setLastUpdateTime(time());
            RedisDeviceInfo::setDevice($device);

            $currentTime = time();
            if($currentTime % 86400 === 110)    //00:01:50 校时
            {
                if( $currentTime - $deviceTime >= 90)
                {
                    $response->setSent(false);       //不返回数据

                    $response->setExt([
                        'cmd'       => 'setTime',
                        'param'     => null,
                        'param1'    => null,
                    ]);
                }
            }
        }
        else
        {
            $info = RedisDeviceTree::searchInfoByFd($fd);
            if($info !== false)
            {
                $response->setSent(false);       //不返回数据

                $device = RedisDeviceInfo::getDevice($info['mn']);
                if($device instanceof  ChengDuDeviceInfo) {
                    $response->setExt([
                        'cmd' => 'setMn',
                        'param' => $device->getMN(),
                        'param1' => $device->getPW(),
                        'param2' => $device->getSecret(),
                    ]);
                }
            }
            else
            {
                $device = ChengDuDeviceInfo::new([
                    'isCreated'         => false,
                    'id'                => '',
                    'name'              => '未知设备#'.$mn,
                    'fd'                => $fd,
                    'lastOnlineTime'    => time(),
                    'lastUpdateTime'    => time(),
                    'lastData'          => $data,
                    'lastOffLimeTime'   => null,
                    'mn'                => $mn,
                ]);
                RedisDeviceTree::set($device->getMN(), $fd,'');
                RedisDeviceInfo::setDevice($device);
            }
        }

        try {
            $this->saveHj212Data($request);
        } catch (ReflectionException $e) {
            $err = [
                'code' => $e->getCode(),
                'msg'  => $e->getMessage(),
            ];
            CLog::info('存储HJ212实时数据时，反射异常:'.JsonHelper::encode($err));
        } catch (ContainerException $e) {
            $err = [
                'code' => $e->getCode(),
                'msg'  => $e->getMessage(),
            ];
            CLog::info('存储HJ212实时数据时，容器异常：'.JsonHelper::encode($err));
        } catch (DbException $e) {
            $err = [
                'code' => $e->getCode(),
                'msg'  => $e->getMessage(),
            ];
            CLog::info('HJ212实时数据存储失败:'.JsonHelper::encode($err));
        }

        CLog::info("客户端{$fd} 上传实时数据，内容为：".$data);
    }

    /**
     * @TcpMapping("2111", root=true)
     *
     * @param Request $request
     * @param Response $response
     * @throws ContainerException
     * @throws RedisException
     * @throws ReflectionException
     */
    public function deviceRtdHistoryData(Request $request, Response $response): void
    {
        $response->setSent(true);       //不返回数据

        $fd = $request->getFd();
        $data = $request->getPackage()->getData();
        $deviceTime = $data['DataTime'];
        $data = JsonHelper::encode($data);
        $mn = $request->getPackage()->getExtValue('MN');

        $device = RedisDeviceInfo::getDevice($mn);
        if($device instanceof  ChengDuDeviceInfo)
        {
            $device->setOnline(true);
            $device->setRtdHistoryData($data);
            RedisDeviceInfo::setDevice($device);
        }

        CLog::info("客户端{$fd} 上传实时历史数据，内容为：".JsonHelper::encode($data));
    }

    /**
     * @TcpMapping("2051", root=true)
     *
     * @param Request $request
     * @param Response $response
     * @throws ContainerException
     * @throws RedisException
     * @throws ReflectionException
     */
    public function deviceMinuteData(Request $request, Response $response): void
    {
        $response->setSent(true);       //不返回数据

        $fd = $request->getFd();
        $data = JsonHelper::encode($request->getPackage()->getData());
        $mn = $request->getPackage()->getExtValue('MN');

        $device = RedisDeviceInfo::getDevice($mn);
        if($device instanceof  ChengDuDeviceInfo)
        {
            $device->setOnline(true);
            $device->setMinuteData($data);
            $device->setLastMinuteDataTime(time());
            RedisDeviceInfo::setDevice($device);
        }

        try {
            $this->saveHj212Data($request);
        } catch (ReflectionException $e) {
            $err = [
                'code' => $e->getCode(),
                'msg'  => $e->getMessage(),
            ];
            CLog::info('存储HJ212分钟数据时，反射异常:'.JsonHelper::encode($err));
        } catch (ContainerException $e) {
            $err = [
                'code' => $e->getCode(),
                'msg'  => $e->getMessage(),
            ];
            CLog::info('存储HJ212分钟数据时，容器异常：'.JsonHelper::encode($err));
        } catch (DbException $e) {
            $err = [
                'code' => $e->getCode(),
                'msg'  => $e->getMessage(),
            ];
            CLog::info('HJ212分钟数据存储失败:'.JsonHelper::encode($err));
        }
        CLog::info("客户端{$fd} 更新分钟数据，内容为：".JsonHelper::encode($data));
    }

    /**
     * @TcpMapping("2151", root=true)
     *
     * @param Request $request
     * @param Response $response
     * @throws ContainerException
     * @throws RedisException
     * @throws ReflectionException
     */
    public function deviceMinuteHistData(Request $request, Response $response): void
    {
        $response->setSent(true);       //不返回数据

        $fd = $request->getFd();
        $data = JsonHelper::encode($request->getPackage()->getData());
        $mn = $request->getPackage()->getExtValue('MN');

        $device = RedisDeviceInfo::getDevice($mn);
        if($device instanceof  ChengDuDeviceInfo)
        {
            $device->setOnline(true);
            $device->setMinuteHistoryData($data);
            RedisDeviceInfo::setDevice($device);
        }
        CLog::info("客户端{$fd} 上传分钟历史数据，内容为：".JsonHelper::encode($data));
    }

    /**
     * @TcpMapping("2061", root=true)
     *
     * @param Request $request
     * @param Response $response
     * @throws ContainerException
     * @throws RedisException
     * @throws ReflectionException
     */
    public function deviceHourData(Request $request, Response $response): void
    {
        $response->setSent(true);       //不返回数据

        $fd = $request->getFd();
        $data = JsonHelper::encode($request->getPackage()->getData());
        $mn = $request->getPackage()->getExtValue('MN');

        $device = RedisDeviceInfo::getDevice($mn);
        if($device instanceof  ChengDuDeviceInfo)
        {
            $device->setOnline(true);
            $device->setHourData($data);
            $device->setLastHourDataTime(time());
            RedisDeviceInfo::setDevice($device);
        }

        try {
            $this->saveHj212Data($request);
        } catch (ReflectionException $e) {
            $err = [
                'code' => $e->getCode(),
                'msg'  => $e->getMessage(),
            ];
            CLog::info('存储HJ212小时数据时，反射异常:'.JsonHelper::encode($err));
        } catch (ContainerException $e) {
            $err = [
                'code' => $e->getCode(),
                'msg'  => $e->getMessage(),
            ];
            CLog::info('存储HJ212小时数据时，容器异常：'.JsonHelper::encode($err));
        } catch (DbException $e) {
            $err = [
                'code' => $e->getCode(),
                'msg'  => $e->getMessage(),
            ];
            CLog::info('HJ212小时数据存储失败:'.JsonHelper::encode($err));
        }
        CLog::info("客户端{$fd} 更新小时数据，内容为：".JsonHelper::encode($data));
    }

    /**
     * @TcpMapping("2161", root=true)
     *
     * @param Request $request
     * @param Response $response
     * @throws ContainerException
     * @throws RedisException
     * @throws ReflectionException
     */
    public function deviceHourHistoryData(Request $request, Response $response): void
    {
        $response->setSent(true);       //不返回数据

        $fd = $request->getFd();
        $data = JsonHelper::encode($request->getPackage()->getData());
        $mn = $request->getPackage()->getExtValue('MN');

        $device = RedisDeviceInfo::getDevice($mn);
        if($device instanceof  ChengDuDeviceInfo)
        {
            $device->setOnline(true);
            $device->setHourHistoryData($data);
            RedisDeviceInfo::setDevice($device);
        }
        CLog::info("客户端{$fd} 上传小时历史数据，内容为：".JsonHelper::encode($data));
    }

    /**
     * @TcpMapping("2031", root=true)
     *
     * @param Request $request
     * @param Response $response
     * @throws ContainerException
     * @throws RedisException
     * @throws ReflectionException
     */
    public function deviceDayData(Request $request, Response $response): void
    {
        $response->setSent(true);       //不返回数据

        $fd = $request->getFd();
        $data = JsonHelper::encode($request->getPackage()->getData());
        $mn = $request->getPackage()->getExtValue('MN');

        $device = RedisDeviceInfo::getDevice($mn);
        if($device instanceof  ChengDuDeviceInfo)
        {
            $device->setOnline(true);
            $device->setDayData($data);
            $device->setLastDayDataTime(time());
            RedisDeviceInfo::setDevice($device);
        }

        try {
            $this->saveHj212Data($request);
        } catch (ReflectionException $e) {
            $err = [
                'code' => $e->getCode(),
                'msg'  => $e->getMessage(),
            ];
            CLog::info('存储HJ212天数据时，反射异常:'.JsonHelper::encode($err));
        } catch (ContainerException $e) {
            $err = [
                'code' => $e->getCode(),
                'msg'  => $e->getMessage(),
            ];
            CLog::info('存储HJ212天数据时，容器异常：'.JsonHelper::encode($err));
        } catch (DbException $e) {
            $err = [
                'code' => $e->getCode(),
                'msg'  => $e->getMessage(),
            ];
            CLog::info('HJ212天数据存储失败:'.JsonHelper::encode($err));
        }
        CLog::info("客户端{$fd} 更新天数据，内容为：".JsonHelper::encode($data));
    }

    /**
     * @TcpMapping("2131", root=true)
     *
     * @param Request $request
     * @param Response $response
     * @throws ContainerException
     * @throws RedisException
     * @throws ReflectionException
     */
    public function deviceDayHistoryData(Request $request, Response $response): void
    {
        $response->setSent(true);       //不返回数据

        $fd = $request->getFd();
        $data = JsonHelper::encode($request->getPackage()->getData());
        $mn = $request->getPackage()->getExtValue('MN');

        $device = RedisDeviceInfo::getDevice($mn);
        if($device instanceof  ChengDuDeviceInfo)
        {
            $device->setOnline(true);
            $device->setDayHistoryData($data);
            RedisDeviceInfo::setDevice($device);
        }

        CLog::info("客户端{$fd} 天历史数据，内容为：".JsonHelper::encode($data));
    }

    /**
     * 保存 HJ212 数据到数据库
     * @param Request $request
     * @return bool
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function saveHj212Data(Request $request): bool
    {
        $db = dbHj212Data::new();
        $db->setData($request->getPackage()->getDataString());
        $db->setPackage($request->getRawData());
        $db->setIdDeviceInfo(1);
        $db->save();

        return true;
    }

    /**
     * 从 device_info 表中通过设备ID 取出设备信息
     * @param $id
     * @return object|Model|Builder|null
     * @throws DbException
     */
    public function getHj212DeviceInfo($id)
    {
//        $db = DB::query('dbTcp.pool')->from('device_info')->where('facId', $id)->first();

        $db = dbHj212DeviceInfo::where('facId',  $id)->first();

        return($db);
    }

    /**
     * 从 device_type 表中通过编号 取出设备信息
     * @param int $id   表中的序号
     * @return object   dbHj212DeviceType对象
     */
    public function getHj212DeviceType($id)
    {
        // find($id, [*])  $id 行号  * 字段名，* 表示查询所有
        $db = dbHj212DeviceType::find($id);

        return($db);
    }
}
