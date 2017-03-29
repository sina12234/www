<?php

class interface_teacher extends interface_base
{
    /*
     * 老师筛选与查询
     * @author zhengtianlong
     */
    public function pageListV2()
    {
		$sortType = [
			2001 => 'student_count',
			2002 => 'avg_score'
		];
		
		$gradeName = [1000=>'小学',2000=>'初中',3000=>'高中'];
        $page   = empty($this->paramsInfo['params']['page']) ? 1 : $this->paramsInfo['params']['page'];
        $length = empty($this->paramsInfo['params']['length']) ? 20 : $this->paramsInfo['params']['length'];
        $params = [
            'p'  => $page,
            //'q'  => ['teacher_status'=>1,'is_star'=>1,'visiable'=>1,'org_id' => "1,10,14,15,35",],
            'q'  => ['teacher_status'=>1,'visiable'=>1, 'course_count'=>'1, 5000'],
            'pl' => $length,
            'f'  => [
                    'teacher_id',
                    'name',
                    'real_name',
                    'thumb_big',
                    'subject_id',
                    'totaltime',
                    'student_count',
                    'avg_score',
                    'mobile',
                    'grade',
                    'grade_id',
                    'subject',
                    'course_count'
                ],
        ];
        if(!empty($this->paramsInfo['params']['city']) && $this->paramsInfo['params']['city']!='全国' )
        {
            $params['q']['city'] = $this->paramsInfo['params']['city'];
        }
        if(!empty($this->paramsInfo['params']['subjectId']))
        {
            $params['q']['subject_id'] = $this->paramsInfo['params']['subjectId'];
        }
        if(!empty($this->paramsInfo['params']['gradeId']))
        {
            $params['q']['grade_id'] = $this->paramsInfo['params']['gradeId'];
        }
        if(!empty($this->paramsInfo['params']['keywords']))
        {
            $params['q']['search_field'] = $this->paramsInfo['params']['keywords'];
        }		
		if(!empty($this->paramsInfo['params']['sort']))
        {
			$sort = explode(',',$this->paramsInfo['params']['sort']);
			foreach ($sortType as $k=>$v)
			{
				if (in_array($k, $sort))
				{
					$params['ob'][$v] = 'desc';
				}
			}
			if(empty($params['ob'])){
				$params['ob']['weight'] = 'desc';
			}
        }else{
			$params['ob']['weight'] = 'desc';
		}
		
        $res_teacher = seek_api::seekTeacher($params);
		
        if (empty($res_teacher->data)) return $this->setMsg(3002);
		
		$data = [
			"total"     => $res_teacher->total,
			"page"      => $res_teacher->page,
			"pageTotal" => ceil($res_teacher->total/$res_teacher->pagelength)
		];
		$grade = array();
		foreach($res_teacher->data as $k=>$v)
		{
			if(!empty($v->grade_id))
			{
				foreach($gradeName as $key=>$val)
				{
					$gradeId = $v->grade_id;
					if(in_array($key,$gradeId))
					{
						$grade[$v->teacher_id][$key] = $val;
					}
				}
			}
			$data['data'][$k] = [
				"teacherId"       => $v->teacher_id,
				"name"            => !empty($v->real_name)?$v->real_name:(!empty($v->name)?mb_substr($v->name,0,15):SLanguage::tr('未设置', 'message')),
				"thumbMed"        => $this->imgUrl($v->thumb_big),
				"subjectName"     => !empty(teacher_api::getSubjectName($v->subject))?str_replace(',',' ',teacher_api::getSubjectName($v->subject)):'',
				"courseTotalTime" => number_format($v->totaltime/3600,1),
				"userTotal"       => $v->student_count,
				"score"           => $v->avg_score/10,
				"grade"           => (!empty($grade) && !empty($grade[$v->teacher_id]))?implode(' ',$grade[$v->teacher_id]):''
			];
		}
		
        return $this->setData($data);
    }

	/*
     * 老师信息接口
     * @author zhengtianlong
     */
    public function pageDetailV2()
    {
        $this->v(['teacherId' => 1000]);
		$sexArr = array(1=>'男',2=>'女');
		$gradeName = [1000=>'小学',2000=>'初中',3000=>'高中'];
        $params = [
            'q' => ['teacher_id'=>$this->paramsInfo['params']['teacherId']],
            'f' => [
                'teacher_id',
                'name',
            	'real_name',
                'org_name',
                'diploma',
                'college',
                'thumb_big',
                'years',
                'subject_id',
                'desc',
                'title',
                'gender',
                'org_id',
				'totaltime',
				'avg_score',
				'student_count',
				'desc_score',
				'student_score',
				'explain_score',
                'org_teacher',
				'subject',
				'grade',
				'grade_id'
            ]
        ];

        $res_teacher = seek_api::seekTeacher($params);
		
        if (empty($res_teacher->data)) return $this->setMsg(3002);
        $t = $res_teacher->data[0];
		
		$grade = array();
		if(!empty($t->grade_id))
		{
			foreach($gradeName as $key=>$val)
			{
				$gradeId = $t->grade_id;
				if(in_array($key,$gradeId))
				{
					$grade[$t->teacher_id][$key] = $val;
				}
			}
		}
		$header = utility_net::isHTTPS() ? "https://" : "http://";
        $data = [
            'teacherId' => $t->teacher_id,
            'name'      => !empty($t->real_name)?$t->real_name:(!empty($t->name)?mb_substr($t->name,0,15):SLanguage::tr('未设置', 'message')),
            'thumbMed'  => !empty($t->thumb_big) ? $this->imgUrl($t->thumb_big) : '',
            'diploma'   => !empty(teacher_major::$diploma[$t->diploma])?teacher_major::$diploma[$t->diploma]: '',
            'years'     => !empty($t->years) ? $t->years : '',
            'college'   => !empty($t->college) ? $t->college : '',
            'subject'   => !empty(teacher_api::getSubjectName($t->subject))?str_replace(',',' ',teacher_api::getSubjectName($t->subject)):'',
            'desc'      => !empty($t->desc) ? $t->desc : '',
            'title'     => !empty($t->title) ? $t->title : '',
			'sex'       => !empty($t->gender) ? $sexArr[$t->gender] : '',
            'userTotal' => $t->student_count,
            'score'     => round($t->avg_score, 1),
			'taughtGrade'=> (!empty($grade) && !empty($grade[$t->teacher_id]))?implode(' ',$grade[$t->teacher_id]):'',
            'conform'   => round($t->avg_score, 1),
			'satisfaction'=> round($t->avg_score, 1),
            'expression'  => round($t->avg_score, 1),
			'courseTotalTime' => number_format($t->totaltime/3600,1)
        ];
		
        if(!empty($t->org_teacher[0]))
        {
            $ownerId = $t->org_teacher[0]->fk_user_owner;
            $orgInfo = user_organization::getOrgByOwner($ownerId);
			$domainInfo = user_organization::getSubdomainByUid($ownerId);
			
			if(!empty($domainInfo) && !empty(user_organization::course_domain($domainInfo->subdomain))){
				//$data['url'] = $header.user_organization::course_domain($domainInfo->subdomain)."/index/teacherblog/entry/".$t->teacher_id;
				$data['url'] = $header.user_organization::course_domain($domainInfo->subdomain)."/teacher/detail/entry/".$t->teacher_id;
			}else{
				$data['url'] = '';
			}
			
            $data['org'] = !empty($orgInfo->subname)?$orgInfo->subname:$t->org_name;
        }

        return $this->setData($data);
    }

	/*
     * 老师评论接口
     * @author zhengtianlong
     */
    public function pageCommentV2()
    {
        $this->v(['teacherId' => 1000]);

        $page   = empty($this->paramsInfo['params']['page']) ? 1 : $this->paramsInfo['params']['page'];
        $length = empty($this->paramsInfo['params']['length']) ? 20 : $this->paramsInfo['params']['length'];
        $tid    = $this->paramsInfo['params']['teacherId'];
		$commentParam = [
			'page'      => $page,
            'limit'     => $length,
            'condition' => "teacher_id={$tid} and fk_plan!=0",
            'orderBy'   => 'pk_comment desc'
		];

		//老师的评论信息
        $res_comment = comment_api::getCommentList($commentParam);

		if (empty($res_comment->items)) return $this->setMsg(3002);

		$planIdArr = $courseIdArr = [];
		foreach($res_comment->items as $val)
		{
			$planIdArr[] = $val->fk_plan;
			$courseIdArr[$val->fk_course] = $val->fk_course;
		}
		$planIdStr = implode(',',$planIdArr);
		//排课信息
		$planParams = [
			'q' => ['plan_id'=>$planIdStr],
			'f' => ['course_id','course_name','section_name','class_name'],
			'pl'=> 10000
		];

		$res_plan = seek_api::seekPlan($planParams);
		
		$planInfo = array();
		if(!empty($res_plan->data))
		{
			foreach($res_plan->data as $v)
			{
				$planInfo[$v->course_id] = [
					'course_name'  => $v->course_name,
					'section_name' => $v->section_name,
					'class_name'   => $v->class_name
				];
			}
		}

        $data   = [];
        if (!empty($res_comment->items))
		{
			$data = [
				'total'    => $res_comment->totalSize,
				'page'     => $res_comment->page,
				'totalPage'=> $res_comment->totalPage
			];

			$scoreInfo = comment_api::getScoreCourseTotalList($courseIdArr);
            foreach ($res_comment->items as $v)
			{
                $data['data'][] = [
					'courseId'  => $v->fk_course,
                    'commentId' => $v->pk_comment,
                    'userId'    => $v->fk_user,
                    'userName'  => $v->user_name,
                    'userImage' => $this->imgUrl($v->user_thumb),
                    'content'   => html_entity_decode($v->comment),
                    'score'     => !empty($scoreInfo['data'][$v->fk_course]['avg_score']) ? $scoreInfo['data'][$v->fk_course]['avg_score'] : 0,
                    'time'      => date('Y-m-d',strtotime($v->last_updated)),
					'course'    => !empty($planInfo[$v->fk_course]['course_name'])?$planInfo[$v->fk_course]['course_name']:'',
					'class'     => !empty($planInfo[$v->fk_course]['class_name'])?$planInfo[$v->fk_course]['class_name']:'',
					'section'   => !empty($planInfo[$v->fk_course]['section_name'])?$planInfo[$v->fk_course]['section_name']:''
                ];
            }
        }
        return $this->setData($data);
    }

