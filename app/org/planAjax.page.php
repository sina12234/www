<?php
class org_planAjax{

    static $courseInfo = array();
    public function __construct(){
        $this->user = user_api::loginedUser();
        if(empty($this->user)) $this->_setMsg('1000','没有登陆');

        $ownerReg = user_organization::subdomain();
        if(empty($ownerReg)) $this->_setMsg('1001', '机构不存在');

        $this->orgInfo = user_organization::getOrgByOwner($ownerReg->userId);
        if(!isset($this->orgInfo->oid)) $this->_setMsg('1001', '机构不存在');

        $this->orgOwner = $ownerReg->userId;
        $this->orgId    = $this->orgInfo->oid;

        $this->courseId = !empty($_POST['courseId']) ? (int)$_POST['courseId'] : 0;
        $this->classId  = !empty($_POST['classId']) ? (int)$_POST['classId'] : 0;

        self::$courseInfo = course_api::getCourseOne($this->courseId);
        if(empty(self::$courseInfo)) $this->_setMsg('1002', '课程不存在');
    }

    public function pageSetClass(){
        $className = !empty($_POST['className']) ? trim($_POST['className']) : '';
        $teacherId = !empty($_POST['teacherId']) ? (int)$_POST['teacherId'] : 0;
        $classNum  = !empty($_POST['studentNum']) ? (int)$_POST['studentNum'] : 0;
        $province  = !empty($_POST['province']) ? (int)$_POST['province'] : 0;
        $city      = !empty($_POST['city']) ? (int)$_POST['city'] : 0;
        $area      = !empty($_POST['area']) ? (int)$_POST['area'] : 0;
        $address   = !empty($_POST['address']) ? trim($_POST['address']) : '';

        if(empty($className)) $this->_setMsg(2000, '请填写班级名称');
        
        if(!utility_tool::check_string($className, 5, 1)) $this->_setMsg(2000, '班级名称最多5个汉字');

        if(empty($teacherId)) $this->_setMsg(2001, '请选择班主任');

        if(self::$courseInfo->type_id !=2 && empty($classNum)){
            $this->_setMsg(2002, '请填写班级人数');
        }
        if(self::$courseInfo->type_id != 2){
            if(empty($classNum) && is_numeric($classNum)) $this->setMsg(2002, '请填写班级人数');
            if($classNum > 100000) $this->_setMsg(2002, '班级人数请输入1-1000000');
        }

        $data = [
            'name'     => $className,
            'user_id'  => $this->orgOwner,
            'courseId' => $this->courseId,
            'user_class_id' => $teacherId
        ];

        $data['max_user'] = (self::$courseInfo->type_id == 2) ? 5000000 : $classNum;

        if(self::$courseInfo->type_id == 3){
            if(empty($province) || empty($city) || empty($address)) $this->_setMsg(2003, '请填写地址');
            $data['region_level0'] = $province;
            $data['region_level1'] = $city;
            $data['region_level2'] = $area;
            $data['address']       = $address;
        }

        if(empty($this->classId)){
            $res = course_api::addClass($this->courseId, $data);
            if(!$res) $this->_setMsg(1, '操作失败');
            $this->_setMsg(0, '操作成功',array("classId"=>$res));
        }else{
            $res = course_api::updateClass($this->classId, $data);
            if(!$res) $this->_setMsg(1, '操作失败');
            $this->_setMsg(0, '操作成功',array("classId"=>$this->classId));
        }
    }

