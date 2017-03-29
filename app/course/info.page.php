<?php

class course_info extends STpl
{

    public function __construct()
    {
        $org             = user_organization::subdomain();
        $this->orgOwner  = !empty($org) ? $org->userId : 0;
        $this->subdomain = !empty($org->subdomain) ? $org->subdomain : '';
        $this->head      = !empty($org->subdomain) ? $org->subdomain : 'www';
    }

    public function pagePlayUrl($inPath)
    {
        if (empty($inPath[3]) || empty($inPath[4])) {
            $this->redirect('/index');
        }

        $cid      = $inPath[3];
        $classId  = $inPath[4];
        $planInfo = course_api::listPlan(
            [
                'cid'       => $cid,
                'class_id'  => $classId,
                'allcourse' => true,
                'order_by'  => "ASC"
            ]
        );

        if (empty($planInfo->data)) {
            $this->redirect("/course.info.show/".$cid);
        }

        $timeArr = array();
        foreach ($planInfo->data as $v) {
            if(!empty($v->video_id) && ($v->video_public_type>=0)){
                $timeArr["latest"] = $v->plan_id;
                break;
            }
            $timeArr[$v->plan_status][] = $v->plan_id;
        }
        foreach ($planInfo->data as $v) {
            if($v->status==2){
                $timeArr["latest"] = $v->plan_id;
                break;
            }
        }
        if(!empty($timeArr["latest"])){
            $this->redirect("/course.plan.play/".$timeArr['latest']);
        }else {
            if (!empty($timeArr['living'][0])) {
                $this->redirect("/course.plan.play/" . $timeArr['living'][0]);
            } elseif (!empty($timeArr['normal'][0])) {
                $this->redirect("/course.plan.play/" . $timeArr['normal'][0]);
            } else {
                if (!empty($timeArr['finished'][0])) {
                    $this->redirect("/course.plan.play/" . $timeArr['finished'][0]);
                } else {
                    $this->redirect("/course.info.show/" . $cid);
                }
            }
        }
    }

	public function pagePlanList($inPath){
	
		$cid  = !empty($inPath[3]) ? (int)$inPath[3] : 0;
		$user = user_api::loginedUser();
		$uid  = !empty($user) ? $user['uid'] : 0;
		
		if(empty($cid) || empty($uid)){
			$this->redirect("/site.main.entry");
		}
		
		$retJudgeuser = utility_judgeid::authrole($uid, $this->orgOwner);
		if(!$retJudgeuser){
			$this->redirect("/site.main.entry");
		}
		
		$retCourse = utility_judgeid::courseid($cid, $uid, $this->orgOwner);
		if(!$retCourse){
			$this->redirect("/site.main.entry");
		}
		
		$res_course = course_api::getCourseone($cid);
		$res_class  = course_api::getClasslist($cid);
		if(empty($res_course) || empty($res_class)){
			$this->redirect("/site.main.entry");
		}
		
		$course = [
			'title' => $res_course->title,
			'type'  => $res_course->type_id,
			'tname' => !empty($res_course->user->user_real_name)?$res_course->user->user_real_name:$res_course->user->username
		];
		
		foreach($res_class as $v){
			if($course['type'] == 2){
				$class = [
					'class_id'   => $v->class_id,
					'class_name' => $v->name
				];
			}else{
				$class[$v->class_id] = [
					'class_id'   => $v->class_id,
					'class_name' => $v->name
				];
			}
		}

		$this->assign('course',$course);
		$this->assign('class',$class);
		$this->assign('courseId',$cid);
		$this->render("course/plan.list.html");
	}

	/*
	 * 获取班级下的排课信息
	 * @param $courseId 课程id $classId 班级id
	 */
	public function pagePlanListAjax(){
		$courseId = !empty($_POST['course_id']) ? (int)($_POST['course_id']) : 0;
		$classId  = !empty($_POST['cid']) ? (int)($_POST['cid']) : 0;
		$lan      = !empty($_POST['lan']) ? $_POST['lan'] : '';
		if(empty($courseId) || empty($classId)){
			return false;
		}
		$sectionName = array();
		if($lan == 'en'){
			for($i=1;$i<=30;$i++){
				$sectionName[$i] = "Section ".$i;
			}
		}
		$res_plan = course_api::listPlan(array('cid'=>$courseId,'class_id'=>$classId,'order_by'=>'asc','allcourse'=>true));
		
		$list = array();
		if(empty($res_plan->data)) return false;
		
		foreach($res_plan->data as $k=>$v){
			$list['admin_name'] = !empty($v->user_class->real_name) ? $v->user_class->real_name : '';

			$list['data'][$k] = [
				'plan_id'         => $v->plan_id,
				'class_id'        => $v->class_id,
				'section_descipt' => $v->section_descipt,
				'status'          => $v->status
			];
			if($lan == 'en'){
				$list['data'][$k]['section_name'] = !empty($sectionName[$v->order_no]) ? $sectionName[$v->order_no] : '';
				$list['data'][$k]['start_time']   = ($v->type_id != 2) ? date('d',strtotime($v->start_time)).'-'.date('m',strtotime($v->start_time)).'-'.date('Y',strtotime($v->start_time)).' '.date('H:i',strtotime($v->start_time)) : '';
			}else{
				$list['data'][$k]['section_name'] = '第'.$v->order_no.'课时';
				$list['data'][$k]['start_time']   = ($v->type_id != 2) ? date('Y年m月d日 H:i',strtotime($v->start_time)) : '';
			}

			if($v->fk_user_class == $v->fk_user_plan){
				$list['data'][$k]['teacher_name'] = '';
			}else{
				$list['data'][$k]['teacher_name'] = !empty($v->user_plan->real_name)?"(".$v->user_plan->real_name.")":(!empty($v->user_plan->name)?"(".$v->user_plan->name.")":'');
			}
		}
		
		echo json_encode($list);exit;
	}

