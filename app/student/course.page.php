<?php
class student_course extends STpl{

	var $user;
	private $domain;
	private $orgOwner;
	function __construct(){
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
		$this->assign('domain', $this->domain);

		$this->user = user_api::loginedUser();
		if(empty($this->user)){
			if(!empty($_SERVER['REQUEST_URI'])){
				$this->redirect("/site.main.login?url=".$_SERVER['REQUEST_URI']);
			}else{
				$this->redirect("/site.main.login");
			}
		}
        $org=user_organization::subdomain();
        if(!empty($org)){
            $this->orgOwner=$org->userId; //机构所有者id 以后会根据域名而列取
        }else{
            header('Location: https://www.'.$this->domain);
        }
		
	}

	public function pageMyCourse($inPath){

		$uid = $this->user['uid'];
		$currDate  = date('n.j',time());
		$num  = 10;
		$page = isset($_GET['page']) ? $_GET['page']:1;
		$size = isset($_GET['size']) ? $_GET['size']:0;
		$searchTitle = isset($_GET['title'])?$_GET['title']:'';
		$searchDel = isset($_GET['del'])?$_GET['del']:0;
		$params = !empty($inPath[3])?$inPath[3]:0;
		if($params == 2){
			$searchDel = 1;
		}
		if(!empty($searchTitle)){
			$path = "/student.course.mycourse?del={$searchDel}&title={$searchTitle}";
		}else{
			$path = '/student.course.mycourse';
		}
		//获取直播列表内容
		$planlist = array();
		$startTime = date('Y-m-d',time()).' 00:00:00';
		$userLivingCourse = course_api::getUserLivingCourse($uid,1,$startTime);
		if(!empty($userLivingCourse)){
			foreach($userLivingCourse as $course){
				if($course->source == 2){
					$memberCourseIdArr[] = $course->fk_course;
				}
			}
			if(!empty($memberCourseIdArr)){
				$memberCourse = org_member_api::checkUserCourseIsMember($uid,$memberCourseIdArr);
			}
			foreach($userLivingCourse as $course){
				$cflag = 0;
				if(isset($memberCourse[$course->fk_course]) ){
					$cflag = $memberCourse[$course->fk_course]['is_member'];
				}else{
					$cflag = 1;
				}
				if($cflag == 1){
					$livingCourseIdArr[] = $course->fk_course;
					$livingClassIdArr[]  = $course->fk_class;
					$livingCourse[$course->fk_course] = $course;
					if( $course->fee_type == 0 ) {
						$livingCourse[$course->fk_course]->fee_type = SLanguage::tr('免费','site.teacher');
						$livingCourse[$course->fk_course]->fee_class = 'cGreen';
					}else{
						$livingCourse[$course->fk_course]->fee_type = '￥'.$course->price/100;
						$livingCourse[$course->fk_course]->fee_class = 'cYellow';
					}
				}
			}
			
			if(!empty($livingCourseIdArr)){
				$livingPlan = $this->getLivingPlan($livingCourseIdArr,$livingClassIdArr,1,20);
				$planlist   = $this->handlePlan($livingPlan, $currDate, $livingCourse);
			}
		}

		//获取报名课程
		$courseCount = 0;
		$totalPage   = 0;
		$courseInfo  = array();
		$regInfo = course_api::getUserRegisterCourseList($uid,$page,$num,0,$searchTitle);//,$this->orgOwner,$searchTitle
		if(!empty($regInfo->data)){
			$courseCount = $regInfo->totalSize;
			$totalPage   = $regInfo->totalPage;
			foreach($regInfo->data as $rv){
				$courseIdArr[] = $rv->fk_course;
				$classIdArr[]  = $rv->fk_class;
				$courseInfo[] = $rv;
			}
			$coursePlan  = $this->getCoursePlanList($courseIdArr,$classIdArr);
			$coursePlanList = array();
			$coursePlanTemp = array();
			if(!empty($coursePlan)){
				foreach($coursePlan as $vo){
					$coursePlanList[$vo->course_id][$vo->status][] = $vo;
					$coursePlanTemp[$vo->course_id][] = $vo;
				}
			}
			foreach($courseInfo as &$co){
				$co->teacher_url = '/teacher.detail.entry/'.$co->fk_user_class;
				$co->thumb_sma = utility_cdn::file($co->thumb_small);
				if( $co->fee_type == 0 ) {
            		$co->fee_type = '免费';
					$co->fee_class = 'cGreen';
            	}else{
                	$co->fee_type = '￥'.$co->price/100;
					$co->fee_class = 'cYellow';
           		}
				//$co->section_url  = '/student.course.detail/'.$co->fk_course.'/'.$co->fk_class;
				$co->section_url  = '/course.info.show/'.$co->fk_course;
				$temp = array();
				if(!empty($coursePlanList[$co->fk_course][2])){
					$temp = $coursePlanList[$co->fk_course][2][0];
				}
				if(empty($temp)){
					$finishPlan = array();
					if(!empty($coursePlanList[$co->fk_course][3])){
						$finishCount = count($coursePlanList[$co->fk_course][3]);
						$finishPlan = $coursePlanList[$co->fk_course][3][$finishCount-1];
						$finishStime = strtotime($finishPlan->start_time);
					}
					if(!empty($coursePlanList[$co->fk_course][1]) && !empty($coursePlanList[$co->fk_course][3])){
						foreach($coursePlanList[$co->fk_course][1] as $to){
							$stime = strtotime($to->start_time);
							if($stime > $finishStime){
								$temp = $to;
								break;
							}
						}
					}
					if(empty($coursePlanList[$co->fk_course][3]) && !empty($coursePlanList[$co->fk_course][1])){
						$temp = $coursePlanList[$co->fk_course][1][0];
					}
					if(empty($temp)){
						$temp = $finishPlan;
					}
				}
				$offlineFlag = 0;
				if(!empty($temp)){
					if($co->type == 3){
						foreach($coursePlanTemp[$co->fk_course] as $plan){
							if(strtotime($plan->start_time) >= time()){
								$offlineFlag = 1;
								$temp->section_name = $plan->section_name;
								break;
							}
							$temp->subdomain = $plan->subdomain;
						}
					}
					$co->subdomain = user_organization::course_domain($temp->subdomain);
					$co->section_name = $temp->section_name;
					$co->section_status = $temp->status;
				}else{
					$co->section_name   = '';
					$co->section_status = 1;
				}
				if($co->status == 3 || $co->type == 2 ){
					$co->section_show = 0;
				}elseif($co->type == 3 && $offlineFlag == 0){
					$co->section_show = 0;
				}else{
					$co->section_show = 1;
				}
			}
		}
		$this->assign('curr_date', $currDate);
		$this->assign('user_course', $courseInfo);
		$this->assign('course_count', $courseCount);
		$this->assign('planlist', $planlist);
		$this->assign('title', $searchTitle);
		$this->assign('del', $searchDel);
		$this->assign('num',$num);
		$this->assign('total_page',$totalPage);
		$this->assign('path',$path);
		$this->assign('page',$page);
		$this->assign('size',$size);
		$this->render('student/my.course.html');
	}

