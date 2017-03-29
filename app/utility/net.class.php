<?php
/**
  *
  */
class utility_net{
	/**
	  * 获取原始的post body
	  */
	public static function getPostData(){
		return file_get_contents("php://input");
	}
	public static function isHTTPS(){
		if(defined("HTTPS") || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")){
			return true;
		}
		return false;
	}
	public static function getDomainRoot(){
		#添加头部和尾巴
		$url = $_SERVER['HTTP_HOST']. "/";
		#判断域名
		preg_match("/((\w*):\/\/)?\w*\.?([\w|-]*\.(com.cn|net.cn|gov.cn|org.cn|com|net|cn|org|asia|tel|mobi|me|tv|biz|cc|name|info))\//", $url, $ohurl);
		#判断IP
		if(empty($ohurl[3])) {
			preg_match("/((\d+\.){3}\d+)\//", $url, $ohip);
            //此处在error_log里报错 加个判断
            if(!empty($ohip[1])){
			    return $ohip[1];
            }else{
                return false; 
            }
		}
		return $ohurl[3];
	}
}
