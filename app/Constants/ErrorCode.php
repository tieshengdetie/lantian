<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("Server Error！")
     */
    const SERVER_ERROR = 500;
    /**
     * @Message("Authorization Error！")
     */
    const AUTHORIZATION_ERROR = 401;
    /**
     * @Message("BadRequest！")
     */
    const BAD_REQUEST = 400;

    /**
     * @Message(" Not Found！")
     */
    const NOT_FOUND = 404;

    /**
     * @Message("emporarily Moved！")
     */
    const TEMPORARILY_MOVED  = 302;
}