	public function pageGetPlanAjax($inPath){
		$page = !empty($_POST['page'])?$_POST['page']: 1;
		$uid = $this->user['uid'];
		$currDate  = date('n.j',time());
		$startTime = date('Y-m-d',time()).' 00:00:00';
		$userLivingCourse = course_api::getUserLivingCourse($uid,1,$startTime,$this->orgOwner);
		if(!empty($userLivingCourse)){
			foreach($userLivingCourse as $course){
				$livingCourseIdArr[] = $course->fk_course;
				$livingClassIdArr[]  = $course->fk_class;
        		$livingCourse[$course->fk_course] = $course;
            	if( $course->fee_type == 0 ) {
            		$livingCourse[$course->fk_course]->fee_type = "免费";
					$livingCourse[$course->fk_course]->fee_class = 'cGreen';
            	}else{
                	$livingCourse[$course->fk_course]->fee_type = '￥'.$course->price/100;
					$livingCourse[$course->fk_course]->fee_class = 'cYellow';
           		}
			}
			$livingPlan = $this->getLivingPlan($livingCourseIdArr,$livingClassIdArr,$page,20);				
			$planlist   = $this->handlePlan($livingPlan, $currDate, $livingCourse);	
			if(!empty($planlist)){
				return $this->setAjaxResult(0,'success',$planlist);
			}else{
				return $this->setAjaxResult(-1,'data is empty');
			}
        }else{
			return $this->setAjaxResult(-2,'not find data');
		}
	}

	public function setAjaxResult($code, $msg, $data=array()){
		return json_encode(
			array(
				'code' => $code,
				'msg'  => $msg,
				'data' => $data
			),
			JSON_UNESCAPED_UNICODE
		);
	}
	
