<?php
class org_course extends STpl{
	private $domain;
	private $orgOwner;
	private $orgInfo;
	private $isAdmin;
	private $sourceUrl;
	function __construct(){
        //如果没有登陆到登陆界面
        $this->user = user_api::loginedUser();
        if(empty($this->user)){
            $this->redirect("/site.main.login");
        }
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
        $org=user_organization::subdomain();
        if(!empty($org)){
            $this->orgOwner=$org->userId; //机构所有者id 以后会根据域名而列取
        }else{
            header('Location: https://www.'.$this->domain);
        }
        $this->orgInfo=user_organization::getOrgByOwner($this->orgOwner);

        //判断管理员
        $isAdmin=user_api::isAdmin($this->orgOwner,$this->user['uid']);
        if($isAdmin===false){			
            //header('Location: https://www.'.$this->domain);
        }
		$this->isAdmin = $isAdmin;

		$teacherAuth = utility_judgeid::userid($this->user['uid'], $this->orgOwner);
		//既不是管理员也不是该机构下的讲师
		if(!$this->isAdmin && !$teacherAuth){
			header('Location: https://www.'.$this->domain."/site.main.noauth");
		}

		$sourceKey = md5("create_course_".$this->orgInfo->oid.'_'.$this->user['uid']);
		$this->sourceUrl = !empty(utility_session::get()[$sourceKey]) ? '//'.$org->subdomain.'/'.utility_session::get()[$sourceKey] : '//'.$org->subdomain.'/user.org.course';
	}
	/**
	 * 获取plan的直播状态 
	 */
	public function pagePlanStatus($inPath){
		if(!empty($_GET['plan_ids']) && is_array($_GET['plan_ids']) && count($_GET['plan_ids']<=200)){
			return course_api::getPlanStatusV2($_GET['plan_ids']);
		}
		return array();
	}
	/**
	 * 查课
	 */
	public function pageLookup($inPath){
		$date=date("Y-m-d");
		if(!empty($_GET['date'])){
			$date=$_GET['date'];
		}
		$planArr=array(
			"f"=>array(
				'course_id',
				'plan_id',
				'class_id',
				'class_name',
				'section_name',
				'section_desc',
				'admin_id',
				'admin_name',
				'admin_real_name',
				'course_type',
				'start_time',
				'max_user',
				'user_total',
				'try',
				'status',
				'owner_id',
				'section_name',
			),  
			"q"=>array(
				'start_time' => $date." 00:00:00".",".$date." 23:59:59",
				'status'=>'1,2,3',
				'owner_id'=>$this->orgOwner,
			),  
			"ob"=>array(
				'start_time'=>'asc',
			),  
			"p"=>1,
			"pl"=>1000,
		);  
		$seekPlan=seek_api::seekPlan($planArr);    
		$this->assign("plans",$seekPlan);
		$this->assign("date",$date);
		return $this->render("org/multi-screen-player.html");
	}
	
	//----------------------------------- 创建课程 ---------------------------------
	private function checkAddCourseAuth(){

		$isTeacher = utility_judgeid::userid($this->user['uid'], $this->orgOwner);

		if($this->isAdmin){
			$auth = 1;
		}elseif($isTeacher && $this->orgInfo->teacher_add_course){
			$auth = 1;
		}else{
			$auth = 0;
		}
		
		return $auth;
	}

	public function pageType($inPath){

		if(!$this->checkAddCourseAuth()){
			return $this->redirect("/site.main.noauth");
		}

        $cookieKey = "course_".$this->orgInfo->oid.'_'.$this->user['uid'];
        utility_session::get()[$cookieKey] = 0;
		$this->assign('sourceUrl',$this->sourceUrl);
		return $this->render("org/course.type.html");
	}
	
