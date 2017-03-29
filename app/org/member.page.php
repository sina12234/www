<?php
class org_member extends STpl{
	private $domain;
	private $orgOwner;
	private $orgInfo;
	private $user;
	private $orgId;
	function __construct(){
        //如果没有登陆到登陆界面
        $this->user = user_api::loginedUser();
        if(empty($this->user)){
            $this->redirect("/site.main.login");
        }
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
        $org=user_organization::subdomain();
        if(!empty($org)){
            $this->orgOwner=$org->userId; //机构所有者id 以后会根据域名而列取
        }else{
            header('Location: https://www.'.$this->domain);
        }
		
        $this->orgInfo=user_organization::getOrgByOwner($this->orgOwner);
		if(!empty($this->orgInfo->oid)){
			$this->orgId = $this->orgInfo->oid;
		}else{
			header('Location: https://www.'.$this->domain);
		}
        //判断管理员
        $isAdmin=user_api::isAdmin($this->orgOwner,$this->user['uid']);
        if($isAdmin===false){			
            header('Location: //'.$org->subdomain.'.'.$this->domain);
        }
	}
	public function pageEntry($inPath){
		$msetList = user_organization::getMemberSetList($this->orgId);
		$validCount = 0;
		if(!empty($msetList)){
			foreach($msetList as $mo){
				if($mo->status == 1){
					$validCount += 1;
				}
			}
		}
		$this->assign('memberCount',count($msetList));
		$this->assign('validCount',$validCount);
		$this->assign('msetList',$msetList);
		$this->render("org/memberset.list.html");
	}
	public function pageaddMemberSet($inPath){
		$msetList = user_organization::getMemberSetList($this->orgId);
		if(count($msetList) >= 30){
			$this->redirect('/org.member');
			exit;
		}
		$this->memberSetCourse(0);
		$this->render('org/memberset.add.html');
	}
	public function pageEditMemberSet($inPath){
		$setId  = !empty($inPath[3])?$inPath[3]:0;
		if(empty($setId)){
			$this->redirect('/org.member');
		}
		$memberSetInfo = org_member_api::getMemberSetInfo($setId);
		if(empty($memberSetInfo) || $memberSetInfo['orgId'] != $this->orgId){
			$this->redirect('/org.member');
			exit;
		}
		$courseList = org_member_api::getMemberPriority($setId,1);
		if(!empty($courseList)){
            $existCidArr = array();
			foreach($courseList as $co){
                if(!empty($co->object_id)){
                    $existCidArr[] = $co->object_id;
                }
			}
			$this->assign('courseIdStr',implode(',',$existCidArr));
		}
		$this->memberSetCourse($setId);
		$this->assign('memberSetInfo',$memberSetInfo);
		$this->assign('courseList',$courseList);
		$this->assign('setId',$setId);
		$this->render('org/memberset.add.html');
	}
	public function pageUpdateMemberSetAjax($inPath){
		$setId  = !empty($_POST['setId'])?$_POST['setId']:0;
		$params = $_POST;
		$data = array();
		$result=new stdclass;
		if(empty(strip_tags($params['title']))){
			$result->field="title";
			$result->error=SLanguage::tr('请输入会员类型名称','org');
			return $result;
		}
		if(!utility_tool::check_string($params['title'],8,1)){
			$result->field="title";
			$result->error=SLanguage::tr('会员类型名称最多8个汉字','org');
			return $result;
		}
		$data['title'] = strip_tags($params['title']);
		$priceArr = array(30,90,180,360);
		$flag = 0;
		$priceCount = 0;
		foreach($priceArr as $po){
			if(isset($params['price_'.$po])){
				if(!empty($params['price_'.$po]) && $params['price_'.$po] != 0.00){
					if(preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $params['price_'.$po])){
						$flag = 1;
					}else{
						$flag = 0;
					}
				}
				$priceCount++;
			}
		}
		if($priceCount == 0){
			$result->field="price";
			$result->error=SLanguage::tr('请填写价格','org');
			return $result;
		}
		if($flag == 0){
			$result->field="price";
			$result->error=SLanguage::tr('请正确填写价格','org');
			return $result;
		}
		foreach($priceArr as $po){
			if(isset($params['price_'.$po])){
				$data['price_'.$po] = $params['price_'.$po]*100;
			}
		}
		if(empty($params['course_id'])){
			$result->field="course_id";
			$result->error=SLanguage::tr('请选择课程范围','org');
			return $result;
		}
		$courseIdArr = explode(',',$params['course_id']);
		if(empty(strip_tags($params['descript']))){
			$result->field="descript";
			$result->error=SLanguage::tr('请输入描述','org');
			return $result;
		}
		if(!utility_tool::check_string($params['descript'],300,1)){
			$result->field="descript";
			$result->error=SLanguage::tr('描述最多300个汉字','org');
			return $result;
		}
		$data['descript'] = strip_tags($params['descript']);
		
