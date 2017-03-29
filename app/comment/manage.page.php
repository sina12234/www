<?php
/*
 * 评价管理
 */
class comment_manage extends STpl{
    public $name;
    public $uid;
    public function __construct(){
        //检测用户权限
        $userInfo = user_organization::subdomain();
        //判断登录
        $user=user_api::loginedUser();
        if (empty($user['uid'])) return interface_func::setMsg(1021);
        $this->name = $user['name'];
        $this->uid = $user['uid'];
        //判断管理员
        $isAdmin		=	user_api::isAdmin($userInfo->userId,$user['uid']);
        $isTeacher		=	user_api::isTeacher($userInfo->userId,$user['uid']);
        if(!$isTeacher && !$isAdmin) $this->redirect('/');//去首页
    }

    /*
     * 课程评价列表  wsn
     */
    public function pageCommentList($inPath){

        $page = isset($_REQUEST['page']) && $_REQUEST['page'] ? (int)($_REQUEST['page']) : 1;
        $length = 20;
        $score = isset($_REQUEST['score']) && $_REQUEST['score'] ? (int)($_REQUEST['score']) : '';
        $time =  isset($_REQUEST['time']) && $_REQUEST['time'] ? trim($_REQUEST['time']) : '';
        if (isset($inPath[3]) && (int)($inPath[3])) {
            $courseId = (int)($inPath[3]);
        }
        !isset($courseId) && $this->redirect('/');
        $data = comment_api::CommentList($courseId,$score,$time, 0 ,$page,$length);  //获取评论列表
        //评论下面挂载老师最新评论
        if(!isset($data['totalPage'])) $data['totalPage'] = 0;
        if(!isset($data['data'])) $data['data'] = array();

//        if(isset($data['data'])){
//            foreach($data['data'] as &$value){
//                $replay_data = comment_api::showReplay($value['commentId'],$this->uid);
//                $replay_data = json_decode($replay_data);
//                $value['time'] = date("Y")==date('Y',strtotime($value['time'])) ? date('m-d H:i',strtotime($value['time'])) :date('Y-m-d H:i',strtotime($value['time']));
//                $st = new stdclass();
//                $st->pk_replay = '';
//                $st->fk_comment = '';
//                $st->fk_user = '';
//                $st->manage_name = '';
//                $st->contents = '';
//                $st->replay_time = '';
//                $st->status = '';
//                if($replay_data->code==0){
//                    $value['replay'] = $replay_data->result;
//                    $value['replay']->replay_time = (date('Y')==date('Y',strtotime($value['replay']->replay_time)) ?  date('m-d H:i',strtotime($value['replay']->replay_time)) : date('Y-m-d H:i',strtotime($value['replay']->replay_time)));
//                }else{
//                    $value['replay'] = $st;
//                }
//            }
//        }
        $st = new stdclass();
        $st->pk_replay = '';
        $st->fk_comment = '';
        $st->fk_user = '';
        $st->manage_name = '';
        $st->contents = '';
        $st->replay_time = '';
        $st->status = '';
        if(isset($data['data']) && !empty($data['data'])){
            $commentIdArr = array();
            foreach($data['data'] as &$v){
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
                foreach($data['data'] as &$value){
                    if(in_array($value['commentId'],$replays)){
                        $value['replay'] = $replayArr[$value['commentId']];
                        $value['replay']->replay_time = (date('Y')==date('Y',strtotime($value['replay']->replay_time)) ?  date('m-d H:i',strtotime($value['replay']->replay_time)) : date('Y-m-d H:i',strtotime($value['replay']->replay_time)));
                    }else{
                        $value['replay'] = $st;
                    }
                }
            }
        }
        $path='/comment/manage/CommentList/'.$inPath[3];
        $path_page = utility_tool::getUrl($path);
        $this->assign('courseId',$courseId);
        $this->assign('isCheck',8);
        $this->assign('data',$data);
        $this->assign("totalPage",$data['totalPage']);
        $this->assign("page",$page);
        $this->assign("path_page",$path_page);
        $this->assign("length",$length);
       return $this->render('course/course.comment.html');
    }

    /*
     * 老师回复插入数据库 wsn
     */

    public function pageInsertCommentReplay($inPath){
        $fk_comment = isset($_REQUEST['commentId']) && $_REQUEST['commentId'] ? (int)($_REQUEST['commentId']) : '';
        $contents = isset($_REQUEST['contents']) && $_REQUEST['contents'] ? htmlspecialchars(trim($_REQUEST['contents'])) : '';
        if(empty($this->uid)) return interface_func::setMsg(1,'没有登录');
        if(empty($fk_comment))  return interface_func::setMsg(1,'用户评论id不能为空');
        if(empty($contents))  return interface_func::setMsg(1,'老师评论内容不能为空');
        if($this->CheckIsReplay($fk_comment,$this->uid)) return interface_func::setMsg(1,'已经评论过');
        $fk_user = $this->uid;//管理员id
        $manage_name = $this->name;//管理员名称
        $time = date('m-d H:i');
        $params = [
            'fk_user'=>$fk_user,
            'manage_name'=>$manage_name,
            'contents' => $contents,
            'fk_comment' =>$fk_comment
        ];
        $ret =    comment_api::InsertCommentReplay($params);
        $res = json_decode($ret,true);
        $res['contents'] = $contents;
        $res['time'] = $time;
        $res['manage_name'] = $manage_name;
        $res = json_encode($res,JSON_UNESCAPED_UNICODE);
        echo $res;
    }

    /*
     * 管理员回复删除
     */
    public function pageDeleteCommentReplay($inPath){
        $pk_replay = isset($_REQUEST['pk_replay']) && $_REQUEST['pk_replay'] ? (int)($_REQUEST['pk_replay']) : '';
        $fk_user = $this->uid;//管理员id
        if(empty($pk_replay))  return interface_func::setMsg(1,'回复的id不能为空');
        if(empty($fk_user))  return interface_func::setMsg(1,'管理员没有登录');
        $params = [
          'pk_replay'   =>$pk_replay,
          'fk_user'     =>$fk_user
        ];
        $ret =  comment_api::DeleteCommentReplay($params);
        $ret = json_encode(['code'=>$ret->code,'message'=>$ret->message]);
        echo $ret;
    }

    /*
     * 检测管理员是否给这条评论评论过
     * 如果评论过 就不能再评论 删除后还可以再评论
     */

    public function CheckIsReplay($fk_comment,$fk_user){
        $fk_user = isset($fk_user) ? intval($fk_user) : '';
        $fk_comment = isset($fk_comment) ? intval($fk_comment) : '';
        $params = [
            'fk_comment'=>$fk_comment,
            'fk_user'   =>$fk_user
        ];
        $ret =   utility_services::call('/comment/course/CheckIsReplay', $params);
        if(isset($ret->pk_replay)){
            return true;
        }
        return false;
    }
}