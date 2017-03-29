<?php
/*
 * chat传递消息接口
 */
class org_discount extends STpl{
	var $user;
	private $domain;
	private $orgOwner;
	private $orgInfo;
	function __construct(){
        //如果没有登陆到登陆界面
        $this->user = user_api::loginedUser();
        if(empty($this->user)){
            $this->redirect("/site.main.login");
        }
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
        $org=user_organization::subdomain();
        if(!empty($org)){
            $this->orgOwner=$org->userId; //机构所有者id 以后会根据域名而列取
        }else{
            header('Location: https://www.'.$this->domain);
        }
        $this->orgInfo=user_organization::getOrgByOwner($this->orgOwner);
        //判断管理员
        $isAdmin=user_api::isAdmin($this->orgOwner,$this->user['uid']);
        if($isAdmin===false){
            header('Location: //'.$org->subdomain.'.'.$this->domain);
        }
	}
	public function pageCreate($inPath){
		$courses = course_api::getFeeCoursesByOrg($this->user["uid"]);
		if(empty($courses->data)){
			$this->assign("courses", false);
		}else{
			$this->assign("courses", $courses->data);
		}
		return $this->render("user/discount.create.html");
	}
	public function pageCreateOk($inPath){
		$ret = course_api::createDiscount($_POST, $this->user["uid"]);
		if(0 == $ret->result->code){
			$this->redirect("/org.discount.list");
			return;
		}else{
			return $ret;
		}
	}
	public function pageList($inPath){
		if(empty($_REQUEST["limit"])){
			$_REQUEST["limit"] = 10;
		}
		if(empty($_REQUEST["page"])){
			$_REQUEST["page"] = 1;
		}
		$discounts = course_api::listDiscount($_REQUEST, $this->user["uid"]);
		//$this->assign("discounts", SJson::encode($discounts));
		$this->assign("discounts", $discounts);
		$this->assign("page", $_REQUEST["page"]);
		$this->assign("limit", $_REQUEST["limit"]);
		return $this->render("user/discount.list.html");
	}
	public function pageForbid($inPath){
		if(empty($inPath[3])){
			$this->redirect("/");
		}
		$discount_id = $inPath[3];
		$discounts = course_api::forbidDiscount($this->user["uid"], $discount_id);
		$this->redirect("/org.discount.list");
	}
	public function pageListCode($inPath){
		if(empty($inPath[3])){
			$this->redirect("/");
		}
		if(empty($_REQUEST["limit"])){
			$_REQUEST["limit"] = 10;
		}
		if(empty($_REQUEST["page"])){
			$_REQUEST["page"] = 1;
		}
		$discount_id = $inPath[3];
		$discounts = course_api::listDiscountCode($_REQUEST, $this->user["uid"], $discount_id);
		if(0 != $discounts->result->code){
			$this->redirect("/org.discount.list");
		}
		//$this->assign("discounts", SJson::encode($discounts));
		$this->assign("discounts", $discounts);
		$this->assign("page", $_REQUEST["page"]);
		$this->assign("limit", $_REQUEST["limit"]);
		return $this->render("user/discount.listcode.html");
	}
	public function pageCreateCodeOk($inPath){
		$ret = course_api::createDiscountCode($_POST, $this->user["uid"]);
		if(0 == $ret->result->code){
			$this->redirect("/org.discount.listcode/".$_POST["discount_id"]);
			return;
		}else{
			return $ret;
		}
	}
	public function pageListCodeUsed($inPath){
		if(empty($inPath[3])){
			$this->redirect("/");
		}
		if(empty($_REQUEST["limit"])){
			$_REQUEST["limit"] = 10;
		}
		if(empty($_REQUEST["page"])){
			$_REQUEST["page"] = 1;
		}
		$discount_code = $inPath[3];
		$discounts = course_api::listDiscountCodeUsed($_REQUEST, $this->user["uid"], $discount_code);
		if(0 != $discounts->result->code){
			$this->redirect("/org.discount.list");
		}
		//$this->assign("discounts", SJson::encode($discounts));
		$this->assign("discounts", $discounts);//var_dump($discounts);
		$this->assign("page", $_REQUEST["page"]);
		$this->assign("limit", $_REQUEST["limit"]);
		return $this->render("user/discount.listcodeused.html");
	}
	public function pageForbidCode($inPath){
		if(empty($inPath[4])){
			$this->redirect("/");
		}
		$discount_code_id = $inPath[3];
		$discount_id = $inPath[4];
		$ret = course_api::ForbidDiscountCode($this->user["uid"], $discount_code_id);
		$this->redirect("/org.discount.listcode/$discount_id");
	}
	//---------------------------- v2 -----------------------------
	public function pageCreateV2($inPath){
		if(!empty($_POST["discount_name"])){
			$ret = course_api::createDiscountV2($_POST, $this->orgOwner);
			if(0 != $ret->result->code){
				echo "<script type='text/jscript' charset='utf-8'>alert('min_fee not less than zero!');window.location.href='/org.discount.listv2';</script>";
				exit;
			}else{
				$this->redirect("/org.discount.listv2");
			}
			return;
		}
		echo "没有优惠规则名！";
	}

