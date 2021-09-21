<?php
namespace App\Service;



use App\Cache\SocketImeiBindFds;
use Exception;
use Swoole\Http\Response;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use App\Exception\WebSocketException;
use App\Cache\SocketFdBindImei;

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
        //验签通过
        if($signMd5){
            //判断设备是否已经绑定
            $socketImeiBindFds = SocketImeiBindFds::getInstance();
            $isOnline = $socketImeiBindFds ->isOnline($formatData['device_imei']);
            if($isOnline === false){
                $socketFdBindImei = SocketFdBindImei::getInstance();
                //绑定会话fd和设备imei的关系
                $socketFdBindImei->bind($frame->fd,$formatData['device_imei']);
                $socketImeiBindFds->bind($frame->fd,$formatData['device_imei']);
            }
            //分发存储消息

            //回复客户端
            switch ($formatData['data_type']){

                case 'UD':
                    return;
                case 'Time':
                    $time = time();
                    $server->push($frame->fd, "[{$formatData['str_info']},{$time}]");
                default:
                    $server->push($frame->fd, "[{$formatData['str_info']}]");
            }


        }



    }
}
