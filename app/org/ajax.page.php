<?PHP

class org_ajax
{
	public $orgId;
	public $user;
	public function __construct()
	{
		//判断是否登陆
		$this->user = user_api::loginedUser();
		if(empty($this->user)){
			return $this->setAjaxResult('-1','没有登陆');
		}
		//机构信息
		$org = user_organization::subdomain();
		if(!empty($org)){
			$orgInfo = user_organization::getOrgByOwner($org->userId);
			$this->orgId = $orgInfo->oid;
		}else{
			return $this->setAjaxResult('-2','不是有效机构');
		}
		//判断管理员
        $isAdmin=user_api::isAdmin($org->userId,$this->user['uid']);
        if($isAdmin === false){			
           return $this->setAjaxResult('-3','没有权限');
        }
	}
	
	//结算数据
	public function pageSettleInfoData()
	{
		$page    = !empty($_POST['page']) ? (int)$_POST['page'] : 1;
		$length  = !empty($_POST['length']) ? (int)$_POST['length'] : 5;
		$payType = [1=>'支付宝',2=>'微信',3=>'免费',4=>'云点支付'];
		$params['objTypeStatus'] = 1;
		$params['status'] = !empty($_POST['data']['status']) ? (int)$_POST['data']['status'] : 2;
		$params['clearingId'] = !empty($_POST['data']['clearingId']) ? (int)$_POST['data']['clearingId'] : 0;
                $params['orgId']  = $orgId = empty($_POST['data']['orgId']) ? $this->orgId : (int) $_POST['data']['orgId']; 
		$params['objType'] = !empty($_POST['data']['type']) ? (int)$_POST['data']['type'] : 0;
		$resellOrgId  = !empty($_POST['data']['resellOrgId']) ? (int)$_POST['data']['resellOrgId'] : 0;                
		$promoteOrgId = !empty($_POST['data']['promoteOrgId']) ? (int)$_POST['data']['promoteOrgId'] : 0;
                
		if(!empty($_POST['data']['startTime'])){
			$params['startTime'] = $_POST['data']['startTime'];
		}
		if(!empty($_POST['data']['endTime'])){
			$params['endTime'] = $_POST['data']['endTime'];
		}
                if(!empty($resellOrgId)){
			$params['resellOrgId'] = $resellOrgId;
                        $params['objType'] = 1;
		} elseif(!empty($promoteOrgId)){
                        $params['promoteOrgId'] = $promoteOrgId;
                        $params['orgId']  = $orgId;  
                        $params['objType'] = 1;
		} else {  
                        $params['orgId']  = $orgId;  
                }
		
		if(empty($params)) return $this->setAjaxResult('-4','参数错误');
		
		//机构结算log
		//$contentRes = org_api::getUserOrderContentList($params);
		//if(empty($contentRes->data->items)) return $this->setAjaxResult("-1","failed",'data incorrect1');
		
		//机构结算订单详情
		/*foreach($contentRes->data->items as $val){
                        $contentOrderId[] = $val->fk_order_content;
		}
		*/
		$orderContentParam = [
			//'contentOrderArr' => $contentOrderId,
			'objectType'      => $params['objType'],
			'clearingId' => $params['clearingId'],
			'orgId'=>$orgId,
			'resellOrgId'=>$resellOrgId,
			'status'=>$params['status'],
		];
		if(!empty($_POST['data']['startTime'])){
			$orderContentParam['startTime'] = $_POST['data']['startTime'];
		}
		if(!empty($_POST['data']['endTime'])){
			$orderContentParam['endTime'] = $_POST['data']['endTime'];
		}
		$orderRes = org_api::getOrderInfo($orderContentParam,$page,$length);
		if(empty($orderRes->items)) return $this->setAjaxResult("-1","failed",'data incorrect2');
		
		$extArr    = array();
		$idArr = array();
		foreach($orderRes->items as $val){
			$extArr[$val->object_id] = $val->ext;
			$idArr['objIdArr'][$val->object_id] = $val->object_id;
			$idArr['extIdArr'][$val->ext] = $val->ext;
			$idArr['userIdArr'][$val->fk_user] = $val->fk_user;
			$idArr['orgIdArr'][$val->org_id] = $val->org_id;
			$idArr['orgIdArr'][$val->resell_org_id] = $val->resell_org_id;
		}
		
		if(empty($idArr)) return $this->setAjaxResult("-1","failed",'data incorrect3');
		
		//用户信息
		$userName = array();
		$userRes = user_api::listUsersByUserIds($idArr['userIdArr']);
		if(($userRes->code == 0)){
			foreach($userRes->result as $val){
				$userName[$val->pk_user] = !empty($val->real_name) ? $val->real_name : (!empty($val->name)?$val->name:'');
			}
		}
		$objIds = implode(',', $idArr['objIdArr']);
		$extIds = implode(',', $idArr['extIdArr']);
               
		//机构信息
		$orgName = array();
		$orgIdArr = array_unique($idArr['orgIdArr']);
		$orgArr = user_api::ListUserProfileByOids($orgIdArr);
                
		//课程信息
		$courseInfo = array();
		$memberInfo = array();
		if($params['objType'] == 1 || $params['objType'] == 21){
			$planParams = [
				'q' => ['course_id'=>$objIds,'class_id'=>$extIds,'deleted'=>'0,-1'],
				'f' => ['course_id','course_name','class_name','deleted','class_id'],
				'p' => 1,
				'pl'=> 500
			];
			$planRes = seek_api::seekplan($planParams);	
                        
			if(!empty($planRes->data)){
				foreach($planRes->data as $val){
					$courseInfo[$val->course_id][$val->class_id] = [
						'courseName' => $val->course_name,
						'className'  => $val->class_name,
						'deleted' => $val->deleted
					];
				}
			}
		}elseif($params['objType'] == 11){
			$memberRes = org_api::getorgmembersets(array('setId'=>$objIds));
			if(($memberRes->code == 0)){
				foreach($memberRes->result->items as $val){
					$memberInfo[$val->pk_member_set]['name'] = $val->title;
					if($extArr[$val->pk_member_set] == 1){
						$memberInfo[$val->pk_member_set]['useTime'] = '一个月';
					}elseif($extArr[$val->pk_member_set] == 2){
						$memberInfo[$val->pk_member_set]['useTime'] = '三个月';
					}elseif($extArr[$val->pk_member_set] == 3){
						$memberInfo[$val->pk_member_set]['useTime'] = '六个月';
					}elseif($extArr[$val->pk_member_set] == 4){
						$memberInfo[$val->pk_member_set]['useTime'] = '一年';
					}
				}
			}
		}	
                
		/* 
		//推广信息
		$resellData = array();
		$resellType = !empty($_POST['data']['promoteType']) ? (int)$_POST['data']['promoteType'] : 0;
		if(!empty($resellType)){ 
			if($resellType == 1){
				$resellParams['org_promote_id'] = (int)$_POST['data']['orgId'];
			}elseif($resellType = 2){
				$resellParams['org_resell_id'] = (int)$_POST['data']['orgId'];
			}
			$resellParams['type'] = $resellType;
			$resellParams['fk_order_content'] = implode(',', $contentOrderId);
			
			$resellReg = course_resell_api::getcourseResellLog(1,0,$resellParams);
			if(!empty($resellReg->data)){
				foreach($resellReg->data as $val){
					$resellData[$val->course_resell_id] = [
						'title'        => $val->title,
						'orgSubname'   => $val->org_subname,
						'userName'     => $val->user_name,
						'income'       => $val->income,
						'priceResell'  => $val->price_resell,
						'pricePromote' => $val->price_promote
					];
				}
			}
		} */
                
		$data = array();
		foreach($orderRes->items as &$val){
			$statusText = "";
			$distributeText = "";
			$distributeStatus = 0;
			$distributePrice = 0;
			if($val->promote_status==1){
				$distributeStatus = 1;
				$statusText = "线上分销";
				$distributeText = "推广支出";
				$distributePrice = ($val->price-$val->price_promote)/100;;
			}elseif($val->distribute_status==1){
				$distributeStatus = 1;
				$statusText = "线下分销";
				$distributeText = "渠道分成";
				if ($val->distribute_qudao==100860){
					$distributeText = "沃学堂渠道分成";
				}elseif ($val->distribute_qudao==100100){
					$distributeText = "和教育渠道分成";
				}elseif ($val->distribute_qudao==100000){
					$distributeText = "天翼渠道分成";
				}

				$distributePrice = $val->distribute_price/100;
			}
			$actualPrice = empty($resellOrgId)?($val->promote_status==1?$val->price_promote/100:$val->price/100):($val->price-$val->price_promote)/100;
			$actualPrice = $actualPrice-(($val->gateway_price+$val->gateway_price_resell+$val->platform_price+$val->platform_price_resell+$val->tax_price+$val->tax_price_resell+$val->distribute_price)/100);
			$price_promote = $val->price_promote/100;
			$price = $val->price/100;
			if($params['objType'] == 1 && empty($resellOrgId)){

				$data[] = [
					'courseName' => !empty($courseInfo[$val->object_id][$val->ext]['courseName']) ? $courseInfo[$val->object_id][$val->ext]['courseName'] : '',
					'className'  => !empty($courseInfo[$val->object_id][$val->ext]['className']) ? $courseInfo[$val->object_id][$val->ext]['className'] : '',
					'deleted'  => !empty($courseInfo[$val->object_id][$val->ext]['deleted']) ? $courseInfo[$val->object_id][$val->ext]['deleted'] : '',
					'orderId'    => date('Ymd',strtotime($val->create_time)).$val->fk_order,
					'createTime' => date('Y-m-d',strtotime($val->create_time)),
					'payType'    => !empty($payType[$val->pay_type]) ? $payType[$val->pay_type] : '',
					'userName'   => !empty($userName[$val->fk_user]) ? $userName[$val->fk_user] : '',
					'objectType' => $val->object_type,
					'price'      => $val->price/100,
					'priceOld'   => $val->price_old/100,
					'isFee'      => ($val->price==0) ? ' ' : ' ',
					'discountPrice' => ($val->price_old/100)-($val->price/100),
					'discountStatus' => ($val->discount_status==0) ? 1 : 0,
					'orgID'      => $val->org_id,
					'orgName'    => isset($orgArr[$val->org_id]) ? $orgArr[$val->org_id]->name : '' ,
					'resellOrgID'=> $val->resell_org_id,
					'resellOrgName' => isset($orgArr[$val->resell_org_id]) ? $orgArr[$val->resell_org_id]->name : '',
					'pricePromote'  => $actualPrice>0?$actualPrice:($val->promote_status==1?$price_promote:$price),   // 基础价 成本价
					'income'    => $val->promote_status==1?$val->price/100 - $val->price_promote/100:0,    // 分销手续费 & 总收入
					'contentRefundStatus' =>is_null($val->content_refund_status)?0:$val->content_refund_status,
					'clearingStatus' =>is_null($val->clearing_status)?-1:$val->clearing_status,
					'priceRefund' =>is_null($val->price_refund)?0:$val->price_refund/100,
					'tax_rate' => $val->tax_rate*100,
					'gateway_rate' => $val->gateway_rate*100,
					'gateway_price' => $val->gateway_price/100,
					'gateway_price_resell' => $val->gateway_price_resell/100,
					'platform_rate' => $val->platform_rate*100,
					'platform_price' => $val->platform_price/100,
					'platform_rate_resell' => $val->platform_rate_resell*100,
					'platform_price_resell' => $val->platform_price_resell/100,
					'tax_price' => $val->tax_price/100,
					'tax_rate_resell' => $val->tax_rate_resell*100,
					'tax_price_resell' => $val->tax_price_resell/100,
					'distribute_rate' => $val->distribute_rate*100,
					'distribute_price' => $val->distribute_price/100,
					'statusText' => $statusText,
					'distributeText' => $distributeText,
					'distributeStatus' => $distributeStatus,
					'distributePrice' => $distributePrice,

				];
			}elseif($params['objType'] == 11){
				$data[] = [
					'memberName' => !empty($memberInfo[$val->object_id]['name']) ? $memberInfo[$val->object_id]['name'] :'',
					//'memberPrice'=> !empty($memberInfo[$val->object_id]['price']) ? $memberInfo[$val->object_id]['price'] :'',
					'useTime'    => !empty($memberInfo[$val->object_id]['useTime']) ? $memberInfo[$val->object_id]['useTime'] :'',
					'orderId'    => date('Ymd',strtotime($val->create_time)).$val->fk_order,
					'createTime' => date('Y-m-d',strtotime($val->create_time)),
					'payType'    => !empty($payType[$val->pay_type]) ? $payType[$val->pay_type] : '',
					'userName'   => !empty($userName[$val->fk_user]) ? $userName[$val->fk_user] : '',
					'objectType' => $val->object_type,
					'price'      => $actualPrice>0?$actualPrice:$val->price/100,
					'orderPrice' => $val->price/100,
					'priceOld'   => $val->price_old/100,
					'contentRefundStatus' =>is_null($val->content_refund_status)?0:$val->content_refund_status,
					'clearingStatus' =>is_null($val->clearing_status)?-1:$val->clearing_status,
					'priceRefund' =>is_null($val->price_refund)?0:$val->price_refund/100,
					'tax_rate' => $val->tax_rate*100,
					'gateway_rate' => $val->gateway_rate*100,
					'gateway_price' => $val->gateway_price/100,
					'gateway_price_resell' => $val->gateway_price_resell/100,
					'platform_rate' => $val->platform_rate*100,
					'platform_price' => $val->platform_price/100,
					'platform_rate_resell' => $val->platform_rate_resell*100,
					'platform_price_resell' => $val->platform_price_resell/100,
					'tax_price' => $val->tax_price/100,
					'tax_rate_resell' => $val->tax_rate_resell*100,
					'tax_price_resell' => $val->tax_price_resell/100,
					'distribute_rate' => $val->distribute_rate*100
				];
			}elseif(!empty($resellOrgId) || !empty($promoteOrgId)){
				/* 课程名称，班级，订单价格，订单ID， 交易时间，支付方式，购买者 */
                /* 分销收入明细 ：推广商，总收入; 推广费用明细 ：分销商，基础价（成本价），总支出，分销手续费  */
				$data[] = [
					'title' => !empty($courseInfo[$val->object_id][$val->ext]['courseName']) ? $courseInfo[$val->object_id][$val->ext]['courseName'] : '',
					'className'  => !empty($courseInfo[$val->object_id][$val->ext]['className']) ? $courseInfo[$val->object_id][$val->ext]['className'] : '',
					'orderId'    => date('Ymd',strtotime($val->create_time)).$val->fk_order,
					'createTime' => date('Y-m-d',strtotime($val->create_time)),
					'payType'    => !empty($payType[$val->pay_type]) ? $payType[$val->pay_type] : '',
					'userName'   => !empty($userName[$val->fk_user]) ? $userName[$val->fk_user] : '',
					'objectType' => $val->object_type,
					'price'      => $val->price/100,                                        
                    'orgID'      => $val->org_id,
					'orgName'    => isset($orgArr[$val->org_id]) ? $orgArr[$val->org_id]->name : '' ,
                    'resellOrgID'=> $val->resell_org_id,
                    'resellOrgName' => isset($orgArr[$val->resell_org_id]) ? $orgArr[$val->resell_org_id]->name : '',
                    'pricePromote'  => $val->price_promote/100,   // 基础价 成本价
                    'income'    =>  $actualPrice>0?$actualPrice:($val->price/100 - $val->price_promote/100),    // 分销手续费 & 总收入
					'contentRefundStatus' =>is_null($val->content_refund_status)?0:$val->content_refund_status,
					'clearingStatus' =>is_null($val->clearing_status)?-1:$val->clearing_status,
					'priceRefund' =>is_null($val->price_refund)?0:$val->price_refund/100,
					'tax_rate' => $val->tax_rate*100,
					'gateway_rate' => $val->gateway_rate*100,
					'gateway_price' => $val->gateway_price/100,
					'gateway_price_resell' => $val->gateway_price_resell/100,
					'platform_rate' => $val->platform_rate*100,
					'platform_price' => $val->platform_price/100,
					'platform_rate_resell' => $val->platform_rate_resell*100,
					'platform_price_resell' => $val->platform_price_resell/100,
					'tax_price' => $val->tax_price/100,
					'tax_rate_resell' => $val->tax_rate_resell*100,
					'tax_price_resell' => $val->tax_price_resell/100,
					'distribute_rate' => $val->distribute_rate*100,
				];
			}
			/*
			elseif(!empty($resellData)){
				$data[] = [
					'title'        => !empty($resellData[$val->object_id]['title']) ? $resellData[$val->object_id]['title'] : '',
					'orgSubname'   => !empty($resellData[$val->object_id]['orgSubname']) ? $resellData[$val->object_id]['orgSubname'] : '',
					'userName'     => !empty($resellData[$val->object_id]['userName']) ? $resellData[$val->object_id]['userName'] : '',
					'income'       => !empty($resellData[$val->object_id]['income']) ? $resellData[$val->object_id]['income'] : '',
					'priceResell'  => !empty($resellData[$val->object_id]['priceResell']) ? $resellData[$val->object_id]['priceResell'] : '',
					'pricePromote' => !empty($resellData[$val->object_id]['pricePromote']) ? $resellData[$val->object_id]['pricePromote'] : '',
					'orderId'    => date('Ymd',strtotime($val->create_time)).$val->fk_order,
					'createTime' => date('Y-m-d',strtotime($val->create_time)),
					'payType'    => !empty($payType[$val->pay_type]) ? $payType[$val->pay_type] : '',
					'price'      => $val->price/100
				];
			}
			*/
		}
		
		return $this->setAjaxResult("1","success",$data);
	}
	
