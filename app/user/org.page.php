<?php
class user_org extends STpl{
	var $user;
	var $orgOwner;
	var $orgId;
	var $orgInfo;
	private $major = array(
		"0"  =>  "请选择",
		"1"  =>  "数学",
		"2"  =>  "英语",
		"3"  =>  "语文",
		"4"  =>  "物理",
		"5"  =>  "化学",
		"6"  =>  "生物",
		"7"  =>  "历史",
		"8"  =>  "地理",
		"9"  =>  "政治",
		"10"  =>  "计算机",
		"11"  =>  "音乐",
		"12"  =>  "科学",
		"13"  =>  "阅读",
		"14"  =>  "安全",
		"15"  =>  "礼仪",
		"16"  =>  "美劳",
		"17"  =>  "培训",
		"18"  =>  "其他",
	);
	function __construct(){
		//如果没有登陆到登陆界面
		$this->user = user_api::loginedUser();
		if(empty($this->user)){
			$this->redirect("/site.main.login");
		}
		$org=user_organization::subdomain();
		$this->domain = $_SERVER["HTTP_HOST"];
		if(!empty($org)){
			$this->orgOwner = $org->userId; //机构所有者id 以后会根据域名而列取
		}else{
            header('Location: https://www.'.$this->domain);
		}
		$this->orgInfo=user_organization::getOrgByOwner($this->orgOwner);
		if(!empty($this->orgInfo->oid)){
			$this->orgId = $this->orgInfo->oid;
		}else{
			header('Location: https://www.'.$this->domain);
		}
        //判断管理员
        $isAdmin=user_api::isAdmin($this->orgOwner,$this->user['uid']);
        if($isAdmin===false){
            header('Location: //'.$org->subdomain.'.'.$this->domain);
        }
	}
	public function pageEntry($inPath){
		return $this->render("user/home.html");
	}
	public function pagegetRegionAjax($inPath){
		$result=new stdClass;
		if(empty($_POST['rid'])){
			$result->error="获取失败！";
			return $result;
		}
		$params=array();
		$params=array(
			'parent_region_id'=>!empty($_POST['rid'])?$_POST['rid']:0,
			//'level'=>!empty($_POST['level'])?$_POST['level']:0,
		);
		if(!empty($_POST['rid']) && $_POST['rid'] <= 4 ){
			$params['level'] = 2;
		}
		$r=user_organization::getRegionList($params);
		if($r){
			$result->data=$r;
			return $result;
		}else{
			$result->error="获取失败！";
			return $result;
		}
	}

	public function pageMenu($inPath){
		$userinfo=user_api::getUser($this->user['uid']);
		$ipinfo = utility_ip::info(utility_ip::realIp());
		if(isset($userinfo->profile->desc) && strlen($userinfo->profile->desc)>7){
			$userinfo->profile->desc = mb_substr($userinfo->profile->desc,0,7,'utf-8').'..';
		}
		$this->assign("userinfo",$userinfo);
		$this->assign("ipinfo",$ipinfo);
		return $this->render("user/menu.html");
	}

	public function pageIframeTeacher($inPath){
        $sectionId=$inPath[3];
        $this->assign('sectionId',$sectionId);
        $this->render('user/iframe.teacher.html');
	}

