<?php

class course_detailed
{
    public static function getCoursePlan($param)
    {
        $courseType = 1;
        if (isset($param['courseType'])) {
            $courseType = $param['courseType'];
        }

        $planArr = [
            'q'  => [
                'course_id' => $param['cid'],
                'status'    => '1,2,3'
            ],
            'f'  => [
                'course_name',
                'course_id',
                'class_name',
                'address',
                'region_level0',
                'region_level1',
                'region_level2',
                'class_id',
                'plan_id',
                'teacher_id',
                'admin_id',
                'teacher_name',
                'course_type',
                'admin_real_name',
                'teacher_real_name',
                'admin_name',
                'section_name',
                'section_desc',
                'start_time',
                'end_time',
                'status',
                'max_user',
                'user_total',
                'live_public_type',
                'video_public_type',
                'teacher_thumb_med'
            ],
            'ob' => ['start_time' => 'asc'],
            'pl' => 100
        ];

        $ret = seek_api::seekPlan($planArr);
        if (empty($ret->data)) {
            return [];
        }

        $data = [];
        foreach ($ret->data as $k => $v) {
            if ($v->admin_id != $v->teacher_id) {
                $v->section_desc .= '('.$v->teacher_real_name.SLanguage::tr('代讲', 'course.info').')';
            }

            $regionLevel0 = !empty(region_geo::$region[$v->region_level0]) ? region_geo::$region[$v->region_level0] : '';
            $regionLevel1 = !empty(region_geo::$region[$v->region_level1]) ? region_geo::$region[$v->region_level1] : '';
            $regionLevel2 = !empty(region_geo::$region[$v->region_level2]) ? region_geo::$region[$v->region_level2] : '';

            $data[$v->course_id][$v->class_id][$v->plan_id] = array(
                'planId'          => $v->plan_id,
                'sectionDesc'     => $v->section_desc.self::getTryIconOrPlanId($v->video_public_type, $v->live_public_type, '', $v->course_type),
                'sectionName'     => SLanguage::tr($v->section_name, 'site.index'),
                'startTime'       => $v->start_time,
                'endTime'         => $v->end_time,
                'planStatus'      => $v->status,
                'className'       => SLanguage::tr($v->class_name, 'site.index'),
                'classAddress'    => $regionLevel0.$regionLevel1.$regionLevel2.$v->address,
                'teacherName'     => $v->teacher_real_name,
                'adminName'       => $v->admin_real_name.' '.SLanguage::tr('主讲', 'course.list'),
                'adminId'         => $v->admin_id,
                'teacherId'       => $v->teacher_id,
                'classId'         => $v->class_id,
                'teacherThumb'    => $v->teacher_thumb_med,
                'videoPublicType' => $v->video_public_type,
                'tryPlayId'       => self::getTryIconOrPlanId($v->video_public_type, $v->live_public_type, $v->plan_id, $v->course_type)
            );

            $data[$v->course_id][$v->class_id][$v->plan_id]['sectionNum'] = count($data[$v->course_id][$v->class_id]);
            $plan[$v->course_id][$v->class_id][$v->status][]              = $v;

            // 获取班级下的课程状态
            if (!empty($plan[$v->course_id][$v->class_id][2])) { // 获取直播课程
                $total                                                               = $data[$v->course_id][$v->class_id][$v->plan_id]['sectionNum'];
                $data[$v->course_id][$v->class_id][$v->plan_id]['progress']          = SLanguage::tr('课程进度', 'course.info')."(1/{$total})";
                $data[$v->course_id][$v->class_id][$v->plan_id]['courseClassStatus'] = self::getCourseClassStatus(2, $v->max_user, $v->user_total, $courseType);
            } elseif (
                !empty($plan[$v->course_id][$v->class_id][1]) &&
                empty($plan[$v->course_id][$v->class_id][2]) &&
                empty($plan[$v->course_id][$v->class_id][3])
            ) {
                $time[$v->course_id][$v->class_id][]                                 = self::getClassTime($v->start_time);
                $data[$v->course_id][$v->class_id][$v->plan_id]['progress']          = $time[$v->course_id][$v->class_id][0].SLanguage::tr('开课', 'course.info');
                $data[$v->course_id][$v->class_id][$v->plan_id]['courseClassStatus'] = self::getCourseClassStatus(1, $v->max_user, $v->user_total, $courseType);
            } elseif (
                empty($plan[$v->course_id][$v->class_id][1]) &&
                empty($plan[$v->course_id][$v->class_id][2]) &&
                !empty($plan[$v->course_id][$v->class_id][3])
            ) {
                $data[$v->course_id][$v->class_id][$v->plan_id]['progress']          = SLanguage::tr('课程直播完结', 'course.info');
                $data[$v->course_id][$v->class_id][$v->plan_id]['courseClassStatus'] = self::getCourseClassStatus(3, $v->max_user, $v->user_total, $courseType);
            } else {
                $total                                                               = $data[$v->course_id][$v->class_id][$v->plan_id]['sectionNum'];
                $normalNum                                                           = count($plan[$v->course_id][$v->class_id][1]);
                $num                                                                 = $total + 1 - $normalNum;
                $data[$v->course_id][$v->class_id][$v->plan_id]['progress']          = SLanguage::tr('课程进度', 'course.info')."({$num}/{$total})";
                $data[$v->course_id][$v->class_id][$v->plan_id]['courseClassStatus'] = self::getCourseClassStatus(1, $v->max_user, $v->user_total, $courseType);
            }

        }

        return $data;
    }

