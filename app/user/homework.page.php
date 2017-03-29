<?php
class user_homework extends STpl{
	var $user;
	function __construct(){
		//如果没有登陆到登陆界面
		$this->user = user_api::loginedUser();
		if(empty($this->user)){
			$this->redirect("/site.main.login");
		}
	}
	public function pageList($inPath){
        $data=array();
        $data['t_task_reply.fk_user_reply']= $this->user['uid'];
        if(empty($inPath[3])){
            $status=false;
        }else{
            $status=$inPath[3];
        }
        if($status=='not'){
            $data['t_task_reply.status']=0;
        }
        if($status=='ok'){
            $data['t_task_reply.status']=1;
        }
        if($status=='yes'){
            $data['t_task_reply.status']=2;
        }
        if(!empty($_REQUEST['section'])){
            $data['t_task.fk_section']=$_REQUEST['section'];
        }
        if(!empty($_REQUEST['class'])){
            $data['t_task.fk_class']=$_REQUEST['class'];
        }
        $page = isset($_GET['page']) ? $_GET['page']:1;
        $num ="10";
        $pm=array();
        $pm['status']=$status;
        $pm['class']=!empty($_REQUEST['class'])?$_REQUEST['class']:0;
        $pm['section']=!empty($_REQUEST['section'])?$_REQUEST['section']:0;
        $path='/user.homework.list.'.$status;
        $result=user_api::getReplyList($page,$num,$data);
        $class=user_api::getReplyClass($this->user['uid']); 
        $section=user_api::getReplySection($this->user['uid']); 
        $count=user_api::countReplyStatus($this->user['uid']);
        $data=array();
        if(!empty($count)){
            foreach($count as $k=>$v){
                $data[$v->status]=array(
                        'status'=>$v->status,
                        'count'=>$v->count,
                    );

            }
        }
        $this->assign("list",$result);    
        $this->assign("num",$num);  
        $this->assign("pm",$pm);  
        $this->assign("count",$data);  
        $this->assign("path",$path);  
        $this->assign("class",$class);    
        $this->assign("section",$section);    

		return $this->render("user/homework.list.html");
	}
    public function pageInfo($inPath){
        $rid=!empty($inPath[3])?$inPath[3]:0;
        $reply=user_api::getReplyInfo($rid); 
        $attach=user_api::getAttachList($rid); 
        $this->assign("reply",$reply);    
        $this->assign("attach",$attach);    
		return $this->render("user/homework.info.html");
    }
    public function pageAdd($inPath){
        $data=array();
        $data['rid']=!empty($_POST['rid'])?$_POST['rid']:0;
        $data['tid']=!empty($_POST['tid'])?$_POST['tid']:0;
        $data['fid']=!empty($_POST['fid'])?$_POST['fid']:'';
        $data['uid']=$this->user['uid'];
        $data['desc']=!empty($_POST['desc'])?$_POST['desc']:'';
        $ret=user_api::addAttach($data);
        //print_r($data);
        return json_encode($ret);
    }
}