    public function pageSetPlan(){
        $planName  = !empty($_POST['planName']) ? trim($_POST['planName']) : '';
        $teacherId = !empty($_POST['teacherId']) ? (int)$_POST['teacherId'] : 0;
        $startTime = !empty($_POST['startTime']) ? $_POST['startTime'] : '';
        $endTime   = !empty($_POST['endTime']) ? $_POST['endTime'] : '';
        $orderNo   = !empty($_POST['orderNo']) ? (int)$_POST['orderNo'] : 1;
        $planId    = !empty($_POST['planId']) ? (int)$_POST['planId'] : 0;

        if(empty($planName)) return $this->_setMsg(2004, '请填写课时名称');

        if(!utility_tool::stringNum($planName) > 70) return $this->_setMsg(2005, '课时名称最多35个汉字');

        if(empty($teacherId)) return $this->_setMsg(2008, '请选择老师');

        if(self::$courseInfo->type_id != 2){
            if(empty($startTime) || empty($endTime)){
                return $this->_setMsg(2006, '请选择时间');
            }
            if(strtotime($startTime) > strtotime($endTime)){
                return $this->_setMsg(2007, '开始时间不能大于结束时间');
            }
        }

        if(empty($this->classId)) $this->_setMsg(1000, '请完善班级信息');
        $planInfo = course_plan_api::getPlanListByClassId($this->classId);
        $planNum  = !empty($planInfo) ? (count($planInfo)+1) : 1;
        if($planNum > 200) $this->_setMsg(1000, '课时最多200个');

        $data = [
            'name'      => $planName,
            'teacherId' => $teacherId,
            'ownerId'   => $this->orgOwner,
            'startTime' => $startTime,
            'endTime'   => $endTime,
            'classId'   => $this->classId,
            'courseId'  => $this->courseId
        ];
        $data['livePublicType']  = !empty($_POST['liveType']) ? $_POST['liveType'] : 0;
        $data['videoPublicType'] = !empty($_POST['videoType']) ? $_POST['videoType'] : 0;
        $data['videoTrialTime']  = !empty($_POST['videoTime']) ? $_POST['videoTime'] : 0;

        if(empty($planId)){
            $data['orderNo'] = $planNum;
            $res = course_plan_api::add($data);
        }else{
            $res = course_api::updatePlanV2($planId, $data);
            if(!empty($startTime) && $res){
                $originaTime = !empty($_POST['originalStartTime']) ? strtotime($_POST['originalStartTime']) : 0;
                $this->setSms($startTime, $originaTime, $planNum);
            }
        }

        if(!$res) return $this->_setMsg(1, '操作失败');

        $this->_setMsg(0, '操作成功');
    }

    /**
     * 修改上课时间发送短信提醒
     * @param $startTime 修改后的排课时间
     * @param $originaTime 原来排课的开课时间
     * @param $orderNo
     * @return bool
     */
    private function setSms($startTime, $originaTime, $orderNo){
        $studentParams = array('classId'=>$this->classId,'page'=>1,'length'=>'-1');
        $studentsReg   = utility_services::call('/course/courseuser/listsByCourseClass', $studentParams);
        if(!empty($studentsReg->result->items)) {
            foreach($studentsReg->result->items as $val){
                $userIdArr[] = $val->fk_user;
            }
            $userReg = user_api::listUsersByUserIds($userIdArr);
            $classReg= course_api::getClass($this->classId);

            $nowTime = strtotime($startTime);
            if (!empty($userReg->result) && ($originaTime != $nowTime)) {
                foreach ($userReg->result as $v) {
                    $msgData = new stdClass();
                    $msgData->sms_type = 3;
                    $msgData->course_name = self::$courseInfo->title ? self::$courseInfo->title : '';
                    $msgData->mobile = $v->mobile ? $v->mobile : '';
                    $msgData->org_id = $this->orgId;
                    $msgData->org_name = $this->orgInfo->subname ? $this->orgInfo->subname : '';
                    $msgData->course_fee = self::$courseInfo->fee_type ? 1 : 2;
                    $msgData->class_name = $classReg->name ? $classReg->name : '';
                    $msgData->order_no = $orderNo ? $orderNo : '';
                    $msgData->start_time = $startTime ? $startTime : '';
                    $res[] = sms_api::smsSend($msgData);
                }
            }

            return $res;
        }
    }

    public function pageDelPlan(){
        $planId = !empty($_POST['planId']) ? (int)$_POST['planId'] : 0;
        if(empty($planId)) $this->_setMsg(2016, '课时不存在');

        $res = course_plan_api::delPlanById($planId);
        
        if(!$res) $this->_setMsg(1, '操作失败');
        //修改课时顺序
        $classPlanInfo = course_plan_api::getplanlistbyclassid($this->classId);
        if(!empty($classPlanInfo)){
            foreach($classPlanInfo as $k=>$v){
                $orderOnArr['order_no'][$v->plan_id] = $k + 1;                
            }
            course_plan_api::batchSetPlan($orderOnArr);
        }

        $this->_setMsg(0, '操作成功');
    }

