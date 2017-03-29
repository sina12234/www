<?php
/**
 * Created by PhpStorm.
 * User: longhouan
 * Date: 2016/11/22
 * Time: 11:01
 */
class course_teacher_api{
    public static function getTeacherByCourseId($courseId,$page=1, $length=0){
        $res = utility_services::call('/course/teacher/getTeacherByCourseId/'.$courseId.'');
        return $res;
    }
}