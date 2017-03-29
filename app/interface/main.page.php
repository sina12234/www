<?PHP
class interface_main extends interface_base
{
    public function pageHome()
	{
        $uid       = !empty($this->paramsInfo['params']['uid']) ? (int)($this->paramsInfo['params']['uid']) : '';
        $condition = !empty($this->paramsInfo['params']['condition']) ? $this->paramsInfo['params']['condition'] : ''; 
        $teacherSeach = !empty($this->paramsInfo['params']['teacherSeach']) ? $this->paramsInfo['params']['teacherSeach'] : ''; 
		$width = !empty($this->paramsInfo['dinfo']['rw']) ? $this->paramsInfo['dinfo']['rw'] : 0;
		$type = ($width <= 720) ? 2 : 3;
        //轮播图
        $banner = array();
        $bannerRes = index_api::getPlatformBanner($type);
        if(!empty($bannerRes->items)){
            foreach($bannerRes->items as $v){
                $banner[] = [
                    'name'  => $v->title,
                    'image' => $this->imgUrl($v->url),
                    'url'   => !empty($v->link) ? $v->link : ''
                ];
            }
        }

        //分类列表
        $types = [
            ['name'=>'小学','id'=>1000],
            ['name'=>'初中','id'=>2000],
            ['name'=>'高中','id'=>3000],
            ['name'=>'全部','id'=>0]
        ];

        //今日直播
        $lives = [
            'name'    =>'最近直播',
            'courses' => $this->todayLives($condition,5,$uid)
        ];
        
        //热门课程  
        $topsCourse = [
            'name'    => '热门课程',
            'courses' => $this->hotCourse($condition,20)
        ];

        //热门老师
        $topTeacher = [
            'name'    => '热门老师',
            'courses' => $this->hotTeacher($teacherSeach,20)
        ];
		
        //推荐课程
        $recommends = [
            'name'    => '推荐课程',
            'courses' => $this->recommendCourse($condition,20)
        ];
        
        $data = [
            'ad'     => $banner,
            'types'  => $types,
            'lives'  => $lives,
            'topsCourse' => $topsCourse,
            'topTeacher' => $topTeacher,
            'recommends' => $recommends
        ];

        return $this->setData($data);
    } 
	
