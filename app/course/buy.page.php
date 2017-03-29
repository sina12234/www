<?php
class course_buy extends STpl{
	public function pageNotify($inPath){
		$this->my_log(__LINE__, "pageNotify");
		$this->my_log(__LINE__, $_REQUEST);
		if(empty($_REQUEST['out_trade_no'])||empty($_REQUEST['trade_no'])){
			//TODO
			return "参数错误";
		}
		//计算得出通知验证结果
		require_once(ROOT_LIBS."/alipay/alipay.config.php");
		require_once(ROOT_LIBS."/alipay/lib/alipay_notify.class.php");
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		//$unique_order_id = $_REQUEST['out_trade_no'];
		$out_trade_id = $_REQUEST['out_trade_no'];
		$ret = $this->dealOrder($out_trade_id,$_REQUEST,$verify_result, "zhifubao");
		$this->my_log(__LINE__, $ret);
		if($ret) {
			$this->my_log(__LINE__);
			echo "success";		//请不要修改或删除
		}else {
			$this->my_log(__LINE__);
			echo "fail";
		}
	}
	/**
	 * 支付宝返回
	 */
	public function pageReturn($inPath){
		$this->my_log(__LINE__,"pageReturn");
		$this->my_log(__LINE__,$_REQUEST);
		if(empty($_REQUEST['out_trade_no'])||empty($_REQUEST['trade_no'])){
			//TODO
			return "参数错误";
		}
		require_once(ROOT_LIBS."/alipay/alipay.config.php");
		require_once(ROOT_LIBS."/alipay/lib/alipay_notify.class.php");
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyReturn();
		$this->my_log(__LINE__,$verify_result);
		//$unique_order_id = $_REQUEST['out_trade_no'];
		$out_trade_id = $_REQUEST['out_trade_no'];
		//$ret = $this->dealOrder($unique_order_id,$_REQUEST,$verify_result);
		$ret = $this->dealOrder($out_trade_id,$_REQUEST,$verify_result, "zhifubao");
		$this->my_log(__LINE__,$ret);
		if($ret) {
			$this->my_log(__LINE__);
			echo "success";		//请不要修改或删除
		}else {
			$this->my_log(__LINE__);
			echo "fail";
		}
		$orderInfo = order_api::getOrderByOutTradeId($out_trade_id);
		$uniqueOrderId = $orderInfo['unique_order_id'];
		$type = 'member';
		if (!empty($orderInfo['orderContent'])) {
			foreach ($orderInfo['orderContent'] as $item) {
				if ($item['object_type'] == 1) {
					$type = 'course';
				}elseif($item['object_type'] == 21){
					$type = 'sms';
				}
				// ...
			}
		}
		return $this->redirect("/order.main.pay/{$type}/{$uniqueOrderId}?callback=1");
	}
	/**
	 * 微信支付NativeCall
	 */
	public function pageWeixinNativeCall($inPath){
		$this->my_log(__LINE__, "pageWeixinNativeCall start");
		$postData = utility_net::getPostData();
		if(empty($postData)){
			$this->my_log(__LINE__, $postData);
			return ;
		}
		$postData = weixin_api::xmlToArray($postData);
		$this->my_log(__LINE__, $postData);
		$checkSign = weixin_api::checkSign($postData);
		$this->my_log(__LINE__, $checkSign);
		if(!$checkSign){
			return weixin_api::createXml(array("return_code"=>"FAIL","return_msg"=>"签名失败"));
		}else{
			$out_trade_id = $postData['product_id'];
			//$order_info = order_api::getOrderAndContentByOutTradeId($out_trade_id); // 老板方法,新版测试无问题之后，删掉2016-04-28 18:47
            $orderInfo = order_api::getOrderByOutTradeId($out_trade_id);
			$this->my_log(__LINE__, $orderInfo);
            $title = $this->getBodyTitle($orderInfo);
			$this->my_log(__LINE__, $title);
			if(empty($orderInfo)){
				$ret=weixin_api::createXml(array("return_code"=>"FAIL","return_msg"=>"订单失效"));
				return $ret;
				return false;
			}
			//$course_info = course_api::getCourseone($order_info->course_id);edit:2016-04-28 18:47
			$ret = array(
				"body"=>$title,//$course_info->title edit:2016-04-28 18:47
				//"out_trade_no"=>$order_info->unique_order_id,
				"out_trade_no"=>$orderInfo['out_trade_id'],//$order_info->out_trade_id,edit:2016-04-28 18:47
				"total_fee"=>$orderInfo['price'] * 100,//$order_info->price*100,//变成分 edit:2016-04-28 18:47
				"notify_url"=>"http://".$_SERVER['HTTP_HOST']."/course.buy.weixinReturn",
				"trade_type"=>"NATIVE",
				//"product_id"=>$order_info->unique_order_id,
				"product_id"=>$orderInfo['out_trade_id'],//$order_info->out_trade_id,//edit:2016-04-28 18:47
			);
			$this->my_log(__LINE__, $ret);
			$prepay_id = weixin_api::getPrepayId($ret,$error);
			$this->my_log(__LINE__, $prepay_id);
			if(empty($prepay_id)){
				return weixin_api::createXml(array("return_code"=>"FAIL","return_msg"=>$error['err_code_des']));
			}
			//if(!empty($order_info->callback_status) && !empty($order_info->third_order_id)){
            if(!empty($orderInfo['callback_status']) && !empty($orderInfo['third_order_id'])){//edit:2016-04-28 18:47
				//订单已经做过处理
				$this->my_log(__LINE__);
				return weixin_api::createXml(array(
							"return_code"=>"SUCCESS",
							"result_code"=>"FAIL",
							"err_code_des"=>"订单已经支付",
							"prepay_id"=>$prepay_id)
						);
			}
			//if($order_info->status=="expired"){
            if($orderInfo['status']=="expired"){//edit:2016-04-28 18:47
				$this->my_log(__LINE__);
				return weixin_api::createXml(array(
							"return_code"=>"SUCCESS",
							"result_code"=>"FAIL",
							"err_code_des"=>"订单已经过期",
							"prepay_id"=>$prepay_id)
						);
			}
			//if($order_info->status=="success"){
            if($orderInfo['status']=="success"){//edit:2016-04-28 18:47
				$this->my_log(__LINE__);
				return weixin_api::createXml(array(
							"return_code"=>"SUCCESS",
							"result_code"=>"FAIL",
							"err_code_des"=>"订单已经支付",
							"prepay_id"=>$prepay_id)
						);
			}
			//if($order_info->status=="deleted"){
            if($orderInfo['status']=="deleted"){//edit:2016-04-28 18:47
				$this->my_log(__LINE__);
				return weixin_api::createXml(array(
							"return_code"=>"SUCCESS",
							"result_code"=>"FAIL",
							"err_code_des"=>"订单不存在",
							"prepay_id"=>$prepay_id)
						);
			}
			$ret["return_code"]="SUCCESS";
			$ret["result_code"]="SUCCESS";
			$ret["prepay_id"]="$prepay_id";
			$this->my_log(__LINE__,$ret);
			$ret = weixin_api::createXml($ret);
			$this->my_log(__LINE__,$ret);
			return $ret;
		}

	}
	/**
	 * 微信支付-公众号/网站扫码支付
	 */
	public function my_log($line,$msg=""){
		
		if(is_array($msg) || is_object($msg)){
			$msg = var_export($msg,true);
		}
		error_log('['.date("Y-m-d H:i:s")."] [{$line}] {$msg}\n", 3 ,"/tmp/course.buy.".date("Ymd").".log");
	}
	public function pageWeixinReturn($inPath){
		$this->my_log(__LINE__, "pageWeixinReturn");
		$postData = utility_net::getPostData();
		//if(empty($postData)){
		//	return ;
		//}
		$this->my_log(__LINE__, $postData);
		$postData = weixin_api::xmlToArray($postData);
		$this->my_log(__LINE__, $postData);
		$checkSign = weixin_api::checkSign($postData);
		$this->my_log(__LINE__, $checkSign);
		if(!$checkSign){
			$this->my_log(__LINE__);
			return weixin_api::createXml(array("return_code"=>"FAIL","return_msg"=>"签名失败"));
		}
		if(!empty($postData)){
			if($postData['return_code']=="FAIL" || $postData['result_code']=="FAIL"){
				$this->my_log(__LINE__);
				return ;
			}
			//$unique_order_id = $postData['out_trade_no'];
			$out_trade_id = $postData['out_trade_no'];
			$ret = $this->dealOrder($out_trade_id,array("trade_no"=>$postData['transaction_id'],"trade_status"=>$postData['result_code']),true, "weixin");
			$this->my_log(__LINE__,$ret);
			if($ret) {
				$this->my_log(__LINE__);
				return weixin_api::createXml(array("return_code"=>"SUCCESS"));
			}else {
				$this->my_log(__LINE__);
				return weixin_api::createXml(array("return_code"=>"FAIL"));
			}
		}
	}

