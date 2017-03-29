<?php
class user_courseAjax{
	private $user;
	private $orgId;
	private $params;
	private $orgInfo;
	private $orgOwner;
	private $courseId = 0;
	static  $courseInfo = array();

	public function __construct(){

		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			exit(json_encode(array('code'=>'-1','msg'=>'request error')));
		}

		$this->user = user_api::loginedUser();
		if(empty($this->user)){
			exit(json_encode(array('code'=>'-1','msg'=>'not login')));
		}

		$organization = user_organization::subdomain();
		if(empty($organization)){
			exit(json_encode(array('code'=>'-2','msg'=>'not organization')));
		}

		$this->orgInfo = user_organization::getOrgByOwner($organization->userId);
		if(!isset($this->orgInfo->oid)){
			exit(json_encode(array('code'=>'-2','msg'=>'not organization')));
		}

		$this->orgOwner = $organization->userId;
		$this->orgId    = $this->orgInfo->oid;

		$dataBody     = utility_net::getPostData();
		$this->params = SJson::decode($dataBody, true);

		$isAdmin = user_api::isAdmin($this->orgOwner, $this->user['uid']);
		$isTeacher = utility_judgeid::userid($this->user['uid'], $this->orgOwner);
		if(!$isAdmin && !$isTeacher && !$this->orgInfo->teacher_add_course){
			exit(json_encode(array('code'=>'-1','msg'=>'not authority')));
		}