	//今日直播
    private function todayLives($conditionstr,$showNum,$uid){
        $data    = array();
        if(empty($conditionstr)) return $data;
        $condition = explode(',',$conditionstr);

        $sTime = strtotime(date('Y-m-d 00:00:00'));
        $eTime = strtotime(date('Y-m-d 23:59:59'));  
		
		$startTime = date('Y-m-d 00:00:00').','.date('Y-m-d 23:59:59',strtotime('+7 days'));
		$params = [
            'q' => ['status'=>'1,2,3','admin_status'=>1,'course_type'=>1,'start_time'=>$startTime,'org_status'=>1],
            'f' => [
                    'course_id','plan_id','class_name','class_id','section_name','teacher_name',
                    'teacher_real_name','status','start_time','live_public_type','course_name',
                    'second_cate','third_cate','try'
                   ],
			'ob'=> ['start_time'=>'asc'],
            'p' => 1,
            'pl'=> 10
        ];
		
        
        $planRes = seek_api::seekPlan($params);
		if(empty($planRes->data)) return $data;
        $secondIdArr = array();
        $reviewArr   = array();
        $lives       = array();
		$courseIdArr = array();
		
		//三级分类下直播课
        foreach($planRes->data as $v){
            if(in_array($v->third_cate,$condition)){
                $time = strtotime($v->start_time);
                if($time > $sTime && $time < $eTime){
                    $courseIdArr[] = $v->course_id;
                    $secondIdArr[$v->second_cate] = $v->second_cate;
					$lives[] = $v->plan_id;
					break;
                }
            }
        }

		//二级分类下直播课
        if(empty($lives)){
            foreach($planRes->data as $v){
                if(in_array($v->second_cate,$secondIdArr)){
                    $time1 = strtotime($v->start_time);
                    if($time1 > $sTime && $time1 < $eTime){
                        $courseIdArr[] = $v->course_id;
                        $lives[] = $v->plan_id;
						break;
                    }
                }
            }
        }

        //报名信息
        $userCourse = array();
        if($uid > 0){
            $regRes = interface_user_api::getUserRegCourse($uid, $courseIdArr);
            if(!empty($regRes['items'])){
                foreach($regRes['items'] as $v){
                    $userCourse[$v['fk_course']] = $v['fk_course'];
                }
            }
        }
        $liveData = array();
		foreach($planRes->data as $v){
			if(in_array($v->plan_id,$lives)){
				$liveData[] = [
					'planId'      => $v->plan_id,
					'classId'     => $v->class_id,
					'className'   => $v->class_name,
					'setionName'  => $v->section_name,
					'courseId'    => $v->course_id,
					'courseName'  => $v->course_name,
					'teacherName' => !empty($v->teacher_real_name) ? $v->teacher_real_name : (!empty($v->teacher_name) ? $v->teacher_name : ''),
					'status'      => $v->status,
					'time'        => date('H:i',strtotime($v->start_time)),
					'userStatus'  => !empty($userCourse[$v->course_id]) ? 1 : 0,
					'livePublicType' => $v->try
				];
			}
		}

		$count = $showNum - count($lives);

		//取精彩回放
		$revTime = date('Y-m-d 23:59:59',strtotime('-7 days')).','.date('Y-m-d 00:00:00');
		$revParams = [
            'q' => ['status'=>'3','admin_status'=>1,'course_type'=>1,'start_time'=>$revTime,'third_cate'=>$conditionstr,'org_status'=>1],
            'f' => [
                    'course_id','plan_id','class_name','class_id','section_name','teacher_name',
                    'teacher_real_name','status','start_time','live_public_type','course_name',
                    'second_cate','third_cate','try'
                   ],
			'ob'=> ['start_time'=>'asc'],
            'p' => 1,
            'pl'=> 10
        ];
        $revPlanRes = seek_api::seekPlan($revParams);
		
		if(!empty($revPlanRes->data)){
			foreach($revPlanRes->data as $v){
			   $reviewArr[] = [
					'planId'      => $v->plan_id,
					'classId'     => $v->class_id,
					'className'   => $v->class_name,
					'setionName'  => $v->section_name,
					'courseId'    => $v->course_id,
					'courseName'  => $v->course_name,
					'teacherName' => !empty($v->teacher_real_name) ? $v->teacher_real_name : (!empty($v->teacher_name) ? $v->teacher_name : ''),
					'status'      => $v->status,
					'time'        => date('H:i',strtotime($v->start_time)),
					'userStatus'  => !empty($userCourse[$v->course_id]) ? 1 : 0,
					'livePublicType' => $v->try
				];
			}
		}
		
		$review = array_slice($reviewArr,0,$count);
        $data   = array_merge($review,$liveData);
		if(empty($data)) return $data;

		//获取课程价格 
        foreach($data as $v){
            $courseId[] = $v['courseId'];
        }
        $courseIdStr = implode(',',$courseId);
        $param = [
            'q' => ['course_id'=>$courseIdStr,'org_status'=>1],
            'f' => ['course_id','price','fee_type','thumb_med']
        ];
        $courseRes = seek_api::seekCourse($param);
        
        foreach($courseRes->data as $v){
            $course[$v->course_id] = [
                'price'      => $v->price/100,
                'feeType'    => $v->fee_type,
                'courseImage'=> $this->imgUrl($v->thumb_med)
            ];
        }
		
		foreach($data as &$val){
            $val['price']   = $course[$val['courseId']]['price'];
            $val['feeType'] = $course[$val['courseId']]['feeType'];
            $val['courseImage'] = $course[$val['courseId']]['courseImage'];
        }
	
        return $data;
    }
    
    
    //热门课程
    private function hotCourse($condition,$showNum){
        $data    = array();
        if(empty($condition)) return $data;
        
        $thirdQuery = ['status'=>'1,2,3','admin_status'=>1,'third_cate'=>$condition,'org_status'=>1];
		$fields     = ['course_id','title','thumb_med','second_cate','third_cate'];
		$ob         = ['start_time'=>'desc','vv'=>'desc'];
		
		$thirdCourse = seek_api::seekcourse(array('q'=>$thirdQuery,'f'=>$fields,'ob'=>$ob,'p'=>1,'pl'=>$showNum));
		$thirdCourseData = $thirdCourse->data;
		if(empty($thirdCourseData)) return $data;
		
		//二级分类的课程
		$secondCate = array_reduce($thirdCourseData, create_function('$v,$w', '$v[$w->second_cate]=$w->second_cate;return $v;'));
		$count = count($thirdCourseData);
		$secondCourseData = array();
		if($count < $showNum && $secondCate){
            $secondCate = implode(',',$secondCate);
			$length = $showNum - $count;
			$secondQuery = ['status'=>'1,2,3','admin_status'=>1,'second_cate'=>$secondCate,'org_status'=>1];
			$secondCourse = seek_api::seekcourse(array('q'=>$secondQuery,'f'=>$fields,'ob'=>$ob,'p'=>1,'pl'=>$length));
			$secondCourseData = $secondCourse->data;
		}
		
		//合并数据
		$courseData = array_merge($thirdCourseData,$secondCourseData);
		if(empty($courseData)) return $data;
		
		foreach($courseData as $v){
			$data[] = [
				'courseId'   => $v->course_id,
				'courseName' => $v->title,
				'courseImage'=> $this->imgUrl($v->thumb_med)
			];
		}
		
		return $data;
		
    }
    
