<?php
class index_org extends STpl{

	
	private $domain;
	public function __construct(){
		if($_GET){
			foreach($_GET as $get_k	=> $get_v){
				$_GET[$get_k] = strip_tags($get_v);
			}
		}
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
		$this->assign('domain', $this->domain);
	}

	public function pageList($inPath){
		utility_cache::pageCache(600);
		if(isset($_REQUEST['search_field'])){
			$_GET['search_field'] = utility_tool::unescape($_REQUEST['search_field']);
		}
		$page = isset($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$firstCate  = isset($_REQUEST['fc'])? intval($_REQUEST['fc']):'-1';
		$search_field = isset($_REQUEST['search_field'])? addslashes($_GET['search_field']):'';
		$sort = isset($_REQUEST['sort'])?addslashes($_REQUEST['sort']):'org:desc';
		$f_array = array('org_id', 'user_owner_id','name',
			'subname',
			'subdomain',
			'desc',
			'hotline',
			'email',
			'scopes',
			'hot_type',
			'thumb_big',
			'thumb_med',
			'thumb_sma',
			'create_time',
			'province',
			'city',
			'address',
			'status',
			'vv',
			'vv_live',
			'vv_record',
			'vt',
			'vt_record',
			'vt_live',
			'zan',
			'comment_count',
			'teacher_count',
			'visiable_teacher_count',
			'course_count',
			'class_count',
			'student_count',
			'member_count',
			'order_count',
			'discuss',
			'balance',
			'income_all',
			'withdraw',
			'income_last_week',
			'orders_last_week',
			'income_last_month',
			'orders_last_month',
			'search_field',
		);

		$q_array=array(
			'search_field' => $search_field,
			'status' =>1,
			'scopes' =>$firstCate,
		);
		foreach($q_array as $k=>$v){
			if($v=='' || $v==-1 ){
				unset($q_array[$k]);
			}
		}
		$sort_arr=explode(':',$sort);
		if($sort_arr[0] == 'org'){
			$ob_array = array(
				'course_count'=>'desc',
				'visiable_teacher_count'=>'desc',
			);
		}else{
			$ob_array = array(
				$sort_arr[0]=>$sort_arr[1],
			);
		}
		$seek_arr = array(
			"f"=>$f_array,
			"q"=>$q_array,
			"ob"=>$ob_array,
			"p"=>$page,
			"pl"=>20,
		);
		$ret_seek = seek_api::seekOrg($seek_arr);
		$orgList = array();
		if(!empty($ret_seek->data)){
			$orgList=$ret_seek->data;
		}
		$scopesName = array(1=>"学前/升学",2=>"大学/考研",3=>"职业/考证",4=>"艺术",5=>"生活/兴趣");
		foreach($orgList as $org){
			$org->show_url ="//" . user_organization::course_domain($org->subdomain);
			$org->desc = strip_tags($org->desc);
			foreach($org->scopes as $scopes){
				if(!empty($scopesName[$scopes])) {
					$org->orgScopesName[] = (string)$scopesName[$scopes];
				}else{
					$org->orgScopesName[] = '';
				}
			}
			$org->orgScopesName = array_filter($org->orgScopesName);
		}
		$total=0;
		$count=0;
		if(!empty($ret_seek->total)){
			$total=ceil($ret_seek->total/$ret_seek->pagelength);
			$count=$ret_seek->total;
		}
		$pm=array(
			'page'=>$page,
			'size'=>20,
			'total'=>$total,
			'count'=>$count,
			'firstCate' =>$firstCate,
			'sort'=>$sort,
			'sort_name'=>$sort_arr[0],
		);
		$level = array("level"=>1);
		$firstCateList = index_api::getCourseFirstCateInfo($level);
		$block = index_api::getBlockInfo();
		$blockList = array();
		if(!empty($block)){
			foreach($block as $k=>$v){
				$blockList[$v->pk_block] = $v;
			}
		}
		//获取热门推荐
		$block_id=13;
		$course_info = $this->getBlockCourseOrderInfo($blockList,$block_id);
		if($course_info['is_custom']>0){
			$recomm_con = array(
				"f"=>array(
					'user_id',
					'course_id',
					'title',
					'thumb_med',
					'thumb_sma',
					'start_time',
					'subdomain',
					'try',
					'vv',
					'user_total',
					'remain_user',
					'course_type',
				),
				"q"=>$course_info['query_where'],
				"ob"=>$course_info['r_order'],
				"p"=>1,
				"pl"=>$course_info['num'],
			);
			foreach($course_info['sort'] as $v){
				$am[$v->course_id] = $v->sort_id;
			}
			$recomm_ret = seek_api::seekcourse($recomm_con);
			foreach($recomm_ret->data as $v){
				$v->sort = !empty($am[$v->course_id]) ? $am[$v->course_id] : '';
			}
			usort(
				$recomm_ret->data,
				function ($a, $b) {
					return ($a->sort <= $b->sort) ? -1 : 1;
				}
			);
		}else{
			$recomm_con = array(
				"f"=>array(
					'user_id',
					'course_id',
					'title',
					'thumb_med',
					'thumb_sma',
					'start_time',
					'vv',
					'subdomain',
					'try',
					'org_subname',
					'user_total',
					'remain_user',
					'course_type',
				),
				"q"=>$course_info['query_where'],
				"ob"=>$course_info['r_order'],
				"p"=>1,
				"pl"=>$course_info['num'],
			);
			$recomm_ret = seek_api::seekcourse($recomm_con);
		}
		$this->assign('type_recomm',!empty($course_info['order_str_type'][0]) ? $course_info['order_str_type'][0] : '');
		$this->assign('hotrecomm_name',!empty($course_info['name']) ? $course_info['name'] : '');

		$recomm_course = array();
		if(!empty($recomm_ret->data)){
			$recomm_course = $recomm_ret->data;
		}
		if(!empty($recomm_course)){
			foreach($recomm_course as $k=>&$ro){
				$ro->show_url = "//" . user_organization::course_domain($ro->subdomain)."/course.info.show/".$ro->course_id;
				if(!empty($user_course) && in_array($ro->course_id,$user_course)){
					$ro->register = 1;
				}else{
					$ro->register = 0;
				}
			}
		}
		$path='/index.org.list';
		$path_page = utility_tool::getUrl($path);
		$this->assign('path',$path);
		$this->assign('path_page',$path_page);
		$this->assign('org_list',$orgList);
		$this->assign('pm',$pm);
		$this->assign('firstCateList',$firstCateList->items);
		$this->assign('recomm_course', $recomm_course);
		$this->render('index/org.list.html');
	}

	public function getBlockCourseOrderInfo($blockList,$block_id){
		$data = array();
		$blockIn = !empty($blockList[$block_id]) ? $blockList[$block_id] : '';
		$data['num']= !empty($blockIn->total_count) ? $blockIn->total_count : '';
		$data['is_custom']= !empty($blockIn->is_custom) ? $blockIn->is_custom : 0;
		$data['name'] = !empty($blockIn->name) ? $blockIn->name : 0;
		if(!empty($blockIn)){
			$order_str= !empty($blockIn->order_str) ? $blockIn->order_str : '';
			$data['order_str_type'] =explode(":",$order_str);
			if(trim($data['order_str_type'][0])=="user_total"){
				$data['r_order']  = array('user_total'=>'desc');
			}elseif(trim($data['order_str_type'][0])=="remain_user"){
				$data['r_order']  = array('remain_user'=>'desc');
			}elseif(trim($data['order_str_type'][0])=="vv"){
				$data['r_order']  = array('vv'=>'desc');
			}else{
				$data['r_order']='';
			}
			if(!empty($blockIn->sort)){
				$data['sort']=$blockIn->sort;
			}
			if(isset($blockIn->is_custom) && $blockIn->is_custom==1){
				if(!empty($blockIn->course_arr)){
					$data['query_where'] = array("course_id"=>$blockIn->course_arr,"admin_status"=>1,'org_status'=>1);
				}else{
					$data['query_where'] = array("admin_status"=>1,'org_status'=>1);
				}
			}else{
				$data['query_where'] = array();
				if(!empty($blockIn->query_str)){
					$queryArr = explode(',',$blockIn->query_str);
					foreach($queryArr as $qo){
						$temp = explode(':',$qo);
						if($temp[0] == 'attr_value_id'){
							$data['query_where']['attr_value_id'] = str_replace('|',',',$temp[1]);
						}else{
							$data['query_where'][$temp[0]] = $temp[1];
						}
					}

				}
				$data['query_where']['admin_status'] = 1;
				$data['query_where']['org_status'] = 1;
			}
		}
		return $data;
	}
}


