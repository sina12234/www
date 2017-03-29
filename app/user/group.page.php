<?php

class user_group extends STpl {

    var $user;
    var $orgOwner;
    var $orgId;
    var $orgInfo;

    function __construct() {
        //如果没有登陆到登陆界面
        $this->user = user_api::loginedUser();
        if (empty($this->user)) {
            $this->redirect("/site.main.login");
        }
        $org = user_organization::subdomain();
        $this->domain = $_SERVER["HTTP_HOST"];
        if (!empty($org)) {
            $this->orgOwner = $org->userId; //机构所有者id 以后会根据域名而列取
        } else {
            header('Location: https://www.' . $this->domain);
        }
        $this->orgInfo = user_organization::getOrgByOwner($this->orgOwner);
        if (!empty($this->orgInfo->oid)) {
            $this->orgId = $this->orgInfo->oid;
        } else {
            header('Location: https://www.' . $this->domain);
        }
        //判断管理员
//        $isAdmin = user_api::isAdmin($this->orgOwner, $this->user['uid']);
//        if ($isAdmin === false) {
//            header('Location: //' . $org->subdomain . '.' . $this->domain);
//        }
    }
    public function pageaddGroupAjax() {
        $group_name = !empty(trim($_POST['group_name'])) ? trim($_POST['group_name']) : '';
        $teacher_id = !empty($_POST['teacher_id']) ? $_POST['teacher_id'] : 0;
        $course = !empty($_POST['course']) ? $_POST['course'] : 0;
        $class = !empty($_POST['class']) ? $_POST['class'] : 0;
        if (empty($group_name)) {
            $this->outputJson(-1, '组名必填');
        }
        if(mb_strlen(trim($group_name),"UTF-8")>5){
            $this->outputJson(-1, '分组名称不能多于5个字');
        }
        if (empty($teacher_id)) {
            $this->outputJson(-1, '助教老师必填');
        }
        if (empty($course) || empty($class)) {
            $this->outputJson(-1, '缺少参数');
        }
        $data['course'] = $course;
        $data['class'] = $class;
        $data['teacher'] = $teacher_id;
        $data['group_name'] = $group_name;
        $rs = user_api::addGroup($data);
        if ($rs->result->code == 0) {
            $this->outputJson(0, '添加成功',$rs->data);
        } else {
            $this->outputJson(-1, '添加失败');
        }
    }

    public function pageupGroupAjax() {
        $group_name = !empty(trim($_POST['group_name'])) ? trim($_POST['group_name']) : '';
        $teacher_id = !empty($_POST['teacher_id']) ? $_POST['teacher_id'] : 0;
        $groupid = !empty($_POST['groupid']) ? $_POST['groupid'] : 0;

        if (empty($group_name)) {
            $this->outputJson(-1, '组名必填');
        }
        if(mb_strlen(trim($group_name),"UTF-8")>5){
            $this->outputJson(-1, '分组名称不能多于5个字');
        }
        if (empty($teacher_id)) {
            $this->outputJson(-1, '助教老师必填');
        }
        if (empty($groupid)) {
            $this->outputJson(-1, '缺少参数');
        }
        $data['groupid'] = $groupid;
        $data['teacher'] = $teacher_id;
        $data['group_name'] = $group_name;
        $rs = user_api::upGroup($data);
        if ($rs->result->code == 0) {
            $this->outputJson(0, '更新成功');
        } else {
            $this->outputJson(-1, '更新失败');
        }
    }

    public function pagedelGroupAjax() {
        $groupid = !empty($_POST['groupid']) ? $_POST['groupid'] : 0;
        $classid = !empty($_POST['classid']) ? $_POST['classid'] : 0;
        if (empty($groupid)) {
            $this->outputJson(-1, '缺少参数');
        }
        if (empty($classid)) {
            $this->outputJson(-1, '缺少参数');
        }
        $rs = user_api::delGroup($groupid, $classid); //print_r($rs);die;
        if ($rs->result->code == 0) {
            $this->outputJson(0, '删除成功');
        } else {
            $this->outputJson(-1, '删除失败');
        }
    }

