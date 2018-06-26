<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('request_api_server')) {
    function request_api_server($method, $url, $data, $timeout = 30)
    {
        $protocol = 'https://';
        $contentTypeHeader= 'Content-Type: application/json';
        $accessKeyHeader= 'X-Cloudbric-Key: ';

        $ch = curl_init();
        $curlOpt = [
            CURLOPT_URL => $protocol . CLOUDBRIC_API_SERVER . $url,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array($contentTypeHeader, $accessKeyHeader),
            CURLOPT_RETURNTRANSFER => true,
        ];

        // 개인 개발 환경에서는 Self-sign 인증서를 사용하고 있어 인증서 체크 옵션을 끄도록 한다.
        //
        if (ENVIRONMENT !== 'production') {
            $curlOpt[CURLOPT_SSL_VERIFYPEER] = false;
        }

        if ($timeout != null && is_int($timeout)) {
            $curlOpt[CURLOPT_TIMEOUT] = $timeout;
        }

        if (isset($data) && !empty($data)) {
            $curlOpt[CURLOPT_POSTFIELDS] = $data;
        }

        curl_setopt_array($ch, $curlOpt);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}

function request_cc_server($url, $port, $data, $timeout = 30)
{
    $protocol = 'http://';

    $ch = curl_init();
    $curlOpt = [
        CURLOPT_URL => $protocol . CLOUDBRIC_CC_SERVER . ':' . $port . $url,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_RETURNTRANSFER => true,
    ];

    if ($timeout != null && is_int($timeout)) {
        $curlOpt[CURLOPT_TIMEOUT] = $timeout;
    }

    if (isset($data) && !empty($data)) {
        $curlOpt[CURLOPT_POSTFIELDS] = $data;
    }

    curl_setopt_array($ch, $curlOpt);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}