		if(!empty($setId)){
			$updateRet = user_organization::updateMemberSet($setId,$data);
			if($updateRet !== false){
				$pdata['object_ids'] = $params['course_id'];
				$pdata['type'] = 1;
				$pdata['fk_member_set'] = $setId;
				$pdata['status'] = $params['status'];
				user_organization::updateMemberPriority($pdata);
			}
			if($updateRet !== false){
				$res = array('code' => 0,'msg'=>'修改成功');
			}else{
				$res = array('code' => -1,'msg'=>'修改失败');
			}
		}else{
			$data['fk_org'] = $this->orgId;
			$data['status'] = 0;
			$data['create_time'] = date('Y-m-d H:i:s',time());
			$updateRet = user_organization::addMemberSet($data);
			if(!empty($updateRet)){
				$pdata['object_ids'] = $params['course_id'];
				$pdata['type'] = 1;
				$pdata['fk_member_set'] = $updateRet;
				$pdata['status'] = 0;
				user_organization::addMemberPriority($pdata);
			}
			if($updateRet !== false){
				$res = array('code' => 0,'msg'=>'添加成功');
			}else{
				$res = array('code' => -1,'msg'=>'添加失败');
			}
		}
		return json_encode($res);
	}
	public function memberSetCourse($setId){
		$page = 1;
		$length = 50;
		$setId  = !empty($setId)?$setId:0;		
		$leftCourseList = $this->getPayCourseList($page,$length);
		$haveCidArr = array();
		if(!empty($setId)){
			$haveCourseList = org_member_api::getMemberPriority($setId,1);
			if(!empty($haveCourseList)){
				foreach($haveCourseList as $ho){
                    if(!empty($ho->object_id)){
                        $haveCidArr[] = $ho->object_id;
                    }
				}
			}
			$this->assign('haveCourseList',$haveCourseList);
		}
		if(!empty($leftCourseList)){
			foreach($leftCourseList as &$co){
				if(in_array($co->course_id,$haveCidArr)){
					$co->selected = 1;
				}else{
					$co->selected = 0;
				}
			}
		}
		$this->assign('leftCourseList',$leftCourseList);
	}
	
	public function getPayCourseList($page,$length,$title=''){
		$fArray = array(
                  "course_id","title","thumb_big","thumb_med","thumb_sma","course_type",
                  "user_id","fee_type","price","status","admin_status",
        		);
		if(!empty($title)){
			$qArray=array(
				'fee_type' => 1,
				'admin_status'=>'-2,1',
				'user_id'=>$this->orgOwner,
				'search_field'=>$title,
				'course_type'=>'1,2',
			);
		}else{
			$qArray=array(
				'fee_type' => 1,
				'admin_status'=>'-2,1',
				'user_id'=>$this->orgOwner,
				'course_type'=>'1,2',
			);
		}
        $seekArr = array(
                  "f"=>$fArray,
                  "q"=>$qArray,
                  "ob"=>array('start_time'=>'desc'),
                  "p"=>$page,
                  "pl"=>$length,
        		);
        $retSeek = seek_api::seekcourse($seekArr);
		if(!empty($retSeek->data)){
        	return $retSeek->data;
		}
		return false;
	}
	public function pageSearchCourse($inPath){
		$title = !empty($_POST['title'])?$_POST['title']:'';
		$page = isset($_POST['page'])?$_POST['page']:1;
		$length = 50;
		$courseRet = $this->getPayCourseList($page,$length,$title);
		if(!empty($courseRet)){
			$ret = array('code'=>0,'data'=>$courseRet);
		}else{
			$ret = array('code'=>-1,'data'=>'');
		}
		return json_encode($ret); 
	}
	public function pageUpdateMemberSetStatus($inPath){
		$setId  = !empty($_POST['setId'])?$_POST['setId']:0;
		if(empty($setId)){
			$res = array('code' => -2,'msg'=>'参数错误');
			return json_encode($res); 
		}
		$data = array();
		if(isset($_POST['status']) && is_numeric($_POST['status'])){
			$data['status'] = $_POST['status'];
		}else{
			$res = array('code' => -2,'msg'=>'参数错误');
			return json_encode($res); 
		}
		$updateRet = user_organization::updateMemberSet($setId,$data);
		if($updateRet !== false){
			if($data['status'] == 0){
				$res = array('code' => 0,'msg'=>'停用成功');
			}elseif($data['status'] == 1){
				$res = array('code' => 0,'msg'=>'启用成功');
			}elseif($data['status'] == -1){
				$res = array('code' => 0,'msg'=>'删除成功');
			}	
		}else{
			$res = array('code' => -1,'msg'=>'操作失败');
		}
		return json_encode($res);	
	}
	
    public function pageAddMember($inPath){
		$setId  = !empty($_POST['setId'])?$_POST['setId']:0;
		$priceType  = isset($_POST['price_type'])?$_POST['price_type']:0;
		$mobile = isset($_POST['mobile'])?$_POST['mobile']:'';
		$status = isset($_POST['status'])?$_POST['status']:'';
		$log = array();
		$data = array();
		if(empty($setId)){
			$res = array('code' => -2,'msg'=>'参数错误');
			return json_encode($res); 
		}
		if(empty($mobile)){
			$res = array('code' => -2,'msg'=>'请填写手机号');
			return json_encode($res); 
		}
		if(empty($priceType)){
			$res = array('code' => -2,'msg'=>'请选择有效期');
			return json_encode($res); 
		}
		$userInfo = user_api::getUserIdByMobile($mobile);
		if($userInfo->code ==0 && !empty($userInfo->uid)){
			$data['fk_user'] = $userInfo->uid;
		}else{
			$res = array('code' => -2,'msg'=>'该用户不是注册用户，添加失败！');
			return json_encode($res); 
		}
		$data['price_type']    = $priceType;
		$data['setId']         = $setId;
		$data['last_type']     = 4;
		$data['type']          = 4;
		$data['status']        = $status;
		$data['member_status'] = 1;
		$data['org_id']        = $this->orgId;

		$res = org_member_api::addMember($data);
		if ($res) {
			$res = array('code' => 0,'msg'=>'添加成功');
		}else{
			$res = array('code' => -1,'msg'=>'添加失败');
		}
		return json_encode($res);
	}
	
	public function pageMemberList($inPath){
		$setId = isset($inPath[3])?$inPath[3]:'';
		if(empty($setId)){
			$this->redirect('/org.member');
			exit;
		}
		$page = 1;
		$length = 50;
		$memberList = user_organization::getMemberListByMembersetId($page,$length,$setId);
		$now = date('Y-m-d H:i:s',time());
		$year = date('Y',time());
		if(!empty($memberList)){
			foreach($memberList as &$mo){
				if($mo->end_time <= $now){
					$mo->member_status = 0;
				}else{
					$mo->member_status = 1;
				}
				$endYear = date('Y',strtotime($mo->end_time));
				if($year == $endYear){
					$mo->end_time = date('m-d H:i',strtotime($mo->end_time));
				}else{
					$mo->end_time = date('Y-m-d H:i',strtotime($mo->end_time));
				}
				if($mo->last_type == 1){
					$mo->last_type = '付费购买';
				}elseif($mo->last_type == 2){
					$mo->last_type = '后台添加';
				}elseif($mo->last_type == 4){
					$mo->last_type = '管理员添加';
				}
			}
		}
		$this->assign('setId',$setId);
		$this->assign('memberList',$memberList);
		$this->render("org/member.list.html");
	}
	
	public function pageGetMemberListAjax($inPath){
		$setId = !empty($_POST['setId'])?$_POST['setId']:'';
		if(empty($setId)){
			$ret = array('code'=>-1,'data'=>'','msg'=>'参数错误');
			return json_encode($ret);
		}
		$page = isset($_POST['page'])?$_POST['page']:1;
		$length = 50;
		$status = isset($_POST['status'])?$_POST['status']:'';
		$memberList = user_organization::getMemberListByMembersetId($page,$length,$setId,$status);
		$now = date('Y-m-d H:i:s',time());
		$year = date('Y',time());
		if(!empty($memberList)){
			foreach($memberList as &$mo){
				if($mo->end_time <= $now){
					$mo->member_status = 0;
				}else{
					$mo->member_status = 1;
				}
				$endYear = date('Y',strtotime($mo->end_time));
				if($year == $endYear){
					$mo->end_time = date('m-d H:i',strtotime($mo->end_time));
				}else{
					$mo->end_time = date('Y-m-d H:i',strtotime($mo->end_time));
				}
				if($mo->last_type == 1){
					$mo->last_type = '付费购买';
				}elseif($mo->last_type == 2){
					$mo->last_type = '后台添加';
				}elseif($mo->last_type == 4){
					$mo->last_type = '管理员添加';
				}
			}
			$ret = array('code'=>0,'data'=>$memberList);
		}else{
			$ret = array('code'=>-1,'data'=>'','msg'=>'获取数据失败');
		}
		return json_encode($ret);
	}
	
}

