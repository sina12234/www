<?php

class user_message extends STpl
{
    public function __construct()
    {
        $user = user_api::loginedUser();
        if (empty($user)) {
            $this->redirect("/site.main.login");
        }

        $this->user = $user;
    }

    public function pageEntry()
    {
        $this->assign('userInfo', $this->user);
        $this->assign('remindOption', json_encode(message_api::getRemindOption()));
        $this->display("user/message.list.html");
    }

    public function pageLeftMenu()
    {
        $this->display('user/message.left.menu.html');
    }
}
