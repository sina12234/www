<?php

class interface_func
{
    public static function validParam($param)
    {
        /*
        $param = array_filter($param, function($v){
            if (empty($v)) return self::error(1001, 'missed required param');
            return $v;
        });
        */

        $tParam = json_encode($param['params'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        $conf = SConfig::getConfig(ROOT_CONFIG.'/interface.conf', 'salt');
        if (empty($conf->$param['u'])) {
            return self::error(3001, 'system salt value not set');
        }

        $tKey = md5(md5($tParam.$param['time'].$conf->$param['u']));
	//	echo "<pre>";print_r($tKey);die;
        if ($param['key'] !== $tKey) {
            SLog::debug('origin[%s],tKey[%s]', var_export($param, 1), $tKey);
            return self::error(1002, 'access key valid failed');
        }

        if (!empty($param['token'])) {
            $userInfo = user_api::getToken($param['token']);
            if (!empty($userInfo)) {
                $param['userInfo'] = array(
                    'uid' => $userInfo->uid
                );
                // 验证token和uid是否匹配，减少user token验证次数
                // 2016-04-20 切换验证方式为dinfo中的uid
                if (!empty($param['dinfo']['uid'])) {
                    if (!self::isValidUid($param['dinfo']['uid'], $userInfo->uid)) {
                        return self::error(1024, 'Is not a legitimate user');
                    }
                }
            }
        }

        // recode interface log
        $url = utility_tool::getCurUrl();
        //过滤消息轮询的日志
        if (strripos($url, 'getDialogList') === false) {
            $logData = [
                'url'        => $url,
                'type'       => $param['u'],
                'deviceInfo' => $param['dinfo']
            ];
            log_api::addInterfaceLog($logData);

            if (defined('DEVELOP')) {
                SLog::debug('request params[%s]', json_encode($param));
            }
        }

        return $param;
    }
    
    public static function setMsg($code = 3000,$errStr='')
    {
        $ret = [
            'code'    => $code,
            'message' => !empty(interface_code::$errCode['en'][$code])
                        ? interface_code::$errCode['en'][$code]
                        : interface_code::$errCode['en'][3000],
            'errMsg' => !empty(interface_code::$errCode['zh'][$code])
                        ? interface_code::$errCode['zh'][$code]
                        : SLanguage::tr(interface_code::$errCode['zh'][3000], 'code')
        ];
        if (!empty($errStr)){ $ret['errMsg'] = $errStr; }

        return json_encode($ret, JSON_UNESCAPED_UNICODE);
    }

    public static function setError($code)
    {
        return [
            'code' => $code,
            'message' => !empty(interface_code::$errCode['en'][$code])
                        ? interface_code::$errCode['en'][$code]
                        : interface_code::$errCode['en'][3000]
        ];
    }

    public static function error($code, $msg)
    {
        return [
            'code'   => $code,
            'msg'    => $msg,
            'errMsg' => $msg
        ];
    }

    public static function success($code = 0, $msg ='success')
    {
        return [
            'code' => $code,
            'msg'  => $msg,
        ];
    }

    public static function setData($data, $forceObj = false)
    {
        $ret = [
            'code'    => 0,
            'message' => 'success',
            'result'    => $data
        ];

        //exit(SJson::encode($ret));
        if ($forceObj) {
            return json_encode($ret, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT);
        }

        return json_encode($ret, JSON_UNESCAPED_UNICODE);
    }

    public static function imgUrl($fileName)
    {
        if (!$fileName) return '';
        $str = utility_cdn::file($fileName);
        if ((strpos($str, 'http://') !== false) || (strpos($str, 'https://') !== false))
            return $str;

        return self::httpHeader() .':'.utility_cdn::file($fileName);
    }

    public static function httpHeader()
    {
        return utility_net::isHTTPS() ? "https" : "http";
    }

    private static function isValidUid($params, $uid)
    {
        // 接口user id 传参不规范，兼容uid,userId
        if (empty($uid)) return false;

        if (isset($params['uid']) && $uid != $params['uid'] )
            return false;

        if (isset($params['userId']) && $uid != $params['userId'] )
            return false;

        // 兼容没有传uid,userId
        return true;
    }

    public static function isValidId($idNameArr, $params)
    {
        foreach ($idNameArr as $v) {
            if (!isset($params[$v]) || !(int)($params[$v])) {
                return self::error(1000, 'params error');
            }

            $params[$v] = (int)($params[$v]);
        }

        return $params;
    }

    public static function requestApi($url, $params=[])
    {
        $data = '';
        if (!empty($params)) {
            (is_object($params) || is_array($params)) && $data = SJson::encode($params);
        }

        $res = SHttp::post(utility_services::url("api", $url),$data,array(),false,5);

        $jsonDecode = SJson::decode($res, true);
        if (defined('DEVELOP')) {
            SLog::debug('[wen_yue_api debug info params[%s],url[%s],result[%s]]', $data, $url, $res);
        }

        if (empty($jsonDecode)) return self::error(2, 'Is not a valid JSON data format');

        if (!empty($jsonDecode['code'])) {
            $msg = '';
            !empty($jsonDecode['zh_msg']) && $msg = $jsonDecode['zh_msg'];
            !empty($jsonDecode['msg']) && $msg = $jsonDecode['msg'];

            return self::error($jsonDecode['code'], $msg);
        } else {
            return $jsonDecode;
        }
    }
}