	public function setAjaxResult($code, $msg, $data=array())
	{
		return json_encode(
			array(
				'code' => $code,
				'msg'  => $msg,
				'data' => $data
			),
			JSON_UNESCAPED_UNICODE
		);
    }

	//订单改价
	public function pageUpdateOrderPrice(){
		$orderId = !empty($_POST["orderId"])?$_POST["orderId"]:0;
		$price = isset($_POST["price"])?$_POST["price"]:0;
		if(empty($orderId)||!isset($price)){
			return json_encode(array('code'=>-1,"msg"=>"请输入改后价格"));
		}
		$orderRes = order_api::getOrder(array('orderId'=>$orderId));
		$orderRes = !empty($orderRes->items[0])?$orderRes->items[0]:array();
		if(!empty($orderRes)&&$this->orgId==$orderRes->org_id&&$price!=$orderRes->price*100&&strtotime($orderRes->expiration_time)>=time()&&($orderRes->status=="paying"||$orderRes->status=="initial")){
			$logData = array(
				"orgId"=>$orderRes->org_id,
				"orderId"=>$orderId,
				"userId"=>$this->user["uid"],
				"price"=>$price,
				"priceOld"=>$orderRes->price*100,
				"status"=>1
			);
			$orderLog = order_api::addOrderLog($logData);
			if($orderLog->code==0) {
				$order = order_api::updateOrderPrice($orderId, array("price" => $price,"change_status"=>1));
				if($order->code==1){
					return json_encode(array("code"=>0,"msg"=>"操作成功"));
				}
				return json_encode(array("code"=>-2,"msg"=>"更新订单价格失败！"));
			}
			return json_encode(array("code"=>-3,"msg"=>"新增订单改价记录失败！"));
		}else{
			if($orderRes->status=="success"){
				return json_encode(array("code"=>-4,"msg"=>"订单已支付成功"));
			}elseif($price==$orderRes->price*100){
				return json_encode(array("code"=>-5,"msg"=>"订单改价前后价格一致！"));
			}else{
				return json_encode(array("code"=>-6,"msg"=>"订单已失效"));
			}
		}
	}
	//提交申请退费
	public function pageAddOrderContentRefund(){
		if(empty($_POST['orderId'])||empty($_POST['price'])||empty($_POST['refundWhy'])){
			return interface_func::setMsg(1001);
		}
		$data =array(
			"orderId"=>$_POST['orderId'],
			"price"=>$_POST['price']*100,
			"orgUserId"=>$this->user['uid'],
			"orgId"=>$this->orgId,
			"refundWhy"=>$_POST['refundWhy'],
		);
		$orgAccount = user_organization::getOrgAccountByOrgId($this->orgId);
		$orderInfo = order_api::getOrder(array("orderId"=>$_POST["orderId"],"orgId"=>$this->orgId));
		if(!empty($orderInfo->items[0])) {
			$orderInfo = $orderInfo->items[0];
			if($orderInfo->fk_clearing>0 && (empty($orgAccount->withdraw)||$orgAccount->withdraw<$data["price"])){
				return json_encode(array("code"=>-1,"msg"=>"可提现余额不足"));
			}elseif($data['orderId']!= $orderInfo->fk_order){
				return json_encode(array("code"=>-3,"msg"=>"请核对订单信息"));
			}elseif($data['price']> $orderInfo->price){
				return json_encode(array("code"=>-2,"msg"=>"退费金额不能大于订单成交金额！"));
			}
			$data["orderContentId"]=$orderInfo->pk_order_content;
			$data["userId"]=$orderInfo->fk_user;
			$data["clearingStatus"]=0;

			if($orderInfo->fk_clearing>0){
				$data["clearingStatus"] = 1;
			}
			$refund = order_api::addOrderContentRefund($data);
			return json_encode($refund);
		}
	}