	public function pageSetAdminStatusAjax($inPath){
        
        $result=new stdclass;
        $cid = !empty($inPath[3]) ? (int)$inPath[3] : 0;
        $adminStatus = !empty($_POST["adminstatus"]) ? $_POST["adminstatus"] : '';
        if(empty($cid) || empty($adminStatus)){
            $result->code = "1";
            $result->error = "No params";
            return $result;
        }
        
        $retJudgeuser = utility_judgeid::authrole($this->user['uid'],$this->orgOwner);
        if(!$retJudgeuser){
            $result->code = "1";
            $result->error = "No authority";
            return $result;
        }
        
        $courseReg = course_api::getCourseOne($cid);
        if(empty($courseReg) || empty($courseReg->thumb_big)){
            $result->code = "1";
            $result->error = "未添加课程图片";
            return $result;
        }
        
        $changeAdmin = course_api::setCourseAdminStatus($cid,$adminStatus);
        
        if(!$changeAdmin){
            $result->code = "1";
            $result->error="更新失败";
            return $result;
        }
        
        if($adminStatus=="normal"){
            $result->code = "0";
            $result->status="Success!";
            $result->error="上架后课程将在8分钟内展现在前台，请耐心等待";
            return $result;
        }else{
            $result->code = "0";
            $result->status="Success!";
            $result->error="下架操作将会在8分钟内生效，请耐心等待";
            return $result;
        }
    }
	/**
	 *新版 课程列表
	 */
	public function pagecourse($inPath){
		
		$retJudgeuser = utility_judgeid::authrole($this->user['uid'],$this->orgOwner);
		if(!$retJudgeuser){
			//没有权限
			$this->redirect("/site.main.entry");
			die("No authority");
		}
		if(!empty($_REQUEST['cid'])&&!empty($_REQUEST['admin_status'])){
			//判定当前课程是不是自己的
			$cid = $_REQUEST['cid'];
			course_api::setCourseAdminStatus($cid,$_REQUEST['admin_status']);
		}
		$path = '/user.org.course';
		$uri = '';
		$num ="10";
		$page = isset($_GET['page']) ? $_GET['page']:1;
		$data = array();
		$oid = $this->orgOwner;
		$org_info = user_organization::getOrgByOwner($oid);
		$shelfArr = array(
			"on"=>"1", //上架
			"off"=>"-2", //下架
			"all"=>null //所有
		);
        //create by zhangtaifeng 2015/09/18
        $shelf=!empty($_REQUEST["shelf"])?$_REQUEST["shelf"]:'all';
        $this->assign('shelf',$shelf);
        if(isset($shelfArr[$shelf])){
            $shelf=$shelfArr[$shelf];
        }else{
            $shelf=null;
        }
		$data["shelf"] =$shelf;
		if(isset($_REQUEST["orderby"])){
			$selorder = $_REQUEST["orderby"];
		}else{
			$selorder = "crtdesc";
		}
		$data["create_time"] = "desc";
		if(!empty($_REQUEST["type"])){
			$getType = $_REQUEST["type"];
		}else{
			$getType = "all";
		}
		if(isset($_REQUEST["orderby"])){
			if($_REQUEST["orderby"]=="crtdesc"){
				$create_time = "desc";
			}elseif($_REQUEST["orderby"]=="crtasc"){
				$create_time = "asc";
			}else{
				$create_time = null;
			}
			if($_REQUEST["orderby"]=="regdesc"){
				$user_total = "desc";
			}elseif($_REQUEST["orderby"]=="regasc"){
				$user_total = "asc";
			}else{
				$user_total = null;
			}
			$data["user_total"] = $user_total;
			$data["create_time"] = $create_time;
			if(isset($_REQUEST['s'])){
				$data["search"] = $_REQUEST["s"];
				$search = $data["search"];
				$this->assign("search",$search);
				$path = "/user.org.course?shelf=".$_REQUEST['shelf']."&orderby=".$_REQUEST['orderby']."&type=".$getType."&s=".$_REQUEST['s'];
			}else{
				$path = "/user.org.course?shelf=".$_REQUEST['shelf']."&orderby=".$_REQUEST['orderby']."&type=".$getType;
			}
		}
		$typeArr = array(
			"all"=>"0",
			"online"=>"1",
			"video"=>"2",
			"offline"=>"3",
		);

		$typeNum = $typeArr[$getType];
		$this->assign('courseType',$getType);
		if(!empty($typeNum)){
			$data["type"] = $typeNum;
		}
		if($getType =="online"){
			$this->assign('message','暂时还没有直播课哦');
		}elseif($getType=="video"){
			$this->assign('message','暂时还没有录播课哦');
		}elseif($getType=="offline"){
			$this->assign('message','暂时没有线下课');
		}
		$course_list_ret = course_api::getCourselistByOid($page,$num,$this->orgOwner,$data);
		// 默认第一个班级的班主任为整个课程的班主任
		$course_list=array();
		if(empty($course_list_ret->data)){
			if(isset($_REQUEST['s'])){
				$this->assign('message','没有搜到这个课程请您换个条件试试吧~');
			}elseif(isset($_REQUEST["shelf"])&&$_REQUEST['shelf']=='off'){
				$this->assign('message','您还没有未上架的课程哦！');
			}elseif(isset($_REQUEST["shelf"])&&$_REQUEST['shelf']=='on'){
				$this->assign('message','您还没有已上架的课程哦！');
			}elseif($getType == 'all'){
				return $this->render("user/course.default.v2.html");
			}
		}else{
			$course_list = $course_list_ret->data;
			//拿出course的id 拼成一个字符串 中间层要用
			$courseidsArr = array();
			foreach($course_list as $courselistk=>$courselistv){
				$courseidsArr[] = $courselistv->course_id;
			}
			$courseids = implode(",",$courseidsArr);

			//获取当前用户的课程列表明
			/*中间层取数据**/
			$fArray = array("course_id","title","create_time","desc","vv","comment");
			$qArray=array(
				'user_id'=>$this->orgOwner,
				'course_id'=>$courseids,
			);

			$obArray = array(
				'top'=>'desc',
			);
			$seekArr = array(
				"f"=>$fArray,
				"q"=>$qArray,
				"ob"=>$obArray,
				"p"=>1,
				"pl"=>$num,
			);
			$retCourseData = seek_api::seekcourse($seekArr);
			if(!empty($retCourseData->data)){
				$courseData = $retCourseData->data;
				$countCourseSections = array();
				$countCoursevv = array();
				$countCourseComment = array();
				foreach($courseData as $courseDatak=>$courseDatav){
					//$countCourseSections[$courseDatav->course_id] = count($courseDatav->section);
					$countCoursevv[$courseDatav->course_id]=  $courseDatav->vv;
					$countCourseComment[$courseDatav->course_id]= $courseDatav->comment;
				}
				/*在course_list上面加上sections的count*/
				foreach($course_list as $courselistk=>$courselistv){
					$course_list[$courselistk]->countsecs = empty( $countCourseSections[$courselistv->course_id])?"0": $countCourseSections[$courselistv->course_id];
					$course_list[$courselistk]->countvv = empty($countCoursevv[$courselistv->course_id])?"0":$countCoursevv[$courselistv->course_id];
					$course_list[$courselistk]->countcomment = empty($countCourseComment[$courselistv->course_id])?"0":$countCourseComment[$courselistv->course_id];
				}
			}
			$teaTmpidArr = array();
			foreach($course_list as $k=>$v){
				if(!empty($v->class)){
					foreach($v->class as $ck=>$cv){
						if(!empty($cv->teacher->pk_user)){
							$teaTmpidArr[$cv->teacher->pk_user] = $cv->teacher->pk_user;
						}
					}
				}
			}
            if(!empty($teaTmpidArr)){
                $teaTmpcode = implode(",",$teaTmpidArr);
                $f_array2 = array("real_name","name","teacher_id","thumb_big");
                $q_array2=array(
                    'teacher_id'=>$teaTmpcode,
                    'org_id'=>$org_info->oid,
                    'teacher_status'=>1,
                );
                $ob_array2 = array(
                    'teacher_id'=>'desc',
                );
                $seek_arr2 = array(
                    "f"=>$f_array2,
                    "q"=>$q_array2,
                    "ob"=>$ob_array2,
                    "p"=>1,
                    "pl"=>1000,
                );
                $ret_seekTea2 = seek_api::seekTeacher($seek_arr2);
                if(!empty($ret_seekTea2->data)){
                    foreach($ret_seekTea2->data as $k=>$v){
                        $teaNameArr[$v->teacher_id] = $v;
                    }
                    $this->assign("teaNameArr",$teaNameArr);
                }
            }
		}
		$list = $course_list_ret;
		$this->assign("list",$list);
		$this->assign("selorder",$selorder);
		$this->assign("course_list",$course_list);

		$this->assign("path",$path);
		$this->assign("num",$num);
		$time_now = time();
		$this->assign("time_now",$time_now);
		$this->assign("isTeacherCreateCourse",$this->orgInfo->teacher_add_course);
		return $this->render("user/org.course.html");
	}

