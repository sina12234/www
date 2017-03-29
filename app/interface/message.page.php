<?php

/**
 * @SWG\Info(title="interface message api", version="1.1")
 */
class interface_message extends interface_base
{
    /**
     * @SWG\Post(
     *     path = "/interface/message/Add",
     *     tags = {"message add"},
     *     summary = "test add",
     *     description = "this is only a test",
     *     operationId = "new add message",
     *     produces = {"application/xml", "application/json"},
     *     @SWG\Parameter(
     *         in = "body",
     *         name = "body",
     *         description = "test message two",
     *         @SWG\Schema(ref="#/definitions/User123")
     *     ),
     *     @SWG\Response(response="200", description="add message")
     * )
     */
    public function pageAdd()
    {
        $res = message_api::addMsgV2($this->paramsInfo['params'], $this->paramsInfo['params']['uid'], $this->paramsInfo['params']['userToken']);

        if (!empty($res)) return $this->setData($res);

        return $this->setMsg(1);
    }
	//置消息为已读
	public function pageUpdate(){
		$data['userToId'] = $this->paramsInfo['params']['uid'];
		$data['msgId'] = $this->paramsInfo['params']['msgId'];
		$res = message_api::updateDialog($data);
		return $this->setMsg($res->code);
	}

    public function pageAddDialog()
    {
        if (!isset($this->paramsInfo['params']['content']) || !strlen($this->paramsInfo['params']['content']))
            return $this->setMsg(1000);

            $this->v(
            [
                'userId' => 1000,
                'userToId' => 1000
            ]
        );

        if (mb_strlen($this->paramsInfo['params']['content']) > 2000) {
            return $this->setMsg(2033);
        }

        // todo content limit
        $data = [
            'content' => $this->paramsInfo['params']['content'],
            'userFrom'    => $this->paramsInfo['params']['userId'],
            'userTo' => $this->paramsInfo['params']['userToId'],
            'msgType' => message_type::SYSTEM_CONTACT_INFORMATION
        ];

        $res = message_api::addDialog($data);
        if ($res) return $this->setData(['maxId' => $res]);

        return $this->setMsg(1);
    }

    public function pageGetDialogList()
    {
        $this->v(
            [
                'userId' => 1000,
            ]
        );
		$oid = $this->paramsInfo['oid'];
        $userId = $this->paramsInfo['params']['userId'];
        $maxId             = isset($this->paramsInfo['params']['maxId']) ? $this->paramsInfo['params']['maxId'] : 0;
        $data['length']    = 500;
        $data['userToId'] = $userId;
		$data['messageType'] = empty($oid) ? array():array(10017,10019);//机构app只取会员购买和会员到期的站内消息
        $maxId && $data['maxId'] = $maxId;

        $res = utility_services::call('/message/dialog/lists', $data);
        if (empty($res->result->items)) return $this->setMsg(3002);

        $result = $users = $usersIdArr = [];

        foreach ($res->result->items as $v) {
            $usersIdArr[$v->fk_user_from] = $v->fk_user_from;
            $usersIdArr[$v->fk_user_to] = $v->fk_user_to;
        }

        $userInfo = user_api::listUsersByUserIds(array_values($usersIdArr));

        if (!empty($userInfo->result)) {
            foreach ($userInfo->result as $v) {
                $users[$v->pk_user] = [
                    'name'  => $v->name,
                    'thumb' => $this->imgUrl($v->thumb_big)
                ];
            }
        }

        $userFromName = $userToName = SLanguage::tr('未设置', 'message');
        $userFromImage = $userToImage = '';
		$obMessage = new message_type();
		$messageTypes=$obMessage->getTypes();
        foreach ($res->result->items as $v) {
            if (!empty($users[$v->fk_user_from]['name'])) {
                $userFromName = $users[$v->fk_user_from]['name'];
            }

            if (!empty($users[$v->fk_user_from]['thumb'])) {
                $userFromImage = $users[$v->fk_user_from]['thumb'];
            }

            if (!empty($users[$v->fk_user_to]['name'])) {
                $userToName = $users[$v->fk_user_to]['name'];
            }

            if (!empty($users[$v->fk_user_to]['thumb'])) {
                $userToImage = $users[$v->fk_user_to]['thumb'];
            }

            if ($v->fk_user_from == 0 && empty($oid)) {
                $userFromName = '系统消息';
            }elseif($oid>0 && $v->fk_user_from==0){
				$userFromName = empty($messageTypes[$v->message_type]) ? "系统消息" : $messageTypes[$v->message_type]['title'];
			}
            $result[]                     = [
                'maxId'        => (int)$v->pk_msg_id,
                'content'      => html_entity_decode($v->content),
                'status'       => $v->status,
                'insertTime'   => $v->insert_time,
                'userFromId'   => (int)$v->fk_user_from,
                'userFromName' => $userFromName,
                'userFromImage' => $userFromImage,
				'type'			=> $userFromName == '系统消息' ? 1 : 2,
                'userToId'   => (int)$v->fk_user_to,
                'userToName' => $userToName,
                'userToImage' => $userToImage,
            ];
        }

			//处理为已读
        message_api::updateAllDialog(array("userToId"=>$userId,"messageType"=>$data['messageType']));
        return $this->setData($result);
    }

