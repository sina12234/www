<?php
/*
 * 课程详情页客服qq列表
 */

class course_customerTools extends STpl{
    
    /*
     * 课程详情页客服qq列表
     * param courseid
     * return json
     */
    //获取机构首页、课程客服列表,ajax
    public function pagegetCusRelationListAjax(){
        $courseid=!empty($_POST['courseid']) ? (int) $_POST['courseid'] : '';
        if(empty($courseid)){
            $this->outputJson(-1,'系统参数错误');
        }
        
        $params['courseid']=$courseid;//课程详情页客服
        
        $customer = org_api::getCusRelationList($params); //print_r($customer);
        if ($customer->result->code ==0) {
            $customerData = $customer->data;
            $this->outputJson(0,'suc',$customerData);
        }else{
            $this->outputJson(-1,'fail');
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