    public function subject()
    {
        $data = [
            'grade' => [
                ['name' => '小学', 'id' => '1000'],
                ['name' => '初中', 'id' => '2000'],
                ['name' => '高中', 'id' => '3000'],
            ],
            'subject' => [
              ['name' => '数学', 'id' => '1'],
              ['name' => '英语', 'id' => '2'],
              ['name' => '语文', 'id' => '3'],
              ['name' => '物理', 'id' => '4'],
              ['name' => '化学', 'id' => '5'],
              ['name' => '生物', 'id' => '6'],
              ['name' => '历史', 'id' => '7'],
              ['name' => '地理', 'id' => '8'],
              ['name' => '政治', 'id' => '9']
            ],
            'others' => [
              'nolimit' => '不限',
              'courseType' => [
                ['name' => '线上课', 'id' => '1001'],
                ['name' => '线下课', 'id' => '1002']
              ],
              'sortType' => [
                ['name' => '人气排序', 'id' => '2001'],
                ['name' => '评分排序', 'id' => '2002']
              ]
            ]
        ];

        return $this->setData($data);
    }

    /*
     * 教师数/课程数/机构数
     * @author zhengtianlong
     */
    public function pageGetNum()
    {
		
        $cityId   = isset($this->paramsInfo['params']['cityId'])?$this->paramsInfo['params']['cityId']:0;
        $cityName = !empty($this->paramsInfo['params']['city'])?$this->paramsInfo['params']['city']:'全国';
        $org      = user_api::getOrgByProvince($cityId);
		
		$orgNum     = 0;
		$courseNum  = 0;
		$teacherNum = 0;
		
		$ownerArr = array();
		if(!empty($org->data->items)){
			$ownerArr = array_reduce($org->data->items, create_function('$v,$w', '$v[$w->fk_user_owner]=$w->fk_user_owner;return $v;'));
		}
		$ownerArr = array_filter($ownerArr);
		
		//课程数
		if($ownerArr){
			$ownerStr = implode(',',$ownerArr);
			$params   = [
				'q'  => ['user_id'=>$ownerStr,'admin_status'=>1],
				'f'  => ['course_id','user_id'],
				'pl' => 10000
			]; 
            $courseRes = seek_api::seekCourse($params);

            //机构对应的课程Id
            $orgCourseNum = array();
            if(!empty($courseRes->data)){
                $orgCourseId = array_reduce($courseRes->data,create_function('$v,$w','$v[$w->user_id][]=$w->course_id;return $v;'));
                foreach($orgCourseId as $k=>$v){
                    $orgCourseNum[$k] = count($v);
                }
            }

			$courseNum = $courseRes->total;
		}

		//机构数
		if(!empty($org)){
			$orgNum = !empty($orgCourseNum) ? count($orgCourseNum) : 0;
		}
		
		//老师数
		if($cityName == '全国')
		{
			$TeacherParams = [
                'q' => ['teacher_status'=>1,'visiable'=>1, 'course_count'=>'1, 5000'],
				'f' => ['teacher_id'],
                'pl'=> 10000
			];
		}else{
			$TeacherParams = [
				'q' => ['city'=>$cityName,'teacher_status'=>1,'visiable'=>1, 'course_count'=>'1, 5000'],
				'f' => ['teacher_id'],
                'pl'=> 10000
			];
		}
		$teacherRes = seek_api::seekTeacher($TeacherParams);
	
		if(!empty($teacherRes->data)){
			$teacherNum = $teacherRes->total;
		}
		
		$data = [
            'teacherTotal' => $teacherNum,
            'courseTotal'  => $courseNum,
            'orgTotal'     => $orgNum
        ];

        return $this->setData($data);
    }

       /*
        * 老师首页接口
        * @author zhengtianlong
        */
        public function pageTeacherInfoCourse()
        {
			$this->v(['uid'=>1000]);
			$page   = empty($this->paramsInfo['params']['page']) ? 1 : $this->paramsInfo['params']['page'];
			$length = empty($this->paramsInfo['params']['length']) ? 20 : $this->paramsInfo['params']['length'];
			$uid    = $this->paramsInfo['params']['uid'];
			$startTime   = date('Y-m-d H:i:s');
            $endTime = date('Y-m-d H:i:s',strtotime("+30 day"));
			//老师信息
			$teacherParams = [
                'q' => ['teacher_id'=>$uid],
                'f' => [
                        'teacher_id',
                        'name',
                        'thumb_big',
                        'student_count',
                        'totaltime',
						'course_count',
						'student_score',
                        'desc'
                    ]
            ];
            $res_teacher = seek_api::seekTeacher($teacherParams);
			if (empty($res_teacher->data)) return $this->setMsg(3002);
			$t = $res_teacher->data[0];
			//课程信息
			$planParams = [
				'q' => ['teacher_id'=>$uid,'status'=>'1,2','start_time'=> "{$startTime},{$endTime}"],
				'f' => ['course_id','class_id','course_name','class_name','start_time'],
				'ob'=> ['start_time'=>'asc'],
				'p' => $page,
				'pl'=> $length
			];
			$res_plan = seek_api::seekPlan($planParams);

			$course = array();
			if(!empty($res_plan->data))
			{

                foreach($res_plan->data as $val)
                {
                    $courseId[$val->course_id] = $val->course_id;
                }
                $courseIdStr = implode(',',$courseId);
                $courseParams = [
                    'q' => ['course_id'=>$courseIdStr],
                    'f' => ['course_id','thumb_med'],
                    'pl'=> 10000
                ];
                $res_course = seek_api::seekcourse($courseParams);
                if(!empty($res_course->data))
                {
                   foreach($res_course->data as $c)
                   {
                        $courseImg[$c->course_id] = $this->imgUrl($c->thumb_med);
                   }
                }
				$course = [
					'page'      => $res_plan->page,
					'totalPage' => $res_plan->pagelength,
					'total'     => $res_plan->total
				];

				foreach($res_plan->data as $v)
				{
					$course['data'][] = [
						'classId'   => $v->class_id,
						'className' => $v->class_name,
						'courseId'  => $v->course_id,
						'courseName'=> $v->course_name,
						'startTime' => date("m-d H:i",strtotime($v->start_time)),
                        'courseImage'=>!empty($courseImg[$v->course_id])?$courseImg[$v->course_id]:'',
						'courseType'=> '线上课',
						'address'   => ''
					];
				}
			}

			//老师详情
			$info = [
                'uid'          => $t->teacher_id,
                'name'         => $t->name,
                'image'        => $this->imgUrl($t->thumb_big),
                'desc'         => $t->desc,
                'pv'           => $t->course_count, //教师总课程数
                'ranking'      => ($t->student_score/10), //学生满意度
                'courseTime'   => number_format($t->totaltime/3600,1),
                'class'        => !empty($course['total'])?$course['total']:0,
                'studentTotal' => $t->student_count,
                'courseTask'   => 0
            ];

			if(!empty($course['page']) && $course['page']!=1)
			{
				$data = $course;
			}else
			{
				if(!empty($course))
				{
					$data = [
						'info'   => $info,
						'course' => $course
					];
				}else
				{
					$data = ['info'=>$info];
				}
			}

			return $this->setData($data);
        }

