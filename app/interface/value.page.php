<?php

class interface_value extends interface_base
{
    public function pageGetUserBalance()
    {
        $this->v(
            [
                'userId' => 1000,
            ]
        );
		$orgId = !empty($this->paramsInfo['oid']) ? (int)$this->paramsInfo['oid'] : 0;
		if(!empty($orgId)){
			$config = SConfig::getConfig(ROOT_CONFIG."/yunDianPrice.conf",'xiaoVoPriceSetting');
		}else{
			$config = SConfig::getConfig(ROOT_CONFIG."/yunDianPrice.conf",'priceSetting');
		}
        
        $balance = interface_user_api::getUserBalance($this->paramsInfo['params']['userId']);

        return interface_func::setData(
            [
                'balance'      => $balance,
                'priceSetting' => $config->value
            ]
        );
    }

    public function pageValid()
    {
        $this->v(
            [
                'userId'  => 1000,
                'payCert' => 1000,
                'transactionId' => 1000
            ]
        );

		$orgId = !empty($this->paramsInfo['oid']) ? (int)$this->paramsInfo['oid'] : 0;
        $transactionId = $this->paramsInfo['params']['transactionId'];
        $userId = $this->paramsInfo['params']['userId'];

        // check transactionId exists?
        $existsTransactionId = interface_user_api::exists($transactionId);
        if ($existsTransactionId) {
            // get balance
            $balance = interface_user_api::getUserBalance($userId);
            return interface_func::setData(['balance' => $balance]);
        }

        try {
            $info = utility_iap::getReceiptData($this->paramsInfo['params']['payCert']);
            $res = interface_user_api::valid($userId, $transactionId, $info, $orgId);
            if (!empty($res['code'])) {
                return interface_func::error($res['code'], $res['msg']);
            }

            return interface_func::setData(['balance' => $res]);

        } catch (Exception $e) {
            if ($e->getCode() == 21007) {
                try {
                    $info = utility_iap::getReceiptData($this->paramsInfo['params']['payCert'], 1); // sandbox valid
                    $res = interface_user_api::valid($userId, $transactionId, $info, $orgId);
                    if (!empty($res['code'])) {
                        return interface_func::error($res['code'], $res['msg']);
                    }

                    return interface_func::setData(['balance' => $res]);
                } catch (Exception $e) {
                    exit(json_encode(['code' => $e->getCode(), 'msg'  => $e->getMessage()]));
                }
            }

            exit(json_encode(['code' => $e->getCode(), 'msg'  => $e->getMessage()]));
        }
    }

    public function pageYunPay()
    {
        $this->v(
            [
                'userId'  => 1000,
                'orderId' => 1000
            ]
        );
        $userId  = $this->paramsInfo['params']['userId'];
        $orderId = $this->paramsInfo['params']['orderId'];
		$objectType = !empty($this->paramsInfo['params']['objectType']) ? (int)$this->paramsInfo['params']['objectType'] : 1;

        $orderInfo = interface_user_api::getOrderInfo($orderId, $objectType);
        if (empty($orderInfo)) return interface_func::setMsg(2025); // 订单不存在
		if($orderInfo['userId']!=$userId) return interface_func::setMsg(1000); // 参数错误
        if ($orderInfo['orderStatus'] == 'expired') return interface_func::setMsg(2024); // 订单过期
		$balance = interface_user_api::getUserBalance($userId);
		if ($balance * 100 < $orderInfo['price'] * 100) {
			return interface_func::setMsg(2045); // 用户余额不足
		}
		$up = interface_user_api::updateBalance($userId, -$orderInfo['price'] * 100);
		if($up){
			// 记录消费记录
			$logData = [
				'userId'  => $userId,
				'iosCoin' => $orderInfo['price'],
				'orderId' => $orderInfo['orderId'],
				'source'  => 1,
				'type'    => 2
			];
			interface_user_api::addLog($logData);
		}
		if($objectType==1){
			// 购买课程 
			if ($up) {
				// 报名
				$reg['uid']        = $orderInfo['userId'];
				$reg['course_id']  = $orderInfo['courseId'];
				$reg['user_owner'] = $orderInfo['userOwner'];
				$reg['class_id']   = $orderInfo['classId'];
				$reg['status']     = 1;
				if (course_api::addregistration($reg) === false) {
					SLog::fatal('yun pay add reg failed,params[%s]', var_export($reg, 1));
				}

				// 更新订单状态
				$orderUpdateData = [
					'status'   => 'success',
					'pay_type' => 'inapp'
				];
				
				$res = order_api::updateOrder($orderInfo['orderId'], $orderUpdateData);
				if($res->code == -1){
					SLog::fatal('yun pay update fee order failed,params[%s]', var_export($orderUpdateData, 1));
				}
				$orderInfo['payType'] = 4;
				$orderInfo['balance'] = interface_user_api::getUserBalance($userId);
				return $this->setData($orderInfo);
			}
		}elseif($objectType==11){
			$memberInfo = org_member_api::getMemberSetInfo($orderInfo['memberId']);
			if(empty($memberInfo) || $memberInfo['status'] != 1) return interface_func::error(1000, "该会员已经被机构停用");
			//购买会员成功
			$price_type = array(
				1 => 30,
				2 => 90,
				3 => 180,
				4 => 360,
			);
			$data['fk_user']    	= $userId;
			$data['setId']         = $orderInfo['memberId'];
			$data['price_type']    = $price_type[$orderInfo['ext']];
			$data['last_type']     = 1;//付费购买
			$data['type']          = 4;
			$data['status']        = $orderInfo['memberStatus'];//启用
			$data['member_status'] = 1;//正常
			$data['org_id']        = $orderInfo['orgId'];
			$res = org_member_api::addMember($data);
			if($res){
				// 更新订单状态
				$orderUpdateData = [
					'status'   => 'success',
					'pay_type' => 'inapp'
				];
				$res = order_api::updateOrder($orderInfo['orderId'], $orderUpdateData);
				if($res->code == -1){
					SLog::fatal('yun pay update fee order failed,params[%s]', var_export($orderUpdateData, 1));
				}
				$orderInfo['payType'] = 4;
				$orderInfo['balance'] = interface_user_api::getUserBalance($userId);
				return $this->setData($orderInfo);
			}
		}
        return $this->setMsg(2048);
    }
}
