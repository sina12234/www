<?php
class student_discount extends STpl
{
	private $user,$domain,$orgOwner;
	function __construct()
	{
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
		$this->assign('domain', $this->domain);

		$this->user = user_api::loginedUser();
		if(empty($this->user))
		{
			if(!empty($_SERVER['REQUEST_URI']))
			{
				$this->redirect("/site.main.login?url=".$_SERVER['REQUEST_URI']);
			}
			else
			{
				$this->redirect("/site.main.login");
			}
		}
        $org=user_organization::subdomain();
        if(!empty($org))
		{
            $this->orgOwner=$org->userId;
        }
		else
		{
            header('Location: https://www.'.$this->domain);
        }
	}

	// public function pageMyDiscount()
	// {
	// 	$owner_id = $this->orgOwner; //= 159;
	// 	$uid = $this->user['uid']; //= 249;
	// 	$path = '/student.discount.mydiscount';
	// 	$page = isset($_GET['page'])?$_GET['page']:1;
	// 	$code = !empty($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : '';
	// 	$num  = 15;
	//
	// 	//t_discount_code_used
	// 	$userCodeParams = array('user_id'=>$uid,'limit'=> -1,'page'=>1);
	// 	$res_discounts = course_api::listUserByCode($userCodeParams);
	// 	$disCodeIdStr = '';
	// 	$disCodeIdArr = array();
	// 	if(!empty($res_discounts->data))
	// 	{
	// 	    $disCodeIdArr = array_reduce($res_discounts->data, create_function('$v,$w', '$v[$w->discount_code]=$w->discount_code;return $v;'));
	// 		$disCodeIdStr = implode(',',$disCodeIdArr);
	// 	}
	//
	// 	 //t_discount_code
	// 	 $disCodeParams = array('dis_ids'=>$disCodeIdStr,'owner'=>$owner_id,'code'=>$code,'limit'=>$num,'page'=>$page);
	// 	 $res_discount_code = course_api::ListDiscountByIds($disCodeParams);
	// 	 $discountIdArr = array();
	// 	 $discountIdStr = '';
	// 	 if(!empty($res_discount_code->data))
	// 	 {
	// 		 $discountIdArr = array_reduce($res_discount_code->data, create_function('$v,$w', '$v[$w->fk_discount]=$w->fk_discount;return $v;'));
	// 		 $discountIdStr = implode(',',$discountIdArr);
	// 	 }
	//
	// 	//t_discount
	// 	$disParams = array('discountid'=>$discountIdStr,'limit'=> -1,'page'=>1);
	// 	$res_discount = course_api::getDiscountByIds($disParams);
	// 	if(!empty($res_discount->data))
	// 	{
	// 	    $names         = array_reduce($res_discount->data, create_function('$v,$w', '$v[$w->pk_discount]=$w->name;return $v;'));
	// 		$discount_type = array_reduce($res_discount->data, create_function('$v,$w', '$v[$w->pk_discount]=$w->discount_type;return $v;'));
	// 		$discount_value= array_reduce($res_discount->data, create_function('$v,$w', '$v[$w->pk_discount]=$w->discount_value;return $v;'));
	// 		$min_fee       = array_reduce($res_discount->data, create_function('$v,$w', '$v[$w->pk_discount]=$w->min_fee;return $v;'));
	// 	}
	// 	$data = ['page'  => 1,'total' => 0];
	// 	if(!empty($res_discount_code->data))
	// 	{
	// 		$data = [
	// 			'page'  => $res_discount_code->page,
	// 			'total' => $res_discount_code->totalPage
	// 		];
	// 		foreach($res_discount_code->data as $val)
	// 		{
	// 			 $data['data'][] = [
	// 				'owner'         => $val->owner,
	// 				'fk_discount'   => $val->fk_discount,
	// 				'endtime'       => date('Y年m月d日',strtotime($val->endtime)),
	// 				'status'        => $val->status,
	// 				'discount_code' => $val->discount_code,
	// 				'used_num'      => $val->used_num,
	// 				'name'          => $names[$val->fk_discount],
	// 				'discount_type' => $discount_type[$val->fk_discount],
	// 				'discount_value'=> $discount_value[$val->fk_discount],
	// 				'min_fee'       => $min_fee[$val->fk_discount]
	// 			];
	// 		}
	// 	}
	//
	// 	$this->assign('data', $data);
	// 	$this->assign('num', $num);
	// 	$this->assign('path', $path);
	// 	$this->assign('code',$code);
	// 	$this->render('student/my.discount.html');
	// }
	//新版
	public function pageMyDiscount()
	{
		$this->render('student/my.discount2.html');
	}
	/**
	 * 新版我的优惠券
	 */
	public function pageDiscountCode(){

		$uid = $this->user['uid'];
		$page = isset($_GET['page']) && !empty($_GET['page']) ?  (int)$_GET['page'] : 1;
		$status = isset($_GET['status']) && !empty($_GET['status']) ? (int)$_GET['status'] : 0;
		$res = course_discount_api::getUserDiscoutCode($uid,$page,$status);
		if($res->code==0){
			$res->data->items = array_values($res->data->items);
			foreach($res->data->items as &$item){
				if($item->discount_type==2){
					$item->discount_value = $item->discount_value/10;
				}else{
					$item->discount_value = $item->discount_value/100;
				}
				$item->min_fee = $item->min_fee/100;
			}
		}
		return $res;
	}

	/**
	 * 兑换优惠码
	 * ajax
	 */
	public function pageExchangeCode(){
		$uid = $this->user['uid'];
		if(empty($_POST['code'])) return interface_func::error(4000,'请输入有效优惠码');
		$code = $_POST['code'];
		$code = htmlspecialchars(trim($code));
		$res = course_discount_api::ExchangeCode($code,$uid);
		echo json_encode(['code'=>$res->code,'message'=>$res->message],JSON_UNESCAPED_UNICODE);
	}

	//用户删除优惠码
	public function pageDeleteCode(){
		$uid = $this->user['uid'];
		if(empty($_GET['code'])) return interface_func::error(4000,'请输入有效优惠码');
		$code = $_GET['code'];
		$code = htmlspecialchars(trim($code));
		if(empty($code))  return interface_func::error(30000,'优惠码为空');
		$res = course_discount_api::DeleteCode($code,$uid);
		echo json_encode(['code'=>$res->code],JSON_UNESCAPED_UNICODE);
	}
}
?>