	/**
	 * @param 创建优惠码接口
	 */
	 public function pageCreateV2New($inPath){
  		if(!empty($_POST["discount_name"]) && trim($_POST['discount_name'])){
  			$_POST['discount_type'] = isset($_POST['discount_type']) && !empty($_POST['discount_type']) ? (int) $_POST['discount_type'] : 1;
  			$_POST['min_fee'] = isset($_POST['min_fee']) && !empty($_POST['min_fee']) ? $_POST['min_fee'] : 1999;
  			$_POST['discount_value'] = isset($_POST['discount_value']) && !empty($_POST['discount_value']) ? $_POST['discount_value'] : 9;
  			$_POST['total_num'] = isset($_POST['total_num']) ? $_POST['total_num'] : 0;
  			$_POST['introduction'] = isset($_POST['introduction']) ? $_POST['introduction'] : '';
  			$_POST['user_limit'] = $_POST['total_num'];
  			$_POST['duration'] = isset($_POST['duration']) ? $_POST['duration'] : 0;
  			$_POST['course_id'] = isset($_POST['course_id']) ? $_POST['course_id'] : 0;
  			$_POST['create_code'] = isset($_POST['create_code']) ? $_POST['create_code'] : '';
  			$_POST['start_time'] = isset($_POST['start_time']) ? $_POST['start_time'] : date('Y-m-d H:i:s');
  			$_POST['object_id'] = isset($_POST['object_id']) ? $_POST['object_id'] : '';
  			$_POST['object_type'] = isset($_POST['object_type']) ? $_POST['object_type'] : '';
			if(empty($_POST['create_code']) || (intval($_POST['create_code']))<=0 ) return interface_func::error(30000,'数量必填');
  			if(empty($_POST['object_id']) || empty($_POST['object_type'])) return interface_func::setMsg(1000);
			if(!in_array($_POST['object_type'],[1,3])) return interface_func::error(30000,'type类型不合法');
			if(!($_POST['total_num'] >=0 && $_POST['total_num']<=999)) return interface_func::setMsg(30001,'使用次数不合法');
  			$_POST['org_id'] = $this->orgInfo->oid;
  			$ret = course_discount_api::createDiscountV2New($_POST, $this->orgOwner);
  			echo json_encode(array('code'=>$ret->result->code,'message'=>$ret->result->msg),JSON_UNESCAPED_UNICODE);exit();
  		}
  		echo json_encode(array('code'=>-1,'message'=>'优惠规则名称为空'),JSON_UNESCAPED_UNICODE);
  	}

	/**
 	 * sheng cheng youhui ma jiekou
 	 *
 	 */

