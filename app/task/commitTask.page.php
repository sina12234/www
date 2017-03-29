<?php
/**
 * 学生提交作业
 * @author zhouyu
 * date: 2016/7/12
 */
class task_commitTask extends STpl{
    private static $domain;
    private static $org;
    private static $orgInfo;
    private static $orgOwner;
    private static $pageSize = 50;
    function __construct(){
        $url = $_SERVER['REQUEST_URI'];
        $this->user = user_api::loginedUser();
        if(empty( $this->user)){
            $this->redirect("/site.main.login".'?url='."$url");
        }
        $domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
        if(empty(self::$domain)){
            self::$domain = $domain_conf->domain;
        }

        if(empty(self::$org)){
            self::$org=user_organization::subdomain();
            if(empty(self::$org)){
                header('Location: https://www.'.self::$domain);
            }
            self::$orgOwner = self::$org->userId; //机构所有者id 以后会根据域名而列取
            self::$orgInfo = user_organization::getOrgByOwner(self::$orgOwner);

        }
        //$this->assign('domain', self::$domain);
    }

    //学生提交作业展示
    function pagestudentCommitTask(){
        $this->render("task/commit.task.html");
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
        if($taskInfo->items[0]->status == 1 || $taskInfo->items[0]->status == 2){
            return json_encode(array('code'=>-103,'msg'=>'此作业已经提交！'));
        }
        $this->checkParam($info);
        if(empty($this->user['uid'])){
            //跳转登录
            header('Location: https://www.'.self::$domain);
        }
        //通过作业ID 查看 待批改作业详情
        $param = array('pk_task'=>$info['fk_task']);
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
            if(!empty($attachName[0]) && !empty($attachType[0]) && !empty($attachFile[0])){
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
        $result = user_api::changeUserLevelAndScore($this->user['uid'],'COMMIT_TASK');
        return json_encode(array('code'=>200,'msg'=>'success','data'=>$result));
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
        //是否是机构下老师

        $this->render("student/publish.task.list.html");
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
            //print_r($taskList);die;
            unset($taskList->data->items);
            $list['page'] = $newList['page'];
            $list['data'] = $res;
            $ret->result->code = 200;
            $ret->result->msg = "success";
            $ret->data = $list;
            return $ret;
        }

    }

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

    //学生查看作业  已批改
    function pageTaskDetail(){


    }

    public function checkParam($info){
        if( empty((int)$info['fk_task']) ){
            echo json_encode(array('code'=>-1,'msg'=>'作业ID为空'));exit;
        }
    }



    /*       课程详情页学生作业列表             */

    /*
     * 课程详情页作业列表
     * @param  status  默认全部 0未提交 1待批改 2已批改
     * @param  page    默认第一页
     * @return array
     * @link   https://wiki.gn100.com/doku.php?id=web:point:coursedetailstudenttasklist
     * */
    function pageGetStudentTaskList(){
        $page = !empty($_POST['page'])?$_POST['page']:1;
        $status = isset($_POST['status'])?$_POST['status']:3; //默认全部 0未提交  1//待批改 2//已批改
        $studentId = !empty($_POST['uId'])?$_POST['uId']:'';
        $classsId = !empty($_POST['classId'])?$_POST['classId']:'';

        if(empty($studentId)) return json_encode(array('code'=>-201,'msg'=>'用户ID不能为空'));
        if(empty($classsId)) return json_encode(array('code'=>-202,'msg'=>'班级ID不能为空'));

        $pageSize = 10;
        $limit = ($page-1) * $pageSize;
        $where = array('page'=>$limit,'pageNum'=>$pageSize,'status'=>$status,'studentId'=>$studentId,'classId'=>$classsId);
        $taskList = task_api::getStudentTaskList($where);
        $params = array('page'=>'null','pageNum'=>'null','status'=>$status,'studentId'=>$studentId,'classId'=>$classsId);
        $taskListCount = task_api::getStudentTaskList($params);
        //分页
        $commitListSum = count($taskListCount->data);
        $newList['page']['total'] = $commitListSum;
        $newList['page']['page'] = $page;
        $newList['page']['pageSize'] = $pageSize;
        $newList['page']['pathPage'] = "/task.commitTask.studentTaskList";
        $newList['page']['totalPage'] = ceil(count($taskListCount->data)/$pageSize);
        if(!empty($taskList->data)){
            foreach($taskList->data as $key =>$val){
                //课程名称 班级名称
                $courseInfo = task_api::getCourseOneName(array('pk_course'=>$val->fk_course));
                $courseName = $courseInfo->result[0]->title;
                $classInfo = task_api::getClassInfo(array('pk_class'=>$val->fk_class));
                $className = $classInfo->result->data->items[0]->name;
                $val->courseName = $courseName;
                $val->className = $className;
                //月 日 星期 时间   end_time
                if(!empty($val->end_time)){
                    $month = date('m',strtotime($val->end_time));
                    $day =  date('d',strtotime($val->end_time));
                    $times = date('H:i',strtotime($val->end_time));
                    $val->end_time_handle =$month.'月'.$day.'日  '. '星期'.utility_time::cnweek($val->end_time).$times;
                }
                if(!empty($val->create_time)){
                    $createMonth = date("m",strtotime($val->create_time));
                    $creteDay =  date("d",strtotime($val->create_time));
                    $createTimes = date("H:i",strtotime($val->create_time));
                    $val->create_time_month = $createMonth.'月'.$creteDay.'日';
                    $val->create_time_time = $createTimes;
                }
                if(!empty($val->fk_user_teacher)){
                    $selTeacherName = task_api::getTeacherName($val->fk_user_teacher);
                    $val->teacher_name = $selTeacherName->data[0]->real_name;
                }
                if($val->student_status == 2){ //已批改
                    $val->url = "/task.publishTask.taskCorrectShow?taskId=$val->fk_task&type=correct&studentId=$val->pk_task_student";
                    $AlreadyCommitTask = task_api::getStudentAllTaskAlealy(array('status'=>1,'fk_task_student'=>$val->pk_task_student));
                    if(!empty($AlreadyCommitTask->result->data[0]->level)){
                        $val->level = $AlreadyCommitTask->result->data[0]->level;
                    }
                }
                if($val->student_status == 1){ //待批改
                    $val->url = "/task.publishTask.taskCorrectShow?taskId=$val->fk_task&type=noCorrect&studentId=$val->pk_task_student";
                }
                if($val->student_status == 4){ //未提交
                    $val->url = "/task.commitTask.studentCommitTask?taskId=$val->fk_task";
                }
            }
        }
        $path =  "/task.commitTask.GetStudentTaskList";
        $path_page = utility_tool::getUrl($path);
        $list['page'] = $newList['page'];
        $list['data'] = $taskList->data;
        return json_encode(array('code'=>0,'msg'=>'success','data'=>$list));
    }
}
