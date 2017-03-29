<?php
/**
  * 2014/12/20 by hetao
  * 文件上传的类,上传到weedfs
  */
 
class utility_feestatus{
	public static function feestatus($course_id,$user_id){
		$baoming = false;
		$reg_info = course_api::listRegistration(array("course_id"=>$course_id,"uid"=>$user_id));
		if(!empty($reg_info->data)){
			$baoming = true;
		}
		return $baoming;
	}
	/**
	 * 把这整数变成 MM:SS 格式
	 */
}
