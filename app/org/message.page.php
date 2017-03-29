<?php
class org_message extends STpl{
    function __construct(){
        //如果没有登陆到登陆界面
        $this->user = user_api::loginedUser();
        if(empty($this->user)){
            $this->redirect("/site.main.login");
        }
        $domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
        $this->domain = $domain_conf->domain;
        $org=user_organization::subdomain();
        if(!empty($org)){
            $this->orgOwner=$org->userId; //机构所有者id 以后会根据域名而列取
        }else{
            header('Location: https://www.'.$this->domain);
        }
        $this->orgInfo=user_organization::getOrgByOwner($this->orgOwner);
        $this->assign('is_pro',isset($this->orgInfo->is_pro) ? $this->orgInfo->is_pro : 0);
        $this->orgId = $this->orgInfo->oid;
        //判断管理员
        $isAdmin=user_api::isAdmin($this->orgOwner,$this->user['uid']);
        if($isAdmin===false){
            header('Location: //'.$org->subdomain.'.'.$this->domain);
        }
    }


    //短信发送记录
    public function pageMessageSendList(){
        $data = array();
        if(!empty($_GET['mobile'])) $data['mobile'] = isset($_GET['mobile'])&& !empty(trim($_GET['mobile']))  ? (int)trim($_GET['mobile']) : '';
        if(!empty($_GET['sms_type'])) $data['sms_type'] =isset($_GET['sms_type'])&& !empty(trim($_GET['sms_type'])) ?  (int)$_GET['sms_type'] : '';
        if(!empty($_GET['status'])) $data['status'] = isset($_GET['status'])&& !empty(trim($_GET['status'])) ? (int)$_GET['status'] : '';
        if(!empty($_GET['start_time'])) $data['start_time'] = isset($_GET['start_time'])&& !empty(trim($_GET['start_time'])) ? trim($_GET['start_time']) : '';
        if(!empty($_GET['end_time'])) $data['end_time'] = isset($_GET['end_time'])&& !empty(trim($_GET['end_time'])) ? trim($_GET['end_time']) : '';
        if(!empty($_GET['course_name'])) $data['course_name'] = isset($_GET['course_name'])&& !empty(trim($_GET['course_name'])) ? htmlspecialchars(strip_tags($_GET['course_name'])) : '';
        $data['page'] = isset($_GET['page'])&& !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $data['org_id'] = $this->orgId;
        $data['page_size'] = 20;
        $ret = sms_api::messageSendList($data);
        $this->assign('data',$ret);
        $path='/org/message/MessageSendList';
        $path_page = utility_tool::getUrl($path);
        $this->assign('sms_type',!empty($data['sms_type']) ? $data['sms_type'] : '');
        $this->assign('mobile',!empty($data['mobile']) ? $data['mobile']  : '');
        $this->assign('status',!empty($data['status']) ? $data['status'] : 6 );
        $this->assign('start_time',!empty($data['start_time']) ? $data['start_time'] : '');
        $this->assign('end_time',!empty($data['end_time']) ? $data['end_time'] : '');
        $this->assign('course_name',!empty($data['course_name']) ? $data['course_name'] : '');
        $this->assign('pagepath',$path_page);
        $this->assign('totalpage',empty($ret->result->totalPage) ? 0 : $ret->result->totalPage);
        $this->assign('page',$data['page']);
        $this->render("sms/sms_detail.html");
    }


    //充值1
    public function pageSmsCharge(){
        $params = new stdClass();
        $params->org_id = $this->orgId;
        $index_data = sms_api::SmsIndex($params);
        $this->assign('data',$index_data);
        $this->assign('org_id',$this->orgId);
        return $this->render("sms/duanxin-chongzhi.html");
    }
    //充值2
    public function pageSmsChargeSecond(){
        if(empty($_GET['money']) || !is_numeric($_GET['money']) || $_GET['money']<10 ) {
            $this->redirect('/org/message/smscharge');
        }
        $money = (int)($_GET['money']*100)/100;
        $this ->assign('money',$money);
        $this->assign('pay_money',(int)$money*100);
        $this->assign('object_type',21);
        $this->assign('object_id',(int)$this->orgId);
        $this->render('sms/recharge.details.html');
    }

    //短信首页
    public function pageSmsIndex(){
        $params = new stdClass();
        $params->org_id = $this->orgId;
        $index_data = sms_api::SmsIndex($params);
        $this->assign('data',$index_data);
        //今日已经发送总条数
        $num = 0;
        $sms_type_one= 0;
        $sms_type_two = 0;
        $sms_type_three = 0;
        $sms_type_four = 0;
        if(!empty($index_data->sms_num)){
            foreach($index_data->sms_num as $v){
                $num += $v->num;
                if($v->sms_type==1){
                    $sms_type_one = $v->num;

                }elseif($v->sms_type==2){
                    $sms_type_two = $v->num;

                }elseif($v->sms_type==3){
                    $sms_type_three = $v->num;

                }elseif($v->sms_type==4){
                    $sms_type_four = $v->num;

                }
            }
        }
        $this->assign('sms_type_one',$sms_type_one);
        $this->assign('sms_type_two',$sms_type_two);
        $this->assign('sms_type_three',$sms_type_three);
        $this->assign('sms_type_four',$sms_type_four);
        $this->assign('num',$num);
        $this->assign('org_id',$this->orgId);
        $this->assign('subnav','sms');
        return $this->render('sms/sms_index.html');
    }

