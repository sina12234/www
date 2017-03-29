<?php
/*
 * chat传递消息接口
 */
class live_live extends STpl{
    public function pageThumb($inPath){
        if(empty($inPath[3])){
            return false;
        }
        $plan_id = $inPath[3];
        $params = array("plan_id"=>$plan_id);
        if(!empty($inPath[4])){
            $params["type"] = $inPath[4];
        }
        $ret = utility_services::call("/live/thumb/thumbbyplan", $params);
        if(!empty($ret->data)){
            header("Content-Type: image/jpeg");
            header("Accept-Ranges: bytes");
            return base64_decode($ret->data);
        }else{
            $ret = course_api::getCourseByPlan($plan_id);
            if(empty($ret->data)){
                return false;
            }else{
                $url = utility_cdn::filecdn() . "/" . $ret->data->thumb_small;
				header("Content-Type:image/jpg");
				utility_cache::pageCache(60);
                header("Location: $url");
            }
        }
    }
}
?>
