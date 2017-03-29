<?php
class message_api{
	public static function getUnreadInstationNum($uid,$token,$messageType=array()){
		$params = new stdclass;
		$params->user_id = $uid;
		$params->token = $token;
		$params->messageType = $messageType;
		$ret = utility_services::call("/message/instation/getunreadinstationnum", $params);
		return $ret->total;
	}
	/**
	 * @deprecated
	 * 发送消息，信号，点赞
	 * 不推荐使用，请直接调用golang agent
	 * @docs https://wiki.gn100.com/doku.php?id=docs:message:plan
	 **/
	public static function addMsgV2($data, $user_id, $user_token){
		$params = new stdclass;
		$params->user_from_id = $user_id;
		$params->user_from_token = $user_token;
		if(!empty($data["user_to_id"])){
			$params->user_to_id = $data["user_to_id"];
		}
		if(!empty($data["user_to_token"])){
			$params->user_to_token = $data["user_to_token"];
		}
		$params->plan_id = $data["plan_id"];
		$params->type=(int)( $data["type"]);
		switch((int)($data["type"])){
			case 1://text
			case 500://reply_text:
				$params->message_type = "text"; break;
			case 100://good:
				$params->message_type = "good"; break;
			default:
				$params->message_type = "signal";
		}
		if(isset($data["content"])){
			$params->content = $data["content"];
		}
		if(!empty($data["live_second"])){
			$params->live_second = $data["live_second"];
		}
		$ret = message_http::addPlanMsg($params);
		if(isset($ret->key) && isset($ret->value)){
			return $ret;
		}
		return false;
	}
/**
	@deprecated by hetao  2016/12/8
 */
/*
	public static function getGood($plan_id){
		$params = new stdclass;
		$params->plan_id = $plan_id;
		//return array
		$ret = utility_services::call("/message/chat/getGood", $params);
		if(!empty($ret)){
			return $ret;
		}else{
			return false;
		}
	}
*/
	/**
	 * 上下课信号
	 */
	public static function startCloseClass($plan_id, $user_id, $user_token, $isStart){
		$params = new stdclass;
		$params->plan_id = $plan_id;
		if($isStart){
			$params->class = "start";
		}else{
			$params->class = "close";
		}
		$params->user_id = $user_id;
		$params->user_token = $user_token;
		$ret = utility_services::call("/message/chat/class", $params);
		if(!empty($ret->data)){
			return $ret->data;
		}else{
			return false;
		}
	}

    public static function requestEval($planId, $uid, $token){
		$params = new stdclass;
        $params->planId = (int)$planId;
        $params->teacherId = (int)$uid;
        $params->token  = $token;

		$ret = utility_services::call("/message/chat/requesteval", $params);
        if(empty($ret->code)) return true;

        return false;
    }
/*
	public static function getSingleForbid($data, $user_id){
		if(empty($data["plan_id"])){
			return false;
		}
		$params = new stdclass;
		$params->user_id = $user_id;
		$params->plan_id = $data["plan_id"];
		$ret = utility_services::call("/message/chat/getsingleforbidbyplan", $params);
		if(!empty($ret->data)){
			return $ret->data;
		}else{
			if(0 == $ret->result->code){
				return true;
			}else{
				return false;
			}
		}
	}
*/
/*
	public static function checkForbid($plan_id, $user_id){
		if(empty($plan_id)){
			return false;
		}
		if(empty($user_id)){
			return false;
		}
		$params = new stdclass;
		$params->user_id = $user_id;
		$params->plan_id = $plan_id;
		$ret = utility_services::call("/message/chat/checkforbid", $params);
		if(0 == $ret->result->code){
			return true;
		}else{
			return false;
		}
	}
*/

    public static function addDialog($data)
    {
        $res = utility_services::call('/message/dialog/add', $data);
        if ($res->code) return false;

        return $res->result;
    }

    public static function listDialog($data)
    {
        return utility_services::call('/message/dialog/lists', $data);
    }

    public static function updateDialog($data)
    {
        return utility_services::call('/message/dialog/update', $data);
    }
	public static function updateAllDialog($data)
    {
        return utility_services::call('/message/dialog/UpdateAllMessage', $data);
    }

    public static function getDialogLastTotalList($data)
    {
        return utility_services::call('/message/dialog/GetDialogLastTotalList', $data);
    }

