<?php
class user_supCourseAjax{
	private $user;
	private $orgId;
	private $params;
	private $orgInfo;
	private $orgOwner;
	private $courseId = 0;
	private $classId = 0;
	static  $courseTeacher = array();
	
	public function __construct(){
		
		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			exit(json_encode(array('code'=>'-1','msg'=>'request error')));
		}
		
		$this->user = user_api::loginedUser();
		if(empty($this->user)){
			exit(json_encode(array('code'=>'-1','msg'=>'not login')));
		}
	
		$organization = user_organization::subdomain();
		if(empty($organization)){
			exit(json_encode(array('code'=>'-2','msg'=>'not organization')));
		} 
		
		$this->orgInfo = user_organization::getOrgByOwner($organization->userId);
		if(!isset($this->orgInfo->oid)){
			exit(json_encode(array('code'=>'-2','msg'=>'not organization')));
		} 
		
		$this->orgOwner = $organization->userId;
		$this->orgId    = $this->orgInfo->oid;
		
		$dataBody     = utility_net::getPostData();
		$this->params = SJson::decode($dataBody, true);

		$this->classId = !empty($this->params['classId']) ? (int)$this->params['classId'] : 0;
		$this->courseId = !empty($this->params['courseId']) ? (int)$this->params['courseId'] : 0;
		$this->isAdmin = user_api::isAdmin($this->orgOwner, $this->user['uid']);
		$teacher = utility_judgeid::checkCourseTeacher($this->courseId, $this->user['uid'], $res);
		
