<?php

class interface_org extends interface_base
{
	/**
     * 本地机构列表
     * @param int $page
     * @param int $length
     * @param int $provinceId
     * @reutrn array
	 */
    public function pageList(){
		$page   = empty($this->paramsInfo['params']['page']) ? 1 : (int)$this->paramsInfo['params']['page'];
        $length = empty($this->paramsInfo['params']['length']) ? 20 : (int)$this->paramsInfo['params']['length'];
        $sort   = !empty($this->paramsInfo['params']['sort']) ? (int)$this->paramsInfo['params']['sort'] : 1000;
        $provinceId = !empty($this->paramsInfo['params']['provinceId']) ? (int)$this->paramsInfo['params']['provinceId'] : 0;

        $sortArr = [
            '1000' => ['org_id'=>'desc'],
            '2000' => ['course_count'=>'desc'],
            '3000' => ['visiable_teacher_count'=>'desc']
        ];

        $params = [
            'q' => ['status'=>1],
            'f' => [
                'org_id','name','subname','subdomain','thumb_med','desc','province',
                'city','address','student_count','visiable_teacher_count','course_count'
            ],
            'p' => $page,
            'pl'=> $length,
            'ob'=> !empty($sortArr[$sort]) ? $sortArr[$sort] : array('org_id'=>'desc')
        ];

        if(!empty($provinceId)){
            $params['q']['province_id'] = $provinceId;
        }
        $organizReg = seek_api::seekOrg($params);
        if(empty($organizReg->data)) return $this->setMsg(3002);

        $data = [
			'page'      => $organizReg->page,
			'total'     => $organizReg->total,
			'pageTotal' => ceil($organizReg->total/$organizReg->pagelength)
        ];

        foreach($organizReg->data as $val){
            $data['data'][] = [
                'orgId'     => $val->org_id,
                'name'      => $val->name,
                'subname'   => $val->subname,
                'subdomain' => $val->subdomain,
                'orgImg'    => $this->imgUrl($val->thumb_med),
                'studnetNum'=> $val->student_count,
                'teacherNum'=> $val->visiable_teacher_count,
                'courseNum' => $val->course_count,
                'cityName'  => $val->city,
                'address'   => $val->address,
                'provinceName' => $val->province,
            ];
        }

        return $this->setData($data);
    }
	
	/*
	 * 机构下的老师
     * @param int $orgId
     * @param int $page
     * @param int $length
     * @return array
	 */
    public function pageTeacherList(){
        $this->v(['orgId'=>1000]);
		$page   = empty($this->paramsInfo['params']['page']) ? 1 : (int)$this->paramsInfo['params']['page'];
        $length = empty($this->paramsInfo['params']['length']) ? 20 : (int)$this->paramsInfo['params']['length'];
		$orgId  = (int)($this->paramsInfo['params']['orgId']);
		$gradeName = [1000=>'小学',2000=>'初中',3000=>'高中'];
		
		$params   = [
            'q'  => ['org_id'=>$orgId,'visiable'=>1,'platform_status'=>1],
            'f'  => [
                'teacher_id','name','thumb_med','totaltime','subject','grade_id','avg_score','student_count',
                'course_count','score_user_count'
            ],
            'pl' => $length,
			'p'  => $page
        ]; 
        $teacherReg = seek_api::seekTeacher($params);
        if (empty($teacherReg->data)) return $this->setMsg(3002);
        
        $data = [
			'page'      => $teacherReg->page,
			'total'     => $teacherReg->total,
			'pageTotal' => $teacherReg->pagelength
        ];
        foreach($teacherReg->data as $v){
			if(!empty($v->grade_id)){
				foreach($gradeName as $key=>$val){
					$gradeId = $v->grade_id;
					if(in_array($key,$gradeId)){
						$grade[$v->teacher_id][$key] = $val;
					}
				}
			}
			$data['data'][] = [
				"teacherId"   => $v->teacher_id,
				"name"        => $v->name,
				"thumbMed"    => $this->imgUrl($v->thumb_med),
				"subjectName" => !empty(teacher_api::getSubjectNameV2($v->subject))?str_replace(',',' ',teacher_api::getSubjectNameV2($v->subject)):'',
				"userTotal"   => $v->student_count,
                "courseTotal" => $v->course_count,
                "scoreTotal"  => $v->score_user_count,
				"score"       => floatval($v->avg_score/10),
				"grade"       => (!empty($grade) && !empty($grade[$v->teacher_id]))?implode(' ',$grade[$v->teacher_id]):'',
				"courseTotalTime" => (string)$v->totaltime
			];
		}

        return $this->setData($data);
    }
	