    public function pagegroupListAjax() {
        $classid 		 = !empty($_POST['classid']) ? $_POST['classid'] : 0;
        $courseId 		 = !empty($_POST['courseId']) ? $_POST['courseId'] : 0;
        if (empty($classid)) {
            $this->outputJson(-1, '缺少参数');
        }
        $rs 			 = user_api::groupList($classid);
		$classInfo 		 = user_api::getGroupCheckStatus($classid);
		$checkStatus	 = !empty($classInfo->data->group_message) ? $classInfo->data->group_message : 0;
		$teacherIdArr 	 = array();
		utility_judgeid::checkCourseTeacher($courseId, $this->user['uid'], $teacherRes);
		if(!empty($teacherRes)){
			foreach($teacherRes as $val){
				if($val->is_assistant == 1){
					$teacherIdArr[] = $val->fk_user_teacher;
				}
			}
		}
		if(!empty($rs->data->items)){
			foreach($rs->data->items as $k=>$v){
				$teacherIdArr[] 	= !empty($v->group_teacher_id) ? $v->group_teacher_id : 0;
			}
			
			$teacherIds 	 = implode(',', $teacherIdArr);
			$teacherParams   = [
				'q' => ['teacher_id'=>$teacherIds],
				'f' => ['teacher_id','real_name']
			];
			$teacherReg 	 = seek_api::seekTeacher($teacherParams);
			if(!empty($teacherReg->data)){
				foreach($teacherReg->data as $m=>$n){
					$teachers[$n->teacher_id] = $n;
				}
			}
			if(!empty($rs->data->items)){
				foreach($rs->data->items as $a=>$b){
					$b->real_name 	= !empty($teachers[$b->group_teacher_id]->real_name) ? $teachers[$b->group_teacher_id]->real_name : '';
					$b->checkStatus = $checkStatus;
				}
			}
		}
		
        if ($rs->result->code == 0) {
            $this->outputJson(0, 'suc', $rs->data);
        } else {
            $this->outputJson(-1, 'fail');
        }
    }

    //设置权限
    public function pagesetGroupPrivilegeAjax() {
        $classid = !empty($_POST['classid']) ? $_POST['classid'] : 0;
        $privilege = $_POST['privilege'];
        if (empty($classid)) {
            $this->outputJson(-1, '缺少参数');
        }
        $data['classid'] = $classid;
        $data['privilege'] = $privilege;
        $rs = user_api::setGroupPrivilege($data);
        if (isset($rs->result->code)) {
            $this->outputJson(0, '设置成功');
        } else {
            $this->outputJson(-1, '设置失败');
        }
    }

    //设置学员分组，支持批量
    public function pagebatchHandleGroupUserAjax() {
        $classid = !empty($_POST['classId']) ? $_POST['classId'] : 0;
        $courseid = !empty($_POST['courseId']) ? $_POST['courseId'] : 0;
        $groupid = !empty($_POST['groupId']) ? $_POST['groupId'] : 0;
        $userids = !empty($_POST['userId']) ? $_POST['userId'] : '';
        if (empty($userids)) {
            $this->outputJson(-1, '请选择学员');
        }
        if (empty($classid) || empty($courseid) || empty($groupid)) {
            $this->outputJson(-1, '缺少参数');
        }
        $data['classid'] = $classid;
        $data['courseid'] = $courseid;
        $data['groupid'] = $groupid;
        $data['userids'] = $userids;
        $rs = user_api::batchHandleGroupUser($data); //print_r($rs);
        if ($rs->result->code == 0) {
            $this->outputJson(0, '分组成功');
        } else {
            $this->outputJson(-1, '分组失败');
        }
    }

    /*
     * 巡课页用户列表接口
     * param type 1代表学生播放页，2代表管理员巡课页
     * param classid
     * param groupid
     * param page
     * param pagesize
     * return json
     */

