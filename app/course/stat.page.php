<?php
class course_stat extends STpl{
    private $orgId;
    private $orgOwner;
    private $orgInfo;
    function __construct($inPath){
        $this->user = user_api::loginedUser();
        if(empty($this->user)){
            if(!empty($_SERVER['REQUEST_URI'])){
                $this->redirect("/site.main.login?url=/".implode('/',$inPath));
            }else{
                $this->redirect("/site.main.login?url=/".implode('/',$inPath));
            }
        }
        $org = user_organization::subdomain();
        if (!empty($org->userId)) {
            $this->orgOwner = $org->userId;
        }else{
            header('Location: https://www.'.$this->domain);
        }
        $this->orgInfo = user_organization::getOrgByOwner($this->orgOwner);
        if(!empty($this->orgInfo->oid)){
            $this->orgId = $this->orgInfo->oid;
        }
    }
//根据plan_id获取上课统计
    public function pageGetPlanStatByPid($inPath){
        $planId = !empty($inPath[3])?$inPath[3]:0;
        //如果为空就跳走
        if(empty($planId)){
            return $this->render('order/order_error.html');
        }

        $uId = $this->user["uid"];
        if(empty($uId)){
            if(!empty($_SERVER['REQUEST_URI'])){
                $this->redirect("/site.main.login?url=/".implode('/',$inPath));
            }else{
                $this->redirect("/site.main.login?url=/".implode('/',$inPath));
            }
        }
        //判断管理员
        $coursePlan = course_plan_api::getGetCoursePlanByPid($planId);
        $user=0;
        $userPlan = 0;
        if(!empty($coursePlan->data)) {
            $coursePlan = $coursePlan->data;
            $userPlan = $coursePlan->fk_user_plan;
            $classId = $coursePlan->fk_class;
        }
        $classInfo = course_class_api::getClassList($classId);
        if(!empty($classInfo)&&!empty($classInfo["result"][0])){
            $user = $classInfo["result"][0]["fk_user"];
            $userClass = $classInfo["result"][0]["fk_user_class"];
        }
        //$special = user_api::getTeacherSpecial($this->orgInfo->oid,$uId);
        $isAdmin=user_api::isAdmin($this->orgOwner,$uId);
        //if((empty($special)||$special->role!=2||$special->status!=1 ) && ($uId!=$user&&$uId!=$userPlan&&$uId!=$userClass&&$isAdmin==false)){
        if(($uId!=$user&&$uId!=$userPlan&&$uId!=$userClass&&$isAdmin==false)){

            return $this->render('order/order_error.html');
        }
        return $this->render('course/plan.statistical.html');
    }

    //根据plan_id获取上课统计
    public function pageGetPlanStatByPidAjax($inPath){
        $planId = !empty($inPath[3])?$inPath[3]:0;
        //如果为空就跳走
        if(empty($planId)){
            return json_encode(array("code"=>-1,"data"=>""));
        }

        $uId = $this->user["uid"];
        if(empty($uId)){
            return json_encode(array("code"=>-2,"data"=>""));
        }
        //判断管理员
        $coursePlan = course_plan_api::getGetCoursePlanByPid($planId);
        $user=0;
        $userPlan = 0;
        if(!empty($coursePlan->data)) {
            $coursePlan = $coursePlan->data;
            $userPlan = $coursePlan->fk_user_plan;
            $classId = $coursePlan->fk_class;
        }
        $classInfo = course_class_api::getClassList($classId);
        if(!empty($classInfo)&&!empty($classInfo["result"][0])){
            $user = $classInfo["result"][0]["fk_user"];
            $userClass = $classInfo["result"][0]["fk_user_class"];
        }
        //$special = user_api::getTeacherSpecial($this->orgInfo->oid,$uId);
        $isAdmin=user_api::isAdmin($this->orgOwner,$uId);
        //if((empty($special)||$special->role!=2||$special->status!=1) && $uId!=$user&&$uId!=$userPlan&&$uId!=$userClass&&$isAdmin==false){
        if($uId!=$user&&$uId!=$userPlan&&$uId!=$userClass&&$isAdmin==false){
            return json_encode(array("code"=>-3,"data"=>""));
        }
        $userTotalNum = course_class_api::getClassRegUserTotalNum($classId);
        //$planId= "4166";
        $courseName = "";
        $className = "";
        $teacherName = "";
        $userCount = 0;
        $videoId = 0;
        $planStatList = array();

        if(!empty($coursePlan)) {
            $courseName = $coursePlan->course_name;
            $className = $coursePlan->class_name;
            $teacherName = $coursePlan->teacher_name;
            $userCount = $coursePlan->user_count;
            $videoId  = $coursePlan->fk_video;
            $planStatList = stat_api::getPlanStatByPidStr($planId);
        }
        $totaltime = 0;
        $videoIdArr = array($videoId);
        $video = video_api::getVideoByIdArr($videoIdArr);
        if(!empty($video)){
            $totaltime = $video[0]->totaltime;
        }
        $planStat = array(
            "attendance"=>0,//到课率
            "vv_record"=>round($totaltime/60),//时长
            "discuss"=>0,//评论数
            "zan"=>0,//赞数
            "handup"=>0,//举手数
            "call"=>0,//发言数
            "max_online"=>0,//最大同事在线人数
            "on_time"=>0,//准时人数
            "late"=>0,//迟到人数
            "noTo"=>0,//未到到数
            "status"=>0,//1:准时，2：迟到
            "correct"=>0,//随堂测试正确率
            "answer_rate"=>0,//询问回答率
            "last_updated"=>0,//更新时间
        );
        //获取老师直播时长

        if(!empty($planStatList->data[0])){
            $planStatList = $planStatList->data[0];
            if($userCount>0){
                $planStat["attendance"] = round((($planStatList->on_time+$planStatList->late)/$userCount*100),2);
            }else{
                $planStat["attendance"]=0;
            }
            $planStat["discuss"] =$planStatList->discuss;
            $planStat["zan"] =$planStatList->zan;
            $planStat["handup"] =$planStatList->handup;
            $planStat["call"] =$planStatList->call;
            $planStat["max_online"] =$planStatList->max_online;
            $planStat["on_time"] =$planStatList->on_time;
            $planStat["late"] =$planStatList->late;
            $planStat["correct"] =$planStatList->correct;
            $planStat["status"] =$planStatList->status;
            $planStat["answer_rate"] =$planStatList->answer_rate;
            $planStat["last_updated"] = $planStatList->last_updated;
        }
        $planStat["noTo"] = !empty($userTotalNum)?($userTotalNum-$planStat["late"]-$planStat["on_time"]):0;
        $query = [
            'status'      => '1,2,3',
            'course_type' => '1,3',
            'plan_id'  => "$planId"
        ];

        $params = [
            'f'  => [
                'section_name'
            ],
            'q'  => $query,
            'ob' => ['start_time' => 'asc'],
            'p'  => 1,
            'pl' => 1,
        ];
        $planName = seek_api::seekPlan($params);
        $planName = $planName->data[0]->section_name;
        return json_encode(array("code"=>0,"data"=>array("courseName"=>$courseName,"teacherName"=>$teacherName,"className"=>$className,"planName"=>$planName,"planStatList"=>$planStat)));
    }

