<?php


namespace App\Services\Self;


abstract class BaseTask
{
    /**
     * @param $url
     * @param  string  $method
     *
     * @return mixed
     */
    public function request($url, $method = 'GET')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Born');

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}