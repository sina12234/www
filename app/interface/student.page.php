<?PHP
class interface_student extends interface_base{
	
	//学生课程日历
	public function pageMyTable(){
		$startTime = !empty($this->paramsInfo['params']['startTime']) ? $this->paramsInfo['params']['startTime'] : '';
		$endTime   = !empty($this->paramsInfo['params']['endTime']) ? $this->paramsInfo['params']['endTime'] : '';
		$userId    = !empty($this->paramsInfo['params']['userId']) ? $this->paramsInfo['params']['userId'] : 0;
		$orgId     = !empty($this->paramsInfo['oid']) ? $this->paramsInfo['oid'] : 0;
		
		$data = array();
		if(empty($startTime) || empty($endTime) || empty($userId)){
			return $this->setMsg(1000);
		}

		$orgInfo = user_api::getOrgByid($orgId);
		$ownerId = 0;//!empty($orgInfo->fk_user_owner) ? $orgInfo->fk_user_owner : 0;

		//报名的课程
		$myCourse = interface_user_api::getUserRegCourse($userId,array(),1,-1,$ownerId);
		if(empty($myCourse['items'])) return $this->setData($data);
		
		foreach($myCourse['items'] as $val){
			$courseIdArr[] = $val['fk_course'];
			$classIdArr[]  = $val['fk_class'];
		}
		$courseIds = implode(',', $courseIdArr);
		$classIds  = implode(',', $classIdArr);
		//章节信息
		$planParams = [
			'q' => ["course_id"=>$courseIds,"class_id"=>$classIds,"start_time"=>"{$startTime},{$endTime}","status"=>"1,2,3",'course_type'=>'1,3'],
			'f' => [
					'plan_id','course_id','start_time','class_name','course_name',
					'section_name','status','subdomain','course_thumb_big','course_thumb_med',
					'course_thumb_small','course_type','region_level0','region_level1','region_level2',
					'address','class_id'
				],
			'p' => 1,
			'pl'=> 5000,
			'ob'=> ['start_time'=>'asc']
		];
		
		$planReg = seek_api::seekPlan($planParams);
		if(empty($planReg->data)) return $this->setData($data);

		foreach($planReg->data as $v){
			$planIdArr[] = $v->plan_id;
		}
		$statusArr = array();
		$statusName = array('normal'=>'1','living'=>2,'finished'=>3,'-1'=>'invalid','0'=>'initial');
		$status = course_api::getPlanStatusV2($planIdArr);
		if(!empty($status)){
			foreach($status as $key=>$val){
				$statusArr[$key] = $statusName[$val->plan_status];
			}
		}

		$region  = region_geo::$region;
		$address = array();
		foreach($planReg->data as $k=>$v){
			$d = date('Y-n-j', strtotime($v->start_time));
			$data[$d]['time'] = $d;
			if($v->course_type == 3){
				$regionLevel0 = !empty($region[$v->region_level0]) ? $region[$v->region_level0] : '';
				$regionLevel1 = !empty($region[$v->region_level1]) ? $region[$v->region_level1] : '';
				$regionLevel2 = !empty($region[$v->region_level2]) ? $region[$v->region_level2] : '';
				$address[$v->class_id] = $regionLevel0.$regionLevel1.$regionLevel2.$v->address;
			}
			$data[$d]['data'][] = [
				'planId'      => $v->plan_id,
				'className'   => $v->class_name,
				'courseName'  => $v->course_name,
				'courseImg'   => interface_func::imgUrl($v->course_thumb_med),
				'sectionName' => $v->section_name,
				'courseType'  => $v->course_type,
				'status'      => !empty($statusArr[$v->plan_id]) ? $statusArr[$v->plan_id] : 1,
				'address'     => !empty($address[$v->class_id]) ? $address[$v->class_id] : '',
				'stime'       => date('H:i', strtotime($v->start_time))
			];
		}
		
		$data = array_values($data);
		return $this->setData($data);
	}
}
?>
