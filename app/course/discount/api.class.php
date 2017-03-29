<?php

class course_discount_api
{
    const COURSE_DISCOUNT_CHECK_DISCOUNT_CODE_VALID_V2 = '/course/discount/CheckDiscountCodeValidV2/';

    const COURSE_DISCOUNT_USE_DISCOUNT_CODE_V2 = '/course/discount/UseDiscountCodeV2/';

    const COURSE_DISCOUNT_GET_CODE_INFO = '/course/discount/GetCodeInfo/';

    /**
     * @desc check discount code valid (version v2)
     *
     * @param $userId
     * @param $orderId
     * @param $discount
     * @return array
     */
    public static function checkDiscountCodeValidV2($userId, $orderId, $discount)
    {
        $url = self::COURSE_DISCOUNT_CHECK_DISCOUNT_CODE_VALID_V2;
        $params = [
            'userId'       => (int)($userId),
            'orderId'      => $orderId,
            'discountCode' => $discount
        ];

        $res =  interface_func::requestApi($url, $params);
        if (!empty($res['code'])) {
            return interface_func::error($res['code'], $res['msg']);
        }

        return [
            'couponCode' =>!empty($res['result']['discountCodeInfo']['discount_code']) ? $res['result']['discountCodeInfo']['discount_code'] : '',
            'useLimit' => !empty($res['result']['discountRules']['name']) ? $res['result']['discountRules']['name'] : '',
            'favourablePrice' => !empty($res['result']['discountRules']['discount_value']) ? $res['result']['discountRules']['discount_value'] / 100 : 0
        ];
    }

    /**
     * @desc use discount
     *
     * @param $userId
     * @param $orderId
     * @param $discountCode
     * @return array
     */
    public static function useDiscountCode($userId, $orderId, $discountCode)
    {
        $url    = self::COURSE_DISCOUNT_USE_DISCOUNT_CODE_V2;
        $params = [
            'userId'   => (int)($userId),
            'orderId'  => $orderId,
            'discountCode' => $discountCode
        ];

        $res = interface_func::requestApi($url, $params);
        if (!empty($res['code'])) {
            return interface_func::error($res['code'], $res['msg']);
        }

        $orderInfo = interface_user_api::getOrderInfo($orderId);

        // 报名
        $reg['uid']        = $orderInfo['userId'];
        $reg['course_id']  = $orderInfo['courseId'];
        $reg['user_owner'] = $orderInfo['userOwner'];
        $reg['class_id']   = $orderInfo['classId'];
        $reg['status']     = 1;
        if (course_api::addregistration($reg) === false) {
            SLog::fatal('android pay add reg failed,params[%s]', var_export($reg, 1));
        }

        // 更新订单状态
        $orderUpdateData = [
            'status'   => 'success',
        ];
		$updateRes = order_api::updateOrder($orderInfo['orderId'], $orderUpdateData);
		if($updateRes->code == -1){
			SLog::fatal('android pay update fee order failed,params[%s]', var_export($orderUpdateData, 1));
		}

        $orderInfo['couponCode']      = !empty($res['result']['discountCodeInfo']['discount_code']) ? $res['result']['discountCodeInfo']['discount_code'] : '';
        $orderInfo['useLimit']        = !empty($res['result']['discountRules']['name']) ? $res['result']['discountRules']['name'] : '';
        $orderInfo['favourablePrice'] = !empty($res['result']['discountRules']['discount_value']) ? $res['result']['discountRules']['discount_value'] / 100 : 0;

        return $orderInfo;
    }

    /**
     * @desc get order detail (version v2)
     *
     * @param $orderId
     * @return array|bool
     */
    public static function orderDetailV2($orderId)
    {
        $orderInfo = interface_user_api::getOrderInfo($orderId);
        if (empty($orderInfo)) return [];
        $codeInfo = self::getCodeInfo($orderId);

		$disPrice = 0;
        if(!empty($codeInfo['discountInfo'])){
		    if(1 == $codeInfo['discountInfo']['discount_type']){
                $disPrice = $codeInfo['discountInfo']['discount_value']/100;
		    }else{
			    //四舍五入
			    $price = ((int)($orderInfo['priceOld'] * $codeInfo['discountInfo']['discount_value'] /100 + 0.5));
                if($price <= 0){
                    $disPrice = 0;
                }else{
                    $disPrice = $orderInfo['priceOld'] - $price;
                }
		    }
        }
        $orderInfo['couponCode']      = !empty($codeInfo['couponCode']) ? $codeInfo['couponCode'] : '';
        $orderInfo['useLimit']        = !empty($codeInfo['useLimit']) ? $codeInfo['useLimit'] : '';
        $orderInfo['favourablePrice'] = $disPrice;

        return $orderInfo;
    }

