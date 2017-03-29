<?php
ini_set("max_execution_time", 2400); // s 40 分钟
//修改此次的最大运行内存
ini_set("memory_limit", 1048576000*4); // Byte 1000 兆，即 4G
class org_main extends STpl
{
	private $domain;
	private $orgOwner;
	private $orgInfo;
	private $orgId;
	function __construct()
	{
		//如果没有登陆到登陆界面
		$this->user = user_api::loginedUser();
		if (empty($this->user)) {
			$this->redirect("/site.main.login");
		}
		$domain_conf = SConfig::getConfig(ROOT_CONFIG . "/const.conf", "domain");
		$this->domain = $domain_conf->domain;
		$org = user_organization::subdomain();
		if (!empty($org)) {
			$this->orgOwner = $org->userId; //机构所有者id 以后会根据域名而列取
		} else {
			header('Location: https://www.' . $this->domain);
		}
		$this->orgInfo = user_organization::getOrgByOwner($this->orgOwner);
		$this->assign('is_pro', isset($this->orgInfo->is_pro) ? $this->orgInfo->is_pro : 0);
		$this->orgId = $this->orgInfo->oid;
		//判断管理员
		$isAdmin = user_api::isAdmin($this->orgOwner, $this->user['uid']);
		if ($isAdmin === false) {
			header('Location: //' . $org->subdomain . '.' . $this->domain);
		}
	}

	public function setResult($data = '', $code = 0, $msg = '')
	{
		$ret = new stdclass;
		$ret->result = new stdclass;
		$ret->result->code = $code;
		$ret->result->data = $data;
		$ret->result->msg = $msg;
		return $ret;
	}

	public function pageMenu($inPath)
	{
		$subnav = "";
		if (!empty($inPath[3])) {
			$subnav = $inPath[3];
		}
		$this->orgInfo = user_organization::getOrgByOwner($this->orgOwner);
		$this->assign('orgInfo', isset($this->orgInfo) ? $this->orgInfo : 0);
		$this->assign("subnav", $subnav);
		return $this->render("/org/menu.html");
	}

