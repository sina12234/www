<?PHP

class order_ajax extends STpl
{
	public $userId;
	public function __construct()
	{
		$this->userId = user_api::getLoginUid();
        if(empty($this->userId)){
            return $this->redirect("/site.main.login");
        } 
	}
	//开通会员
	public function pageOpenVip()
	{
		if(empty($_POST['setId']) || empty($_POST['ext'])){
			return interface_func::setMsg(1000);
		}
		$params = ['setId'=>(int)$_POST['setId'],'day'=>(int)$_POST['ext'],'uid'=>$this->userId];
		$ret = order_api::getVipTime($params);
		if(!$ret){
			return interface_func::setMsg(0);
		}
		
		return interface_func::setData(array('ext'=>$ret));
	}
	
	public function pagePay()
	{
		if(empty($_POST['objectId']) || empty($_POST['objectType']) || empty($_POST['ext'])){
			return interface_func::error(1000,'参数错误');
		}
		
		$objectId = (int)$_POST['objectId'];
		$type     = $_POST['objectType'];
		$ext      = (int)$_POST['ext'];
		$code     = !empty($_POST['code']) ? trim($_POST['code']) : '';
		$orgId=0;
		//使用优惠码状态
		$disStatus = 0;
	    $orgRes = user_organization::subdomain();
		if(empty($orgRes)){
			return interface_func::error(1000,'参数错误');
		}
		
		if($type == 'member'){
			$orgInfo=user_organization::getOrgByOwner($orgRes->userId);
			if(!empty($orgInfo)){
				$orgId = $orgInfo->oid;
			}
			$memberInfo = org_member_api::getMemberSetInfo($objectId);
			if(empty($memberInfo) || $memberInfo['status'] != 1){
				return interface_func::error(1000,'获取数据失败');
			}
			if($ext == 30){
				$price = $memberInfo['price_30']/100;
				$ext   = 1;
			}elseif($ext == 90){
				$price = $memberInfo['price_90']/100;
				$ext   = 2;
			}elseif($ext == 180){
				$price = $memberInfo['price_180']/100;
				$ext   = 3;
			}elseif($ext == 360){
				$price = $memberInfo['price_360']/100;
				$ext   = 4;
			}else{
				return interface_func::error(20010,'金额设置错误');
			}
			$objectType = 11;
		}elseif($type == 'course'){
			$courseInfo = course_api::getCourseone($objectId);
			if(empty($courseInfo)){
				return interface_func::error(2017,'获取课程信息失败');
			}
			$orgInfo=user_organization::getOrgByOwner($courseInfo->user_id);
			if(!empty($orgInfo)){
				$orgId = $orgInfo->oid;
			}
			$checkReg = course_api::checkUserCanRegistration($this->userId,$objectId,$ext);
			if(!$checkReg){
				return interface_func::error(2018,'该课程不能报名');
			}
			
			//分销课程
			$resellRes = array();
			if(!empty($_POST['orgId'])){
				$reseOrgId = (int)$_POST['orgId'];
				$resellRes = course_resell_api::getResellCourseInfo($objectId, $reseOrgId);
			}
	
			$price    = !empty($resellRes['priceResell']) ? $resellRes['priceResell'] : $courseInfo->fee->price;
			$priceOld = $courseInfo->fee->price;
			
			if(!empty($code)){
				$params  = ['code'=>$code,'objectId'=>$objectId,'userId'=>$this->userId,'isRot'=>1];
				$codeRes = order_api::checkCode($params);
				if($codeRes->code == 0){
					$price = $codeRes->result->price;
					$disStatus = $codeRes->result->discountCodeId;
				}else{
					return $codeRes;
				}
			}
			$objectType = 1;
		}
		$data[] = [
				'orgId'        => $orgId,
				'userId'       => $this->userId,
				'objectId'     => $objectId,
				'objectType'   => $objectType,
				'price'        => $price,
				'priceOld'     => !empty($priceOld) ? $priceOld : $price,
				'ext'          => $ext,
				'pricePromote' => !empty($resellRes['pricePromete']) ? $resellRes['pricePromete'] : 0,
				'resellOrgId'  => !empty($resellRes['resellOrgId']) ? $resellRes['resellOrgId'] : 0,
				'promoteStatus'=> !empty($resellRes) ? 1 : 0
			];
		
		//生成订单
		$order = order_api::addOrder($this->userId,$data,$disStatus);

		if($order->result->code != 0){
			return interface_func::error(27001,'生成订单错误');
		}
		if($price <= 0){
			//更新订单
			$updateOrderData = [
				'price'    => 0,
				'pay_type' => '',
				'status'   => 'success'
			];
			$ret = order_api::updateOrder($order->data->order_id, $updateOrderData);
			//报名
			$orgInfo = org_api::getOrgInfo($orgId);
			$regData = [
				'uid'        => $this->userId,
				'course_id'  => $objectId,
				'class_id'   => $ext,
				'user_owner' => $orgInfo->user_owner_id,
				'status'     => 1
			];
			$regStatus = course_api::addregistration($regData);
			if($ret->code == '1' && $regStatus){
				return interface_func::setData(array('back'=>1,'objectId'=>$objectId,'ext'=>$ext));
			}
		}
		
		return interface_func::setData(array('uniqueOrderId'=>$order->data->unique_order_id,'objectType'=>$type));
	}
	
