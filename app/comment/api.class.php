<?php

class comment_api
{
    public static function getComment($data, $user)
    {
        if (empty($data["course_id"])) {
            return false;
        }
        $params            = new stdclass;
        $params->course_id = $data["course_id"];
        $params->user_id   = $user;
        if (isset($data["plan_id"])) {
            $params->plan_id = $data["plan_id"];
        }
        $ret = utility_services::call("/comment/course/getcomment", $params);
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }

    /**
     * @param commentIdArr  array(1,2,3,4)
     * @return array
     * @author wsn
     */
    public static function getTeacherReplayByCommentIdArr($commentIdArr){
        if(!is_array($commentIdArr)) return interface_func::setMsg(30001,'参数不规范');
        $ret = utility_services::call("comment/course/getTeacherReplayByCommentIdArr",$commentIdArr);
        return $ret;
    }
    public static function getCommentNum($data, $user)
    {
        if (empty($data["course_id"])) {
            return false;
        }
        $params            = new stdclass;
        $params->course_id = $data["course_id"];
        $params->user_id   = $user;
        $ret               = utility_services::call("/comment/course/getcommentnum", $params);
        if (!empty($ret->data)) {
            return $ret->data[0]->total;
        } else {
            return false;
        }
    }

    public static function getComments($data, $user)
    {
        if (empty($data["course_id"]) || !isset($data["start"]) || empty($data["num"])) {
            return false;
        }
        $params            = new stdclass;
        $params->course_id = $data["course_id"];
        $params->start     = $data["start"];
        $params->num       = $data["num"];
        $params->user_id   = $user;
        $ret               = utility_services::call("/comment/course/getcomments", $params);
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }

    public static function getCommentsDesc($data, $user)
    {
        if (empty($data["course_id"]) || empty($data["num"])) {
            return false;
        }
        $params            = new stdclass;
        $params->course_id = $data["course_id"];
        $params->num       = $data["num"];
        $params->user_id   = $user;
        if (isset($data["max"])) {
            $params->max = $data["max"];
        }
        $ret = utility_services::call("/comment/course/getcommentsdesc", $params);
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }

    public static function getDetail($data, $user)
    {
        if (empty($data["course_id"])) {
            return false;
        }
        $params            = new stdclass;
        $params->course_id = $data["course_id"];
        $params->user_id   = $data["user_id"];
        if (isset($data["plan_id"])) {
            $params->plan_id = $data["plan_id"];
        }
        $ret = utility_services::call("/comment/course/getdetail", $params);
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }

    public static function getTotal($data)
    {
        if (empty($data["course_id"])) {
            return false;
        }
        $params            = new stdclass;
        $params->course_id = $data["course_id"];
        $ret               = utility_services::call("/comment/course/gettotal", $params);
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }
	public static function getCommentTotal($data){
		if (empty($data["course_id"])) {
            return false;
        }
        $params            = new stdclass;
        $params->course_id = $data["course_id"];
        $ret               = utility_services::call("/comment/course/GetCommentTotal", $params);
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
	}
    public static function addComment($data, $user)
    {
        if (empty($data["course_id"]) || !isset($data["comment"]) || "" === $data["comment"]) {
            return false;
        }
        $params               = new stdclass;
        $params->course_id    = $data["course_id"];
        $params->user_id      = $user;
        $params->comment      = strip_tags($data["comment"]);
        $params->user_teacher = $data["user_teacher"];
        $params->plan_id      = $data["plan_id"];
        $params->user_owner   = $data["user_owner"];
        
        $ret                  = utility_services::call("/comment/course/addcomment", $params);
        if (!empty($ret->result) && $ret->result->code == 0) {
            return $ret->result->data;
        }

        return false;
    }