    //根据plan_id获取学生上课情况W
    public function pageGetUserPlanStatAjax($inPath){
        $planId = !empty($inPath[3])?$inPath[3]:0;
        $page = !empty($inPath[4])?$inPath[4]:1;
        $length = !empty($inPath[5])?$inPath[5]:50;
        //如果为空就跳走
        if(empty($planId)){
            return json_encode(array("code"=>-2,"data"=>""));
        }
        $uId = $this->user["uid"];
        if(empty($uId)){
            return json_encode(array("code"=>-2,"data"=>""));
        }
        //判断管理员
        $coursePlan = course_plan_api::getGetCoursePlanByPid($planId);
        $user=0;
        $userPlan = 0;
        $classId = 0;
        if(!empty($coursePlan->data)) {
            $coursePlan = $coursePlan->data;
            $userPlan = $coursePlan->fk_user_plan;
            $classId = $coursePlan->fk_class;
        }
        $classInfo = course_class_api::getClassList($classId);
        if(!empty($classInfo)&&!empty($classInfo["result"][0])){
            $user = $classInfo["result"][0]["fk_user"];
            $userClass = $classInfo["result"][0]["fk_user_class"];
        }
        //$special = user_api::getTeacherSpecial($this->orgInfo->oid,$uId);
        $isAdmin=user_api::isAdmin($this->orgOwner,$uId);
       // if((empty($special)||$special->role!=2||$special->status!=1) && $uId!=$user&&$uId!=$userPlan&&$uId!=$userClass&&$isAdmin==false){
        if($uId!=$user&&$uId!=$userPlan&&$uId!=$userClass&&$isAdmin==false){
            return json_encode(array("code"=>-3,"data"=>""));
        }
        $userIdArr = array();

        $userStat = stat_api::getUserPlanStatByPid($planId,$page,$length);
        if(!empty($userStat)&& !empty($userStat->items)){
            foreach($userStat->items as $stat){
                $userIdArr[] = $stat-> fk_user;
            }
        }
        $userNameArr = array();
        $params = array("uidArr"=>$userIdArr);
        $userInfo = user_api::getUserInfoByUidArr($params);
        if(!empty($userInfo)&&!empty($userInfo->data)){
            foreach($userInfo->data as $user){
                $userNameArr[$user->user_id] = !empty($user->real_name)?$user->real_name:(!empty($user->name)?$user->name:"未设置");
            }
        }
        $statUser = array();
        $i=0;
        if(!empty($userStat)&& !empty($userStat->items)){
            foreach($userStat->items as &$stat){
                $statUser[$i]["userId"] = $stat->fk_user;
                if(!empty($userNameArr[$stat->fk_user])) {
                    $statUser[$i]["name"] = $userNameArr[$stat->fk_user];
                }else{
                    $statUser[$i]["name"]="未设置";
                }
                $statUser[$i]["orderNum"] = !empty($stat->order_num)?$stat->order_num:0;
                $statUser[$i]["status"] = !empty($stat->status)?$stat->status:0;
                $statUser[$i]["zan"] = !empty($stat->zan)?$stat->zan:0;
                $statUser[$i]["call"] = !empty($stat->call)?$stat->call:0;
                $statUser[$i]["handup"] = !empty($stat->handup)?$stat->handup:0;
                $statUser[$i]["discuss"] = !empty($stat->discuss)?$stat->discuss:0;
                $statUser[$i]["inClassTest"] = !empty($stat->in_class_test)?$stat->in_class_test:0;
                $statUser[$i]["ask"] = !empty($stat->ask)?$stat->ask:0;
                $statUser[$i]["vvRecord"] = !empty($stat->vt_live)?round(($stat->vt_live/60)):0;
                $statUser[$i]["vtRecord"] = !empty($stat->vt_record)?round(($stat->vt_record/60)):0;
                $statUser[$i]["vtLive"] = !empty($stat->vv_record)?$stat->vv_record:0;
                $i++;
            }
        }
        $userStatInfo = array();
        if($userStat->totalPage<100){
            $regUserIdArr = array();
            $regUser = course_class_api::getClassRegUser($classId);
            if(!empty($regUser)){
                foreach($regUser as $regUser1){
                    $regUserIdArr[] = $regUser1["fk_user"];
                }
            }
            $userStat1 = stat_api::getUserPlanStatByPid($planId,1,-1);
            if(!empty($userStat1)&&!empty($userStat1->items)){
                foreach($userStat1->items as $userId){
                    $userIdArr[] = $userId->fk_user;
                    $userStatInfo[$userId->fk_user] = $userId;
                }
            }
            $diffuserIdArr = array_diff($regUserIdArr, $userIdArr);
        }
        if(($page==$userStat->totalPage)||(!empty($diffuserIdArr)&&$i<50&&$userStat->totalPage<100)){
            $userNameArr = array();
            $params = array("uidArr"=>$diffuserIdArr);
            $userInfo = user_api::getUserInfoByUidArr($params);
            if(!empty($userInfo)&&!empty($userInfo->data)){
                foreach($userInfo->data as $user){
                    $userNameArr[$user->user_id] = !empty($user->real_name)?$user->real_name:(!empty($user->name)?$user->name:"未设置");
                }
            }
            foreach($diffuserIdArr as $v){
                $statUser[$i]["userId"] = $v;
                if(!empty($userNameArr[$v])) {
                    $statUser[$i]["name"] = $userNameArr[$v];
                }else{
                    $statUser[$i]["name"]="未设置";
                }
                $statUser[$i]["orderNum"] = 0;
                if(!empty($userStatInfo[$v]->order_num)){
                    $statUser[$i]["orderNum"] = $userStatInfo[$v]->order_num;
                }
                $statUser[$i]["status"] = 3;
                if(!empty($userStatInfo[$v]->status)){
                    $statUser[$i]["status"] = $userStatInfo[$v]->status;
                }
                $statUser[$i]["zan"] = 0;
                if(!empty($userStatInfo[$v]->zan)){
                    $statUser[$i]["zan"] = $userStatInfo[$v]->zan;
                }
                $statUser[$i]["call"] = 0;
                if(!empty($userStatInfo[$v]->call)){
                    $statUser[$i]["call"] = $userStatInfo[$v]->call;
                }
                $statUser[$i]["handup"] = 0;
                if(!empty($userStatInfo[$v]->handup)){
                    $statUser[$i]["handup"] = $userStatInfo[$v]->handup;
                }
                $statUser[$i]["discuss"] = 0;
                if(!empty($userStatInfo[$v]->discuss)){
                    $statUser[$i]["discuss"] = $userStatInfo[$v]->discuss;
                }
                $statUser[$i]["inClassTest"] = 0;
                if(!empty($userStatInfo[$v]->in_class_test)){
                    $statUser[$i]["inClassTest"] = $userStatInfo[$v]->in_class_test;
                }
                $statUser[$i]["ask"] = 0;
                if(!empty($userStatInfo[$v]->ask)){
                    $statUser[$i]["ask"] = $userStatInfo[$v]->ask;
                }
                $statUser[$i]["vvRecord"] = 0;
                if(!empty($userStatInfo[$v]->vt_live)){
                    $statUser[$i]["vvRecord"] = $userStatInfo[$v]->vt_live;
                }
                $statUser[$i]["vtRecord"] = 0;
                if(!empty($userStatInfo[$v]->vt_record)){
                    $statUser[$i]["vtRecord"] = $userStatInfo[$v]->vt_record;
                }
                $statUser[$i]["vtLive"] = 0;
                if(!empty($userStatInfo[$v]->vv_record)){
                    $statUser[$i]["vtLive"] = $userStatInfo[$v]->vv_record;
                }
                $i++;
            }
        }
        if(empty($userStat->totalPage)){
            $userStat->totalPage=1;
        }
        if(!empty($statUser)){
            return json_encode(array("code"=>0,"data"=>$statUser,"totalPage"=>$userStat->totalPage));
        }
        return json_encode(array("code"=>-1,"data"=>""));
    }

