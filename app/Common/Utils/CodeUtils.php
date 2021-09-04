<?php
declare(strict_types=1);

namespace App\Common\Utils;

/**
 * API请求返回业务码
 *
 * Class ResponseUtils
 *
 * @package App\Common\Utils
 */
class CodeUtils
{

    public function getBusinessMessage($code)
    {

        return [
                10000 => "请求成功",
                40001 => "token认证失败",
                99999 => "请求失败",

            ][$code] ?? "";
    }
}