<?php
/**
 * 播放页笔记功能
 * @author zhouyu
 * date: 2016/10/31
 */
class interface_note extends interface_base
{
    /*
     * 获取笔记内容
     * @param fk_plan
     * @param videoStatus  播放状态 2直播  3录播
     * */
    public function pageNoteList(){
        $videoStatus = !empty($this->paramsInfo['params']['videoStatus']) ? $this->paramsInfo['params']['videoStatus'] : '';
        $planId = !empty($this->paramsInfo['params']['planId']) ? $this->paramsInfo['params']['planId'] : '';
        $uId = !empty($this->paramsInfo['params']['uId']) ? $this->paramsInfo['params']['uId'] : '';
        if(empty($videoStatus)){
            return interface_func::setMsg(1001); //videoStatus为空!
        }
        if(empty($uId)){
            return interface_func::setMsg(1001);
        }
        if(empty($planId)){
            return interface_func::setMsg(1001); //排课ID为空
        }
        //查询排课信息
        $planInfos = course_api::listPlan(array("plan_id" => $planId, "allcourse" => true));

        if(empty($planInfos)){
            return interface_func::setMsg(2017); //排课信息为空
        }
        if(empty($uId)){
            return interface_func::setMsg(1006);//用户ID为空
        }
        //学生是否报名本课程
        $courseUser = course_note_api::getCourseUser(array('fk_user'=>$uId,'fk_course'=>$planInfos->data[0]->pk_course,'fk_class'=>$planInfos->data[0]->class_id));
        if(empty($courseUser->items)){
            return interface_func::setMsg(2052);//未报名此课程
        }
        //视屏直播 class_id
        if($videoStatus == 2){
            //直播 不转换时间
            $params = array('fk_user'=>$uId,
                'course_id'=>$planInfos->data[0]->pk_course,
                'class_id'=>$planInfos->data[0]->class_id,
                'fk_plan'=>$planId,
                'status'=>1,
                'live_type'=>2 //2 直播  3 录播
            );
            $noteList = course_note_api::noteList($params);

            //直播时间格式转换
            if(!empty($noteList->result->data->items)){
            foreach($noteList->result->data->items as $k1 =>$v1){

                $v1->play_time_tmp_handle = $v1->play_time_tmp;
                //$nowTime = date('Y',time())."年";
                $nowTime = date("Y年m月d日",strtotime($v1->create_time));
                $his = $this->Sec2Time($v1->play_time_tmp);
                $dateTime = $his;
                if($v1->play_time_tmp == 0 || $v1->play_time_tmp == ''){
                    $v1->play_time_tmp_handle = 'error';
                }
                $v1->play_time_format = $dateTime;
            }
            }
        }
        if($videoStatus == 3){
            //录播
            $params = array('fk_user'=>$uId,
                'course_id'=>$planInfos->data[0]->pk_course,
                'class_id'=>$planInfos->data[0]->class_id,
                'fk_plan'=>$planId,
                'status'=>1,
                'live_type'=>3 //1 直播  2 录播
            );
            $noteList = course_note_api::noteList($params);
            if(!empty($noteList->result->data->items)) {
                //print_r($noteList->result->data->items);die;
                //绝对时间 转换相对时间；
                $vId = $planInfos->data[0]->video_id;
                //$vId = 489;
                $videoInfo = video_api::getVideoByIdArr(array($vId));
                $segsArr = !empty($videoInfo[0]->segs) ? json_decode($videoInfo[0]->segs) : '';

                foreach ($noteList->result->data->items as $key => $val) {
                    if (empty($segsArr)) {
                        // 没有裁剪
                        $val->play_time_tmp_handle = $val->play_time;
                        if ($val->play_time == 0 || $val->play_time == '') {
                            $val->play_time_tmp_handle = 'error';
                            $val->tailor = 'notailor';
                        }
                    } else {
                        // 裁剪
                        // 绝对时间转换相对时间 theoryReal
                        $realTime = $this->realTheory($val->play_time, $segsArr);
                        $val->play_time_tmp_handle = $realTime;
                        if (empty($realTime)) {
                            //添加已裁剪标示
                            $val->play_time_tmp_handle = 'error';
                            $val->tailor = 'tailor';
                        } else {
                            $val->tailor = 'notailor';
                            if ($val->play_time_tmp_handle == 'error') {
                                $val->tailor = 'tailor';
                            }
                        }
                    }
                }

                //按照转换后的相对时间排序
                $arrSort = array();
                $sort = array(
                    'direction' => 'SORT_ASC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
                    'field' => 'play_time_tmp_handle',       //排序字段
                );
                foreach ($noteList->result->data->items AS $uniqid => $row) {
                    foreach ($row AS $key => $value) {
                        $arrSort[$key][$uniqid] = $value;
                    }
                }
                if ($sort['direction']) {
                    array_multisort($arrSort[$sort['field']], constant($sort['direction']), $noteList->result->data->items);
                }
                //时间格式转换
                foreach ($noteList->result->data->items as $k => $v) {
                    $v->play_time_format = $this->Sec2Time($v->play_time_tmp_handle);
                }
            }
        }
        $list = array();
        $array = array();
        $list['items']=$noteList->result->data->items;
        if(!empty($noteList->result->data->items)){
            $list['totalSize']=(int)$noteList->result->data->totalSize;
            foreach($list['items'] as $k =>$v){
                $array[$k]['id'] = $v->id;
                $array[$k]['courseId'] = $v->course_id;
                $array[$k]['planId'] = $v->fk_plan;
                $array[$k]['userId'] = $v->fk_user;
                $array[$k]['createTime'] = $v->create_time;
                $array[$k]['lastUpdated'] = $v->last_updated;
                $array[$k]['status'] = $v->status;
                $array[$k]['content'] = $v->content;
                $array[$k]['playTime'] = $v->play_time;
                $array[$k]['playTimeTmp'] = $v->play_time_tmp;
                $array[$k]['playTimeTmpHandle'] = $v->play_time_tmp_handle;
                $array[$k]['playTimeFormat'] = $v->play_time_format;
            }
        }
        $list['items'] = $array;
        if($noteList->result->code == 200){
            return interface_func::setData($list);
        }else{
            return interface_func::setMsg(1);
        }
    }