    //根据classId与suId获取上课统计
    public function pageGetStudentPlanStatByPid($inPath){
        $classId = !empty($inPath[3])?$inPath[3]:0;
        $suId = !empty($inPath[4])?$inPath[4]:0;
        if(empty($classId)||empty($suId)){
            return $this->render('order/order_error.html');
        }
        $uId = $this->user["uid"];
        if(empty($uId)){
            if(!empty($_SERVER['REQUEST_URI'])){
                $this->redirect("/site.main.login?url=/".implode('/',$inPath));
            }else{
                $this->redirect("/site.main.login?url=/".implode('/',$inPath));
            }
        }
        //判断权限
        $user=0;
        $userClass = 0;
        $signUpId = 0;
        $courseId = 0;
        $classInfo = course_class_api::getClassList($classId);
        if(!empty($classInfo)&&!empty($classInfo["result"][0])){
            $user = $classInfo["result"][0]["fk_user"];
            $userClass = $classInfo["result"][0]["fk_user_class"];
            $courseId = $classInfo["result"][0]["fk_course"];
        }
        if(!empty($courseId)) {
            $UserReg = interface_user_api::getUserRegCourse($suId, array($courseId));
            if(!empty($UserReg)&&!empty($UserReg["items"])){
                $signUpId = $UserReg["items"][0]["fk_user"];
            }

        }
        //$special = user_api::getTeacherSpecial($this->orgInfo->oid,$uId);
        $isAdmin=user_api::isAdmin($this->orgOwner,$uId);
        //if((empty($special)||$special->role!=2||$special->status!=1) && $uId!=$user&&$uId!=$userClass&&$uId!=$signUpId&&$isAdmin==false){
        if($uId!=$user&&$uId!=$signUpId&&$uId!=$userClass&&$isAdmin==false){
            return $this->render('order/order_error.html');
        }
        $this->assign("classId",$classId);
        $this->assign("userId",$suId);
        return $this->render('course/student.class.statistical.html');
    }
    public function pageGetStudentPlanStatByPidAjax($inPath){
        $classId = !empty($inPath[3])?$inPath[3]:0;
        $suId = !empty($inPath[4])?$inPath[4]:0;
        if(empty($classId)||empty($suId)){
            return json_encode(array("code"=>-2,"data"=>""));
        }
        $uId = $this->user["uid"];
        if(empty($uId)){
            return json_encode(array("code"=>-2,"data"=>""));
        }
        //判断权限
        $user=0;
        $userClass = 0;
        $signUpId = 0;
        $courseId = 0;
        $className = "";
        $courseName = "";
        $classInfo = course_class_api::getClassList($classId);
        if(!empty($classInfo)&&!empty($classInfo["result"][0])){
            $user = $classInfo["result"][0]["fk_user"];
            $userClass = $classInfo["result"][0]["fk_user_class"];
            $courseId = $classInfo["result"][0]["fk_course"];
            $className = $classInfo["result"][0]["name"];
        }
        if(!empty($courseId)) {
            $courseInfo = course_api::getCourseOne($courseId);
            if(!empty($courseInfo)&&!empty($courseInfo->title)){
                $courseName = $courseInfo->title;
            }
            $UserReg = interface_user_api::getUserRegCourse($suId, array($courseId));
            if(!empty($UserReg)&&!empty($UserReg["items"])){
                $signUpId = $UserReg["items"][0]["fk_user"];
            }

        }
        //$special = user_api::getTeacherSpecial($this->orgInfo->oid,$uId);
        $isAdmin=user_api::isAdmin($this->orgOwner,$uId);
        //if((empty($special)||$special->role!=2||$special->status!=1) && $uId!=$user&&$uId!=$userClass&&$uId!=$signUpId&&$isAdmin==false){
        if($uId!=$user&&$uId!=$userClass&&$uId!=$signUpId&&$isAdmin==false){
            return json_encode(array("code"=>-3,"data"=>""));
        }
        $orgOwner = $this->orgOwner;
        $classIds = array("$classId");
        //这个班级有几个结束的plan
        $planGroupList = course_api::endgroupbyclassids($orgOwner,$classIds);
        $planIdArr = array();
        $planCount=1;
        if(!empty($planGroupList->data)){
            foreach($planGroupList->data as $plan){
                $planIdArr[] = $plan->pk_plan;
            }
            $planCount = count($planGroupList->data);
        }
        $planInfo = array();
        if(!empty($planIdArr)) {
            $query = [
                'status' => '1,2,3',
                'course_type' => '1,3',
                'plan_id' => implode(',', $planIdArr)
            ];

            $params = [
                'f' => [
                    'section_name',
                    'plan_id'
                ],
                'q' => $query,
                'ob' => ['start_time' => 'asc'],
            ];
            $planInfo = seek_api::seekPlan($params);
        }
        $planName = array();
        if(!empty($planInfo->data)){
            foreach($planInfo->data as $name){
                $planName[$name->plan_id] = $name->section_name;
            }
        }
        $userPlanStat = stat_api::getUserPlanStatByPidArr($suId,$planIdArr);
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
        $userStat = array();
        $planStatCorrectCount=0;
        $planStatAnswerRateCount=0;
        $planStatCount = 0;
        if(!empty($userPlanStat)&&!empty($planName)){
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
                if($stat->status==1 || $stat->status==2){
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

                $userStat[$stat->fk_plan]["vvRecord"] =round($stat->vt_live/60);
                $userStat[$stat->fk_plan]["discuss"] =$stat->discuss;
                $userStat[$stat->fk_plan]["zan"] =$stat->zan;
                $userStat[$stat->fk_plan]["handup"] =$stat->handup;
                $userStat[$stat->fk_plan]["call"] =$stat->call;
                $userStat[$stat->fk_plan]["inClassTest"] =$stat->in_class_test;
                $userStat[$stat->fk_plan]["ask"] =$stat->ask;
                $userStat[$stat->fk_plan]["vtRecord"] = round($stat->vt_record/60);
                $userStat[$stat->fk_plan]["vtLive"] = $stat->vv_record;
                $userStat[$stat->fk_plan]["orderNum"] = $stat->order_num;
                $userStat[$stat->fk_plan]["status"] = $stat->status;
                if(!empty($planName[$stat->fk_plan])){
                    $userStat[$stat->fk_plan]["planName"] = $planName[$stat->fk_plan];
                    unset($planName[$stat->fk_plan]);
                }else{
                    $userStat[$stat->fk_plan]["planName"] = '';
                }
                if((!empty($stat->classroom_test_count)&&$stat->classroom_test_count>0)||(!empty($stat->in_class_test))){
                    $planStatCorrectCount +=1;
                }
                if((!empty($stat->ask_count)&&$stat->ask_count>0)||(!empty($stat->ask))){
                    $planStatAnswerRateCount +=1;
                }
                $planStatCount +=1;
            }
            if(!empty($planName)){
                foreach($planName as $kk=>$vv){
                    $userStat[$kk]["vvRecord"] =0;
                    $userStat[$kk]["discuss"] =0;
                    $userStat[$kk]["zan"] =0;
                    $userStat[$kk]["handup"] =0;
                    $userStat[$kk]["call"] =0;
                    $userStat[$kk]["inClassTest"] =0;
                    $userStat[$kk]["ask"] =0;
                    $userStat[$kk]["vtRecord"] = 0;
                    $userStat[$kk]["vtLive"] = 0;
                    $userStat[$kk]["orderNum"] = 0;
                    $userStat[$kk]["status"] = 0;
                    $userStat[$kk]["planName"] = $vv;
                }
            }
            $userStatTotal["noTo"] = $planStatCount-($userStatTotal["onTime"]+$userStatTotal["late"]);
            $userStatTotal["vvRecord"] = round(($userStatTotal["vvRecord"]/60));
            $userStatTotal["vtRecord"] = round(($userStatTotal["vtRecord"]/60));
            if($planStatCorrectCount>0) {
                $userStatTotal["correct"] = round(($userStatTotal["correct"] / 100 / $planStatCorrectCount), 2);
            }
            if($planStatAnswerRateCount>0) {
                $userStatTotal["answerRate"] = round(($userStatTotal["answerRate"] / 100 / $planStatAnswerRateCount), 2);
            }
            if($planCount>0) {
                $userStatTotal["attendance"] = round(($userStatTotal["attendance"] / $planStatCount * 100), 2);
            }
            if($userStatTotal["attendance"]>100){
                $userStatTotal["attendance"]=100;
            }
        }
        $studentName="";
        $userInfo = user_api::getUser($suId);
        if(!empty($userInfo)){
            $studentName = !empty($userInfo->real_name)?$userInfo->real_name:$userInfo->name;
        }
        return json_encode(array("code"=>0,"data"=>array("courseName"=>$courseName,"studentName"=>$studentName,"className"=>$className,"userStatTotal"=>$userStatTotal,"userStat"=>$userStat)));
    }

