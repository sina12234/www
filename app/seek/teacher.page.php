<?php
/**
 * 课程sphinx接口
 * 用法 测试地址http://xxx.gn100.com/seek.teacher.seekteacher
 * 
 */
class seek_teacher extends STpl{
	/*
	 * 显示页面
	 */
	public function pageSeeklist($inPath){

		// q的查询键值
		// 过滤作用
		 $attrs = array(
			'teacher_id'=>'teacher_id',
			'org_id'=>'org_id',
			'role'=>'role',
			'name'=>'name',
			'visiable'=>'visiable',
			'real_name'=>'real_name',
			'birthday'=>'birthday',
			'gender'=>'gender',
			'user_status'=>'user_status',
			'verify_status'=>'verify_status',
			'teacher_status'=>'teacher_status',
			'platform_status'=>'platform_status',
			'thumb_big'=>'thumb_big',
			'thumb_med'=>'thumb_med',
			'thumb_sma'=>'thumb_small',
			'register_ip'=>'register_ip',
			'create_time'=>'create_time',
			'last_login'=>'last_login',
			'mobile'=>'mobile',
			'title'=>'title',
			'college'=>'college',
			'years'=>'years',
			'diploma'=>'diploma',
			'subject_id'=>'subject_id',
			'grade_id'=>'grade_id',
			'desc'=>'descript',
			'org_name'=>'org_name',
			'org_subname'=>'org_subname',
			'province'=>'province',
			'city'=>'city',
			'course_count' => 'course_count',
			'student_count'=>'student_count',
			'avg_score'=>'avg_score',
			'score_user_count' => 'score_user_count',
			'student_score'=>'student_score',
			'desc_score'=>'desc_score',
			'explain_score'=>'explain_score',
			'totaltime'=> 'totaltime',
			'comment' => 'comment',
			'weight' => 'weight',
			'search_field'=>'search_field',
			'org_teacher' => 'org_teacher',
			'subject' => 'subject',
			'grade' => 'grade',
		);

		$f = $_POST["f"];
		if(!empty($f)){
			$f_array = explode(",",$f);
			foreach($f_array as $fk=>$fo){
				if(!in_array($fo,$attrs)){
					unset($f_array[$fk]);
				}
			}
		}else{
			$f_array = array(
				'teacher_id',
				'org_id',
				'role',
				'name',
				'visiable',
				'real_name',
				'birthday',
				'gender',
				'user_status',
				'verify_status',
				'teacher_status',
				'thumb_big',
				'thumb_med',
				'thumb_sma',
				'mobile',
				'title',
				'college',
				'years',
				'diploma',
				'subject_id',
				'grade_id',
				'org_name',
				'org_subname',
				'province',
				'city',
				'course_count',
				'student_count',
				'avg_score',
				'comment',
			);
		 }

		 //	设置q
		$q_array = array();
		$q = $_POST['q'];
		if(!empty($q)){
			$q_temp = explode(' ',$q);
			foreach($q_temp as $temp){
				if(!empty($temp)){
					$query = explode(':',$temp);
					if(in_array($query[0],$attrs)){
						$q_array[$query[0]] = $query[1];
					}
				} 	 
			}
		}
		 
		 // 设置ob
		$obNewarray = array();
		if(!empty($_POST["ob"])){
			$ob = $_POST["ob"];
			$obArrayEx = explode(" ",$ob);
			foreach($obArrayEx as $obk=>$obv){
				$obvArrayEx = explode(":",$obv);
				$obNewarray[$obvArrayEx[0]] = $obvArrayEx[1];
			}
		}
		 $ob_array = array();
		 $ob_array = $obNewarray;
		 if(empty($ob_array)){
			 $ob_array["course_id"] = "desc";
		 }
		 
		 // 设置p
		 if(!empty($_POST["p"])){
			 $p = $_POST["p"];
		 }else{
			 $p = 1;	 
		 }
		 
		 // 设置pl
		 if(!empty($_POST["pl"])){
			 $pl = $_POST["pl"];
			 if($pl > 500 ){
				 $pl = 500;
			 }
		 }else{
			 $pl = 20;	 
		 }

		 $seek_arr = array(
			 "f"=>$f_array,
			 "q"=>$q_array,
			 "ob"=>$ob_array,
			 "p"=>$p,
			 "pl"=>$pl,
		 );
		 $ret_seek= seek_api::seekteacher($seek_arr);
		 $teacherList = array();
		 if(!empty($ret_seek->data)){
			foreach($ret_seek->data as $ro){
				$teacherList[] = (array)$ro;
			}
		 }
		 $type = !empty($_POST['dataType'])?$_POST['dataType']:2;
		 if($type == 1){
			if(!empty($ret_seek->data)){
				echo "<pre>";
				var_dump($ret_seek->data);
				echo "</pre>"; 
			}else{
				echo "获取数据失败!";
			}
		 }else{
			 
			$this->assign('num',$pl);
			$this->assign("ret_seek",$ret_seek);
			$this->assign("teacherList",$teacherList);
			$this->assign('f_array',$f_array);
			return $this->render("seekview/teacher.seeklist.html");
		 }
	}
	/**
	 * 查询页面
	 */
	public function pageSeekTeacher($inPath){
	 return $this->render("seekview/teacher.seeksearch.html");
	}
	

}
