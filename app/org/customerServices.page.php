<?php
/*
 *机构-客服设置 电话,qq,二维码
 */
class org_customerServices extends STpl{
	
	private $orgOwner;
	private $orgInfo;
	private $domain;
	function __construct(){
        //如果没有登陆到登陆界面
        $user = user_api::loginedUser();
        if(empty($user)){
            $this->redirect("/site.main.login");
        }
		$domainConf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domainConf->domain;
        $org=user_organization::subdomain();
        if(!empty($org)){
            $this->orgOwner=$org->userId;
        }else{
			
            header('Location: https://www.'.$this->domain);
        }
        $this->orgInfo=user_organization::getOrgByOwner($this->orgOwner);
		$isAdmin=user_api::isAdmin($this->orgOwner,$user['uid']);
		$orgInfo=user_organization::getOrgByOwner($user['uid']);
        if(($isAdmin ===false) &&($this->orgOwner != $orgInfo->user_owner_id)){
            return $this->redirect("/site.main.entry");
        }
		
	}
	public function pagelist(){
        $orgId = !empty($this->orgInfo->oid) ? $this->orgInfo->oid : '';
		$customer = org_api::customerServicesQqList($orgId);//获取维码及电话
		$customerData = !empty($customer->data) ? $customer->data : '';
		if(!empty($customerData->weima)){
			foreach($customerData->weima as $k=>$v){
				$v->type_value = utility_cdn::file($v->type_value);
			}
		}
                $qqdata="";
                $qqservice=org_api::getCusRelationList(array('orgid'=>$orgId));
                if(!empty($qqservice->data)){
                    $qqdata=$qqservice->data;
                }//print_r($qqdata);
        $this->assign('customerData',$customerData);
        $this->assign("qqdata",$qqdata);
		return $this->render("org/customerService.html");
	}
	public function pageqqTest(){
		//机构客服设置右侧浮窗
        $orgId = !empty($this->orgInfo->oid) ? $this->orgInfo->oid : 0;
        $customer = org_api::customerServicesQqList($orgId);
		$customerData = !empty($customer->data) ? $customer->data : '';
		if(!empty($customerData->weima)){
			foreach($customerData->weima as $k=>$v){
				$v->type_value = utility_cdn::file($v->type_value);
			}
		}
        $qqArr = array();
		$qqInfo = array();
        if(!empty($customerData->qq)){
			foreach($customerData->qq as $k=>$v){
				$qqArr[] = $v->type_value;
					
			}
            $qqStr = implode(":",$qqArr).":";
		}
		$url = "http://webpresence.qq.com/getonline?Type=1&{$qqStr}";
		$str = file_get_contents($url);
		$arrResponse = explode(";",rtrim($str,";"));
		$dataStatus = array();
		foreach($arrResponse as $k=>$v){
			$status = substr(strstr($v,"="),1);
			$dataStatus[$k] = $status;
		}
		if(!empty($customerData->qq)){
			foreach($customerData->qq as $k=>$v){
				$v->qqStatus= $dataStatus[$k];	
			}
		}
        $this->assign('customerData',$customerData->qq);
	}
	public function pageaddOrgCustomerInfo(){
		$user = user_api::loginedUser();
		$data = array();
        $result = new stdclass;
		$data['type'] = !empty($_POST['type']) ? $_POST['type'] : '';
		$data['type_value'] = !empty($_POST['type_value']) ? $_POST['type_value'] : '';
		$data['type_name'] = !empty($_POST['type_name']) ? $_POST['type_name'] : '';
		$data['type_code'] = !empty($_POST['type_code']) ? $_POST['type_code'] : '';
		$data['fk_user_owner'] = !empty($this->orgInfo->oid) ? $this->orgInfo->oid : '';
		$pos = substr($data['type_code'],strpos($data['type_code'], "http"));
		$data['type_code'] =trim($pos);
        if($_POST['type']=='2'){
            $type_name=trim($_POST['type_name']);
            if(empty($type_name)){
                $result->error="客服名称不能为空~";
                $result->field="tel";
                return $result;
            }
            $strNum=  utility_tool::stringNum($type_name);
            if($strNum>12){
                $result->error="长度不能超过6个汉字或12个字符~";
                $result->field="tel";
                return $result;
            }
            $type_value=trim($_POST['type_value']);
            if(empty($type_value)){
                $result->error="电话号码不能为空~";
                $result->field="tel";
                return $result;
            }
            if(!preg_match('/^((\+?86-?)?(18|15|13)[0-9]{9})|((\d{1}|0[3-9]\d{2})-\d{7,9})|(400(-\d{3,4}){2})$/', $type_value)){
                $result->error="电话号码格式不正确~";
                $result->field="tel";
                return $result;
            }    
        }
        if(($_POST['type']=='3')&&!empty($_POST['type_value'])){
            if(!preg_match('/^[1-9][0-9]{4,}$/', $_POST['type_value'])){
                $result->error="QQ号格式不正确~";
                $result->field="qq";
                return $result;
            }    
        }
        if(($_POST['type']=='4')&&!empty($_POST['type_value'])){
            if(!preg_match('/^[1-9][0-9]{4,}$/', $_POST['type_value'])){
                $result->error="QQ群号格式不正确~";
                $result->field="qqun";
                return $result;
            } 
        }
		$custom = org_api::addOrgCustomerInfo($data);
		$result = array();
		if($custom->result->code==100){
			$result = array("code"=>1,"msg"=>"add success");
		}else{
			$result = array("code"=>-1,"msg"=>"failed");
		}
		echo json_encode($result);
	}
	public function pageeditOrgCustomInfo(){
		$user = user_api::loginedUser();
		$data = array();
		$tid = !empty($_POST['tid']) ? (int)$_POST['tid'] : '';
		$data['type'] = !empty($_POST['type']) ? $_POST['type'] : '';
		$data['type_value'] = !empty($_POST['value']) ? $_POST['value'] : '';
		$data['fk_user_owner'] = !empty($this->orgInfo->oid) ? $this->orgInfo->oid : '';
		$custom = org_api::updateOrgCustomerInfo($data,$tid);
		return $this->render("org/info.show.html");
	}
	public function pageupdateOrgCustomInfo(){
		$user = user_api::loginedUser();
		$data = array();
        $result = new stdclass;
		$tid = !empty($_POST['tid']) ? (int)$_POST['tid'] : '';
		$data['type'] = !empty($_POST['type']) ? $_POST['type'] : '';
		$data['type_value'] = !empty($_POST['type_value']) ? $_POST['type_value'] : '';
		$data['type_name'] = !empty($_POST['type_name']) ? $_POST['type_name'] : '';
		$data['type_code'] = !empty($_POST['type_code']) ? $_POST['type_code'] : '';
		$data['fk_user_owner'] = !empty($this->orgInfo->oid) ? $this->orgInfo->oid : '';
		$pos = substr($data['type_code'],strpos($data['type_code'], "http"));
		$data['type_code'] =trim($pos);
        if($_POST['type']=='2'){
            $type_name=trim($_POST['type_name']);
            if(empty($type_name)){
                $result->error="客服名称不能为空~";
                $result->field="tel";
                return $result;
            }
            $strNum=  utility_tool::stringNum($type_name);
            if($strNum>12){
                $result->error="长度不能超过6个汉字或12个字符~";
                $result->field="tel";
                return $result;
            }
            $type_value=trim($_POST['type_value']);
            if(empty($type_value)){
                $result->error="电话号码不能为空~";
                $result->field="tel";
                return $result;
            }
            if(!preg_match('/^((\+?86-?)?(18|15|13)[0-9]{9})|((\d{1}|0[3-9]\d{2})-\d{7,9})|(400(-\d{3,4}){2})$/', $type_value)){
                $result->error="电话号码格式不正确~";
                $result->field="tel";
                return $result;
            }    
        }
        if(($_POST['type']=='3')&&!empty($_POST['type_value'])){
            if(!preg_match('/^[1-9][0-9]{4,}$/', $_POST['type_value'])){
                $result->error="QQ号格式不正确~";
                $result->field="qq";
                return $result;
            }   
        }
        if(($_POST['type']=='4')&&!empty($_POST['type_value'])){
            if(!preg_match('/^[1-9][0-9]{4,}$/', $_POST['type_value'])){
                $result->error="QQ群号格式不正确~";
                $result->field="qqun";
                return $result;
            } 
        }
		$custom = org_api::updateOrgCustomerInfo($tid,$data);
		$result = array();
		if($custom->result->code==100){
			$result = array("code"=>1,"msg"=>"success");
		}else{
			$result = array("code"=>-1,"msg"=>"failed");
		}
		echo json_encode($result);
	}
	public function pagedelOrgCustomerInfo(){
		$user = user_api::loginedUser();
		$data = array();
		$data['pk_customer'] = !empty($_POST['tid']) ? (int)$_POST['tid'] : '';
		$cid = !empty($_POST['c_id']) ? $_POST['c_id'] : '';
		$data['fk_user_owner'] = !empty($this->orgInfo->oid) ? $this->orgInfo->oid : '';
		$data['type'] = !empty($_POST['type']) ? $_POST['type'] : '';
		$info = org_api::getOrgCustomerInfo($data);
		if($info->result->code==100){
			$custom = org_api::delOrgCustomerInfo($data['pk_customer']);
			if($custom->result->code==100){
				$result = array("code"=>1,"msg"=>"success");
			}
		}else{
				$result = array("code"=>-1,"msg"=>" delete is failed");
		}
		echo json_encode($result);
	}
	