    //获取学生答题
    public function pageGetStudentQuestion($inPath){
        $planId = !empty($inPath[3])?$inPath[3]:0;
        $suId = !empty($inPath[4])?$inPath[4]:0;
        //如果为空就跳走
        if(empty($planId)||empty($suId)){
            return $this->render('order/order_error.html');
        }
        $uId = $this->user["uid"];
        if(empty($uId)){
            if(!empty($_SERVER['REQUEST_URI'])){
                $this->redirect("/site.main.login?url=/".implode('/',$inPath));
            }else{
                $this->redirect("/site.main.login?url=/".implode('/',$inPath));
            }
        }
        //判断管理员
        $coursePlan = course_plan_api::getGetCoursePlanByPid($planId);
        $user=0;
        $userPlan = 0;
        $signUpId = 0;
        $userClass = 0;
        $courseId= 0 ;
        if(!empty($coursePlan->data)) {
            $coursePlan = $coursePlan->data;
            $userPlan = $coursePlan->fk_user_plan;
            $classId = $coursePlan->fk_class;
        }
        $classInfo = course_class_api::getClassList($classId);
        if(!empty($classInfo)&&!empty($classInfo["result"][0])){
            $user = $classInfo["result"][0]["fk_user"];
            $userClass = $classInfo["result"][0]["fk_user_class"];
            $courseId = $classInfo["result"][0]["fk_course"];
        }
        if(!empty($courseId)) {
            $UserReg = interface_user_api::getUserRegCourse($suId, array($courseId));
            if(!empty($UserReg)&&!empty($UserReg["items"])){
                $signUpId = $UserReg["items"][0]["fk_user"];
            }

        }
       // $special = user_api::getTeacherSpecial($this->orgInfo->oid,$uId);
        $isAdmin=user_api::isAdmin($this->orgOwner,$uId);
        //if((empty($special)||$special->role!=2||$special->status!=1) && $uId!=$user&&$uId!=$userPlan&&$uId!=$userClass&&$uId!=$signUpId&&$isAdmin=false){
        if($uId!=$user&&$uId!=$userPlan&&$uId!=$userClass&&$uId!=$signUpId&&$isAdmin=false){
            return $this->render('order/order_error.html');
        }
        return $this->render('course/student.question.statistical.html');
    }
    public function pageGetStudentQuestionAjax($inPath){
        $planId = !empty($inPath[3])?$inPath[3]:0;
        $suId = !empty($inPath[4])?$inPath[4]:0;
        //如果为空就跳走
        if(empty($planId)||empty($suId)){
            return json_encode(array("code"=>-2,"data"=>""));
        }
        $uId = $this->user["uid"];
        if(empty($uId)){
            return json_encode(array("code"=>-2,"data"=>""));
        }
        //判断管理员
        $coursePlan = course_plan_api::getGetCoursePlanByPid($planId);
        $courseName = "";
        $className = "";
        $user=0;
        $userPlan = 0;
        $userClass = 0;
        $courseId = 0;
        $signUpId= 0;
        $start_time="";
        if(!empty($coursePlan->data)) {
            $coursePlan = $coursePlan->data;
            $userPlan = $coursePlan->fk_user_plan;
            $classId = $coursePlan->fk_class;
            $courseName = $coursePlan->course_name;
            $className = $coursePlan->class_name;
            $start_time =date('Y-m-d',strtotime($coursePlan->start_time));
        }
        $classInfo = course_class_api::getClassList($classId);
        if(!empty($classInfo)&&!empty($classInfo["result"][0])){
            $user = $classInfo["result"][0]["fk_user"];
            $userClass = $classInfo["result"][0]["fk_user_class"];
            $courseId = $classInfo["result"][0]["fk_course"];
        }
        if(!empty($courseId)) {
            $UserReg = interface_user_api::getUserRegCourse($suId, array($courseId));
            if(!empty($UserReg)&&!empty($UserReg["items"])){
                $signUpId = $UserReg["items"][0]["fk_user"];
            }

        }
        //$special = user_api::getTeacherSpecial($this->orgInfo->oid,$uId);
        $isAdmin=user_api::isAdmin($this->orgOwner,$uId);
        //if((empty($special)||$special->role!=2||$special->status!=1) && $uId!=$user&&$uId!=$userPlan&&$uId!=$userClass&&$uId!=$signUpId&&$isAdmin==false){
        if($uId!=$user&&$uId!=$userPlan&&$uId!=$userClass&&$uId!=$signUpId&&$isAdmin==false){
            return json_encode(array("code"=>-3,"data"=>""));
        }
//获取用户答题统计
        $question_ret = course_api::getPlanQuestionByPid($planId);
        $answer_ret = exam_api::getLogUserQuestionByPid($suId,$planId);
        $answerList = array();
        $questionTotal = array(
            "createTime"=>$start_time,//答题时间
            "name"=>"",//学生姓名
            "questionCount"=>0,//题目数量
            "correct"=>"",//正确率
            "answer"=>0,//答对题目数
            "wrong"=>0,//错误题目数
            "notAnswer"=>0//未答题目数
        );
        if(!empty($answer_ret) && !empty($answer_ret->data)){
            foreach($answer_ret->data as $answer){
                $answerList[$answer->fk_question]["options"] = explode(',',$answer->options);
                $answerList[$answer->fk_question]["correct"] = $answer->correct;
                if($answer->correct==1){
                    $questionTotal["answer"] +=1;
                }elseif($answer->correct==0){
                    $questionTotal["wrong"] +=1;
                }
                if(strtotime($answer->create_time)>strtotime($questionTotal["createTime"])&&strtotime($questionTotal["createTime"])<0){
                    $questionTotal["createTime"] = date('Y-m-d',strtotime($answer->create_time));
                }
            }
        }
        $questionExamList = array();
        if(!empty($question_ret) && !empty($question_ret->data)){
            foreach($question_ret->data as &$question){
                $questionExamList[$question->pk_plan_exam]["qDesc"] = $question->q_desc;
                $questionExamList[$question->pk_plan_exam]["qDescImg"] = !empty($question->q_desc_img)?interface_func::imgUrl($question->q_desc_img):'';
                if($question->a){
                    $questionExamList[$question->pk_plan_exam]["options"][]=$question->a;
                }
                if($question->b){
                    $questionExamList[$question->pk_plan_exam]["options"][]=$question->b;
                }
                if($question->c){
                    $questionExamList[$question->pk_plan_exam]["options"][]=$question->c;
                }
                if($question->d){
                    $questionExamList[$question->pk_plan_exam]["options"][]=$question->d;
                }
                if($question->e){
                    $questionExamList[$question->pk_plan_exam]["options"][]=$question->e;
                }
                $questionExamList[$question->pk_plan_exam]["answer"] = explode(',',$question->answer);
                if(!empty($answerList[$question->pk_plan_exam])) {
                    $questionExamList[$question->pk_plan_exam]["userAnswer"] = $answerList[$question->pk_plan_exam];
                }else{
                    $questionExamList[$question->pk_plan_exam]["userAnswer"] = array();
                }
                $questionExamList[$question->pk_plan_exam]["createTime"][]=$question->last_updated;
                $questionTotal["questionCount"] += 1;
            }
            $question = $question_ret->data;
        }

        $questionPhraseList = array();
        $phraseIdArr = array();
        $planPhraseIdArr = array();

        $questionPhrase = course_phrase_api::getPlanPhraseByPid($planId);
        $answerList = array();
        if(!empty($questionPhrase)&&!empty($questionPhrase->data)){
            foreach($questionPhrase->data as $qPhrase){
                $phraseIdArr[$qPhrase->fk_phrase] = $qPhrase->fk_phrase;
                $planPhraseIdArr[] = $qPhrase->pk_plan_phrase;
                $questionPhraseList[$qPhrase->pk_plan_phrase]["phrase"] = $qPhrase->fk_phrase;
                $questionPhraseList[$qPhrase->pk_plan_phrase]["createTime"] = $qPhrase->last_updated;
                if(strtotime($qPhrase->last_updated)>strtotime($questionTotal["createTime"])){
                    $questionTotal["createTime"] = date('Y-m-d',strtotime($qPhrase->last_updated));
                }
                $questionTotal["questionCount"] += 1;
                $answerList[$qPhrase->pk_plan_phrase]=  $qPhrase->answer_right;
            }
        }
        $planPhraseLog = stat_api::getPlanPhraseLogByPid($planId,$suId,$planPhraseIdArr);
        if(!empty($planPhraseLog)&&!empty($planPhraseLog->items)){
            foreach($planPhraseLog->items as $phraseLog) {
                if (!empty($questionPhraseList[$phraseLog->fk_plan_phrase]["phrase"])) {
                    if (empty($questionPhraseList[$phraseLog->fk_plan_phrase]["createTime"])) {
                        $questionPhraseList[$phraseLog->fk_plan_phrase]["createTime"] = '';

                    }
                    if (strtotime($questionPhraseList[$phraseLog->fk_plan_phrase]["createTime"]) < strtotime($phraseLog->last_updated)) {
                        $questionPhraseList[$phraseLog->fk_plan_phrase]["createTime"] = $phraseLog->last_updated;
                    }
                    if (!empty($questionPhraseList[$phraseLog->fk_plan_phrase]["phraseLog"])) {
                        if (!empty($phraseLog->answer)) {
                            $questionPhraseList[$phraseLog->fk_plan_phrase]["phraseLog"]["answer"] = explode(',', $phraseLog->answer);
                            $questionPhraseList[$phraseLog->fk_plan_phrase]["phraseLog"]["answerStatus"] = $phraseLog->answer_status;
                            if (!empty($answerList[$phraseLog->fk_plan_phrase])) {
                                $questionPhraseList[$phraseLog->fk_plan_phrase]["phraseLog"]["answerRight"] = $answerList[$phraseLog->fk_plan_phrase];
                            } else {
                                $questionPhraseList[$phraseLog->fk_plan_phrase]["phraseLog"]["answerRight"] = "";
                            }
                        }
                    } else {
                        $questionPhraseList[$phraseLog->fk_plan_phrase]["phraseLog"]["answer"] = explode(',', $phraseLog->answer);
                        $questionPhraseList[$phraseLog->fk_plan_phrase]["phraseLog"]["answerStatus"] = $phraseLog->answer_status;
                        if (!empty($answerList[$phraseLog->fk_plan_phrase])) {
                            $questionPhraseList[$phraseLog->fk_plan_phrase]["phraseLog"]["answerRight"] = $answerList[$phraseLog->fk_plan_phrase];
                        } else {
                            $questionPhraseList[$phraseLog->fk_plan_phrase]["phraseLog"]["answerRight"] = "";
                        }
                    }

                    if ($phraseLog->answer_status == 1) {
                        $questionTotal["answer"] += 1;
                    } elseif ($phraseLog->answer_status == -1) {
                        $questionTotal["wrong"] += 1;
                    }
                }
            }
        }

        $phraseList = array();
        if(!empty($phraseIdArr)){
            $phrase = course_phrase_api::getPhraseIdArr($phraseIdArr);
            if(!empty($phrase)&&!empty($phrase->data->items)){
                foreach($phrase->data->items as $phrase){
                    $phraseList[$phrase->pk_phrase]["type"] = $phrase->type;
                    if($phrase->type==3){
                        $option = json_decode($phrase->answer);
                        foreach($option as $k=>&$o){
                            $o = trim(preg_replace('|[0-9a-zA-Z]+|', '', trim($o)));
                        }
                        $phraseList[$phrase->pk_phrase]["option"] = $option;
                    }else {
                        $phraseList[$phrase->pk_phrase]["option"] = json_decode($phrase->answer);
                    }
                    $phraseList[$phrase->pk_phrase]["qDesc"] = $phrase->question;
                    $phraseList[$phrase->pk_phrase]["qDescImg"] = !empty($phrase->question_img)?interface_func::imgUrl($phrase->question_img):'';
                }
            }
        }
        if(!empty($questionPhraseList)){
            foreach($questionPhraseList as &$list){
                if(!empty($list["phrase"])&&!empty($phraseList[$list["phrase"]])){
                    $list["phrase"] = $phraseList[$list["phrase"]];
                }
            }
        }
        if(!empty($questionTotal["questionCount"])) {
            $questionTotal["notAnswer"] = $questionTotal["questionCount"] - ($questionTotal["answer"] + $questionTotal["wrong"]);
            $questionTotal["correct"] = round(($questionTotal["answer"]/$questionTotal["questionCount"]*100),2);
        }else{
            $questionTotal["notAnswer"] = 0;
            $questionTotal["correct"] = 0;
        }
        if(!empty($questionTotal["questionCount"])) {
            $questionTotal["correct"] = round(($questionTotal["answer"] / $questionTotal["questionCount"] * 100), 2);
        }else{
            $questionTotal["correct"]=0;
        }
        $userInfo = user_api::getUser($suId);
        $questionTotal["name"] = !empty($userInfo->name)?$userInfo->name:(!empty($userInfo->real_name)?$userInfo->real_name:'未设置');


        $query = [
            'status'      => '1,2,3',
            'course_type' => '1,3',
            'plan_id'  => "$planId"
        ];

        $params = [
            'f'  => [
                'section_name'
            ],
            'q'  => $query,
            'ob' => ['start_time' => 'asc'],
            'p'  => 1,
            'pl' => 1,
        ];
        $planName = seek_api::seekPlan($params);
        $planName = $planName->data[0]->section_name;
        return json_encode(array("code"=>0,"data"=>array("courseName"=>$courseName,"className"=>$className,"planName"=>$planName,"questionTotal"=>$questionTotal,"questionExamList"=>$questionExamList,"questionPhraseList"=>$questionPhraseList)));
    }