    /* 删除笔记
     * @param note_id    笔记ID
     * @param plan_id    planId
     * @param videoStatus  播放状态 2直播  3录播
     * */
    public function pageDelNote(){
        $noteId = !empty($this->paramsInfo['params']['noteId'])?$this->paramsInfo['params']['noteId']:'';
        $planId = !empty($this->paramsInfo['params']['planId'])?$this->paramsInfo['params']['planId']:'';
        $videoStatus = !empty($this->paramsInfo['params']['videoStatus'])?$this->paramsInfo['params']['videoStatus']:'';
        $uId = !empty($this->paramsInfo['params']['uId'])?$this->paramsInfo['params']['uId']:'';
        if(empty($noteId)){
            return interface_func::setMsg(1001); //笔记ID为空
        }
        if(empty($planId)){
            return interface_func::setMsg(1001); //PlanID为空
        }
        if(empty($videoStatus)){
            return interface_func::setMsg(1001); //播放状态为空
        }
        $this->user = user_api::loginedUser();
        if(empty($uId)){
            return interface_func::setMsg(1001);//登录失败
        }
        $params = array('id'=>$noteId,'fk_user'=>$uId,'plan_id'=>$planId,'live_type'=>$videoStatus);
        $delNote = course_note_api::DelNote($params);
        if($delNote->result->code == 200){
            return interface_func::setMsg(0);
        }else{
            return interface_func::setMsg(1);
        }
    }

    /* 编辑笔记
     * @param note_id    笔记ID
     * @param plan_id    planId
     * @param content    笔记内容
     * @param videoStatus  播放状态 2直播  3录播
     * */
    public function pageUpdateNote(){
        $noteId = !empty($this->paramsInfo['params']['noteId'])?$this->paramsInfo['params']['noteId']:'';
        $content = isset($this->paramsInfo['params']['content'])?$this->paramsInfo['params']['content']:'';
        $planId = !empty($this->paramsInfo['params']['planId'])?$this->paramsInfo['params']['planId']:'';
        $videoStatus = !empty($this->paramsInfo['params']['videoStatus'])?$this->paramsInfo['params']['videoStatus']:'';
        $uId = !empty($this->paramsInfo['params']['uId'])?$this->paramsInfo['params']['uId']:'';
        if(empty($noteId)){
            return interface_func::setMsg(1001); //笔记ID为空
        }
        if(empty($uId)){
            return interface_func::setMsg(1001); //用户ID为空
        }
        if(!isset($content)){
            return interface_func::setMsg(1001); //笔记内容D为空
        }
        $strnum =   utility_tool::stringNum($content);
        if($strnum > 200 ){
            return interface_func::setMsg(2054); //笔记内容100汉字
        }
        if(empty($uId)){
            return interface_func::setMsg(1006); //登录失败
        }
        $params = array('id'=>$noteId,'fk_user'=>$uId,'content'=>$content,'plan_id'=>$planId,'live_type'=>$videoStatus);

        $UpdateNote = course_note_api::UpdateNote($params);
        if($UpdateNote->result->code == 200){
            return interface_func::setMsg(0);
        }else{
            return interface_func::setMsg(1);
        }
    }


