<?php

class message_ajax
{
    public function __construct()
    {
        $user = user_api::loginedUser();
        if (empty($user['uid'])) return interface_func::setMsg(1021);
        $this->uid = $user['uid'];
    }

    // ====================follow group action===========================

    /**
     * get group list
     */
    public function pageGetGroupList()
    {
        $except = [];
        if (isset($_POST['filter']) && $_POST['filter'] == 1) {
            $except = [-1, -5];
        }

        $data = message_api::getGroupListByUid($this->uid, $except);

        if (!empty($data))
            return interface_func::setData(['groupList' => $data]);

        return interface_func::setMsg(3002);
    }

    /**
     * add group
     */
    public function pageAddGroup()
    {
        if (empty($_POST['groupName']))
            return interface_func::setMsg(1000);

        $groupName = trim($_POST['groupName']);
        if (mb_strlen($groupName, 'UTF-8') > 20) {
            return interface_func::setMsg(1043);
        }

        if (message_api::addGroup($this->uid, $groupName)) return interface_func::setMsg(0);

        return interface_func::setMsg(1);
    }

    /**
     * update group name
     */
    public function pageChangeName()
    {
        if (empty($_POST['groupName']) || !isset($_POST['groupId']) || !(int)($_POST['groupId']))
            return interface_func::setMsg(1000);

        $groupName = trim($_POST['groupName']);
        $groupId   = (int)($_POST['groupId']);

        if (mb_strlen($groupName, 'UTF-8') > 20) {
            return interface_func::setMsg(1043);
        }

        if (message_api::changeGroupName($this->uid, $groupId, $groupName)) return interface_func::setMsg(0);

        return interface_func::setMsg(1);
    }

    /**
     * delete group by group id user id
     */
    public function pageDelGroup()
    {
        if (!isset($_POST['groupId']) || !(int)($_POST['groupId']))
            return interface_func::setMsg(1000);

        $groupId = (int)($_POST['groupId']);

        if (message_api::delGroup($this->uid, $groupId)) return interface_func::setMsg(0);

        return interface_func::setMsg(1);
    }

    // ====================follow group contacts action===========================

    /**
     * get contacts list by group id
     */
    public function pageGetContactsListByGroupId()
    {
        if (!isset($_POST['groupId']) || !(int)($_POST['groupId']))
            return interface_func::setMsg(1000);

        $groupId = (int)($_POST['groupId']);

        $data = message_api::getContactsListByGroupId($groupId, $this->uid);
        if (!empty($data)) {
            return interface_func::setData(['contactsList' => $data]);
        }

        return interface_func::setMsg(3002);
    }

    public function pageLatestUser()
    {
        $data = message_api::getLatestUser($this->uid);
        if (!empty($data)) {
            return interface_func::setData(['contactsList' => $data]);
        }

        return interface_func::setMsg(3002);
    }

    /**
     * group contacts user move
     */
    public function pageContactsMove()
    {
        if (
            empty($_POST['userId']) || count($_POST['userId']) < 1 ||
            !isset($_POST['groupId']) || !(int)($_POST['groupId'])
        ) {
            return interface_func::setMsg(1000);
        }

        if (message_api::contactsMove($_POST['userId'], (int)($_POST['groupId']), $this->uid)) return interface_func::setMsg(0);

        return interface_func::setMsg(1);
    }

    public function pageDelContact()
    {
        if (empty($_POST['linkMan']) || count($_POST['linkMan']) < 1) {
            return interface_func::setMsg(1000);
        }
        $linkMan = (int)($_POST['linkMan'][0]);

        if (message_api::delContact($this->uid, $linkMan)) {
            return interface_func::setMsg(0);
        }

        return interface_func::setMsg(1);
    }

    // ================== message user text gather action ====================

    /**
     * get messages list
     */
    public function pageGetMyMessages()
    {
        $page   = isset($_POST['page']) && (int)($_POST['page']) ? (int)($_POST['page']) : 1;
        $length = isset($_POST['length']) && (int)($_POST['length']) ? (int)($_POST['length']) : 10;

        $res = message_api::getMyMessages($this->uid, $page, $length);
        if (!empty($res['data'])) {
            return interface_func::setData(
                [
                    'messageList' => $res['data'],
                ]
            );
        }

        return interface_func::setMsg(3002);
    }

    /**
     * get message detail
     */
    public function pageGetMsgDetail()
    {
        if (!isset($_POST['userFrom']) || !isset($_POST['msgType']) || !(int)($_POST['msgType'])) {
            return interface_func::setMsg(1000);
        }

        $userFrom = (int)$_POST['userFrom'];
        $msgType  = (int)$_POST['msgType'];
        $page     = isset($_POST['page']) && (int)($_POST['page']) ? (int)($_POST['page']) : 1;
        $length   = isset($_POST['length']) && (int)($_POST['length']) ? (int)($_POST['length']) : 10;


        $res = message_api::getMsgDetail($userFrom, $this->uid, $msgType, $page, $length);

        if (!empty($res['data'])) {
            return interface_func::setData(
                [
                    'messageDetail' => $res['data'],
                    'totalPage'     => $res['totalPage'],
                    'totalSize'     => $res['totalSize'],
                ]
            );
        }

        return interface_func::setMsg(3002);
    }

