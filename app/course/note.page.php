<?php
/**
 * 播放页笔记功能
 * @author zhouyu
 * date: 2016/9/22
 */
class course_note extends STpl
{
    private static $domain;
    private static $org;
    private static $orgInfo;
    private static $orgOwner;
    private static $pageSize = 50;

    //是否登录 是否报名 是否引导
    //笔记引导 fk_guide 12
    public function pageNoteInfo(){
        $this->user = user_api::loginedUser();
        //判断登录 4320
        $planId = !empty($_POST['plan_id']) ? $_POST['plan_id'] : '';
        if(empty($planId)){
            echo json_encode(array('code'=>-101,'msg'=>'排课ID为空','data'=>'false'));exit;
        }
        if(empty($this->user['uid'])){
            echo json_encode(array('code'=>-102,'msg'=>'用户ID为空','data'=>'false'));exit;
        }
        $planInfos = course_api::listPlan(array("plan_id" => $planId, "allcourse" => true));
        if(!empty($planInfos->data)){
            $courseId = $planInfos->data[0]->course_id;
            //判断报名
            $param = array('fk_course'=>$courseId,'fk_user'=>$this->user['uid']);
            $signUp = course_note_api::studentSinUp($param);
            if(!empty($signUp->items)){
                    $uid = $this->user['uid'];
                    $userGuide=user_api::getUserGuideByUid($uid,12);
                    if(!empty($userGuide)){
                        $tips = 'false';
                    }else{
                        $tips = 'true';
                    }
                    if(empty($userGuide)){
                        $guideArr=array(
                            'uid'=>$uid,
                            'gid'=>12,
                        );
                        //添加不需要返回值
                        user_api::addUserGuide($guideArr);
                    }
                    echo json_encode(array('code'=>200,'msg'=>'success','data'=>$tips));exit;
            }else{
                echo json_encode(array('code'=>-103,'msg'=>'没有报名此课程','data'=>'false'));exit;
            }
        }

    }

    //添加笔记  判断登录  报名
    public function pageNoteAdd(){
        //2 直播  3  录播
        $videoStatus = !empty($_POST['videoStatus']) ? $_POST['videoStatus'] : '';
        $planId = !empty($_POST['plan_id']) ? $_POST['plan_id'] : '';
        $content = isset($_POST['content']) ? $_POST['content'] : '';
        $play_time_tmp = isset($_POST['play_time_tmp']) ? $_POST['play_time_tmp'] : '';
        $this->user = user_api::loginedUser();

        if(empty($this->user['uid'])){
            return json_encode(array('code'=>-102,'msg'=>'用户ID为空'));
        }
        if( empty((int)$planId) ){
            return json_encode(array('code'=>-101,'msg'=>'plan_id is null'));
        }
        if( empty($play_time_tmp) ){
            return json_encode(array('code'=>-103,'msg'=>'play_time_tmp is null'));
        }
        if( empty($content) ){
            return json_encode(array('code'=>-104,'msg'=>'content is null'));
        }
        if(!empty($content)){
            //header("Content-type: text/html; charset=utf-8");
            $strnum =   utility_tool::stringNum($content);
            if($strnum > 100 ){
                return json_encode(array('code'=>-104,'msg'=>'content > 100'));
            }
        }
        if(empty($videoStatus)){
            return json_encode(array('code'=>-105,'msg'=>'status is null'));
        }


        //查询排课信息
        $planInfos = course_api::listPlan(array("plan_id" => $planId, "allcourse" => true));
        if(empty($planInfos)){
            return json_encode(array('code'=>-109,'msg'=>'没有排课信息!'));
        }
        //一节课最多50条笔记
        $selNoteCount = course_note_api::noteCount(array("fk_plan" => $planId, "fk_user" => $this->user['uid']));
        if($selNoteCount->totalSize > 50){
            return json_encode(array('code'=>-108,'msg'=>'笔记已达上限'));
        }
        //学生是否报名本课程
        $courseUser = course_note_api::getCourseUser(array('fk_user'=>$this->user['uid'],'fk_course'=>$planInfos->data[0]->pk_course,'fk_class'=>$planInfos->data[0]->class_id));
        if(empty($courseUser->items)){
            return json_encode(array('code'=>-105,'msg'=>'未报名此课程!'));
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
            return json_encode(array('code'=>-106,'msg'=>'course_id is null'));
        }
        if( empty($planInfos->data[0]->class_id) ){
            return json_encode(array('code'=>-107,'msg'=>'class_id is null'));
        }

        $nowTime = date('Y-m-d H:i:s',time());
        $params = array(
            //'org_id'=>self::$orgOwner,
            'course_id'=>$planInfos->data[0]->pk_course,
            'fk_plan'=>$planId,
            'fk_user'=>$this->user['uid'],
            'create_time'=>$nowTime,
            'class_id'=>$planInfos->data[0]->class_id,
            'content'=>$content,
            'play_time'=>$time,
            'play_time_tmp'=>$play_time_tmp, //flash相对时间
            'live_type'=>$videoStatus,
        );
//        if($videoStatus == 3){
//            $params['play_time'] = $time;
//        }
        $noteAdd = course_note_api::noteAdd($params);
        $result = user_api::changeUserLevelAndScore($this->user['uid'],"NOTE_ADD");
        if($noteAdd->result->code == 200){
            return json_encode(array('code'=>200,'msg'=>'success','note_id'=>$noteAdd->result->data,
                             'play_time_format'=>$dateTime,'play_time_tmp_handle'=>$play_time_tmp,'play_time'=>$time,'date'=>$result));
        }else{
            return json_encode(array('code'=>-120,'msg'=>'error'));
        }
    }