    //获取该服务的 操作信息
    public function pageGetService(){
        if(empty($_POST['org_id']) || empty($_POST['service_id'])) return interface_func::setMsg(3002);
        $data = new stdClass();
        $data->org_id = (int)$_POST['org_id'];
        $data->service_id = (int)$_POST['service_id'];
        return sms_api::smsOperation($data);
    }

    //短信服务操作
    public function pageMessageOperation(){
        if(empty($_POST['org_id']) || empty($_POST['service_id'])) return interface_func::setMsg(3002);
        if(!isset($_POST['course_free']) || !isset($_POST['course_charge']) || !isset($_POST['status'])) return interface_func::setMsg(3002);
        $data= new stdClass();
        $data->org_id = (int)$this->orgId;
        $data->service_id = (int)$_POST['service_id'];
        $data->course_free = (int)$_POST['course_free'];
        $data->course_charge = (int)$_POST['course_charge'];
        $data->status = (int)$_POST['status'];
        return sms_api::smsUpdate($data);

    }

    //充值记录
    public function pageSmsRecharge(){
        $time_start = !empty($_GET['time_start']) ? trim($_GET['time_start']) : '';
        $time_end = !empty($_GET['time_end']) ? trim($_GET['time_end']) : '';
        $page = !empty($_GET['page']) ? (int)(trim($_GET['page'])) : 1;
        $params = new stdClass();
        $params->time_start = $time_start;
        $params->time_end = $time_end;
        $params->org_id = (int)$this->orgId;
        $params->page = $page;
        $params->page_size = 20;
        $ret = sms_api::smsRecharge($params);
        $path='/org/message/SmsRecharge';
        $path_page = utility_tool::getUrl($path);
        $this->assign('time_start',$time_start);
        $this->assign('time_end',$time_end);
        $this->assign('path_page',$path_page);
        $this->assign('totalpage',isset($ret->result->totalPage) ? $ret->result->totalPage : 0);
        $this->assign('page',$page);
        $this->assign('data',!empty($ret->result->items) ? $ret->result->items : []);
        $this->render('sms/recharge.html');
    }

