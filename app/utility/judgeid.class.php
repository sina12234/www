<?php
/**
  *  by fanbin
  *  判断权限方法
  */
 
class utility_judgeid{


	/*  TODO  this
	 *  判断当前课程是否属于该机构
	 *  判断当前用户是否属于该机构
	 *  $course_id  
	 *  $user_id    当前登录用户id
	 *	$orgOwnerid 是机构所有者id
	 */
	public static function courseid($course_id=null,$user_id=null,$orgOwnerid=null){
		$ret = course_api::getCourseOne($course_id);
		if(!empty($ret)){
			if($ret->user_id ==$orgOwnerid){
				return $ret;
			}
		}
		return false;
	}
	/*  
	 *  判断当前登录用户是否是本机构用户
	 *  $user_id    当前登录用户id
	 *	$orgOwnerid 是机构所有者id
	 */
	public static function loginuserid($user_id=null,$orgOwnerid=null){
		//没有权限
		if($user_id == $orgOwnerid){
			return true;
		}else{
			return false;
		}
	
	}
    //判断管理员
	public static function authrole($user_id=null,$orgOwnerid=null){
		$isAdmin=user_api::isAdmin($orgOwnerid,$user_id);
        if($isAdmin===false){
            return 0;
        }
        return 1;
	}
	/*
	 *  判断当前班级是否属于该机构 
	 *	// 当前班级班主任是否是登录人
	 *  判断当前用户是否属于该机构
	 *  $course_id  
	 *  $class_id  
	 *  $user_id    当前登录用户id
	 *	$orgOwnerid 是机构所有者id
	 *  返回信息
	 *	array = (
	 *   [class_id] => 
	 *   [user_id] => 机构所有者id
	 *   [user_class_id] => 班主任
	 *   [course_id] => 
	 *   [name] => 111
	 *   [descript] => 暂无描述
	 *   [type] => 
	 *   [max_user] => 
	 *   [min_user] => 
	 *   [user_total] => 
	 *   [status] => normal
	 *	)
	 */
	public static function classid($class_id,$user_id,$orgOwnerid){
		if(empty($class_id)||empty($orgOwnerid)||empty($user_id)){
			return false;
		}
		//判断当前用户是否属于该机构
		$judgeuser = self::userid($user_id,$orgOwnerid);
		//如果返回的是false
		if(!$judgeuser){
			return false;
		}
		$classData = course_api::getClass($class_id);
		// 当前班级班主任是否是登录人
		//	if(($classData->user_id == $orgOwnerid)&&($classData->class_id == $class_id)&&($user_id == $classData->user_class_id)){
		//当前班级是否属于该机构-
		if(($classData->user_id == $orgOwnerid)&&($classData->class_id == $class_id)){
			return $classData;
		}
		return false;

	}
	/*
	 *  判断当前排课是否属于该机构
	 *  $course_id  
	 *  $plan_id  
	 *  $user_id    当前登录用户id
	 *	$orgOwnerid 是机构所有者id
	 *
	 *  返回plan的信息
	 *  array= (
	 *	 [plan_id] => 
	 *	 [user_id] => 
	 *	 [user_plan_id] => 讲课老师
	 *	 [course_id] => 
	 *	 [section_id] => 
	 *	 [class_id] => 
	 *	 [start_time] => 2015-04-22 17:09:00
	 *	 [end_time] => 2015-07-29 16:10:48
	 *	 [live_public_type] => 1
	 *	 [video_public_type] => 2
	 *	 [video_trial_time] => 300
	 *	 [status] => finished
	 *	)
	 */
	public static function planid($plan_id,$user_id,$orgOwnerid){
		if(empty($plan_id)||empty($orgOwnerid)||empty($user_id)){
			return false;
		}
		//判断当前用户是否属于该机构
		$judgeuser = self::userid($user_id,$orgOwnerid);
		//如果返回的是false
		if(!$judgeuser){
			return false;
		}
		$planData = course_api::getPlan($plan_id);
		
		if(($planData->user_id == $orgOwnerid)&&($planData->plan_id == $plan_id)){
			return $planData;
		}
		return false;
	}
	/*
	 *  判断当前排课是否属于该老师
	 *  $course_id  
	 *  $plan_id  
	 *  $user_id    当前登录用户id
	 *	$orgOwnerid 是机构所有者id
	 *
	 *  返回plan的信息
	 *  array= (
	 *	 [plan_id] => 
	 *	 [user_id] => 
	 *	 [user_plan_id] => 讲课老师
	 *	 [course_id] => 
	 *	 [section_id] => 
	 *	 [class_id] => 
	 *	 [start_time] => 2015-04-22 17:09:00
	 *	 [end_time] => 2015-07-29 16:10:48
	 *	 [live_public_type] => 1
	 *	 [video_public_type] => 2
	 *	 [video_trial_time] => 300
	 *	 [status] => finished
	 *	)
	 */
	public static function planteacherid($plan_id,$user_id,$orgOwnerid){
		if(empty($plan_id)||empty($orgOwnerid)||empty($user_id)){
			return false;
		}
		//判断当前用户是否属于该机构
		$judgeuser = self::userid($user_id,$orgOwnerid);
		//如果返回的是false
		if(!$judgeuser){
			return false;
		}
		$planData = course_api::getPlan($plan_id);
		
		if(($planData->user_plan_id == $user_id)&&($planData->plan_id == $plan_id)){
			return $planData;
		}
		return false;
	}

