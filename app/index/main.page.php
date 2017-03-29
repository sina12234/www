<?php
class index_main extends STpl{
	private $domain;
	public function __construct(){
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
		$this->assign('domain', $this->domain);
	}
	public function pageHeader($inPath){
		$this->render('index/header.html');
	}

	public function pageWoFooter(){
		$this->render();//调用xiaowo footer
	}

	public function pageJump($inPath){
        $user=user_api::loginedUser();
        if(empty($inPath[3]) || empty($user)){
             $this->redirect("/index");
        }
        $checkAuth=user_api::getTeacherSpecial($inPath[3],$user['uid']);
        if(empty($checkAuth)){
             $this->redirect("/index");
        }
        $subdomain=user_organization::getSubDomainByOid($inPath[3]);
        if(!empty($subdomain)){
            header('Location: //'.$subdomain->subdomain.'.'.$this->domain.'/org');
        }else{
             $this->redirect("/index");
        }
	}
    public function pageCloseTips($inPath){
        //用户点击关闭提示条
        $user=user_api::loginedUser();
        if(!empty($user)){
            $guide=array(
                'status'=>1,
            );
            $updateUserGuide=user_api::updateUserGuide($user['uid'],1,$guide);
            return $updateUserGuide;
        }else{
            return false;
        }
    }
	public function pageTop($inPath){
		$user=user_api::loginedUser();
        if(!empty($user)){
            //控制顶部提示条开关
            $closeTips=true;
			$userGuide=user_api::getUserGuideByUid($user['uid'],1);
            //如果查不到 新建一条
            if(empty($userGuide)){
                $guideArr=array(
                    'uid'=>$user['uid'],
                    'gid'=>1,
                );
                //添加不需要返回值
                user_api::addUserGuide($guideArr);
            }
            //判断用户信息是否完整 不完整就显示提示条,如果信息不完善引导1天只出现
            $userInfo	=	user_api::getUser($user['uid']);
			$guideTime	=	!empty($userGuide->last_updated) ? $userGuide->last_updated : 0;
			$gTime		=	date("d",strtotime($guideTime));
			$Mnow		=	strtotime(date("Y-m")."-$gTime 23:59:59");
			$now		=	strtotime(date("Y-m-d H:i:s"));
			$params		=	array($userInfo->name,
							   $userInfo->gender,
							   $userInfo->birthday,
							   $userInfo->student->region_level0,
							   $userInfo->student->region_level1,
							   $userInfo->student->school_type,
							   $userInfo->profile->real_name
							);
			foreach($params as $k=>$v){
				if(empty($v)){
					if(!empty($userGuide)&&$userGuide->status==0){
						$closeTips=true;
					}else if(!empty($userGuide)&&$userGuide->status==1&&($now>$Mnow)){
						$closeTips=true;
					}else{
						$closeTips=false;
					}
					break;
				}else{
					$closeTips=false;
				}
			}
            $this->assign('closeTips', $closeTips);
        }
		$this->assign("user",$user);
		$this->render('index/top.html');
	}
	public function pageTopNav($inPath){
        $uri=!empty($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'/';
        $tmp=explode('.',$uri);
        $uri=end($tmp);
        $this->assign('uri',$uri);
        $user=user_api::loginedUser();

        //是否显示注册成功引导遮挡
        $successTips=!empty(utility_session::get()['successTips'])?true:false;
		$this->assign('successTips', $successTips);
        //显示一次就关闭
        utility_session::get()['successTips']=false;

        $oidArr=array();
        $isAdmin=false;
        //如果是机构用户，直接获取机构信息
        $org=user_organization::getOrgByUid($user['uid']);
        if(!empty($org)&&$org->status>=1){
            $oidArr[$org->oid]=$org->oid;
        }
        //查询用户是否是其他机构管理员
        $orgList=user_organization::getOrgIdsByUid($user['uid']);
        if(!empty($orgList)){
            foreach($orgList as $v){
                //role user_role都要兼容
                if(($v->role==2||$v->user_role&0x04) && $v->status>=1){
                $oidArr[$v->fk_org]=$v->fk_org;
                }
            }
        }
        if(!empty($oidArr)){
            $isAdmin=true;
            $orgProfile=user_api::getOrgProfileByOidArr($oidArr);
            if(!empty($orgProfile)){
                foreach($orgProfile as $opk=>$opv){
                    $orgProfile[$opk]->subdomain='//'.user_organization::course_domain($opv->subdomain).'/org';
                }
            }
		    $this->assign('orgList', $orgProfile);
        }
		if(!empty($inPath[3]) && $inPath[3] == 'teacher'){
			$search_name = 'teacher';
		}else{
			$search_name = 'course';
		}
		$search_field = isset($_GET['search_field'])?$_GET['search_field']:'';
//        $retMessagesNum = message_api::getUnreadInstationNum($user['uid'],$user['token']);
        $retMessagesNum = message_api::getUnreadNewsRemind($user['uid']);
        $this->assign("retMessagesNum", $retMessagesNum);
        $this->assign("user",$user);
		//var_dump($user);die;
        $this->assign("isAdmin",$isAdmin);
		$this->assign('search_field', $search_field);
		$this->assign('search_name',$search_name);
		$this->render('index/top.nav.html');
	}
	public function pageNav($inPath){
        $nav=!empty($inPath[3])?$inPath[3]:'';
		if(!empty($inPath[3]) && $inPath[3] == 'teacher'){
			$search_name = 'teacher';
		}elseif(!empty($inPath[3]) && $inPath[3] == 'organization'){
            $search_name = 'organization';
        }else{
			$search_name = 'course';
		}
		$search_field = isset($_GET['search_field'])?$_GET['search_field']:'';
		$this->getIndexCourseCategory();
		$this->assign('search_field', $search_field);
		$this->assign('search_name',$search_name);
        $this->assign('nav',$nav);
		$this->render('index/nav.html');
	}
	public function pageUserNav($inPath){
        $type=!empty($inPath[3])?$inPath[3]:'default';
        $this->assign('type',$type);
        $uri=!empty($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'/';
        $tmp=explode('.',$uri);
        $uri=end($tmp);
        $this->assign('uri',$uri);
        $user=user_api::loginedUser();
        $oidArr=array();
        $isAdmin=false;
        //如果是机构用户，直接获取机构信息
        $org=user_organization::getOrgByUid($user['uid']);
        if(!empty($org)&&($org->status !='-1' && $org->status !='0')){
            $oidArr[$org->oid]=$org->oid;
        }
        //查询用户是否是其他机构管理员
        $orgList=user_organization::getOrgIdsByUid($user['uid']);
        if(!empty($orgList)){
            foreach($orgList as $v){
                //role user_role都要兼容
				if($v->status != '-1'){
					if($v->role==2||$v->user_role&0x04){
						$oidArr[$v->fk_org]=$v->fk_org;
					}
				}
            }
        }
        if(!empty($oidArr)){
            $isAdmin=true;
            $orgProfile=user_api::getOrgProfileByOidArr($oidArr);
            if(!empty($orgProfile)){
                foreach($orgProfile as $opk=>$opv){
                    $orgProfile[$opk]->subdomain='//'.user_organization::course_domain($opv->subdomain).'/org';
                }
            }
		    $this->assign('orgList', $orgProfile);
        }
		$search_field = isset($_GET['search_field'])?$_GET['search_field']:'';
//        $retMessagesNum = message_api::getUnreadInstationNum($user['uid'],$user['token']);
        $retMessagesNum = message_api::getUnreadNewsRemind($user['uid']);
        $this->assign("retMessagesNum", $retMessagesNum);
        $this->assign("user",$user);
        $this->assign("isAdmin",$isAdmin);
		$this->assign('search_field', $search_field);
		$this->assign('search_name','course');
		$this->render('index/nav.user.html');
	}
    function pageLogin($inPath){
        if(empty(utility_session::get()['login_url'])){
            utility_session::get()['login_url']= !empty($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
        }
		if(!empty($_REQUEST['url'])){
			utility_session::get()['login_url'] = $_REQUEST['url'];
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
                    $url="/";
                    if(!empty(utility_session::get()['login_url'])){
                        $url=utility_session::get()['login_url'];
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
                    return $this->render("index/parterner.bind.html");
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
        $error1="";
        $error2="";
        $forever=false;
        if(!empty($_REQUEST['forever'])){
            $forever=true;
        }
        if(!empty($_POST['uname']) && !empty($_POST['password'])){
            $islogin = user_api::login($_POST['uname'],$_POST['password'],$forever,$ret);
            if(user_api::logined()){
                if(!empty($_REQUEST['url']) && $_REQUEST['url']!="/index/main/login" && $_REQUEST['url']!="/index/main/register"){
                    return $this->redirect($_REQUEST['url']);
                }
                if(!empty(utility_session::get()['login_url'])){
                    $url = utility_session::get()['login_url'];
                    return $this->redirect($url);
                }
                return $this->redirect("/");
            }else{
                if($ret->result->code!=-2){
                    $error1="登录失败[".$ret->result->msg."]!";
                }else{
                    $error2="登录失败[".$ret->result->msg."]!";
                }
            }
        }
        $url = !empty($_REQUEST['url'])?$_REQUEST['url']:(!empty($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'');
        $uname=!empty($_POST['uname'])?$_POST['uname']:'';
        $this->assign("url",urlencode($url));
        $this->assign("uname",$uname);
        $this->assign("error1",$error1);
        $this->assign("error2",$error2);

        return $this->render("/index/login.html");
    }
	public function pageALertLogin($inPath){
        $url = !empty($_REQUEST['url'])?$_REQUEST['url']:(!empty($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'');
        utility_session::get()['login_url']=$url;
        $this->assign('url',$url);
        return $this->render("/index/login.alert.html");
	}
	public function pageLoginAjax($inPath){
        $result=new stdClass;
        if(empty(trim($_POST['uname']))){
            $result->field="uname";
            $result->error='请输入手机号';
            $result;
        }
		$mobile	= 	trim($_POST['uname']);
		$area	= 	!empty($_POST['smsNumCode']) ? (int)$_POST['smsNumCode'] : '';
		if($_POST['smsNumCode']=='86'){
			if(utility_valid::mobile(trim($_POST['uname']))==false){
				$result->field="mobile";
				$result->error="手机号码格式不正确";
				return $result;
			}
		}else{
			$mobile	= 	"+".$area.trim($_POST['uname']);
		}

        if(empty($_POST['pass'])){
            $result->field="pass";
            $result->error='请输入密码';
            $result;
        }
        $url='/';
        if(!empty(utility_session::get()['login_url'])){
            $url=utility_session::get()['login_url'];
        }
        if(!empty($_REQUEST['forever'])){
            $forever=true;
        }else{
            $forever=false;
        }

        $islogin = user_api::login($mobile,$_POST['pass'],$forever,$ret);
        if(user_api::logined()){
            $result->url=$url;
        }else{
            $result->field="account";
            $result->error="账号或者密码错误";
        }
        return $result;
	}
    function pageLogout($inPath){
        user_api::logout();
        if(!empty($_SERVER['HTTP_REFERER'])){
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }
        return $this->redirect("/index");
    }
	public function pageFooter($inPath){

		$this->render('index/footer.html');

	}
    //显示注册页面
    public function pageRegister(){
        if(empty(utility_session::get()['login_url'])){
            utility_session::get()['login_url']= !empty($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
        }
        $logined=user_api::logined();
        if($logined){
			$url = $this->getRedirectUrl();
            return $this->redirect($url);
        }
        if(isset($_GET['pr']) && !empty($_GET['pr'])){
            $promote_id = trim($_GET['pr']);
            $this->assign('pr',$promote_id);
                if($logined){
                    $loginedUser = user_api::loginedUser();
                    log_api::insertPromoteLog($promote_id,log_api::register_page,$loginedUser['uid']);
                }else{
                    log_api::insertPromoteLog($promote_id,log_api::register_page);
                }
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
        $time=!empty(utility_session::get()['register_platform']['time'])?time()-utility_session::get()['register_platform']['time']:0;
        if($time>60||$time<=0){
            $time=60;
        }
        $this->assign("time",60-$time);
        $this->render('index/register.html');
    }
    //注册
    public function pageRegisterAjax($inPath){

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
		$_REQUEST['verify_code'] = str_replace(' ','',$_REQUEST['verify_code']);
        if(empty($_REQUEST['verify_code'])){
            $result->error="手机验证码不能为空";
            $result->field="verify_code";
            return $result;
        }
        if(empty($_REQUEST['name'])){
            $result->error="姓名不能为空";
            $result->field="name";
            return $result;
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
        if(strlen($_REQUEST['password'])<6 || strlen($_REQUEST['password'])>25){
            $result->error="密码不能少于6个，多于25个字符";
            $result->field="password";
            return $result;
        }

        if(verify_api::verifyMobile($_REQUEST['mobile'],$_REQUEST['verify_code'])==false){
			/*
			$count = !empty($_COOKIE["countCode"]) ? $_COOKIE["countCode"]+1 : 1;
			setCookie("countCode",$count,time()+60*10);
			*/
			$result->error = SLanguage::tr("验证码错误","site.login");
			$result->field="verify_code";
			return $result;
        }

        if(preg_match('/^[\x{4e00}-\x{9fa5}]+$/u',trim($_REQUEST['name']))){
			if(strlen($_REQUEST['name'])>15){
				$result->error = '中文名称不能大于5个汉字';
				$result->field='name';
				return $result;
			}
		}

		if(preg_match("/[a-zA-Z\s]+$/", str_replace(' ','',$_REQUEST['name'])))
        {
            if(strlen($_REQUEST['name'])>25)
            {
                $result->error = '英文名称不能大于25个字符';
                $result->field='name';
                return $result;
            }
        }

        if(user_api::isRegister($_REQUEST['mobile'])){
            $result->error="您的手机号已经注册了，请不要重复注册";
            $result->field='mobile';
            return $result;
        }

        //注册成为用户，并设置验证状态
        $uid = user_api::registerByMobile(trim($_REQUEST['name']),$_REQUEST['mobile'],$_REQUEST['password'],$from=0,$ret);
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
                $promote = promote_api::getPromoteByCode($promote_code);
                if(isset($promote->result->data->items)){
                    $promote = $promote->result->data->items[0];
                    $promote_id = $promote->pk_promote;
                    if($promote_id){
                        log_api::insertPromoteLog($promote_code,log_api::reg_succ_post,$uid);
                        promote_api::addPromoteUser(array('fk_user'=>$uid,'fk_promote'=>$promote_id,'create_time'=>date('Y-m-d H:i:s',time())));
                    }
                }

			}
			$isLogin = user_api::login($_REQUEST['mobile'],$_REQUEST['password'],false);
            //注册成功后进入首页显示引导
            utility_session::get()['successTips']=true;
            //发送成功短信
            verify_api::sendMobileRegisterSMS($_REQUEST['mobile'],$_REQUEST['name']);
            $result->uid=$uid;
            $result->redirect_url = $this->getRedirectUrl();
            return $result;
        }else{
            $result->error=$ret->result->msg;
            return $result;
        }
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
        //记录发送时间
        utility_session::get()['findpwd']['time']=time();
        $result->field="code";
        $result->ok = 1;
        return $result;
    }
    function pageForget1($inPath){
        if(!empty(user_api::loginedUser())){
            $this->redirect("/");
            exit;
        }
		return $this->render("/index/forget.step1.html");
    }
    function pageForget1Ajax($inPath){
        $result=new stdclass;
		$_REQUEST['code'] = str_replace(' ','',$_REQUEST['code']);
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
                $result->error="发送验证码错误,[".$ret->result->msg."]";
                $result->field="code";
                return $result;
            }
        }
        utility_session::get()['findpwd']=array("uname"=>$uname,"uid"=>$uid,'time'=>time());
        return $result;
    }
    function pageForget2($inPath){
        if(!empty(user_api::loginedUser())){
            $this->redirect("/");
            exit;
        }
        if(empty(utility_session::get()['findpwd']['uname'])){
            $this->redirect("/index.main.forget1");
            exit;
        }
        $uname = utility_session::get()['findpwd']['uname'];
        $uname{3}="*";
        $uname{4}="*";
        $uname{5}="*";
        $uname{6}="*";
        $time=!empty(utility_session::get()['findpwd']['time'])?time()-utility_session::get()['findpwd']['time']:0;
        if($time>60||$time<=0){
           $time=60;
        }
        $this->assign("uname",$uname);
        $this->assign("time",60-$time);
		return $this->render("/index/forget.step2.html");
    }
    function pageForget2Ajax($inPath){
        $result=new stdclass;
		$_REQUEST['code'] = str_replace(' ','',$_REQUEST['code']);
        if(empty(utility_session::get()['findpwd']['uname'])){
            $this->redirect("/index.main.forget1");
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
    function pageForget3($inPath){
        if(!empty(user_api::loginedUser())){
            $this->redirect("/");
            exit;
        }
        if(empty(utility_session::get()['findpwd']['uname'])){
            $this->redirect("/index.main.forget1");
            exit;
        }
        if(empty(utility_session::get()['findpwd']['checked'])){
            $this->redirect("/index.main.forget2");
            exit;
        }
		return $this->render("/index/forget.step3.html");
    }
    function pageForget3Ajax($inPath){
        $result=new stdclass;
        if(empty(utility_session::get()['findpwd']['uname']) || empty(utility_session::get()['findpwd']['checked'])){
            $this->redirect("/index.main.forget1");
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
            $this->redirect("/");
            exit;
        }
		return $this->render("/index/forget.step4.html");
    }
    public function getBlockCourseOrderInfo($blockList,$block_id){
        $data = array();
		$blockIn = !empty($blockList[$block_id]) ? $blockList[$block_id] : '';
		$data['num']= !empty($blockIn->total_count) ? $blockIn->total_count : '';
        $data['is_custom']= !empty($blockIn->is_custom) ? $blockIn->is_custom : 0;
		$data['name'] = !empty($blockIn->name) ? $blockIn->name : 0;
		if(!empty($blockIn)){
			$order_str= !empty($blockIn->order_str) ? $blockIn->order_str : '';
			$data['order_str_type'] =explode(":",$order_str);
			if(trim($data['order_str_type'][0])=="user_total"){
				$data['r_order']  = array('user_total'=>'desc');
			}elseif(trim($data['order_str_type'][0])=="remain_user"){
				$data['r_order']  = array('remain_user'=>'desc');
			}elseif(trim($data['order_str_type'][0])=="vv"){
				$data['r_order']  = array('vv'=>'desc');
			}else{
				$data['r_order']='';
			}
			if(!empty($blockIn->sort)){
				$data['sort']=$blockIn->sort;
			}
            if(isset($blockIn->is_custom) && $blockIn->is_custom==1){
                if(!empty($blockIn->course_arr)){
                    $data['query_where'] = array("course_id"=>$blockIn->course_arr,"admin_status"=>1,"org_status"=>1);
                }else{
                    $data['query_where'] = array("admin_status"=>1,"org_status"=>1);
                }
            }else{
				$data['query_where'] = array();
				if(!empty($blockIn->query_str)){
					$queryArr = explode(',',$blockIn->query_str);
					foreach($queryArr as $qo){
						$temp = explode(':',$qo);
						if($temp[0] == 'attr_value_id'){
							$data['query_where']['attr_value_id'] = str_replace('|',',',$temp[1]);
						}else{
							$data['query_where'][$temp[0]] = $temp[1];
						}
					}

				}
				$data['query_where']['admin_status'] = 1;
                $data['query_where']['org_status'] = 1;
            }
		}
        return $data;
    }
	public function platformBlockOfData($blockList,$blockArrBlockId){
		$course_info1 = $this->getBlockCourseOrderInfo($blockList,$blockArrBlockId);
		$data = array();
		if($course_info1['is_custom']>0){
			foreach($course_info1['sort'] as $v){
				$am[$v->course_id] = $v->sort_id;
			}
			$data['course_all'] = $this->getCourseList($course_info1['query_where'], $course_info1['r_order'],$course_info1['num']);

			foreach($data['course_all'] as $v){
				$v->sort = !empty($am[$v->course_id]) ? $am[$v->course_id] : '';
			}
			usort(
				$data['course_all'],
				function ($a, $b) {
					return ($a->sort <= $b->sort) ? -1 : 1;
				}
			);
		}else{
			$data['course_all'] = $this->getCourseList($course_info1['query_where'], $course_info1['r_order'],$course_info1['num']);
		}

		$data['course_type'] = !empty($course_info1['order_str_type'][0]) ? 	$course_info1['order_str_type'][0] : '';
		$data['course_name'] = !empty($course_info1['name']) ? $course_info1['name'] : '';
		return $data;

	}
	public function pageEntry($inPath){
		utility_cache::pageCache(60);

		$banner_data = index_api::getPlatformBanner(1);
		//平台获取预约排课
		$pointInfo = $this->GetplatformPoint();
		$this->assign("pointPlan",$pointInfo);
		$banner = array();
		if(!empty($banner_data->items)){
			$banner = $banner_data->items;
		}
		$r_pimary4 = array();
		$r_pimary5 = array();
		$r_pimary6 = array();
		$r_junior1 = array();
		$r_junior2 = array();
		$r_junior3 = array();
		$r_senior1 = array();
		$r_senior2 = array();
		$r_senior3 = array();
		$l_pimary0  = array();
		$l_pimary1  = array();
		$l_junior0  = array();
		$l_junior1  = array();
		$blockArr = array(1,2,3,4,5,6,7,8,9,10,11,12,15);
		$r_order  = array('vv'=>'desc');
		$block = index_api::getBlockInfo();
		$blockList = array();
		if(!empty($block)){
			foreach($block as $k=>$v){
				$blockList[$v->pk_block] = $v;
			}
		}
		//获取录播课检验区ID: 15
		$recordCourse = $this->platformBlockOfData($blockList,$blockArr[12]);
		if(isset($blockList[$blockArr[12]]->is_custom)){
			$type 		  = isset($blockList[$blockArr[12]]->is_custom) ? $blockList[$blockArr[12]]->is_custom : 0;
			$courseIdStr  = !empty($blockList[$blockArr[12]]->course_arr) ? $blockList[$blockArr[12]]->course_arr : '';
			$rescourse = $this->GetRecordingCourse($type,$courseIdStr);
			$this->assign("rescourse",!empty($rescourse) ? $rescourse : '');
			$this->assign('recordName',!empty($recordCourse['course_name']) ? $recordCourse['course_name'] : '');
		}
		//小学全部: 1
		$course_info1 = $this->platformBlockOfData($blockList,$blockArr[0]);
		$r_pimary_all = !empty($course_info1['course_all']) ? $course_info1['course_all'] : '';
		$this->assign('type_pimary_all',!empty($course_info1['course_type']) ? $course_info1['course_type'] : '');
		$this->assign('pimary_all_name',!empty($course_info1['course_name']) ? $course_info1['course_name'] : '');
		//四年级:2
		$course_info2 = $this->platformBlockOfData($blockList,$blockArr[1]);
		$r_pimary4 = !empty($course_info2['course_all']) ? $course_info2['course_all'] : '';
		$this->assign('type_pimary4',!empty($course_info2['course_type']) ? $course_info2['course_type'] : '');
		$this->assign('pimary4_name',!empty($course_info2['course_name']) ? $course_info2['course_name'] : '');
		//五年级:3
		$course_info3 = $this->platformBlockOfData($blockList,$blockArr[2]);
		$r_pimary5 = !empty($course_info3['course_all']) ? $course_info3['course_all'] : '';
		$this->assign('type_pimary5',!empty($course_info3['course_type']) ? $course_info3['course_type'] : '');
		$this->assign('pimary5_name',!empty($course_info3['course_name']) ? $course_info3['course_name'] : '');
		//六年级:4
		$course_info4 = $this->platformBlockOfData($blockList,$blockArr[3]);
		$r_pimary6 = !empty($course_info4['course_all']) ? $course_info4['course_all'] : '';
		$this->assign('type_pimary6',!empty($course_info4['course_type']) ? $course_info4['course_type'] : '');
		$this->assign('pimary6_name',!empty($course_info4['course_name']) ? $course_info4['course_name'] : '');
		//初中全部:5
		$course_info5 = $this->platformBlockOfData($blockList,$blockArr[4]);
		$r_junior_all = !empty($course_info5['course_all']) ? $course_info5['course_all'] : '';
		$this->assign('type_junior_all',!empty($course_info5['course_type']) ? $course_info5['course_type'] : '');
		$this->assign('junior_all_name',!empty($course_info5['course_name']) ? $course_info5['course_name'] : '');
		//初一 :6
		$course_info6 = $this->platformBlockOfData($blockList,$blockArr[5]);
		$r_junior1 = !empty($course_info6['course_all']) ? $course_info6['course_all'] : '';
		$this->assign('type_junior1',!empty($course_info6['course_type']) ? $course_info6['course_type'] : '');
		$this->assign('junior1_name',!empty($course_info6['course_name']) ? $course_info6['course_name'] : '');
		//初二 :7
		$course_info7 = $this->platformBlockOfData($blockList,$blockArr[6]);
		$r_junior2 = !empty($course_info7['course_all']) ? $course_info7['course_all'] : '';
		$this->assign('type_junior2',!empty($course_info7['course_type']) ? $course_info7['course_type'] : '');
		$this->assign('junior2_name',!empty($course_info7['course_name']) ? $course_info7['course_name'] : '');
		//初三 :8
		$course_info8 = $this->platformBlockOfData($blockList,$blockArr[7]);
		$r_junior3 = !empty($course_info8['course_all']) ? $course_info8['course_all'] : '';
		$this->assign('type_junior3',!empty($course_info8['course_type']) ? $course_info8['course_type'] : '');
		$this->assign('junior3_name',!empty($course_info8['course_name']) ? $course_info8['course_name'] : '');
		//高中全部 :9
		$course_info9 = $this->platformBlockOfData($blockList,$blockArr[8]);
		$r_senior_all = !empty($course_info9['course_all']) ? $course_info9['course_all'] : '';
		$this->assign('type_senior_all',!empty($course_info9['course_type']) ? $course_info9['course_type'] : '');
		$this->assign('senior_all_name',!empty($course_info9['course_name']) ? $course_info9['course_name'] : '');
		//高一 :10
		$course_info10 = $this->platformBlockOfData($blockList,$blockArr[9]);
		$r_senior1 = !empty($course_info10['course_all']) ? $course_info10['course_all'] : '';
		$this->assign('type_senior1',!empty($course_info10['course_type']) ? $course_info10['course_type'] : '');
		$this->assign('senior1_name',!empty($course_info10['course_name']) ? $course_info10['course_name'] : '');
		//高二 :11
		$course_info11 = $this->platformBlockOfData($blockList,$blockArr[10]);
		$r_senior2 = !empty($course_info11['course_all']) ? $course_info11['course_all'] : '';
		$this->assign('type_senior2',!empty($course_info11['course_type']) ? $course_info11['course_type'] : '');
		$this->assign('senior2_name',!empty($course_info11['course_name']) ? $course_info11['course_name'] : '');
		//高三 :12
		$course_info12 = $this->platformBlockOfData($blockList,$blockArr[11]);
		$r_senior3 = !empty($course_info12['course_all']) ? $course_info12['course_all'] : '';
		$this->assign('type_senior3',!empty($course_info12['course_type']) ? $course_info12['course_type'] : '');
		$this->assign('senior3_name',!empty($course_info12['course_name']) ? $course_info12['course_name'] : '');

		$p_where0  = array('second_cate'=>7, 'fee_type'=>0, 'admin_status'=>1, 'org_status'=>1);
		$l_order0  = array('user_total'=>'desc');
		$l_pimary0 = $this->getCourseList($p_where0, $l_order0,$num=8);
		$j_where0  = array('second_cate'=>8, 'fee_type'=>0, 'admin_status'=>1, 'org_status'=>1);
		$l_junior0 = $this->getCourseList($j_where0, $l_order0,$num=8);

		$p_where1  = array('second_cate'=>7, 'fee_type'=>1, 'admin_status'=>1, 'org_status'=>1);
		$l_order1  = array('user_total'=>'desc');
		$l_pimary1 = $this->getCourseList($p_where1, $l_order1,$num=8);
		$j_where1  = array('second_cate'=>8, 'fee_type'=>1, 'admin_status'=>1, 'org_status'=>1);
		$l_junior1 = $this->getCourseList($j_where1, $l_order1,$num=8);

		$all_data = array(
			$r_pimary4,$r_pimary5,$r_pimary6,$r_pimary_all,
			$r_junior1,$r_junior2,$r_junior3,$r_junior_all,
			$r_senior1,$r_senior2,$r_senior3,$r_senior_all,
			$l_pimary0,$l_junior0,$l_pimary1,$l_junior1
		);



		foreach($all_data as $ro){
			foreach($ro as &$vv){
				$vv->course_url = '//'.user_organization::course_domain($vv->subdomain).'/course.info.show/'.$vv->course_id;
			}
		}

		//获取新入驻机构
		$length = 9;
		$page   = 1;
		$new_org = user_api::getNewJoinOrg($page,$length);
		$join_org = array();
		if(!empty($new_org->items)){
			$join_org = $new_org->items;
		}
		$all_data = array(
			'r_pimary4'=>$r_pimary4,'r_pimary5'=>$r_pimary5,'r_pimary6'=>$r_pimary6,'r_pimary_all'=>$r_pimary_all,
			'r_junior1'=>$r_junior1,'r_junior2'=>$r_junior2,'r_junior3'=>$r_junior3,'r_junior_all'=>$r_junior_all,
			'r_senior1'=>$r_senior1,'r_senior2'=>$r_senior2,'r_senior3'=>$r_senior3,'r_senior_all'=>$r_senior_all,
			'l_pimary0'=>$l_pimary0,'l_junior0'=>$l_junior0,'l_pimary1'=>$l_pimary1,'l_junior1'=>$l_junior1,
		);
		$this->getIndexCourseCategory();
		$this->assign('all_data', $all_data);
		$this->assign('banner', $banner);
		$this->assign('new_org',  $join_org);

		$config = ROOT_CONFIG."/site.conf";
		$ssl_flag  = false;
		if(is_file($config)){
			$ssl= SConfig::getConfig($config,"ssl");
			if(isset($ssl->flag) && $ssl->flag){
				$ssl_flag=true;
			}
		}
		//机构推荐列表
		$orgRecommendList 	= user_organization::getOrgRecommendList();
		$domainConf 		= SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$resOrg 			= array();
		if(!empty($orgRecommendList->result->data->items)){
			foreach($orgRecommendList->result->data->items as $k=>$v){
				$v->thumb_med 		 =	utility_cdn::file($v->thumb_med);
                $v->subdomain= "//" . user_organization::course_domain($v->subdomain);
				$orgInfo[$v->pk_org] = $v;
				$orgId[$k] 			 = $v->pk_org;
			}
			$idArr			= array_unique($orgId);
			foreach($idArr as $v){
				$resOrg[] 	= $orgInfo[$v];
			}
		}
		$this->assign("ssl_flag",$ssl_flag);
		$this->assign("resOrg",$resOrg);
		$this->render("index/index.html");
	}
    //获取平台自定义导航
    public function pageGetPlatformNav($inPath){
        $condition =array( 'org_id'=>0,'status'=>0);
        $ret = utility_services::call("/customnav/custom/SelNav/",$condition);
        if(!empty($ret->items)){
            foreach($ret->items as $k=>$v){
                if(strpos($v->url,".") != false){
                    $v->tips =  explode("/",explode(".",ltrim(substr($v->url,strrpos($v->url,"com/")),"/"))['0'])['1'];
                    
                }
            }
        }
        if(!empty($ret->items)) $this->assign('wonav',$ret->items);
    }

	public function pageLiving($inPath){
		$user=user_api::loginedUser();
		//精彩回放
		$params = [
			'f' => [
				'course_id','plan_id','class_id','teacher_id','owner_id','section_id',
				'course_name','class_name','section_name','teacher_name','teacher_real_name','start_time',
				'end_time','max_user','user_total','status','try','course_type','fee_type','subdomain',
			],
			'q' => [
				'status'=>'3',
				'admin_status' => 1,
				'course_type'=> 1,
                'org_status'=>1,
			],
			'ob' => [ 'start_time'=>'desc' ],
			'p' => 1,
			'pl'=> 5
		];
		$seekPlan = seek_api::seekPlan($params);
		$planSplend = array();
		if(!empty($seekPlan->data)){
			$planSplend = $seekPlan->data;
			$plan_ids=array();
			foreach($planSplend as $val){
				$plan_ids[]=$val->plan_id;
			}
			$checkResults=array();
			if(!empty($user)){
				$checkResults = course_api::verifyPlanMulti($user['uid'],$plan_ids);
			}
			foreach($planSplend as &$val){
				$plan_id = $val->plan_id;
				$val->domain = user_organization::course_domain($val->subdomain);
				$val->plan_url = '//'.user_organization::course_domain($val->subdomain).'/course.plan.play/'.$val->plan_id;

				if(!empty($checkResults->$plan_id->ok)){
					$val->register = 1;
				}else{
					$val->register = 0;
				}
				$remain_user = $val->max_user - $val->user_total;
				if($remain_user > 0){
					$val->is_full = 0;
				}else{
					$val->is_full = 1;
				}

			}
		}

		//直播
		$planlist = $this->getCoursePlan();
		if( !empty($planlist) ){
			$today    = date('Y-m-d',time());
			$tomorrow = date('Y-m-d',strtotime('+1 day'));
			$new_plan = array();

			$plan_ids=array();
			$checkResults=array();
			foreach($planlist as $val){
				$plan_ids[]=$val->plan_id;
			}
			$checkResults=array();
			if(!empty($user)){
				$checkResults = course_api::verifyPlanMulti($user['uid'],$plan_ids);
			}
			foreach($planlist as &$po){
				$plan_id = $po->plan_id;
				$temp_date = date('Y-m-d',strtotime($po->start_time));
				$temp_hour = date('H:i',strtotime($po->start_time));
				if($temp_date == $today){
					$po->start_date = '今日 '.$temp_hour;
				}elseif($temp_date == $tomorrow){
					$po->start_date = '明日 '.$temp_hour;
				}else{
					$po->start_date = date('Y-m-d H:i',strtotime($po->start_time));
				}

				if(!empty($checkResults->$plan_id->ok)){
					$po->register = 1;
				}else{
					$po->register = 0;
				}

				$remain_user = $po->max_user - $po->user_total;
				if($remain_user > 0){
					$po->is_full = 0;
				}else{
					$po->is_full = 1;
				}
				$po->domain = user_organization::course_domain($po->subdomain);
				$po->plan_url = '//'.user_organization::course_domain($po->subdomain).'/course.plan.play/'.$po->plan_id;
			}
		}

		$this->assign('planLive', $planlist);
		$this->assign('planSplendid', $planSplend) ;
		$this->render("index/index.living.html");
	}

	public function getIndexCourseCategory(){
		$cateConf = SConfig::getConfig(ROOT_CONFIG."/course.conf","menu");
		$this->assign('cateTree',$cateConf->menu);
	}

	public function pageJoin($inPath){
		utility_cache::pageCache(3600);
		$this->render("index/join.index.html");
    }
	public function pageClient($inPath){
		utility_cache::pageCache(3600);
        $config = SConfig::getConfig(ROOT_CONFIG."/version.conf",'default');
		$download = explode(',',$config->windows->download);
		$appdownload = !empty($config->android->download->release) ? $config->android->download->release : '';

		$this->assign('down',$appdownload);
        $this->assign('host',$download[0]);
		$this->render("index/client.html");
    }

	public function pageClientPhone($inPath){
		header('Location:http://a.app.qq.com/o/simple.jsp?pkgname=com.yunke.android');
    }

	/*
	 * 获取domain url
	 * @param $ownerArr[array] 拥有者ID
	 */
	private function getDomainUrl($ownerArr){
		if(empty($ownerArr)) return array();
		$header = utility_net::isHTTPS() ? "https://" : "http://";
		$domainlist = array();
		$domainInfo = user_api::getSubdomainByUidArr($ownerArr);
		if( $domainInfo->result->code == 0 && !empty($domainInfo->result->data->items)){
			$subdomains = $domainInfo->result->data->items;
			foreach($subdomains as $so){
				$domainlist[$so->fk_user] = user_organization::course_domain($so->subdomain);
			}
		}
		return $domainlist;
	}


	public function setAjaxResult($code, $msg, $data=array()){
        return json_encode(
            array(
                'code' => $code,
                'msg'  => $msg,
                'data' => $data
            ),
            JSON_UNESCAPED_UNICODE
        );
    }

	public function getOwnerArr($data){
		$owner_arr = array();
		if(!empty($data)){
              foreach($data as $v){
                  $owner_arr[$v->user_id] = $v->user_id;
              }
         }
		return $owner_arr;

	}


	public function getCoursePlan(){
		$start_time = date('Y-m-d H:00:00', time()-3600);
		$end_time   = date('Y-m-d H:00:00', time()+3600*24*180);//半年
		$planArr = array(
        		"f"=>array(
                	'course_id',
                    'plan_id',
                    'class_id',
                    'teacher_id',
					'owner_id',
                    'section_id',
                    'course_name',
                    'class_name',
                    'section_name',
                    'teacher_name',
					'teacher_real_name',
					'fee_type',
					'try',
					'course_type',
					'subdomain',
                    'start_time',
                    'end_time',
                    'max_user',
                    'user_total',
                    'org_subname',
                    'user_total',
                    'status',
                 ),
                 "q" => array(
						'status'=>'1,2',
						'admin_status' => 1,
						'course_type' => 1,
						'start_time' => "$start_time,$end_time",
                        'org_status'=>1,
					),
                 "ob" => array(
                       'start_time'=>'asc',
                    ),
                 "p" => 1,
                 "pl" => 5,
              );
		$seek_plan=seek_api::seekPlan($planArr);
		$plan_course = array();
		$planlist = array();
		if( !empty($seek_plan->data) ){
			$planlist = $seek_plan->data;
			foreach($seek_plan->data as $pv){
				$course_id_arr[] = $pv->course_id;
			}
			$course_id_str = implode(',',array_unique($course_id_arr));
			$where = array('course_id'  => $course_id_str,'org_status'=>1);
			$order = array('start_time' => 'asc');
			$course_ret = $this->getCourseList($where,$order);
			if(!empty($course_ret)){
				foreach($course_ret as $co){
					$plan_course[$co->course_id] = $co;
				}
			}
		}
		$this->assign('plan_course', $plan_course);
		return $planlist;
	}


	public function getCourseList($where, $order,$num=''){
		$recomm_con = array(
        			"f"=>array(
                    	'course_id',
                        'title',
                        'thumb_sma',
                        'thumb_med',
                        'price',
                        'user_id',
                        'course_type',
						'try',
                        'fee_type',
                        'user_total',
                        'remain_user',
                        'start_time',
                        'subdomain',
						'org_subname',
                        'vv',
                     ),
                    "q" => $where,
                    "ob"=> $order,
                    "p"=>1,
                    "pl"=>$num,
                );
		$recomm_ret = seek_api::seekcourse($recomm_con);
		if(!empty($recomm_ret) && !empty($recomm_ret->data)){
        	return $recomm_ret->data;
		}else{
        	return array();
		}
	}

	public function pageMenu($inPath){
        $type=!empty($inPath[3])?$inPath[3]:'default';
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
        //判断教师身份 为了切换个人中心快捷链接
        $isTeacher=false;
        if($userInfo->types->teacher==true){
            $isTeacher=true;
        }

		$userLevel = 1;
		$levelRet = user_api::getUserLevel($user['uid']);
		if(!empty($levelRet)){
			$userLevel = $levelRet->fk_level;
		}
		$this->assign('userLevel',$userLevel);
        $this->assign("isTeacher",$isTeacher);
        $this->assign("userInfo",$userInfo);
        $this->assign("ipinfo",$ipinfo);
        $this->assign("province",$province);
        $this->assign("city",$city);
        $menu=index_menu::instance()->create($type);
        $this->assign('type',$type);
        $this->assign('menu',$menu);
        $this->render("index/menu.html");
	}
	public function pageprivacy(){
		$this->render("index/privacy.html");
	}
	public function page404(){
		$this->render("index/404.html");
	}
	public function pageWeiXinPay($inPath){
        if (empty($_REQUEST['uqid']) || empty($_REQUEST['backurl'])) {
            $this->redirect("/");
        }
		$backurl = $_REQUEST['backurl'];
        $unique_order_id = $_REQUEST['uqid'];

        $user      = user_api::loginedUser();
        if (empty($user)) $this->redirect($backurl);

        $order_info      = order_api::getOrderAndContent($unique_order_id);
        if (empty($order_info)) $this->redirect($backurl);

        $error     = null;
        if (!empty($_REQUEST['error'])) {
            switch ($_REQUEST['error']) {
                case "verify_error":
                    $error = array("title" => "错误", "msg" => "验证失败");
                    break;
            }
        }
        $status = "";
        if ($order_info->status == "expired") {
            //TODO ERROR Page
            $error = array("title" => "错误", "msg" => "订单已经过期");
        }
        if ($order_info->status == "success") {
            $status = "ok";
            //$error=array("title"=>"错误","msg"=>"已经支付，请不要重复支付");
        }
        if ($order_info->status == "deleted") {
            //TODO ERROR Page
            $error = array("title" => "错误", "msg" => "定单不存在");
        }
        if ($order_info->status == "fail") {
            //TODO ERROR Page
            $error = array("title" => "错误", "msg" => "支付失败，请重新购买");
        }

        $title = '';
        if ($order_info->object_type == 1) {// course
            $course_info = interface_courseApi::getCourseBasic($order_info->object_id);
            if(!empty($course_info)&&$order_info->resell_org_id>0) {
                $resell = course_resell_api::getResellCourselist(1, 1, array("cid" => $order_info->object_id, "uid" => $order_info->resell_org_id, "status" => 'n', 'sort' => ''));
                if (!empty($resell->data->items)) {
                    $resell = $resell->data->items[0];
                    if ($resell->ver == $resell->promote_ver && $resell->status == 1 && $resell->status == $resell->promote_status && $resell->price_resell > 0) {
                        $course_info['fee_type'] = 1;
                    } else {
                        $course_info['fee_type'] = 0;
                    }
                }
            }
            if (empty($course_info) || $course_info['fee_type'] == 0){
                    $this->redirect($backurl);
            }

            $title = $course_info['title'];
        } elseif ($order_info->object_type == 11) {// member
            $member_info = org_member_api::getMemberSetInfo($order_info->object_id);
            if (empty($member_info)) $this->redirect($backurl);

            $title = $member_info['title'];
        }

        $titleInfo = new stdClass;
        $titleInfo->title = $title;
		$jsApiParameters = $signature = "";
		if (empty($error) && empty($status)) {
			//公众号支付
			$sc = "http";
			if (utility_net::isHTTPS()) {
				$sc = "https";
			}
			$jsApiParameters = SJson::decode(weixin_api::getJsParameters($order_info, $titleInfo));
			$url = "$sc://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$string = "jsapi_ticket=".$jsApiParameters->jsapi_ticket."&noncestr=".$jsApiParameters->nonceStr."&timestamp=".$jsApiParameters->timeStamp."&url=$url";

			$signature = sha1($string);
		}
		$this->assign("jsApiParameters", $jsApiParameters);
		$this->assign("signature", $signature);
		$this->assign("backurl", $backurl);
		$this->render("index/weixin.pay.html");
	}
    public function pageVerifyCodeNew($inPath){
        $result=new stdclass;
		$mobile = utility_valid::getMobile($_REQUEST['mobile']);
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
        //图片验证-TODO
        if(empty($_REQUEST['verify_code_img'])){
            $result->field="verify_code_img";
            $result->error="图片验证码不能为空";
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
        if(user_api::isRegister($_REQUEST['mobile'])){
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
            //记录发送时间
            utility_session::get()['register_platform']['time']=time();
            $result->field="verify_code";
            $result->ok = 1;
            return $result;
        }else{
            $result->error="发送验证码错误";
            $result->field="verify_code";
            return $result;
        }
    }
    private function getRedirectUrl(){
        $url = '/';
        if(!empty(utility_session::get()['login_url'])){
            $url = utility_session::get()['login_url'];
            if(preg_match('/R|register/',$url)||preg_match('/L|login/',$url)){
                $url='/';
            }
        }
        return $url;
    }
    public function pagegetRegionAjax($inPath){
        $result=new stdClass;
        if(empty($_POST['rid'])){
            $result->error="获取失败！";
            return $result;
        }
        $params=array();
        $params=array(
            'parent_region_id'=>!empty($_POST['rid'])?$_POST['rid']:0,
            //'level'=>!empty($_POST['level'])?$_POST['level']:0,
        );
		if(!empty($_POST['rid']) && $_POST['rid'] <= 4 ){
			$params['level'] = 1;
		}
        $r=user_organization::getRegionList($params);
        if($r){
            $result->data=$r;
            return $result;
        }else{
            $result->error="获取失败！";
            return $result;
        }

    }
    public function pageIframeLogo(){
        // 机构基本信息
        $this->render('index/iframe.logo.html');
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
	 //获取短信动态密码
	public function pagegetLoginSmsCode($inPath){
		$result=new stdclass;
		$mobile = utility_valid::getMobile($_REQUEST['mobile']);
		if(empty($mobile)){
			$result->field="mobile";
			$result->error="手机号码不能为空";
			$result->code=-8;
			return $result;
		}
		if(utility_valid::mobile($mobile)==false){
			$result->field="mobile";
			$result->error="手机号码格式不正确";
			$result->code=-8;
			return $result;
		}
		if(empty($_REQUEST['verifyCode'])){
			$result->field="verifyCode";
			$result->error="图片验证码不能为空";
			$result->code=-8;
			return $result;
		}
		if(SCaptcha::check(strtolower(trim(str_replace(" ","",$_REQUEST['verifyCode']))))===false){
			$result->error="图片验证码错误";
			$result->field="verifyCode";
			$result->code=-8;
			return $result;
		}
		//发送
		$data = array("mobile"=>$mobile,"tagCode"=>!empty($_REQUEST['tagCode']) ? $_REQUEST['tagCode'] : 0);
		$verify = user_organization::getUserVerifyCodeLoginSms($data);
		if(!empty($verify)){
			$result->verify="mobile";
			$result->error="手机动态密码10分钟有效";
			$result->code=-8;
			return $result;
		}
		$r = verify_api::sendMobileSmsLogin($mobile,$ret);
		if($r!==false){
			$result->error="动态码已经发送到您的手机";
            utility_session::get()['register_site']['time']=time();
            $result->field="verify_code";
			$result->code=1;
			return $result;
		}else{
			$result->error="发送动态码错误,[".$ret->result->msg."]";
			$result->field="verify_code";
			$result->code=-8;
			return $result;
		}
	}

	//短信登录
	function pagemobileSmsLogin($inPath){
		$result=new stdclass;
		$mobile = utility_valid::getMobile(trim($_REQUEST['mobile']));
		$isRegister = user_api::isRegister($mobile);
		$uid = (int)$isRegister;
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
			$result->error="动态码不能为空";
			$result->field="password";
			return $result;
		}
		$data = array("mobile"=>$mobile,"code"=>trim($_REQUEST['password']),"tagCode"=>!empty($_REQUEST['tagCode']) ? $_REQUEST['tagCode'] : 0);
		$verify = user_organization::getUserVerifyCodeLoginSms($data);
		if($isRegister==false){
			$result->error="手机号或者验证码错误";
			$result->field="mobile";
			return $result;
		}
		if($verify==false){
			$result->error="手机号或者验证码错误";
			$result->field="mobile";
			return $result;
		}
		if(!empty($_REQUEST['url'])){
			utility_session::get()['login_url']=$_REQUEST['url'];
		}
		$logined=user_api::logined();
		if(!empty($_POST['mobile']) && !empty($_POST['password'])){
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

	//平台首页接口
	public function pageMainIndex($inPath){
		$content 		= new stdClass;
		$content->code	= 200;
		$banner_data 	= index_api::getPlatformBanner(1);
		$banner 		= array();
		if(!empty($banner_data->items)){
			$banner 	= $banner_data->items;
		}
		//即将开始和精彩回放
		$user			= user_api::loginedUser();
		//精彩回放
		$params 		= [
						'f' => [
							'course_id','plan_id','class_id','teacher_id','owner_id','section_id',
							'course_name','class_name','section_name','teacher_name','teacher_real_name','start_time',
							'end_time','max_user','user_total','status','try','course_type','fee_type','subdomain',
						],
						'q' => [
							'status'=>'3',
							'admin_status' => 1,
							'course_type'=> 1,
							'org_status'=>1,
						],
						'ob' => [ 'start_time'=>'desc' ],
						'p' => 1,
						'pl'=> 5
					];
		$seekPlan 	= seek_api::seekPlan($params);
		$planSplend = array();
		if(!empty($seekPlan->data)){
			$planSplend = $seekPlan->data;
			$plan_ids=array();
			foreach($planSplend as $val){
				$plan_ids[]=$val->plan_id;
			}
			$checkResults=array();
			if(!empty($user)){
				$checkResults = course_api::verifyPlanMulti($user['uid'],$plan_ids);
			}
			foreach($planSplend as &$val){
				$plan_id = $val->plan_id;
				$val->domain = user_organization::course_domain($val->subdomain);
				$val->plan_url = '//'.user_organization::course_domain($val->subdomain).'/course.plan.play/'.$val->plan_id;

				if(!empty($checkResults->$plan_id->ok)){
					$val->register = 1;
				}else{
					$val->register = 0;
				}
				$remain_user = $val->max_user - $val->user_total;
				if($remain_user > 0){
					$val->is_full = 0;
				}else{
					$val->is_full = 1;
				}

			}
		}

		//直播
		$planlist = $this->getCoursePlan();
		if( !empty($planlist) ){
			$today    = date('Y-m-d',time());
			$tomorrow = date('Y-m-d',strtotime('+1 day'));
			$new_plan = array();

			$plan_ids=array();
			$checkResults=array();
			foreach($planlist as $val){
				$plan_ids[]=$val->plan_id;
			}
			$checkResults=array();
			if(!empty($user)){
				$checkResults = course_api::verifyPlanMulti($user['uid'],$plan_ids);
			}
			foreach($planlist as &$po){
				$plan_id = $po->plan_id;
				$temp_date = date('Y-m-d',strtotime($po->start_time));
				$temp_hour = date('H:i',strtotime($po->start_time));
				if($temp_date == $today){
					$po->start_date = '今日 '.$temp_hour;
				}elseif($temp_date == $tomorrow){
					$po->start_date = '明日 '.$temp_hour;
				}else{
					$po->start_date = date('Y-m-d H:i',strtotime($po->start_time));
				}

				if(!empty($checkResults->$plan_id->ok)){
					$po->register = 1;
				}else{
					$po->register = 0;
				}

				$remain_user = $po->max_user - $po->user_total;
				if($remain_user > 0){
					$po->is_full = 0;
				}else{
					$po->is_full = 1;
				}
				$po->domain = user_organization::course_domain($po->subdomain);
				$po->plan_url = '//'.user_organization::course_domain($po->subdomain).'/course.plan.play/'.$po->plan_id;
			}
		}
		//即将开始
		$content->planLive 		= $planlist;
		//精彩回放
		$content->planSplendid  = $planSplend;
		$r_pimary4  = array();
		$r_pimary5  = array();
		$r_pimary6  = array();
		$r_junior1  = array();
		$r_junior2  = array();
		$r_junior3  = array();
		$r_senior1  = array();
		$r_senior2  = array();
		$r_senior3  = array();
		$l_pimary0  = array();
		$l_pimary1  = array();
		$l_junior0  = array();
		$l_junior1  = array();
		$blockArr 	= array(1,2,3,4,5,6,7,8,9,10,11,12);
		$r_order  	= array('vv'=>'desc');
		$block 		= index_api::getBlockInfo();
		$blockList  = array();
		if(!empty($block)){
			foreach($block as $k=>$v){
				$blockList[$v->pk_block] = $v;
			}
		}

		//小学全部: 1
		$course_info1 			  = $this->platformBlockOfData($blockList,$blockArr[0]);
		$r_pimary_all 			  = !empty($course_info1['course_all']) ? $course_info1['course_all'] : '';
		$content->type_pimary_all = !empty($course_info1['course_type']) ? $course_info1['course_type'] : '';
		$content->pimary_all_name = !empty($course_info1['course_name']) ? $course_info1['course_name'] : '';

		//四年级:2
		$course_info2 		   = $this->platformBlockOfData($blockList,$blockArr[1]);
		$r_pimary4 	  		   = !empty($course_info2['course_all']) ? $course_info2['course_all'] : '';
		$content->type_pimary4 = !empty($course_info2['course_type']) ? $course_info2['course_type'] : '';
		$content->pimary4_name = !empty($course_info2['course_name']) ? $course_info2['course_name'] : '';

		//五年级:3
		$course_info3 = $this->platformBlockOfData($blockList,$blockArr[2]);
		$r_pimary5 = !empty($course_info3['course_all']) ? $course_info3['course_all'] : '';
		$content->type_pimary5 = !empty($course_info3['course_type']) ? $course_info3['course_type'] : '';
		$content->pimary5_name = !empty($course_info3['course_name']) ? $course_info3['course_name'] : '';
		//六年级:4
		$course_info4 			  = $this->platformBlockOfData($blockList,$blockArr[3]);
		$r_pimary6 				  = !empty($course_info4['course_all']) ? $course_info4['course_all'] : '';
		$content->type_pimary6 	  = !empty($course_info4['course_type']) ? $course_info4['course_type'] : '';
		$content->pimary6_name 	  = !empty($course_info4['course_name']) ? $course_info4['course_name'] : '';
		//初中全部:5
		$course_info5 			  = $this->platformBlockOfData($blockList,$blockArr[4]);
		$r_junior_all 			  = !empty($course_info5['course_all']) ? $course_info5['course_all'] : '';
		$content->type_junior_all = !empty($course_info5['course_type']) ? $course_info5['course_type'] : '';
		$content->content 		  = !empty($course_info5['course_name']) ? $course_info5['course_name'] : '';
		//初一 :6
		$course_info6 			= $this->platformBlockOfData($blockList,$blockArr[5]);
		$r_junior1 				= !empty($course_info6['course_all']) ? $course_info6['course_all'] : '';
		$content->type_junior1  = !empty($course_info6['course_type']) ? $course_info6['course_type'] : '';
		$content->junior1_name  = !empty($course_info6['course_name']) ? $course_info6['course_name'] : '';
		//初二 :7
		$course_info7 			= $this->platformBlockOfData($blockList,$blockArr[6]);
		$r_junior2 				= !empty($course_info7['course_all']) ? $course_info7['course_all'] : '';
		$content->type_junior2  = !empty($course_info7['course_type']) ? $course_info7['course_type'] : '';
		$content->junior2_name  = !empty($course_info7['course_name']) ? $course_info7['course_name'] : '';
		//初三 :8
		$course_info8 			= $this->platformBlockOfData($blockList,$blockArr[7]);
		$r_junior3 				= !empty($course_info8['course_all']) ? $course_info8['course_all'] : '';
		$content->type_junior3  = !empty($course_info8['course_type']) ? $course_info8['course_type'] : '';
		$content->junior3_name  = !empty($course_info8['course_name']) ? $course_info8['course_name'] : '';
		//高中全部 :9
		$course_info9 			  = $this->platformBlockOfData($blockList,$blockArr[8]);
		$r_senior_all 			  = !empty($course_info9['course_all']) ? $course_info9['course_all'] : '';
		$content->type_senior_all = !empty($course_info9['course_type']) ? $course_info9['course_type'] : '';
		$content->senior_all_name = !empty($course_info9['course_name']) ? $course_info9['course_name'] : '';
		//高一 :10
		$course_info10 = $this->platformBlockOfData($blockList,$blockArr[9]);
		$r_senior1 = !empty($course_info10['course_all']) ? $course_info10['course_all'] : '';
		$content->type_senior1 = !empty($course_info10['course_type']) ? $course_info10['course_type'] : '';
		$content->senior1_name = !empty($course_info10['course_name']) ? $course_info10['course_name'] : '';
		//高二 :11
		$course_info11 = $this->platformBlockOfData($blockList,$blockArr[10]);
		$r_senior2 				= !empty($course_info11['course_all']) ? $course_info11['course_all'] : '';
		$content->type_senior2 	= !empty($course_info11['course_type']) ? $course_info11['course_type'] : '';
		$content->senior2_name 	= !empty($course_info11['course_name']) ? $course_info11['course_name'] : '';
		//高三 :12
		$course_info12 			= $this->platformBlockOfData($blockList,$blockArr[11]);
		$r_senior3 				= !empty($course_info12['course_all']) ? $course_info12['course_all'] : '';
		$content->type_senior3  = !empty($course_info12['course_type']) ? $course_info12['course_type'] : '';
		$content->senior3_name  = !empty($course_info12['course_name']) ? $course_info12['course_name'] : '';
		$p_where0  = array('second_cate'=>7, 'fee_type'=>0, 'admin_status'=>1, 'org_status'=>1);
		$l_order0  = array('user_total'=>'desc');
		$l_pimary0 = $this->getCourseList($p_where0, $l_order0,$num=8);
		$j_where0  = array('second_cate'=>8, 'fee_type'=>0, 'admin_status'=>1, 'org_status'=>1);
		$l_junior0 = $this->getCourseList($j_where0, $l_order0,$num=8);

		$p_where1  = array('second_cate'=>7, 'fee_type'=>1, 'admin_status'=>1, 'org_status'=>1);
		$l_order1  = array('user_total'=>'desc');
		$l_pimary1 = $this->getCourseList($p_where1, $l_order1,$num=8);
		$j_where1  = array('second_cate'=>8, 'fee_type'=>1, 'admin_status'=>1, 'org_status'=>1);
		$l_junior1 = $this->getCourseList($j_where1, $l_order1,$num=8);

		$all_data  = array(
			$r_pimary4,$r_pimary5,$r_pimary6,$r_pimary_all,
			$r_junior1,$r_junior2,$r_junior3,$r_junior_all,
			$r_senior1,$r_senior2,$r_senior3,$r_senior_all,
			$l_pimary0,$l_junior0,$l_pimary1,$l_junior1
		);

		foreach($all_data as $ro){
			foreach($ro as &$vv){
				$vv->course_url = '//'.user_organization::course_domain($vv->subdomain).'/course.info.show/'.$vv->course_id;
				$vv->price      = $vv->price/100;
				$vv->thumb_sma	= utility_cdn::file($vv->thumb_sma);
				$vv->thumb_med	= utility_cdn::file($vv->thumb_med);
			}
		}


		$all_data 			= array(
			'r_pimary4'=>$r_pimary4,'r_pimary5'=>$r_pimary5,'r_pimary6'=>$r_pimary6,'r_pimary_all'=>$r_pimary_all,
			'r_junior1'=>$r_junior1,'r_junior2'=>$r_junior2,'r_junior3'=>$r_junior3,'r_junior_all'=>$r_junior_all,
			'r_senior1'=>$r_senior1,'r_senior2'=>$r_senior2,'r_senior3'=>$r_senior3,'r_senior_all'=>$r_senior_all,
			'l_pimary0'=>$l_pimary0,'l_junior0'=>$l_junior0,'l_pimary1'=>$l_pimary1,'l_junior1'=>$l_junior1,
		);
		$content->all_data 	= $all_data;
		$content->banner  	= $banner;

		$config 			= ROOT_CONFIG."/site.conf";
		$ssl_flag  			= false;
		if(is_file($config)){
			$ssl			= SConfig::getConfig($config,"ssl");
			if(isset($ssl->flag) && $ssl->flag){
				$ssl_flag	= true;
			}
		}
		//机构推荐列表
		$orgRecommendList 	= user_organization::getOrgRecommendList();
		$domainConf 		= SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$resOrg 			= array();
		if(!empty($orgRecommendList->result->data->items)){
			foreach($orgRecommendList->result->data->items as $k=>$v){
				$v->thumb_med 		 =	utility_cdn::file($v->thumb_med);
                $v->subdomain= "//" . user_organization::course_domain($v->subdomain);
				$orgInfo[$v->pk_org] = $v;
				$orgId[$k] 			 = $v->pk_org;
			}
			$idArr			= array_unique($orgId);
			foreach($idArr as $v){
				$resOrg[] 	= $orgInfo[$v];
			}
		}
		$content->ssl_flag  = $ssl_flag;
		$content->resOrg    = $resOrg;
		return $content;
	}
	public function pageuserMenuIsLogin($inPath){
		$result 		= new stdClass;
        /*$type			= !empty($inPath[3])?$inPath[3]:'default';
        $this->assign('type',$type);
        $uri			= !empty($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'/';
        $tmp			= explode('.',$uri);
        $uri			= end($tmp);
        $this->assign('uri',$uri);*/
        $user			= user_api::loginedUser();
        $oidArr			= array();
        $isAdmin		= false;
        //如果是机构用户，直接获取机构信息
        $org			= user_organization::getOrgByUid($user['uid']);
        if(!empty($org)&&($org->status !='-1' && $org->status !='0')){
            $oidArr[$org->oid]=$org->oid;
        }
        //查询用户是否是其他机构管理员
        $orgList		= user_organization::getOrgIdsByUid($user['uid']);
        if(!empty($orgList)){
            foreach($orgList as $v){
                //role user_role都要兼容
				if($v->status != '-1'){
					if($v->role==2||$v->user_role&0x04){
						$oidArr[$v->fk_org]=$v->fk_org;
					}
				}
            }
        }
        if(!empty($oidArr)){
            $isAdmin	= true;
            $orgProfile = user_api::getOrgProfileByOidArr($oidArr);
            if(!empty($orgProfile)){
                foreach($orgProfile as $opk=>$opv){
                    $orgProfile[$opk]->subdomain='//'.user_organization::course_domain($opv->subdomain).'/org';
                }
            }
		    $this->assign('orgList', $orgProfile);
			$result->orgList = $orgProfile;
        }
		$search_field 	= isset($_GET['search_field'])?$_GET['search_field']:'';
//        $retMessagesNum = message_api::getUnreadInstationNum($user['uid'],$user['token']);
        $retMessagesNum = message_api::getUnreadNewsRemind($user['uid']);
        /*$this->assign("retMessagesNum", $retMessagesNum);
        $this->assign("user",$user);
        $this->assign("isAdmin",$isAdmin);
		$this->assign('search_field', $search_field);
		$this->assign('search_name','course');
		$this->render('index/nav.user.html');*/
		$result->retMessagesNum = $retMessagesNum;
		$result->user			= $user;
		$result->isAdmin 		= $isAdmin;
		return $result;
	}

	public function pageNewindex($inPath){
		utility_cache::pageCache(3600);
		$this->render("index/index2.html");
    }
	public function pageleftMenuCate($inPath){
		$result 			= new stdClass;
		$result->code 		= 200;
        $cateConf 			= SConfig::getConfig(ROOT_CONFIG."/course.conf","menu");
		$result->cate_tree = !empty($cateConf->menu) ? $cateConf->menu : '';
		return $result;
	}
	/*
	 *平台首页-----查询显示预约排课,
	 *@return array $result 
	 */
	 public function GetplatformPoint(){
		$user = user_api::loginedUser();
		//如果platform表中有数据,则是手动设置,没有数据会默认给出排课
		$pointPlanIdArr = [];
		$planArrList = index_api::GetPlatformSetPlan();
		if(!empty($planArrList)){
			foreach($planArrList as $a=>$b){
				$pointPlanIdArr[] = !empty($b->fk_plan) ? $b->fk_plan : '';
			}
			
		}
		 $point = course_api::GetplatformPoint(array("status" => 1,"fk_user"=>$user['uid']));
		 if(!empty($point)){
			 foreach($point as $k=>$v){
				 $planArr[$v->fk_plan] = $v;
			 }
		 }
		 //取最近5个课时
		 $start_time = date("Y-m-d")." 00:00:00";
		 $end_time   = date('Y-m-d 23:59:59',strtotime('+365 days'));
		 $params     = [];
		 $params = [
			'f' => [
				'course_id','plan_id','class_id','teacher_id','owner_id','section_id',
				'course_name','class_name','section_name','teacher_name','teacher_real_name','start_time',
				'end_time','max_user','user_total','status','try','course_type','fee_type','subdomain','course_thumb_med',
			],
			'q' => [
				'admin_status' => 1,
				'course_type'  => 1,
                //'start_time'   => "$start_time,$end_time" ,
			],
			'ob' => [ 'start_time'=>'asc' ],
			'p' => 1,
			'pl'=> 5
		];
		if(empty($planArrList)){
			$params['q']['start_time'] = "$start_time,$end_time";
		}
		if(!empty($planArrList)){
			$params['q']['plan_id'] = implode(",",$pointPlanIdArr);
		}
		$seekPlan = seek_api::seekPlan($params);
		$result   = array();
		if(!empty($seekPlan->data)){
			foreach($seekPlan->data as $m=>$n){
				$seekData[$n->plan_id] = $n;
			}
			$result = $seekData;
			$planids=array();
			foreach($result as $key=>$val){
				$val->is_point   = isset($planArr[$val->plan_id]->fk_plan) ? 1 : 0;
				$val->fk_user   = !empty($user['uid']) ? $user['uid'] : 0;
				if(date('d',strtotime($val->start_time)) == date('d')){
					$val->start_time = "今日 ".date("H:i",strtotime($val->start_time));
				}elseif(date('d',strtotime($val->start_time)) ==date("d",strtotime('+1 days'))){                  
					$val->start_time = "明日 ".date("H:i",strtotime($val->start_time));
				}else{
					$val->start_time = date("Y-m-d H:i",strtotime($val->start_time));
				} 
			}
		}
		return $result;
	 }
	 /*
	 *平台首页-----增加预约排课
	 *@params int $uid  
	 */
	 public function pageAddPointPlan(){
		 $user = user_api::loginedUser();
		 $data = array();
		 $planid = '';
		 if(empty($user['uid'])){
			 return index_api::setResult('', $code = -100, $msg = "预约失败刷新重试");
		 }
		 $data = array(
						"course_id" => !empty($_POST['course_id']) ? $_POST['course_id'] : 0,
						"plan_id"   => !empty($_POST['plan_id']) ? $_POST['plan_id'] : 0,
						"fk_user"   => $user['uid'],
						);
		$getInfo = index_api::getPointPlanOneInfoById($data);
		if(!empty($getInfo)){
			return index_api::setResult('', $code = -100, $msg = "已经预约");
		}else{
			$planid = index_api::AddPointPlan($data);
		}
		if(!empty($planid)){
			return index_api::setResult($planid, $code = 200, $msg = "预约成功");
		}else{
			return index_api::setResult('', $code = -100, $msg = "预约失败");
		}
	 }
	 
	 /*
	 *平台首页-----免费课检验区
	 *@params int $uid  
	 *@params int $type  
	 *@return object $result  
	 */
	 public function GetRecordingCourse($type,$courseIdStr){
		 $fields = [
			"course_id","title","create_time","start_time","thumb_big", "thumb_med","thumb_sma","course_type","user_id","public_type", "status","admin_status", "system_status","create_time","last_updated","class", "try",
		];
		$query = [];
		if($type == 1){
			$query['course_id']    = $courseIdStr;
			$query['admin_status'] = 1;
		}elseif($type == 0){
			$query = [	
					'course_type'    => 2,
					'fee_type'       => 0,
					'admin_status'   => 1,
					];
		}
		$params = [
			"f" => $fields,
			"q" => $query,
			"p" => 1,
			"pl"=> 10,
			"ob"=> ["start_time" => "desc"]
		];

		$resCourse = seek_api::seekcourse($params);
		if(!empty($resCourse->data)){
			foreach($resCourse->data as $k=>$v){
				$courseIdArr[] = $v->course_id;
			}
			$courseIds = implode(",",$courseIdArr);
		}
		$info = course_api::getPlanListByCourseIdArr(array("courseId" => $courseIdArr));
		if(!empty($info)){
			foreach($info as $k=>$v){
				$arr[$v->course_id][] = $v;
				$courseIdData[$v->course_id]['course_id'] = !empty($v->course_id) ? $v->course_id : 0;
				$courseIdData[$v->course_id]['plan_id'] = !empty($v->plan_id) ? $v->plan_id : 0;
				$courseIdData[$v->course_id]['user_id'] = !empty($v->user_id) ? $v->user_id : 0;
				$courseIdArrRes[] = !empty($v->course_id) ? $v->course_id : 0;
			}
			foreach($arr as $m=>$n){
				$coursePlanArr[$m] = count($n);
			}
		}
		$condi = implode(",",array_unique($courseIdArrRes));
		$params = [
			'f' => [
					'course_id','plan_id','teacher_id','teacher_real_name',
					],
			'q' => [
				'admin_status' => 1,
				'course_id'  => $condi,
			],
			'ob' => [ 'start_time'=>'asc' ],
			'p' => 1,
			'pl'=> 1000
		];
		$seekPlan = seek_api::seekPlan($params);
		if(!empty($seekPlan->data)){
			$planArr = $seekPlan->data;
			foreach($planArr as $x=>$y){
				$planInfo[$y->course_id] = $y;
			}
		}
		$result = array();
		if(!empty($resCourse->data)){
			foreach($resCourse->data as $key=>$val){
				$val->planCount 		= !empty($coursePlanArr[$val->course_id]) ? $coursePlanArr[$val->course_id] : 0;
				$val->teacher_real_name = !empty($planInfo[$val->course_id]->teacher_real_name) ? $planInfo[$val->course_id]->teacher_real_name : '';
				$courseRes[$val->course_id] = $val;
			}
			$result = $courseRes;
			if($type == 1){
				$idArr = explode(",",$courseIdStr);
				foreach($idArr as $km=>$kn){
					
					$platformCourse[] = !empty($courseRes[$kn]) ? $courseRes[$kn] : '';
				}
				$result = $platformCourse;
			}
		}
		return $result;
	 }
}