	/*
	 * 机构下的课程
     * @param int $orgId
     * @param int $page
     * @param int $length
     * @return array
	 */
    public function pageCourseList(){
		$this->v(['orgId'=>1000]);
		$gradeArr  = array(3000,2000,1000);
		$page   = empty($this->paramsInfo['params']['page']) ? 1 :(int)$this->paramsInfo['params']['page'];
        $length = empty($this->paramsInfo['params']['length']) ? 20 : (int)$this->paramsInfo['params']['length'];
        $orgId  = (int)($this->paramsInfo['params']['orgId']);

    	$params   = [
            'q'  => ['org_id'=>$orgId,'admin_status'=>1,'status'=>'1,2,3'],
            'f'  => [
                'course_id','title','thumb_med','user_total','comment','avg_score','status','fee_type','price',
                'course_type','third_cate_name','course_attr'
            ],
            'pl' => $length,
			'p'  => $page
        ]; 
        $courseReg = seek_api::seekCourse($params);
		if (empty($courseReg->data)) return $this->setMsg(3002);
        
        $data = [
			"total"     => $courseReg->total,
			"page"      => $courseReg->page,
			"pageTotal" => ceil($courseReg->total/$courseReg->pagelength)
        ];
        foreach($courseReg->data as $val){
            if(!empty($val->course_attr)){
                foreach($val->course_attr as $v){
                    if(!empty($v->attr_value)){
                        foreach($v->attr_value as $name){
                            $attrArr[$val->course_id][] = $name->attr_value_name;
                        }
                    }
                }
            }
			$data['data'][] = [
				"courseId"  => $val->course_id,
                "title"     => $val->title,
                "thumbMed"  => !empty($val->thumb_med) ? $this->imgUrl($val->thumb_med) : '',
                'subject'   => !empty($attrArr[$val->course_id]) ? implode(' ',$attrArr[$val->course_id]) : '',
                "grade"     => $val->third_cate_name,
                "userTotal" => $val->user_total,
                "comment"   => $val->comment,
                "score"     => $val->avg_score/10,
                "status"    => $val->status,
                "courseType"=> $val->course_type,
                "feeType"   => $val->fee_type,
                "price"     => floatval($val->price/100)
			];
		}
        
        return $this->setData($data);
    }

	/*
	 * 机构下的老师总数
	 * @params $orgStr 机构id
	 */
	private function getTeacherByOid($orgStr)
	{
		$total = array();
		$org   = array();
		$params   = [
            'q'  => ['org_id'=>$orgStr,'visiable'=>1],
            'f'  => ['teacher_id','org_id'],
            'pl' => 1000
        ]; 
        $res_teacher = seek_api::seekTeacher($params);
		
		if(empty($res_teacher->data)){
			$total = array();
		}
		
		foreach($res_teacher->data as $val)
		{
			foreach($val->org_id as $v)
			{
				$org[$v][] = $val->teacher_id;
			}
		}
		
		foreach($org as $k=>$v)
		{
			$total[$k] = (string)count($v);
		}
		
		return $total;
	}
	
