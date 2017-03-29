<?php

class course_customer extends STpl {

    private $domain;
    private $orgOwner;
    private $orgId;

    function __construct() {
        $domain_conf = SConfig::getConfig(ROOT_CONFIG . "/const.conf", "domain");
        $this->domain = $domain_conf->domain;
        $this->assign('domain', $this->domain);

        $org = user_organization::subdomain();
        if (!empty($org)) {
            $this->orgOwner = $org->userId; //机构所有者id 以后会根据域名而列取 
        } else {
            header('Location: https://www.' . $this->domain);
        }
        $this->orgInfo = user_organization::getOrgByOwner($this->orgOwner);
        if (!empty($this->orgInfo->oid)) {
            $this->orgId = $this->orgInfo->oid;
        } else {
            header('Location: https://www.' . $this->domain);
        }
    }
    
    //获取客服列表,ajax
    public function pagegetCustom(){
        $orgId = !empty($this->orgInfo->oid) ? $this->orgInfo->oid : '';
        $params['orgid'] = $orgId;
        if(!empty($_POST['type'])){
            $params['type']=$_POST['type'];
        }
        if(!empty($_POST['type_name'])){
            $params['type_name']=$_POST['type_name'];
        }
        $customer = org_api::customerToolsList($params); //print_r($customer);
        $customerData = '';
        if ($customer->result->code != 0 || empty($customer->data)) {
            $customerData = $customer->data;
        }
        $this->outputJson(array('code' => 0, 'msg' => 'suc','data'=>$customerData));
    }

    //绑定的客服列表
    public function pagelist() {
        $user = user_api::loginedUser();
        $courseid = !empty($_POST['courseid']) ? (int) $_POST['courseid'] : '';
        $data['courseid']=$courseid;
        $res=  common_api::getCustomerList($data);print_r($res);
        $this->assign('list', $res);
        return $this->render("course/customerList.html");
    }

    /**
     * 绑定客服，ajax
     * 如果是设置首页客服，需要机构id及客服ids
     * 如果是设置课程客服，需要课程id及客服ids
     */
    public function pagebindCustomerInfo() {
        $user = user_api::loginedUser();
        $owner = !empty($this->orgInfo->oid) ? $this->orgInfo->oid : '';
        //$type = !empty($_POST['type']) ? (int) $_POST['type'] : 1; //1代表机构首页客服，2代表课程详情页客服
        $courseid = !empty($_POST['courseid']) ? (int) $_POST['courseid'] : '';
        $customerids = !empty($_POST['customerids']) ? (int) $_POST['customerids'] : ''; //逗号分隔
        if (empty($customerids)) {
            $this->outputJson(array('code' => -1, 'msg' => '请选择要绑定的客服'));
        }
        if (empty($courseid)) {
            $this->outputJson(array('code' => -1, 'msg' => '请指定要绑定客服的课程'));
        }
        $data['customer'] = $customerids;
        $data['fk_course'] = $courseid;
        $rs = common_api::bindCustomerInfo($data);
        if ($custom->result->code == 0) {
            $this->outputJson(array('code' => 0, 'msg' => '绑定成功'));
        } else {
            $this->outputJson(array('code' => -1, 'msg' => '绑定失败'));
        }
    }

    //解除绑定,ajax
    public function pageunbindCustomerInfo() {
        $user = user_api::loginedUser();
        $pid = !empty($_POST['pid']) ? (int) $_POST['pid'] : '';
        $res = common_api::unbindCustomerInfo($pid);
        if ($custom->result->code == 0) {
            $this->outputJson(array('code' => 0, 'msg' => '删除成功'));
        } else {
            $this->outputJson(array('code' => -1, 'msg' => '删除失败'));
        }
    }

    private function outputJson($data) {
        $this->result['code'] = $data['code'];
        $this->result['msg'] = $data['msg'];
        $this->result['data'] = $data['data'];
        exit(json_encode($this->result));
    }

}