	public function pageAdd($inPath){
		if(!$this->checkAddCourseAuth()){
			return $this->redirect("/site.main.noauth");
		}

		$type = !empty($inPath[3]) ? (int)$inPath[3] : 0;
		if(!in_array($type, array(1,2,3))) return $this->redirect("/org.course.type");

		$cookieKey = "course_".$this->orgInfo->oid.'_'.$this->user['uid'];
		$courseId = (isset(utility_session::get()[$cookieKey]) && !empty(utility_session::get()[$cookieKey])) ? utility_session::get()[$cookieKey] : 0;
	
		$this->assign('token','');
		$this->assign('type',$type);
		$this->assign('courseId',$courseId);
		$this->assign('sourceUrl',$this->sourceUrl);
		$this->render("org/course.add.html");
	}


	public function pageSetDesc(){
		if(!$this->checkAddCourseAuth()){
			return $this->redirect("/site.main.noauth");
		}
		
		$cookieKey = "course_".$this->orgInfo->oid.'_'.$this->user['uid'];
		$courseId = isset(utility_session::get()[$cookieKey]) ? utility_session::get()[$cookieKey] : 0;
		$courseReg = course_api::getCourseOne($courseId);
		if(empty($courseReg)){
			return $this->redirect("/course.main.404");
		} 
		if($courseReg->user_id != $this->orgOwner){
			return $this->redirect("/site.main.entry");
		}
		$data = [
			'userId'    => $this->user['uid'],
			'scope'     => !empty($courseReg->scope) ? $courseReg->scope : '',
			'descript'  => !empty($courseReg->descript) ? $courseReg->descript : '',
			'thumbBig'  => !empty($courseReg->thumb_big) ? interface_func::imgUrl($courseReg->thumb_big) : '',
			'thumb_med' => !empty($courseReg->thumb_med) ? interface_func::imgUrl($courseReg->thumb_med) : ''
		];

		$this->assign('sourceUrl',$this->sourceUrl);
		$this->assign('courseId', $courseId);
		$this->assign('type', $courseReg->type_id);
		$this->assign('data', json_encode($data));
		$this->render("org/course.desc.html");
	}
	/*
	public function pageAddPlan($inPath){

		if(!$this->checkAddCourseAuth()){
			return $this->redirect("/site.main.noauth");
		}

		if(empty($inPath[3])){
			$cookieKey = "course_".$this->orgInfo->oid.'_'.$this->user['uid'];
			$courseId = isset(utility_session::get()[$cookieKey]) ? utility_session::get()[$cookieKey] : 0;
		}else{
			$courseId = (int)$inPath[3];
		}
		$courseReg = course_api::getCourseOne($courseId);
		if(empty($courseReg)){
			return $this->redirect("/course.main.404");
		} 

		$classReg = course_class_api::getClassIndex($courseId);
		$classId  = !empty($classReg) ? $classReg->pk_class : 0;
		
		$this->assign('sourceUrl',$this->sourceUrl);
		$this->assign("classId", $classId);
		$this->assign("type", $courseReg->type_id);
		$this->assign("courseId", $courseId);
		return $this->render("org/plan.add.html");
	}
    */

