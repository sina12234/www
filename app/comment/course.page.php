<?php

class comment_course extends STpl{
	var $user;
	public function __construct($inPath){
		$this->user = user_api::loginedUser();
	}
	public function pageGetComment($inPath){
		if(empty($this->user)){
			return false;
		}
		if(!empty($_POST)){
			return comment_api::getComment($_POST, $this->user["uid"]);
		}
		return false;
	}
	public function pageGetCommentNum($inPath){
		if(empty($this->user)){
			return false;
		}
		if(!empty($_POST)){
			return comment_api::getCommentNum($_POST, $this->user["uid"]);
		}
		return false;
	}
	public function pageGetComments($inPath){
		$uid = 0;
		if(!empty($this->user)){
			$uid = $this->user["uid"];
		}
		if(!empty($_POST)){
			return comment_api::getComments($_POST, $uid);
		}
		return false;
	}
	public function pageGetCommentsDesc($inPath){
		$uid = 0;
		if(!empty($this->user)){
			$uid = $this->user["uid"];
		}
		if(!empty($_POST)){
			return comment_api::getCommentsDesc($_POST, $uid);
		}
		return false;
	}
	public function pageGetDetail($inPath){
		if(empty($this->user)){
			return false;
		}
		if(!empty($_POST)){
			return comment_api::getDetail($_POST, $this->user["uid"]);
		}
		return false;
	}
	public function pageGetTotal($inPath){
		if(empty($this->user)){
			return false;
		}
		if(!empty($_POST)){
			return comment_api::getTotal($_POST);
		}
		return false;
	}
	/*public function pageAddComment($inPath){
		if(empty($this->user)){
			return false;
		}
		if(!empty($_POST)){
			return comment_api::addComment($_POST, $this->user["uid"]);
		}
		return false;
	}*/
	public function pageAddDetail($inPath){
		if(empty($this->user)){
			return false;
		}
		if(!empty($_POST)){
			return comment_api::addDetail($_POST, $this->user["uid"]);
		}
		return false;
	}
	/*public function pageAddCommentScore($inPath)
	{
		if (empty($this->user)) return interface_func::setMsg(1021);
		if (empty($_POST)) return interface_func::setMsg(1000);

		if (empty($this->user['uid'])) return interface_func::setMsg(1021);
		//检测是否评论
		if (empty($_POST['course_id']) || !(int)($_POST['course_id']))
			return interface_func::setMsg(1000);

		if (comment_api::checkIsComment((int)($_POST['course_id']), $this->user['uid'], $_POST['plan_id'])) {
			return interface_func::setMsg(2043);
		}

		comment_api::addComment($_POST, $this->user["uid"]);
		comment_api::addDetail($_POST, $this->user["uid"]);
		$res = comment_api::addUserExperience($this->user["uid"]);

		if ($res) return interface_func::setData($res);

		return interface_func::setMsg(1);
	}*/
	
	//评分新接口
	public function pageAddScore(){
		if (empty($this->user)) return interface_func::setMsg(1021);
		if (empty($_POST)) return interface_func::setMsg(1000);
		if (empty($this->user['uid'])) return interface_func::setMsg(1021);
		//检测是否评论
		if (empty($_POST['course_id']) || !(int)($_POST['course_id'])) return interface_func::setMsg(1000);
		if (comment_api::checkIsAddScore((int)($_POST['course_id']), $this->user['uid'], $_POST['plan_id'])) return interface_func::setMsg(2043);
		//没评论 则添加
		if($_POST['score']>=5){
			$_POST['score'] = 5;
		}
		$ret = comment_api::getplanidInfo($_POST['plan_id']);
		if(!$ret) return json_encode(['msg'=>'非法添加！','code'=>0]);
		$ret = json_decode($ret,true);
		$_POST['user_owner'] = $ret['fk_user'];
		$_POST['user_teacher'] = $ret['fk_user_plan'];
		$_POST['course_id'] = $ret['fk_course'];
		$res = comment_api::addScore($_POST, $this->user["uid"]);
		if($res['code']==1){
			$data = $res['data'];
			$data['data'] = $_POST;
			return json_encode(['msg'=>'success','code'=>1,'result'=>$data],JSON_UNESCAPED_UNICODE);
		}
		return json_encode(['msg'=>$res['msg'],'code'=>0],JSON_UNESCAPED_UNICODE);
		
	}
//检测评论新接口
	public function pagecheckIsAddScore(){
		if (empty($_POST['course_id']) || !(int)($_POST['course_id'])) return interface_func::setMsg(1000);
		if (comment_api::checkIsAddScore((int)($_POST['course_id']), $this->user['uid'], $_POST['plan_id'])){
			$datainfo = comment_api::getSingleCommentScore((int)($_POST['course_id']), $this->user['uid'], $_POST['plan_id']);
			$secname = '';
			$cour = json_decode($datainfo,true);
			$cour['section'] = $secname;
			return json_encode($cour,JSON_UNESCAPED_UNICODE);
		}
		return json_encode(array('result'=>array('code'=>'101','msg'=>'没有评论','data'=>'')));
	}

