<?php

class common_api{
    public static function getIdStr($param){
        return utility_services::call('/common/list/getIdStr', $param);
    }

    /**
     * @desc  友盟消息推送入库
     * @param array
     * @retrun boole
     */
    public static function addYmMessage($params){
        if(empty($params)) return false;
        
        $res = utility_services::call('/ymeng/message/Add ', $params);
        if(empty($res->code)){
            return true;
        }

        return false;
    }

}