    public function pageAddPlan($inPath){
        if(!$this->checkAddCourseAuth()){
			return $this->redirect("/site.main.noauth");
		}

        $cookieKey = "course_".$this->orgInfo->oid.'_'.$this->user['uid'];
		$courseId  = isset(utility_session::get()[$cookieKey]) ? utility_session::get()[$cookieKey] : 0;
        $isClassEdit = !empty($inPath[3]) ? (int)$inPath[3] : 0;   

        //课程信息
		$courseReg = course_api::getCourseOne($courseId);
		if(empty($courseReg)){
			return $this->redirect("/course.main.404");
		} 

        //班级信息
        $classReg  = course_api::getclasslist($courseId);
        if(!empty($classReg[0])){
            $classInfo = $classReg[0];
            $classId = $classInfo->class_id;
        }else{
            $classInfo = array();
            $classId = 0;
        }
        //省份信息
        $regionInfo = region_geo::$region;

        //老师信息
        $teacherReg = utility_judgeid::checkCourseTeacher($courseId, $this->user['uid'], $teacherInfo);
        $teachers   = array();
        if(!empty($teacherInfo)){
            foreach($teacherInfo as $val){
		if($val->is_teacher == 1){
                	$teacherIdArr[] = $val->fk_user_teacher;
		}
            }
            $teacherIds = implode(',', $teacherIdArr);
            $teacherParams = [
                'q' => ['teacher_id'=>$teacherIds],
                'f' => ['teacher_id','real_name']
            ];
            $seekTeacher = seek_api::seekTeacher($teacherParams);
            if(!empty($seekTeacher->data)){
                foreach($seekTeacher->data as $val){
                    $teachers[$val->teacher_id] = $val->real_name;
                }
            }
        }

        //地区信息
        $firstRegion = region_api::listRegion(0);

        //排课信息
        $planInfo = course_plan_api::getPlanListByClassId($classId);
        $planNum  = !empty($planInfo) ? count($planInfo) : 0;

		$this->assign('sourceUrl',$this->sourceUrl);
        $this->assign('classInfo', $classInfo);
        $this->assign('regionInfo', $regionInfo);
        $this->assign('planInfo', $planInfo);
        $this->assign('teachers', $teachers);
		$this->assign("type", $courseReg->type_id);
		$this->assign("courseId", $courseId);
		$this->assign("classId", $classId);
        $this->assign("planNum", $planNum);
        $this->assign("firstRegion", $firstRegion);
		$this->assign("isClassEdit", $isClassEdit);
        $this->render("org/plan.add_v2.html");
    }

	//-------------------------- 课程管理 -----------------------------------	
	public function pageManageTop($inPath){
		$courseId = !empty($inPath[3]) ? (int)$inPath[3] : 0;
		$courseReg  = course_api::getCourseOne($courseId);
		//该课程不存在
		if(empty($courseReg)){
			return $this->redirect("/course.main.404");
		}
		
		//该课程不是本机构的
		if($courseReg->user_id != $this->orgOwner){
			return $this->redirect("/site.main.entry");
		}
		
        $sectionReg = course_plan_api::getPlanNumByCourseId($courseId);
		//获取课程平均分
		$ret = comment_api::courseAverage($courseId);
		$score = !empty($ret->total_user) ? round($ret->score/$ret->total_user,1) : 0;
		
		//中间层查询统计数
		$params = [
			'q' => ['course_id'=>$courseId],
			'f' => ['vv','comment']
		];
		$courseRes = seek_api::seekCourse($params);
		
		$data = [
			'courseId'  => $courseId,
			'thumbMed'  => interface_func::imgUrl($courseReg->thumb_med),
			'thumbBig'  => interface_func::imgUrl($courseReg->thumb_big),
			'title'     => $courseReg->title,
			'price'     => !empty($courseReg->fee->price) ? $courseReg->fee->price : 0,
			'publicType'=> !empty($courseReg->public_type) ? '有试听' : '',
			'sectionNum'=> !empty($sectionReg) ? $sectionReg->num : 0,
			'commNum'   => $score ? $score : 0,
			'vv'        => !empty($courseRes->data[0]) ? $courseRes->data[0]->vv : 0,
			'userTotal' => $courseReg->user_total,
			'adminStatus'=> ($courseReg->admin_status==1) ? 'offline' : 'normal',
			'adminStatusName'=> ($courseReg->admin_status==1) ? '下架课程' : '上架课程',
			'scope'     => !empty($courseReg->scope) ? $courseReg->scope : '',
			'descript'  => !empty($courseReg->descript) ? $courseReg->descript : ''
		];
		$isImg = !empty($courseReg->thumb_big) ? 1 : 0;	
		
		$this->assign('type',$courseReg->type_id);
		$this->assign('courseInfo',$data);
		$this->assign('courseId',$courseReg->course_id);
		$this->assign('isImg', $isImg);
		$this->assign('sourceUrl',$this->sourceUrl);
		$this->assign('isAdmin', $this->isAdmin);
		return $this->render("org/course.managetop.html");
	}
	