	public function pageEntry($inPath)
	{
		if (!empty($this->orgInfo->oid)) {
			$orgId = $this->orgInfo->oid;
		} else {
			header('Location: https://www.' . $this->domain);
		}
		$uid = $this->user['uid'];
		if ($uid == $this->orgOwner) {
			$isAdmin = 1;
		} else {
			$isAdmin = 0;
		}
		$this->assign('isAdmin', $isAdmin);
		$today = date('Y-m-d H:i', time());
		$this->assign('today', $today);
		//收入查询
		$orgAccount = user_organization::getOrgAccountByOrgId($orgId);
		$this->assign('orgAccount', $orgAccount);
		$withdrawLog = user_organization::getOrgAccountWithdrawList($orgId, 1, 0, '', '', 0);
		if (empty($withdrawLog->items) && !empty($orgAccount->withdraw)) {
			$wflag = 1;
		} else {
			$wflag = 0;
		}
		if (!empty($withdrawLog->items)) {
			$wlog = 1;
		} else {
			$wlog = 0;
		}
		$this->assign('wlog', $wlog);
		$this->assign('wflag', $wflag);
		//获取日、周、月百分比
		$dayPercent = user_organization::getDayPercent($orgId);
		$weekPercent = user_organization::getWeekPercent($orgId);
		$monthPercent = user_organization::getMonthPercent($orgId);
		$this->assign('dayPercent', $dayPercent);
		$this->assign('weekPercent', $weekPercent);
		$this->assign('monthPercent', $monthPercent);
		//获取银行卡信息
		$cardInfo = user_organization::getOrgCardByOrgId($orgId);
		if (!empty($cardInfo)) {
			$cardInfo->card_no = '*************** ' . substr($cardInfo->card_no, -4);
			$cardInfo->user = '*' . mb_substr($cardInfo->user, -1, 1, 'utf-8');
			$cardInfo->last_no = substr($cardInfo->card_no, -4);
		}
		$this->assign('cardInfo', $cardInfo);
		//排课数量查询
		$allNormalPlan = course_api::countPlanByOwner($this->orgOwner, $status = 1);
		$todayAllPlan = course_api::countPlanByOwner($this->orgOwner, $status = 0, date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59'));
		$todayFinishPlan = course_api::countPlanByOwner($this->orgOwner, $status = 3, date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59'));
		$this->assign('allNormalPlan', $allNormalPlan);
		$this->assign('todayAllPlan', $todayAllPlan);
		$this->assign('todayFinishPlan', $todayFinishPlan);
		//学生数量查询
		$allStudent = course_api::countStudentByOwner($this->orgOwner, $status = 1);
		$todayStudent = course_api::countStudentByOwner($this->orgOwner, $status = 1, date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59'));
		$this->assign('allStudent', $allStudent);
		$this->assign('todayStudent', $todayStudent);
		//排课列表查询
		$tidArr = array();
		$todayPlanList = course_api::getPlanListByOwner($this->orgOwner, $status = 0, date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59'));
		if (!empty($todayPlanList)) {
			foreach ($todayPlanList as $td) {
				$tidArr[$td->fk_user_plan] = $td->fk_user_plan;
			}
			$todayCount = count($todayPlanList);
		} else {
			$todayCount = 0;
		}
		$tomorrowPlanList = course_api::getPlanListByOwner($this->orgOwner, $status = 0, date('Y-m-d 00:00:00', strtotime('+1 day')), date('Y-m-d 23:59:59', strtotime('+1 day')));
		if (!empty($tomorrowPlanList)) {
			foreach ($tomorrowPlanList as $tm) {
				$tidArr[$tm->fk_user_plan] = $tm->fk_user_plan;
			}
			$tomorrowCount = count($tomorrowPlanList);
		} else {
			$tomorrowCount = 0;
		}
		if (!empty($tidArr)) {
			$ret_teachers = user_api::getTeacherInfoByIds($tidArr);
			$teachers = json_decode(json_encode($ret_teachers), true);
			$this->assign("teachers", $teachers);
		}
		$weekStart = date("n月j日", mktime(0, 0, 0, date("m"), date("d") - date("w") + 1 - 14, date("Y")));
		$weekEnd = date("n月j日", mktime(23, 59, 59, date("m"), date("d") - date("w") + 1 - 7, date("Y")));
		$monthStart = date("n月", mktime(0, 0, 0, date("m") - 2, 1, date("Y")));
		$monthEnd = date("n月", mktime(23, 59, 59, date("m"), 0, date("Y")));
		$this->assign('weekStart', $weekStart);
		$this->assign('weekEnd', $weekEnd);
		$this->assign('monthStart', $monthStart);
		$this->assign('monthEnd', $monthEnd);
		$this->assign('todayPlanList', $todayPlanList);
		$this->assign('tomorrowPlanList', $tomorrowPlanList);
		$this->assign('todayCount', $todayCount);
		$this->assign('tomorrowCount', $tomorrowCount);
		return $this->render("org/home.html");
	}

	public function pageInfo($inPath)
	{
		$act = !empty($_GET['act']) ? $_GET['act'] : '';
		// 先获取机构审核表中的信息
		$checkInfo = user_organization::getOrgByOwnerTmp($this->orgOwner);
		//获取机构主表信息
		$masterInfo = user_organization::getOrgByOwner($this->orgOwner);
		$data = array();
		if ($act == "edit") {
			if (!empty($checkInfo->scopes)) {
				$data['cate_id'] = trim($checkInfo->scopes);
				$cate = user_organization::getCourseCateSomeName($data);
			}
		} else {
			if (!empty($masterInfo->scopes)) {
				$data['cate_id'] = trim($masterInfo->scopes);
				$cate = user_organization::getCourseCateSomeName($data);
			}
		}
		$nameArr = array();
		$idArr = array();
		if (!empty($cate)) {
			foreach ($cate as $k => $v) {
				$nameArr[] = isset($v->name_display) ? $v->name_display : '';
				$idArr[] = isset($v->pk_cate) ? $v->pk_cate : '';
			}
			$scopesStr = implode(",", $nameArr);
			$idStr = implode(",", $idArr);
			$masterInfo->scopes = $scopesStr;
			$masterInfo->idStr = $idStr;
			if (!empty($checkInfo)) {
				$checkInfo->scopes = $scopesStr;
				$checkInfo->idStr = $idStr;
			}
		}
		//获取所有的省
		//$province_list = user_organization::getRegionList(array('level' => 0));
		$province_list   = region_api::listRegion(0);
		//判断机构审核状态
		if (!empty($checkInfo) && $checkInfo->tmp_status != 1) {
			$this->assign('org', $checkInfo);
		} else {
			$this->assign('org', $masterInfo);
		}
		$this->assign('act', $act);
		$this->assign("province_list", $province_list);
		return $this->render("org/info.html");
	}

	public function pageorgCourseRange($inPath)
	{
		$orgVerify = user_organization::getOrgVerify($this->orgOwner);
		if (!empty($orgVerify->scopes)) {
			$selectScopes = explode(",", $orgVerify->scopes);
			$this->assign('selectScopes', $selectScopes);
		}
		$level = array("level" => 1);
		$cate = index_api::getCourseFirstCateInfo($level);
		$this->assign('cate', $cate);
		$this->render("/org/orgCourseRange.html");
	}

	//修改机构信息
	public function pageupdateOrgProfileAjax($inPath)
	{
		$result = new stdClass;
		$path = ROOT_WWW . $_POST['thumb_big'];
		$filename = $path;
		/*if (empty($this->orgInfo->thumb_big) && !is_file($filename)) {
			$result->error = "请上传机构logo";
			return $result;
		}*/
		if (strlen(iconv('utf-8', 'gb2312', trim($_POST['name']))) > 90) {
			$result->error = "机构全称不能超过30汉字或90个字符";
			$result->field = "name";
			return $result;
		}
		if (empty($_POST['subname'])) {
			$result->error = "机构简称不能为空";
			$result->field = "subname";
			return $result;
		}
		if (empty($_POST['scopes'])) {
			$result->error = "机构培训范围不能为空";
			$result->field = "scopes";
			return $result;
		}
		if (empty($_POST['province'])) {
			$result->error = "请选择所在地";
			$result->field = "province";
			return $result;
		}
		if (empty($_POST['address'])) {
			$result->error = "地址不能为空";
			$result->field = "address";
			return $result;
		}
		if (empty($_POST['hotline'])) {
			$result->error = "服务电话不能为空";
			$result->field = "hotline";
			return $result;
		}
		if (!preg_match('/\d{7,15}/', $_POST['hotline'])) {
			$result->error = "请填写正确的服务电话格式";
			$result->field = "hotline";
			return $result;
		}
		if (empty($_POST['email'])) {
			$result->error = "邮箱不能为空";
			$result->field = "email";
			return $result;
		}
		//处理上传图片
		//var_dump($_REQUEST['w']);die;
		if (is_file($filename)) {
			list($width, $height, $type, $attr) = getimagesize($filename);
			$filename_dst = $path;
			$targ_w = $_REQUEST['w'];
			$targ_h = $_REQUEST['h'];

			//$src = 'demo_files/flowers.jpg';
			switch ($type) {
				case 1:
					$img_r = imagecreatefromgif($filename);
					break;
				case 2:
					$img_r = imagecreatefromjpeg($filename);
					break;
				default:
					$img_r = imagecreatefrompng($filename);
			}
			$pic = explode(".", $_POST['thumb_big']);
			/*if($pic[2]=="jpeg"){
				$dst_r = ImageCreateTrueColor($targ_w, $targ_h);
			}elseif($pic[2]=="png"){
				$dst_r = ImageCreate( $targ_w, $targ_h );
			}*/

			//imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_REQUEST['w'], $_REQUEST['h']);
			//imagesavealpha($dst_r,true);

			//$r = imagejpeg($dst_r, $filename_dst);
			//if ($r) {
				$thumbnail = new SThumbnail($filename_dst, 100);
				$thumbnail->setMaxSize(180, 48);
				$filename_180 = utility_file::tempname("thumb");
				if ($thumbnail->genFile($filename_180) === false) {
					$result->error = "不是有效果的图片";
					return $result;
				};
				$file = utility_file::instance();
				$r1 = $file->upload($filename_180, user_api::getLoginUid(), "image", "");
				if (empty($r1)) {
					$result->error = "图片处理失败";
					return $result;
				}
				//图片尺寸都是一样的
				$_POST['thumb_small'] = $r1->fid;
				$_POST['thumb_med'] = $r1->fid;
				$_POST['thumb_big'] = $r1->fid;
				//unlink($filename);
				unlink($filename_dst);
				unlink($filename_180);
			//}
		}
		//组装数据
		$data = array();
		$data['subname'] = !empty($_POST['subname']) ? $_POST['subname'] : '';
		//$data['company'] = !empty($_POST['company'])?$_POST['company']:'';
		$data['scopes'] = !empty($_POST['scopes']) ? trim($_POST['scopes']) : '';
		$data['province'] = !empty($_POST['province']) ? $_POST['province'] : 0;
		$data['city'] = !empty($_POST['city']) ? $_POST['city'] : 0;
		$data['county'] = !empty($_POST['county']) ? $_POST['county'] : 0;
		$data['address'] = !empty($_POST['address']) ? $_POST['address'] : '';
		$data['email'] = !empty($_POST['email']) ? $_POST['email'] : '';
		//$data['areacode'] = !empty($_POST['areacode'])?$_POST['areacode']:'';
		$data['hotline'] = !empty($_POST['hotline']) ? $_POST['hotline'] : '';
		//$data['extension'] = !empty($_POST['extension'])?$_POST['extension']:'';
		$data['policy'] = !empty($_POST['policy']) ? $_POST['policy'] : '';
		$data['thumb_big'] = !empty($_POST['thumb_big']) ? $_POST['thumb_big'] : $this->orgInfo->thumb_big;
		$data['thumb_med'] = !empty($_POST['thumb_med']) ? $_POST['thumb_med'] : $this->orgInfo->thumb_med;
		$data['thumb_small'] = !empty($_POST['thumb_small']) ? $_POST['thumb_small'] : $this->orgInfo->thumb_small;
		$data['tmp_status'] = 0;
		$data['name'] = !empty($_POST['name']) ? $_POST['name'] : '';
		$data['desc'] = !empty($_POST['desc']) ? $_POST['desc'] : '';
		$r = user_organization::setOrgProfileRelateInfo($this->orgOwner, $data);
		if ($r) {
			$result->status = "Success!";
			return $result;
		} else {
			$result->error = "修改失败!";
			return $result;
		}
	}

	//修改机构关于我们
	public function pageupdateAboutAjax($inPath)
	{
		$result = new stdClass;
		if (empty($_POST['name'])) {
			$result->error = "机构名称名不能为空";
			$result->field = "name";
			return $result;
		} else {
			$data = array();
			$data['name'] = !empty($_POST['name']) ? $_POST['name'] : '';
			$data['desc'] = !empty($_POST['desc']) ? $_POST['desc'] : '';
			$r = user_organization::setOrgAbout($this->orgOwner, $data);
			if ($r == false) {
				$result->status = "Success!";
				return $result;
			} elseif ($r == true) {
				$result->status = "Success!";
				return $result;
			} else {
				$result->error = "修改失败!";
				return $result;
			}
		}
	}

	public function pagedelCheckInfo($inPath)
	{
		$st = !empty($_GET['sk']) ? $_GET['sk'] : '';
		if ($st == 'tag') {
			$del_status = user_organization::delCheckInfo($this->orgOwner);
		}
		$this->render('org/info.html');
	}

	public function pageIframeLogo()
	{
		// 机构基本信息
		$orgInfo = user_organization::getOrgByUid($this->orgOwner);
		$this->assign('orgInfo', $orgInfo);
		$this->render('org/iframe.logo.html');
	}

	public function pageIframeAbout()
	{
		// 机构基本信息
		$orgInfo = user_organization::getOrgByUid($this->orgOwner);
		$res_info = user_organization::getOrgByOwnerTmp($this->orgOwner);
		$this->assign('orgInfo', $orgInfo);
		$this->assign('res_info', $res_info);
		$this->render('org/iframe.about.html');
	}

	public function pageSetting($inPath)
	{
		//查询机构基本信息
		$orgInfo = user_organization::getOrgByUid($this->orgOwner);
		$hot_type = user_organization::getOrgSetHotType($this->orgOwner);
		$this->assign('hot_type', $hot_type);
		//幻灯片
		$slideList = user_organization::getOrgSlideList($this->orgOwner);
		$this->assign('slideList', $slideList);
		//读取常用和最近标签
		$tagInfo = $this->getRecentTag();
		$this->assign("often", !empty($tagInfo->data->often) ? $tagInfo->data->often : '');
		$this->assign("lasted", !empty($tagInfo->data->lasted) ? $tagInfo->data->lasted : '');
		//机构已选标签
		$selected = $this->getOrgTagInfo();
		$this->assign("selected", !empty($selected->result->data) ? $selected->result->data : '');

		$selectdTagId = [];
		if (!empty($selected->result->data)) {
			foreach ($selected->result->data as $k => $v) {
				$selectdTagId[] = !empty($v->pk_tag) ? $v->pk_tag : '';
			}
		}
		$this->assign('selectdTagId', $selectdTagId);
		//课程推荐
		$topCourse = array();
		$topCourseRet = course_api::getTopCourseByOwner($this->orgOwner);
		if (!empty($topCourseRet)) {
			foreach ($topCourseRet as $k => $v) {
				$topCourse[$v->top] = $v;
			}
		}
		$this->assign('topCourse', $topCourse);
		//获取明星教师
		$starTeacher = array();
		$starTeacherRet = user_organization::listOrgUser($orgInfo->oid, $all = 0, $star = 1, $page = 1, $length = 5);
		//快捷导航
		$result = user_organization::getOrgOfNavList($this->orgInfo->oid);
		$this->assign("orgNav", !empty($result->items) ? $result->items : '');
		utility_session::get()['noticeToken'] = md5(time() . 'noticeToken' . time());
		$this->assign('noticeToken', utility_session::get()['noticeToken']);
		$this->assign('starTeacher', $starTeacherRet);
		return $this->render("/org/setting.html");
	}

	public function getRecentTag()
	{
		$org = user_organization::getOrgByOwner($this->orgOwner);
		$data = ["oid" => (int)$org->oid];
		$tagInfo = tag_api::getRecentTag($data);
		return $tagInfo;
	}

	public function CourseTagInfo($courseId)
	{
		$tagConf = SConfig::getConfig(ROOT_CONFIG . "/group.conf", "group");
		$data = ['courseId' => $courseId, "tagConf" => $tagConf->tagCourse];
		$info = tag_api::getUserSelectedCourseTag($data);
		return $info;
	}

	//显示机构标签
	public function getOrgTagInfo()
	{
		$data = ["oid" => (int)$this->orgInfo->oid];
		$orgTagInfo = org_api::getOrgTagInfo($data);
		return $orgTagInfo;
	}

	//增加机构标签
	public function pageaddOrgTagAjax()
	{
		$tagName = !empty($_POST['tagName']) ? trim($_POST['tagName']) : '';
		$tagArr = [];
		if (!empty($tagName)) {
			$tagArr = explode(",", $tagName);
		}
		$data = ['fk_org' => (int)$this->orgInfo->oid, 'tag_names' => $tagArr];
		$add = org_api::addOrgTagAjax($data);
		return $add;
	}

	//课程热度展示
	public function pageupdatehot($inPath){
		$hotType 			= !empty($_POST['hot_type']) ? $_POST['hot_type'] : '1';
		$liShow 			= !empty($_POST['li_show']) ? $_POST['li_show'] : '2';
		$data 				= array();
		$data['hot_type'] 	= $hotType;
		$data['living_show']= $liShow;
		$data['is_nav'] 	= isset($_POST['nav_name']) ? $_POST['nav_name'] : 0;
		$data['is_cate'] 	= isset($_POST['course_cate']) ? $_POST['course_cate'] : 1;
		$skinType 			= array("orange"=>1,"green"=>2,"skyblue"=>3,"blue"=>4);
		
		if(!empty($_POST['skin'])){
			$data['site_skin'] =  isset($skinType[$_POST['skin']]) ? $skinType[$_POST['skin']] : "1";
		}
		$orgInfo = user_organization::getOrgByUid($this->orgOwner);
		$res = user_organization:: updatehotType($orgInfo->user_owner_id, $data);
		$result = new stdClass;
		if ($res) {
			$result->status = "Success!";
			return $result;
		} else {
			$result->error = "设置失败!";
			return $result;
		}

	}

	public function pageIframeSLide($inPath)
	{
		$slide = user_organization::getOrgSlide($inPath[3]);
		if (!empty($slide)) {
			$act = 'edit';
		} else {
			$act = 'add';
		}
		$this->assign('act', $act);
		$this->assign('slide', $slide);
		$this->render('org/iframe.slide.html');
	}

	public function pageUpdateSlideAjax($inPath)
	{
		$result = new stdClass;
		$act = !empty($_POST['act']) ? 'add' : 'edit';
		$sid = !empty($_POST['sid']) ? $_POST['sid'] : 0;

		if (empty($_POST['fid'])) {
			$result->error = "请先上传图片";
			return $result;
		}
		$sid = !empty($_POST['sid']) ? $_POST['sid'] : 0;
		$data = array();

		$data['slide_link'] = !empty($_POST['slide_link']) ? $_POST['slide_link'] : '';
		$data['slide_url'] = !empty($_POST['fid']) ? $_POST['fid'] : '';
		$data['rgb'] = !empty($_POST['color_rgb']) ? $_POST['color_rgb'] : '';
		if(!empty($_POST['slide_link'])){
			preg_match("/^[a-zA-z]+:\/\/[\w.]+(yunke.com|gn100.com)+[^\s]*$/", $_POST['slide_link'], $matches);
			if (empty($matches[0])) {
				$result->error = "请使用本机构或云课链接地址~";
				return $result;
			}
		}
		if ($act == 'add') {
			if ($_POST['noticeToken'] != '' && $_POST['noticeToken'] == utility_session::get()['noticeToken']) {
				utility_session::get()['noticeToken'] = '';
				$r = user_organization::setOrgSlide($sid, $this->orgOwner, $data, $act);
			} else {
				$result->error = "请不要重复提交!";
				return $result;
			}
		} else {
			$r = user_organization::setOrgSlide($sid, $this->orgOwner, $data, $act);
		}

		if ($r) {
			$result->status = "Success!";
			return $result;
		} else {
			$result->error = "设置失败!";
			return $result;
		}
	}

	public function pagedelOrgSlideAjax($inPath)
	{
		$result = new stdClass;
		if (empty($_POST['sid'])) {
			$result->error = "参数错误";
			return $result;
		} else {
			$r = user_organization::delOrgSlide((int)$_POST['sid']);
			if ($r) {
				$result->status = "Success!";
				return $result;
			} else {
				$result->error = "删除失败!";
				return $result;
			}
		}
	}

	public function pageIframeNotice($inPath)
	{
		$nid = !empty($inPath[3]) ? $inPath[3] : 0;
		$notice = user_api::getNotice($nid);
		if (!empty($notice)) {
			$act = 'edit';
		} else {
			$act = 'add';
		}
		$this->assign('act', $act);
		$this->assign('notice', $notice);
		$this->render('org/iframe.notice.html');
	}

	public function pageIframeCourse($inPath)
	{
		$top = $inPath[3];
		$this->assign('top', $top);
		$this->render('org/iframe.course.html');
	}

	public function pageSetcourseTopAjax($inPath)
	{
		$result = new stdClass;
		if (empty($_POST['cid']) || empty($_POST['top'])) {
			$result->error = "参数错误";
			return $result;
		} else {
			//删除历史推荐课程
			$history = course_api::delHistoryTopCourse($this->orgOwner, $_POST['top']);

			$r = course_api::setCourse($_POST['cid'], array('top' => $_POST['top']));
			if ($r) {
				$result->status = "Success!";
				return $result;
			} else {
				$result->error = "修改失败!";
				return $result;
			}
		}
	}

	public function pageDownCourseAjax($inPath)
	{
		$result = new stdClass;
		if (empty($_POST['cid'])) {
			$result->error = "参数错误";
			return $result;
		} else {
			$r = course_api::setCourse($_POST['cid'], array('top' => 0));
			if ($r) {
				$result->status = "Success!";
				return $result;
			} else {
				$result->error = "修改失败!";
				return $result;
			}
		}
	}

	public function pageSearchCourseAjax($inPath)
	{
		$keyword = !empty(trim($_POST['keyword'])) ? trim($_POST['keyword']) : '';
		$f_array = array(
			'title',
			'course_id',
			'fee_type',
			'price',
			'thumb_big',
		);

		$q_array = array();
		$q_array = array(
			//'top'=>'0',
			'admin_status' => 1,
			'user_id' => $this->orgOwner,
		);
		if (!empty($keyword)) {
			$q_array['title'] = $keyword;
		}
		$ob_array = array(
			'create_time' => 'desc',
		);
		$seek_arr = array(
			"f" => $f_array,
			"q" => $q_array,
			"ob" => $ob_array,
			"p" => 1,
			"pl" => 50,
		);
		$ret_seek = seek_api::seekcourse($seek_arr);
		return json_encode($ret_seek);
	}

	public function pageIframeTeacher($inPath)
	{
		$is_star = $inPath[3];
		$recommend_type = $inPath[4];
		$old_id = $inPath[5];
		$this->assign('is_star', $is_star);
		$this->assign('recommend_type', $recommend_type);
		$this->assign('old_id', $old_id);
		$this->render('org/iframe.teacher.html');
	}

	public function pageSetTeacherStarAjax($inPath)
	{
		$result = new stdClass;
		$recommend_type = isset($_POST['recommend_type']) ? $_POST['recommend_type'] : '1';
		$old_id = isset($_POST['old_id']) ? $_POST['old_id'] : '';
		if (empty($_POST['tid']) || empty($_POST['is_star'])) {
			$result->error = "参数错误";
			return $result;
		} else {
			//查询机构基本信息
			$orgInfo = user_organization::getOrgByUid($this->orgOwner);
			//如果recommend_type等于1修改推荐老师
			if ($recommend_type == '0') {
				$u = user_organization::delHistoryStarTeacher($orgInfo->oid, $old_id);
			}

			$r = user_organization::setStarteacher($orgInfo->oid, $_POST['tid'], array('is_star' => $_POST['is_star'], 'visiable' => 1));
			if ($r) {
				$result->status = "Success!";
				return $result;
			} else {
				$result->error = "修改失败!";
				return $result;
			}
		}
	}

	public function pageDownTeacherAjax($inPath)
	{
		$result = new stdClass;
		if (empty($_POST['tid'])) {
			$result->error = "参数错误";
			return $result;
		} else {
			//查询机构基本信息
			$orgInfo = user_organization::getOrgByUid($this->orgOwner);
			$r = user_organization::setStarteacher($orgInfo->oid, $_POST['tid'], array('is_star' => 0));
			if ($r) {
				$result->status = "Success!";
				return $result;
			} else {
				$result->error = "修改失败!";
				return $result;
			}
		}
	}

	public function pageSearchTeacherAjax($inPath)
	{
		$keyword = !empty($_POST['keyword']) ? $_POST['keyword'] : '';
		//查询机构基本信息
		$orgInfo = user_organization::getOrgByUid($this->orgOwner);
		$f_array = array("real_name", "teacher_id", "thumb_big");
		$q_array = array(
			'real_name' => $keyword,
			'org_id' => $orgInfo->oid,
			'teacher_status' => 1,
		);
		$ob_array = array(
			'teacher_id' => 'desc',
		);
		$seek_arr = array(
			"f" => $f_array,
			"q" => $q_array,
			"ob" => $ob_array,
			"p" => 1,
			"pl" => 10,
		);
		$ret_seek = seek_api::seekTeacher($seek_arr);
		if (empty($ret_seek->data)) {
			$ret_seek = user_organization::searchOrgTeacherByRealName($orgInfo->oid, $keyword);
		}

		if (!empty($ret_seek->data)) {
			foreach ($ret_seek->data as $k => $v) {
				if (!empty($v->thumb_big)) {
					$ret_seek->data[$k]->thumb_big = utility_cdn::file($v->thumb_big);
				} else {
					$ret_seek->data[$k]->thumb_big = '/assets_v2/img/defaultPhoto.gif';
				}
			}
		}
		return json_encode($ret_seek);
	}

	public function pageDefault()
	{
		$this->render('org/404.html');
	}

	public function pageOrder($inPath)
	{
		$option = !empty($_POST) ? $_POST : $_GET;
		$page = !empty($option['page']) ? (int)$option['page'] : 1;
		$length = 10;
		$type = false;
		$flag = !empty($option['shelf']) ? $option['shelf'] : 'all';
		$path = 'org.main.order?shelf=' . $flag;
		$conf = array('expired' => '-2,-4', 'initial' => '0,1', 'success' => '2');
		$params['orgId'] = $this->orgId;
		if (isset($conf[$flag])) {
			$params['status'] = $conf[$flag];
		}

		//关键字筛选
		$search = !empty($option['search']) ? (int)$option['search'] : 0;
		$keywords = !empty($option['keywords']) ? trim($option['keywords']) : '';
		$discount = isset($option['discount']) ? (int)$option['discount'] : 2;
		$orderType = !empty($option['orderType']) ? (int)$option['orderType'] : 0;
		$status = true;
		if (!empty($keywords) && !empty($search)) {
			if ($search == 1) {
				//订单号筛选
				$params['orderSn'] = $keywords;
			} elseif ($search == 2) {
				//课程名筛选
				$params['title'] = $keywords;
				$params['orderType'] = 1;
				$params['objectId'] = $this->likeCourse($keywords);
				if(empty($params['objectId'])){
					$status=false;
				}
			} elseif ($search == 3) {
				//手机号筛选
				$userInfo = user_api::likeMobile($keywords);
				if (!empty($userInfo)) {
					$params['userId'] = array_reduce($userInfo, create_function('$v,$w', '$v[$w->fk_user]=$w->fk_user;return $v;'));
				}else{
					$status=false;
				}
			} elseif ($search == 4) {
				//用户名筛选
				$userInfo = user_api::likeUserName($keywords);
				if (!empty($userInfo)) {
					$params['userId'] = array_reduce($userInfo, create_function('$v,$w', '$v[$w->pk_user]=$w->pk_user;return $v;'));
				}else{
					$status=false;
				}
			}
			$path .= "&search={$search}&keywords={$keywords}";
		}
		//时间筛选
		if (!empty($option['start_time']) && !empty($option['end_time'])) {
			$type = true;

			$params['start_time'] = str_replace('-', '/', $option['start_time']);
			$params['end_time'] = str_replace('-', '/', $option['end_time']);
			$params['time'] = $option['start_time'] . ',' . $option['end_time'];
			$path .= "&start_time={$params['start_time']}&end_time={$params['end_time']}";
		}
		//价格筛选
		if (isset($option['price1']) && isset($option['price2'])) {
			$type = true;

			$params['price1'] = $option['price1'];
			$params['price2'] = $option['price2'];
			$params['price'] = ($option['price1'] * 100) . ',' . ($option['price2'] * 100);
			$path .= "&price1={$params['price1']}&price2={$params['price2']}";
		}
		//优惠券筛选
		if ($discount != 2) {
			$type = true;
			$params['isFavorable'] = $discount;
			$path .= "&discount={$discount}";
		}
		//订单类型筛选
		if (!empty($orderType)) {
			$params['orderType'] = $orderType;
			$path .= "&orderType={$orderType}";
		}
		//分销课程
		if (!empty($option['resell'])) {
			$type = true;
			$params['resell'] = (int)$option['resell'];
		}
		if(!$status){
			$this->assign('search', $search);
			$this->assign('keywords', $keywords);
			$this->assign('discount', $discount);
			$this->assign('orderType', $orderType);
			$this->assign('path', $path);
			$this->assign('type', $type);
			$this->assign('flag', $flag);
			$this->assign('totalSize', 0);
			$this->assign('orderList', array());
			$this->render('org/order.list.v3.html');
		}
		$params['objTypeStatus'] = 1;
		$orderList = order_api::orderList($params, $page, $length);
		$this->assign('length', $length);
		$this->assign('page', $page);
		$this->assign('totalPage', $orderList['total']);
		$this->assign('totalSize', $orderList['totalSize']);
		$this->assign('orderList', $orderList['data']);
		$this->assign('params', $params);
		$this->assign('flag', $flag);
		$this->assign('search', $search);
		$this->assign('keywords', $keywords);
		$this->assign('discount', $discount);
		$this->assign('orderType', $orderType);
		$this->assign('path', $path);
		$this->assign('type', $type);
		$this->render('org/order.list.v3.html');
	}

	/*app模板设置*/
	public function pageAppTemplate($inPath)
	{
		$data['fk_org'] = !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$catId			= !empty($_POST['cate_id']) ? $_POST['cate_id'] : "";
		$orgCate 		= org_api::getorgCateInfo($data['fk_org'],$this->orgOwner,$catId);
		$condition 		= array();
		$condition['fk_org'] = !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$info = user_organization::xiaowoOrgList($condition);
		$bannerData 	= array();
		if(!empty($info)){
			foreach($info as $k=>$v){
				$bannerData[$v->types][] = $v;
			}
		}
		$this->assign("bannerApp",!empty($bannerData[1]) ? $bannerData[1] : '');
		$this->assign("themePic",!empty($bannerData[2]) ? $bannerData[2] : '');
		$this->assign('orgCate', !empty($orgCate->data) ? $orgCate->data : "");
		return $this->render("/org/app.template.html");
	}
	/*app模板-选择分类弹窗*/
	public function pageiframeCate($inPath)
	{
		$data['fk_org'] = !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$catId			= !empty($_POST['cate_id']) ? $_POST['cate_id'] : "";
		$orgCate 		= org_api::getOrgCustomerCateList($data['fk_org'],$this->orgOwner,$catId);
		$this->assign('orgCate', !empty($orgCate->data) ? $orgCate->data : "");
		$appcate 		= org_api::getorgCateInfo($data['fk_org'],$this->orgOwner,$catId);
		if(!empty($appcate->data)){
			foreach($appcate->data as $key=>$val){
				$cateArrInfo[$val->pk_cate] = $val;
			}
		}
		$cateResult = utility_tool::objToArray($cateArrInfo);
		$this->assign('appcate', !empty($cateResult) ? $cateResult : "");
		return $this->render("/org/iframe.app.course.cate.html");
	}

	

	/*app demo设置*/
	public function pageAppDemo($inPath)
	{
		return $this->render("/org/iframe.app.demo.html");
	}

	/*app demo设置*/
	public function pageAppclassify($inPath)
	{
		return $this->render("/org/iframe.app.classify.html");
	}

	/**
	 * 首页模版设置
	 * @author Panda <zhangtaifeng@gn100.com>
	 */
	public function pageTemplate($inPath)
	{
		//查询现有课程模版
		$templates = user_organization::getTemplateCheck($this->orgOwner);
		$condition = array("fk_org_resell" => $this->orgInfo->oid);
		$salesInfo = course_resell_api::getSalesCourse($page = 1, $length = 0, $condition);
		
		$resell = array();
		if (!empty($salesInfo)) {
			foreach ($salesInfo as $k => $v) {
				$resell[$v->fk_course] = $v;
			}
		}
		//echo "<pre>";print_r($resell);die;
		if (!empty($templates)) {
			$valueNameStr = array();
			$cateIdArr = array();
			$valueIdArr = array();
			$num = $this->orgInfo->is_pro == 1 ? 5 : 4;
			foreach ($templates as $tk => $tv) {
				if ($tv->recommend == 1) {
					$cateTemp = array('first_cate', 'second_cate', 'third_cate');
					$queryArr = array();
					foreach ($tv->query_arr as $qk => $qv) {
						if ($tv->query_arr->fee_type == "2") {
							$tv->query_arr->fee_type = "0,1";
						}
						if ($qk == 'grade_id' && $qv == 0) {
							continue;
						}
						if ($qk == 'subject_id' && $qv == 0) {
							continue;
						}
						if ($qk == 'attr_value_id') {
							$queryArr[$qk] = str_replace('|', ',', $qv);
							$attrValueArr[$tv->template_id] = explode('|', $qv);
						}
						if (in_array($qk, $cateTemp)) {
							$cateIdArr[] = $qv;
						}
					}

					if (!isset($tv->query_arr->course_type)) {
						$tv->query_arr->course_type = "1,2,3";
						$queryArr['course_type'] = $tv->query_arr->course_type;
					} else {
						$queryArr['course_type'] = $tv->query_arr->course_type;
					}
					if (isset($tv->query_arr->first_cate)) {
						$queryArr['first_cate'] = $tv->query_arr->first_cate;
					}
					if (isset($tv->query_arr->second_cate)) {
						$queryArr['second_cate'] = $tv->query_arr->second_cate;
					}
					if (isset($tv->query_arr->third_cate)) {
						$queryArr['third_cate'] = $tv->query_arr->third_cate;
					}

					$queryArr['fee_type'] = $tv->query_arr->fee_type;
					if (!empty($attrValueArr[$tv->template_id])) {
						$valueRet = course_api::getAttrValueByVidArr($attrValueArr[$tv->template_id]);
						if (!empty($valueRet)) {
							foreach ($valueRet as $vo) {
								$valueNameArt = SLanguage::tr($vo->name, "course.list");
							}
							$valueNameStr[$tv->template_id] = $valueNameArt;

						}
					}

					$queryArr['user_id'] = $this->orgOwner;
					$queryArr['admin_status'] = 1;
					//$queryArr['course_id'] = $tv->course_ids;
					//查询排序
					if (!empty($tv->order_by)) {
						$obArr = explode(':', $tv->order_by);
						$orderArr = array($obArr[0] => $obArr[1]);
					} else {
						$orderArr = array('create_time' => 'desc');
					}

					if($obArr[0]=="register"){
						$orderArr = array("user_total" => $obArr[1]);
					}
					$seekArr = array(
						"f" => array(
							'course_id',
							'title',
							'thumb_big',
						),
						"q" => $queryArr,
						"ob" => $orderArr,
						"p" => 1,
						"pl" => $tv->row_count * $num,
					);

					$seekRet 	= seek_api::seekcourse($seekArr);
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
						if(empty($tb)){
							$templates[$tk]->courses = $seekRet->data;
						}else{
							$templates[$tk]->courses = $tb;
						}
						
					}else{
						$templates[$tk]->courses = $seekRet->data;
					}
					//$templates[$tk]->courses = $seekRet->data;
					$cid = '';

					/*foreach ($seekRet->data as $a => $b) {
						$cid .= $b->course_id . ",";
					}
					$cid = rtrim($cid, ",");
					$templates[$tk]->course_ids = $cid;*/
				}

				if ($tv->recommend == 2) {
					if (!empty($tv->course_ids)) {
						$seekArr = array(
							"f" => array(
								'course_id',
								'title',
								'thumb_big',
								'admin_status',
								'subdomain',
							),
							"q" => array('course_id' => $tv->course_ids),
							"p" => 1,
							"pl" => 30,
						);
						$seekRet = seek_api::seekcourse($seekArr);
						if (!empty($seekRet->data)) {
							//按照存的顺序取出来
							$data1 = array();
							$data2 = array();
							foreach ($seekRet->data as $sdk => $sdv) {
								$sdv->resell_status = !empty($resell[$sdv->course_id]->status) ? $resell[$sdv->course_id]->status : '';
								$sdv->restatus = !empty($resell[$sdv->course_id]->restatus) ? $resell[$sdv->course_id]->restatus : '';
								$data1[$sdv->course_id] = $sdv;
								if (strpos($sdv->subdomain, ".") === false) {
									$sdv->surl = "//" . $sdv->subdomain . "." . $this->domain . "/course.info.show/" . $sdv->course_id;
								} else {
									$sdv->surl = "//" . $sdv->subdomain . "/course.info.show/" . $sdv->course_id;
								}
							}
							foreach($tv->course_arr as $tcv){
                                if(!empty($data1[$tcv])){
                                    $data2[]=$data1[$tcv];
                                }
                            }
							$templates[$tk]->courses = $data2;

						}
					} else {
						$templates[$tk]->courses = '';
					}


				}
			}

			if (!empty($cateIdArr)) {
				$cateIdStr = implode(',', $cateIdArr);
				$cateRet = course_api::getCateByCidStr($cateIdStr);
				if (!empty($cateRet)) {
					foreach ($cateRet as $co) {
						$cateList[$co->pk_cate] = $co->name_display;
					}
					$this->assign('cateList', $cateList);
				}
			}
			$this->assign('valueNameStr', $valueNameStr);
		}
		$this->assign('templates', $templates);
		$this->assign('is_pro', isset($this->orgInfo->is_pro) ? $this->orgInfo->is_pro : 0);
		$this->render('org/template.html');
	}

	public function pageSystemSet($inPath)
	{
		$orgProfile = user_organization::orgAboutProfileInfo($this->orgOwner);
		if (!empty($orgProfile->scopes)) {
			$firstCateIdStr = $orgProfile->scopes;
		} else {
			$firstCateIdStr = 1;
		}
		$firstCateList = array();
		$firstCateList = course_api::getCateByCidStr($firstCateIdStr);
		if (count($firstCateList) == 1) {
			$secondCateList = course_api::getNodeCate($firstCateList[0]->pk_cate);
			$this->assign('secondCateList', $secondCateList);
		}
		if (!empty($inPath[3])) {
			$tid = $inPath[3];
			$act = 'edit';
			$template = user_organization::getOrgtemplateInfo($tid);
			$recommend = $template->recommend;
			if (!empty($template->query_arr->attr_value_id)) {
				$template->query_arr->attr_value_id = explode('|', $template->query_arr->attr_value_id);
				$this->assign('attrValueIds', json_encode($template->query_arr->attr_value_id));
			}
			if (!empty($secondCateList) && !empty($template->query_arr->second_cate)) {
				$thirdCateList = course_api::getNodeCate($template->query_arr->second_cate);
				$this->assign('thirdCateList', $thirdCateList);
			}
			if (!empty($thirdCateList) && !empty($template->query_arr->third_cate)) {
				$attrRet = course_api::getAttrAndValueByCateId($template->query_arr->third_cate);
				foreach ($attrRet as $ro) {
					if ($ro->name_display == '科目') {
						$attrList = $ro;
						foreach ($attrList->attr_value as &$ao) {
							if (!empty($template->query_arr->attr_value_id)) {
								if (in_array($ao->attr_value_id, $template->query_arr->attr_value_id)) {
									$ao->check = 'checked';
								} else {
									$ao->check = '';
								}
							} else {
								$ao->check = '';
							}
						}
						break;
					}
				}
				$this->assign('attrList', $attrList);
			}
			$this->assign('template', $template);
		} else {
			$recommend = 1;
			$act = 'add';
		}
		$this->assign('firstCateList', $firstCateList);
		$this->assign('firstCount', count($firstCateList));
		$this->assign('act', $act);
		$this->assign('recommend', $recommend);
		$this->render('org/iframe.systemset.html');
	}

	public function setResultAjax($code, $msg, $data = '')
	{

		$ret = array(
			'code' => $code,
			'msg' => $msg,
			'data' => $data
		);
		exit(json_encode($ret));

	}

	public function pageGetNodeCate($inPath)
	{
		$cateId = isset($inPath[3]) ? (int)$inPath[3] : 0;
		if (empty($cateId)) {
			$this->setResultAjax(-1, 'cateId is empty');
		}
		$ret = course_api::getNodeCate($cateId);
		if (!empty($ret)) {
			foreach ($ret as &$cate) {
				$cate->name_display = SLanguage::tr($cate->name_display, "course.list");
			}
			$this->setResultAjax(0, 'success', $ret);
		} else {
			$this->setResultAjax(-2, '获取数据失败');
		}
	}

	public function pageGetAttrAndValueByCateId($inPath)
	{
		$cateId = isset($inPath[3]) ? (int)$inPath[3] : 0;
		if (!empty($cateId)) {
			$attrRet = course_api::getAttrAndValueByCateId($cateId);
		} else {
			$this->setResultAjax(-2, '获取数据失败');
		}
		if (!empty($attrRet)) {
			foreach ($attrRet as $ro) {
				if ($ro->name_display == '科目') {
					foreach ($ro->attr_value as &$ao) {
						$ao->value_name = SLanguage::tr($ao->value_name, "course.list");
					}
					$ret = $ro;
					break;
				}
			}

			$this->setResultAjax(0, 'success', $ret);
		} else {
			$this->setResultAjax(-2, '获取数据失败');
		}
	}

	public function pageAddTemplateAjax($inPath)
	{
		$title = !empty($_POST['title']) ? $_POST['title'] : '';
		$result = new stdClass;
		if (empty($title)) {
			$result->error = "标题不能为空";
			return $result;
		}
		$type = !empty($_POST['set_url_name']) ? $_POST['set_url_name'] : '2';
		$set_url = !empty($_POST['set_url']) ? $_POST['set_url'] : '';
		$htype = !empty($_POST['recommend-img']) ? $_POST['recommend-img'] : 0;
		$picType    = isset($_POST['pic_type']) ? $_POST['pic_type'] : '0';
		
		if(!empty($picType)){
			$htype = 0;
		}
		if (isset($type) && $type == '1') {
			if (empty($set_url)) {
				$result->error = "URL地址链接不能为空";
				return $result;
			}
			preg_match("/^[a-zA-z]+:\/\/[\w.]+(yunke.com|gn100.com)+[^\s]*$/", $set_url, $matches);
			if (empty($matches[0])) {
				$result->error = "请使用本机构或云课链接地址~";
				return $result;
			}
		}
		if (isset($type) && $type == '2') {
			$set_url = '';
		}
		if (strlen(iconv('utf-8', 'gb2312', $title)) > 16) {
			$result->error = "标题不能超过8个汉字或16个英文字符";
			return $result;
		}
		$rowCount = !empty($_POST['rowCount']) ? (int)$_POST['rowCount'] : 1;
		$num = $this->orgInfo->is_pro == 1 ? 8 : 4;
		$recommend = !empty($_POST['recommend']) ? (int)$_POST['recommend'] : 0;
		if (empty($recommend) || !in_array($recommend, array(1, 2))) {
			$result->error = "非法参数";
			return $result;
		}
		//自动推荐
		$queryStr = '';
		$queryArr = array();
		$orderBy = 'create_time:desc';
		if ($recommend == 1) {
			$params = $_POST;
			$orderTemp = array(
				'crtdesc' => 'create_time:desc',
				'crtasc' => 'create_time:asc',
				'regdesc' => 'register:desc',
				'regasc' => 'register:asc'
			);
			$cateTemp = array('first_cate', 'second_cate', 'third_cate', 'course_type');
			foreach ($params as $pk => $pv) {
				if ($pk == 'attr_value_id' && !empty($pv)) {
					//$attrValueIds = implode('|',$pv);
					$queryStr .= $pk . ':' . $pv.',';
					$queryArr[$pk] = $pv;
				} elseif ($pk == 'fee_type') {
					$queryStr .= $pk . ':' . $pv . ',';
					$queryArr[$pk] = $pv;
				} elseif ($pk == 'order_by') {
					$orderBy = $orderTemp[$pv];
				} elseif (in_array($pk, $cateTemp) && !empty($pv)) {
					$queryStr .= $pk . ':' . $pv . ',';
					$queryArr[$pk] = $pv;
				}
			}
			
			$queryStr = rtrim($queryStr, ',');
			//判断此搜索条件下是否有课程
			$queryArr['user_id'] = $this->orgOwner;
			$queryArr['admin_status'] = 1;
			//查询排序
			if (isset($params['fee_type']) && $params['fee_type'] == 2) {
				$queryArr['fee_type'] = "0,1";
			}
			$obArr = explode(':', $orderBy);
			$orderArr = array($obArr[0] => $obArr[1]);
			$seekArr = array(
				"f" => array(
					'course_id',
				),
				"q" => $queryArr,
				"ob" => $orderArr,
				"p" => 1,
				"pl" => $rowCount * $num,
			);

			$seekRet = seek_api::seekcourse($seekArr);
			if (empty($seekRet->data)) {
				$result->error = "您还没有创建此类型的课程";
				return $result;
			}
		}
		//手动推荐
		$courseIds = '';
		if ($recommend == 2) {
			$rowCount = 0;
			$queryStr = '';
			$orderBy = '';
			$courseIds = '';

		}
		//查询当前排序最大值
		$maxSort = org_api::getOrgTemplateMaxSort($this->orgOwner);
		$params = array(
			'title' => $title,
			'fk_user_owner' => $this->orgOwner,
			'row_count' => $rowCount,
			'recommend' => $recommend,
			'query_str' => $queryStr,
			'order_by' => $orderBy,
			'sort' => $maxSort + 1,
			'course_ids' => $courseIds,
			'create_time' => date('Y-m-d H:i:s'),
			'last_updated' => date('Y-m-d H:i:s'),
			'set_url' => $set_url,
			"type" => $htype,
		);
		$r = user_organization::addOrgTemplate($params);
		if (!empty($r)) {
			$result->status = "Success!";
			return $result;
		} else {
			$result->error = "添加失败!";
			return $result;
		}
	}

	public function pageUpdateTemplateAjax($inPath)
	{
		$tid = !empty($_POST['tid']) ? $_POST['tid'] : 0;
		//判断能否修改
		$courseIds = '';
		$template = user_organization::getOrgTemplateInfo($tid);
		$recommend = !empty($_POST['recommend']) ? (int)$_POST['recommend'] : 0;
		
		$idArr = !empty($template->course_arr) ? $template->course_arr : '';
		if (!empty($idArr)) {
            $courseIds 		= implode(",", $idArr);
			if($recommend==1){
				$courseIds  = '';
			}
		}
		$result = new stdClass;
		if (empty($template) || $template->owner_id != $this->orgOwner) {
			$result->error = "模版不存在或无权限修改";
			return $result;
		}
		$title = !empty($_POST['title']) ? $_POST['title'] : '';
		if (empty($title)) {
			$result->error = "标题不能为空";
			return $result;
		}
		if (strlen(iconv('utf-8', 'gb2312', $title)) > 16) {
			$result->error = "标题不能超过8个汉字或16个英文字符";
			return $result;
		}
		$type = !empty($_POST['set_url_name']) ? $_POST['set_url_name'] : '2';
		$set_url = !empty($_POST['set_url']) ? $_POST['set_url'] : '';
		$htype = !empty($_POST['recommend-img']) ? $_POST['recommend-img'] : 0;
		$lthumb = '';
		$lturl = '';
		$rthumb = '';
		$rturl = '';
		if ($htype == 1) {
			if (!empty($template->thumb_left)) {
				$lthumb = !empty($template->thumb_left) ? $template->thumb_left : '';
				$lturl = !empty($template->thumb_left_url) ? $template->thumb_left_url : '';
			}
			if (!empty($template->thumb_right)) {
				$lthumb = !empty($template->thumb_right) ? $template->thumb_right : '';
				$lturl = !empty($template->thumb_right_url) ? $template->thumb_right_url : '';
			}
		}
		if ($htype == 2) {
			if (!empty($template->thumb_left)) {
				$rthumb = !empty($template->thumb_left) ? $template->thumb_left : '';
				$rturl = !empty($template->thumb_left_url) ? $template->thumb_left_url : '';
			}
			if (!empty($template->thumb_right)) {
				$rthumb = !empty($template->thumb_right) ? $template->thumb_right : '';
				$rturl = !empty($template->thumb_right_url) ? $template->thumb_right_url : '';
			}
		}

		if (isset($type) && $type == '1') {
			if (empty($set_url)) {
				$result->error = "URL地址链接不能为空";
				return $result;
			}
			preg_match("/^[a-zA-z]+:\/\/[\w.]+(yunke.com|gn100.com)+[^\s]*$/", $set_url, $matches);
			if (empty($matches[0])) {
				$result->error = "请使用本机构或云课链接地址~";
				return $result;
			}
		}
		if (isset($type) && $type == '2') {
			$set_url = '';
		}
		$rowCount = !empty($_POST['rowCount']) ? (int)$_POST['rowCount'] : 1;
		$num = $this->orgInfo->is_pro == 1 ? 8 : 4;
		
		if (empty($recommend) || !in_array($recommend, array(1, 2))) {
			$result->error = "非法参数";
			return $result;
		}
		//自动推荐
		$queryStr = '';
		$orderBy = 'create_time:desc';
		$queryArr = array();
		if ($recommend == 1) {
			$params = $_POST;
			$orderTemp = array(
				'crtdesc' => 'create_time:desc',
				'crtasc' => 'create_time:asc',
				'regdesc' => 'register:desc',
				'regasc' => 'register:asc'
			);
			$cateTemp = array('first_cate', 'second_cate', 'third_cate', 'course_type');
			foreach ($params as $pk => $pv) {
				if ($pk == 'attr_value_id' && !empty($pv)) {
					$queryStr .= $pk . ':' . $pv . ',';
					$queryArr[$pk] = $pv;
				} elseif ($pk == 'fee_type') {
					$queryStr .= $pk . ':' . $pv . ',';
					$queryArr[$pk] = $pv;
				} elseif ($pk == 'order_by') {
					$orderBy = $orderTemp[$pv];
				} elseif (in_array($pk, $cateTemp) && !empty($pv)) {
					$queryStr .= $pk . ':' . $pv . ',';
					$queryArr[$pk] = $pv;
				}
			}
			$queryStr = rtrim($queryStr, ',');
			//判断此搜索条件下是否有课程
			$queryArr['user_id'] = $this->orgOwner;
			$queryArr['admin_status'] = 1;
			if (isset($params['fee_type']) && $params['fee_type'] == 2) {
				$queryArr['fee_type'] = "0,1";
			}
        $obArr = explode(':', $orderBy);
        $orderArr = array($obArr[0] => $obArr[1]);
        $seekArr = array(
            "f" => array(
                'course_id',
            ),
            "q" => $queryArr,
            "ob" => $orderArr,
            "p" => 1,
            "pl" => $rowCount * $num,
        );
        $seekRet = seek_api::seekcourse($seekArr);
        if (empty($seekRet->data)) {
            $result->error = "您还没有创建此类型的课程";
            return $result;
        }
    }
    //手动推荐
    $params = array(
        'title' => $title,
        'row_count' => $rowCount,
        'recommend' => $recommend,
        'query_str' => $queryStr,
        'order_by' => $orderBy,
        'course_ids' => $courseIds,
        'last_updated' => date('Y-m-d H:i:s'),
        'set_url' => $set_url,
        'type' => $htype,
        'thumb_left' => $lthumb,
        'thumb_left_url' => $lturl,
        'thumb_right' => $rthumb,
        'thumb_right_url' => $rturl,
    );
    $r = user_organization::updateOrgTemplate($tid, $params);
    if (!empty($r)) {
        $result->status = "Success!";
        return $result;
    } else {
        $result->error = "修改失败!";
        return $result;
    }
}

public function pageAddRowsAjax($inPath){
    $tid=!empty($_POST['tid'])?$_POST['tid']:0;
    //判断能否修改
    $template=user_organization::getOrgTemplateInfo($tid);
    $result=new stdClass;
    if(empty($template)||$template->owner_id!=$this->orgOwner){
        $result->error="模版不存在或无权限修改";
        return $result;
    }

    $queryArr=array();
    if($template->query_arr){
        foreach($template->query_arr as $qk=>$qv){
            if($qk=='grade_id'&&$qv==0){continue;}
            if($qk=='subject_id'&&$qv==0){continue;}
        $queryArr[$qk]=$qv;
        }
    }
    if($template->query_arr->fee_type=="2"){
        $template->query_arr->fee_type = "0,1";
    }
    $queryArr['fee_type'] = $template->query_arr->fee_type;
    $queryArr['user_id']=$this->orgOwner;
    $queryArr['admin_status']=1;
    $rowCount=$template->row_count+1;
    $seekArr = array(
        "f"=>array(
            'course_id',
            'title',
            'thumb_big',
        ),
        "q" => $queryArr,
        "p"=>$rowCount,
        "pl"=>4,
    );

    $seekRet=seek_api::seekcourse($seekArr);
    $courses=$seekRet->data;
    if(empty($courses)){
        $result->error="课程数量不足，无法添加";
        return $result;
    }
    $params=array(
            'row_count'=>$rowCount,
            'last_updated'=>date('Y-m-d H:i:s'),
        );
    $r=user_organization::updateOrgTemplate($tid,$params);
    if(!empty($r)){
        foreach($courses as $ck=>$cv){
            $courses[$ck]->thumb_big=utility_cdn::file($cv->thumb_big);
        }
        return $courses;
    }else{
        $result->error="修改失败!";
        return $result;
    }
}
public function pageIframeTemplateCourse($inPath){
    $top 			= !empty($inPath[3]) ? $inPath[3] : 0;
    $templateId		= !empty($inPath[4]) ? $inPath[4] : 0;
    $page 			= isset($_GET['page']) ? $_GET['page']:1;
    $length  		= 200;
    $data 			= array();
    $data["type"] 	= array(1,2,3);//全部课程
    $data["admin_status"] = "1";
    $data["shelf"] 	= 1;
    $data["create_time"] = "desc";
    $courseRet  	= course_api::getCourselistByOid($page, $length, $this->orgOwner, $data);
    $courseList 	= !empty($courseRet->data) ? $courseRet->data : '';
    $this->assign('top', $top);
    $this->assign('courseList', $courseList);
    //分销课程获取
    $condition = array("con" => "t_course_promote.ver=t_course_resell.ver AND   t_course_promote.status=1  AND t_course_resell.status=1", "fk_org_resell" => $this->orgInfo->oid);
    $salesInfo = course_resell_api::getSalesCourse($page, $length, $condition);
    if (!empty($salesInfo)) {
        foreach ($salesInfo as $k => $v) {
            $course[$k] = $v->fk_course;
        }
        $idStr = implode(",", $course);
        $fields = array("course_id", "org_subname", "title", "create_time", "desc", "start_time", "thumb_big",
            "thumb_med", "thumb_sma", "is_promote", "org_id",
        );
        $query = [
            'course_id' => $idStr,
        ];
        $params = [
            "f" => $fields,
            "q" => $query,
            "p" => $page,
            "pl" => $length,
            "ob" => 'desc'
        ];
        $resCourse = seek_api::seekcourse($params);
    }
    $salseList = !empty($resCourse->data) ? $resCourse->data : '';
    $this->assign('salseList', $salseList);
    $this->assign('tid', $templateId);
    $this->render('org/iframe.template.course.html');
}

public function pageIframeTemplateCourseEdit($inPath)
{
    $top = $inPath[3];
    $templateId = $inPath[4];
    $courseId = !empty($inPath[5]) ? $inPath[5] : 0;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $length = 200;
    $data = array();
    $data["type"] = array(1, 2, 3);//全部课程
    $data["admin_status"] = "1";
    $data["shelf"] = 1;
    $data["create_time"] = "desc";
    $courseRet = course_api::getCourselistByOid($page, $length, $this->orgOwner, $data);
    $courseList = !empty($courseRet->data) ? $courseRet->data : '';
    $this->assign('top', $top);
    $this->assign('courseList', $courseList);
    $this->assign('courseId', $courseId);
    //分销课程获取
    $condition = array("con" => "t_course_promote.ver=t_course_resell.ver AND   t_course_promote.status=1  AND t_course_resell.status=1", "fk_org_resell" => $this->orgInfo->oid);
    $salesInfo = course_resell_api::getSalesCourse($page, $length, $condition);
    if (!empty($salesInfo)) {
        foreach ($salesInfo as $k => $v) {
            $course[$k] = $v->fk_course;
        }
        $idStr = implode(",", $course);
        $fields = array("course_id", "org_subname", "title", "create_time", "desc", "start_time", "thumb_big",
            "thumb_med", "thumb_sma", "is_promote", "org_id",
        );
        $query = [
            'course_id' => $idStr,
        ];
        $params = [
            "f" => $fields,
            "q" => $query,
            "p" => $page,
            "pl" => $length,
            "ob" => 'desc'
        ];
        $resCourse = seek_api::seekcourse($params);
    }
    $fields = ["course_id", "title", "is_promote", "org_id"];
    $query = ['course_id' => $courseId];
    $params = [
        "f" => $fields,
        "q" => $query,
        "p" => $page,
        "pl" => $length,
        "ob" => 'desc'
    ];
    $selected = seek_api::seekcourse($params);
    //echo "<pre>";print_r($selected);die;
    $salseList = !empty($resCourse->data) ? $resCourse->data : '';
    $this->assign('salseList', $salseList);
    $this->assign('selected', !empty($selected->data) ? $selected->data : '');
    $this->assign('tid', $templateId);
    $this->render('org/iframe.template.course.edit.html');
}

public function pagesearchCourse($inPath)
{
    $title = !empty($_POST['title']) ? $_POST['title'] : '';
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $length = 200;
    $data = array();
    $data["shelf"] = 1;
    $data["create_time"] = "desc";
    $courseRet = course_api::getCourselistByOid($page, $length, $this->orgOwner, $data);
    $courseList = !empty($courseRet->data) ? $courseRet->data : '';
    if (!empty($courseList)) {
        $ret = array('code' => 0, 'data' => $courseList);
    } else {
        $ret = array('code' => -1, 'data' => '');
    }
    return json_encode($ret);
}

//删除手动模板中课程
public function pagedelTemplateOfCourse()
{
    $result = new stdClass;
    $cid = !empty($_POST['cid']) ? (int)$_POST['cid'] : 0;
    $tid = !empty($_POST['tid']) ? (int)$_POST['tid'] : 0;
    $templates = user_organization::getOrgTemplateInfo($tid);
    if (empty($cid) || empty($tid)) {
        $result->error = "参数错误";
        return $result;
    }
    if (!empty($templates->course_arr) && in_array($cid, $templates->course_arr)) {
        $arr1 = [];
        $arr1[] = $cid;
        $courseIdArr = array_diff($templates->course_arr, $arr1);
        $courseStr = implode(",", $courseIdArr);
        $params = array(
            'row_count' => 0,
            'course_ids' => $courseStr,
            'last_updated' => date('Y-m-d H:i:s'),
        );
        $data = user_organization::updateOrgTemplate($_POST['tid'], $params);
        if ($data > 0) {
            $result->code = 200;
            $result->success = "删除成功";
            return $result;
        } else {
            $result->code = -200;
            $result->success = "删除失败";
            return $result;
        }
    }

}

public function pageAddTemplateCourseAjax($inPath)
{
    $result = new stdClass;
    if (empty($_POST['tid']) || empty($_POST['cid'])) {
        $result->error = "课程不能为空或模板错误";
        return $result;
    } else {
        //判断能否修改
        $template = user_organization::getOrgTemplateInfo($_POST['tid']);
        if (empty($template) || $template->owner_id != $this->orgOwner) {
            $result->error = "模版不存在或无权限修改";
            return $result;
        }
        //判断课程是否已设置
        if (!empty($template->course_arr)) {
            $course_ids = array_merge($template->course_arr, $_POST['cid']);
        } else {
            $course_ids = $_POST['cid'];
        }
        $cArr = array_unique($course_ids);
        $course_ids = implode(',', $cArr);
        if (!empty($template->course_arr) && count($template->course_arr) > 0) {
            $count = count($template->course_arr);
        } else {
            $count = 1;
        }
        //重新计算行数
        $rowCount = ceil($count / 4);
        $params = array(
            'row_count' => $rowCount,
            'course_ids' => $course_ids,
            'last_updated' => date('Y-m-d H:i:s'),
        );
        $r = user_organization::updateOrgTemplate($_POST['tid'], $params);
        if ($r) {
            $result->status = "Success!";
            return $result;
        } else {
            $result->error = "修改失败!";
            return $result;
        }
    }
}

public function pageupdateTemplateCourseAjax($inPath)
{
    $result = new stdClass;
    if (count($_POST['cid']) > 1) {
        $result->error = "只能选择一个课程";
        return $result;
    }
    $oldCourse = !empty($_POST['oldCourse']) ? $_POST['oldCourse'] : 0;

    if (empty($_POST['tid']) || empty($_POST['cid'])) {
        $result->error = "课程不能为空或模板错误";
        return $result;
    } else {
        //判断能否修改
        $template = user_organization::getOrgTemplateInfo($_POST['tid']);
        if (empty($template) || $template->owner_id != $this->orgOwner) {
            $result->error = "模版不存在或无权限修改";
            return $result;
        }
        //判断课程是否已设置
        if (!empty($template->course_arr)) {
            $cids = implode(',', $template->course_arr);
            $course_ids = str_replace($oldCourse, implode(",", $_POST['cid']), $cids);
        } else {
            $course_ids = implode(",", $_POST['cid']);
        }
        if (!empty($template->course_arr) && count($template->course_arr) > 0) {
            $count = count($template->course_arr);
        } else {
            $count = 1;
        }
        //重新计算行数
        $rowCount = ceil($count / 4);
        $params = array(
            'row_count' => $rowCount,
            'course_ids' => $course_ids,
            'last_updated' => date('Y-m-d H:i:s'),
        );
        $r = user_organization::updateOrgTemplate($_POST['tid'], $params);
        if ($r) {
            $result->status = "Success!";
            return $result;
        } else {
            $result->error = "修改失败!";
            return $result;
        }
    }
}
/**
 * 模板修改课程
 */

/**
 * 自定义首页
 * @author Panda <zhangtaifeng@gn100.com>
 */
public function pageDeleteTemplateAjax($inPath)
{
    $result = new stdClass;
    if (empty($_POST['tid'])) {
        $result->error = "参数错误";
        return $result;
    }
    //判断能否修改
    $template = user_organization::getOrgTemplateInfo($_POST['tid']);
    if (empty($template) || $template->owner_id != $this->orgOwner) {
        $result->error = "模版不存在或无权限删除";
        return $result;
    }
    $r = user_organization::deleteOrgTemplate((int)$_POST['tid']);
    if ($r) {
        $result->status = "Success!";
        return $result;
    } else {
        $result->error = "删除失败!";
        return $result;
    }
}

//删除公告
public function pageDelNoticeAjax($inPath)
{
    $result = new stdclass;
    $user_id = $this->orgOwner;
    if (empty($_REQUEST['nid'])) {
        $result->error = "参数错误";
        return $result;
    }
    $notice_info = user_api::getNotice($_REQUEST['nid']);
    if (empty($notice_info) || $notice_info->fk_user_id != $user_id) {
        $result->error = "非法操作";
        return $result;
    }
    $r = user_api::delNotice($_REQUEST['nid']);
    if ($r) {
        $result->status = "Success!";
        return $result;
    } else {
        $result->error = "删除失败!";
        return $result;
    }

}

//置顶公告
public function pageTopNoticeAjax($inPath)
{
    $result = new stdclass;
    $user_id = $this->orgOwner;
    if (empty($_REQUEST['nid'])) {
        $result->error = "参数错误";
        return $result;
    }
    $notice_info = user_api::getNotice($_REQUEST['nid']);
    if (empty($notice_info) || $notice_info->fk_user_id != $user_id) {
        $result->error = "非法操作";
        return $result;
    }
    $r = user_api::topNotice($_REQUEST['nid'], $user_id);
    if ($r) {
        $result->status = "Success!";
        return $result;
    } else {
        $result->error = "置顶失败!";
        return $result;
    }

}

//取消置顶公告
public function pageDownNoticeAjax($inPath)
{
    $result = new stdclass;
    $user_id = $this->orgOwner;
    if (empty($_REQUEST['nid'])) {
        $result->error = "参数错误";
        return $result;
    }
    $notice_info = user_api::getNotice($_REQUEST['nid']);
    if (empty($notice_info) || $notice_info->fk_user_id != $user_id) {
        $result->error = "非法操作";
        return $result;
    }
    $r = user_api::noTopNotice($_REQUEST['nid']);
    if ($r) {
        $result->status = "Success!";
        return $result;
    } else {
        $result->error = "取消置顶失败!";
        return $result;
    }

}

//模版上移
public function pageMoveUpAjax($inPath)
{
    $result = new stdClass;
    if (empty($_POST['tid'])) {
        $result->error = "参数错误";
        return $result;
    } else {
        $tid = $_POST['tid'];
        $old = !empty($_POST['old']) ? $_POST['old'] : 0;
        $sort = !empty($_POST['sort']) ? (int)$_POST['sort'] : 0;
        if ($sort < 1) {
            $sort = 1;
        } else {
            $sort = $sort + 1;
        }
        //判断能否修改
        $template = user_organization::getOrgTemplateInfo($tid);
        if (empty($template) || $template->owner_id != $this->orgOwner) {
            $result->error = "模版不存在或无权限修改";
            return $result;
        }
        //设置新模版
        $newArr = array(
            'sort' => $sort - 1,
            'last_updated' => date('Y-m-d H:i:s'),
        );
        $r1 = user_organization::updateOrgTemplate($tid, $newArr);
        //修改老模版
        $oldArr = array(
            'sort' => $sort,
            'last_updated' => date('Y-m-d H:i:s'),
        );
        $r2 = user_organization::updateOrgTemplate($old, $oldArr);
        if (!empty($r1) && !empty($r2)) {
            $result->status = "Success!";
            return $result;
        } else {
            $result->error = "修改失败!";
            return $result;
        }
    }
}

//模版下移
public function pageMoveDownAjax($inPath)
{
    $result = new stdClass;
    if (empty($_POST['tid'])) {
        $result->error = "参数错误";
        return $result;
    } else {
        $tid = $_POST['tid'];
        $sort = !empty($_POST['sort']) ? $_POST['sort'] : 0;
        $old = !empty($_POST['old']) ? (int)$_POST['old'] : 0;
        if ($sort < 1) {
            $sort = 1;
        } else {
            $sort = $sort + 1;
        }
        //判断能否修改
        $template = user_organization::getOrgTemplateInfo($tid);
        if (empty($template) || $template->owner_id != $this->orgOwner) {
            $result->error = "模版不存在或无权限修改";
            return $result;
        }
        //设置新模版
        $newArr = array(
            'sort' => $sort + 1,
            'last_updated' => date('Y-m-d H:i:s'),
        );
        $r1 = user_organization::updateOrgTemplate($tid, $newArr);
        //修改老模版
        $oldArr = array(
            'sort' => $sort,
            'last_updated' => date('Y-m-d H:i:s'),
        );
        $r2 = user_organization::updateOrgTemplate($old, $oldArr);
        if (!empty($r1) && !empty($r2)) {
            $result->status = "Success!";
            return $result;
        } else {
            $result->error = "修改失败!";
            return $result;
        }
    }
}

//保存排序
public function pageSaveSortAjax($inPath)
{
    $result = new stdClass;
    if (empty($_POST['tid']) || empty($_POST['cids'])) {
        $result->error = "参数错误";
        return $result;
    } else {
        $tid = $_POST['tid'];
        $cids = trim($_POST['cids'], ',');
        //判断能否修改
        $template = user_organization::getOrgTemplateInfo($tid);
        if (empty($template) || $template->owner_id != $this->orgOwner) {
            $result->error = "模版不存在或无权限修改";
            return $result;
        }
        //保存排序
        $newArr = array(
            'course_ids' => $cids,
            'last_updated' => date('Y-m-d H:i:s'),
        );

        $r = user_organization::updateOrgTemplate($tid, $newArr);
        if (!empty($r)) {
            $result->status = "Success!";
            return $result;
        } else {
            $result->error = "修改失败!";
            return $result;
        }
    }
}

//公告列表
public function pageactiveNoticeList($inPath)
{
    $num = 20;
    $cateId = isset($_GET['c']) ? (int)$_GET['c'] : 0;
    $cate_name = isset($_GET['cate_name']) ? trim($_GET['cate_name']) : '';
    $path = "/org.main.activeNoticeList?c=" . $cateId . "";
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $noticeList = user_api::getNoticeList($page, $num, $this->orgOwner, $cateId, $this->orgInfo->oid);
    $data = array("fk_org" => $this->orgInfo->oid);
    $cateList = org_api::noticeCategoryList($data);
    $this->assign('cateList', $cateList);
    $this->assign('cate_name', $cate_name);
    $this->assign('noticeList', $noticeList);
    $this->assign('path', $path);
    $this->assign('length', $num);
    $this->assign('page', $page);
    return $this->render("org/noticeList.html");
}

public function pageactiveNoticeAdd($inPath)
{
    $data = array("fk_org" => $this->orgInfo->oid);
    $cateList = org_api::noticeCategoryList($data);
    $this->assign('cateList', $cateList);
    return $this->render("org/noticeAdd.html");
}

public function pageactiveNoticeEdit($inPath)
{
    $nid = !empty($inPath[3]) ? $inPath[3] : 0;
    $notice = user_api::getNotice($nid);
    $data = array("fk_org" => $this->orgInfo->oid);
    $cateList = org_api::noticeCategoryList($data);
    $this->assign('cateList', $cateList);
    $this->assign('notice', $notice);
    return $this->render("org/noticeEdit.html");
}

//显示分类
public function pagenoticeAddCate($inPath)
{
    $data = array("fk_org" => $this->orgInfo->oid);
    $cateList = org_api::noticeCategoryList($data);
    $this->assign('cateList', $cateList);
    return $this->render("org/noticeAddCate.html");
}

//增加文章资讯分类
public function pageajaxAddCate($inPath)
{
    $name = !empty($_POST['catename']) ? trim($_POST['catename']) : '';
    $data = array("name" => $name,
        "fk_org" => $this->orgInfo->oid,
        "fk_user" => $this->user['uid']
    );
    $addCate = org_api::ajaxAddNoticeCategory($data);
    if ($addCate->result->code > 0) {
        $result = array("status" => 100, "data" => $addCate->result->data, "msg" => "添加成功");
    } else {
        $result = array("status" => -1, "msg" => "添加失败");
    }
    echo json_encode($result);
}

public function pageupdateNoticeCate()
{
    $data = array();
    $data = array("pk_cate" => !empty($_POST['cid']) ? $_POST['cid'] : '',
        "name" => !empty($_POST['cate_name']) ? $_POST['cate_name'] : '',
        "fk_org" => $this->orgInfo->oid,
        "fk_user" => $this->user['uid']
    );
    $cateInfo = org_api::getCateNameInfo($data);
    if ($cateInfo->result->code > 0) {
        $info = org_api::updateNoticeCate($data);
        $result = array("status" => 100, "msg" => "修改成功");
    } else {
        $result = array("status" => -1, "msg" => "修改失败");
    }
    echo json_encode($result);
}

public function pagedelnoticeCateInfo()
{
    $data = array();
    $data = array("pk_cate" => !empty($_POST['cid']) ? $_POST['cid'] : '',
        "fk_org" => $this->orgInfo->oid,
        "fk_user_id" => $this->orgOwner
    );
    $delInfo = org_api::delnoticeCateInfo($data);
    if ($delInfo->result->code > 0) {
        $result = array("status" => 100, "msg" => "删除成功");
    } else {
        $result = array("status" => -1, "msg" => "删除失败");
    }
    echo json_encode($result);
}

/*
 *页面设置--增加快捷导航
 */
public function pageaddOrgOfNav()
{
    $data = array();
    $result = new stdClass;
    $data = array(
        "nav_name" => !empty($_POST['nav_name']) ? $_POST['nav_name'] : '',
        "url" => !empty($_POST['nav_url']) ? $_POST['nav_url'] : '',
        "fk_org" => $this->orgInfo->oid
    );
    if (empty($_POST['nav_name'])) {
        $result->code = -100;
        $result->msg = "标题不能为空";
        return $result;
    }
    if (empty($_POST['nav_url'])) {
        $result->code = -100;
        $result->msg = "链接不能为空";
        return $result;
    }
    if (preg_match("/^[a-zA-z]+:\/\/[^\s]*$/", $_POST['nav_url']) == false) {
        $result->code = -100;
        $result->msg = "链接地址格式错误";
        return $result;
    }
    $nav = user_organization::addOrgOfNav($data);
    if ($nav > 0) {
        $result->code = 100;
    }
    return $result;
}

/*
 *页面设置--快捷导航删除
 */
public function pagedelOrgOfNav()
{
    $data = array();
    $result = new stdClass;
    $data = array(
        "pk_nav_id" => !empty($_POST['nid']) ? $_POST['nid'] : '',
        "fk_org" => $this->orgInfo->oid
    );
    if (empty($_POST['nid'])) {
        $result->code = -100;
        $result->msg = "参数错误~";
        return $result;
    }
    $info = user_organization::delOrgOfNav($data);
    if ($info > 0) {
        $result->code = 100;
    }
    return $result;
}

public function pageupdateOrgOfNavOneInfo()
{
    $result = new stdClass;
    $data = array(
        "pk_nav_id" => !empty($_POST['nid']) ? $_POST['nid'] : '',
        "fk_org" => $this->orgInfo->oid,
        "nav_name" => !empty($_POST['nav_name']) ? $_POST['nav_name'] : '',
        "url" => !empty($_POST['nav_url']) ? $_POST['nav_url'] : '',
    );
    if (empty($_POST['nav_name'])) {
        $result->code = -100;
        $result->msg = "标题不能为空";
        return $result;
		}
		if (empty($_POST['nav_url'])) {
			$result->code = -100;
			$result->msg = "链接不能为空";
			return $result;
		}
		if (preg_match("/^[a-zA-z]+:\/\/[^\s]*$/", $_POST['nav_url']) == false) {
			$result->code = -100;
			$result->msg = "链接地址格式错误";
			return $result;
		}
		$info = user_organization::updateOrgOfNavOneInfo($data);
		if (!empty($info)) {
			$result->msg = "修改成功";
			$result->code = 100;
		} else {
			$result->msg = "修改有误";
			$result->code = -100;
		}
		return $result;
	}

	//模糊搜索课程
	private function likeCourse($title)
	{
		$data = array();
		if (empty($title)) return $data;
		$params = [
			'q' => ['search_field' => $title,'deleted'=>'0,-1'],
			'f' => ['course_id']
		];
		$courseRes = seek_api::seekcourse($params);
		$courseIds = '';
		if (!empty($courseRes->data)) {
			foreach ($courseRes->data as $val) {
				$courseIds .= $val->course_id . ',';
			}
			$data = trim($courseIds, ',');
		}
		return $data;
	}

	/*分销-我推广的课程*/
	public function pagePlace($inPath)
	{
		return $this->render("/org/place.list.html");
	}

	/*分销-我引入的课程*/
	public function pageMySpread($inPath)
	{
		return $this->render("/org/my.spread.html");
	}

	/*分销-推广中心*/
	public function pageSpread($inPath)
	{
		return $this->render("/org/spread.list.html");
	}

	/*分销-推广明细*/
	public function pageSpreadInfo($inPath)
	{
		return $this->render("/org/spread.info.html");
	}

	/**
	 * 机构课程自定义模板首页发布
	 * @param  int $oid
	 * @return array $result
	 * @author peng
	 */
	public function pagesetTemplate()
	{
		$result = new stdClass;
		$data = array();
		$info = '';
		$owerId = isset($this->orgInfo->user_owner_id) ? $this->orgInfo->user_owner_id : 0;
		if (!empty($_POST['template_id'])) {
			foreach ($_POST['template_id'] as $k => $v) {
				$data[$k]['pk_template'] = isset($_POST['template_id'][$k]) ? $_POST['template_id'][$k] : '';
				$data[$k]['fk_user_owner'] = isset($this->orgInfo->user_owner_id) ? $this->orgInfo->user_owner_id : 0;
				$data[$k]['title'] = isset($_POST['title'][$k]) ? $_POST['title'][$k] : '';
				$data[$k]['row_count'] = isset($_POST['row_count'][$k]) ? $_POST['row_count'][$k] : '';
				$data[$k]['recommend'] = isset($_POST['recommend'][$k]) ? $_POST['recommend'][$k] : '';
				$data[$k]['query_str'] = isset($_POST['query_str'][$k]) ? $_POST['query_str'][$k] : '';
				$data[$k]['order_by'] = isset($_POST['order_by'][$k]) ? $_POST['order_by'][$k] : '';
				$data[$k]['course_ids'] = isset($_POST['course_ids'][$k]) ? $_POST['course_ids'][$k] : '';
				$data[$k]['sort'] = isset($_POST['sort'][$k]) ? $_POST['sort'][$k] : '';
				$data[$k]['create_time'] = isset($_POST['create_time'][$k]) ? $_POST['create_time'][$k] : '';
				$data[$k]['last_updated'] = date("Y-m-d H:i:s");
				$data[$k]['set_url'] = isset($_POST['set_url'][$k]) ? $_POST['set_url'][$k] : '';
				$data[$k]['type'] = isset($_POST['type'][$k]) ? $_POST['type'][$k] : '';
				$data[$k]['thumb_left'] = isset($_POST['thumb_left'][$k]) ? $_POST['thumb_left'][$k] : '';
				$data[$k]['thumb_right'] = isset($_POST['thumb_right'][$k]) ? $_POST['thumb_right'][$k] : '';
				$data[$k]['thumb_left_url'] = isset($_POST['thumb_left_url'][$k]) ? $_POST['thumb_left_url'][$k] : '';
				$data[$k]['thumb_right_url'] = isset($_POST['thumb_right_url'][$k]) ? $_POST['thumb_right_url'][$k] : '';
			}
			$templateId = array();
			$template = user_organization::getOrgTemplate($owerId);
			if (!empty($template)) {
				foreach ($template as $m => $n) {
					$templateId[] = $n->template_id;
				}
			}
			$delInfo = array_diff($templateId, $_POST['template_id']);
			$idStr = implode(",", $delInfo);
			$idArr = array("fk_template" => $idStr);
			$condition = array("fk_org_resell" => $this->orgInfo->oid);
			$salesInfo = course_resell_api::getSalesCourse($page = 1, $length = 0, $condition);
			$resell = array();
			if (!empty($salesInfo)) {
				foreach ($salesInfo as $key => $val) {
					if($val->restatus == "-1"){
						$resell[] = $val->fk_course;
					}
					
				}
			}
			user_organization::deleteOrgTemplateMoreInfo($idArr);
			foreach ($data as $a => $b) {
				$template = user_organization::getTemplateData($b['pk_template'], $b['fk_user_owner']);
				if (empty($b['course_ids'])&&$b['recommend']=='2') {
					$result->code  = "-101";
					$result->error = "请在空模板中添加课程";
					return $result;
				}elseif(!empty($b['course_ids'])&&$b['recommend']=='2'){
					$courseIdArr = explode(",",$b['course_ids']);
					if(empty(array_diff($courseIdArr,$resell))){
						$result->code  = "-101";
						$result->error = "请在空模板中添加课程";
						return $result;
					}
				}
				if (!empty($template)) {
					$info = user_organization::updateTemplateData($b);
				} else {
					$info = user_organization::addTemplateData($b);
				}
			}
		}
		if ($info) {
			$result->code = 100;
			$result->msg = "发布成功";
			return $result;
		} else {
			$result->code = -100;
			$result->msg = "发布失败";
			return $result;
		}
	}

	public function pagewoIndex()
	{
		$result = new stdClass;
		$cateConf = SConfig::getConfig(ROOT_CONFIG . "/course.conf", "menu");
		//左侧导航
		$menu = !empty($cateConf->menu) ? $cateConf->menu : '';
		if (!empty($menu)) {
			$result->code = 100;
			$result->leftMenu = $menu;
			return $result;
		}
	}

	//增加左右图
	public function pageaddCourseImg($inPath)
	{
		$tid = !empty($inPath[3]) ? substr($inPath[3], 5) : 0;
		$template = user_organization::getOrgTemplateInfo($tid);
		$this->assign("tinfo", $template);
		return $this->render("/org/iframe.template.adimg.html");
	}

	public function pageupdateThumbPic()
	{
		//处理上传图片
		$result = new stdClass;
		$path = ROOT_WWW . "/upload/tmp";
		$thumb = !empty($_POST['pic_url']) ? $_POST['pic_url'] : '';
		if (!empty($thumb)) {
			preg_match("/^[a-zA-z]+:\/\/[\w.]+(yunke.com|gn100.com)+[^\s]*$/", $thumb, $matches);
			if (empty($matches[0])) {
				$result->code = -8;
				$result->error = "请使用本机构或云课链接地址~";
				return $result;
			}
		}
	    $filename = $path."/".$this->user['uid'].".org.jpg";
        if(is_file($filename)&&!empty($_REQUEST['w'])){
            list($width, $height, $type, $attr) = getimagesize($filename);
            $filename_dst = $path."/".$this->user['uid'].".dst.png";
            $targ_w = $_REQUEST['w'];
            $targ_h = $_REQUEST['h'];
            switch($type){
            case 1: $img_r = imagecreatefromgif($filename);break;
            case 2: $img_r = imagecreatefromjpeg($filename);break;
            default:
                $img_r = imagecreatefrompng($filename);
            }
            $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
            imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_REQUEST['w'],$_REQUEST['h']);
            $r = imagejpeg($dst_r, $filename_dst);
            if($r){
                $thumbnail = new SThumbnail($filename_dst, 230);
                $thumbnail->setMaxSize(230, 390);
                $filename_180 = utility_file::tempname("thumb");
                if($thumbnail->genFile($filename_180)===false){
                    $result->error = "不是有效果的图片";
                    return $result;
                };
                $file = utility_file::instance();
                $r1 = $file->upload($filename_180,user_api::getLoginUid(),"image","");
                if(empty($r1)){
                    $result->error = "图片处理失败";
                    return $result;
                }
                //图片尺寸都是一样的
                $_POST['thumb_small']=$r1->fid;
                $_POST['thumb_med']=$r1->fid;
                $_POST['thumb_big']=$r1->fid;
                unlink($filename);
                unlink($filename_dst);
                unlink($filename_180);
            }
        }
		if(isset($_POST['thumbType'])&&$_POST['thumbType']==2){
				if(!empty($_POST['thumb_big'])){
					$params = array(	"type"=>2,
										"thumb_right"=>!empty($_POST['thumb_big']) ? $_POST['thumb_big'] : '',
										"pk_template"=>$_POST['tpic'],
										"fk_user_owner"=> $this->orgOwner,
										"thumb_right_url"=>$thumb,
								);
				}else{
					$params = array(	"type"=>2,
										"pk_template"=>$_POST['tpic'],
										"fk_user_owner"=> $this->orgOwner,
										"thumb_right_url"=>$thumb,
								);
				}
		}elseif(isset($_POST['thumbType'])&&$_POST['thumbType']==1){
				if(!empty($_POST['thumb_big'])){
					$params = array(	"type"=>1,
										"thumb_left"=>!empty($_POST['thumb_big']) ? $_POST['thumb_big'] : '',
										"pk_template"=>$_POST['tpic'],
										"fk_user_owner"=>$this->orgOwner,
										"thumb_left_url"=>$thumb,
								);
				}else{
					$params = array(	"type"=>1,
										"pk_template"=>$_POST['tpic'],
										"fk_user_owner"=>$this->orgOwner,
										"thumb_left_url"=>$thumb,
								);
				}

		}

		$picInfo = user_organization::updateThumbPic($params);
		if($picInfo){
			$result->code = 100;
			$result->msg = "success";
		} else {
			$result->code = -100;
			$result->msg = "fialed";
		}
		return $result;
	}

	//获取列表
	public function pagexiaowoOrgList()
	{
		$data = array();
		$data['fk_org'] = !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$info = user_organization::xiaowoOrgList($data);
		$this->assign("bannerApp",$info);
	}

	//获取单条
	public function pagexiaowoOrgOneInfo()
	{
		$data = array();
		$data['fk_org'] = !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$data['pk_banner'] = !empty($_POST['banner_id']) ? (int)$_POST['banner_id'] : 0;
		$info = user_organization::xiaowoOrgOneInfo($data);
		return !empty($info) ? $this->setResult($info, 200, "查询成功") : $this->setResult('', -200, "查询为空");
	}

	//增加banner
	public function pageaddXiaowoOrg()
	{
		$result 			= new stdClass;
		$data 				= array();
		$data['fk_org'] 	= !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$data['types'] 		= !empty($_POST['type']) ? (int)$_POST['type'] : 1;
		$data['title'] 		= !empty($_POST['title']) ? $_POST['title'] : '';
		$data['thumb_app']  = !empty($_POST['thumb_app']) ? $_POST['thumb_app'] : '';
		$data['url'] = !empty($_POST['thumb_url']) ? $_POST['thumb_url'] : '';
		$data['thumb_ipad'] = !empty($_POST['thumb_ipad']) ? $_POST['thumb_ipad'] : '';
		if(!empty($_POST['thumb_url'])){
			preg_match("/^[a-zA-z]+:\/\/[\w.]+(yunke.com|gn100.com)+[^\s]*$/", $_POST['thumb_url'], $matches);
			if (empty($matches[0])) {
				$result->error = "请使用本机构或云课链接地址~";
				return $result;
			}
		}
		$result = user_organization::addXiaowoOrg($data);
		return !empty($result) ? $this->setResult($result, 200, "操作成功") : $this->setResult($result, -200, "操作失败");
	}

	//修改banner
	public function pageupdateXiaowoOrgBanner(){
		$result 			= new stdClass;
		$data 				= array();
		$bid 				= !empty($_POST['banner_id']) ? (int)$_POST['banner_id'] : 0;
		$data['fk_org'] 	= !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$data['title'] 		= !empty($_POST['title']) ? $_POST['title'] : '';
		$data['types'] 		= !empty($_POST['type']) ? (int)$_POST['type'] : 1;
		$data['thumb_app']  = !empty($_POST['thumb_app']) ? $_POST['thumb_app'] : '';
		$data['url']        = !empty($_POST['thumb_url']) ? $_POST['thumb_url'] : '';
		$data['thumb_ipad'] = !empty($_POST['thumb_ipad']) ? $_POST['thumb_ipad'] : '';
		if(empty($_POST['banner_id'])){
			$result->error = "id is not empty";
			return $result;
		}
		if(!empty($_POST['thumb_url'])){
			preg_match("/^[a-zA-z]+:\/\/[\w.]+(yunke.com|gn100.com)+[^\s]*$/", $_POST['thumb_url'], $matches);
			if (empty($matches[0])) {
				$result->error = "请使用本机构或云课链接地址~";
				return $result;
			}
		}
		$result = user_organization::updateXiaowoOrgBanner($bid, $data);
		return !empty($result) ? $this->setResult($result, 200, "操作成功") : $this->setResult($result, -200, "操作失败");
	}
	//删除手机banner
	public function pagedelXiaoWoOrgBanner(){
		$result 			= new stdclass;
		$data 				= array();
		$bid 				= !empty($_POST['banner_id']) ? (int)$_POST['banner_id'] : 0;
		$data['fk_org'] 	= !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$info 				= org_api::delXiaoWoOrgBanner($bid,$data);
		$result  			= $info;
		return $result;
	}
	//增加分类
	public function pageaddOrgCustomerCate()
	{
		$data 				= array();
		$data['cate_id']    = !empty($_POST['cate_name']) ? rtrim($_POST['cate_name'],",") : '';
		$data['fk_org']  	= !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$info 				= org_api::addCustomerCate($data);
		return !empty($info->result->code)&&$info->result->code >0 ? $this->setResult('', 200, "操作成功") : $this->setResult('', -200, "操作失败");
	}

	public function pagegetOrgCustomerCateList()
	{
		$result 		= new stdclass;
		$data['fk_org'] = !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$catId			= !empty($_POST['cate_id']) ? $_POST['cate_id'] : "";
		$orgCate 		= org_api::getOrgCustomerCateList($data['fk_org'],$this->orgOwner,$catId);
		$result  		= $orgCate;
		return $result;
	}


	public function pageOrderExcel($inPath)
	{
		$option = !empty($_POST) ? $_POST : $_GET;
		$page = !empty($option['page']) ? (int)$option['page'] : 1;
		$length = 1000;
		$type = false;
		$flag = !empty($option['shelf']) ? $option['shelf'] : 'all';
		$path = 'org.main.order?shelf=' . $flag;
		$conf = array('expired' => '-2,-4', 'initial' => '0,1', 'success' => '2');
		$params['orgId'] = $this->orgId;
		if (isset($conf[$flag])) {
			$params['status'] = $conf[$flag];
		}

		//关键字筛选
		$search = !empty($option['search']) ? (int)$option['search'] : 0;
		$keywords = !empty($option['keywords']) ? trim($option['keywords']) : '';
		$discount = isset($option['discount']) ? (int)$option['discount'] : 2;
		$orderType = !empty($option['orderType']) ? (int)$option['orderType'] : 0;
		if (!empty($keywords) && !empty($search)) {
			if ($search == 1) {
				//订单号筛选
				$params['orderSn'] = $keywords;
			} elseif ($search == 2) {
				//课程名筛选
				$params['title'] = $keywords;
				$params['orderType'] = 1;
				$params['objectId'] = $this->likeCourse($keywords);
			} elseif ($search == 3) {
				//手机号筛选
				$userInfo = user_api::likeMobile($keywords);
				if (!empty($userInfo)) {
					$params['userId'] = array_reduce($userInfo, create_function('$v,$w', '$v[$w->fk_user]=$w->fk_user;return $v;'));
				}
			} elseif ($search == 4) {
				//用户名筛选
				$userInfo = user_api::likeUserName($keywords);
				if (!empty($userInfo)) {
					$params['userId'] = array_reduce($userInfo, create_function('$v,$w', '$v[$w->pk_user]=$w->pk_user;return $v;'));
				}
			}
			$path .= "&search={$search}&keywords={$keywords}";
		}
		//时间筛选
		if (!empty($option['start_time']) && !empty($option['end_time'])) {
			$type = true;

			$params['start_time'] = str_replace('-', '/', $option['start_time']);
			$params['end_time'] = str_replace('-', '/', $option['end_time']);
			$params['time'] = $option['start_time'] . ',' . $option['end_time'];
			$path .= "&start_time={$params['start_time']}&end_time={$params['end_time']}";
		}
		//价格筛选
		if (isset($option['price1']) && isset($option['price2'])) {
			$type = true;

			$params['price1'] = $option['price1'];
			$params['price2'] = $option['price2'];
			$params['price'] = ($option['price1'] * 100) . ',' . ($option['price2'] * 100);
			$path .= "&price1={$params['price1']}&price2={$params['price2']}";
		}
		//优惠券筛选
		if ($discount != 2) {
			$type = true;
			$params['isFavorable'] = $discount;
			$path .= "&discount={$discount}";
		}
		//订单类型筛选
		if (!empty($orderType)) {
			$params['orderType'] = $orderType;
			$path .= "&orderType={$orderType}";
		}
		//分销课程
		if (!empty($option['resell'])) {
			$type = true;
			$params['resell'] = (int)$option['resell'];
		}
		$params['objTypeStatus'] = 1;
        $orderList = order_api::orderList($params, $page, $length);
		$start_time = !empty($params['start_time'])?$params['start_time']:'';
		$end_time = !empty($params['end_time'])?$params['end_time']:'';
		$this->downloadExcelCommonPart($orderList,$start_time,$end_time);
	}

	public function downloadExcelCommonPart($data,$start_time,$end_time)
	{
		$priceSum = 0;
		foreach($data['data'] as $val){
			$priceSum += $val->actual_price;
		}
		require_once(ROOT_LIBS . "/phpexcel/PHPExcel.class.php");
		require_once(ROOT_LIBS . "/phpexcel/PHPExcel/Writer/Excel2007.php");
		//$cacheSettings = array('memoryCacheSize'  => '2048MB'  );
		//PHPExcel_Settings::setCacheStorageMethod(PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp,$cacheSettings);
		//实例化表格
		$objPHPExcel = new PHPExcel();
		//保存excel—2007格式
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		//基本设置
		$fileName = "订单列表";
		$objProps = $objPHPExcel->getProperties();
		$objProps->setCreator("www.yunke.com");
		$objProps->setLastModifiedBy("www.yunke.com");
		$objProps->setTitle("Office XLS Test Document");
		$objProps->setSubject("Office XLS Test Document, yunke");
		$objProps->setDescription("Test document, generated by PHPExcel.");
		$objProps->setKeywords("office excel PHPExcel");
		$objProps->setCategory("报表");
		$objPHPExcel->setActiveSheetIndex(0);
		// 行高
		for ($i = 1; $i <= 11; $i++) {
			$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
		}
		$objActiveSheet = $objPHPExcel->getActiveSheet();
		$objActiveSheet->setTitle($fileName);


		$objActiveSheet->setCellValue('A' . 1, '#订单导出   ');
		$objActiveSheet->setCellValue('A' . 2, '');
		$objActiveSheet->setCellValue('A' . 3, '#内容仅供参考，不作为凭证');
		$objActiveSheet->mergeCells('A3:B3');
		$objActiveSheet->setCellValue('A' . 4, '');
		$objActiveSheet->setCellValue('A' . 5, '#账户名');
		$name = $this->user['name'];
		$objActiveSheet->setCellValue('A' . 6, "$name");
		$start_time = date('Y-m-d H:i:s',strtotime($start_time));
		$end_time = date('Y-m-d H:i:s',strtotime($end_time));
		$objActiveSheet->setCellValue('A' . 7, "#自[$start_time]至[$end_time]");
		$objActiveSheet->mergeCells('A7:D7');
		$total = $data['totalSize'];
		$objActiveSheet->setCellValue('A' . 8, "#导出订单数：$total");
		$objActiveSheet->mergeCells('A8:B8');
		$time = date("Y-m-d H:i:s",time());
		$objActiveSheet->setCellValue('A' . 9, "#下载时间：$time");
		$objActiveSheet->mergeCells('A9:B9');
		$objActiveSheet->setCellValue('A' . 10, '#---------------------------订单导出---------------------------');
		$objActiveSheet->mergeCells('A10:J10');
		$objActiveSheet->setCellValue('C' . 8, "收入：".round($priceSum,2));
		//设置表头
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
		$objActiveSheet->getStyle('A11')->getFont()->setBold(true);
		$objActiveSheet->getStyle('B11')->getFont()->setBold(true);
		$objActiveSheet->getStyle('C11')->getFont()->setBold(true);
		$objActiveSheet->getStyle('D11')->getFont()->setBold(true);
		$objActiveSheet->getStyle('E11')->getFont()->setBold(true);
		$objActiveSheet->getStyle('F11')->getFont()->setBold(true);
		$objActiveSheet->getStyle('G11')->getFont()->setBold(true);
		$objActiveSheet->getStyle('H11')->getFont()->setBold(true);
		$objActiveSheet->getStyle('I11')->getFont()->setBold(true);
		$objActiveSheet->getStyle('J11')->getFont()->setBold(true);
		$objActiveSheet->getStyle('K11')->getFont()->setBold(true);
		$objActiveSheet->getStyle('L11')->getFont()->setBold(true);
		$objActiveSheet->getStyle('M11')->getFont()->setBold(true);
		$objActiveSheet->getStyle('N11')->getFont()->setBold(true);
		$objActiveSheet->getStyle('O11')->getFont()->setBold(true);
		$objActiveSheet->getStyle('P11')->getFont()->setBold(true);
		$objActiveSheet->getStyle('Q11')->getFont()->setBold(true);

		$objActiveSheet->getStyle('A11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('B11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('C11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('D11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('E11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('F11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('G11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('H11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('I11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('J11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('K11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('L11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('M11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('N11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('O11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('P11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('Q11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objActiveSheet->setCellValue('A11', '订单号码');
		$objActiveSheet->setCellValue('B11', '时间');
		$objActiveSheet->setCellValue('C11', '课程名称');
		$objActiveSheet->setCellValue('D11', '班级');
		$objActiveSheet->setCellValue('E11', '原价');
		$objActiveSheet->setCellValue('F11', '支付手续费');
		$objActiveSheet->setCellValue('G11', '平台使用费');
		$objActiveSheet->setCellValue('H11', '税费');
		$objActiveSheet->setCellValue('I11', '推广支出');
		$objActiveSheet->setCellValue('J11', '渠道分成');
		$objActiveSheet->setCellValue('K11', '支付金额');
		$objActiveSheet->setCellValue('L11', '实际收入');
		$objActiveSheet->setCellValue('M11', '优惠券');
		$objActiveSheet->setCellValue('N11', '交易状态');
		$objActiveSheet->setCellValue('O11', '购买者');
		$objActiveSheet->setCellValue('P11', '联系方式');
		$objActiveSheet->setCellValue('Q11', '支付方式');
		//设置第二列为文本格式 防止科学计数
		$objActiveSheet->getStyle('C11')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		//填充表格内容
		$j = 12;
		foreach ($data['data'] as $k => $v) {
			$objActiveSheet->setCellValue('A' . $j, ' '.date('Ymd', strtotime($v->create_time)) . $v->fk_order);
			$objActiveSheet->setCellValue('B' . $j, $v->create_time);
			$coursName = !empty($v->course['name'])?$v->course['name']:(!empty($v->member['name'])?$v->member['name']:'');

			$objActiveSheet->setCellValue('C' . $j, ' ' . $coursName);
			$className = !empty($v->course['className'])?$v->course['className']:'';
			$objActiveSheet->setCellValue('D' . $j, $className);
			if ($v->discount_status == 0) {
				$discount = '未使用优惠券';
			} elseif ($v->discount_status == 1) {
				$discount = $v->discount;
			}
			$objActiveSheet->setCellValue('E' . $j, ' '.$v->price_old.' ');
			$objActiveSheet->setCellValue('F' . $j, ' '.$v->gateway_price.' ');
			$objActiveSheet->setCellValue('G' . $j, ' '.$v->platform_price.' ');
			$objActiveSheet->setCellValue('H' . $j, ' '.$v->tax_price.' ');
			if($v->promote_status==1){
				$objActiveSheet->setCellValue('I' . $j, ' '.($v->price-$v->price_promote).' ');
			}else{

				$objActiveSheet->setCellValue('I' . $j, " 0 ");
			}
			$objActiveSheet->setCellValue('J' . $j, ' '.$v->distribute_price.' ');
			$objActiveSheet->setCellValue('K' . $j, ' '.$v->actual_price.' ');
			$objActiveSheet->setCellValue('L' . $j, ' '.$v->price.' ');
			$objActiveSheet->setCellValue('M' . $j, ' '.$discount.' ');
			if ($v->status == 'expired') {
				$status = '已过期';
			} elseif ($v->status == 'success') {
				$status = '已成功';
			} elseif ($v->status == 'fail') {
				$status = '失败';
			} else {
				$status = '已取消';
			}
			$objActiveSheet->setCellValue('N' . $j, $status);
			$objActiveSheet->setCellValue('O' . $j, $v->stuName);
			$objActiveSheet->setCellValue('P' . $j, ' '.$v->mobile.' ');
			if ($v->pay_type == 1) {
				$pay = '支付宝';
			} elseif ($v->pay_type == 2) {
				$pay = '微信';
			} elseif ($v->pay_type == 3) {
				$pay = ' ';
			} elseif ($v->pay_type == 4){
				$pay = '云点支付';
			}else{
				$pay = ' ';
			}

		$objActiveSheet->setCellValue('Q' . $j, $pay);
		$objActiveSheet->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('B' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objActiveSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('E' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('G' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('H' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('I' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('J' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('K' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('L' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('M' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('L' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('N' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('O' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('P' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('Q' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$j++;
		}


		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		if(empty($fileName)){
			$ftime = time();
			$outputFileName = $ftime . ".xls";
		}else{
			$outputFileName = $fileName . ".xls";
		}
		//加此防乱码
		ob_clean();
		@header("Pragma: public");
		@header("Expires: 0");
		@header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
		@header("Content-Type:charset=UTF-8");
		@header("Content-Type:application/force-download");
		@header("Content-Type:application/vnd.ms-execl");
		@header("Content-Type:application/octet-stream");
		@header("Content-Type:application/download");
		@header('Content-Disposition:attachment;filename="' . $outputFileName . '"');
		@header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
	}

	public function pagechannelList(){
		return $this->render('org/channel.list.html');
	}
	
	/*
	 * 机构管理---设置分类列表页
	 *@params string    $scopes
	 *@return array 	$data
	 */
	public function pageclassify(){
		$cateTreeList 	= array();
		$cateIdArr		= array();
		$scopesArr		= !empty($this->orgInfo->scopes) ? explode(",",$this->orgInfo->scopes) : '';
		$res  			= org_api::getOrgCateSomeInfoById($scopesArr);
		
		
		$orgExist 		= org_api::getOrgExistCate($this->orgInfo->oid,$init=array());
		if(!empty($orgExist)){
			foreach($orgExist as $k=>$v){
				$cateIdArr[] = !empty($v->third_cate) ? $v->third_cate : 0;
			}
		}
		if(!empty($res)){
			foreach($res as $key=>$val){
				foreach($val->children as $k=>$v){
					foreach($v->children as $kc=>$vt){
						if(in_array($vt->pk_cate,$cateIdArr)){
							$vt->is_check = 1;
						}else{
							$vt->is_check = 0;
						}
					}
				}
			}	
		}
		$this->assign("cateList",$res);
		$info	= org_api::getOrgCateSomeInfoById($scopesArr);
		if(!empty($info)&&!empty($cateIdArr)){
			foreach($info as $key=>$val){
				foreach($val->children as $k=>$v){
					foreach($v->children as $kc=>$vt){
						if(!in_array($vt->pk_cate,$cateIdArr)){
							unset($v->children[$kc]);
						}
					}
				}
			}	
		}else{
			$info = array();
		}
		$this->assign("orgExist",$info);
		$this->assign("orgInfo",!empty($this->orgInfo) ? $this->orgInfo : '');
		return $this->render("/org/classify.set.html");
		
	} 
	
	/*
	 *机构管理---增加分类
	 *@params int    $oid
	 *@params array  array
	 *@return json 	
	 */
	public function pageAddOrgCate(){
		$data 		= array();
		$oid 		= !empty($this->orgInfo->oid) ? $this->orgInfo->oid : 0;
		if(empty($_POST['cateId'])){
			return $this->setResult('', -101, "请选择分类~");
		}
		$cateIdArr 	= !empty($_POST['cateId']) ? $_POST['cateId'] : '';
		$scopesArr	= !empty($this->orgInfo->scopes) ? explode(",",$this->orgInfo->scopes) : '';
		$info		= org_api::getOrgCateSomeInfoById($scopesArr);
		$orgExist 		= org_api::getOrgExistCate($this->orgInfo->oid,$init=array());
		$IdArr		= array();
		if(!empty($orgExist)){
			foreach($orgExist as $k=>$v){
				$IdArr[] = !empty($v->third_cate) ? $v->third_cate : 0;
			}
		}
		if(!empty(array_diff($IdArr,$cateIdArr))){
			org_api::delOrgCateById(array("third_cate"=>implode(",",array_diff($IdArr,$cateIdArr)),"fk_org"=>$oid));
			
		}
		
		if(!empty($info)){
			foreach($info as $key=>$val){
				foreach($val->children as $k=>$v){
					foreach($v->children as $kc=>$vt){
						if(!in_array($vt->pk_cate,$cateIdArr)){
							unset($v->children[$kc]);
						}else{
							
							$data[] = array("first_cate"=>$val->pk_cate,"second_cate"=>$v->pk_cate,"third_cate"=>$vt->pk_cate);
						}
					}
				}
			}	
		}else{
			$info = array();
		}
		
		$orgCate = 1;
		if(!empty($data)){
			foreach($data as $k=>$v){
				$condition 	= array("fk_org"=>$oid,"first_cate"=>$v['first_cate'],"second_cate"=>$v['second_cate'],"third_cate"=>$v['third_cate']);
				$orgExist 		= org_api::getOrgExistCate($this->orgInfo->oid,$condition);
				if(empty($orgExist)){
					$orgCate 	= org_api::addOrgCate($condition);
				}
			}
		}
		if($orgCate >0 ){
			return $this->setResult('', 200, "保存成功");
		}
	}

	public function pageRefund($inPath){
		if(empty($inPath[3])){
			$this->redirect('/site.main.404');
		}
		$orderInfo = order_api::getOrder(array("orderId"=>(int)$inPath[3],"orgId"=>$this->orgId));
		if(!empty($orderInfo->items[0])){
			$orderInfo = $orderInfo->items[0];
			if($orderInfo->object_type!=1||$orderInfo->price<=0||$orderInfo->promote_status==1||(!is_null($orderInfo->content_refund_status)&&$orderInfo->content_refund_status>=0)){
				$this->redirect('/site.main.404');
			}
			$orgInfo = user_organization::getOrgInfoByOidArr(array($orderInfo->org_id), true);
			if (!empty($orgInfo[0])) {
				$userIdArr = $orderIdArr = $courseIdArr = $classIdArr = $memberSetArr =  $memberIdArr = $discountOrderIdArr =array();
				$orgInfo = $orgInfo[0];
				$userIdArr[$orderInfo->fk_user] = $orderInfo->fk_user;
				$userOwner[$orgInfo->pk_org] = $orgInfo->fk_user_owner;
				$org_name[$orgInfo->pk_org] = $orgInfo->subname;
				$subDomainList = user_api::getSubdomainByUidArr($userOwner);
				if (!empty($subDomainList->result->data->items[0])) {
					$subDomainList = $subDomainList->result->data->items[0];
					$subDomainInfo[$subDomainList->fk_user][] = "//" . user_organization::course_domain($subDomainList->subdomain);
				}
				if($orderInfo->discount_status==1){
					$discountOrderIdArr[$orderInfo->fk_order] = $orderInfo->fk_order;
				}
				if($orderInfo->object_type == 1){
					$courseIdArr[$orderInfo->object_id] = $orderInfo->object_id;
					$classIdArr[$orderInfo->ext]  = $orderInfo->ext;
				}elseif($orderInfo->object_type == 11){
					$memberIdArr[$orderInfo->object_id]  = $orderInfo->object_id;
					$memberSetArr[$orderInfo->object_id] = $orderInfo->ext;
				}
				//分销订单
				/*if($orderInfo->promote_status){
					$resellOrg[$orderInfo->resell_org_id] = $orderInfo->resell_org_id;
					$resellRes = user_organization::getOrgInfoByOidArr($resellOrg, true);
					if(!empty($resellRes)){
						foreach($resellRes as $val){
							$resellInfo[$val->pk_org] = $val->subname;
						}
					}
				}*/
				$user    = self::getUserInfo($userIdArr);
				$course  = self::getCourseInfo($courseIdArr, $classIdArr,1);
				//$member  = self::getMemberInfo($memberIdArr, $memberSetArr);
				$discount= self::getDiscountInfo($discountOrderIdArr);
				$payType = [1=>'支付宝',2=>'微信',3=>' ',4=>'云点支付'];
			}
			$orderInfo->stuName     = !empty($user[$orderInfo->fk_user]['name']) ? $user[$orderInfo->fk_user]['name'] : '';
			$orderInfo->mobile      = !empty($user[$orderInfo->fk_user]['mobile']) ? $user[$orderInfo->fk_user]['mobile'] : '';
			$orderInfo->stuImg      = !empty($user[$orderInfo->fk_user]['userImg']) ? $user[$orderInfo->fk_user]['userImg'] : '';
			$orderInfo->course      = array();
			//$orderInfo->member      = array();
			$orderInfo->discount    = !empty($discount[$orderInfo->fk_order]) ? $discount[$orderInfo->fk_order] : '';
			$orderInfo->payType     = !empty($payType[$orderInfo->pay_type]) ? $payType[$orderInfo->pay_type] : '';
			$orderInfo->orderSn     = date('Ymd',strtotime($orderInfo->create_time)).$orderInfo->fk_order;
			$orderInfo->disPrice    = ($orderInfo->price_old/100) - ($orderInfo->price/100);
			$orderInfo->orderStatus = course_status::$orderStatus[$orderInfo->status];
			if($orderInfo->pay_type == 3){
				$orderInfo->uniqueOrderId = '优惠券';
			}else{
				$orderInfo->uniqueOrderId = $orderInfo->unique_order_id;
			}

			if($orderInfo->object_type == 1){
				$orderInfo->course = !empty($course[$orderInfo->object_id]) ? $course[$orderInfo->object_id] : array();
			}
			/*elseif($orderInfo->object_type == 11){
				$orderInfo->member['name'] = !empty($member[$orderInfo->object_id]['name']) ? $member[$orderInfo->object_id]['name'] : '';
				if($orderInfo->ext == 1){
					$orderInfo->member['price'] = $orderInfo->price/100;
					$orderInfo->member['day']   = !empty($member[$orderInfo->object_id]['price_30']) ? 30 : 0;
				}elseif($orderInfo->ext == 2){
					$orderInfo->member['price'] = $orderInfo->price/100;
					$orderInfo->member['day']   = !empty($member[$orderInfo->object_id]['price_90']) ? 90 : 0;
				}elseif($orderInfo->ext == 3){
					$orderInfo->member['price'] = $orderInfo->price/100;
					$orderInfo->member['day']   = !empty($member[$orderInfo->object_id]['price_180']) ? 180 : 0;
				}elseif($orderInfo->ext == 4){
					$orderInfo->member['price'] = $orderInfo->price/100;
					$orderInfo->member['day']   = !empty($member[$orderInfo->object_id]['price_360']) ? 360 : 0;
				}

			}*/
			$orderInfo->price = $orderInfo->price/100;
			$orderInfo->price_old = $orderInfo->price_old/100;
			$orderInfo->domainUrl = !empty($subDomainInfo[$userOwner[$orderInfo->org_id]]) ? $subDomainInfo[$userOwner[$orderInfo->org_id]] : '';
			$orderInfo->org_name = !empty($org_name[$orderInfo->org_id]) ? $org_name[$orderInfo->org_id] : '';
			//$orderInfo->resellName = !empty($resellInfo[$orderInfo->resell_org_id]) ? $resellInfo[$orderInfo->resell_org_id] : '';
		}else{
			$this->redirect('/site.main.404');
		}
		$this->assign('orderInfo',$orderInfo);
		$this->render('org/order.refund.html');
	}

	//用户信息
	public static function getUserInfo($userId)
	{
		$data = array();
		if(empty($userId)) return $data;
		$userRes = user_api::listUsersByUserIds($userId);
		if(!empty($userRes->result)){
			foreach($userRes->result as $val){
				$data[$val->pk_user] = [
					'name'   => !empty($val->real_name) ? $val->real_name : '',
					'mobile' => !empty($val->mobile) ? $val->mobile : '',
					'userImg'=> !empty($val->thumb_med) ? interface_func::imgUrl($val->thumb_med) : ''
				];
			}
		}

		return $data;
	}

	//课程信息
	public static function getCourseInfo($courseId, $classId,$length)
	{
		$data = array();
		if(empty($courseId) || empty($classId)) return $data;
		$courseIds = implode(',',$courseId);
		$courseParam = [
			'q' => ['course_id'=>$courseIds,'deleted'=>'0,-1'],
			'f' => ['course_id','class_id','thumb_med','title','course_type','subdomain','deleted','class'],
			'pl'=>$length,
		];
		$courseRes = seek_api::seekcourse($courseParam);
		if(empty($courseRes->data)) return $data;
		$classProgress = array();
		if(!empty($courseRes->data[0]->class)){
			foreach($courseRes->data[0]->class as $class){
				$classProgress[$class->class_id]= $class->progress_plan;
			}
		}
		$progress = $classProgress[reset ($classId)];
		$domainConf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");

		$classIds = implode(',',$classId);
		$planParams = [
			'q' => ['class_id'=>$classIds,'deleted'=>'0,-1'],
			'f' => ['class_name','start_time','course_id','status','admin_real_name','teacher_real_name','deleted','plan_id','section_order_no'],
			'pl'=>$length
		];
		$planRes = seek_api::seekplan($planParams);
		$planData = array();
		if(!empty($planRes->data)){
			foreach($planRes->data as $val){
				$planData[$val->course_id] = [
					'className' => $val->class_name,
					'startTime' => $val->start_time,
					'teacherName'=> !empty($val->admin_real_name) ? $val->admin_real_name : $val->teacher_real_name,
				];
				if($val->plan_id==$progress){
					$planData[$val->course_id]["planName"]= $val->section_order_no;
				}
				if(empty($planData[$val->course_id]['planNum'])) {
					$planData[$val->course_id]['planNum'] = 0;
				}
				$planData[$val->course_id]['planNum'] +=1;
			}
		}

		foreach($courseRes->data as $val){
			$data[$val->course_id] = [
				'name' => $val->title,
				'img'  => interface_func::imgUrl($val->thumb_med),
				'courseType' => $val->course_type,
				'className'  => !empty($planData[$val->course_id]['className']) ? $planData[$val->course_id]['className'] : '',
				'startTime'  => !empty($planData[$val->course_id]['startTime']) ? $planData[$val->course_id]['startTime'] : '',
				'teacherName'  => !empty($planData[$val->course_id]['teacherName']) ? $planData[$val->course_id]['teacherName'] : '',
				'deleted'  => !empty($val->deleted) ? $val->deleted : 0,
				'planNum' => !empty($planData[$val->course_id]['planNum']) ? $planData[$val->course_id]['planNum'] : '',
				'progress' =>!empty($planData[$val->course_id]['planName']) ? $planData[$val->course_id]['planName'] : '',
			];

			if(strrpos($val->subdomain, '.')){
				$data[$val->course_id]['url'] = '//'.$val->subdomain .'/course.info.show/'.$val->course_id;
				$data[$val->course_id]['payUrl']    = '//'.$val->subdomain .'/course.info.show/'.$val->course_id;
			}else{
				$data[$val->course_id]['url'] = '//'.$val->subdomain .'.'.$domainConf->domain .'/course.info.show/'.$val->course_id;
				$data[$val->course_id]['payUrl']    = '//'.$val->subdomain .'.'.$domainConf->domain .'/course.info.show/'.$val->course_id;
			}
		}

		return $data;
	}

	//会员信息
	public static function getMemberInfo($memberId, $ext)
	{
		$data = array();
		if(empty($memberId)) return $data;
		$memberIds = implode(',',$memberId);
		$params    = array('setId'=>$memberIds);
		$memberRes = utility_services::call("/user/orgMemberSet/getOrgMemberSets/", $params);
		if($memberRes->code != 0) return $data;

		foreach($memberRes->result->items as $val){
			$data[$val->pk_member_set] = [
				'name' => $val->title,
				'price_30' => $val->price_30,
				'price_90' => $val->price_90,
				'price_180' => $val->price_180,
				'price_360' => $val->price_360
			];
		}

		return $data;
	}

	//优惠码信息
	public static function getDiscountInfo($orderId)
	{
		$data = array();
		if(empty($orderId)) return $data;
		$orderIds = implode(',',$orderId);
		$discountRes = course_api::getDiscountCodeByOrder($orderIds);
		if(!empty($discountRes)){
			foreach($discountRes as $val){
				$data[$val->fk_order] = $val->discount_code;
			}
		}

		return $data;
	}
	
}
