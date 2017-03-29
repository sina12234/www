<?php

class interface_search extends interface_base
{
    public function pageSubject()
    {
        $data = [
            '1' => '数学',
            '2' => '英语',
            '3' => '语文',
            '4' => '物理',
            '5' => '化学',
            '6' => '生物',
            '7' => '历史',
            '8' => '地理',
            '9' => '政治',
            '10' => '综合'
        ];

        return $this->setData($data);
    }

    /*
     * 区域
     * @author zhengtianlong
     */
    public function pageGetArea()
    {
        $data['data'] = array(
            array('name'=>'全国','id'=>0),
            array('name'=>'北京','id'=>1),
            array('name'=>'长沙','id'=>252),
            array('name'=>'济南','id'=>168)
        );
        return $this->setData($data);
    }

    /*
     * ip地址
     * @author zhengtianlong
     */
    public function pageGetIpInfoOld()
    {
        if (empty($this->paramsInfo['params']['ip'])) return $this->setMsg(1);
        $ip     = utility_ip::realIp();
        $result = utility_ip::info($ip);
        $area = array_flip(region_geo::$region);
        empty($result) && exit('全国');
        $data = [
            'address'   => $result->area_name,
            'addressId' => !empty($area[$result->area_desc]) ? $area[$result->area_desc] : 0
        ];

        return $this->setData($data);
    }

    public function pageGetIpInfo()
    {
        $data = [
            '北京' => 1,
            '湖南' => 14,
            '江西' => 17,
            '广东' => 18,
            '山东' => 8,
            '陕西' => 10,
            '云南' => 20,
            '贵州' => 21,
            '福建' => 31,
            '安徽' => 29,
            '广西' => 19,
            '甘肃' => 25
        ];

        $result = utility_ip::info(utility_ip::realIp());

        $res = [
            'address' => '全国',
            'addressId' => 0
        ];

        if (!empty($result->area_name) && !empty($data[$result->area_name])) {
            $res = [
                'address'   => $result->area_name,
                'addressId' => $data[$result->area_name]
            ];
        }

        return $this->setData($res);
    }

     /*
     * 课程筛选与查询
     * @author zhengtianlong
     */
    public function pageListV2()
    {
        $sortType = [
            2001 => 'user_total',
            2002 => 'avg_score'
        ];

        $gradeArr  = array(3000,2000,1000);
        $page      = $this->s('page') ? (int)$this->paramsInfo['params']['page'] : 1;
        $length    = $this->s('length') ? (int)$this->paramsInfo['params']['length'] : 20;
        $cityId    = isset($this->paramsInfo['params']['cityId'])?$this->paramsInfo['params']['cityId']:0;
        $res_org = user_api::getOrgByProvince($cityId);

        if (empty($res_org->data->items)) return $this->setMsg(3002);

        $ownerArr = array_reduce($res_org->data->items, create_function('$v,$w', '$v[$w->fk_user_owner]=$w->fk_user_owner;return $v;'));
        $ownerArr = array_filter($ownerArr);
        $ownerStr = implode(',',$ownerArr);

        $params = [
            'p'  => $page,
            'pl' => $length,
            'q'  => ['admin_status'=>1,'org_status'=>1],
            'f'  => [
                    'user_id', 'course_id','title','price',
                    'user_total','comment','thumb_med','status','fee_type','class',
                    'avg_score','course_type'
                ]
        ];
		
		if($cityId > 0)
		{
			$params['q']['user_id'] = $ownerStr;
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
                $params['ob']['course_id'] = 'desc';
            }
        }else{
            $params['ob']['course_id'] = 'desc';
        }

        $res = seek_api::seekcourse($params);
        if (empty($res->data)) return $this->setMsg(3002);

        //机构所有者
		$course_arr = array();
		$uid_arr    = array();
        foreach($res->data as $v)
        {
            $uid_arr[$v->user_id] = $v->user_id;
			$course_arr[$v->course_id] = $v->course_id;
        }
        $sublist = array();

        if(!empty($uid_arr))
        {
            $sub_ret = user_api::getSubdomainByUidArr($uid_arr);
            $org_ret = user_organization::getOrgInfoByUidArr($uid_arr);
            if(!empty($org_ret))
            {
                foreach($org_ret as $ov)
                {
                    $sublist[$ov->user_owner] = [
                        'name'    => $ov->name,
                        'subname' => $ov->subname
                    ];
                }
            }

            if(!empty($sub_ret->result->data->items))
            {
                foreach($sub_ret->result->data->items as $val)
                {
                    $sublist[$val->fk_user]['subdomain'] = $val->subdomain;
                }
            }
        }