	public static function getPlanGoodByPidArr($pid_arr){

        return utility_services::call('/message/good/getPlanGoodByPidArr', $pid_arr);
	}

	public static function getPlanScoreByPidArr($pid_arr){

        return utility_services::call('/message/score/getPlanScoreByPidArr', $pid_arr);
	}

    /**
     * @desc get group list by uid
     *
     * @param $uid
     * @param array $except
     * @return array
     *
     * @author wen
     */
    public static function getGroupListByUid($uid, $except=[])
    {
        $data = [];
        $res  = utility_services::call("/user/followGroup/List/{$uid}");

        if ($res->code == 0) {
            $groupNum = self::getEachGroupNum($uid);
            foreach ($res->result as $v) {
                if (!in_array($v->pk_user_follow_group, $except, true)) {
                    $data[] = [
                        'groupId' => $v->pk_user_follow_group,
                        'name'    => SLanguage::tr($v->group_name, 'message'),
                        'num'     => isset($groupNum[$v->pk_user_follow_group]['num'])
                                        ? $groupNum[$v->pk_user_follow_group]['num']
                                        : $v->group_user_num
                    ];
                }
            }
        }

        return $data;
    }

    /**
     * @desc add group
     *
     * @param $uid
     * @param $groupName
     * @return bool
     *
     * @author wen
     */
    public static function addGroup($uid, $groupName)
    {
        $data = [
            'userId'    => $uid,
            'groupName' => $groupName
        ];

        $res = utility_services::call("/user/followGroup/add", $data);

        return !$res->code;
    }

    /**
     * @desc change group name
     *
     * @param $uid
     * @param $groupId
     * @param $groupName
     * @return bool
     *
     * @author wen
     */
    public static function changeGroupName($uid, $groupId, $groupName)
    {
        $data = [
            'userId'    => $uid, // 限制只有自己可修改
            'groupId'   => $groupId,
            'groupName' => $groupName
        ];

        $res = utility_services::call("/user/followGroup/ChangeName", $data);

        return !$res->code;
    }

    /**
     * @desc delete group by group id
     *
     * @param $uid
     * @param $groupId
     * @return bool
     *
     * @author wen
     */
    public static function delGroup($uid, $groupId)
    {
        $data     = [
            'userId'  => $uid, // 限制只有自己可删除
            'groupId' => $groupId,
        ];
        $userList = utility_services::call("/user/followGroup/ListGroupContactsByGroupId/{$groupId}");

        if (!empty($userList->result)) {
            $userIdArr = [];
            foreach ($userList->result as $user) {
                $userIdArr[] = $user->fk_user;
            }

            $moveData = [
                'userIdArr' => $userIdArr,
                'userId'    => $uid,
                'groupId'   => -4 // move default group
            ];

            $moveRes = utility_services::call("/user/followGroup/UserMove", $moveData);

            if ($moveRes->code) {
                SLog::fatal('move groupUser into default failed,params[%s]', var_export($moveData, 1));
                return false;
            }
        }

        // move group user contacts into default group (-4)
        $res = utility_services::call("/user/followGroup/Del", $data);

        return !$res->code;
    }

    /**
     * @desc get contacts list by group id
     *
     * @param $groupId
     * @return array
     *
     * @author wen
     */
    public static function getContactsListByGroupId($groupId, $userId)
    {
        $data = [];
        $res  = utility_services::call("/user/followGroup/ListGroupContactsByGroupId", ['groupId' => $groupId, 'userId' => $userId]);

        if ($res->code) return $data;

        $userIdArr = [];
        foreach ($res->result as $v) {
            $userIdArr[$v->fk_user] = $v->fk_user;
        }

        $userInfo = user_api::listUserBasicInfo(array_values($userIdArr));

        foreach ($res->result as $v) {
            $data[$v->fk_user] = [
                'userId'    => $v->fk_user,
                'groupId'   => $v->fk_user_follow_group,
                'userName'  => !empty($userInfo[$v->fk_user]['name']) ? $userInfo[$v->fk_user]['name'] : SLanguage::tr('未设置', 'message'),
                'userThumb' => !empty($userInfo[$v->fk_user]['thumb']) ? $userInfo[$v->fk_user]['thumb'] : '/assets_v2/img/photo-1.jpg',
            ];
        }

        return array_values($data);
    }

