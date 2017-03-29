<?php
class user_teacher extends STpl{
	var $user;
	function __construct($inPath){
		//如果没有登陆到登陆界面
		$this->user = user_api::loginedUser();
		$authFuc = array('TeacherVideoPreview');		
		if(empty($this->user)&&in_array(strtolower($inPath[2]),$authFuc)){
			$this->redirect("/site.main.login");
		}
		//TODO判断用户是不是教师，如果不是，退出
	}
	/**
	 * 教师信息
	 */
	public function pageInfo($inPath){
		if(!empty($_POST)){
			$data=array();
			$ret = user_api::setTeacherInfo($this->user['uid'],$_POST);
			if($ret){
				$this->assign("error","修改成功");
			}
		}
		$teacher = user_api::getTeacherInfo($this->user['uid']);
		$this->assign("teacher",$teacher);
		return $this->render("user/teacher.info.html");
//		return $this->render("user/teacher.html");
	}
	public function pagePlantest($inPath){
		$path = '/user.teacher.plantest';
		$uri = '';
		$num ="6";
		$page = isset($_GET['page']) ? $_GET['page']:1;
		$uid = $this->user["uid"];
		$ret_org_info = user_organization::getOrgByTeacher($uid);
		$select_course_id = 0;
		$select_class_id = 0;
		$select_section_id = 0;
/*		if($_REQUEST){
			$select_course_id = $_REQUEST["course_id"] ? $_REQUEST["course_id"]:"0";
			$select_section_id = $_REQUEST["section_id"] ? $_REQUEST["section_id"]:"0";
			$select_class_id = $_REQUEST["class_id"] ? $_REQUEST["class_id"]:"0";
		}
*/
		$this->assign("ret_org_info",$ret_org_info);
		$plist = array(
			"cid"=>$select_course_id,
			"allcourse"=>true, //筛选所有状态!= -1的课程
			"class_id"=>$select_class_id,
			"user_plan_id"=>$uid,
			"sid"=>$select_section_id,
			"order_by"=>"desc",
			"page"=>$page,
			"length"=>$num,
		);
		$ret_plan_info_ret = course_api::listPlan($plist);
		if(!empty($ret_plan_info_ret)){
			$ret_plan_info = $ret_plan_info_ret->data;
			$this->assign("planlist",$ret_plan_info);
		}
		$list = $ret_plan_info_ret;
		$haha = SJson::encode($list);
		//	print_r($ret_plan_info);
		$selarray = array();
		//	$selarray["course_id"]["class_id"]["section_id"];
/*		foreach($ret_plan_info as $plk=>$plv){
			$selarray [$plv->course_id] = 1;
		}
		foreach($selarray as $selk=>$selv){
			foreach($ret_plan_info as $plk=>$plv){
				if($selk == [$plv->course_id]){
					$selarray[$selk][$plv->class_id] = 1;
				}
			}
		}
		print_r($selarray);
*/
		$this->assign("list",$list);
		$this->assign("path",$path);
		$this->assign("num",$num);
		return $this->render("user/teachertest.plan.html");
	}
	public function pagePlan($inPath){
		$path = '/user.teacher.plan';
		$uri = '';
		$num ="10";
		$page = isset($_GET['page']) ? $_GET['page']:1;
		$uid = $this->user["uid"];
		$ret_org_info = user_organization::getOrgByTeacher($uid);
		$course_id = isset($_GET["course_id"]) ? $_GET["course_id"]:0;
		$class_id = isset($_GET["class_id"]) ? $_GET["class_id"]:0;
		if(!empty($course_id)){
			$num=1000;
		}
		$this->assign("ret_org_info",$ret_org_info);
		$plist = array(
			"cid"=>$course_id,
			"allcourse"=>true, //筛选所有状态!= -1的课程
			"class_id"=>$class_id,
			"user_plan_id"=>$uid,
			"order_by"=>"desc",
			"page"=>$page,
			"length"=>$num,
		);
		$ret_plan_info_ret = course_api::listPlan($plist);
		if(!empty($ret_plan_info_ret)){
			$ret_plan_info = $ret_plan_info_ret->data;
			$this->assign("planlist",$ret_plan_info);
		}
		$list = $ret_plan_info_ret;
		$haha = SJson::encode($list);
		//	print_r($ret_plan_info);
		$selarray = array();
		$course_user_id = 0;
		$isOrg=false;
		if(!empty($this->user['types']->organization)){
			$isOrg=true;
			$course_user_id = $this->user['uid'];//老师的id
		}else{
			$org_info = user_organization::getOrgByTeacher($this->user['uid']);
			if(!empty($org_info->user_owner_id)){
				$course_user_id = $org_info->user_owner_id;
			}
		}
		$courses_ret = course_api::getCourselistByOid(1,1000,$course_user_id);
		$courses=array();
		if(!empty($courses_ret->data)){
			$courses = $courses_ret->data;
			//当是老师时，过滤掉不是自己的课程和班级数据
			if(!$isOrg){
				foreach($courses as $i=>$course){
					foreach($course->class as $j=>$class){
						if(!empty($class->teacher->pk_user) && $class->teacher->pk_user==$this->user['uid']){
							continue;
						}
						//删除班级
						unset($courses[$i]->class[$j]);
					}
					//删除课程
					if(empty($courses[$i]->class))unset($courses[$i]);
				}
			}
		}
		$ret = array();
		if(!empty($course_id)){
			$regdata = array("course_id"=>$course_id);
			$regdata["class_id"]= $class_id;
			$tmp_r = course_api::listRegistration($regdata);
			if(!empty($tmp_r->data)) $ret[]= $tmp_r->data;
		}
		$this->assign("ret",$ret);
		$this->assign("courses",$courses);
		$this->assign("course_id",$course_id);
		$this->assign("class_id",$class_id);
		$this->assign("list",$list);
		$this->assign("path",$path);
		$this->assign("num",$num);
		return $this->render("user/teacher.plan.html");
	}