    /**
     * ＠desc dealOrder new
     *
     * @param $out_trade_id
     * @param $params
     * @param $verify_result
     * @param $pay_type
     * @return bool
     */
    private function dealOrder($out_trade_id, $params, $verify_result, $pay_type)
    {
		$this->my_log(__LINE__, $out_trade_id);
        $orderInfo = order_api::getOrderByOutTradeId($out_trade_id);
		$this->my_log(__LINE__, $orderInfo);
        if (empty($orderInfo)) return false;

        if (!empty($orderInfo['callback_status']) && !empty($orderInfo['third_order_id'])) {
			$this->my_log(__LINE__);
            return true;
            //订单已经做过处理
        }

        $orderId                      = $orderInfo['pk_order'];
        $update['callback_status']     = $params['trade_status'];
        $update['third_return_params'] = SJson::encode($params);
        $update['third_order_id']      = $params['trade_no'];

        $order_status = "fail";
        if ($verify_result) {
			$this->my_log(__LINE__);
            $order_status = "success";
            if (!empty($orderInfo['orderContent'])) {
				$this->my_log(__LINE__);
                foreach ($orderInfo['orderContent'] as $item) {
					$this->my_log(__LINE__,$item);
                    $r = $this->_handleSuccessOrderLater($item,$pay_type);
					$this->my_log(__LINE__,$r);
                }
            }
        } else {
			$this->my_log(__LINE__);
        }

        $update['status']   = $order_status;
        $update['pay_type'] = $pay_type;

        // 记录订单日志 2015-12-02 15:39:36
        log_userAgent::addUserAgentInfo(
            [
                'status'  => $order_status,
                'payType' => $pay_type,
                'source'  => 1, // todo
                'orderId' => $orderId
            ]
        );
        $resUpdate = order_api::updateOrder($orderId, $update);
        if ($resUpdate === false) {
			$this->my_log(__LINE__);
        } else {
			$this->my_log(__LINE__);
        }
		$this->my_log(__LINE__);

        return $resUpdate;
    }

