<?php
require_once(ROOT_LIBS."/phpexcel/PHPExcel.class.php");
require_once(ROOT_LIBS."/phpexcel/PHPExcel/Writer/Excel2007.php");
ini_set("max_execution_time", 2400); // s 40 分钟 
//修改此次的最大运行内存 
ini_set("memory_limit", 1048576000); // Byte 1000 兆，即 1G
class phpexcel_classstat extends STpl{
    public  function __construct(){
        $this->user = user_api::loginedUser();
        if (empty($this->user)) {
            $this->redirect("/site.main.login");
        }
        $domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
        $this->domain = $domain_conf->domain;
        $org=user_organization::subdomain();
        $this->orgOwner = !empty($org) ? $org->userId : 0;
        $this->subdomain = !empty($org) ? $org->subdomain : '';
        $this->orgInfo = user_organization::getOrgByOwner($this->orgOwner);
        if(!empty($this->orgInfo->oid)){
            $this->orgId = $this->orgInfo->oid;
        }
    }
    public function pageEntry($inPath){
        $classId = !empty($_REQUEST["classId"])?$_REQUEST["classId"]:0;
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
        }
        $special = user_api::getTeacherSpecial($this->orgInfo->oid,$uId);
        if((empty($special)||$special->role!=2||$special->status!=1) && $uId!=$user&&$uId!=$userClass){
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
                if($userTotalNum>0) {
                    $planStatList[$stat->fk_plan]["attendance"] = round((($stat->on_time + $stat->late) / $userTotalNum*100),2);
                    if($planStatList[$stat->fk_plan]["attendance"]>100){
                        $planStatList[$stat->fk_plan]["attendance"]=100;
                    }
                    $planStatList[$stat->fk_plan]["noTo"]=$userTotalNum-($stat->on_time + $stat->late);
                }else{
                    $planStatList[$stat->fk_plan]["attendance"]=0;
                    $planStatList[$stat->fk_plan]["noTo"] = 0;
                }
                $planStatList[$stat->fk_plan]["late"] = $stat->late;
                $planStatList[$stat->fk_plan]["zan"] = $stat->zan;
                $planStatList[$stat->fk_plan]["call"] = $stat->call;
                $planStatList[$stat->fk_plan]["handup"] = $stat->handup;
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
        if(empty($planStatList)){
            header("Content-type:text/html;charset=utf-8");
            echo '<script type="text/javascript">alert("没有学生");history.go(-1);"</script>';
            exit;
        }
        //实例化表格
        $objPHPExcel = new PHPExcel();
        //保存excel—2007格式
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        //基本设置
        $fileName="学习统计 - ".$courseName." ".$className;
        $objProps = $objPHPExcel->getProperties();
        $objProps->setCreator("www.yunke.com");
        $objProps->setLastModifiedBy("www.yunke.com");
        $objProps->setTitle("Office XLS Test Document");
        $objProps->setSubject("Office XLS Test Document, yunke");
        $objProps->setDescription("Test document, generated by PHPExcel.");
        $objProps->setKeywords("office excel PHPExcel");
        $objProps->setCategory("报表");
        $objPHPExcel->setActiveSheetIndex(0);
        // 行高
        for ($i = 1; $i <=11; $i++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(22);
        }
        $objActiveSheet = $objPHPExcel->getActiveSheet();
        $objActiveSheet->getColumnDimension('A')->setWidth(15);
        $objActiveSheet->getColumnDimension('B')->setWidth(15);
        $objActiveSheet->getColumnDimension('C')->setWidth(15);
        $objActiveSheet->getColumnDimension('F')->setWidth(15);
        $objActiveSheet->getColumnDimension('E')->setWidth(15);
        $objActiveSheet->getColumnDimension('F')->setWidth(15);
        $objActiveSheet->getColumnDimension('G')->setWidth(15);
        $objActiveSheet->getColumnDimension('H')->setWidth(15);
        $objActiveSheet->getColumnDimension('I')->setWidth(15);
        $objActiveSheet->getColumnDimension('J')->setWidth(20);
        $objActiveSheet->getColumnDimension('K')->setWidth(25);
        $objActiveSheet->setTitle(mb_substr($fileName,0,60));
        $objPHPExcel->getActiveSheet()->mergeCells('A1:K1');
        $objActiveSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->setCellValue('A1', $fileName);
        //设置表头
        $objActiveSheet->getStyle('A2')->getFont()->setBold(true);
        $objActiveSheet->getStyle('B2')->getFont()->setBold(true);
        $objActiveSheet->getStyle('C2')->getFont()->setBold(true);
        $objActiveSheet->getStyle('D2')->getFont()->setBold(true);
        $objActiveSheet->getStyle('E2')->getFont()->setBold(true);
        $objActiveSheet->getStyle('F2')->getFont()->setBold(true);
        $objActiveSheet->getStyle('G2')->getFont()->setBold(true);
        $objActiveSheet->getStyle('H2')->getFont()->setBold(true);
        $objActiveSheet->getStyle('I2')->getFont()->setBold(true);
        $objActiveSheet->getStyle('J2')->getFont()->setBold(true);
        $objActiveSheet->getStyle('K2')->getFont()->setBold(true);
        $objActiveSheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->setCellValue('A2', '课时');
        $objActiveSheet->setCellValue('B2', '到课率');
        $objActiveSheet->setCellValue('C2', '未到');
        $objActiveSheet->setCellValue('D2', '迟到');
        $objActiveSheet->setCellValue('E2', '点赞');
        $objActiveSheet->setCellValue('F2', '发言');
        $objActiveSheet->setCellValue('G2', '举手');
        $objActiveSheet->setCellValue('H2', '随堂测试');
        $objActiveSheet->setCellValue('I2', '询问');
        $objActiveSheet->setCellValue('J2', '时长');
        $objActiveSheet->setCellValue('K2', '老师是否迟到');
        //设置第二列为文本格式 防止科学计数
        $objActiveSheet->getStyle('C2')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        //填充表格内容
        $j=3;
        foreach($planStatList as $k=>$v){
            $objActiveSheet->setCellValue('A' . $j, $v['name']);
            $objActiveSheet->setCellValue('B' . $j, $v['attendance']);
            $objActiveSheet->setCellValue('C' . $j, $v['noTo']);
            $objActiveSheet->setCellValue('D' . $j, $v['late']);
            $objActiveSheet->setCellValue('E' . $j, $v['zan']);
            $objActiveSheet->setCellValue('F' . $j, $v['call']);
            $objActiveSheet->setCellValue('G' . $j, $v['handup']);
            $objActiveSheet->setCellValue('H' . $j, $v['correct']);
            $objActiveSheet->setCellValue('I' . $j, $v['answerRate']);
            if($v['vvRecord']!=="-"){
                $v['vvRecord'] = $v['vvRecord'].'分钟';
            }
            $objActiveSheet->setCellValue('J' . $j, $v['vvRecord']);
            if($v['status']==1){
                $v['status'] = '准时';
            }
            if($v['status']==2){
                $v['status'] = '迟到';
            }
            $objActiveSheet->setCellValue('K' . $j, $v['status']);
            $objActiveSheet->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('B' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('E' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('G' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('H' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('I' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('J' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('K' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $j++;
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        if(empty($fileName)){
            $ftime = time();
            $outputFileName = $ftime . ".xls";
        }else{
            $outputFileName = $fileName . ".xls";
        }
        //加此防乱码
        ob_clean();
        @header("Pragma: public");
        @header("Expires: 0");
        @header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
        @header("Content-Type:charset=UTF-8");
        @header("Content-Type:application/force-download");
        @header("Content-Type:application/vnd.ms-execl");
        @header("Content-Type:application/octet-stream");
        @header("Content-Type:application/download");
        @header('Content-Disposition:attachment;filename="' . $outputFileName . '"');
        @header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
    }

    public function pageNum($inPath)
    {
        $classId = !empty($_REQUEST["classId"]) ? $_REQUEST["classId"] : 0;
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
        $teacherName = "";
        $classInfo = course_class_api::getClassList($classId);
        if (!empty($classInfo) && !empty($classInfo["result"][0])) {
            $user = $classInfo["result"][0]["fk_user"];
            $userClass = $classInfo["result"][0]["fk_user_class"];
            $courseId = $classInfo["result"][0]["fk_course"];
            $className = $classInfo["result"][0]["name"];
            $teacherName = !empty($classInfo["result"][0]["teacher_real_name"])?$classInfo["result"][0]["teacher_real_name"]:(!empty($classInfo["result"][0]["teacher_name"])?$classInfo["result"][0]["teacher_name"]:'');
        }
        if (!empty($courseId)) {
            $courseInfo = course_api::getCourseOne($courseId);
            if (!empty($courseInfo) && !empty($courseInfo->title)) {
                $courseName = $courseInfo->title;
            }

        }
        $special = user_api::getTeacherSpecial($this->orgInfo->oid, $uId);
        $isAdmin = user_api::isAdmin($this->orgInfo->oid, $uId);
        if ((empty($special) || $special->role != 2 || $special->status != 1) && $uId != $user && $uId != $userClass && $isAdmin == false) {
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
            "pidArr" => $planIdArr,
            "item" => "SUM(`zan`) AS `zan`,SUM(`correct`) AS `correct`,COUNT(`fk_plan`) AS `plan_count`,SUM(`vt_live`) AS `vt_live`,SUM(`call`) AS `call`,SUM(`handup`) AS `handup`,SUM(`discuss`) AS `discuss`,SUM(`answer_rate`) AS `answer_rate`,SUM(`vt_record`) AS `vt_record`,SUM(`vv_record`) AS `vv_record`,`fk_user`,last_updated",
            "order" => "zan DESC ,correct DESC,plan_count DESC,vt_live DESC,status ASC",
            "group" => "fk_user",
            "page" => 1,
            "length" => -1
        );
        $userIdArr = array();
        $userPlanStat = stat_api::getClassPlanStatByPidArr($params);
        if (!empty($userPlanStat) && !empty($userPlanStat->items)) {
            foreach ($userPlanStat->items as $stat) {
                $userIdArr[] = $stat->fk_user;
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
        $i = 0;
        $a = 0;
        $lastUpdated = '';
        if (!empty($userPlanStat) && !empty($userPlanStat->items)) {
            foreach ($userPlanStat->items as &$stat) {
                $statUser[$a]["userId"] = $stat->fk_user;
                $statUser[$a]["orderNum"] = $i + 1;
                if (!empty($userNameArr[$stat->fk_user])) {
                    $statUser[$a]["name"] = $userNameArr[$stat->fk_user];
                } else {
                    $statUser[$a]["name"] = "未设置";
                }
                $statUser[$a]["planCount"] = !empty($stat->plan_count) ? $stat->plan_count : 0;
                $statUser[$a]["zan"] = !empty($stat->zan) ? $stat->zan : 0;
                $statUser[$a]["correct"] = !empty($stat->correct) ? round(($stat->correct/$stat->plan_count),2).'%' : '--';
                $statUser[$a]["answerRate"] = !empty($stat->answer_rate) ? round(($stat->answer_rate/$stat->plan_count),2).'%' : '--';
                $statUser[$a]["call"] = !empty($stat->call) ? $stat->call.'次' : '--';
                $statUser[$a]["discuss"] = !empty($stat->discuss) ? $stat->discuss.'条' : '--';
                $statUser[$a]["handup"] = !empty($stat->handup) ? $stat->handup.'次' : '--';
                $statUser[$a]["vtLive"] = !empty($stat->vt_live) ? round(($stat->vt_live / 60)).'分钟' : '--';
                $statUser[$a]["vtRecord"] = !empty($stat->vt_record) ? round(($stat->vt_record / 60)).'分钟' : '';
                $statUser[$a]["vvRecord"] = !empty($stat->vv_record) ? '('.$stat->vv_record.'次)' : '';
                if (empty($lastUpdated)) {
                    $lastUpdated = $stat->last_updated;
                } else {
                    if (strtotime($lastUpdated) < strtotime($stat->last_updated)) {
                        $lastUpdated = $stat->last_updated;
                    }
                }
                $a++;
                $i++;
            }
        }
        $regUserIdArr = array();
        $regUser = course_class_api::getClassRegUser($classId);
        if (!empty($regUser)) {
            foreach ($regUser as $regUser1) {
                $regUserIdArr[] = $regUser1["fk_user"];
            }
        }
        $params["page"] = 1;
        $params["length"] = -1;
        $userStat1 = stat_api::getClassPlanStatByPidArr($params);
        if (!empty($userStat1) && !empty($userStat1->items)) {
            foreach ($userStat1->items as $userId) {
                $userIdArr[] = $userId->fk_user;
                $userStatInfo[$userId->fk_user] = $userId;
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
                $statUser[$a]["planCount"] = 0;
                if (!empty($userStatInfo[$v]->plan_count)) {
                    $statUser[$a]["planCount"] = $userStatInfo[$v]->plan_count;
                }
                $statUser[$a]["zan"] = 0;
                if (!empty($userStatInfo[$v]->zan)) {
                    $statUser[$a]["zan"] = $userStatInfo[$v]->zan;
                }
                $statUser[$a]["correct"] = '--';
                if (!empty($userStatInfo[$v]->correct)) {
                    $statUser[$a]["correct"] = round(($userStatInfo[$v]->correct/$userStatInfo[$v]->plan_count),2).'%';
                }
                $statUser[$a]["answerRate"] = '--';
                if (!empty($userStatInfo[$v]->answer_rate)) {
                    $statUser[$a]["answerRate"] = round(($userStatInfo[$v]->answer_rate/$userStatInfo[$v]->plan_count),2).'%';
                }
                $statUser[$a]["call"] = '--';
                if (!empty($userStatInfo[$v]->call)) {
                    $statUser[$a]["call"] = $userStatInfo[$v]->call.'次';
                }
                $statUser[$a]["discuss"] = '--';
                if (!empty($userStatInfo[$v]->discuss)) {
                    $statUser[$a]["discuss"] = $userStatInfo[$v]->discuss.'条';
                }
                $statUser[$a]["handup"] = '--';
                if (!empty($userStatInfo[$v]->handup)) {
                    $statUser[$a]["handup"] = $userStatInfo[$v]->handup.'次';
                }
                $statUser[$a]["vtLive"] = '--';
                if (!empty($userStatInfo[$v]->vt_live)) {
                    $statUser[$a]["vtLive"] = round($userStatInfo[$v]->vt_live/60).'分钟';
                }
                $statUser[$a]["vtRecord"] = '';
                if (!empty($userStatInfo[$v]->vt_record)) {
                    $statUser[$a]["vtRecord"] = round($userStatInfo[$v]->vt_record/60).'分钟';
                }
                $statUser[$a]["vVRecord"] = '';
                if (!empty($userStatInfo[$v]->vv_record)) {
                    $statUser[$a]["vVRecord"] = '('.$userStatInfo[$v]->vv_record.'次)';
                }
                $a++;
                $i++;
            }
        }
        if (empty($statUser)) {
            header("Content-type:text/html;charset=utf-8");
            echo '<script type="text/javascript">alert("没有学生");history.go(-1);"</script>';
            exit;
        }
        //实例化表格
        $objPHPExcel = new PHPExcel();
        //保存excel—2007格式
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        //基本设置
        $fileName = "课程排名 - " . $courseName . " " . $className;
        $objProps = $objPHPExcel->getProperties();
        $objProps->setCreator("www.yunke.com");
        $objProps->setLastModifiedBy("www.yunke.com");
        $objProps->setTitle("Office XLS Test Document");
        $objProps->setSubject("Office XLS Test Document, yunke");
        $objProps->setDescription("Test document, generated by PHPExcel.");
        $objProps->setKeywords("office excel PHPExcel");
        $objProps->setCategory("报表");
        $objPHPExcel->setActiveSheetIndex(0);
        // 行高
        for ($i = 1; $i <= 11; $i++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(22);
        }
        $objActiveSheet = $objPHPExcel->getActiveSheet();
        $objActiveSheet->getColumnDimension('A')->setWidth(15);
        $objActiveSheet->getColumnDimension('B')->setWidth(15);
        $objActiveSheet->getColumnDimension('C')->setWidth(15);
        $objActiveSheet->getColumnDimension('F')->setWidth(15);
        $objActiveSheet->getColumnDimension('E')->setWidth(15);
        $objActiveSheet->getColumnDimension('F')->setWidth(15);
        $objActiveSheet->getColumnDimension('G')->setWidth(15);
        $objActiveSheet->getColumnDimension('H')->setWidth(15);
        $objActiveSheet->getColumnDimension('I')->setWidth(15);
        $objActiveSheet->getColumnDimension('J')->setWidth(20);
        $objActiveSheet->getColumnDimension('K')->setWidth(25);
        $objActiveSheet->setTitle($fileName);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:K1');
        $objActiveSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->setCellValue('A1', $fileName);
        $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
        $objActiveSheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objActiveSheet->setCellValue('A2', "班主任：".$teacherName);
        $objPHPExcel->getActiveSheet()->mergeCells('D2:K2');
        $objActiveSheet->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objActiveSheet->setCellValue('D2', "数据更新时间：".$lastUpdated);
        //设置表头
        $objActiveSheet->getStyle('A3')->getFont()->setBold(true);
        $objActiveSheet->getStyle('B3')->getFont()->setBold(true);
        $objActiveSheet->getStyle('C3')->getFont()->setBold(true);
        $objActiveSheet->getStyle('D3')->getFont()->setBold(true);
        $objActiveSheet->getStyle('E3')->getFont()->setBold(true);
        $objActiveSheet->getStyle('F3')->getFont()->setBold(true);
        $objActiveSheet->getStyle('G3')->getFont()->setBold(true);
        $objActiveSheet->getStyle('H3')->getFont()->setBold(true);
        $objActiveSheet->getStyle('I3')->getFont()->setBold(true);
        $objActiveSheet->getStyle('J3')->getFont()->setBold(true);
        $objActiveSheet->getStyle('K3')->getFont()->setBold(true);
        $objActiveSheet->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('K3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->setCellValue('A3', '排名');
        $objActiveSheet->setCellValue('B3', '姓名');
        $objActiveSheet->setCellValue('C3', '到课次数');
        $objActiveSheet->setCellValue('D3', '赞数');
        $objActiveSheet->setCellValue('E3', '答题正确率');
        $objActiveSheet->setCellValue('F3', '询问回答率');
        $objActiveSheet->setCellValue('G3', '发言');
        $objActiveSheet->setCellValue('H3', '讨论区');
        $objActiveSheet->setCellValue('I3', '举手');
        $objActiveSheet->setCellValue('J3', '观看直播');
        $objActiveSheet->setCellValue('K3', '观看录播');
        //设置第二列为文本格式 防止科学计数
        $objActiveSheet->getStyle('C2')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        //填充表格内容
        $j = 4;
        foreach ($statUser as $k => $v) {
            $objActiveSheet->setCellValue('A' . $j, $v['orderNum']);
            $objActiveSheet->setCellValue('B' . $j, $v['name']);
            $objActiveSheet->setCellValue('C' . $j, $v['planCount']);
            $objActiveSheet->setCellValue('D' . $j, $v['zan']);
            $objActiveSheet->setCellValue('E' . $j, $v['correct']);
            $objActiveSheet->setCellValue('F' . $j, $v['answerRate']);
            $objActiveSheet->setCellValue('G' . $j, $v['call']);
            $objActiveSheet->setCellValue('H' . $j, $v['discuss']);
            $objActiveSheet->setCellValue('I' . $j, $v['handup']);
            $objActiveSheet->setCellValue('J' . $j, $v['vtLive']);
            if(!empty($v['vtRecord'])&&!empty($v['vvRecord'])) {
                $objActiveSheet->setCellValue('K' . $j, $v['vtRecord'] . $v['vvRecord']);
            }else{
                $objActiveSheet->setCellValue('K' . $j, '--');
            }
            $objActiveSheet->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('B' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('E' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('G' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('H' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('I' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('J' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('K' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $j++;
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        if (empty($fileName)) {
            $ftime = time();
            $outputFileName = $ftime . ".xls";
        } else {
            $outputFileName = $fileName . ".xls";
        }
        //加此防乱码
        ob_clean();
        @header("Pragma: public");
        @header("Expires: 0");
        @header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
        @header("Content-Type:charset=UTF-8");
        @header("Content-Type:application/force-download");
        @header("Content-Type:application/vnd.ms-execl");
        @header("Content-Type:application/octet-stream");
        @header("Content-Type:application/download");
        @header('Content-Disposition:attachment;filename="' . $outputFileName . '"');
        @header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
    }
}