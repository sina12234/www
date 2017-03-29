<?php
/**
 * 教师发布作业
 * @author zhouyu
 * date: 2016/7/6
 */
class task_replyTask extends STpl
{
    private static $domain;
    private static $org;
    private static $orgInfo;
    private static $orgOwner;

    function __construct()
    {
        $this->user = user_api::loginedUser();

        if(empty( $this->user)){

            $this->redirect("/site.main.login");
        }
        $domain_conf = SConfig::getConfig(ROOT_CONFIG . "/const.conf", "domain");
        if (empty(self::$domain)) {
            self::$domain = $domain_conf->domain;
        }
        if (empty(self::$org)) {
            self::$org = user_organization::subdomain();
            if (empty(self::$org)) {
                header('Location: https://www.' . self::$domain);
            }
            self::$orgOwner = self::$org->userId; //机构所有者id 以后会根据域名而列取
            self::$orgInfo = user_organization::getOrgByOwner(self::$orgOwner);

        }
        //$this->assign('domain', self::$domain);
    }

    public function pagereplyTask(){
        //权限
        //$this->Power(self::$orgInfo->oid,$this->user['uid']);
        $userId = $this->user['uid'];
        if(empty($userId)){
            //跳转登录
            header('Location: https://www.'.self::$domain);
        }
        $special = user_api::getTeacherSpecial(self::$orgInfo->oid,$userId);
        //学生账号 没有此权限
        if(empty($special)){
            return $this->redirect("/site.main.entry");
        }
        $this->render("task/correct.task.html");
    }
    //批改作业展示页面
    public function pagereplyTaskShow(){
        //权限
        //$this->Power(self::$orgInfo->oid,$this->user['uid']);
        //通过作业ID 查询 所有学生 答题列表  未批改 取第一条 如果没有
        //教师ID $this->user
        $ret = new stdclass;
        $ret->result =  new stdclass;
        $teacherid = $this->user['uid'];
        if(empty($_POST['fk_task'])){
            $ret->result->code = -1;
            $ret->result->msg = "参数错误";
            return $ret;
        }
        $param = array('pk_task'=>$_POST['fk_task']);
        $taskDetail = task_api::getTaskDetail($param);
        if(empty($taskDetail->result->data)){
            $ret->result->code = -101;
            $ret->result->msg = "notFount";
            return $ret;exit;
            //return $this->render("task/task.notFount.html");
        }
        //学生ID 如果有学生ID 查询学生  教师详情点击学生进入
        //如果没有学生ID 为批改下一份作业
        $fk_user_student = !empty($_POST['fk_user_student'])?$_POST['fk_user_student']:'';
        if(!empty($fk_user_student)){
            $pk_task = array('fk_task'=>$_POST['fk_task'],'status'=>1,'fk_user_student'=>$fk_user_student);
            $selTaskList =  task_api::getStudentAllTask($pk_task);
        }else{
            $pk_task = array('fk_task'=>$_POST['fk_task'],'status'=>1);
            $selStudentInfo = task_api::selStudentInfo($pk_task);
            if(!empty($selStudentInfo->items)){
                $selTaskList = new stdclass;
                $selTaskList->data =  new stdclass;
                $selTaskList->data = $selStudentInfo;
            }else{
                $ret->result->code = -104;
                $ret->result->msg = "没有作业可批改了!";
                return $ret;
            }
        }


        if(!empty($selTaskList->data->items[0])){
            $pk_task_student = array('pk_task_student'=>$selTaskList->data->items[0]->pk_task_student,'status'=>1);
            $task = task_api::getStudentTaskDetail($pk_task_student);
            if(!empty($task->data->items)){
                $newList['data'] = $task->data->items[0];
                //查询图片
                $thumbCondition =  $param = array('object_id'=>$task->data->items[0]->pk_task_student,'object_type'=>2,'status'=>1);
                $taskDetailThumb = task_api::getTaskDetailThumb($thumbCondition);
                $newList['thumb'] = $taskDetailThumb->result->data;
                if(!empty($newList['thumb'])){
                    foreach($newList['thumb'] as $value){
                        $value->src_big = utility_cdn::file($value->thumb_big);
                        $value->src_mall = utility_cdn::file($value->thumb_small);
                    }
                }

                //查询附件
                $attachCondition =  $param = array('object_id'=>$task->data->items[0]->pk_task_student,'object_type'=>2,'status'=>1);
                $taskDetailAttach = task_api::getTaskDetailAttach($attachCondition);

                $newList['attach'] = $taskDetailAttach->result->data;
                if(!empty($newList['attach'])){
                    foreach($newList['attach'] as $value){
                        $value->src_attach = utility_cdn::file($value->file);
                    }
                }

                //教师发布标签
                //查询标签
                $attachCondition =  $param = array('fk_task'=>$_POST['fk_task']);
                $tag = task_api::getTaskDetailTag($attachCondition);
                if(!empty($tag->result->data)){
                    for($i=0;$i<count($tag->result->data);$i++){
                        //查询标签名字
                        $tagName = array('pk_tag'=>$tag->result->data[$i]->fk_tag);
                        $name = task_api::seltagTag($tagName);
                        if(!empty($name->data)){
                            $newName[$i]['name'] = $name->data[0]->name;
                            $newName[$i]['pk_tag'] = $name->data[0]->pk_tag;
                            $newList['tag'] = $newName;
                        }

                    }

                }

                //查询作业信息   学生姓名
                $param = array('pk_task'=>$task->data->items[0]->fk_task);
                $taskDetail = task_api::getTaskDetail($param);
                if(!empty($taskDetail->result->data[0])){
                    $studentName = task_api::getTeacherName($task->data->items[0]->fk_user_student);
                    if(!empty($studentName->data[0])){
                        //班级 课程名称
                        $courseId = array('pk_course'=>$taskDetail->result->data[0]->fk_course);
                        $classId = array('pk_class'=>$taskDetail->result->data[0]->fk_class);
                        $courseInfo = task_api::getCourseOneName($courseId);
                        if(!empty($courseInfo->result)){
                            $classInfo = task_api::getClassInfo($classId);
                            if(!empty($classInfo->result->data->items)){
                                $taskInfo = array();
                                $taskInfo['className'] = $classInfo->result->data->items[0]->name;
                                $taskInfo['pk_class'] = $classInfo->result->data->items[0]->pk_class;
                                $taskInfo['pk_course'] = $courseInfo->result[0]->pk_course;
                                $taskInfo['title'] = $courseInfo->result[0]->title;
                                @$taskInfo['create_time']  = date("m月d日 H:i",strtotime($taskDetail->result->data[0]->start_time)); //教师发布时间
                                $taskInfo['start_time']  = date("m月d日 H:i",strtotime(substr($selTaskList->data->items[0]->create_time,0,16))); //学生提交
                                $taskInfo['end_time']  = date("m月d日 H:i",strtotime($taskDetail->result->data[0]->end_time));
                                $taskInfo['StudentName']  = !empty($studentName->data[0]->real_name)?$studentName->data[0]->real_name:(!empty($studentName->data[0]->name)?$studentName->data[0]->name:'未设置');
                                $taskInfo['StudentId']  = $studentName->data[0]->pk_user;
                                $newList['taskInfo'] = $taskInfo;
                            }
                        }
                    }
                }
                $ret->result->code = 200;
                $ret->result->msg = "success";
                $ret->data = $newList;
                return $ret;
            }else{
                $ret->result->code = -104;
                $ret->result->msg = "没有作业可批改了!";
                return $ret;
            }
        }else{
            $ret->result->code = -104;
            $ret->result->msg = "没有作业可批改了!";
            return $ret;exit;
        }

    }

