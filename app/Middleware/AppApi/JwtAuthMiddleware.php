<?php

declare(strict_types=1);

namespace App\Middleware\AppApi;

use App\Model\User;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Phper666\JwtAuth\Jwt;
use Phper666\JwtAuth\Exception\TokenValidException;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use App\Common\Utils\ResponseUtils;
use App\Constants\ErrorCode;
use App\Constants\BusinessCode;

class JwtAuthMiddleware implements MiddlewareInterface
{
    /**
     * @var HttpResponse
     */
    protected $response;

    protected $prefix = 'Bearer';
    protected $jwt;
    /**
     * @var ResponseUtils
     */
    protected $return;

    public function __construct(HttpResponse $response, Jwt $jwt, ResponseUtils $return)
    {
        $this->response = $response;
        $this->jwt      = $jwt;
        $this->return   = $return;
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): PsrResponseInterface
    {
        $isValidToken = false;
        $token = $request->getHeader('Authorization')[0] ?? '';
        if (strlen($token) > 0) {
            $token = ucfirst($token);
            $arr   = explode($this->prefix . ' ', $token);
            $token = $arr[1] ?? '';

            try {
                if (strlen($token) > 0 && $res = $this->jwt->checkToken()) {
                    $isValidToken = true;
                }
            } catch (\Exception $e) {

                return $this->return->fail(ErrorCode::AUTHORIZATION_ERROR,BusinessCode::TOKEN_VALID);
            }

        }else{
            return $this->return->fail(ErrorCode::BAD_REQUEST,BusinessCode::TOKEN_MISSING);
        }

        if ($isValidToken) {

            $jwtData = $this->jwt->getParserData();

//            $user = User::find($jwtData['uid']);
            $user =[
                'name'=>'zhaojinsheng'
            ];
            $request = Context::get(ServerRequestInterface::class);
            $request = $request->withAttribute('user', $user);
            Context::set(ServerRequestInterface::class, $request);

            return $handler->handle($request);
        }

        return $this->return->fail(ErrorCode::AUTHORIZATION_ERROR,BusinessCode::TOKEN_VALID);
    }
}
