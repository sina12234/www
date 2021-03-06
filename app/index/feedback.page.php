<?php
class index_feedback extends STpl{
	var $user;
	var $domain;
	private $ques_type = array(
			 1 => '注册失败',
             2 => '无法登录',
             3 => '网页打不开',
             4 => '手机接收不到验证码',
             5 => '视频加载失败',
             6 => '报名失败',
             7 => '支付失败',
             8 => '其他',
			);
    function __construct(){
		$this->user = user_api::loginedUser();
        $domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
        $this->domain = $domain_conf->domain;
    }

	public function pageEntry($inPath){
		$this->assign('ques_type', $this->ques_type);
		if( !empty($_POST)){
			$ques_type = isset($_POST['ques_type'])?$_POST['ques_type']:0;
			$content = isset($_POST['content'])?$_POST['content']:'';
			$mobile  = isset($_POST['mobile'])?$_POST['mobile']:'';

			$params = new stdclass;
			$params->ques_type = implode(',',$ques_type);
			$params->content = $content;
			$params->mobile = $mobile;
			if( $this->user['uid']){
				$params->fk_user = $this->user['uid'];	
			}else{
				$userId = user_api::isRegister($mobile);
				if($userId){
					$params->fk_user = $userId;
				}else{
					$params->fk_user = 0;
				}	
			}
			$params->create_time = date('Y-m-d H:i:s', time());
			$add_ret = user_api::addUserFeedback($params);
			$this->assign('uid', $params->fk_user);
			return $this->render('index/feedback.suc.html');
		}	
		$this->assign('uid', $this->user['uid']);
		return $this->render("index/feedback.html");
	}

	public function pageReply($inPath){
		$data = array();	
		if($this->user['uid']){
        	$feed_data = user_api::getUserFeedbackByUid($this->user['uid']);
            if($feed_data->result->data){
            	$data = $feed_data->result->data->items;
                foreach( $data as &$v){
                	$v->ques_type = explode(',',$v->ques_type);
                }
            }
        	$this->assign('reply_data', $data);
			$this->assign('uid', $this->user['uid']);
        	$this->assign('ques_type', $this->ques_type);
 		    $this->render('index/feedback.reply.html');
		}else{
			$this->assign('uid', $this->user['uid']);
        	$this->assign('ques_type', $this->ques_type);
 		    $this->render('index/feedback.html');
		}
	}

	public function pageCheckMobile(){
		
		$mobile = isset($_POST['mobile'])?$_POST['mobile']:'';

		$regex = '/^1[3|4|5|7|8][0-9]{9}$/';
		if (!empty(trim($mobile)) && !preg_match($regex, $mobile)) {
			$result = array('code'=>-1,'msg'=>'手机格式不正确');
			
		}else{
			$result = array('code'=>0, 'msg'=>'输入正确');
		}
		return json_encode($result);

	}
}
