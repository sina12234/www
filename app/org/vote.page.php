<?php
/*
 * 运营工具之投票
 */
class org_vote extends STpl{
	private $domain;
	public function __construct(){
		//判断是否登录
        $this->user = user_api::loginedUser();
        if(empty($this->user)){
            $this->redirect("/site.main.login");
        }
		//机构信息
		$domain_conf  = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
        $org          = user_organization::subdomain();

        if(!empty($org)){
            $this->orgOwner = $org->userId;
        }else{
            header('Location: https://www.'.$this->domain);
        }
		//判断是否是管理员
		$isAdmin=user_api::isAdmin($this->orgOwner,$this->user['uid']);
        if($isAdmin===false){
            header('Location: //'.$org->subdomain.'.'.$this->domain);
        }
        $this->orgInfo = user_organization::getOrgByOwner($this->orgOwner);
	}

	/*
	 * 投票列表
	 */
	public function pageList(){

		$num  = 20;
		$page = isset($_GET['page']) ? $_GET['page']:1;
		$path = '/org.vote.list?';
		$voteList = array();
		$keywords = '';
		$sType = 'all';
		$param = array();
		$param['ownerId'] = $this->orgOwner;

		if(isset($_GET['sType']) && !is_numeric($_GET['sType']) && ($_GET['sType']=='ing' || $_GET['sType']=='end')){
			$sType = $_GET['sType'];
			$path .= '&sType='.$sType;
			$param['sType'] = $sType;
		}
		if(isset($_GET['serh']) && !empty($_GET['serh'])){
			$keywords = htmlspecialchars(trim($_GET['serh']));
			$path .= '&serh='.$keywords;
			$param['keywords'] = $keywords;
		}


        $header = utility_net::isHTTPS() ? "https://" : "http://";
        $url = '';

		$subdomainRes = user_organization::subdomain();

		if(!empty($subdomainRes->subdomain)){
			$sc = substr_count($subdomainRes->subdomain,'.');
			if($sc > 0){
				$url = $header.$subdomainRes->subdomain;
			}else{
				$dom = utility_net::getDomainRoot();
				$url = $header.$subdomainRes->subdomain.'.'.$dom;
			}
		}

		$list  = org_api::voteList($page,$num,$param);
		if (!empty($list->items)) {
			foreach ($list->items as $v) {
				$userOwner[$v->fk_user_owner] = $v->fk_user_owner;
			}
			$orgInfo = user_organization::getOrgInfoByUidArr($userOwner);
            if (!empty($orgInfo)) {
                foreach ($orgInfo as $info) {
                    $orgName[$info->user_owner] = !empty($info->subname) ? $info->subname : (!empty($info->name) ? $info->name : '');
                }
            }
		}


		if(!empty($list->items)){
			$voteList = $list->items;
			foreach($voteList as &$val){
				$val->startTime = date('y-m-d H:i',strtotime($val->start_time));
				$val->endTime   = date('y-m-d H:i',strtotime($val->end_time));
				$val->newTime   = time();
				$val->start_time= strtotime($val->start_time);
				$val->end_time  = strtotime($val->end_time);
				$val->url       = $url.'/org/poll/info/'.$val->pk_vote;
				$val->orgName 	= !empty($orgName[$val->fk_user_owner]) ? $orgName[$val->fk_user_owner] : '';
			}
		}

		if(!empty($list->totalPage)){
			$totalPage = $list->totalPage;
		}else{
			$totalPage = 0;
		}

		$this->assign('url',$url);
		$this->assign('voteImg',interface_func::imgUrl('6,01155cb427b585'));
		$this->assign('keywords',$keywords);
		$this->assign('sType',$sType);
		$this->assign('list',$voteList);
		$this->assign('totalPage',$totalPage);
		$this->assign('num',$num);
		$this->assign('path',$path);
		$this->assign('page',$page);
		return $this->render("/org/vote.list.html");
	}

