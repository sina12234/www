<?php

class interface_user extends interface_base
{
	private static $gender = array(1=>'male',2=>'female');

    public function pageGetVerificationCode()
    {
        $this->v(
            [
                'mobile' => 1013,
                'type' => 1000
            ]
        );

		$type   = (int)($this->paramsInfo['params']['type']);
		$mobile = utility_valid::getMobile($this->paramsInfo['params']['mobile']);

		if(!in_array($type,array(1,2,3,4))){
			return $this->setMsg(1);
		}

        if (!utility_valid::mobile($mobile)) {
            return $this->setMsg(1012);
        }

        if ($type == 1) {
            if (user_api::isRegister($mobile)) {
                return $this->setMsg(2009);
            }
        }

		if ($type == 2) {
			$res = user_api::getUserIdByMobile($mobile);
			if (empty($res->uid))
			{
				return $this->setMsg(2014);
			}
		}

        if (!verify_api::sendMobileCode($mobile, $ret)) {
            return interface_func::setMsg(2049, "发送验证码错误,[".$ret->result->msg."]");
        }

        return $this->setMsg(0);
    }

    public function pageRegister()
    {
        $this->v(
            [
                'mobile'       => 1013,
                'verifyCode'   => 1014,
                'password'     => 1015,
                'repeatPasswd' => 1016,
            ]
        );

        if (!utility_valid::mobile($this->paramsInfo['params']['mobile'])) {
            return $this->setMsg(1012);
        }

        if (!preg_match('/^[\\x20-\\x7e]+$/', $this->paramsInfo['params']['password'])) {
            return $this->setMsg(1017);
        }

        if (strlen($this->paramsInfo['params']['password']) < 6 || strlen($this->paramsInfo['params']['password']) > 20) {
            return $this->setMsg(1018);
        }

        if ($this->paramsInfo['params']['password'] != $this->paramsInfo['params']['repeatPasswd']) {
            return $this->setMsg(1019);
        }

        if (!verify_api::verifyMobile($this->paramsInfo['params']['mobile'], $this->paramsInfo['params']['verifyCode'])) {
            return $this->setMsg(2011);
        }

        $name = $this->paramsInfo['params']['mobile']; // 暂时用mobile替代用户名

        // register
        if ($this->paramsInfo['params']['type'] == 1) {
            if (user_api::isRegister($this->paramsInfo['params']['mobile'])) {
                return $this->setMsg(2009);
            }

            $uid = user_api::registerByMobile($name, $this->paramsInfo['params']['mobile'], $this->paramsInfo['params']['password']);

            if ($uid) {
                $msg   = "#name#={$this->paramsInfo['params']['mobile']}&#company#=高能壹佰";
                $tplId = 630841;
                if (verify_api::sendSMS($this->paramsInfo['params']['mobile'], $msg, $tplId)) {
                    return $this->setData(
                        [
                            'uid' => $uid
                        ]
                    );
                }
                return $this->setMsg(2015);
            } else {
                return $this->setMsg(2012);
            }
        }

        // update password
        if ($this->paramsInfo['params']['type'] == 2) {
            $res = user_api::getUserIdByMobile($this->paramsInfo['params']['mobile']);
            if (empty($res->uid)) {
                return $this->setMsg(2014);
            }

            if (user_api::updatePassword($res->uid, $this->paramsInfo['params']['password'])) {
                return $this->setMsg(0);
            }

            return $this->setMsg(2013);
        }

        return $this->setMsg(3000);
    }

