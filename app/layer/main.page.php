<?php
class layer_main extends STpl
{
    public function pageUserLogin($inPath)
    {
        $cid = !empty($inPath[3]) ? (int)($inPath[3]) : 0;          // [必填] 课程ID
        $classId = !empty($inPath[4]) ? (int)($inPath[4]) : 0;      // [必填] 班级ID
        $num = !empty($inPath[5]) ? (int)($inPath[5]) : 0;          // [必填] 班级个数
        $source = !empty($inPath[6]) ? trim($inPath[6]) : 0;        // [必填] 类型 (like 收藏 ；memberset 马上开通 ；reg 立即报名/注册/登录 ； )
		$setId = !empty($inPath[7]) ? trim($inPath[7]) : 0; // [必填] 会员类型ID
        $resellOrgId = !empty($inPath[8]) ? trim($inPath[8]):0;     // 分销机构ID
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
        $this->assign('cid', $cid);
        $this->assign('num', $num);
        $this->assign('source', $source);
        $this->assign('classId', $classId);
        $this->assign('setId', $setId);
        $this->assign('resellOrgId', $resellOrgId);
        $this->assign('qudaoCode', $qudaoCode);
        $this->render("layer/user.login.html");
    }

    public function pageLogin($inPath)
    {
        $teacherId = !empty($inPath[4]) ? (int)($inPath[4]) : 0;
        $source = !empty(trim($inPath[3])) ? trim($inPath[3]) : 0;

        $this->assign('teacherId', $teacherId);
        $this->assign('source', $source);
        $this->render("layer/login.new.html");
    }