    //教师批改作业 handle
    public function pageteacherReplyTask(){
        //权限
        $this->Power(self::$orgInfo->oid,$this->user['uid']);
        $result = new stdClass();
        $info['fk_task'] = !empty($_POST['fk_task'])?$_POST['fk_task']:'';
        $info['pk_task_student'] = !empty($_POST['pk_task_student'])?$_POST['pk_task_student']:'';
        $info['desc'] = !empty($_POST['desc'])?trim($_POST['desc']):'';
        $info['level'] = !empty($_POST['level'])?$_POST['level']:'';
        $info['taskImages'] =  !empty($_POST['taskImages'])?$_POST['taskImages']:'';
        $info['attachName'] = !empty($_POST['attachName'])?$_POST['attachName']:'';
        $info['attachType'] = !empty($_POST['attachType'])?$_POST['attachType']:'';
        $info['attachFile'] = !empty($_POST['attachFile']) ? $_POST['attachFile']:'';
        $info['tags'] = !empty($_POST['tags'])?$_POST['tags']:'';
        $info['pk_task_student'] = !empty($_POST['pk_task_student'])?$_POST['pk_task_student']:'';
        $this->checkParam($info);
        //通过作业ID 查看 待批改作业详情
        $param = array('pk_task'=>$info['fk_task']);
        $taskDetail = task_api::getTaskDetail($param);
        if(empty($taskDetail->result->data)){
            $result->code = -101;
            $result->msg = "notFount";
            return $result;
        }

        $pk_task = array('fk_task' => $taskDetail->result->data[0]->pk_task,'status'=>1,'pk_task_student'=> $info['pk_task_student'],'reply'=>'reply');
        $StudentList = task_api::selStudentInfo($pk_task);
        $selTaskList = new stdclass;
        $selTaskList->data =  new stdclass;
        $selTaskList->data = $StudentList;

        //查询批改作业表
        $where = array('fk_task_student'=>$selTaskList->data->items[0]->pk_task_student,'fk_task'=>$info['fk_task']);
        $replyTaskInfo =  task_api::getStudentAllTaskAlealy($where);
        if(!empty($replyTaskInfo->result->data[0])){
            $result->code = -105;
            $result->msg = "此作业已经批改!";
            return $result;
        }
        //批改作业添加 t_task_student_reply
        $params = array(
            'fk_task_student'=>$selTaskList->data->items[0]->pk_task_student,
            'fk_task'=>$info['fk_task'],
            'desc'=>$info['desc'],
            'status'=>'1',
            'level'=>$info['level'],
            'fk_user_teacher'=>$this->user['uid'],
        );
        $addReplyTask = task_api::replyTask($params);
        $nowtime = date('Y-m-d H:i:s',time());
        //图片
        $taskImages =$info['taskImages'];
        if(!empty($taskImages)){
            //$taskImages = explode(',',$taskImages);
            for($i=0;$i<count($taskImages);$i++){
                $width = !empty($taskImages[$i]["small_width"])?$taskImages[$i]["small_width"]:0;
                $height = !empty($taskImages[$i]["small_height"])?$taskImages[$i]["small_height"]:0;
                $imgCondition = array(
                    'thumb_big'=>$taskImages[$i]["big"],
                    'thumb_small'=>$taskImages[$i]["small"],
                    'small_width'=>$width,
                    'small_height'=>$height,
                    'status'=>'1', //正常
                    'createtime'=>$nowtime,
                    'object_id'=>$addReplyTask->data,
                    'object_type'=>3, //教师发布1 学生提交2 教师批改3
                );
                $addTaskImg = task_api::addPublishTaskImg($imgCondition);
            }
        }
        //附件
        //附件表
        $attachName = $info['attachName'];
        $attachType = $info['attachType'];
        $attachFile = $info['attachFile'];
        if(!empty($attachName[0]) && !empty($attachType[0]) && !empty($attachFile[0]) ){
            for($i=0;$i<count($attachName);$i++){
                $attachCondition = array(
                    'name'=>$attachName[$i],
                    'file'=>$attachFile[$i],
                    'status'=>'1', //正常
                    'type'=>$attachType[$i],
                    'create_time'=>$nowtime,
                    'object_id'=>$addReplyTask->data,
                    'object_type'=>3,
                );
                $addTaskAttach = task_api::addPublishTaskAttach($attachCondition);
            }
        }
        //标签
        /*  标签表 t_tag 库  t_course 库
        *  t_tag t_tag_belong_group
        *  t_mapping_tag_task   t_mapping_tag_task_student
        */
        //如果tag为空  t_tag tag_belong_group添加数据
        if(!empty($info['tags'])){
            $tags = $info['tags'];
            $tags = explode(',',$tags);
            for($i=0;$i<count($tags);$i++){
                //查询标签是否存在
                $new_param = array('name'=>$tags[$i]);
                $sel_tag = task_api::seltagTag($new_param);
                if(empty($sel_tag->data)){
                    $where = array(
                        'fk_user'=>$this->user['uid'],
                        'name'=>$tags[$i],
                        'desc'=>'',
                        'status'=>'0',
                    );
                    //添加 t_tag
                    $addTag = task_api::addTagTag($where);
                    $params = array(
                        'fk_tag'=>$addTag->data,
                        'fk_group'=>'4',
                        'status'=>'0',
                    );
                    //添加 t_tag_belong_group
                    $addTagBelong = task_api::addTagBelongGroup($params);
                    //添加c_ouurse  t_mapping_tag_task
                    $params = array(
                        'fk_group'=>'4',
                        'fk_tag'=>$addTag->data,
                        'fk_task_student'=>$selTaskList->data->items[0]->pk_task_student,
                        'status'=>'1',
                    );
                    $addMappingTag = task_api::addMappingStudentTag($params);
                }else{
                    //添加c_ouurse  t_mapping_tag_task
                    $params = array(
                        'fk_group'=>'4',
                        'fk_tag'=>$sel_tag->data[0]->pk_tag,
                        'fk_task_student'=>$selTaskList->data->items[0]->pk_task_student,
                        'status'=>'1',
                    );
                    $addMappingTag = task_api::addMappingStudentTag($params);

                }
            }
        }
        //修改批改数
        //修改作业表 提交数量
        $params = array('id'=>$info['fk_task']);
        $taskNum = task_api::getTaskCommitNum($params);
        //提交次数 +1
        $where = array(
            'mark_count'=>$taskNum->data->mark_count+1,
            'status'=>1,  //修改task 状态
            'pk_task'=>$info['fk_task']
        );
        $studentCount = task_api::updateReplyCount($where);

        //修改 t_task_student 状态
        $statusWhere = array(
            'status'=>2,  //修改task 状态
            'pk_task_student'=>$selTaskList->data->items[0]->pk_task_student
        );
        $upStatus = task_api::updateTaskStudentStatus($statusWhere);

        //批改作业后给学生发消息
        $courseId = $taskDetail->result->data[0]->fk_course;
        $userTeacher = $taskDetail->result->data[0]->fk_user_teacher;
        $startTime = $StudentList->items[0]->create_time;
        $endTime = $taskDetail->result->data[0]->end_time;
        //课程id查询课程名称
        $courseInfo = task_api::getCourseOneName(array('pk_course'=>$courseId));
        $courseName = '';
        if(!empty($courseInfo->result[0])){
            $courseName = $courseInfo->result[0]->title;
        }
        //查询教师信息
        $teacherName = '';
        $teacherInfo = task_api::getTeacherName($userTeacher);
        if(!empty($teacherInfo->data[0])){
            $teacherName = $teacherInfo->data[0]->real_name;
        }
        $subdomain = user_organization::getSubdomainByUid(self::$orgOwner);
        $subdomain = user_organization::course_domain($subdomain->subdomain);
        if(!empty($courseName) && !empty($teacherName)){                                                                                                                                               //?taskId=848&type=correct&studentId=111?fk_task_student=674
            $content = '【批改作业】老师已经批改了你的'.date('m-d H:i',strtotime($startTime)).'提交的['.$courseName.']的作业，快去看看批改结果吧~<a href="//'.$subdomain.'/task.publishTask.taskCorrectShow/?taskId='.$selTaskList->data->items[0]->fk_task.'&type=correct'.'&studentId='.$selTaskList->data->items[0]->pk_task_student.'" target="_blank">【去查看】</a>';
        }else{
            $content = "老师已经批改了你的作业,快去看看批改结果吧~";
        }

        $message = array(
            'msgType'=>'10021',       //type
            'userFrom'=>0,  //发送
            'userTo'=>$selTaskList->data->items[0]->fk_user_student,    //接受ssfgdd gf
            'content'=>$content,
            'title'=>$content,
        );
        $sendMessage = task_api::replyTaskMessage($message);

        //手机推送消息(云课2.0)
        $ymData = [
            "title"  => "批改作业",
            "text"   => $content,
            "to_uid" => $selTaskList->data->items[0]->fk_user_student,
            "organization" => 0,
            "message_type" => message_type::TEACHER_REPLY_TASK
        ];
        common_api::addYmMessage($ymData);

        $result->code = 200;
        $result->msg = "success";
        return $result;

    }

