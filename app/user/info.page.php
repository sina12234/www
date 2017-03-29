<?php
class user_info extends STpl{
	var $user;
	function __construct(){
		//如果没有登陆到登陆界面
		$this->user = user_api::loginedUser();
		if(empty($this->user)){
			$this->redirect("/site.main.login");
		}
	}
	public function pageBase($inPath){
		$userinfo = user_api::getUser($this->user['uid']);
		$this->assign("userinfo",$userinfo);
		if(!empty($_POST)){
			$r = user_api::updateBase($this->user['uid'],
				@$_POST['name'],
				@$_POST['gender'],
				@$_POST['birthday'],
				@$_POST['real_name'],
				@$_POST['address'],
				@$_POST['desc'],
				@$_POST['zip_code'],
				$ret);
			$error = "";
			if($r===true){
				$error="修改成功";
				user_api::loginByUid($this->user['uid']);
                $this->redirect('/user.info.base');
			}else{
				$error="修改失败";
			}
			$this->assign("error",$error);
		}
		return $this->render("user/info.base.html");
	}
	public function pagePic($inPath){
		return $this->render("user/info.pic.html");
	}
	public function pagePicSelect($inPath){
		$select = SConfig::getConfig(ROOT_CONFIG."/user.conf","thumb_select");
		$pics = array();
		if(!empty($select)){
			if(is_array($select)){
				$pics = $select;
			}else{
				$pics = array($select);
			}
		}
		if(!empty($_POST['big'])){
			foreach($pics as $pic){
				if($pic->big==$_POST['big']){

					//TODO 删除以前的头像图片
					$ret_up = user_api::updateThumbs($this->user['uid'],
						array("large"=>$pic->big,"medium"=>$pic->med,"small"=>$pic->small),$_r);
					//更新登录用户的头像
					user_api::loginByUid($this->user['uid']);
					break;
					//修改用户头像
				}
			}
		}
		$this->assign("pics",$pics);
		return $this->render("user/info.pic.select.html");
	}
	public function pageStudentInfo($inPath){
		if(!empty($_POST)){
			$r = user_api::setStudentProfile($this->user['uid'],$_POST);
			if($r){
				$this->assign("error","修改成功");
			}
		}
		//$uid = array();
		//$uid['uid'] = $this->user['uid'];
		//获取报名信息
		//$arr_regis = $uid;
		//$list_reg_info = course_api::listRegistration($arr_regis);
		//$count_regis = count($list_reg_info);
		////获取喜欢课程信息
		//$arr_fav = $uid;
		//$list_fav_info = user_api::listfav($arr_fav);
		//$count_fav = count($list_fav_info);
		//print_r($count_fav);
		$student = user_api::getStudentProfile($this->user['uid']);
		$this->assign("grades",course_grade::$data);
		$level0 = region_api::listRegion(0);
		$this->assign("student",$student);
		$this->assign("level0",$level0);
		return $this->render("user/student.info.html");
	}
	public function pagePicAjax($inPath){
		$path = ROOT_WWW."/upload/tmp";
		$filename = $path."/".$this->user['uid'].".org.jpg";
		$filename_dst = $path."/".$this->user['uid'].".dst.png";
		if(!is_file($filename)){
			return array("error"=>"请上传头像");
		}
		list($width, $height, $type, $attr) = getimagesize($filename);
		if(!$width || !$height){
			return array("error"=>"不是有效果的图片");
		}
		//print_r($_REQUEST);
		$targ_w = $_REQUEST['w'];
		$targ_h = $_REQUEST['h'];

		//$src = 'demo_files/flowers.jpg';
		switch($type){
		case 1: $img_r = imagecreatefromgif($filename);break;
		case 2: $img_r = imagecreatefromjpeg($filename);break;
		case 3:
			$img_r = imagecreatefrompng($filename);
			//imagesavealpha($img_r,true);
		break;
		default:
			return array("error"=>"不是有效果的图片");
		}
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

		$bg = imagecolorallocatealpha($dst_r, 0 , 0 , 0 , 127);
		imagealphablending($dst_r,false);

		imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
			$targ_w,$targ_h,$_REQUEST['w'],$_REQUEST['h']);
		imagesavealpha($dst_r,true);

		$r = imagepng($dst_r, $filename_dst);
		if($r){
			$thumbs = user_thumb::genByFile($filename_dst);
			if($thumbs){
				//TODO 删除以前的头像图片
				$ret_up = user_api::updateThumbs($this->user['uid'],$thumbs,$_r);
				//更新登录用户的头像
				user_api::loginByUid($this->user['uid']);
			}
			return array("ok"=>1);
		}
		return array("error"=>"失败，请重试");
	}
	public function pagePicLOGOAjax($inPath){
		$orgOwnerid = 153;
		if($orgOwnerid != $this->user['uid']){
			return false;
		}
		$path = ROOT_WWW."/upload/tmp";
		$filename = $path."/".$orgOwnerid.".org.jpg";
		$filename_dst = $path."/".$orgOwnerid.".dst.png";
		if(!is_file($filename)){
			return array("error"=>"请上传LOGO");
		}
		list($width, $height, $type, $attr) = getimagesize($filename);
		if(!$width || !$height){
			return array("error"=>"不是有效图片");
		}
		//print_r($_REQUEST);
		$targ_w = $_REQUEST['w'];
		$targ_h = $_REQUEST['h'];

		//$src = 'demo_files/flowers.jpg';
		switch($type){
		case 1: $img_r = imagecreatefromgif($filename);break;
		case 2: $img_r = imagecreatefromjpeg($filename);break;
		case 3:
			$img_r = imagecreatefrompng($filename);
			//imagesavealpha($img_r,true);
		break;
		default:
			return array("error"=>"不是有效果的图片");
		}
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

		$bg = imagecolorallocatealpha($dst_r, 0 , 0 , 0 , 127);
		imagealphablending($dst_r,false);

		imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
			$targ_w,$targ_h,$_REQUEST['w'],$_REQUEST['h']);
		imagesavealpha($dst_r,true);

		$r = imagepng($dst_r, $filename_dst);
		if($r){
			$thumbs = user_thumb::genByFileLOGO($filename_dst);
			if($thumbs){
				//TODO 删除以前的LOGO图片
				$ret_up = user_organization::updateLOGO($this->user['uid'],$thumbs,$_r);
				//更新登录用户的头像

			//	user_api::loginByUid($this->user['uid']);
			}
			return array("ok"=>1);
		}
		return array("error"=>"失败，请重试");
	}
}
