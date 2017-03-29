<?php

class org_member_api
{
    const GET_ORG_MEMBER_SET_INFO = '/user/orgMemberSet/GetOrgMemberSetInfo/';

    /**
     * @desc get member set info
     *
     * @param $setId
     * @return array
     */
    public static function getMemberSetInfo($setId, $orgId='')
    {
		$params     = new stdclass;
		$params->orgId = !empty($orgId) ? $orgId : 0;
        $url = self::GET_ORG_MEMBER_SET_INFO.$setId;
        $res = interface_func::requestApi($url, $params);
        if (!empty($res['code']) || empty($res['result'])) return [];

        $data = [
            'setId'     => $res['result']['pk_member_set'],
            'orgId'     => $res['result']['fk_org'],
            'title'     => $res['result']['title'],
            'desc'      => $res['result']['descript'],
            'thumb'     => interface_func::imgUrl($res['result']['thumb']),
            'price_30'  => $res['result']['price_30'],
            'price_90'  => $res['result']['price_90'],
            'price_180' => $res['result']['price_180'],
            'price_360' => $res['result']['price_360'],
            'status'    => $res['result']['status'],
        ];

        return $data;
    }

    /**
     * @desc check member is reg or member or expire
     *
     * @param $userId
     * @param $setIdArr
     * @param $courseId
     * @return array
     */
    public static function checkIsMemberOrExpire($userId, $setIdArr, $courseId)
    {
        $regId = $isMember = $isExpire = $isOpen = $isMemberRegType = 0; //默认不是会员,未过期,未报名,未启用,普通身份报名
		$userMemberSet = array(); //用户购买的本课程有的会员id
	   // 获取报名信息
        $regInfo = course_api::checkIsRegistration($userId, $courseId);

        //if(empty($regInfo)) return array();
        !empty($regInfo['classId']) && $regId = $regInfo['classId'];
        ($regInfo['source'] == 2) && $isMemberRegType = 1; //会员身份报名

        // 检测是否为会员,是否过期
        $member = array();
        if(!empty($setIdArr[0])) {
            $member = user_organization::getMemberByUidAndSetIdArr($userId, $setIdArr);
        }
        //$member->member_status 暂不用
        $time = time();
        if (!empty($member->items)) {
            $expireStatus = [];
            foreach ($member->items as $k=>$v) {
                if($k == 0){
                    $endLongTime = $v->end_time;
                }
                $openStatus[] = $v->status;
                if ($time > strtotime($v->end_time)) {
                    $expireStatus[] = $v->pk_member;
                    $userMemberSet[$k]['is_expire'] = 1;
                }else{
                    $userMemberSet[$k]['is_expire'] = 0;
                }
                $userMemberSet[$k]['fk_member_set'] = $v->fk_member_set;
            }

            if (in_array(1, $openStatus)) {
                $isMember = $isOpen = 1; // 启用,正常状态下才是会员
            }

            if (count($member->items) == count($expireStatus)) {
                $isExpire = 1; //当前时间大于会员结束时间为过期
            } else { // 会员结束时间未过期
                // member end_time > t_course_user expire_time update
                $expireTime = strtotime($endLongTime);
                if ($regInfo['source'] == 2) {
                    interface_user_api::updateCourseUserExpireTime($regInfo['courseUserId'], $expireTime);
                }
            }
        }
        return array(
            'regId'           => $regId,
            'isMember'        => $isMember,
            'isMemberRegType' => $isMemberRegType,
            'isExpire'        => $isExpire,
            'isOpen'          => $isOpen,
            'userMemberSet'   => $userMemberSet,
        );
    }
    public static function checkIsMemberOrExpire1($userId, $setIdArr, $courseId)
    {
        $regId = $isMember = $isExpire = $isOpen = $isMemberRegType = 0; //默认不是会员,未过期,未报名,未启用,普通身份报名
        $userMemberSet = array(); //用户购买的本课程有的会员id
        // 获取报名信息
        $regInfo = course_api::checkIsRegistration($userId, $courseId);

        if(empty($regInfo)) return array();
        !empty($regInfo['classId']) && $regId = $regInfo['classId'];
        ($regInfo['source'] == 2) && $isMemberRegType = 1; //会员身份报名

        // 检测是否为会员,是否过期
        $member = user_organization::getMemberByUidAndSetIdArr($userId, $setIdArr);
        //$member->member_status 暂不用
        $time = time();
        if (!empty($member->items)) {
            $expireStatus = [];
            foreach ($member->items as $k=>$v) {
                if($k == 0){
                    $endLongTime = $v->end_time;
                }
                $openStatus[] = $v->status;
                if ($time > strtotime($v->end_time)) {
                    $expireStatus[] = $v->pk_member;
                    $userMemberSet[$k]['is_expire'] = 1;
                }else{
                    $userMemberSet[$k]['is_expire'] = 0;
                }
                $userMemberSet[$k]['fk_member_set'] = $v->fk_member_set;
            }

            if (in_array(1, $openStatus)) {
                $isMember = $isOpen = 1; // 启用,正常状态下才是会员
            }

            if (count($member->items) == count($expireStatus)) {
                $isExpire = 1; //当前时间大于会员结束时间为过期
            } else { // 会员结束时间未过期
                // member end_time > t_course_user expire_time update
                $expireTime = strtotime($endLongTime);
                if ($regInfo['source'] == 2) {
                    interface_user_api::updateCourseUserExpireTime($regInfo['courseUserId'], $expireTime);
                }
            }
        }
        return array(
            'regId'           => $regId,
            'isMember'        => $isMember,
            'isMemberRegType' => $isMemberRegType,
            'isExpire'        => $isExpire,
            'isOpen'          => $isOpen,
            'userMemberSet'   => $userMemberSet,
        );
    }
    public static function getbtnMemberInfo($userStatus ,$logined)
    {
        $result = [
            'showBtnEnroll'         => 1,  // 立即报名 : 0 不显示 ; 1 显示
            'showDivInvalidMember'  => 0,  // 会员失效DIV 0 不显示; 1 显示
        ];
        if ($userStatus['regId']) $result['showBtnEnroll'] = 0;
        if (($userStatus['isMemberRegType']==1) && ($userStatus['isOpen']==0)) $result['showBtnEnroll'] = 1;

       if($logined && $userStatus['regId'] && $userStatus['isMemberRegType'] && ($userStatus['isOpen'] == 0 || $userStatus['isExpire'])){
            $result['showDivInvalidMember'] = 1;
        }

        return $result;
    }

