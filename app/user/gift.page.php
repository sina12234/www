<?php
/**
 * 礼物
 * @author zhouyu
 * date: 2016/12/07
 */
class user_gift extends STpl{
    var $user;

    function __construct(){
        //如果没有登陆到登陆界面
        $this->user = user_api::loginedUser();
    }

    /*
     * 学生获取礼物
     * @params uId 用户ID
     * @return array
     * @link https://wiki.gn100.com/docs:api:getgiftlist
     * */
    public function pagegetStudentGift(){
        $planId = !empty($_POST['planId'])?$_POST['planId']:''; //7387
        $uid = !empty($this->user['uid'])?$this->user['uid']:'';
        if(empty($planId)){
            return json_encode(array('code' => '-101', 'msg' => '排课ID不能为空!'));
        }
        $giftList['point'] = 0;
        $giftList['redNameCardStatus'] = false;
        if(!empty($uid)){
            //获取礼物信息
            $gift = user_api::getStudentGiftByUserId($uid);
            $giftList['gift'] = array();
            if (!empty($gift->result->items)) {
                foreach($gift->result->items as $key => $val){
                    $val->giftId = $val->fk_gift;
                    $val->pkUserGift = $val->pk_user_gift;
                    $val->giftCount = $val->gift_count;
                    unset($val->t_gift_status);
                    unset($val->ver);
                    unset($val->pk_user_gift);
                    unset($val->t_user_gift_status);
                    unset($val->fk_user);
                    unset($val->gift_count);
                    $giftList['gift'][$val->fk_gift] = $val;
                    unset($val->fk_gift);
                }
            }
            //获取用户积分
            $userScorePoint = user_api::getUserScoreAndPoint($uid);
            $giftList['point'] = !empty($userScorePoint->items[0]->point)?$userScorePoint->items[0]->point:0;
            //获取红名卡是否过期
            $redCard = user_api::GetRedCardByUId($this->user['uid']);
            if(!empty($redCard->items[0])){
                if($redCard->items[0]->status == 1 && $redCard->items[0]->stop_time > time()){
                    $giftList['redNameCardStatus'] = true;
                }
            }
        }
        //获取当前排课教师获得礼物数
        $coursePlan = course_plan_api::getGetCoursePlanByPid($planId);
        if (empty($coursePlan->data->fk_user_plan)) {
            return json_encode(array('code' => '-103', 'msg' => '获取排课教师失败!'));
        }
        //讲师ID
        $teacherId = $coursePlan->data->fk_user_plan;
        $params = array(
            'fk_plan' => $planId,
            'fk_user_teacher' => $teacherId,
            'fk_gift' => 1, //1鲜花
        );
        $giftList['giftSum'] = 0;
        $giftCount = user_gift_api::getPlanGiftSum($params);
        if (!empty($giftCount->result->items[0]->gift_sum)) {
            $giftList['giftSum'] = $giftCount->result->items[0]->gift_sum;
        }
        return json_encode(array('code' => '0', 'result' => $giftList));
    }

    public function pageGetGuide(){
        $planId = !empty($_POST['planId'])?$_POST['planId']:''; //7387
        $uid = !empty($this->user['uid'])?$this->user['uid']:'';
        if(empty($planId)){
            return json_encode(array('code' => '-101', 'msg' => '排课ID不能为空!'));
        }
        if(empty($uid)) {
            return json_encode(array('code' => '-102', 'msg' => '用户ID不能为空!'));
        }
        //获取当前排课教师获得礼物数
        $coursePlan = course_plan_api::getGetCoursePlanByPid($planId);
        if (empty($coursePlan->data->fk_user_plan)) {
            return json_encode(array('code' => '-103', 'msg' => '获取排课教师失败!'));
        }
        //首次进入鲜花引导
        $courseId = $teacherId = $coursePlan->data->fk_course;
        $param = array('fk_course'=>$courseId,'fk_user'=>$uid);
        //判断报名
        $signUp = course_note_api::studentSinUp($param);
        $giftList['giftGuide'] = 'false';
        if(!empty($signUp->items)){
            $userGuide = user_api::getUserGuideByUid($uid,14);
            if(!empty($userGuide)){
                $giftList['giftGuide'] = 'false';
            }else{
                $giftList['giftGuide'] = 'true';
                $guideArr=array(
                    'uid'=>$uid,
                    'gid'=>14,  //鲜花引导
                );
                //添加不需要返回值
                user_api::addUserGuide($guideArr);
            }
        }
        return json_encode(array('code' => '0', 'result' => $giftList));
    }


