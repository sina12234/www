<?php

class course_ajax
{
    public function pageDelComment()
    {
        $user = user_api::loginedUser();
        if (empty($user['uid'])) return interface_func::setMsg(1021);

        $r = interface_func::isValidId(['userId', 'planId', 'courseId'], $_POST);
        if (!empty($r['code'])) return interface_func::setMsg($r['code']);

        if ($user['uid'] != $r['userId']) {
            return interface_func::setMsg(1024);
        }

        $params = [
            'userId' => $r['userId'],
            'planId' => $r['planId'],
            'courseId' => $r['courseId']
        ];

        $res = utility_services::call('/comment/course/DelComment', $params);

        if (!empty($res->code)) return interface_func::setMsg(1);

        return interface_func::setMsg(0);
    }


    public function pageGetCourseRegUser()
    {
        $page = isset($_REQUEST['page']) && $_REQUEST['page'] ? (int)($_REQUEST['page']) : 1;
        $courseId = 0;
        if (!empty($_POST['cid'])) {
            $courseId = (int)($_POST['cid']);
        }

        if (!$courseId) return interface_func::setMsg(1000);

        $info = course_plan_api::getCourseRegUser($courseId, 0, $page, 10);
        if (!empty($info)) return interface_func::setData(['regUserList' => $info]);

        return interface_func::setMsg(3002);
    }

    public function pageCheckUserIsRegAndLike()
    {
        $r = interface_func::isValidId(['userId', 'courseId'], $_POST);
        if (!empty($r['code'])) return interface_func::setMsg($r['code']);

        $userId   = (int)($r['userId']);
        $courseId = (int)($r['courseId']);
        if ($userId != user_api::getLoginUid()) {
            return interface_func::setMsg(1021);
        }
        $setIdArr = explode(',', $_POST['setIdStr']);
        $res      = org_member_api::checkIsMemberOrExpire($userId, $setIdArr, $courseId);

        // 是否收藏该课程
        $likeInfo = user_api::listfav(
            array(
                'cid' => $courseId,
                'uid' => $userId
            )
        );

        $like = 0;
        !empty($likeInfo) && $like = 1;
        $res['isLike'] = $like;

        return interface_func::setData($res);
    }
    
    public function pageGetUserWillBeginRemindAjax($inPath){    
            $uid = empty($_REQUEST['uid']) ? 0 : (int)$_REQUEST['uid'];      
            if(empty($uid)) {
                $user = user_api::loginedUser();
                $uid = empty($user['uid']) ? 0 : (int) $user['uid'] ;
            }
            if(empty($uid)) return interface_func::setMsg(1000);            
                        
            $ret = new stdClass();   
            $ret->code = 0;
            $ret->msg  = "success";          
            
            // 获取即将开课的排课
            $UserWillBeginPlanClassObj = course_plan_api::getUserWillBeginPlanClassIds();

            if(empty($UserWillBeginPlanClassObj->willBeginClassPlan)) return interface_func::setMsg(5001,'没有即将开课的课程');     
            $classIds = $UserWillBeginPlanClassObj->classIds;
            $willBeginClassPlanArr = $UserWillBeginPlanClassObj->willBeginClassPlan;
            
            // 获取当前用户+当前班级的报名信息
            $userParams = array('condition'=>"fk_user={$uid} and fk_class in($classIds)");
            $UserRegListObj = course_plan_api::getCourseUserList($userParams);
            if(empty($UserRegListObj->data)) return interface_func::setMsg(5002,'您没有即将开课的课程');
      
            // 获取课程名称，章节名称，班级等详细信息
            $result = course_plan_api::getUserWillBeginPlanListData($UserRegListObj,$willBeginClassPlanArr,10);
            
            $ret->data = $result;
            //$ret = json_encode($ret);   

            return $ret;
    }
    
