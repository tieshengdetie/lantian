<?php

declare(strict_types=1);

namespace App\Controller\AppApi;

use App\Constants\HttpCode;
use App\Constants\BusinessCode;
use App\Controller\AbstractController;
use App\Model\User;
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
        $credentials = $request->inputs(['username', 'password']);
        //查询数据库
        $user = User::query()->where('username',$credentials['username'])->first();
        //验证密码
        if(!password_verify($credentials['password'],$user->password)){
            return $this->error(BusinessCode::PASSWORD_ERROR);
        }
        $token = auth('api')->login($user);
//        if (!$token = auth('api')->attempt($credentials)) {
//            return $this->error(BusinessCode::LOGIN_FAIL);
//        }
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
