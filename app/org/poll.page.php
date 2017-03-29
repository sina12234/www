<?php

class org_poll extends STpl{
	private $domain;
	public function __construct(){
		$this->user = user_api::loginedUser();
		//机构信息
		$domain_conf  = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
        $org          = user_organization::subdomain();
        if(!empty($org)){
            $this->orgOwner = $org->userId; 
        }else{
            header('Location: https://www.'.$this->domain);
        }
	}
	
	public function pageInfo($inPath){
		if(empty($inPath[3])){
			return $this->redirect("/index.main.404");
		}else{
			$voteId = (int)($inPath[3]);
		}
		
		$resVote = org_api::voteOne($voteId);

		if($resVote->code != 0){
			return $this->redirect("/site.main.404");
		}
		
		//用户信息
		$userInfo = user_api::getUser($resVote->result->fk_user);
		if(!empty($userInfo)){
			$userName = !empty($userInfo->profile->real_name) ? $userInfo->profile->real_name : $userInfo->name;
		}
		
		//用户是否投票
		$uid = 0;
		$optionIdArr = array();
		$isVote = 0;
		if($this->user){
			$uid = $this->user['uid'];
			$userRes = org_api::userLogList(array('voteId'=>$resVote->result->pk_vote,'userId'=>$uid));
			if(!empty($userRes->items)){
				$isVote = 1;
				$optionIdArr = array_reduce($userRes->items, create_function('$v,$w', '$v[$w->fk_option]=$w->fk_option;return $v;'));
			}
		}
		//投票选项
		$optionData = array();
		$resOption = org_api::optionList($resVote->result->pk_vote);
		$planInfo = array();
		$teacherInfo = array();
		if(!empty($resOption->items)){
			$optionData = $resOption->items;
			//$objIdArr = array_reduce($optionData,create_function('$v,$w','$v[$w->object_id]=$w->object_id;return $v;'));
            foreach($optionData as $val){
                $objIdArr[$val->object_id] = $val->object_id;
                $totalCount[$val->object_type][] = $val->total_count;
            }
			if($resVote->result->object_type == 3){
                if($objIdArr){
                    $planInfo = $this->getPlanInfo($objIdArr);
                }
            }elseif($resVote->result->object_type == 2){
				$teacherInfo = $this->getTeacherInfo($objIdArr);

			}elseif($resVote->result->object_type == 1){
                if($objIdArr){
                    $courseInfo = $this->getPlanInfo($objIdArr,$resVote->result->object_type);
					$courseTmpArr = array();
					foreach($courseInfo as $k=>$v){
						$courseTmpArr[$v['course_id']] = $v;
					}
                }
			}
			
			foreach($optionData as &$ov){
				$ov->isOption     = !empty($optionIdArr[$ov->pk_option]) ? 1 : 0; 
				$ov->thumbDisplay = !empty(utility_cdn::file($ov->thumb_display)) ? utility_cdn::file($ov->thumb_display) : '';
				if(!empty($totalCount[$resVote->result->object_type])){
                    $maxTotal = max($totalCount[$resVote->result->object_type]);
                    $ov->preVoteCount = !empty($ov->total_count) ? ($ov->total_count/$maxTotal)*100 : 0;
                }else{
                    $ov->preVoteCount = 0;
                }
				if($resVote->result->object_type == 1){
					$ov->url = "/course.info.show/".$ov->object_id;
					$ov->teacherId = !empty($courseTmpArr[$ov->object_id]['admin_id']) ? $courseTmpArr[$ov->object_id]['admin_id'] : ''; 
					$ov->teacherName = !empty($courseTmpArr[$ov->object_id]['admin_real_name']) ? $courseTmpArr[$ov->object_id]['admin_real_name'] : ''; 
					$ov->teacherThumb = !empty($courseTmpArr[$ov->object_id]['admin_thumb_med']) ? $courseTmpArr[$ov->object_id]['admin_thumb_med'] : ''; 
				}elseif($resVote->result->object_type == 2){
					$ov->url = "/teacher/detail/entry/".$ov->object_id;
					$ov->teacherTitle  = !empty($teacherInfo[$ov->object_id]['title']) ? $teacherInfo[$ov->object_id]['title'] : '';
				}elseif($resVote->result->object_type == 3){
                    $ov->planName  = !empty($planInfo[$ov->object_id]['sectionName']) ? $planInfo[$ov->object_id]['sectionName'] : '';
                    $ov->courseName= !empty($planInfo[$ov->object_id]['corseName']) ? $planInfo[$ov->object_id]['corseName'] : '';
                    $ov->className = !empty($planInfo[$ov->object_id]['className']) ? $planInfo[$ov->object_id]['className'] : '';
					$ov->url = "/course.plan.play/".$ov->object_id;
					$ov->status = !empty($planInfo[$ov->object_id]['status']) ? $planInfo[$ov->object_id]['status'] : ''; 
					$ov->teacherId = !empty($planInfo[$ov->object_id]['admin_id']) ? $planInfo[$ov->object_id]['admin_id'] : ''; 
					$ov->teacherName = !empty($planInfo[$ov->object_id]['admin_real_name']) ? $planInfo[$ov->object_id]['admin_real_name'] : ''; 
					$ov->teacherThumb = !empty($planInfo[$ov->object_id]['admin_thumb_med']) ? $planInfo[$ov->object_id]['admin_thumb_med'] : ''; 
				}
			}
		}
		
		//投票内容
		$voteInfo = [
			'voteId'     => $resVote->result->pk_vote,
			'title'      => $resVote->result->name,
			'userName'   => !empty($userName) ? $userName : '',
			'userId'     => $resVote->result->fk_user,
			'descript'   => !empty($resVote->result->descript) ? $resVote->result->descript : '',
			'thumb'      => !empty($resVote->result->thumb) ? utility_cdn::file($resVote->result->thumb) : '',
			'thumb1'     => !empty($resVote->result->thumb1) ? utility_cdn::file($resVote->result->thumb1) : '',
			'thumb2'     => !empty($resVote->result->thumb2) ? utility_cdn::file($resVote->result->thumb2) : '',
			'objectType' => $resVote->result->object_type,
			'multiSelect'=> $resVote->result->multi_select,
			'selectCount'=> ($resVote->result->select_count==0) ? 1 : $resVote->result->select_count,
			'type'       => $resVote->result->type,
			'status'     => $resVote->result->status,
			'totalCount' => $resVote->result->total_count,
			//'startTime'  => date("n-j H:i",strtotime($resVote->result->start_time)),
			//'endTime'    => date("Y/n/j",strtotime($resVote->result->end_time))
			'startTime'  => date("n月j日H:i",strtotime($resVote->result->start_time)),
			'endTime'    => date("n月j日H:i",strtotime($resVote->result->end_time))
		];
		
		$newTime = time();
		if( ($newTime >= strtotime($resVote->result->start_time)) && ($newTime < strtotime($resVote->result->end_time)) ){
			$voteInfo['proceed'] = Slanguage::tr('进行中','org');
			$voteInfo['proceedType'] = 1;
		}else if($newTime > strtotime($resVote->result->end_time) ){
			$voteInfo['proceed'] = Slanguage::tr('已结束','org');
			$voteInfo['proceedType'] = 2;
		}else{
			$voteInfo['endTime'] = date("Y/n/j H:i:s",strtotime($resVote->result->end_time));
			$voteInfo['proceed'] = Slanguage::tr('未开始','org');
			$voteInfo['proceedType'] = 3;
		}
		
		$header = utility_net::isHTTPS() ? "https://" : "http://";
        $url = '';
		$urlImg = '';
		$subdomainRes = user_organization::subdomain();
		
		if(!empty($subdomainRes->subdomain)){
			$sc = substr_count($subdomainRes->subdomain,'.');
			if($sc > 0){
				$url    = $header.$subdomainRes->subdomain;
				//$urlImg = $header.$subdomainRes->subdomain.'/assets_v2/img/vote.png';
			}else{
				$dom = utility_net::getDomainRoot();
				$url = $header.$subdomainRes->subdomain.'.'.$dom;
				//$urlImg = $header.$subdomainRes->subdomain.'/assets_v2/img/vote.png';
			}
		}
		
		$orgRes = user_organization::getOrgInfoByUidArr(array($resVote->result->fk_user_owner));
		$orgName = !empty($orgRes[0]->subname) ? $orgRes[0]->subname : (!empty($orgRes[0]->name) ? $orgRes[0]->name :'');
		$orgLogo = !empty($orgRes[0]->thumb_med)?utility_cdn::file($orgRes[0]->thumb_med):'';
		$this->assign('voteImg', interface_func::imgUrl('6,01155cb427b585'));
		$path = "/org/poll/info/".$voteId;
		$this->assign('orgName',$orgName);
		$this->assign('orgLogo',$orgLogo);
		$this->assign('url',$url);
		$this->assign('path',$path);
		$this->assign('uid',$uid);
		$this->assign('isVote',$isVote);
		$this->assign('optionData',$optionData);
		$this->assign('info',$voteInfo);
		return $this->render("/org/vote.sea.v2.html");
	}
	