    //热门老师
    private function hotTeacher($condition,$showNum){
        
        $data = array();        
        $params = [
            'q'  => ['teacher_status'=>1,'visiable'=>1,'course_count'=>'1, 5000','student_count'=>'1,5000'],
            'f'  => ['teacher_id','grade_id','real_name','name','thumb_big','student_count','subject'],
            'p'  => 1,
            'ob' => ['student_count'=>'desc'],
            'pl' => 1000
        ];
        
        $teacherRes = seek_api::seekTeacher($params);
        empty($teacherRes->data) && $data;
        
        $teacherIdArr = array();
        $gradeIdArr   = array();
        $gradeTeacher = array();
        $tIdArr       = array();
        $gData        = array();
        $starData     = array();
        
        //根据年级id获取老师信息
        if(!empty($condition)){
            $condition = explode(',',$condition);
            
            foreach($teacherRes->data as $v){
                if(!empty($v->grade_id)){
                    $gradeIdArr[$v->teacher_id] = $v->grade_id;
                }
            }
            
            foreach($gradeIdArr as $k=>$v){
                foreach($condition as $vv){
                    if(in_array($vv,$v)){
                        $teacherIdArr[$k] = $k;
                    }
                }
            }
            
            if(!empty($teacherIdArr)){
                foreach($teacherRes->data as $v){
                    if(in_array($v->teacher_id,$teacherIdArr)){
                        $tIdArr[$v->teacher_id] = $v->teacher_id;
                        $gradeTeacher[] = [
                            'teacherId'    => $v->teacher_id,
                            'teacherName'  => !empty($v->real_name) ? $v->real_name : (!empty($v->name) ? $v->name : ''),
                            'teacherImage' => $this->imgUrl($v->thumb_big),
                            'studentNum'   => $v->student_count,
                            'subjects'     => !empty(teacher_api::getSubjectName($v->subject))?str_replace(',',' ',teacher_api::getSubjectName($v->subject)):'',
                        ];
                    }
                }
            }
            
            $gData  = array_slice($gradeTeacher,0,$showNum);
        }
        $gCount = count($gData);
        
        //明星教师
        if($gCount < $showNum){
            foreach($teacherRes->data as $v){
                if(!in_array($v->teacher_id,$tIdArr)){
                    $starArr[] = [
                        'teacherId'    => $v->teacher_id,
                        'teacherName'  => !empty($v->real_name) ? $v->real_name : (!empty($v->name) ? $v->name : ''),
                        'teacherImage' => $this->imgUrl($v->thumb_big),
                        'studentNum'   => $v->student_count,
                        'subjects'     => !empty(teacher_api::getSubjectName($v->subject))?str_replace(',',' ',teacher_api::getSubjectName($v->subject)):'',
                    ];
                }
            }
            
            if(!empty($starArr)){
                $count     = $showNum - $gCount;
                $starData  = array_slice($starArr,0,$count);
            }
        }

        $data = array_merge($gData,$starData);
        return $data;
    }
    
