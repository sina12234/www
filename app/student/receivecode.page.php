<?php
/**
 * 领取优惠码 展现页面
 */
class student_receivecode extends STpl{
    private $user,$domain;
    function __construct()
    {
        $domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
        $this->domain = $domain_conf->domain;
        $this->assign('domain', $this->domain);

        $this->user = user_api::loginedUser();
    }
    public function pageReceiveCode($inPath){
        if(empty($inPath[3])) $this->redirect('/');
        $fk_discount = base64_decode(htmlspecialchars(trim($inPath[3])));
        $domain_conf = SConfig::getConfig(ROOT_CONFIG."/discountCodeSalt.conf","salt");
        $salt = $domain_conf->one;
        $salt = $salt->name;
        $fk_discount = str_replace($salt,'',$fk_discount);//优惠的主键id
        $fk_discount = (int)$fk_discount;
        if(!$fk_discount) $this->redirect('/');
        //查看优惠是否过期 禁用 删除 领取页面展现的信息
        $ret = course_discount_api::getPkDiscountInfo($fk_discount);
        if(!empty($ret->data->pk_discount)){
            $result = course_discount_api::getDiscountInfo($fk_discount,$ret->data->fk_org);
            //用户是㤇领取过
            if(!empty($this->user)){
                $uid = $this->user['uid'];
                $operation_status = course_discount_api::codeIsReceive($fk_discount,$uid);
            }else{
                $operation_status = new stdClass();
                $operation_status ->code = 100001;
            }
            $this->assign('operation_status',$operation_status->code);
            $this->assign('result',$result);
        }
        $this->render('student/coupon.get.html');
    }


    /**
     * 点击领取
     */
    public function pageDraw(){
        if(empty($this->user)){
            echo json_encode(array('code'=>30001,'message'=>'请登录！'),JSON_UNESCAPED_UNICODE);
            exit;
        }
        if(empty($_POST['fk_discount'] || $this->user['uid'])){
            return interface_func::setMsg(1000);
        }
        $pk_discount = (int)$_POST['fk_discount'];
        $uid = $this->user['uid'];
        $ret = course_discount_api::getCodeStatus($pk_discount,$uid);
        echo json_encode(array('code'=>$ret->code,'message'=>$ret->message),JSON_UNESCAPED_UNICODE);
    }
}