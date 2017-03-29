<?php
class user_token{
	public static function gen($uid,$platform=1,$user_status=1,$live_status=0,$token=''){
		$params=new stdclass;
		$params->uid=$uid;
		$params->platform=$platform;
		$params->token=$token;
		$params->user_status=$user_status;
		$params->live_status=$live_status;
		$params->ip=utility_ip::realIp();
		$ret = utility_services::call("/user/token/gen",$params);
		if(!empty($ret->data->token)){
			return $ret->data->token;
		}
		return false;
	}
}