	public function pagetwoCode($inPath){
		$user = user_api::loginedUser();
		if(!empty($_POST['big']) && empty($_POST['w'])){
			$ret_up = $this->updateThumbs();
			if($ret_up){
				return array('ok'=>1);
			}else{
				return array('error'=>'保存失败!');
			}
			exit;	
		}
		$path = ROOT_WWW."/upload/tmp";
		$filename = $path."/".$user['uid'].".jpg";
		$filename_dst = $path."/".$user['uid'].".dst.png";
		if(!is_file($filename)){
			return array("error"=>"请上传头像");
		}
		list($width, $height, $type, $attr) = getimagesize($filename);
		if(!$width || !$height){
			return array("error"=>"不是有效果的图片");
		}
		$targ_w = $_REQUEST['w'];
		$targ_h = $_REQUEST['h'];
		switch($type){
			case 1: $img_r = imagecreatefromgif($filename);break;
			case 2: $img_r = imagecreatefromjpeg($filename);break;
			case 3: $img_r = imagecreatefrompng($filename);break;
			default:
				return array("error"=>"不是有效果的图片");
		}	
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
		$bg = imagecolorallocatealpha($dst_r, 0 , 0 , 0 , 127);
		imagealphablending($dst_r,false);
		imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_REQUEST['w'],$_REQUEST['h']);
		imagesavealpha($dst_r,true);

		$r = imagepng($dst_r, $filename_dst);
		if($r){
			$thumbs = user_thumb::genByFile($filename_dst);
			$data = array();
			$data['pk_customer'] = $cid = !empty($_POST['c_id']) ? $_POST['c_id'] : '';
			$data['fk_user_owner'] = !empty($this->orgInfo->oid) ? $this->orgInfo->oid : '';
			$data['type'] = !empty($_POST['type']) ? $_POST['type'] : '';
			$info = org_api::getOrgCustomerInfo($data);
			$data['type_value'] = !empty($thumbs['large']) ? $thumbs['large'] : '';
			$data['type_name'] = !empty($_POST['type_name']) ? $_POST['type_name'] : '';
			$data['type_code'] = !empty($_POST['type_code']) ? $_POST['type_code'] : '';
			
			if($info->result->code=='-1'){
				$custom = org_api::addOrgCustomerInfo($data);
			}elseif($info->result->code=='100'){
				$custom = org_api::updateOrgCustomerInfo($cid,$data);
			}
			if($custom->result->code=='100'){
				return array("ok"=>1);
			}
		}	
		return array("error"=>"失败，请重试");

	}
	
}