	/*
	 * ajax 记录投票
	 */
	public function pageVoteAjax(){
		$result = new stdClass;

		if(empty($_POST['voteId']) || empty($_POST['siteId']) || empty($_POST['userId'])){
			$result->msg  = '请至少勾选一项';
			$result->code = -2;
			return $result;
		}
		$voteId = (int)($_POST['voteId']);
		$siteId = $_POST['siteId'];
		$userId = (int)($_POST['userId']);
		
		if($userId != $this->user['uid']){
			$result->msg = '没有权限';
			$result->code = -3;
			return $result;
		}
		
		$resVote = org_api::voteOne($voteId);
		
		if($resVote->code != 0){
			$result->msg = '非法投票';
			$result->code = -4;
			return $result;
		}
		
		$maxVote = ($resVote->result->select_count==0) ? 1 : $resVote->result->select_count;
		if(count($siteId) > $maxVote){
			$result->msg = '您最多只能投'.$maxVote;
			$result->code = -5;
			return $result;	
		}
		
		$newTime = time();
		if($newTime > strtotime($resVote->result->end_time) ){
			$result->msg = '该投票已结束';
			$result->code = -6;
			return $result;
		}
		if($newTime < strtotime($resVote->result->start_time)){
			$result->msg = '该投票未开始';
			$result->code = -7;
			return $result;
		}
		
		$userRes = org_api::userLogList(array('voteId'=>$voteId,'userId'=>$userId));
		if(!empty($userRes->items)){
			$result->msg = '你已经投过了';
			$result->code = -5;
			return $result;
		}
		
		foreach($siteId as $v){
			$data = [
				'userId'   => $userId,
				'voteId'   => $voteId,
				'optionId' => $v
			];
			$res = org_api::userLogAdd($data);
		}
		
		if($res->code==0){
			//修改t_vote 
			$data['voteId'] = $resVote->result->pk_vote;
			
			$data['userCount'] = $resVote->result->user_count+1;
			org_api::voteUpdate($data);
			
			$result->msg = '投票成功';
			$result->code  = 1;
			return $result;
		}else{
			$result->msg = '投票失败';
			$result->code  = -1;
			return $result;
		}
	}
	
