<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Tcp\Controller;

use App\Tcp\Db\dbHj212Data;
use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Db\Exception\DbException;
use Swoft\Log\Helper\CLog;
use Swoft\Redis\Redis;
use Swoft\Tcp\Server\Annotation\Mapping\TcpController;
use Swoft\Tcp\Server\Annotation\Mapping\TcpMapping;
use Swoft\Tcp\Server\Request;
use Swoft\Tcp\Server\Response;
use function strrev;

/**
 * Class DemoController
 *
 * @TcpController()
 */
class DemoController
{
    /**
     * @TcpMapping("list", root=true)
     * @param Response $response
     */
    public function list(Response $response): void
    {
        $response->setData('[list]allow command: list, echo, demo.echo');
    }

    /**
     * @TcpMapping("update", root=true)
     * @param Request $request
     * @param Response $response
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function update(Request $request, Response $response): void
    {
        $db = dbHj212Data::new();
        $db->setData($request->getPackage()->getDataString());
        $db->setPackage($request->getRawData());
        $db->setIdDeviceInfo(1);
        var_dump($db->getId());
        $db->save();
        var_dump($db->getId());

        Redis::set('device'.$db->getId(),'ok');
        var_dump(Redis::get('*'));

        $response->setData('is ok!');
    }

    /**
     * @TcpMapping("error", root=true)
     * @param Request  $request
     */
    public function error(Request $request): void
    {
        $str = $request->getPackage()->getDataString();

        CLog::warning('数据包异常--->'.$str);
    }

    /**
     * @TcpMapping("echo")
     * @param Request  $request
     * @param Response $response
     */
    public function index(Request $request, Response $response): void
    {
        $str = $request->getPackage()->getDataString();

        $response->setData('[demo.echo]hi, we received your message: ' . $str);
    }

    /**
     * @TcpMapping("strrev", root=true)
     * @param Request  $request
     * @param Response $response
     */
    public function strRev(Request $request, Response $response): void
    {
        $str = $request->getPackage()->getDataString();

        $response->setData(strrev($str));
    }

    /**
     * @TcpMapping("echo", root=true)
     * @param Request  $request
     * @param Response $response
     */
    public function echo(Request $request, Response $response): void
    {
        var_dump($request);
        // $str = $request->getRawData();
        $str = $request->getPackage()->getDataString();

        $response->setData('[echo]hi, we received your message: ' . $str);
    }
}