    /* 添加笔记  判断登录  报名
     * @param play_time_tmp  flash相对时间
     * @param plan_id        planId
     * @param content        笔记内容
     * @param videoStatus    2直播  3录播
     * */
    public function pageNoteAdd(){
        //2 直播  3  录播
        $videoStatus = !empty($this->paramsInfo['params']['videoStatus']) ? $this->paramsInfo['params']['videoStatus'] : '';
        $planId = !empty($this->paramsInfo['params']['planId']) ? $this->paramsInfo['params']['planId'] : '';
        $content = isset($this->paramsInfo['params']['content']) ? $this->paramsInfo['params']['content'] : '';
        $play_time_tmp = isset($this->paramsInfo['params']['playTimeTmp']) ? $this->paramsInfo['params']['playTimeTmp'] : '';
        $uId = isset($this->paramsInfo['params']['uId']) ? $this->paramsInfo['params']['uId'] : '';
        $this->user = user_api::loginedUser();
        if( empty($uId) ){
            return interface_func::setMsg(1001);
        }
        if( empty($uId) ){
            return interface_func::setMsg(1006);
        }
        if( empty((int)$planId) ){
            return interface_func::setMsg(1001);
        }
        if( empty($play_time_tmp) ){
            return interface_func::setMsg(1001);
        }
        if( $play_time_tmp < 0 ){
            return interface_func::setMsg(4004);
        }
        if( empty($content) ){
            return interface_func::setMsg(1001);
        }
        if(!empty($content)){
            $strnum =   utility_tool::stringNum($content);
            if($strnum > 200 ){
                return interface_func::setMsg(2054);//100汉字
            }
        }
        if(empty($videoStatus)){
            return interface_func::setMsg(1001);
        }
        //查询排课信息
        $planInfos = course_api::listPlan(array("plan_id" => $planId, "allcourse" => true));
        if(empty($planInfos)){
            return interface_func::setMsg(2017); //无排课信息
        }
        //一节课最多50条笔记
        $selNoteCount = course_note_api::noteCount(array("fk_plan" => $planId, "fk_user" => $uId));
        if($selNoteCount->totalSize > 50){
            return interface_func::setMsg(2053); //笔记已达上限
        }
        //学生是否报名本课程
        $courseUser = course_note_api::getCourseUser(array('fk_user'=>$uId,'fk_course'=>$planInfos->data[0]->pk_course,'fk_class'=>$planInfos->data[0]->class_id));
        if(empty($courseUser->items)){
            return interface_func::setMsg(2052); //未报名此课程
        }
        //视屏直播
        if($videoStatus == 2){
            //直播 不转换时间
            $time = $play_time_tmp;
            //格式化时间  前端显示
            //$nowTime = date('Y年m月d日',time());
//            if(date('Y年',$play_time_tmp) == $nowTime){
//                //今年显示
//                $dateTime = date('m月:d日 H:i:s',$play_time_tmp);
//            }else{
//                $dateTime = date('Y年:m月:d日 H:i:s',$play_time_tmp);
//            }
            $his = $this->Sec2Time($time);
            $dateTime = $his;
        }
        //视频录播
        if($videoStatus == 3){
            //录播 转换时间
            //判断是否裁剪视频  video_id
            $vId = $planInfos->data[0]->video_id;
            $videoInfo = video_api::getVideoByIdArr(array($vId));
            $segsArr = !empty($videoInfo[0]->segs)?json_decode($videoInfo[0]->segs):'';
            if(empty($segsArr)){
                // 没有裁剪
                $time = $play_time_tmp;
                //格式化时间 前端展示
                $dateTime = $this->Sec2Time($play_time_tmp);
            }else{
                // 裁剪
                // 相对时间转换绝对时间 theoryReal
                $realTime = $this->theoryReal($play_time_tmp,$segsArr);
                $time = $realTime;
                //格式化时间 前端展示
                $dateTime = $this->Sec2Time($play_time_tmp);
            }
        }

        //添加笔记处理
        if( empty($planInfos->data[0]->pk_course) ){
            return interface_func::setMsg(2055);//课程ID为空
        }
        if( empty($planInfos->data[0]->class_id) ){
            return interface_func::setMsg(2032);//该课程下没有此班级
        }
        $nowTime = date('Y-m-d H:i:s',time());
        $params = array(
            'course_id'=>$planInfos->data[0]->pk_course,
            'fk_plan'=>$planId,
            'fk_user'=>$uId,
            'create_time'=>$nowTime,
            'class_id'=>$planInfos->data[0]->class_id,
            'content'=>$content,
            'play_time'=>$time,
            'play_time_tmp'=>$play_time_tmp, //flash相对时间
            'live_type'=>$videoStatus,
        );
        $noteAdd = course_note_api::noteAdd($params);
        if($noteAdd->result->code == 200){
            $list = array();
            $list['noteId'] = $noteAdd->result->data;
            $list['playTimeFormat']=$dateTime;
            $list['playTimeTmpHandle']=$play_time_tmp;
            return interface_func::setData($list);
        }else{
            return interface_func::setMsg(1);
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

    //相对时间转换真实时间 theory
    public function theoryReal($time,$segsArr){
        $realTime = 0;
        if(!empty($segsArr)){
            foreach($segsArr as $key => $val){
                if($time+$val[0] <= $val[1] ){
                    $realTime = $time + $val[0];
                    if($time+$val[0]==$val[0]){
                        $realTime-=1;
                    }
                    break;
                }
                $time = $time-($val[1] - $val[0]);
            }
        }
        return $realTime;
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


}