	//登录弹窗
	public function pageUserLogin($inPath){
		$this->render("layer/vote.login.html");
	}
	
	 public function pageLoginAjax(){
		$result = new stdClass;
        if(empty($_REQUEST['uname'])){
			$result->msg = '请输入账号';
			$result->code = -1;
			return $result;
        }
        if(empty($_REQUEST['password'])){
			$result->msg = '请输入密码';
			$result->code = -2;
			return $result;
        }

        if(utility_valid::mobile($_REQUEST['uname']) === false){
			$result->msg = '手机号码格式不正确';
			$result->code = -3;
			return $result;
        }

        if(user_api::isRegister($_REQUEST['uname']) === false){
			$result->msg = '该手机号未注册';
			$result->code = -4;
			return $result;
        }
        $forever=false;
        if(!empty($_REQUEST['forever'])){
            $forever=true;
        }
        $ret = user_api::login($_REQUEST['uname'],$_REQUEST['password'],$forever);
        $uid = !empty(utility_session::get()['user']['uid']) ? utility_session::get()['user']['uid'] : 0;
		
        if($uid){
            $result->msg = '登录成功';
			$result->code = 1;
			return $result;
        }else{
			$result->msg = '密码错误';
			$result->code = -5;
			return $result;
        }
    }
		