    public static function addDetail($data, $user)
    {
        //	if(empty($data["course_id"]) || !isset($data["student_score"]) || !isset($data["desc_score"]) || !isset($data["explain_score"]) || !isset($data["service_score"])){
        if (empty($data["course_id"]) || !isset($data["student_score"]) || !isset($data["desc_score"]) || !isset($data["explain_score"])) {
            return false;
        }
        $params                = new stdclass;
        $params->user_id       = $user;
        $params->course_id     = $data["course_id"];
        $params->student_score = $data["student_score"];
        $params->desc_score    = $data["desc_score"];
        $params->explain_score = $data["explain_score"];
        $params->user_teacher  = $data["user_teacher"];
        $params->plan_id       = $data["plan_id"];
        $params->user_owner    = $data["user_owner"];
        //	$params->service_score = $data["service_score"];
        $ret = utility_services::call("/comment/course/adddetail", $params);
        if (!empty($ret->result) && $ret->result->code == 0) {
            $ret = utility_services::call("/comment/course/addtotal", $params);

            return $ret;
        }

        return true;
    }

    public static function getCommentList($params)
    {
        return utility_services::call('/comment/course/getCommentList', $params);
    }

    public static function checkIsComment($courseId, $uid, $planId)
    {
        $data = [
            'courseId' => $courseId,
            'uid'      => $uid,
            'planId'   => $planId,
        ];
        $res  = utility_services::call('/comment/course/CheckIsComment', $data);

        return $res->code ? false : true;
    }
	
	//评分检测新接口
	public static function checkIsAddScore($courseId, $uid, $planId){
        $courseId = isset($courseId) ? intval($courseId) : 0;
        $uid = isset($uid) ? intval($uid) : 0;
        $planId = isset($planId) ? intval($planId) : 0;
		 $data = [
            'courseId' => $courseId,
            'uid'      => $uid,
            'planId'   => $planId,
        ];
        $res  = utility_services::call('/comment/course/checkIsAddScore', $data);

        return $res->code ? false : true;
	}

    /**
     * @param $courseId
     * @param $uid
     * @return {  {'planid':123,'sectionName':'xiaowo','type':0/1 } } 0 评论过  1 还没有评论
     */
    public static function checkIsAddScoreAndCourseId($courseId, $uid, $classId){
        if( empty($courseId) || empty($uid) || empty($classId)) return interface_func::setMsg(1000);
        $data = [
            'courseId' => (int)$courseId,
            'uid'      => (int)$uid,
        ];
        $res_1  = utility_services::call('/comment/course/checkIsAddScoreAndCourseId', $data);
        $arr_1 = array();
        if($res_1->code ==0){
            foreach($res_1->result as $item){
                $arr_1[] = $item->fk_plan;
            }
        }
        $arr_2 = array();
        $planParams = [
            'q' => ['course_id'=>$courseId,'status'=>'1,2,3','class_id'=>$classId,'deleted'=>0],
            'f' => ['plan_id','section_id','section_name','status'],
			'ob'=> ['section_order_no'=>'asc'],
            'pl'=>500
        ];
        $plandata = seek_api::seekPlan($planParams);
        if($plandata->data){
            foreach($plandata->data as $value){
                if(in_array($value->plan_id,$arr_1) || $value->status==1 ){
                    $arr_2[$value->plan_id] = ['planId'=>$value->plan_id,'sectoinName'=>$value->section_name,'type'=>0];
                }else{
                    $arr_2[$value->plan_id] = ['planId'=>$value->plan_id,'sectoinName'=>$value->section_name,'type'=>1];
                }

            }
        }
      return $arr_2;
    }