	public function pagecheckCourse($inPath){
        $cookieKey = "course_".$this->orgId.'_'.$this->user['uid'];
        utility_session::get()[$cookieKey] = 0;
		return $this->render("user/course.check.html");
	}

	public function pageCourseInfo($inPath){
		$isAdmin = user_api::isAdmin($this->orgOwner, $this->user['uid']);
		if(!$isAdmin) return $this->redirect("/site.main.entry");

		$type = !empty($inPath[3]) ? (int)$inPath[3] : 0;
		if(!in_array($type, array(1,2,3))) return $this->redirect("/user.org.checkcourse");

		$token = utility_session::get()['courseAddToken'] = md5(time().'courseAddToken'.time());
		$cookieKey = "course_".$this->orgId.'_'.$this->user['uid'];
		$courseId = (isset(utility_session::get()[$cookieKey]) && !empty(utility_session::get()[$cookieKey])) ? utility_session::get()[$cookieKey] : 0;
		$this->assign('token',$token);
		$this->assign('type',$type);
		$this->assign('courseId',$courseId);
		$this->render("user/course.info.v2.html");
	}

	public function pageSetCourseDesc(){
		$isAdmin = user_api::isAdmin($this->orgOwner, $this->user['uid']);
		if(!$isAdmin) return $this->redirect("/user.org.course");

		$cookieKey = "course_".$this->orgId.'_'.$this->user['uid'];
		$courseId = isset(utility_session::get()[$cookieKey]) ? utility_session::get()[$cookieKey] : 0;
		$courseReg = course_api::getCourseOne($courseId);
		if(empty($courseReg)) return $this->redirect("/user.org.course");
		if($courseReg->user_id != $this->orgOwner){
			return $this->redirect("/user.org.course");
		}
		$data = [
			'userId'    => $this->user['uid'],
			'scope'     => !empty($courseReg->scope) ? $courseReg->scope : '',
			'descript'  => !empty($courseReg->descript) ? $courseReg->descript : '',
			'thumbBig'  => !empty($courseReg->thumb_big) ? interface_func::imgUrl($courseReg->thumb_big) : '',
			'thumb_med' => !empty($courseReg->thumb_med) ? interface_func::imgUrl($courseReg->thumb_med) : ''
		];

		$this->assign('courseId', $courseId);
		$this->assign('type', $courseReg->type_id);
		$this->assign('data', json_encode($data));
		$this->render("user/course.desc.html");
	}