    public function pageQuicksetPlan(){
        //章节名称
        $planNames = (!empty($_POST['data']) && is_array($_POST['data'])) ? $_POST['data'] : array();

        /**
         * 循环方式
         * 1:每天 2:每周 3:自定义
         */
        $typeArr  = array(1,2,3);
        $weekType = (!empty($_POST['weekType']) && in_array($_POST['weekType'],$typeArr)) ? (int)$_POST['weekType'] : 0;
        
        //开课时间
        $startTime = !empty($_POST['startTime']) ? $_POST['startTime'] : '';

        //授课老师
        $teacherId = !empty($_POST['teacherId']) ? (int)$_POST['teacherId'] : 0;

        /**
         * 课程时长
         * 1:0.5小时 2:1小时 3:1.5小时 4:2小时 5:2.5小时 6:3小时 7:自定义
         */
        $longArr  = array(1=>1800,2=>3600,3=>5400,4=>7200,5=>9000,6=>10800,7=>7);
        $longType = !empty($_POST['longType']) ? (int)$_POST['longType'] : 0;

        //自定义时长
        if(!empty($longArr[$longType])){
            if($longType == 7){
                if(empty($_POST['myLongTime'])){
                    $this->_setMsg(2008, '请填写自定义时间');
                }
                $longTime = $_POST['myLongTime'] * 60;
            }else{
                $longTime = $longArr[$longType];
            }
        }

        //自定义循环方式
        if($weekType == 3){
            if(!isset($_POST['myTimes']) && empty($_POST['myTimes'])){
                $this->_setMsg(2009, '请填写自定义时间');
            }
            $bySelfArr = explode(',', $_POST['myTimes']);
        }

        if(empty($teacherId)) $this->_setMsg(2010, '请选择讲师');

        if(empty($planNames)) $this->_setMsg(2011, '请完善课时信息');

        if(self::$courseInfo->type_id != 2){
            if(empty($startTime)) $this->_setMsg(2012, '请填写开课时间');

            if(empty($weekType)) $this->_setMsg(2013, '请填写循环方式');

            if(empty($longTime)) $this->_setMsg(2014, '请填写时长');
        }

        //班级下排课数
        $planNum = course_plan_api::getPlanNumByClassIdArr(array($this->classId));

        //循环排课时间
        $fTime = ($weekType == 1) ? 86400 : (($weekType == 2) ? 604800 : 0);
        
        $videoPublicType = !empty($_POST['videoPublicType']) ? $_POST['videoPublicType'] : 0;
        $videoTrialTime  = !empty($_POST['videoTrialTime']) ? $_POST['videoTrialTime'] : 0;

        $maxNum = $planNum[$this->classId] + count($planNames);
        if($maxNum > 200) $this->_setMsg(1000, '课时最多200个');

        //过滤课时名称为空的
        $planNames = array_values(array_filter($planNames));
        $data = $planDates = $planEndDates = array();
        foreach($planNames as $k=>$v){
            if(!utility_tool::stringNum($v) > 70){
                $errorLine = $k + 1;
                return $this->_setMsg(2005, "第 {$errorLine} 行:课时名称最多35个汉字");
            }
            $data[$k] = [
                'fk_course' => $this->courseId,
                'fk_class'  => $this->classId,
                'status'    => 1,
                'name'      => $v,
                'order_no'  => ($k + $planNum[$this->classId] + 1),
                'fk_user'   => $this->orgOwner,
                'fk_user_plan' => $teacherId,
                'live_public_type'  => 0,
                'video_public_type' => 0,
                'video_trial_time'  => 0
            ];
            
            if(self::$courseInfo->type_id != 2){
                if($k == 0 && $planNum[$this->classId]==1){
                    if(self::$courseInfo->fee_type == 0 && self::$courseInfo->type_id == 1){
                        $data[$k]['live_public_type']  = 0;
                        $data[$k]['video_public_type'] = 2;
                        $data[$k]['video_trial_time']  = 1200;
                    }
                }
                if($weekType == 3){
                    //手动排课
                    $s = date('H:i:s',strtotime($startTime));
                    $bySelfTime = !empty($bySelfArr[$k]) ? (strtotime($bySelfArr[$k].' '.$s)) : 0;

                    $data[$k]['start_time'] = !empty($bySelfTime) ? date("Y-m-d H:i:s", $bySelfTime) : '';
                    $data[$k]['end_time']   = !empty($bySelfTime) ? date("Y-m-d H:i:s", ($bySelfTime + $longTime)) : '';
                }else{
                    //每天/每周 排课
                    $sTime = strtotime($startTime) + ($fTime * $k);

                    $data[$k]['start_time'] = date("Y-m-d H:i:s", $sTime);
                    $data[$k]['end_time']   = date("Y-m-d H:i:s", ($sTime + $longTime));
                }
                
                $planDates[] = $data[$k]['start_time'];
                $planEndDates[] = $data[$k]['end_time'];
            }else{
                $data[$k]['video_public_type'] = $videoPublicType;
                $data[$k]['video_trial_time']  = $videoTrialTime;
            }
        }

        $res = !empty($data) ? course_plan_api::batchAddPlan($this->courseId, $this->classId, $data) : false;

        //修改课程时间
        if(!empty($planDates) && !empty($planEndDates)){
            sort($planDates);
            sort($planEndDates);
            $courseData = array('start_time'=>$planDates[0],'end_time'=>$planEndDates[count($planEndDates)-1]);
            course_api::setCourse($this->courseId, $courseData);
        } 

        if(!$res) $this->_setMsg(1, '操作失败');

        $this->_setMsg(0, '操作成功');
    }

