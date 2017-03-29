<?php
class user_planAjax{
	private $user;
	private $orgId;
	private $params;
	private $orgInfo;
	private $orgOwner;
	private $courseId;
	private $classId;
	static  $courseInfo = array();
	static  $courseTeacher = array();

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

		$this->courseId = !empty($this->params['courseId']) ? (int)$this->params['courseId'] : 0;
		$this->classId  = !empty($this->params['classId']) ? (int)$this->params['classId'] : 0;

		$this->isAdmin = user_api::isAdmin($this->orgOwner, $this->user['uid']);
		$this->teacher = utility_judgeid::checkCourseTeacher($this->courseId, $this->user['uid'], $teacherRes);

		$isTeacher = utility_judgeid::userid($this->user['uid'], $this->orgOwner);
		if(!$this->isAdmin && !$isTeacher && !$this->orgInfo->teacher_add_course){
			exit(json_encode(array('code'=>'-1','msg'=>'not authority')));
		}

		self::$courseTeacher = $teacherRes;

		self::$courseInfo = course_api::getCourseOne($this->courseId);
		if(empty(self::$courseInfo)){
			exit(json_encode(array('code'=>'-1','msg'=>'data error')));
		}
		if(self::$courseInfo->user_id != $this->orgOwner){
			exit(json_encode(array('code'=>'-1','msg'=>'not authority')));
		}
	}

	private function setMsg($code = 1000, $msg='', $classId=0){
		$result = [
			'code'    => $code,
			'message' => SLanguage::tr($msg, 'site.course'),
			'classId' => $classId
		];

		return json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	public function pagePlanInfo(){

		if($this->isAdmin){
			$authority = 2;
		}elseif($this->teacher){
			$authority = 1;
		}

		$classReg   = course_api::getClass($this->classId);
		if(empty($classReg->class_id)) return $this->setMsg(3002, '获取数据失败');

		//老师信息
		$teacherRes = self::$courseTeacher;
        $teacherArr = array();
        $assistantArr = array();
		if(!empty($teacherRes)){
			foreach($teacherRes as $val){
				$teacherIdArr[$val->fk_user_teacher] = $val->fk_user_teacher;
                if($val->is_assistant == 1){
                    $assistantArr[$val->fk_user_teacher] = $val->fk_user_teacher;
                }
			}
			$teacherIds = implode(',', $teacherIdArr);
			$teacherParams = [
				'q' => ['teacher_id'=>$teacherIds],
				'f' => ['teacher_id','real_name']
			];
			$seekTeacher = seek_api::seekTeacher($teacherParams);
			if(!empty($seekTeacher->data)){
				foreach($seekTeacher->data as $val){
					$teacherArr[$val->teacher_id] = $val->real_name;
				}
			}
		}

		//排课信息
        $planReg = course_plan_api::getPlanListByClassId($this->classId);

		if(!empty($planReg)){
			foreach($planReg as $val){

				$plan['section'][$val->plan_id] = [
	                "sectionName" => $val->sectionName,
	                'sectiondesc' => $val->section_name,
	                'orderNo'     => $val->order_no,
	                'planId'      => $val->plan_id,
	                'teacherId'   => $val->user_plan_id,
	                'teacher'     => '',
	                'startTime'   => '',
	                'endTime'     => '',
	                'livePublicType' => $val->live_public_type,
	                'videoPublicType'=> $val->video_public_type,
	                'videoTrialTime' => $val->video_trial_time,
	                'button'      => '',
	                'button1'     => '',
	                'button2'     => '',
	                'button3'     => '',
	                'authority'   => $authority,
	            ];

				if(($val->start_time != "0000-00-00 00:00:00" || $val->end_time != "0000-00-00 00:00:00") && self::$courseInfo->type_id !=2){
					$plan['section'][$val->plan_id]['startTime'] = date("Y-m-d H:i",strtotime($val->start_time));
					$plan['section'][$val->plan_id]['endTime'] = date("Y-m-d H:i",strtotime($val->end_time));
				}

				if(!empty($teacherArr)){
					$i = 0;
					foreach($teacherArr as $k=>$v){
						if($k == $val->user_plan_id){
							$plan['section'][$val->plan_id]['teacher'] = $v;
						}
						$i++;
					}
				}

				if($this->isAdmin){
					if(self::$courseInfo->type_id == 1){
						if($val->status == 1){
							$plan['section'][$val->plan_id]['button1'] = ['name'=>'上传视频','url'=>"/video.point.videoUpload"."/".$val->plan_id."/{$authority}"];
							$plan['section'][$val->plan_id]['button3'] = ['name'=>'备课','url'=>"/teacher.manage.plan"."/".$val->plan_id];
						}elseif($val->status == 2){
							$plan['section'][$val->plan_id]['button1'] = ['name'=>'上传视频','url'=>"/video.point.videoUpload"."/".$val->plan_id."/{$authority}"];
						}elseif($val->status == 3){
							$plan['section'][$val->plan_id]['button1'] = ['name'=>'视频管理','url'=>"/video.point.videoPart"."/".$val->plan_id."/{$authority}"];
						}
						if($this->isAdmin || ($this->teacher && $this->orgInfo->teacher_add_course)){
							$plan['section'][$val->plan_id]['button2'] = ['name'=>'巡课管理','url'=>"/course.plan.start"."/".$val->plan_id];
						}
					}elseif(self::$courseInfo->type_id == 2){
						if($val->status == 1){
							$plan['section'][$val->plan_id]['button1'] = ['name'=>'上传视频','url'=>"/video.point.videoUpload"."/".$val->plan_id."/{$authority}"];
						}elseif($val->status == 3){
							$plan['section'][$val->plan_id]['button1'] = ['name'=>'视频管理','url'=>"/video.point.videoPart/".$val->plan_id."/{$authority}"];
						}
					}
				}elseif($this->teacher && $val->user_plan_id==$this->user['uid']){
					if(self::$courseInfo->type_id == 1){
						if($val->status == 1){
							$plan['section'][$val->plan_id]['button1'] = ['name'=>'上传视频','url'=>"/video.point.videoUpload"."/".$val->plan_id."/{$authority}"];
							$plan['section'][$val->plan_id]['button3'] = ['name'=>'备课','url'=>"/teacher.manage.plan"."/".$val->plan_id];
						}elseif($val->status == 2){
							$plan['section'][$val->plan_id]['button1'] = ['name'=>'上传视频','url'=>"/video.point.videoUpload"."/".$val->plan_id."/{$authority}"];
						}elseif($val->status == 3){
							$plan['section'][$val->plan_id]['button1'] = ['name'=>'视频管理','url'=>"/video.point.videoPart"."/".$val->plan_id."/{$authority}"];
						}
					}elseif(self::$courseInfo->type_id == 2){
						if($val->status == 1){
							$plan['section'][$val->plan_id]['button1'] = ['name'=>'上传视频','url'=>"/video.point.videoUpload"."/".$val->plan_id."/{$authority}"];
						}elseif($val->status == 3){
							$plan['section'][$val->plan_id]['button1'] = ['name'=>'视频管理','url'=>"/video.point.videoPart/".$val->plan_id."/{$authority}"];
						}
                    }
                }
                if(self::$courseInfo->type_id == 1 && !empty($assistantArr)){
					$plan['section'][$val->plan_id]['button2'] = ['name'=>'巡课管理','url'=>"/course.plan.start"."/".$val->plan_id];
                }
                if(self::$courseInfo->type_id == 1 && $val->status == 3){
					$plan['section'][$val->plan_id]['button3'] = ['name'=>'下载','url'=>"javascript:;"];
                }
			}
		    $data['section'] = array_values($plan['section']);
		}

		//班级信息
		$data['class'] = [
			'id'      => $classReg->class_id,
			'name'    => $classReg->name,
			'maxUser' => $classReg->max_user
		];
		$j=0;
		$classTeacher = '';
		if(!empty($teacherArr)){
			foreach($teacherArr as $key=>$val){
				if($key == $classReg->user_class_id){
					$data['classTeacher'][$j]['checked'] = 1;
					$classTeacher = $val;
				}else{
					$data['classTeacher'][$j]['checked'] = 0;
				}
				$data['classTeacher'][$j]['teacherId']   = $key;
				$data['classTeacher'][$j]['teacherName'] = $val;
				$j++;
			}
		}

		if(self::$courseInfo->type_id == 3){
			$regionLevel0 = region_api::listRegion(0);
			foreach($regionLevel0 as $key=>$val){
				$data['province'][$key]['regionId'] = $val->region_id;
				$data['province'][$key]['name']     = SLanguage::tr($val->name,'course.list');
				if($val->region_id == $classReg->region_level0){
					$data['province'][$key]['checked'] = 1;
				}else{
					$data['province'][$key]['checked'] = 0;
				}
			}

			if(!empty($classReg->region_level0)){
				$regionLevel1 = region_api::listRegion($classReg->region_level0);
				if(!$regionLevel1) {
					$data['city'] = array();
				}else{
					foreach($regionLevel1 as $key=>$val){
						$data['city'][$key]['regionId'] = $val->region_id;
						$data['city'][$key]['name']     = SLanguage::tr($val->name,'course.list');
						if($val->region_id == $classReg->region_level1){
							$data['city'][$key]['checked'] = 1;
						}else{
							$data['city'][$key]['checked'] = 0;
						}
					}
				}
			}

			if(!empty($classReg->region_level1)){
				$regionLevel2 = region_api::listRegion($classReg->region_level1);
				if(!$regionLevel2) {
					$data['area'] = array();
				}else{
					foreach($regionLevel2 as $key=>$val){
						$data['area'][$key]['regionId'] = $val->region_id;
						$data['area'][$key]['name']     = SLanguage::tr($val->name,'course.list');
						if($val->region_id == $classReg->region_level2){
							$data['area'][$key]['checked'] = 1;
						}else{
							$data['area'][$key]['checked'] = 0;
						}
					}
				}
			}

			$data['address'] = $classReg->address;
		}

		$data['top'] = [
			'teacher' => $classTeacher,
			'tutor'   => '',
			'userNum' => $classReg->user_total.'/'.$classReg->max_user
		];
		$data['type'] = self::$courseInfo->type_id;

		return interface_func::setData($data);
	}

	public function pageSetPlan(){
		//班级信息
		if(empty($this->params['className'])){
			return $this->setMsg(1000, '请填写班级名称');
		}
		if(empty($this->params['classTeacher'])){
			return $this->setMsg(1000, '请选择班主任');
		}
		if(empty($this->params['classNum']) && self::$courseInfo->type_id != 2){
			return $this->setMsg(1000, '请填写班级人数');
		}

		$classData = [
			'name'          => trim($this->params['className']),
			'user_class_id' => (int)$this->params['classTeacher'],
			'user_id'       => $this->orgOwner,
			'courseId'      => $this->courseId
		];

		if(self::$courseInfo->type_id == 1 || self::$courseInfo->type_id == 3){
			if(empty($this->params['classNum']) && !is_numeric($this->params['classNum'])){
				return $this->setMsg(1000, '请填写班级人数');
			}
			$classData['max_user'] = (int)$this->params['classNum'];
		}else{
			$classData['max_user'] = 50000000;
		}

		if(self::$courseInfo->type_id == 3){
			if(empty($this->params['regLevel0']) || empty($this->params['regLevel1']) || empty($this->params['address'])){
				return $this->setMsg(1000, '请填写地址');
			}
			$classData['region_level0'] = (int)$this->params['regLevel0'];
			$classData['region_level1'] = (int)$this->params['regLevel1'];
			$classData['region_level2'] = !empty($this->params['regLevel2']) ? (int)$this->params['regLevel2'] : 0;
			$classData['address']   = strip_tags($this->params['address']);
		}

		if(empty($this->classId)){
			$this->classId = course_api::addClass($this->courseId, $classData);
			if(empty($this->classId)) return $this->setMsg('1', '创建班级失败');
		}else{
			$classres = course_api::updateClass($this->classId, $classData);
			if(!$classres) return $this->setMsg('1', '操作失败');
		}

        $res = true;

		//排课信息
		if(!empty($this->params['data'])){
            $addPlanData = array();
            $setPlanData = array();

            foreach($this->params['data'] as $key=>$val){
                if(empty($val['name'])){
                    return $this->setMsg('1002', '请填写章节名称', $this->classId);
                }
                if(empty($val['teacherId'])){
                    return $this->setMsg('1003', '请选择老师', $this->classId);
                }
                if(self::$courseInfo->type_id == 1 || self::$courseInfo->type_id == 3){
                    if(empty($val['startTime']) || empty($val['endTime'])){
                        return $this->setMsg('1004', '请选择时间');
                    }
                    if(strtotime($val['startTime']) > strtotime($val['endTime'])){
                        return $this->setMsg('1004', '开始时间不能大于结束时间', $this->classId);
                    }
                }

                if(empty($val['planId'])){
                    $addPlanData[$key] = [
                        'fk_user_plan' => $val['teacherId'],
                        'order_no'     => $val['order_on'],
                        'name'         => $val['name'],
                        'start_time'   => $val['startTime'],
                        'end_time'     => $val['endTime'],
                        'fk_user'      => $this->orgOwner,
                        'fk_class'     => $this->classId,
                        'fk_course'    => $this->courseId,
                        'status'       => 1,
                        'create_time'  => date('Y-m-d H:i:s'),
                    ];
                    if(!empty($val['livePublicType'])){
                        $addPlanData[$key]['live_public_type'] = $val['livePublicType'];
                    }
                    if(!empty($val['videoPublicType'])){
                        $addPlanData[$key]['video_public_type'] = $val['videoPublicType'];
                    }
                    if(!empty($val['videoTrialTime'])){
                        $addplanData[$key]['video_trial_time'] = $val['videoTrialTime'];
                    }
                }else{
                    $setPlanData['fk_user_plan'][$val['planId']] = $val['teacherId'];
                    $setPlanData['order_no'][$val['planId']]   = $val['order_on'];
                    $setPlanData['name'][$val['planId']]       = addslashes($val['name']);
                    $setPlanData['start_time'][$val['planId']] = $val['startTime'];
                    $setPlanData['end_time'][$val['planId']]   = $val['endTime'];
                    $setPlanData['live_public_type'][$val['planId']]  = !empty($val['livePublicType']) ? $val['livePublicType'] : 0;
                    $setPlanData['video_public_type'][$val['planId']] = !empty($val['videoPublicType']) ? $val['videoPublicType'] : 0;
                    $setPlanData['video_trial_time'][$val['planId']]  = !empty($val['videoTrialTime']) ? $val['videoTrialTime'] : 0;
                    /*
                    if(!empty($val['livePublicType'])){
                        $setPlanData['live_public_type'][$val['planId']]  = $val['livePublicType'];
                    }
                    if(!empty($val['videoPublicType'])){
                        $setPlanData['video_public_type'][$val['planId']] = $val['videoPublicType'];
                    }
                    if(!empty($val['videoTrialTime'])){
                        $setPlanData['video_trial_time'][$val['planId']]  = $val['videoTrialTime'];
                    }
                     */
                }
            }

            if(!empty($addPlanData)){
                $addPlanData = array_values($addPlanData);
                $res = course_plan_api::batchAddPlan($this->courseId, $this->classId, $addPlanData);
            }
            if(!empty($setPlanData)){
                $res = course_plan_api::batchSetPlan($setPlanData);
            }
		}

		//修改课程开始结束时间
		if(!empty($startTime) && !empty($endTime)){
			sort($startTime);
			sort($endTime);
			$courseParam = array('start_time'=>$startTime[0],'end_time'=>$endTime[count($endTime)-1]);
			course_api::setCourse($this->courseId, $courseParam);
		}

		if($res){
			return $this->setMsg('0', '操作成功', $this->classId);
		}

		return $this->setMsg('1', '操作失败', $this->classId);
	}

	//快速排课
	public function pageQuicksetCourse(){

		if(empty($this->courseId) || empty($this->classId)){
			return $this->setMsg(1000, '请完善班级信息');
		}

		$typeArr   = array(1,2,3);//1 每天 2 每周 3 自定义
		$weekType  = (!empty($this->params['weekType']) && in_array($this->params['weekType'],$typeArr)) ? (int)$this->params['weekType'] : 0;
		$startTime = !empty($this->params['startTime']) ? strtotime($this->params['startTime']) : '';
		$teacherId = !empty($this->params['teacherId']) ? (int)$this->params['teacherId'] : 0;
		$longType  = !empty($this->params['longType']) ? (int)$this->params['longType'] : 0;
		//1:0.5小时, 2:1小时, 3:1.5小时, 4:2小时, 5:2.5小时, 6:3小时, 7:自定义
		$longArr   = array(1=>1800,2=>3600,3=>5400,4=>7200,5=>9000,6=>10800,7=>7);

		//自定义时长
		if(!empty($longArr[$longType])){
			if($longType == 7){
				if(empty($this->params['myLongTime'])){
					return $this->setMsg(1000, '请填写自定义时间');
				}
				$longTime = $this->params['myLongTime'] * 60;
			}else{
				$longTime = $longArr[$longType];
			}
		}

		//自定义循环方式
		if($weekType == 3){
			if(!isset($this->params['myTimes']) && empty($this->params['myTimes'])){
				return $this->setMsg(1000, '请填写自定义时间');
			}
			$bySelfArr = explode(',', $this->params['myTimes']);
		}

		if(empty($teacherId)){
			return $this->setMsg(1000, '请选择讲师');
		}
		if(empty($this->params['data'])){
			return $this->setMsg(1000, '请完善课时信息');
		}

		//非录播课
		if(self::$courseInfo->type_id != 2){
			if(empty($startTime)){
				return $this->setMsg(1000, '请填写开课时间');
			}
			if(empty($weekType)){
				return $this->setMsg(1000, '请填写循环方式');
			}
			if(empty($longTime)){
				return $this->setMsg(1000, '请填写时长');
			}
		}


		if(self::$courseInfo->type_id == 2){
			/*暂时去掉(不要删除)
			if(!isset($this->params['videoPublicType']) || !isset($this->params['videoTrialTime'])){
				return $this->setMsg(1000, '请选择视频权限');
			}
			*/
			$videoPublicType = !empty($this->params['videoPublicType']) ? $this->params['videoPublicType'] : 0;
			$videoTrialTime  = !empty($this->params['videoTrialTime']) ? $this->params['videoTrialTime'] : 0;
		}

        //班级下排课数
        $planNum = course_plan_api::getPlanNumByClassIdArr(array($this->classId));

		//循环排课时间
		$fTime = ($weekType == 1) ? 86400 : (($weekType == 2) ? 604800 : 0);

		foreach($this->params['data'] as $key=>$val){
			if(empty($val['name'])){
				return $this->setMsg(1000, '请填写章节名称');
			}
            $planData[$key] = [
                'fk_course' => $this->courseId,
                'fk_class'  => $this->classId,
                'status'    => 1,
                'name'      => $val['name'],
                'order_no'  => ($key + $planNum[$this->classId] + 1),
                'fk_user'   => $this->orgOwner,
                'fk_user_plan' => $teacherId,
                'live_public_type' => 0,
                'video_public_type'=> 0,
                'video_trial_time' => 0
            ];
            if(self::$courseInfo->type_id == 1 || self::$courseInfo->type_id == 3){
                if($key == 0){
                    if(self::$courseInfo->fee_type == 0 && self::$courseInfo->type_id == 1){
                        $planData[$key]['live_public_type']  = 0;
                        $planData[$key]['video_public_type'] = 2;
                        $planData[$key]['video_trial_time']  = 1200;
                    }
                }
                if($weekType == 3){
                    //手动排课
                    $bySelfTime = !empty($bySelfArr[$key]) ? strtotime($bySelfArr[$key]) : 0;

                    $planData[$key]['start_time'] = !empty($bySelfTime) ? date("Y-m-d H:i:s", $bySelfTime) : '';
                    $planData[$key]['end_time']   = !empty($bySelfTime) ? date("Y-m-d H:i:s", ($bySelfTime + $longTime)) : '';
                }else{
                    //每天/每周 排课
                    $sTime = $startTime + ($fTime * $key);

                    $planData[$key]['start_time'] = date("Y-m-d H:i:s", $sTime);
                    $planData[$key]['end_time']   = date("Y-m-d H:i:s", ($sTime + $longTime));
                }

                $planDates[]    = $planData[$key]['start_time'];
                $planEndDates[] = $planData[$key]['end_time'];
            }else{
                $planData[$key]['video_public_type'] = $videoPublicType;
                $planData[$key]['video_trial_time']  = $videoTrialTime;
            }
  		}

        $res = course_plan_api::batchAddPlan($this->courseId, $this->classId, $planData);

		//修改课程开始结束时间
		if(!empty($planDates) && !empty($planEndDates)){
		  sort($planDates);
		  sort($planEndDates);
		  $courseData = array('start_time'=>$planDates[0],'end_time'=>$planEndDates[count($planEndDates)-1]);
		  course_api::setCourse($this->courseId, $courseData);
		}

		if(!$res) return $this->setMsg('1', '操作失败');

		return $this->setMsg('0', '操作成功');
	}

	//删除章节
	public function pageDelSection(){
        $planId  = !empty($this->params['planId']) ? (int)$this->params['planId'] : 0;
        $classId = !empty($this->params['classId']) ? (int)$this->params['classId'] : 0;
        if(empty($planId) || empty($classId)) return $this->setMsg(1, '操作失败');

        //删除排课
        $result = course_plan_api::delPlanById($planId);

        if($result){
            $planRes = course_plan_api::getPlanListByClassId($classId);
            if(!empty($planRes)){
                foreach($planRes as $key=>$val){
                    $planStartDates[$val->plan_id] = $val->start_time;
                    $planEndDates[$val->plan_id]   = $val->end_time;
                    $updateOrderOn['order_no'][$val->plan_id] = $key+1;
                }

                //修改排课顺序
                $res = course_plan_api::batchSetPlan($updateOrderOn);

                //修改课程时间
                sort($planStartDates);
                sort($planEndDates);
                $condition = [
                    'start_time' => $planStartDates[0],
                    'end_time'   => $planEndDates[count($planEndDates)-1]
                ];
                $res = course_api::setCourse($this->courseId, $condition);
            }

            return $this->setMsg('0', '操作成功');
        }

        return $this->setMsg('1', '操作失败');
	}

	//新增班级
	public function pageAddClass(){
		if(empty($this->courseId)) return $this->setMsg(1, '操作失败');

		$classList = course_api::getClassList($this->courseId);
		$classNum  = !empty($classList) ? count($classList) : 0;

		if(self::$courseInfo->type_id == 2 && $classNum >= 1){
			return $this->setMsg(1000, '录播课只能创建一个班');
		}
		if($classNum >= 6){
			return $this->setMsg(1000, '最多6个班级');
		}
		if(empty(self::$courseTeacher)){
			return $this->setMsg(1000, '请填写讲师');
		}

		$classData = [
			'name'          => ($classNum + 1).'班',
			'user_class_id' => self::$courseTeacher[0]->fk_user_teacher,
			'user_id'       => $this->orgOwner
		];

		$classId = course_api::addClass($this->courseId, $classData);

		if(empty($classId)){
			return $this->setMsg(1, '操作失败');
		}

		return $this->setMsg(0, '操作成功', $classId);
	}

	//删除班级
	public function pageDelClass(){

		if(empty($this->courseId) || empty($this->classId)){
			return $this->setMsg(1, '操作失败');
		}
		$res = course_api::delClass($this->classId, $this->courseId);
		if($res){
			if($res->result->code==-4){
				return $this->setMsg(1, '该班内有学生不能删除');
			}
            $planRes = course_api::getPlanByCourseId($this->courseId);
            if(!empty($planRes)){
                foreach($planRes as $val){
                    $plan_dates[$val->plan_id]     = $val->start_time;
                    $plan_end_dates[$val->plan_id] = $val->end_time;
                }
                sort($plan_dates);
                sort($plan_end_dates);
                $condition = [
                    'start_time' => $plan_dates[0],
                    'end_time'   => $plan_end_dates[count($plan_end_dates)-1]
                ];
            }
            //删除最后一个班级更新课程下假
            $classReg = course_api::getClasslist($this->courseId);

            if(!$classReg){
                course_api::setCourse($this->courseId, $condition);
            }

			return $this->setMsg(0, '操作成功');
		}

		return $this->setMsg(1, '操作失败');
	}

    public function pageUploadVideo(){
        if(empty($this->params['planId'])) return $this->setMsg(1000, '缺少必要参数');

        $planId = (int)$this->params['planId'];
        $planInfo = course_api::getPlan($planId);
        $videoRes = video_api::getVideoList($planId);
        if(empty($videoRes) || empty($planInfo))  return $this->setMsg(4000, '视频暂为生成!');

        $name = '第'.$planInfo->order_no.'课时-'.$planInfo->section_name;
        $userIp = $_SERVER['REMOTE_ADDR'];
        $time   = time();
        $conf   = SConfig::getConfig(ROOT_CONFIG."/download.conf");
        $secret = $conf->key;
        $hosts  = $conf->domain;
        foreach($videoRes as $k=>$val){
            $endName  = strstr($val->filename, '.');
            $fileName = rtrim(base64_encode('/'.$val->source_ip.substr($val->filename,10)),'=');
            $key      = md5($fileName.'-'.$userIp.'-'.$time.'-'.$secret);
            $data['data'][$k]['size'] = utility_tool::formatSizeUnits($val->filesize);
            
            /*
            if($k == 0){
                $data['data'][$k]['name']= !empty($val->filename_org) ? $val->filename_org : $name;
                $data['data'][$k]['url'] = "{$hosts}/source/{$fileName}?t={$time}&key={$key}&file={$name}{$endName}";
            }else{
                $data['data'][$k]['name']= !empty($val->filename_org) ? $val->filename_org : $k.'.'.$name;
                $data['data'][$k]['url'] = "{$hosts}/source/{$fileName}?t={$time}&key={$key}&file={$name}-{$k}{$endName}";
            }
             */
            $data['data'][$k]['name']= !empty($val->filename_org) ? $val->filename_org : ($k+1).'.'.$name;
            $data['data'][$k]['url'] = "{$hosts}/source/{$fileName}?t={$time}&key={$key}&file={$data['data'][$k]['name']}{$endName}";
        }

		return interface_func::setData($data);
    }
}
?>
