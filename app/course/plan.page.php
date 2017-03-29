<?php
/**
 * 上课页
 */
class course_plan extends STpl{

	/**通过课程ID找到当前正在播放的plan_id**/
	public function pagePlayByCid($inPath){
		if(empty($inPath[3])){
			$this->redirect("/");
		}
		$course_id = $inPath[3];
		$plan_info_s = course_api::listPlan(array("cid"=>$course_id,"allcourse"=>true));
		if(empty($plan_info_s->data)){
			return "参数错误";
		}
		foreach($plan_info_s->data as $plan){
			if($plan->plan_status=="living"){
				$this->redirect("/course.plan.play/".$plan->plan_id);
			}
		}
		$this->redirect("/course.info.show/".$course_id);
	}
	public function pageStart($inPath){
		if(empty($inPath[3])){
			$this->redirect("/");
		}
		$plan_id = $inPath[3];
		//获取课程信息，判断老师有没有权限上课
		$plan_info_s = course_api::listPlan(array("plan_id"=>$plan_id,"allcourse"=>true));
		if(empty($plan_info_s->data[0])){
			$this->redirect("/");
		};
		$plan_info = $plan_info_s->data[0];
		$user = user_api::loginedUser();
		if(empty($user['uid'])){
			$this->redirect("/site.main.login?url=/course.plan.start/$plan_id");
		}
                $orgId = 0;
                $org = user_organization::subdomain();
                if (!empty($org->userId)) { $orgOwner = $org->userId; }
                $orgInfo = user_organization::getOrgByOwner($orgOwner);
                if(!empty($orgInfo->oid)){ $orgId = $orgInfo->oid; }
		        $user_id = $user['uid'];
                $special = user_api::getTeacherSpecial($orgId,$user_id);
                $special_role = '';
//                if(!empty($special) && ($special->status==1) && ($special->role==2 || $special->role==1 || $special->user_role&0x01 || $special->user_role&0x04 || $special->user_role&0x02)){
//                     $special_role = 'special';
//                }
                if(!empty($special) && ($special->status==1) && ($special->role==2 || $special->user_role&0x04)){
                    $special_role = 'special';
                }
                //添加是否是当前课程下的教师OR助教判断
                $param = array('fk_course' => $plan_info->course_id,'fk_user_teacher'=>$user['uid']);
                $getTeacherCourse = task_api::getTeacherCourse($param);
                if(!empty($getTeacherCourse->result)){
                    $special_role = 'special';
                }
                if($user_id==$plan_info->user_plan_id) $special_role = 'mainTeacher';
		if($special_role == ''){
			//机构管理员/助教可登录隐藏开始上课/下课; 不是当前的上课老师,到学生页面
			$this->redirect("/course.plan.play/$plan_id");
		};
		$user_token = $user['token'];
		if(empty($user['small'])){
			$user_thumb = '';
		}else{
			$user_thumb = $user['small'];
		}
		//获取plan的状态，如果已经结束，提示是否重新上课，重新上课的视频录制会被覆盖
		$teacher= user_api::getUser($plan_info->user_plan_id);
		/*$course_users = array();
		$course_users1 = course_api::listRegistration(array("course_id"=>$plan_info->course_id,"class_id"=>$plan_info->class_id));
		if(!empty($course_users1->data)){
			$course_users = $course_users1->data;
		}


		foreach($course_users as $k=>$v){
			if (!empty($v->user_info->user_id)) {
				$uidArr[] = $v->user_info->user_id;
			}
		}

		if(!empty($uidArr)){
			$stuProfile = user_api::getUserProfileByUidArr($uidArr);
			if(!empty($stuProfile)){
				foreach($stuProfile as $stk=>$stv){
					$stuProfileArr[$stv->user_id] = $stv;
				}
			}

			foreach($course_users as $ck=>$cv){
				if(!empty($cv->user_info->user_id) && !empty($stuProfileArr[$cv->user_info->user_id]->real_name)){
					$cv->user_info->name = $stuProfileArr[$cv->user_info->user_id]->real_name;
					$cv->user_info->student_name = $stuProfileArr[$cv->user_info->user_id]->real_name;
				}
			}
		}*/

		//获取真实姓名
		$exams = course_api::getPlanExamsByPlan($plan_id, $user_id);
		$filecdn = utility_cdn::filecdn();
		$this->assign("special_role",$special_role);
		$this->assign("exams",json_encode($exams));
		//$this->assign("course_users",$course_users);
		$this->assign("teacher",$teacher);
		$this->assign("plan_id",$plan_id);
		$this->assign("class_id",$plan_info->class_id);
		$this->assign("course_id",$plan_info->course_id);
		$this->assign("user_id",$user_id);
		$this->assign("user_token",$user_token);
		$this->assign("plan_info",$plan_info);
		$this->assign("user_thumb",$user_thumb);
		$this->assign("filecdn",$filecdn);
        
        //当前课程的分组
        $groupList = array();
        $checkTeacherPrivilege=  user_api::checkTeacherPrivilege($plan_info->class_id,$user_id);
        if($checkTeacherPrivilege->result->code==0 && $checkTeacherPrivilege->data==1){//当前老师是助教
            $grouplist_tmp=user_api::groupList($plan_info->class_id);
            if($grouplist_tmp->result->code==0 && !empty($grouplist_tmp->data->items)){
                $grouplist_data=$grouplist_tmp->data->items;
                for($counter=0;$counter<count($grouplist_data);$counter++){
                    $groupid=$grouplist_data[$counter]->pk_group;
                    $groupList[$groupid]=array("name"=>$grouplist_data[$counter]->group_name,"uid"=>$grouplist_data[$counter]->group_teacher_id);
                }
                $groupList[-2]=array("name"=>"未分组", "uid"=>0);
            }
        }
        /*$a[1] = array("name"=>"爱好", "uid"=>111);
        $a[2] = array("name"=>"wsg", "uid"=>222);
        $a[3] = array("name"=>"测试", "uid"=>333);
        $a[-2] = array("name"=>"未分组", "uid"=>0);*/
		$this->assign("groups", json_encode($groupList));
		return $this->render("course/plan.start.v5.html");
	}
	public function pageStartPlan($inPath){
		$ret=new stdclass;
		$cleanFile = false;
		if(empty($inPath[3])){
			$ret->error="参数错误";
			return $ret;
		}
		if(!empty($inPath[4]) && $inPath[4]=='cleanFile'){
			$cleanFile=true;
		}
		$plan_id = $inPath[3];
		// 为题目排序
		$orderexam = $this->orderExams($plan_id);
		//获取课程信息，判断老师有没有权限上课
		$plan_info_s = course_api::listPlan(array("plan_id"=>$plan_id,"allcourse"=>true));
		if(empty($plan_info_s->data[0])){
			return $this->redirect("/");
		};
		$plan_info = $plan_info_s->data[0];
		$user = user_api::loginedUser();

		//设置当前plan_id的上课状态
		//开始录制
		//获取发布流信息，如果没有，或者user_id和当前用户不一样，说明没有发布
		$publish = live_publish::getByUid($user['uid']);
		if(empty($publish)){
			$ret->error="您还没有开始直播";
			return $ret;
		}
		if($publish->live_call=="publish_done"){
			$ret->error="直播已经结束";
			return $ret;
		}
		if($publish->plan_id>0 && $publish->plan_id!=$plan_id){
			$ret->error="同一时间只能上同一节课，你当前正在上课{$publish->plan_id}";
			return $ret;
		}
		$ret_up = course_plan_api::startPlan($user['uid'], $user["token"], $plan_id);
		if($ret_up){
			$ret->code=0;
			$ret->error="更新成功";
		}
		return $ret;
	}
	public function pageStopPlan($inPath){
		$ret=new stdclass;
		if(empty($inPath[3])){
			$ret->error="参数错误";
			return $ret;
		}
		$plan_id = $inPath[3];
		//获取课程信息，判断老师有没有权限上课
		$plan_info = course_api::getPlan($plan_id);
		$user = user_api::loginedUser();
		if(empty($plan_info->user_plan_id) || $plan_info->user_plan_id !=$user['uid']){
			$ret->error="您没有权限";
			return $ret;
		}

		$ret_up = course_plan_api::stopPlan($user['uid'], $user["token"], $plan_id);
		if($ret_up){
			$ret->code=0;
			$ret->error="关闭成功";
		}
		return $ret;
	}
	public function pageUserStatus($inPath){
		if(empty($inPath[3])){
			return false;
		}
		$plan_id = $inPath[3];
		return  course_api::listPlanUser($plan_id);
	}
	public function pageGetStudentsForClass($inPath){
		if(!empty($_POST) && !empty($_POST["class_id"])){
			$ret = course_api::listRegistration(array("class_id"=>$_POST["class_id"]));
			if(!empty($ret->data)){
				return $ret->data;
			}else{
				return false;
			}
		}
		return false;
	}