        $data = [
            'total'     => $res->total,
            'page'      => $res->page,
            'pageTotal' => ceil($res->total/$res->pagelength)
        ];

		$scoreArr = array();
		if(!empty($course_arr)){
			$scoreRes = comment_api::getScoreCourseTotalList($course_arr);
			if(!empty($scoreRes['data'])){
				$scoreArr = $scoreRes['data'];
			}
		}

        foreach($res->data as $val)
        {
            if(!empty($val->class))
            {
                //apple check
                /*if ($this->paramsInfo['u'] == 'i') {
                    if ($val->fee_type > 0 || $val->price > 0) continue;
                }*/
                // end
                $data['data'][] = [
                    'courseId'  => empty($val->course_id)?'':$val->course_id,
                    'title'     => empty($val->title)?'':$val->title,
                    'cate'      => teacher_api::getSubjectName($val->subject),
                    'thumbMed'  => empty($val->thumb_med)?'':$this->imgUrl($val->thumb_med),
                    'price'     => floatval($val->price / 100),
                    'grade'     => '',
                    'userTotal' => empty($val->user_total)?0:$val->user_total,
                    'comment'   => empty($val->comment)?0:$val->comment,
                    'status'    => ($val->status==0)?1:$val->status,
                    'score'     => !empty($scoreArr[$val->course_id]['avg_score']) ? $scoreArr[$val->course_id]['avg_score'] : 0,
                    'org'       => !empty($sublist[$val->user_id]['subname'])?$sublist[$val->user_id]['subname']:(!empty($sublist[$val->user_id]['name'])?$sublist[$val->user_id]['name']:''),
                    'courseType'=> '线上',
                    'courseTypeInt' => $val->course_type,
                    'feeType' => empty($val->fee_type)?0:$val->fee_type
                ];
            }else
            {
                continue;
            }
        }