    public static function getCodeInfo($orderId)
    {
        $url = self::COURSE_DISCOUNT_GET_CODE_INFO.$orderId;

        // discountCodeUsedInfo table,discountCodeInfo table, discountInfo table
        $res = interface_func::requestApi($url);
        if (!empty($res['code'])) {
            return interface_func::error($res['code'], $res['msg']);
        }

        $data = [
            'couponCode'      => $res['result']['discountCodeInfo']['discount_code'],
            'useLimit'        => $res['result']['discountInfo']['name'],
            'favourablePrice' => $res['result']['discountInfo']['discount_value'] / 100,
			'discountInfo'    => $res['result']['discountInfo']
        ];

        return $data;
    }

    /**
     * @param $data
     * @param $uid
     * @ 创建优惠码
     */
    public static function createDiscountV2New($data, $uid)
    {
        $params                 = new stdclass;
        $params->user_id        = $uid;
        $params->org_id         =$data['org_id'];
        $params->name           = $data["discount_name"];
        $params->course_id      = $data["course_id"];
        $params->introduction   = $data['introduction'];
        $params->discount_type  = (int)($data["discount_type"]);
        $params->discount_value = floatval($data["discount_value"]);
        $params->min_fee        = floatval($data["min_fee"]);
        $params->total_num      = (int)($data["total_num"]);
        $params->user_limit     = (int)($data["user_limit"]);
        $params->duration       = (int)($data["duration"]);
        $params->object_type       = (int)($data["object_type"]);
        $params->object_id       = trim($data["object_id"]);
        $params->start_time      =$data['start_time'] .' 00:00:00';
        if (!empty($data["create_code"])) {
            $params->create_code =$data["create_code"];
        }
        $ret = utility_services::call("/course/discount/createv2New", $params);

        return $ret;
    }

    public static function createDiscountCodeV2New($num, $uid, $discount_id)
    {
        $params              = new stdclass;
        $params->user_id     = $uid;
        $params->discount_id = $discount_id;
        $params->num         = (int)($num);
        $ret                 = utility_services::call("/course/discount/createcodev2New", $params);

        return $ret;
    }
    /**
     * 获取优惠详情 列表
     */
    public static function getDiscountInfoList($fk_org,$page,$status,$length,$search,$time_over){
        if(empty($fk_org)) return interface_func::setMsg(1000);
        $page = isset($page)&& !empty($page) ? (int)$page : 1;
        $status = isset($status)&& !empty($status) ? (int)$status : '';
        $length  = isset($length)&& !empty($length) ? (int)$length : 20;
        $search = isset($search)&& !empty(htmlspecialchars(trim($search))) ? htmlspecialchars(trim($search)) : '';
        $time_over = isset($time_over)&& !empty(htmlspecialchars(trim($time_over))) ? htmlspecialchars(trim($time_over)) : '';
        $params = new stdClass();
        $params -> page = $page;
        $params -> status = $status;
        $params -> length = $length;
        $params -> search = $search;
        $params->fk_org = $fk_org;
        $params->time_over = $time_over;
        $res = utility_services::call('/course/discount/getDiscountInfoList', $params);
        return $res;
    }

