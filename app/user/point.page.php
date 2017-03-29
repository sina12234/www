<?php

/**
 * 用户积分成长体系 1 积分获取  2 积分兑换
 * @author zy
 * date: 2017/03/19
 */
class user_point extends STpl{

    var $user;

    function __construct(){
        //如果没有登陆到登陆界面
        $this->user = user_api::loginedUser();
    }

    /*
     * 添加积分经验值
     * */
    public function pageAddPoint(){
        $type = !empty($_POST['type']) ? $_POST['type'] : '';
        if (empty($type)) {
            return json_encode(array('code' => -101, 'msg' => '类型不能为空'));
        }
        if (empty($this->user['uid'])) {
            return json_encode(array('code' => -102, 'msg' => '用户ID不能为空'));
        }
        $result = user_api::changeUserLevelAndScore($this->user['uid'], $type);
        return $result;
    }

   /*
    * 积分兑换礼物规则
    * 礼物ID => 积分数量
    * */
    public function convertRule($gift){
        if ( empty( $gift ) ) return false;
        $arr = [
            '1'=>1, //鲜花 -> 1
            '2'=>50 //红名卡->50
        ];
        return $arr[$gift];
    }

    /*
     * 积分兑换鲜花   1 积分相减 2 鲜花数相加（回调golang清空鲜花数 更改ver版本号）  2 礼物log增加
     * 积分兑换红名卡 1 积分相减 2 红名卡数相加（暂不进行回调 和 更改ver版本号）  2 礼物log增加
     * @param $giftId 1 鲜花 2 红名卡
     * */
    public function pagePointConvertFlower(){

        $giftId = !empty($_POST['giftId']) ? $_POST['giftId'] : '';
        $giftNum =  !empty($_POST['giftNum']) ? $_POST['giftNum'] : '';
        if ( empty($giftId) || empty($giftNum) ) return json_encode(array('code' => -101, 'msg' => "参数错误"));
        if( !in_array($giftId,[1,2]) ) return json_encode(array('code' => -102, 'msg' => "此礼物不能兑换"));
        if (empty($this->user['uid'])) return json_encode(array('code' => -103, 'msg' => "未登录"));

        //获取礼物兑换积分数量
        $giftPoint = $this->convertRule($giftId);

        //判断当前积分是否可以兑换
        $userScorePoint = user_api::getUserScoreAndPoint($this->user['uid']);
        if (empty($userScorePoint->items[0]->point)) {
            return json_encode(array('code' => -104, 'msg' => "积分不够兑换","allPoint"=>0));
        }
        //判断相减积分
        if ($userScorePoint->items[0]->point < $giftPoint ) { return json_encode(array('code' => -104, 'msg' => "积分不够兑换","allPoint"=>$userScorePoint->items[0]->point)); }
        if($userScorePoint->items[0]->point < $giftPoint * $giftNum ){
            return json_encode(array('code' => -104, 'msg' => "积分不够兑换","allPoint"=>$userScorePoint->items[0]->point));
        }else{
            $point = $giftPoint * $giftNum; //兑换总积分 积分*数量
        }

        //积分相减
        user_api::scoreSubtractByUIdAndPoint($this->user['uid'], $point);
        //鲜花相加  用户ID 礼物ID 增加礼物数量
        user_gift_api::updateUserFlowerNumByUserIdAndGiftIdAndGiftNum($this->user['uid'], $giftId, $giftNum);
        //LOG增加 鲜花兑换记录
        $params = array(
            'fk_user' => $this->user['uid'],
            'fk_gift' => $giftId,
            'gift_count' => $giftNum, //兑换礼物数
            'type' => 3,//礼物兑换
            'create_time' => date('Y-m-d H:i:s', time()),
            'object_id' => 0
        );
        user_gift_api::addPlanFlower($params);
        return json_encode(array('code' => 200, 'msg' => "兑换成功","allPoint"=>$userScorePoint->items[0]->point - $point));
    }

    /*
     * 红名卡使用 1 用户礼物相减 2 使用日志记录 3 红名卡使用，过期状态添加
     * */
    public function pageRedCardUse(){
        $giftId = !empty($_POST['giftId']) ? $_POST['giftId'] : '';
        if (empty($giftId)) return json_encode(array('code' => -101, 'msg' => "参数错误"));
        if ($giftId != 2 ) return json_encode(array('code' => -101, 'msg' => "参数错误")); //目前只有红名卡可以使用
        if (empty($this->user['uid'])) return json_encode(array('code' => -102, 'msg' => "未登录"));

        //查询原有礼物数量
        $gift = user_api::getStudentGiftByUserId($this->user['uid']);
        if( empty($gift->result->items[1]->gift_count) ) return json_encode(array('code' => -103, 'msg' => "该学生没有红名卡",'cardNum'=>0));
        if( $gift->result->items[1]->gift_count < 1 ) return json_encode(array('code' => -103, 'msg' => "该学生没有红名卡",'cardNum'=>$gift->result->items[1]->gift_count));

        //礼物数相减 用户ID 礼物ID 礼物数量 [用户ID 礼物ID 相减数量]
        $giftSubtract = user_gift_api::giftSubtractByUIdAndGIdAndGiftCount($this->user['uid'],$giftId,1);

        //红名卡使用log   [giftId=>2 红名卡 , giftNum=>1 使用数量]
        $param = ["userId"=>$this->user['uid'], "giftId"=>$giftId, "giftNum"=>1];
        $giftUseLog = user_gift_api::giftUseLog($param);

        //红名截止时间查询
        $redCard = user_api::GetRedCardByUId($this->user['uid']);
        if( !empty($redCard->items[0]) ){
            if( $redCard->items[0]->status == 1 ){
                $stopTime =  $redCard->items[0]->stop_time + 3600*24*7;
                $status = false; //有红名且未过期 修改时间
            }
            if( $redCard->items[0]->status == -1 ){
                $stopTime =  time() + 3600*24*7;
                $status = true; //有红名且已经过期  修改时间 修改状态
            }
        }else{
            $stopTime = time() + 3600*24*7; //没红名  添加时间
            $status = false;
        }
        //红名卡截止时间添加
        $cardAdd = user_api::redNameUseByUIdAndStopTime($this->user['uid'],$stopTime,$status);
        $cardNum = $gift->result->items[1]->gift_count - 1;
        return json_encode(array('code' => 200, 'msg' => "使用成功",'cardNum'=>$cardNum));
    }


}