	/*
		评分添加接口
		参数 course_id,score,plan_id,userid
		返回 array(
			'code'=>-1, //code等于1为成功
			'msg'=>'',  //提示
			'data'=>''  //返回数据
		)
	*/
	public static function addScore($data,$user){
		$returnData = array(
			'code'=>-1,
			'msg'=>'参数有错',
			'data'=>'',
		);
        if (empty($data['comment']) || empty($data['user_owner']) || empty($data['user_teacher']) || empty($data["course_id"]) || !isset($data["score"]) || empty($data['plan_id']) || empty($user) ) {
            return $returnData;
        }
		//检测报名
		$resUser = course_api::checkIsRegistration($user, $data["course_id"]);
		if(empty($resUser)){
			$returnData['code'] = -2;
			$returnData['msg'] = '没有报名';
			 return $returnData;
		}
		if (comment_api::checkIsAddScore((int)($data['course_id']), $user, $data['plan_id'])){
			$returnData['code'] = -3;
			$returnData['msg'] = '已经评论过';
			 return $returnData;
		}
        $params                = new stdclass;
        $params->fk_user       = $user;
        $params->fk_course     = $data["course_id"];
        $params->score         = $data["score"];
        $params->teacher_id    = $data["user_teacher"];
        $params->fk_plan       = $data["plan_id"];
        $params->fk_user_owner        = $data["user_owner"];
		$params->comment       = addslashes(strip_tags($data["comment"]));
		$create_time           = date('Y-m-d H:i:s',time());
		$params->create_time   = "$create_time";
		$params->last_updated  = "$create_time";
		$params->status        = 1;
        $ret = utility_services::call("/comment/course/addScore", $params);
        if (!empty($ret->result) && $ret->result->code == 0) {
            utility_services::call("/comment/course/addCommentScore", $params);
            utility_services::call("/comment/course/addTeacherScore", $params);
            $experience = comment_api::addUserExperience($user);//添加经验
			$experience['time'] = $create_time;
			$experience['section']='';
			$returnData['code'] = 1;
			$returnData['msg'] = '评论成功';
			$returnData['data'] = $experience;
			return $returnData;
        }
		$returnData['code'] = -4;
		$returnData['msg'] = '添加错误，请重试';
        return $returnData;
	}
	//获取planid详情
	public static function getplanidInfo($planId){
 		if(empty($planId)) return false;
 		$planId = (int)$planId;
		$params                = new stdclass;
		$params->planId = $planId;
		$ret = utility_services::call("/course/plan/GetplanidInfo", $params);
		if($ret) return json_encode($ret);
		return json_encode(['msg'=>'faild','code'=>0]);
 	}
	
	//教师 平均分
	public static function teacherAverage($teacher_id){
		$params                = new stdclass;
		$params->fk_user_teacher = $teacher_id;
		$ret = utility_services::call("/comment/course/teacherAverage", $params);
        if($ret->code == 1){
            return $ret->result;
        }
        return false;
	}
	
	//课程平均分
	public static function courseAverage($course_id){
		if(empty($course_id)) return false;
		$params                = new stdclass;
		$params->fk_course = $course_id;
		$ret = utility_services::call("/comment/course/courseAverage", $params);
        if($ret->code == 1){
            return $ret->result;
        }
        return false;
	}
	
	

    public static function checkIsCommentByPlanId($planIdArr, $uid)
    {
        $data = [
            'planIdArr' => $planIdArr,
            'uid'       => $uid
        ];


        $res = utility_services::call('/comment/course/checkIsCommentByPlanId', $data);

        return $res;

    }

    /**
     * @desc add user experience
     *
     * @param $uid
     * @param string $ruleName
     * @return array|bool
     */
    public static function addUserExperience($uid, $ruleName = 'COMMENT')
    {
        if (!(int)($uid)) return false;

        $res = user_api::changeUserLevelAndScore($uid, $ruleName);

        if ($res->code == 0) {
            return [
                'upType'       => $res->data->up_type, // 1:Small upgrade, 2:Big upgrade
                'userId'       => $res->data->fk_user, // current login user id
                'userLevel'    => $res->data->fk_level, // user current level
                'title'        => $res->data->title, // user current level title
                'currentScore' => $res->data->score, // user current score
                'addScore'     => $res->data->add_score // user added score when comment
            ];
        }

        return false;
    }