    /**
     * @desc get reg user status
     *
     * @param $userId
     * @param $courseId
     * @return array
     */
    public static function getUserStatus($userId, $plan_info)
    {
        $result = [
            'regId'             => 0,  // 未报名
            'className'         => 0,  // 已报名班级名
            'isMember'          => 0,  // 不是会员
            'isMemberRegType'   => 0,  // 不是会员报名身份
            'isExpire'          => 0,  // 未过期
            'isOpen'            => 0,  // 未启用
            'userMemberSet'     => [], //用户购买的本课程有的会员类型id
            'courseMemberSetId' => [], //会员课程的会员类型id
            'courseFeeType'     => 0,  // 0 免费课+过期会员 , 1付费课, 2 会员课
            'isValidMember'     => 0,  // 0 不是有效会员，1 有效会员（renewVip : isMember=1&isOpen=1&isExpire=0）
            'userMemberInfo'    => '', // openVip 马上开通 , reVip 过期会员 重新开通（isMember=1&isOpen=1&isExpire=1） , renewVip 有效会员 立即续费
        ];
        $courseId = $plan_info->course_id;
        $result['courseFeeType'] = $plan_info->fee_type;
        // 获取报名信息
        $regInfo = course_api::checkIsRegistration($userId, $courseId);
        !empty($regInfo['classId']) && $result['regId'] = $regInfo['classId'];
        !empty($regInfo['className']) && $result['className'] = $regInfo['className'];
        ($regInfo['source'] == 2) && $result['isMemberRegType'] = 1; //会员身份报名

        // 检测是否为会员,是否过期
        $setIdArr = course_api::getCourseOpenMemberSetIdArr($courseId);

        if (empty($setIdArr)) return $result;
        $result['courseMemberSetId'] = $setIdArr;

        $member   = user_organization::getMemberByUidAndSetIdArr($userId, $setIdArr);
        if (empty($member->items)) return $result;

        $time          = time();
        $expireStatus = [];
        foreach ($member->items as $k => $v) {
            if ($k == 0) $endLongTime = $v->end_time;
            $openStatus[] = $v->status;

            if ($time > strtotime($v->end_time)) {
                $expireStatus[]                 = $v->pk_member;
                $result['userMemberSet'][$k]['is_expire'] = 1;
            } else {
                $result['userMemberSet'][$k]['is_expire'] = 0;
            }
            $result['userMemberSet'][$k]['fk_member_set'] = $v->fk_member_set;
        }

        if (in_array(1, $openStatus)) {
            $result['isMember'] = $result['isOpen'] = 1;  // 启用,正常状态下才是会员
        }

        if (count($member->items) == count($expireStatus)) {
            $result['isExpire'] = 1; //当前时间大于会员结束时间为过期
        } else { // 会员结束时间未过期
            // member end_time > t_course_user expire_time update
            $expireTime = strtotime($endLongTime);
            if ($regInfo['source'] == 2) {
                interface_user_api::updateCourseUserExpireTime($regInfo['courseUserId'], $expireTime);
            }
        }
        if (($result['isMember']==1) && ($result['isOpen']==1) && ($result['isExpire'])==0){
            $result['isValidMember'] = 1;
            $result['userMemberInfo'] = 'renewVip'; // 未到期，立即续费
            $result['courseFeeType'] = 0;
        } elseif (($result['isMember']==1) && ($result['isOpen']==1) && ($result['isExpire'])==1){
            $result['isValidMember'] = 0;
            $result['userMemberInfo'] = 'reVip';   // 已到期，重新开通
        } else {
            $result['userMemberInfo'] = 'openVip';  // 马上开通
        }

        return $result;
    }

