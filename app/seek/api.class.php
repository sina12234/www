<?php
class seek_api{
	public static function seekCourse($data){
		$params=new stdclass;
		$params->f=new stdclass;
		$params->q=new stdclass;
		$params->ob=new stdclass;
		if(isset($data["f"]))
			$params->f = $data["f"];
		if(isset($data["q"]))
			$params->q = $data["q"];
		if(isset($data["ob"]))
			$params->ob = $data["ob"];
		if(isset($data["p"])){
			$params->p = $data["p"];
		}else{
			$params->p = "1";
		}
		if(isset($data["pl"])){
			$params->pl = $data["pl"];
		}else{
			$params->pl = "20";
		}
		$ret = utility_services::call("/seek/course/list/",$params);
		return $ret;
	}
	public static function seekPlan($data){
		$params=new stdclass;
		$params->f=new stdclass;
		$params->q=new stdclass;
		$params->ob=new stdclass;
		if(isset($data["f"]))
			$params->f = $data["f"];
		if(isset($data["q"]))
			$params->q = $data["q"];
		if(isset($data["ob"]))
			$params->ob = $data["ob"];
		if(isset($data["p"])){
			$params->p = $data["p"];
		}else{
			$params->p = "1";
		}
		if(isset($data["pl"])){
			$params->pl = $data["pl"];
		}else{
			$params->pl = "20";
		}
		$ret = utility_services::call("/seek/plan/list/",$params);
		return $ret;
	}
	public static function seekTeacher($data){
		$params=new stdclass;
		$params->f=new stdclass;
		$params->q=new stdclass;
		$params->ob=new stdclass;
		if(isset($data["f"]))
			$params->f = $data["f"];
		if(isset($data["q"]))
			$params->q = $data["q"];
		if(isset($data["ob"]))
			$params->ob = $data["ob"];
		if(isset($data["p"])){
			$params->p = $data["p"];
		}else{
			$params->p = "1";
		}
		if(isset($data["pl"])){
			$params->pl = $data["pl"];
		}else{
			$params->pl = "20";
		}
		$ret = utility_services::call("/seek/teacher/list/",$params);
		return $ret;
	}
	public static function seekQuestion($data){
		$params=new stdclass;
		$params->f=new stdclass;
		$params->q=new stdclass;
		$params->ob=new stdclass;
		if(isset($data["f"]))
			$params->f = $data["f"];
		if(isset($data["q"]))
			$params->q = $data["q"];
		if(isset($data["ob"]))
			$params->ob = $data["ob"];
		if(isset($data["p"])){
			$params->p = $data["p"];
		}else{
			$params->p = "1";
		}
		if(isset($data["pl"])){
			$params->pl = $data["pl"];
		}else{
			$params->pl = "20";
		}
		$ret = utility_services::call("/seek/question/list/",$params);
		return $ret;
	}
	public static function seekOrg($data){
		$params=new stdclass;
		$params->f=new stdclass;
		$params->q=new stdclass;
		$params->ob=new stdclass;
		if(isset($data["f"]))
			$params->f = $data["f"];
		if(isset($data["q"]))
			$params->q = $data["q"];
		if(isset($data["ob"]))
			$params->ob = $data["ob"];
		if(isset($data["p"])){
			$params->p = $data["p"];
		}else{
			$params->p = "1";
		}
		if(isset($data["pl"])){
			$params->pl = $data["pl"];
		}else{
			$params->pl = "20";
		}
		$ret = utility_services::call("/seek/organization/list/",$params);
		return $ret;
	}
}