	public function pageDetail($inPath){
		$course_id = $inPath[3];	
		$class_id  = $inPath[4];	
		$uid = $this->user['uid'];
		if(!empty($course_id) && !empty($class_id)){
			$checkCourse = course_api::getCourseOne($course_id);
			if(empty($checkCourse)){
				$this->redirect("/course.main.404");
			}
			$checkClass = course_api::getClass($class_id);
			if(empty($checkClass)){
				$this->redirect("/course.main.404");
			}
			$check = course_api::checkUserRegisterCourse($course_id,$class_id,$uid);
			if($check->result->code == 0 && !empty($check->result->data)){
				$cid_arr = array($course_id);
				$classid_arr = array($class_id);
				$course_ret = $this->getCourseInfo($cid_arr);
				$course_info = array();
				$class_detail = array();
				if(!empty($course_ret)){
					$course_info = $course_ret[$course_id];
					if(!empty($course_info->class)){
						foreach($course_info->class as $class){
							if($class->class_id == $class_id){
								$class_info = $class;
								$admin_id  = $class->class_admin_id;
								break;
							} 
						}
					}
					//获取班主任信息
					if(!empty($admin_id)){
						$admin_info = user_api::getUser($admin_id);
						$class_detail['admin_id']    = $admin_id;
						$class_detail['admin_url']   = '/teacher.detail.entry/'.$admin_id;
						$class_detail['admin_thumb'] = $admin_info->avatar->large;
					}else{
						$class_detail['admin_id']    = '';
						$class_detail['admin_url']   = '';
						$class_detail['admin_thumb'] = '';
					}

					if(!empty($admin_info->profile->real_name)){
						$class_detail['admin_name'] = $admin_info->profile->real_name;
					}elseif(!empty($admin_info->name)){
						$class_detail['admin_name'] = $admin_info->name;
					}else{
						$class_detail['admin_name'] = '';
					}

					$class_detail['course_url'] = '/course.info.show/'.$course_id;
					if(!empty($course_info)){
						$class_detail['course_thumb_sma']  = utility_cdn::file($course_info->thumb_sma);
						$class_detail['course_name'] = $course_info->title;
						$class_detail['section_count'] = 0;//$course_info->section_count;		
						$class_detail['course_type'] = $course_info->course_type;
					}else{
						$class_detail['course_thumb_sma']  = '';
						$class_detail['course_name'] = '';
						$class_detail['section_count'] = 0;
						$class_detail['course_type'] = '';
					}
					//获取班级信息
					if(!empty($class_info)){
						$class_detail['class_name'] = $class_info->name;
						$region_level0 = !empty(region_geo::$region[$class_info->region_level0])?region_geo::$region[$class_info->region_level0]:'';
						$region_level1 = !empty(region_geo::$region[$class_info->region_level1])?region_geo::$region[$class_info->region_level1]:'';
						$region_level2 = !empty(region_geo::$region[$class_info->region_level2])?region_geo::$region[$class_info->region_level2]:'';
						$address = $class_info->address;
						$class_detail['address'] = $region_level0.$region_level1.$region_level2.$address;
					}else{
						$class_detail['class_name'] = '';
						$class_detail['address'] = '';
					}				
					$class_detail['curr_sname'] = '';
					$class_detail['curr_pid']   = '';
                   	$class_detail['percent']    = '0%';
				}
				$course_plan  = $this->getCoursePlanList($cid_arr,$classid_arr);
				$section_count = count($course_plan);
				$finish_section = 0;
				$temp = array();
				$curr_plan = array();
				$curr_date = date('m.d',time());
				$finish_planids = array();
				$planids = array();
				$sort = 0;
				$good_count = 0;
				$right_percent = '0%';
				$right_count = 0;
				$question_count = 0;
				$avg_score = 0;
				$attach_data = array();
				$file_data = array();
				$study_flag = array();
				$offline_finish = array();
				$user_comment = array();
				if(!empty($course_plan)){
					//章节信息
					$curr_year = date('Y',time());
					foreach($course_plan as &$po){
						$temp[$po->status][] = $po;
            			$start_day = date('m.d',strtotime($po->start_time));
						$start_time_year = date('Y',strtotime($po->start_time));
						$po->start_day = $start_day;
                    	$po->plan_url = '/course.plan.play/'.$po->plan_id;
                    	$po->teacher_url = '/teacher.detail.entry/'.$po->teacher_id;
						$stime = strtotime($po->start_time);
						$nextday = date('Y-m-d',$stime+24*3600).' '.'00:00:00';
						$po->syear = date('Y',$stime).SLanguage::tr('年','LearningCenter');
						$po->smonth = date('m',$stime).SLanguage::tr('月','LearningCenter');
						$po->sday   = date('d',$stime).SLanguage::tr('日','LearningCenter');
						$po->shour  = date('H:i',$stime);
						if($curr_year != $start_time_year){
							$po->start_date = $po->syear.$po->smonth.$po->sday.' '.$po->shour;
						}else{
							$po->start_date = $po->smonth.$po->sday.' '.$po->shour;
						}
						if($po->course_type == 2){
							if($po->status == 3){
								$finish_planids[] = $po->plan_id;
							}
						}else{
							if($po->status == 3 || $po->status == 2){
								$finish_planids[] = $po->plan_id;
							}
						}
						if($po->course_type == 3 && time() >= strtotime($nextday)){
							$offline_finish[] = $po->plan_id;
						}
						$planids[] = $po->plan_id;
						$po->video_time = utility_tool::sec2time($po->totaltime);
					}

					
					//获取用户已经学习的章节
					if( !empty($course_info) && $course_info->course_type != 3){
						$study_flag = $this->getStudyPlan($planids);
					}
					
					//获取课程进度	
					if(!empty($course_info) && $course_info->course_type == 1){
						if(!empty($temp[3])){
							$finish_section = count($temp[3]);
							$curr_plan = $temp[3][$finish_section-1];
							$finish_stime = strtotime($curr_plan->start_time);
						}
						if(!empty($temp[1]) && !empty($temp[3]) ){
							foreach($temp[1] as $to){
								$stime = strtotime($to->start_time);
								if($stime < $finish_stime){
									$finish_section += 1;	
								}
							}
						}
						if(!empty($curr_plan)){
							$class_detail['curr_sname'] = $curr_plan->section_name;
							$class_detail['curr_pid']   = $curr_plan->plan_id;
						}else{
							$class_detail['curr_sname'] = '';
							$class_detail['curr_pid']   = '';
						}
						$class_detail['percent'] = floor(($finish_section/$section_count)*100) .'%';
					}elseif(!empty($course_info) && $course_info->course_type == 2){
						$video_count = count($finish_planids);
						$class_detail['percent'] = floor(($video_count/$section_count)*100) .'%';
					}elseif(!empty($course_info) && $course_info->course_type == 3){
						$offline_finish_count = count($offline_finish);
						$class_detail['percent'] = floor(($offline_finish_count/$section_count)*100) .'%';
					}

					//课堂统计
					if(!empty($finish_planids) && !empty($course_info) && $course_info->course_type == 1){
					//排名 点赞总次数
						$good_ret = message_api::getPlanGoodByPidArr($finish_planids);
						if(!empty($good_ret) && !empty($good_ret->data)){
							$good_data = $good_ret->data;
							foreach($good_data as $go){
								$good_key[] = $go->num;
							}
							array_multisort($good_key,SORT_DESC,$good_data);
							foreach($good_data as $kk=>$gg){
								if($gg->fk_user == $uid){
									$sort = $kk+1;
									$good_count = $gg->num;
									break;
								}
							}				
							if($sort == 0){
								$sort = count($good_data)+1;
							}
						}
						//获取用户答题统计
						$question_ret = course_api::getPlanQuestionCountByPidArr($finish_planids);
						if(!empty($question_ret) && !empty($question_ret->data)){
							$question_count = $question_ret->data;
						}
						$answer_ret = exam_api::getUserRightAnswerCountByPidArr($uid,$finish_planids);		
						if(!empty($answer_ret) && !empty($answer_ret->data)){
							$right_count = $answer_ret->data;
						}
						if($question_count != 0 ){
					    	$right_percent = floor(($right_count/$question_count)*100) .'%';
						}
					}

					//获取新版课程统计
					$orgOwner = $this->orgOwner;
					$classIds = array("$class_id");
					//这个班级有几个结束的plan
					$planGroupList = course_api::endgroupbyclassids(null,$classIds);
					$planIdArr = array();
					$planCount=1;
					if(!empty($planGroupList->data)){
						$planCount = count($planGroupList->data);
					}
					//$planids = array(0=>"4166",1=>"4141");
					//$uid = 255;
					$userPlanStat = stat_api::getUserPlanStatByPidArr($uid,$planids);
					$userStatTotal = array(
						"vvRecord"=>0,//直播时长
						"vtRecord"=>0,//录播时长
						"vtLive"=>0,//录播次数
						"discuss"=>0,//评论数
						"zan"=>0,//赞数
						"handup"=>0,//举手数
						"call"=>0,//发言数
						"correct"=>0,//随堂测试正确率
						"answerRate"=>0,//询问回答率
						"lastUpdated"=>0,//更新时间
						"attendance"=>0,//到课率
						"onTime"=>0,//准时次数
						"late"=>0,//迟到次数
						"noTo"=>0,//未到到次数
					);
					$planStatCount = 0;
					$planStatCorrectCount=0;
					$planStatAnswerRateCount=0;
					if(!empty($userPlanStat)){
						foreach($userPlanStat as $stat){
							$userStatTotal["vvRecord"] +=$stat->vt_live;
							$userStatTotal["discuss"] +=$stat->discuss;
							$userStatTotal["zan"] +=$stat->zan;
							$userStatTotal["handup"] +=$stat->handup;
							$userStatTotal["call"] +=$stat->call;
							$userStatTotal["correct"] +=$stat->correct*100;
							$userStatTotal["answerRate"] +=$stat->answer_rate*100;
							if(strtotime($userStatTotal["lastUpdated"])<strtotime($stat->last_updated)) {
								$userStatTotal["lastUpdated"] = $stat->last_updated;
							}
							if($stat->status==1||$stat->status==2){
								$userStatTotal["attendance"] += 1;
							}
							if($stat->status==1){
								$userStatTotal["onTime"] += 1;
							}
							if($stat->status==2){
								$userStatTotal["late"] += 1;
							}
							$userStatTotal["vtRecord"] += $stat->vt_record;
							$userStatTotal["vtLive"] += $stat->vv_record;
							$planStatCount +=1;
							if(!empty($stat->classroom_test_count)&&$stat->classroom_test_count>0){
								$planStatCorrectCount +=1;
							}
							if(!empty($stat->ask_count)&&$stat->ask_count>0){
								$planStatAnswerRateCount +=1;
							}
						}
						$userStatTotal["noTo"] = $planStatCount-($userStatTotal["onTime"]+$userStatTotal["late"]);
						if($userStatTotal["noTo"]<0){
							$userStatTotal["noTo"]=0;
						}
						$userStatTotal["vvRecord"] = round(($userStatTotal["vvRecord"]/60));
						$userStatTotal["vtRecord"] = round(($userStatTotal["vtRecord"]/60));
						$userStatTotal["correct"] = $userStatTotal["correct"] / 100;
						if($planStatCorrectCount>0) {
							$userStatTotal["correct"] = round(($userStatTotal["correct"] / $planStatCorrectCount), 2);
						}
						$userStatTotal["answerRate"] = $userStatTotal["answerRate"]/100;
						if($planStatAnswerRateCount>0){
							$userStatTotal["answerRate"] = round(($userStatTotal["answerRate"]/$planStatAnswerRateCount),2);
						}
						if($planStatCount>0) {
							$userStatTotal["attendance"] = round(($userStatTotal["attendance"] / $planStatCount * 100), 2);
						}
						if($userStatTotal["attendance"]>100){
							$userStatTotal["attendance"]=100;
						}
					}

					//获取班级学生对课程的评价
					if(!empty($course_info) && $course_info->course_type != 3 && !empty($finish_planids)){
						$score_ret = message_api::getPlanScoreByPidArr($finish_planids);
						if(!empty($score_ret->data->user_total)){
							$avg_score = floor(($score_ret->data->score_count/$score_ret->data->user_total)*10)/10;
						}
						$comment_ret = comment_api::checkIsCommentByPlanId($finish_planids,$uid);
						
						if(!empty($comment_ret->result->items)){
							foreach($comment_ret->result->items as $comment){
								$user_comment[$comment->fk_plan] = $comment;
							}
						}
					}elseif(!empty($course_info) && $course_info->course_type == 3 && !empty($offline_finish)){
						$score_ret = message_api::getPlanScoreByPidArr($offline_finish);
						if(!empty($score_ret->data->user_total)){
							$avg_score = floor(($score_ret->data->score_count/$score_ret->data->user_total)*10)/10;
						}
						$comment_ret = comment_api::checkIsCommentByPlanId($offline_finish,$uid);
						if(!empty($comment_ret->result->items)){
							foreach($comment_ret->result->items as $comment){
								$user_comment[$comment->fk_plan] = $comment;
							}
						}
					}
				}

				//获取下载课件资料
				$attach = course_api::listPlanAttach(array('classId'=>$class_id));
				if(!empty($attach) && !empty($attach->data)){
					$attach_data = $attach->data;
					foreach($attach_data as $ao){
						$fidArr[] = '\''.$ao->attach.'\'';
					}
					$file = utility_file::getFileByFidArr($fidArr);
					if(!empty($file)){
						foreach($file as $fo){
							$file_data[$fo->fid] = floor(($fo->size/1024)*10)/10;
						}
					}
				}			
				$first_show  = 0;
				$second_show = 0;
				if(!empty($class_detail['curr_sname']) && $class_detail['course_type'] == 1){
					$first_show = 1;
				}elseif(!empty($attach_data)){
					$second_show =1;
				}
				$setIdArr  = course_api::getCourseOpenMemberSetIdArr($course_id);
				$memberRet = org_member_api::checkIsMemberOrExpire($uid, $setIdArr, $course_id);
				$memberRole = 1;
				if( $memberRet['isMemberRegType'] == 1 ){
					if($memberRet['isMember'] == 0){
						$memberRole = 0;
					}elseif($memberRet['isExpire'] == 1){
						$memberRole = 0;
					}
				}
				foreach($course_plan as $plan){
					$sret = comment_api::getSingleCommentScore((int)($plan->course_id), $this->user['uid'], $plan->plan_id);
					$code = json_decode($sret,true)['result']['code'];
					if($code==0) $user_comment[$plan->plan_id] =$plan->plan_id;
					if($code != 0) $user_comment[$plan->plan_id] = '';
				}
				$this->assign("userStatTotal",$userStatTotal);
				$this->assign('courseInfo',$course_info);
				$this->assign('memberRole',$memberRole);
				$this->assign('memberRet',$memberRet);
				$this->assign('user_comment',$user_comment);
				$this->assign('first_show',$first_show);
				$this->assign('second_show',$second_show);
				$this->assign('study_flag',$study_flag);
				$this->assign('file_data',$file_data);
				$this->assign('attach_count',count($attach_data));
				$this->assign('attach_data',$attach_data);
				$this->assign('avg_score',$avg_score);
				$this->assign('good_count',$good_count);
				$this->assign('sort',$sort);
				$this->assign('right_count',$right_count);
				$this->assign('right_percent',$right_percent);
				$this->assign('curr_date',$curr_date);
				$this->assign('planlist',$course_plan);
				$this->assign('class_detail',$class_detail);
				$this->assign('user_id',$uid);
				$this->assign('course_id',$course_id);
				$this->assign('class_id',$class_id);
				$this->assign('owner_id',$this->orgOwner);
				$this->render('/student/course.detail.html');
			}else{
            	$this->redirect("/course.main.404");
			}
		}else{
            $this->redirect("/course.main.404");
		}	
	}
	
