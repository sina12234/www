<?php

class tweeter_ajax
{
    /**
     * @desc 获取图片流
     */
    public function pageGetPic()
    {
        if (empty($_POST['tweeterId']) || !(int)($_POST['tweeterId']))
            return interface_func::setMsg(1000);

        $res = tweeter_api::getPicList((int)($_POST['tweeterId']), 1, 9);

        if (!empty($res)) return interface_func::setData($res);

        return interface_func::setMsg(3002);
    }

    /**
     * @desc 发动态
     */
    public function pagePush()
    {
        if (empty($_POST['content']) || !strlen(trim($_POST['content'])))
            return interface_func::setMsg(1000);

        $user = user_api::loginedUser();
        if (empty($user['uid'])) return interface_func::setMsg(1021);

        $data = [
            'userId'  => $user['uid'],
            'content' => trim($_POST['content']),
        ];

        if (tweeter_api::publish($data)) return interface_func::setMsg(0);

        return interface_func::setMsg(1);
    }

    /**
     * @desc 我的粉丝列表
     */
    public function pageGetMyFans()
    {
        $r = interface_func::isValidId(['userId'], $_POST);
        if (!empty($r['code'])) return interface_func::setMsg($r['code']);

        $page   = isset($_POST['page']) && (int)($_POST['page']) ? (int)($_POST['page']) : 1;
        $length = isset($_POST['length']) && (int)($_POST['length']) ? (int)($_POST['length']) : 20;

        return tweeter_user_api::getMyFans($r['userId'], $page, $length);
    }

    /**
     * @desc 关注某人
     */
    public function pageFollow()
    {
        $followId = isset($_POST['followId']) && (int)($_POST['followId']) ? (int)($_POST['followId']) : 0;
        if (!$followId) return interface_func::setMsg(1000);

        $groupId = isset($_POST['groupId']) && (int)($_POST['groupId']) ? (int)($_POST['groupId']) : -1;

        $userId = user_api::getLoginUid();
        if (!$userId) {
            return interface_func::setMsg(1021); // no landing
        }

        $identity = interface_user_api::checkIsUserOrOrg($userId);
        $data = [
            'followId' => $followId,
            'groupId'  => $groupId,
            'orgId'    => $identity['orgId'],
            'userId'   => $identity['userId']
        ];

        return tweeter_user_api::createRelation($data) ? interface_func::setMsg(0) : interface_func::setMsg(1);
    }

    /**
     * @desc 取消关注某人
     */
    public function pageCancelFollow()
    {
        $followId = isset($_POST['followId']) && (int)($_POST['followId']) ? (int)($_POST['followId']) : 0;
        if (!$followId) return interface_func::setMsg(1000);

        $orgOwner = $orgId = 0;
        $userInfo = user_api::loginedUser();
        if (empty($userInfo['uid'])) {
            return interface_func::setMsg(1021); // no landing
        }

        $userId = $userInfo['uid'];
        $org    = user_organization::subdomain();
        if (!empty($org->userId)) {
            $orgOwner = $org->userId;
        }

        if (user_api::isAdmin($orgOwner, $userId)) {
            $orgId  = $userInfo['uid'];
            $userId = 0;
        }

        return tweeter_user_api::delRelation($followId, $userId, $orgId)
            ? interface_func::setMsg(0)
            : interface_func::setMsg(1);
    }

    /**
     * @desc 获取动态下的评论
     */
    public function pageGetComments()
    {
        $r = interface_func::isValidId(['tweeterId'], $_POST);
        if (!empty($r['code'])) return interface_func::setMsg($r['code']);

        $page   = isset($_POST['page']) && (int)($_POST['page']) ? (int)($_POST['page']) : 1;
        $length = isset($_POST['length']) && (int)($_POST['length']) ? (int)($_POST['length']) : 20;

        $res = tweeter_api::getComments($r['tweeterId'], $page, $length);
        if (empty($res)) return interface_func::setMsg(3002);

        return interface_func::setData($res);
    }

