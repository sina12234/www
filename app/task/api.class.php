<?php
/**
 * 作业api
 * @author zhouyu
 * date: 2016/7/6
 */
class task_api{

    //查询已发布课程 测试方法
    public static function selPublishTask(){
        $ret = utility_services::call("/task/publishTask/Test/");
        return $ret;
    }

    //教师发布课程 t_task
    public static function addPublishTask($params){
        $ret = utility_services::call("/task/publishTask/teacherPublishTask/",$params);
        return $ret;
    }
    //教师发布作业 同时添加图片
    public static  function addPublishTaskImg($imgs){
        $ret = utility_services::call("/task/publishTask/teacherUploadImg/",$imgs);
        return $ret;
    }
    //教师发布作业 同时添加附件
    public static function addPublishTaskAttach($attach){
        $ret = utility_services::call("/task/publishTask/teacherUploadAttach/",$attach);
        return $ret;
    }

    //教师发布作业查询标签
    public static function seltagTag($tag){
        $ret = utility_services::call("/task/publishTask/selTag/",$tag);
        return $ret;
    }

    //发布作业添加tag tag
    public static function addTagTag($tag){
        $ret = utility_services::call("/task/publishTask/InsertTag/",$tag);
        return $ret;
    }

    //发布作业添加tag_belong
    public static function addTagBelongGroup($param){
        $ret = utility_services::call("/task/publishTask/AddTagBelong/",$param);
        return $ret;
    }

    //教师发布作业 添加
    public static function addMappingTag($params){
        $ret = utility_services::call("/task/publishTask/AddMappingTag/",$params);
        return $ret;
    }


    //通过当前教师ID 查询所代课程
    public static function getTeacherCourse($userId){

        $ret = utility_services::call("/task/publishTask/getTeacherCourse/",$userId);
        return $ret;

    }

    // 课程ID 查询课程name  pageCourseOneName
    public static function getCourseOneName($courseId){
        $ret = utility_services::call("/task/publishTask/getCourseOneName/",$courseId);
        return $ret;
    }

    //通过课程Id查询对应班级
    public static function getCourseClass($courseId){
        $ret = utility_services::call("/task/publishTask/getCourseClass/",$courseId);
        return $ret;
    }

    //教师作业列表
    public static function getTaskList($pageNum){
        $ret = utility_services::call("/task/publishTask/getTaskList/",$pageNum);
        return $ret;
    }

    //待批改状态 教师查看作业详情
    public static function getTaskDetail($teacherId){
        $ret = utility_services::call("/task/publishTask/getTaskDetail/",$teacherId);
        return $ret;
    }
    //查看图片
    public static function getTaskDetailThumb($thumbCondition){
        $ret = utility_services::call("/task/publishTask/getTaskDetailThumb/",$thumbCondition);
        return $ret;
    }
    //查询附件
    public static function getTaskDetailAttach($attachCondition){
        $ret = utility_services::call("/task/publishTask/getTaskDetailAttach/",$attachCondition);
        return $ret;
    }

    //教师未发布修改作业
    public static function updatePublishTask($params){
        $ret = utility_services::call("/task/publishTask/updatePublishTask/",$params);
        return $ret;
    }
    //未发布 修改作业 修改图片
    public static function updatePublishTaskImg($imgCondition){
        $ret = utility_services::call("/task/publishTask/updatePublishTaskImg/",$imgCondition);
        return $ret;
    }
    //未发布 修改作业 修改附件
    public static function updatePublishTaskAttach($attachCondition){
        $ret = utility_services::call("/task/publishTask/updatePublishTaskAttach/",$attachCondition);
        return $ret;
    }
    //通过taskid 查询 tagid
    public static function getTaskDetailTag($tagCondition){
        $ret = utility_services::call("/task/publishTask/getTaskDetailTag/",$tagCondition);
        return $ret;
    }



    //学生提交作业
    public static function addStudentTask($params){
        $ret = utility_services::call("/task/replyTask/StudentReplyTask/",$params);
        return $ret;
    }
    //获取作业提交次数
    public static function getTaskCommitNum($param){
        $ret = utility_services::call("/task/replyTask/GetCommitNum/",$param);
        return $ret;
    }
    //修改学生提交次数
    public static function updateCommitCount($where){
        $ret = utility_services::call("/task/replyTask/UpdateCommitNum/",$where);
        return $ret;
    }
    //获取学生作业列表
    public static function getStudentTaskList($param){
        $ret = utility_services::call("/task/replyTask/getStudentTaskList/",$param);
        return $ret;
    }
    //获取教师名字
    public static function getTeacherName($teacherId){
        $ret = utility_services::call("/task/replyTask/getTeacherName/",$teacherId);
        return $ret;
    }
    //作业Id 查询未批改作业 t_task_student
    public static function getStudentTaskDetail($pk_task_student){
        $ret = utility_services::call("/task/replyTask/getStudentTaskDetail/",$pk_task_student);
        return $ret;
    }



    //教师批改作业
    public static function replyTask($param){
        $ret = utility_services::call("/task/correctTask/TeacherCorrectTask/",$param);
        return $ret;
    }
    //批改作业添加
    public static function addMappingStudentTag($param){
        $ret = utility_services::call("/task/publishTask/addMappingStudentTag/",$param);
        return $ret;
    }
    //修改 批改次数
    public static function updateReplyCount($where){
        $ret = utility_services::call("/task/correctTask/updateReplyCount/",$where);
        return $ret;
    }

