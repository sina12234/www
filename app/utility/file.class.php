<?php
/**
  * 2014/12/20 by hetao
  * 文件上传的类,上传到weedfs
  */
 
class utility_file{
	const TMPDIR="/tmp";

	public static $instance;
	public static $weedfs;
	public static $masterHost;
	public static function tempname($prefix="www_file_"){
		return tempnam(self::TMPDIR,$prefix);
	}

	public static function instance(){
		if( ! isset(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
	public function __construct(){
		$conf = SConfig::getConfig(ROOT_CONFIG."/services.conf","weedfs");
		if(!empty($conf->master)){
			if(is_array($conf->master)){
				self::$masterHost=$conf->master[0];
			}else{
				self::$masterHost=$conf->master;
			}
		}else{
			trigger_error("Weedfs not defined in file " .(ROOT_CONFIG."/mgr.conf"));
		}
		self::$weedfs = new utility_weedfs(self::$masterHost);
	}
	/**
	  * 上传文件
	  * @param string $filename 
	  * @return mixed $result false | mixed
		$result = stdClass Object
		(
		    [fid] => 2,34043e6c5f
		    [url] => 10.165.61.79:8081
		    [publicUrl] => f1.gn100.com:8081
		    [count] => 1
		    [result] => stdClass Object
		        (
		            [name] => 0SConfig_Cache_f621353219a48b8806b61763108301ec
		            [size] => 96
		        )
		
		)
	  *
	  **/
	public static function upload($filename,$uid=0,$type=null,$name=null){
		self::instance();
		$r = SJson::decode(self::$weedfs->assign(1));
		$realpath = realpath($filename);
		if(!is_file($realpath)){
			trigger_error("file $filename not exists!");
			return false;
		}
		if(filesize($realpath)<=0){
			trigger_error("file $filename is empty!");
			return false;
		}
		if(!empty($r->fid)){
			$store_r = SJson::decode(self::$weedfs->store($r->url,$r->fid,self::curl_file($realpath)));
			if(empty($store_r) || !empty($store_r->error)){
				trigger_error("store $realpath error!");
				return false;
			}
		}
		$r->result = $store_r;
		//{{{写入db
		$params=new stdclass;
		$params->fid=$r->fid;
		$params->uid=$uid;
		$params->type=$type;
		$params->size=$store_r->size;
		if(empty($name)){
			$params->name=$store_r->name;
		}else{
			$params->name=$name;
		}
		$params->publicUrl=$r->publicUrl;
		$params->url=$r->url;
		$ret = utility_services::call("/file/info/set",$params);
		if(!empty($ret->result) && $ret->result->code===0){
			$r->write_db=true;
		}else{
			$r->write_db=false;
		}
		//}}}
		return $r;
	}
	public static function listfile($uid=0,$type=null){
		$params=new stdclass;
		$params->uid=$uid;
		$params->type=$type;
		$page=1;
		$size=20;
		$ret = utility_services::call("/file/info/list/$page/$size",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function curl_file($filename, $mimetype = '', $postname = ''){
		if (!function_exists('curl_file_create')) {
			function curl_file_create($filename, $mimetype = '', $postname = '') {
				return "@$filename;filename="
					. ($postname ?: basename($filename))
					. ($mimetype ? ";type=$mimetype" : '');
			}
		}
		return curl_file_create($filename, $mimetype = '', $postname = '');
	}
	public static function getFileByFidArr($fidArr){
		$ret = utility_services::call("/file/info/getFileByFidArr",$fidArr);
		if(!empty($ret->data)){
			return $ret->data;
		}else{
			return false;
		}
	}

	public static function getFileByFid($fid)
	{
		$res = utility_services::call("/file/info/getFileByFid/{$fid}");
		if ($res->code)
			return [];

		return $res->result;
	}

	public static function getUrlByVolume($volume)
	{
		$res = utility_services::call("/file/info/GetUrlByVolume/{$volume}");
		if ($res->code)
			return [];

		return $res->result;
	}
}