        /*
         * 老师的课程接口
         * @author zhengtianlong
        */
        public function pageTeacherCourse()
        {
            $page   = empty($this->paramsInfo['params']['page']) ? 1 : $this->paramsInfo['params']['page'];
            $length = empty($this->paramsInfo['params']['length']) ? 20 : $this->paramsInfo['params']['length'];
            $this->v(['teacherId' => 1000]);
            $tid = $this->paramsInfo['params']['teacherId'];
            //老师下所有的课
			$planParams = [
				'q' => ['teacher_id'=>$tid,'status'=>'1,2,3'],
				'f' => ['course_id'],
                'ob' => ['start_time' => 'asc'],
				'pl'=> 1000
			];

			$res_plan = seek_api::seekPlan($planParams);
			if (empty($res_plan->data)) return $this->setMsg(3002);
			//老师下的所有课程id
			foreach($res_plan->data as $v)
			{
				$cid[] = $v->course_id;
			}
			$cidStr = implode(',',$cid);
			//学生报的课程
			$userCourseId = array();
			if(!empty($this->paramsInfo['params']['uid']))
			{
				$res_usercourse = course_api::listregistration(array('uid'=>$this->paramsInfo['params']['uid']));
				if(!empty($res_usercourse->data))
				{
					foreach($res_usercourse->data as $val)
					{
						$userCourseId[$val->cid] = $val->cid;
					}
				}
			}

			//课程的信息
			$courseParams = [
				'q' => ['course_id'=>$cidStr,'admin_status'=>'1'],
				'f' => ['course_id','title','price','start_time','status','fee_type'],
                'p' => $page,
                'pl'=> $length
			];
			$res_course = seek_api::seekCourse($courseParams);
			if (empty($res_course->data)) return $this->setMsg(3002);
			$data = [
                'page'     => $res_course->page,
                'total'    => $res_course->total,
                'totalPage'=> $res_course->pagelength
            ];

			foreach($res_course->data as $v)
			{
                //apple check
                /*if ($this->paramsInfo['u'] == 'i') {
                    if ($v->fee_type > 0 || $v->price > 0) continue;
                }*/
                // end
				$data['data'][] = array(
                    'courseId'     => $v->course_id,
                    'title'        => $v->title,
                    'feeTyep'      => $v->fee_type,
                    'price'        => floatval($v->price / 100),
                    'courseStatus' => $v->status,
                    'startTime'    => date('Y年m月d日 H:i',strtotime($v->start_time)),
                    'userStatus'   => empty($userCourseId[$v->course_id])?'0':'1',
                    'address'      => '',
                    'courseType'   => 0
                );
			}
			return $this->setData($data);
        }

        /*
         * 老师下的订单接口
         * @author zhengtianlong
        */
        public function pageTeacherOrder()
        {
            $page   = empty($this->paramsInfo['params']['page']) ? 1 : $this->paramsInfo['params']['page'];
            $length = empty($this->paramsInfo['params']['length']) ? 20 : $this->paramsInfo['params']['length'];
			if (empty($this->paramsInfo['params']['uid'])) return $this->setMsg(1009);
            $uid    = $this->paramsInfo['params']['uid'];

			$params = [
                'q' => ['teacher_id'=>$uid],
                'f' => ['course_id','class_name'],
				'pl'=> 10000
            ];
            $res = seek_api::seekplan($params);
			
            if (empty($res->data)) return $this->setMsg(3002);
			
			$courseIdArr = array();
			$classInfo   = array();
			foreach($res->data as $v){
				$courseIdArr[$v->course_id] = $v->course_id;
				$classInfo[$v->course_id]   = $v->class_name;
			}
			
            $courseId = implode(',',$courseIdArr);
			
            $condition = [
                'objectId' => $courseId,
				'orderType'=> 1,
                'status'   => 2
            ];

			$result = order_api::orderList($condition, $page, $length);
			
            if (empty($result['data'])) return $this->setMsg(3002);
            $data = [
                'page'      => $page,
                'total'     => $result['totalSize'],
                'totalPage' => $result['total']
            ];

            foreach($result['data'] as $val){
				$data['data'][] = [
                    'orderId'     => $val->fk_order,
                    'price'       => $val->price,
                    'orderStatus' => $val->status,
                    'startTime'   => empty($val->course['startTime'])?'':date("n月d日 H:i",strtotime($val->course['startTime'])),
                    'studentId'   => $val->fk_user,
                    'courseId'    => $val->object_id,
                    'courseTitle' => !empty($val->course['name']) ? $val->course['name'] : '',
                    'orderTime'   => date('Y-m-d H:i',strtotime($val->create_time)),
                    'className'   => $val->course['className'],
                    'studentMobile'=> $val->stuName,
                    'studentImage' => $val->stuImg,
                    'courseType'   => '线上课'
                ];
            }

            return $this->setData($data);
        }


        public function pageStudent()
        {
            $data = interface_courseApi::getTeacherStudent(
                $this->paramsInfo['params']['courseId'],
                $this->paramsInfo['params']['classId']
            );

            if (!empty ($data)) return interface_func::setData($data);
            return interface_func::setMsg(1);
        }

        /*
         * 老师下的学生列表
         * @author zhengtianlong
         */
        public function pageStudentV2()
        {
            $this->v(['uid' => 1000]);
            $uid      = $this->paramsInfo['params']['uid'];
            $page     = !empty($this->paramsInfo['params']['page'])?$this->paramsInfo['params']['page']:1;
            $length   = !empty($this->paramsInfo['params']['length'])?$this->paramsInfo['params']['length']:20;
            $courseId = !empty($this->paramsInfo['params']['courseId'])?$this->paramsInfo['params']['courseId']:0;
            $classId  = !empty($this->paramsInfo['params']['classId'])?$this->paramsInfo['params']['classId']:0;
            $data = array();
            //老师下的课程
            $planParams = [
                'q' => ['admin_id'=>$uid,'status'=>'1,2,3'],
                'f' => ['course_id','class_id','course_name','class_name','teacher_id'],
                'pl'=> 100000
            ];
            $tresult = seek_api::seekPlan($planParams);
            if (empty($tresult->data)) return $this->setMsg(3002);
            if($courseId==0 && $classId==0)
            {
			   $res_user = utility_teacherreg::teacherClassList(0,$uid,0,$page,$length);
			   if (empty($res_user->data)) return $this->setMsg(3002);
			   
			   $data = [
					'page' => $res_user->page,
					'totalPage' => $res_user->total,
					'total' => $res_user->size
				];
			   
			   foreach($res_user->data as $val)
			   {
				   $data['data'][] = [
						"stuId"      => $val->uid,
                        "name"	     => !empty($val->user_info->student_name)?$val->user_info->student_name:(!empty($val->user_info->name) ? $val->user_info->name : SLanguage::tr('未设置', 'message')),
                        "mobile"     => !empty($val->user_info->mobile)?$val->user_info->mobile:'',
                        "image"      => !empty($val->user_info->thumb_med)?$this->imgUrl($val->user_info->thumb_med):'',
                        "courseId"   => $val->cid,
                        "courseName" => !empty($val->course_name)?$val->course_name:'',
                        "classId"	 => $val->class_id,
                        "className"  => !empty($val->class_name)?$val->class_name:'',
                        "teacherCourseCount"=>$tresult->total
				   ];
			   }
            }else
            {
                foreach($tresult->data as $v)
                {
                    $cids[] = $v->course_id;
                    $course[$v->course_id] = [
                        'course_name' => $v->course_name,
                        'class_name'  => $v->class_name
                    ];
                }
			if(!in_array($courseId,$cids))
			{
				return $this->setMsg(3002);
			}
			$courseResult = course_api::listRegistration(array('course_id'=>$courseId,'class_id'=>$classId),$page,$length);
			if (empty($courseResult)) return $this->setMsg(3002);
			$data = [
				'page' => $courseResult->page,
				'totalPage' => $courseResult->total,
				'total' => $courseResult->size
			];
			foreach($courseResult->data as $val)
			{
				$data['data'][] = [
					"stuId"        => $val->uid,
					"name"	     => !empty($val->user_info->student_name)?$val->user_info->student_name:(!empty($val->user_info->name) ? $val->user_info->name : SLanguage::tr('未设置', 'message')),
					"mobile"     => !empty($val->user_info->mobile)?$val->user_info->mobile:'',
					"image"      => !empty($val->user_info->thumb_med)?$this->imgUrl($val->user_info->thumb_med):'',
					"courseId"   => $val->cid,
					"courseName" => !empty($course[$val->cid]['course_name'])?$course[$val->cid]['course_name']:'',
					"classId"	 => $val->class_id,
					"className"  => !empty($course[$val->cid]['class_name'])?$course[$val->cid]['class_name']:'',
                    "teacherCourseCount"=>$tresult->total
				];
			}
		}
		return $this->setData($data);
	}

    /*
     * 老师的课程于班级
     * @author zhengtianlong
     */
    public function pageGetCourseAndClass()
    {
        $this->v(['uid'=>1000]);
        $uid = $this->paramsInfo['params']['uid'];
        $planParams = [
                'q' => ['teacher_id'=>$uid,'status'=>'1,2,3'],
                'f' => ['course_id'],
                'pl'=> 100000
            ];
        $res_plan = seek_api::seekPlan($planParams);
        if (empty($res_plan->data)) return $this->setMsg(3002);
        foreach($res_plan->data as $v)
        {
           $courseId[$v->course_id] = $v->course_id;
        }
        $courseIdStr = implode(',',$courseId);
        $courseParams = [
            'q'  => ['course_id'=>$courseIdStr],
            'f'  => ['course_id','title','class'],
            'pl' => 100000
        ];

        $res_course = seek_api::seekCourse($courseParams);
        if (empty($res_course->data)) return $this->setMsg(3002);
        $data  = array();
        $class = array();
        foreach($res_course->data as $val)
        {
            if(!empty($val->class))
            {
               foreach($val->class as $v)
               {
                    $class[$val->course_id][] = [
                        'classId'   => $v->class_id,
                        'className' => $v->name
                    ];
               }
            }
            $data['data'][$val->course_id] = [
                'courseId'   => $val->course_id,
                'courseName' => $val->title,
                'classes'    => !empty($class[$val->course_id])?$class[$val->course_id]:''
            ];

        }
        $data['data'] = array_values($data['data']);
        return $this->setData($data);
    }
	