	/*
	 * 机构下的课程总数评论数
	 * @params 机构所有者id
	 */
	private function getCourseByOid($ownerStr)
    {
        if($ownerStr == '') return array();
		$total = array();
		$params   = [
            'q'  => ['user_id'=>$ownerStr,'admin_status'=>1],
            'f'  => ['user_id','course_id','comment','user_total'],
            'pl' => 10000
        ]; 
        $res_course = seek_api::seekCourse($params);

		if(empty($res_course->data)){
			$total = array();
		}
	
		$course    = array_reduce($res_course->data, create_function('$v,$w', '$v[$w->user_id][]=$w->course_id;return $v;'));
		$comment   = array_reduce($res_course->data, create_function('$v,$w', '$v[$w->user_id][]=$w->comment;return $v;'));
		$userTotal = array_reduce($res_course->data, create_function('$v,$w', '$v[$w->user_id][]=$w->user_total;return $v;'));
		
		if($course){
			foreach($course as $k=>$v)
			{
				$total['courseNum'][$k] = (string)count($v);
			}
		}
		
		if($comment){
			foreach($comment as $k=>$v)
			{
				$total['commentNum'][$k] = (string)array_sum($v);
			}
		}
		
		if($userTotal){
			foreach($userTotal as $k=>$v)
			{
				$total['userNum'][$k] = (string)array_sum($v);
			}
		}
		return $total;
    }

