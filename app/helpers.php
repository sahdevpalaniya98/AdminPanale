<?php

if (!function_exists('base64Encode')) {
    function base64Encode($id)
    {
        if ($id) {
            $base = base64_encode($id);
            $base = str_replace('=', '__', $base);
            $base = str_replace('+', '-', $base);
            $base = str_replace('/', '--', $base);
            $base = '__' . $base;
            return $base;
        } else {
            return '';
        }
    }
}

if (!function_exists('base64Decode')) {
    function base64Decode($id)
    {
        if ($id) {
            $id = ltrim($id, '__');
            $id = str_replace('__', '=', $id);
            $id = str_replace('--', '/', $id);
            $id = str_replace('-', '+', $id);
            $id = base64_decode($id);
            return $id;
        } else {
            return '';
        }
    }
}

if (!function_exists('generateFileName')) {
    function generateFileName($extension)
    {
        return date("YmdHis") . md5(time() . rand(1111, 9999)) . '.' . $extension;
    }
}

if (!function_exists('spaceOutString')) {
    function spaceOutString($str)
    {
        return str_replace(' ', '', trim($str));
    }
}

if (!function_exists('priceFormat')) {
    function priceFormat($price)
    {
        return number_format((float)$price, 2, ".", "");
    }
}

if (!function_exists('sickwImeiData')) {
    function sickwImeiData($imei,$brand = 0)
    {
        $service = '';
        if($brand == 1)
        {
            $service =  env('SICKW_IPHONE_SERVICE'); //"1"; // APi Service iD
        }else if($brand == 2){
            $service = env('SICKW_SAMSUNG_SERVICE'); //"61"; // APi Service iD
        }
        $format = "json"; // $format = "html"; display result in JSON or HTML format
        // $imei = $imei; //'353009110641259'; // IMEI or SERIAL Number
        $api = "6TZ-W1P-RZ5-9CR-62L-95S-MAO-8EJ"; // env('SICKW_API_KEY'); //"6TZ-W1P-RZ5-9CR-62L-95S-MAO-8EJ"; // APi Key

        $curl = curl_init ("https://sickw.com/api.php?format=$format&key=$api&imei=$imei&service=$service");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        $result = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($result);
        if ($data->status !== 'success') {
            throw new Exception($data->result);
        }
        return $data;
    }
}

if(! function_exists('get_sports')){
    function get_sports($id=''){
        $array      = array(
            array(
                "id"            => 1,
                "text"          => "Sport 1"
            ),
            array(
                "id"            => 2,
                "text"          => "Sport 2"
            ),
            array(
                "id"            => 3,
                "text"          => "Sport 3"
            ),
            array(
                "id"            => 4,
                "text"          => "Sport 4"
            ),
            array(
                "id"            => 5,
                "text"          => "Sport 5"
            )
        );
        if($id!=''){
            $key    = array_search($id, array_column($array, 'id'));
            if ($key!==false) {
                return $array[$key];
            }else{
                return [];
            }
        }else{
            return $array;
        }
    }
}
