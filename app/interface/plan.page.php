<?php

class interface_plan extends interface_base
{

	public function pageStartPlay()
	{
		$userInfo = user_api::loginedUser();
		if(empty($userInfo)) return $this->setMsg(1000);
		
		$cleanFile = false;

		if (!utility_tool::check_int($this->paramsInfo['params']['planId'])) {
			return $this->setMsg(1010);
		}

		if (
			!empty($this->paramsInfo['params']['cleanFile'])
			&& $this->paramsInfo['params']['cleanFile'] == 'yes'
		) {
			$cleanFile = true;
		}

		$tPlanInfo = course_api::listPlan(
			array(
				'plan_id'   => $this->paramsInfo['params']['planId'],
				'allcourse' => true
			)
		);

		if (empty($tPlanInfo->data[0]))
			return $this->setMsg(2001);

		$planInfo = $tPlanInfo->data[0];

		$publish = live_publish::getByuid($userInfo['uid']);

		if (empty($publish))
			return $this->setMsg(2002);

		if ($publish->live_call == 'publish_done')
			return $this->setMsg(2003);

		if ($publish->plan_id > 0 && $publish->plan_id != $this->paramsInfo['params']['planId'])
			return $this->setMsg(2004);

		$ret = course_plan_api::startPlan(
			$userInfo['uid'],
			$this->paramsInfo['token'],
			$this->paramsInfo['params']['planId']
		);

		if ($ret) {
			return $this->setData(array());
		}

		return $this->setMsg(2005);
	}

    public function pageClosePlay()
    {
        $planId = !empty($this->paramsInfo['params']['planId']) ? (int)$this->paramsInfo['params']['planId'] : 0;
        $uid    = !empty($this->paramsInfo['params']['uid']) ? (int)$this->paramsInfo['params']['uid'] : 0;
        $token  = !empty($this->paramsInfo['token']) ? $this->paramsInfo['token'] : '';

        if(empty($planId) || empty($uid) || empty($token)){
            return $this->setMsg(1000);
        }

        $planInfo = course_api::getPlan($planId);
        if (empty($planInfo)) return $this->setMsg(2001);

        $ret = course_plan_api::stopPlan($uid, $token, $planId);
        message_api::requestEval($planId, $uid, $token);
        if($ret){
            return $this->setMsg(0);
        }

        return $this->setMsg(2030);
    }

    /*
     * 学生上课详情
     * @author  zhengtianlong
    */
    public function pageListV2()
    {
        $this->v([
            'uid' => 1000,
            'planId' => 1000
        ]);

        $page = empty($this->paramsInfo['params']['page']) ? 1 : $this->paramsInfo['params']['page'];
		$uid  = $this->paramsInfo['params']['uid'];
		$pid  = explode(',',trim($this->paramsInfo['params']['planId'],','));
		$planId = implode(',',$pid);
        $params = [
            'q' => [
                'plan_id'=> $planId,
                'status' => '1,2,3'
                ],
            'p' => $page,
            'f' => [
                'plan_id',
                'course_name',
                'class_name',
                'section_name',
                'section_desc',
                'start_time',
                'teacher_name',
                'teacher_id',
                'teacher_thumb_med',
                'owner_id',
                'status'
            ]
        ];
        $result = seek_api::seekPlan($params);
        if (empty($result->data)) return $this->setMsg(3002);
        foreach($result->data as $v)
        {
            $owner_id_arr[] = $v->owner_id;
        }
		$domain_ret = user_api::getSubdomainByUidArr($owner_id_arr);

		$domainlist = array();
		if( $domain_ret->result->code == 0 && $domain_ret->result->data){
			$subdomains = $domain_ret->result->data->items;
			foreach($subdomains as $so){
				$domainlist[$so->fk_user] = $this->httpHeader() .'://'.$so->subdomain.'.gn100.com';
			}
		}


        $data = [];
        foreach($result->data  as $val)
        {
            $data[] = [
                'planId'      => $val->plan_id,
                'courseTitle' => $val->course_name,
                'className'   => $val->class_name,
                'sectionName' => $val->section_name,
                'sectionDesc' => $val->section_desc,
                'startTime'   => date('Y-m-d H:i',strtotime($val->start_time)),
                'status'      => $val->status,
                'courseType'  => 0,
                'teacherName' => !empty($val->teacher_real_name)?$val->teacher_real_name:$val->teacher_name,
                'teacherId'   => $val->teacher_id,
                'teacherImage'=> $this->imgUrl($val->teacher_thumb_med),
				'playUrl'     => !empty($domainlist[$val->owner_id])?$domainlist[$val->owner_id].'/course.plan.play/'.$val->plan_id:''
            ];
        }

        return $this->setData($data);
    }


	/*
	 * 老师上课详情
	 * @author zhengtianlong
	 */
	public function pageTeacherDetail()
    {
        $this->v([
            'uid' => 1000,
            'planId' => 1000
        ]);

        $page = empty($this->paramsInfo['params']['page']) ? 1 : $this->paramsInfo['params']['page'];
		$uid  = $this->paramsInfo['params']['uid'];
		$pid  = explode(',',trim($this->paramsInfo['params']['planId'],','));
		$planId = implode(',',$pid);
		//排课的信息
        $planParams = [
            'q' => [
                'plan_id'=> $planId,
                'status' => '1,2,3'
                ],
            'p' => $page,
            'f' => [
				'course_id',
                'plan_id',
                'course_name',
                'class_name',
                'section_name',
                'section_desc',
                'start_time',
                'owner_id',
                'status'
            ]
        ];
        $res_plan = seek_api::seekPlan($planParams);
        if (empty($res_plan->data)) return $this->setMsg(3002);

        foreach($res_plan->data as $v)
        {
            $owner_id_arr[] = $v->owner_id;
			$course_id_arr[] = $v->course_id;
        }

		$courseIdStr = implode(',',$course_id_arr);
		//课程信息
		$courseParams = [
			'q'  => ['course_id'=>$courseIdStr],
			'f'  => ['course_id','thumb_med'],
			'pl' => 1000
		];
		$res_course = seek_api::seekCourse($courseParams);

		$courseImgs = array();
		if(!empty($res_course->data))
		{
			foreach($res_course->data as $val)
			{
				$courseImgs[$val->course_id] = $this->imgUrl($val->thumb_med);
			}
		}

		//机构信息
		$domain_ret = user_api::getSubdomainByUidArr($owner_id_arr);
		$domainlist = array();
		if( $domain_ret->result->code == 0 && $domain_ret->result->data){
			$subdomains = $domain_ret->result->data->items;
			foreach($subdomains as $so){
				$domainlist[$so->fk_user] = $this->httpHeader() .'://'.$so->subdomain.'.gn100.com';
			}
		}

        $data = [];
        foreach($res_plan->data  as $val)
        {
            $data[] = [
                'planId'      => $val->plan_id,
                'courseTitle' => $val->course_name,
				'courseImage' => !empty($courseImgs[$val->course_id])?$courseImgs[$val->course_id]:'',
                'className'   => $val->class_name,
                'sectionName' => $val->section_name,
                'sectionDesc' => $val->section_desc,
                'startTime'   => date('Y-m-d H:i',strtotime($val->start_time)),
                'status'       => $val->status,
                'courseType'  => 0,
                'address'      => '北京市海淀区北四环48号',
            ];
        }

        return $this->setData($data);
    }

