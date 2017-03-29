<?php
class course_note_api{

    /*
     * 笔记添加  cid=3559&classid=1499&rtime=0&guser=1
     * $params : cid=3559
     * $params : classid=1499
     * $params : rtime=0
     * $params : guser=1
     */
    //添加笔记
    public static function noteAdd($params){
        $ret = utility_services::call("/course/note/noteAdd",$params);
        return $ret;
    }
    //查询笔记数量
    public static function noteCount($params){
        $ret = utility_services::call("/course/note/noteCount",$params);
        return $ret;
    }

    //查询报名信息
    public static function getCourseUser($params){
        $ret = utility_services::call("/course/note/getCourseUser",$params);
        return $ret;
    }

    //删除笔记
    public static function DelNote($params){
        $ret = utility_services::call("/course/note/DelNote",$params);
        return $ret;
    }
    //编辑笔记
    public static function UpdateNote($params){
        $ret = utility_services::call("/course/note/UpdateNote",$params);
        return $ret;
    }

    //笔记列表
    public static function noteList($params){
        $ret = utility_services::call("/course/note/noteList",$params);
        return $ret;
    }

    //判断学生是否报名
    public static function studentSinUp($params){
        $ret = utility_services::call("/course/note/studentSinUp",$params);
        return $ret;
    }
    

}