    public function pageUserList() {
        //$this->user = user_api::loginedUser();
        $type = !empty($_POST['type']) ? $_POST['type'] : 1; //1代表学生播放页，2代表管理员巡课页
        $classid = !empty($_POST['classid']) ? $_POST['classid'] : 0;
        $groupid = !empty($_POST['groupid']) ? $_POST['groupid'] : 0;
        $page = !empty($_POST['page']) ? $_POST['page'] : 1;
        $pagesize = !empty($_POST['pagesize']) ? $_POST['pagesize'] : 10;
        if (empty($classid)) {
            $this->outputJson(-1, 'lack classid');
        }
        if (empty($groupid)) {
            $this->outputJson(-1, 'lack groupid');
        }
        $data['cache']=0;//1需要缓存
        $data['type'] = $type;
        $data['classid'] = $classid;
        $data['groupid'] = $groupid;
        $data['loginid']=$this->user['uid'];
        //$data['loginid'] = 1;
        $data['page'] = $page;
        $data['pagesize'] = $pagesize;
        $rs = user_api::userList($data); //print_r($rs);
        if ($type == 1) {
            if ($rs->result->code == 0) {
                if(!empty($rs->data)){
                    $this->outputJson(0, 'suc', $rs->data);
                }else{
                    $this->outputJson(0, 'suc');
                }
            }
            $this->outputJson(-1, $rs->result->msg);
        } else {
            if ($rs->result->code == 0) {
                $list="";
                $tmp = $rs->data; //print_r($tmp);die;
                if(!empty($tmp)){
                    for ($i = 0; $i < count($tmp); $i++) {
                        $list[$i]['uid'] = $tmp[$i]->user_id;
                        $list[$i]['name'] = $tmp[$i]->name;
                        $list[$i]['real_name'] = $tmp[$i]->real_name;
                        $list[$i]['thumb_small'] = $tmp[$i]->thumb_small;
                        $list[$i]['mobile'] = $tmp[$i]->mobile;
                    }
                }
                $this->outputJson(0, 'suc', $list);
            }
            $this->outputJson(-1, $rs->result->msg);
        }
    }

    public function pagegetAdminListAjax() {
        $rs = user_api::adminList($this->orgId); //84204
        if ($rs->result->code == 0) {
            $this->outputJson(0, 'suc', $rs->data);
        } else {
            $this->outputJson(-1, 'fail');
        }
    }
	//该课程的讲师信息
	public function pagegetCourseOfTeacherList(){
		$teacherInfo = array();
		$courseId  	 = !empty($_POST['courseId']) ?  $_POST['courseId'] : 0;
		utility_judgeid::checkCourseTeacher($courseId, $this->user['uid'], $teacherRes);
		if(!empty($teacherRes)){
			foreach($teacherRes as $val){
				if($val->is_assistant == 1){
					$teacherIdArr[] = $val->fk_user_teacher;
				}
			}
			
			$planTeacher = array();
			if(!empty($courseId)){
				$planTeacher = course_class_api::getTeacherByCourseId($courseId);
			}
			if(!empty($teacherIdArr)){
				$assistanIds 	= implode(',', $teacherIdArr);
				$assistanParams = [
					'q' => ['teacher_id'=>$assistanIds],
					'f' => ['teacher_id','real_name','thumb_med']
				];
				$assistan = seek_api::seekTeacher($assistanParams);
				
				foreach($assistan->data as $val){
					$teacherInfo[] = [
						'teacherId'   => $val->teacher_id,
						'teacherName' => !empty($val->real_name) ? $val->real_name : '',
						'thumbMed'    => interface_func::imgUrl($val->thumb_med),
						'hasPlan'     => (!empty($planTeacher) && in_array($val->teacher_id, $planTeacher)) ? 1 : 0
					];
				}
				$this->outputJson(0, 'success', $teacherInfo);
			}else{
				$this->outputJson(-1, 'fail', $teacherInfo);
			}
		}
	}
	/*
	 *获取助教在该课程下是否有分组
	 */
	 public function pagegetTeacherIsGroupById() {
		 $result	= new stdClass;
		 $con 		= array();
		 $teacherIdStr = '';
		 if(!empty($_POST['teacher_id']) && is_array($_POST['teacher_id'])){
			 $teacherIdStr = implode(",",$_POST['teacher_id']);
		 }else{
			 $result->code = -100;
			 $result->error= "teacher_id不能为空";
			 return $result;
		 }
		 $con		= array(
						"teacher_id" => $teacherIdStr,
						"course_id"  => !empty($_POST['course_id']) ? $_POST['course_id'] : 0
						);
		
		 $groupInfo = user_api::getTeacherIsGroupById($con);
		 if(!empty($groupInfo)){
			return interface_func::setData($groupInfo);
		 }else{
			return interface_func::setMsg(3002);
		 }
	 }
    public function pagegetGroupPrivilege() {
        $classid = !empty($_POST['classid']) ? $_POST['classid'] : 0;
        if (empty($classid)) {
            $this->outputJson(-1, 'lack classid');
        }
        $classInfo = course_api::getClass($classid);
        $this->outputJson(0, 'suc', array('group_message' => $classInfo->group_message));
    }

    private function outputJson($code, $msg = '', $data = '') {
        $result = array('code' => 0, 'msg' => '', 'data' => '');
        $result['code'] = $code;
        $result['msg'] = $msg;
        $result['data'] = $data;
        exit(json_encode($result));
    }

}