	/*
	 * 章节列表
	 */
	public function pageGetPlanList()
	{
		$this->v([
			'courseId' => 1000,
			'classId'  => 1000
		]);
		$courseId = $this->paramsInfo['params']['courseId'];
		$classId  = $this->paramsInfo['params']['classId'];
		$params = [
			'q' => ['course_id'=>$courseId,'class_id'=>$classId,'status'=>'1,2,3'],
			'f' => ['plan_id','section_id','section_name','section_desc','start_time',
					'status','video_public_type','owner_id'],
			'ob'=> ['plan_id'=>'asc']
		];
		$res_plan = seek_api::seekPlan($params);
		if (empty($res_plan->data)) return $this->setMsg(3002);

		foreach($res_plan->data as $v)
        {
            $owner_id_arr[] = $v->owner_id;
        }

		$domain_ret = user_api::getSubdomainByUidArr($owner_id_arr);

		$domainlist = array();
		if( $domain_ret->result->code == 0 && $domain_ret->result->data)
		{
			$subdomains = $domain_ret->result->data->items;
			foreach($subdomains as $so)
			{
				$domainlist[$so->fk_user] = $this->httpHeader() .'://'.$so->subdomain.'.gn100.com';
			}
		}

		$data = array();
		foreach($res_plan->data as $v)
		{
			$data['data'][] = [
				"planId"    => $v->plan_id,
                "status"    => $v->status,
                "sectionId" => $v->section_id,
                "name"      => $v->section_name,
                "desc"      => $v->section_desc,
                "startTime" => date('m-d H:i',strtotime($v->start_time)),
				"videoPublicType" => $v->video_public_type,
                "playUrl"   => !empty($domainlist[$v->owner_id])?$domainlist[$v->owner_id].'/course.plan.play/'.$v->plan_id:''
			];
		}

		return $this->setData($data);
	}

	/*
	 * 发送评价
	 */
	public function pageAddComment()
	{
		$this->v([
			'planId' => 1000,
			'uid'    => 1000,
			'content'=> 1000
		]);

		$planId       = (int)($this->paramsInfo['params']['planId']);
		$uid          = (int)($this->paramsInfo['params']['uid']);
		$content      = $this->paramsInfo['params']['content'];
		$satisfaction = isset($this->paramsInfo['params']['satisfaction'])?(int)($this->paramsInfo['params']['satisfaction']):5;
		$conform      = isset($this->paramsInfo['params']['conform'])?(int)($this->paramsInfo['params']['conform']):5;
		$expression   = isset($this->paramsInfo['params']['expression'])?(int)($this->paramsInfo['params']['expression']):5;

		$params = [
			'q' => ['plan_id'=>$planId],
			'f' => ['course_id','teacher_id','plan_id','owner_id']
		];
		$res_plan = seek_api::seekPlan($params);
		if (empty($res_plan->data)) return $this->setMsg(3002);

		$data = [
			'course_id'    => $res_plan->data[0]->course_id,
			'user_id'      => $uid,
			'plan_id'      => $res_plan->data[0]->plan_id,
			'user_owner'   => $res_plan->data[0]->owner_id,
			'user_teacher' => $res_plan->data[0]->teacher_id,
			'comment'      => $content,
			'score'=> $satisfaction,
		];
		$res_content   = comment_api::addScore($data, $uid);
		if($res_content['code']==1)
		{
			$data = [
				'code'    => 0,
				'message' => 'success',
				'result'  => 1
			];
		}else{
			$data = [
				'code'    => 1,
				'message' => 'failure',
				'result'  => 0
			];
		}

		return json_encode($data);
	}

	/*
	 * 排名列表
	 */
/**
	@deprecated by hetao  2016/12/8
 */
/*
	public function pagePlanGood()
	{
		$this->v(['planId'=>1000]);
		$planId = (int)($this->paramsInfo['params']['planId']);
		$goods = message_api::getGood($planId);
		$data=array();
		if($goods){
			$resData=array();
			foreach($goods as $good)
			{
				$o = new stdclass;
				$o->name  = $good->UserToName;
				$o->stuId = (int)($good->UserIdTo);
				$o->image = $this->imgUrl($good->UserToThumb);
				$o->praise= (int)($good->Content);
				$resData[] = $o;
			}
			if (empty($resData)) return $this->setMsg(3002);
			usort($resData,function($a,$b){
				if($a->praise == $b->praise)return 0;
				return $a->praise<$b->praise?-1:1;
			});
			$data['data'] = $resData;
		}
		return $this->setData($data);
	}
*/

	/*
	 * 根据某个字段排序
	 * @params $data $orderBy(排序顺序标志 SORT_DESC 降序；SORT_ASC 升序) $field(排序字段)
	 */
	private function mysort($data,$orderBy,$field)
	{
		$sort = [
			'direction' => $orderBy,
			'field'     => $field
		];

		$arrSort = array();

		foreach($data AS $uniqid => $row)
		{
			foreach($row AS $key=>$value)
			{
				$arrSort[$key][$uniqid] = $value;
			}
		}

		if($sort['direction'])
		{
			array_multisort($arrSort[$sort['field']], constant($sort['direction']), $data);
		}

		return $data;
	}


    public function pageCheck()
    {
        $this->v(
            [
                'uid' => 1000,
                'planId' => 1000
            ]
        );

        $publish = live_publish::getByuid($this->paramsInfo['params']['uid']);

        if (empty($publish))
            return $this->setMsg(2002);

        if ($publish->live_call == 'publish_done')
            return $this->setMsg(2003);

        if ($publish->plan_id > 0 && $publish->plan_id != $this->paramsInfo['params']['planId'])
            return $this->setMsg(2004);

        return $this->setMsg(0);
    }

    public function pageGetPlanExam()
    {
        $this->v(
            [
                'planId' => 1000,
                'uid' => 1000
            ]
        );

        $data = interface_planApi::getPlanExam($this->paramsInfo['params']['planId'], $this->paramsInfo['params']['uid']);

        if (!empty($data)) return $this->setData($data);

        return $this->setMsg(1);
    }

