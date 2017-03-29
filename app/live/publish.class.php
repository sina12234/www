<?php
class live_publish{
	public static function getByUid($uid){
		$params = array("uid"=>$uid);
		$ret = utility_services::call("/live/publish/get/",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getByPlanId($plan_id){
		$params = array("plan_id"=>$plan_id);
		$ret = utility_services::call("/live/publish/get/",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function setPlan($uid,$token,$plan_id,$cleanFile=false){
		$params = array("cleanFile"=>$cleanFile);
		$ret = utility_services::call("/live/publish/setPlan/$uid/$token/$plan_id",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function close($uid){
		$ret = utility_services::call("/live/publish/close/$uid");
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function closeChat($uid,$planid){
		$ret = utility_services::call("/live/publish/closeChat/$uid/$planid");
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function allowChat($uid,$planid){
		$ret = utility_services::call("/live/publish/allowChat/$uid/$planid");
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
}