    /**
     * @desc get default and black group list
     *
     * @param $userId
     * @return array
     */
    public static function getDefaultAndBlackGroupList($userId)
    {
        $data = $result = [];
        $res  = utility_services::call("/user/followGroup/GetDefaultAndBlackGroupList", ['userId' => $userId]);

        if ($res->code) return $data;

        $userIdArr = [];
        foreach ($res->result as $v) {
            $userIdArr[$v->fk_user] = $v->fk_user;
        }

        $userInfo = user_api::listUserBasicInfo(array_values($userIdArr));

        foreach ($res->result as $v) {
            $data[$v->fk_user_follow_group][$v->fk_user] = [
                'userId'    => $v->fk_user,
                'userName'  => !empty($userInfo[$v->fk_user]['name']) ? $userInfo[$v->fk_user]['name'] : SLanguage::tr('未设置', 'message'),
                'userThumb' => !empty($userInfo[$v->fk_user]['thumb']) ? $userInfo[$v->fk_user]['thumb'] : '/assets_v2/img/photo-1.jpg',
            ];
        }
        $result['normalList'] = !empty($data[-4]) ? array_values($data[-4]) : [];
        $result['blackList'] = !empty($data[-5]) ? array_values($data[-5]) : [];

        return $result;
    }

    /**
     * @desc check user is black
     *
     * @param $from
     * @param $loginUser
     * @return bool
     */
    public static function checkUserIsBlack($from, $loginUser)
    {
        $res = utility_services::call("/user/followGroup/ListGroupContactsByGroupId", ['groupId' => -5, 'userId' => $loginUser]);
        if ($res->code) return false;

        foreach ($res->result as $v) {
            if ($v->fk_user == $from) {
                return true;
            }
        }

        return false;
    }

    /**
     * @desc get latest user info
     *
     * @param $to
     * @param int $page
     * @param int $length
     * @return array
     *
     * @author wen
     */
    public static function getLatestUser($to, $page=1, $length=20)
    {
        $data = [];
        $res  = utility_services::call("/message/dialog/GetLatestUser", [
            'userToId' => $to,
            'page'     => $page,
            'length'   => $length
        ]);

        if ($res->code) return $data;

        $userIdArr = [];
        foreach ($res->result as $v) {
            $userIdArr[$v->fk_user_from] = $v->fk_user_from;
        }

        $userInfo = user_api::listUserBasicInfo(array_values($userIdArr));

        foreach ($res->result as $v) {
            $data[$v->fk_user_from] = [
                'userId'    => $v->fk_user_from,
                'groupId'   => -1,
                'display'   => 1,
                'userName'  => !empty($userInfo[$v->fk_user_from]['name']) ? $userInfo[$v->fk_user_from]['name'] : SLanguage::tr('未设置', 'message'),
                'userThumb' => !empty($userInfo[$v->fk_user_from]['thumb']) ? $userInfo[$v->fk_user_from]['thumb'] : '/assets_v2/img/photo-1.jpg',
            ];
        }

        return array_values($data);
    }

    /**
     * @desc group user contacts move
     *
     * @param array $userId
     * @param $groupId
     * @return bool
     *
     * @author wen
     */
    public static function contactsMove(array $userId, $groupId, $loginUser)
    {
        $param = [
            'userIdArr' => $userId,
            'groupId'   => $groupId,
            'userId'    => $loginUser,
        ];

        $res = utility_services::call("/user/followGroup/UserMove", $param);

        return !$res->code;
    }

