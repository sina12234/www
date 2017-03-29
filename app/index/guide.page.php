<?php
/*
  *平台引导
  *平台完善信息   1
  *平台用户中心   2
  *机构引导
  *笔记  12        //需报名
  *礼物 14         //需报名
  *机构引入分销课程 11
  *播放页分享引导 （数据库不进行记录）
  *机构引导 5 （页面无效果）
  *机构用户学习中心引导 3 （页面无效果）
 * */
class index_guide extends STpl{
    var $user;
    function __construct(){
        $this->user = user_api::loginedUser();
    }

    /*
     * 若需要判断报名 传值PlanId
     * @param $planId 排课ID
     * @param $guideIdArr 礼物ID
     * return array
     * */
    public function pageGuide(){
        $uId = $this->user['uid'];
        $guideIdArr = !empty($_POST['guide']) ? $_POST['guide'] : ''; //礼物Id
        $planId = !empty($_POST['planId']) ? $_POST['planId'] : '';
        if(empty($uId)) return json_encode(array('code'=>-101,'msg'=>'用户ID为空'));
        if(empty($guideIdArr)) return json_encode(array('code'=>-102,'msg'=>'引导类型为空'));
        $guideArr = array();
        //查询平台用户学习中西引导信息
        //$guideIdArr = [1,22222];
        $brr = array_flip($guideIdArr);
        $userGuide = user_api::getUserGuideByUidAndArrGuideId($uId,$guideIdArr);
        //print_r($userGuide);die;
        //没有引导记录直接返回
        if(empty($userGuide->items)){
            foreach($brr as $k => $v){
                $guideArr[$k] = 'true';
            }
            //特殊引导处理 需要报名后提示
            if(!empty($planId)){
                $guideArr = $this->guideHandle($guideArr,$planId,$uId);
            }
            //未报名不进行入库操作
            $signGuide = array();
            foreach($guideArr as $k => $v){
                if($v == 'true'){
                    $signGuide[$k] = $v;
                }
            }
            //添加引导记录 只添加显示引导的数据
            $this->addGuide($signGuide,$uId);
            return json_encode(array('code'=>0,'msg'=>'success','data'=>$guideArr));
        }

        //对引导记录进行处理
        foreach($userGuide->items as $key => $val){
            if(in_array($val->fk_guide,$guideIdArr)){
                $guideArr[$val->fk_guide] = 'false';
                unset($brr[$val->fk_guide]);
            }
        }
        //显示引导
        foreach($brr as $brrKey => $brrVal){
            $guideArr[$brrKey] = 'true';
        }
        //特殊引导处理 需要报名后提示
        if(!empty($planId)){
            $guideArr = $this->guideHandle($guideArr,$planId,$uId);
        }
        //添加引导记录
        if(!empty($brr)){
            $this->addGuide($brr,$uId);
        }
        return json_encode(array('code'=>0,'msg'=>'success','data'=>$guideArr));
    }

    /*
     * 引导
     * 判断是否报名
     * @param array $guideArr
     * @param int   $planId
     * @param int   $uId
     * return array
     * */
    private function guideHandle($guideArr,$planId,$uId){
        //guide->12 笔记
        //guide->14 礼物
        if(!empty($guideArr['12']) || !empty($guideArr['14'])){
            if(empty($planId)) {echo json_encode(array('code'=>-103,'msg'=>'排课ID为空'));exit;}
            $planInfo = course_api::listPlan(array("plan_id" => $planId, "allcourse" => true));
            if(!empty($planInfo->data)){
                $courseId = $planInfo->data[0]->course_id;
                $param = array('fk_course'=>$courseId,'fk_user'=>$uId);
                $signUp = course_note_api::studentSinUp($param);
                if(empty($signUp->items)){
                    $guideArr['12'] = 'false';
                    $guideArr['14'] = 'false';
                }
            }
        }
        return $guideArr;
    }


    //添加引导
    private function addGuide($guideArr,$uId){
        if(!empty($guideArr)){
            foreach($guideArr as $key => $val){
                $params=array(
                    'uid'=>$uId,
                    'gid'=>$key,
                );
                //添加不需要返回值
                user_api::addUserGuide($params);
            }
        }
    }


}