    private function _handleSuccessOrderLater($orderContentInfo,$pay_type)
    {
		if($orderContentInfo['object_type']==21){
			$smsData = new stdClass();
			$smsData->org_id = $orderContentInfo['org_id'];
			$smsData->fee = $orderContentInfo['price'];
			$this->my_log(__LINE__,$smsData);
			$res = user_organization::addSmsMoney($smsData);
			$this->my_log(__LINE__,$res);
		}else{
			$orderId = $orderContentInfo['fk_order'];
			$gateway_price = 0;
			$gateway_price_resell = 0;
			$platform_price = 0;
			$platform_price_resell = 0;
			$tax_price = 0;
			$tax_price_resell = 0;
			$distribute_price = 0;
			$price=$orderContentInfo["price"];
			$resellPrice = 0;
			if($orderContentInfo["promote_status"]==1){
				$resellPrice = $orderContentInfo["price"]-$orderContentInfo["price_promote"];
				$price=$orderContentInfo["price_promote"];
			}
			//网关扣费
			if ("zhifubao" == $pay_type) {
				$update["gateway_rate"] = 0;
				$gateway = order_api::getGatewayById(1);
				if (!empty($gateway->rate) && $gateway->rate > 0) {
					$update["gateway_rate"] = $gateway->rate;
					$gateway_price = round(($price * $gateway->rate));
					$update["gateway_price"] = $gateway_price;
					$price -= $gateway_price;
					if ($resellPrice > 0) {
						$gateway_price_resell = round(($resellPrice * $gateway->rate));
						$update["gateway_price_resell"] = $gateway_price_resell;
						$resellPrice -= $gateway_price_resell;
					}
				}
				//return 1;
			} elseif ("weixin" == $pay_type) {
				$update["gateway_rate"] = 0;
				$gateway = order_api::getGatewayById(2);
				if (!empty($gateway->rate) && $gateway->rate > 0) {
					$update["gateway_rate"] = $gateway->rate;
					$gateway_price = round(($price * $gateway->rate));
					$update["gateway_price"] = $gateway_price;
					$price -= $gateway_price;
					if ($resellPrice > 0) {
						$gateway_price_resell = round(($resellPrice * $gateway->rate));
						$update["gateway_price_resell"] = $gateway_price_resell;
						$resellPrice -= $gateway_price_resell;
					}
				}
				//return 2;
			} elseif ("free" == $pay_type) {
				$update["gateway_rate"] = 0;
				$gateway = order_api::getGatewayById(3);
				if (!empty($gateway->rate) && $gateway->rate > 0) {
					$update["gateway_rate"] = $gateway->rate;
					$gateway_price = round(($price * $gateway->rate));
					$update["gateway_price"] = $gateway_price;
					$price -= $gateway_price;
					if ($resellPrice > 0) {
						$gateway_price_resell = round(($resellPrice * $gateway->rate));
						$update["gateway_price_resell"] = $gateway_price_resell;
						$resellPrice -= $gateway_price_resell;
					}
				}
				//return 3;
			} elseif (" " == $pay_type) {
				$update["gateway_rate"] = 0;
				$gateway = order_api::getGatewayById(0);
				if (!empty($gateway->rate) && $gateway->rate > 0) {
					$update["gateway_rate"] = $gateway->rate;
					$gateway_price = round(($price * $gateway->rate));
					$update["gateway_price"] = $gateway_price;
					$price -= $gateway_price;
					if ($resellPrice > 0) {
						$gateway_price_resell = round(($resellPrice * $gateway->rate));
						$update["gateway_price_resell"] = $gateway_price_resell;
						$resellPrice -= $gateway_price_resell;
					}
				}
				//return 0;
			} elseif ("inapp" == $pay_type) {
				$update["gateway_rate"] = 0;
				$gateway = order_api::getGatewayById(4);
				if (!empty($gateway->rate) && $gateway->rate > 0) {
					$update["gateway_rate"] = $gateway->rate;
					$gateway_price = round(($price * $gateway->rate));
					$update["gateway_price"] = $gateway_price;
					$price -= $gateway_price;
					if ($resellPrice > 0) {
						$gateway_price_resell = round(($resellPrice * $gateway->rate));
						$update["gateway_price_resell"] = $gateway_price_resell;
						$resellPrice -= $gateway_price_resell;
					}
				}
				//return 4;
			} elseif ("unipay" == $pay_type) {
				$update["gateway_rate"] = 0.3;
				$gateway = order_api::getGatewayById(5);
				if (!empty($gateway->rate) && $gateway->rate > 0) {
					$update["gateway_rate"] = $gateway->rate;
				}
				$gateway_price = round(($price * $update["gateway_rate"]));
				$update["gateway_price"] = $gateway_price;
				$price -= $gateway_price;
				if ($resellPrice > 0) {
					$gateway_price_resell = round(($resellPrice * $update["gateway_rate"]));
					$update["gateway_price_resell"] = $gateway_price_resell;
					$resellPrice -= $gateway_price_resell;
				}
				//return 5;
			} else {
				$update["gateway_rate"] = 0;
				$gateway = order_api::getGatewayById(-1);
				if (!empty($gateway->rate) && $gateway->rate > 0) {
					$update["gateway_rate"] = $gateway->rate;
					$gateway_price = round(($price * $gateway->rate));
					$update["gateway_price"] = $gateway_price;
					$price -= $gateway_price;
					if ($resellPrice > 0) {
						$gateway_price_resell = round(($resellPrice * $gateway->rate));
						$update["gateway_price_resell"] = $gateway_price_resell;
						$resellPrice -= $gateway_price_resell;
					}
				}
				//return -1;
			}
			//开票税点与平台分成
			$orgId = $orderContentInfo['org_id'];
			if ($orderContentInfo["promote_status"] == 1) {
				$resellOrgId = $orderContentInfo['resell_org_id'];
				$resellOrgAccount = user_organization::getOrgAccountByOrgId($resellOrgId);
				//分销平台分成
				$update["platform_rate_resell"] = 0;
				if (!empty($resellOrgAccount->separate_rate) && $resellOrgAccount->separate_rate > 0) {
					$update["platform_rate_resell"] = $resellOrgAccount->separate_rate;
				}
				$platform_price_resell = round(($resellPrice * $update["platform_rate_resell"]));
				$update["platform_price_resell"] = $platform_price_resell;
				$resellPrice -= $platform_price_resell;
				//分销开票税点
				$update["tax_rate_resell"] = 0;
				if (!empty($resellOrgAccount->tax_rate) && $resellOrgAccount->tax_rate > 0) {
					$update["tax_rate_resell"] = $resellOrgAccount->tax_rate;
				}
				$tax_price_resell = round(($resellPrice * $update["tax_rate_resell"]));
				$update["tax_price_resell"] = $tax_price_resell;
				$resellPrice -= $tax_price_resell;
			}
			//渠道分成
			$update["distribute_rate"] = 0;
			$distributePrice = 0;
			$orgAccount = user_organization::getOrgAccountByOrgId($orgId);
			//平台分成
			$update["platform_rate"] = 0;
			if (!empty($orgAccount->separate_rate) && $orgAccount->separate_rate > 0 && $orderContentInfo["distribute_status"] == 0) {
				$update["platform_rate"] = $orgAccount->separate_rate;
			}
			if (!empty($orderContentInfo["distribute_status"]) && $orderContentInfo["distribute_status"] == 1 && $orderContentInfo['object_type'] == 1) {
				$courseId = $orderContentInfo['object_id'];
				$distribute = order_api::getDistributeByCourseId($courseId);
				if (!empty($distribute->rate) && $distribute->rate >= 0) {
					$distributePrice = round($price * $distribute->rate);
					$update["distribute_rate"] = 0.8 - $distribute->rate;
					$distribute_price = round(($price * $update["distribute_rate"]));
					$update["distribute_price"] = $distribute_price;
					$update["platform_rate"] = 0.2;
				}
			}
			$platform_price = round(($price * $update["platform_rate"]));
			$update["platform_price"] = $platform_price;
			$price -= ($distribute_price + $platform_price);
			//开票税点
			$update["tax_rate"] = 0;
			if (!empty($orgAccount->tax_rate) && $orgAccount->tax_rate > 0) {
				$update["tax_rate"] = $orgAccount->tax_rate;
			}
			$tax_price = round(($price * $update["tax_rate"]));
			$update["tax_price"] = $tax_price;
			$price -= $tax_price;
			$resUpdate = order_api::updateOrder($orderId, $update);

			if ($orderContentInfo['distribute_status'] == 1) {
				$orderContentInfo['object_type'] = 1315;
			}
			if ($orderContentInfo['promote_status'] == 1) {
				$orderContentInfo['object_type'] = 1314;
			}

			switch ($orderContentInfo['object_type']) {
				case 11: //会员订单
					$this->my_log(__LINE__);
					$res = $this->regMember($orderContentInfo);
					break;
				case 1314: //线上分销订单
					$res = $this->resellCourseReg($orderContentInfo, $price, $resellPrice);
					break;
				case 1315: //线下分销订单
					$res = $this->distributeCourseReg($orderContentInfo, $price, $distributePrice);
					break;
				default:
					// 课程订单
					$this->my_log(__LINE__);
					$res = $this->courseReg($orderContentInfo);
					break;
			}

		}
		return $res;
    }