    public function pageGetListLast()
    {

        $this->v(['userToId'=>1000]);

        $param['uid'] = $this->paramsInfo['params']['userToId'];
        $param['page'] = $this->s('page') ? (int)$this->paramsInfo['params']['page'] : 1;
        $param['length'] = $this->s('length') ? (int)$this->paramsInfo['params']['length'] : 20;

        $res = interface_messageApi::getDialogLastTotalList($param);

        if (!empty($res)) return $this->setData($res);

        return $this->setMsg(3002);
    }

    /*
     * 上课通知
     * @param  userId  消息接收者Id
     * @param  maxId   已接收消息的最大maxId
     */
    public function pageGetDialogListV2(){
        $this->v(['userId'=>1000]);
        $userId = (int)($this->paramsInfo['params']['userId']);
        $maxId  = isset($this->paramsInfo['params']['maxId']) ? (int)($this->paramsInfo['params']['maxId']) : 0;
		$oid    = empty($this->paramsInfo['oid'])?0:intval($this->paramsInfo['oid']);
        $data = [
            'length'   => 500,
            'userToId' => $userId,
            'maxId'    => $maxId
        ];
		if($oid>0){
			$orgInfo = user_organization::getOrg($oid);
			if (empty($orgInfo)) return $this->setMsg(1000);
			 $data ['orgUserId'] = $orgInfo->fk_user_owner;
		}
		$data['messageType'] = empty($oid) ? array():array(10017,10019);//机构app只取会员购买和会员到期的站内消息
        $res = utility_services::call('/message/dialog/lists',$data);
		
        if (empty($res->result->items)) return $this->setMsg(3002);

        $result = $users = $usersIdArr = array();

        foreach($res->result->items as $v){
            $usersIdArr[$v->fk_user_from] = $v->fk_user_from;
            $usersIdArr[$v->fk_user_to]   = $v->fk_user_to;
        }

        $userInfo = user_api::listUsersByUserIds(array_values($usersIdArr));
        
        if(!empty($userInfo->result)){
            foreach($userInfo->result as $v){
                $users[$v->pk_user] = [
                    'name'  => !empty($v->real_name) ? $v->real_name : (!empty($v->name) ? $v->name : ''),
                    'thumb' => $this->imgUrl($v->thumb_big)
                ];
            }
        }
        
        $userFromName  = $userToName  = SLanguage::tr('未设置', 'message');
        $userFromImage = $userToImage = '';
		$obMessage = new message_type();
		$messageTypes=$obMessage->getTypes();
        foreach($res->result->items as $k=>$v){
            if(!empty($users[$v->fk_user_from]['name'])){
                $userFromName = $users[$v->fk_user_from]['name'];
            }

            if(!empty($users[$v->fk_user_from]['thumb'])){
                $userFromImage = $users[$v->fk_user_from]['thumb'];
            } 

            if(!empty($users[$v->fk_user_to]['name'])){
                $userToName = $users[$v->fk_user_to]['name'];
            }   

            if(!empty($users[$v->fk_user_to]['thumb'])){
                $userToImage = $users[$v->fk_user_to]['thumb'];
            }
			if ($v->fk_user_from == 0) {
                $userFromName = '系统消息';
            }elseif($v->fk_user_from==0){
				$userFromName = empty($messageTypes[$v->message_type]) ? "系统消息" : $messageTypes[$v->message_type]['title'];
			}

			$result[] = [
						'maxId'         => (int)$v->pk_msg_id,
						'content'       => html_entity_decode($v->content),
						'status'        => $v->status,
						'insertTime'    => $v->insert_time,
						'userFromId'    => (int)$v->fk_user_from,
						'type'          =>  $v->message_type,
						'userFromName'  => $userFromName,
						'userFromImage' => $userFromImage,
						'userToId'      => (int)$v->fk_user_to,
						'userToName'    => $userToName,
						'userToImage'   => $userToImage
			];
        }

        message_api::updateAllDialog(array("userToId"=>$userId,"messageType"=>$data['messageType']));
        return $this->setData($result);
    }
	
