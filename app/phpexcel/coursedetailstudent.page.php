<?php
require_once(ROOT_LIBS."/phpexcel/PHPExcel.class.php");
require_once(ROOT_LIBS."/phpexcel/PHPExcel/Writer/Excel2007.php");
ini_set("max_execution_time", 2400); // s 40 分钟 
//修改此次的最大运行内存 
ini_set("memory_limit", 1048576000); // Byte 1000 兆，即 1G
class phpexcel_coursedetailstudent extends STpl{
      public  function __construct(){
        $user = user_api::loginedUser();
        if (empty($user)) {
            $this->redirect("/site.main.login");
        }   
        $org=user_organization::subdomain();
        $this->orgOwner = !empty($org) ? $org->userId : 0;
    } 
	public function pageEntry($inPath){
		//如果没有登陆到登陆界面
		$user = user_api::loginedUser();
		if(empty($user)){
			$this->redirect("index/main/login");
		}
		//  增加筛选条件
		$course_id = isset($_GET["course_id"]) ? $_GET["course_id"]:0;
		$classId = isset($_GET["class_id"]) ? $_GET["class_id"]:0;
		$lege = utility_judgeid::courseid($course_id,$user['uid'],$this->orgOwner);
		if($lege ===false){
			return $this->redirect("/site.main.entry");
		}
		$judgeClass = utility_judgeid::classBelonguid($classId,$user['uid']);
		if(empty($judgeClass)){
			return $this->redirect("/site.main.entry");
		}
		$showTag = 1; //是否全显示学生
		if($classId ==0 && $course_id==0){
			$showTag= 0;
		}
		$count_all = 0;

		//我的学生
		//$page = 1;
		//$length = 10;
		$showTag = 0;

		//判断是否是手机号
		$likemobile = 0;
		$likename = 0;
		if(!empty($_GET["sedata"])){
			$phonenumber = $_GET["sedata"];
			if(is_numeric($phonenumber)){
			//if(preg_match("/1[3458]{1}\d{9}$/",$phonenumber)){}
				$likemobile = 1;
				$showTag =1;
			}else{
				$likename = 1;
				$showTag =1;
			}
			
		}

		$regdata = array(
			'class_id'=>$classId,
		);
		//获取courseid是这些的老师的报名学生
		$reglist = course_api::listRegistrationBycond($regdata);
		$user_ids_arr = array();
		//筛选出这批人的userid
		if(!empty($reglist->data)){
			foreach($reglist->data as $k=>$v){
				$user_ids_arr[$v->uid]=$v->uid;
			}
		}

		if($likemobile){
			$mobile = $_GET['sedata'];
			$user_like_list = user_api::listUserIdsBylikeMobileArr($user_ids_arr,$mobile);
		}
		if($likename){
			$sreal_name = $_GET['sedata'];
			$user_like_list = user_api::listUserIdsBylikeNameArr($user_ids_arr,$sreal_name);
		}

		//like查询出的用户  组合userid
		if(!empty($user_like_list->data)){
			foreach($user_like_list->data as $k=>$v){
				$list_reg_uid_arr2[$v->user_id] = $v->user_id;
			}
			//如果是模糊查询的就不用在查询一次库直接拿出数据
			//真实姓名
			//前端显示的配对真实姓名数组
			foreach($user_like_list->data as $k=>$v){
				$realNameArr[$v->user_id] = $v;
			}
			//结果	
			 //查询出这个班中是这些id的报名的人
			$regdata2 = array(
				//'course_ids'=>150,
				'class_id'=>$classId,
				'uids'=>$list_reg_uid_arr2,
			);
			if(!empty($course_id)){
				$regdata2["course_ids"] = $course_id;
			}
			//结果
			$reglist1 = course_api::listRegistrationBycond($regdata2);
		}

		$ret = array();
		$ret = $reglist;

		//模糊查询部分替换掉 全查部分数据
		if(!empty($reglist1->data)){
			$ret = new stdclass;
			$ret->data = $reglist1->data;
		}elseif($likename ||$likemobile){
			$ret = new stdclass;
			$ret->data = 0;
		}
		$totalStudent = 0;
		if(!empty($ret->data)){
			$totalStudent = count($ret->data);
		}
		
		$this->assign('showTag',$showTag);
		$this->assign("totalStudent",$totalStudent);
		$this->assign("ret",$ret);
	



        $data=array();
		if(!empty($ret->data)){
				if(!empty($ret->data)){
					foreach($ret->data as $k=>$v){
						$thisdata = array(
							'name' => !empty($v->user_info->real_name) ? $v->user_info->real_name:'未设置',
							'mobile' => !empty($v->user_info->mobile) ? $v->user_info->mobile:'',
							//'course_name' =>4 ,
							//'class_name' => 5,
							'reg_time' => date("Y-m-d",strtotime($v->create_time)),
						);

						if(!empty($v->user_info->gender)){
							if($v->user_info->gender ==1){
								$thisdata['gender'] = '男';
							}elseif($v->user_info->gender ==2){
								$thisdata['gender'] = '女';
							}else{
								$thisdata['gender'] = '未设置';
							}
						}else{
							$thisdata['gender'] = '未设置';
						}

						if(!empty($v->user_info->province)){
							if($v->user_info->province==$v->user_info->city){
								$thisdata['area'] = $v->user_info->province;
							}else{
								$thisdata['area'] = $v->user_info->province.",".$v->user_info->city;
							}
						}else{
							$thisdata['area'] = '未设置';
						}

						if(!empty($classShowdata[$v->class_id]->name)){
							$thisdata['class_name'] = $classShowdata[$v->class_id]->name;
						}else{
							if(!empty($classesdata[$v->class_id]->name)){
								$thisdata['class_name'] = $classesdata[$v->class_id]->name;
							}else{
								$thisdata['class_name'] = "班级为空";
							}
						}

						/*if(!empty($course->title)){
							$thisdata['course_name'] = $course->title;
						}else{
						}
*/
							if(!empty($coursedata[$v->cid]["title"])){
								$thisdata['course_name'] = $coursedata[$v->cid]["title"];
							}else{
								$thisdata['course_name'] = '课程加载失败';
							}

						$data[] = $thisdata;

					}
				}
		}else{
			header("Content-type:text/html;charset=utf-8");
			echo '<script type="text/javascript">alert("没有学生");location.href="/teacher.course.detail/'.$course_id.'/'.$classId.'"</script>';
		//	header("Location: /index.teacher.student"); 
           exit;
		}


/*

        $params = array(
            'condition' => "fk_user_owner={$this->orgOwner} AND status=1 ",
            'page'      => !empty($_REQUEST['page']) && (int)($_REQUEST['page']) ? (int)($_REQUEST['page']) : 1,
            'length'    => 10000, 
            'item'      => array('fk_user', 'count(*) as courseNum'),
            'groupBy'   => array('fk_user'),
            'orgOwner'  => $this->orgOwner
        );  
        $list = course_api::getOrgStudentList($params);
        if(empty($list->data->list)){
           echo '<script type="text/javascript">alert("没有学生")</script>';
           exit;
        }
        $data=array();
        foreach ($list->data->list as $v) {
            $data[] = array(
                'id' => !empty($v->fk_user) ? $v->fk_user : '',
                'name' => !empty($v->real_name) && trim($v->real_name) ? $v->real_name : (!empty($v->name) ? $v->name : ''),
                'gender' => !empty($v->gender) ? ($v->gender==1 ? '男' : '女') : '',
                'age' => !empty($v->birthday) ? utility_tool::getAge($v->birthday) : '',
                'school' => !empty($v->school_name) ? $v->school_name : '',
                'mobile' => !empty($v->mobile) ? $v->mobile : '',
                'courseNum' => !empty($v->courseNum) ? $v->courseNum : 0,
                'thumb' => !empty($v->thumb_small) ? $v->thumb_small : ''
            );
        }
*/
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
        //$objActiveSheet->getStyle('E1')->getFont()->setBold(true);
        //$objActiveSheet->getStyle('F1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('E1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //$objActiveSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       // $objActiveSheet->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->setCellValue('A1', '姓名');
        $objActiveSheet->setCellValue('B1', '性别');
        $objActiveSheet->setCellValue('C1', '手机号');
        $objActiveSheet->setCellValue('D1', '地区');
        //$objActiveSheet->setCellValue('E1', '课程');
        //$objActiveSheet->setCellValue('F1', '班级');
        $objActiveSheet->setCellValue('E1', '报名时间');
    
        //设置第二列为文本格式 防止科学计数
        $objActiveSheet->getStyle('C1')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        //填充表格内容
        $j=2;
        foreach($data as $k=>$v){
            $objActiveSheet->setCellValue('A' . $j, $v['name']);
            $objActiveSheet->setCellValue('B' . $j, $v['gender']);
            $objActiveSheet->setCellValue('C' . $j, ' '.$v['mobile']);
            $objActiveSheet->setCellValue('D' . $j, $v['area']);
            //$objActiveSheet->setCellValue('E' . $j, $v['course_name']);
            //$objActiveSheet->setCellValue('F' . $j, $v['class_name']);
            $objActiveSheet->setCellValue('E' . $j, $v['reg_time']);
            $objActiveSheet->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('B' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objActiveSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //$objActiveSheet->getStyle('E' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //$objActiveSheet->getStyle('F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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

