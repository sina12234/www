<?php
class org_custom extends STpl{
//插入
public $user;
public $domain;
public $orgInfo;
public $orgId;
public $subdomain;
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
	$this->subdomain = $org->subdomain;
    //管理员
    $isAdmin=user_api::isAdmin($this->orgOwner,$this->user['uid']);
    if($isAdmin===false){			
        header('Location: //'.$org->subdomain.'.'.$this->domain);
    }
} 

//点击保存  提交到这里
public  function pageEditCustomNav($inPath){
    $params = $_POST;
    $data = !empty($params) ? $params : '';
    if($data){
        if($data['item']) {
            foreach($data['item'] as $k=>$v) {
                if(empty($v['title']) || empty($v['url'])) continue;
                if (isset($v['title']) && !empty(trim($v['title'])) && isset($v['url']) && !empty(trim($v['url']))) {
                    $title = trim($v['title']);
                    $url = trim($v['url']);
                }
                if (isset($v['id']) && $v['id'] && !empty($title) && !empty($url)) {
                    //修改
                    $v['id'] = intval(trim($v['id']));
                    $v['org_id'] = $this->orgId;
                    $title = trim($v['title']);
                    $title =$this->title($title);
                    $v['sort'] = 0;
                    $v['status'] = 0;
                    $v['title'] = addslashes($title);
                    if(preg_match("/http/i",$url)){
                        $v['url'] = addslashes($url);
                    }else{
                        $v['url'] = "http://".addslashes($url);
						}
						org_api::ModNav($v);
					} elseif (!isset($v['id']) && !empty($url) && !empty($title)) {
						//添加
						$v['org_id'] = $this->orgId;
						$title = trim($v['title']);
						$title =$this->title($title);
						$v['sort'] = 0;
						$v['status'] = 0;
						$v['title'] = addslashes($title);
						if(preg_match('/http/i',$url)){
							$v['url'] = addslashes($url);
						}else{
							$v['url'] = "http://".addslashes($url);
						}
						org_api::addNav($v);
					}
				}
			}
			if(isset($data['removeId']) && $data['removeId']){
				foreach($data['removeId'] as $vv){
					$con['org_id'] = $this->orgId;
					$con['id'] = $vv;
					org_api::delNav($con);
				}
			}
		}
		return json_encode(['code'=>200]);
	}
	//判断title
	public function title($title){
		if(preg_match_all("/([\x80-\xff]*)/i",$title) && !preg_match_all("|[0-9a-zA-Z]+|",$title)) {
			//中文4个  截取前4个
			$title = mb_substr($title,0,4,'utf-8');
		}else{
			//中英hun排  英文
			$title = mb_substr($title,0,5,'utf-8');
		}
		return $title;
	}

	//select
	public function pageSelCustomNav($inPath){
		$arr = array('org_id'=>$this->orgId,'status'=>'0');
        $ret = org_api::SelNav($arr);
        echo (json_encode($ret));
	}

	//后台查看数据
	public function pageShowNav(){
		$arr = array('org_id'=>$this->orgId,'status'=>'0');
		org_api::addCustomNav($this->orgId,$this->subdomain);
		$ret = org_api::SelNav($arr);
		$this->assign('ret',$ret);
	}
	
	public function pageCusNav(){
		
		$this->render('org/iframe.custom.nav.html');
	}
}
