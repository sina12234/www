<?php
class about_main extends STpl{
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
	private static $org_info;
	private static $orgOwner;
	function __construct(){

		if(empty(self::$org_info)){
			$org=user_organization::subdomain();
			if(!empty($org)){
				self::$orgOwner = $org->userId; //机构所有者id 以后会根据域名而列取
			}else{
				self::$orgOwner = 2; //机构所有者id 以后会根据域名而列取
			}
			self::$org_info = user_organization::getOrgByUid(self::$orgOwner);
			$orgInfo 		= self::$org_info;
			$this->assign("is_pro",!empty($orgInfo->is_pro) ? $orgInfo->is_pro : 0);
		}
		$this->assign("data",self::$org_info);

	}
	function pageEntry($inPath){
		utility_cache::pageCache(600);
		$this->assign("org_info",self::$org_info);
        $left=!empty($inPath[3])?$inPath[3]:'';
		$this->assign("left",$left);
		return $this->render("about/about.html");
	}
	function pageContact($inPath){
		utility_cache::pageCache(600);
		$org = user_organization::orgAboutProfileInfo(self::$orgOwner);
		$org->oid = !empty($org->fk_org) ? $org->fk_org : 0;
		$this->assign("org_info",$org);
        $left=!empty($inPath[2])?$inPath[2]:'';
		$this->assign("left",$left);
		return $this->render("about/contact.html");
	}
	function pageJoin($inPath){
		utility_cache::pageCache(600);
        $left=!empty($inPath[2])?$inPath[2]:'';
		$this->assign("left",$left);
		return $this->render("about/join.html");
	}

	public function pageFeedBack($inPath){

		$param = !empty($inPath[3])?$inPath[3]:'';
        $left=!empty($inPath[2])?$inPath[2]:'';
		$this->assign("left",$left);
		$this->assign('ques_type', $this->ques_type);
		if( !empty($_POST)){
			$ques_type = isset($_POST['ques_type'])?$_POST['ques_type']:0;
			$content = isset($_POST['content'])?$_POST['content']:'';
			$mobile  = isset($_POST['mobile'])?$_POST['mobile']:'';

			$params = new stdclass;
			$params->ques_type = implode(',',$ques_type);
			$params->content = $content;
			$params->mobile = $mobile;
			$user = user_api::loginedUser();
			if( $user['uid']){
				$params->fk_user = $user['uid'];
			}else{
				$params->fk_user = 0;
			}
			$params->create_time = date('Y-m-d H:i:s', time());
			$add_ret = user_api::addUserFeedback($params);
			$this->assign('uid', $params->fk_user);
			return $this->render('about/feedback.suc.html');
		}
		return $this->render("about/feedback.html");
	}

	public function pageReply($inPath){
        $left=!empty($inPath[2])?$inPath[2]:'';
		$data = array();
		$user = user_api::loginedUser();
		if($user['uid']){
        	$feed_data = user_api::getUserFeedbackByUid($user['uid']);
            if($feed_data->result->data){
            	$data = $feed_data->result->data->items;
                foreach( $data as &$v){
                	$v->ques_type = explode(',',$v->ques_type);
                }
            }
		}
        $this->assign('reply_data', $data);
        $this->assign('ques_type', $this->ques_type);
        $this->assign("left",$left);
 		return $this->render('about/feedback.reply.html');
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

	function pageLeft($inPath){
		$subnav = "";
		if (!empty($inPath[3])) {
			$subnav = $inPath[3];
		}
		$this->assign("subnav", $subnav);
		return $this->render("about/left.html");
	}
	function pageBusiness($inPath){
		return $this->render("about/businessCooperation.html");
	}
	function pageContent($inPath){
		return $this->render("about/content.html");
	}
	function pageTech($inPath){
		return $this->render("about/tech.html");
	}
}
