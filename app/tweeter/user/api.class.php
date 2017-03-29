<?php

class tweeter_user_api
{
    const TWEETER_USER_PULL_BLACK = '/tweeter/user/PullBlack/';

    const TWEETER_USER_CREATE_GROUP = '/tweeter/user/CreateGroup/';

    const TWEETER_USER_CREATE_RELATION = '/tweeter/user/CreateRelation/';

    const TWEETER_USER_DEL_RELATION = '/tweeter/user/DelRelation/';

    const TWEETER_USER_DEL_GROUP = '/tweeter/user/DelGroup/';

    const TWEETER_USER_CHANGE_GROUP_NAME = '/tweeter/user/ChangeGroupName/';

    const TWEETER_USER_GROUP_LIST = '/tweeter/user/GroupList/';

    const TWEETER_MY_FANS = '/tweeter/my/Fans/';

    const TWEETER_MY_FOLLOW_NUM = '/tweeter/my/FollowNum/';

    const TWEETER_ACTION_COMMENT = '/tweeter/action/Comment/';

    const TWEETER_USER_ZAN = '/tweeter/info/Zan/';

    const TWEETER_USER_DEL_COMMENT = '/tweeter/action/DelComment/';

    /**
     * @desc pull black
     *
     * @param $data
     * @return bool
     */
    public static function pullBlack($data)
    {
        $params = [
            'userId'      => $data['userId'],
            'blackUserId' => isset($data['blackUserId']) ? $data['blackUserId'] : 0,
            'blackOrgId'  => isset($data['blackOrgId']) ? $data['blackOrgId'] : 0,
        ];

        $res = interface_func::requestApi(self::TWEETER_USER_PULL_BLACK, $params);
        if (!empty($res['code'])) return false;

        return true;
    }

    /**
     * @desc create group
     *
     * @param $data
     * @return bool
     */
    public static function createGroup($data)
    {
        $params = [
            'userId'    => $data['userId'],
            'groupName' => $data['groupName'],
        ];

        $res = interface_func::requestApi(self::TWEETER_USER_CREATE_GROUP, $params);
        if (!empty($res['code'])) return false;

        return true;
    }

    /**
     * @desc follow user
     *
     * @param $data
     * @return bool
     */
    public static function createRelation($data)
    {
        $params = [
            'followId' => $data['followId'],
            'groupId'  => $data['groupId'],
            'orgId'    => isset($data['orgId']) ? $data['orgId'] : 0,
            'userId'   => isset($data['userId']) ? $data['userId'] : 0,
        ];

        $res = interface_func::requestApi(self::TWEETER_USER_CREATE_RELATION, $params);
        if (!empty($res['code'])) return false;

        return true;
    }

    /**
     * @desc cancel follow
     *
     * @param $followId
     * @param $userId
     * @param int $orgId
     * @return bool
     */
    public static function delRelation($followId, $userId, $orgId=0)
    {
        $params = [
            'followId' => $followId,
            'userId'   => $userId,
            'orgId'    => $orgId
        ];

        $res = interface_func::requestApi(self::TWEETER_USER_DEL_RELATION, $params);
        if (!empty($res['code'])) return false;

        return true;
    }

    /**
     * @desc delete group
     *
     * @param $userId
     * @param $groupId
     * @return bool
     */
    public static function delGroup($userId, $groupId)
    {
        $params = [
            'userId'  => $userId,
            'groupId' => $groupId
        ];

        $res = interface_func::requestApi(self::TWEETER_USER_DEL_GROUP, $params);
        if (!empty($res['code'])) return false;

        return true;
    }

    /**
     * @desc update group name
     *
     * @param $userId
     * @param $groupId
     * @param $groupName
     * @return bool
     */
    public static function changeGroupName($userId, $groupId, $groupName)
    {
        $params = [
            'userId'    => $userId,
            'groupId'   => $groupId,
            'groupName' => $groupName
        ];

        $res = interface_func::requestApi(self::TWEETER_USER_CHANGE_GROUP_NAME, $params);
        if (!empty($res['code'])) return false;

        return true;
    }

    /**
     * @desc get group list by user id
     *
     * @param $userId
     * @param int $page
     * @param int $length
     * @return array
     */
    public static function getGroupList($userId, $page = 1, $length = -1)
    {
        $params = [
            'userId' => $userId,
            'page'   => $page,
            'length' => $length
        ];

        $res = interface_func::requestApi(self::TWEETER_USER_GROUP_LIST, $params);
        if (!empty($res['code'])) return [];

        return $res['result'];
    }

    /**
     * @desc get my fans list
     *
     * @param $uid
     * @param int $page
     * @param int $length
     * @return array|mixed
     */
    public static function getMyFans($uid, $page = 1, $length = -1)
    {
        $params = [
            'userId' => $uid,
            'page'   => $page,
            'length' => $length
        ];

        $res = interface_func::requestApi(self::TWEETER_MY_FANS, $params);

        if (!empty($res['code'])) return [
            'totalPage' => 0,
            'totalSize' => 0,
            'data'      => []
        ];

        return $res;
    }

    /**
     * @desc get my follow num
     *
     * @param $uid
     * @return array
     */
    public static function getMyFollowNum($uid)
    {
        $data = [
            'follow' => 0,
            'fans'   => 0,
            'feed'   => 0
        ];

        $url = self::TWEETER_MY_FOLLOW_NUM.$uid;
        $res = interface_func::requestApi($url);
        if (!empty($res['code'])) return $data;

        return [
            'follow' => $res['result']['follow_count'],
            'fans'   => $res['result']['fan_count'],
            'feed'   => $res['result']['tweet_count']
        ];
    }

    /**
     * @desc add comment
     *
     * @param $data
     * @return bool
     */
    public static function addComment($data)
    {
        $res = interface_func::requestApi(self::TWEETER_ACTION_COMMENT, $data);
        if (!empty($res['code'])) return false;

        return true;
    }

    /**
     * @desc user zan feed
     *
     * @param $userId
     * @param $tweeterId
     * @return bool
     */
    public static function zan($userId, $tweeterId)
    {
        $params = [
            'userId'    => $userId,
            'tweeterId' => $tweeterId
        ];

        $res = interface_func::requestApi(self::TWEETER_USER_ZAN, $params);
        if (!empty($res['code'])) return false;

        return true;
    }

    /**
     * @desc user delete comment by comment id
     *
     * @param $commentId
     * @param $userId
     * @param $orgId
     * @return bool
     */
    public static function delComment($commentId, $userId, $orgId)
    {
        $params = [
            'tweeterId' => $commentId,
            'userId'    => $userId,
            'orgId'     => $orgId
        ];

        $res = interface_func::requestApi(self::TWEETER_USER_DEL_COMMENT, $params);
        if (!empty($res['code'])) return false;

        return true;
    }
}
