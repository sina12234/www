<?php
class course_announcement_api{
    //添加公告
    public static function AnnouncementAdd($params){
        $ret = utility_services::call("/course/announcement/AnnouncementAdd",$params);
        return $ret;
    }

    //编辑公告
    public static function AnnouncementUpdate($params){
        $ret = utility_services::call("/course/announcement/AnnouncementUpdate",$params);
        return $ret;
    }

    //删除公告
    public static function AnnouncementDelete($params){
        $ret = utility_services::call("/course/announcement/AnnouncementDelete",$params);
        return $ret;
    }

    //获取公告
    public static function GetAnnouncement($params){
        $ret = utility_services::call("/course/announcement/GetAnnouncement",$params);
        return $ret;
    }
}