    public function pageGetClassList($inPath)
    {
        if (isset($inPath[3], $inPath[4]) && $inPath[3] && $inPath[4]) {
            $cid = $inPath[3];
            $classId = $inPath[4];
        } else {
            return interface_func::setMsg(1000);
        }
        $resellOrgId = isset($inPath[5])?$inPath[5]:0;
        $qudaoCode = '';
        if(!is_numeric($resellOrgId)){
            if(substr_count($resellOrgId,'%7C')>0){
                $resellOrgId = str_replace("%7C","|",$resellOrgId);
            }
            if(substr_count($resellOrgId,'d')==1 &&substr_count($resellOrgId,'|')==2){
                $qudaoCode = $resellOrgId;
            }
            $resellOrgId=0;
        }
        $res = course_detailed::getClassAndPlan($cid,$resellOrgId);
        if (empty($res['classList'][$classId])) return interface_func::setMsg(2017);

        $user = user_api::loginedUser();
        $uid = empty($user) ? 0 : $user['uid'];
        $res['courseInfo']['is_member'] = 0;
        $courseMember = user_organization::getMemberPriorityByObjectId($cid,1);
        $setIdArr = array();
        if(!empty($courseMember)){
            foreach($courseMember as $mo){
                if($mo->status == 1){
                    $setIdArr[] = $mo->fk_member_set;
                }
            }
        }
        $memberRet = org_member_api::checkIsMemberOrExpire($uid, $setIdArr, $cid);
        if(!empty($memberRet)&&isset($memberRet['isExpire'])&&isset($memberRet['isMember'])&&$memberRet['isMember'] == 1 && $memberRet['isExpire'] == 0){
            $res['courseInfo']['is_member'] = 1;
        }

        $this->assign('info', $res['classList'][$classId]);
        $this->assign('courseInfo', $res['courseInfo']);
        $this->assign('resellOrgId', $resellOrgId);
        $this->assign('qudaoCode',$qudaoCode);
        $this->render('/layer/class.list.v2.html');
    }

    public function pagePay($inPath){
        if (empty($inPath[3])) {
            $this->redirect("/");
        }

        $unique_order_id = $inPath[3];
        $order_info      = order_api::getOrderAndContent($unique_order_id);
        if (empty($order_info)) {
            //TODO ERROR Page
            return "生成订单错误";
        }
        $course_id = $order_info->object_id;
        $class_id  = $order_info->ext;
        $error     = null;
        $user      = user_api::loginedUser();
        if (empty($user)) {
            $this->redirect("/site.main.login?url=/course.info.show/$course_id/");
        }
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
        $course_info = course_api::getCourseone($course_id);
        if (empty($course_info)) {
            $this->redirect("/");
        }
        if ($course_info->fee_type == 0) {
            //免费课,直接去详情页报名
            $this->redirect("/course.info.show/$course_id?error=6");
        } else {
            //	付费的课程详情显示
            $class_all_fee = course_api::getClasslist($course_id);
            if (empty($class_all_fee)) {
                $this->redirect("/course.info.show/$course_id?error=8");
            }
            $in             = false;
            $class_info_fee = "";
            foreach ($class_all_fee as $_class_fee) {
                if ($class_id == $_class_fee->class_id) {
                    $class_info_fee = $_class_fee;
                    $in             = true;
                    break;
                }
            }
            $this->assign("class", $class_info_fee);
        }

        $ret_code      = false;
        $discount_code = 0;
        $weixinPayUrl = '';
        if (empty($error) && empty($status)) {
            if (!empty($_POST["discount_code"])) {
                $discount_code = $_POST["discount_code"];
                $ret_code      = course_api::useDiscountCode($discount_code, $user["uid"], $unique_order_id);
                $order_info    = course_api::getOrderByOutTradeId($unique_order_id);
            } else if (!empty($_POST["confirm"])) {
                course_api::confirmDiscountCode($order_info->order_id);
            } else if (!empty($_POST["cancel"])) {
                course_api::cancelDiscountCode($order_info->order_id);
                $order_info = order_api::getOrderByOutTradeId($unique_order_id);
            }
            $link = course_api::genPayUrl($order_info, $course_info);
            $this->assign("aliPayUrl", $link);
			if (weixin_api::is_weixin()) {
				$this->assign("weixin",1);
				$sc = "http";
				if (utility_net::isHTTPS()) {
					$sc = "https";
				}
				$backurl = $sc."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				$weixinPayUrl=utility_services::url("weixin_pay" ,"index.main.weixinpay/?uqid=$unique_order_id&backurl=$backurl");
            } else {
                //扫码支付
                $weixinQrcode = course_api::genWeixinPayQrcodeUrl($order_info);
                $qrcode       = utility_cdn::qrcode("qr?s=200&t=".urlencode($weixinQrcode));
                $this->assign("qrcode", $qrcode);
            }
        }
        $sy_course_user     = $course_info->max_user - $course_info->user_total;
        $discount_code_used = course_api::getDiscountCodeUsed($order_info->order_id);
        $show_pay           = true;
        if ($discount_code_used && 0 == $discount_code_used->status) {
            $show_pay = false;
        }
        $order_info->price_discount = $order_info->price_old - $order_info->price * 100;

        $this->assign("weixinPayUrl", $weixinPayUrl);
        $this->assign("discount_code_used", $discount_code_used);
        $this->assign("show_pay", $show_pay);
        $this->assign("discount_code", $discount_code);
        $this->assign("ret_code", $ret_code);
        $this->assign("sy_course_user", $sy_course_user);
        $this->assign("status", $status);
        $this->assign("user", $user);
        $this->assign("course", $course_info);
        $this->assign("order_info", $order_info);
        $this->assign("order_id", $unique_order_id);
        $this->assign("error", $error);//ok,fail
        //return $this->render("course/info.buy.html");
        $this->render("course/info.buy.v2.html");
    }

