<?php
declare(strict_types=1);


namespace App\Controller\AppApi;

use App\Controller\AbstractController;
use Hyperf\Di\Annotation\Inject;
use \Phper666\JwtAuth\Jwt;

class AuthController extends AbstractController{

    /**
     * @Inject()
     * @var Jwt
     */
    protected $jwt;
    public function login(){

        $username = $this->request->input('username');
        $password = $this->request->input('password');

        if ($username && $password) {
            $userData = [
                'uid' => 1, // 如果使用单点登录，必须存在配置文件中的sso_key的值，一般设置为用户的id
                'username' => 'xx',
            ];
            $token = $this->jwt->getToken($userData);
            $data = [
                'token' => (string)$token,
                'exp' => $this->jwt->getTTL(),
            ];
            return $this->success($data);
        }
        return $this->fail('登录失败');

    }

    public function refeshToken(){

        $token = $this->jwt->refreshToken();
        $data = [
            'token' => (string)$token,
            'exp' => $this->jwt->getTTL(),
        ];
        return $this->success($data);

    }

    public function logout()
    {
        $this->jwt->logout();
        return $this->success();
    }
    /**
     * 用户信息
     */
    public function getInfoByLoginUserId()
    {
        $sys_user = $this->request->getAttribute("user");
        $select = [
            'user_id as userId',
            'username',
            'password',
            'salt',
            'email',
            'mobile',
            'status',
            'create_user_id as createUserId',
            'create_time as createTime'
        ];
        $data = $this->sysUserService->findForSelect($sys_user['user_id'], $select);
        return $this->success([
            'user' => $data
        ]);
    }

}