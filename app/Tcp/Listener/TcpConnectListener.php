<?php declare(strict_types=1);


namespace App\Tcp\Listener;

use Swoft;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\CLog;
use Swoft\Tcp\Packer\XphPacker;
use Swoft\Tcp\Server\TcpServerEvent;

/**
 * Class TcpConnectListener
 *
 * @since 2.0
 *
 * @Listener(TcpServerEvent::CONNECT)
 */
class TcpConnectListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        /* @var \Swoole\Server $server */
//        vdump($event);
        $server = $event->getTarget();

        $fd = $event->getParam(0);
        $reactorId = $event->getParam(1);

        $protocol = Swoft::getBean('tcpServerProtocol');
        server()->log("有设备接入，开始发送读设备ID 命令");
        $xph = $protocol->getPacker(XphPacker::TYPE);

        $content = $xph->getDeviceId();
        if ($server->send($fd, $content) === false) {
            $code = $server->getLastError();
            Clog::error("Error on send data to client #{$fd}", $code);
        }
    }
}
