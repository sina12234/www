<?php
/**
 * Created by PhpStorm.
 * User: longhouan
 * Date: 2017/3/8
 * Time: 13:16
 */
class course_distribute_api{
    public static function updateDistribute($courseId,$data){
        $ret = utility_services::call("/course/distribute/updateDistribute/$courseId", $data);
        return $ret;
    }
    public static function getDistributeCourseList($page, $length , $params = array()){
        $res = utility_services::call("/course/distribute/GetDistributeCourseList/".$page."/".$length,$params);
        if(!empty($res)){
            return $res;
        }else{
            return false;
        }
    }
    public static function addDistribute($params=array()){
        $ret = utility_services::call("/course/distribute/addDistribute",$params);
        if(!empty($ret)){
            return $ret;
        }
        return false;
    }
    /* 修改推广课程信息 */
    public static function EditDistributeCourse($courseID,$params=array()){
        $courseDistributeList = utility_services::call("/course/distribute/UpdateDistribute/{$courseID}",$params);
        if(!empty($courseDistributeList)){
            return $courseDistributeList;
        }else{
            return false;
        }
    }
    /* 删除推广 暂停推广 */
    public static function changeDistributeCourse($courseId,$params=array()){
        //$coursePromoteList = utility_services::call("/course/promote/SyncPromoteCourseStatusVer/{$op}",$params);
        $coursePromoteList = utility_services::call("/course/distribute/UpdateDistribute/{$courseId}",$params);
        if(!empty($coursePromoteList)){
            return $coursePromoteList;
        }else{
            return false;
        }
    }
}