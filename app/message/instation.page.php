<?php
/*
 * chat传递消息接口
 */
class message_instation extends STpl{
	var $user;
	public function __construct($inPath){
		$this->user = user_api::loginedUser();
	}
	public function pageGetUnreadNum($inPath){
		if(empty($this->user)){
			return false;
		}
		$ret = message_api::getUnreadInstationNum($this->user["uid"], utility_session::get()["user"]["token"]);
		$data = new stdclass;
		$data->num = $ret;
		return $data;
	}
}