    /**
     * @desc get my messages
     *
     * @param $userToId
     * @param int $page
     * @param int $length
     * @return array
     *
     * @author wen
     */
    /*public static function getMyMessages($userToId, $page=1, $length=50)
    {
        $param = [
            'userToId' => $userToId,
            'page'     => $page,
            'length'   => $length
        ];

        $res  = utility_services::call("/user/followGroup/GetMessages", $param);
        if ($res->code) return [];

        $data = $usersIdArr = [];
        foreach ($res->result->data as $v) {
            if ($v->fk_user_from || $v->fk_user_to) {
                $usersIdArr[$v->fk_user_from] = $v->fk_user_from;
                $usersIdArr[$v->fk_user_to] = $v->fk_user_to;
            }
        }

        $userInfo = self::getMsgUserInfo($usersIdArr);

        foreach ($res->result->data as $v) {
            if ($v->fk_user_from && message_type::SYSTEM_CONTACT_INFORMATION == $v->message_type) {
                $msgTypeTitle  = !empty($userInfo[$v->fk_user_from]['userName'])
                                    ? $userInfo[$v->fk_user_from]['userName']
                                    : SLanguage::tr('未设置', 'message');
                $msgTitleThumb = !empty($userInfo[$v->fk_user_from]['userThumb'])
                                    ? $userInfo[$v->fk_user_from]['userThumb']
                                    : '/assets_v2/img/photo-1.jpg';
            } else {
                $msgTypeTitle  = !empty(message_type::$msgTypeMap[$v->message_type]['title'])
                                    ? message_type::$msgTypeMap[$v->message_type]['title']
                                    : SLanguage::tr('系统消息', 'message');
                $msgTitleThumb = !empty(message_type::$msgTypeMap[$v->message_type]['thumb'])
                                    ? message_type::$msgTypeMap[$v->message_type]['thumb']
                                    : '';
            }

            $data[] = [
                'redPoint'     => ($v->status == 'unread' && $v->is_remind) ? 0 : 1,
                'userFrom'     => $v->fk_user_from,
                'userTo'       => $v->fk_user_to,
                'time'         => $v->create_time,
                'message'      => html_entity_decode($v->last_message),
                'num'          => $v->message_num,
                'msgTypeTitle' => $msgTypeTitle,
                'msgTitleThumb' => $msgTitleThumb,
                'msgType'      => $v->message_type,
                'status'       => $v->status,
                'topFlag'      => SLanguage::tr('置顶', 'message'),
                'isTop'        => $v->is_top ? 0 : 1,
                'isTopStr'     => $v->is_top ? SLanguage::tr('取消置顶', 'message') : SLanguage::tr('置顶消息', 'message'),
                'isRemind'     => $v->is_remind,
            ];
        }

        return [
            'totalPage' => $res->result->totalPage,
            'totalSize' => $res->result->totalSize,
            'data'      => $data
        ];
    }*/
	public static function getMyMessages($userToId, $page=1, $length=50)
    {
        $param = [
            'userToId' => $userToId,
            'page'     => $page,
            'length'   => $length
        ];

        $res  = utility_services::call("/user/followGroup/GetMessagesV2", $param);
		
        if ($res->code) return [];
        $data = $usersIdArr = [];
		if(!empty($res->result->chatMsg->data)){
			foreach ($res->result->chatMsg->data as $v) {
				if ($v->fk_user_from || $v->fk_user_to) {
					$usersIdArr[$v->fk_user_from] = $v->fk_user_from;
					$usersIdArr[$v->fk_user_to] = $v->fk_user_to;
				}
			}
		}
		if(!empty($res->result->sysMsg)){
			$usersIdArr[] = $res->result->sysMsg->fk_user_to;
		}
		$userInfo 		  = self::getMsgUserInfo($usersIdArr);
		if(!empty($res->result->chatMsg->data)){
			foreach ($res->result->chatMsg->data as $v) {
					$msgTypeTitle  = !empty($userInfo[$v->fk_user_from]['userName'])
										? $userInfo[$v->fk_user_from]['userName']
										: SLanguage::tr('未设置', 'message');
					$msgTitleThumb = !empty($userInfo[$v->fk_user_from]['userThumb'])
										? $userInfo[$v->fk_user_from]['userThumb']
										: '/assets_v2/img/photo-1.jpg';
					$data['chatMsg'][] = [
											'redPoint'     => ($v->status == 'unread' && $v->is_remind) ? 0 : 1,
											'userFrom'     => $v->fk_user_from,
											'userTo'       => $v->fk_user_to,
											'time'         => $v->create_time,
											'message'      => html_entity_decode($v->last_message),
											'num'          => $v->message_num,
											'msgTypeTitle' => $msgTypeTitle,
											'msgTitleThumb'=> $msgTitleThumb,
											'msgType'      => $v->message_type,
											'status'       => $v->status,
											'topFlag'      => SLanguage::tr('置顶', 'message'),
											'isTop'        => $v->is_top ? 0 : 1,
											'isTopStr'     => $v->is_top ? SLanguage::tr('取消置顶', 'message') : SLanguage::tr('置顶消息', 'message'),
											'isRemind'     => $v->is_remind,
											];
			}
		}
		$data['totalPage'] = !empty($res->result->chatMsg->totalPage) ? $res->result->chatMsg->totalPage : 0;
		$data['totalSize'] = !empty($res->result->chatMsg->totalSize) ? $res->result->chatMsg->totalSize : 0;
		if(!empty($res->result->sysMsg)){
			$sysInfo = $res->result->sysMsg;
			$data['systemMsg'][] = [
											'redPoint'     => ($sysInfo->status == 'unread' && $sysInfo->is_remind) ? 0 : 1,
											'userFrom'     => $sysInfo->fk_user_from,
											'userTo'       => $sysInfo->fk_user_to,
											'time'         => $sysInfo->create_time,
											'message'      => html_entity_decode($sysInfo->last_message),
											'num'          => $sysInfo->message_num,
											'msgTypeTitle' => "系统消息",
											'msgTitleThumb'=> '/assets_v2/img/msg-icon1.png',
											'msgType'      => $sysInfo->message_type,
											'status'       => $sysInfo->status,
											'topFlag'      => SLanguage::tr('置顶', 'message'),
											'isTop'        => $sysInfo->is_top ? 0 : 1,
											'isTopStr'     => $sysInfo->is_top ? SLanguage::tr('取消置顶', 'message') : SLanguage::tr('置顶消息', 'message'),
											'isRemind'     => $sysInfo->is_remind,
											];
		}
        return [
            'data'      => $data
        ];
    }
    /**
     * @desc get message user info
     *
     * @param $usersIdArr
     * @return array
     */
    public static function getMsgUserInfo($usersIdArr)
    {
        $data = [];
        $userInfo = user_api::listUsersByUserIds($usersIdArr);

        if (!empty($userInfo->result)) {
            foreach ($userInfo->result as $user) {
                if ($user->pk_user) {
                    $data[$user->pk_user] = [
                        'userName'  => $user->real_name,
                        'userThumb' => interface_func::imgUrl($user->thumb_big)
                    ];
                }
            }
        }

        return $data;
    }

