<?php
/*
	用户管理
*/
class openapi_user extends openapi_base{
	//个人中心
	public function pageCenter(){
		$returnData = array();
        $userId = !empty($this->params->userId) ? (int)$this->params->userId : 0;
        $mobile = !empty($this->params->mobile) ? $this->params->mobile : '';
        $page   = !empty($this->params->pn)     ? (int)$this->params->pn : 1;
        $pageNumber = !empty($this->params->pl)     ? (int)$this->params->pl   : 20;
        
		if(!empty($mobile) && empty($userId)){
			$userRes = user_api::getUserIdByMobile($mobile);
			$userId  = ($userRes->code === 0) ? $userRes->uid : $userId;
		}
        if(!$userId) self::returnData(-4);

		//用户报名的课程
        $userReg     = interface_user_api::getUserRegCourse($userId, array(), $page, $pageNumber);
        
        $signCount   = !empty($userReg['totalSize']) ? $userReg['totalSize'] : 0;
        $courseIdArr = !empty($userReg['items']) ? array_column($userReg['items'], 'fk_course', 'fk_class') : array();

        $userStatRet = user_api::getUserStatByUid($userId);
        $vt          = !empty($userStatRet) ? ($userStatRet->vt_live + $userStatRet->vt_record) : 0;
        $vv          = !empty($userStatRet) ? ($userStatRet->vv_live + $userStatRet->vv_record) : 0;
        $studyCount = user_api::getUserPlanStatCountByUid($userId);
		//基本信息
        $returnData['base'] = [
            "signCourseCount" => $signCount,
            "vv"              => $vv,
            "studyPlanCount"  => $studyCount,
            "vt"              => $vt
        ];

		$courseIds = !empty($courseIdArr) ? implode(',', $courseIdArr) : 0;
		
		//课程信息
		$returnData['courseInfo'] =array();
		$params = [
			'q' => ['course_id'=>$courseIds],
			'f' => [
					'course_id','class_id','class','title','price','course_type','course_tag_id',
					'thumb_med','third_cate_name','course_attr','subdomain'
				],
			'p' => $page,
			'pl'=> $pageNumber
		];
		if(!empty($this->app->orgid) || !empty($this->params->orgId)){
			$params['q']['org_id'] = !empty($this->app->orgid) ? (int)$this->app->orgid : (int)$this->params->orgId;
		}
		$seekCourse = seek_api::seekCourse($params);

		$returnData['courseInfo'] = [
			"page"      => $seekCourse->page,
			"totalPage" => $seekCourse->pagelength,
			"totalSize" => $seekCourse->total,
		];
		if(!empty($seekCourse->data)){
			$adminIdArr = array();
			$attrArr    = array();
			$className  = array();
			$teacherInfo= array();
			
			$classIdArr = array_keys($courseIdArr);;
			foreach($seekCourse->data as $value){
				if(!empty($value->class)){
					foreach($value->class as $v){
						if(in_array($v->class_id, $classIdArr)){
							$adminIdArr[] = $v->class_admin_id;
							$className[$value->course_id] = $v->name;
						}  
					}
				}

				//科目信息
				if(!empty($value->course_attr)){
					foreach($value->course_attr as $val){
						if(!empty($val->attr_value)){
							foreach($val->attr_value as $v){
								$attrArr[$value->course_id][] = $v->attr_value_name;
							}
						}
					}
				}
			}
			
			//老师信息
			if(!empty($adminIdArr)){
				$adminIds = implode(',', $adminIdArr);
				$teacherParams = [
					'q' => ['teacher_id'=>$adminIds],
					'f' => ['teacher_id','real_name','name']
				];
				$seekTeacher = seek_api::seekTeacher($teacherParams);
				if(!empty($seekTeacher->data)){
					foreach($seekTeacher->data as $val){
						$teacherInfo[$val->teacher_id] = $val;
						unset($val->teacher_id);
					}
				}
			}
			
			//拼装数据
			$http = utility_net::isHTTPS() ? 'https://' : 'http://';
			foreach($seekCourse->data as $val){
				$returnData['courseInfo']['list'][] = [
					"courseId"      => $val->course_id,
					"courseName"    => $val->title,
					"imgUrl"        => interface_func::imgUrl($val->thumb_med),
					"price"         => sprintf("%.2f",$val->price/100),
					"isLive"        => ($val->course_type==1) ? 1 : 0,
					//默认取第一班(有问题)
					"className"     => !empty($className[$val->course_id]) ? $className[$val->course_id] : '',
					"thirdCateName" => $val->third_cate_name,
					"subject"       => !empty($attrArr[$val->course_id]) ? $attrArr[$val->course_id] : array(),
					"teacherInfo"   => !empty($teacherInfo[$val->class[0]->class_admin_id]) ? $teacherInfo[$val->class[0]->class_admin_id] : array(),
					"courseUrl"     => !empty($val->subdomain) ? $http.$val->subdomain.'/course.info.show/'.$val->course_id : ''
				];
			}
		}

		return self::returnData(0,$returnData);
	}
	
	//获取个人信息
	public function pageInfo(){
		$returnData = array();
		$userId = empty($this->params->userId)?0:intval($this->params->userId);
		$mobile = empty($this->params->mobile)?0:intval($this->params->mobile);
		if(empty($userId) && empty($mobile)) self::returnData(-4);
		//用户基本信息
		if(empty($userId)){
			$ret = user_api::getUserIdByMobile($mobile);
			if(0 === $ret->code)
				$userId = $ret->uid;
			else
				self::returnData(-4);
		}
		$userInfo = user_api::getUser($userId);
		if(!empty($userInfo->code)) self::returnData(-4);
		$returnData = array(
			'userId'        => $userInfo->uid,
			'name' 			=> $userInfo->name,
			'realName' 		=> $userInfo->real_name,
			'mobile' 		=> $userInfo->mobile,
			'avatar' 		=> $userInfo->avatar->medium,
			'email'			=> $userInfo->email,
		);
		return self::returnData(0,$returnData);
	}
}
