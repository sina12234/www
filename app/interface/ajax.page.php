<?php

class interface_ajax
{
    // todo token uid验证
    public function pageMessageUpdate()
    {
        if (empty($_POST['data']) || empty($_POST['uid']) || !is_numeric($_POST['uid']))
            return interface_func::setMsg(1000);

        $params = [
            'userFromId' => $_POST['data'],
            'userToId' => $_POST['uid']
        ];

        if ($_POST['type'] == 'del') {
            $params['action'] = 'delete';
        }

        $res = utility_services::call('/message/dialog/UpdateMessage', $params);

        if (!empty($res->code)) return interface_func::setMsg(1);

        return interface_func::setMsg(0);
    }

}
