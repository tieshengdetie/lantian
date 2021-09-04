<?php
declare(strict_types=1);
/**
 * api请求后返回的业务码
 */
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;


/**
 * @Constants
 */
class BusinessCode extends  AbstractConstants{

    /**
     * @Message("请求成功！")
     */
    const SUCCESS_REQUEST = 100000;
    /**
     * @Message("token认证失败！")
     */
    const TOKEN_VALID = 400001;
    /**
     * @Message("token已过期！")
     */
    const TOKEN_EXPERIED = 400002;
    /**
     * @Message("token不存在！")
     */
    const TOKEN_MISSING = 400003;
    /**
     * @Message("请求出错！")
     */
    const FAIL_REQUEST = 999999;
}