    //推荐课程
    private function recommendCourse($condition,$showNum)
	{
        $data    = array();
		//获取推荐课程id
        $courseIdStr = '';
        if(!empty($condition)){
            $recommendRes = course_api::recommendByCateId($condition);
            
            $cateIdStr = '';
            if(!empty($recommendRes)){
                
                foreach($recommendRes as $v){
					if(!empty($v->fk_course)){
						$cateIdStr .= $v->fk_course.",";
					}
                }
				
				if(!empty($cateIdStr)){
					$courseIdStr = trim($cateIdStr,',');
				}
            }
        }
	
		//课程筛选条件
		if(empty($courseIdStr)){
			//没有推荐课程
			$query = ['status'=>'1,2,3','admin_status'=>1,'third_cate'=>$condition,'org_status'=>1];
		}else{
			$query = ['status'=>'1,2,3','admin_status'=>1,'course_id'=>$courseIdStr,'org_status'=>1];
		}
		$fields = [
			'course_id','title','thumb_med','status','fee_type','price','third_cate',
			'user_total','third_cate_name','remain_user','second_cate'
		];
		$ob = ['user_total'=>'desc'];
		
        $courseRes = seek_api::seekcourse(array('q'=>$query,'f'=>$fields,'ob'=>$ob,'p'=>1,'pl'=>20));
		
		if(empty($courseRes->data)) return $data;
		$courseData = $courseRes->data;

		//二级分类的课程
		$secondCate = array_reduce($courseData, create_function('$v,$w', '$v[$w->second_cate]=$w->second_cate;return $v;'));
		$courseResult = array();
		$count  = count($courseData);
		if($count < $showNum){
			$length = $showNum - $count;
			$cateStr       = implode(',',$secondCate);
			$secondQuery  = ['status'=>'1,2,3','admin_status'=>1,'second_cate'=>$cateStr,'org_status'=>1];
			$courseResu = seek_api::seekcourse(array('q'=>$secondQuery,'f'=>$fields,'ob'=>$ob,'p'=>1,'pl'=>$length));
			$courseResult = $courseResu->data;
		}

		//合并数据
		$mergeData = array_merge($courseData,$courseResult);
		if(empty($mergeData)) return $data;
		
		foreach($mergeData as $v){
            $data[] = [
				'courseId'    => $v->course_id,
				'courseName'  => $v->title,
				'courseImage' => $this->imgUrl($v->thumb_med),
				'status'      => $v->status,
				'feeType'     => $v->fee_type,
				'price'       => floatval($v->price / 100),
				'grade'       => $v->third_cate_name,
				'subject'     => '',
				'studentNum'  => $v->user_total
			];         
        }
		return $data;
    }
	