    /* 保存班级分组 data={"data":[{"pid":4,"cid":3559,"classid":"1499","gname":"0A组","tid":"2932"},{"cid":3559,"classid":"1499","gname":"0B组","tid":"2932"}]} */
    public function pageSaveCourseClassGroupAjax($inPath){    
        $inputData = $_REQUEST;
        
        $params['data']     = isset($inputData['data'])  ? strip_tags($inputData['data']) : '';

        if(empty($params['data'])) return interface_func::setMsg(1000); 
    
        $res = course_group_api::saveCourseClassGroup($params);

        return $res;
    }
    /* 班级分组列表 cid=3559&classid=1499&rtime=0 */     
    public function pageGetCourseClassGroupListAjax($inPath){ 
        $inputData = $_REQUEST;
        $params['cid']      = empty($inputData['cid']) ? 0 : (int) $inputData['cid'];
        $params['classid']  = empty($inputData['classid']) ? 0 : (int) $inputData['classid'];
        $params['guser']    = empty($inputData['guser']) ? 0 : (int) $inputData['guser'];
        $params['rtime']    = empty($inputData['rtime']) ? 0 : (int) $inputData['rtime']; 
        if(empty($params['cid']) || empty($params['classid'])) return interface_func::setMsg(1000);   
        
        $groupList = course_group_api::getCourseClassGroupList($params);
        
        if (!empty($groupList->result)) {
            return $groupList;
        } else {
            return interface_func::setMsg(5001,'获取数据失败');
        }
    }
    
    /* 保存班级分组 cid=3559&classid=1499&gname=A组&tid=2932 */
    public function pageAddCourseClassGroupAjax($inPath){    
        $inputData = $_REQUEST;
        $params['cid']      = empty($inputData['cid']) ? 0 : (int) $inputData['cid'];
        $params['classid']  = empty($inputData['classid']) ? 0 : (int) $inputData['classid'];
        $params['tid']      = empty($inputData['tid']) ? 0 : (int) $inputData['tid'];   
        $params['gname']    = isset($inputData['gname'])  ? strip_tags($inputData['gname']) : '';

        if(empty($params['cid']) || empty($params['classid']) || empty($params['tid']) || empty($params['gname'])) return interface_func::setMsg(1000); 
    
        $res = course_group_api::addCourseClassGroup($params);

        return $res;
    }    
    
    /* 修改班级分组 cid=3559&classid=1499&gname=A组&tid=2932 */
    public function pageUpdateCourseClassGroupAjax($inPath){    
        $inputData = $_REQUEST;
        $params['cid']      = empty($inputData['cid']) ? 0 : (int) $inputData['cid'];
        $params['classid']  = empty($inputData['classid']) ? 0 : (int) $inputData['classid'];
        $params['tid']      = empty($inputData['tid']) ? 0 : (int) $inputData['tid'];   
        $params['gname']    = isset($inputData['gname'])  ? strip_tags($inputData['gname']) : '';
        
        if(empty($params['cid']) || empty($params['classid']) || empty($params['tid']) || empty($params['gname'])) return interface_func::setMsg(1000); 
        
        $res = course_group_api::updateCourseClassGroup($params);

        return $res;
    }
    
    /* 修改班级分组 gid=5 */
    public function pageDelCourseClassGroupAjax($inPath){    
        $inputData = $_REQUEST;
        $params['gid']  = empty($inputData['gid']) ? 0 : (int) $inputData['gid'];
        $params['st']   = -1;
        
        if(empty($params['gid'])) return interface_func::setMsg(1000); 
        
        $res = course_group_api::updateCourseClassGroup($params);

        return $res;
    }
    
    /* 班级分组下学生列表 gid=4&rtime=0 */     
    public function pageGetCourseClassGroupUserListAjax($inPath){ 
        $inputData = $_REQUEST;
        $params['gid']      = empty($inputData['gid']) ? 0 : (int) $inputData['gid'];
        $params['guser']    = empty($inputData['guser']) ? 0 : (int) $inputData['guser'];
        $params['rtime']    = empty($inputData['rtime']) ? 0 : (int) $inputData['rtime']; 
        if(empty($params['gid'])) return interface_func::setMsg(1000);   
        
        $groupUserList = course_group_api::getCourseClassGroupUserList($params);
        
        if (!empty($groupUserList->result)) {
            return $groupUserList;
        } else {
            return interface_func::setMsg(5001,'获取数据失败');
        }
    }
}