	public static function getMemberPriority($setId, $type, $page='', $length=''){
		$params = new stdClass;
		$params->setId = $setId;
		$params->type  = $type;
		$ret = utility_services::call("/user/orgMemberPriority/getMemberPriority/{$page}"."/".$length,$params);
		if(!empty($page) || !empty($length)){
            return $ret;
        }
		if(!empty($ret->result)){
            unset($ret->result->page);
            unset($ret->result->totalPage);
            unset($ret->result->totalSize);
			return $ret->result;
		}
		return false;
	}

    public static function addMember($params)
    {
        $loginUid = isset($params['fk_user']) && (int)$params['fk_user'] ? (int)$params['fk_user'] : 0;
        if (!$loginUid) return false;

        $data                    = $log = [];
        $setId                   = isset($params['setId']) && (int)$params['setId'] ? (int)$params['setId'] : 0;
        $priceType               = isset($params['price_type']) ? $params['price_type'] : 0;
        $data['status']          = isset($params['status']) ? $params['status'] : 1; // 默认会员状态启用
        $data['last_type']       = isset($params['last_type']) ? $params['last_type'] : 1; //默认付费购买
        $type                    = isset($params['type']) ? $params['type'] : 1; //默认付费购买
        $orderId                 = isset($params['orderId']) && (int)$params['orderId'] ? (int)$params['orderId'] : 0;
        $data['member_status']   = isset($params['member_status']) ? $params['member_status'] : 1;
        $data['fk_user']         = $loginUid;
        $data['fk_member_set']   = $setId;
        $log['fk_order_content'] = isset($params['fk_order_content']) && (int)$params['fk_order_content'] ? (int)$params['fk_order_content'] : 0;

        // check member is exist?
        $userMember = user_organization::checkMemberByUidAndSetId($loginUid, $setId);
        $currentTime = date('Y-m-d H:i:s', time());
        $endTime = date('Y-m-d H:i:s', time() + $priceType * 24 * 3600);
        if (!empty($userMember)) {
            $data['begin_time'] = $userMember->begin_time;
            $endTimeTemp = strtotime($userMember->end_time);
            if ($endTimeTemp > time()) {
                $data['end_time'] = date('Y-m-d H:i:s', strtotime($userMember->end_time) + $priceType * 24 * 3600);
            } else {
                $data['end_time'] = $endTime;
            }

            if ($type == 4) {
                $data['type'] = $userMember->type | 0x04;
            } else if ($type == 2) {
                $data['type'] = $userMember->type | 0x02;
            } else {
                $data['type'] = $userMember->type | 0x01;
            }
            $updateRet              = user_organization::updateMember($userMember->pk_member, $data);
            $log['fk_member']       = $userMember->pk_member;
            $log['end_time_before'] = $userMember->end_time;
            $log['end_time_after']  = $data['end_time'];
            $log['start_time_new']  = $currentTime;
        } else {
            $data['begin_time']     = $currentTime;
            $data['end_time']       = $endTime;
            $data['type']           = $type;
            $data['create_time']    = $currentTime;
            $log['end_time_before'] = $endTime;
            $log['end_time_after']  = $endTime;
            $log['start_time_new']  = $currentTime;
            $updateRet              = user_organization::addMember($data);
            if (!empty($updateRet)) {
                $log['fk_member'] = $updateRet;
            }
        }
        if ($updateRet !== false) {
            $log['type']        = $type;
            $log['create_time'] = $currentTime;
            $log['order_id'] = $orderId;
            user_organization::addMemberLog($log);
            self::sendOpenVipMsg($params);
            return true;
        } else {
            return false;
        }
    }