    //获取班级答题
    //获取班级答题
    public function pageGetClassQuestion($inPath){
        $planId = !empty($inPath[3])?$inPath[3]:0;
        //如果为空就跳走
        if(empty($planId)){
            return $this->render('order/order_error.html');
        }
        $uId = $this->user["uid"];
        if(empty($uId)){
            if(!empty($_SERVER['REQUEST_URI'])){
                $this->redirect("/site.main.login?url=/".implode('/',$inPath));
            }else{
                $this->redirect("/site.main.login?url=/".implode('/',$inPath));
            }
        }
        //判断管理员
        $coursePlan = course_plan_api::getGetCoursePlanByPid($planId);
        $user=0;
        $userPlan = 0;
        $userClass = 0;
        if(!empty($coursePlan->data)) {
            $coursePlan = $coursePlan->data;
            $userPlan = $coursePlan->fk_user_plan;
            $classId = $coursePlan->fk_class;
        }
        $classInfo = course_class_api::getClassList($classId);
        if(!empty($classInfo)&&!empty($classInfo["result"][0])){
            $user = $classInfo["result"][0]["fk_user"];
            $userClass = $classInfo["result"][0]["fk_user_class"];
        }
        //$special = user_api::getTeacherSpecial($this->orgInfo->oid,$uId);
        $isAdmin=user_api::isAdmin($this->orgOwner,$uId);
        //if((empty($special)||$special->role!=2||$special->status!=1) && $uId!=$user&&$uId!=$userPlan&&$uId!=$userClass&&$isAdmin==false){
        if($uId!=$user&&$uId!=$userPlan&&$uId!=$userClass&&$isAdmin==false){
            return $this->render('order/order_error.html');
        }
        return $this->render('course/class.question.statistical.html');
    }
    public function pageGetClassQuestionAjax($inPath){
        $planId = !empty($inPath[3])?$inPath[3]:0;
        //如果为空就跳走
        if(empty($planId)){
            return json_encode(array("code"=>-2,"data"=>""));
        }
        $uId = $this->user["uid"];
        if(empty($uId)){
            return json_encode(array("code"=>-2,"data"=>""));
        }
        //判断管理员
        $coursePlan = course_plan_api::getGetCoursePlanByPid($planId);
        $courseName = "";
        $className = "";
        $teacherName = "";
        $user=0;
        $userPlan = 0;
        $userClass = 0;
        $start_time = "";
        if(!empty($coursePlan->data)) {
            $coursePlan = $coursePlan->data;
            $userPlan = $coursePlan->fk_user_plan;
            $classId = $coursePlan->fk_class;
            $courseName = $coursePlan->course_name;
            $className = $coursePlan->class_name;
            $teacherName = $coursePlan->teacher_name;
            $start_time =date('Y-m-d',strtotime($coursePlan->start_time));
        }
        $classInfo = course_class_api::getClassList($classId);
        if(!empty($classInfo)&&!empty($classInfo["result"][0])){
            $user = $classInfo["result"][0]["fk_user"];
            $userClass = $classInfo["result"][0]["fk_user_class"];
        }
        //$special = user_api::getTeacherSpecial($this->orgInfo->oid,$uId);
        $isAdmin=user_api::isAdmin($this->orgOwner,$uId);
        //if((empty($special)||$special->role!=2||$special->status!=1) && $uId!=$user&&$uId!=$userPlan&&$uId!=$userClass&&$isAdmin==false){
        if($uId!=$user&&$uId!=$userPlan&&$uId!=$userClass&&$isAdmin==false){
            json_encode(array("code"=>-3,"data"=>""));
        }
        //获取班级答题统计
        $questionTotal = array(
            "createTime"=>$start_time,//答题时间
            "name"=>"",//老师姓名
            "questionCount"=>0,//题目数量
            "correct"=>"",//正确率
        );
        //备课出题
        $examStatList = array();
        $questionExamList = array();
        $phraseStatList = array();
        $questionPhraseList = array();
        $phraseIdArr = array();
        $examStat = stat_api::getClassExamStat($planId);
        if(!empty($examStat)&&!empty($examStat->data)){
            foreach($examStat->data as $examStat){
                $examStatList[$examStat->fk_question]["correctAnswer"] = $examStat->correct_answer;
                $examStatList[$examStat->fk_question]["rateCorrect"] = round($examStat->rate_correct,2);
                $examStatList[$examStat->fk_question]["totalAnswer"] = $examStat->total_answer;
                $examStatList[$examStat->fk_question]["notAnswer"] = $examStat->not_answer;
                $examStatList[$examStat->fk_question]["option"] = is_object(json_decode($examStat->option)) ? get_object_vars(json_decode($examStat->option)) : json_decode($examStat->option);
                $examStatList[$examStat->fk_question]["optionUser"] = is_object(json_decode($examStat->option_user)) ? get_object_vars(json_decode($examStat->option_user)) : json_decode($examStat->option_user);
            }
        }
        $phraseStat = stat_api::getClassPhraseStat($planId);
        if(!empty($phraseStat)&&!empty($phraseStat->data)){
            foreach($phraseStat->data as $phraseStat){
                $phraseStatList[$phraseStat->fk_plan_phrase]["correctAnswer"] = $phraseStat->correct_answer;
                $phraseStatList[$phraseStat->fk_plan_phrase]["rateCorrect"] = round($phraseStat->rate_correct,2);
                $phraseStatList[$phraseStat->fk_plan_phrase]["totalAnswer"] = $phraseStat->total_answer;
                $phraseStatList[$phraseStat->fk_plan_phrase]["notAnswer"] = $phraseStat->not_answer;
                $phraseStatList[$phraseStat->fk_plan_phrase]["option"] = is_object(json_decode($phraseStat->option)) ? get_object_vars(json_decode($phraseStat->option)) : json_decode($phraseStat->option);
                $phraseStatList[$phraseStat->fk_plan_phrase]["optionUser"] = is_object(json_decode($phraseStat->option_user)) ? get_object_vars(json_decode($phraseStat->option_user)) : json_decode($phraseStat->option_user);
                $phraseStatList[$phraseStat->fk_plan_phrase]["wrongAnswerUser"] = is_object(json_decode($phraseStat->wrong_answer_user)) ? get_object_vars(json_decode($phraseStat->wrong_answer_user)) : json_decode($phraseStat->wrong_answer_user);
            }
        }
        $question_ret = course_api::getPlanQuestionByPid($planId);
        if(!empty($question_ret) && !empty($question_ret->data)){
            foreach($question_ret->data as &$question){
                $questionExamList[$question->pk_plan_exam]["qDesc"] = $question->q_desc;
                $questionExamList[$question->pk_plan_exam]["qDescImg"] = !empty($question->q_desc_img)?interface_func::imgUrl($question->q_desc_img):'';
                $questionExamList[$question->pk_plan_exam]["answer"] = explode(',',$question->answer);
                if($question->a){
                    $questionExamList[$question->pk_plan_exam]["options"]["A"]["text"]=$question->a;
                    if(!empty($examStatList[$question->pk_plan_exam]["option"]["A"])){
                        $questionExamList[$question->pk_plan_exam]["options"]["A"]["select"] =   $examStatList[$question->pk_plan_exam]["option"]["A"]->select;
                        $questionExamList[$question->pk_plan_exam]["options"]["A"]["percentage"] =   $examStatList[$question->pk_plan_exam]["option"]["A"]->percentage;
                    }
                    if(in_array('a',$questionExamList[$question->pk_plan_exam]["answer"])){
                        $questionExamList[$question->pk_plan_exam]["options"]["A"]["status"] =1;
                    }else{
                        $questionExamList[$question->pk_plan_exam]["options"]["A"]["status"] =0;
                    }
                }
                if($question->b){
                    $questionExamList[$question->pk_plan_exam]["options"]["B"]["text"]=$question->b;
                    if(!empty($examStatList[$question->pk_plan_exam]["option"]["B"])){
                        $questionExamList[$question->pk_plan_exam]["options"]["B"]["select"] =   $examStatList[$question->pk_plan_exam]["option"]["B"]->select;
                        $questionExamList[$question->pk_plan_exam]["options"]["B"]["percentage"] =   $examStatList[$question->pk_plan_exam]["option"]["B"]->percentage;
                    }
                    if(in_array('b',$questionExamList[$question->pk_plan_exam]["answer"])){
                        $questionExamList[$question->pk_plan_exam]["options"]["B"]["status"] =1;
                    }else{
                        $questionExamList[$question->pk_plan_exam]["options"]["B"]["status"] =0;
                    }
                }
                if($question->c){
                    $questionExamList[$question->pk_plan_exam]["options"]["C"]["text"]=$question->c;
                    if(!empty($examStatList[$question->pk_plan_exam]["option"]["C"])){
                        $questionExamList[$question->pk_plan_exam]["options"]["C"]["select"] =   $examStatList[$question->pk_plan_exam]["option"]["C"]->select;
                        $questionExamList[$question->pk_plan_exam]["options"]["C"]["percentage"] =   $examStatList[$question->pk_plan_exam]["option"]["C"]->percentage;
                    }
                    if(in_array('c',$questionExamList[$question->pk_plan_exam]["answer"])){
                        $questionExamList[$question->pk_plan_exam]["options"]["C"]["status"] =1;
                    }else{
                        $questionExamList[$question->pk_plan_exam]["options"]["C"]["status"] =0;
                    }
                }
                if($question->d){
                    $questionExamList[$question->pk_plan_exam]["options"]["D"]["text"]=$question->d;
                    if(!empty($examStatList[$question->pk_plan_exam]["option"]["D"])){
                        $questionExamList[$question->pk_plan_exam]["options"]["D"]["select"] =   $examStatList[$question->pk_plan_exam]["option"]["D"]->select;
                        $questionExamList[$question->pk_plan_exam]["options"]["D"]["percentage"] =   $examStatList[$question->pk_plan_exam]["option"]["D"]->percentage;
                    }
                    if(in_array('d',$questionExamList[$question->pk_plan_exam]["answer"])){
                        $questionExamList[$question->pk_plan_exam]["options"]["D"]["status"] =1;
                    }else{
                        $questionExamList[$question->pk_plan_exam]["options"]["D"]["status"] =0;
                    }
                }
                if($question->e){
                    $questionExamList[$question->pk_plan_exam]["options"]["E"]["text"]=$question->e;
                    if(!empty($examStatList[$question->pk_plan_exam]["option"]["E"])){
                        $questionExamList[$question->pk_plan_exam]["options"]["E"]["select"] =   $examStatList[$question->pk_plan_exam]["option"]["E"]->select;
                        $questionExamList[$question->pk_plan_exam]["options"]["E"]["percentage"] =   $examStatList[$question->pk_plan_exam]["option"]["E"]->percentage;
                    }
                    if(in_array('e',$questionExamList[$question->pk_plan_exam]["answer"])){
                        $questionExamList[$question->pk_plan_exam]["options"]["E"]["status"] =1;
                    }else{
                        $questionExamList[$question->pk_plan_exam]["options"]["E"]["status"] =0;
                    }
                }
                if(!empty($examStatList[$question->pk_plan_exam]["rateCorrect"])){
                    $questionExamList[$question->pk_plan_exam]["rateCorrect"] = round($examStatList[$question->pk_plan_exam]["rateCorrect"],2);
                    $questionTotal["correct"] += $examStatList[$question->pk_plan_exam]["rateCorrect"];
                }else{
                    $questionExamList[$question->pk_plan_exam]["rateCorrect"] = 0;
                }
                if(!empty($examStatList[$question->pk_plan_exam]["totalAnswer"])){
                    $questionExamList[$question->pk_plan_exam]["totalAnswer"] = $examStatList[$question->pk_plan_exam]["totalAnswer"];
                }else{
                    $questionExamList[$question->pk_plan_exam]["totalAnswer"] = 0;
                }
                if(!empty($examStatList[$question->pk_plan_exam]["notAnswer"])){
                    $questionExamList[$question->pk_plan_exam]["notAnswer"] = $examStatList[$question->pk_plan_exam]["notAnswer"];
                }else{
                    $questionExamList[$question->pk_plan_exam]["notAnswer"] = 0;
                }
                if(!empty($examStatList[$question->pk_plan_exam]["optionUser"])){
                    $questionExamList[$question->pk_plan_exam]["optionUser"] = $examStatList[$question->pk_plan_exam]["optionUser"];
                }else{
                    $questionExamList[$question->pk_plan_exam]["optionUser"] = array();
                }
                $questionExamList[$question->pk_plan_exam]["createTime"][]=$question->last_updated;
                if(strtotime($questionTotal["createTime"])<strtotime($question->last_updated)&&strtotime($question->create_time)<0) {
                    $questionTotal["createTime"] = date('Y-m-d',strtotime($question->last_updated));
                }
                $questionTotal["questionCount"] += 1;
            }
        }
        $questionPhrase = course_phrase_api::getPlanPhraseByPid($planId);
        $answerList = array();
        if(!empty($questionPhrase)&&!empty($questionPhrase->data)){
            foreach($questionPhrase->data as $qPhrase){
                $phraseIdArr[$qPhrase->fk_phrase] = $qPhrase->fk_phrase;
                $questionPhraseList[$qPhrase->pk_plan_phrase]["phrase"] = $qPhrase->fk_phrase;
                if(!empty($phraseStatList[$qPhrase->pk_plan_phrase]["rateCorrect"])){
                    $questionPhraseList[$qPhrase->pk_plan_phrase]["rateCorrect"] = round($phraseStatList[$qPhrase->pk_plan_phrase]["rateCorrect"],2);
                    $questionTotal["correct"] += $phraseStatList[$qPhrase->pk_plan_phrase]["rateCorrect"];
                }else{
                    $questionPhraseList[$qPhrase->pk_plan_phrase]["rateCorrect"] = 0;
                }
                if(!empty($phraseStatList[$qPhrase->pk_plan_phrase]["totalAnswer"])){
                    $questionPhraseList[$qPhrase->pk_plan_phrase]["totalAnswer"] = $phraseStatList[$qPhrase->pk_plan_phrase]["totalAnswer"];
                }else{
                    $questionPhraseList[$qPhrase->pk_plan_phrase]["totalAnswer"] = 0;
                }
                if(!empty($phraseStatList[$qPhrase->pk_plan_phrase]["notAnswer"])){
                    $questionPhraseList[$qPhrase->pk_plan_phrase]["notAnswer"] = $phraseStatList[$qPhrase->pk_plan_phrase]["notAnswer"];
                }else{
                    $questionPhraseList[$qPhrase->pk_plan_phrase]["notAnswer"] = 0;
                }
                if(!empty($phraseStatList[$qPhrase->pk_plan_phrase]["option"])){
                    $phraseStatList[$qPhrase->pk_plan_phrase]["option"] = $phraseStatList[$qPhrase->pk_plan_phrase]["option"];
                }else{
                    $phraseStatList[$qPhrase->pk_plan_phrase]["option"] = array();
                }
                if(!empty($phraseStatList[$qPhrase->pk_plan_phrase]["optionUser"])){
                    $questionPhraseList[$qPhrase->pk_plan_phrase]["optionUser"] = $phraseStatList[$qPhrase->pk_plan_phrase]["optionUser"];
                }else{
                    $questionPhraseList[$qPhrase->pk_plan_phrase]["optionUser"] = array();
                }
                if(!empty($phraseStatList[$qPhrase->pk_plan_phrase]["wrongAnswerUser"])){
                    $questionPhraseList[$qPhrase->pk_plan_phrase]["wrongAnswerUser"] = $phraseStatList[$qPhrase->pk_plan_phrase]["wrongAnswerUser"];
                }else{
                    $questionPhraseList[$qPhrase->pk_plan_phrase]["wrongAnswerUser"] = array();
                }
                $questionPhraseList[$qPhrase->pk_plan_phrase]["answerRight"] = $qPhrase->answer_right;
                $questionPhraseList[$qPhrase->pk_plan_phrase]["createTime"] = $qPhrase->last_updated;
                if(strtotime($questionTotal["createTime"])<strtotime($qPhrase->last_updated)) {
                    $questionTotal["createTime"] = date('Y-m-d',strtotime($qPhrase->last_updated));
                }
                $questionTotal["questionCount"] += 1;
                $answerList[$qPhrase->pk_plan_phrase]=  $qPhrase->answer_right;
            }
        }
        $phraseList = array();
        if(!empty($phraseIdArr)){
            $phrase = course_phrase_api::getPhraseIdArr($phraseIdArr);
            if(!empty($phrase)&&!empty($phrase->data->items)){
                foreach($phrase->data->items as $phrase){
                    $phraseList[$phrase->pk_phrase]["type"] = $phrase->type;
                    $phraseList[$phrase->pk_phrase]["option"] = json_decode($phrase->answer);
                    $phraseList[$phrase->pk_phrase]["qDesc"] = $phrase->question;
                    $phraseList[$phrase->pk_phrase]["qDescImg"] = !empty($phrase->question_img)?interface_func::imgUrl($phrase->question_img):'';
                }
            }
        }
        $a = array();
        if(!empty($questionPhraseList)){
            foreach($questionPhraseList as $k=>&$list){
                $answerArr = explode(',',$list["answerRight"]);
                if(!empty($list["phrase"])&&!empty($phraseList[$list["phrase"]])){
                    $type="1";
                    foreach($phraseList[$list["phrase"]] as $kk=>$phrase){
                        if($kk=="type"){
                            $type = $phrase;
                        }
                        if($kk=="option"){
                            if(!empty($phrase)) {
                                $count = count($phrase);
                                $i=0;
                                foreach ($phrase as $vvv) {
                                    if(!empty($vvv)&&$i<$count) {
                                        if($type=="3") {
                                            $a[$k][$i]["text"] = trim(preg_replace('|[0-9a-zA-Z]+|', '', trim($vvv)));
                                        }else{
                                            $a[$k][$i]["text"] = trim($vvv);
                                        }
                                        $testStr = trim(preg_replace('/([\x80-\xff]*)/i','',trim($vvv)));
                                        if (!empty($phraseStatList[$k]["option"][trim($testStr)])) {
                                            $a[$k][$i]["select"] = $phraseStatList[$k]["option"][trim($testStr)]->select;
                                            $a[$k][$i]["percentage"] = $phraseStatList[$k]["option"][trim($testStr)]->percentage;
                                        } else {
                                            $a[$k][$i]["select"] = 0;
                                            $a[$k][$i]["percentage"] = 0;
                                        }
                                        if(in_array($testStr,$answerArr)){
                                            $a[$k][$i]["status"] = 1;
                                        }else{
                                            $a[$k][$i]["status"] = 0;
                                        }
                                        $i++;
                                    }
                                }
                            }
                        }

                    }

                }
            }
            foreach($questionPhraseList as $k=>&$list){
                $list["phrase"] = $phraseList[$list["phrase"]];
                if(!empty($a[$k])) {
                    $list["phrase"]["option"] = $a[$k];
                }
            }
        }
        $planStat = stat_api::getPlanStatByPidStr($planId);
        $questionTotal["correct"] = !empty($planStat->data[0]->correct)?$planStat->data[0]->correct:0;
        if($questionTotal["correct"]==0) {
            if ($questionTotal["questionCount"] > 0) {
                $questionTotal["correct"] = round(($questionTotal["correct"] / $questionTotal["questionCount"]), 2);
            } else {
                $questionTotal["correct"] = 0;
            }
        }
        $questionTotal["name"] = !empty($teacherName)?$teacherName:'未设置';


        $query = [
            'status'      => '1,2,3',
            'course_type' => '1,3',
            'plan_id'  => "$planId"
        ];

        $params = [
            'f'  => [
                'section_name'
            ],
            'q'  => $query,
            'ob' => ['start_time' => 'asc'],
            'p'  => 1,
            'pl' => 1,
        ];
        $planName = seek_api::seekPlan($params);
        $planName = $planName->data[0]->section_name;
        return json_encode(array("code"=>0,"data"=>array("courseName"=>$courseName,"className"=>$className,"planName"=>$planName,"questionTotal"=>$questionTotal,"questionExamList"=>$questionExamList,"questionPhraseList"=>$questionPhraseList)));
    }

