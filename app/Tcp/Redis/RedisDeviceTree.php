<?php declare(strict_types=1);


namespace App\Tcp\Redis;


use PHPUnit\Framework\Assert;
use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Redis\Exception\RedisException;
use Swoft\Redis\Redis;

class RedisDeviceTree extends Assert
{
    /**
     * redis 默认关键字
     */
    private const DEFAULT_KEY = 'deviceTree';

    /**
     * redis 默认连接池
     */
    private const DEFAULT_POOL = 'redisTcp.pool';

    /**
     * key      : self::DEFAULT_KEY
     * value    : [
     *              'fd' => [
     *               'mn'=> string,
     *              'id' => string,
     *              ]
     *           ]
     * @var array 设备树集合
     */

    /**
     * 写入设备树
     * @param string $mn 设备MN 作为redis key值
     * @param int $fd 连接fd 作为值
     * @param string $id 设备ID 作为值
     * @param string $tree
     * @return bool
     * @throws ContainerException
     * @throws RedisException
     * @throws ReflectionException
     */
    public static function set(string $mn, int $fd, string $id, string $tree = self::DEFAULT_KEY): bool
    {
        $redis = Redis::connection(self::DEFAULT_POOL);

        $device = $redis->get($tree);
        if($device === false)
        {
            $device = array(
                $fd => ['mn'=>$mn, 'id'=>$id]
            );
        }
        else
        {
            $fd2 = self::isExist($device, $mn, $id);
            unset($device[$fd2]);

            $device[$fd] = ['mn'=>$mn, 'id'=>$id];
        }
        $result = $redis->set($tree, $device);

        return $result;
    }

    /**
     * 向 redis 中写入 mn号
     * @param string $mn
     * @param int $fd
     * @param string $tree
     * @return bool
     * @throws ContainerException
     * @throws RedisException
     * @throws ReflectionException
     */
    public static function setMn(string $mn, int $fd, string $tree = self::DEFAULT_KEY): bool
    {
        $redis = Redis::connection(self::DEFAULT_POOL);

        $device = $redis->get($tree);
        if($device === false)
        {
            $device = array(
                $fd => ['mn'=>$mn, 'id'=>'']
            );
        }
        else
        {
            $fd2 = self::isExist($device, $mn);
            unset($device[$fd2]);

            $device[$fd]['mn'] = $mn;
        }
        $result = $redis->set($tree, $device);

        return $result;
    }

    /**
     * 向 redis 中写入 设备ID
     * @param string $id
     * @param int $fd
     * @param string $tree
     * @return bool
     * @throws ContainerException
     * @throws RedisException
     * @throws ReflectionException
     */
    public static function setId(string $id, int $fd, string $tree = self::DEFAULT_KEY): bool
    {
        $redis = Redis::connection(self::DEFAULT_POOL);

        $device = $redis->get($tree);
        if($device === false)
        {
            $device = array(
                $fd => ['mn'=>'', 'id'=>$id]
            );
        }
        else
        {
            $fd2 = self::isExist( $device, null, $id);
            unset($device[$fd2]);

            $device[$fd]['id'] = $id;
        }
        $result = $redis->set($tree, $device);

        return $result;
    }

    /**
     * @param string $tree
     * @return bool|mixed           false => 不存在  其他 设备树
     * @throws ContainerException
     * @throws RedisException
     * @throws ReflectionException
     */
    public static function get(string $tree = self::DEFAULT_KEY)
    {
        $redis = Redis::connection(self::DEFAULT_POOL);

        $result = $redis->get($tree);

        return $result;
    }

    /**
     * 通过 链接 fd ，从redis 中查找 设备MN 号和设备ID
     * @param int $fd
     * @return bool|array           false => 不存在  其他['mn' => $mn, 'id' => $id]
     * @throws ContainerException
     * @throws RedisException
     * @throws ReflectionException
     */
    public static function searchInfoByFd(int $fd)
    {
        $device = self::get();
        if($device !== false)
        {
            if(isset($device[$fd]))
            {
                return $device[$fd];
            }
        }

        return false;
    }

    /**
     * 查找 设备树中 mn号 或 id 是否存在
     * @param array $device
     * @param string|null $mn
     * @param string|null $id
     * @return bool|int
     */
    public static function isExist(array $device, string $mn = null, string $id = null)
    {
        $mnIsEmpty = empty($mn);
        $idIsEmpty = empty($id);

//        $device = self::get();

//        if($device !== false)
        {
            if($idIsEmpty === false && $mnIsEmpty === false)
            {
                foreach ($device as $fd => $info)
                {
                    if($mn === $info['mn'])
                        return $fd;
                    if($id === $info['id'])
                        return $fd;
                }
            }
        }

        return false;
    }
}