    /*
     * 判断是否可以领取鲜花
     * @params planId
     * @params uId
     * @link https://wiki.gn100.com/docs:api:livecoursegetgift
     * */
    public function pageaddPlanFlower(){
        $uId = !empty($this->user['uid'])?$this->user['uid']:'';
        $planId = !empty($_POST['planId'])?$_POST['planId']:''; //123
        $courseId = !empty($_POST['courseId'])?$_POST['courseId']:'';
        if(empty($uId)){
            return json_encode(array('code' => '-101', 'msg' => '用户ID为空'));
        }
        if(empty($planId)){
            return json_encode(array('code' => '-102', 'msg' => '排课ID为空'));
        }
        $param = array('fk_course'=>$courseId,'fk_user'=>$uId);
        //判断报名
        $signUp = course_note_api::studentSinUp($param);
        if(empty($signUp)){
            return json_encode(array('code'=>'-103','msg'=>'未报名此课程','status'=>'false'));
        }
            if($signUp->items[0]->source == 2){
                //查询会员类型
                $numberType =  org_member_api::getValidMemberListByUid($uId);
                if(!empty($numberType)){
                    $number = array();
                    foreach($numberType as $val){
                        $number[] = $val->fk_member_set;
                    }
                    //会员是否过期
                    $isNumber = org_member_api::checkIsMemberOrExpire($uId, $number, $courseId);
                    if(empty($isNumber)){
                        return json_encode(array('code'=>'-104','msg'=>'会员失效','status'=>'false'));
                    }
                }
            }
        $gift = user_const::FLOWER;//鲜花
        $type = 2;//直播课领取
        $getFlower = user_gift_api::getPlanFlower($uId,$planId,$gift,$type);
        //有记录返回不能领取
        if(!empty($getFlower->result->items[0])){
            return json_encode(array('code'=>'-103','msg'=>'超时不能领取','status'=>'false'));
        }
        $params = array(
            'fk_user'=>$uId,
            'fk_gift'=>$gift,
            'gift_count'=>0,
            'type'=>$type,//直播课领取
            'create_time'=>date('Y-m-d H:i:s',time()),
            'object_id'=>$planId
        );
        $getFlower = user_gift_api::addPlanFlower($params);
        if($getFlower->code ==0){
            return json_encode(array('code'=>'0','msg'=>'success','status'=>'true'));
        }else{
            return json_encode(array('code'=>'-104','msg'=>'操作失败'));
        }
    }


    /*
     * 学生直播课领取鲜花
     * 1修改鲜花领取数 2 修改用户鲜花总数
     * @params planId
     * @params uId
     * @link https://wiki.gn100.com/docs:api:livecoursegetgift
     * */
    public function pagegetFlowers(){
        $uId = !empty($this->user['uid'])?$this->user['uid']:'';
        $planId = !empty($_POST['planId'])?$_POST['planId']:'';
        if(empty($uId)){
            return json_encode(array('code' => '-101', 'msg' => '用户ID为空'));
        }
        if(empty($planId)){
            return json_encode(array('code' => '-102', 'msg' => '排课ID为空'));
        }
        $giftId = user_const::FLOWER;//鲜花
        $type = 2;//直播课领取
        $params = array(
            'fk_user'=>$uId,
            'fk_gift'=>$giftId,
            'gift_count'=>1,
            'type'=>$type,//直播课领取
            'object_id'=>$planId
        );
        //判断不为空 &&  超过五分钟
        $getFlower = user_gift_api::getPlanFlower($uId,$planId,$giftId,$type);
        if(!empty($getFlower->result->items[0])){
            if($getFlower->result->items[0]->gift_count == 1){
                return json_encode(array('code'=>'-103','msg'=>'不能重复领取'));
            }
        }
        //修改鲜花领取数
        $getFlower = user_gift_api::updatePlanFlowerNum($params);
        //2 用户鲜花总数+1
        $giftNum = 1;
        $flowerNum = user_gift_api::updateUserFlowerNumByUserIdAndGiftIdAndGiftNum($uId,$giftId,$giftNum);
        if($getFlower->code == 0 && $flowerNum->code == 0){
            return json_encode(array('code'=>'0','msg'=>'领取成功'));
        }else{
            return json_encode(array('code'=>'-104','msg'=>'领取失败'));
        }
    }

