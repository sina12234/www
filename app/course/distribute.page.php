<?php
class course_distribute extends STpl{
	private $domain;
	private $platform;
	private $orgOwner;
	private $orgId;
	function __construct(){
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf");
		$this->domain = $domain_conf->domain->domain;
		$this->assign('domain', $this->domain);
		$this->platform = $domain_conf->platform->platform;
		$this->assign('platform', $this->platform);

		$org=user_organization::subdomain();
		if(!empty($org)){
			$this->orgOwner = $org->userId; //机构所有者id 以后会根据域名而列取
		}else{
			header('Location: https://www.'.$this->domain);
		}
		$this->orgInfo = user_organization::getOrgByOwner($this->orgOwner);
		if(!empty($this->orgInfo->oid)){
			$this->orgId = $this->orgInfo->oid;
		}else{
			header('Location: https://'.$org->subdomain);
		}
	}
	/*渠道分销课程*/
	public function pageList(){
		$page = !empty($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$size = !empty($_REQUEST['size'])?(int)$_REQUEST['size']:20;
		$searchField = isset($_REQUEST['search_field'])?$_REQUEST['search_field']:'';
		$sort = !empty($_REQUEST['sort'])?$_REQUEST['sort']:'t_course_distribute.last_updated desc';
		$userID      = $this->orgOwner;
		$params      = array('sort'=>$sort ,'uid'=>$userID,'search'=>$searchField);
		$resCourse   = course_distribute_api::getDistributeCourselist($page, $size, $params);
		$course_ids  = '';
		$status_arr = course_resell_api::$resell_status_arr;
		$course_type_arr = ['1'=>'直播课' , '2'=>'录播课' , '3'=>'线下课'];
		$courseList = array();
		if(!empty($resCourse->data)){
			$courseList = $resCourse->data->items;
			//获取科目名称
			foreach($courseList as $key => $value){
				$course_ids .= $value->course_id.',';
				$courseList[$key]->course_type_str  = isset($course_type_arr[$value->course_type]) ? $course_type_arr[$value->course_type] : '' ;
			}
			$course_ids .= substr($course_ids, 0,-1);

			$count = $resCourse->data->totalSize;   // 总条数
			$total = ceil($count/$size);  // 总页数
		} else {
			$count = $total = 0;
		}
		// 从中间层获取课程数据
		$seek_course_info = course_resell_api::getSeekCourseInfo($course_ids);
		// 获取章节数，分类 ，属性 信息
		$attrValues = course_resell_api::getCourseAttr($seek_course_info);
		$pm = [
			'page' =>$page,
			'size' =>$size,
			'total' =>$total,
			'count' =>$count,
			'search_field'=> $searchField,
		];
		$path = '/course.distribute.list';
		$path_page = utility_tool::getUrl($path);
		$this->assign("path",$path);
		$this->assign("path_page",$path_page);
		$this->assign("courseList",$courseList);
		$this->assign("pm",$pm);
		$this->assign("attrValues",$attrValues);
		$this->render("/course/distribute/course.distribute.list.html");
	}
	/*渠道分销成交记录*/
	public function pageLogList(){
		$option = !empty($_POST) ? $_POST : $_GET;
		$page = !empty($option['page']) ? (int)$option['page'] : 1;
		$length = 10;
		$path = 'course.distribute.logList';
		$params['orgId'] = $this->orgId;
		$params['status'] = 2;
		$params['distribute_status'] = 1;
		$search = !empty($option['search']) ? (int)$option['search'] : 0;
		$keywords = !empty($option['keywords']) ? trim($option['keywords']) : '';
		if (!empty($keywords) && !empty($search)) {
			if ($search == 1) {
				//订单号筛选
				$params['orderSn'] = $keywords;
			} elseif ($search == 2) {
				//课程名筛选
				$params['title'] = $keywords;
				$params['orderType'] = 1;
				$params['objectId'] = $this->likeCourse($keywords);
				if(empty($params['objectId'])){
					$status=false;
				}
			} elseif ($search == 3) {
				//手机号筛选
				$userInfo = user_api::likeMobile($keywords);
				if (!empty($userInfo)) {
					$params['userId'] = array_reduce($userInfo, create_function('$v,$w', '$v[$w->fk_user]=$w->fk_user;return $v;'));
				}else{
					$status=false;
				}
			} elseif ($search == 4) {
				//用户名筛选
				$userInfo = user_api::likeUserName($keywords);
				if (!empty($userInfo)) {
					$params['userId'] = array_reduce($userInfo, create_function('$v,$w', '$v[$w->pk_user]=$w->pk_user;return $v;'));
				}else{
					$status=false;
				}
			}
			$path .= "&search={$search}&keywords={$keywords}";
		}
		$status = true;
		$orderList = order_api::orderList($params, $page, $length);
		//var_dump($orderList['data'][0]->course);die;
		$this->assign('search', $search);
		$this->assign('keywords', $keywords);
		$this->assign('length', $length);
		$this->assign('page', $page);
		$this->assign('totalPage', $orderList['total']);
		$this->assign('totalSize', $orderList['totalSize']);
		$this->assign('orderList', $orderList['data']);
		$this->assign('params', $params);
		$this->assign('path', $path);
		$this->render("/course/distribute/org.distribute.log.list.html");
	}
	/*渠道分销课程成交记录*/
	public function pageCourseLogList($inPath){
		$option = !empty($_POST) ? $_POST : $_GET;
		$page = !empty($option['page']) ? (int)$option['page'] : 1;
		$length = 10;
		$path = 'course.distribute.courselogList';
		$courseId = !empty($inPath[3])?$inPath[3]:0;
		if(!empty($courseId)){
			$path .= "/".$courseId;
			$params['objectId'] = $courseId;
		}
		$params['orgId'] = $this->orgId;
		$params['status'] = 2;
		$params['distribute_status'] = 1;
		$params['distribute_qudao'] = 100000;
		$orderList = order_api::orderList($params, $page, $length);
		//var_dump($orderList['data']);die;
		$this->assign('length', $length);
		$this->assign('page', $page);
		$this->assign('totalPage', $orderList['total']);
		$this->assign('totalSize', $orderList['totalSize']);
		$this->assign('orderList', $orderList['data']);
		$this->assign('params', $params);
		$this->assign('path', $path);
		$this->render("/course/distribute/org.distribute.course.log.list.html");
	}
	public function pageAddDistribute(){
		$courseId   = !empty($_REQUEST['courseId'])?$_REQUEST['courseId']:'';
		$costPrice = !empty($_REQUEST['costPrice'])? (float) $_REQUEST['costPrice']:0;
		$params=array(
			'fk_course'=>$courseId,
			'rate'=>$costPrice,
			'create_time'=> date('Y-m-d H:i:s',time()),
		);
		$ret = course_distribute_api::addDistribute($params);
		if($ret->code==0){
			course_api::updateCourseV2($courseId,array("is_distribute"=>1));
		}
		return json_encode($ret);
	}
	/* 修改分成比例,已删除的课程重新推广 */
	function pageEditDistributeCourseAjax($inPath){
		$op = empty($inPath[3]) ? (empty($inputData['act']) ? '':$inputData['act']) : $inPath[3];  // 'orgStartPromote','updatePromotePrice'
		$course_id = !empty($_REQUEST['cid'])?$_REQUEST['cid']:'';
		$rate = !empty($_REQUEST['rate'])? (float) $_REQUEST['rate']:0;
		$ret = new stdClass();
		if (empty($course_id) || (!empty($price_old) && !isset($price_promote))) {
			$code = -1;
			$msg  = 'The course_id or price is empty!';
		} else {
			$params = [
				'rate' => $rate,
				'status' => 1
			];
			$res = course_distribute_api::EditDistributeCourse($course_id,$params);
			$code = isset($res->code) ? $res->code : '';
			$msg  = isset($res->msg) ? $res->msg : '';
		}
		if($code==0){
			course_api::updateCourseV2($course_id,array("is_distribute"=>1));
		}
		$ret->code = $code;
		$ret->msg  = $msg;
		return json_encode($ret);
	}
	/* (删除+暂停推广+重新推广)推广课程 */
	function pageChangeDistributeCourseAjax($inPath){
		$course_id = !empty($_REQUEST['cid'])?$_REQUEST['cid']:'';
		$course_title = !empty($_REQUEST['ctitle'])?$_REQUEST['ctitle']:'';
		$status = !empty($_REQUEST['status'])?$_REQUEST['status']:0;
		$ret = new stdClass();
		if (empty($course_id)) {
			$code = -1;
			$msg  = 'The request str is empty!';
		} else {
			$op         = empty($inPath[3]) ? '' : $inPath[3];
			if (empty($op)) {
				$code = -2;
				$msg  = 'The operation is empty({$op})!';
			} else {
				$params = array(
					'status' => $status
				);
				// (删除+暂停推广+重新推广)推广课程
				$res = course_distribute_api::changeDistributeCourse($course_id,$params);

				$code = isset($res->code) ? $res->code : '';
				$msg  = isset($res->msg) ? $res->msg : '';

			}
		}
		if($code==0){
			course_api::updateCourseV2($course_id,array("is_distribute"=>0));
		}
		$ret->code = $code;
		$ret->msg  = $msg;
		return json_encode($ret);
	}
	//模糊搜索课程
	private function likeCourse($title)
	{
		$data = array();
		if (empty($title)) return $data;
		$params = [
			'q' => ['search_field' => $title,'deleted'=>'0,-1'],
			'f' => ['course_id']
		];
		$courseRes = seek_api::seekcourse($params);
		$courseIds = '';
		if (!empty($courseRes->data)) {
			foreach ($courseRes->data as $val) {
				$courseIds .= $val->course_id . ',';
			}
			$data = trim($courseIds, ',');
		}
		return $data;
	}
}
