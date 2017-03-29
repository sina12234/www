<?php
require_once(ROOT_LIBS."/phpexcel/PHPExcel.class.php");
require_once(ROOT_LIBS."/phpexcel/PHPExcel/Writer/Excel2007.php");
ini_set("max_execution_time", 2400); // s 40 分钟 
//修改此次的最大运行内存 
ini_set("memory_limit", 1048576000); // Byte 1000 兆，即 1G
class phpexcel_member extends STpl{
	private $domain;
	private $orgOwner;
	private $orgInfo;
	private $user;
	private $orgId;
    public  function __construct(){
		$this->user = user_api::loginedUser();
        if(empty($this->user)){
            $this->redirect("/site.main.login");
        }
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
        $org = user_organization::subdomain();
        if(!empty($org)){
            $this->orgOwner=$org->userId;
        }else{
            header('Location: https://www.'.$this->domain);
        }
        $this->orgInfo=user_organization::getOrgByOwner($this->orgOwner);
		if(!empty($this->orgInfo->oid)){
			$this->orgId = $this->orgInfo->oid;
		}else{
			header('Location: https://www.'.$this->domain);
		}
        //判断管理员
        $isAdmin=user_api::isAdmin($this->orgOwner,$this->user['uid']);
        if($isAdmin===false){			
            header('Location: //'.$org->subdomain.'.'.$this->domain);
        }
    } 
	public function pageExportMemberList($inPath){
		$setId = isset($_GET['setId'])?$_GET['setId']:'';
		if(empty($setId)){
			$this->redirect('/org.member');
			exit;
		}
		$status = isset($_GET['status'])?$_GET['status']:'';
		$memberList = user_organization::getMemberListByMembersetId(1,5000,$setId,$status);
		$now = date('Y-m-d H:i:s',time());
		$year = date('Y',time());
		if(!empty($memberList)){
			foreach($memberList as $mo){
				if($mo->end_time <= $now){
					$mo->member_status = '已失效';
				}else{
					$mo->member_status = '有效';
				}
				$endYear = date('Y',strtotime($mo->end_time));
				if($year == $endYear){
					$mo->end_time = date('m-d H:i',strtotime($mo->end_time));
				}else{
					$mo->end_time = date('Y-m-d H:i',strtotime($mo->end_time));
				}
				if($mo->last_type == 1){
					$mo->last_type = '付费购买';
				}elseif($mo->last_type == 2){
					$mo->last_type = '后台添加';
				}elseif($mo->last_type == 4){
					$mo->last_type = '管理员添加';
				}
				$data[] = $mo;
			}
		}else{
			$data = array();
		}
		//实例化表格
        $objPHPExcel = new PHPExcel(); 
        //保存excel—2007格式
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        //基本设置
        $fileName="会员列表";
        $objProps = $objPHPExcel->getProperties();
        $objProps->setCreator("www.yunke.com");
        $objProps->setLastModifiedBy("www.yunke.com");
        $objProps->setTitle("Office XLS Test Document");
        $objProps->setSubject("Office XLS Test Document, yunke");
        $objProps->setDescription("Test document, generated by PHPExcel.");
        $objProps->setKeywords("office excel PHPExcel");
        $objProps->setCategory("报表");
        $objPHPExcel->setActiveSheetIndex(0);
		
		 // 行高
        for ($i = 1; $i <=200; $i++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(22);
        }
		
        $objActiveSheet = $objPHPExcel->getActiveSheet();
        $objActiveSheet->setTitle($fileName);

        //设置表头
		$objActiveSheet->getColumnDimension('A')->setWidth(30);
		$objActiveSheet->getColumnDimension('B')->setWidth(30);
		$objActiveSheet->getColumnDimension('C')->setWidth(25);
		$objActiveSheet->getColumnDimension('D')->setWidth(20);
		$objActiveSheet->getColumnDimension('E')->setWidth(30);
        $objActiveSheet->getStyle('A1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('B1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('C1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('D1')->getFont()->setBold(true);
		$objActiveSheet->getStyle('E1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActiveSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->setCellValue('A1', '用户名');
        $objActiveSheet->setCellValue('B1', '手机号');
		$objActiveSheet->setCellValue('C1', '到期时间');
        $objActiveSheet->setCellValue('D1', '状态');
		$objActiveSheet->setCellValue('E1', '开通类型');

        $j=2;
        foreach($data as $k=>$v){
            $objActiveSheet->setCellValue('A' . $j, $v->real_name);
            $objActiveSheet->setCellValue('B' . $j, $v->mobile);
            $objActiveSheet->setCellValue('C' . $j, $v->end_time);
            $objActiveSheet->setCellValue('D' . $j, $v->member_status);
			$objActiveSheet->setCellValue('E' . $j, $v->last_type);
            $objActiveSheet->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('B' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objActiveSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActiveSheet->getStyle('E' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $j++;
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        if(empty($fileName)){
            $ftime = time();
            $outputFileName = $ftime . ".xls";
        }else{
            $outputFileName = $fileName . ".xls";
        }
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