    private static function sendOpenVipMsg($params)
    {
        $loginUid  = isset($params['fk_user']) && (int)$params['fk_user'] ? (int)$params['fk_user'] : 0;
        $orgId     = isset($params['org_id']) && (int)$params['org_id'] ? (int)$params['org_id'] : 0;
        $setId     = isset($params['setId']) && (int)$params['setId'] ? (int)$params['setId'] : 0;
        $priceType = isset($params['price_type']) && (int)$params['price_type'] ? (int)$params['price_type'] : 0;
        if (!$loginUid || !$orgId || !$setId) return false;

        $orgSubName = $setName = '';
        $orgProfileInfo = user_organization::getOrgProfileInfo($params['org_id']);
        !empty($orgProfileInfo['subname']) && $orgSubName = $orgProfileInfo['subname'];

        $memberSetInfo = self::getMemberSetInfo($setId);
        !empty($memberSetInfo['title']) && $setName = $memberSetInfo['title'];
        $data = [
            'userFrom' => 0,
            'userTo'   => $loginUid,
            'content'  => "恭喜你已成功开通{$orgSubName}的{$setName}，有效期{$priceType}天",
            'title'    => '开通会员',
            'msgType'  => message_type::OPEN_VIP
        ];

        $ymData = [
            "title"  => "开通会员",
            "text"   => "恭喜你已成功开通{$orgSubName}的{$setName}，有效期{$priceType}天",
            "to_uid" => $loginUid,
            "organization" => $orgId,
            "message_type" => message_type::OPEN_VIP
        ];

		common_api::addYmMessage($ymData);
        return message_api::addDialog($data);
    }

	public static function getValidMemberListByUid($userId){
		$ret = utility_services::call("/user/orgMember/getValidMemberListByUid/".$userId);
		if(!empty($ret->result)){
			return $ret->result;
		}
		return false;
	}

    public static function getCourseMemberInfo($setId)
    {
        if (is_array($setId) && count($setId) > 0) {
            $setIdStr = implode(',', $setId);
        } else {
            $setIdStr = (int)$setId;
        }

        if (!$setIdStr) return [];
        $params = ['setId' => $setIdStr,'status'=>1];
        $courseMember     = org_api::getorgmembersets($params);
        $courseMemberInfo = [];
        if (!empty($courseMember->result->items)) {
            foreach ($courseMember->result->items as $v) {
                $courseMemberInfo[] = [
                    'title' => $v->title,
                    'setId' => $v->pk_member_set
                ];
            }
        }

        return $courseMemberInfo;
    }

	/**
     * @desc 判断用户以会员身份报名的课程是否是会员课程
     *
     * @param $userId
     * @param $courseIdArr 用户以会员身份报名的课程id数组
     * @return array
     */
	public static function checkUserCourseIsMember($userId,$courseIdArr){
		$courseMemberRet = user_organization::getMemberPriorityByObjectId($courseIdArr,1);
		$courseSetIdArr = array();
		$courseTemp = array();
		$courseData = array();
		foreach($courseIdArr as $cid){
			$courseData[$cid]['is_member'] = 0;
		}
		if(!empty($courseMemberRet)){
			//课程对应的会员
			foreach($courseMemberRet as $mo){
				if($mo->status == 1){
					$courseSetIdArr[] = $mo->fk_member_set;
					$courseTemp[$mo->object_id][] = $mo->fk_member_set;
				}
			}
			//用户报名课程中购买课程对应的会员
			$memberRet = user_organization::getMemberByUidAndSetIdArr($userId, $courseSetIdArr);
			$time = time();
			if (!empty($memberRet->items)) {
				$userMember   = array();
				foreach($memberRet->items as $mk=>$mv){
					$userMember[$mv->fk_member_set] = $mv;
				}
				foreach($courseTemp as $courseId=>$setArr){
					$memberCount = $isExpire = $isMember = 0;
					$openStatus = $expireMember = $endTimeArr = array();
					foreach($setArr as $setId){
						if(!empty($userMember[$setId])){
							$openStatus[] = $userMember[$setId]->status;
							if ($time > strtotime($userMember[$setId]->end_time)) {
								$expireMember[] = $userMember[$setId]->pk_member;
							}
							$endTimeArr[] = strtotime($userMember[$setId]->end_time);
							$memberCount +=1;
						}
					}
					if (in_array(1, $openStatus)) {
						$isMember = 1;
					}
					if ($memberCount == count($expireMember)) {
						$isExpire = 1;
					}elseif(!empty($endTimeArr)){
						rsort($endTimeArr);
						$expireTime = $endTimeArr[0];
						course_api::updateCourseUserExpireTime($userId,$courseId ,$expireTime);
					}
					if($isMember == 1 && $isExpire == 0){
						$courseData[$courseId]['is_member'] = 1;
					}
				}
			}
		}
		return $courseData;
	}

}