	public function pageEditCourse($inPath){
		$isAdmin = user_api::isAdmin($this->orgOwner, $this->user['uid']);
		if(!$isAdmin) return $this->redirect("/site.main.entry");

		$courseId = !empty($inPath[3]) ? (int)$inPath[3] : 0;
		$courseReg = course_api::getCourseOne($courseId);
		if(empty($courseReg)) return $this->redirect("/user.org.course");
		if($courseReg->user_id != $this->orgOwner){
			return $this->redirect("/user.org.course");
		}

		$this->assign('top','/site.main.nav.home');
		$this->assign('type',$courseReg->type_id);
		$this->assign('courseId',$courseId);
		$this->assign('token','');
		$this->assign('isCheck',1);
		$this->render("user/course.edit.v2.html");
	}

	public function pageGetNodeCate($inPath){
		$cateId = isset($inPath[3]) ? (int)$inPath[3] : 0;
		if(empty($cateId)){
			$this->setResultAjax(-1,'cateId is empty');
		}
        $ret = course_api::getNodeCate($cateId);
		if(!empty($ret)){
			foreach($ret as &$cate){
				$cate->name_display = SLanguage::tr($cate->name_display,"course.list");
			}
			$this->setResultAjax(0,'success',$ret);
		}else{
			$this->setResultAjax(-2,'获取数据失败');
		}
	}

	public function pageGetAttrAndValueByCateId($inPath){
		$cateId = isset($inPath[3]) ? (int)$inPath[3] : 0;
        $ret = course_api::getAttrAndValueByCateId($cateId);
		if(!empty($ret)){
			foreach($ret as &$attr){
				$attr->name_display = SLanguage::tr($attr->name_display,"course.list");
			}
			$this->setResultAjax(0,'success',$ret);
		}else{
			$this->setResultAjax(-2,'获取数据失败');
		}
	}

	public function pageGetAttrValueByAttrId($inPath){
		$attrId = isset($inPath[3]) ? (int)$inPath[3] : 0;
		if(empty($attrId)){
			$this->setResultAjax(-2,'属性id为空!');
		}
        $ret = course_api::getAttrValueByAttrId($attrId);
		if(!empty($ret)){
			foreach($ret as &$value){
				$value->name = SLanguage::tr($value->name,"course.list");
			}
			$this->setResultAjax(0,'success',$ret);
		}else{
			$this->setResultAjax(-2,'获取数据失败');
		}
	}

	public function setResultAjax($code,$msg,$data=''){
		$ret = array(
				'code' => $code,
				'msg'  => $msg,
				'data' => $data
		);
		exit(json_encode($ret));
	}

