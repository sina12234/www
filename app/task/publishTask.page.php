<?php

/**
 * 教师发布作业
 * @author zhouyu
 * date: 2016/7/6
 */
class task_publishTask extends STpl{
    private static $domain;
    private static $org;
    private static $orgInfo;
    private static $orgOwner;
    private static $pageSize = 50;

    function __construct(){
        $this->user = user_api::loginedUser();
        if (empty($this->user)) {
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
    }

    //测试方法
    function pageEntry($inPath){
        //查询已发布作业
        return $this->render("teacher/teacher.html");
    }

    function pageTaskCourse(){
        //查询已发布作业
        return $this->render("task/publish.task.course.html");
    }

    //班级 课程 信息
    public function pageclassCourseInfo(){
        //权限
        $this->Power(self::$orgInfo->oid, $this->user['uid']);
        $ret = new stdclass;
        $ret->result = new stdclass;
        //通过课程id 查询课程信息
        $courseId = !empty($_POST['courseId']) ? $_POST['courseId'] : '';
        $classId = !empty($_POST['classId']) ? $_POST['classId'] : '';
        if (empty($courseId)) {
            $ret->result->code = -1;
            $ret->result->msg = '课程ID为空';
            return $ret;
        }
        if (empty($classId)) {
            $ret->result->code = -2;
            $ret->result->msg = '班级ID为空';
            return $ret;
        }
        $courseId = array('pk_course' => $_POST['courseId']);
        $classId = array('pk_class' => $_POST['classId']);
        $courseInfo = task_api::getCourseOneName($courseId);
        if (!empty($courseInfo->result)) {
            $classInfo = task_api::getClassInfo($classId);
            if (!empty($classInfo->result->data->items)) {
                $infos = array();
                $infos['name'] = $classInfo->result->data->items[0]->name;
                $infos['pk_class'] = $classInfo->result->data->items[0]->pk_class;
                $infos['pk_course'] = $courseInfo->result[0]->pk_course;
                $infos['title'] = $courseInfo->result[0]->title;
                $ret->result->code = 200;
                $ret->result->msg = 'success';
                $ret->result->data = $infos;
                return $ret;
            }
        }
    }

    //教师发布作业展示
    public function pageteacherPublishTask(){
        //权限
        $userId = $this->user['uid'];
        if (empty($userId)) {
            //跳转登录
            header('Location: https://www.' . self::$domain);
        }
        $special = user_api::getTeacherSpecial(self::$orgInfo->oid, $userId);
        //学生账号 没有此权限
        if (empty($special)) {
            return $this->redirect("/site.main.entry");
        }
        return $this->render("task/publishTask.html");
    }

    //教师发布作业 处理
    public function pagepublishTask(){
        //权限
        $this->Power(self::$orgInfo->oid, $this->user['uid']);
        $info['fk_course'] = !empty($_POST['fk_course']) ? $_POST['fk_course'] : '';
        $info['fk_class'] = !empty($_POST['fk_class']) ? $_POST['fk_class'] : '';
        $info['fk_user_teacher'] = $this->user['uid'];
        $info['desc'] = !empty($_POST['desc']) ? $_POST['desc'] : '';
        $info['start_time'] = !empty($_POST['start_time']) ? $_POST['start_time'] : '';
        $info['end_time'] = !empty($_POST['end_time']) ? $_POST['end_time'] : '';
        $info['taskImages'] = !empty($_POST['taskImages']) ? $_POST['taskImages'] : '';
        $info['attachName'] = !empty($_POST['attachName']) ? $_POST['attachName'] : '';
        $info['attachType'] = !empty($_POST['attachType']) ? $_POST['attachType'] : '';
        $info['attachFile'] = !empty($_POST['attachFile']) ? $_POST['attachFile'] : '';
        $info['tags'] = !empty($_POST['tags']) ? $_POST['tags'] : '';
        $this->publishTaskCheck($info);
        if (empty($this->user['uid'])) {
            //跳转登录
            header('Location: https://www.' . self::$domain);
        }
        $nowtime = date("Y-m-d H:i:s", time());
        $params = array(
            'fk_course' => $info['fk_course'],
            'fk_class' => $info['fk_class'],
            'fk_user_teacher' => $this->user['uid'],
            'status' => '0', //未发布
            'desc' => $info['desc'],
            'create_time' => $nowtime,
            'start_time' => $info['start_time'],
            'end_time' => $info['end_time'],

        );
        //t_task发布作业表
        $addPublishTask = task_api::addPublishTask($params);
        if (!empty($addPublishTask->data)) {
            $taskImages = $info['taskImages'];
            //图片表
            if (!empty($taskImages)) {
                for ($i = 0; $i < count($taskImages); $i++) {
                    $width = !empty($taskImages[$i]["small_width"]) ? $taskImages[$i]["small_width"] : 0;
                    $height = !empty($taskImages[$i]["small_height"]) ? $taskImages[$i]["small_height"] : 0;
                    $imgCondition = array(
                        'thumb_big' => $taskImages[$i]['big'],
                        'thumb_small' => $taskImages[$i]['small'],
                        'status' => '1', //正常
                        'createtime' => $nowtime,
                        'object_id' => $addPublishTask->data,
                        'object_type' => 1, //教师发布1 学生提交2 教师批改3
                        'small_width' => $width,
                        'small_height' => $height
                    );
                    $addTaskImg = task_api::addPublishTaskImg($imgCondition);
                }
            }
            //附件表
            $attachName = $info['attachName'];
            $attachName = explode(',', $attachName);
            $attachType = $info['attachType'];
            $attachType = explode(',', $attachType);
            $attachFile = $info['attachFile'];
            $attachFile = explode('&', $attachFile);
            if (!empty($attachName[0]) && !empty($attachType[0]) && !empty($attachFile[0])) {
                for ($i = 0; $i < count($attachName); $i++) {
                    $attachCondition = array(
                        'name' => $attachName[$i],
                        'file' => $attachFile[$i],
                        'status' => '1', //正常
                        'type' => $attachType[$i],
                        'create_time' => $nowtime,
                        'object_id' => $addPublishTask->data,
                        'object_type' => 1,
                    );
                    $addTaskAttach = task_api::addPublishTaskAttach($attachCondition);
                }
            }
        }
        /*  标签表 t_tag 库  t_course 库
         *  t_tag t_tag_belong_group
         *  t_mapping_tag_task   t_mapping_tag_task_student
         */
        //如果tag为空  t_tag tag_belong_group添加数据
        if (!empty($info['tags'])) {
            $tags = $info['tags'];
            $tags = explode(',', $tags);
            for ($i = 0; $i < count($tags); $i++) {
                //查询标签是否存在
                $new_param = array('name' => $tags[$i]);
                $sel_tag = task_api::seltagTag($new_param);
                if (empty($sel_tag->data)) {
                    $where = array(
                        'fk_user' => self::$orgInfo->oid,
                        'name' => $tags[$i],
                        'desc' => '',
                        'status' => '0',
                    );
                    //添加 t_tag
                    $addTag = task_api::addTagTag($where);
                    if (!empty($addTag->data)) {
                        $params = array(
                            'fk_tag' => $addTag->data,
                            'fk_group' => '4',
                            'status' => '0',
                        );
                        //添加 t_tag_belong_group
                        $addTagBelong = task_api::addTagBelongGroup($params);

                        //添加c_ouurse  t_mapping_tag_task
                        $params = array(
                            'fk_group' => '4',
                            'fk_tag' => $addTag->data,
                            'fk_task' => $addPublishTask->data,
                            'status' => '0',
                        );
                        $addMappingTag = task_api::addMappingTag($params);
                    }

                } else {
                    //添加c_ouurse  t_mapping_tag_task
                    $params = array(
                        'fk_group' => '4',
                        'fk_tag' => $sel_tag->data[0]->pk_tag,
                        'fk_task' => $addPublishTask->data,
                        'status' => '0',
                    );
                    $addMappingTag = task_api::addMappingTag($params);
                }
            }
        }
        return json_encode(array('code' => 200, 'msg' => 'success'));
    }

    //教师发布作业列表 =》 选择课程 -》课程名称
    public function pagecourseInfo(){
        //权限
        $this->Power(self::$orgInfo->oid, $this->user['uid']);
        //通过当前教师ID 查询所代课程
        $ret = new stdclass;
        $ret->result = new stdclass;
        $param = array('fk_user_teacher' => $this->user['uid']);
        $getTeacherCourse = task_api::getTeacherCourse($param);
        if (empty($getTeacherCourse->result)) {
            $ret->result->code = -109;
            $ret->result->msg = "此教师没有课程";
            return $ret;
        }
        //过滤课程下的班级不是当前老师所带班级 的课程信息
        //过滤班级为空的课程
        foreach ($getTeacherCourse->result as $k => $v) {
            if (empty($v->fk_course)) {
                unset($getTeacherCourse->result[$k]);
            } else {
                $where = array(
                    'courseId' => $v->fk_course,
                );
                //查询班级
                $getCoursePlan = task_api::getCoursePlan($where);
                if (!empty($getCoursePlan->data)) {
                    foreach ($getCoursePlan->data as $k1 => $v1) {
                        if ($v1->fk_user_plan != $this->user['uid']) {
                            unset($getCoursePlan->data[$k1]);
                        }
                    }
                }
                if (empty($getCoursePlan->data)) {
                    unset($getTeacherCourse->result[$k]);
                }
            }
        }
        $newarray = array();
        if ($getTeacherCourse->result) {
            foreach ($getTeacherCourse->result as $k => $j) {
                //课程ID 查询课程name
                $courseId = array('pk_course' => $j->fk_course);
                $courseName = task_api::getCourseOneName($courseId);
                if (empty($courseName->result[0]->title)) {
                    continue;
                }
                $newarray[$j->fk_course] = $courseName->result[0]->title;
            }
            $ret->result->code = 200;
            $ret->result->msg = "success";
            $ret->data = $newarray;
            return $ret;
        }
    }

    //发布作业下拉菜单搜索
    public function pagecourseSearch(){
        $title = !empty($_POST['title']) ? $_POST['title'] : '';
        //权限
        $this->Power(self::$orgInfo->oid, $this->user['uid']);
        //通过当前教师ID 查询所代课程
        $ret = new stdclass;
        $ret->result = new stdclass;
        $param = array('fk_user_teacher' => $this->user['uid']);
        $getTeacherCourse = task_api::getTeacherCourse($param);
        if (empty($title)) {
            $ret->result->code = -101;
            $ret->result->msg = "课程名称为空!";
            return $ret;
        }
        if (empty($getTeacherCourse->result)) {
            $ret->result->code = -102;
            $ret->result->msg = "此教师没有课程!";
            return $ret;
        }
        //过滤课程下的班级不是当前老师所带班级 的课程信息
        //过滤班级为空的课程
        foreach ($getTeacherCourse->result as $k => $v) {

            if (empty($v->fk_course)) {
                unset($getTeacherCourse->result[$k]);
            } else {
                $where = array(
                    'courseId' => $v->fk_course,
                );
                //查询班级
                $getCoursePlan = task_api::getCoursePlan($where);

                if (!empty($getCoursePlan->data)) {
                    foreach ($getCoursePlan->data as $k1 => $v1) {
                        if ($v1->fk_user_plan != $this->user['uid']) {
                            unset($getCoursePlan->data[$k1]);
                        }
                    }
                }
                if (empty($getCoursePlan->data)) {
                    unset($getTeacherCourse->result[$k]);
                }
            }
        }

        $newarray = array();
        if ($getTeacherCourse->result) {
            foreach ($getTeacherCourse->result as $k => $j) {
                //课程ID 查询课程name
                $courseId = array('pk_course' => $j->fk_course);
                $courseName = task_api::getCourseOneName($courseId);
                if (empty($courseName->result[0]->title)) {
                    continue;
                }
                if (strstr($courseName->result[0]->title, $title)) {
                    $newarray[$j->fk_course] = $courseName->result[0]->title;
                }
            }
            if (empty($newarray)) {
                $ret->result->code = -103;
                $ret->result->msg = "没有此课程!";
                return $ret;
            }
            $ret->result->code = 200;
            $ret->result->msg = "success";
            $ret->data = $newarray;
            return $ret;
        }
    }

    //通过选择课程课程id对应班级
    public function pagegetCourseClass($info){
        //权限
        $this->Power(self::$orgInfo->oid, $this->user['uid']);
        $courseId = !empty($_POST['course_id']) ? $_POST['course_id'] : '';
        $ret = new stdclass;
        $ret->result = new stdclass;
        if (empty($courseId)) {
            $ret->result->code = -1;
            $ret->result->msg = "课程ID为空";
            return $ret;
        }
        //排课包括教师 和 班主任
        $where = array(
            'courseId' => $courseId,
        );
        $getCoursePlan = task_api::getCoursePlan($where);
        $getCoursePlan = $this->array_unique_fb($getCoursePlan->data);
        $Arr = array();
        if (!empty($getCoursePlan)) {
            foreach ($getCoursePlan as $k => $v) {
                if ($v->fk_user_plan == $this->user['uid']) {
                    $Arr[$k]['pk_class'] = $v->fk_class;
                    $Arr[$k]['fk_course'] = $v->fk_course;
                    $course_id = array('pk_class' => $v->fk_class);
                    $courseName = task_api::getClassInfo($course_id);
                    if (!empty($courseName->result->data->items)) {
                        $Arr[$k]['name'] = $courseName->result->data->items[0]->name;
                    }
                }
            }
        }
        $Arr = $this->array_unique_fb($Arr);
        $arr = array();
        if (!empty($Arr)) {
            foreach ($Arr as $f => $j) {
                $arr[$f] = $this->array2object($j);
            }
        }
        if (!empty($arr)) {
            $result = new stdclass;
            $result->result = new stdclass;
            $result->result = $arr;

            $ret->result->code = 200;
            $ret->result->msg = 'success';
            $ret->result->data = $result;
        } else {
            $ret->result->code = -1;
            $ret->result->msg = '班级为空';
        }
        return $ret;
    }

    //tag
    public function pagetag(){
        $tags = '几何,语文,数学';
        $tags = explode(',', $tags);
        for ($i = 0; $i < count($tags); $i++) {
            echo $tags[$i] . "<br/>";
        }
    }

    //check 修改作业
    public function checkParam($info){
        if (empty((int)$info['pk_task'])) {
            echo json_encode(array('code' => -3, 'msg' => '作业ID为空'));
            exit;
        }
        if (empty(trim($info['start_time']))) {
            echo json_encode(array('code' => -10, 'msg' => '开始时间为空'));
            exit;
        }
        if (strtotime($info['start_time']) < time()) {
            echo json_encode(array('code' => -11, 'msg' => '开始大于当前时间'));
            exit;
        }
        if (empty(trim($info['end_time']))) {
            echo json_encode(array('code' => -12, 'msg' => '结束时间为空'));
            exit;
        }
        if ($info['end_time'] < $info['start_time']) {
            echo json_encode(array('code' => -13, 'msg' => '开始时间为空'));
            exit;
        }
        if (!empty($info['tags'])) {
            //标签判断
            $tags = explode(',', $info['tags']);
            if (count($tags) > 5) {
                echo json_encode(array('code' => -15, 'msg' => '标签数最多为5'));
                exit;
            }
            for ($i = 0; $i < count($tags); $i++) {
                if (preg_match("/[\x7f-\xff]/", $tags[$i])) {
                    //exit('有中文!');
                    if (mb_strlen($tags[$i]) > 60) {
                        echo json_encode(array('code' => -16, 'msg' => '每个标签中文最多为20字符'));
                        exit;
                    }
                } else {
                    //exit('没有中文!');
                    if (mb_strlen($tags[$i]) > 40) {
                        echo json_encode(array('code' => -17, 'msg' => '每个标签数字英文最多为40字符'));
                        exit;
                    }
                }
            }
        }
    }

    //作业列表展示
    public function pageteacherTaskList(){
        $userId = $this->user['uid'];
        if (empty($userId)) {
            //跳转登录
            header('Location: https://www.' . self::$domain);
        }
        $special = user_api::getTeacherSpecial(self::$orgInfo->oid, $userId);
        //学生账号 没有此权限
        if (empty($special)) {
            return $this->redirect("/site.main.entry");
        }
        //权限
        $this->render("task/publish.task.list.html");
    }

    //教师作业列表
    public function pagetaskList(){
        //权限
        $ret = new stdclass;
        $ret->result = new stdclass;
        $page = !empty($_GET['page']) ? $_GET['page'] : 1;
        $status = isset($_GET['status']) ? $_GET['status'] : 3;  //0 未发布 1 带批改 2 已批改 3 全部
        $userId = $this->user['uid'];
        //获取教师所带班级课程
        $params = array("user_id" => self::$orgOwner, "user_class_id" => $userId);
        $teacherClassList = course_api::classlistbycond($params);
        if (!empty($teacherClassList->data)) {
            if (count($teacherClassList->data) == 1) {
                $classIds = $teacherClassList->data[0]->class_id;
            } else {
                $classIds = '';
                for ($i = 0; $i < count($teacherClassList->data); $i++) {
                    $classIds .= $teacherClassList->data[$i]->class_id . ",";
                }
                $classIds = rtrim($classIds, ',');
            }
            $pageNum = array(
                'pageNum' => self::$pageSize,
                'page' => $page,
                'status' => $status,
                //'classIds'=>$classIds,
                'uId' => $userId,
            );
            $taskList = task_api::getTaskList($pageNum);

            if (!empty($taskList->result->data->items)) {
                //处理内容  图片  附件显示
                foreach ($taskList->result->data->items as $key => $val) {
                    //查询是否有图片
                    //查询是否与附件
                    $thumbCondition = $param = array('object_id' => $val->pk_task, 'object_type' => 1);
                    $taskDetailThumb = task_api::getTaskDetailThumb($thumbCondition);
                    if (!empty($taskDetailThumb->result->data)) {
                        $val->thumb = "[图片作业]";
                    } else {
                        $val->thumb = "";
                    }
                    //查询附件
                    $attachCondition = $param = array('object_id' => $val->pk_task, 'object_type' => 1);
                    $taskDetailAttach = task_api::getTaskDetailAttach($attachCondition);
                    if (!empty($taskDetailAttach->result->data)) {
                        $val->attach = "[文件作业]";
                    } else {
                        $val->attach = "";
                    }
                    if ($val->student_count == $val->mark_count) {
                        $val->correct = false;
                    } else {
                        $val->correct = true;
                    }
                    //月 日 星期 时间   end_time
                    $month = date('m', strtotime($val->end_time));
                    $day = date('d', strtotime($val->end_time));
                    $times = substr($val->end_time, 10, 6);
                    $val->end_time_handle = $month . '月' . $day . '日  ' . '星期' . utility_time::cnweek($val->end_time) . $times;
                    //月 日 时间   end_time
                    $createMonth = date('m', strtotime($val->create_time));
                    $creteDay = date('d', strtotime($val->create_time));
                    $createTimes = substr($val->create_time, 10, 6);
                    $val->create_time_month = $createMonth . '月' . $creteDay . '日';
                    $val->create_time_time = $createTimes;
                }
            }

            if (!empty($taskList->result->data->items)) {
                //年/月/日 为key 重组数组
                foreach ($taskList->result->data->items as $item) {
                    $res['data'][date("Y", strtotime($item->create_time))][date("m", strtotime($item->create_time))]["'" . $item->pk_task . "'"] = $this->objarray_to_array($item);
                }
                //待批改作业原点提示   $userId
                $isPrompt = task_api::getTaskListIsPrompt($userId);
                if (!empty($isPrompt->result->data->items)) {
                    if ($isPrompt->result->data->items[0]->student_count_sum == $isPrompt->result->data->items[0]->mark_count_sum) {
                        $res['status'] = "noprompt";
                    } else {
                        $res['status'] = "prompt";
                    }
                }
                unset($taskList->result->data->items);
                $res['page'] = $taskList->result->data;
                $path = "/task.publishTask.taskList";
                $path_page = utility_tool::getUrl($path);
                $res['page']->pathPage = $path_page;
                $ret->result->code = 200;
                $ret->result->msg = "success";
                $ret->data = $res;
            }
        }
        return $ret;
    }

    //作业详情展示
    public function pagetaskDetailShow(){
        //权限
        $userId = $this->user['uid'];
        if (empty($userId)) {
            //跳转登录
            header('Location: https://www.' . self::$domain);
        }
        $special = user_api::getTeacherSpecial(self::$orgInfo->oid, $userId);
        //学生账号 没有此权限
        if (empty($special)) {
            return $this->redirect("/site.main.entry");
        }
        $this->render("task/publish.look.task.html");
    }

    //批改结果展示
    public function pagetaskCorrectShow(){
        //权限
        $this->render("task/publish.look.result.html");
    }

    //教师查看作业详情
    public function pagetaskDetail(){
        //权限
        $this->Power(self::$orgInfo->oid, $this->user['uid']);
        $result = new stdClass();
        $result->result = new stdClass();
        if (empty($_GET['pk_task'])) {
            $result->result->code = -1;
            $result->result->msg = "参数错误";
            return $result;
        }
        $pk_task = $_GET['pk_task'];
        $page = !empty($_GET['page']) ? $_GET['page'] : 1;
        //查询作业信息   教师 发布时间
        $newList = array();
        $param = array('pk_task' => $pk_task);
        $taskDetail = task_api::getTaskDetail($param);
        $courseId = !empty($taskDetail->result->data[0]->fk_course) ? $taskDetail->result->data[0]->fk_course : '';
        $classId = !empty($taskDetail->result->data[0]->fk_class) ? $taskDetail->result->data[0]->fk_class : '';

        if (!empty($taskDetail->result->data[0])) {
            $teacherName = task_api::getTeacherName($taskDetail->result->data[0]->fk_user_teacher);
            if (!empty($teacherName->data[0])) {
                //班级 课程名称
                $courseInfo = task_api::getCourseOneName(array('pk_course' => $taskDetail->result->data[0]->fk_course));
                if (!empty($courseInfo->result)) {
                    $classInfo = task_api::getClassInfo(array('pk_class' => $taskDetail->result->data[0]->fk_class));
                    if (!empty($classInfo->result->data->items)) {
                        $taskInfo = array();
                        $taskInfo['taskInfo']['className'] = $classInfo->result->data->items[0]->name;
                        $taskInfo['taskInfo']['pk_class'] = $classInfo->result->data->items[0]->pk_class;
                        $taskInfo['taskInfo']['pk_course'] = $courseInfo->result[0]->pk_course;
                        $taskInfo['taskInfo']['title'] = $courseInfo->result[0]->title;
                        $taskInfo['taskInfo'] ['create_time'] = date('m月d日 H:i', strtotime($taskDetail->result->data[0]->start_time));
                        $taskInfo['taskInfo'] ['end_time'] = date('Y-m月-d日 H:i', strtotime($taskDetail->result->data[0]->end_time));
                        $taskInfo['taskInfo'] ['teacherName'] = !empty($teacherName->data[0]->real_name) ? $teacherName->data[0]->real_name : (!empty($teacherName->data[0]->name) ? $teacherName->data[0]->real_name : '未设置');
                        $taskInfo['taskInfo'] ['teacherId'] = $taskDetail->result->data[0]->fk_user_teacher;
                        $newList = $taskInfo;
                    }
                }
            }
        }
        //通过作业ID 查看 待批改作业详情
        $param = array('pk_task' => $pk_task);
        $taskDetail = task_api::getTaskDetail($param);
        //查询教师名字
        if (!empty($taskDetail->result->data)) {
            $classId = $taskDetail->result->data[0]->fk_class;
            $course_id = $taskDetail->result->data[0]->fk_course;
            $newList['data'] = $taskDetail->result->data[0];
            //查询图片
            $thumbCondition = $param = array('object_id' => $taskDetail->result->data[0]->pk_task, 'object_type' => 1, 'status' => 1);
            $taskDetailThumb = task_api::getTaskDetailThumb($thumbCondition);
            $newList['thumb'] = $taskDetailThumb->result->data;
            if (!empty($newList['thumb'])) {
                foreach ($newList['thumb'] as $value) {
                    $value->src_big = utility_cdn::file($value->thumb_big);
                    $value->src_mall = utility_cdn::file($value->thumb_small);
                }
            }
            //查询附件
            $attachCondition = $param = array('object_id' => $taskDetail->result->data[0]->pk_task, 'object_type' => 1, 'status' => 1);
            $taskDetailAttach = task_api::getTaskDetailAttach($attachCondition);
            $newList['attach'] = $taskDetailAttach->result->data;
            if (!empty($newList['attach'])) {
                foreach ($newList['attach'] as $value) {
                    $value->src_attach = utility_cdn::file($value->file);
                }
            }
            //查询标签
            $attachCondition = $param = array('fk_task' => $taskDetail->result->data[0]->pk_task);
            $tag = task_api::getTaskDetailTag($attachCondition);
            if (!empty($tag->result->data)) {
                for ($i = 0; $i < count($tag->result->data); $i++) {
                    //查询标签名字
                    $tagName = array('pk_tag' => $tag->result->data[$i]->fk_tag);
                    $name = task_api::seltagTag($tagName);
                    if (!empty($name->data)) {
                        $newName[$i]['name'] = $name->data[0]->name;
                        $newName[$i]['pk_tag'] = $name->data[0]->pk_tag;
                        $newList['tag'] = $newName;
                    }

                }

            }
        }
        $path = "/task.publishTask.taskDetail";
        $path_page = utility_tool::getUrl($path);
        /**    列表展示   1,2  **/
        /*列表展示1   已提交 已批改+未批改
         *  通过作业ID 查询 所有学生 答题列表  未批改 取第一条 如果没有
         *  已提交 未批改
         */

        if (!empty($taskDetail->result->data)) {
            $pk_task = array('fk_task' => $taskDetail->result->data[0]->pk_task, 'page' => $page, 'pageSize' => 200, 'type' => 1);
            $selTaskList = task_api::getStudentAllTask($pk_task);
            //排除desc为空的数据
            foreach ($selTaskList->data->items as $k1 => $v1) {
                if (($v1->status == 4) || ($v1->status == -1)) {
                    unset($selTaskList->data->items[$k1]);
                }
            }
            rsort($selTaskList->data->items);
            if (!empty($selTaskList->data->items)) {
                for ($i = 0; $i < count($selTaskList->data->items); $i++) {
                    //已经批改
                    if ($selTaskList->data->items[$i]->status == 2) {
                        $alealy = array('fk_task_student' => $selTaskList->data->items[$i]->pk_task_student, 'status' => 1);
                        $taskListAlealy = task_api::getStudentAllTaskAlealy($alealy);
                        //教师评论整合数组
                        if ($taskListAlealy->result->data) {
                            $selTaskList->data->items[$i]->level = $taskListAlealy->result->data[0]->level;
                        }
                    } else {
                        $selTaskList->data->items[$i]->level = '';
                    }
                }
                for ($j = 0; $j < count($selTaskList->data->items); $j++) {
                    //判断是否提交超时
                    //查询task 作业最后提交时间
                    $wheretime = array('pk_task' => $selTaskList->data->items[$j]->fk_task);
                    $taskInfo = task_api::getTaskDetail($wheretime);
                    //追加  提交超时 字段
                    if ($selTaskList->data->items[$j]->create_time >= $taskInfo->result->data[0]->end_time) {
                        $selTaskList->data->items[$j]->time_status = "迟交";
                    } else {
                        $selTaskList->data->items[$j]->time_status = "";
                    }
                    //学生Id 查询学生 Name
                    $selStuName = task_api::getTeacherName($selTaskList->data->items[$j]->fk_user_student);
                    $selTaskList->data->items[$j]->student_name = !empty($selStuName->data[0]->real_name) ? $selStuName->data[0]->real_name : (!empty($selStuName->data[0]->name) ? $selStuName->data[0]->name : "未设置");
                }
            }

            unset($selTaskList->result);//已经提交作业学生
            $newList['commitList'] = $selTaskList;
            $newList['commitList']->data->pathPage = $path_page;

            /*  显示列表2 未提交作业学生信息
             *  获取 当前老师 所代班级 课程 下的学生信息 与已经提交做对比  筛选出未提交作业学生
             */
            //判断用户是否是本机构下的老师

            //print_r($studentList);die;
            //班级总学生中 删除已经提交作业学生
            //如果学生人数 > 200 将不显示 未提交列表
            $pk_task = array('fk_task' => $taskDetail->result->data[0]->pk_task, 'page' => $page, 'pageSize' => 200, 'status' => 4);
            $selNoCommitList = task_api::getStudentAllTask($pk_task);
            //获取未提交数据
            foreach ($selNoCommitList->data->items as $k1 => $v1) {
                $selStuName = task_api::getTeacherName($v1->fk_user_student);
                $selNoCommitList->data->items[$k1]->teacherName = !empty($selStuName->data[0]->real_name) ? $selStuName->data[0]->real_name : $selStuName->data[0]->name;;

                if (!empty($v1->desc)) {
                    unset($selNoCommitList->data->items[$k1]);
                }
                if ($v1->status == 1 || $v1->status == 2) {
                    unset($selNoCommitList->data->items[$k1]);
                }
            }
            rsort($selNoCommitList->data->items);
            if (!empty($selNoCommitList->data->items)) {
                if (count($selNoCommitList->data->items) < 200) {
                    //未提交学生列表分页展示
                    $commitListSum = count($selNoCommitList->data->items);
                    //每页20条
                    $pageend = 20 * $page;
                    $pagestart = $pageend - 20;

                    // $newList['noCommitList']['data'] = array_slice($selNoCommitList->data->items,$pagestart,$pageend);
                    $newList['noCommitList']['data'] = $selNoCommitList->data;
                    $newList['noCommitList']['page']['total'] = $commitListSum;
                    $newList['noCommitList']['page']['page'] = $page;
                    $newList['noCommitList']['page']['pageSize'] = 20;
                    $newList['noCommitList']['page']['pathPage'] = $path_page;
                    $newList['noCommitList']['page']['totalPage'] = ceil($commitListSum / 20);

                } else {
                    $studentList['noCommitList'] = '无数据';
                }
            }
        }
        //print_r($newList);die;
        $result->result->code = 200;
        $result->result->msg = "success";
        $result->result->data = $newList;
        return $result;
        //$studentList 未提交作业信息

    }

    //教师修改作业前展示   学生提交作业前展示
    public function pageupdateTaskShow(){
        $ret = new stdclass;
        $ret->result = new stdclass;
        //通过作业ID 查看 待批改作业详情
        if (empty($_GET['pk_task'])) {
            $ret->result->code = -1;
            $ret->result->msg = "参数错误";
            return $ret;
        }
        $param = array('pk_task' => $_GET['pk_task']);
        $taskDetail = task_api::getTaskDetail($param);
        if (empty($taskDetail->result->data)) {
            //此页面已经删除
            $ret->result->code = -102;
            $ret->result->msg = "delete";
            return $ret;
        }
        if ($taskDetail->result->data[0]->status == -1) {
            //此页面已经删除
            $ret->result->code = -102;
            $ret->result->msg = "delete";
            return $ret;
        }
        $act = !empty($_GET["act"]) ? $_GET["act"] : '';
        if ($taskDetail->result->data[0]->status == 1 && empty($act)) {
            //作业已经发布，不能修改
            return json_encode(array('code' => -101, 'msg' => 'notFount'));
        }
        if (!empty($taskDetail->result->data)) {
            $teacherInfo = task_api::getTeacherName($taskDetail->result->data[0]->fk_user_teacher);
            $newList['data'] = $taskDetail->result->data[0];
            $newList['data']->create_time = date('m月d日 H:i', strtotime($newList['data']->create_time));
            $newList['data']->end_time = date('Y-m-d H:i', strtotime($newList['data']->end_time));
            $newList['data']->teacherName = !empty($teacherInfo->data[0]->real_name) ? $teacherInfo->data[0]->real_name : (!empty($teacherInfo->data[0]->name) ? $teacherInfo->data[0]->name : "未设置");
            //查询图片
            $thumbCondition = $param = array('object_id' => $taskDetail->result->data[0]->pk_task, 'object_type' => 1, 'status' => 1);
            $taskDetailThumb = task_api::getTaskDetailThumb($thumbCondition);
            $newList['thumb'] = $taskDetailThumb->result->data;
            if (!empty($newList['thumb'])) {
                foreach ($newList['thumb'] as $value) {
                    $value->src_big = utility_cdn::file($value->thumb_big);
                    $value->src_mall = utility_cdn::file($value->thumb_small);
                }
            }
            //查询附件
            $attachCondition = $param = array('object_id' => $taskDetail->result->data[0]->pk_task, 'object_type' => 1, 'status' => 1);
            $taskDetailAttach = task_api::getTaskDetailAttach($attachCondition);
            $newList['attach'] = $taskDetailAttach->result->data;
            if (!empty($newList['attach'])) {
                foreach ($newList['attach'] as $val) {
                    $val->src_attach = utility_cdn::file($val->file);
                }
            }

            //查询标签
            $attachCondition = $param = array('fk_task' => $taskDetail->result->data[0]->pk_task);
            $tag = task_api::getTaskDetailTag($attachCondition);
            if (!empty($tag->result->data)) {
                for ($i = 0; $i < count($tag->result->data); $i++) {
                    //查询标签名字
                    $tagName = array('pk_tag' => $tag->result->data[$i]->fk_tag);
                    $name = task_api::seltagTag($tagName);
                    if (!empty($name->data)) {
                        $newName[$i]['name'] = $name->data[0]->name;
                        $newName[$i]['pk_tag'] = $name->data[0]->pk_tag;
                        $newList['tag'] = $newName;
                    }

                }

            }

            //查询班级  课程信息
            $courseId = array('pk_course' => $taskDetail->result->data[0]->fk_course);
            $classId = array('pk_class' => $taskDetail->result->data[0]->fk_class);
            $courseInfo = task_api::getCourseOneName($courseId);
            if (!empty($courseInfo->result)) {
                $classInfo = task_api::getClassInfo($classId);
                if (!empty($classInfo->result->data->items)) {
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


    //修改作业    未发布
    public function pageupdateTask(){
        //权限
        $this->Power(self::$orgInfo->oid, $this->user['uid']);
        $info['pk_task'] = !empty($_POST['pk_task']) ? $_POST['pk_task'] : '';
        $info['desc'] = !empty($_POST['desc']) ? $_POST['desc'] : '';
        $info['start_time'] = !empty($_POST['start_time']) ? $_POST['start_time'] : '';
        $info['end_time'] = !empty($_POST['end_time']) ? $_POST['end_time'] : '';
        $info['taskImages'] = !empty($_POST['taskImages']) ? $_POST['taskImages'] : '';
        $info['attachName'] = !empty($_POST['attachName']) ? $_POST['attachName'] : '';
        $info['attachType'] = !empty($_POST['attachType']) ? $_POST['attachType'] : '';
        $info['attachFile'] = !empty($_POST['attachFile']) ? $_POST['attachFile'] : '';
        $info['removeImageId'] = !empty($_POST['removeImageId']) ? $_POST['removeImageId'] : '';
        $info['removeFileId'] = !empty($_POST['removeFileId']) ? $_POST['removeFileId'] : '';
        $info['tags'] = !empty($_POST['tags']) ? $_POST['tags'] : '';
        $param = array('pk_task' => $_POST['pk_task']);
        $taskDetail = task_api::getTaskDetail($param);
        if (empty($taskDetail->result->data)) {
            echo json_encode(array('code' => -101, 'msg' => 'notFount'));
            exit;
        }
        //验证
        $this->checkParam($info);
        //通过作业ID 查询 班级id 课程id
        $nowtime = date("Y-m-d H:i:s", time());
        $params = array(
            'pk_task' => $info['pk_task'],
            // 'fk_course'=>$info['fk_course'],
            //'fk_class'=>$info['fk_class'],
            'fk_user_teacher' => $this->user['uid'],
            'status' => '0', //未发布
            'desc' => $info['desc'],
            'create_time' => $nowtime,
            'start_time' => $info['start_time'],
            'end_time' => $info['end_time'],

        );
        //t_task修改作业表
        $PublishTask = task_api::updatePublishTask($params);
        $takImages = $info['taskImages'];
        //修改图片表
        $taskImages = $info['taskImages'];
        //图片表
        if (!empty($taskImages)) {
            for ($i = 0; $i < count($taskImages); $i++) {
                $width = !empty($taskImages[$i]["small_width"]) ? $taskImages[$i]["small_width"] : 0;
                $height = !empty($taskImages[$i]["small_height"]) ? $taskImages[$i]["small_height"] : 0;
                $imgCondition = array(
                    'thumb_big' => $taskImages[$i]['big'],
                    'thumb_small' => $taskImages[$i]['small'],
                    'status' => '1', //正常
                    'createtime' => $nowtime,
                    'object_id' => $info['pk_task'],
                    'object_type' => 1, //教师发布1 学生提交2 教师批改3
                    'small_width' => $width,
                    'small_height' => $height,
                );
                $addTaskImg = task_api::addPublishTaskImg($imgCondition);
            }
        }
        if (!empty($info['removeImageId'])) {
            $removeImageIdStr = implode(',', $info['removeImageId']);
            $imgDelCondition = array(
                'imageIdStr' => $removeImageIdStr,
            );
            $delImg = task_api::batchDelImage($imgDelCondition);
        }

        //附件表
        $attachName = $info['attachName'];
        $attachName = explode(',', $attachName);
        $attachType = $info['attachType'];
        $attachType = explode(',', $attachType);
        $attachFile = $info['attachFile'];
        $attachFile = explode('&', $attachFile);
        if (!empty($attachName[0]) && !empty($attachType[0]) && !empty($attachFile[0])) {
            for ($i = 0; $i < count($attachName); $i++) {
                $attachCondition = array(
                    'name' => $attachName[$i],
                    'file' => $attachFile[$i],
                    'status' => '1', //正常
                    'type' => $attachType[$i],
                    'create_time' => $nowtime,
                    'object_id' => $info['pk_task'],
                    'object_type' => 1,
                );
                $addTaskAttach = task_api::addPublishTaskAttach($attachCondition);
            }
        }
        if (!empty($info['removeFileId'])) {
            $removeFileIdStr = implode(',', $info['removeFileId']);
            $fileDelCondition = array(
                'attachIdStr' => $removeFileIdStr,
            );
            $delFile = task_api::batchDelImage($removeFileIdStr);
        }
        /*  标签表 t_tag 库  t_course 库
         *  t_tag t_tag_belong_group
         *  t_mapping_tag_task   t_mapping_tag_task_student
         */
        //如果tag为空  t_tag tag_belong_group添加数据
        if(!empty($info['tags'])){
        $tags = $info['tags'];
        $tags = explode(',', $tags);
        for ($i = 0; $i < count($tags); $i++) {
            //查询标签是否存在
            $new_param = array('name' => $tags[$i]);
            $sel_tag = task_api::seltagTag($new_param);
            if (empty($sel_tag->data)) {
                $where = array(
                    'fk_user' => self::$orgInfo->oid,
                    'name' => $tags[$i],
                    'desc' => '',
                    'status' => '0',
                );
                //添加 t_tag
                $addTag = task_api::addTagTag($where);
                $params = array(
                    'fk_tag' => $addTag->data,
                    'fk_group' => '4',
                    'status' => '0',
                );
                //添加 t_tag_belong_group
                $addTagBelong = task_api::addTagBelongGroup($params);
                //添加c_ourse  t_mapping_tag_task
                $params = array(
                    'fk_group' => '4',
                    'fk_tag' => $addTag->data,
                    'fk_task' => $info['pk_task'],
                    'status' => '0',
                );
                $addMappingTag = task_api::addMappingTag($params);
            } else {
                //添加c_ouurse  t_mapping_tag_task
                $params = array(
                    'fk_group' => '4',
                    'fk_tag' => $sel_tag->data[0]->pk_tag,
                    'fk_task' => $info['pk_task'],
                    'status' => '0',
                );
                $addMappingTag = task_api::addMappingTag($params);
            }
        }
        }
        echo json_encode(array('code' => 200, 'msg' => 'success'));
    }

    //教师已批改作业展示   学生已提交作业展示
    public function pagetaskShow(){

        //通过作业Id 进行查询数据
        //批改结果  教师批改 有标签 查询批改信息
        $ret = new stdclass;
        $ret->result = new stdclass;
        if (empty($_GET['fk_task_student'])) {
            $ret->result->code = -1;
            $ret->result->msg = "参数错误";
            return $ret;
        }
        //权限如果是学生登陆 判断改作业是否是该学生  老师跳过
        //是否是机构下老师
        $special = user_api::getTeacherSpecial(self::$orgInfo->oid, $this->user['uid']);
        // 讲师1  助教2
        if (empty($special) || ($special->role != 2 && $special->role != 1 && $special->status != 1 && empty($userId))) {
            $pk_task_student_id = array('pk_task_student' => $_GET['fk_task_student']);
            $commitTaskList = task_api::getStudentTaskDetail($pk_task_student_id);
            if ($commitTaskList->data->items[0]->fk_user_student != $this->user['uid']) {
                $ret->result->code = -108;
                $ret->result->msg = "不是此学生作业";
                return $ret;
            }
        }
        $fk_task_student = $_GET['fk_task_student'];
        $pk_task = array('fk_task_student' => $fk_task_student);
        //查询批改作业表
        $replyTaskInfo = task_api::getStudentAllTaskAlealy($pk_task);
        if (!empty($replyTaskInfo->result->data)) {
            $newList['reply']['data'] = $replyTaskInfo->result->data[0];
            $stuReplyId = $replyTaskInfo->result->data[0]->pk_task_student_reply;
            //查询图片 学生提交
            $replythumbCondition = $param = array('object_id' => $stuReplyId, 'object_type' => 3, 'status' => 1);
            $replyTasklThumb = task_api::getTaskDetailThumb($replythumbCondition);
            $newList['reply']['thumb'] = $replyTasklThumb->result->data;
            if (!empty($newList['reply']['thumb'])) {
                foreach ($newList['reply']['thumb'] as $value) {
                    $value->src_big = utility_cdn::file($value->thumb_big);
                    $value->src_mall = utility_cdn::file($value->thumb_small);
                }
            }
            //查询附件 学生提交
            $replyAttachCondition = $param = array('object_id' => $stuReplyId, 'object_type' => 3, 'status' => 1);
            $taskDetailAttach = task_api::getTaskDetailAttach($replyAttachCondition);
            $newList['reply']['attach'] = $taskDetailAttach->result->data;
            if (!empty($newList['reply']['attach'])) {
                foreach ($newList['reply']['attach'] as $value) {
                    $value->src_attach = utility_cdn::file($value->file);
                }
            }
            //标签  查询批改标签
            //查询 t_task_student 取出ID 在查询标签
            //查询标签
            $pk_task_student_id = array('pk_task_student' => $fk_task_student);
            $commitTaskList = task_api::getStudentTaskDetail($pk_task_student_id);
            if (!empty($commitTaskList->data->items[0])) {
                $pk_task_student = $commitTaskList->data->items[0]->pk_task_student;
            }
            $where = array('fk_task_student' => $pk_task_student);
            $replyTaskTag = task_api::replyTaskTag($where);
            if (!empty($replyTaskTag->result->data)) {
                for ($i = 0; $i < count($replyTaskTag->result->data); $i++) {
                    //查询标签名字
                    $tagName = array('pk_tag' => $replyTaskTag->result->data[$i]->fk_tag);
                    $name = task_api::seltagTag($tagName);
                    if (!empty($name->data[0])) {
                        $newName[$i]['name'] = $name->data[0]->name;
                        $newName[$i]['pk_tag'] = $name->data[0]->pk_tag;
                        $newList['reply']['tag'] = $newName;
                    } else {
                        $newList['reply']['tag'] = '';
                    }
                }
            }
        }
        //提交内容  学生提交  无标签 查询提交信息
        //查询提交作业表
        $pk_task_student_id = array('pk_task_student' => $fk_task_student);
        $commitTaskList = task_api::getStudentTaskDetail($pk_task_student_id);
        if (!empty($commitTaskList->data->items[0])) {
            $pk_task_student = $commitTaskList->data->items[0]->pk_task_student;
            //学生提交内容
            $newList['commit']['data'] = $commitTaskList->data->items[0];
            //查询图片 学生提交
            $commitThumbCondition = $param = array('object_id' => $pk_task_student, 'object_type' => 2, 'status' => 1);
            $taskDetailThumb = task_api::getTaskDetailThumb($commitThumbCondition);
            $newList['commit']['thumb'] = $taskDetailThumb->result->data;
            if (!empty($newList['commit']['thumb'])) {
                foreach ($newList['commit']['thumb'] as $value) {
                    $value->src_big = utility_cdn::file($value->thumb_big);
                    $value->src_mall = utility_cdn::file($value->thumb_small);
                }
            }
            //查询附件 学生提交
            $commitAttachCondition = $param = array('object_id' => $pk_task_student, 'object_type' => 2, 'status' => 1);
            $taskDetailAttach = task_api::getTaskDetailAttach($commitAttachCondition);
            $newList['commit']['attach'] = $taskDetailAttach->result->data;
            if (!empty($newList['commit']['attach'])) {
                foreach ($newList['commit']['attach'] as $value) {
                    $value->src_attach = utility_cdn::file($value->file);
                }
            }
        }
        //作业内容  教师发布  查询发布信息
        if (!empty($commitTaskList->data->items)) {
            $param = array('pk_task' => $commitTaskList->data->items[0]->fk_task);
            $publishTaskDetail = task_api::getTaskDetail($param);
            //教师名字
            $teacher_name = task_api::getTeacherName($publishTaskDetail->result->data[0]->fk_user_teacher);
            $newList['publish']['data'] = $publishTaskDetail->result->data[0];
            //查询图片
            $publishThumbCondition = $param = array('object_id' => $publishTaskDetail->result->data[0]->pk_task, 'object_type' => 1, 'status' => 1);
            $taskDetailThumb = task_api::getTaskDetailThumb($publishThumbCondition);
            $newList['publish']['thumb'] = $taskDetailThumb->result->data;
            if (!empty($newList['publish']['thumb'])) {
                foreach ($newList['publish']['thumb'] as $value) {
                    $value->src_big = utility_cdn::file($value->thumb_big);
                    $value->src_mall = utility_cdn::file($value->thumb_small);
                }
            }
            //查询附件
            $publishAttachCondition = $param = array('object_id' => $publishTaskDetail->result->data[0]->pk_task, 'object_type' => 2, 'status' => 1);
            $taskDetailAttach = task_api::getTaskDetailAttach($publishAttachCondition);
            $newList['publish']['attach'] = $taskDetailAttach->result->data;
            if (!empty($newList['publish']['attach'])) {
                foreach ($newList['publish']['attach'] as $value) {
                    $value->src_attach = utility_cdn::file($value->file);
                }
            }
            //查询标签
            $publishAttachCondition = $param = array('fk_task' => $publishTaskDetail->result->data[0]->pk_task);
            $publishTag = task_api::getTaskDetailTag($publishAttachCondition);
            if (!empty($publishTag->result->data)) {
                for ($i = 0; $i < count($publishTag->result->data); $i++) {
                    //查询标签名字
                    $tagName = array('pk_tag' => $publishTag->result->data[$i]->fk_tag);
                    $name = task_api::seltagTag($tagName);
                    if (!empty($name->data)) {
                        $newName[$i]['name'] = $name->data[0]->name;
                        $newName[$i]['pk_tag'] = $name->data[0]->pk_tag;
                        $newList['publish']['tag'] = $newName;
                    }
                }
            }

            //查询学生信息  班级信息
            $param = array('pk_task' => $commitTaskList->data->items[0]->fk_task);
            $taskDetail = task_api::getTaskDetail($param);
            if (!empty($taskDetail->result->data[0])) {
                $studentName = task_api::getTeacherName($commitTaskList->data->items[0]->fk_user_student);
                if (!empty($studentName->data[0])) {
                    //班级 课程名称
                    $courseId = array('pk_course' => $taskDetail->result->data[0]->fk_course);
                    $classId = array('pk_class' => $taskDetail->result->data[0]->fk_class);
                    $courseInfo = task_api::getCourseOneName($courseId);
                    if (!empty($courseInfo->result)) {
                        $classInfo = task_api::getClassInfo($classId);
                        if (!empty($classInfo->result->data->items)) {
                            $taskInfo = array();
                            $taskInfo['className'] = $classInfo->result->data->items[0]->name;
                            $taskInfo['pk_class'] = $classInfo->result->data->items[0]->pk_class;
                            $taskInfo['pk_course'] = $courseInfo->result[0]->pk_course;
                            $taskInfo['title'] = $courseInfo->result[0]->title;
                            $taskInfo['create_time'] = date("m月d日 H:i", strtotime(substr($taskDetail->result->data[0]->start_time, 0, 16)));
                            $taskInfo['end_time'] = date("m月d日 H:i", strtotime(substr($taskDetail->result->data[0]->end_time, 0, 16)));
                            $taskInfo['StudentName'] = !empty($studentName->data[0]->real_name) ? $studentName->data[0]->real_name : (!empty($studentName->data[0]->name) ? $studentName->data[0]->name : "未设置");
                            $taskInfo['teacherName'] = !empty($teacher_name->data[0]->real_name) ? $teacher_name->data[0]->real_name : (!empty($teacher_name->data[0]->name) ? $teacher_name->data[0]->name : "未设置");
                            $taskInfo['StudentId'] = $studentName->data[0]->pk_user;
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

    //删除作业
    public function pagedeleteTask(){
        //权限
        $this->Power(self::$orgInfo->oid, $this->user['uid']);
        $taskId = !empty($_POST['pk_task']) ? $_POST['pk_task'] : '';
        $ret = new stdclass;
        $ret->result = new stdclass;
        if (empty($taskId)) {
            $ret->result->code = -1;
            $ret->result->msg = "参数错误";
            return $ret;
        }
        //查询作业表
        $param = array('pk_task' => $taskId);
        $taskDetail = task_api::getTaskDetail($param);
        if (!empty($taskDetail->result->data)) {
            //删除作业表
            $taskDel = task_api::getdelTask($param);
        }
        $pk_task = array('fk_task' => $taskId);
        $commitTaskList = task_api::getStudentAllTask($pk_task);
        if (!empty($commitTaskList->data->items)) {
            //删除作业表
            $taskCommitDel = task_api::getdelCommitTask($pk_task);
        }
        //删除批改作业表
        $replyTaskList = task_api::getStudentAllTaskAlealy($pk_task);
        if (!empty($replyTaskList->result->data)) {
            //删除作业表
            $taskReplyDel = task_api::getdelReplyTask($pk_task);
        }
        $ret->result->code = 200;
        $ret->result->msg = "success";
        return $ret;
    }

    //发布作业
    //check 修改作业
    public function publishTaskCheck($info){
        //权限
        $this->Power(self::$orgInfo->oid, $this->user['uid']);
        if (empty((int)$info['fk_course'])) {
            echo json_encode(array('code' => -1, 'msg' => '课程ID为空'));
            exit;
        }
        if (empty(trim($info['fk_class']))) {
            echo json_encode(array('code' => 2, 'msg' => '班级Id为空'));
            exit;
        }
        if (empty(trim($info['start_time']))) {
            echo json_encode(array('code' => -5, 'msg' => '开始时间为空'));
            exit;
        }
        if (strtotime($info['start_time']) < time()) {
            echo json_encode(array('code' => -6, 'msg' => '开始大于当前时间'));
            exit;
        }
        if (empty(trim($info['end_time']))) {
            echo json_encode(array('code' => -7, 'msg' => '结束时间为空'));
            exit;
        }
        if ($info['end_time'] < $info['start_time']) {
            echo json_encode(array('code' => -8, 'msg' => '开始时间为空'));
            exit;
        }
        if (!empty($info['tags'])) {
            //标签判断
            $tags = explode(',', $info['tags']);
            if (count($tags) > 5) {
                echo json_encode(array('code' => -10, 'msg' => '标签数最多为5'));
                exit;
            }
            for ($i = 0; $i < count($tags); $i++) {
                if (preg_match("/[\x7f-\xff]/", $tags[$i])) {
                    //exit('有中文!');
                    if (strlen($tags[$i]) > 60) {
                        echo json_encode(array('code' => -11, 'msg' => '每个标签中文最多为20字符'));
                        exit;
                    }
                } else {
                    //exit('没有中文!');
                    if (strlen($tags[$i]) > 40) {
                        echo json_encode(array('code' => -12, 'msg' => '每个标签数字英文最多为40字符'));
                        exit;
                    }
                }
            }
        }
    }


    //一键催交
    public function pagetaskMessage(){
        $ret = new stdclass;
        $ret->result = new stdclass;
        $studentId = !empty($_POST['studentId']) ? $_POST['studentId'] : '';
        if (empty($studentId)) {
            $ret->result->code = -100;
            $ret->result->msg = "学生Id为空！";
            return $ret;
        }
        $pk_task = !empty($_POST['pk_task']) ? $_POST['pk_task'] : '';
        if (empty($pk_task)) {
            $ret->result->code = -1;
            $ret->result->msg = "作业ID为空";
            return $ret;
        }
        $param = array('pk_task' => $pk_task);
        $taskDetail = task_api::getTaskDetail($param);
        $courseId = $taskDetail->result->data[0]->fk_course;
        $userTeacher = $taskDetail->result->data[0]->fk_user_teacher;
        $startTime = $taskDetail->result->data[0]->start_time;
        $endTime = $taskDetail->result->data[0]->end_time;
        //课程id查询课程名称
        $courseInfo = task_api::getCourseOneName(array('pk_course' => $courseId));
        $courseName = '';
        if (!empty($courseInfo->result[0])) {
            $courseName = $courseInfo->result[0]->title;
        }
        //查询教师信息
        $teacherName = '';
        $teacherInfo = task_api::getTeacherName($userTeacher);
        if (!empty($teacherInfo->data[0])) {
            $teacherName = $teacherInfo->data[0]->name;
        }
        $subdomain = user_organization::getSubdomainByUid(self::$orgOwner);
        $subdomain = user_organization::course_domain($subdomain->subdomain);
        if (!empty($courseName) && !empty($teacherName)) {
            $content = '【催交作业】[' . $courseName . ']的' . $teacherName . date("m-d H:i", strtotime($startTime)) . '发布的作业还没有提交，截止时间' . date("m-d H:i", strtotime($endTime)) . ',快去写作业吧。<a href="//' . $subdomain . '/task.commitTask.studentCommitTask/?taskId=' . $_POST['pk_task'] . '" target="_blank">【写作业】</a>';
        } else {
            $content = "发布的作业还没有提交,快去写作业吧。";
        }
        //单个催交
        $param = array(
            'userTo' => $studentId,
            'userFrom' => 0,
            'content' => "$content",
            'title' => "催交作业",
            'msgType' => 10022,
        );
        $ins = task_api::taskMessage($param);
        //手机推送消息(云课2.0)
        $ymData = [
            "title" => "催交作业",
            "text" => $content,
            "to_uid" => $studentId,
            "organization" => 0,
            "message_type" => message_type::TEACHER_CALL_TASK
        ];
        common_api::addYmMessage($ymData);
        $ret->result->code = 200;
        $ret->result->msg = "success";
        return $ret;
    }

    public function  pageimage(){
        $this->render("task/img.html");
    }

    public function objarray_to_array($obj){
        $ret = array();
        foreach ($obj as $key => $value) {
            if (gettype($value) == "array" || gettype($value) == "object") {
                $ret[$key] = $this->objarray_to_array($value);
            } else {
                $ret[$key] = $value;
            }
        }
        return $ret;
    }

    //数组转对象
    function array2object($array){
        if (is_array($array)) {
            $obj = new StdClass();
            foreach ($array as $key => $val) {
                $obj->$key = $val;
            }
        } else {
            $obj = $array;
        }
        return $obj;
    }

    //删除图片
    public function pagedeleteImage(){
        $ret = new stdclass;
        $ret->result = new stdclass;
        $userId = $this->user['uid'];
        $pk_task = !empty($_POST['pk_thumb']) ? $_POST['pk_thumb'] : '';
        if (empty($pk_task)) {
            $ret->result->code = -1;
            $ret->result->msg = "图片ID为空";
            return $ret;
        }
        $param = array('pk_thumb' => $_POST['pk_thumb']);
        $imageInfo = task_api::delImage($param);
        if ($imageInfo == 1) {
            $ret->result->code = 200;
            $ret->result->msg = "successs";
            return $ret;
        } else {
            $ret->result->code = -1;
            $ret->result->msg = "error";
            return $ret;
        }
    }

    //删除附件
    public function pagedeleteAttach(){
        $ret = new stdclass;
        $ret->result = new stdclass;
        $userId = $this->user['uid'];
        $pk_task = !empty($_POST['pk_attach']) ? $_POST['pk_attach'] : '';
        if (empty($pk_task)) {
            $ret->result->code = -1;
            $ret->result->msg = "附件ID为空";
            return $ret;
        }
        $param = array('pk_attach' => $_POST['pk_attach']);
        $imageInfo = task_api::delAttach($param);
        if ($imageInfo == 1) {
            $ret->result->code = 200;
            $ret->result->msg = "successs";
            return $ret;
        } else {
            $ret->result->code = -1;
            $ret->result->msg = "error";
            return $ret;
        }
    }

    //权限
    public function Power($owerId, $userId){
        //是否是机构下老师
        $special = user_api::getTeacherSpecial($owerId, $userId);
        //老师所代班级
        $params = array("user_id" => $owerId, "user_class_id" => $userId);
        $teacherClassList = course_api::classlistbycond($params);
        // 讲师1  助教2
        if (empty($special) || ($special->role != 2 && $special->role != 1 && $special->status != 1 && empty($userId))) {
            $this->render('task/task.notFount.html');
        }
    }

    public function pageFlash(){
        $this->render("org/iframe.homeworkEditer.html");
    }

    //删除标签
    public function  pageDelTag(){
        $tagId = !empty($_POST['pk_tag']) ? $_POST['pk_tag'] : '';
        $ret = new stdclass;
        $ret->result = new stdclass;
        if (empty($tagId)) {
            $ret->result->code = -1;
            $ret->result->msg = "标签ID为空";
            return $ret;
        }
        //删除标签
        $tag = task_api::DelTag(array('pk_tag' => $tagId));
        if ($tag == 1) {
            $ret->result->code = 200;
            $ret->result->msg = "success";
            return $ret;
        } elseif ($tag == 2) {
            $ret->result->code = -2;
            $ret->result->msg = "error";
            return $ret;
        }
    }

    public function pageNotFound(){
        $this->render('task/task.notFount.html');
    }

    public function pageDelete(){
        return $this->render("task/task.delete.html");
    }


    //数组去重
    function array_unique_fb($array2D){
        $tmp_array = array();
        $new_array = array();
        if (is_array($array2D)) {
            foreach ($array2D as $k => $val) {
                $hash = md5(json_encode($val));
                if (in_array($hash, $tmp_array)) {
                } else {
                    $tmp_array[] = $hash;
                    $new_array[] = $val;
                }
            }
        }
        return $new_array;
    }

}
