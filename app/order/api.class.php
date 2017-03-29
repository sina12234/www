<?php

class order_api
{
    const GET_ORDER_BY_OUT_TRADE_ID = '/order/info/GetOrderByOutTradeId/';

    const GET_ORDER_BY_ORDER_ID = '/order/info/GetOrderByOrderId/';

    const GET_ORDER_AND_CONTENT = '/order/info/GetOrderAndContent/';

    static $priceType = [
        1 => 30,
        2 => 90,
        3 => 180,
        4 => 360
    ];

    /**
     * @desc get order by out trade id
     *
     * @param $outTradeId
     * @return array
     */
    public static function getOrderByOutTradeId($outTradeId)
    {
        $url = self::GET_ORDER_BY_OUT_TRADE_ID.$outTradeId;
        $res = interface_func::requestApi($url);
        if (!empty($res['code'])) return [];

        return $res['result'];
    }

    public static function getOrderAndContent($orderId)
    {
        $url = self::GET_ORDER_AND_CONTENT.$orderId;
        $res = interface_func::requestApi($url);
        if (!empty($res['code'])) return [];
        $item                  = new stdclass;
        $item->order_id        = $res['result']["pk_order"];
        $item->user_id         = $res['result']["fk_user"];
        $item->price           = $res['result']["price"];
        $item->price_old       = $res['result']["price_old"];
        $item->price_market    = $res['result']["price"];
        $item->status          = $res['result']["status"];
        $item->callback_status = $res['result']["callback_status"];
        $item->unique_order_id = $res['result']["unique_order_id"];
        $item->out_trade_id    = $res['result']["out_trade_id"];
        $item->pay_type        = $res['result']["pay_type"];
        $item->expiration_time = $res['result']["expiration_time"];
        $item->third_order_id  = $res['result']["third_order_id"];
        $item->last_updated    = $res['result']["last_updated"];
        $item->create_time     = $res['result']["create_time"];

        if (!empty($res['result']['orderContent'])) {
            foreach ($res['result']['orderContent'] as $v) {
                // 后期１对多这里应该是个列表
                $item->object_id = $v['object_id'];
                $item->ext = $v['ext'];
                $item->org_id = $v['org_id'];
		$item->resell_org_id = $v['resell_org_id'];
                $item->object_type = $v['object_type'];
            }

            $orgInfo = org_api::getOrgInfo($item->org_id);
            $item->user_owner = $orgInfo->user_owner_id;
        }

        return $item;
    }

    public static function getOrderByOrderId($orderId)
    {
        $url = self::GET_ORDER_BY_ORDER_ID.$orderId;
        $res = interface_func::requestApi($url);
        if (!empty($res['code'])) return [];

        return $res['result'];
    }
	
	/*
	 * @param $data['org_id','fk_user','object_type','object_id','price','price_old','ext']
	 */
	public static function addOrder($userId,$data,$disStatus=0)
	{
		$params = new stdclass;
		$params->userId = $userId;
		$params->data   = $data;
		
		$orderPrice = 0;
		$orderPriceOld = 0;
		foreach($data as $val){
			$orderPrice+=$val['price'];
            $orderPriceOld+=$val['priceOld'];
		}
		$params->orderPrice = $orderPrice;
		$params->orderPriceOld = $orderPriceOld;
        $params->disStatus  = $disStatus;
	
		$ret = utility_services::call('/order/feeorder/add',$params);
		return $ret;
	}
	
	public static function updateOrder($orderId, $orderInfo)
    {
        $ret = utility_services::call("/order/feeorder/update/$orderId", $orderInfo);
        return $ret; 
    }
	
	public static function getOrderInfo($params)
	{
		$ret = utility_services::call("/order/feeorder/getOrderInfo/", $params);
		return $ret;
	}
	
