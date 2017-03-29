<?php
class course_plan_api{
	/**
	 * 开始上课
	 */
	public static function startPlan($uid,$token,$plan_id){
		//关联用户直播token和planid
		$ret = live_publish::setPlan($uid, $token, $plan_id,$cleanFile=false);
		if($ret){
			//修改plan直播状态
			course_api::updatePlanStatus($plan_id,"living");
			//发送信号
			message_api::startCloseClass($plan_id, $uid, $token, true);
		}
		return $ret;
	}
	/**
	 * 下课
	 */
	public static function stopPlan($uid,$token,$plan_id){
		//设置直播的plan id为0
		$ret = live_publish::setPlan($uid, $token, 0);
		//关闭直播流
		if($ret){
			//关闭直播流
			live_publish::close($uid);
			//修改plan 状态 
			course_api::updatePlanStatus($plan_id,"finished");
			//发送下课信号
			message_api::startCloseClass($plan_id, $uid, $token, false);
		}
		return $ret;
	}

    public static function getCourseRegUser($courseId, $owner=0, $page=1, $length=0)
    {
        $params = [
            'courseId' => $courseId,
            'owner'    => $owner,
            'page'     => $page,
            'length'   => $length
        ];
        $res = utility_services::call('/course/courseuser/GetCourseRegUser/', $params);

        $data = [];
        if (!empty($res->result)) {
            foreach ($res->result as $item) {
                $data[] = [
                    'name' => utility_tool::replaceStr($item->name),
                    'thumb' => interface_func::imgUrl($item->thumb_big)
                ];
            }
        }

        return $data;
    }

    public static function getScoreInfo($courseId)
    {
        $res = comment_api::getTotal(array('course_id' => $courseId));
        if (!empty($res)) {
            $student_score = (int)ceil($res[0]->student_score / $res[0]->total_user);
            $desc_score    = (int)ceil($res[0]->desc_score / $res[0]->total_user);
            $explain_score = (int)ceil($res[0]->explain_score / $res[0]->total_user);
            $avg_score     = round($res[0]->avg_score);

            return [
                'studentScore' => $student_score > 5 ? 5 : $student_score,
                'descScore'    => $desc_score > 5 ? 5 : $desc_score,
                'explainScore' => $explain_score > 5 ? 5 : $explain_score,
                'avgScore'     => $avg_score > 5 ? 5 : $avg_score
            ];
        }

        return [];
    }
	
	public static function hasVideo($planIdArr)
	{
		$params     = new stdclass;
		$params->planIdArr = $planIdArr;
		$ret = utility_services::call("/course/plan/hasvideo/",$params);
		return $ret;
	}
	
	public static function getPhraseByType($type)
	{
		$ret = utility_services::call("/course/plan/questionType/{$type}");
		return $ret;
	}

    public static function getGetCoursePlanByPid($pid){
        $ret = utility_services::call("/course/courseplan/GetCoursePlanByPid/{$pid}");
        return $ret;
    }
    
	public static function getPlanList($params)
	{            
            $items = array();
            $res = utility_services::call("/course/plan/getPlanList/", $params);

            if (!empty($res->data)) {
                $retDataOri = $res->data;
                $retDataItemsOri = $res->data->items;      
                $count = $res->data->pageSize;      
                for ($i = 0; $i < $count; $i++) {
                    $itemOri = $retDataItemsOri[$i];
                    $item = new stdclass();
                    $item->plan_id      = $itemOri->pk_plan;
                    $item->status       = $itemOri->status;
                    $item->user_id      = $itemOri->fk_user;
                    $item->plan_user_id = $itemOri->fk_user_plan;
                    $item->course_id    = $itemOri->fk_course;
                    $item->section_id   = $itemOri->fk_section;
                    $item->class_id     = $itemOri->fk_class;
                    $item->section_id   = $itemOri->fk_section;
                    $item->video_id     = $itemOri->fk_video;
                    $item->start_time   = $itemOri->start_time;
                    $item->end_time     = $itemOri->end_time;
                    $item->live_public_type     = $itemOri->live_public_type;
                    $item->video_public_type    = $itemOri->video_public_type;
                    $item->video_trial_time     = $itemOri->video_trial_time;
                    $items[] = $item;
                }
                $res->data->items = $items;
            } 
            return $res;
	}
        public static function getCourseUserList($params)
	{      
            $items = array();
            $res = utility_services::call("/course/courseuser/GetCourseUserList/", $params);

            if (!empty($res->data)) {
                $retDataOri = $res->data;
                $retDataItemsOri = $res->data->items;      
                $count = $res->data->pageSize;      
                for ($i = 0; $i < $count; $i++) {
                    $itemOri = $retDataItemsOri[$i];
                    $item = new stdclass();
                    $item->course_id    = $itemOri->fk_course;
                    $item->user_id      = $itemOri->fk_user;
                    $item->class_id     = $itemOri->fk_class;
                    $items[] = $item;
                }
                $res->data->items = $items;
            } 
            return $res;
        }
        