	/*
	 * 视频上传裁剪权限
	 * @params $planId 章节id
	 * @params $type 1 老师上传视频  2 管理员班主任上传视频
	 */
	private function checkUploadAuthor($planId,$type){
		$result = new stdClass;
		$resPlan = course_api::listPlan(array("plan_id"=>$planId,"allcourse"=>1));
		if(empty($resPlan->data)){
			$result->code = -2;
			return $result;
		}
		$planInfo = $resPlan->data[0];

		if($type == 1){
			if($this->user['uid'] != $planInfo->user_plan_id){
				$result->code = -3;
				return $result;
			}
		}
		if($type == 2){
			if(user_api::isTeacher($planInfo->user_id , $this->user['uid'])===false){
				$result->code = -3;
				return $result;
			}
		}

		$result->code = 1;
		$result->data = $planInfo;
		return $result;
	}

	public function pageTeacherVideoPreview($inPath){
		return $this->render("teachervideo/teacher.profile.html");
	}
	/*
	 * 删除视频 ajax
	 * @params $field 文件id
	 * @params $type  1 老师上传视频  2 管理员上传视频
	 * @params $planUid
	 */
	public function pageDelFile(){
		$result = new stdClass;

		if(empty($_POST['fileId']) || empty($_POST['status'])){
			$result->msg  = '参数错误';
			$result->code = -1;
			return $result;
		}

		$fileId     = (int)($_POST['fileId']);
        $planUserId = !empty($_POST['planUid']) ? (int)($_POST['planUid']) : 0;
		$status     = (int)($_POST['status']);
        $planID     = empty($_POST['planId']) ? 0 : (int)($_POST['planId']) ;

		$file = live_file::getUploadFile($fileId);
		if(empty($file)){
			$result->msg  = '该视不存在';
			$result->code = -2;
			return $result;
		}

		if($status == 1){
			if($file->user_id != $this->user['uid']){
				$result->msg  = '没有权限';
				$result->code = -3;
				return $result;
			}
		}
		if($status == 2){
			if($file->user_id != $planUserId){
				$result->msg  = '没有权限';
				$result->code = -3;
				return $result;
			}
        }
		//设置状态
		if(live_file::setUploadFileStatus($fileId)){
            // 视频删除成功，若视频删除打点信息
            //$pointParams = ['pid'=>$planID,'act'=>'del'];
            //$pointRet = video_api::changeVideoTeacherPoint($pointParams);

			$result->msg  = '删除成功';
			$result->code = 1;
			return $result;
		}else{
			$result->msg  = '删除失败';
			$result->code = -4;
			return $result;
		}
	}

	/*
	 * 视频转码 ajax
	 * @params plan_id
	 * @params status  1 老师上传视频  2 管理员上传视频
	 * @params type    UPLOAD
	 */
	public function pageUploadTask(){
		$result = new stdClass;

		if(empty($_POST['plan_id']) || empty($_POST['status']) || empty($_POST['type'])){
			$result->code = -1;
			$result->msg  = '参数错误';
			return $result;
		}

		$planId = (int)($_POST['plan_id']);
		$status = (int)($_POST['status']);
		$type   = $_POST['type'];

		$planRes = $this->checkUploadAuthor($planId,$status);
		if($planRes->code == -2){
			$result->msg  = '没有上课权限';
			$result->code = -2;
			return $result;
		}
		if($planRes->code == -3){
			$result->msg  = '不是讲课老师';
			$result->code = -3;
			return $result;
		}
		if($planRes->code == -4){
			$result->msg  = '不是管理员';
			$result->code = -4;
			return $result;
		}
		if($status == 1){
            $uid = $this->user['uid'];
        }else{
            $uid = $planRes->data->user_plan_id;
        }

        $uploadTask = live_file::getUploadTask($uid,$planId);

		if(!empty($uploadTask)){
			$result->code = -11;
			$result->msg  = '视频转码中';
			return $result;
        }

		if(live_file::addUploadTask($uid,$planId,'UPLOAD')){
            // 视频转码成功，视频删除打点信息
            $pointParams = ['pid'=>$planId,'act'=>'uploadtask'];
            $pointRet = video_api::changeVideoTeacherPoint($pointParams);
			// 视频转码成功，视频删除剪辑信息
			/*$video = course_api::getCourseVideo($planId);
			$data = [
				'segs'      => null,
				'totalTime' => 0
			];
			course_api::setCourseVideoV2($video->video_id,$data);
			*/
			$result->code = 1;
			$result->msg  = '转码成功';
			return $result;
		}else{
			$result->code = -5;
			$result->msg  = '转码失败';
			return $result;
		}

	}

