<?php

class site_special extends STpl
{
    public function pageVacation()
    {
        $this->render('/special/hanjia.html');
    }

    public function pageNewyear()
    {
        $this->render('/special/yuandan.html');
    }

    public function pageChanSpecial()
    {
        $this->render('/special/chan_special.html');
    }

    public function pageZaozhuang()
    {
        $this->render('/special/weike_zaozhuang.html');
    }

    public function pageliuyi()
    {
        $this->render('/special/liuyi.html');
    }

    public function pageliuyiwinning()
    {   
        $this->render('/special/liuyi.winning.html');
    }

    public function pageChangjun()
    {
        $referer = !empty($_SERVER['HTTP_REFERER']) ? urlencode($_SERVER['HTTP_REFERER']) : '';

        $this->assign('referer', $referer);
        $this->render('/special/changjun.html');
    }

    public function pageSummerPromot()
    {
        $this->render('/special/summer.promot.html');
    }

}

