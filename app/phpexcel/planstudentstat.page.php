<?php
require_once(ROOT_LIBS."/phpexcel/PHPExcel.class.php");
require_once(ROOT_LIBS."/phpexcel/PHPExcel/Writer/Excel2007.php");
ini_set("max_execution_time", 2400); // s 40 分钟 
//修改此次的最大运行内存 
ini_set("memory_limit", 1048576000); // Byte 1000 兆，即 1G
class phpexcel_planstudentstat extends STpl{
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
        $planId = isset($_GET["plan_id"]) ? $_GET["plan_id"]:0;
        $uid = $this->user['uid'];
        //判断权限
        $user=0;
        $userClass = 0;
        $courseId = 0;
        $classId = 0;
        $coursePlan = course_plan_api::getGetCoursePlanByPid($planId);
        $userPlan = 0;
        if(!empty($coursePlan)) {
            $coursePlan = $coursePlan->data;
            $courseName = $coursePlan->course_name;
            $className = $coursePlan->class_name;
            $userPlan = $coursePlan->fk_user_plan;
            $classId = $coursePlan->fk_class;
        }
        $classInfo = course_class_api::getClassList($classId);
        if(!empty($classInfo)&&!empty($classInfo["result"][0])){
            $user = $classInfo["result"][0]["fk_user"];
            $userClass = $classInfo["result"][0]["fk_user_class"];
        }
        $special = user_api::getTeacherSpecial($this->orgInfo->oid,$uid);
        $isAdmin=user_api::isAdmin($this->orgInfo->oid,$uid);
        if((empty($special)||$special->role!=2||$special->status!=1) && $uid!=$user&&$uid!=$userPlan&&$uid!=$userClass&&$isAdmin==false){
            header("Content-type:text/html;charset=utf-8");
            echo '<script type="text/javascript">alert("没有学生");location.href="/course.plan.getPlanStatByPid/'.$planId.'"</script>';
            //	header("Location: /index.teacher.student");
            exit;
            //header('Location: //'.$this->subdomain.'.'.$this->domain);
        }
        $userIdArr = array();
        $i=0;
        $userStat = stat_api::getUserPlanStatByPid($planId,1,-1);
        if(!empty($userStat)&&!empty($userStat->items)){
            foreach($userStat->items as $userId){
                $userIdArr[] = $userId->fk_user;
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
        if(!empty($userStat)&& !empty($userStat->items)){
            foreach($userStat->items as &$stat){
                if(!empty($userNameArr[$stat->fk_user])) {
                    $statUser[$i]["name"] = $userNameArr[$stat->fk_user];
                }else{
                    $statUser[$i]["name"]="未设置";
                }
                $statUser[$i]["orderNum"] = !empty($stat->order_num)?$stat->order_num:'-';
                if($stat->status==1){
                    $statUser[$i]["status"]="准时";
                }elseif($stat->status==2){
                    $statUser[$i]["status"]="迟到";
                }elseif($stat->status==3){
                    $statUser[$i]["status"]="未到";
                }else{
                    $statUser[$i]["status"]="-";
                }
                $statUser[$i]["zan"] = !empty($stat->zan)?$stat->zan:'-';
                $statUser[$i]["call"] = !empty($stat->call)?$stat->call:'-';
                $statUser[$i]["handup"] = !empty($stat->handup)?$stat->handup:'-';
                $statUser[$i]["discuss"] = !empty($stat->discuss)?$stat->discuss:'-';
                $statUser[$i]["inClassTest"] = !empty($stat->in_class_test)?$stat->in_class_test:'-';
                $statUser[$i]["ask"] = !empty($stat->ask)?$stat->ask:'-';
                $statUser[$i]["vvRecord"] = !empty($stat->vt_live)?round(($stat->vt_live/60)):'-';
                $statUser[$i]["vtRecord"] = !empty($stat->vt_record)?round(($stat->vt_record/60)):'-';
                $statUser[$i]["vtLive"] = !empty($stat->vv_record)?$stat->vv_record:'-';
                $i++;
            }
        }
        $regUserIdArr=array();
        $regUser = course_class_api::getClassRegUser($classId);
        if(!empty($regUser)){
            foreach($regUser as $regUser1){
                $regUserIdArr[] = $regUser1["fk_user"];
            }
        }
        $diffuserIdArr = array_diff($regUserIdArr, $userIdArr);
        if(!empty($diffuserIdArr)){
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
                $statUser[$i]["orderNum"] = '-';
                $statUser[$i]["status"] = "未到";
                $statUser[$i]["zan"] = '-';
                $statUser[$i]["call"] = '-';
                $statUser[$i]["handup"] = '-';
                $statUser[$i]["discuss"] = '-';
                $statUser[$i]["inClassTest"] = '-';
                $statUser[$i]["ask"] = '-';
                $statUser[$i]["vvRecord"] = '-';
                $statUser[$i]["vtRecord"] = '-';
                $statUser[$i]["vtLive"] = '-';
                $i++;
            }
        }
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
        $planStatList = stat_api::getPlanStatByPidStr($planId);
        if(!empty($planStatList->data[0])) {
            $planStatList = $planStatList->data[0];
            $lastUpdated = $planStatList->last_updated;
        }
        if(empty($statUser)){
            header("Content-type:text/html;charset=utf-8");
            echo '<script type="text/javascript">alert("没有学生");location.href="/course.plan.getPlanStatByPid/'.$planId.'"</script>';
            //	header("Location: /index.teacher.student");
            exit;
        }
        //实例化表格
        $objPHPExcel = new PHPExcel();
        //保存excel—2007格式
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        //基本设置
        $fileName="章节统计 - ".$courseName." ".$className." ".$planName;
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
        $objPHPExcel->getActiveSheet()->mergeCells('A2:K2');
        $objActiveSheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objActiveSheet->setCellValue('A2', "数据更新时间：".$lastUpdated);
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
        $objActiveSheet->setCellValue('A3', '学生姓名');
        $objActiveSheet->setCellValue('B3', '排名');
        $objActiveSheet->setCellValue('C3', '到课情况');
        $objActiveSheet->setCellValue('D3', '赞数');
        $objActiveSheet->setCellValue('E3', '发言');
        $objActiveSheet->setCellValue('F3', '举手');
        $objActiveSheet->setCellValue('G3', '讨论区');
        $objActiveSheet->setCellValue('H3', '随堂测试');
        $objActiveSheet->setCellValue('I3', '询问');
        $objActiveSheet->setCellValue('J3', '观看直播');
        $objActiveSheet->setCellValue('K3', '观看录播');

        //设置第二列为文本格式 防止科学计数
        $objActiveSheet->getStyle('C3')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        //填充表格内容
        $j=4;
        foreach($statUser as $k=>$v){
            $objActiveSheet->setCellValue('A' . $j, $v['name']);
            $objActiveSheet->setCellValue('B' . $j, $v['orderNum']);
            $objActiveSheet->setCellValue('C' . $j, $v['status']);
            $objActiveSheet->setCellValue('D' . $j, $v['zan']);
            $objActiveSheet->setCellValue('E' . $j, $v['call']);
            $objActiveSheet->setCellValue('F' . $j, $v['handup']);
            $objActiveSheet->setCellValue('G' . $j, $v['discuss']);
            $objActiveSheet->setCellValue('H' . $j, $v['inClassTest']);
            $objActiveSheet->setCellValue('I' . $j, $v['ask']);
            if($v['vvRecord']!=="-"){
                $v['vvRecord'] = $v['vvRecord'].'分钟';
            }
            $objActiveSheet->setCellValue('J' . $j, $v['vvRecord']);
            if($v['vtRecord']!=="-"){
                $v['vtRecord'] = $v['vtRecord'].'分钟';
            }
            if($v['vtLive']!=="-"){
                $v['vtLive'] = '（'.$v['vtLive'].'次）';
            }
            $objActiveSheet->setCellValue('K' . $j, $v['vtRecord']."".$v['vtLive']."");
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
}


