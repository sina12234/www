<?php
/**
 * 环信接口
 */
class interface_easemob_api{
    private $clientId;
    private $clientSecret;
    private $orgName;
    private $appName;
    private $url;

    public function __construct(){
        $conf = SConfig::getConfig(ROOT_CONFIG . '/secretkey.conf', 'easemob');
        $this->clientId     = $conf->client_id;
        $this->clientSecret = $conf->client_secret;
        $this->orgName      = $conf->org_name;
        $this->appName      = $conf->app_name;
        if (!empty($this->orgName) && !empty($this->appName)) {
            $this->url = "https://a1.easemob.com/" . $this->orgName . '/' . $this->appName . '/';
        }
    }

    /**
     * 授权注册
     * @param string $userName 用户名
     * @param string $password 密码
     * @return bool
     */
    public static function createUser($userName, $password){
        if(empty($userName) || empty($password)) return false;

        $url     = $this->url.'users';
        $options = [
            'username' => $userName,
            'password' => $password
        ];
        $body   = json_encode($options);
        $header = array($this->getToken());
        $result = $this->postCurl($url, $body, $header);

        //记录错误日志
        if(!empty($result['error'])){
            SLog::fatal('hx error[%s]', var_export($result), 1);
            return false;
        }

        return true;
    }

    private static function getToken(){
        $options = [
            'grant_type'    => 'client_credentials',
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret
        ];
        $body = json_encode($options);
        $url  = $this->url.'token';
        $tokenResult = $this->postCurl($url, $body);

        return "Authorization:Bearer ".$tokenResult['access_token'];
    }

    private static function postCurl($url, $body, $header=array(), $type="POST"){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置为false,只会获得响应的正文(true的话会连响应头一并获取到)
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //设置超时限制防止死循环
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        //设置发起链接前的等待时间,如果设置为0,则无限等待
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //将curl_exec()获取的信息以文件流的形式返回,而不是直接输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if(count($body) > 0){
            //全部数据使用http协议中的post操作发送
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        if(count($header) > 0){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        //上传文件相关设置
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        //对认证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //从证书中检查ssl加密算
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        //支持提交方式
        switch($type){
            case "GET":
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case "POST":
                curl_setopt($ch, CURLOPT_POST, true);
                break;
        }

        //在http请求中包含一个"User-Agent: "头的字符串
        curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        //模拟用户使用的浏览器
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)');

        $res = curl_exec($ch);
        $result = json_decode($res, true);
        curl_close($ch);
        if(empty($result)){
            return $res;
        }else{
            return $result;
        }
    }
}