    private function mysort($data,$orderBy,$field){
		$sort = [
			'direction' => $orderBy,
			'field'     => $field
		];

		$arrSort = array();

		foreach($data AS $uniqid => $row){
			foreach($row AS $key=>$value){
				$arrSort[$key][$uniqid] = $value;
			}
		}

		if($sort['direction']){
			array_multisort($arrSort[$sort['field']], constant($sort['direction']), $data);
		}

		return $data;
	}
	/*app首页
		oid 		机构id
		secondId 	二级分类id
		thirdId 	三级id     
	*/
    public function pageGetAppHome(){
        $orgId = !empty($this->paramsInfo['oid'])?intval($this->paramsInfo['oid']):0; //机构id
        $secondId = empty($this->paramsInfo['params']['secondId'])?"":trim($this->paramsInfo['params']['secondId']);
        $thirdId = empty($this->paramsInfo['params']['thirdId'])?"":trim($this->paramsInfo['params']['thirdId']);
        $width = empty($this->paramsInfo['dinfo']['rw'])?0:intval($this->paramsInfo['dinfo']['rw']);
        $secondIds = array();
        $thirdIds =  array();
        if (preg_match('/\d+(,\d+)*$/', $secondId)) {
            $secondIds = explode(",",$secondId);
        }
        if (preg_match('/\d+(,\d+)*$/',$thirdId)){
            $thirdIds = explode(",",$thirdId);
        }
        $orgInfo = org_api::getOrgInfo($orgId);
        if(empty($orgInfo)){
            return $this->setMsg(1000);
        }
        $setSecondCate = org_api::getorgCateInfo($orgId,$orgInfo->user_owner_id,0);
        if(empty($secondIds)){
            $setSecondCate = org_api::getorgCateInfo($orgId,$orgInfo->user_owner_id,0);
            if($setSecondCate->data){
                foreach($setSecondCate->data as $setSecondCateInfo){
                    $secondIds[] = $setSecondCateInfo->pk_cate;
                }
            }
        }
        $cateids= !empty($secondIds) ? array_merge($secondIds,$thirdIds):array_merge($thirdIds,$secondIds);
        $data=array();
        $data['ad'] = array();//机构app广告图
        $data['special'] =array();
        $xiaowoLists = user_organization::xiaowoOrgList(array("fk_org"=>$orgId));
        if($xiaowoLists){
            foreach($xiaowoLists as $xiaowoList){
                if($xiaowoList->types==1){
                    $data['ad'][] = [
                        'name'  => !empty($xiaowoList->title) ? $xiaowoList->title : '',
                        'imgurl' => $width<=720?$this->imgUrl($xiaowoList->thumb_app):$this->imgUrl($xiaowoList->thumb_ipad),
                        'url'   => !empty($xiaowoList->url) ? $xiaowoList->url : '',
                    ];
                }elseif($xiaowoList->types==2){
                    $data['special'][] = array(
                        "specialId"=>$xiaowoList->pk_banner,
                        'specialName'  => !empty($xiaowoList->title) ? $xiaowoList->title : '',
                        'specialImg' => $width<=720?$this->imgUrl($xiaowoList->thumb_app):$this->imgUrl($xiaowoList->thumb_ipad),
                        'specialUrl'   => !empty($xiaowoList->url) ? $xiaowoList->url : '',
                    );
                }
            }
        }
        if(!empty($thirdIds)){
            //查询三级分类id的父id
            $parentThirdIds=course_api::getParentCateByIds($thirdIds);
        }
        $data['secondIds'] = array();//二级分类
        $data['thirdIds']  = array();//三级分类
        $catelist = array();
        if(!empty($cateids)){
            $catelist = course_api::getCateByCidStr(implode(",",$cateids));
        }
        if($catelist){
            foreach($catelist as $cateinfo){
                if($cateinfo->level==2){
                    if(!in_array($cateinfo->pk_cate,$secondIds)) continue;
                    $data['secondIds'][] = array(
                        'name' => $cateinfo->name,
                        'id'   => $cateinfo->pk_cate,
                    );
                }elseif($cateinfo->level==3){
                    if(!in_array($cateinfo->pk_cate,$thirdIds)) continue;
                    $data['thirdIds'][] = array(
                        'name' => $cateinfo->name,
                        'id'   => $cateinfo->pk_cate,
                    );
                }
            }
        }
        if($data['thirdIds']){
            //查询三级分类下面的课程
            foreach($data['thirdIds'] as $thirdIdInfoKey=>$thirdIdInfo){
                $seekArr = array(
                    "f"=>array(
                        "course_id","title","create_time","thumb_med","user_total",
                    ),
                    "q" => array(
                        'third_cate'=>$thirdIdInfo['id'],
                        'admin_status'=>'1',
						'expression' => "@resell_org_id =".$orgId." | @org_id=".$orgId,
                    ),
                    "ob" => array(
                        'user_total'=>'desc',
                    ),
                    "p"=>1,
                    "pl"=>4,
                );
                $seekRet=seek_api::seekcourse($seekArr);
                $seekRets=array();
                if($seekRet->data){
                    foreach($seekRet->data as $k_seekRet=>$v_seekRet){
                        $seekRets[$k_seekRet]['courseId']=$v_seekRet->course_id;
                        $seekRets[$k_seekRet]['courseName']=$v_seekRet->title;
                        $seekRets[$k_seekRet]['imgurl']=interface_func::imgUrl($v_seekRet->thumb_med);
                        $seekRets[$k_seekRet]['userTotal']=$v_seekRet->user_total;
                        $seekRets[$k_seekRet]['resellId']=$orgId;
                    }
                }
                $data['thirdIds'][$thirdIdInfoKey]['pId']=!empty($parentThirdIds[$thirdIdInfo['id']])?$parentThirdIds[$thirdIdInfo['id']]['pCateId']:0;
                $data['thirdIds'][$thirdIdInfoKey]['list']=isset($seekRets)?$seekRets:array();
            }
        }
        $data['recommends'] = array();//机构推荐课程
        $recommend_template = user_organization::getOrgTemplate($orgInfo->user_owner_id);//推荐模板
        if($recommend_template){
            foreach($recommend_template as $k_recommend_template=>$v_recommend_template){
                $courses_info=array();
                $recommend_course=array();
                $queryArr = array();
                if(empty($v_recommend_template->course_ids)){
                    $queryField = array(
                        'first_cate'    => 'fc',
                        'second_cate'   => 'sc',
                        'third_cate'    => 'tc',
                        'course_type'   => 'course_type',
                        'attr_value_id' => 'vid',
                    );
                    $query = '';
                    foreach($v_recommend_template->query_arr as $qk=>$qv){
                        if($v_recommend_template->query_arr->fee_type=="2"){
                            $v_recommend_template->query_arr->fee_type = "0,1";
                        }
                        if($qk=='grade_id'&&$qv==0){continue;}
                        if($qk=='subject_id'&&$qv==0){continue;}
                        if($qk == 'attr_value_id'){
                            $queryArr[$qk] = str_replace('|',',',$qv);
                        }else{
                            $queryArr[$qk]=$qv;
                        }
                        if(!empty($queryField[$qk]) && $queryField[$qk] != 'vid'){
                            $query.= $queryField[$qk].'='.$qv.'&';
                        }
                    }
                    $queryArr['fee_type'] = $v_recommend_template->query_arr->fee_type;
                    if(!isset($v_recommend_template->query_arr->course_type)){
                        $v_recommend_template->query_arr->course_type= "1,2,3";
                        $queryArr['course_type'] = $v_recommend_template->query_arr->course_type;
                    }else{
                        $queryArr['course_type'] = $v_recommend_template->query_arr->course_type;
                    }
                    if(isset($v_recommend_template->query_arr->first_cate)){
                        $queryArr['first_cate'] = $v_recommend_template->query_arr->first_cate;
                    }
                    if(isset($v_recommend_template->query_arr->second_cate)){
                        $queryArr['second_cate'] = $v_recommend_template->query_arr->second_cate;
                    }
                    if(isset($v_recommend_template->query_arr->third_cate)){
                        $queryArr['third_cate'] = $v_recommend_template->query_arr->third_cate;
                    }
                    //$queryArr['user_id']= $orgInfo->user_owner_id;
                    $queryArr['admin_status']=1;
                    $seekArr = array(
                        "f"=>array(
                            "course_id","title","thumb_med",
                            "thumb_sma","course_type","user_id","user_total",
                            "admin_status"
                        ),
                        "q"  => $queryArr,
                        "p"  => 1,
                        "pl" => $v_recommend_template->row_count*4,
                    );
                    $courseData = seek_api::seekcourse($seekArr);
                    $courses_info = !empty($courseData->data) ? $courseData->data : '';
                }else{
                    $course_ids = isset($v_recommend_template->course_ids)?explode(",",$v_recommend_template->course_ids):"";
                    $course_ids_arr = array_slice($course_ids,0,8);
                    $courses_info = course_api::getCourseByCids($course_ids_arr,$orgInfo->user_owner_id);
                }
                if($courses_info){
                    foreach($courses_info as $kk=>$vv){
                        if(!in_array($vv->admin_status,array(1))) continue;
                        $recommend_course[] =array(
                            'imgurl'=>interface_func::imgUrl($vv->thumb_med),
                            'courseId'=>$vv->course_id,
                            'courseName'=>isset($vv->course_name)?$vv->course_name:$vv->title,
                            'userTotal'=>$vv->user_total,
                        );
                    }
                }
                $data['recommends'][]=array(
                    'id'=>$v_recommend_template->template_id,
                    'name'=>$v_recommend_template->title,
                    'list'=>$recommend_course,

                );
            }
        }

        //直播列表
        $userId = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;
        $liveList = interface_planApi::getLives($userId, $orgId);
        if(!empty($liveList)){
            $data['liveList'] = $liveList;
        }

        return $this->setData($data);
    }
	
