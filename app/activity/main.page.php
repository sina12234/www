<?php
class activity_main extends STpl{
	function __construct(){
		$org=user_organization::subdomain();
        if(!empty($org)){
	        $this->orgOwner = $org->userId; 	
        }else{
	        $this->orgOwner = 2; 	
        }

	}
	public function setResult($data = '', $code = 0, $msg = ''){
		$ret = new stdclass;
		$ret->result = new stdclass;
		$ret->result->code = $code;
		$ret->result->data = $data;
		$ret->result->msg = $msg;
		return $ret;
	}
	function pageList($inPath){
		utility_cache::pageCache(60);
        $page=isset($_GET['page'])?$_GET['page']:1;
        $num=20;
        $cateId = isset($_GET['c']) ? (int)$_GET['c'] : 0;
        $path="/activity.main.list?c=".$cateId."";
        $orgInfo=user_organization::getOrgByOwner($this->orgOwner);
        $data = array( "fk_org"=>$orgInfo->oid);
        $list=user_api::getNoticeList($page,$num,$this->orgOwner,$cateId,$orgInfo->oid);
		if(!empty($list->data)){
            foreach($list->data as $nlk=>$nlv){
                //取出图片
                preg_match('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i',$nlv->notice_content,$match);
                //过滤php、html标签
                $tmpStr=strip_tags($nlv->notice_content);
                //有图片时截取54个字，无图片时截取67个字
                if(!empty($match[2])){
					//部分图片地址是绝对路径去掉http或https不能正确显示
					//$str = str_replace(array("http:","https:")," ",$match[2]);
                    //$noticeList->data[$nlk]->thumb =$str;
					$list->data[$nlk]->thumb=$match[2];
                    //$noticeList->data[$nlk]->sub_content=mb_substr($tmpStr,0,54,'utf-8').'...';
                }else{
                    $list->data[$nlk]->thumb='';
                    //$noticeList->data[$nlk]->sub_content=mb_substr($tmpStr,0,67,'utf-8').'...';
                }
                $list->data[$nlk]->sub_content=$tmpStr;
                //不显示当年年份
                //获取年份
                $year=date('Y',strtotime($nlv->update_time));
                if($year==date('Y')){
                    $list->data[$nlk]->update_time=date('m-d H:i',strtotime($nlv->update_time));
                }else{
                    $list->data[$nlk]->update_time=date('Y-m-d H:i',strtotime($nlv->update_time));
                }
            }
        }
		if(!empty($cateId)){
			$catename = !empty($list->data[0]->name) ? $list->data[0]->name : '';
			$li = "";
			$this->assign('catename',$catename);
			$this->assign('catename',$catename);
		}else{
			$catename = "全部文章";
			$this->assign('catename',$catename);
		}
		$cateRes 	= array_slice((array)$list->cateInfo,0,5);
		$cateResult 	= array_slice((array)$list->cateInfo,5);
		$cateNum	= isset($list->cateInfo) ? count((array)$list->cateInfo) : 0;
		$cateResult = array_slice((array)$list->cateInfo,5);
        $cateList = org_api::noticeCategoryList($data);
		$this->assign('cateNum',$cateNum); 
		$this->assign('cateList',$cateRes);        
		$this->assign('cateResult',$cateResult);   
		$this->assign('cateId',$list->cateInfo);      
		$this->assign('c',$cateId); 		
        $this->assign('list',$list);
        $this->assign('path',$path);
        $this->assign("num",$num);
		return $this->render("activity/noticeList.html");
	}
	function pageInfo($inPath){
		utility_cache::pageCache(600);
		$uid = 0;
		$userLogin 	 = user_api::loginedUser();
		if(!empty($userLogin['uid'])){
			 $uid = $userLogin['uid'];
		}
		$this->assign('user_id',$uid);
		$notice = new stdclass;
        $notice=user_api::getNotice($inPath[3]);
		//WEB-5153
		//$notice->notice_content = str_replace(array("http:","https:")," ",$notice->notice_content);
		$orgInfo = user_organization::getOrgByOwner($this->orgOwner);
        $data 	 = array("fk_org" => $orgInfo->oid,"pk_cate" => $notice->fk_cate);
		$condis  = array("fk_notice" => !empty($inPath[3]) ? $inPath[3] : 0,"status" => 1);
		$cateList = org_api::noticeCategoryList($data);
		$CommentList = activity_api::NoticeCommentList($condis);
		if(!empty($CommentList)){
			$userIdArr1 = array();
			$userIdArr2 = array();
			foreach($CommentList as $k=>$v){
				if(!empty($v->comment_fk_user)){
					$userIdArr1[] = $v->comment_fk_user;
				}
				if(!empty($v->from_comment_fk_user)){
					$userIdArr2[] = $v->from_comment_fk_user;
				}
			}
			$userIdArr  = array_values(array_unique(array_merge($userIdArr1,$userIdArr2)));
			$userInfo 	= activity_api::getUsersListInfo(array("userIdArr" => $userIdArr));
			$userLevel  = user_api::listLevelByUserIdsArr($userIdArr);
			
			if(!empty($userLevel->result)){
				foreach($userLevel->result as $x=>$y){
					$levelValue[$y->fk_user] = $y;
				}
			}
		}
		
		if(!empty($userInfo)){
			foreach($userInfo as $key=>$val){
				$user[$val->pk_user] = $val;
			}
		}
		if(!empty($CommentList)){
			foreach($CommentList as $key=>$val){
				if(!empty($user[$val->comment_fk_user])){
					$val->c_name = !empty($user[$val->comment_fk_user]->name) ? $user[$val->comment_fk_user]->name : '';
					$val->c_real_name = !empty($user[$val->comment_fk_user]->real_name) ? $user[$val->comment_fk_user]->real_name : '';
					$val->c_thumb_med = !empty($user[$val->comment_fk_user]->thumb_med) ? utility_cdn::file($user[$val->comment_fk_user]->thumb_med) : '';
				}
				if(!empty($user[$val->from_comment_fk_user])){
					$val->f_name = !empty($user[$val->from_comment_fk_user]->name) ? $user[$val->from_comment_fk_user]->name : '';
					$val->f_thumb_med = !empty($user[$val->from_comment_fk_user]->thumb_med) ? utility_cdn::file($user[$val->from_comment_fk_user]->thumb_med) : '';
				}
				if(!empty($levelValue[$val->comment_fk_user])){
					$val->c_level = !empty($levelValue[$val->comment_fk_user]->fk_level) ? $levelValue[$val->comment_fk_user]->fk_level : 1;
				}
			}
		}
		$noticeCateName = "";
		if(!empty($cateList->items)){
			foreach($cateList->items as $x=>$y){
				if($y->pk_cate == $notice->fk_cate){
					$noticeCateName = $y->name;
				}
			}
		}
		$notice->cate_name = !empty($notice->fk_cate) ? $noticeCateName : "默认";
        $this->assign('notice',$notice);
        $this->assign('commentList',$CommentList);
        $this->assign('notice_id',!empty($inPath[3]) ? $inPath[3] : 0);
		return $this->render("activity/info.html");
	}
	/**
	 * 机构资讯详情页评论增加
	 *@return array $result 
	 */
	function pageAddNoticeComment($inPath){
		$result  = new stdclass;
		$user 	 = user_api::loginedUser();
		if(empty($user)){
			$result->code  =  -100;
			$result->field = "user_id";
			$result->msg   = "请登录后评论~";
			return $result;
		}
		$orgInfo = user_organization::getOrgByOwner($this->orgOwner);
		if(empty($_POST['notice_id'])){
			$result->code  =  -100;
			$result->field = "notice_id";
			$result->error = "资讯ID不能为空~";
			return $result;
		}
		if(empty(trim($_POST['comment']))){
			$result->code  =  -100;
			$result->field = "comment";
			$result->error = "评论不能为空~";
			return $result;
		}
		$fromComment = "";
		$fromUser	 = 0;
		if(!empty($_POST['comment_id'])){
			$con 	 = array(
						"fk_org" 		=> $orgInfo->oid,
						"status" 		=> 1,
						"pk_comment" 	=> !empty($_POST['comment_id']) ? $_POST['comment_id'] : 0
						);
			$noticeInfo = activity_api::getNoticeCommentOneInfoById($con);	
			if(!empty($noticeInfo->comment)){
				$fromComment = $noticeInfo->comment;
				$fromUser	 = $noticeInfo->comment_fk_user;
			}
			
		}
		$data 	 = array(
						"fk_org" 				=> $orgInfo->oid,
						"status" 				=> 1,
						"comment_fk_user"		=> $user['uid'],
						"from_comment_fk_user"	=> $fromUser,
						"fk_notice"				=> !empty($_POST['notice_id']) ? $_POST['notice_id'] : 0,
						"comment"				=> !empty($_POST['comment']) ? $_POST['comment'] : '',
						"from_comment"			=> $fromComment,
						"create_time" 			=> date("Y-m-d H:i:s")
						);
		$addInfo  = activity_api::AddNoticeComment($data);
		if(!empty($addInfo)){
			return $this->setResult($addInfo, $code = 200, $msg = '增加成功');
		}else{
			return $this->setResult($addInfo, $code = -100, $msg = '增加失败');
		}
	}
	/**
	 * 机构资讯详情页评论删除
	 *@return array $result 
	 */
	function pagedeleteNoticeComment($inPath){
		$orgInfo = user_organization::getOrgByOwner($this->orgOwner);
		$result  = new stdclass;
		if(empty($_POST['comment_id'])){
			$result->code  =  -100;
			$result->field = "comment_id";
			$result->error = "ID不能为空~";
			return $result;
		}
		$data 	 	= array(
						"fk_org" 		=> $orgInfo->oid,
						"status" 		=> 1,
						"pk_comment" 	=> !empty($_POST['comment_id']) ? $_POST['comment_id'] : 0
						);
		$noticeInfo = activity_api::getNoticeCommentOneInfoById($data);		
		$delete 	= "";
		if($noticeInfo != false ){
			$delete = activity_api::deleteNoticeComment($data);
		}
		if(!empty($delete)){
			return $this->setResult($delete, $code = 200, $msg = '删除成功');
		}else{
			return $this->setResult($delete, $code = -100, $msg = '删除失败');
		}
	}
	public function pageUserLogin($inPath){
        $notice  = !empty($inPath[3]) ?  explode(",",$inPath[3]) : ''; 
        $comment = isset($inPath[4]) ? utility_tool::unescape($inPath[4]):'';	
		$this->assign('notice_id',!empty($notice[0]) ? $notice[0] : 0);
		$this->assign('comment_id',!empty($notice[1]) ? $notice[1] : 0);
		$this->assign('comment',$comment);
        $this->render("activity/iframeLogin.html");
    }
}