	/*
	 *  判断当前用户是否属于该机构
	 *  $user_id    当前登录用户id
	 *	$orgOwnerid 是机构所有者id
	 *	返回值 
	 *  boolean
	 */
	public static function userid($userId,$orgOwnerid){
		if(empty($orgOwnerid)||empty($userId)){
			return false;
		}
		//获取机构信息 
		$orgInfo = user_organization::getOrgByUid($orgOwnerid);
		$orgId = $orgInfo->oid;
		//根据机构id 用户id查找记录
		$orguserinfo = user_organization::getOrgUserinfo($orgId,$userId);
		if(empty($orguserinfo)){
			$userinfo = user_api::getUser($userId);
		}
		//该用户是机构下的老师
		if(!empty($orguserinfo)){
			if($orguserinfo->user_id == $userId){
				return true;
			}else{
				return false;
			}
		//该用户是机构所有者
		}elseif(!empty($userinfo)){
			if(isset($userinfo->organization)){
				$userorg = $userinfo->organization;
			}else{
				//不是机构所有者
				return false;
			}
			//是机构所有者
			if($userorg->user_owner_id==$orgOwnerid){
				return true;
			}
		}else{
			return false;
		}
		return false;
	}
	/*
	 *判断该班级班主任是否是该用户
	 */
	public static function classBelonguid($classid,$uid){
		$classData = course_api::getClass($classid);
		if(!empty($classData)){
			if($classData->user_class_id == $uid){
				return 1;
			}
		}
		return 0;
	}

	/*
	 *判断该排课的讲课老师是否是该用户
	 */
	public static function planBelonguid($planid,$uid){
		$planData = course_api::getPlan($planid);
		if(!empty($planData)){
			if($planData->user_plan_id == $uid){
				return 1;
			}
		}
		return 0;
	}

	/*
	 * 判断听课用户是否是班主任,讲课老师,机构管理员,机构所有者
	 * 返回值 0/1
	 */
	public static function planCanSearch($uid,$orgOwnerid,$class_id,$plan_id){
		//2015-12-18 by hetal
		$perms = course_api::verifyPlan($uid,$plan_id);
		if(!empty($perms->ok)){
			return 1;
		}
/*
		$judgePlan = self::planBelonguid($plan_id,$uid);
		if($judgePlan) return 1;
		$judgeClass = self::classBelonguid($class_id,$uid);
		if($judgeClass)return 1;
		$judgeUser = self:: authrole($uid,$orgOwnerid);
		if($judgeUser) return 1;
*/
		return 0;
	}
	
	/*
	 *@desc 判断是否是该课程的讲师
	 */
	public static function checkCourseTeacher($courseId, $userId, &$res = array()){
		$teacherRes = course_api::getCourseTeacher(array('courseId'=>$courseId));
		$res = array();
		if(!empty($teacherRes)){
			$res = $teacherRes;
			
			foreach($teacherRes as $val){
				$teacherIdArr[] = $val->fk_user_teacher;
			}
			if(in_array($userId, $teacherIdArr)) return true;
		}
		
		return false;
	}
}