    public static function getCourseClassStatus($courseStatus, $maxUser, $userTotal, $courseType = 1)
    {
        $result = '';

        if ($courseStatus == 1) {
            $num = $maxUser - $userTotal;

            if ($num == 0) {
                $result = "<span class='cRed'>".SLanguage::tr('报名已满', 'course.info')."</span>";
            } else if ($num > 5) {
                $result = "<span class='g-icon8'></span>";
            } else {
                $result = "<span class='cGreen'>".SLanguage::tr('剩余名额', 'course.info')."</span>".($num < 0 ? 1 : $num);
            }
        }

        if ($courseStatus == 3) {
            //$result = "<span class='cRed'>已完结</span>";
            $result = ($courseType == 2) ? "<span class='course-icon2' style='height:26px;margin:4px;padding:0 20px;'></span>" : "<span class='course-icon5' style='height:23px;margin:5px;padding:3px 19px;'></span>";
        }

        if ($courseStatus == 2) {
            $result = "<span class='cRed'>".SLanguage::tr('正在上课', 'course.info')."</span>";
        }

        return $result;
    }

    public static function getTryIconOrPlanId($vpt, $lpt, $planId = '', $courseType = 3)
    {
        if ($courseType == 3) {
            return '';
        }

        //录播课video_public_type = 1,2 直播课 live_public_type=1为试看
        if (in_array($vpt, [1, 2]) || $lpt == 1) {
            //return $planId ? $planId : "<span id='video-try-icon' class='try-icon video-try-icon'></span>";
            return $planId ? $planId : "<span class='course-chapter-look-icon'>可试看</span>";
        }

        return '';
    }

