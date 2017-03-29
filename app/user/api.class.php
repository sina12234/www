<?php
if(!defined("COOKIE_TOKEN_NAME")) define("COOKIE_TOKEN_NAME","token");
if(!defined("COOKIE_UID_NAME")) define("COOKIE_UID_NAME","uid");
class user_api{
	/**
	 * 根据用户名和密码的登陆接口
	 */
	public static function uidName(){
		return COOKIE_UID_NAME;
	}
	public static function tokenName(){
		return COOKIE_TOKEN_NAME;
	}
	public static function login($uname,$password,$forever,&$ret=null){
		$uid = self::verifyByUname($uname,$password,$ret);
		if($uid){
			return self::loginByUid($uid,$forever,$ret);
		}else{
			self::logout();
		}
		return false;
	}

	public static function verifyByUname($uname,$password,&$ret=null){
		$params=new stdclass;
		$params->uname=$uname;
		$params->password=$password;
		$params->login_ip=utility_ip::realIp();
		$ret = utility_services::call("/user/auth/verify",$params);
		if(!empty($ret->data->uid)){
			return $ret->data->uid;
		}
		return false;
	}

	public static function verifyByUid($uid,$password){
		$params=new stdclass;
		$params->uid=$uid;
		$params->password=$password;
		$params->login_ip=utility_ip::realIp();
		$ret = utility_services::call("/user/auth/verify",$params);
		if(!empty($ret->data->uid)){
			return $ret->data->uid;
		}
		return false;
	}
	public static function verifyOnceToken($accountId,$onceToken){
		$params=new stdclass;
		$params->accountId=$accountId;
		$params->onceToken=$onceToken;
		$ret = utility_services::call("/xplatform/info/verifyOnceToken",$params);
		if(!empty($ret->data->uid)){
			return $ret->data->uid;
		}
		return false;
	}

	/**
	 * 直接通过用户id登陆
	 */
	public static function loginByUid($uid,$forever=false,$ret=null){
		$token="";
		if(!empty($_COOKIE[COOKIE_TOKEN_NAME])){
			$token_info = self::getToken($_COOKIE[COOKIE_TOKEN_NAME]);
			if(!empty($token_info->token)){
				$token=$token_info->token;
			}
		}

		$user_info = user_api::getUser($uid);
		$real_uid = 0;
		if(!empty($user_info)){
			$real_uid=$user_info->uid;
		}
		//刷新token
		$token = user_token::gen($real_uid,$platform=1,$user_status=1,$live_status=0,$token);
		utility_session::get()['token']=$token;
        //记录登录时间
        self::updateUser($real_uid,array("last_login"=>date('Y-m-d H:i:s')));
		if(!empty($user_info)){
			utility_session::get()['user']=array(
				"name"=>$user_info->name,
				"real_name" => empty($user_info->real_name) ? $user_info->profile->real_name : $user_info->real_name,
				"mobile" => $user_info->mobile,
				"uid"=>$user_info->uid,
				"token"=>$token,
				"large"=>$user_info->avatar->large,
				"medium"=>$user_info->avatar->medium,
				"small"=>$user_info->avatar->small,
				"types"=>$user_info->types,
				"xplatform_accountId"=>isset($ret->data->xplatform->accountId) ? $ret->data->xplatform->accountId : 0,
				"xplatform_refreshToken"=>isset($ret->data->xplatform->refreshToken) ? $ret->data->xplatform->refreshToken: "",
				"types"=>$user_info->types,
				"last_login"=>$user_info->last_login,
			);
		}
		//20天
		if($forever){
			@setcookie(COOKIE_UID_NAME,$real_uid,time()+20*24*60*60,"/",utility_net::getDomainRoot());
			@setcookie(COOKIE_TOKEN_NAME,$token,time()+20*24*60*60,"/",utility_net::getDomainRoot());
		}else{
			@setcookie(COOKIE_UID_NAME,$real_uid,0,"/",utility_net::getDomainRoot());
			@setcookie(COOKIE_TOKEN_NAME,$token,0,"/",utility_net::getDomainRoot());
		}
		if($real_uid)return true;
		return false;
	}

	/**
	 * 获取用户信息
	 */
	public static function getUser($uid){
		$ret = utility_services::call("/user/info/get/$uid");
		if(!empty($ret->data)){
			if(empty($ret->data->avatar)){
				$ret->data->avatar=new stdclass;
			}
			if(empty($ret->data->avatar->large)){
				$ret->data->avatar->large="/assets/images/defaultPhoto.gif";
			}else{
				$ret->data->avatar->large=utility_cdn::file($ret->data->avatar->large);
			}
			if(empty($ret->data->avatar->medium)){
				$ret->data->avatar->medium="/assets/images/defaultPhoto.gif";
			}else{
				$ret->data->avatar->medium=utility_cdn::file($ret->data->avatar->medium);
			}
			if(empty($ret->data->avatar->small)){
				$ret->data->avatar->small="/assets/images/defaultPhoto.gif";
			}else{
				$ret->data->avatar->small=utility_cdn::file($ret->data->avatar->small);
			}
			return $ret->data;
		}
		return false;
	}