	//首页机构主导航
	public function pageCustomNav(){
        return $this->setData(array());

		$orgId = !empty($this->paramsInfo['oid']) ? intval($this->paramsInfo['oid']) : 0;
		if(empty($orgId)) return $this->setMsg(1000);
		
		$params = array('org_id'=>$orgId, 'status'=>0);
		$ret    = org_api::selNav($params);
		
		if(empty($ret->items)) return $this->setMsg(3002);
		
		foreach($ret->items as $val){
			$data[] = [
				'navName' => $val->title,
				'navUrl'  => $val->url
			];
		}
		
		return $this->setData($data);
	}
	
	//学生报名的课程(支持云课2.0)
	public function pageStudentCourse(){
		$this->v(['userId'=>1000]);
		
		$orgId  = !empty($this->paramsInfo['oid']) ? (int)$this->paramsInfo['oid'] : 0;
		$userId = (int)$this->paramsInfo['params']['userId'];
		$page   = !empty($this->paramsInfo['params']['page']) ? (int)$this->paramsInfo['params']['page'] : 1;
		$length = !empty($this->paramsInfo['params']['length']) ? (int)$this->paramsInfo['params']['length'] : 20;
		$token  = $this->paramsInfo['token'];
		if(empty($token)) return $this->setMsg(1000);

		//获取机构信息
		$ownerId = 0;
		if(!empty($orgId)) {
			$orgInfo = user_organization::getOrg($orgId);
			if (empty($orgInfo)) return $this->setMsg(3002);
			$ownerId = $orgInfo->fk_user_owner;
		}
		//报名的课程信息
		//$userRegCourse = interface_user_api::getUserRegCourse($userId,array(),$page,$length,$ownerId);
		$userRegCourse = interface_user_api::getUserRegCourse($userId,array(),$page,$length,0);
        $header = utility_net::isHTTPS() ? "https" : "http";
        //兼容云课/小沃
		$mesType = !empty($orgId) ? array(10017,10019) : array();	
		$msgNum = empty($orgId) ? 0 : message_api::getUnreadInstationNum($userId,$token,$mesType);

		$data = [
			'msgNum'    => !empty($msgNum) ? $msgNum : 0,
            'thumbMed'  => !empty(utility_session::get()['user']) ? $header.':'.utility_session::get()['user']['medium'] : '',    
			'page'      => !empty($userRegCourse['page']) ? $userRegCourse['page'] : 1,
			'pageSize'  => !empty($userRegCourse['totalSize']) ? $userRegCourse['totalSize'] : 0,
			'pageTotal' => !empty($userRegCourse['totalPage']) ? $userRegCourse['totalPage'] : 0,
			'isDayCourse'=> 0
		];
		//是否签到
		$day = date('Y-m-d');
		$signInfo = user_api::getUserSignByDay($day, $userId);
		$data['isDaySign']   = !empty($signInfo) ? 1 : 0;

		//用户等级信息
		$userLevelRes = user_api::getUserLevel($userId);
		$data['exper']= !empty($userLevelRes->score) ? $userLevelRes->score : '0';
		$data['level'] = !empty($userLevelRes->fk_level) ? $userLevelRes->fk_level : '1';
		$data['levelName'] = !empty($userLevelRes->title) ? $userLevelRes->title : '书生1';

		//距下个等级所需要的经验
		$nextLevelRes = user_api::getNextLevel($data['level']);
		$data['nextEx'] = !empty($nextLevelRes) ? ($nextLevelRes->score_min - $data['exper']) : 0;
		$data['nextLevelName'] = !empty($nextLevelRes) ? $nextLevelRes->title : '';

		//学生未完成的作业数
		$data['unfinishNum'] = task_api::unfinishNumByUserId($userId);

        //老师待批改的作业状态
        $data['waitTask'] = !empty(task_api::teacherWaitTask($userId)) ? 1 : 0;
		$data['data'] = array();

		if(!empty($userRegCourse['items'])){
			$time = time();
			$date = date_create();
			$days = array();
			$memberCourseId = array();
			$userRegCourses = array();
			foreach($userRegCourse['items'] as $val){
				$userRegCourses[$val['fk_course']] = $val;
				$courseIdArr[$val['fk_course']] = $val['fk_course'];
				if($val['source'] == 2){
					$memberCourseId[] = $val['fk_course'];
					//剩余天数
					$expireTime = strtotime($val['expire_time']);
					$expireDate = date_create($val['expire_time']);
					$days[$val['fk_course']] = array(
						'diff'     => ($expireTime >= $time) ? (date_diff($expireDate, $date)->format('%a') + 1) : 0,
						'isMember' => 1
					);
				}
			}
			//验证是否为会员
			$status = org_member_api::checkUserCourseIsMember($userId, $memberCourseId);
			
			//用户课程的学习时间
			$UserCourseStudyTimes = stat_api::getUserCourseStudyTime($userId,$courseIdArr);
			$UserCourseStudyTimesArr = array();
			if($UserCourseStudyTimes){
				foreach($UserCourseStudyTimes as $CourseStudyTimes){
					$UserCourseStudyTimesArr[$CourseStudyTimes->fk_course] = $CourseStudyTimes->sum_record + $CourseStudyTimes->sum_live;
				}
			}
			//中间层
			$courseIds = implode(',', $courseIdArr);
			//今天课程
			$planTime = date('Y-m-d 00:00:00').",".date('Y-m-d 23:59:59');
			$planParams = [
				'q' => ['course_id'=>$courseIds,'start_time'=>$planTime],
				'f' => ['course_id','start_time']
			];
			$planSeek = seek_api::seekPlan($planParams);
			$data['isDayCourse'] = !empty($planSeek->data) ? 1 : 0;

			$params = [
				'q' => ['course_id'=>$courseIds],
				'f' => ['course_id','title','thumb_med'],
				'pl'=> $length
			];
			$seekCourse = seek_api::seekCourse($params);
			$seekCourses = array();
			if(!empty($seekCourse->data)){
				foreach($seekCourse->data as $val){
					$seekCourses[$val->course_id] = [
						'courseId'   => $val->course_id,
						'courseName' => $val->title,
						'courseImg'  => $this->imgUrl($val->thumb_med),
						'days'       => !empty($days[$val->course_id]) ? $days[$val->course_id]['diff'] : 0,
						'isMember'   => !empty($days[$val->course_id]) ? $days[$val->course_id]['isMember'] : 0,
						'longTime'   => !empty($UserCourseStudyTimesArr[$val->course_id]) ? $UserCourseStudyTimesArr[$val->course_id] : 0,
						'status'     => !empty($status[$val->course_id]['is_member']) ? 1 : 0
					];
				}
			}
			if($userRegCourses && $seekCourses){
				foreach($userRegCourses as $v){
					if(!empty($seekCourses[$v['fk_course']])){
						$data['data'][]=$seekCourses[$v['fk_course']];
					}
				}
			}
		}
		
		return $this->setData($data);
	}
	
