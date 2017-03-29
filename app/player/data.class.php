<?php
class player_data{
	var $plan;
	var $section;
	var $course;
	private $planid;
	private $uid=0;
	private $user_info;
	private $perms;
	private $plan_info;
	private $course_info;
	private $logined;
	private $playInfo;
	var $clearConfig=array(
		array("name"=>"标清","bitrate"=>160000),
		array("name"=>"高清","bitrate"=>280000),
		array("name"=>"超清","bitrate"=>360000),
		array("name"=>"原画","bitrate"=>640000),
	);
	private $error_code=array(
		"-401"	=>	"视频需要登录后才能观看",
		"-402"	=>	"视频需要报名后才能观看",

		"-410"	=>	"获取课程信息失败，请稍后再试",
		"-412"	=>	"课程还没有开始，请稍后再试",
		"-413"	=>	"你已经报名了本课程其它班级，请选择正确班级后观看",

		"-420"	=>	"视频正在处理中，请稍后再试",
		"-421"	=>	"视频没有公开，暂时不能观看",//视频隐藏，不公开
		"-422"	=>	"视频已经删除，不能观看",
	);
	/**
	 * live , recorded
	 */
	var $streamType="live";
	/**
	 * hls , rtmp
	 */
	var $playmode="";
	var $cdn_rtmp;
	var $cdn_hls;
	var $live_starttime=0;
	var $rtmp;
	var $chat;
	var $publish_chat;
	var $hls;
	var $hls_v2;
	var $segs=array();
	var $thumbs=array();
	var $points=array();
	var $notes=array();
	var $user;
	//试看
	private $trial_str="";
	private $sc="http";
	private $isTeacher=false;
	public function __construct($planid){
		$this->planid = $planid;
		if(utility_net::isHTTPS()){
			$this->sc="https";
		}
		$this->getUserInfo();
		$this->getPlan();
		$this->getChart();
		$this->getPlayLog();
		$this->getPlayUrl();
		$this->getPoint();
		$this->getToken();
		$this->getMember();
        $this->getPlanList();
	}
	function getPoint(){
		$params=array();
		$params['pid']   = $this->planid;
		$params['rtime']   = 0;
		$params['order'] = "point_time ASC";
		$pointList = video_api::getTeacherPointList($params);
		if(!empty($pointList->result)){
			foreach($pointList->result as $item){
				$this->points[]=array("time"=>(int)$item->ptime,"content"=>$item->content);
			}
		}
		if(!empty($this->uid)){
			//录播
			$params = array('fk_user'=>$this->uid,
				'course_id'=>$this->plan_info->course_id,
				'class_id'=>$this->plan_info->class_id,
				'fk_plan'=>$this->plan_info->plan_id,
				'status'=>1,
				'live_type'=>3 //2 直播  3 录播
			);
			if($this->plan_info->plan_status=="finished"){
				$params['live_type']=3;
			}elseif($this->plan_info->plan_status=="living"){
				$params['live_type']=2;
			}
			$noteList = course_note_api::noteList($params);
			if(!empty($noteList->result->data->items)){
				foreach($noteList->result->data->items as $item){
					$this->notes[]=array("time"=>(int)$item->play_time,"content"=>$item->content);
				}
			}
		}
	}
	function cdn_hls_cipher($host,$uri){
		if($host=="cdn-hls-ssl.gn100.com"){
			/**
				ali B mode
				https://help.aliyun.com/document_detail/cdn/getting-started/set/set-reqauth.html
			 */
			$timestamp = date("YmdHi",time()+5*3600);
			$g_ali_secret="b58d12735d41a3ec69b51efd0f6ebf59";
			$buf = "{$g_ali_secret}{$timestamp}{$uri}";
			$secret = md5($buf);
			return "{$timestamp}/{$secret}{$uri}";
		}else{
			return $uri;
		}
	}
	public function getPlan(){
		$plan_info_s = course_api::listPlan(array("plan_id"=>$this->planid,"allcourse"=>true));
		if(!empty($plan_info_s->data[0])){
			$this->plan_info = $plan_info = $plan_info_s->data[0];
		}else{
			$this->error=array("code"=>"-410","msg"=>$this->error_code[-410],"linktip"=>"","link"=>"");
			return ;
		}
		{
			$big = utility_cdn::file($plan_info->user_plan->thumb_big);
			$med = utility_cdn::file($plan_info->user_plan->thumb_med);
			$small = utility_cdn::file($plan_info->user_plan->thumb_small);
            $this->classId = $plan_info->class_id;
			$this->plan = array(
				"plan_id"=>$plan_info->plan_id,
				"plan_status"=>$plan_info->plan_status,
				"class_id"=>$plan_info->class_id,
				"section_id"=>$plan_info->section_id,
				"course_id"=>$plan_info->course_id,
				"teacher_id"=>$plan_info->user_plan_id,
				"start_time"=>$plan_info->start_time,
				"teacher_info" => array(
					"user_id"=>$plan_info->user_plan->user_id,
					"name"=>$plan_info->user_plan->name,
					"thumb_big"		=>"{$this->sc}:{$big}",
					"thumb_med"		=>"{$this->sc}:{$med}",
					"thumb_small"	=>"{$this->sc}:{$small}",
				),

				"organization_user_id"=>$plan_info->user_id,
			);
		}
		$this->course_info = $course_ret = course_api::getCourseOne($plan_info->course_id);
		$subdomain = user_organization::course_domain(user_organization::getSubdomainByUid($course_ret->user_id)->subdomain);
		$this->plan['play_url']  = "{$this->sc}://{$subdomain}/course.plan.play/{$plan_info->plan_id}";
		$this->plan['login_url'] = "{$this->sc}://{$subdomain}/site.main.login";
		$this->plan['apply_url'] = "{$this->sc}://{$subdomain}/course.info.show/{$plan_info->course_id}";
		if(empty($course_ret)){
			$this->error=array("code"=>"-410","msg"=>$this->error_code[-410],"linktip"=>"","link"=>"");
			return;
		}else{
			$this->course = array(
				"course_id"=>$course_ret->course_id,
				"title"=>$course_ret->title,
				"user_total"=>$course_ret->user_total,
				"thumb_big"=>interface_func::imgUrl($course_ret->thumb_big),
				"thumb_med"=>interface_func::imgUrl($course_ret->thumb_med),
				"thumb_small"=>interface_func::imgUrl($course_ret->thumb_small),
			);
			$this->section = array(
				"section_id" => $plan_info->section_id,
				"name"       => '第'.$plan_info->order_no.'课时',
				"desc"       => $plan_info->section_descipt,
				"status"     => $plan_info->status,
			);
			if(!empty($course_ret->fee)){
				$this->course['fee'] = $course_ret->fee;
			}

		}


		$this->logined = false;
		if(!empty($this->user_info)){
			$this->logined=true;
		}
		if($plan_info->user_plan_id == $this->uid|| $plan_info->user_id == $this->uid){
			$this->isTeacher=true;
		}
		$this->perms = course_api::verifyPlan($this->uid,$plan_info->plan_id);
		if(!empty($this->perms->reg)){
			$this->plan['apply']=1;
		}else{
			$this->plan['apply']=0;

		}
		if(!empty($this->perms->reg_course)){
			$this->plan['apply_course']=1;
		}else{
			$this->plan['apply_course']=0;
		}
		if(empty($this->perms->ok)){
			if(!empty($this->perms->trial_type) && $this->perms->trial_type==-2){//视频禁止观看
				$this->error=array("code"=>"-421","msg"=>$this->error_code[-421],"linktip"=>"","link"=>"");
				return;
			}elseif(empty($this->perms->reg) && $this->logined){//用户已经登陆但是没有权限观
				if(!empty($this->perms->reg_course)){//报名了课程，选错了班级
					$this->error=array(
						"code"=>"-413",
						"msg"=>$this->error_code[-413],
						"link"=>"/course.info.show/{$this->course_info->course_id}",
						"linktip"=>"报名"
					);
				}else{
					$this->error=array(
						"code"=>"-402",
						"msg"=>$this->error_code[-402],
						"link"=>"/course.info.show/{$this->course_info->course_id}",
						"linktip"=>"报名"
					);
				}
				return;
			}else{
				$this->error=array(
					"code"=>"-401",
					"msg"=>$this->error_code[-401],
					"link"=>"/site.main/login?url=/course.plan.play/{$this->planid}",
					"linktip"=>"登录后观看"
				);
				return;
			}
		}else{
			if(!empty($this->perms->live_public_type)){
				$this->plan['live_public_type'] = $this->perms->live_public_type;
			}
			if(!empty($this->perms->video_public_type)){
				$this->plan['video_public_type'] = $this->perms->video_public_type;

				if(empty($this->perms->reg) && $this->perms->video_public_type==2){//没有报名，且试看部分时间
					if(!empty($this->perms->time)){
						$this->plan['video_trial_time'] = (int)$this->perms->time;
						$this->trial_str="_try_".$this->perms->time;//试看时长
					}
				}
			}
		}

	}
	public function getPlayUrl(){
		$this->thumbs = new stdclass();
		if(!empty($this->plan_info)){
			if($this->plan_info->plan_status=="finished"){
				$this->playmode="hls";
				$this->streamType="recorded";
				$this->getVodStream();
				$this->getThumbs();
			}elseif($this->plan_info->plan_status=="living"){
				$this->playmode="rtmp";
				$this->getLiveStream();
			}else{
				if(empty($this->error)){
					$this->error=array("code"=>"-412","msg"=>$this->error_code[-412],"linktip"=>"","link"=>"");
				}
			}
		}
	}
	private function getThumbs(){
		if(!empty($this->plan_info->video_id)){
			$thumbs = player_live::getThumbs($this->plan_info->video_id);
			if(!empty($thumbs)){
				$data=explode(" ",$thumbs->thumbs);
				foreach($data as &$thumb){
					$thumb = "{$this->sc}:".utility_cdn::file($thumb);
				}
				$this->thumbs=array(
					"cols"=>(int)$thumbs->cols,
					"rows"=>(int)$thumbs->rows,
					"width"=>(int)$thumbs->width,
					"height"=>(int)$thumbs->height,
					"last_num"=>(int)$thumbs->last_num,
					"interval"=>(int)$thumbs->intervals,
					"data"=>$data,
				);
			}
        }
	}
	public function getLiveStream(){

		$playInfo = $this->playInfo = player_live::getLiveUrl($this->planid,$this->uid,$this->sc);
		if(!empty($playInfo) && $playInfo->live_call!="publish_done"){
			$this->live_starttime = $playInfo->live_uptime;
			$this->cdn_rtmp=$playInfo->cdn_rtmp;
			foreach($playInfo as $k=>$v){
				if(stripos($k,"rtmp")===0){
					$this->$k=$v;
				}
			}
			$this->cdn_hls=$playInfo->cdn_hls;
			foreach($playInfo as $k=>$v){
				if(stripos($k,"hls")===0){
					$this->$k=$v;
				}
			}
			$stream_name = $playInfo->stream_name;

			$this->chat=array("url"=>"rtmp://".$playInfo->chat->host."/chat","stream"=>$stream_name);
			$this->publish_chat=array("url"=>"rtmp://".$playInfo->host_chat_publish."/chat","stream"=>$stream_name);
		}else{
			$this->error=array("code"=>"-412","msg"=>$this->error_code[-412],"linktip"=>"","link"=>"");
		}
	}
	public function getVodStream(){
		$playInfo = $this->playInfo = player_live::getVodUrl($this->planid,$this->uid,$this->sc);
		if(!empty($playInfo)){
            if(empty($playInfo->hls_v2)){
				$this->error=array("code"=>"-420","msg"=>$this->error_code[-420],"linktip"=>"","link"=>"");
            }
			if(!empty($playInfo->video_id)){
				$this->cdn_hls=$playInfo->cdn_hls;
				foreach($playInfo as $k=>$v){
					if(stripos($k,"hls")===0){
						$this->$k=$v;
					}
				}
			}
			if(!empty($playInfo->segs)){
				$this->segs=$playInfo->segs;
			}
		}else{
			$this->error=array("code"=>"-420","msg"=>$this->error_code[-420],"linktip"=>"","link"=>"");
		}
	}
	public function getChart(){
	}
	/**
	 * @deprected ,新的播放不用这个 token了
	 */
	private function getToken(){
		$this->user->token = self::__getToken($this->uid);
	}
	private function __getToken($uid){
		$info=array(
			"plan_id"=>$this->planid,
			"user_id"=>$uid,
			"stream"=>!empty($this->playInfo->stream_name)?$this->playInfo->stream_name:"",
			"timestamp"=>time(),
			"token"=>user_api::getCookieToken(),
		);
		$conf = SConfig::getConfig(ROOT_CONFIG."/key.conf","live");
		if(!empty($conf->key)){
			$aes = new utility_aes();
			$aes->set_key($conf->key);
			return $aes->encrypt(SJson::encode($info));
		}
		return user_api::getCookieToken();

	}
	public function getUserInfo(){
		$this->user= new stdclass;
		$this->user->now=time();
		$this->user->info = new stdclass;
		$this->user->token = "";
		$this->user->ip= new stdclass;
		$ipinfo = utility_ip::info(utility_ip::realIp());
		if(!empty($ipinfo->ok)){
			$this->user->ip->ip = $ipinfo->ip;
			$this->user->ip->area_name = $ipinfo->area_name;
			$this->user->ip->op_name= $ipinfo->op_name;
		}
		$this->user_info = user_api::loginedUser();
		if(!empty($this->user_info['uid'])){
			$this->uid = $this->user_info['uid'];
			$this->user->info=array(
				"uid"=>$this->user_info['uid'],
				"name"=>$this->user_info['name'],
				"large"=> $this->sc.":".$this->user_info['large'],
				"medium"=>$this->sc.":".$this->user_info['medium'],
				"small"=> $this->sc.":".$this->user_info['small'],
			);
		}
	}
	public function getPlayLog(){
		$this->playLog = utility_cdn::playlog(null);
		$this->playLog = "{$this->sc}:{$this->playLog}";
	}
	//会员信息
	public function getMember(){
		$memberId = array();
		$courseMember = user_organization::getMemberPriorityByObjectId($this->plan['course_id'], 1);
		if(!empty($courseMember)){
			foreach($courseMember as $v){
				$memberId[] = $v->fk_member_set;
			}
		}
		$this->member = new stdclass;
		$this->member->memberId = $memberId;
	}
    public function getPlanList(){
        $params = [
            'q' => ['class_id'=>$this->classId],
            'f' => ['plan_id','section_name','section_desc','start_time','video_id','totaltime'],
            'p' => 1,
            'pl'=> 200,
            'ob'=> ['section_order_no'=>'asc']
        ];
        $seekPlan = seek_api::seekPlan($params);
        if(!empty($seekPlan->data)){
            foreach($seekPlan->data as $v){
                $planIdArr[]  = $v->plan_id;
            }
            $statusReg = course_api::getPlanStatusV2($planIdArr);
            $statusArr = array();
            if(!empty($statusReg)){
                $statusName = array('normal'=>1,'living'=>2,'finished'=>3,'-1'=>'invalid','0'=>'initial');
                foreach($statusReg as $key=>$val){
                    $statusArr[$key] = $statusName[$val->plan_status];
                }
            }

            foreach($seekPlan->data as $val){
                $this->planList[] = [
                    'plan_id'      => $val->plan_id,
                    'section_name' => $val->section_name,
                    'section_desc' => $val->section_desc,
                    'start_time'   => date('Y-m-d H:i', strtotime($val->start_time)),
                    'status'       => !empty($statusArr[$val->plan_id]) ? $statusArr[$val->plan_id] : 1,
                    'totaltime'    => $val->totaltime 
                ];
            }
        }
    }
}