	/*
	 *设置老师排序
	 */
	public static function pageSetTeacherSortAjax($inPath){
		$result=new stdclass;
		$data = array();
		$oid = $_REQUEST["oid"];
		$uid = $_REQUEST["uid"];
		$data["sort"] = empty($_REQUEST["sort"])? 0:$_REQUEST["sort"];
		$data["is_star"] = empty($_REQUEST["is_star"])? 0:1;
		//	$ret = user_organization::Usersetsort($oid,$uid,$sort);


		if(!empty($_REQUEST["major"])){
			$major = array();
			$major["major"] = $_REQUEST["major"];
			$ret1 = user_api::setTeacherInfo($uid,$major);
			if($ret1){
				//	$this->assign("error","修改成功");
				//	$ret = user_organization::addUser($oid,$uid,$data);
				$ret = user_organization::Usersetsort($oid,$uid,$data);
			}
			//	$teacher = user_api::getTeacherInfo($this->user['uid']);
			//
			if($ret){
				$result->status="Success!";
				return $result;
			}else{
				$result->error="failed";
				return $result;
			}
		}else{
			//	$ret = user_organization::addUser($oid,$uid,$data);
			$ret = user_organization::Usersetsort($oid,$uid,$data);
			//	$teacher = user_api::getTeacherInfo($this->user['uid']);
			//
			if($ret){
				$result->status="Success!";
				return $result;
			}else{
				$result->error="failed";
				return $result;
			}
		}
	}
	//添加公告
	public function pageAddNoticeAjax($inPath){
		$result=new stdclass;
		$user_id=$this->user['uid'];
		$title = strip_tags($_REQUEST['notice_title']);
		if(empty($title)){
			$result->error="公告标题不能为空";
			$result->field="notice_title";
			return $result;
		}
		if(strlen($title)>100){
			$result->error="标题不能超过100个字符";
			$result->field="notice_title";
			return $result;
		}
		if(empty($_REQUEST['notice_content'])){
			$result->error="公告内容不能为空";
			$result->field="notice_content";
			return $result;
		}
		$notice_data=array(
			'fk_user_id'=>$this->orgOwner,
			'notice_title'=>$title,
			'notice_content'=>$_REQUEST['notice_content'],
			'fk_cate'=>$_REQUEST['catename'],
			'create_time'=>date('Y-m-d H:i:s'),
			'update_time'=>date('Y-m-d H:i:s'),
		);

		$r=user_api::addNotice($notice_data);
		if($r){
			$result->status="Success!";
			return $result;
		}else{
			$result->error="添加失败!";
			return $result;
		}
	}
	//修改公告
	public function pageupdateNoticeAjax($inPath){
		$result=new stdclass;
		$user_id=$this->user['uid'];
		if(empty($_REQUEST['notice_title'])){
			$result->error="公告标题不能为空";
			$result->field="notice_title";
			return $result;
		}
		if(empty($_REQUEST['notice_content'])){
			$result->error="公告内容不能为空";
			$result->field="notice_content";
			return $result;
		}
		if(empty($_REQUEST['nid'])){
			$result->error="参数错误";
			return $result;
		}
		$notice_data=array(
			'notice_title'=>$_REQUEST['notice_title'],
			//'notice_link'=>$_REQUEST['notice_link'],
			'notice_content'=>$_REQUEST['notice_content'],
			'fk_cate'=>$_REQUEST['catename'],
			'update_time'=>date('Y-m-d H:i:s')
		);
		$notice_info=user_api::getNotice($_REQUEST['nid']);
		if($notice_info==false){
			$result->error="非法操作";
			return $result;
		}
		$r=user_api::updateNotice($_REQUEST['nid'],$notice_data);
		if($r){
			$result->status="Success!";
			return $result;
		}else{
			$result->error="修改失败!";
			return $result;
		}

	}
	