    /**
     * @desc get message detail list
     *
     * @param $from
     * @param $to
     * @param $type
     * @param int $page
     * @param int $length
     * @return array
     *
     * @author wen
     */
    public static function getMsgDetail($from, $to, $type, $page=1, $length=50)
    {
        $param = [
            'userFrom' => $from,
            'userTo'   => $to,
            'msgType'  => $type,
            'page'     => $page,
            'length'   => $length
        ];

        $res  = utility_services::call("/user/followGroup/MsgDetail", $param);
        if ($res->code) return [];
        $data = $users = $usersIdArr = [];
        foreach ($res->result->data as $v) {
            $usersIdArr[$v->fk_user_from] = $v->fk_user_from;
            $usersIdArr[$v->fk_user_to] = $v->fk_user_to;
        }

        if ($from && $type == message_type::SYSTEM_CONTACT_INFORMATION) {
            $userInfo = self::getMsgUserInfo($usersIdArr);
        } else {
            $msgTypeTitle  = !empty(message_type::$msgTypeMap[$v->message_type]['title'])
                ? message_type::$msgTypeMap[$v->message_type]['title']
                : SLanguage::tr('系统消息', 'message');
            $msgTitleThumb = !empty(message_type::$msgTypeMap[$v->message_type]['thumb'])
                ? message_type::$msgTypeMap[$v->message_type]['thumb']
                : '';
        }

        foreach ($res->result->data as $v) {
            if ($v->fk_user_from && $v->message_type == message_type::SYSTEM_CONTACT_INFORMATION) {
                $msgTypeTitle  = !empty($userInfo[$v->fk_user_from]['userName'])
                    ? $userInfo[$v->fk_user_from]['userName']
                    : SLanguage::tr('未设置', 'message');
                $msgTitleThumb = !empty($userInfo[$v->fk_user_from]['userThumb'])
                    ? $userInfo[$v->fk_user_from]['userThumb']
                    : '/assets_v2/img/photo-1.jpg';
            }

            $data[] = [
                'userFrom'     => $v->fk_user_from,
                'userTo'       => $v->fk_user_to,
                'time'         => $v->insert_time,
                'message'      => html_entity_decode($v->content),
                'msgTypeTitle' => $v->title,
                'msgTitleThumb'=> $msgTitleThumb,
                'msgType'      => $v->message_type,
                'status'       => $v->status,
            ];
        }


        return [
            'totalPage' => $res->result->totalPage,
            'totalSize' => $res->result->totalSize,
            'data'      => $data
        ];
    }

