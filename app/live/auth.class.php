<?php
class live_auth{
	public static function getPublishAuth($uid){
		$ret = utility_services::call("/live/publish/getAuth/$uid",$params=new stdclass);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function setPublishAuth($uid){
		$params = new stdclass;
		$params->uid=$uid;
		$params->app_name="publish";
		$params->stream_name=substr(md5($uid),0,10);
		$params->token=md5($uid.rand(100000,999999));
		$ret = utility_services::call("/live/publish/setAuth/$uid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
}
