<?php
class org_main extends STpl{
	private $domain;
	private $orgOwner;
	private $orgInfo;
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
		$this->assign('is_pro',isset($this->orgInfo->is_pro) ? $this->orgInfo->is_pro : 0);
		$this->orgId = $this->orgInfo->oid;
        //判断管理员
        $isAdmin=user_api::isAdmin($this->orgOwner,$this->user['uid']);
        if($isAdmin===false){			
            header('Location: //'.$org->subdomain.'.'.$this->domain);
        }
	}
	public function pageAddXiaowoOrg($inPath){
		$result=new stdClass;
        if(empty($_POST['thumb_url'])){
			$result->error="链接不能为空";
			return $result;
        }else{
			preg_match("/^[a-zA-z]+:\/\/[\w.]+gn100.com|yunke.com([\w\/].*)$/",$_POST['thumb_url'],$matches);
			if(empty($matches[0])){
				$result->error="请使用本机构或云课链接地址~";
				return $result;
			}
		}
		if($type == '1'){
			$data = array("url_app"		=>	!empty($_POST['thumb_url']) ? $_POST['thumb_url'] : '',
						  "types"		=>	!empty($_POST['type']) ? $_POST['type'] : '',
						  "thumb_app"	=>	!empty($_POST['thumb_url']) ? $_POST['thumb_url'] : '',
						  );
		}elseif($type == '2'){
			$data = array("url_ipad"	=>	!empty($_POST['thumb_url']) ? $_POST['thumb_url'] : '',
						  "types"		=>	!empty($_POST['type']) ? $_POST['type'] : '',
						  "thumb_ipad"	=>	!empty($_POST['thumb_url']) ? $_POST['thumb_url'] : '',
						  );
		}
		$addInfo = user_organization::AddXiaowoOrg($data);
        if(!empty($addInfo)){
            $result->status="Success!";
            return $result;
        }else{
            $result->error="添加失败!";
            return $result;
        }
    }
	
	//修改手机banner图片
	public function pageUpdateXiaowoOrg($inPath){
        $result=new stdClass;
        if(empty($_POST['thumb_url'])){
			$result->error	=	"链接不能为空";
			return $result;
        }else{
			preg_match("/^[a-zA-z]+:\/\/[\w.]+gn100.com|yunke.com([\w\/].*)$/",$_POST['thumb_url'],$matches);
			if(empty($matches[0])){
				$result->error	=	"请使用本机构或云课链接地址~";
				return $result;
			}
		}
		if($type == '1'){
			$data = array("url_app"		=>	!empty($_POST['thumb_url']) ? $_POST['thumb_url'] : '',
						  "types"		=>	!empty($_POST['type']) ? $_POST['type'] : '',
						  "thumb_app"	=>	!empty($_POST['thumb_url']) ? $_POST['thumb_url'] : '',
						  );
		}elseif($type == '2'){
			$data = array("url_ipad"	=>	!empty($_POST['thumb_url']) ? $_POST['thumb_url'] : '',
						  "types"		=>	!empty($_POST['type']) ? $_POST['type'] : '',
						  "thumb_ipad"	=>	!empty($_POST['thumb_url']) ? $_POST['thumb_url'] : '',
						  );
		}
		$oid 	 = isset($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$addInfo = user_organization::UpdateXiaowoOrg($oid,$data);
        if(!empty($addInfo)){
            $result->status="Success!";
            return $result;
        }else{
            $result->error="添加失败!";
            return $result;
        }
    }
	//显示列表
	public function pageXiaowoOrgList(){
		$params   = array("fk_org"=>isset($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0);
		$listInfo = user_organization::XiaowoOrgList($params);
	}
	public function pageXiaowoOrgOneInfo(){
		$params   = array("fk_org"		=>	isset($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0,
						  "pk_banner"	=>	!empty($_POST['banner_id']) ? (int)$_POST['banner_id'] : 0
					);
		$listInfo = user_organization::XiaowoOrgOneInfo($params);	
	}
}

