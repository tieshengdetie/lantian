<?php
declare(strict_types=1);


namespace App\Controller\AppApi;

use App\Controller\AbstractController;
use Hyperf\Di\Annotation\Inject;

class EchartsDataController extends AbstractController{


    public function getEchartsData(){


        return $this->success();
    }
}