	public function pageGetCommentAndScore(){
		$params = $_POST;
		if (empty($this->user)) return interface_func::setMsg(1021);
		if (empty($params)) return interface_func::setMsg(1000);
		if (empty($this->user['uid'])){
			return interface_func::setMsg(1021);
		} 
		if (empty($params['course_id']) || !(int)($params['course_id'])){
			return interface_func::setMsg(1000);
		}
		if (empty($params['plan_id']) || !(int)($params['plan_id'])){
			return interface_func::setMsg(1000);
		}
		$commentRet = comment_api::getCommentAndScore($params['course_id'],$this->user['uid'],$params['plan_id']);
		return interface_func::setData($commentRet);
	}	
	
/*	public function pageAddCommentScore($inPath){
		if (empty($this->user)) return interface_func::setMsg(1021);
		if (empty($_POST)) return interface_func::setMsg(1000);

		if (empty($this->user['uid'])) return interface_func::setMsg(1021);
		$_POST['comment'] = strip_tags($_POST['comment']);
		if(empty($_POST['comment'])){
			return interface_func::setMsg(4003);
		}
		//检测是否评论
		if (empty($_POST['course_id']) || !(int)($_POST['course_id']))
			return interface_func::setMsg(1000);

		if (comment_api::checkIsComment((int)($_POST['course_id']), $this->user['uid'], (int)($_POST['plan_id']))) {
			return interface_func::setMsg(2043);
		}

		$re_comment = comment_api::addComment($_POST, $this->user["uid"]);
		$re_detail = comment_api::addDetail($_POST, $this->user["uid"]);
		$res = comment_api::addUserExperience($this->user["uid"]);
		if ($res) return interface_func::setData($res);
		return interface_func::setMsg(1);
	}*/
	
	
	//获取用户已经学习的章节
	public function getStudyPlan($planids){
		$uid = $this->user['uid'];
		$study_flag = array();
		$study = stat_api::getUserPlanStatByPidArr($uid,$planids);
		if(!empty($study)){
			foreach($study as $so){
				$study_plan[] = $so->fk_plan;
				$study_time[$so->fk_plan] = $so->vt_live+$so->vt_record;
			}
			foreach($planids as $pid){
				if(in_array($pid,$study_plan)){
					$study_flag[$pid] = 1;
				}else{
					$study_flag[$pid] = 0;
				}
			}
		}
		return $study_flag;
	}
	