    /**
     * @desc 添加动态评论
     */
    public function pageAddComment()
    {
        $tweeterId = isset($_POST['tweeterId']) ? (int)($_POST['userId']) : 0;
        if (!$tweeterId) return interface_func::setMsg(1000);
        if (!isset($_POST['content']) || !strlen(trim($_POST['content'])))
            return interface_func::setMsg(1000);

        $userId    = isset($_POST['userId']) ? (int)($_POST['userId']) : 0;
        $orgId     = isset($_POST['orgId']) ? (int)($_POST['orgId']) : 0;
        $replyUser = isset($_POST['replyUser']) ? (int)($_POST['replyUser']) : 0;
        $replyOrg  = isset($_POST['replyOrg']) ? (int)($_POST['replyOrg']) : 0;

        $data = [
            'tweeterId' => $tweeterId,
            'userId'    => $userId,
            'orgId'     => $orgId,
            'content'   => trim($_POST['content']),
            'replyUser' => $replyUser,
            'replyOrg'  => $replyOrg,
        ];

        if (tweeter_user_api::addComment($data)) {
            return interface_func::setMsg(0);
        }

        return interface_func::setMsg(1);
    }

    /**
     * @desc 动态上传图片
     */
    public function pageUploadPic()
    {
        $uid = user_api::getLoginUid();
        if (!$uid) return interface_func::setMsg(1021);

        if (empty($_FILES['file']['tmp_name'])) return interface_func::setMsg(1027);

        $thumbNail = new SThumbnail($_FILES['file']['tmp_name']);
        $thumbNail->setMaxSize(500, 500);
        $fileName500 = utility_file::tempname('thumb_500_500');
        if ($thumbNail->genFile($fileName500) === false) return interface_func::setMsg(1028);

        $thumbNail->setMaxSize(220, 124);
        $fileName220 = utility_file::tempname('thumb_220_124');
        if ($thumbNail->genFile($fileName220) === false) return interface_func::setMsg(1028);

        $thumbNail->setMaxSize(100, 100);
        $fileName100 = utility_file::tempname('thumb_100_100');
        if ($thumbNail->genFile($fileName100) === false) return interface_func::setMsg(1028);
        $file = utility_file::instance();

        $res500    = $file->upload($fileName500, $uid, 'image', $_FILES['file']['name']);
        $res220    = $file->upload($fileName220, $uid, 'image', $_FILES['file']['name']);
        $res100    = $file->upload($fileName100, $uid, 'image', $_FILES['file']['name']);
        $resOrigin = $file->upload($_FILES['file']['tmp_name'], $uid, "image", $_FILES['file']['name']);

        if (!empty($res220->fid) && !empty($res500->fid) && !empty($res100->fid)) {
            unlink($fileName500);
            unlink($fileName220);
            unlink($fileName100);

            $addImg = tweeter_api::picUpload(
                [
                    'tweeterId' => 1,
                    'picRaw'    => $resOrigin->fid,
                    'picBig'    => $res500->fid,
                    'picMid'    => $res220->fid,
                    'picSma'    => $res100->fid
                ]
            );

            if ($addImg) return interface_func::setMsg(0);

            return interface_func::setMsg(1029);
        }

        return interface_func::setMsg(1029);
    }

    /**
     * @desc 动态点赞
     */
    public function pageZan()
    {
        $tweeterId = isset($_POST['tweeterId']) && (int)$_POST['tweeterId'] ? (int)$_POST['tweeterId'] : 0;
        if (!$tweeterId) return interface_func::setMsg(1000);

        $uid = user_api::getLoginUid();
        if (!$uid) return interface_func::setMsg(1021);

        if (tweeter_user_api::zan($uid, $tweeterId)) {
            return interface_func::setMsg(0);
        }

        return interface_func::setMsg(1);
    }

    /**
     * @desc 删除评论
     */
    public function pageDelComment()
    {
        $commentId = isset($_POST['commentId']) && (int)$_POST['commentId'] ? (int)$_POST['commentId'] : 0;
        if (!$commentId) return interface_func::setMsg(1000);

        $uid = user_api::getLoginUid();
        if (!$uid) return interface_func::setMsg(1021);

        $identity = interface_user_api::checkIsUserOrOrg($uid);
        if (tweeter_user_api::delComment($commentId, $identity['userId'], $identity['orgId'])) {
            return interface_func::setMsg(0);
        }

        return interface_func::setMsg(1);
    }
}