	//开始裁剪
	public function pagePart($inPath){
		if(empty($inPath[3]) || empty($inPath[4])){
			$this->redirect("/index.main.404");
		}

		$planId = (int)($inPath[3]);
		$status = (int)($inPath[4]);

		$planRes = $this->checkUploadAuthor($planId,$status);

		if($planRes->code != 1){
			$this->redirect("/user.teacher.upload/".$planId."/".$status);
		}

		$video = course_api::getCourseVideo($planId);
		if(empty($video)){
			$this->redirect("/user.teacher.upload/".$planId."/".$status);
		}
        $totalTime = !empty($video->totaltime)?$video->totaltime:0;

		$seg = array();
		if(!empty($video->segs)){
			$segs = json_decode($video->segs);
			$i = 1;
			foreach($segs as $v){
				$seg[$i] = [
					'start' => gmstrftime('%H:%M:%S',$v[0]),
					'end'   => gmstrftime('%H:%M:%S',$v[1])
				];
				$i++;
			}
		}

		$thumbs = array();
		if(!empty($video->thumb1)){
			for($i=1;$i<=8;$i++){
				$key = "thumb$i";
				$thumbs[] = $video->$key;
			}
		}
        $pointList = array();

        if(!empty($video->point)){
            foreach($video->point as $key => $val){
				$pointThumbs = json_decode($val->thumbs);
                $itemValueLeft = $pointThumbs->width * ($pointThumbs->cols-1);
                $itemValueTop  = $pointThumbs->height * ($pointThumbs->rows-1);
                $itemValueUrl  = utility_cdn::file($pointThumbs->thumbs);
                $thumbs_style  = "width:{$pointThumbs->width}px;"
                                . "height:{$pointThumbs->height}px;"
                                . "display:inline-block;background:url({$itemValueUrl}) no-repeat -{$itemValueLeft}px -{$itemValueTop}px;";
                $pointInfo = new stdClass();
                $pointInfo->left          = empty($pointThumbs->left) ? 0 : (int) $pointThumbs->left;
                $pointInfo->top           = empty($pointThumbs->top) ? 0 : (int) $pointThumbs->top;
                $pointInfo->stime         = $val->stime;      // 剪辑视频打点时间（秒）
                $pointInfo->ptime         = $val->ptime;      // 原始视频打点时间（秒）
                $pointInfo->seg_time      = video_api::formatSecondToTime($val->stime);      // 剪辑视频打点时间
                $pointInfo->point_time    = video_api::formatSecondToTime($val->ptime);      // 剪辑视频打点时间
                $pointInfo->pseg          = $val->pseg;       // 打点视频段
                $pointInfo->content       = $val->content;    // 打点注释内容
                $pointInfo->thumbs_style  = $thumbs_style; // 截图 style
                $pointList[] = $pointInfo;
            }
        }

		$thumb0 = !empty($video->thumb0)?$video->thumb0:'';
        $sectionDescipt = !empty($planRes->data->section_descipt) ? mb_substr($planRes->data->section_descipt,0,25,'utf-8') : '';
        $this->assign('secDesc',$sectionDescipt);
		$this->assign('thumb0',$thumb0);
		$this->assign('thumbs',$thumbs);
		$this->assign('pointList',$pointList);
		$this->assign('video',$video);
		$this->assign("plan_id",$planId);
        $this->assign("totaltime",$totalTime);
		$this->assign("plan_info",$planRes->data);
		$this->assign('status',$status);
		$this->assign("segs",$seg);
		$this->render("user/teacher.part.html");
	}

	/*
	 * 确定裁剪 ajax
	 * @params $plan_id
	 * @params $status   1 老师上传视频  2 管理员上传视频
	 * @params $times    裁剪时间
	 */
	public function pageDoPart(){
		$result = new stdClass;

		if(empty($_POST['plan_id']) || empty($_POST['status']) || empty($_POST['partArr'])){
			$result->code = -1;
			$result->msg  = '至少保留一个片段';
			return $result;
		}

		$planId = (int)($_POST['plan_id']);
		$status = (int)($_POST['status']);
		$partArr= $_POST['partArr'];

		$planRes = $this->checkUploadAuthor($planId,$status);
		if($planRes->code == -2){
			$result->msg  = '没有上课权限';
			$result->code = -2;
			return $result;
		}
		if($planRes->code == -3){
			$result->msg  = '不是讲课老师';
			$result->code = -3;
			return $result;
		}
		if($planRes->code == -4){
			$result->msg  = '不是管理员';
			$result->code = -4;
			return $result;
		}

		$timeArr   = array();
		$totalTime = 0;

		foreach($_POST['partArr'] as $v){
			$timesArr[] = explode(",",$v);
		}
		foreach ($timesArr as $key=>$value){
			$a[$key] = $value[0];
			$b[$key] = $value[1];
		}

		array_multisort($a,SORT_NUMERIC,SORT_ASC,$b,SORT_STRING,SORT_ASC,$timesArr);
		//裁剪总长度
		foreach($timesArr as $key=>$val){
			$totalTime += $val[1]-$val[0];
		}

		$video = course_api::getCourseVideo($planId);

		if($totalTime <= 0){
			$result->code = -12;
			$result->msg  = '裁剪时间不能为空';
			return $result;
		}

		$segs = json_encode($timesArr);

		$data = [
			'segs'      => $segs,
			'totalTime' => $totalTime
		];

		if(!empty($video->video_id)) course_api::setCourseVideoV2($video->video_id,$data);

		$result->code = 1;
		$result->msg  = '裁剪成功';
		return $result;
	}