    public static function getPlanTimeAndStatus($planStatus, $courseType = 1, $videoPublicType = 1, $begin, $last, $videoTime = 0)
    {
        $planStatusStr = '';
        $startTime = strtotime($begin);
        $endTime   = strtotime($last);
        $nowYear   = date('Y');
	$tStart = '';
        $end = '';
        $start = '';
        if(!empty($startTime) && !empty($endTime)) {
        $tStart = $start = ($nowYear == date('Y', $startTime)) ? date('m-d H:i', $startTime) : date('Y-m-d H:i', $startTime);
        $end    = (date('d', $startTime) == date('d', $endTime)) ? date('H:i', $endTime) : date('d H:i', $endTime);

        if ($nowYear != date('Y', $endTime)) {
            $end = date('Y-m-d H:i', $endTime);
        }

        if (
            date('y', $startTime) == date('y', $endTime)
            &&
            date('m', $startTime) == date('m', $endTime)
            &&
            date('d', $startTime) == date('d', $endTime)
        ) {
            $end = date('H:i', $endTime);
        }

        if (date('d', $endTime) - date('d', $startTime) > 1) {
            $end = date('d H:i', $endTime);
        }

        if (date('m', $endTime) - date('m', $startTime) > 1) {
            $end = date('m-d H:i', $endTime);
        }

        if ($startTime >= $endTime || (ceil(($endTime - $startTime) / 3600) > 3)) {
            $start         = $tStart;
            $planStatusStr = $start;
        } else {
            $planStatusStr = $start.'-'.$end;
        }
	}
        if ($courseType == 2) $planStatusStr = '';

        $result = '';
        switch ($planStatus) {
            case 1 :
                $result = ($courseType == 1 || $courseType == 3) ? SLanguage::tr('<span class="courseEnd">未开课</span>', 'course.info') : '';
                break;
            case 2 :
                //$result = "<div class='course-icon3 fs14' style='margin-left:90px'>".SLanguage::tr('正在上课', 'course.info')."</div>";
                $result = "<span class='fs14 cGreen3'>".SLanguage::tr('正在上课', 'course.info')."</span>";
                break;
            case 3 :
                if ($videoPublicType == -2) {
                    $result = SLanguage::tr('<span class="courseEnd">无回看</span>', 'course.info');
                } else if ($courseType == 1) {
                    $result = "<span class='course-look-icon'></span>";
                } else if ($videoTime) {
                    $result = "<span class='course-look-icon'></span>";
                }
                break;
        }

        if ($courseType == 3 && $endTime < time()) {
            $result = "<span class='courseEnd'>".SLanguage::tr('已结束', 'course.info')."</span>";
        }

        return [
            'planStatus' => $planStatusStr,
            'planTime'   => $result
        ];
    }

    public static function getClassTime($time)
    {
        $time = strtotime($time);
        if (date('Y') != date('Y', $time)) {
            return date('Y年m月d日 H:i', $time);
        }

        return date('m月d日 H:i', $time);
    }

