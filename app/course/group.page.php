<?php
/*
 * 分组管理
 */

class course_group extends STpl{
    var $user;
    
    /*
     * 巡课页用户列表接口
     * param type 1代表学生播放页，2代表管理员巡课页
     * param classid
     * param groupid
     * param page
     * param pagesize
     * return json
     */

    public function pageUserList() {
        $this->user = user_api::loginedUser();
        if(empty($this->user)){
            $this->outputJson(-1, 'lack userid');
        }
        $type = !empty($_POST['type']) ? $_POST['type'] : 1; //1代表学生播放页，2代表管理员巡课页
        if($type==2){
            $this->getOrgInfo();
        }
        $classid = !empty($_POST['classid']) ? $_POST['classid'] : 0;
        $groupid = !empty($_POST['groupid']) ? $_POST['groupid'] : 0;
        $page = !empty($_POST['page']) ? $_POST['page'] : 1;
        $pagesize = !empty($_POST['pagesize']) ? $_POST['pagesize'] : 10;
        if (empty($classid)) {
            $this->outputJson(-1, 'lack classid');
        }
        if (empty($groupid)) {
            $this->outputJson(-1, 'lack groupid');
        }
        $data['cache']=1;//需要缓存
        $data['type'] = $type;
        $data['classid'] = $classid;
        $data['groupid'] = $groupid;
        $data['loginid']=$this->user['uid'];
        //$data['loginid'] = 1;
        $data['page'] = $page;
        $data['pagesize'] = $pagesize;
        $rs = user_api::userList($data); //print_r($rs);
        if ($type == 1) {
            if ($rs->result->code == 0) {
                if(!empty($rs->data)){
                    $this->outputJson(0, 'suc', $rs->data);
                }else{
                    $this->outputJson(0, 'suc');
                }
            }
            $this->outputJson(-1, $rs->result->msg);
        } else {
            if ($rs->result->code == 0) {
                $list="";
                $tmp = $rs->data; //print_r($tmp);die;
                if(!empty($tmp)){
                    for ($i = 0; $i < count($tmp); $i++) {
                        $list[$i]['uid'] = $tmp[$i]->user_id;
                        $list[$i]['name'] = $tmp[$i]->name;
                        $list[$i]['real_name'] = $tmp[$i]->real_name;
                        $list[$i]['thumb_small'] = $tmp[$i]->thumb_small;
                        $list[$i]['mobile'] = $tmp[$i]->mobile;
                    }
                }
                $this->outputJson(0, 'suc', $list);
            }
            $this->outputJson(-1, $rs->result->msg);
        }
    }
    
    private function getOrgInfo(){
        $org = user_organization::subdomain();
        $domain = $_SERVER["HTTP_HOST"];
        if (!empty($org)) {
            $orgOwner = $org->userId; //机构所有者id 以后会根据域名而列取
        } else {
            $this->outputJson(-1, 'lack org');
        }
        $orgInfo = user_organization::getOrgByOwner($orgOwner);
        if (!empty($orgInfo->oid)) {
            $orgId = $orgInfo->oid;
        } else {
            $this->outputJson(-1, 'lack org');
        }
        //判断管理员
        //echo $this->orgOwner;echo "|";echo $this->user['uid'];die;
        $isAdmin = user_api::isAdmin($orgOwner, $this->user['uid']);
        if ($isAdmin === false) {
            $this->outputJson(-1, 'invalid admin');
        }
    }
    
    private function outputJson($code, $msg = '', $data = '') {
        $result = array('code' => 0, 'msg' => '', 'data' => '');
        $result['code'] = $code;
        $result['msg'] = $msg;
        $result['data'] = $data;
        exit(json_encode($result));
    }
    
}
