<?php
class member_list extends STpl{
	private $domain;
	private $orgOwner;
	private $orgInfo;
	private $user;
	private $orgId;
	function __construct(){
        $this->user = user_api::loginedUser();
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
	}
	public function pageEntry($inPath){
		$msetList = user_organization::getMemberSetList($this->orgId,1);
		if(empty($msetList)){
			$this->redirect('/course.list');
			exit;
		}
		$validIdArr = array();
		if(!empty($this->user['uid'])){
			$uid = $this->user['uid'];
			$memberRet = org_member_api::getValidMemberListByUid($uid);
			if(!empty($memberRet)){
				foreach($memberRet as $mo){
					$validIdArr[] = $mo->fk_member_set;
				}
			}
		}else{
			$uid = 0;
		}
		if(!empty($msetList)){
			foreach($msetList as &$so){
				if(in_array($so->pk_member_set,$validIdArr)){
					$so->is_open = 1;
				}else{
					$so->is_open = 0;
				}
				if(strlen($so->descript) > 75){
					$so->descript_part = mb_substr($so->descript,0,75,'utf-8').'...';
				}else{
					$so->descript_part = $so->descript;
				}
			}
		}
		$this->assign('uid',$uid);
		$this->assign('msetList',$msetList);
		$this->render("member/member.list.html");
	}

	
}

