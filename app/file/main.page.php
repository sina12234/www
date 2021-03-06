<?php
class file_main extends STpl{
	var $user;
	function __construct(){
		//如果没有登陆到登陆界面
		$this->user = user_api::loginedUser();
		if(empty($this->user)){
			die("ERROR,Not Login");
		}
	}
	public function pageBrowseData($inPath){
		if(empty($_REQUEST['type'])){
			$type="image";
		}else{
			$type=$_REQUEST['type'];
		}
		$r = utility_file::listfile(user_api::getLoginUid(),$type);
		//$re = ['menu'=>'<ul><li><span title="'.$type.'">'.$type.'</span></li></ul>', 'imgs'=>''];
		$re = ['menu'=>'', 'imgs'=>''];
		foreach($r as $file){
			$re['imgs'].='<span><img src="'.utility_cdn::filecdn().'/'.$file->fid.'" alt="'. $file->name.'" height="80" />'. $file->name.'</span>';
		}
		return $re;
	}
	public function pageBrowse($inPath){
		if(empty($_REQUEST['type'])){
			$type="image";
		}else{
			$type=$_REQUEST['type'];
		}
		$this->assign("type",$type);
		return $this->render("file/imgbrowse.html");
	}
	/**课程图片用**/
	//大图：480:270，小图：240:135
	//不在用
/*
	public function pageUploadCoursePic($inPath){
		//判断大小，如果
		$path = ROOT_WWW."/upload/tmp";
		if(!is_dir($path)){
			mkdir($path,0777,true);
		}
		if(empty($inPath[3]))return;
		$course_id = $inPath[3];
		$uid = $this->user['uid'];
		$course_info = course_api::getCourseOne($course_id);
		if(empty($course_info) || $course_info->user_id != $uid){
			return array();
		}
		$ret=array();

		if(!empty($_FILES['file']['tmp_name'])){
			list($width, $height, $type, $attr) = getimagesize($_FILES['file']['tmp_name']);
			//大图：480:270，小图：240:135
			if($width<480 || $height<270){
				$ret['error']="文件大小不符合标准";
				return $ret;
			}
			if($width*9 != $height*16){//16:9
				$ret['error']="图片比例不是16：9的宽屏比例";
				return $ret;
			}


			//小图
			$thumbnail = new SThumbnail($_FILES['file']['tmp_name'], 100);
			$thumbnail->setMaxSize(240, 135);
			$filename_128 = utility_file::tempname("thumb");
			if($thumbnail->genFile($filename_128)===false){
				return false;
			};

			//中图
			$thumbnail = new SThumbnail($_FILES['file']['tmp_name'], 100);
			$thumbnail->setMaxSize(480, 270);
			$filename_280 = utility_file::tempname("thumb");
			if($thumbnail->genFile($filename_280)===false){
				return false;
			};

			//大图就是原图
			$file = utility_file::instance();
			$r1 = $file->upload($filename_128,user_api::getLoginUid(),"image",$_FILES['file']['name']);
			$r2 = $file->upload($filename_280,user_api::getLoginUid(),"image",$_FILES['file']['name']);
			$r3 = $file->upload($_FILES['file']['tmp_name'],user_api::getLoginUid(),"image",$_FILES['file']['name']);

			if(!empty($r1) && !empty($r2) && !empty($r3)){
				$ret['small'] = $r1->fid;
				$ret['medium'] = $r2->fid;
				$ret['large']=$r3->fid;
			}
			unlink($filename_128);
			unlink($filename_280);
			return $ret;
		}
		return array();
	}
 */
	/**上传课程图片，临时用**/
	public function pageUploadCoursePicV2($inPath){
		$path = ROOT_WWW."/upload/tmp";
		if(!is_dir($path)){
			mkdir($path,0777,true);
		}
		if(empty($inPath[3])){
			return false;
		}
		$uid = $inPath[3];
		$filename = "course.$uid.org.jpg";
		$data = array();
		if(!empty($_FILES['file']['tmp_name'])){
			$ret = move_uploaded_file($_FILES['file']['tmp_name'],$path."/".$filename);
			if($ret){
				list($width, $height, $type, $attr) = getimagesize($path."/".$filename);
				//大图：480:270，小图：240:135
				if($width<480 || $height<270){
					$data['error']="文件大小不符合标准";
					@unlink($path."/".$filename);
				}else{
					$data['file']="/upload/tmp/".$filename;
				}
			}else{
				$data['error']="上传失败-3";
			}
		}else{
				$data['error']="上传失败-2";
		}
		return $data;
	}
	/**手机APP自定义幻灯片**/
    public function pageUploadBannerApp($inPath){
        $path = ROOT_WWW."/upload/tmp";
        if(!is_dir($path)){
            mkdir($path,0777,true);
        }
        if(!empty($_FILES['file']['tmp_name'])){
			list($width, $height, $type, $attr) = getimagesize($_FILES['file']['tmp_name']);
			if($inPath[3]==1 &&($width!=690 || $height!=200)){
		        $data['error']="图片尺寸不符合标准";
                return $data;
			}
			if($inPath[3]==2 &&($width!=960 || $height!=290)){
		        $data['error']="图片尺寸不符合标准";
                return $data;
			}
            $file= utility_file::upload($_FILES['file']['tmp_name'],user_api::getLoginUid(),'image',$_FILES['file']['name']);
            $ret=array();
            if($file){
			    $ret['src']=utility_cdn::filecdn().'/'.$file->fid;
                $ret['fid']=$file->fid;
                return $ret;
            }else{
                return "";
            }
        }
        return "";
    }
	/**上传头像文件，临时用**/
	public function pageUploadPic($inPath){
		$path = ROOT_WWW."/upload/tmp";
		if(!is_dir($path)){
			mkdir($path,0777,true);
        }
		$filename = $this->user['uid'].".jpg";
		if(!empty($_FILES['file']['tmp_name'])){
			$ret = move_uploaded_file($_FILES['file']['tmp_name'],$path."/".$filename);
			if($ret){
				list($src_w,$src_h,$type) = getimagesize($path."/".$filename);
                //缩放
                switch($type){
                    case 1: $source = imagecreatefromgif($path."/".$filename);
                    break;
                    case 2: $source = imagecreatefromjpeg($path."/".$filename);
                    break;
                    case 3: $source = imagecreatefrompng($path."/".$filename);
                    break;
                    default:
                    return false;
                }
                $width=300;
                $height=300;
                //图片宽高比
                $ratio_orig = $src_w/$src_h;
                if ($width/$height > $ratio_orig) {
                    $width = $height*$ratio_orig;
                } else {
                    $height = $width/$ratio_orig;
                }
                $target = @imagecreatetruecolor($width, $height);
                @imagecopyresampled($target, $source, 0, 0, 0, 0, $width, $height, $src_w, $src_h);
                // 保存
                imagejpeg($target, $path."/".$filename,100);
                //imagedestroy($target);


				return "/upload/tmp/".$filename;
			}
		}
		return "";
	}

