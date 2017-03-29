<?php

class org_stat_api
{
    public static function getOrgStat($params){
        $ret = utility_services::call("/stat/orgstat/getOrgStat",$params);
        return $ret;
    }

    public static function getDayOrgStatByOwnerid($params){
        $ret = utility_services::call("/stat/dayuserorgstat/GetDayOrgStatByOwnerid",$params);
        return $ret;
    }
    public static function getOrgOrderCountByDay($params){
        $ret = utility_services::call("/stat/dayuserorgstat/GetOrgOrderCountByDay",$params);
        return $ret;
    }
}