    //获取老师特殊信息
	public static function getTeacherSpecial($oid,$uid){
		$ret = utility_services::call("/user/teacher/getTeacherSpecial/$oid/$uid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

	/**
	 * 修改用户密码
	 */
	public static function updatePassword($uid,$password,&$ret=""){
		$params=new stdclass;
		$params->password=$password;
		$ret = utility_services::call("/user/info/update/$uid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}

	/**
	 * 设置用户为老师
	 */
	public static function setTeacher($uid,&$ret=""){
		$user = self::getUser($uid);
		if(!empty($user->types)){
			$params=new stdclass;
			$params->types=$user->types;
			$params->types->teacher=true;
			$ret = utility_services::call("/user/info/update/$uid",$params);
			if(!empty($ret->result) && $ret->result->code==0){
				return true;
			}
		}
		return false;
	}

	/**
	 * 修改用户头像
	 */
	public static function updateThumbs($uid,$avatar=array(),&$ret=""){
		$params=new stdclass;
		$params->avatar=new stdclass;
		if($avatar['large']){
			$params->avatar->large=$avatar['large'];
		}
		if($avatar['medium']){
			$params->avatar->medium=$avatar['medium'];
		}
		if($avatar['small']){
			$params->avatar->small=$avatar['small'];
		}
		$ret = utility_services::call("/user/info/update/$uid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}

	public static function updateBase($uid,$name,$condition,$birthday,$real_name,$address,$desc,$zip_code,&$ret=""){
		$params=new stdclass;
		if(!empty($name))$params->name=$name;
		if(isset($condition) && !empty($condition) && is_array($condition)){
			$params->gender	= $condition['gender'];
			$params->type	= $condition['type'];
		}else{
			$params->gender	= $condition;
		}
		if(isset($birthday) && !empty($birthday)) $params->birthday=$birthday;
		if(isset($real_name) && !empty($real_name))$params->real_name=$real_name;
		$params->profile= new stdclass;
		if(isset($real_name) && !empty($real_name))$params->profile->real_name=$real_name;
		if(isset($address) && !empty($address))$params->profile->address=$address;
		if(isset($desc))$params->profile->desc=$desc;
		if(isset($zip_code) && !empty($zip_code))$params->profile->zip_code=$zip_code;
		$ret = utility_services::call("/user/info/update/$uid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}

	public static function singleTeacherInfoHave($uid,$real_name,$gender){
		$params=new stdclass;
		if(!empty($real_name))$params->real_name=$real_name;
		if(isset($gender) && !empty($gender))$params->gender=$gender;
		$ret = utility_services::call("/user/info/singleTeacherInfoHave/$uid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}

	public static function updateTagMapUser($uid,$good_subject){
		$params=new stdclass;
        if(!empty($good_subject))$params->good_subject=$good_subject;
		$ret = utility_services::call("/user/teacher/updateTagMapUser/$uid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}

	public static function updateBase2($uid,$data)
	{
		$params = new stdclass;
		$params->profile = new stdclass;
		if(isset($data['nickName']) && !empty($data['nickName']))
        {
            $params->name = $data['nickName'];
        }
        if(isset($data['gender']) && !empty($data['gender']))
        {
            $params->gender = $data['gender'];
        }
		if(isset($data['birthday']) && !empty($data['birthday']))
        {
            $params->birthday = $data['birthday'];
        }
		if(isset($data['realName']) && !empty($data['realName']))
        {
            $params->profile->real_name = $data['realName'];
        }
		if(isset($data['address']) && !empty($data['address']))
        {
            $params->profile->address = $data['address'];
        }
        if(isset($data['desc']) && !empty($data['desc']))
        {
            $params->profile->desc = $data['desc'];
        }
        if(isset($data['zip_code']) && !empty($data['zip_code']))
        {
            $params->profile->zip_code = $data['zip_code'];
        }

		$ret = utility_services::call("/user/info/update/$uid",$params);
		if(!empty($ret->result) && $ret->result->code==0)
		{
			return true;
		}
		return false;
	}

	/**
	 * 通过手机号注册用户,目前不开放邮箱注册
	 */
	public static function registerByMobile($name,$mobile,$password="",$ownerFrom=0,&$ret=""){
		$params=new stdclass;
		$params->name=$name;
		$params->real_name=$name;
		$params->register_ip=utility_ip::realIp();
		$params->mobile=$mobile;
		$params->password=$password;
		$params->owner_from=$ownerFrom;
		$thumb = SConfig::getConfig(ROOT_CONFIG."/user.conf","thumb_select");
		$key = array_rand($thumb);
		if(!empty($thumb[$key]->big)){ $params->thumb_big = $thumb[$key]->big; }
		if(!empty($thumb[$key]->med)){ $params->thumb_med = $thumb[$key]->med; }
		if(!empty($thumb[$key]->small)){ $params->thumb_small = $thumb[$key]->small; }
		/*
		$thumb = SConfig::getConfig(ROOT_CONFIG."/user.conf","thumb_default");
		if(!empty($thumb->big)){ $params->thumb_big = $thumb->big; }
		if(!empty($thumb->med)){ $params->thumb_med = $thumb->med; }
		if(!empty($thumb->small)){ $params->thumb_small = $thumb->small; }
		*/
		$ret = utility_services::call("/user/info/create",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return $ret->data->uid;
		}
		return false;
	}
	/**
	 * 通过parterner注册用户
	 */

	public static function registerByParternerId($name,$parterner_id){
		$params=new stdclass;
		$params->name=$name;
		$params->real_name=$name;
		$params->register_ip=utility_ip::realIp();
		$params->parterner_id=$parterner_id;
		$thumb = SConfig::getConfig(ROOT_CONFIG."/user.conf","thumb_select");
		$key = array_rand($thumb);
		if(!empty($thumb[$key]->big)){ $params->thumb_big = $thumb[$key]->big; }
		if(!empty($thumb[$key]->med)){ $params->thumb_med = $thumb[$key]->med; }
		if(!empty($thumb[$key]->small)){ $params->thumb_small = $thumb[$key]->small; }
		$ret = utility_services::call("/user/info/create",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return $ret->data->uid;
		}
		return false;
	}

	/**
	 * 判断手机号/邮箱是还是注册
	 */
	public static function isRegister($uname){
		$params=new stdclass;
		$params->uname=$uname;
		$ret = utility_services::call("/user/auth/check",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return $ret->data->uid;
		}
		return false;
	}

	/**
	 * 获取第3方用户登陆信息
	 */
	public static function getParterner($source,$parterner_uid="",$user_id=""){
		$params=new stdclass;
		$params->parterner=array("source"=>$source,"parterner_uid"=>$parterner_uid,"user_id"=>$user_id);
		$ret = utility_services::call("/user/parterner/get",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

	/**
	 * 绑定第3方登录信息
	 **/
	public static function bindParterner($parterner_id,$uid){
		$params=new stdclass;
		$params->parterner_id=$parterner_id;
		$params->uid=$uid;
		$ret = utility_services::call("/user/parterner/bind",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}

	/**
	 * 获取第3方绑定信息
	 **/
	public static function getParternerById($parterner_id){
		$params=new stdclass;
		$params->parterner_id=$parterner_id;
		$ret = utility_services::call("/user/parterner/get",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

	/**
	 * 设置第3方绑定信息
	 **/
	public static function setParterner($uid,$nickname,$source,$parterner_uid,$parterner_uinfo,$auth_code,$thumb_url){
		$params=new stdclass;
		$params->uid=$uid;
		$params->parterner=array(
			"nickname"=>$nickname,
			"source"=>$source,
			"parterner_uid"=>$parterner_uid,
			"parterner_uinfo"=>$parterner_uinfo,
			"auth_code"=>$auth_code,
			"thumb_url"=>$thumb_url,
		);
		$ret = utility_services::call("/user/parterner/set",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

	/**
	 * 第3方绑定信息，临时登陆信息
	 **/
	public static function parternerLogin($id,$name,$pic){
		utility_session::get()['parterner']=array("id"=>$id,"name"=>$name,"pic"=>$pic);
	}

	/**
	 * 第3方绑定信息，获取临时登陆信息
	 **/
	public static function getParternerLogin(){
		if(!empty(utility_session::get()['parterner'])){
			return utility_session::get()['parterner'];
		}
		return false;
	}

	/**
	 * 获取登陆的用户ID
	 **/
	public static function getLoginUid(){
		if(!empty(utility_session::get()['user']['uid'])){
			return utility_session::get()['user']['uid'];
		}
		return 0;
	}

	/**
	 * 获取登录的用户信息
	 **/
	public static function loginedUser(){
		if(self::logined()){
			return utility_session::get()['user'];
		}
		return false;
	}

	public static function getCookieToken(){
		if(!empty($_COOKIE[COOKIE_TOKEN_NAME])){
			return $_COOKIE[COOKIE_TOKEN_NAME];
		}elseif(!empty(utility_session::get()['token'])){
			return utility_session::get()['token'];
		}else{
			return "";
		}
	}
	public static function getTokenDirect(){
		return self::getCookieToken();
	}

	/**
	 * 判断是否登录，此接口会自动登录
	 **/
	public static function logined(){
		if(!empty(utility_session::get()['user'])){
			return true;
		}
		if(!empty($_COOKIE[COOKIE_TOKEN_NAME])){
			$token_info = self::getToken($_COOKIE[COOKIE_TOKEN_NAME]);
			if(!empty($_COOKIE[COOKIE_UID_NAME]) && !empty($token_info->uid) && $token_info->uid==$_COOKIE[COOKIE_UID_NAME]){
				return self::loginByUid($token_info->uid,true);
			}
			return false;
		}
		//为没有登录的用户生成token
		return self::loginByUid(0,false);
	}

	/**
	 * 登出
	 **/
	public static function logout(){
		$r = self::delToken(self::getTokenDirect());
		unset(utility_session::get()['user']);
		unset(utility_session::get()['parterner']);
		unset(utility_session::get()['login_url']);
		setcookie(COOKIE_UID_NAME,"",-1,"/",utility_net::getDomainRoot());
		setcookie(COOKIE_TOKEN_NAME,"",-1,"/",utility_net::getDomainRoot());
		return true;
	}

	/**
	 * 设置用户的学生信息
	 **/
	public static function setStudentProfile($uid,$Profile){
        $params=new stdclass;
		$params->grade=isset($Profile['grade'])?$Profile['grade']:0;;
		$params->student_name=addslashes($Profile['student_name']);
		$params->region_level0=	isset($Profile['region_level0'])?$Profile['region_level0']:0;
		$params->region_level1=	isset($Profile['region_level1'])?$Profile['region_level1']:0;
		$params->region_level2=	isset($Profile['region_level2'])?$Profile['region_level2']:0;
		$params->school_type=	isset($Profile['school_type'])?$Profile['school_type']:0;
		$params->school_id=		isset($Profile['school_id'])?$Profile['school_id']:0;
       $ret = utility_services::call("/user/student/set/$uid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}

    public static function setStudentProfile2($uid,$Profile)
    {
       $params = new stdclass;
       if(isset($Profile['grade']) && !empty($Profile['grade']))
       {
            $params->grade = $Profile['grade'];
       }
       if(isset($Profile['student_name']) && !empty($Profile['student_name']))
       {
            $params->student_name = $Profile['student_name'];
       }
       if(isset($Profile['region_level0']) && !empty($Profile['region_level0']))
       {
            $params->region_level0 = $Profile['region_level0'];
       }
       if(isset($Profile['region_level1']) && !empty($Profile['region_level1']))
       {
            $params->region_level1 = $Profile['region_level1'];
       }
       if(isset($Profile['region_level2']))
       {
            $params->region_level2 = $Profile['region_level2'];
       }
       if(isset($Profile['school_type']) && !empty($Profile['school_type']))
       {
            $params->school_type = $Profile['school_type'];
       }
       if(isset($Profile['school_id']) && !empty($Profile['school_id']))
       {
            $params->school_id = $Profile['school_id'];
       }

	   $ret = utility_services::call("/user/student/set2/$uid",$params);

	   if(!empty($ret->result) && $ret->result->code==0){
	    	return true;
	   }
	   return false;
    }

	public static function getStudentProfile($uid){
		$params=new stdclass;
		$ret = utility_services::call("/user/student/get/$uid",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

    public static function getsubjectTag($group_id){
		$params=new stdclass;
		$ret = utility_services::call("/tag/tag/getsubjectTag/$group_id");
		if(!empty($ret->items)){
			return $ret->items;
		}
		return false;
	}

	/**
	 * 获取用户的token信息
	 **/
	public static function getToken($token){
		$ret = utility_services::call("/user/token/get/$token");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;

	}
	public static function delToken($token){
		$ret = utility_services::call("/user/token/del/$token");
		if(!empty($ret->result->code) && $ret->result->code==1){
			return true;
		}
		return false;

	}

	public static function getTeacherInfo($uid){
		$ret = utility_services::call("/user/teacher/get/$uid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

	public static function getTeacherInfoByIds($tids){
		$ret = utility_services::call("/user/teacher/getTeacherInfoByIds/",$tids);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

	public static function setTeacherInfo($uid,$data){
		$datain = array(
			"title"=>"title",
			"college"=>"college",
			"years"=>"years",
			"scopes"=>"scopes",
			"diploma"=>"diploma",
			"desc"=>"desc",
			"major"=>"major",
			"good_subject"=>"good_subject",
			"brief_desc"=>"brief_desc"
		);
		$params=new stdclass;
		foreach($datain as $datak=>$datav){
			if(isset($data[$datak])){
				$params->$datak = $data[$datak];
			}
		}

		$ret = utility_services::call("/user/teacher/set/$uid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}

	public static function setTeacherInfoV2($uid,$data){
		$datain = array(
			"title"   => "title",
			"college" => "college",
			"years"   => "years",
			"scopes"  => "scopes",
			"diploma" => "diploma",
			"desc"    => "desc",
			"major"   => "major",
			"good_subject" => "good_subject",
			"brief_desc"   => "brief_desc"
		);
		$params = new stdclass;
		foreach($datain as $datak=>$datav){
			if(isset($data[$datak])){
				$params->$datak = $data[$datak];
			}
		}

		$ret = utility_services::call("/user/teacher/setV2/$uid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}

	public static function addfav($favdata){
		$params=new stdclass;
		$params->course_id=$favdata["course_id"];
		$params->user_id=$favdata["user_id"];
		$ret = utility_services::call("/user/info/addfav/",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}

	public static function listfav($favdata){
		$params=new stdclass;
		if(isset($favdata["cid"])){$params->cid=$favdata["cid"];}
		if(isset($favdata["uid"])){$params->uid=$favdata["uid"];}
		$page = 1;
		$length = -1;
		$ret = utility_services::call("/user/info/listfav/$page/$length",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

    /*
     * @author zhengtianlong
     */
    public static function listfav2($favdata,$page=1,$length=20){
		$params=new stdclass;
		if(isset($favdata["cid"])){$params->cid=$favdata["cid"];}
		if(isset($favdata["uid"])){$params->uid=$favdata["uid"];}
        $ret = utility_services::call("/user/info/listfav/$page/$length",$params);
        //临时方案
        if($length < 1000){
            return $ret;
        }
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

    // 删除收藏课程
    public static function delFav($params)
    {
        return utility_services::call("/user/info/delFav", $params);
    }

    public static function getUserIdByMobile($mobile)
    {
        return utility_services::call("/user/info/getUserIdByMobile/{$mobile}");
    }

    //添加公告
    public static function addNotice($data){
        $params=new stdclass;
        $params->fk_user_id=$data['fk_user_id'];
        $params->notice_title=$data['notice_title'];
        $params->notice_content=$data['notice_content'];
        $params->fk_cate=!empty($data['fk_cate']) ? $data['fk_cate'] : 0;
        $params->create_time=$data['create_time'];
        $params->update_time=$data['update_time'];
		if(!empty($data['notice_link'])){
			$params->notice_link=$data['notice_link'];
		}
        $ret=utility_services::call("/user/info/addNotice",$params);
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }

    //修改公告
    public static function updateNotice($nid,$data){
        $params=new stdclass;
        $params->notice_title=$data['notice_title'];
        $params->fk_cate=$data['fk_cate'];
        $params->notice_content=$data['notice_content'];
        $params->update_time=$data['update_time'];
        $ret=utility_services::call("/user/info/updateNotice/$nid",$params);
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }

    //删除公告
    public static function delNotice($nid){
        $ret=utility_services::call("/user/info/delNotice/$nid");
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }

    //置顶公告
    public static function topNotice($nid,$uid){
        $ret=utility_services::call("/user/info/topNotice/$nid/$uid");
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }

    //取消置顶公告
    public static function noTopNotice($nid){
        $ret=utility_services::call("/user/info/noTopNotice/$nid");
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }

    //公告列表
    public static function getNoticeList($page,$size,$userId,$cateId,$orgId){
        $params=new stdclass;
        $params->uid=$userId;
        $params->cateid=$cateId;
        $params->orgId=$orgId;
        $ret=utility_services::call("/user/info/getNoticeList/$page/$size",$params);
        return $ret;
    }

    //获取公告
	public static function getNotice($nid){
		$ret = utility_services::call("/user/info/getNotice/$nid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

    //根据老师获取作业列表
	public static function getTaskListByOwner($uid,$pn,$pl){
		$ret = utility_services::call('/user/task/getTaskListByOwner/'.$uid.'/'.$pn.'/'.$pl);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

    //根据计划获取作业
	public static function getTaskListByPlan($pid,$pn,$pl){
		$ret = utility_services::call('/user/task/getTaskListByPlan/'.$pid.'/'.$pn.'/'.$pl);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

    //统计交作业人数
    public static function countReply($tids){
        $ret = utility_services::call("/user/task/countReply",array('tids'=>$tids));
        if(!empty($ret)){
            return $ret;
        }
        return false;
    }

    //获取作业
    public static function getTask($tid){
        $ret = utility_services::call("/user/task/getTask/$tid");
        if(!empty($ret)){
            return $ret;
        }
        return false;
    }

    //添加作业
    public static function addTask($params){
        $ret=utility_services::call("/user/task/addTask/",$params);
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }

    //修改作业
    public static function updateTask($tid,$params){
        $ret=utility_services::call("/user/task/updateTask/$tid",$params);
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }

    //删除作业
    public static function deleteTask($tid){
        $ret=utility_services::call("/user/task/deleteTask/$tid");
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }

    //分发作业
    public static function addMoreReply($params){
        $ret=utility_services::call("/user/task/addMoreReply/",$params);
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }

    //获取作业列表
	public static function getReplyList($pn,$pl,$params){
		$ret = utility_services::call('/user/task/getReplyList/'.$pn.'/'.$pl,$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

	public static function getReplyListByTid($tid){
		$ret = utility_services::call('/user/task/getReplyListByTid/'.$tid);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

    //获取老师作业班级章节
    public static function getCourseClassSection($uid){
        $ret=utility_services::call('/user/task/getCourseClassSection/'.$uid);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
    }

    //获取学生作业班级
    public static function getReplyClass($uid){
        $ret=utility_services::call('/user/task/getReplyClass/'.$uid);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
    }

    //获取学生作业章节
    public static function getReplySection($uid){
        $ret=utility_services::call('/user/task/getReplySection/'.$uid);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
    }

    //获取学生作业详情
    public static function getReplyInfo($rid){
        $ret=utility_services::call('/user/task/getReplyInfo/'.$rid);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
    }

    //获取学生作业附件
    public static function getAttachList($rid){
        $ret=utility_services::call('/user/task/getAttachList/'.$rid);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
    }

    //统计学生各状态作业的数量
    public static function countReplyStatus($uid){
        $ret=utility_services::call('/user/task/countReplyStatus/'.$uid);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
    }

    //学生交作业
    public static function addAttach($params){
        $ret=utility_services::call('/user/task/addAttach/',$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
    }

	public static function getTeacherOrg($teacherUid){
		$ret = utility_services::call("/user/teacher/getUserOrg/$teacherUid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

	public static function getUserOwnerOrg($userId){
		$ret = utility_services::call("/user/organization/getByUid/$userId");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

	public static function getBasicUser($user_id){
		$ret = utility_services::call("/user/info/getBasicUser/$user_id");
		if(!empty($ret)){
			return $ret;
		}
		return false;
	}

	public static function getBasicUserAndMobile($user_id){
		$ret = utility_services::call("/user/info/getBasicUserAndMobile/$user_id");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

	public static function addUserFeedback($params){

        $ret=utility_services::call('/user/feedback/addUserFeedback/',$params);
		return $ret;

	}

	public static function getUserFeedbackByUid($user_id){
        $ret=utility_services::call("/user/feedback/getUserFeedbackByUid/$user_id");
		return $ret;
	}

	public static function getSubdomainByUidArr($uidArr){
		$params = new stdclass;
		$params -> uidArr = $uidArr;
        $ret=utility_services::call('/user/info/getSubdomainByUidArr/',$params);
		return $ret;
	}

	public static function getOrgProfileByUidArr($uidArr){

          $url = '/user/organization/getOrgProfileByUidArr/';
          return utility_services::call($url, $uidArr);
    }

	public static function getOrgProfileByOidArr($oidArr){
        $ret=utility_services::call('/user/organization/getOrgProfileByOidArr/', $oidArr);
        if(!empty($ret->data)){
            return $ret->data;
        }
        return false;
    }

	public static function getNewJoinOrg($page,$length){
          $url = '/user/organization/getNewJoinOrg/'.$page.'/'. $length;
          return utility_services::call($url);
    }

    public static function updateUserBase($uid,$params){
        $ret = utility_services::call("/user/info/update/$uid",$params);
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }

	public static function updateteacherUserRealName($uid,$params){
        $ret = utility_services::call("/user/info/updateteacherUserRealName/$uid",$params);
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }

	public static function getUserThumbByUid($uid,$limit){
        $url = '/user/thumb/getUserThumbByUid/'.$uid.'/'. $limit;
        return utility_services::call($url);
	}

	public static function addUserThumb($params){
        $url = '/user/thumb/addUserThumb/';
        return utility_services::call($url, $params);
	}

    public static function setTeacherSpecial($oid,$uid,$params){
        $ret = utility_services::call("/user/teacher/setTeacherSpecial/$oid/$uid",$params);
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }

	public static function getUserMobileByUidArr($uidsArr){
		$ret=utility_services::call("/user/info/getUserMobileByUidArr",$uidsArr);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

	public static function updateUserProfile($uid,$params){
		$ret = utility_services::call("/user/info/updateUserProfile/$uid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}

	/**
	 * 这个方法只更新 t_user.last_updated , last_login
	 * 如果要更新用户信息，请使用 updateUserBase
	 */
	public static function updateUser($uid,$params){
		$ret = utility_services::call("/user/info/updateUser/$uid",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}

	public static function getOrgTeacherCount($oidArr){
        $url = '/user/organization/getOrgTeacherCount';
        return utility_services::call($url,$oidArr);
	}

    public static function listUsersByUserIds($idArr)
    {
        $url = '/user/info/listUsersByUserIds';

        return utility_services::call($url, $idArr);
    }

    public static function listUserBasicInfo($userIdArr)
    {
        if (empty($userIdArr) || count($userIdArr) < 1) return [];
        $res = utility_services::call('/user/info/listUsersByUserIds', $userIdArr);

        $data = [];
        if (!empty($res->result)) {
            foreach ($res->result as $v) {
                $data[$v->pk_user] = [
                    'name'     => !empty($v->real_name) ? $v->real_name : (!empty($v->name) ? $v->name : ''),
                    'thumb'    => interface_func::imgUrl($v->thumb_big),
                    'thumbMed' => interface_func::imgUrl($v->thumb_med),
                    'thumbSma' => interface_func::imgUrl($v->thumb_small)
                ];
            }
        }

        return $data;
    }

    /*
     * 统计按地区机构数
     * @author zhengtianlong
     */
    public static function getOrgCount($cityId)
    {
        return utility_services::call('/user/organization/getcount/'.$cityId);
    }

    public static function getOrgByCity($cityId,$page=1,$length='-1')
    {
        return utility_services::call('/user/organization/getOrgByCity/'.$cityId.'/'.$page.'/'. $length);
    }

	public static function getOrgByCid($cityId,$page=1,$length='-1')
    {
        return utility_services::call('/user/organization/getOrgByCid/'.$cityId.'/'.$page.'/'. $length);
    }

	public static function checkNickName($uid,$nickName){
		$params = new stdclass;
		$params->nickname = addslashes($nickName);
		$params->uid = $uid;
		$url = '/user/info/checkName';
        return utility_services::call($url,$params);
	}

	public static function getUserProfileByUidArr($uidArr){
		$params = new stdclass;
		$params->uids = new stdclass;
		if(!empty($uidArr)){
			$params->uids  = $uidArr;
		}
		$ret = utility_services::call("/user/info/getUserProfileByUidArr",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

	public static function listUserIdsBylikeMobileArr($uidsArr,$mobile){
		$params = new stdclass;
		$params->uids = new stdclass;
		if(!empty($uidsArr)){
			$params->uids  = $uidsArr;
		}
		if(!empty($mobile)){
			$params->mobile  = $mobile;
		}
		$ret = utility_services::call("/user/info/listUserIdsBylikeMobileArr",$params);
		if(!empty($ret->data)){
			return $ret;
		}
		return false;
	}

	public static function listUserIdsBylikeNameArr($uidsArr,$realName){
		$params = new stdclass;
		$params->uids = new stdclass;
		if(!empty($uidsArr)){
			$params->uids  = $uidsArr;
		}
		$params->real_name  = "";
		if(!empty($realName)){
			$params->real_name  = $realName;
		}
		$ret = utility_services::call("/user/info/listUserIdsBylikeNameArr",$params);
		if(!empty($ret->data)){
			return $ret;
		}
		return false;
	}

    //判断是否为机构下的管理员
	public static function isAdmin($owner,$uid){
		$params=new stdclass;
		$params->owner=$owner;
		$params->uid=$uid;
		$ret = utility_services::call("/user/auth/userRole",$params);
		if(!empty($ret->result)&&$ret->result->code==0&&!empty($ret->roles)){
            //判断是否为创建者或管理员
            if(in_array('owner',$ret->roles)||in_array('admin',$ret->roles)){
			    return true;
            }else{
		        return false;
            }
		}
		return false;
	}

    //判断是否为机构下的老师
	public static function isTeacher($owner,$uid){
		$params=new stdclass;
		$params->owner=$owner;
		$params->uid=$uid;
		$ret = utility_services::call("/user/auth/userRole",$params);
		if(!empty($ret->result)&&$ret->result->code==0&&!empty($ret->roles)){
            //判断是否为普通老师或助教 管理员也是老师
            if(in_array('general',$ret->roles)||in_array('assistant',$ret->roles)||in_array('admin',$ret->roles)){
			    return true;
            }else{
		        return false;
            }
		}
		return false;
	}

	public static function getUserLevel($uid){
		$ret = utility_services::call("/user/level/getUserLevel/".$uid);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
	}

	public static function getPreAndNextLevel($pk_level){
		$ret = utility_services::call("/user/level/getPreAndNextLevel/".$pk_level);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
	}

	public static function getNextLevel($pk_level){
		$ret = utility_services::call("/user/level/getNextLevel/".$pk_level);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
	}

	public static function getUserSignByDay($day,$uid){
		$params = new stdclass;
		$params->day = $day;
		$params->uid = $uid;
		$ret = utility_services::call("/user/level/getUserSignByDay",$params);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
	}

	public static function getLastUserSign($uid){
		$ret = utility_services::call("/user/level/getLastUserSign/$uid");
		if(empty($ret->code)){
			return $ret->result;
		}else{
			return false;
		}
	}

	public static function changeUserLevelAndScore($uid,$rule_name){
		$params = new stdclass;
		$params->fk_user = $uid;
		$params->rule_name = $rule_name;
		$ret = utility_services::call("/user/level/changeUserLevelAndScore",$params);
		return $ret;
	}

	public static function getAllUserCount(){
		$ret = utility_services::call("/user/level/getAllUserCount");
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
	}

	public static function getUserStatByUid($uid){
		$ret = utility_services::call("/stat/statuser/getUserStatByUid/$uid");
		if(!empty($ret->data)){
			return $ret->data;
		}else{
			return false;
		}
	}

	public static function getUserRankByDate($start,$end,$page,$length){
		$params = new stdclass;
		$params->start_date = $start;
		$params->end_date = $end;
		$params->page     = $page;
		$params->length   = $length;
		$ret = utility_services::call("/user/level/getUserRankByDate",$params);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
	}

	public static function getUserSortByDate($uid,$start,$end){
		$params = new stdclass;
		$params->start_date = $start;
		$params->end_date   = $end;
		$params->uid        = $uid;
		$ret = utility_services::call("/user/level/getUserSortByDate",$params);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return 0;
		}
	}

	public static function getUserAllSortByUid($uid){
		$params = new stdclass;
		$params->uid        = $uid;
		$ret = utility_services::call("/user/level/getUserAllSortByUid",$params);
		if(empty($ret->code)){
			return $ret->result;
		}else{
			return 0;
		}
	}

	public static function getAllUserRank($page,$length){
		$params = new stdclass;
		$params->page     = $page;
		$params->length   = $length;
		$ret = utility_services::call("/user/level/getAllUserRank",$params);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
	}

	public static function getUserPlanStatCountByUid($uid){
		$ret = utility_services::call("/stat/userplan/getUserPlanStatCountByUid/$uid");
		if(!empty($ret->data)){
			return $ret->data;
		}else{
			return 0;
		}
	}

	public static function getUserPlanStatCountByPid($uid,$pidArr){
		$ret = utility_services::call("/stat/userplan/getUserPlanStatCountByPid/$uid",$pidArr);
		if(!empty($ret->data)){
			return $ret->data;
		}else{
			return 0;
		}
	}

	public static function getOrgInfo($oid)
	{
		$ret = utility_services::call("/user/organization/Get/".$oid);
		if(!empty($ret->data)){
			return $ret->data;
		}else{
			return false;
		}
	}

	public static function listLevelByUserIdsArr($userIdsArr = array()){
		$params = new stdclass;
		$params->userIdArr = new stdclass;
		$params = new stdclass;
		$default = new stdclass;
		$default->result = new stdclass;
		if(is_array($userIdsArr) && (!empty($userIdsArr))){
			foreach($userIdsArr as $k=>$v){
				$default->result->$k = new stdclass;
				$default->result->$k->fk_user = $v;
				$default->result->$k->fk_level = "1";
				$default->result->$k->title = "书生1";
				$default->result->$k->score = "0";
			}
		}else{
			return false;
		}
		$params->userIdArr = $userIdsArr;
		$ret = utility_services::call("/user/score/listByUidArr/",$params);
		//这个就是没有取到值的情况返回默认数组
		if(!empty($ret->code==3002)){
			return $default;
			//如果取到值了如果有这个值就替换掉没有就保留默认值
		}elseif($ret->code==0){
			if(!empty($ret->result)){
				foreach($ret->result as $k=>$v){
					$retTmp[$v->fk_user] = $v;
				}
				foreach($default->result as $k=>$v){
					if(!empty($retTmp[$v->fk_user])){
						$default->result->$k = $retTmp[$v->fk_user];
					}
				}
			}
			return $default;
		}else{
			return false;
		}
	}


	/*
	 * 修改视频封面
	 */
	public static function setCourseImg($params)
	{
		$ret = utility_services::call("/course/video/UpdateImg",$params);
		return $ret;
	}

	public static function checkPassword($password){
		$ret = utility_services::call("/user/info/CheckPassword/".$password);
		return $ret;
	}

	public static function getUserInfoByUidArr($params){
		$ret = utility_services::call("/user/info/getUserInfoByUidArr",$params);
		return $ret;
	}

	public static function searchUser($params){
		$url = "/user/info/searchShow";
		$ret = utility_services::call($url, $params);
		if(!empty($ret->result->data)){
			return $ret->result->data;
		}else{
			return false;
		}
	}

	public static function searchOrgTeacherNameOrMobileInfo($orgId,$params){
		$url = "/user/organization/searchOrgTeacherNameOrMobileInfo/".$orgId;
		$result = utility_services::call($url, $params);
		if(!empty($result->items)){

			return $result->items;
		}else{
			return false;
		}
		return $result;
	}

	public static function getOrgByProvince($cityId,$page='',$length=''){
		$ret = utility_services::call("/user/organization/getOrgByProvince/".$cityId.'/'.$page.'/'.$length);
		return $ret;
	}

    public static function getUserGuideByUid($uid,$gid){
        $ret=utility_services::call("/user/info/getUserGuideByUid/$uid/$gid");
        if(!empty($ret->data)){
            return $ret->data;
        }
        return false;
    }

	//引导 用户ID 引导ID
	public static function getUserGuideByUidAndArrGuideId($uId,$guideId){
		$ret=utility_services::call("/user/info/getUserGuideByUidAndArrGuideId/$uId",$guideId);
		return $ret;
	}

    public static function addUserGuide($params){
        $ret=utility_services::call("/user/info/addUserGuide",$params);
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }

    public static function updateUserGuide($uid,$gid,$params){
        $ret=utility_services::call("/user/info/updateUserGuide/$uid/$gid",$params);
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }

	public static function checkSpecial($uid){
		$special = user_api::getBasicUser($uid);
		$identity = 0;
        if (!empty($special->type) && $special->type & 0x01) {
            $identity = 1; // student
        }
        if (!empty($special->type) && $special->type & 0x02) {
            $identity = 2; // teacher
        }
        if (!empty($special->type) && $special->type & 0x04) {
            $identity = 2; // organization
        }

		return $identity;
	}
	/*
	 *临时用导入数据表
	 */
	public static function addInternationalSomeInfo($params){
        $ret=utility_services::call("/user/organization/addInternationalSomeInfo",$params);
        if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
        return false;
    }
	/*
	 *手机国际编号
	 */
	public static function getInternationalCodeByInfo($params){
        $ret=utility_services::call("/user/organization/getInternationalCodeByInfo",$params);
        return $ret;
    }
	public static function likeMobile($mobile){
        $ret = utility_services::call("/user/info/likeMobile/".$mobile);
		if(empty($ret->result->items)){
			return array();
		}
        return $ret->result->items;
    }
	public static function likeUserName($name){
        $ret = utility_services::call("/user/info/likeName/".$name);
		if(empty($ret->result->items)){
			return array();
		}
        return $ret->result->items;
    }
	public static function GetParternerSchool($school_id, $source="xplatform"){
		$params->source=$source;
        $ret = utility_services::call("/user.parterner.getschool/".$school_id,$params);
		if(empty($ret->data)){
			return false;
		}
        return $ret->data;
    }

	public static function getUserOrgCourse($data, $page=0, $length=0){
		$ret = utility_services::call("course/courseuser/getUserOrgCourse/".$page."/".$length, $data);
		if(!empty($ret->code)){
			return array();
		}
		return $ret->result;
	}

	public static function getPlanByCourseId($courseId){
		$params = new stdclass;
		$params->courseId = $courseId;
		$ret = utility_services::call("/course/plan/GetPlanByCourseId", $params);
		if(!empty($ret->code)){
			return array();
		}
		return $ret->result;
	}

        /* 获取机构信息 */
	public static function ListUserProfileByOids($orgIdArr){
                $params = new stdclass();
                $params->oids = $orgIdArr;
		$ret = utility_services::call("/user/organizationUser/listUserProfileByOids/", $params);

		if(empty($ret->items)){ return array(); }

                $itemsArr = array();
                foreach($ret->items as $key => $val){
                    $itemsArr[$val->pk_org] = $val;
                }

		return $itemsArr;
	}

	//添加分组
	public static function addGroup($params){
		   $ret = utility_services::call("/course/group/AddGroup/", $params);
		   return $ret;
	}

	public static function upGroup($params){
		   $ret = utility_services::call("/course/group/upGroup/", $params);
		   return $ret;
	}

	public static function delGroup($groupid,$classid){
		   $ret = utility_services::call("/course/group/delGroup/{$groupid}/{$classid}");
		   return $ret;
	}

	public static function groupList($classid){
		   $ret = utility_services::call("/course/group/groupList/{$classid}");
		   return $ret;
	}
	public static function getGroupCheckStatus($classid){
		   $ret = utility_services::call("/course/group/GetGroupCheckStatus/{$classid}");
		   return $ret;
	}
	public static function setGroupPrivilege($params){
		   $ret = utility_services::call("/course/group/setGroupPrivilege/",$params);
		   return $ret;
	}
	
	//设置学员分组
	public static function batchHandleGroupUser($params){
		   $ret = utility_services::call("/course/group/batchHandleGroupUser/",$params);
		   return $ret;
	}
	public static function getTeacherIsGroupById($params){
		   $ret = utility_services::call("/course/group/getTeacherIsGroupById/,",$params);
		   if($ret->code==0&&!empty($ret->result)){
               foreach($ret->result as $val){
                    $data[$val->group_teacher_id] = $val;
               }
			   return $data;
		   }
		   return false;
	}
	public static function userList($params){
		   $ret = utility_services::call("/course/group/userList/",$params);
		   return $ret;
	}

	//管理员list
	public static function adminList($orgid){
		   $ret = utility_services::call("/course/group/AdminList/{$orgid}");
		   return $ret;
	}

	public static function checkIsGroupuser($params){
		   $ret = utility_services::call("/course/group/checkIsGroupuser/",$params);
		   return $ret;
	}

	public static function getGroupInfo($groupid){
		   $ret = utility_services::call("/course/group/getGroupInfo/{$groupid}");
		   return $ret;
	}

	public static function checkTeacherPrivilege($classid,$teacherid){
		   $ret = utility_services::call("/course/group/checkTeacher/{$classid}/{$teacherid}");
		   return $ret;
	}
	 public static function getOrgByid($org_id){
		$url = '/user/organization/getOrgByid/'.$org_id;
		return utility_services::call($url);
	}
	//搜索该机构下的老师(先从中间层获取,如果获取不到从数据库获取)
	public static function getorgTeacherData($orgId, $page, $length, $keywords=''){
		$data = array();

		if(empty($orgId)) return $data;
		$params['q']['org_id'] = $orgId;
		if(!empty($keywords)) $params['q']['real_name'] = $keywords;
		$params['f']  = ['teacher_id','real_name','thumb_med'];
		$params['pl'] = $length;
		$params['p']  = $page;
		$seekTeacher  = seek_api::seekTeacher($params);
		$data = [
			'page'      => $seekTeacher->page,
			'total'     => $seekTeacher->total,
			'pageTotal' => $seekTeacher->pagelength
		];

		//查询数据库(只支持用户名搜索)
		if(!empty($keywords) && empty($seekTeacher->data)){
			$condition['keyword'] = $keywords;
			$res = user_api::searchOrgTeacherNameOrMobileInfo($orgId, $condition);
		}else{
			$res = !empty($seekTeacher->data) ? $seekTeacher->data : array();
		}

		if(!empty($res)){
			foreach($res as $val){
				$data['teachers'][] = [
					'teacherId' => !empty($val->teacher_id) ? $val->teacher_id : $val->user_id,
					'realName'  => !empty($val->real_name) ? $val->real_name : '',
					'thumbMed'  => interface_func::imgUrl($val->thumb_med)
				];
			}
		}

		return $data;
	}

	//检查老师是否能创建课程
	public static function checkTeacherCreateCourse($courseId, $teacherId){
		if(empty($courseId) || empty($teacherId)){
			return false;
		}
		//是否开启老师建课
        $openTeacherCourse = 1;//(暂时)

		$teacherCourse = course_api::getCourseTeacher(array('courseId'=>$courseId,'teacherId'=>$teacherId));
		if(!empty($openTeacherCourse) && !empty($teacherCourse)){
			return true;
		}
		return false;
	}

	//根据uid获取教师预览视频
	public static function getPreviewVideoByUid($uid){		
		if (empty($uid)) {
			return false;
		}
		$url = "/user/info/getPreviewVideoByUid/$uid";				
		$ret = utility_services::call($url);		
		if(!empty($ret)){
			return $ret;
		}
		return false;
	}

	//通过用户ID 获取学生礼物
	public static function getStudentGiftByUserId($uid){
		$url = "/user/gift/getGiftListByUserId/$uid";
		$ret = utility_services::call($url);
		return $ret;
	}

	//删除学生
	public static function delClassStudent($courseId,$classId,$studentId){
		$ret = utility_services::call("/course/student/delClassStudent/$courseId/$classId/$studentId");
		return $ret;
	}

	//通过用户id 返回 is_student is_teacher is_organization
	public static function getUserLeavelById($uid){
		if(empty($uid)) return interface_func::setMsg();
		$uid = intval($uid);
		if(!is_int($uid)) return interface_func::setMsg();
		$ret = utility_services::call("/user/info/getuserleavel/$uid");
		return $ret;
	}

	public static function addUserAddress($data){
		$url = "/user/info/addUserAddress/";
		$ret = utility_services::call($url,$data);
		return $ret;
	}

	public static function getUserAddress($uid){
		$url = "/user/info/getUserAddress/$uid";
		$ret = utility_services::call($url);
		return $ret;
	}

	public static function getUserAddressByAddressId($addressId){
		$url = "/user/info/getUserAddressByAddressId/$addressId";
		$ret = utility_services::call($url);
		return $ret;
	}

	public static function updateUserAddress($addressId,$data){
		$url = "/user/info/updateUserAddress/$addressId";
		$ret = utility_services::call($url,$data);
		return $ret;
    }	
    
    public static function getUserAddressByUserIdArr($userIdArr){
        if(empty($userIdArr)) return false;

        $params['userIdArr'] = $userIdArr;
		$ret = utility_services::call("/user/info/getUserAddressByUserIdArr", $params);
        if(empty($ret->code)) return $ret->result;

		return false;
	}

	public static function getRecommendTeacherByAreaId($orgId,$areaId){
		$params = new stdclass;
		$params->orgId = $orgId;
		$params->areaId = $areaId;
		$ret = utility_services::call('/user/info/getRecommendTeacherByAreaId',$params);
		return $ret;
	}

	/*
	 * 获取用户积分经验值
	 * */
	public static function getUserScoreAndPoint($uId){
		if(empty($uId)) return false;
		$ret = utility_services::call("/user/level/getUserScoreAndPoint/$uId");
		return $ret;
	}

	/*
	 * 积分相减
	 * */
	public static function scoreSubtractByUIdAndPoint($uId,$point){
		if( empty($uId) || empty($point) ) return false;
		$ret = utility_services::call("/user/score/scoreSubtractByUIdAndPoint/$uId/$point");
		return $ret;
	}

	// 红名使用
	public static function redNameUseByUIdAndStopTime($uId,$stopTime,$status){
		if( empty($uId) || empty($stopTime) ) return false;
		$ret = utility_services::call("/user/gift/redNameUseByUIdAndStopTime/$uId/$stopTime/$status");
		return $ret;
	}

	//红名查询
	public static function getRedCardByUId($uId){
		if( empty($uId) ) return false;
		$ret = utility_services::call("/user/gift/getRedCardByUId/$uId");
		return $ret;
	}
}
