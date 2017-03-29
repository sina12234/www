<?php
class user_organization{
	//获取课程的连接
	public static function course_domain($subdomain){
		if(stripos($subdomain, ".")!==false){
			//全域名路径
			return $subdomain;
		}else{
			//二级域名路径
			$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
			return $subdomain.".".$domain_conf->domain;
		}
	}
	//获取domain
	public static function domain(){
		return $_SERVER['HTTP_HOST'];
	}
	//获取2级域名信息，如果是yunke的，只取前缀
	public static function subdomain(){
		//把100也当成100.yunke.com去机构域名里去获取，方便以后升级
		$domain = self::domain();
		$params=array(
			'subdomain'=>$domain,
		);
		$ret = utility_services::call("/user/organization/GetUserIdBySubDomain/",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}

		//库里没有数据，走兼容模式
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$domain = $domain_conf->domain;
		if(utility_net::getDomainRoot()!=$domain){
			$subdomain = $_SERVER['HTTP_HOST'];
		}else{
			$host=explode('.',$_SERVER['HTTP_HOST']);
			$subdomain=$host[0];
			$count=count($host);
			if($count!=3){
				return false;
			}
		}
		$params=array(
			'subdomain'=>$subdomain,
		);
		$ret = utility_services::call("/user/organization/GetUserIdBySubDomain/",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getSubdomainByUid($uid){
		$ret = utility_services::call("/user/organization/getSubDomainByUserId/".$uid);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getRegionList($params){
		$ret = utility_services::call("/region/main/ListRegion/",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	/*
	 *获取机构信息没有则创建一个仅有id的记录
	 */
	public static function getOrg($oid){
		$ret = utility_services::call("/user/organization/get/$oid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getOrgByUid($uid){
		$ret = utility_services::call("/user/organization/getByUid/$uid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getOrgByOwner($uid){
		$ret = utility_services::call("/user/organization/getOrgByOwner/$uid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

	
	public static function getOrgByUidTmp($uid){
		$ret = utility_services::call("/user/organization/getByUidTmp/$uid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	
	public static function getOrgByOwnerTmp($uid){
		$ret = utility_services::call("/user/organization/getOrgByOwnerTmp/$uid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getApplyOrgSubdomainOfUser($userId){
		$ret = utility_services::call("/user/organization/getApplyOrgSubdomainOfUser/".$userId);
		return !empty($ret->data) ? $ret->data : false;
		
	}
	public static function setOrgProfileTmp($uid,$data){

		$ret = utility_services::call("/user/organization/setInfoStatus/$uid",$data);
		if(!empty($ret->result) && $ret->result->code==0){
			return $ret->result;
		}
		return false;
	}
	
	public static function getOrgByTeacher($uid){
		$ret = utility_services::call("/user/organization/getByTeacher/$uid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	/**
	 * 设置机构信息
	 **/
	public static function setOrg($uid,$data){
		/*$params=new stdclass;
		$params->name        =empty($data['name'])?"":$data['name'];
		$params->thumb_big   =empty($data['thumb_big']  )?"":$data['thumb_big'];
		$params->thumb_mid   =empty($data['thumb_mid']  )?"":$data['thumb_mid'];
		$params->thumb_small =empty($data['thumb_small'])?"":$data['thumb_small'];
		$params->status        =empty($data['status'])?0:$data['status'];
		$params->desc        =$data['desc'];*/
		$ret = utility_services::call("/user/organization/set/$uid",$data);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
    //修改机构信息 新版
	public static function setOrgProfile($uid,$data){
		/*$params=new stdclass;
		$params->subname=$data['subname'];
		$params->company=$data['company'];
		$params->scopes=$data['scopes'];
		$params->province=$data['province'];
		$params->city=$data['city'];
		$params->county=$data['county'];
		$params->address=$data['address'];
		$params->email=$data['email'];
		$params->areacode=$data['areacode'];
		$params->hotline=$data['hotline'];
		//$params->extension=$data['extension'];
		$params->policy=$data['policy'];*/
		$ret = utility_services::call("/user/organization/setOrgProfile/$uid",$data);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
    public static function setOrgProfileRelateInfo($uid,$data){
        $ret = utility_services::call("/user/organization/setOrgProfileRelateInfo/$uid",$data);
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }
    //设置机构轮播图
	public static function setOrgSlide($sid,$uid,$params,$act){
        if($act=='edit'){
		    $ret = utility_services::call("/user/organization/updateOrgSlide/$sid",$params);
        }else{
		    $ret = utility_services::call("/user/organization/addOrgSlide/$uid",$params);
        }
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function delOrgSlide($sid){
		$ret = utility_services::call("/user/organization/delOrgSlide/$sid");
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}

	public static function delCheckInfo($bid){
		$ret = utility_services::call("/user/organization/delCheckInfo/$bid");
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}

	public static function setOrgLogo($uid,$data){
		$params=new stdclass;
		$params->thumb_small=$data['thumb_small'];
		$params->thumb_med=$data['thumb_med'];
		$params->thumb_big=$data['thumb_big'];
		$ret = utility_services::call("/user/organization/updateOrgLogo/$uid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}

	public static function updatehotType($uid,$data){
		$params=new stdclass;
		if(isset($data['hot_type'])){
			$params->hot_type		= isset($data['hot_type']) ? $data['hot_type'] : 1;
		}
		if(isset($data['living_show'])){
			$params->living_show	= isset($data['living_show']) ? $data['living_show'] : 1;
		}
		if(isset($data['is_nav'])){
			$params->is_nav	= isset($data['is_nav']) ? $data['is_nav'] : 2;
		}
		if(isset($data['is_cate'])){
			$params->is_cate	= isset($data['is_cate']) ? $data['is_cate'] : 2;
		}
		if(isset($data['site_skin'])){
			$params->site_skin	= isset($data['site_skin']) ? $data['site_skin'] : 1;
		}
		$ret = utility_services::call("/user/organization/updatehotType/$uid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}

	public static function getOrgLogo($uid){
		$ret = utility_services::call("/user/organization/getOrgLogo/$uid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getOrgSlide($sid){
		$ret = utility_services::call("/user/organization/getOrgSlide/$sid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getOrgSlideList($uid,$page=1,$size=5){
		$ret = utility_services::call("/user/organization/getOrgSlideList/$uid/$page/$size");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
    //获取机构关于我们
	public static function getOrgAbout($uid){
		$ret = utility_services::call("/user/organization/getOrgAbout/$uid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
    //修改机构关于我们
	public static function setOrgAbout($uid,$data){
		$params=new stdclass;
		$params->name=$data['name'];
		$params->desc=$data['desc'];
		$ret = utility_services::call("/user/organization/setTmp/$uid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	/**
	 * 修改LOGO图像
	 **/
	public static function updateLOGO($uid,$avatar = array(),&$ret=""){
		$params=new stdclass;
	//	$params->name        =$data['name'];
	//	$params->thumb_big   =empty($data['thumb_big']  )?"":$data['thumb_big'];
	//	$params->thumb_mid   =empty($data['thumb_mid']  )?"":$data['thumb_mid'];
	//	$params->thumb_small =empty($data['thumb_small'])?"":$data['thumb_small'];
		if($avatar['large']){
			$params->thumb_big=$avatar['large'];
		}
		if($avatar['medium']){
			$params->thumb_med=$avatar['medium'];
		}
		if($avatar['small']){
			$params->thumb_small=$avatar['small'];
		}
		$ret = utility_services::call("/user/organization/setlogo/$uid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	//课在未来扩展为列取本机构下的明星教师
	public static function listOrgUser($oid,$all=0,$is_star=0,$page=1,$size=5){
		$params=new stdclass;
		$params->all = $all;
		$params->is_star = $is_star;
		$ret = utility_services::call("/user/organization/userlist/$oid/$page/$size",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

    public static function getTeacherList($oid, $page=1, $length=100, $data)
    {
        $params = new stdclass;
        $params->orderBy = $data['orderBy'];
        $params->condition = $data['condition'];

        return utility_services::call("/user/organization/getOrgUserList/$oid/$page/$length", $params);
    }

	public static function getOrgUserinfo($oid,$uid,$page=1,$size=5){
		$params=new stdclass;
		$params->uid = $uid;
		$ret = utility_services::call("/user/organization/getorguserinfo/$oid/$page/$size",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function setteacherDisplay($oid,$params){
		$ret = utility_services::call("/user/organization/setteacherDisplay/$oid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function delUser($oid,$params){
		$ret = utility_services::call("/user/organization/userdel/$oid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function addUser($oid,$uid,$data){
		$params=new stdclass;
		$params->is_star = empty($data["is_star"])?0:$data["is_star"];
		$params->sort = empty($data["sort"])?0:$data["sort"];
		$params->user_role = empty($data["user_role"])?0:$data["user_role"];
		$ret = utility_services::call("/user/organization/userset/$oid/$uid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function Usersetsort($oid,$uid,$data){
		$params=new stdclass;
		$params->is_star = empty($data["is_star"])?0:1;
		$params->sort = empty($data["sort"])?0:$data["sort"];
		$ret = utility_services::call("/user/organization/usersetdata/$oid/$uid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
    public static function setStarteacher($oid,$uid,$params){
        $ret = utility_services::call("/user/organization/setOrgUserData/$oid/$uid",$params);
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }
	public static function getOrgSetHotType($uid){
		$ret = utility_services::call("/user/organization/getOrgSetHotType/$uid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getOrgOfNavList($oid){
		$ret = utility_services::call("/user/organization/getOrgOfNavList/$oid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function updateOrgOfNavOneInfo($params){
		$ret = utility_services::call("/user/organization/updateOrgOfNavOneInfo/",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function addOrgOfNav($params){
		$ret = utility_services::call("/user/organization/addOrgOfNav/",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function delOrgOfNav($params){
		$ret = utility_services::call("/user/organization/delOrgOfNav/",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
    public static function delHistoryStarTeacher($oid,$fk_user){
        $ret = utility_services::call("/user/organization/delHistoryStarteacher/".$oid,array('fk_user'=>$fk_user));
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }
    public static function setOrgUser($oid,$uid,$params){
        $ret = utility_services::call("/user/organization/setOrgUser/$oid/$uid",$params);
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }
	public static function countOrgRole($oid){
		$ret = utility_services::call("/user/organization/countOrgRole/$oid");
        return $ret;
	}
	public static function addOrg($params){
		return utility_services::call("/user/organization/addOrg",$params);
	}
	public static function addOrgVerify($params){
		$ret = utility_services::call("/user/organization/addOrgVerify",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function getOrgVerify($uid){
		$ret = utility_services::call("/user/organization/getOrgVerify/$uid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getOrgVerifyBySubDomain($subdomain){
        $params=array('subdomain'=>$subdomain);
		$ret = utility_services::call("/user/organization/getOrgVerifyBySubDomain/",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
    public static function getUserIdBySubDomain($subdomain){
        $params=array(
                'subdomain'=>$subdomain,
            );
		$ret = utility_services::call("/user/organization/GetUserIdBySubDomain/",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
    }
	public static function getCourseCateSomeName($params){
		$ret = utility_services::call("/user/organization/getCourseCateSomeName/",$params);
		if(!empty($ret->items)){
			return $ret->items;
		}
		return false;
    }
	public static function getOrgIdsByUid($uid){
		$ret = utility_services::call("/user/organization/getOrgIdsByUid/$uid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getSubDomainByOid($oid){
		$ret = utility_services::call("/user/organization/getSubDomainByOid/$oid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
    public static function getOrgInfoByOidArr($oidArr, $join = true){
        $url = '/user/organization/getOrgInfoByOidArr';
		$data = ['oidArr' => $oidArr, 'join' => $join];
        $ret=utility_services::call($url, $data);
		if(!empty($ret->items)){
			return $ret->items;
		}
		return false;
    }
	public static function orgAboutProfileInfo($ownerId){
        $url = "/user/organization/orgAboutProfileInfo/$ownerId";
        $ret=utility_services::call($url);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
    }
    public static function getOrgInfoByUidArr($uidArr){
        $url = '/user/organization/getOrgInfoByUidArr';
        $ret=utility_services::call($url, $uidArr);
		if(!empty($ret->items)){
			return $ret->items;
		}
		return false;
    }
	public static function getTeacherRealName($uidArr){
       $ret = utility_services::call("/user/organization/getTeacherRealName/$uidArr");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
    }
	
	public static function getOrgShowInfo($oid)
	{
		$ret = utility_services::call("user/organization/GetOrgShowInfo/".$oid);
		return $ret;
	}
    /**
     * 获取机构模版
     * @param $uid 
     * @return false or obiect
     * @author Panda <zhangtaifeng@gn100.com>
     */ 
	public static function getOrgTemplate($ownerId){
		$ret = utility_services::call("/user/organization/getOrgTemplate/".$ownerId);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
    /**
     * 获取机构模版信息
     * @param $tid 
     * @return false or obiect
     * @author Panda <zhangtaifeng@gn100.com>
     */ 
    public static function getOrgTemplateInfo($tid){
        $ret = utility_services::call("/user/organization/getOrgTemplateInfo/".$tid);
        if(!empty($ret->data)){
            return $ret->data;
        }
        return false;
    }
	/**
     * 从临时表t_organization_template_check获取机构模板课程
     * @param $ownerId 
     * @return false or obiect
     */ 
	public static function getTemplateCheck($ownerId){
		$ret = utility_services::call("/user/organization/getTemplateCheck/".$ownerId);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function updateOrgTemplate($tid,$params){
		$ret = utility_services::call("/user/organization/updateOrgTemplate/$tid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function addOrgTemplate($params){
		$ret = utility_services::call("/user/organization/addOrgTemplate",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function deleteOrgTemplate($tid){
		$ret = utility_services::call("/user/organization/deleteOrgTemplate/$tid");
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function deleteOrgTemplateMoreInfo($params){
		$ret = utility_services::call("/user/organization/deleteOrgTemplateMoreInfo/",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function dataOrgTeacherCount($orgId){
		$ret = utility_services::call("/user/organization/dataOrgTeacherCount/".$orgId);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

    /*
     * 查询机构教师数量
     * @param $owner,$status
     * @return int
     * @author Panda <zhangtaifeng@gn100.com>
     */
    public static function countTeacherByOid($oid, $status){
        $params= new stdCLass;
        $params->status=$status;
        $ret=utility_services::call("/user/organization/countTeacherByOid/$oid", $params);
        if (!empty($ret->data)) {
            return $ret->data->count;
        }
        return 0;
    }

	public static function searchOrgTeacherByRealName($orgId,$realName){
		$params = new stdClass;
		$params->oid = $orgId;
		$params->real_name = $realName;
		$ret = utility_services::call("/user/organizationUser/searchOrgTeacherByRealName", $params);
		if(!empty($ret->data)){
			return $ret;
		}else{
			return false;
		}
	}
	public static function getUserVerifyCodeLoginSms($params){
		$ret = utility_services::call("/user/organization/getUserVerifyCodeLoginSms",$params);
		if(!empty($ret->result) && $ret->result->code==100){
			return true;
		}
		return false;
	}
	
	public static function getOrgAccountByOrgId($orgId){
		$ret = utility_services::call("/user/organizationAccount/getOneByOrgId/".$orgId);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	
	public static function getDayPercent($orgId){

		$ret = utility_services::call("/stat/userOrgOrderStat/getDayPercent/".$orgId);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getWeekPercent($orgId){
		$ret = utility_services::call("/stat/userOrgOrderStat/getWeekPercent/".$orgId);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getMonthPercent($orgId){
		$ret = utility_services::call("/stat/userOrgOrderStat/getMonthPercent/".$orgId);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getWeekStat($orgId){
		$ret = utility_services::call("/stat/userOrgOrderStat/getWeekStat/".$orgId);
		//$ret = utility_services::call("/user/orgAccountOrderContent/getWeekPercent/".$orgId);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getMonthStat($orgId){
		$ret = utility_services::call("/stat/userOrgOrderStat/getMonthStat/".$orgId);
		//$ret = utility_services::call("/user/orgAccountOrderContent/getMonthPercent/".$orgId);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getOrgCardByOrgId($orgId){
		$ret = utility_services::call("/user/orgAccountCard/getOrgCardByOrgId/".$orgId);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getOrderCountByOrgId($orgId,$start='',$end=''){
		$params             = new stdCLass;
        $params->start_time = $start;
        $params->end_time   = $end;
		$ret = utility_services::call("/user/orgAccountOrderContent/getOrderCountByOrgId/".$orgId,$params);
		if (!empty($ret->data)) {
            return $ret->data;
        }
        return 0;
	}
	
	public static function getOrgAccountWithdrawList($orgId,$page,$length,$stime,$etime,$status){
		$params = new stdClass;
		$params->oid = $orgId;
		$params->page = $page;
		$params->length = $length;
		$params->start_time = $stime;
		$params->end_time = $etime;
		$params->status = $status;
		$ret = utility_services::call("/user/orgAccountWithdraw/list",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function updateOrgAccountWithdraw($withdrawId,$data){
		$ret = utility_services::call("/user/orgAccountWithdraw/update/".$withdrawId,$data);
		if($ret->data !== false){
			return true;
		}
		return false;
	}
	
	public static function addOrgWithdrawLog($uid,$orgId,$data){
		$params = new stdClass;
		$params->fk_org = $orgId;
		$params->fk_user_create = $uid;
		$params->fk_org_account_card = $data['cardId'];
		$params->withdraw = $data['withdraw']*100;
		$params->withdraw_org = $data['withdraw_org']*100;
		if(!empty($data['descript'])){
			$params->descript = $data['descript'];
		}
		$params->status = 0;
		$params->create_time = date('Y-m-d H:i:s',time());
		$ret = utility_services::call("/user/orgAccountWithdraw/add",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}	
	public static function addOrgCard($data){
		$ret = utility_services::call("/user/orgAccountCard/add",$data);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getMemberSetList($orgId,$status = ''){
		$params = new stdClass;
		if(is_numeric($status)){
			$params->status = $status;
		}
		$ret = utility_services::call("/user/orgMemberSet/listByOrgId/".$orgId,$params);
		if(!empty($ret->result)){
			return $ret->result;
		}
		return false;
	}

	public static function updateMemberSet($setId,$data){
		$ret = utility_services::call("/user/orgMemberSet/update/".$setId,$data);
		if($ret->result !== false){
			return true;
		}
		return false;
	}
	public static function addMemberSet($data){
		$ret = utility_services::call("/user/orgMemberSet/add/",$data);
		if(!empty($ret->result)){
			return $ret->result;
		}
		return false;
	}
	public static function getOrgRecommendList(){
		$url = "/user/organization/getOrgRecommendList";
        return utility_services::call($url);
	}
	public static function addMember($data){
		$ret = utility_services::call("/user/orgMember/add/",$data);
		if(!empty($ret->result)){
			return $ret->result;
		}

		SLog::fatal(
			'/user/orgMember/add/ failed, result[%s]',
			var_export(
				[
					'data'   => $data,
					'result' => $ret
				],
				1
			)
		);

		return false;
	}
	public static function updateMember($memberId,$data){
		$ret = utility_services::call("/user/orgMember/update/".$memberId,$data);
		if($ret->result !== false){
			return true;
		}
		SLog::fatal(
			'/user/orgMember/update/ failed, result[%s]',
			var_export(
				[
					'data'     => $data,
					'memberId' => $memberId,
					'result'   => $ret
				],
				1
			)
		);
		return false;
	}
	public static function checkMemberByUidAndSetId($userId,$setId){
		$params = new stdClass;
		$params->userId = $userId;
		$params->setId = $setId;
		$ret = utility_services::call("/user/orgMember/checkMemberByUidAndSetId/",$params);
		if(!empty($ret->result)){
			return $ret->result;
		}
		return false;
	}

	public static function getMemberByUidAndSetIdArr($userId, $setIdArr){
		if(empty($userId)||empty($setIdArr[0])){
			return false;
		}
		$params["userId"] = $userId;
		$params["setIdArr"] = $setIdArr;
		$ret = utility_services::call("/user/orgMember/GetMemberByUidAndSetIdArr/",$params);
		if(!empty($ret->result)){
			return $ret->result;
		}
		return false;
	}

	public static function getMemberBySetIdArr($setIdArr){
		$params = new stdClass;
		$params->setIdArr = $setIdArr;
		$ret = utility_services::call("/user/orgMember/GetMemberBySetIdArr/",$params);
		if(!empty($ret->result)){
			return $ret->result;
		}
		return false;
	}

	public static function addMemberLog($data){
		$ret = utility_services::call("/user/orgMemberLog/add/",$data);
		if(!empty($ret->result)){
			return $ret->result;
		}
		return false;
	}
	public static function getMemberListByMemberSetId($page,$length,$setId,$memberStatus=''){
		$params = new stdClass;
		if(is_numeric($memberStatus)){
			$params->member_status = $memberStatus;
		}	
		$params->page = $page;
		$params->length = $length;
		$ret = utility_services::call("/user/orgMember/getMemberListByMemberSetId/".$setId,$params);
		if(!empty($ret->result)){
			return $ret->result;
		}
		return false;
	}
	
	public static function updateMemberPriority($data){
		$ret = utility_services::call("/user/orgMemberPriority/update/",$data);
		if($ret->result !== false){
			return true;
		}
		return false;
	}
	
	public static function updateMemberPriorityByObjectId($objectId,$type,$setData){
		$params = new stdClass;
		$params->object_id = $objectId;
		$params->type = 1;
		$params->data = $setData;
		$ret = utility_services::call("/user/orgMemberPriority/updateByObjectId/",$params);
		if($ret->result !== false){
			return true;
		}
		return false;
	}
	public static function delMemberPriorityByObjectId($objectId,$type){
		$params = new stdClass;
		$params->object_id = $objectId;
		$params->type = 1;
		$ret = utility_services::call("/user/orgMemberPriority/delByObjectId/",$params);
		if($ret->result !== false){
			return true;
		}
		return false;
	}
	public static function addMemberPriority($data){
		$ret = utility_services::call("/user/orgMemberPriority/add/",$data);
		if(!empty($ret->result)){
			return $ret->result;
		}
		return false;
	}
	public static function getMemberPriorityByObjectId($objectId,$type){
		$params = new stdClass;
		$params->object_id = $objectId;
		$params->type = $type;
		$ret = utility_services::call("/user/orgMemberPriority/getMemberPriorityByObjectId/",$params);
		if(!empty($ret->result)){
			return $ret->result;
		}
		return false;
	}

	public static function getOrgProfileInfo($orgId)
	{
		$url = "/user/organization/GetOrgProfileInfo/{$orgId}";

		$res = interface_func::requestApi($url);
		if (!empty($res['code'])) {
			return [];
		}

		return $res['result'];
	}
	public static function getSubdomainByNameIsExist($subdomain){
		$params = new stdClass;
		$params->subdomain = $subdomain;
        $ret = utility_services::call("/user/organization/getSubdomainByNameIsExist/",$params);
        if(!empty($ret->data)){
            return $ret->data;
        }
        return false;
    }
	/**
     * 获取自定义模版指定信息
     * @param $tid 
     * @return false or obiect
     */ 
    public static function getTemplateData($tid,$ownerId){
        $ret = utility_services::call("/user/organization/getTemplateData/".$tid,$ownerId);
        if(!empty($ret->data)){
            return $ret->data;
        }
        return false;
    }
	/**
     * 修改自定义模版指定信息
     * @param $data 
     * @return false or obiect
     */ 
    public static function updateTemplateData($params){
        $ret = utility_services::call("/user/organization/updateTemplateData/",$params);
        if(!empty($ret->result->code)&&$ret->result->code==100){
            return $ret->result->msg;
        }
        return false;
    }
	/**
     * 增加自定义模版信息
     * @param $data 
     * @return false or obiect
     */ 
    public static function addTemplateData($params){
        $ret = utility_services::call("/user/organization/addTemplateData/",$params);
        if(!empty($ret->result->code)&&$ret->result->code==100){
            return $ret->result->msg;
        }
        return false;
    }
	
	public static function getUserMember($data, $page='', $length=''){
 		$params = new stdClass;
 		if(isset($data['memberStatus']) && is_numeric($data['memberStatus'])){
 			$params->member_status = $data['memberStatus'];
 		}
	
 		$params->userId = !empty($data['userId']) ? $data['userId'] : 0;
 		$params->orgId  = !empty($data['orgId']) ? $data['orgId'] : 0;
 
 		$ret = utility_services::call("/user/orgMember/getUserMember/{$page}"."/".$length, $params);
 		if(!empty($ret->result)){
 			return $ret->result;
 		}
 		return false;
 	}
	public static function updateThumbPic($params){
        $ret = utility_services::call("/user/organization/updateThumbPic/",$params);
        if(!empty($ret->result->code)&&$ret->result->code==100){
            return $ret->result->msg;
        }
        return false;
    }
	public static function xiaowoOrgList($params){
        $ret = utility_services::call("/user/orgSetting/xiaowoOrgList/",$params);
        if(!empty($ret->data)){
            return $ret->data;
        }
        return false;
    }
	public static function xiaowoOrgOneInfo($params){
        $ret = utility_services::call("/user/orgSetting/XiaowoOrgOneInfo/",$params);
        if(!empty($ret->data)){
            return $ret->data;
        }
        return false;
    }
	public static function addXiaowoOrg($params){
        $ret = utility_services::call("/user/orgSetting/AddXiaowoOrg/",$params);
        if(!empty($ret->result->code)&&$ret->result->code==100){
            return $ret->result->msg;
        }
        return false;
    }
	public static function updateXiaowoOrgBanner($bid,$params){
        $ret = utility_services::call("/user/orgSetting/updateXiaowoOrgBanner/$bid",$params);
        if(!empty($ret->result->code)&&$ret->result->code==100){
            return $ret->result->msg;
        }
        return false;
    }
	public static function addOrgCustomerCate($params){
        $ret = utility_services::call("/user/orgSetting/addOrgCustomerCate/",$params);
        if(!empty($ret->result->code)&&$ret->result->code==100){
            return $ret->result->msg;
        }
        return false;
    }
	public static function getOrgCustomerCateByInfo($params){
        $ret = utility_services::call("/user/orgSetting/getOrgCustomerCateByInfo/",$params);
        if(!empty($ret->data)){
            return $ret->data;
        }
        return false;
    }
	public static function getOrgCustomerCateList($params){
        $ret = utility_services::call("/user/orgSetting/getOrgCustomerCateList/",$params);
        if(!empty($ret->data)){
            return $ret->data;
        }
        return false;
    }
	public static function addCustomerCate($params){
        $ret = utility_services::call("/user/orgSetting/addCustomerCate/",$params);
        if(!empty($ret->result->code)&&$ret->result->code >100){
            return $ret->result->msg;
        }else{
			return $ret;
		}
    }
	public static function delCourseTag($courseId, $groupId, $tagId){
		if(empty($courseId) || empty($groupId) || empty($tagId)) return false;
		$params = new stdClass;
		$params->courseId = $courseId;
		$params->groupId  = $groupId;
		$params->tagId    = $tagId;//数组形式
		$ret = utility_services::call("/tag/tag/delCourseTag/",$params);

		if(empty($ret->code)){
			return true;
		}
		
		return false;
	}
	public static function getDomainByOwner($params){
        $ret = utility_services::call("/user/organization/getDomainByOwner/",$params);
        if(!empty($ret)){
            return $ret;
        }
        return false;
    }

	public static function addSmsMoney($params){
		$ret = utility_services::call("/user/organizationAccount/AddSmsMoney",$params);
		return $ret;
	}
}
