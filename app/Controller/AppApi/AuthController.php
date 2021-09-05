<?php

declare(strict_types=1);

namespace App\Controller\AppApi;

use App\Constants\HttpCode;
use App\Constants\BusinessCode;
use Hyperf\HttpServer\Contract\RequestInterface;
use HyperfExt\Jwt\Contracts\JwtFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AuthController
 * @package App\Controller\AppApi
 */
class AuthController extends AbstractController
{


    /**
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function login(RequestInterface $request): ResponseInterface
    {
        $credentials = $request->inputs(['email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            return $this->setHttpCode(HttpCode::BAD_REQUEST)->fail('Unauthorized');
        }
        return $this->respondWithToken($token);
    }

    /**
     *
     */
    public function me(): ResponseInterface
    {
        return $this->success(auth('api')->user());
    }

    /**
     *
     */
    public function refresh(): ResponseInterface
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     *
     */
    public function logout(): ResponseInterface
    {
        auth('api')->logout();
        return $this->success(['message' => 'Successfully logged out']);
    }

    /**
     * @param $token
     * @return ResponseInterface
     */
    protected function respondWithToken($token): ResponseInterface
    {
        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expire_in' => make(JwtFactoryInterface::class)->make()->getPayloadFactory()->getTtl()
        ]);
    }

}