	/*
	 * 注册和找回密码
	 * @author zhengtianlong
	 * @params $mobile $verifyCode $password $type $name
	 */
	 public function pageRegisterV2()
    {

		//必传参数
        $this->v(
            [
                'mobile'     => 1013,
                'verifyCode' => 1014,
                'password'   => 1015,
            ]
        );

		$type = !empty($this->paramsInfo['params']['type'])?$this->paramsInfo['params']['type']:1;

		$mobile = utility_valid::getMobile($this->paramsInfo['params']['mobile']);

        if (!utility_valid::mobile($mobile))
		{
            return $this->setMsg(1012);
        }

        if (!preg_match('/^[\\x20-\\x7e]+$/', $this->paramsInfo['params']['password']))
		{
            return $this->setMsg(1017);
        }

        if (strlen($this->paramsInfo['params']['password']) < 6 || strlen($this->paramsInfo['params']['password']) > 20)
		{
            return $this->setMsg(1018);
        }

        if (!verify_api::verifyMobile($mobile, $this->paramsInfo['params']['verifyCode']))
		{
            return $this->setMsg(2011);
        }

        // register
        if ($type == 1)
		{
			$this->v(['name' => 1023]);
			//不能是数字和特殊字符
			if(!preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',$this->paramsInfo['params']['name']))
			{
				return $this->setMsg(2040);
			}
			//真实姓名最少2个汉字最多10个汉字
			if(preg_match("/[\x7f-\xff]/", $this->paramsInfo['params']['name']))
			{
				if(!utility_tool::check_string($this->paramsInfo['params']['name'], 10,2))
				{
					return $this->setMsg(2039);
				}
			}
			//真实姓名不能超过50个英文字符
			if(preg_match("/[a-zA-Z\s]+$/", str_replace(' ','',$this->paramsInfo['params']['name'])))
			{
				if(!utility_tool::check_string($this->paramsInfo['params']['name'], 50,1))
				{
					return $this->setMsg(2038);
				}
			}

            if (user_api::isRegister($mobile))
			{
                return $this->setMsg(2009);
            }

            $uid = user_api::registerByMobile($this->paramsInfo['params']['name'], $mobile, $this->paramsInfo['params']['password']);

            if ($uid) {
                $msg   = "#name#={$mobile}&#company#=高能壹佰";
                $tplId = 630841;
                if (verify_api::sendSMS($mobile, $msg, $tplId)) {
                    return $this->setData(['uid' => $uid]);
                }
                //注册环信
                $conf   = SConfig::getConfig(ROOT_CONFIG."/const.conf",'present');
                $hxName = $conf->domain.$uid;
                $hxPwd  = substr(md5(md5($conf->domain.'2017')), 0, 7);
                interface_easemob_api::createUser($hxName, $hxPwd);

                return $this->setMsg(2015);
            } else {
                return $this->setMsg(2012);
            }
        }

        // update password
        if ($type == 2)
		{
            $res = user_api::getUserIdByMobile($this->paramsInfo['params']['mobile']);
            if (empty($res->uid)) {
                return $this->setMsg(2014);
            }

            if (user_api::updatePassword($res->uid, $this->paramsInfo['params']['password'])) {
                return $this->setMsg(0);
            }

            return $this->setMsg(2013);
        }

        return $this->setMsg(3000);
    }


	/*
	 * 检查验证码
	 * @author zhengtianlong
	 */
	 public function pageCheckVerify()
	 {
		$this->v(
			[
				'mobile'     => 1013,
				'verifyCode' => 1014
			]
        );

		$mobile = utility_valid::getMobile($this->paramsInfo['params']['mobile']);
		$verifyCode = $this->paramsInfo['params']['verifyCode'];

		if (!verify_api::verifyMobile($mobile, $verifyCode))
		{
            return $this->setMsg(2011);
        }
		return $this->setMsg(0);

	 }

	 /*
	  * 修改密码
	  * @author zhengtianlong
	  */
	  public function pageUpdatePwd()
	  {
		$this->v(
			[
				'mobile'   => 1013,
				'uid'      => 1001,
				'password' => 1015,
				'oldPassword'=> 1015
			]
        );

		$mobile = utility_valid::getMobile($this->paramsInfo['params']['mobile']);
		$uid      = $this->paramsInfo['params']['uid'];
		$password = $this->paramsInfo['params']['password'];

        $md5Password = user_api::checkPassword($this->paramsInfo['params']['oldPassword']);

		$oldPassword = $md5Password->result->str;

		$res = user_api::getUserIdByMobile($mobile);

        if (empty($res->uid))
		{
			return $this->setMsg(2014);
		}

        if($res->uid == $uid)
        {
			$userBase = user_api::getBasicUserAndMobile($res->uid);
			if($userBase->password == $oldPassword)
			{
				if (user_api::updatePassword($res->uid, $password))
				{
					return $this->setMsg(0);
				}
			}
        }

		return $this->setMsg(2013);
	  }



    public function pageOrder()
    {
        $this->v(
            [
                'uid'    => 1000,
                'status' => 1000,
            ]
        );
        //云课/小沃取全部订单
		$orgId = 0;//!empty($this->paramsInfo['oid']) ? (int)($this->paramsInfo['oid']) : 0;

        $condition = [
            //'price'   => 'price>0',
            'userId' => $this->paramsInfo['params']['uid']
        ];

        if ($this->paramsInfo['params']['status'] == 'all') {
            //$condition['status'] = '';
        }

        if ($this->paramsInfo['params']['status'] == 'paid') {
            $condition['status'] = '2';
        }

        if ($this->paramsInfo['params']['status'] == 'unpaid') {
            $condition['status'] = '0';
        }

        if ($this->paramsInfo['params']['status'] == 'cancel') {
            $condition['status'] = '-4';
        }

        if ($this->s('cid')) {
            $condition['objectId'] = $this->paramsInfo['params']['cid'];
        }

        if ($this->s('orderId')) {
            $condition['order_id'] = $this->paramsInfo['params']['orderId'];
        }

		if(!empty($orgId)){
			$condition['orgId'] = $orgId;
		}else{
			$condition['orderType'] = 1;
		}
		//如果是IOS,过滤掉object_type类型会员11,安卓正常输出
		if($this->paramsInfo['u']=='i'){
			$condition['object_type'] = 1;
		}
        $page   = $this->s('page') ? (int)$this->paramsInfo['params']['page'] : 1;
        $length = $this->s('length') ? (int)$this->paramsInfo['params']['length'] : 20;

		$ret = order_api::orderList($condition, $page, $length);
		if (empty($ret['data'])) return $this->setMsg(3002);
		$result['page']      = $ret['page'];
		$result['total']     = $ret['totalSize'];
        $result['totalPage'] = $ret['total'];

		foreach ($ret['data'] as $m) {
				$result['data'][] = [
						'objectType'  => $m->object_type,
						'courseType'  => !empty($m->course['courseType']) ? $m->course['courseType'] : 0,
						'courseId'    => $m->object_id,
						'courseTitle' => !empty($m->course['name']) ? $m->course['name'] : '',
						'className'   => !empty($m->course['className']) ? $m->course['className'] : '',
						'imgUrl'      => !empty($m->course['img']) ? $m->course['img'] : '',
						'teacherName' => !empty($m->course['teacherName']) ? $m->course['teacherName'] : '',
						'startTime'   => !empty($m->course['startTime']) ? date('n月j日H:i', strtotime($m->course['startTime'])) : '',
						'orderId'     => $m->fk_order,
						'orderStatus' => $m->status,
						'price'       => $m->price,
						'orderSn'     => $m->orderSn,
						'memberName'  => !empty($m->member['name']) ? $m->member['name'] : '',
						'memberPrice' => !empty($m->member['price']) ? $m->member['price'] : '',
						'memberDays'  => !empty($m->member['day']) ? $m->member['day'] : '',
						'createTime'  => date('Y-m-d', strtotime($m->create_time)),
						'outTradeId'  => $m->out_trade_id,
                        'isDelCourse' => !empty($m->course['deleted']) ? 1 : 0
					];
		}
        return $this->setData($result);
    }


    public function pageBasicInfo()
    {
         $res = user_api::getBasicUserAndMobile($this->paramsInfo['params']['uid']);

         if (!empty($res)) return $this->setData($res);

         return $this->setMsg(3002);
    }

    public function pageUserBasicInfo()
    {
        $userId = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;
        $res = user_api::getBasicUser($userId);
        if (empty($res)) return $this->setMsg(3002);

        $data = [
            'userId'     => $res->pk_user,
            'name'       => $res->name,
            'realName'   => $res->real_name,
            'mobile'     => $res->mobile,
            'thumbMed'   => interface_func::imgUrl($res->thumb_med),
            'thumbBig'   => interface_func::imgUrl($res->thumb_big),
            'thumbSmall' => interface_func::imgUrl($res->thumb_small),
        ];

        return $this->setData($data);
    }

    public function pageInfo()
    {
         $res = user_api::getBasicUser($this->paramsInfo['params']['userId']);
         if (empty($res)) return $this->setMsg(3002);

         $data = [
             'userToId' => $this->paramsInfo['params']['userId'],
             'userToName' => !empty($res->name) ? $res->name : SLanguage::tr('未设置', 'message'),
             'userImage' => $this->imgUrl($res->thumb_small)
         ];

        return $this->setData($data);
    }

    public function pageOrderDetail()
    {
        $this->v(['orderId'=>1000]);

        if (!utility_tool::check_int($this->paramsInfo['params']['orderId']))
            return $this->setMsg(1022);
		$userId     = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;
		$objectType = !empty($this->paramsInfo['params']['objectType']) ? (int)$this->paramsInfo['params']['objectType'] : 1;

		$data = interface_user_api::getOrderInfo($this->paramsInfo['params']['orderId'],$objectType);
        if (!empty($data)) {
			//剩余云点(支持云课/小沃)
			$priceSetting = !empty($this->paramsInfo['oid']) ? (int)$this->paramsInfo['oid'] : 0;
			$config = SConfig::getConfig(ROOT_CONFIG."/yunDianPrice.conf", $priceSetting);
			if(!empty($userId)){
				$balance = interface_user_api::getUserBalance($userId);
				$data['balance'] = $balance;
			}

            return $this->setData($data);
        }

        return $this->setData([]);
    }

	public function pageOrderInfo(){
		$this->v(['objectId'=>1000,'objectType'=>1000,'ext'=>1000,'userId'=>1000]);

		$objectType = (int)$this->paramsInfo['params']['objectType'];
 		$objectId   = (int)$this->paramsInfo['params']['objectId'];
		$orgId  = (int)$this->paramsInfo['oid'];
		$ext    = (int)$this->paramsInfo['params']['ext'];
		$userId = (int)$this->paramsInfo['params']['userId'];
		$data = array();
		if($objectType == 1){
			$courseParam = [
				'q' => ['course_id'=>$objectId,'fee_type'=>1],
				'f' => ['course_id','class','title','thumb_med','price','course_type']
			];
			$courseRes = seek_api::seekCourse($courseParam);
            if(empty($courseRes->data)){
                return $this->setMsg(1056);
            }
            //是否已报名
			$checkIsOk = course_api::checkusercanregistration($userId, $objectId, $ext);
			if(!$checkIsOk) return $this->setMsg(2069);

			//分销信息
            $resellCourseRes = !empty($orgId) ? course_resell_api::getCourseResell($objectId, $orgId) : array();

			if(!empty($courseRes->data)){
				foreach($courseRes->data as $val){
					$data = [
						'title'    => $val->title,
						'thumbMed' => $this->imgUrl($val->thumb_med),
                        //'price'    => (string)$val->price/100,
                        'price'    => !empty($resellCourseRes) ? (string)$resellCourseRes['price_resell'] / 100 : (string)$val->price/100,
                        'courseType'=> $val->course_type
					];
				}
			}
		}elseif($objectType == 11){
			$memberInfo = org_member_api::getMemberSetInfo($objectId);
			if(empty($memberInfo) || $memberInfo['status'] != 1) return $this->error(1000,"该会员已经被机构停用");
			$memberReg = org_member_api::getMemberSetInfo($objectId);
			if(empty($memberReg) || $memberReg['orgId']!=$orgId) return $this->setMsg(3002);

			if($ext == 30){
				$data['price'] = (string)$memberReg['price_30']/100;
				$data['ext']   = 30;
			}elseif($ext == 90){
				$data['price'] = (string)$memberReg['price_90']/100;
				$data['ext']   = 90;
			}elseif($ext == 180){
				$data['price'] = (string)$memberReg['price_180']/100;
				$data['ext']   = 180;
			}elseif($ext == 360){
				$data['price'] = (string)$memberReg['price_360']/100;
				$data['ext']   = 360;
			}else{
				return $this->setMsg(1000);
			}
			//新开通天数
			$data['time'] = date('Y-m-d', strtotime("+$ext day"));
			//续费
			$userMember = user_organization::checkMemberByUidAndSetId($userId, $objectId);
			if(!empty($userMember)){
				$data['time'] = date('Y-m-d', strtotime("$userMember->end_time +$ext day"));
			}
			$data['title'] = $memberReg['title'];
		}

		return $this->setData($data);
	}

	//验证支付宝
	public function pageCheckAlipay(){
		$uniqOrderId  = !empty($this->paramsInfo['params']['orderId']) ? $this->paramsInfo['params']['orderId'] : 0;
		$userInfo = user_api::loginedUser();
		$title    = !empty($this->paramsInfo['params']['title']) ? $this->paramsInfo['params']['title'] : '';

		if(empty($userInfo) || empty($uniqOrderId) || empty($title)) return $this->setMsg(1000);

		$orderInfo = order_api::getOrder(array('uniqueOrderId'=>$uniqOrderId));
		if(empty($orderInfo->items)) return $this->setMsg(3002);

		$domain_conf  = SConfig::getConfig(ROOT_CONFIG."/const.conf","alipay");

		$scheme       = utility_net::isHTTPS() ? 'https://' : 'http://';
		$out_trade_no = $orderInfo->items[0]->out_trade_id;
		$price        = $orderInfo->items[0]->price/100;
		$notify_url   = $scheme.$domain_conf->domain.'/course.buy.notify';
		$return_url   = $scheme.$domain_conf->domain.'/course.buy.return';

		require_once(ROOT_LIBS."/alipay/alipay.config.php");
		require_once(ROOT_LIBS."/alipay/lib/alipay_submit.class.php");
		require_once(ROOT_LIBS."/alipay/lib/alipay_core.function.php");

        $alipay_config['sign_type'] = "RSA";

        $parameter = array(
	        "partner"   => '"'.trim($alipay_config['partner']).'"',
			"seller_id"	=> '"gn100@talkweb.com.cn"',
			"out_trade_no"	=> '"'.$out_trade_no.'"',
			"subject"	    => '"'.$title.'"',
			"body"	        => '"'.$title.'"',
			"total_fee"	    => '"'.$price.'"',
			"notify_url"	=> '"'.$scheme.$domain_conf->domain.'/course.buy.notify"',
			"service"       => '"mobile.securitypay.pay"',
			"payment_type"	=> 1,
			"_input_charset"=> '"'.trim(strtolower($alipay_config['input_charset'])).'"',
            "it_b_pay"      => '"60m"',
			"return_url"	=> '"'.$scheme.$domain_conf->domain.'/course.buy.return"'
        );

		$alipaySubmit = new AlipaySubmit($alipay_config);
		$sign = $alipaySubmit->buildRequestMysign($parameter);
		$orgderStr = createLinkstring($parameter);

		return $this->setData(array("orderInfo"=>$orgderStr,"sign"=>$sign));
	}


	/*
	 * 个人信息
	 * @author zhengtianlong
	 * @params $uid
	 */
	public function pageGetInfo()
	{
        $gradeArr = [
            '1001' => '一年级',
            '1002' => '二年级',
            '1003' => '三年级',
            '1004' => '四年级',
            '1005' => '五年级',
            '1006' => '六年级',
            '2001' => '初一',
            '2002' => '初二',
            '2003' => '初三',
            '3001' => '高一',
            '3002' => '高二',
            '3003' => '高三'
        ];

        if (!utility_tool::check_int($this->paramsInfo['params']['uid'])) return $this->setMsg(1022);
		$uid = $this->paramsInfo['params']['uid'];
		$res = user_api::getUser($uid);
        if (empty($res)) return $this->setMsg(3002);
		$sexArr    = ['male'=>'男','female'=>'女'];

		$level0 = !empty($res->student->region_level0) ? $res->student->region_level0 : 0;
		$level1 = !empty($res->student->region_level1) ? $res->student->region_level1 : 0;
		$level2 = !empty($res->student->region_level2) ? $res->student->region_level2 : 0;
		if($level2 == 0)
		{
			$addId = $level1;
		}else
		{
			$addId = $level2;
		}

		//学校数据
		$schoolArr = array();
		$school    = array();
		$schoolArr = region_api::ListSchool($addId,$res->student->school_type);
		if(!empty($schoolArr))
		{
			foreach($schoolArr as $v)
			{
				$school[$v->school_id] = $v->school_name;
			}
		}

		//地区数据
		$address = '';
		if(!empty(region_geo::$region[$level0]))
		{
			$address.= region_geo::$region[$level0];
		}
		if(!empty(region_geo::$region[$level1]))
		{
			$address.= ','.region_geo::$region[$level1];
		}
		if(!empty(region_geo::$region[$level2]))
		{
			$address.= ','.region_geo::$region[$level2];
		}
		$data = [
			"nickName" => $res->name,
			"image"    => 'http:'.$res->avatar->large,
			"mobile"   => $res->mobile,
			"realName" => $res->profile->real_name,
			"sex"      => !empty($res->gender) ? $sexArr[$res->gender] : '',
			"address"  => $address,
			"addressId"=> $level0.",".$level1.",".$level2,
            "schoolType"=>$res->student->school_type,
			"school"   => !empty($school) ? (!empty($school[$res->student->school_id])?$school[$res->student->school_id]:'') : '',
			"schoolId" => !empty($res->student->school_id)?$res->student->school_id:0,
            "grade"    => !empty($res->student->grade) ? (!empty($gradeArr[$res->student->grade])?$gradeArr[$res->student->grade]:'') : '',
			"gradeId"  => !empty($res->student->grade) ? $res->student->grade : 0,
            "birthday" => $res->birthday
		];

		return $this->setData($data);
	}

	/*
	 * 修改个人信息
	 * @author zhengtianlong
	 * @params
	 */
	 public function pageSetUserInfo()
	 {
        if (empty($this->paramsInfo['params']['uid'])) return $this->setMsg(1000);

        $uid    = $this->paramsInfo['params']['uid'];
		if(isset($this->paramsInfo['params']['sex']))
		{
			$baseParams['gender'] = self::$gender[$this->paramsInfo['params']['sex']];
		}

        if(isset($this->paramsInfo['params']['schoolType']))
        {
            $fileParams['school_type'] = $this->paramsInfo['params']['schoolType'];
        }

        if(!empty($this->paramsInfo['params']['nickName']))
        {
		    //昵称不能超过15个字符
		    if(!empty($this->paramsInfo['params']['nickName']) && !utility_tool::check_string($this->paramsInfo['params']['nickName'],15,1))
		    {
			    return $this->setMsg(2036);
		    }
		    $baseParams['nickName'] = $this->paramsInfo['params']['nickName'];
        }
		if(!empty($this->paramsInfo['params']['realName']))
		{
			//不能是数字和特殊字符
			if(!preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',$this->paramsInfo['params']['realName']))
			{
				return $this->setMsg(2040);
			}
			//真实姓名不能超过10个汉字
			if(preg_match("/[\x7f-\xff]/", $this->paramsInfo['params']['realName']))
			{
				if(!utility_tool::check_string($this->paramsInfo['params']['realName'], 10,2))
				{
					return $this->setMsg(2037);
				}
			}
			//真实姓名不能超过50个英文字符
			if(preg_match("/[a-zA-Z\s]+$/", str_replace(' ','',$this->paramsInfo['params']['realName'])))
			{
				if(!utility_tool::check_string($this->paramsInfo['params']['realName'], 50,1))
				{
					return $this->setMsg(2038);
				}
			}
			$fileParams['student_name'] = $this->paramsInfo['params']['realName'];
			$baseParams['realName'] = $this->paramsInfo['params']['realName'];
		}

		if(!empty($this->paramsInfo['params']['birthday']))
		{
			$baseParams['birthday'] = $this->paramsInfo['params']['birthday'];
		}

		if(!empty($this->paramsInfo['params']['school']))
		{
			$fileParams['school_id'] = $this->paramsInfo['params']['school'];
		}

		if(!empty($this->paramsInfo['params']['grade']))
		{
			$fileParams['grade'] = $this->paramsInfo['params']['grade'];
		}

		if(!empty($this->paramsInfo['params']['address']))
		{
			$address = explode(',',$this->paramsInfo['params']['address']);
			$fileParams['region_level0'] = !empty($address['0'])?$address['0']:0;
			$fileParams['region_level1'] = !empty($address['1'])?$address['1']:0;
			$fileParams['region_level2'] = !empty($address['2'])?$address['2']:0;
		}

        if(!empty($baseParams))
        {
   		    $baseRet = user_api::updateBase2($uid,$baseParams);
			if($baseRet)
			{
				return $this->setMsg(0);
			}
        }

	    if(!empty($fileParams))
        {
		   $profileRet = user_api::setStudentProfile2($uid,$fileParams);

		   if($profileRet)
		   {
			  return $this->setMsg(0);
		   }
	    }

		return $this->setMsg(3002);

	 }

	 /*
	  * 修改头像
	  * @author zhengtianlong
	  * @params $uid
	  */
	  public function pageUploadPic()
	  {
          if (empty($this->paramsInfo['params']['uid'])) return $this->setMsg(1000);
		  $uid = $this->paramsInfo['params']['uid'];

          if (empty($this->paramsInfo['params']['image'])) return $this->setMsg(1000);
		  $img = $this->paramsInfo['params']['image'];
		  $data = array();
		  $path = ROOT_WWW."/upload/tmp";
		  if(!is_dir($path))
		  {
			mkdir($path,0777,true);
		  }
		  $filename = $path."/".$uid.".org.jpg";

		  if(file_put_contents($filename, base64_decode($img)))
		  {
			ini_set('gd.jpeg_ignore_warning', 1);;
			list($width, $height, $type, $attr) = getimagesize($filename);
			if(!$width || !$height)
			{
				return $this->setMsg(3002);
			}
			switch($type)
			{
				case 1: $img_r = imagecreatefromgif($filename);break;
				case 2: $img_r = imagecreatefromjpeg($filename);break;
				case 3: $img_r = imagecreatefrompng($filename);break;
				default:
					return $this->setMsg(3002);
			}

			$thumbs = user_thumb::genByFile($filename);
			/*
			  [large] => 4,1a20b130fa47
				[medium] => 4,1a218fcb2551
				[small] => 5,1a22c926a8e0
			*/
			if($thumbs)
			{
				$ret_up = user_api::updateThumbs($uid,$thumbs);
				$data = new stdclass;
				$data->thumb_big = $thumbs['large'];
                $data->thumb_med = $thumbs['medium'];
                $data->thumb_small = $thumbs['small'];
                $data->fk_user   = $uid;
                $data->create_time = date('Y-m-d H:i:s', time());
				user_api::addUserThumb($data);
				user_api::loginByUid($uid);

			}
		  }
		  $data = [
			'url'=>!empty($thumbs['medium'])?$this->imgUrl($thumbs['medium']):''
		  ];
		  return $this->setData($data);
	  }

	  /*
	   * 学校查询
	   * @author zhengtianlong
	   * @params $addressId
	   */
	   public function pageGetScholl()
	   {
           if (empty($this->paramsInfo['params']['addressId'])) return $this->setMsg(1000);
		   $addressStr = $this->paramsInfo['params']['addressId'];
		   $addressArr = explode(',',$addressStr);
           if(end($addressArr)==0)
           {
                $addressId = $addressArr[1];
           }else
           {
                $addressId = end($addressArr);
           }
           $result = region_api::ListSchool($addressId,'');
		   if (empty($result)) return $this->setMsg(3002);
		   foreach($result as $val)
		   {
               if($val->school_type == 1)
               {
                   $data['data']['primary'][] = [
                       'schoolId' => $val->school_id,
                       'name'     => $val->school_name
                   ];
               }
               if($val->school_type == 6)
               {
			        $data['data']['middle'][] = [
					    'schoolId' => $val->school_id,
				    	'name'     => $val->school_name
			        ];
               }
		   }
		   return $this->setData($data);
	   }

	   /*
	    * 地区接口
		* @author zhengtianlong
		*/
	   public function pageGetArea()
	   {
		   $data = region_area::$area;
		   return $this->setData($data);
	   }

	   /*
		* 年级查询
		* @author zhengtianlong
		* @params $gradeList
		*/
		public function pageGetGrade()
		{
			$data = course_grade::$data;
			return $this->setData($data);
		}

    /*
     * 取消订单
     * @author zhengtianlong
	 * @params $orderId
     */
    public function pageOrderStatus()
    {
        if(!utility_tool::check_int($this->paramsInfo['params']['orderId'])){
             return $this->setMsg(1022);
        }

		$orderId = (int)$this->paramsInfo['params']['orderId'];

		$orderInfo = order_api::getOrderByOrderId($orderId);
		if($orderInfo['status']=='initial')
		{
			$res = order_api::updateOrder($orderId, array('status'=>'cancel'));

			if($res->code == 1) return $this->setMsg(0);
		}
        $data = [
			'code'    => -1,
			'message' => 'error',
			"errMsg"  => "操作失败"
		];
        exit(json_encode($data));
    }
    /*
     * 删除订单
     * @author zhengtianlong
	 * @params $orderId
     */
    public function pageDelOrder()
    {
         if(!utility_tool::check_int($this->paramsInfo['params']['orderId']))
        {
             return $this->setMsg(1022);
        }

		$orderId = (int)$this->paramsInfo['params']['orderId'];

		$res = order_api::updateOrder($orderId, array('status'=>'deleted'));

        if($res->code == 1)
        {
            $data = [
                'code'    => 0,
                'message' => 'success'
            ];
        }else
        {
           $data = [
            'code'    => -1,
            'message' => 'error'
           ];
        }
        return $this->setData($data);

    }


    public function pageAddReg()
    {
        $this->v(
            [
                'userId'    => 1000,
                'courseId' => 1000,
                'classId' => 1000,
            ]
        );

        $courseId = (int)($this->paramsInfo['params']['courseId']);
        $classId  = (int)($this->paramsInfo['params']['classId']);
        $userId = (int)($this->paramsInfo['params']['userId']);

        $classData = course_api::getclass($classId);
        if (empty($classData)) return $this->setMsg(3002);

        if(!empty($classData->user_total) && !empty($classData->max_user)){
            if ($classData->user_total >= $classData->max_user)  return $this->setMsg(2034);
        }

        $courseInfo = course_api::getCourseone($courseId);
        if (empty($courseInfo)) return interface_func::setMsg(1056);

        if ($courseInfo->fee_type == 1) {
			//是否付费
			//判断是不是已经购买
			$setIdArr  = course_api::getCourseOpenMemberSetIdArr($courseId);
			if(empty($setIdArr)) return interface_func::setMsg(1);
			$memberRet = org_member_api::checkIsMemberOrExpire($userId, $setIdArr, $courseId);
			if($memberRet["isExpire"] || empty($memberRet["isOpen"])){
				//过期或者未开启报错
				return interface_func::setMsg(1);
			}
        }

        $data = array(
            "class_id"   => $classId,
            "course_id"  => $courseId,
            "uid"        => $userId,
            "user_owner" => $courseInfo->user_id,
            "status"     => 1,
        );

        if (course_api::addregistration($data)) {
            return interface_func::setMsg(0);
        }

        return interface_func::setMsg(1);
    }

    // 生成订单
    public function pageAddOrder()
    {
        $this->v(
            [
                'uid'    => 1000,
                'courseId' => 1000,
                'classId' => 1000,
            ]
        );

        $courseId = (int)($this->paramsInfo['params']['courseId']);
        $classId  = (int)($this->paramsInfo['params']['classId']);
        $userId = (int)($this->paramsInfo['params']['uid']);
		$orderType = empty($this->paramsInfo['params']['orderType'])?1:(int)($this->paramsInfo['params']['orderType']);//订单类型
		if($orderType==11){
			//会员类型购买下面废弃
			return $this->setMsg(1000);
			//会员
			$memberInfo = org_member_api::getMemberSetInfo($courseId);
			if(empty($memberInfo) || $memberInfo['status'] != 1){
				return interface_func::error(1000,'获取数据失败');
			}
			if($classId == 30){
				$priceOld = $price = $memberInfo['price_30']/100;
				$ext   = 1;
			}elseif($classId == 90){
				$priceOld = $price = $memberInfo['price_90']/100;
				$ext   = 2;
			}elseif($classId == 180){
				$priceOld = $price = $memberInfo['price_180']/100;
				$ext   = 3;
			}elseif($classId == 360){
				$priceOld = $price = $memberInfo['price_360']/100;
				$ext   = 4;
			}else{
				return interface_func::error(20010,'金额设置错误');
			}
			$objectType = 11;
			$oid = $memberInfo['orgId'];
		}else{
			$courseInfo = course_api::getCourseone($courseId);
			if (empty($courseInfo)) return interface_func::setMsg(1056);
			// 免费课不能生成订单
			if ($courseInfo->fee_type == 0) {
				return interface_func::setMsg(2035);
			}

			$orgRes = user_organization::getOrgByOwner($courseInfo->user_id);
			$oid = $orgRes->oid;
			$objectType = 1;
			$price = $courseInfo->fee->price;
			$priceOld = $courseInfo->fee->price;
		}
         //生成订单
        $data[] = [
                'orgId'      => $oid,
                'userId'     => $userId,
                'objectId'   => $courseId,
                'objectType' => $objectType,//不考虑会员
                'price'      => $price,
                'priceOld'   => $priceOld,
                'ext'        => $classId
        ];
        $res = order_api::addOrder($userId, $data);
        if ($res->result->code == 0) {
            $orderInfo = interface_user_api::getOrderInfo($res->data->order_id,$orderType);
            if (!empty($orderInfo)) {
                return $this->setData($orderInfo);
            }
            // 刚生成的订单 获取不到信息
            return $this->setMsg(3000);
        }

        return $this->setMsg(1);
    }

	//小沃生成订单
	public function pageAddOrderV2(){

		$this->v(['userId'=>1000,'objectId'=>1000,'ext'=>1000,'objectType'=>1000]);
		$userId     = (int)$this->paramsInfo['params']['userId'];
		$objectId   = (int)$this->paramsInfo['params']['objectId'];
		$objectType = (int)$this->paramsInfo['params']['objectType'];
		$ext        = (int)$this->paramsInfo['params']['ext'];
		$codeId     = !empty($this->paramsInfo['params']['codeId']) ? (int)$this->paramsInfo['params']['codeId'] : 0;
		$code       = !empty($this->paramsInfo['params']['code']) ? trim($this->paramsInfo['params']['code']) : '';

		//用户使用优惠码状态
		$disStatus = 0;
		if($objectType == 1){
			$courseInfo = course_api::getCourseone($objectId);
			if(empty($courseInfo)) return $this->setMsg(1056);
			if($courseInfo->fee_type == 0) return $this->setMsg(2035);

			//分销信息(目前是写死的842)
            $resellOrgId = !empty($this->paramsInfo['oid']) ? (int)$this->paramsInfo['oid'] : 0;
            $resellCourseRes = array();
            if(!empty($resellOrgId) && $courseInfo->is_promote) {
                $resellCourseRes = course_resell_api::getResellCourseInfo($objectId, $resellOrgId);
            }
            $price = !empty($resellCourseRes) ? $resellCourseRes['priceResell'] : $courseInfo->fee->price;

			//优惠码
			if(!empty($code)){
				$params = [
					'code'     => $code,
					'objectId' => $objectId,
					'userId'   => $userId,
					'isRot'    => 1
				];
				$codeRes = order_api::checkCodeNew($params);
				if(isset($codeRes->object_type)) $codeRes = json_decode($this->checkPay($codeRes));
				if($codeRes->code == 0){
					$price = $codeRes->result->price;
					$disStatus = $codeRes->result->discountCodeId;
				}else{
					return $codeRes;
				}
				/*if($codeRes->code == 0){
					$price = $codeRes->result->price;
					$disStatus = $codeRes->result->discountCodeId;
				}else{
					return $this->setMsg(2050);
				}*/
			}

			$orgRes = user_organization::getOrgByOwner($courseInfo->user_id);
			$orgId  = $orgRes->oid;
			$priceOld   = $courseInfo->fee->price;

		}elseif($objectType == 11){
			$memberInfo = org_member_api::getMemberSetInfo($objectId);
			if(empty($memberInfo) || $memberInfo['status'] != 1) return interface_func::error(1000, "该会员已经被机构停用");
			if($ext == 30){
				$priceOld = $memberInfo['price_30'] / 100;
				$price    = $memberInfo['price_30'] / 100;
				$ext      = 1;
			}elseif($ext == 90){
				$priceOld = $memberInfo['price_90'] / 100;
				$price    = $memberInfo['price_90'] / 100;
				$ext      = 2;
			}elseif($ext == 180){
				$priceOld = $memberInfo['price_180'] / 100;
				$price    = $memberInfo['price_180'] / 100;
				$ext      = 3;
			}elseif($ext == 360){
				$priceOld = $memberInfo['price_360'] / 100;
				$price    = $memberInfo['price_360'] / 100;
				$ext      = 4;
			}else{
				return $this->setMsg(2022);
			}

			$orgId = $memberInfo['orgId'];
		}

		if(empty($orgId) || empty($priceOld)){
			return $this->setMsg(2022);
		}

        $data[] = [
            'orgId'      => $orgId,
            'userId'     => $userId,
            'objectId'   => $objectId,
            'objectType' => $objectType,
            'price'      => $price,
            'priceOld'   => $priceOld,
            'ext'        => $ext,
            'pricePromote' => !empty($resellCourseRes) ? $resellCourseRes['pricePromete'] : 0,
            'resellOrgId'  => !empty($resellCourseRes) ? $resellCourseRes['resellOrgId'] : 0,
            'promoteStatus'=> !empty($resellCourseRes) ? 1 : 0
        ];
        $res = order_api::addOrder($userId, $data, $disStatus);

		if ($res->result->code === 0) {
			$orderInfo = interface_user_api::getOrderInfo($res->data->order_id, $objectType);
            if (!empty($orderInfo)) {
				//剩余云点(支持云课/小沃)
				$priceSetting = !empty($this->paramsInfo['oid']) ? (int)$this->paramsInfo['oid'] : 0;
				$config = SConfig::getConfig(ROOT_CONFIG."/yunDianPrice.conf",$priceSetting);
				$balance = interface_user_api::getUserBalance($userId);
				$orderInfo['balance'] = $balance;

				//优惠码满额减直接报名(只支持课程)
				if($price <= 0){
					$updateOrderData = [
						'price'    => 0,
						'pay_type' => '',
						'status'   => 'success'
					];
					$ret = order_api::updateOrder($res->data->order_id, $updateOrderData);
					$orgInfo = org_api::getOrgInfo($orgId);
					$regData = [
						'uid'        => $userId,
						'course_id'  => $objectId,
						'class_id'   => $ext,
						'user_owner' => $orgInfo->user_owner_id,
						'status'     => 1
					];
					$regStatus = course_api::addregistration($regData);
				}

                return $this->setData($orderInfo);
            }
            // 刚生成的订单 获取不到信息
            return $this->setMsg(3000);
        }

		return $this->setMsg(2022);
	}

	//验证优惠码
	public function pageCheckCode(){
		$this->v(['code'=>1000,'courseId'=>1000,'userId'=>1000]);
		if(empty($this->paramsInfo['token'])) return $this->setMsg(1000);

		$data = [
			'code'     => trim($this->paramsInfo['params']['code']),
			'objectId' => (int)$this->paramsInfo['params']['courseId'],
			'userId'   => (int)$this->paramsInfo['params']['userId'],
			'isRob'    => 1
		];

//		$codeRes = order_api::checkCode($data);
		$codeRes = order_api::checkCodeNew($data);
		if(!isset($codeRes->object_type)) return $codeRes;
		return $this->checkPay($codeRes);
		/*if(empty($codeRes->code)){
			$data = [
				'priceOld' => $codeRes->result->priceOld,
				'disPirce' => $codeRes->result->disPrice,
				'disCodeId'=> $codeRes->result->discountCodeId
			];
			return $this->setData($data);

		}
		return json_encode(array('code'=>$codeRes->code,'errMsg'=>$codeRes->msg), JSON_UNESCAPED_UNICODE);*/
	}

    public function pageProtocol()
    {
        $data = '云课直播平台服务协议
一、服务项目
云课直播平台是北京高能壹佰教育科技有限公司利用服务器及相关软件为用户提供网络教育及收费所需要的相应功能，同时提供相关技术及网络支持服务。
云课直播平台服务的具体内容和收费形式以云课直播平台网站及产品中所具有的功能和收费为准。

二、协议的确认与接受
用户必须完全同意《云课直播平台服务协议》全部条款，方可注册开通云课直播平台服务。用户使用云课直播平台网络服务的行为将视为用户自动签定本协议，并接受本协议全部内容和条款。

三、费用结算方式
用户使用云课直播平台服务在平台上开展收费项目所得金额，按照用户与云课直播平台实际合同约定分成，由云课直播平台定期进行结算，并支付给用户。
云课直播平台会不定期向用户赠送服务项目。用户充分理解所有的赠送服务项目均为云课直播平台在正常服务之外的一次性特别优惠，如本协议解除，会自动扣除相应金额。对于赠送的服务项目，云课直播平台拥有最终的解释权。

四、知识产权
用户利用云课直播平台服务产生的任何作品，包括但不限于图片、音乐、视频的知识产权均归用户和高能壹佰共同所有，高能壹佰有权使用上述作品。
除法律法规另有规定外，未获得权利人的事先授权，用户不得随意使用第三方作品。如果云课直播平台收到任何第三方作品所有人或其合法代表发出的合理通知后，有权移除相应侵权作品。
五、云课直播平台的保证
云课直播平台根据所适用的法律，已经取得签订并履行本协议的相关资质。
云课直播平台签订并履行本协议不会侵犯任何人的知识产权或其他合法权利。如果针对本协议的签订或者履行出现任何第三人指控侵权之情形， 云课直播平台将采取一切必要的措施进行抗辩。
六、用户的保证
用户根据所适用的法律，已经取得签订并履行本协议的相关资质。
用户签订并履行本协议不会侵犯任何人的知识产权或其他合法权利。如果针对本协议的签订或者履行出现任何第三人指控侵权之情形， 用户将采取一切必要的措施进行抗辩。
用户保证在使用云课直播平台网络服务过程中提供的信息不含有任何违反国家有关法律、法规及中华人民共和国承认或加入的国际条约的内容， 包括但不限于危害国家安全、淫秽色情、虚假、侮辱、诽谤、恐吓或骚扰、侵犯他人知识产权、人身权或其他合法权益以及有违 社会公序良俗的内容或指向这些内容的链接。
因用户违反本协议，而导致云课直播平台受到第三方投诉或诉讼，或面临相关主管机关的审查或质询，云课直播平台有权先暂停对用户提供的服务。用户应在收到云课直播平台通知后，应以自己名义出面与第三方协商、应诉或接受相关主管机关审查或质询，并承担所有费用（包括但不限于 诉讼费、律师费、赔偿款），并赔偿因此给云课直播平台造成的全部损失。
如用户违反上述任何条款，云课直播平台有权终止向用户提供服务，并解除本协议，同时保留有进一步追究用户法律责任的权利。
七、保密
在本协议订立前、履行中及终止或解除后，未经协议其他方书面同意，任何一方对本协议履行过程中所知悉的资料、信息、数据负有保密责任。未经一方书面同意，任何一方不得超越本协议履行之目的自行使用，或授权任何其他第三方使用，或向其他第三方泄露。
如对方提出要求，任何一方均应将载有对方保密信息之原件、副本、重制本及节录本按对方要求归还，或予以删除、销毁，并且不得继续使用这些保密信息。
本保密条款具有独立性，不受本协议的终止或解除的影响。
八、协议变更
一方变更通知、通讯地址或者其他联系方式，应自变更之日起3个工作日内，将变更后的地址、联系方式通知另一方，否则变更方应对此造成的一切后果承担责任。
云课直播平台有权随时根据中华人民共和国有关法律、法规的变化、互联网的发展以及公司经营状况和经营策略的调整等情况修改本协议，但不得单方不公平地加大用户的义务。协议条款一旦发生变动，云课直播平台将会在其官方网页上提示有关内容。用户如果不同意所改动的内容，可以在7个工作日内停止接受服务并同时书面通知云课直播平台，对本协议进行解除；如果用户继续接受服务，则视为接受本协议条款的修改。
在本协议有效期内，一方出现上市、被收购、分立、与第三方合并、名称变更等事由，另一方同意出现上述事由的一方可以将其在本协议中的权利和义务转让给相应的权利或义务承受者，但转让方应保证另一方在本协议中的权益不会因此而受到不利影响，否则转让方应承担由此引起的一切法律责任和经济损失。
九、协议解除
如任何一方严重违反本协议，则另一方有权解除本协议。

十、不可抗力
“不可抗力”是指甲乙双方不能合理控制、不可预见或即使预见亦无法避免的事件，该事件妨碍、影响或延误任何一方根据协议履行其全部或部分义务。该事件包括但不限于政府行为、自然灾害、战争、黑客攻击、计算机病毒（如木马程序、蠕虫等）、电信部门技术性调整或任何其它类似事件。
出现不可抗力事件时，受影响方应及时、充分地以邮件或电话方式通知对方，并告知对方该类事件对本协议可能产生的影响，并应当在合理期限（不可抗力事件发生后30日）内提供该等事件的详细信息及由有关组织出具的解释受影响方因此无法履行全部或部分本协议项下义务的相关证明。
由于以上所述不可抗力事件致使协议的部分或全部不能履行或延迟履行，则甲乙双方于彼此间不承担任何违约责任。
十一、争议解决
由本协议产生的一切争议由双方友好协商解决。协商不成的，双方一致同意将争议提交北京仲裁委员会，按其在提起仲裁时现行有效的仲裁规则进行仲裁。仲裁裁决是终局的，协议双方都应执行。仲裁费用由败诉方承担。
本协议 的订立、效力、解释、履行和争议的解决均应适用中华人民共和国法律。
十二、其他
云课直播平台在相关网页上及产品提供的服务说明、价格说明、表格等均为本协议不可分割的一部分，与本协议具有同等法律效力。
任何一方没有行使其权利或者没有就对方的违约行为采取任何行动，不应被视为是对权利的放弃或者对追究违约责任或者义务的放弃。任何一方放弃针对对方的任何权利，或者放弃追究对方的任何过失，不应视为对任何其他权利或者追究任何其他过失的放弃。
无论因何种原因（包括但不限于违反适用的法律法规）导致本协议任何条款完全或部分无效或不具有执行力，不影响本协议任何其他条款的效力、合法和可执行性。
任何一方致另一方的通知，均可通过网站通告、站内邮件或电子邮件发送。
';
		return $this->setData($data);
    }


	/*
	 * 学生所报课程与班级
	 * @params $uid 老师id  $stuId 学生id
	 * @author zhengtianlong
	 */
	public function pageGetStudentCourse()
	{
		$this->v(['uid'=>1000,'stuId'=>1000]);
		$page  = !empty($this->paramsInfo['params']['page'])?$this->paramsInfo['params']['page']:1;
		$length= !empty($this->paramsInfo['params']['length'])?$this->paramsInfo['params']['length']:10;
		$uid   = $this->paramsInfo['params']['uid'];
		$stuId = $this->paramsInfo['params']['stuId'];


		//老师下所有的课
		$planParams = [
			'q' => ['teacher_id'=>$uid,'status'=>'1,2,3'],
			'f' => ['course_id','class_id','class_name'],
			'pl'=> 1000
		];

		$res_plan = seek_api::seekPlan($planParams);
		if (empty($res_plan->data)) return $this->setMsg(3002);
		$teacherCidArr = array_reduce($res_plan->data, create_function('$v,$w', '$v[$w->course_id]=$w->course_id;return $v;'));

		//学生报所有课程
		$res_student = course_api::getstudentCourse($stuId);

		if (empty($res_student->data)) return $this->setMsg(3002);
		$studentCidArr = array_reduce($res_student->data, create_function('$v,$w', '$v[$w->fk_course]=$w->fk_course;return $v;'));
        $studentClassArr = array_reduce($res_student->data, create_function('$v,$w', '$v[$w->fk_class]=$w->fk_class;return $v;'));

		//该学生所报该老师的课程id
		$intersectArr = array_intersect_assoc($teacherCidArr,$studentCidArr);

    	if (empty($intersectArr)) return $this->setMsg(3002);
		$intersectStr = implode(',',$intersectArr);
        $studentClassStr = implode(',',$studentClassArr);

       	//课程信息
		$courseParams = [
			'q' => ['course_id'=>$intersectStr,'status'=>'1,2,3'],
			'f' => ['thumb_med','course_id'],
			'pl'=> 1000
 		];

		$res_course = seek_api::seekCourse($courseParams);
		if (empty($res_course->data)) return $this->setMsg(3002);


        $course = array();
        foreach($res_course->data as $v)
        {
            $course[$v->course_id] = [
                'img'    => $this->imgUrl($v->thumb_med)
            ];
        }

        $params = [
            'q'  => ['course_id'=>$intersectStr,'status'=>'1,2,3','class_id'=> $studentClassStr] ,
            'f'  => ['course_id','class_id','class_name','status','course_name'],
            'pl' => 1000
        ];
        $result = seek_api::seekPlan($params);

	   	if (empty($result->data)) return $this->setMsg(3002);
        $courseType = 0;
        foreach($result->data as $v)
        {
            $list[$v->class_id] = [
                'courseId'   => $v->course_id,
                'courseName' => $v->course_name,
                'courseType' => $courseType,
                'status'     => $v->status,
                'classId'    => $v->class_id,
                'className'  => $v->class_name,
                'image'      => !empty($course[$v->course_id]['img'])?$course[$v->course_id]['img']:''
            ];
           	if($courseType > 0)
			{
				$list[$v->course_id]['address'] = '';
				$list[$v->course_id]['time']    = '';
			}
        }


        $data['total']     = count($list);
        $data['totalPage'] = ceil($data['total']/$length);
        $data['page']      = $page;

        $data['data'] = array_values($list);
        $offset       = ($page-1)*$length;
        $data['data'] = array_slice($data['data'],$offset,$length);


        if (empty($data['data'])) return $this->setMsg(3002);

		return $this->setData($data);
	}

	/*
	 * 意见反馈
	 * @params  $type状态  $content 内容  $mobile 手机号
	 * @author zhengtianlong
	 */
	public function pageFeedBack()
	{
		$this->v(['type'=>1000,'content'=>1000,'mobile'=>1013]);
		$type    = $this->paramsInfo['params']['type'];
		$content = htmlspecialchars($this->paramsInfo['params']['content']);
		$mobile  = $this->paramsInfo['params']['mobile'];
		$uid     = isset($this->paramsInfo['params']['uid'])?$this->paramsInfo['params']['uid']:0;

		$regex = '/^1[3|4|5|7|8][0-9]{9}$/';
		if (!empty(trim($mobile)) && !preg_match($regex, $mobile))
		{
			return $this->setMsg(1012);
		}
		$data = [
			'fk_user'     => $uid,
			'ques_type'   => $type,
			'content'     => $content,
			'mobile'      => $mobile,
			'create_time' => date('Y-m-d H:i:s', time())
		];

		$res = user_api::addUserFeedback($data);

		if($res->result->code==0)
		{
			return $this->setMsg(0);
		}
		return $this->setMsg(3002);
	}

    public function pagePartner()
    {
        $this->v(
            [
                'type' => 1000,
                'openId' => 1000
            ]
        );

        if (!in_array($this->paramsInfo['params']['type'], [1,2])) {
            return interface_func::setMsg(1026);
        }

        $partnerInfo = user_api::getParterner(
            $this->paramsInfo['params']['type'],
            $this->paramsInfo['params']['openId']
        );

        if (empty($partnerInfo->parterner_id)) {
            $res = user_api::setParterner(
                0,
                $this->paramsInfo['params']['nickName'],
                $this->paramsInfo['params']['type'],
                $this->paramsInfo['params']['openId'],
                json_encode($this->paramsInfo['params']['info']),
                $this->paramsInfo['params']['authCode'],
                $this->paramsInfo['params']['thumbUrl']
            );

            if (empty($res->parterner_id)) return interface_func::setMsg(3000);
        }

        if (empty($partnerInfo->uid)) return interface_func::setMsg(1037);
        // 检测是否设置真实姓名(只检测t_user表)
        $userInfo = user_api::getBasicUser($partnerInfo->uid);
        if (empty($userInfo)) return interface_func::setMsg(3002);

        if (user_api::loginByUid($partnerInfo->uid)) {
			$header = utility_net::isHTTPS() ? "https" : "http";
            $data = utility_session::get()['user'];
			$data['large']    = $header.':'.utility_session::get()['user']['large'];
            $data['medium']   = $header.':'.utility_session::get()['user']['medium'];
            $data['small']    = $header.':'.utility_session::get()['user']['small'];
            $data['mobile']   = $userInfo->mobile;
            $data['realName'] = !empty($userInfo->real_name) ? $userInfo->real_name : SLanguage::tr('未设置', 'message');
            return interface_func::setData($data);
        }

        return interface_func::setMsg(1);
    }

    public function pageCheckIsBind()
    {
        $this->v(
            [
                'mobile' => 1000,
                'code'   => 1000,
                'openId' => 1000,
                'type'   => 1000
            ]
        );

        if (!verify_api::verifyMobile($this->paramsInfo['params']['mobile'], $this->paramsInfo['params']['code'])) {
            return $this->setMsg(2011);
        }

        $partnerInfo= user_api::getParterner(
            $this->paramsInfo['params']['type'],
            $this->paramsInfo['params']['openId']
        );
        if (empty($partnerInfo)) return interface_func::setMsg(1031);

        $userInfo = utility_services::call("/user/info/GetUserInfoByMobile/{$this->paramsInfo['params']['mobile']}");

        if ($userInfo->code || empty($userInfo->result->real_name))
            return interface_func::setMsg(2042);

        $res = user_api::bindParterner($partnerInfo->parterner_id, $userInfo->result->pk_user);
        if (empty($res)) return interface_func::setMsg(1032);

        if (user_api::loginByUid($userInfo->result->pk_user)){
            $streamData = array();
            if(!empty($this->paramsInfo['params']['streamInfo']) && $this->paramsInfo['params']['streamInfo'] == 'yes'){
                $token = live_auth::getPublistAuth($userInfo->result->pk_user);
                if(empty($token)){
                    $ret = live_auth::setPublistAuth($userInfo->result->pk_user);
                    $ret && $token = live_auth::getPublistAuth($userInfo->result->pk_user);
                }
                $streamData['streamInfo'] = [
                    'server' => $token->server,
                    'stream' => $token->stream_name
                ];
            }
            $data = array_merge(utility_session::get()['user'], $streamData);
            return interface_func::setData($data);
        }

        return interface_func::setMsg(1);
    }

    public function pageUpdateUserRealName()
    {
        $this->v(
            [
                'mobile'   => 1000,
                'userName' => 1000,
                'openId'   => 1000,
                'type'     => 1000
            ]
        );

        if (!in_array($this->paramsInfo['params']['type'], [1,2])) {
            return interface_func::setMsg(1026);
        }

        // 检测是否注册
        $uid = user_api::isRegister($this->paramsInfo['params']['mobile']);
        if (empty($uid)) {
            $partnerInfo= user_api::getParterner(
                $this->paramsInfo['params']['type'],
                $this->paramsInfo['params']['openId']
            );
            if (empty($partnerInfo)) return interface_func::setMsg(1031);

            // 用真实姓名注册
            $password = substr($this->paramsInfo['params']['mobile'], 5, 6);
            $uid = user_api::registerByMobile(
                $this->paramsInfo['params']['userName'],
                $this->paramsInfo['params']['mobile'],
                $password
            );
            if (empty($uid)) return interface_func::setMsg(2012);

			//发送默认密码
			verify_api::sendUserOfMobileAndPasswordSMS($this->paramsInfo['params']['mobile'],$password);

            // update user source
            $source = 0;
            if ($this->paramsInfo['u'] == 'a') {
                $source = 1001; //android
            }

            if ($this->paramsInfo['u'] == 'i') {
                $source = 1002; //ios
            }

            $updateSourceRes = utility_services::call('/user/info/UpdateUserSource', [$uid, $source]);
            if ($updateSourceRes->code) {
                SLog::fatal('update user source failed,uid[%d],source[%d]', $uid, $source);
            }

            $res = user_api::bindParterner($partnerInfo->parterner_id, $uid);
            if (empty($res)) return interface_func::setMsg(1032);
        }
        if(user_api::loginByUid($uid)){
            $header = utility_net::isHTTPS() ? "https" : "http";
            $data   = utility_session::get()['user'];
            $data['large']  = $header.':'.utility_session::get()['user']['large'];
            $data['medium'] = $header.':'.utility_session::get()['user']['medium'];
            $data['small']  = $header.':'.utility_session::get()['user']['small'];
            return interface_func::setData($data);
        }

        return interface_func::setMsg(1);
    }

	/*
	 * 成长体系 获取成长值
	 * @params uid
	 */
	public function pageUserLevel(){
		$this->v(['uid'=>1000]);
		$uid = $this->paramsInfo['params']['uid']; //= 835;
		$day = date('Y-m-d',time());
		//是否签到
		$signInfo = user_api::getUserSignByDay($day,$uid);


		//签到天数
		$lastSign = user_api::getLastUserSign($uid);
		$yesterday= date('Y-m-d',time()-3600*24);
		if(!empty($lastSign) && !empty($signInfo)){
			$days = $signInfo->combo;
		}elseif(!empty($lastSign) && empty($signInfo)){
			if($lastSign->day != $yesterday ){
				$days = 0;
			}elseif($lastSign->day == $yesterday ){
				$days = $lastSign->combo;
			}
		}else{
			$days = 0;
		}

		//用户状态(1 学生 2 老师)
		$userType = user_api::checkSpecial($uid);
		//用户等级信息
		$userLevelRes = user_api::getUserLevel($uid);

		$regInfo  = course_api::listRegistration(array("uid"=>$uid));
		$lookPlan = user_api::getUserPlanStatCountByUid($uid);
		$studyReg = user_api::getUserStatByUid($uid);
		//学习时间
		$studyTime = 0;
		$finish    = 0;
		if(!empty($studyReg)){
			$studyTime = ceil(($studyReg->vt_live + $studyReg->vt_record)/3600);
		}
        //已学课程数
        $course = 0;
		if(!empty($regInfo->data)){
			foreach($regInfo->data as $val){
				$myCourseId[] = $val->cid;
				$myClassId[]  = $val->class_id;
			}
			$query = [
				'course_id'=>implode(',',$myCourseId),'class_id'=>implode(',',$myClassId),
				'status'=>'1,2,3','course_type'=>'1,2'
			];
			$seekPlan = seek_api::seekPlan(array('q'=>$query,'p'=>1,'pl'=>1000));

			if(!empty($seekPlan->data)){
				foreach($seekPlan->data as $v){
					$planIdArr[] = $v->plan_id;
				}
				$finish = user_api::getUserPlanStatCountByPid($uid,$planIdArr);
			}
            $course = $regInfo->size;
		}
		$nextLevelRes = user_api::getNextLevel($userLevelRes->fk_level);
		//距下个等级所需要的经验
		$nextEx    = 0;
		if(!empty($nextLevelRes)){
			$nextEx = $nextLevelRes->score_min - $userLevelRes->score;
		}

		$teacher = array();
		if($userType == 2){
			$params = [
				'q' => ['teacher_id'=>$uid],
				'f' => ['student_count','course_count','totaltime','avg_score']
			];
			$teacherRes = seek_api::seekTeacher($params);
			if(!empty($teacherRes->data)){
				$teacherInfo = $teacherRes->data[0];
				$teacher = [
					"students"       => $teacherInfo->student_count,
					"teacherCourses" => $teacherInfo->course_count,
					"teachingTime"   => number_format($teacherInfo->totaltime/3600,1),
					"score"          => round($teacherInfo->avg_score, 1)
				];
			}
		}

		$student = [
			"day"       => $days,
			"ex"        => !empty($userLevelRes->score) ? $userLevelRes->score : 0,
			"level"     => !empty($userLevelRes->fk_level) ? $userLevelRes->fk_level : '1',
			"levelName" => !empty($userLevelRes->title) ? $userLevelRes->title : '书生1',
			"status"    => !empty($signInfo) ? 1 : 0,
			"grow"      => ($days<5) ? 2 : 7,
			"courses"   => $course,
			"finish"    => $finish,
			"learningTime" => $studyTime,
			"videoTimes"   => $lookPlan,
			"continueDay"  => 5-$days,
			"extraEx"      => 5,
			"nextEx"       => $nextEx,
			"yd"           => interface_user_api::getUserBalance($uid),
			"levelColor"   => interface_user_api::bigLevel($userLevelRes->fk_level),
			"nextLevelName"=> $nextLevelRes->title
		];

		$data = array_merge($student,$teacher);
		return $this->setData($data);
	}

	/*
	 * 签到接口
	 * @params uid
	 */
	public function pageSign(){
		$this->v(['uid'=>1000]);
		$uid = $this->paramsInfo['params']['uid'];
		$day = date('Y-m-d',time());
		$signInfo = user_api::getUserSignByDay($day,$uid);
		if(empty($signInfo)){
			//执行签到
			$result = user_api::changeUserLevelAndScore($uid,'SIGN');
		    $signInfo = user_api::getUserSignByDay($day,$uid);
		}
		//用户等级信息
		$userLevelRes = user_api::getUserLevel($uid);

		//是否签到
		$isSign = (!empty($result->data) || !empty($signInfo)) ? 1 : 0;

		//签到天数
		$lastSign = user_api::getLastUserSign($uid);
		$yesterday= date('Y-m-d',time()-3600*24);
		if(!empty($lastSign) && !empty($signInfo)){
			$days = $signInfo->combo;
		}elseif(!empty($lastSign) && empty($signInfo)){
			if($lastSign->day != $yesterday ){
				$days = 0;
			}elseif($lastSign->day == $yesterday ){
				$days = $lastSign->combo;
			}
		}else{
			$days = 0;
		}

		$nextLevelRes = user_api::getNextLevel($userLevelRes->fk_level);
		//距下个等级所需要的经验
		$nextEx    = 0;
		if(!empty($nextLevelRes)){
			$nextEx = $nextLevelRes->score_min - $userLevelRes->score;
		}

		$data = [
			"day"         =>$days,
			"ex"          =>!empty($userLevelRes->score) ? $userLevelRes->score : 0,
			"level"       =>!empty($userLevelRes->fk_level) ? $userLevelRes->fk_level : '1',
			"levelName"   =>!empty($userLevelRes->title) ? $userLevelRes->title : '1',
			"status"      =>$isSign,
			"grow"        =>($days<5) ? 2 : 7,
			"continueDay" => 5-$days,
			"extraEx"     => 5,
			"nextEx"      => $nextEx,
			"levelColor"  => interface_user_api::bigLevel($userLevelRes->fk_level),
			"nextLevelName"=> $nextLevelRes->title
		];

		return $this->setData($data);
	}

	//评论
	public function pageAddComment()
	{
		$this->v([
			'planId' => 1000,
			'uid'    => 1000,
			'content'=> 1000
		]);

		$planId       = (int)($this->paramsInfo['params']['planId']);
		$uid          = (int)($this->paramsInfo['params']['uid']);
		$content      = $this->paramsInfo['params']['content'];
		$satisfaction = isset($this->paramsInfo['params']['satisfaction'])? $this->paramsInfo['params']['satisfaction'] : 5;
		$conform      = isset($this->paramsInfo['params']['conform']) ? $this->paramsInfo['params']['conform'] : 5;
		$expression   = isset($this->paramsInfo['params']['expression']) ? $this->paramsInfo['params']['expression'] : 5;
		$params = [
			'q' => ['plan_id'=>$planId],
			'f' => ['course_id','teacher_id','plan_id','owner_id']
		];
		$seekPlan = seek_api::seekPlan($params);
		if (empty($seekPlan->data)) return $this->setMsg(3002);

		$planRes = $seekPlan->data[0];
		$comment = [
			'course_id'    => $planRes->course_id,
			'user_id'      => $uid,
			'plan_id'      => $planRes->plan_id,
			'user_owner'   => $planRes->owner_id,
			'user_teacher' => $planRes->teacher_id,
			'comment'      => $content,
			'score'=> $satisfaction,
		];
		$resScore   = comment_api::addScore($comment, $uid);
		if($resScore['code']<1){
			return $this->error(1000,$resScore['msg']);
		}
		$day = date('Y-m-d',time());
		$signInfo = user_api::getUserSignByDay($day,$uid);
		if(empty($signInfo)){
		    $signInfo = user_api::getUserSignByDay($day,$uid);
		}
		//用户等级信息
		$userLevelRes = user_api::getUserLevel($uid);
		//是否签到
		$isSign = (!empty($result->data) || !empty($signInfo)) ? 1 : 0;
		//签到天数
		$lastSign = user_api::getLastUserSign($uid);
		$yesterday= date('Y-m-d',time()-3600*24);
		if(!empty($lastSign) && !empty($signInfo)){
			$days = $signInfo->combo;
		}elseif(!empty($lastSign) && empty($signInfo)){
			if($lastSign->day != $yesterday ){
				$days = 0;
			}elseif($lastSign->day == $yesterday ){
				$days = $lastSign->combo;
			}
		}else{
			$days = 0;
		}
		$nextLevelRes = user_api::getNextLevel($userLevelRes->fk_level);
		//距下个等级所需要的经验
		$nextEx    = 0;
		if(!empty($nextLevelRes)){
			$nextEx = $nextLevelRes->score_min - $userLevelRes->score;
		}

		$data = [
			"day"         =>$days,
			"ex"          =>!empty($userLevelRes->score) ? $userLevelRes->score : 0,
			"level"       =>!empty($userLevelRes->fk_level) ? $userLevelRes->fk_level : '1',
			"levelName"   =>!empty($userLevelRes->title) ? $userLevelRes->title : '1',
			"status"      =>$isSign,
			"grow"        =>($days<5) ? 2 : 7,
			"continueDay" => 5-$days,
			"extraEx"     => 5,
			"nextEx"      => $nextEx,
			"levelColor"  => interface_user_api::bigLevel($userLevelRes->fk_level),
			"nextLevelName"=> $nextLevelRes->title
		];
		return $this->setData($data);
	}
	/*
		删除评价
		$uid 		用户id
		$courseId 	课程id
		$planId 	排课id
	*/
	public function pageDelComment(){
		$planId = !empty($this->paramsInfo['params']['planId']) ? (int)($this->paramsInfo['params']['planId']) :0;
		$uid = !empty($this->paramsInfo['params']['uid']) ? (int)($this->paramsInfo['params']['uid']) :0;
		$courseId = !empty($this->paramsInfo['params']['courseId']) ? (int)($this->paramsInfo['params']['courseId']) :0;
		if(empty($planId) || empty($uid) || empty($courseId)) return $this->setMsg(1001);
		$params = [
				'userId' => $uid,
				'planId' => $planId,
				'courseId' => $courseId,
				'teacherId' => 0,
		];
		$seek_params = [
			'q' => ['plan_id'=>$planId],
			'f' => ['course_id','teacher_id','plan_id','owner_id']
		];
		$seekPlan = seek_api::seekPlan($seek_params);
		if (empty($seekPlan->data)) return $this->setMsg(1001);
		$planRes = $seekPlan->data[0];
		$params ['teacherId'] =$planRes->teacher_id;
		$res = utility_services::call('/comment/course/DeleteComment', $params);
		if (!empty($res->code)) return $this->setMsg(1001);
		return $this->setMsg(0);
	}

	public function pageCheckOrder(){
		$uniqueOrderId = !empty($this->paramsInfo['params']['uniqueOrderId']) ? $this->paramsInfo['params']['uniqueOrderId'] : '';
		if(empty($uniqueOrderId)) return $this->setMsg(1000);

		$orderRes = order_api::getOrder(array('uniqueOrderId'=>$uniqueOrderId));
		if(empty($orderRes->items)) return $this->setMsg(3002);

		$orderInfo = $orderRes->items[0];

        //检查课程是否有效
        if($orderInfo->object_type == 1){
            $courseReg = course_api::getcourseone($orderInfo->object_id);
            if(empty($courseReg)) return $this->setMsg(1056);
        }

		$timeNow = strtotime('now');
		$timeExp = strtotime($orderInfo->expiration_time);

		if($orderInfo->status == 'expried' || $orderInfo->status == 'cancel' || ($timeNow > $timeExp)){
			return $this->setMsg(2024);
		}

		return $this->setMsg(0);
	}

	//计费点接口
	public function pagegetunipayCode(){
		$scode = !empty($this->paramsInfo['params']['scode']) ? $this->paramsInfo['params']['scode'] : '15';
		$type = !empty($this->paramsInfo['params']['type']) ? $this->paramsInfo['params']['type'] : '2';
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/unipayCode.conf");
		$codeConf = utility_tool::objToArray($domain_conf);
		/*$conf = array(
						"course"=>array(
							1=>array("scode"=>'903865228220160918152203332100001',"num"=>160919556566),
							5=>array("scode"=>'903865228220160918152203332100002',"num"=>160919556567),
							6=>array("scode"=>'903865228220160918152203332100003',"num"=>160919556568),
							8=>array("scode"=>'903865228220160918152203332100004',"num"=>160919556569),
							9=>array("scode"=>'903865228220160918152203332100005',"num"=>160919556570),
							10=>array("scode"=>'903865228220160918152203332100006',"num"=>160919556571),
							15=>array("scode"=>'903865228220160918152203332100007',"num"=>160919556572),
							19=>array("scode"=>'903865228220160918152203332100008',"num"=>160919556573),
							20=>array("scode"=>'903865228220160918152203332100009',"num"=>160919556574),
							25=>array("scode"=>'903865228220160918152203332100010',"num"=>160919556575),
							29=>array("scode"=>'903865228220160918152203332100011',"num"=>160919556576),
							30=>array("scode"=>'903865228220160918152203332100012',"num"=>160919556577),
						),
						"member"=>array(
							10=>array("scode"=>'903865228220160918152203332100013',"num"=>160919556578),
							20=>array("scode"=>'903865228220160918152203332100014',"num"=>160919556579),
							30=>array("scode"=>'903865228220160918152203332100015',"num"=>160919556580),
						)
					);
		*/
		$data = array();
		if(!isset($codeConf['course'][$scode])){
				return $this->setMsg(3002);
		}else{
				$data 		= $codeConf['course'][$scode] ;
		}
		if($type=="1"){
			$uncode 		= !empty(explode("#",$codeConf['course'][$scode]["scode"])[1]) ? explode("#",$codeConf['course'][$scode]["scode"])[1] : '';
			$data['scode']  = $uncode;
		}elseif($type=="2"){
			$uncode = !empty(explode("#",$codeConf['member'][$scode]["scode"])[1]) ? explode("#",$codeConf['member'][$scode]["scode"])[1] : '';
			$data 			= $codeConf['member'][$scode] ;
			$data['scode']  = $uncode;
		}
		if(!empty($data)){
			return $this->setData($data);
		}else{
			return $this->setMsg(3002);
		}
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
			if($org_id != $code_id ) return $this->Msg(20018,'不存在对应的课程');
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

					if(empty($resCourse->data)) return $this->Msg(20016,'不在优惠范围内');
				}
			}elseif($ret->object_type == 1){
				if($ret->object_id !='all'){
					$courseIds = explode(',',$ret->object_id);
					$courseIds = array_map(array($this,'stringToInt'),$courseIds);
					if(!in_array($ret->courseId,$courseIds))  return $this->Msg(20017,'不在优惠范围内');
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
				if (!empty($discountCodeInfo) && ($discountCodeInfo->total_num > 0 && $discountCodeInfo->total_num < $discountCodeInfo->used_num)) {
					$st->num = -1;
					order_api::updateUsedNumForDiscountCodeById($st);
					return $this->Msg(20012, '优惠已经使用完了');
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
					'disPirce' => $disPrice/100,
					'price'    => $price/100,
					'disCodeId' => $discountCodeInfo->discount_code_id,
					'type'     =>  $discountRules->discount_type,//1 manjian 2 dazhe
					'discount_value' => (int)$discountRules->discount_value/100,
					'min_fee'   => (int)$discountRules->min_fee/100,
			];

			return interface_func::setData($data);
		}
	}


	public function Msg($code,$message){
		$st = new stdClass();
		$st->code = $code;
		$st->errMsg = $message;
		$st->msg = $message;
		return json_encode($st);
	}
}
