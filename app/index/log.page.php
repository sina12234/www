<?php

class index_log extends STpl{
	var $user;
	public function __construct($inPath){
		$this->user = user_api::loginedUser();
	}
	public function pageHz(){		
		$params = $_GET;
		if(!empty($params['pr'])){
			$promote_id = trim($params['pr']);
			$rf = '';
			if(!empty($params['rf'])){
				$rf= urldecode($params['rf']);
			}
			if($this->user['uid']){
				log_api::insertPromoteLog($promote_id,log_api::click_page,$this->user['uid'],$rf);
			}else{
				log_api::insertPromoteLog($promote_id,log_api::click_page,0,$rf);
			}
		}
		if(!empty($params['url']) ){
			$url = urldecode($params['url']);
			header("Location:$url");
		}
	}
}
?>
