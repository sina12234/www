<?PHP

class org_order extends org_base
{
	
	public function pageList($inPath)
	{
		$page   = !empty($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
		$length = 10;
		$type = false;
		$flag = !empty($_REQUEST['shelf']) ? $_REQUEST['shelf'] : 'all';
        $conf = ['expired'=>-2,'cancel'=>-4,'success'=>2];
		$path = 'org.order.list?shelf='.$flag;
        $params['status'] = !empty($conf[$flag]) ? $conf[$flag] : array();
		$params['orgId']  = $this->orgId;
		
		//关键字筛选
		$search   = '';
		$keywords = '';
		$discount = '';
		$orderType= '';
		if(!empty($_REQUEST['keywords'])){
			$search   = $_REQUEST['search'];
			$keywords = trim($_REQUEST['keywords']);
			if($search==1){
				//订单号筛选
				$params['order_sn'] = $keywords;
			}elseif($search==2){
				//课程名筛选
				$params['title'] = $keywords;
				$params['orderType'] = 1;
				$params['objectId'] = $this->likeCourse($keywords);
				
			}elseif($search==3){
				//手机号筛选
				$userInfo = user_api::likeMobile($keywords);
				if(!empty($userInfo)){
					$params['userId'] = array_reduce($userInfo, create_function('$v,$w', '$v[$w->fk_user]=$w->fk_user;return $v;'));
				}
			}elseif($search==4){
				//用户名筛选
				$userInfo = user_api::likeUserName($keywords);
				if(!empty($userInfo)){
					$params['userId'] = array_reduce($userInfo, create_function('$v,$w', '$v[$w->pk_user]=$w->pk_user;return $v;'));
				}
			}
			$path .= "&search={$search}&keywords={$keywords}";
		}
		//时间筛选
		if(!empty($_REQUEST['start_time']) && !empty($_REQUEST['end_time'])){
			$type = true;
			$params['start_time'] = $_REQUEST['start_time'];
            $params['end_time']   = $_REQUEST['end_time'];
			$params['time'] = $_REQUEST['start_time'].','.$_REQUEST['end_time'];
            $path .= "&start_time={$params['start_time']}&end_time={$params['end_time']}";
		}
		//价格筛选
		if(isset($_REQUEST['price1']) && isset($_REQUEST['price2'])){
			$type = true;
			$params['price1'] = (int)($_REQUEST['price1']);
			$params['price2'] = (int)($_REQUEST['price2']);
			$params['price'] = (int)($_REQUEST['price1']).','.(int)($_REQUEST['price2']);
			$path .= "&price1={$params['price1']}&price2={$params['price2']}";
	    }
		//优惠券筛选
        if(isset($_REQUEST['discount'])){
			$type = true;
			$discount = $_REQUEST['discount'];
			$params['isFavorable'] = $discount;
			$path .= "&discount={$discount}";
        }
		//订单类型筛选
		if(isset($_REQUEST['orderType'])){
			$type = true;
			$orderType = $_REQUEST['orderType'];
			$params['orderType'] = $orderType;
			$path .= "&orderType={$orderType}";
		}

		$orderList = order_api::orderList($params, $page, $length);

		$this->assign('length',$length);
        $this->assign('page',$page);
		$this->assign('totalPage',$orderList['total']);
		$this->assign('totalSize',$orderList['totalSize']);
		$this->assign('orderList',$orderList['data']);
		$this->assign('params',$params);
		$this->assign('flag',$flag);
		$this->assign('search',$search);
		$this->assign('keywords',$keywords);
		$this->assign('discount',$discount);  
		$this->assign('orderType',$orderType);
		$this->assign('path',$path);
		$this->assign('type',$type);
		$this->render('org/order.list.v3.html');
	}
	
	//模糊搜索课程
	private function likeCourse($title)
	{
		$data = array();
		if(empty($title)) return $data;
		$params = [
			'q' => ['search_field'=>$title],
			'f' => ['course_id']
		];
		$courseRes = seek_api::seekcourse($params);
		$courseIds = '';
		if(!empty($courseRes->data)){
			foreach($courseRes->data as $val){
				$courseIds .= $val->course_id.',';
			}
			$data = trim($courseIds,',');
		}
		
		return $data;
	}
	
	
	
	
	
}
?>