	/*
	 * 获取老师信息
	 */
	public function pageGetTeacherInf()
	{
		$this->v(['uid'=>1000]);
		$diploma = array(1=>'大专',2=>'本科',3=>'硕士',4=>'博士');
		$gradeName = [1000=>'小学',2000=>'初中',3000=>'高中','4000'=>'学前'];
		$uid = (int)($this->paramsInfo['params']['uid']);
		
		$params = [
            'q'  => ['teacher_id'=>$uid,'teacher_status'=>1,'visiable'=>1],
            'f'  => ['teacher_id','diploma','college','years','subject','subject_id','grade','grade_id','title','desc']
        ];

        $res_teacher = seek_api::seekTeacher($params);
		if (empty($res_teacher->data)) return $this->setMsg(3002);
		
		$data = array();
		$grade = array();
		foreach($res_teacher->data as $v)
		{
			if(!empty($v->grade_id))
			{
				foreach($gradeName as $key=>$val)
				{
					$gradeId = $v->grade_id;
					if(in_array($key,$gradeId))
					{
						$grade[$key] = $val;
					}
				}
			}
			
			$data['data'] = [
				"diploma"       => !empty($diploma[$v->diploma])?$diploma[$v->diploma]:'',
				"diplomaId"     => $v->diploma,
				"graduaction"   => !empty($v->college)?$v->college:'',
				"seniority"     => $v->years,
				"teachingField" => !empty($grade)?implode(',',$grade):'',
				"subject"       => !empty(teacher_api::getSubjectName($v->subject))?teacher_api::getSubjectName($v->subject):'',
				"subjectId"     => !empty($v->subject_id)?implode(',',$v->subject_id):'',
				"title"         => !empty($v->title)?$v->title:'',
				"desc"          => !empty($v->desc)?$v->desc:'',
				"teachingFieldId" => !empty($grade)?implode(',',array_keys($grade)):'',
			];
		}
		
		return $this->setData($data);
	}
	
	/*
	 * 修改老师信息
	 */
	public function pageEditTeacher()
	{
		$this->v(['uid'=>1000]);
		$uid     = (int)($this->paramsInfo['params']['uid']);
		
		$data = array();
		//毕业院校
		if(!empty($this->paramsInfo['params']['graduaction'])){
			$data['college'] = $this->paramsInfo['params']['graduaction'];
			if(mb_strlen($data['college'],'utf-8')>15){
				return $this->setMsg(1);
			}
		}
		//学历ID
		if(!empty($this->paramsInfo['params']['diplomaId'])){
			$data['diploma'] = (int)($this->paramsInfo['params']['diplomaId']);
		}
		//教龄  
		if(!empty($this->paramsInfo['params']['seniority'])){
			$data['years'] = (int)($this->paramsInfo['params']['seniority']);
		}
		//科目对应ID
		if(!empty($this->paramsInfo['params']['subjectId'])){
			$data['good_subject'] = $this->paramsInfo['params']['subjectId'];
		}
		//教学领域对应ID
		if(!empty($this->paramsInfo['params']['teachingFieldId'])){
			$scope = explode(',',$this->paramsInfo['params']['teachingFieldId']);
			
			$scopeArr=array(
				4000 => 0x01,
                1000 => 0x02,
                2000 => 0x04,
                3000 => 0x08,
            );
			$scopes=0;
			if(!empty($scope))
			{
				foreach($scope as $v)
				{
					if(isset($scopeArr[$v]))
					{
						$scopes+=$scopeArr[$v];
					}
				}
			}
			$data['scopes'] = $scopes;
		}
		//头衔
		if(!empty($this->paramsInfo['params']['title'])){
			$data['title'] = trim($this->paramsInfo['params']['title']);
		}
		//个人介绍
		if(!empty($this->paramsInfo['params']['desc'])){
			$data['desc'] = htmlspecialchars($this->paramsInfo['params']['desc']);
		}
		
		$res1 = user_api::setTeacherInfoV2($uid,$data);
        //更新t_user表中的last_updated字段
        $res2 = user_api::updateUser($uid,array('last_updated'=>date('Y-m-d H:i:s',time())));
		
        if($res1 && $res2){
           return $this->setMsg(0);
        }

		return $this->setMsg(1);
	}
	
	/*
	 * 老师下所有的课
	 */
	public function pageCourseList()
	{
		$this->v(['userId'=>1000]);
		$page   = !empty($this->paramsInfo['params']['page']) ? $this->paramsInfo['params']['page']:1;
        $length = !empty($this->paramsInfo['params']['length']) ? $this->paramsInfo['params']['length']:20;
		$type   = !empty($this->paramsInfo['params']['type']) ? $this->paramsInfo['params']['type'] : 0;
		$status = !empty($this->paramsInfo['params']['status']) ? $this->paramsInfo['params']['status'] : 0;
		$userId = (int)($this->paramsInfo['params']['userId']);
		
		$params = [
			'userClassId'    => $userId,
			'courseType'     => $type,
			'progressStatus' => $status
		];
		$classRes = course_api::getClassAndCourseList($params,$page,$length);
		if(empty($classRes->result->items)) return $this->setMsg(3002);
		
		foreach($classRes->result->items as $val){
			$courseIdArr[] = $val->course_id;
			$classIdArr[]  = $val->class_id;
		}
		$courseIds = implode(',', $courseIdArr);
		$classIds  = implode(',', $classIdArr);
		$planParams = [
			'q' => ['course_id'=>$courseIds, 'class_id'=>$classIds],
			'f' => ['plan_id','course_id','class_id','video_id','start_time','section_name'],
			'pl'=> 1000
		];
		$planRes = seek_api::seekPlan($planParams);
		$videoInfo = array();
		$planTime  = array();
		$select = array();
		$selectName  = array();
		if(!empty($planRes->data)){
			foreach($planRes->data as $val){
				$videoInfo[$val->course_id][] = !empty($val->video_id) ? 1 : 0;
				$planTime[$val->plan_id] = $val->start_time;
				$select[$val->class_id][] = $val->plan_id;
				$selectName[$val->plan_id] = $val->section_name;
			}
		}
		if(!empty($select)){
			foreach($select as $k=>$v){
				$selectNum[$k] = count($v);
			}
		}

		$videos = array();
		if(!empty($videoInfo)){
			foreach($videoInfo as $key=>$val){
				$videos[$key] = array_sum($val);
			}
		}

		$data = [
			'page'      => $classRes->result->page,
			'totalPage' => $classRes->result->totalPage,
			'total'     => $classRes->result->totalSize
		];

		foreach($classRes->result->items as $val){
			$data['data'][] = [
				"type"        => (int)$val->course_type,
				"courseId"    => $val->course_id,
				"courseName"  => $val->course_title,
				"image"       => $this->imgUrl($val->thumb_med),
				"rate"        => $val->progress_percent/100,
				"className"   => $val->name,
				"classId"     => $val->class_id,
				"selectCount" => !empty($selectNum[$val->class_id]) ? $selectNum[$val->class_id] : 0,
				"progressPlan"=> !empty($selectName[$val->progress_plan]) ? $selectName[$val->progress_plan] : '未开课',
				"time"        => !empty($planTime[$val->progress_plan]) ? date('H:i',strtotime($planTime[$val->progress_plan])) : '',
				"videoNum"    => !empty($videos[$val->course_id]) ? $videos[$val->course_id] : 0
			];
		}
		
		return $this->setData($data);
	}

	
	/*
	 * 课程详情
	 */
	public function pageCourseInfo()
	{
		$this->v(['courseId'=>1000]);
		$courseId = (int)($this->paramsInfo['params']['courseId']);
		
		$params = [
			'q' => ['status' => '1,2,3','course_id'=>$courseId,'admin_status'=>'1,-2,0'],
			'f' => [ 
					'plan_id','course_name','section_name','section_id','section_desc','admin_id',
					'course_type','status','teacher_real_name','start_time','class_name','teacher_id',
					'region_level0','region_level1','region_level2','address','course_id','video_id','totaltime'
				   ],
			'ob' => ['plan_id'=>'asc']
				   
		];
		if(!empty($this->paramsInfo['params']['classId'])){
			$params['q']['class_id'] = (int)$this->paramsInfo['params']['classId'];
		}

		$seekPlan = seek_api::seekPlan($params);
		if (empty($seekPlan->data)) return $this->setMsg(3002);
		
		
		foreach($seekPlan->data as $val){
			$planIdArr[] = $val->plan_id;
		}
		
		$videoInfo = array();
		$videoRes = course_plan_api::hasVideo($planIdArr);
		if($videoRes->code == 0){
			foreach($videoRes->result as $val){
				$videoInfo[$val->planId] = [
					'hasVideo'  => $val->hasVideo,
					'totalTime' => $val->totalTime
				];
			}
		}
		
		$planIds  = implode(',', $planIdArr);
		$tasksRes = live_file::getUploadTasks(array('planIds'=>$planIds));
		$taskStatus = array();
		if(!empty($tasksRes)){
			foreach($tasksRes as $val){
				$taskStatus[$val->plan_id] = $val;
			}
		}
		
		foreach($seekPlan->data as $val){
			if($val->course_type==3){
				$regionLevel0 = !empty(region_geo::$region[$val->region_level0]) ? region_geo::$region[$val->region_level0] : '';
                $regionLevel1 = !empty(region_geo::$region[$val->region_level1]) ? region_geo::$region[$val->region_level1] : '';
                $regionLevel2 = !empty(region_geo::$region[$val->region_level2]) ? region_geo::$region[$val->region_level2] : '';
                $address[$val->plan_id] = $regionLevel0.$regionLevel1.$regionLevel2.$val->address;
			}
			$newYear = date("Y");
			$courseYear = date("Y",strtotime($val->start_time));
            if($newYear > $courseYear){
				$time[$val->course_id] = date('Y-m-d H:i',strtotime($val->start_time));
			}else{
				$time[$val->course_id] = date('m-d H:i',strtotime($val->start_time));
			}

			$data['data'][] = [
				"planId"        => $val->plan_id,
				"courseName"    => $val->course_name,
				"className"     => $val->class_name,
				"sectionId"     => $val->section_id,
				"sectionName"   => $val->section_name,
				"sectionDesc"   => $val->section_desc,
                "time"          => !empty($time[$val->course_id]) ? $time[$val->course_id] : '',
				"type"          => $val->course_type,
				"address"       => !empty($address[$val->plan_id]) ? $address[$val->plan_id] : '',
				"hasVideo"      => !empty($videoInfo[$val->plan_id]['hasVideo']) ? $videoInfo[$val->plan_id]['hasVideo'] : 0,
				"status"        => $val->status,
				"taskStatus"    => !empty($taskStatus[$val->plan_id]) ? 1 : 0,
				"supplyTeacher" => ($val->teacher_id!=$val->admin_id) ? $val->teacher_real_name : '',
                "totalTime"     => !empty($videoInfo[$val->plan_id]['totalTime']) ? $videoInfo[$val->plan_id]['totalTime'] : 0,
			];
		}

		return $this->setData($data);
	}
	
