<?php
/**
 * Created by PhpStorm.
 * User: longhouan
 * Date: 2016/11/16
 * Time: 16:10
 */
class user_organizationStudent_api{
    public static function addOrganizationStudent($params){
        $ret = utility_services::call("/user/organizationStudent/add",$params);
        return $ret;
        if(!empty($ret->result)){
            return $ret->result;
        }else{
            return false;
        }
    }
}