	public function pageStat($inPath){
		$pid = !empty($inPath[3]) ? $inPath[3] : 0;
		$uid = $this->user['uid'];
		//$uid = 149;
		if(!empty($pid) && is_numeric($pid)){
			$plan_data = course_api::getPlanByPid($pid);
			if(!empty($plan_data) ){
				$check = course_api::checkUserRegisterCourse($plan_data->course_id,$plan_data->class_id,$this->user['uid']);
				if($check->result->code == 0 && !empty($check->result->data)){
					$course_ret  = course_api::getCourseOne($plan_data->course_id);

					$good_count = 0;
					$sort = 0;
					$good_ret = message_api::getPlanGoodByPidArr(array($pid));
					if(!empty($good_ret) && !empty($good_ret->data)){
						$good_data = $good_ret->data;
						foreach($good_data as $go){
							$good_key[] = $go->num;
						}
						array_multisort($good_key,SORT_DESC,$good_data);
						foreach($good_data as $kk=>$gg){
							if($gg->fk_user == $uid){
								$sort = $kk+1;
								$good_count = $gg->num;
								break;
							}
						}				
						if($sort == 0){
							$sort = count($good_data)+1;
						}
					}
					//获取用户答题统计
					$question_count = 0;
					$right_count = 0;
					$wrong_count = 0;
					$no_count = 0;
					$question_data = array();
					$user_question_data = array();
					$user_right_question = array();
					$user_wrong_question = array();
					$user_no_question = array();
					$all_question = course_api::getPlanQuestionByPid($pid);	
					if(!empty($all_question) && !empty($all_question->data)){
						$question_data = $all_question->data;
					}

					$user_question = exam_api::getLogUserQuestionByPid($uid,$pid);	
					if(!empty($user_question) && !empty($user_question->data)){
						$user_right = array();
						$user_wrong = array();
						$user_question_data = $user_question->data;
						foreach($user_question_data as $vo){
							if($vo->correct == 1){
								$user_right[$vo->fk_question]['options'] = $vo->options;
							}	
							if($vo->correct == 0){
								$user_wrong[$vo->fk_question]['options'] = $vo->options;
							}
						}
						foreach($question_data as &$qo){
							$qo->options = '';
							$qo->correct = '';
							if(!empty($user_right[$qo->fk_question])){
								$qo->options = $user_right[$qo->fk_question]['options'];
								$qo->correct = 'right';
								$user_right_question[] = $qo;
							}elseif(!empty($user_wrong[$qo->fk_question])){
								$qo->options = $user_wrong[$qo->fk_question]['options'];
								$qo->correct = 'wrong';
								$user_wrong_question[] = $qo;
							}else{
								$user_no_question[] = $qo;
							}	
						}
					}else{
						$user_no_question = $question_data;
					}
					$question_count = count($question_data);
					$right_count = count($user_right_question);
					$wrong_count = count($user_wrong_question);
					$no_count = count($user_no_question);

					$this->assign('good_count',$good_count);
					$this->assign('sort', $sort);
					$this->assign('wrong_count',$wrong_count);
					$this->assign('question_count',$question_count);
					$this->assign('right_count',$right_count);
					$this->assign('no_count',$no_count);
					$this->assign('question_data',$question_data);
					$this->assign('user_right_question',$user_right_question);
					$this->assign('user_wrong_question',$user_wrong_question);
					$this->assign('user_no_question',$user_no_question);
					$this->assign('course_ret',$course_ret);
					$this->render('/student/course.stat.html');

				}	
			}
		}
	}

