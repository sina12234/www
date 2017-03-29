<?php
require_once(ROOT_LIBS."/phpexcel/PHPExcel.class.php");
require_once(ROOT_LIBS."/phpexcel/PHPExcel/Writer/Excel2007.php");
ini_set("max_execution_time", 2400); // s 40 分钟 
//修改此次的最大运行内存 
ini_set("memory_limit", 1048576000); // Byte 1000 兆，即 1G
class phpexcel_platformstu{
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
		$isorg=false;
		$org_info = user_organization::getOrgByOwner($this->orgOwner);
		$special = user_api::getTeacherSpecial($org_info->oid,$this->user['uid']);
		$path = '/user.teacher.student';
		$uri = '';
		$num ="6";
		$page = isset($_GET['page']) ? $_GET['page']:1;
		$courseId = isset($_GET["course_id"]) ? $_GET["course_id"]:0;
		$class_id = isset($_GET["class_id"]) ? $_GET["class_id"]:0;
		$count_all = 0;
		$lege = utility_judgeid::courseid($courseId,$this->user['uid'],$this->orgOwner);
		$isAdmin=user_api::isAdmin($this->orgOwner,$this->user['uid']);
		if(($lege ===false) || ($isAdmin===false)){
			return $this->redirect("/site.main.entry");
		}
		$coursesRet = course_api::getcourselistbyoid(1,200,$this->orgOwner);
		$courses=array();
		if(!empty($coursesRet->data)){
			$coursesTmp = $coursesRet->data;
			//当是老师时，过滤掉不是自己的课程和班级数据
			if(!$isorg){
				foreach($coursesTmp as $i=>$course){
						if(!empty($courseId) && $courseId==$course->course_id){
							$courses[]=$course;
						}
				}
			}
		}
		$ret = array();
		$regdata = array("course_id"=>$courseId);
		$regdata["class_id"]= $class_id;
		$mobile = isset($_GET["mobile"]) ? trim($_GET["mobile"]) :'';
		if(!empty($_GET['sub'])){
			$tmp_r = course_api::listregistration($regdata);
			foreach($tmp_r->data as $k=>$v){
				if($mobile==$v->user_info->mobile || $mobile==$v->user_info->real_name){
					$ret[][]=$v;
				}
			}
		}else{
			if(!empty($courseId)){
			$tmp_r = course_api::listregistration($regdata);
			if(!empty($tmp_r->data)) $ret[]= $tmp_r->data;
			}
			
		}
		//$this->assign("ret",$ret);
        $userIdArr = array();
		if(!empty($ret[0])){
			$courseids = array();
			foreach($ret[0] as $rk=>$rv){
				$courseids[$rv->cid] = $rv->cid;
				$classids[$rv->class_id] = $rv->class_id;
                $userIdArr[$rv->user_info->user_id] = $rv->user_info->user_id;
			}
			$courseidsdata = course_api::getcoursebycids($courseids);
			$classidsdata = course_api::getClassByClassIdArr($classids);
			foreach($courseidsdata as $k=>$v){ 
				$coursesdata[$v->course_id] = $v;
			}
			foreach($classidsdata->data as $k=>$v){ 
				$classesdata[$v->pk_class] = $v;
			}

			//$this->assign("coursesdata",$coursesdata);	
			//$this->assign("classesdata",$classesdata);	
		}
		if($courseId){
			$course= course_api::getcourseone($courseId);
			$classes_tmp = course_api::getclasslist($courseId);
			$classes=array();
			if(!empty($classes_tmp)){
				foreach($classes_tmp as $tmp){
					$classes[$tmp->class_id]=$tmp;
				}
			}
				
		}
		$showData = array();
		foreach($courses as $k=>$v){
			if(isset($courses[$k]->course_id)){
				$showData[$k]["course_id"] = $courses[$k]->course_id;
			}
			if(isset($courses[$k]->class)){
				foreach($courses[$k]->class as $ck=>$cv){
					$showData[$k]["class"][$ck]["class_id"] = $courses[$k]->class[$ck]->class_id;
					$showData[$k]["class"][$ck]["name"] = $courses[$k]->class[$ck]->name;
				}
			}
		}

