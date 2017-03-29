<?php

class interface_user_api
{
    const USER_VALUE_GET_USER_BALANCE = '/user/value/GetUserBalance/';

    const USER_VALUE_ADD = '/user/value/Add/';

    const USER_VALUE_ADD_LOG = '/user/value/AddLog/';

    const USER_VALUE_ADD_THIRD_PARTY_LOG = '/user/value/AddThirdPartyLog/';

    const USER_VALUE_EXISTS = '/user/value/Exists/';

    const USER_VALUE_UPDATE_BALANCE = '/user/value/UpdateBalance/';

    const COURSE_COURSE_USER_GET_USER_REG_COURSE = '/course/courseuser/GetUserRegCourse/';

    const UPDATE_COURSE_USER_EXPIRE_TIME = '/course/courseuser/UpdateCourseUserExpireTime/';

    const CHECK_USER_IS_ORG = '/user/auth/OrgRole/';

    /**
     * @desc get user balance
     *
     * @param $userId
     * @return float|int
     */
    public static function getUserBalance($userId)
    {
        $url = self::USER_VALUE_GET_USER_BALANCE.$userId;
        $res = interface_func::requestApi($url);
        if (!empty($res['code'])) return 0;

        return $res['result']['iosCoin']/100;
    }

    /**
     * @desc recharge
     *
     * @param $data
     * @return bool
     */
    public static function add($data)
    {
        if (empty($data['userId']) || empty($data['iosCoin'])) {
            return false;
        }

        $data = [
            'userId'      => (int)$data['userId'],
            'iosCoin'     => (int)$data['iosCoin'],
            'balance'     => 0,
            'virtualCoin' => 0,
            'status'      => 1
        ];

        $res = interface_func::requestApi(self::USER_VALUE_ADD, $data);
        if (!empty($res['code'])) {
            SLog::fatal('ios charge failed,params[%s]', var_export($data, 1));
            return false;
        }

        return true;
    }

    /**
     * @desc add recharge log
     *
     * @param $data
     * @return bool
     */
    public static function addLog($data)
    {
        if (empty($data['userId']) || empty($data['iosCoin'])) {
            return false;
        }

        $data = [
            'userId'      => (int)$data['userId'],
            'iosCoin'     => (int)$data['iosCoin'],
            'orderId'     => isset($data['orderId']) ? $data['orderId'] : 0,
            'source'      => isset($data['source']) ? $data['source'] : 1,
            'type'        => isset($data['type']) ? $data['type'] : 1,
            'balance'     => 0,
            'virtualCoin' => 0,
            'status'      => 1
        ];

        $res = interface_func::requestApi(self::USER_VALUE_ADD_LOG, $data);
        if (!empty($res['code'])) {
            SLog::fatal('add ios charge log failed,params[%s]', var_export($data, 1));
            return false;
        }

        return $res['result'];
    }

    /**
     * @desc add third party log
     *
     * @param $data
     * @return bool
     */
    public static function addThirdPartyLog($data)
    {
        if (empty($data['userId']) || empty($data['transactionId'])) {
            return false;
        }

        $data = [
            'userId'          => (int)$data['userId'],
            'logId'           => isset($data['logId']) ? $data['logId'] : 0,
            'transactionId'   => $data['transactionId'],
            'transactionInfo' => isset($data['transactionInfo']) ? $data['transactionInfo'] : '',
            'source'          => isset($params['source']) ? $params['source'] : 1,
        ];

        $res = interface_func::requestApi(self::USER_VALUE_ADD_THIRD_PARTY_LOG, $data);
        if (!empty($res['code'])) {
            SLog::fatal('add third party log failed,params[%s]', var_export($data, 1));
            return false;
        }

        return true;
    }

    /**
     * @desc check transactionId is exists ?
     *
     * @param $transactionId
     * @return bool
     */
    public static function exists($transactionId)
    {
        $url = self::USER_VALUE_EXISTS.$transactionId;
        $res = interface_func::requestApi($url);

        return !empty($res['code']) ? false : true;
    }