    private function courseReg($contentInfo)
    {
		$this->my_log(__LINE__);
        if (empty($contentInfo['fk_user'])) return false;
		$orgInfo=org_api::getOrgInfo($contentInfo['org_id']);

        $regData = [
            'uid'        => $contentInfo['fk_user'],
            'course_id'  => $contentInfo['object_id'],
            'class_id'   => $contentInfo['ext'],
            //'user_owner' => $contentInfo['org_id'],
            'user_owner' => $orgInfo->user_owner_id ,
            'status'     => 1
        ];
		$this->my_log(__LINE__);
        $regStatus = course_api::addregistration($regData);
		if($regStatus===true){
			$info = array(
				"userId"=>$contentInfo['fk_user'],
				"courseId"=>$contentInfo['object_id'],
				"orgId"=>$contentInfo['org_id'],
			);
			user_api::changeUserLevelAndScore($contentInfo['fk_user'],'APPLY');
			user_organizationStudent_api::addOrganizationStudent($info);
		}
		$this->my_log(__LINE__,$regData);
		$this->my_log($regStatus);
        if ($regStatus === false) {
			$this->my_log(__LINE__);
        } else {
			$this->my_log(__LINE__);
        }

        return true;
    }

    private function regMember($contentInfo)
    {
        if (empty($contentInfo['fk_user'])) return false;
		
        $memberData = [
            'fk_user'          => $contentInfo['fk_user'],
			'org_id'           => $contentInfo['org_id'],
            'setId'            => $contentInfo['object_id'],
            'last_type'        => 1,
            'type'             => 1,
            'member_status'    => 1,
            'status'           => 1,
            'fk_order_content' => $contentInfo['pk_order_content'],
            'orderId'          => $contentInfo['fk_order'],
            'price_type'       => !empty(order_api::$priceType[$contentInfo['ext']])
                                    ? order_api::$priceType[$contentInfo['ext']]
                                    : 30,
        ];
		$this->my_log(__LINE__,$memberData);
        $regStatus = org_member_api::addMember($memberData);
		$this->my_log(__LINE__,$regStatus);
        if ($regStatus === false) {
			return false;
        }

        return true;
    }

