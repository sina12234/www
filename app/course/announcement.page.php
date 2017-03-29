<?php
/**
 * 播放页公告功能
 * @author zhouyu
 * date: 2016/10/21
 */
class course_announcement extends STpl
{
    //公告添加
    public function pageAnnouncementAdd(){
        $planId = !empty($_POST['fk_plan']) ? $_POST['fk_plan'] : '';
        $content = !empty($_POST['content']) ? $_POST['content'] : '';
        $strnum =   utility_tool::stringNum($content);
        if(empty($planId)) return json_encode(array('code'=>-101,'msg'=>'fk_plan is null'));
        if(empty($content)) return json_encode(array('code'=>-102,'msg'=>'content is null'));
        //100汉字 200英文
        if($strnum > 200 ) return  json_encode(array('code'=>-103,'msg'=>'content > 100'));
        $condition = [
            'status'=>1,
            'fk_plan'=>$planId,
            'content'=>$content,
            'create_time'=>date("Y-m-d H:i:s",time())
        ];
        $InsertAnnouncementAdd = course_announcement_api::AnnouncementAdd($condition);
        if($InsertAnnouncementAdd->result->code == 200){
            return json_encode(array('code'=>200,'msg'=>'success'));
        }else{
            return $InsertAnnouncementAdd;
        }
    }

    //公告编辑
    public function pageAnnouncementUpdate(){
        $planId = !empty($_POST['fk_plan']) ? $_POST['fk_plan'] : '';
        $content = !empty($_POST['content']) ? $_POST['content'] : '';
        $strnum =   utility_tool::stringNum($content);
        if(empty($planId)) return json_encode(array('code'=>-101,'msg'=>'fk_plan is null'));
        if(empty($content)) return json_encode(array('code'=>-102,'msg'=>'content is null'));
        if($strnum > 200 ) return  json_encode(array('code'=>-103,'msg'=>'content > 100'));
        $condition = [
            'fk_plan'=>$planId,
            'content'=>$content,
        ];
        $AnnouncementUpdate = course_announcement_api::AnnouncementUpdate($condition);
        if($AnnouncementUpdate->result->code == 200){
            return json_encode(array('code'=>200,'msg'=>'success'));
        }else{
            return json_encode(array('code'=>-104,'msg'=>'error','data'=>'already update'));
        }
    }

    //公告删除
    public function pageAnnouncementDelete(){
        $planId = !empty($_POST['fk_plan']) ? $_POST['fk_plan'] : '';
        if(empty($planId)) return json_encode(array('code'=>-101,'msg'=>'fk_plan is null'));
        $AnnouncementDelete = course_announcement_api::AnnouncementDelete(array('fk_plan'=>$planId));
        if($AnnouncementDelete->result->code == 200){
            return json_encode(array('code'=>200,'msg'=>'success'));
        }else{
            return $AnnouncementDelete;
        }
    }

    //获取公告
    public function pageGetAnnouncement(){
        $planId = !empty($_POST['fk_plan']) ? $_POST['fk_plan'] : '';
        if(empty($planId)) return json_encode(array('code'=>-101,'msg'=>'fk_plan is null'));
        $GetAnnouncement = course_announcement_api::GetAnnouncement(array('fk_plan'=>$planId));
        if($GetAnnouncement->result->code == 200){
            return json_encode(array('code'=>200,'msg'=>'success','data'=>$GetAnnouncement->result->data->items));
        }else{
            return json_encode(array('code'=>-202,'msg'=>'success','data'=>'null'));
        }
    }

}