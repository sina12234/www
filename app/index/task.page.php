<?php
/**
 * 学生提交作业
 * @author zhouyu
 * date: 2016/7/12
 */
class index_task extends STpl{
    private static $domain;
    private static $org;
    private static $orgInfo;
    private static $orgOwner;
    private static $pageSize = 50;
    function __construct(){
        $url = $_SERVER['REQUEST_URI'];
        $this->user = user_api::loginedUser();
        if(empty( $this->user)){
            $this->redirect("/index.main.login".'?url='."$url");
        }
    }

    //学生提交作业展示
    function pagestudentCommitTask(){
        $this->render("index/commit.task.html");
    }

    //展示
    public function pageupdateTaskShow(){
        $ret = new stdclass;
        $ret->result =  new stdclass;
        //通过作业ID 查看 待批改作业详情
        if(empty($_GET['pk_task']))
        {
            $ret->result->code = -1;
            $ret->result->msg = "参数错误";
            return $ret;
        }

        $param = array('pk_task'=>$_GET['pk_task']);
        $taskDetail = task_api::getTaskDetail($param);
        if(empty($taskDetail->result->data)){
            //此页面已经删除
            $ret->result->code = -102;
            $ret->result->msg = "delete";
            return $ret;exit;

            // return $this->render("task/task.delete.html");
        }

        if($taskDetail->result->data[0]->status == -1){
            //此页面已经删除
            $ret->result->code = -102;
            $ret->result->msg = "delete";
            return $ret;exit;
            // return $this->render("task/task.delete.html");
        }
        $act = !empty($_GET["act"])?$_GET["act"]:'';
        if($taskDetail->result->data[0]->status == 1&&empty($act)){
            //作业已经发布，不能修改
            echo json_encode(array('code' => -101, 'msg' => 'notFount'));exit;
            //return $this->render("task/task.notFount.html");
        }
        if(!empty($taskDetail->result->data)){
            $teacherInfo = task_api::getTeacherName($taskDetail->result->data[0]->fk_user_teacher);
            $newList['data'] = $taskDetail->result->data[0];
            $newList['data']->create_time = date('m月d日 H:i',strtotime($newList['data']->create_time));
            $newList['data']->end_time = date('Y-m-d H:i',strtotime($newList['data']->end_time));
            $newList['data']->teacherName = !empty($teacherInfo->data[0]->real_name)?$teacherInfo->data[0]->real_name:(!empty($teacherInfo->data[0]->name)?$teacherInfo->data[0]->name:"未设置");
            //查询图片
            $thumbCondition =  $param = array('object_id'=>$taskDetail->result->data[0]->pk_task,'object_type'=>1,'status'=>1);
            $taskDetailThumb = task_api::getTaskDetailThumb($thumbCondition);
            $newList['thumb'] = $taskDetailThumb->result->data;
            if(!empty($newList['thumb'])){
                foreach($newList['thumb'] as $value){
                    $value->src_big = utility_cdn::file($value->thumb_big);
                    $value->src_mall = utility_cdn::file($value->thumb_small);
                }
            }
            //查询附件
            $attachCondition =  $param = array('object_id'=>$taskDetail->result->data[0]->pk_task,'object_type'=>1,'status'=>1);
            $taskDetailAttach = task_api::getTaskDetailAttach($attachCondition);
            $newList['attach'] = $taskDetailAttach->result->data;
            if(!empty($newList['attach'])){
                foreach($newList['attach'] as $val){
                    $val->src_attach = utility_cdn::file($val->file);
                }
            }

            //查询标签
            $attachCondition =  $param = array('fk_task'=>$taskDetail->result->data[0]->pk_task);
            $tag = task_api::getTaskDetailTag($attachCondition);
            if(!empty($tag->result->data)){
                for($i=0;$i<count($tag->result->data);$i++){
                    //查询标签名字
                    $tagName = array('pk_tag'=>$tag->result->data[$i]->fk_tag);
                    $name = task_api::seltagTag($tagName);
                    if(!empty($name->data)){
                        $newName[$i]['name'] =$name->data[0]->name;
                        $newName[$i]['pk_tag'] = $name->data[0]->pk_tag;
                        $newList['tag'] = $newName;
                    }

                }

            }

            //查询班级  课程信息
            $courseId = array('pk_course'=>$taskDetail->result->data[0]->fk_course);
            $classId = array('pk_class'=>$taskDetail->result->data[0]->fk_class);
            $courseInfo = task_api::getCourseOneName($courseId);
            if(!empty($courseInfo->result)){
                $classInfo = task_api::getClassInfo($classId);
                if(!empty($classInfo->result->data->items)){
                    $infos = array();
                    $infos['name'] = $classInfo->result->data->items[0]->name;
                    $infos['pk_class'] = $classInfo->result->data->items[0]->pk_class;
                    $infos['pk_course'] = $courseInfo->result[0]->pk_course;
                    $infos['title'] = $courseInfo->result[0]->title;
                    $newList['info'] = $infos;
                }
            }
            $ret->result->code = 200;
            $ret->result->msg = "success";
            $ret->data = $newList;
            return $ret;
        }

    }



