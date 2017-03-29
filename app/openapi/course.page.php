<?php
/**
 * https://wiki.gn100.com/doku.php?id=docs:openapi:course
 */
class openapi_course extends openapi_base{

	public function pageList($inPath){
		$page   = !empty($this->params->pn) ? (int)$this->params->pn : 1;
		$length = !empty($this->params->pl) ? (int)$this->params->pl : 20;
		$status = !empty($this->params->status) ? $this->params->status : '1,2,3';
		
		$params = [
			'q'  => ['admin_status'=>1,'status'=>$status,'org_status'=>1],
			'f'  => [
					'course_id','title','fee_type','course_type','org_subname','vv','course_tag_id',
                    'user_total','thumb_med','status','third_cate_name','start_time','first_cate','second_cate',
					'course_attr','first_cate_name','second_cate_name','class','subdomain','third_cate','attr_value_id'
				],
			'p'  => $page,
			'pl' => $length,
			'ob'=> ['vv' =>'desc'],
		];
		if(!empty($this->app->orgid) || !empty($this->params->orgId)){
			$params['q']['org_id'] = !empty($this->app->orgid) ? (int)$this->app->orgid : (int)$this->params->orgId;
		}
		if(!empty($this->params->courseId)){
            $params['q']['course_id'] = $this->params->courseId;
        }
		if(!empty($this->params->firstCateId)){
			$params['q']['first_cate'] = (int)$this->params->firstCateId;
		}
		if(!empty($this->params->secondCateId)){
			$params['q']['second_cate'] = (int)$this->params->secondCateId;
		}
		if(!empty($this->params->thirdCateId)){
			$params['q']['third_cate'] = (int)$this->params->thirdCateId;
		}
		if(!empty($this->params->attrId)){
			$params['q']['attr_value_id'] = (int)$this->params->attrId;
		}
		if(!empty($this->params->keywords)){
			$params['q']['search_field'] = trim($this->params->keywords);
		}
		if(!empty($this->params->courseType)){
			$params['q']['course_type'] = (int)$this->params->courseType;
		}
		if(!empty($this->params->feeType)){
			$params['q']['fee_type'] = (int)$this->params->feeType;
		}
		if(!empty($this->params->startTime) && !empty($this->params->endTime)){
			$params['q']['start_time'] = "{$this->params->startTime},{$this->params->endTime}";
		}
		if(!empty($this->params->tagId)){
			$params['q']['course_tag_id'] = $this->params->tagId;
		}
		if(!empty($this->params->ob)){
			unset($params['ob']['vv']);
			if(1 === (int)$this->params->ob)
				$params['ob']['course_id'] = 'desc';
			elseif(2 === (int)$this->params->ob)
				$params['ob']['course_id'] = 'asc';
			elseif(3 === (int)$this->params->ob)
				$params['ob']['vv'] = 'desc';
			elseif(4 === (int)$this->params->ob)
				$params['ob']['vv'] = 'asc';
		}
		
		$result = seek_api::seekCourse($params);
		if(empty($result->data)) self::returnData(0,array());
		//获取科目名称
		$temp = array();
		$attrValues = array();
		foreach($result->data as $value){
			if(!empty($value->course_attr)){
				foreach($value->course_attr as $val){
					if(!empty($val->attr_value)){
						foreach($val->attr_value as $v){
							if(!empty($this->params->attrId) && $v->attr_value_id == $this->params->attrId){
								$temp[$value->course_id][$v->attr_value_id] = $v->attr_value_name;
							}else{
								$temps[$value->course_id][$v->attr_value_id] = $v->attr_value_name;
							}
						}
					}
				}
			}
			if(!empty($value->class)){
				foreach($value->class as &$classs){
					unset($classs->address,$classs->region_level2,$classs->region_level1,$classs->region_level0,$classs->class_admin_id,$classs->owner_id);
				}
			}
		}

		if(!empty($this->params->attrId) && !empty($temp)){
			foreach($temp as $k=>$v){
				$attrValues[$k] = implode(" ",$v);
			}
		}elseif(!empty($temps)){
			foreach($temps as $k=>$v){
				$attrValues[$k] = implode(" ",$v);
			}
		}
		
		foreach($result->data as &$val){
			$val->thumb_med = interface_func::imgUrl($val->thumb_med);
			$val->attrName  = !empty($attrValues[$val->course_id]) ? $attrValues[$val->course_id] : '';
			$val->course_url = interface_func::httpHeader().'://'.$val->subdomain.'/course.info.show/'.$val->course_id;
			$val->class_info = $val->class;
			unset($val->course_attr);
			unset($val->subdomain);
			unset($val->class);
		}
		$totalPage = ceil($result->total / $result->pagelength);
		$data = [
			'page'      => $result->page,
			'totalSize' => $result->total,
			'totalPage' => $totalPage,
			'data'      => $result->data
		];
		return self::returnData(0,$data);
	}
	
	
	public function pagePlanList(){
		$courseId = !empty($this->params->courseId) ? intval($this->params->courseId) : 0;
		$classId  = !empty($this->params->classId) ? intval($this->params->classId) : 0;
		$planId   = !empty($this->params->planId) ? $this->params->planId : 0;
        $orgId    = !empty($this->params->orgId) ? (int)$this->params->orgId : 0;

		$queryParams = array();
		if(!empty($courseId)){
			$queryParams['course_id'] = $courseId;
		}
		if(!empty($classId)){
			$queryParams['class_id'] = $classId;
		}
		if(!empty($planId)){
			$queryParams['plan_id'] = $planId;
		}
		if(!empty($orgId)){
			$queryParams['org_id'] = $orgId;
		}
		if(empty($queryParams)) return self::returnData(-4);
        $queryParams['status'] = '1,2,3';
		$params = [
			'q' => $queryParams,
			'f' => [
				'plan_id','course_id','section_id','section_name','section_desc','class_id',
				'class_name','start_time','try','teacher_name','teacher_real_name','subdomain'
			],
			'ob'=> ['section_order_no' =>'asc']
		];

		$seekPlan = seek_api::seekplan($params);
		if(empty($seekPlan->data)) self::returnData(0,array());;
		
		foreach($seekPlan->data as $val){
			$data[] = [
				'plan_id'      => $val->plan_id,
				'course_id'    => $val->course_id,
				'section_id'   => $val->section_id,
				'section_name' => $val->section_name,
				'section_desc' => $val->section_desc,
				'class_id'     => $val->class_id,
				'class_name'   => $val->class_name,
				'start_time'   => $val->start_time,
				'is_try'       => $val->try,
				'teacher_real_name'=>$val->teacher_real_name,
				'plan_url'     => interface_func::httpHeader().'://'.$val->subdomain.'/course.plan.play/'.$val->plan_id
			];
		}
		return self::returnData(0,$data);
	}
 	//取三级分类的属性值
 	public function pageGetThirdCateAttr(){
 		$cateId = !empty($this->params->cateId) ? intval($this->params->cateId) : 0;
 		if(empty($cateId)) return self::returnData(-4);
 		$cateAttr = course_api::getAttrAndValueByCateId($cateId);
 		$data = array();
 		if($cateAttr){
 			foreach($cateAttr as $cate){
 				if(!empty($cate->attr_value)){
 					foreach($cate->attr_value as $attr_value){
 						$data[]=array(
 							'id' => $attr_value->attr_value_id,
 							'name' => $attr_value->value_name,
 						);
 					}
 				}
 			}
 		}
 		return self::returnData(0,$data);
 	}
 }
 ?>