	/**
	 * 发送验证码
	 **/
	public function pageAddTeacherCodeAjax($inPath){
		$result=new stdclass;
		if(empty($_REQUEST['mobile'])){
			$result->field="mobile";
			$result->error="手机号码不能为空";
			return $result;
		}
		if(utility_valid::mobile($_REQUEST['mobile'])==false){
			$result->field="mobile";
			$result->error="手机号码格式不正确";
			return $result;
		}
		//发送
		$org_info = user_organization::getOrgByUid($this->user['uid']);
		if(empty($org_info->name)){
			$result->error="你还没有完善您的机构信息";
			return $result;
		}
		$r = verify_api::sendMobileAddTeacher($_REQUEST['mobile'],$org_info->name,$ret);
		if($r!==false){
			$result->error="验证码已经发送到该用户的手机上";
			$result->field="verify_code";
			return $result;
		}else{
			$result->error="发送验证码错误,[".$ret->result->msg."]";
			$result->field="verify_code";
			return $result;
		}
	}
	public function pageTeacher($inPath){
		$org_info = user_organization::getOrgByUid($this->user['uid']);
		if(empty($org_info->name)){
			//机构信息没有完善，提示
			$this->assign("tips","请完善您的机构信息");
		}
		$user_list = user_organization::listOrgUser($org_info->oid,$all=1,$star=-1,$page=1,$length=100);
		//print_r($user_list);
		$this->assign("user_list",$user_list);
		$this->assign("major",$this->major);
		$this->assign("org_info",$org_info);
		return $this->render("user/org.teacher.html");
	}
	public function pageTeacherv2($inPath){
		$org_info = user_organization::getOrgByUid($this->user['uid']);
		//	$host = $_SERVER['HTTP_HOST'];
		//	$urledit = "http://".$host."/user.org.test";
		if(empty($org_info->name)){
			//机构信息没有完善，提示
			$this->assign("tips","请完善您的机构信息");
		}
		$user_list = user_organization::listOrgUser($org_info->oid,$all=1,$star=0,$page=1,$length=1000);
		$this->assign("user_list",$user_list);
		//	$this->assign("urledit",$urledit);
		$this->assign("org_info",$org_info);
		return $this->render("user/teachermanage_v2.html");
	}
	public function pageuserCourse($inPath){
		//获取老师下的机构对应的所有课程，然后过滤掉不是自己班级的信息
                //error_reporting(0);
		$isorg=false;
		$org_info = user_organization::getOrgByOwner($this->orgOwner);
		$special = user_api::getTeacherSpecial($org_info->oid,$this->user['uid']);
		//$path = '/user.teacher.student';
		$uri = '';
		$num ="6";
		$page = isset($_GET['page']) ? $_GET['page']:1;
		$course_id = isset($_GET["course_id"]) ? $_GET["course_id"]:0;
		$class_id = isset($_GET["class_id"]) ? $_GET["class_id"]:0;
                $group_id=isset($_GET["group_id"]) ? $_GET["group_id"]:-1;//默认显示全部学员
		$count_all = 0;
		$lege = utility_judgeid::courseid($course_id,$this->user['uid'],$this->orgOwner);
		$isAdmin=user_api::isAdmin($this->orgOwner,$this->user['uid']);
		if(($lege ===false) || ($isAdmin===false)){
			return $this->redirect("/site.main.entry");
		}
		//$coursesRet = course_api::getcourselistbyoid(1,400,$this->orgOwner);
                //$class_list = course_api::getClasslist($course_id);//print_r($classReg);
		/*$courses=array();
		if(!empty($coursesRet->data)){
			$coursesTmp = $coursesRet->data;
			//当是老师时，过滤掉不是自己的课程和班级数据
			if(!$isorg){
				foreach($coursesTmp as $i=>$course){
						if(!empty($course_id) && $course_id==$course->course_id){
							$courses[]=$course;
						}
				}
			}
		}*/
		$ret = array();
		$regdata = array("course_id"=>$course_id);
		$regdata["class_id"]= $class_id;
		$mobile = isset($_GET["mobile"]) ? trim($_GET["mobile"]) :'';
                if(!empty($mobile))$data['search']=$mobile;
                $data['cache']=0;//不需要缓存
                $data['type'] = 2;
                $data['classid'] = $class_id;
                $data['groupid'] = $group_id;
                $data['loginid']=$this->user['uid'];
                $data['page'] = $page;
                $data['pagesize'] = 100;
                $rs = user_api::userList($data);
                
                //获取组list
                $grouplist[-2]='未分组';
                $grouplist_tmp=  user_api::groupList($class_id);//print_r($grouplist_tmp->data->items);
                if(!empty($grouplist_tmp->data->items)){
                    for($num=0;$num<count($grouplist_tmp->data->items);$num++){
                        $pk_group=$grouplist_tmp->data->items[$num]->pk_group;
                        $group_name=$grouplist_tmp->data->items[$num]->group_name;
                        $grouplist[$pk_group]=$group_name;
                    }
                    $this->assign("showbutton",1);//显示批量修改按钮
                    
                }else{
                     $this->assign("showbutton",0);
                }
                //print_r($grouplist);
                $this->assign('grouplist',$grouplist);
                
                $tmp_rs=new stdclass;
                if(!empty($rs->data)){
                            for($counter=0;$counter<count($rs->data);$counter++){
                                //print_r($rs->data[$counter]);
                                $tmp_rs->$counter=new stdClass;
                                $tmp_rs->$counter->user_info=$rs->data[$counter];

                            }
                }

		//if(!empty($mobile)){
			//$tmp_r = course_api::listregistration($regdata);
			/*foreach($tmp_rs as $k=>$v){
                        //foreach($tmp_r->data as $k=>$v){
				if($mobile==$v->user_info->mobile || $mobile==$v->user_info->real_name){
					//$ret[][]=$v;
                                    $tmp_mobile[]=$v;
				}
			}
                        if(!empty($tmp_mobile)){
                            $ret[]=$tmp_mobile;
                        }*/
		//}else{
			if(!empty($course_id)){
			//$tmp_r = course_api::listregistration($regdata);//echo 2;print_r($tmp_r);

                        $ret[]=$tmp_rs;
			//if(!empty($tmp_r->data)) $ret[]= $tmp_r->data;
			}

		//}//print_r($ret);
                $this->assign("path","/user.org.userCourse?course_id={$course_id}&class_id={$class_id}&group_id={$group_id}");
                $this->assign("page",$rs->page);
		$this->assign("ret",$ret);
		$this->assign("mobile",$mobile);
		if($course_id){
			$course= course_api::getcourseone($course_id);
			$classes_tmp = course_api::getclasslist($course_id);
			$classes=array();
			if(!empty($classes_tmp)){
				foreach($classes_tmp as $tmp){
					$classes[$tmp->class_id]=$tmp;
				}
			}
			$this->assign("classes",$classes);
			$this->assign("course",$course);
		}//print_r($classes);
		/*$showData = array();
		foreach($courses as $k=>$v){
			if(isset($courses[$k]->course_id)){
				$showData[$k]["course_id"] = $courses[$k]->course_id;
			}
			if(isset($courses[$k]->class)){
				foreach($courses[$k]->class as $ck=>$cv){
					$showData[$k]["class"][$ck]["class_id"] = $courses[$k]->class[$ck]->class_id;
					$showData[$k]["class"][$ck]["name"] = $courses[$k]->class[$ck]->name;
				}
			}
		}*/
                
		//$this->assign("showData",$showData);
		//$this->assign("courses",$courses);
		$this->assign("course_id",$course_id);
		$this->assign("class_id",$class_id);
		//$this->assign("path",$path);
                
		return $this->render("user/userCourse.html");

	}