    public static function getCommentListByCourseId($courseId, $teacherId = 0, $page = 1, $length = 20)
    {
        if (!$courseId) return [];

        $data = [
            'courseId'  => $courseId,
            'teacherId' => $teacherId ? $teacherId : 0,
            'page'      => $page ? $page: 1,
            'length'    => $length ? $length : 20
        ];

        $commentDetail = utility_services::call('/comment/course/ListCommentsByCourseId', $data);
        if ($commentDetail->code) return [];

        $result = $userIdArr = $planIdArr = $planInfo = $userInfo = $userLevelInfo = [];
        foreach ($commentDetail->result->data as $v) {
            $userIdArr[$v->fk_user] = $v->fk_user;
            $planIdArr[$v->fk_plan] = $v->fk_plan;
            $result[]               = [
                'userId'       => $v->fk_user,
                'planId'       => $v->fk_plan,
                'comment'      => !empty($v->comment) ? $v->comment : '',
				'commentId'      => !empty($v->pk_comment) ? $v->pk_comment : 0,
                'time'         => $v->last_updated,
                'studentScore' => $v->student_score,
            ];
        }


        $planData = [
            'q' => [
                'course_id' => $courseId,
                'plan_id'   => implode(',', array_values($planIdArr)),
                'status'    => '1,2,3'
            ],
            'f' => [
                'plan_id',
                'course_name',
                'class_name',
                'section_name'
            ]
        ];

        $planData = seek_api::seekPlan($planData);
        if (!empty($planData)) {
            foreach ($planData->data as $plan) {
                $planInfo[$plan->plan_id] = [
                    'courseName'  => $plan->course_name,
                    'className'   => $plan->class_name,
                    'sectionName' => $plan->section_name
                ];
            }
        }

        $userData = [
            'userIdArr' => $userIdArr,
            'scope'     => [
                'profile' => true
            ]
        ];

        $userData = utility_services::call('/common/list/GetUsersInfo', $userData);
        if (!empty($userData->result)) {
            foreach ($userData->result as $user) {
                $userInfo[$user->pk_user] = [
                    'name'  => !empty($user->real_name)
                        ? $user->real_name
                        : (!empty($user->name) ? $user->name : SLanguage::tr('未设置', 'message')),
                    'thumb' => !empty($user->thumb_med)
                        ? interface_func::imgUrl($user->thumb_med)
                        : ''
                ];
            }
        }

        $userLevel = utility_services::call('/user/score/listByUidArr', ['userIdArr' => $userIdArr]);
        foreach ($userIdArr as $userId) {
            $userLevelInfo[$userId] = [
                'level' => 1,
                'title' => '书生',
                'score' => 0
            ];
        }

        if (!empty($userLevel->result)) {
            foreach ($userLevel->result as $val) {
                $userLevelInfo[$val->fk_user] = [
                    'level' => $val->fk_level,
                    'title' => $val->title,
                    'score' => $val->score
                ];
            }
        }
        $res['totalPage'] = $commentDetail->result->totalPage;
        $res['totalSize'] = $commentDetail->result->totalSize;
        foreach ($result as $r) {
            $res['data'][] = [
                'userId'       => $r['userId'],
                'planId'       => $r['planId'],
                'comment'      => $r['comment'],
				'commentId'      => $r['commentId'],
                'time'         => $r['time'],
                'ymd'          => (date('Y') != date('Y', strtotime($r['time']))) ? date('Y-m-d', strtotime($r['time'])) : date('m-d', strtotime($r['time'])),
                'his'          => date('H:i', strtotime($r['time'])),
                'studentScore' => $r['studentScore'],
                'className'    => !empty($planInfo[$r['planId']]['className']) ? $planInfo[$r['planId']]['className'] : '',
                'courseName'   => !empty($planInfo[$r['planId']]['courseName']) ? $planInfo[$r['planId']]['courseName'] : '',
                'sectionName'  => !empty($planInfo[$r['planId']]['sectionName']) ? $planInfo[$r['planId']]['sectionName'] : '',
                'userName'     => !empty($userInfo[$r['userId']]['name']) ? $userInfo[$r['userId']]['name'] : '',
                'userThumb'    => !empty($userInfo[$r['userId']]['thumb']) ? $userInfo[$r['userId']]['thumb'] : '',
                'userLevel'    => $userLevelInfo[$r['userId']]['level'],
                'userTitle'    => $userLevelInfo[$r['userId']]['title'],
                'userScore'    => $userLevelInfo[$r['userId']]['score']
            ];
        }

        return $res;
    }

