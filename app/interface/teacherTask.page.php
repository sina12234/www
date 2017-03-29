<?php

/**
 * @desc   APP教师端作业
 * @author Zhouyu
 * @date   2016/12/22
 */
class interface_teacherTask extends interface_base{
    private static $pageSize = 20;

    /*
     * 教师作业列表
     * @param page
     * @param status  0 未发布 1 带批改 2 已批改 3 全部
     * @param uId
     * @return array
     * */
    public function pageTaskList(){
        $page = !empty($this->paramsInfo['params']['page']) ? $this->paramsInfo['params']['page'] : 1;
        $status = isset($this->paramsInfo['params']['status']) ? $this->paramsInfo['params']['status'] : 3;
        $userId = !empty($this->paramsInfo['params']['uId']) ? $this->paramsInfo['params']['uId'] : '';
        if (empty($userId)) {
            return interface_func::setMsg(1001);
        }
        $pageNum = array(
            'pageNum' => self::$pageSize,
            'page' => $page,
            'status' => $status,
            'uId' => $userId,
        );
        $taskList = task_api::getTaskList($pageNum);
        if (empty($taskList->result->data->items)) {
            //作业列表为空
            return interface_func::setMsg(1054);
        }
        foreach ($taskList->result->data->items as $key => $val) {
            $val->formatTimeWeek = '星期' . utility_time::cnweek($val->create_time);
            $val->desc = trim($val->desc);
            $thumbCondition = $param = array('object_id' => $val->pk_task, 'object_type' => 1);
            $taskDetailThumb = task_api::getTaskDetailThumb($thumbCondition);
            if (!empty($taskDetailThumb->result->data)) {
                $val->thumb = "[图片作业]";
            } else {
                $val->thumb = "";
            }
            //查询附件 type->1 取附件   2 取语音
            $attachCondition = $param = array('object_id' => $val->pk_task, 'object_type' => 1, 'type' => 1);
            $taskDetailAttach = task_api::getTaskDetailAttach($attachCondition);
            if (!empty($taskDetailAttach->result->data)) {
                $val->attach = "[文件作业]";
            } else {
                $val->attach = "";
            }
        }
        $newArray = array();
        foreach ($taskList->result->data->items as $y => $j) {
            $newArray[$y]['pkTask'] = $j->pk_task;
            $newArray[$y]['fkCourse'] = $j->fk_course;
            $newArray[$y]['fkClass'] = $j->fk_class;
            $newArray[$y]['fkUserTeacher'] = $j->fk_user_teacher;
            $newArray[$y]['createTime'] = $j->create_time;
            $newArray[$y]['desc'] = $j->desc;
            $newArray[$y]['startTime'] = $j->start_time;
            $newArray[$y]['endTime'] = $j->end_time;
            $newArray[$y]['studentCount'] = $j->student_count;
            $newArray[$y]['markCount'] = $j->mark_count;
            $newArray[$y]['pkClass'] = $j->pk_class;
            $newArray[$y]['name'] = $j->name;
            $newArray[$y]['userTotal'] = $j->user_total;
            $newArray[$y]['title'] = $j->title;
            $newArray[$y]['formatTimeWeek'] = $j->formatTimeWeek;
            $newArray[$y]['thumb'] = $j->thumb;
            $newArray[$y]['attach'] = $j->attach;
            $newArray[$y]['status'] = $j->status;
        }
        $taskList->result->data->items = $newArray;
        //年/月/日  重组数组
        foreach ($taskList->result->data->items as $item) {
            $res[date("Y-m-d", strtotime($item['createTime']))]["'" . $item['pkTask'] . "'"] = $item;
        }
        $newArray = array();
        $newArray1 = array();
        $n = -1;
        foreach ($res as $k => $v) {
            $n++;
            $newArray[$n]['day'] = $k;
            $num = -1;
            foreach ($v as $k1 => $v1) {
                $num++;
                $newArray1[$num] = $v1;
            }
            unset($num);
            $newArray[$n]['days'] = $newArray1;
            unset($newArray1);
        }
        unset($taskList->result->data->items);
        $ret = array();
        $ret['page'] = $taskList->result->data->page;
        $ret['pageSize'] = $taskList->result->data->pageSize;
        $ret['limit'] = $taskList->result->data->limit;
        $ret['totalPage'] = $taskList->result->data->totalPage;
        $ret['totalSize'] = $taskList->result->data->totalSize;
        $ret['data'] = $newArray;
        return interface_func::setData($ret);
    }

    /*
     * 课程 & 班级信息 列表  搜索
     * */
    public function pageCourseClass(){
        $userId = !empty($this->paramsInfo['params']['uId']) ? (int)$this->paramsInfo['params']['uId'] : '';
        $page = !empty($this->paramsInfo['params']['page']) ? (int)$this->paramsInfo['params']['page'] : 1;
        $length = !empty($this->paramsInfo['params']['length']) ? (int)$this->paramsInfo['params']['length'] : 50;
        $keywords = !empty($this->paramsInfo['params']['keywords']) ? trim($this->paramsInfo['params']['keywords']) : '';
        if (empty($userId)) return $this->setMsg(1000);
        //查询有排课讲师的课程列表
        $teacherCourseRes = course_plan_api::getCourseListByTeacherId($userId, $page, $length);
        if ($teacherCourseRes->code == 3002) return $this->setMsg(3002);
        foreach ($teacherCourseRes->result as $val) {
            $courseIdArr[] = $val->fk_course;
        }
        $courseIds = implode(',', $courseIdArr);
        $params = [
            'q' => ['course_id' => $courseIds],
            'f' => ['course_id', 'title', 'class'],
            'p' => $page,
            'pl' => $length,
            'ob' => ['start_time' => 'desc']
        ];
        if (!empty($keywords)) {
            $params['q']['search_field'] = $keywords;
        }
        $seekCourse = seek_api::seekCourse($params);
        if (empty($seekCourse->data)) return $this->setMsg(3002);
        foreach ($seekCourse->data as $val) {
            if (!empty($val->class)) {
                foreach ($val->class as $v) {
                    $class[$val->course_id][] = [
                        'fkClass' => $v->class_id,
                        'fkCourse' => $val->course_id,
                        'name' => $v->name
                    ];
                }
            }
            $data[] = [
                'title' => $val->title,
                'courseId' => $val->course_id,
                'className' => !empty($class[$val->course_id]) ? $class[$val->course_id] : array()
            ];
        }
        return $this->setData($data);
    }


    /*
     * 数组去重
     * @param $array2D (array)
     * @return array
     * */
    function arrayUniqueFb($array2D){
        $tmp_array = array();
        $new_array = array();
        if (is_array($array2D)) {
            foreach ($array2D as $k => $val) {
                $hash = md5(json_encode($val));
                if (!in_array($hash, $tmp_array)) {
                    $tmp_array[] = $hash;
                    $new_array[] = $val;
                }
            }
        }
        return $new_array;
    }