	public static function orderList($params,$page=1,$length=10)
	{
		$data = $userOwner = $subDomainInfo = array();
		if(!empty($params['orgId'])){
			$data['orgId'] = (int)$params['orgId'];
		}
		if(!empty($params['orderSn'])){
			$data['orderSn'] = $params['orderSn'];
		}
		if(!empty($params['price'])){
			$data['price'] = explode(',',$params['price']);
		}
        if(!empty($params['time'])){
			$time = explode(',',$params['time']);
            $data['time'] = [
                '0' => $time[0].' 00:00:00',
                '1' => $time[1].' 23:59:59'
            ];
		}
		if(!empty($params['isFavorable'])){
			$data['isFavorable'] = (int)$params['isFavorable'];
		}
        if(isset($params['status'])){
            $data['status'] = $params['status'];
        }
		if(!empty($params['userId'])){
			if(is_array($params['userId'])){
				$userIds = implode(',',$params['userId']);
			}else{
				$userIds = (int)$params['userId'];
			}
			$data['userId'] = $userIds;
		}
        if(isset($params['orderType']) && $params['orderType'] != ''){
            $data['orderType'] = (int)$params['orderType'];
        }
		if(!empty($params['objectId'])){
			$data['objectId'] = $params['objectId'];
		}
		if(!empty($params['resell'])){
			$data['resell'] = $params['resell'];
		}
		if(!empty($params['object_type'])){
			$data['object_type'] = $params['object_type'];
		}
		if(!empty($params['distribute_status'])){
			$data['distribute_status'] = $params['distribute_status'];
		}
		if(isset($params['objTypeStatus'])) {
			$data['objTypeStatus'] = $params['objTypeStatus'];
		}
		if(isset($params['distribute_qudao'])){
			$data['distribute_qudao'] = $params['distribute_qudao'];
		}
		if(empty($data)) return $data;
		$orderRes = utility_services::call("/order/feeorder/orderlist/".$page."/".$length, $data);
		$resellOrg = array();
		if(!empty($orderRes->data)){
			$orderList = $orderRes->data;
			$userIdArr = $orderIdArr = $courseIdArr = $classIdArr = $memberSetArr =  $memberIdArr = $discountOrderIdArr =array();
			foreach($orderList as $val){
				$userIdArr[$val->fk_user] = $val->fk_user;
				$orderIdArr[$val->fk_order] = $val->fk_order;
				if($val->discount_status==1){
					$discountOrderIdArr[$val->fk_order] = $val->fk_order;
				}
                $orgIdArr[$val->org_id] = $val->org_id;
				if($val->object_type == 1){
					$courseIdArr[$val->object_id] = $val->object_id;
					$classIdArr[$val->ext]  = $val->ext;
				}elseif($val->object_type == 11){
					$memberIdArr[$val->object_id]  = $val->object_id;
					$memberSetArr[$val->object_id] = $val->ext;
				}elseif($val->object_type == 21){
					$smsInfo["title"] = "短信充值";
					$smsInfo["img"] = utility_cdn::img('/assets_v2/img/details-img.png');
				}
				//分销订单
				if($val->promote_status){
					$resellOrg[$val->resell_org_id] = $val->resell_org_id;
				}
			}
            $orgInfo = user_organization::getOrgInfoByOidArr($orgIdArr, true);
            if (!empty($orgInfo)) {
                foreach ($orgInfo as $org) {
                    $userOwner[$org->pk_org] = $org->fk_user_owner;
					$org_name[$org->pk_org] = $org->subname;
                }

                $subDomainList = user_api::getSubdomainByUidArr($userOwner);
                if (!empty($subDomainList->result->data->items)) {
                    foreach ($subDomainList->result->data->items as $subDomain) {
                        $subDomainInfo[$subDomain->fk_user][] = "//" . user_organization::course_domain($subDomain->subdomain);
                    }
                }
            }
			//分销机构信息
			$resellInfo = array();
			if(!empty($resellOrg)){
				$resellRes = user_organization::getOrgInfoByOidArr($resellOrg, true);
				if(!empty($resellRes)){
					foreach($resellRes as $val){
						$resellInfo[$val->pk_org] = $val->subname;
					}
				}
			}
			$user    = self::getUserInfo($userIdArr);
			$course  = self::getOrderCourseInfo($courseIdArr);
			$member  = self::getMemberInfo($memberIdArr, $memberSetArr);
			$discount= self::getDiscountInfo($discountOrderIdArr);
			$payType = [1=>'支付宝',2=>'微信',3=>' ',4=>'云点支付'];

			foreach($orderList as &$v){
				$v->stuName     = !empty($user[$v->fk_user]['name']) ? $user[$v->fk_user]['name'] : '';
				$v->mobile      = !empty($user[$v->fk_user]['mobile']) ? $user[$v->fk_user]['mobile'] : '';
				$v->stuImg      = !empty($user[$v->fk_user]['userImg']) ? $user[$v->fk_user]['userImg'] : '';
				$v->course      = array();
				$v->member      = array();
				$v->discount    = !empty($discount[$v->fk_order]) ? $discount[$v->fk_order] : '';
				$v->payType     = !empty($payType[$v->pay_type]) ? $payType[$v->pay_type] : '';
				$v->orderSn     = date('Ymd',strtotime($v->create_time)).$v->fk_order;
				$v->disPrice    = ($v->price_old/100) - ($v->price/100);
				$v->orderStatus = course_status::$orderStatus[$v->status];
				if($v->pay_type == 3){
					$v->uniqueOrderId = '优惠券';
				}else{
					$v->uniqueOrderId = $v->unique_order_id;
				}
				
				if($v->object_type == 1){
					$v->course = !empty($course[$v->object_id]) ? $course[$v->object_id] : array();
					if(!empty($v->resell_org_id)){
						$v->course['payUrl'] .= '/'.$v->resell_org_id;
					}
				}elseif($v->object_type == 11){
					$v->member['name'] = !empty($member[$v->object_id]['name']) ? $member[$v->object_id]['name'] : '';
					if($v->ext == 1){
						$v->member['price'] = $v->price/100;
						$v->member['day']   = !empty($member[$v->object_id]['price_30']) ? 30 : 0;
					}elseif($v->ext == 2){
						$v->member['price'] = $v->price/100;
						$v->member['day']   = !empty($member[$v->object_id]['price_90']) ? 90 : 0;
					}elseif($v->ext == 3){
						$v->member['price'] = $v->price/100;
						$v->member['day']   = !empty($member[$v->object_id]['price_180']) ? 180 : 0;
					}elseif($v->ext == 4){
						$v->member['price'] = $v->price/100;
						$v->member['day']   = !empty($member[$v->object_id]['price_360']) ? 360 : 0;
					}
					
				}elseif($v->object_type == 21){
					$v->sms = $smsInfo;
				}
				$v->price = $v->price/100;
				$v->price_promote = $v->price_promote/100;
				$v->price_old = $v->price_old/100;
				$v->price_refund = $v->price_refund/100;
				$v->gateway_price = $v->gateway_price/100;
				$v->platform_price = $v->platform_price/100;
				$v->tax_price = $v->tax_price/100;
				$v->distribute_price = $v->distribute_price/100;
				$v->gateway_price_resell = $v->gateway_price_resell/100;
				$v->platform_price_resell = $v->platform_price_resell/100;
				$v->tax_price_resell = $v->tax_price_resell/100;
				$price = $v->promote_status==1?$v->price_promote:$v->price;
				$v->actual_price = $price-($v->gateway_price+$v->platform_price+$v->tax_price+$v->distribute_price+$v->price_refund);

                $v->domainUrl = !empty($subDomainInfo[$userOwner[$v->org_id]]) ? $subDomainInfo[$userOwner[$v->org_id]] : '';
				$v->org_name = !empty($org_name[$v->org_id]) ? $org_name[$v->org_id] : '';
				$v->resellName = !empty($resellInfo[$v->resell_org_id]) ? $resellInfo[$v->resell_org_id] : '';
				$v->resellId = $v->resell_org_id;
 			}
		}
		return array(
            'page'      => !empty($orderRes->page) ? $orderRes->page : 1,
			'total'     => !empty($orderRes->total) ? $orderRes->total : 0,
			'totalSize' => !empty($orderRes->totalSize) ? $orderRes->totalSize : 0,
			'data'      => !empty($orderList) ? $orderList : array()
		);
	}
	
