<?php
require_once(ROOT_LIBS."/phpexcel/PHPExcel.class.php");
require_once(ROOT_LIBS."/phpexcel/PHPExcel/Writer/Excel2007.php");
ini_set("max_execution_time", 2400); // s 40 分钟 
//修改此次的最大运行内存 
ini_set("memory_limit", 1048576000); // Byte 1000 兆，即 1G
class phpexcel_votetenderer extends STpl{
      private $orgOwner='';
      private $user='';
      public  function __construct(){
        $this->user = user_api::loginedUser();
        if (empty($this->user)) {
            $this->redirect("/site.main.login");
        }   
        $org=user_organization::subdomain();
        $this->orgOwner = !empty($org) ? $org->userId : 0;
    } 
	public function pageEntry($inPath){

		if(empty($inPath[3])){
			return $this->redirect("/org.vote.list");
		}
		$voteId = (int)($inPath[3]);
		$retJudgeuser = utility_judgeid::authrole($this->user['uid'],$this->orgOwner);
		if(!$retJudgeuser){
			//没有权限
			$this->redirect("/site.main.entry");
			die("No authority");
		}
	
		$list = org_api::getTenderer(array('voteId'=>$voteId), 1, 4000);

		if(empty($list['data'])){
			header("Content-type:text/html;charset=utf-8");
            echo '<script type="text/javascript">alert("没有学生");location.href="/teacher.manage.student"</script>';
            exit;
		}

        //实例化表格
        $objPHPExcel = new PHPExcel(); 
        //保存excel—2007格式
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        //基本设置
        $fileName="学生列表";
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
        for ($i = 1; $i <=10; $i++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(22);
        }
        $objActiveSheet = $objPHPExcel->getActiveSheet();
        $objActiveSheet->setTitle($fileName);

        //设置表头
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
        $objActiveSheet->setCellValue('A1', '姓名');
        $objActiveSheet->setCellValue('B1', '性别');
        $objActiveSheet->setCellValue('C1', '手机号');
        $objActiveSheet->setCellValue('D1', '年龄');
        $objActiveSheet->setCellValue('E1', '学校');
        $objActiveSheet->setCellValue('F1', '投票时间');
        $objActiveSheet->setCellValue('G1', '选项');
    
        //设置第二列为文本格式 防止科学计数
        $objActiveSheet->getStyle('C1')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        //填充表格内容
        $j=2;
        foreach($list['data'] as $k=>$v){
            $objActiveSheet->setCellValue('A' . $j, $v['name']);
            $objActiveSheet->setCellValue('B' . $j, $v['gender']);
            $objActiveSheet->setCellValue('C' . $j, ' '.$v['mobile']);
            $objActiveSheet->setCellValue('D' . $j, $v['age']);
            $objActiveSheet->setCellValue('E' . $j, $v['school']);
            $objActiveSheet->setCellValue('F' . $j, $v['voteTime']);
            $objActiveSheet->setCellValue('G' . $j, $v['optionId']);
            $objActiveSheet->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('B' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
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
    /*
	*机构-教师管理-我的学生excel导出
	*@return array  $data
	*/
	/*
	*机构-学生管理excel导出
	*@return array  $data
	*/
	
}