	/*
	 * 获取单对单历史聊天
	 * @param userId
	 * @param userFromId
	 * @param maxId
	 */
	public function pageChatSingle(){
		$this->v(['userId'=>1000]);
		
		$userFromId = (int)($this->paramsInfo['params']['userFromId']);
		$userId     = (int)($this->paramsInfo['params']['userId']);
		$maxId      = isset($this->paramsInfo['params']['maxId']) ? (int)($this->paramsInfo['params']['maxId']) : 0;
		$data = message_api::getchatdetail($userFromId,$userId,$maxId);
		
		return $this->setData($data);
	}

    public function pageEmptyChat()
    {
        $r = interface_func::isValidId(['userId', 'linkMan'], $this->paramsInfo['params']);
        if (!empty($r['code'])) return interface_func::setMsg($r['code']);

        if (message_api::msgDel($r['linkMan'], $r['linkMan'], message_type::SYSTEM_CONTACT_INFORMATION)) {
            return interface_func::setMsg(0);
        }

        return interface_func::setMsg(1);
    }

    public function pageAddLinkMan()
    {
        $r = interface_func::isValidId(['userId', 'linkMan'], $this->paramsInfo['params']);
        if (!empty($r['code'])) return interface_func::setMsg($r['code']);

        // add default group -4
        if (message_api::contactsMove([$r['linkMan']], -4, $r['userId'])) {
            return interface_func::setMsg(0);
        }

        return interface_func::setMsg(1);
    }

    public function pageAddToBlack()
    {
        $r = interface_func::isValidId(['userId', 'linkMan'], $this->paramsInfo['params']);
        if (!empty($r['code'])) return interface_func::setMsg($r['code']);
        $groupId = empty($this->paramsInfo['params']['add']) ? -4 : -5;

        // add black group -5/add default group -4
        if (message_api::contactsMove([$r['linkMan']], $groupId, $r['userId'])) {
            return interface_func::setMsg(0);
        }

        return interface_func::setMsg(1);
    }

    public function pageGetContactList()
    {
        $r = interface_func::isValidId(['userId'], $this->paramsInfo['params']);
        if (!empty($r['code'])) return interface_func::setMsg($r['code']);

        $data = message_api::getDefaultAndBlackGroupList($r['userId']);
        if (!empty($data)) {
            return interface_func::setData($data);
        }

        return interface_func::setMsg(3002);
    }

    public function pageGetRelation()
    {
        $r = interface_func::isValidId(['userId', 'linkMan'], $this->paramsInfo['params']);
        if (!empty($r['code'])) return interface_func::setMsg($r['code']);

        $res = message_api::getUserContact($r['userId'], $r['linkMan']);

        if (empty($res)) return interface_func::setMsg(3002);

        return interface_func::setData($res);
    }

    public function pageDelContact()
    {
        $r = interface_func::isValidId(['userId', 'linkMan'], $this->paramsInfo['params']);
        if (!empty($r['code'])) return interface_func::setMsg($r['code']);

        if (message_api::delContact($r['userId'], $r['linkMan'])) {
            return interface_func::setMsg(0);
        }

        return interface_func::setMsg(1);
    }
}
