<?php

class interface_planApi
{

    public static function getPlanExam($planId, $userId)
    {
        $res = course_api::getPlanExamsByPlan($planId, $userId);

        return $res;
    }

    /**
     * 2016-03-30 老师身份课表，取该老师为主讲老师和代讲老师的课程章节
     */
    public static function getTable($start, $end, $uid)
    {
        $identity = interface_user_api::getIdentity($uid);
        if ($identity === false) return [];


        $query = [
            'status'      => '1,2,3',
            'course_type' => '1,3',
            'start_time'  => "{$start},{$end}"
        ];

        $params = [
            'f'  => [
                'plan_id',
                'course_id',
                'class_id',
                'start_time',
                'status'
            ],
            'q'  => $query,
            'ob' => ['start_time' => 'asc'],
            'p'  => 1,
            'pl' => 10000,
        ];

        $courseIdArr = $classIdArr = [];
        if ($identity) {
            // 当身份存在（为老师身份时）时,需要取出该老师报名的所有课程id为条件＋该讲师或者班主任的当前月时间段
            $query['teacher_id'] = $uid; //讲师
            $query['admin_id']   = $uid; // 班主任

            $params['q'] = $query;
            $res = seek_api::seekPlan($params);
            $masterCourseIdArr = $masterClassIdArr = [];
            if (!empty($res->data)) {
                foreach ($res->data as $master) {
                    $masterCourseIdArr[$master->course_id] = $master->course_id;
                    $masterClassIdArr[$master->class_id]   = $master->class_id;
                }
            }

            $userRegCourseList = interface_user_api::getUserRegCourse($uid);
            if (!empty($userRegCourseList['items'])) {
                $identityTeacherRegCourseIdArr = array_column($userRegCourseList['items'], 'fk_course');
                $classIdArr                    = array_column($userRegCourseList['items'], 'fk_class');
                // 作为老师 我报名的课程
                $courseIdArr        = array_unique(array_merge($masterCourseIdArr, $identityTeacherRegCourseIdArr));
                $classIdArr         = array_unique(array_merge($masterClassIdArr, $classIdArr));
                $query['course_id'] = implode(',', $courseIdArr);

                // query course_id 和 teacher_id,admin_id互斥
                unset($query['teacher_id']);
                unset($query['admin_id']);
            }

            $params['q'] = $query;
            $res = seek_api::seekPlan($params);
        } else {
            // 当身份不存在（默认为学生身份）时，取出当前月时间段的plan,筛选出课程courseId数组，在取出该学生部分报名课程（不需要一次请求全部取出该学生所有报名课程）
            $res = seek_api::seekPlan($params);
            if (!empty($res->data)) {
                foreach ($res->data as $item) {
                    $courseIdArr[$item->course_id] = $item->course_id;
                }
            }
            // 获取学生报名课程
            $userRegCourseList = interface_user_api::getUserRegCourse($uid, $courseIdArr);
        }

        // 合并老师,学生
        if (!empty($userRegCourseList['items'])) {
            $regCourseIdArr = array_column($userRegCourseList['items'], 'fk_course');
            $regClassIdArr  = array_column($userRegCourseList['items'], 'fk_class');
            if ($identity) { // 老师身份进行合并
                $courseIdArr = array_unique(array_merge($regCourseIdArr, $courseIdArr));
                $classIdArr  = array_unique(array_merge($regClassIdArr, $classIdArr));
            } else { // 学生身份只取报名
                $courseIdArr = $regCourseIdArr;
                $classIdArr  = $regClassIdArr;
            }
        }

        if(empty($res->data)) return array();
		foreach($res->data as $val){
			$planIdArr[] = $val->plan_id;
		}
		$statusArr = array();
		$statusName = array('normal'=>'1','living'=>2,'finished'=>3,'-1'=>'invalid','0'=>'initial');
		$status = course_api::getPlanStatusV2($planIdArr);
		if(!empty($status)){
			foreach($status as $key=>$val){
				$statusArr[$key] = $statusName[$val->plan_status];
			}
		}

        foreach ($res->data as $k => &$item) {
			//临时方法 (解决status 延迟)
			$item->status = !empty($statusArr[$item->plan_id]) ? $statusArr[$item->plan_id] : 0;
			
			$item->student[$item->plan_id] = 1; //默认学生身份标识符
            // 报本班，学生报名（老师报名）
            if (in_array($item->course_id, $courseIdArr) && in_array($item->class_id, $classIdArr)) {
                if ((!empty($masterCourseIdArr) && in_array($item->course_id, $masterCourseIdArr)) || !empty($identityTeacherCourseIdArr)) {
                    $item->student[$item->plan_id] = 0; // 作为老师主讲和代讲的课程标识符为0
                }
            } else {
                unset($res->data[$k]);
            }
        }

        return $res->data;
    }