    //新接口 课程打分列表
    public static function getCourseCommentList($courseId, $teacherId = 0, $page = 1, $length = 20)
    {
        if (!$courseId) return [];

        $data = [
            'courseId'  => $courseId,
            'teacherId' => $teacherId ? $teacherId : 0,
            'page'      => $page ? $page : 1,
            'length'    => $length ? $length : 20
        ];

        $commentDetail = utility_services::call('/comment/course/getCourseCommentList', $data);
        if ($commentDetail->code) return [];

        $result = $userIdArr = $planIdArr = $planInfo = $userInfo = $userLevelInfo = [];
        foreach ($commentDetail->result->data as $v) {
            $userIdArr[$v->fk_user] = $v->fk_user;
            $planIdArr[$v->fk_plan] = $v->fk_plan;
            $result[]               = [
                'userId'       => $v->fk_user,
                'planId'       => $v->fk_plan,
                'comment'      => !empty($v->comment) ? $v->comment : '',
                'commentId'      => !empty($v->pk_comment) ? $v->pk_comment : 0,
                'time'         => $v->last_updated,
                'studentScore' => $v->score,
            ];
        }


        $planData = [
            'q' => [
                'course_id' => $courseId,
                'plan_id'   => implode(',', array_values($planIdArr)),
                'status'    => '1,2,3'
            ],
            'f' => [
                'plan_id',
                'course_name',
                'class_name',
                'section_name'
            ]
        ];

        $planData = seek_api::seekPlan($planData);
        if (!empty($planData)) {
            foreach ($planData->data as $plan) {
                $planInfo[$plan->plan_id] = [
                    'courseName'  => $plan->course_name,
                    'className'   => $plan->class_name,
                    'sectionName' => $plan->section_name
                ];
            }
        }

        $userData = [
            'userIdArr' => $userIdArr,
            'scope'     => [
                'profile' => true
            ]
        ];

        $userData = utility_services::call('/common/list/GetUsersInfo', $userData);
        if (!empty($userData->result)) {
            foreach ($userData->result as $user) {
                $userInfo[$user->pk_user] = [
                    'name'  => !empty($user->real_name)
                        ? $user->real_name
                        : (!empty($user->name) ? $user->name : SLanguage::tr('未设置', 'message')),
                    'thumb' => !empty($user->thumb_med)
                        ? interface_func::imgUrl($user->thumb_med)
                        : ''
                ];
            }
        }

        $userLevel = utility_services::call('/user/score/listByUidArr', ['userIdArr' => $userIdArr]);
        foreach ($userIdArr as $userId) {
            $userLevelInfo[$userId] = [
                'level' => 1,
                'title' => '书生',
                'score' => 0
            ];
        }

        if (!empty($userLevel->result)) {
            foreach ($userLevel->result as $val) {
                $userLevelInfo[$val->fk_user] = [
                    'level' => $val->fk_level,
                    'title' => $val->title,
                    'score' => $val->score
                ];
            }
        }
        $res['totalPage'] = $commentDetail->result->totalPage;
        $res['totalSize'] = $commentDetail->result->totalSize;
        foreach ($result as $r) {
            $res['data'][] = [
                'userId'       => $r['userId'],
                'planId'       => $r['planId'],
                'comment'      => $r['comment'],
                'commentId'      => $r['commentId'],
                'time'         => $r['time'],
                'ymd'          => (date('Y') != date('Y', strtotime($r['time']))) ? date('Y-m-d', strtotime($r['time'])) : date('m-d', strtotime($r['time'])),
                'his'          => date('H:i', strtotime($r['time'])),
                'studentScore' => $r['studentScore'],
                'className'    => !empty($planInfo[$r['planId']]['className']) ? $planInfo[$r['planId']]['className'] : '',
                'courseName'   => !empty($planInfo[$r['planId']]['courseName']) ? $planInfo[$r['planId']]['courseName'] : '',
                'sectionName'  => !empty($planInfo[$r['planId']]['sectionName']) ? $planInfo[$r['planId']]['sectionName'] : '',
                'userName'     => !empty($userInfo[$r['userId']]['name']) ? $userInfo[$r['userId']]['name'] : '',
                'userThumb'    => !empty($userInfo[$r['userId']]['thumb']) ? $userInfo[$r['userId']]['thumb'] : '',
                'userLevel'    => $userLevelInfo[$r['userId']]['level'],
                'userTitle'    => $userLevelInfo[$r['userId']]['title'],
                'userScore'    => $userLevelInfo[$r['userId']]['score']
            ];
        }

        return $res;
    }

