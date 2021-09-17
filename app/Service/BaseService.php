<?php

namespace App\Service;

class BaseService
{

    /**
     * 格式化字符串数据
     * @param $data
     * @return array
     */
    protected function formatClientData($data)
    {
        $strData = str_replace(']', '', str_replace('[', '', $data));
        $arrData = explode(',', $strData);
        $md5 = substr($arrData[0], 0, 31);
        $clientInfo = substr($arrData[0], 32);
        //去除第一个元素，剩下的是数据
        array_shift($arrData);
        $arrClientInfo = explode('*', $clientInfo);
        return [
            'md5' => $md5,
            'company_code' => $arrClientInfo[0],
            'device_imei' => $arrClientInfo[1],
            'content_length' => $arrClientInfo[2],
            'data_type' => $arrClientInfo[3],
            'str_info' => $clientInfo,
            'data' =>$arrData
        ];

    }

    /**
     * 验签
     * @param array $arr
     * @return bool
     */
    protected function signMd5(array $arr){

        $secret = config('lantian_secret');

        return $arr['md5'] == md5($arr['str_info'].$secret);
    }

}
