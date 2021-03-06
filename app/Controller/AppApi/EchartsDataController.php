<?php
declare(strict_types=1);


namespace App\Controller\AppApi;

use App\Controller\AbstractController;

class EchartsDataController extends AbstractController{


    public function getEchartsData(){

        $user = auth()->user();
        return $this->success($user);
    }
}