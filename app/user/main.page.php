<?php
class user_main extends STpl{
	function __construct(){
        //$this->user = user_api::loginedUser();
        /*if(empty($this->user)){
            if(!empty($_SERVER['REQUEST_URI'])){
                $this->redirect("/site.main.login?url=".$_SERVER['REQUEST_URI']);
            }else{
                $this->redirect("/site.main.login");
            }
        }*/
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
		$this->assign('domain', $this->domain);

		$org=user_organization::subdomain();
		if(!empty($org)){
			$this->orgOwner = $org->userId; //机构所有者id 以后会根据域名而列取 
		}else{
            $this->orgOwner=0;
           // header('Location: https://www.'.$this->domain);
		}
	}
	function pageLogin($inPath){
		if(!empty($_REQUEST['url'])){
			utility_session::get()['login_url']=$_REQUEST['url'];
		}
		$logined=user_api::logined();
		if($logined){
			if(!empty($_REQUEST['url']) && $_REQUEST['url']!="/site.main.login" && $_REQUEST['url']!="/site.main.register"){
				return $this->redirect($_REQUEST['url']);
			}
			if(!empty(utility_session::get()['login_url'])){
				$url = utility_session::get()['login_url'];
				unset(utility_session::get()['login_url']);
				return $this->redirect($url);
			}
			return $this->redirect("/site");
		}

		if(!empty($inPath[3]) && $inPath[3]=="parterner"){
			$parter_login_info = user_api::getParternerLogin();
			if(!empty($parter_login_info['id'])){
				$parter_info= user_api::getParternerById($parter_login_info['id']);
				if(!empty($parter_info->parterner_id) && empty($parter_info->uid)){
					$this->assign("parter_info",$parter_info);
					$url="/site";
					if(!empty(utility_session::get()['login_url'])){
						$url=utility_session::get()['login_url'];
						unset(utility_session::get()['login_url']);
					}
					//WEB-3420
					if(stripos($url, "activity.2015")){
						$tips = "为了保护您的成绩，请验证您的手机号";
						$this->assign("tips",$tips);
					}
					$mobile="";
					if(!empty($_REQUEST['mobile']) && utility_valid::mobile($_REQUEST['mobile'])){
						$mobile = $_REQUEST['mobile'];
					}
					$name="";
					if(!empty($_REQUEST['name'])){
						$name = urldecode($_REQUEST['name']);
					}
					$this->assign("url",$url);
					$this->assign("mobile",$mobile);
					$this->assign("name",$name);
					//平台和机构绑定的页面模板不是一个
					if(parse_url($url, PHP_URL_HOST)=="www.yunke.com"){
						return $this->render("index/parterner.bind.html");
					}else{
						return $this->render("user/parterner.bind.html");
					}
				}else{
					if(user_api::loginByUid($parter_info->uid,true)){
						utility_session::get()['parterner_uid'] = $parter_info->parterner_uid;
						if(!empty($_REQUEST['url'])){
							return $this->redirect($_REQUEST['url']);
						}
						if(!empty(utility_session::get()['login_url'])){
							$url = utility_session::get()['login_url'];
							unset(utility_session::get()['login_url']);
							return $this->redirect($url);
						}
						return $this->redirect("/site");
					}
				}
			}

		}
		if(weixin_api::is_weixin()){
				return $this->redirect("/weixin.user.login/");
		}
		$error="";
		$forever=false;
		if(!empty($_REQUEST['forever'])){
			$forever=true;
		}
		if(!empty($_POST['uname']) && !empty($_POST['password'])){
			$islogin = user_api::login($_POST['uname'],$_POST['password'],$forever,$ret);
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
				$error="登录失败[".$ret->result->msg."]!";
			}
		}
		$url = !empty($_REQUEST['url'])?$_REQUEST['url']:"";
		$this->assign("url",$url);
		$this->assign("error",$error);