    /*
     * 教师发布作业
     * */
    public function pagePublishTask(){
        $info['fkCourse'] = !empty($this->paramsInfo['params']['fkCourse']) ? $this->paramsInfo['params']['fkCourse'] : '';
        $info['fkClass'] = !empty($this->paramsInfo['params']['fkClass']) ? $this->paramsInfo['params']['fkClass'] : '';
        $info['fkUserTeacher'] = !empty($this->paramsInfo['params']['uId']) ? $this->paramsInfo['params']['uId'] : '';
        $info['desc'] = !empty($this->paramsInfo['params']['desc']) ? $this->paramsInfo['params']['desc'] : '';
        $info['startTime'] = !empty($this->paramsInfo['params']['startTime']) ? $this->paramsInfo['params']['startTime'] : '';
        $info['endTime'] = !empty($this->paramsInfo['params']['endTime']) ? $this->paramsInfo['params']['endTime'] : '';
        $info['taskImages'] = !empty($this->paramsInfo['params']['taskImages']) ? $this->paramsInfo['params']['taskImages'] : '';
        //$info['voice'] = !empty($this->paramsInfo['params']['voice']) ? $this->paramsInfo['params']['voice'] : '';
        $info['tags'] = !empty($this->paramsInfo['params']['tags']) ? $this->paramsInfo['params']['tags'] : '';

        //字段验证
        if (empty($info['tags']) || empty($info['fkCourse']) || empty($info['fkClass']) || empty($info['fkUserTeacher']) || empty($info['startTime']) || empty($info['endTime'])) {
            return interface_func::setMsg(1001);
        }
        if (empty($info['desc']) && empty($info['taskImages'])) {
            return interface_func::setMsg(2057);
        }
        if (strtotime($info['startTime']) < time()) {
            return interface_func::setMsg(4004);
        }
        if (count($info['tags']) > 5) {
            return interface_func::setMsg(2061);
        }
        foreach ($info['tags'] as $key => $val) {
            $strNum = utility_tool::stringNum($val['name']);
            //汉字10
            if ($strNum > 20) return interface_func::setMsg(2062);
        }
        if (!empty($info['desc'])) {
            $strNum = utility_tool::stringNum($info['desc']);
            if ($strNum > 2000) return interface_func::setMsg(2063);
        }
        if (!empty($info['taskImages'])) {
            if (count($info['taskImages']) > 10 ) return interface_func::setMsg(2067);
        }
        $nowTime = date("Y-m-d H:i:s", time());
        $params = array(
            'fk_course' => $info['fkCourse'],
            'fk_class' => $info['fkClass'],
            'fk_user_teacher' => $info['fkUserTeacher'],
            'status' => '0', //未发布
            'desc' => trim($info['desc']),
            'create_time' => $nowTime,
            'start_time' => $info['startTime'],
            'end_time' => $info['endTime'],
        );
        //t_task发布作业表
        $addPublishTask = task_api::addPublishTask($params);
        if (!empty($addPublishTask->data)) {
            $taskImages = $info['taskImages'];
            if (!empty($taskImages)) {
                for ($i = 0; $i < count($taskImages); $i++) {
                    $width = !empty($taskImages[$i]["srcMallWidth"]) ? $taskImages[$i]["srcMallWidth"] : 0;
                    $height = !empty($taskImages[$i]["srcMallHeight"]) ? $taskImages[$i]["srcMallHeight"] : 0;
                    $imgCondition = array(
                        'thumb_big' => $taskImages[$i]['thumbBig'],
                        'thumb_small' => $taskImages[$i]['thumbSmall'],
                        'status' => '1', //正常
                        'createtime' => $nowTime,
                        'object_id' => $addPublishTask->data,
                        'object_type' => 1, //教师发布1 学生提交2 教师批改3
                        'small_width' => $width,
                        'small_height' => $height
                    );
                    $addTaskImg = task_api::addPublishTaskImg($imgCondition);
                }
            }
        }
        //标签
        $tags = $info['tags'];
        if (!empty($tags)) {
            for ($i = 0; $i < count($tags); $i++) {
                //查询标签是否存在
                $new_param = array('name' => $tags[$i]['name']);
                $sel_tag = task_api::seltagTag($new_param);
                if (empty($sel_tag->data)) {
                    $where = array(
                        'fk_user' => 0,
                        'name' => $tags[$i]['name'],
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
                        //添加  t_mapping_tag_task
                        $params = array(
                            'fk_group' => '4',
                            'fk_tag' => $addTag->data,
                            'fk_task' => $addPublishTask->data,
                            'status' => '0',
                        );
                        $addMappingTag = task_api::addMappingTag($params);
                    }
                } else {
                    //添加  t_mapping_tag_task
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
        return interface_func::setMsg(0);
    }

    /*
     * 修改作业前展示
     *
     * */
    public function pageUpdateTaskShow(){
        //通过作业ID 查看 待批改作业详情
        $uId = !empty($this->paramsInfo['params']['uId']) ? $this->paramsInfo['params']['uId'] : '';
        $taskId = !empty($this->paramsInfo['params']['taskId']) ? $this->paramsInfo['params']['taskId'] : '';
        if (empty($taskId) || empty($uId)) {
            return interface_func::setMsg(1001);
        }
        $param = array('pk_task' => $taskId);
        $taskDetail = task_api::getTaskDetail($param);
        if (empty($taskDetail->result->data)) {
            //此页面已经删除
            return interface_func::setMsg(1051);
        }
        if ($taskDetail->result->data[0]->status == -1) {
            //此页面已经删除
            return interface_func::setMsg(1051);
        }

        if ($taskDetail->result->data[0]->fk_user_teacher != $uId) {
            return interface_func::setMsg(2060);
        }
        if ($taskDetail->result->data[0]->status == 1) {
            //作业已经发布，不能修改
            return interface_func::setMsg(2059);
        }
        if (!empty($taskDetail->result->data)) {
            $teacherInfo = task_api::getTeacherName($taskDetail->result->data[0]->fk_user_teacher);
            //$newList['data'] = $taskDetail->result->data[0];
            $newList['data'] = new stdclass;
            $newList['data']->pkTask = $taskDetail->result->data[0]->pk_task;
            $newList['data']->fkCourse = $taskDetail->result->data[0]->fk_course;
            $newList['data']->fkClass = $taskDetail->result->data[0]->fk_class;
            $newList['data']->fkUserTeacher = $taskDetail->result->data[0]->fk_user_teacher;
            $newList['data']->status = $taskDetail->result->data[0]->status;
            $newList['data']->desc = $taskDetail->result->data[0]->desc;
            $newList['data']->createTime = $taskDetail->result->data[0]->create_time;
            $newList['data']->startTime = $taskDetail->result->data[0]->start_time;
            $newList['data']->endTime = $taskDetail->result->data[0]->end_time;
            $newList['data']->studentCount = $taskDetail->result->data[0]->student_count;
            $newList['data']->markCount = $taskDetail->result->data[0]->mark_count;
            $newList['data']->createTime = date('m-d H:i', strtotime($newList['data']->createTime));
            $newList['data']->endTime = date('Y-m-d H:i', strtotime($newList['data']->endTime));
            $newList['data']->teacherName = !empty($teacherInfo->data[0]->real_name) ? $teacherInfo->data[0]->real_name : (!empty($teacherInfo->data[0]->name) ? $teacherInfo->data[0]->name : "未设置");
            $newList['data']->smallThumb = interface_func::imgURL($teacherInfo->data[0]->thumb_small);
            //查询图片
            $thumbCondition = $param = array('object_id' => $taskDetail->result->data[0]->pk_task, 'object_type' => 1, 'status' => 1);
            $taskDetailThumb = task_api::getTaskDetailThumb($thumbCondition);
            $newList['thumb'] = array();
            if (!empty($taskDetailThumb->result->data)) {
                $newList['thumb'] = $taskDetailThumb->result->data;
                foreach ($newList['thumb'] as $value) {
                    $value->srcBig = interface_func::imgURL($value->thumb_big);
                    $value->srcMall = interface_func::imgURL($value->thumb_small);
                    $value->smallWidth = $value->small_width;
                    $value->smallHeight = $value->small_height;
                    $value->pkThumb = $value->pk_thumb;
                    unset($value->createtime);
                    unset($value->last_updated);
                    unset($value->status);
                    unset($value->thumb_big);
                    unset($value->thumb_small);
                    unset($value->object_id);
                    unset($value->object_type);
                    unset($value->small_width);
                    unset($value->small_height);
                    unset($value->pk_thumb);
                }
            }
            //查询标签
            $attachCondition = $param = array('fk_task' => $taskDetail->result->data[0]->pk_task);
            $tag = task_api::getTaskDetailTag($attachCondition);
            $newList['tag'] = array();
            if (!empty($tag->result->data)) {
                for ($i = 0; $i < count($tag->result->data); $i++) {
                    //查询标签名字
                    $tagName = array('pk_tag' => $tag->result->data[$i]->fk_tag);
                    $name = task_api::seltagTag($tagName);
                    if (!empty($name->data)) {
                        $newName[$i]['name'] = $name->data[0]->name;
                        $newName[$i]['pkTag'] = $name->data[0]->pk_tag;
                        $newList['tag'] = $newName;
                    }
                }
                rsort($newList['tag']);
            }
            //查询班级  课程信息
            $courseId = array('pk_course' => $taskDetail->result->data[0]->fk_course);
            $classId = array('pk_class' => $taskDetail->result->data[0]->fk_class);
            $courseInfo = task_api::getCourseOneName($courseId);
            $infos = array();
            if (!empty($courseInfo->result)) {
                $classInfo = task_api::getClassInfo($classId);
                if (!empty($classInfo->result->data->items)) {
                    $infos['name'] = $classInfo->result->data->items[0]->name;
                    $infos['pkClass'] = $classInfo->result->data->items[0]->pk_class;
                    $infos['pkCourse'] = $courseInfo->result[0]->pk_course;
                    $infos['title'] = $courseInfo->result[0]->title;
                    $newList['info'] = $infos;
                }
            }
            return interface_func::setData($newList);
        } else {
            return interface_func::setMsg(1);
        }
    }

    /*
     * 修改作业
     * */
    public function pageUpdateTask(){
        $uId = !empty($this->paramsInfo['params']['uId']) ? $this->paramsInfo['params']['uId'] : '';
        $info['pkTask'] = !empty($this->paramsInfo['params']['pkTask']) ? $this->paramsInfo['params']['pkTask'] : '';
        $info['desc'] = !empty($this->paramsInfo['params']['desc']) ? $this->paramsInfo['params']['desc'] : '';
        $info['startTime'] = !empty($this->paramsInfo['params']['startTime']) ? $this->paramsInfo['params']['startTime'] : '';
        $info['endTime'] = !empty($this->paramsInfo['params']['endTime']) ? $this->paramsInfo['params']['endTime'] : '';
        $info['taskImages'] = !empty($this->paramsInfo['params']['taskImages']) ? $this->paramsInfo['params']['taskImages'] : '';
        $info['tags'] = !empty($this->paramsInfo['params']['tags']) ? $this->paramsInfo['params']['tags'] : '';
        //字段验证
        if (empty($uId) || empty($info['pkTask']) || empty($info['startTime']) || empty($info['endTime']) ) {
            return interface_func::setMsg(1001);
        }
        if (empty($info['desc']) && empty($info['taskImages']) && empty($info['tags'])) {
            return interface_func::setMsg(0);
        }
        if (strtotime($info['startTime']) < time()) {
            return interface_func::setMsg(4004);
        }
        if(!empty($info['tags'])){
        if (count($info['tags']) > 5) {
            return interface_func::setMsg(2061);
        }
        foreach ($info['tags'] as $key => $val) {
            $strNum = utility_tool::stringNum($val['name']);
            //汉字10
            if ($strNum > 20) return interface_func::setMsg(2062);
        }
        }
        if (!empty($info['desc'])) {
            $strNum = utility_tool::stringNum($info['desc']);
            if ($strNum > 2000) return interface_func::setMsg(2063);
        }
        if (!empty($info['taskImages'])) {
            if (count($info['taskImages']) > 10 ) return interface_func::setMsg(2067);
        }
        $param = array('pk_task' => $info['pkTask']);
        $taskDetail = task_api::getTaskDetail($param);
        if (empty($taskDetail->result->data)) {
            return interface_func::setMsg(1051);
        }
        //通过作业ID 查询 班级id 课程id
        $nowTime = date("Y-m-d H:i:s", time());
        $params = array(
            'pk_task' => $info['pkTask'],
            'fk_user_teacher' => $uId,
            'status' => '0', //未发布
            'desc' => trim($info['desc']),
            'create_time' => $nowTime,
            'start_time' => $info['startTime'],
            'end_time' => $info['endTime'],

        );
        //t_task修改作业表
        $PublishTask = task_api::updatePublishTask($params);
        $takImages = $info['taskImages'];
        //修改图片表
        $taskImages = $info['taskImages'];
        //图片表
        if (!empty($taskImages)) {
            for ($i = 0; $i < count($taskImages); $i++) {
                $width = !empty($taskImages[$i]["srcMallWidth"]) ? $taskImages[$i]["srcMallWidth"] : 0;
                $height = !empty($taskImages[$i]["srcMallHeight"]) ? $taskImages[$i]["srcMallHeight"] : 0;
                $imgCondition = array(
                    'thumb_big' => $taskImages[$i]['thumbBig'],
                    'thumb_small' => $taskImages[$i]['thumbSmall'],
                    'status' => '1', //正常
                    'createtime' => $nowTime,
                    'object_id' => $info['pkTask'],
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
        for ($i = 0; $i < count($info['tags']); $i++) {
            //查询标签是否存在
            $new_param = array('name' => $info['tags'][$i]['name']);
            $sel_tag = task_api::seltagTag($new_param);
            if (empty($sel_tag->data)) {
                $where = array(
                    'fk_user' => $uId,
                    'name' => $info['tags'][$i]['name'],
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
                    'fk_task' => $info['pkTask'],
                    'status' => '0',
                );
                $addMappingTag = task_api::addMappingTag($params);
            } else {
                //添加c_ouurse  t_mapping_tag_task
                $params = array(
                    'fk_group' => '4',
                    'fk_tag' => $sel_tag->data[0]->pk_tag,
                    'fk_task' => $info['pkTask'],
                    'status' => '0',
                );
                $addMappingTag = task_api::addMappingTag($params);
            }
        }
        return interface_func::setMsg(0);
    }


    /*
     * 删除图片
     * */
    public function pageDeleteImage(){
        $thumbId = !empty($this->paramsInfo['params']['pkThumb']) ? $this->paramsInfo['params']['pkThumb'] : '';
        if (empty($thumbId)) {
            return interface_func::setMsg(1001);
        }
        $param = array('pk_thumb' => $thumbId);
        $imageInfo = task_api::delImage($param);
        if ($imageInfo == 1) {
            return interface_func::setMsg(0);
        } else {
            return interface_func::setMsg(1);
        }
    }


    /*
     * 删除标签
     * */
    public function  pageDelTag(){
        $tagId = !empty($this->paramsInfo['params']['pkTag']) ? $this->paramsInfo['params']['pkTag'] : '';
        if (empty($tagId)) {
            return interface_func::setMsg(1001);
        }
        //删除标签
        $tag = task_api::DelTag(array('pk_tag' => $tagId));
        if ($tag == 1) {
            return interface_func::setMsg(0);
        } elseif ($tag == 2) {
            return interface_func::setMsg(1);
        }
    }

    /*
     * 删除作业
     * */
    public function pageDeleteTask(){
        $taskId = !empty($this->paramsInfo['params']['pkTask']) ? $this->paramsInfo['params']['pkTask'] : '';
        if (empty($taskId)) return interface_func::setMsg(1001);
        $param = ['pk_task' => $taskId];
        $taskDetail = task_api::getTaskDetail($param);
        if (empty($taskDetail->result->data)) return interface_func::setMsg(1051);
        //删除作业表
        $taskDel = task_api::getdelTask($param);
        $pk_task = ['fk_task' => $taskId];
        $commitTaskList = task_api::getStudentAllTask($pk_task);
        if (!empty($commitTaskList->data->items)) {
            //删除提交作业表
            $taskCommitDel = task_api::getdelCommitTask($pk_task);
        }
        //删除批改作业表
        $replyTaskList = task_api::getStudentAllTaskAlealy($pk_task);
        if (!empty($replyTaskList->result->data)) {
            //删除批改作业表
            $taskReplyDel = task_api::getdelReplyTask($pk_task);
        }
        return interface_func::setMsg(0);
    }


    /*
     * 图片上传
     * */
    public function pageUploadImage(){
        $image = !empty($this->paramsInfo['params']['image']) ? $this->paramsInfo['params']['image'] : '';
        if (empty($image)) {
            return interface_func::setMsg(1001);
        }
        $data = array();
        $path = ROOT_WWW . "/upload/tmp";
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $imgName = 'task' . time() . ".org.jpg";
        $filename = $path . "/" . $imgName;

        //获取图片
        if (file_put_contents($filename, base64_decode($image))) {
            //压缩原图
            $imgName152 = 'task' . time() . ".small.org.jpg";
            $filename152 = $path . "/" . $imgName152;
            file_put_contents($filename152, base64_decode($image));
            $thumbnail = new SThumbnail($filename152, 100);
            $thumbnail->setMaxSize(300, 300);
            $filename_152 = utility_file::tempname("thumb");
            if ($thumbnail->genFile($filename_152) === false) {
                return false;
            }
            //上传服务器taskUpload
            $file152 = utility_file::instance();
            $r152 = $file152->upload($filename_152, user_api::getLoginUid(), "image");
            $url152 = $r152->fid;
            $src152 = interface_func::imgURL($r152->fid);
            if(@getimagesize($src152)){
                $imageSize = getimagesize($src152);
                $width = !empty($imageSize[0]) ? $imageSize[0] : 200; //app默认200
                $height = !empty($imageSize[1]) ? $imageSize[1] : 200;//app默认200
            }else{
                $width = 200;
                $height = 200;
            }
            //上传服务器taskUpload 不改变尺寸
            $file = utility_file::instance();
            $r1 = $file->upload($filename, user_api::getLoginUid(), "image", $imgName);
            $url = $r1->fid;
            $src = interface_func::imgURL($r1->fid);
            //@unlink($filename);
            $result = array('big' => $url, 'bigUrl' => $src, 'small' => $url152, 'smallUrl' => $src152, 'srcMallWidth' => $width, 'srcMallHeight' => $height);
        }
        return interface_func::setData($result);
    }

    /*
     * 语音上传
     * @params type 文件类型
     * @params voice 文件base64加密二进制流
     * return array
     * */
    public function pageUploadFile(){
        $voice = !empty($this->paramsInfo['params']['voice']) ? $this->paramsInfo['params']['voice'] : '';
        $suffix = !empty($this->paramsInfo['params']['type']) ? $this->paramsInfo['params']['type'] : '';
        $sort = !empty($this->paramsInfo['params']['sort']) ? $this->paramsInfo['params']['sort'] : '';
        if (empty($voice) || empty($suffix)) {
            return interface_func::setMsg(1001);
        }
        $uptypes = array("txt", "pdf", "doc", "docx", "ppt", "pptx", "jpg", "xls", "xlsx", "mp3");
        //判断文件的类型
        if (!in_array($suffix, $uptypes)) {
            return interface_func::setMsg(2064);
        }
        $path = ROOT_WWW . "/upload/tmp";
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $attachName = 'task' . time() . ".$suffix";
        $filename = $path . "/" . $attachName;
        //获取图片
        if (file_put_contents($filename, base64_decode($voice))) {
            $file = utility_file::instance();
            $rid = $file->upload($filename, user_api::getLoginUid(), $suffix, "");
            $files = $rid->fid;
            if (!$rid) {
                return interface_func::setMsg(1029);
                unlink($filename);
            }
            unlink($filename);
            $data['type'] = $suffix;
            $data['attach'] = $files;
            $data['sort'] = $sort;
            $data["url"] = interface_func::imgURL($files);
        } else {
            return interface_func::setMsg(1029);
        }
        return interface_func::setData($data);
    }

    /*
     * 教师待批改,已批改详情页
     * 教师发布内容  学生已交未交列表
     * */
    public function pageTaskDetail(){
        $taskId = !empty($this->paramsInfo['params']['pkTask']) ? $this->paramsInfo['params']['pkTask'] : '862';
        $page = !empty($this->paramsInfo['params']['page']) ? $this->paramsInfo['params']['page'] : '1';
        if (empty($taskId)) {
            return interface_func::setMsg(1001);
        }
        //查询作业信息   教师 发布时间
        $newList = array();
        $param = array('pk_task' => $taskId);
        $taskDetail = task_api::getTaskDetail($param);
        if (empty($taskDetail->result->data)) {
            return interface_func::setMsg(1);
        }
        $teacherName = task_api::getTeacherName($taskDetail->result->data[0]->fk_user_teacher);

        if (!empty($teacherName->data[0])) {
            //班级 课程名称
            $courseInfo = task_api::getCourseOneName(array('pk_course' => $taskDetail->result->data[0]->fk_course));
            if (!empty($courseInfo->result)) {
                $classInfo = task_api::getClassInfo(array('pk_class' => $taskDetail->result->data[0]->fk_class));
                if (!empty($classInfo->result->data->items)) {
                    $taskInfo = array();
                    $taskInfo['taskInfo']['className'] = $classInfo->result->data->items[0]->name;
                    $taskInfo['taskInfo']['pkClass'] = $classInfo->result->data->items[0]->pk_class;
                    $taskInfo['taskInfo']['pkCourse'] = $courseInfo->result[0]->pk_course;
                    $taskInfo['taskInfo']['title'] = $courseInfo->result[0]->title;
                    $taskInfo['taskInfo'] ['createTime'] = date('m-d H:i', strtotime($taskDetail->result->data[0]->start_time));
                    $weekArray = array("日", "一", "二", "三", "四", "五", "六");
                    $week = "星期" . $weekArray[date("w", strtotime($taskDetail->result->data[0]->end_time))];
                    $ymd = date('Y-m-d', strtotime($taskDetail->result->data[0]->end_time));
                    $his = date('h:i', strtotime($taskDetail->result->data[0]->end_time));
                    $taskInfo['taskInfo'] ['endTime'] = $ymd . ' ' . $week . ' ' . $his;
                    $taskInfo['taskInfo'] ['teacherName'] = !empty($teacherName->data[0]->real_name) ? $teacherName->data[0]->real_name : (!empty($teacherName->data[0]->name) ? $teacherName->data[0]->real_name : '未设置');
                    $taskInfo['taskInfo'] ['teacherId'] = $taskDetail->result->data[0]->fk_user_teacher;
                    $taskInfo['taskInfo']['thumbMed'] = interface_func::imgURL($teacherName->data[0]->thumb_med);
                    $newList = $taskInfo;
                }
            }
        }
        //通过作业ID 查看 待批改作业详情
        //查询教师名字
        $newList['publish']['pkTask'] = $taskDetail->result->data[0]->pk_task;
        $newList['publish']['fkCourse'] = $taskDetail->result->data[0]->fk_course;
        $newList['publish']['fkClass'] = $taskDetail->result->data[0]->fk_class;
        $newList['publish']['fkUserTeacher'] = $taskDetail->result->data[0]->fk_user_teacher;
        $newList['publish']['status'] = $taskDetail->result->data[0]->status;
        $newList['publish']['desc'] = $taskDetail->result->data[0]->desc;
        $newList['publish']['createTime'] = $taskDetail->result->data[0]->create_time;
        $newList['publish']['startTime'] = $taskDetail->result->data[0]->start_time;
        $newList['publish']['endTime'] = $taskDetail->result->data[0]->end_time;
        $newList['publish']['studentCount'] = $taskDetail->result->data[0]->student_count;
        $newList['publish']['markCount'] = $taskDetail->result->data[0]->mark_count;
        //查询图片
        $thumbCondition = $param = array('object_id' => $taskDetail->result->data[0]->pk_task, 'object_type' => 1, 'status' => 1);
        $taskDetailThumb = task_api::getTaskDetailThumb($thumbCondition);
        $newList['thumb'] = array();
        if (!empty($taskDetailThumb->result->data)) {
            $newList['thumb'] = $taskDetailThumb->result->data;
            foreach ($newList['thumb'] as $value) {
                $value->srcBig = interface_func::imgURL($value->thumb_big);
                $value->srcMall = interface_func::imgURL($value->thumb_small);
                $value->pkThumb = $value->pk_thumb;
                $value->smallWidth = $value->small_width;
                $value->smallHeight = $value->small_height;
                unset($value->createtime);
                unset($value->last_updated);
                unset($value->status);
                unset($value->thumb_big);
                unset($value->thumb_small);
                unset($value->object_id);
                unset($value->object_type);
                unset($value->pk_thumb);
                unset($value->small_width);
                unset($value->small_height);
            }
        }
        //查询附件
        $attachCondition = $param = array('object_id' => $taskDetail->result->data[0]->pk_task, 'object_type' => 1, 'status' => 1);
        $taskDetailAttach = task_api::getTaskDetailAttach($attachCondition);
        $newList['attach'] = array();
        if (!empty($taskDetailAttach->result->data)) {
            $newList['attach'] = $taskDetailAttach->result->data;
            foreach ($newList['attach'] as $value) {
                $value->srcAttach = interface_func::imgURL($value->file);
                $value->type = substr($value->type, 1);
                $value->pkAttach = $value->pk_attach;
                unset($value->create_time);
                unset($value->last_updated);
                unset($value->status); //object_type
                unset($value->object_type);
                unset($value->object_id);
                unset($value->pk_attach);
            }
        }
        //查询标签
        $attachCondition = $param = array('fk_task' => $taskDetail->result->data[0]->pk_task);
        $tag = task_api::getTaskDetailTag($attachCondition);
        $newList['tag'] = array();
        if (!empty($tag->result->data)) {
            for ($i = 0; $i < count($tag->result->data); $i++) {
                //查询标签名字
                $tagName = array('pk_tag' => $tag->result->data[$i]->fk_tag);
                $name = task_api::seltagTag($tagName);
                if (!empty($name->data)) {
                    $newName[$i]['name'] = $name->data[0]->name;
                    $newName[$i]['pkTag'] = $name->data[0]->pk_tag;
                    $newList['tag'] = $newName;
                }
            }
            rsort($newList['tag']);
        }
        /**    列表展示   1,2  **/
        /*列表展示1   已提交 已批改+未批改
         *  通过作业ID 查询 所有学生 答题列表  未批改 取第一条 如果没有
         *  已提交 未批改
         */
        $newList['commitList'] = array();
        $pk_task = array('fk_task' => $taskDetail->result->data[0]->pk_task, 'page' => $page, 'pageSize' => 20, 'type' => 1);
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
                    $selTaskList->data->items[$j]->timeStatus = "迟交";
                } else {
                    $selTaskList->data->items[$j]->timeStatus = "";
                }
                //学生Id 查询学生 Name
                $selStuName = task_api::getTeacherName($selTaskList->data->items[$j]->fk_user_student);
                $selTaskList->data->items[$j]->thumbMed = !empty($selStuName->data[0]->thumb_med)?interface_func::imgURL($selStuName->data[0]->thumb_med):'';
                $selTaskList->data->items[$j]->studentName = !empty($selStuName->data[0]->real_name) ? $selStuName->data[0]->real_name : (!empty($selStuName->data[0]->name) ? $selStuName->data[0]->name : "未设置");
                $selTaskList->data->items[$j]->pkTaskStudent = $selTaskList->data->items[$j]->pk_task_student;
                $selTaskList->data->items[$j]->fkTask = $selTaskList->data->items[$j]->fk_task;
                $selTaskList->data->items[$j]->fkUserStudent = $selTaskList->data->items[$j]->fk_user_student;
                $selTaskList->data->items[$j]->createTime = $selTaskList->data->items[$j]->create_time;
                $selTaskList->data->items[$j]->lastUpdated = $selTaskList->data->items[$j]->last_updated;
                $selTaskList->data->items[$j]->replyTime = $selTaskList->data->items[$j]->reply_time;
                unset($selTaskList->data->items[$j]->pk_task_student);
                unset($selTaskList->data->items[$j]->fk_task);
                unset($selTaskList->data->items[$j]->fk_user_student);
                unset($selTaskList->data->items[$j]->create_time);
                unset($selTaskList->data->items[$j]->last_updated);
                unset($selTaskList->data->items[$j]->reply_time);
            }
        }
        unset($selTaskList->result);//已经提交作业学生
        $newList['commitList'] = $selTaskList->data;

        /*  显示列表2 未提交作业学生信息
         *  获取 当前老师 所代班级 课程 下的学生信息 与已经提交做对比  筛选出未提交作业学生
         */
        $newList['noCommitList'] = array();
        $pk_task = array('fk_task' => $taskDetail->result->data[0]->pk_task, 'page' => $page, 'pageSize' => 20, 'status' => 4);
        $selNoCommitList = task_api::getStudentAllTask($pk_task);
        //获取未提交数据
        foreach ($selNoCommitList->data->items as $k1 => $v1) {
            $selStuName = task_api::getTeacherName($v1->fk_user_student);
            $selNoCommitList->data->items[$k1]->teacherName = !empty($selStuName->data[0]->real_name) ? $selStuName->data[0]->real_name : (!empty($selStuName->data[0]->name)?$selStuName->data[0]->name:'未设置');
            if (!empty($v1->desc)) {
                unset($selNoCommitList->data->items[$k1]);
            }
            if ($v1->status == 1 || $v1->status == 2) {
                unset($selNoCommitList->data->items[$k1]);
            }
            $v1->thumbMed = !empty($selStuName->data[0]->thumb_med)?interface_func::imgURL($selStuName->data[0]->thumb_med):'';
            $v1->pkTaskStudent = $v1->pk_task_student;
            $v1->fkTask = $v1->fk_task;
            $v1->fkUserStudent = $v1->fk_user_student;
            $v1->createTime = $v1->create_time;
            $v1->lastUpdated = $v1->last_updated;
            $v1->replyTime = $v1->reply_time;
            unset($v1->pk_task_student);
            unset($v1->fk_task);
            unset($v1->create_time);
            unset($v1->last_updated);
            unset($v1->reply_time);

        }
        rsort($selNoCommitList->data->items);
        $newList['noCommitList'] = $selNoCommitList->data;
        return interface_func::setData($newList);
    }


    /*
     * 批改作业展示 ，已批改作业展示
     * 批改作业  返回  教师发布作业  学生提交作业
     * 已批改页  返回  获取教师发布作业  学生提交作业 教师批改作业
     * @params taskId           =》作业Id     必传
     * @params pk_task_student  =》提交作业ID 必传
     * return array
     *
     * */
    public function pageTaskReplyDetail(){
        $taskId = !empty($this->paramsInfo['params']['taskId']) ? $this->paramsInfo['params']['taskId'] : '';
        $fk_task_student = !empty($this->paramsInfo['params']['pkTaskStudent']) ? $this->paramsInfo['params']['pkTaskStudent'] : '';
        if (empty($taskId) || empty($fk_task_student)) {
            return interface_func::setMsg(1001);
        }
        $param = array('pk_task' => $taskId);
        $taskDetail = task_api::getTaskDetail($param);
        if (empty($taskDetail->result->data)) {
            //此页面已经删除
            return interface_func::setMsg(1051);
        }
        if ($taskDetail->result->data[0]->status == -1) {
            //此页面已经删除
            return interface_func::setMsg(1051);
        }
        $newList['publish']['data'] = array();
        $newList['publish']['data']['pkTask'] = $taskDetail->result->data[0]->pk_task;
        $newList['publish']['data']['fkCourse'] = $taskDetail->result->data[0]->fk_course;
        $newList['publish']['data']['fkClass'] = $taskDetail->result->data[0]->fk_class;
        $newList['publish']['data']['fkUserTeacher'] = $taskDetail->result->data[0]->fk_user_teacher;
        $newList['publish']['data']['status'] = $taskDetail->result->data[0]->status;
        $newList['publish']['data']['desc'] = $taskDetail->result->data[0]->desc;
        $newList['publish']['data']['createTime'] = $taskDetail->result->data[0]->create_time;
        $newList['publish']['data']['startTime'] = $taskDetail->result->data[0]->start_time;
        $newList['publish']['data']['endTime'] = $taskDetail->result->data[0]->end_time;
        $newList['publish']['data']['studentCount'] = $taskDetail->result->data[0]->student_count;
        $newList['publish']['data']['markCount'] = $taskDetail->result->data[0]->mark_count;
        //查询图片
        $thumbCondition = $param = array('object_id' => $taskDetail->result->data[0]->pk_task, 'object_type' => 1, 'status' => 1);
        $taskDetailThumb = task_api::getTaskDetailThumb($thumbCondition);
        $newList['publish']['thumb'] = array();
        if (!empty($taskDetailThumb->result->data)) {
            $newList['publish']['thumb'] = $taskDetailThumb->result->data;
            foreach ($newList['publish']['thumb'] as $value) {
                $value->srcBig = interface_func::imgURL($value->thumb_big);
                $value->srcMall = interface_func::imgURL($value->thumb_small);
                $value->pkThumb = $value->pk_thumb;
                $value->smallWidth = $value->small_width;
                $value->smallHeight = $value->small_height;
                unset($value->createtime);
                unset($value->last_updated);
                unset($value->status);
                unset($value->thumb_big);
                unset($value->thumb_small);
                unset($value->object_id);
                unset($value->object_type);
                unset($value->pk_thumb);
                unset($value->small_width);
                unset($value->small_height);
            }
        }
        $newList['publish']['attach'] = array();
        $attachCondition = $param = array('object_id' => $taskDetail->result->data[0]->pk_task, 'object_type' => 1, 'status' => 1);
        $taskDetailAttach = task_api::getTaskDetailAttach($attachCondition);
        $newList['publish']['attach'] = array();
        if (!empty($taskDetailAttach->result->data)) {
            $newList['publish']['attach'] = $taskDetailAttach->result->data;
            foreach ($newList['publish']['attach'] as $value) {
                $value->srcAttach = interface_func::imgURL($value->file);
                $value->type = substr($value->type, 1);
                $value->pkAttach = $value->pk_attach;
                unset($value->create_time);
                unset($value->last_updated);
                unset($value->status); //object_type
                unset($value->object_type);
                unset($value->object_id);
                unset($value->pk_attach);
            }
        }
        //查询标签
        $attachCondition = $param = array('fk_task' => $taskDetail->result->data[0]->pk_task);
        $tag = task_api::getTaskDetailTag($attachCondition);
        $newList['publish']['tag'] = array();
        if (!empty($tag->result->data)) {
            for ($i = 0; $i < count($tag->result->data); $i++) {
                //查询标签名字
                $tagName = array('pk_tag' => $tag->result->data[$i]->fk_tag);
                $name = task_api::seltagTag($tagName);
                if (!empty($name->data)) {
                    $newName[$i]['name'] = $name->data[0]->name;
                    $newName[$i]['pkTag'] = $name->data[0]->pk_tag;
                    $newList['publish']['tag'] = $newName;
                }
            }
            rsort($newList['publish']['tag']);
        }
        //查询学生提交作业
        $newList['commit'] = array();
        //查询提交作业表
        $pk_task_student_id = array('pk_task_student' => $fk_task_student);
        $commitTaskList = task_api::getStudentTaskDetail($pk_task_student_id);
        if (!empty($commitTaskList->data->items[0])) {
            $pk_task_student = $commitTaskList->data->items[0]->pk_task_student;
            //学生提交内容
            $month = date("m-d", strtotime($commitTaskList->data->items[0]->last_updated));
            $time_is = date("h:i", strtotime($commitTaskList->data->items[0]->last_updated));
            $weekArray = array("日", "一", "二", "三", "四", "五", "六");
            $week = "星期" . $weekArray[date("w", strtotime($commitTaskList->data->items[0]->last_updated))];
            $commitTaskList->data->items[0]->commitTime = $month . ' ' . $week . ' ' . $time_is;
            $newList['commit']['data']['pkTaskStudent'] = $commitTaskList->data->items[0]->pk_task_student;
            $newList['commit']['data']['fkTask'] = $commitTaskList->data->items[0]->fk_task;
            $newList['commit']['data']['fkUserStudent'] = $commitTaskList->data->items[0]->fk_user_student;
            $newList['commit']['data']['desc'] = $commitTaskList->data->items[0]->desc;
            $newList['commit']['data']['createTime'] = $commitTaskList->data->items[0]->create_time;
            $newList['commit']['data']['lastUpdated'] = $commitTaskList->data->items[0]->last_updated;
            $newList['commit']['data']['status'] = $commitTaskList->data->items[0]->status;
            $newList['commit']['data']['replyTime'] = $commitTaskList->data->items[0]->reply_time;
            $newList['commit']['data']['commitTime'] = $commitTaskList->data->items[0]->commitTime;
            //查询图片 学生提交
            $commitThumbCondition = $param = array('object_id' => $pk_task_student, 'object_type' => 2, 'status' => 1);
            $taskDetailThumb = task_api::getTaskDetailThumb($commitThumbCondition);
            $newList['commit']['thumb'] = $taskDetailThumb->result->data;
            if (!empty($newList['commit']['thumb'])) {
                foreach ($newList['commit']['thumb'] as $value) {
                    $value->srcBig = interface_func::imgURL($value->thumb_big);
                    $value->srcMall = interface_func::imgURL($value->thumb_small);
                    $value->pkThumb = $value->pk_thumb;
                    $value->smallWidth = $value->small_width;
                    $value->smallHeight = $value->small_height;
                    unset($value->createtime);
                    unset($value->last_updated);
                    unset($value->status);
                    unset($value->thumb_big);
                    unset($value->thumb_small);
                    unset($value->object_id);
                    unset($value->object_type);
                    unset($value->pk_thumb);
                    unset($value->small_width);
                    unset($value->small_height);
                }
            }
            //查询附件 学生提交
            $commitAttachCondition = $param = array('object_id' => $pk_task_student, 'object_type' => 2, 'status' => 1);
            $taskDetailAttach = task_api::getTaskDetailAttach($commitAttachCondition);
            $newList['commit']['attach'] = $taskDetailAttach->result->data;
            if (!empty($newList['commit']['attach'])) {
                foreach ($newList['commit']['attach'] as $value) {
                    $value->srcAttach = interface_func::imgURL($value->file);
                    $value->type = substr($value->type, 1);
                    $value->pkAttach = $value->pk_attach;
                    unset($value->create_time);
                    unset($value->last_updated);
                    unset($value->status); //object_type
                    unset($value->object_type);
                    unset($value->object_id);
                    unset($value->pk_attach);
                }
            }
        }
        //已批改作业
        $pk_task = array('fk_task_student' => $fk_task_student);
        $replyTaskInfo = task_api::getStudentAllTaskAlealy($pk_task);
        if (!empty($replyTaskInfo->result->data)) {
            $newList['reply']['data'] = array();
            $newList['reply']['thumb'] = array();
            $newList['reply']['attach'] = array();
            $newList['reply']['tag'] = array();
            if (!empty($replyTaskInfo->result->data)) {
                $newList['reply']['data']['pkTaskStudentReply'] = $replyTaskInfo->result->data[0]->pk_task_student_reply;
                $newList['reply']['data']['fkTaskStudent'] = $replyTaskInfo->result->data[0]->fk_task_student;
                $newList['reply']['data']['fkTask'] = $replyTaskInfo->result->data[0]->fk_task;
                $newList['reply']['data']['desc'] = $replyTaskInfo->result->data[0]->desc;
                $newList['reply']['data']['status'] = $replyTaskInfo->result->data[0]->status;
                $newList['reply']['data']['level'] = $replyTaskInfo->result->data[0]->level;
                $newList['reply']['data']['fkUserTeacher'] = $replyTaskInfo->result->data[0]->fk_user_teacher;
                $newList['reply']['data']['lastUpdated'] = $replyTaskInfo->result->data[0]->last_updated;
                $stuReplyId = $replyTaskInfo->result->data[0]->pk_task_student_reply;
                //查询图片
                $replythumbCondition = $param = array('object_id' => $stuReplyId, 'object_type' => 3, 'status' => 1);
                $replyTasklThumb = task_api::getTaskDetailThumb($replythumbCondition);
                $newList['reply']['thumb'] = $replyTasklThumb->result->data;
                if (!empty($newList['reply']['thumb'])) {
                    foreach ($newList['reply']['thumb'] as $value) {
                        //查询图片对应语音
                        $selVoice = task_api::getVoiceByVoiceId($value->pk_thumb, 3);
                        $value->voice = !empty($selVoice->result->items) ? $selVoice->result->items : array();
                        $value->srcBig = interface_func::imgURL($value->thumb_big);
                        $value->srcMall = interface_func::imgURL($value->thumb_small);
                        $value->pkThumb = $value->pk_thumb;
                        $value->smallWidth = $value->small_width;
                        $value->smallHeight = $value->small_height;
                        unset($value->createtime);
                        unset($value->last_updated);
                        unset($value->status);
                        unset($value->thumb_big);
                        unset($value->thumb_small);
                        unset($value->object_id);
                        unset($value->object_type);
                        unset($value->pk_thumb);
                        unset($value->small_width);
                        unset($value->small_height);
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
                //查询附件
                $replyAttachCondition = $param = array('object_id' => $stuReplyId, 'object_type' => 3, 'status' => 1);
                $taskDetailAttach = task_api::getTaskDetailAttach($replyAttachCondition);
                $newList['reply']['attach'] = $taskDetailAttach->result->data;
                if (!empty($newList['reply']['attach'])) {
                    foreach ($newList['reply']['attach'] as $value) {
                        $value->srcAttach = interface_func::imgURL($value->file);
                        $value->type = substr($value->type, 1);
                        $value->pkAttach = $value->pk_attach;
                        unset($value->create_time);
                        unset($value->last_updated);
                        unset($value->status); //object_type
                        unset($value->object_type);
                        unset($value->object_id);
                        unset($value->pk_attach);
                    }
                }
                //标签  查询批改标签
                //查询 t_task_student 取出ID 在查询标签
                //查询标签
                $newList['reply']['tag'] = array();
                $newName = array();
                $pk_task_student_id = array('pk_task_student' => $fk_task_student);
                $commitTaskList = task_api::getStudentTaskDetail($pk_task_student_id);
                if (!empty($commitTaskList->data->items[0])) {
                    $pk_task_student = $commitTaskList->data->items[0]->pk_task_student;
                }
                $where = array('fk_task_student' => $pk_task_student);
                $replyTaskTag = task_api::replyTaskTag($where);
                if (!empty($replyTaskTag->result->data)) {
                    foreach ($replyTaskTag->result->data as $key => $val) {
                        //查询标签名字
                        $tagName = array('pk_tag' => $val->fk_tag);
                        $name = task_api::seltagTag($tagName);
                        if (!empty($name->data[0])) {
                            $newName[$key]['name'] = $name->data[0]->name;
                            $newName[$key]['pkTag'] = $name->data[0]->pk_tag;
                            $newList['reply']['tag'] = $newName;
                        }
                    }
                    rsort($newList['reply']['tag']);
                }
            }
        }
        //查询班级  课程信息
        $courseId = array('pk_course' => $taskDetail->result->data[0]->fk_course);
        $classId = array('pk_class' => $taskDetail->result->data[0]->fk_class);
        $courseInfo = task_api::getCourseOneName($courseId);
        $info = array();
        $newList['info'] = array();
        if (!empty($courseInfo->result)) {
            $classInfo = task_api::getClassInfo($classId);
            if (!empty($classInfo->result->data->items)) {
                $info['name'] = $classInfo->result->data->items[0]->name;
                $info['pkClass'] = $classInfo->result->data->items[0]->pk_class;
                $info['pkCourse'] = $courseInfo->result[0]->pk_course;
                $info['title'] = $courseInfo->result[0]->title;
            }
        }
        $teacherName = task_api::getTeacherName($commitTaskList->data->items[0]->fk_user_student);
        $info['rename'] = $teacherName->data[0]->real_name;
        $info['thumbMed'] = interface_func::imgURL($teacherName->data[0]->thumb_med);
        $newList['info'] = $info;
        return interface_func::setData($newList);
    }


    /*
     * 批改作业处理
     * */
    public function pageReplyTask(){
        $info['uId'] = !empty($this->paramsInfo['params']['uId']) ? $this->paramsInfo['params']['uId'] : '';
        $info['fkTask'] = !empty($this->paramsInfo['params']['fkTask']) ? $this->paramsInfo['params']['fkTask'] : '';
        $info['pkTaskStudent'] = !empty($this->paramsInfo['params']['pkTaskStudent']) ? $this->paramsInfo['params']['pkTaskStudent'] : '';
        $info['desc'] = !empty($this->paramsInfo['params']['desc']) ? trim($this->paramsInfo['params']['desc']) : '';
        $info['level'] = !empty($this->paramsInfo['params']['level']) ? $this->paramsInfo['params']['level'] : '';
        $info['taskImages'] = !empty($this->paramsInfo['params']['taskImages']) ? $this->paramsInfo['params']['taskImages'] : '';
        $info['tags'] = !empty($this->paramsInfo['params']['tags']) ? $this->paramsInfo['params']['tags'] : '';
        if (empty($info['uId']) || empty($info['fkTask']) || empty($info['pkTaskStudent']) || empty($info['level'])) {
            return interface_func::setMsg(1001);
        }
        if (empty($info['taskImages']) && empty($info['desc'])) {
            return interface_func::setMsg(1001);
        }
        //通过作业ID 查看 待批改作业详情
        $param = array('pk_task' => $info['fkTask']);
        $taskDetail = task_api::getTaskDetail($param);
        if (empty($taskDetail->result->data)) {
            return interface_func::setMsg(1051);//此作业不存在
        }
        $pk_task = array('fk_task' => $taskDetail->result->data[0]->pk_task,
            'status' => 1,
            'pk_task_student' => $info['pkTaskStudent'],
            'reply' => 'reply'
        );
        $StudentList = task_api::selStudentInfo($pk_task);
        if (empty($StudentList->items)) {
            return interface_func::setMsg(2065);//此作业已经批改
        }
        //查询批改作业表
        $where = array(
            'fk_task_student' => $StudentList->items[0]->pk_task_student,
            'fk_task' => $info['fkTask']
        );
        $replyTaskInfo = task_api::getStudentAllTaskAlealy($where);
        if (!empty($replyTaskInfo->result->data[0])) {
            return interface_func::setMsg(2065);//此作业已经批改
        }
        //批改作业添加 t_task_student_reply
        $params = array(
            'fk_task_student' => $StudentList->items[0]->pk_task_student,
            'fk_task' => $info['fkTask'],
            'desc' => trim($info['desc']),
            'status' => '1',
            'level' => $info['level'],
            'fk_user_teacher' => $info['uId']
        );
        $addReplyTask = task_api::replyTask($params);
        $nowtime = date('Y-m-d H:i:s', time());
        //图片
        $taskImages = $info['taskImages'];
        if (!empty($taskImages)) {
            //$taskImages = explode(',',$taskImages);
            for ($i = 0; $i < count($taskImages); $i++) {
                $width = !empty($taskImages[$i]["srcMallWidth"]) ? $taskImages[$i]["srcMallWidth"] : 0;
                $height = !empty($taskImages[$i]["srcMallHeight"]) ? $taskImages[$i]["srcMallHeight"] : 0;
                $imgCondition = array(
                    'thumb_big' => $taskImages[$i]["thumbBig"],
                    'thumb_small' => $taskImages[$i]["thumbSmall"],
                    'small_width' => $width,
                    'small_height' => $height,
                    'status' => '1', //正常
                    'createtime' => $nowtime,
                    'object_id' => $addReplyTask->data,
                    'object_type' => 3, //教师发布1 学生提交2 教师批改3
                );
                $addTaskImg = task_api::addPublishTaskImg($imgCondition);
                if (!empty($taskImages[$i]['voice'])) {
                    for ($j = 0; $j < count($taskImages[$i]['voice']); $j++) {
                        if (empty($taskImages[$i]['voice'][$j]['name'] || empty($taskImages[$i]['voice'][$j]['type']) ||
                            empty($taskImages[$i]['voice'][$j]['file']) || empty($taskImages[$i]['voice'][$j]['voiceTime']) ||
                            empty($taskImages[$i]['voice'][$j]['xCoordinate']) || empty($taskImages[$i]['voice'][$j]['yCoordinate']) || empty($taskImages[$i]['voice'][$j]['sort']))
                        ) {
                            continue;
                        }
                        //添加语音
                        $params = [
                            'name' => $taskImages[$i]['voice'][$j]['name'],       //语音名称
                            'type' => $taskImages[$i]['voice'][$j]['type'],       //MP3
                            'file' => $taskImages[$i]['voice'][$j]['file'],       //真实名
                            'voice_time' => $taskImages[$i]['voice'][$j]['voiceTime'],     //语音时间
                            'x_coordinate' => $taskImages[$i]['voice'][$j]['xCoordinate'], //未知 X
                            'y_coordinate' => $taskImages[$i]['voice'][$j]['yCoordinate'], //未知 Y
                            'create_time' => date('Y-m-d H:i:s', time()),
                            'object_id' => $addTaskImg->result->thumbId,
                            'object_type' => 3, //教师发布1 学生提交2 教师批改3
                            'sort'=>$taskImages[$i]['voice'][$j]['sort'],
                        ];
                        //添加语音
                        $voice = task_api::addVoice($params);
                    }
                }
            }
        }
        //标签
        /*  标签表 t_tag 库  t_course 库
        *  t_tag t_tag_belong_group
        *  t_mapping_tag_task   t_mapping_tag_task_student
        */
        //如果tag为空  t_tag tag_belong_group添加数据
        $tags = $info['tags'];
        if (!empty($tags)) {
            foreach ($tags as $key => $val) {
                //查询标签是否存在
                $new_param = array('name' => $val['name']);
                $sel_tag = task_api::seltagTag($new_param);
                if (empty($sel_tag->data)) {
                    $where = array(
                        'fk_user' => $info['uId'],
                        'name' => $val['name'],
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
                    //添加t_course  t_mapping_tag_task
                    $params = array(
                        'fk_group' => '4',
                        'fk_tag' => $addTag->data,
                        'fk_task_student' => $StudentList->items[0]->pk_task_student,
                        'status' => '0',
                    );
                    $addMappingTag = task_api::addMappingStudentTag($params);
                } else {
                    if (empty($sel_tag->data)) {
                        return interface_func::setMsg(2066); //标签错误
                    }
                    //添加t_course  t_mapping_tag_task
                    $params = array(
                        'fk_group' => '4',
                        'fk_tag' => $sel_tag->data[0]->pk_tag,
                        'fk_task_student' => $StudentList->items[0]->pk_task_student,
                        'status' => '1',
                    );
                    $addMappingTag = task_api::addMappingStudentTag($params);
                }
            }
        }
        //修改作业表 提交数量
        $params = array('id' => $info['fkTask']);
        $taskNum = task_api::getTaskCommitNum($params);
        //提交次数 +1
        $where = array(
            'mark_count' => $taskNum->data->mark_count + 1,
            'status' => 1,
            'pk_task' => $info['fkTask']
        );
        $studentCount = task_api::updateReplyCount($where);
        //修改 t_task_student 状态
        $statusWhere = array(
            'status' => 2,
            'pk_task_student' => $StudentList->items[0]->pk_task_student
        );
        $upStatus = task_api::updateTaskStudentStatus($statusWhere);
        //批改作业后给学生发消息
        $courseId = $taskDetail->result->data[0]->fk_course;
        $userTeacher = $taskDetail->result->data[0]->fk_user_teacher;
        $startTime = $StudentList->items[0]->create_time;
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
            $teacherName = $teacherInfo->data[0]->real_name;
        }
        $params = [
            'q' => ['course_id' => $taskDetail->result->data[0]->fk_course],
            'f' => ['course_id', 'subdomain'],

        ];
        $result = seek_api::seekCourse($params);
//        $subdomain = user_organization::getSubdomainByUid(self::$orgOwner);
//        $subdomain = user_organization::course_domain($subdomain->subdomain);
        $subdomain = $result->data[0]->subdomain;
        if (!empty($courseName) && !empty($teacherName)) {                                                                                                                                               //?taskId=848&type=correct&studentId=111?fk_task_student=674
            $content = '【批改作业】老师已经批改了你的' . date('m-d H:i', strtotime($startTime)) . '提交的[' . $courseName . ']的作业，快去看看批改结果吧~<a href="//' . $subdomain . '/task.publishTask.taskCorrectShow/?taskId=' . $taskDetail->result->data[0]->pk_task . '&type=correct' . '&studentId=' . $StudentList->items[0]->pk_task_student . '" target="_blank">【去查看】</a>';
        } else {
            $content = "老师已经批改了你的作业,快去看看批改结果吧~";
        }
        $message = array(
            'msgType' => '10021',//type
            'userFrom' => 0,  //发送
            'userTo' => $StudentList->items[0]->fk_user_student,    //接受
            'content' => $content,
            'title' => $content,
        );
        $sendMessage = task_api::replyTaskMessage($message);
        //手机推送消息(云课2.0)
        $ymData = [
            "title" => "批改作业",
            "text" => $content,
            "to_uid" => $StudentList->items[0]->fk_user_student,
            "organization" => 0,
            "message_type" => message_type::TEACHER_REPLY_TASK
        ];
        common_api::addYmMessage($ymData);
        return interface_func::setMsg(0);
    }

    /*
     * 教师催交作业 单个催交
     * */
    public function pagePushMessage(){
        $studentId = !empty($this->paramsInfo['params']['studentId']) ? $this->paramsInfo['params']['studentId'] : '';
        $pkTask = !empty($this->paramsInfo['params']['pkTask']) ? $this->paramsInfo['params']['pkTask'] : '';
        if (empty($studentId) || empty($pkTask)) return interface_func::setMsg(1001);
        $param = [
            'pk_task' => $pkTask
        ];
        $taskDetail = task_api::getTaskDetail($param);
        if (empty($taskDetail->result->data[0])) {
            return interface_func::setMsg(1051);
        }
        $teacherId = $taskDetail->result->data[0]->fk_user_teacher;
        $courseId = $taskDetail->result->data[0]->fk_course;
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
        $teacherInfo = task_api::getTeacherName($teacherId);
        if (!empty($teacherInfo->data[0])) {
            $teacherName = $teacherInfo->data[0]->name;
        }
        $params = [
            'q' => ['course_id' => $courseId],
            'f' => ['course_id', 'subdomain'],
        ];
        $seekCourse = seek_api::seekCourse($params);
        $subDomain = !empty($seekCourse->data[0]->subdomain) ? $seekCourse->data[0]->subdomain : '';
        if (!empty($courseName) && !empty($teacherName)) {
            $content = '【催交作业】[' . $courseName . ']的' . $teacherName . date("m-d H:i", strtotime($startTime)) . '发布的作业还没有提交，截止时间' . date("m-d H:i", strtotime($endTime)) . ',快去写作业吧。<a href="//' . $subDomain . '/task.commitTask.studentCommitTask/?taskId=' . $pkTask . '" target="_blank">【写作业】</a>';
        } else {
            $content = "发布的作业还没有提交,快去写作业吧。";
        }
        //单个催交
        $param = array(
            'userTo' => $studentId,
            'userFrom' => 0,
            'content' => "$content",
            'title' => "催交作业",
            'msgType' => message_type::TEACHER_CALL_TASK,
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
        return interface_func::setMsg(0);
    }

    /*
     * 获取语音类型
     * */
    public function pageType($typeId){
        if (empty($typeId)) return false;
        $voiceType = [
            '1' => '.mp3'
        ];
        return $voiceType[$typeId];
    }

}

