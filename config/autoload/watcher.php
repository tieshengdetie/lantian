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
use Hyperf\Watcher\Driver\ScanFileDriver;
use Hyperf\Watcher\Driver\FswatchDriver;
use Hyperf\Watcher\Driver\FindDriver;

return [
    'driver' => ScanFileDriver::class,
    'bin' => 'php',
    'watch' => [
        'dir' => ['app', 'config','vendor'],
        'file' => ['.env'],
        'scan_interval' => 2000,
    ],
];