	public function pageManageNav($inPath){
		$courseId = !empty($inPath[3]) ? (int)$inPath[3] : 0;
		
		$teacherAuth = user_api::checkTeacherCreateCourse($courseId, $this->user['uid']);
		$isAuth = ($this->isAdmin || ($teacherAuth && $this->orgInfo->teacher_add_course)) ? 1 : 0;
		
		$this->assign('courseId', $courseId);
		$this->assign('isAuth', $isAuth);
		return $this->render("org/course.managenav.html");
	}

	public function pageEdit($inPath){
		$courseId = !empty($inPath[3]) ? (int)$inPath[3] : 0;
		if(!$this->isAdmin){
			$teacherAuth = user_api::checkTeacherCreateCourse($courseId, $this->user['uid']);
			//不是该课程下的讲师
			if(!$teacherAuth){
				return $this->redirect("/site.main.noauth");
			}elseif($teacherAuth && !$this->orgInfo->teacher_add_course){
				return $this->redirect("/org.course.plan.{$courseId}");
			}
		}
		
		$this->assign('courseId',$courseId);
		$this->assign('token','');
		$this->assign('isCheck',1);
		$this->render("org/course.edit.html");
	}
	
	public function pageSetImg($inPath){
		$courseId = !empty($inPath[3]) ? (int)$inPath[3] : 0;
		
		if(!$this->isAdmin){
			$teacherAuth = user_api::checkTeacherCreateCourse($courseId, $this->user['uid']);
			//不是该课程下的讲师
			if(!$teacherAuth){
				return $this->redirect("/site.main.noauth");
			}elseif($teacherAuth && !$this->orgInfo->teacher_add_course){
				return $this->redirect("/site.main.noauth");
			}
		}
		
		$this->assign('courseId',$courseId);
		$this->assign('userId',$this->user['uid']);
		$this->assign('isCheck',2);
		$this->render("org/course.img.html");
	}
	
	public function pageSetAbstract($inPath){
		$courseId = !empty($inPath[3]) ? (int)$inPath[3] : 0;

		if(!$this->isAdmin){
			$teacherAuth = user_api::checkTeacherCreateCourse($courseId, $this->user['uid']);
			//不是该课程下的讲师
			if(!$teacherAuth){
				return $this->redirect("/site.main.noauth");
			}elseif($teacherAuth && !$this->orgInfo->teacher_add_course){
				return $this->redirect("/site.main.noauth");
			}
		}
		
		$scope    = !empty($courseReg->scope) ? $courseReg->scope : '';
		$descript = !empty($courseReg->descript) ? $courseReg->descript : '';
		$this->assign('courseId',$courseId);
		$this->assign('scope',$scope);
		$this->assign('descript',$descript);
		$this->assign('isCheck',3);
		$this->render("org/course.abstract.html");
	}
	
	public function pagePlan($inPath){
		$courseId = !empty($inPath[3]) ? (int)$inPath[3] : 0;
		$classId  = !empty($inPath[4]) ? (int)$inPath[4] : 0;
		
		if(!$this->isAdmin){
			$teacherAuth = user_api::checkTeacherCreateCourse($courseId, $this->user['uid']);
			//不是该课程下的讲师
			if(!$teacherAuth){
				return $this->redirect("/site.main.noauth");
			}
		}
		
		$authPlan = (!$this->isAdmin && !$this->orgInfo->teacher_add_course) ? 0 : 1;
		$this->assign('courseId',$courseId);
		$this->assign('updateId',$classId);
		$this->assign('authPlan',$authPlan);
		$this->assign('isCheck',4);
		return $this->render("org/plan.info.html");
	}

    /*
	public function pageEditPlan($inPath){
		$courseId = !empty($inPath[3]) ? (int)$inPath[3] : 0;
		$classId  = !empty($inPath[4]) ? (int)$inPath[4] : 0;

		if(!$this->isAdmin){
			$teacherAuth = user_api::checkTeacherCreateCourse($courseId, $this->user['uid']);
			if(!$teacherAuth){
				return $this->redirect("/site.main.noauth");
			}elseif($teacherAuth && !$this->orgInfo->teacher_add_course){
				return $this->redirect("/site.main.noauth");
			}   
		} 
		
		$this->assign('courseId',$courseId);
		$this->assign('classId',$classId);
		$this->assign('isCheck',4);
		return $this->render("org/plan.edit.html");
    }
    */

