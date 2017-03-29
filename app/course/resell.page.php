<?php
class course_resell extends STpl{
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
        /* 我引入的课程 */
	function pageList($inPath){		
		$page = !empty($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$size = !empty($_REQUEST['size'])?(int)$_REQUEST['size']:20;
                $searchField = isset($_REQUEST['search_field'])?$_REQUEST['search_field']:'';
			$searchType = isset($_REQUEST['search_type'])?$_REQUEST['search_type']:1;
                $status      = isset($_REQUEST['st'])?$_REQUEST['st']:'';
		
                $courseListObj = $this->getResellCourseList();

                $total = $courseListObj['total'];
                $count = $courseListObj['count'];
                $courseList = empty($courseListObj['data']) ? '' : $courseListObj['data'];
                $attrValues = $courseListObj['attrs'];
                $course_ids = $courseListObj['course_ids'];
		$courseFeeTypeArr = array();
                // 从中间层获取课程数据
                $seek_course_info = course_resell_api::getSeekCourseInfo($course_ids,$searchType,$searchField);
		if(!empty($seek_course_info)){
			foreach($seek_course_info as $fee){
				$courseFeeTypeArr[$fee->course_id] = $fee->fee_type;
			}
		}
                // 获取章节数，分类 ，属性 信息
                $attrValues = course_resell_api::getCourseAttr($seek_course_info);                   
		$maxCount = 200;
                $resellCount = $maxCount-$count;
		$pm = [
			'page' =>$page,
			'size' =>$size,
			'total' =>$total,
			'count' =>$count,
			'search_field'=> $searchField,
			'status' => $status,
                        'resellCount' =>$resellCount,
		];

		$path = '/course.resell.list';	
                $path_page = utility_tool::getUrl($path);

		$this->assign("courseList",$courseList);
		$this->assign("pm",$pm);
		$this->assign("path",$path);
                $this->assign("path_page",$path_page);
		$this->assign("attrValues",$attrValues);

		$this->assign("searchType",$searchType);
		$this->assign("courseFeeTypeArr",$courseFeeTypeArr);
		$this->render("course/fenxiao/course.resell.list.html");
	}
        
        public function getResellCourseList(){  	
                $courseList = NULL;
		//$sort = !empty($_REQUEST['sort'])?$_REQUEST['sort']:'t_course_resell.status desc,t_course_resell.last_updated desc';  
		$sort = !empty($_REQUEST['sort'])?$_REQUEST['sort']:'t_course_resell.last_updated desc';              
		$page = !empty($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$size = !empty($_REQUEST['size'])?(int)$_REQUEST['size']:20;
                $searchField = isset($_REQUEST['search_field'])?$_REQUEST['search_field']:'';
                $status      = isset($_REQUEST['st'])?$_REQUEST['st']:'';
                $userID      = $this->orgId;
                $params      = array('sort'=>$sort ,'status'=>$status,'uid'=>$userID,'search'=>$searchField);
                $resCourse  = course_resell_api::getResellCourselist($page, $size,$params);    

                $course_ids = '';
                $status_arr = course_resell_api::$resell_status_arr;
                $course_type_arr = ['1'=>'直播课' , '2'=>'录播课' , '3'=>'线下课'];
                if(!empty($resCourse->data)){
                    $courseList = $resCourse->data->items;
                    //获取科目名称
                    foreach($courseList as $key => $value){
                        $course_ids .= $value->course_id.',';
                        $orgInfo = user_organization::getOrgByOwner($value->fk_org_promote);
                        $courseList[$key]->org_name = empty($orgInfo->subname) ? '' : $orgInfo->subname;
                        $courseList[$key]->status_info = isset($status_arr[$value->status]['info']) ? $status_arr[$value->status]['info'] : '';
                        $courseList[$key]->status_tip  = isset($status_arr[$value->status]['tip']) ? $status_arr[$value->status]['tip'] : '';
                        $courseList[$key]->course_type_str  = isset($course_type_arr[$value->course_type]) ? $course_type_arr[$value->course_type] : '' ;
                    }
                    $course_ids .= substr($course_ids, 0,-1);                
                    
                    $count = $resCourse->data->totalSize;       // 总条数
                    $total = ceil($count/$size);                 // 总页数  
                } else {
                    $count = $total = 0;
                }
                 
                return array('total'=>$total,'count'=>$count,'data'=>$courseList,'course_ids'=>$course_ids,'attrs'=>'');
        }
        
	/*新增引入分销课程*/
	function pageAdd(){
		$params=array(
			'course_id'=>isset($_REQUEST['courseId'])?$_REQUEST['courseId']:'',
			'price_resell'=>isset($_REQUEST['salePrice'])?$_REQUEST['salePrice']:'',
			'op'=>isset($_REQUEST['op'])?$_REQUEST['op']:'',
			'org_id'=>$this->orgId,
		);
		$ret = course_resell_api::addResell($params);
		return json_encode($ret);
	}

	/*获取课程分销成交记录*/
	public function pageGetCourseResellLog($inPath){
		$page = !empty($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$size = 20;
		$courseId = !empty($_REQUEST['cId'])?(int)$_REQUEST['cId']:0;
		$params = array(
			'course_resell_id'=>$courseId,
			'type'=>2,
			'org_resell_id'=>$this->orgId,
			);
		$ret = course_resell_api::getCourseResellLog($page,$size,$params);
		$resellLogList = empty($ret->data) ? '' : $ret->data;
		$orderIdArr = array();
		if(!empty($resellLogList)){
			foreach($resellLogList as $v){
				$orderIdArr[$v->order_id] =$v->order_id;
			}
		}
		$orderList = array();
		if(!empty($orderIdArr)){
			$orderInfo = order_api::getOrderContentByOrderIdArr($orderIdArr);
			if(!empty($orderInfo->result)){
				foreach($orderInfo->result as $or){
					$orderList[$or->fk_order]=array(
						"gateway_price"=>$or->gateway_price_resell,
						"platform_price"=>$or->platform_price_resell,
						"tax_price"=>$or->tax_price_resell,
						"distribute_price"=>$or->distribute_price,
						"gateway_rate"=>$or->gateway_rate,
						"platform_rate"=>$or->platform_rate_resell,
						"tax_rate"=>$or->tax_rate_resell,
					);
				}
			}
			foreach($resellLogList as &$val){
				$gatewayRate = 0;
				$separateRate = 0;
				$taxRate = 0;
				$gatewayPrice = 0;
				$separatePrice = 0;
				$taxPrice = 0;
				$channelPrice = 0;
				$actualPrice = $val->income;
				if(!empty($orderList[$val->order_id]["gateway_rate"])&&$orderList[$val->order_id]["gateway_rate"]>0){
					$gatewayRate=$orderList[$val->order_id]["gateway_rate"];
				}
				if(!empty($orderList[$val->order_id]["platform_rate"])&&$orderList[$val->order_id]["platform_rate"]>0){
					$separateRate=$orderList[$val->order_id]["platform_rate"];
				}
				if(!empty($orderList[$val->order_id]["tax_rate"])&&$orderList[$val->order_id]["tax_rate"]>0){
					$taxRate=$orderList[$val->order_id]["tax_rate"];
				}
				if(!empty($orderList[$val->order_id]["gateway_price"])&&$orderList[$val->order_id]["gateway_price"]>0){
					$gatewayPrice=$orderList[$val->order_id]["gateway_price"]/100;
					$actualPrice -= $gatewayPrice;
				}
				if(!empty($orderList[$val->order_id]["platform_price"])&&$orderList[$val->order_id]["platform_price"]>0){
					$separatePrice=$orderList[$val->order_id]["platform_price"]/100;
					$actualPrice -= $separatePrice;
				}
				if(!empty($orderList[$val->order_id]["distribute_price"])&&$orderList[$val->order_id]["distribute_price"]>0){
					$channelPrice=$orderList[$val->order_id]["distribute_price"]/100;
					$actualPrice -= $channelPrice;
				}
				if(!empty($orderList[$val->order_id]["tax_price"])&&$orderList[$val->order_id]["tax_price"]>0){
					$taxPrice=$orderList[$val->order_id]["tax_price"]/100;
					$actualPrice -= $taxPrice;
				}
				$val->gateway_rate=$gatewayRate;
				$val->platform_rate=$separateRate;
				$val->tax_rate=$taxRate;
				$val->gateway_price=$gatewayPrice;
				$val->separate_price=$separatePrice;
				$val->tax_price=$taxPrice;
				$val->channel_price=$channelPrice;
				$val->income = $actualPrice;
			}
		}
		$total = empty($ret->total)? 0 : $ret->total;
		$path = '/course.resell.GetCourseResellLog';
		$path_page = utility_tool::getUrl($path);
		$this->assign("page",$page);
		$this->assign("size",$size);
		$this->assign("total",$total);
		$this->assign("path_page",$path_page);
		$this->assign("resellLogList",$resellLogList);
		$this->render("course/fenxiao/course.resell.log.list.html");
	}

	/*获取课程推广成交记录*/
	public function pageGetCoursePromoteLog($inPath){
		$page = !empty($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$size = 20;
		$courseId = !empty($_REQUEST['cId'])?(int)$_REQUEST['cId']:0;
		$params = array(
			'course_resell_id'=>$courseId,
			'type'=>1,
			'org_promote_id'=>$this->orgId,
		);
		$ret = course_resell_api::getCourseResellLog($page,$size,$params);
		$total = empty($ret->total)? 0 : $ret->total;
		$resellLogList = empty($ret->data) ? '' : $ret->data;
		$orderIdArr = array();
		if(!empty($resellLogList)){
			foreach($resellLogList as $v){
				$orderIdArr[$v->order_id] =$v->order_id;
			}
		}
		$orderList = array();
		if(!empty($orderIdArr)){
			$orderInfo = order_api::getOrderContentByOrderIdArr($orderIdArr);
			if(!empty($orderInfo->result)){
				foreach($orderInfo->result as $or){
					$orderList[$or->fk_order]=array(
						"gateway_price"=>$or->gateway_price,
						"platform_price_resell"=>$or->platform_price_resell,
						"tax_price_resell"=>$or->tax_price_resell,
						"distribute_price"=>$or->distribute_price,
						"gateway_rate"=>$or->gateway_rate,
						"platform_rate"=>$or->platform_rate,
						"tax_rate"=>$or->tax_rate,
					);
				}
			}
			foreach($resellLogList as &$val){
				$gatewayRate = 0;
				$separateRate = 0;
				$taxRate = 0;
				$gatewayPrice = 0;
				$separatePrice = 0;
				$taxPrice = 0;
				$channelPrice = 0;
				$actualPrice = $val->income;
				if(!empty($orderList[$val->order_id]["gateway_rate"])&&$orderList[$val->order_id]["gateway_rate"]>0){
					$gatewayRate=$orderList[$val->order_id]["gateway_rate"];
				}
				if(!empty($orderList[$val->order_id]["platform_rate"])&&$orderList[$val->order_id]["platform_rate"]>0){
					$separateRate=$orderList[$val->order_id]["platform_rate"];
				}
				if(!empty($orderList[$val->order_id]["tax_rate"])&&$orderList[$val->order_id]["tax_rate"]>0){
					$taxRate=$orderList[$val->order_id]["tax_rate"];
				}
				if(!empty($orderList[$val->order_id]["gateway_price"])&&$orderList[$val->order_id]["gateway_price"]>0){
					$gatewayPrice=$orderList[$val->order_id]["gateway_price"]/100;
					$actualPrice -= $gatewayPrice;
				}
				if(!empty($orderList[$val->order_id]["platform_price"])&&$orderList[$val->order_id]["platform_price"]>0){
					$separatePrice=$orderList[$val->order_id]["platform_price"]/100;
					$actualPrice -= $separatePrice;
				}
				if(!empty($orderList[$val->order_id]["distribute_price"])&&$orderList[$val->order_id]["distribute_price"]>0){
					$channelPrice=$orderList[$val->order_id]["distribute_price"]/100;
					$actualPrice -= $channelPrice;
				}
				if(!empty($orderList[$val->order_id]["tax_price_resell"])&&$orderList[$val->order_id]["tax_price_resell"]>0){
					$taxPrice=$orderList[$val->order_id]["tax_price_resell"]/100;
					$actualPrice -= $taxPrice;
				}
				$val->gateway_rate=$gatewayRate;
				$val->platform_rate=$separateRate;
				$val->tax_rate=$taxRate;
				$val->gateway_price=$gatewayPrice;
				$val->separate_price=$separatePrice;
				$val->tax_price=$taxPrice;
				$val->channel_price=$channelPrice;
				$val->income = $actualPrice;
			}
		}
		$path = '/course.resell.GetCoursePromoteLog';
		$path_page = utility_tool::getUrl($path);
		$this->assign("page",$page);
		$this->assign("size",$size);
		$this->assign("total",$total);
		$this->assign("path_page",$path_page);
		$this->assign("resellLogList",$resellLogList);
		$this->render("course/fenxiao/course.promote.log.list.html");
	}

	/*获取机构分销成交记录*/
	public function pageGetOrgResellLog($inPath){
		$searchField = isset($_REQUEST['search_field'])?$_REQUEST['search_field']:'';
		$page = !empty($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$size = 20;
		$params = array(
			'type'=>2,
			'org_resell_id'=>$this->orgId,
		);
		$ret = course_resell_api::getCourseResellLog($page,$size,$params);
		$total = empty($ret->total)? '' : $ret->total;
		$resellLogList = empty($ret->data) ? '' : $ret->data;
		$orderIdArr = array();
		if(!empty($resellLogList)){
			foreach($resellLogList as $v){
				$orderIdArr[$v->order_id] =$v->order_id;
			}
		}
		$orderList = array();
		if(!empty($orderIdArr)){
			$orderInfo = order_api::getOrderContentByOrderIdArr($orderIdArr);
			if(!empty($orderInfo->result)){
				foreach($orderInfo->result as $or){
					$orderList[$or->fk_order]=array(
						"gateway_price"=>$or->gateway_price_resell,
						"platform_price"=>$or->platform_price_resell,
						"tax_price"=>$or->tax_price_resell,
						"distribute_price"=>$or->distribute_price,
						"gateway_rate"=>$or->gateway_rate,
						"platform_rate"=>$or->platform_rate_resell,
						"tax_rate"=>$or->tax_rate_resell,
					);
				}
			}
			foreach($resellLogList as &$val){
				$gatewayPrice = 0;
				$separatePrice = 0;
				$taxPrice = 0;
				$channelPrice = 0;
				$gatewayRate = 0;
				$separateRate = 0;
				$taxRate = 0;
				$actualPrice = $val->income;
				if(!empty($orderList[$val->order_id]["gateway_rate"])&&$orderList[$val->order_id]["gateway_rate"]>0){
					$gatewayRate=$orderList[$val->order_id]["gateway_rate"];
				}
				if(!empty($orderList[$val->order_id]["platform_rate"])&&$orderList[$val->order_id]["platform_rate"]>0){
					$separateRate=$orderList[$val->order_id]["platform_rate"];
				}
				if(!empty($orderList[$val->order_id]["tax_rate"])&&$orderList[$val->order_id]["tax_rate"]>0){
					$taxRate=$orderList[$val->order_id]["tax_rate"];
				}
				if(!empty($orderList[$val->order_id]["gateway_price"])&&$orderList[$val->order_id]["gateway_price"]>0){
					$gatewayPrice=$orderList[$val->order_id]["gateway_price"]/100;
					$actualPrice -= $gatewayPrice;
				}
				if(!empty($orderList[$val->order_id]["platform_price"])&&$orderList[$val->order_id]["platform_price"]>0){
					$separatePrice=$orderList[$val->order_id]["platform_price"]/100;
					$actualPrice -= $separatePrice;
				}
				if(!empty($orderList[$val->order_id]["distribute_price"])&&$orderList[$val->order_id]["distribute_price"]>0){
					$channelPrice=$orderList[$val->order_id]["distribute_price"]/100;
					$actualPrice -= $channelPrice;
				}
				if(!empty($orderList[$val->order_id]["tax_price"])&&$orderList[$val->order_id]["tax_price"]>0){
					$taxPrice=$orderList[$val->order_id]["tax_price"]/100;
					$actualPrice -= $taxPrice;
				}
				$val->gateway_rate=$gatewayRate;
				$val->platform_rate=$separateRate;
				$val->tax_rate=$taxRate;
				$val->gateway_price=$gatewayPrice;
				$val->separate_price=$separatePrice;
				$val->tax_price=$taxPrice;
				$val->channel_price=$channelPrice;
				$val->income = $actualPrice;
			}
		}
		$path = '/course.resell.GetOrgResellLog';
		$path_page = utility_tool::getUrl($path);
		$pm = [
			'page' =>$page,
			'size' =>$size,
			'total' =>$total,
			'search_field'=> $searchField,
		];
		$this->assign("pm",$pm);
		$this->assign("path_page",$path_page);
		$this->assign("resellLogList",$resellLogList);
		$this->render("course/fenxiao/org.resell.log.list.html");
	}

	/*获取机构推广成交记录*/
	public function pageGetOrgPromoteLog($inPath){
		$searchField = isset($_REQUEST['search_field'])?$_REQUEST['search_field']:'';
		$page = !empty($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$size = 20;
		$params = array(
			'type'=>1,
			'org_promote_id'=>$this->orgId,
		);
		$ret = course_resell_api::getCourseResellLog($page,$size,$params);
		$total = empty($ret->total)?0:$ret->total;
		$resellLogList = empty($ret->data) ? '' : $ret->data;
		$orderIdArr = array();
		if(!empty($resellLogList)){
			foreach($resellLogList as $v){
				$orderIdArr[$v->order_id] =$v->order_id;
			}
		}
		$orderList = array();
		if(!empty($orderIdArr)){
			$orderInfo = order_api::getOrderContentByOrderIdArr($orderIdArr);
			if(!empty($orderInfo->result)){
				foreach($orderInfo->result as $or){
					$orderList[$or->fk_order]=array(
						"gateway_price"=>$or->gateway_price,
						"platform_price"=>$or->platform_price,
						"tax_price"=>$or->tax_price,
						"distribute_price"=>$or->distribute_price,
						"gateway_rate"=>$or->gateway_rate,
						"platform_rate"=>$or->platform_rate,
						"tax_rate"=>$or->tax_rate,
					);
				}
			}
			foreach($resellLogList as &$val){
				$gatewayPrice = 0;
				$separatePrice = 0;
				$taxPrice = 0;
				$gatewayRate = 0;
				$separateRate = 0;
				$taxRate = 0;
				$channelPrice = 0;
				$actualPrice = $val->price_promote;
				if(!empty($orderList[$val->order_id]["gateway_rate"])&&$orderList[$val->order_id]["gateway_rate"]>0){
					$gatewayRate=$orderList[$val->order_id]["gateway_rate"];
				}
				if(!empty($orderList[$val->order_id]["platform_rate"])&&$orderList[$val->order_id]["platform_rate"]>0){
					$separateRate=$orderList[$val->order_id]["platform_rate"];
				}
				if(!empty($orderList[$val->order_id]["tax_rate"])&&$orderList[$val->order_id]["tax_rate"]>0){
					$taxRate=$orderList[$val->order_id]["tax_rate"];
				}
				if(!empty($orderList[$val->order_id]["gateway_price"])&&$orderList[$val->order_id]["gateway_price"]>0){
					$gatewayPrice=$orderList[$val->order_id]["gateway_price"]/100;
					$actualPrice -= $gatewayPrice;
				}
				if(!empty($orderList[$val->order_id]["platform_price"])&&$orderList[$val->order_id]["platform_price"]>0){
					$separatePrice=$orderList[$val->order_id]["platform_price"]/100;
					$actualPrice -= $separatePrice;
				}
				if(!empty($orderList[$val->order_id]["distribute_price"])&&$orderList[$val->order_id]["distribute_price"]>0){
					$channelPrice=$orderList[$val->order_id]["distribute_price"]/100;
					$actualPrice -= $channelPrice;
				}
				if(!empty($orderList[$val->order_id]["tax_price"])&&$orderList[$val->order_id]["tax_price"]>0){
					$taxPrice=$orderList[$val->order_id]["tax_price"]/100;
					$actualPrice -= $taxPrice;
				}
				$val->gateway_rate=$gatewayRate;
				$val->platform_rate=$separateRate;
				$val->tax_rate=$taxRate;
				$val->gateway_price=$gatewayPrice;
				$val->separate_price=$separatePrice;
				$val->tax_price=$taxPrice;
				$val->channel_price=$channelPrice;
				$val->income = $actualPrice;
			}
		}
		$path = '/course.resell.GetOrgPromoteLog';
		$path_page = utility_tool::getUrl($path);
		$pm = [
			'page' =>$page,
			'size' =>$size,
			'total' =>$total,
			'search_field'=> $searchField,
		];
		$this->assign("pm",$pm);
		$this->assign("path_page",$path_page);
		$this->assign("resellLogList",$resellLogList);
		$this->render("course/fenxiao/org.promote.log.list.html");
	}        
        
	/* (删除+重新引入)分销课程 */
	function pageChangeResellCourseAjax($inPath){
                $crid = !empty($_REQUEST['crid'])?$_REQUEST['crid']:'';
                $ret = new stdClass();
                if (empty($crid)) {
                    $code = -1;
                    $msg  = 'The request str is empty!';
                } else {
                    $crids = explode('-', $crid);
                    $course_id  = empty($crids[0]) ? 0 : $crids[0];
                    $org_id     = empty($crids[1]) ? 0 : $crids[1];
                    $op         = empty($inPath[3]) ? '' : $inPath[3];
                    if (empty($course_id)|| empty($org_id) || empty($op)) {
                        $code = -2;
                        $msg  = 'The course_id or org_id or operation is empty!';
                    } else {
                        $params = array(
                            'course_id' => $course_id,
                            'org_id'    => $org_id,
                        );
                        // 删除分销课程
                        $res = course_resell_api::changeResellCourse($op,$params);          

                        $code = $res->result->code;
                        $msg  = $res->result->msg;
                    }
                }
		$ret->code = $code;
                $ret->msg  = $msg;
		return json_encode($ret);
	}
        
	/* 修改分销课程 */
	function pageEditResellCourseAjax($inPath){
                $course_id = !empty($_REQUEST['cid'])?$_REQUEST['cid']:'';
                $org_id = !empty($_REQUEST['oid'])?$_REQUEST['oid']:'';
                $price_resell = !empty($_REQUEST['price'])? (float) $_REQUEST['price']:'';
                $op = !empty($_REQUEST['op'])?$_REQUEST['op']:'';

                $ret = new stdClass();
                if (empty($course_id) || empty($org_id) || empty($price_resell)) {
                    $code = -1;
                    $msg  = 'The course_id or org_id or price is empty!';
                } else {
                    $params = array(
                        'op' => $op,
                        'org_id' => $org_id,
                        'price_resell' => $price_resell*100
                    );

                    $res = course_resell_api::EditResellCourse($course_id,$params);          

                    $code = $res->result->code;
                    $msg  = $res->result->msg;
                }
		$ret->code = $code;
                $ret->msg  = $msg;
		return json_encode($ret);
	}
}