    public function pageTable()
    {
        $this->v(['uid' => 1000]);

        $index    = !empty($this->paramsInfo['params']['index']) ? $this->paramsInfo['params']['index'] + 0 : 0;
        $length   = !empty($this->paramsInfo['params']['length']) ? $this->paramsInfo['params']['length'] + 0 : 5;
        $identity = isset($this->paramsInfo['params']['identity']) ? $this->paramsInfo['params']['identity'] : 0;

        $start = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), date('d') - 7, date('Y')));
        $end   = date("Y-m-d H:i:s", mktime(23, 59, 59, date('m'), date('d') - 8 + $length * 7, date('Y')));

        if ($index) {
            $start = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), date('d') - 7 + $index * $length * 7, date('Y')));
            $end   = date("Y-m-d H:i:s", mktime(23, 59, 59, date('m'), date('d') - 8 + ($index + 1) * $length * 7, date('Y')));
        }
        $d = date('w', strtotime($start));
        if ($d) {
            $start = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), date('d') - $d - 7 + $index * $length * 7, date('Y')));
            $end   = date("Y-m-d H:i:s", mktime(23, 59, 59, date('m'), date('d') - $d - 8 + ($index + 1) * $length * 7, date('Y')));
        }

        //$start = '2015-10-10 00:00:00';
        //$end = '2015-10-15 23:59:59';
        $timeArr = $this->_timeFormat($start, $end);

        $res = $this->_getData(
            $start,
            $end,
            $this->paramsInfo['params']['uid'],
            $identity
        );
        if (empty($res)) return $this->setMsg(3002);

        $amPlans = $pmPlans = $ngPlans = $status = [];

        list($amS, $amE, $pmS, $pmE) = [
            strtotime('00:00'),
            strtotime('13:00'),
            strtotime('13:00'),
            strtotime('19:00')
        ];

        $result['today'] = date('Y-m-d', time());
        foreach ($timeArr as $k => $m) {
            $result['data'][$k] = [
                'day'  => date('w', strtotime($m)),
                'date' => date('Y-m-d', strtotime($m)),
                //'am' => [],
                //'pm' => [],
                //'night' => [],
            ];

            foreach ($res as $v) {
                if ($m == date('Y-m-d', strtotime($v->start_time))) {
                    $time  = date('H:i', strtotime($v->start_time));
                    $tTime = strtotime($time);

                    if ($tTime >= $amS && $tTime < $amE) {
                        $status[$m][] = $v->status;
                        $ps           = array_values($status[$m]);

                        if (in_array(2, $ps)) {
                            $st = 2;
                        } elseif (in_array(1, $ps)) {
                            $st = 1;
                        } else {
                            $st = 3;
                        }

                        $result['data'][$k]['am'] = [
                            'startTime' => $time,
                            'status'    => $st
                        ];

                        $amPlans[$m][] = [
                            'planId'   => $v->plan_id,
                            'time'     => $v->start_time,
                            'courseId' => $v->course_id
                        ];

                        $result['data'][$k]['am']['plans'] = $amPlans[$m];
                    } elseif ($tTime >= $pmS && $tTime < $pmE) {
                        $status[$m][] = $v->status;
                        $ps           = array_values($status[$m]);

                        if (in_array(2, $ps)) {
                            $st = 2;
                        } elseif (in_array(1, $ps)) {
                            $st = 1;
                        } else {
                            $st = 3;
                        }

                        $result['data'][$k]['pm'] = [
                            'startTime' => $time,
                            'status'    => $st
                        ];

                        $pmPlans[$m][] = [
                            'planId'   => $v->plan_id,
                            'courseId' => $v->course_id
                        ];

                        $result['data'][$k]['pm']['plans'] = $pmPlans[$m];
                    } else {
                        $status[$m][] = $v->status;
                        $ps           = array_values($status[$m]);

                        if (in_array(2, $ps)) {
                            $st = 2;
                        } elseif (in_array(1, $ps)) {
                            $st = 1;
                        } else {
                            $st = 3;
                        }

                        $result['data'][$k]['night'] = [
                            'startTime' => $time,
                            'status'    => $st,
                        ];

                        $ngPlans[$m][] = [
                            'planId'   => $v->plan_id,
                            'courseId' => $v->course_id
                        ];

                        $result['data'][$k]['night']['plans'] = $ngPlans[$m];
                    }
                }
            }
        }
        $result['data'] = array_chunk($result['data'], 7);

        foreach ($result['data'] as $k => $n) {
            $i = 0;
            foreach ($n as $b) {
                if (empty($b['am']) && empty($b['pm']) && empty($b['night'])) {
                    $i++;
                }
            }
            if ($i == 7) {
                $result['data'][$k][0]['weekStatus'] = '0';
            } else {
                $result['data'][$k][0]['weekStatus'] = '1';
            }
        }

        return $this->setData($result);
    }

    public function pageTableV2()
    {
        $this->v(['uid' => 1000]);

        $index  = !empty($this->paramsInfo['params']['index']) ? $this->paramsInfo['params']['index'] + 0 : 0;
        $length = !empty($this->paramsInfo['params']['length']) ? $this->paramsInfo['params']['length'] + 0 : 5;

        $start = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), date('d') - 7, date('Y')));
        $end   = date("Y-m-d H:i:s", mktime(23, 59, 59, date('m'), date('d') - 8 + $length * 7, date('Y')));

        if ($index) {
            $start = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), date('d') - 7 + $index * $length * 7, date('Y')));
            $end   = date("Y-m-d H:i:s", mktime(23, 59, 59, date('m'), date('d') - 8 + ($index + 1) * $length * 7, date('Y')));
        }
        $d = date('w', strtotime($start));
        if ($d) {
            $start = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), date('d') - $d - 7 + $index * $length * 7, date('Y')));
            $end   = date("Y-m-d H:i:s", mktime(23, 59, 59, date('m'), date('d') - $d - 8 + ($index + 1) * $length * 7, date('Y')));
        }

        //$start = '2015-10-10 00:00:00';
        //$end = '2015-10-15 23:59:59';
        $timeArr = $this->_timeFormat($start, $end);
        $res     = interface_planApi::getTable($start, $end, $this->paramsInfo['params']['uid']);
        if (empty($res))  return $this->setData([], 1);

        $amPlans = $pmPlans = $ngPlans = $status = [];

        list($amS, $amE, $pmS, $pmE) = [
            strtotime('00:00'),
            strtotime('13:00'),
            strtotime('13:00'),
            strtotime('19:00')
        ];

        $result['today'] = date('Y-m-d', time());
        foreach ($timeArr as $k => $m) {
            $result['data'][$k] = [
                'day'  => date('w', strtotime($m)),
                'date' => date('Y-m-d', strtotime($m)),
                //'am' => [],
                //'pm' => [],
                //'night' => [],
            ];

            foreach ($res as $v) {
                if ($m == date('Y-m-d', strtotime($v->start_time))) {
                    $time  = date('H:i', strtotime($v->start_time));
                    $tTime = strtotime($time);

                    if ($tTime >= $amS && $tTime < $amE) {
                        $status[$m][] = $v->status;
                        $ps           = array_values($status[$m]);

                        if (in_array(2, $ps)) {
                            $st = 2;
                        } elseif (in_array(1, $ps)) {
                            $st = 1;
                        } else {
                            $st = 3;
                        }

                        $result['data'][$k]['am'] = [
                            'startTime' => $time,
                            'status'    => $st,
                            'student'   => $v->student[$v->plan_id]
                        ];

                        $amPlans[$m][] = [
                            'planId'   => $v->plan_id,
                            'time'     => $v->start_time,
                            'courseId' => $v->course_id
                        ];

                        $result['data'][$k]['am']['plans'] = $amPlans[$m];
                    } elseif ($tTime >= $pmS && $tTime < $pmE) {
                        $status[$m][] = $v->status;
                        $ps           = array_values($status[$m]);

                        if (in_array(2, $ps)) {
                            $st = 2;
                        } elseif (in_array(1, $ps)) {
                            $st = 1;
                        } else {
                            $st = 3;
                        }

                        $result['data'][$k]['pm'] = [
                            'startTime' => $time,
                            'status'    => $st,
                            'student'   => $v->student[$v->plan_id]
                        ];

                        $pmPlans[$m][] = [
                            'planId'   => $v->plan_id,
                            'courseId' => $v->course_id
                        ];

                        $result['data'][$k]['pm']['plans'] = $pmPlans[$m];
                    } else {
                        $status[$m][] = $v->status;
                        $ps           = array_values($status[$m]);

                        if (in_array(2, $ps)) {
                            $st = 2;
                        } elseif (in_array(1, $ps)) {
                            $st = 1;
                        } else {
                            $st = 3;
                        }

                        $result['data'][$k]['night'] = [
                            'startTime' => $time,
                            'status'    => $st,
                            'student'   => $v->student[$v->plan_id]
                        ];

                        $ngPlans[$m][] = [
                            'planId'   => $v->plan_id,
                            'courseId' => $v->course_id
                        ];

                        $result['data'][$k]['night']['plans'] = $ngPlans[$m];
                    }
                }
            }
        }
        $result['data'] = array_chunk($result['data'], 7);

        foreach ($result['data'] as $k => $n) {
            $i = 0;
            foreach ($n as $b) {
                if (empty($b['am']) && empty($b['pm']) && empty($b['night'])) {
                    $i++;
                }
            }
            if ($i == 7) {
                $result['data'][$k][0]['weekStatus'] = '0';
            } else {
                $result['data'][$k][0]['weekStatus'] = '1';
            }
        }

        return $this->setData($result);
    }

    private function _getData($start, $end, $uid, $type)
    {
        $query                 = [
            'status'       => '1,2,3',
            'start_time'   => "{$start},{$end}"
        ];

        // 身份为学生时
        if ($type == 0) {
            $courseClassIdArr          = $this->_getCourseStrByUser($uid);
            if (!empty($courseClassIdArr['courseIdArr'])) {
                $query['course_id'] = implode(',', $courseClassIdArr['courseIdArr']);
                $query['class_id'] = implode(',', $courseClassIdArr['classIdArr']);
            } else {
                return [];
            }
        }
        // 身份为老师时
        if ($type == 1) {
            // 查询主讲老师(班主任)
            $query['admin_id'] = $uid;
        }

        $params = [
            'f' => [
                'plan_id',
                'course_id',
                'start_time',
                'status'
            ],
            'q' => $query,
            'ob' => ['start_time' => 'asc'],
            'p' =>1,
            'pl'=>10000,
        ];

        $res = seek_api::seekPlan($params);
        if (empty($res->data)) return [];

        return $res->data;
    }

    private function _getCourseStrByUser($uid)
    {
        //$org = user_organization::subdomain();

        //$orgOwner = !empty($org) ? $org->userId : 0;
        $queryArr = array(
            'dbName' => 'db_course',
            'table' => 't_course_user',
            'pk' => 'fk_course',
            'condition' => " status=1 AND fk_user={$uid}"
        );

        $data['courseIdArr'] = $data['classIdArr'] = [];
        $res = common_api::getIdStr($queryArr);

        if (empty($res->result->items)){
            return $data;
        }

        foreach ($res->result->items  as $v) {
            $data['courseIdArr'][] = $v->fk_course;
            $data['classIdArr'][] = $v->fk_class;
        }


        return $data;
    }

    private function _timeFormat($start, $end)
    {
        $time = range(strtotime($start), strtotime($end), 24*60*60);

        return array_map(create_function('$v', 'return date("Y-m-d", $v);'), $time);
    }

    private function _pageArray($arr, $page=1, $length=5)
    {
        $page = $page ?: 1;
        $start = ($page-1) * $length;
        $totalSize = count($arr);
        $totalPage = ceil($totalSize/$length);

        $result['totalPage'] = $totalPage;
        $result['totalSize'] = $totalSize;
        $result['data'] = array_slice($arr, $start, $length);

        return $result;
    }

    public function pageCopyUrl()
    {
        $data = [];
        if (isset($this->paramsInfo['params']['courseId'])) {
            $data['courseId'] = $this->paramsInfo['params']['courseId'];
        }

        if (isset($this->paramsInfo['params']['planId'])) {
            $data['planId'] = $this->paramsInfo['params']['planId'];
        }

        if (count($data) < 1)
            return interface_func::setMsg(1000);

        return interface_func::setData(
            [
                'url' => interface_courseApi::getDomainUrl($data)
            ]
        );
    }

	public function pageGetComment(){

		$this->v(['planId'=>1000,'uid'=>1000]);

		$planId = $this->paramsInfo['params']['planId'];
		$uid    = $this->paramsInfo['params']['uid'];

		$params = [
            'condition' => "fk_user={$uid} and fk_plan={$planId}"
		];

		$planParams = [
			'q' => ['plan_id'=>$planId],
			'f' => ['course_id','section_name','section_desc']
		];
		$resPlan = seek_api::seekPlan($planParams);

		$planName    = !empty($resPlan->data[0]->section_name) ? $resPlan->data[0]->section_name : '';
		$sectionName = !empty($resPlan->data[0]->section_desc) ? $resPlan->data[0]->section_desc : '';

        $resComment = comment_api::getCommentList($params);
		if (empty($resComment->items[0])) return $this->setMsg(3002);

		$comment  = $resComment->items[0];
		$courseId = $comment->fk_course;

		$detail = comment_api::getDetail(array('course_id'=>$courseId,'plan_id'=>$planId,'user_id'=>$uid),'');

		$data = [
		   "content"      => !empty($comment->comment) ? html_entity_decode($comment->comment) : '',
		   "satisfaction" => empty($detail[0]->avg_score)?0:$detail[0]->avg_score,
		   "conform"      => empty($detail[0]->avg_score)?0:$detail[0]->avg_score,
		   "expression"   => empty($detail[0]->avg_score)?0:$detail[0]->avg_score,
		   "commentTime"  => $detail[0]->last_updated,
		   "planName"     => $planName.' '.$sectionName
		];

		return $this->setData($data);

	}

    /*
     * 直播列表
     * @param uid     用户Id
     * @param condition 兴趣筛选Id
     * @param living    0 即将直播(后7天) 1 正在直播或已播放(前3天)
     */
    public function pageLives(){
        $uid      = isset($this->paramsInfo['params']['uid']) ? (int)($this->paramsInfo['params']['uid']) : 0;
        $living   = isset($this->paramsInfo['params']['living']) ? (int)($this->paramsInfo['params']['living']) : 0;
        $condition= !empty($this->paramsInfo['params']['condition']) ? $this->paramsInfo['params']['condition'] : '';

        //按日期查询plan
		if($living == 0){
            $startTime = date('Y-m-d 00:00:00').','.date('Y-m-d 23:59:59',strtotime('+7 days'));
            $planQuery = [
                'start_time'   => $startTime,
                'status'       => '1,2,3',
                'couorse_type' =>1
            ];
			$ob = ['start_time'=>'asc'];
        }else{
            $startTime = date('Y-m-d 23:59:59',strtotime('-7 days')).','.date('Y-m-d 00:00:00');
            $planQuery = [
                'start_time' => $startTime,
                'status'=>'3',
                'couorse_type'=>1
            ];
			$ob = ['start_time'=>'desc'];
        }
		$planParams = [
            'q' => $planQuery,
            'f' => ['plan_id','course_id'],
            'ob'=> $ob,
            'p' => 1,
            'pl'=> 1000
        ];

        $planRes = seek_api::seekPlan($planParams);
        if (empty($planRes->data)) return $this->setMsg(3002);
		
		//按兴趣查询course
		$courseIdArr = [];
		$planIdArr   = [];
        foreach ($planRes->data as $v) {
            $courseIdArr[$v->course_id] = $v->course_id;
			$planIdArr[$v->plan_id]     = $v->plan_id;
        }
        $courseQuery = [
            'admin_status' => 1,
            'third_cate'   => $condition,
            'status'       => '1,2,3',
            'course_id'    => implode(',', $courseIdArr)
        ];
        $courseParams = [
            'f' => ['course_id','second_cate','thumb_med','fee_type','price'],
            'q' => $courseQuery,
            'p' => 1,
            'pl'=> count($courseIdArr)
        ];
        $courseRes = seek_api::seekCourse($courseParams);
        if (empty($courseRes->data)) return $this->setMsg(3002);
		
		foreach($courseRes->data as $val){
			$courseIdArrs[$val->course_id] = $val->course_id;
			$courseInfo[$val->course_id]   = [
				'img'     => $val->thumb_med,
				'feeType' => $val->fee_type,
				'price'   => $val->price
			];
		}
		
		//报名用户信息
		$userCourse = array();
        if ($uid > 0) {
            $regRes = interface_user_api::getUserRegCourse($uid, $courseIdArrs);
            if (!empty($regRes['items'])) {
                foreach ($regRes['items'] as $v) {
                    $userCourse[$v['fk_course']] = $v['fk_course'];
                }
            }
        }
		
		//拼装数据
		$planQuery['course_id'] = implode(',', $courseIdArrs);
		$planParams = [
            'q' => $planQuery,
            'f' => [
					'plan_id','class_id','class_name','course_id','course_name','status','start_time',
                    'teacher_name','teacher_real_name','section_name','live_public_type',
                  ],
            'ob'=> $ob,
            'p' => 1,
            'pl'=> count($planIdArr)
        ];

        $planRes = seek_api::seekPlan($planParams);
        if (empty($planRes->data)) return $this->setMsg(3002);

		foreach($planRes->data as $v){
            $stime = date('Y-m-d',strtotime($v->start_time));
            $data['data'][$stime]['date'] = $stime;
            $data['data'][$stime]['courses'][] = [
                'planId'     => $v->plan_id,
                'classId'    => $v->class_id,
                'className'  => $v->class_name,
                'setionName' => $v->section_name,
                'courseId'   => $v->course_id,
                'courseName' => $v->course_name,
                'courseImage'=> !empty($courseInfo[$v->course_id]['img']) ? $this->imgUrl($courseInfo[$v->course_id]['img']) : '',
                'teacherName'=> !empty($v->teacher_real_name) ? $v->teacher_real_name : (!empty($v->teacher_name) ? $v->teacher_name : ''),
                'status'     => $v->status,
                'time'       => date('H:i',strtotime($v->start_time)),
                'userStatus' => !empty($userCourse[$v->course_id]) ? 1 : 0,
                'feeType'    => !empty($courseInfo[$v->course_id]['feeType']) ? $courseInfo[$v->course_id]['feeType'] : 0,
                'price'      => !empty($courseInfo[$v->course_id]['price']) ? $courseInfo[$v->course_id]['price']/100 : 0,
                'livePublicType'=>$v->live_public_type
            ];
        }

        $data['data'] = array_values($data['data']);

        return $this->setData($data);        
    }

	/*
	 * 章节信息
	 * @param  courseId 课程id
	 * @param  uid      用户id
	 */
	 public function pageInfo(){
		$this->v(['courseId'=>1000,'uid'=>1000]);
		$courseId = (int)($this->paramsInfo['params']['courseId']);
		$uid      = (int)($this->paramsInfo['params']['uid']);

		$regInfo = course_api::checkIsRegistration($uid, $courseId);
		if(empty($regInfo['classId'])) return $this->setMsg(3002);
	
		$params = [
			'q' => ['course_id'=>$courseId,'status'=>'1,2,3','class_id'=>$regInfo['classId']],
			'f' => ['plan_id','course_name','section_id','section_name','section_desc','course_type',
					'teacher_name','teacher_real_name','start_time','status','comment','class_name','video_id'
                ],
            'p' => 1,
            'pl'=> 20
		];

		$planRes = seek_api::seekPlan($params);
		if (empty($planRes->data)) return $this->setMsg(3002);

		$planIdArr = array_reduce($planRes->data, create_function('$v,$w', '$v[$w->plan_id]=$w->plan_id;return $v;'));


		$commentRet = comment_api::checkIsCommentByPlanId($planIdArr,$uid);

		$checkComm  = array();
		if(!empty($commentRet->result->items)){
			foreach($commentRet->result->items as $v){
				$checkComm[$v->fk_plan] = 1;
			}
		}

		$time = array();
		foreach($planRes->data as $v){
			if(!empty($v->start_time)){
				$newYear  = date("Y");
				$planYear = date("Y",strtotime($v->start_time));
				if($newYear > $planYear){
					$time[$v->plan_id] = date('Y年m月d日', strtotime($v->start_time));
				}else{
					$time[$v->plan_id] = date('m月d日', strtotime($v->start_time));
				}
			}
			
			$data[] = [
				'planId'       => $v->plan_id,
				'courseName'   => $v->course_name,
				'className'    => $v->class_name,
				'sectionId'    => $v->section_id,
				'sectionName'  => $v->section_name,
				'sectionDesc'  => $v->section_desc,
				'teacher'      => !empty($v->teacher_real_name) ? $v->teacher_real_name : (!empty($v->teacher_name) ? $v->teacher_name : ''),
				'time'         => !empty($time[$v->plan_id]) ? $time[$v->plan_id] : '',
				'type'         => $v->course_type,
				'address'      => ($v->status==3) ? '线下课' : '',
				'status'       => $v->status,
				'hasVideo'     => !empty($v->video_id) ? 1 : 0,
				'common'       => ($v->status == 1) ? 2 : (!empty($checkComm[$v->plan_id]) && $checkComm[$v->plan_id] > 0 ? 1 : 0)
			];
		}

		return $this->setData($data);
	 }

    public function pageInfoV2(){
        $this->v(['courseId' => 1000, 'uid' => 1000]);
        $courseId = (int)($this->paramsInfo['params']['courseId']);
        $uid      = (int)($this->paramsInfo['params']['uid']);

		$regInfo = course_api::checkIsRegistration($uid, $courseId);
		if(empty($regInfo['classId'])) return $this->setMsg(3002);
		
        $paramsCourse = [
            'q' => [
                'course_id'  => $courseId,'class_id'=>$regInfo['classId']
            ],
            'f' => ['thumb_med']
        ];

        $courseInfo = seek_api::seekCourse($paramsCourse);
        if (empty($courseInfo->data[0])) return $this->setMsg(3002);
        $image = interface_func::imgUrl($courseInfo->data[0]->thumb_med);

        $paramsPlan = [
            'q'  => ['course_id' => $courseId, 'status' => '1,2,3','class_id'=>$regInfo['classId']],
            'f'  => [
                'plan_id', 'course_name', 'section_id', 'section_name', 'section_desc', 'course_type',
                'teacher_name', 'teacher_real_name', 'start_time', 'status', 'comment', 'class_name',
                'address', 'region_level0', 'region_level1', 'region_level2','course_id','class_id','video_id','totaltime'
            ],
            'p'  => 1,
            'pl' => 20,
			'ob' => ['plan_id'=>'asc']
        ];

        $planRes = seek_api::seekPlan($paramsPlan);
        if (empty($planRes->data)) return $this->setMsg(3002);

        $planIdArr = $videoIdArr = [];
        foreach ($planRes->data as $plan) {
            $planIdArr[$plan->plan_id]   = $plan->plan_id;
            $videoIdArr[$plan->video_id] = $plan->video_id;
        }

        $commentRet = comment_api::checkIsCommentByPlanId($planIdArr,$uid);
        $isComment  = [];
        if(!empty($commentRet->result->items)){
            foreach($commentRet->result->items as $v){
                $isComment[$v->fk_plan] = 1;
            }
        }

        //$uploadVideoInfo = interface_planApi::getVideoList($videoIdArr);

        $address = '';
		$time    = array();
        $region = region_geo::$region;
		$statusName = array('normal'=>'1','living'=>2,'finished'=>3,'-1'=>'invalid','0'=>'initial');
        foreach ($planRes->data as $v) {
            if ($v->course_type === 3) {
                $regionLevel0 = !empty($region[$v->region_level0]) ? $region[$v->region_level0] : '';
                $regionLevel1 = !empty($region[$v->region_level1]) ? $region[$v->region_level1] : '';
                $regionLevel2 = !empty($region[$v->region_level2]) ? $region[$v->region_level2] : '';
                $address = $regionLevel0.$regionLevel1.$regionLevel2.$v->address;
            }
			if(!empty($v->start_time)){
				$newYear  = date("Y");
				$planYear = date("Y",strtotime($v->start_time));
				if($newYear > $planYear){
					$time[$v->plan_id] = date('Y年m月d日', strtotime($v->start_time));
				}else{
					$time[$v->plan_id] = date('m月d日 H:i', strtotime($v->start_time));
				}
			}

            if ($v->video_id) {
                $videoIdArr[$v->video_id] = $v->video_id;
            }

			$status = course_api::getPlanStatusV2($v->plan_id);
			if(!empty($status)){
				$planStatus[$v->plan_id] = $statusName[$status->plan_status];
			}else{
				$planStatus[$v->plan_id] = 1;
			}
			
            $data[] = [
                'planId'      => $v->plan_id,
                'courseId'    => $v->course_id,
                'courseName'  => $v->course_name,
                'className'   => $v->class_name,
                'classId'     => $v->class_id,
                'sectionId'   => $v->section_id,
                'sectionName' => $v->section_name,
                'sectionDesc' => $v->section_desc,
                'teacher'     => !empty($v->teacher_real_name) ? $v->teacher_real_name : (!empty($v->teacher_name) ? $v->teacher_name : ''),
                'time'        => !empty($time[$v->plan_id]) ? $time[$v->plan_id] : '',
                'type'        => $v->course_type,
                'address'     => $address,
                'status'      => $planStatus[$v->plan_id],
                'hasVideo'    => !empty($v->video_id) ? 1 : 0,
                'common'      => ($v->status == 1) ? 2 : (!empty($isComment[$v->plan_id]) && $isComment[$v->plan_id] > 0 ? 1 : 0),
                'downUrl'     => interface_planApi::getPlanDownLoadUrl($v->plan_id),
                'duration'    => utility_tool::sec2time($v->totaltime),
                //'duration'    => !empty($uploadVideoInfo[$v->plan_id]['duration']) ? $uploadVideoInfo[$v->plan_id]['duration'] : 0,
                //'size'        => !empty($uploadVideoInfo[$v->plan_id]['size']) ? $uploadVideoInfo[$v->plan_id]['size'] : 0,
                'size'        => 0,
                'image'       => $image,
                'videoCount'  => count($videoIdArr)
            ];
        }

        return $this->setData($data);
    }

    //发送答题卡
	public function pageSendExam()
	{
		$this->v(
            [
                'phrase_id' => 1000,
                'plan_id'   => 1000,
				'userToken'=> 1000,
				'uid'      => 1000
            ]
        );

		$phraseId   = (int)($this->paramsInfo['params']['phrase_id']);
		$planId     = (int)($this->paramsInfo['params']['plan_id']);
		$uid        = (int)($this->paramsInfo['params']['uid']);
		$userToken  = $this->paramsInfo['params']['userToken'];
		$answerRight= !empty($this->paramsInfo['params']['answer_right']) ? $this->paramsInfo['params']['answer_right'] : '';

		$content = !empty($this->paramsInfo['params']['content']) ? json_decode($this->paramsInfo['params']['content']) : '';
		$questId = course_api::addPlanPhrase(array('phraseId'=>$phraseId,'planId'=>$planId,'answerRight'=>$answerRight));

		if($questId){
			$content->id = "{$questId}";
			$data = [
				'content' => json_encode($content),
				'type'    => (int)($this->paramsInfo['params']['type']),
				'uid'     => $uid,
				'plan_id' => $planId,
				'userToken' => $userToken,
				'user_from_id' => (int)($this->paramsInfo['params']['user_from_id'])
			];
			$messageRes = message_api::addMsgV2($data, $uid,$userToken);
			if(!empty($messageRes)){
				$messageInfo = json_decode($messageRes->info);
				if($messageInfo->code == 0){
					$messageRes->questId = "{$questId}";
					$messageRes->phraseType = "{$phraseId}";
					return $this->setData($messageRes);
				}
			}
		}
        return $this->setMsg(1);
	}

	//公布答案
	public function pagePublishAnswer()
	{
		$this->v(
            [
				'content'=>1000,'plan_id'=>1000,'userToken'=> 1000,
				'uid'=>1000,'type'=> 1000,'user_from_id'=>1000
            ]
        );

		$data       = $this->paramsInfo['params'];
		$uid        = (int)($this->paramsInfo['params']['uid']);
		$userToken  = $this->paramsInfo['params']['userToken'];

		$data = [
			'content' => $this->paramsInfo['params']['content'],
			'plan_id' => $this->paramsInfo['params']['plan_id'],
			'type'    => $this->paramsInfo['params']['type'],
			'uid'     => $this->paramsInfo['params']['uid'],
			'userToken' => $this->paramsInfo['params']['userToken'],
			'user_from_id' => $this->paramsInfo['params']['user_from_id']
		];

		$messageRes = message_api::addMsgV2($data, $uid,$userToken);

		$dbFlag = 0;
		if(!empty($messageRes)){
			$messageInfo = json_decode($messageRes->info);
			if($messageInfo->code == 0){
				//插入log表
				if(!empty($this->paramsInfo['params']['data'])){
					$userData = json_decode($this->paramsInfo['params']['data']);
					$userLogRes = stat_api::addPlanPhraseLog($userData);
					if($userLogRes){
						$dbFlag = 1;
					}
				}
				$messageRes->dbFlag = $dbFlag;
				return $this->setData($messageRes);
			}
		}
		return $this->setMsg(1);
	}

	//学生上课详情
	public function pageStudentCourseInfo()
	{
		$this->v([
            'uid' => 1000,
            'planId' => 1000
        ]);

        $uid    = $this->paramsInfo['params']['uid'];
		$planId = trim($this->paramsInfo['params']['planId'],',');
        $params = [
            'q' => ['plan_id'=> $planId,'status' => '1,2,3','course_type'=>'1,3'],
            'p' => 1,
            'f' => [
                'plan_id','course_name','class_name','section_name','section_desc','status','course_id',
                'start_time','teacher_name','teacher_id','teacher_thumb_big','owner_id','course_type','region_level0',
				'region_level1','region_level2','address'
            ],
            'ob' => ['start_time'=>'desc']
        ];
        $seekPlan = seek_api::seekPlan($params);
        if (empty($seekPlan->data)) return $this->setMsg(3002);

		$courseIdArr = array();
		foreach($seekPlan->data as $val){
			$courseIdArr[] = $val->course_id;
		}
		if(!empty($courseIdArr)){
			$courseIds = implode(',',$courseIdArr);
		}

		$courseParams = [
			'q' => ['course_id'=>$courseIds],
			'f' => ['thumb_med','course_id','subdomain'],
			'p' => 1,
			'pl'=>1000
		];
		$seekCourse = seek_api::seekCourse($courseParams);
		$courseInfo = array();
		if(!empty($seekCourse->data)){
			foreach($seekCourse->data as $val){
				$courseInfo[$val->course_id] = [
					'thumbMed' => $this->imgUrl($val->thumb_med),
					'subdomain'=> $val->subdomain
				];
			}
		}

		//取数据库中的上课状态
        $status = course_api::getPlanStatusV2(explode(',',$planId));
		$statusName = array('normal'=>'1','living'=>2,'finished'=>3,'-1'=>'invalid','0'=>'initial');
		
		if(!empty($status)){
			foreach($status as $key=>$val){
				$planStatus[$key] = !empty($statusName[$val->plan_status]) ? $statusName[$val->plan_status] : 1;
			}
		}
	
		$address = array();
        foreach($seekPlan->data  as $val){
			if($val->course_type == 3){
				$regionLevel0 = !empty(region_geo::$region[$val->region_level0]) ? region_geo::$region[$val->region_level0] : '';
				$regionLevel1 = !empty(region_geo::$region[$val->region_level1]) ? region_geo::$region[$val->region_level1] : '';
				$regionLevel2 = !empty(region_geo::$region[$val->region_level2]) ? region_geo::$region[$val->region_level2] : '';
				$address[$val->course_id] = $regionLevel0.$regionLevel1.$regionLevel2.$val->address;
			}
            $data[] = [
                "planId"      => $val->plan_id,
                "courseTitle" => $val->course_name,
                "className"   => $val->class_name,
                "sectionName" => $val->section_name,
                "sectionDesc" => $val->section_desc,
                "startTime"   => date('Y-m-d H:i',strtotime($val->start_time)),
                "status"      => !empty($planStatus[$val->plan_id]) ? $planStatus[$val->plan_id] : 1,
                "address"     => !empty($address[$val->course_id]) ? $address[$val->course_id] : '',
                "teacherName" => !empty($val->teacher_real_name)?$val->teacher_real_name:$val->teacher_name,
                "teacherId"   => $val->teacher_id,
                "teacherImage"=> $this->imgUrl($val->teacher_thumb_big),
                "playUrl"     => !empty($courseInfo[$val->course_id]['subdomain']) ? $this->httpHeader() .'://'.$courseInfo[$val->course_id]['subdomain'].'/course.plan.play/'.$val->plan_id : '',
                "courseImage"=> !empty($courseInfo[$val->course_id]['thumbMed']) ? $courseInfo[$val->course_id]['thumbMed'] : '',
                "courseType"  => $val->course_type
            ];
        }
        return $this->setData($data);
	}
	
	/*
	 *@desc 题目类型
	 *@param $userId $token
	 */
	public function pageQuestionType()
	{
		//$this->v(['userId'=>1000]);
		$type  = (int)$this->paramsInfo['params']['type'];
		
		$phraseRes = course_plan_api::getPhraseByType($type);
		if(empty($phraseRes->result->items)) return $this->setMsg(3002);
		
		foreach($phraseRes->result->items as $val){
			$data['data'][] = [
				'phraseId' => $val->pk_phrase,
				'answer'   => $val->answer
			];
		}
		
		return $this->setData($data);
	}
	
	/*
	 *@desc 获取问题Id
	 *@params $phraseId 问题模板Id $planId 排课Id $answerRight 正确答案
	 */
	public function pageGetQuestionId()
	{
		$this->v(['phraseId'=>1000,'planId'=>1000]);
		$phraseId    = (int)$this->paramsInfo['params']['phraseId'];
		$planId      = (int)$this->paramsInfo['params']['planId'];
		$answerRight = !empty($this->paramsInfo['params']['answerRight']) ? $this->paramsInfo['params']['answerRight'] : '';
		
		$data = [
			'phraseId' => $phraseId,
			'planId'   => $planId,
			'answerRight' => $answerRight
		];
		$questId = course_api::addPlanPhrase($data);
		if(!$questId) return $this->setData(array('code'=>'-1','questId'=>''));
		
		return $this->setData(array('code'=>1,'questId'=>$questId));
	}
	
	/*
	 *@desc 统计答案log
	 *@params $data
	 */
	public function pageAddAnswerLog()
	{
		if(!empty($this->paramsInfo['params']['data'])){	
		    $data = $this->paramsInfo['params']['data'];
		
		    $userLogRes = stat_api::addPlanPhraseLog($data);
		    if(!$userLogRes) return $this->setData(array('code'=>'-1','dbFlag'=>'-1'));
        }
		return $this->setData(array('code'=>1,'dbFlag'=>1));
	}

    public function pageAddQuestionLog()
    {
        $this->v(
            [
                'userId'     => 1000,
                'planId'     => 1000,
                'questionId' => 1000
            ]
        );

        $userId     = (int)($this->paramsInfo['params']['userId']);
        $planId     = (int)($this->paramsInfo['params']['planId']);
        $questionId = (int)($this->paramsInfo['params']['questionId']);
        $data       = $this->paramsInfo['params']['data'];

        $params = [
            'plan_id'     => $planId,
            'question_id' => $questionId,
            'data'        => $data
        ];
        $data = [
            'plan_id'      => $planId,
            'plan_exam_id' => $questionId,
            'status'       => 1
        ];
		//修改出题状态
        course_api::updatePlanExamStatus($data,$userId);
        $res = exam_api::logIssue($params, $userId);

        return $res ? interface_func::setMsg(0) : interface_func::setMsg(1);
    }
	/*
	 *@desc 视频下载列表
	 *@params $data
	 */
	public function pageGetVideoDownloadInfo()
	{
		$this->v(['planId' => 1000, 'uid' => 1000]);
        $planId = (int)($this->paramsInfo['params']['planId']);
        $uid    = (int)($this->paramsInfo['params']['uid']);
        $paramsPlan = [
            'q'  => ['plan_id' => $planId, 'status' => '1,2,3'],
            'f'  => [
                'plan_id', 'course_name', 'section_id', 'section_name', 'section_desc', 'course_type',
                'teacher_name', 'teacher_real_name', 'start_time', 'status', 'comment', 'class_name',
                'address', 'region_level0', 'region_level1', 'region_level2','course_id','class_id','video_id','totaltime'
            ],
            'p'  => 1,
            'pl' => 20,
			'ob' => ['plan_id'=>'asc']
        ];
        $planRes = seek_api::seekPlan($paramsPlan);
		$courseId= !empty($planRes->data[0]->course_id) ? $planRes->data[0]->course_id : 0;
		$paramsCourse = [
            'q' => [
                'course_id'  => $courseId],
            'f' => ['thumb_med']
        ];
		
        $courseInfo = seek_api::seekCourse($paramsCourse);
		
        if (empty($courseInfo->data[0])) return $this->setMsg(3002);
        $image = interface_func::imgUrl($courseInfo->data[0]->thumb_med);
        if (empty($planRes->data)) return $this->setMsg(3002);
        $planIdArr = $videoIdArr = [];
        foreach ($planRes->data as $plan) {
            $planIdArr[$plan->plan_id]   = $plan->plan_id;
            $videoIdArr[$plan->video_id] = $plan->video_id;
        }
        $commentRet = comment_api::checkIsCommentByPlanId($planIdArr,$uid);
        $isComment  = [];
        if(!empty($commentRet->result->items)){
            foreach($commentRet->result->items as $v){
                $isComment[$v->fk_plan] = 1;
            }
        }
        //$uploadVideoInfo = interface_planApi::getVideoList($videoIdArr);
        $address = '';
		$time    = array();
        $region = region_geo::$region;
		$statusName = array('normal'=>'1','living'=>2,'finished'=>3,'-1'=>'invalid','0'=>'initial');
        foreach ($planRes->data as $v) {
            if ($v->course_type === 3) {
                $regionLevel0 = !empty($region[$v->region_level0]) ? $region[$v->region_level0] : '';
                $regionLevel1 = !empty($region[$v->region_level1]) ? $region[$v->region_level1] : '';
                $regionLevel2 = !empty($region[$v->region_level2]) ? $region[$v->region_level2] : '';
                $address = $regionLevel0.$regionLevel1.$regionLevel2.$v->address;
            }
			if(!empty($v->start_time)){
				$newYear  = date("Y");
				$planYear = date("Y",strtotime($v->start_time));
				if($newYear > $planYear){
					$time[$v->plan_id] = date('Y年m月d日', strtotime($v->start_time));
				}else{
					$time[$v->plan_id] = date('m月d日 H:i', strtotime($v->start_time));
				}
			}

            if ($v->video_id) {
                $videoIdArr[$v->video_id] = $v->video_id;
            }

			$status = course_api::getPlanStatusV2($v->plan_id);
			if(!empty($status)){
				$planStatus[$v->plan_id] = $statusName[$status->plan_status];
			}else{
				$planStatus[$v->plan_id] = 1;
			}
			
            $data[] = [
                'planId'      => $v->plan_id,
                'courseId'    => $v->course_id,
                'courseName'  => $v->course_name,
                'className'   => $v->class_name,
                'classId'     => $v->class_id,
                'sectionId'   => $v->section_id,
                'sectionName' => $v->section_name,
                'sectionDesc' => $v->section_desc,
                'teacher'     => !empty($v->teacher_real_name) ? $v->teacher_real_name : (!empty($v->teacher_name) ? $v->teacher_name : ''),
                'time'        => !empty($time[$v->plan_id]) ? $time[$v->plan_id] : '',
                'type'        => $v->course_type,
                'address'     => $address,
                'status'      => $planStatus[$v->plan_id],
                'hasVideo'    => !empty($v->video_id) ? 1 : 0,
                'common'      => ($v->status == 1) ? 2 : (!empty($isComment[$v->plan_id]) && $isComment[$v->plan_id] > 0 ? 1 : 0),
                'downUrl'     => interface_planApi::getPlanDownLoadUrl($v->plan_id),
                'duration'    => utility_tool::sec2time($v->totaltime),
                //'duration'    => !empty($uploadVideoInfo[$v->plan_id]['duration']) ? $uploadVideoInfo[$v->plan_id]['duration'] : 0,
                //'size'        => !empty($uploadVideoInfo[$v->plan_id]['size']) ? $uploadVideoInfo[$v->plan_id]['size'] : 0,
                'size'        => 0,
                'image'       => $image,
                'videoCount'  => count($videoIdArr)
            ];
        }
        return $this->setData($data);
	}
	
	//最近直播日历(小沃)
    public function pageLiveTable(){
        $orgId  = !empty($this->paramsInfo['oid']) ? $this->paramsInfo['oid'] : 0;
        $userId = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;

        $startTime = strtotime(date('Y-m-d 00:00:00',strtotime('-1 days')));
        $endTime   = strtotime(date('Y-m-d 23:59:59',strtotime('+5 days')));
        $days = ($endTime - $startTime) / 86400;
        $date = array();
        for($i=0; $i<$days; $i++){
            $date[] = date('Y-n-j', $startTime + (86400 * $i));
        }

        $data  = array();
        $sTime = date('Y-m-d 00:00:00',strtotime('-1 days')).','.date('Y-m-d 23:59:59',strtotime('+5 days'));

        //报名的课程
        $courseIdArr = array();
        $classIdArr  = array();
        if(!empty($userId)){
            //$orgInfo = user_api::getOrgByid($orgId);
            $ownerId = 0;//!empty($orgInfo->fk_user_owner) ? $orgInfo->fk_user_owner : 0;
            $myCourse = interface_user_api::getUserRegCourse($userId,array(),1,-1,$ownerId);
            if(!empty($myCourse['items'])){
                foreach($myCourse['items'] as $val){
                    $courseIdArr[] = $val['fk_course'];
                    $classIdArr[]  = $val['fk_class'];
                }
            }
        }

        $params = [
            'q' => ['start_time'=>$sTime,'status'=>'1,2,3','admin_status'=>1,'course_type'=>1,'expression' => "@resell_org_id =".$orgId." | @org_id=".$orgId],
            'f' => [
                'plan_id','course_id','start_time','course_name','class_name','section_name',
                'status','subdomain','course_thumb_big','course_thumb_med','course_thumb_small',
                'try','class_id'
            ],
            'p' => 1,
            'pl'=> 500,
            'ob'=> ['start_time'=>'asc']
        ];

        $planRes = seek_api::seekPlan($params);
        $plan = array();
        if(!empty($planRes->data)){
            foreach($planRes->data as $v){
                $d = date('Y-n-j', strtotime($v->start_time));
                $plan[$d]['time'] = $d;
                $plan[$d]['data'][] = [
                    'isSign'      => (in_array($v->course_id, $courseIdArr) && in_array($v->class_id, $classIdArr)) ? 1 : 0,
                    'courseId'    => $v->course_id,
                    'planId'      => $v->plan_id,
                    'className'   => $v->class_name,
                    'classId'     => $v->class_id,
                    'trys'        => $v->try,
                    'courseName'  => $v->course_name,
                    'courseImg'   => interface_func::imgUrl($v->course_thumb_med),
                    'sectionName' => $v->section_name,
                    'status'      => $v->status,
                    'stime'       => date('H:i', strtotime($v->start_time))
                ];
            }
        }
        foreach($date as $val){
            if(!empty($plan[$val])){
                $data[$val] = $plan[$val];
            }else{
                $data[$val]['time'] = $val;
                $data[$val]['data'] = array();
            }
        }

        $data = array_values($data);
        return $this->setData($data);
    }
	
	public function pagePlanCommentStatus(){
		$courseId = !empty($this->paramsInfo['params']['courseId']) ? (int)$this->paramsInfo['params']['courseId'] : 0;
		$classId  = !empty($this->paramsInfo['params']['classId']) ? (int)$this->paramsInfo['params']['classId'] : 0;
		$userId   = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;
		
		if(empty($courseId)|| empty($userId) || empty($classId)){
			return $this->setMsg(1000);
		}
		
		$res = comment_api::checkIsAddScoreAndCourseId($courseId, $userId, $classId);
		
		if(empty($res)) return $this->setMsg(3002);
		
		return $this->setData(array_values($res));
	}
	
	public function pageDownLoadUrl(){
		$planId = !empty($this->paramsInfo['params']['planId']) ? (int)$this->paramsInfo['params']['planId'] : 0;
		$userId = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;
		
		$planReg = comment_api::getplanidinfo($planId);
		$planReg = json_decode($planReg);
		if(empty($planReg)) return $this->setMsg(3002);
		
		$resUser = course_api::checkIsRegistration($userId, $planReg->fk_course);
		if(!$resUser || $planReg->video_public_type=='-2'){
			return $this->setMsg(3002);
		}
		
		$data = interface_planApi::getPlanDownLoadUrl($planId);
		return $this->setData($data);
	}

	//云课2.0最近直播头部
	public function  pageLatelyLiveTop(){

		//排课数
		$stTime = date('Y-m-d 00:00:00',strtotime('-1 days'));
		$enTime = date('Y-m-d 23:59:59',strtotime('+5 days'));
		$result = course_plan_api::getPlanNumByTime($stTime, $enTime);
		$planTimeArr = array();
		if(!empty($result)){
			foreach($result as $val) {
				$planTime = date('j', strtotime($val->start_time));
				$planTimeArr[$planTime] = $val->num;
			}
		}

		//日期信息
		$startTime  = strtotime(date('Y-m-d 00:00:00',strtotime('-1 days')));
		$endTime    = strtotime(date('Y-m-d 23:59:59',strtotime('+5 days')));
		$dayNameArr = array('周日','周一','周二','周三','周四','周五','周六');
		$nowTime    = date('w');
		$days = ($endTime - $startTime) / 86400;
		$data = array();
		for($i=0; $i<$days; $i++){
			$w = date('w', $startTime + (86400 * $i));
			$j = date('j', $startTime + (86400 * $i));
			$data['days'][$i] = [
				'dayName' => (!empty($dayNameArr[$w])) ? $dayNameArr[$w] : '',
				'day'     => $j,
				'dayTime' => date('Y-m-d', $startTime + (86400 * $i)),
				'num'     => !empty($planTimeArr[$j]) ? $planTimeArr[$j] : '0'
			];
		}

		//分类信息
		$data['cateList'] = array(
			array('cateId'=>0,'cateName'=>'全部'),
			array('cateId'=>7,'cateName'=>'小学'),
			array('cateId'=>8,'cateName'=>'初中'),
			array('cateId'=>9,'cateName'=>'高中')
		);

		return $this->setData($data);
	}

	//云课2.0最近直播列表
	public  function  pageLatelyLiveList(){
		$userId = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;
		$sTime  = !empty($this->paramsInfo['params']['sTime']) ? $this->paramsInfo['params']['sTime'] : date('Y-m-d');
		$cateId = !empty($this->paramsInfo['params']['cateId']) ? (int)$this->paramsInfo['params']['cateId'] : 0;
		$page   = !empty($this->paramsInfo['params']['page']) ? (int)$this->paramsInfo['params']['page'] : 1;
		$length = !empty($this->paramsInfo['params']['length']) ? (int)$this->paramsInfo['params']['length'] : 20;

		//报名的课程
		$courseIdArr = array();
		$classIdArr  = array();
		if(!empty($userId)){
			$myCourse = interface_user_api::getUserRegCourse($userId,array(),1,-1);
			if(!empty($myCourse['items'])){
				foreach($myCourse['items'] as $val){
					$courseIdArr[] = $val['fk_course'];
					$classIdArr[]  = $val['fk_class'];
				}
			}
		}

		//排课信息
		$startTime = $sTime.'00:00:00'.','.$sTime.'23:59:59';
		$params = [
			'q' => ['start_time'=>$startTime,'status'=>'1,2,3','admin_status'=>1,'course_type'=>1,'org_status'=>1],
			'f' => [
				'plan_id','course_id','start_time','course_name','class_name','section_name',
				'status','subdomain','course_thumb_big','course_thumb_med','course_thumb_small',
				'try','class_id','video_public_type','live_public_type'
			],
			'p' => $page,
			'pl'=> $length,
			'ob'=> ['start_time'=>'asc']
		];
		if(!empty($cateId)){
			$params['q']['second_cate'] = $cateId;
		}

		$planRes = seek_api::seekPlan($params);
		if(empty($planRes->data)) return $this->setMsg(3002);
		foreach($planRes->data as $v){
			$data['data'][] = [
				'isSign'      => (in_array($v->course_id, $courseIdArr) && in_array($v->class_id, $classIdArr)) ? 1 : 0,
				'courseId'    => $v->course_id,
				'planId'      => $v->plan_id,
				'className'   => $v->class_name,
				'classId'     => $v->class_id,
				'trys'        => (in_array($v->video_public_type,[1,2]) || $v->live_public_type == 1) ? 1 : 0,
				'courseName'  => $v->course_name,
				'courseImg'   => interface_func::imgUrl($v->course_thumb_med),
				'sectionName' => $v->section_name,
				'status'      => $v->status,
				'stime'       => date('H:i', strtotime($v->start_time))
			];
		}

		return $this->setData($data);
	}

    /**
     * @desc  检查排课是否已开课
     * @param int $planId
     * @param int $userId
     * @return 
     */
    public function pageCheckPublish(){
        $planId = !empty($this->paramsInfo['params']['planId']) ? (int)$this->paramsInfo['params']['planId'] : 0;
        $userId = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;

        $publish = live_publish::getByPlanId($planId);
        if(empty($publish)) return $this->setMsg(0);

        if($publish->uid == $userId){
            if($publish->live_call == 'publish'){
                return $this->setMsg(1);
            }elseif($publish->live_call == 'publish_done'){
                return $this->setMsg(0);
            }
        }

        return $this->setMsg(1);
    }

    public function pageDtouch(){
        $userId = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;
        if(empty($userId)) return $this->setMsg(1000);

        $willBenginPlan = course_plan_api::getUserWillBeginPlanClassIds();
        if(empty($willBenginPlan)) return $this->setMsg(3002);

        $userParams = array('condition'=>"fk_user={$userId} and fk_class in ({$willBenginPlan->classIds})");
        $userReg    = course_plan_api::getCourseUserList($userParams);
        if(empty($userReg->data)) return $this->setMsg(3002);

        $userWillPlanReg = course_plan_api::getUserWillBeginPlanListData($userReg, $willBenginPlan->willBeginClassPlan, 1);
        if(empty($userWillPlanReg)) return $this->setMsg(3002);
        
        $data = [
            'planId'     => $userWillPlanReg[0]->plan_id,
            'courseName' => $userWillPlanReg[0]->course_name,
            'courseImg'  => $this->imgUrl($userWillPlanReg[0]->course_thumb_med),
            'sectionName'=> $userWillPlanReg[0]->section_name,
            'sectionDesc'=> $userWillPlanReg[0]->section_desc,
            'startTime'  => $userWillPlanReg[0]->plan_start_time
        ];

        return $this->setData($data);
    }
}
