<?php
/**
 * 作业功能
 * @author zhouyu
 * date: 2016/11/7
 */

class interface_studentTask extends interface_base
{
    private static $pageSize = 50;

    /*
     * 学生作业列表
     * */
    function pageStudentTaskList(){
        $page = !empty($this->paramsInfo['params']['page'])?$this->paramsInfo['params']['page']:1;
        $status = isset($this->paramsInfo['params']['status'])?(int)$this->paramsInfo['params']['status']:3; //默认全部   0未提交  1//待批改 2//已批改
        $studentId = isset($this->paramsInfo['params']['uId'])?(int)$this->paramsInfo['params']['uId']:'';
        if(empty($studentId)){
            return interface_func::setMsg(1001);
        }
        if(!isset($status)){
            return interface_func::setMsg(1001);
        }
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
        $newList['page']['pathPage'] = "/interface/studentTask/StudentTaskList";
        $newList['page']['totalPage'] = ceil(count($taskListCount->data)/self::$pageSize);

        if(!empty($taskList->data)){
            foreach($taskList->data as $key =>$val){
                unset($val->create_time);
                unset($val->desc);
                unset($val->last_updated);
                unset($val->student_count);
                unset($val->mark_count);
                unset($val->start_time);
                unset($val->end_time);
                unset($val->status);
                //课程名称
                //班级名称
                $courseInfo = task_api::getCourseOneName(array('pk_course'=>$val->fk_course));
                $courseName = $courseInfo->result[0]->title;
                $classInfo = task_api::getClassInfo(array('pk_class'=>$val->fk_class));
                $className = $classInfo->result->data->items[0]->name;
                $val->courseName = $courseName;
                $val->className = $className;
                if($status==0){ //未提交
                    if(!empty($val->task_create_time)){
                        $val->formatTime = date("H:i",strtotime($val->task_create_time));
                        $val->formatYMDTime = date("Y-m-d",strtotime($val->task_create_time));
                        $val->formatTimeWeek = '星期'.utility_time::cnweek($val->task_create_time);
                        $time = $val->task_create_time;
                    }
                }elseif($status==1){ //待批改
                    if(!empty($val->student_create_time)){
                        $val->formatTime = date("H:i",strtotime($val->student_create_time));
                        $val->formatYMDTime = date("Y-m-d",strtotime($val->student_create_time));
                        $val->formatTimeWeek = '星期'.utility_time::cnweek($val->student_create_time);
                        $time = $val->student_create_time;
                    }
                }elseif($status==2){ //已批改
                    if(!empty($val->reply_time)){
                        $val->formatTime = date("H:i",strtotime($val->reply_time));
                        $val->formatYMDTime = date("Y-m-d",strtotime($val->reply_time));
                        $val->formatTimeWeek = '星期'.utility_time::cnweek($val->reply_time);
                        $time = $val->reply_time;
                    }
                }
            }

            //组合数组 教师Name 字段新增紧数组
            for($i=0;$i<count($taskList->data);$i++){
                //查询教师Name
                if(!empty($taskList->data[$i]->fk_user_teacher)){
                    $selTeacherName = task_api::getTeacherName($taskList->data[$i]->fk_user_teacher);
                    $taskList->data[$i]->teacher_name = $selTeacherName->data[0]->real_name;
                    unset($taskList->data[$i]->fk_user_teacher);
                    unset($taskList->data[$i]->fk_course);
                    unset($taskList->data[$i]->fk_class);
                }
            }
            //年/月/日 为key 重组数组
            $res = array();
            foreach($taskList->data as $item) {
                if($status==0){ //未提交
                    $statusTime = $item->task_create_time;
                }
                if($status==1){ //待批改
                    $statusTime = $item->student_create_time;
                }
                if($status==2){ //已批改
                    $statusTime = $item->reply_time;
                }
                if(!empty($statusTime) && !empty($item->pk_task_student)){
                    $res[date("Y-m-d",strtotime($statusTime))]["'".$item->pk_task_student."'"] = $item;
                }
            }
            $newArray = array();
            $newArray1 = array();
            $n=-1;
            foreach($res as $k=> $v){
                $n++;
                $newArray[$n]['day']=$k;
                $num=-1;
                foreach($v as $k1=>$v1){
                    $num++;
                    $newArray1[$num] =$v1;
                }
                unset($num);
                $newArray[$n]['days']=$newArray1;
                unset($newArray1);
            }
            unset($n);
            $path =  "/interface/studentTask/studentTaskList";
            $path_page = utility_tool::getUrl($path);
            unset($taskList->data->items);
            $list['page'] = $newList['page'];
            $list['data'] = $newArray;
            return interface_func::setData($list);
        }else{
            return interface_func::setMsg(1054);
        }
    }

