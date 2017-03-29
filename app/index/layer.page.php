<?php
class index_layer extends STpl{
	
    public function pageUserLogin($inPath){
        $cid = !empty($inPath[3]) ? (int)($inPath[3]) : 0;
        $classId = !empty($inPath[4]) ? (int)($inPath[4]) : 0;
        $num = !empty($inPath[5]) ? (int)($inPath[5]) : 0;
        $source = !empty($inPath[6]) ? trim($inPath[6]) : 0;
		$domain = !empty($_GET['domain']) ? trim($_GET['domain']) : '';
		$planId = !empty($inPath[7]) ? (int)($inPath[7]) : 0;
		
        $this->assign('cid', $cid);
        $this->assign('num', $num);
        $this->assign('source', $source);
        $this->assign('classId', $classId);
		$this->assign('planId', $planId);
		 $this->assign('host_domain', $domain);
        $this->render("index/layer.user.login.html");
    }

    // todo
    public function pageLoginAjax($inPath){
        $cid = !empty($inPath[3]) ? (int)($inPath[3]) : 0;
        $classId = !empty($inPath[4]) ? (int)($inPath[4]) : 0;
        $num = !empty($inPath[5]) ? (int)($inPath[5]) : 0;
        $source = !empty($inPath[6]) ? trim($inPath[6]) : 0;
        if(empty($_REQUEST['uname'])){
            $this->_setMsg('请输入账号', 1);
        }
        if(empty($_REQUEST['password'])){
            $this->_setMsg('请输入密码', 2);
        }

        if(utility_valid::mobile($_REQUEST['uname']) === false){
            $this->_setMsg('手机号码格式不正确', 3);
        }

        if(user_api::isRegister($_REQUEST['uname']) === false){
            $this->_setMsg('该手机号未注册', 4);
        }
        $forever=false;
        if(!empty($_REQUEST['forever'])){
            $forever=true;
        }
        $ret = user_api::login($_REQUEST['uname'],$_REQUEST['password'],$forever);
        $uid = !empty(utility_session::get()['user']['uid']) ? utility_session::get()['user']['uid'] : 0;
        if(user_api::logined() && $uid){
            if ($source === 'reg') {
                if ($cid && $classId && $num =1) {
					$regInfo = course_api::checkIsRegistration($uid, $cid);
					if(!empty($regInfo)){
						$this->_setMsg('您已报名',1);
					}else{
						$regInfo = $this->_addRegistration($cid, $classId);
						if ($regInfo === true) {
							$this->_setMsg('success');
						} elseif ($regInfo === -2){
							$this->_setMsg('请先支付', -2);
						} elseif ($regInfo === -3){
							$this->_setMsg('报名已满', -3);
						} else {
							$this->_setMsg('登录之后报名失败', -4);
						}
					}
                } elseif ($num>1) {
                    $this->_setMsg('多个班级未登录报名直接跳转', -3);
                } else {
                    $this->_setMsg('缺少班级和课程编号', -4);
                }
            }

            if ($source === 'like') {
                $this->_addFav($uid, $cid) && $this->_setMsg('success');
                $this->_setMsg('收藏课程失败', -5);
            }

            $this->_setMsg('非法登录', 7);
        }else{
            $this->_setMsg('密码错误', 5);
        }

        $this->_setMsg('登录失败', 6);
    }

    private function _setMsg($msg, $code=0){
        exit(json_encode(['code'=>$code, 'message'=>$msg]));
    }

    private function _addRegistration($cid, $classId){
        $user     = user_api::loginedUser();
        if (empty($user)) {
            SLog::write("暂未登录");
            return false;
        }

        $courseInfo = course_api::getCourseone($cid);
        if (empty($courseInfo)) {
            SLog::write("登录报名的时候未查到该课程[{$cid}]信息");
            return false;
        }

        if ($courseInfo->fee_type == 1) {
            SLog::write("收费课程需要先支付");
            return -2;
        }

        $data = array(
            "class_id"   => $classId,
            "course_id"  => $cid,
            "uid"        => $user['uid'],
            "user_owner" => $courseInfo->user_id,
            "status"     => 1,
        );
		$classInfo = course_api::getClass($classId);
		if(!empty($classInfo) && $classInfo->user_total >= $classInfo->max_user){
			SLog::write("报名已满");
            return -3;
		}
        return course_api::addregistration($data);
    }

    private function _addFav($uid, $cid){
        $checkFav = user_api::listfav([
                'cid' => $cid,
                'uid' => $uid
        ]);

        if (!empty($checkFav)) return false;

        return user_api::addfav([
                'user_id' => $uid,
                'course_id' => $cid
        ]);
    }

    private function _action(callable $func, $param){
        if(!is_callable($func, false, $callable_name)){
            SLog::fatal("method does not found,param[%s]", var_export(func_get_args(), 1));
            return false;
        }

        return call_user_func_array($func, $param);
    }

}