	/*
	 * PC班主任课表
	 *
	 */
	public function pageClassCourseList()
	{
		if(empty($this->paramsInfo['params']['teacherId'])){
			return $this->setMsg(1000);
		}
		$userClassId = $this->paramsInfo['params']['teacherId'];
		$page   = $this->s('page') ? (int)$this->paramsInfo['params']['page'] : 1;
        $length = $this->s('length') ? (int)$this->paramsInfo['params']['length'] : 20;
		$type   = !empty($this->paramsInfo['params']['type']) ? (int)$this->paramsInfo['params']['type'] : 0;   
		$keywords = !empty($this->paramsInfo['params']['keywords']) ? $this->paramsInfo['params']['keywords'] : '';
		$sort     = !empty($this->paramsInfo['params']['sort']) ? $this->paramsInfo['params']['sort'] : 1000;
		$sortType = [1000=>'',2000=>'total',3000=>'courseTime',4000=>''];
		$status   = !empty($this->paramsInfo['params']['status']) ? (int)$this->paramsInfo['params']['status'] : 0;
		
		//模糊搜索
		$courseIdArr = array();
		if(!empty($keywords)){
			$courseParam = [
				'q' => ['search_field'=>$keywords],
				'f' => ['course_id'],
				'p' => 1,
				'pl'=> 100
			];
			$courseRes = seek_api::seekCourse($courseParam);
			if(!empty($courseRes->data)){
				foreach($courseRes->data as $val){
					$courseIdArr[$val->course_id] = $val->course_id;
				}
			}
		}
				
		$params = [
			'userClassId'    => $userClassId,
			'courseType'     => $type,
			'courseId'       => $courseIdArr,
			'sort'           => $sortType[$sort],
			'progressStatus' => $status
		];
		$classRes = course_api::getClassAndCourseList($params,$page,$length);
		if(empty($classRes->result->items)) return $this->setMsg(3002);
		
		
		$classIds  = array();
		$courseIds = array();
		foreach($classRes->result->items as $val){
			$courseIds[$val->course_id] = $val->course_id;
			$classIds[$val->class_id]   = $val->class_id;
			$ownerId = $val->user_id;
		}

        //机构名称
        $subname = array();
        if(!empty($courseIds)){
            $courseId = implode(',', $courseIds);
            $courseParams = [
                'q' => ['course_id'=>$courseId],
                'f' => ['course_id','org_subname']
            ];
            $courseRes = seek_api::seekCourse($courseParams);
            if(!empty($courseRes->data)){
                foreach($courseRes->data as $val){
                    $subname[$val->course_id] = $val->org_subname;
                }
            }
        }

		$sectionList = course_api::planGroupSectionByCourseIds($courseIds);
        $planIdArr = array();
        foreach($classRes->result->items as $val){
            $planIdArr[] = $val->progress_plan;
        }

        //获取章节名称
        $sectionName = array();
        if(!empty($planIdArr)){
            $planIds = implode(',', $planIdArr);
            $planParams = [
                'q' => ['plan_id'=>$planIds],
                'f' => ['plan_id','section_name']
            ];
            $planRes = seek_api::seekPlan($planParams);
            foreach($planRes->data as $val){
                $sectionName[$val->plan_id] = $val->section_name;
            }
        }
		
		if(!empty($sectionList->data)){
			foreach($sectionList->data as $k=>$v){
				$sectionGroup[$v->fk_course] = $v->section_count;
			}
		}
	
		//拼装数据
		$data = [
            'page'      => $classRes->result->page,
            'totalPage' => $classRes->result->totalPage,
            'total'     => $classRes->result->totalSize
        ];
		$courseNum = teacher_api::teacherCourseNumV2($userClassId);

		foreach($classRes->result->items as $val){
			$data['data'][] = [
				'courseId'   => $val->course_id,
				'courseName' => $val->course_title,
				'courseImg'  => $this->imgUrl($val->thumb_med),
				'courseType' => $val->course_type,
				'className'  => $val->name,
				'classId'    => $val->class_id,
				'userTotal'  => $val->user_total,
				'selectCount'=> !empty($sectionGroup[$val->course_id]) ? $sectionGroup[$val->course_id] : '0',
				'planNum'    => !empty($sectionName[$val->progress_plan]) ? $sectionName[$val->progress_plan] : '第0章',
				'schedule'   => $val->progress_percent/100,
				'subname'    => !empty($subname[$val->course_id]) ? $subname[$val->course_id] : '',
				'livingNum'  => $courseNum['livingNum'],
				'recordNum'  => $courseNum['recordNum'],
				'underNum'   => $courseNum['underNum'],
				'total'      => $courseNum['total'] 
			];
		}
		
		return $this->setData($data);
	}
	
	/** PC
	 * @desc (班主任)课程详情
	 * @param courseId classId teacherId
	 * @return array
	 * @update 支持讲师下的学生 2016-12-08
	 */
	public function pagePlans()
	{
		$this->v(['courseId'=>1000,'classId'=>1000]);
		$courseId  = (int)($this->paramsInfo['params']['courseId']);
		$classId   = (int)($this->paramsInfo['params']['classId']);
		$teacherId = !empty($this->paramsInfo['params']['teacherId']) ? (int)($this->paramsInfo['params']['teacherId']) : 0;
		$lecturer  = !empty($this->paramsInfo['params']['lecturerId']) ? (int)$this->paramsInfo['params']['lecturerId'] : 0;

		//判断是否该课程下讲师
        if(!empty($lecturer)){
			$check = utility_judgeid::checkCourseTeacher($courseId, $lecturer, $teacherRes);
			if(empty($check)) return $this->setMsg(3002);
		}
		
		$params = [
			'q' => ['course_id'=>$courseId,'status'=>'1,2,3','class_id'=>$classId],
			'f' => [
					'section_name','status','section_desc','teacher_id','admin_id','admin_name','admin_real_name',
					'teacher_name','teacher_real_name','course_type','start_time','plan_id','totaltime'
				   ],
			'ob'=> ['section_order_no'=>'asc']
		];
		if(!empty($teacherId) && empty($lecturer)){
			$params['q']['admin_id'] = $teacherId;
		}

		$planRes = seek_api::seekPlan($params);
		if (empty($planRes->data)) return $this->setMsg(3002);
		
		foreach($planRes->data as $val){
			$data['data'][] = [
				'planId' => $val->plan_id,
				'sectionDesc' => $val->section_desc,
				'sectionName' => $val->section_name,
				'teacherId'   => $val->teacher_id,
				'teacherName' => !empty($val->teacher_real_name) ? $val->teacher_real_name : (!empty($val->teacher_name)?$val->teacher_name:''),
				'adminId'     => $val->admin_id,
				'adminName'   => !empty($val->admin_real_name) ? $val->admin_real_name : (!empty($val->admin_name)?$val->admin_name:''),
				'courseType'  => $val->course_type,
				'startTime'   => $val->start_time,
				'status'      => $val->status,
				'totalTime'   => $val->totaltime
			];
		}
		
		return $this->setData($data);
	}

