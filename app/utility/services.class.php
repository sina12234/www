<?php
class utility_services{
	public static function url($node,$uri){
		$conf = SConfig::getConfig(ROOT_CONFIG."/services.conf",$node);
		$gateway="";
		if(!empty($conf->gateway)){
			if(is_array($conf->gateway)){
				$gateway=$conf->gateway[0];
			}else{
				$gateway=$conf->gateway;
			}
		}else{
			trigger_error("api not defined in file " .(ROOT_CONFIG."/services.conf"));
		}
		return $gateway."/".$uri;
	}
	public static function call($uri,$params=""){
		$data="";
		if(!empty($params)){
			if(is_object($params) || is_array($params)){
				$data=SJson::encode($params);
			}else{
				$data=$params;
			}
		}
		$ret_post = SHttp::post(utility_services::url("api",$uri),$data,array(),false,5);
		$ret_json = SJson::decode($ret_post);
		if(!empty($ret_json)){
			return $ret_json;
		}
		trigger_error("call $uri may be error[$ret_post]");
		return $ret_post;
	}
}