    /**
     * @desc delete message by message type
     *
     * @param $from
     * @param $to
     * @param $msgType
     * @return bool
     *
     * @author wen
     */
    public static function msgDel($from, $to, $msgType)
    {
        $param = [
            'userFrom' => $from,
            'userTo'   => $to,
            'msgType'  => $msgType,
        ];

        $res = utility_services::call("/user/followGroup/MsgDel", $param);

        return !$res->code;
    }

    /**
     * @desc get chat detail list
     *
     * @param $from
     * @param $to
     * @param int $page
     * @param int $length
     * @return array
     *
     * @author wen
     */
    public static function getChatDetail($from, $to, $maxId=0, $page=1, $length=50)
    {
        $data = [
            'page' => $page,
            'length' => $length,
            'userToId' => $to,
            'userFrom' => $from
        ];
        $maxId && $data['maxId'] = $maxId;

        $res = utility_services::call('/message/dialog/ChatSingle', $data);

        if (empty($res->result)) return [];

        $result = $users = $usersIdArr = [];
        foreach ($res->result as $v) {
            $usersIdArr[$v->fk_user_from] = $v->fk_user_from;
            $usersIdArr[$v->fk_user_to] = $v->fk_user_to;
        }

        $userInfo = user_api::listUsersByUserIds(array_values($usersIdArr));

        if (!empty($userInfo->result)) {
            foreach ($userInfo->result as $user) {
                $users[$user->pk_user] = [
                    'userName'  => $user->name,
                    'userThumb' => interface_func::imgUrl($user->thumb_big)
                ];
            }
        }

        foreach ($res->result as $v) {
				$result[] = [
								'maxId'        => $v->pk_msg_id,
								'userFrom'     => $v->fk_user_from,
								'userTo'       => $v->fk_user_to,
								'msgId'        => $v->pk_msg_id,
								'status'       => $v->status,
								'content'      => html_entity_decode($v->content),
								'isLeft'       => ($from == $v->fk_user_from) ? 0 : 1,
								'userFromName' => !empty($users[$v->fk_user_from]['userName'])
									? $users[$v->fk_user_from]['userName']
									: SLanguage::tr('未设置', 'message'),
								'userFromImg'  => !empty($users[$v->fk_user_from]['userThumb'])
									? $users[$v->fk_user_from]['userThumb']
									: '/assets_v2/img/photo-1.jpg',
								'userToName'   => !empty($users[$v->fk_user_to]['userName'])
									? $users[$v->fk_user_to]['userName']
									: SLanguage::tr('未设置', 'message'),
								'userToImg'    => !empty($users[$v->fk_user_to]['userThumb'])
									? $users[$v->fk_user_to]['userThumb']
									: '/assets_v2/img/photo-1.jpg',
								'insertTime'   => $v->insert_time
							];
        }
        return $result;
    }

    /**
     * @desc search contacts
     *
     * @param $keyword
     * @return array
     */
    public static function searchContacts($keyword)
    {
        $res = utility_services::call('/user/info/SearchUserByKeyword', ['keyword' => $keyword]);
        $data = [];
        if (!empty($res->result)) {
            foreach ($res->result as $user) {
                $userIdArr[$user->pk_user] = $user;
            }
            $contacts = utility_services::call('/user/followGroup/GetUserByUserIds', ['userIdArr' => array_keys($userIdArr)]);

            if (!empty($contacts->result)) {
                foreach ($contacts->result as $v) {
                    if (!empty($userIdArr[$v->fk_user])) {
                        $data[$v->fk_user] = $userIdArr[$v->fk_user];
                    }
                }
            }
        }

        return $data;
    }

    /**
     * @desc delete contact
     *
     * @param $userId
     * @param $linkMan
     * @return bool
     */
    public static function delContact($userId, $linkMan)
    {
        $params = [
            'userId'  => $userId,
            'linkMan' => $linkMan
        ];

        $res = utility_services::call('/user/followGroup/DelContact', $params);

        return !$res->code;
    }

