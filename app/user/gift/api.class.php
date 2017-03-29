<?php
class user_gift_api{

    //获取礼物
    public static function getGiftListByUserId($uId){
        $ret = utility_services::call("/user/gift/getGiftListByUserId/$uId");
        return $ret;
    }

    //教师获取礼物
    public static function  getGiftTeacherListByUserId($teacherId){
        $ret = utility_services::call("/user/gift/getGiftTeacherListByUserId/$teacherId");
        return $ret;
    }

    /*
     * 获取教师鲜花总数
     * */
    public static function getTeacherGiftSum($teacherId,$giftId){
        $ret = utility_services::call("/user/gift/getTeacherGiftSum/$teacherId/$giftId");
        return $ret;
    }

    /*
     * PC客户端获取礼物  区分老师和学生
     * @params fk_plan
     * @params fk_user_teacher
     * @params fk_gift
     * @return array
     * */
    public static function getPlanGiftSum($params){
        $ret = utility_services::call("/user/gift/getPlanGiftSum",$params);
        return $ret;
    }

    public static function getPlanFlower($uId,$planId,$gift,$type){
        $ret = utility_services::call("/user/gift/getPlanFlower/$uId/$planId/$gift/$type");
        return $ret;
    }

    public static function addPlanFlower($params){
        $ret = utility_services::call("/user/gift/addPlanFlower",$params);
        return $ret;
    }

    public static function updatePlanFlowerNum($params){
        $ret = utility_services::call("/user/gift/updatePlanFlowerNum",$params);
        return $ret;
    }

    public static function updateUserFlowerNumByUserIdAndGiftIdAndGiftNum($uId,$giftId,$giftNum){
        $ret = utility_services::call("/user/gift/updateUserFlowerNumByUserIdAndGiftIdAndGiftNum/$uId/$giftId/$giftNum");
        return $ret;
    }

    public static function getUserGiftNum($uId,$giftId){
        $ret = utility_services::call("/user/gift/getUserFlower/$uId/$giftId");
        return $ret;
    }

    //礼物数量相减
    public static function giftSubtractByUIdAndGIdAndGiftCount($uId,$gId,$gCount){
        if ( empty($uId) || empty($gCount) || empty($gId) )  return false;
        $ret = utility_services::call("/user/gift/giftSubtractByUIdAndGIdAndGiftCount/$uId/$gId/$gCount");
        return $ret;
    }

    //红名卡使用
    public static function giftUseLog($params){
        $ret = utility_services::call("/user/gift/studentGiveGift",$params);
        return $ret;
    }
}