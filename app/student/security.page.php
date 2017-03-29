<?php
class student_security extends STpl{

	var $user;
    function __construct(){
		$this->user = user_api::loginedUser();
        if(empty($this->user)){
			if(!empty($_SERVER['REQUEST_URI'])){
            	$this->redirect("/site.main.login?url=".$_SERVER['REQUEST_URI']);
            }else{
                $this->redirect("/site.main.login");
            }
        }
    }

	public function pagePassword($inPath){
		

		if(!empty($_POST)){
			$old_password = isset($_POST['old_password'])?$_POST['old_password']:'';
			$new_password = isset($_POST['new_password'])?$_POST['new_password']:'';
			$re_password  = isset($_POST['re_password'])?$_POST['re_password']:'';
			$ret = new stdclass;
			$ret->result = new stdclass;
			
			if( empty($old_password) ){
				$ret->result->code = -1;
				$ret->result->msg  = '请输入旧密码！';
				return json_encode($ret);	
			}
			if( empty($new_password)){
				$ret->result->code = -2;
				$ret->result->msg  = '请输入新密码！';
				return json_encode($ret);	
			}
			if( empty($re_password)){
				$ret->result->code = -3;
				$ret->result->msg  = '请确认新密码！';
				return json_encode($ret);	
			}
			/*if(!preg_match( '/^[\\x20-\\x7e]+$/' ,$new_password)){
				$ret->result->code = -2;
				$ret->result->msg  = "密码不能为中文或者特殊字符";
				return json_encode($ret);	
			}*/		
			if(strlen($new_password)<6 || strlen($new_password)>16){
				$ret->result->code = -2;
				$ret->result->msg  = "密码不能少于6个，多于16个字符";
				return json_encode($ret);	
			}
			if( $new_password != $re_password ){
				$ret->result->code = -3;
                $ret->result->msg="确认密码不正确，请重新输入";
                return json_encode($ret); 
			}

			$uid= $this->user['uid'];
			if(user_api::verifyByUid($uid,$old_password)){
				$r = user_api::updatePassword($uid,$new_password);
				if($r===false){
					$ret->result->code = -2;
					$ret->result->msg  = '新密码长度不合规范';
					return json_encode($ret);
				}else{
					$ret->result->code = 0;
					$ret->result->msg  = '保存成功!';
					return json_encode($ret);
				}

			}else{
				$ret->result->code = -1;
				$ret->result->msg  = '旧密码输入不正确！';
				return json_encode($ret);
			}
		}
		$this->render('/student/info.password.html');
	}
	
	
	public function pageVerifyPsd($inPath){

		$old_password = isset($_POST['old_password'])?$_POST['old_password']:'';
		if( !empty($old_password) ){
			$ret = new stdclass;
			$ret->result = new stdclass;
			$uid= $this->user['uid'];
			if(user_api::verifyByUid($uid,$old_password)){
				$ret->result->code = 0;
                $ret->result->msg  = SLanguage::tr('输入正确','site.user').'!';
                return json_encode($ret);
			}else{
				$ret->result->code = -1;
                $ret->result->msg  = SLanguage::tr('旧密码输入不正确','site.user').'!';
                return json_encode($ret);
			}	
		}else{
			$ret->result->code = -1;
            $ret->result->msg  = SLanguage::tr('请输入旧密码','site.user').'!';
            return json_encode($ret);
		}
	}






}
