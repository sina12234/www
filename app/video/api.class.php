<?php
class video_api{
    public static function getVideoByIdArr($vIdArr){
        $params=array(
            "videoIdArr"=>$vIdArr
        );
        $ret=utility_services::call("/video/info/GetListByVideoArr", $params);
        if (!empty($ret->result->items)) {
            return $ret->result->items;
        }
        return false;
    }
    
    /* 获取视频打点截图列表N张 */
    public static function getVideoThumbsPointTime($params){
        $ret = utility_services::call("/video/point/getVideoThumbsPointTime", $params);

        return $ret;
    }
    
    /* 打点保存修改截图+删除打点 */
    public static function updateTeacherPoint($params){
        $ret = utility_services::call("/video.point.UpdateTeacherPoint", $params);

        return $ret;
    }
    
    /* 保存打点 */
    public static function saveTeacherPoint($params){
        $ret = utility_services::call("/video.point.AddTeacherPoint", $params);

        return $ret;
    }
    
	public static function partVideoTeacherPoint($params){
            
		$ret = utility_services::call("/video.point.PartVideoTeacherPoint", $params);

		return $ret;
	}   
    
	public static function changeVideoTeacherPoint($params){
            
		$ret = utility_services::call("/video.point.ChangeVideoTeacherPoint", $params);

		return $ret;
	}   
    