        /* 获取即将开课的排课
         * $dataSource ： seek 中间层 ， 'mysql' 数据库
         *  */
        public static function getUserWillBeginPlanClassIds($dataSource='seek')
	{
            $result = new stdClass();
            $classIds = '';
            $willBeginClassPlan = array();    
            if ($dataSource == 'seek') {    
                /* 从中间层 获取正在进行中&即将开始的排课 */
                $planList = course_plan_api::getPlanListData();                 
            } else {
                /* 从DB 获取即将开课的排课 */
                $params = array(
                    'condition'=>"status=2 or (status=1 and start_time < date_add(now(), interval +10 minute) and end_time > SYSDATE())",
                    'order'=>''
                );
                $planList = course_plan_api::getPlanList($params);     
            }
            $aheadTime = date("Y-m-d H:i:s",strtotime('+10 minutes'));
            $nowTime = date("Y-m-d H:i:s");
            $willBeginPlanListCount = $planList->data->pageSize;
            $willBeginPlanList = $planList->data->items;
            for ($i = 0; $i < $willBeginPlanListCount; $i++) {
                $classIds .= $willBeginPlanList[$i]->class_id.',';
                $status = $willBeginPlanList[$i]->status;
                $start_time = $willBeginPlanList[$i]->start_time;
                $end_time = $willBeginPlanList[$i]->end_time;
                if ($status==2 || ($status==1 && $start_time <$aheadTime && $end_time>=$nowTime)){
                    $willBeginClassPlan[$willBeginPlanList[$i]->class_id]['plan_id'] = $willBeginPlanList[$i]->plan_id;
                    $willBeginClassPlan[$willBeginPlanList[$i]->class_id]['status'] = $willBeginPlanList[$i]->status;
                }
            }
            $classIds = substr($classIds, 0,-1); 
            
            $result->classIds = $classIds;
            $result->willBeginClassPlan = $willBeginClassPlan;
     
            return $result;
        }        
        
