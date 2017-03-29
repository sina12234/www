<?PHP

class org_base extends STpl
{
	public $user;
	public $domain;
	public $orgInfo;
	public $orgId;
	public function __construct()
	{
		//登陆信息
		$this->user = user_api::loginedUser();
		if(empty($this->user)){
			$this->redirect("/site.main.login");
		}
		//机构信息
		$domainConf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domainConf->domain;
		$org = user_organization::subdomain();
		if(empty($org)){
			header('Location: https://www.'.$this->domain);
		}
		$this->orgOwner = $org->userId;
		$this->orgInfo  = user_organization::getOrgByOwner($this->orgOwner);
		$this->orgId    = $this->orgInfo->oid;
		//管理员
		$isAdmin=user_api::isAdmin($this->orgOwner,$this->user['uid']);
        if($isAdmin===false){			
            header('Location: //'.$org->subdomain.'.'.$this->domain);
        }
	}
}
?>