    //新获取个人打分详情
    public static function getSingleCommentScore($courseId, $uid, $planId){
        if(empty($courseId) || empty($uid) || empty($planId)) return false;
        $data = [
            'fk_course' => $courseId,
            'fk_plan'   => $planId,
            'fk_user'   =>$uid,
        ];
        $data = new stdClass();
        $data->fk_course = $courseId;
        $data->fk_plan  = $planId;
        $data->fk_user  = $uid;
        $data->status   = 1;
        $comment = utility_services::call('/comment/course/getSingleCommentScore', $data);
        return json_encode($comment);
    }

	
    public static function getCommentAndScore($courseId, $uid, $planId)
    {
        $data = [
            'course_id' => $courseId,
            'plan_id'   => $planId
        ];

        $param = [
            'course_id' => $courseId,
            'plan_id'   => $planId,
            'user_id'   => $uid
        ];

        $commentInfo = comment_api::getComment($data, $uid);

        $commentStr = '';
        if (isset($commentInfo[0]->comment) && strlen($commentInfo[0]->comment)) {
            $commentStr = $commentInfo[0]->comment;
        }

        $scoreInfo = comment_api::getDetail($param, $uid);
        $studentScore = !empty($scoreInfo[0]->student_score) ? $scoreInfo[0]->student_score : 0;
        $descScore = !empty($scoreInfo[0]->desc_score) ? $scoreInfo[0]->desc_score : 0;
        $explainScore = !empty($scoreInfo[0]->explain_score) ? $scoreInfo[0]->explain_score : 0;
        $avgScore = !empty($scoreInfo[0]->avg_score) ? $scoreInfo[0]->avg_score : 0;

        return [
            'commentStr'   => $commentStr,
            'studentScore' => $studentScore,
            'descScore'    => $descScore,
            'explainScore' => $explainScore,
            'avgScore'     => $avgScore
        ];
    }

    public static function getScoreCourseTotalList($courseIdArr, $page = 1, $length = -1)
    {
        if (count($courseIdArr) < 1) return false;

        $url = "/comment/course/GetScoreCourseTotalList";
        $res = interface_func::requestApi($url, compact('courseIdArr', 'page', 'length'));

        if (!empty($res['code'])) return [];

        $list['totalPage'] = $res['result']['totalPage'];
        $list['totalSize'] = $res['result']['totalSize'];
        $list['data'] = [];
        if (!empty($res['result']['items'])) {
            foreach ($res['result']['items'] as $v) {
                $student_score = empty($v['total_user'])?0:(int)ceil($v['student_score'] / $v['total_user']);
                $desc_score    = empty($v['total_user'])?0:(int)ceil($v['desc_score'] / $v['total_user']);
                $explain_score = empty($v['total_user'])?0:(int)ceil($v['explain_score'] / $v['total_user']);
                $avg_score     = empty($v['total_user'])?0:(int)ceil($v['score'] / $v['total_user']);

                $list['data'][$v['fk_course']] = [
                    'student_score' => $student_score > 5 ? 5 : $student_score,
                    'desc_score'    => $desc_score > 5 ? 5 : $desc_score,
                    'explain_score' => $explain_score > 5 ? 5 : $explain_score,
                    'avg_score'     => $avg_score > 5 ? 5 : $avg_score
                ];
            }
        }

        return $list;
    }