    /**
     * 优惠码编辑
     */
    public static function getDiscountInfo($pk_discount,$org_id){
        if(empty($pk_discount) || empty($org_id)){
            return interface_func::setMsg(1000);
        }
        $params = new stdClass();
        $params -> pk_discount = $pk_discount;
        $params -> org_id = $org_id;
        $res = utility_services::call('/course/discountcode/getDiscountInfo', $params);
        if($res->code != 0) return $res;
        $res->data[0]->duration = $res->data[0]->duration/86400;
        $arr[] = $res->data[0]->fk_org;
        $arr[] = $res->data[0]->fk_org;
        if($res->code ==0){
            $re = $res->data;
            $fk_org = $re[0]->fk_org;
            $fk_discount = $re[0]->pk_discount;
            $fk = new stdClass();
            $fk->fk_org = $fk_org;
            $fk->fk_discount = $fk_discount;
            $ret = utility_services::call('/course/discountcode/getDiscountRangeInfo', $fk);
            if($ret->code==0){
                $rr = $ret->data;
                $re[0]->object_type = $rr[0]->object_type;
                $re[0]->object_id = $rr[0]->object_id;
                if($res->data[0]->duration==0){
                    $re[0]->range_time = date('Y.m.d',strtotime($res->data[0]->starttime)).'-'.'2035.12.1';
                }else{
                    $re[0]->range_time =date('Y.m.d',strtotime($res->data[0]->starttime)).'-'. date('Y.m.d',strtotime($res->data[0]->starttime)+$res->data[0]->duration*86400);
                }
                if($rr[0]->object_type==3){//分类
                    ///course.list?fc=1&sc=7&tc=31&vid=5|46&course_type=1
                    $re[0]->range_name = '查看优惠课程';
                    if($rr[0]->object_id == 'all'){
                        $re[0]->url = "/course.list";
                    }else {
                        $object_id = explode(',', $rr[0]->object_id);
                        $re[0]->url = "/course.list?fc=";
                        if (count($object_id) == 1) {
                            $re[0]->url = $re[0]->url . $object_id[0];
                        } elseif (count($object_id) == 2) {
                            $re[0]->url = $re[0]->url . $object_id[0] . "&sc=" . $object_id[1];
                        } elseif (count($object_id) == 3) {
                            $re[0]->url = $re[0]->url . $object_id[0] . "&sc=" . $object_id[1] . "&tc=" . $object_id[2];
                        } elseif (count($object_id) == 4) {
                            $re[0]->url = $re[0]->url . $object_id[0] . "&sc=" . $object_id[1] . "&tc=" . $object_id[2] . "&vid=1|" . $object_id[3];
                        } elseif (count($object_id) == 5) {
                            $re[0]->url = $re[0]->url . $object_id[0] . "&sc=" . $object_id[1] . "&tc=" . $object_id[2] . "&vid=1|" . $object_id[3] . "&course_type=" . $object_id[4];
                        }
                    }
                }elseif($rr[0]->object_type==1 && $rr[0]->object_id=='all'){//全部课程
                    $re[0]->range_name = '全部课程可用';
                    $re[0]->url = "/course.list";
                }elseif($rr[0]->object_type==1 && $rr[0]->object_id !='all'){//多课
                    $re[0]->range_name = '查看优惠课程';
                    $re[0]->url = "/course.list?cid=".$rr[0]->object_id;//1,2,3
                }
                $rt = utility_services::call('/user/organization/getOrgInfo',$arr);//获取机构名称 t_org
                $re[0]->org_name = $rt->data[0]->subname;
            }
        }
        return $res;
    }

    /**
     * 更新优惠  及 用户绑定的优惠码状态
     */
    public static function UpdateDiscountStatus($pk_discount,$org_id,$status){
        if(empty($pk_discount) || empty($org_id)) return interface_func::setMsg(1000);
        $pk_discount = (int)$pk_discount;
        $org_id = (int)$org_id;
        $status = (int)$status;
        if(in_array($status,array(0,-1,-2))){
            $params = new stdClass();
            $params -> status = $status;
            $params ->pk_discount = $pk_discount;
            $params ->org_id = $org_id;
            $res = utility_services::call('/course/discount/UpdateDiscountStatus', $params);
            if($res->code ==0){
                utility_services::call('/course/discount/UpdateDiscountUserStatus',$params);
            }
            return $res;
        }else{
            return interface_func::setMsg(1000);
        }
    }