    public function pagePlay($inPath)
    {
        $planId   = empty($inPath[3]) ? 0 : (int)$inPath[3];
        if (empty($planId)) {  $this->redirect("/"); }
        $userInfo = user_api::loginedUser();

        if (empty($userInfo['uid'])) {
            $userId    = 0;
            $userToken = user_api::getTokenDirect();
        } else {
            $userId    = $userInfo['uid'];
            $userToken = $userInfo['token'];
        }

        if (empty($userToken)) $userToken = 0;
        $userThumb = !empty($userInfo['medium']) ? $userInfo['medium'] : '';
        $resellOrgId = empty($inPath[4]) ? 0 : (int)$inPath[4];
        $planInfos = course_api::listPlan(array("plan_id" => $planId, "allcourse" => true,"resellOrgId"=>$resellOrgId));
        if (empty($planInfos->data[0])) $this->redirect("/");
        $planInfo = $planInfos->data[0];
        $cid      = $planInfo->course_id;
        $classId  = $planInfo->class_id;
        $plist = [
            "cid"       => $cid,
            "class_id"  => $classId,
            "order_by"  => "asc",
            "allcourse" => true,
        ];

        $plansInfos = course_api::listPlan($plist);
        $plansInfo = $plansInfos->data;
        @setcookie("blackUrl".$cid."",implode("/",$inPath),time()+3600,"/",utility_net::getDomainRoot());
        //获取上课老师信息
        $teacher = user_api::getUser($planInfo->user_plan_id);
        $this->assign("teacher", $teacher);

        $perms = course_api::verifyPlan($userId, $planId);
        $level = user_api::getUserLevel($userId);
        $retListAtt = course_api::listPlanAttach(array('classId'=>$classId));
        $listAtt    = 0;
        if (!empty($retListAtt->data)) {
            $listAtt = $retListAtt->data;
        }

        //禁言初始化
        $inputText = 1;//message_api::checkforbid($planId, $userId) ? 1 : 0;

        //判断该章节观看权限
        $isLogin    = !empty($userInfo) ? 1 : 0;
        $livePub    = $planInfos->data[0]->live_public_type; //直播课
        $videoPub   = $planInfos->data[0]->video_public_type;//录播课

        $needLogin = $needSign = 1;
        $isReg = 0;
        if (!empty($perms->reg)){
            $isReg = 1;
            $needSign=0;
        }
        if (!empty($userId)) $needLogin = 0;
        //录播课video_public_type = 1,2 直播课 live_public_type=1为试看
        if (in_array($videoPub, [1, 2]) || $livePub == 1) $needLogin = 0; //直播试看，不需要登陆WEB-4993
        if(!empty($perms->ok)) $needSign=0;
        $needDataInfo = 0;
        if (($needSign) || ($needLogin)) {
            $needDataInfo = 1;
        }

        //播放页新增头部导航
        $retMessagesNum = 0;
        if ($userId) $retMessagesNum = message_api::getUnreadNewsRemind($userId);//$retMessagesNum = message_api::getUnreadInstationNum($userId, $userInfo['token']);

        $orgOwner = 0;
        $org = user_organization::subdomain();
        if (!empty($org->userId)) { $orgOwner = $org->userId; }
        $sourceSubdomain = '';
        if($planInfo->user_course->user_owner!=$orgOwner) {
            $sourceSubdomainInfo = user_organization::getSubdomainByUid($planInfo->user_course->user_owner);
            if(!empty($sourceSubdomainInfo)){
                $http_type = (defined("HTTPS") || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")) ? 'https://' : 'http://';
                $sourceSubdomain = $http_type.$sourceSubdomainInfo->subdomain;
            }
        }
        //判断管理员
        $isAdmin = user_api::isAdmin($orgOwner, $userId);
        //判断老师
        $isTeacher = user_api::isTeacher($orgOwner, $userId);
        //播放页新增头部导航结束

        //获取当前页面url
        $http = utility_net::isHTTPS() ? 'https://' : 'http://';
        if (!empty($org->subdomain)) {
            $myHost  = user_organization::course_domain($org->subdomain);
            $curHost = $myHost.'/course.plan.play/'.$planId;
        } else {
            $curHost = $myHost = 'www.yunke.com';
        }

        $curUrl = $http.$curHost;
        // 获取分享图片真实地址
        $shareImg = interface_func::imgUrl($planInfo->thumb_med);

        // 会员检测报名
        $userStatus = org_member_api::getUserStatus($userId, $planInfo);
        $courseMemberInfo = org_member_api::getCourseMemberInfo($userStatus['courseMemberSetId']);
        $btnMemberInfo = org_member_api::getBtnMemberInfo($userStatus,$isLogin);
        $sameSectionPlist = [
            "sid"=> $planInfo->section_id,
            "class_id"  => $userStatus['regId'],
            "order_by"  => "asc",
			"allcourse" => true
        ];
        $sameSectionPlansInfo = course_api::listPlan($sameSectionPlist);
        $sameSectionPlanInfo = !empty($sameSectionPlansInfo->data[0]) ? $sameSectionPlansInfo->data[0] : array();

        // 获取课程大小班（分组）信息
        /*
        $courseGroupParams = ['cid'=>$cid,'classid'=>$classId];
        $courseClassGroupList = course_group_api::getCourseClassGroupList($courseGroupParams);
        $courseGroupUserParams = ['gid'=>$gid];
        $courseClassGroupUserList = course_group_api::getCourseClassGroupUserList($courseGroupUserParams);

        $courseGroupArr = ['groupId'=>1,'groupName'=>'A组','groupCount'=>30,'groupMessage'=>1];
        $this->assign("courseGroupArr", $courseGroupArr);
        */

        //获取当前登录用户分组信息
        $groupid=0;
        $group_teacher_id=0;
        $group_name='';
        $displayAll=0;
        $checkIsGroupuser=  user_api::checkIsGroupuser(array('classid'=>$classId,'userid'=>$userId));

        if(!empty($checkIsGroupuser->data)){
			$groupid=isset($checkIsGroupuser->data->fk_class_group)?$checkIsGroupuser->data->fk_class_group:0;
            $getGroupInfo=  user_api::getGroupInfo($groupid);
            $classInfo=  course_api::getClass($classId);
			$displayAll=isset($classInfo->group_message)?$classInfo->group_message:0;
			$group_teacher_id=isset($getGroupInfo->data->group_teacher_id)?$getGroupInfo->data->group_teacher_id:0;
            $group_name=$getGroupInfo->data->group_name;
        }
        $isMember =0;
        if($userStatus['regId']==0&&$userStatus['isExpire']==0&&$userStatus['isMember']==1&&$userStatus['isOpen']==1){
            $isMember=1;
        }
		$courseDesc = $this->courseDesc($planInfo, $teacher, $myHost);
        $this->assign("btnMemberInfo", $btnMemberInfo);
        $isWxLogin = weixin_api::is_weixin() ? 1 : 0;
        $this->assign('isMember',$isMember);//是否可以使用会员免费报名
        $this->assign('sourceSubdomain',$sourceSubdomain);
        $this->assign("wxlogined", $isWxLogin);
		$this->assign("courseDesc", $courseDesc);
        $this->assign("needlogin", $needLogin);
        $this->assign("needdatainfo", $needDataInfo);
        $this->assign("needsign", $needSign);
        $this->assign("logined", $isLogin);
        $this->assign("beforeStart", $this->_getBeforeStartInfo($planInfo));
        $this->assign("inputText", $inputText);
        $this->assign("listAtt", $listAtt);
        $this->assign("courseUserTotal", course_class_api::getClassRegUserTotalNum($planInfo->class_id));//获取上课总学生
        $this->assign("isAdmin", $isAdmin);
        $this->assign("isTeacher", $isTeacher);
        $this->assign('retMessagesNum', $retMessagesNum);
        $this->assign("level", $level);
        $this->assign("isReg", $isReg);             // 已报名
        $this->assign("plan_id", $planId);
        $this->assign("class_id", $planInfo->class_id);
        $this->assign("user_info", $userInfo);
        $this->assign('shareImg', $shareImg);
        $this->assign("plan_info", $planInfo);
        $this->assign("plans_info", $plansInfo);
        $this->assign("sameSectionPlanInfo", $sameSectionPlanInfo);
        $this->assign("user_id", $userId);
        $this->assign("user_owner", $orgOwner);
        $this->assign("user_token", $userToken);
        $this->assign("user_thumb", $userThumb);
        $this->assign("filecdn", utility_cdn::filecdn());

        $this->assign('qrCode', utility_cdn::qrcode("qr?s=200&t=".urlencode($curUrl)."?guide={$isWxLogin}"));
        //$this->assign('curUrl', $curUrl);
        $this->assign('myHost', $http.$myHost);
        $this->assign('playMsgInfo', $this->_getPlayMsg($planInfo));

        $this->assign('userStatus', $userStatus);
        $this->assign('courseMemberInfo', $courseMemberInfo);
        //test
        $this->assign('groupId', $groupid);
        $this->assign('groupUid', $group_teacher_id);
        $this->assign('groupName', $group_name);
        //$this->assign('groupNum', 30);
        $this->assign('displayAll', $displayAll);
        $this->assign('resellOrgId',$resellOrgId);
        $this->render("course/plan.play.v2.html");
    }

    public function pagePlayV3($inPath){
        $planId   = empty($inPath[3]) ? 0 : (int)$inPath[3];
        if (empty($planId)) {  $this->redirect("/"); }
        $resellOrgId = empty($inPath[4]) ? 0 : (int)$inPath[4];
        $planInfos = course_api::listPlan(array("plan_id" => $planId, "allcourse" => true,"resellOrgId"=>$resellOrgId));
        if (empty($planInfos->data[0])) $this->redirect("/");
        $planInfo = $planInfos->data[0];
        $cid      = $planInfo->course_id;
        @setcookie("blackUrl".$cid."",implode("/",$inPath),time()+3600,"/",utility_net::getDomainRoot());
        //获取上课老师信息
        $teacher = user_api::getUser($planInfo->user_plan_id);
        $title = $planInfo->title;
        $teacherName = !empty($teacher->profile->real_name) ? $teacher->profile->real_name : $teacher->name;
        $resellOrgId = empty($inPath[4]) ? 0 : (int)$inPath[4];
        $this->assign("teacher", $teacherName);
        $this->assign('title',$title);
        $this->assign('resellOrgId',$resellOrgId);
        $this->render("course/plan.play.v3.html");
    }

    public function pagePlayData(){
        $planId   = empty($_POST['planId']) ? 0 : (int)$_POST['planId'];
        if (empty($planId)) {  return json_encode(array('code'=>-101,'msg'=>'排课ID为空')); }
        $userInfo = user_api::loginedUser();
        if (empty($userInfo['uid'])) {
            $userId    = 0;
            $userToken = user_api::getTokenDirect();
        } else {
            $userId    = $userInfo['uid'];
            $userToken = $userInfo['token'];
        }
        if (empty($userToken)) $userToken = 0;
        $userThumb = !empty($userInfo['medium']) ? $userInfo['medium'] : '';
        $resellOrgId = empty($inPath[4]) ? 0 : (int)$inPath[4];
        $planInfos = course_api::listPlan(array("plan_id" => $planId, "allcourse" => true,"resellOrgId"=>$resellOrgId));
        if (empty($planInfos->data[0])) { return json_encode(array('code'=>-102,'msg'=>'排课信息为空')); }
        $planInfo = $planInfos->data[0];
        $cid      = $planInfo->course_id;
        $classId  = $planInfo->class_id;
        $plist = [
            "cid"       => $cid,
            "class_id"  => $classId,
            "order_by"  => "asc",
            "allcourse" => true,
        ];
        $plansInfos = course_api::listPlan($plist);
        $plansInfo = $plansInfos->data;
        // @setcookie("blackUrl".$cid."",implode("/",$inPath),time()+3600,"/",utility_net::getDomainRoot());
        //@setcookie("blackUrl".$cid."course/plan/playV3/".(time()+3600)."/",utility_net::getDomainRoot());
        $array = array();
        //获取上课老师信息
        $teacher = user_api::getUser($planInfo->user_plan_id);
        $array['teacher'] = $teacher;
        //$this->assign("teacher", $teacher);

        $perms = course_api::verifyPlan($userId, $planId);
        $level = user_api::getUserLevel($userId);
        $retListAtt = course_api::listPlanAttach(array('classId'=>$classId));
        $listAtt    = 0;
        if (!empty($retListAtt->data)) {
            $listAtt = $retListAtt->data;
        }

        //禁言初始化
        $inputText = 1;//message_api::checkforbid($planId, $userId) ? 1 : 0;

        //判断该章节观看权限
        $isLogin    = !empty($userInfo) ? 1 : 0;
        $livePub    = $planInfos->data[0]->live_public_type; //直播课
        $videoPub   = $planInfos->data[0]->video_public_type;//录播课

        $needLogin = $needSign = 1;
        $isReg = 0;
        if (!empty($perms->reg)){
            $isReg = 1;
            $needSign=0;
        }
        if (!empty($userId)) $needLogin = 0;
        //录播课video_public_type = 1,2 直播课 live_public_type=1为试看
        if (in_array($videoPub, [1, 2]) || $livePub == 1) $needLogin = 0; //直播试看，不需要登陆WEB-4993
        if(!empty($perms->ok)) $needSign=0;
        $needDataInfo = 0;
        if (($needSign) || ($needLogin)) {
            $needDataInfo = 1;
        }

        //播放页新增头部导航
        $retMessagesNum = 0;
        if ($userId) $retMessagesNum = message_api::getUnreadNewsRemind($userId);//$retMessagesNum = message_api::getUnreadInstationNum($userId, $userInfo['token']);

        $orgOwner = 0;
        $org = user_organization::subdomain();
        if (!empty($org->userId)) { $orgOwner = $org->userId; }

        //判断管理员
        $isAdmin = user_api::isAdmin($orgOwner, $userId);
        //判断老师
        $isTeacher = user_api::isTeacher($orgOwner, $userId);
        //播放页新增头部导航结束

        //获取当前页面url
        $http = utility_net::isHTTPS() ? 'https://' : 'http://';
        if (!empty($org->subdomain)) {
            $myHost  = user_organization::course_domain($org->subdomain);
            $curHost = $myHost.'/course.plan.playV3/'.$planId;
        } else {
            $curHost = $myHost = 'www.yunke.com';
        }

        $curUrl = $http.$curHost;
        // 获取分享图片真实地址
        $shareImg = interface_func::imgUrl($planInfo->thumb_med);

        // 会员检测报名
        $userStatus = org_member_api::getUserStatus($userId, $planInfo);
        $courseMemberInfo = org_member_api::getCourseMemberInfo($userStatus['courseMemberSetId']);
        $btnMemberInfo = org_member_api::getBtnMemberInfo($userStatus,$isLogin);
        $sameSectionPlist = [
            "sid"=> $planInfo->section_id,
            "class_id"  => $userStatus['regId'],
            "order_by"  => "asc",
            "allcourse" => true
        ];
        $sameSectionPlansInfo = course_api::listPlan($sameSectionPlist);
        $sameSectionPlanInfo = !empty($sameSectionPlansInfo->data[0]) ? $sameSectionPlansInfo->data[0] : array();

        // 获取课程大小班（分组）信息
        /*
        $courseGroupParams = ['cid'=>$cid,'classid'=>$classId];
        $courseClassGroupList = course_group_api::getCourseClassGroupList($courseGroupParams);
        $courseGroupUserParams = ['gid'=>$gid];
        $courseClassGroupUserList = course_group_api::getCourseClassGroupUserList($courseGroupUserParams);
        $courseGroupArr = ['groupId'=>1,'groupName'=>'A组','groupCount'=>30,'groupMessage'=>1];
        $this->assign("courseGroupArr", $courseGroupArr);
        */

        //获取当前登录用户分组信息
        $groupid=0;
        $group_teacher_id=0;
        $group_name='';
        $displayAll=0;
        $checkIsGroupuser=  user_api::checkIsGroupuser(array('classid'=>$classId,'userid'=>$userId));

        if(!empty($checkIsGroupuser->data)){
            $groupid=isset($checkIsGroupuser->data->fk_class_group)?$checkIsGroupuser->data->fk_class_group:0;
            $getGroupInfo=  user_api::getGroupInfo($groupid);
            $classInfo=  course_api::getClass($classId);
            $displayAll=isset($classInfo->group_message)?$classInfo->group_message:0;
            $group_teacher_id=isset($getGroupInfo->data->group_teacher_id)?$getGroupInfo->data->group_teacher_id:0;
            $group_name=$getGroupInfo->data->group_name;
        }
        $isMember =0;
        if($userStatus['regId']==0&&$userStatus['isExpire']==0&&$userStatus['isMember']==1&&$userStatus['isOpen']==1){
            $isMember=1;
        }
        $courseDesc = $this->courseDesc($planInfo, $teacher, $myHost);
        $array['btnMemberInfo'] = $btnMemberInfo;
        $isWxLogin = weixin_api::is_weixin() ? 1 : 0;
        $array['isMember'] = $isMember; //是否可以使用会员免费报名
        $array['wxlogined'] = $isWxLogin;
        $array['courseDesc'] = $courseDesc;
        $array['needlogin'] = $needLogin;
        $array['needdatainfo'] = $needDataInfo;
        $array['needsign'] = $needSign;
        $array['logined'] = $isLogin;
        $array['beforeStart'] = $this->_getBeforeStartInfo($planInfo);
        $array['inputText'] = $inputText;
        $array['listAtt'] = $listAtt;
        $array['courseUserTotal'] = course_class_api::getClassRegUserTotalNum($planInfo->class_id);
        $array['isAdmin'] = $isAdmin;
        $array['isTeacher'] = $isTeacher;
        $array['retMessagesNum'] = $retMessagesNum;
        $array['level'] = $level;
        $array['isReg'] = $isReg;  // 已报名
        $array['plan_id'] = $planId;
        $array['class_id'] = $planInfo->class_id;
        $array['user_info'] = $userInfo;
        $array['shareImg'] = $shareImg;
        $array['plan_info'] = $planInfo;
        $array['plans_info'] = $plansInfo;
        $array['sameSectionPlanInfo'] = $sameSectionPlanInfo;
        $array['user_id'] = $userId;
        $array['user_owner'] = $orgOwner;
        $array['user_token'] = $userToken;
        $array['user_thumb'] = $userThumb;
        $array['filecdn'] = utility_cdn::filecdn();
        $array['qrCode'] = utility_cdn::qrcode("qr?s=200&t=".urlencode($curUrl)."?guide={$isWxLogin}");
        $array['curUrl'] = $curUrl;
        $array['myHost'] = $http.$myHost;
        $array['playMsgInfo'] = $this->_getPlayMsg($planInfo);
        $array['userStatus'] = $userStatus;
        $array['courseMemberInfo'] = $courseMemberInfo;
        //test
        $array['groupId'] = $groupid;
        $array['groupUid'] = $group_teacher_id;
        $array['groupName'] = $group_name;

        //$this->assign('groupNum', 30);
        $array['displayAll'] = $displayAll;
        return json_encode(array('code'=>0,'msg'=>'success','data'=>$array));
    }

    private function courseDesc($courseInfo, $teacherInfo, $myHost){
		$data = array();
		if(!empty($courseInfo)){
			$data['courseName'] = $courseInfo->title;
			$data['courseDesc']  = !empty($courseInfo->descript) ? trim(strip_tags($courseInfo->descript)) : '';
			$data['courseUrl']   = '//'.$myHost."/course.info.show/".$courseInfo->course_id;
		}
		if(!empty($teacherInfo)){
			$data['teacherName'] = $teacherInfo->profile->real_name;
			$data['teacherImg']  = $teacherInfo->avatar->medium;
			$data['teacherDesc'] = !empty($teacherInfo->teacher->desc) ? strip_tags($teacherInfo->teacher->desc) : '';
			$data['teacherUrl']  = '//'.$myHost."/teacher/detail/entry/".$teacherInfo->uid;
		}
		
		return $data;
	}
	
	public function orderExams($plan_id){
		$data = array(
			"plan_id"=>$plan_id,
		);
		$retQuesdata = course_api::coursePlanExamList($data);
		if(!empty($retQuesdata->data)){
			$examdata = $retQuesdata->data;
			foreach($examdata as $k=>$v){
				$examid = $v->plan_exam_id;
				$orderno = $k;
				$dataup = array(
					"order_no"=>$orderno,
				);
				$retupexam = course_api::updateCoursePlanExam($examid,$dataup);
			}
		}
	}
	public function pageUpdatePlanExamStatus($inPath){
		$user = user_api::loginedUser();
		if(empty($user)){
			return false;
		}
		if(empty($_POST)){
			return false;
		}
		return course_api::updatePlanExamStatus($_POST, $user["uid"]);
	}
    /**
     * ＠desc 获取开课前的信息
     *
     * @param $planInfo
     * @return stdclass
     */
    private function _getBeforeStartInfo($planInfo)
    {
        $startTime   = strtotime($planInfo->start_time);
        $seconds     = $startTime - time();
        $beforeStart = new stdclass;
        if (($seconds < 86400) && ($seconds > 0)) {
            $hours   = floor($seconds / 3600);
            $minutes = floor($seconds % 3600 / 60);
            if ($minutes <= 0) {
                $minutes = 0;
            }

            if ($hours > 0) {
                $beforeStart->seconds        = $seconds;
                $beforeStart->time_countdown = "离开课时间:".$hours."小时".$minutes."分";
                $beforeStart->img            = "/assets_v2/img/icon-clock.png";
            } else {
                $beforeStart->seconds        = $seconds;
                $beforeStart->time_countdown = "离开课时间:".$minutes."分";
                $beforeStart->img            = "/assets_v2/img/icon-clock.png";
            }
        } else {
            $beforeStart->plandesc       = "还没有开课哦！";
            $beforeStart->time_countdown = $planInfo->start_time;
            $beforeStart->img            = "/assets_v2/img/icon-coffee.png";
            $beforeStart->section_name   = $planInfo->section_name;
            $beforeStart->section_desc   = $planInfo->section_descipt;
        }

        return $beforeStart;
    }

    private function _getPlayMsg($planInfo)
    {
        if ($planInfo->plan_status === 'finished' && $planInfo->video_public_type == -2) {
            return interface_func::error(20080, '该课时暂无视频~');
        } elseif ($planInfo->type_id == 2 && !$planInfo->video_id) {
            return interface_func::error(20090, '该课时暂无视频~');
        } else {
            if ($planInfo->plan_status === 'finished') {
                if (!$planInfo->video_id) {
                    return interface_func::error(20010, '课程视频正在准备中...请稍后再回来观看'); //转码中
                }

                /*$uploadTask = live_file::getUploadTask(0, $planInfo->plan_id);
                if (!empty($uploadTask)) {
                    return interface_func::error(20010, '课程视频正在准备中...请稍后再回来观看!'); //转码中
                }*/
            }
        }

        return interface_func::error(0, 'success');
    }

    public function pageError(){
        return $this->render('order/order_error.html');
    }
}
