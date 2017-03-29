<?php

class interface_plan_orglist {

    /**
     * @desc 小沃首页直播列表(包含分销课)
     * @param int $userId
     * @param int $orgId
     * @return array
     */
    public static function getIndexLives($userId, $orgId){
        $data = array();

        //报名的课程
        $courseIdArr = array();
        if(!empty($userId)){
            $orgInfo = user_api::getOrgByid($orgId);
            $ownerId = !empty($orgInfo->fk_user_owner) ? $orgInfo->fk_user_owner : 0;
            $myCourse = interface_user_api::getUserRegCourse($userId, array(), 1, -1, $ownerId);
            if(!empty($myCourse['items'])){
                $courseIdArr = array_column($myCourse['items'],'fk_course');
            }
        }

        //即将直播
        $soonTime = date('Y-m-d H:i:s').','.date('Y-m-d 23:59:59',strtotime('+5 days'));
        $soonParams = [
            'q' => [
                'start_time'=>$soonTime,'status'=>'1,2,3','admin_status'=>1,'course_type'=>1,'org_status'=>1,
                'expression' => "@resell_org_id =".$orgId." | @org_id=".$orgId,
            ],
            'f' => [
                'plan_id','course_id','start_time','course_name','class_name','section_name',
                'status','subdomain','course_thumb_big','course_thumb_med','course_thumb_small',
                'try','class_id'
            ],
            'p' => 1,
            'pl'=> 6,
            'ob'=> ['start_time'=>'asc']
        ];
        $soonPlanRes = seek_api::seekPlan($soonParams);
        $soonNum     = !empty($soonPlanRes->data) ? count($soonPlanRes->data) : 0;

        //精彩回放
        $backNum = 6 - $soonNum;
        $records = array();
        if($soonNum < 6){
            $backTime = date('Y-m-d 00:00:00',strtotime('-1 days')).','.date('Y-m-d 23:59:59');
            $backParam = [
                'q' => [
                    'start_time'=>$backTime,'status'=>'3','admin_status'=>1,'course_type'=>1,'org_status'=>1,
                    'expression' => "@resell_org_id =".$orgId." | @org_id=".$orgId,
                ],
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

        $mergeData = array_merge($records, $soonPlanRes->data);
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