    /**
     * 获取用户绑定的优惠码
     */
    public static function getUserDiscoutCode($uid,$page,$status){
        if(empty($uid)) return interface_func::setMsg(1000);
        if(empty($page)) return interface_func::setMsg(1000);
        if(empty($status)) $status = 0;
        if(!in_array($status,[0,-1,1])) $status = 0;
        $params = new stdCLass;
        $params->uid = $uid;
        $params->page = $page;
        $params->status = $status;
        $result = utility_services::call('/course/discountcode/getUserbindingCode', $params); //t_discount_code_user 获取用户绑定的优惠码
        //组装优惠码的信息
        if(!empty($result->data->items)){
            foreach($result->data->items  as $k=>&$item){
                if($status==1){
                    $total_num =$item->total_num;//总数
                    $used_num = $item->used_num;//使用数量
                    if($total_num==0 ){
                        $item ->left_num = '无限';
                    }else{
                        if($total_num >0  && $used_num ==$total_num){
                            $item->left_num = 0;
                        }else{
                            $item->left_num = $total_num-$used_num;
                        }
                    }
                }else{
                    $used_num = $item->used_num;
                    $left_num = $item->total_num - $used_num;//剩余次数
                    $total_num = $item->total_num ;//一共的次数
                    if( $left_num > 0){
                        $item ->left_num = $left_num;
                    }else{
                        $item->left_num = 0;
                    }
                    if($total_num==0){
                        $item->left_num ='无限';
                    }
                }
                if($item->duration==0){
                    $item->time_limit = date('Y.m.d',strtotime($item->starttime)).'-'.'2035.12.1';
                }elseif($item->duration){
                    $item->time_limit = date('Y.m.d',strtotime($item->starttime)).'-'.date('Y.m.d',strtotime($item->starttime)+$item->duration);
                }
                if($item->object_type==3){//分类
                    ///course.list?fc=1&sc=7&tc=31&vid=5|46&course_type=1
                    $item->range_name = '查看优惠课程';
                    if($item->object_id == 'all'){
                        $item->url = "/course.list";
                    }else {
                        $object_id = explode(',', $item->object_id);
                        $item->url = "/course.list?fc=";
                        if (count($object_id) == 1) {
                            $item->url = $item->url . $object_id[0];
                        } elseif (count($object_id) == 2) {
                            $item->url = $item->url . $object_id[0] . "&sc=" . $object_id[1];
                        } elseif (count($object_id) == 3) {
                            $item->url = $item->url . $object_id[0] . "&sc=" . $object_id[1] . "&tc=" . $object_id[2];
                        } elseif (count($object_id) == 4) {
                            $item->url = $item->url . $object_id[0] . "&sc=" . $object_id[1] . "&tc=" . $object_id[2] . "&vid=1|" . $object_id[3];
                        } elseif (count($object_id) == 5) {
                            $item->url = $item->url . $object_id[0] . "&sc=" . $object_id[1] . "&tc=" . $object_id[2] . "&vid=1|" . $object_id[3] . "&course_type=" . $object_id[4];
                        }
                    }
                }elseif($item->object_type==1 && $item->object_id=='all'){//全部课程
                    $item->range_name = '全部课程可用';
                    $item->url = "/course.list";
                }elseif($item->object_type==1 && $item->object_id !='all'){//多课
                    $item->range_name = '查看优惠课程';
                    $item->url = "/course.list?cid=".$item->object_id;//1,2,3
                }
            }
            $tmp_arr = array();
            foreach($result->data->items as $kk=>&$value){
                $tmp_arr['fk_discount_code'][$value->pk_code_user] = $value->fk_discount_code;
                $tmp_arr['fk_org'][$value->pk_code_user] = $value->fk_org;
                $tmp_arr['owner'][$value->pk_code_user] = $value->owner;
            }
            // 机构名称 //获取机构域名
            $rt = utility_services::call('/user/organization/getOrgNameByOrgArr',$tmp_arr);
            $http = (utility_net::isHTTPS() === true) ? "https://" : "http://";
            foreach($rt->items as $val){
                $urlArr[$val->fk_user_owner]['domain'] = $http.$val->subdomain;
                $urlArr[$val->fk_user_owner]['org_name'] = $val->subname;
            }

            foreach($result->data->items as &$v){
                $v->url = !empty($urlArr[$v->owner]) ? $urlArr[$v->owner]['domain'].$v->url : '';
                $v->org_name = !empty($urlArr[$v->owner]) ? $urlArr[$v->owner]['org_name'] : '';
            }

        }
        return $result;
    }

    /**
     *兑换优惠码
     */

    public static function ExchangeCode($code,$uid){
        if(empty($code) || empty($uid)) return interface_func::setMsg(1000);
        $code = htmlspecialchars(trim($code));
        $uid = (int)$uid;
        if($code && $uid){
            $params = new stdClass();
            $params -> discount_code = $code;
            $params ->uid = $uid;
            $res = utility_services::call('/course/discount/ExchangeCode', $params);
            return $res;
        }

    }

    /**
     * 领取优惠码 检测码 是否过期
     */
    public static function getCodeStatus($pk_discount,$uid){
        if(empty($pk_discount) || empty($uid)) return interface_func::setMsg(1000);
        $pk_discount = (int)$pk_discount;
        $uid = (int)$uid;
        $params = new stdClass();
        $params -> pk_discount = $pk_discount;
        $params ->uid = $uid;
        $res = utility_services::call('/course/discount/getCodeStatus',$params);
        return $res;
    }