	/*
	 * 删除投票
	 */
	public function pageVoteDelAjax(){
		$result = new stdClass;

		if(empty($_POST['voteId'])){
		   $result->msg  = '参数错误';
		   $result->code = -1;
		   return $result;
		}

		$data = [
			'voteId' => (int)($_POST['voteId']),
			'status' => -1
		];
		$res = org_api::voteUpdate($data);

		if($res->code == 0){
			$result->msg  = '删除成功';
		    $result->code = 1;
		    return $result;
		}else{
			$result->msg  = '删除失败';
		    $result->code = -2;
		    return $result;
		}
	}

	/*
	 * 创建投票
	 */
	public function pageAdd(){
		$result = new stdClass;

		if(!empty($_POST)){

			if($_POST['voteToken'] != '' && $_POST['voteToken'] == utility_session::get()['voteToken']){
				utility_session::get()['voteToken'] = '';

				if(!empty($_POST['title'])){
					$data['title'] = trim($_POST['title']);
				}else{
					$result->code = -1;
					$result->msg  = '投票内容不能为空！';
					return $result;
				}

				//投票选项
				$objType = (int)($_POST['objtype']);
				if(empty($_POST['optionVal'])){
					$result->code = -1;
					$result->msg = '投票选项不能为空！';
					return $result;
				}

				$optionArr  = $_POST['optionVal'];
				if(count($optionArr) < 2){
					$result->code = -1;
					$result->msg = '投票选项最少两项！';
					return $result;
				}

				$optioinVal = array();
				$option     = array();
				foreach ($optionArr as $key => $value) {
					$optioinVal[] = explode('***', $value);
				}
				if(!empty($optioinVal)){
					foreach ($optioinVal as $key => $value) {
						$temp = array_shift($value);
						if(!empty($value)){
							$option[$temp] = [
								'val' => !empty($value[0]) ? $value[0] : '',
								'img' => !empty($value[1]) ? $value[1] : ''
							];
						}
					}
				}
				//介绍图片
				if(!empty($_POST['imgs'])){
					$data['thumb']  = !empty($_POST['imgs'][0]) ? $_POST['imgs'][0] : '';
					$data['thumb1'] = !empty($_POST['imgs'][1]) ? $_POST['imgs'][1] : '';
					$data['thumb2'] = !empty($_POST['imgs'][2]) ? $_POST['imgs'][2] : '';
				}

				$multiSelect = (int)($_POST['multiSelect']);

				$data['ownerId'] = $this->orgOwner;
				$data['userId']  = $this->user['uid'];

				//投票简介
				if(!empty($_POST['descript'])){
					$data['descript'] = htmlspecialchars($_POST['descript']);
				}
				//投票模式
				$data['multiSelect'] = $multiSelect;
				if($multiSelect == 2){
					$data['selectCount'] = (int)($_POST['selectCount']);
				}
				//选项类型
				$data['objectType'] = $objType;
				//开始时间
				$data['startTime']   = $_POST['startTime'];
				//结束时间
				if($_POST['endTime'] == 1){
					$data['endTime'] = !empty($_POST['startTime']) ? date('Y-m-d h:i:s',strtotime('+1 week',strtotime($_POST['startTime']))) : date('Y-m-d h:i:s',strtotime('+1 week'));
				}elseif($_POST['endTime'] == 2){
					$data['endTime'] = !empty($_POST['startTime']) ? date('Y-m-d h:i:s',strtotime('+1 month',strtotime($_POST['startTime']))) : date('Y-m-d h:i:s',strtotime('+1 month'));
				}elseif($_POST['endTime'] == 3){
					$data['endTime'] = !empty($_POST['startTime']) ? date('Y-m-d h:i:s',strtotime('+5 month',strtotime($_POST['startTime']))) : date('Y-m-d h:i:s',strtotime('+5 month'));
				}elseif($_POST['endTime'] == 4){
					$data['endTime'] = !empty($_POST['startTime']) ? date('Y-m-d h:i:s',strtotime('+1 year',strtotime($_POST['startTime']))) : date('Y-m-d h:i:s',strtotime('+1 year'));
				}else{
					$data['endTime'] = $_POST['endTime1'];
				}
				//展示状态
				$data['type'] = (int)($_POST['type']);

				$header = utility_net::isHTTPS() ? "https://" : "http://";
				$url = '';
				$subdomainRes = user_organization::subdomain();
				if(!empty($subdomainRes->subdomain)){
					$sc = substr_count($subdomainRes->subdomain,'.');
					if($sc > 0){
						$url = $header.$subdomainRes->subdomain;
					}else{
						$dom = utility_net::getDomainRoot();
						$url = $header.$subdomainRes->subdomain.'.'.$dom;
					}
                }


				$res = org_api::voteAdd($data);
				if($res->code == 0){
					$i = 1;
					foreach($option as $k=>$v){
						$optionData = [
							'voteId'       => $res->result->voteId,
							'objectType'   => $objType,
							'objectId'     => $k,
							'nameDisplay'  => $v['val'],
							'thumbDisplay' => !empty($v['img']) ? $v['img'] : '',
							'orderNo'      => $i
						];
						$optRes = org_api::optionAdd($optionData);
						$i++;
					}
					if($optRes->code == 0){
						//公告表
                        if($_POST['pubarticle']=='true'){
                            $desc = '';
                            $voteUrl = $url.'/org/poll/info/'.$res->result->voteId;
                            if(!empty($_POST['descript'])){
                                $desc = $_POST['descript']."<p><a href='{$voteUrl}' target='_blank'>$voteUrl</a></p>";
                            }else{
                                $desc = "大家快来投票吧.<p><a href='{$voteUrl}' target='_blank'>$voteUrl</a></p>";
                            }

						    $noticeData = [
						    	'fk_user_id'    => $this->orgOwner,
							    'notice_title'  => $data['title'],
							    'notice_content'=> $desc,
							    'create_time'   => date('Y-m-d H:s:i'),
							    'update_time'   => date('Y-m-d H:s:i'),
						    ];
						    user_api::addNotice($noticeData);
                        }


						$result->code = 1;
						$result->msg = '插入成功！';
						return $result;
					}

				}
				$result->code = -1;
				$result->msg = '插入失败！';
				return $result;
			}else{
				$result->code = -3;
				$result->msg = '请不要重复提交！';
				return $result;
			}
		}else{
			//防止重复提交
			utility_session::get()['voteToken'] = md5(time().'yunke'.time());
			$this->assign('voteToken',utility_session::get()['voteToken']);
			return $this->render("/org/vote.add.html");
		}
	}

