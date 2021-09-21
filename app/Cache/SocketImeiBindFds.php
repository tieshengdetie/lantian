<?php

namespace App\Cache;

use App\Cache\Repository\SetGroupRedis;

/**
 * 注:用户ID与客户端ID绑定(一对多关系)
 *
 * @package App\Cache
 */
class SocketImeiBindFds extends SetGroupRedis
{
    protected $name = 'ws:imei-fds';

    /**
     * @param int    $fd      客户端ID
     * @param string    $imei    设备ID
     * @param string $run_id  服务运行ID（默认当前服务ID）
     * @return bool|int
     */
    public function bind(int $fd, string $imei, $run_id = SERVER_RUN_ID)
    {
        return $this->add($this->filter([$run_id, $imei]), $fd);
    }

    /**
     * @param int    $fd     客户端ID
     * @param string $run_id 服务运行ID（默认当前服务ID）
     * @return int
     */
    public function unBind(int $fd, string $imei, $run_id = SERVER_RUN_ID)
    {
        return $this->rem($this->filter([$run_id, $imei]), $fd);
    }

    /**
     * 检测用户当前是否在线（指定运行服务器）
     *
     * @param string    $imei 用户ID
     * @param string $run_id  服务运行ID（默认当前服务ID）
     * @return bool
     */
    public function isOnline(string $imei, $run_id = SERVER_RUN_ID): bool
    {
        return (bool)$this->count($this->filter([$run_id, $imei]));
    }

    /**
     * 检测用户当前是否在线(查询所有在线服务器)
     *
     * @param string   $imei 用户ID
     * @param array $run_ids 服务运行ID（默认当前服务ID）
     * @return bool
     */
    public function isOnlineAll(string $imei, array $run_ids = []): bool
    {
        $run_ids = $run_ids ?: ServerRunID::getInstance()->getServerRunIdAll();

        foreach ($run_ids as $run_id => $time) {
            if ($this->isOnline($imei, $run_id)) return true;
        }

        return false;
    }

    /**
     * 查询用户的客户端fd集合(用户可能存在多端登录)
     *
     * @param string    $imei    设备ID
     * @param string $run_id  服务运行ID（默认当前服务ID）
     * @return array
     */
    public function findFds(string $imei, $run_id = SERVER_RUN_ID): array
    {
        $arr = $this->all($this->filter([$run_id, $imei]));
        foreach ($arr as $k => $value) {
            $arr[$k] = intval($value);
        }

        return $arr;
    }

    public function getCachePrefix(string $run_id): string
    {
        return $this->getCacheKey($run_id);
    }
}
