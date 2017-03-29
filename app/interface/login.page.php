<?php

class interface_login
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
        $param = SJson::decode(utility_net::getPostData(), true);

        if (empty($param)) return interface_func::setMsg(1000);

        foreach ($param as $k=>$v) {
            if (isset($this->paramsInfo[$k])) {
                $this->paramsInfo[$k] = $v;
            }
        }

        $valid = interface_func::validParam($this->paramsInfo);
        if (!empty($valid['code'])) exit(interface_func::setMsg($valid['code']));

        $this->paramsInfo = $valid;
    }


    public function pageEntry()
    {

		if(empty($this->paramsInfo['params']['name'])){
			return interface_func::setMsg(1000);
		}		
		
		$userName = utility_valid::getMobile($this->paramsInfo['params']['name']);
	
        if (!utility_valid::mobile($userName)) {
            return interface_func::setMsg(1012);
        }

        $uid = user_api::isRegister($this->paramsInfo['params']['name']);
        if ($uid === false) {
            return interface_func::setMsg(2014);
        }
		
		//用户名 密码登陆
		if(!empty($this->paramsInfo['params']['password'])){
			$res = user_api::login($userName, $this->paramsInfo['params']['password'], 1);
        }elseif(!empty($this->paramsInfo['params']['token'])){
			//token登陆
			$result = user_api::getToken($this->paramsInfo['params']['token']);
			if(empty($result) || $result->uid != $uid){
				return interface_func::setMsg(2014);
			}
			$res = user_api::loginByUid($uid);
		}
        if ($res) {
            $header = utility_net::isHTTPS() ? "https" : "http";

            $data = utility_session::get()['user'];
            $userProfileInfo = utility_services::call("/user/profile/info/{$data['uid']}");
            !empty($userProfileInfo->result->real_name) && $data['real_name'] = $userProfileInfo->result->real_name;

            $data['large'] = $header.':'.utility_session::get()['user']['large'];
            $data['medium'] = $header.':'.utility_session::get()['user']['medium'];
            $data['small'] = $header.':'.utility_session::get()['user']['small'];

            if (
                !empty($this->paramsInfo['params']['streamInfo']) &&
                $this->paramsInfo['params']['streamInfo'] == 'yes'
            ) {
                $token = live_auth::getPublishAuth($data['uid']);

                if (empty($token)) {
                    $ret = live_auth::setPublishAuth($data['uid']);
                    $ret && $token = live_auth::getPublishAuth($data['uid']);
                }

                $data['streamInfo'] = array(
                    'server' => $token->server,
                    'stream' => $token->stream_name
                );

                $data['host'] = $header.'://'.$_SERVER['HTTP_HOST'];
                $data['imgHost'] = $header . ':'.utility_cdn::file('');
            }

            return interface_func::setData($data);
        }

        return interface_func::setMsg(1038);
    }
	
	public function pageLogOut()
	{
		if(user_api::logout()){
			return interface_func::setMsg(1047);
		}else{
			return interface_func::setMsg(1047);
		}
	}
	
	//验证码登陆
	public function pageSmsLogin(){
		//$this->params(['moblie'=>1013,'verifyCode'=>1000]);
		$mobile     = utility_valid::getMobile(intval($this->paramsInfo['params']['mobile']));
		$verifyCode = trim($this->paramsInfo['params']['verifyCode']);
		$oid = empty($this->paramsInfo['oid']) ? 0 : intval($this->paramsInfo['oid']);
		
		//检查验证码是否正确
		$checkVerify = user_organization::getUserVerifyCodeLoginSms(array('mobile'=>$mobile,'code'=>$verifyCode));
		if($checkVerify === false) return interface_func::setMsg(1049);
		
		//检查是否注册用户
		$newUser = 0;
		$uid = user_api::isRegister($mobile);
		if($uid == false){
			//注册
			$company = "云课网";
			if(!empty($oid)){
				$orgInfo = user_organization::getOrgInfoByOidArr(array($oid));
				if(empty($orgInfo)) return $this->setMsg(1000);
				$orgInfo=$orgInfo[0]; 
				$company = $orgInfo->name;
			}
			$password = substr($mobile, 5, 6);
			$now_time = time();
			$name = "用户".substr($mobile, 0, 4).substr($now_time,-5);	
			$uid = user_api::registerByMobile($name, $mobile, $password, 0, $ret);
			if(!empty($uid)){
				//注册成功发短信
				$msg = "#orgname#=$company&#code#=$password";
				$tpl_id = 1503814;
				$r = verify_api::sendSMS($mobile,$msg,$tpl_id);
			}
			$newUser = 1;
		}
		
		$res = user_api::loginByUid($uid);
		if(!$res) return interface_func::setMsg(1039);
		
		$header = utility_net::isHTTPS() ? "https" : "http";
		$data = utility_session::get()['user'];
		$userProfileInfo = utility_services::call("/user/profile/info/{$data['uid']}");
        !empty($userProfileInfo->result->real_name) && $data['real_name'] = $userProfileInfo->result->real_name;
        $data['large'] = $header.':'.utility_session::get()['user']['large'];
        $data['medium'] = $header.':'.utility_session::get()['user']['medium'];
        $data['small'] = $header.':'.utility_session::get()['user']['small'];
		$data['newUser'] = $newUser;
		
		return interface_func::setData($data);
	}

    public function pageCheckToken(){
        $token  = !empty($this->paramsInfo['params']['token']) ? $this->paramsInfo['params']['token'] : '';
        $userId = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;
        if(empty($token) || empty($userId)) return interface_func::setMsg(1000);

        $result = user_api::getToken($token);
        if(empty($result) || $result->uid != $userId){
            return interface_func::setMsg(1);
        }

        return interface_func::setMsg(0);
    }
}