	//退费记录
	public function pageRefundAjax($inPath){
		if(empty($_POST["userId"])){
			return json_encode(array("code"=>-1,"data"=>array(),"msg"=>"缺少必传参数"));
		}
		$params['userId'] = $_POST["userId"];
		$params['orgId'] = $this->orgId;
		$params['object_type'] = 1;
		$params['status'] = 2;
		$classIdArr = array();
		$orderRefund = order_api::orderList($params,1,0);
		$refundCount = 0;
		if(!empty($orderRefund["data"])){
			foreach($orderRefund["data"] as $course){
				$classIdArr[$course->ext] = $course->ext;
				if($course->content_refund_status>0){
					$refundCount += 1;
				}
			}
			if(!empty($classIdArr)){
				$params = array(
					"classId" => $classIdArr,
					"userId" => $params['userId'],
					"item" => array(
						"vt_record",
						"vt_live",
						"plan_count",
						"fk_class",
					),
					"page" => 1,
				);
				$userClassStat = stat_api::getClassUserStatByClassId($params);
				$userStat = array();
				if(!empty($userClassStat->items)){
					foreach($userClassStat->items as $stat){
						$userStat[$stat->fk_class]=$stat;
					}
				}
				foreach($orderRefund["data"] as &$course){
					if(!empty($userStat[$course->ext])){
						$course->stat["planCount"] = $userStat[$course->ext]->plan_count;

						$course->stat["vtRecord"] = self::get_stay_time($userStat[$course->ext]->vt_record);

						$course->stat["vtLive"] = self::get_stay_time($userStat[$course->ext]->vt_live);
					}else{
						$course->stat["planCount"] = 0;

						$course->stat["vtRecord"] = 0;

						$course->stat["vtLive"] = 0;
					}
				}
			}
			return json_encode(array("code"=>0,"courseCount"=>count($orderRefund["data"]),"refundCount"=>$refundCount,"data"=>$orderRefund["data"],"msg"=>"success"));
		}
		return json_encode(array("code"=>-2,"data"=>array(),"msg"=>"查询失败"));
	}

	function get_stay_time($timestamp, $is_hour = 1, $is_minutes = 1)
	{
		if(empty($timestamp) || $timestamp <= 60) {
			return null;
		}
		$remain_time = $timestamp;
		$hour = floor($remain_time / 3600);
		$hour = $hour > 0 ? $hour.'小时' : '';
		if($is_hour && $is_minutes) {
			$minutes = floor(($remain_time % 3600) / 60);
			$minutes = $minutes > 0 ? $minutes.'分钟' : '';
			return $hour.$minutes;
		}

		if($hour) {
			return $hour;
		}
	}
}
?>
