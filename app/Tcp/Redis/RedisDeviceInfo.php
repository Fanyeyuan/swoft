<?php declare(strict_types=1);

namespace App\Tcp\Redis;

use App\Tcp\Device\ChengDuDeviceInfo;
use Swoft\Log\Helper\CLog;
use Swoft\Redis\Redis;
use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Redis\Exception\RedisException;

class RedisDeviceInfo
{
    /**
     * redis 默认连接池
     */
    private const DEFAULT_POOL = 'redisTcp.pool';

    /**
     * 设置 $fd 对应设备的键值
     * @param string $mn
     * @param mixed $key 键
     * @param mixed $value 值
     * @return bool                 false 设置失败
     * @throws ContainerException
     * @throws RedisException
     * @throws ReflectionException
     */
    public static function set(string $mn, $key, $value): bool
    {
        $result = false;
        $redis = Redis::connection(self::DEFAULT_POOL);

        $device = $redis->get($mn);
        if($device === true)
        {
            if(isset($device[$key]))
            {
                $device[$key] = $value;

                $result = $redis->set($mn, $device);
            }
            else
            {
                Clog::warning("当前设备中不包含 {$key} 键值");
            }
        }
        else
        {
            CLog::warning("设备#{$mn} 在redis 中不存在，请先建立该表。");
        }
        return $result;
    }

    /**
     * @param ChengDuDeviceInfo $device
     * @return bool
     * @throws ContainerException
     * @throws RedisException
     * @throws ReflectionException
     */
    public static function setDevice(ChengDuDeviceInfo $device)
    {
        $redis = Redis::connection(self::DEFAULT_POOL);

        $result = $redis->set($device->getMN(), $device);

        return $result;
    }

    /**
     * @param string $mn
     * @param $key
     * @return bool|mixed
     * @throws ContainerException
     * @throws RedisException
     * @throws ReflectionException
     */
    public static function get(string $mn, $key)
    {
        $redis = Redis::connection(self::DEFAULT_POOL);

        $result = $redis->get($mn);

        if($result === true)
        {
            if(isset($result[$key]))
            {
                return $result[$key];
            }
            else
            {
                Clog::warning("当前设备中不包含 {$key} 键值");
            }
        }
        else
        {
            CLog::warning("设备#{string $mn} 在redis 中不存在，请先建立该表。");
        }

        return $result;
    }

    /**
     * 从 redis 中读取 $fd 对应的device 信息
     * @param string $mn
     * @return false/ChengDuDeviceInfo      false 读取失败，其他返回设备对象
     * @throws ContainerException
     * @throws RedisException
     * @throws ReflectionException
     */
    public static function getDevice(string $mn)
    {
        $redis = Redis::connection(self::DEFAULT_POOL);

        $result = $redis->get($mn);

        if($result === false)
        {
            CLog::warning("设备#{string $mn}  在redis 中不存在，请先建立该表。");
        }

        return $result;
    }
}