    //学生发布作业
    function pagecommitTask(){
        $ret = new stdclass;
        $ret->result =  new stdclass;
        $nowtime = date("Y-m-d H:i:s",time());
        $info['fk_task'] = !empty($_POST['fk_task']) ? $_POST['fk_task'] : '';
        $info['desc'] = !empty($_POST['desc']) ? trim($_POST['desc']) : '';
        $info['taskImages'] =  !empty($_POST['taskImages']) ? $_POST['taskImages'] : '';
        $info['attachName'] = !empty($_POST['attachName']) ? $_POST['attachName'] : '';
        $info['attachType'] = !empty($_POST['attachType']) ? $_POST['attachType'] : '';
        $info['attachFile'] = !empty($_POST['attachFile']) ? $_POST['attachFile'] : '';
        $info['tags'] = !empty($_POST['tags']) ? $_POST['tags'] : '';
        //判断是否已经提交
        $arr = array('fk_user_student'=>$this->user['uid'],'fk_task'=>$info['fk_task']);
        $taskInfo = task_api::taskStudent($arr);
        if($taskInfo->items[0]->status == 1 || $taskInfo->items[0]->status ==2 ){
            return json_encode(array('code'=>-103,'msg'=>'此作业已经提交！'));
        }

        $this->checkParam($info);
        if(empty($this->user['uid'])){
            //跳转登录
            header('Location: https://www.'.self::$domain);
        }

        //通过作业ID 查看 待批改作业详情
        $param = array('pk_task'=>$info['fk_task']);//$info['fk_task']
        $taskDetail = task_api::getTaskDetail($param);

        if(empty($taskDetail->result->data)){
            return json_encode(array('code'=>-10,'msg'=>'没有此作业'));
        }
        $params = array(
            'fk_task'=>$info['fk_task'],
            'fk_user_student'=>$this->user['uid'],
            'desc'=>$info['desc'],
            'create_time'=>$nowtime,
            'status'=>1,
        );
        //添加作业表t_task_student
        $addTask = task_api::addStudentTask($params);

        if(!empty($addTask->data)){
            //添加图片表
            //图片表
            $takImages =  $info['taskImages'];
            if(!empty($takImages)){
                for($i=0;$i<count($takImages);$i++){
                    $width = !empty($takImages[$i]['small_width'])?$takImages[$i]['small_width']:0;
                    $height = !empty($takImages[$i]['small_height'])?$takImages[$i]['small_height']:0;
                    $imgCondition = array(
                        'thumb_big'=>$takImages[$i]['big'],
                        'thumb_small'=>$takImages[$i]['small'],
                        'small_width'=>$width,
                        'small_height'=>$height,
                        'status'=>'1', //正常
                        'createtime'=>$nowtime,
                        'object_id'=>$taskInfo->items[0]->pk_task_student,
                        'object_type'=>2, //教师发布1 学生提交2 教师批改3
                    );
                    $addTaskImg = task_api::addPublishTaskImg($imgCondition);
                }
            }
            //添加附件表
            //附件表
            $attachName = $info['attachName'];
            $attachName = explode(',',$attachName);
            $attachType = $info['attachType'];
            $attachType = explode(',',$attachType);
            $attachFile = $info['attachFile'];
            $attachFile = explode('&',$attachFile);
            if(!empty($attachName) && !empty($attachType) && !empty($attachFile)){
                for($i=0;$i<count($attachName);$i++){
                    $attachCondition = array(
                        'name'=>$attachName[$i],
                        'file'=>$attachFile[$i],
                        'status'=>'1', //正常
                        'type'=>$attachType[$i],
                        'create_time'=>$nowtime,
                        'object_id'=>$taskInfo->items[0]->pk_task_student,
                        'object_type'=>2,//教师发布1 学生提交2 教师批改3
                    );
                    $addTaskAttach = task_api::addPublishTaskAttach($attachCondition);
                }
            }
        }

        //修改作业表 提交数量
        $params = array('id'=>$info['fk_task']);
        $taskNum = task_api::getTaskCommitNum($params);
        //提交次数 +1
        $where = array(
            'student_count'=>$taskNum->data->student_count+1,
            'pk_task'=>$info['fk_task']
        );
        $studentCount = task_api::updateCommitCount($where);
        $result = user_api::changeUserLevelAndScore($this->user['uid'],"COMMIT_TASK");
        return json_encode(array('code'=>200,'msg'=>'success','data'=>$result));

    }

