<?php
class live_file{
	public static function listUploadFile($uid,$plan_id){
		$ret = utility_services::call("/live/file/listUpload/$uid/$plan_id");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getUploadFile($file_id){
		$ret = utility_services::call("/live/file/getUpload/$file_id");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function setUploadFileStatus($file_id){
		$params=array("status"=>-1);
		$ret = utility_services::call("/live/file/setUpload/$file_id",$params);
		if(isset($ret->result->code) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function addUploadTask($user_id,$plan_id,$type="UPLOAD"){
		$params=array("type"=>$type);
		$ret = utility_services::call("/live/file/addUploadTask/$user_id/$plan_id",$params);
		if(isset($ret->result->code) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function getUploadTask($user_id,$plan_id){
		$ret = utility_services::call("/live/file/getUploadTask/$user_id/$plan_id");
		if(isset($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getUploadTasks($params){
		$ret = utility_services::call("/live/file/getUploadTasks",$params);
	
		if(isset($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function setUploadTask($task_id,$type="UPLOAD"){
		$params=array("status"=>0,"type"=>$type);
		$ret = utility_services::call("/live/file/setUploadTask/$task_id",$params);
	
		if(isset($ret->result->code) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function listRecordFile($uid,$plan_id){
		$ret = utility_services::call("/live/file/listRecord/$uid/$plan_id");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getRecordFile($file_id){
		$ret = utility_services::call("/live/file/Record/$file_id");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
}