    //充值excel
    public function pageSmsRechargeExcel(){
        $time_start = !empty($_GET['time_start']) ? trim($_GET['time_start']) : '';
        $time_end = !empty($_GET['time_end']) ? trim($_GET['time_end']) : '';
        $page =  1;
        $params = new stdClass();
        $params->time_start = $time_start;
        $params->time_end = $time_end;
        $params->org_id = (int)$this->orgId;
        $params->page = 1;
        $params->page_size = 3000;
        $ret = sms_api::smsRecharge($params);
        if(empty($ret->result->items)){
            $this->redirect('/org/message/SmsRecharge');
        }
        require_once(ROOT_LIBS . "/phpexcel/PHPExcel.class.php");
        require_once(ROOT_LIBS . "/phpexcel/PHPExcel/Writer/Excel2007.php");
        //实例化表格
        $objPHPExcel = new PHPExcel();
        //基本设置
        $fileName = "短信账户充值记录";
        $objProps = $objPHPExcel->getProperties();
        $objProps->setCreator("www.yunke.com");
        $objProps->setLastModifiedBy("www.yunke.com");
        $objProps->setTitle("Office XLS Test Document");
        $objProps->setSubject("Office XLS Test Document, yunke");
        $objProps->setDescription("Test document, generated by PHPExcel.");
        $objProps->setKeywords("office excel PHPExcel");
        $objProps->setCategory("报表");
        $objPHPExcel->setActiveSheetIndex(0);
        $objActiveSheet = $objPHPExcel->getActiveSheet();
        $objActiveSheet->setTitle($fileName);
        //设置表头
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objActiveSheet->getStyle('A1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('B1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('C1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('D1')->getFont()->setBold(true);

        $objActiveSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $objActiveSheet->setCellValue('A1', '充值id');
        $objActiveSheet->setCellValue('B1', '时间');
        $objActiveSheet->setCellValue('C1', '充值金额');
        $objActiveSheet->setCellValue('D1', '状态');

        // 防止科学计数
        $objActiveSheet->getStyle('A1')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        //填充表格内容
        $j = 2;
        foreach ($ret->result->items as $k => $v) {
            if($v->status==2){
                $v->st = '成功';
            }else{
                $v->st = '失败';
            }
            if(empty($v->remark)) $v->remark = '';
            $objActiveSheet->setCellValue('A' . $j, $v->fk_order);
            $objActiveSheet->setCellValue('B' . $j, $v->create_time);
            $objActiveSheet->setCellValue('C' . $j, $v->price_old /100);
            $objActiveSheet->setCellValue('D' . $j, $v->st);

            $objActiveSheet->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('B' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objActiveSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $j++;
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        if(empty($fileName)){
            $ftime = time();
            $outputFileName = $ftime . ".xls";
        }else{
            $outputFileName = $fileName . ".xls";
        }
        //加此防乱码
        ob_clean();
        @header("Pragma: public");
        @header("Expires: 0");
        @header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
        @header("Content-Type:charset=UTF-8");
        @header("Content-Type:application/force-download");
        @header("Content-Type:application/vnd.ms-execl");
        @header("Content-Type:application/octet-stream");
        @header("Content-Type:application/download");
        @header('Content-Disposition:attachment;filename="' . $outputFileName . '"');
        @header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');

    }


    //发送短息excel
    public function pageSendMessageExcel(){
        $data = array();
        if(!empty($_GET['mobile'])) $data['mobile'] = isset($_GET['mobile'])&& !empty(trim($_GET['mobile']))  ? (int)trim($_GET['mobile']) : '';
        if(!empty($_GET['sms_type'])) $data['sms_type'] =isset($_GET['sms_type'])&& !empty(trim($_GET['sms_type'])) ?  (int)$_GET['sms_type'] : '';
        if(!empty($_GET['status'])) $data['status'] = isset($_GET['status'])&& !empty(trim($_GET['status'])) ? (int)$_GET['status'] : '';
        if(!empty($_GET['start_time'])) $data['start_time'] = isset($_GET['start_time'])&& !empty(trim($_GET['start_time'])) ? trim($_GET['start_time']) : '';
        if(!empty($_GET['end_time'])) $data['end_time'] = isset($_GET['end_time'])&& !empty(trim($_GET['end_time'])) ? trim($_GET['end_time']) : '';
        if(!empty($_GET['course_name'])) $data['course_name'] = isset($_GET['course_name'])&& !empty(trim($_GET['course_name'])) ? htmlspecialchars(strip_tags($_GET['course_name'])) : '';
        $data['page'] =  1;
        $data['org_id'] = $this->orgId;
        $data['page_size'] = 3000;
        $result = sms_api::messageSendList($data);
        if(empty($result->result->items)) {
            $this->redirect('/org/message/MessageSendList');
        }
        require_once(ROOT_LIBS . "/phpexcel/PHPExcel.class.php");
        require_once(ROOT_LIBS . "/phpexcel/PHPExcel/Writer/Excel2007.php");
        //实例化表格
        $objPHPExcel = new PHPExcel();
        //基本设置
        $fileName = "短信发送明细";
        $objProps = $objPHPExcel->getProperties();
        $objProps->setCreator("www.yunke.com");
        $objProps->setLastModifiedBy("www.yunke.com");
        $objProps->setTitle("Office XLS Test Document");
        $objProps->setSubject("Office XLS Test Document, yunke");
        $objProps->setDescription("Test document, generated by PHPExcel.");
        $objProps->setKeywords("office excel PHPExcel");
        $objProps->setCategory("报表");
        $objPHPExcel->setActiveSheetIndex(0);
        $objActiveSheet = $objPHPExcel->getActiveSheet();
        $objActiveSheet->setTitle($fileName);
        //设置表头
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objActiveSheet->getStyle('A1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('B1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('C1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('D1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('E1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('F1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('G1')->getFont()->setBold(true);

        $objActiveSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objActiveSheet->setCellValue('A1', '手机号');
        $objActiveSheet->setCellValue('B1', '短信类型');
        $objActiveSheet->setCellValue('C1', '短信内容');
        $objActiveSheet->setCellValue('D1', '课程');
        $objActiveSheet->setCellValue('E1', '发送时间');
        $objActiveSheet->setCellValue('F1', '状态');
        $objActiveSheet->setCellValue('G1', '备注');
        // 防止科学计数
        $objActiveSheet->getStyle('A1')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        //填充表格内容
        $j = 2;
        foreach ($result->result->items as $k => $v) {
            if($v->status==0){
                $v->st = '成功';
            }else{
                $v->st = '失败';
            }
            if(empty($v->remark)) $v->remark = '';
            $objActiveSheet->setCellValue('A' . $j, $v->mobile);
            $objActiveSheet->setCellValue('B' . $j, $v->sms_name);
            $objActiveSheet->setCellValue('C' . $j, $v->content);
            $objActiveSheet->setCellValue('D' . $j, $v->course_name);
            $objActiveSheet->setCellValue('E' . $j, $v->create_time);
            $objActiveSheet->setCellValue('F' . $j, $v->st);
            $objActiveSheet->setCellValue('G' . $j, $v->remark);
            $objActiveSheet->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('B' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('E' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('G' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $j++;
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        if(empty($fileName)){
            $ftime = time();
            $outputFileName = $ftime . ".xls";
        }else{
            $outputFileName = $fileName . ".xls";
        }
        //加此防乱码
        ob_clean();
        @header("Pragma: public");
        @header("Expires: 0");
        @header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
        @header("Content-Type:charset=UTF-8");
        @header("Content-Type:application/force-download");
        @header("Content-Type:application/vnd.ms-execl");
        @header("Content-Type:application/octet-stream");
        @header("Content-Type:application/download");
        @header('Content-Disposition:attachment;filename="' . $outputFileName . '"');
        @header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
    }
}