    /**
     * @desc get user contact
     *
     * @param $userId
     * @param $linkMan
     * @return array
     */
    public static function getUserContact($userId, $linkMan)
    {
        $params = [
            'userId'  => $userId,
            'linkMan' => $linkMan
        ];

        $res = utility_services::call('/user/followGroup/GetUserRelation', $params);
        $contain = 0;
        if (!empty($res->result)) {
            $contain = $res->result->fk_user_follow_group == -5 ? 2 : 1;
        }

        $linkManInfo = user_api::getBasicUser($linkMan);
        if(empty($linkMan)) return [];

        $identity = 0;
        if (!empty($linkManInfo->type) && $linkManInfo->type & 0x01) {
            $identity = 0; // student
        }
        if (!empty($linkManInfo->type) && $linkManInfo->type & 0x02) {
            $identity = 1; // teacher
        }
        if (!empty($linkManInfo->type) && $linkManInfo->type & 0x04) {
            $identity = 1; // organization
        }

        return [
            'linkMan'  => $linkMan,
            'contain'  => $contain,
            'top'      => 0, // to do,
            'identity' => $identity
        ];


    }

    /**
     * @desc get each group num by login user id
     *
     * @param $userId
     * @return array|bool
     *
     * @author wen
     */
    public static function getEachGroupNum($userId)
    {
        $res = utility_services::call("/user/followGroup/GetEachGroupNum/{$userId}");
        if ($res->code) return false;

        $data = [];
        foreach ($res->result as $item) {
            $data[$item->fk_user_follow_group] = [
                'groupId' => $item->fk_user_follow_group,
                'num'     => $item->num
            ];
        }

        return $data;
    }

