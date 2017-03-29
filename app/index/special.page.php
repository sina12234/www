<?php
class index_special extends STpl{
	public function pageEntry($inPath){
		$this->render('special/teac_spi.html');
	}
	public function pageHanjia($inPath){
		$this->render('special/hanjiabaoming.html');
	}
	public function pageaddTeacherActivity($inPath){
		$data 			= array();
		$result 		= new stdclass;
		$user			= user_api::loginedUser();
		$data['name'] 	= !empty($_POST['name']) ? $_POST['name'] : '';
		$data['mobile'] = !empty($_POST['mobile']) ? $_POST['mobile'] : '';
		$data['fk_user']= !empty($user) ? $user['uid'] : 0;
		if(empty($_POST['name'])){
			$result->error = "名字不能为空";
			$result->code  = -100;
			return $result;
		}
		if(empty($_POST['mobile'])){
			$result->error = "手机号不能为空";
			$result->code  = -100;
			return $result;
		}
		if(utility_valid::mobile($_POST['mobile'])==false){
			$result->error = "手机号码格式不正确";
			$result->code  = -100;
			return $result;
		}
		$con['mobile']    = !empty($_POST['mobile']) ? $_POST['mobile'] : '';
		$isActive		  = org_api::getteacherActivityOneOfInfo($con);
		if(!empty($isActive)){
			$result->error = "该手机号码已报名~";
			$result->code  = -100;
			return $result;
		}
		if(SCaptcha::check(strtolower(trim(str_replace(" ","",$_POST['verifyCode']))))===false){
			$result->error = "图片验证码错误";
			$result->code  = -100;
			return $result;
		}
		$info = org_api::addTeacherActivity($data);
		if(!empty($info)){
			$result->error = "成功";
			$result->code  = 200;
			return $result;
		}else{
			$result->error = "报名失败";
			$result->code  = -100;
			return $result;
		}
	}
	public function pageteacherActivityIsExist(){
		$data 			= array();
		$result 		= new stdclass;
		$user			= user_api::loginedUser();
		//$data['fk_user']= !empty($user) ? $user['uid'] : 0;
		$data['mobile'] = !empty($_POST['mobile']) ? $_POST['mobile'] : '';
		if(!empty($_POST['mobile'])&&utility_valid::mobile($_POST['mobile'])==false){
			$result->error = "手机号码格式不正确";
			$result->code  = -100;
			return $result;
		}
		if(!empty($_POST['mobile'])){
			$info		   = org_api::getteacherActivityOneOfInfo($data);
		}
		if(!empty($info)){
			$result->error = "该手机号码已报名~";
			$result->code  = -100;
			return $result;
		}else{
			$result->msg = "可以报名~";
			$result->code  = 200;
			return $result;
		}
	}
	public function pageteacherActivity(){
		$data 			= array();
		$data['name'] 	= !empty($_POST['name']) ? $_POST['name'] : '';
		$data['mobile'] = !empty($_POST['mobile']) ? $_POST['mobile'] : '';
		$user			= user_api::loginedUser();
		$data['fk_user']= !empty($user) ? $user['uid'] : 0;
		if(!empty($user)){
			$info		= org_api::getteacherActivityOneOfInfo($data);
		}else{
			$info		= 0;
		}
		$this->assign("flag",!empty($info) ? 1 : 0);
		return $this->render("index/teacher.special.html");
	}
	public function pageteacherRankList(){
		$conf 	   = SConfig::getConfig(ROOT_CONFIG."/teacherActivityPlanId.conf","plan_id");
		$planIdStr = !empty($conf->plan_id) ? $conf->plan_id : '';
		$page 	   = !empty($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$type	   = !empty($_GET['type'])   ?  (int)$_GET['type'] : 1;
		$realName  = !empty($_GET['realname'])   ?  trim($_GET['realname']) : '';
		$size 	   = 20;
		//通过plan_id查询course_id,来获取课程图片
		if(!empty($realName)){
			$condition = array('plan_id'=>$planIdStr,'status'=>"1,2,3","teacher_real_name"=>$realName);
		}else{
			$condition = array('plan_id'=>$planIdStr,'status'=>"1,2,3");
		}

		$planParam 	= [
				'f' => array(
						'course_id','plan_id','teacher_thumb_big','subdomain','teacher_thumb_sma','org_subname','vv','teacher_real_name','start_time','status'
					),
				'q' => $condition,
				'ob'=> $type==1 ? array('vv'=>'desc') : array('start_time'=>'desc'),
				'p' => $page,
                'pl'=> $size,
			];
		$resPlan 	= seek_api::seekPlan($planParam);
		$total	 	= 0;
        $count	 	= 0;
		$idStr   	= '';
        if(!empty($resPlan->total)){
            $total	= ceil($resPlan->total/$resPlan->pagelength);
            $count	= $resPlan->total;
			foreach($resPlan->data as $m=>$n){
				$courseIdArr[] = $n->course_id;
			}
			if(!empty($courseIdArr)){
				$idStr = implode(",",$courseIdArr);
			}else{
				return $this->render("special/teacher.rank.list.html");
			}
        }
		$params 	= [
				'f' => array(
						'course_id','thumb_big','thumb_med','thumb_sma',
					),
				'q' => array('course_id'=>!empty($idStr) ? $idStr : 0,'status'=>"1,2,3"),
				'ob'=> $type==1 ? array('vv'=>'desc') : array('start_time'=>'desc'),
				'p' => 1,
                'pl'=> 4000,
			];

		$courseThumb 	= seek_api::seekCourse($params);
		//获取排名
		$planRankParmas = [
				'f' => array(
						'plan_id','vv','start_time','status'
					),
				'q' => array('plan_id'=>$planIdStr,'status'=>"1,2,3"),
				'ob'=> array('vv'=>'desc'),
				'p' => 1,
                'pl'=> 4000,
			];
		$resRank 	= seek_api::seekPlan($planRankParmas);
		if(!empty($resRank->data)){
			foreach($resRank->data as $k=>$v){
				$v->rank 		= $k+1;
				$mq[$v->plan_id]  = $v;
			}
		}
		$thumb 		= array();
		if(!empty($courseThumb->data)){
			foreach($courseThumb->data as $a=>$b){
				$thumb[$b->course_id] = $b;
			}
		}
		$teacherTotal = !empty($resPlan->total) ? (int)$resPlan->total : 0;
		$searchResult = array();
		if(!empty($realName)){
			if(!empty($resPlan->data)){
				foreach($resPlan->data as $k=>$v){
					$v->thumb 				= !empty($thumb[$v->course_id]) ? $thumb[$v->course_id] : '';
					$v->rank 				= !empty($mq[$v->plan_id]->rank) ? $mq[$v->plan_id]->rank : '';
					if($realName == $v->teacher_real_name){
						$searchResult[]		= $v;
					}
				}
			}
		}else{
			if(!empty($resPlan->data)){
				foreach($resPlan->data as $k=>$v){
					$v->thumb 				= !empty($thumb[$v->course_id]) ? $thumb[$v->course_id] : '';
					$v->rank 				= !empty($mq[$v->plan_id]->rank) ? $mq[$v->plan_id]->rank : '';
					$resPlan->data[$k]		= $v;
				}
			}
		}
		$pm = [
			'page'=>$page,
			'size'=>$size,
			'total'=>$total,
			'count'=>$count,
		];
		$path		= '/index.special.teacherRankList';
		$pathPage   = utility_tool::getUrl($path);
		$this->assign("realname",!empty($realName) ? $realName : '');
		$this->assign("pm",$pm);
		$this->assign("type",$type);
		$this->assign("resPlan",!empty($searchResult) ? $searchResult : $resPlan->data);
		$this->assign("teacherTotal",$teacherTotal);
		$this->assign("page",$page);
		$this->assign("path_page",$pathPage);
		return $this->render("special/teacher.rank.list.html");
	}
	public function pageteacherOfResult(){
		return $this->render("special/teacher.winning.list.html");
	}
	public function pagecomposition(){

		$conf 	   = SConfig::getConfig(ROOT_CONFIG."/composition.conf","junior");
		$planIdStr = !empty($conf->junior) ? $conf->junior : '';
		$res['junior'] 		= $this->getInfo($planIdStr);
		$res['senior'] 	    = $this->getInfo($conf->senior);
		$this->assign("res",!empty($res) ? $res : '');
		return $this->render("special/composition.html");
	}
	public function getInfo($planIdStr){
		$page 	   = !empty($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$size 	   = 20;
		//通过plan_id查询course_id,来获取课程图片
		$condition = array('plan_id'=>$planIdStr,'status'=>"1,2,3");
		$planParam 	= [
				'f' => array(
						'course_id','plan_id','teacher_thumb_big','subdomain','teacher_thumb_sma','org_subname','vv','teacher_real_name','start_time','status'
					),
				'q' => $condition,
				'ob'=> array('vv'=>'desc'),
				'p' => $page,
                'pl'=> $size,
			];
		$resPlan 	= seek_api::seekPlan($planParam);
		$total	 	= 0;
        $count	 	= 0;
		$idStr   	= '';
        if(!empty($resPlan->total)){
            $total	= ceil($resPlan->total/$resPlan->pagelength);
            $count	= $resPlan->total;
			foreach($resPlan->data as $m=>$n){
				$courseIdArr[] = $n->course_id;
			}
			if(!empty($courseIdArr)){
				$idStr = implode(",",$courseIdArr);
			}else{
				return $this->render("special/teacher.rank.list.html");
			}
        }
		$params 	= [
				'f' => array(
						'course_id','thumb_big','thumb_med','thumb_sma','title'
					),
				'q' => array('course_id'=>!empty($idStr) ? $idStr : 0,'status'=>"1,2,3"),
				'ob'=> array('vv'=>'desc') ,
				'p' => 1,
                'pl'=> 4000,
			];

		$courseThumb 	= seek_api::seekCourse($params);
		$thumb 		= array();
		if(!empty($courseThumb->data)){
			foreach($courseThumb->data as $a=>$b){
				$thumb[$b->course_id] = $b;
			}
		}
		$searchResult = array();
		if(!empty($resPlan->data)){
				foreach($resPlan->data as $k=>$v){
					$v->thumb 				= !empty($thumb[$v->course_id]) ? $thumb[$v->course_id] : '';
					$resPlan->data[$k]		= $v;
				}
		}
		return $resPlan->data;
	}
	public function pageyuanjiang(){
		return $this->render("special/aidXinjiang.html");
	}
	public function pagelastCompetition(){
		return $this->render("special/micro.class.competition.html");
	}
	public function pageSpecialTopics(){
		return $this->render("special/Special.topics.html");
	}
	public function pagewinterHoliday(){
		return $this->render("special/hanjiakecheng.html");
	}
	public function pageCourseVideo(){
		return $this->render("teachervideo/course.video.html");
	}
	public function pageAppMM(){
		return $this->render("special/app.mm.html");
	}
	public function pagepoint(){
		return $this->render("special/mathematics.point.html");
	}
	public function pageshici(){
		$userInfo = array();
		if(empty($_REQUEST['code'])){
			$this->weixin('index.special.shici');
		} if(!empty($_REQUEST['code'])){
			$userInfo = $this->weixinReturn($_REQUEST['code']);
		}
		$this->assign('userInfo',json_encode($userInfo));
		return $this->render("special/shici201703.html");
	}
	public function pagespecialClass(){
		$url         = "http://www.yunke.com/index.special.specialclass";
		$twoCodeUrl  = utility_cdn::qrcode("qr?s=200&t=".urlencode($url));
		$this->assign("qrCode",$twoCodeUrl);
		return $this->render("special/spring.special.class.html");
	}
	private function weixin($reUrl){
		$host = $_SERVER['HTTP_HOST'];
		$scope="snsapi_userinfo";
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize";
		$sc="http:";
		if(utility_net::isHTTPS()){
			$sc="https:";
		}
		$params = array(
			"appid"			=>"wx02043514c23e8211",
			"redirect_uri"	=>"{$sc}//{$host}/".$reUrl,
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
	private function weixinReturn($code){
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token";
		$config = weixin_api::getConfig();
		$params = array(
			"appid"			=>"wx02043514c23e8211",
			"secret"		=>$config->appsecret,//"wx02043514c23e8211",
			"code"			=>$code,
			"grant_type"	=>"authorization_code",
		);
		$code_url=$url."?".http_build_query ($params);
		$userInfo = array();
		//print_r($_REQUEST);
		//获取access_token&openid
		$ret_auth = SJson::decode(SHttp::get( $code_url, $params=array(), $cookies=array(), $returnHeader=false, $timeout=10));
		if(empty($ret_auth->openid) || empty($ret_auth->access_token)){
			return $userInfo;
		}
		//print_r($ret_auth);
		//通过公众号获取union_id
		$code_url ="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$config->access_token."&openid=".$ret_auth->openid."&lang=zh_CN";
		$ret_info = SJson::decode(SHttp::get( $code_url, $params=array(), $cookies=array(), $returnHeader=false, $timeout=10));
		if (!empty($ret_info->nickname)){
			$userInfo["nickName"] = $ret_info->nickname;
		}
		if (!empty($ret_info->headimgurl)){
			$userInfo["thumbUrl"] = $ret_info->headimgurl;
		}
		if (!empty($ret_info->thumb_url)){
			$userInfo["thumbUrl"] = $ret_info->thumb_url;
		}
		return $userInfo;
	}
}