    //通过作业ID 查询 所有学生 答题列表未批改
    public static function getStudentAllTask($pk_task){
        $ret = utility_services::call("/task/correctTask/getStudentAllTask/",$pk_task);
        return $ret;
    }
    //通过作业ID 查询 所有学生 答题列表已经批该
    public static function getStudentAllTaskAlealy($pk_task){
        $ret = utility_services::call("/task/publishTask/getStudentAllTaskAlealy/",$pk_task);
        return $ret;
    }
    //通过作业ID 查询 所有学生 答题列表已经批该
    public static function getStudentAllTaskAlealyNew($pk_task){
        $ret = utility_services::call("/task/publishTask/getStudentAllTaskAlealyNew/",$pk_task);
        return $ret;
    }

    //批改作业修改 updateTaskStudentStatus($statusWhere)
    public static function updateTaskStudentStatus($statusWhere){
        $ret = utility_services::call("/task/correctTask/updateTaskStudentStatus/",$statusWhere);
        return $ret;
    }

    //通过t_task_student id查询标签 t_mapping_tag_task_student
    public static function replyTaskTag($statusWhere){
        $ret = utility_services::call("/task/publishTask/replyTaskTag/",$statusWhere);
        return $ret;
    }

    public  static function  getdelTask($param){
        $ret = utility_services::call("/task/publishTask/getdelTask/",$param);
        return $ret;
    }
    //删除提交作业表
    public static function getdelCommitTask($pk_task){
        $ret = utility_services::call("/task/publishTask/getdelCommitTask/",$pk_task);
        return $ret;
    }
    //查询批改作业表
    public static function getdelReplyTask($pk_task){
        $ret = utility_services::call("/task/correctTask/getdelReplyTask/",$pk_task);
        return $ret;
    }

    public static function getClassInfo($param){
        $ret = utility_services::call("/task/publishTask/getClassInfo/",$param);
        return $ret;
    }

    //催交作业
    public static function taskMessage($param){
        $ret = utility_services::call("/message/dialog/Add/",$param);
        return $ret;
    }

    //教师作业列表 是否提示
    public static function getTaskListIsPrompt($userId){
        $ret = utility_services::call("/task/publishTask/getTaskListIsPrompt/",$userId);
        return $ret;
    }


    //批改作业消息发送
    public static function replyTaskMessage($message){
        $ret = utility_services::call("/message/replyTaskMessage/repyTaskMessage/",$message);
        return $ret;
    }

    //删除图片
    public static function delImage($param){
        $ret = utility_services::call("/task/publishTask/delImage/",$param);
        return $ret;
    }

    //删除附件
    public static function delAttach($param){
        $ret = utility_services::call("/task/publishTask/delAttach/",$param);
        return $ret;
    }
    //批量删除图片
    public static function batchDelImage($param){
        $ret = utility_services::call("/task/publishTask/BatchDelImage/",$param);
        return $ret;
    }

    //批量删除附件
    public static function batchDelAttach($param){
        $ret = utility_services::call("/task/publishTask/BatchDelAttach/",$param);
        return $ret;
    }

    //删除标签
    public static function DelTag($tagId){
        $ret = utility_services::call("/task/publishTask/DelTag/",$tagId);
        return $ret;
    }

    //查询提交作业表
    public static function taskStudent($param){
        $ret = utility_services::call("/task/publishTask/taskStudent/",$param);
        return $ret;
    }

    //查询提交中作业学生
    public static  function selStudentInfo($param){
        $ret = utility_services::call("/task/publishTask/selStudentInfo/",$param);
        return $ret;
    }

    //查询排课
    public static  function getCoursePlan($param){
        $ret = utility_services::call("/course/courseplan/getCoursePlan/",$param);
        return $ret;
    }

    //学生未完成的作业数
    public static  function unfinishNumByUserId($studentId){
        $studentId = !empty($studentId) ? $studentId : 0;
        $ret = utility_services::call("/task/replyTask/unfinishNumByUserId/".$studentId);
        if(empty($ret->code)){
            return $ret->result->num;
        }
        return "0";
    }


    //添加语音
    public static function addVoice($param){
        $ret = utility_services::call("/task/publishTask/addVoice/",$param);
        return $ret;
    }

    //获取语音
    public static function getVoiceByVoiceId($fkThumb,$type)
    {
        $ret = utility_services::call("/task/publishTask/getVoiceByVoiceId/$fkThumb/$type");
        return $ret;
    }
    /**
     * 老师待批改的作业
     * @param int $teacherId
     * @return 
     */
    public static function teacherWaitTask($teacherId){
        if(empty($teacherId)) return false;
        $ret = utility_services::call("/task/correctTask/waitTask/".$teacherId);
        if(empty($ret->code)){
            return $ret->result;
        }
        return false;
    }

    //删除批改作业标签
    public static function delReplyTaskTagByPkTaskStudentId($pkTaskStudent){
        $ret = utility_services::call("/task/replyTask/delReplyTaskTagByPkTaskStudentId/$pkTaskStudent");
        return $ret;
    }
}