	//我收藏的课程
	public function pageMyfav(){
		$this->v(['userId'=>1000]);
		
		$orgId  = !empty($this->paramsInfo['oid']) ? (int)$this->paramsInfo['oid'] : 0;
		$userId = (int)$this->paramsInfo['params']['userId'] ? (int)$this->paramsInfo['params']['userId'] : 0;
		$page   = !empty($this->paramsInfo['params']['page']) ? (int)$this->paramsInfo['params']['page'] : 1;
		$length = !empty($this->paramsInfo['params']['length']) ? (int)$this->paramsInfo['params']['length'] : 20;
				
		$favReg = user_api::listfav2(array('uid'=>$userId),$page,$length);
		if(empty($favReg->data)) return $this->setMsg(3002);
		
		foreach($favReg->data as $val){
			$courseIdArr[$val->course_id] = $val->course_id;
		}

		//中间层
		$courseIds = implode(',', $courseIdArr);
		$params = [
			'q' => ['course_id'=>$courseIds],
			'f' => ['course_id','title','thumb_med','user_total','price','fee_type','status'],
			'pl'=> $length
		];
        if(!empty($orgId)){
            $params['q']['org_id'] = $orgId;
        }
		$seekCourse = seek_api::seekCourse($params);
		if(empty($seekCourse->data)) return $this->setMsg(3002);
		
		$data = [
			'page'      => $favReg->page,
			'totalSize' => $favReg->size,
			'totalPage' => $favReg->total
		];
		foreach($seekCourse->data as $val){
			$CourseIds[] = $val->course_id;
		}
		$tearcherInfos = course_api::headteacher($CourseIds);
		foreach($seekCourse->data as $val){
			$data['data'][] = [
				'courseId'   => $val->course_id,
				'courseName' => $val->title,
				'courseImg'  => $this->imgUrl($val->thumb_med),
				'userTotal'  => $val->user_total,
				'feeType'    => $val->fee_type,
				'price'      => $val->price / 100,
				'status'     => $val->status,
				'teachers'   => empty($tearcherInfos[$val->course_id]) ? array(): array_values($tearcherInfos[$val->course_id]),
			]; 
		}
		
		return $this->setData($data);
	}
	
