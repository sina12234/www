<?php

class course_class_api
{
    const COURSE_CLASS_GET_CLASS_LIST = '/course/class/GetClassList/';

    const GET_CLASS_REG_USER_TOTAL_NUM = '/course/courseuser/GetClassRegUserTotalNum/';

    public static function getClassList($classIdArr)
    {
        $res = interface_func::requestApi(self::COURSE_CLASS_GET_CLASS_LIST, ['classIdArr' => $classIdArr]);
        if (!empty($res['code'])) {
            return interface_func::error($res['code'], $res['msg']);
        }

        return $res;
    }

    public static function getClassRegStatus($classIdArr,$hotType=2)
    {
        $list = self::getClassList($classIdArr);
        if (empty($list['result'])) return [];

        $classInfo      = [];
        $fullClassStr   = "<span class='cRed'>".SLanguage::tr('报名已满', 'course.info')."</span>";
        $hotClassStr    = "<span class='cRed'>".SLanguage::tr('热报中', 'course.info')."</span>";
        //$classEnd       = "<span class='cRed'>".SLanguage::tr('本班已结束', 'course.info')."</span>";
        $remainClassStr = "<span class='cGreen'>".SLanguage::tr('剩余名额', 'course.info')."</span>";

        foreach ($list['result'] as $item) {
            $classInfo[$item['pk_class']]['status'] = 1;
            $num = $item['max_user'] - $item['user_total'];
            $classInfo[$item['pk_class']] = [
                'className' => $item['name'],
                'classId'   => $item['pk_class']
            ];
            if($hotType==2){
                //剩余名额
                if ($num == 0) {
                    $classInfo[$item['pk_class']]['classStatus'] = $fullClassStr;
                } else if ($num > 10) {
                    $classInfo[$item['pk_class']]['classStatus'] = $hotClassStr;
                } else {
                    $classInfo[$item['pk_class']]['classStatus'] = $remainClassStr.($num < 0 ? 1 : $num);
                }
            }

           if ($item['status'] == 3) {
                $classInfo[$item['pk_class']]['status'] = 0;
            }else{
               $classInfo[$item['pk_class']]['status'] = 1;
           }

            $classInfo[$item['pk_class']]['currentStatus'] = $item['progress_status'];
        }
        return $classInfo;
    }

    public static function getClassRegUserTotalNum($classId)
    {
        $url = self::GET_CLASS_REG_USER_TOTAL_NUM.$classId;
        $res = interface_func::requestApi($url);
        if (!empty($res['code'])) {
            return interface_func::error($res['code'], $res['msg']);
        }

        return $res['result'];
    }

    public static function getClassById($id){
        $params = new stdclass;
        $ret = utility_services::call("/course/plan/getcoursebyplan/$id", $params);
        return $ret;
    }

    public static function getClassRegUser($classId)
    {
        $url = "/course/courseuser/GetClassRegUser/".$classId;
        $res = interface_func::requestApi($url);
        if (!empty($res['code'])) {
            return interface_func::error($res['code'], $res['msg']);
        }

        return $res['result'];
    }
	public static function getTeacherByCourseId($courseId){
		$url = "/course/courseplan/GetTeacherByCourseId/";
        $params = new stdCLass;
		$params->courseId = $courseId;	
		$result = utility_services::call($url, $params);
		$teacherIds = array();
		if(!empty($result->data)){
			foreach($result->data as $v){
				$teacherIds[$v->fk_user_plan] = $v->fk_user_plan;
			}
		}
		return $teacherIds;
	}
	public static function getClassIndex($courseId){
		$params = new stdCLass;
		$params->courseId = $courseId;
		
		$result = utility_services::call("/course/info/classIndex/", $params);
		if($result->code == 0 && !empty($result->result)){
			return $result->result;
		}
		
		return array();
	}

    /*
     * @desc 获取课程下班级列表(包含班主任/地址)
     * @param $courseId
     * @param $adminIdArr (班主任id)回调
     * @author zhengtianlong 2016-11-21
     */
    public static function getClassListByCourseId($courseId, &$adminIdArr){
        if(empty($courseId)) return false;
        $result = utility_services::call("/course/class/getClassListByCourseId/".$courseId);
        if(!empty($result->code)) return false;

        foreach($result->result as $value){
            $adminIdArr[] = $value->class_user_id;
        }

        //获取老师信息
        $teacherArr = array();
        $adminIds   = implode(',', $adminIdArr);
        $teacherParams = [
            'q' => ['teacher_id'=>$adminIds],
            'f' => ['teacher_id','real_name']
        ];
        $seekTeacher = seek_api::seekTeacher($teacherParams);
        if(!empty($seekTeacher->data)){
            foreach($seekTeacher->data as $val){
                $teacherArr[$val->teacher_id] = $val->real_name;
            }
        }

        foreach($result->result as &$val){
            $val->adminName = $teacherArr[$val->class_user_id];
            $val->province  = region_geo::$region[$val->province];
            $val->city      = region_geo::$region[$val->city];
            $val->area      = region_geo::$region[$val->area];
        }

        return $result->result;
    }
	
}
