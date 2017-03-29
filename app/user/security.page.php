<?php
class user_security extends STpl{
	var $user;
	function __construct(){
		//如果没有登陆到登陆界面
		$this->user = user_api::loginedUser();
		if(empty($this->user)){
			$this->redirect("/site.main.login");
		}
	}
	public function pageEntry($inPath){
		return $this->render("user/info.security.html");
	}
	public function pagePassword($inPath){
		if(!empty($_POST)){
			$error="";
			if(empty($_REQUEST['password'])){$error="密码不能为空";}
			elseif(empty($_REQUEST['password2'])){$error="新密码不能为空";}
			elseif($_REQUEST['password3']!=$_REQUEST['password2']){$error="新密码不一致";}
			else{
				$uid= $this->user['uid'];
				if(user_api::verifyByUid($uid,$_REQUEST['password'])){
					$r = user_api::updatePassword($uid,$_REQUEST['password2'],$ret);
					if($r===false){
						$error="密码长度不合规范";
					}else{
						$error="修改成功";
						$_REQUEST['password'] = $_REQUEST['password2'] = $_REQUEST['password3'] = "";
					}

				}else{
					$error="密码不正确";
				}
			}

			$this->assign("password", @$_REQUEST['password']);
			$this->assign("password2",@$_REQUEST['password2']);
			$this->assign("password3",@$_REQUEST['password3']);
			$this->assign("error",$error);
		}
		return $this->render("user/info.password.html");
	}
}
