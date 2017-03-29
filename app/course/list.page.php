<?php
class course_list extends STpl{
	private $domain;
	private $orgOwner;
	private $orgId;
	function __construct(){
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
		$this->assign('domain', $this->domain);

        $org=user_organization::subdomain();
        if(!empty($org)){
            $this->orgOwner = $org->userId; //机构所有者id 以后会根据域名而列取 
        }else{
            header('Location: https://www.'.$this->domain);
        }
		$this->orgInfo = user_organization::getOrgByOwner($this->orgOwner);
		if(!empty($this->orgInfo->oid)){
			$this->orgId = $this->orgInfo->oid;
		}else{
			header('Location: https://www.'.$this->domain);
		}
	}
	function pageEntry($inPath){
		utility_cache::pageCache(600);
		
		$page = !empty($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$size = 20;
		$sort = !empty($_REQUEST['sort'])?$_REQUEST['sort']:'course_id:desc';
		$feeType     = isset($_REQUEST['fee_type'])?(int)$_REQUEST['fee_type']:'-1';
		$courseType  = !empty($_REQUEST['course_type'])?(int)$_REQUEST['course_type']:'';
		$startTime   = !empty($_REQUEST['start_time'])?$_REQUEST['start_time']:'';
		//$searchField = isset($_REQUEST['search_field'])?$_REQUEST['search_field']:'';
		$searchField = isset($_REQUEST['search_field'])?utility_tool::unescape($_REQUEST['search_field']):'';
		$firstCate   = isset($_REQUEST['fc'])?(int)$_REQUEST['fc']:'-1';
		$secondCate  = isset($_REQUEST['sc'])?(int)$_REQUEST['sc']:'-1';
		$thirdCate   = isset($_REQUEST['tc'])?(int)$_REQUEST['tc']:'-1';
		$attrValue   = isset($_REQUEST['vid'])?$_REQUEST['vid']:'-1';
		$memberSet   = isset($_REQUEST['ms'])?(int)$_REQUEST['ms']:'-1';
		$CourseTagId   = isset($_REQUEST['tg'])?(int)$_REQUEST['tg']:'-1';
		$cid =       isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
		//机构信息
        $orgInfo = user_organization::getOrgByOwner($this->orgOwner);
		//标签
		$selected = $this->getOrgTagInfo();
		$selectedTagName = '';
		
		if(!empty($selected->result->data)){
			foreach($selected->result->data as $k=>$v){
				$tgId[] = !empty($v->pk_tag) ? $v->pk_tag : '';
				if($CourseTagId == $v->pk_tag){
					$selectedTagName = $v->name;
				}
			}
				//$CourseTagId = implode(",",$tgId);
		}
		$this->assign("selectedTagName",$selectedTagName);
		$attrValueId = '';
		if($attrValue != -1){
			$attrArr = explode(',',$attrValue);
			foreach($attrArr as $attr){
				$temp = explode('|',$attr);
				if($temp[1] != -1){
					$attrValueIdArr[] = $temp[1];
				}
			}
			if(!empty($attrValueIdArr)){
				$attrValueId = implode(',',$attrValueIdArr);
			}
		}
		
		$fields = [
			"tags","course_id","title","create_time","desc","start_time","thumb_big", "thumb_med","thumb_sma","course_type","user_id","public_type", "fee_type","price","market_price","max_user","min_user","user_total","status","admin_status", "system_status","class_id","end_time","create_time","last_updated","class", "try","top","vv","first_cate","second_cate","third_cate","attr_value_id","second_cate_name", "third_cate_name","course_attr",
			'resell_org_id','is_promote',
			"member_set_id","course_tag_id"
		];	
			
        $query = [
			'first_cate'     => $firstCate,
			'second_cate'    => $secondCate,
			'third_cate'     => $thirdCate,
			'attr_value_id'  => $attrValueId,
			'course_type'    => $courseType,
			'fee_type'       => $feeType,
			'have_plan_date' => $startTime,
			'admin_status'   => 1,
			'search_field'   => $searchField,
			//'user_id'        => $this->orgOwner,
			'member_set_id'  => $memberSet,
			'course_tag_id'  => $CourseTagId,
			'course_id'		 => $cid,
			'expression'   	 => "@resell_org_id =".$this->orgInfo->oid." | @org_id=".$this->orgInfo->oid,
        ];
        foreach($query as $k=>$v){
            if($v=='' || $v==-1){
                unset($query[$k]);
            }
        }
        $sortArr = explode(':',$sort);
        if($sortArr[0] == 'price'){
            $obArr   = array('fee_type'=>$sortArr[1],$sortArr[0]=>$sortArr[1]);
        }elseif($sortArr[0] == 'comment'){
			 $obArr  = array('avg_score'=>"desc");
		}else{
            $obArr   = array($sortArr[0]=>$sortArr[1]);
        }
		$params = [
			"f" => $fields,
			"q" => $query,
			"p" => $page,
			"pl"=> $size,
			"ob"=> $obArr
		];
        $resCourse = seek_api::seekcourse($params);
		$courseList  = array();
		$courseIdArr = array();
		$plan = array();
		$attrValues = array();
		$temp = array();
		//全部课程---获取分销课
		$condition = array("status"=>1,"fk_org_resell"=>$this->orgInfo->oid);
		$salesInfo = course_resell_api::getSalesCourse($page=1,$length=0,$condition);
		$resell = array();
		if(!empty($salesInfo)){
			foreach($salesInfo as $k=>$v){
				$resell[$v->fk_course] =$v;
			}
		}
		
		if(!empty($resCourse->data)){
			$courseList = $resCourse->data;
			//获取科目名称
			foreach($courseList as $value){
				$value->price 		 = !empty($value->price) ? $value->price/100 : 0;
				$value->resell_price = isset($resell[$value->course_id]->price_resell) ? $resell[$value->course_id]->price_resell/100 : $value->price;
				if(!empty($value->resell_org_id[0])&& ($value->resell_org_id[0] == $this->orgInfo->oid) ){
					$value->is_org_self = 0;
					$value->url         = $value->is_promote ? "/course.info.show/".$value->course_id."/".$this->orgInfo->oid : "/course.info.show/".$value->course_id;
				}else{
					$value->is_org_self = 1;
					$value->url         = "/course.info.show/".$value->course_id;
				}
				//$value->sectionNum = count($value->section);
				if(!empty($value->course_attr)){
					foreach($value->course_attr as $val){
						if(!empty($val->attr_value)){
							foreach($val->attr_value as $v){
								$temp[$value->course_id][$v->attr_value_id] = $v->attr_value_name;
							}
						}
					}
				}
			}
			
			if(!empty($temp)){
				foreach($temp as $k=>$v){
					$attrValues[$k] = implode(",",$v);
				}
			}
			$courseIdArr = array_reduce($courseList, create_function('$v,$w', '$v[$w->course_id]=$w->course_id;return $v;'));
		}
		
		//获取排课信息
		if(!empty($courseIdArr)){
			$planParam = [
				'f' => [
						'course_id','plan_id','class_id','class_name','section_name','admin_name',
						'admin_real_name','course_type','start_time','max_user','user_total','try','status'
					],
				'q' => ['course_id'=>implode(',',$courseIdArr),'status'=>'1,2,3'],
				'ob'=> ['start_time'=>'asc'],
				'p' =>1,
                'pl'=> 1000
			];
            $resPlan = seek_api::seekPlan($planParam);
			
			
			$plan1=array();
			$planinfo = array();
			if(!empty($resPlan->data)){
				foreach($resPlan->data as $rp){
					$plan1[$rp->course_id][$rp->class_id][$rp->status][] = $rp;
					$planinfo[$rp->course_id][$rp->class_id][] = $rp;
				}
			}
			
			$plan = org_api::getOrgCourseProjectInfo($courseList,$plan1,$planinfo);
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
			$attrValueList = $this->getAttrValueList($thirdCate,$attrValueId);
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
	
		$total=0;
        $count=0;
        if(!empty($resCourse->total)){
            $total=ceil($resCourse->total/$resCourse->pagelength);
            $count=$resCourse->total;
        }
		
		$pm = [
			'page'=>$page,
			'size'=>$size,
			'total'=>$total,
			'count'=>$count,
			'firstCate'  => $firstCate,
			'secondCate' => $secondCate,
			'thirdCate'  => $thirdCate,
			'fee_type'   => $feeType,
			'course_type'=> $courseType,
			'sort'       => $sort,
			'sort_name'  => $sortArr[0],
			'start_time' => $startTime,
			'memberSet'  => $memberSet,
		];

		$path='/course.list';
		if($startTime){
			$path_page = utility_tool::getUrl($path,'','',$startTime);
		}else{
			$path_page = utility_tool::getUrl($path);
		}
		
		$checkMember = '';
		$memberSetList = user_organization::getMemberSetList($this->orgId,1);
		$memberCount = count($memberSetList);
		if($memberSet != -1){
			foreach($memberSetList as $mk=>$mo){
				if($mo->pk_member_set == $memberSet){
					$checkMember = $mo->title;
					if($memberCount > 1){
						unset($memberSetList[$mk]);
					}
				}
			}
		}
		$memberShow = 1;
		if($memberCount <= 1 && $memberSet != -1){
			$memberShow = 0;
		}
		$this->assign('memberShow',$memberShow);
		$this->assign('checkMember',$checkMember);
		$this->assign('memberSetList',$memberSetList);
		$this->assign("selected",!empty($selected->result->data) ? $selected->result->data : '');
		$this->assign('firstCateInfo',$firstCateInfo);
		$this->assign('secondCateInfo',$secondCateInfo);
		$this->assign('firstCateList',$firstCateList);
		$this->assign('secondCateList',$secondCateList);
		$this->assign('thirdCateList',$thirdCateList);
		$this->assign("attrValueList",$attrValueList);
		$this->assign("courseList",$courseList);
		$this->assign("org_hot_type",$orgInfo);
		$this->assign("searchField",$searchField);
		$this->assign("pm",$pm);
		$this->assign("path",$path);
		$this->assign("plan",$plan);
		$this->assign("path_page",$path_page);
		$this->assign("attrValues",$attrValues);
		$this->assign('showLevel',$showLevel);
		$this->assign('tg',$CourseTagId);
		if(!empty($this->orgInfo)&&$this->orgInfo->is_pro==1){
			return $this->render("course/course.list.html");
		}
		$this->render("course/course.list.html");
	}
	//机构已有标签
	public function getOrgTagInfo(){
		$data = ["oid"=>(int)$this->orgInfo->oid];
		$orgTagInfo = org_api::getOrgTagInfo($data);
		return $orgTagInfo;
	}
	public function getFirstCateList(){	
		$showFirstCate = array();
		$firstCateList = course_api::getCateByLevel(1);

		if(!empty($firstCateList)){
			foreach($firstCateList as $fcate){
				$fcateIdArr[] = $fcate->pk_cate;
			}
			$firstRet = course_api::checkCourseByFirstCateArr($fcateIdArr,$this->orgOwner);	
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
			$courseRet = course_api::checkCourseBySecondCateArr($cateIdArr,$this->orgOwner);
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
			$courseRet = course_api::checkCourseByThirdCateArr($cateIdArr,$this->orgOwner);

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
	
	public function getAttrValueList($thirdCate,$attrValueId){	
		$requestValueIdArr = array();
		if($attrValueId != -1){
			$requestValueIdArr = explode(',',$attrValueId);
		}
		$attrList = array();
		$attrRet = course_api::getAttrAndValueByCateId($thirdCate,$this->orgOwner);
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
				$courseRet = course_api::checkMappingCourseByAttrValueIdArr($attrValueIdArr,$this->orgOwner);
				if(!empty($courseRet)){
					foreach($courseRet as $ro){
						$tempAttrValue[] = $ro->fk_attr_value; 
					}
					$hideAttrValue = array_diff($attrValueIdArr,$tempAttrValue);
					foreach($attrRet as $ao){
						if($attrTemp[$ao->attr_id] != $hideAttrValue){
							$flag = 0;
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

	//全部课程---H5适配接口
	function pageCourseList($inPath){
		$result = new stdClass;
		$page = !empty($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$size = 20;
		$sort = !empty($_REQUEST['sort'])?$_REQUEST['sort']:'course_id:desc';
		$feeType     = isset($_REQUEST['fee_type'])?(int)$_REQUEST['fee_type']:'-1';
		$courseType  = !empty($_REQUEST['course_type'])?(int)$_REQUEST['course_type']:'';
		$startTime   = !empty($_REQUEST['start_time'])?$_REQUEST['start_time']:'';
		$searchField = isset($_REQUEST['search_field'])?utility_tool::unescape($_REQUEST['search_field']):'';
		$firstCate   = isset($_REQUEST['fc'])?(int)$_REQUEST['fc']:'-1';
		$secondCate  = isset($_REQUEST['sc'])?(int)$_REQUEST['sc']:'-1';
		$thirdCate   = isset($_REQUEST['tc'])?(int)$_REQUEST['tc']:'-1';
		$attrValue   = isset($_REQUEST['vid'])?$_REQUEST['vid']:'-1';
		$memberSet   = isset($_REQUEST['ms'])?(int)$_REQUEST['ms']:'-1';
		$CourseTagId   = isset($_REQUEST['tg'])?(int)$_REQUEST['tg']:'-1';
		$cid =       isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
		//机构信息
		$orgInfo = user_organization::getOrgByOwner($this->orgOwner);
		//标签
		$selected = $this->getOrgTagInfo();
		$selectedTagName = '';
		if(!empty($selected->result->data)){
			foreach($selected->result->data as $k=>$v){
				$tgId[] = !empty($v->pk_tag) ? $v->pk_tag : '';
				if($CourseTagId == $v->pk_tag){
					$selectedTagName = $v->name;
				}
			}
		}
		$result->selectedTagName = $selectedTagName;
		$attrValueId = '';
		if($attrValue != -1){
			$attrArr = explode(',',$attrValue);
			foreach($attrArr as $attr){
				$temp = explode('|',$attr);
				if(!empty($temp[0]) && $temp[0] != -1){
					$attrValueIdArr[] = $temp[0];
				}
				
			}
			if(!empty($attrValueIdArr)){
				$attrValueId = implode(',',$attrValueIdArr);
			}
		}
		if($attrValue == 'all'){
			$attrValueId = '';
		}
		$fields = [
			"tags","course_id","title","create_time","desc","start_time","thumb_big", "thumb_med","thumb_sma","course_type","user_id","public_type", "fee_type","price","market_price","max_user","min_user","user_total","status","admin_status", "system_status","class_id","end_time","create_time","last_updated","class", "try","top","vv","first_cate","second_cate","third_cate","attr_value_id","second_cate_name", "third_cate_name","course_attr",
			"member_set_id","course_tag_id","resell_org_id","org_id","is_promote"
		];
		$query = [];
		
		$query = [
			'first_cate'     => $firstCate,
			'second_cate'    => $secondCate,
			'third_cate'     => $thirdCate,
			'attr_value_id'  => $attrValueId,
			'course_type'    => $courseType,
			'fee_type'       => $feeType,
			'have_plan_date' => $startTime,
			'admin_status'   => 1,
			//'search_field'   => $searchField,
			//'expression'     => "@resell_org_id =".$this->orgInfo->oid." | @org_id=".$this->orgInfo->oid,
			'member_set_id'  => $memberSet,
			'course_tag_id'  => $CourseTagId,
			'course_id'		 => $cid,
		];
		if(!empty($searchField)){
			$query['title'] = $searchField;
		}else{
			$query['expression']   = "@resell_org_id =".$this->orgInfo->oid." | @org_id=".$this->orgInfo->oid;
		}
		foreach($query as $k=>$v){
			if($v=='' || $v==-1){
				unset($query[$k]);
			}
		}
		$sortArr = explode(':',$sort);
		if($sortArr[0] == 'price'){
			$obArr   = array('fee_type'=>$sortArr[1],$sortArr[0]=>$sortArr[1]);
		}elseif($sortArr[0] == 'comment'){
			$obArr  = array('avg_score'=>"desc");
		}else{
			$obArr   = array($sortArr[0]=>$sortArr[1]);
		}
		$params = [
			"f" => $fields,
			"q" => $query,
			"p" => $page,
			"pl"=> $size,
			"ob"=> $obArr
		];
		$resCourse = seek_api::seekcourse($params);
		$courseList  = array();
		$courseIdArr = array();
		$plan = array();
		$attrValues = array();
		//全部课程---获取分销课
		$condition = array("status"=>1,"fk_org_resell"=>$this->orgInfo->oid);
		$salesInfo = course_resell_api::getSalesCourse($page=1,$length=0,$condition);
		$resell = array();
		if(!empty($salesInfo)){
			foreach($salesInfo as $k=>$v){
				$resell[$v->fk_course] =$v;
			}
		}
		if(!empty($resCourse->data)){
			$courseList = $resCourse->data;
			//获取科目名称
			foreach($courseList as $value){
				//$value->sectionNum = count($value->section);
				$subjectAttrNameStr = '';
				if(!empty($value->course_attr)){
					foreach($value->course_attr as $val){
						$temp = array();
						if(!empty($val->attr_value)){
							foreach($val->attr_value as $v){
								$temp[] = $v->attr_value_name;
							}
						}
						$subjectAttrNameStr = implode(",",$temp);
					}
				}
				$value->price 		 = !empty($value->price) ? $value->price/100 : 0;
				$value->resell_price = isset($resell[$value->course_id]->price_resell) ? $resell[$value->course_id]->price_resell/100 : $value->price;
				if(!empty($value->resell_org_id[0])&& ($value->resell_org_id[0] == $this->orgInfo->oid) ){
					$value->is_org_self = 0;
					$value->url         = $value->is_promote ? "/course.info.show/".$value->course_id."/".$this->orgInfo->oid : "/course.info.show/".$value->course_id;
				}else{
					$value->is_org_self = 1;
					$value->url         = "/course.info.show/".$value->course_id;
				}
				
				$value->course_attr = $subjectAttrNameStr;
				$value->thumb_big 	= !empty($value->thumb_big) ? utility_cdn::file($value->thumb_big) : '';
				$value->thumb_med 	= !empty($value->thumb_med) ? utility_cdn::file($value->thumb_med) : '';
				$value->thumb_sma = !empty($value->thumb_sma) ? utility_cdn::file($value->thumb_sma) : '';
			}
			$attrValues = array();
			/*if(!empty($temp)){
				foreach($temp as $k=>$v){
					$attrValues[$k] = implode(",",$v);
				}
			}*/
			$courseIdArr = array_reduce($courseList, create_function('$v,$w', '$v[$w->course_id]=$w->course_id;return $v;'));
		}

		//获取排课信息
		if(!empty($courseIdArr)){
			$planParam = [
				'f' => [
					'course_id','plan_id','class_id','class_name','section_name','admin_name',
					'admin_real_name','course_type','start_time','max_user','user_total','try','status'
				],
				'q' => ['course_id'=>implode(',',$courseIdArr),'status'=>'1,2,3'],
				'ob'=> ['start_time'=>'asc'],
				'p' =>1,
				'pl'=> 1000
			];
			$resPlan = seek_api::seekPlan($planParam);


			$plan1=array();
			$planinfo = array();
			if(!empty($resPlan->data)){
				foreach($resPlan->data as $rp){
					$plan1[$rp->course_id][$rp->class_id][$rp->status][] = $rp;
					$planinfo[$rp->course_id][$rp->class_id][] = $rp;
				}
			}

			$plan = org_api::getOrgCourseProjectInfo($courseList,$plan1,$planinfo);
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
			$attrValueList = $this->getAttrValueList($thirdCate,$attrValueId);
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

		$total=0;
		$count=0;
		if(!empty($resCourse->total)){
			$total=ceil($resCourse->total/$resCourse->pagelength);
			$count=$resCourse->total;
		}

		$pm = [
			'page'=>$page,
			'size'=>$size,
			'total'=>$total,
			'count'=>$count,
			'firstCate'  => $firstCate,
			'secondCate' => $secondCate,
			'thirdCate'  => $thirdCate,
			'fee_type'   => $feeType,
			'course_type'=> $courseType,
			'sort'       => $sort,
			'sort_name'  => $sortArr[0],
			'start_time' => $startTime,
			'memberSet'  => $memberSet,
		];

		$path='/course.list';
		if($startTime){
			$path_page = utility_tool::getUrl($path,'','',$startTime);
		}else{
			$path_page = utility_tool::getUrl($path);
		}

		$checkMember = '';
		$memberSetList = user_organization::getMemberSetList($this->orgId,1);
		$memberCount = count($memberSetList);
		if($memberSet != -1){
			foreach($memberSetList as $mk=>$mo){
				if($mo->pk_member_set == $memberSet){
					$checkMember = $mo->title;
					/*if($memberCount > 1){
						unset($memberSetList[$mk]);
					}*/
				}
			}
		}
		$memberShow = 1;
		if($memberCount <= 1 && $memberSet != -1){
			$memberShow = 0;
		}
		$result->memberShow 		= $memberShow;
		$result->memberSetList 		= $memberSetList;
		$result->checkMember 		= $checkMember;
		$result->memberSetList 		= $memberSetList;
		$result->selected 			= !empty($selected->result->data) ? $selected->result->data : '';
		$result->firstCateInfo 		= $firstCateInfo;
		$result->secondCateInfo 	= $secondCateInfo;
		$result->firstCateList 		= $firstCateList;
		$result->secondCateList 	= $secondCateList;
		$result->thirdCateList 		= $thirdCateList;
		$result->attrValueList 		= $attrValueList;
		$result->courseList 		= $courseList;
		$result->org_hot_type 		= $orgInfo;
		$result->searchField 		= $searchField;
		$result->pm 				= $pm;
		$result->path 				= $path;
		$result->plan 				= $plan;
		$result->path_page 			= $path_page;
		$result->attrValues 		= $attrValues;
		$result->showLevel 			= $showLevel;
		$std = new stdClass;
		if(!empty($result)){
			$std->code 				= 200;
			$std->data   			= $result;
		}else{
			$std->info->code 		= -200;
			$std->data				= "";
		}
		return $std;
	}
}