		$isTeacher = utility_judgeid::userid($this->user['uid'], $this->orgOwner);
		if(!$this->isAdmin && !$isTeacher && !$this->orgInfo->teacher_add_course){
			exit(json_encode(array('code'=>'-1','msg'=>'not authority')));
		}
		self::$courseTeacher = $res;
	}
	
	private function setMsg($code = 1000, $msg='', $classId=0){
		$result = [
			'code'    => $code,
			'message' => SLanguage::tr($msg, 'site.course'),
			'classId' => $classId
		];
		
		return json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	//修改上下架课程
	public function pageSetCourseAdminStatus(){
		if(!$this->isAdmin){
			return $this->setMsg('1', '操作失败');
		}
		
		$adminStatus = !empty($this->params['adminStatus']) ? $this->params['adminStatus'] : 'initial';
		$courseReg = course_api::getCourseOne($this->courseId);
		$classReg  = course_api::getClasslist($this->courseId);
		
		if(empty($courseReg) || empty($courseReg->thumb_big)){
			return $this->setMsg('1','未添加课程图片!');
		} 
		if(empty($classReg)){
			return $this->setMsg('1','未添加班级!');
		}
		
		$result = course_api::setCourseAdminStatus($this->courseId, $adminStatus);
		if($result){
			if($adminStatus == 'normal'){
				return $this->setMsg('0', '上架后课程将在8分钟内显示在前台,请耐心等待!');
			}else{
				return $this->setMsg('0', '下架操作将在8分钟内显示在前台,请耐心等待!');
			}
		}
		
		return $this->setMsg('1', '操作失败');
	}
	
	//上传资料
	public function pageUploadFile(){
		
		$num    = !empty($this->params['num']) ? (int)$this->params['num'] : 10;
		$title  = !empty($this->params['title'][0]) ? strip_tags($this->params['title'][0]) : '';
		$type   = !empty($this->params['type'][0]) ? $this->params['type'][0] : '';
		$thumb  = !empty($this->params['thumb'][0]) ? $this->params['thumb'][0] : '';
		$attach = !empty($this->params['attach'][0]) ? $this->params['attach'][0] : '';
		
		$attrReg   = course_api::listPlanAttach(array('classId'=>$this->classId));
		$totalFile = !empty($attrReg->size) ? $attrReg->size : 0;	
		if($totalFile > $num) return $this->setMsg('-1', '课件已满请删除后再上传');
		if(empty($title)) return $this->setMsg('-1', '标题不能为空');
		if(empty($type)) return $this->setMsg('-1', '文件类型不正确');
		if(empty($thumb)) return $this->setMsg('-1', '缩略图不能为空');
		if(empty($attach)) return $this->setMsg('-1', '上传资料不能为空');
		
		$upfilesNum = count($this->params["title"]);
		if($totalFile+$upfilesNum>10) return $this->setMsg('-1', '课件最多上传10个');
			
		foreach($this->params['title'] as $k=>$v){
			$attArr = [
				'title'    => strip_tags($this->params['title'][$k]),
				"order_no" => "123",
				"fk_user"  => $this->user['uid'],
				"type"     => $this->params['type'][$k],
				"thumb"    => $this->params['thumb'][$k],
				"attach"   => $this->params['attach'][$k]
			];
			$retaddAtt = course_api::addPlanAttach($this->classId, $attArr);
		}
		if($retaddAtt){
			return $this->setMsg('1', '上传成功',$this->classId);
		}
		
		return $this->setMsg('-1', '上传失败',$this->classId);
	}
	
	//删除资料
	public function pageDelPlanAtt(){
		$planAttId = !empty($this->params['planAttId']) ? (int)$this->params['planAttId'] : 0;
		if(empty($planAttId)) return $this->setMsg(1000, '操作失败');

		if(course_api::delPlanAttach(array($planAttId))){
			return $this->setMsg(0, '操作成功');
		}
		
		return $this->setMsg(1, '操作失败');
	}
	
	//文件管理
	public function pageClassFile(){
		$page    = !empty($this->params['page']) ? (int)$this->params['page'] : 1;
		$length  = !empty($this->params['length']) ? (int)$this->params['length'] : 20;
		if(!$this->classId) return $this->setMsg(1000, '参数错误');
		
		$fileReg = course_api::listPlanAttach(array('classId'=>$this->classId));
		if(empty($fileReg->data)) return $this->setMsg(3002, '获取数据为空');
		foreach($fileReg->data as $v){
			$userIdArr[] = $v->fk_user;
		}
		$userRes = user_api::getUserInfoByUidArr(array('uidArr'=>$userIdArr));
		$userName = array();
		if(!empty($userRes->data)){
			foreach($userRes->data as $v){
				$userName[$v->user_id] = $v->real_name;
			}
		}

		foreach($fileReg->data as $val){
			$data[] = [
				'planattId'  => $val->planattid,
				'classId'    => $val->class_id,
				'fileName'   => $val->title,
				'fileFormat' => substr(strrchr($val->thumb, '-'), 1,3),
				'fileSize'   => 0,
				'createTime' => $val->create_time,
				'actionUser' => !empty($userName[$val->fk_user]) ? $userName[$val->fk_user] : '管理员',
				'url'        => utility_cdn::file($val->attach)
			];
		}
		
		return interface_func::setData(
			[
				'fileList' => $data,
				'pageTotal'=> $fileReg->total,
				'total'    => $fileReg->size
			]
		);
	}
	
	//学员管理
	/*public function pageClassUser(){

		$page   = !empty($this->params['page']) ? (int)$this->params['page'] : 1;
		$length = !empty($this->params['length']) ? (int)$this->params['length'] : 20;
		$data['courseId'] = $this->courseId;
		$data['classId']  = $this->classId;
		$data['ownerId']  = $this->orgOwner;

		if(empty($data['classId']) || empty($data['courseId'])) return $this->setMsg(1000, '参数错误');
		
		$userCourse = user_api::getUserOrgCourse($data, $page, $length);
		if(empty($userCourse->items)) return $this->setMsg(3002, '获取数据为空');
		foreach($userCourse->items as $val){
			$userIdArr[$val->fk_user] = $val->fk_user;
		}
		
		$userInfo = array();
		if(!empty($userIdArr)){
			$userReg = user_api::getUserInfoByUidArr(array('uidArr'=>$userIdArr));
				
			if(!empty($userReg->data)){
				foreach($userReg->data as $v){
					$userInfo[$v->user_id] = [
						'name'   => $v->real_name,
						'mobile' => $v->mobile,
						'address'=> $v->province.' '.$v->city
					];
				}
			}
		}
		$sourceArr = array(1=>'正常报名', 2=>'会员报名', 3=>'分销报名');
		foreach($userCourse->items as $key=>$val){
			$udata[$key] = [
				"classId"  => $val->fk_class,
				"userId"   => $val->fk_user,
				"userName" => !empty($userInfo[$val->fk_user]['name']) ? $userInfo[$val->fk_user]['name'] : '',
				"mobile"   => !empty($userInfo[$val->fk_user]['mobile']) ? $userInfo[$val->fk_user]['mobile'] : '',
				"address"  => !empty($userInfo[$val->fk_user]['address']) ? $userInfo[$val->fk_user]['address'] : '',
				"source"   => !empty($sourceArr[$val->source]) ? $sourceArr[$val->source] : '',
				"createTime" => !empty($val->create_time) ? date("Y-m-i",strtotime($val->create_time))  : '',
			];
			if($val->source == 2 && (strtotime($val->expire_time)<strtotime('now'))){
				$udata[$key]['status'] = '失效';
			}else{
				$udata[$key]['status'] = '正常';
			}
		}
		
		return interface_func::setData(
			[
				'userCourse' => array_values($udata),
				'page'       => $userCourse->page,
				'total'      => $userCourse->totalSize,
				'pageTotal'  => $userCourse->totalPage
			]
		);
	}
	*/
	public function pageClassUser(){
		
		$page   = !empty($this->params['page']) ? (int)$this->params['page'] : 1;
		$length = !empty($this->params['length']) ? (int)$this->params['length'] : 20;
		$data['courseId'] = $this->courseId;
		$data['classId']  = $this->classId;
		$data['ownerId']  = $this->orgOwner;
		$data['group_id']  = $this->params['groupId'];

		if(empty($this->classId) || empty($this->courseId)) return $this->setMsg(1000, '参数错误');
		$uri = '';
		$num ="6";
		$course_id 	= $this->courseId;
		$class_id 	= $this->classId;
        $group_id	= isset($this->params['groupId']) ? $this->params['groupId']:-1;//默认显示全部学员
		$count_all 	= 0;
		
		$ret = array();
		$regdata = array("course_id"=>$course_id);
		$regdata["class_id"]= $class_id;
		$mobile = isset($this->params['mobile']) ? trim($this->params['mobile']) :'';
        $courseReg = course_api::getCourseOne($course_id);
        if(!empty($mobile))$data['search']=$mobile;
        $data['cache']=0;//不需要缓存
        $data['type'] = 2;
        $data['classid'] = $class_id;
        $data['groupid'] = !empty($group_id) ? $group_id : 0;
        $data['loginid']=$this->user['uid'];
        $data['page'] = $page;
        $data['pagesize'] = 20;
        $rs = user_api::userList($data);
               
        //获取组list
        $grouplist[-2]='未分组';
        $grouplist_tmp=  user_api::groupList($class_id);//print_r($grouplist_tmp->data->items);
        if(!empty($grouplist_tmp->data->items)){
            for($num=0;$num<count($grouplist_tmp->data->items);$num++){
                $pk_group=$grouplist_tmp->data->items[$num]->pk_group;
                $group_name=$grouplist_tmp->data->items[$num]->group_name;
                $grouplist[$pk_group]=$group_name;
            }
        }else{
        }
                
		if(!empty($rs->data)){
		    foreach($rs->data as $k=>$v){
			    $v->address 		= $v->province==$v->city ? $v->city : $v->province." ".$v->city;
				$v->register_time	= !empty($v->register_time) ? date("Y-m-d",strtotime($v->register_time)) : '';
				$v->class_id		= $class_id;
                $v->isDocument      = $courseReg->document;
			}
		}
		return interface_func::setData(
										[
											'userCourse' => !empty($rs->data) ? $rs->data : 0,
											'page'       => !empty($rs->page->page) ? $rs->page->page : 0,
											'total'      => !empty($rs->page->totalNum) ? $rs->page->totalNum : 0,
											'pageTotal'  => !empty($rs->page->totalPage) ? $rs->page->totalPage : 0
										]
	    );
	}
	//班级列表
	public function pageClassList(){
		$classReg = course_api::getClasslist($this->courseId);
		if(empty($classReg)) return $this->setMsg(3002, '获取数据为空');
		
		foreach($classReg as $val){
			$data['classList'][] = [
				'classId'   => $val->class_id,
				'className' => $val->name
			];
		}
		
		return interface_func::setData($data);
	}
	
	//地区列表
	public function pageGetRegion(){
		$regId = !empty($this->params['regId']) ? (int)$this->params['regId'] : 0;
		$res = region_api::listRegion($regId);
		if(empty($res)) return $this->setMsg(3002, '获取数据为空');
		
		foreach($res as $v){
			$data['region'][] = [
				'regId'   => $v->region_id,
				'regName' => $v->name,
				'level'   => $v->level
			];
		}
		
		return interface_func::setData($data);
	}
	
	//课程讲师列表
	public function pageCourseTeacher(){
		$teacherRes = self::$courseTeacher;
		
		if(empty($teacherRes)) return $this->setMsg(3002, '获取数据为空');
		foreach($teacherRes as $val){
			$teacherIdArr[$val->fk_user_teacher] = $val->fk_user_teacher;
		}
		$teacherIds = implode(',', $teacherIdArr);
		$teacherParams = [
			'q' => ['teacher_id'=>$teacherIds],
			'f' => ['teacher_id','real_name']
		];
		$seekTeacher = seek_api::seekTeacher($teacherParams);
		if(empty($seekTeacher->data)) return $this->setMsg(3002, '获取数据为空');
		foreach($seekTeacher->data as $val){
			$data['teacher'][] = [
				'teacherId'   => $val->teacher_id,
				'teacherName' => $val->real_name,
				'checked'     => 0
			];
		}
		
		return interface_func::setData($data);
	}
	//删除学生
	public function pageDelClassStudent(){
		$courseId = !empty($_POST["courseId"])?$_POST["courseId"]:0;
		$classId  = !empty($_POST["classId"])?$_POST["classId"]:0;
		$studentId  = !empty($_POST["studentId"])?$_POST["studentId"]:0;
		if(empty($courseId) || empty($classId) || empty($studentId)){
			return json_encode(array("code"=>-1,"data"=>false));
		}
		$ret = user_api::delClassStudent($courseId,$classId,$studentId);
		if(!empty($ret->code==0)){
			return json_encode(array("code"=>0,"data"=>$ret->result));
		}
		return json_encode(array("code"=>-2,"data"=>false));
	}

    public function pageGetDocumentUser(){
        $userId  = !empty($_POST['userId']) ? (int)$_POST['userId'] : 0;
        //$userRes = user_api::getUser($userId);
        $userReg = user_api::getUserAddress($userId);
        if(empty($userReg->result->items)) return $this->setMsg(3002, '获取数据失败');

        $userInfo = $userReg->result->items[0];
        $data = [
            //'name'    => !empty($userRes->real_name) ? $userRes->real_name : $userRes->name,
            'receiver' => $userInfo->receiver,
            'phone'   => $userInfo->phone,
            'address' => region_geo::$region[$userInfo->province].' '.region_geo::$region[$userInfo->city].' '.$userInfo->address,
            'desc'    => $userInfo->desc
        ];

		return interface_func::setData($data);
    }
}
?>
