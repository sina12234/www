<?php
class org_class{

    public function __construct(){

    }

    /*
     * @desc 班级下排课列表
     * @param $inPath[3] 课程id
     * @param $inPath[4] 班级id
     * @author zhengtianlong 2016-11-21
     */
    public function pagePlanList($inPath){
        $courseId = !empty($inPath[3]) ? (int)$inPath[3] : 0;
        $classReg = course_class_api::getClassListByCourseId($courseId);



        echo '<pre>';
        print_r($res);
    }
}
?>