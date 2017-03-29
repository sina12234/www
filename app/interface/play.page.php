<?php

class interface_play extends interface_base
{
    public function pageStream()
    {
        if (!utility_tool::check_int($this->paramsInfo['params']['planId'])) {
            return $this->setMsg(1010);
        }

		$userId = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;
		$scheme = utility_net::isHTTPS() ? 'https' : 'http';

        $playInfo = player_live::getLiveUrl($this->paramsInfo['params']['planId'], $userId, $scheme);

        if (!empty($playInfo) && $playInfo->live_call != 'publish_done') {
            return $this->setData(
                [
                    'url' => "rtmp://".$playInfo->chat->host."/chat",
                    'stream' => $playInfo->stream_name
                ]
            );
        }

        return $this->setMsg(1011);
    }

    /**
     * 老师上课初始化数据接口
     */
    public function pagePlanInfo(){
        $planId = !empty($this->paramsInfo['params']['planId']) ? (int)$this->paramsInfo['params']['planId'] : 0;
        if(empty($planId)) return $this->setMsg(1000);
        
        $userReg = user_api::loginedUser();
        if(!$userReg) return $this->setMsg(1003);

        $data = new interface_planinfo($planId);

        return $this->setData(get_object_vars($data));
    }
}