    //用户信息
    public static function getUserInfo($userId)
    {
        $data = array();
        if(empty($userId)) return $data;
        $userRes = user_api::listUsersByUserIds($userId);
        if(!empty($userRes->result)){
            foreach($userRes->result as $val){
                  $data[$val->pk_user] = [
                    'name'   => !empty($val->real_name) ? $val->real_name : '',
                    'mobile' => !empty($val->mobile) ? $val->mobile : '',
                    'userImg'=> !empty($val->thumb_med) ? interface_func::imgUrl($val->thumb_med) : ''
                  ];
             }
        }
        
        return $data;
    }

	//课程信息
	public static function getOrderCourseInfo($courseId)
	{
		$data = array();
		if(empty($courseId)) return $data;
		$courseIds = implode(',',$courseId);
		$courseParam = [
			'q' => ['course_id'=>$courseIds,'deleted'=>'0,-1'],
			'f' => ['course_id','class_id','thumb_med','title','course_type','subdomain','deleted','class'],
			'pl'=>count($courseId),
		];
		$courseRes = seek_api::seekcourse($courseParam);
		if(empty($courseRes->data)) return $data;
		foreach($courseRes->data as $course){
			$data[$course->course_id] = [
				'name' => $course->title,
				'img'  => interface_func::imgUrl($course->thumb_med),
				'courseType' => $course->course_type,
				'deleted'  => !empty($course->deleted) ? $course->deleted : 0,
			];
			if(!empty($course->class)){
				foreach ($course->class as $class) {
					$data[$course->course_id]["class"][$class->class_id] = [
						'className'  => !empty($class->name) ? $class->name : '',
					];
				}
			}
			$data[$course->course_id]['url'] = '/course.info.show/'.$course->course_id;
			$data[$course->course_id]['payUrl']    = '/course.info.show/'.$course->course_id;
		}
		return $data;
	}

