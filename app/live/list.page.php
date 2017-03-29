<?php
class live_list extends STpl{
	private $domain;
	private $orgOwner;
	function __construct(){
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
		$this->assign('domain', $this->domain);

		$org=user_organization::subdomain();
		if(!empty($org)){
			$this->orgOwner = $org->userId; //机构所有者id 以后会根据域名而列取 
		}else{
			header('Location: https://www.'.$this->domain);
		}
		$this->orgInfo = user_organization::getOrgByOwner($this->orgOwner);
	}
	public function pageEntry($inPath)
	{
		$params = [
			'q' => ['course_type'=>1,'admin_status'=>1,'user_id'=>$this->orgOwner],
			'f' => [
					"tags","course_id","title","create_time","desc","start_time","thumb_big",
					"thumb_med","thumb_sma","course_type","user_id","public_type",
					"fee_type","price","market_price","max_user","min_user","user_total","status","admin_status",
					"system_status","class_id","end_time","create_time","last_updated","class",
					"third_cate_name","course_attr",
			       ],
			'p' => 1,
			'pl'=> 4,
			'ob'=> ['vv'=>'desc']	
		];
		$seekCourse = seek_api::seekcourse($params);
		$houtCourse = array();
		if(!empty($seekCourse->data)){
			$houtCourse = $seekCourse->data;
			foreach($houtCourse as &$val){
				//$val->sectionNum = count($val->section);
				$val->courseUrl  = '/course.info.show/'.$val->course_id;
				if(!empty($val->course_attr)){
					foreach($val->course_attr as $couk=>$couv){
						if(!empty($couv->attr_value)){
							foreach($couv->attr_value as $couvk=>$couvv){
								$val->attrName = $couvv->attr_value_name;
							}
						}
					}
				}
			}
		}
		$this->assign("houtCourse",$houtCourse); 
		$this->assign("orgInfo",$this->orgInfo);	
		$this->render("course/live.plan.list.html");
	}