 	public function pageCreateCodeV2New($inPath){
 		if(!isset($_POST['discount_id'])) return interface_func::setMsg(1000);
 		$discount_id = (int)$_POST['discount_id'];
 		if(!empty($_POST["num"])){
 			course_discount_api::createDiscountCodeV2New($_POST["num"], $this->orgOwner, $discount_id);
 			return json_encode(array('code'=>0,'message'=>'success'));
 		}
 		return interface_func::setMsg(1000);
 	}
	/**
	 *  优惠管理列表
	 */
	 public function pageListV2New($inPath){
  		$page = isset($_GET['page'])&& !empty($_GET['page']) ? (int)$_GET['page'] : 1;
  		$status = isset($_GET['status'])&& !empty($_GET['status']) ? (int)$_GET['status'] : '';
  		$length  = 10;
  		$search = isset($_GET['search'])&& !empty(trim($_GET['search'])) ? trim($_GET['search']) : '';

  		$time_over = isset($_GET['time_over'])&& !empty(trim($_GET['time_over'])) ? trim($_GET['time_over']) : '';
  		$ret = course_discount_api::getDiscountInfoList($this->orgInfo->oid,$page,$status,$length,$search,$time_over);
  		if(!empty($ret->data->items)){
  			foreach($ret->data->items as $key=>&$item){
  				$item->discount_value = $item->discount_value/100;
  				$item->min_fee        = $item->min_fee/100;
  				if($item->duration==0){
  					$item->limit_time = '永久';
  				}else{
  					$item->limit_time     =(date('Y.m.d',strtotime($item->starttime))). '-'. date('Y.m.d',strtotime($item->starttime)+$item->duration);
  				}
				if($item->duration==0){

				}elseif(strtotime($item->starttime)+$item->duration < time()){
					if($status==1 || $status==2){
						unset($ret->data->items[$key]);continue;
					}else{
						$item->status = -3;//过期
					}
				}
				if(isset($item)){
					$item->url = $this->createDiscountUrl($item->pk_discount);
				}

  			}
			rsort($ret->data->items);
  			return $ret;
  		}
  		return json_encode(array('code'=>-1,'message'=>'faild'));
  	}

	/**
 	 * @param 生成优惠码链接
 	 */
 	public function createDiscountUrl($pk_discount){

 		if(empty((int)$pk_discount)) return interface_func::setMsg(1000);
 		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/discountCodeSalt.conf","salt");
 		$salt = $domain_conf->one;
 		$salt = $salt->name;
 		$domain = user_organization::subdomain()->subdomain;
 		return $domain.'/student/receivecode/ReceiveCode/'.base64_encode($pk_discount.$salt);
 	}

	public function pageListV2($inPath){
		if(empty($_REQUEST["limit"])){
			if(!empty($_REQUEST["size"])){
				$_REQUEST["limit"] = $_REQUEST["size"];
			}else{
				$_REQUEST["limit"] = 8;
			}
		}
		if(empty($_REQUEST["page"])){
			$_REQUEST["page"] = 1;
		}
		$discounts = course_api::listDiscountV2($_REQUEST, $this->orgOwner);
		$courses = course_api::getFeeCoursesByOrg($this->orgOwner);
		if(empty($courses->data)){
			$this->assign("courses", false);
		}else{
			$this->assign("courses", $courses->data);
		}
		//$this->assign("discounts", SJson::encode($discounts));
		//$discounts->total = 0;
		$this->assign("ck_patener1", '/^0{1}([.]\d{1})?$|^[1-9]\d*([.]{1}[0-9]{1})?$/');
		$this->assign("ck_patener2", '/^0{1}([.]\d{1,2})?$|^[1-9]\d*([.]{1}[0-9]{1,2})?$/');
		$this->assign("discounts", $discounts);
		$this->assign("page", $_REQUEST["page"]);
		$this->assign("limit", $_REQUEST["limit"]);
		return $this->render("user/discount.listv2.html");
	}
	public function pageCreateCodeV2($inPath){
		if(empty($inPath[3])){
			$this->redirect("/");
		}
		$discount_id = $inPath[3];
		if(!empty($_POST["num"])){
			course_api::createDiscountCodeV2($_POST["num"], $this->orgOwner, $discount_id);
		}
		$this->redirect("/org.discount.listcodev2/$discount_id");
	}
	public function pageListCodeV2($inPath){
		if(empty($inPath[3])){
			$this->redirect("/");
		}
		$discount_id = $inPath[3];
		if(empty($_REQUEST["limit"])){
			if(!empty($_REQUEST["size"])){
				$_REQUEST["limit"] = $_REQUEST["size"];
			}else{
				$_REQUEST["limit"] = 10;
			}
		}
		if(empty($_REQUEST["page"])){
			$_REQUEST["page"] = 1;
		}
		$discounts = course_api::listDiscountCodeV2($_REQUEST, $this->orgOwner, $discount_id);
		if(0 != $discounts->result->code){
			$this->redirect("/org.discount.listv2");
		}
		$this->assign("discount_id", $discount_id);
		$this->assign("discounts", $discounts);
		$this->assign("page", $_REQUEST["page"]);
		$this->assign("limit", $_REQUEST["limit"]);
		return $this->render("user/discount.listcodev2.html");
	}
	public function pageListCodeUsedV2($inPath){
		if(empty($inPath[3])){
			$this->redirect("/");
		}
		$discountcode = $inPath[3];
		if(empty($_REQUEST["limit"])){
			if(!empty($_REQUEST["size"])){
				$_REQUEST["limit"] = $_REQUEST["size"];
			}else{
				$_REQUEST["limit"] = 10;
			}
		}
		if(empty($_REQUEST["page"])){
			$_REQUEST["page"] = 1;
		}
		$discounts = course_api::listDiscountCodeUsedV2($_REQUEST, $this->orgOwner, $discountcode);
		if(0 != $discounts->result->code){
			$this->redirect("/org.discount.listv2");
		}
		$this->assign("discounts", $discounts);
		$this->assign("discountcode", $discountcode);
		$this->assign("page", $_REQUEST["page"]);
		$this->assign("limit", $_REQUEST["limit"]);
		return $this->render("user/discount.listcodeusedv2.html");
	}
	public function pageForbidV2($inPath){
		if(empty($inPath[3])){
			return false;
		}
		$discount_id = $inPath[3];
		$discounts = course_api::forbidDiscount($this->orgOwner, $discount_id);
		return true;
	}
	public function pageRecoverV2($inPath){
		if(empty($inPath[3])){
			return false;
		}
		$discount_id = $inPath[3];
		$discounts = course_api::recoverDiscount($this->orgOwner, $discount_id);
		return true;
	}
	public function pageForbidCodeV2($inPath){
		if(empty($inPath[4])){
			return false;
		}
		$discount_code_id = $inPath[3];
		$discount_id = $inPath[4];
		$ret = course_api::forbidDiscountCode($this->orgOwner, $discount_code_id);
		return true;
	}
	public function pageRecoverCodeV2($inPath){
		if(empty($inPath[4])){
			return false;
		}
		$discount_code_id = $inPath[3];
		$discount_id = $inPath[4];
		$ret = course_api::recoverDiscountCode($this->orgOwner, $discount_code_id);
		return true;
	}