    // todo
    public function pageLoginAjax($inPath){
        $cid = !empty($inPath[3]) ? (int)($inPath[3]) : 0;
        $classId = !empty($inPath[4]) ? (int)($inPath[4]) : 0;
        $num = !empty($inPath[5]) ? (int)($inPath[5]) : 0;
        $source = !empty($inPath[6]) ? trim($inPath[6]) : "reg";
		$setId = !empty($inPath[7]) ? trim($inPath[7]) : 0;
        $resellOrgId = !empty($inPath[8]) ? trim($inPath[8]) : 0;
		
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
                $result = user_api::getUserAddress($uid);
                $document = course_api::getCourseOne($cid);
                if (empty($result->result->items) && !empty($document->document)) {
                    $this->_setMsg('没有收货地址', -7);           
                }
                if ($cid && $classId && $num =1) {
                    $regInfo = $this->_addRegistration($cid, $classId,$resellOrgId);
                    if ($regInfo === true) {
                        $user     = user_api::loginedUser();
                        $org      = user_organization::subdomain();
                        $orgInfo = user_organization::getOrgByOwner($org->userId);
                        $info = array(
                            "userId"=>$user['uid'],
                            "courseId"=>$cid,
                            "orgId"=>!empty($orgInfo->oid)?$orgInfo->oid:0,
                        );
                        user_organizationStudent_api::addOrganizationStudent($info);
                        $this->_setMsg('success');
                    } elseif ($regInfo['code'] === -2){
                        $this->_setMsg('请先支付', -2,$regInfo['classId']);
                    } elseif($regInfo['code'] === -6) {
                        $this->_setMsg('名额已满', -6);
                    } else {
                        $this->_setMsg('登录之后报名失败', -4);
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
			if($source === 'memberset'){
				$this->_setMsg('success',100,$setId);
			}
            $this->_setMsg('非法登录', 7);
        }else{
            $this->_setMsg('账号或者密码错误', 5);
        }

        $this->_setMsg('登录失败', 6);
    }

    public function pageDoLogin($inPath){
        if (!isset($inPath[3]) || !$source = trim($inPath[3]))
            return interface_func::setMsg(1042);

        $tid = !empty($inPath[4]) ? (int)($inPath[4]) : 0;
        if (!$tid) return interface_func::setMsg(1000);

        if (empty($_REQUEST['uname'])) return interface_func::setMsg(1040);
        if (empty($_REQUEST['pass'])) return interface_func::setMsg(1041);

        if(utility_valid::mobile($_REQUEST['uname']) === false){
            return interface_func::setMsg(1012);
        }

        if(user_api::isRegister($_REQUEST['uname']) === false){
            return interface_func::setMsg(2014);
        }

        $forever=false;
        if(!empty($_REQUEST['forever'])){
            $forever=true;
        }

        user_api::login($_REQUEST['uname'],$_REQUEST['pass'],$forever);

        $uid = !empty(utility_session::get()['user']['uid']) ? utility_session::get()['user']['uid'] : 0;
        if (!$uid) return interface_func::setMsg(1039);

        switch ($source) {
            case 'addFavTeacher':
                $params = [$uid, $tid];
                if ($this->_action([new teacher_api, $source], $params))  return interface_func::setMsg(0);

                return interface_func::setMsg(1);
                break;
            default:
                return interface_func::setMsg(1);
        }
    }

    public function pageChangeClass($inPath)
    {
        $cid = !empty($inPath[3]) ? (int)($inPath[3]) : 0;
        $uid = !empty($inPath[4]) ? (int)($inPath[4]) : 0;
        $classId = !empty($inPath[5]) ? (int)($inPath[5]) : 0;
        $lan = !empty($inPath[6]) ? $inPath[6] : '';
        $cookie = !empty($_COOKIE['language']) ? $_COOKIE['language'] : '';
        $flag = false;
        if(!empty($lan) && $lan != $cookie){
            setcookie('language',$lan);
            $flag = true;
        }
        if($flag){
            echo "<script>location.reload(true);</script>";
            $flag = false;
        }
        $res = course_detailed::getCoursePlan(['cid'=>$cid]);
        $info = array();
        if(!empty($res))
        {
            $userInfo = user_api::getStudentProfile($uid);
            foreach($res[$cid] as $v)
            {
                foreach ($v as $m)
                {
                    if($classId == $m['classId'])
                    {
                        $info = [
                            'class_name' => !empty($m['className'])?$m['className']:'',
                            'teacher_name'=> !empty($m['adminName'])?$m['adminName']:'',
                            'classBeginTime'=> !empty($m['progress'])?$m['progress']:'',
                            'classSectionName'=>!empty($m['sectionName'])?$m['sectionName']:'',
                            'num' => !empty(count($v))?count($v):0
                        ];
                    }
                }
            }
            $course = course_api::getCourseone($cid);
            $info['courseName'] = '';
            if(!empty($course))
            {
                if($lan == 'en'){
                    ($course->fee_type==0) ? $info['price'] = 'free' : ($info['price'] = '￥'.$course->fee->price);
                }else{
                    ($course->fee_type==0) ? $info['price'] = '免费' : ($info['price'] = '￥'.$course->fee->price);
                }
                $info['courseName'] = !empty($course->title)?$course->title:'';
            }
            $info['studentName'] = isset($userInfo->student_name)?$userInfo->student_name:'';
        }
        $result = $this->_getOrgCourseClass();
        $rclass = !empty($result['class']) ? $result['class'] : '';
        $this->assign('course',$result['course']);
        $this->assign('info',$info);
        $this->assign('uid',$uid);
        $this->assign('classId',$classId);
        $this->assign('cid',$cid);
        $this->assign('class',json_encode($rclass));
        $this->render("layer/change.class.html");
    }

    public function pagePlayerComment($inPath)
    {
        $teacherId = isset($_REQUEST['teacherId']) && (int)($_REQUEST['teacherId']) ? (int)($_REQUEST['teacherId']) : 0;
        $planId = isset($_REQUEST['planId']) && (int)($_REQUEST['planId']) ? (int)($_REQUEST['planId']) : 0;
        $courseId = isset($_REQUEST['courseId']) && (int)($_REQUEST['courseId']) ? (int)($_REQUEST['courseId']) : 0;
        $userOwner = isset($_REQUEST['userOwner']) && (int)($_REQUEST['userOwner']) ? (int)($_REQUEST['userOwner']) : 0;

        if (!$courseId || !$planId) {
            return interface_func::setMsg(1000);
        }
        $user = user_api::loginedUser();
        if (empty($user)) return interface_func::setMsg(1021);
        $commentAndScore = comment_api::getCommentAndScore($courseId, $user['uid'], $planId);

        $this->assign('comment', $commentAndScore['commentStr']);
        $this->assign('studentScore', $commentAndScore['studentScore']);
        $this->assign('descScore', $commentAndScore['descScore']);
        $this->assign('explainScore', $commentAndScore['explainScore']);
        $this->assign('avgScore', $commentAndScore['avgScore']);
        $this->assign('teacherId', $teacherId);
        $this->assign('courseId', $courseId);
        $this->assign('planId', $planId);
        $this->assign('userOwner', $userOwner);
        $this->display("layer/player.comment.html");
    }

    private function _setMsg($msg, $code=0,$data='')
    {
        exit(json_encode(['code'=>$code, 'message'=>$msg,'data'=>$data]));
    }

    private function _addRegistration($cid, $classId,$resellOrgId=0)
    {
        $user     = user_api::loginedUser();
        if (empty($user)) {
            return false;
        }

        $courseInfo = course_api::getCourseone($cid);
        if (empty($courseInfo)) {
            return false;
        }
        $uid = $user['uid'];
		$isMember = 0;
		$setIdArr  = course_api::getCourseOpenMemberSetIdArr($cid);
		$memberRet = org_member_api::checkIsMemberOrExpire($uid, $setIdArr, $cid);
		if($memberRet['isMember'] == 1 && $memberRet['isExpire'] == 0){
			$isMember = 1;
		}
		if(!empty($memberRet['regId']) && $memberRet['regId'] != $classId ){
			$classId = $memberRet['regId'];
		}
        $resell = new stdClass();
        if(!empty($resellOrgId)){
            $resell = course_resell_api::getResellCourselist(1,1,array("cid"=>$cid,"uid"=>$resellOrgId,"status"=>'n','sort'=>''));
            if(!empty($resell->data->items)) {
                $resell = $resell->data->items[0];
                if($resell->ver==$resell->promote_ver&&$resell->status==1&&$resell->status==$resell->promote_status&&$resell->price_resell>0){
                    $courseInfo->fee_type = 1;
                    $courseInfo->fee->price = $resell->price_resell;
                }else{
                    $courseInfo->fee_type = 0;
                    $courseInfo->fee->price = 0;
                }

            }
        }
		if($isMember == 0 ){
			if (($courseInfo->fee_type == 1 && !empty($courseInfo->fee->price)) || (!empty($resell->ver)&&$resell->ver==$resell->promote_ver&&$resell->status==1&&$resell->status==$resell->promote_status&&$resell->price_resell>0)) {
				$ret['code'] = -2;
				$ret['classId'] = $classId;
				return $ret;
			}
		}
		
        $ClassData = course_api::getclass($classId);
        if (empty($ClassData)) {
            return false;
        }
		if(empty($memberRet['regId'])){
			if ($ClassData->user_total >= $ClassData->max_user) {
				$ret['code'] = -6;
				$ret['classId'] = $classId;
				return $ret;
			}
		}

        $data = array(
            "class_id"   => $classId,
            "course_id"  => $cid,
            "uid"        => $user['uid'],
            "user_owner" => $courseInfo->user_id,
            "status"     => 1,
        );

        return course_api::addregistration($data);
    }

    private function _addFav($uid, $cid)
    {
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

    /*
     * 机构下老师的课程列表
     * @author zhengtianlong
     */
    public function _getOrgCourseClass()
    {
        $org      = user_organization::subdomain();
        $orgOwner = !empty($org) ? $org->userId : 0;
        $courseInfo= course_api::getCourselistByOid(1,1000,$orgOwner);
        $data = [];
        if(!empty($courseInfo->data))
        {
            foreach($courseInfo->data as $val)
            {
                if(!empty($val->class))
                {
                    $data['course'][] = [
                        'course_id' => $val->course_id,
                        'title'     => $val->title
                    ];
                    foreach($val->class as $v)
                    {
                        $data['class'][$val->course_id][] = [
                            'class_id' => $v->class_id,
                             'name' => $v->name
                        ];
                    }
                }
            }
        }
        return $data;
    }

    private function _action(callable $func, $param)
    {
        if(!is_callable($func, false, $callable_name)){
            SLog::fatal("method does not found,param[%s]", var_export(func_get_args(), 1));
            return false;
        }

        return call_user_func_array($func, $param);
    }

    public function pageAddUserAddressLayer($inPath){
        $type = !empty($inPath['3']) ? $inPath['3'] : 0;
        if ($type == 'm') {//手机
            $source = !empty($inPath['4']) ? (int)($inPath['4']) : 0;
            $cid = !empty($inPath['5']) ? (int)($inPath['5']) : 0;
            $classId = !empty($inPath['6']) ? (int)($inPath['6']) : 0;
            $resellOrgId = !empty($inPath['7']) ? trim($inPath['7']) : "reg";
            $login_url = !empty($inPath['8']) ? trim($inPath['8']) : "";
            $qudaoCode = "";
            if(!is_numeric($resellOrgId)){
                if(substr_count($resellOrgId,'%7C')>0){
                    $resellOrgId = str_replace("%7C","|",$resellOrgId);
                }
                if(substr_count($resellOrgId,'d')==1 &&substr_count($resellOrgId,'|')==2){
                    $qudaoCode = $resellOrgId;
                }
                $resellOrgId=0;
            }
        }elseif ($type == 'w') {//微信
            $cid = !empty($inPath['4']) ? (int)($inPath['4']) : 0;
            $classId = !empty($inPath['5']) ? (int)($inPath['5']) : 0;
            $num = !empty($inPath['6']) ? (int)($inPath['6']) : 0;
            $source = !empty($inPath['7']) ? trim($inPath['7']) : "reg";
            $setId = !empty($inPath['8']) ? trim($inPath['8']) : 0;
            $resellOrgId = !empty($inPath['9']) ? trim($inPath['9']) : 0;
            $qudaoCode = "";
            if(!is_numeric($resellOrgId)){
                if(substr_count($resellOrgId,'%7C')>0){
                    $resellOrgId = str_replace("%7C","|",$resellOrgId);
                }
                if(substr_count($resellOrgId,'d')==1 &&substr_count($resellOrgId,'|')==2){
                    $qudaoCode = $resellOrgId;
                }
                $resellOrgId=0;
            }
        }else{
            $cid = !empty($inPath['3']) ? (int)($inPath['3']) : 0;
            $classId = !empty($inPath['4']) ? (int)($inPath['4']) : 0;
            $num = !empty($inPath['5']) ? (int)($inPath['5']) : 0;
            $source = !empty($inPath['6']) ? trim($inPath['6']) : "reg";
            $setId = !empty($inPath['7']) ? trim($inPath['7']) : 0;
            $resellOrgId = !empty($inPath['8']) ? trim($inPath['8']) : 0;
            $qudaoCode = "";
            if(!is_numeric($resellOrgId)){
                if(substr_count($resellOrgId,'%7C')>0){
                    $resellOrgId = str_replace("%7C","|",$resellOrgId);
                }
                if(substr_count($resellOrgId,'d')==1 &&substr_count($resellOrgId,'|')==2){
                    $qudaoCode = $resellOrgId;
                }
                $resellOrgId=0;
            }
        }
       
        $level0  = region_api::listRegion(0);
        if (isset($level0)) {
            $this->assign('level0',$level0);
        }
        $this->assign('type', $type);
        $this->assign('cid', $cid);
        if (isset($num)) {
            $this->assign('num', $num);
        }        
        $this->assign('source', $source);
        $this->assign('classId', $classId);
        if (isset($setId)) {
           $this->assign('setId', $setId);
        }        
        $this->assign('resellOrgId', $resellOrgId);
        $this->assign('qudaoCode', $qudaoCode);
        if (isset($login_url)) {
           $this->assign('login_url', $login_url);
        }          
        $this->render("layer/received.course.info.html");
    }

    public function pageRegAjax($inPath){
        $cid = !empty($inPath['3']) ? (int)($inPath['3']) : 0;
        $classId = !empty($inPath['4']) ? (int)($inPath['4']) : 0;
        $num = !empty($inPath['5']) ? (int)($inPath['5']) : 0;
        $source = !empty($inPath['6']) ? trim($inPath['6']) : "reg";
        $setId = !empty($inPath['7']) ? trim($inPath['7']) : 0;
        $resellOrgId = !empty($inPath['8']) ? trim($inPath['8']) : 0;        
        if ($source === 'reg') {
            if ($cid && $classId && $num =1) {
                        $regInfo = $this->_addRegistration($cid, $classId,$resellOrgId);
                        if ($regInfo === true) {
                            $user     = user_api::loginedUser();
                            $org      = user_organization::subdomain();
                            $orgInfo = user_organization::getOrgByOwner($org->userId);
                            $info = array(
                                "userId"=>$user['uid'],
                                "courseId"=>$cid,
                                "orgId"=>!empty($orgInfo->oid)?$orgInfo->oid:0,
                            );
                            user_organizationStudent_api::addOrganizationStudent($info);
                            $this->_setMsg('success');
                        } elseif ($regInfo['code'] === -2){
                            $this->_setMsg('请先支付', -2,$regInfo['classId']);
                        } elseif($regInfo['code'] === -6) {
                            $this->_setMsg('名额已满', -6);
                        } else {
                            $this->_setMsg('登录之后报名失败', -4);
                        }
            } elseif ($num>1) {
                        $this->_setMsg('多个班级未登录报名直接跳转', -3);
            } else {
                        $this->_setMsg('缺少班级和课程编号', -4);
            }
        }
    }

}
