<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 01.04.2016
 * Time: 17:20
 */
class RussianPostCalc
{
    function _russianpostcalc_api_communicate($request)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://russianpostcalc.ru/api_beta_077.php");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);

        curl_close($curl);
        if($data === false)
        {
            return "10000 server error";
        }

        $js = json_decode($data, $assoc=true);
        return $js;
    }

    function russianpostcalc_api_calc($apikey, $password, $from_index, $to_index, $weight, $ob_cennost_rub)
    {
        $request = array("apikey"=>$apikey,
            "method"=>"calc",
            "from_index"=>$from_index,
            "to_index"=>$to_index,
            "weight"=>$weight,
            "ob_cennost_rub"=>$ob_cennost_rub
        );

        if ($password != "")
        {
            //���� ������ ������, �������������� �� ������ API ���� + API ������.
            $all_to_md5 = $request;
            $all_to_md5[] = $password;
            $hash = md5(implode("|", $all_to_md5));
            $request["hash"] = $hash;
        }

        $ret = $this->_russianpostcalc_api_communicate($request);

        return $ret;
    }
}