	/**
	 * 优惠码编辑
	 */
	 public function pageEditDiscountCode($inPath){
  		if(!isset($_GET['pk_discount'])) return interface_func::setMsg(1000);
  		$pk_discount = (int)$_GET['pk_discount'];
  		//获取优惠码的详细信息
  		$ret = course_discount_api::getDiscountInfo($pk_discount,$this->orgInfo->oid);
		if(!empty($ret->data)){
			if($ret->data[0]->discount_type==2){
				$ret->data[0]->discount_value = $ret->data[0]->discount_value/10;
				$ret->data[0]->min_fee = $ret->data[0]->min_fee/100;
			}else{
				$ret->data[0]->discount_value = $ret->data[0]->discount_value/100;
				$ret->data[0]->min_fee = $ret->data[0]->min_fee/100;
			}

		}
  		return $ret;
  	}

	// 优惠更新接口
 	public function pageUpdateEditDiscountCode(){
 		if(!isset($_POST['pk_discount']) || !isset($_POST['start_time']) || !isset($_POST['introduction']) || !isset($_POST['duration']) || !isset($_POST['object_id']) || !isset($_POST['object_type'])){
 			return interface_func::setMsg(1000);
 		}
		if(!in_array($_POST['object_type'],[1,3])) return interface_func::error(30002,'type不符合要求');
 		$ret = course_discount_api::UpdateEditDiscountCode($_POST);
 		return $ret;
 	}

	/**
 	 *停用  -1   启用 0       删除 -2       优惠t_discount
 	 * 用户 绑定的优惠码对应status
 	 *ajax 方法
 	 * -1          0          -2            t_discout_code_user
 	 */
 	public function pageUpdateDiscountStatus($inPath){
 		$org_id = $this->orgInfo->oid;//机构id
 		$pk_discount = isset($_POST['pk_discount']) && !empty((int)$_POST['pk_discount']) ? (int)$_POST['pk_discount'] : '';
 		if(empty($pk_discount)) return interface_func::setMsg(1000);
 		if(isset($_POST['status']) && (in_array((int)$_POST['status'],array(0,-1,-2)))){
 			$st =  course_discount_api::UpdateDiscountStatus($pk_discount,$org_id,$_POST['status']);
 			echo json_encode(array('code'=>$st->code,'message'=>$st->message));
 		}else{
 			echo interface_func::setMsg(1000);
		}
 	}