	//课程信息
	public static function getCourseInfo($courseId, $classId,$length)
	{
		$data = array();
		if(empty($courseId) || empty($classId)) return $data;
		$courseIds = implode(',',$courseId);
		$courseParam = [
			'q' => ['course_id'=>$courseIds,'deleted'=>'0,-1'],
			'f' => ['course_id','class_id','thumb_med','title','course_type','subdomain','deleted','class'],
			'pl'=>count($courseId),
		];
		$courseRes = seek_api::seekcourse($courseParam);
		if(empty($courseRes->data)) return $data;
		$classProgress = array();
		foreach($courseRes->data as $course){
			if(!empty($course->class)){
				foreach ($course->class as $class) {
					$classProgress[$course->course_id][$class->class_id]= $class->progress_plan;
				}
			}
		}
		$domainConf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");

		$classIds = implode(',',$classId);
		$planParams = [
			'q' => ['class_id'=>$classIds,'deleted'=>'0,-1'],
            'f' => ['class_name','start_time','course_id','status','admin_real_name','teacher_real_name','deleted','plan_id','section_order_no','class_id'],
            'pl'=>count($courseId)*count($classId)*100,
		];
		$planRes = seek_api::seekplan($planParams);
		$planData = array();
		if(!empty($planRes->data)){
			foreach($planRes->data as $val){
				$planData[$val->course_id] = [
					'className' => $val->class_name,
					'startTime' => $val->start_time,
					'teacherName'=> !empty($val->admin_real_name) ? $val->admin_real_name : $val->teacher_real_name
				];
				if(!empty($classProgress[$val->course_id][$val->class_id])&&$val->plan_id==$classProgress[$val->course_id][$val->class_id]){
					$planData[$val->course_id]["planName"]= $val->section_order_no;
				}
				if(empty($planData[$val->course_id]['planNum'])) {
					$planData[$val->course_id]['planNum'] = 0;
				}
				$planData[$val->course_id]['planNum'] +=1;
			}
		}

		foreach($courseRes->data as $val){
			$data[$val->course_id] = [
				'name' => $val->title,
				'img'  => interface_func::imgUrl($val->thumb_med),
				'courseType' => $val->course_type,
				'className'  => !empty($planData[$val->course_id]['className']) ? $planData[$val->course_id]['className'] : '',
				'startTime'  => !empty($planData[$val->course_id]['startTime']) ? $planData[$val->course_id]['startTime'] : '',
				'teacherName'  => !empty($planData[$val->course_id]['teacherName']) ? $planData[$val->course_id]['teacherName'] : '',
				'deleted'  => !empty($val->deleted) ? $val->deleted : 0,
				'planNum' => !empty($planData[$val->course_id]['planNum']) ? $planData[$val->course_id]['planNum'] : '',
				'progress' =>!empty($planData[$val->course_id]['planName']) ? $planData[$val->course_id]['planName'] : 0,
			];

			/*if(strrpos($val->subdomain, '.')){
				$data[$val->course_id]['url'] = '//'.$val->subdomain .'/course.info.show/'.$val->course_id;
				$data[$val->course_id]['payUrl']    = '//'.$val->subdomain .'/course.info.show/'.$val->course_id;
			}else{
				$data[$val->course_id]['url'] = '//'.$val->subdomain .'.'.$domainConf->domain .'/course.info.show/'.$val->course_id;
				$data[$val->course_id]['payUrl']    = '//'.$val->subdomain .'.'.$domainConf->domain .'/course.info.show/'.$val->course_id;
			}
			*/
			$data[$val->course_id]['url'] = '/course.info.show/'.$val->course_id;
			$data[$val->course_id]['payUrl']    = '/course.info.show/'.$val->course_id;
		}
			
		return $data;
	}
	
