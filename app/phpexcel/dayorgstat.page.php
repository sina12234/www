<?php
require_once(ROOT_LIBS."/phpexcel/PHPExcel.class.php");
require_once(ROOT_LIBS."/phpexcel/PHPExcel/Writer/Excel2007.php");
ini_set("max_execution_time", 2400); // s 40 分钟 
//修改此次的最大运行内存 
ini_set("memory_limit", 1048576000); // Byte 1000 兆，即 1G
class phpexcel_dayorgstat extends STpl{
    public  function __construct(){
        //如果没有登陆到登陆界面
        $this->user = user_api::loginedUser();
        if(empty($this->user)){
            $this->redirect("/site.main.login");
        }
        $domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
        $this->domain = $domain_conf->domain;
        $org=user_organization::subdomain();
        if(!empty($org)){
            $this->orgOwner=$org->userId; //机构所有者id 以后会根据域名而列取
        }else{
            header('Location: https://www.'.$this->domain);
        }
        $this->orgInfo=user_organization::getOrgByOwner($this->orgOwner);
        $this->assign('is_pro',isset($this->orgInfo->is_pro) ? $this->orgInfo->is_pro : 0);
        $this->orgId = $this->orgInfo->oid;
        //判断管理员
        $isAdmin=user_api::isAdmin($this->orgOwner,$this->user['uid']);
        if($isAdmin===false){
            header('Location: //'.$org->subdomain.'.'.$this->domain);
        }
    }
    public function pageEntry($inPath){
        $startDay = !empty($_REQUEST["startDay"])?$_REQUEST["startDay"]:'';
        $endDay = !empty($_REQUEST["endDay"])?$_REQUEST["endDay"]:'';
        $act = !empty($_REQUEST["act"])?$_REQUEST["act"]:'regUser';
        if(empty($startDay)){
            $startDay = date("Y-m-d", time() - 86400*7);
        }
        if(empty($endDay)){
            $endDay = date("Y-m-d", time() - 86400);
        }
        $itemStr='';
        $tableList = array();
        if(!empty($act)){
            switch ($act){
                case "regUser"://注册用户数
                    $itemStr = "t_day_org_stat.reg_user_count as count";
                    $fileName="注册用户数统计列表";
                    $B1Text = "注册用户总数";
                    break;
                case "activeUser"://激活用户数
                    $itemStr = "t_day_org_stat.active_user_count as count";
                    $B1Text = "激活用户总数";
                    $fileName="激活用户数统计列表";
                    break;
                case "importUser"://导入用户数
                    $itemStr = "t_day_org_stat.import_user_count as count";
                    $B1Text = "导入用户总数";
                    $fileName="导入用户数统计列表";
                    break;
                case "teacher"://教师数
                    $itemStr = "t_day_org_stat.teacher_count as count";
                    $B1Text = "教师总数";
                    $fileName="教师数统计列表";
                    break;
                case "activeTeacher"://激活教师数
                    $itemStr = "t_day_org_stat.active_teacher_count as count";
                    $B1Text = "激活教师总数";
                    $fileName="激活教师数统计列表";
                    break;
                case "videoTeacher"://有视频的教师数
                    $itemStr = "t_day_org_stat.have_video_teacher_count as count";
                    $B1Text = "有视频的教师总数";
                    $fileName="有视频的教师数统计列表";
                    break;
                case "enroll"://报名学生数
                    $itemStr = "t_day_org_stat.enroll_count as count";
                    $fileName="报名学生数统计列表";
                    $B1Text = "报名学生总数";
                    break;
                case "payEnroll"://收费报名学生数
                    $itemStr = "t_day_org_stat.pay_enroll_count as count";
                    $fileName="收费课报名统计列表";
                    $B1Text = "收费课报名总数";
                    break;
                case "vv"://视频播放次数
                    $itemStr = "t_day_org_stat.vv_live+t_day_org_stat.vv_record as count";
                    $fileName="视频播放次数统计列表";
                    $B1Text = "视频播放次数";
                    break;
                case "vt"://视频播放时长
                    $itemStr = "t_day_org_stat.vt_live+t_day_org_stat.vt_record as count";
                    $fileName="视频播放时长统计列表";
                    $B1Text = "视频播放时长(h)";
                    break;
                case "vvLive"://直播次数
                    $itemStr = "t_day_org_stat.vv_live as count";
                    $fileName="直播次数统计列表";
                    $B1Text = "直播次数";
                    break;
                case "vvRecord"://录播次数
                    $itemStr = "t_day_org_stat.vv_record as count";
                    $fileName="录播次数统计列表";
                    $B1Text = "录播次数";
                    break;
                case "vtLive"://直播时长
                    $itemStr = "t_day_org_stat.vt_live as count";
                    $fileName="直播时长统计列表";
                    $B1Text = "直播时长(h)";
                    break;
                case "vtRecord"://录播时长
                    $itemStr = "t_day_org_stat.vt_record as count";
                    $fileName="录播时长统计列表";
                    $B1Text = "录播时长(h)";
                    break;
                case "course"://课程数量
                    $itemStr = "t_day_org_stat.course_count as count";
                    $fileName="课程数量统计列表";
                    $B1Text = "课程数量";
                    break;
                case "video"://视频数量
                    $itemStr = "t_day_org_stat.video_count as count";
                    $fileName="视频数量统计列表";
                    $B1Text = "视频数量";
                    break;
                case "length"://视频时长
                    $itemStr = "t_day_org_stat.video_length as count";
                    $fileName="视频时长统计列表";
                    $B1Text = "视频时长(h)";
                    break;
                default://注册用户数
                    $itemStr = "reg_user_count";
                    $fileName="注册用户数统计列表";
                    $B1Text = "注册用户总数";
                    break;
            }
        }
        $item = array(
            $itemStr,
            'pk_day'
        );
        $condition = "fk_org=" . $this->orgId . " AND pk_day <= '" . $endDay . "' AND pk_day >= '" . date("Y-m-d", strtotime($startDay)-86400*2) . "'";
        $params = array(
            "item" => $item,
            "condition" => $condition,
            "order"=>"pk_day DESC",
        );
        $orgStat = org_stat_api::getOrgStat($params);
        $table = array();
        if (!empty($orgStat->data)) {
            foreach ($orgStat->data as $stat) {
                $table[$stat->pk_day] = is_object($stat) ? get_object_vars($stat) : $stat;
            }
        }
        $day = (strtotime($endDay)-strtotime($startDay))/86400+1;
        if(!empty($table)){
            for($i=0;$i<$day;$i++){
                if(!empty($table[date('Y-m-d',strtotime($endDay)-86400*($i))])){
                    $a = 0;//上一天增量
                    $b = 0;//当日增量
                    if(isset($table[date('Y-m-d',strtotime($endDay)-86400*($i))]["count"])&&isset($table[date('Y-m-d',strtotime($endDay)-86400*($i+1))]["count"])){
                        $b = $table[date('Y-m-d',strtotime($endDay)-86400*($i))]["count"]-$table[date('Y-m-d',strtotime($endDay)-86400*($i+1))]["count"];
                    }
                    if(isset($table[date('Y-m-d',strtotime($endDay)-86400*($i+1))]["count"])&&isset($table[date('Y-m-d',strtotime($endDay)-86400*($i+2))]["count"])){
                        $a = $table[date('Y-m-d',strtotime($endDay)-86400*($i+1))]["count"]-$table[date('Y-m-d',strtotime($endDay)-86400*($i+2))]["count"];
                    }
                    if($a<0){
                        $a=0;
                    }
                    if($b<0){
                        $b=0;
                    }
                    $percentage=0;
                    if($a>0){
                        if($b>0){
                            if(!empty($act)&&($act=="length"||$act=='vt'||$act=='vtLive'||$act=='vtRecord')) {
                                $percentage = round((abs($b - $a) / 3600 * 100), 2);
                            }else {
                                $percentage = round((abs($b - $a) / $a * 100), 2);
                            }
                        }else {
                            if (!empty($act) && ($act == "length" || $act == 'vt' || $act == 'vtLive' || $act == 'vtRecord')) {
                                $percentage=round((abs($b-$a)/3600*100),2);
                            } else {
                                $percentage = round((abs($b - $a)/1* 100), 2);
                            }
                        }
                    }else{
                        $percentage=0;
                    }
                    $status=0;
                    if($b>$a){
                        $status=1;//正增长
                    }elseif($b<$a){
                        $status=2;//负增长
                    }else{
                        $status=0;//无增长
                    }
                    if(!empty($act)&&($act=="length"||$act=='vt'||$act=='vtLive'||$act=='vtRecord')) {
                        $b = round(($b / 3600), 2);
                        $tableList[]=array(
                            "day"=>$table[date('Y-m-d',strtotime($endDay)-86400*($i))]["pk_day"],
                            "count"=>number_format(round(($table[date('Y-m-d',strtotime($endDay)-86400*($i))]["count"]/3600),2),2),
                            "addCount"=>number_format($b,2),
                            "percentage"=>$percentage,
                            "status"=>$status,
                        );
                    }else{
                        $tableList[]=array(
                            "day" => $table[date('Y-m-d', strtotime($endDay) - 86400 * ($i))]["pk_day"],
                            "count" => number_format($table[date('Y-m-d', strtotime($endDay) - 86400 * ($i))]["count"]),
                            "addCount"=>number_format($b),
                            "percentage"=>$percentage,
                            "status"=>$status,
                        );
                    }
                }else{
                    $tableList[]=array(
                        "day"=>date('Y-m-d',strtotime($endDay)-86400*($i)),
                        "count"=>0,
                        "addCount"=>0,
                        "percentage"=>0,
                        "status"=>0,
                    );
                }
            }
        }
        if(empty($tableList)){
            header("Content-type:text/html;charset=utf-8");
            echo '<script type="text/javascript">alert("没有数据");history.go(-1);"</script>';
            exit;
        }
        //实例化表格
        $objPHPExcel = new PHPExcel();
        //保存excel—2007格式
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        //基本设置
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
        $objActiveSheet->getColumnDimension('D')->setWidth(15);
        $objActiveSheet->setTitle($fileName);
        //设置表头
        $objActiveSheet->getStyle('A1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('B1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('C1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('D1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->setCellValue('A1', '日期');
        $objActiveSheet->setCellValue('B1', $B1Text);
        $objActiveSheet->setCellValue('C1', '昨日');
        $objActiveSheet->setCellValue('D1', '变化');
        //设置第二列为文本格式 防止科学计数
        $objActiveSheet->getStyle('C1')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        //填充表格内容
        $j=2;
        foreach($tableList as $k=>$v){
            $objActiveSheet->setCellValue('A' . $j, $v['day']);
            if(empty($v['count'])){
                $v['count']='--';
            }
            if(empty($v['addCount'])){
                $v['addCount']='--';
            }
            $objActiveSheet->setCellValue('B' . $j, $v['count']);
            $objActiveSheet->setCellValue('C' . $j, $v['addCount']);
            if(!empty($v['percentage'])){
                if($v['status']==1){
                    $v['percentage'] = '+ '.$v['percentage'];
                }
                if($v['status']==2){
                    $v['percentage'] = '- '.$v['percentage'];
                }
                $v['percentage'] = $v['percentage'].'%';
            }else{
                $v['percentage'] = '--';
            }
            $objActiveSheet->setCellValue('D' . $j, $v['percentage']);
            $objActiveSheet->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('B' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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