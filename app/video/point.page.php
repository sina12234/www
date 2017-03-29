<?php
class video_point extends STpl
{
    var $user;

    function __construct($inPath)
    {
        //如果没有登陆到登陆界面
        $this->user = user_api::loginedUser();
        if (empty($this->user)) {
            $this->redirect("/site.main.login");
        }
        $planId = $inPath[3];
        $type = $inPath[4];
        $org = user_organization::subdomain();
        $orgInfo = user_organization::getOrgByOwner($org->userId);
        $this->isAdmin = user_api::isAdmin($org->userId, $this->user["uid"]);
        //TODO判断用户是不是教师，如果不是，退出
        $resPlan = course_api::listPlan(array("plan_id"=>$planId,"allcourse"=>1));
        if(empty($resPlan->data) && $this->isAdmin==false){
            $this->redirect("/index.main.404");
        }
        $planInfo = $resPlan->data[0];
        $isTeacher = user_api::isTeacher($planInfo->user_id , $this->user['uid']);
        if(($this->user['uid'] != $planInfo->user_plan_id)&&($isTeacher===false) &&$this->isAdmin==false){
            $this->redirect("/index.main.404");
        }

    }
    //视频打点
    public function pageVideoPoint($inPath){
        if(empty($inPath[3]) || empty($inPath[4])){
            $this->redirect("/index.main.404");
        }

        $planId = (int)($inPath[3]);
        $status = (int)($inPath[4]);

        $planRes = $this->checkUploadAuthor($planId,$status);

        if($planRes->code != 1&&$this->isAdmin==false){
            $this->redirect("/video.point.VideoUpload/".$planId."/".$status);
        }

        $video = course_api::getCourseVideo($planId);

        if(empty($video)){
            $this->redirect("/video.point.VideoUpload/".$planId."/".$status);
        }
        $this->render("/video/video.point.html");
    }
    //视频裁剪
    public function pageVideoPart($inPath){
        if(empty($inPath[3]) || empty($inPath[4])){
            $this->redirect("/index.main.404");
        }

        $planId = (int)($inPath[3]);
        $status = (int)($inPath[4]);

        $planRes = $this->checkUploadAuthor($planId,$status);
        if($planRes->code != 1 && $this->isAdmin==false){
            $this->redirect("/video.point.VideoUpload/".$planId."/".$status);
        }

        $video = course_api::getCourseVideo($planId);

        if(empty($video)){
            $this->redirect("/video.point.VideoUpload/".$planId."/".$status);
        }
        $totalTime = !empty($video->totaltime)?$video->totaltime:0;
        $segsTotalTime = !empty($video->segs_totaltime)?$this->Sec2Time($video->segs_totaltime):"";
        $seg = array();
        if(!empty($video->segs)){
            $segs = json_decode($video->segs);
            $i = 1;
            $leftTime = 0;
            $left1 = 0;
            foreach($segs as $v){
                $left = ($v[0]-$leftTime)/$totalTime*100;
                if($left<0){
                    $left = ceil(abs($left))*-1;
                }else{
                    $left = ceil($left);
                }
                $left1 += $left;
                $width = ceil(($v[1]-$v[0])/$totalTime*100);
                $seg[$i] = [
                    'start' => gmstrftime('%H:%M:%S',$v[0]),
                    'end'   => gmstrftime('%H:%M:%S',$v[1]),
                    'width' => $width,
                    'left' =>$left1
                ];
                $left1 += $width;
                $leftTime=$v[1];
                $i++;
            }
        }

        $thumbs = array();
        if(!empty($video->thumb1)){
            for($i=1;$i<=8;$i++){
                $key = "thumb$i";
                $thumbs[] = $video->$key;
            }
        }
        /*
        $pointList = array();

        if(!empty($video->point)){
            foreach($video->point as $key => $val){
                $pointThumbs = json_decode($val->thumbs);
                $pointInfo = new stdClass();
                if(!empty($pointThumbs)) {
                    $itemValueLeft = $pointThumbs->width * ($pointThumbs->cols - 1);
                    $itemValueTop = $pointThumbs->height * ($pointThumbs->rows - 1);
                    $itemValueUrl = utility_cdn::file($pointThumbs->thumbs);
                    $thumbs_style = "width:{$pointThumbs->width}px;"
                        . "height:{$pointThumbs->height}px;"
                        . "display:inline-block;background:url({$itemValueUrl}) no-repeat -{$itemValueLeft}px -{$itemValueTop}px;";
                    $pointInfo->left = empty($pointThumbs->left) ? 0 : (int)$pointThumbs->left;
                    $pointInfo->top = empty($pointThumbs->top) ? 0 : (int)$pointThumbs->top;
                }
                    $pointInfo->ptime = $val->ptime;      // 原始视频打点时间（秒）

                    $pointInfo->point_time = video_api::formatSecondToTime($val->ptime);      // 剪辑视频打点时间
                    $pointInfo->content = $val->content;    // 打点注释内容
                    $pointInfo->thumbs_style = $thumbs_style; // 截图 style
                $pointList[] = $pointInfo;
            }
        }
        */
        $thumb0 = !empty($video->thumb0)?$video->thumb0:'';
        $sectionDescipt = !empty($planRes->data->section_descipt) ? mb_substr($planRes->data->section_descipt,0,25,'utf-8') : '';
        $this->assign('secDesc',$sectionDescipt);
        $this->assign('thumb0',$thumb0);
        $this->assign('thumbs',$thumbs);
        //$this->assign('pointList',$pointList);
        $this->assign('video',$video);
        $this->assign("plan_id",$planId);
        $this->assign("totaltime",$totalTime);
        $this->assign("plan_info",$planRes->data);
        $this->assign('status',$status);
        $this->assign("segs",$seg);
        $this->assign("segsTotalTime",$segsTotalTime);
        $this->render("/video/video.part.html");
    }
    //视频上传封面
    public function pageVideoPreview($inPath){
        if(empty($inPath[3]) || empty($inPath[4])){
            $this->redirect("/index.main.404");
        }

        $planId = (int)($inPath[3]);
        $status = (int)($inPath[4]);

        $planRes = $this->checkUploadAuthor($planId,$status);
        if($planRes->code != 1 && $this->isAdmin==false){
            $this->redirect("/video.point.VideoPreview/".$planId."/".$status);
        }

        $video = course_api::getCourseVideo($planId);

        if(empty($video)){
            $this->redirect("/video.point.VideoPreview/".$planId."/".$status);
        }
        $this->render("/video/video.preview.html");
    }
    //视频上传封面
    public function pageVideoCover($inPath){
        if(empty($inPath[3]) || empty($inPath[4])){
            $this->redirect("/index.main.404");
        }

        $planId = (int)($inPath[3]);
        $status = (int)($inPath[4]);

        $planRes = $this->checkUploadAuthor($planId,$status);

        if($planRes->code != 1&&$this->isAdmin==false){
            $this->redirect("/video.point.VideoUpload/".$planId."/".$status);
        }

        $video = course_api::getCourseVideo($planId);

        if(empty($video)){
            $this->redirect("/video.point.VideoUpload/".$planId."/".$status);
        }
        $this->render("/video/video.cover.html");
    }
    //视频上传
    public function pageVideoUpload($inPath){
        if(empty($inPath[3]) || empty($inPath[4])){
            $this->redirect("/index.main.404");
        }
        $planId = (int)($inPath[3]);
        $status = (int)($inPath[4]);
        $path = "/video.point.videoUpload/".$planId."/".$status."";
        $act = isset($inPath[5])?(int)($inPath[5]):0;
        $planRes = $this->checkUploadAuthor($planId,$status);
        if($planRes->code != 1 && $this->isAdmin==false){
            $this->redirect("/teacher.course.timetable");
        }

        $uploadFiles = live_file::listUploadFile(0,$planId);
        /*
        if($act==1){
            if(!empty($uploadFiles)) {
                foreach ($uploadFiles as $v) {
                    live_file::setUploadFileStatus($v->file_id);
                }
                $uploadFiles = live_file::listUploadFile(0, $planId);
            }
        }
        */
        $video       = course_api::getCourseVideo($planId);
        if($status == 1){
            $uid = $this->user['uid'];
        }else{
            $uid = $planRes->data->user_plan_id;
        }
        $uploadTask  = live_file::getUploadTask($uid,$planId);
        $uploadVideo = utility_cdn::upload_video();

        $sc = parse_url($uploadVideo,PHP_URL_SCHEME);
        if(empty($sc)){
            if(utility_net::isHTTPS()){
                $uploadVideo = "https:".$uploadVideo;
            }else{
                $uploadVideo = "http:".$uploadVideo;
            }
        }

        $sectionDescipt = !empty($planRes->data->section_descipt) ? mb_substr($planRes->data->section_descipt,0,25,'utf-8') : '';
        $this->assign('sectionDesc',$sectionDescipt);
        $this->assign('planUid',$uid);
        $this->assign("token",$this->user['token']);
        $this->assign("plan_id",$planId);
        $this->assign("uploadTask",$uploadTask);
        $this->assign("video",$video);
        $this->assign("plan_info",$planRes->data);
        $this->assign("uploadFiles",$uploadFiles);
        $this->assign("status",$status);
        $this->assign("upload_video",$uploadVideo);
        $this->assign("path",$path);
        $this->render("/video/video.upload.html");
    }