    public function pageEditPlan($inPath){
		$courseId = !empty($inPath[3]) ? (int)$inPath[3] : 0;
		$classId  = !empty($inPath[4]) ? (int)$inPath[4] : 0;

		if(!$this->isAdmin){
			$teacherAuth = user_api::checkTeacherCreateCourse($courseId, $this->user['uid']);
			if(!$teacherAuth){
				return $this->redirect("/site.main.noauth");
			}elseif($teacherAuth && !$this->orgInfo->teacher_add_course){
				return $this->redirect("/site.main.noauth");
			}   
        }

        //课程信息
		$courseReg = course_api::getCourseOne($courseId);
		if(empty($courseReg)){
			return $this->redirect("/course.main.404");
		} 

        //班级信息
        $classInfo = course_api::getClass($classId);
        //省份信息
        $regionInfo = region_geo::$region;

        //老师信息
        $teacherReg = utility_judgeid::checkCourseTeacher($courseId, $this->user['uid'], $teacherInfo);
        $teachers   = array();
        if(!empty($teacherInfo)){
            foreach($teacherInfo as $val){
		if($val->is_teacher == 1){
                	$teacherIdArr[] = $val->fk_user_teacher;
		}
            }
            $teacherIds = implode(',', $teacherIdArr);
            $teacherParams = [
                'q' => ['teacher_id'=>$teacherIds],
                'f' => ['teacher_id','real_name']
            ];
            $seekTeacher = seek_api::seekTeacher($teacherParams);
            if(!empty($seekTeacher->data)){
                foreach($seekTeacher->data as $val){
                    $teachers[$val->teacher_id] = $val->real_name;
                }
            }
        }

        //地区信息
        $firstRegion = region_api::listRegion(0);

        //排课信息
        $planInfo = course_plan_api::getPlanListByClassId($classId);
        $planNum  = !empty($planInfo) ? count($planInfo) : 0;
        
        $liveTypeArr  = array('无试看','试看整节');
        $videoTypeArr = array('无试看','试看整节','-2'=>'隐藏视频');
        $videoTime    = array(300=>'试看5分钟',600=>'试看10分钟',1200=>'试看20分钟');

        if(!empty($planInfo)){
            foreach($planInfo as &$val){
                $val->liveType  = !empty($liveTypeArr[$val->live_public_type]) ? $liveTypeArr[$val->live_public_type] : '无试看';
                $val->videoType = !empty($videoTypeArr[$val->video_public_type]) ? $videoTypeArr[$val->video_public_type] : '无试看';
                $val->videoTime = !empty($videoTime[$val->video_trial_time]) ? $videoTime[$val->video_trial_time] : 0;
            }
        }

		$this->assign('courseId',$courseId);
		$this->assign('classInfo',$classInfo);
		$this->assign('classId',$classId);
		$this->assign('teachers',$teachers);
		$this->assign('regionInfo',$regionInfo);
		$this->assign('planInfo',$planInfo);
		$this->assign('planNum',$planNum);
		$this->assign('firstRegion',$firstRegion);
		$this->assign('type',$courseReg->type_id);
		$this->assign('isCheck',4);
		return $this->render("org/plan.edit_v2.html");
	}


	//删除课程
	public function pageDelCourse(){
		//是否是管理员
		if(!$this->isAdmin) return interface_func::setMsg(1);
		$courseId = !empty($_POST['courseId']) ? (int)$_POST['courseId'] : 0;
		$courseReg  = course_api::getCourseOne($courseId);
		if(empty($courseReg)) return interface_func::setMsg(1);
		//该课程不是本机构的
		if($courseReg->user_id != $this->orgOwner) return interface_func::setMsg(1);

		$res = course_api::delCourse($courseId);
		if($res === false) return interface_func::setMsg(1);

		return interface_func::setMsg(0);
	}

}
