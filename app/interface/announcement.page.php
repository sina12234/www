<?php

class interface_announcement extends interface_base
{

    /* 公告新增 修改 删除
     * @param status   1新增,编辑 2删除
     * @param fk_plan  planId
     * @param content  公告内容
     * @param create_time
     * */
    public function pageAnnouncement(){
        $status = !empty($this->paramsInfo['params']['status'])?$this->paramsInfo['params']['status']:'';
        if (empty($status)) {
            return interface_func::setMsg(1001);
        }
        $fk_plan = !empty($this->paramsInfo['params']['fkPlan'])?$this->paramsInfo['params']['fkPlan']:'';
        if (empty($fk_plan)) {
            return interface_func::setMsg(1001);
        }
        //获取排课信息
        $planInfos = course_api::listPlan(array("plan_id" => $fk_plan, "allcourse" => true));
        if(empty($planInfos->data[0])){
            return interface_func::setMsg(2017);
        }

        //查询是否有记录 GetAnnouncement
        $selAnnouncement = course_plan_api::GetAnnouncement(array('fk_plan'=>$fk_plan));
        if($status == 1){
            $content = !empty($this->paramsInfo['params']['content'])?$this->paramsInfo['params']['content']:'';
            if (empty($content)) {
                return interface_func::setMsg(1001);
            }
            $strnum =   utility_tool::stringNum($this->paramsInfo['params']['content']);
            if($strnum > 200 ){
                return interface_func::setMsg(2051);
            }
            $condition = [
                'status'=>1,
                'fk_plan'=>$fk_plan,
                'content'=>$content,
                'create_time'=>date("Y-m-d H:i:s",time()),
            ];

            if(isset($selAnnouncement->result->data->items[0]->id)){
                $condition['id']=$selAnnouncement->result->data->items[0]->id;
            }

            //新增公告  编辑公告
            $Announcement = course_plan_api::Announcement($condition);
            if($Announcement->result->code){
                return interface_func::setMsg(0);
            }else{
                return interface_func::setMsg(1);
            }
        }
        if($status == 2){
            //删除公告
            $condition = array('fk_plan'=>$fk_plan);
            $AnnouncementDel = course_plan_api::AnnouncementDel($condition);
            if($AnnouncementDel->result->code == 200){
                return interface_func::setMsg(0);
            }else{
                return interface_func::setMsg(1);
            }
        }
    }

    /*
     * 获取公告内容
     * @param fk_plan
     * */
    public function pageGetAnnouncement(){
        $fk_plan = !empty($this->paramsInfo['params']['fkPlan'])?$this->paramsInfo['params']['fkPlan']:'';
        if (empty($fk_plan)) {
            return interface_func::setMsg(1001);
        }
        $Announcement = course_plan_api::GetAnnouncement(array('fk_plan'=>$fk_plan));

        if($Announcement->result->data){
            $list = array();
            if(!empty($Announcement->result->data->items)){
                foreach($Announcement->result->data->items as $k => $v){
                    $list['fkPlan'] = $v->fk_plan;
                    $list['createTime'] = $v->create_time;
                    $list['content'] = $v->content;
                    $list['status'] = $v->status;
                    $list['id'] = $v->id;
                }
            }
            return interface_func::setData($list);
        }else{
            return interface_func::setMsg(1);
        }


    }
}