    //获取课程班级学习统计
    public function pageGetClassStatAjax($inPath){
        $classId = !empty($inPath[3])?$inPath[3]:0;
        if(empty($classId)){
            return json_encode(array("code"=>-2,"data"=>""));
        }
        $uId = $this->user["uid"];
        if(empty($uId)){
            return json_encode(array("code"=>-2,"data"=>""));
        }
        //判断权限
        $user=0;
        $userClass = 0;
        $classInfo = course_class_api::getClassList($classId);
        if(!empty($classInfo)&&!empty($classInfo["result"][0])){
            $user = $classInfo["result"][0]["fk_user"];
            $userClass = $classInfo["result"][0]["fk_user_class"];
        }
        //$special = user_api::getTeacherSpecial($this->orgInfo->oid,$uId);
        $isAdmin=user_api::isAdmin($this->orgOwner,$uId);
        //if((empty($special)||$special->role!=2||$special->status!=1) && $uId!=$user&&$uId!=$userClass&&$isAdmin==false){
        if($uId!=$user&&$uId!=$userClass&&$isAdmin==false){
            return json_encode(array("code"=>-3,"data"=>""));
        }
        $orgOwner = $this->orgOwner;
        $classIds = array("$classId");
        //这个班级有几个结束的plan
        $planGroupList = course_api::endgroupbyclassids($orgOwner,$classIds);
        $planIdArr = array();
        $videoIdArr = array();
        $planCount=1;
        if(!empty($planGroupList->data)){
            foreach($planGroupList->data as $plan){
                $planIdArr[$plan->fk_video] = $plan->pk_plan;
                $videoIdArr[] = $plan->fk_video;
            }
            $planCount = count($planGroupList->data);
        }
        $totaltime = array();
        if(!empty($videoIdArr)) {
            $video = video_api::getVideoByIdArr($videoIdArr);
            if (!empty($video)) {
                foreach($video as $video){
                    $totaltime[$planIdArr[$video->pk_video]] = $video->totaltime;
                }
            }
        }
        $planName = array();
        $planIdStr = implode(',', $planIdArr);
        if(!empty($planIdArr)) {
            $query = [
                'status' => '1,2,3',
                'course_type' => '1,3',
                'plan_id' => $planIdStr
            ];

            $params = [
                'f' => [
                    'section_name',
                    'plan_id'
                ],
                'q' => $query,
                'ob' => ['start_time' => 'asc'],
            ];
            $planInfo = seek_api::seekPlan($params);
            if (!empty($planInfo->data)) {
                foreach ($planInfo->data as $name) {
                    $planName[$name->plan_id] = $name->section_name;
                }
            }
        }
        $userTotalNum = course_class_api::getClassRegUserTotalNum($classId);
        $planStat = stat_api::getPlanStatByPidStr($planIdStr);
        $planStatList = array();
        if(!empty($planStat)&&!empty($planStat->data)&&!empty($planName)){
            foreach($planStat->data as &$stat){
                $planStatList[$stat->fk_plan]["name"] = $planName[$stat->fk_plan];
                $planStatList[$stat->fk_plan]["planId"] = $stat->fk_plan;
                if($userTotalNum>0) {
                    $planStatList[$stat->fk_plan]["attendance"] = round((($stat->on_time + $stat->late) / $userTotalNum*100),2);
                    if($planStatList[$stat->fk_plan]["attendance"]>100){
                        $planStatList[$stat->fk_plan]["attendance"]=100;
                    }
                }else{
                    $planStatList[$stat->fk_plan]["attendance"]=0;
                }
                $planStatList[$stat->fk_plan]["zan"] = $stat->zan;
                $planStatList[$stat->fk_plan]["call"] = $stat->call;
                $planStatList[$stat->fk_plan]["handup"] = $stat->handup;
                $planStatList[$stat->fk_plan]["discuss"] = $stat->discuss;
                $planStatList[$stat->fk_plan]["maxOnline"] = $stat->max_online;
                $planStatList[$stat->fk_plan]["correct"] = $stat->correct;
                if($planStatList[$stat->fk_plan]["correct"]>100){
                    $planStatList[$stat->fk_plan]["correct"]=100;
                }
                $planStatList[$stat->fk_plan]["answerRate"] = $stat->answer_rate;
                if($planStatList[$stat->fk_plan]["answerRate"]>100){
                    $planStatList[$stat->fk_plan]["answerRate"]=100;
                }
                if(isset($totaltime[$stat->fk_plan])) {
                    $planStatList[$stat->fk_plan]["vvRecord"] = round(($totaltime[$stat->fk_plan] / 60));
                }else{
                    $planStatList[$stat->fk_plan]["vvRecord"]=0;
                }
                $planStatList[$stat->fk_plan]["status"] = $stat->status;
            }
        }
        $data['planId'] = array_values($planStatList);
        if(!empty($data)){
            return json_encode(array("code"=>0,"data"=>$data));
        }
        return json_encode(array("code"=>-1,"data"=>array()));

    }