		$this->courseId  = !empty($this->params['courseId']) ? (int)$this->params['courseId'] : 0;
		if($this->courseId){
			self::$courseInfo = course_api::getCourseOne($this->courseId);
			if(empty(self::$courseInfo)){
				exit(json_encode(array('code'=>'3002','msg'=>'data empty')));
			}
			if(self::$courseInfo->user_id != $this->orgOwner){
				exit(json_encode(array('code'=>'-1','msg'=>'not authority')));
			}
		}
	}

	private function setMsg($code = 1000, $msg='', $id=0){
		$result = [
			'code'    => $code,
			'message' => SLanguage::tr($msg, 'site.course')
		];

		return json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	//课程详情
	public function pageCourseInfo(){
		if(empty($this->courseId)) return $this->setMsg(1000, '参数错误');
		if(empty(self::$courseInfo)) return $this->setMsg(3002, '获取数据为空');

		//分类信息
		$orgProfile  = user_organization::orgAboutProfileInfo($this->orgOwner);
		$firstCateId = !empty($orgProfile->scopes) ? $orgProfile->scopes : 1;
		$firstCate = course_api::getCateByCidStr($firstCateId);
		$secondCate = course_api::getNodeCate(self::$courseInfo->first_cate);
		$thirdCate  = course_api::getNodeCate(self::$courseInfo->second_cate);

		$firstArr   = array();
		if(!empty($firstCate)){
			foreach($firstCate as $key=>$val){
				$firstArr[$key]['cateId'] = $val->pk_cate;
				$firstArr[$key]['name']   = SLanguage::tr($val->name_display,'course.list');
				if($val->pk_cate == self::$courseInfo->first_cate){
					$firstArr[$key]['checked']   = 1;
				}else{
					$firstArr[$key]['checked']   = 0;
				}
			}
		}

		$secondArr  = array();
		if(!empty($secondCate)){
			foreach($secondCate as $key=>$val){
				$secondArr[$key]['cateId']   = $val->pk_cate;
				$secondArr[$key]['name'] = SLanguage::tr($val->name_display,'course.list');
				if($val->pk_cate == self::$courseInfo->second_cate){
 					$secondArr[$key]['checked']   = 1;
 				}else{
 					$secondArr[$key]['checked']   = 0;
 				}
			}
		}

		$thirdArr = array();
		if(!empty($thirdCate)){
			foreach($thirdCate as $key=>$val){
 				$thirdArr[$key]['cateId']   = $val->pk_cate;
 				$thirdArr[$key]['name'] = SLanguage::tr($val->name_display,'course.list');
 				if($val->pk_cate == self::$courseInfo->third_cate){
 					$thirdArr[$key]['checked']   = 1;
 				}else{
 					$thirdArr[$key]['checked']   = 0;
 				}
			}
		}

		//科目信息
		$attrName = '';
		$attrId   = '';
		$attrReg  = course_api::getCourseAttrValueByCourseId($this->courseId);
		if(!empty($attrReg)){
			foreach($attrReg as $val){
				$attrName = $val->value_name;
				$attrId   = $val->attr_value_id;
			}
		}

		//会员信息
		$memberSetArr  = array();
		$memberSetList = user_organization::getMemberSetList($this->orgId);
		$courseMember  = user_organization::getMemberPriorityByObjectId($this->courseId, 1);

		$isMember      = 0;
		if(!empty($memberSetList)){
			$courseSetIdArr = array();
			if(!empty($courseMember)){
				foreach($courseMember as $val){
					$courseSetIdArr[] = $val->fk_member_set;
				}
			}
			foreach($memberSetList as $val){
				if(in_array($val->pk_member_set, $courseSetIdArr)){
					$memberSetArr[$val->pk_member_set]['memberId']   = $val->pk_member_set;
					$memberSetArr[$val->pk_member_set]['memberName'] = $val->title;
					$memberSetArr[$val->pk_member_set]['checked']    = 1;
					$isMember = 1;
				}else{
					$memberSetArr[$val->pk_member_set]['memberId']   = $val->pk_member_set;
					$memberSetArr[$val->pk_member_set]['memberName'] = $val->title;
					$memberSetArr[$val->pk_member_set]['checked']    = 0;
				}
			}

			$memberSetArr = array_values($memberSetArr);
		}

		//讲师信息
		$teacherInfo = array();
        $assistanInfo = array();
		utility_judgeid::checkCourseTeacher(self::$courseInfo->course_id, $this->user['uid'], $teacherRes);

		if(!empty($teacherRes)){
			foreach($teacherRes as $val){
                if($val->is_teacher == 1){
                    $teacherIdArr[] = $val->fk_user_teacher;
                }
                if($val->is_assistant == 1){
                    $assistanIdArr[] = $val->fk_user_teacher;
                }
			}

            //讲师信息
			$planTeacher = array();
			if(!empty($this->courseId)){
				$planTeacher = course_class_api::getTeacherByCourseId($this->courseId);
			}
			if(!empty($teacherIdArr)){
				$teacherIds = implode(',', $teacherIdArr);
				$teacherParams = [
					'q' => ['teacher_id'=>$teacherIds],
					'f' => ['teacher_id','real_name','thumb_med']
				];
				$teacherReg = seek_api::seekTeacher($teacherParams);
				foreach($teacherReg->data as $val){
					$teacherInfo[] = [
						'teacherId'   => $val->teacher_id,
						'teacherName' => !empty($val->real_name) ? $val->real_name : '',
						'thumbMed'    => interface_func::imgUrl($val->thumb_med),
						'hasPlan'     => (!empty($planTeacher) && in_array($val->teacher_id, $planTeacher)) ? 1 : 0
					];
				}
            }

            //助教信息
            if(!empty($assistanIdArr)){
				$assistanIds = implode(',', $assistanIdArr);
				$assistanParams = [
					'q' => ['teacher_id'=>$assistanIds],
					'f' => ['teacher_id','real_name','thumb_med']
				];
                $groupRes = user_api::getTeacherIsGroupById(array('course_id'=>$this->courseId,'teacher_id'=>$assistanIds));
				$teacherReg = seek_api::seekTeacher($assistanParams);
				foreach($teacherReg->data as $val){
					$assistanInfo[] = [
						'teacherId'   => $val->teacher_id,
						'teacherName' => !empty($val->real_name) ? $val->real_name : '',
						'thumbMed'    => interface_func::imgUrl($val->thumb_med),
                        'isGroup'     => (!empty($groupRes) && !empty($groupRes[$val->teacher_id])) ? 1 : 0 
					];
				}
            }
		}

		//标签信息
		$tagConf   = SConfig::getConfig(ROOT_CONFIG."/group.conf","group");
		$useTagReg = tag_api::getUserSelectedCourseTag(array('courseId'=>$this->courseId,'tagConf'=>$tagConf->tagCourse));
		$tagArr    = array();
		if(!empty($useTagReg->data->items)){
			foreach($useTagReg->data->items as $val){
				$tagArr[] = [
					'tagId'   => $val->pk_tag,
					'tagName' => $val->name
				];
			}
		}


		$data = [
			"type"           => self::$courseInfo->type_id,
			"userId"         => self::$courseInfo->user_id,
			"fkUserId"       => $this->user['uid'],
			"typeName"       => !empty($typeArr[self::$courseInfo->type_id]) ? $typeArr[self::$courseInfo->type_id] : '',
			"title"          => self::$courseInfo->title,
			"firstCate"      => self::$courseInfo->first_cate,
			'firstCateName'  => $firstArr,
			"secondCate"     => self::$courseInfo->second_cate,
			"secondCateName" => $secondArr,
			"thirdCate"      => self::$courseInfo->third_cate,
			"thirdCateName"  => $thirdArr,
			"attrName"       => $attrName,
			"attrId"         => $attrId,
			"feeType"        => self::$courseInfo->fee_type,
			"price"          => !empty(self::$courseInfo->fee->price) ? self::$courseInfo->fee->price : 0,
			"setIds"         => $memberSetArr,
			"teacherArr"     => $teacherInfo,
			"assistanArr"    => $assistanInfo,
			"tagArr"         => $tagArr,
			"isPromote"      => self::$courseInfo->is_promote,
			"isMember"       => $isMember
		];

		return interface_func::setData($data);
	}

	private function checkParams($options){

		if(empty($options)){
			return $this->setMsg(0, '添加失败');
		}
		if(empty($options['title'])){
			return $this->setMsg(0, '课程名称不能为空');
		}
		if(!empty($options['title']) && (!utility_tool::check_string($options['title'], 50, 1))){
			return $this->setMsg(0, '课程名称最多50个字');
		}
		if(empty($options['firstCate']) || empty($options['secondCate'])){
			return $this->setMsg(0, '请选择课程分类');
		}
		if($options['flag']==1 && empty($options['attrValueIds'])){
			return $this->setMsg(0, '请选择科目');
		}
		if(isset($options['feeType']) && $options['feeType'] == 1 && empty($options['price'])){
			return $this->setMsg(0, '请添写价格');
		}
		if(!empty($options['price']) && !is_numeric($options['price'])){
			return $this->setMsg(0, '请填写有效价格');
		}
		if(isset($options['isMember']) && $options['isMember'] == 1 && empty($options['setIds'])){
			return $this->setMsg(0, '请选择会员类型');
		}
		if(empty($options['teacherArrId'])){
			return $this->setMsg(0, '请选择老师');
		}
		if(!empty($options['teacherArrId']) && count($options['teacherArrId']) > 10){
			//return $this->setMsg(0, '老师最多10个');
		}
	}

	//添加课程
	public function pageAddCourse(){
		if(empty($this->params['type'])){
			return $this->setMsg(0, '添加失败');
		}

		if(empty($this->params['courseAddToken']) || ($this->params['courseAddToken'] != utility_session::get()['courseAddToken'])){
			//return $this->setMsg(0, '请不要重复提交');
		}

		$checkParam = $this->checkParams($this->params);
		if(!empty($checkParam)) return $checkParam;

		$courseData = [
			'type'        => (int)$this->params['type'],
			'user_id'     => $this->orgOwner,
			'title'       => strip_tags($this->params['title']),
			//防止 price 0.0
			'fee_type'    => !empty($this->params['price']) ? (int)$this->params['feeType'] : 0,
			'first_cate'  => (int)$this->params['firstCate'],
			"second_cate" => (int)$this->params['secondCate'],
			"third_cate"  => !empty($this->params['thirdCate']) ? (int)$this->params['thirdCate'] : 0,
			"price"       => $this->params['price']
		];

		if(!empty($this->params['attrValueIds'])){
			$courseData['attrValueIds'] = $this->params['attrValueIds'];
		}

		$courseId = course_api::addCourseV2($courseData);

		if($courseId){
			//添加标签
			if(!empty($this->params['tagNameArr'])){
				$tagConf = SConfig::getConfig(ROOT_CONFIG."/group.conf","group");
				$tag = array('courseId'=>$courseId, 'tagConf'=>$tagConf->tagCourse, 'tagNameArr'=>$this->params['tagNameArr']);
				org_api::addCourseTagBelongGroup($tag);
            }
			//添加会员
			if(!empty($this->params['setIds']) && $courseData['type'] != 3){
				$this->setMember($courseId,$courseData['fee_type'],$this->params['setIds'],$this->params['isMember']);
            }
            //添加老师
            $teacherIdArr = $this->params['teacherArrId'];

            if(!empty($this->params['assistanTeacherArrId'])){
                $assistanIdArr = $this->params['assistanTeacherArrId'];

                //即使老师又是助教
                $teacherAndAssistanIdArr = array_intersect($teacherIdArr, $assistanIdArr);
                foreach($teacherAndAssistanIdArr as $val){
                    $teacherData[$val] = [
                        'is_teacher' => 1,
                        'is_assistan'=> 1
                    ];
                }
                
                //只是老师
                $teacherIds = array_diff($teacherIdArr, $teacherAndAssistanIdArr);
                foreach($teacherIds as $val){
                    $teacherData[$val] = [
                        'is_teacher' => 1,
                        'is_assistan'=> 0 
                    ];
                }
                
                //只是助教
                $assistanIds = array_diff($assistanIdArr, $teacherAndAssistanIdArr);
                foreach($assistanIds as $val){
                    $teacherData[$val] = [
                        'is_teacher' => 0,
                        'is_assistan'=> 1 
                    ];
                }
            }else{
                foreach($teacherIdArr as $val){
                    $teacherData[$val] = [
                        'is_teacher' => 1,
                        'is_assistan'=> 0 
                    ];
                }
            }
            
            foreach($teacherData as $k=>$v){
				course_api::addCourseTeacher(array('courseId'=>$courseId,'teacherId'=>$k,'isTeacher'=>$v['is_teacher'],'isAssistan'=>$v['is_assistan']));
            }

			utility_session::get()['courseAddToken'] = '';
			$cookieKey = "course_".$this->orgId.'_'.$this->user['uid'];
			utility_session::get()[$cookieKey] = $courseId;
            return $this->setMsg(1, '添加成功', $courseId);
		}

		return $this->setMsg(0, '添加失败');
	}

	public function pageEditCourse(){
		if(empty($this->courseId)) return $this->setMsg(1000, '修改失败');

		$checkParam = $this->checkParams($this->params);
		if(!empty($checkParam)) return $checkParam;

		$courseId = $this->courseId;
		//课程基本信息
		$courseData = [
			'user_id'       => $this->orgOwner,
			"title"         => strip_tags($this->params['title']),
			'fee_type'      => !empty($this->params['price']) ? (int)$this->params['feeType'] : 0,
			'first_cate'    => (int)$this->params['firstCate'],
			"second_cate"   => (int)$this->params['secondCate'],
			"third_cate"    => !empty($this->params['thirdCate']) ? (int)$this->params['thirdCate'] : 0,
			"price"         => !empty($this->params['price']) ? $this->params['price'] : 0
		];

		if(isset($this->params['attrValueIds'])){
            if($courseData['fee_type'] == 0){
                $courseData['price'] = 0;
            }
			$courseData['attrValueIds'] = $this->params['attrValueIds'];
		}

		$courseUpdate = course_api::updateCourseV2($courseId, $courseData);
		if($courseUpdate){
			//修改分销课状态
			if(!empty($this->params['isPromote'])){
				$option     = '';
				$isPromote  = (int)$this->params['isPromote'];
				$feeType    = !empty($this->params['feeType']) ? (int)$this->params['feeType'] : 0;
				$feeTypeOld = self::$courseInfo->fee_type;
				if($isPromote == 1 && $feeType != $feeTypeOld){
					$promoteData['fee_type_old'] = $feeTypeOld;
					$promoteData['fee_type_new'] = $feeType;
					$option = "updateCourseFeetype";
				}elseif($isPromote == 1 && $this->params['price'] != $this->params['priceOld']){
					$promoteData['price_old'] = $this->params['priceOld'] * 100;
					$promoteData['price']     = $this->params['price'];
					$option = "updateCourseprice";
				}
				$promoteData['title']   = strip_tags($this->params['title']);
				$promoteData['subname'] = $this->orgInfo->subname;
				if(!empty($option)){
					course_resell_api::updatePromoteTypeAndSetMsg($courseId, $option, $promoteData);
				}
			}
			//修改标签
			if(!empty($this->params['tagNameArr']) && !empty($this->params['oldTagNameArr'])){
				$tagConf = SConfig::getConfig(ROOT_CONFIG."/group.conf","group");
				//API已做处理
				$tag = array('courseId'=>$courseId, 'tagConf'=>$tagConf->tagCourse, 'tagNameArr'=>$this->params['tagNameArr']);
				org_api::addCourseTagBelongGroup($tag);
				/*$tagNameArr = array_values(array_diff_assoc($this->params['tagNameArr'],$this->params['oldTagNameArr']));
				if(!empty($tagNameArr)){
					$tag = array('courseId'=>$courseId, 'tagConf'=>$tagConf->tagCourse, 'tagNameArr'=>$tagNameArr);
					org_api::addCourseTagBelongGroup($tag);
				}*/
			}
			//修改会员
			$this->setMember($courseId,$this->params['feeType'],$this->params['setIds'],$this->params['isMember']);
			/*
			if(!empty($this->params['teacherArrId']) && !empty($this->params['oldTeacherArrId'][0])){
				//删除讲师
				$oldTeacherArr = explode(',', $this->params['oldTeacherArrId'][0]);
				foreach($oldTeacherArr as $val){
					course_api::delCourseTeacher(array('courseId'=>$this->courseId,'teacherId'=>$val));
				}
				//添加讲师
				foreach($this->params['teacherArrId'] as $val){
					course_api::addCourseTeacher(array('courseId'=>$courseId,'teacherId'=>$val));
				}
			}
			*/
			if(!empty($this->params['teacherArrId'])){
				//删除讲师
                course_api::delTeacherByCourseId($this->courseId);
                
                $teacherIdArr = $this->params['teacherArrId'];
                if(!empty($this->params['assistanTeacherArrId'])){
                    $assistanIdArr = $this->params['assistanTeacherArrId'];

                    //即使老师又是助教
                    $teacherAndAssistanIdArr = array_intersect($teacherIdArr, $assistanIdArr);
                    foreach($teacherAndAssistanIdArr as $val){
                        $teacherData[$val] = [
                            'is_teacher' => 1,
                            'is_assistan'=> 1
                        ];
                    }
                
                    //只是老师
                    $teacherIds = array_diff($teacherIdArr, $teacherAndAssistanIdArr);
                    foreach($teacherIds as $val){
                        $teacherData[$val] = [
                            'is_teacher' => 1,
                            'is_assistan'=> 0 
                        ];
                    }
                
                    //只是助教
                    $assistanIds = array_diff($assistanIdArr, $teacherAndAssistanIdArr);
                    foreach($assistanIds as $val){
                        $teacherData[$val] = [
                            'is_teacher' => 0,
                            'is_assistan'=> 1 
                        ];
                    }
                }else{
                    foreach($teacherIdArr as $val){
                        $teacherData[$val] = [
                            'is_teacher' => 1,
                            'is_assistan'=> 0 
                        ];
                    }
                }
            
				//添加讲师
                foreach($teacherData as $k=>$v){
				    course_api::addCourseTeacher(array('courseId'=>$courseId,'teacherId'=>$k,'isTeacher'=>$v['is_teacher'],'isAssistan'=>$v['is_assistan']));
                }
			}
			return $this->setMsg(1, '修改成功');
		}

		return $this->setMsg(0, '修改失败');
	}

	//设置会员
	private function setMember($courseId, $feeType, $setIds, $isMember){
		$result = false;
		if($feeType == 1){
			if($isMember == 1 && !empty($setIds)){
				$setData = array();
				$memberSetList = user_organization::getMemberSetList($this->orgId);
				foreach($memberSetList as $val){
					if(in_array($val->pk_member_set,$setIds)){
						$setData[$val->pk_member_set]['status'] = $val->status;
						$setData[$val->pk_member_set]['setId']  = $val->pk_member_set;
					}
				}
				if(!empty($setData)){
					$result = user_organization::updateMemberPriorityByObjectId($courseId, 1, $setData);
				}
			}else{
				$result = user_organization::delMemberPriorityByObjectId($courseId, 1);
			}
		}else{
			$result = user_organization::delMemberPriorityByObjectId($courseId, 1);
		}
		return $result;
	}

	//删除标签
	public function pageDelCourseTag(){
		$tagConf  = SConfig::getConfig(ROOT_CONFIG."/group.conf","group");
		$groupId  = $tagConf->tagCourse;
		$tagId    = !empty($this->params['tagId']) ? (int)$this->params['tagId'] : 0;

		$reg = user_organization::delCourseTag($this->courseId, $groupId, array($tagId));

		if($reg) return $this->setMsg(0, '操作成功');

		return $this->setMsg(1, '操作失败');
	}

	//机构下讲师列表
	public function pageOrgTeacher(){
		$page     = !empty($this->params['page']) ? (int)$this->params['page'] : 1;
		$length   = !empty($this->params['length'])? (int)$this->params['length'] : 10;
		$keywords = !empty($this->params['keywords']) ? trim($this->params['keywords']) : '';
        $copyType = !empty($this->params['copyType']) ? (int)$this->params['copyType'] : 0;
        //1 老师 2 助教
        $type     = !empty($this->params['teacherType']) ? (int)$this->params['teacherType'] : 1;
		$data 	  = user_api::getorgTeacherData($this->orgId, $page, $length, $keywords);

		$teacherIdArr = array();
		if(!empty($this->params['teacherIds'])){
			$teacherIdArr = explode(',', $this->params['teacherIds']);
		}
		$planTeacher = array();
		if(!empty($this->courseId)){
			$planTeacher = course_class_api::getTeacherByCourseId($this->courseId);
		}
		if(!empty($data['teachers'])){
			foreach($data['teachers'] as $val){
				if(!empty($teacherIdArr) && in_array($val['teacherId'], $teacherIdArr)){
					$data['checkedTeachers'][$val['teacherId']] = $val;
                    if($type == 1){
                        if($copyType == 1){
						    $data['checkedTeachers'][$val['teacherId']]['hasPlan'] = 0;
					    }elseif(!empty($planTeacher) && in_array($val['teacherId'], $planTeacher)){
						    $data['checkedTeachers'][$val['teacherId']]['hasPlan'] = 1;
					    }else{
						    $data['checkedTeachers'][$val['teacherId']]['hasPlan'] = 0;
					    }
                    }else{
                        $groupRes = user_api::getTeacherIsGroupById(array('course_id'=>$this->courseId,'teacher_id'=>$this->params['teacherIds']));
                        if($copyType == 1){
						    $data['checkedTeachers'][$val['teacherId']]['isGroup'] = 0;
					    }elseif(!empty($isGroup) && !empty($isGroup[$val['teacherId']])){
						    $data['checkedTeachers'][$val['teacherId']]['isGroup'] = 1;
					    }else{
						    $data['checkedTeachers'][$val['teacherId']]['isGroup'] = 0;
					    }
                    }
				}
			}
			if(!empty($data['checkedTeachers'])) $data['checkedTeachers'] = array_values($data['checkedTeachers']);

			return interface_func::setData($data);
		}

		return $this->setMsg(3002, '获取数据为空');
	}

	//分类信息
	public function pageGetCate(){
		$cateId = isset($this->params['cateId']) ? (int)$this->params['cateId'] : 0;
		if(empty($cateId)){
			$orgProfile = user_organization::orgAboutProfileInfo($this->orgOwner);
			$firstCate  = !empty($orgProfile->scopes) ? $orgProfile->scopes : 1;
			$cateReg = course_api::getCateByCidStr($firstCate);
		}else{
			$cateReg = course_api::getNodeCate($cateId);
		}

		if(empty($cateReg)) return $this->setMsg(3002, '获取数据为空');
		foreach($cateReg as $val){
			$data['cateList'][] = [
				'cateId' => $val->pk_cate,
				'name'   => SLanguage::tr($val->name_display,'course.list')
			];
		}

		return interface_func::setData($data);
	}

	//科目信息
	public function pageGetAttr(){
		$cateId = !empty($this->params['cateId']) ? (int)$this->params['cateId'] : 0;
		$attrReg = course_api::getAttrAndValueBycateId($cateId);
		if(empty($attrReg)) return $this->setMsg(3002, '获取数据为空');

		$data = array();
		foreach($attrReg as $val){
			if(!empty($val->attr_value)){
				foreach($val->attr_value as $v){
					$data[] = [
						'attrId' => $v->attr_value_id,
						'name'   => $v->value_name
					];
				}
			}
		}

		return interface_func::setData(['attr'=>$data]);
	}

	//标签信息
	public function pageGetTag(){
		if(empty($this->courseId)) return $this->setMsg(1000, '请求格式错误');

		$courseId = $this->courseId;
		$tagReg = tag_api::getRecentTag(array('oid'=>$this->orgId));
		if(empty($tagReg->data)) return $this->setMsg(3002, '获取数据为空');

		//使用的标签
		$tagConf   = SConfig::getConfig(ROOT_CONFIG."/group.conf","group");
		$useTagReg = tag_api::getUserSelectedCourseTag(array('courseId'=>$courseId,'tagConf'=>$tagConf->tagCourse));
		$selected = array();
		if(!empty($useTagReg->data->items)){
			foreach($useTagReg->data->items as $val){
				$selected[] = $val->pk_tag;
			}
		}

		//最近使用标签
		$data['often'] = array();
		if(!empty($tagReg->data->often)){
			foreach($tagReg->data->often as $val){
				if(in_array($val->fk_tag ,$selected)){
					$data['often'][$val->fk_tag]['selected'] = 1;
					$data['often'][$val->fk_tag]['tagId']    = $val->fk_tag;
					$data['often'][$val->fk_tag]['tagName']  = strip_tags($val->name);
				}else{
					$data['often'][$val->fk_tag]['selected'] = 0;
					$data['often'][$val->fk_tag]['tagId']    = $val->fk_tag;
					$data['often'][$val->fk_tag]['tagName']  = strip_tags($val->name);
				}
			}
			$data['often'] = array_values($data['often']);
		}

		//常用标签
		$data['lasted'] = array();
		if(!empty($tagReg->data->lasted)){
			foreach($tagReg->data->lasted as $val){
				if(in_array($val->fk_tag ,$selected)){
					$data['lasted'][$val->fk_tag]['selected'] = 1;
					$data['lasted'][$val->fk_tag]['tagId']    = $val->fk_tag;
					$data['lasted'][$val->fk_tag]['tagName']  = strip_tags($val->name);
				}else{
					$data['lasted'][$val->fk_tag]['selected'] = 0;
					$data['lasted'][$val->fk_tag]['tagId']    = $val->fk_tag;
					$data['lasted'][$val->fk_tag]['tagName']  = strip_tags($val->name);
				}
			}
			$data['lasted'] = array_values($data['lasted']);
		}

		return interface_func::setData($data);
	}

	//会员类型列表
	public function pageOrgMemberSet(){
		$result = user_organization::getMemberSetList($this->orgId);
		if(empty($result)) return $this->setMsg(3002, '获取数据为空');

		foreach($result as $val){
			$data['memberSet'][] = [
				'memberId' => $val->pk_member_set,
				'title'    => $val->title
			];
		}

		return interface_func::setData($data);
	}

	public function pageSetCourseDetail(){

		$data = [
			'scope'    => !empty($this->params['scope']) ? strip_tags($this->params['scope']) : '',
			'descript' => !empty($this->params['descript']) ? $this->params['descript'] : ''
		];

		if(!empty($this->params['w']) && !empty($this->params['h'])){
			$x  = isset($this->params['x']) ? $this->params['x'] : 0;
			$y  = isset($this->params['y']) ? $this->params['y'] : 0;
			$w  = $this->params['w'];
			$h  = $this->params['h'];

			$smaSize = "240,135";
			$medSize = "480,270";
			$bigSize = "";
			$r = user_thumb::tailorPic($this->user['uid'], 'course', $smaSize, $medSize, $bigSize, $w, $h, $x, $y);

			if(!empty($r['data'])){
				$data['thumbBig'] = $r['data']['large'];
				$data['thumbMed'] = $r['data']['medium'];
				$data['thumbSmal'] = $r['data']['small'];
			}
		}

		course_api::setCourseImg($this->courseId, $data);
		$res = course_api::getCourseOne($this->courseId);
		if(empty($res->thumb_big) || empty($res->thumb_med)){
			return $this->setMsg(1, '没有上传图片');
		}
		return $this->setMsg(0, '操作成功');
	}

	//设置图片
	public function pageSetCourseImg(){

		$x  = isset($this->params['x']) ? $this->params['x'] : 0;
		$y  = isset($this->params['y']) ? $this->params['y'] : 0;
		$w  = !empty($this->params['w']) ? $this->params['w'] : 0;
		$h  = !empty($this->params['h']) ? $this->params['h'] : 0;

		$smaSize = "240,135";
		$medSize = "480,270";
		$bigSize = "";
		$r = user_thumb::tailorPic($this->user['uid'], 'course', $smaSize, $medSize, $bigSize, $w, $h, $x, $y);

		if(!empty($r['data'])){
			$data = [
				'thumbBig'  => $r['data']['large'],
				'thumbMed'  => $r['data']['medium'],
				'thumbSmal' => $r['data']['small']
			];
			if(course_api::setCourseImg($this->courseId, $data)){
				return $this->setMsg(0, '操作成功');
			}
		}

		return $this->setMsg(1, '操作失败');
	}

	//课程简介
	public function pageSetCourseDesc(){

		$data['scope']    = !empty($this->params['scope']) ? strip_tags($this->params['scope']) : '';
		$data['descript'] = !empty($this->params['descript']) ? $this->params['descript'] : '';

		if(course_api::setCourseImg($this->courseId, $data)){
			return $this->setMsg(0, '操作成功');
		}

		return $this->setMsg(1, '操作失败');
	}

	//机构最大课程id
	public function pageGetMaxCourseId(){
		$courseType = !empty($this->params['courseType']) ? (int)$this->params['courseType'] : 0;
		if(empty($courseType)) return $this->setMsg(1000, '参数错误');

		$courseId   = course_api::getCourseMaxId($this->orgOwner,$courseType);
		if($courseId){
			return $courseId;
		}
		return 0;
	}

}
?>
