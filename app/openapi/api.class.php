<?php
class openapi_api{
	public static function hashCheck($object,&$app,&$error=-1,&$msg=""){
		if(!is_object($object)){
			return false;
		}
		if(empty($object->appid)){
			$error = -1; $msg="appid empty";
			return false;
		}
		if( ($app=user_parterner_api::getApp($object->appid)) == false){
			$error = -2; $msg="appid not exists";
			return false;
		}
		if( (self::appHash($object->params,$app->appkey)) != $object->apphash){
			$error = -3; $msg="apphash error";
			return false;
		}
		return true;
	}
	public static function appHash($params,$key){
		return md5($key.SJson::encode($params));
	}
}
