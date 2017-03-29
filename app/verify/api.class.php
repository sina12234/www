<?php
class verify_api{
	public static function sendSMS($mobile,$msg,$tpl_id,&$ret=""){
		$params=new stdclass;
		$params->mobile=$mobile;
		$params->msg=$msg;
		$params->tpl_id=$tpl_id;
		$params->sender_ip=utility_ip::realIp();
		$ret = utility_services::call("/verify/send/mobile",$params);
		error_log("sendSMS".var_export($ret,true)."\n", 3, "/tmp/fanfan.log_");
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function sendMobileRegisterSMS($Mobile,$Name,$company="云课网"){
		//$msg = "您好，恭喜您注册成为高能壹佰的会员";
		$msg = "#name#=$Name&#company#=$company";
		$tpl_id = 630841;
		return self::sendSMS($Mobile,$msg,$tpl_id);
	}
	public static function sendUserOfMobileAndPasswordSMS($Mobile,$password='',$company="云课网"){
		//$msg = "您好，恭喜您注册成为高能壹佰的会员,密码为";
		$msg = "#orgname#=$company&#code#=$password";
		$tpl_id = 1503814;
		return self::sendSMS($Mobile,$msg,$tpl_id);
	}
	/**
	 * 发送手机验证码
	 * 机构添加老师用
	 */
	public static function sendMobileAddTeacher($Mobile,$org,&$ret=""){
		$code = self::genCode();
		$msg = "#org#=\"$org\"&#code#=$code";
		$tpl_id = 667231;
		return self::sendSMS($Mobile,$msg,$tpl_id,$ret);
	}
	public static function sendMobileAddTeacherOk($Mobile,$org,$domain,&$ret=""){
		$msg = "#org#=\"$org\"&#domain#=$domain";
		$tpl_id = 667285;
		return self::sendSMS($Mobile,$msg,$tpl_id,$ret);
	}
	public static function sendMobileAddTeacherOkv2($Mobile,$pass,$org,$domain,&$ret=""){
		$msg = "#org#=\"$org\"&#pwd#=$pass&#domain#=$domain";
		$tpl_id = 834483;
		return self::sendSMS($Mobile,$msg,$tpl_id,$ret);
	}
	/**
	  * 发送手机验证码，需要防刷
	  **/
	public static function sendMobileCode($Mobile,&$ret=""){
		$code = self::genCode();
		//$msg = "您好，您的手机号 $Mobile 在高能壹佰的验证码是 $code";
		$msg = "#code#=$code";
		if($Mobile{0}=="+"){
			$tpl_id = 1392965;
		}else{
			$tpl_id = 630839;
		}
		return self::sendSMS($Mobile,$msg,$tpl_id,$ret);
	}
    /**
	  * 发送手机动态登录密码
	  **/
	public static function sendMobileSmsLogin($Mobile,&$ret=""){
		$code = self::genCodeLogin();
		//$msg = "您好，您的手机号 $Mobile 在高能壹佰的验证码是 $code";
		$msg = "#code#=$code";
		$tpl_id = 1286745;
		return self::sendSMS($Mobile,$msg,$tpl_id,$ret);
	}
	/**
	  * 发送手机语音验证码，需要防刷
	  **/
	public static function sendMobileVoice($Mobile,&$ret=""){
		return false;
		$code = self::genCode();
		$params=new stdclass;
		$params->mobile=$Mobile;
		$params->code=$code;
		$params->sender_ip=utility_ip::realIp();
		$ret = utility_services::call("/verify/send/voice",$params);
		error_log("sendSMS".var_export($ret,true)."\n", 3, "/tmp/fanfan.log_");
		
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	/**
	  * TODO
	  **/
	public static function sendEmailCode($msg,$UserID){
		
	}
	public static function verifyMobile($mobile,$verifycode){
		$params=new stdclass;
		$params->mobile=$mobile;
		$params->code=$verifycode;
		$ret = utility_services::call("/verify/check/mobile",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	/**
	  * TODO
	  **/
	public static function verifyEmail($mobileno,$verifycode){
	}
	public static function genCode(){
		return rand(1000,9999);
	}
	//动态登录生成
	public static function genCodeLogin(){
		return rand(100000,999999);
	}
}