    public function pageSortPlan(){
        $orderNoArr = !empty($_POST['orderno']) ? $_POST['orderno'] : array();

        if(empty($orderNoArr)) $this->_setMsg(4001, '排序失败');
        foreach($orderNoArr as $k=>$v){
            $data['order_no'][$v] = $k + 1;
        }

        $res = course_plan_api::batchSetPlan($data);
        if(!$res) $this->_setMsg(1, '操作失败');

        $this->_setMsg(0, '操作成功');
    }

    public function pagePlanInfo(){
        $planId   = !empty($_POST['planId']) ? (int)$_POST['planId'] : 0;
        $planInfo = course_api::getPlan($planId);

        if(empty($planInfo)) $this->_setMsg(3002, '获取数据失败');
        $teacherReg = utility_judgeid::checkCourseTeacher($this->courseId, $this->user['uid'], $teacherInfo);
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
                    $teachers[$val->teacher_id]['teacherId']  = $val->teacher_id;
                    $teachers[$val->teacher_id]['teacherName'] = $val->real_name;
                    if($val->teacher_id == $planInfo->user_plan_id) {
                        $teachers[$val->teacher_id]['selected'] = "1";
                    }else{
                        $teachers[$val->teacher_id]['selected'] = "0";
                    }
                }
                $teachers = array_values($teachers);
            }
        }

        $liveTypeArr  = array('无试看','试看整节');
        $videoTypeArr = array('无试看','试看整节','-2'=>'隐藏视频');
        $videoTrialTimeArr = array('300'=>'试看5分钟','600'=>'试看10分钟','1200'=>'试看20分钟');

        $planInfo->liveTypeName  = !empty($liveTypeArr[$planInfo->live_public_type]) ? $liveTypeArr[$planInfo->live_public_type] : '无试看';
        $planInfo->videoTypeName = !empty($vidoeTypeArr[$planInfo->video_public_type]) ? $vidoeTypeArr[$planInfo->video_public_type] : '无试看';
        $planInfo->videoTrialTime = !empty($videoTrialTimeArr[$planInfo->video_trial_time]) ? $videoTrialTimeArr[$planInfo->video_trial_time] : '0';
        $planInfo->teachers = $teachers;

        $this->_setMsg(1, '获取数据成功', $planInfo);
    }

    private function _setMsg($code, $msg='', $items=array()){
        $data = [
            'code' => $code,
            'msg'  => $msg,
        ];
        if(!empty($items)){
            $data['data'] = $items;
        }

        echo json_encode($data, JSON_UNESCAPED_UNICODE); exit;
    }
}
