<?php

class index_teacherblog extends STpl
{

public function __construct($inPath)
{
    if (!isset($inPath[3]) || empty($inPath[3])) {
        $this->redirect('/');
    }

    $this->tid  = (int)($inPath[3]);

    $this->info = teacher_api::getTeacherInfo($this->tid);
    if (empty($this->info)) {
        $this->redirect('/');
    }

    $this->user = user_api::loginedUser();

    $this->uid = !empty($this->user['uid']) ? $this->user['uid'] : 0;
}

    public function pageEntry()
    {
        utility_cache::pageCache(600);
        $courseList = teacher_api::getTeacherCourse($this->tid, 0, 1, 4);
        $imgList = teacher_api::getImgList($this->tid, 1, 3);
        $articleList = teacher_api::getArticleListByTeacherId($this->tid, 1, 3);
		$ret = comment_api::teacherAverage($this->tid);
        $score = 0;
        $total_user = 0;
        if($ret->items){
            foreach($ret->items as $item){
                $score += $item->score;
                $total_user +=$item->total_user;
            }
        }
        $ret->total_user = $total_user;
        $ret->score = $score;
        $teacher_score = 0;
        $total_user = 0;
        if($ret){
            if(empty($ret->total_user)){
                $teacher_score = 0; 
            }else{
                $teacher_score = round($ret->score/$ret->total_user,1);
            } 
            $total_user = $ret->total_user ? $ret->total_user : 0;
        }
        if($teacher_score<0) $teacher_score=0;
        if($total_user<0) $total_user = 0;
		$teacher_score = $teacher_score ? $teacher_score : 0;
		$this->assign('total_user',$total_user);
		$this->assign('teacher_score',$teacher_score);
        $this->assign('courseList', $courseList);
        $this->assign('imgList', $imgList);
        $this->assign('articleList', $articleList);
        $this->assign('info', $this->info);
        $this->assign('tid', $this->tid);
        $this->assign('uid', $this->uid);

        $this->render("/site/new_teac_index.html");
    }
	public function pageCourse()
	{
		utility_cache::pageCache(600);
		$courseList = teacher_api::getTeacherCourse($this->tid);
		$ret = comment_api::teacherAverage($this->tid);
        $teacher_score = 0;
        $total_user = 0;
        $score = 0;
        if($ret->items){
            foreach($ret->items as $item){
                $score += $item->score;
                $total_user +=$item->total_user;
            }
        }
        $ret->total_user = $total_user;
        $ret->score = $score;
        if($ret){
            if(empty($ret->total_user)){
                $teacher_score = 0; 
            }else{
                $teacher_score = round($ret->score/$ret->total_user);
            } 
            $total_user = $ret->total_user ? $ret->total_user : 0;
        }
		$teacher_score = $teacher_score ? $teacher_score : 0;
        if($teacher_score<0) $teacher_score=0;
        if($total_user<0) $total_user = 0;
		
		$offCourse = 0;
		$courseOnCount = count($courseList);
		if(!empty($this->info->course_count) && !empty($courseList) && $this->info->course_count > $courseOnCount){
			$offCourse = 1;
		}
		$this->assign('offCourse',$offCourse);
		$this->assign('total_user',$total_user);
		$this->assign('teacher_score',$teacher_score);
        $this->assign('courseList', $courseList);
        $this->assign('info', $this->info);
        $this->assign('tid', $this->tid);
        $this->assign('uid', $this->uid);
        $this->assign('favTotal', 0);

        $this->render("/site/new_teac_courses.html");
    }

public function pageArticle($inPath)
{
    utility_cache::pageCache(600);
    if (isset($inPath[4]) && is_numeric($inPath[4]) && (int)($inPath[4])) {
        $tagId       = (int)($inPath[4]);
        $articleList = teacher_api::getArticle($this->tid, $tagId);

        if (empty($articleList)) {
            $this->redirect("/index/teacherblog/Article/{$this->tid}");
        }
    } else if (isset($inPath[4]) && $inPath[4] == 'draft') {
        $tagId = 'draft';
        $articleList = teacher_api::getArticle($this->tid, '', 1);
    } else {
        $tagId = 0;
        $articleList = teacher_api::getArticle($this->tid);
    }
    if(isset($articleList->tagLists->list) && !empty($articleList->tagLists->list)){
        foreach($articleList->tagLists->list as $k=>&$value){
            if(!isset($value->name)){
                unset($articleList->tagLists->list[$k]);
            }
        }
    }
    $userType = isset($this->user['types']->teacher) && $this->user['types']->teacher ? 1 : 0;

    $this->assign('list', $articleList);
    $this->assign('info', $this->info);
    $this->assign('tid', $this->tid);
    $this->assign('uid', $this->uid);
    $this->assign('tagId', $tagId);
    $this->assign('userType', $userType);
    $this->assign('favTotal', 0);

    $this->render("/site/new_teac_index_article.html");
}

public function pagePublish($inPath)
{
    if ($this->uid != $this->tid) {
        $this->redirect("/index/teacherblog/article/{$this->tid}");
    }

    if (isset($inPath[4], $inPath[5]) && ($inPath[4] == 'edit') && (int)($inPath[5])) {
        $articleInfo = teacher_api::getArticleDetail($inPath[5]);
        if (empty($articleInfo)) {
            $this->redirect("/index/teacherblog/article/{$this->tid}");
        }
        $this->assign('article', $articleInfo);
    }

    $tagList = utility_services::call("/blog/article/GetTagListByTeacherId/{$this->tid}");
    $this->assign('tagList', !empty($tagList->result) ? $tagList->result : '');
    $this->assign('info', $this->info);
    $this->assign('tid', $this->tid);
    $this->assign('uid', $this->uid);
    $this->assign('favTotal', 0);
    $this->assign('imgHost', utility_cdn::filecdn().'/');
    $this->render("/site/new_teac_index_textarea.html");
}

public function pageArticleDetail($inPath)
{
    utility_cache::pageCache(600);
    if (!is_numeric($inPath[4]) || !(int)($inPath[4])) {
        $this->redirect("/index/teacherblog/Article/{$this->tid}");
    }

    $id = (int)($inPath[4]);

    $articleInfo = teacher_api::getArticleDetail($id);
    if (empty($articleInfo)) {
        $this->redirect("/index/teacherblog/article/{$this->tid}");
    }

    $commentList = teacher_api::getArticleComment($id);

    $this->assign('info', $this->info);
    $this->assign('article', $articleInfo);
    $this->assign('commentList', $commentList);
    $this->assign('tag', teacher_api::getTagNameTotalList($this->tid));
    $this->assign('tid', $this->tid);
    $this->assign('aid', $id);
    $this->assign('uid', $this->uid);

    $this->render("/site/new_teac_article_detail.html");
}

public function pageComment($inPath)
{
    utility_cache::pageCache(600);
    $page = isset($_REQUEST["page"])? (int)($_REQUEST["page"]) : 1;
    $sort = 'desc';
    if (isset($inPath[4]) && $inPath[4]) {
        $sort = trim($inPath[4]);
    }
    $param = [
        'teacherId' => $this->tid,
        'page' => $page,
        'length' => 20,
        'sort' => $sort,
    ];
    $res = teacher_api::getTeacherCourseComment($param);
    $st = new stdclass();
    $st->pk_replay = '';
    $st->fk_comment = '';
    $st->fk_user = '';
    $st->manage_name = '';
    $st->contents = '';
    $st->replay_time = '';
    $st->status = '';
    if(isset($res['list']) && !empty($res['list'])){
        $commentIdArr = array();
        foreach($res['list'] as &$v){
            $v['time'] = date("Y")==date('Y',strtotime($v['time'])) ? date('m-d H:i',strtotime($v['time'])) :date('Y-m-d H:i',strtotime($v['time']));
            $commentIdArr[] = $v['commentId'];
        }
        $ret = comment_api::getTeacherReplayByCommentIdArr($commentIdArr);
        if(0==$ret->code){
            $replayArr = array();
            foreach($ret->result->items as $item){
                $replayArr[$item->fk_comment] = $item;
            }
            $replays = array_keys($replayArr);
            foreach($res['list'] as &$value){
                if(in_array($value['commentId'],$replays)){
                    $value['replay'] = $replayArr[$value['commentId']];
                    $value['replay']->replay_time = (date('Y')==date('Y',strtotime($value['replay']->replay_time)) ?  date('m-d H:i',strtotime($value['replay']->replay_time)) : date('Y-m-d H:i',strtotime($value['replay']->replay_time)));
                }else{
                    $value['replay'] = $st;
                }
            }
        }
    }
    $info = [
        'avg_score'     => $this->info->avg_score / 10,
        'desc_score'    => ceil($this->info->desc_score / 10),
        'student_score' => ceil($this->info->student_score / 10),
        'explain_score' => ceil($this->info->explain_score / 10),
    ];

    $org = user_organization::subdomain();
    $head = !empty($org->subdomain) ? $org->subdomain : 'www';
    $http = utility_net::isHTTPS() ? "https" : "http";
    $host = $http."://".user_organization::course_domain($head);
    $domainConf = SConfig::getConfig(ROOT_CONFIG.'/const.conf', 'platform');
    $path='/index/teacherblog/comment/'.$inPath[3];
    if(!empty($inPath[4])){
        $path .= '/'.$inPath[4];
    }
		$ret = comment_api::teacherAverage($this->tid);
        $score = 0;
        $total_user = 0;
        if($ret->items){
            foreach($ret->items as $item){
                $score += $item->score;
                $total_user +=$item->total_user;
            }
        }
        $ret->total_user = $total_user;
        $ret->score = $score;
        if($ret){
			if(empty($ret->total_user)){
				$score = 0;
			}else{
				 $score = round($ret->score/$ret->total_user,1);
			}
        }
		if(!isset($res['totalPage'])) $res['totalPage'] = 0;
		if(empty($res["list"]))  $res["list"] = 0;
        $this->assign('score',$score);
        $path_page = utility_tool::getUrl($path);
        $this->assign('teacInfo', $info);
        $this->assign('info', $this->info);
        $this->assign('tid', $this->tid);
        $this->assign('sort', $sort);
        $this->assign('list', $res["list"]);
        $this->assign('uid', $this->uid);
        $this->assign('favTotal', 0);
        $this->assign('platformUrl', $domainConf->platform);
        $this->assign("totalPage",$res['totalPage']);
        $this->assign("page",$page);
        $this->assign("path_page",$path_page);
        $this->assign("length",20);

        $this->render("/site/new_student_evaluate.html");
    }

