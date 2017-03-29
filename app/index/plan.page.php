<?php
class index_plan extends STpl{
	private $domain;
	private $week = array(
			1 => '星期一',
			2 => '星期二',
			3 => '星期三',
			4 => '星期四',
			5 => '星期五',
			6 => '星期六',
			7 => '星期日',
	);
    public function __construct(){
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
		$this->assign('domain', $this->domain);
	}
	public function pageList($inpath){
		utility_cache::pageCache(60);
		$curr_date = date('Y-m-d',time());
		$s_cate   = isset($_GET['cate'])?$_GET['cate']:'';
		$s_date = isset($_GET['sdate'])?$_GET['sdate']: $curr_date;
		$order  = array('start_time'=> 'asc');
		$status = '1,2,3';

		$cate_id = $s_cate;
		$s_time = $s_date . ' 00:00:00';
		$e_time = $s_date . ' 23:59:59';
		$planlist = $this->getPlanList(1,20,$order,$status,$s_time,$e_time,$cate_id,"all");
		$planlist_pages = isset($planlist->total) ? ceil($planlist->total/20) : 0;
		$planlist = isset($planlist->data) ? $planlist->data : array();
		$week_arr = array();
		$we_date = date('Y-m-d', strtotime('5 days'));
		$ws_date = date('Y-m-d', time()-3200*24);
		$week_date = $this->timeFormat($ws_date, $we_date);
		foreach($week_date as $k=>$v){
			$week_arr[$k]['sdate'] = date('m-d',strtotime($v));
			$week_arr[$k]['ydate'] = $v;
			$week_arr[$k]['date'] = str_replace('-','月',$week_arr[$k]['sdate']).'日';
			$week_arr[$k]['week'] = $this->week[date('N', strtotime($v))];
		}
        //往期回顾
		$block = index_api::getBlockInfo();
		$blockList = array();
		if(!empty($block)){
			foreach($block as $k=>$v){
				$blockList[$v->pk_block] = $v;
			}
		}
        $block_id=14;
        $course_info = $this->getBlockCourseOrderInfo($blockList,$block_id);
        if($course_info['is_custom']>0){
            $course_con = array(
                  "f"=>array(
                         'user_id',
                         'course_id',
                         'title',
                         'org_subname',
                         'thumb_sma',
                         'start_time',
                         'vv',
                         'user_total',
                         'remain_user',
                         'subdomain',
						 'try',
						 'fee_type',
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
            $course_ret = seek_api::seekcourse($course_con);
				
			foreach($course_ret->data as $v){
				$v->sort = !empty($am[$v->course_id]) ? $am[$v->course_id] : '';
			}
			usort(
				$course_ret->data,
				function ($a, $b) {
					return ($a->sort <= $b->sort) ? -1 : 1;
				}
			);
		}else{
            $course_con = array(
                  "f"=>array(
                         'user_id',
                         'course_id',
                         'title',
                         'thumb_sma',
                         'start_time',
                         'vv',
                         'user_total',
                         'org_subname',
                         'remain_user',
						 'subdomain',
						 'try',
						 'fee_type',
						 'course_type',
                      ),
                  "q"=>$course_info['query_where'],
                  "ob"=>$course_info['r_order'],
                  "p"=>1,
                  "pl"=>$course_info['num'],
              );
			$course_ret = seek_api::seekcourse($course_con);
		}
        $this->assign('type_hotcomm',!empty($course_info['order_str_type'][0]) ? $course_info['order_str_type'][0] : '');
		$this->assign('before_name',!empty($course_info['name']) ? $course_info['name'] : '');
		$course_list = array();
		if(!empty($course_ret->data)){
        	$course_list = $course_ret->data;
		}


		$owner_id_arr = array();
		$cid_arr = array();
		if(!empty($planlist)){
			foreach($planlist as $po){
				$owner_id_arr[$po->owner_id] = $po->owner_id;
				$cid_arr[$po->course_id] = $po->course_id;
			}
		}
		
		
		if(!empty($course_list)){
        	foreach($course_list as &$co){
				$co->show_url = "//" . user_organization::course_domain($co->subdomain)."/course.info.show/".$co->course_id;
        	}
		}
		$price_ret = array();
		if(!empty($cid_arr)){
			$price_ret = $this->getCoursePrice($cid_arr);
		}
		if(!empty($planlist)){
        	$planlist = $this->handlePlan($planlist,$price_ret); 
        }
		$this->assign('path','/index.plan.list');
		$this->assign('planlist_pages', $planlist_pages);
		$this->assign('cate', $s_cate);
		$this->assign('ydate', $s_date);
		$this->assign('planlist', $planlist);
		//echo "<pre>";print_r($course_list);die;
		$this->assign('week_arr', $week_arr);	
		$this->assign('course_list',$course_list);
		$this->render('index/plan.list.html');

	}
	public function pageVerifyPlan($inPath){
        $user = user_api::loginedUser();
		if(!empty($user['uid']) && !empty($_REQUEST['plan_ids']) && is_array($_REQUEST['plan_ids'])){ 
			return course_api::verifyPlanMulti($user['uid'],$_REQUEST['plan_ids']);
		}
		return array();
	}
	
	public function getCoursePrice($cid_arr){
		$cid_str = implode(',',$cid_arr);
    	$course_con = array(
                  "f"=>array(
                         'course_id',
                         'fee_type',
						 'price',
                      ),
                  "q"=>array(
                          'course_id'=>$cid_str,
                      ),
                  "ob"=>array(
                          'course_id'=>'desc',
                          ),
                  "p"=>1,
                  "pl"=>20,
              );
        $course_ret = seek_api::seekcourse($course_con);
        $course_list = array();
		if(!empty($course_ret->data)){
			foreach($course_ret->data as $co){
				$course_list[$co->course_id] = $co;
			}
		}
		return $course_list;
	}

	public function pagePlanAjax($inPath){
		$curr_date = date('m-d', time());
		$s_cate = isset($_POST['cate'])?$_POST['cate']:'';
        $s_date = isset($_POST['sdate'])?$_POST['sdate']: '';
		$page = !empty($_POST['page'])?$_POST['page']: 1;
		if(empty($s_date)){
			return $this->setAjaxResult(-1,'参数错误');
		}
        $order  = array('start_time'=> 'asc');
        $status = '1,2,3';

		$cate_id = $s_cate;
        $s_time = $s_date . ' 00:00:00';
        $e_time = $s_date . ' 23:59:59';
        $planlist = $this->getPlanList($page,20,$order,$status,$s_time,$e_time,$cate_id);
		$cid_arr = array();
        if($planlist){
        	foreach($planlist as $po){
				$cid_arr[$po->course_id] = $po->course_id;
            }
       	}

		$price_ret = array();
		if(!empty($cid_arr)){
			$price_ret = $this->getCoursePrice($cid_arr);
		}
		if(!empty($planlist)){
            $planlist = $this->handlePlan($planlist,$price_ret);
			return $this->setAjaxResult(0,'success', $planlist);
        }else{
			return $this->setAjaxResult(-2,'not find datas');
		}
	}

	public function setAjaxResult($code, $msg, $data=array()){
		return json_encode(
			array(
				'code' => $code,
				'msg'  => $msg,
				'data' => $data
			),
			JSON_UNESCAPED_UNICODE
		);
    }

	public function handlePlan($planlist,$price_ret){
		$plan_ids = array();
		foreach($planlist as $v){
			$plan_ids[]=$v->plan_id;
		}
		//$checkResults=array();
		//if(!empty($this->user['uid'])){ 
		//	$checkResults = course_api::verifyPlanMulti($this->user['uid'],$plan_ids);
		//}
		foreach($planlist as &$v){
			$plan_id = $v->plan_id;
            $plan_url = "//" . user_organization::course_domain($v->subdomain)."/course.plan.play/".$v->plan_id;
            $course_url = "//" . user_organization::course_domain($v->subdomain)."/course.info.show/".$v->course_id;
			$v->plan_url = $plan_url;
			$v->course_url = $course_url;
			$v->try_video = $v->try;
        	if($v->status == 2){
				$v->tip_class = 'live-btn';
                $v->tip = '正在直播';
                //$v->tip_url = $plan_url;
            }elseif($v->status == 3){
				$v->tip_class = 'enter-btn';
                $v->tip = '观看录播';
            	//$v->tip_url = $plan_url;
			//}elseif(!empty($checkResults->$plan_id->ok)){
			//	$v->register = 1;
			//	$v->tip_class = 'enter-btn';
			//	$v->tip = '进入课堂';
			//	$v->tip_url = $plan_url;
            }else{
				$v->tip_class = 'sign-btn';
            	$v->tip = '立即报名';
                //$v->tip_url = $course_url;
            }
			if(!empty($price_ret) && !empty($price_ret[$v->course_id])){
				$v->fee_type = $price_ret[$v->course_id]->fee_type;
				$v->price = $price_ret[$v->course_id]->price;
			}else{
				$v->fee_type = 0;
				$v->price = 0;
			}
			$v->start_time = date('H:i',strtotime($v->start_time));
			//if(!empty($checkResults->$plan_id->ok)){
			//	$v->register = 1;
			//}else{
			//	$v->register = 0;
			//}
        }
		return $planlist;

	}

	public function getPlanList($page,$size,$order,$status,$start_time,$end_time,$second_cate,$data_type="data"){

		$plan_condition = array(
			'second_cate'   => "$second_cate",
			'status'     => "$status",
			'course_type' => 1,
			'start_time' => "$start_time,$end_time",
			'admin_status' => 1,
			'org_status'=>1
		);
		foreach($plan_condition as $k=>$vo){
			if(empty($vo)){
				unset($plan_condition[$k]);
			}
		}
		$planArr=array(
			"f"=>array(
				'course_id',
				'plan_id',
				'class_id',
				'first_cate',
				'second_cate',
				'subdomain',
				'teacher_id',
				'owner_id',
				'section_id',
				'course_name',
				'class_name',
				'section_name',
				'teacher_name',
				'teacher_real_name',
				'try',
				'fee_type',
				'org_subname',
				'course_type',
				'start_time',
				'end_time',
				'max_user',
				'user_total',
				'status',
			),
			"q" =>$plan_condition,
			"ob"=>$order,
			"p" =>$page,
			"pl"=>$size,
		);
		$seek_plan=seek_api::seekPlan($planArr);
		if(!empty($seek_plan->data)){
 			if($data_type=='all'){
 				return $seek_plan;
 			}
 			return $seek_plan->data;
 		}else{
			return array();
		}
	}

	public function timeFormat($begin, $end){
    	$time = range(strtotime($begin), strtotime($end),24*60*60);

        return  array_map(create_function('$v', 'return date("Y-m-d", $v);'), $time);
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

	//平台直播列表优化改版
	public function pageNewList(){
		//utility_cache::pageCache(60);
		$result 	= new stdClass;
		$curr_date = date('Y-m-d',time());
		$s_cate   = isset($_GET['cate'])?$_GET['cate']:'';
		$s_date = isset($_GET['sdate'])?$_GET['sdate']: $curr_date;
		$order  = array('start_time'=> 'asc');
		$status = '1,2,3';

		$cate_id = $s_cate;
		$s_time = $s_date . ' 00:00:00';
		$e_time = $s_date . ' 23:59:59';
		$planlist = $this->getPlanList(1,20,$order,$status,$s_time,$e_time,$cate_id);
		

		$week_arr = array();
		$we_date = date('Y-m-d', strtotime('5 days'));
		$ws_date = date('Y-m-d', time()-3200*24);
		$week_date = $this->timeFormat($ws_date, $we_date);
		foreach($week_date as $k=>$v){
			$week_arr[$k]['sdate'] = date('m-d',strtotime($v));
			$week_arr[$k]['ydate'] = $v;
			$week_arr[$k]['date'] = str_replace('-','月',$week_arr[$k]['sdate']).'日';
			$week_arr[$k]['week'] = $this->week[date('N', strtotime($v))];
		}
        //往期回顾
		/*$block = index_api::getBlockInfo();
		$blockList = array();
		if(!empty($block)){
			foreach($block as $k=>$v){
				$blockList[$v->pk_block] = $v;
			}
		}
        $block_id=14;
        $course_info = $this->getBlockCourseOrderInfo($blockList,$block_id);
        if($course_info['is_custom']>0){
            $course_con = array(
                  "f"=>array(
                         'user_id',
                         'course_id',
                         'title',
                         'org_subname',
                         'thumb_sma',
                         'start_time',
                         'vv',
                         'user_total',
                         'remain_user',
                         'subdomain',
						 'try',
						 'fee_type',
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
            $course_ret = seek_api::seekcourse($course_con);
				
			foreach($course_ret->data as $v){
				$v->sort = !empty($am[$v->course_id]) ? $am[$v->course_id] : '';
			}
			usort(
				$course_ret->data,
				function ($a, $b) {
					return ($a->sort <= $b->sort) ? -1 : 1;
				}
			);
		}else{
            $course_con = array(
                  "f"=>array(
                         'user_id',
                         'course_id',
                         'title',
                         'thumb_sma',
                         'start_time',
                         'vv',
                         'user_total',
                         'org_subname',
                         'remain_user',
						 'subdomain',
						 'try',
						 'fee_type',
						 'course_type',
                      ),
                  "q"=>$course_info['query_where'],
                  "ob"=>$course_info['r_order'],
                  "p"=>1,
                  "pl"=>$course_info['num'],
              );
			$course_ret = seek_api::seekcourse($course_con);
		}
        $this->assign('type_hotcomm',!empty($course_info['order_str_type'][0]) ? $course_info['order_str_type'][0] : '');
		$this->assign('before_name',!empty($course_info['name']) ? $course_info['name'] : '');
		*/
		$course_list = array();
		if(!empty($course_ret->data)){
        	$course_list = $course_ret->data;
		}


		$owner_id_arr = array();
		$cid_arr = array();
		if(!empty($planlist)){
			foreach($planlist as $po){
				$owner_id_arr[$po->owner_id] = $po->owner_id;
				$cid_arr[$po->course_id] = $po->course_id;
			}
		}
		
		
		if(!empty($course_list)){
        	foreach($course_list as &$co){
				$co->show_url = "//" . user_organization::course_domain($co->subdomain)."/course.info.show/".$co->course_id;
        	}
		}
		$price_ret = array();
		if(!empty($cid_arr)){
			$price_ret = $this->getCoursePrice($cid_arr);
		}
		
		/*$this->assign('path','/index.plan.list');
		$this->assign('cate', $s_cate);
		$this->assign('ydate', $s_date);
		$this->assign('week_arr', $week_arr);	
		$this->assign('course_list',$course_list);
		*/
		if(!empty($planlist)){
        	$planlist 		= $this->handlePlan($planlist,$price_ret); 
			$result->code	= 100;
			$result->data	= $planlist;
			$result->msg	= "获取成功";
			return $result;
        }else{
			$result->code	= -101;
			$result->data	= "";
			$result->msg	= "获取失败";
			return $result;
		}
	}
	



}
