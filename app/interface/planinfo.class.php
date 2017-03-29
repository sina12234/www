<?php
/**
 * 老师上课初始化数据
 * @update 2017-01-04
 */
class interface_planinfo{

    /**
     * @var int 排课id
     */
    private $planId = 0;

    /**
     * @var string 
     */
    private $isHttp = "";

    /**
     * $var array 用户信息
     */
    private $userInfo = array();

    /**
     * 初始化
     */
    public function __construct($planId){
        $this->planId = $planId;
        $this->isHttp = utility_net::isHTTPS() ? 'https' : 'http';

        $this->userInfo();
        $this->streamInfo();
        $this->planInfo();
        $this->shareInfo();
        $this->announcementInfo();
        $this->examInfo();
    }

    /**
     * 用户信息
     */
    private function userInfo(){
        $userInfo = user_api::loginedUser();
        $this->userInfo = [
            'uid'      => $userInfo['uid'],
            'realName' => !empty($userInfo['real_name']) ? $userInfo['real_name'] : $userInfo['name'],
            'token'    => $userInfo['token']
        ];
    }

    /**
     * 流信息(推流信息/语音流信息)
     */
    private function streamInfo(){
        //推流信息
        $publish = live_auth::getPublishAuth($this->userInfo['uid']);
        if(empty($publish)){
            $ret = live_auth::setPublishAuth($this->userInfo['uid']);
            $ret && $publish = live_auth::getPublishAuth($this->userInfo['uid']);
        }

        //语音流信息
        $playInfo = player_live::getCdn("CHAT-RTMP");

        $this->stream = [
            'publish' => [
                'url'    => !empty($publish->server) ? $publish->server : '',
                'stream' => !empty($publish->stream_name) ? $publish->stream_name : ''
            ],
            'chat'    => [
                'url'    => !empty($playInfo) ? "rtmp://".$playInfo->host."/chat" : '',
                'stream' => !empty($publish->stream_name) ? $publish->stream_name : ''
            ]
        ];
    }

    /**
     * 排课信息
     */
    private function planInfo(){
        $planInfo = course_api::getplanbypid($this->planId);
        $courseInfo = !empty($planInfo) ? course_api::getcourseone($planInfo->course_id) : array();
        $classInfo  = !empty($planInfo) ? course_api::getclass($planInfo->class_id) : array();
        $domain     = !empty($courseInfo->user_id) ? user_organization::getDomainByOwner(array('fk_user_owner'=>$courseInfo->user_id)) : '';

        $this->plan = [
            'courseId'    => !empty($courseInfo) ? $courseInfo->course_id : 0,
            'courseTitle' => !empty($courseInfo) ? $courseInfo->title : '',
            'studentNum'  => !empty($classInfo)  ? $classInfo->user_total : 0,
            'thumBig'     => !empty($courseInfo) ? interface_func::imgUrl($courseInfo->thumb_big) : '',
            'thumbMed'    => !empty($courseInfo) ? interface_func::imgUrl($courseInfo->thumb_med) : '',
            'thumbSmall'  => !empty($courseInfo) ? interface_func::imgUrl($courseInfo->thumb_small) : '',
            'url'         => $this->isHttp.'://'.$domain->subdomain."/course.plan.play/".$this->planId,
            'name'        => !empty($planInfo) ? '第'.$planInfo->order_no.'课时' : '',
            'desc'        => !empty($planInfo) ? $planInfo->section_name : ''
        ];
    }

    /**
     * 分享信息
     */
    private function shareInfo(){
        $this->share = [
            'title' => $this->plan['courseTitle'] .'-'. $this->plan['name'],
            'desc'  =>'【'.$this->userInfo['realName'].'】要开课啦,同学们快来上课吧～',
            'url'   => $this->plan['url'],
            'img'   => $this->plan['thumbMed']
        ];
    }

    /**
     * 公告信息
     */
    private function announcementInfo(){
        $annCementReg = course_plan_api::getAnnouncement(array('fk_plan'=>$this->planId));
        $this->announcement = new stdclass;

        if(!empty($annCementReg->result->data->items)){
            foreach($annCementReg->result->data->items as $val){
                $this->announcement = [
                    'createTime' => $val->create_time,
                    'content'    => $val->content,
                    'id'         => $val->id
                ];
            }
        }
    }

    /**
     * 备课出题
     */
    public function examInfo(){
        $examReg = interface_planApi::getPlanExam($this->planId, $this->userInfo['uid']);
        $this->examInfo = !empty($examReg) ? $examReg : array();
    }
}