    public static function getUploadList($uid, $planIdStr)
    {
        if (!$planIdStr) return [];
        $params = [
            'userId'    => $uid,
            'planIdStr' => $planIdStr
        ];

        $res = utility_services::call('/live/file/GetUploadList/', $params);
        if (!empty($res->code)) return [];

        $data = [];
        foreach ($res->result as $item) {
            $data[$item->fk_plan] = [
                'name'       => pathinfo($item->filename)['filename'],
                'duraction'  => utility_tool::sec2time($item->duration),
                'size'       => utility_tool::formatSizeUnits($item->filesize),
                'status'     => $item->status,
                'rate'       => 100,
            ];
        }

        return $data;
    }

    public static function getVideoList($videoIdArr)
    {
        if (count($videoIdArr) < 1) return [];
        $params = [
            'videoIdArr' => $videoIdArr
        ];

        $res = utility_services::call('/video/info/GetListByVideoArr/', $params);
        if (!empty($res->code)) return [];

        $data = [];
        foreach ($res->result->items as $item) {
            $data[$item->pk_video] = [
                'duraction'  => utility_tool::sec2time($item->totaltime),
            ];
        }

        return $data;
    }

    public static function getPlanDownLoadUrl($planId)
    {
        if (!(int)$planId) return [];
        $data = new player_data($planId);
        $data->getPlan();
        $data->getUserInfo();
        $data->getChart();
        $data->getPlayLog();

        if (empty($data->hls_v2) || empty($data->hls_v2->detail)) return [];
        $host = $data->hls_v2->url;

        $result = [];
        foreach ($data->hls_v2->detail as $item) {
            $result[] = [
                'clear' => $item->name,
                'url' => "{$host}{$item->stream}?{$item->key_name}={$item->key_value}"
            ];
        }

        return $result;
    }

    public static function getLives($userId=0, $orgId=0){
        $data = array();

        //报名的课程
        $courseIdArr = array();
        if(!empty($userId)){
            //$orgInfo = user_api::getOrgByid($orgId);
            $ownerId = 0;//!empty($orgInfo->fk_user_owner) ? $orgInfo->fk_user_owner : 0;
            $myCourse = interface_user_api::getUserRegCourse($userId,array(),1,-1,$ownerId);
            if(!empty($myCourse['items'])){
                $courseIdArr = array_column($myCourse['items'],'fk_course');
            }
        }

        //即将直播
        //$soonTime = date('Y-m-d 00:00:00').','.date('Y-m-d 23:59:59',strtotime('+5 days'));
        $soonTime = date('Y-m-d 00:00:00').','.date('Y-m-d 00:00:00',strtotime('+6 days'));
        $soonParams = [
            'q' => ['start_time'=>$soonTime,'status'=>'1,2,3','admin_status'=>1,'course_type'=>1,'expression' => "@resell_org_id =".$orgId." | @org_id=".$orgId],
            'f' => [
                'plan_id','course_id','start_time','course_name','class_name','section_name',
                'status','subdomain','course_thumb_big','course_thumb_med','course_thumb_small',
                'try','class_id'
            ],
            'p' => 1,
            'pl'=> 20,
            'ob'=> ['start_time'=>'asc']
        ];
        /*
        if(!empty($orgId)){
            $soonParams['q']['org_id'] = $orgId;
        }
        */
        $soonPlanRes = seek_api::seekPlan($soonParams);
        /*$soonNum     = !empty($soonPlanRes->data) ? count($soonPlanRes->data) : 0;

        //精彩回放
        $backNum = 6 - $soonNum;
        $records = array();
        if($soonNum < 6){
            $backTime = date('Y-m-d 00:00:00',strtotime('-1 days')).','.date('Y-m-d 23:59:59');
            $backParam = [
                'q' => ['start_time'=>$backTime,'status'=>'3','admin_status'=>1,'course_type'=>1,'expression' => "@resell_org_id =".$orgId." | @org_id=".$orgId],
                'f' => [
                    'plan_id','course_id','start_time','course_name','class_name','section_name',
                    'status','subdomain','course_thumb_big','course_thumb_med','course_thumb_small',
                    'try','class_id'
                ],
                'p' => 1,
                'pl'=> $backNum,
                'ob'=> ['start_time'=>'asc']
            ];
            $backPlanRes = seek_api::seekPlan($backParam);
            $records     = $backPlanRes->data;
        }
		*/
        $mergeData = $soonPlanRes->data;
        if(empty($mergeData)) return $data;

        foreach($mergeData as $v){
            $d = date('n-j', strtotime($v->start_time));
            $data[$d]['time'] = $d;
            $data[$d]['list'][] = [
                'isSign'      => (!empty($courseIdArr) && in_array($v->course_id, $courseIdArr)) ? 1 : 0,
                'courseId'    => $v->course_id,
                'planId'      => $v->plan_id,
                'className'   => $v->class_name,
                'classId'     => $v->class_id,
                'trySee'      => $v->try,
                'courseName'  => $v->course_name,
                'imgurl'      => interface_func::imgUrl($v->course_thumb_med),
                'sectionName' => $v->section_name,
                'status'      => $v->status,
                'stime'       => date('H:i', strtotime($v->start_time))
            ];
        }

        $data = array_values($data);
        return $data;
    }
}
