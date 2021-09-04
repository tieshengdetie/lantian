<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
$appEnv = env('APP_ENV', 'dev');
if ($appEnv == 'dev') {
    $formatter = [
        'class' => Monolog\Formatter\LineFormatter::class,
        'constructor' => [
            'format' => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
            'allowInlineLineBreaks' => true,
            'includeStacktraces' => true,
        ],
    ];
} else {
    $formatter = [
        'class' => Monolog\Formatter\JsonFormatter::class,
        'constructor' => [],
    ];
}
return [
    'default' => [
        'handler' => [
//            'class' => Monolog\Handler\StreamHandler::class,
            'class' => \Monolog\Handler\RotatingFileHandler::class,
            'constructor' => [
//                'stream' => BASE_PATH . '/runtime/logs/hyperf.log',
                'filename' => BASE_PATH . '/runtime/logs/hyperf.log',
//                'stream' => 'php://stdout',
                'level' => Monolog\Logger::DEBUG,
            ],
        ],
        'formatter' => $formatter,
    ],
    'sql' => [
        'handler' => [
//            'class' => Monolog\Handler\StreamHandler::class,
            'class' => \Monolog\Handler\RotatingFileHandler::class,
            'constructor' => [
//                'stream' => BASE_PATH . '/runtime/logs/hyperf.log',
                'filename' => BASE_PATH . '/runtime/logs/hyperf_sql.log',
                'stream' => 'php://stdout',
                'level' => Monolog\Logger::DEBUG,
            ],
        ],
        'formatter' => [
            'class' => Monolog\Formatter\LineFormatter::class,
            'constructor' => [
                'format' => null,
                'allowInlineLineBreaks' => true,
                'includeStacktraces' => true,
            ],
         ],
    ],

];
