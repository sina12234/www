<?php
class student_order extends STpl{

	var $user;
	private $domain;
	private $orgOwner;
    function __construct(){
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
		$this->assign('domain', $this->domain);

		$this->user = user_api::loginedUser();
        if(empty($this->user)){
			if(!empty($_SERVER['REQUEST_URI'])){
            	$this->redirect("/site.main.login?url=".$_SERVER['REQUEST_URI']);
            }else{
                $this->redirect("/site.main.login");
            }
        }
        $org=user_organization::subdomain();
        if(!empty($org)){
            $this->orgOwner=$org->userId; //机构所有者id 以后会根据域名而列取
        }else{
            header('Location: https://www.'.$this->domain);
        }
    }
	
	public function pageMyOrder($inPath)
	{
		$length = 10;
		$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		//机构信息
		$orgInfo = user_organization::getOrgByOwner($this->orgOwner);
		//支付状态
		$statusArr = ["all"=>"0","pay"=>"1","suc"=>"2","del"=>"-1","exp"=>"-2","fai"=>"-3","cal"=>"-4"];
		$statusArray = [
						"initial"=>"未支付","paying"=>"正在支付","success"=>"已支付","cancel"=>"订单已取消",
						"deleted"=>"订单已删除","expired"=>"超时失效","fail"=>"订单失败"
					   ];
		//支付类型
		$payType = [0=>'未知',1=>'支付宝',2=>'微信',3=>'免费',4=>'云点支付']; 

		$getSta = empty($inPath[3]) ? 'all' : $inPath[3];
		$status = $statusArr[$getSta];
		$path   = '/student.order.myorder/'.$getSta;
		
        $params['userId'] = $this->user['uid'];
        //$params['orgId']  = $orgInfo->oid;
        if(!empty($status)){
            $params['status'] = $status;
        }
		$params['objTypeStatus'] = 1;
		$listFeeInfo = order_api::orderList($params, $page, $length);
		$this->assign("order_list",$listFeeInfo['data']);
		$this->assign('pay_type', $payType);
		$this->assign('get_sta', $getSta);
		$this->assign("status_array",$statusArray);
		$this->assign("page",$page);		
		$this->assign("path",$path);	
		$this->assign("num",$length);
		$this->assign("totalPage",$listFeeInfo['total']);		
		$this->render('/student/my.order.v2.html');
	}
	
	public function pageUpdateStatusAjaxV2()
	{
		$result= new stdclass;
		if(empty($_POST['order_id']) || empty($_POST['status'])){
			$result->msg  = '参数错误！';
			$result->code = -1;
			return $result;
		}
		$orderId = (int)$_POST['order_id'];
		$status  = array('status'=>$_POST['status']);
		$orderInfo = order_api::getOrderInfo(array('orderId'=>$orderId));
		if(empty($orderInfo->data)){
			$result->msg = '操作失败';
			$result->code  = -1;
			return $result;
		}elseif($orderInfo->data->status=='success'){
			$result->msg = '订单已经支付过了';
			$result->code  = -1;
			return $result;
		}
		$ret     = order_api::updateOrder($orderId, $status);

		if($ret->code == 1){
			$result->msg = '操作成功';
			$result->code  = 1;
			return $result;
		}else{
			$result->msg = '操作失败';
			$result->code  = -1;
			return $result;
		}
	}

}