	//会员信息
	public static function getMemberInfo($memberId, $ext)
	{
		$data = array();
		if(empty($memberId)) return $data;
		$memberIds = implode(',',$memberId);
		$params    = array('setId'=>$memberIds);
		$memberRes = utility_services::call("/user/orgMemberSet/getOrgMemberSets/", $params);
		if($memberRes->code != 0) return $data;
		
		foreach($memberRes->result->items as $val){
			$data[$val->pk_member_set] = [
				'name' => $val->title,
				'price_30' => $val->price_30,
				'price_90' => $val->price_90,
				'price_180' => $val->price_180,
				'price_360' => $val->price_360
			];
		}
		
		return $data;
	}
	
	//优惠码信息
	public static function getDiscountInfo($orderId)
	{
		$data = array();
		if(empty($orderId)) return $data;
		$orderIds = implode(',',$orderId);
		$discountRes = course_api::getDiscountCodeByOrder($orderIds);
		if(!empty($discountRes)){
			foreach($discountRes as $val){
				$data[$val->fk_order] = $val->discount_code;
			}
		}

		return $data;
	}
	
	//从redis获取
	public static function getVipTime($param)
	{
		$ret = utility_services::call("/order/member/addviptime",$param);
		return $ret;
	}
	
	public static function getOrder($param)
	{
		$ret = utility_services::call("/order/feeorder/getOrder",$param);
		return $ret;
	}
	
	public static function checkCode($params)
	{
		$ret = utility_services::call("/order/discount/checkCode",$params);
		return $ret;
	}

	public static function checkCodeNew($params)
	{
		$ret = utility_services::call("/order/discount/checkCodeNew",$params);
		return $ret;
	}

	public static function updateUsedNumForDiscountCodeById($params){
		$ret = utility_services::call("/order/discount/updateUsedNumForDiscountCodeById",$params);
		return $ret;
	}

	public static  function getDiscountCodeById($params){
		$ret = utility_services::call("/order/discount/getDiscountCodeById",$params);
		return $ret;
	}

	public static function updateOrderPrice($orderId, $orderInfo)
	{
		$ret = utility_services::call("/order/feeorder/updatePrice/$orderId", $orderInfo);
		return $ret;
	}

	public static function addOrderLog($info){
		$ret = utility_services::call("/order/orderLog/add",$info);
		return $ret;
	}

	/*
	 * @param $data['orderId','orderContentId','userId','price','clearingStatus','orgUserId']
	 */
	public static function addOrderContentRefund($params){
		$ret = utility_services::call("/order/orderContentRefund/add",$params);
		return $ret;
	}

