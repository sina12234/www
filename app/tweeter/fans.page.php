<?php

class tweeter_fans extends STpl
{
    public function pageEntry()
    {
        $user = user_api::loginedUser();
        if (empty($user)) {
            $this->redirect("/");
        }

        $countInfo = tweeter_user_api::getMyFollowNum($user['uid']);

        $this->assign('countInfo', $countInfo);
        $this->render('/tweeter/fans.html');
    }

    public function pageFeeds()
    {
        $this->render('/tweeter/feeds.html');
    }
    
    public function pageInfo($inPath)
    {
        $tweeterId = isset($inPath[3]) && (int)$inPath[3] ? (int)$inPath[3] : 0;
        !$tweeterId && $this->redirect('/tweeter/fans');

        $info = tweeter_api::getTwInfo($tweeterId);
        empty($info) && $this->redirect('/tweeter/fans');

        $this->assign('info', $info);
        $this->render('/tweeter/info.html');
    }
}
