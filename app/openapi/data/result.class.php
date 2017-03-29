<?php
class openapi_data_result{
	var $result=array("code"=>0,"msg"=>"");
	var $data;
	static public function set($data){
		$error = new self;
		$error->result['code']=0;
		$error->result['msg']="success";
		$error->data=$data;
		return SJson::encode($error);
	}
}