	/*
	 * @param $data['orgId','userId']
	 */
	public static function getOrderRefundList($page,$length,$params){
		$orderRes = utility_services::call("/order/orderContentRefund/list/$page/$length",$params);
		$resellOrg = array();
		if(!empty($orderRes->data)){
			$orderList = $orderRes->data;
			$userIdArr = $orderIdArr = $courseIdArr = $classIdArr = $memberSetArr =  $memberIdArr = $discountOrderIdArr =array();
			foreach($orderList as $val){
				$userIdArr[$val->fk_user] = $val->fk_user;
				$orderIdArr[$val->fk_order] = $val->fk_order;
				if($val->discount_status==1){
					$discountOrderIdArr[$val->fk_order] = $val->fk_order;
				}
				$orgIdArr[$val->org_id] = $val->org_id;
				if($val->object_type == 1){
					$courseIdArr[$val->object_id] = $val->object_id;
					$classIdArr[$val->ext]  = $val->ext;
				}elseif($val->object_type == 11){
					$memberIdArr[$val->object_id]  = $val->object_id;
					$memberSetArr[$val->object_id] = $val->ext;
				}
				//分销订单
				if($val->promote_status){
					$resellOrg[$val->resell_org_id] = $val->resell_org_id;
				}
			}
			$orgInfo = user_organization::getOrgInfoByOidArr($orgIdArr, true);
			if (!empty($orgInfo)) {
				foreach ($orgInfo as $org) {
					$userOwner[$org->pk_org] = $org->fk_user_owner;
					$org_name[$org->pk_org] = $org->subname;
				}

				$subDomainList = user_api::getSubdomainByUidArr($userOwner);
				if (!empty($subDomainList->result->data->items)) {
					foreach ($subDomainList->result->data->items as $subDomain) {
						$subDomainInfo[$subDomain->fk_user][] = "//" . user_organization::course_domain($subDomain->subdomain);
					}
				}
			}
			//分销机构信息
			$resellInfo = array();
			if(!empty($resellOrg)){
				$resellRes = user_organization::getOrgInfoByOidArr($resellOrg, true);
				if(!empty($resellRes)){
					foreach($resellRes as $val){
						$resellInfo[$val->pk_org] = $val->subname;
					}
				}
			}

			$user    = self::getUserInfo($userIdArr);
			$course  = self::getCourseInfo($courseIdArr, $classIdArr,$length);
			$member  = self::getMemberInfo($memberIdArr, $memberSetArr);
			$discount= self::getDiscountInfo($discountOrderIdArr);
			$payType = [1=>'支付宝',2=>'微信',3=>' ',4=>'云点支付'];

			foreach($orderList as &$v){
				$v->stuName     = !empty($user[$v->fk_user]['name']) ? $user[$v->fk_user]['name'] : '';
				$v->mobile      = !empty($user[$v->fk_user]['mobile']) ? $user[$v->fk_user]['mobile'] : '';
				$v->stuImg      = !empty($user[$v->fk_user]['userImg']) ? $user[$v->fk_user]['userImg'] : '';
				$v->course      = array();
				$v->member      = array();
				$v->discount    = !empty($discount[$v->fk_order]) ? $discount[$v->fk_order] : '';
				$v->payType     = !empty($payType[$v->pay_type]) ? $payType[$v->pay_type] : '';
				$v->orderSn     = date('Ymd',strtotime($v->create_time)).$v->fk_order;
				$v->disPrice    = ($v->price_old/100) - ($v->price/100);
				$v->orderStatus = course_status::$orderStatus[$v->status];
				if($v->pay_type == 3){
					$v->uniqueOrderId = '优惠券';
				}else{
					$v->uniqueOrderId = $v->unique_order_id;
				}

				if($v->object_type == 1){
					$v->course = !empty($course[$v->object_id]) ? $course[$v->object_id] : array();
				}elseif($v->object_type == 11){
					$v->member['name'] = !empty($member[$v->object_id]['name']) ? $member[$v->object_id]['name'] : '';
					if($v->ext == 1){
						$v->member['price'] = $v->price/100;
						$v->member['day']   = !empty($member[$v->object_id]['price_30']) ? 30 : 0;
					}elseif($v->ext == 2){
						$v->member['price'] = $v->price/100;
						$v->member['day']   = !empty($member[$v->object_id]['price_90']) ? 90 : 0;
					}elseif($v->ext == 3){
						$v->member['price'] = $v->price/100;
						$v->member['day']   = !empty($member[$v->object_id]['price_180']) ? 180 : 0;
					}elseif($v->ext == 4){
						$v->member['price'] = $v->price/100;
						$v->member['day']   = !empty($member[$v->object_id]['price_360']) ? 360 : 0;
					}

				}
				$v->price = $v->price/100;
				$v->price_old = $v->price_old/100;
				$v->price_refund = $v->price_refund/100;
				$v->refund_create_time = date("Y-m-d H:i",strtotime($v->refund_create_time));
				$v->domainUrl = !empty($subDomainInfo[$userOwner[$v->org_id]]) ? $subDomainInfo[$userOwner[$v->org_id]] : '';
				$v->org_name = !empty($org_name[$v->org_id]) ? $org_name[$v->org_id] : '';
				$v->resellName = !empty($resellInfo[$v->resell_org_id]) ? $resellInfo[$v->resell_org_id] : '';
			}
		}

		return array(
			'page'      => !empty($orderRes->page) ? $orderRes->page : 1,
			'total'     => !empty($orderRes->total) ? $orderRes->total : 0,
			'totalSize' => !empty($orderRes->totalSize) ? $orderRes->totalSize : 0,
			'data'      => !empty($orderList) ? $orderList : array()
		);
	}