    /*格式化时间 : 时分秒 60:60:60 */
    public static function formatSecondToTime($sec){     
        $secondTime = $sec;
        $hour = $minute = $second =  '';
        $hour   = floor($sec/(60*60));
        $minute = floor(($sec - $hour*(60*60))/(60));
        $second = floor(($sec - $hour*(60*60) - $minute*(60)));
        
        if($hour<10) $hour = '0'.$hour;
        if($minute<10) $minute = '0'.$minute;
        if($second<10) $second = '0'.$second;
        
        $secondTime = $hour.':'.$minute.':'.$second;
        
        return $secondTime;
    }
     /* 根据剪辑视频段格式化原视频时间轴&剪辑时间轴
      * $params=['totalWidth'=>$totalWidth,'totalTime'=>$totaltime,'segsTotalTime'=>$segs_totaltime,'segs'=>$segs];
      *  */
    public static function formatSegTimeAxis($params){     
        $totalWidth     = empty($params['totalWidth']) ? 0 :(int) $params['totalWidth']; // 总宽度
        $totalTime      = empty($params['totalTime']) ? 0 :(int) $params['totalTime']; // 总时长
        $segsTotalTime  = empty($params['segsTotalTime']) ? 0 :(int) $params['segsTotalTime']; // 总剪辑时长
        $segs           = empty($params['segs']) ? '' : $params['segs'] ; //  剪辑段
        $segs           = str_replace('"', '', substr($segs,1,-1));
        if(empty($totalWidth) || empty($totalTime) || empty($segsTotalTime) || empty($segs)) return false; 
        
        if(is_array($segs)){
            $segsArr = $segs;
        } else {
            $segsArr = explode('],', $segs);   
        }
        
        $segMin = 0;  $segMax = $totalTime;
        $segDurationSum = 0;
        $segCount = count($segsArr);       
        $k = 1;
        for($j=0; $j<$segCount; $j++){
            if (empty($segsArr[$j])) return false;                                                   // 非法seg段
            $segiStr = str_replace(['[',']'], '', $segsArr[$j]);
            $segiArr   = explode(',', $segiStr);
            if (empty($segiArr[0]) || empty($segiArr[1]) || $segiArr[0]>=$segiArr[1] ) return false; // 非法seg段
            
            $segiA = $segiArr[0];
            $segiB = $segiArr[1];
            // 被剪辑段
            if($segiA>$segMin ) {      
                if($segiA>$segMin){
                    $segiNA = $segMin;
                    $segiNB = $segiA;
                }
                
                $segiDurationNew = $segiNB - $segiNA;     
                $segTimeAxisInfoNew = [];
                $segTimeAxisInfoNew['segBegin']    = $segiNA;
                $segTimeAxisInfoNew['segEnd']      = $segiNB;
                $segTimeAxisInfoNew['width']       = $segiDurationNew;
                $segTimeAxisInfoNew['finalWidth']  = ($segiDurationNew /$totalTime) * $totalWidth ;
                $segTimeAxisInfoNew['segWidth']    = ($segiDurationNew /$segsTotalTime) * $totalWidth ;
                $segTimeAxisInfoNew['isSeg']       = 0;   
                $oriTimeAxisArr[$k] = $segTimeAxisInfoNew; 
                $segMin = $segiB;
                $segDurationSum += $segiDurationNew; 
                $k++;
            } 
            // 原剪辑视频段
            $segiDuration = $segiB - $segiA;      
            $segTimeAxisInfo = [];
            $segTimeAxisInfo['segBegin']    = $segiA;
            $segTimeAxisInfo['segEnd']      = $segiB;
            $segTimeAxisInfo['width']       = $segiDuration;
            $segTimeAxisInfo['totalWidth']  = ($segiDuration /$totalTime) * $totalWidth ;
            $segTimeAxisInfo['segWidth']    = ($segiDuration /$segsTotalTime) * $totalWidth ;
            $segTimeAxisInfo['isSeg']       = 1;     
            $oriTimeAxisArr[$k] = $segTimeAxisInfo;  
            $segTimeAxisArr[$j] = $segTimeAxisInfo; 
            $segDurationSum += $segiDuration;
            $k++;
        }   
         // 被剪辑段
        if($segDurationSum<$segMax ) {      
            $segiNA = $segDurationSum;
            $segiNB = $segMax;

            $segiDurationNew = $segiNB - $segiNA;     
            $segTimeAxisInfoNew = [];
            $segTimeAxisInfoNew['segBegin']    = $segiNA;
            $segTimeAxisInfoNew['segEnd']      = $segiNB;
            $segTimeAxisInfoNew['width']       = $segiDurationNew;
            $segTimeAxisInfoNew['totalWidth']  = ($segiDurationNew /$totalTime) * $totalWidth ;
            $segTimeAxisInfoNew['segWidth']    = ($segiDurationNew /$segsTotalTime) * $totalWidth ;
            $segTimeAxisInfoNew['isSeg']       = 0;   
            $oriTimeAxisArr[$k] = $segTimeAxisInfoNew; 
            $segMin = $segiB;
            $segDurationSum += $segiDurationNew; 
        } 

        $timeAxisArr = array(
            'totalWidth'    => $totalWidth,
            'totalTime'     => $totalTime,
            'segsTotalTime' => $segsTotalTime,
            'oriTimeAxis'   => $oriTimeAxisArr,
            'segTimeAxis'   => $segTimeAxisArr
        );
        return $timeAxisArr;
    }

    /* 获取打点信息列表 */
    /* 获取视频打点信息列表 */
    public static function getTeacherPointList($params)
    {
        $ret = utility_services::call("/video.point.getTeacherPointList", $params);

        return $ret;
    }

    /* 获取打点信息9张小图 */
    public static function getPointThumbInfo($params){
        $ret = utility_services::call("/video.point.getPointThumbInfo", $params);

        return $ret;
    }

    /**
     * 视频列表
     * @param int $planId
     * @param int $userId 暂时不用但是必须有直(看api接口)
     * @return mixed
     */
    public static function getVideoList($planId, $userId=1){
        if(empty($planId)) return false;
        $ret = utility_services::call("/live.file.listupload/".$userId.'/'.$planId);
        if(!empty($ret->result)) return false;

        if(empty($ret->data)){
            $ret = utility_services::call("/live.file.listrecord/".$userId.'/'.$planId);
            if(!empty($ret->result)) return false;
        }

        return $ret->data;
    }
}
?>
