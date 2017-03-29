<?php
class site_main extends STpl{
	private static $domain;
	private static $org;
	private static $orgInfo;
	private static $orgOwner;
	function __construct(){
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		if(empty(self::$domain)){
			self::$domain = $domain_conf->domain;
		}
		if(empty(self::$org)){
			self::$org=user_organization::subdomain();
			if(empty(self::$org)){
				header('Location: https://www.'.self::$domain);
			}
			self::$orgOwner = self::$org->userId; //机构所有者id 以后会根据域名而列取
			self::$orgInfo = user_organization::getOrgByOwner(self::$orgOwner);
			$this->assign('is_pro',isset(self::$orgInfo->is_pro) ? self::$orgInfo->is_pro : 0);
		}

		$this->assign('domain', self::$domain);
	}

	//xiaowo 头header
	public function pageWoTop($inpath){
		$uri=!empty($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'/';
		$tmp=explode('.',$uri);
		$uri=end($tmp);
		$arr = array();
		$user=user_api::loginedUser();
        $userInfo = user_organization::subdomain();
		//是否显示注册成功引导遮挡
		$successTips=!empty(utility_session::get()['successTips'])?true:false;
		//显示一次就关闭
		utility_session::get()['successTips']=false;
		$oidArr=array();
		$isAdmin=false;
		 //判断登录
        $user=user_api::loginedUser();
        //判断管理员
        $isAdmin		=	user_api::isAdmin($userInfo->userId,$user['uid']);
        $isTeacher		=	user_api::isTeacher($userInfo->userId,$user['uid']);
//		$messagesNum 	= 	message_api::getUnreadInstationNum($user['uid'],$user['token']);
        $messagesNum = message_api::getUnreadNewsRemind($user['uid']);
		$arr['user']['types']['student']		= !empty($user['types']->student) ?$user['types']->student : false;
		$arr['user']['types']['teacher']		= !empty($isTeacher) ? $isTeacher : false;
		$arr['user']['types']['organization']	= !empty($isAdmin) ? $isAdmin : false;
		$arr['user']['small']	                = !empty($user['small']) ? $user['small'] : '';
		$arr['user']['real_name']               = !empty($user['real_name']) ? $user['real_name'] : '';
		$arr['user']['name']               		= !empty($user['name']) ? $user['name'] : '';
		$arr['user']['retMessagesNum'] 			= !empty($messagesNum) ? $messagesNum : 0;
		echo json_encode($arr);
	}


    function pageOrgName($inPath){
        if(empty(self::$orgInfo->name) && empty(self::$orgInfo->subname)){
            $orgName='高能壹佰';
        }else{
            $orgName=!empty(self::$orgInfo->subname)?self::$orgInfo->subname:mb_substr(self::$orgInfo->name,0,6,'utf-8');
        }
       echo  $orgName;
	}
	/**
	 * 第3方统计代码
	 */
    function pageStatistic($inPath){
		if(!empty(self::$orgInfo->statistic)){
			return self::$orgInfo->statistic;
		}
    }
	function pageRedirect($inPath){
		$domain = $_SERVER['HTTP_HOST'];
		header("Location: https://$domain");
	}
	public function pageLoginAjax($inPath){
        $url = !empty($_REQUEST['url'])?$_REQUEST['url']:(!empty($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'');
        if(!empty($_REQUEST['forever'])){
            $forever=true;
        }else{
            $forever=false;
        }
        $result=new stdClass;
        if(!empty(trim($_POST['uname'])) && !empty($_POST['pass'])){
            $islogin = user_api::login(trim($_POST['uname']),$_POST['pass'],$forever,$ret);
            if(user_api::logined()){
                $result->url=$url;
            }else{
                if(!empty($ret->result->code)&&$ret->result->code==-2){
                    $result->field="pass";
                }else{
                    $result->field="uname";
                }
                $ret->result->msg=!empty($ret->result->msg)?$ret->result->msg:'';
                $result->error=SLanguage::tr($ret->result->msg,"site.login");
            }
        }
        return $result;
	}
    function pageCheckLoginAjax($inPath){
        if(user_api::logined()==true){
            return 'yes';
        }else{
            return 'no';
        }

    }
    function pageTest($inPath){
        $tpl = user_menu::instance()->create();
        //return $this->render("/site/test.html");
    }
    function pageRegister($inPath){
        if(empty(utility_session::get()['login_url'])){
            utility_session::get()['login_url']= !empty($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
        }
        $logined=user_api::logined();
        if($logined){
			$url = $this->getRedirectUrl();
            return $this->redirect($url);
        }
        //统计
        if(isset($_GET['pr'])){
            $promote_id = trim($_GET['pr']);
            $this->assign('pr',$promote_id);
            if($logined){
                    $loginedUser = user_api::loginedUser();
                    log_api::insertPromoteLog($promote_id,log_api::register_page,$loginedUser['uid']);
                }else{
                    log_api::insertPromoteLog($promote_id,log_api::register_page);
                }
         }

        $error="";
        $this->assign("error",$error);
        if(!empty($inPath[3]) && $inPath[3]=="parterner"){
            $parter_login_info = user_api::getParternerLogin();
            if(!empty($parter_login_info['id'])){
                $parter_info= user_api::getParternerById($parter_login_info['id']);
                if(!empty($parter_info->parterner_id) && empty($parter_info->uid)){
                    $this->assign("parter_info",$parter_info);
                    $url="/site.main.entry";
                    if(!empty(utility_session::get()['login_url'])){
                        $url=utility_session::get()['login_url'];
                    }
                    $this->assign("url",$url);
                    return $this->render("user/parterner.bind.html");
                }else{
                    if(user_api::loginByUid($parter_info->uid,true)){
						utility_session::get()['parterner_uid'] = $parter_info->parterner_uid;
                        if(!empty($_REQUEST['url'])){
                            return $this->redirect($_REQUEST['url']);
                        }
                        if(!empty(utility_session::get()['login_url'])){
                            $url = utility_session::get()['login_url'];
                            return $this->redirect($url);
                        }
                        return $this->redirect("/");
                    }
                }
            }
        }
        if(weixin_api::is_weixin()){
                return $this->redirect("/weixin.user.login/");
        }

         if(isset($_REQUEST['url']) && $_REQUEST['url']){
                $url = urldecode($_REQUEST['url']);
                $domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
                $domain = $domain_conf->domain;
                $parseUrl = parse_url($url);
                if(isset($parseUrl['scheme'])){
                    if(preg_match("/$domain/",$parseUrl['host'])){
                        $this->assign('url',$url);
                    }
                }elseif(isset($parseUrl['path'])){
                    if(preg_match("/$domain/",$parseUrl['path'])){
                        $this->assign('url','https://'.$url);
                    }
                }

         }
        $time=!empty(utility_session::get()['register_site']['time'])?time()-utility_session::get()['register_site']['time']:0;
        if($time>60||$time<=0){
            $time=60;
        }
        if($time>0){
        }
        $this->assign("time",60-$time);
        return $this->render("/site/register.html");
    }
    function pageLogin($inPath){
        if(empty(utility_session::get()['login_url'])){
            utility_session::get()['login_url']= !empty($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
        }
		$result=new stdclass;
		$error2="";
		$error3="";
		/*if(!empty($_POST['submit'])&&!empty($_POST['uname'])&&(utility_valid::mobile($_POST['uname'])==false)){
			$error2=SLanguage::tr("手机号码格式不正确","site.login");
		}*/
		if(!empty($_POST['submit'])&&!empty($_POST['uname'])&&empty($_POST['password'])){
			$error3=SLanguage::tr("密码不能为空","site.login");
		}
		if(!empty($_REQUEST['url'])){
			utility_session::get()['login_url'] = $_REQUEST['url'];
		}
        if(!empty($_REQUEST["cid"])){
            utility_session::get()['login_cid']=$_REQUEST["cid"];
        }
        if(!empty($_REQUEST["clid"])){
            utility_session::get()['login_clid']=$_REQUEST["clid"];
        }
        if(!empty($_REQUEST["source"])){
            utility_session::get()['login_source']=$_REQUEST["source"];
        }
        utility_session::get()['login_resellOrgId']=!empty($_REQUEST["resellOrgId"])?$_REQUEST["resellOrgId"]:(!empty($_REQUEST["qudaoCode"])?$_REQUEST["qudaoCode"]:0);
		if(!empty($_POST['submit'])&&!empty($_POST['uname'])){
			$uname = $_POST['uname'];
			if(!empty($_POST['areaId'])&&$_POST['areaId']=='86'){
				if(utility_valid::mobile($_POST['uname'])==false){
					$error2	=	SLanguage::tr("手机号码格式不正确","site.login");
				}
			}else{
				$areaId = trim($_POST['areaId']);
				$tname 	= trim($_POST['uname']);
				$uname 	= "+".$areaId.$tname;
			}
		}
        $logined=user_api::logined();
        if($logined){
			$url = $this->getRedirectUrl();
            return $this->redirect($url);
        }
        if(!empty($inPath[3]) && $inPath[3]=="parterner"){
            $parter_login_info = user_api::getParternerLogin();
            if(!empty($parter_login_info['id'])){
                $parter_info= user_api::getParternerById($parter_login_info['id']);
                if(!empty($parter_info->parterner_id) && empty($parter_info->uid)){
                    $this->assign("parter_info",$parter_info);
                    $url="/site.main.entry";
                    if(!empty(utility_session::get()['login_url'])){
                        $url=utility_session::get()['login_url'];
                    }
                    $this->assign("url",$url);
                    return $this->render("user/parterner.bind.html");
                }else{
                    if(user_api::loginByUid($parter_info->uid,true)){
						utility_session::get()['parterner_uid'] = $parter_info->parterner_uid;
                        if(!empty($_REQUEST['url'])){
                            return $this->redirect($_REQUEST['url']);
                        }
                        if(!empty(utility_session::get()['login_url'])){
                            $url = utility_session::get()['login_url'];
                            return $this->redirect($url);
                        }
                        return $this->redirect("/");
                    }
                }
            }
        }
        if(weixin_api::is_weixin()){
            $sc = "http";
            if (utility_net::isHTTPS()) $sc = "https";
                return $this->redirect("/weixin.user.login/?url=".$sc."://".$_SERVER[HTTP_HOST].utility_session::get()['login_url']."");
        }
        $error1="";
        $forever=false;
        if(!empty($_REQUEST['forever'])){
            $forever=true;
        }

        if(!empty($_POST['uname']) && !empty($_POST['password'])){
            $islogin = user_api::login(trim($uname),$_POST['password'],$forever,$ret);
			if(utility_valid::mobile($_POST['uname'])==false){
			$error2=SLanguage::tr("手机号码格式不正确","site.login");
			}
            if(!empty($islogin)){
                if (!empty(utility_session::get()['login_source'])&&utility_session::get()['login_source'] === 'reg') {
                    $user = user_api::loginedUser();
                    $result = user_api::getUserAddress($user['uid']);
                    $document = course_api::getCourseOne(utility_session::get()['login_cid']);
                    if (empty($result->result->items) && !empty($document->document)) {
                        return $this->redirect("/layer/main/addUserAddressLayer/m/".utility_session::get()['login_source'].'/'.utility_session::get()['login_cid'].'/'.utility_session::get()['login_clid'].'/'.utility_session::get()['login_resellOrgId'].'/'.utility_session::get()['login_url']);
                    }                    
                    if (utility_session::get()['login_cid'] && utility_session::get()['login_clid']) {
                        $regInfo = $this->_addRegistration(utility_session::get()['login_cid'], utility_session::get()['login_clid'],utility_session::get()['login_resellOrgId']);
                        if ($regInfo['code'] === -2){
                            return $this->redirect("/order.main.buy/course/".utility_session::get()['login_cid']."/".utility_session::get()['login_clid']."/".utility_session::get()['login_resellOrgId']);
                        } else {
                            if($regInfo===true){
                                $user     = user_api::loginedUser();
                                $info = array(
                                    "userId"=>$user['uid'],
                                    "courseId"=>utility_session::get()['login_cid'],
                                    "orgId"=>!empty(self::$orgInfo->oid)?self::$orgInfo->oid:0,
                                );
                                user_organizationStudent_api::addOrganizationStudent($info);
                            }
                            if(!empty(utility_session::get()['login_url'])){
                                $url = utility_session::get()['login_url'];
                                return $this->redirect($url);
                            }
                        }
                        utility_session::get()['login_cid']='';
                        utility_session::get()['login_clid']='';
                        utility_session::get()['login_source']='';
                        utility_session::get()['login_resellOrgId'] = '';
                        return $this->redirect("/");
                    }
                }
                if(!empty($_REQUEST['url']) && $_REQUEST['url']!="/site/main/login" && $_REQUEST['url']!="/site/main/register"){
                    return $this->redirect($_REQUEST['url']);
                }
                if(!empty(utility_session::get()['login_url'])){
                    $url = utility_session::get()['login_url'];
                    return $this->redirect($url);
                }
                return $this->redirect("/");
            }else{
                if($ret->result->code!=-2){
                    $error1=SLanguage::tr("账号或者密码错误","site.login");
                }else{
                    $error2=SLanguage::tr("账号或者密码错误","site.login");
                }
            }
        }
        $url = !empty($_REQUEST['url'])?$_REQUEST['url']:(!empty($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'');
        $uname=!empty($_POST['uname'])?$_POST['uname']:'';
        $this->assign("url",$url);
        $this->assign("clid",!empty($_REQUEST["clid"])?$_REQUEST["clid"]:0);
        $this->assign("source",!empty($_REQUEST["source"])?$_REQUEST["source"]:"");
        $this->assign("cid",!empty($_REQUEST["cid"])?$_REQUEST["cid"]:1);
        $this->assign("resellOrgId",!empty($_REQUEST["resellOrgId"])?$_REQUEST["resellOrgId"]:(!empty($_REQUEST["qudaoCode"])?$_REQUEST["qudaoCode"]:0));

        $this->assign("uname",$uname);
        $this->assign("error1",$error1);
        $this->assign("error2",$error2);
        $this->assign("error3",$error3);

        return $this->render("/site/login.html");
    }
    //手机端填写收货地址自动报名
    public function pageRegAjax($inPath){
            if(!empty($inPath['3'])){
                utility_session::get()['login_cid'] = $inPath['3'];
            }
            if(!empty($inPath['4'])){
                utility_session::get()['login_clid'] = $inPath['4'];
            }
            if(!empty($inPath['5'])){
                utility_session::get()['login_resellOrgId'] = $inPath['5'];
            }
            // if(!empty($inPath['6'])){
            //     utility_session::get()['login_url'] = $inPath['6'];
            // }
            $user = user_api::loginedUser();            
            $result = user_api::getUserAddress($user['uid']);
            $document = course_api::getCourseOne(utility_session::get()['login_cid']);
            if (empty($result->result->items) && !empty($document->document)) {
                $url = "/layer/main/addUserAddressLayer/m/".utility_session::get()['login_source'].'/'.utility_session::get()['login_cid'].'/'.utility_session::get()['login_clid'].'/'.utility_session::get()['login_resellOrgId'].'/'.utility_session::get()['login_url'];
                
                return json_encode(array('code'=>1,'url'=>$url));
                //return $this->redirect("/layer/main/addUserAddressLayer/m/".utility_session::get()['login_source'].'/'.utility_session::get()['login_cid'].'/'.utility_session::get()['login_clid'].'/'.utility_session::get()['login_resellOrgId']);
            }                                  
            if (utility_session::get()['login_cid'] && utility_session::get()['login_clid']) {
                        $regInfo = $this->_addRegistration(utility_session::get()['login_cid'], utility_session::get()['login_clid'],utility_session::get()['login_resellOrgId']);
                        if ($regInfo['code'] === -2){
                            $url = "/order.main.buy/course/".utility_session::get()['login_cid']."/".utility_session::get()['login_clid']."/".utility_session::get()['login_resellOrgId'].'/'.utility_session::get()['login_url'];
                            return json_encode(array('code'=>1,'url'=>$url));
                            //return $this->redirect("/order.main.buy/course/".utility_session::get()['login_cid']."/".utility_session::get()['login_clid']."/".utility_session::get()['login_resellOrgId']);
                        } else {
                            if($regInfo===true){
                                $user     = user_api::loginedUser();
                                $info = array(
                                    "userId"=>$user['uid'],
                                    "courseId"=>utility_session::get()['login_cid'],
                                    "orgId"=>!empty(self::$orgInfo->oid)?self::$orgInfo->oid:0,
                                );
                                user_organizationStudent_api::addOrganizationStudent($info);
                            }
                            if(!empty(utility_session::get()['login_url'])){
                                $url = utility_session::get()['login_url'];                                                              
                                return json_encode(array('code'=>1,'url'=>$url));
                                //return $this->redirect($url);
                            }
                        }
                        // utility_session::get()['login_cid']='';
                        // utility_session::get()['login_clid']='';
                        // utility_session::get()['login_source']='';
                        // utility_session::get()['login_resellOrgId'] = '';                        
                        return json_encode(array('code'=>1,'url'=>'/'));
                        //return $this->redirect("/");
            }              
    }

    function pageLogout($inPath){
		user_api::logout();
        if(!empty($_REQUEST['url'])){
            return $this->redirect($_REQUEST['url']);
        }
		if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']){
			$url = $_SERVER['HTTP_REFERER'];
			$url = parse_url($url);
            if(isset($url['path']) && isset($url['query'])){
                $url = $url['path'].'?'.$url['query'];
                $this->redirect($url);
            }
			if(isset($url['path']) && $url['path']){
				$this->redirect($url['path']);
			}
		}
        return $this->redirect("/");
    }
    public function pageVerifyCodePwd($inPath){
        $result=new stdclass;
        //发送验证码
        $r = verify_api::sendMobileCode(utility_session::get()['findpwd']['uname'],$ret);
        if($r===false){
            $result->error="发送验证码错误,[".$ret->result->msg."]";
            $result->field="code";
            return $result;
        }
        utility_session::get()['findpwd']['time']=time();
        $result->field="code";
        $result->ok = 1;
        return $result;
    }
    function pageForget1($inPath){
        if(!empty(user_api::loginedUser())){
            $this->redirect("/site.main.entry");
            exit;
        }
		return $this->render("/site/forget.step1.html");
    }
    function pageForget1Ajax($inPath){
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
        $uname = $_REQUEST['uname'];
        $uid = user_api::isRegister($uname);
        if($uid===false){
            $result->error="手机号没有注册!";
            $result->field="uname";
            return $result;
        }
        //发送手机验证码
        if(utility_valid::mobile($uname)!==false){
            $r = verify_api::sendMobileCode($uname,$ret);
            if($r===false){
                $result->error="发送动态码错误,[".$ret->result->msg."]";
                $result->field="code";
                return $result;
            }
        }
        utility_session::get()['findpwd']=array("uname"=>$uname,"uid"=>$uid,'time'=>time());
        return $result;
    }
    function pageForget2($inPath){
        if(!empty(user_api::loginedUser())){
            $this->redirect("/site.main.entry");
            exit;
        }
        if(empty(utility_session::get()['findpwd']['uname'])){
            $this->redirect("/site.main.forget1");
            exit;
        }
        $uname = utility_session::get()['findpwd']['uname'];
        $uname{3}="*";
        $uname{4}="*";
        $uname{5}="*";
        $uname{6}="*";
        $time=!empty(utility_session::get()['findpwd']['time'])?time()-utility_session::get()['findpwd']['time']:0;
        if($time>60||$time<0){
           $time=60;
        }
        $this->assign("uname",$uname);
        $this->assign("time",60-$time);
		return $this->render("/site/forget.step2.html");
    }
    function pageForget2Ajax($inPath){
        $result=new stdclass;
        if(empty(utility_session::get()['findpwd']['uname'])){
            $this->redirect("/site.main.forget1");
            exit;
        }
        $uname = utility_session::get()['findpwd']['uname'];
		$_REQUEST['code'] = str_replace(' ','',$_REQUEST['code']);
        if(verify_api::verifyMobile($uname,$_REQUEST['code'])==false){
            $result->error="验证码错误";
            $result->field="code";
            return $result;
        }
        utility_session::get()['findpwd']['checked']=true;
        return $result;
    }
    function pageForget3($inPath){
        if(!empty(user_api::loginedUser())){
            $this->redirect("/site.main.entry");
            exit;
        }
        if(empty(utility_session::get()['findpwd']['uname'])){
            $this->redirect("/site.main.forget1");
            exit;
        }
        if(empty(utility_session::get()['findpwd']['checked'])){
            $this->redirect("/site.main.forget2");
            exit;
        }
		return $this->render("/site/forget.step3.html");
    }
    function pageForget3Ajax($inPath){
        $result=new stdclass;
        if(empty(utility_session::get()['findpwd']['uname']) || empty(utility_session::get()['findpwd']['checked'])){
            $this->redirect("/site.main.forget1");
            exit;
        }
        if(empty($_REQUEST['password'])){
            $result->error="密码不能为空";
            $result->field="password";
            return $result;
        }
        if(!preg_match( '/^[\\x20-\\x7e]+$/' ,$_REQUEST['password'])){
            $result->error="密码不能为中文或者特殊字符";
            $result->field="password";
            return $result;
        }
        if(strlen($_REQUEST['password'])<6 || strlen($_REQUEST['password'])>20){
            $result->error="密码不能少于6个字符，不能多于20个字符";
            $result->field="password";
            return $result;
        }
        if(empty($_REQUEST['password2'])){
            $result->error="确认密码不能为空";
            $result->field="password2";
            return $result;
        }
        if($_REQUEST['password'] != $_REQUEST['password2']){
            $result->error="两次密码输入不一致，请重新输入";
            $result->field="password2";
            return $result;
        }
        //修改密码
        $uid = utility_session::get()['findpwd']['uid'];
        $r = user_api::updatePassword($uid,$_REQUEST['password']);
        if($r===false){
            $result->error="密码修改失败，请重试";
            $result->field="password";
        }else{
            unset(utility_session::get()['findpwd']);
            //TODO clear session
        }
        return $result;
    }
    function pageForget4($inPath){
        if(!empty(user_api::loginedUser())){
            $this->redirect("/site.main.entry");
            exit;
        }
		return $this->render("/site/forget.step4.html");
    }
	function pageWeixin($inPath){
		if(weixin_api::is_weixin()){
			$options = (weixin_api::getJsOptions());
			$this->assign("signPackage",$options);
			return $this->render("index/header.weixin.html");
		}
	}
	function pageHeader($inPath){
		$logoconf = SConfig::getConfig(ROOT_CONFIG."/xiaowo.logo.conf", "logo");
		$skinType = array(1=>"orange",2=>"green",3=>"skyblue",4=>"blue");
		$skin	  = isset($skinType[self::$orgInfo->site_skin]) ? $skinType[self::$orgInfo->site_skin] : "orange";
		$this->assign('white_logo',$logoconf->white_logo->download);
		$this->assign('blue_logo',$logoconf->blue_logo->download);
		if(!empty(self::$orgInfo->is_pro)){
			$this->assign('urlskin',utility_cdn::css('/assets_v2/css/skin_'.$skin.'.'.'css'));
		}
		return $this->render("site/header.html");
	}

    public function pageHeaderPlatform()
    {
        $this->render("site/HeaderPlatform.html");
    }
	function pageNav($inPath){
        $subnav="";
        if(!empty($inPath[3]))$subnav=$inPath[3];
		$orgId = self::$orgInfo->oid;
		$orgmemberset = user_organization::getMemberSetList($orgId,1);
		$this->assign('orgmemberset',$orgmemberset);
        $this->assign("subnav",$subnav);
		$this->assign("orgInfo",self::$orgInfo);
		$this->assign("flag",!empty($inPath[3]) ? $inPath[3] : 0);
		$cateId = '';
		$orgRes = user_organization::getOrgByOwner(self::$orgOwner);
		if(!empty($orgRes->scopes)){
			$cate = explode(',',$orgRes->scopes);
			if(count($cate)==1){
				$cateId = "?fc=".$cate[0];
				$second = $this->getSecondCateList($cate[0]);
				if(count($second)==1){
					$secondKey = array_keys($second);
					$cateId .= "&sc=".$secondKey[0];
				}
			}
		}
		//xiao wo logo
		//$logoconf = SConfig::getConfig(ROOT_CONFIG."/xiaowo.logo.conf", "logo");
		//$this->assign('white_logo',$logoconf->white_logo->download);
		$this->assign("cateId",$cateId);
		return $this->render("site/nav2.html");
	}

	//获取二级分类
	private function getSecondCateList($firstCate){
		$showCateList = array();
		$cateRet = course_api::getNodeCate($firstCate);
		if(!empty($cateRet)){
			foreach($cateRet as $cate){
				$cateIdArr[] = $cate->pk_cate;
			}
			$courseRet = course_api::checkCourseBySecondCateArr($cateIdArr,self::$orgOwner);
			if(!empty($courseRet)){
				foreach($courseRet as $ro){
					$tempCate[] = $ro->second_cate;
				}
				$hideCate = array_diff($cateIdArr,$tempCate);
				if(!empty($hideCate)){
					foreach($cateRet as $cl){
						if(!in_array($cl->pk_cate,$hideCate)){
							$showCateList[] = $cl;
						}
					}
				}else{
					$showCateList = $cateRet;
				}
			}
		}
		if(!empty($showCateList)){
			foreach($showCateList as $so){
				$tempShow[$so->pk_cate] = $so->name_display;
			}
			$showCateList = $tempShow;
		}
		return $showCateList;
	}

	/**
	只有动态部分
	**/
	function pageUserNav2($inPath){
        //判断登录
        $user=user_api::loginedUser();
        //判断管理员
        $isAdmin=user_api::isAdmin(self::$orgOwner,$user['uid']);
        //判断老师
        $isTeacher=user_api::isTeacher(self::$orgOwner,$user['uid']);
        if(!empty($user)){
            //$userInfo = user_api::getUser($user['uid']);
//            $retMessagesNum = message_api::getUnreadInstationNum($user['uid'],$user['token']);
            $retMessagesNum = message_api::getUnreadNewsRemind($user['uid']);
            $this->assign("retMessagesNum", $retMessagesNum);
        }else{
            $this->assign("retMessagesNum", 0);
        }
        $this->assign("isAdmin",$isAdmin);
        $this->assign("isTeacher",$isTeacher);
        $this->assign("user",$user);
        $subnav="";
        if(!empty($inPath[3]))$subnav=$inPath[3];
        $this->assign("subnav",$subnav);

		return $this->render("site/nav.user.2.html");
	}
	function pageUserNav($inPath){
		return $this->pageNav($inPath);
/*
        //判断登录
        $user=user_api::loginedUser();
        //判断管理员
        $isAdmin=user_api::isAdmin($this->orgOwner,$user['uid']);
        //判断老师
        $isTeacher=user_api::isTeacher($this->orgOwner,$user['uid']);
        if(!empty($user)){
            //$userInfo = user_api::getUser($user['uid']);
            $retMessagesNum = message_api::getUnreadInstationNum($user['uid'],$user['token']);
            $this->assign("retMessagesNum", $retMessagesNum);
        }else{
            $this->assign("retMessagesNum", 0);
        }
        $this->assign("isAdmin",$isAdmin);
        $this->assign("isTeacher",$isTeacher);
        $this->assign("user",$user);
        $subnav="";
        if(!empty($inPath[3]))$subnav=$inPath[3];
        $this->assign("subnav",$subnav);
		$this->assign("orgInfo",$this->orgInfo);
		return $this->render("site/nav.user.html");
*/
	}
	function pageFooter($inPath){
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","platform");
		$platform_url = $domain_conf->platform;
		//获取二维码
		$twoWeiMa = '';
		$orgId = !empty(self::$orgInfo->oid) ? self::$orgInfo->oid : '';
		$customer = org_api::customerServicesQqList($orgId);//获取维码及电话
		if(!empty($customer->data->weima)){
			$twoWeiMa = !empty($customer->data->weima[0]) ? $customer->data->weima[0]->type_value : '';
		}
		$this->assign('twoWeiMa',$twoWeiMa);
		$this->assign('platform_url',$platform_url);
		$this->assign("orgInfo",self::$orgInfo);
		return $this->render("site/footer.html");
	}
    function page404($inPath){
		if(strpos(self::$org->subdomain,'.')){
			$subdomain = '//'.self::$org->subdomain;
		}else{
			$subdomain = '//'.self::$org->subdomain.'.'.self::$domain;
		}

		$this->assign('subdomain',$subdomain);
		return $this->render("site/404.html");
	}
	public function pageNoAuth(){
		if(strpos(self::$org->subdomain,'.')){
			$subdomain = '//'.self::$org->subdomain;
		}else{
			$subdomain = '//'.self::$org->subdomain.'.'.self::$domain;
		}
		
		$this->assign('subdomain',$subdomain);
		return $this->render("site/noauth.html");
	}
    private function getRedirectUrl(){
        $url = '/';
        if(!empty(utility_session::get()['login_url'])){
            $url = utility_session::get()['login_url'];
            if(preg_match('/R|register/',$url)||preg_match('/L|login/',$url)){
                $url = '/';
            }
        }
        return $url;
    }
    public function pageALertLogin($inPath){
        $url = !empty($_REQUEST['url'])?$_REQUEST['url']:(!empty($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'');
        $this->assign('url',$url);
        $from = !empty($_REQUEST['from'])?$_REQUEST['from']:"";
        $this->assign('from',$from);
        return $this->render("/site/login.alert.html");
    }
	/**
	 * 机构自定义首页
	 * @author Panda <zhangtaifeng@gn100.com>
	 */
	function pageEntry($inPath){
		utility_cache::pageCache(300);
        //如果用户已登录 取出报名的课程
		
		$slideList = user_organization::getOrgSlideList(self::$orgOwner);
		$HotType = user_organization::getOrgSetHotType(self::$orgOwner);
		if($HotType->is_pro==1){
			//$logoconf = SConfig::getConfig(ROOT_CONFIG."/xiaowo.logo.conf", "logo");
			$this->assign('pro_logo',!empty(self::$orgInfo->thumb_big) ? utility_cdn::file(self::$orgInfo->thumb_big) : '');
			$this->assign("orgInfo",!empty(self::$orgInfo) ?  self::$orgInfo : '');
			return $this->render("site/wo.index.html");
		}
		$this->assign("org_hot_type",$HotType);
		$this->assign("slideList",$slideList);
        //即将直播 一周内
        //查询机构现有课程模版
        $templates=user_organization::getOrgTemplate(self::$orgOwner);
		//直接列表是否显示
		$liShow = user_organization::getOrgSetHotType(self::$orgOwner);
        $this->assign('liShow',$liShow);
		//快速导航
		$result = user_organization::getOrgOfNavList(self::$orgInfo->oid);
		$this->assign("orgNav",!empty($result->items) ? $result->items : '');
        $cidArr=array();
		$attrValues = array();
		$temp = array();
		$condition = array("status"=>1,"fk_org_resell"=>self::$orgInfo->oid);
		$salesInfo = course_resell_api::getSalesCourse($page=1,$length=0,$condition);
		$resell = array();
		if(!empty($salesInfo)){
			foreach($salesInfo as $k=>$v){
				$resell[$v->fk_course] =$v;
			}
		}
        if(!empty($templates)){
			$queryField = array(
						'first_cate'=>'fc',
						'second_cate'=>'sc',
						'third_cate'=>'tc',
						'course_type'=>'course_type',
						'attr_value_id' => 'vid',
					);
			$domain = self::$domain;
            foreach($templates as $tk=>$tv){
                if($tv->recommend==1){
                    $queryArr=array();
                    $query='';
                    foreach($tv->query_arr as $qk=>$qv){
						if($tv->query_arr->fee_type=="2"){
							$tv->query_arr->fee_type = "0,1";
						}
                        if($qk=='grade_id'&&$qv==0){continue;}
                        if($qk=='subject_id'&&$qv==0){continue;}
						if($qk == 'attr_value_id'){
							$queryArr[$qk] = str_replace('|',',',$qv);
						}else{
							$queryArr[$qk]=$qv;
						}
						if(!empty($queryField[$qk]) && $queryField[$qk] != 'vid'){
							$query.= $queryField[$qk].'='.$qv.'&';
						}
                    }
					$queryArr['fee_type'] = $tv->query_arr->fee_type;
					if(!isset($tv->query_arr->course_type)){
						$tv->query_arr->course_type= "1,2,3";
						$queryArr['course_type'] = $tv->query_arr->course_type;
					}else{
						$queryArr['course_type'] = $tv->query_arr->course_type;
					}
					if(isset($tv->query_arr->first_cate)){
						$queryArr['first_cate'] = $tv->query_arr->first_cate;
					}
					if(isset($tv->query_arr->second_cate)){
						$queryArr['second_cate'] = $tv->query_arr->second_cate;
					}
					if(isset($tv->query_arr->third_cate)){
						$queryArr['third_cate'] = $tv->query_arr->third_cate;
					}
                    $queryArr['user_id']=self::$orgOwner;
                    $templates[$tk]->query=trim($query,'&');
                    $queryArr['admin_status']=1;
                    $seekArr = array(
                        "f"=>array(
                            "tags","course_id","title","create_time",
                            "start_time","thumb_big","thumb_med",
                            "thumb_sma","course_type","user_id",
                            "public_type","fee_type","price","market_price",
                            "max_user","min_user","user_total","status",
                            "admin_status","system_status","class_id",
                            "end_time","create_time","last_updated",
                            "class", 'top','try','vv',"third_cate_name","course_attr"
                        ),
                        "q" => $queryArr,
                        "p"=>1,
                        "pl"=>$tv->row_count*4,
                    );
                    $seekRet=seek_api::seekcourse($seekArr);

                    if(!empty($seekRet->data)){
                        foreach($seekRet->data as $rsk=>$rsv){
                            $cidArr[$rsv->course_id]=$rsv->course_id;
							$rsv->diplayStatus =1;
							if(!empty($rsv->course_attr)){
								foreach($rsv->course_attr as $val){
									if(!empty($val->attr_value)){
										foreach($val->attr_value as $v){
											$temp[$rsv->course_id][$v->attr_value_id] = $v->attr_value_name;
										}
									}
								}
							}

							if(!empty($temp)){
								foreach($temp as $k=>$v){
									$attrValues[$k] = implode(",",$v);
								}
							}
                        }
						$courseData = array();
						$tb 		= array();
						if(!empty($seekRet)){
							foreach($seekRet->data as $m=>$n){
								$courseData[$n->course_id] = $n;
							}
						}
						if(!empty($tv->course_ids)){
							foreach($tv->course_arr as $tcv){
								if(!empty($courseData[$tcv])){
									$tb[]= $courseData[$tcv];
								}
							}
							$templates[$tk]->courses = $tb;
						}else{
							$templates[$tk]->courses = $seekRet->data;
						}
                    }
                }
				
                if($tv->recommend==2){
					if(!empty($tv->course_ids)){
						$courseId = $tv->course_ids;
					}else{
						$courseId = 0;
					}
                    $seekArr = array(
                        "f"=>array(
                            "tags","org_id","subdomain","is_promote","course_id","title","create_time",
                            "start_time","thumb_big","thumb_med",
                            "thumb_sma","course_type","user_id",
                            "public_type","fee_type","price","market_price",
                            "max_user","min_user","user_total","status",
                            "admin_status","system_status","class_id",
                            //"section_id",
                            "end_time","create_time","last_updated",
                            //"section",
                            "class", 'top','try','vv',"third_cate_name","course_attr"
                        ),
                        "q" => array('course_id'=>$courseId),
                        "p"=>1,
                        "pl"=>50,
                    );
                    if(!empty($tv->course_arr)){
                        foreach($tv->course_arr as $ck=>$cv){
                            $cidArr[$cv]=$cv;
                        }
                    }
                    $seekRet=seek_api::seekcourse($seekArr);
                    if(!empty($seekRet->data)){
                        //按照存的顺序取出来
                        $data1=array();
                        $data2=array();
                        foreach($seekRet->data as $sdk=>$sdv){
                            $data1[$sdv->course_id]=$sdv;
							if(strpos($sdv->subdomain,".")===false){
								$sdv->url = "//".self::$org->subdomain.".".$domain."/course.info.show/".$sdv->course_id;
							}else{
								$sdv->url = "//".self::$org->subdomain."/course.info.show/".$sdv->course_id;
							}

							$sdv->price = isset($resell[$sdv->course_id]->price_resell) ? $resell[$sdv->course_id]->price_resell : $sdv->price;
							$sdv->url = !empty($resell[$sdv->course_id]->fk_org_resell) ? $sdv->url."/".$resell[$sdv->course_id]->fk_org_resell : $sdv->url;
							$sdv->resellTips = !empty($resell[$sdv->course_id]) ? 1 : 0;
							$sdv->resellStatus	= !empty($resell[$sdv->course_id]->status) ? $resell[$sdv->course_id]->status : 5;
							$sdv->restatus	= !empty($resell[$sdv->course_id]->restatus) ? $resell[$sdv->course_id]->restatus : 5;
							if(($sdv->admin_status==1)&&($sdv->is_promote==0)&&($sdv->resellStatus>0)){
								$sdv->diplayStatus = 1;
							}elseif(($sdv->admin_status==1)&&($sdv->resellStatus>0)&&($sdv->restatus==5 ||$sdv->restatus==1)){
								$sdv->diplayStatus = 1;
							}else{
								$sdv->diplayStatus = 0;
							}
							if(!empty($sdv->course_attr)){
								foreach($sdv->course_attr as $val){
									if(!empty($val->attr_value)){
										foreach($val->attr_value as $v){
											$temp[$sdv->course_id][$v->attr_value_id] = $v->attr_value_name;
										}
									}
								}
							}
                        }

						if(!empty($temp)){
							foreach($temp as $k=>$v){
								$attrValues[$k] = implode(",",$v);
							}
						}

                        foreach($tv->course_arr as $tcv){
                            if(!empty($data1[$tcv])){
                                $data2[$tcv]=$data1[$tcv];
                            }
                        }
                        $templates[$tk]->courses=$data2;
                    }
                }
				
            }
        }else{
            $seekArr = array(
                "f"=>array(
                    "tags","course_id","title","create_time",
                    "start_time","thumb_big","thumb_med",
                    "thumb_sma","course_type","user_id",
                    "public_type","fee_type","price","market_price",
                    "max_user","min_user","user_total","status",
                    "admin_status","system_status","class_id",
                    "end_time","create_time","last_updated",
                    "class", 'top','vv','try',"third_cate_name","course_attr","org_id",
                ),
                "q" => array(
                    'admin_status'=>1,
                    'user_id'=>self::$orgOwner,
                ),
                "ob" => array(
                    'start_time'=>'desc',
                ),
                "p"=>1,
                "pl"=>12,
            );
            $seekRet=seek_api::seekcourse($seekArr);
            if(!empty($seekRet->data)){
				foreach($seekRet->data as $value){
					if(!empty($value->course_attr)){
						foreach($value->course_attr as $val){
							if(!empty($val->attr_value)){
								foreach($val->attr_value as $v){
									$temp[$value->course_id][$v->attr_value_id] = $v->attr_value_name;
								}
							}
						}
					}
					$cidArr[$value->course_id]=$value->course_id;
				}

				if(!empty($temp)){
					foreach($temp as $k=>$v){
						$attrValues[$k] = implode(",",$v);
					}
				}
            }
            $templates=array();
            $templates[0]=new stdClass;
            $templates[0]->title='全部课程';
            $templates[0]->courses=$seekRet->data;
        }
		$this->assign("templates",$templates);
        //从中间层获取排课
        if(!empty($cidArr)){
            $planArr=array(
                "f"=>array(
                    'course_id',
                    'plan_id',
                    'class_id',
                    'class_name',
                    'section_name',
                    'admin_id',
                    'admin_name',
                    'admin_real_name',
					'course_type',
                    'start_time',
                    'max_user',
                    'user_total',
                    'try',
                    'status',
                ),
                "q"=>array(
                    'course_id'=>implode(',',$cidArr),
                    'status'=>'1,2,3',
                ),
                "ob"=>array(
                    'start_time'=>'asc',
                ),
                "p"=>1,
                "pl"=>1000,
            );
            $seekPlan=seek_api::seekPlan($planArr);
            $retPlan=!empty($seekPlan->data)?$seekPlan->data:false;
            //$ret_plan1=course_api::getPlanByCids($cidArr1);
            //排课信息每个班取一个 每个课程最多显示两个
            $plan1=array();
			$planinfo = array();
            if(!empty($retPlan)){
                foreach($retPlan as $rp){
                    $plan1[$rp->course_id][$rp->class_id][$rp->status][]=$rp;
                    $planinfo[$rp->course_id][$rp->class_id][]=$rp;
                }
            }
			if(!empty($templates[1]->courses)){
				$templatesInfo = $templates[1]->courses;
			}else{
				$templatesInfo = $templates[0]->courses;
			}
			$plan = org_api::getOrgCourseProjectInfo($templatesInfo,$plan1,$planinfo);
            $this->assign("plan",$plan);
        }
		//获取明星教师
		$retStar1 = user_organization::listOrgUser(self::$orgInfo->oid,$all=0,$star=1,$page=1,$length=5);
        $arr1=array();
        //$arr2=array();
        if(!empty($retStar1)){
            $arr1=utility_tool::objToArray($retStar1);
        }
        if(!empty($arr1)){
        foreach ($arr1 as $value) {
            $teacherId[] = $value['user_id'];
        }
        if(!empty($teacherId)){
                $previewVideoArr = array();       
                $uidStr = implode(',',$teacherId);       
                $preview_video = user_api::getPreviewVideoByUid($uidStr);
                if (!empty($preview_video->result)) {                                               
                    foreach ($preview_video->result as $key => $value) {
                            if(!empty($value->uid)){
                             $previewVideoArr[$value->uid] = $value->planid;
                            }
                    }
            }
        }    
            foreach ($arr1 as $key => $value) {                       
                if(array_key_exists($value['user_id'], $previewVideoArr)){
                   $arr1[$key]['planid'] = $previewVideoArr[$value['user_id']];                   
                }else{
                    $arr1[$key]['planid'] = '';
                }
            }
        }
		$this->assign("teachers",$arr1);        
        //学习资讯(原公告列表)
		$cateId = isset($_GET['c']) ? (int)$_GET['c'] : 0;
        $noticeInfo = $this->getNoticeInfo($cateId);
		//echo "<pre>";print_r($noticeInfo);die;
        $this->assign("noticeList",$noticeInfo);
		$config = ROOT_CONFIG."/site.conf";
		$ssl_flag  = false;
		if(is_file($config)){
			$ssl= SConfig::getConfig($config,"ssl");
			if(isset($ssl->flag) && $ssl->flag){
				$ssl_flag=true;
			}
		}
		$this->assign("ssl_flag",$ssl_flag);
		$this->assign("attrValues",$attrValues);

		return $this->render("site/index.html");
	}
	/**
	 * 机构-学习资讯
	 * @params int $cateId
	 * @return $noticeList
	 */
	public function getNoticeInfo($cateId){
		$noticeList = user_api::getNoticeList(1,8,self::$orgOwner,$cateId,self::$orgInfo->oid);
        if(!empty($noticeList->data)){
            foreach($noticeList->data as $nlk=>$nlv){
                //取出图片
                preg_match('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i',$nlv->notice_content,$match);
                //过滤php、html标签
                $tmpStr=strip_tags($nlv->notice_content);
                //有图片时截取54个字，无图片时截取67个字
                if(!empty($match[2])){
					//部分图片地址是绝对路径去掉http或https不能正确显示
					//$str = str_replace(array("http:","https:")," ",$match[2]);
                    //$noticeList->data[$nlk]->thumb =$str;
					$noticeList->data[$nlk]->thumb = $match[2];
                    //$noticeList->data[$nlk]->sub_content=mb_substr($tmpStr,0,54,'utf-8').'...';
                }else{
                    $noticeList->data[$nlk]->thumb = '';
                    //$noticeList->data[$nlk]->sub_content=mb_substr($tmpStr,0,67,'utf-8').'...';
                }
                $noticeList->data[$nlk]->sub_content = $tmpStr;
                //不显示当年年份
                //获取年份
                $year = date('Y',strtotime($nlv->update_time));
                if($year == date('Y')){
                    $noticeList->data[$nlk]->update_time = date('m-d H:i',strtotime($nlv->update_time));
                }else{
                    $noticeList->data[$nlk]->update_time = date('Y-m-d H:i',strtotime($nlv->update_time));
                }
            }
			return  $noticeList;
		}else{
			return false;
		}
	}
	function pageUserInfo($inPath){
		if(!empty(self::$orgInfo->oid)){
			$orgId = self::$orgInfo->oid;
		}else{
			header('Location: https://www.'.$this->domain);
		}
        $from = !empty($_REQUEST['from'])?$_REQUEST['from']:"";
        $this->assign('from',$from);
        //首页登录框显示日期
        $month=date('n');
        $day=date('j');
        $weekarray=array("日","一","二","三","四","五","六");
        $w=date('w');
        $week='星期'.$weekarray[$w];
        $this->assign('month',$month);
        $this->assign('day',$day);
        $this->assign('week',$week);
		$user=user_api::loginedUser();
		if(!empty($user['uid'])){
			//获取成长值
			$userLevel = user_api::getUserLevel($user['uid']);
			$this->assign('userLevel',$userLevel);
			//获取签到
			$day = date('Y-m-d');
			$yesterday = date('Y-m-d',time()-3600*24);
			$signInfo = user_api::getUserSignByDay($day,$user['uid']);
			$lastSign = user_api::getLastUserSign($user['uid']);
			if(!empty($lastSign) && !empty($signInfo)){
				$userCombo = $signInfo->combo;
			}elseif(!empty($lastSign) && empty($signInfo)){
				if($lastSign->day != $yesterday ){
					$userCombo = 0;
				}elseif($lastSign->day == $yesterday ){
					$userCombo = $lastSign->combo;
				}
			}else{
				$userCombo = 0;
			}
			$this->assign('signInfo',$signInfo);
			$this->assign('userCombo',$userCombo);
			//判断是否有机构权限
			$isAdmin=user_api::isAdmin(self::$orgOwner,$user['uid']);
			$this->assign('isAdmin',$isAdmin);
			//判断是否有教师权限
			$isTeacher=user_api::isTeacher(self::$orgOwner,$user['uid']);
			$this->assign('isTeacher',$isTeacher);
			//机构身份
			if(!empty($isAdmin)){
				//课程数量查询
				$allCourse=course_api::countCourseByOwner(self::$orgOwner,$status=1);
				$this->assign('allCourse',$allCourse);
				//教师数量查询
				$allTeacher=user_organization::countTeacherByOid(self::$orgInfo->oid,$status=1);
				$this->assign('allTeacher',$allTeacher);
				//学生数量查询
				$allStudent=course_api::countStudentByOwner(self::$orgOwner,$status=1);
				$this->assign('allStudent',$allStudent);
				//昨日订单数量
				$yestodayOrder=user_organization::getOrderCountByOrgId($orgId,date('Y-m-d 00:00:00',strtotime('-1 day')),date('Y-m-d 23:59:59',strtotime('-1 day')));
				$this->assign('yestodayOrder',$yestodayOrder);
				$this->assign('org',self::$orgInfo);
			}
			//教师身份
			if(!empty($isTeacher)){
				//学生数量、课程数量查询
				$teacherStat=stat_api::getTeacherStat($user['uid']);
				if(!empty($teacherStat)){
					$countStudent=$teacherStat->student_count;
					$courseCount=$teacherStat->course_count;
					$this->assign('countStudent',$countStudent);
					$this->assign('courseCount',$courseCount);
				}
				$remainCourse = course_api::getTeacherOngoingCourseCount($user['uid']);
				$this->assign('remainCourse',$remainCourse);
				//待上课
				$params = new stdClass;
				$params->start_time = date('Y-m-d H:i:s',time());
				$params->end_time = date('Y-m-d 23:59:59',time());
				$params->status = 1;
				$params->type = 1;
				$remainPlan=course_api::getTeacherPlanCountByUid($user['uid'],$params);
				$this->assign('remainPlan',$remainPlan);
				//未备课 题库上了之后再加
			}
			//查询学习时长等
			$studentStat=stat_api::getOrgStudentStat(self::$orgOwner,$user['uid']);
			if(!empty($studentStat)){
				$studyTime=ceil(($studentStat->vt_live+$studentStat->vt_record)/3600);
				$this->assign('studyTime',$studyTime);
			}
			//查询课程数量
			$studentCourse=course_api::countStudentCourseByUid(self::$orgOwner,$user['uid'],1);
			$startTime=date('Y-m-d 00:00:00');
			$endTime=date('Y-m-d 23:59:59');
			$studentPlan=course_api::countStudentPlanByUid(self::$orgOwner,$user['uid'],1,$startTime,$endTime);
			$this->assign('studentCourse',$studentCourse);
			$this->assign('studentPlan',$studentPlan);
		}
		$this->assign("user",$user);

		return $this->render("site/index.user.html");
	}
	/**
	 * 直播模块，不能缓存
	 */
	function pageLiving($inPath){
        $userCourseList = array();
        $user=user_api::loginedUser();
		$planList = course_api::getOrgLivingList($user['uid'],self::$orgOwner,$tips=self::$orgInfo->is_pro);
		$this->assign("planList",$planList);
		return $this->render("site/index.living.html");
	}
	/*
	 *手机国际编号
	 */
	 public function pagegetInternationalCodeByInfo(){
		 $cid = !empty($_POST['cid']) ? (int)$_POST['cid'] : 0;
		 $data = array("cid"=>$cid);
		 $info = utility_valid::$mobile_country;
		 $codeResult = array();
		 if(empty($cid)){
			 //常用国家区号
			 $info = array_slice($info,0,14);
		 }else{
			 if(!empty($info)){
				 foreach($info as $k=>$v){
					 $codeResult[] = !empty($v['name_en']) ? $v['name_en'] : '';
				 }
			}
				array_multisort($codeResult,SORT_ASC,SORT_STRING,$info);
		 }
		 if(!empty($info)){
			 $res = array("status"=>100,"msg"=>$info);
		 }else{
			 $res = array("status"=>-101,"msg"=>"data is not found");
		 }

		 echo json_encode($res);
	 }

	 public function pagewoIndex(){
		 return $this->render("site/wo.index.html");
	 }
    //获取后台自定义导航
    public function pageWoNav($inPath){
        $condition =array( 'org_id'=>self::$orgInfo->oid,'status'=>0);
        $ret = utility_services::call("/customnav/custom/SelNav/",$condition);
		if(!empty($ret->items)){
			foreach($ret->items as $k=>$v){
				if(strpos($v->url,".") != false){
					$v->tips =  explode("/",explode(".",ltrim(substr($v->url,strrpos($v->url,"com/")),"/"))[0])[1];
					
				}
			}
		}
        if(!empty($ret->items)) $this->assign('wonav',$ret->items);
    }

	 public function pagewoContent(){
		$result			=	new stdClass;
		//左侧导航
		$menu 			=	$this->getOrgCateInfoAtLeftMenu();
		$Hot 			= user_organization::getOrgSetHotType(self::$orgOwner);
		$isCate			= !empty($Hot->is_cate) ? $Hot->is_cate : '2';
		$result->leftMenu = new stdClass;
		$result->code 	= 	100;
		$orgLogo		= 	!empty(self::$orgInfo->thumb_big) ? utility_cdn::file(self::$orgInfo->thumb_big) : '';
		$result->logo	=	$orgLogo;
		$result->leftMenu->data 	= !empty($menu) ? $menu : '';
		$result->leftMenu->status 	= !empty($Hot->is_cate) ? $Hot->is_cate : '2';
		$num 			= self::$orgInfo->is_pro == 1 ? 5 : 4;
		//直播列表
		$user			= user_api::loginedUser();
		$live 			= course_api::getOrgLivingList($user['uid'],self::$orgOwner,$tips=self::$orgInfo->is_pro);
		//获取banner图
		$bannerList = user_organization::getOrgSlideList(self::$orgOwner);
		if(!empty($bannerList)){
			foreach($bannerList as $a=>$b){
				$b->slide_url = utility_cdn::file($b->slide_url);
			}
			$result->banner	= $bannerList;
		}else{
			$result->banner	= "";
		}
		//获取展示类型
		//echo "<pre>";print_r(self::$org->subdomain);die;
		$HotType		= !empty($Hot->hot_type) ? $Hot->hot_type : '4';
		$isLiving		= !empty($Hot->living_show) ? $Hot->living_show : '2';
		$result->living = new stdClass;
		$result->living->status = $isLiving;
		$result->living->data 	= !empty($live) ? $live : '';
		if(!empty($HotType)){
			$result->HotType	= $HotType;
		}else{
			$result->HotType	= "";
		}
		
		//获取首页课程
		$templates		=	user_organization::getOrgTemplate(self::$orgOwner);
		//分销课程
		$condition 		= array("status"=>1,"fk_org_resell"=>self::$orgInfo->oid);
		$salesInfo 		= course_resell_api::getSalesCourse($page=1,$length=0,$condition);
		$resell 		= array();
		if(!empty($salesInfo)){
			foreach($salesInfo as $k=>$v){
				$resell[$v->fk_course] =$v;
			}
		}
		if(!empty($templates)){
			$domain 	= self::$domain; 
			$queryField = array(
									'first_cate'=>'fc',
									'second_cate'=>'sc',
									'third_cate'=>'tc',
									'course_type'=>'course_type',
									'attr_value_id' => 'vid',
									'fee_type'=>'fee_type'
								);
			$arr = array();
            foreach($templates as $tk=>$tv){
				if(!empty($tv->query_arr)){
					$query = (array)$tv->query_arr;
					if ($tv->query_arr->fee_type == "2") {
						$tv->query_arr->fee_type =  "0,1";
					}else{
						$tv->query_arr->fee_type =  $tv->query_arr->fee_type;
					}
					foreach($query as $a=>$b){
						if(!empty($queryField[$a])){
							$arr[$queryField[$a]] = $b ;
						}
					}
					$str = '';
					if(!empty($arr)){
						foreach($arr as $m=>$n){
							$str .= "&".$m."=".$n;
						}
						$tv->query= "/course.list?".$str;
					}
				}
				
				
				//自动推荐
				if (!empty($tv->order_by)&&$tv->recommend=='1') {
					$obArr 	  = explode(':', $tv->order_by);
					$orderArr = array($obArr[0] => $obArr[1]);
					if($obArr[0]=="register"){
						$orderArr = array("user_total" => $obArr[1]);
					}
					if(!empty($tv->course_ids)){
						$qcourse_id = $tv->course_ids;
						$queryArr 	= array("admin_status"=>1,"course_id"=>$qcourse_id,"user_id"=>self::$orgOwner);
					}else{
						$qArr 	 	= array("admin_status"=>1,"user_id"=>self::$orgOwner);
						$queryArr   = array_merge($qArr,(array)$tv->query_arr);
					}
					$seekArr = array(
								"f"  =>array(
									"course_id","title","subdomain","thumb_big","thumb_med",
									"thumb_sma","course_type","user_id",
									"public_type","fee_type","price","market_price",
									"max_user","min_user","user_total","status",
									"admin_status","system_status",'top','try','vv',"third_cate_name"
									),
								"q"  => $queryArr,
								"ob" => $orderArr,
								"p"  =>1,
								"pl" =>$tv->row_count*$num,
								);
					$seekRet =	seek_api::seekcourse($seekArr);
					$courseData = array();
					$tb 		= array();
					if(!empty($seekRet->data)){
							foreach($seekRet->data as $rsk=>$rsv){
								$rsv->thumb_big = !empty($rsv->thumb_big) ? utility_cdn::file($rsv->thumb_big) : '';
								$rsv->thumb_med = !empty($rsv->thumb_med) ? utility_cdn::file($rsv->thumb_med) : '';
								$rsv->thumb_sma = !empty($rsv->thumb_sma) ? utility_cdn::file($rsv->thumb_sma) : '';
								$rsv->url 		= "/course.info.show/".$rsv->course_id;
								$rsv->price		= number_format($rsv->price/100,2);
								$courseData[$rsv->course_id] = $rsv;
							}
					}
					
					$tv->thumb_left  =	!empty($tv->thumb_left) ? utility_cdn::file($tv->thumb_left) : '';
					$tv->thumb_right =	!empty($tv->thumb_right) ? utility_cdn::file($tv->thumb_right) : '';
					if(!empty($tv->course_ids)){
						foreach($tv->course_arr as $tcv){
							if(!empty($courseData[$tcv])){
								$tb[]= $courseData[$tcv];
							}
                        }
						$templates[$tk]->courses = $tb;
					}else{
						$templates[$tk]->courses = $seekRet->data;
					}
				}
				//手动推荐
				if ($tv->recommend=='2') {
					//$obArr 	  = explode(':', $tv->order_by);
					//$orderArr = array($obArr[0] => $obArr[1]);
					if(!empty($tv->course_ids)){
						$qcourse_id = $tv->course_ids;
					}
					$qArr 	 = array("admin_status"=>1,"course_id"=>$qcourse_id);
					$seekArr = array(
								"f"  =>array(
									"course_id","title","org_id","subdomain","is_promote","thumb_big","thumb_med",
									"thumb_sma","course_type","user_id",
									"public_type","fee_type","price","market_price",
									"max_user","min_user","user_total","status",
									"admin_status","system_status",'top','try','vv',"third_cate_name"
									),
								"q"  => $qArr,
								//"ob" => $orderArr,
								"p"  =>1,
								"pl" =>20,
								);
					$seekRet 	=	seek_api::seekcourse($seekArr);
					$courseInfo = array();
					if(!empty($seekRet->data)){
							foreach($seekRet->data as $rsk=>$rsv){
								$rsv->thumb_big = !empty($rsv->thumb_big) ? utility_cdn::file($rsv->thumb_big) : '';
								$rsv->thumb_med = !empty($rsv->thumb_med) ? utility_cdn::file($rsv->thumb_med) : '';
								$rsv->thumb_sma = !empty($rsv->thumb_sma) ? utility_cdn::file($rsv->thumb_sma) : '';
								//$rsv->url 		= "/course.info.show/".$rsv->course_id;
								//$rsv->price		= number_format($rsv->price/100,2);
								if(strpos($rsv->subdomain,".")===false){
									$rsv->url = "//".self::$org->subdomain.".".$domain."/course.info.show/".$rsv->course_id;
								}else{
									$rsv->url = "//".self::$org->subdomain."/course.info.show/".$rsv->course_id;
								}
								//$courseInfo[$rsv->course_id]= $rsv;
								$rsv->price = isset($resell[$rsv->course_id]->price_resell) ? number_format($resell[$rsv->course_id]->price_resell/100,2) : number_format($rsv->price/100,2);
								$rsv->url = !empty($resell[$rsv->course_id]->fk_org_resell) ? $rsv->url."/".$resell[$rsv->course_id]->fk_org_resell : $rsv->url;
								$rsv->resellTips = !empty($resell[$rsv->course_id]) ? 1 : 0;
								$resellStatus	= !empty($resell[$rsv->course_id]->status) ? $resell[$rsv->course_id]->status : 5;
								$restatus	= !empty($resell[$rsv->course_id]->restatus) ? $resell[$rsv->course_id]->restatus : 5;
								if(($rsv->admin_status==1)&&($rsv->is_promote==0)&&($resellStatus>0)){
									//$rsv->diplayStatus = 1;
									$courseInfo[$rsv->course_id]= $rsv;
								}elseif(($rsv->admin_status==1)&&($resellStatus>0)&&($restatus==5 ||$restatus==1)){
									$courseInfo[$rsv->course_id]= $rsv;
								}else{
									$rsv->diplayStatus = 0;
								}
							}

					}
					$tv->thumb_left  =	!empty($tv->thumb_left) ? utility_cdn::file($tv->thumb_left) : '';
					$tv->thumb_right =	!empty($tv->thumb_right) ? utility_cdn::file($tv->thumb_right) : '';
					$tac 			 = array();
					foreach($tv->course_arr as $v){
						if(!empty($courseInfo[$v])){
							$tac[] 	 =  $courseInfo[$v];
						}

					}
					$templates[$tk]->courses	= $tac;
				}
            }
        }else{
			$qArr 	 = array("admin_status"=>1,"user_id"=>self::$orgOwner);
			$seekArr = array(
						"f"  =>array(
							"course_id","title","subdomain","thumb_big","thumb_med",
							"thumb_sma","course_type","user_id",
							"public_type","fee_type","price","market_price",
							"max_user","min_user","user_total","status",
							"admin_status","system_status",'top','try','vv',"third_cate_name"
							),
						"q"  => $qArr,
						"p"  =>1,
						"pl" =>20,
						);
			$seekRet 			= seek_api::seekcourse($seekArr);
			$templates          = array();
			$template   		= new stdClass;
			$template->title    = "全部课程";

			if(!empty($seekRet->data)){
					foreach($seekRet->data as $rsk=>$rsv){
						$rsv->thumb_big = !empty($rsv->thumb_big) ? utility_cdn::file($rsv->thumb_big) : '';
						$rsv->thumb_med = !empty($rsv->thumb_med) ? utility_cdn::file($rsv->thumb_med) : '';
						$rsv->thumb_sma = !empty($rsv->thumb_sma) ? utility_cdn::file($rsv->thumb_sma) : '';
						$rsv->url 		= "/course.info.show/".$rsv->course_id;
						$rsv->price		= number_format($rsv->price/100,2);
					}
			}
			$template->courses  = $seekRet->data;
			$templates[] 		= $template;
		}
		if(!empty($templates)){
			$result->courses	=	$templates;
		}else{
			$result->courses	=	'';
		}
		//获取推荐教师
		$retStar1 = user_organization::listOrgUser(self::$orgInfo->oid,$all=0,$star=1,$page=1,$length=5);
        $arr1	  = array();
        if(!empty($retStar1)){
            $arr1=utility_tool::objToArray($retStar1);
        }
        if(!empty($arr1)){
			foreach ($arr1 as $value) {
				$teachersArr[] = array(
									"org_id" 		=> !empty($value['org_id']) ? $value['org_id'] : 0,
									"user_id" 		=> !empty($value['user_id']) ? $value['user_id'] : 0,
									"real_name" 	=> !empty($value['real_name']) ? $value['real_name'] : 0,
									"user_id" 		=> !empty($value['user_id']) ? $value['user_id'] : 0,
									"thumb_big" 	=> !empty($value['thumb_big']) ? utility_cdn::file($value['thumb_big']) : '',
									"thumb_med" 	=> !empty($value['thumb_med']) ? utility_cdn::file($value['thumb_med']) : '',
									"thumb_small" 	=> !empty($value['thumb_small']) ? utility_cdn::file($value['thumb_med']) : '',
									"title" 		=> !empty($value['title']) ? $value['title'] : '',
									"desc" 			=> !empty($value['desc']) ? $value['desc'] : '',
									
									);
				$teacherId[] = $value['user_id'];
			}
        }
		$previewVideoArr 	= array();  
		if(!empty($teacherId)){
                $previewVideoArr = array();       
                $uidStr = implode(',',$teacherId);       
                $preview_video = user_api::getPreviewVideoByUid($uidStr);
                if (!empty($preview_video->result)) {                                               
                    foreach ($preview_video->result as $key => $value) {
                            if(!empty($value->uid)){
                             $previewVideoArr[$value->uid] = $value->planid;
                            }
                    }
            }
			foreach ($teachersArr as $key => $value) {                       
                if(array_key_exists($value['user_id'], $previewVideoArr)){
                   $teachersArr[$key]['planid'] = $previewVideoArr[$value['user_id']];                   
                }else{
                    $teachersArr[$key]['planid'] = '';
                }
            }
        }
		//学习资讯
		$cateId = isset($_GET['c']) ? (int)$_GET['c'] : 0;
        $noticeInfo = $this->getNoticeInfo($cateId);
		$result->teachers 	= !empty($teachersArr) ? $teachersArr : '';
		$result->noticeList = !empty($noticeInfo) ? $noticeInfo : '';
		return $result;
	}
	public function pagewoTopMenu(){
		//左侧导航
		$result  		= new stdClass;
		$menu 			= org_api::woMenu();
		$result->code	= "100";
		$result->data 	= !empty($menu) ? $menu : '';
		return $result;
	}
	public function pagexiaoWoAppDownload(){
		return $this->render('org/xiaowo.app.download.html');
	}
    public function setResult($data = '', $code = 0, $msg = ''){
		$ret = new stdclass;
		$ret->result = new stdclass;
		$ret->result->code = $code;
		$ret->result->data = $data;
		$ret->result->msg = $msg;
		return $ret;
	}
    /*频道首页*/
    public function pagechannel($inPath){
		$result  		        = new stdClass;
        $result->code	        = "100";
        $banner                 = array();
        $thumbData              = array();
        $banner['fk_channel']   = !empty($inPath[3]) ? $inPath[3] : 0;
		$banner['fk_org'] 	    = !empty(self::$orgInfo->oid) ? self::$orgInfo->oid : 0;
		if(empty($inPath[3])){
			$result->code = -100;
			$result->msg  = "频道id参数缺失";
			return $result;
		}
		//获取展示类型
		$Hot 			= user_organization::getOrgSetHotType(self::$orgOwner);
		$HotType		= !empty($HotType->hot_type) ? $HotType->hot_type : '4';
		//频道页推广课
		$condition 		= array("status"=>1,"fk_org_resell"=>self::$orgInfo->oid);
		$salesInfo 		= course_resell_api::getSalesCourse($page=1,$length=0,$condition);
		$resell 		= array();
		if(!empty($salesInfo)){
			foreach($salesInfo as $k=>$v){
				$resell[$v->fk_course] =$v;
			}
		}
		if(!empty($HotType)){
			$result->HotType	= $HotType;
		}else{
			$result->HotType	= "";
		}
        //频道banner列表
		$info 				    = org_api::BannerList($banner);
        if(!empty($info)){
            foreach($info as $k => $v){
				$v->thumb = !empty($v->thumb) ? utility_cdn::file($v->thumb) : '';
                $thumbData[$v->type][] = $v;
            }
        }
        $result->channelBanner  = !empty($thumbData[1]) ? $thumbData[1] : '';
        //频道课程模块列表
        $blockCondition         = array();
        $blockCondition['fk_channel']    = !empty($inPath[3]) ? $inPath[3] : 0;
        $blockCondition['fk_user_owner'] = !empty(self::$orgOwner) ? self::$orgOwner : 0;
        $blockList               = org_api::getChannelBlockList($blockCondition);
		if(!empty($blockList)){
			$queryField = array(
									'first_cate'=>'fc',
									'second_cate'=>'sc',
									'third_cate'=>'tc',
									'course_type'=>'course_type',
									'attr_value_id' => 'vid',
								);

			$qcourse_id = '';
            foreach($blockList as $tk=>$tv){
				if(!empty($tv->course_ids)){
					$qcourse_id = $tv->course_ids;
				}
				$arr 	   = array();
				if(!empty($tv->query_arr)){
					$query = (array)$tv->query_arr;
					foreach($query as $a=>$b){
						if(!empty($queryField[$a])){
							$arr[$queryField[$a]] = $b ;
						}
					}
					$str = '';
					if(!empty($arr)){
						foreach($arr as $m=>$n){
							$str .= "&".$m."=".$n;
						}
						$tv->query= "/course.list?".$str;
					}
				}

				if(!empty($qcourse_id)&&$tv->course_ids){
					$qArr 	 = array("admin_status"=>1,"course_id"=>$qcourse_id);
					$seekArr = array(
							"f"  =>array(
								"course_id","title","subdomain","thumb_big","thumb_med",
								"thumb_sma","course_type","user_id",
								"public_type","fee_type","price","market_price",
								"max_user","min_user","user_total","status",
								"admin_status","system_status",'top','try','vv',"third_cate_name","is_promote",
								),
							"q"  => $qArr,
							"p"  =>1,
							"pl" =>20,
							);
					$seekRet =	seek_api::seekcourse($seekArr);
					if(!empty($seekRet->data)){
						$courseInfo = array();
                        foreach($seekRet->data as $rsk=>$rsv){
							if(strpos($rsv->subdomain,".")===false){
								$rsv->url = "//".self::$org->subdomain.".".$domain."/course.info.show/".$rsv->course_id;
							}else{
								$rsv->url = "//".self::$org->subdomain."/course.info.show/".$rsv->course_id;
							}
							$rsv->url     = !empty($resell[$rsv->course_id]->fk_org_resell) ? $rsv->url."/".$resell[$rsv->course_id]->fk_org_resell : $rsv->url;
							$rsv->resellTips = !empty($resell[$rsv->course_id]) ? 1 : 0;
							$resellStatus	 = !empty($resell[$rsv->course_id]->status) ? $resell[$rsv->course_id]->status : 5;
							$restatus		 = !empty($resell[$rsv->course_id]->restatus) ? $resell[$rsv->course_id]->restatus : 5;
							if(($rsv->admin_status==1)&&($rsv->is_promote==0)&&($resellStatus>0)){
								$courseInfo[$rsv->course_id]= $rsv;
							}elseif(($rsv->admin_status==1)&&($resellStatus>0)&&($restatus==5 ||$restatus==1)){
								$courseInfo[$rsv->course_id]= $rsv;
							}else{
								$rsv->diplayStatus = 0;
							}
                            $rsv->thumb_big = !empty($rsv->thumb_big) ? utility_cdn::file($rsv->thumb_big) : '';
                            $rsv->thumb_med = !empty($rsv->thumb_med) ? utility_cdn::file($rsv->thumb_med) : '';
                            $rsv->thumb_sma = !empty($rsv->thumb_sma) ? utility_cdn::file($rsv->thumb_sma) : '';
							if(!empty($rsv->price)){
								$rsv->price	= number_format($rsv->price/100,2);
							}else{
								$rsv->price = 0;
							}
                        }
						$tac 		  = array();
						if($tv->recommend == 2 ){
							foreach($tv->course_arr as $v){
								if(!empty($courseInfo[$v])){
									$tac[]   = !empty($courseInfo[$v]) ? $courseInfo[$v] : '';
								}
							}
							$blockList[$tk]->courses	= $tac;
						}else{
							$blockList[$tk]->courses	= $seekRet->data;
						}
					}
				}
				//频道推荐图
				if($tv->type==3){
					$con['fk_block']			= $tv->pk_block;
					$con['type']		    	= 2;
					$thumbInfo 					= org_api::BannerList($con);

					if(!empty($thumbInfo)){
						foreach($thumbInfo as $n){
						$n->thumb  = !empty($n->thumb) ? utility_cdn::file($n->thumb) : '';
						}
					}
					$blockList[$tk]->thumb_arr	= $thumbInfo;

				}

				$tv->thumb_left  =	!empty($tv->thumb_left) ? utility_cdn::file($tv->thumb_left) : '';
				$tv->thumb_right =	!empty($tv->thumb_right) ? utility_cdn::file($tv->thumb_right) : '';
            }
        }
        //$result->recommendThumb  = !empty($thumbData[2]) ? $thumbData[2] : '';
        $result->blockList       = !empty($blockList) ? $blockList : '';
		return $result;
	}
	public function pagechannelIndex($inPath){
		 $data 				= array();
		$data['fk_org']		= !empty(self::$orgInfo->oid) ? (int)self::$orgInfo->oid : 0;
		$data['pk_channel'] = !empty($inPath[3]) ? $inPath[3] : 0;
		$dataInfo 	= org_api::getchannelOneInfo($data);
		$this->assign("channelName",!empty($dataInfo->name) ? $dataInfo->name : 0);

		 return $this->render('site/channel.index.html');
	 }

	 public function pageWoDownload($inPath){
		$config   = SConfig::getConfig(ROOT_CONFIG."/version.conf",'default');
		$http     = '//'.self::$org->subdomain;
		$download = !empty($config->xiaowo->download->release) ? $http.$config->xiaowo->download->release : '';
		$this->assign("orgInfo",self::$orgInfo);
		$this->assign('downloadUrl', $download);
		return $this->render('wo/download.page.html');
	 }
	public function pagenav3($inPath){
                $subnav="";
          if(!empty($inPath[3]))$subnav=$inPath[3];
        $orgId = self::$orgInfo->oid;
          $orgmemberset = user_organization::getMemberSetList($orgId,1);
          $this->assign('orgmemberset',$orgmemberset);
          $this->assign("subnav",$subnav);
          $this->assign("orgInfo",self::$orgInfo);
          $this->assign("flag_nav",!empty($inPath[3]) ? $inPath[3] : 0);
          $cateId = '';
          $orgRes = user_organization::getOrgByOwner(self::$orgOwner);
          if(!empty($orgRes->scopes)){
              $cate = explode(',',$orgRes->scopes);
              if(count($cate)==1){
                $cateId = "?fc=".$cate[0];
                  $second = $this->getSecondCateList($cate[0]);
                  if(count($second)==1){
                      $secondKey = array_keys($second);
                      $cateId .= "&sc=".$secondKey[0];
                  }
              }
          }
		  
          //xiao wo logo
          //$logoconf = SConfig::getConfig(ROOT_CONFIG."/xiaowo.logo.conf", "logo");
       //$this->assign('white_logo',$logoconf->white_logo->download);
        $this->assign("cateId",$cateId);
		 return $this->render('site/nav3.html');
	}
	
	public function pageCoursedelete(){
		 return $this->render('site/Course.delete.html');
	}
	
	/*
	 *机构管理设置---获取已选分类信息
	 *@params array    $scopesArr
	 *@return array    $res    
	 */
	public function getOrgCateInfoAtLeftMenu(){
		$scopesArr		= !empty(self::$orgInfo->scopes) ? explode(",",self::$orgInfo->scopes) : '';
		$orgExist 		= org_api::getOrgExistCate(self::$orgInfo->oid,$init=array());
		$cateIdStr		= "";
		if(!empty($orgExist)){
			foreach($orgExist as $k=>$v){
				$cateIdArr[] = !empty($v->first_cate) ? $v->first_cate : 0;
				$cateIdArr[] = !empty($v->second_cate) ? $v->second_cate : 0;
				$cateIdArr[] = !empty($v->third_cate) ? $v->third_cate : 0;
			}
			$unCateId = array_unique($cateIdArr);
			$cateIdStr= !empty($unCateId) ? implode(",",$unCateId) : '';
		}
		$cateList = org_api::getCateByCateIdArr(array("cateIdStr"=>$cateIdStr));
		$res 	  = org_api::getCateTreeList($cateList,$lft=0,$rgt=0,$level=1);
		
		if(!empty($res)){
			if(!empty($res)){
				foreach($res as $key=>$val){
					foreach($val->children as $k=>$v){
						$v->second_url = "//".self::$org->subdomain."/course.list?fc=".$val->pk_cate."&sc=".$v->pk_cate;
						foreach($v->children as $kc=>$vt){
							if(!in_array($vt->pk_cate,$cateIdArr)){
								unset($v->children[$kc]);
							}
							$vt->third_url = "//".self::$org->subdomain."/course.list?fc=".$val->pk_cate."&sc=".$v->pk_cate."&tc=".$vt->pk_cate;
						}
					}
				}	
			}else{
				$info = array();
			}
			return $res;
		}
		return false;
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
