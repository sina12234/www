<?php

class tweeter_api
{
    const TWEETER_ACTION_PUBLISH = '/tweeter/action/Publish/';

    const TWEETER_ACTION_COMMENT = '/tweeter/action/Comment/';

    const TWEETER_ACTION_PIC = '/tweeter/action/Pic/';

    const TWEETER_INFO_GET = '/tweeter/info/Get/';

    const TWEETER_INFO_PIC_LIST = '/tweeter/info/PicList/';

    const TWEETER_INFO_GET_FEEDS = '/tweeter/info/GetFeeds/';

    const GET_TWEETER_COMMENTS = '/tweeter/info/GetComments/';

    public static function publish($data)
    {
        $params = [
            'userId'  => $data['userId'],
            'orgId'   => $data['orgId'],
            'content' => $data['content'],
        ];

        $res = interface_func::requestApi(self::TWEETER_ACTION_PUBLISH, $params);
        if (!empty($res['code'])) return false;

        return true;
    }

    public static function comment($data)
    {
        $params = [
            'tweeterId' => $data['tweeterId'],
            'userId'    => $data['userId'],
            'orgId'     => $data['orgId'],
            'replyUser' => $data['replyUser'],
            'replyOrg'  => $data['replyOrg'],
            'content'   => $data['content'],
        ];

        $res = interface_func::requestApi(self::TWEETER_ACTION_COMMENT, $params);
        if (!empty($res['code'])) return false;

        return true;
    }

    public static function picUpload($data)
    {
        $params = [
            'tweeterId' => $data['tweeterId'],
            'picRaw'    => $data['picRaw'],
            'picBig'    => $data['picBig'],
            'picMid'    => $data['picMid'],
            'picSma'    => $data['picSma'],
            'sort'      => 0,
        ];

        $res = interface_func::requestApi(self::TWEETER_ACTION_PIC, $params);
        if (!empty($res['code'])) return false;

        return true;
    }

    public static function getTwInfo($tweeterId)
    {
        $res = interface_func::requestApi(self::TWEETER_ACTION_PUBLISH.$tweeterId);
        if (!empty($res['code'])) return [];

        return [
            'name'    => $res['result']['name'],
            'thumb'   => $res['result']['thumb'],
            'time'    => $res['result']['create_time'],
            'zan'     => $res['result']['zan_count'],
            'view'    => $res['result']['view_count'],
            'comment' => $res['result']['comment_count'],
            'content' => $res['result']['content'],
        ];
    }

    public static function getTwPic($tweeterId)
    {
        $res = interface_func::requestApi(self::TWEETER_INFO_PIC_LIST.$tweeterId);
        if (!empty($res['code'])) return [];

        $picList = [];
        foreach ($res['result'] as $v) {
            $picList[] = [
                'raw' => $v['pic_raw'],
                'big' => $v['pic_big'],
                'sma' => $v['pic_sma'],
                'med' => $v['pic_mid'],
            ];
        }

        return $picList;
    }

    /**
     * @param $params
     * userId
     * orgId
     * scope[all 1, my 2, myFollow 3]
     * page
     * length
     * @return array
     */
    public static function getFeeds($params)
    {
        $orgId  = !empty($params['orgId']) && (int)($params['orgId']) ? (int)($params['orgId']) : 0;
        $userId = !empty($params['userId']) && (int)($params['userId']) ? (int)($params['userId']) : 0;
        if (!$orgId && !$userId) return [];

        $page   = !empty($params['page']) && (int)($params['page']) ? (int)($params['page']) : 1;
        $length = !empty($params['length']) && (int)($params['length']) ? (int)($params['length']) : 30;
        // 1,全部动态，2,我关注的动态，3,我的动态
        $scope  = isset($params['scope']) && array_key_exists($params['scope'], [1, 2, 3]) ? $params['scope'] : 3;

        $res = interface_func::requestApi(
            self::TWEETER_INFO_GET_FEEDS,
            compact('orgId', 'userId', 'page', 'length', 'scope')
        );
        if (!empty($res['code'])) return [];

        return $res['result'];
    }

    public static function getComments($tweeterId, $page = 1, $length = -1)
    {
        $params = [
            'userId' => $tweeterId,
            'page'   => $page,
            'length' => $length
        ];

        $res = interface_func::requestApi(self::GET_TWEETER_COMMENTS, $params);
        if (!empty($res['code'])) return [];

        return $res['result'];
    }
}
