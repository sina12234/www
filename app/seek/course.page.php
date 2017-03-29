<?php
/**
 * 课程sphinx接口
 * 用法 测试地址http://xxx.gn100.com/seek.course.seekcourse
 * 
 */
class seek_course extends STpl{
	/*
	 * 显示页面
	 */
	public function pageSeeklist($inPath){

	// q的查询键值
	// 过滤作用
	 $attrs = array(
		'course_id'=>'course_id',
		'title'=>'title',
		'tags'=>'tags',
		'desc'=>'desc',
		'thumb_big'=>'thumb_big',
		'thumb_med'=>'thumb_med',
		'thumb_sma'=>'thumb_small',
		'first_cate'=>'first_cate',
		'second_cate'=>'second_cate',
		'third_cate'=>'third_cate',
		'first_cate_name'=>'first_cate_name',
		'second_cate_name'=>'second_cate_name',
		'third_cate_name'=>'third_cate_name',
		'first_cate_name_display'=>'first_cate_name_display',
		'second_cate_name_display'=>'second_cate_name_display',
		'third_cate_name_display'=>'third_cate_name_display',
		'attr_value_id'=>'attr_value_id',
		'user_thumb_big'=>'user_thumb_big',
		'user_thumb_med'=>'user_thumb_med',
		'user_thumb_sma'=>'user_thumb_small',
		'user_name'=>'user_name',
		'user_real_name'=>'user_real_name',
		'course_type'=>'course_type',
		'user_id'=>'user_id',
		'public_type'=>'public_type',
		'fee_type'=>'fee_type',
		'max_user'=>'max_user',
		'min_user'=>'min_user',
		'remain_user' =>'remain_user',
		'try' => 'try',
		'status'=>'status',
		'admin_status'=>'admin_status',
		'system_status'=>'system_status',
		'start_time'=>'start_time',
		'end_time'=>'end_time',
		'create_time'=>'create_time',
		'last_updated'=>'last_updated',
		'class_id'=>'class_id',
		'section_id'=>'section_id',
		'price'=>'price',
		'market_price'=>'market_price',
		'top'=>'top',
		'vv'=>'vv',
		'user_total'=>'user_total',
		'vv_live'=>'vv_live',
		'vv_record'=>'vv_record',
		'vt'=>'vt',
		'vt_live'=>'vt_live',
		'vt_record'=>'vt_record',
		'class_count'=>'class_count',
		'comment'=>'comment',
		'discuss'=>'discuss',
		'avg_score' => 'avg_score',
		'search_field'=>'search_field',
		'have_plan_date'=>'have_plan_date',
		'subdomain'=>'subdomain',
		'org_subname'=>'org_subname',
		'class' => 'class',
		'section' => 'section',
		'course_attr' => 'course_attr',
		'org_status'=>'org_status',
		'scope' => 'scope',
		'org_id' => 'org_id',
		'member_set_id' => 'member_set_id',
		'course_tag_id' => 'course_tag_id',
		'is_promote' => 'is_promote',
		'price_promote' => 'price_promote',
		'promote_status' => 'promote_status',
		'deleted' => 'deleted',
		'city_id' =>  'city_id',
		'city' =>  'city',
		'province_id' =>  'province_id',
		'province' =>  'province',
		'resell_org_id'=>'resell_org_id',
		'expression'=>'expression',
	);

	$f = !empty($_POST["f"])?$_POST["f"]:'';
	if(!empty($f)){
		$f_array = explode(",",$f);
		foreach($f_array as $fk=>$fo){
			if(!in_array($fo,$attrs)){
				unset($f_array[$fk]);
			}
		}
	}else{
		$f_array = array("course_id","title","desc","first_cate","second_cate","third_cate",'first_cate_name',"class_count",
			'second_cate_name','third_cate_name',"thumb_big","course_type","user_id","fee_type","price","max_user","min_user",
			"status","admin_status","system_status","comment","avg_score",'vv','user_total',
			"start_time","end_time",
		);
	 }
	 //	设置q
	$q_array = array();
	$q = !empty($_POST['q'])?$_POST['q']:'';
	if(!empty($q)){
		$q_temp = explode('~',$q);
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
	 $ret_seek= seek_api::seekcourse($seek_arr);
     $courseList = array();
	 if(!empty($ret_seek->data)){
		foreach($ret_seek->data as $ro){
			$courseList[] = (array)$ro;
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
		$this->assign("courseList",$courseList);
		$this->assign('f_array',$f_array);
		return $this->render("seekview/course.seeklist.html");
	 }
	 
	}
	/**
	 * 查询页面
	 */
	public function pageSeekcourse($inPath){
	 return $this->render("seekview/course.seeksearch.html");
	}
	
	public function pageCheckPassword(){
		$password = !empty($_POST['password'])?$_POST['password']:'';
		if(!empty($password)){
			if(md5($password) == md5('2016gn100')){
				$ret = new stdclass;
				$ret->code = 0;
				$ret->msg = 'success';
			}else{
				$ret = new stdclass;
				$ret->code = -1;
				$ret->msg = 'password is error';
			}
		}else{
			$ret = new stdclass;
			$ret->code = -1;
			$ret->msg = 'password is error';
		}
		return json_encode($ret);
	}
}
