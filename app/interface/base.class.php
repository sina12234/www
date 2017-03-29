<?php

class interface_base
{
    public $paramsInfo = [
		'oid' => '',
        'u' => '',
        'v' => '',
        'time' => '',
        'params' => [],
        'key' => '',
		'dinfo' => []
    ];


    public function __construct()
    {
        $dataBody = utility_net::getPostData();
        $param = SJson::decode($dataBody, true);

        if (empty($param)) {
            SLog::debug('http body[%s]', $dataBody);
            return interface_func::setMsg(2);
        }

        foreach ($param as $k=>$v) {
            if (isset($this->paramsInfo[$k])) {
                $this->paramsInfo[$k] = $v;
            }
        }

        if (!empty($param['token'])) {
            $this->paramsInfo['token'] = $param['token'];
        }

        $valid = interface_func::validParam($this->paramsInfo);
        if (!empty($valid['code'])) {
            exit(interface_func::setMsg($valid['code']));
        }

        $this->paramsInfo = $valid;

        $this->paramsInfo['token'] = !empty($param['token']) ? $param['token'] : 'token';
    }

    public function setMsg($code)
    {
        return interface_func::setMsg($code);
    }

    public function setData($data, $forceObj = false)
    {
        if ($forceObj) {
            return interface_func::setData($data, true);
        }

        return interface_func::setData($data);
    }

    public function error($code, $msg)
    {
        return interface_func::error($code, $msg);
    }

    public function success($code = 0, $msg ='success')
    {
        return interface_func::success($code, $msg);
    }

    public function imgUrl($fileName)
    {
        return interface_func::imgUrl($fileName);
    }

    public function httpHeader()
    {
        return interface_func::httpHeader();
    }

    public function v($var)
    {
        foreach ($var as $k=>$v) {
            if (!isset($this->paramsInfo['params'][$k]) || !$this->paramsInfo['params'][$k]) exit($this->setMsg($v));
        }
    }

    public function s($var)
    {
        return isset($this->paramsInfo['params'][$var]) && $this->paramsInfo['params'][$var];
    }

    public function requestApi($url, $params)
    {
        return interface_func::requestApi($url, $params);
    }
}