    public function checkParam($info){

        if( empty((int)$info['fk_task']) ){
            echo json_encode(array('code'=>-1,'msg'=>'作业ID为空'));exit;
        }
        if( empty(trim($info['level'])) ){
            echo json_encode(array('code'=>-4,'msg'=>'等级为空'));exit;
        }
        //附件 $attachName $attachType $attachFile
        //标签判断
//        if(empty($info['tags'])){
//            echo json_encode(array('code'=>-7,'msg'=>'标签为空'));exit;
//        }
//        $tags = explode(',',$info['tags']);
//        if(count($tags)  > 5 ){
//            echo json_encode(array('code'=>-8,'msg'=>'标签数最多为5'));exit;
//        }
//        for($i=0;$i<count($tags);$i++){
//            if (preg_match("/[\x7f-\xff]/", $tags[$i]))
//            {
//                //exit('有中文!');
//                if( mb_strlen($tags[$i]) > 60 ){
//                    echo json_encode(array('code'=>-9,'msg'=>'每个标签中文最多为20字符'));exit;
//                }
//            }else{
//                //exit('没有中文!');
//                if( strlen($tags[$i]) > 40){
//                    echo json_encode(array('code'=>-10,'msg'=>'每个标签数字英文最多为40字符'));exit;
//                }
//            }
//        }
    }

    //权限
    public function Power($owerId,$userId){
        //是否是机构下老师
        $special = user_api::getTeacherSpecial($owerId,$userId);
        //老师所代班级
        $params = array("user_id"=>$owerId,"user_class_id"=>$userId);
        $teacherClassList = course_api::classlistbycond($params);
        // 讲师1  助教2
        if(empty($special) ||  ($special->role != 1 && $special->status!=1) || empty($userId)){
            $this->render('task/task.notFount.html');
        }
    }



}