	/** PC
	 * @desc (班主任)报名该老师下课的学生
	 * @param courseId classId teacherId
	 * @return array
	 * @update 支持讲师下的学生 2016-12-08
	 */
	public function pageStudents()
	{
		$this->v(['courseId'=>1000,'classId'=>1000]);
		$courseId = (int)($this->paramsInfo['params']['courseId']);
		$classId  = (int)($this->paramsInfo['params']['classId']);
		$page     = $this->s('page') ? (int)$this->paramsInfo['params']['page'] : 1;
        $length   = $this->s('length') ? (int)$this->paramsInfo['params']['length'] : 20;
        $teacherId = !empty($this->paramsInfo['params']['teacherId']) ? (int)($this->paramsInfo['params']['teacherId']) : 0;
        $lecturer  = !empty($this->paramsInfo['params']['lecturerId']) ? (int)$this->paramsInfo['params']['lecturerId'] : 0;

		//判断该班主任是否是该用户
		if(!empty($teacherId) && empty($lecturer)){
			$check = utility_judgeid::classBelonguid($classId,$teacherId);
			if(empty($check)) return $this->setMsg(3002);
        }
		//判断是否该课程下讲师
        if(!empty($lecturer)){
			$check = utility_judgeid::checkCourseTeacher($courseId, $lecturer, $teacherRes);
			if(empty($check)) return $this->setMsg(3002);
		}
		$params = [
			'course_ids' => $courseId,
			'class_id'   => $classId
		];
		
		$studentRet = course_api::listRegistrationBycond($params,$page,$length);
		if(empty($studentRet->data)) return $this->setMsg(3002);
		
		
		$data = [
            'page'      => $studentRet->page,
            'totalPage' => $studentRet->total,
            'total'     => $studentRet->size
        ];
		foreach($studentRet->data as $val){
			$supplier[$val->uid] = !empty($val->user_info->supplier) ? $val->user_info->supplier : '';
			$province[$val->uid] = !empty($val->user_info->province) ? $val->user_info->province : '';
			$data['data'][] = [
				'userId'    => $val->uid,
				'userName'  => !empty($val->user_info->real_name) ? $val->user_info->real_name : (!empty($val->user_info->name)?$val->user_info->name:'未设置'),
				'sex'       => !empty($val->user_info->gender) ? (($val->user_info->gender==1) ? '男' : '女' ) : '', 
				'mobile'    => !empty($val->user_info->mobile) ? $val->user_info->mobile : '',
				'address'   => $supplier[$val->uid].' '.$province[$val->uid],
				'startTime' => date('Y年m月d日 H:i',strtotime($val->create_time))
			];
		}
		
		return $this->setData($data);
	}

    //云课2.0名师列表
    public  function  pageFamousList(){
        $condition = !empty($this->paramsInfo['params']['condition']) ? explode(',', $this->paramsInfo['params']['condition']) : array();
        $cateIdArr = array('1000'=>'小学','2000'=>'初中','3000'=>'高中','4000'=>'学前');
        
        //按用户兴趣显示老师列表
        $conditionArr = array();
        if(!empty($condition)){
            foreach($condition as $v){
                //防止$condition="3000, 1000,4000";
                $v = trim($v); 
                if(!empty($cateIdArr[$v])){
                    $conditionArr[$v] = $cateIdArr[$v];
                }
            }
        }

        $cateDiffArr = array_diff($cateIdArr, $conditionArr);
        $tempA = array_flip($conditionArr);
        $tempB = array_flip($cateDiffArr);
        $cateArr  = array_flip(array_merge($tempA, $tempB));

        $data = array();
        foreach($cateArr as $key=>$val) {
            $params = [
                'q' => ['grade_id'=>$key,'course_count'=>'1,5000','teacher_status'=>1,'visiable'=>1],
                'f' => [
                    "teacher_id", "org_name", "user_status", "real_name", "name", "thumb_med", "course_count",
                    "student_count", "avg_score", "comment","org_teacher","subject","totaltime",'thumb_big'
                ],
                'p' => 1,
                'pl'=> 6,
                'ob'=>['comment'=>'desc']
            ];
            $seekTeacher = seek_api::seekTeacher($params);
            if(!empty($seekTeacher->data)){
                foreach($seekTeacher->data as $v){
                    $data[$key]['type']   = $val;
                    $data[$key]['typeId'] = $key;
                    $data[$key]['teachers'][] = [
                        "teacherId"   => $v->teacher_id,
                        "teacherName" => $v->real_name,
                        "imageUrl"    => $this->imgUrl($v->thumb_big),
                        "originName"  => !empty($v->org_teacher[0]->subname) ? $v->org_teacher[0]->subname : '',
                        "score"       => round($v->avg_score, 1),
                        "counts"      => $v->comment,
                        "lessons"     => $v->course_count,
                        "subjectName" => !empty(teacher_api::getSubjectNameV2($v->subject)) ? str_replace(',',' ',teacher_api::getSubjectNameV2($v->subject)) : '',
                        "totalTime"   => number_format($v->totaltime/3600)
                    ];
                }
            }
        }

        $data = array_values($data);
        return $this->setData($data);
    }