    /**
     * @desc get remind option
     *
     * @return array
     */
    public static function getRemindOption()
    {
        $msgType = [
            message_type::SYSTEM_CLASS_REMIND, //10001
            message_type::SYSTEM_CLASS_END_STATISTICS,//10004
            message_type::SYSTEM_PREFERENTIAL_VOLUME_REMIND,//10005
            message_type::SYSTEM_CLASS_CHANGE_FEEDBACK,//10006
            message_type::SYSTEM_COURSE_CHANGE,//10007
            message_type::SYSTEM_COURSE_ARRANGEMENT,//10008
            message_type::ORG_DATA_INFO_VERIFY,//10010
            message_type::CLICK_PRAISE,//10013
            message_type::INTERACTIVE_COMMENT,//10014
        ];

        foreach ($msgType as $v) {
            if (!isset(utility_session::get()['msgRemind'][$v])) {
                utility_session::get()['msgRemind'][$v] = 1;
            }
        }

        $remindOption = [
            message_type::SYSTEM             => [ // 系统消息
                                                  [
                                                      'title'     => SLanguage::tr('课程变动', 'message'),
                                                      'msgType'   => 10007,
                                                      'remindStr' => SLanguage::tr('提醒', 'message'),
                                                      'ignoreStr' => SLanguage::tr('不提醒', 'message'),
                                                      'remind'    => (isset(utility_session::get()['msgRemind'][10007]) && utility_session::get()['msgRemind'][10007] == 1) ? 0 : 1,
                                                      'noRemind'  => (isset(utility_session::get()['msgRemind'][10007]) && utility_session::get()['msgRemind'][10007] == 0) ? 0 : 1
                                                  ],
                                                  [
                                                      'title'     => SLanguage::tr('调课反馈', 'message'),
                                                      'msgType'   => 10006,
                                                      'remindStr' => SLanguage::tr('提醒', 'message'),
                                                      'ignoreStr' => SLanguage::tr('不提醒', 'message'),
                                                      'remind'    => (isset(utility_session::get()['msgRemind'][10006]) && utility_session::get()['msgRemind'][10006] == 1) ? 0 : 1,
                                                      'noRemind'  => (isset(utility_session::get()['msgRemind'][10006]) && utility_session::get()['msgRemind'][10006] == 0) ? 0 : 1
                                                  ],
                                                  [
                                                      'title'     => SLanguage::tr('优惠卷', 'message'),
                                                      'msgType'   => 10005,
                                                      'remindStr' => SLanguage::tr('提醒', 'message'),
                                                      'ignoreStr' => SLanguage::tr('不提醒', 'message'),
                                                      'remind'    => (isset(utility_session::get()['msgRemind'][10005]) && utility_session::get()['msgRemind'][10005] == 1) ? 0 : 1,
                                                      'noRemind'  => (isset(utility_session::get()['msgRemind'][10005]) && utility_session::get()['msgRemind'][10005] == 0) ? 0 : 1
                                                  ],
                                                  [
                                                      'title'     => SLanguage::tr('排课通知', 'message'),
                                                      'msgType'   => 10008,
                                                      'remindStr' => SLanguage::tr('提醒', 'message'),
                                                      'ignoreStr' => SLanguage::tr('不提醒', 'message'),
                                                      'remind'    => (isset(utility_session::get()['msgRemind'][10008]) && utility_session::get()['msgRemind'][10008] == 1) ? 0 : 1,
                                                      'noRemind'  => (isset(utility_session::get()['msgRemind'][10008]) && utility_session::get()['msgRemind'][10008] == 0) ? 0 : 1
                                                  ]
            ],
            message_type::SYSTEM_INTERACTIVE => [ // 互动消息
                                                  [
                                                      'title'     => SLanguage::tr('获赞', 'message'),
                                                      'msgType'   => 10013,
                                                      'remindStr' => SLanguage::tr('提醒', 'message'),
                                                      'ignoreStr' => SLanguage::tr('不提醒', 'message'),
                                                      'remind'    => (isset(utility_session::get()['msgRemind'][10013]) && utility_session::get()['msgRemind'][10013] == 1) ? 0 : 1,
                                                      'noRemind'  => (isset(utility_session::get()['msgRemind'][10013]) && utility_session::get()['msgRemind'][10013] == 0) ? 0 : 1
                                                  ],
                                                  [
                                                      'title'     => SLanguage::tr('评论', 'message'),
                                                      'msgType'   => 10014,
                                                      'remindStr' => SLanguage::tr('提醒', 'message'),
                                                      'ignoreStr' => SLanguage::tr('不提醒', 'message'),
                                                      'remind'    => (isset(utility_session::get()['msgRemind'][10014]) && utility_session::get()['msgRemind'][10014] == 1) ? 0 : 1,
                                                      'noRemind'  => (isset(utility_session::get()['msgRemind'][10014]) && utility_session::get()['msgRemind'][10014] == 0) ? 0 : 1
                                                  ]
            ],
            message_type::SYSTEM_COURSE_INFO => [ // 课程信息
                                                  [
                                                      'title'     => SLanguage::tr('上课提醒', 'message'),
                                                      'msgType'   => 10001,
                                                      'remindStr' => SLanguage::tr('提醒', 'message'),
                                                      'ignoreStr' => SLanguage::tr('不提醒', 'message'),
                                                      'remind'    => (isset(utility_session::get()['msgRemind'][10001]) && utility_session::get()['msgRemind'][10001] == 1) ? 0 : 1,
                                                      'noRemind'  => (isset(utility_session::get()['msgRemind'][10001]) && utility_session::get()['msgRemind'][10001] == 0) ? 0 : 1
                                                  ],
                                                  [
                                                      'title'     => SLanguage::tr('下课统计', 'message'),
                                                      'msgType'   => 10004,
                                                      'remindStr' => SLanguage::tr('提醒', 'message'),
                                                      'ignoreStr' => SLanguage::tr('不提醒', 'message'),
                                                      'remind'    => (isset(utility_session::get()['msgRemind'][10004]) && utility_session::get()['msgRemind'][10004] == 1) ? 0 : 1,
                                                      'noRemind'  => (isset(utility_session::get()['msgRemind'][10004]) && utility_session::get()['msgRemind'][10004] == 0) ? 0 : 1
                                                  ]
            ],
            message_type::ORG_DATA_INFO_VERIFY => [ // 课程信息
                                                    [
                                                        'title'     => SLanguage::tr('机构审核信息', 'message'),
                                                        'msgType'   => 10010,
                                                        'remindStr' => SLanguage::tr('提醒', 'message'),
                                                        'ignoreStr' => SLanguage::tr('不提醒', 'message'),
                                                        'remind'    => (isset(utility_session::get()['msgRemind'][10010]) && utility_session::get()['msgRemind'][10010] == 1) ? 0 : 1,
                                                        'noRemind'  => (isset(utility_session::get()['msgRemind'][10010]) && utility_session::get()['msgRemind'][10010] == 0) ? 0 : 1
                                                    ]
            ],
        ];

        return $remindOption;
    }

    /**
     * 消息红标提醒
     * return num
     * wsn
     * 获取个人的未读消息，存入redis，当有人给他互动时候，删除key，再查 存入reids
     */
    public static function getUnreadNewsRemind($uid){
        if(empty($uid)) return interface_func::setMsg(30000,'用户不能为空！');
        $res = utility_services::call("/message/instation/unreadNewsRemind/{$uid}");
        return $res->data;
    }
}