    private function resellCourseReg($contentInfo,$price,$resellPrice)
    {
        if (empty($contentInfo['fk_user'])) return false;
        $orgInfo = org_api::getOrgInfo($contentInfo['org_id']);

        $regData = [
            'uid'        => $contentInfo['fk_user'],
            'course_id'  => $contentInfo['object_id'],
            'class_id'   => $contentInfo['ext'],
            'user_owner' => $orgInfo->user_owner_id,
            'status'     => 1,
            'source'     => 3
        ];

        $regStatus = course_api::addregistration($regData);
		if($regStatus===true){
			$info = array(
				"userId"=>$contentInfo['fk_user'],
				"courseId"=>$contentInfo['object_id'],
				"orgId"=>$contentInfo['org_id'],
			);
			user_api::changeUserLevelAndScore($contentInfo['fk_user'],'APPLY');
			user_organizationStudent_api::addOrganizationStudent($info);
		}
        if ($regStatus === false) return false;

        //记录分销日志t_course_resell_log
        $resellLogData = [
            'courseResellId' => $contentInfo['object_id'],
            'resellOrgId'    => $contentInfo['resell_org_id'],
            'promoteOrgId'   => $contentInfo['org_id'],
            'userId'         => $contentInfo['fk_user'],
            'orderContentId' => $contentInfo['pk_order_content'],
            'orderId'        => $contentInfo['fk_order'],
            'priceResell'    => $contentInfo['price'],
            'pricePromote'   => $contentInfo['price_promote'],
        ];
        //if (course_resell_api::addResellLog($resellLogData) === false) return false;
		
		course_resell_api::addResellLog($resellLogData);
		
        //update t_course_resell order_count
		//$resellPrice = $contentInfo['price'] - $contentInfo['price_promote'];
        course_resell_api::updateResellOrderNum(
            $contentInfo['object_id'],
            $contentInfo['resell_org_id'],
            $resellPrice
        );

        //update t_course_promote order_count/income
        course_resell_api::updatePromoteOrderNum($contentInfo['object_id'], $price);

		//check course is max
		$courseReg = interface_courseApi::getCourseBasic($contentInfo['object_id']);
		if(!empty($courseReg)){
			if($courseReg['max_user'] <= $courseReg['user_total']){
				$orgInfo = user_organization::getOrgByOwner($this->orgOwner);
				$msgData = [
					'title'   => $courseReg['title'],
					'subname' => $orgInfo->subname
				];
				course_resell_api::updatePromoteTypeAndSetMsg($contentInfo['object_id'], 'courseMaxUser', $msgData);
			}
		}
		
        return true;
    }

