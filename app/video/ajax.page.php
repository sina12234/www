<?php

class video_ajax
{
    function __construct($inPath)
    {
        //如果没有登陆到登陆界面
        $this->user = user_api::loginedUser();
        if (empty($this->user)) {
            return interface_func::setMsg(1000);
        }
        $planId = $inPath[3];
        $org = user_organization::subdomain();
        $orgInfo = user_organization::getOrgByOwner($org->userId);
        $this->isAdmin = user_api::isAdmin($orgInfo->oid, $this->user["uid"]);
        //TODO判断用户是不是教师，如果不是，退出
        $resPlan = course_api::listPlan(array("plan_id"=>$planId,"allcourse"=>1));
        if(empty($resPlan->data) && $this->isAdmin==false){
            return interface_func::setMsg(1000);
        }
        $planInfo = $resPlan->data[0];
        $isTeacher = user_api::isTeacher($planInfo->user_id , $this->user['uid']);
        if(($this->user['uid'] != $planInfo->user_plan_id)&&($isTeacher===false) &&$this->isAdmin==false){
            return interface_func::setMsg(1000);
        }

    }
    /* 获取视频打点截图列表N张 */
    public function pageGetVideoThumbsPointTimeAjax($inPath){
        $inputData = $_REQUEST;
        $params['vid']   = empty($inputData['vid']) ? 0 : (int) $inputData['vid'];
        $params['ptime'] = empty($inputData['ptime']) ? 0 : (int) $inputData['ptime'];   // 原视频时间点
        $params['num']   = empty($inputData['num']) ? 1 : (int) $inputData['num'];       // 截图张数
        $params['rtime']   = empty($inputData['rtime']) ? 0 : (int) $inputData['rtime'];
        if(empty($params['vid']) || empty($params['ptime']) || empty($params['num'])) return interface_func::setMsg(1000);

        $ThumbsList = video_api::getVideoThumbsPointTime($params);

        if (!empty($ThumbsList->result)) {
            return $ThumbsList;
        } else {
            return interface_func::setMsg(5001,'获取数据失败');
        }
    }

    /* 打点保存修改截图 */
    public function pageUpdateTeacherPointAjax($inPath){
        $inputData = $_REQUEST;
        $params['pid']   = empty($inputData['pid']) ? 0 : (int) $inputData['pid'];
        $params['vid']   = empty($inputData['vid']) ? 0 : (int) $inputData['vid'];
        $params['ptime'] = empty($inputData['ptime']) ? 0 : (int) $inputData['ptime'];   // 原视频时间点
        $params['point_time'] = empty($inputData['point_time']) ? 0 : (int) $inputData['point_time'];   // 原视频时间点(新)

        if(empty($params['pid']) || empty($params['vid']) || empty($params['ptime']) || empty($params['point_time'])) return interface_func::setMsg(1000);

        $res = video_api::updateTeacherPoint($params);

        return $res;
    }

    /* 删除打点 */
    public function pageDelTeacherPointAjax($inPath){
        $inputData = $_REQUEST;
        $params['pid']   = empty($inputData['pid']) ? 0 : (int) $inputData['pid'];
        $params['vid']   = empty($inputData['vid']) ? 0 : (int) $inputData['vid'];
        $params['ptime'] = empty($inputData['ptime']) ? 0 : (int) $inputData['ptime'];   // 原视频时间点
        $params['st']    = empty($inputData['st']) ? -1 : (int) $inputData['st'] ;

        if(empty($params['pid']) || empty($params['vid']) || empty($params['ptime']) || empty($params['st'])) return interface_func::setMsg(1000);

        $res = video_api::updateTeacherPoint($params);

        return $res;
    }