	/**
 	 * 优惠码列表 详情
 	 */
 	public function pagediscountListInfo(){
 		// 全部 status='all'   0 未绑定 已绑定 1  失效  -1   可用 used_num=on  失效 已用完 used_num=off
 		$orgOwner = $this->orgOwner;
 		if(empty($_GET['pk_discount'])) return interface_func::setMsg(1000);

		$status = isset($_GET['status']) && !empty($_GET['status'])  ? $_GET['status'] : 'all';
		$used_num = isset($_GET['used_num']) && !empty($_GET['used_num']) ? $_GET['used_num']  : 'all';
		$fk_discount = (int)$_GET['pk_discount'];
 		$page = isset($_GET['page'])&&!empty($_GET['page']) ? (int)$_GET['page'] : 1;
		$size = isset($_GET['size'])&&!empty($_GET['size']) ? (int)$_GET['size'] : 20;
 		$result = course_discount_api::getDiscountCodeListBydiscount($status,$fk_discount,$orgOwner,$page,$used_num,$size);
		if(empty($result->discount_data->pk_discount)) return interface_func::error(100001,'不存在');
 		$result->discount_data->url = $this->createDiscountUrl($result->discount_data->pk_discount);
 		return $result;
 	}

	/**
 	 * 优惠码使用情况
 	 */
 	public function pageCodeInfo(){
 		if(empty($_GET['code'])) return interface_func::setMsg(1000);
		$page = isset($_GET['page']) && !empty($_GET['page']) ? (int)$_GET['page'] : 1;
 		$code = htmlspecialchars(trim($_GET['code']));
 		$orgOwner = $this->orgOwner;
 		$res =course_discount_api::getcodeUsed($orgOwner,$code,$page);
 		return $res;
 	}

