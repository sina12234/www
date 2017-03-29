<?PHP

class order_main extends STpl
{
	private $userId;
	public function __construct()
	{
		//如果没有登陆到登陆界面
        $this->userId = user_api::getLoginUid();
        if(empty($this->userId)){
            $this->redirect("/site.main.login");
        } 	
	}

	//会员购买详情
	public function pageMemberInfo($inPath)
	{		
		if(empty($inPath[3])){
			$this->redirect('/site.main.404');
		}
		
		$setId = (int)$inPath[3];
		$memberInfo = org_member_api::getMemberSetInfo($setId);
		if(empty($memberInfo)){
			$this->redirect('/member.list');
		}
		$userMember = user_organization::checkMemberByUidAndSetId($this->userId, $setId);
		$memberInfo['time'] = date('Y-m-j', time());
		//新开通
		$memberInfo['openType'] = true;
		//续费
		if(!empty($userMember) && (time() < strtotime($userMember->end_time)) )  {
			$memberInfo['time'] = date('Y-m-j',strtotime($userMember->end_time));
			$memberInfo['openType'] = false;
		}
		if($memberInfo['price_30'] != 0){
			$memberInfo['type'][1] = array('price'=>$memberInfo['price_30']/100,'day'=>30);
		}
		if($memberInfo['price_90'] != 0){
			$memberInfo['type'][2] = array('price'=>$memberInfo['price_90']/100,'day'=>90);
		}
		if($memberInfo['price_180'] != 0){
			$memberInfo['type'][3] = array('price'=>$memberInfo['price_180']/100,'day'=>180);
		}
		if($memberInfo['price_360'] != 0){
			$memberInfo['type'][4] = array('price'=>$memberInfo['price_360']/100,'day'=>360);
		}
		
		$this->assign('memberInfo', $memberInfo);
		$this->render('order/member.info.html');
	}
	//物品购买信息
	public function pageBuy($inPath)
	{
		$typeArr = array('course','member','sms');
		if(empty($inPath[3]) || !in_array($inPath[3],$typeArr)){
			$this->redirect('/site.main.404');
		}
		$qudaoUserCode = array();
		$objectType = $inPath[3];
		$objectId   = !empty($inPath[4]) ? (int)$inPath[4] : 0;
		$ext        = !empty($inPath[5]) ? (int)$inPath[5] : 0;
		$memberInfo = array();
		$courseInfo = array();
		$smsInfo = array();
		$qudaoCode = '';
		$org=user_organization::subdomain();
		if(empty($org)){
			$this->redirect('/site.main.noauth');
		}
		if($objectType == 'member'){
			$memberInfo = org_member_api::getMemberSetInfo($objectId);
			if(empty($memberInfo)){
				$this->redirect('/member.list');
			}
			$orgInfo=user_organization::getOrgByOwner($org->userId);
			if($orgInfo->oid!=$memberInfo["orgId"]){
				$this->redirect('/site.main.noauth');
			}
			//新开通天数
			if($ext == 30){
				$memberInfo['price'] = $memberInfo['price_30']/100;
			}elseif($ext == 90){
				$memberInfo['price'] = $memberInfo['price_90']/100;
			}elseif($ext == 180){
				$memberInfo['price'] = $memberInfo['price_180']/100;
			}elseif($ext == 360){
				$memberInfo['price'] = $memberInfo['price_360']/100;
			}else{
				$this->redirect('/order.main.memberInfo/'.$objectId);
			}
			$memberInfo['time'] = date('Y-m-j', strtotime("+$ext day"));
			//实际天数(续费)
			$userMember = user_organization::checkMemberByUidAndSetId($this->userId, $objectId);
			if(!empty($userMember)){
				$memberInfo['time'] = date('Y-m-j',strtotime("$userMember->end_time +$ext day"));
			}
            $resellRes = array();
		}elseif($objectType == 'course'){
			$checkCourse = course_api::getCourseOne($objectId);
			if(empty($checkCourse)){
				$this->redirect('/course.main.404');
			}
			/*if($org->userId!=$checkCourse->user_id){
				//$this->redirect('/site.main.noauth');
			}*/
			$checkIsOk = course_api::checkusercanregistration($this->userId, $objectId, $ext);
			if(!$checkIsOk){
				$this->redirect("/course.info.show/".$objectId);
			}
			
			//分销课程			
			$orgId = !empty($inPath[6]) ? $inPath[6] : 0;
			if(!is_numeric($orgId)){
				if(substr_count($orgId,'%7C')>0){
					$orgId = str_replace("%7C","|",$orgId);
				}
				if(substr_count($orgId,'d')==1 &&substr_count($orgId,'|')==2){
					$qudaoCode = $orgId;
				}
				$resellOrgId=0;
			}
			$resellRes = course_resell_api::getResellCourseInfo($objectId, $orgId);

			$courseParam = [
				'q' => ['course_id'=>$objectId],
				'f' => ['course_id','class','title','thumb_med','price']
			];
			$courseRes = seek_api::seekCourse($courseParam);
			$className  = '';
			$courseInfo = array();
			if(!empty($courseRes->data)){
				foreach($courseRes->data as $val){
					$courseInfo = [
						'title'    => $val->title,
						'thumbMed' => utility_cdn::file($val->thumb_med),
						'price'    => isset($resellRes['priceResell']) ? $resellRes['priceResell'] : $val->price/100
					];
					foreach($val->class as $v){
						if($v->class_id == $ext){
							$className = $v->name;
						}
					}
				}
			}
			$courseInfo['className'] = $className;
			//收货地址
		    $userAddressResult = user_api::getUserAddress($this->userId);
		    if (!empty($userAddressResult->result->items)) {
		    	$this->assign('userAddress',$userAddressResult->result->items);
		    	$this->assign('province',utility_region::$region[$userAddressResult->result->items['0']->province]);
	    		$this->assign('city',utility_region::$region[$userAddressResult->result->items['0']->city]);
	    		$this->assign('country',utility_region::$region[$userAddressResult->result->items['0']->country]);
		    }

			if(!empty($qudaoCode)) {
				$distribute = order_api::getDistributeByCourseId($objectId);
				if (!empty($distribute->status) && $distribute->status == 1) {
					$distributeStatus = 1;
					$codeInfo = array();
					if(substr_count($qudaoCode,'d')==1 &&substr_count($qudaoCode,'|')==2){
						$codeInfo = explode('|',$qudaoCode);
					}
					if(!empty($codeInfo)){
						$qudaoUserId=$codeInfo[1];
						$qudaoUserCode = $codeInfo[2];
						$qudaoUser = order_api::getOneQudaoUserById($qudaoUserId);
						if(!empty($qudaoUser->fk_qudao)){
							$qudao = $qudaoUser->fk_qudao;
						}
					}
					$qudaoUserCode = order_api::getQudaoUserCodeByQudaoUserIdAndCourseIdAndCode(array("fk_course"=>$objectId,"fk_qudao_user"=>$qudaoUserId,"code"=>$qudaoUserCode));
					if(!empty($qudaoUserCode)){
					}
				}
			}
		}elseif($objectType == 'sms'){
			$resellRes = array();
			$smsInfo["title"] = "短信充值";
			$smsInfo["price"] = $ext/100;
		}
		$this->assign('ext',$ext);
		$this->assign('objectId', $objectId);
		$this->assign('objectType', $objectType);
		$this->assign('memberInfo', $memberInfo);
		$this->assign('smsInfo', $smsInfo);
		$this->assign('courseInfo', $courseInfo);
		$this->assign('resellRes', $resellRes);
		$this->assign('qudaoCode',$qudaoCode);
		$this->assign('qudaoUserCode',$qudaoUserCode);
		$this->render('order/order.buy.html');
	}
	//物品支付信息
	public function pagePay($inPath)
	{
		if(empty($inPath[3]) || !in_array($inPath[3],array('course','member','sms')) || empty($inPath[4])){
			$this->redirect('/site.main.404');
		}
		$objectType    = $inPath[3];
		$uniqueOrderId = $inPath[4];
		$orderRes = order_api::getOrder(array('uniqueOrderId'=>$uniqueOrderId));
		if(empty($orderRes->items)){
			$this->redirect('/site.main.404');
		}
		$orderInfo = $orderRes->items[0];
		$org=user_organization::subdomain();
		if(empty($org)){
			$this->redirect('/site.main.noauth');
		}
		$orgInfo=user_organization::getOrgByOwner($org->userId);
		//if($this->userId!=$orderInfo->fk_user||$orgInfo->oid!=$orderInfo->org_id){
		if($this->userId!=$orderInfo->fk_user){
			$this->redirect('/site.main.noauth');
		}
		//修改订单状态(失效订单)
		$timeNow = strtotime("now");
		$timeExp = strtotime($orderInfo->expiration_time);
		if(($orderInfo->status == 'initial'||$orderInfo->status == 'paying') && ($timeNow > $timeExp) ) {
			order_api::updateOrder($orderInfo->fk_order, array('status'=>'expired'));
			$orderRes = order_api::getOrder(array('uniqueOrderId'=>$uniqueOrderId));
			$orderInfo = $orderRes->items[0];
		}
		//错误页面
		if($orderInfo->status == 'expired' || $orderInfo->status == 'cancel'){
			return $this->render('order/order_error.html');
		}

		$orderInfo->orderSn = date('Ymd',strtotime($orderInfo->create_time)).$orderInfo->fk_order;
		$orderInfo->price   = $orderInfo->price/100;
		$layer = 0;
		$blackUrlArr =array("course.info.show","course/info/show","course.plan.play","course/plan/play");
		if (isset($_REQUEST['callback']) && $_REQUEST['callback'] == 1 && $orderInfo->status == 'success') {
			$layer = 1;
		} else {
			//已经支付，跳转到订单页
			if ($orderInfo->status == 'success') {
				if (isset($_COOKIE["blackUrl".$orderInfo->object_id.""])&&$objectType=="course"){
					$blackUrl = substr($_COOKIE["blackUrl".$orderInfo->object_id.""],0,strrpos($_COOKIE["blackUrl".$orderInfo->object_id.""],"/"));
					if(in_array($blackUrl,$blackUrlArr)) {
						$this->redirect('/' . $_COOKIE["blackUrl" . $orderInfo->object_id . ""]);
					}else{
						$this->redirect('/student.order.myorder');
					}
				}else {
					if($objectType=="sms"){
						$this->redirect('/org/message/SmsRecharge');
					}else {
						$this->redirect('/student.order.myorder');
					}
				}
			}
		}
		if($orderInfo->price <= 0){
			//更新订单
			$updateOrderData = [
				'price'    => 0,
				'pay_type' => '',
				'status'   => 'success'
			];
			$ret = order_api::updateOrder($orderInfo->fk_order, $updateOrderData);
			if($orderInfo->object_type == 1) {
				//课程订单处理
				//报名
				$orgInfo = org_api::getOrgInfo($orderInfo->org_id);
				$regData = [
					'uid' => $orderInfo->fk_user,
					'course_id' => $orderInfo->object_id,
					'class_id' => $orderInfo->ext,
					'user_owner' => $orgInfo->user_owner_id,
					'status' => 1
				];
				$regStatus = course_api::addregistration($regData);
				if ($regStatus === true) {
					$info = array(
						"userId"=>$orderInfo->fk_user,
						"courseId"=>$orderInfo->object_id,
						"orgId"=>!empty($orderInfo->org_id)?$orderInfo->org_id:0,
					);
					user_organizationStudent_api::addOrganizationStudent($info);
				}
			}elseif($orderInfo->object_type == 11){
				//会员订单处理
				$memberData = [
					'fk_user'          => $orderInfo->fk_user,
					'org_id'           => $orderInfo->org_id,
					'setId'            => $orderInfo->object_id,
					'last_type'        => 1,
					'type'             => 1,
					'member_status'    => 1,
					'status'           => 1,
					'fk_order_content' => $orderInfo->pk_order_content,
					'orderId'          => $orderInfo->fk_order,
					'price_type'       => !empty(order_api::$priceType[$orderInfo->ext])
						? order_api::$priceType[$orderInfo->ext]
						: 30,
				];
				$regStatus = org_member_api::addMember($memberData);
			}
			if($ret->code == '1' && $regStatus){
				if (isset($_COOKIE["blackUrl".$orderInfo->object_id.""])&&$objectType=="course"){
					$blackUrl = substr($_COOKIE["blackUrl".$orderInfo->object_id.""],0,strrpos($_COOKIE["blackUrl".$orderInfo->object_id.""],"/"));
					if(in_array($blackUrl,$blackUrlArr)) {
						$this->redirect('/' . $_COOKIE["blackUrl" . $orderInfo->object_id . ""]);
					}else{
						$this->redirect('/student.order.myorder');
					}
				}else {
					$this->redirect('/student.order.myorder');
				}
			}
		}
		$orderInfo->teacherName = $orderInfo->title = $orderInfo->extName = '';
		if($orderInfo->object_type == 11){
			$memberRes = org_member_api::getMemberSetInfo($orderInfo->object_id);
			if (!empty($memberRes['title'])) $orderInfo->title   = $memberRes['title'];
			if($orderInfo->ext == 1){
				$orderInfo->extName = '30天';
			}elseif($orderInfo->ext == 2){
				$orderInfo->extName = '90天';
			}elseif($orderInfo->ext == 3){
				$orderInfo->extName = '180天';
			}elseif($orderInfo->ext == 4){
				$orderInfo->extName = '360天';
			}
			$orderInfo->url = "/order.main.info/member/".$orderInfo->object_id;
		}elseif($orderInfo->object_type == 1){
			$courseRes = interface_courseApi::getCourseBasic($orderInfo->object_id);
			$classRes  = course_api::getClass($orderInfo->ext);
			$adminName = user_api::getBasicUser($classRes->user_class_id);
			if($classRes->course_id != $courseRes['pk_course']){
				$this->redirect('/site.main.404');
			}
			$orderInfo->title = $courseRes['title'];
			$orderInfo->courseThumb = interface_func::imgUrl($courseRes['thumb_med']);
			$orderInfo->extName = $classRes->name;
			if (!empty($adminName->real_name)) $orderInfo->teacherName = $adminName->real_name;
			$orderInfo->url = "/course.info.show/".$orderInfo->object_id;
		}elseif($orderInfo->object_type == 21){
			$orderInfo->title = "短信充值";
			$orderInfo->url = "/order.main.info/sms/".$orderInfo->object_id;
		}
		if (isset($_COOKIE["blackUrl".$orderInfo->object_id.""])&&$objectType=="course"){
			$blackUrl = substr($_COOKIE["blackUrl".$orderInfo->object_id.""],0,strrpos($_COOKIE["blackUrl".$orderInfo->object_id.""],"/"));
			if(in_array($blackUrl,$blackUrlArr)) {
				$orderInfo->url = '/'.$_COOKIE["blackUrl" . $orderInfo->object_id . ""];
			}
		}
		$titleInfo = new stdClass;
		$titleInfo->title = $orderInfo->title;
		$weiXinPayUrl = $qrCode = $aliPayUrl = $weiXin = '';
        if (empty($status)) {
			$weiXin = weixin_api::is_weixin();
			$gateway = order_api::getOrgGatewayByOrgId($orderInfo->org_id);
			if(!empty($gateway)){
				foreach($gateway as $g){
					if($g->fk_gateway==1){
						$aliPayUrl = course_api::genPayUrl($orderInfo, $titleInfo);
					}elseif($g->fk_gateway==2){
						if ($weiXin) {
							$sc = "http";
							if (utility_net::isHTTPS()) $sc = "https";
							if (isset($_COOKIE["blackUrl".$orderInfo->object_id.""])&&$objectType=="course"){
								$blackUrl = substr($_COOKIE["blackUrl".$orderInfo->object_id.""],0,strrpos($_COOKIE["blackUrl".$orderInfo->object_id.""],"/"));
								if(in_array($blackUrl,$blackUrlArr)) {
									$backUrl = $sc.'://'.$_SERVER['HTTP_HOST'].'/'.$_COOKIE["blackUrl" . $orderInfo->object_id . ""];
								}else{
									$backUrl = $sc."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
								}
							}else {
								$backUrl = $sc."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
							}
							$weiXinPayUrl = utility_services::url(
								"weixin_pay",
								"index.main.weixinpay/?uqid={$orderInfo->unique_order_id}&backurl=$backUrl"
							);
						} else {
							//扫码支付
							$weiXinQrCode = course_api::genWeixinPayQrcodeUrl($orderInfo);
							$qrCode       = utility_cdn::qrcode("qr?s=200&t=".urlencode($weiXinQrCode));
						}
					}
				}
			}else {
				$aliPayUrl = course_api::genPayUrl($orderInfo, $titleInfo);
				if ($weiXin) {
					$sc = "http";
					if (utility_net::isHTTPS()) $sc = "https";
					if (isset($_COOKIE["blackUrl".$orderInfo->object_id.""])&&$objectType=="course"){
						$blackUrl = substr($_COOKIE["blackUrl".$orderInfo->object_id.""],0,strrpos($_COOKIE["blackUrl".$orderInfo->object_id.""],"/"));
						if(in_array($blackUrl,$blackUrlArr)) {
							$backUrl = $sc.'://'.$_SERVER['HTTP_HOST'].'/'.$_COOKIE["blackUrl" . $orderInfo->object_id . ""];
						}else{
							$backUrl = $sc."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
						}
					}else {
						$backUrl = $sc."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
					}
					$weiXinPayUrl = utility_services::url(
						"weixin_pay",
						"index.main.weixinpay/?uqid={$orderInfo->unique_order_id}&backurl=$backUrl"
					);
				} else {
					//扫码支付
					$weiXinQrCode = course_api::genWeixinPayQrcodeUrl($orderInfo);
					$qrCode       = utility_cdn::qrcode("qr?s=200&t=".urlencode($weiXinQrCode));
				}
			}
        }
		$this->assign('weiXinPayUrl',$weiXinPayUrl);
		$this->assign('aliPayUrl',$aliPayUrl); 
		$this->assign('objectType',$objectType);
		$this->assign('qrCode',$qrCode);
		$this->assign('weiXin',$weiXin);
		$this->assign('layer',$layer);
		$this->assign('uniqueOrderId',$uniqueOrderId);
		$this->assign('orderInfo',$orderInfo);
		$this->render('order/order.pay.html');
	}
}
?>
