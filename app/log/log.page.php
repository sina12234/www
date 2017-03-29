<?php
/*
 *
 */
class log_log extends STpl{
	var $user;
	public function __construct($inPath){
		$this->user = user_api::loginedUser();
		header("Access-Control-Allow-Origin: *");
	}
	public function pageAddPlayLog($inPath){
		/*if(empty($this->user)){
			return false;
		}*/
		//$params = SJson::decode(utility_net::getPostData());
        $play = empty($inPath[3]) ? 0 : $inPath[3];
        $reason = empty($inPath[4]) ? 0 : $inPath[4];
		$params = utility_net::getPostData();
		if(!empty($params)){
			return log_api::addPlayLog($params, $play, $reason);
		}
		return false;
	}
}
?>
