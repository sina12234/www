<?php
class live_verify{
	/**
	 *
	 * {"action":"on_connect","client_id":38542,"ip":"121.69.7.6","vhost":"play-cdn-cc.gn100.com","app":"play","tcUrl":"rtmp://play-cdn-cc.gn100.com:1935/play?token=w-_8aFd4JqleD62ZMfTacKy6zyT0EClPsoxPNabbNfA1bUyjSM1P8uBfP8SfoemEAuTz-PbSRfqJG5v-qnb3R4dd3xtnNSNssFyo0M8ZEQSdB1XjJm9WICDCjCl1baaeVGOORtQaDabIB5KPinMGjkabWd9L2-P-cDovlvc7ZH0=&planid=2175/7e44f6169f?token=w-_8aFd4JqleD62ZMfTacKy6zyT0EClPsoxPNabbNfA1bUyjSM1P8uBfP8SfoemEAuTz-PbSRfqJG5v-qnb3R4dd3xtnNSNssFyo0M8ZEQSdB1XjJm9WICDCjCl1baaeVGOORtQaDabIB5KPinMGjkabWd9L2-P-cDovlvc7ZH0=&planid=2175","pageUrl":"http://test.gn100.com/course.plan.play/2175"}
	 */
	public function pageCC($inPath){
		error_log(date("Y-m-d H:i:s")." pageCC ".__LINE__."\n" , 3 ,"/tmp/verify.log");
		$data = utility_net::getPostData();
		$post = SJson::decode($data);
		if(empty($post->tcUrl)){
			error_log(var_export($data,true)."\n", 3 ,"/tmp/verify.log");
			error_log(var_export($post,true)."\n", 3 ,"/tmp/verify.log");
			error_log("FAILED-1!\n", 3 ,"/tmp/verify.log");
			header("HTTP/1.0 404 Not Found");
			exit;;
		}else{
			$info = parse_url($post->tcUrl, PHP_URL_QUERY);
			parse_str($info,$url);
			if(empty($url['token'])){
				error_log(var_export($info,true)."\n", 3 ,"/tmp/verify.log");
				error_log(var_export($url,true)."\n", 3 ,"/tmp/verify.log");
				error_log("FAILED-2!\n", 3 ,"/tmp/verify.log");
				header("HTTP/1.0 404 Not Found");
				exit;;
			}
			$params=array("app"=>"play","token"=>$url['token']);
			$r = SHttp::get(utility_services::url("api","/live/video/play"), $params, array(),false,3);
			if($r===false){
				error_log(var_export($params,true)."\n", 3 ,"/tmp/verify.log");
				error_log(var_export($r,true)."\n", 3 ,"/tmp/verify.log");
				error_log("FAILED-3!\n", 3 ,"/tmp/verify.log");
				header("HTTP/1.0 404 Not Found");
				exit;;
			}
		}
		error_log("SUCCESS!\n", 3 ,"/tmp/verify.log");
		exit;
	}
	public function pageChinaCache($inPath){
		error_log(date("Y-m-d H:i:s")." pageChinaCache ".__LINE__."\n" , 3 ,"/tmp/verify.log");
		error_log(var_export($_REQUEST,true)."\n", 3 ,"/tmp/verify.log");
		if(empty($_REQUEST['token'])){
			error_log("FAILED-1!\n", 3 ,"/tmp/verify.log");
			header("HTTP/1.0 404 Not Found");
		}
		$params=array("app"=>"play","token"=>$_REQUEST['token']);
		$r = SHttp::get(utility_services::url("api","/live/video/play"), $params, array(),false,3);
		if($r===false){
			error_log(var_export($params,true)."\n", 3 ,"/tmp/verify.log");
			error_log(var_export($r,true)."\n", 3 ,"/tmp/verify.log");
			error_log("FAILED-2!\n", 3 ,"/tmp/verify.log");
			header("HTTP/1.0 404 Not Found");
			exit;;
		}
		error_log("SUCCESS!\n", 3 ,"/tmp/verify.log");
		exit;
	}
}
