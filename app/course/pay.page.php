<?php

class course_pay extends STpl
{
    public function __construct()
    {
        $org = user_organization::subdomain();

        $this->orgOwner = !empty($org) ? $org->userId : 0;

        $this->user = user_api::loginedUser();

        if (empty($this->user)) return interface_func::setMsg(1001);
    }

    public function pageInfo($inPath)
    {
        if (empty($inPath[3])) {
            $this->redirect("/course/list");
        }

        if ($inPath[3] && empty($inPath[4])) {
            $this->redirect("/course/info/show/{$inPath[3]}");
        }

        $uniqueOrderId = $inPath[4];
        $orderInfo     = $this->_validOrderStatus($uniqueOrderId);

        if (!is_object($orderInfo) && !empty($orderInfo['errCode'])) {
            $errorInfo = interface_func::setError($orderInfo['errCode']);
        } else {
            $courseClassInfo = $this->_validCourseClass($orderInfo->object_id, $orderInfo->ext);
            if (!is_object($courseClassInfo) && !empty($courseClassInfo['errCode'])) {
                $errorInfo = interface_func::setError($courseClassInfo['errCode']);
            }

            $aliPayUrl = course_api::genPayUrl($orderInfo, $courseClassInfo['courseInfo']);

            if (weixin_api::is_weixin()) {
                //公众号支付
                $jsApiParameters = weixin_api::getJsParameters($orderInfo, $courseClassInfo['courseInfo']);
                $this->assign("jsApiParameters", $jsApiParameters);
            } else {
                //扫码支付
                $wXQrCode = course_api::genWeixinPayQrcodeUrl($orderInfo, $courseClassInfo['courseInfo']);
                $qrCode   = utility_cdn::qrcode("qr?s=200&t=".urlencode($wXQrCode));
                $this->assign("qrCode", $qrCode);
            }
        }




        //$order_info->price_discount = $orderInfo->price_old - $orderInfo->price * 100;

        $this->assign('payUrl', $aliPayUrl);
        $this->assign('errorInfo', $errorInfo);
        $this->assign("info", $courseClassInfo);
        $this->assign("orderInfo", $orderInfo);

        $this->render("course/newpay.info.html");
    }

    /**
     * 第一次使用优惠码占位
     *
     * @param $param
     */
    public function pageUseDiscount($param)
    {
        $obj     = (object)$param;
        $retCode = course_api::useDiscountCode($obj->code, $this->user["uid"], $obj->uqOId);

        if ($retCode->result->code == 0) {
            $orderInfo = order_api::getOrderByOutTradeId($obj->uqOId);
            $aliPayUrl = course_api::genPayUrl($orderInfo, $obj->title);

            return interface_func::setData(['payUrl' => $aliPayUrl]);
        }

        exit(json_encode($retCode, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 确定优惠码(更新优惠码使用状态)
     *
     * @param $orderId
     */
    public function pageConfirmDiscountCode($orderId)
    {
        $res = course_api::confirmDiscountCode($orderId);

        if (!empty($res)) return interface_func::setMsg(0);

        return interface_func::setMsg(1);
    }

    /**
     * 取消使用优惠码
     *
     * @param $param
     */
    public function pageCancelDiscountCode($param)
    {
        $obj     = (object)$param;
        $retCode = course_api::cancelDiscountCode($obj->orderId);

        if ($retCode->result->code == 0) {
            $orderInfo = order_api::getOrderByOutTradeId($obj->uqOId);
            $aliPayUrl = course_api::genPayUrl($orderInfo, $obj->title);

            return interface_func::setData(['payUrl' => $aliPayUrl]);
        }

        exit(json_encode($retCode, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 付费课程报名
     */
    public function pageCheck()
    {
        if (!isset($_POST['cid']) || !(int)($_POST['cid'])) return interface_func::setMsg('1000');
        if (!isset($_POST['classId']) || !(int)($_POST['classId'])) return interface_func::setMsg('1000');
        $courseId   = (int)($_POST['cid']);
        $classId    = (int)($_POST['classId']);
        $objectType = isset($_POST['isMember']) ? course_status::$objectType['memberSet'] : 1;
        $resellOrgId = isset($_POST['resellOrgId']) ? $_POST['resellOrgId']:0;
        $resellPrice = 0;
        $courseInfo = course_api::getCourseone($courseId);
        if ($resellOrgId) {
            $resellInfo = course_resell_api::getResellCourseInfo($courseId, $resellOrgId);
            if (!empty($resellInfo['courseId'])) {
                $resellPrice = $resellInfo['priceResell'];
            }
        }
        if (empty($courseInfo)) return interface_func::setMsg(2017);
        // 免费课直接报名(正常逻辑下免费课，代码运行不到这里)
        if ($courseInfo->fee_type == 0&&$resellPrice==0) return interface_func::setMsg(2018);

        // 判断班级人数是否还允许报名
        $classInfo = course_api::getclass($classId);
        if (empty($classInfo)) return interface_func::setMsg(2020);

        if ($classInfo->course_id != $courseInfo->course_id || $classInfo->status == 'invalid') {
            return interface_func::setMsg(2021);
        }

        //判断是不是已经购买
		$setIdArr  = course_api::getCourseOpenMemberSetIdArr($courseId);
		$memberRet = org_member_api::checkIsMemberOrExpire1($this->user['uid'], $setIdArr, $courseId);
		if(isset($memberRet['regId'])){
			if($memberRet['isMemberRegType'] == 0){
				return interface_func::setMsg(2019);
			}elseif($memberRet['isMemberRegType'] == 1){
				if($memberRet['isMember'] == 1 && $memberRet['isExpire'] == 0){
					return interface_func::setMsg(2019);
				}
			}
		}
		if(empty($memberRet['regId'])){
			if($classInfo->user_total >= $classInfo->max_user){
				return interface_func::setMsg(2041);
			}	
		}
        return interface_func::setData(['uniqueOrderId' => 'aaa']);
    }

    private function _validOrderStatus($uniqueOrderId)
    {
        $orderInfo = order_api::getOrderAndContent($uniqueOrderId);
        if (empty($orderInfo)) {
            return ['errCode' => 2016];
        }
        if ($orderInfo->status == "expired") {
            return ['errCode' => 2024];
        }

        if ($orderInfo->status == "success") {
            return ['errCode' => 2027];
        }

        if ($orderInfo->status == "deleted") {
            return ['errCode' => 2025];
        }

        if ($orderInfo->status == "fail") {
            return ['errCode' => 2026];
        }

        return $orderInfo;
    }

    private function _validCourseClass($cid, $classId)
    {
        $courseInfo = course_api::getCourseone($cid);

        if (empty($courseInfo)) {
            return ['errCode' => 2017];
        }

        // 免费课直接报名(正常逻辑下免费课，代码运行不到这里)
        if ($courseInfo->fee_type == 0) {
            return ['errCode' => 2018];
        }

        $classInfo = course_api::getClass($classId);
        if (empty($classInfo)) {
            return ['errCode' => 2020];
        }

        if ($classInfo->course_id != $cid || $classInfo->status == 'invalid') {
            return ['errCode' => 2021];
        }

        return ['classInfo' => $classInfo, 'courseInfo' => $courseInfo];
    }
}
