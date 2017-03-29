<?php
class interface_group extends interface_base{

    /**
     * 班级下分组列表
     * @param int $classId
     * @param int $teacherId
     * @return object
     */
    public function pageClassList(){
        $classId = !empty($this->paramsInfo['params']['classId']) ? (int)$this->paramsInfo['params']['classId'] : 0;
        $result  = user_api::groupList($classId);

        $data[-1] = array('groupId'=>'-1','groupName'=>'全部分组');
        $data[-2] = array('groupId'=>'-2','groupName'=>'未分组');
        if(empty($result->data->items)) return $this->setMsg(1000);

        foreach ($result->data->items as $key=>$val){
            $data[$key] = [
                'groupId'   => $val->pk_group,
                'groupName' => $val->group_name
            ];

        }

        $data = array_values($data);
        return $this->setData($data);
    }

    /**
     * 分组下学生列表
     * @param int $classId
     * @param int $groupId (-1 全部 -2 未分组 其他按组列表)
     * @return mixed
     */
    public function pageClassStudents(){
        $classId = !empty($this->paramsInfo['params']['classId']) ? (int)$this->paramsInfo['params']['classId'] : 0;
        $groupId = !empty($this->paramsInfo['params']['groupId']) ? (int)$this->paramsInfo['params']['groupId'] : 0;
        if(empty($classId) || empty($groupId)) return $this->setMsg(1000);

        $params = [
            "classid" => $classId,
            "groupid" => $groupId,
            "type"    => 2,
            "cache"   => 0,
            "page"    => 1,
            "loginid" => 0,
            "pagesize"=> '-1',
        ];

        $result = user_api::userList($params);
        if(empty($result->data)) return $this->setMsg(3002);

        foreach ($result->data as $val) {
            $data[] = [
                'uid'   => $val->user_id,
                'name'  => $val->name,
                'thumb' => $this->imgUrl($val->thumb_med),
                'mobile'=> $val->mobile
            ];
        }

        return $this->setData($data);
    }
}