        //收货地址
        $userAddress = array();
        if(!empty($course->document)){
            $userAddressReg = user_api::getUserAddressByUserIdArr($userIdArr);
            if(!empty($userAddressReg)){
                foreach($userAddressReg as $val){
                    $userAddress[$val->fk_user] = $val;
                }
            }
        }


        $data=array();
        if(!empty($ret[0])){
            foreach($ret[0] as $k=>$v){
				$thisdata = array(
					'name' => !empty($v->user_info->real_name) ? $v->user_info->real_name:'',
					'mobile' => !empty($v->user_info->mobile) ? $v->user_info->mobile:'',
					'course_name' =>4 ,
					'class_name' => 5,
					'reg_time' => date("Y-m-d",strtotime($v->create_time)),
				);
                if(!empty($userAddress) && $userAddress[$v->uid]){
                    $thisdata['receiver'] = $userAddress[$v->uid]->receiver;
                    $thisdata['phone']    = $userAddress[$v->uid]->phone;
                    $thisdata['address']  = region_geo::$region[$userAddress[$v->uid]->province].' '.region_geo::$region[$userAddress[$v->uid]->city].' '.$userAddress[$v->uid]->address;
                    $thisdata['desc']     = $userAddress[$v->uid]->desc;
                }

				if($v->user_info->gender ==1){
					$thisdata['gender'] = '男';
				}elseif($v->user_info->gender ==2){
					$thisdata['gender'] = '女';
				}else{
					$thisdata['gender'] = '未设置';
				}

				if($v->user_info->province==$v->user_info->city){
					$thisdata['area'] = $v->user_info->province;
				}else{
					$thisdata['area'] = $v->user_info->province.",".$v->user_info->city;
				}

				if(!empty($classes[$v->class_id]->name)){
					$thisdata['class_name'] = $classes[$v->class_id]->name;
				}else{
					if(!empty($classesdata[$v->class_id]->name)){
						$thisdata['class_name'] = $classesdata[$v->class_id]->name;
					}
				}

				if(!empty($course->title)){
					$thisdata['course_name'] = $course->title;
				}else{
					if(!empty($coursesdata[$v->cid]->course_name)){
						$thisdata['course_name'] = $coursesdata[$v->cid]->course_name;
					}else{
						$thisdata['course_name'] = '课程加载失败';
					}
				}

				$data[] = $thisdata;
			}
        }else{
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
        $objActiveSheet->setCellValue('D1', '地区');
        $objActiveSheet->setCellValue('E1', '课程');
        $objActiveSheet->setCellValue('F1', '班级');
        $objActiveSheet->setCellValue('G1', '报名时间');
        if(!empty($userAddress)){
            $objActiveSheet->setCellValue('H1', '收件人姓名');
            $objActiveSheet->setCellValue('I1', '收件人联系电话');
            $objActiveSheet->setCellValue('J1', '收件地址');
            $objActiveSheet->setCellValue('K1', '收件信息备注');
        }
        //设置第二列为文本格式 防止科学计数
        $objActiveSheet->getStyle('C1')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        //填充表格内容
        $j=2;
        foreach($data as $k=>$v){
            $objActiveSheet->setCellValue('A' . $j, $v['name']);
            $objActiveSheet->setCellValue('B' . $j, $v['gender']);
            $objActiveSheet->setCellValue('C' . $j, ' '.$v['mobile']);
            $objActiveSheet->setCellValue('D' . $j, $v['area']);
            $objActiveSheet->setCellValue('E' . $j, $v['course_name']);
            $objActiveSheet->setCellValue('F' . $j, $v['class_name']);
            $objActiveSheet->setCellValue('G' . $j, $v['reg_time']);
            if(!empty($userAddress)){
                $objActiveSheet->setCellValue('H' . $j, $v['receiver']);
                $objActiveSheet->setCellValue('I' . $j, $v['phone']);
                $objActiveSheet->setCellValue('J' . $j, $v['address']);
                $objActiveSheet->setCellValue('K' . $j, $v['desc']);
            }
            $objActiveSheet->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('B' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objActiveSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('E' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('G' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            if(!empty($userAddress)){
                $objActiveSheet->getStyle('H' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objActiveSheet->getStyle('I' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objActiveSheet->getStyle('J' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objActiveSheet->getStyle('K' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }
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
    public function pagemyStudent($inPath){
		
		//判断用户是否是本机构下的老师
		$userId = $this->user['uid'];
		$orgInfo = user_organization::getOrgByOwner($this->orgOwner);
       
		$special = user_api::getTeacherSpecial($orgInfo->oid,$userId);
        
		if(empty($special)){
			return $this->redirect("/site.main.entry");
		}
        
		$courseId = isset($_GET['course_id'])?$_GET['course_id']:0;
		$classId  = isset($_GET['class_id'])?$_GET['class_id']:0;
		$mobile   = isset($_GET['mobile'])?$_GET['mobile']:'';
		
		//获取教师所带班级课程
		$params = array("user_id"=>$this->orgOwner,"user_class_id"=>$userId);
		$teacherClassList = course_api::classlistbycond($params);
		$courseList = array();
		$classList  = array();
		$classIdArr = array();
		$courseIdArr = array();
		if(!empty($teacherClassList->data)){
			foreach($teacherClassList->data as $class){
				if(empty($courseList[$class->course_id])){
					$courseList[$class->course_id]['user_total'] = 0;
				}
				$courseList[$class->course_id]['class'][$class->class_id] = $class;
				$courseList[$class->course_id]['title'] = $class->course_title;
				$courseList[$class->course_id]['user_total'] = $courseList[$class->course_id]['user_total']+$class->user_total;
				$classList[$class->class_id] = $class;
				$classIdArr[] = $class->class_id;
				$courseIdArr[] = $class->course_id;
			}
		}	
		//获取课程及班级下的报名学生
		$studentList  = array();
		$userIdArr    = array();
		$regCondition = array();
		if($courseId || $classId || $mobile){	
			if(!empty($courseId) && !in_array($courseId,$courseIdArr)){
				return $this->redirect("/site.main.entry");
			}
			if(!empty($classId) && !in_array($classId,$classIdArr)){
				return $this->redirect("/site.main.entry");
			}
			if(!empty($courseId)){
				$regCondition['course_ids'] = $courseId;
			}elseif(!empty($courseIdArr)){
				$regCondition['course_ids'] = $courseIdArr;
			}
			if(!empty($classId)){
				$regCondition['class_id'] = $classId;
			}elseif(!empty($courseIdArr)){
				$regCondition['class_id'] = $classIdArr;
			}
			if(!empty($regCondition)){
				$studentRet = course_api::listRegistrationBycond($regCondition);
				if(!empty($studentRet->data)){
					$studentList = $studentRet->data;
					foreach($studentList as $student){
						$userIdArr[$student->uid] = $student->uid;
					}
					if(!empty($mobile) && !empty($userIdArr)){	
						$searchData = array(
							'mobile' => $mobile,
							'uidArr' => $userIdArr,
						);
						$searchRet = user_api::searchUser($searchData);
						if(!empty($searchRet)){
							$tempStudent = array();
							foreach($searchRet as $sh ){
								foreach($studentList as $sd ){
									if($sh->uid == $sd->uid){
										$tempStudent[] = $sd;
									}
								}
							}
							$studentList = $tempStudent;
						}
					}
                    foreach($studentList as $k=>$v){
                        if($v->user_info->gender=='1'){
                            $v->user_info->gender='男';
                        }elseif($v->user_info->gender=='2'){
                            $v->user_info->gender='女';
                        }elseif($v->user_info->gender=='0'){
                            $v->user_info->gender='未设置';
                        }
                        $v->courseClass = !empty($classList[$v->class_id]) ? $classList[$v->class_id]->name : '';
                        $v->courseTitle = !empty($courseList[$v->cid]['title']) ? $courseList[$v->cid]['title'] : '';
                        if(isset($v->user_info->province)&&($v->user_info->province==$v->user_info->city)){
                            $v->address = $v->user_info->city;
                        }else{
                            $v->address = $v->user_info->province.$v->user_info->city;
                        }
                    }
				}
			}
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
        $objActiveSheet->setCellValue('D1', '地区');
        $objActiveSheet->setCellValue('E1', '课程');
        $objActiveSheet->setCellValue('F1', '班级');
        $objActiveSheet->setCellValue('G1', '报名时间');
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        //设置第二列为文本格式 防止科学计数
        $objActiveSheet->getStyle('C1')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        //填充表格内容
        $j=2;
        foreach($studentList as $k=>$v){
            $objActiveSheet->setCellValue('A' . $j, !empty($v->user_info->real_name) ? $v->user_info->real_name : $v->user_info->name);
            $objActiveSheet->setCellValue('B' . $j, $v->user_info->gender);
            $objActiveSheet->setCellValue('C' . $j, ' '.$v->user_info->mobile);
            $objActiveSheet->setCellValue('D' . $j, $v->address);
            $objActiveSheet->setCellValue('E' . $j, $v->courseTitle);
            $objActiveSheet->setCellValue('F' . $j, $v->courseClass);
            $objActiveSheet->setCellValue('G' . $j, $v->create_time);
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
	*机构-学生管理excel导出
	*@return array  $data
	*/
	public function pageimportOrgOfStudent(){
		$user = user_api::loginedUser();
		if(empty($user)){
			$this->redirect("index/main/login");
		}
        $isAdmin=user_api::isAdmin($this->orgOwner,$user['uid']);
		$orgInfo=user_organization::getOrgByOwner($user['uid']);
        if(($isAdmin ===false) &&($this->orgOwner != $orgInfo->user_owner_id)){
            return $this->redirect("/site.main.entry");
        }
		$params = array(
            'condition' => "fk_user_owner={$this->orgOwner} AND status=1 ",
            'page'      => !empty($_REQUEST['page']) && (int)($_REQUEST['page']) ? (int)($_REQUEST['page']) : 1,
            'length'    => 2000,
            'item'      => array('fk_user', 'count(*) as courseNum'),
            'groupBy'   => array('fk_user'),
            'orgOwner'  => $this->orgOwner
        );
        $keyword = '';
        if (isset($_REQUEST['keyword']) && $_REQUEST['keyword']) {
            $keyword = mb_strtolower(trim($_REQUEST['keyword']));
            $params['condition'] = "real_name LIKE '%{$keyword}%'";
            $params['orgOwner'] = $this->orgOwner;
            $list = course_api::searchDataByRealName($params);
        } else {
            $list = course_api::exportOrgOfstudentData($params);
        }
        $data = array();
        if (!empty($list->data->list)) {
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
        }
        $totalPage = 0;
        if (!empty($list->data->total[0]->totalNum)) {
             $total = $list->data->total[0]->totalNum;
             $totalPage = ceil($total/$params['length']);
        }
        return $this->downloadExcelCommonPart($data);
	}
	/*
	*机构-教师管理excel导出
	*@return array  $mt
	*/
	public function pageexportTeacher(){
		$org=user_organization::subdomain();
        if(!empty($org)){
            $this->orgOwner=$org->userId;
        }else{
            header('Location: https://www.'.$this->domain);
        }   
        $this->orgInfo=user_organization::getOrgByOwner($this->orgOwner);
		//机构下所有老师列表
        $teachers = user_organization::listOrgUser($this->orgInfo->oid,$all=1,$star=-1,$page=1,$length=1000);
        if(empty($teachers)){
		    return $this->render("org/teacher.404.html");
        }
		$user = user_api::loginedUser();
		if(empty($user)){
			$this->redirect("index/main/login");
		}
		//机构拥有者和管理员权限
        $isAdmin=user_api::isAdmin($this->orgOwner,$user['uid']);
		$orgInfo=user_organization::getOrgByOwner($user['uid']);
        if(($isAdmin ===false) &&($this->orgOwner != $orgInfo->user_owner_id)){
            return $this->redirect("/site.main.entry");
        }
        //获取所有老师id
        $tidArr=array();
		$mt = array();
        foreach($teachers as $tv){
			$mt[$tv->user_id] = $tv;
            $tidArr[$tv->user_id]=$tv->user_id;
        }
        if(!empty($tidArr)){
            //获取老师tags
            $tidStr=implode(',',$tidArr);
			$group = SConfig::getConfig(ROOT_CONFIG."/group.conf","group");
            $tagRet=teacher_api::getTagUserInUids(array('ids'=>$tidStr,'groupId'=>$group->subject)); 
            if(!empty($tagRet->data)){
                $tagArr=$tagRet->data;
                $tags=array();
                foreach($tagArr as $tv){
                    $tags[$tv->fk_user][]=$tv->name;
                }
            }
            //根据老师id从中间层获取排课
            $planArr=array(
                "f"=>array(
                        'teacher_id',
                        'plan_id',
                        'class_name',
                        'section_name',
                        'teacher_name',
                        'start_time',
                        'max_user',
                        'user_total',
                        'status',
                    ),
                "q"=>array(
                        'teacher_id'=>implode(',',$tidArr),
                        'status'=>1,
                    ),
                "ob"=>array(
                        'start_time'=>'desc',
                   ),
                "p"=>1,
                "pl"=>1000,
            );
            $seekPlan=seek_api::seekPlan($planArr);
            $retPlan=$seekPlan->data;
            $plan=array();
            foreach($retPlan as $rp){
                $plan[$rp->teacher_id][]=$rp;
            } 
        }
		$id_arr = implode(",",$tidArr);
		$nameInfo = user_organization::getTeacherRealName($id_arr);
		$arr = array();
		if(!empty($nameInfo)){
			foreach($nameInfo as $k=>$v){
				$arr[$v->fk_user]=$v->fk_user;
                //真实姓名超过10个汉字显示省略号
                if(mb_strlen($v->real_name,'utf-8')>10){
				    $arr[$v->fk_user]=mb_substr($v->real_name,0,10,'utf-8').'...';
                }else{
				    $arr[$v->fk_user]=$v->real_name;
                }
			}
		}
		if(!empty($arr)){
			foreach($arr as $k=>$v){
				if(!empty($mt[$k])){
					$mt[$k]->real_name = $v;
				}else{
					$mt[$k]->real_name = '';
				}	
			}	
		}
		$userRoles= array(1=>"普通老师",2=>"助教",4=>"管理员");
        $group = SConfig::getConfig(ROOT_CONFIG."/group.conf","group");
        $set_arr = user_api::getsubjectTag($group->subject);
        if(!empty($mt)){
            foreach($mt as $k=>$v){
				if($v->gender==1){
					$v->gender="男";
				}elseif($v->gender==2){
					$v->gender="女";
				}elseif($v->gender==0){
					$v->gender="未设置";
				}
				if($v->visiable==1){
					$v->visiable="是";
				}else{
					$v->visiable="否";
				}
				if(!empty($plan[$v->user_id])){
					$v->isCourse=count($plan[$v->user_id]);
				}else{
					$v->isCourse=0;
				}
				if(!empty($userRoles[$v->user_role])){
					$v->user_role =  $userRoles[$v->user_role];
				}else{
					$v->user_role = 0;
				}
				
                if(!empty($v->good_subject)){
                    $result = explode(",",$v->good_subject);
                    $s_suject= array();
                    foreach($result as $v){
                        $s_suject[]= isset($set_arr[$v]) ? $set_arr[$v]->name : '';
                    }
                    $str =implode(",",$s_suject);
                    $mt[$k]->good_subject=$str;   
                }
            }
        }
		$objPHPExcel = new PHPExcel(); 
        //保存excel—2007格式
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        //基本设置
        $fileName="教师列表";
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
        $objActiveSheet->getStyle('A1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('B1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('C1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('D1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('E1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('F1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('G1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('H1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('I1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->setCellValue('A1', '姓名');
        $objActiveSheet->setCellValue('B1', '性别');
        $objActiveSheet->setCellValue('C1', '科目');
        $objActiveSheet->setCellValue('D1', '角色');
        $objActiveSheet->setCellValue('E1', '联系方式');
        $objActiveSheet->setCellValue('F1', '是否展示');
        $objActiveSheet->setCellValue('G1', '最后登录时间');
        $objActiveSheet->setCellValue('H1', '待上课');
        //设置第二列为文本格式 防止科学计数
        $objActiveSheet->getStyle('C1')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        //填充表格内容
        $j=2;
		
        foreach($mt as $k=>$v){
            $objActiveSheet->setCellValue('A' . $j, $v->real_name);
            $objActiveSheet->setCellValue('B' . $j, $v->gender);
            $objActiveSheet->setCellValue('C' . $j, $v->good_subject);
            $objActiveSheet->setCellValue('D' . $j, $v->user_role);
            $objActiveSheet->setCellValue('E' . $j, $v->mobile);
            $objActiveSheet->setCellValue('F' . $j, $v->visiable);
            $objActiveSheet->setCellValue('G' . $j, $v->last_login);
            $objActiveSheet->setCellValue('H' . $j, $v->isCourse);
            $objActiveSheet->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('B' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objActiveSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('E' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('G' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('H' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
	*机构-优惠码excel导出
	*@return array  $discount
	*/
	public function pageexportPromoCode($inPath){
		
		$org=user_organization::subdomain();
        if(!empty($org)){
            $this->orgOwner=$org->userId;
        }else{
            header('Location: https://www.'.$this->domain);
        }   
        $this->orgInfo=user_organization::getOrgByOwner($this->orgOwner);
		//机构下所有老师列表
        $teachers = user_organization::listOrgUser($this->orgInfo->oid,$all=1,$star=-1,$page=1,$length=2000);
        if(empty($teachers)){
		    return $this->render("org/teacher.404.html");
        }
		$user = user_api::loginedUser();
		if(empty($user)){
			$this->redirect("index/main/login");
		}
		//机构拥有者和管理员权限
        $isAdmin=user_api::isAdmin($this->orgOwner,$user['uid']);
		$orgInfo=user_organization::getOrgByOwner($user['uid']);
        if(($isAdmin ===false) &&($this->orgOwner != $orgInfo->user_owner_id)){
            return $this->redirect("/site.main.entry");
        }
        //优惠码的数据
		if(empty($inPath[3])){
			$this->redirect("/");
		}
		$discount_id = $inPath[3];
		if(empty($_REQUEST["limit"])){
			if(!empty($_REQUEST["size"])){
				$_REQUEST["limit"] = $_REQUEST["size"];
			}else{
				$_REQUEST["limit"] = 2000;
			}
		}
		if(empty($_REQUEST["page"])){
			$_REQUEST["page"] = 1;
		}
		$discounts = course_api::listDiscountCodeV2($_REQUEST, $this->orgOwner, $discount_id);
		if(0 != $discounts->result->code){
			$this->redirect("/user.discount.listv2");
		}
		foreach($discounts->data as $k=>$v){
			if($v->used_num >0){
				$v->usedStatus = "使用详情";
			}else{
				$v->usedStatus = "未使用";
			}
			$v->starttime = date("Y-m-d",strtotime($v->starttime));
			$v->endtime = date("Y-m-d",strtotime($v->endtime));
		}
		$objPHPExcel = new PHPExcel(); 
        //保存excel—2007格式
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        //基本设置
        $fileName="优惠码管理";
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objActiveSheet->getStyle('A1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('B1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('C1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('D1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('E1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->setCellValue('A1', '优惠码');
        $objActiveSheet->setCellValue('B1', '有效时间');
        $objActiveSheet->setCellValue('C1', '使用次数');
        $objActiveSheet->setCellValue('D1', '使用情况');
        //设置第二列为文本格式 防止科学计数
        $objActiveSheet->getStyle('C1')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        //填充表格内容
        $j=2;
		
        foreach($discounts->data as $k=>$v){
            $objActiveSheet->setCellValue('A' . $j, $v->discount_code);
            $objActiveSheet->setCellValue('B' . $j, $v->starttime."至".$v->endtime);
            $objActiveSheet->setCellValue('C' . $j, $v->used_num);
            $objActiveSheet->setCellValue('D' . $j, $v->usedStatus);
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
	/*
	*机构-指定优惠码个数excel导出
	*@return array  $discount
	*/
	public function pageexportPromoCodeUsedOfnumber($inPath){
		$org=user_organization::subdomain();
        if(!empty($org)){
            $this->orgOwner=$org->userId;
        }else{
            header('Location: https://www.'.$this->domain);
        }   
        $this->orgInfo=user_organization::getOrgByOwner($this->orgOwner);
        $teachers = user_organization::listOrgUser($this->orgInfo->oid,$all=1,$star=-1,$page=1,$length=1000);
        if(empty($teachers)){
		    return $this->render("org/teacher.404.html");
        }
		$user = user_api::loginedUser();
		if(empty($user)){
			$this->redirect("index/main/login");
		}
		//机构拥有者和管理员权限
        $isAdmin=user_api::isAdmin($this->orgOwner,$user['uid']);
		$orgInfo=user_organization::getOrgByOwner($user['uid']);
        if(($isAdmin ===false) &&($this->orgOwner != $orgInfo->user_owner_id)){
            return $this->redirect("/site.main.entry");
        }
        //优惠码的使用
		if(empty($inPath[3])){
			$this->redirect("/");
		}
		$discountcode = $inPath[3];
		if(empty($_REQUEST["limit"])){
			if(!empty($_REQUEST["size"])){
				$_REQUEST["limit"] = $_REQUEST["size"];
			}else{
				$_REQUEST["limit"] = 2000;
			}
		}
		if(empty($_REQUEST["page"])){
			$_REQUEST["page"] = 1;
		}
		$discounts = course_api::listDiscountCodeUsedV2($_REQUEST, $this->orgOwner, $discountcode);
		if(0 != $discounts->result->code){
			$this->redirect("/user.discount.listv2");
		}
		$objPHPExcel = new PHPExcel(); 
        //保存excel—2007格式
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        //基本设置
        $fileName="优惠码使用详情管理";
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objActiveSheet->getStyle('A1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('B1')->getFont()->setBold(true);
        $objActiveSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActiveSheet->setCellValue('A1', '使用人');
        $objActiveSheet->setCellValue('B1', '使用时间');
        //设置第二列为文本格式 防止科学计数
        $objActiveSheet->getStyle('C1')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        //填充表格内容
        $j=2;
		
        foreach($discounts->used as $k=>$v){
            $objActiveSheet->setCellValue('A' . $j, $v->name);
            $objActiveSheet->setCellValue('B' . $j, $v->createtime);
            $objActiveSheet->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('B' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
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
	public function downloadExcelCommonPart($data){
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
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
        $objActiveSheet->setCellValue('C1', '年龄');
        $objActiveSheet->setCellValue('D1', '学校');
        $objActiveSheet->setCellValue('E1', '联系方式');
        $objActiveSheet->setCellValue('F1', '正在学习的课程');
        //设置第二列为文本格式 防止科学计数
        $objActiveSheet->getStyle('C1')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        //填充表格内容
        $j=2;
        foreach($data as $k=>$v){
            $objActiveSheet->setCellValue('A' . $j, $v['name']);
            $objActiveSheet->setCellValue('B' . $j, $v['gender']);
            $objActiveSheet->setCellValue('C' . $j, ' '.$v['age']);
            $objActiveSheet->setCellValue('D' . $j, $v['school']);
            $objActiveSheet->setCellValue('E' . $j, $v['mobile']);
            $objActiveSheet->setCellValue('F' . $j, $v['courseNum']);
            $objActiveSheet->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('B' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objActiveSheet->getStyle('C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('E' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActiveSheet->getStyle('F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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