	/*
	 * 机构管理-视频上传权限
	 */
	private function checkOrgAuthor($planId){
		if(empty($planId)) return -1;
		$resPlan = course_api::listPlan(array("plan_id"=>$planId,"allcourse"=>1));
		if(empty($resPlan->data[0])){
			return -2;
		}
		$planInfo = $resPlan->data[0];
		//判断是不是这个机构的管理员
		if($planInfo->user_plan_id != $this->user['uid']){
            if(user_api::isAdmin($planInfo->user_id , $this->user['uid'])===false){
                return -4;
            }
        }
		return $planInfo;
	}

	/*
	 * 机构管理-视频上传
	 */
	public function pageOrgUpload($inPath){
		if(empty($inPath[3]))
        {
			return $this->redirect("/course.info.planlist?error=1");
		}
		$planId = $inPath[3];

		$result = $this->checkOrgAuthor($planId);
		$planInfo = array();
		if(is_object($result)){
			$planInfo = $result;
		}elseif($result == -1){
			return $this->redirect("/course.info.planlist?error=1");
		}elseif($result == -2){
			return $this->redirect("/course.info.planlist?error=2");
		}elseif($result == -4){
			return $this->redirect("/course.info.planlist?error=4");
		}

		$uploadFiles = live_file::listUploadFile(0,$planId);
		$uploadTask = live_file::getUploadTask(0,$planId);
		$this->assign("token",$this->user['token']);
		$this->assign("plan_id",$planId);
		$this->assign("uploadTask",$uploadTask);
		$this->assign("plan_info",$planInfo);
		$this->assign("uploadFiles",$uploadFiles);
		$this->assign('planUserId',$planInfo->user_plan_id);
		$upload_video = utility_cdn::upload_video();

		$sc = parse_url($upload_video,PHP_URL_SCHEME);
		if(empty($sc))
        {
			if(utility_net::isHTTPS())
            {
				$upload_video="https:".$upload_video;
			}else{
				$upload_video="http:".$upload_video;
			}
		}

		$this->assign("upload_video",$upload_video);
		return $this->render("user/video.list.html");
	}

	/*
	 * 机构管理-视频裁剪
	 */
	public function pageOrgPart($inPath)
	{
		if(empty($inPath[3]))
        {
			return $this->redirect("/course.info.planlist?error=1");
		}
		$planId = $inPath[3];

		$result = $this->checkOrgAuthor($planId);
		$planInfo = array();
		if(is_object($result)){
			$planInfo = $result;
		}elseif($result == -1){
			return $this->redirect("/course.info.planlist?error=1");
		}elseif($result == -2){
			return $this->redirect("/course.info.planlist?error=2");
		}elseif($result == -4){
			return $this->redirect("/course.info.planlist?error=4");
		}

		$video = course_api::getCourseVideo($planId);
        $totalTime = !empty($video->totaltime)?$video->totaltime:0;

		if(empty($video)){
			return $this->redirect("/course.info.planlist?error=2");
		}
		$thumbs = array();
		if(!empty($video->thumb1)){
			for($i=1;$i<=8;$i++){
				$key = "thumb$i";
				$thumbs[] = $video->$key;
			}
		}
		$thumb0 = !empty($video->thumb0)?$video->thumb0:'';
		$this->assign('thumb0',$thumb0);
		$this->assign('thumbs',$thumbs);
		$this->assign('video',$video);
		$this->assign("plan_id",$planId);
        $this->assign("totaltime",$totalTime);
		$this->assign("plan_info",$planInfo);
		return $this->render("user/video.edit.html");
	}

	/*
	 * 机构管理-视频删除
	 */
	public function pageDelOrgUploadFile($inPath){
        $file_id = (int)($_POST['fileId']);
        $plan_user_id = (int)($_POST['planUid']);

		if(empty($file_id) || empty($plan_user_id))
		{
			return -1;
		}
		//获取文件信息，判断是不是当前的用户的
		$file = live_file::getUploadFile($file_id);
		if(empty($file))return -2;
		if($file->user_id != $plan_user_id)
		{
			return -3;
		}
		//设置状态
		if(live_file::setUploadFileStatus($file_id)){
			return true;
		}else{
			return -4;
		}
	}

