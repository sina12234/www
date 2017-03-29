<?php
class user_course extends STpl{
	var $user;
	function __construct(){
		//如果没有登陆到登陆界面
		$this->user = user_api::loginedUser();
		if(empty($this->user)){
			if(!empty($_SERVER['REQUEST_URI'])){
				$this->redirect("/site.main.login?url=".$_SERVER['REQUEST_URI']);
			}else{
				$this->redirect("/site.main.login");
			}
		}
		//TODO判断用户是不是教师，如果不是，退出
	}
	public function pageList($inPath){
		$uid = $this->user['uid']; 
		$reg_info = course_api::listRegistration(array("uid"=>$uid));
		if(!empty($reg_info->data)){
			foreach($reg_info->data as $k=>$v){
				//获取班级信息
				$class_info[$k] = course_api::getClass($v->class_id);
				//计算本课程章节个数
				$sec_info = course_api::listSection($v->cid);
				$count_sec = count($sec_info);
				//获取本班级所在的课程信息
				$class_info[$k]->course = course_api::getcourseone($v->cid);
				$class_info[$k]->count_sec = $count_sec;
			}
			$this->assign("class_info",$class_info);
		}
		return $this->render("user/student.course.list.html");
	}
	public function pageStudentInfo($inPath){
		if(!empty($_POST)){
			$r = user_api::setStudentProfile($this->user['uid'],$_POST);
			if($r){
				$this->assign("error","修改成功");
			}
		}
		//$uid = array();
		//$uid['uid'] = $this->user['uid'];
		//获取报名信息
		//$arr_regis = $uid;
		//$list_reg_info = course_api::listRegistration($arr_regis);
		//$count_regis = count($list_reg_info);
		////获取喜欢课程信息
		//$arr_fav = $uid;
		//$list_fav_info = user_api::listfav($arr_fav);
		//$count_fav = count($list_fav_info);
		//print_r($count_fav);
		$student = user_api::getStudentProfile($this->user['uid']);
		$this->assign("grades",course_grade::$data);
		$level0 = region_api::listRegion(0);
		$this->assign("student",$student);
		$this->assign("level0",$level0);
		return $this->render("user/student.info.html");
	}
	public function pageListRegion($inPath){
		if(isset($inPath[3])){
			return region_api::listRegion($parent_region_id = $inPath[3]);
		}
	}
	public function pageListSchool($inPath){
		$school = region_api::ListSchool($_GET['region_id'],$_GET['school_type']);
		if($school)return $school;
		return array();
	}
	public function pageFav($inPath){
		$uid = $this->user['uid']; 
		$fav_info = user_api::listfav(array("uid"=>$uid));
		if(!empty($fav_info)){
			foreach($fav_info as $k=>$v){
				//	计算章节
				$sec_info = course_api::listSection($v->course_id);
				$count_sec = count($sec_info);
				//获取课程信息	
				//	$cou_info[$k]->course= course_api::getcourseone($v->course_id);
				$cou_info[$k]= course_api::getcourseone($v->course_id);
				$cou_info[$k]->count_sec = $count_sec;
			}

		$this->assign("cou_info",$cou_info);
		}
		//	print_r($cou_info);
		return $this->render("user/student.course.fav.html");
	}
	public function pagedelFeeAjax($inPath){
		$order_id = (int)$_REQUEST["order_id"];
		$ret_set_fee = order_api::updateOrder($order_id, array('status'=>'deleted'));

		//如果这个course_id是属于这个org的就可以删除
		//	$result->error=$sid;//这样返回
		//	return $result;

		$result=new stdclass;
		if($ret_set_fee){
			$result->status="Success!";
			return $result;
		}else{
			$result->error=$ret_set_fee->result->msg;
			//	$result->error=$sec_ret;
			return $result;
		}
	}
	public function pagecancelFeeAjax($inPath){
		$order_id = (int)$_REQUEST["order_id"];
		$ret_set_fee = order_api::updateOrder($order_id, array('status'=>'cancel'));

		//如果这个course_id是属于这个org的就可以删除
		//	$result->error=$sid;//这样返回
		//	return $result;

		$result=new stdclass;
		if($ret_set_fee){
			$result->status="Success!";
			return $result;
		}else{
			$result->error=$ret_set_fee->result->msg;
			//	$result->error=$sec_ret;
			return $result;
		}
	/*	if($ret_course->user_id == $uid){
			$sec_ret = course_api::delSection($sid,$cid);
		}
	 */
		//	$result->error="添加失败";
	}

}
