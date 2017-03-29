<?php
/*
 * add by jay
 * 2016-08-16
 * 机构-客服设置 电话,qq,二维码
 */
error_reporting(E_ALL & ~E_NOTICE);//上线时注释掉
class org_customerTools extends STpl {

    private $orgOwner;
    private $orgInfo;
    private $domain;
    private $result;
    private $user;
    private $orgId;

    function __construct($inPath) {
        if($inPath[2]!="getCusRelationListAjax") {
            //如果没有登陆到登陆界面
            $this->user = user_api::loginedUser();
            if (empty($this->user)) {
                $this->redirect("/site.main.login");
            }
            $org = user_organization::subdomain();
            $this->domain = $_SERVER["HTTP_HOST"];
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
            //判断管理员
            /*$isAdmin = user_api::isAdmin($this->orgOwner, $this->user['uid']);
            if ($isAdmin === false) {
                header('Location: //' . $org->subdomain);
            }*/
        }
    }

    //客服列表
    public  function pagelist(){
        $this->render("org/customerTools.html");
    }
    
    //课程客服设置页面
    public function pagecourseCustomerList($inPath){
        $courseId=$inPath[3];
		/*$special = user_api::getTeacherSpecial($this->orgInfo->oid,$this->user['uid']);
		if(empty($special)){
			return $this->redirect("/site.main.entry");
		}*/
        if(empty($courseId)){
            header('Location: https://www.' . $this->domain);
        }
        $courseInfo = course_api::getCourseOne($courseId);
        if(!empty($courseInfo)){
            $document =  $courseInfo->document;
        }
        $this->assign('document', $document);
        $this->assign("isCheck",9);
        $this->assign("courseId",$courseId);
        $this->render("org/courseCustomerList.html");
    }

    //客服列表ajax
    public function pagelistAjax() {
        $params['orgid'] =$this->orgId ;
        $params['type'] = !empty($_POST['type']) ? $_POST['type'] : 1; //1qq,2qq群
        $params['page']=!empty($_POST['page']) ? $_POST['page'] : 1;
        $params['pageSize']=!empty($_POST['pageSize']) ? $_POST['pageSize'] : 100;
        $params['type_name']=isset($_POST['type_name']) ? trim($_POST['type_name']) : '';
        $params['cache']=0;
        $customer = org_api::customerToolsList($params); //print_r($customer);
        $customerData = '';
        if ($customer->result->code ==0) {
            $customerData = $customer->data;
            $customerPage=$customer->page;
            $this->outputJson(0,'suc',array($customerData,$customerPage));
        }else{
            $this->outputJson(-1,'fail');
        }
        
    }

    //客服添加，ajax
    public function pageaddOrgCustomerInfoAjax() {
        $data['type'] = !empty($_POST['type']) ? $_POST['type'] : 1; //1qq,2qq群
        $type_name=trim($_POST['type_name']);
        if (empty($type_name)) {
            $msg = $data['type'] == 1 ? '客服名称不能为空' : '群名称不能为空';
            $this->outputJson(-1,$msg);
        }
        $strNum= utility_tool::stringNum($type_name);
        if($data['type']==1 && $strNum>12){
            $this->outputJson(-1,"长度不能超过6个汉字或12个字符");
        }
        if($data['type']==2 && $strNum>20){
            $this->outputJson(-1,"长度不能超过10个汉字或20个字符");
        }
        $data['type_name'] = $type_name;
        $type_value=trim($_POST['type_value']);
        if (empty($type_value)) {
            $msg =  'QQ号不能为空';
            $this->outputJson(-1,$msg);
        }
        
        if (in_array($data['type'], array(1, 2)) && !empty($type_value)) {
            if (!preg_match('/^[1-9][0-9]{4,}$/', $type_value)) {
                $msg = 'QQ号格式不正确';
                $this->outputJson(-1,$msg);
            }
        }
        $data['type_value'] = $type_value;
        $ext=isset($_POST['ext'])? trim($_POST['ext']):'';
        if ($data['type'] == 2 && empty($ext)) {//QQ群号群链接
            $this->outputJson( -1,'群链接不能为空');
        }
        $data['ext'] = $ext ? $ext : '';
        if(!empty($data['ext'])){
            $pos = substr($data['ext'], strpos($data['ext'], "http"));
            $data['ext'] = trim($pos);
        }
        $data['fk_org'] = $this->orgId;

        $custom = org_api::addCustomerTools($data);

        if ($custom->result->code == 0) {
            $this->outputJson(0,'添加成功');
        } else {
            $this->outputJson(-1, '添加失败');
        }
    }

    //单个客服详情，ajax
    public function pagegetOrgCustomInfoAjax() {
        $pid = !empty($_POST['pid']) ? (int) $_POST['pid'] : '';
        if(empty($pid)){
            $this->outputJson(-1,'系统参数错误');
        }
        $custom = org_api::getCustomerToolsInfo($pid, $this->orgId);//print_r($custom);
        if($custom->result->code == 0){
            $this->outputJson(0,'suc',$custom->data);
        }else{
            $this->outputJson(-1,'获取信息失败');
        }
        
    }