	/*
	 * ajax 机构管理-视频转码/剪裁
	 */
	public function pageAddOrgUploadTask(){
		$result = new stdClass;
		$plan_id = (int)($_POST['plan_id']);
		if(empty($plan_id)){
			$result->code = -1;
			$result->msg  = '参数错误';
			return $result;
		}

		$plan_info = $this->checkOrgAuthor($plan_id);

		if(!is_object($plan_info)){
			return $plan_info;
		}

		$uploadTask = live_file::getUploadTask($plan_info->user_plan_id,$plan_id);
		if(!empty($uploadTask) && $uploadTask->status==1){
			$result->code = -11;
			$result->msg  = '视频转码中';
			return $result;
		}

		//裁剪
		if(!empty($_POST['times'])){

            $timeArr = array();
			$timeArr1 = array();
            $totalTime = 0;

			foreach($_POST['times'] as $v){
				$timesArr[] = explode(",",$v);
			}

			foreach ($timesArr as $key => $value) {
				foreach ($value as $k => $v) {
					$timeArr1[$key][] = utility_time::toSec($v);
				}
			}

            foreach($timeArr1 as $key=>$val){
				$totalTime += $val[1]-$val[0];
            }

			$video=course_api::getCourseVideo($plan_id);
			if($totalTime > $video->totaltime){
				$result->code = -12;
				$result->msg  = '裁剪时间大于视频总时间';
				return $result;
			}

			if($totalTime <= 0){
				$result->code = -12;
				$result->msg  = '裁剪时间不能为空';
				return $result;
			}

            $segs = json_encode($timeArr1);

			$data = [
				'segs'      => $segs,
				'totalTime' => $totalTime
			];


			if(!empty($video->video_id)) course_api::setCourseVideoV2($video->video_id,$data);
            $result->code = 1;
			$result->msg  = '裁剪成功';
			return $result;
		}

		//转码
		if(!empty($_POST['type'])){
			if(empty($uploadTask)){
				if(live_file::addUploadTask($plan_info->user_plan_id,$plan_id,'UPLOAD')){
					$result->code = 1;
					$result->msg  = '转码成功';
					return $result;
				}else{
					$result->code = -5;
					$result->msg  = '转码失败';
					return $result;
				}
			}else{
				if(live_file::setUploadTask($uploadTask->task_id,'UPLOAD')){
					$result->code = 1;
					$result->msg  = '转码成功';
					return $result;
				};
				$result->code = -5;
				$result->msg  = '转码失败';
				return $result;
			}
		}

		$result->code = -1;
		$result->msg  = '参数错误';
		return $result;
	}


	//修改视频图片
	public function pageSetCourseImgAjax()
	{
		$vid = !empty($_POST['vid'])?(int)($_POST['vid']):0;
		$img = !empty($_POST['img'])?$_POST['img']:'';
		if(empty($vid) || empty($img)){
			return -1;
		}
		$params = [
			'img' => $img,
			'vid' => $vid
		];
		return user_api::setCourseImg($params);
	}


