<?php

class org_stat extends STpl
{
    function __construct(){
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
    //用户统计开始
    //用户统计路由
    public function pageUserStatistical(){
        return $this->render('org/user.statistical.html');
    }
    //用户统计关键指标
    public function pageUserKeyIndicator()
    {
        $startDay = date("Y-m-d", time() - 86400);
        $medDay = date("Y-m-d", time() - 86400 * 2);
        $endDay = date("Y-m-d", time() - 86400 * 3);
        $item = array(
            "reg_user_count",
            "teacher_count",
            "enroll_count",
            "active_user_count",
            "import_user_count",
            "pk_day",
        );
        $condition = "fk_org=" . $this->orgId . " AND pk_day <= '" . $startDay . "' AND pk_day >= '" . $endDay . "'";
        $params = array(
            "item" => $item,
            "condition" => $condition,
        );
        $orgStat = org_stat_api::getOrgStat($params);
        $keyIndicator = array();
        $keyIndicatorTotal = array();
        if (!empty($orgStat->data)) {
            foreach ($orgStat->data as $stat) {
                $keyIndicator[$stat->pk_day] = $stat;
            }
        }
        if (!empty($keyIndicator[$startDay]) && !empty($keyIndicator[$endDay]) && !empty($keyIndicator[$medDay])) {
            $keyIndicatorTotal["regUserCount"] = number_format
            ($keyIndicator[$startDay]->reg_user_count);
            $keyIndicatorTotal["teacherCount"] = number_format($keyIndicator[$startDay]->teacher_count);
            $keyIndicatorTotal["enrollCount"] = number_format($keyIndicator[$startDay]->enroll_count);
            $keyIndicatorTotal["activeUserCount"] = number_format($keyIndicator[$startDay]->active_user_count);
            $keyIndicatorTotal["importUserCount"] = number_format($keyIndicator[$startDay]->import_user_count);
            //用户增量开始
            $a = $keyIndicator[$medDay]->reg_user_count - $keyIndicator[$endDay]->reg_user_count;//上一天增量
            $b = $keyIndicator[$startDay]->reg_user_count - $keyIndicator[$medDay]->reg_user_count;//当天增量
            if($a<0){
                $a=0;
            }
            if($b<0){
                $b=0;
            }
            if ($a > 0) {
                if($b>0) {
                    $keyIndicatorTotal["regUserPercentage"] = round(((abs($b - $a)) / $a * 100), 2);
                }else{
                    $keyIndicatorTotal["regUserPercentage"] = round(((abs($b - $a)) / 1 * 100), 2);
                }
            } else {
                if($b>0){
                    $keyIndicatorTotal["regUserPercentage"] = round(((abs($b - $a)) / 1*100), 2);
                }else {
                    $keyIndicatorTotal["regUserPercentage"] = 0;
                }
            }
            if ($b > $a) {
                $keyIndicatorTotal["regUserStatus"] = 1;//正增长
            } elseif ($b < $a) {
                $keyIndicatorTotal["regUserStatus"] = 2;//负增长
            } else {
                $keyIndicatorTotal["regUserStatus"] = 0;//无增长
            }
            //用户增量结束
            //老师增量开始
            $a = $keyIndicator[$medDay]->teacher_count - $keyIndicator[$endDay]->teacher_count;//上一天增量
            $b = $keyIndicator[$startDay]->teacher_count - $keyIndicator[$medDay]->teacher_count;//当天增量
            if($a<0){
                $a=0;
            }
            if($b<0){
                $b=0;
            }
            if ($a > 0) {
                if($b>0) {
                    $keyIndicatorTotal["teacherPercentage"] = round(((abs($b - $a)) / $a * 100), 2);
                }else{
                    $keyIndicatorTotal["teacherPercentage"] = round(((abs($b - $a)) / 1 * 100), 2);
                }
            } else {
                if($b>0){
                    $keyIndicatorTotal["teacherPercentage"] = round(((abs($b - $a)) / 1*100), 2);
                }else {
                    $keyIndicatorTotal["teacherPercentage"] = 0;
                }
            }
            if ($b > $a) {
                $keyIndicatorTotal["teacherStatus"] = 1;//正增长
            } elseif ($b < $a) {
                $keyIndicatorTotal["teacherStatus"] = 2;//负增长
            } else {
                $keyIndicatorTotal["teacherStatus"] = 0;//无增长
            }
            //老师增量结束
            //学生增量开始
            $a = $keyIndicator[$medDay]->enroll_count - $keyIndicator[$endDay]->enroll_count;//上一天增量
            $b = $keyIndicator[$startDay]->enroll_count - $keyIndicator[$medDay]->enroll_count;//当天增量
            if($a<0){
                $a=0;
            }
            if($b<0){
                $b=0;
            }
            if ($a > 0) {
                if($b>0) {
                    $keyIndicatorTotal["enrollPercentage"] = round(((abs($b - $a)) / $a * 100), 2);
                }else{
                    $keyIndicatorTotal["enrollPercentage"] = round(((abs($b - $a)) / 1 * 100), 2);
                }
            } else {
                if($b>0){
                    $keyIndicatorTotal["enrollPercentage"] = round(((abs($b - $a)) / 1*100), 2);
                }else {
                    $keyIndicatorTotal["enrollPercentage"] = 0;
                }
            }
            if ($b > $a) {
                $keyIndicatorTotal["enrollStatus"] = 1;//正增长
            } elseif ($b < $a) {
                $keyIndicatorTotal["enrollStatus"] = 2;//负增长
            } else {
                $keyIndicatorTotal["enrollStatus"] = 0;//无增长
            }
            //学生增量结束
            //激活增量开始
            $a = $keyIndicator[$medDay]->active_user_count - $keyIndicator[$endDay]->active_user_count;//上一天增量
            $b = $keyIndicator[$startDay]->active_user_count - $keyIndicator[$medDay]->active_user_count;//当天增量
            if($a<0){
                $a=0;
            }
            if($b<0){
                $b=0;
            }
            if ($a > 0) {
                if($b>0) {
                    $keyIndicatorTotal["activateStudentPercentage"] = round(((abs($b - $a)) / $a * 100), 2);
                }else{
                    $keyIndicatorTotal["activateStudentPercentage"] = round(((abs($b - $a)) / 1 * 100), 2);
                }
            } else {
                if($b>0){
                    $keyIndicatorTotal["activateStudentPercentage"] = round(((abs($b - $a)) / 1*100), 2);
                }else {
                    $keyIndicatorTotal["activateStudentPercentage"] = 0;
                }
            }
            if ($b > $a) {
                $keyIndicatorTotal["activateStudentStatus"] = 1;//正增长
            } elseif ($b < $a) {
                $keyIndicatorTotal["activateStudentStatus"] = 2;//负增长
            } else {
                $keyIndicatorTotal["activateStudentStatus"] = 0;//无增长
            }
            //激活增量结束
            //导入增量开始
            $a = $keyIndicator[$medDay]->import_user_count - $keyIndicator[$endDay]->import_user_count;//上一天增量
            $b = $keyIndicator[$startDay]->import_user_count - $keyIndicator[$medDay]->import_user_count;//当天增量
            if($a<0){
                $a=0;
            }
            if($b<0){
                $b=0;
            }
            if ($a > 0) {
                if($b>0) {
                    $keyIndicatorTotal["importUserPercentage"] = round(((abs($b - $a)) / $a * 100), 2);
                }else{
                    $keyIndicatorTotal["importUserPercentage"] = round(((abs($b - $a)) / 1 * 100), 2);
                }
            } else {
                if($b>0){
                    $keyIndicatorTotal["importUserPercentage"] = round(((abs($b - $a)) / 1*100), 2);
                }else {
                    $keyIndicatorTotal["importUserPercentage"] = 0;
                }
            }
            if ($b > $a) {
                $keyIndicatorTotal["importUserStatus"] = 1;//正增长
            } elseif ($b < $a) {
                $keyIndicatorTotal["importUserStatus"] = 2;//负增长
            } else {
                $keyIndicatorTotal["importUserStatus"] = 0;//无增长
            }
            //导入增量结束
        } elseif (!empty($keyIndicator[$startDay])) {
            $keyIndicatorTotal["regUserCount"] = $keyIndicator[$startDay]->reg_user_count;
            $keyIndicatorTotal["teacherCount"] = $keyIndicator[$startDay]->teacher_count;
            $keyIndicatorTotal["enrollCount"] = $keyIndicator[$startDay]->enroll_count;
            $keyIndicatorTotal["activeUserCount"] = $keyIndicator[$startDay]->active_user_count;
            $keyIndicatorTotal["importUserCount"] = $keyIndicator[$startDay]->import_user_count;
            //用户增量开始
            $keyIndicatorTotal["regUserPercentage"] = 0;
            $keyIndicatorTotal["regUserStatus"] = 0;//无增长
            //用户增量结束
            //老师增量开始
            $keyIndicatorTotal["teacherPercentage"] = 0;
            $keyIndicatorTotal["teacherStatus"] = 0;//无增长
            //老师增量结束
            //学生增量开始
            $keyIndicatorTotal["enrollPercentage"] = 0;
            $keyIndicatorTotal["enrollStatus"] = 0;//无增长
            //学生增量结束
            //激活增量开始
            $keyIndicatorTotal["activateStudentPercentage"] = 0;
            $keyIndicatorTotal["activateStudentStatus"] = 0;//无增长
            //激活增量结束
            //导入增量开始
            $keyIndicatorTotal["importUserPercentage"] = 0;
            $keyIndicatorTotal["importUserStatus"] = 0;//无增长
        }
        if (!empty($keyIndicatorTotal)) {
            return json_encode(array("code" => 0, "data" => $keyIndicatorTotal));
        }
        return json_encode(array("code"=>-1, "data"=>array()));
    }
    //用户统计趋势图
    public function pageUserTrend(){
        $startDay = !empty($_POST["startDay"])?$_POST["startDay"]:'';
        $endDay = !empty($_POST["endDay"])?$_POST["endDay"]:'';
        $act = !empty($_POST["act"])?$_POST["act"]:'regUser';
        if(empty($startDay)){
            $startDay = date("Y-m-d", time() - 86400*7);
        }
        if(empty($endDay)){
            $endDay = date("Y-m-d", time() - 86400);
        }
        $itemStr='';
        if(!empty($act)){
            switch ($act){
                case "regUser"://注册用户数
                    $itemStr = "reg_user_count";
                    break;
                case "activeUser"://激活用户数
                    $itemStr = "active_user_count";
                    break;
                case "importUser"://导入用户数
                    $itemStr = "import_user_count";
                    break;
                case "teacher"://教师数
                    $itemStr = "teacher_count";
                    break;
                case "activeTeacher"://激活教师数
                    $itemStr = "active_teacher_count";
                    break;
                case "videoTeacher"://有视频的教师数
                    $itemStr = "have_video_teacher_count";
                    break;
                case "enroll"://报名学生数
                    $itemStr = "enroll_count";
                    break;
                case "payEnroll"://收费报名学生数
                    $itemStr = "pay_enroll_count";
                    break;
                default://注册用户数
                    $itemStr = "reg_user_count";
                    break;
            }

        }
        $item = array(
            't_day_org_stat.'.$itemStr.' as count',
            'pk_day'
        );
        $condition = "fk_org=" . $this->orgId . " AND pk_day <= '" . $endDay . "' AND pk_day >= '" . date("Y-m-d", strtotime($startDay)-86400) . "'";
        $params = array(
            "item" => $item,
            "condition" => $condition,
        );
        $orgStat = org_stat_api::getOrgStat($params);
        $trend = array();
        $trendList = array();
        if (!empty($orgStat->data)) {
            foreach ($orgStat->data as $stat) {
                $trend[$stat->pk_day] = is_object($stat) ? get_object_vars($stat) : $stat;
            }
        }
        $day = (strtotime($endDay)-strtotime($startDay))/86400+1;
        if(!empty($trend)){
            for($i=0;$i<$day;$i++){
                if(!empty($trend[date('Y-m-d',strtotime($startDay)+86400*$i)])){
                    if(isset($trend[date('Y-m-d',strtotime($startDay)+86400*$i)]["count"])&&isset($trend[date('Y-m-d',strtotime($startDay)+86400*($i-1))]["count"])) {
                        $trendList[] = array(
                            "day" => $trend[date('Y-m-d', strtotime($startDay) + 86400 * $i)]["pk_day"],
                            "count" => (int)$trend[date('Y-m-d', strtotime($startDay) + 86400 * $i)]["count"],
                            "addCount" => (int)($trend[date('Y-m-d', strtotime($startDay) + 86400 * $i)]["count"] - $trend[date('Y-m-d', strtotime($startDay) + 86400 * ($i - 1))]["count"])

                        );
                    }else{
                        $trendList[] = array(
                            "day" => $trend[date('Y-m-d', strtotime($startDay) + 86400 * $i)]["pk_day"],
                            "count" => (int)$trend[date('Y-m-d', strtotime($startDay) + 86400 * $i)]["count"],
                            "addCount" => 0

                        );
                    }
                }else{
                    $trendList[]=array(
                        "day"=>date('Y-m-d',strtotime($startDay)+86400*$i),
                        "count"=>0,
                        "addCount"=>0
                    );
                }
            }
        }
        if (!empty($trendList)) {
            return json_encode(array("code" => 0, "data" => $trendList));
        }
        return json_encode(array("code"=>-1, "data"=>array()));
    }
    //用户统计表格
    public function pageUserTable(){
        $startDay = !empty($_POST["startDay"])?$_POST["startDay"]:'';
        $endDay = !empty($_POST["endDay"])?$_POST["endDay"]:'';
        $act = !empty($_POST["act"])?$_POST["act"]:'regUser';
        $page = isset($_POST["page"])?$_POST["page"]:1;
        $length = isset($_POST["length"])?$_POST["length"]:10;
        if(empty($startDay)){
            $startDay = date("Y-m-d", time() - 86400*7);
        }
        if(empty($endDay)){
            $endDay = date("Y-m-d", time() - 86400);
        }
        $itemStr='';
        if(!empty($act)){
            switch ($act){
                case "regUser"://注册用户数
                    $itemStr = "reg_user_count";
                    break;
                case "activeUser"://激活用户数
                    $itemStr = "active_user_count";
                    break;
                case "importUser"://导入用户数
                    $itemStr = "import_user_count";
                    break;
                case "teacher"://教师数
                    $itemStr = "teacher_count";
                    break;
                case "activeTeacher"://激活教师数
                    $itemStr = "active_teacher_count";
                    break;
                case "videoTeacher"://有视频的教师数
                    $itemStr = "have_video_teacher_count";
                    break;
                case "enroll"://报名学生数
                    $itemStr = "enroll_count";
                    break;
                case "payEnroll"://收费报名学生数
                    $itemStr = "pay_enroll_count";
                    break;
                default://注册用户数
                    $itemStr = "reg_user_count";
                    break;
            }

        }
        $item = array(
            't_day_org_stat.'.$itemStr.' as count',
            'pk_day'
        );
        $condition = "fk_org=" . $this->orgId . " AND pk_day <= '" . $endDay . "' AND pk_day >= '" . date("Y-m-d", strtotime($startDay)-86400*2) . "'";
        $params = array(
            "item" => $item,
            "condition" => $condition,
            "order"=>"pk_day DESC",
            "page"=>$page,
            "length"=>$length
        );
        $orgStat = org_stat_api::getOrgStat($params);
        $table = array();
        $tableList = array();
        $totalPage=0;
        if(isset($orgStat->data->totalPage)){
            $totalPage=$orgStat->data->totalPage;
        }
        if (!empty($orgStat->data->items)) {
            foreach ($orgStat->data->items as $stat) {
                $table[$stat->pk_day] = is_object($stat) ? get_object_vars($stat) : $stat;
            }
        }
        $day = (strtotime($endDay)-strtotime($startDay))/86400+1;
        if($day>$length){
            $day=$length;
        }
        if(!empty($table)){
            $p = ($page-1)*$length;
            for($i=0;$i<$day;$i++){
                if(!empty($table[date('Y-m-d',strtotime($endDay)-86400*($i+$p))])){
                    $a = 0;//上一天增量
                    $b = 0;//当日增量
                    if(isset($table[date('Y-m-d',strtotime($endDay)-86400*($i+$p))]["count"])&&isset($table[date('Y-m-d',strtotime($endDay)-86400*($i+1+$p))]["count"])){
                        $b = $table[date('Y-m-d',strtotime($endDay)-86400*($i+$p))]["count"]-$table[date('Y-m-d',strtotime($endDay)-86400*($i+1+$p))]["count"];
                    }
                    if(isset($table[date('Y-m-d',strtotime($endDay)-86400*($i+1+$p))]["count"])&&isset($table[date('Y-m-d',strtotime($endDay)-86400*($i+2+$p))]["count"])){
                        $a = $table[date('Y-m-d',strtotime($endDay)-86400*($i+1+$p))]["count"]-$table[date('Y-m-d',strtotime($endDay)-86400*($i+2+$p))]["count"];
                    }
                    if($a<0){
                        $a=0;
                    }
                    if($b<0){
                        $b=0;
                    }
                    $percentage=0;
                    if($a>0){
                        if($b>0) {
                            $percentage = round((abs($b - $a) / $a * 100), 2);
                        }else{
                            $percentage = round((abs($b - $a) / 1 * 100), 2);
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
                    $tableList[]=array(
                        "day"=>$table[date('Y-m-d',strtotime($endDay)-86400*($i+$p))]["pk_day"],
                        "count"=>number_format($table[date('Y-m-d',strtotime($endDay)-86400*($i+$p))]["count"]),
                        "addCount"=>number_format($b),
                        "percentage"=>$percentage,
                        "status"=>$status,
                    );

                }else{
                    $tableList[]=array(
                        "day"=>date('Y-m-d',strtotime($endDay)-86400*($i+$p)),
                        "count"=>0,
                        "addCount"=>0,
                        "percentage"=>0,
                        "status"=>0,
                    );
                }
            }
        }
        if (!empty($tableList)) {
            return json_encode(array("code" => 0, "data" =>array("list"=>$tableList,"totalPage"=>$totalPage)));
        }
        return json_encode(array("code"=>-1, "data"=>array()));
    }
    //用户统计结束
    //流量统计开始
    //流量统计路由
    public function pageTrafficStatistical(){
        return $this->render('org/traffic.statistical.html');
    }
    //流量统计关键指标
    public function pageTrafficKeyIndicator(){
        $startDay = date("Y-m-d", time() - 86400);
        $medDay = date("Y-m-d", time() - 86400 * 2);
        $endDay = date("Y-m-d", time() - 86400 * 3);
        $item = array(
            "vv_live",
            "vt_live",
            "vv_record",
            "vt_record",
            "pk_day",
        );
        $condition = "fk_org=" . $this->orgId . " AND pk_day <= '" . $startDay . "' AND pk_day >= '" . $endDay . "'";
        $params = array(
            "item" => $item,
            "condition" => $condition,
        );
        $orgStat = org_stat_api::getOrgStat($params);
        $keyIndicator = array();
        $keyIndicatorTotal = array();
        if (!empty($orgStat->data)) {
            foreach ($orgStat->data as $stat) {
                $keyIndicator[$stat->pk_day] = $stat;
            }
        }
        if (!empty($keyIndicator[$startDay]) && !empty($keyIndicator[$endDay]) && !empty($keyIndicator[$medDay])) {
            $keyIndicatorTotal["vv"] = number_format($keyIndicator[$startDay]->vv_live+$keyIndicator[$startDay]->vv_record);
            $keyIndicatorTotal["vt"] = number_format(round((($keyIndicator[$startDay]->vt_live+$keyIndicator[$startDay]->vt_record)
                /3600),2),2);
            //播放次数增量开始
            $a = ($keyIndicator[$medDay]->vv_live+ $keyIndicator[$medDay]->vv_record) - ($keyIndicator[$endDay]->vv_live+$keyIndicator[$endDay]->vv_record);//上一天增量
            $b = $keyIndicatorTotal["vv"] - ($keyIndicator[$medDay]->vv_live+$keyIndicator[$medDay]->vv_record);//当天增量
            if($a<0){
                $a=0;
            }
            if($b<0){
                $b=0;
            }
            if ($a > 0) {
                if($b>0) {
                    $keyIndicatorTotal["vvPercentage"] = round(((abs($b - $a)) / $a * 100), 2);
                }else{
                    $keyIndicatorTotal["vvPercentage"] = round(((abs($b - $a)) / 1*100), 2);
                }
            } else {
                if($b>0){
                    $keyIndicatorTotal["vvPercentage"] = round(((abs($b - $a)) / 1*100), 2);
                }else {
                    $keyIndicatorTotal["vvPercentage"] = 0;
                }
            }
            if ($b > $a) {
                $keyIndicatorTotal["vvStatus"] = 1;//正增长
            } elseif ($b < $a) {
                $keyIndicatorTotal["vvStatus"] = 2;//负增长
            } else {
                $keyIndicatorTotal["vvStatus"] = 0;//无增长
            }
            //播放次数增量结束
            //播放时长增量开始
            $a = ($keyIndicator[$medDay]->vt_live+$keyIndicator[$medDay]->vt_record) - ($keyIndicator[$endDay]->vt_live+$keyIndicator[$endDay]->vt_record);//上一天增量
            $b = ($keyIndicator[$startDay]->vt_live+$keyIndicator[$startDay]->vt_record) - ($keyIndicator[$medDay]->vt_live+$keyIndicator[$medDay]->vt_record);//当天增量
            if($a<0){
                $a=0;
            }
            if($b<0){
                $b=0;
            }
            if ($a > 0) {
                if($b>0) {
                    $keyIndicatorTotal["vtPercentage"] = round(((abs($b - $a)) / $a * 100), 2);
                }else{
                    $keyIndicatorTotal["vtPercentage"] = round(((abs($b - $a)) / 1 * 100 / 3600), 2);
                }
            } else {
                if($b>0){
                    $keyIndicatorTotal["vtPercentage"] = round(((abs($b - $a)) / 1*100/3600), 2);
                }else {
                    $keyIndicatorTotal["vtPercentage"] = 0;
                }
            }
            if ($b > $a) {
                $keyIndicatorTotal["vtStatus"] = 1;//正增长
            } elseif ($b < $a) {
                $keyIndicatorTotal["vtStatus"] = 2;//负增长
            } else {
                $keyIndicatorTotal["vtStatus"] = 0;//无增长
            }
            //播放时长增量结束
        } elseif (!empty($keyIndicator[$startDay])) {
            $keyIndicatorTotal["vv"] = $keyIndicator[$startDay]->vv_live+$keyIndicator[$startDay]->vv_record;
            $keyIndicatorTotal["vt"] = round((($keyIndicator[$startDay]->vt_live+$keyIndicator[$startDay]->vt_record)
                /3600),2);
            //播放次数增量开始
            $keyIndicatorTotal["vvPercentage"] = 0;
            $keyIndicatorTotal["vvStatus"] = 0;//无增长
            //播放次数增量结束
            //播放时长增量开始
            $keyIndicatorTotal["vtPercentage"] = 0;
            $keyIndicatorTotal["vtStatus"] = 0;//无增长
            //播放时长增量结束
        }
        if (!empty($keyIndicatorTotal)) {
            return json_encode(array("code" => 0, "data" => $keyIndicatorTotal));
        }
        return json_encode(array("code"=>-1, "data"=>array()));
    }
    //流量统计趋势图
    public function pageTrafficTrend(){
        $startDay = !empty($_POST["startDay"])?$_POST["startDay"]:'';
        $endDay = !empty($_POST["endDay"])?$_POST["endDay"]:'';
        $act = !empty($_POST["act"])?$_POST["act"]:'vv';
        if(empty($startDay)){
            $startDay = date("Y-m-d", time() - 86400*7);
        }
        if(empty($endDay)){
            $endDay = date("Y-m-d", time() - 86400);
        }
        $itemStr='';
        if(!empty($act)){
            switch ($act){
                case "vv"://视频播放次数
                    $itemStr = "t_day_org_stat.vv_live+t_day_org_stat.vv_record as count";
                    break;
                case "vt"://视频播放时长
                    $itemStr = "t_day_org_stat.vt_live+t_day_org_stat.vt_record as count";
                    break;
                case "vvLive"://直播次数
                    $itemStr = "t_day_org_stat.vv_live as count";
                    break;
                case "vvRecord"://录播次数
                    $itemStr = "t_day_org_stat.vv_record as count";
                    break;
                case "vtLive"://直播时长
                    $itemStr = "t_day_org_stat.vt_live as count";
                    break;
                case "vtRecord"://录播时长
                    $itemStr = "t_day_org_stat.vt_record as count";
                    break;
                default://视频播放次数
                    $itemStr = "t_day_org_stat.vv_live+t_day_org_stat.vv_record as count";
                    break;
            }

        }
        $item = array(
            $itemStr,
            'pk_day'
        );
        $condition = "fk_org=" . $this->orgId . " AND pk_day <= '" . $endDay . "' AND pk_day >= '" . date("Y-m-d", strtotime($startDay)-86400) . "'";
        $params = array(
            "item" => $item,
            "condition" => $condition,
        );
        $orgStat = org_stat_api::getOrgStat($params);
        $trend = array();
        $trendList = array();
        if (!empty($orgStat->data)) {
            foreach ($orgStat->data as $stat) {
                $trend[$stat->pk_day] = is_object($stat) ? get_object_vars($stat) : $stat;
            }
        }
        $day = (strtotime($endDay)-strtotime($startDay))/86400+1;
        if(!empty($trend)){
            for($i=0;$i<$day;$i++){
                if(!empty($trend[date('Y-m-d',strtotime($startDay)+86400*$i)])){
                    $addCount = 0;
                    if(isset($trend[date('Y-m-d',strtotime($startDay)+86400*$i)]["count"])&&isset($trend[date('Y-m-d',strtotime($startDay)+86400*($i-1))]["count"])) {
                        $addCount=($trend[date('Y-m-d', strtotime($startDay) + 86400 * $i)]["count"] - $trend[date('Y-m-d', strtotime($startDay) + 86400 * ($i - 1))]["count"]);
                    }
                    if($addCount<0){
                        $addCount=0;
                    }
                    $count = $trend[date('Y-m-d', strtotime($startDay) + 86400 * $i)]["count"];
                    if(!empty($act)&&($act=="vt"||$act=="vtLive"||$act=="vtRecord")){
                        $addCount = round(($addCount / 3600), 2);
                        $count = round(($count / 3600), 2);
                        $trendList[] = array(
                            "day" => $trend[date('Y-m-d', strtotime($startDay) + 86400 * $i)]["pk_day"],
                            "count" => round($count,2),
                            "addCount" => round($addCount,2)

                        );
                    }else {
                        $trendList[] = array(
                            "day" => $trend[date('Y-m-d', strtotime($startDay) + 86400 * $i)]["pk_day"],
                            "count" => (int)$count,
                            "addCount" => (int)$addCount

                        );
                    }
                }else{
                    $trendList[]=array(
                        "day"=>date('Y-m-d',strtotime($startDay)+86400*$i),
                        "count"=>0,
                        "addCount"=>0
                    );
                }
            }
        }
        if (!empty($trendList)) {
            return json_encode(array("code" => 0, "data" => $trendList));
        }
        return json_encode(array("code"=>-1, "data"=>array()));
    }
    //流量统计表格
    public function pageTrafficTable(){
        $startDay = !empty($_POST["startDay"])?$_POST["startDay"]:'';
        $endDay = !empty($_POST["endDay"])?$_POST["endDay"]:'';
        $act = !empty($_POST["act"])?$_POST["act"]:'vv';
        $page = isset($_POST["page"])?$_POST["page"]:1;
        $length = isset($_POST["length"])?$_POST["length"]:10;
        if(empty($startDay)){
            $startDay = date("Y-m-d", time() - 86400*7);
        }
        if(empty($endDay)){
            $endDay = date("Y-m-d", time() - 86400);
        }
        $itemStr='';
        if(!empty($act)){
            switch ($act){
                case "vv"://视频播放次数
                    $itemStr = "t_day_org_stat.vv_live+t_day_org_stat.vv_record as count";
                    break;
                case "vt"://视频播放时长
                    $itemStr = "t_day_org_stat.vt_live+t_day_org_stat.vt_record as count";
                    break;
                case "vvLive"://直播次数
                    $itemStr = "t_day_org_stat.vv_live as count";
                    break;
                case "vvRecord"://录播次数
                    $itemStr = "t_day_org_stat.vv_record as count";
                    break;
                case "vtLive"://直播时长
                    $itemStr = "t_day_org_stat.vt_live as count";
                    break;
                case "vtRecord"://录播时长
                    $itemStr = "t_day_org_stat.vt_record as count";
                    break;
                default://视频播放次数
                    $itemStr = "t_day_org_stat.vv_live+t_day_org_stat.vv_record as count";
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
            "page"=>$page,
            "length"=>$length
        );
        $orgStat = org_stat_api::getOrgStat($params);
        $table = array();
        $tableList = array();
        $totalPage = 0;
        if(isset($orgStat->data->totalPage)){
            $totalPage=$orgStat->data->totalPage;
        }
        if (!empty($orgStat->data->items)) {
            foreach ($orgStat->data->items as $stat) {
                $table[$stat->pk_day] = is_object($stat) ? get_object_vars($stat) : $stat;
            }
        }
        $day = (strtotime($endDay)-strtotime($startDay))/86400+1;
        if($day>$length){
            $day=$length;
        }
        if(!empty($table)){
            $p = ($page-1)*$length;
            for($i=0;$i<$day;$i++){
                if(!empty($table[date('Y-m-d',strtotime($endDay)-86400*($i+$p))])){
                    $a = 0;//上一天增量
                    $b = 0;//当日增量
                    if(isset($table[date('Y-m-d',strtotime($endDay)-86400*($i+$p))]["count"])&&isset($table[date('Y-m-d',strtotime($endDay)-86400*($i+1+$p))]["count"])){
                        $b = $table[date('Y-m-d',strtotime($endDay)-86400*($i+$p))]["count"]-$table[date('Y-m-d',strtotime($endDay)-86400*($i+1+$p))]["count"];
                    }
                    if(isset($table[date('Y-m-d',strtotime($endDay)-86400*($i+1+$p))]["count"])&&isset($table[date('Y-m-d',strtotime($endDay)-86400*($i+2+$p))]["count"])){
                        $a = $table[date('Y-m-d',strtotime($endDay)-86400*($i+1+$p))]["count"]-$table[date('Y-m-d',strtotime($endDay)-86400*($i+2+$p))]["count"];
                    }
                    if($a<0){
                        $a=0;
                    }
                    if($b<0){
                        $b=0;
                    }
                    $percentage=0;
                    if($a>0){
                        if($b>0) {
                            if(!empty($act)&&($act=="vt"||$act=="vtLive"||$act=="vtRecord")) {
                                $percentage=round((abs($b-$a)/$a/3600*100),2);
                            }else {
                                $percentage = round((abs($b - $a) / $a * 100), 2);
                            }
                        }else{
                            if(!empty($act)&&($act=="vt"||$act=="vtLive"||$act=="vtRecord")) {
                                $percentage=round((abs($b-$a)/3600*100),2);
                            }else{
                                $percentage=round((abs($b-$a)/1*100),2);
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
                    if(!empty($act)&&($act=="vt"||$act=="vtLive"||$act=="vtRecord")) {
                        $b = round(($b / 3600), 2);
                        $tableList[]=array(
                            "day"=>$table[date('Y-m-d',strtotime($endDay)-86400*($i+$p))]["pk_day"],
                            "count"=>number_format(round(($table[date('Y-m-d',strtotime($endDay)-86400*($i+$p))]["count"]/3600),2),2),
                            "addCount"=>number_format($b,2),
                            "percentage"=>$percentage,
                            "status"=>$status,
                        );
                    }else {
                        $tableList[] = array(
                            "day" => $table[date('Y-m-d', strtotime($endDay) - 86400 * ($i+$p))]["pk_day"],
                            "count" => number_format($table[date('Y-m-d', strtotime($endDay) - 86400 * ($i+$p))]["count"]),
                            "addCount" => number_format($b),
                            "percentage" => $percentage,
                            "status" => $status,
                        );
                    }
                }else{
                    $tableList[]=array(
                        "day"=>date('Y-m-d',strtotime($endDay)-86400*($i+$p)),
                        "count"=>0,
                        "addCount"=>0,
                        "percentage"=>0,
                        "status"=>0,
                    );
                }
            }
        }
        if (!empty($tableList)) {
            return json_encode(array("code" => 0, "data" =>array("list"=>$tableList,"totalPage"=>$totalPage)));
        }
        return json_encode(array("code"=>-1, "data"=>array()));
    }
    //流量统计结束
    //内容统计开始
    //内容统计路由
    public function pageContentStatistical(){
        return $this->render('org/content.statistical.html');
    }
    //内容统计关键指标
    public function pageContentKeyIndicator(){
        $startDay = date("Y-m-d", time() - 86400);
        $medDay = date("Y-m-d", time() - 86400 * 2);
        $endDay = date("Y-m-d", time() - 86400 * 3);
        $item = array(
            "course_count",
            "video_count",
            "video_length",
            "pk_day",
        );
        $condition = "fk_org=" . $this->orgId . " AND pk_day <= '" . $startDay . "' AND pk_day >= '" . $endDay . "'";
        $params = array(
            "item" => $item,
            "condition" => $condition,
        );
        $orgStat = org_stat_api::getOrgStat($params);
        $keyIndicator = array();
        $keyIndicatorTotal = array();
        if (!empty($orgStat->data)) {
            foreach ($orgStat->data as $stat) {
                $keyIndicator[$stat->pk_day] = $stat;
            }
        }
        if (!empty($keyIndicator[$startDay]) && !empty($keyIndicator[$endDay]) && !empty($keyIndicator[$medDay])) {
            $keyIndicatorTotal["courseCount"] = number_format($keyIndicator[$startDay]->course_count);
            $keyIndicatorTotal["videoCount"] = number_format($keyIndicator[$startDay]->video_count);
            $keyIndicatorTotal["videoLength"] = number_format(round(($keyIndicator[$startDay]->video_length/3600),2),2);
            //课程数量增量开始
            $a = $keyIndicator[$medDay]->course_count - $keyIndicator[$endDay]->course_count;//上一天增量
            $b = $keyIndicatorTotal["courseCount"] - $keyIndicator[$medDay]->course_count;//当天增量
            if($a<0){
                $a=0;
            }
            if($b<0){
                $b=0;
            }
            if ($a > 0) {
                if($b>0) {
                    $keyIndicatorTotal["coursePercentage"] = round(((abs($b - $a)) / $a * 100), 2);
                }else{
                    $keyIndicatorTotal["coursePercentage"] = round(((abs($b - $a)) / 1*100), 2);
                }
            } else {
                if($b>0){
                    $keyIndicatorTotal["coursePercentage"] = round(((abs($b - $a)) / 1*100), 2);
                }else {
                    $keyIndicatorTotal["coursePercentage"] = 0;
                }
            }
            if ($b > $a) {
                $keyIndicatorTotal["courseStatus"] = 1;//正增长
            } elseif ($b < $a) {
                $keyIndicatorTotal["courseStatus"] = 2;//负增长
            } else {
                $keyIndicatorTotal["courseStatus"] = 0;//无增长
            }
            //课程数量增量结束
            //视频数量增量开始
            $a = $keyIndicator[$medDay]->video_count - $keyIndicator[$endDay]->video_count;//上一天增量
            $b = $keyIndicatorTotal["videoCount"] - $keyIndicator[$medDay]->video_count;//当天增量
            if($a<0){
                $a=0;
            }
            if($b<0){
                $b=0;
            }
            if ($a > 0) {
                if($b>0) {
                    $keyIndicatorTotal["videoPercentage"] = round(((abs($b - $a)) / $a * 100), 2);
                }else{
                    $keyIndicatorTotal["videoPercentage"] = round(((abs($b - $a)) / 1 * 100), 2);
                }
            } else {
                if($b>0){
                    $keyIndicatorTotal["videoPercentage"] = round(((abs($b - $a)) / 1*100), 2);
                }else {
                    $keyIndicatorTotal["videoPercentage"] = 0;
                }
            }
            if ($b > $a) {
                $keyIndicatorTotal["videoStatus"] = 1;//正增长
            } elseif ($b < $a) {
                $keyIndicatorTotal["videoStatus"] = 2;//负增长
            } else {
                $keyIndicatorTotal["videoStatus"] = 0;//无增长
            }
            //视频数量增量结束
            //视频时长增量开始
            $a = $keyIndicator[$medDay]->video_length - $keyIndicator[$endDay]->video_length;//上一天增量
            $b = $keyIndicator[$startDay]->video_length - $keyIndicator[$medDay]->video_length;//当天增量
            if($a<0){
                $a=0;
            }
            if($b<0){
                $b=0;
            }
            if ($a > 0) {
                if($b>0) {
                    $keyIndicatorTotal["lengthPercentage"] = round(((abs($b - $a)) / $a * 100), 2);
                }else{
                    $keyIndicatorTotal["lengthPercentage"] = round(((abs($b - $a)) / 3600 * 100), 2);
                }
            } else {
                if($b>0){
                    $keyIndicatorTotal["lengthPercentage"] = round(((abs($b - $a)) / 1*100/3600), 2);
                }else {
                    $keyIndicatorTotal["lengthPercentage"] = 0;
                }
            }
            if ($b > $a) {
                $keyIndicatorTotal["lengthStatus"] = 1;//正增长
            } elseif ($b < $a) {
                $keyIndicatorTotal["lengthStatus"] = 2;//负增长
            } else {
                $keyIndicatorTotal["lengthStatus"] = 0;//无增长
            }
            //视频时长增量结束
        } elseif (!empty($keyIndicator[$startDay])) {
            $keyIndicatorTotal["courseCount"] = number_format($keyIndicator[$startDay]->course_count);
            $keyIndicatorTotal["videoCount"] = number_format($keyIndicator[$startDay]->video_count);
            $keyIndicatorTotal["videoLength"] = number_format(round(($keyIndicator[$startDay]->video_length/3600),2),2);
            //课程数量增量开始
            $keyIndicatorTotal["coursePercentage"] = 0;
            $keyIndicatorTotal["courseStatus"] = 0;//无增长
            //课程数量增量结束
            //视频数量增量开始
            $keyIndicatorTotal["videoPercentage"] = 0;
            $keyIndicatorTotal["videoStatus"] = 0;//无增长
            //视频数量增量结束
            //视频时长增量开始
            $keyIndicatorTotal["lengthPercentage"] = 0;
            $keyIndicatorTotal["lengthStatus"] = 0;//无增长
            //视频时长增量结束
        }
        if (!empty($keyIndicatorTotal)) {
            return json_encode(array("code" => 0, "data" => $keyIndicatorTotal));
        }
        return json_encode(array("code"=>-1, "data"=>array()));
    }
    //内容统计趋势图
    public function pageContentTrend(){
        $startDay = !empty($_POST["startDay"])?$_POST["startDay"]:'';
        $endDay = !empty($_POST["endDay"])?$_POST["endDay"]:'';
        $act = !empty($_POST["act"])?$_POST["act"]:'course';
        if(empty($startDay)){
            $startDay = date("Y-m-d", time() - 86400*7);
        }
        if(empty($endDay)){
            $endDay = date("Y-m-d", time() - 86400);
        }
        $itemStr='';
        if(!empty($act)){
            switch ($act){
                case "course"://课程数量
                    $itemStr = "t_day_org_stat.course_count as count";
                    break;
                case "video"://视频数量
                    $itemStr = "t_day_org_stat.video_count as count";
                    break;
                case "length"://视频时长
                    $itemStr = "t_day_org_stat.video_length as count";
                    break;
                default://课程数量
                    $itemStr = "t_day_org_stat.course_count as count";
                    break;
            }

        }
        $item = array(
            $itemStr,
            'pk_day'
        );
        $condition = "fk_org=" . $this->orgId . " AND pk_day <= '" . $endDay . "' AND pk_day >= '" . date("Y-m-d", strtotime($startDay)-86400) . "'";
        $params = array(
            "item" => $item,
            "condition" => $condition,
        );
        $orgStat = org_stat_api::getOrgStat($params);
        $trend = array();
        $trendList = array();
        if (!empty($orgStat->data)) {
            foreach ($orgStat->data as $stat) {
                $trend[$stat->pk_day] = is_object($stat) ? get_object_vars($stat) : $stat;
            }
        }
        $day = (strtotime($endDay)-strtotime($startDay))/86400+1;
        if(!empty($trend)){
            for($i=0;$i<$day;$i++){
                if(!empty($trend[date('Y-m-d',strtotime($startDay)+86400*$i)])){
                    $addCount = 0;
                    if(isset($trend[date('Y-m-d',strtotime($startDay)+86400*$i)]["count"])&&isset($trend[date('Y-m-d',strtotime($startDay)+86400*($i-1))]["count"])) {
                        $addCount=$trend[date('Y-m-d', strtotime($startDay) + 86400 * $i)]["count"] - $trend[date('Y-m-d', strtotime($startDay) + 86400 * ($i - 1))]["count"];
                    }
                    if($addCount<0){
                        $addCount=0;
                    }
                    $count = $trend[date('Y-m-d', strtotime($startDay) + 86400 * $i)]["count"];
                    if(!empty($act)&&$act=="length"){
                        $addCount = round(($addCount / 3600), 2);
                        $count = round(($count / 3600), 2);
                        $trendList[] = array(
                            "day" => $trend[date('Y-m-d', strtotime($startDay) + 86400 * $i)]["pk_day"],
                            "count" => round($count,2),
                            "addCount" => round($addCount,2)

                        );
                    }else {
                        $trendList[] = array(
                            "day" => $trend[date('Y-m-d', strtotime($startDay) + 86400 * $i)]["pk_day"],
                            "count" => (int)$count,
                            "addCount" => (int)$addCount

                        );
                    }
                }else{
                    $trendList[]=array(
                        "day"=>date('Y-m-d',strtotime($startDay)+86400*$i),
                        "count"=>0,
                        "addCount"=>0
                    );
                }
            }
        }
        if (!empty($trendList)) {
            return json_encode(array("code" => 0, "data" => $trendList));
        }
        return json_encode(array("code"=>-1, "data"=>array()));
    }
    //内容统计表格
    public function pageContentTable(){
        $startDay = !empty($_POST["startDay"])?$_POST["startDay"]:'';
        $endDay = !empty($_POST["endDay"])?$_POST["endDay"]:'';
        $act = !empty($_POST["act"])?$_POST["act"]:'course';
        $page = isset($_POST["page"])?$_POST["page"]:1;
        $length = isset($_POST["length"])?$_POST["length"]:10;
        if(empty($startDay)){
            $startDay = date("Y-m-d", time() - 86400*7);
        }
        if(empty($endDay)){
            $endDay = date("Y-m-d", time() - 86400);
        }
        $itemStr='';
        if(!empty($act)){
            switch ($act){
                case "course"://课程数量
                    $itemStr = "t_day_org_stat.course_count as count";
                    break;
                case "video"://视频数量
                    $itemStr = "t_day_org_stat.video_count as count";
                    break;
                case "length"://视频时长
                    $itemStr = "t_day_org_stat.video_length as count";
                    break;
                default://课程数量
                    $itemStr = "t_day_org_stat.course_count as count";
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
            "page"=>$page,
            "length"=>$length
        );
        $orgStat = org_stat_api::getOrgStat($params);
        $table = array();
        $tableList = array();
        $totalPage=0;
        if(isset($orgStat->data->totalPage)){
            $totalPage=$orgStat->data->totalPage;
        }
        if (!empty($orgStat->data->items)) {
            foreach ($orgStat->data->items as $stat) {
                $table[$stat->pk_day] = is_object($stat) ? get_object_vars($stat) : $stat;
            }
        }
        $day = (strtotime($endDay)-strtotime($startDay))/86400+1;
        if($day>$length){
            $day=$length;
        }
        if(!empty($table)){
            $p=($page-1)*$length;
            for($i=0;$i<$day;$i++){
                if(!empty($table[date('Y-m-d',strtotime($endDay)-86400*($i+$p))])){
                    $a = 0;//上一天增量
                    $b = 0;//当日增量
                    if(isset($table[date('Y-m-d',strtotime($endDay)-86400*($i+$p))]["count"])&&isset($table[date('Y-m-d',strtotime($endDay)-86400*($i+1+$p))]["count"])){
                        $b = $table[date('Y-m-d',strtotime($endDay)-86400*($i+$p))]["count"]-$table[date('Y-m-d',strtotime($endDay)-86400*($i+1+$p))]["count"];
                    }
                    if(isset($table[date('Y-m-d',strtotime($endDay)-86400*($i+1+$p))]["count"])&&isset($table[date('Y-m-d',strtotime($endDay)-86400*($i+2+$p))]["count"])){
                        $a = $table[date('Y-m-d',strtotime($endDay)-86400*($i+1+$p))]["count"]-$table[date('Y-m-d',strtotime($endDay)-86400*($i+2+$p))]["count"];
                    }
                    if($a<0){
                        $a=0;
                    }
                    if($b<0){
                        $b=0;
                    }
                    $percentage=0;
                    if($a>0){
                        if($b>0) {
                            $percentage=round((abs($b-$a)/$a*100),2);
                        }else{
                            if(!empty($act)&&$act=="length"){
                                $percentage=round((abs($b-$a)/3600*100),2);
                            }else{
                                $percentage=round((abs($b-$a)/1*100),2);
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
                    if(!empty($act)&&$act=="length") {
                        $b = round(($b / 3600), 2);
                        $tableList[]=array(
                            "day"=>$table[date('Y-m-d',strtotime($endDay)-86400*($i+$p))]["pk_day"],
                            "count"=>number_format(round(($table[date('Y-m-d',strtotime($endDay)-86400*($i+$p))]["count"]/3600),2),2),
                            "addCount"=>number_format($b,2),
                            "percentage"=>$percentage,
                            "status"=>$status,
                        );
                    }else {
                        $tableList[] = array(
                            "day" => $table[date('Y-m-d', strtotime($endDay) - 86400 * ($i+$p))]["pk_day"],
                            "count" => number_format($table[date('Y-m-d', strtotime($endDay) - 86400 * ($i+$p))]["count"]),
                            "addCount" => number_format($b),
                            "percentage" => $percentage,
                            "status" => $status,
                        );
                    }
                }else{
                    $tableList[]=array(
                        "day"=>date('Y-m-d',strtotime($endDay)-86400*($i+$p)),
                        "count"=>0,
                        "addCount"=>0,
                        "percentage"=>0,
                        "status"=>0,
                    );
                }
            }
        }
        if (!empty($tableList)) {
            return json_encode(array("code" => 0, "data" =>array("list"=>$tableList,"totalPage"=>$totalPage)));
        }
        return json_encode(array("code"=>-1, "data"=>array()));
    }
    //内容统计结束
    //首页关键指标开始
    public function pageKeyIndicator(){
        $startDay = date("Y-m-d", time() - 86400);
        $medDay = date("Y-m-d", time() - 86400 * 2);
        $endDay = date("Y-m-d", time() - 86400 * 3);
        $params = new stdClass();
        $params->min_date = $endDay;
        $params->max_date = $startDay;
        $params->fk_user = $this->orgOwner;
        $userOrgStat = org_stat_api::getDayOrgStatByOwnerid($params);
        $orgAccount = user_organization::getOrgAccountByOrgId($this->orgId);
        $keyIndicatorTotal = array();
        if(!empty($orgAccount->income_all)){
            $keyIndicatorTotal["incomeCount"] = number_format(round(($orgAccount->income_all/100),2),2);
        }else{
            $keyIndicatorTotal["incomeCount"] = 0;
        }
        if(!empty($orgAccount->order_count)){
            $keyIndicatorTotal["orderCount"] = number_format($orgAccount->order_count);
        }else{
            $keyIndicatorTotal["orderCount"]=0;
        }
        $keyIndicator1 = array();
        if (!empty($userOrgStat->result->data)) {
            foreach ($userOrgStat->result->data as $stat) {
                $keyIndicator1[$stat->pk_day] = $stat;
            }
        }
        if (!empty($keyIndicator1[$startDay]) && !empty($keyIndicator1[$endDay]) && !empty($keyIndicator1[$medDay])) {
            //收入增量开始
            $a = $keyIndicator1[$medDay]->income_new;//上一天增量
            $b = $keyIndicator1[$startDay]->income_new;//当天增量
            if($a<0){
                $a=0;
            }
            if($b<0){
                $b=0;
            }
            $keyIndicatorTotal["incomeAddCount"] = number_format($b/100);
            if ($a > 0) {
                if($b>0) {
                    $keyIndicatorTotal["incomePercentage"] = round(((abs($b/100 - $a/100)) / ($a/100)*100), 2);
                }else{
                    $keyIndicatorTotal["incomePercentage"] = round(((abs($b/100 - $a/100)) / 1*100), 2);
                }
            } else {
                $keyIndicatorTotal["incomePercentage"] = 0;
            }
            if ($b > $a) {
                $keyIndicatorTotal["incomeStatus"] = 1;//正增长
            } elseif ($b < $a) {
                $keyIndicatorTotal["incomeStatus"] = 2;//负增长
            } else {
                $keyIndicatorTotal["incomeStatus"] = 0;//无增长
            }
            //收入增量结束
            //订单增量开始
            $a = $keyIndicator1[$medDay]->order_new;//上一天增量
            $b = $keyIndicator1[$startDay]->order_new;//当天增量
            if($a<0){
                $a=0;
            }
            if($b<0){
                $b=0;
            }
            $keyIndicatorTotal["orderAddCount"] = number_format($b);
            if ($a > 0) {
                if($b>0) {
                    $keyIndicatorTotal["orderPercentage"] = round(((abs($b - $a)) / $a * 100), 2);
                }else{
                    $keyIndicatorTotal["orderPercentage"] = round(((abs($b - $a)) / 1 * 100), 2);
                }
            } else {
                $keyIndicatorTotal["orderPercentage"] = 0;
            }
            if ($b > $a) {
                $keyIndicatorTotal["orderStatus"] = 1;//正增长
            } elseif ($b < $a) {
                $keyIndicatorTotal["orderStatus"] = 2;//负增长
            } else {
                $keyIndicatorTotal["orderStatus"] = 0;//无增长
            }
            //订单增量结束
        } elseif (!empty($keyIndicator1[$startDay])) {
            if(!empty($keyIndicator1[$medDay])){
                $keyIndicatorTotal["incomeAddCount"] = number_format($keyIndicator1[$startDay]->income_new/100);
                $keyIndicatorTotal["orderAddCount"] = number_format($keyIndicator1[$startDay]->order_new);
            }else{
                $keyIndicatorTotal["incomeAddCount"] = 0;
                $keyIndicatorTotal["orderAddCount"] = 0;
            }
            //收入增量开始
            $keyIndicatorTotal["incomePercentage"] = 0;
            $keyIndicatorTotal["incomeStatus"] = 0;//无增长
            //收入增量结束
            //订单增量开始
            $keyIndicatorTotal["orderPercentage"] = 0;
            $keyIndicatorTotal["orderStatus"] = 0;//无增长
            //订单增量结束
        }else{
            $keyIndicatorTotal["incomeAddCount"] = 0;
            $keyIndicatorTotal["orderAddCount"] = 0;
            //收入增量开始
            $keyIndicatorTotal["incomePercentage"] = 0;
            $keyIndicatorTotal["incomeStatus"] = 0;//无增长
            //收入增量结束
            //订单增量开始
            $keyIndicatorTotal["orderPercentage"] = 0;
            $keyIndicatorTotal["orderStatus"] = 0;//无增长
            //订单增量结束
        }
        $item = array(
            "reg_user_count",
            "teacher_count",
            "enroll_count",
            "course_count",
            "vv_live",
            "vt_live",
            "vv_record",
            "vt_record",
            "pk_day",
            "video_length",
        );
        $condition = "fk_org=" . $this->orgId . " AND pk_day <= '" . $startDay . "' AND pk_day >= '" . $endDay . "'";
        $data = array(
            "item" => $item,
            "condition" => $condition,
        );
        $orgStat = org_stat_api::getOrgStat($data);
        $keyIndicator = array();
        if (!empty($orgStat->data)) {
            foreach ($orgStat->data as $stat) {
                $keyIndicator[$stat->pk_day] = $stat;
            }
        }
        if (!empty($keyIndicator[$startDay]) && !empty($keyIndicator[$endDay]) && !empty($keyIndicator[$medDay])) {
            $keyIndicatorTotal["vv"] = number_format($keyIndicator[$startDay]->vv_live+$keyIndicator[$startDay]->vv_record);
            $keyIndicatorTotal["vt"] = number_format(round((($keyIndicator[$startDay]->video_length)
                /3600),2),2);
            $keyIndicatorTotal["regUserCount"] = number_format($keyIndicator[$startDay]->reg_user_count);
            $keyIndicatorTotal["teacherCount"] = number_format($keyIndicator[$startDay]->teacher_count);
            $keyIndicatorTotal["enrollCount"] = number_format($keyIndicator[$startDay]->enroll_count);
            $keyIndicatorTotal["courseCount"] = number_format($keyIndicator[$startDay]->course_count);
            //用户增量开始
            $a = $keyIndicator[$medDay]->reg_user_count - $keyIndicator[$endDay]->reg_user_count;//上一天增量
            $b = $keyIndicator[$startDay]->reg_user_count - $keyIndicator[$medDay]->reg_user_count;//当天增量
            if($a<0){
                $a=0;
            }
            if($b<0){
                $b=0;
            }
            $keyIndicatorTotal["regUserAddCount"] = number_format($b);
            if ($a > 0) {
                if($b>0) {
                    $keyIndicatorTotal["regUserPercentage"] = round(((abs($b - $a)) / $a * 100), 2);
                }else{
                    $keyIndicatorTotal["regUserPercentage"] = round(((abs($b - $a)) / 1 * 100), 2);
                }
            } else {
                $keyIndicatorTotal["regUserPercentage"] = 0;
            }
            if ($b > $a) {
                $keyIndicatorTotal["regUserStatus"] = 1;//正增长
            } elseif ($b < $a) {
                $keyIndicatorTotal["regUserStatus"] = 2;//负增长
            } else {
                $keyIndicatorTotal["regUserStatus"] = 0;//无增长
            }
            //用户增量结束
            //老师增量开始
            $a = $keyIndicator[$medDay]->teacher_count - $keyIndicator[$endDay]->teacher_count;//上一天增量
            $b = $keyIndicator[$startDay]->teacher_count - $keyIndicator[$medDay]->teacher_count;//当天增量
            if($a<0){
                $a=0;
            }
            if($b<0){
                $b=0;
            }
            $keyIndicatorTotal["teacherAddCount"] = number_format($b);
            if ($a > 0) {
                if($b>0) {
                    $keyIndicatorTotal["teacherPercentage"] = round(((abs($b - $a)) / $a * 100), 2);
                }else{
                    $keyIndicatorTotal["teacherPercentage"] = round(((abs($b - $a)) / 1 * 100), 2);
                }
            } else {
                $keyIndicatorTotal["teacherPercentage"] = 0;
            }
            if ($b > $a) {
                $keyIndicatorTotal["teacherStatus"] = 1;//正增长
            } elseif ($b < $a) {
                $keyIndicatorTotal["teacherStatus"] = 2;//负增长
            } else {
                $keyIndicatorTotal["teacherStatus"] = 0;//无增长
            }
            //老师增量结束
            //学生增量开始
            $a = $keyIndicator[$medDay]->enroll_count - $keyIndicator[$endDay]->enroll_count;//上一天增量
            $b = $keyIndicator[$startDay]->enroll_count - $keyIndicator[$medDay]->enroll_count;//当天增量
            if($a<0){
                $a=0;
            }
            if($b<0){
                $b=0;
            }
            $keyIndicatorTotal["enrollAddCount"] = number_format($b);
            if ($a > 0) {
                if($b>0) {
                    $keyIndicatorTotal["enrollPercentage"] = round(((abs($b - $a)) / $a * 100), 2);
                }else{
                    $keyIndicatorTotal["enrollPercentage"] = round(((abs($b - $a)) / 1 * 100), 2);
                }
            } else {
                $keyIndicatorTotal["enrollPercentage"] = 0;
            }
            if ($b > $a) {
                $keyIndicatorTotal["enrollStatus"] = 1;//正增长
            } elseif ($b < $a) {
                $keyIndicatorTotal["enrollStatus"] = 2;//负增长
            } else {
                $keyIndicatorTotal["enrollStatus"] = 0;//无增长
            }
            //学生增量结束
            //课程数量增量开始
            $a = $keyIndicator[$medDay]->course_count - $keyIndicator[$endDay]->course_count;//上一天增量
            $b = $keyIndicatorTotal["courseCount"] - $keyIndicator[$medDay]->course_count;//当天增量
            if($a<0){
                $a=0;
            }
            if($b<0){
                $b=0;
            }
            $keyIndicatorTotal["courseAddCount"] = number_format($b);
            if ($a > 0) {
                if($b>0) {
                    $keyIndicatorTotal["coursePercentage"] = round(((abs($b - $a)) / $a * 100), 2);
                }else{
                    $keyIndicatorTotal["coursePercentage"] = round(((abs($b - $a)) / 1 * 100), 2);

                }
            } else {
                $keyIndicatorTotal["coursePercentage"] = 0;
            }
            if ($b > $a) {
                $keyIndicatorTotal["courseStatus"] = 1;//正增长
            } elseif ($b < $a) {
                $keyIndicatorTotal["courseStatus"] = 2;//负增长
            } else {
                $keyIndicatorTotal["courseStatus"] = 0;//无增长
            }
            //课程数量增量结束
            //播放次数增量开始
            $a = ($keyIndicator[$medDay]->vv_live+ $keyIndicator[$medDay]->vv_record) - ($keyIndicator[$endDay]->vv_live+$keyIndicator[$endDay]->vv_record);//上一天增量
            $b = ($keyIndicator[$startDay]->vv_live+$keyIndicator[$startDay]->vv_record) - ($keyIndicator[$medDay]->vv_live+$keyIndicator[$medDay]->vv_record);//当天增量
            if($a<0){
                $a=0;
            }
            if($b<0){
                $b=0;
            }
            $keyIndicatorTotal["vvAdd"] = number_format($b);
            if ($a > 0) {
                if($b>0) {
                    $keyIndicatorTotal["vvPercentage"] = round(((abs($b - $a)) / $a * 100), 2);
                }else{
                    $keyIndicatorTotal["vvPercentage"] = round(((abs($b - $a)) / 1 * 100), 2);
                }
            } else {
                $keyIndicatorTotal["vvPercentage"] = 0;
            }
            if ($b > $a) {
                $keyIndicatorTotal["vvStatus"] = 1;//正增长
            } elseif ($b < $a) {
                $keyIndicatorTotal["vvStatus"] = 2;//负增长
            } else {
                $keyIndicatorTotal["vvStatus"] = 0;//无增长
            }
            //播放次数增量结束
            //播放时长增量开始
            $a = ($keyIndicator[$medDay]->video_length+$keyIndicator[$medDay]->video_length) - ($keyIndicator[$endDay]->video_length+$keyIndicator[$endDay]->video_length);//上一天增量
            $b = ($keyIndicator[$startDay]->video_length+$keyIndicator[$startDay]->video_length) - ($keyIndicator[$medDay]->video_length+$keyIndicator[$medDay]->video_length);//当天增量
            if($a<0){
                $a=0;
            }
            if($b<0){
                $b=0;
            }
            $keyIndicatorTotal["vtAdd"] = number_format(round(($b/3600),2),2);
            if ($a > 0) {
                if($b>0) {
                    $keyIndicatorTotal["vtPercentage"] = round(((abs($b - $a)) / $a * 100), 2);
                }else{
                    $keyIndicatorTotal["vtPercentage"] = round(((abs($b - $a)) / 3600 * 100), 2);
                }
            } else {
                $keyIndicatorTotal["vtPercentage"] = 0;
            }
            if ($b > $a) {
                $keyIndicatorTotal["vtStatus"] = 1;//正增长
            } elseif ($b < $a) {
                $keyIndicatorTotal["vtStatus"] = 2;//负增长
            } else {
                $keyIndicatorTotal["vtStatus"] = 0;//无增长
            }
            //播放时长增量结束
        } elseif (!empty($keyIndicator[$startDay])) {
            $keyIndicatorTotal["vv"] = number_format($keyIndicator[$startDay]->vv_live+$keyIndicator[$startDay]->vv_record);
            $keyIndicatorTotal["vt"] = number_format(round((($keyIndicator[$startDay]->video_length)
                /3600),2),2);
            $keyIndicatorTotal["regUserCount"] = number_format($keyIndicator[$startDay]->reg_user_count);
            $keyIndicatorTotal["teacherCount"] = number_format($keyIndicator[$startDay]->teacher_count);
            $keyIndicatorTotal["enrollCount"] = number_format($keyIndicator[$startDay]->enroll_count);
            $keyIndicatorTotal["courseCount"] = number_format($keyIndicator[$startDay]->course_count);
            if(!empty($keyIndicator[$medDay])){
                $keyIndicatorTotal["vvAdd"] = number_format(($keyIndicator[$startDay]->vv_live+$keyIndicator[$startDay]->vv_record)-($keyIndicator[$medDay]->vv_live+$keyIndicator[$medDay]->vv_record));
                $keyIndicatorTotal["vtAdd"] = number_format(round(((($keyIndicator[$startDay]->video_length+$keyIndicator[$startDay]->video_length)-($keyIndicator[$medDay]->video_length+$keyIndicator[$medDay]->video_length))
                    /3600),2),2);
                $keyIndicatorTotal["regUserAddCount"] = number_format($keyIndicator[$startDay]->reg_user_count-$keyIndicator[$medDay]->reg_user_count);
                $keyIndicatorTotal["teacherAddCount"] = number_format($keyIndicator[$startDay]->teacher_count-$keyIndicator[$medDay]->teacher_count);
                $keyIndicatorTotal["enrollAddCount"] = number_format($keyIndicator[$startDay]->enroll_count-$keyIndicator[$medDay]->enroll_count);
                $keyIndicatorTotal["courseAddCount"] = number_format($keyIndicator[$startDay]->course_count-$keyIndicator[$medDay]->course_count);;
            }else{
                $keyIndicatorTotal["vvAdd"] = 0;
                $keyIndicatorTotal["vtAdd"] = 0;
                $keyIndicatorTotal["regUserAddCount"] = 0;
                $keyIndicatorTotal["teacherAddCount"] = 0;
                $keyIndicatorTotal["enrollAddCount"] = 0;
                $keyIndicatorTotal["courseAddCount"] = 0;
            }
            //用户增量开始
            $keyIndicatorTotal["regUserPercentage"] = 0;
            $keyIndicatorTotal["regUserStatus"] = 0;//无增长
            //用户增量结束
            //老师增量开始
            $keyIndicatorTotal["teacherPercentage"] = 0;
            $keyIndicatorTotal["teacherStatus"] = 0;//无增长
            //老师增量结束
            //学生增量开始
            $keyIndicatorTotal["enrollPercentage"] = 0;
            $keyIndicatorTotal["enrollStatus"] = 0;//无增长
            //学生增量结束
            //课程数量增量开始
            $keyIndicatorTotal["coursePercentage"] = 0;
            $keyIndicatorTotal["courseStatus"] = 0;//无增长
            //课程数量增量结束
            //播放次数增量开始
            $keyIndicatorTotal["vvPercentage"] = 0;
            $keyIndicatorTotal["vvStatus"] = 0;//无增长
            //播放次数增量结束
            //播放时长增量开始
            $keyIndicatorTotal["vtPercentage"] = 0;
            $keyIndicatorTotal["vtStatus"] = 0;//无增长
            //播放时长增量结束
        }else{
            $keyIndicatorTotal["vv"] = 0;
            $keyIndicatorTotal["vt"] = 0;
            $keyIndicatorTotal["regUserCount"] = 0;
            $keyIndicatorTotal["teacherCount"] = 0;
            $keyIndicatorTotal["enrollCount"] = 0;
            $keyIndicatorTotal["vvAdd"] = 0;
            $keyIndicatorTotal["vtAdd"] = 0;
            $keyIndicatorTotal["regUserAddCount"] = 0;
            $keyIndicatorTotal["teacherAddCount"] = 0;
            $keyIndicatorTotal["enrollAddCount"] = 0;
            $keyIndicatorTotal["courseAddCount"] = 0;
            //用户增量开始
            $keyIndicatorTotal["regUserPercentage"] = 0;
            $keyIndicatorTotal["regUserStatus"] = 0;//无增长
            //用户增量结束
            //老师增量开始
            $keyIndicatorTotal["teacherPercentage"] = 0;
            $keyIndicatorTotal["teacherStatus"] = 0;//无增长
            //老师增量结束
            //学生增量开始
            $keyIndicatorTotal["enrollPercentage"] = 0;
            $keyIndicatorTotal["enrollStatus"] = 0;//无增长
            //学生增量结束
            //课程数量增量开始
            $keyIndicatorTotal["coursePercentage"] = 0;
            $keyIndicatorTotal["courseStatus"] = 0;//无增长
            //课程数量增量结束
            //播放次数增量开始
            $keyIndicatorTotal["vvPercentage"] = 0;
            $keyIndicatorTotal["vvStatus"] = 0;//无增长
            //播放次数增量结束
            //播放时长增量开始
            $keyIndicatorTotal["vtPercentage"] = 0;
            $keyIndicatorTotal["vtStatus"] = 0;//无增长
            //播放时长增量结束
        }
        if (!empty($keyIndicatorTotal)) {
            return json_encode(array("code" => 0, "data" => $keyIndicatorTotal));
        }
        return json_encode(array("code"=>-1, "data"=>array()));
    }
    //首页关键指标结束
}