    public function pagecheckclassAjax($inPath)
    {
        $this->user = user_api::loginedUser();
        $cid        = $_POST['couid'];//course_id

        if (empty($_POST['couid'])) {
            return $this->redirect("/");
        }
        $course_id = $_POST['couid'];
        $user      = user_api::loginedUser();
        if (empty($user)) {
            return $this->redirect("/site.main.login?url=/course.info.show/$course_id");
        }
        $uid         = $user['uid'];//uid
        $course_info = course_api::getCourseone($course_id);
        if (empty($course_info)) {
            return $this->redirect("/");
        }
        if ($course_info->fee_type == 1) {
            //收费课,直接去购买
            return $this->redirect("/course.info.show/$course_id?error=1");
        }
        $status    = empty($_POST['status']) ? '1' : $_POST['status'];
        $class_id  = empty($_POST['classid']) ? 0 : $_POST['classid'];//class_id
        $hello     = explode("/", $this->user["small"]);
        $thumb_big = $hello[3];//头像

        $result = new stdclass;
        if (!empty($class_id)) {
            $regdata = array(
                "class_id"   => $class_id,
                "course_id"  => $course_id,
                "uid"        => $uid,
                "user_owner" => $course_info->user_id,
                "status"     => $status,
            );
            if ($thumb_big) {
                $regdata["thumb_big"] = $thumb_big;
            }
            $reg_ret = course_api::addregistration($regdata);
            if ($reg_ret) {
                $result->status = "Success!";

                return $result;
            } else {
                $result->error = SLanguage::tr('报名失败', 'course.info');

                return $result;
            }
        }
        $result->error = SLanguage::tr('报名失败', 'course.info');

        return $result;
    }

    public function pagecheckfavAjax($inPath)
    {
        $this->user = user_api::loginedUser();
        $uid        = $this->user['uid'];
        $cid        = $_POST['couid'];
        $result     = new stdclass;
        if (!empty($cid)) {
            $favdata = array(
                "user_id"   => $uid,
                "course_id" => $cid,
            );
            $reg_ret = user_api::addFav($favdata);
            if ($reg_ret) {
                $result->status = "Success!";

                return $result;
            } else {
                $result->error = SLanguage::tr('添加失败', 'course.info');

                return $result;
            }
        }
        $result->error = SLanguage::tr('报名失败', 'course.info');

        return $result;
    }

    public function pageDelFav()
    {
        $result = new stdclass;
        if (empty($_POST['cid'])) {
            $result->error = SLanguage::tr('参数错误', 'course.info');;

            return $result;
        }

        $user = user_api::loginedUser();

        if (empty($user)) {
            $result->error = SLanguage::tr('请先登陆', 'course.info');;

            return $result;
        }

        $param = array(
            'uid' => $user['uid'],
            'cid' => $_POST['cid']
        );

        $res = user_api::delFav($param);
        if (!empty($res) && $res->code == 0) {
            return true;
        } else {
            $result->error = SLanguage::tr('删除失败', 'course.info');;

            return $result;
        }
    }

    public function pageAddReg($inPath)
    {
        if (empty($_POST['cid']) && !(int)($_POST['cid'])) return interface_func::setMsg(1000);
        if (empty($_POST['classId']) && !(int)($_POST['classId'])) return interface_func::setMsg(1000);

        $source = isset($_POST['source']) && (int)$_POST['source'] ? (int)$_POST['source'] : 1;
        $courseId = (int)($_POST['cid']);
        $resellOrgID = (empty($_POST['oid'])) ? 0 : (int)($_POST['oid']);
        $classId  = (int)($_POST['classId']);

        $user = user_api::loginedUser();
        if (empty($user)) return interface_func::setMsg(1021);

        $tImg     = explode("/", $user["small"]);
        $thumbBig = $tImg[3];
        
        $params = array('uid'=>$user['uid']);
        $resellCourseInfo = course_resell_api::getCourseInfo($courseId , $params);  
        $courseInfo = empty($resellCourseInfo->data) ? '' : $resellCourseInfo->data;
        //$courseInfo = course_api::getCourseone($courseId);

        if (empty($courseInfo)) return interface_func::setMsg(2017);
	//if ($resellCourseInfo->result->code<0) return interface_func::setMsg($resellCourseInfo->result->code,$resellCourseInfo->result->msg);
        if ($resellCourseInfo->result->sub_code==-4 ) return interface_func::setMsg(5001,'课程已报满');

        $classData = course_api::getclass($classId);
        if (empty($classData)) return interface_func::setMsg(2020);
        if ($classData->user_total >= $classData->max_user) {
            return interface_func::setMsg(5003,'班级已报满');
        }
        $data = array(
            "class_id"   => $classId,
            "course_id"  => $courseId,
            "uid"        => $user['uid'],
            "user_owner" => $courseInfo->fk_user,
            "source"     => $source,
            "status"     => 1,
        );

        !empty($thumbBig) && $data['thumb_big'] = $thumbBig;
        
        // 多班，已报名，未报满。  提示 ：您已报名“1班”如需报名2班请联系机构调班。
        if ($resellCourseInfo->result->class_count >1 && $resellCourseInfo->result->sub_code==-5){       
             $reg_class_name = $resellCourseInfo->result->reg_class_name;
             return interface_func::setMsg(5002,"您已报名“{$reg_class_name}”,如需报名其它班请联系机构调班。");
        }
        // 单班未报满
        $ret_reg = course_api::addregistration($data); 

        if ($ret_reg) {
            // 报名成功，插入分销报名表
            if ($courseInfo->is_promote==1 && !empty($resellOrgID)){
                $promoteOrgInfo = user_organization::getOrgByOwner($courseInfo->fk_user); // 推广机构信息
                $promoteOrgId = empty($promoteOrgInfo->oid) ? 0 : $promoteOrgInfo->oid;
                //记录分销日志t_course_resell_log
                $resellLogData = [
                    'courseResellId' => $courseId,              // 课程ID
                    'resellOrgId'    => $resellOrgID,           // 分销机构ID
                    'promoteOrgId'   => $promoteOrgId ,   // 推广机构ID
                    'userId'         => $user['uid'],           // 报名人
                    'orderContentId' => 0,
                    'orderId'        => 0,
                    'priceResell'    => 0,
                    'pricePromote'   => 0,
                ];
                course_resell_api::addResellLog($resellLogData);
            }
            // 分销课程，课程报满，更改分销课程表&推广课程表enroll_count
            if ($courseInfo->is_promote==1 && $resellCourseInfo->result->sub_code==4 && !empty($resellOrgID)){
                $msgData = [
                     'title'   => $courseInfo->title,
                     'subname' => ''
		];
                course_resell_api::updatePromoteTypeAndSetMsg($courseId,'courseMaxUser',$msgData,$resellOrgID);
            }
            $orgInfo = user_organization::getOrgByOwner($data['user_owner']);
            $info = array(
                "userId"=>$user['uid'],
                "courseId"=>$courseId,
                "orgId"=>!empty($orgInfo->oid)?$orgInfo->oid:0,
            );
            user_organizationStudent_api::addOrganizationStudent($info);
            return interface_func::setMsg(0);
        }
        return interface_func::setMsg(1);
    }