    public function pageStyle()
    {
        utility_cache::pageCache(600);
        $userType = isset($this->user['types']->teacher) && $this->user['types']->teacher ? 1 : 0;

        $this->assign('info', $this->info);
        $this->assign('tid', $this->tid);
        $this->assign('uid', $this->uid);
        $this->assign('list', teacher_api::getImgList($this->tid));
        $this->assign('favTotal', 0);

        if ($userType && $this->uid == $this->tid) {
            $this->render("/site/new_teac_pic_up.html");
        } else {
            $this->render("/site/new_teac_pic_content.html");
        }

    }

    public function pageImgInfo($inPath)
    {
        utility_cache::pageCache(600);
        $imgId = 0;
        if (!empty($inPath[4])) {
            $imgId = (int)($inPath[4]);
        }

        $this->assign('info', $this->info);
        $this->assign('list', teacher_api::getImgList($this->tid));
        $this->assign('tid', $this->tid);
        $this->assign('uid', $this->uid);
        $this->assign('imgId', $imgId);
        $this->assign('favTotal', 0);

        $this->render("/site/new_teac_pic.html");
    }

    public function pageStyleDetail()
    {
        //utility_cache::pageCache(600);
        $res = teacher_api::getImgList($this->tid);

        $list = [];
        if (!empty($res)) {
            foreach ($res as $v) {
                $list[] = [
                    'imgId'     => $v->pk_teacher_img,
                    'name'      => $v->image_name,
                    'thumb_big' => interface_func::imgUrl($v->thumb_big),
                    'thumb_med' => interface_func::imgUrl($v->thumb_med),
                ];
            }
        }

        $this->assign('info', $this->info);
        $this->assign('tid', $this->tid);
        $this->assign('list', $list);
        $this->assign('uid', $this->uid);
        $this->assign('favTotal', 0);

        $this->render("/site/new_teac_pic.html");
    }

    public function pageNavHeader($inPath)
    {
        $userType = isset($this->user['types']->teacher) && $this->user['types']->teacher ? 1 : 0;
        $nav = isset($inPath[4]) && $inPath[4] ? $inPath[4] : '';

        $isFav = teacher_api::checkTeacherFav($this->uid, $this->tid);
        $preview_video = user_api::getPreviewVideoByUid($this->tid);
        $planid = '';
        if (!empty($preview_video->result)) {       
            foreach ($preview_video->result as $key => $value) {
                if (!empty($value->uid)) {
                    $planid = $value->planid;
                }
                break;
            }
        }
        $this->assign('isFav', $isFav);
        $this->assign('userType', $userType);
        $this->assign('info', $this->info);
        $this->assign('tid', $this->tid);
        $this->assign('uid', $this->uid);
        $this->assign('nav', $nav);
        $this->assign('favTotal', teacher_api::getTeacherFavTotal($this->tid));
        $this->assign('planid',$planid);
        $this->render("/site/teacher_nav_header.html");
    }
}
