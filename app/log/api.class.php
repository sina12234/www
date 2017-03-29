<?php
class log_api{
	/* for course.plan.play */
	//promote log
	const click_page = 1;
	const register_page = 1;
	const reg_succ_get = 2;
	const reg_succ_post = 3;
	const HOST_PROMOTE = 'www.yunke.com';
	public static function addPlayLog($data, $play=null, $reason=null){
		$params = new stdclass;
		$params->data = $data;
        if(!empty($play)){
            $params->play = $play;
        }
        if(!empty($reason)){
            $params->reason = $reason;
        }
		$ret = utility_services::call("/log/log/addplaylog", $params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function addPromoteLog($promote_id,$org_id=0,$type,$user_id=0,$rf=''){
		if(empty($promote_id)  || empty($type)){
			return false;
		}

		$params = new stdclass;
		$params->promote = (int)($promote_id);
		$params->fk_user_owner = (int)($org_id);
		$params->client_ip = utility_ip::realIp();
		if(!empty($rf)){
			$params->referer = "$rf";
		}else{
			$params->referer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
		}
		$params->type = (int)($type);
		$params->fk_user = $user_id;
		$params->create_time = date('Y-m-d H:i:s',time());
		$ret = utility_services::call("/log/log/addPromoteLog",$params);
		if(!empty($ret->result) && $ret->result->code == 0){
			return true;
		}
		return false;
	}
	//验证是否可以插入promote_log表中 规则半小时同一ip插一次
	public static function isVerifyPromoteLog(){
		$params = new stdclass;
		$params->client_ip = utility_ip::toLong(utility_ip::realIp());
		$params->type = self::register_page;
		$ret = utility_services::call("/log/log/getPromoteLog",$params);
		//print_r($ret);
		if(!empty($ret->result) && $ret->result->code == 0){
			$log_time = strtotime($ret->result->data->create_time);
			$now_time = time();
			if($now_time-$log_time<1800){
				return false;
			}else{
				return true;
			}
		}else{
			return true;
		}
	}
	//验证是否存在poromote_id 和插入数据
	public static function insertPromoteLog($promote_id,$type,$user_id=0,$rf=''){
        $promoteInfo = promote_api::getPromoteByCode($promote_id);
		if(!empty($promoteInfo->result->data->items[0]->pk_promote)){
			$promote_ids = $promoteInfo->result->data->items[0]->pk_promote;
			if($_SERVER['HTTP_HOST'] == self::HOST_PROMOTE){
				self::addPromoteLog($promote_ids,0,$type,$user_id,$rf);
			}else{
				$org = user_organization::subdomain();
				if(!empty($org) && $org->userId == $promoteInfo->result->data->items[0]->fk_user_owner){
					self::addPromoteLog($promote_ids,$org->userId,$type,$user_id,$rf);
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
    }

    /**
     * ＠desc add interface request log
     *
     * @param $data
     * @return bool
     */
    public static function addInterfaceLog($data)
    {
        if (empty($data['deviceInfo'])) return false;

        $map = [
            'a' => 1, // android
            'i' => 2, // ios
            'p' => 3  // pc
        ];

        $type = 0; // default web
        if (isset($data['type']) && array_key_exists($data['type'], $map)) {
            $type = $map[$data['type']];
        }

        $params = [
            'url'        => utility_tool::getCurUrl(),
            'type'       => $type,
            'deviceInfo' => $data['deviceInfo']
        ];

        $reqUrl = '/log/log/AddInterfaceLog';
        $res    = interface_func::requestApi($reqUrl, $params);
        if (!empty($res['code'])) return false;

        return true;
    }
}