	public static function getOrderByOrderIdArr($orderIdArr){
		$orderRes = utility_services::call("/order/info/GetOrderByOrderIds",array("orderIdArr"=>$orderIdArr));
		return $orderRes;
	}
	public static function getOrderContentByOrderIdArr($orderIdArr){
		$orderRes = utility_services::call("/order/info/GetOrderContentByOrderIds",array("orderIdArr"=>$orderIdArr));
		return $orderRes;
	}
	//根据支付方式获取网关信息
	public static function getGatewayById($payType){
		$gateway = utility_services::call("/order/gateway/getGatewayById/$payType");
		if(!empty($gateway->result)){
			return $gateway->result;
		}
		return false;
	}
	//获取线下推广课程分成信息
	public static function getDistributeByCourseId($courseId){
		$distribute = utility_services::call("/course/distribute/getDistributeByCourseId/$courseId");
		if(!empty($distribute->result)){
			return $distribute->result;
		}
		return false;
	}
	//根据机构Id获取支付方式
	public static function getOrgGatewayByOrgId($orgId){
		$gateway = utility_services::call("/order/gateway/GetOrgGatewayByOrgId/$orgId");
		if(!empty($gateway->result)){
			return $gateway->result;
		}
		return false;
	}
	//更新渠道分销订单数以及收入
	public static function UpdateQudaoUserCodeBycourseIdAndQudaoUserIdAndCode($data){
		$gateway = utility_services::call("/course/distributeQudaoUserCode/UpdateQudaoUserCodeBycourseIdAndQudaoUserIdAndCode",$data);
		return $gateway;
	}
	//获取渠道Id
	public static function getOneQudaoUserById($qudaoUser){
		$gateway = utility_services::call("/mgr/qudaoUser/getOneQudaoUserById/$qudaoUser");
		if(!empty($gateway->result)){
			return $gateway->result;
		}
		return false;
	}
	//验证线下分销码是否有效
	public static function getQudaoUserCodeByQudaoUserIdAndCourseIdAndCode($data){
		if( empty($data["fk_course"]) ) {
			return false;
		}
		if(empty($data["fk_qudao_user"]) ){
			return false;
		}
		if(empty($data["code"]) ){
			return false;
		}
		$qudaoUserCode = utility_services::call("/course/distributeQudaoUserCode/getQudaoUserCodeByQudaoUserIdAndCourseIdAndCode",$data);
		if(!empty($qudaoUserCode->result)){
			return $qudaoUserCode->result;
		}
		return false;
	}
}
