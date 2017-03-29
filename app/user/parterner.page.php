<?php
class user_parterner extends STpl{
	private $domain;
	public function __construct(){
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
		$this->assign('domain', $this->domain);
        $org=user_organization::subdomain();
        if(!empty($org)){
            $this->orgOwner = $org->userId; //机构所有者id 以后会根据域名而列取
        }else{
            $this->orgOwner=0;
        }
		$this->orgInfo = user_organization::getOrgByOwner($this->orgOwner);
	}	
	/**
	 * http://jira.gn100.com/browse/WEB-6218
	 * 第3方登录统一接口,参数 @param 是从$_REQUEST里获取
	 * @param $userid 必须 第3方用户唯一标识，比如微信的open_id之类
	 * @param $appid  必须
	 * @param $apphash	必须
	 * @param $timestamp 必须
	 *
	 * @param mobile 可选，如果userid是手机号，这个变量自动复制
	 * @param password 可选，密码，如果mobile不为空，并且密码匹配，可以直接登录
	 * @param name 可选，默认 "佚名"
	 * @param url 可选 ，默认 "/"

	 * @return int ,如果失败，参数错误值附在网址后面
	 * 错误代码
	 * 说明
	 * -1	userid为空
	 * -3	appid为空或者不存在
	 * -4	apphash计算错误
	 * -5	timestamp错误，或者接口超时
	 * -6	其它错误
	 * -7	绑定关系失败
	 * -8	登录失败
	 */
	public function pageSSO($inPath){
		$url = "/";
		if(!empty($_REQUEST['url'])){
			$url = urldecode($_REQUEST['url']);
		}
		if(empty($_REQUEST['userid'])){ //return $this->redirect("$url#code=-1"); 
		}
		//appid不存在
		if(empty($_REQUEST['appid'])){
			return $this->redirect("$url#code=-3"); 
		}
		$app = user_parterner_api::getApp($_REQUEST['appid']);
		if(empty($app)){
			return $this->redirect("$url#code=-3"); 
		}
		//apphash校验失败
		if(empty($_REQUEST['apphash']) || user_parterner_api::checkHash($_REQUEST['appid'], $_REQUEST['apphash'], $_REQUEST)!=true){
			return $this->redirect("$url#code=-4"); 
		}
		//timestamp校验失败
		if(empty($_REQUEST['timestamp']) || time() > $_REQUEST['timestamp']+60*10 || time() < $_REQUEST['timestamp']-60*20){
			return $this->redirect("$url#code=-5"); 
		}

		$appid = $_REQUEST['appid'];
		$open_id = $_REQUEST['userid'];
		$password=!empty($_REQUEST['password'])?urldecode($_REQUEST['password']):"";
		$name = !empty($_REQUEST['name'])?urldecode($_REQUEST['name']):'佚名';
		$mobile = "";
		if(utility_valid::mobile($open_id)){
			$mobile = $open_id;
		}
		if(!empty($_REQUEST['mobile']) && utility_valid::mobile($_REQUEST['mobile'])){
			$mobile = $_REQUEST['mobile'];
		}
		if(!empty($mobile) && !empty($password)){//手机号和密码直接登录
			if(user_api::login($mobile,$password,true)){
				return $this->redirect("$url"); 
			}
		}
		$login_url = utility_session::get()['login_url'];
		user_api::logout();
		utility_session::get()['login_url'] = $login_url;
		$parter_info = user_api::getParterner($appid,$open_id);

		//新来的用户,注册parterner
		//或者已经登录的用户，但是parter.uid为0的时候，重新绑定
		$parterner_id = 0;
		if(!empty($parter_info->parterner_id) && !empty($parter_info->uid)){
			$parterner_id = $parter_info->parterner_id;
			//这里应该直接登录
			user_api::parternerLogin($ret_set->parterner_id,
				$name,
				""
			);
			if(user_api::loginByUid($parter_info->uid,true)){
				utility_session::get()['parterner_uid'] = $parter_info->parterner_uid;
				return $this->redirect("$url"); 
			}
		}else{
			//引导进入绑定页
			$ret_set = user_api::setParterner(user_api::getLoginUid(),
				$name,
				$appid,
				$open_id,
				SJson::encode($_REQUEST),
				"",
				""
			);
			if(empty($ret_set->parterner_id)){
				die("接口错误");
			}
			$parterner_id = $ret_set->parterner_id;
			user_api::parternerLogin($ret_set->parterner_id,
				$name,
				""
			);
		}
		$host=$_SERVER['HTTP_HOST'];
		if(!empty(utility_session::get()['url'])){
			$host = utility_session::get()['url'];
			utility_session::get()['url']="";
		}
		if($host=="www.yunke.com"){
			$jump= "//{$host}/index.main.login.parterner?mobile=$mobile&name=".urlencode($name);
		}else{
			$jump= "//{$host}/user.main.login.parterner?mobile=$mobile&name=".urlencode($name);
		}
		utility_session::get()['login_url'] = $url;
		if(!empty($app->noMobile)){//不需要绑定手机号
			$user_id = user_api::registerByParternerId($name, $parterner_id);
			if(!empty($user_id) && user_api::loginByUid($user_id,true)){
				utility_session::get()['parterner_uid'] = $parter_info->parterner_uid;
			}
			return $this->redirect("$url"); 
		}else{
			$this->assign('url',$jump);
			return $this->render("user/parterner.ok.html");
		}
	}
	/**
	 * http://jira.gn100.com/browse/WEB-6290
	 * 福建教育平台接入
	 */
	public function pageFuJian($inPath){
		if(empty($_REQUEST['ticket'])){
			return $this->pageSSO($inPath);
		}
		$ct = SHttp::get("http://www.fjedu.cn:20014/aamif/ticketValidate?ticket={$_REQUEST['ticket']}");
		preg_match("/\<cas:user\>(.*?)\<\/cas:user\>/",$ct, $_m);
		if(empty($_m[1])){
			return $this->pageSSO($inPath);
		}
		$usessionid= $_m[1];
		$params = array();
		//这是在福建教育平台的APP信息
		$app_key = 'aa9d2a36e34c0cc5fc876c4ed92a64e6';
		$params['appid']='AP060000012778';
		$params['timestamp']=time();
		$params['keyinfo']=strtoupper(hash_hmac('sha1',$params['appid'].$app_key.$params['timestamp'],$app_key));
		$params['usessionid']=$usessionid;
		$ct = SJson::decode(SHttp::post("http://www.fjedu.cn:20001/apigateway/getaccesstoken",SJson::encode($params),array(),false,5));
		if(empty($ct->tokenInfo->token)){
			return $this->pageSSO($inPath);
		}
		$token = $ct->tokenInfo->token;
		$ct = SJson::decode(SHttp::get("http://www.fjedu.cn:20001/aam/rest/user/getuserinfo/{$usessionid}?token={$token}"));
		if(empty($ct->userinfo->personid)){
			return $this->pageSSO($inPath);
		}
		$url="/";
		if(!in_array($_SERVER['HTTP_HOST'] ,array("fjzj.yunke.com","fjjinpin.yunke.com"))){
			if(!empty($ct->userinfo->orgaid)){
				$school_info = user_api::GetParternerSchool($ct->userinfo->orgaid,"fjedu");
				if($school_info!==false){
					$url = (utility_net::isHTTPS()?"https":"http")."://".$school_info->subdomain;
				}
			}
		}
		//构造云课平台的APP信息，调用统一登陆接口
		$appid = 10101;
		$appkey = "72c8c198e41e5edc2c334abaae0f494d";
		$timestamp=time();
		$userid= $ct->userinfo->personid;
		$name= !empty($ct->userinfo->name) ? $ct->userinfo->name :"";
		$mobile= !empty($ct->userinfo->mobnum) ? $ct->userinfo->mobnum :"";
		$params = array(
			"appid"=>$appid,
			"userid"=>$userid,
			"timestamp"=>$timestamp,
			"url"=>$url,
			"name"=>urlencode($name),
			"password"=>"",
			"mobile"=>$mobile,
		);
		$apphash = user_parterner_api::appHash($params,$appkey);
		$params['apphash'] = $apphash;

		return $this->redirect("/user.parterner.sso?".http_build_query($params));
	}
	/**
	 * http://jira.gn100.com/browse/WEB-5732
	 * 湖南和教育对接
     * 参数名称	类型	是否必须	描述说明
     * userMobile	String	是	用户手机号码
     * name	String	是	用户名
     * userType	int	是	用户类型0：家长角色1:学校管理员2：班主任3：任课老师4：其他
     * schoolNo	String	是	学校编码
     * channelNo	String	是	渠道编码(由产品方提供)
     * timestamp	int	是	当前跳转过来的实间戳
     * hash	String	是	对上面的参数进行加密，可以是非对称加密，防止伪造。
	 * 加密算法:使用md5进行加密。userMobile=xx_name=xx_userType=xx_schoolNo=xx_channelNo=xx_timestamp=xx_key。密钥key:hexiaoyuan
     **/
	public function pageHunanHejiaoyu($inPath){
		//1.鉴权
		$userMobile = isset($_REQUEST['userMobile'])?$_REQUEST['userMobile']:"";
		$name		= isset($_REQUEST['name'])?$_REQUEST['name']:"";
		$userType	= isset($_REQUEST['userType'])?$_REQUEST['userType']:"";
		$schoolNo	= isset($_REQUEST['schoolNo'])?$_REQUEST['schoolNo']:"";
		$channelNo	= isset($_REQUEST['channelNo'])?$_REQUEST['channelNo']:"";
		$timestamp	= isset($_REQUEST['timestamp'])?$_REQUEST['timestamp']:"";
		$hash		= isset($_REQUEST['hash'])?$_REQUEST['hash']:"";
		$key 		= "hexiaoyuan";
		if(empty($userMobile) || empty($name) || empty($hash)){
			return $this->redirect("/site.main.login?error=-101");
		}
		if($hash != md5("userMobile={$userMobile}_name={$name}_userType={$userType}_schoolNo={$schoolNo}_channelNo={$channelNo}_timestamp={$timestamp}_{$key}")){
			return $this->redirect("/site.main.login?error=-102");
		}
		//2.参数转换
		//构造云课平台的APP信息，调用统一登陆接口
		$appid = 10002;
		$appkey = "ace2fce31f25c6293ae54b5e7599b1a0";
		$timestamp=time();
		$userid=$userMobile;
		$url="/";
		$params = array(
			"appid"=>$appid,
			"userid"=>$userid,
			"timestamp"=>$timestamp,
			"url"=>$url,
			"name"=>urlencode($name),
			"password"=>"",
			"mobile"=>$userid,
		);
		$apphash = user_parterner_api::appHash($params,$appkey);
		$params['apphash'] = $apphash;

		return $this->redirect("/user.parterner.sso?".http_build_query($params));

	}
	/**
	 * 成长通第3方登录
	 * @depracated
	 */
	public function pageCzTone($inPath){
		return $this->parternerLogin(user_const::SOURCE_CZTONE, "www.gn100.com/cztone");
	}
	/**
	 * WEB-209 高招帮第3方登录
	 * @deprecated 
	 */
	public function pageGaoZhaoBang($inPath){
		return $this->parternerLogin(user_const::SOURCE_GAOZHAOBANG, "www.gn100.com/gaozhaobang");
	}
	/**
	 * WEB-209 智慧校第3方登录
	 * @deprecated 
	 */
	public function pageZhihuiXiao($inPath){
		return $this->parternerLogin(user_const::SOURCE_ZHIHUIXIAO, "www.gn100.com/zhihuixiao");
	}
	/**
	 * WEB-2924 
	 * @deprecated 用xplatfrom接口了
	public function pageYunxiaoyuan($inPath){
		return $this->parternerLogin(user_const::SOURCE_YUNXIAOYUAN, "www.gn100.com/yunxiaoyuan");
	}
	 */
	/**
	 * 长征教育
	 * @deprecated 
	 */
	public function pageChangZheng($inPath){
		return $this->parternerLogin(user_const::SOURCE_CHANGZHENG, "www.gn100.com/changzhengedu");
	}
	/**
	 * parterner.bind.html 的时候使用
	 */
	public function pageLoginAjax($inPath){
		$result=new stdclass;
		if(empty($_REQUEST['mobile'])){
			$result->error="手机号码不能为空";
			$result->field="mobile";
			return $result;
		}
		if(utility_valid::mobile($_REQUEST['mobile'])==false){
			$result->error="手机号码格式不正确";
			$result->field="mobile";
			return $result;
		}
		//图片验证-TODO
		if(SCaptcha::check(strtolower(trim(str_replace(" ","",$_REQUEST['verify_code_img']))))===false){
			$result->error="图片验证码错误";
			$result->field="verify_code_img";
			return $result;
		}
        SCaptcha::del(strtolower($_REQUEST['verify_code_img']));
		if(empty($_REQUEST['verify_code'])){
			$result->error="手机动态码不能为空";
			$result->field="verify_code";
			return $result;
		}
		if(verify_api::verifyMobile($_REQUEST['mobile'],$_REQUEST['verify_code'])==false){
			$result->error="手机动态码错误";
			$result->field="verify_code";
			return $result;
		}
		//获取parter_info
		$parter_login_info = user_api::getParternerLogin();
		if(empty($parter_login_info['id'])){
			$result->error="第3方登录错误";
			return $result;
		}
		$parter_info= user_api::getParternerById($parter_login_info['id']);
		if(empty($parter_info)){
			$result->error="第3方登录错误";
			return $result;
		}
		//获取uid
		$uid = user_api::isRegister($_REQUEST['mobile']);
		$password 	= substr(trim($_REQUEST['mobile']),5,6);
		$subname 	= !empty($this->orgInfo->subname) ? $this->orgInfo->subname : '';
		//QQ与手机绑定时,未绑定时,注册,并发送手机短信,密码为手机号后6位
		if(empty($uid)){
			$uid = user_api::registerByMobile($parter_info->nickname,$_REQUEST['mobile'],$password,$this->orgOwner);
			if($uid){
				verify_api::sendUserOfMobileAndPasswordSMS(trim($_REQUEST['mobile']),$password,$subname);
			}
		}
		if(empty($uid)){
			$result->error="绑定失败-2";
		}
		//绑定手机号
		$ret = user_api::bindParterner($parter_login_info['id'],$uid);
		if($ret){
			user_api::loginByUid($uid);
		}else{
			$result->error="失败，请重试";
		}
		return $result;
	}
	public function pageXplatformJump($inPath){
		if(empty($_REQUEST['url'])){
			return $this->redirect("/");
		}
		$url = $_REQUEST['url'];
		$user =  user_api::loginedUser();
		if(!empty($user['xplatform_accountId']) && !empty($user['xplatform_refreshToken'])){
			$url.= empty(parse_url($url,PHP_URL_QUERY))?"?":"&";
			$url.="accountId={$user['xplatform_accountId']}&refreshToken=".urlencode($user['xplatform_refreshToken']);
		}
		return $this->redirect($url);
	}
	/**
	 * http://jira.gn100.com/browse/WEB-5248
	 * X平台登录接口
	 * 参数名					类型	必须	默认值	说明
	 * onceToken				string	是				X平台生成的
	 * accountId				int		是				X平台的accountid
	 * url						string	否		/		要跳转的连接，默认是首页
	 * yunxiaoyuanSchoolId		int		否				云校园的内部学校ID，这个ID在云课这边会做和云课学校的映射关系
	 * yunxiaoyuanSchoolName	string	否				学校名
	 * provinceName				string	否				学生所在省份名
	 * cityName					string	否				学生所在城市名
	 **/
	public function pageXplatform($inPath){
		$url="/";
		if(!empty($_REQUEST['url'])){
			$url = $_REQUEST['url'];
		}else{
			if(!empty($_REQUEST['yunxiaoyuanSchoolId'])){
				$school_info = user_api::GetParternerSchool($_REQUEST['yunxiaoyuanSchoolId']);
				if($school_info!==false){
					$url = (utility_net::isHTTPS()?"https":"http")."://".$school_info->subdomain;
				}
			}
		}
		if(empty($_REQUEST['onceToken']) || empty($_REQUEST['accountId'])){
			return $this->redirect($url);
		}
		//校验 $onceToken
		//如果校验通过直接登录 
		$uid = user_api::verifyOnceToken($_REQUEST['accountId'], $_REQUEST['onceToken']);
		if($uid!==false){
			user_api::loginByUid($uid);
		}
		return $this->redirect($url);
	}
	private function zhihuixiao_passwd($params,$key){
		ksort($params);
		$hash_c = "";
		foreach($params as $k=>$v){
			$hash_c.= $k.":".$v.";";
		}
		return md5($hash_c.$key);

	}
	/**
	 * 第3方登陆接口
	 * @deprecated 
	 * @param int $parterner_source
	 * @param string $key
	 **/
	private function parternerLogin($parterner_source, $key){
		//1.判断参数
		if(
			empty($_REQUEST['mobile']) || 
			empty($_REQUEST['name']) || 
			empty($_REQUEST['timestamp']) || 
			empty($_REQUEST['hash'])
		){
			return $this->redirect("/site.main.login?error=-1");
		}
		//2.检验参数
		$hash = $_REQUEST['hash'];
		unset($_REQUEST['hash']);
		$name = urldecode($_REQUEST['name']);
		$hash_check = self::zhihuixiao_passwd($_REQUEST,$key);
		if($hash !=$hash_check){
			return $this->redirect("/site.main.login?error=-2");
		}
		if(utility_valid::mobile($_REQUEST['mobile'])===false){
			return $this->redirect("/site.main.login?error=-3");
		}
		//3.判断用户是否注册
		user_api::logout();
		$uid = user_api::isRegister($_REQUEST['mobile']);
		if(!empty($uid)){
			$parter_info = user_api::getParterner($parterner_source,$_REQUEST['mobile']);
			if(!empty($parter_info)){
				//如果已经绑定，直接登录
				user_api::loginByUid($uid,true);
			}else{
				//禁用密码登录
			}
			if(user_api::logined()){
				if(!empty($_REQUEST['url']) && $_REQUEST['url']!="/site.main.login" && $_REQUEST['url']!="/site.main.register"){
					return $this->redirect($_REQUEST['url']);
				}
				if(!empty(utility_session::get()['login_url'])){
					$url = utility_session::get()['login_url'];
					unset(utility_session::get()['login_url']);
					return $this->redirect($url);
				}
				return $this->redirect("/site");
			}else{
				return $this->redirect("/site.main.login?error=-4");
			}
		}else{
			$uid = user_api::registerByMobile($name,$_REQUEST['mobile'],$_REQUEST['password'],$this->orgOwner);
		}
		if(empty($uid)){
			return $this->redirect("/site.main.login?error=-5");
		}
		//4.如果是新用户，注册
		//5.如果已经注册，判断用户来源是不是智慧校的
		//6.是的话自动登录，不是的话，跳到我们的登录页
		$parter_info = user_api::getParterner($parterner_source,$_REQUEST['mobile']);

		//新来的用户,注册parterner
		//或者已经登录的用户，但是parter.uid为0的时候，重新绑定
		if( empty($parter_info->parterner_id) ){
			$ret_set = user_api::setParterner($uid,
				$name,
				$parterner_source,
				$_REQUEST['mobile'],
				SJson::encode($_REQUEST),
				$_REQUEST['password'],
				""
			);
			if(empty($ret_set->parterner_id)){
				return $this->redirect("/site.main.login?error=-6");
			}
			//绑定手机号
			$ret = user_api::bindParterner($ret_set->parterner_id,$uid);
			if($ret){
				user_api::loginByUid($uid);
				return $this->redirect("/site");
			}else{
				return $this->redirect("/site.main.login?error=-7");
			}
		}else{
			user_api::loginByUid($uid);
			if(user_api::logined()){
				if(!empty($_REQUEST['url']) && $_REQUEST['url']!="/site.main.login" && $_REQUEST['url']!="/site.main.register"){
					return $this->redirect($_REQUEST['url']);
				}
				if(!empty(utility_session::get()['login_url'])){
					$url = utility_session::get()['login_url'];
					unset(utility_session::get()['login_url']);
					return $this->redirect($url);
				}
				return $this->redirect("/site");
			}else{
				return $this->redirect("/site/main/login?error=-8");
			}
		}

	}
	/**
	 * QQ登录
	 */
	public function pageQQ($inPath){
		$appid=101173081;
		$scope="get_user_info,add_share";
		$callback=utility_services::url("parterner_callbak","user/parterner/qqCallback");
		utility_session::get()['qq_state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
		$code_url= "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=" . $appid . "&redirect_uri=" . urlencode($callback) . "&state=" . utility_session::get()['qq_state'] . "&scope=".$scope;
        utility_session::get()['url']=$_SERVER['HTTP_HOST'];
		if(!empty($_SERVER['HTTP_REFERER'])){
			utility_session::get()['referer']=$_SERVER['HTTP_REFERER'];
		}
		if(!empty($_REQUEST['from'])){
			utility_session::get()['referer']=$_REQUEST['from'];
		}
		$this->redirect($code_url);
	}
	public function pageQQCallback($inPath){
		if(empty($_REQUEST['state']) || $_REQUEST['state'] != utility_session::get()['qq_state']){
			return $this->pageSSO($inPath);
		}
		$appid=101173081;
		$appkey="6ffc145bb0f739663ce470106db5953a";
		$callback=utility_services::url("parterner_callbak","/user/parterner/qqCallback");
		$token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&" . "client_id=" . $appid. "&redirect_uri=" . urlencode($callback) . "&client_secret=" . $appkey. "&code=" . $_REQUEST["code"]."&format=json";
		$result = SHttp::get($token_url);
		parse_str($result,$ret);
		//获取open_id
		//获取用户资料
		if(empty($ret['access_token'])){
			return $this->pageSSO($inPath);
		}
		$graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" . $ret['access_token']."&format=json";
		$str  = SHttp::get( $graph_url, $params=array(), $cookies=array(), $returnHeader=false, $timeout=2);
		if (strpos($str, "callback") !== false)
		{
			$lpos = strpos($str, "(");
			$rpos = strrpos($str, ")");
			$str  = substr($str, $lpos + 1, $rpos - $lpos -1);
		}

		$open_info = SJson::decode($str);
		if(empty($open_info->openid)){
			return $this->pageSSO($inPath);
		}
		$get_user_info = "https://graph.qq.com/user/get_user_info?"
			. "access_token=" . $ret['access_token']
			. "&oauth_consumer_key=" . $appid
			. "&openid=" . $open_info->openid
			. "&format=json";

		$info = file_get_contents($get_user_info);
		$arr = SJson::decode($info);
		if(empty($arr->nickname)){
			return $this->pageSSO($inPath);
		}
		//构造云课平台的APP信息，调用统一登陆接口
		$appid = 1;
		$appkey = "e19fce1e11ed228b9f04315beb3aee91";
		$timestamp=time();
		$url="/";
		if(utility_session::get()['referer']){
			$url = utility_session::get()['referer'];
		}
		$params = array(
			"appid"=>$appid,
			"userid"=>$open_info->openid,
			"timestamp"=>$timestamp,
			"url"=>$url,
			"name"=>urlencode($arr->nickname),
			"password"=>"",
			"mobile"=>"",
		);
		$apphash = user_parterner_api::appHash($params,$appkey);
		$params['apphash'] = $apphash;

		return $this->redirect("/user.parterner.sso?".http_build_query($params));
	}
	/**
	 * 这是微信网页扫码登录及回调
	 */
	public function pageWeixin($inPath){
		utility_session::get()['weixin_state'] = $state = md5(uniqid(rand(), TRUE)); //CSRF protection
        utility_session::get()['url']=$_SERVER['HTTP_HOST'];
		$callback=utility_services::url("parterner_callbak","/user.parterner.weixinCallback");
		$url = "https://open.weixin.qq.com/connect/qrconnect?appid=wx079b98eef983e832&redirect_uri=".urlencode($callback)."&response_type=code&scope=snsapi_login&state=$state#wechat_redirect";
        utility_session::get()['url']=$_SERVER['HTTP_HOST'];
		if(!empty($_SERVER['HTTP_REFERER'])){
			utility_session::get()['referer']=$_SERVER['HTTP_REFERER'];
		}
		if(!empty($_REQUEST['from'])){
			utility_session::get()['referer']=$_REQUEST['from'];
		}
		$this->redirect($url);
	}
	public function pageWeixinCallback($inPath){
		if(empty($_REQUEST['state']) || empty($_REQUEST['code']) || $_REQUEST['state']!=utility_session::get()['weixin_state']){
			return $this->pageSSO($inPath);
		}
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx079b98eef983e832&secret=3325f5ca027fdf94a5b53314ef358d08&code={$_REQUEST['code']}&grant_type=authorization_code";
		$ct = SHttp::get($url);
		$ct = SJson::decode($ct);
		if(empty($ct->access_token) || empty($ct->openid)){
			return $this->pageSSO($inPath);
		}
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$ct->access_token}&openid={$ct->openid}";
		$user_info = SJson::decode(SHttp::get($url));
		if(empty($user_info->unionid)){
			return $this->pageSSO($inPath);
		}
		//构造云课平台的APP信息，调用统一登陆接口
		$appid = 2;
		$appkey = "4e399afb1069758156d0b75c6a59bc42";
		$timestamp=time();
		$url="/";
		if(utility_session::get()['referer']){
			$url = utility_session::get()['referer'];
		}
		$params = array(
			"appid"=>$appid,
			"userid"=>$user_info->unionid,
			"timestamp"=>$timestamp,
			"url"=>$url,
			"name"=>urlencode($user_info->nickname),
			"password"=>"",
			"mobile"=>"",
		);
		$apphash = user_parterner_api::appHash($params,$appkey);
		$params['apphash'] = $apphash;

		return $this->redirect("/user.parterner.sso?".http_build_query($params));
	}
}