    /**
     * @desc iap valid get user balance
     *
     * @param $userId
     * @param $transactionId
     * @param $transactionInfo
     * @return array|float|int
     */
    public static function valid($userId, $transactionId, $transactionInfo, $orgId=0)
    {
        //$productValue = self::getProductValue($transactionInfo, $transactionId, $orgId);
        $productInfo = self::getProductValue($transactionInfo, $transactionId, $orgId);
        if (empty($productInfo['productIdValue'])) return interface_func::error(2044, 'yun dian value configuration error');

        // recharge
        $rechargeData = [
            'userId'  => $userId,
            'iosCoin' => $productInfo['productIdValue']
        ];
        // add recharge log
        $rechargeLogRes = self::addLog($rechargeData);
        if ($rechargeLogRes === false) {
            return interface_func::error(2046, 'Recharge records failed'); // recharge records failed
        }

        if (self::add($rechargeData) === false) {
            return interface_func::error(2047, 'Recharge failed');
        }

        // add userThirdParty log
        $envArr = array(1=>'Sandbox',2=>'Production');
        $thirdPartyData = [
            'userId'          => $userId,
            'logId'           => $rechargeLogRes,
            'transactionId'   => $transactionId,
            'price'           => $productInfo['productPrice'] * 100,
            'environment'     => !empty($envArr[$transactionInfo->environment]) ? $envArr[$transactionInfo->environment] : 0,
            'transactionInfo' => json_encode($transactionInfo)
        ];
        self::addThirdPartyLog($thirdPartyData);

        return self::getUserBalance($userId); // valid success return balance
    }

    /**
     * @desc get product value
     *
     * @param $receiptData
     * @param $transactionId
     * @return int
     */
    public static function getProductValue($receiptData, $transactionId, $orgId=0)
    {
        $productId = '';
        if (!empty($receiptData->receipt->in_app)) {
            foreach ($receiptData->receipt->in_app as $item) {
                if ($item->transaction_id == $transactionId && !empty($item->product_id)) {
                    $productId = $item->product_id;
                }
            }
        }

        //$productIdValue = 0;
        $productInfo = array();
		if(!empty($orgId)){
			$config = SConfig::getConfig(ROOT_CONFIG."/yunDianPrice.conf",'xiaoVoPriceSetting');
		}else{
			$config = SConfig::getConfig(ROOT_CONFIG."/yunDianPrice.conf",'priceSetting');
		}
        if (!empty($config->value)) {
            foreach ($config->value as $v) {
                if ($productId == $v->id) {
                    //$productIdValue = $v->yundian;
                    $productInfo = [
                        'productIdValue' => $v->yundian,
                        'productPrice'   => $v->price 
                    ];
                }
            }
        }

        return $productInfo;
    }

    /**
     * @desc get user order info
     *
     * @param $orderId
     * @return array|bool
     */
    public static function getOrderInfo($orderId,$orderType=1)
    {
        $res = order_api::getOrderAndContent($orderId);
        if (empty($res) || !(int)($res->ext)) {
            return false;
        }

        $courseArr = [
            'q' => [
                'course_id' => (int)$res->object_id,
                //'class_id'  => (int)$res->ext
            ],
            'f' => [
                'title',
                'course_id',
                'class',
                'thumb_med',
                'start_time',
				'course_type'
            ]
        ];

        // get course class info
		if($orderType==1){
			$search = seek_api::seekCourse($courseArr);
			if (empty($search->data)) {
				return false;
			}
		}elseif($orderType == 11){
			$memberInfo = org_member_api::getMemberSetInfo($res->object_id);
			if(empty($memberInfo)){
				return false;
			}
		}

        $planInfo = course_api::getClassPlan([(int)$res->ext]);
        $startTime = '';
        if (!empty($planInfo)) {
            $tmpClass = [];
            foreach ($planInfo as $plan) {
                if (!in_array($plan->fk_class, $tmpClass)) {
                    $tmpClass[] = $plan->fk_class;
                    $planList[$plan->fk_class] = $plan->start_time;
                }
            }
            $startTime = !empty($planList[$res->ext]) ? $planList[$res->ext] : '';
        }

        $class = [];
        if (!empty($search->data[0]->class)) {
            foreach ($search->data[0]->class as $v) {
                $class[$v->class_id] = [
                    'name' => $v->name,
                    'adminId' => $v->class_admin_id
                ];
            }
        }

        //get user name
        if (!empty($class[$res->ext]['adminId'])) {
            $name = user_api::getBasicUser($class[$res->ext]['adminId']);
        }
        $domainUrl = interface_courseApi::getDomainUrl(['courseId' => $res->object_id]);
        $data = [
			'userId'          => $res->user_id,
            'userOwner'       => $res->user_owner,
			'orgId'           => $res->org_id,
			'orderId'         => $res->order_id,
			'objectType'         => $res->object_type,
            'orderNumber'     => date('Ymd', strtotime($res->create_time)).$res->order_id,
            'orderOutTradeId' => $res->out_trade_id,
            'uniqueOrderId'   => $res->unique_order_id,
            'payType'         => $res->pay_type,
            'orderStatus'     => $res->status,
            'orderTime'       => date('Y-m-d H:i', strtotime($res->create_time)),
            'price'           => $res->price,
            'priceOld'        => $res->price_old,
			'startTime'       => date('n月d日 H:i', strtotime($startTime)),
			'address'         => '北京市海淀区北四环银谷大厦'
		];
		if($orderType == 1){
			$data['classId']     = $res->ext;
			$data['courseId']    = $res->object_id;
			$data['courseTitle'] = !empty($search->data[0]->title) ? $search->data[0]->title : '';
			$data['className']   = !empty($class[$res->ext]['name']) ? $class[$res->ext]['name'] : '';
			$data['imgUrl']      = interface_func::imgUrl($search->data[0]->thumb_med);
			$data['teacherName'] = !empty($name->name) ? $name->name : '空';
			$data['courseType']  = $search->data[0]->course_type;
			$data['payUrl']      = $domainUrl.'/course/info/pay/'.$res->unique_order_id;
		}elseif($orderType == 11){
			$data['memberId']   = $memberInfo['setId'];
			$data['memberName'] = $memberInfo['title'];
			$data['ext']        = $res->ext;
			$data['memberStatus'] = $memberInfo['status'];
		}
        return $data;
    }