	/**
	 * 优惠码使用详情导出
	 */
	public function pageCodeExcel(){
		if(empty($_GET['code'])) return interface_func::setMsg(1000);
		$code = htmlspecialchars(trim($_GET['code']));
		$orgOwner = $this->orgOwner;
		$res =course_discount_api::getcodeUsed($orgOwner,$code);
		if(empty($res->data->items)) return false;
		require_once(ROOT_LIBS . "/phpexcel/PHPExcel.class.php");
		require_once(ROOT_LIBS . "/phpexcel/PHPExcel/Writer/Excel2007.php");
		//实例化表格
		$objPHPExcel = new PHPExcel();
		//基本设置
		$fileName = "优惠码";
		$objProps = $objPHPExcel->getProperties();
		$objProps->setCreator("www.yunke.com");
		$objProps->setLastModifiedBy("www.yunke.com");
		$objProps->setTitle("Office XLS Test Document");
		$objProps->setSubject("Office XLS Test Document, yunke");
		$objProps->setDescription("Test document, generated by PHPExcel.");
		$objProps->setKeywords("office excel PHPExcel");
		$objProps->setCategory("报表");
		$objPHPExcel->setActiveSheetIndex(0);
		$objActiveSheet = $objPHPExcel->getActiveSheet();
		$objActiveSheet->setTitle($fileName);
		//设置表头
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objActiveSheet->getStyle('A1')->getFont()->setBold(true);
		$objActiveSheet->getStyle('B1')->getFont()->setBold(true);
		$objActiveSheet->getStyle('C1')->getFont()->setBold(true);
		$objActiveSheet->getStyle('D1')->getFont()->setBold(true);

		$objActiveSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objActiveSheet->setCellValue('A1', '用户');
		$objActiveSheet->setCellValue('B1', '手机号');
		$objActiveSheet->setCellValue('C1', '使用时间');
		$objActiveSheet->setCellValue('D1', '账单号');
		// 防止科学计数
		$objActiveSheet->getStyle('C1')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$objActiveSheet->getStyle('D1')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		//填充表格内容
		$j = 2;
		foreach ($res->data->items as $k => $v) {
			$objActiveSheet->setCellValue('A' . $j, $v->username);
			$objActiveSheet->setCellValue('B' . $j, $v->mobile);
			if($v->status==2){
				$objActiveSheet->setCellValue('C' . $j, '待支付');
			}else{
				$objActiveSheet->setCellValue('C' . $j, $v->createtime);
			}
			$objActiveSheet->setCellValue('D' . $j, $v->order_num);
			$objActiveSheet->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActiveSheet->getStyle('B' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objActiveSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActiveSheet->getStyle('D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$j++;
		}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		if(empty($fileName)){
			$ftime = time();
			$outputFileName = $ftime . ".xls";
		}else{
			$outputFileName = $fileName . ".xls";
		}
		//加此防乱码
		ob_clean();
		@header("Pragma: public");
		@header("Expires: 0");
		@header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
		@header("Content-Type:charset=UTF-8");
		@header("Content-Type:application/force-download");
		@header("Content-Type:application/vnd.ms-execl");
		@header("Content-Type:application/octet-stream");
		@header("Content-Type:application/download");
		@header('Content-Disposition:attachment;filename="' . $outputFileName . '"');
		@header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');

	}

	/**
	 * 优惠码列表条件筛选导出excel
	 */
	public function pageCodeListExcel(){
		$orgOwner = $this->orgOwner;
		if(empty($_GET['pk_discount'])) return interface_func::setMsg(1000);

		$status = isset($_GET['status'])  ? $_GET['status'] : 'all';
		$used_num = isset($_GET['used_num'])&& !empty($_GET['used_num']) ? $_GET['used_num']  : 'all';
		$fk_discount = (int)$_GET['pk_discount'];
		$page = isset($_GET['page'])&&!empty($_GET['page']) ? (int)$_GET['page'] : 1;
		$size = isset($_GET['size'])&&!empty($_GET['size']) ? (int)$_GET['size'] : 1000;
		$result = course_discount_api::getDiscountCodeListBydiscount($status,$fk_discount,$orgOwner,$page,$used_num,$size);
		//$result->discount_data->url = $this->createDiscountUrl($result->discount_data->pk_discount);
		if(empty($result->data->items)) return false;
		require_once(ROOT_LIBS . "/phpexcel/PHPExcel.class.php");
		require_once(ROOT_LIBS . "/phpexcel/PHPExcel/Writer/Excel2007.php");
		//实例化表格
		$objPHPExcel = new PHPExcel();
		//基本设置
		$fileName = "优惠码";
		$objProps = $objPHPExcel->getProperties();
		$objProps->setCreator("www.yunke.com");
		$objProps->setLastModifiedBy("www.yunke.com");
		$objProps->setTitle("Office XLS Test Document");
		$objProps->setSubject("Office XLS Test Document, yunke");
		$objProps->setDescription("Test document, generated by PHPExcel.");
		$objProps->setKeywords("office excel PHPExcel");
		$objProps->setCategory("报表");
		$objPHPExcel->setActiveSheetIndex(0);
		$objActiveSheet = $objPHPExcel->getActiveSheet();
		$objActiveSheet->setTitle($fileName);
		//设置表头
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objActiveSheet->getStyle('A1')->getFont()->setBold(true);
		$objActiveSheet->getStyle('B1')->getFont()->setBold(true);
		$objActiveSheet->getStyle('C1')->getFont()->setBold(true);
		$objActiveSheet->getStyle('D1')->getFont()->setBold(true);
		$objActiveSheet->getStyle('E1')->getFont()->setBold(true);

		$objActiveSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objActiveSheet->setCellValue('A1', '优惠码');
		$objActiveSheet->setCellValue('B1', '使用次数');
		$objActiveSheet->setCellValue('C1', '绑定用户');
		$objActiveSheet->setCellValue('D1', '状态');
		$objActiveSheet->setCellValue('E1', '操作');
		// 防止科学计数
		$objActiveSheet->getStyle('B1')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		//填充表格内容
		$j = 2;
		foreach ($result->data->items as $k => $v) {
			$objActiveSheet->setCellValue('A' . $j, $v->code);
			$objActiveSheet->setCellValue('B' . $j, $v->uselimit);
			$objActiveSheet->setCellValue('C' . $j, $v->binduser);
			$objActiveSheet->setCellValue('D' . $j, $v->usestatus);
			$objActiveSheet->setCellValue('E' . $j, $v->operation);
			$objActiveSheet->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActiveSheet->getStyle('B' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objActiveSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActiveSheet->getStyle('D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActiveSheet->getStyle('E' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$j++;
		}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		if(empty($fileName)){
			$ftime = time();
			$outputFileName = $ftime . ".xls";
		}else{
			$outputFileName = $fileName . ".xls";
		}
		//加此防乱码
		ob_clean();
		@header("Pragma: public");
		@header("Expires: 0");
		@header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
		@header("Content-Type:charset=UTF-8");
		@header("Content-Type:application/force-download");
		@header("Content-Type:application/vnd.ms-execl");
		@header("Content-Type:application/octet-stream");
		@header("Content-Type:application/download");
		@header('Content-Disposition:attachment;filename="' . $outputFileName . '"');
		@header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');

	}

	/**
 	 * 优惠吗 启用禁用
 	 */

 	public function pageCodeDisableAndEnable(){
 		if(empty($_GET['code'])) return interface_func::setMsg(1000);
 		$code = htmlspecialchars(trim($_GET['code']));
 		if(empty($code)) return interface_func::setMsg(1000);
 		if(!isset($_GET['status'])) return interface_func::setMsg(1000);
 		$status = $_GET['status'];
 		if(!in_array($status,[0,-1])) return interface_func::setMsg(1000);
 		$res = course_discount_api::CodeDisableAndEnable($this->orgOwner,$code,$status);
 		echo  json_encode(['code'=>$res->code,'message'=>$res->message],JSON_UNESCAPED_UNICODE);
 	}

	/**
 	 * 机构所有者下全部课程
 	 */
 	public function pageCourseListInfo($inPath){
 		$page = isset($_POST['page']) ? $_POST['page'] : 1;
 		$length = 50;
 		$data = array();
 		$data["type"] = array(1, 2, 3);//全部课程
 		//$data["admin_status"] = "1";
 		$data["shelf"] = 1;
 		$data["create_time"] = "desc";
 		$courseRet = course_api::getCourselistByOid($page, $length, $this->orgOwner, $data);
 		$courseIdArr = array();
 		if(!empty($_POST['courseIds'])){
 			$courseIdArr = explode(',', $_POST['courseIds']);
 		}
 		if(!empty($courseRet->data)) {
 			foreach ($courseRet->data as $val) {
 				if (!empty($courseIdArr) && in_array($val->course_id, $courseIdArr)) {
 					$courseRet->checkedCourseids[$val->course_id] = $val;
 				}
 			}
			if (!empty($courseRet->checkedCourseids)) $courseRet->checkedCourseids = array_values($courseRet->checkedCourseids);
			return $courseRet;
 		}
	}

	/**
	 * @
	 * @ 课程搜索
	 */
	public function pageSearchCourseAjax($inPath)
	{
		$keyword = !empty(trim($_POST['keyword'])) ? trim($_POST['keyword']) : '';
		$f_array = array(
				'title',
				'course_id',
				'fee_type',
				'price',
				'thumb_big',
		);

		$q_array = array(
				'admin_status' => 1,
				'user_id' => $this->orgOwner,
		);
		if (!empty($keyword)) {
			$q_array['title'] = $keyword;
		}
		$ob_array = array(
				'create_time' => 'desc',
		);
		$seek_arr = array(
				"f" => $f_array,
				"q" => $q_array,
				"ob" => $ob_array,
				"p" => 1,
				"pl" => 50,
		);
		$ret_seek = seek_api::seekcourse($seek_arr);
		return json_encode($ret_seek);
	}

	/**
 	 * 机构下所有老师列表
 	 */
 	public function pageOrgTeacher(){
 		$page     = !empty($_POST['page']) ? (int)$_POST['page'] : 1;
 		$length   = !empty($_POST['length'])? (int)$_POST['length'] : 10;
 		$keywords = !empty($_POST['keywords']) ? trim($_POST['keywords']) : '';
 		$data 	  = user_api::getorgTeacherData($this->orgInfo->oid, $page, $length, $keywords);
 		$teacherIdArr = array();
 		if(!empty($_POST['teacherIds'])){
 			$teacherIdArr = explode(',', $_POST['teacherIds']);
 		}

 		if(!empty($data['teachers'])){
 			foreach($data['teachers'] as $val){
 				if(!empty($teacherIdArr) && in_array($val['teacherId'], $teacherIdArr)){
 					$data['checkedTeachers'][$val['teacherId']] = $val;
 				}
 			}
 			if(!empty($data['checkedTeachers'])) $data['checkedTeachers'] = array_values($data['checkedTeachers']);

 			return interface_func::setData($data);
 		}

 		return $this->setMsg(3002, '获取数据为空');
 	}

	//机构下的分类信息

 	public function pageGetCate(){
 		$cateId = isset($_POST['cateId']) ? (int)$_POST['cateId'] : 0;
 		if(empty($cateId)){
 			$orgProfile = user_organization::orgAboutProfileInfo($this->orgOwner);
 			$firstCate  = !empty($orgProfile->scopes) ? $orgProfile->scopes : 1;
 			$cateReg = course_api::getCateByCidStr($firstCate);
 		}else{
			$cateReg = course_api::getNodeCate($cateId);
		}
 		if(empty($cateReg)) return $this->setMsg(3002, '获取数据为空');
 		foreach($cateReg as $val){
 			$data['cateList'][] = [
 					'cateId' => $val->pk_cate,
 					'name'   => SLanguage::tr($val->name_display,'course.list')
 			];
 		}

 		return interface_func::setData($data);
 	}

//	//机构下的科目信息
// 	public function pageGetAttr(){
// 		$cateId = !empty($_POST['cateId']) ? (int)$_POST['cateId'] : 0;
// 		$attrReg = course_api::getAttrAndValueBycateId($cateId);
// 		if(empty($attrReg)) return $this->setMsg(3002, '获取数据为空');
// 		$data = array();
// 		foreach($attrReg as $val){
// 			if(!empty($val->attr_value)){
// 				foreach($val->attr_value as $v){
// 					$data[] = [
// 							'attrId' => $v->attr_value_id,
// 							'name'   => $v->value_name
// 					];
// 				}
// 			}
// 		}
//
// 		return interface_func::setData(['attr'=>$data]);
// 	}
//
//	//获取科目下的课程类型直播 录播 线下 course_type
// 	public function pageGetCourseType(){
// 		$uid = $this->user['uid'];
// 		$attrId = !empty($_POST['attrId']) ? (int)$_POST['attrId'] : 0;
// 		$attrReg = course_api::GetCourseType($attrId,$uid);
// 		$course_type= array();
// 		if($attrReg->code==0){
// 			foreach($attrReg->result as $kk=>$item){
// 				if($item==1){
// 					$course_type[$item]['course_type'] = $item;
// 					$course_type[$item]['name'] = '直播课';
// 				}elseif($item==2){
// 					$course_type[$item]['course_type'] = $item;
// 					$course_type[$item]['name'] = '录播课';
// 				}elseif($item==3){
// 					$course_type[$item]['course_type'] = $item;
// 					$course_type[$item]['name'] = '线下课';
// 				}
// 			}
// 			return interface_func::setData(['course_type'=>$course_type]);
// 		}
//
// 		return $this->setMsg(3002, '获取数据为空');
// 	}

	private function setMsg($code = 1000, $msg='', $id=0){
 		$result = [
 				'code'    => $code,
 				'message' => SLanguage::tr($msg, 'site.course')
 		];

 		return json_encode($result, JSON_UNESCAPED_UNICODE);

 	}

// 创建优惠劵
	public function pageSetUp($inPath){
		return $this->render("org/discount.setup.html");
	}
// 编辑优惠劵
	public function pageEdit($inPath){
		return $this->render("org/discount.edit.html");
	}
// 优惠劵列表
	public function pageListNew($inPath){
		return $this->render("user/discount.list.new.html");
	}
	// 优惠劵列表
	public function pageInfo($inPath){
		return $this->render("user/discount.info.html");
	}
	// 优化码使用列表
	public function pageUsedList($inPath){
		return $this->render("user/discount.usedlist.html");
	}

	public function pageCourseSelect($inPath){
		return $this->render("layer/course.select.html");
	}
	public function pageCateSelect($inPath){
		return $this->render("layer/cate.select.html");
	}
}
?>
