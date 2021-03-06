<?php

class interface_messageApi
{
    public static function getDialogLastTotalList($params)
    {
        $res = message_api::getDialogLastTotalList($params);

        $result = [];
        if (!empty($res->result->items)) {
            $result['total']     = $res->result->totalSize;
            $result['page']      = $params['page'];
            $result['totalPage'] = $res->result->totalPage;

            $uidArr = $users = [];

            foreach ($res->result->items as $m) {
                $uidArr[$m->fk_user_from] = $m->fk_user_from;
            }
            $userInfo = user_api::listUsersByUserIds($uidArr);

            if (!empty($userInfo->result)) {
                foreach ($userInfo->result as $user) {
                    $users[$user->pk_user] = [
                        'name'  => $user->name,
                        'thumb' => interface_func::imgUrl($user->thumb_big)
                    ];
                }
            }

            $userFromName = SLanguage::tr('未设置', 'message');
            $userFromImage = '';
            foreach ($res->result->items as $v) {
                if (!empty($users[$v->fk_user_from]['name'])) {
                    $userFromName = $users[$v->fk_user_from]['name'];
                }

                if (!empty($users[$v->fk_user_from]['thumb'])) {
                    $userFromImage = $users[$v->fk_user_from]['thumb'];
                }

                if ($v->fk_user_from == 0) {
                    $userFromName = SLanguage::tr('系统消息', 'message');
                }

                $result['data'][] = [
                    'userFromId'    => (int)$v->fk_user_from,
                    'userFromName'  => $userFromName,
                    'userFromImage' => $userFromImage,
                    'content'       => $v->content,
                    'time'          => $v->insert_time,
                    'num'           => $v->totalNum,
                    'id'            => $v->pk_msg_id
                ];
            }
        }

        return $result;
    }
}