	public function getLivingPlan($my_courseid_arr,$my_classid_arr,$page=1,$size){
		$start_time = date('Y-m-d', time()).' 00:00:00';
        $end_time   = date('Y-m-d H:i:s', strtotime('next year'));
        $status = '1,2,3';
        $order = array('start_time'=>'asc');
        $livingPlan = $this->getPlanList($page,$my_courseid_arr,$my_classid_arr,$status,$start_time,$end_time,$order,$size);
		return $livingPlan;
	}

	public function handlePlan($plan_info, $curr_date, $course_info){
  		if( !empty($plan_info) ) {
			$owner_arr = array();
        	foreach($plan_info as $pv){
            	$start_date = date('n.j',strtotime($pv->start_time));
                $start_hour = date('H:i',strtotime($pv->start_time));
				$month = Slanguage::tr(date('n',strtotime($pv->start_time)).'月','site.teacher');
                $pv->start_date = $start_date;
                $pv->start_day = $start_date;
                $pv->start_month = $month;
                $pv->start_hour = $start_hour;
				if(!empty($course_info[$pv->course_id])){
               	 	$pv->thumb_sma  = utility_cdn::file($course_info[$pv->course_id]->thumb_sma);
                	$pv->fee_type   = $course_info[$pv->course_id]->fee_type;
                	$pv->fee_class  = $course_info[$pv->course_id]->fee_class;
                	$pv->section_count  = $course_info[$pv->course_id]->section_count;
				}else{
               	 	$pv->thumb_sma  = '';
                	$pv->fee_type   = '';
                	$pv->fee_class  = '';
                	$pv->section_count  = 0;
				}
                //$temp[$pv->start_date][] = $pv;
                $temp[$pv->start_month][$pv->start_date][] = $pv;
				$owner_arr[$pv->owner_id] = $pv->owner_id;
            }
			$video_type_arr = array(0,-2);
			foreach($temp as $k=>$v){
				foreach($v as $kk=>$vv){
					foreach($vv as $kkk=>$vvv){
						$smonth = date('n',strtotime($vvv->start_time)).'-';
						$sday = date('j',strtotime($vvv->start_time));
						$start_date = $vvv->start_date;
						if($kk == $curr_date){
							if($kkk == 0){
								$vvv->start_date = '<p class="vertical-line-date">'.SLanguage::tr('今日','site.teacher').'</p><p>'.$vvv->start_hour.'</p>';
							}else{
								$vvv->start_date = '<p class="vertical-line-time">'.$vvv->start_hour.'</p>';
							}
						}else{
							if($kkk == 0){
								$vvv->start_date = '<p class="vertical-line-date">'.$smonth.$sday.'</p><p>'.$vvv->start_hour.'</p>';
							}else{
								$vvv->start_date = $vvv->start_hour;
							}
						}
                    	//$vvv->course_url = '/student.course.detail/'.$vvv->course_id.'/'.$vvv->class_id;
						$vvv->course_url = '/course.info.show/'.$vvv->course_id;
                    	$vvv->plan_url = '/course.plan.play/'.$vvv->plan_id;
                    	$vvv->teacher_url = '/teacher.detail.entry/'.$vvv->teacher_id;
                    	$vvv->plan_stat_url = '/student.course.stat/'.$vvv->plan_id;
						$vvv->org_name   = $vvv->org_subname;
						$vvv->org_url = user_organization::course_domain($vvv->subdomain);
						if(!empty($vvv->teacher_real_name)){
							$vvv->teacher_name = $vvv->teacher_real_name;
						}
						$vvv->plan_button = '';
						if($curr_date == $start_date){
                     		if($vvv->status != 3){
								$vvv->plan_button = "<a href='{$vvv->plan_url}' target='_blank'><button class='come-look-btn'>".SLanguage::tr('进入课堂','LearningCenter')."</button></a>";
                     		}
							if($vvv->status == 3 && $vvv->video_public_type != -2 ){
								$vvv->plan_button = "<a href='{$vvv->plan_url}' target='_blank'><button class='look-btn' >".SLanguage::tr('直播回看','LearningCenter')."</button></a>";
												//"<p class='class-stat'><a data-pid = '{$vvv->plan_id}'  href='javascript::void(0);'>课堂统计</p>";
							}
                 		}else{
                     		if($vvv->status == 1 || $vvv->status == 2){
                         		$vvv->plan_button="<p class='margin-top:14px;'>".SLanguage::tr('未开课','LearningCenter')."</p>";
                     		}elseif($vvv->status == 3 && $vvv->video_public_type != -2){
								$vvv->plan_button = "<a href='{$vvv->plan_url}' target='_blank'><button class='look-btn'>".SLanguage::tr('直播回看','LearningCenter')."</button></a>";
								//"<p class='class-stat'><a data-pid='{$vvv->plan_id}' href=javascript::void(0);'>课堂统计</p>";
							}
                 		}
						$vvv->section_name = SLanguage::tr($vvv->section_name,'site.index');
						$vvv->class_name = SLanguage::tr($vvv->class_name,'site.index');
						$planlist[$k][] = $vvv;
					}
				}
			}
        }else{
			$planlist = array();
		}
		return $planlist;
	}


