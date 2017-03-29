<?php

/**
 * @author
 * @docs https://wiki.gn100.com/doku.php?id=docs:message:plan
 */
class message_http{

	public static function addPlanMsg($params){
		$params->plan_id = (int) ($params->plan_id);
		$params->type= (int) ($params->type);
		if(isset($params->user_from_id)) 	$params->user_from_id= (int) ($params->user_from_id);
		if(isset($params->user_to_id))		$params->user_to_id= (int) ($params->user_to_id);
		if(isset($params->live_second))		$params->live_second= floatval($params->live_second);
		$conf = SConfig::getConfig(ROOT_CONFIG."/services.conf","message");
		$url = $conf->gateway . "/message.plan.add";
		$ret = SJson::decode(SHttp::post($url,array("data"=>SJson::encode($params)), array(),false,3));
		return $ret;
	}
}