	public static function pageGetNodeCateAjax($inPath){
		$result=new stdclass;
		$data = array();
		if(!empty($_REQUEST["cateId"])){
			$cateId = $_REQUEST["cateId"];
		}else{
			$result->error="failed";
			return $result;
		}
		$ret = course_api::getnodecate($cateId);
		if($ret){
			$result->status="Success!";
			return $result;
		}else{
			$result->error="failed";
			return $result;
		}
	}
	public function getRecentTag(){
		$org=user_organization::getOrgByOwner($this->orgOwner);
		$data = ["oid"=>(int)$org->oid];
		$tagInfo = tag_api::getRecentTag($data);
		return $tagInfo;
	}
	public function CourseTagInfo($courseId){
		$tagConf = SConfig::getConfig(ROOT_CONFIG."/group.conf","group");
		$data = ['courseId'=>$courseId,"tagConf"=>$tagConf->tagCourse];
		$info = tag_api::getUserSelectedCourseTag($data);
		return $info;
	}
	
	//设置老师建课权限
	public function pageOpenCreate(){
		$type = (!empty($_POST['type']) && in_array($_POST['type'],array(0,1))) ? (int)$_POST['type'] : 0;
		
		$data = array('teacher_add_course'=>$type);
		$res = org_api::setOrganization($data, $this->orgId);
		return $res;
	}
	
	public function pageSourceUrl(){
		$key = md5("create_course_".$this->orgInfo->oid."_".$this->user['uid']);
		utility_session::get()[$key] = "user.org.course";
	}

}