    //添加笔记验证
    public function check($planId,$content,$play_time_tmp){
        if( empty((int)$planId) ){
            return json_encode(array('code'=>-101,'msg'=>'plan_id is null'));
        }
        if( !isset($play_time_tmp) ){
            return json_encode(array('code'=>-103,'msg'=>'play_time_tmp is null'));
        }
        if(!empty($content)){
            //header("Content-type: text/html; charset=utf-8");
            $strnum =   utility_tool::stringNum($content);
            if($strnum > 100 ){
                return json_encode(array('code'=>-104,'msg'=>'content > 100'));
            }
        }
    }

    public function pageTest(){

        $time = 1600;
        $vId = 1230;
        $videoInfo = video_api::getVideoByIdArr(array($vId));
        $segsArr = !empty($videoInfo[0]->segs)?json_decode($videoInfo[0]->segs):'';
        $arr =  $this->realTheory($time,$segsArr);
        print_r($arr);die;
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

    //删除笔记
    public function pageDelNote(){
        $noteId = !empty($_POST['note_id'])?$_POST['note_id']:'';
        $planId = !empty($_POST['plan_id'])?$_POST['plan_id']:'';
        $videoStatus = !empty($_POST['videoStatus'])?$_POST['videoStatus']:'';

        if(empty($noteId)){
            return json_encode(array('code'=>-101,'msg'=>'note_is is null'));
        }
        $this->user = user_api::loginedUser();
        if(empty($this->user['uid'])){
            return json_encode(array('code'=>-102,'msg'=>'用户ID为空'));
        }
        $params = array('id'=>$noteId,'fk_user'=>$this->user['uid'],'plan_id'=>$planId,'live_type'=>$videoStatus);
        $delNote = course_note_api::DelNote($params);
        if($delNote->result->code == 200){
            return json_encode(array('code'=>200,'msg'=>'success'));
        }else{
            return json_encode(array('code'=>-120,'msg'=>'error'));
        }


    }

    //编辑笔记
    public function pageUpdateNote(){

        $noteId = !empty($_POST['note_id'])?$_POST['note_id']:'';
        $content = isset($_POST['content'])?$_POST['content']:'';
        $planId = !empty($_POST['plan_id'])?$_POST['plan_id']:'';
        $videoStatus = !empty($_POST['videoStatus'])?$_POST['videoStatus']:'';
        if(empty($noteId)){
            return json_encode(array('code'=>-101,'msg'=>'note_is is null'));
        }
        if(!isset($content)){
            return json_encode(array('code'=>-102,'msg'=>'content_is is null'));
        }
        $strnum =   utility_tool::stringNum($content);
        if($strnum > 100 ){
            return json_encode(array('code'=>-103,'msg'=>'content > 100'));
        }
        $this->user = user_api::loginedUser();
        if(empty($this->user['uid'])){
            return json_encode(array('code'=>-102,'msg'=>'用户ID为空'));
        }
        $params = array('id'=>$noteId,'fk_user'=>$this->user['uid'],'content'=>$content,'plan_id'=>$planId,'live_type'=>$videoStatus);

        $UpdateNote = course_note_api::UpdateNote($params);
        if($UpdateNote->result->code == 200){
            return json_encode(array('code'=>200,'msg'=>'success'));
        }else{
            return json_encode(array('code'=>-120,'msg'=>'error'));
        }
    }

    //笔记列表
    public function pageNoteList(){
        $this->user = user_api::loginedUser();
        $videoStatus = !empty($_POST['videoStatus']) ? $_POST['videoStatus'] : '';
        $planId = !empty($_POST['plan_id']) ? $_POST['plan_id'] : '';
        if(empty($videoStatus)){
            return json_encode(array('code'=>-105,'msg'=>'videoStatus为空!'));
        }
        if(empty($planId)){
            return json_encode(array('code'=>-101,'msg'=>'排课ID为空!'));
        }
        //查询排课信息
        $planInfos = course_api::listPlan(array("plan_id" => $planId, "allcourse" => true));

        if(empty($planInfos)){
           return json_encode(array('code'=>-102,'msg'=>'排课信息为空!'));
        }
        if(empty($this->user['uid'])){
            return json_encode(array('code'=>-103,'msg'=>'用户ID为空'));
        }
            //学生是否报名本课程
        $courseUser = course_note_api::getCourseUser(array('fk_user'=>$this->user['uid'],'fk_course'=>$planInfos->data[0]->pk_course,'fk_class'=>$planInfos->data[0]->class_id));
        if(empty($courseUser->items)){
            return json_encode(array('code'=>-103,'msg'=>'未报名此课程!'));
        }
            //视屏直播 class_id
            if($videoStatus == 2){
                //直播 不转换时间
                $params = array('fk_user'=>$this->user['uid'],
                                 'course_id'=>$planInfos->data[0]->pk_course,
                                 'class_id'=>$planInfos->data[0]->class_id,
                                 'fk_plan'=>$planId,
                                 'status'=>1,
                                 'live_type'=>2 //2 直播  3 录播
                                );
                $noteList = course_note_api::noteList($params);

                //直播时间格式转换
                foreach($noteList->result->data->items as $k1 =>$v1){

                    $v1->play_time_tmp_handle = $v1->play_time_tmp;
                    //$nowTime = date('Y',time())."年";
                    $nowTime = date("Y年m月d日",strtotime($v1->create_time));

                    $his = $this->Sec2Time($v1->play_time_tmp);
                    $dateTime = $his;
                    if($v1->play_time_tmp == 0 || $v1->play_time_tmp == ''){
                        $v1->play_time_tmp_handle = 'error';
                    }
//                    if(date('Y年',$v1->play_time_tmp) == (date('Y',time())."年")){
//                     //今年显示
//                        $v1->play_time_format = date('m月:d日 H:i:s',$v1->play_time_tmp);
//                    }else{
//                        $v1->play_time_format = date('Y年:m月:d日 H:i:s',$v1->play_time_tmp);
//                    }
                    $v1->play_time_format = $dateTime;
                }
            }

            if($videoStatus == 3){

                //录播
                $params = array('fk_user'=>$this->user['uid'],
                    'course_id'=>$planInfos->data[0]->pk_course,
                    'class_id'=>$planInfos->data[0]->class_id,
                    'fk_plan'=>$planId,
                    'status'=>1,
                    'live_type'=>3 //1 直播  2 录播
                );
                $noteList = course_note_api::noteList($params);
                if(empty($noteList->result->data->items)){
                    return json_encode(array('code'=>-105,'msg'=>'没有笔记!'));
                }

                //绝对时间 转换相对时间；
                $vId = $planInfos->data[0]->video_id;
                //$vId = 489;
                $videoInfo = video_api::getVideoByIdArr(array($vId));
                $segsArr = !empty($videoInfo[0]->segs)?json_decode($videoInfo[0]->segs):'';

                foreach($noteList->result->data->items as $key => $val){
                    if(empty($segsArr)){
                        // 没有裁剪
                        $val->play_time_tmp_handle = $val->play_time;
                        if($val->play_time == 0 || $val->play_time == ''){
                            $val->play_time_tmp_handle = 'error';
                            $val->tailor = 'notailor';
                        }

                    }else{
                        // 裁剪
                        // 绝对时间转换相对时间 theoryReal
                        $realTime = $this->realTheory($val->play_time,$segsArr);
                        $val->play_time_tmp_handle = $realTime;
                        if(empty($realTime)){
                            //添加已裁剪标示
                            $val->play_time_tmp_handle = 'error';
                            $val->tailor = 'tailor';
                        }else{
                            $val->tailor = 'notailor';
                            if($val->play_time_tmp_handle == 'error'){
                                $val->tailor = 'tailor';
                            }
                        }
                    }
                }


                //按照转换后的相对时间排序
                $arrSort = array();
                $sort = array(
                    'direction' => 'SORT_ASC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
                    'field'     => 'play_time_tmp_handle',       //排序字段
                );
                foreach($noteList->result->data->items AS $uniqid => $row){
                    foreach($row AS $key=>$value){
                        $arrSort[$key][$uniqid] = $value;
                    }
                }
                if(!empty($noteList->result->data->items)){
                    if($sort['direction']){
                        array_multisort($arrSort[$sort['field']], constant($sort['direction']), $noteList->result->data->items);
                    }
                }
                //时间格式转换
                foreach($noteList->result->data->items as $k=> $v){
                    $v->play_time_format = $this->Sec2Time($v->play_time_tmp_handle);
                }
            }

        $list['items']=$noteList->result->data->items;
        if(!empty($noteList->result->data->items)){
            //$noteList->totalSize = (int)$noteList->totalSize;
            $list['totalSize']=(int)$noteList->result->data->totalSize;
        }

            if(empty($noteList->result->data->items)){
                return json_encode(array('code'=>-105,'msg'=>'没有笔记!'));
            }else{
                return json_encode(array('code'=>200,'msg'=>'success','data'=>$list));
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

    /**
     * @desc 添加笔记加分  每次两分每天4分封顶
     *
     * @param  $uid  用户ID
     * @param  $type 笔记
     * @return array
     */
    public function pageScoreAdd($uid,$type){
        $result = comment_api::addUserExperience(123456789,'NOTE_ADD');
        return $result;
    }

    /**
     * @desc 修改笔记加分  每次两分每天4分封顶
     *
     * @param  $uid  用户ID
     * @param  $type 笔记
     * @return array
     */
    public function pageScoreUpdate($uid,$type){
        $result = comment_api::addUserExperience(123456789,'NOTE_UPDATE');
        return $result;
    }

    /*     课程详情页笔记接口     */

    /* 课程详情页笔记列表展示
     * @params  ClassId 必传
     * @planId  非必传
     * @page    非必传
     * @return array
     * @link https://wiki.gn100.com/doku.php?id=web:point:coursedetailnotelist#post_body
     * */
    public function pageGetNoteListByPlanIdAndClassId(){
        $uId = !empty($_POST['fkUser']) ? $_POST['fkUser'] : '';
        $classId = !empty($_POST['classId']) ? $_POST['classId'] : '';
        $planId = !empty($_POST['planId']) ? $_POST['planId'] : '';
        $page = !empty($_POST['page']) ? $_POST['page'] : 1;
        if(empty($classId)){
            return json_encode(array('code'=>-201,'msg'=>'班级ID不能为空'));
        }
        if(empty($uId)){
            return json_encode(array('code'=>-202,'msg'=>'用户Id不能为空'));
        }
        $limit = 10;//分页数
        $noteList = course_api::GetNoteListByPlanIdAndClassId($classId,$planId,$uId,$page,$limit);
        foreach($noteList->result->items as $key => $val){
            //查询排课信息
            $planInfos = course_api::listPlan(array("plan_id" => $val->fk_plan, "allcourse" => true));
            //判断录播课 视频是否被隐藏
            if($planInfos->data[0]->status == 3 && $planInfos->data[0]->video_public_type == -2){
                $val->hidden = 'true';
            }else{
                $val->hidden = 'false';
            }
            //绝对时间 转换相对时间；
            $vId = $planInfos->data[0]->video_id;
            //$vId = 489;
            $videoInfo = video_api::getVideoByIdArr(array($vId));
            $segsArr = !empty($videoInfo[0]->segs)?json_decode($videoInfo[0]->segs):'';
            if(empty($segsArr)){
                // 没有裁剪
                $val->play_time_tmp_handle = $val->play_time;
                if($val->play_time == 0 || $val->play_time == ''){
                    $val->play_time_tmp_handle = 'error';
                    $val->tailor = 'notailor';
                }
            }else{
                // 裁剪
                // 绝对时间转换相对时间 theoryReal
                $realTime = $this->realTheory($val->play_time,$segsArr);
                $val->play_time_tmp_handle = $realTime;
                if(empty($realTime)){
                    //添加已裁剪标示
                    $val->play_time_tmp_handle = 'error';
                    $val->tailor = 'tailor';
                }else{
                    $val->tailor = 'notailor';
                    if($val->play_time_tmp_handle == 'error'){
                        $val->tailor = 'tailor';
                    }
                }
            }
            $val->play_time_format = $this->Sec2Time($val->play_time_tmp_handle);
            $val->url = "/course.plan.play/$val->fk_plan";
        }
        if($noteList->code == 0){
            return json_encode(array('code'=>0,'msg'=>'success','data'=>$noteList->result));
        }
    }
}