	//云课2.0
	public function pageHomeV2(){
		$uid       = !empty($this->paramsInfo['params']['uid']) ? (int)($this->paramsInfo['params']['uid']) : '';
        $condition = !empty($this->paramsInfo['params']['condition']) ? $this->paramsInfo['params']['condition'] : '';
		$width     = !empty($this->paramsInfo['dinfo']['rw']) ? $this->paramsInfo['dinfo']['rw'] : 0;
		
		//轮播图
		$banner = $this->getBanners($width);
		
		//分类列表
        $types = [
            ['name'=>'小学','id'=>7],
            ['name'=>'初中','id'=>8],
            ['name'=>'高中','id'=>9],
            ['name'=>'全部','id'=>0]
        ];
		
		//直播课堂
		$lives = interface_planApi::getLives($uid);
		
		//机构推送课程
		$recommends = array();
		$blockArr   = array(1,2,3,4,5,6,7,8,9,10,11,12);
		$block = index_api::getBlockInfo();
		$blockList = array();
		if(!empty($block)){
			foreach($block as $k=>$v){
				$blockList[$v->pk_block] = $v;
			}
		}
		//小学全部: 1
		$course_info1 					= org_api::platformBlockOfData($blockList,$blockArr[0]);
		$recommends[] 					= $course_info1;
		//初中全部:5
		$course_info5 					= org_api::platformBlockOfData($blockList,$blockArr[4]);
		$recommends[] 					= $course_info5;
		//高中全部 :9
		$course_info9 					= org_api::platformBlockOfData($blockList,$blockArr[8]);
		$recommends[] 					= $course_info9;
		//兴趣的课程
		$interests = $this->getInterests($condition);

		$data = [
            'ad'     		=> $banner,
            'types'  		=> $types,
            'lives'  		=> $lives,
			'recommends' 	=> $recommends,
            'interests' 	=> $interests
        ];
		
		return $this->setData($data);
	}
	
	//banner图列表
	private function getBanners($width){
		$type = ($width <= 720) ? 2 : 3;
		
		$data = array();
		$bannerRes = index_api::getPlatformBanner($type);
		if(!empty($bannerRes->items)){
            foreach($bannerRes->items as $v){
                $data[] = [
                    'name'  => $v->title,
                    'image' => $this->imgUrl($v->url),
                    'url'   => !empty($v->link) ? $v->link : ''
                ];
            }
        }
		
		return $data;
	}
	
	//兴趣的课程
	private function getInterests($thirdCateIds){
		$data = array();
		if(empty($thirdCateIds)) return $data;
		
		$thirdCateIdArr = explode(',', $thirdCateIds);
		foreach($thirdCateIdArr as $val){
			$params = [
				'q' => ['third_cate'=>$val,'status'=>'1,2,3','admin_status'=>1,'org_status'=>1],
				'f' => [
						'course_id','title','org_subname','user_total','third_cate',
						'second_cate','thumb_med','third_cate_name','start_time'
					],
				'p' => 1,
				'pl'=> 4
			];
			$seekCourse = seek_api::seekCourse($params);
			if(!empty($seekCourse->data)){
				foreach($seekCourse->data as $v){
					$data[$val]['id']   = $val;
					$data[$val]['name'] = $v->third_cate_name;
					$data[$val]['pId']  = $v->second_cate;
					$data[$val]['list'][] = [
						'courseId'   => $v->course_id,
						'courseName' => $v->title,
						'imgurl'     => $this->imgUrl($v->thumb_med),
						'userTotal'  => $v->user_total
					]; 
				}
			}
		}
		
		$data = array_values($data);
		return $data;
    }