	//取消收藏
	public function pageDelFav(){
		$this->v(['userId'=>1000,'courseId'=>1000]);
		
		$orgId    = !empty($this->paramsInfo['oid']) ? $this->paramsInfo['oid'] : 0;
		$userId   = (int)$this->paramsInfo['params']['userId'];
		$courseId = (int)$this->paramsInfo['params']['courseId'];
		
		//该课程是否是本机构的
		$params = [
			'q' => ['course_id'=>$courseId],
			'f' => ['course_id','title'],
        ];
        if(!empty($orgId)){
            $params['q']['org_id'] = $orgId;
        }
		$seekCourse = seek_api::seekCourse($params);
		if(empty($seekCourse->data)) return $this->setMsg(1);
		
		$result = user_api::delFav(array('uid'=>$userId,'cid'=>$courseId));
		if(!empty($result) && $result->code == 0){
			return $this->setMsg(0);
		}
		
		return $this->setMsg(1);
	}
	
	//添加收藏
	public function pageAddFav(){
		$this->v(['userId'=>1000,'courseId'=>1000]);
		
		$orgId    = !empty($this->paramsInfo['oid']) ? $this->paramsInfo['oid'] : 0;
		$userId   = (int)$this->paramsInfo['params']['userId'];
		$courseId = (int)$this->paramsInfo['params']['courseId'];
		
		//该课程是否是本机构的
		$params = [
			'q' => ['course_id'=>$courseId],
			'f' => ['course_id','title']
		];
        if(!empty($orgId)){
            $params['q']['org_id'] = $orgId;
        }
		$seekCourse = seek_api::seekCourse($params);
		if(empty($seekCourse->data)) return $this->setMsg(1);
		
		$result = user_api::addFav(array('user_id'=>$userId,'course_id'=>$courseId));
		if(!$result){
			return $this->setMsg(1);
		}
		
		return $this->setMsg(0);
	}
	//小沃协议
	public function pageWoProtocol(){
		$style ="
		.content p{ font-size:12px; text-align:left;float:left;margin:10px 0 10px 0;}
	.content h5{margin:0;}
	.content p span{ padding-top: 5px;padding-bottom: 5px;float: left;width: 100%; }
	.Agreement_one,.Agreement_two,.Agreement_three,.Agreement_four,.Agreement_five,.Agreement_six,.Agreement_seven,.Agreement_eight,.Agreement_nine,.Agreement_Ten,.Agreement_Eleven,.Agreement_Twelve,.Agreement_Thirteen{ width:100%;float:left;}
	";
		$stpl = new STpl();
		$stpl->assign('style', $style);
		$stpl->render("org/WoProtocol.html");
	}
	
	/**
	 * @desc 未读消息(云课/小沃)
	 */
	public function pageMsgNum(){
		$orgId    = !empty($this->paramsInfo['oid']) ? $this->paramsInfo['oid'] : 0;
		$userId   = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;
		$userInfo = user_api::loginedUser();

		$mesType = !empty($orgId) ? array(10017,10019) : array();	
		$data['msgNum'] = !empty($userInfo) ? message_api::getUnreadInstationNum($userInfo['uid'],$userInfo['token'],$mesType) : 0;
		
		return $this->setData($data);
	}
}
?>