    /**
     * 领取展现页面
     */
    public static function getPkDiscountInfo($pk_discount){
        if(empty($pk_discount)) return interface_func::setMsg(1000);
        $pk_discount = (int)$pk_discount;
        $params = new stdClass();
        $params -> pk_discount = $pk_discount;
        $res = utility_services::call('/course/discount/getPkDiscountInfo',$params);
        return $res;
    }

    /**
     * 优惠吗列表
     */
    public static function getDiscountCodeListBydiscount($status,$fk_discount,$orgOwner,$page,$used_num='',$length=''){
        if(empty($fk_discount || $orgOwner || $page)) return interface_func::setMsg(1000);
        $fk_discount = (int)$fk_discount;
        $orgOwner   = (int)$orgOwner;
        $params = new stdClass();
        $params -> fk_discount = $fk_discount;
        $params  -> orgOwner = $orgOwner;
        $params ->page = $page;
        $params->status = $status;
        $params ->used_num = $used_num;
        $length = $length > 0 ? $length : 20;
        $params ->length = $length;
        if(!empty($used_num)&& ($used_num=='on' || $used_num=='off' || $used_num=='-1' ) ) $params->used_num = $used_num;
        $res = utility_services::call('/course/discountcode/getDiscountCodeListBydiscount',$params);
        return $res;
    }

    /**
     * 优惠码使用详情
     */
    public static function getcodeUsed($orgOwner,$code,$page=''){
        if(empty($orgOwner || $code)) return interface_func::setMsg(1000);
        $params = new stdClass();
        $params -> orgOwner = $orgOwner;
        $params  -> code = $code;
        $page = isset($page) && !empty($page) ? (int)$page : '';
        if(isset($page) && !empty($page))  $params  -> page = $page;
        $res = utility_services::call('/course/discountcode/getcodeUsed',$params);
        return $res;

    }

    /**
     * 优惠码的禁用 启用
     */
    public static function CodeDisableAndEnable($orgOwner,$code,$status){
        if(empty($orgOwner || $code)) return interface_func::setMsg(1000);
        if(!isset($status)) return interface_func::setMsg(1000);
        if(!in_array($status,[0,-1])) return interface_func::setMsg(1000);
        $orgOwner = (int)$orgOwner;
        $code = htmlspecialchars(trim($code));
        $params = new stdClass();
        $params-> orgOwner = $orgOwner;
        $params ->code = $code;
        $params ->status = $status;
        $res = utility_services::call('/course/discountcode/CodeDisableAndEnable',$params);
        return $res;
    }

    //you hui ma bian ji
    public static function UpdateEditDiscountCode($data){
        $params = new stdClass();
        $params-> pk_discount = $data['pk_discount'];
        $params ->start_time = $data['start_time'];
        $params ->duration = $data['duration'];
        $params ->object_id = $data['object_id'];
        $params ->object_type = $data['object_type'];
        $params ->introduction = $data['introduction'];
        $res = utility_services::call('/course/discountcode/UpdateEditDiscountCode',$params);
        return $res;
    }

    public static function codeIsReceive($fk_discount,$uid){
        if(empty($fk_discount) || empty($uid) || !(int)$fk_discount || !(int)$uid ) return interface_func::error(10000,'参数错误');
        $params = new stdClass();
        $params ->fk_discount = $fk_discount;
        $params ->fk_user = $uid;
        $res = utility_services::call('/course/discountcode/codeIsReceive',$params);
        return $res;
    }

    //优惠码被绑定不能使用
    public static function getCodeBander($code){
        if(empty($code)) return interface_func::error(100001,'参数错误');
        $params  = new stdClass();
        $params->code =$code;
        $res = utility_services::call('/course/discountcode/getCodeBander',$params);
        return $res;
    }

    //用户删除优惠码
    public static function DeleteCode($code,$uid){
        if(empty($code)) return interface_func::error(100001,'参数错误');
        $params  = new stdClass();
        $params->code =$code;
        $params->uid =$uid;
        $res = utility_services::call('/course/discountcode/DeleteCode',$params);
        return $res;
    }

    //更新用户优惠码
    public static function updateCodeStatusByCode($code,$status){
        if(empty($code)) return interface_func::setMsg(100001,'参数错误');
        $params  = new stdClass();
        $params->code =$code;
        $params->status = $status;
        $res = utility_services::call('course/discountcode/updateCodeStatusByCode',$params);
        return $res;
    }
}
