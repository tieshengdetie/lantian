<?php

namespace App\Cache;

use App\Cache\Repository\HashGroupRedis;

/**
 * 注:客户端ID与用户ID绑定(多对一关系)
 *
 * @package App\Cache
 */
class SocketFdBindImei extends HashGroupRedis
{
    protected $name = 'ws:fd-imei';

    /**
     * 添加绑定
     *
     * @param int    $fd      回话ID
     * @param string    $imei    设备码ID
     * @param string $run_id  服务运行ID（默认当前服务ID）
     * @return bool|int
     */
    public function bind(int $fd, string $imei, $run_id = SERVER_RUN_ID)
    {
        return $this->add($run_id, strval($fd), $imei);
    }

    /**
     * 解除绑定
     *
     * @param string $run_id 服务运行ID（默认当前服务ID）
     * @return bool|int
     */
    public function unBind(int $fd, $run_id = SERVER_RUN_ID)
    {
        return $this->rem($run_id, strval($fd));
    }

    /**
     * 查询会话FD对应的设备IMEI
     *
     * @param int    $fd     会话ID
     * @param string $run_id 服务运行ID（默认当前服务ID）
     * @return int
     */
    public function findDeviceImei(int $fd, $run_id = SERVER_RUN_ID): string
    {
        return (string)$this->get($run_id, strval($fd)) ?: "";
    }

    /**
     * 根据用户的IMEI查询回话fd
     * @param string $imei
     * @param string $run_id
     */
    public function findDeviceFd(string $imei,$run_id = SERVER_RUN_ID){

        $arr = $this->getAll($run_id);
    }
}
