<?php
class user_parterner_api{
	/**
	 * 获取第3方app信息,后面从数据库里存
	 */
	public static function getApp($app_id){
		$app = SConfig::getConfig(ROOT_CONFIG."/parterner.conf",$app_id);
		return $app?$app:false;
	}
	public static function checkHash($app_id,$app_hash,$data){
		unset($data['apphash']);
		if( ($app=self::getApp($app_id)) == false){
			return false;
		}
		$hash = self::appHash($data,$app->appkey);
		if($hash != $app_hash){
			return false;
		}
		return true;
	}
	public static function appHash($params,$key){
		ksort($params);
		$hash_c = "";
		foreach($params as $k=>$v){
			$hash_c.= $k.":".$v.";";
		}
		return md5($hash_c.$key);
	}
}