	/**上传LOGO图片**/
	public function pageUploadLoGoPic($inPath){
		$path = ROOT_WWW."/upload/tmp";
		if(!is_dir($path)){
			mkdir($path,0777,true);
		}
		$uid = $this->user['uid'];
		if($_FILES['file']['type']){
			$image 		= explode("/",$_FILES['file']['type']);
			$filename   = $uid.".org.".$image[1];
		}
		$data = array();
		list($src_w, $src_h, $type, $attr) = getimagesize($_FILES['file']['tmp_name']);
		if($src_h >48){
			$data['error'] = "高度必须小于等于48px";
			return $data;
		}
		if($src_h >180){
			$data['error'] = "宽度必须小于等于180px";
			return $data;
		}
		$ret = move_uploaded_file($_FILES['file']['tmp_name'],$path."/".$filename);
		/*if($src_w >392 || $src_h>212){
			switch($type){
				case 1: $source = imagecreatefromgif($path."/".$filename);
				break;
				case 2: $source = imagecreatefromjpeg($path."/".$filename);
				break;
				case 3: $source = imagecreatefrompng($path."/".$filename);
				break;
				default:
				return false;
			}
			$width=392;
			$height=212;
			//图片宽高比
			$ratio_orig = $src_w/$src_h;
			if ($width/$height > $ratio_orig) {
				$width = $height*$ratio_orig;
			} else {
				$height = $width/$ratio_orig;
			}
			$target = imagecreatetruecolor($width, $height); 
			//上色 
			$color=imagecolorallocate($target,255,255,255); 
			//设置透明 
			imagecolortransparent($target,$color); 
			imagefill($target,0,0,$color); 
			@imagecopyresampled($target, $source, 0, 0, 0, 0, $width, $height, $src_w, $src_h);
			// 保存
			imagejpeg($target, $path."/".$filename,180);
		
		}*/
		$data['pic'] = "/upload/tmp/".$filename;
		return $data;
	}
    /**机构上传首页轮播图片**/
    public function pageUploadOrgSlide($inPath){
        $path = ROOT_WWW."/upload/tmp";
        if(!is_dir($path)){
            mkdir($path,0777,true);
        }
        //$filename='orgpic.'.user_api::getLoginUid().'.jpg';
        if(!empty($_FILES['file']['tmp_name'])){
			list($width, $height, $type, $attr) = getimagesize($_FILES['file']['tmp_name']);
			if($width!=890 || $height!=360){
		        $data['error']="图片尺寸不符合标准";
                return $data;
			}
            //$file = move_uploaded_file($_FILES['file']['tmp_name'],$path."/".$filename);
            $file= utility_file::upload($_FILES['file']['tmp_name'],user_api::getLoginUid(),'image',$_FILES['file']['name']);
            $ret=array();
            if($file){
			    $ret['src']=utility_cdn::filecdn().'/'.$file->fid;
                $ret['fid']=$file->fid;
                return $ret;
                //return "/upload/tmp/".$filename;
            }else{
                return "";
            }
        }
        return "";
    }
    /**机构更换LOGO**/
    public function pageUploadOrgLogo($inPath){
        $path = ROOT_WWW."/upload/tmp";
        if(!is_dir($path)){
            mkdir($path,0777,true);
        }
        if(!empty($_FILES['file']['tmp_name'])){
			list($width, $height, $type, $attr) = getimagesize($_FILES['file']['tmp_name']);
			if($width>180 || $height!=48){
		        $data['error']="图片尺寸不符合标准";
                return $data;
			}
			//小图
			$thumbnail = new SThumbnail($_FILES['file']['tmp_name'], 100);
			$thumbnail->setMaxSize(240, 135);
			$filename_128 = utility_file::tempname("thumb");
			if($thumbnail->genFile($filename_128)===false){
				return false;
			};

			//中图
			$thumbnail = new SThumbnail($_FILES['file']['tmp_name'], 100);
			$thumbnail->setMaxSize(480, 270);
			$filename_280 = utility_file::tempname("thumb");
			if($thumbnail->genFile($filename_280)===false){
				return false;
			};

			//大图就是原图
			$file = utility_file::instance();
			$r1 = $file->upload($filename_128,user_api::getLoginUid(),"image",$_FILES['file']['name']);
			$r2 = $file->upload($filename_280,user_api::getLoginUid(),"image",$_FILES['file']['name']);
			$r3 = $file->upload($_FILES['file']['tmp_name'],user_api::getLoginUid(),"image",$_FILES['file']['name']);

            $ret=array();
			if(!empty($r1) && !empty($r2) && !empty($r3)){
				$ret['thumb_small'] = $r1->fid;
				$ret['thumb_med'] = $r2->fid;
				$ret['thumb_big']=$r3->fid;
			    $ret['src']=utility_cdn::filecdn().'/'.$r3->fid;
			}
			unlink($filename_128);
			unlink($filename_280);
			return $ret;
        }
        return "";
    }
	/**上传课程图片，临时用**/
	public function pageUploadExamPic($inPath){
		$path = ROOT_WWW."/upload/tmp";
		if(!is_dir($path)){
			mkdir($path,0777,true);
		}
		if(empty($inPath[3])){
			return false;
		}
		$timeStamps = $inPath[3];
		$filename = "course.$timeStamps.org.jpg";
		$data = array();
		if(!empty($_FILES['file']['tmp_name'])){
			$ret = move_uploaded_file($_FILES['file']['tmp_name'],$path."/".$filename);
			if($ret){
				list($width, $height, $type, $attr) = getimagesize($path."/".$filename);
				//大图：480:270，小图：240:135
				if($width<500 || $height<180){
					$data['error']="文件大小不符合标准";
					@unlink($path."/".$filename);
				}else{
					$data['file']="/upload/tmp/".$filename;
				}
			}else{
				$data['error']="上传失败-3";
			}
		}else{
				$data['error']="上传失败-2";
		}
		return $data;
	}
	/**
	 * ckeditor上传文件
	 * TODO:
	 * 1.鉴权
	 * 2.入库
	 */
	public function pageUpload($inPath){

		$msg = '';
		$filename="";
		$callback = ($_GET['CKEditorFuncNum']);
		if(empty($_REQUEST['type'])){
			$type="image";
		}else{
			$type=$_REQUEST['type'];
		}

		if(!empty($_FILES['upload']['tmp_name'])){
			$ret = utility_file::upload($_FILES['upload']['tmp_name'],user_api::getLoginUid(),$type,$_FILES['upload']['name']);
			if($ret){
				$filename=utility_cdn::filecdn()."/".$ret->fid;
			}else{
				$msg="ERROR";
			}
		}
		return '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$callback.', "'.$filename.'","'.$msg.'");</script>';
	}
	
	/**
	 * umeditor上传文件
	 * TODO:
	 * 1.鉴权
	 * 2.入库
	 */
	public function pageUmeditorUpload($inPath){
		$msg = 'SUCCESS';
		$filename="";
		$callback = isset($_GET['callback'])?$_GET['callback']:'';
		if(empty($_REQUEST['type'])){
			$type="image";
		}else{
			$type=$_REQUEST['type'];
		}

		if(!empty($_FILES['upfile']['tmp_name'])){
			$ret = utility_file::upload($_FILES['upfile']['tmp_name'],user_api::getLoginUid(),$type,$_FILES['upfile']['name']);
			if($ret){
				$filename=utility_cdn::filecdn()."/".$ret->fid;
			}else{
				$msg="ERROR";
			}
		}
		$info = array(
            "originalName" => $_FILES['upfile']['name'],
            "name"  => !empty($ret)?$ret->fid:'',
            "url"   => !empty($ret)?$filename:'',
            "size"  => $_FILES['upfile']['size'],
            "type"  => strtolower( strrchr( $_FILES['upfile']['name'] , '.' ) ),
            "state" => $msg
        );
		if($callback) {
			return '<script>'.$callback.'('.json_encode($info).')</script>';
		} else {
			return json_encode($info);
		}
	}
	
	
	/**上传作业**/
	public function pageUploadTaskFiles($inPath){
		$path = ROOT_WWW."/upload/tmp";
		if(!is_dir($path)){
			mkdir($path,0777,true);
		}
		if(empty($_REQUEST['type'])){
			$type="image";
		}else{
			$type=$_REQUEST['type'];
		}
		if(!empty($_FILES['file']['tmp_name'])){
			$thumbnail = new SThumbnail($_FILES['file']['tmp_name'],100);
			//$thumbnail->setMaxSize(220, 124);
	        $filename = utility_file::tempname("thumb");
			if($thumbnail->genFile($filename)===false){
				return false;
			};
			$file = utility_file::instance();
			$ret=$file->upload($filename,user_api::getLoginUid(),$type,$_FILES['file']['name']);
			if($ret){
				return array('fid'=>$ret->fid);
			}
		}
		return array();
	}
	public function pageUploadAttach($inPath){
		$plan_id = $inPath[3];
		$uptypes = array(
			"txt",
			"pdf",
			"doc",
			"docx",
			"ppt",
			"pptx",
			"jpg",
		//	"png",
		//	"gif",
			"xls",
			"xlsx",
		);
		$intype = array(
			"txt"=>"1",
			"pdf"=>"2",
			"doc"=>"3",
			"docx"=>"4",
			"ppt"=>"5",
			"pptx"=>"6",
			"jpg"=>"7",
		//	"png"=>"8",
		//	"gif"=>"9",
			"xls"=>"8",
			"xlsx"=>"9",
		);
		$swappic = array(
			"1"=>"/assets_v2/img/lesson-txt.png",
			"2"=>"/assets_v2/img/lesson-pdf.png",
			"3"=>"/assets_v2/img/lesson-doc.png",
			"4"=>"/assets_v2/img/lesson-doc.png",
			"5"=>"/assets_v2/img/lesson-ppt.png",
			"6"=>"/assets_v2/img/lesson-ppt.png",
			"7"=>"/assets_v2/img/lesson-jpg.png",
			"8"=>"/assets_v2/img/lesson-xls.png",
			"9"=>"/assets_v2/img/lesson-xls.png",
		//	"8"=>"/assets_v2/img/lesson-png.png",
		//	"9"=>"/assets_v2/img/lesson-gif.png",
		);
		$upfilename = strrev($_FILES['upfile']['name']);
		$arraytmp = explode('.',$upfilename);
		$suffix = strrev($arraytmp[0]);
		/*if (!in_array($suffix, $uptypes)) {        //判断文件的类型
			$error =  '上传文件类型不符';
			$errorcode = 300;
			$this->assign("errorcode",$errorcode);
			$this->assign("error",$error);
			$this->assign("plan_id",$plan_id);
			return $this->render("teacher/iframe.attach.html");
			//	$this->redirect("/teacher.manage.plan.{$plan_id}");
		}*/
		$data = array();
		$timestamps = time();
		if(!empty($_FILES['upfile']['tmp_name'])){
			$filenamereal = $_FILES['upfile']['name'];//.$timestamps.".".$suffix;
			$filenametmp = $_FILES["upfile"]["tmp_name"];
			$pathfile = ROOT_WWW."/upload/tmp";
			if(!is_dir($pathfile)){
				mkdir($pathfile,0777,true);
			}
			$filename = $pathfile."/".$filenamereal;
			$ret = move_uploaded_file($filenametmp,$pathfile."/".$filenamereal);
			$file = utility_file::instance();
			$rid = $file->upload($filename,user_api::getLoginUid(),$suffix,"");
			$files = $rid->fid;
			if(!$rid){
				$error ="上传失败";
				//	$this->redirect("/teacher.manage.plan.{$plan_id}");
			}
			$attArr = array(
			//	"title"=>$_POST["title"],
				"title"=>"12333",
				"order_no"=>"123",
				"type"=>$intype[$suffix],
				"thumb"=>$swappic[$intype[$suffix]],
				"attach"=>$files,
			);
		//	$retaddAtt = course_api::addPlanAttach($plan_id,$attArr);
			if($rid){
				$error= "上传成功请刷新页面";
				$errorcode = 200;
				unlink($filename);
			}else{
				$error ="上传失败";
				unlink($filename);
			}
			$data['type'] = $intype[$suffix];
			$data['attach'] = $files;
			$data["thumb"] = $swappic[$intype[$suffix]];
		}else{
			$error ="上传失败";
		}
		$data['error'] = $error;
		$data['errorcode'] = $errorcode;
		$timeStamps = time();
		return $data;
	}
	public function pageBatchUploadAttach($inPath){
		$plan_id = $inPath[3];
		$uptypes = array(
			"txt",
			"pdf",
			"doc",
			"docx",
			"ppt",
			"pptx",
			"jpg",
		//	"png",
		//	"gif",
			"xls",
			"xlsx",
            "rar",
            "zip"
		);
		$intype = array(
			"txt"=>"1",
			"pdf"=>"2",
			"doc"=>"3",
			"docx"=>"4",
			"ppt"=>"5",
			"pptx"=>"6",
			"jpg"=>"7",
		//	"png"=>"8",
		//	"gif"=>"9",
			"xls"=>"8",
			"xlsx"=>"9",
            "rar"=>"10",
            "zip"=>"11"
		);
		$swappic = array(
			"1"=>"/assets_v2/img/lesson-txt.png",
			"2"=>"/assets_v2/img/lesson-pdf.png",
			"3"=>"/assets_v2/img/lesson-doc.png",
			"4"=>"/assets_v2/img/lesson-doc.png",
			"5"=>"/assets_v2/img/lesson-ppt.png",
			"6"=>"/assets_v2/img/lesson-ppt.png",
			"7"=>"/assets_v2/img/lesson-jpg.png",
			"8"=>"/assets_v2/img/lesson-xls.png",
			"9"=>"/assets_v2/img/lesson-xls.png",
		//	"8"=>"/assets_v2/img/lesson-png.png",
		//	"9"=>"/assets_v2/img/lesson-gif.png",
			"10"=>"/assets_v2/img/lesson-rar.png",
			"11"=>"/assets_v2/img/lesson-zip.png",
		);
		$upfilename = strrev($_FILES['upfile']['name']);
		$arraytmp = explode('.',$upfilename);
		$suffix = strrev($arraytmp[0]);
		if (!in_array($suffix, $uptypes)) {        //判断文件的类型
			$error =  '上传文件类型不符';
			$errorcode = 300;
			$this->assign("errorcode",$errorcode);
			$this->assign("error",$error);
			$this->assign("plan_id",$plan_id);
			return $this->render("teacher/iframe.attach.html");
			//	$this->redirect("/teacher.manage.plan.{$plan_id}");
		}
		$data = array();
		$timestamps = time();
		//return $_FILES;
		if(!empty($_FILES['upfile']['tmp_name'])){
			$filenamereal = $_FILES['upfile']['name'];//.$timestamps.".".$suffix;
			$filenametmp = $_FILES["upfile"]["tmp_name"];
			$pathfile = ROOT_WWW."/upload/tmp";
			if(!is_dir($pathfile)){
				mkdir($pathfile,0777,true);
			}
			$filename = $pathfile."/".$filenamereal;
			$ret = move_uploaded_file($filenametmp,$pathfile."/".$filenamereal);
			$file = utility_file::instance();
			$rid = $file->upload($filename,user_api::getLoginUid(),$suffix,"");
			$files = $rid->fid;
/*
			$filenametmp = $_FILES["upfile"]["tmp_name"];
			$file = utility_file::instance();
			$rid = $file->upload($filenametmp,user_api::getLoginUid(),"image","");
			return $_FILES;

			$filenamereal = $_FILES['upfile']['name'];//.$timestamps.".".$suffix;
			$filenametmp = $_FILES["upfile"]["tmp_name"];
			$pathfile = ROOT_WWW."/upload/tmp";
			if(!is_dir($pathfile)){
				mkdir($pathfile,0777,true);
			}
			$filename = $pathfile."/".$filenamereal;
			//$ret = move_uploaded_file($filenametmp,$pathfile."/".$filenamereal);
			$file = utility_file::instance();
			$rid = $file->upload($filenametmp,user_api::getLoginUid(),$suffix,"");
			$files = $rid->fid;
*/
			if(!$rid){
				$error ="上传失败";
				//	$this->redirect("/teacher.manage.plan.{$plan_id}");
			}
			$attArr = array(
			//	"title"=>$_POST["title"],
				"title"=>"12333",
				"order_no"=>"123",
				"type"=>$intype[$suffix],
				"thumb"=>$swappic[$intype[$suffix]],
				"attach"=>$files,
			);
		//	$retaddAtt = course_api::addPlanAttach($plan_id,$attArr);
			if($rid){
				$error= "上传成功请刷新页面";
				$errorcode = 200;
				unlink($filename);
			}else{
				$error ="上传失败";
				unlink($filename);
			}
			$data['type'] = $intype[$suffix];
			$data['attach'] = $files;
			$data["thumb"] = $swappic[$intype[$suffix]];
		}else{
			$error ="上传失败";
		}
		$data['error'] = $error;
		$data['errorcode'] = $errorcode;
		$timeStamps = time();
		return $data;
	}
    public function pageUploadIdcard($inPath){
        $path = ROOT_WWW."/upload/tmp";
        if(!is_dir($path)){
            mkdir($path,0777,true);
        }
        if(!empty($_FILES['file']['tmp_name'])){
			list($width, $height, $type, $attr) = getimagesize($_FILES['file']['tmp_name']);
			//if($width!=1920 || $height!=360){
		     //   $data['error']="图片尺寸不符合标准";
              //  return $data;
			//}
            $file= utility_file::upload($_FILES['file']['tmp_name'],user_api::getLoginUid(),'image',$_FILES['file']['name']);
            $ret=array();
            if($file){
			    $ret['src']=utility_cdn::filecdn().'/'.$file->fid;
                $ret['fid']=$file->fid;
                return $ret;
            }else{
                return "";
            }
        }
        return "";
    }
    public function pageUploadQualify($inPath){
        $path = ROOT_WWW."/upload/tmp";
        if(!is_dir($path)){
            mkdir($path,0777,true);
        }
        if(!empty($_FILES['file']['tmp_name'])){
			list($width, $height, $type, $attr) = getimagesize($_FILES['file']['tmp_name']);
			//if($width!=1920 || $height!=360){
		     //   $data['error']="图片尺寸不符合标准";
              //  return $data;
			//}
            $file= utility_file::upload($_FILES['file']['tmp_name'],user_api::getLoginUid(),'image',$_FILES['file']['name']);
            $ret=array();
            if($file){
			    $ret['src']=utility_cdn::filecdn().'/'.$file->fid;
                $ret['fid']=$file->fid;
                return $ret;
            }else{
                return "";
            }
        }
        return "";
    }


	//作业图片上传  临时文件
	public function pagetaskUpload(){

		$path = ROOT_WWW."/upload/tmp/";
		if(!is_dir($path)){
			mkdir($path,0777,true);
		}
		$timeStamps =time();
        $uid = $this->user['uid'];
		$filename = "$uid.task.$timeStamps.jpg";
		$data = array();

		if($_FILES['file']['size'] > 1024*1024*5){
			return json_encode(array('code'=>-1,'msg'=>'最大为5M'));
		}
		if(!empty($_FILES['file']['tmp_name'])){

			//压缩原图
			$thumbnail = new SThumbnail($_FILES['file']['tmp_name'], 100);
			$thumbnail->setMaxSize(152, 152);
			$filename_152 = utility_file::tempname("thumb");
			if($thumbnail->genFile($filename_152)===false){
				return false;
			}
			//上传服务器taskUpload
			$file1 = utility_file::instance();
			$r152 = $file1->upload($filename_152,user_api::getLoginUid(),"image",$_FILES['file']['name']);
			$url152 = $r152->fid;
			$srcUrl152 = utility_cdn::file($url152);
			$imageSize = getimagesize("http:".$srcUrl152);
			$width = !empty($imageSize[0])?$imageSize[0]:'';
			$height = !empty($imageSize[1])?$imageSize[1]:'';


			//原图  不改变尺寸
			$ret = move_uploaded_file($_FILES['file']['tmp_name'],$path."/".$filename);
            //上传服务器taskUpload
			$file = utility_file::instance();
			$r1 = $file->upload($path.$filename,user_api::getLoginUid(),"image",$_FILES['file']['name']);
			$url = $r1->fid;
			$srcUrl = utility_cdn::file($url);
		}else{
			return json_encode(array('code'=>-2,'msg'=>'error'));
		}
		$imageName = $filename;
		//echo $path.$imageName;die;
		@unlink($path.$imageName);
		if(!empty($url) && $url152){
			return json_encode(array('code'=>200,'msg'=>'success','data'=>array('big'=>$url,'small'=>$url152,'src_big'=>$srcUrl,'src_small'=>$srcUrl152
			,'small_width'=>$width,'small_height'=>$height)));
		}
	}

	public function pageUploadFile(){
		$uptypes = array("txt","pdf","doc","docx","ppt","pptx","jpg","xls","xlsx");
		$intype = array("txt"=>"1","pdf"=>"2","doc"=>"3","docx"=>"4","ppt"=>"5","pptx"=>"6","jpg"=>"7","xls"=>"8","xlsx"=>"9","rar"=>"10","zip"=>"11");
		$swappic = array(
			"1"=>"/assets_v2/img/lesson-txt.png",
			"2"=>"/assets_v2/img/lesson-pdf.png",
			"3"=>"/assets_v2/img/lesson-doc.png",
			"4"=>"/assets_v2/img/lesson-doc.png",
			"5"=>"/assets_v2/img/lesson-ppt.png",
			"6"=>"/assets_v2/img/lesson-ppt.png",
			"7"=>"/assets_v2/img/lesson-jpg.png",
			"8"=>"/assets_v2/img/lesson-xls.png",
			"9"=>"/assets_v2/img/lesson-xls.png",
			"10"=>"/assets_v2/img/lesson-rar.png",
			"11"=>"/assets_v2/img/lesson-rar.png",
		);
		$upfilename = strtolower(strrev($_FILES['upfile']['name']));
		$arraytmp   = explode('.',$upfilename);
		$suffix     = strrev($arraytmp[0]);
		//判断文件的类型
		if (!in_array($suffix, $uptypes)) {
			$error     = '上传文件类型不符';
			$errorcode = '-1';
		}
		if(!empty($_FILES['upfile']['tmp_name'])){
			$filenamereal = $_FILES['upfile']['name'];
			$filenametmp  = $_FILES["upfile"]["tmp_name"];
			$pathfile = ROOT_WWW."/upload/tmp";
			if(!is_dir($pathfile)) mkdir($pathfile,0777,true);

			$filename = $pathfile."/".$filenamereal;
			$ret   = move_uploaded_file($filenametmp,$pathfile."/".$filenamereal);
			$file  = utility_file::instance();
			$rid   = $file->upload($filename,user_api::getLoginUid(),$suffix,"");
			$files = $rid->fid;

			if(!$rid){
				$error     = '上传失败';
				$errorcode = '-1';
				unlink($filename);
			}else{
				$error     = '上传成功';
				$errorcode = '1';
				unlink($filename);
			}
			$attArr = array(
				"title"    => "12333",
				"order_no" => "123",
				"type"     => $intype[$suffix],
				"thumb"    => $swappic[$intype[$suffix]],
				"attach"   => $files
			);
			$data['type']   = $intype[$suffix];
			$data['attach'] = $files;
			$data["thumb"]  = $swappic[$intype[$suffix]];
			$data["url"]  = utility_cdn::file($files);
		}else{
			$error     = "上传失败";
			$errorcode = "-1";
		}
		$data['error'] = $error;
		$data['errorcode'] = $errorcode;
		$timeStamps = time();

		return $data;
	}

    //作业  教师批改作业 flas 生成位二进制流 图片上传
	public function pagetaskFlashUpload(){

		$dataBody     = utility_net::getPostData();
		$params = SJson::decode($dataBody, true);
		if(empty($params['imageInfo'])){
			return   array('code'=>-1,'msg'=>'参数错误');
		}
		$data = array();
		$path = ROOT_WWW."/upload/tmp";
		if(!is_dir($path))
		{
			mkdir($path,0777,true);
		}
		$imgName = 'task'.time().".org.jpg";
		$filename = $path."/".$imgName;

		//获取图片

		if(file_put_contents($filename, base64_decode($params['imageInfo']))){

			//压缩原图
			$imgName152 = 'task'.time().".small.org.jpg";
			$filename152 = $path."/".$imgName152;
			//$thumbnail = new SThumbnail(file_put_contents($filename152, base64_decode($params['imageInfo'])), 100);
			$thumbnail = new SThumbnail($filename152, 100);

			$thumbnail->setMaxSize(152, 152);
			$filename_152 = utility_file::tempname("thumb");
			if($thumbnail->genFile($filename_152)===false){
				return false;
			}
			//上传服务器taskUpload
			//$imgName152 = 'task'.time().".small.org.jpg";
			$file152 = utility_file::instance();
			$r152 = $file152->upload($filename,user_api::getLoginUid(),"image");
			$url152 = $r152->fid;
			$src152 = utility_cdn::file($r152->fid);

			//上传服务器taskUpload 不改变尺寸
			$file = utility_file::instance();
			$r1 = $file->upload($filename,user_api::getLoginUid(),"image",$imgName);
			$url = $r1->fid;
			$src = utility_cdn::file($r1->fid);
			//@unlink($filename);
			$result = array('code'=>200,'big'=>$url,'bigurl'=>$src,'small'=>$url152,'smallurl'=>$src152);
		}
         return json_encode($result);
	}

	/**上传模板左右图**/
	public function pageUploadTemplatePic($inPath){
		$path 		= ROOT_WWW."/upload/tmp";
		if(!is_dir($path)){
			mkdir($path,0777,true);
		}
		$uid 		= $this->user['uid'];
		$filename = $uid.".org.jpg";
		if(!empty($_FILES['file']['tmp_name'])){
			$ret = move_uploaded_file($_FILES['file']['tmp_name'],$path."/".$filename);
			if($ret){
				return "/upload/tmp/".$filename;
			}
		}
		return "";
	}

	/**机构频道幻灯片**/
    public function pageUploadOrgChannelBanner($inPath){
        $path = ROOT_WWW."/upload/tmp";
        if(!is_dir($path)){
            mkdir($path,0777,true);
        }
        if(!empty($_FILES['file']['tmp_name'])){
			list($width, $height, $type, $attr) = getimagesize($_FILES['file']['tmp_name']);
			if($width!=890 || $height!=360){
		        $data['error']="图片尺寸不符合标准";
                return $data;
			}
            $file= utility_file::upload($_FILES['file']['tmp_name'],user_api::getLoginUid(),'image',$_FILES['file']['name']);
            $ret=array();
            if($file){
			    $ret['src']=utility_cdn::filecdn().'/'.$file->fid;
                $ret['fid']=$file->fid;
                return $ret;
            }else{
                return "";
            }
        }
        return "";
    }
	

}
