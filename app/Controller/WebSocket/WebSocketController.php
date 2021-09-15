<?php
declare(strict_types=1);

namespace App\Controller\WebSocket;

use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Swoole\Http\Request;
use Swoole\Server;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server as WebSocketServer;

class WebSocketController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    public function onMessage($server, Frame $frame): void
    {
        $server->push($frame->fd, 'Recv: ' . $frame->data);
    }

    public function onClose($server, int $fd, int $reactorId): void
    {
        stdLog()->notice("连接已断开！ 时间：". date('Y-m-d H:i:s'));
    }

    public function onOpen($server, Request $request): void
    {
        stdLog()->notice("用户连接信息 : fd:{$request->fd} 时间：". date('Y-m-d H:i:s'));
        $server->push($request->fd, '已连接');
    }
}