    //更新下载资料次数
    public function pageUpdateDownloadCount($inPath){
        $planAttachId = !empty($_POST["planAttachId"])?$_POST["planAttachId"]:0;
        if(empty($planAttachId)){
            return json_encode(array("code"=>-2,"data"=>array()));
        }
        $ret = stat_api::updateDownloadCount($planAttachId);
        if(!empty($ret)){
            return json_encode(array("code"=>0,"data"=>$ret));
        }
        return json_encode(array("code"=>-1,"data"=>array()));
    }

    //根据classId获取班级学生所有上课统计排名
    public function pageGetClassStudentStat($inPath)
    {
        $classId = !empty($inPath[3]) ? $inPath[3] : 0;
        if (empty($classId)) {
            return $this->render('order/order_error.html');
        }
        $uId = $this->user["uid"];
        if (empty($uId)) {
            if (!empty($_SERVER['REQUEST_URI'])) {
                $this->redirect("/site.main.login?url=/".implode('/',$inPath));
            } else {
                $this->redirect("/site.main.login?url=/".implode('/',$inPath));
            }
        }
        //判断权限
        $user = 0;
        $userClass = 0;
        $classInfo = course_class_api::getClassList($classId);
        if (!empty($classInfo) && !empty($classInfo["result"][0])) {
            $user = $classInfo["result"][0]["fk_user"];
            $userClass = $classInfo["result"][0]["fk_user_class"];
        }
        //$special = user_api::getTeacherSpecial($this->orgInfo->oid, $uId);
        $isAdmin = user_api::isAdmin($this->orgOwner, $uId);
        //if ((empty($special) || $special->role != 2 || $special->status != 1) && $uId != $user && $uId != $userClass && $isAdmin == false) {
        if ($uId != $user && $uId != $userClass && $isAdmin == false) {
            return $this->render('order/order_error.html');
        }
        return $this->render('course/class.student.statistical.html');
    }