       return $this->setData($data);
    }
	
	/*
	 * 课程搜索
	 * @param $condition 兴趣id
	 * @param $keywords  关键字
	 * @param $sort 1价格最底,2价格最高，3最新上价，4人气排序，5评分排序
	 * @param $type
	 * @param $oid 机构id
	 */
	public function pageCourseSearch(){
		$page      = $this->s('page') ? abs((int)$this->paramsInfo['params']['page']) : 1;
        $length    = $this->s('length') ? abs((int)$this->paramsInfo['params']['length']) : 20;
		$condition = !empty($this->paramsInfo['params']['condition']) ? $this->paramsInfo['params']['condition'] : '';
		
		$thirdId = '';
		$attrId  = '';
		if(!empty($condition)){
			$conditionArr = explode(',',$condition);
		
            $secondId= !empty($conditionArr[0]) ? $conditionArr[0] : '';
			$thirdId = !empty($conditionArr[1]) ? $conditionArr[1] : '';
			$attrId  = !empty($conditionArr[2]) ? $conditionArr[2] : '';
		}
		
		$sortType = [
			1 => 'priceAsc',
			2 => 'price',
			3 => 'course_id',
            4 => 'user_total',
            5 => 'avg_score'
        ];
		
		$params = [
            'p'  => $page,
            'pl' => $length,
            'q'  => ['admin_status'=>1,'org_status'=>1],
            'f'  => [
                    'user_id','course_id','title','price','fee_type','third_cate','course_type',
                    'user_total','comment','thumb_med','status','avg_score','third_cate_name',
					'course_attr','org_subname'
                ]
        ];

		if(!empty($secondId)){
			$params['q']['second_cate'] = $secondId;
		}

		if(!empty($thirdId)){
			$params['q']['third_cate'] = $thirdId;
		}

		if(!empty($attrId)){
			$params['q']['attr_value_id'] = $attrId;
		}

		if(!empty($this->paramsInfo['params']['keywords'])){
            $params['q']['search_field'] = $this->paramsInfo['params']['keywords'];
        }

        $orgId = !empty($this->paramsInfo['oid']) ? (int)$this->paramsInfo['oid'] : 0;
		if(!empty($orgId)){
            //$params['q']['org_id'] = intval($this->paramsInfo['oid']);
            $params['q']['expression'] = "(@resell_org_id =".$orgId." | @org_id=".$orgId.") & @search_field =".$this->paramsInfo['params']['keywords'];
        }
        

		if(!empty($this->paramsInfo['params']['sort'])){
            $sort = explode(',',$this->paramsInfo['params']['sort']);
            foreach ($sortType as $k=>$v){
                if (in_array($k, $sort)){
					if($v == 'priceAsc'){
						$params['ob']['price'] = 'asc';
						$params['ob']['fee_type'] = 'desc';
					}
					if($v == 'price'){
						$params['ob']['price'] = 'desc';
						$params['ob']['fee_type'] = 'desc';
					}
                    $params['ob'][$v] = 'desc';
                }
            }
            if(empty($params['ob'])){
                $params['ob']['vv'] = 'desc';
            }
        }else{
            $params['ob']['vv'] = 'desc';
        }
		
		
		if(!empty($this->paramsInfo['params']['type'])){
			$params['q']['course_type'] = $this->paramsInfo['params']['type'];
		}

		$resCourse = seek_api::seekcourse($params);

		if (empty($resCourse->data)) return $this->setMsg(3002);
		
		$data = [
            'total'     => $resCourse->total,
            'page'      => $resCourse->page,
            'pageTotal' => ceil($resCourse->total/$resCourse->pagelength)
        ];
		
		
		//获取科目名称
		$temp = array();
		$attrValues = array();
		$attrCourseId = array();
		foreach($resCourse->data as $value){
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
			$CourseIds[] = $value->course_id;
		}

		if(!empty($temp)){
			foreach($temp as $k=>$v){
				$attrValues[$k] = implode(",",$v);
			}
		}

        //分销信息
        $resellIdArr = array();
        if(!empty($orgId)) {
            $salesInfo = course_resell_api::getSalesCourse(1, 0, array('fk_org_resell' => $orgId));
            if(!empty($salesInfo)){
                foreach ($salesInfo as $val) {
                    $resellIdArr[$val->fk_course] = $val->price_resell;
                }
            }
        }

		$tearcherInfo = course_api::headteacher($CourseIds);
		foreach($resCourse->data as $val){
			if(!empty($attrCourseId) && in_array($val->course_id,$attrCourseId)){
				$data['data'][] = [
					"courseId"   => $val->course_id,
					"title"      => $val->title,
					"thumbMed"   => empty($val->thumb_med) ? '' : $this->imgUrl($val->thumb_med),
					"cate"       => !empty($attrValues[$val->course_id]) ? $attrValues[$val->course_id] : '',
					"grade"      => $val->third_cate_name,
					"userTotal"  => $val->user_total,
					"comment"    => $val->comment,
					"org"        => $val->org_subname,
					"score"      => $val->avg_score,
					"status"     => $val->status,
					"courseType" => $val->course_type,
					"feeType"    => $val->fee_type,
                    "price"      => !empty($resellIdArr[$val->course_id]) ? $resellIdArr[$val->course_id]/100 : $val->price / 100,
					"teachers"   => empty($tearcherInfo[$val->course_id])?array():array_values($tearcherInfo[$val->course_id]),
				];
			}else{
				$data['data'][] = [
					"courseId"   => $val->course_id,
					"title"      => $val->title,
					"thumbMed"   => empty($val->thumb_med) ? '' : $this->imgUrl($val->thumb_med),
					"cate"       => !empty($attrValues[$val->course_id]) ? $attrValues[$val->course_id] : '',
					"grade"      => $val->third_cate_name,
					"userTotal"  => $val->user_total,
					"comment"    => $val->comment,
					"org"        => $val->org_subname,
					"score"      => $val->avg_score,
					"status"     => $val->status,
					"courseType" => $val->course_type,
					"feeType"    => $val->fee_type,
                    "price"      => !empty($resellIdArr[$val->course_id]) ? $resellIdArr[$val->course_id]/100 : $val->price / 100,
					"teachers"   => empty($tearcherInfo[$val->course_id])?array():array_values($tearcherInfo[$val->course_id]),
				];
			}
		}
		
		return $this->setData($data);
	}
	
	/*
	 * 老师搜索
	 * @param $condition 兴趣id
	 * @param $keywords  关键字
	 * @param $sort 1人气排序，2评分排序，3评价排序
	 * @param $type 0 全部 1直播课，2录播课，3线下课
	 * @param $oid 机构id
	 */
    public function pageTeacherSearch()
    {
        $gradeName = [4000=>'学前',1000=>'小学',2000=>'初中',3000=>'高中'];
        $sortType = [1 =>'student_count',2=>'avg_score',3=>'comment'];
        $page      = $this->s('page') ? (int)$this->paramsInfo['params']['page'] : 1;
        $length    = $this->s('length') ? (int)$this->paramsInfo['params']['length'] : 20;
        $condition = !empty($this->paramsInfo['params']['condition']) ? $this->paramsInfo['params']['condition'] : '';

        $subjectId = 0;
        $gradeId   = 0;
        if(!empty($condition)){
            $conditionArr = explode(',',$condition);
            $gradeId    = !empty($conditionArr[0]) ? $conditionArr[0] : '';
            $subjectId  = !empty($conditionArr[1]) ? $conditionArr[1] : '';
        }

        $params = [
            'p'  => $page,
            'q'  => ['teacher_status'=>1,'visiable'=>1, 'course_count'=>'1,5000'],
            'pl' => $length,
            'f'  => [
                'teacher_id','org_name','name','real_name','thumb_big','subject_id','totaltime',
                'student_count','org_teacher','avg_score','mobile','grade','grade_id','subject','course_count','comment'
            ],
        ];

        if(!empty($subjectId)){
            $params['q']['subject_id'] = $subjectId;
        }
        if(!empty($gradeId)){
            $params['q']['grade_id'] = $gradeId;
        }
        if(!empty($this->paramsInfo['params']['keywords'])){
            $params['q']['search_field'] = $this->paramsInfo['params']['keywords'];
        }
        if(!empty($this->paramsInfo['oid'])){
            $params['q']['org_id'] = $this->paramsInfo['oid'];
        }
        if(!empty($this->paramsInfo['params']['sort'])){
            $sort = explode(',',$this->paramsInfo['params']['sort']);
            foreach ($sortType as $k=>$v){
                if (in_array($k, $sort)){
                    $params['ob'][$v] = 'desc';
                }
            }
            if(empty($params['ob'])){
                $params['ob']['weight'] = 'desc';
            }
        }else{
            $params['ob']['weight'] = 'desc';
        }
        if(!empty($this->paramsInfo['params']['type'])){
            $params['q']['gender'] = $this->paramsInfo['params']['type'];
        }

        $seekTeacher = seek_api::seekTeacher($params);
		
        if (empty($seekTeacher->data)) return $this->setMsg(3002);
        $teacherCount = !empty($seekTeacher->data) ? count($seekTeacher->data) : 0;
        $data = [
            "total"     => $seekTeacher->total,
            "page"      => $seekTeacher->page,
            "pageTotal" => ceil($seekTeacher->total/$seekTeacher->pagelength),
            "teacherCount"=> $teacherCount
        ];
        $grade = array();
        foreach($seekTeacher->data as $k=>$v){
            if(!empty($v->grade_id)){
                foreach($gradeName as $key=>$val){
                    $gradeId = $v->grade_id;
                    if(in_array($key,$gradeId)){
                        $grade[$v->teacher_id][$key] = $val;
                    }
                }
            }
            $data['data'][] = [
                "teacherId"       => $v->teacher_id,
                "name"            => !empty($v->real_name)?$v->real_name:(!empty($v->name)?mb_substr($v->name,0,15,'utf-8'):SLanguage::tr('未设置', 'message')),
                "thumbMed"        => $this->imgUrl($v->thumb_big),
                "subjectName"     => !empty(teacher_api::getSubjectName($v->subject))?str_replace(',',' ',teacher_api::getSubjectName($v->subject)):'',
                "courseTotalTime" => number_format($v->totaltime/3600,1),
                "userTotal"       => $v->student_count,
                "score"           => round($v->avg_score,1),
                "grade"           => (!empty($grade) && !empty($grade[$v->teacher_id]))?implode(' ',$grade[$v->teacher_id]):'',
                "orgName"                 => !empty($v->org_teacher[0]->subname) ? $v->org_teacher[0]->subname : '',
                "comment"                 => $v->comment,
                "courseCount"     => $v->course_count,
            ];
        }
        return $this->setData($data);
    }

    /*
     * @desc 云课2.0
     */
    public function pageTeacher(){
        $gradeName = [4000=>'学前',1000=>'小学',2000=>'初中',3000=>'高中'];
        $sortType  = [2000=>'student_count',1000=>'avg_score',3=>'comment'];
        $page      = $this->s('page') ? (int)$this->paramsInfo['params']['page'] : 1;
        $length    = $this->s('length') ? (int)$this->paramsInfo['params']['length'] : 20;
        $condition = !empty($this->paramsInfo['params']['condition']) ? $this->paramsInfo['params']['condition'] : '';

        $subjectId = 0;
        $gradeId   = 0;
        if(!empty($condition)){
            $conditionArr = explode(',',$condition);
            $gradeId    = !empty($conditionArr[0]) ? $conditionArr[0] : '';
            $subjectId  = !empty($conditionArr[1]) ? $conditionArr[1] : '';
        }

        $params = [
            'p'  => $page,
            'q'  => ['teacher_status'=>1,'visiable'=>1, 'course_count'=>'1,5000','platform_status'=>1],
            'pl' => $length,
            'f'  => [
                'teacher_id','org_name','name','real_name','thumb_big','subject_id','totaltime',
                'student_count','org_teacher','avg_score','mobile','grade','grade_id','subject',
                'course_count','comment','org_teacher'
            ],
        ];

        if(!empty($subjectId)){
            $params['q']['subject_id'] = $subjectId;
        }
        if(!empty($gradeId)){
            $params['q']['grade_id'] = $gradeId;
        }
        if(!empty($this->paramsInfo['params']['keywords'])){
            $params['q']['search_field'] = $this->paramsInfo['params']['keywords'];
        }
        if(!empty($this->paramsInfo['oid'])){
            $params['q']['org_id'] = $this->paramsInfo['oid'];
        }
        if(!empty($this->paramsInfo['params']['sort'])){
            $sort = explode(',',$this->paramsInfo['params']['sort']);
            foreach ($sortType as $k=>$v){
                if (in_array($k, $sort)){
                    $params['ob'][$v] = 'desc';
                }
            }
            if(empty($params['ob'])){
                $params['ob']['weight'] = 'desc';
            }
        }else{
            $params['ob']['weight'] = 'desc';
        }
        if(!empty($this->paramsInfo['params']['type'])){
            $params['q']['gender'] = $this->paramsInfo['params']['type'];
        }
        $seekTeacher = seek_api::seekTeacher($params);
        $teacherCount = !empty($seekTeacher->data) ? count($seekTeacher->data) : 0;
        $data = [
            "total"     => $seekTeacher->total,
            "page"      => $seekTeacher->page,
            "pageTotal" => ceil($seekTeacher->total/$seekTeacher->pagelength),
            "teacherCount"=> $teacherCount
        ];
        //分类信息
        $data['data']['cate'] = [
            ['id'=>0,'name'=>'全部'],
            ['id'=>4000,'name'=>'学前'],
            ['id'=>1000,'name'=>'小学'],
            ['id'=>2000,'name'=>'初中'],
            ['id'=>3000,'name'=>'高中']
        ];

        $cateArr = array();
        //老师信息
        $data['data']['teacher'] = array();
        if(!empty($seekTeacher->data)) {
            foreach ($seekTeacher->data as $k => $v) {
                if (!empty($v->grade_id)) {
                    foreach ($gradeName as $key => $val) {
                        $gradeId = $v->grade_id;
                        if (in_array($key, $gradeId)) {
                            $grade[$v->teacher_id][$key] = $val;
                        }
                    }
                }
                if(!empty($v->subject_id)){
                    foreach($v->subject_id as $sv){
                        $cateArr[$sv] = $sv;
                    }
                }
                $data['data']['teacher'][] = [
                    "teacherId" => $v->teacher_id,
                    "name" => !empty($v->real_name) ? $v->real_name : (!empty($v->name) ? mb_substr($v->name, 0, 15, 'utf-8') : SLanguage::tr('未设置', 'message')),
                    "thumbMed" => $this->imgUrl($v->thumb_big),
                    "subjectName" => !empty(teacher_api::getSubjectNameV2($v->subject)) ? str_replace(',', ' ', teacher_api::getSubjectNameV2($v->subject)) : '',
                    "courseTotalTime" => number_format($v->totaltime / 3600, 1),
                    "userTotal" => $v->student_count,
                    "score" => round($v->avg_score, 1),
                    "grade" => (!empty($grade) && !empty($grade[$v->teacher_id])) ? implode(' ', $grade[$v->teacher_id]) : '',
                    "orgName" => !empty($v->org_teacher[0]->subname) ? $v->org_teacher[0]->subname : '',//$v->org_name,
                    "comment" => $v->comment,
                    "courseCount" => $v->course_count,
                ];
            }
        }

        //科目信息
        $group = SConfig::getConfig(ROOT_CONFIG."/group.conf","group");
        $subjectArr = tag_api::getTagByGroupId($group->subject);
        $attrData = array();
        if(!empty($subjectArr)){
            $attrData[0]['id'] = 0;
            $attrData[0]['name'] = '全部';
            foreach($subjectArr as $key=>$val){
                if(in_array($key, $cateArr)){
                    $attrData[$key]['id']   = $key;
                    $attrData[$key]['name'] = $val;
                }
            }
        }
        $data['data']['attr'] = array_values($attrData);

        return $this->setData($data);
    }

    /*
     * 年级与对应ID
     * @author zhengtianlong
     */
     public function pageGradeAndSubject()
     {
        $data['data'] = array(
            'grade' => array(
                array("name"=>"全部年级","id"=>"0"),
                array("name"=>"小学","id"=>"1000"),
                array("name"=>"初中","id"=>"2000"),
                array("name"=>"高中","id"=>"3000")
            ),
            'subject' => array(
                array("name"=>"全部科目","id"=>"0"),
                array("name"=>"数学","id"=>"1"),
                array("name"=>"英语","id"=>"2"),
                array("name"=>"语文","id"=>"3"),
                array("name"=>"物理","id"=>"4"),
                array("name"=>"化学","id"=>"5"),
                array("name"=>"生物","id"=>"6"),
                array("name"=>"历史","id"=>"7"),
                array("name"=>"地理","id"=>"8"),
                array("name"=>"政治","id"=>"9"),
            ),
            'others' => array(
                "nolimit"=>array("name"=>"不限","id"=>"0"),
                "courseType"=>array(
                    array("name"=>"线上课", "id"=>"1001"),
                    array("name"=>"线下课", "id"=>"1002"),
                ),
                "sortType"=>array(
                    array("name"=>"人气排序", "id"=>"2001"),
                    array("name"=>"评分排序", "id"=>"2002")
                )
            )
        );

        return $this->setData($data);
     }
	
    public function pageGetTag(){
        $cateList = course_api::getCateList();
        $cate = array(6=>"4000",7=>"1000",8=>"2000",9=>"3000");
        $i = 0;
		$oid = !empty($this->paramsInfo['oid']) ? (int)($this->paramsInfo['oid']) : 0;
        $scopes = array();
        if(!empty($oid)){
			$orgProfileInfo = user_organization::GetOrgProfileInfo($oid);
            $scopes = !empty($orgProfileInfo['scopes']) ? explode(',',$orgProfileInfo['scopes']) : array();
            if(empty($scopes)) return $this->setMsg(3002);
        }
        foreach($cateList as $key=>$value){
			if($oid>0 && !in_array($value->pk_cate,$scopes)){
				continue;
			}
            if($value->level == 1){
                $data[$key] = [
                    'name' => $value->name,
                    'id'   => $value->pk_cate
                ];
                $i = 0;
                foreach($cateList as $ke=>$val){
                    if($val->level == 2 && $val->lft > $value->lft && $val->rgt < $value->rgt){
                        $data[$key]['data'][$i] = [
                            'name' => $val->name,
                            'id'   => $val->pk_cate
                        ];
                        
                        foreach($cateList as $k=>$v){
                            if($v->level == 3 && $v->lft > $val->lft && $v->rgt < $val->rgt){
                                
                                if(!empty($cate[$val->pk_cate])){
                                    $data[$key]['data'][$i]['data'][] = [
                                        'name' => $v->name,
                                        'id'   => $v->pk_cate,
                                        'oldId'=> $cate[$val->pk_cate]
                                    ];
                                }else{
                                    if($v->name == '其他'){
                                        $data[$key]['data'][$i]['data'][] = [
                                             'name' => $val->name.$v->name,
                                             'id'   => $v->pk_cate,    
                                        ];
                                    }else{
                                        $data[$key]['data'][$i]['data'][] = [
                                             'name' => $v->name,
                                             'id'   => $v->pk_cate,    
                                        ];
                                    }
                                }
                                
                            }
                        }
                        $i++;
                    }
                }
            }
        }
	 $data = array_values($data);
        return $this->setData($data);
    }
	
	//兼容老版本
	public function pageIndex()
	{
        $uid       = !empty($this->paramsInfo['params']['uid']) ? (int)($this->paramsInfo['params']['uid']) : '';
        $condition = !empty($this->paramsInfo['params']['condition']) ? $this->paramsInfo['params']['condition'] : ''; 
        $teacherSeach = !empty($this->paramsInfo['params']['teacherSeach']) ? $this->paramsInfo['params']['teacherSeach'] : ''; 
		//$width = !empty($this->paramsInfo['dinfo']['rw']) ? $this->paramsInfo['dinfo']['rw'] : 0;
		//$type = ($width <= 720) ? 2 : 3;
        //轮播图
        $banner = array();
        $bannerRes = index_api::getPlatformBanner(1);
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
		
		$thirdArr    = array();
        $secondIdArr = array();
        $reviewArr   = array();
        $review      = array();
        $lives       = array();
		$livesCourseId = array();
        
        //报名信息
        $userCourse = array();
        if($uid > 0){
            $regis = course_api::listRegistration(array('uid'=>$uid));
            if(!empty($regis->data)){
                foreach($regis->data as $v){
                    $userCourse[$v->cid] = $v->cid;
                }
            }
        }
		
		//三级分类下直播课
        foreach($planRes->data as $v){
            if(in_array($v->third_cate,$condition)){
                $time = strtotime($v->start_time);
                if($time > $sTime && $time < $eTime){
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
                        $lives[] = $v->plan_id;
						break;
                    }
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
            'q' => ['course_id'=>$courseIdStr],
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
            'q'  => ['teacher_status'=>1,'visiable'=>1,'course_count'=>'1, 5000','student_count'=>'1,5000','platform_status'=>1],
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
            'subject','user_total','third_cate_name','remain_user','second_cate'
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
				'subject'     => !empty(teacher_api::getSubjectName($v->subject)) ? teacher_api::getSubjectName($v->subject) : '',
				'studentNum'  => $v->user_total
			];         
        }
		return $data;
    }
	
	//获取区号列表
	public function pageValid()
	{
		$valid = utility_valid::$mobile_country;
		
		$commonCode = array_slice($valid,0,14);
		
		foreach($valid as $k=>$v){
			$codeResult[] = !empty($v['name_en']) ? $v['name_en'] : '';
		}
		array_multisort($codeResult,SORT_ASC,SORT_STRING,$valid);

		$data = [
			'commonCode' => $commonCode,
			'allCode'    => $valid
		];

		return $this->setData($data);
	}

    /*
     * @desc 课程筛选(小沃/云课2.0)
     * @param $condition":"44,0,0", 二级分类 三级分类 科目
     * @param "sort":"0,3000"    {0 全部 1000 免费 2000 付费}{3000 按热门 4000 按最新} 默认(全部/按热门)
     */
    public function pageCourseFilter(){
        $orgId     = !empty($this->paramsInfo['oid']) ? (int)$this->paramsInfo['oid'] : 0;
        $page      = !empty($this->paramsInfo['params']['page']) ? (int)$this->paramsInfo['params']['page'] : 1;
        $length    = !empty($this->paramsInfo['params']['length']) ? (int)$this->paramsInfo['params']['length'] : 20;
        $condition = !empty($this->paramsInfo['params']['condition']) ? explode(',', $this->paramsInfo['params']['condition']) : array(0,0,0);
        $sort      = !empty($this->paramsInfo['params']['sort']) ? explode(',', $this->paramsInfo['params']['sort']) : array(0,3000);

        //兴趣分类id(7,0,0)
        if(count($condition) < 3){
            array_push($condition, 0);
        }
        list($secondId, $thirdId, $attrId) = $condition;

        //筛选类型(0,3000)
        if(count($sort) > 2){
            array_push($sort, 3000);
        }
        list($feeType, $sorts) = $sort;

        //机构信息
        $orgInfo = user_organization::getOrgInfoByOidArr(array($orgId));
        $ownerId = !empty($orgInfo) ? $orgInfo[0]->fk_user_owner : 0;

        //分类信息
        $cate[]     = array('id'=>0,'name'=>'全部');
        $secondInfo = array();
        if(!empty($secondId)) {
            //三级分类信息
            $catesInfo = course_api::getOrgValidCateIds($ownerId, 3, array('second_cate' => $secondId));
            $cateInfo = course_api::getCateByCidStr(implode(',',$catesInfo->ids));
            if(!empty($cateInfo)){
                foreach($cateInfo as $val){
                    $cate[] = [
                        'id'   => $val->pk_cate,
                        'name' => $val->name,
                    ];
                }
            }
            //二级分类信息
            $secondInfos = course_api::getCateByCateId($secondId);
            $secondInfo  = [
                'id'   => $secondInfos->pk_cate,
                'name' => $secondInfos->name
            ];
        }

        //科目列表
        $attr[] = array('id'=>0,'name'=>'全部');
        if(!empty($thirdId)){
            $attrsInfo = course_api::getAttrValueList($thirdId,$ownerId);
            if($attrsInfo){
                foreach($attrsInfo as $v){
                    foreach($v->attr_value as $vv){
                        $attr[] = [
                            'id'   => $vv->attr_value_id,
                            'name' => $vv->value_name
                        ];
                    }
                }
            }
        }

        $params = [
            'p'  => $page,
            'pl' => $length,
            'q'  => ['admin_status'=>1,'org_status'=>1],
            'f'  => [
                'user_id','course_id','title','price','fee_type','third_cate','course_type',
                'user_total','comment','thumb_med','status','avg_score','third_cate_name',
                'course_attr','org_subname'
            ]
        ];
        if(!empty($secondId)){
            $params['q']['second_cate'] = $secondId;
        }
        if(!empty($thirdId)){
            $params['q']['third_cate'] = $thirdId;
        }
        if(!empty($attrId)){
            $params['q']['attr_value_id'] = $attrId;
        }
        if(!empty($orgId)){
            //$params['q']['org_id'] = $orgId;
            $params['q']['expression'] = "(@resell_org_id =".$orgId." | @org_id=".$orgId.") & @search_field =".$this->paramsInfo['params']['keywords'];
        }
        if($feeType == 1000){
            $params['q']['fee_type'] = 0;
        }
        if($feeType == 2000){
            $params['q']['fee_type'] = 1;
        }
        if($sorts == 3000){
            $params['ob']['user_total'] = 'desc';
        }
        if($sorts == 4000){
            $params['ob']['course_id'] = 'desc';
        }
        $courseReg = seek_api::seekcourse($params);

        //分销信息
        $resellIdArr = array();
        if(!empty($orgId)) {
            $salesInfo = course_resell_api::getSalesCourse(1, 0, array('fk_org_resell' => $orgId));
            if(!empty($salesInfo)){
                foreach ($salesInfo as $val) {
                    $resellIdArr[$val->fk_course] = $val->price_resell;
                }
            }
        }

        $list = array();
        if(!empty($courseReg->data)){
            foreach($courseReg->data as $val){
                $list[$val->course_id] = [
                    'courseId'   => $val->course_id,
                    'courseName' => $val->title,
                    'courseImg'  => !empty($val->thumb_med) ? $this->imgUrl($val->thumb_med) : '',
                    'feeType'    => $val->fee_type,
                    //'price'      => $val->price / 100,
                    "price"      => !empty($resellIdArr[$val->course_id]) ? $resellIdArr[$val->course_id]/100 : $val->price / 100,
                    'userNum'    => $val->user_total
                ];
                $courseIdArr[] = $val->course_id;
            }
            $tearcherInfos = course_api::headteacher($courseIdArr);
            if($tearcherInfos){
                foreach($tearcherInfos as $course_id=>$tearcherInfo){
                    $list[$course_id]['teachers']=empty($tearcherInfo)?array():array_values($tearcherInfo);
                }
            }
            $list = array_values($list);
        }

        $data = [
            'page'      => $courseReg->page,
            'totalPage' => ceil($courseReg->total/$courseReg->pagelength),
            'total'     => $courseReg->total,
        ];
        $data['data']['cate']   = $cate;
        $data['data']['attr']   = $attr;
        $data['data']['secondInfo']   = empty($secondInfo)?array():$secondInfo;
        $data['data']['course'] = $list;

        return $this->setData($data);
    }

    /*
        获取机构的所有有效分类
        $oid
    */
	public function pageGetAllCate(){
		$oid = !empty($this->paramsInfo['oid']) ? intval($this->paramsInfo['oid']) : 0;//机构id
		if(empty($oid)) return $this->setMsg(1000);
		$orgProfileInfo = user_organization::GetOrgProfileInfo($oid);
		if(empty($orgProfileInfo)) return $this->setMsg(1000);
		$uid=$orgProfileInfo['fk_user_owner'];
		//查询机构的有效分类id
		$result=course_api::getOrgValidCateIds($uid);
		$cateList=array();
		if($result->ids){
			//取机构设置的一级分类
			$scopes = $orgProfileInfo['scopes'];
			if(empty($scopes)) return $this->setMsg(3002);
			$scopes = explode(',',$scopes);
			$cateInfo = course_api::getCateByCidStr(implode(",",$result->ids));
			$cateInfos=array();
			if($cateInfo){
				foreach($cateInfo as $v){
					$cateInfos[$v->pk_cate]=$v->name;
				}
			}
            $i=0;
			foreach($result->lists as $fisrtCate=>$secondCates){
				if(!in_array($fisrtCate,$scopes)) continue;
				//一级分类
				$cateList[$i]=array(
					'id'=>$fisrtCate,
					'name'=>$cateInfos[$fisrtCate],
					'data'=>array(),
				);
                $j=0;
				foreach($secondCates as $secondCate=>$thirdCates){
					//二级分类
					$cateList[$i]['data'][$j]=array(
						'id'=>$secondCate,
						'name'=>$cateInfos[$secondCate],
						'data'=>array(),
					);
                    $k=0;
					foreach($thirdCates as $thirdCate){
						//三级分类
						$cateList[$i]['data'][$j]['data'][$k]=array(
							'id'=>$thirdCate,
							'name'=>$cateInfos[$thirdCate],
						);
						$k++;
					}
					$j++;
				}
				$i++;
			}
		}
		return $this->setData($cateList);
	}
}
?>
