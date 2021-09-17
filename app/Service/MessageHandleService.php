<?php
namespace App\Service;



use Exception;
use Swoole\Http\Response;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use App\Exception\WebSocketException;

class MessageHandleService extends BaseService {

    /**
     * 消息分发
     *
     * @param Response|Server $server
     * @param Frame           $frame
     * @param array|string    $data 解析后数据
     * @return void
     */

    public function dispatch($server,Frame $frame,$data){

        //格式化化数据
        $formatData = $this->formatClientData($data);
        //验签
        $signMd5 = $this->signMd5($formatData);

        if(!$signMd5){
            throw new Exception('验签不成功');
        }
        var_dump($signMd5);

    }
}
