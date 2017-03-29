<?php
/**
 * 用户头像生成类
 */
class user_thumb{
	public static function genByFile($filename){
		//生成3种大小的文件 128*128 64*64 24*24
		$thumbnail = new SThumbnail($filename, 100); 
		$thumbnail->setMaxSize(128, 128);
		$filename_128 = utility_file::tempname("thumb");
		if($thumbnail->genFile($filename_128)===false){
			return false;
		};
		$thumbnail = new SThumbnail($filename, 100); 
		$thumbnail->setMaxSize(64, 64);
		$filename_64 = utility_file::tempname("thumb");;
		if($thumbnail->genFile($filename_64)===false){
			return false;
		};
		$thumbnail = new SThumbnail($filename, 100); 
		$thumbnail->setMaxSize(24, 24);
		$filename_24 = utility_file::tempname("thumb");
		if($thumbnail->genFile($filename_24)===false){
			return false;
		};
		//上传3个文件
		$ret = array("large"=>"","medium"=>"","small"=>"");
		$file = utility_file::instance();
		$r1 = $file->upload($filename_128,user_api::getLoginUid(),"image");
		if(!empty($r1)){
			$ret['large']=$r1->fid;
		}
		$r1 = $file->upload($filename_64,user_api::getLoginUid(),"image");
		if(!empty($r1)){
			$ret['medium']=$r1->fid;
		}
		$r1 = $file->upload($filename_24,user_api::getLoginUid(),"image");
		if(!empty($r1)){
			$ret['small']=$r1->fid;
		}
		@unlink($filename_24);
		@unlink($filename_64);
		@unlink($filename_128);
		return $ret;
	}
	public static function genByFileLOGO($filename){
		//生成3种大小的文件 128*128 64*64 24*24
		$thumbnail = new SThumbnail($filename, 100); 
		$thumbnail->setMaxSize(129, 48);
		$filename_128 = utility_file::tempname("thumb");
		if($thumbnail->genFile($filename_128)===false){
			return false;
		};
		$thumbnail = new SThumbnail($filename, 100); 
		$thumbnail->setMaxSize(129, 48);
		$filename_64 = utility_file::tempname("thumb");
		if($thumbnail->genFile($filename_64)===false){
			return false;
		};
		$thumbnail = new SThumbnail($filename, 100); 
		$thumbnail->setMaxSize(129, 48);
		$filename_24 = utility_file::tempname("thumb");
		if($thumbnail->genFile($filename_24)===false){
			return false;
		};
		//上传3个文件
		$ret = array("large"=>"","medium"=>"","small"=>"");
		$file = utility_file::instance();
		$r1 = $file->upload($filename_128,user_api::getLoginUid(),"image");
		if(!empty($r1)){
			$ret['large']=$r1->fid;
		}
		$r1 = $file->upload($filename_64,user_api::getLoginUid(),"image");
		if(!empty($r1)){
			$ret['medium']=$r1->fid;
		}
		$r1 = $file->upload($filename_24,user_api::getLoginUid(),"image");
		if(!empty($r1)){
			$ret['small']=$r1->fid;
		}
		@unlink($filename_24);
		@unlink($filename_64);
		@unlink($filename_128);
		return $ret;
	}
	public static function genByUrl($url){
		$data=SHttp::get( $url, $params=array(), $cookies=array(), $returnHeader=false, $timeout=2);
		if(empty($data))return false;
		$filename = utility_file::tempname("thumb");;
		file_put_contents($filename,$data);
		return self::genByFile($filename);
	}
	
	/*
	 *@desc 图片裁剪
	 *@params $option ('course'|'user')
	 *@params $userId 用户id
	 *@params $smaSize("240,135") $medSize("480,270") $$bigSize
	 *@params $w $h $x $y 裁剪参数
	 */
	public static function tailorPic($userId, $option, $smaSize, $medSize, $bigSize, $w, $h, $x, $y ){	
		$data = array();
		$path = ROOT_WWW."/upload/tmp/";
		if($option == 'user'){
			$fileName    = $path.$userId.".jpg";
			$fileNameDst = $path.$userId.".dst.png";
			
		}elseif($option == 'course'){
			$fileName    = $path."course.".$userId.".org.jpg";
			$fileNameDst = $path."course.".$userId.".dst.jpg";
		}
		
		if(!is_file($fileName) && empty($w)) return array('msg'=>'参数错误', 'data'=>$data);
		
		list($width, $height, $type, $attr) = getimagesize($fileName);
		if(empty($width) || empty($height)) return array('msg'=>'参数错误', 'data'=>$data);
		
		switch($type){
			case 1: 
				$imgReg = imagecreatefromgif($fileName);
			break;
			case 2:
				$imgReg = imagecreatefromjpeg($fileName);
			break;
			default:
			    $imgReg = imagecreatefrompng($fileName);
		} 
		$dstReg = imagecreatetruecolor($w, $h);
		
		if($option == 'user'){
			//png
			imagealphablending($dst_r,false);
			imagecopyresampled($dstReg, $imgReg, 0, 0, $x, $y, $w, $h, $w, $h);
			imagesavealpha($dst_r,true);
		}elseif($option == 'course'){
			imagecopyresampled($dstReg, $imgReg, 0, 0, $x, $y, $w, $h, $w, $h);
		}
		
		$r = imagejpeg($dstReg, $fileNameDst);
		if(!$r) return array('msg'=>'生成图片失败', 'data'=>$data);
		
		//小图
		$thumbnail = new SThumbnail($fileNameDst, 100);
		$thumbnail->setMaxSize($smaSize);
		$fileNameSma = utility_file::tempname("thumb");
		if($thumbnail->genFile($fileNameSma)===false){
			return array('msg'=>'生成小图片失败', 'data'=>$data);
		};
		
		//中图
		$thumbnail = new SThumbnail($fileNameDst, 100); 
		$thumbnail->setMaxSize($medSize);
		$fileNameMed = utility_file::tempname("thumb");;
		if($thumbnail->genFile($fileNameMed)===false){
			return array('msg'=>'生成中图片失败', 'data'=>$data);
		};
		
		//大图
		$fileNameBig = $fileNameDst;
		if(!empty($bigSize)){
			$thumbnail = new SThumbnail($fileNameDst, 100); 
			$thumbnail->setMaxSize($bigSize);
			$fileNameBig = utility_file::tempname("thumb");
			if($thumbnail->genFile($filenameBig)===false){
				return array('msg'=>'生成大图片失败','data'=>$data);
			};
		}
		
		$file = utility_file::instance();
		$r1 = $file->upload($fileNameBig,user_api::getLoginUid(),"image");
		$r2 = $file->upload($fileNameMed,user_api::getLoginUid(),"image");
		$r3 = $file->upload($fileNameSma,user_api::getLoginUid(),"image");
		
		if(!empty($r1) || !empty($r2) || !empty($r3)){
			$data = [
				'large'  => $r1->fid,
				'medium' => $r2->fid,
				'small'  => $r3->fid
			];
		}
		$ret = array('msg'=>'','data'=>$data);
		
		@unlink($fileNameBig);
		@unlink($fileNameMed);
		@unlink($fileNameSma);
		return $ret;
	}
}