	//删除评论
	public function pageDelComment()
	{
		$user = user_api::loginedUser();
		if (empty($user['uid'])) return interface_func::setMsg(1021);
		$_POST['userId'] = $user['uid'];
		//通过 courseid plain 获取teacherid
		$ret = [
			'userId' => intval($_POST['userId']) ? intval($_POST['userId']) : '' ,
			'courseId'=>intval(isset($_POST['courseId'])) ? intval($_POST['courseId']) : '',
			'planId'=>intval(isset($_POST['planId'])) ? intval($_POST['planId']) : ''
		];
		$teacherId = utility_services::call('/comment/course/getTeacherId',$ret);
		if($teacherId->code==1) return  interface_func::setMsg(1);
		$teacherId = $teacherId->data->teacher_id;
		$_POST['teacherId'] = $teacherId;
		$r = interface_func::isValidId(['userId', 'planId', 'courseId','teacherId'], $_POST);
		if (!empty($r['code'])) return interface_func::setMsg($r['code']);

		if ($user['uid'] != $r['userId']) {
			return interface_func::setMsg(1024);
		}

		$params = [
				'userId' => $r['userId'],
				'planId' => $r['planId'],
				'courseId' => $r['courseId'],
				'teacherId' => $r['teacherId']
		];

		$res = utility_services::call('/comment/course/DeleteComment', $params);

		if (!empty($res->code)) return interface_func::setMsg(1);

		return interface_func::setMsg(0);
	}

	//教师 平均分
	public function pageTeacherAverage(){
		//$teacher_id $org_id
		
		if( empty($_POST['teacher_id']) ) return false;
		$fk_user_teacher = $_POST['teacher_id'];
		$ret = comment_api::teacherAverage($fk_user_teacher);
		$score = 0;
		$total_user = 0;
		if($ret->items){
			foreach($ret->items as $item){
				$score += $item->score;
				$total_user +=$item->total_user;
			}
		}
		$ret->total_user = $total_user;
		$ret->score = $score;
		if($ret){
			if(empty($ret->total_user)){
				$score = 0;
			}else{
				$score = round($ret->score/$ret->total_user,1);
			}
			return json_encode(['score'=>$score]);
		}
		return json_encode(array('msg'=>'faild','code'=>0));
	}
	
	//课程 平均分
	public function pageCourseAverage(){
		if(empty($_POST['fk_course'])) return false;
		$ret = comment_api::courseAverage($_POST['fk_course']);
		if($ret){
			if(empty($ret->total_user)){
				$score = 0;
			}else{
				$score = round($ret->score/$ret->total_user,1);
			}
			return json_encode(['score'=>$score]);
		}
		return json_encode(array('msg'=>'faild','code'=>0));
	}


	//新个人评论接口
	public function pageSingleComment(){
		if (!isset($_POST['courseId']) || !(int)($_POST['courseId']))
			return interface_func::setMsg(1000);
		if (!isset($_POST['planId']) || !(int)($_POST['planId']))
			return interface_func::setMsg(1000);
		if (empty($this->user['uid'])) return interface_func::setMsg(1021);
		if(comment_api::checkIsComment((int)($_POST['course_id']), $this->user['uid'], $_POST['plan_id'])){
			$commentScore = comment_api::getCommentScore((int)($_POST['courseId']), $this->user['uid'], (int)($_POST['planId']));
			if($commentScore) return interface_func::setData($commentScore);
		}
		return interface_func::setMsg(2043);
	}


    public function pageCheckUserIsComment()
    {
        if (!isset($_POST['courseId']) || !(int)($_POST['courseId']))
            return interface_func::setMsg(1000);

        if (!isset($_POST['planId']) || !(int)($_POST['planId']))
            return interface_func::setMsg(1000);

        if (empty($this->user['uid'])) return interface_func::setMsg(1021);

        if (comment_api::checkIsComment((int)($_POST['courseId']), $this->user['uid'], (int)($_POST['planId']))) {
            $commentAndScore = comment_api::getCommentAndScore((int)($_POST['courseId']), $this->user['uid'], (int)($_POST['planId']));
            return interface_func::setData($commentAndScore);
        }

        return interface_func::setMsg(2043);
    }

}

