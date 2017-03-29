<?php
class course_public extends STpl{
	function __construct(){
	}
	function pageEntry($inPath){
		$page = 1;
		$size = 20;
		$feetype= "free";
		$grade_id= 0;
		if(!empty($_REQUEST['grade_id'])){
			$grade_id= $_REQUEST['grade_id'];
		}
		$org=user_organization::subdomain();
		$oid = $org->userId;
		$courselist = course_api::getcourselist($page,$size,$feetype,$grade_id,'',$oid,1);
		$this->assign("courselist",$courselist);
		$this->assign("grade_id",$grade_id);
		return $this->render("course/public.html");
	}

	/*
	 * ajax获取教师课程数据
	 * @author zhengtianlong
	*/
	public function pageAjaxTeacherCourseList()
    {

		$language = !empty($_POST['lan']) ? $_POST['lan'] : '';
		if($language == 'en'){
			$status = array(0=>'Start',1=>'Start',2=>'Continue',3=>'Continue');
		}else{
			$status = array(0=>'开始上课',1=>'开始上课',2=>'继续上课',3=>'继续上课');
		}
       
        $param = $_POST['param'];
        $param['start_time'] = strtotime($param['start_time']);
        $res = course_api::listPlan($param);
        if (empty($res->data)) return interface_func::setMsg(2000);
        $result = new stdclass;
        foreach ($res->data as $k=> &$v)
        {
            $result->courseList[$k] = array(
                'course_id'    => $v->course_id,
                'class_id'     => $v->class_id,
                'title'        => $v->title,
                'class_name'   => $v->class_name,
                'section_name' => $v->section_name,
                'thumb'        => utility_cdn::file($v->thumb_small),
                'teacher_name' => $v->user_plan->real_name,
                'plan_id'      => $v->plan_id,
                'status'       => $status[$v->status],
                'type'         => $v->status,
                'start_time'   => date('Y-m-d H:i',strtotime($v->start_time))
            );

            if(date('Y-m-d',strtotime($v->start_time))>date('Y-m-d'))
            {
                $v->status = 4;
				if($language == 'en'){
					$result->courseList[$k]['status'] = 'Not start';
				}else{
					$result->courseList[$k]['status'] = '未开课';
				}
			}

            //添加css样式
            if($v->status==4)
            {
                $result->courseList[$k]['classs'] = 'no-start-subject';
				if($language == 'en'){
					$result->courseList[$k]['click'] = "layer.msg('No classes')";
				}else{
					$result->courseList[$k]['click'] = "layer.msg('暂未开课')";
				}
            }else
            {
                $result->courseList[$k]['click'] = "getId(".$v->course_id.",".$v->class_id.",".$v->plan_id.")";
                $result->courseList[$k]['classs'] = 'start-subject';
            }
        }
        foreach ($result->courseList as &$v)
        {
            //$v['num'] = count(course_api::listSection($v['course_id']));
            $sectionReg = course_plan_api::getPlanNumByCourseId($v['course_id']);
            $v['num'] = !empty($sectionReg) ? $sectionReg['num'] : 0;
        }
        exit(json_encode($result->courseList));
    }

}