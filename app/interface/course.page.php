<?php

class interface_course extends STpl
{

    public $paramsInfo = [
		'oid'=>'',
		'u' => '',
		'v' => '',
		'time' => '',
		//'token' => '',
		'key' => '',
		'params' => [],
        'dinfo'  => []
   ];


    public function __construct()
    {
        if (!isset($_GET['debug'])) {
            $param = SJson::decode(utility_net::getPostData(), true);
            if (empty($param)) return interface_func::setMsg(1000);
            foreach ($param as $k=>$v) {
                if (isset($this->paramsInfo[$k])) {
                    $this->paramsInfo[$k] = $v;
                }
            }

            $valid = interface_func::validParam($this->paramsInfo);
            if (!empty($valid['code'])) exit( interface_func::setMsg($valid['code']) );

            $this->paramsInfo = $valid;
        }
    }

	/**
	 * TODO 这个方法有严重性能问题，必须尽快删除 by hetal 2016/3/25
	 * 后面的 API如果没有用到，也必须删除
	 */
    public function pageStudent()
    {
        if (!utility_tool::check_int($this->paramsInfo['params']['courseId'])) {
            return interface_func::setMsg(1007);
        }

        if (!utility_tool::check_int($this->paramsInfo['params']['classId'])) {
            return interface_func::setMsg(1008);
        }

        $param = [
            'courseId' => $this->paramsInfo['params']['courseId'],
            'classId' => $this->paramsInfo['params']['classId'],
            'page' => 1,
            'length' => -1
        ];

        $data = [];
        $courseUser = utility_services::call('/course/courseuser/listsByCourseClass', $param);

        if (empty($courseUser->result->items)) return interface_func::setData($data);

        $userIdArr = [];
        foreach ($courseUser->result->items as $user) {
            $userIdArr[$user->fk_user] = $user->fk_user;
        }

        $userData = [
            'userIdArr' => $userIdArr,
        ];

        $res = utility_services::call('/common/list/GetUsersInfo', $userData);

        if (!empty($res->result)) {
            foreach ($res->result as $v) {
                $data[] = [
                    'uid' => $v->pk_user,
                    'name' => !empty($v->real_name)
                        ? $v->real_name
                        : (!empty($v->name) ? $v->name : SLanguage::tr('未设置', 'message')),
                    'thumb' => !empty($v->thumb_med)
                        ? interface_func::imgUrl($v->thumb_med)
                        : '',
                    'mobile' => $v->mobile
                ];
            }
        }

        return interface_func::setData($data);
    }

      /*
       * PC端 课程列表
       * @author zhengtianlong
       */
    public function pageGetTeacherCourseList()
    {
        $page = empty($this->paramsInfo['params']['page']) ? 1 : $this->paramsInfo['params']['page'];
        $length = empty($this->paramsInfo['params']['length']) ? 20 : $this->paramsInfo['params']['length'];

        if (!utility_tool::check_int($page)) {
            return interface_func::setMsg(1007);
        }

        if (!utility_tool::check_int($length)) {
            return interface_func::setMsg(1008);
        }

        if (!utility_tool::check_int($this->paramsInfo['params']['teacherId'])) {
            return interface_func::setMsg(1009);
        }

        $param = array(
            'allcourse' => false,
            'order_by'  => 'desc',
            'page'      => $page,
            'length'    => $length,
            'user_plan_id' => $this->paramsInfo['params']['teacherId']
            //'user_plan_id' => 255
        );
        $this->assign('param',json_encode($param));
        $this->render('/interface/pc_client/course.list.html');
    }