    public function checkParam($info){
        if( empty((int)$info['fk_task']) ){
            echo json_encode(array('code'=>-1,'msg'=>'作业ID为空'));
        }
    }

    //批改结果展示
    public function pagetaskCorrectShow(){
        //权限
        //$this->Power(self::$orgInfo->oid,$this->user['uid']);
        $this->render("index/publish.look.result.html");
    }
    //教师已批改作业展示   学生已提交作业展示
    public function pagetaskShow(){

        //通过作业Id 进行查询数据
        //$taskId = $_GET['fk_task_student']; //提交作业ID
        //批改结果  教师批改 有标签 查询批改信息
        //$fk_task_student = $_GET['fk_task_student'];
        $ret = new stdclass;
        $ret->result =  new stdclass;
        if(empty($_GET['fk_task_student'])){
            $ret->result->code = -1;
            $ret->result->msg = "参数错误";
            return $ret;
        }

        //权限如果是学生登陆 判断改作业是否是该学生  老师跳过
        //是否是机构下老师
//        $special = user_api::getTeacherSpecial(self::$orgInfo->oid,$this->user['uid']);
//        // 讲师1  助教2
//        if(empty($special) || ( $special->role != 2 && $special->role != 1 && $special->status!=1 && empty($userId) ) ){
//            $pk_task_student_id = array('pk_task_student'=>$_GET['fk_task_student']);
//            $commitTaskList =  task_api::getStudentTaskDetail($pk_task_student_id);
//            if($commitTaskList->data->items[0]->fk_user_student !=$this->user['uid'] ){
//                $ret->result->code = -108;
//                $ret->result->msg = "不是此学生作业";
//                return $ret;exit;
//            }
//        }

        //print_R($commitTaskList);die;
        $fk_task_student = $_GET['fk_task_student'];
        $pk_task = array('fk_task_student'=>$fk_task_student);

        //查询批改作业表
        $replyTaskInfo =  task_api::getStudentAllTaskAlealy($pk_task);
        if(!empty($replyTaskInfo->result->data)){
            $newList['reply']['data'] = $replyTaskInfo->result->data[0];
            $stuReplyId = $replyTaskInfo->result->data[0]->pk_task_student_reply;
            //查询图片 学生提交
            $replythumbCondition = $param = array('object_id' => $stuReplyId,'object_type'=>3,'status'=>1);
            $replyTasklThumb = task_api::getTaskDetailThumb($replythumbCondition);
            $newList['reply']['thumb'] = $replyTasklThumb->result->data;
            if(!empty($newList['reply']['thumb'])){
                foreach($newList['reply']['thumb'] as $value){
                    $value->src_big = utility_cdn::file($value->thumb_big);
                    $value->src_mall = utility_cdn::file($value->thumb_small);
                }
            }
            //查询附件 学生提交
            $replyAttachCondition = $param = array('object_id' => $stuReplyId,'object_type'=>3,'status'=>1);
            $taskDetailAttach = task_api::getTaskDetailAttach($replyAttachCondition);
            $newList['reply']['attach'] = $taskDetailAttach->result->data;
            if(!empty($newList['reply']['attach'])){
                foreach($newList['reply']['attach'] as $value){
                    $value->src_attach = utility_cdn::file($value->file);
                }
            }
            //标签  查询批改标签
            //查询 t_task_student 取出ID 在查询标签
            // $selStudentTask =  task_api::getStudentAllTask($pk_task);
            //查询标签
            //print_r($replyTaskInfo->result->data);die;
            $pk_task_student_id = array('pk_task_student'=>$fk_task_student);
            $commitTaskList =  task_api::getStudentTaskDetail($pk_task_student_id);
            if(!empty($commitTaskList->data->items[0])) {
                $pk_task_student = $commitTaskList->data->items[0]->pk_task_student;
            }

            $where = array('fk_task_student'=>$pk_task_student);
            $replyTaskTag = task_api::replyTaskTag($where);
            if(!empty($replyTaskTag->result->data)){
                for($i=0;$i<count($replyTaskTag->result->data);$i++){
                    //查询标签名字
                    $tagName = array('pk_tag'=>$replyTaskTag->result->data[$i]->fk_tag);
                    $name = task_api::seltagTag($tagName);
                    if(!empty($name->data[0])){
                        $newName[$i]['name'] = $name->data[0]->name;
                        $newName[$i]['pk_tag'] = $name->data[0]->pk_tag;
                        $newList['reply']['tag'] = $newName;
                    }else{
                        $newList['reply']['tag'] = '';
                    }

                }

            }
        }

        //提交内容  学生提交  无标签 查询提交信息
        //查询提交作业表
        $pk_task_student_id = array('pk_task_student'=>$fk_task_student);
        $commitTaskList =  task_api::getStudentTaskDetail($pk_task_student_id);
        if(!empty($commitTaskList->data->items[0])) {
            $pk_task_student = $commitTaskList->data->items[0]->pk_task_student;
            //学生提交内容
            $newList['commit']['data'] = $commitTaskList->data->items[0];
            //查询图片 学生提交
            $commitThumbCondition = $param = array('object_id' => $pk_task_student,'object_type'=>2,'status'=>1);
            $taskDetailThumb = task_api::getTaskDetailThumb($commitThumbCondition);
            $newList['commit']['thumb'] = $taskDetailThumb->result->data;
            if(!empty( $newList['commit']['thumb'])){
                foreach( $newList['commit']['thumb'] as $value){
                    $value->src_big = utility_cdn::file($value->thumb_big);
                    $value->src_mall = utility_cdn::file($value->thumb_small);
                }
            }
            //查询附件 学生提交
            $commitAttachCondition = $param = array('object_id' => $pk_task_student,'object_type'=>2,'status'=>1);
            $taskDetailAttach = task_api::getTaskDetailAttach($commitAttachCondition);
            $newList['commit']['attach'] = $taskDetailAttach->result->data;
            if(!empty($newList['commit']['attach'])){
                foreach($newList['commit']['attach'] as $value){
                    $value->src_attach = utility_cdn::file($value->file);
                }
            }
        }

        //作业内容  教师发布  查询发布信息
        if(!empty($commitTaskList->data->items)){
            $param = array('pk_task'=>$commitTaskList->data->items[0]->fk_task);
            $publishTaskDetail = task_api::getTaskDetail($param);

            //教师名字
            $teacher_name = task_api::getTeacherName($publishTaskDetail->result->data[0]->fk_user_teacher);
            $newList['publish']['data'] = $publishTaskDetail->result->data[0];
            //查询图片
            $publishThumbCondition =  $param = array('object_id'=>$publishTaskDetail->result->data[0]->pk_task,'object_type'=>1,'status'=>1);
            $taskDetailThumb = task_api::getTaskDetailThumb($publishThumbCondition);
            $newList['publish']['thumb'] = $taskDetailThumb->result->data;
            if(!empty( $newList['publish']['thumb'])){
                foreach( $newList['publish']['thumb'] as $value){
                    $value->src_big = utility_cdn::file($value->thumb_big);
                    $value->src_mall = utility_cdn::file($value->thumb_small);
                }
            }
            //查询附件
            $publishAttachCondition =  $param = array('object_id'=>$publishTaskDetail->result->data[0]->pk_task,'object_type'=>2,'status'=>1);
            $taskDetailAttach = task_api::getTaskDetailAttach($publishAttachCondition);
            $newList['publish']['attach'] = $taskDetailAttach->result->data;
            if(!empty( $newList['publish']['attach'])){
                foreach( $newList['publish']['attach'] as $value){
                    $value->src_attach = utility_cdn::file($value->file);
                }
            }
            //查询标签
            $publishAttachCondition =  $param = array('fk_task'=>$publishTaskDetail->result->data[0]->pk_task);
            $publishTag = task_api::getTaskDetailTag($publishAttachCondition);
            if(!empty($publishTag->result->data)){
                for($i=0;$i<count($publishTag->result->data);$i++){
                    //查询标签名字
                    $tagName = array('pk_tag'=>$publishTag->result->data[$i]->fk_tag);
                    $name = task_api::seltagTag($tagName);
                    if(!empty($name->data)){
                        $newName[$i]['name'] = $name->data[0]->name;
                        $newName[$i]['pk_tag'] = $name->data[0]->pk_tag;
                        $newList['publish']['tag'] = $newName;
                    }

                }

            }


            //查询学生信息  班级信息
            $param = array('pk_task'=>$commitTaskList->data->items[0]->fk_task);
            $taskDetail = task_api::getTaskDetail($param);
            if(!empty($taskDetail->result->data[0])){
                $studentName = task_api::getTeacherName($commitTaskList->data->items[0]->fk_user_student);
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
                            $taskInfo['create_time']  = date("m月d日 H:i",strtotime(substr($taskDetail->result->data[0]->start_time,0,16)));
                            $taskInfo['end_time']  =date("m月d日 H:i",strtotime(substr($taskDetail->result->data[0]->end_time,0,16)));
                            $taskInfo['StudentName']  = !empty($studentName->data[0]->real_name)?$studentName->data[0]->real_name:(!empty($studentName->data[0]->name)?$studentName->data[0]->name:"未设置");
                            $taskInfo['teacherName']  = !empty($teacher_name->data[0]->real_name)?$teacher_name->data[0]->real_name:(!empty($teacher_name->data[0]->name)?$teacher_name->data[0]->name:"未设置");
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
        }
    }


    //查看作业
    //学生查看作业  未批改  提交内容 和 教师发布作业
    function pagestudentTaskDetail(){

        $ret = new stdclass;
        $ret->result =  new stdclass;
        // 学生提交内容
        //作业Id 查询未批改作业 t_task_student
        //457  提交作业id
        if(empty($_GET['pk_task_student'])){
            $ret->result->code = -1;
            $ret->result->msg = "参数错误";
            return $ret;
        }

        $pk_task_student =array('pk_task_student'=>$_GET['pk_task_student']);
        $task = task_api::getStudentTaskDetail($pk_task_student);
        if($task->data->items[0]->status == -1){
            //作业已经删除
            $ret->result->code = -102;
            $ret->result->msg = "delete";
            return $ret;exit;

            // return $this->render("task/task.delete.html");
        }

        //权限如果是学生登陆 判断改作业是否是该学生
        $student =  task_api::getStudentTaskDetail($pk_task_student);
        if($student->data->items[0]->fk_user_student !=$this->user['uid'] ){
            $ret->result->code = -108;
            $ret->result->msg = "不是此学生作业";
            return $ret;exit;
        }



        $newList['commit']['data'] = $task->data;
        if($task->data->items){
            //查询图片
            $thumbCondition =  $param = array('object_id'=>$task->data->items[0]->pk_task_student,'object_type'=>2);
            $taskDetailThumb = task_api::getTaskDetailThumb($thumbCondition);
            $newList['commit']['thumb'] = $taskDetailThumb->result->data;
            if(!empty($newList['commit']['thumb'])){
                foreach($newList['commit']['thumb'] as $value){
                    $value->src_big = utility_cdn::file($value->thumb_big);
                    $value->src_mall = utility_cdn::file($value->thumb_small);
                }
            }
            //查询附件
            $attachCondition =  $param = array('object_id'=>$task->data->items[0]->pk_task_student,'object_type'=>2);
            $taskDetailAttach = task_api::getTaskDetailAttach($attachCondition);
            $newList['commit']['attach'] = $taskDetailAttach->result->data;
            if(!empty($newList['commit']['attach'])){
                foreach($newList['commit']['attach'] as $value){
                    $value->src_attach = utility_cdn::file($value->file);
                }
            }
            //教师发布内容
            //作业内容  教师发布  查询发布信息
            $param = array('pk_task'=>$task->data->items[0]->fk_task);
            $publishTaskDetail = task_api::getTaskDetail($param);

            $newList['publish']['data'] = $publishTaskDetail->result->data[0];
            //查询图片
            $publishThumbCondition =  $param = array('object_id'=>$publishTaskDetail->result->data[0]->pk_task,'object_type'=>1);
            $taskDetailThumb = task_api::getTaskDetailThumb($publishThumbCondition);
            $newList['publish']['thumb'] = $taskDetailThumb->result->data;
            if(!empty($newList['publish']['thumb'])){
                foreach($newList['publish']['thumb'] as $value){
                    $value->src_big = utility_cdn::file($value->thumb_big);
                    $value->src_mall = utility_cdn::file($value->thumb_small);
                }
            }
            //查询附件
            $publishAttachCondition =  $param = array('object_id'=>$publishTaskDetail->result->data[0]->pk_task,'object_type'=>1);
            $taskDetailAttach = task_api::getTaskDetailAttach($publishAttachCondition);
            $newList['publish']['attach'] = $taskDetailAttach->result->data;
            if(!empty($newList['publish']['attach'])){
                foreach($newList['publish']['attach'] as $value){
                    $value->src_attach = utility_cdn::file($value->file);
                }
            }
            //查询标签
            $publishAttachCondition =  $param = array('fk_task'=>$publishTaskDetail->result->data[0]->pk_task);
            $publishTag = task_api::getTaskDetailTag($publishAttachCondition);
            if(!empty($publishTag->result->data)){
                for($i=0;$i<count($publishTag->result->data);$i++){
                    //查询标签名字
                    $tagName = array('pk_tag'=>$publishTag->result->data[$i]->fk_tag);
                    $name = task_api::seltagTag($tagName);
                    if(!empty($name->data)){
                        $newName[$i]['name'] = $name->data[0]->name;
                        $newName[$i]['pk_tag'] = $name->data[0]->pk_tag;
                        $newList['publish']['tag'] = $newName;
                    }

                }

            }

            //查询教师信息  班级信息
            //查询作业信息   教师 发布时间
            $param = array('pk_task'=>$task->data->items[0]->fk_task);
            $taskDetail = task_api::getTaskDetail($param);
            if(!empty($taskDetail->result->data[0])){
                $teacherName = task_api::getTeacherName($taskDetail->result->data[0]->fk_user_teacher);
                if(!empty($teacherName->data[0])){
                    //班级 课程名称
                    $courseId = array('pk_course'=>$taskDetail->result->data[0]->fk_course);
                    $classId = array('pk_class'=>$taskDetail->result->data[0]->fk_class);
                    $courseInfo = task_api::getCourseOneName($courseId);
                    if(!empty($courseInfo->result)){
                        $classInfo = task_api::getClassInfo($classId);
                        if(!empty($classInfo->result->data->items)){
                            // $taskInfo = array();
                            $taskInfo['className'] = $classInfo->result->data->items[0]->name;
                            $taskInfo['pk_class'] = $classInfo->result->data->items[0]->pk_class;
                            $taskInfo['pk_course'] = $courseInfo->result[0]->pk_course;
                            $taskInfo['title'] = $courseInfo->result[0]->title;
                            $taskInfo['create_time']  = date("m月d日 H:i",strtotime($taskDetail->result->data[0]->start_time));
                            $taskInfo['end_time']  = date("m月d日 H:i",strtotime($taskDetail->result->data[0]->end_time));
                            $taskInfo['teacherName']  = !empty($teacherName->data[0]->real_name)?$teacherName->data[0]->real_name:(!empty($teacherName->data[0]->name)?$teacherName->data[0]->name:"未设置");
                            $taskInfo['teacherId']  = $taskDetail->result->data[0]->fk_user_teacher;
                            $newList['taskInfo'] = $taskInfo;
                        }
                    }
                }
            }
            $ret->result->code = 200;
            $ret->result->msg = "success";
            $ret->data = $newList;
            return $ret;
        }
    }



    //学生提交作业前展示页面   --
    public function pagecommitTaskShow(){
        $ret = new stdclass;
        $ret->result =  new stdclass;
        //通过作业ID 查看 待批改作业详情
        if(empty($_GET['pk_task']))
        {
            $ret->result->code = -1;
            $ret->result->msg = "参数错误";
            return $ret;
        }
        $param = array('pk_task'=>$_GET['pk_task']);
        $taskDetail = task_api::getTaskDetail($param);
        if($taskDetail->result->data[0]->status == -1){
            //此页面已经删除
            $ret->result->code = -102;
            $ret->result->msg = "delete";
            return $ret;exit;
            //return $this->render("task/task.delete.html");
        }
        if(!empty($taskDetail->result->data)){
            $newList['data'] = $taskDetail->result->data[0];
            //查询图片
            $thumbCondition =  $param = array('object_id'=>$taskDetail->result->data[0]->pk_task,'object_type'=>1,'status'=>1);
            $taskDetailThumb = task_api::getTaskDetailThumb($thumbCondition);
            $newList['thumb'] = $taskDetailThumb->result->data;
            if(!empty($newList['thumb'])){
                foreach($newList['thumb'] as $value){
                    $value->src_big = utility_cdn::file($value->thumb_big);
                    $value->src_mall = utility_cdn::file($value->thumb_small);
                }
            }
            //查询附件
            $attachCondition =  $param = array('object_id'=>$taskDetail->result->data[0]->pk_task,'object_type'=>1,'status'=>1);
            $taskDetailAttach = task_api::getTaskDetailAttach($attachCondition);
            $newList['attach'] = $taskDetailAttach->result->data;
            if(!empty($newList['attach'])){
                foreach($newList['attach'] as $val){
                    $val->src_attach = utility_cdn::file($val->file);
                }
            }
            //查询标签
            $attachCondition =  $param = array('fk_task'=>$taskDetail->result->data[0]->pk_task);
            $tag = task_api::getTaskDetailTag($attachCondition);
            if(!empty($tag->result->data)){
                for($i=0;$i<count($tag->result->data);$i++){
                    //查询标签名字
                    $tagName = array('pk_tag'=>$tag->result->data[$i]->fk_tag);
                    $name = task_api::seltagTag($tagName);
                    $newName[$i]['name'] = $name->data[0]->name;
                    $newName[$i]['pk_tag'] = $name->data[0]->pk_tag;
                }
                $newList['tag'] = $newName;
            }
            //查询班级  课程信息
            $courseId = array('pk_course'=>$taskDetail->result->data[0]->fk_course);
            $classId = array('pk_class'=>$taskDetail->result->data[0]->fk_class);
            $courseInfo = task_api::getCourseOneName($courseId);
            if(!empty($courseInfo->result)){
                $classInfo = task_api::getClassInfo($classId);
                if(!empty($classInfo->result->data->items)){
                    $infos = array();
                    $infos['name'] = $classInfo->result->data->items[0]->name;
                    $infos['pk_class'] = $classInfo->result->data->items[0]->pk_class;
                    $infos['pk_course'] = $courseInfo->result[0]->pk_course;
                    $infos['title'] = $courseInfo->result[0]->title;
                    $newList['info'] = $infos;
                }
            }
            $ret->result->code = 200;
            $ret->result->msg = "success";
            $ret->data = $newList;
            return $ret;
        }
    }


    //学生作业列表展示
    function pagestudentTaskListShow(){
        $this->render("index/publish.task.list.html");
    }

    //学生作业列表
    function pagestudentTaskList(){
        $ret = new stdclass;
        $ret->result =  new stdclass;
        $page = !empty($_GET['page'])?$_GET['page']:1;
        $status = isset($_GET['status'])?$_GET['status']:3; //默认全部 0未提交  1//待批改 2//已批改
        $studentId = $this->user['uid'];
        $limit = ($page-1) * self::$pageSize;

        $where = array('page'=>$limit,'pageNum'=>self::$pageSize,'status'=>$status,'studentId'=>$studentId);
        $taskList = task_api::getStudentTaskList($where);
        $params = array('page'=>'null','pageNum'=>'null','status'=>$status,'studentId'=>$studentId);
        $taskListCount = task_api::getStudentTaskList($params);
        //分页
        $commitListSum = count($taskListCount->data);
        $newList['page']['total'] = $commitListSum;
        $newList['page']['page'] = $page;
        $newList['page']['pageSize'] = self::$pageSize;
        $newList['page']['pathPage'] = "/task.commitTask.studentTaskList";
        $newList['page']['totalPage'] = ceil(count($taskListCount->data)/self::$pageSize);
        //处理内容  图片  附件显示
        if(!empty($taskList->data)){
            foreach($taskList->data as $key =>$val){
                //课程名称
                //班级名称
                $courseInfo = task_api::getCourseOneName(array('pk_course'=>$val->fk_course));
                $courseName = $courseInfo->result[0]->title;
                $classInfo = task_api::getClassInfo(array('pk_class'=>$val->fk_class));
                $className = $classInfo->result->data->items[0]->name;
                $val->courseName = $courseName;
                $val->className = $className;
                if($status==0){
                    $val->status = 0;
                }elseif($status==1){
                    //$val->status = $val->status;
                    $val->status = 1;
                }elseif($status==2){
                    //$val->status = $val->status;
                    $val->status = 2;
                }
                //查询是否有图片
                //$isImage = task_api::
                //查询是否与附件
                $thumbCondition =  $param = array('object_id'=>$val->pk_task,'object_type'=>1);
                $taskDetailThumb = task_api::getTaskDetailThumb($thumbCondition);
                if(!empty($taskDetailThumb->result->data)){
                    $val->thumb = "[图片作业]";
                }else{
                    $val->thumb = "";
                }
                //查询附件
                $attachCondition =  $param = array('object_id'=>$val->pk_task,'object_type'=>1);
                $taskDetailAttach = task_api::getTaskDetailAttach($attachCondition);
                if(!empty($taskDetailAttach->result->data)){
                    $val->attach = "[文件作业]";
                }else{
                    $val->attach = "";
                }
                //月 日 星期 时间   end_time
                if(empty($val->status)){
                    $val->status=0;
                }
                if(!empty($val->end_time)){
                    $month = date('m',strtotime($val->end_time));
                    $day =  date('d',strtotime($val->end_time));
                    $times = substr($val->end_time,10,6);
                    $val->end_time_handle =$month.'月'.$day.'日  '. '星期'.utility_time::cnweek($val->end_time).$times;
                }
                if(!empty($val->create_time)){
                    $createMonth = date("m",strtotime($val->create_time));
                    $creteDay =  date("d",strtotime($val->create_time));
                    $createTimes = substr($val->create_time,10,6);
                    $val->create_time_month = $createMonth.'月'.$creteDay.'日';
                    $val->create_time_time = $createTimes;
                }
            }
        }

        //组合数组 教师Name 字段新增紧数组
        for($i=0;$i<count($taskList->data);$i++){
            //查询教师Name
            if(!empty($taskList->data[$i]->fk_user_teacher)){
                $selTeacherName = task_api::getTeacherName($taskList->data[$i]->fk_user_teacher);
                $taskList->data[$i]->teacher_name = $selTeacherName->data[0]->real_name;
            }
        }

        //年/月/日 为key 重组数组
        $res = array();
        if(!empty($taskList->data)){
            foreach($taskList->data as $item) {
                if(!empty($item->create_time) && !empty($item->pk_task)){
                    $res[date("Y",strtotime($item->create_time))][date("m",strtotime($item->create_time))]["'".$item->pk_task."'"] = $item;
                }
            }
            $path =  "/task.commitTask.studentTaskList";
            $path_page = utility_tool::getUrl($path);
            unset($taskList->data->items);
            $list['page'] = $newList['page'];
            $list['data'] = $res;
            $ret->result->code = 200;
            $ret->result->msg = "success";
            $ret->data = $list;
            return $ret;
        }
    }

}