	public function getCourseInfo( $my_courseid_arr, $search_cname='' ){

		$f_array = array(
                  "course_id","title","thumb_big","thumb_med","thumb_sma","course_type",
                  "user_id","public_type","fee_type","price","status","admin_status",
                  "class_id","start_time","end_time","class","avg_score",
        		);
		if(!empty($search_cname)){
			$q_array=array(
                  'search_field'=> $search_cname,
                  'course_id'=> implode(',', $my_courseid_arr),
        		);
		}else{
			$q_array=array(
                  'course_id'=> implode(',', $my_courseid_arr),
        		);
		}
        $seek_arr = array(
                  "f"=>$f_array,
                  "q"=>$q_array,
                  "ob"=>array('start_time'=>'asc'),
                  "p"=>1,
                  "pl"=>1000,
        		);
        $ret_seek = seek_api::seekcourse($seek_arr);
		$course_info = array();
		if(!empty($ret_seek->data)){
        	$ret_course = $ret_seek->data;
        	foreach($ret_course as $cv){
        		$course_info[$cv->course_id] = $cv;
            	if( $cv->fee_type == 0 ) {
            		$course_info[$cv->course_id]->fee_type = SLanguage::tr('免费','site.teacher');
					$course_info[$cv->course_id]->fee_class = 'cGreen';
            	}else{
                	$course_info[$cv->course_id]->fee_type = '￥'.$cv->price/100;
					$course_info[$cv->course_id]->fee_class = 'cYellow';
           		}
        	}
		}
		return $course_info;
	}

