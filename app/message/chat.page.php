<?php
/*
 * chat传递消息接口
 */
class message_chat extends STpl{
	var $user;
	public function __construct($inPath){
		$this->user = user_api::loginedUser();
	}
	public function pageAddMsgV2($inPath){
		if(empty($this->user)){
			return false;
		}
		if(!empty($_POST)){
			return message_api::addMsgV2($_POST, $this->user["uid"], $this->user["token"]);
		}
		return false;
	}
	/**
	 * TODO
	 * @有用么？
	public function pageGetMsgV2($inPath){
		if(empty($this->user)){
			$user_id = 0;
			$token = user_api::getTokenDirect();
		}else{
			$user_id = $this->user["uid"];
			$token = $this->user["token"];
		}
		if(!empty($_POST)){
			return message_api::getMsgV2($_POST, $user_id, $token);
		}
		return false;
	}
	 **/
/*
	public function pageGetSingleForbid($inPath){
		if(empty($this->user)){
			return false;
		}
		if(!empty($_POST)){
			return message_api::getSingleForbid($_POST, $this->user["uid"]);
		}
		return false;
	}
*/
}