	/*
	 * 查看投票人
	 */
	public function pageTenderer($inPath){

		if(empty($inPath[3])){
			return $this->redirect("/org.vote.list");
		}

		$page   = isset($_GET['page']) ? (int)$_GET['page']: 1;
		$length = 20;
		$voteId = (int)($inPath[3]);
		$path = '/org.vote.tenderer/'.$voteId;

		$list = org_api::getTenderer(array('voteId'=>$voteId), $page, $length);

		if(empty($list['data'])){
			return $this->redirect("/org.vote.list");
		}

        $this->assign('total',$list['totalSize']);
		$this->assign('userInfo',$list['data']);
		$this->assign("path",$path);
		$this->assign("page",$list['page']);
		$this->assign("size",$length);
		$this->assign("voteId",$voteId);
		$this->assign("totalsize",$list['totalPage']);

		return $this->render("/org/vote.tenderer.html");
	}

	/*
	 * ajax 统计分享数
	 */
	public function pageStatShare(){
		$result = new stdClass;
		if(empty($_POST['voteId'])){
			$result->msg  = '参数错误';
			$result->code = -2;
			return $result;
		}
		$voteId = (int)($_POST['voteId']);
		$resVote = org_api::voteOne($voteId);

		if($resVote->code != 0){
			$result->msg = '该投票不存在';
			$result->code = -4;
			return $result;
		}

		$data = [
			'voteId'     => $resVote->result->pk_vote,
			'shareCount' => $resVote->result->share_count+1
		];

		org_api::voteUpdate($data);

	}


