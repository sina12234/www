<?php
class course_group_api{
	
    /* 
     * 获取班级分组列表  cid=3559&classid=1499&rtime=0&guser=1
     * $params : cid=3559
     * $params : classid=1499
     * $params : rtime=0
     * $params : guser=1
     */
    public static function getCourseClassGroupList($params){
        $ret = utility_services::call("/course/coursegroup/getCourseClassGroupList", $params);

        return $ret;
    }    
    
    /* 保存班级分组 */
    public static function addCourseClassGroup($params){
        $ret = utility_services::call("/course/coursegroup/AddCourseClassGroup", $params);

        return $ret;
    }
    
    /* 批量保存班级分组 
     $params {"data":[{"pid":4,"cid":3559,"classid":"1499","gname":"0A组","tid":"2932"},{"cid":3559,"classid":"1499","gname":"0B组","tid":"2932"}]}
    */
    public static function saveCourseClassGroup($params){
        $ret = utility_services::call("/course/coursegroup/SaveCourseClassGroup", $params);

        return $ret;
    }
    
    /* 修改班级分组 & 删除班级分组 */
    public static function updateCourseClassGroup($params){
        $ret = utility_services::call("/course/coursegroup/UpdateCourseClassGroup", $params);

        return $ret;
    }
	
    /* 获取班级分组下学生列表 */
    public static function getCourseClassGroupUserList($params){
        $ret = utility_services::call("/course/coursegroup/getCourseClassGroupUserList", $params);

        return $ret;
    }   
}
