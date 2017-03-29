<?php
class weixin_user extends STpl{

	static $domain;
	public function __construct($inPath){
		if(!weixin_api::is_weixin()){
			die("请使用微信访问本网址!");
		}
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		self::$domain = "www.".$domain_conf->domain;
	}
	//这是微信App和服务号登录及回调
	public function pageLogin($inPath){
		$scope="snsapi_base";
		if(!empty($inPath[3]) && $inPath[3]=="new"){
			$scope="snsapi_userinfo";
		}
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize";	
		$sc="http:";
		if(utility_net::isHTTPS()){
			$sc="https:";
		}
		$domain = user_organization::domain();
		$params = array(	
			"appid"			=>"wx02043514c23e8211",
			"redirect_uri"	=>"{$sc}//www.yunke.com/weixin.user.loginCallback/$scope?domain=$domain",
			"response_type"	=>"code",
			"scope"			=>$scope,
			"state"			=>session_id(),
		);
        if(!empty($_SERVER['HTTP_REFERER'])){
            utility_session::get()['login_url'] = $_SERVER['HTTP_REFERER'];
        }
		$code_url=$url."?".http_build_query ($params);
		$this->redirect($code_url);
	}
	public function pageLoginCallback($inPath){
		if(empty($_REQUEST['code'])){
			die("ERROR 1");
		}
		$sc="http:";
		if(utility_net::isHTTPS()){
			$sc="https:";
		}
		$domain = empty($_REQUEST['domain'])?self::$domain:$_REQUEST['domain'];
		if($domain==self::$domain){
			$parter_url = "{$sc}//{$domain}/index.main.login.parterner";
		}else{
			$parter_url = "{$sc}//{$domain}/user.main.login.parterner";
		}
		$scope="snsapi_base";
		if(!empty($inPath[3]) && $inPath=="snsapi_userinfo"){
			$scope="snsapi_userinfo";
		}
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token";	
		$config = weixin_api::getConfig();
		$params = array(	
			"appid"			=>"wx02043514c23e8211",
			"secret"		=>$config->appsecret,//"wx02043514c23e8211",
			"code"			=>$_REQUEST['code'],
			"grant_type"	=>"authorization_code",
		);
		$code_url=$url."?".http_build_query ($params);

		//print_r($_REQUEST);
		//获取access_token&openid
		$ret_auth = SJson::decode(SHttp::get( $code_url, $params=array(), $cookies=array(), $returnHeader=false, $timeout=10));
		if(empty($ret_auth->openid) || empty($ret_auth->access_token)){
			die("ERROR 2");
		}
		//print_r($ret_auth);
		//通过公众号获取union_id
		$code_url ="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$config->access_token."&openid=".$ret_auth->openid."&lang=zh_CN";
		$ret_info = SJson::decode(SHttp::get( $code_url, $params=array(), $cookies=array(), $returnHeader=false, $timeout=10));

		$nickName = $thumbUrl = '';
		if (!empty($ret_info->nickname)) $nickName = $ret_info->nickname;
		if (!empty($ret_info->headimgurl)) $thumbUrl = $ret_info->headimgurl;
		if (!empty($ret_info->thumb_url)) $thumbUrl = $ret_info->thumb_url;

		if(empty($ret_info->unionid)){
				//需要重定向啊
				//die("ERROR 5");
				return $this->redirect("{$sc}//{$domain}/weixin.user.login.new");
		}
		//print_r($ret_info);
		//从我们库里获取用户信息
		$parter_info = user_api::getParterner(user_const::SOURCE_WEIXIN,$ret_info->unionid);

		//已经登录并绑定了用户
		if(!empty($parter_info->parterner_id)){
			//之前绑定过的用户，如果取消订阅，这里不能获取到昵称，复用已经保存的昵称
			if(empty($nickName)){$nickName = $parter_info->nickname;}
			if(!empty($parter_info->uid)){
				/**
				 * 如果信息有变化，更新parter_info，因为这里设计到微信App里JS调起微信支付，所以需要更新
				 * 不然有可能这个用户第一次通过网站扫码登录后，第一次建立的openid和信里的openid不一起，引起无法支付
				 * http://jira.gn100.com/browse/WEB-1942
				 */
				if($parter_info->parterner_uinfo != SJson::encode($ret_info)){
					$ret_set = user_api::setParterner($parter_info->uid,
						$nickName,
						user_const::SOURCE_WEIXIN,
						$ret_info->unionid,
						SJson::encode($ret_info),
						$ret_auth->access_token,
						$thumbUrl
					);
					if(empty($ret_set->parterner_id)){
						error_log("ERROR1!!!!"."\n",3,"/tmp/wx.log");
						error_log(date("Y-m-d H:i:s")."\t".__LINE__."\n",3,"/tmp/wx.log");
						error_log(var_export($ret_info,true)."\n",3,"/tmp/wx.log");
						error_log(var_export($ret_set,true)."\n",3,"/tmp/wx.log");
						error_log(var_export($ret_auth,true)."\n",3,"/tmp/wx.log");
					}
				}
				if(user_api::loginByUid($parter_info->uid,true)){
					utility_session::get()['parterner_uid'] = $parter_info->parterner_uid;
					if (!empty(utility_session::get()['login_source'])&&utility_session::get()['login_source'] === 'reg') {
						$user = user_api::loginedUser();
	                    $result = user_api::getUserAddress($user['uid']);
	                    $document = course_api::getCourseOne(utility_session::get()['login_cid']);
	                    if (empty($result->result->items) && !empty($document->document)) {
	                        return $this->redirect("/layer/main/addUserAddressLayer/w/");
	                    }
						if (utility_session::get()['login_cid'] && utility_session::get()['login_clid']) {
							$regInfo = $this->_addRegistration(utility_session::get()['login_cid'], utility_session::get()['login_clid'],utility_session::get()['login_resellOrgId']);
							if($regInfo===true){
								if($regInfo){
									$user     = user_api::loginedUser();
									$org             = user_organization::subdomain();
									$orgInfo = user_organization::getOrgByOwner($org->orgOwner);
									$info = array(
										"userId"=>$user['uid'],
										"courseId"=>utility_session::get()['login_cid'],
										"orgId"=>!empty($orgInfo->oid)?$orgInfo->oid:0,
									);
									user_organizationStudent_api::addOrganizationStudent($info);
								}
							}
							if ($regInfo['code'] === -2){
								return $this->redirect("{$sc}//{$domain}/order.main.buy/course/".utility_session::get()['login_cid']."/".utility_session::get()['login_clid']."/".utility_session::get()['login_resellOrgId']);
							} else {
								if(!empty(utility_session::get()['login_url'])){
									$url = utility_session::get()['login_url'];
									return $this->redirect($url);
								}
							}
							utility_session::get()['login_cid']='';
							utility_session::get()['login_clid']='';
							utility_session::get()['login_source']='';
							return $this->redirect("/");
						}
					}
					if(!empty(utility_session::get()['login_url'])){
						$url = utility_session::get()['login_url'];
						return $this->redirect($url);
					}
					$this->redirect("{$sc}//{$domain}/");
				}else{
					$this->redirect("{$sc}//{$domain}/user.main.login");
				}
			}else{
				#没有绑定手机
				user_api::parternerLogin($parter_info->parterner_id,
					$nickName,
					$thumbUrl
				);
				$this->redirect($parter_url);
			}
		}else{
			//新用户，生
			if(empty($ret_info->nickname)){
				$code_url ="https://api.weixin.qq.com/sns/userinfo?access_token=".$ret_auth->access_token."&openid=".$ret_auth->openid."&lang=zh_CN";
				$ret_info = SJson::decode(SHttp::get( $code_url, $params=array(), $cookies=array(), $returnHeader=false, $timeout=10));
				if(empty($ret_info->nickname) || empty($ret_info->unionid)){
					//die("ERROR 3");
					return $this->redirect("{$sc}//{$domain}/weixin.user.login.new");
				}
			}
			if(empty($ret_info->nickname)){
				//需要重定向啊
				//die("ERROR 5");
				return $this->redirect("{$sc}//{$domain}/weixin.user.login.new");
			}
			//新来的用户,注册parterner
			//或者已经登录的用户，但是parter.uid为0的时候，重新绑定
			{
				if (!empty($ret_info->nickname)) $nickName = $ret_info->nickname;
				if (!empty($ret_info->headimgurl)) $thumbUrl = $ret_info->headimgurl;
				if (!empty($ret_info->thumb_url)) $thumbUrl = $ret_info->thumb_url;

				$ret_set = user_api::setParterner(user_api::getLoginUid(),
					$nickName,
					user_const::SOURCE_WEIXIN,
					$ret_info->unionid,
					SJson::encode($ret_info),
					$ret_auth->access_token,
					$thumbUrl
				);
				if(empty($ret_set->parterner_id)){
					error_log("ERROR2!!!!"."\n",3,"/tmp/wx.log");
					error_log(date("Y-m-d H:i:s")."\t".__LINE__."\n",3,"/tmp/wx.log");
					error_log(var_export($ret_info,true)."\n",3,"/tmp/wx.log");
					error_log(var_export($ret_set,true)."\n",3,"/tmp/wx.log");
					error_log(var_export($ret_auth,true)."\n",3,"/tmp/wx.log");
					die("接口错误");
				}
				user_api::parternerLogin($ret_set->parterner_id,
					$nickName,
					$thumbUrl
				);
			}
			$this->redirect($parter_url);
		}
	}
	//微信端自动报名
	public function pageRegAjax($inPath){
		$user = user_api::loginedUser();
	    $result = user_api::getUserAddress($user['uid']);	    
		$document = course_api::getCourseOne(utility_session::get()['login_cid']);
	    if (empty($result->result->items) && !empty($document->document)) {
				$url = '/layer/main/addUserAddressLayer/w/';
				return json_encode(array('code'=>1,'url'=>$url));
	            //return $this->redirect("/layer/main/addUserAddressLayer/w/");
	    }
		if (utility_session::get()['login_cid'] && utility_session::get()['login_clid']) {
			$regInfo = $this->_addRegistration(utility_session::get()['login_cid'], utility_session::get()['login_clid'],utility_session::get()['login_resellOrgId']);
			if($regInfo===true){
				if($regInfo){
					$user     = user_api::loginedUser();
					$org             = user_organization::subdomain();
					$orgInfo = user_organization::getOrgByOwner($org->orgOwner);
					$info = array(
						"userId"=>$user['uid'],
						"courseId"=>utility_session::get()['login_cid'],
						"orgId"=>!empty($orgInfo->oid)?$orgInfo->oid:0,
					);
					user_organizationStudent_api::addOrganizationStudent($info);
				}
			}
			if ($regInfo['code'] === -2){
				$url = "{$sc}//{$domain}/order.main.buy/course/".utility_session::get()['login_cid']."/".utility_session::get()['login_clid']."/".utility_session::get()['login_resellOrgId'];
				return json_encode(array('code'=>1,'url'=>$url));
				//return $this->redirect("{$sc}//{$domain}/order.main.buy/course/".utility_session::get()['login_cid']."/".utility_session::get()['login_clid']."/".utility_session::get()['login_resellOrgId']);
			} else {
				if(!empty(utility_session::get()['login_url'])){
					$url = utility_session::get()['login_url'];
					return json_encode(array('code'=>1,'url'=>$url));
					//return $this->redirect($url);
				}
			}
			// utility_session::get()['login_cid']='';
			// utility_session::get()['login_clid']='';
			// utility_session::get()['login_source']='';
			return json_encode(array('code'=>1,'url'=>'/'));
			//return $this->redirect("/");
		}					                                    
	}