    /*
    public static function getClassAndPlan($cid, $resellOrgId = 0)
    {
        $data['courseInfo'] = self::getCourseInfo($cid);
        if (empty($data['courseInfo'])) return [];
        $data['courseInfo']['originPrice'] = $data['courseInfo']['price'];

        if ($resellOrgId) {
            $resellInfo = course_resell_api::getResellCourseInfo($cid, $resellOrgId);
            if (!empty($resellInfo['courseId'])) {
                $data['courseInfo']['price'] = $resellInfo['priceResell'];
                $data['courseInfo']['resellCourseType'] = 1;
            }
        }
        
        $planArr = [
            'q'  => [
                'course_id' => $cid,
                'status'    => '1,2,3'
            ],
            'f'  => [
                'course_name',
                'course_id',
                'class_name',
                'video_id',
                'address',
                'region_level0',
                'region_level1',
                'region_level2',
                'class_id',
                'plan_id',
                'teacher_id',
                'admin_id',
                'teacher_name',
                'course_type',
                'admin_real_name',
                'teacher_real_name',
                'admin_name',
                'section_name',
                'section_desc',
                'start_time',
                'end_time',
                'status',
                'totaltime',
                'max_user',
                'try',
                'user_total',
                'live_public_type',
                'video_public_type',
                'teacher_thumb_med',
                'vt',
				'section_order_no'
            ],
            'ob' => ['section_order_no' => 'asc'],
            'pl' => 100
        ];

        $res = seek_api::seekPlan($planArr);
        if (empty($res->data)) return $data;
        $courseType = $data['courseInfo']['courseType'];

        $noVideo       = SLanguage::tr('暂无视频', 'course.info');
        $replaceMaster = SLanguage::tr('代讲', 'course.info');
        $address       = '';

        $planIdArr = $classIdArr = $classStatus = [];
        foreach ($res->data as $item) {
            $planIdArr[]                                    = $item->plan_id;
            $progressPlan[$item->class_id][$item->status][] = $item->plan_id;

            $classIdArr[$item->class_id] = $item->class_id;
            $planInfo[$item->plan_id]    = [
                'sectionName' => $item->section_name,
                'startTime'   => self::getClassTime($item->start_time)
            ];
        }
        $classIsFullStatus = course_class_api::getClassRegStatus($classIdArr);

        $region = region_geo::$region;
		$try=0;
		$try_plan=0;
        $recorded = 0;
        foreach ($res->data as $v) {
            if ($v->admin_id != $v->teacher_id) {
                $v->section_desc .= '('.$v->teacher_real_name.$replaceMaster.')';
            }

            // 线下课显示地址
            if ($courseType === 3) {
                $regionLevel0 = !empty($region[$v->region_level0]) ? $region[$v->region_level0] : '';
                $regionLevel1 = !empty($region[$v->region_level1]) ? $region[$v->region_level1] : '';
                $regionLevel2 = !empty($region[$v->region_level2]) ? $region[$v->region_level2] : '';

                $address = SLanguage::tr('地址：', 'course.info').$regionLevel0.$regionLevel1.$regionLevel2.$v->address;
            }

            $timeAndStatus = course_detailed::getPlanTimeAndStatus($v->status, $courseType, $v->video_public_type, $v->start_time, $v->end_time, $v->totaltime);
            if($v->totaltime>0){
                $recorded =1;
            }
			if($v->try){ 
				$try=1;
				$try_plan=$v->plan_id;
			}
            $data['planList'][$v->class_id][] = [
                'planId'      => $v->plan_id,
                'try'      => $v->try,
                'sectionName' => SLanguage::tr($v->section_name, 'site.index'),
                'sectionDesc' => $v->section_desc.self::getTryIconOrPlanId($v->video_public_type, $v->live_public_type, '', $v->course_type),
                'startTime'   => $timeAndStatus['planStatus'],
                'planStatus'  => ($courseType == 2)
                    ?
                    (
                    !empty($v->totaltime) && $v->video_public_type != -2
                        ? $timeAndStatus['planTime'].' <span class="cDark_9">('.utility_tool::sec2time($v->totaltime).' )</span>'
                        : $timeAndStatus['planTime'].' <span class="c-fr">'.$noVideo.'</span>'
                    )
                    : $timeAndStatus['planTime'],
            ];

            $data['teacherMasterList'][$v->teacher_id] = [
                'teacherName'  => !empty($v->teacher_real_name) ? $v->teacher_real_name : $v->teacher_name,
                'teacherId'    => $v->teacher_id,
                'teacherThumb' => interface_func::imgUrl($v->teacher_thumb_med)
            ];

            $data['classList'][$v->class_id] = [
                'className'    => $v->class_name,
                'planNum'      => count($data['planList'][$v->class_id]),
                'classId'      => $v->class_id,
                'teacherId'    => $v->teacher_id,
                'admin_id'     => $v->admin_id,
                'teacherName'  => !empty($v->admin_real_name) ? $v->admin_real_name : $v->admin_name,
                'classStatus'  => !empty($classIsFullStatus[$v->class_id]['classStatus']) ? $classIsFullStatus[$v->class_id]['classStatus'] : '',
                'progress'     => self::getProgressId($progressPlan, $planInfo, $courseType, $v->vt)[$v->class_id],
                'classAddress' => $address,
                'maxUser'=>$v->max_user,
                'userTotal'=>$v->user_total
            ];
        }
        $data['defaultClassId']          = (int)reset($classIdArr);
        $data['try']          = $try;
        $data['try_plan']          = $try_plan;
        $data['recorded'] = $recorded;

        return $data;
    }
    */

