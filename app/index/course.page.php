<?php
class index_course extends STpl{
	private $user;
	private $domain;
	public function __construct(){
		$this->user = user_api::loginedUser();
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
		$this->assign('domain', $this->domain);
	}	
	public function pageList($inPath){
		utility_cache::pageCache(600);
 		$page = isset($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$_GET['fc'] = $firstCate  = isset($_REQUEST['fc'])? intval($_REQUEST['fc']):'-1';
		$_GET['sc'] = $secondCate = isset($_REQUEST['sc'])? intval($_REQUEST['sc']):'-1';
		$_GET['tc'] = $thirdCate  = isset($_REQUEST['tc'])? intval($_REQUEST['tc']):'-1';
		$_GET['vid'] = $attrValue  = isset($_REQUEST['vid'])? strip_tags($_REQUEST['vid']):'-1';
		$_GET['ft'] = $fee_type =isset($_REQUEST['ft'])? intval($_REQUEST['ft']):'-1';
		$_GET['ct'] = $course_type = isset($_REQUEST['ct'])? intval($_REQUEST['ct']):'-1';
		$_GET['sort'] = $sort = isset($_REQUEST['sort'])? strip_tags($_REQUEST['sort']):'course_id:desc';
		$_GET['start_time'] = $start_time = isset($_REQUEST['start_time'])?strip_tags($_REQUEST['start_time']):'';
		$_GET['search_field'] = $search_field = isset($_REQUEST['search_field'])?utility_tool::unescape(strip_tags($_REQUEST['search_field'])):'';
		$size = 16;
		
		$user_course = array();
		if(!empty($this->user['uid'])){
			$reg_info = course_api::listRegistration(array("uid"=>$this->user['uid']));
          	if(!empty($reg_info->data)){
				foreach($reg_info->data as $rv){
                	$user_course[] = $rv->cid;
				}
            }
		}
		
		$attrValueId = '';
		$attrIds = '';
		if($attrValue != -1){
			$attrArr = explode(',',$attrValue);
			foreach($attrArr as $attr){
				$temp = explode('|',$attr);
				if($temp[1] != -1){
					$attrValueIdArr[] = $temp[1];
				}
				$attrIdArr[] = $temp[0];
			}
			if(!empty($attrIdArr)){
				$attrIds = implode(',', $attrIdArr);
			}
			if(!empty($attrValueIdArr)){
				$attrValueId = implode(',',$attrValueIdArr);
			}
		}

        $f_array = array("course_id","title","create_time","start_time","first_cate","second_cate","third_cate","thumb_big","thumb_med","thumb_sma",
                "course_type","user_id","public_type","fee_type","price","market_price","user_total",
                "status","admin_status","system_status","class_id","end_time","create_time","last_updated",
                "class",'vv','try','attr_value_id','subdomain','org_subname',
        		'top'=>'top',
            );
		$f_name = 'title';
    
        $q_array=array(
                'first_cate'  => $firstCate,
				'second_cate' => $secondCate,
				'third_cate'  => $thirdCate,
				'attr_value_id' => $attrValueId,
                'fee_type' => $fee_type,
                'have_plan_date' => $start_time,
                'admin_status' => 1,
				'course_type' => $course_type,
				'search_field' => $search_field,
				'org_status' =>1,
            );
        foreach($q_array as $k=>$v){
            if($v==='' || $v==-1 ){
                unset($q_array[$k]);
            }
        }
        $sort_arr=explode(':',$sort);
		if($sort_arr[0] == 'vv'){
			$ob_array = array(
				'user_total'=>'desc',
				$sort_arr[0]=>$sort_arr[1],
			);
		}elseif($sort_arr[0] == 'price'){
			$ob_array = array(
				'fee_type'=>$sort_arr[1],
				$sort_arr[0]=>$sort_arr[1],
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
                "pl"=>$size,
            );
        $ret_seek = seek_api::seekcourse($seek_arr);
		$courseList = array();
		if(!empty($ret_seek->data)){
        	$courseList=$ret_seek->data;
		}
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


		if(!empty($courseList)){
        	foreach($courseList as $k=>&$co){
				$co->show_url = "//" . user_organization::course_domain($co->subdomain)."/course.info.show/".$co->course_id;
				if(!empty($user_course) && in_array($co->course_id,$user_course)){
					$co->register = 1;
				}else{
					$co->register = 0;
				}
			}
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

		//获取分类以及属性
		$firstCateList  = array();
		$secondCateList = array();
		$thirdCateList  = array();
		$attrValueList  = array();
		$showLevel = 0;
		if($firstCate == -1){
			$firstCateList = $this->getFirstCateList();
			$showLevel = 1;
		}elseif($firstCate != -1 && $secondCate == -1){
			$secondCateList = $this->getSecondCateList($firstCate);
			$showLevel = 2;
		}elseif($firstCate != -1 && $secondCate != -1 && $thirdCate == -1){
			$thirdCateList = $this->getThirdCateList($secondCate);
			$showLevel = 3;	
		}elseif($firstCate != -1 && $secondCate != -1 && $thirdCate != -1){
			$thirdCateList = $this->getThirdCateList($secondCate);
			$attrValueList = $this->getAttrValueList($thirdCate,$attrValueId,$attrIds);
			$showLevel = 3;
		}
		$firstCateInfo = array();
		$secondCateInfo = array();
		if($firstCate != -1){
			$firstCateInfo = course_api::getCateByCateId($firstCate);
		}
		if($secondCate != -1){
			$secondCateInfo = course_api::getCateByCateId($secondCate);
		}
		$total = 0;
		$count = 0;
		if(!empty($ret_seek->total)){
        	$total=ceil($ret_seek->total/$ret_seek->pagelength);
        	$count=$ret_seek->total;
		}
        $pm=array(
                'page'=>$page,
				'size'=>$size,
                'total'=>$total,
                'count'=>$count,
                'firstCate' =>$firstCate,
                'secondCate'=>$secondCate,
				'thirdCate' =>$thirdCate,
                'fee_type'=>$fee_type,
                'course_type'=>$course_type,
                'sort'=>$sort,
                'sort_name'=>$sort_arr[0],
                'start_time'=>$start_time,
            );
        $path='/index.course.list';
		if($start_time){
			$path_page = utility_tool::getUrl($path,'','',$start_time);
		}else{
			$path_page = utility_tool::getUrl($path);
		}
		$this->assign('path_page',$path_page);
		$this->assign('firstCateInfo',$firstCateInfo);
		$this->assign('secondCateInfo',$secondCateInfo);
		$this->assign('showLevel',$showLevel);
		$this->assign('firstCateList',$firstCateList);
		$this->assign('secondCateList',$secondCateList);
		$this->assign('thirdCateList',$thirdCateList);
		$this->assign("attrValueList",$attrValueList);
		$this->assign('courseList', $courseList);
		$this->assign("path",$path);
		$this->assign("pm",$pm);
		$this->assign('recomm_course', $recomm_course);
		return $this->render("index/course.list.html");
	}
	
	public function getFirstCateList(){	
		$showFirstCate = array();
		$firstCateList = course_api::getCateByLevel(1);
		if(!empty($firstCateList)){
			foreach($firstCateList as $fcate){
				$fcateIdArr[] = $fcate->pk_cate;
			}
			$firstRet = course_api::checkCourseByFirstCateArr($fcateIdArr);
			if(!empty($firstRet)){
				foreach($firstRet as $ro){
					$tempFirst[] = $ro->first_cate; 
				}
				$hideFirst = array_diff($fcateIdArr,$tempFirst);	
				if(!empty($hideFirst)){
					foreach($firstCateList as $fc){
						if(!in_array($fc->pk_cate,$hideFirst)){
							$showFirstCate[] = $fc;
						}
					}
				}else{
					$showFirstCate = $firstCateList;
				}	
			}
		}
		if(!empty($showFirstCate)){
			foreach($showFirstCate as $so){
				$tempShow[$so->pk_cate] = $so->name_display;
			}
			$showFirstCate = $tempShow;
		}
		return $showFirstCate;
	}
	
	public function getSecondCateList($firstCate){	
		$showCateList = array();
		$cateRet = course_api::getNodeCate($firstCate);
		if(!empty($cateRet)){
			foreach($cateRet as $cate){
				$cateIdArr[] = $cate->pk_cate;
			}
			$courseRet = course_api::checkCourseBySecondCateArr($cateIdArr);
			if(!empty($courseRet)){
				foreach($courseRet as $ro){
					$tempCate[] = $ro->second_cate; 
				}
				$hideCate = array_diff($cateIdArr,$tempCate);	
				if(!empty($hideCate)){
					foreach($cateRet as $cl){
						if(!in_array($cl->pk_cate,$hideCate)){
							$showCateList[] = $cl;
						}
					}
				}else{
					$showCateList = $cateRet;
				}	
			}
		}
		if(!empty($showCateList)){
			foreach($showCateList as $so){
				$tempShow[$so->pk_cate] = $so->name_display;
			}
			$showCateList = $tempShow;
		}
		return $showCateList;
	}
	
	public function getThirdCateList($secondCate){	
		$showCateList = array();
		$cateRet = course_api::getNodeCate($secondCate);
		if(!empty($cateRet)){
			foreach($cateRet as $cate){
				$cateIdArr[] = $cate->pk_cate;
			}
			$courseRet = course_api::checkCourseByThirdCateArr($cateIdArr);
			if(!empty($courseRet)){
				foreach($courseRet as $ro){
					$tempCate[] = $ro->third_cate; 
				}
				$hideCate = array_diff($cateIdArr,$tempCate);	
				if(!empty($hideCate)){
					foreach($cateRet as $cl){
						if(!in_array($cl->pk_cate,$hideCate)){
							$showCateList[] = $cl;
						}
					}
				}else{
					$showCateList = $cateRet;
				}	
			}
		}
		if(!empty($showCateList)){
			foreach($showCateList as $so){
				$tempShow[$so->pk_cate] = $so->name_display;
			}
			$showCateList = $tempShow;
		}
		return $showCateList;
	}
	
	public function getAttrValueList($thirdCate,$attrValueId,$attrIds){	
		$requestValueIdArr = array();
		$requestAttrIdArr  = array();
		if($attrValueId != -1 && !empty($attrValueId)){
			$requestValueIdArr = explode(',',$attrValueId);
		}
		if(!empty($attrIds)){
			$requestAttrIdArr = explode(',',$attrIds);
		}
		$attrList = array();
		$attrRet = course_api::getAttrAndValueByCateId($thirdCate);
		if(!empty($attrRet)){
			$attrTemp = array();
			$attrValueIdArr = array();
			foreach($attrRet as $attr){
				if(!empty($attr->attr_value)){
					foreach($attr->attr_value as $value){
						$attrValueIdArr[] = $value->attr_value_id;
						$attrTemp[$attr->attr_id][] = $value->attr_value_id;
					}
				}
			}
			
			if($attrValueIdArr){
				$courseRet = course_api::checkMappingCourseByAttrValueIdArr($attrValueIdArr);
				if(!empty($courseRet)){
					foreach($courseRet as $ro){
						$tempAttrValue[] = $ro->fk_attr_value; 
					}
					$hideAttrValue = array_diff($attrValueIdArr,$tempAttrValue);
					foreach($attrRet as $ao){
						if($attrTemp[$ao->attr_id] != $hideAttrValue){
							if(in_array($ao->attr_id,$requestAttrIdArr)){
								$flag = -1;
							}else{
								$flag = 0;
							}
							if(!empty($ao->attr_value)){
								foreach($ao->attr_value as $vo){
									if(!in_array($vo->attr_value_id,$hideAttrValue)){
										$attrValueTemp[] = $vo;
										if(in_array($vo->attr_value_id,$requestValueIdArr)){
											$flag = 1;
											$vo->checked = 1;
										}else{
											$vo->checked = 0;
										}
									}
								}
								if(!empty($attrValueTemp)){
									$ao->attr_value = $attrValueTemp;
									$ao->attr_vlaue_ids = $attrTemp[$ao->attr_id];
									$ao->flag = $flag;
									$attrList[] = $ao; 
								}
							}
						}	
					}
				}
			}
		}
		return $attrList;
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
	
	  public function pageAddReg(){
        if (empty($_POST['cid']) || !(int)($_POST['cid'])) return interface_func::setMsg(1000);
        if (empty($_POST['classId']) || !(int)($_POST['classId'])) return interface_func::setMsg(1000);

        $courseId = (int)($_POST['cid']);
        $classId  = (int)($_POST['classId']);

        $user = user_api::loginedUser();
        if (empty($user)) return interface_func::setMsg(1021);

        $tImg     = explode("/", $user["small"]);
        $thumbBig = $tImg[3];

        $courseInfo = course_api::getCourseone($courseId);
        if (empty($courseInfo)) return interface_func::setMsg(2017);

        if ($courseInfo->fee_type == 1) {
            return interface_func::setMsg(2017);
        }

        $data = array(
            "class_id"   => $classId,
            "course_id"  => $courseId,
            "uid"        => $user['uid'],
            "user_owner" => $courseInfo->user_id,
            "status"     => 1,
        );

        !empty($thumbBig) && $data['thumb_big'] = $thumbBig;
		
		$regInfo = course_api::checkIsRegistration($user['uid'], $courseId);
		if(!empty($regInfo)){
			exit(json_encode(['code'=>1, 'errMsg'=>'您已报名']));
		}else{
			$classInfo = course_api::getClass($classId);
			if(!empty($classInfo) && $classInfo->user_total >= $classInfo->max_user){
				return interface_func::setMsg(2034);
			}
			if (course_api::addregistration($data)) return interface_func::setMsg(0);

			return interface_func::setMsg(1);
		}
    }




}
