<?php
class course_fee extends STpl{
	function __construct(){
	}
	function pageEntry($inPath){
		$page = 1;
		$size = 20;
		$feetype= "fee";
		$grade_id= 0;
		if(!empty($_REQUEST['grade_id'])){
			$grade_id= $_REQUEST['grade_id'];
		}
		$org=user_organization::subdomain();
		$oid = $org->userId;
		$shelf = 1;
		$courselist = course_api::getcourselist($page,$size,$feetype,$grade_id,'',$oid,$shelf);
		$this->assign("grade_id",$grade_id);
		$this->assign("courselist",$courselist);
		return $this->render("course/fee.html");
	}

}