    /**
     * @desc update balance
     *
     * @param $userId
     * @param $balance
     * @return bool
     */
    public static function updateBalance($userId, $balance)
    {
        $url = self::USER_VALUE_UPDATE_BALANCE;
        $params = [
            'userId'  => $userId,
            'balance' => $balance
        ];
        $res = interface_func::requestApi($url, $params);
        if (!empty($res['code'])) {
            SLog::fatal('update user balance failed,params[%s]', var_export([$userId, $balance, $url], 1));
            return false;
        }

        return true;
    }

    /**
     * @desc get user identity
     *
     * @param $userId
     * @return bool|int
     */
    public static function getIdentity($userId)
    {
        $userInfo = user_api::getBasicUser($userId);
        if (empty($userInfo)) return false;

        $identity = 0; // default student identity
        if (!empty($userInfo->type) && $userInfo->type & 0x02) {
            $identity = 1; // teacher
        }

        return $identity;
    }

    /**
     * @desc get user reg course
     *
     * @param $uid
     * @param array $courseIdArr
     * @param int $page
     * @param int $length
     * @return array
     */
    public static function getUserRegCourse($uid, $courseIdArr=[], $page = 1, $length = -1, $ownerId = 0)
    {
        $params = [
            'userId' => $uid,
            'page'   => $page,
            'length' => $length,
            'ownerId'=> $ownerId
        ];
        if (!empty($courseIdArr)) {
            $params['courseIdArr'] = $courseIdArr;
        }

        $url = self::COURSE_COURSE_USER_GET_USER_REG_COURSE;
        $res = interface_func::requestApi($url, $params);
        if (!empty($res['code'])) return [];

        return $res['result'];
    }

    /**
     * @desc update course user expire time
     *
     * @param $pKey
     * @param $time
     * @return bool
     */
    public static function updateCourseUserExpireTime($pKey, $time)
    {
        $params = [
            'courseUserId' => $pKey,
            'expireTime'   => $time
        ];
        $res    = interface_func::requestApi(self::UPDATE_COURSE_USER_EXPIRE_TIME, $params);
        if (!empty($res['code'])) return false;

        return true;
    }

    /**
     * @param $level
     * @return int
     *
     * @author 田螺
     */
    public static function bigLevel($level)
    {
        $bigLevel = 0;
        if ($level < 661) {
            $bigLevel = 1;
        } elseif ($level >= 661 || $level <= 3631) {
            $bigLevel = 2;
        } elseif ($level >= 3631 || $level <= 9031) {
            $bigLevel = 3;
        } elseif ($level >= 9031 || $level <= 15861) {
            $bigLevel = 4;
        } elseif ($level >= 15861 || $level <= 27121) {
            $bigLevel = 5;
        }

        return $bigLevel;
    }

    /**
     * @desc check user is org
     *
     * @param $loginUserId
     * @return array
     */
    public static function checkIsUserOrOrg($loginUserId)
    {
        $orgOwner = $orgId = 0;
        $org      = user_organization::subdomain();
        if (!empty($org->userId)) {
            $orgOwner = $org->userId;
        }

        // check user is org user owner
        $params = [
            'ownerId' => $orgOwner,
            'userId'  => $loginUserId
        ];
        $res    = interface_func::requestApi(self::CHECK_USER_IS_ORG, $params);

        if (!empty($res['code'])) return [
            'userId' => $loginUserId,
            'orgId'  => $orgId
        ];

        return [
            'userId' => 0,
            'orgId'  => $res['result']['pk_org']
        ];
    }
}