        // 获取课程名称，章节名称，班级等详细信息
        public static function getUserWillBeginPlanListData($UserRegListObj,$classPlanArr,$needCount=0)
	{
            $result = array();
            $planInfoArr = array('0'=>'即将开课','1'=>'即将开课','2'=>'正在上课','3'=>'已下课','-1'=>'未开课');
            $seekParams = [
                'q' => '',
                'f' => [ 
                    'plan_id','subdomain', 'course_id','course_name','section_id','section_name',
                    'class_id','class_name','start_time','end_time','status','section_desc','course_thumb_med'
                ],
                'ob'=> ['start_time'=>'asc'],
                'p' => 1,
                'pl'=> 5
            ];     

            $userRegListCount = $UserRegListObj->data->pageSize;
            if(!empty($needCount)) $userRegListCount = min($userRegListCount,$needCount);
            $userRegList = $UserRegListObj->data->items;
          
            for ($j = 0; $j < $userRegListCount; $j++) {
                $userRegInfo = $userRegList[$j];    
                if(!isset($userRegInfo->class_id)) continue;
                if(!isset($classPlanArr[$userRegInfo->class_id])) continue;
                $classArr = $classPlanArr[$userRegInfo->class_id];
                $planId = $classArr['plan_id'];  
                $status = $classArr['status'];  
                
                $seekParams['q'] = ['plan_id' => $planId];                   
                $seekPlanRes = seek_api::seekPlan($seekParams);            
                $seekPlanInfo = $seekPlanRes->data[0]; 
                
                $seekPlanInfo->subdomain = $subdomain = user_organization::course_domain($seekPlanInfo->subdomain);
                $seekPlanInfo->plan_status_info = $planInfoArr[$status];   
                $seekPlanInfo->plan_start_time = date("H:i",strtotime($seekPlanInfo->start_time)); 
                $seekPlanInfo->plan_end_time = date("H:i",strtotime($seekPlanInfo->end_time)); 
                $seekPlanInfo->url = '//'.$subdomain.'/course.plan.play/'.$planId; 
                
                $result[] = $seekPlanInfo;
            }
            
            return $result;
        }
        /* 获取正在进行中&即将开始的排课 */
        public static function getPlanListData(){
            $planList = new stdClass();
            $planList->data = new stdClass();
                    
            /* 从中间层获取正在进行中的排课 */
            $paramsOnGoing = ['status'=>2,'admin_status'=>1];
            $planOnGoingList = course_plan_api::getSeekPlanListData($paramsOnGoing);
            
            /* 从中间层获取即将开始的排课 */
            date_default_timezone_set("UTC");
            $startTime = date("Y-m-d 00:00:00").','.date('Y-m-d 23:59:59');
            $paramsWillBegin = ['status'=>1,'admin_status'=>1,'start_time'=>$startTime];
            $planWillBeginList = course_plan_api::getSeekPlanListData($paramsWillBegin);
                        
            $planList->data->page        = $planOnGoingList->page || $planWillBeginList->page;
            $planList->data->pageSize    = $planOnGoingList->total + $planWillBeginList->total;
            $planList->data->pagelength  = $planOnGoingList->pagelength + $planWillBeginList->pagelength;
            $planList->data->time        = $planOnGoingList->time + $planWillBeginList->time;
            $planList->data->items       = array_merge($planOnGoingList->data , $planWillBeginList->data);
            
            return $planList;
        }
        