    public static function getOneCourseScoreInfo($cid)
    {
        if (!$cid) return [];
        $res = comment_api::getTotal(array('course_id' => $cid));
        if (!empty($res)) {
            $student_score =  empty($res[0]->total_user)?0:(int)ceil($res[0]->student_score / $res[0]->total_user);
            $desc_score    =  empty($res[0]->total_user)?0:(int)ceil($res[0]->desc_score / $res[0]->total_user);
            $explain_score =  empty($res[0]->total_user)?0:(int)ceil($res[0]->explain_score / $res[0]->total_user);
			$avg_score     = empty($res[0]->total_user)?0:sprintf('%.1f',$res[0]->score/$res[0]->total_user);

            return [
                'student_score' => $student_score > 5 ? 5 : $student_score,
                'desc_score'    => $desc_score > 5 ? 5 : $desc_score,
                'explain_score' => $explain_score > 5 ? 5 : $explain_score,
                'avg_score'     => $avg_score > 5 ? 5 : $avg_score
            ];
        }

        return [];
    }

    /*
     * 评价管理 评论列表 wsn
     */
    public static function CommentList($courseId, $score,$time,$teacherId = 0,$page = 1, $length,$userId='')
    {
        if (!$courseId) return [];
        $h = date('H',time());
        $i = date('i',time());
        $s = date('s',time());
        if($time=='week'){
            $time=mktime($h,$i,$s,date("m"),date("d")-7,date("Y")); //一周之前
        }elseif($time=='month'){
            $time=mktime($h,$i,$s,date("m")-1,date("d"),date("Y"));//一月之前
        }else{
            $time = '';
        }
        $data = [
            'courseId'  => (int)$courseId,
            'teacherId' => $teacherId ? $teacherId : 0,
            'page'      => $page ? (int)$page : 1,
            'length'    => $length ? (int)$length : 20,
            'score'     => intval($score) ? intval($score) : '',
            'time'     => $time ? trim($time) : '',
            'userId'   => $userId ?  (int)$userId : '',
        ];
        $commentDetail = utility_services::call('/comment/course/commentList', $data);
        if ($commentDetail->code) return [];

        $result = $userIdArr = $planIdArr = $planInfo = $userInfo = $userLevelInfo = [];
        foreach ($commentDetail->result->data as $v) {
            $userIdArr[$v->fk_user] = $v->fk_user;
            $planIdArr[$v->fk_plan] = $v->fk_plan;
            $result[]               = [
                'userId'       => $v->fk_user,
                'planId'       => $v->fk_plan,
                'comment'      => !empty($v->comment) ? $v->comment : '',
                'commentId'      => !empty($v->pk_comment) ? $v->pk_comment : 0,
                'time'         => $v->create_time,
                'studentScore' => $v->score,
            ];
        }


        $planData = [
            'q' => [
                'course_id' => $courseId,
                'plan_id'   => implode(',', array_values($planIdArr)),
                'status'    => '1,2,3'
            ],
            'f' => [
                'plan_id',
                'course_name',
                'class_name',
                'section_name'
            ]
        ];

        $planData = seek_api::seekPlan($planData);
        if (!empty($planData)) {
            foreach ($planData->data as $plan) {
                $planInfo[$plan->plan_id] = [
                    'courseName'  => $plan->course_name,
                    'className'   => $plan->class_name,
                    'sectionName' => $plan->section_name
                ];
            }
        }

        $userData = [
            'userIdArr' => $userIdArr,
            'scope'     => [
                'profile' => true
            ]
        ];

        $userData = utility_services::call('/common/list/GetUsersInfo', $userData);
        if (!empty($userData->result)) {
            foreach ($userData->result as $user) {
                $userInfo[$user->pk_user] = [
                    'name'  => !empty($user->real_name)
                        ? $user->real_name
                        : (!empty($user->name) ? $user->name : SLanguage::tr('未设置', 'message')),
                    'thumb' => !empty($user->thumb_med)
                        ? interface_func::imgUrl($user->thumb_med)
                        : ''
                ];
            }
        }

        $userLevel = utility_services::call('/user/score/listByUidArr', ['userIdArr' => $userIdArr]);
        foreach ($userIdArr as $userId) {
            $userLevelInfo[$userId] = [
                'level' => 1,
                'title' => '书生',
                'score' => 0
            ];
        }

        if (!empty($userLevel->result)) {
            foreach ($userLevel->result as $val) {
                $userLevelInfo[$val->fk_user] = [
                    'level' => $val->fk_level,
                    'title' => $val->title,
                    'score' => $val->score
                ];
            }
        }
        $res['totalPage'] = $commentDetail->result->totalPage;
        $res['totalSize'] = $commentDetail->result->totalSize;
        foreach ($result as $r) {
            $res['data'][] = [
                'userId'       => $r['userId'],
                'planId'       => $r['planId'],
                'comment'      => $r['comment'],
                'commentId'      => $r['commentId'],
                'time'         => $r['time'],
                'ymd'          => (date('Y') != date('Y', strtotime($r['time']))) ? date('Y-m-d', strtotime($r['time'])) : date('m-d', strtotime($r['time'])),
                'his'          => date('H:i', strtotime($r['time'])),
                'studentScore' => $r['studentScore'],
                'className'    => !empty($planInfo[$r['planId']]['className']) ? $planInfo[$r['planId']]['className'] : '',
                'courseName'   => !empty($planInfo[$r['planId']]['courseName']) ? $planInfo[$r['planId']]['courseName'] : '',
                'sectionName'  => !empty($planInfo[$r['planId']]['sectionName']) ? $planInfo[$r['planId']]['sectionName'] : '',
                'userName'     => !empty($userInfo[$r['userId']]['name']) ? $userInfo[$r['userId']]['name'] : '',
                'userThumb'    => !empty($userInfo[$r['userId']]['thumb']) ? $userInfo[$r['userId']]['thumb'] : '',
                'userLevel'    => $userLevelInfo[$r['userId']]['level'],
                'userTitle'    => $userLevelInfo[$r['userId']]['title'],
                'userScore'    => $userLevelInfo[$r['userId']]['score']
            ];
        }

        return $res;
    }