    /**
     * msg del
     */
    public function pageMsgDel()
    {
        if (!isset($_POST['userFrom']) || !isset($_POST['msgType']) || !(int)($_POST['msgType'])) {
            return interface_func::setMsg(1000);
        }
		
        if (message_api::msgDel((int)$_POST['userFrom'], $this->uid, (int)$_POST['msgType'])) return interface_func::setMsg(0);

        return interface_func::setMsg(1);
    }

    /**
     * is_top action
     */
    public function pageMsgTop()
    {
        $userFrom = 0; // default system
        if (isset($_POST['userFrom']) && (int)($_POST['userFrom'])) {
            $userFrom = (int)($_POST['userFrom']);
        }

        if (!isset($_POST['msgType']) || !(int)($_POST['msgType'])) {
            return interface_func::setMsg(1000);
        }

        $msgType  = (int)$_POST['msgType'];
        $type     = (int)($_POST['type']) ? 1 : 0;

        $param = [
            'userFrom' => $userFrom,
            'userTo'   => $this->uid,
            'msgType'  => $msgType,
            'type'     => $type
        ];

        $res = utility_services::call("/user/followGroup/MsgTop", $param);

        if ($res->code) return interface_func::setMsg(1);
        return interface_func::setMsg(0);
    }

    /**
     * is_remind action
     */
    public function pageMsgRemind()
    {
        if (!isset($_POST['data']) || empty($_POST['data']))
            return interface_func::setMsg(1000);

        $userFrom = 0; // default system
        foreach ($_POST['data'] as $v) {
            if (isset($v['userFrom']) && (int)($v['userFrom'])) {
                $userFrom = (int)($v['userFrom']);
            }

            if (!isset($v['msgType']) || !(int)($v['msgType'])) {
                return interface_func::setMsg(1000);
            }

            $param = [
                'userFrom' => $userFrom,
                'userTo'   => $this->uid,
                'msgType'  => (int)$v['msgType'],
                'type'     => (int)($v['type'])
            ];
            utility_session::get()['msgRemind'][(int)$v['msgType']] = (int)($v['type']);

            $res = utility_services::call("/user/followGroup/MsgRemind", $param);
            if ($res->code) {
                SLog::fatal('MsgRemind failed,params[%s]', var_export($param, 1));
            }
        }
        return interface_func::setMsg(0);
    }

    public function pageChatAdd()
    {
        if (!isset($_POST['userTo']) || !(int)($_POST['userTo'])) {
            return interface_func::setMsg(1000);
        }

        if (!isset($_POST['content']) || !strlen($_POST['content'])) {
            return interface_func::setMsg(1000);
        }

        $content = trim($_POST['content']);
        if (mb_strlen($content, 'UTF-8') > 200) {
            return interface_func::setMsg(1043);
        }
        $receive = (int)($_POST['userTo']);

        if (message_api::checkUserIsBlack($this->uid, $receive)) {
            return interface_func::setMsg(4002);
        }

        $res = message_api::addDialog(
            [
                'content'  => $content,
                'userFrom' => $this->uid,
                'userTo'   => $receive,
                'msgType'  => message_type::SYSTEM_CONTACT_INFORMATION
            ]
        );

        if ($res) return interface_func::setMsg(0);

        return interface_func::setMsg(1);
    }

    public function pageDirectChat()
    {
        if (!isset($_POST['userFrom']) || !(int)($_POST['userFrom'])) {
            return interface_func::setMsg(1000);
        }

        if (!isset($_POST['content']) || !strlen($_POST['content'])) {
            return interface_func::setMsg(1000);
        }

        $content = trim($_POST['content']);
        if (mb_strlen($content, 'UTF-8') > 200) {
            return interface_func::setMsg(1043);
        }
        $receive = (int)($_POST['userFrom']);

        $res = message_api::addDialog(
            [
                'content'  => $content,
                'userFrom' => $receive,
                'userTo'   => $this->uid,
                'msgType'  => message_type::SYSTEM_CONTACT_INFORMATION
            ]
        );

        if ($res) return interface_func::setMsg(0);

        return interface_func::setMsg(1);
    }

    public function pageGetChatDetail()
    {
        $r = interface_func::isValidId(['userFrom'], $_POST);
        if (!empty($r['code'])) return interface_func::setMsg($r['code']);
        $maxId = 0;
        if (isset($_POST['maxId']) && (int)($_POST['maxId'])) {
            $maxId = (int)($_POST['maxId']);
        }

        $page   = isset($_POST['page']) && (int)($_POST['page']) && $_POST['page'] > 0 ? (int)($_POST['page']) : 1;
        $length = isset($_POST['length']) && (int)($_POST['length']) && $_POST['length'] > 0 ? (int)($_POST['length']) : 500;

        $res = message_api::getChatDetail((int)($_POST['userFrom']), $this->uid, $maxId, $page, $length);
        if (empty($res)) return interface_func::setMsg(3002);

        return interface_func::setData(['chatDetailList' => $res]);

    }

    public function pageSearchUser()
    {
        if (!isset($_POST['keyword']) || empty(trim($_POST['keyword']))) {
            return interface_func::setMsg(1000);
        }

        $res = message_api::searchContacts(trim($_POST['keyword']));

        if (!empty($res)) {
            foreach ($res as $v) {
                $data[] = [
                    'uid' => $v->pk_user,
                    'userName' => $v->real_name,
                    'userThumb' => interface_func::imgUrl($v->thumb_med)
                ];
            }

            return interface_func::setData($data);
        }

        return interface_func::setMsg(3002);
    }
}

