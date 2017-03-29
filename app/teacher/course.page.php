<?php
class teacher_course extends STpl{
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
        //判断是否机构下的老师
        $special = user_api::getTeacherSpecial($this->orgInfo->oid,$this->user['uid']);
        if(empty($special)){
            header('Location: //'.$org->subdomain.'.'.$this->domain);
        }
	}
	/*获取所选天的plan接口**/
	/*默认获取今天的课程接口*/
	public function pagePlan($inPath){
		$uid = $this->user["uid"];
		$this->assign("uid",$uid);	
		//{{{ 获取机构信息
		$ret_org_info = user_organization::getOrgByTeacher($uid);
		$this->assign("ret_org_info",$ret_org_info);
		//}}}
		if(empty($_REQUEST["todays"])){
			$strtimeToday = strtotime("today");
		}else{
			$strtimeToday = strtotime($_REQUEST["todays"]);
		}
		$this->assign("inputTime",$strtimeToday);	
		//{{{  //获取未来14天的时间
		$daySeconds = "86400";
		$timesAfterArr = array();
		$timesBeforeArr = array();
		$timeFormatAfter = array();
		$timeFormatBefore = array();
		$timesToday = array();
		for($i=1;$i<=14;$i++){	
			$timesAfterArr[$i] = $strtimeToday+$daySeconds*$i;
		}
		foreach($timesAfterArr as $k=>$v){
			$timeFormatAfter[$k]["week"] ="周".utility_time::cnweek($v);
			$timeFormatAfter[$k]["day"] =date("d",$v);
			$timeFormatAfter[$k]["timestamps"] =$v;
		}
		$this->assign("timeFormatAfter",$timeFormatAfter);	
		
		//}}}
		//{{{    今天
			$timesToday["week"] ="周".utility_time::cnweek($strtimeToday);
			$timesToday["day"] =date("d",$strtimeToday);
			$timesToday["month"] =date("m",$strtimeToday);
			$timesToday["timestamps"] =$strtimeToday;
			$this->assign("timesToday",$timesToday);	
		//}}}
		//{{{  //获取之前7天的时间
		for($i=1;$i<8;$i++){	
			$timesBeforeArr[$i] = $strtimeToday-$daySeconds*$i;
		}
		$reserveArr = array_reverse($timesBeforeArr);
		foreach($reserveArr as $k=>$v){
			$timeFormatBefore[$k]["week"] ="周".utility_time::cnweek($v);
			$timeFormatBefore[$k]["day"] =date("d",$v);
			$timeFormatBefore[$k]["timestamps"] =$v;
		}
		$this->assign("timeFormatBefore",$timeFormatBefore);	
		//}}}
		//{{{   筛选当天plan信息
	//	$starttime = "1429891200";
		$starttime = $strtimeToday;
		$ret_plan_info_ret = $this->listPlan($uid,$starttime);
		//}}}
	//	print_r($ret_plan_info_ret);
		//{{{  数据组装
		$showArr = array(
			"plan_id"=>"plan_id",	
			"course_id"=>"course_id",	
			"class_id"=>"class_id",	
			"section_id"=>"section_id",	
			"section_name"=>"section_name",	
			"class_name"=>"class_name",	
			"title"=>"course_name",	
			"start_time"=>"start_time",	
			"fee_type"=>"fee_type",	
			"price"=>"price",	
			"price_market"=>"price_market",	
			"status"=>"status",	
			"section_count"=>"section_count",	
			"user_total_class"=>"user_total_class",
			"max_user_class"=>"max_user_class",
		);
		$showData = array();
		$todaystr = $strtimeToday;
		$this->assign("todaystr",$todaystr);	
		$todaystr = time();
		if(!empty($ret_plan_info_ret->data)){
			$retPdata = $ret_plan_info_ret->data;
			foreach($retPdata as $k=>$v){
				foreach($showArr as $showk=>$showv){
					if(isset($retPdata[$k]->$showk)){
						$showData[$k][$showv] = $retPdata[$k]->$showk;
					}
					if($retPdata[$k]->fee_type==1){
						$showData[$k]["fee_info"] = $retPdata[$k]->price."元";
					}else{
						$showData[$k]["fee_info"] = "免费";
					}
					if($retPdata[$k]->status==3){
						$showData[$k]["plan_status"] = "已经结束";
						$showData[$k]["plan_link"] = "";
						$showData[$k]["lesson"] = "";
						$showData[$k]["lesson_link"] = "";
					}else{
						$showData[$k]["plan_status"] = "开始上课";
						$showData[$k]["plan_link"] = "./course.plan.start"."/".$retPdata[$k]->plan_id;
						$showData[$k]["lesson"] = "备课";
						$showData[$k]["lesson_link"] = "./teacher.manage.plan"."/".$retPdata[$k]->plan_id;
					}
					$showData[$k]["start_date"] = date("n月d日 H:i",strtotime($retPdata[$k]->start_time));
					$showData[$k]["thumb"] = utility_cdn::file($retPdata[$k]->thumb_small) ;
					//获取今天的课程开课时间
					$strtothisday = strtotime($retPdata[$k]->start_time);
					$seconds = $strtothisday-$todaystr;
					if(($seconds<86400) && ($seconds>0)){
						$hours = floor($seconds/3600);
						$yushu  = $seconds%3600;
						$minutes = floor($yushu/60); 
						if($hours>0){
							$showData[$k]["time_countdown"] = "距离开课时间:".$hours."小时".$minutes."分";
						}else{
							$showData[$k]["time_countdown"] = "距离开课时间:".$minutes."分";
						}
					}
				}
			}
		}
		$courseidsArr = array();
		foreach($showData as $courselistk=>$courselistv){
			$courseidsArr[] = $courselistv["course_id"];
		}
		$courseids = implode(",",$courseidsArr);
		///}}}

		if(!empty($showData)){
			$showData = utility_countbySphinx::countsections($showData,$this->orgOwner);
		}
		$this->assign("showData",$showData);	
		//}}}

		//	print_r($ret_plan_info_ret);
		$JsonshowData = SJson::encode($showData);
		//	print_r($ret_plan_info)
	//	$this->assign("course_id",$course_id);	
		$this->assign("JsonshowData",$JsonshowData);	
	//	$this->assign("class_id",$class_id);	
		return $this->render("teacher/teacher.plan.html");
	}
	public function pagePlanAjax($inPath){
		$starttime = $_REQUEST["start_time"];
		$attruid = $_REQUEST["uid"];
		$uid = $this->user["uid"];
		if(empty($attruid) || empty($starttime)){
			return $this->setAjaxResult("1000777","failed","not null");
		}
		if(!is_numeric($attruid) || !is_numeric($starttime)){
			return $this->setAjaxResult("1000777","failed"," data incorrect");
		}
		if(empty($_REQUEST["todays"]) || !is_numeric($_REQUEST["todays"])){
			$strtimeToday = strtotime("today");
		}else{
			$strtimeToday = $_REQUEST["todays"];
		}

		if($attruid != $uid){
			return $this->setAjaxResult("100133","failed","uid is incorrect");
		}
		//{{{ 获取机构信息
		$ret_org_info = user_organization::getOrgByTeacher($uid);
		//}}}
		//{{{   筛选当天plan信息
		$ret_plan_info_ret = $this->listPlan($uid,$starttime);	
		//}}}
//		print_r($ret_plan_info_ret);
		//{{{  数据组装
		$showArr = array(
			"plan_id"=>"plan_id",	
			"course_id"=>"course_id",	
			"class_id"=>"class_id",	
			"section_id"=>"section_id",	
			"section_name"=>"section_name",	
			"class_name"=>"class_name",	
			"title"=>"course_name",	
			"start_time"=>"start_time",	
			"fee_type"=>"fee_type",	
			"price"=>"price",	
			"price_market"=>"price_market",	
			"status"=>"status",	
			"section_count"=>"section_count",	
			"user_total_class"=>"user_total_class",
			"max_user_class"=>"max_user_class",
		);
		$showData = array();

		$todaystr = strtotime("today");
		$todaystr = time();
		if(!empty($ret_plan_info_ret->data)){
			$retPdata = $ret_plan_info_ret->data;
			foreach($retPdata as $k=>$v){
				foreach($showArr as $showk=>$showv){
					if(isset($retPdata[$k]->$showk)){
						$showData[$k][$showv] = $retPdata[$k]->$showk;
					}
					if($retPdata[$k]->fee_type==1){
						$showData[$k]["fee_info"] = $retPdata[$k]->price."元";
					}else{
						$showData[$k]["fee_info"] = "免费";
					}
					if($retPdata[$k]->status==3){
						$showData[$k]["plan_status"] = "已经结束";
						$showData[$k]["plan_link"] = "";
						$showData[$k]["lesson"] = "";
						$showData[$k]["lesson_link"] = "";
					}else{
						$showData[$k]["plan_status"] = "开始上课";
						$showData[$k]["plan_link"] = "./course.plan.start".".".$retPdata[$k]->plan_id;
						$showData[$k]["lesson"] = "备课";
						$showData[$k]["lesson_link"] = "./teacher.manage.plan".".".$retPdata[$k]->plan_id;
					}
					$showData[$k]["start_date"] = date("n月d H:i",strtotime($retPdata[$k]->start_time));
					$showData[$k]["thumb"] = utility_cdn::file($retPdata[$k]->thumb_small) ;
					//获取今天的课程开课时间
					$strtothisday = strtotime($retPdata[$k]->start_time);
					$seconds = $strtothisday-$todaystr;
					if(($seconds<86400) && ($seconds>0)){
						$hours = floor($seconds/3600);
						$yushu  = $seconds%3600;
						$minutes = floor($yushu/60); 
						if($hours>0){
							$showData[$k]["time_countdown"] = "距离开课时间:".$hours."小时".$minutes."分";
						}else{
							$showData[$k]["time_countdown"] = "距离开课时间:".$minutes."分";
						}
					}
				}
			}
		}
		if(!empty($showData)){
			$showData = utility_countbySphinx::countsections($showData,$this->orgOwner);
		}
	//	$showData = utility_countbySphinx::countsections($showData,$this->orgOwner);
		return $this->setAjaxResult("200","success",$showData);
		//}}}
	}
	public function pageTimeTableEndAjax($inPath){
		$starttime = $_REQUEST["start_time"];
		$endstarttime = $_REQUEST["endstart_time"];
		$attruid = $_REQUEST["uid"];
		$page = $_REQUEST["page"];
//		print_r($page);
	//	$page = 10;
		$status = 3;
		$uid = $this->user["uid"];
		if(empty($attruid) || empty($starttime)){
			return $this->setAjaxResult("1000777","failed","not null");
		}
		if(!is_numeric($attruid) || !is_numeric($starttime)){
			return $this->setAjaxResult("1000777","failed"," data incorrect");
		}
		if(empty($_REQUEST["start_time"]) || !is_numeric($_REQUEST["start_time"])){
			//$starttime = strtotime("today");
			$starttime = time();
		}else{
			$starttime = $_REQUEST["start_time"];
		}

		if($attruid != $uid){
			return $this->setAjaxResult("100133","failed","uid is incorrect");
		}
		//{{{ 获取机构信息
		$ret_org_info = user_organization::getOrgByTeacher($uid);
		//}}}
		//{{{   筛选当天plan信息
		$dataend = array();
	//	$dataend["endstart_time"]=$endstarttime;
		$dataend["endstart_time"]=$starttime+86400;
		$endstart = "628358400";
		$dataend["status"]="3";
		$ret_plan_info_ret = $this->listPlan($uid,$endstart,$dataend,$page,$length=20);
		//}}}
	//	print_r($ret_plan_info_ret);
		//{{{  数据组装
		$showData = array();
		$showDataend = array();
	//	$todaystr = $strtimeToday;
	//	$this->assign("todaystr",$todaystr);	
		$todaystr = time();
		if(!empty($ret_plan_info_ret->data)){
			$showData = $this->gdata($ret_plan_info_ret);
		}
		//}}}
//		print_r($ret_plan_info_ret);
		//{{{  数据组装
		if(!empty($showData)){
			$showData = utility_countbySphinx::countsections($showData,$this->orgOwner);
		}
//		print_r($showData);
	//	$showData = utility_countbySphinx::countsections($showData,$this->orgOwner);
		return $this->setAjaxResult("200","success",$showData);
		//}}}
	}
	public function pageTimeTableStartAjax($inPath){
		$starttime = $_REQUEST["start_time"];
		$endstarttime = $_REQUEST["endstart_time"];
		$attruid = $_REQUEST["uid"];
		$page = $_REQUEST["page"];
//		print_r($page);
	//	$page = 10;
		$status = 1;
		$uid = $this->user["uid"];
		if(empty($attruid) || empty($starttime)){
			return $this->setAjaxResult("1000777","failed","not null");
		}
		if(!is_numeric($attruid) || !is_numeric($starttime)){
			return $this->setAjaxResult("1000777","failed"," data incorrect");
		}
		if(empty($_REQUEST["start_time"]) || !is_numeric($_REQUEST["start_time"])){
			//$starttime = strtotime("today");
			$starttime = time();
		}else{
			$starttime = $_REQUEST["start_time"];
		}

		if($attruid != $uid){
			return $this->setAjaxResult("100133","failed","uid is incorrect");
		}
		//{{{ 获取机构信息
		$ret_org_info = user_organization::getOrgByTeacher($uid);
		//}}}
		//{{{   筛选当天plan信息
		$dataend = array();
		$dataend["endstart_time"]=$endstarttime;
		$dataend["status"]="1";
		$ret_plan_info_ret = $this->listPlan($uid,$starttime,$dataend,$page,$length=20);
		//}}}
	//	print_r($ret_plan_info_ret);
		//{{{  数据组装
		$showData = array();
		$showDataend = array();
	//	$todaystr = $strtimeToday;
	//	$this->assign("todaystr",$todaystr);	
		$todaystr = time();
		if(!empty($ret_plan_info_ret->data)){
			$showData = $this->gdata($ret_plan_info_ret);
		}
		//}}}
//		print_r($ret_plan_info_ret);
		//{{{  数据组装
		if(!empty($showData)){
			$showData = utility_countbySphinx::countsections($showData,$this->orgOwner);
		}
//		print_r($showData);
	//	$showData = utility_countbySphinx::countsections($showData,$this->orgOwner);
		return $this->setAjaxResult("200","success",$showData);
		//}}}
	}
	public function pageTimeTableStartAjaxnew($inPath){
		$starttime = $_REQUEST["start_time"];
		$endstarttime = $_REQUEST["endstart_time"];
		$attruid = $_REQUEST["uid"];
		$page = $_REQUEST["page"];
		$month=$_REQUEST["stmd"];
		$md=$_REQUEST["m_d"];

//		print_r($page);
	//	$page = 10;
		$uid = $this->user["uid"];
		if(empty($attruid) || empty($starttime)){
			return $this->setAjaxResult("1000777","failed","not null");
		}
		if(!is_numeric($attruid) || !is_numeric($starttime)){
			return $this->setAjaxResult("1000777","failed"," data incorrect");
		}
		if(empty($_REQUEST["start_time"]) || !is_numeric($_REQUEST["start_time"])){
			//$starttime = strtotime("today");
			$starttime = time();
		}else{
			$starttime = $_REQUEST["start_time"];
		}

		if($attruid != $uid){
			return $this->setAjaxResult("100133","failed","uid is incorrect");
		}
		$dataend = array();
		$dataend["endstart_time"]=$endstarttime;
		//$dataend["status"]="-3";
		$dataend["status"]="-3";
		$dataend["type"]="1";
		$ret_plan_info_ret = $this->listPlan($uid,$starttime,$dataend,$page,$length=20);
		//print_r($ret_plan_info_ret);
		$showData = $this->comData($ret_plan_info_ret,$month,$md);
		return $this->setAjaxResult("200","success",$showData);
	}
	public function gdata($ret_plan_info_ret){
		$showArr = array(
			"plan_id"=>"plan_id",	
			"user_course"=>"org_info",
			"course_id"=>"course_id",	
			"class_id"=>"class_id",	
			"section_id"=>"section_id",	
			"section_name"=>"section_name",	
			"class_name"=>"class_name",	
			"title"=>"course_name",	
			"start_time"=>"start_time",	
			"fee_type"=>"fee_type",	
			"price"=>"price",	
			"price_market"=>"price_market",	
			"status"=>"status",	
			"section_count"=>"section_count",	
			"user_total_class"=>"user_total_class",
			"max_user_class"=>"max_user_class",
		);
		$showData = array();
		$todaystr = strtotime("today");
		$todaystr = time();
		if(!empty($ret_plan_info_ret->data)){
			$retPdata = $ret_plan_info_ret->data;
			foreach($retPdata as $k=>$v){
				foreach($showArr as $showk=>$showv){
					if(isset($retPdata[$k]->$showk)){
						$showData[$k][$showv] = $retPdata[$k]->$showk;
					}
					if($retPdata[$k]->fee_type==1){
						$showData[$k]["fee_info"] = $retPdata[$k]->price."元";
						$showData[$k]["fee_info_color"] = "cRed";
					}else{
						$showData[$k]["fee_info"] = "免费";
						$showData[$k]["fee_info_color"] = "cGreen";
					}
					$todaystr1 = time();
					$todaystr = strtotime(date("Y-m-d"));
					$strtothisday = strtotime($retPdata[$k]->start_time);
					$seconds1 = $strtothisday-$todaystr;
					if($retPdata[$k]->status==3){
						//$showData[$k]["plan_status"] = '<a href="/video.point.videoPart/'.$retPdata[$k]->plan_id.'" target="_blank"><button>视频管理</button></a>';
						$showData[$k]["plan_status"] = '<a href="/video.point.videoPart/'.$retPdata[$k]->plan_id.'/1" target="_blank"><button>视频管理</button></a>';
                		$showData[$k]["lesson"] = "";
						$showData[$k]["lesson_link"] = "";
						$showData[$k]["plan_play"] = "继续上课";
						$showData[$k]["plan_play_link"] = "./course.plan.start"."/".$retPdata[$k]->plan_id;
					}else{
						$showData[$k]["plan_status"] = '<a href="/course.plan.start/'.$retPdata[$k]->plan_id.'" target="_blank"><button>开始上课</button></a>';
						$showData[$k]["lesson"] = "备课";
						$showData[$k]["lesson_link"] = "./teacher.manage.plan"."/".$retPdata[$k]->plan_id;
						$showData[$k]["plan_manage"] = "上传视频";
						//$showData[$k]["plan_manage_link"] = "./video.point.videoUpload"."/".$retPdata[$k]->plan_id;
						$showData[$k]["plan_manage_link"] = "./video.point.videoUpload"."/".$retPdata[$k]->plan_id."/1";
						//	$showData[$k]["lesson"] = "";
						//	$showData[$k]["lesson_link"] = "";
						if(($seconds1<86400) && ($seconds1>0)){
						    $showData[$k]["plan_status"] = '<a href="/course.plan.start/'.$retPdata[$k]->plan_id.'" target="_blank"><button>开始上课</button></a>';
						}else{
						    $showData[$k]["plan_status"] = '未开课';
						}
						if(strtotime($retPdata[$k]->start_time)<=time()){
						    $showData[$k]["plan_status"] = '<a href="/course.plan.start/'.$retPdata[$k]->plan_id.'" target="_blank"><button>开始上课</button></a>';
						//	$showData[$k]["plan_status"] = "未开课";
						//	$showData[$k]["plan_link"] = "";
							$showData[$k]["lesson"] = "";
							$showData[$k]["lesson_link"] = "";
							$showData[$k]["plan_play"] = "";
							$showData[$k]["plan_play_link"] = "";
						}
					}
					$showData[$k]["start_date"] = date("n月d H:i",strtotime($retPdata[$k]->start_time));
					if(($seconds1<86400) && ($seconds1>0)){
						$showData[$k]["start_date"] = date("今日 H:i",strtotime($retPdata[$k]->start_time));
					}else{
						$showData[$k]["start_date"] = date("n月d H:i",strtotime($retPdata[$k]->start_time));
					}
					$showData[$k]["thumb"] = utility_cdn::file($retPdata[$k]->thumb_small) ;
					//获取今天的课程开课时间
					$strtothisday = strtotime($retPdata[$k]->start_time);
					$seconds = $strtothisday-$todaystr1;
					if(($seconds<86400) && ($seconds>0)){
						$hours = floor($seconds/3600);
						$yushu  = $seconds%3600;
						$minutes = floor($yushu/60); 
						if($hours>0){
							$showData[$k]["time_attr"] =$hours.":".$minutes;
							$showData[$k]["time_countdown"] = "距离开课时间:".$hours."小时".$minutes."分";
						}else{
							$showData[$k]["time_attr"] =$minutes;
							$showData[$k]["time_countdown"] = "距离开课时间:".$minutes."分";
						}
					}
				}
			}
		}
		return $showData;
	}

	public function listPlan($uid,$time,$data=array(),$page=0,$length=0){
		$plist = array(
			"allcourse"=>true, //筛选所有状态!= -1的课程
			"user_plan_id"=>$uid,
			"orgUserId"=>$this->orgOwner,
			"order_by"=>"desc",	
			"start_time"=>$time,
		);
		if(!empty($page)){
			$plist["page"] = $page;
		}
		if(!empty($length)){
			$plist["length"] = $length;
		}
		if(!empty($data["endstart_time"])){
			$plist["endstart_time"] = $data["endstart_time"];
		}
		if(isset($data["status"])){
			$plist["status"] = $data["status"];
		}
		$plist["type"] = 1;
		$ret_plan_info_ret = course_api::listPlan($plist);	
		return $ret_plan_info_ret;
	}
	public function pageaddDaysLaterAjax($inPath){
		//{{{  //获取未来7天的时间
		$counts = empty($_REQUEST["counts"])?"0":$_REQUEST["counts"];
		$strtimeToday = $_REQUEST["thisDay"];
		if(empty($counts) || empty($strtimeToday)){
			return $this->setAjaxResult("1000777","failed","not null");
		}
		if(!is_numeric($counts) || !is_numeric($strtimeToday)){
			return $this->setAjaxResult("1000777","failed"," data incorrect");
		}
		$daySeconds = "86400";
		$timesAfterArr = array();
		$timeFormatAfter = array();
		$timesToday = array();
		$start = 8+$counts*7;
		$limits = $start+6;
		for($i=$start;$i<=$limits;$i++){	
			$timesAfterArr[$i] = $strtimeToday+$daySeconds*$i;
		}
		foreach($timesAfterArr as $k=>$v){
			$timeFormatAfter[$k]["week"] ="周".utility_time::cnweek($v);
			$timeFormatAfter[$k]["day"] =date("d",$v);
			$timeFormatAfter[$k]["timestamps"] =$v;
		}
		return $this->setAjaxResult("200","success",$timeFormatAfter);
		
		//}}}
	}
	public function pageaddDaysBeforeAjax($inPath){
		$counts = $_REQUEST["counts"];
		$strtimeToday = $_REQUEST["thisDay"];
		if(empty($counts) || empty($strtimeToday)){
			return $this->setAjaxResult("1000777","failed","not null");
		}
		if(!is_numeric($counts) || !is_numeric($strtimeToday)){
			return $this->setAjaxResult("1000777","failed"," data incorrect");
		}
		$daySeconds = "86400";
		$timesBeforeArr = array();
		$timeFormatBefore = array();
		$timesToday = array();
		
		//{{{  //获取之前7天的时间
		$start = 1+$counts*7;
		$limits = $start+6;
		for($i=$start;$i<=$limits;$i++){	
			$timesBeforeArr[$i] = $strtimeToday-$daySeconds*$i;
		}
/*
		for($i=1;$i<7;$i++){	
			$timesBeforeArr[$i] = $strtimeToday-$daySeconds*$i;
		}
*/
		$reserveArr = $timesBeforeArr;
		foreach($reserveArr as $k=>$v){
			$timeFormatBefore[$k]["week"] ="周".utility_time::cnweek($v);
			$timeFormatBefore[$k]["day"] =date("d",$v);
			$timeFormatBefore[$k]["timestamps"] =$v;
		}
		return $this->setAjaxResult("200","success",$timeFormatBefore);
		//}}}
	}
    public function setAjaxResult($code, $msg, $data=array()){
		return json_encode(
			array(
				'code' => $code,
				'msg'  => $msg,
				'data' => $data
			),
			JSON_UNESCAPED_UNICODE
		);
    }
	public function pageHelp($inPath){
		$token = live_auth::getPublishAuth($this->user['uid']);
		if(empty($token)){
			//设置token
			$ret = live_auth::setPublishAuth($this->user['uid']);
			if($ret){
				$token = live_auth::getPublishAuth($this->user['uid']);
				$this->assign("token",$token);
			}
		}else{
			$this->assign("token",$token);
		}
        $config = SConfig::getConfig(ROOT_CONFIG."/version.conf",'default');
		
        $header = utility_net::isHTTPS() ? "https" : "http";
        $host = $header.'://'.$_SERVER['HTTP_HOST'];
		$download = explode(',',$config->windows->download);
		$download_url = $download[0];
		$this->assign("download_url",$download_url);
		return $this->render("teacher/teacher.help.html");
	}

	//老师在教课程
	public function pageteacherOfCourseList($inPath){
		$uid = $this->user['uid'];
		//判断该用户是否属于该机构
		$judgeUserid = utility_judgeid::userid($uid,$this->orgOwner);
		if(!$judgeUserid){
			//没有权限
			header('Location: https://www.'.$this->domain);
		}
		
		$data = array();
		$data["search"] = !empty($_GET["searchdata"]) ? $_GET["searchdata"] : '';
		$data["page"] = !empty($_GET["page"]) ? $_GET["page"] : 1;
		
		$courseIdArr = array();
		$courseids 	 = '';
		$courseOfId  = teacher_api::getTeacherCourseInfoById($this->user['uid']);
		$mCouseId 	 = array();
		if(!empty($courseOfId)){
			foreach($courseOfId as $k=>$v){
				if(isset($_GET['ad'])){
					if($_GET['ad'] == 1){
						$courseIdArr[] = !empty($v->fk_course) ? $v->fk_course : 0;	
					}elseif($_GET['ad'] == 2 && $v->is_teacher == 1){
						$courseIdArr[] = !empty($v->fk_course) ? $v->fk_course : 0;		
					}elseif($_GET['ad'] == 3 && $v->is_assistant == 1){
						$courseIdArr[] = !empty($v->fk_course) ? $v->fk_course : 0;		
					}
					$mCouseId[] 	= !empty($v->fk_course) ? $v->fk_course : 0;
				}else{
					$courseIdArr[] 	= !empty($v->fk_course) ? $v->fk_course : 0;
				}
			}
		}
		$sArray =  array(
						 "user_class_id"	=> $uid,
						 "course_ids"		=> $courseIdArr,
						 "st"       		=> isset($_GET['st']) ? (int)$_GET['st'] : '',
						// "course_type"		=> isset($_GET['fc']) ? (int)$_GET['fc'] : '',
						 "ut"				=> isset($_GET['ut']) ? (int)$_GET['ut'] : '0',
						 "page"				=> isset($_GET['page']) ? (int)$_GET['page'] : '1',
						 "search"			=> !empty($_GET["searchdata"]) ? $_GET["searchdata"] : ''
						 );
		
		if(!empty($_GET["sub"])){
			$courseidsTwo = array();
			$courseSearchListRet = course_api::courselikelist(0,$courseIdArr,$sArray);
			
			if(!empty($courseSearchListRet->data)){
				foreach($courseSearchListRet->data as $k=>$v){
					$courseidsTwo[$v->course_id] = $v->course_id;
				}
			}
		}
		if(!empty($_GET['fc'])){
			$sArray['course_type'] = (int)$_GET['fc'];
		}
		$courseNum = array();
		if(!empty($courseIdArr)){
			$courseSearchListRet   = course_api::courselikelist(0,$courseIdArr,$sArray);
			if( isset($_GET['ad']) && $_GET['ad'] == 3){
				$courseIdArr = $mCouseId; 
			}
			$courseNum 			   = teacher_api::teacherCourseNum(0,$courseIdArr,$data=array());
		}
		
		$this->assign("courseNum",$courseNum);
		$page = 1;
		$length = 6;
		$total = 1;
		if(!empty($_GET["page"])){
			$page=$_GET["page"];
		}
		//统计章节和进度
		$classIds = array();
		$courseIds = array();
		$classListShow = course_api::classListByCourseIds($sArray,$page,$length);
		
		if(!empty($classListShow->data)){
			foreach($classListShow->data as $k=>$v){
				$courseIds[$v->course_id] = $v->course_id;
				$classIds[$v->class_id] = $v->class_id;
			}
			
		}
		
		//这个班级有几个结束的plan
		$planGroupList = course_api::endgroupbyclassids(0,$classIds,$sArray['course_type']='',$sArray['ut']);
		//这些课程都各有多少个章节 
		$planGroup = array();
		if(!empty($planGroupList->data)){
			foreach($planGroupList->data as $k=>$v){
				$planGroup[$v->fk_class][] = $v;
			}
		}
		
		$classInfo     = array();
		if(!empty($classListShow->data)){
			foreach($classListShow->data as $k=>$v){
				if(!empty($planGroup[$v->class_id])){
					$v->plan_info = count($planGroup[$v->class_id]);
				}else{
					$v->plan_info = 1;
				}
				$seekArr = array(
					"f"=>array('section_name'),
					"q" => array("class_id"=>$v->class_id),
					"pl"=>500,
				);
				$seekRet			 = seek_api::seekPlan($seekArr);
				$v->planend_progress = !empty($seekRet->data[0]->section_name) ? $seekRet->data[0]->section_name : 0;
				if(isset($v->course_id)){
					$classInfo[$v->course_id][] = $v;
				}
			}
		}
		
		$courseResult 	= array();
		$courseData 	= array();
		if(!empty($courseSearchListRet->data)){
			foreach($courseSearchListRet->data as $m=>$n){
				//if(!empty($classInfo[$n->course_id])){
					/*$seekData = array(
									"f"=>array('org_subname','subdomain'),
									"q" => array("course_id"=>$n->course_id),
									"pl"=>5,
								);
					$seekResult		  = seek_api::seekCourse($seekData);
					$subdomain	  = !empty($seekResult->data[0]->subdomain) 	? $seekResult->data[0]->subdomain : '';*/
					//subdomain查库
					$sub		  = user_organization::getDomainByOwner(array("fk_user_owner"=>$n->user_id));
					$org 		  = user_organization::getOrgByOwner($n->user_id);
					$subdomain	  = !empty($sub->subdomain) ? $sub->subdomain : '';
					$subname	  = !empty($org->subname) 	? $org->subname : '';
					$courseData[$n->course_id]['course_id'] = $n->course_id;
					$courseData[$n->course_id]['course_thumb'] = $n->thumb_med;
					$courseData[$n->course_id]['title'] 	= $n->title;
					$courseData[$n->course_id]['type'] 		= $n->type;
					$courseData[$n->course_id]['subname'] 	= $subname;
					$courseData[$n->course_id]['subdomain'] = $subdomain;
					$courseData[$n->course_id]['class']  	= !empty($classInfo[$n->course_id]) ? $classInfo[$n->course_id] : '' ;
				//}
			}
			$courseResult['data'] = $courseData;
		}
		 
		if(!empty($courseSearchListRet->page)){
			$page = $courseSearchListRet->page;
		}
		if(!empty($courseSearchListRet->totalPage)){
			$total = $courseSearchListRet->totalPage;
		}
		$con = '';
		if(isset($_GET['st'])){
			$con .= "&st=".$_GET['st'];
		}
		$inArray = array(0,1,2,3);
		if(isset($_GET['fc'])&&in_array($_GET['fc'],$inArray)){
			$con .= "&fc=".$_GET['fc'];
		}else{
			if(isset($_GET['searchdata'])){
				$con .= "&searchdata=".$_GET['searchdata'];
			}
			//$this->assign("searchdata",$data["search"]);
		}
		/*if(isset($_GET['fc'])){
			$con .= "&fc=".$_GET['fc'];
		}*/
		if(isset($_GET['ut'])){
			$con .= "&ut=".$_GET['ut'];
		}
		if(isset($_GET['sub'])){
			$con .= "&sub=".$_GET['sub'];
		}
		$urlBefore = "/teacher.course.teacherOfCourseList?".$con;
		$url = $urlBefore;
		$path = "/teacher.course.teacherOfCourseList";
		$this->assign("fc",isset($_GET['fc']) ? (int)$_GET['fc'] : '0');
		$this->assign("st",isset($_GET['st']) ? (int)$_GET['st'] : '1');
		$this->assign("ut",isset($_GET['ut']) ? (int)$_GET['ut'] : '0');
		$this->assign("ad",isset($_GET['ad']) ? (int)$_GET['ad'] : '1');
		$this->assign("url",$url);	
		$this->assign("page",$page);	
		$this->assign("length",$length);	
		$this->assign("total",$total);
		$this->assign("path",$path);
		$this->assign("courseResult",$courseResult);
		$classCount = 0;
		if(!empty($classListShow->totalsize)){
			$classCount = $classListShow->totalsize;
		}
		$this->assign("isOpenCreate",$this->orgInfo->teacher_add_course);
		$this->assign("classCount",$classCount);
		$this->assign("classListShow", $classListShow);
		return $this->render("teacher/teacherOfCourseList.html");
	}
	
	public function pageSourceUrl(){
		$key = md5("create_course_".$this->orgInfo->oid."_".$this->user['uid']);
		utility_session::get()[$key] = "teacher.course.teacherOfCourseList";
	}
	
	
	public function comData($retPlanInfoRet,$month=0,$md=0){
		//$month = 0;
		//$md=0;
		$showData = array();
		$today = date("Ymd",time());
		$showArr = array(
			"plan_id"=>"plan_id",	
			"course_id"=>"course_id",	
			"class_id"=>"class_id",	
			"section_id"=>"section_id",	
			"section_name"=>"section_name",	
			"class_name"=>"class_name",	
			"title"=>"course_name",	
			"start_time"=>"start_time",	
			"fee_type"=>"fee_type",	
			"price"=>"price",	
			"price_market"=>"price_market",	
			"status"=>"status",	
			"user_total_class"=>"user_total_class",
			"max_user_class"=>"max_user_class",
		);
		//首先把时间抽离出来

		//然后组合数据
		if(!empty($retPlanInfoRet->data)){
			$retPdata = $retPlanInfoRet->data;
			foreach($retPdata as $k=>$v){
				foreach($showArr as $showk=>$showv){
					if(isset($retPdata[$k]->$showk)){
						$showData[$k][$showv] = $retPdata[$k]->$showk;
					}
				}
				$showData[$k]["class_name"] = SLanguage::tr($retPdata[$k]->class_name,'site.index');
				$showData[$k]["section_name"] = SLanguage::tr($retPdata[$k]->section_name,'site.index');
				if(!empty($retPdata[$k]->start_time)){
					$mmonth = date("n",strtotime($retPdata[$k]->start_time));
					$mmd = date("md",strtotime($retPdata[$k]->start_time));
					//当传人的月份不等于这个月份的时候
					if($mmonth!=$month){
						//$month = $mmonth;
						$showData[$k]["start_time_month"] = SLanguage::tr($mmonth.'月','site.teacher');
						$month = $mmonth;
					}
					$showData[$k]["start_time_month_num"] = $mmonth;
					$todayYmd = date("Ymd",strtotime($retPdata[$k]->start_time));

					//当传人的月+日不等于这个的时候
					
					if($mmd!=$md){
						$showData[$k]["m_d"] = $mmd;
						$md = $mmd;
						$showData[$k]["start_time_m_d"] = date("n-j",strtotime($retPdata[$k]->start_time));
						$showData[$k]["start_time_H_i"] = date("H:i",strtotime($retPdata[$k]->start_time));
						$todayYmd = date("Ymd",strtotime($retPdata[$k]->start_time));
						if($todayYmd == $today)
						//$showData[$k]["start_time_m_d"] = date("今日",strtotime($retPdata[$k]->start_time));
						$showData[$k]["start_time_m_d"] = SLanguage::tr('今日','site.teacher');
						$showData[$k]["start_time_H_i"] = date("H:i",strtotime($retPdata[$k]->start_time));
					}else{
						$showData[$k]["m_d"] = $mmd;
						//$showData[$k]["start_time_m_dd"] = date("m-d H:i:s",strtotime($retPdata[$k]->start_time));
						$showData[$k]["start_time_m_d"] = date("H:i",strtotime($retPdata[$k]->start_time));
						$showData[$k]["mmm"] = date("Ymd",strtotime($retPdata[$k]->start_time));
					}

					if($retPdata[$k]->status==1){
						if($todayYmd == $today){
							$showData[$k]["b_status"] = SLanguage::tr('开始上课','site.teacher');
							//$showData[$k]["b_status_link"] = '<a href="/course.plan.start/'.$retPdata[$k]->plan_id.'" target="_blank"><button>开始上课</button></a>';
							$showData[$k]["b_status_link"] = './course.plan.start/'.$retPdata[$k]->plan_id;
							$showData[$k]["btn_color"] = "look-start-btn";
						}else{
							$showData[$k]["b_status"] = "";
							//$showData[$k]["b_status_link"] = '<a href="/course.plan.start/'.$retPdata[$k]->plan_id.'" target="_blank"><button>开始上课</button></a>';
							$showData[$k]["b_status_link"] = '';
							$showData[$k]["btn_color"] = "hidden";
						}
/*
						$showData[$k]["b_status"] = "开始上课";
						//$showData[$k]["b_status_link"] = '<a href="/course.plan.start/'.$retPdata[$k]->plan_id.'" target="_blank"><button>开始上课</button></a>';
						$showData[$k]["b_status_link"] = './course.plan.start/'.$retPdata[$k]->plan_id;
						$showData[$k]["btn_color"] = "look-start-btn";
*/

						$showData[$k]["left_status"] = SLanguage::tr('备课','site.teacher');
						$showData[$k]["left_link"] = "./teacher.manage.plan"."/".$retPdata[$k]->plan_id;
						$showData[$k]["mid_status"] = " | ";

						$showData[$k]["right_status"] = SLanguage::tr('上传视频','site.teacher');
						//$showData[$k]["right_link"] = "./video.point.videoUpload"."/".$retPdata[$k]->plan_id;
						$showData[$k]["right_link"] = "./video.point.videoUpload"."/".$retPdata[$k]->plan_id."/1";
					}else{
						$showData[$k]["b_status"] = SLanguage::tr('继续上课','site.teacher');
						$showData[$k]["b_status_link"] = './course.plan.start/'.$retPdata[$k]->plan_id;
						$showData[$k]["btn_color"] = "look-cone-btn";

						//$showData[$k]["left_status"] = "课堂统计";
						$showData[$k]["left_status"] = "";
						$showData[$k]["left_link"] = "#";

						$showData[$k]["mid_status"] = "";

						$showData[$k]["right_status"] = SLanguage::tr('视频管理','site.teacher');
						//$showData[$k]["right_link"] = "./video.point.videoPart"."/".$retPdata[$k]->plan_id;
						$showData[$k]["right_link"] = "./video.point.videoPart"."/".$retPdata[$k]->plan_id."/1";
					}

					$showData[$k]["course_type"]='';
						//$showData[$k]["course_type"]='<span class="taped-icon">线下课</span>';
					//线下课
					if($retPdata[$k]->type_id==3){
						$showData[$k]["b_status"] = '';
						$showData[$k]["b_status_link"] = '';
						$showData[$k]["btn_color"] = "hidden";
						//$showData[$k]["left_status"] = "课堂统计";
						$showData[$k]["left_status"] = "";
						$showData[$k]["left_link"] = "#";
						$showData[$k]["mid_status"] = "";
						$showData[$k]["right_status"] = SLanguage::tr('课程详情','site.teacher');
						//$showData[$k]["right_link"] = "./video.point.videoPart"."/".$retPdata[$k]->plan_id;
						//$showData[$k]["right_link"] = "./video.point.videoPart"."/".$retPdata[$k]->plan_id."/1";
						$showData[$k]["right_link"] = "./teacher.course.detail/".$retPdata[$k]->course_id.'/'.$retPdata[$k]->class_id ;
						$showData[$k]["course_type"]='<span class="taped-icon">'.SLanguage::tr('线下课','LearningCenter').'</span>';
					}
					//公用
					$showData[$k]["thumb"] = utility_cdn::file($retPdata[$k]->thumb_small) ;
					$showData[$k]["course_title_link"] = "./teacher.course.detail/".$retPdata[$k]->course_id.'/'.$retPdata[$k]->class_id ;

				}
			}
		}
		//$showData = $retPdata;
		return $showData;
	}


	public function pageDetail($inPath){
		$uid = $this->user["uid"];
		$this->assign("uid",$uid);
		$this->assign('mobile',$this->user['mobile']);
		$this->assign('token',$this->user['token']);
		$courseId = !empty($inPath[3])?$inPath[3]:0;
		$classId =  !empty($inPath[4])?$inPath[4]:0;
		$studentSelected =  !empty($inPath[5])?$inPath[5]:0;
		$orgOwner = $this->orgOwner;
		//如果为空就跳走
		if(empty($courseId)||empty($classId)){
			header('Location: https://www.'.$this->domain);
		}
		$this->assign("courseId",$courseId);	
		$this->assign("classId",$classId);	
		$planId = 0;
		$this->assign("planId",$planId);	
		//判断老师 课程 班级 是否属于该机构 班级是否属于老师  班级属否属于课程
		/*$judgeOver = $this->Judgeid($courseId,$classId,$uid,$orgOwner);
		if(empty($judgeOver)){
			//跳转
			return $this->redirect("/teacher.course.teacherOfCourseList");
		}*/
		$courseInfo = $this->CourseDetail($courseId,$orgOwner);
		$this->assign("courseInfo",$courseInfo);	
		$classInfo = course_api::getClass($classId);
		$seekArr = array(
					"f"=>array('section_name','org_subname'),
					"q" => array("plan_id"=>$classInfo->progress_plan),
					"pl"=>500,
				);
		$seekRet=seek_api::seekPlan($seekArr);
		$progressChart =  !empty($seekRet->data[0]->section_name) ? $seekRet->data[0]->section_name : '';
		$progress_percent =  !empty($classInfo->progress_percent) ? $classInfo->progress_percent : '';
		$this->assign("classInfo",$classInfo);	
		$this->assign("t_chart",$progressChart);
		$this->assign("percent",$progress_percent);
		$classIds = array("$classId");
		$courseIds = array("$courseId");
		//这个班级有几个结束的plan
		$planGroupList = course_api::endgroupbyclassids($orgOwner,$classIds);
		if(!empty($planGroupList->data)) {
			$planCount = count($planGroupList->data);
		}else{
			$planCount=0;
		}
		$this->assign("planGroupList",$planGroupList);
		$this->assign("planCount",$planCount);
		//筛选出班级的课程列表
		$plist = array(
			"cid"=>$courseId,
			"class_id"=>$classId,
			"user_plan_id"=>null,
			"sid"=>null,
			"order_by"=>"ASC",
			"allcourse"=>"true",
		);
		$listPlans = array();
		$listPlans = course_api::listPlan($plist);
		$this->assign("listPlans",$listPlans);	

		//学生列表
		//我的学生
		$page = !empty($_REQUEST["page"])?$_REQUEST["page"]:1;
		$length = !empty($_REQUEST["length"])?$_REQUEST["length"]:20;
		$showTag = 0;

		//判断是否是手机号
		$likemobile = 0;
		$likename = 0;
		if(!empty($_GET["sedata"])){
			$phonenumber = $_GET["sedata"];
			$this->assign("sedata",$phonenumber);	
			if(is_numeric($phonenumber)){
			//if(preg_match("/1[3458]{1}\d{9}$/",$phonenumber)){}
				$likemobile = 1;
				$showTag =1;
			}else{
				$likename = 1;
				$showTag =1;
			}
		}
		$regdata = array(
			'class_id'=>$classId,
		);
		if(!empty($courseId)){
			$regdata["course_ids"] = $courseId;
		}
		//获取courseid是这些的老师的报名学生
		$reglist = course_api::listRegistrationBycond($regdata,$page,$length);
		$user_ids_arr = array();
		//筛选出这批人的userid
		if(!empty($reglist->data)){
			foreach($reglist->data as $k=>$v){
				$user_ids_arr[$v->uid]=$v->uid;
			}
		}
		if($likemobile){
			$mobile = $_GET['sedata'];
			$user_like_list = user_api::listUserIdsBylikeMobileArr($user_ids_arr,$mobile);
		}
		if($likename){
			$sreal_name = $_GET['sedata'];
			$user_like_list = user_api::listUserIdsBylikeNameArr($user_ids_arr,$sreal_name);
		}

		//like查询出的用户  组合userid
		if(!empty($user_like_list->data)){
			foreach($user_like_list->data as $k=>$v){
				$list_reg_uid_arr2[$v->user_id] = $v->user_id;
			}
			//如果是模糊查询的就不用在查询一次库直接拿出数据
			//真实姓名
			//前端显示的配对真实姓名数组
			foreach($user_like_list->data as $k=>$v){
				$realNameArr[$v->user_id] = $v;
			}
			//结果	
			 //查询出这个班中是这些id的报名的人
			$regdata2 = array(
				//'course_ids'=>150,
				'class_id'=>$classId,
				'uids'=>$list_reg_uid_arr2,
			);
			if(!empty($courseId)){
				$regdata2["course_ids"] = $courseId;
			}
			//结果
			$reglist1 = course_api::listRegistrationBycond($regdata2,$page,$length);
		}
		$ret = array();
		$ret = $reglist;
		//模糊查询部分替换掉 全查部分数据
		if(!empty($reglist1->data)){
			$ret = new stdclass;
			$ret->data = $reglist1->data;
		}elseif($likename ||$likemobile){
			$ret = new stdclass;
			$ret->data = 0;
		}
		$totalStudent = 0;
		if(!empty($ret->totalSize)){
			$totalStudent = $ret->totalSize;
		}

		$retlistAtt = course_api::listPlanAttach(array('classId'=>$classId));
		$listAtt = 0;
		if(!empty($retlistAtt->data)){
			$listAtt = $retlistAtt->data;
		}
		//print_r($listAtt);
		$retlistAtt = course_api::listPlanAttach(array('classId'=>$classId));
		if(!empty($retlistAtt->data)){
			$countAtt = count($retlistAtt->data);
		}else{
			$countAtt = 0;
		}
		//获取班级上课统计
		$classPlanStat = array(
			"vvRecord"=>0,//时长
			"discuss"=>0,//评论数
			"zan"=>0,//赞数
			"handup"=>0,//举手数
			"call"=>0,//发言数
			"maxOnline"=>0,//最大同事在线人数
			"onTime"=>0,//准时人数
			"late"=>0,//迟到人数
			"correct"=>0,//随堂测试正确率
			"answerRate"=>0,//询问回答率
			"lastUpdated"=>0,//更新时间
			"attendance"=>0,//到课率
		);
		$planIdArr = array();
		$planStatList = array();
		$videoIdArr = array();
		if(!empty($planGroupList)&&!empty($planGroupList->data)) {
			foreach ($planGroupList->data as $list) {
				$planIdArr[] = $list->pk_plan;
			}
		}
		if(!empty($planIdArr)){
			$pIdStr = implode(",",$planIdArr);
			//$pIdStr= "4141,4166";
			$planStatList = stat_api::getPlanStatByPidStr($pIdStr);

			$courPlan = course_plan_api::getPlanList(array("condition"=>"pk_plan IN ({$pIdStr})","order"=>""));
			if(!empty($courPlan->data->items)){
				foreach($courPlan->data->items as $videoId){
					$videoIdArr[] = $videoId->video_id;
				}
			}
		}
		$planStatCount = 0;
		$planStatCorrectCount = 0;
		$planStatAnswerRateCount = 0;
		if(!empty($planStatList)&&!empty($planStatList->data)){
			foreach($planStatList->data as $stat){
				$classPlanStat["vvRecord"] +=$stat->vv_record;
				$classPlanStat["discuss"] +=$stat->discuss;
				$classPlanStat["zan"] +=$stat->zan;
				$classPlanStat["handup"] +=$stat->handup;
				$classPlanStat["call"] +=$stat->call;
				$classPlanStat["maxOnline"] +=$stat->max_online;
				$classPlanStat["onTime"] +=$stat->on_time;
				$classPlanStat["late"] +=$stat->late;
				$classPlanStat["correct"] +=$stat->correct*100;
				$classPlanStat["answerRate"] +=$stat->answer_rate*100;
				if(strtotime($classPlanStat["lastUpdated"])<strtotime($stat->last_updated)) {
					$classPlanStat["lastUpdated"] = $stat->last_updated;
				}
				if($totalStudent>0) {
					$classPlanStat["attendance"] += ($stat->on_time + $stat->late) / $totalStudent;
				}
				$planStatCount +=1;
				if(!empty($stat->classroom_test_count)&&$stat->classroom_test_count>0){
					$planStatCorrectCount +=1;
				}
				if(!empty($stat->ask_count)&&$stat->ask_count>0){
					$planStatAnswerRateCount +=1;
				}
			}
		}
		$totaltime = 0;
		$video = video_api::getVideoByIdArr($videoIdArr);
		if(!empty($video)){
			foreach($video as $video){
				$totaltime += $video->totaltime;
			}

		}
		$classPlanStat["vvRecord"] = round(($totaltime/60));
		if($planStatCorrectCount>0) {
			$classPlanStat["correct"] = round(($classPlanStat["correct"] / 100 / $planStatCorrectCount), 2);
		}
		if($planStatAnswerRateCount>0) {
			$classPlanStat["answerRate"] = round(($classPlanStat["answerRate"] / 100 / $planStatAnswerRateCount), 2);
		}
		if($planStatCount>0) {
			$classPlanStat["attendance"] = round(($classPlanStat["attendance"] / $planStatCount * 100), 2);
		}
		if($classPlanStat["attendance"]>100){
			$classPlanStat["attendance"]=100;
		}
		$config = SConfig::getConfig(ROOT_CONFIG."/version.conf",'default');
		$download = explode(',',$config->windows->download);
		$act = !empty($_REQUEST["act"])?$_REQUEST["act"]:"";
		if(!empty($act)&&$act=="ajaxStudent"){
			if(!empty($reglist->data)) {
				foreach($reglist->data as &$value){
					if(!empty($value->user_info->thumb_med)) {
						$value->user_info->thumb_med = utility_cdn::file($value->user_info->thumb_med);
					}else{
						if(empty($value->user_info)){
							$value->user_info = new stdClass();
						}
						$value->user_info->thumb_med="";
					}
					$value->create_time = date('Y-m-d',strtotime($value->create_time));
				}
				return json_encode(array("code" => 1, "data" => $reglist));
			}else{
				return json_encode(array("code" => -1, "data" => array()));
			}
			exit;
		}
		if(empty($reglist->total)){
			$totalPage=1;
		}else{
			$totalPage=$reglist->total;
		}
		$this->assign('host',$download[0]);//获取下载链接地址
		$this->assign('classPlanStat',$classPlanStat);
		$this->assign('countAtt',$countAtt);
		$this->assign('listAtt',$listAtt);
		$this->assign('showTag',$showTag);
		$this->assign('studentSelected', $studentSelected);
		$this->assign("totalStudent",$totalStudent);
		$this->assign("ret",$ret);
		$this->assign("page",$page);
		$this->assign("length",$length);
		$this->assign("totalPage",$totalPage);
		$this->assign("classId",$classId);
		$this->assign("courseId",$courseId);

		return $this->render("teacher/teacher.course.detail.html");
	}

	public function Judgeid($courseId = 0,$classId = 0,$userId = 0,$orgOwner = 0){
		$judgeCourse = utility_judgeid::courseid($courseId,$userId,$orgOwner);//course是否属于该机构
		$judgeClass = utility_judgeid::classid($classId,$userId,$orgOwner);//class是否属于该机构 该用户是否是该机构下的老师
		$judgeClassBelong = utility_judgeid::classBelonguid($classId,$userId);//class是否属于该机构 该用户是否是该机构下的老师
		//return $judgeClassBelong;

		if(empty($judgeClass) || empty($judgeCourse) || empty($judgeClassBelong)){
			return false;
		}
		if($judgeClass->course_id!=$courseId){
			return false;
		}
		return 1;
	}

	public function CourseDetail($courseId=0,$userOwner=0){
		if(empty($courseId)||empty($userOwner)){
			return false;
		}
		$f_array = array("tags","course_id","title","create_time","desc","start_time","thumb_big",
			"course_type","user_id","public_type","fee_type","price","market_price","max_user","min_user","user_total",
			"status","admin_status","system_status","class_id",
			"end_time","create_time","last_updated","create_time",
			"class",
			//新加上的
			'top'=>'top',
			'search_field'=>'search_field',
		);
		//	设置q
		$q_array = array();
		$q_array["course_id"] = $courseId;
		$q_array["user_id"] = $userOwner;
		$ob_array = array();
		$ob_array["course_id"] = "desc";
		// 设置p
		$p = 1;	 
		// 设置pl
		$pl = 20;	 

		$seek_arr = array(
			"f"=>$f_array,
			"q"=>$q_array,
			"ob"=>$ob_array,
			"p"=>$p,
			"pl"=>$pl,
		);
		$ret_seek= seek_api::seekcourse($seek_arr);
		return $ret_seek;
	}
    //关闭机构教学中心引导
    public function pageCloseGrowthTips($inPath){
        //用户点击关闭提示条
        $user=user_api::loginedUser();
        if(!empty($user)){ 
            $guide=array(
                    'status'=>1, 
                );
            $updateUserGuide=user_api::updateUserGuide($user['uid'],4,$guide);
            return $updateUserGuide;
        }else{
            return false;
        }
    }

	public function pageTimetable2($inPath){
		$uid = 0;
		if(!empty($this->user['uid'])){ $uid=$this->user["uid"];}
		if(!empty($this->user['mobile'])){ $mobile=$this->user["mobile"];}
		if(!empty($this->user['token'])){ $token=$this->user["token"];}
		$strstart_time = strtotime(date("Y-m-01",time()));
		$strend_time = strtotime(date("Y-m-t",time()));
		$start_time = date('Y-m-d 00:00:00',$strstart_time);
		$endstart_time = date('Y-m-d 23:59:59',$strend_time);
		$config = SConfig::getConfig(ROOT_CONFIG."/version.conf",'default');
		$download = explode(',',$config->windows->download);
		$this->assign('host',$download[0]);//获取下载链接地址
		$planCond = array(
			'allcourse'    => true,
			'order_by'     => 'desc',
			'page'         => 1,
			'length'       => 10000,
			'startTime'    => "$start_time,$endstart_time",
			"user_plan_id" =>$uid,
		);
		//print_r($planCond);
		$planRes1 = course_api::listPlan($planCond);

		$jsonOrm = array(
			"plan_id",
			//"user_id",
			"course_id",
			"section_id",
			//"fk_user_class",
			"fk_user_plan",
			"user_total_class",
			"max_user_class",
			"live_public_type",
			"video_public_type",
			//"user_plan_name",
			"start_time",
			"status",
			"title",
			"fee_type",
			"price",
			"section_name",
			"class_name",
			"thumb_med",
		);
		if(!empty($planRes1->data)){
			foreach($planRes1->data as $plk=>$plv){
				foreach($jsonOrm as $jsk=>$jsv){
					//$planRes[$plk]["live_public_type"] = 0;
					//$planRes[$plk]["video_public_type"] = 0;
					if(!empty($plv->$jsv)){
						//$jsonOrm[$jsv] = $plv->$jsv;
						$planRes[$plk][$jsv] = $plv->$jsv;
					}
					if(!empty($plv->user_plan)){
						$planRes[$plk]["user_plan_name"] = $plv->user_plan->name;
					}
				}
			}
		}
		//print_r($jsonOrm);


		//拼接年月日数组
		$yearArr = date("Y");
		$monthArr = date("m");
		$countMonthdays = date("t");
		$jsonTimeArr = array();
		for($i=1;$i<=$countMonthdays;$i++){
			$keyArr = $yearArr.$monthArr.$i;
			$jsonTimeArr[$keyArr] = array();
		}
		/*数组格式 
			array( 
				[月份]=> 数据
		)*/

		$courseIdArr1 = array();
		if(!empty($planRes)){
			foreach($planRes as $rek=>$rev){
				$timeTostr  = date("Ymj",strtotime($rev["start_time"]));
				$jsonTimeArr[$timeTostr][] = $rev; 
			}
		}
		//$this->assign("regStatus",$regStatus);  //报名状态
		$this->assign("token",$token);
		$this->assign("mobile",$mobile);
		$this->assign("jsonTimeArr",json_encode($jsonTimeArr)); //json后的plan数据
		$this->assign("TimeArr",$jsonTimeArr);  //未json的plan数据
		$this->assign("orgInfo",$this->orgInfo);  //机构信息
		$this->assign("userId",$uid);
		//$this->assign("subArr",$subArr);  //机构信息
		//$this->render("course/live.plan.list.html");
		$this->render("teacher/teacher.new.timetable.html");
	}


	public function pagelivePlanAjax($inPath){
		if(empty($_REQUEST["start_time"])){
			$this->setAjaxResult("1000777","failed"," data incorrect");
		}
		$starttime = (int)$_REQUEST["start_time"];
		if(empty($starttime) || !is_numeric($starttime)){
			$this->setAjaxResult("1000777","failed"," data incorrect");
		}
		$time = $starttime;
		$strstart_time = strtotime(date("Y-m-01",$time));
		$strend_time = strtotime(date("Y-m-t",$time));
		$start_time = date('Y-m-d 00:00:00',$strstart_time);
		$endstart_time = date('Y-m-d 23:59:59',$strend_time);
		
		$planCond = array(
			'allcourse'    => true,
			'order_by'     => 'desc',
			'page'         => 1,
			'length'       => 10000,
			'type'         =>"1,3",
			'startTime'    => "$start_time,$endstart_time",
			"user_plan_id" =>$this->user['uid'],
		);
		$planRes1 = course_api::listPlan($planCond);
		$jsonOrm = array(
			"plan_id",
			//"user_id",
			"course_id",
			"section_id",
			//"fk_user_class",
			"fk_user_plan",
			"user_total_class",
			"max_user_class",
			"live_public_type",
			"video_public_type",
			//"user_plan_name",
			"start_time",
			"status",
			"title",
			"type_id",
			"fee_type",
			"price",
			"section_name",
			"class_name",
			"thumb_med",
			"title",
		);
		$org = user_organization::subdomain();
		if(!empty($planRes1->data)){
			foreach($planRes1->data as $plk=>$plv){
				foreach($jsonOrm as $jsk=>$jsv){
					$planRes[$plk]["live_public_type"] = $plv->live_public_type;
					$planRes[$plk]["video_public_type"] = $plv->video_public_type;
					$planRes[$plk]["type_id"] = $plv->type_id;
					$planRes[$plk]["user_total_class"] = $plv->user_total_class;
					$planRes[$plk]["class_id"] = $plv->class_id;
					$planRes[$plk]["section_title"] = $plv->section_descipt;
					$planRes[$plk]["fee_type"] = $plv->fee_type;
					$subdomain 				   = user_organization::course_domain($plv->user_course->domain);
					$planRes[$plk]["url"] = "//".$org->subdomain."/teacher.course.detail/".$plv->course_id."/".$plv->class_id;
					$planRes[$plk]["prepare_course_url"] = "//".$subdomain."/teacher.manage.plan/".$plv->plan_id;
					$planRes[$plk]["continue_course_url"] = "//".$subdomain."/course.plan.start/".$plv->plan_id;
					$planRes[$plk]["video_manage_url"] = "//".$subdomain."/user.teacher.part/".$plv->plan_id.'/1';
					$planRes[$plk]["upload_video_url"] = "//".$subdomain."/video.point.videoUpload/".$plv->plan_id.'/2';
					$planRes[$plk]["video_t_url"] = "//".$subdomain."/video.point.videoPart/".$plv->plan_id.'/2';
					$planRes[$plk]["thumb_med"] = utility_cdn::file($plv->thumb_med);
					if(!empty($plv->$jsv)){
						//$jsonOrm[$jsv] = $plv->$jsv;
						$planRes[$plk][$jsv] = $plv->$jsv;
					}
					if(!empty($plv->user_plan)){
						$planRes[$plk]["user_plan_name"] = $plv->user_plan->name;
					}
				}
			}
		}
		//print_r($jsonOrm);
		//拼接年月日数组
		$yearArr = date("Y");
		$monthArr = date("m");
		$countMonthdays = date("t");
		$jsonTimeArr = array();
		for($i=1;$i<=$countMonthdays;$i++){
			$keyArr = $yearArr.$monthArr.$i;
			$jsonTimeArr[$keyArr] = array();
		}
		/*数组格式 
			array( 
				[月份]=> 数据
		)*/
		$courseIdArr1 = array();
		if(!empty($planRes)){
			foreach($planRes as $rek=>$rev){
				$timeTostr  = date("Ymj",strtotime($rev["start_time"]));
				if(time()>strtotime($rev["start_time"])){
					$rev['t_status'] = 1;
				}elseif(time()<strtotime($rev["start_time"])){
					$rev['t_status'] = 2;
				}
				$rev["start_time"] = date("H:i",strtotime($rev["start_time"]));
				$jsonTimeArr[$timeTostr][] = $rev;
				if(!empty($rev["course_id"])){
					$courseIdArr1[$rev["course_id"]] = $rev["course_id"];
				}
			}
		}
		foreach($courseIdArr1 as $k=>$v){
			$courseIdArr[] = $v;
		}
		echo json_encode(array("code"=>200,"msg"=>"success","data"=>$jsonTimeArr));
		//$this->setAjaxResult("200","success",$jsonTimeArr);
	}
	public function pageMyClassList($inPath){
		$uid = $this->user['uid'];
		$classCond = array("user_id"=>$this->orgOwner,"user_class_id"=>$uid,"course_id"=>"0");
		$classList = course_api::classlistbycond($classCond);
		//拿出course_ids
		$courseids = array();
		if(!empty($classList)){
			foreach($classList->data as $ck=>$cv){
				if(!empty($cv->course_id)){
					$courseids[$cv->course_id] = $cv->course_id;
				}
			}
		}
		$courseidsTwo = $courseids;

		if(!empty($data["search"])){
			$courseidsTwo = array();
			$courseSearchListRet = course_api::courselikelist($this->orgOwner,$courseids,$data);
			if(!empty($courseSearchListRet->data)){
				foreach($courseSearchListRet->data as $k=>$v){
					$courseidsTwo[$v->course_id] = $v->course_id;
				}
			}
		}
		//结束－－－－－
		$sArray =  array("user_id"=>$this->orgOwner,"user_class_id"=>$uid,"course_ids"=>$courseidsTwo);
		//print_r($sArray);

		$page = 1;
		$length = 6;
		$total = 1;
		if(!empty($_GET["page"])){
			$page=$_GET["page"];
		}

		$classListShow = course_api::classListByCourseIds($sArray,$page,$length);
		if(!empty($classListShow->page)){
			$page = $classListShow->page;
		}
		if(!empty($classListShow->total)){
			$total = $classListShow->total;
		}

		$classIds = array();
		$courseIds = array();
		if(!empty($classListShow->data)){
			foreach($classListShow->data as $k=>$v){
				$courseIds[$v->course_id] = $v->course_id;
				$classIds[$v->class_id] = $v->class_id;
			}
		}

		if(!empty($classListShow->data)){
			foreach($classListShow->data as $k=>$v){
				if(!empty($planGroup[$v->class_id])){
					$v->plan_info = $planGroup[$v->class_id];
				}else{
					$v->plan_info = new stdclass;
					$v->plan_info->planend_count = 0;
					$v->plan_info->fk_user = 0;
					$v->plan_info->fk_class = 0;
				}

				if(!empty($sectionGroup[$v->course_id])){
					$v->section_info = $sectionGroup[$v->course_id];
				}else{
					$v->section_info = new stdclass;
					$v->section_info->section_count = 0;
					$v->section_info->fk_course = 0;

				}
			}
		}
		$classCount = 0;
		if(!empty($classListShow->totalsize)){
			$classCount = $classListShow->totalsize;
		}
	}


}
