<?php
class user_commont extends STpl{
	public $orgOwner;
	public $user;
	public function __construct(){
		$this->user = user_api::loginedUser();
		if(empty($this->user)){
			$this->redirect("/site.main.login");
		}
		$org=user_organization::subdomain();
		$this->domain = $_SERVER["HTTP_HOST"];
		if(!empty($org)){
			$this->orgOwner = $org->userId; //机构所有者id 以后会根据域名而列取 
		}else{
            header('Location: https://www.'.$this->domain);
		}
	}
	
	public function pageCourseNav($inPath){
		$courseId = !empty($inPath[3]) ? (int)$inPath[3] : 0;
		$courseReg = course_api::getCourseOne($courseId);
		if(empty($courseReg)) return $this->redirect("/user.org.course");
		$isImg = !empty($courseReg->thumb_big) ? 1 : 0;		
		//管理员
		$isAdmin = user_api::isAdmin($this->orgOwner, $this->user['uid']);
		
		$teacherAuth = user_api::checkTeacherCreateCourse($courseId, $this->user['uid']);
		$this->assign('courseId', $courseId);
		$this->assign('teacherAuth', $teacherAuth);
		$this->assign('isAdmin', $isAdmin);
		$this->assign('isImg', $isImg);
		return $this->render("user/course.left.html");
	}
	
	public function pageCourseTop($inPath){
		$courseId = !empty($inPath[3]) ? $inPath[3] : 0;
		$courseReg  = course_api::getCourseOne($courseId);
        $sectionReg = course_plan_api::getPlanNumByCourseId($courseId);
		//获取课程平均分
		$ret = comment_api::courseAverage($courseId);
		$score = 0;
		if($ret){
			if(empty($ret->total_user)){

			}else{
				$score = round($ret->score/$ret->total_user,1);
			}
		}
		if(empty($courseReg)){
			$this->redirect("/user.org.course");
		}

		$adminStatusArr = array('-1'=>"notpassed",'-2'=>"offline",0=>"initial",1=>"normal");
		//中间层查询统计数
		$params = [
			'q' => ['course_id'=>$courseId],
			'f' => ['vv','comment']
		];
		$courseRes = seek_api::seekCourse($params);
		
		$data = [
			'courseId'  => $courseId,
			'thumbMed'  => interface_func::imgUrl($courseReg->thumb_med),
			'title'     => $courseReg->title,
			'price'     => !empty($courseReg->fee->price) ? $courseReg->fee->price : 0,
			'publicType'=> !empty($courseReg->public_type) ? '有试听' : '',
			'sectionNum'=> !empty($sectionReg) ? $sectionReg->num : 0,
			'commNum'   => $score ? $score : 0,
			'vv'        => !empty($courseRes->data[0]) ? $courseRes->data[0]->vv : 0,
			'userTotal' => $courseReg->user_total,
			'adminStatus'=> ($courseReg->admin_status==1) ? 'offline' : 'normal',
			'adminStatusName'=> ($courseReg->admin_status==1) ? '下架课程' : '上架课程'
		];
		$this->assign('type',$courseReg->type_id);
		$this->assign('courseInfo',$data);
		return $this->render("user/course.top.html");
	}
}