	private function distributeCourseReg($contentInfo,$price,$distributePrice)
	{
		if (empty($contentInfo['fk_user'])) return false;
		$orgInfo = org_api::getOrgInfo($contentInfo['org_id']);
		$regData = [
			'uid'        => $contentInfo['fk_user'],
			'course_id'  => $contentInfo['object_id'],
			'class_id'   => $contentInfo['ext'],
			'user_owner' => $orgInfo->user_owner_id,
			'status'     => 1,
			'source'     => 3
		];
		$regStatus = course_api::addregistration($regData);
		if ($regStatus === false) return false;
		if($regStatus===true){
			$info = array(
				"userId"=>$contentInfo['fk_user'],
				"courseId"=>$contentInfo['object_id'],
				"orgId"=>$contentInfo['org_id'],
			);
			user_api::changeUserLevelAndScore($contentInfo['fk_user'],'APPLY');
			user_organizationStudent_api::addOrganizationStudent($info);course_distribute_api::updateDistribute($contentInfo['object_id'],array("0"=>"succ_order_count=succ_order_count+1","1"=>"enroll_count=enroll_count+1","2"=>"income=income+".$price));
			order_api::UpdateQudaoUserCodeBycourseIdAndQudaoUserIdAndCode(array('fk_course'=>$contentInfo['object_id'],'fk_qudao_user'=>$contentInfo['distribute_qudao_user'],'code'=>$contentInfo['distribute_qudao_code'],'income'=>$distributePrice,'enroll_count'=>1,'succ_order_count'=>1));
		}
		return true;
	}

    private function getBodyTitle($orderInfo)
    {
        $title = '';
        if (!empty($orderInfo['orderContent'])) {
            foreach ($orderInfo['orderContent'] as $item) {
                $objectId[$item['object_type']][] = $item['object_id'];
            }
        }
		$this->my_log(__LINE__,$objectId);

        if (!empty($objectId[11])) {// 会员订单
            $setIdStr = implode(',', $objectId[11]);
            $res      = org_api::getorgmembersets(['setId' => $setIdStr]);
			$this->my_log(__LINE__,$res);
            if (!empty($res->result->items)) {
                foreach ($res->result->items as $v) {
                    $titleName[$v->pk_member_set] = $v->title;
                }
                $title = implode(',', $titleName);
            }
        } else {
            if (!empty($objectId[1][0])) {
                $res = interface_courseApi::getCourseBasic($objectId[1][0]);
				$this->my_log(__LINE__,$res);
                if (!empty($res)) $title = $res['title'];
            }
        }
		$this->my_log(__LINE__,$title);

        return $title;
    }

