<?php

require_once(ROOT.'/vendor/autoload.php');
use UAParser\Parser;

class log_userAgent
{
    public static function addUserAgentInfo($data)
    {
        if (!isset($data['orderId']) || !(int)($data['orderId']))
            return false;

        $status = $payType = $source = 0;
        isset($data['status']) && (int)$data['status'] && $status = $data['status'];
        isset($data['payType']) && (int)$data['payType'] && $payType = $data['payType'];
        isset($data['source']) && (int)$data['source'] && $source = $data['source'];

        $ua = $_SERVER['HTTP_USER_AGENT'];
        $parser = Parser::create();
        $res = $parser->parse($ua);

        $params = [
            'orderId'        => $data['orderId'],
            'browser'        => $res->ua->family,
            'browserVersion' => $res->ua->major,
            'system'         => $res->os->family,
            'device'         => $res->device->family,
            'userAgent'      => $res->originalUserAgent,
            'payType'        => $payType,
            'status'         => $status,
            'source'         => $source
        ];

        $res = utility_services::call("/log/log/AddUserAgentInfo", $params);

        return !$res->code;
    }
}