    public static function getClassAndPlan($cid, $resellOrgId = 0,$hotType=2)
    {
        $data['courseInfo'] = self::getCourseInfo($cid);
        if (empty($data['courseInfo'])) return [];
        $classProgress = array();
        if(!empty($data['courseInfo']['class'])){
            foreach($data['courseInfo']['class'] as $class){
                $classProgress[$class->class_id]= $class->progress_plan;
            }
        }
        $data['courseInfo']['originPrice'] = $data['courseInfo']['price'];

        if ($resellOrgId) {
            $resellInfo = course_resell_api::getResellCourseInfo($cid, $resellOrgId);
            if (!empty($resellInfo['courseId'])) {
                $data['courseInfo']['price'] = $resellInfo['priceResell'];
                $data['courseInfo']['resellCourseType'] = 1;
            }
        }

        $planArr = [
            'q'  => [
                'course_id' => $cid,
                'status'    => '1,2,3'
            ],
            'f'  => [
                'course_name',
                'course_id',
                'class_name',
                'video_id',
                'address',
                'region_level0',
                'region_level1',
                'region_level2',
                'class_id',
                'plan_id',
                'teacher_id',
                'admin_id',
                'teacher_name',
                'course_type',
                'admin_real_name',
                'teacher_real_name',
                'admin_name',
                'section_name',
                'section_desc',
                'start_time',
                'end_time',
                'status',
                'totaltime',
                'max_user',
                'try',
                'user_total',
                'live_public_type',
                'video_public_type',
                'teacher_thumb_med',
                'vt',
                'section_order_no'
            ],
            'ob' => ['section_order_no' => 'asc'],
            'pl' => 100
        ];

        $res = seek_api::seekPlan($planArr);
        if (empty($res->data)) return $data;
        $courseType = $data['courseInfo']['courseType'];

        $noVideo       = SLanguage::tr('暂无视频', 'course.info');
        $replaceMaster = SLanguage::tr('代讲', 'course.info');
        $address       = '';

        $planIdArr = $classIdArr = $classStatus = [];
        foreach ($res->data as $item) {
            $planIdArr[$item->plan_id]                                    = $item->plan_id;
            $oncePlanIdArr[$item->class_id][]                                    = $item->plan_id;
            $stepsPlanIdArr[$item->class_id][$item->plan_id]                                    = $item->plan_id;
            //$progressPlan[$item->class_id][$item->status][] = $item->plan_id;

            $classIdArr[$item->class_id] = $item->class_id;
            $planInfo[$item->plan_id]    = [
                'sectionName' => $item->section_name,
                'startTime'   => self::getClassTime($item->start_time),
                'status' =>$item->status
            ];
        }
        $classIsFullStatus = course_class_api::getClassRegStatus($classIdArr,$hotType);
        $region = region_geo::$region;
        $try=array();
        $try_plan=array();
        $recorded = 0;
        foreach ($res->data as $v) {
            if ($v->admin_id != $v->teacher_id) {
                $v->section_desc .= '('.$v->teacher_real_name.$replaceMaster.')';
            }

            // 线下课显示地址
            if ($courseType === 3) {
                $regionLevel0 = !empty($region[$v->region_level0]) ? $region[$v->region_level0] : '';
                $regionLevel1 = !empty($region[$v->region_level1]) ? $region[$v->region_level1] : '';
                $regionLevel2 = !empty($region[$v->region_level2]) ? $region[$v->region_level2] : '';

                $address = SLanguage::tr('地址：', 'course.info').$regionLevel0.$regionLevel1.$regionLevel2.$v->address;
            }

            $timeAndStatus = course_detailed::getPlanTimeAndStatus($v->status, $courseType, $v->video_public_type, $v->start_time, $v->end_time, $v->totaltime);
            if($v->totaltime>0){
                $recorded =1;
            }
            if($v->live_public_type==1||$v->video_public_type==1||$v->video_public_type==2){
                $try[]=1;
                $try_plan[]=$v->plan_id;
            }
            $data['planList'][$v->class_id][] = [
                'record' => '',
                'playTime' => 0,
                'planId'      => $v->plan_id,
                'try'      => $v->try,
                'section_name' => SLanguage::tr($v->section_name, 'site.index'),
                'section_desc' => SLanguage::tr($v->section_desc, 'site.index'),
                'course_type' => $v->course_type,
                'video_public_type' => $v->video_public_type,
                'sectionName' => $v->section_order_no<100?sprintf("%02d",$v->section_order_no):$v->section_order_no,
                'sectionDesc' => '<span>'.$v->section_desc.'</span>'.self::getTryIconOrPlanId($v->video_public_type, $v->live_public_type, '', $v->course_type),
                'startTime'   => ($courseType == 2)?(!empty($v->totaltime) && $v->video_public_type != -2?"时长：".utility_tool::sec2time($v->totaltime):"0分钟"):$timeAndStatus['planStatus'],
                'planStatus'  => ($courseType == 2)
                    ?
                    (
                    !empty($v->totaltime) && $v->video_public_type != -2
                        ? $timeAndStatus['planTime']
                        : $timeAndStatus['planTime'].' <span class="c-fr">'.$noVideo.'</span>'
                    )
                    : $timeAndStatus['planTime'],
                'resellOrgId'=>!empty($resellOrgId)?$resellOrgId:'',
            ];
            $progress="";
            if($courseType!=2) {
                $steps = new course_steps();

                foreach($stepsPlanIdArr[$v->class_id] as $key=>$value){

                    $steps->add($key);

                }
                $nextPlanId=0;
                if(!empty($classProgress[$v->class_id])) {
                    if(!empty($stepsPlanIdArr[$v->class_id][$classProgress[$v->class_id]])){
                    $steps->setCurrent($stepsPlanIdArr[$v->class_id][$classProgress[$v->class_id]]);//参数为key值
                  }
                    $nextPlanId = $steps->getNext();
                }else{
                    $nextPlanId=0;
                }
                if (!empty($classIsFullStatus[$v->class_id]['currentStatus']) && $classIsFullStatus[$v->class_id]['currentStatus'] == 1) {
                    if(!empty($nextPlanId)){
                        $progress .= '进度： ' . $planInfo[$nextPlanId]["sectionName"];
                    }else{
                        if(!empty($classProgress[$v->class_id])){
                            $progress .= '进度： ' . $planInfo[$classProgress[$v->class_id]]["sectionName"];
                        }else {
                            $progress .= ' ' . $planInfo[$oncePlanIdArr[$v->class_id][0]]["startTime"];
                            $progress .= ' ' . self::getProgress($courseType, $planInfo[$oncePlanIdArr[$v->class_id][0]]["status"]);
                        }
                    }
                }elseif(!empty($classIsFullStatus[$v->class_id]['currentStatus']) && $classIsFullStatus[$v->class_id]['currentStatus'] == 2){
                    if(!empty($nextPlanId)){
                        $progress .= '进度： ' . $planInfo[$nextPlanId]["sectionName"];
                    }else{
                        if(!empty($classProgress[$v->class_id]) && !empty($planInfo[$classProgress[$v->class_id]]["sectionName"])){
                            $progress .= '进度： ' . $planInfo[$classProgress[$v->class_id]]["sectionName"];
                        }else {
                            $progress .= '进度： ' . $planInfo[$oncePlanIdArr[$v->class_id][0]]["sectionName"];
                        }
                    }
                }elseif(!empty($classIsFullStatus[$v->class_id]['currentStatus']) && $classIsFullStatus[$v->class_id]['currentStatus'] == 3){
                    $progress .='已完结';
                }
            }
            /*if(!empty($planInfo[$classProgress[$v->class_id]])){
                $nextPlanId = 0;
                if($courseType!=2){
                    $steps = new course_steps();

                    foreach($planIdArr as $key=>$value){

                        $steps->add($key);

                    }
                    $steps->setCurrent($planIdArr[$classProgress[$v->class_id]]);//参数为key值
                    $nextPlanId = $steps->getNext();
                    if(!empty($nextPlanId)){
                        $progress .= ' ' . $planInfo[$nextPlanId]["startTime"];
                        $progress .= ' ' . $planInfo[$nextPlanId]["sectionName"];
                    }else {
                        $progress .= ' ' . $planInfo[$classProgress[$v->class_id]]["startTime"];
                        $progress .= ' ' . $planInfo[$classProgress[$v->class_id]]["sectionName"];
                    }
                }
                if(!empty($nextPlanId)){
                    $progress .= ' ' . self::getProgress($courseType, $planInfo[$nextPlanId]["status"]);
                }else {
                    $progress .= ' ' . self::getProgress($courseType, $planInfo[$classProgress[$v->class_id]]["status"]);
                }
            }else{
                if($courseType!=2){
                    $progress .= ' ' . $planInfo[$planIdArr1[0]]["startTime"];
                    $progress .= ' ' . $planInfo[$planIdArr1[0]]["sectionName"];
                }
                $progress .= ' ' . self::getProgress($courseType, $planInfo[$planIdArr1[0]]["status"]);
            }*/
            $data['classList'][$v->class_id] = [
                'className'    => $v->class_name,
                'planNum'      => count($data['planList'][$v->class_id]),
                'classId'      => $v->class_id,
                'teacherId'    => $v->teacher_id,
                'admin_id'     => $v->admin_id,
                'teacherName'  => !empty($v->admin_real_name) ? $v->admin_real_name : $v->admin_name,
                'classStatus'  => !empty($classIsFullStatus[$v->class_id]['classStatus']) ? $classIsFullStatus[$v->class_id]['classStatus'] : '',
                'progress'     => $progress,
                'classAddress' => $address,
                'maxUser'=>$v->max_user,
                'userTotal'=>$v->user_total,
                'signUpStatus'=> $v->max_user==$v->user_total?false:true,
                'status'=>!empty($classIsFullStatus[$v->class_id]['status']) ? true : false,
            ];
            if ($courseType === 3) {
                if(strtotime($v->end_time)<time()){
                    $data['classList'][$v->class_id]["courseStatus"] = false;
                }else{
                    $data['classList'][$v->class_id]["courseStatus"] = true;
                }
            }else{
                $data['classList'][$v->class_id]["courseStatus"] = true;
            }
        }
        $data['defaultClassId']          = (int)reset($classIdArr);
        $data['try']          = !empty($try[0])?$try[0]:0;
        $data['try_plan']          = !empty($try_plan[0])?$try_plan[0]:0;
        $data['recorded'] = $recorded;
        return $data;
    }

