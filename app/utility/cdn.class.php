<?php
class utility_cdn{
	public static function filecdn($cdnname="file",$file=""){
		$conf = SConfig::getConfig(ROOT_CONFIG."/cdn.conf",$cdnname);
		if(isset($conf->url)){
			$ver = isset($conf->version)?"?ver=".$conf->version:"";
			if(empty($file)){
				return $conf->url;
			}else{
				$path = $file{0}=="/"?"":"/";
				return $conf->url.$path.$file.$ver;
			}
		}
		return ;
	}
	public static function fileurl($cdnname="file",$file=""){
		$conf = SConfig::getConfig(ROOT_CONFIG."/cdn.conf",$cdnname);
		if(isset($conf->url)){
			if(empty($file)){
				return $conf->url;
			}else{
				$path = $file{0}=="/"?"":"/";
				return $conf->url.$path.$file;
			}
		}
		return ;
	}
	public static function jsurl($f){
		return self::fileurl("js",$f);
	}
	public static function file($f){
		return self::filecdn("file",$f);
	}
	public static function css($f){
		return self::filecdn("css",$f);
	}
	public static function img($f){
		return self::filecdn("img",$f);
	}
	public static function js($f){
		return self::filecdn("js",$f);
	}
	public static function swf($f){
		return self::filecdn("swf",$f);
	}
	public static function qrcode($f){
		return self::filecdn("qrcode",$f);
	}
	public static function playlog($f){
		return self::filecdn("playlog",$f);
	}
	public static function upload_video(){
		return self::filecdn("upload_video");
	}
	public static function chat_pull(){
		return self::filecdn("chat_pull");
	}
	public static function chat_ws(){
		return self::filecdn("chat_ws");
	}
	public static function chat_pullset(){
		return self::filecdn("chat_pullset");
	}
	public static function http($url){
		return self::url_replace($url,array("scheme"=>"http"));
	}
	public static function https($url){
		return self::url_replace($url,array("scheme"=>"https"));
	}
	public static function url_replace($url, $parts = array()){
		is_array($url) || $url = parse_url($url);
		is_array($parts) || $parts = parse_url($parts);
		isset($url['query']) && is_string($url['query']) || $url['query'] = null;
		isset($parts['query']) && is_string($parts['query']) || $parts['query'] = null;
		$keys = array('user', 'pass', 'port', 'path', 'query', 'fragment');
		if (isset($url['path']) && $url['path'] !== '' && substr($url['path'], 0, 1) !== '/') {
			$url['path'] = '/' . $url['path'];
		}
		foreach (array('scheme', 'host') as $part) {
			if (isset($parts[$part])) {
				$url[$part] = $parts[$part];
			}
		}
		foreach ($keys as $key) {
			if (isset($parts[$key])) {
				$url[$key] = $parts[$key];
			}
		}
		$parsed_string = '';
		if (!empty($url['scheme'])) {
			$parsed_string .= $url['scheme'] . '://';
		}
		if (!empty($url['user'])) {
			$parsed_string .= $url['user'];
			if (isset($url['pass'])) {
				$parsed_string .= ':' . $url['pass'];
			}
			$parsed_string .= '@';
		}
		if (!empty($url['host'])) {
			$parsed_string .= $url['host'];
		}
		if (!empty($url['port'])) {
			$parsed_string .= ':' . $url['port'];
		}
		if (!empty($url['path'])) {
			$parsed_string .= $url['path'];
		}
		if (!empty($url['query'])) {
			$parsed_string .= '?' . $url['query'];
		}
		if (!empty($url['fragment'])) {
			$parsed_string .= '#' . $url['fragment'];
		}
		$new_url = $url;
		return $parsed_string;
	}
}