    //更新客服
    public function pageupdateOrgCustomInfoAjax() {
        $data = array();
        $data['type'] = !empty($_POST['type']) ? $_POST['type'] : 1; //1qq,2qq群
        $pid = !empty($_POST['pid']) ? (int) $_POST['pid'] : '';
        if(empty($pid)){
            $this->outputJson(-1,'系统参数错误');
        }
        $type_name=trim($_POST['type_name']);
        if (empty($type_name)) {
            $msg = $data['type'] == 1 ? '客服名称不能为空' : '群名称不能为空';
            $this->outputJson(-1,$msg);
        }
        $strNum= utility_tool::stringNum($type_name);
        if($data['type']==1 && $strNum>12){
            $this->outputJson(-1,"长度不能超过6个汉字或12个字符");
        }
        if($data['type']==2 && $strNum>20){
            $this->outputJson(-1,"长度不能超过10个汉字或20个字符");
        }
        $data['type_name'] = $type_name;
        $type_value=trim($_POST['type_value']);
        if (empty($type_value)) {
            $msg = 'QQ号不能为空';
            $this->outputJson(-1,$msg);
        }
        
        if (in_array($data['type'], array(1, 2)) && !empty($type_value)) {
            if (!preg_match('/^[1-9][0-9]{4,}$/', $type_value)) {
                $msg = 'QQ号格式不正确';
                $this->outputJson(-1, $msg);
            }
        }
        $data['type_value'] = $type_value;
        
        $ext=isset($_POST['ext'])? trim($_POST['ext']):'';
        if ($data['type'] == 2 && empty($ext)) {//QQ群号群链接
            $this->outputJson( -1,'群链接不能为空');
        }
        $data['ext'] = $ext ? $ext : '';
        if(!empty($data['ext'])){
            $pos = substr($data['ext'], strpos($data['ext'], "http"));
            $data['ext'] = trim($pos);
        }

        $data['fk_org'] = $this->orgId;

        $custom = org_api::updateCustomerTools($pid, $data);

        if ($custom->result->code == 0) {
            $this->outputJson(0, '编辑成功');
        } else {
            $this->outputJson( -1, '编辑失败');
        }
    }

    //删除客服
    public function pagedelOrgCustomerInfoAjax() {
        $pid = !empty($_POST['pid']) ? (int) $_POST['pid'] : '';
        if(empty($pid)){
            $this->outputJson(-1,'系统参数错误');
        }
        
        $custom = org_api::delCustomerTools($pid, $this->orgId);
        if ($custom->result->code == 0) {
            $this->outputJson(0,'删除成功');
        } else {
            $this->outputJson( -1,'删除失败');
        }
    }

    //获取机构首页、课程客服列表,ajax
    public function pagegetCusRelationListAjax(){
        $courseid=!empty($_POST['courseid']) ? (int) $_POST['courseid'] : '';
        if(!empty($courseid)){
            $params['courseid']=$courseid;//课程详情页客服
        }else{
            $params['orgid'] = $this->orgId;//机构首页客服
        }
        $customer = org_api::getCusRelationList($params); //print_r($customer);
        if ($customer->result->code ==0) {
            $customerData = $customer->data;
            $this->outputJson(0,'suc',$customerData);
        }else{
            $this->outputJson(-1,'fail');
        }
        
    }
    
    /**
     * 绑定客服，ajax
     * 如果是设置首页客服，需要机构id及客服ids
     * 如果是设置课程客服，需要课程id及客服ids
     */
    public function pagebindCustomerInfoAjax() {
        $type = !empty($_POST['type']) ? (int) $_POST['type'] : 1; //1代表qq客服，2代表qq群客服
        $object_type=!empty($_POST['object_type']) ? (int) $_POST['object_type'] : 1; //1代表机构首页客服，2代表课程详情页客服
        $courseid = !empty($_POST['courseid']) ? (int) $_POST['courseid'] : '';
        $customerids = trim($_POST['customerids']); //逗号分隔
        
        if (empty($customerids)) {
            $this->outputJson( -1, '请选择要绑定的客服');
        }
        if ($object_type == 2 && empty($courseid)) {
            $this->outputJson(-1, '请指定要绑定客服的课程');
        }
        $data['type']=$type;
        $data['object_type']=$object_type;
        $data['customer'] = $customerids;
        if ($object_type == 1) {
            $data['fk_org'] = $this->orgId;
        } else if ($object_type == 2) {
            $data['fk_course'] = $courseid;
        }
        $rs = org_api::bindCustomerInfo($data);
        if ($rs->result->code == 0) {
            $this->outputJson( 0,  '绑定成功');
        }else if($rs->result->code == -2){
            $this->outputJson( -1,  '最多绑定4个客服');
        }else {
            $this->outputJson(-1, '绑定失败');
        }
    }

    //解除绑定,ajax
    public function pageunbindCustomerInfoAjax() {
        $pid = !empty($_POST['pid']) ? (int) $_POST['pid'] : '';
        if(empty($pid)){
            $this->outputJson(-1,'系统参数错误');
        }
        $res = org_api::unbindCustomerInfo($pid);
        if ($res->result->code == 0) {
            $this->outputJson( 0, '删除成功');
        } else {
            $this->outputJson( -1,'删除失败');
        }
    }

    private function outputJson($code, $msg = '', $data = '') {
        $result = array('code' => 0, 'msg' => '', 'data' => '');
        $result['code'] = $code;
        $result['msg'] = $msg;
        $result['data'] = $data;
        exit(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    public function pageSendDocument(){
        $courseId   = !empty($_POST['courseId']) ? (int)$_POST['courseId'] : 0;
        $courseInfo = course_api::getCourseOne($courseId);
        if(empty($courseInfo) || $courseInfo->user_id != $this->orgOwner){
            $this->outputJson(-1, '该课程不存在');
        }
        if($courseInfo->user_id != $this->orgOwner){
            $this->outputJson(-2, '不是该机构的课程');
        }

        $type = (!empty($_POST['type'])) ? (int)$_POST['type'] : 0;
        $res  = course_api::setcourseimg($courseId, array('document'=>$type));
        if(!$res) $this->outputJson(-3, '操作失败');

        $this->outputJson(0, '操作成功');
    }

}