    public static function getProgress($courseType, $courseStatus, $vt = 0)
    {
        $str        = '';
        $kaiKe      = SLanguage::tr('开课', 'course.info');
        $classIng   = SLanguage::tr('正在上课', 'course.info');
        $hasEnd     = SLanguage::tr('课程直播完结', 'course.info');
        $backLook   = SLanguage::tr('可回看', 'course.info');
        $courseOver = SLanguage::tr('课程已结束', 'course.info');
        $classOver  = SLanguage::tr('本班已结束', 'course.info');
        $vtStr      = SLanguage::tr('本课程视频总时长:', 'course.info');

        if ($courseType == 1) {// 直播课
            if ($courseStatus == 2) {// 正在直播
                $str = $classIng;
            } elseif ($courseStatus == 1) {// 全部未开课
                $str = $kaiKe;
            } elseif ($courseStatus == 3) {// 全部已完结
                $str = $hasEnd.' '.$backLook;
            } else {// 有未开课，有已完结 courseStatus =4
                $str = '';
            }
        } elseif ($courseType == 3) {
            if ($courseStatus == 4) {
                $str = $courseOver.' '.$classOver;
            }
        } elseif ($courseType == 2) {
            if ($vt) {
                $str = $vtStr.utility_tool::sec2time($vt);
            } else {
                $str = '';
            }

        }

        return $str;
    }
//待废弃 longhouan getProgressId()
    public static function getProgressId($progressPlan, $planInfo, $courseType, $vt = 0)
    {
        $data  = [];
        $jinDu = SLanguage::tr('进度：', 'course.info');

        foreach ($progressPlan as $classId => $item) {
            if (in_array(2, array_keys($item))) { //直播
                $data[$classId] = $planInfo[$item[2][0]]['sectionName'] .' '.self::getProgress($courseType, 2);
            } elseif (!in_array(3, array_keys($item))) { // 全部未开课
                $data[$classId] = ($courseType == 2) ? self::getProgress($courseType, 1) : $planInfo[$item[1][0]]['startTime'].' '.$planInfo[$item[1][0]]['sectionName'].' '.self::getProgress($courseType, 1);
            } elseif (!in_array(1, array_keys($item))) { // 全部完结
                $data[$classId] = $planInfo[$item[3][0]]['sectionName'].' '.self::getProgress($courseType, 3);
            } else { //有未开课，有已完结(只取未开课的)
                if(in_array(3,array_keys($item))){
                    if ($courseType == 2) {
                        $data[$classId] = $planInfo[$item[3][0]]['sectionName'] . ' ' . self::getProgress($courseType, 4, $vt);
                    } else {
                        $data[$classId] = self::getProgress($courseType, 4, $vt) ?: $jinDu . ' ' . $planInfo[$item[3][0]]['sectionName'] . ' ' . $planInfo[$item[3][0]]['startTime'];
                    }
                }else {
                    if ($courseType == 2) {
                        $data[$classId] = $planInfo[$item[1][0]]['sectionName'] . ' ' . self::getProgress($courseType, 4, $vt);
                    } else {
                        $data[$classId] = self::getProgress($courseType, 4, $vt) ?: $jinDu . ' ' . $planInfo[$item[1][0]]['sectionName'] . ' ' . $planInfo[$item[1][0]]['startTime'];
                    }
                }

            }
        }
        return $data;
    }

