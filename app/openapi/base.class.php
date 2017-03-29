<?php
/*
	openapi公共类
*/
class openapi_base{

	private 	$request; //请求的所有参数
	public 		$params;  //使用参数
	public 	    $app;
	public 		$returnData = array('code' => 0,'message'  => '成功','result' => array());//返回信息
	public function __construct($inPath){
		//header('Content-type: application/json');
		$request = SJson::decode(utility_net::getPostData());
		if($this->hashCheck($request,$app,$error,$msg)===false){
			self::returnData($error,"",$msg);
		}
		$this->request=$request;
		$this->params=$request->params;
		$this->app=$app;
	}
	//获取变量
	public function getVar($var){
		return isset($this->$var)?$this->$var:"";
	}
	//返回数据
	public function returnData($code,$data="",$msg=""){
		$this->returnData['code'] = $code;
		$this->returnData['result'] = !empty($data) ? $data : new stdClass();
		if(empty($msg)){
			$errorMsg = self::errorCode($code);
			$this->returnData['message'] = !empty($errorMsg) ? $errorMsg:"未知错误";
		}else{
			$this->returnData['message'] = $msg;
		}
		echo json_encode($this->returnData, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);exit;
	}
	public function hashCheck($object,&$app,&$error=-1,&$msg=""){
		if(!is_object($object)){
			self::returnData("-4");
		}
		if(empty($object->appid)){
			self::returnData("-1");
		}
		if( ($app=user_parterner_api::getApp($object->appid)) == false){
			self::returnData("-2");
		}
		if( $this->appHash($object->params,$app->appkey) != $object->apphash){
			//$key = user_parterner_api::getApp($object->appid);
			//echo openapi_api::apphash(($object->params),$key->appkey);
			self::returnData("-3");
		}
		return true;
	}
	public function appHash($params,$key){
		//测试打开
		//echo md5($key.SJson::encode($params));exit;
		return md5($key.SJson::encode($params, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
	}
	//返回错误码
	public function errorCode($code){
		$codeCn = array(
			'0'		=> '成功',
			'-1'	=> 'appid不存在',
			'-2'	=> 'appid错误',
			'-3'	=> 'appkey错误',
			'-4'	=> '参数错误',
			'-5'	=> '没有数据',
		);
		return empty($codeCn[$code])?false:$codeCn[$code];
	}
	
}