	/*
	 * ajax 获取课程 老师
	 */
	public function pageSearch(){
		$result = new stdClass;

		if(empty($_POST) || empty($_POST['selectId']) || empty($_POST['optionVal'])){
			$result->error = '';
			$result->code = -1;
			return $result;
		}

		$keyword = trim($_POST['optionVal']);
		$type    = (int)($_POST['selectId']);

		if($type == 1 || $type == 3){
			$courseParams = [
				'q'  => ['admin_status'=>1,'title'=>$keyword,'user_id'=>$this->orgOwner],
				'f'  => ['course_id','title','thumb_med'],
				'p'  => 1,
				'pl' => 10
			];
			$result = seek_api::seekcourse($courseParams);

			if(!empty($result->data)){
				$result->type = $type;
				$result->code = 1;
				return json_encode($result);
			}else{
				$result->error = '没有找到课程';
				$result->code = -1;
			}
		}elseif($type == 2){
			$teacherParams = [
				'q'  => ['teacher_status'=>1,'visiable'=>1,'real_name'=>$keyword,'org_id'=>$this->orgInfo->oid],
				'f'  => ['teacher_id','real_name','thumb_big'],
				'p'  => 1,
 				'pl' => 10
			];
			$result = seek_api::seekTeacher($teacherParams);
			if(!empty($result->data)){
				foreach($result->data as &$val){
					$val->thumbBig = utility_cdn::file($val->thumb_big);
				}
				$result->type = $type;
				$result->code = 1;
				return json_encode($result);
			}else{
				$result->error = '没有找到老师';
				$result->code = -1;
			}
		}
		return $result;
	}

	public function pageSearchClass(){
		$result = new stdClass;

		if(empty($_POST) || empty($_POST['siteid'])){
			$result->error = '参数错误';
			return $result;
		}

		$courseId = (int)($_POST['siteid']);

		//班级信息
		$courseParam = [
			'q'  => ['admin_status'=>1,'course_id'=>$courseId,'user_id'=>$this->orgOwner],
			'f'  => ['class']
		];
		$courseRes = seek_api::seekcourse($courseParam);

		$data = array();
		if(!empty($courseRes->data)){
			$res = $courseRes->data[0];
			foreach($res->class as $v){
				$data[$v->class_id]['name'] = $v->name;
			}
		}

		if(!empty($data)){
			return json_encode($data);
		}
		return $data;
	}

	public function pageSearchPlan(){
		$result = new stdClass;

		if(empty($_POST) || empty($_POST['classId'])){
			$result->error = '参数错误';
			return $result;
		}

		$classId = (int)($_POST['classId']);
		$planParam = [
				'q' => ['status' => '1,2,3','class_id'=>$classId],
				'f' => ['section_name','plan_id','section_id']
			];
		$planRes = seek_api::seekplan($planParam);

		$data = array();
		if(!empty($planRes->data)){
			foreach($planRes->data as $v){
				$data[$v->plan_id] = [
					'sectionName' => $v->section_name,
					'planId'      => $v->plan_id
				];
			}
		}

		sort($data);

		if(!empty($data)){
			return json_encode($data);
		}
		return $data;
	}

	public function pageUploadImg($inPath){

		$path = ROOT_WWW."/upload/tmp";
		if(!is_dir($path)){
			mkdir($path,0777,true);
        }

		$time = 1;//time();
		$filename = $this->user['uid']."_".$time.".jpg";
		if(!empty($_FILES['file']['tmp_name'])){
			$ret = move_uploaded_file($_FILES['file']['tmp_name'],$path."/".$filename);
			if($ret){
				list($src_w,$src_h,$type) = getimagesize($path."/".$filename);

                if($src_w < 150 || $src_h < 84){
                    @unlink($path."/".$filename);
                }
				return "/upload/tmp/".$filename;
			}
		}
		return "";

    }