		return $this->render("index/login.html");
	}
	//短信登录
	function pagemobileSmsLogin($inPath){
		$result=new stdclass;
		$mobile = utility_valid::getMobile(trim($_REQUEST['mobile']));
		$isRegister = user_api::isRegister($mobile);
		$uid = (int)$isRegister;
		if(empty($_REQUEST['verifyCode'])){
			$result->field="verifyCode";
			$result->error="图片验证码不能为空";
			return $result;
		}
		if(SCaptcha::check(strtolower(trim(str_replace(" ","",$_REQUEST['verifyCode']))))===false){
			$result->error="图片验证码错误";
			$result->field="verifyCode";
			$result->code="-8";
			return $result;
		}
		if(empty($mobile)){
			$result->field="mobile";
			$result->error="手机号码不能为空";
			return $result;
		}
		if(utility_valid::mobile($mobile)==false){
			$result->field="mobile";
			$result->error="手机号码格式不正确";
			return $result;
		}
		if(empty(trim($_REQUEST['password']))){
			$result->error="验证码不能为空";
			$result->field="password";
			return $result;
		}
		$data = array("mobile"=>$mobile,"code"=>trim($_REQUEST['password']),"tagCode"=>!empty($_REQUEST['tagCode']) ? $_REQUEST['tagCode'] : 0);
		$verify = user_organization::getUserVerifyCodeLoginSms($data);
		if($verify==false){
			$result->error="动态码错误";
			$result->field="mobile";
			return $result;
		}
		
		$orgInfo = user_organization::getOrgByOwner($this->orgOwner);
		if($isRegister==false){
			$password = substr(trim($mobile),5,6);
			$nowTime  = time();
			$username = "用户".substr($mobile, 0, 4).substr($nowTime,-5);
			$uid 	  = user_api::registerByMobile($username,$mobile,$password,$this->orgOwner,$ret);
			verify_api::sendUserOfMobileAndPasswordSMS($mobile,$password,!empty($orgInfo->subname) ? $orgInfo->subname : '');
			$result->error	= "注册成功";
			$result->code   = "1010";
			$result->field  = "mobile";
			return $result;
		}
		if(!empty($_REQUEST['url'])){
			utility_session::get()['login_url']=$_REQUEST['url'];
		}
		$logined=user_api::logined();
		if(!empty(trim($_POST['mobile'])) && !empty($_POST['password'])){
			if($verify){
				if(!empty($_REQUEST['url']) && $_REQUEST['url']!="/site.main.login" && $_REQUEST['url']!="/site.main.register"){
					return $this->redirect($_REQUEST['url']);
				}
				if($uid >0){
					$login = user_api::loginByUid($uid,$forever=false);
				}
				if(!empty(utility_session::get()['login_url'])){
					$url = utility_session::get()['login_url'];
					unset(utility_session::get()['login_url']);
					 $result->data = $url;
					 $result->code = 100;
					 return $result;
				}else{
					if($uid >0){
						$result->data = "登录成功";
						$result->code = 99;
						return $result;
					}
				}
				//return $this->redirect("/site");
			}else{
				$result->error ="手机号或者验证码错误~";
				$result->field = "acount";
				return $result;
			}
		}
	}
	//获取短信动态密码
	public function pagegetLoginSmsCode($inPath){
		$result=new stdclass;
		$mobile = utility_valid::getMobile($_REQUEST['mobile']);
		if(empty($mobile)){
			$result->field="mobile";
			$result->error="手机号码不能为空";
			$result->code="-8";
			return $result;
		}
		if(utility_valid::mobile($mobile)==false){
			$result->field="mobile";
			$result->error="手机号码格式不正确";
			$result->code="-8";
			return $result;
		}
		if(empty($_REQUEST['verifyCode'])){
			$result->field="verifyCode";
			$result->error="图片验证码不能为空";
			$result->code="-8";
			return $result;
		}
		if(SCaptcha::check(strtolower(trim(str_replace(" ","",$_REQUEST['verifyCode']))))===false){
			$result->error="图片验证码错误";
			$result->field="verifyCode";
			$result->code="-8";
			return $result;
		}
		//发送
		$data = array("mobile"=>$mobile,"tagCode"=>!empty($_REQUEST['tagCode']) ? $_REQUEST['tagCode'] : 0);
		$verify = user_organization::getUserVerifyCodeLoginSms($data);
		if(!empty($verify)){
			$result->verify="mobile";
			$result->error="手机动态密码10分钟有效";
			$result->code="-8";
			return $result;
		}
		$r = verify_api::sendMobileSmsLogin($mobile,$ret);
		if($r!==false){
			$result->error="动态码已经发送到您的手机";
            utility_session::get()['register_site']['time']=time();
            $result->field="verify_code";
			$result->code = 1;
			return $result;
		}else{
			$result->error="发送动态码错误,[".$ret->result->msg."]";
			$result->field="verify_code";
			$result->code="-8";
			return $result;
		}
	}
	function pageLoginAjax($inPath){
		$result=new stdclass;
		if(empty($_REQUEST['uname'])){
			$result->error="请输入账号";
			$result->field="uname";
			return $result;
		}
		if(empty($_REQUEST['password'])){
			$result->error="请输入密码";
			$result->field="password";
			return $result;
		}
		if(utility_valid::mobile($_REQUEST['uname'])==false){
			$result->error="手机号码格式不正确";
			$result->field="uname";
			return $result;
		}
		if(user_api::isRegister($_REQUEST['uname'])==false){
			$result->error="该手机号未注册!";
			$result->field="uname";
			return $result;
		}
		$forever=false;
		if(!empty($_REQUEST['forever'])){
			$forever=true;
		}
		$ret = user_api::login($_REQUEST['uname'],$_REQUEST['password'],$forever);
		if(user_api::logined()){
			$result->ok=1;
		}else{
			$result->error="密码错误";
			$result->field="password";
		}
		return $result;
	}
	function pageFindPwd($inPath){
		return $this->pageFindPwd1($inPath);
	}
	function pageFindPwd1($inPath){
		return $this->render("user/findpwd/step_1.html");
	}
	function pageFindPwd1Ajax($inPath){
		$result=new stdclass;
		if(utility_valid::mobile($_REQUEST['uname'])==false){
			$result->error="手机号码格式不正确";
			$result->field="uname";
			return $result;
		}
		if(SCaptcha::check(strtolower(trim(str_replace(" ","",$_REQUEST['code']))))===false){
			$result->error="验证码错误";
			$result->field="code";
			return $result;
		}
		SCaptcha::del(strtolower($_REQUEST['code']));
		//发送手机验证码
		$uname = $_REQUEST['uname'];
		$uid = user_api::isRegister($uname);
		if($uid===false){
			$result->error="手机号没有注册!";
			$result->field="uname";
			return $result;
		}
		if(utility_valid::mobile($uname)!==false){
			$r = verify_api::sendMobileCode($uname,$ret);
			if($r===false){
				$result->error="发送动态码错误,[".$ret->result->msg."]";
				$result->field="code";
				return $result;
			}
		}
		utility_session::get()['findpwd']=array("uname"=>$uname,"uid"=>$uid);
		return $result;
	}
	function pageFindPwd2($inPath){
		if(empty(utility_session::get()['findpwd']['uname'])){
			$this->redirect("/user.main.findpwd");
			exit;
		}
		$uname = utility_session::get()['findpwd']['uname'];
		//$this->assign("uname",$uname);
		$uname{3}="*";
		$uname{4}="*";
		$uname{5}="*";
		$uname{6}="*";
		$this->assign("uname_max",$uname);
		$this->assign("uname",utility_session::get()['findpwd']['uname']);
		return $this->render("user/findpwd/step_2.html");
	}
	function pageFindPwd2Ajax($inPath){
		$result=new stdclass;
		if(empty(utility_session::get()['findpwd']['uname'])){
			$this->redirect("/user.main.findpwd");
			exit;
		}
		$uname = utility_session::get()['findpwd']['uname'];
		if(verify_api::verifyMobile($uname,$_REQUEST['code'])==false){
			$result->error="验证码错误";
			$result->field="code";
			return $result;
		}
		utility_session::get()['findpwd']['checked']=true;
		return $result;
	}
	function pageFindPwd3($inPath){
		if(empty(utility_session::get()['findpwd']['uname'])){
			$this->redirect("/user.main.findpwd");
			exit;
		}
		if(empty(utility_session::get()['findpwd']['checked'])){
			$this->redirect("/user.main.findpwd");
			exit;
		}
		return $this->render("user/findpwd/step_3.html");
	}
	function pageFindPwd3Ajax($inPath){
		$result=new stdclass;
		if(empty(utility_session::get()['findpwd']['uname']) || empty(utility_session::get()['findpwd']['checked'])){
			$this->redirect("/user.main.findpwd");
			exit;
		}
		//修改密码
		$uid = utility_session::get()['findpwd']['uid'];
		$r = user_api::updatePassword($uid,$_REQUEST['password'],$ret);
		if($r===false){
			$result->error="密码长度不合规范";
		}else{
			unset(utility_session::get()['findpwd']);
			//TODO clear session
		}
		return $result;
	}
	function pageFindPwd4($inPath){
		return $this->render("user/findpwd/step_4.html");
	}
	function pageRegister($inPath){
		$error="";
		$this->assign("error",$error);
		return $this->render("user/register.html");
	}
	public function pageRegisterAjax($inPath){
        $result=new stdclass;
		$mobile = utility_valid::getMobile($_REQUEST['mobile']);
		if(empty($mobile)){
			$result->error=SLanguage::tr("手机号码不能为空","site.login");
			$result->field="mobile";
			return $result;
		}
		
		if(utility_valid::mobile($mobile)==false){
			$result->error=SLanguage::tr("手机号码格式错误","site.login");;
			$result->field="mobile";
			return $result;
		}
		if(empty($_REQUEST['verify_code_img'])){
			$result->field="verify_code_img";
			$result->error="图片验证码不能为空";
			$result->code="-8";
			return $result;
		}
		//图片验证-TODO
		if(!empty($_REQUEST['verify_code_img']) && $_REQUEST['verify_code_img']){
			if(SCaptcha::check(strtolower(trim(str_replace(" ","",$_REQUEST['verify_code_img']))))===false){
				$result->error="图片验证码错误";
				$result->field="verify_code_img";
				$result->code="-8";
				return $result;
			}
		}
		$_REQUEST['verify_code'] = str_replace(' ','',$_REQUEST['verify_code']);
		if(empty($_REQUEST['verify_code'])){
			$result->error=SLanguage::tr("手机验证码不能为空","site.login");;
			$result->field="verify_code";
			return $result;
		}
		if(empty($_REQUEST['username'])){
			$result->error=SLanguage::tr("姓名不能为空","site.login");;
			$result->field="username";
			return $result;
		}

		if(preg_match('/^[\x{4e00}-\x{9fa5}]+$/u',trim($_REQUEST['username']))){
			if(strlen($_REQUEST['username'])>15){
				$result->error=SLanguage::tr("中文名称不能大于5个汉字","site.login");;
				$result->field='username';
				return $result;
			}
		}

		if(preg_match("/[a-zA-Z\s]+$/", str_replace(' ','',$_REQUEST['username'])))
        {
            if(strlen($_REQUEST['username'])>25)
            {
				$result->error=SLanguage::tr("英文名称不能大于25个字符","site.login");;
                $result->field='username';
                return $result;
            }
        }
		if(empty($_REQUEST['userpassword'])){
			$result->error=SLanguage::tr("密码不能为空","site.login");;
			$result->field="userpassword";
			return $result;
		}
		if(!preg_match( '/^[\\x20-\\x7e]+$/' ,$_REQUEST['userpassword'])){
			$result->error=SLanguage::tr("密码不能为中文或者特殊字符","site.login");;
			$result->field="userpassword";
			return $result;
		}
		if(strlen($_REQUEST['userpassword'])<6 || strlen($_REQUEST['userpassword'])>20){
			$result->error=SLanguage::tr("密码不能少于6个，多于20个字符","site.login");;
			$result->field="userpassword";
			return $result;
		}
		/*
		if(empty($_REQUEST['password2'])){
			$result->error="确认密码不能为空";
			$result->field="password2";
			return $result;
		}
		if($_REQUEST['password'] != $_REQUEST['password2']){
			$result->error="密码二次输入不一致，请重新输入";
			$result->field="password2";
			return $result;
		}
		*/
		if(verify_api::verifyMobile($mobile,$_REQUEST['verify_code'])==false){
			/*
			$count = !empty($_COOKIE["countCode"]) ? $_COOKIE["countCode"]+1 : 1;
			setCookie("countCode",$count,time()+60*10);
			*/
			$result->error = SLanguage::tr("验证码错误","site.login");
			$result->field="verify_code";
			return $result;
        }
        //if($_REOUEST[''])
		//{{{判断手机是不是已经注册
		if(user_api::isRegister($mobile)){
			$result->error=SLanguage::tr("您的手机号已经注册了，请不要重复注册","site.login");;
			return $result;
		}
		//}}}
		//注册成为用户，并设置验证状态
		$uid = user_api::registerByMobile(trim($_REQUEST['username']),$mobile,$_REQUEST['userpassword'],$this->orgOwner,$ret);
		if($uid){
			//for 活动
			if(!empty($_REQUEST['grade'])){
				user_api::setStudentProfile($uid,array("grade"=>$_REQUEST['grade']));
			}
			//记录统计
            if(isset($_POST['pr']) && !empty($_POST['pr'])){
                $promote_id = trim($_POST['pr']);
                if($promote_id){

                    log_api::insertPromoteLog($promote_id,log_api::reg_succ_get,$uid);
					$promote = promote_api::getPromoteByCode($promote_id);
					if(isset($promote->result->data->items[0]->pk_promote)){
					
					$promote_ids = $promote->result->data->items[0]->pk_promote;
                    promote_api::addPromoteUser(array('fk_user'=>$uid,'fk_promote'=>$promote_ids,'create_time'=>date('Y-m-d H:i:s',time())));
					}
                }
            }
            //记录统计
            if(isset($_POST['pr_code'])  && !empty($_POST['pr_code'])){
						$promote_code = trim($_POST['pr_code']);
                        log_api::insertPromoteLog($promote_code,log_api::reg_succ_post,$uid);
						$promote = promote_api::getPromoteByCode($promote_code);
						if(isset($promote->result->data->items[0]->pk_promote)){
							$promote_id = $promote->result->data->items[0]->pk_promote;
							promote_api::addPromoteUser(array('fk_user'=>$uid,'fk_promote'=>$promote_id,'create_time'=>date('Y-m-d H:i:s',time())));
						}
            }
			//发送成功短信
			$isLogin = user_api::login($mobile,$_REQUEST['userpassword'],false);
			verify_api::sendMobileRegisterSMS($mobile,$_REQUEST['username']);
			$result->uid=$uid;
			 $result->redirect_url = $this->getRedirectUrl();
			return $result;
		}else{
			$result->error=$ret->result->msg;
			return $result;
		}
	}
	public function pageTest($inPath){
		//verify_api::sendMobileCode("13488810858");
		//verify_api::sendMobileRegisterSMS("13488810858",13488810858);
	}
	public function pageVerifyCode($inPath){
		$result=new stdclass;
		$mobile = utility_valid::getMobile($_REQUEST['mobile']);
		if(empty($mobile)){
			$result->field="mobile";
			$result->error="手机号码不能为空";
			$result->code="-8";
			return $result;
		}
		if(utility_valid::mobile($mobile)==false){
			$result->field="mobile";
			$result->error="手机号码格式不正确";
			$result->code="-8";
			return $result;
		}
		//图片验证-TODO
		if(empty($_REQUEST['verify_code_img'])){
			$result->field="verify_code_img";
			$result->error="图片验证码不能为空";
			$result->code="-8";
			return $result;
		}
		if(SCaptcha::check(strtolower(trim(str_replace(" ","",$_REQUEST['verify_code_img']))))===false){
			$result->error="图片验证码错误";
			$result->field="verify_code_img";
			$result->code="-8";
			return $result;
		}
        //删除验证码
        //SCaptcha::del(strtolower($_REQUEST['verify_code_img']));
		
		//{{{判断手机是不是已经注册
		if(user_api::isRegister($mobile)){
			$result->error="您的手机号已经注册了，请不要重复注册";
			$result->error_code = -1;
			return $result;
		}
		//}}}
		//发送
		if(empty($_REQUEST['voice'])){
			$r = verify_api::sendMobileCode($mobile,$ret);
		}else{
			$r = verify_api::sendMobileVoice($mobile,$ret);
		}
		if($r!==false){
			if(empty($_REQUEST['voice'])){
				$result->error="验证码已经发送到您的手机";
			}else{
				$result->error="验证码将通过 400-081-2798 为您播报，请接听";
			}
            utility_session::get()['register_site']['time']=time();
            $result->field="verify_code";
			$result->ok = 1;
			return $result;
		}else{
			$result->error="发送验证码错误,[".$ret->result->msg."]";
			$result->field="verify_code";
			$result->code="-8";
			return $result;
		}
	}
	
	public function pageVerifyCodePwd($inPath){
		$result=new stdclass;
		if(empty($_REQUEST['mobile'])){
			$result->field="mobile";
			$result->error="手机号码不能为空";
			return $result;
		}
		if(utility_valid::mobile($_REQUEST['mobile'])==false){
			$result->field="mobile";
			$result->error="手机号码格式不正确";
			return $result;
		}
		//图片验证-TODO
		if(SCaptcha::check(strtolower(trim(str_replace(" ","",$_REQUEST['verify_code_img']))))===false){
			$result->error="图片验证码错误";
			$result->field="verify_code_img";
			return $result;
		}
		//{{{判断手机是不是已经注册
		$uid = user_api::isRegister($_REQUEST['mobile']);
		if($uid===false){
			$result->error="手机号没有注册!";
			return $result;
		}
		//}}}
		//发送
		if(empty($_REQUEST['voice'])){
			$r = verify_api::sendMobileCode($_REQUEST['mobile'],$ret);
		}else{
			$r = verify_api::sendMobileVoice($_REQUEST['mobile'],$ret);
		}
		if($r!==false){
			if(empty($_REQUEST['voice'])){
				$result->error="验证码已经发送到您的手机";
			}else{
				$result->error="验证码将通过 400-081-2798 为您播报，请接听";
			}
			$result->field="code";
			$result->ok = 1;
			return $result;
		}else{
			$result->error="发送验证码错误,[".$ret->result->msg."]";
			$result->field="code";
			return $result;
		}
	}
	public function pageVerifyCodeBind($inPath){
		$result=new stdclass;
		if(empty($_REQUEST['mobile'])){
			$result->field="mobile";
			$result->error="手机号码不能为空";
			return $result;
		}
		if(utility_valid::mobile($_REQUEST['mobile'])==false){
			$result->field="mobile";
			$result->error="手机号码格式不正确";
			return $result;
		}
		//图片验证-TODO
		if(SCaptcha::check(strtolower(trim(str_replace(" ","",$_REQUEST['verify_code_img']))))===false){
			$result->error="图片验证码错误";
			$result->field="verify_code_img";
			$result->code = -8;
			return $result;
		}
		//发送
		if(empty($_REQUEST['voice'])){
			$r = verify_api::sendMobileCode($_REQUEST['mobile'],$ret);
		}else{
			$r = verify_api::sendMobileVoice($_REQUEST['mobile'],$ret);
		}
		if($r!==false){
			if(empty($_REQUEST['voice'])){
				$result->error="动态码已经发送到您的手机";
				$result->code = 200;
			}else{
				$result->error="验证码将通过 400-081-2798 为您播报，请接听";
			}
			$result->field="code";
			$result->ok = 1;
			return $result;
		}else{
			$result->error="发送验证码错误,[".$ret->result->msg."]";
			$result->field="code";
			$result->code = -8;
			return $result;
		}
	}
	function pageLogout($inPath){
		user_api::logout();
		if(!empty($_REQUEST['url'])){
			return $this->redirect($_REQUEST['url']);
		}
		return $this->redirect("/site");
	}
    function pageMenu($inPath){
        $type=!empty($inPath[3])?$inPath[3]:'default';
		$stud=!empty($inPath[4])?$inPath[4]:'student';
		$teach = array("growth","mycourse","myorder","myfav","mydiscount","studentTaskListShow");
		$Tinfo=array("edit","timeable","student","help","newtimetable","teacherOfCourseList");
		$infoMenuNoDisplay =array("infobase","infouploadpic","list","infopassword","address");
		$this->assign("learnCss",$stud);
        $this->assign("css",$type);
		$this->assign("Tinfo",$Tinfo);
		$this->assign("infoMenuNoDisplay",$infoMenuNoDisplay);
		$menuArr = array("infobase","infouploadpic","list","infopassword","address");
		$this->assign("menuArr",$menuArr);
		$this->assign("teach",$teach);
        $user = user_api::loginedUser();
        $userInfo = user_api::getUser($user['uid']);
        $ipinfo = utility_ip::info(utility_ip::realIp());
        $provinces=user_organization::getRegionList(array('parent_region_id'=>0,'level'=>0));
        $province='';
        if(!empty($provinces)&&!empty($userInfo->student->region_level0)){
            foreach($provinces as $p){
                if($p->region_id==$userInfo->student->region_level0){
                    $province=$p->name;
                }
            }
            $citys=user_organization::getRegionList(array('parent_region_id'=>$userInfo->student->region_level0,'level'=>1));
        }
        $city='';
        if(!empty($citys)&&!empty($userInfo->student->region_level0)&&!empty($userInfo->student->region_level1)){
            foreach($citys as $c){
                if($c->region_id==$userInfo->student->region_level1){
                    $city=$c->name;
                }
            }
        }
		
		$userLevel = 1;
		$levelRet = user_api::getUserLevel($user['uid']);
		if(!empty($levelRet)){
			$userLevel = $levelRet->fk_level;
		}
		$this->assign('userLevel',$userLevel);
		
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","platform");
		$platform_url = $domain_conf->platform;
		$this->assign('platform_url',$platform_url);
		
        //判断教师身份 为了切换个人中心快捷链接
        $isTeacher=user_api::isTeacher($this->orgOwner,$user['uid']);
        $this->assign("isTeacher",$isTeacher);
        $this->assign("userInfo",$userInfo);
        $this->assign("ipinfo",$ipinfo);
        $this->assign("province",$province);
        $this->assign("city",$city);
        $menu=user_menu::instance()->create($type);
        $this->assign('type',$type);
        $this->assign('menu',$menu);
		$this->assign('userId', $user['uid']);
        //$this->assign('li',$ab);
        return $this->render("user/menu.v2.html");
    }
    private function getRedirectUrl(){
        $url = '/';
        if(isset($_REQUEST['url']) && $_REQUEST['url']){
            $request_url = urldecode($_REQUEST['url']);
            $domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
            $domain = $domain_conf->domain;
            $parseUrl = parse_url($request_url);
            if(isset($parseUrl['scheme'])){
                    if(preg_match("/$domain/",$parseUrl['host'])){
                        $url = $request_url;
                    }
                }elseif(isset($parseUrl['path'])){
                    if(preg_match("/$domain/",$parseUrl['path'])){
                        $url = 'https://'.$request_url;
                    }
                }
                
        }elseif(!empty($_SERVER['HTTP_REFERER'])){
            $url = $_SERVER['HTTP_REFERER'];
        }
        return $url;
    }

}
