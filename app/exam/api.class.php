<?php
class exam_api{
	public static function genQuestionId($uid){
		$ret = utility_services::call("/exam/question/genquestionid/$uid");
		if(!empty($ret->result) && $ret->result->code==0){
			return $ret->data->qid;
		}else{
			return false;
		}
	}
	public static function updateQuestion($qid,$data){
		$params = new stdclass;
		$arrkeys = array(
			"uid"=>"uid",
			"type"=>"type",
			"subject_id"=>"subject_id",
			"grade_id"=>"grade_id",
			"desc"=>"desc",
			"desc_img"=>"desc_img",
			"result"=>"result",
			"mode"=>"mode",
			"status"=>"status",
		);
		foreach($arrkeys as $arrk=>$arrv){
			if(isset($data[$arrk])){ 
				$params->$arrk = $data[$arrk];
			}
		}
		$ret = utility_services::call("/exam/question/update/$qid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}else{
			return false;
		}
	}
	public static function addAnswer($data){
		$params = new stdclass;
		$arrkeys = array(
			"question_id"=>"question_id",
			"correct"=>"correct",
			"desc"=>"desc",
			"desc_img"=>"desc_img",
		);
		foreach($arrkeys as $arrk=>$arrv){
			if(isset($data[$arrk])){ 
				$params->$arrk = $data[$arrk];
			}
		}
		$ret = utility_services::call("/exam/answer/addanswer",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return $ret->data;
		}else{
			return false;
		}
	}
	public static function logIssue($data, $user_id){
		if(empty($data["plan_id"]) || empty($data["question_id"]) || empty($data["data"])){
			return false;
		}
		$params = new stdclass;
		$params->plan_id = $data["plan_id"];
		$params->question_id = $data["question_id"];
		$params->data = $data["data"];
		$params->user_id = $user_id;
		$ret = utility_services::call("/exam/log/logissue",$params);
		if(0 == $ret->result->code){
			return true;
		}else{
			return false;
		}
	}
    /* 备课出题 */
	public static function logUserQues($data, $user_id){
		if(empty($data["plan_id"]) || empty($user_id)){
			return false;
		}
		$params = new stdclass;
		$params->plan_id = $data["plan_id"];
		$params->user_id = $user_id;
		$ret = utility_services::call("/exam/log/loguserques",$params);
		if(0 == $ret->result->code){
			return $ret;
		}else{
			return false;
		}
	}

    /* 快速出题 */
	public static function logUserQuesQuick($data, $user_id){
		if(empty($data["plan_id"]) || empty($user_id)){
			return false;
		}
		$params = new stdclass;
		$params->plan_id = $data["plan_id"];
		$params->user_id = $user_id;
		$ret = utility_services::call("/exam/log/LogUserQuesQuick",$params);
		if(0 == $ret->result->code){
			return $ret;
		}else{
			return false;
		}
	}
    
	public static function getUserRightAnswerCountByPidArr($uid,$pid_arr){
        return utility_services::call('/exam/question/getUserRightAnswerCountByPidArr/'.$uid, $pid_arr);
	}

	public static function getLogUserQuestionByPid($uid=0,$pid){
        return utility_services::call('/exam/question/getLogUserQuestionByPid/'.$uid, $pid);
	}





}