    public function pagePlanList(){
        $teacherId = !empty($this->paramsInfo['params']['teacherId']) ? (int)$this->paramsInfo['params']['teacherId'] : 0;
        $year      = !empty($this->paramsInfo['params']['year']) ? (int)$this->paramsInfo['params']['year'] : 0;
        $month     = !empty($this->paramsInfo['params']['month']) ? (int)$this->paramsInfo['params']['month'] : 0;

        if(empty($teacherId) || empty($year) || empty($month)){
            return interface_func::setMsg(1000);
        }

        $days = ($month == 2) ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))):(($month-1) % 7 % 2 ? 30 : 31);
        $date1 = $year."-".$month;
        $date  = date("Y-m-d H:i:s",strtotime($date1));
        $startTime = date('Y-m-01 00:00:00', strtotime($date));
        $endTime   = date('Y-m-d  23:59:59', strtotime("$startTime +1 month -1 day"));

        $param = [
            'allcourse'    => true,
            'order_by'     => 'desc',
            'page'         => 1,
            'length'       => 10000,
            'type'         => 1,
            'startTime'    => "$startTime,$endTime",
            'user_plan_id' => $teacherId
        ];

        $planRes  = course_api::listPlan($param);
        $planData = array();
        $status   = array(0=>'开始上课',1=>'开始上课',2=>'继续上课',3=>'继续上课');

        if(!empty($planRes->data)){
            foreach($planRes->data as $val){
                $classIdArr[$val->class_id]   = $val->class_id;
                $courseIdArr[$val->course_id] = $val->course_id;
            }
            $classPlanNum = course_plan_api::getplannumbyclassidarr($classIdArr);
            
            for($i=1;$i<=$days;$i++){
                $planData[$i]['flag'] = 0;
                foreach($planRes->data as $k=>$v){
                    $d = date('j',strtotime($v->start_time));
                    if($d == $i){
                        $planData[$i]['flag'] = 1;
                        $planData[$i]['data'][] = [
                            'course_id'    => $v->course_id,
                            'class_id'     => $v->class_id,
                            'title'        => $v->title,
                            'class_name'   => $v->class_name,
                            'section_name' => $v->order_no,
                            'thumb'        => interface_func::imgUrl($v->thumb_med),
                            'teacher_name' => $v->user_plan->real_name,
                            'plan_id'      => $v->plan_id,
                            'status'       => (date('Y-m-d',strtotime($v->start_time)) > date('Y-m-d')) ? '未开课' : $status[$v->status],
                            'type'         => (date('Y-m-d',strtotime($v->start_time)) > date('Y-m-d')) ? '4' : $v->status,
                            'start_time'   => date('H:i',strtotime($v->start_time)),
                            'num'          => !empty($classPlanNum[$v->class_id]) ? "".$classPlanNum[$v->class_id]."" : 0,//$comm[$v->course_id],
                            'userTotal'    => $v->user_total_class,
                            'sectionDescipt'=> $v->section_descipt,
                            'course_type'  => $v->course_type,
                        ];
                    }
                }
            }
        }

        return interface_func::setData($planData);
    }
    
    /**
     * 助教直播列表
     * @param int $teacherId 助教id
     * @param int $year 
     * @param int $month
     * @return object
     */
    public function pageAssistantPlanList(){
        $year  = !empty($this->paramsInfo['params']['year']) ? (int)$this->paramsInfo['params']['year'] : 0;
        $month = !empty($this->paramsInfo['params']['month']) ? (int)$this->paramsInfo['params']['month'] : 0;
        $teacherId = !empty($this->paramsInfo['params']['teacherId']) ? (int)$this->paramsInfo['params']['teacherId'] : 0;

        if(empty($teacherId) || empty($year) || empty($month)){
            return interface_func::setMsg(1000);
        }
        
        //助教老师下的课程
        $result = teacher_api::getcoursebyteacherid(array('teacherId'=>$teacherId,'isAssistant'=>1));
        if(empty($result)) return interface_func::setMsg(3002);
        foreach($result as $val){
            $courseIdArr[] = $val->fk_course;
        }
        $courseIds = implode(',', $courseIdArr);

        $days = ($month == 2) ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))):(($month-1) % 7 % 2 ? 30 : 31);
        $date1 = $year."-".$month;
        $date  = date("Y-m-d H:i:s",strtotime($date1));
        $startTime = date('Y-m-01 00:00:00', strtotime($date));
        $endTime   = date('Y-m-d  23:59:59', strtotime("$startTime +1 month -1 day"));

        //助教老师排课信息
        $params = [
            'q' => ['course_id'=>$courseIds,'start_time'=>"$startTime,$endTime"],
            'f' => [
                'course_id','class_id','section_name','class_name','section_desc','course_thumb_med','start_time',
                'user_total','course_type','course_name','plan_id','teacher_name','teacher_real_name'
            ],
            'pl'=> 1000,
            'ob'=> ['start_time'=>'asc']
        ];
        $seekPlan = seek_api::seekPlan($params);
        if(empty($seekPlan->data)) return interface_func::setMsg(3002);

        //班级下排课数    
        $classIdArr = array();
        foreach($seekPlan->data as $val){
            $classIdArr[] = $val->class_id;
        }
        if(!empty($classIdArr)){
            $classPlanNum = course_plan_api::getplannumbyclassidarr($classIdArr);
        }

        for($i=1;$i<=$days;$i++){
            $planData[$i]['flag'] = 0;
            foreach($seekPlan->data as $k=>$v){
                $d = date('j',strtotime($v->start_time));
                if($d == $i){
                    $planData[$i]['flag'] = 1;
                    $planData[$i]['data'][] = [
                            'course_id'    => $v->course_id,
                            'class_id'     => $v->class_id,
                            'title'        => $v->course_name,
                            'class_name'   => $v->class_name,
                            'section_name' => $v->section_name,
                            'thumb'        => interface_func::imgUrl($v->course_thumb_med),
                            'teacher_name' => !empty($v->teacher_real_name) ? $v->teacher_real_name : $v->teacher_name,
                            'plan_id'      => $v->plan_id,
                            'start_time'   => date('H:i',strtotime($v->start_time)),
                            'num'          => !empty($classPlanNum[$v->class_id]) ? "".$classPlanNum[$v->class_id]."" : 0,
                            'section_desc' => $v->section_desc,
                            'course_type'  => $v->course_type,
                    ];
                }
            }
        }
        
        $data = array_values($planData);
        return interface_func::setData($data);
    }

   /*
   * 课程详情
   * @author zhengtianlong
   * $param uid 用户id courseid 课程id
   * todo 待优化
   */
    public function pageDetailV2()
    {
        $uid = !isset($this->paramsInfo['params']['uid']) ? 0 : $this->paramsInfo['params']['uid'];
		if(empty($this->paramsInfo['params']['courseId'])){
			return interface_func::setMsg(3002);
		}
		$cid = $this->paramsInfo['params']['courseId'];

		$courseParams = [
			  'q' => ['course_id'=>$cid,'admin_status'=>'1,-2'],
			  'f' => [
					'user_id','course_id','title','desc','status','fee_type','price',
					'start_time','comment','user_total','thumb_med','course_attr',
					'course_type','class_id','class','org_subname','third_cate_name','subdomain'
			  ]
        ];
        $courseData = seek_api::seekCourse($courseParams);
		if (empty($courseData)) return interface_func::setMsg(3002);

		$planParams = [
			  'q' => ['course_id'=>$cid,'status'=>'1,2,3'],
			  'f' => [
					'owner_id','section_name','section_desc','plan_id','status','admin_name','section_id',
					'try','teacher_name','video_public_type','teacher_thumb_med','start_time','class_id',
					'course_id','teacher_real_name','admin_real_name','teacher_thumb_big','class_name',
					'course_type','subdomain','admin_id','video_id','totaltime'
			  ],
			  'ob' => ['plan_id'=>'asc'],
		'pl' => 1000
		];
        $planData = seek_api::seekPlan($planParams);

		//该学生是否报名和学生所报的班
		$studentInfo = array();
		if(!empty($uid)){
			$res_user = course_api::checkIsRegistration($uid,$cid);
			if(!empty($res_user)){
				$studentInfo["userStatus"]  = 1;
				$studentInfo["signClassId"] = $res_user['classId'];
			}
		}
		$http = utility_net::isHTTPS() ? 'https://' : 'http://';
		//获取科目名称
		$temp = array();
		$attrValues = array();
		$attrCourseId = array();
		foreach($courseData->data as $value){
			if(!empty($value->course_attr)){
				foreach($value->course_attr as $val){
					if(!empty($val->attr_value)){
						foreach($val->attr_value as $v){
							if(!empty($attrId) && $v->attr_value_id == $attrId){
								$attrCourseId[] = $value->course_id;
							}
							$temp[$value->course_id][$v->attr_value_id] = $v->attr_value_name;
						}
					}
				}
			}
		}
		if(!empty($temp)){
			foreach($temp as $k=>$v){
				$attrValues[$k] = implode(",",$v);
			}
		}

		//班级信息
		$class = array();
		if(isset($courseData->data[0]->class) && !empty($courseData->data[0]->class)){
			$courseClass = $courseData->data[0];
            $region = region_geo::$region;
			foreach($courseClass->class as $val){
				if($courseClass->course_type == 3){
					$regionLevel0 = !empty($region[$val->region_level0]) ? $region[$val->region_level0] : '';
					$regionLevel1 = !empty($region[$val->region_level1]) ? $region[$val->region_level1] : '';
					$regionLevel2 = !empty($region[$val->region_level2]) ? $region[$val->region_level2] : '';
					$address[$val->class_id] = $regionLevel0.$regionLevel1.$regionLevel2.$val->address;
				}
				$class[$val->class_id] = [
					'classId'      => $val->class_id,
					'name'         => $val->name,
					'desc'         => $val->desc,
					'startTime'    => !empty($courseClass->start_time) ? date('Y年m月d日', strtotime($courseClass->start_time)) : '',
					'courseType'   => '线上课',
                    'courseTypeInt'=> $courseClass->course_type,
					'address'      => !empty($address[$val->class_id]) ? $address[$val->class_id] : ''
				];
			}
		}
		//章节信息
		if(!empty($planData->data)){
            $planIdArr = [];
			$statusName = array('normal'=>'1','living'=>2,'finished'=>3,'-1'=>'invalid','0'=>'initial');
            foreach ($planData->data as $plan) {
                $planIdArr[$plan->plan_id] = $plan->plan_id;
				$status = course_api::getPlanStatusV2($plan->plan_id);
				if(!empty($status)){
					$planStatus[$plan->plan_id] = $statusName[$status->plan_status];
				}else{
					$planStatus[$plan->plan_id] = 1;
				}
            }

            //$uploadVideoInfo = interface_planApi::getUploadList($uid, implode(',', $planIdArr));
			foreach($planData->data as $val){
				$class[$val->class_id]['teacherImage'] = interface_func::imgUrl($val->teacher_thumb_big);
				$class[$val->class_id]['classAdmin']   = $val->admin_real_name;
                $class[$val->class_id]['teacherId']   = $val->admin_id;
				$class[$val->class_id]['classTeacher'] = $val->teacher_real_name;
                $class[$val->class_id]['section'][] = [
                    "planId"          => $val->plan_id,
                    "status"          => !empty($planStatus[$val->plan_id]) ? (int)$planStatus[$val->plan_id] : 1,
                    "videoPublicType" => $val->try,
                    "sectionId"       => $val->section_id,
                    "name"            => $val->section_name,
                    "desc"            => $val->section_desc,
                    "startTime"       => !empty($val->start_time) ? date('Y年m月d日', strtotime($val->start_time)) : '',
                    "playUrl"         => !empty($val->subdomain) ? $http.user_organization::course_domain($val->subdomain).'/course.plan.play/'.$val->plan_id : '',
                    'downUrl'         => interface_planApi::getPlanDownLoadUrl($val->plan_id),
                    //'duration'        => !empty($uploadVideoInfo[$val->plan_id]['duration']) ? $uploadVideoInfo[$val->plan_id]['duration'] : 0,
                    'duration'        => utility_tool::sec2time($val->totaltime),
                    //'size'            => !empty($uploadVideoInfo[$val->plan_id]['size']) ? $uploadVideoInfo[$val->plan_id]['size'] : 0,
                    'size'            => 0
                ];
			}
		}
		$class = array_values($class);
		$scoreInfo = comment_api::getOneCourseScoreInfo($cid);
		//整合数据
		$data = array();
		foreach($courseData->data as $val){
			$data = [
				"courseId"     => $val->course_id,
                'type'         => $courseData->data[0]->course_type,
				"title"        => $val->title,
				"desc"         => $val->desc,
				"userStatus"   => !empty($studentInfo["userStatus"]) ? 1 : 0,
				"signClassId"  => !empty($studentInfo["signClassId"]) ? $studentInfo["signClassId"] : 0,
				"status"       => $val->status,
				"feeType"      => $val->fee_type,
				"price"        => ($val->fee_type==0) ? 0 : $val->price/100,
				"comment"      => $val->comment,
				"userTotal"    => $val->user_total,
				"thumbMed"     => interface_func::imgUrl($val->thumb_med),
				"url"          => !empty($val->subdomain) ? $http.user_organization::course_domain($val->subdomain).'/course.info.show/'.$val->course_id : '',
				"grade"        => $val->third_cate_name,
				"cate"         => !empty($attrValues[$val->course_id]) ? $attrValues[$val->course_id] : '',
				"score"        => !empty($scoreInfo['avg_score'])?$scoreInfo['avg_score']:0,
				"satisfaction" => !empty($scoreInfo['avg_score'])?$scoreInfo['avg_score']:0,
				"conform"      => !empty($scoreInfo['avg_score'])?$scoreInfo['avg_score']:0,
				"expression"   => !empty($scoreInfo['avg_score'])?$scoreInfo['avg_score']:0,
				"courseType"   => '线上课',
                "courseTypeInt"=> $courseClass->course_type,
				"org"          => !empty($val->org_subname) ? $val->org_subname : '',
				"class"        => !empty($class) ? $class : ''
			];
		}

		return interface_func::setData($data);
    }

    public function pageCommentV2()
    {
        $page   = empty($this->paramsInfo['params']['page']) ? 1 : $this->paramsInfo['params']['page'];
        $length = empty($this->paramsInfo['params']['length']) ? 20 : $this->paramsInfo['params']['length'];
        $cid    = !empty($this->paramsInfo['params']['courseId']) ? (int)$this->paramsInfo['params']['courseId'] : 0; //= 130;
        if(empty($cid)) return interface_func::setMsg(1000);
		$result = comment_api::getCourseCommentList($cid, 0, $page,$length);  //新的评分列表 去重后的
        if (empty($result["data"])) {
            return interface_func::setMsg(3002);
        }

        $data = [
            'page'      => $page,
            'totalPage' => $result["totalPage"],
            'total'     => $result["totalSize"],
        ];
        foreach($result["data"] as $val)
        {
            $data['data'][] = [
				'planId'    => $val["planId"],
                'commentId' => $val["commentId"],
                'userId'    => $val["userId"],
                'userName'  => $val["userName"],
                'userImage' => $val["userThumb"],
                'content'   => html_entity_decode($val["comment"]),
                'score'     => $val["studentScore"],
				'studentScore' => $val["studentScore"],
				'className' => $val["className"],
				'sectionName' => $val["sectionName"],
                'time'      => $val["ymd"],
            ];
        }
        return interface_func::setData($data);
    }

    /*
     * 用户课程列表
     * @author zhengtianlong
     */
    public function pageGetUserCourse()
    {
        $page   = empty($this->paramsInfo['params']['page']) ? 1 : $this->paramsInfo['params']['page'];
        $length = empty($this->paramsInfo['params']['length']) ? 20 : $this->paramsInfo['params']['length'];
        $uid    = $this->paramsInfo['params']['uid'];
        if (empty($uid)) return interface_func::setMsg(3002);

        $param = [
            'q' => ['user_id'=>$uid,'admin_status'=>1],
            'p' => $page,
            'pl'=> $length,
            'f' => [
                'class_id',
                'class',
                'course_id',
                'title',
                'thumb_med',
                'start_time'
            ]
        ];
        $result = seek_api::seekCourse($param);
        if (empty($result->data[0])) return interface_func::setMsg(3002);
        $course = $result->data;
        $data = [
            'page'   => $result->page,
            'length' => $result->pagelength,
            'total'  => $result->total
        ];

        foreach($course as $key=>$val)
        {
            foreach($val->class as $v)
            {
                $data['data'][] = [
                    'classId'   => $v->class_id,
                    'className' => $v->name,
                    'courseId'  => $val->course_id,
                    'courseName'=> $val->title,
                    'startTime' => $val->start_time,
                    'courseType'=> '线上课程',
                    'address'   => '北京海淀'
                ];
            }
        }

       return interface_func::setData($data);
    }

    /*
     * 获取当前课程的课程名称和章节名称
     * @author zhengtianlong
     */
    public function pageGetName()
    {
        if (empty($this->paramsInfo['params']['planId'])) {
            return interface_func::setMsg(3002);
        }
        $planId = $this->paramsInfo['params']['planId'];
        $params = [
            'f' => ['course_name','section_name','class_name'],
            'q' => ['plan_id'=>$planId]
        ];

        $result  = seek_api::seekPlan($params);
        if (empty($result->data[0])) return interface_func::setData(
            [
                'courseName' => '',
                'sectionName' => '',
                'className' => ''
            ]
        );

        $data = [
            'courseName'  => $result->data[0]->course_name,
            'sectionName' => $result->data[0]->section_name,
            'className'   => $result->data[0]->class_name
        ];
        return interface_func::setData($data);
    }

    public function pageMyStudents()
    {
        if (empty($this->paramsInfo['params']['uid'])) {
            return interface_func::setMsg(1000);
        }

        $params = [
            'teacherId' => $this->paramsInfo['params']['uid'],
            'scope'     => [
                'profile' => true,
                'mobile'  => true,
                'student' => true,
                'school'  => true
            ],
            'page'      => 1,
            'length'    => 500
        ];

        $res = utility_services::call('/course/courseuser/ListsByTeacherId', $params);

        $list = [];
        if (!empty($res->result)) {
            foreach ($res->result as $v) {
                $province = !empty($v->province) ? $v->province : '';
                $city = !empty($v->city) ? $v->city : '';
                $region = $province . $city;

                if ($province == $city) {
                    $region = $province;
                }

                $list[] = [
                    'name' => !empty($v->real_name) ? $v->real_name : (!empty($v->name) ? $v->name : SLanguage::tr('未设置', 'message')),
                    'gender' => SLanguage::tr(interface_dict::$gender[$v->gender], 'site.user'),
                    'mobile' => !empty($v->mobile) ? $v->mobile : '',
                    'region' => $region,
                    'schoolName' => !empty($v->school_name) ? $v->school_name : ''
                ];
            }
        }

        $this->assign('list', $list);
        $this->render('/interface/pc_client/mystudents.html');
    }

    public function pageMyMessages()
    {

        $param['uid'] = !empty($this->paramsInfo['params']['userToId']) ? $this->paramsInfo['params']['userToId'] : '';
        $param['page'] = !empty($this->paramsInfo['params']['page']) ? $this->paramsInfo['params']['page'] : 1;
        $param['length'] = !empty($this->paramsInfo['params']['length']) ? $this->paramsInfo['params']['length'] : 20;
        //$param['uid'] = 254;
        //$param['page'] = 1;
        //$param['length'] = 20;

        $res = interface_messageApi::getDialogLastTotalList($param);

        $list = !empty($res['data']) ? $res['data'] : [];


        $this->assign('list', $list);
        $this->assign('uid', $param['uid']);
        $this->render('/interface/pc_client/mymessages.html');
    }

    /*
     * 我的课程列表
     * @param  type 课程类型 0全部 1直播课 2录播课 3线下课
     * @param  uid  用户id
     */
    public function pageMyCourses()
	{
		$page   = isset($this->paramsInfo['params']['page']) ? (int)($this->paramsInfo['params']['page']) : 1;
        $length = isset($this->paramsInfo['params']['length']) ? (int)($this->paramsInfo['params']['length']) : 20;
        $type   = isset($this->paramsInfo['params']['type']) ? (int)($this->paramsInfo['params']['type']) : 0;
        $uid    = isset($this->paramsInfo['params']['uid']) ? (int)($this->paramsInfo['params']['uid']) : 0;
		if(!$uid) return interface_func::setMsg(3002);

		$courseReg = course_api::getUserRegisterCourseList($uid,$page,$length,'','',$type);
		if(empty($courseReg->data)) return interface_func::setMsg(3002);

		//当前章节名称
		$planIdArr = array();
		foreach($courseReg->data as $val){
			$planIdArr[] = $val->progress_plan;
		}
		$sectionName = array();
		if(!empty($planIdArr)){
			$planIds = implode(',', $planIdArr);
			$params = [
				'q' => ['plan_id'=>$planIds],
				'f' => ['plan_id','section_name']
			];
			$planReg = seek_api::seekPlan($params);
			if(!empty($planReg->data)){
				foreach($planReg->data as $val){
					$sectionName[$val->plan_id] = $val->section_name;
				}
			}
		}

		$data = [
			'page'  => $courseReg->page,
			'total' => $courseReg->totalSize,
			'totalPage' => $courseReg->totalPage
		];

		//拼装数据
		foreach($courseReg->data as $val){
			$data['data'][] = [
				'type'        => $val->type,
                'courseId'    => $val->fk_course,
                'courseName'  => $val->title,
                'image'       => interface_func::imgUrl($val->thumb_med),
                'rate'        => $val->progress_percent/100,
                'sectionCount'=> $val->section_count,
                'teacher'     => !empty($val->teacher_info->real_name) ? $val->teacher_info->real_name : '',
				'alreadyStartSection' => !empty($sectionName[$val->progress_plan]) ? $sectionName[$val->progress_plan] : '未开课',
			];
		}

		return interface_func::setData($data);
    }

	//h5 课程简介
    public function pageDesc(){
        $courseId = !empty($_GET['courseId']) ? intval($_GET['courseId']) : 0;
        $userId   = !empty($_GET['uid']) ? intval($_GET['uid']) : 0;
        $resellOrgId = !empty($_GET['orgId']) ? (int)$_GET['orgId'] : 0;

        $courseRes = course_detailed::getCourseInfo($courseId);
        if(empty($courseRes)){
            return $this->render("interface/yunke/empty.html");
        }

        if(!empty($resellOrgId) && $courseRes['isPromote']) {
            $resellCourseRes = course_resell_api::getCourseResell($courseId, $resellOrgId);
            $courseRes['price'] = !empty($resellCourseRes) ? $resellCourseRes['price_resell'] / 100 : $courseRes['price'];
        }

        $userReg = interface_user_api::getUserRegCourse($userId, array($courseId));
        $classId = !empty($userReg['items']) ? array_column($userReg['items'], 'fk_class', 'fk_class') : array();

        //班级信息
        $classData = array();
        $signClass = array();
        if($courseRes['courseType'] == 3){
            foreach($courseRes['class'] as $val){
                $classData[] = [
                    'className' => $val->name,
                    'address'   => $val->region_level0.' '.$val->region_level1.' '.$val->region_level2.' '.$val->address
                ];
                if(!empty($classId[$val->class_id])){
                    $signClass[] = [
                        'className' => $val->name,
                        'address'   => $val->region_level0.' '.$val->region_level1.' '.$val->region_level2.' '.$val->address
                    ];
                }
            }
        }

        $scoreInfo=comment_api::getOneCourseScoreInfo($courseId);
        $courseRes['score'] = empty($scoreInfo['avg_score']) ? 0: $scoreInfo['avg_score'];

        $courseRes['isFree'] = 0;
        //会员信息
        if(!empty($courseRes['memberset'])){
            foreach($courseRes['memberset'] as $v){
                $setIdArr[] = $v->member_set_id;
            }
            $memberRet = org_member_api::checkIsMemberOrExpire($userId, $setIdArr, $courseId);

            $isExpire = array();
            if(!empty($memberRet['userMemberSet'])){
                $isExpire = array_column($memberRet['userMemberSet'], 'is_expire', 'fk_member_set');
                $courseRes['isFree'] = in_array(0 ,$isExpire) ? 1 : 0;
            }
            //1 过期 0 未过期
            foreach($courseRes['memberset'] as &$value){
                $value->isExpire = isset($isExpire[$value->member_set_id]) ? $isExpire[$value->member_set_id] :1;
            }
        }

        //老师信息
        $teacher = array();
        $teacherIds = course_class_api::getTeacherByCourseId(array($courseId));
        if(!empty($teacherIds)){
            //默认取第一个
            //老师详情
            $teachersInfo = user_api::getTeacherInfoByIds($teacherIds);
            if(!empty($teachersInfo)){
                foreach($teachersInfo as $teacherInfo){
                    $teacher[]= [
                        'teacherId'   => $teacherInfo->pk_user,
                        'teacherName' => $teacherInfo->real_name,
                        'thumbMed'    => interface_func::imgUrl($teacherInfo->thumb_med),
                        'teacherDesc' => $teacherInfo->desc,
                    ];
                }
            }
        }

        $this->assign('signClass', $signClass);
        $this->assign('classData', $classData);
        $this->assign('data', $courseRes);
        $this->assign('teachers', $teacher);
        $this->render("course/course.intro.html");
    }

	/*
 		课程简介和目录信息
 	*/
    public function pageDetail(){
        $orgId    = !empty($this->paramsInfo['oid']) ? (int)$this->paramsInfo['oid'] : 0;
        $courseId = !empty($this->paramsInfo['params']['courseId']) ? (int)$this->paramsInfo['params']['courseId'] : 0;
        $userId   = !empty($this->paramsInfo['params']['uid']) ? (int)$this->paramsInfo['params']['uid'] : 0;

        if(empty($courseId)) return interface_func::setMsg(1000);
        $params = [
            'q' => ['course_id'=>$courseId,'deleted'=>'0,-1'],
            'f' => [
                'course_id','fee_type','thumb_med','user_total','comment','avg_score',
                'try','title','course_type','class','desc','subdomain','status','deleted'
            ],
        ];

        if(!empty($orgId)){
            //$params['q']['expression'] = "@resell_org_id =".$orgId." | @org_id=".$orgId;
        }
        $seekCourse = seek_api::seekCourse($params);
        if(empty($seekCourse->data)) return interface_func::setMsg(3002);

        $courseInfo = $seekCourse->data[0];
        //课程删除
        if($courseInfo->deleted=='-1') return interface_func::setMsg(1056);

        //分享链接
        $host = utility_net::isHTTPS() ? "https://" : "http://";
        if(strpos($courseInfo->subdomain, '.')){
            $shareUrl   = $host.$courseInfo->subdomain."/course.info.show/".$courseId;
        }else{
            $domainConf = SConfig::getConfig(ROOT_CONFIG."/const.conf", "domain");
            $shareUrl   = $host.$courseInfo->subdomain.'.'.$domainConf->domain."/course.info.show/".$courseId;
        }
        //是否收藏
        $fav = array();
        if(!empty($userId)){
            $fav = user_api::listfav(array("uid"=>$userId, "cid"=>$courseId));
        }

        //检查用户是否报名
        $resUser = course_api::checkIsRegistration($userId, $courseId);
        $isSign  = !empty($resUser) ? 1 : 0;
        $classId = !empty($resUser) ? $resUser['classId'] : 0;

        $isFree = ($courseInfo->fee_type==1) ? 1 : 0;
        $setReg  = user_organization::getMemberPriorityByObjectId($courseId, 1);
        if(!empty($setReg)){
            foreach($setReg as $val){
                $setIdArr[] = $val->fk_member_set;
            }
            $memberRet = org_member_api::checkIsMemberOrExpire($userId, $setIdArr, $courseId);

            if($memberRet['isMember'] == 1 && $memberRet['isExpire'] == 0){
                $isFree = 0;
            }else{
                $isSign = (!empty($resUser) && $resUser['source']==1) ? 1 : 0;
            }
        }elseif($resUser['source'] == 2 && empty($setReg)){
            //课程被会员移除
            $isSign = 0;
        }
        $scoreInfo=comment_api::getOneCourseScoreInfo($courseId);
        //QQ咨询
        $cid            = array();
        $cid['courseid']    = $courseId;
        //课程详情页客服
        $customer           = org_api::getCusRelationList($cid);
        $qqInfo             = !empty($customer->data) ? $customer->data : '';
        //课程信息
        $data = [
            "courseType" => $courseInfo->course_type,
            "courseId"   => $courseInfo->course_id,
            "courseName" => $courseInfo->title,
            "decurl"     => "/interface.course.desc?courseId={$courseId}&uid={$userId}",
            "feeType"    => $courseInfo->fee_type,
            "avgScore"   => empty($scoreInfo['avg_score'])?0:sprintf("%.1f",$scoreInfo['avg_score']),
            "commentNum" => $courseInfo->comment,
            "userTotal"  => $courseInfo->user_total,
            "imgurl"     => interface_func::imgUrl($courseInfo->thumb_med),
            "trySee"     => $courseInfo->try,
            "isFree"     => $isFree,
            "shareUrl"   => $shareUrl,
            "shareContent"=>!empty($courseInfo->desc) ? mb_substr(strip_tags($courseInfo->desc), 0, 60, 'utf-8') : $courseInfo->title,
            "isFav"      => !empty($fav) ? 1 : 0,
        ];
        if(!empty($qqInfo)){
            $data['qqData'] = $qqInfo;
        }
        $commentNum = comment_api::getCommentTotal(array('course_id'=>$courseInfo->course_id));
        $data['commentNum'] = empty($commentNum[0])?0:$commentNum[0]->total;
        //班级信息
        $class = array();
        if(isset($courseInfo->class) && !empty($courseInfo->class)){
            foreach($courseInfo->class as $val){
                $classIdArr[] = $val->class_id;
                //用户名报名是否有效
                $checkSign = course_api::checkClassUserIsFull($val->class_id);

                if($courseInfo->course_type == 3){
                    $address[$val->class_id] = $val->region_level0.' '.$val->region_level1.' '.$val->region_level2.' '.$val->address;
                }

                $userReg = order_api::getUserInfo(array($val->class_admin_id));
                $class[$val->class_id] = [
                    'classId'    => $val->class_id,
                    'isSign'     => ($val->class_id == $classId) ? $isSign : 0,
                    'name'       => $val->name,
                    'desc'       => $val->desc,
                    'userName'   => !empty($userReg[$val->class_admin_id]['name']) ? $userReg[$val->class_admin_id]['name'] : '',
                    'courseType' => $courseInfo->course_type,
                    'address'    => !empty($address[$val->class_id]) ? $address[$val->class_id] : ''
                ];


                if($courseInfo->course_type == 3 && $val->status == 3){
                    $class[$val->class_id]['checkSign'] = 2;
                }elseif($checkSign === false){
                    $class[$val->class_id]['checkSign'] = 0;
                }else{
                    $class[$val->class_id]['checkSign'] = 1;
                }
            }
            //章节信息
            $classIds = implode(',', $classIdArr);
            $planParams = [
                'q' => ['course_id'=>$courseId,'status'=>'1,2,3','class_id'=>$classIds],
                'f' => [
                    'section_name','section_desc','plan_id','status','section_id','video_trial_time',
                    'try','teacher_name','video_public_type','start_time','class_id','live_public_type',
                    'course_id','class_name','course_type','video_id','totaltime','end_time'
                ],
                'ob' => ['plan_id'=>'asc'],
                'pl' => 500
            ];
            $planData = seek_api::seekPlan($planParams);
            if(!empty($planData->data)){
                //视频状态(数据库)
                $statusName = array('normal'=>'1','living'=>'2','finished'=>'3','invalid'=>'-1','initial'=>'0');
                foreach ($planData->data as $plan) {
                    $planIdArr[] = $plan->plan_id;
                }
                $status = course_api::getPlanStatusV2($planIdArr);
                if(!empty($status)){
                    foreach($status as $key=>$val){
                        $statusArr[$key] = $statusName[$val->plan_status];
                    }
                }
                //是否学过
                $studyFlag = array();
                if($courseInfo->course_type != 3){
                    $studyFlag = course_plan_api::getStudyPlan($userId, $planIdArr);
                }
                $time  = date('Y');
                $time_1= strtotime("+24 hours");
                $startTime = array();
                foreach($planData->data as $val){

                    if($val->course_type != 2){
                        $stime = date('Y', strtotime($val->start_time));
                        if($stime == $time){
                            $startTime[$val->plan_id] = date('m-d H:i', strtotime($val->start_time));
                        }else{
                            $startTime[$val->plan_id] = date('Y-m-d H:i', strtotime($val->start_time));
                        }
                        $endTime[$val->plan_id]   = date('H:i', strtotime($val->end_time));
                    }

                    $class[$val->class_id]['section'][] = [
                        "planId"      => $val->plan_id,
                        "status"      => !empty($statusArr[$val->plan_id]) ? $statusArr[$val->plan_id] : 1,
                        "sectionId"   => $val->section_id,
                        "name"        => $val->section_name,
                        "desc"        => $val->section_desc,
                        "startTime"   => !empty($startTime[$val->plan_id]) ? $startTime[$val->plan_id] : '',
                        "endTime"     => !empty($endTime[$val->plan_id]) ? $endTime[$val->plan_id] : '',
                        //中间层try逻辑与现在网站try逻辑不一致
                        "trySee"      => (in_array($val->video_public_type,[1,2]) || $val->live_public_type == 1) ? 1 : 0,
                        'videoId'     => $val->video_id,
                        'totalTime'   => $val->totaltime,
                        'isStudy'     => !empty($studyFlag[$val->plan_id]) ? 1 : 0,
                        'downUrl'     => ($val->totaltime > 0 && $val->status == 3 && $val->video_public_type!='-2') ? interface_planApi::getPlanDownLoadUrl($val->plan_id) : array(),
                        'duration'    => utility_tool::sec2time($val->totaltime),
                        'hideVideo'   => ($val->video_public_type == '-2') ? 1 : 0,
                        //是否能进入播放页
                        'isPlay'      => (strtotime($val->start_time) < $time_1) ? 1 : 0
                    ];
                }
            }
        }

        $data['class'] = array_values($class);
        return interface_func::setData($data);
    }
	
	//班级课件列表
	public function pageAttachList(){
		
		$userId   = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;
		$courseId = !empty($this->paramsInfo['params']['courseId']) ? (int)$this->paramsInfo['params']['courseId'] : 0;
		$classId  = !empty($this->paramsInfo['params']['classId']) ? (int)$this->paramsInfo['params']['classId'] : 0;
		
		$regRes = interface_user_api::getUserRegCourse($userId, array($courseId));
		if(empty($regRes['items'])) return interface_func::setMsg(3002);
		
		$classIdArr = array_column($regRes['items'], 'fk_class');
		if(!in_array($classId, $classIdArr)) return interface_func::setMsg(3002);
		
		$fileReg = course_api::listPlanAttach(array('classId'=>$classId));
		if(empty($fileReg->data)) return interface_func::setMsg(3002);
        //获取文件大小
        foreach($fileReg->data as $val){
            $fidArr[] = '\''.$val->attach.'\'';
        }
        $file = utility_file::getFileByFidArr($fidArr);
        foreach($file as $v){
            $fileSize[$v->fid] = floor(($v->size/1024)*10)/10;
        }
		
		foreach($fileReg->data as $val){
			$data[] = [
				'attachName' => $val->title,
				'attachType' => (int)$val->type,
				'fileFormat' => substr(strrchr($val->thumb, '-'), 1,3),
                'downUrl'    => interface_func::httpHeader().':'.utility_cdn::file($val->attach),
                'fileSize'       => !empty($fileSize[$val->attach]) ? $fileSize[$val->attach] : 0
			];
		}
		
		return interface_func::setData($data);
	}
	
	//PC端 
	public function pageTeacherCourse(){
		$page   = !empty($this->paramsInfo['params']['page']) ? (int)$this->paramsInfo['params']['page'] : 1;
		$length = !empty($this->paramsInfo['params']['length']) ? (int)$this->paramsInfo['params']['length'] : 20;
		$userId = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;
		if(empty($userId)) return interface_func::setMsg(1000);
		
		$keywords = !empty($this->paramsInfo['params']['keyWords']) ? $this->paramsInfo['params']['keyWords'] : '';
		$orgList  = user_organization::getOrgIdsByUid($userId);
		if(empty($orgList)) return interface_func::setMsg(3002);
		$orgIdArr = [];
		if(!empty($orgList)){
            foreach($orgList as $v){
                //role user_role都要兼容
				if($v->status != '-1'){
					if($v->role==2||$v->user_role&0x04){
						$orgIdArr[$v->fk_org]=$v->fk_org;
					}
				} 
            }
        }
		$orgIds = implode(',', $orgIdArr);
		$params = [
			'q' => ['org_id'=>$orgIds,'course_type'=>2],
			'f' => ['course_id','title','class','org_subname'],
			'p' => $page,
			'pl'=> $length
		];
		if(!empty($keywords)){
			$params['q']['search_field'] = $keywords;
		}
	
		$seekCourse = seek_api::seekCourse($params);
		if(empty($seekCourse->data)) return interface_func::setMsg(3002);

		$data['page']      = $seekCourse->page;
		$data['totalSize'] = $seekCourse->total;
		$data['totalPage'] = ceil($seekCourse->total / $length);
		
		foreach($seekCourse->data as $val){
			$data['data'][] = [
				'courseId'   => $val->course_id,
				'courseName' => $val->title,
				'classNum'   => count($val->class),
				'subname'    => $val->org_subname
			];
		}
		
		return interface_func::setData($data);
	}
	
	public function pageTeacherPlan(){
		$courseId = !empty($this->paramsInfo['params']['courseId']) ? (int)$this->paramsInfo['params']['courseId'] : 0;
		if(empty($courseId)) return interface_func::setMsg(1000);
		
		$courseParams = [
			'q' => ['course_id'=>$courseId,'status'=>'1,2,3','course_type'=>2],
			'f' => ['class'],
		];
		$seekCourse = seek_api::seekCourse($courseParams);
		if(empty($seekCourse->data)) return interface_func::setData($seekCourse->data=[]);
		//班级信息
		$courseInfo = $seekCourse->data[0];
		foreach($courseInfo->class as $val){
			$class[$val->class_id] = [
				'classId'    => $val->class_id,
				'name'       => $val->name
			];
		}
		
		//章节信息
		$params = [
			'q' => ['course_id'=>$courseId,'status'=>'1,2,3','course_type'=>2],
			'f' => ['plan_id','section_name','section_desc','class_id'],
			'ob'=> ['plan_id'=>'asc'],
			'pl'=> 100
		];
		$seekPlan = seek_api::seekPlan($params);

		$plan    = array();
		$endPlan = array();
		if(!empty($seekPlan->data)){
			$statusArr  = array();
			$statusName = array('normal'=>'1','living'=>'2','finished'=>'3','invalid'=>'-1','initial'=>'0');
			foreach($seekPlan->data as $val){
				$planIdArr[] = $val->plan_id;
			}
			$status = course_api::getPlanStatusV2($planIdArr);
			
			if(!empty($status)){
				foreach($status as $key=>$val){
					$statusArr[$key] = $statusName[$val->plan_status];
				}
			}
			foreach($seekPlan->data as $val){
				$plan[$val->class_id][] = $val->plan_id;
				$class[$val->class_id]['data'][] = [
					'planId'      => $val->plan_id,
					'sectionName' => $val->section_name,
					'sectionDesc' => $val->section_desc,
					"status"      => !empty($statusArr[$val->plan_id]) ? $statusArr[$val->plan_id] : 1,
				];
				if(!empty($statusArr[$val->plan_id]) && $statusArr[$val->plan_id] == 3){
					$endPlan[$val->class_id][] = $val->plan_id;
				}
			}
		}
		
		foreach($class as $key=>$val){
			$class[$key]['planNum']     = !empty($plan[$key]) ? count($plan[$key]) : 0;
			$class[$key]['uploadVideo'] = !empty($endPlan[$key]) ? count($endPlan[$key]) : 0;
		}
		
		$data = array_values($class);
		return interface_func::setData($data);
	}
	//获取机构课程客服列表
    public function pagegetCusRelationList(){
        $courseId 			= !empty($this->paramsInfo['params']['courseId']) ? (int)$this->paramsInfo['params']['courseId'] : 0;
        if(empty($courseId)) return interface_func::setMsg(1000);
		$params   			= array();
        $params['courseid']	= $courseId;
        //课程详情页客服
        $customer 			= org_api::getCusRelationList($params);
		$info				= !empty($customer->data) ? $customer->data : '';
        if ($customer->result->code ==0) {
           return interface_func::setData($info);
        }else{
            return interface_func::setMsg(3002);
        }    
    }
	//返回失败订单错误码
	public function pagegetUnipayStatus(){
        $orderId 			= !empty($this->paramsInfo['params']['orderId']) ? (int)$this->paramsInfo['params']['orderId'] : 0;
        if(empty($orderId)) return interface_func::setMsg(1000);
		$params   			= array();
        $params['fk_order']	= $orderId;
        $info 			= interface_courseApi::getUnipayStatus($params);
		if(!empty($info->third_return_params)){
			$stauts = json_decode($info->third_return_params);
			$mess   = !empty($stauts->status) ? array("status"=>$stauts->status) : "";
		}
        return interface_func::setData($mess);  
    }
}