    public  function  pageFamousListV2(){
        $page      = !empty($this->paramsInfo['params']['page']) ? (int)$this->paramsInfo['params']['page'] : 1;
        $length    = !empty($this->paramsInfo['params']['length']) ? (int)$this->paramsInfo['params']['length'] : 20;
        $condition = !empty($this->paramsInfo['params']['condition']) ? explode(',', $this->paramsInfo['params']['condition']) : array();
        $gradeIdArr= array(6=>'4000',7=>'1000',8=>'2000',9=>'3000');
        $gradeNameArr= array(4000=>'学前',1000=>'小学',2000=>'初中',3000=>'高中');

        list($provinceId, $gradeId, $subjectId) = $condition;
        $provinceId = trim($provinceId);
        $gradeId    = !empty($gradeIdArr[trim($gradeId)]) ? $gradeIdArr[trim($gradeId)] : 0;
        if(empty($gradeId)) return $this->setMsg(1000);
        $subjectId  = trim($subjectId);

        $subjectArr = array(
            1000 => array(
                array('id'=>3,'name'=>'语文'),
                array('id'=>1,'name'=>'数学'),
                array('id'=>2,'name'=>'英语')
            ),
            2000 => array(
                array('id'=>3,'name'=>'语文'),
                array('id'=>1,'name'=>'数学'),
                array('id'=>2,'name'=>'英语'),
                array('id'=>4,'name'=>'物理'),
                array('id'=>5,'name'=>'化学')
            ),
            3000 => array(
                array('id'=>3,'name'=>'语文'),
                array('id'=>1,'name'=>'数学'),
                array('id'=>2,'name'=>'英语'),
                array('id'=>4,'name'=>'物理'),
                array('id'=>5,'name'=>'化学'),
                array('id'=>6,'name'=>'生物'),
                array('id'=>7,'name'=>'历史'),
                array('id'=>8,'name'=>'物理'),
                array('id'=>9,'name'=>'政治')
            ),
            4000 => array(
                array('id'=>27,'name'=>'早教'),
                array('id'=>28,'name'=>'幼儿园')
            )
        );
        $subject = !empty($subjectArr[$gradeId]) ? $subjectArr[$gradeId] : array();
        $data['subject'] = $subject;
        $params = [
            'q' => ['teacher_status'=>1,'visiable'=>1,'course_count'=>'1,50000','grade_id'=>$gradeId],
            'f' => [
                'teacher_id','org_name','real_name','name','thumb_med','course_count','student_count','avg_score',
                'comment','title','college','years','diploma','desc','totaltime','subject','thumb_big'
            ],
            'p' => $page,
            'pl'=> $length
        ];
        if(!empty($subjectId)){
            $params['q']['subject_id'] = $subjectId;
        }
        if(!empty($provinceId)){
            $params['q']['province'] = region_geo::$region[$provinceId];
        }
        $seekTeacher  = seek_api::seekTeacher($params);	

        //$data['data'] = array();
        if(!empty($seekTeacher->data)){ 
            $data['data'] = [
			    'page'      => $seekTeacher->page,
			    'totalPage' => ceil($seekTeacher->total/$seekTeacher->pagelength),
			    'totalSize' => $seekTeacher->total
		    ];

            foreach($seekTeacher->data as $val){
                foreach($val->subject as $v){
                    $subjectNameArr[$val->teacher_id][] = $v->subject_name;
                }
                $data['data']['list'][] = [
                    'teacherId'   => $val->teacher_id,
                    'teacherName' => !empty($val->real_name) ? $val->real_name : $val->name,
                    'teacherImg'  => $this->imgUrl($val->thumb_big),
                    'orgName'     => $val->org_name,
                    'courseCount' => $val->course_count,
                    'studentCount'=> $val->student_count,
                    'avgScore'    => $val->avg_score,
                    'commentCount'=> $val->comment,
                    'college'     => $val->college,
                    'years'       => $val->years,
                    'diploma'     => $val->diploma,
                    'desc'        => $val->desc,
                    'title'       => $val->title,
                    'gradeName'   => !empty($gradeNameArr[$gradeId]) ? $gradeNameArr[$gradeId] : '',
                    'subjectName' => !empty($subjectNameArr[$val->teacher_id]) ? implode(' ', $subjectNameArr[$val->teacher_id]) : '',
                    'totalTime'   => $val->totaltime
                ];
            }
        }

        return $this->setData($data);
    }
    /*
     * @desc 老师详情
     */
    public function pageDetail(){
        $this->v(['teacherId'=>1000]);
        $teacherId = (int)$this->paramsInfo['params']['teacherId'];
        $userId    = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;
        $page      = !empty($this->paramsInfo['params']['page']) ? (int)$this->paramsInfo['params']['page'] : 1;
        $length    = !empty($this->paramsInfo['params']['length']) ? (int)$this->paramsInfo['params']['length'] : 20;
        //老师信息
        $teacherInfo = teacher_api::getTeacherInfo($teacherId);
        if(empty($teacherInfo)) return $this->setMsg(3002);

        //教学阶段
        $gradeName = [1000=>'小学',2000=>'初中',3000=>'高中'];
        $grade = array();
        if(!empty($teacherInfo->grade_id)) {
            foreach($gradeName as $key=>$val) {
                $gradeId = $teacherInfo->grade_id;
                if(in_array($key,$gradeId)) {
                    $grade[$teacherInfo->teacher_id][$key] = $val;
                }
            }
        }

        $data['info'] = [
            'teacherId' => $teacherInfo->teacher_id,
            'name'      => $teacherInfo->real_name,
            'thumbMed'  => $this->imgUrl($teacherInfo->thumb_big),
            'diploma'   => !empty(teacher_major::$diploma[$teacherInfo->diploma]) ? teacher_major::$diploma[$teacherInfo->diploma] : '',
            'years'     => !empty($teacherInfo->years) ? $teacherInfo->years : '',
            'college'   => !empty($teacherInfo->college) ? $teacherInfo->college : '',
            'subject'   => $teacherInfo->subject,
            'desc'      => !empty($teacherInfo->desc) ? $teacherInfo->desc : '',
            'title'     => !empty($teacherInfo->title) ? $teacherInfo->title : '',
            'userTotal' => $teacherInfo->student_count,
            'score'     => round($teacherInfo->avg_score, 1),
            'courseCount'=> $teacherInfo->course_count,
            'taughtGrade'=> (!empty($grade) && !empty($grade[$teacherInfo->teacher_id]))?implode(' ',$grade[$teacherInfo->teacher_id]):'',
            'courseTotalTime' => number_format($teacherInfo->totaltime/3600,1),
            'comment'    => $teacherInfo->comment,
            'isFav'      => !empty($userId) ? teacher_api::checkTeacherFav($userId, $teacherId) : 0,
			'address'    => $teacherInfo->province ."". $teacherInfo->city
        ];

        //老师下有试看的排课
        $planParams = [
            'q' => ['teacher_id'=>$teacherId,'try'=>1],
            'f' => [
                'plan_id','course_thumb_med','section_desc','section_name','course_type','user_total'
            ],
            'pl'=> 5,
            'ob'=> ['start_time'=>'desc']
        ];
        $seekPlan = seek_api::seekPlan($planParams);
        if(!empty($seekPlan->data)){
            foreach($seekPlan->data as $val){
                $data['plan'][] = [
                    'planId'   => $val->plan_id,
                    'thumbMed' => $this->imgUrl($val->course_thumb_med),
                    'sectionDesc' => $val->section_desc,
                    'sectionName' => $val->section_name,
                    'courseType'  => $val->course_type,
                    'userTotal'   => $val->user_total
                ];
            }
        }

        //老师下课程
        $teacherCourse = teacher_api::teacherCourse($teacherId, $page, $length);
        if(!empty($teacherCourse)){
            $data['course']['page']  = $teacherCourse->page;
            $data['course']['total'] = $teacherCourse->total;
            $data['course']['pagelength'] = $teacherCourse->pagelength;
            foreach($teacherCourse->data as $val){
                $data['course']['list'][] = [
                    'courseId' => $val->course_id,
                    'title'    => $val->title,
                    'thumbMed' => $this->imgUrl($val->thumb_med),
                    'price'    => $val->price/100,
                    'userTotal'  => $val->user_total,
                    'orgSubname' => $val->org_subname,
                    'courseType' => $val->course_type,
                ];
            }
        }

        return $this->setData($data);
    }

    /*
     * @desc 设置喜欢
     */
    public function pageSetFav(){
        $userId    = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;
        $teacherId = !empty($this->paramsInfo['params']['teacherId']) ? (int)$this->paramsInfo['params']['teacherId'] : 0;
        if(empty($userId) || empty($teacherId)) return interface_func::setMsg(1021);
		if ($userId == $teacherId) return interface_func::setMsg(1025);
		
		/* 不判断是否登陆
        $user = user_api::loginedUser();
        if (!isset($user['uid']) || !(int)($user['uid']) || $userId != $user['uid']) {
            return interface_func::setMsg(1021);
        }
		*/

        if(teacher_api::checkTeacherFav($userId, $teacherId)){
            $res = teacher_api::cancelFavTeacher($userId, $teacherId);
        }else{
            $res = teacher_api::addFavTeacher($userId, $teacherId);
        }

        if (!$res) return interface_func::setMsg(1);

        return interface_func::setMsg(0);
    }
	
	/**
	 * @desc PC新版(老师下的课)
	 */
	public function pagePcCourseList(){
		$teacherId = !empty($this->paramsInfo['params']['teacherId']) ? (int)$this->paramsInfo['params']['teacherId'] : 0;
		$page      = !empty($this->paramsInfo['params']['page']) ? (int)$this->paramsInfo['params']['page'] : 1;
        $length    = !empty($this->paramsInfo['params']['length']) ? (int)$this->paramsInfo['params']['length'] : 20;
        $type      = !empty($this->paramsInfo['params']['type']) ? (int)$this->paramsInfo['params']['type'] : '1,2,3';   
        $keywords  = !empty($this->paramsInfo['params']['keywords']) ? trim($this->paramsInfo['params']['keywords']) : '';
        $sort      = !empty($this->paramsInfo['params']['sort']) ? $this->paramsInfo['params']['sort'] : 0;
        $sortType  = [1000=>'',2000=>'user_total',3000=>'course_id'];
        $status    = !empty($this->paramsInfo['params']['status']) ? (int)$this->paramsInfo['params']['status'] : 0;
        $userType  = !empty($this->paramsInfo['params']['userType']) ? (int)$this->paramsInfo['params']['userType'] : 1;

		if(empty($teacherId)) return $this->setMsg(1000);
        if($userType == 2){
		    $res  = teacher_api::getCourseByTeacherId(array('teacherId'=>$teacherId,'isAssistant'=>1));
        }else{
		    $res  = teacher_api::getTeacherCourseInfoById($teacherId);
        }
	
		//课程数
		$courseNum = teacher_api::getNumCourseByTeacherId($teacherId);
		$data['nums'] = [
			'livingNum'  => (int)$courseNum[1],
			'recordNum'  => (int)$courseNum[2],
			'underNum'   => (int)$courseNum[3],
			'total'      => count($res) 
		];
		
		//课程信息
		$courseRes = array();
		if(!empty($res)){
			foreach($res as $val){
				$courseIdArr[] = $val->fk_course;
			}
			
			$courseIds = implode(',', $courseIdArr);
			$params = [
				'q' => ['course_id'=>$courseIds,'course_type'=>$type],
				'f' => [
						'course_id','title','course_type','thumb_med','class','org_subname','user_total'
					],
				'p' => $page,
				'pl'=> $length
			];
			if(!empty($status)){
				$params['q']['progress_status'] = $status;
			}
			
			if(!empty($keywords)){
				$params['q']['title'] = $keywords;
			}
			if(!empty($sort)){
				$params['ob'] = [$sortType[$sort]=>'desc']; 
			}

			$seekCourse = seek_api::seekCourse($params);
			$courseRes  = !empty($seekCourse->data) ? $seekCourse->data : array();	
			$data['list'] = [
				'page'      => $seekCourse->page,
				'totalPage' => ceil($seekCourse->total/$seekCourse->pagelength),
				'totalSize' => $seekCourse->total
			];
		}

		//拼装数据
		if(!empty($courseRes)){	
			$classIdArr = array();
			$planIdArr  = array();
			foreach($courseRes as &$value){
				if(!empty($value->class)){
					foreach($value->class as $vc){
						$classIdArr[$vc->class_id] = $vc->class_id;
						$planIdArr[$vc->progress_plan] = $vc->progress_plan;
					}
				}
			}
			$classRes = array();
			if(!empty($classIdArr) || !empty($planIdArr)){
				$classRes = $this->getPlanInfo($classIdArr, $planIdArr);
			}
			
			foreach($courseRes as &$val){
				if(!empty($val->class)){
					foreach($val->class as $v){
						$val->classList[] = [
							'classId'     => $v->class_id,
							'className'   => $v->name,
							'sectionNum'  => !empty($classRes['planNum'][$v->class_id]) ? count($classRes['planNum'][$v->class_id]) : 0,
							'sectionName' => !empty($classRes['sectionName'][$v->progress_plan]) ? $classRes['sectionName'][$v->progress_plan] : '第0章'
						];
					}
				}

				$data['list']['data'][] = [
					'courseId'   => $val->course_id,
					'courseName' => $val->title,
					'courseImg'  => $this->imgUrl($val->thumb_med),
					'courseType' => $val->course_type,
                    'userTotal'  => $val->user_total,
					'classList'  => !empty($val->classList) ? $val->classList : array(),
					'subname'    => $val->org_subname
				];
			}
		}
		
		return $this->setData($data);
	}