    public function pageShow($inPath)
    {
        if (!isset($inPath[3]) || !(int)($inPath[3])) {
            $this->redirect('/index');
        }
        @setcookie("blackUrl".$inPath[3]."",implode("/",$inPath),time()+3600,"/",utility_net::getDomainRoot());
        $cid = (int)$inPath[3];
        $checkCourse = course_api::getCourseOne($cid);
        if(empty($checkCourse)){
            $this->redirect('/course.main.404');
        }
        $tResellOrgId = isset($inPath[4]) && $inPath[4] ? $inPath[4] : 0;
        $codeInfo = array();
        $qudaoCode = '';
        if(!is_numeric($tResellOrgId)){
            if(substr_count($tResellOrgId,'%7C')>0){
                $tResellOrgId = str_replace("%7C","|",$tResellOrgId);
            }
            if(substr_count($tResellOrgId,'d')==1 &&substr_count($tResellOrgId,'|')==2){
                $qudaoCode = $tResellOrgId;
                $codeInfo = explode('|',$tResellOrgId);
            }
            $tResellOrgId=0;
        }
        $qudaoUser = 0;
        $qudaoUserCode = 0;
        if(!empty($codeInfo)){
            $qudaoUser=$codeInfo[1];
            $qudaoUserCode = $codeInfo[2];
        }
        $distribute = order_api::getDistributeByCourseId($cid);
        if (!empty($distribute->status) && $distribute->status == 1) {
            $qudaoUserCode = order_api::getQudaoUserCodeByQudaoUserIdAndCourseIdAndCode(array("fk_course" => $cid, "fk_qudao_user" => $qudaoUser, "code" => $qudaoUserCode));
            if (empty($qudaoUserCode)) {
                $qudaoCode = '';
            }
        }else{
            $qudaoCode = '';
        }
        $orgProfile  = user_organization::orgAboutProfileInfo($this->orgOwner);
        $hotType = !empty($orgProfile->hot_type)?(int)$orgProfile->hot_type:1;
        $defaultClassId = $resellOrgId = 0;
        $res = course_detailed::getClassAndPlan($cid, $tResellOrgId,$hotType);
        if(empty($res['courseInfo']) || empty($res['planList']) || empty($res['classList'])){
            $res['courseInfo'] = get_object_vars($checkCourse);
            $this->assign("courseInfo",$res['courseInfo']);//课程信息
            $this->render("course/new.course.info.show.check.html");exit;
        }
        $sourceOrgName='';
        $sourceSubdomain = '';
        if($res['courseInfo']['userOwnerId']!=$this->orgOwner) {
            $sourceOrgInfo = user_organization::getOrgByOwner($res['courseInfo']['userOwnerId']);
            $sourceSubdomainInfo = user_organization::getSubdomainByUid($res['courseInfo']['userOwnerId']);
            if(!empty($sourceOrgInfo)){
                $sourceOrgName = $sourceOrgInfo->subname;
            }
            if(!empty($sourceSubdomainInfo)){
                $http_type = (defined("HTTPS") || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")) ? 'https://' : 'http://';
                $sourceSubdomain = $http_type.$sourceSubdomainInfo->subdomain;
            }
        }
        $hotTotal = 0;
        if($hotType==1){
            //报名人数
            $hotTotal = $res['courseInfo']['userTotal'];
        }elseif($hotType==2){
            //剩余名额
            $hotTotal = $res['courseInfo']["maxUser"]-$res['courseInfo']['userTotal'];
        } elseif($hotType==3){
            //播放数
            $hotTotal =$res['courseInfo']['vv'];
        }elseif($hotType==4){
            //不显示
            $hotTotal=-1;
        }
        $regSource = 1;
        if (!empty($res['courseInfo']['resellCourseType'])) {
            $regSource = 3;
            $resellOrgId = $tResellOrgId;
        }
        $classList = $planList = $teacherMasterList = [];
        $try=$try_plan=$recorded=0;

        if (!empty($res['classList'])) $classList = $res['classList'];
        if (!empty($res['planList'])) $planList = $res['planList'];
        if (!empty($res['try'])) $try= $res['try'];
        if (!empty($res['try_plan'])) $try_plan= $res['try_plan'];
        //if (!empty($res['teacherMasterList'])) $teacherMasterList = $this->_getTeacherMasterProfile($res['teacherMasterList']);
        if (!empty($res['defaultClassId'])) $defaultClassId = $res['defaultClassId'];
        if (!empty($res['recorded'])) $recorded= $res['recorded'];
        $http = (utility_net::isHTTPS() === true) ? "https://" : "http://";
        $curUrl = $http.'www.yunke.com';
        if ($this->subdomain) {
            $curUrl = $http.user_organization::course_domain($this->subdomain).'/course.info.show/'.$cid;
        }

        $page = isset($_REQUEST['page']) && $_REQUEST['page'] ? (int)($_REQUEST['page']) : 1;
        $ret = comment_api::courseAverage($cid);
        $course_score = 0;
        if($ret){
            $course_score = empty($ret->total_user)?0:round($ret->score/$ret->total_user,1);
            $course_score = $course_score ? $course_score : 0;
        }

        $courseTeacher = course_teacher_api::getTeacherByCourseId($cid);
        $courseTeacherIdArr = array();
        if(!empty($courseTeacher->result)){
            foreach($courseTeacher->result as $teacher){
                $courseTeacherIdArr[] = $teacher->fk_user_teacher;
            }
        }
        if(!empty($courseTeacherIdArr)){
            $teacherArr = [
                'q' => [
                    'teacher_id'=>implode(',',$courseTeacherIdArr),
                ],
                'f' => [
                    'name',
                    'real_name',
                    'thumb_med',
                    'teacher_id',
                    'desc'
                ]
            ];
            $teacherInfo = seek_api::seekTeacher($teacherArr);
            if(!empty($teacherInfo->data)){
                foreach($teacherInfo->data as $v){
                    $teacherMasterList[$v->teacher_id] = [
                        'teacherName'  => !empty($v->real_name) ? $v->real_name : $v->name,
                        'teacherId'    => $v->teacher_id,
                        'teacherThumb' => interface_func::imgUrl($v->thumb_med),
                        'desc' =>!empty($v->desc) ? mb_substr( $v->desc, 0, 35, 'utf-8')  : '',
                    ];
                }
            }
        }
        //获取教师预览视频信息
        if (!empty($teacherMasterList)){
            foreach ($teacherMasterList as $key => $value) {
                $teacherId[] = $key;
            }
            $previewVideoArr = array();
            if(!empty($teacherId)){
                $uidStr = implode(',',$teacherId);
                $preview_video = user_api::getPreviewVideoByUid($uidStr);
                if (!empty($preview_video->result)) {
                    foreach ($preview_video->result as $key => $value) {
                        if (!empty($value->uid)) {
                            $previewVideoArr[$value->uid] = $value->planid;
                        }
                    }
                }
            }
            foreach ($teacherMasterList as $key => $value) {
                if(array_key_exists($key, $previewVideoArr)){
                    $teacherMasterList[$key]['planid'] = $previewVideoArr[$key];
                }else{
                    $teacherMasterList[$key]['planid'] = '';
                }
            }

        }

        /*$teacherId = 0;
        if(isset($res['teacherMasterList'])) {
            foreach ($res['teacherMasterList'] as $item) {
                $teacherId = $item['teacherId'];
            }
        }
        */
        $like = 0;
        $member = array();
        $userMemberSet = array();
        $fileData = array();
        $attachData = array();
        $courseUser = array();

        $livePlanId = array();
        $livePlanName = array();
        $isLivePlanId = 0;
        $isLivePlanName = '';
        $theLivePlanId = 0;
        $theLivePlanName = '';
        $videoLivePlanId = 0;
        $videoLivePlanName = '';
        $videoRecordPlanId = 0;
        $videoRecordPlanName = '';
        $recordPlanIdArr = array();
        $recordPlanName = array();
        $userTotalTime = 0;
        $isMemberRegType = 0;
        $recordPlanType=array();
        //获取报名用户所属班级
        $user = user_api::loginedUser();
        if(!empty($user["uid"])) {
            $courseUser = course_api::getUserClassId($user["uid"], $cid);
            if(!empty($courseUser->result->fk_class)){
                $defaultClassId = $courseUser->result->fk_class;
                $classList1 =$classList[$defaultClassId];
                $classList=array();
                $classList[$defaultClassId] = $classList1;
                //$planList1 = $planList[$defaultClassId];
                //$planList = array();
                //$planList[$defaultClassId]=$planList1;
                //获取下载课件资料
                $attach = course_api::listPlanAttach(array('classId'=>$defaultClassId));
                if(!empty($attach) && !empty($attach->data)){
                    $attachData = $attach->data;
                    foreach($attachData as $ao){
                        $fidArr[] = '\''.$ao->attach.'\'';
                    }
                    $file = utility_file::getFileByFidArr($fidArr);
                    if(!empty($file)){
                        foreach($file as $fo){
                            $fileData[$fo->fid] = floor(($fo->size/1024)*10)/10;
                        }
                    }
                }

                //获取当前直播
                $planArr = [
                    'q'  => [
                        'class_id' => $courseUser->result->fk_class,
                        'status'    => '1,2,3'
                    ],
                    'f'  => [
                        'video_id',
                        'plan_id',
                        'course_type',
                        'section_name',
                        'section_desc',
                        'start_time',
                        'end_time',
                        'status',
                        'totaltime',
                        'try',
                        'live_public_type',
                        'video_public_type',
                        'vt',
                        'section_order_no'
                    ],
                    'ob' => ['section_order_no' => 'asc'],
                    'pl' => 100
                ];
                $planInfo = seek_api::seekPlan($planArr);
                if (!empty($planInfo->data)) {
                    foreach ($planInfo->data as $v) {
                        $recordPlanType[$v->plan_id] = 1;
                        if($v->video_public_type==-2) {
                            $recordPlanType[$v->plan_id] = 0;
                        }
                        if ($v->course_type==1) {
                            if($v->status==2) {
                                $isLivePlanId = $v->plan_id;
                                $isLivePlanName = $v->section_name . '  ' . $v->section_desc;
                                break;
                            }else{
                                $startTime = strtotime($v->start_time);
                                if($startTime>=time() && $startTime<=strtotime(date('Y-m-d H:i:s',time()+7200)) && $v->video_id==0){
                                    $theLivePlanId = $v->plan_id;
                                    $theLivePlanName = $v->section_name . '  ' . $v->section_desc;
                                    break;
                                }elseif(!empty($v->video_id)){
                                    $videoLivePlanId = $v->plan_id;
                                    $videoLivePlanName = $v->section_name . '  ' . $v->section_desc. '   <span class="fs14" style="color: #CCC;margin-left: 10px;">   ' .date("Y-m-d H:i",strtotime($v->start_time)).'</span>';
                                }
                            }
                            $livePlanId[]=$v->plan_id;
                            $livePlanName[]=$v->section_name . '  ' . $v->section_desc. '   <span class="fs14" style="color: #CCC;margin-left: 10px;">' .date("Y-m-d H:i",strtotime($v->start_time)).'</span>';
                        }elseif($v->course_type==2){
                            if(!empty($v->video_id)){
                                $videoRecordPlanId = $v->plan_id;
                                $videoRecordPlanName = $v->section_name . '  ' . $v->section_desc;
                                break;
                            }
                            $recordPlanIdArr[] = $v->plan_id;
                            $recordPlanName[] = $v->section_name . '  ' . $v->section_desc;
                        }
                    }
                }
                //获取用户学习时长
                $userCourseStat = stat_api::getUserCourseStudyTime($user["uid"],array($cid));
                if(!empty($userCourseStat[0])){
                    $userTotalTime = $userCourseStat[0]->sum_live+$userCourseStat[0]->sum_record;
                }
                $userTotalTime = (int)ceil($userTotalTime/60);
            }
            //会员是否有效与用户报名的班级
            $setIdArr = explode(',', $res['courseInfo']['setIdStr']);
            $member = org_member_api::checkIsMemberOrExpire($user["uid"], $setIdArr, $cid);
            $isMemberRegType =!empty($member["isMemberRegType"])?$member["isMemberRegType"]:0;
            if (!empty($member["userMemberSet"])&&!empty($member["isOpen"])) {
                foreach ($member["userMemberSet"] as $m) {
                    $userMemberSet[$m["fk_member_set"]] = $m["is_expire"];
                }
            }
            // 是否收藏该课程
            $likeInfo = user_api::listfav(
                array(
                    'cid' => $cid,
                    'uid' => $user["uid"]
                )
            );
            !empty($likeInfo) && $like = 1;
        }
        $teacherId = !empty($classList[$defaultClassId]["teacherId"])?$classList[$defaultClassId]["teacherId"]:$teacherId;
        $params['courseid']=$cid;//课程详情页客服

        $customer = org_api::getCusRelationList($params);
        $qqQun = !empty($customer->data->qqun)?$customer->data->qqun:array();
        $qq = !empty($customer->data->qq)?reset($customer->data->qq):array();
        $regUserList = course_plan_api::getCourseRegUser($cid, 0, $page, 10);
        $recordPlanTime =array();
        if(!empty($courseUser->result)){
            if (($isMemberRegType==1&&in_array(0,$userMemberSet))|| $isMemberRegType!=1){
                $record = course_detailed::getCourseUserPlanRecordListByClassIdAndUserId($courseUser->result->fk_class,$courseUser->result->fk_user);
                $recordArr = array();
                $recordTime = '';
                $recordPlanId = 0;
                if(!empty($record)){
                    foreach($record as $r){
                        $recordArr[$r->fk_plan]=$r;
                        if(empty($recordPlanId)){
                            $recordPlanId = $r->fk_plan;
                            $recordTime = $r->watch_time;
                        }else{
                            if(strtotime($recordTime)<strtotime($r->watch_time)){
                                $recordPlanId = $r->fk_plan;
                                $recordTime = $r->watch_time;
                            }
                        }
                    }
                }
                if(!empty($recordArr) && !empty($planList[$courseUser->result->fk_class])){
                    foreach($planList[$courseUser->result->fk_class] as &$p){
                        $recordPlanType[$p["planId"]] = 1;
                        $recordPlanTime[$p["planId"]] = 0;
                        if($p["video_public_type"]==-2) {
                            $recordPlanType[$p["planId"]] = 0;
                        }
                        if(!empty($recordArr[$p["planId"]]->time)&& $recordArr[$p["planId"]]->type==2){
                            $p["record"] = utility_tool::sec2time($recordArr[$p["planId"]]->time);
                            $p["playTime"]= $recordArr[$p["planId"]]->time;
                            $recordPlanTime[$p["planId"]] = $recordArr[$p["planId"]]->time;
                        }else{
                            $p["record"] = '';
                            $p["playTime"] = 0;
                        }
                        if(!empty($recordPlanId)&&$recordPlanId==$p["planId"]){
                            $recordText = '';
                            if($p["course_type"]==1){
                                $videoLivePlanId = $recordPlanId;
                                if(!empty($p["record"])){
                                    $recordText = '<span class="fs14" style="color: #CCC;margin-left: 10px;">   上次学到：' .$p["record"].'</span>';
                                }
                                $videoLivePlanName = $p["section_name"] . '  ' . $p["section_desc"].'   '.$recordText;
                            }elseif($p["course_type"]==2){
                                $videoRecordPlanId = $recordPlanId;
                                if(!empty($p["record"])){
                                    $recordText = '<span class="fs14" style="color: #CCC;margin-left: 10px;">   上次学到：' .$p["record"].'</span>';
                                }
                                $videoRecordPlanName = $p["section_name"] . '  ' . $p["section_desc"].'   '.$recordText;
                            }
                        }
                    }
                }
            }
        }
        $haveClassStatus = array(
            'livePlanId' => !empty($livePlanId[0])?$livePlanId[0]:0,//直播未开课planId
            'livePlanName' => !empty($livePlanName[0])?$livePlanName[0]:'',//直播未开课planName
            'isLivePlanId' => $isLivePlanId,//正在直播的planId
            'isLivePlanName' => $isLivePlanName,//正在直播的planName
            'theLivePlanId' => $theLivePlanId,//即将直播的planId
            'theLivePlanName' => $theLivePlanName,//即将直播的planName
            'videoLivePlanId' => $videoLivePlanId,//直播有视频planId
            'videoLivePlanName' => $videoLivePlanName,//直播有视频planName
            'videoRecordPlanId' => $videoRecordPlanId,//录播有视频planId
            'videoRecordPlanName' => $videoRecordPlanName,//录播有视频planName
            'recordPlanId' => !empty($recordPlanIdArr[0])?$recordPlanIdArr[0]:0,//录播没有视频时planId
            'recordPlanName' => !empty($recordPlanName[0])?$recordPlanName[0]:'',//录播没有视频时planName
            'recordPlanType'=>$recordPlanType,//视频是否隐藏
            'recordPlanTime'=>$recordPlanTime,//视频上次观看时间
        );
        $this->assign('qudaoCode',$qudaoCode);
        $this->assign('sourceSubdomain',$sourceSubdomain);//来源机构域名
        $this->assign('sourceOrgName',$sourceOrgName);//来源机构名称
        $this->assign('isMemberRegType',$isMemberRegType);//用户是否会员报名
        $this->assign('regUserList',$regUserList);//报名用户列表
        $this->assign('courseUser',$courseUser);//用户报名信息
        $this->assign('userTotalTime',$userTotalTime);//报名用户学习时间
        $this->assign('haveClassStatus',$haveClassStatus);//学习记录上课状态
        $this->assign('recorded',$recorded);//是否有录播
        $this->assign('qqQun',$qqQun);//qq群
        $this->assign('qq',$qq);//客服qq
        $this->assign('hotType',$hotType);//课程热度类型
        $this->assign('hotTotal',$hotTotal);//课程热度数量
        $this->assign('attachData',$attachData);//课件列表
        $this->assign('fileData',$fileData);//课件大小对应
        $this->assign('member',$member);//会员是否有效与用户报名的班级
        $this->assign('userMemberSet',$userMemberSet);//用户开通过的会员是否过期对应
        $this->assign('like',$like);//是否收藏
        //$this->assign('teacherId',$teacherId);//老师Id
        $this->assign('course_score',!empty($course_score)?sprintf("%.1f",$course_score):0);//课程评分
        $this->assign("try",$try);//是否可以试看
        $this->assign("try_plan",$try_plan);//试看的排课
        $this->assign("classList",$classList);//班级列表
        $this->assign("planList",json_encode($planList));//章节列表
        $this->assign("teacherMasterList",$teacherMasterList);//老师列表
        $this->assign("classId",$defaultClassId);//初始或报名的班级Id
        $this->assign("className",!empty($classList[$defaultClassId]['className']) ? $classList[$defaultClassId]['className'] : '');//初始或报名的班级名称
        $this->assign("classNum",count($classList));//班级数量
        $this->assign("courseInfo",$res['courseInfo']);//课程信息
        $this->assign("qrCode",utility_cdn::qrcode("qr?s=200&t=".urlencode($curUrl)));//二维码
        $this->assign("page",$page);//页码
        $this->assign("memberSetList",$res['courseInfo']['memberset']);//会员列表
        $this->assign("regSource",$regSource);//报名类型
        $this->assign("resellOrgId",$resellOrgId);//分销机构ID
        $this->render("course/new.course.info.show.html");
    }
//准备弃用 longhouan _getTeacherMasterProfile()
    private function _getTeacherMasterProfile($teacherMasterList)
    {
        $userIdArr = array_column($teacherMasterList, 'teacherId');
        if (count($userIdArr) < 1) return $teacherMasterList;

        $params = ['userIdArr' => $userIdArr];

        $userInfo = utility_services::call('/user/profile/TeacherProfile', $params);
        if (!empty($userInfo->result)) {
            foreach ($userInfo->result as $v) {
                $profile[$v->fk_user] = [
                    //'desc' => utility_tool::cut_str($v->desc, 34)
                    'desc' => $v->desc
                ];
            }
        }

        foreach ($teacherMasterList as &$item) {
            $item['desc'] = !empty($profile[$item['teacherId']]['desc']) ? $profile[$item['teacherId']]['desc'] : '';
        }

        return $teacherMasterList;
    }

    public function pageComment($inPath)
    {
        utility_cache::pageCache(600);
        $page = isset($_REQUEST['page']) && $_REQUEST['page'] ? (int)($_REQUEST['page']) : 1;
        $courseId = 0;
        if (isset($inPath[3]) && (int)($inPath[3])) {
            $courseId = (int)($inPath[3]);
        }
                $data = comment_api::getCourseCommentList($courseId, 0, $page,20);  //新的评分列表 去重后的
                $st = new stdclass();
                $st->pk_replay = '';
                $st->fk_comment = '';
                $st->fk_user = '';
                $st->manage_name = '';
                $st->contents = '';
                $st->replay_time = '';
                $st->status = '';
                if(isset($data['data']) && !empty($data['data'])){
                    foreach($data['data'] as &$value){
                        $replay = comment_api::showReplay($value['commentId'],'');
                        $show_replay = json_decode($replay);
                        $value['time'] = date("Y")==date('Y',strtotime($value['time'])) ? date('m-d H:i',strtotime($value['time'])) :date('Y-m-d H:i',strtotime($value['time']));
                        if($show_replay->code==0){
                            $value['replay'] = $show_replay->result;
                            $value['replay']->replay_time = (date('Y')==date('Y',strtotime($value['replay']->replay_time)) ?  date('m-d H:i',strtotime($value['replay']->replay_time)) : date('Y-m-d H:i',strtotime($value['replay']->replay_time)));
                        }else{
                            $value['replay'] = $st;
                        }
                    }
                }
        $domainConf = SConfig::getConfig(ROOT_CONFIG.'/const.conf', 'platform');
        $act = empty($_REQUEST['act'])?'':$_REQUEST['act'];
        if($act=='ajax'){
            $result = new stdClass;
            if(!empty($data)){
                $result->code = 1;
                $result->data = $data['data'];
                $result->platformUrl = $domainConf->platform;
                $result->totalPage = $data['totalPage'];
            }else{
                $result->code = -1;
                $result->error = '没有数据';
            }

            return json_encode($result);
        }
        $ret = comment_api::courseAverage($courseId);
        $course_score = 0;
        if($ret){
            $course_score = empty($ret->total_user)?0:round($ret->score/$ret->total_user,1);
        }
        if(!isset($data['totalSize'])) $data['totalPage'] = 0; $totalSize = 0;
        $this->assign('total_size',$totalSize);
        $this->assign('course_score',$course_score);
        $this->assign('platformUrl', $domainConf->platform);
        $this->assign('commentList', $data);
        $this->assign('page', $page);
        $this->assign('courseId', $courseId);
        //$this->assign('scoreInfo', comment_api::getOneCourseScoreInfo($courseId));
        $this->render("course/newcourse.detailv2.comment.html");
    }

    public function pageOpenVip($inPath)
    {
        $setId = isset($inPath[3]) && (int)$inPath[3] ? (int)$inPath[3] : 0;
        !$setId && $this->redirect('/course.list');

        $uid = user_api::getLoginUid();
        !$uid && $this->redirect('/course.list');

        $info = org_member_api::getMemberSetInfo($setId);
        empty($info) && $this->redirect('/course.list');

        $userMember = user_organization::checkMemberByUidAndSetId($uid, $setId);
        $time = date('Y-m-d', time());
        if (!empty($userMember)) {
            $time = $userMember->end_time;
        }

        $this->assign('info', $info);
        $this->assign('time', $time);
        $this->assign('setId', $setId);
        $this->render('course/open-vip.html');
    }



    /**params page courseId
     * return json
     */
    public function pageCommentAjax($inPath)
    {
        //utility_cache::pageCache(600);
        $page = isset($_GET['page']) && !empty($_GET['page']) ? (int)($_GET['page']) : 1;
        $courseId = isset($_GET['courseId']) && !empty($_GET['courseId']) ? (int)($_GET['courseId']) : 0;
        $time = isset($_GET['time']) && !empty($_GET['time']) ? (int)($_GET['time']) : '';
        $length = isset($_GET['length']) && !empty($_GET['length']) ? (int)($_GET['length']) : 20;
        $score = isset($_GET['score']) && !empty($_GET['score']) ? (int)($_GET['score']) : 0;
        $me = isset($_GET['me']) && !empty($_GET['me']) ? $_GET['me'] : '';
        $userId = '';
        if($me=='me'){
            $user = user_api::loginedUser();
            $userId = empty($user) ? '' : $user['uid'];
            if($me && empty($userId)){
                return interface_func::setMsg(100001,'请登录');
            }
        }
        $data = comment_api::CommentList($courseId,$score,$time, 0 ,$page,$length,$userId);  //获取评论列表
        if(empty($data['data'])) return interface_func::setMsg(30001,'没有评论');
        $st = new stdclass();
        $st->pk_replay = '';
        $st->fk_comment = '';
        $st->fk_user = '';
        $st->manage_name = '';
        $st->contents = '';
        $st->replay_time = '';
        $st->status = '';
        if(isset($data['data']) && !empty($data['data'])){
            $commentIdArr = array();
            foreach($data['data'] as &$v){
                $v['time'] = date("Y")==date('Y',strtotime($v['time'])) ? date('m-d H:i',strtotime($v['time'])) :date('Y-m-d H:i',strtotime($v['time']));
                $commentIdArr[] = $v['commentId'];
            }
            $ret = comment_api::getTeacherReplayByCommentIdArr($commentIdArr);
            if(0==$ret->code){
                $replayArr = array();
                foreach($ret->result->items as $item){
                    $replayArr[$item->fk_comment] = $item;
                }
                $replays = array_keys($replayArr);
                foreach($data['data'] as &$value){
                    if(in_array($value['commentId'],$replays)){
                        $value['replay'] = $replayArr[$value['commentId']];
                        $value['replay']->replay_time = (date('Y')==date('Y',strtotime($value['replay']->replay_time)) ?  date('m-d H:i',strtotime($value['replay']->replay_time)) : date('Y-m-d H:i',strtotime($value['replay']->replay_time)));
                    }else{
                        $value['replay'] = $st;
                    }
                }
            }
        }
        return interface_func::setData($data);
    }

    /**
     *获得未评论的章节
     */
    public function pagePlanCommentStatus(){
        $user = user_api::loginedUser();
        $userId = empty($user) ? 0 : $user['uid'];
        if(empty($userId)){
            return interface_func::setMsg(100001,'请登录');
        }
        $courseId = !empty($_GET['courseId']) ? (int)$_GET['courseId'] : 0;
        $classId  = !empty($_GET['classId']) ? (int)$_GET['classId'] : 0;
        if(empty($courseId)|| empty($userId) || empty($classId)){
            return interface_func::setMsg(100002,'参数错误');
        }
        $res = comment_api::checkIsAddScoreAndCourseId($courseId, $userId, $classId);
        if(empty($res)) return interface_func::setMsg(100003,'数据为空');
        return interface_func::setData(array('list'=>array_values($res)));
    }
}
