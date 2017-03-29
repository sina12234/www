<?php

class org_rightWindow extends STpl {

    public function pageMenu($inPath) {
        $subnav = "";
        if (!empty($inPath[3])) {
            $subnav = $inPath[3];
        }
        $this->assign("subnav", $subnav);
        return $this->render("/org/menu.html");
    }

    //机构客服设置右侧浮窗
    public function pagerightWindow() {
        utility_cache::pageCache(1800);
        $org = user_organization::subdomain();
        if (!empty($org->userId)) {
            $orgInfo = user_organization::getOrgByOwner($org->userId);
        }
        $orgId = !empty($orgInfo->oid) ? $orgInfo->oid : 0;
        $customer = org_api::customerServicesQqList($orgId);
        //print_r($customer);die;
        $customerData = !empty($customer->data) ? $customer->data : '';
        if (!empty($customerData->weima)) {
            foreach ($customerData->weima as $k => $v) {
                $v->type_value = utility_cdn::file($v->type_value);
            }
        }
        $qqunArr = array();
        if (!empty($customerData->qq)) {
            foreach ($customerData->qq as $k => $v) {
                $qqunArr[] = $v->type_value;
            }
            $qqStr = implode(":", $qqunArr) . ":";
            $this->assign("qqStr", $qqStr);
        }
        if (!empty($customerData->qqun)) {
            foreach ($customerData->qqun as $k => $v) {
                $strCode = preg_replace("/<img.*?>/si", $v->type_name, $v->type_code);
                $v->type_code = $strCode;
            }
        }
        $this->assign("customerData", $customerData);
        //客服QQ
        $qqArr = array();
        $qqInfo = array();
        if (!empty($customerData->qq)) {
            foreach ($customerData->qq as $k => $v) {
                $qqArr[] = $v->type_value;
            }
            $qqStr = implode(":", $qqArr) . ":";
            //请求接口在线状态
            if (!empty($qqStr)) {
                $url = "http://webpresence.qq.com/getonline?Type=1&{$qqStr}";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($httpCode > 0) {
                    foreach ($customerData->qq as $k => $v) {
                        $v->qqStatus = 0;
                    }
                    $this->assign('qqCustomerInfo', $customerData->qq);
                    return $this->render("/org/rightWindow.html");
                } else {
                    $str = file_get_contents($url);
                }
                curl_close($ch);
                $arrResponse = explode(";", rtrim($str, ";"));
                $dataStatus = array();
                foreach ($arrResponse as $k => $v) {
                    $status = substr(strstr($v, "="), 1);
                    $dataStatus[$k] = $status;
                }
                if (!empty($customerData->qq)) {
                    foreach ($customerData->qq as $k => $v) {
                        $v->qqStatus = isset($dataStatus[$k]) ? $dataStatus[$k] : 0;
                    }
                }
                $this->assign('qqCustomerInfo', $customerData->qq);
            }
        }
        return $this->render("/org/rightWindow.html");
    }

    //机构客服设置右侧浮窗
    public function pagerightWindownew() {
        utility_cache::pageCache(300);
        $org = user_organization::subdomain();
        if (!empty($org->userId)) {
            $orgInfo = user_organization::getOrgByOwner($org->userId);
        }
        $orgId = !empty($orgInfo->oid) ? $orgInfo->oid : 0;
        $customer = org_api::customerServicesQqList($orgId); //获取二维码，电话
        //print_r($customer);
        //die;
        $customerData='';
        if (!empty($customer->data)) {
            $customerData = $customer->data;
            if (!empty($customerData->weima)) {
                foreach ($customerData->weima as $k => $v) {
                    $v->type_value = utility_cdn::file($v->type_value);
                }
            }
        }
        //print_r($customerData);
        $this->assign("customerData", $customerData);
        //获取新版机构qq及qq群
        $qqdata='';
        $qqservice=org_api::getCusRelationList(array('orgid'=>$orgId));
        //print_r($qqservice);die;
        if(!empty($qqservice->data)){
            $qqdata=$qqservice->data;
            if (!empty($qqdata->qqun)) {
                foreach ($qqdata->qqun as $k => $v) {
                    $strCode = preg_replace("/<img.*?>/si", $v->type_name, $v->ext);
                    $v->ext = $strCode;
                }
            }
        }
        //print_r($qqdata);die;
        $this->assign("qqData", $qqdata);
        //判定qq是否在线，暂时无用
        /*$qqArr = array();
        $qqInfo = array();
        if (!empty($qqdata->qq)) {
            foreach ($qqdata->qq as $k => $v) {
                $qqArr[] = $v->type_value;
            }
            $qqStr = implode(":", $qqArr) . ":";
            //请求接口在线状态
            if (!empty($qqStr)) {
                $url = "http://webpresence.qq.com/getonline?Type=1&{$qqStr}";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($httpCode > 0) {
                    foreach ($qqdata->qq as $k => $v) {
                        $v->qqStatus = 0;
                    }
                    print_r($qqdata->qq);die;
                    $this->assign('qqCustomerInfo', $qqdata->qq);
                    return $this->render("/org/rightWindow.html");
                } else {
                    $str = file_get_contents($url);
                }
                curl_close($ch);
                $arrResponse = explode(";", rtrim($str, ";"));
                $dataStatus = array();
                foreach ($arrResponse as $k => $v) {
                    $status = substr(strstr($v, "="), 1);
                    $dataStatus[$k] = $status;
                }
                if (!empty($qqdata->qq)) {
                    foreach ($qqdata->qq as $k => $v) {
                        $v->qqStatus = isset($dataStatus[$k]) ? $dataStatus[$k] : 0;
                    }
                }
                $this->assign('qqCustomerInfo', $qqdata->qq);
            }
        }*/
        return $this->render("/org/rightWindownew.html");
    }

}