    /* 保存打点 */
    public function pageSaveTeacherPointAjax($inPath){
        $inputData = $_REQUEST;
        $params['pid']   = empty($inputData['pid']) ? 0 : (int) $inputData['pid'];
        $params['vid']   = empty($inputData['vid']) ? 0 : (int) $inputData['vid'];
        $params['ptime'] = empty($inputData['ptime']) ? 0 : (int) $inputData['ptime'];   // 原视频时间点
        $params['content']  = isset($inputData['content'])  ? strip_tags($inputData['content']) : '';

        if (empty($params['uid'])) {
            $user = user_api::loginedUser();
            $params['uid'] = $user['uid'];
        }
        if(empty($params['pid']) || empty($params['vid']) || empty($params['uid']) || empty($params['ptime']) || empty($params['content'])) return interface_func::setMsg(1000);

        $res = video_api::saveTeacherPoint($params);

        return $res;
    }

    /* 获取打点信息列表 */
    public function pageGetTeacherPointAjax($inPath){
        $inputData = $_REQUEST;
        $params['pid']   = empty($inputData['pid']) ? 0 : (int) $inputData['pid'];
        $params['rtime']   = empty($inputData['rtime']) ? 0 : (int) $inputData['rtime'];
        $params['order'] = "point_time ASC";
        $pointList = video_api::getTeacherPointList($params);
        $newPointTime = array();
        $newPoint = 0;
        $vId = 0;
        if(!empty($pointList->result)){
            foreach($pointList->result as &$list){
                $vId = $list->vid;
                if(empty($newPointTime["ptime"])){
                    $newPointTime["ptime"] = strtotime($list->ctime);
                    $newPoint=(int)$list->ptime;
                }else{
                   if($newPointTime["ptime"]<strtotime($list->ctime)){
                       $newPointTime["ptime"]=strtotime($list->ctime);
                       $newPoint=(int)$list->ptime;
                   }
                }
                if(!empty($list->thumbs)){
                    $list->thumbs = json_decode($list->thumbs);
                    $list->thumbs->thumbs=utility_cdn::file($list->thumbs->thumbs);
                }
            }
            $videoInfo = video_api::getVideoByIdArr(array($vId));
            $segsArr = !empty($videoInfo[0]->segs)?json_decode($videoInfo[0]->segs):'';
            foreach($pointList->result as &$list) {
                if (!empty($segsArr)) {
                    $list->pointStatus = 1;//不在剪辑时间范围
                    foreach ($segsArr as $v) {
                        $a = (int)$v[0];
                        $b = (int)$v[1];
                        if ($list->ptime >= $a && $list->ptime <= $b) {
                            $list->pointStatus = 0;//在剪辑时间范围
                            break;
                        }
                    }
                }else{
                    $list->pointStatus = 0;//在剪辑时间范围
                }
                if($list->ptime==$newPoint){
                    $list->pointChecked= 1;//最新打点
                }else{
                    $list->pointChecked=0;//不是最新
                }
            }
        }
        return json_encode($pointList);
    }

    /* 获取打点信息n张小图 */
    public function pageGetPointThumbInfoAjax($inpath){
        $inputData = $_REQUEST;
        $params['pid']   = empty($inputData['pid']) ? 0 : (int) $inputData['pid'];
        $params['vid']   = empty($inputData['vid']) ? 0 : (int) $inputData['vid'];
        $params['thumb_num'] = empty($inputData['thumbNum']) ? 9 : (int) $inputData['thumbNum'];
        $params['ptime'] = empty($inputData['ptime']) ? 0 : (int) $inputData['ptime'];   // 原视频时间点

        if(empty($params['pid']) || empty($params['vid']) || empty($params['ptime'])) return interface_func::setMsg(1000);

        $res = video_api::getPointThumbInfo($params);
        if(!empty($res->result->thumbInfo)){
            foreach($res->result->thumbInfo as &$thumb){
                if(!empty($thumb->thumbs)){
                    $thumb->thumbs = utility_cdn::file($thumb->thumbs);
                }
            }
            return $res;

        }else{
            return interface_func::setMsg(5001,'获取数据失败');
        }
    }

