<?php
/*
 *  公网接口
 */
class live_video{
    public function pagePlay($inPath){
		if(empty($_REQUEST['token'])){
			header("HTTP/1.0 404 Not Found");exit;
		}
		$params=array("app"=>"play","token"=>$_REQUEST['token']);
		$r = SHttp::get(utility_services::url("api","/live/video/play"), $params, array(),false,3);
		if($r===false){
			header("HTTP/1.0 404 Not Found");
		}
		exit;
	}
	public function pagePlaydone($inPath){
		if(empty($_REQUEST['token'])){
			header("HTTP/1.0 404 Not Found");exit;
		}
		$params=array("app"=>"play","token"=>$_REQUEST['token']);
        $r = SHttp::get(utility_services::url("api","/live/video/playdone"), $params,array(),false,3);
		if($r===false){
			header("HTTP/1.0 404 Not Found");
		}
		exit;
    }
}
