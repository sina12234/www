<?php
class user_home extends STpl{
	var $user;
	function __construct(){
		//如果没有登陆到登陆界面
		$this->user = user_api::loginedUser();
		if(empty($this->user)){
			$this->redirect("/site.main.login");
		}
		$org=user_organization::subdomain();
		if(!empty($org)){
			$this->orgOwner = $org->userId;
		}else{
			$this->orgOwner = 2;
		}
	}
	public function pageMenu($inPath){
		$userinfo=user_api::getUser($this->user['uid']);
		$ipinfo = utility_ip::info(utility_ip::realIp());
		if(isset($userinfo->profile->desc) && strlen($userinfo->profile->desc)>7){
			$userinfo->profile->desc = mb_substr($userinfo->profile->desc,0,7,'utf-8').'..';
		}
		$orgUserId = $this->orgOwner ;
        $user=$this->user;
        $orgInfo = user_organization::getOrgByUid($this->orgOwner);
        //查询老师与当前机构是否有关系 role oid等
        $special = user_api::getTeacherSpecial($orgInfo->oid,$user['uid']);
        if($user['types']->organization == TRUE ){
            if($this->orgOwner!= $user['uid']&&(empty($special)||$special->role!=2)){
                $user['types']->organization = FALSE;
            }
        }
        if( $user['types']->teacher == TRUE){
            if(empty($special)){
                $user['types']->teacher = FALSE;
            }
        }   
		$this->assign("userInfoMenu",$userinfo);
		$this->assign("ipinfo",$ipinfo);
		return $this->render("user/menu.html");
	}
}