    /*
	 * 视频上传裁剪权限
	 * @params $planId 章节id
	 * @params $type 1 老师上传视频  2 管理员班主任上传视频
	 */
    private function checkUploadAuthor($planId,$type){
        $result = new stdClass;
        $resPlan = course_api::listPlan(array("plan_id"=>$planId,"allcourse"=>1));
        if(empty($resPlan->data)){
            $result->code = -2;
            return $result;
        }
        $planInfo = $resPlan->data[0];

        if($type == 1){
            if($this->user['uid'] != $planInfo->user_plan_id){
                $result->code = -3;
                return $result;
            }
        }
        if($type == 2){
            if(user_api::isTeacher($planInfo->user_id , $this->user['uid'])===false){
                $result->code = -3;
                return $result;
            }
        }

        $result->code = 1;
        $result->data = $planInfo;
        return $result;
    }

    private function Sec2Time($time){
        if(is_numeric($time)){
            $value = array(
                "years" => 0, "days" => 0, "hours" => 0,
                "minutes" => 0, "seconds" => 0,
            );
            if($time >= 31556926){
                $value["years"] = floor($time/31556926);
                $time = ($time%31556926);
            }
            if($time >= 86400){
                $value["days"] = floor($time/86400);
                $time = ($time%86400);
            }
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
            if(!empty($value["years"])){
                $t .=$value["years"] ."年";
            }
            if(!empty($value["days"])){
                $t .=$value["days"] ."天 ";
            }
            if(!empty($value["hours"])){
                $t .=$value["hours"] ."时";
            }
            if(!empty($value["minutes"])){
                $t .=$value["minutes"] ."分";
            }
            if(!empty($value["seconds"])){
                $t .=$value["seconds"] ."秒";
            }
            Return $t;

        }else{
            return (bool) FALSE;
        }
    }
}
