<?php
class player_live{
 	/**
	 * @param $type = CHAT-RTMP
	 * @return $ret = object:{"cdn_id":"","host":""}
 	 */
	public static function getCDN($type){
		$params=new stdclass;
		$params->type=$type;
		$params->ip=utility_ip::realIp();
		$ret = utility_services::call("/live/play/getCdn",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getLiveUrl($plan_id,$user_id,$scheme){
		$params=new stdclass;
		$params->plan_id=$plan_id;
		$params->user_id=$user_id;
		$params->scheme=$scheme;
		$params->ip=utility_ip::realIp();
		$ret = utility_services::call("/live/play/getLiveUrl",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getVodUrl($plan_id,$user_id,$scheme){
		$params=new stdclass;
		$params->plan_id=$plan_id;
		$params->user_id=$user_id;
		$params->scheme=$scheme;
		$params->ip=utility_ip::realIp();
		$ret = utility_services::call("/live/play/getVodUrl",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getThumbs($video_id){
		$ret = utility_services::call("/video/thumb/getThumbs/{$video_id}");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
}