    public function pageGetClassStudentStatAjax($inPath)
    {
        $classId = !empty($_REQUEST["classId"]) ? $_REQUEST["classId"] : 0;
        $length = !empty($_REQUEST["length"]) ? $_REQUEST["length"] : 50;
        $page = !empty($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
        if (empty($classId)) {
            return json_encode(array("code" => -2, "data" => ""));
        }
        $uId = $this->user["uid"];
        if (empty($uId)) {
            return json_encode(array("code" => -2, "data" => ""));
        }
        //判断权限
        $user = 0;
        $userClass = 0;
        $courseId = 0;
        $className = "";
        $courseName = "";
        $classInfo = course_class_api::getClassList($classId);
        if (!empty($classInfo) && !empty($classInfo["result"][0])) {
            $user = $classInfo["result"][0]["fk_user"];
            $userClass = $classInfo["result"][0]["fk_user_class"];
            $courseId = $classInfo["result"][0]["fk_course"];
            $className = $classInfo["result"][0]["name"];
        }
        if (!empty($courseId)) {
            $courseInfo = course_api::getCourseOne($courseId);
            if (!empty($courseInfo) && !empty($courseInfo->title)) {
                $courseName = $courseInfo->title;
            }

        }
        //$special = user_api::getTeacherSpecial($this->orgInfo->oid, $uId);
        $isAdmin = user_api::isAdmin($this->orgOwner, $uId);
        //if ((empty($special) || $special->role != 2 || $special->status != 1) && $uId != $user && $uId != $userClass && $isAdmin == false) {
        if ($uId != $user && $uId != $userClass && $isAdmin == false) {
            return json_encode(array("code" => -3, "data" => ""));
        }
        $orgOwner = $this->orgOwner;
        $classIds = array("$classId");
        //这个班级有几个结束的plan
        $planGroupList = course_api::endgroupbyclassids($orgOwner, $classIds);
        $planIdArr = array();
        $planCount = 1;
        if (!empty($planGroupList->data)) {
            foreach ($planGroupList->data as $plan) {
                $planIdArr[] = $plan->pk_plan;
            }
            $planCount = count($planGroupList->data);
        }
        $planInfo = array();
        if (!empty($planIdArr)) {
            $query = [
                'status' => '1,2,3',
                'course_type' => '1,3',
                'plan_id' => implode(',', $planIdArr)
            ];

            $params = [
                'f' => [
                    'section_name',
                    'plan_id'
                ],
                'q' => $query,
                'ob' => ['start_time' => 'asc'],
            ];
            $planInfo = seek_api::seekPlan($params);
        }
        $planName = array();
        if (!empty($planInfo->data)) {
            foreach ($planInfo->data as $name) {
                $planName[$name->plan_id] = $name->section_name;
            }
        }
        $params = array(
            "classId" => $classId,
            "item" => array(
                "zan",
                "correct",
                "vt_live",
                "call",
                "handup",
                "discuss",
                "answer_rate",
                "vt_record",
                "vv_record",
                "fk_user",
                "last_updated",
                "plan_count",
            ),
            "orderby" => "zan DESC ,correct DESC,vt_live DESC,plan_count DESC",
            "page" => $page,
            "length" => $length
        );
        $userIdArr = array();
        $planStat = array();
        $userClassStat = stat_api::getClassUserStatByClassId($params);
        if (!empty($userClassStat) && !empty($userClassStat->items)) {
            foreach ($userClassStat->items as $key=>$stat) {
                $planStat[$stat->fk_user]["fk_user"] = $stat->fk_user;
                $planStat[$stat->fk_user]["last_updated"] = $stat->last_updated;
                $userIdArr[$stat->fk_user] = $stat->fk_user;
                $planStat[$stat->fk_user]["zan"]=$stat->zan;
                $planStat[$stat->fk_user]["correct"] = $stat->correct;
                $planStat[$stat->fk_user]["vt_live"] = $stat->vt_live;
                $planStat[$stat->fk_user]["call"] =$stat->call;
                $planStat[$stat->fk_user]["handup"] = $stat->handup;
                $planStat[$stat->fk_user]["discuss"] = $stat->discuss;
                $planStat[$stat->fk_user]["answer_rate"] = $stat->answer_rate;
                $planStat[$stat->fk_user]["vt_record"] = $stat->vt_record;
                $planStat[$stat->fk_user]["vv_record"] = $stat->vv_record;
                $planStat[$stat->fk_user]["plan_count"] = $stat->plan_count;
            }
        }
        $userNameArr = array();
        $params1 = array("uidArr" => $userIdArr);
        $userInfo = user_api::getUserInfoByUidArr($params1);
        if (!empty($userInfo) && !empty($userInfo->data)) {
            foreach ($userInfo->data as $user) {
                $userNameArr[$user->user_id] = !empty($user->real_name) ? $user->real_name : (!empty($user->name) ? $user->name : "未设置");
            }
        }
        $statUser = array();
        $i = ($page-1)*$length;
        $a = 0;
        $lastUpdated='';
        if (!empty($planStat)) {
            foreach ($planStat as &$stat) {
                $statUser[$a]["userId"] = $stat["fk_user"];
                $statUser[$a]["orderNum"] = $i + 1;
                if (!empty($userNameArr[$stat["fk_user"]])) {
                    $statUser[$a]["name"] = $userNameArr[$stat["fk_user"]];
                } else {
                    $statUser[$a]["name"] = "未设置";
                }
                $statUser[$a]["planCount"] = !empty($stat["plan_count"]) ? $stat["plan_count"] : 0;
                $statUser[$a]["zan"] = !empty($stat["zan"]) ? $stat["zan"] : 0;
                $statUser[$a]["correct"] = !empty($stat["correct"]) ? round($stat["correct"],2) : 0;
                $statUser[$a]["answerRate"] = !empty($stat["answer_rate"]) ? round($stat["answer_rate"],2) : 0;
                $statUser[$a]["call"] = !empty($stat["call"]) ? $stat["call"] : 0;
                $statUser[$a]["discuss"] = !empty($stat["discuss"]) ? $stat["discuss"] : 0;
                $statUser[$a]["handup"] = !empty($stat["handup"]) ? $stat["handup"] : 0;
                $statUser[$a]["vtLive"] = !empty($stat["vt_live"]) ? round(($stat["vt_live"] / 60)) : 0;
                $statUser[$a]["vtRecord"] = !empty($stat["vt_record"]) ? round(($stat["vt_record"] / 60)) : 0;
                $statUser[$a]["vvRecord"] = !empty($stat["vv_record"]) ? $stat["vv_record"] : 0;
                if(empty($lastUpdated)){
                    $lastUpdated = $stat["last_updated"];
                }else{
                    if(strtotime($lastUpdated)<strtotime($stat["last_updated"])){
                        $lastUpdated=$stat["last_updated"];
                    }
                }
                $a++;
                $i++;
            }
        }
        if ((!empty($userClassStat->totalPage))&&($page == $userClassStat->totalPage) && $userClassStat->totalPage <= 100) {
            $regUserIdArr = array();
            $regUser = course_class_api::getClassRegUser($classId);
            if (!empty($regUser)) {
                foreach ($regUser as $regUser1) {
                    $regUserIdArr[] = $regUser1["fk_user"];
                }
            }
            $params["page"] = 1;
            $params["length"] = -1;
            $userStat1 = stat_api::getClassUserStatByClassId($params);
            $userStatInfo = array();
            $correctCount = 0;
            $askCount = 0;
            if (!empty($userStat1) && !empty($userStat1->items)) {
                foreach ($userStat1->items as $userId) {
                    $userStatInfo[$userId->fk_user]["fk_user"] = $userId->fk_user;
                    $userStatInfo[$userId->fk_user]["last_updated"] = $userId->last_updated;
                    $userIdArr[$userId->fk_user] = $userId->fk_user;
                    $userStatInfo[$userId->fk_user]["zan"]=$userId->zan;
                    $userStatInfo[$userId->fk_user]["correct"] = $userId->correct;
                    $userStatInfo[$userId->fk_user]["vt_live"] = $userId->vt_live;
                    $userStatInfo[$userId->fk_user]["call"] =$userId->call;
                    $userStatInfo[$userId->fk_user]["handup"] = $userId->handup;
                    $userStatInfo[$userId->fk_user]["discuss"] = $userId->discuss;
                    $userStatInfo[$userId->fk_user]["answer_rate"] = $userId->answer_rate;
                    $userStatInfo[$userId->fk_user]["vt_record"] = $userId->vt_record;
                    $userStatInfo[$userId->fk_user]["vv_record"] = $userId->vv_record;
                }
            }
            $diffuserIdArr = array_diff($regUserIdArr, $userIdArr);
            if (!empty($diffuserIdArr)) {
                $userNameArr = array();
                $params1 = array("uidArr" => $diffuserIdArr);
                $userInfo = user_api::getUserInfoByUidArr($params1);
                if (!empty($userInfo) && !empty($userInfo->data)) {
                    foreach ($userInfo->data as $user) {
                        $userNameArr[$user->user_id] = !empty($user->real_name) ? $user->real_name : (!empty($user->name) ? $user->name : "未设置");
                    }
                }
                foreach ($diffuserIdArr as $v) {
                    $statUser[$a]["userId"] = $v;
                    $statUser[$a]["orderNum"] = $i + 1;
                    if (!empty($userNameArr[$v])) {
                        $statUser[$a]["name"] = $userNameArr[$v];
                    } else {
                        $statUser[$a]["name"] = "未设置";
                    }
                    $statUser[$a]["zan"] = 0;
                    if (!empty($userStatInfo[$v]["zan"])) {
                        $statUser[$a]["zan"] = $userStatInfo[$v]["zan"];
                    }
                    $statUser[$a]["correct"] = 0;
                    if (!empty($userStatInfo[$v]["correct"])) {
                        $statUser[$a]["correct"] = round($userStatInfo[$v]["correct"],2);
                    }
                    $statUser[$a]["answerRate"] = 0;
                    if (!empty($userStatInfo[$v]["answer_rate"])) {
                        $statUser[$a]["answerRate"] = round($userStatInfo[$v]["answer_rate"],2);
                    }
                    $statUser[$a]["call"] = 0;
                    if (!empty($userStatInfo[$v]["call"])) {
                        $statUser[$a]["call"] = $userStatInfo[$v]["call"];
                    }
                    $statUser[$a]["discuss"] = 0;
                    if (!empty($userStatInfo[$v]["discuss"])) {
                        $statUser[$a]["discuss"] = $userStatInfo[$v]["discuss"];
                    }
                    $statUser[$a]["handup"] = 0;
                    if (!empty($userStatInfo[$v]["handup"])) {
                        $statUser[$a]["handup"] = $userStatInfo[$v]["handup"];
                    }
                    $statUser[$a]["vtLive"] = 0;
                    if (!empty($userStatInfo[$v]["vt_live"])) {
                        $statUser[$a]["vtLive"] = $userStatInfo[$v]["vt_live"];
                    }
                    $statUser[$a]["vtRecord"] = 0;
                    if (!empty($userStatInfo[$v]["vt_record"])) {
                        $statUser[$a]["vtRecord"] = $userStatInfo[$v]["vt_record"];
                    }
                    $statUser[$a]["vVRecord"] = 0;
                    if (!empty($userStatInfo[$v]["vv_record"])) {
                        $statUser[$a]["vVRecord"] = $userStatInfo[$v]["vv_record"];
                    }
                    $a++;
                    $i++;
                }
            }
            if (empty($userClassStat->totalPage)) {
                $userClassStat->totalPage = 1;
            }
        }
        if (!empty($statUser)) {
            return json_encode(array("code" => 0, "result"=>array("data" => $statUser, "totalPage" => $userClassStat->totalPage,"courseName"=>$courseName,"className"=>$className,"lastUpdated"=>$lastUpdated)));
        }
        return json_encode(array("code" => -1, "data" => ""));
    }
}