	private function _addRegistration($cid, $classId,$resellOrgId=0)
	{
		$user     = user_api::loginedUser();
		if (empty($user)) {
			return false;
		}

		$courseInfo = course_api::getCourseone($cid);
		if (empty($courseInfo)) {
			return false;
		}

		$uid = $user['uid'];
		$isMember = 0;
		$setIdArr  = course_api::getCourseOpenMemberSetIdArr($cid);
		$memberRet = org_member_api::checkIsMemberOrExpire($uid, $setIdArr, $cid);
		if($memberRet['isMember'] == 1 && $memberRet['isExpire'] == 0){
			$isMember = 1;
		}
		if(!empty($memberRet['regId']) && $memberRet['regId'] != $classId ){
			$classId = $memberRet['regId'];
		}
		$resell = new stdClass();
		if(!empty($resellOrgId)){
			$resell = course_resell_api::getResellCourselist(1,1,array("cid"=>$cid,"uid"=>$resellOrgId,"status"=>'n','sort'=>''));
			if(!empty($resell->data->items)) {
				$resell = $resell->data->items[0];
				if($resell->ver==$resell->promote_ver&&$resell->status==1&&$resell->status==$resell->promote_status&&$resell->price_resell>0){
					$courseInfo->fee_type = 1;
					$courseInfo->fee->price = $resell->price_resell;
				}else{
					$courseInfo->fee_type = 0;
					$courseInfo->fee->price = 0;
				}
			}
		}
		if($isMember == 0 ){
			if (($courseInfo->fee_type == 1 && !empty($courseInfo->fee->price)) || (!empty($resell->ver)&&$resell->ver==$resell->promote_ver&&$resell->status==1&&$resell->status==$resell->promote_status&&$resell->price_resell>0)) {
				$ret['code'] = -2;
				$ret['classId'] = $classId;
				return $ret;
			}
		}


		$ClassData = course_api::getclass($classId);
		if (empty($ClassData)) {
			return false;
		}
		if(empty($memberRet['regId'])){
			if ($ClassData->user_total >= $ClassData->max_user) {
				$ret['code'] = -6;
				$ret['classId'] = $classId;
				return $ret;
			}
		}

		$data = array(
			"class_id"   => $classId,
			"course_id"  => $cid,
			"uid"        => $user['uid'],
			"user_owner" => $courseInfo->user_id,
			"status"     => 1,
		);

		return course_api::addregistration($data);
	}
}