	public function getPlanList($page,$my_courseid_arr,$my_classid_arr,$status,$start_time,$end_time,$order,$size){

		$plan_condition = array(
                  'course_id'=>implode(',', $my_courseid_arr),
                  'class_id' =>implode(',', $my_classid_arr),
                  'status'   =>$status,
                  'start_time'=>"$start_time,$end_time",
				  'course_type' => 1,
              );
        $planArr=array(
                    "f"=>array(
                          'course_id',
                          'plan_id',
                          'class_id',
                          'teacher_id',
						  'admin_id',
						  'owner_id',
                          'section_id',
                          'course_name',
                          'class_name',
                          'section_name',
                          'teacher_name',
                          'teacher_real_name',
						  'admin_name',
						  'admin_real_name',
                          'start_time',
                          'end_time',
                          'max_user',
                          'user_total',
						  'video_public_type',
						  'course_type',
                          'status',
						  'subdomain',
						  'org_subname',
                          ),
                     "q" =>$plan_condition,
                     "ob"=>$order,
                     "p" =>$page,
                     "pl"=>$size,
                );
        $seek_plan=seek_api::seekPlan($planArr);
		if(!empty($seek_plan->data)){
			return $seek_plan->data;
		}else{
			return array();
		}	
	}

	public function getCoursePlanList($my_courseid_arr,$my_classid_arr){

		$plan_condition = array(
                  'course_id'=>implode(',', $my_courseid_arr),
                  'class_id' =>implode(',', $my_classid_arr),
                  'status'   =>'1,2,3',
              );
        $planArr=array(
                    "f"=>array(
                          'course_id',
                          'plan_id',
                          'class_id',
                          'teacher_id',
						  'admin_id',
						  'owner_id',
                          'section_id',
                          'course_name',
                          'class_name',
						  'region_level0',
						  'region_level1',
						  'region_level2',
						  'address',
                          'section_name',
                          'section_desc',
                          'teacher_name',
                          'teacher_real_name',
						  'teacher_thumb_sma',
						  'teacher_thumb_med',
						  'teacher_thumb_big',
						  'admin_name',
						  'admin_real_name',
						  'admin_thumb_sma',
						  'admin_thumb_med',
						  'admin_thumb_big',
						  'video_public_type',
                          'start_time',
                          'end_time',
                          'max_user',
                          'user_total',
                          'video_id',
                          'totaltime',
						  'course_type',
                          'status',
						  'subdomain',
						  'org_subname',
                          ),
                     "q" =>$plan_condition,
                     "ob"=>array('start_time'=>'asc'),
                     "p" =>1,
                     "pl"=>10000,
                );
        $seek_plan=seek_api::seekPlan($planArr);
		if(!empty($seek_plan->data)){
			return $seek_plan->data;		
		}else{
			return '';		
		}
	}






}