	//检查优惠码
	public function pageCheckCode()
	{
		if(empty($_POST['code']) || empty($_POST['objectId'])){
			return interface_func::setMsg(1000);
		}
		$code     = trim($_POST['code']);
		$objectId = (int)$_POST['objectId'];
		
		$params = ['code'=>trim($_POST['code']),'objectId'=>(int)$_POST['objectId'],'userId'=>$this->userId];
		
		$ret = order_api::checkCode($params);
		return $ret;
	}

	/**
	 *  提交订单
	 */
	public function pagePayNew()
	{
		if(empty($_POST['objectId']) || empty($_POST['objectType']) || empty($_POST['ext'])){
			return interface_func::error(1000,'参数错误');
		}
		$qudaoUserId = 0;
		$qudaoUserCode = 0;
		$qudaoUser = 0;
		$qudao = 0;
		$objectId = (int)$_POST['objectId'];
		$type     = $_POST['objectType'];
		$ext      = (int)$_POST['ext'];
		$code     = !empty($_POST['code']) ? trim($_POST['code']) : '';
		$orgId = 0;
		$distributeStatus = 0;
		//使用优惠码状态
		$disStatus = 0;
		$orgRes = user_organization::subdomain();
		if(empty($orgRes)){
			return interface_func::error(1000,'参数错误');
		}

		if($type == 'member'){
			$orgInfo=user_organization::getOrgByOwner($orgRes->userId);
			if(!empty($orgInfo)){
				$orgId = $orgInfo->oid;
			}
			$memberInfo = org_member_api::getMemberSetInfo($objectId);
			if(empty($memberInfo) || $memberInfo['status'] != 1){
				return interface_func::error(1000,'获取数据失败');
			}
			if($ext == 30){
				$price = $memberInfo['price_30']/100;
				$ext   = 1;
			}elseif($ext == 90){
				$price = $memberInfo['price_90']/100;
				$ext   = 2;
			}elseif($ext == 180){
				$price = $memberInfo['price_180']/100;
				$ext   = 3;
			}elseif($ext == 360){
				$price = $memberInfo['price_360']/100;
				$ext   = 4;
			}else{
				return interface_func::error(20010,'金额设置错误');
			}
			$objectType = 11;
		}elseif($type == 'course'){
			$courseInfo = course_api::getCourseone($objectId);
			if(empty($courseInfo)){
				return interface_func::error(2017,'获取课程信息失败');
			}
			$orgInfo=user_organization::getOrgByOwner($courseInfo->user_id);
			if(!empty($orgInfo)){
				$orgId = $orgInfo->oid;
			}
			$checkReg = course_api::checkUserCanRegistration($this->userId,$objectId,$ext);
			if(!$checkReg){
				return interface_func::error(2018,'该课程不能报名');
			}

			//分销课程
			$resellRes = array();
			if(!empty($_POST['orgId'])){
				$reseOrgId = (int)$_POST['orgId'];
				$resellRes = course_resell_api::getResellCourseInfo($objectId, $reseOrgId);
			}
			$price    = !empty($resellRes['priceResell']) ? $resellRes['priceResell'] : $courseInfo->fee->price;
			$priceOld = !empty($courseInfo->fee->price)?$courseInfo->fee->price:0;

			if(!empty($code)){
				$params  = ['code'=>$code,'objectId'=>$objectId,'userId'=>$this->userId,'isRot'=>1];
				$codeRes = order_api::checkCodeNew($params);
				if(isset($codeRes->object_type)) $codeRes = json_decode($this->checkPay($codeRes));
				if($codeRes->code == 0){
					$price = $codeRes->result->price;
					$disStatus = $codeRes->result->discountCodeId;
				}else{
					return $codeRes;
				}
			}
			$objectType = 1;
			$qudaoCode = !empty($_POST['qudaoCode'])?$_POST['qudaoCode']:'';
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
				}
			}
			$qudaoUserCodeInfo = order_api::getQudaoUserCodeByQudaoUserIdAndCourseIdAndCode(array("fk_course"=>$objectId,"fk_qudao_user"=>$qudaoUserId,"code"=>$qudaoUserCode));
			if(empty($qudaoUserCodeInfo)){
				$distributeStatus = 0;
				$qudaoUserId = 0;
				$qudaoUserCode = 0;
				$qudao = 0;
			}
		}elseif($type == 'sms') {
			$orgInfo = user_organization::getOrgByOwner($orgRes->userId);
			if (!empty($orgInfo)) {
				$orgId = $orgInfo->oid;
			}
			$price = $ext / 100;
			$ext = 0;
			$objectType = 21;
		}
		$data[] = [
			'orgId'        => $orgId,
			'userId'       => $this->userId,
			'objectId'     => $objectId,
			'objectType'   => $objectType,
			'price'        => $price,
			'priceOld'     => !empty($priceOld) ? $priceOld : $price,
			'ext'          => $ext,
			'pricePromote' => !empty($resellRes['pricePromete']) ? $resellRes['pricePromete'] : 0,
			'resellOrgId'  => !empty($resellRes['resellOrgId']) ? $resellRes['resellOrgId'] : 0,
			'promoteStatus'=> !empty($resellRes) ? 1 : 0,
			'qudaoUser'    => $qudaoUserId,
			'qudaoUserCode'=> $qudaoUserCode,
			'qudao'        => $qudao,
			'distributeStatus'=>$distributeStatus,
		];
		//生成订单
		$order = order_api::addOrder($this->userId,$data,$disStatus);
		if($order->result->code != 0){
			return interface_func::error(27001,'生成订单错误');
		}
		if($price <= 0){
			//更新订单
			$updateOrderData = [
					'price'    => 0,
					'pay_type' => '',
					'status'   => 'success'
			];
			$ret = order_api::updateOrder($order->data->order_id, $updateOrderData);
			//报名
			$orgInfo = org_api::getOrgInfo($orgId);
			$regData = [
					'uid'        => $this->userId,
					'course_id'  => $objectId,
					'class_id'   => $ext,
					'user_owner' => $orgInfo->user_owner_id,
					'status'     => 1
			];
			$regStatus = course_api::addregistration($regData);
			if($ret->code == '1' && $regStatus){
				$url = '';
				$blackUrlArr =array("course.info.show","course/info/show","course.plan.play","course/plan/play");
				if (isset($_COOKIE["blackUrl".$objectId.""])&&$type=="course") {
					$blackUrl = substr($_COOKIE["blackUrl" . $objectId . ""], 0, strrpos($_COOKIE["blackUrl" . $objectId . ""], "/"));
					if (in_array($blackUrl, $blackUrlArr)) {
						$url ='/' . $_COOKIE["blackUrl" . $objectId . ""];
					}
				}
				return interface_func::setData(array('back'=>1,'objectId'=>$objectId,'ext'=>$ext,'blackUrl'=>$url));
			}
		}

		return interface_func::setData(array('uniqueOrderId'=>$order->data->unique_order_id,'objectType'=>$type));
	}
	//验证订单是否有效
	public function pageCheckOrder()
	{
		if(empty($_POST['uniqueOrderId'])) return json_encode(array('code'=>0));
		$orderRes = order_api::getOrder(array('uniqueOrderId'=>$_POST['uniqueOrderId']));
		if(empty($orderRes->items[0])) return json_encode(array('code'=>0));
		$orderInfo = $orderRes->items[0];
		if($orderInfo->status == 'expired' || $orderInfo->status == 'cancel'){
			return json_encode(array('code'=>0));
		}else{
			return json_encode(array('code'=>1));
		}
	}

	/**
	 * 输入优惠码 优惠券时候检测 是否可以使用
	 */
	public function pageCheckCodeNew()
	{
		if(empty($_POST['code']) || empty($_POST['objectId'])){
			return interface_func::setMsg(1000);
		}
		$code     = htmlspecialchars(trim($_POST['code']));
		$objectId = (int)$_POST['objectId'];
		$params = ['code'=>$code,'objectId'=>$objectId,'userId'=>$this->userId];
		//检测是㤇存在优惠码
		//如果优惠码被绑定就不能使用
		$res = course_discount_api::getCodeBander($code);
		if(!empty($res->result->items)) return interface_func::error(10001,'请输入有效的优惠码');
		$ret = order_api::checkCodeNew($params);
		if(!isset($ret->object_type)) return $ret;
		return $this->checkPay($ret);
	}

	public function setMsg($code,$message){
		$st = new stdClass();
		$st->code = $code;
		$st->errMsg = $message;
		return json_encode($st);
	}

