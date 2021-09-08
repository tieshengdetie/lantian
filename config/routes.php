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
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');
Router::post('/user/login', 'App\Controller\AppApi\AuthController@login');
Router::post('/user/add', 'App\Controller\AppApi\UserController@add');

Router::addGroup('/echarts/', function () {
    Router::post('getEchartsData', 'App\Controller\AppApi\EchartsDataController@getEchartsData');
}
,['middleware' => [App\Middleware\AppApi\JwtAuthMiddleware::class]]
);

Router::get('/favicon.ico', function () {
    return '';
});

//websocket
Router::addServer('ws', function () {
    Router::get('/', 'App\Controller\WebSocket\WebSocketController');
});
