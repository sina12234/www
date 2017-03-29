<?php

/**
 * 礼物客户端
 * @author zhouyu
 * date: 2016/12/06
 */
class interface_gift extends interface_base{
    /*
     * PC客户端获取礼物
     * @params fk_plan
     * @params fk_user_teacher
     * @params fk_gift
     * @return array
     * */
    function pagegetGiftList(){
        $teacherId = !empty($this->paramsInfo['params']['teacherId']) ? (int)$this->paramsInfo['params']['teacherId'] : '';
        $planId = !empty($this->paramsInfo['params']['fkPlan']) ? (int)$this->paramsInfo['params']['fkPlan'] : '';//12
        if (empty($teacherId) || empty($planId)) {
            return interface_func::setMsg(1001);
        }
        //礼物展示
//        $giftList = user_gift_api::getGiftTeacherListByUserId($teacherId);
//        $list['gift'] = array();
//        if (!empty($giftList->result->items)) {
//            $list['gift'] = $giftList->result->items;
//        }
        //当前排课礼物总数
        //区分老师和学生  老师端取 传教师ID
        $params = array(
            'fk_plan' => $planId,
            'fk_user_teacher' => $teacherId,
            'fk_gift' => 1, //1鲜花
        );
        $giftCount = user_gift_api::getPlanGiftSum($params);
        $list['giftSum'] = 0;
        if (!empty($giftCount->result->items[0]->gift_sum)) {
            $list['giftSum'] = $giftCount->result->items[0]->gift_sum;
        }
        return interface_func::setData($list);
    }
}