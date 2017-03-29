<?php
/**
 * Created by PhpStorm.
 * User: longhouan
 * Date: 2016/11/15
 * Time: 16:19
 */
class interface_video extends interface_base
{
    /* 获取打点信息列表 */
    public function pageGetTeacherPoint($inPath)
    {
        $params['pid'] = empty($this->paramsInfo['params']['planId']) ? 0 : (int)$this->paramsInfo['params']['planId'];
        $params['rtime'] = empty($this->paramsInfo['params']['rtime']) ? 0 : (int)$this->paramsInfo['params']['rtime'];

        if(empty($params['pid'])){
            return interface_func::setMsg(1001); //排课ID为空
        }
        $params['order'] = "point_time ASC";
        $pointList = video_api::getTeacherPointList($params);
        $listArr=array();
        if(!empty($pointList->result)){
            $vId = $pointList->result[0]->vid;
            $videoInfo = video_api::getVideoByIdArr(array($vId));
            $segsArr = !empty($videoInfo[0]->segs) ? json_decode($videoInfo[0]->segs) : '';
            foreach($pointList->result as $list){
                $play_time_tmp_handle = $list->ptime;
                if (!empty($segsArr)) {
                    // 裁剪
                    // 绝对时间转换相对时间 theoryReal
                    $realTime = $this->realTheory($list->ptime, $segsArr);
                    $play_time_tmp_handle = $realTime;
                }
                if($play_time_tmp_handle!='error'&&!empty($play_time_tmp_handle)) {
                    $listArr["items"][] = array(
                        'pTime' => $list->ptime,
                        'content' => $list->content,
                        'playTimeTmpHandle' => $play_time_tmp_handle,
                        'playTimeFormat' => $this->Sec2Time($play_time_tmp_handle)
                    );
                }
            }
        }
        if(!empty($listArr)) {
            return interface_func::setData($listArr);
        }else{
            return interface_func::setData(array('items'=>array()));
        }
        return interface_func::setMsg(1);
    }

    //真实时间转换相对时间
    public function realTheory($time,$segsArr){
        $time1 = $time;
        $ds = 0;
        $ds1 = '';
        $ds2 = 0;
        if(!empty($segsArr)){
            $i=1;
            foreach($segsArr as $key => $val){
                $ds1 += $val[0] - $ds2;
                if( ($time - ($val[1]-$val[0]+$ds1)) > 0 ){
                    $time = $time - ($val[1]-$val[0]+$ds1);
                    $ds = $time;

                }else{
                    //echo $ds."-";
                    if($val[0]>0 && $i == 1){
                        $ds = $time-$val[0];
                    }else{
                        $ds = $ds-($val[0]-$ds2);
                    }

                    if($time1>=$val[0] && $time1 <= $val[1]) {
                        if ($ds == 0) {
                            $ds = $time;
                        }
                        //echo $ds.',';
                        $ds = $val[0] + $ds-$ds1;
                    }else{
                        //相对时间不在区间内
                        $ds = 'error';
                    }

                    return $ds;break;exit;
                }
                $ds2 = $val[1];
                $i++;
            }
        }
    }

    //时间格式转换 时分秒
    public function Sec2Time($time){
        if(is_numeric($time)){
            $value = array(
                "hours" => 0,
                "minutes" => 0, "seconds" => 0,
            );
            if($time >= 3600){
                $value["hours"] = floor($time/3600);
                $time = ($time%3600);
            }
            if($time >= 60){
                $value["minutes"] = floor($time/60);
                $time = ($time%60);
            }
            $value["seconds"] = floor($time);
            //return (array) $value;
            $t = "";
            if(!empty($value["hours"])){
                if($value["hours"] < 10){
                    $t .= '0'.$value["hours"].":";
                }else{
                    $t .= $value["hours"] .":";
                }
            }else{
                $t .= "00" .":";
            }

            if(!empty($value["minutes"])){
                if($value["minutes"] < 10){
                    $t .= '0'.$value["minutes"].":";
                }else{
                    $t .= $value["minutes"] .":";
                }
            }else{
                $t .= '00' .":";
            }

            if(!empty($value["seconds"])){
                if($value["seconds"] < 10){
                    $t .= '0'.$value["seconds"];
                }else{
                    $t .= $value["seconds"];
                }

            }else{
                $t .= '00';
            }
            Return $t;

        }else{
            return (bool) FALSE;
        }
    }
}