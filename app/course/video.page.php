<?php
class course_video extends STpl{
	function __construct(){
	}
	function pageEntry($inPath){
		$page = 1;
		$size = 20;
		$grade_id= 0;
		if(!empty($_REQUEST['grade_id'])){
			$grade_id= $_REQUEST['grade_id'];
		}
		$org=user_organization::subdomain();
		$oid = $org->userId;
		$shelf = 1;
		$courselist = course_api::getFinishedCourselist($grade_id,$oid,$shelf,$page,$size);
		$this->assign("grade_id",$grade_id);
		$this->assign("courselist",$courselist);
		return $this->render("course/video.html");
	}

}