	public function pageHomeV3(){
		$userId    = !empty($this->paramsInfo['params']['uid']) ? (int)($this->paramsInfo['params']['uid']) : '';
        $condition = !empty($this->paramsInfo['params']['condition']) ? explode(',', $this->paramsInfo['params']['condition']) : array(0,0,0);
		$width     = !empty($this->paramsInfo['dinfo']['rw']) ? $this->paramsInfo['dinfo']['rw'] : 0;

        list($provinceId, $secondId, $threeId) = $condition;
        $secondArr   = array('7'=>1000,'8'=>2000,'9'=>3000);
        $secondId    = (int)$secondId;

		//轮播图
		$banner = $this->getBanners($width);
	    
        //科目列表
        $subjectArr = array(
            7 => array(
                array('id'=>3,'name'=>'语文'),
                array('id'=>1,'name'=>'数学'),
                array('id'=>2,'name'=>'英语')
            ),
            8 => array(
                array('id'=>3,'name'=>'语文'),
                array('id'=>1,'name'=>'数学'),
                array('id'=>2,'name'=>'英语'),
                array('id'=>4,'name'=>'物理'),
                array('id'=>5,'name'=>'化学')
            ),
            9 => array(
                array('id'=>3,'name'=>'语文'),
                array('id'=>1,'name'=>'数学'),
                array('id'=>2,'name'=>'英语'),
                array('id'=>4,'name'=>'物理'),
                array('id'=>5,'name'=>'化学'),
                array('id'=>6,'name'=>'生物'),
                array('id'=>7,'name'=>'历史'),
                array('id'=>8,'name'=>'地理'),
                array('id'=>9,'name'=>'政治')
            ),
        );
        $subject = !empty($subjectArr[$secondId]) ? $subjectArr[$secondId] : array();

        $host = interface_func::httpHeader().':';
        //筛选列表
        $types = array(
            array('name'=>'直播课','img'=>$host.utility_cdn::img("assets_v2/interface/yunke/img/{$this->paramsInfo['u']}/01.png"),'condition'=>array('courseType'=>1)),
            array('name'=>'录播课','img'=>$host.utility_cdn::img("assets_v2/interface/yunke/img/{$this->paramsInfo['u']}/02.png"),'condition'=>array('courseType'=>2)),
            array('name'=>'免费课','img'=>$host.utility_cdn::img("assets_v2/interface/yunke/img/{$this->paramsInfo['u']}/03.png"),'condition'=>array('feeType'=>0)),
        );

        //用户的最近直播
        $userWillPlan = '';
        if(!empty($userId)){
            $startTime = date('Y-m-d', time()).' 00:00:00';
            $userLivingCourse = course_api::getUserLivingCourse($userId, 1, $startTime);
            if(!empty($userLivingCourse)){
                foreach($userLivingCourse as $val){
                    $courseIdArr[$val->fk_course] = $val->fk_course;
                    $classIdArr[$val->fk_class]   = $val->fk_class;
                }

                $courseIds = implode(',', $courseIdArr);
                $classIds  = implode(',', $classIdArr);
                $sTime     = date('Y-m-d H:i:s');
                $eTime     = date('Y-m-d H:i:s', strtotime('next year'));
                $planParams = [
                    'q' => [
                        'course_id'=>$courseIds, 'class_id'=>$classIds, 'status'=>'1,2', 'course_type'=>1,
                        'start_time'=>"$sTime,$eTime"
                    ],
                    'f' => ['start_time','status'],
                    'pl'=> 1,
                    'ob'=> ['start_time'=>'asc']
                ];
                $seekPlan = seek_api::seekPlan($planParams);
                if(!empty($seekPlan->data)){
                    $userWillPlan = [
                        'willTime' => date('Y-m-d H:i',strtotime($seekPlan->data[0]->start_time)),
                        'status'   => $seekPlan->data[0]->status
                    ];
                }
            }
        }

        //直播试听
        $trySeeList = array();
        $soonTime = date('Y-m-d 23:59:59', strtotime('-12 month')).','.date('Y-m-d H:i:s');
        $paramsPlan = [
            'q' => [
                'status'=>'2,3','admin_status'=>1,'course_type'=>'1','try'=>1,'start_time'=>$soonTime,
            ],
            'f' => [
                'course_name','course_thumb_med','section_name','section_desc','start_time','plan_id',
                'class_name','class_id','course_id','status'
            ],
            'ob'=> ['start_time'=>'desc'],
            'pl'=> 10
        ];

        if(!empty($provinceId)){
            $paramsPlan['q']['province_id'] = $provinceId;
        }
        $planSeek = seek_api::seekPlan($paramsPlan);
        if(!empty($planSeek->data)){
            foreach($planSeek->data as $val){
                $d = date('n-j', strtotime($val->start_time));
                $trySee[$d]['time'] = $d;
                $trySee[$d]['list'][] = [
                    'courseId'   => $val->course_id,
                    'planId'     => $val->plan_id,
                    'courseName' => $val->course_name,
                    'className'  => $val->class_name,
                    'imgUrl'     => $this->imgUrl($val->course_thumb_med),
                    'sectionName'=> $val->section_name,
                    'sectionDesc'=> $val->section_desc,
                    'status'     => $val->status,
                    'stime'      => date('Y-m-d H:i', strtotime($val->start_time))
                ];
            }
            $trySeeList = array_values($trySee);
        }

        //精选课程
        $courseList = array();
        $courseParams = [
            'q' => ['third_cate'=>$threeId,'fee_type'=>1,'status'=>'1,2','admin_status'=>1],
            'f' => ['course_id','title','thumb_med','user_total','org_subname'],
            'p' => 1,
            'pl'=> 8,
            'ob'=> ['user_total'=>'desc']
        ];
        if(!empty($provinceId)){
            $courseParams['q']['province_id'] = $provinceId;
        }

        $seekCourse = seek_api::seekCourse($courseParams);
        if(!empty($seekCourse->data)){
            foreach($seekCourse->data as $val){
                $courseList[] = [
                    'courseId'   => $val->course_id,
                    'courseName' => $val->title,
                    'courseImg'  => $this->imgUrl($val->thumb_med),
                    'subname'    => $val->org_subname,
                    'userTotal'  => $val->user_total
                ];
            }
        }

        //精选老师
        $teacherList = array();
        if(!empty($secondArr[$secondId])){
            $teacherParams = [
                'q' => ['grade_id'=>$secondArr[$secondId],'platform_status'=>1],
                'f' => [
                    'teacher_id','thumb_med','comment','name','totaltime','years','subject','grade'
                ],
                'p' => 1,
                'pl'=> 10,
                'ob'=> ['comment'=>'desc']
            ];
            if(!empty($provinceId)){
                $teacherParams['q']['province'] = region_geo::$region[$provinceId];
            }
            $seekTeacher = seek_api::seekTeacher($teacherParams);

            if(!empty($seekTeacher->data)){
                $tmpGrade = array(7=>'小学',8=>'初中',9=>'高学',);
                foreach($seekTeacher->data as $val){
                    $teacherList[] = [
                        'teacherId'   => $val->teacher_id,
                        'teacherName' => $val->name,
                        'teahcerImg'  => $this->imgUrl($val->thumb_med),
                        'totalTime'   => $val->totaltime,
                        'comment'     => $val->comment,
                        'years'       => $val->years,
                        'subject'     => !empty(teacher_api::getSubjectNameV2($val->subject))?str_replace(',',' ',teacher_api::getSubjectNameV2($val->subject)):'',
                        'grade'       => !empty($tmpGrade[$secondId]) ? $tmpGrade[$secondId] : ''
                    ];
                }
            }
        }

        //精选机构
        $orgList = array();
        $orgParams = [
            'q' => ['status'=>1],
            'f' => ['org_id','name','subname','thumb_med','desc','course_count','visiable_teacher_count'],
            'p' => 1,
            'pl'=> 4
        ];
        if(!empty($provinceId)){
            $orgParams['q']['province_id'] = $provinceId;
        }
        $seekOrg = seek_api::seekOrg($orgParams);
        if(!empty($seekOrg->data)){
            foreach($seekOrg->data as $val){
                $orgList[] = [
                    'orgId'      => $val->org_id,
                    'orgName'    => $val->name,
                    'orgsubName' => $val->subname,
                    'orgImg'     => $this->imgUrl($val->thumb_med),
                    'desc'       => $val->desc,
                    'courseNum'  => $val->course_count,
                    'teacherNum' => $val->visiable_teacher_count
                ];
            }
        }
        
        $data = [
            'banner'  => $banner,
            'subject' => $subject,
            'types'   => $types,
            'userWillPlan'=> $userWillPlan,
            'trySeeList'  => $trySeeList,
            'courseList'  => $courseList,
            'teacherList' => $teacherList,
            'orgList'     => $orgList
        ];

        return $this->setData($data);
    }
}

