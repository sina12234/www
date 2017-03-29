<?php
class course_promote extends STpl{
	private $domain;
	private $platform;
	private $orgOwner;
	private $orgId;
	function __construct(){
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf");
		$this->domain = $domain_conf->domain->domain;
		$this->assign('domain', $this->domain);
                $this->platform = $domain_conf->platform->platform;
                $this->assign('platform', $this->platform);

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
			header('Location: https://'.$org->subdomain);
		}
	}
        /* 我推广的课程 */
	function pageList($inPath){
		$page = !empty($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$size = !empty($_REQUEST['size'])?(int)$_REQUEST['size']:20;
                $searchField = isset($_REQUEST['search_field'])?$_REQUEST['search_field']:'';
                $courseListObj = $this->getPromoteCourseList();

                $total = $courseListObj['total'];
                $count = $courseListObj['count'];
                $courseList = empty($courseListObj['data']) ? '' : $courseListObj['data'];
                $attrValues = $courseListObj['attrs'];
                $course_ids = $courseListObj['course_ids'];
				$courseFeeTypeArr = array();
				$promoteResellCount = course_resell_api::getPromoteCourseResellCount(explode(',',$course_ids));
				$promoteResellCountArr = array();
				if(!empty($promoteResellCount)){
					foreach($promoteResellCount as $prc){
						$promoteResellCountArr[$prc->fk_course]=$prc->count;

					}
				}
                // 从中间层获取课程数据
                $seek_course_info = course_resell_api::getSeekCourseInfo($course_ids);
			if(!empty($seek_course_info)){
				foreach($seek_course_info as $fee){
					$courseFeeTypeArr[$fee->course_id] = $fee->fee_type;
				}
			}
                // 获取章节数，分类 ，属性 信息
                $attrValues = course_resell_api::getCourseAttr($seek_course_info);

                $maxCount = 50;
                $promoteCount = $maxCount-$this->coursePromoteCount();
                $pm = [
			'page' =>$page,
			'size' =>$size,
			'total' =>$total,
			'max_count' =>$maxCount,
			'count' =>$count,
			'search_field'=> $searchField,
                        'promoteCount' =>$promoteCount,
		];

		$path = '/course.promote.list';
                $path_page = utility_tool::getUrl($path);

		$this->assign("courseList",$courseList);
		$this->assign("pm",$pm);
		$this->assign("path",$path);
                $this->assign("path_page",$path_page);
		$this->assign("attrValues",$attrValues);
		$this->assign("courseFeeTypeArr",$courseFeeTypeArr);
		$this->assign("promoteResellCountArr",$promoteResellCountArr);
		$this->render("course/fenxiao/course.promote.list.html");
	}

        public function getPromoteCourseList(){
                $courseList = NULL;
		$sort = !empty($_REQUEST['sort'])?$_REQUEST['sort']:'t_course_promote.last_updated desc';
		$page = !empty($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$size = !empty($_REQUEST['size'])?(int)$_REQUEST['size']:20;
                $searchField = isset($_REQUEST['search_field'])?$_REQUEST['search_field']:'';
                $userID      = $this->orgOwner;
                $params      = array('sort'=>$sort ,'uid'=>$userID,'search'=>$searchField);
                $resCourse   = course_resell_api::getPromoteCourselist($page, $size, $params);
                $course_ids  = '';
                $status_arr = course_resell_api::$resell_status_arr;
                $course_type_arr = ['1'=>'直播课' , '2'=>'录播课' , '3'=>'线下课'];

                if(!empty($resCourse->data)){
                    $courseList = $resCourse->data->items;
                    //获取科目名称
                    foreach($courseList as $key => $value){
                        $course_ids .= $value->course_id.',';
                        $courseList[$key]->status_info = isset($status_arr[$value->status_code]) ? $status_arr[$value->status_code]['promote_info'] : '';
                        $courseList[$key]->status_tip  = isset($status_arr[$value->status_code]) ? $status_arr[$value->status_code]['tip'] : '';
                        $courseList[$key]->course_type_str  = isset($course_type_arr[$value->course_type]) ? $course_type_arr[$value->course_type] : '' ;
                    }
                    $course_ids .= substr($course_ids, 0,-1);

                    $count = $resCourse->data->totalSize;   // 总条数
                    $total = ceil($count/$size);  // 总页数
                } else {
                    $count = $total = 0;
                }
                return array('total'=>$total,'count'=>$count,'data'=>$courseList,'course_ids'=>$course_ids,'attrs'=>'');
        }
        /* 云课推广中心 */
	function pageCenter($inPath){
		utility_cache::pageCache(600);

		$page = !empty($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$size = !empty($_REQUEST['size'])?(int)$_REQUEST['size']:20;
		$sort = !empty($_REQUEST['sort'])?$_REQUEST['sort']:'last_updated:desc';
		$courseType  = !empty($_REQUEST['course_type'])?$_REQUEST['course_type']:'';
		$firstCate   = isset($_REQUEST['fc'])?$_REQUEST['fc']:'-1';
		$secondCate  = isset($_REQUEST['sc'])?$_REQUEST['sc']:'-1';
		$thirdCate   = isset($_REQUEST['tc'])?$_REQUEST['tc']:'-1';
		$attrValue   = isset($_REQUEST['vid'])?$_REQUEST['vid']:'-1';
		$searchField = isset($_REQUEST['search_field'])?$_REQUEST['search_field']:'';
		$searchType = isset($_REQUEST['search_type'])?$_REQUEST['search_type']:1;
		$minPrice    = empty($_REQUEST['minprice'])? '' : $_REQUEST['minprice'];
		$maxPrice    = empty($_REQUEST['maxprice'])? '' : $_REQUEST['maxprice'];

                $courseListObj = $this->getCenterCourseList();

                $total = $courseListObj['total'];
                $count = $courseListObj['count'];
                $courseList = $courseListObj['data'];
                $attrValues = $courseListObj['attrs'];
                $course_ids = $courseListObj['course_ids'];
                $userCourseRelations =  $this->getUserCourseRelation($course_ids);

                $attrValueArr = $this->fillAttrValue($attrValue);
                $cateList = $this->getCateList($firstCate , $secondCate , $thirdCate , $attrValueArr['attrIds'],$attrValueArr['attrValueId'],0,$course_ids);

                $sortArr = explode(':',$sort);

		$pm = [
			'page'=>$page,
			'size'=>$size,
			'total'=>$total,
			'count'=>$count,
			'firstCate'  => $firstCate,
			'secondCate' => $secondCate,
			'thirdCate'  => $thirdCate,
			'course_type'=> $courseType,
			'search_field'=> $searchField,
			'minprice'   => $minPrice,
			'maxprice'   => $maxPrice,
			'sort'       => $sort,
			'sort_name'  => $sortArr[0],
		];

		$path = '/course.promote.center';
                $path_page = utility_tool::getUrl($path);

		$this->assign('showLevel',$cateList['showLevel']);
                $this->assign('firstCateList',$cateList['firstCateList']);
		$this->assign('secondCateList',$cateList['secondCateList']);
		$this->assign('thirdCateList',$cateList['thirdCateList']);
		$this->assign("attrValueList",$cateList['attrValueList']);
		$this->assign('firstCateInfo',$cateList['firstCateInfo']);
		$this->assign('secondCateInfo',$cateList['secondCateInfo']);
                $this->assign("userCourseRelations",$userCourseRelations);
		$this->assign("courseList",$courseList);
		$this->assign("pm",$pm);
		$this->assign("path",$path);
                $this->assign("path_page",$path_page);
		$this->assign("attrValues",$attrValues);
		$this->assign('showLevel',$cateList['showLevel']);               ;
		$this->assign("orgInfo",$this->orgInfo);
		$this->assign("searchType",$searchType);
		$this->render("course/fenxiao/course.promote.center.html");
	}
        public function getCenterCourseList(){
            $page = !empty($_REQUEST['page'])?(int)$_REQUEST['page']:1;
            $size = !empty($_REQUEST['size'])?(int)$_REQUEST['size']:20;
            $sort = !empty($_REQUEST['sort'])?$_REQUEST['sort']:'last_updated:desc';
            $courseType  = !empty($_REQUEST['course_type'])?$_REQUEST['course_type']:'1,2';
            $firstCate   = isset($_REQUEST['fc'])?$_REQUEST['fc']:'-1';
            $secondCate  = isset($_REQUEST['sc'])?$_REQUEST['sc']:'-1';
            $thirdCate   = isset($_REQUEST['tc'])?$_REQUEST['tc']:'-1';
            $attrValue   = isset($_REQUEST['vid'])?$_REQUEST['vid']:'-1';
            $searchField = isset($_REQUEST['search_field'])?$_REQUEST['search_field']:'';
			$orgName = isset($_REQUEST['search_field'])?$_REQUEST['search_field']:'';
            $minPrice    = empty($_REQUEST['minprice'])? 0 : $_REQUEST['minprice'];
            $maxPprice   = empty($_REQUEST['maxprice'])? 10000000 : $_REQUEST['maxprice'];
			$searchType = isset($_REQUEST['search_type'])?$_REQUEST['search_type']:1;

            $fields = ["course_id","title","thumb_sma","price","market_price","first_cate","second_cate","third_cate",
				//"section_count","section",
				"second_cate_name","third_cate_name","remain_user","status","course_type","desc"
                    ,"course_attr","org_subname","subdomain","user_id","price_promote","is_promote","fee_type"
            ];

            $query = [
                    'first_cate'     => $firstCate,
                    'second_cate'    => $secondCate,
                    'third_cate'     => $thirdCate,
                    'course_type'    => $courseType,
                    'price_promote'   => ($minPrice*100).','.($maxPprice*100),
                    'admin_status'   => 1,
                    'is_promote'     => 1,
                    'promote_status' => 1,
            ];
			if($searchType==1){
				$query['search_field']=$searchField;
			}elseif($searchType==2){
				$query['org_subname']=$searchField;
			}
            foreach($query as $k=>$v){
                if($v=='' || $v==-1){
                    unset($query[$k]);
                }
            }
            $sortArr = explode(':',$sort);
            if($sortArr[0] == 'price'){
                $obArr   = array('fee_type'=>$sortArr[1],$sortArr[0]=>$sortArr[1]);
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
            if(!empty($resCourse->data)){
                    $courseList = $resCourse->data;
            }
            $count = 0;
            if(!empty($resCourse->total)){
                    $count=$resCourse->total;       // 总条数
            }
            $total = ceil($count/$resCourse->pagelength); // 总页数
            $course_type_arr = ['1'=>'直播课' , '2'=>'录播课' , '3'=>'线下课'];
            $attrValues = $userCourseRelations = array();
            $temp = array();
            $course_ids = '';
            if(!empty($resCourse->data)){
                    $courseList = $resCourse->data;
                    //获取科目名称
                    foreach($courseList as $key => $value){
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
                            $courseList[$key]->subdomain  = user_organization::course_domain($value->subdomain);
                            $courseList[$key]->course_type_str = isset($course_type_arr[$value->course_type]) ? $course_type_arr[$value->course_type] : '';
                            $course_ids .= $value->course_id.',';
                    }
                    $course_ids .= substr($course_ids, 0,-1);

                    if(!empty($temp)){
                            foreach($temp as $k=>$v){
                                    $attrValues[$k] = implode(",",$v);
                            }
                    }
            }

            return array('total'=>$total,'count'=>$count,'data'=>$courseList,'course_ids'=>$course_ids,'attrs'=>$attrValues);
        }
        public function getUserCourseRelation($course_ids){
                // 当前用户是否引入推广课程
                $params = array('uid'=>$this->orgId ,'course_id'=> $course_ids);
                $course_id_arr = explode(',', $course_ids);
                $temp_r = course_resell_api::getUserCourseRelation($params);
                $userCourseRelations = array();

                foreach($course_id_arr as $val){
                    $userCourseRelations[$val] = 0; // 0 ： 未引入
                }
                if($temp_r->data){
                    foreach($temp_r->data->items as $val){
                        $userCourseRelations[$val->fk_course] = $val->status;
                    }
                }
                return $userCourseRelations;
        }
        public function fillAttrValue($attrValue){
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
                return array('attrIds'=>$attrIds,'attrValueId'=>$attrValueId);
        }

        public function getCateList($firstCate , $secondCate , $thirdCate, $attrIds , $attrValueId , $ownerId=0 , $courseIds = '' ){
		//获取分类以及属性
		$firstCateList  = array();
		$secondCateList = array();
		$thirdCateList  = array();
		$attrValueList  = array();
		$showLevel = 0;
		if($firstCate == -1){
			$firstCateList = $this->getFirstCateList($ownerId , $courseIds);
			$showLevel = 1;
		}elseif($firstCate != -1 && $secondCate == -1){
			$secondCateList = $this->getSecondCateList($firstCate , $ownerId , $courseIds);
			$showLevel = 2;
		}elseif($firstCate != -1 && $secondCate != -1 && $thirdCate == -1){
			$thirdCateList = $this->getThirdCateList($secondCate , $ownerId , $courseIds);
			$showLevel = 3;
		}elseif($firstCate != -1 && $secondCate != -1 && $thirdCate != -1){
			$thirdCateList = $this->getThirdCateList($secondCate , $ownerId , $courseIds);
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
                return $CateList = array(
                    'showLevel'     =>  $showLevel,
                    'firstCateList' =>  $firstCateList,
                    'secondCateList'=>  $secondCateList,
                    'thirdCateList' =>  $thirdCateList,
                    'attrValueList' =>  $attrValueList,
                    'firstCateInfo' =>  $firstCateInfo,
                    'secondCateInfo' =>  $secondCateInfo
                 );
        }

        /* 分销规则页面 */
	function pageRule($inPath){
		$this->render("course/fenxiao/course.promote.rule.html");
	}

	/* 引入课程的机构列表 */
	function pageOrgList($inPath){
		$this->render("course/fenxiao/promote.org.list.html");
	}

        /* 分销导航模块 */
	function pageNavModule($inPath){
             $nav = empty($inPath[3]) ? '' : $inPath[3];
             $this->assign('nav',$nav);
             $this->render("course/fenxiao/course.promote.nav.module.html");
	}

        /* 分销条件筛选模块 */
	function pageFilterModule($inPath){
             $this->render("course/fenxiao/course.promote.filter.module.html");
	}

	//机构已有标签
	public function getOrgTagInfo(){
		$data = ["oid"=>(int)$this->orgInfo->oid];
		$orgTagInfo = org_api::getOrgTagInfo($data);
		return $orgTagInfo;
	}

	public function getFirstCateList($ownerId=0 , $courseIds=''){
		$showFirstCate = array();
		$firstCateList = course_api::getCateByLevel(1);

		if(!empty($firstCateList)){
			foreach($firstCateList as $fcate){
				$fcateIdArr[] = $fcate->pk_cate;
			}
			$firstRet = course_api::checkCourseByFirstCateArr($fcateIdArr , $ownerId , $courseIds);
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

	public function getSecondCateList($firstCate , $ownerId=0 , $courseIds=''){
		$showCateList = array();
		$cateRet = course_api::getNodeCate($firstCate);
		if(!empty($cateRet)){
			foreach($cateRet as $cate){
				$cateIdArr[] = $cate->pk_cate;
			}
			$courseRet = course_api::checkCourseBySecondCateArr($cateIdArr , $ownerId , $courseIds);
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

	public function getThirdCateList($secondCate , $ownerId=0 , $courseIds=''){
		$showCateList = array();
		$cateRet = course_api::getNodeCate($secondCate);
		if(!empty($cateRet)){
			foreach($cateRet as $cate){
				$cateIdArr[] = $cate->pk_cate;
			}
			$courseRet = course_api::checkCourseByThirdCateArr($cateIdArr , $ownerId , $courseIds);

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
	/* 分销引入 */
	function pageGetPromoteCourseAjax(){
		$courseId   = !empty($_REQUEST['courseId'])?$_REQUEST['courseId']:'';
		//return json_decode(array('code'=>$courseId));
		if(empty($courseId)){

		}
		$fields = ["course_id","title","price","market_price","first_cate","second_cate","third_cate",
			//"section_count",
			"second_cate_name","third_cate_name","remain_user","status","course_type","desc"
			,"org_subname","price_promote","is_promote","fee_type"
		];

		$query = [
			'course_id'     => $courseId,
			'admin_status'   => 1,
			'is_promote'     => 1,
			//'user_id'        => $this->orgOwner,
		];

		foreach($query as $k=>$v){
			if($v=='' || $v==-1){
				unset($query[$k]);
			}
		}

		$params = [
			"f" => $fields,
			"q" => $query,
			"p" => 1,
			"pl"=> 1,
			"ob"=> ''
		];

		$resCourse = seek_api::seekcourse($params);

		$courseList  = array();
		$resellCount=200;
		if(!empty($resCourse->data)){
			$courseList = $resCourse->data;
			$resellCount = 200-$this->courseResellCount();
		}
                $data = isset($courseList[0]) ? $courseList[0] : array();
		return json_encode(array('cdoe'=>1,'data'=>$data,'count'=>$resellCount));
	}

	public function courseResellCount(){
		$params =array('orgResellId'=>$this->orgId);
		$resellCount = course_resell_api::courseResellCount($params);
		return isset($resellCount->result)?$resellCount->result:0;
	}

	public function coursePromoteCount(){
		$params =array('fk_user'=>$this->orgOwner,'is_promote'=>1);
		$PromoteCount = course_resell_api::coursePromoteCount($params);
		return isset($PromoteCount->result)?$PromoteCount->result:0;
	}

	/*
	 * ajax 获取课程
	 */
	public function pageAddPromoteSearch(){
		$result = new stdClass;

		if(empty($_POST) || empty($_POST['optionVal'])){
			$result->error = '';
			$result->code = -1;
			return $result;
		}

		$keyword = trim($_POST['optionVal']);

		$courseParams = [
			'q'  => ['admin_status'=>1,'title'=>$keyword,'course_type'=>'1,2','user_id'=>$this->orgOwner],
			'f'  => ['course_id','title','thumb_med','price','fee_type'],
			'p'  => 1,
			'pl' => 10
		];
		$result 	= seek_api::seekcourse($courseParams);
		//$courseOne 	= course_api::getCourseOne($courseId);
		if(!empty($result->data)){
			$result->code = 1;
			return json_encode($result);
		}else{
			$result->error = '没有找到课程';
			$result->code = -1;
		}
		return $result;
	}

	public function pageAddPromoteCourse(){
		$courseId   = !empty($_REQUEST['courseId'])?$_REQUEST['courseId']:'';
		$costPrice = !empty($_REQUEST['costPrice'])? (float) $_REQUEST['costPrice']:0;
		$params=array(
			'course_id'=>$courseId,
			'price_promote'=>$costPrice,
		);
		$ret = course_resell_api::addPromote($params);
		return json_encode($ret);
	}

	/* (删除+暂停推广+重新推广)推广课程 */
	function pageChangePromoteCourseAjax($inPath){
                $course_id = !empty($_REQUEST['cid'])?$_REQUEST['cid']:'';
                $course_title = !empty($_REQUEST['ctitle'])?$_REQUEST['ctitle']:'';

                $ret = new stdClass();
                if (empty($course_id)) {
                    $code = -1;
                    $msg  = 'The request str is empty!';
                } else {
                    $op         = empty($inPath[3]) ? '' : $inPath[3];
                    if (empty($op)) {
                        $code = -2;
                        $msg  = 'The operation is empty({$op})!';
                    } else {
                        $params = array(
                            'org_id' => $this->orgId,
                            'org_owner' => $this->orgOwner,
                            'org_name' => $this->orgInfo->subname,
                            'course_id' => $course_id
                        );
                        // (删除+暂停推广+重新推广)推广课程
                        $res = course_resell_api::changePromoteCourse($op,$params);

                        $code = isset($res->result->code) ? $res->result->code : '';
                        $msg  = isset($res->result->msg) ? $res->result->msg : '';

                        $seek_course_info = course_resell_api::getSeekCourseInfo($course_id);
                        if ($courseInfo = $seek_course_info[0]){
                            $msgRes = course_resell_api::sendResellMessage($course_id,$op, $courseInfo);
                        }
                    }
                }
		$ret->code = $code;
                $ret->msg  = $msg;
		return json_encode($ret);
	}

	/* 修改成本价,已删除的课程重新推广 */
	function pageEditPromoteCourseAjax($inPath){
		$op = empty($inPath[3]) ? (empty($inputData['act']) ? '':$inputData['act']) : $inPath[3];  // 'orgStartPromote','updatePromotePrice'
		$course_id = !empty($_REQUEST['cid'])?$_REQUEST['cid']:'';
		$price_promote = !empty($_REQUEST['price'])? (float) $_REQUEST['price']:0;
		$price_old = !empty($_REQUEST['oprice'])? (float) $_REQUEST['oprice']:'';
		$ret = new stdClass();
		if (empty($course_id) || (!empty($price_old) && !isset($price_promote))) {
			$code = -1;
			$msg  = 'The course_id or price is empty!';
		} else {
			$params = ["course_id={$course_id}"];
			$course_info = course_resell_api::getPromoteCourselist(1, 1, $params);
			if ($courseInfo = $course_info->data->items[0]){
				//$op = 'updatePromotePrice';
				$courseInfo->org_subname = $this->orgInfo->subname;
				$courseInfo->price_promote_new = $price_promote;
				$msgRes = course_resell_api::sendResellMessage($course_id,$op, $courseInfo);
			}

			$params = [
				'org_id' => $this->orgId,
				'org_owner' => $this->orgOwner,
				'org_name' => $this->orgInfo->subname,
				'price_promote' => $price_promote*100,
				'price_old' => $price_old*100
			];
			$res = course_resell_api::EditPromoteCourse($course_id,$params,$op);
			$code = isset($res->result->code) ? $res->result->code : '';
			$msg  = isset($res->result->msg) ? $res->result->msg : '';
		}
		$ret->code = $code;
		$ret->msg  = $msg;
		return json_encode($ret);
	}

	/*获取课程引入机构列表*/
	function pageGetCourseRsellOrgList($inPath){
		if(empty($_POST["courseId"])){
			return json_encode(array('code'=>-1,'data'=>array()));
		}else {
			$courseId = $_POST["courseId"];
		}
		$resell = course_resell_api::getCourseRsellOrgList($courseId);
		$orgIdArr = array();
		$resellPrice = array();
		if(!empty($resell)){
			foreach($resell as $r){
				$orgIdArr[] = $r->fk_org_resell;
				$resellPrice[$r->fk_org_resell]["price_resell"] = $r->price_resell;
				$resellPrice[$r->fk_org_resell]["enroll_count"] = $r->enroll_count;
				$resellPrice[$r->fk_org_resell]["ver"] = $r->ver;
			}
		}

		if(empty($orgIdArr)){
			return json_encode(array('code'=>-1,'data'=>array()));
		}
		$promote = course_resell_api::getCoursePromote($courseId);
		$pricePromote = 0;
		$verPromote = 0;
		if(!empty($promote["price_promote"])){
			$pricePromote = $promote["price_promote"];
		}
		if(!empty($promote["ver"])){
			$verPromote = $promote["ver"];
		}
		$page = isset($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$f_array = array('org_id', 'user_owner_id','name',
			'subname',
			'subdomain',
		);
		$q_array=array(
			'org_id' => implode(',',$orgIdArr),
		);
		foreach($q_array as $k=>$v){
			if($v=='' || $v==-1 ){
				unset($q_array[$k]);
			}
		}
		$seek_arr = array(
			"f"=>$f_array,
			"q"=>$q_array,
			"p"=>1,
			"pl"=>10000
			//"ob"=>$ob_array,
		);
		$ret_seek = seek_api::seekOrg($seek_arr);
		$orgList = array();
		if(!empty($ret_seek->data)){
			$orgList=$ret_seek->data;
			$scopesName = array(1=>"学前/升学",2=>"大学/考研",3=>"职业/考证",4=>"艺术",5=>"生活/兴趣");
			foreach($orgList as $org){
				if(!empty($resellPrice[$org->org_id])){
					$org->subname = !empty($org->subname)?$org->subname:$org->name;
					$org->resellPrice = number_format(($resellPrice[$org->org_id]["price_resell"]-$pricePromote)*$resellPrice[$org->org_id]["enroll_count"]/100);
					$org->promotePrice = number_format($pricePromote*$resellPrice[$org->org_id]["enroll_count"]/100);
					$org->studentCount = $resellPrice[$org->org_id]["enroll_count"];
					if($resellPrice[$org->org_id]["ver"]==$verPromote){
						$org->resellStatus = 1;//分销状态正常
					}else{
						$org->resellStatus = 0;//分销状态异常
					}
				}
				$org->showUrl ="//" . user_organization::course_domain($org->subdomain);
			}
		}
		if(!empty($orgList)){
			return json_encode(array('code'=>1,'data'=>$orgList));
		}
		return json_encode(array('code'=>-1,'data'=>array()));
	}
	
	/*
	 * 课程管理---推广入口
	 *@params int 		$courseId
	 *@return object 	$result
	 */
	public function pageAddCoursePromoteTrance(){
		$result = new stdClass;
		if(empty($_POST) || empty($_POST['optionVal'])){
			$result->error = '';
			$result->code = -1;
			return $result;
		}
		$courseId 		= trim($_POST['optionVal']);
		$courseParams 	= [
			'q'  => ['admin_status'=>1,'course_id'=>$courseId,'course_type'=>'1,2','user_id'=>$this->orgOwner],
			'f'  => ['course_id','title','thumb_med','price','fee_type'],
			'p'  => 1,
			'pl' => 10
		];
		$result 	= seek_api::seekcourse($courseParams);
		$courseOne 	= course_api::getCourseOne($courseId);
		if(!empty($result->data) || !empty($courseOne)){
			$result->code = 1;
			return json_encode($result);
		}else{
			$result->error = '没有找到课程';
			$result->code = -1;
		}
		return $result;
	}
	/*
	 * 课程管理---获取推广课的状态
	 *@params int 		$courseId
	 *@return object 	$result
	 */
	public function pageGetCoursePromoteById(){
		$result = new stdClass;
		if(empty($_POST) || empty($_POST['optionVal'])){
			$result->error = '参数不能为空';
			$result->code = -1;
			return $result;
		}
		$courseOne 		= array();
		$courseId 		= trim($_POST['optionVal']);
		$courseOne[] 	= course_api::getCourseOne($courseId);
		$course  		= new stdClass;
		$result->data 	= $courseOne;
		if(!empty($result->data) || !empty($courseOne)){
			$result->code = 1;
			return json_encode($result);
		}else{
			$result->error = '没有找到课程';
			$result->code = -1;
		}
		return $result;
	}
}