	public function pagelivePlanAjax($inPath)
	{
		if(empty($_REQUEST["start_time"]) || !is_numeric($_REQUEST["start_time"])){
			return $this->setAjaxResult("1000777","failed"," data incorrect");
		}
		$loginInfo = user_api::loginedUser();
		$uid = !empty($loginInfo['uid']) ? $loginInfo['uid'] : 0;
	
		$data = $this->courseList($_REQUEST["start_time"],$uid);
		return $this->setAjaxResult("200","success",$data);
	}
	private function courseList($time,$uid=0)
	{
		$data = array();
		if(empty($time) || !is_numeric($time)){
			return $data;
		}
		
		$year  = date("Y",$time);
		$month = date("m",$time);
		$days = ($month == 2) ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))):(($month-1) % 7 % 2 ? 30 : 31);
		$startTime = date('Y-m-d 00:00:00',strtotime(date("Y-m-01",$time)));
		$endTime   = date('Y-m-d 23:59:59',strtotime(date("Y-m-t",$time)));
		$params = [
			'allcourse'    => false,
			'order_by'     => 'desc',
			'page'         => 1,
			'length'       => 10000,
            'type'         => 1,
			'startTime'    => "$startTime,$endTime",
			"orgUserId"    => $this->orgOwner,
		];
		$planRes = course_api::listPlan($params);
		//if(empty($planRes->data)) return $data;
		
		$courseIdArr   = array();
		$videoTypeArr  = [0,-2,-1];
		$liveStatusArr = [1,2];
		$try = array(); //试看
		foreach($planRes->data as $val){
			$courseIdArr[$val->course_id] = $val->course_id;
			if($val->status == 3 && !in_array($val->video_public_type,$videoTypeArr)){
				$try[$val->plan_id] = 1;
			}elseif(in_array($val->status,$liveStatusArr) && $val->live_public_type != 0){
				$try[$val->plan_id] = 1;
			}
		}
		$startPlan = $this->getResellOrgPlanList();
		$courseArrResult = array_merge($startPlan['fkCourse'],$courseIdArr);
		//是否报名
		$applyType = array();
		if(!empty($courseArrResult) || $uid != 0){
			$courseIds = implode(',',$courseArrResult);
			$regParams = ['course_ids'=>$courseIds,'uids'=>array($uid)];
			$studentRet = course_api::listRegistrationBycond($regParams);
			
			if(!empty($studentRet->data)){
				foreach($studentRet->data as $val){
					$applyType[$val->cid] = 1;
				}
			}
		}
		$planInfo = array_merge(!empty($startPlan['result']) ? $startPlan['result'] : array(),!empty($planRes->data) ? $planRes->data : array());
		//拼装数据
		for($i=1;$i<=$days;$i++){
			$data[$year.$month.$i] = array();
			foreach($planInfo as $val){
				$d = date('j',strtotime($val->start_time));
				if($d == $i){
					//已完结并且有观看权限
					if($val->status == 3){
						if($val->video_public_type!=-2){
							$buttonType[$val->plan_id] = '1';
							if(isset($val->price_resell)){
								$buttonUrl[$val->plan_id]  = '/course.plan.play/'.$val->plan_id.'/'.$this->orgInfo->oid;
							}else{
								$buttonUrl[$val->plan_id]  = '/course.plan.play/'.$val->plan_id;
							}
						}else{
							$buttonType[$val->plan_id] = '4';
							if(isset($val->price_resell)){
								$buttonUrl[$val->plan_id]  = '/course.info.show/'.$val->course_id.'/'.$this->orgInfo->oid;
							}else{
								$buttonUrl[$val->plan_id]  = '/course.info.show/'.$val->course_id;
							}
							
						}
					}else{
						if(!empty($try[$val->plan_id]) || ($uid != 0 && !empty($applyType[$val->course_id]))){
							$buttonType[$val->plan_id] = '2';
							if(isset($val->price_resell)){
								$buttonUrl[$val->plan_id]  = '/course.plan.play/'.$val->plan_id.'/'.$this->orgInfo->oid;
							}else{
								$buttonUrl[$val->plan_id]  = '/course.plan.play/'.$val->plan_id;
							}
							
						}else{
							$buttonType[$val->plan_id] = '3';
							if(isset($val->price_resell)){
								$buttonUrl[$val->plan_id]  = '/course.info.show/'.$val->course_id.'/'.$this->orgInfo->oid;
							}else{
								$buttonUrl[$val->plan_id]  = '/course.info.show/'.$val->course_id;
							}
							
						}
					}
					
					$data[$year.$month.$i][] = [
						'plan_id'           => $val->plan_id,
						'user_plan_name'    => !empty($val->user_plan->real_name) ? $val->user_plan->real_name : '',
						'course_id'         => $val->course_id,
						'section_id'        => $val->section_id,
						'fk_user_plan'      => $val->fk_user_plan,
						'user_total_class'  => !empty($val->user_total_class) ? $val->user_total_class : 0,
						'max_user_class'    => !empty($val->max_user_class) ? $val->max_user_class : 0,
						'video_public_type' => $val->video_public_type,
						'start_time'        => date('H:i',strtotime($val->start_time)),
						'status'            => $val->status,
						'title'             => $val->title,
						'section_name'      => $val->section_name,
						'class_name'        => $val->class_name,
						'thumb_med'         => utility_cdn::file($val->thumb_med),
						'buttonType'        => !empty($buttonType[$val->plan_id]) ? $buttonType[$val->plan_id] : '',
						'buttonUrl'         => !empty($buttonUrl[$val->plan_id]) ? $buttonUrl[$val->plan_id] : '',
						'fee_type'          => $val->fee_type,
						'price'             => !empty($val->price) ? $val->price : 0,
						'tips'             => isset($val->price_resell) ? 1 : 0,
						'price_resell'             => isset($val->price_resell) ? $val->price_resell : 0,
						'tryAndSee'         => !empty($try[$val->plan_id]) ? $try[$val->plan_id] : 0
					];
				}
			}
	    }
		return $data;
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
	
	//获取该分销课的排课相关信息
	public function getResellOrgPlanList(){
		//获取分销
		$condition = array("status"=>1,"fk_org_resell"=>$this->orgInfo->oid);
		$salesInfo = course_api::getSalesCourse($page=1,$length=0,$condition);
		if(!empty($salesInfo)){
			foreach($salesInfo as $k=>$v){
				$resell[$v->fk_course]['course_id']    = $v->fk_course;
				$resell[$v->fk_course]['price_resell'] = $v->price_resell/100;
			}
		}
		$courseIdStr = '';
		
		$timeArr1=array(
            "f"=>array(
                'course_id',
                'course_name',
                'section_name',
				'class_name',
				'section_id',
				'max_user',
				'video_public_type',
                'plan_id',
                'teacher_id',
                'admin_name',
                'teacher_real_name',
                'start_time',
                'fee_type',
                'try',
                'status',
				'course_thumb_med',
            ),
            "q"=>array(
                //'start_time'=>$time1,
                'status'=>'1,2',
                'course_type'=>1,
                'admin_status'=>1,
                //'owner_id'=>$ownerId,
				'expression'   => "@resell_org_id =".$this->orgInfo->oid,
            ),
            "ob"=>array(
                'start_time'=>'desc',
            ),
            "p"=>1,
            "pl"=>10000,
        );
        $StartedPlan =seek_api::seekPlan($timeArr1);
		$fkCourse    = array();
		if(!empty($StartedPlan->data)){
			foreach($StartedPlan->data as $m=>$n){
				$fkCourse[] =  $n->course_id;
				$n->price_resell = !empty($resell[$n->course_id]['price_resell']) ? $resell[$n->course_id]['price_resell'] : 0;
				$n->user_plan_name = !empty($n->teacher_real_name) ? $n->teacher_real_name : '';
				$n->fk_user_plan = !empty($n->teacher_id) ? $n->teacher_id : '';
				$n->title = !empty($n->course_name) ? $n->course_name : '';
				$n->thumb_med = !empty($n->course_thumb_med) ? $n->course_thumb_med : '';
				//$n->price = 0;
			}
			$courseStr = implode(",",array_unique($fkCourse));
		}
	   $data 			 = array();
	   $data['fkCourse'] = $fkCourse;
	   $data['result']   = !empty($StartedPlan->data) ? $StartedPlan->data : '';
	   return $data;
	}
	




}
