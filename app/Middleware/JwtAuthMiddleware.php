<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Model\User;
use Phper666\JWTAuth\JWT;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Phper666\JwtAuth\Exception\TokenValidException;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use App\Service\ResponseService;
use App\Constants\ErrorCode;

class JwtAuthMiddleware implements MiddlewareInterface
{
    /**
     * @var HttpResponse
     */
    protected $response;

    protected $prefix = 'Bearer';
    protected $jwt;

    public function __construct(ResponseService $response, JWT $jwt)
    {
        $this->response = $response;
        $this->jwt      = $jwt;
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
                if (strlen($token) > 0 && $this->jwt->checkToken()) {
                    $isValidToken = true;
                }
            } catch (\Exception $e) {
                return $this->response->setHttpCode(ErrorCode::AUTHORIZATION_ERROR)->fail("TOKEN验证失败");
            }

        }

        if ($isValidToken) {

            $jwtData = $this->jwt->getParserData();

            $user = User::find($jwtData['uid']);

            $request = Context::get(ServerRequestInterface::class);
            $request = $request->withAttribute('user', $user);
            Context::set(ServerRequestInterface::class, $request);

            return $handler->handle($request);
        }

        return $this->response->setHttpCode(ErrorCode::AUTHORIZATION_ERROR)->fail("TOKEN验证失败");
    }
}
