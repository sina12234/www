<?php

class interface_member extends interface_base{
	
	//会员中心
	public function pageEntry(){
		$this->v(['userId'=>1000]);
		$orgId  = !empty($this->paramsInfo['oid']) ? (int)$this->paramsInfo['oid'] : 0;
		$userId = (int)$this->paramsInfo['params']['userId'];
		
		$memberReg = user_organization::getMemberSetList($orgId, 1);
		if(empty($memberReg)) return $this->setMsg(3002);

        //用户购买的会员列表
        $userMemberReg = user_organization::getUserMember(array('userId'=>$userId,'orgId'=>$orgId,'memberStatus'=>1));
        $userMemberInfo= $userMemberIdArr = array();
        if(!empty($userMemberReg)){
            $time = strtotime("now");
            $date = date_create();
            foreach($userMemberReg as $val){
                $userMemberIdArr[$val->fk_member_set] = $val->fk_member_set;
                $userMemberInfo[] = [
                    "memberId"     => $val->fk_member_set,
                    "memberName"   => $val->title,
                    "memberStatus" => $val->member_status,
                    "endTime"      => date('Y-m-d', strtotime($val->end_time)),
                    "remainder"    => (strtotime($val->end_time) >= $time) ? (date_diff(date_create($val->end_time), $date)->format('%a') + 1) : 0
                ];
            }
        }

        //会员类型列表
		$memberInfo = array();
		foreach($memberReg as $val){
		    if(!in_array($val->pk_member_set, $userMemberIdArr)) {
                if (!empty($val->price_30)) {
                    $days[$val->pk_member_set][] = [
                        'day' => $val->price_30 / 100 . "元/30天",
                        'memberDay' => 30
                    ];
                }
                if (!empty($val->price_90)) {
                    $days[$val->pk_member_set][] = [
                        'day' => $val->price_90 / 100 . "元/90天",
                        'memberDay' => 90
                    ];
                }
                if (!empty($val->price_180)) {
                    $days[$val->pk_member_set][] = [
                        'day' => $val->price_180 / 100 . "元/180天",
                        'memberDay' => 180
                    ];
                }
                if (!empty($val->price_360)) {
                    $days[$val->pk_member_set][] = [
                        'day' => $val->price_360 / 100 . "元/360天",
                        'memberDay' => 360
                    ];
                }
                $memberInfo[] = [
                    "objectType" => 11,
                    "memberId" => $val->pk_member_set,
                    "memberName" => $val->title,
                    "descript" => $val->descript,
                    "days" => !empty($days[$val->pk_member_set]) ? $days[$val->pk_member_set] : array()
                ];
            }
		}

		return $this->setData([
			'userInfo'   => $userMemberInfo,
			'memberList' => $memberInfo
		]);
	}
	
	//会员课程列表
	public function pageCourseList(){
		$this->v(['memberId'=>1000,'userId'=>1000]);
		
		$orgId    = !empty($this->paramsInfo['oid']) ? (int)$this->paramsInfo['oid'] : 0;
		$page     = !empty($this->paramsInfo['params']['page']) ? (int)$this->paramsInfo['params']['page'] : 1;
		$length   = !empty($this->paramsInfo['params']['length']) ? (int)$this->paramsInfo['params']['length'] : 20;
		$memberId = (int)$this->paramsInfo['params']['memberId'];
		$userId   = (int)$this->paramsInfo['params']['userId'];
		
		//验证会员是否是该机构的
		$memberReg = org_member_api::getMemberSetInfo($memberId, $orgId);
		if(empty($memberReg)) return $this->setMsg(3002);

        $days = array();
        if(!empty($memberReg['price_30'])){
            $days[30] = [
                'day' => $memberReg['price_30'] / 100 . "元/30天",
                'memberDay' => 30
            ];
        }
        if(!empty($memberReg['price_90'])){
            $days[90] = [
                'day' => $memberReg['price_90'] / 100 . "元/90天",
                'memberDay' => 90
            ];
        }
        if(!empty($memberReg['price_180'])){
            $days[180] = [
                'day' => $memberReg['price_180'] / 100 . "元/180天",
                'memberDay' => 180
            ];
        }
        if(!empty($memberReg['price_360'])){
            $days[360] = [
                'day' => $memberReg['price_30'] / 100 . "元/360天",
                'memberDay' => 360
            ];
        }
        $days = array_values($days);
        $memberInfo = [
            "objectType" => 11,
            "memberId"   => $memberReg['setId'],
            "memberName" => $memberReg['title'],
            "descript"   => $memberReg['desc'],
            "endTime"    => '',
            "remainder"  => 0,
            "days"       => $days
        ];

		//用户购买本机构的会员
		$userMemberReg = user_organization::getUserMember(array('userId'=>$userId,'memberStatus'=>1,'orgId'=>$orgId));
		$userMemberSetId = array();
		if(!empty($userMemberReg)){
			foreach($userMemberReg as $val){
				if($val->status == 1){
					$userMemberSetId[] = $val->fk_member_set;
				}
				if($val->fk_member_set == $memberId){
                    $time = strtotime("now");
                    $date = date_create();
                    $memberInfo['endTime']   = date('Y-m-d', strtotime($val->end_time));
                    $memberInfo['remainder'] = (strtotime($val->end_time) >= $time) ? (date_diff(date_create($val->end_time), $date)->format('%a') + 1) : 0;
                }
			}
		}

		$memberPriorityReg = org_member_api::getMemberPriority($memberId,1,$page,$length);
		if(empty($memberPriorityReg)) return $this->setMsg(3002);

		foreach($memberPriorityReg->result as $val){
			if(!empty($val->object_id)){
				$courseIdArr[] = $val->object_id;
			}
		}
		
		$courseIds = implode(',', $courseIdArr);
		//查询中间层
		$params = [
			'q' => ['course_id'=>$courseIds],
			'f' => ['course_id','title','thumb_med','price','user_total'],
			'pl'=> $length
		];
		$seekCourse = seek_api::seekCourse($params);
		if(empty($seekCourse->data)) return $this->setMsg(3002);

		$data = [
			'page'      => $memberPriorityReg->result->page,
			'totalPage' => $memberPriorityReg->result->totalPage,
			'totalSize' => $memberPriorityReg->result->totalSize
		];
		foreach($seekCourse->data as $val){
			$data['data'][] = [
				'courseId'   => $val->course_id,
				'courseName' => $val->title,
				'thumbMed'   => $this->imgUrl($val->thumb_med),
				'price'      => $val->price / 100,
                'isMember'   => (!empty($userMemberSetId) && (in_array($memberId, $userMemberSetId))) ? 1 : 0,
				'userTotal'  => $val->user_total
			];
		}

		//会员详情
        $data['memberInfo'] = $memberInfo;
			
		return $this->setData($data);
	}
}
