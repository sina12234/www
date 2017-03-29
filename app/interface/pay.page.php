<?php

class interface_pay extends interface_base
{
    public function pageGetPrepayId()
    {
        $this->v(
            [
                'title'       => 1000,
                'out_trade_id'   => 1000,
                'price'     => 1000
            ]
        );

        $data     = [
            "body"         => $this->paramsInfo['params']['title'],
            "out_trade_no" => $this->paramsInfo['params']['out_trade_id'],
            "total_fee"    => $this->paramsInfo['params']['price'] * 100,//变成分
            "notify_url"   => "http://".$_SERVER['HTTP_HOST']."/course.buy.weixinReturn",
            "trade_type"   => "NATIVE",
            "product_id"   => $this->paramsInfo['params']['out_trade_id'],
        ];
        $prepayId = weixin_api::getPrepayId($data);

        if ($prepayId) return $this->setData($prepayId);

        return $this->setMsg(1);
    }
}