	public function pageXiaoWoNotify(){
		require_once(ROOT_LIBS . "/unipay/xiaoWoUnipay.class.php");
		$serviceid 		= !empty($_GET['serviceid']) ? $_GET['serviceid'] : '';
		$payService 	= new UnicomPayService();
		if ($serviceid 	== 'validateorderid') {
			$request 		= UnicomUtils::getRequestBean();
			$orderid 		= $request['orderid'];
			$signMsg 		= $request['signMsg'];
			$usercode 		= $request['usercode'];
			$provinceid 	= $request['provinceid'];
			$cityid 		= $request['cityid'];
			$mySign 		= md5("orderid=${orderid}&Key=" . App::$KEY);
			$response 		= array();
			$jid 			= (int)$request['orderid'];
			//验证签名是否一致
			//var_dump($mySign)."<br/>".var_dump($signMsg);die;	
			if (strcasecmp($mySign, $signMsg) == 0) {
				//$orderStrlen	= strlen($request['orderid']);
				//$distId 		= sprintf("%0".$orderStrlen."d", $orderid);
				//判断订单是否有效
				
				$ret = utility_services::call("/order/feeorder/GetOrderOneInfoByOrderId",array('fk_order'=>$jid));
				//echo "<pre>";print_r($ret);die;
				/*
				 * 如果通过校验，将ifpasswd设置为true
				 * 通过校验的含义为，待校验的订单在开发者系统中为有效订单，可以继续支付
				 */
				if(!empty($ret)&&(isset($ret->status)&&($ret->status=="0" || $ret->status=="1"))){
					$ifpasswd = true; 
				}else{
					$ifpasswd = false;
				}
				if ($ifpasswd) {
					$serviceid 		= "";
					$feename 		= "";
					$payfee 		= 0;
					$ordertime 		= date("YmdHis");
					$gameaccount 	= "";
					$macaddress 	= "";
					$ipaddress 		= "";
					$imei 			= "";
					$appversion 	= "";
					//0-验证成功 1-验证失败，必填
					$response["checkOrderIdRsp"] = '0';
					//应用名称，必填
					$response["appName"] = App::$APP_NAME;
					//计费点名称
					$response["feename"] = $feename;
					//计费点金额，单位分
					$response["payfee"]  = $payfee;
					//应用开发商名称，必填
					$response["appdeveloper"] = App::$APP_DEVELOPER;
					//游戏账号，长度<=64，联网支付必填
					$response["gameaccount"] = $gameaccount;
					//MAC地址去掉冒号，联网支付必填，单机尽量上报
					$response["macaddress"] = $macaddress;
					//沃商店应用id，必填
					$response["appid"] = App::$APP_ID;
					//IP地址，去掉点号，补零到每地址段3位, 如：192168000001，联网必填，单机尽量
					$response["ipaddress"] = $ipaddress;
					//沃商店计费点，必填
					$response["serviceid"] = $serviceid;
					//渠道ID，必填
					$response["channelid"] = App::$CHANNEL_ID;
					//沃商店CPID，必填
					$response["cpid"] 	   = App::$CPID;
					//订单时间戳，14位时间格式，联网必填，单机尽量
					$response["ordertime"] = $ordertime;
					//设备标识，联网必填，单机尽量上报
					$response["imei"] 	   = $imei;
					//应用版本号，必填
					$response["appversion"] = $appversion;
				}else{
					$response['checkOrderIdRsp'] = '1';
				}				
			} else {
				$response['checkOrderIdRsp'] = '1';
			}
			return UnicomUtils::toResponse($response, "paymessages");
		} else if (is_null($serviceid)===false) {//支付通知
			//订单是否被处理

        $fullyProcessed = false;
        
        //解析http请求体
        $request 		= UnicomUtils::getRequestBean();
        //cp订单号
        $orderid 		= $request["orderid"];
		$rid 	 		= (int)$request["orderid"];
		
        //订单时间
        $ordertime 		= $request["ordertime"];
        //沃商店cpid
        $cpid 			= $request["cpid"];
        //应用ID
        $appid 			= $request["appid"];
        //渠道ID
        $fid 			= $request["fid"];
        //计费点ID
        $consumeCode 	= $request["consumeCode"];
        //支付金额，单位分
        $payfee 		= $request["payfee"];
        //0-沃支付，1-支付宝，2-VAC支付，3-神州付 ...
        $payType 		= $request["payType"];
        //支付结果，0代表成功，其他代表失败
        $hRet 			= $request["hRet"];
        //状态码
        $status 		= $request["status"];
        //签名 MD5(orderid=XXX&ordertime=XXX&cpid=XXX&appid=XXX&fid=XXX&consumeCode=XXX&payfee=XXX&payType=XXX&hRet=XXX&status=XXX&Key=XXX)
        $signMsg 		= $request["signMsg"];
        $payInfo 		= json_encode($request);
       //校验签名是否正确
        $mySign = md5("orderid=${orderid}&ordertime=${ordertime}&cpid=${cpid}&appid=${appid}&fid=${fid}&consumeCode=${consumeCode}&payfee=${payfee}&payType=${payType}&hRet=${hRet}&status=${status}&Key=" . App::$KEY);
		//var_dump($mySign)."<br/>".var_dump($signMsg);die;
		$owner = user_organization::subdomain();
			if (strcasecmp($mySign, $signMsg) == 0) {
					$ret = utility_services::call("/order/feeorder/GetOrderOneInfoByOrderId",array('fk_order'=>$rid));
					$mq					 = array();
					$mq['fk_user']    	 = !empty($ret->fk_user) ? $ret->fk_user : 0;
					$mq['org_id']        = !empty($ret->org_id) ? $ret->org_id : 0;
					$mq['setId']         = !empty($ret->ext) ? $ret->ext : 0;
					$mq['last_type']     = 1;//付费购买
					$mq['type']          = 1;
					$mq['member_status'] = 1;//正常
					$mq['status']        = 1;//启用
					$mq['pk_order_content'] =  !empty($ret->ext) ? $ret->ext : 0;
					$mq['fk_order']      = !empty($ret->pk_order) ? $ret->pk_order : 0;
					$mq['price_type']    = 1;
					$mq['object_id']     = !empty($ret->object_id) ? $ret->object_id : 0;
					$mq['ext']     		 = !empty($ret->ext) ? $ret->ext : 1;
					if ("0" == $hRet&&!empty($ret->object_type)&&$ret->object_type=="1") {
						//添加支付成功逻辑
						$updateOrderData = [
												'price'    				=> 0,
												'pay_type' 				=> 'unipay',
												'status'   				=> 'success',
												'third_return_params'	=> $payInfo,
												'callback_status'		=> 'SUCCESS',
												'third_order_id'		=> $orderid
											];
						$ret 			 = order_api::updateOrder($rid, $updateOrderData);								
						$this->courseReg($mq);
					}if ("0" == $hRet&&!empty($ret->object_type)&&$ret->object_type=="11"&&$ret->status=='2') {
						//续费逻辑
						$data[] = [
									'orgId'        => $ret->org_id,
									'userId'       => $ret->fk_user,
									'objectId'     => $ret->object_id,
									'objectType'   => $ret->object_type,
									'price'        => $ret->price,
									'priceOld'     => $ret->price_old,
									'ext'          => $ret->ext,
									'pricePromote' => !empty($resellRes['pricePromete']) ? $resellRes['pricePromete'] : 0,
									'resellOrgId'  => !empty($resellRes['resellOrgId']) ? $resellRes['resellOrgId'] : 0,
									'promoteStatus'=> !empty($resellRes) ? 1 : 0
								];	
						//生成订单
						$order = order_api::addOrder($ret->fk_user,$data,$disStatus=0);
						$updateOrderData = [
												'price'    				=> 0,
												'pay_type' 				=> 'unipay',
												'status'   				=> 'success',
												'third_return_params'	=> $payInfo,
												'callback_status'		=> 'SUCCESS',
												'third_order_id'		=> $orderid
											];
						//变更状态
						$ret 			  = order_api::updateOrder($rid, $updateOrderData);
						$this->regMember($mq);
					}if ("0" == $hRet&&!empty($ret->object_type)&&$ret->object_type=="11"&&$ret->status=='0') {
						//添加支付成功逻辑
						$updateOrderData = [
												'price'    				=> 0,
												'pay_type' 				=> 'unipay',
												'status'   				=> 'success',
												'third_return_params'	=> $payInfo,
												'callback_status'		=> 'SUCCESS',
												'third_order_id'		=> $orderid
											];
						order_api::updateOrder($rid, $updateOrderData);
						$this->regMember($mq);
					}
				$fullyProcessed = true;			
            
			}
		}

			if (!$fullyProcessed) {
				header("HTTP/1.0 400 Bad Request");
			}

			echo UnicomUtils::toResponse($fullyProcessed ? 1 : 0, "callbackRsp");
		
	}
	
}