    /*
     * 教师已批改作业展示
     * */
    public function pageTaskShow(){
        $fkTaskStudent = !empty((int)$this->paramsInfo['params']['fkTaskStudent'])?(int)$this->paramsInfo['params']['fkTaskStudent']:'';
        $uId = !empty((int)$this->paramsInfo['params']['uId'])?(int)$this->paramsInfo['params']['uId']:'';
        if(empty($fkTaskStudent)){
            return interface_func::setMsg(1001);
        }
        //权限如果是学生登陆 判断改作业是否是该学生  老师跳过
        //是否是机构下老师
        //$special = user_api::getTeacherSpecial(self::$orgInfo->oid,$uId);
        // 讲师1  助教2
        //if(empty($special) || ( $special->role != 2 && $special->role != 1 && $special->status!=1 && empty($uId) ) ){
            $pk_task_student_id = array('pk_task_student'=>$fkTaskStudent);
            $commitTaskList =  task_api::getStudentTaskDetail($pk_task_student_id);
            if($commitTaskList->data->items[0]->fk_user_student != $uId ){
                return interface_func::setMsg(1052);
            }
        //}

        $pk_task = array('fk_task_student'=>$fkTaskStudent);
        //查询批改作业表
        $replyTaskInfo =  task_api::getStudentAllTaskAlealy($pk_task);
        if(!empty($replyTaskInfo->result->data[0])){
           if(!empty($replyTaskInfo->result->data[0]->last_updated)){
               $near = date("Y-m-d",strtotime($replyTaskInfo->result->data[0]->last_updated));
               $weekArray = array("日","一","二","三","四","五","六");
               $week = "星期".$weekArray[date("w",strtotime($replyTaskInfo->result->data[0]->last_updated))];
               $date = date("H:i",strtotime($replyTaskInfo->result->data[0]->last_updated));
               $replyTaskInfo->result->data[0]->lastUpdatedHandle=$near.' '.$week.' '.$date;
           }
        }
        $newList['reply']['data'] = array();
        if(!empty($replyTaskInfo->result->data)){
            $newList['reply']['data'] = $replyTaskInfo->result->data[0];
            $stuReplyId = $replyTaskInfo->result->data[0]->pk_task_student_reply;
            //查询图片 学生提交
            $replythumbCondition = $param = array('object_id' => $stuReplyId,'object_type'=>3,'status'=>1);
            $replyTasklThumb = task_api::getTaskDetailThumb($replythumbCondition);
            $newList['reply']['thumb'] = array();
            $newList['reply']['thumb'] = $replyTasklThumb->result->data;
            if(!empty($newList['reply']['thumb'])){
                foreach($newList['reply']['thumb'] as $value){
                    //查询图片对应语音
                    $selVoice = task_api::getVoiceByVoiceId($value->pk_thumb, 3);
                    $value->voice = !empty($selVoice->result->items) ? $selVoice->result->items : array();
                    $value->src_mallWidth = !empty($value->small_width)?$value->small_width:300;
                    $value->src_mallHeight = !empty($value->small_height)?$value->small_height:300;
                    unset($value->createtime);
                    unset($value->last_updated);
                    unset($value->object_type);
                    unset($value->object_id);
                    unset($value->status);
                    unset($value->pk_thumb);
                    unset($value->small_width);
                    unset($value->small_height);
                    $value->src_big = interface_func::imgURL($value->thumb_big);
                    $value->src_mall = interface_func::imgURL($value->thumb_small);
                }
                $voiceType = [
                    '1' => 'mp3'
                ];
                foreach ($newList['reply']['thumb'] as $v) {
                    foreach ($v->voice as $vv) {
                        $vv->srcVoice = interface_func::imgURL($vv->file);
                        $vv->pkVoice = $vv->pk_voice;
                        $vv->xCoordinate = $vv->x_coordinate;
                        $vv->YCoordinate = $vv->y_coordinate;
                        $vv->voiceTime = $vv->voice_time;
                        $vv->type = $voiceType[$vv->type];
                        $vv->sort = $vv->sort;
                        unset($vv->create_time);
                        unset($vv->last_updated);
                        unset($vv->status);
                        unset($vv->fk_thumb);
                        unset($vv->x_coordinate);
                        unset($vv->y_coordinate);
                        unset($vv->pk_voice);
                        unset($vv->voice_time);
                        unset($vv->object_id);
                        unset($vv->object_type);
                    }
                }
            }
            //查询附件 学生提交
            $replyAttachCondition = $param = array('object_id' => $stuReplyId,'object_type'=>3,'status'=>1);
            $taskDetailAttach = task_api::getTaskDetailAttach($replyAttachCondition);
            $newList['reply']['attach'] = array();
            $newList['reply']['attach'] = $taskDetailAttach->result->data;
            if(!empty($newList['reply']['attach'])){
                foreach($newList['reply']['attach'] as $k => $value){
                    if(empty($value->name)){
                        unset($newList['reply']['attach'][$k]);
                    }
                    unset($value->status);
                    unset($value->create_time);
                    unset($value->last_updated);
                    unset($value->pk_attach);
                    unset($value->object_type);
                    $value->type=substr($value->type,1);
                    $value->src_attach = interface_func::imgURL($value->file);
                }
                rsort( $newList['reply']['attach']);
            }
            //标签  查询批改标签
            //查询 t_task_student 取出ID 在查询标签
            // $selStudentTask =  task_api::getStudentAllTask($pk_task);
            //查询标签
            $pk_task_student_id = array('pk_task_student'=>$fkTaskStudent);
            $commitTaskList =  task_api::getStudentTaskDetail($pk_task_student_id);
            if(!empty($commitTaskList->data->items[0])) {
                $pk_task_student = $commitTaskList->data->items[0]->pk_task_student;
            }
            $where = array('fk_task_student'=>$pk_task_student);
            $replyTaskTag = task_api::replyTaskTag($where);
            $newList['reply']['tag'] = array();
            if(!empty($replyTaskTag->result->data)){
                for($i=0;$i<count($replyTaskTag->result->data);$i++){
                    //查询标签名字
                    $tagName = array('pk_tag'=>$replyTaskTag->result->data[$i]->fk_tag);
                    $name = task_api::seltagTag($tagName);
                    if(!empty($name->data[0])){
                        $newName[$i]['name'] = $name->data[0]->name;
                        $newName[$i]['pk_tag'] = $name->data[0]->pk_tag;
                        $newList['reply']['tag'] = $newName;
                    }
                }
                if(!empty($newList['reply']['tag'])){
                    rsort($newList['reply']['tag']);
                }
            }
        }

        //提交内容  学生提交  无标签 查询提交信息
        //查询提交作业表
        $pk_task_student_id = array('pk_task_student'=>$fkTaskStudent);
        $commitTaskList =  task_api::getStudentTaskDetail($pk_task_student_id);
        if(!empty($commitTaskList->data->items[0])) {
            $pk_task_student = $commitTaskList->data->items[0]->pk_task_student;
            //学生提交内容
            $newList['commit']['data'] = array();
            $newList['commit']['data'] = $commitTaskList->data->items[0];
            //查询图片 学生提交
            $commitThumbCondition = $param = array('object_id' => $pk_task_student,'object_type'=>2,'status'=>1);
            $taskDetailThumb = task_api::getTaskDetailThumb($commitThumbCondition);
            $newList['commit']['thumb'] = array();
            $newList['commit']['thumb'] = $taskDetailThumb->result->data;
            if(!empty( $newList['commit']['thumb'])){
                foreach( $newList['commit']['thumb'] as $value){
                    $value->src_mallWidth = !empty($value->small_width)?$value->small_width:300;
                    $value->src_mallHeight = !empty($value->small_height)?$value->small_height:300;
                    unset($value->createtime);
                    unset($value->last_updated);
                    unset($value->object_type);
                    unset($value->object_id);
                    unset($value->status);
                    unset($value->pk_thumb);
                    $value->src_big = interface_func::imgURL($value->thumb_big);
                    $value->src_mall = interface_func::imgURL($value->thumb_small);
                }
            }
            //查询附件 学生提交
            $commitAttachCondition = $param = array('object_id' => $pk_task_student,'object_type'=>2,'status'=>1);
            $taskDetailAttach = task_api::getTaskDetailAttach($commitAttachCondition);
            $newList['commit']['attach'] = array();
            $newList['commit']['attach'] = $taskDetailAttach->result->data;
            if(!empty($newList['commit']['attach'])){
                foreach($newList['commit']['attach'] as $key => $value){
                    if(empty($value->name)){
                        unset($newList['commit']['attach'][$key]);
                    }
                    unset($value->status);
                    unset($value->create_time);
                    unset($value->last_updated);
                    unset($value->pk_attach);
                    unset($value->object_type);
                    $value->type=substr($value->type,1);
                    $value->src_attach = interface_func::imgURL($value->file);
                }
                rsort($newList['commit']['attach']);
            }
        }

        //作业内容  教师发布  查询发布信息
        if(!empty($commitTaskList->data->items)){
            $param = array('pk_task'=>$commitTaskList->data->items[0]->fk_task);
            $publishTaskDetail = task_api::getTaskDetail($param);

            if(!empty($publishTaskDetail->result->data[0])){
                $publishTaskDetail->result->data[0]->startTimeHandle=!empty(date("m-d H:i",strtotime($publishTaskDetail->result->data[0]->start_time)))?date("m-d H:i",strtotime($publishTaskDetail->result->data[0]->start_time)):'';
            }
            //教师名字
            $teacher_name = task_api::getTeacherName($publishTaskDetail->result->data[0]->fk_user_teacher);
            $newList['publish']['data'] = array();
            $newList['publish']['data'] = $publishTaskDetail->result->data[0];
            //查询图片
            $publishThumbCondition =  $param = array('object_id'=>$publishTaskDetail->result->data[0]->pk_task,'object_type'=>1,'status'=>1);
            $taskDetailThumb = task_api::getTaskDetailThumb($publishThumbCondition);
            $newList['publish']['thumb'] = array();
            $newList['publish']['thumb'] = $taskDetailThumb->result->data;
            if(!empty( $newList['publish']['thumb'])){
                foreach( $newList['publish']['thumb'] as $value){
                    $value->src_mallWidth = !empty($value->small_width)?$value->small_width:300;
                    $value->src_mallHeight = !empty($value->small_height)?$value->small_height:300;
                    unset($value->createtime);
                    unset($value->last_updated);
                    unset($value->object_type);
                    unset($value->object_id);
                    unset($value->status);
                    unset($value->pk_thumb);
                    $value->src_big = interface_func::imgURL($value->thumb_big);
                    $value->src_mall = interface_func::imgURL($value->thumb_small);
                }
            }
            //查询附件
            $publishAttachCondition =  $param = array('object_id'=>$publishTaskDetail->result->data[0]->pk_task,'object_type'=>2,'status'=>1);
            $taskDetailAttach = task_api::getTaskDetailAttach($publishAttachCondition);
            $newList['publish']['attach'] = array();
            $newList['publish']['attach'] = $taskDetailAttach->result->data;
            if(!empty( $newList['publish']['attach'])){
                foreach( $newList['publish']['attach'] as $k1 => $value){
                    if(empty($value->name)){
                        unset($newList['publish']['attach'][$k1]);
                    }
                    unset($value->status);
                    unset($value->create_time);
                    unset($value->last_updated);
                    unset($value->pk_attach);
                    unset($value->object_type);
                    $value->type=substr($value->type,1);
                    $value->src_attach = interface_func::imgURL($value->file);
                }
                rsort( $newList['publish']['attach']);
            }
            //查询标签
            $newList['publish']['tag'] = array();
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
                if(!empty($newList['publish']['tag'])){
                    rsort($newList['publish']['tag']);
                }
            }

            //查询学生信息  班级信息
            $param = array('pk_task'=>$commitTaskList->data->items[0]->fk_task);
            $taskDetail = task_api::getTaskDetail($param);
            $taskInfo = array();
            if(!empty($taskDetail->result->data[0])){
                $studentName = task_api::getTeacherName($commitTaskList->data->items[0]->fk_user_student);
                $taskInfo['thumb_big'] = !empty($studentName->data[0]->thumb_big)?interface_func::imgURL($studentName->data[0]->thumb_big):'';
                $taskInfo['thumb_med'] = !empty($studentName->data[0]->thumb_med)?interface_func::imgURL($studentName->data[0]->thumb_med):'';
                $taskInfo['thumb_small'] = !empty($studentName->data[0]->thumb_small)?interface_func::imgURL($studentName->data[0]->thumb_small):'';

                if(!empty($studentName->data[0])){
                    //班级 课程名称
                    $courseId = array('pk_course'=>$taskDetail->result->data[0]->fk_course);
                    $classId = array('pk_class'=>$taskDetail->result->data[0]->fk_class);
                    $courseInfo = task_api::getCourseOneName($courseId);
                    if(!empty($courseInfo->result)){
                        $classInfo = task_api::getClassInfo($classId);
                        if(!empty($classInfo->result->data->items)){
                            $taskInfo['className'] = !empty($classInfo->result->data->items[0]->name)?$classInfo->result->data->items[0]->name:'';
                            $taskInfo['pk_class'] = !empty($classInfo->result->data->items[0]->pk_class)?$classInfo->result->data->items[0]->pk_class:'';
                            $taskInfo['pk_course'] = !empty($courseInfo->result[0]->pk_course)?$courseInfo->result[0]->pk_course:'';
                            $taskInfo['title'] = $courseInfo->result[0]->title;
                            $taskInfo['create_time']  = date("m月d日 H:i",strtotime(substr($taskDetail->result->data[0]->start_time,0,16)));
                            $taskInfo['end_time']  =date("m月d日 H:i",strtotime(substr($taskDetail->result->data[0]->end_time,0,16)));
                            $taskInfo['StudentName']  = !empty($studentName->data[0]->real_name)?$studentName->data[0]->real_name:(!empty($studentName->data[0]->name)?$studentName->data[0]->name:"未设置");
                            $taskInfo['teacherName']  = !empty($teacher_name->data[0]->real_name)?$teacher_name->data[0]->real_name:(!empty($teacher_name->data[0]->name)?$teacher_name->data[0]->name:"未设置");
                            $taskInfo['StudentId']  = !empty($studentName->data[0]->pk_user)?$studentName->data[0]->pk_user:'';
                            $newList['taskInfo'] = $taskInfo;
                        }
                    }
                }
            }
            return interface_func::setData($newList);
        }
    }


    /*
     * 时间转换
     * */
    function TimeConversion($RecordTime=''){
        if(empty($RecordTime)) return false;
        //2016-11-04 14:10:49
        $datetime = strtotime($RecordTime);
        $time = time();
        if($datetime > $time+3600*24*3){ //大于三天用当前时间
            $data = '';
        }elseif($time+3600*24*2 < $datetime && $datetime <= $time+3600*24*3){
            $data = "仅剩3天";
        }elseif($time+3600*24*1 < $datetime && $datetime <= $time+3600*24*2){
            $data = "仅剩2天";
        }elseif($time <= $datetime && $datetime <= $time+3600*24*1){
            $data = "仅剩1天";
        }elseif($datetime < $time ){
            $data = "已截止";
        }
        return $data;
    }


    /*
     * 待批改作业详情
     * */
    function pageStudentTaskDetail(){
        // 学生提交内容
        $pkTaskStudent = isset($this->paramsInfo['params']['pkTaskStudent']) ? (int)$this->paramsInfo['params']['pkTaskStudent'] : '';
        $uId = isset($this->paramsInfo['params']['uId']) ? $this->paramsInfo['params']['uId'] : '';
        if(empty($pkTaskStudent)){
            return interface_func::setMsg(1001);
        }
        $pk_task_student =array('pk_task_student'=>$pkTaskStudent);
        $task = task_api::getStudentTaskDetail($pk_task_student);

        if(empty($task->data->items)){
            return interface_func::setMsg(1051);
        }
        if($task->data->items[0]->status == -1){
            //作业已经删除
            return interface_func::setMsg(1051);
        }
        //权限如果是学生登陆 判断改作业是否是该学生
        $student =  task_api::getStudentTaskDetail($pk_task_student);
        if($student->data->items[0]->fk_user_student != $uId){
            return interface_func::setMsg(1052);
        }
        if(!empty($task->data->items[0]->create_time)){
            $near = date("Y-m-d",strtotime($task->data->items[0]->create_time));
            $weekArray = array("日","一","二","三","四","五","六");
            $week = "星期".$weekArray[date("w",strtotime($task->data->items[0]->create_time))];
            $date = date("H:i",strtotime($task->data->items[0]->create_time));
            $task->data->items[0]->createTimeHandle=$near.' '.$week.' '.$date;
        }
        $newList['commit']['data'] = array();
        $newList['commit']['data'] = $task->data->items[0];

        if($task->data->items){
            //查询图片
            $thumbCondition =  $param = array('object_id'=>$task->data->items[0]->pk_task_student,'object_type'=>2);
            $taskDetailThumb = task_api::getTaskDetailThumb($thumbCondition);
            $newList['commit']['thumb'] = array();
            $newList['commit']['thumb'] = $taskDetailThumb->result->data;
            if(!empty($newList['commit']['thumb'])){
                foreach($newList['commit']['thumb'] as $value){
                    $value->src_mallWidth = !empty($value->small_width)?$value->small_width:300;
                    $value->src_mallHeight = !empty($value->small_height)?$value->small_height:300;
                    unset($value->createtime);
                    unset($value->last_updated);
                    unset($value->object_type);
                    unset($value->object_id);
                    unset($value->status);
                    unset($value->pk_thumb);
                    $value->src_big = interface_func::imgURL($value->thumb_big);
                    $value->src_mall = interface_func::imgURL($value->thumb_small);
                }
            }
            //查询附件
            $attachCondition =  $param = array('object_id'=>$task->data->items[0]->pk_task_student,'object_type'=>2);
            $taskDetailAttach = task_api::getTaskDetailAttach($attachCondition);
            $newList['commit']['attach'] = array();
            $newList['commit']['attach'] = $taskDetailAttach->result->data;
            if(!empty($newList['commit']['attach'])){
                foreach($newList['commit']['attach'] as $k=> $value){
                    if(empty($value->name)){
                        unset($newList['commit']['attach'][$k]);
                    }
                    unset($value->status);
                    unset($value->create_time);
                    unset($value->last_updated);
                    unset($value->pk_attach);
                    unset($value->object_type);
                    $value->type=substr($value->type,1);
                    $value->src_attach = interface_func::imgURL($value->file);
                }
                rsort($newList['commit']['attach']);
            }
            //教师发布内容
            //作业内容  教师发布  查询发布信息
            $param = array('pk_task'=>$task->data->items[0]->fk_task);
            $publishTaskDetail = task_api::getTaskDetail($param);
            if(!empty($publishTaskDetail->result->data[0])){
                $publishTaskDetail->result->data[0]->startTimeHandle=!empty(date("m-d H:i",strtotime($publishTaskDetail->result->data[0]->start_time)))?date("m-d H:i",strtotime($publishTaskDetail->result->data[0]->start_time)):'';
            }
            $newList['publish']['data'] = array();
            $newList['publish']['data'] = $publishTaskDetail->result->data[0];
            //查询图片
            $publishThumbCondition =  $param = array('object_id'=>$publishTaskDetail->result->data[0]->pk_task,'object_type'=>1);
            $taskDetailThumb = task_api::getTaskDetailThumb($publishThumbCondition);
            $newList['publish']['thumb'] = array();
            $newList['publish']['thumb'] = $taskDetailThumb->result->data;
            if(!empty($newList['publish']['thumb'])){
                foreach($newList['publish']['thumb'] as $value){
                    $value->src_mallWidth = !empty($value->small_width)?$value->small_width:300;
                    $value->src_mallHeight = !empty($value->small_height)?$value->small_height:300;
                    unset($value->createtime);
                    unset($value->last_updated);
                    unset($value->object_type);
                    unset($value->object_id);
                    unset($value->status);
                    unset($value->pk_thumb);
                    $value->src_big = interface_func::imgURL($value->thumb_big);
                    $value->src_mall = interface_func::imgURL($value->thumb_small);
                }

            }
            //查询附件
            $publishAttachCondition =  $param = array('object_id'=>$publishTaskDetail->result->data[0]->pk_task,'object_type'=>1);
            $taskDetailAttach = task_api::getTaskDetailAttach($publishAttachCondition);
            $newList['publish']['attach'] = array();
            $newList['publish']['attach'] = $taskDetailAttach->result->data;
            if(!empty($newList['publish']['attach'])){
                foreach($newList['publish']['attach'] as $key=>$value){
                    if(empty($value->name)){
                        unset($newList['publish']['attach'][$key]);
                    }
                    unset($value->status);
                    unset($value->create_time);
                    unset($value->last_updated);
                    unset($value->pk_attach);
                    $value->type = substr($value->type,1);
                    unset($value->object_type);
                    $value->src_attach = interface_func::imgURL($value->file);
                }
                rsort($newList['publish']['attach']);
            }
            $newList['publish']['tag'] = array();
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
                if(!empty($newList['publish']['tag'])){
                    rsort($newList['publish']['tag']);
                }
            }
            //查询教师信息  班级信息
            //查询作业信息   教师 发布时间
            $param = array('pk_task'=>$task->data->items[0]->fk_task);
            $taskDetail = task_api::getTaskDetail($param);
            if(!empty($taskDetail->result->data[0])){
                $teacherName = task_api::getTeacherName($taskDetail->result->data[0]->fk_user_teacher);
                if(!empty($teacherName->data[0])){
                        $taskInfo['thumb_big'] = !empty($teacherName->data[0]->thumb_big)?interface_func::imgURL($teacherName->data[0]->thumb_big):'';
                        $taskInfo['thumb_med'] = !empty($teacherName->data[0]->thumb_med)?interface_func::imgURL($teacherName->data[0]->thumb_med):'';
                        $taskInfo['thumb_small'] = !empty($teacherName->data[0]->thumb_small)?interface_func::imgURL($teacherName->data[0]->thumb_small):'';

                    //班级 课程名称
                    $courseId = array('pk_course'=>$taskDetail->result->data[0]->fk_course);
                    $classId = array('pk_class'=>$taskDetail->result->data[0]->fk_class);
                    $courseInfo = task_api::getCourseOneName($courseId);
                    if(!empty($courseInfo->result)){
                        $classInfo = task_api::getClassInfo($classId);
                        if(!empty($classInfo->result->data->items)){
                            $taskInfo['className'] = !empty($classInfo->result->data->items[0]->name)?$classInfo->result->data->items[0]->name:'';
                            $taskInfo['pk_class'] = !empty($classInfo->result->data->items[0]->pk_class)?$classInfo->result->data->items[0]->pk_class:'';
                            $taskInfo['pk_course'] = !empty($courseInfo->result[0]->pk_course)?$courseInfo->result[0]->pk_course:'';
                            $taskInfo['title'] = !empty($courseInfo->result[0]->title)?$courseInfo->result[0]->title:'';
                            $taskInfo['create_time']  = date("m月d日 H:i",strtotime($taskDetail->result->data[0]->start_time));
                            $taskInfo['end_time']  = date("m月d日 H:i",strtotime($taskDetail->result->data[0]->end_time));
                            $taskInfo['teacherName']  = !empty($teacherName->data[0]->real_name)?$teacherName->data[0]->real_name:(!empty($teacherName->data[0]->name)?$teacherName->data[0]->name:"未设置");
                            $taskInfo['teacherId']  = !empty($taskDetail->result->data[0]->fk_user_teacher)?$taskDetail->result->data[0]->fk_user_teacher:'';
                            $newList['taskInfo'] = $taskInfo;
                        }
                    }
                }
            }
            return interface_func::setData($newList);
        }
    }


    /*
     * 未提交作业详情
     * */
    public function pageGetNoCommitList(){
        $taskId = !empty($this->paramsInfo['params']['taskId']) ? $this->paramsInfo['params']['taskId'] : '';
        $uId = !empty($this->paramsInfo['params']['uId']) ? $this->paramsInfo['params']['uId'] : '';
        if(empty($taskId) || empty($uId)){
            return interface_func::setMsg(1001);
        }
        $params = array('fk_user_student'=>$uId,'fk_task'=>$taskId);
        $studentTaskCheck = task_api::taskStudent($params);
        if(!empty($studentTaskCheck->items)){
            if($studentTaskCheck->items[0]->status == 1 || $studentTaskCheck->items[0]->status == 2){
                return interface_func::setMsg(1053);
            }
        }
        //通过作业ID 查看 待批改作业详情
        $param = array('pk_task'=>$taskId);
        $taskDetail = task_api::getTaskDetail($param);
        if(empty($taskDetail->result->data) || $taskDetail->result->data[0]->status == -1){
            return interface_func::setMsg(1051);//此作业不存在
        }
        if(!empty($taskDetail->result->data)){
            unset($taskDetail->result->data[0]->student_count);
            unset($taskDetail->result->data[0]->mark_count);
            unset($taskDetail->result->data[0]->status);
            if(!empty($taskDetail->result->data[0]->end_time)){
                $near = date("Y-m-d",strtotime($taskDetail->result->data[0]->end_time));
                $weekArray = array("日","一","二","三","四","五","六");
                $week = "星期".$weekArray[date("w",strtotime($taskDetail->result->data[0]->end_time))];
                $date = date("H:i",strtotime($taskDetail->result->data[0]->end_time));
                $taskDetail->result->data[0]->endTimeHandle=$near.' '.$date;
                $taskDetail->result->data[0]->countdown =!empty($this->TimeConversion($taskDetail->result->data[0]->end_time))? $this->TimeConversion($taskDetail->result->data[0]->end_time):'';
            }
            $teacherInfo = task_api::getTeacherName($taskDetail->result->data[0]->fk_user_teacher);
            $newList['data'] = array();
            $newList['data'] = $taskDetail->result->data[0]; //interface_func::imgURL();
            $newList['data']->teacherName = !empty($teacherInfo->data[0]->real_name)?$teacherInfo->data[0]->real_name:(!empty($teacherInfo->data[0]->name)?$teacherInfo->data[0]->name:"未设置");
            $newList['data']->thumb_big = !empty($teacherInfo->data[0]->thumb_big)?interface_func::imgURL($teacherInfo->data[0]->thumb_big):'';
            $newList['data']->thumb_med = !empty($teacherInfo->data[0]->thumb_med)?interface_func::imgURL($teacherInfo->data[0]->thumb_med):'';
            $newList['data']->thumb_small = !empty($teacherInfo->data[0]->thumb_small)?interface_func::imgURL($teacherInfo->data[0]->thumb_small):'';

            //查询图片
            $thumbCondition =  $param = array('object_id'=>$taskDetail->result->data[0]->pk_task,'object_type'=>1,'status'=>1);
            $taskDetailThumb = task_api::getTaskDetailThumb($thumbCondition);
            $newList['thumb'] = array();
            $newList['thumb'] = $taskDetailThumb->result->data;
            if(!empty($newList['thumb'])){
                foreach($newList['thumb'] as $value){
                    $value->src_mallWidth = !empty($value->small_width)?$value->small_width:300;
                    $value->src_mallHeight = !empty($value->small_height)?$value->small_height:300;
                    unset($value->pk_thumb);
                    unset($value->status);
                    unset($value->createtime);
                    unset($value->last_updated);
                    unset($value->object_id);
                    unset($value->object_type);
                    $value->src_big = interface_func::imgURL($value->thumb_big);
                    $value->src_mall = interface_func::imgURL($value->thumb_small);
                }
            }
            //查询附件
            $attachCondition =  $param = array('object_id'=>$taskDetail->result->data[0]->pk_task,'object_type'=>1,'status'=>1);
            $taskDetailAttach = task_api::getTaskDetailAttach($attachCondition);
            $newList['attach'] = array();
            $newList['attach'] = $taskDetailAttach->result->data;
            if(!empty($newList['attach'])){
                foreach($newList['attach'] as $key=>$val){
                    if(empty($val->name)){
                        unset($newList['attach'][$key]);
                    }
                    unset($val->create_time);
                    unset($val->last_updated);
                    unset($val->object_type);
                    unset($val->status);
                    unset($val->pk_attach);
                    $val->type=substr($val->type,1);
                    $val->src_attach = interface_func::imgURL($val->file);
                }
                rsort($newList['attach']);
            }
            $newList['tag'] = array();
            //查询标签
            $attachCondition =  $param = array('fk_task'=>$taskDetail->result->data[0]->pk_task);
            $tag = task_api::getTaskDetailTag($attachCondition);
            //print_r($tag);die;
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
                if(!empty($newList['tag'])){
                    rsort($newList['tag']);
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
                    $infos['name'] = !empty($classInfo->result->data->items[0]->name)?$classInfo->result->data->items[0]->name:'';
                    $infos['pk_class'] = !empty($classInfo->result->data->items[0]->pk_class)?$classInfo->result->data->items[0]->pk_class:'';
                    $infos['pk_course'] = !empty($courseInfo->result[0]->pk_course)?$courseInfo->result[0]->pk_course:'';
                    $infos['title'] = !empty($courseInfo->result[0]->title)?$courseInfo->result[0]->title:'';
                    $newList['info'] = $infos;
                }
            }
            //print_r($newList);die;
            return interface_func::setData($newList);
        }
    }


    //学生发布作业
    function pageCommitTask(){
        $info['fkTask'] = !empty((int)$this->paramsInfo['params']['fkTask']) ? (int)$this->paramsInfo['params']['fkTask'] : '';
        $info['desc'] = !empty($this->paramsInfo['params']['desc']) ? $this->paramsInfo['params']['desc'] : '';
        $info['uId'] = !empty((int)$this->paramsInfo['params']['uId']) ? (int)$this->paramsInfo['params']['uId'] : '';
        $info['images'] = !empty($this->paramsInfo['params']['images']) ? $this->paramsInfo['params']['images'] : '';
        if(empty($info['fkTask'])  || empty($info['uId'])){
            return interface_func::setMsg(1001);
        }
        if(empty($info['desc']) && empty($info['images'])){
            return interface_func::setMsg(1001);
        }
        //判断是否已经提交
        $arr = array('fk_user_student'=>$info['uId'],'fk_task'=>$info['fkTask']);
        $taskInfo = task_api::taskStudent($arr);

        if(empty($taskInfo->items)){
            return interface_func::setMsg(1051);
        }
        if($taskInfo->items[0]->status == 1 || $taskInfo->items[0]->status == 2){
            return interface_func::setMsg(1053);//作业已经提交
        }
        if(empty($info['uId'])){
            return interface_func::setMsg(1006); //登录失效
        }
        //通过作业ID 查看 待批改作业详情
        $param = array('pk_task'=>$info['fkTask']);
        $taskDetail = task_api::getTaskDetail($param);

        if(empty($taskDetail->result->data)){
            return interface_func::setMsg(1051); //没有此作业
        }
        if($taskDetail->result->data[0]->status == -1){
            return interface_func::setMsg(1051); //作业已经删除
        }
        $nowtime = date("Y-m-d H:i:s",time());
        $params = array(
            'fk_task'=>$info['fkTask'],
            'fk_user_student'=>$info['uId'],
            'desc'=>$info['desc'],
            'create_time'=>$nowtime,
            'status'=>1,
        );
        //添加作业表t_task_student
        $addTask = task_api::addStudentTask($params);
        if(!empty($addTask->data)){
            //添加图片表
            $takImages =  $info['images'];
            if(!empty($info['images'])){
                for($i=0;$i<count($takImages);$i++){
                    $width = !empty($takImages[$i]['src_mallWidth'])?$takImages[$i]['src_mallWidth']:0;
                    $height = !empty($takImages[$i]['src_mallHeight'])?$takImages[$i]['src_mallHeight']:0;
                    $imgCondition = array(
                        'thumb_big'=>$takImages[$i]['thumb_big'],
                        'thumb_small'=>$takImages[$i]['thumb_small'],
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
            //修改作业表 提交数量
            $params = array('id'=>$info['fkTask']);
            $taskNum = task_api::getTaskCommitNum($params);
            //提交次数 +1
            $where = array(
                'student_count'=>$taskNum->data->student_count+1,
                'pk_task'=>$info['fkTask']
            );
            $studentCount = task_api::updateCommitCount($where);
            return interface_func::setMsg(0);
        }else{
            return interface_func::setMsg(1);
        }
    }



    //图片上传  原图不处理
    public function pageUploadImage(){
        $image = !empty($this->paramsInfo['params']['image']) ? $this->paramsInfo['params']['image'] : '';
        if(empty($image)){
            return interface_func::setMsg(1001);
        }
        $data = array();
        $path = ROOT_WWW."/upload/tmp";
        if(!is_dir($path))
        {
            mkdir($path,0777,true);
        }
        $imgName = 'task'.time().".org.jpg";
        $filename = $path."/".$imgName;

        //获取图片
        if(file_put_contents($filename, base64_decode($image))){
            //压缩原图
            $imgName152 = 'task'.time().".small.org.jpg";
            $filename152 = $path."/".$imgName152;
            //$thumbnail = new SThumbnail(file_put_contents($filename152, base64_decode($params['imageInfo'])), 100);
            file_put_contents($filename152, base64_decode($image));
            $thumbnail = new SThumbnail($filename152, 100);
            $thumbnail->setMaxSize(500, 500);
            $filename_152 = utility_file::tempname("thumb");
            if($thumbnail->genFile($filename_152)===false){
                return false;
            }
            //上传服务器taskUpload
            //$imgName152 = 'task'.time().".small.org.jpg";
            $file152 = utility_file::instance();
            $r152 = $file152->upload($filename_152,user_api::getLoginUid(),"image");
            $url152 = $r152->fid;
            $src152 = interface_func::imgURL($r152->fid);
            if(@getimagesize($src152)){
                $imageSize = getimagesize($src152);
                $width = !empty($imageSize[0])?$imageSize[0]:200; //app默认200
                $height = !empty($imageSize[1])?$imageSize[1]:200;//app默认200
            }else{
                $width = 200;
                $height = 200;
            }
            //上传服务器taskUpload 不改变尺寸
            $file = utility_file::instance();
            $r1 = $file->upload($filename,user_api::getLoginUid(),"image",$imgName);
            $url = $r1->fid;
            $src = interface_func::imgURL($r1->fid);
            //@unlink($filename);
            $result = array('big'=>$url,'bigurl'=>$src,'small'=>$url152,'smallurl'=>$src152,'src_mallWidth'=>$width,'src_mallHeight'=>$height);
        }
        return interface_func::setData($result);
    }
}
