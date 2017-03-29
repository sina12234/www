<?php

class interface_dict
{
    static $courseType = array(
        '0' => '未设置',
        '1' => '直播课',
        '2' => '点播课'
    );

    static $publicType = array(
        '0' => '公开',
        '1' => '注册用户',
        '2' => '验证用户'
    );

    static $feeType = array(
        '0' => '免费',
        '1' => '按课程收费'
    );

    static $status = array(
        '1' => '未开始',
        '2' => '直播中',
        '3' => '已完结'
    );

    static $orderStatus = [
        'initial' => '未支付',
        'paying' => '正在支付',
        'success' => '已支付',
        'deleted' => '已删除',
        'expired' => '超时失效',
        'fail' => '订单失败',
        'cancel' => '已取消'
    ];

    static $gender = [
        '0' => '未设置',
        '1' => '男',
        '2' => '女'
    ];
}