	//裁剪图片
	public function pagePartImg(){
		$path = ROOT_WWW;
		$result = new stdClass;
		if(empty($_POST['voteImg'])){
			$result->error = '参数错误';
			return $result;
		}
		$voteImg  = $path.$_POST['voteImg'];
		$filename = substr($voteImg,0,strpos($voteImg, '?'));

		if(is_file($filename) && !empty($_POST['w']) && !empty($_POST['x2'])){
			list($width, $height, $type, $attr) = getimagesize($filename);

			if(!empty($width) && !empty($height)){
					$filename_dst = substr($voteImg,0,strpos($voteImg, '?'));
					$targ_w = $_POST['w'];
					$targ_h = $_POST['h'];

					switch($type){
					case 1: $img_r = imagecreatefromgif($filename);break;
					case 2: $img_r = imagecreatefromjpeg($filename);break;
					case 3:
					default:
					$img_r = imagecreatefrompng($filename);
					}
					$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

					imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
					$targ_w,$targ_h,$_POST['w'],$_POST['h']);
					$r = imagejpeg($dst_r, $filename_dst);

					if($r){

						$file = utility_file::instance();
						$r1 = $file->upload($filename_dst,user_api::getLoginUid(),"image","");
					}



					unlink($filename);
					if(!empty($r1)){
						$data = array('fid'=>$r1->fid,'src'=>utility_cdn::file($r1->fid));
						return json_encode($data);
					}
					return '';
				}
		}
	}

    public function pageAddMsg(){
        $result = new stdClass;
		$result->msg  = '推送失败';
		$result->code = -1;
		if(empty($_POST['voteId']) || empty($_POST['title'])){
			return $result;
		}

		$title = $_POST['title'];
		$voteId = (int)($_POST['voteId']);
		$resVote = org_api::voteOne($voteId);
		// status 2 已推送 -1 删除
		if($resVote->code != 0 || $resVote->result->status == 2 || $resVote->result->status == -1){
			return $result;
		}

		//机构下学生
		$studentIdArr = array();
		$resStudent   = course_api::getStudentByOwnerId($this->orgOwner);
		if(!empty($resStudent)){
			foreach($resStudent as $k=>$v){
                $studentIdArr[] = $v->fk_user;
			}
		}

		//机构下老师
		$teacherIdArr  = array();
		$TeacherParams = [
            'q'  => ['org_id'=>$this->orgInfo->oid,'visiable'=>1],
            'f'  => ['teacher_id'],
            'pl' => 1000,
        ];
        $resTeacher = seek_api::seekTeacher($TeacherParams);
		if(!empty($resTeacher->data)){
			foreach($resTeacher->data as $v){
				$teacherIdArr[] = $v->teacher_id;
			}
		}

		//推送消息到任务表
		$userFromArr = array_merge($studentIdArr,$teacherIdArr);
		if(!empty($userFromArr)){

			$header = utility_net::isHTTPS() ? "https://" : "http://";
			$url = '';
			$subdomainRes = user_organization::subdomain();
			if(!empty($subdomainRes->subdomain)){
				$sc = substr_count($subdomainRes->subdomain,'.');
				if($sc > 0){
					$url = $header.$subdomainRes->subdomain;
				}else{
					$dom = utility_net::getDomainRoot();
					$url = $header.$subdomainRes->subdomain.'.'.$dom;
				}
			}
			$orgName = !empty($this->orgInfo->subname) ?  $this->orgInfo->subname : (!empty($this->orgInfo->name) ? $this->orgInfo->name : '');
			$mtitle  = "<a href={$url}/org/poll/info/{$voteId} target=_blank>点击查看</a>";
			$content = "【{$orgName}】快来参加“{$title}”活动吧，投上你宝贵的一票！{$mtitle}" ;

			$data = [
				'voteId'   => $voteId,
				'userTo'   => $this->user['uid'],
				'userFrom' => implode(',',$userFromArr),
				'msgType'  => message_type::SYSTEM_VOTE_INFO,
				'content'  => $content
			];
			//修改推送状态
			$msgStatus = org_api::voteUpdate(array('voteId'=>$voteId,'status'=>2));

			if($msgStatus->code == 0){
				//插入redis
				$msgTask = org_api::addMsgTask($data);
				$result->msg  = '推送成功';
				$result->code = 1;
			}
		}
		return $result;
    }
}
?>
