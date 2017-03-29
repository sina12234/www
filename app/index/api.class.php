<?php
class index_api{
	public static function setResult($data = '', $code = 0, $msg = ''){
		$ret = new stdclass;
		$ret->result = new stdclass;
		$ret->result->code = $code;
		$ret->result->data = $data;
		$ret->result->msg = $msg;
		return $ret;
	}
	public static function getPlatformBanner($type){
		$url = '/platform/banner/getShowBannerByType';
		$params = new stdclass;
		$params->type = $type;
		$ret = utility_services::call($url,$params);
		if(empty($ret->code)){
			return $ret->result;
		}else{
			return false;
		}
	}


	public static function getTeacherClassByUidArr($tid_arr){

		$url = '/course/class/getTeacherClassByUidArr';
		return utility_services::call($url,$tid_arr);

	}


	public static function getTeacherScoreByTidArr($tid_arr){

		$url = '/message/score/getTeacherScoreByTidArr';
		return utility_services::call($url,$tid_arr);

	}
	public static function getBlockInfo(){

		$url = '/platform/setting/getBlockInfo/';
		return utility_services::call($url);

	}
	public static function getOrgSubdomainName($fk_user){
		$url = '/user/organization/getOrgSubdomainName/'.$fk_user;
		return utility_services::call($url);
	}
	public static function getCourseFirstCateInfo($level){
		$url = '/user/organization/getCourseFirstCateInfo/';
		return utility_services::call($url,$level);
	}
	public static function GetPlatformSetPlan(){
		$res = utility_services::call("/platform/setting/GetPlatformSetPlan");
		if($res->code==0){
			return $res->data;
		}
		return false;
		
	}
	public static function AddPointPlan($params){
        $res = utility_services::call('course/plan/AddPointPlan/', $params);
		if($res->code ==0 && !empty($res->result)){
			return true;
		}
		return false;
    }
	public static function getPointPlanOneInfoById($params){
		$url = '/course/plan/GetPointPlanOneInfoById/';
		$res = utility_services::call($url,$params);
		if(!empty($res->result)){
			return $res->result;
		}else{
			return false;
		}
	}
}