	public function pageStutest($inPath){
		//获取老师下的机构对应的所有课程，然后过滤掉不是自己班级的信息
		$course_user_id = 0;
		$isOrg=false;
		if(!empty($this->user['types']->organization)){
			$isOrg=true;
			$course_user_id = $this->user['uid'];//老师的id
		}else{
			$org_info = user_organization::getOrgByTeacher($this->user['uid']);
			if(!empty($org_info->user_owner_id)){
				$course_user_id = $org_info->user_owner_id;
			}
		}
		$arr_view_list = array(
			"course_user_id"=>$course_user_id,//登陆老师是班主任的课程
		);
		$path = '/user.teacher.student';
		$uri = '';
		$num ="6";
		$page = isset($_GET['page']) ? $_GET['page']:1;
		//  增加筛选条件
		$course_id = isset($_REQUEST["course_id"]) ? $_REQUEST["course_id"]:0;
		$class_id = isset($_REQUEST["class_id"]) ? $_REQUEST["class_id"]:0;
		$user_class_id = 0;
		$count_all = 0;
		/************************/
		$group_item = array("user_plan_id"=>$course_user_id);
		$courses_ret = course_api::getCourselistByOid(1,1000,$course_user_id);
		$courses=array();
		if(!empty($courses_ret->data)){
			$courses = $courses_ret->data;
			//当是老师时，过滤掉不是自己的课程和班级数据
			if(!$isOrg){
				foreach($courses as $i=>$course){
					foreach($course->class as $j=>$class){
						if(!empty($class->teacher->pk_user) && $class->teacher->pk_user==$this->user['uid']){
							continue;
						}
						//删除班级
						unset($courses[$i]->class[$j]);
					}
					//删除课程
					if(empty($courses[$i]->class))unset($courses[$i]);
				}
			}
		}
		$ret = array();
		if(!empty($course_id)){
			$regdata = array("course_id"=>$course_id);
			$regdata["class_id"]= $class_id;
			$tmp_r = course_api::listRegistration($regdata);
			if(!empty($tmp_r->data)) $ret[]= $tmp_r->data;
		}
		$this->assign("ret",$ret);
		$this->assign("courses",$courses);
		$this->assign("course_id",$course_id);
		$this->assign("class_id",$class_id);
		$this->assign("path",$path);
		if(!empty($_POST["reg_user_id"])){
			if($_POST["class_up_id"]){
				$old_course_id = $course_id;
				$old_class_id = $class_id;
				$new_course_id = $_POST["course_up_id"];
				$new_class_id = $_POST["class_up_id"];
				$up_user_total = array(
					"old_course_id"=>$old_course_id,
					"old_class_id"=>$old_class_id,
					"new_course_id"=>$new_course_id,
					"new_class_id"=>$new_class_id,
				);
				//	print_r($up_user_total);
				$updata = array(
					"class_id"=>$_POST["class_up_id"],//目标班级
					"course_id"=>$_POST["course_up_id"],//目标课程
					"old_course_id"=>$old_course_id,  //原班course_id
					"old_class_id"=>$old_class_id,   //原班class_id
				);
				$post_reg_id = $_POST["reg_user_id"];//学生
				$tb_count = count($post_reg_id);
				foreach($post_reg_id as $kreg=>$vreg){
					if(!empty($vreg)){
						$reg_user_id = $kreg;
						$ret_up_class = course_api::updateregclass($reg_user_id,$updata);
					}
				}
				$ret_up_count = course_api::updateregcount($up_user_total);
				//		print_r($ret_up_count);
				if($ret_up_class){
					$this->redirect("/user.teacher.stutest?course_id=$course_id");
				}
			}
		}
		if(!empty($course_id)){
			$classes_tmp = course_api::getClasslist($course_id);
			$classes=array();
			foreach($classes_tmp as $tmp){
				$tid = $tmp->class_id;
				$classes[$tid]=$tmp;
			}
			$this->assign("classes",$classes);
		}

		return $this->render("user/teachertest.student.html");
	}
	public function pageStudent($inPath){
		//获取老师下的机构对应的所有课程，然后过滤掉不是自己班级的信息
		$course_user_id = 0;
		$isOrg=false;
		if(!empty($this->user['types']->organization)){
			$isOrg=true;
			$course_user_id = $this->user['uid'];//老师的id
		}else{
			$org_info = user_organization::getOrgByTeacher($this->user['uid']);
			if(!empty($org_info->user_owner_id)){
				$course_user_id = $org_info->user_owner_id;
			}
		}
		$arr_view_list = array(
			"course_user_id"=>$course_user_id,//登陆老师是班主任的课程
		);
		$path = '/user.teacher.student';
		$uri = '';
		$num ="6";
		$page = isset($_GET['page']) ? $_GET['page']:1;
		//  增加筛选条件
		$course_id = isset($_GET["course_id"]) ? $_GET["course_id"]:0;
		$class_id = isset($_GET["class_id"]) ? $_GET["class_id"]:0;
		$count_all = 0;
		/************************/
		$group_item = array("user_plan_id"=>$course_user_id);
		$courses_ret = course_api::getCourselistByOid(1,1000,$course_user_id);
		$courses=array();
		if(!empty($courses_ret->data)){
			$courses = $courses_ret->data;
			//当是老师时，过滤掉不是自己的课程和班级数据
			if(!$isOrg){
				foreach($courses as $i=>$course){
					foreach($course->class as $j=>$class){
						if(!empty($class->teacher->pk_user) && $class->teacher->pk_user==$this->user['uid']){
							continue;
						}
						//删除班级
						unset($courses[$i]->class[$j]);
					}
					//删除课程
					if(empty($courses[$i]->class))unset($courses[$i]);
				}
			}
		}
		$ret = array();
		if(!empty($course_id)){
			$regdata = array("course_id"=>$course_id);
			$regdata["class_id"]= $class_id;
			$tmp_r = course_api::listRegistration($regdata);
			if(!empty($tmp_r->data)) $ret[]= $tmp_r->data;
		}
		$this->assign("ret",$ret);
/*
		$course=array();
		foreach($courses as $t){
			if($t->course_id==$course_id){$course = $t;break;}
		}
*/
		if($course_id){
			$course= course_api::getCourseone($course_id);
			$classes_tmp = course_api::getClasslist($course_id);
			$classes=array();
			foreach($classes_tmp as $tmp){
				$tid = $tmp->class_id;
				$classes[$tid]=$tmp;
			}
			$this->assign("classes",$classes);
			$this->assign("course",$course);
		}
		$this->assign("courses",$courses);
		$this->assign("course_id",$course_id);
		$this->assign("class_id",$class_id);
		$this->assign("path",$path);
		//$this->assign("num",$num);
		return $this->render("user/teacher.student.html");
	}
	public function pageHelp($inPath){
		$token = live_auth::getPublishAuth($this->user['uid']);
		if(empty($token)){
			//设置token
			$ret = live_auth::setPublishAuth($this->user['uid']);
			if($ret){
				$token = live_auth::getPublishAuth($this->user['uid']);
				$this->assign("token",$token);
			}
		}else{
			$this->assign("token",$token);
		}
		return $this->render("user/teacher.help.html");
	}
	public function pageTask($inPath){
        $page = isset($_GET['page']) ? $_GET['page']:1;
        $num ="10";
        $path='/user.teacher.task';
        //$result=user_api::getTaskListByCourse((int)$inPath[3],$page,$num);
        $result=user_api::getTaskListByPlan((int)$inPath[3],$page,$num);
        //$result=user_api::getTaskListByOwner($this->user['uid'],$page,$num);
        $tids='';
        $cids='';
        $count1=array();
        $count2=array();
        if(!empty($result->data)){
            foreach($result->data as $v){
                $tids.=$v->pk_task.',';
                $cids.=$v->fk_course.',';
            }
            $tids=trim($tids,',');
            $cids=trim($cids,',');
            $count_db1=user_api::countReply($tids);
            if(!empty($count_db1->data)){
                foreach($count_db1->data as $c1){
                    $count1[$c1->fk_task]=$c1->num;
                }
            }
            $count_db2=course_api::countStudent($cids);
            foreach($count_db2 as $c2){
                $count2[$c2->fk_course]=$c2->num;
            }
        }
        $ccs=user_api::getCourseClassSection($this->user['uid']);
        $course=array();
        $class=array();
        $section=array();
        if($ccs){
            foreach($ccs as $c){
                $course[$c->course_id]=array(
                        'course_id'=>$c->course_id,
                        'course_name'=>$c->course_name,
                    );
                $class[$c->class_id]=array(
                        'class_id'=>$c->class_id,
                        'class_name'=>$c->class_name,
                    );
                $section[$c->section_id]=array(
                        'section_id'=>$c->section_id,
                        'section_name'=>$c->section_name,
                    );
            }
        }
        $pm=array(
                'plan'=>!empty($inPath[3])?$inPath[3]:0,
            );
        $this->assign("list",$result);
        $this->assign("num",$num);
        $this->assign("path",$path);
        $this->assign("count1",$count1);
        $this->assign("count2",$count2);
        $this->assign("course",$course);
        $this->assign("pm",$pm);
        return $this->render("user/teacher.task.html");
	}
    //获取作业
    public function pagegetTaskAjax($inPath){
        $result=new stdclass;
        if(empty($_REQUEST['tid'])){
            $result->error="tid is not found!";
        }
        $r=user_api::getTask($_REQUEST['tid']);
        if($r){
            $result->data=$r;
            return $result;
        }else{
            $result->error="获取失败！";
        }

    }
    //添加作业
    public function pageaddTaskAjax($inPath){
        $result=new stdclass;
        $user_id=$this->user['uid'];
        if(empty($_REQUEST['title'])){
            $result->error="请输入标题";
            $result->field="title";
            return $result;
        }
        if(empty($_REQUEST['pid'])){
            $result->error="参数错误";
            $result->field="";
            return $result;
        }
        if(empty($_REQUEST['fid']) && empty($_REQUEST['desc'])){
            $result->error="请上传附件或填写描述";
            $result->field="title";
            return $result;
        }
        $data=array(
            'user_id'=>$user_id,
            'title'=>$_REQUEST['title'],
            'plan'=>$_REQUEST['pid'],
            'attach'=>trim($_REQUEST['fid'],'|'),
            'desc'=>$_REQUEST['desc']
        );
        $r=user_api::addTask($data);
        if($r){
            $result->status="Success!";
            return $result;
        }else{
            $result->error="添加失败!";
            return $result;
        }

    }
    //修改作业
    public function pageupdateTaskAjax($inPath){
        $result=new stdclass;
        $user_id=$this->user['uid'];
        if(empty($_REQUEST['title'])){
            $result->error="请输入标题";
            $result->field="title";
            return $result;
        }
        if(empty($_REQUEST['fid']) && empty($_REQUEST['desc'])){
            $result->error="请上传附件或填写描述";
            $result->field="title";
            return $result;
        }
        if(empty($_REQUEST['tid'])){
            $result->error="参数错误";
            return $result;
        }
        $data=array(
            'title'=>$_REQUEST['title'],
            'attach'=>trim($_REQUEST['fid'],'|'),
            'desc'=>$_REQUEST['desc']
        );
        $task_info=user_api::getTask($_REQUEST['tid']);
        if($user_id!=$task_info->fk_user_owner){
            $result->error="无权限修改";
            return $result;
        }
        $r=user_api::updateTask($_REQUEST['tid'],$data);
        if($r){
            $result->status="Success!";
            return $result;
        }else{
            $result->error="修改失败!";
            return $result;
        }

    }
    //分发作业
    public function pagesendTaskAjax($inPath){
        $user_id=$this->user['uid'];
        $result=new stdclass;
        if(empty($_REQUEST['tid'])){
            $result->error="tid is not found!";
        }
        $taskInfo=user_api::getTask($_REQUEST['tid']);
        if($user_id!=$taskInfo->fk_user_owner){
            $result->error="无权限!";
            return $result;
        }
        $userArr1=array();
        $userArr2=array();
        $db_course=course_api::getStudentsByCid($taskInfo->fk_course);
        if(!empty($db_course)){
            foreach($db_course as $v1){
                $userArr1[]=$v1->fk_user;
            }
        }
        $db_reply=user_api::getReplyListByTid($_REQUEST['tid']);
        if(!empty($db_reply)){
            foreach($db_reply as $v2){
                $userArr2[]=$v2->fk_user_reply;
            }
        }
        $userIds=array_diff($userArr1,$userArr2);
        if(empty($userIds)){
            $result->status="no user need reply!";
            return $result;

        }
        $data=array();
        foreach($userIds as $u){
            $data[]=array(
                    'fk_task'=>$_REQUEST['tid'],
                    'fk_plan'=>$taskInfo->fk_plan,
                    'fk_user_reply'=>$u,
                    'create_time'=>time(),
                );

        }
        $db_result=user_api::addMoreReply($data);
        if($db_result){
            $result->status="Success!";
            return $result;
        }else{
            $result->error="失败！";
            return $result;
        }

    }
    //删除作业
    public function pagedelTaskAjax($inPath){
        $user_id=$this->user['uid'];
        $result=new stdclass;
        if(empty($_REQUEST['tid'])){
            $result->error="tid is not found!";
        }
        $task_info=user_api::getTask($_REQUEST['tid']);
        if($user_id!=$task_info->fk_user_owner){
            $result->error="无权限删除!";
            return $result;
        }
        $r=user_api::deleteTask($_REQUEST['tid']);
        if($r){
            $result->status="Success!";
            return $result;
        }else{
            $result->error="删除失败！";
            return $result;
        }

    }
	public function pageReply($inPath){
        $data=array();
        $data['t_task.fk_user_owner']= $this->user['uid'];
        if(empty($inPath[3])){
            $status=false;
        }else{
            $status=$inPath[3];
        }
        if($status=='not'){
            $data['t_task_reply.status']=0;
        }
        if($status=='ok'){
            $data['t_task_reply.status']=2;
        }
        if($status=='yes'){
            $data['t_task_reply.status']=1;
        }
        if(!empty($_REQUEST['course'])){
            $data['t_task.fk_course']=$_REQUEST['course'];
        }
        if(!empty($_REQUEST['section'])){
            $data['t_task.fk_section']=$_REQUEST['section'];
        }
        if(!empty($_REQUEST['class'])){
            $data['t_task.fk_class']=$_REQUEST['class'];
        }
        $page = isset($_GET['page']) ? $_GET['page']:1;
        $num ="10";
        $pm=array();
        $pm['status']=$status;
        $pm['course']=!empty($_REQUEST['course'])?$_REQUEST['course']:0;
        $pm['class']=!empty($_REQUEST['class'])?$_REQUEST['class']:0;
        $pm['section']=!empty($_REQUEST['section'])?$_REQUEST['section']:0;
        $path='/user.teacher.reply.'.$status;
        $result=user_api::getReplyList($page,$num,$data);
        $ccs=user_api::getCourseClassSection($this->user['uid']);
        $course=array();
        $class=array();
        $section=array();
        if($ccs){
            foreach($ccs as $c){
                $course[$c->course_id]=array(
                        'course_id'=>$c->course_id,
                        'course_name'=>$c->course_name,
                    );
                $class[$c->class_id]=array(
                        'class_id'=>$c->class_id,
                        'class_name'=>$c->class_name,
                    );
                $section[$c->section_id]=array(
                        'section_id'=>$c->section_id,
                        'section_name'=>$c->section_name,
                    );
            }
        }
        $this->assign("list",$result);
        $this->assign("num",$num);
        $this->assign("pm",$pm);
        $this->assign("path",$path);
        $this->assign("course",$course);
        $this->assign("class",$class);
        $this->assign("section",$section);
        return $this->render("user/teacher.reply.html");
	}
	public function pageReplyInfo($inPath){
        $rid=!empty($inPath[3])?$inPath[3]:0;
        $reply=user_api::getReplyInfo($rid);
        $attach=user_api::getAttachList($rid);
        $this->assign("reply",$reply);
        $this->assign("attach",$attach);
        return $this->render("user/teacher.replyinfo.html");
    }

	public function pageFileList($inPath){
		$courseId = !empty($inPath[3]) ? (int)$inPath[3] : 0;
		$classId  = !empty($inPath[4]) ? (int)$inPath[4] : 0;

		$this->assign('courseId', $courseId);
		$this->assign('classId', $classId);
		$this->assign('isCheck',5);
		return $this->render("user/file.manage.html");
	}

	public function pagestudentList($inPath){
		$courseId = !empty($inPath[3]) ? (int)$inPath[3] : 0;

		$this->assign('courseId', $courseId);
		$this->assign('isCheck',6);
		return $this->render("user/student.list.html");
	}

	public function pageStatistics($inPath){
		$courseId = !empty($inPath[3]) ? (int)$inPath[3] : 0;

		$this->assign('courseId', $courseId);
		$this->assign('isCheck',7);
		return $this->render("user/course.statistics.html");
	}
	
}