    /* 获取课程班级章节名称 */
    public function pageGetNameByPidAjax($inpath){
        $planId = empty($_REQUEST["pid"])?0:$_REQUEST["pid"];
        $query = [
            'status'      => '1,2,3',
            'plan_id'  => "$planId"
        ];

        $params = [
            'f'  => [
                'section_name',
                'course_name',
                'class_name',
                'plan_id',
                'video_id',
                'totaltime'
            ],
            'q'  => $query,
            'ob' => ['start_time' => 'asc'],
            'p'  => 1,
            'pl' => 1,
        ];
        $planName = seek_api::seekPlan($params);
        $planNameInfo =!empty($planName->data[0])?$planName->data[0]:'';
        $planName = !empty($planNameInfo->section_name)?$planNameInfo->section_name:'';
        $courseName = !empty($planNameInfo->course_name)?$planNameInfo->course_name:'';
        $className = !empty($planNameInfo->class_name)?$planNameInfo->class_name:'';
        $planId = !empty($planNameInfo->plan_id)?$planNameInfo->plan_id:'';
        $videoId = !empty($planNameInfo->video_id)?$planNameInfo->video_id:'';
        $video = course_api::getCourseVideo($planId);
        $totalTime = !empty($video->totaltime)?$video->totaltime:0;
        return json_encode(array("code"=>0,"data"=>array("courseName"=>$courseName,"className"=>$className,"planName"=>$planName,"planId"=>$planId,"videoId"=>$videoId,"totalTime"=>$totalTime)));
    }

    /* 获取视频封面 */
    public function pageGetVideoCoverAjax($inpath){
        $planId = empty($_REQUEST["pid"])?0:$_REQUEST["pid"];
        $video = course_api::getCourseVideo($planId);
        if(empty($video)){
            return interface_func::setMsg(5001,'获取数据失败');
        }
        $thumbs = array();
        if(!empty($video->thumb1)){
            for($i=1;$i<=8;$i++){
                $key = "thumb$i";
                $thumbs[] = [
                    "path" => !empty($video->$key)?$video->$key:'',
                    "url" => !empty($video->$key)?utility_cdn::file($video->$key):'',

                ];
            }
        }
        $thumb0 = !empty($video->thumb0)?utility_cdn::file($video->thumb0):'';
        return json_encode(array("code"=>0,"data"=>array("thumb0"=>$thumb0,"thumbs"=>$thumbs)));
    }

    /* 获取视频剪辑时间段信息 */
    public function pageGetVideoSegAjax($inpath){
        $inputData = $_REQUEST;
        $params['vid']   = empty($inputData['vid']) ? 0 : (int) $inputData['vid'];
        $videoInfo = video_api::getVideoByIdArr(array($params['vid']));
        $segsArr = !empty($videoInfo[0]->segs)?json_decode($videoInfo[0]->segs):'';
        return json_encode(array("code"=>0,"data"=>$segsArr));
    }

    /* 获取视频播放页打点信息列表 */
    public function pageGetVideoPlayTeacherPointAjax($inPath){
        $inputData = $_REQUEST;
        $params['pid']   = empty($inputData['pid']) ? 0 : (int) $inputData['pid'];
        $params['rtime']   = empty($inputData['rtime']) ? 0 : (int) $inputData['rtime'];
        $params['order'] = "point_time ASC";
        if(empty($params['pid'])){
            return json_encode(array('code'=>1000,'data'=>array()));
        }
        $pointList = video_api::getTeacherPointList($params);
        $listArr=array();
        if(!empty($pointList->result)){
            foreach($pointList->result as $list){
                $listArr[]=array(
                    'pTime'=>$list->ptime,
                    'content'=>$list->content
                );
            }
        }
        if(empty($listArr)){
            return json_encode(array('code'=>5001,'data'=>$listArr));
        }
        return json_encode(array('code'=>0,'data'=>$listArr));
    }
}