	//章节信息
	private function getPlanInfo($planArr,$objectType=''){
        if(empty($planArr)) return array();
        $data = array();
        $planStr = implode(',',$planArr);
		if(empty($objectType)){
			$planParam = [
				'q' => ['status' => '1,2,3','plan_id'=>$planStr],
				'f' => ['section_name','plan_id','class_name','course_name','status','admin_real_name','admin_id','course_id','admin_thumb_med'],
				'p' => 1,
				'pl'=> 2000,
			];
		}elseif($objectType==1){
			$courseStr = implode(',',$planArr);
			$planParam = [
				'q' => ['status' => '1,2,3','course_id'=>$courseStr],
				'f' => ['section_name','plan_id','class_name','course_name','status','admin_real_name','admin_id','course_id','admin_thumb_med'],
				'p' => 1,
				'pl'=> 2000,
			];
		}
		$planRes = seek_api::seekplan($planParam);
        if(!empty($planRes->data)){
            foreach($planRes->data as $v){
                $data[$v->plan_id] = [
                    'planId'      => $v->plan_id,
                    'sectionName' => $v->section_name,
                    'className'   => $v->class_name,
                    'corseName'   => $v->course_name,
					'status'      => $v->status,
					'admin_id'    => $v->admin_id,
					'course_id'   => $v->course_id,
					'admin_real_name'      => $v->admin_real_name,
					'admin_thumb_med'      => $v->admin_thumb_med,
                ];
            }
        }
        return $data;
    }
	
	//老师信息
	private function getTeacherInfo($teacherArr){
        if(empty($teacherArr)) return array();
        $data = array();
        $teacherStr = implode(',',$teacherArr);
        $teacherParams = [
			'q'  => ['teacher_status'=>1,'visiable'=>1,'teacher_id'=>$teacherStr],
			'f'  => ['teacher_id','title']
		];
		$teacherRes = seek_api::seekTeacher($teacherParams);
        if(!empty($teacherRes->data)){
            foreach($teacherRes->data as $v){
                $data[$v->teacher_id] = [
                    'title' => $v->title
				];
            }
        }
        return $data;
    }
	
	/*
	 * ajax 统计分享数
	 */
	public function pageStatShare(){
		$result = new stdClass;
		if(empty($_POST['voteId'])){
			$result->msg  = '参数错误';
			$result->code = -2;
			return $result;
		}
		$voteId = (int)($_POST['voteId']);
		$resVote = org_api::voteOne($voteId);
		
		if($resVote->code != 0){
			$result->msg = '该投票不存在';
			$result->code = -4;
			return $result;
		}
		
		$data = [
			'voteId'     => $resVote->result->pk_vote,
			'shareCount' => $resVote->result->share_count+1
		];
		
		org_api::voteUpdate($data);
		
	}
	
}
?>
