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
use App\Service\MessageHandleService;
use Hyperf\Di\Annotation\Inject;

class WebSocketController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{

    /**
     * @inject
     * @var MessageHandleService
     */
    private $messageHandleService;

    public function onMessage($server, Frame $frame): void
    {

        $data = $frame->data;
        //判断接受的数据是否为标准json
        $isJson = isJson($data);
        if($isJson){
            var_dump($data);
        }else{
            //分发数据
            call_user_func_array([$this->messageHandleService,'dispatch'],[$server,$frame,$data]);
        }

    }

    public function onClose($server, int $fd, int $reactorId): void
    {
        stdLog()->notice("连接已断开！ 时间：". date('Y-m-d H:i:s'));

        $this->messageHandleService->removeBind($fd);

    }

    public function onOpen($server, Request $request): void
    {
        stdLog()->notice("用户连接信息 : fd:{$request->fd} 时间：". date('Y-m-d H:i:s'));
        $server->push($request->fd, '已连接');
    }
}
