<?php

namespace App\Services;


class RestService
{

    public function send_callback_request(string $path, array $data)
    {
        $data_string = json_encode($data);
        // options
        $options = [
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_HEADER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array(
                'Content-Type:application/json',
                'Content-Length: ' . strlen($data_string),
                'Cookie: XDEBUG_SESSION=XDEBUG_ECLIPSE;'
            ),
            CURLOPT_TIMEOUT => 30,
            CURLOPT_URL => env('APP_URL') . $path
        ];
        // output
        return json_decode($this->rest_curl($options), true);
    }

    // SERVICE

    private function rest_curl($options)
    {
        // curl
        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $result = curl_exec($curl);
        curl_close($curl);
        // output
        return $result;
    }
}