    /*
     * 签到获取鲜花
     * @params $level 用户等级
     * @params $uId   用户ID
     * @link https://wiki.gn100.com/docs:api:signupgetgift
     * return array
     * */
    public function pagegetGiftSign(){
        $level = !empty($_POST['level'])?$_POST['level']:'';
        $uId = !empty($this->user['uid'])?$this->user['uid']:'';
        if(empty($uId)){
            return json_encode(array('code'=>'-101','msg'=>'用户ID不能为空!'));
        }
        if(empty($level)){
            return json_encode(array('code'=>'-102','msg'=>'等级不能为空!'));
        }
        //获取不同等级领取鲜花数
        $conf = SConfig::getConfig(ROOT_CONFIG."/gift.flowerGet.conf","gift");
        $name = "level$level";
        $addGift = $conf->$name->flowerNum;
        //判断是否领取
        $giftId = user_const::FLOWER; //鲜花
        $type = 1;   //签到领取
        $object = 0; //签到默认0
        //判断当天是否领取
        $getFlower = user_gift_api::getPlanFlower($uId,$object,$giftId,$type);
        if(!empty($getFlower->result->items)){
            return json_encode(array('code'=>'-103','msg'=>'已经领取'));
        }
        $params = array(
            'fk_user'=>$uId,
            'fk_gift'=>$giftId,
            'gift_count'=>$addGift,
            'type'=>$type,//签到领取
            'create_time'=>date('Y-m-d H:i:s',time()),
            'object_id'=>0
        );
        //添加签到记录
        $getFlowerSign = user_gift_api::addPlanFlower($params);
        //用户鲜花总数+$addGift
        $flowerNum = user_gift_api::updateUserFlowerNumByUserIdAndGiftIdAndGiftNum($uId,$giftId,$addGift);
        if($flowerNum->code == 0 && $getFlowerSign->code == 0){
            return json_encode(array('code'=>'0','msg'=>'领取成功','giftNum'=>$addGift));
        }else{
            return json_encode(array('code'=>'-104','msg'=>'领取失败'));
        }
    }


    /*
     * 教师 学生 中心获取鲜花总数
     * @params uId 用户ID
     * @params type 1 学生 2 教师
     * @link https://wiki.gn100.com/docs:api:studentorteachergiftsum
     * */
    public function pagegetStudentOrTeacherGiftSum(){
        $uId = !empty($_POST['uId'])?$_POST['uId']:(!empty($this->user['uid'])?$this->user['uid']:'');
        $type = !empty($_POST['type'])?$_POST['type']:'';//2
        if(empty($uId)){
            return (array('code'=>'-101','msg'=>'用户ID不能为空'));
        }
        if(empty($type)){
            return (array('code'=>'-102','msg'=>'类型不能为空'));
        }
        if($type == 1){
            //获取学生鲜花总数 1鲜花
            $studentGift = user_gift_api::getUserGiftNum($uId,user_const::FLOWER);
            $gift_count = 0;
            if(!empty($studentGift->result->items)){
                $gift_count = isset($studentGift->result->items[0]->gift_count)?$studentGift->result->items[0]->gift_count:0;
            }
            return (array('code'=>'0','msg'=>'请求成功','gift_count'=>$gift_count));
        }elseif($type == 2){
            //获取教师鲜花总数  1鲜花
            $teacherGiftSum = user_gift_api::getTeacherGiftSum($uId,user_const::FLOWER);
            $gift_count = 0;
            if(!empty($teacherGiftSum->result->items)){
                $gift_count = isset($teacherGiftSum->result->items[0]->gift_count)?$teacherGiftSum->result->items[0]->gift_count:0;
            }
            return (array('code'=>'0','msg'=>'请求成功','gift_count'=>$gift_count));
        }else{
            return (array('code'=>'-103','msg'=>'类型不正确'));
        }
    }
}