//支付检测
	public function checkPay($ret){
		if(isset($ret->object_type)){
			$object_id= $ret ->object_id;
			$discountRules = $ret -> discountRules;
			$courseInfo = $ret -> courseInfo ;
			$discountCodeInfo = $ret ->discountCodeInfo;
			$code_id = $ret->code_id;
			$param = [
					"f" => ["org_id"],
					"q" => [
							'course_id'		=> $ret->courseId
							]
					];
			$orgId = seek_api::seekcourse($param);
			$org_id = $orgId->data[0]->org_id;
			if($org_id != $code_id ) return $this->setMsg(20018,'不存在对应的课程');
			if($ret->object_type==3){
				if($ret->object_id !='all'){
				$object_id = explode(',',$object_id);
				$len = count($object_id);
				$fields = [
						"course_id"
				];
				for($i=0;$i<$len;$i++){
					if($i==0){
						$firstCate = $object_id[$i];
					}elseif($i==1){
						$secondCate = $object_id[$i];
					}elseif($i==2){
						$thirdCate = $object_id[$i];
					}elseif($i==3){
						$attrValueId = $object_id[$i];
					}elseif($i==4){
						$courseType = $object_id[$i];
					}
				}
				$query = [
						'first_cate'     => isset($firstCate) ? $firstCate : '',
						'second_cate'    => isset($secondCate) ? $secondCate : '',
						'third_cate'     => isset($thirdCate) ? $thirdCate : '',
						'attr_value_id'  => isset($attrValueId) ? $attrValueId : '',
						'course_type'    => isset($courseType) ? $courseType : '',
						'org_id'		=> $org_id,
						'course_id'		=> $ret->courseId
				];
				foreach($query as $kk=>$item){
					if(empty($item)) unset($query[$kk]);
				}
				$params = [
						"f" => $fields,
						"q" => $query
				];
				$resCourse = seek_api::seekcourse($params);

				if(empty($resCourse->data)) return $this->setMsg(20016,'不在优惠范围内');
				}
			}elseif($ret->object_type == 1){
				if($ret->object_id !='all'){
					$courseIds = explode(',',$ret->object_id);
					$courseIds = array_map(array($this,'stringToInt'),$courseIds);
					if(!in_array($ret->courseId,$courseIds))  return $this->setMsg(20017,'不在优惠范围内');
				}
			}
			//优惠码使用订单
			if($ret->isRob){
				$st = new stdClass();
				$discountRules = $ret -> discountRules;
				$courseInfo = $ret -> courseInfo ;
				$discountCodeInfo = $ret ->discountCodeInfo;
				$st->discount_code_id = $discountCodeInfo->discount_code_id;
				$st->num = 1;
				order_api::updateUsedNumForDiscountCodeById($st);
				$codeData  = order_api::getDiscountCodeById($st);
				$discountCodeInfo = $codeData->data;
				if($discountCodeInfo->bind_status==1 && $discountCodeInfo->used_num > 0 && $discountCodeInfo->total_num == $discountCodeInfo->used_num){
					course_discount_api::updateCodeStatusByCode($discountCodeInfo->discount_code,1);
				}
				if (!empty($discountCodeInfo) && ($discountCodeInfo->total_num > 0 && $discountCodeInfo->total_num < $discountCodeInfo->used_num)) {
					$st->num = -1;
					order_api::updateUsedNumForDiscountCodeById($st);
					return $this->setMsg(20012, '优惠已经使用完了');
				}
			}
			$disPrice = 0;
			if(1 == $discountRules->discount_type){
				$price = ($courseInfo->price - $discountRules->discount_value);
				$disPrice = $discountRules->discount_value;
			}else{
				$price = ((int)($courseInfo->price * $discountRules->discount_value /100 + 0.5));
				if($price <= 0){
					$disPrice = 0;
				}else{
					$disPrice = $courseInfo->price - $price;
				}
			}

			$data = [
					'priceOld' => $courseInfo->price /100,
					'disPrice' => $disPrice/100,
					'price'    => ($price/100 < 0 ) ? 0 : ($price/100)  ,
					'discountCodeId' => $discountCodeInfo->discount_code_id,
					'type'     =>  $discountRules->discount_type,//1 manjian 2 dazhe
				    'discount_value' => (int)$discountRules->discount_value/100,
					'min_fee'   => (int)$discountRules->min_fee/100,
			];

			return interface_func::setData($data);
		}
	}

	/**
	 *
	 * 1, 查询用户在该课程下可用的优惠券
	 * 2，优惠后钱数最低的排在前面
	 * 3，再走优惠码的逻辑
	 */

	public function pageDiscountTicket(){
		if(empty($_POST['objectId'])){
			return interface_func::setMsg(1000);
		}
		$objectId = (int)$_POST['objectId'];
		$data = course_discount_api::getUserDiscoutCode($this->userId,'all',0);//取出所有可用优惠码
		if(!empty($data->data->items)){
			$ticket_list = array();
			foreach($data->data->items as $item){
					//检测用户的优惠码是㤇能在该课程下使用
				$ret = json_decode($this->DiscountTicketCheck($item->fk_discount_code,$objectId));
				if(isset($ret->code) && $ret->code==0){
					//收集可用的优惠码
					$ret->result->code = $item->fk_discount_code;
					$ticket_list[$item->fk_discount_code] = (array)$ret->result;
				}
			}
			if(count($ticket_list)){
				return $this->sort_ticket($ticket_list,'price');
			}
			return interface_func::error('30000','没有可用的优惠券');
		}

		return interface_func::error('30000','没有可用的优惠券');
	}

	//获取price 最低的值
	function sort_ticket($list, $field, $sortby = 'asc'){
			$refer = $resultSet = array();
			foreach ($list as $i => $data){
				$refer[$i] = &$data[$field];
			}
			switch ($sortby)
			{
				case 'asc': // 正向排序
					asort($refer);
					break;
				case 'desc': // 逆向排序
					arsort($refer);
					break;
				case 'nat': // 自然排序
					natcasesort($refer);
					break;
			}
			foreach ($refer as $key => $val)
			{
				$resultSet['codeList'][] = &$list[$key];
			}
			return interface_func::setData($resultSet);
	}


	public function DiscountTicketCheck($code,$objectId)
	{
		$code     = htmlspecialchars(trim($code));
		$objectId = (int)$objectId;
		$params = ['code'=>$code,'objectId'=>$objectId,'userId'=>$this->userId];
		$ret = order_api::checkCodeNew($params);
		if(isset($ret->code)){
		    return $this->setMsg(-1,'faild');
        }
		return $this->checkPay($ret);
	}

	public function stringToInt($courseId){
		return (int)$courseId;
	}

	//获取订单最新支付信息
	public function pageGetOrderPay($inPath){
		if(empty($inPath[3]) || !in_array($inPath[3],array('course','member','sms')) || empty($inPath[4])){
			return json_encode(array("code"=>-1,"url"=>"/site.main.404"));
		}
		$objectType    = $inPath[3];
		$uniqueOrderId = $inPath[4];

		$orderRes = order_api::getOrder(array('uniqueOrderId'=>$uniqueOrderId));

		if(empty($orderRes->items)){
			return json_encode(array("code"=>-2,"url"=>"/site.main.404"));
		}
		$orderInfo = $orderRes->items[0];
		$org=user_organization::subdomain();
		if(empty($org)){
			return json_encode(array("code"=>-3,"url"=>"/site.main.noauth"));
		}
		//$orgInfo=user_organization::getOrgByOwner($org->userId);
		//if($this->userId!=$orderInfo->fk_user||$orgInfo->oid!=$orderInfo->org_id){
		if($this->userId!=$orderInfo->fk_user){
			return json_encode(array("code"=>-5,"url"=>"/site.main.noauth"));
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
			return json_encode(array("code"=>-6,"url"=>"/order/order_error.html"));
		}

		$orderInfo->orderSn = date('Ymd',strtotime($orderInfo->create_time)).$orderInfo->fk_order;
		$orderInfo->price   = $orderInfo->price/100;
		if($orderInfo->price <= 0 &&$objectType=="course"){
			//更新订单
			$updateOrderData = [
				'price'    => 0,
				'pay_type' => '',
				'status'   => 'success',
			];
			$ret = order_api::updateOrder($orderInfo->fk_order, $updateOrderData);
			//报名
			$orgInfo = org_api::getOrgInfo($orderInfo->org_id);
			$regData = [
				'uid'        => $orderInfo->fk_user,
				'course_id'  => $orderInfo->object_id,
				'class_id'   => $orderInfo->ext,
				'user_owner' => $orgInfo->user_owner_id,
				'status'     => 1
			];
			$regStatus = course_api::addregistration($regData);
			if($ret->code == '1' && $regStatus){
				return json_encode(array("code"=>-9,"url"=>"/student.order.myorder"));
			}
		}
		$layer = 0;
		if (isset($_REQUEST['callback']) && $_REQUEST['callback'] == 1 && $orderInfo->status == 'success') {
			$layer = 1;
		} else {
			//已经支付，跳转到订单页
			if ($orderInfo->status == 'success') {
				return json_encode(array("code"=>-7,"url"=>"/student.order.myorder"));
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
				return json_encode(array("code"=>-8,"url"=>"/site.main.404"));
			}
			$orderInfo->title = $courseRes['title'];
			$orderInfo->courseThumb = interface_func::imgUrl($courseRes['thumb_med']);
			$orderInfo->extName = $classRes->name;
			if (!empty($adminName->real_name)) $orderInfo->teacherName = $adminName->real_name;
			$orderInfo->url = "/student.course.detail/".$orderInfo->object_id.'/'.$orderInfo->ext;
		}

		$titleInfo = new stdClass;
		$titleInfo->title = $orderInfo->title;
		$weiXinPayUrl = $qrCode = $aliPayUrl = $weiXin = '';
		if (empty($status)) {
			$aliPayUrl = course_api::genPayUrl($orderInfo, $titleInfo);
			$weiXin    = weixin_api::is_weixin();
			if ($weiXin) {
				$sc = "http";
				if (utility_net::isHTTPS()) $sc = "https";

				$backUrl      = $sc."://.".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
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
		return json_encode(array("code"=>0,"items"=>array("weiXinPayUrl"=>$weiXinPayUrl,"aliPayUrl"=>$aliPayUrl,"qrCode"=>$qrCode,"weiXin"=>$weiXin,"layer"=>$layer,"orderInfo"=>$orderInfo)));
	}



	//机构短信充值
	public function pageOrgSmsReacharge(){
		$data[] = [
				'orgId'        => $orgId,
				'userId'       => $this->userId,
				'objectId'     => $objectId,
				'objectType'   => $objectType,
				'price'        => $price,
				'priceOld'     => !empty($priceOld) ? $priceOld : $price,
				'ext'          => $ext,
				'pricePromote' => !empty($resellRes['pricePromete']) ? $resellRes['pricePromete'] : 0,
				'resellOrgId'  => !empty($resellRes['resellOrgId']) ? $resellRes['resellOrgId'] : 0,
				'promoteStatus'=> !empty($resellRes) ? 1 : 0
		];

		//生成订单
		$order = order_api::addOrder($this->userId,$data,$disStatus);

		if($order->result->code != 0){
			return interface_func::error(27001,'生成订单错误');
		}
		if($price <= 0){
			//更新订单
			$updateOrderData = [
					'price'    => 0,
					'pay_type' => '',
					'status'   => 'success'
			];
			$ret = order_api::updateOrder($order->data->order_id, $updateOrderData);
			//报名
			$orgInfo = org_api::getOrgInfo($orgId);
			$regData = [
					'uid'        => $this->userId,
					'course_id'  => $objectId,
					'class_id'   => $ext,
					'user_owner' => $orgInfo->user_owner_id,
					'status'     => 1
			];
			$regStatus = course_api::addregistration($regData);
			if($ret->code == '1' && $regStatus){
				return interface_func::setData(array('back'=>1,'objectId'=>$objectId,'ext'=>$ext));
			}
		}

		return interface_func::setData(array('uniqueOrderId'=>$order->data->unique_order_id,'objectType'=>$type));
	}
}
?>