    /*
     * 插入老师的评论 wns
     */
    public static function InsertCommentReplay($params){
        $params['fk_comment'] = isset($params['fk_comment']) && intval($params['fk_comment']) ? intval($params['fk_comment']) : '';
        $params['contents'] = isset($params['contents']) ? htmlspecialchars(addslashes($params['contents'])) : '';
        $params['manage_name'] = isset($params['manage_name']) ? htmlspecialchars(addslashes($params['manage_name'])) : '';
        $params['fk_user'] = isset($params['fk_user']) ? intval($params['fk_user']) : '';
        if(empty($params['fk_comment']))  return interface_func::setMsg(1,'用户评论id不能为空');
        if(empty($params['contents']))  return interface_func::setMsg(1,'老师评论内容不能为空');
        if(empty($params['manage_name'])) return  interface_func::setMsg(1,'管理员姓名不能为空');
        if(empty($params['fk_user'])) return  interface_func::setMsg(1,'管理员id不能为空');
        $ret =   utility_services::call('/comment/course/InsertCommentReplay', $params);
        return json_encode($ret,JSON_UNESCAPED_UNICODE);
    }

    /*
     * 老师删除回复
     */
    public static function DeleteCommentReplay($params){
        $params['fk_user'] = isset($params['fk_user']) && intval($params['fk_user']) ? intval($params['fk_user']) : '';
        $params['pk_replay'] = isset($params['pk_replay']) && intval($params['pk_replay']) ? intval($params['pk_replay']) : '';
        $ret =   utility_services::call('/comment/course/DeleteCommentReplay', $params);
        return  $ret;
    }

    /**
     * 找到老师的回复
     */

    public static function showReplay($fk_comment,$fk_user){
        $fk_comment = isset($fk_comment) && intval($fk_comment) ? intval($fk_comment) : '';
        $fk_user = isset($fk_user) && intval($fk_user) ? intval($fk_user) : '';
        if(empty($fk_comment)) return interface_func::setMsg(1,'评论id不能为空');
        //if(empty($fk_user))  return interface_func::setMsg(1,'管理员id不能为空');  course/info/show 页面用到
        $params = [
          'fk_comment'=>$fk_comment,
          'fk_user' => $fk_user
        ];
        if(empty($fk_user)) $params= array('fk_comment'=>$fk_comment);
        $ret  = utility_services::call('/comment/course/showReplay',$params);
        if(isset($ret->pk_replay)) return interface_func::setData($ret);
        return interface_func::setMsg(1,'没有匹配数据');
    }
}

