<?php
/**
 * 公共方法类
 * User: penghcheng
 * Date: 2020/5/18
 * Time: 11:29
 */

use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\JobInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\Formatter\FormatterInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;
use HyperfExt\Auth\Contracts\AuthManagerInterface;
use HyperfExt\Auth\Contracts\GuardInterface;
use HyperfExt\Auth\Contracts\StatefulGuardInterface;
use HyperfExt\Auth\Contracts\StatelessGuardInterface;
use Psr\Container\ContainerInterface;
use Swoole\WebSocket\Server as WebSocketServer;

/**
 * 获取Container
 */
if (!function_exists('container')) {
    /**
     * Finds an entry of the container by its identifier and returns it.
     * @param null|mixed $id
     * @return mixed|ContainerInterface
     */
    function container($id = null)
    {
        $container = ApplicationContext::getContainer();
        if ($id) {
            return $container->get($id);
        }
        return $container;
    }
}

if (!function_exists('auth')) {
    /**
     * Auth认证辅助方法
     * @param string|null $guard
     * @return GuardInterface|StatefulGuardInterface|StatelessGuardInterface
     */
    function auth(string $guard = null)
    {
        if (is_null($guard)) $guard = config('auth.default.guard');
        return make(AuthManagerInterface::class)->guard($guard);
    }
}

#app/Functions.php
/**
 * 控制台日志
 */
if (!function_exists('stdLog')) {
    function stdLog()
    {
        return container()->get(StdoutLoggerInterface::class);
    }
}

/**
 * 文件日志
 */
if (!function_exists('logger')) {
    function logger($name = 'hyperf', $group = 'default')
    {
        return container()->get(LoggerFactory::class)->get($name, $group);
    }
}

if (!function_exists('websocket')) {
    /**
     *  websocketServer实例
     *
     * @return mixed|WebSocketServer
     */
    function websocket()
    {
        return container()->get(WebSocketServer::class);
    }
}
/**
 * redis 客户端实例
 */
if (!function_exists('redis')) {
    function redis()
    {
        return container()->get(Hyperf\Redis\Redis::class);
    }
}

/**
 * 缓存实例 简单的缓存
 */
if (!function_exists('cache')) {
    function cache()
    {
        return container()->get(\Psr\SimpleCache\CacheInterface::class);
    }
}

if (!function_exists('format_throwable')) {
    /**
     * Format a throwable to string.
     * @param Throwable $throwable
     * @return string
     */
    function format_throwable(Throwable $throwable): string
    {
        return container()->get(FormatterInterface::class)->format($throwable);
    }
}

if (!function_exists('queue_push')) {
    /**
     * Push a job to async queue.
     * @param JobInterface $job
     * @param int $delay
     * @param string $key
     * @return bool
     */
    function queue_push(JobInterface $job, int $delay = 0, string $key = 'default'): bool
    {
        $driver = container()->get(DriverFactory::class)->get($key);
        return $driver->push($job, $delay);
    }
}

if (!function_exists('encryptWithSalt')) {
    /**
     * 加密
     * @param $str
     * @param $salt
     * @return string
     */
    function encryptWithSalt($str, $salt)
    {
        return md5(md5($str) . $salt);
    }
}

if (!function_exists('encrypt')) {
    /**
     * 加密函数
     *
     * @param string $str 加密前的字符串
     * @param string $key 密钥
     * @return string 加密后的字符串
     */
    function encrypt($str, $key = '')
    {
        $coded = '';
        $keylength = strlen($key);

        for ($i = 0, $count = strlen($str); $i < $count; $i += $keylength) {
            $coded .= substr($str, $i, $keylength) ^ $key;
        }

        return str_replace('=', '', base64_encode($coded));
    }
}

if (!function_exists('decrypt')) {
    /**
     * 解密函数
     *
     * @param string $str 加密后的字符串
     * @param string $key 密钥
     * @return string 加密前的字符串
     */
    function decrypt($str, $key = '')
    {
        $coded = '';
        $keylength = strlen($key);
        $str = base64_decode($str);

        for ($i = 0, $count = strlen($str); $i < $count; $i += $keylength) {
            $coded .= substr($str, $i, $keylength) ^ $key;
        }

        return $coded;
    }
}

/**
 * 校验密码复杂度
 */
if (!function_exists('valid_pass')) {
    function valid_pass($password)
    {
        //$r1 = '/[A-Z]/';  //uppercase
        $r2 = '/[A-z]/';  //lowercase
        $r3 = '/[0-9]/';  //numbers
        $r4 = '/[~!@#$%^&*()\-_=+{};:<,.>?]/';  // special char

        /*if (preg_match_all($r1, $candidate, $o) < 1) {
            $msg =  "密码必须包含至少一个大写字母，请返回修改！";
            return FALSE;
        }*/
        if (preg_match_all($r2, $password, $o) < 1) {
            $msg = "密码必须包含至少一个字母，请返回修改！";
            return ['code' => -1, 'msg' => $msg];
        }
        if (preg_match_all($r3, $password, $o) < 1) {
            $msg = "密码必须包含至少一个数字，请返回修改！";
            return ['code' => -1, 'msg' => $msg];
        }
        /*if (preg_match_all($r4, $candidate, $o) < 1) {
            $msg =  "密码必须包含至少一个特殊符号：[~!@#$%^&*()\-_=+{};:<,.>?]，请返回修改！";
            return FALSE;
        }*/
        if (strlen($password) < 8) {
            $msg = "密码必须包含至少含有8个字符，请返回修改！";
            return ['code' => -1, 'msg' => $msg];
        }
        return ['code' => 0, 'msg' => 'success'];
    }
}

/**
 * 检查手机号码格式
 * @param $mobile 手机号码
 */
if (!function_exists('check_mobile')) {
    function check_mobile($mobile)
    {
        if (preg_match('/1[3-9]\d{9}$/', $mobile) || preg_match('/000\d{8}$/', $mobile)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('page')) {
    /**
     * 计算总页数等
     * @param int $pageSize
     * @param int $currPage
     * @param $totalCount
     * @return array
     */
    function page($totalCount, int $pageSize = 10, int $currPage = 1): array
    {
        if ($totalCount > 0) {
            $totalPage = ceil($totalCount / $pageSize);
        } else {
            $totalPage = 0;
        }

        if ($currPage <= 0 || $currPage > $totalPage) {
            $currPage = 1;
        }

        $startCount = ($currPage - 1) * $pageSize;
        return array($totalPage, $startCount);
    }


}

if (!function_exists('isJson')) {
    /**
     * 判断是否为json字符串
     */
    function isJson($json)
    {
        return !preg_match('/[^,:{}\\[\\]0-9.\\-+Eaeflnr-u \\n\\r\\t]/', preg_replace('/"(\\.|[^"\\\\])*"/', '', $json));
    }


}