    private function getPlanInfo($classIdArr, $progressPlanIdArr=array()){
        $params['f'] = ['plan_id','section_name','class_id'];

        if(!empty($classIdArr)){
            $params['q']['class_id'] = implode(',',$classIdArr);
        }

        $params['pl'] = 100000;
        $seekPlan = seek_api::seekPlan($params);

        $data = array();
        if(!empty($seekPlan->data)){
            foreach($seekPlan->data as $v){
                $data['sectionName'][$v->plan_id] = !empty($progressPlanIdArr[$v->plan_id]) ? $v->section_name : '';
                $data['planNum'][$v->class_id][$v->plan_id] = $v->plan_id;
            }
        }

        return $data;
    }
	
    /**
     * @desc  APP老师课表
     * @param string $startTime
     * @param string $endTime
     * @param int $userId
     * @return json
     */
    public function pageMyTable(){
        $startTime = !empty($this->paramsInfo['params']['startTime']) ? $this->paramsInfo['params']['startTime'] : '';
        $endTime   = !empty($this->paramsInfo['params']['endTime']) ? $this->paramsInfo['params']['endTime'] : '';
        $userId    = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;

        if(empty($startTime) || empty($endTime) || empty($userId)){
            return $this->setMsg(1000);
        }

        //验证是否是老师
        $userRes = user_api::getUser($userId);
        if(empty($userRes) || empty($userRes->types->teacher)){
            return $this->setMsg(2056);
        } 

        $params = [
            'q' => [
                'teacher_id'=>$userId,'start_time'=>"{$startTime},{$endTime}",'status'=>'1,2,3','course_type'=>'1,3'
            ],
            'f' => [
                'plan_id','course_id','start_time','end_time','class_name','course_name','section_name',
                'status','subdomain','course_thumb_med','course_type','region_level0','region_level1',
                'region_level2','address','class_id','section_desc'
            ],
            'p' => 1,
            'pl'=> 5000,
            'ob'=> ['start_time'=>'asc']
        ]; 
        $planRes = seek_api::seekPlan($params);
        if(empty($planRes->data)) return $this->setMsg(3002);

        foreach($planRes->data as $val){
            $classIdArr[$val->class_id] = $val->class_id;
        }

        //班级下排课数
        $classPlanNum = course_plan_api::getPlanNumByClassIdArr($classIdArr);

        //线下课课程地址
        $region  = region_geo::$region;
        $address = array();
        $nowTime = strtotime(date("Y-m-d"));
        foreach($planRes->data as $k=>$v){
            $planTime = strtotime(date("Y-m-d",strtotime($v->start_time)));
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
                'courseImage' => $this->imgUrl($v->course_thumb_med),
                'courseId'    => $v->course_id,
                'classId'     => $v->class_id,
                'sectionName' => $v->section_name,
                'sectionDesc' => $v->section_desc,
                'classPlanNum'=> !empty($classPlanNum[$v->class_id]) ? $classPlanNum[$v->class_id] : 0,
                'courseType'  => $v->course_type,
                'status'      => ($v->course_type==1 && ($planTime<=$nowTime)) ? 1 : 0,
                'address'     => !empty($address[$v->class_id]) ? $address[$v->class_id] : '',
                'stime'       => date('H:i', strtotime($v->start_time))
            ];
        }

        $data = array_values($data);

        return $this->setData($data);
    }

    /**
     * 小沃1.3老师详情
     * @param int $teacherId
     * @param int $userId
     * @param int $page
     * @param int $length
     */
    public function pageXiaoWoDetail(){
        $this->v(['teacherId'=>1000]);
        $teacherId = (int)$this->paramsInfo['params']['teacherId'];
        $userId    = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;
        $page      = !empty($this->paramsInfo['params']['page']) ? (int)$this->paramsInfo['params']['page'] : 1;
        $length    = !empty($this->paramsInfo['params']['length']) ? (int)$this->paramsInfo['params']['length'] : 20;
        $orgId     = !empty($this->paramsInfo['oid']) ? (int)$this->paramsInfo['oid'] : 0; 
        //老师信息
        $teacherInfo = teacher_api::getTeacherInfo($teacherId);
        if(empty($teacherInfo)) return $this->setMsg(3002);

        //教学阶段
        $gradeName = [4000=>'学前',1000=>'小学',2000=>'初中',3000=>'高中'];
        $grade = array();
        if(!empty($teacherInfo->grade_id)) {
            foreach($gradeName as $key=>$val) {
                $gradeId = $teacherInfo->grade_id;
                if(in_array($key,$gradeId)) {
                    $grade[$teacherInfo->teacher_id][$key] = $val;
                }
            }
        }

        $data['info'] = [
            'teacherId' => $teacherInfo->teacher_id,
            'name'      => $teacherInfo->real_name,
            'thumbMed'  => $this->imgUrl($teacherInfo->thumb_big),
            'diploma'   => !empty(teacher_major::$diploma[$teacherInfo->diploma]) ? teacher_major::$diploma[$teacherInfo->diploma] : '',
            'years'     => !empty($teacherInfo->years) ? $teacherInfo->years : '',
            'college'   => !empty($teacherInfo->college) ? $teacherInfo->college : '',
            'subject'   => $teacherInfo->subject,
            'desc'      => !empty($teacherInfo->desc) ? $teacherInfo->desc : '',
            'title'     => !empty($teacherInfo->title) ? $teacherInfo->title : '',
            'userTotal' => $teacherInfo->student_count,
            'score'     => round($teacherInfo->avg_score, 1),
            'courseCount'=> $teacherInfo->course_count,
            'taughtGrade'=> (!empty($grade) && !empty($grade[$teacherInfo->teacher_id]))?implode(' ',$grade[$teacherInfo->teacher_id]):'',
            'courseTotalTime' => number_format($teacherInfo->totaltime/3600,1),
            'comment'    => $teacherInfo->comment,
            'isFav'      => !empty($userId) ? teacher_api::checkTeacherFav($userId, $teacherId) : 0,
            'address'    => $teacherInfo->province ."". $teacherInfo->city
        ];

        //老师下有试看的排课
        $planParams = [
            'q' => [
                'expression'=>"@resell_org_id=".$orgId." | @org_id=".$orgId,
                'try'=>1,'teacher_id'=>$teacherId
            ],
            'f' => [
                'plan_id','course_thumb_med','section_desc','section_name','course_type','user_total'
            ],
            'pl'=> 5,
            'ob'=> ['start_time'=>'desc']
        ];
        $seekPlan = seek_api::seekPlan($planParams);
        if(!empty($seekPlan->data)){
            foreach($seekPlan->data as $val){
                $data['plan'][] = [
                    'planId'   => $val->plan_id,
                    'thumbMed' => $this->imgUrl($val->course_thumb_med),
                    'sectionDesc' => $val->section_desc,
                    'sectionName' => $val->section_name,
                    'courseType'  => $val->course_type,
                    'userTotal'   => $val->user_total
                ];
            }
        }

        //老师下课程
        $teacherCourse = teacher_api::teacherCourse($teacherId, $page, $length, $orgId);
        if(!empty($teacherCourse)){
            $data['course']['page']  = $teacherCourse->page;
            $data['course']['total'] = $teacherCourse->total;
            $data['course']['pagelength'] = $teacherCourse->pagelength;
            foreach($teacherCourse->data as $val){
                $data['course']['list'][] = [
                    'courseId' => $val->course_id,
                    'title'    => $val->title,
                    'thumbMed' => $this->imgUrl($val->thumb_med),
                    'price'    => $val->price/100,
                    'userTotal'  => $val->user_total,
                    'orgSubname' => $val->org_subname,
                    'courseType' => $val->course_type,
                ];
            }
        }

        return $this->setData($data);
    }   
	
}
