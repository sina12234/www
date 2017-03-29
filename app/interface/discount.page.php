<?php

class interface_discount extends interface_base
{
    public function pageValid()
    {
        $this->v(
            [
                'couponCode' => 1000,
                'orderId'    => 1000,
                'userId'     => 1000
            ]
        );

        $userId = $this->paramsInfo['params']['userId'];
        $orderId = $this->paramsInfo['params']['orderId'];
        $discountCode = $this->paramsInfo['params']['couponCode'];
        $res = course_discount_api::checkDiscountCodeValidV2($userId, $orderId, $discountCode);

        if (!empty($res['code'])) {
            return interface_func::error($res['code'], $res['msg']);
        }

        return interface_func::setData($res);
    }

    public function pagePay()
    {
        $this->v(
            [
                'couponCode' => 1000,
                'orderId'    => 1000,
                'userId'     => 1000
            ]
        );

        $userId       = $this->paramsInfo['params']['userId'];
        $orderId      = $this->paramsInfo['params']['orderId'];
        $discountCode = $this->paramsInfo['params']['couponCode'];
        $res = course_discount_api::useDiscountCode($userId, $orderId, $discountCode);

        if (!empty($res['code'])) {
            return interface_func::error($res['code'], $res['msg']);
        }

        return interface_func::setData($res);
    }

    public function pageOrderDetailV2()
    {
        $this->v(
            [
                'orderId'    => 1000,
                'userId'     => 1000
            ]
        );
        $orderId      = $this->paramsInfo['params']['orderId'];

        $res = course_discount_api::orderDetailV2($orderId);
        //兼容订单价格
        $res['price'] = $res['priceOld'] /100;
        if (empty($res)) return interface_func::setMsg(3002);
        //兼容订单价格
        $res['price'] = $res['priceOld'];

        return interface_func::setData($res);
    }
}
