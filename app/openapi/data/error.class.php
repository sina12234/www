<?php
class openapi_data_error{
	var $result=array("code"=>0,"msg"=>"");
	var $data;
	static public function error($code,$msg=""){
		$error = new self;
		unset($error->data);
		$error->result['code']=$code;
		$error->result['msg']=$msg;
		return SJson::encode($error);
	}
}
