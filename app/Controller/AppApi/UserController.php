<?php
declare(strict_types=1);

namespace App\Controller\AppApi;

use App\Constants\HttpCode;
use App\Constants\BusinessCode;
use App\Controller\AbstractController;
use App\Model\User;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController extends AbstractController{


    public function add(RequestInterface $request):ResponseInterface
    {
        $credentials = $request->inputs(['username', 'password','email','phone']);
        $credentials['password'] = password_hash($credentials['password'],PASSWORD_DEFAULT);

        User::create($credentials);

        return $this->success();


    }
}