        // 获取正在进行中的排课
        public static function getSeekPlanListData($params=[]){
            if (empty($params)) return false;
            foreach($params as $k=>$v){
                if($v=='' || $v==-1){
                    unset($params[$k]);
                }
            }
            $seekParams = [
                'q' => $params,
                'f' => [ 'plan_id', 'course_id','course_name','section_id','section_name','class_id','class_name','start_time','end_time','status'],
                'ob'=> ['start_time'=>'asc'],
                'p' => 1,
                'pl'=> 100
            ];

            $seekPlanRes = seek_api::seekPlan($seekParams);    
            return $seekPlanRes;
        }
		//获取用户已经学习的章节
		public static function getStudyPlan($uid,$planids){
			if(empty($uid) || empty($planids) )return array();
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


    /*
     * 获取公告
     * @param array()  fk_plan
     * */
    public static function GetAnnouncement($params)
    {
        $ret = utility_services::call("/course/announcement/GetAnnouncement",$params);
        return $ret;
    }

    /* 公告新增 修改
     * @param id 自增 存在跟新 不存在插入
     * @param status   1正常 -1删除
     * @param fk_plan  planId
     * @param content  公告内容
     * @param create_time
     * */
    public static function Announcement($params){
        $ret = utility_services::call("/course/announcement/Announcement",$params);
        return $ret;
    }


    /*
     * 删除公告
     * @param array()  fk_plan
     * */
    public static function AnnouncementDel($params){
        $ret = utility_services::call("/course/announcement/AnnouncementDelete",$params);
        return $ret;
    }

    public static function getPlanNumByTime($startTime,$endTime){
        $params = new stdClass();
        $params->startTime = $startTime;
        $params->endTime   = $endTime;
        $ret = utility_services::call("/course/plan/getPlanNumByTime",$params);
        if(empty($ret->code)){
            return $ret->result;
        }
        return array();
    }

    /*
     * @desc 获取排课/章节/班级信息根据课程id
     */
    public static function getJoinPlanByCourseId(array $courseIdArr){
        $params = new stdClass();
        $params->courseIdArr = $courseIdArr;
        $ret = utility_services::call("/course/plan/getJoinPlanByCourseId", $params);

        if(!empty($ret->code)) return array();
        foreach($ret->result as $value){
            $teacherArr[$value->fk_user_class] = $value->fk_user_class;
            $teacherArr[$value->fk_user_plan] = $value->fk_user_plan;
        }

        //获取老师信息
        $teacher = array();
        $teacherIds = implode(',', $teacherArr);
        $teacherParams = [
            'q' => ['teacher_id'=>$teacherIds],
            'f' => ['teacher_id','real_name']
        ];
        $seekTeacher = seek_api::seekTeacher($teacherParams);
        if(!empty($seekTeacher->data)){
            foreach($seekTeacher->data as $val){
                $teacher[$val->teacher_id] = $val->real_name;
            }
        }

        foreach($ret->result as &$v){
            $v->classUserName = !empty($teacher[$v->fk_user_class]) ? $teacher[$v->fk_user_class] : '';
            $v->teacherName   = !empty($teacher[$v->fk_user_plan]) ? $teacher[$v->fk_user_plan] : '';
        }

        return $ret->result;
    }

    /**
     * @desc 获取班级下的排课列表
     */
    public static function getPlanListByClassId($classId){
        if(empty($classId)) return false;

        $ret = utility_services::call("/course/plan/getPlanListByClassId/".$classId);
        if(empty($ret->code)) return $ret->result;

        return false;
    }

    /**
     * @desc 获取班级下排课数
     */
    public static function getPlanNumByClassIdArr($classIdArr){
        if(empty($classIdArr) || !is_array($classIdArr)){
            return false;
        }

        $params = new stdClass();
        $params->classIdArr = $classIdArr;
        $ret = utility_services::call("/course/plan/getPlanNumByClassIdArr", $params);
        if(empty($ret->code)) return get_object_vars($ret->result);

        return false;
    }

    /**
     * @desc  讲师下的课程列表(t_course_plan表中)
     * @param $teacherId
     * @param $page
     * @param $length
     * @return array
     */
    public static function getCourseListByTeacherId($teacherId, $page=1, $length=20){
        if(empty($teacherId)) return false;
        $ret = utility_services::call("/course/plan/getcourselistbyteacherid/$page/$length/$teacherId");
        return $ret;
    }

    /**
     * @desc 删除排课
     */
    public static function delPlanById($planId){
        if(empty($planId)) return false;

        $ret = utility_services::call("/course/plan/delPlanById/".$planId);
        if(empty($ret->code)) return true;

        return false;
    }
    
    /**
     * @desc  批量修改排课
     * @data array  
     */
    public static function batchSetPlan($data){
        if(empty($data) || !is_array($data)) return false;

        $ret = utility_services::call("/course/plan/batchSetPlan", $data);
        if(empty($ret->code)) return true;

        return false;
    } 
    
    /**
     * @desc 批量添加排课
     */
    public static function batchAddPlan($courseId, $classId, $data){
        if(empty($courseId) || empty($data) || !is_array($data)) return false;

        $ret = utility_services::call("/course/plan/batchAddPlan/".$courseId.'/'.$classId, $data);
        if(empty($ret->code)) return true;

        return false;
    }

    /**
     * @desc 获取课程下排课数
     */
    public static function getPlanNumByCourseId($courseId){

        $params = new stdClass();
        $params->courseId = (int)$courseId;
        $ret = utility_services::call("/course/plan/getPlanNumByCourseId", $params);
        if(empty($ret->code)) return $ret->result;

        return false;
    }

    /**
     * 添加排课
     */
    public static function add($data){
        if(empty($data)) return false;
        $ret = utility_services::call("/course/plan/add", $data);
        if(empty($res->code)) return true;

        return false;
    }
}