    public static function getCourseInfo($cid)
    {
        if (!is_numeric($cid) || !(int)($cid)) return [];
        $param = [
            'q' => [
                'course_id' => $cid,
            ],
            'f' => [
                'course_id',
                'title',
                'desc',
                'status',
                'max_user',
                'user_total',
                'thumb_med',
                //'section_count',
                'fee_type',
                'price',
                'market_price',
                'course_type',
                'avg_score',
                'try',
                'comment',
                'price',
                'class',
                'vv',
                'first_cate_name_display',
                'second_cate_name_display',
                'third_cate_name_display',
                'first_cate',
                'second_cate',
                'third_cate',
                'course_attr',
                'scope',
                'memberset',
                'deleted',
                'user_id',
                'is_promote'
            ]
        ];

        $courseInfo = seek_api::seekCourse($param);
        if (empty($courseInfo->data)) return [];
        $memberInfo = self::getMemberSetIdAndName($courseInfo->data[0]->memberset);

        return [
            'courseId'     => $courseInfo->data[0]->course_id,
            'feeType'      => $courseInfo->data[0]->fee_type,
            'title'        => $courseInfo->data[0]->title,
            'desc'         => $courseInfo->data[0]->desc,
            'vv'           => $courseInfo->data[0]->vv,
            'price'        => $courseInfo->data[0]->price / 100,
            'marketPrice'  => $courseInfo->data[0]->market_price / 100,
            'fName'        => $courseInfo->data[0]->first_cate_name_display,
            'sName'        => $courseInfo->data[0]->second_cate_name_display,
            'tName'        => $courseInfo->data[0]->third_cate_name_display,
            'fCate'        => $courseInfo->data[0]->first_cate,
            'sCate'        => $courseInfo->data[0]->second_cate,
            'tCate'        => $courseInfo->data[0]->third_cate,
            'cAttr'        => $courseInfo->data[0]->course_attr,
            'major'        => teacher_api::getSubjectName($courseInfo->data[0]->course_attr),
            'thumb'        => interface_func::imgUrl($courseInfo->data[0]->thumb_med),
            //'sectionNum'   => $courseInfo->data[0]->section_count,
            'commentNum'   => $courseInfo->data[0]->comment,
            'courseStatus' => $courseInfo->data[0]->status,
            'maxUser'      => $courseInfo->data[0]->max_user,
            'userTotal'    => $courseInfo->data[0]->user_total,
            'score'        => $courseInfo->data[0]->avg_score / 10,
            'try'          => $courseInfo->data[0]->try,
            'scope'        => $courseInfo->data[0]->scope,
            'courseType'   => $courseInfo->data[0]->course_type,
            'setIdStr'     => $memberInfo['id'],
            'memberName'   => $memberInfo['name'],
			'memberset'    => $memberInfo['memberset'],
			'class'         =>$courseInfo->data[0]->class,
            'deleted' => $courseInfo->data[0]->deleted,
            'userOwnerId' => $courseInfo->data[0]->user_id,
            'isPromote'   => $courseInfo->data[0]->is_promote
        ];
    }

    public static function getMemberSetIdAndName($obj)
    {
        $id = $name = $memberset = [];
        foreach ($obj as $v) {
            if (!empty($v->member_set_id) && $v->status == 1) {
                $id[$v->member_set_id]   = $v->member_set_id;
                $name[$v->member_set_id] = $v->member_set_name;
				$memberset[] = $v;
            }
        }

        return [
            'id'   => implode(',', $id),
            'name' => implode(',', $name),
			'memberset' => $memberset
        ];
    }

    public static function getCourseUserPlanRecordListByClassIdAndUserId($classId,$userId){
        if(empty($classId) || empty($userId)) return false;
        $param["classId"] = $classId;
        $param["userId"] = $userId;
        $res = utility_services::call('stat/courseUserPlanRecord/getCourseUserPlanRecordListByClassIdAndUserId/', $param);
        if($res->code==0) return $res->result;

        return false;
    }
}
