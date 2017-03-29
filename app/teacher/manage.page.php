<?php
class teacher_manage extends STpl{
    private $majors=array(
                    "0"  =>  "不设置",
                    "1"  =>  "数学",
                    "2"  =>  "英语",
                    "3"  =>  "语文",
                    "4"  =>  "物理",
                    "5"  =>  "化学",
                    "6"  =>  "生物",
                    "7"  =>  "历史",
                    "8"  =>  "地理",
                    "9"  =>  "政治",
                );
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
        //判断是否机构下的老师
        $special = user_api::getTeacherSpecial($this->orgInfo->oid,$this->user['uid']);
        if(empty($special)){
            header('Location: //'.$org->subdomain.'.'.$this->domain);
        }
	}

	public function pagePlan($inPath){
		$orgOwnerid = $this->orgOwner;
	    $oid = $this->orgInfo->oid;
		//	$oid = 0; // 0 就是说把所有的筛选出来
		$planId = $inPath[3];
		$user_id = $this->user['uid'];
		//权限判断
		$retjudgeplan = utility_judgeid::planteacherid($planId,$user_id,$orgOwnerid); 
		if(!$retjudgeplan){
			//没有权限
			//$this->redirect("/teacher.course.teacherOfCourseList");
		}
		$f_array = array(
			'plan_id',
			'course_id',
			'section_id',
			'class_id',
			'course_name',
			'section_name',
			'class_name',
		);
		$q_array['plan_id'] = $planId;
		$ob_array["plan_id"] = "desc";
		$p = 1;
		$pl = 100;
		$seek_arr = array(
			"f"=>$f_array,
			"q"=>$q_array,
			"ob"=>$ob_array,
			"p"=>$p,
			"pl"=>$pl,
		);
		$ret_seek= seek_api::seekplan($seek_arr);
		if(!empty($ret_seek->data)){
			$planDatas = $ret_seek->data;
		}else{
			$planDatas = 0;
		}
		$classId = 0;
		if(!empty($planDatas[0]->class_id)){
			$classId = $planDatas[0]->class_id;
		}
		if(empty($classId)){
			$this->redirect("/teacher.course.teacherOfCourseList");
		}
		$this->assign("class_id",$classId);
		$this->assign("planDatas",$planDatas);

		$arr = array(
			"plan_id"=>$planId,
		);
		$this->assign("plan_id",$planId);
		$retQuesdata = course_api::coursePlanExamList($arr);

		$Ques = 0;
		if(!empty($retQuesdata->data)){
			$Ques = $retQuesdata->data;
			$this->assign("Ques",$Ques);
		}

		$timeStamps = time();
		$this->assign("timeStamps",$timeStamps);

		$retlistAtt = course_api::listPlanAttach(array('planId'=>$planId));
		$listAtt = 0;
		if(!empty($retlistAtt->data)){
			$countAtt = count($retlistAtt->data);
			$listAtt = $retlistAtt->data;
		}else{
			$countAtt = 0;
		}
		$this->assign("countAtt",$countAtt);
		$this->assign("listAtt",$listAtt);

		if($_POST){

		/**上传图片题干*/
		//{{{生成截图
		$timeStampsPost = $_POST["timeStampsPost"];
		$path = ROOT_WWW."/upload/tmp";
		$filename = $path."/course.$timeStampsPost.org.jpg";
		if(is_file($filename) && !empty($_REQUEST['w']) && !empty($_REQUEST['x22'])){
			//彩图
			list($width, $height, $type, $attr) = getimagesize($filename);
			if(!empty($width) && !empty($height)){
				$filename_dst = $path."/course.$timeStampsPost.dst.jpg";
				$targ_w = $_REQUEST['w'];
				$targ_h = $_REQUEST['h'];

				//$src = 'demo_files/flowers.jpg';
				switch($type){
				case 1: $img_r = imagecreatefromgif($filename);break;
				case 2: $img_r = imagecreatefromjpeg($filename);break;
				case 3: 
				default:
				$img_r = imagecreatefrompng($filename);
				}
				$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
				imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
					$targ_w,$targ_h,$_REQUEST['w'],$_REQUEST['h']);
				//imagesavealpha($dst_r,true);

				$r = imagejpeg($dst_r, $filename_dst);
				if($r){
					//大图就是原图
					$file = utility_file::instance();
					$r3 = $file->upload($filename_dst,user_api::getLoginUid(),"image","");

					if( !empty($r3)){
						$_POST['desc_img']=$r3->fid;
					}
					unlink($filename_dst);
				}
			}
		}

		//}}}




			//插入 question {{{
			//questionId
			$questionId = exam_api::genQuestionId($orgOwnerid);
			if(empty($questionId)){
				//继续出错
				return $this->redirect("/teacher.manage.plan.$planId");
			}
			$quArr = array(
				//	"uid"=>"uid",
				"type"=>"type",  //单选多选
				"subject"=>"subject_id",  //科目
				"grade"=>"grade_id",	//年级
				"question"=>"desc",   //科目
				"desc_img"=>"desc_img",
				//	"result"=>"result", //正确答案
				"mode"=>"mode", // 难易度
			);	
			foreach($quArr as $quk=>$quv){
				if(isset($_POST[$quk])){
					$quArr2[$quv] = $_POST[$quk];
				}
			}
			//正确答案对应数组
			$anskey = array( 
				"ri1"=>"an1",
				"ri2"=>"an2",
				"ri3"=>"an3",
				"ri4"=>"an4",
				"ri5"=>"an5",
			);
			foreach($anskey as $anskeyk=>$anskeyv){ 
				if(isset($_POST[$anskeyk])){
					$rightArr[$anskeyv] = $_POST[$anskeyv];
				}
			}
			$rightAntmp = implode(",",$rightArr);
			$rightAn = rtrim($rightAntmp,",");
			//此时Question的数据完成拼接
			$quArr2["result"] = $rightAn;

			$retAddq = exam_api::updateQuestion($questionId,$quArr2);
			if($retAddq){
				//插入成功
			}
			//}}}
			//{{{   插入answer表
			$ansArr = array(
				"a" => "an1",
				"b" => "an2",
				"c" => "an3",
				"d" => "an4",
				"e" => "an5",
			);
			$ansArr2= array();
			$ansin = array();
			//拼凑answer的数据格式
			foreach($ansArr as $ansk=>$ansv){
				if(isset($_POST[$ansv])){
					$ansArr2[$ansk]["question_id"] = $questionId;
					$ansArr2[$ansk]["desc"] = $_POST[$ansv];
					//	$ansArr2[$ansk]["desc_img"] = $_POST[$ansv];
					$ansArr2[$ansk]["correct"] = isset($rightArr[$ansv])?"1":"0";
				}
			}
			$retids = $ansArr2;
			foreach($ansArr2 as $ansArr2k=>$ansArr2v){
				$retAnswer = exam_api::addAnswer($ansArr2v);
				$retids[$ansArr2k]["answer_id"] = $retAnswer->aid;
			}
			//}}}   插入answer表结束
			$retidsin = array();
			foreach($retids as $k=>$v){
				$retidsin[] = $v;
			}
			$retids = $retidsin;
	//		shuffle($retids);

			//{{{ 增加course_exam中的一条纪录
			$qAnsArr = array();
			$qAnsArrkeys = array(
				"0"=>"a",
				"1"=>"b",
				"2"=>"c",
				"3"=>"d",
				"4"=>"e",
			);
			foreach($retids as $retidsk=>$retidsv){
				if($retidsv["correct"]==1){
					$qAnsArr[] = $qAnsArrkeys[$retidsk];
				}
			}
			$qRighttmp = implode(",",$qAnsArr);
			$qRight = rtrim($qRighttmp,",");
			$arrkeys = array(
				"plan_id" => $planId,
				"question_id" => $questionId,
				"type" => $quArr2["type"],
				"a" => $retids[0]["desc"],
				"b" => $retids[1]["desc"],
				"c" => $retids[2]["desc"],
				"d" => $retids[3]["desc"],
				"answer_a_id" => $retids[0]["answer_id"],
				"answer_b_id" => $retids[1]["answer_id"],
				"answer_c_id" => $retids[2]["answer_id"],
				"answer_d_id" => $retids[3]["answer_id"],
				"answer" => $qRight,
				"order_no" =>0,
				"status" => 0,
			);
			if(isset($quArr2["desc_img"])){
				$arrkeys["q_desc_img"] = $quArr2["desc_img"];
			}
			if(isset($quArr2["desc"])){
				$arrkeys["q_desc"] = $quArr2["desc"];
			}
			if(isset($retids["4"])){
				$arrkeys["answer_e_id"] = $retids[4]["answer_id"];
				$arrkeys["e"] = $retids[4]["desc"];
			}
			$retExam = course_api::addCoursePlanExam($arrkeys);
			//}}} 增加course_exam中的一条纪录 结束

			if($retExam){
				$this->redirect("/teacher.manage.plan.{$planId}");
			}
		}
		return $this->render("teacher/lesson.teacher.html");
	}
	public function pageDelExamAjax($inPath){
		$result=new stdclass;
		if(!empty($_REQUEST["planexamid"]) && !empty($_REQUEST["planid"])){
			$planExamArr = $_REQUEST["planexamid"];
			$planId = $_REQUEST["planid"];
			$ret_plan = course_api::getPlan($planId);
			//判断当前plan属否属于本机构 {{{
			if(empty($ret_plan->user_id) || $ret_plan->user_id!=$this->orgOwner){
				$result->error="您没有权限";
				return $result;
			}
			//}}}
			$retExam = course_api::courseDelPlanExam($planExamArr);
			if($retExam){
				$result->status="Success!";
				return $result;
			}else{
				$result->error="删除失败";
				return $result;
			}
		}
	}
	public function pageDelPlanAttAjax($inPath){
		$result=new stdclass;
		if(!empty($_REQUEST["planattid"]) && !empty($_REQUEST["planid"])){
			$planAttArr = $_REQUEST["planattid"];
			$planId = $_REQUEST["planid"];
			$classId = $_REQUEST["classid"];
			//判断当前plan属否属于本机构 {{{
			$orgOwnerid = $this->orgOwner;//$this->user['uid'];//机构的user_id
			//获取本班级信息
			$userId = $this->user['uid'];
			//$retjudgeplan = utility_judgeid::planid($planId,$userId,$orgOwnerid); 
			$retjudgeplan = utility_judgeid::classbelonguid($classId,$userId); 
			if(empty($retjudgeplan)){
				$result->error="您没有权限";
				return $result;
			}
			//}}}
			$retAtt = course_api::delPlanAttach($planAttArr);
			if($retAtt){
				$result->status="Success!";
				return $result;
			}else{
				$result->error="删除失败";
				return $result;
			}
		}else{
			$result->error="删除失败";
			return $result;
		}
	}
	
	public function pageStudent($inPath){
		
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
		$params = array("user_class_id"=>$userId);
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
				$courseList[$class->course_id]['class_data'][$class->class_id] = $class;
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
				}
			}
		}
		$this->assign('studentList', $studentList);
		$this->assign('mobile',$mobile);
		$this->assign('class_id',$classId);
		$this->assign('course_id',$courseId);
		$this->assign('courseList',$courseList);
		$this->assign('classList',$classList);			
		$this->render("teacher/teacher.student.html");		
	}
	

	public function pageEdit($inpath){
        $teacher = user_api::getTeacherInfo($this->user['uid']);
		$group = SConfig::getConfig(ROOT_CONFIG."/group.conf","group");
		$tagRet=teacher_api::getTagUserInUids(array('ids'=>$this->user['uid'],'groupId'=>$group->subject));
		if(!empty($teacher->good_subject)){
			$goodSubject = explode(",",$teacher->good_subject);
		}else{
			$goodSubject = !empty($teacher->major) ? (array)$teacher->major : '';
		}
        //从t_tag表获取科目标签
        $setArr = user_api::getsubjectTag($group->subject);
        $subjectArr= array();
        if(!empty($setArr)){
            foreach($setArr as $k=>$v){
                $subjectArr[$v->pk_tag]=isset($v->name) ? $v->name : ''; 
            }
        }
		$tagArr= array();
		if(!empty($tagRet->data)){
			foreach($tagRet->data as $v){
				$tagArr[] = !empty($v->fk_tag) ? $v->fk_tag : '';
			} 
		}
		$this->assign("subject_id",$goodSubject);
        $this->assign("teacher",$teacher);
        $this->assign("good_subject",$tagRet);
        $this->assign('tagArr',$tagArr);
        $this->assign('setArr',$setArr);
		return $this->render("teacher/teacher.edit.html");
    }
	public function pageUpdateAjax($inpath){
        $result=new stdclass;
        $college=!empty($_POST['college'])?trim($_POST['college']):'';
        if(empty($college)){
            $result->error="请填写毕业院校";
            $result->field="college";
            return $result;
        }
		
        if(mb_strlen($college,'utf-8')>15){
            $result->error="毕业院校不能超过15个字";
            $result->field="college";
            return $result;
        }
        $diploma=!empty($_POST['diploma'])?trim($_POST['diploma']):'';
        $brief_desc=!empty($_POST['brief_desc'])?trim($_POST['brief_desc']):'';
        $scopes_arr=!empty($_POST['scopes'])? $_POST['scopes']:'';

		if(!empty($_POST['scopes'])){
		$scopes = implode(",",$scopes_arr);
		}
        $years=!empty($_POST['years'])?(int)$_POST['years']:0;
        if($years<0){
            $result->error="教龄只允许输入正数字";
            $result->field="years";
            return $result;
        }
		if(empty($scopes)){
            $result->error="请勾选教育领域";
            $result->field="scopes";
            return $result;
        }
        $major=!empty($_POST['good_subject'])? $_POST['good_subject']:0;
		/*if(!empty($_POST['good_subject'])){
			$major= implode(",",$major_arr);
		}*/
        if(empty($major)){
            $result->error="请选择擅长学科";
            $result->field="major";
            return $result;
        }
        $scope=!empty($_POST['scopes'])?$_POST['scopes']:'';
        if(empty($scope)){
            $result->error="请选择培训范围";
            $result->field="scopes";
            return $result;
        }
        $scopeArr=array(
				'preschool'=>0x01,
                'primary'=>0x02,
                'junior'=>0x04,
                'senior'=>0x08,
            );
        $scopes=0;
        if(is_array($scope)){
            foreach($scope as $v){
                if(isset($scopeArr[$v])){
                    $scopes+=$scopeArr[$v];
                }
            }
        }
        $title=!empty($_POST['title'])?trim($_POST['title']):'';
        if(empty(trim($title))){
            $result->error="教师头衔不能为空";
            $result->field="title";
            return $result;
        }
        $desc=!empty($_POST['desc'])?$_POST['desc']:'';
        if(empty(trim($_POST['desc']))){
            $result->error="个人介绍不能为空";
            $result->field="desc";
            return $result;
        }
		//$desc =str_replace("\n","<br/>",$desc);
        $data=array(
                'college'=>$college,
                'title'=>$title,
                'years'=>$years,
                'scopes'=>$scopes,
                'diploma'=>$diploma,
                'good_subject'=>$major,
                'desc'=>$desc,
				'brief_desc'=>$brief_desc
            );
		
        $res1 = user_api::setTeacherInfo($this->user['uid'],$data);
        //更新t_user表中的last_updated字段
        $res2=user_api::updateUser($this->user['uid'],array('last_updated'=>date('Y-m-d H:i:s',time())));
        if($res1&&$res2){
            $result->status="Success!";
            return $result;
        }else{
            $result->error="修改失败!";
            return $result;
        }
    }
	public function pageUpload($inPath){
		$plan_id = $inPath[3];
		$class_id = $inPath[4];
		$orgOwnerid = $this->orgOwner;
		//获取本班级信息
		$user_id = $this->user['uid'];
		$retjudgeplan= utility_judgeid::planid($plan_id,$user_id,$orgOwnerid); 
		if(!$retjudgeplan){
			$this->redirect("/teacher.course.teacherOfCourseList");
		}
		$this->uploadAttach($plan_id,$class_id);
	}
	public function pageBatchUpload($inPath){
		$plan_id = $inPath[3];
		$class_id = $inPath[4];
		$orgOwnerid = $this->orgOwner;
		//获取本班级信息
		$user_id = $this->user['uid'];
		$retjudgeclass = utility_judgeid::classbelonguid($class_id,$user_id); 
		if(!$retjudgeclass){
			//$this->redirect("/teacher.course.teacherOfCourseList");
		}
		$this->uploadAttach($plan_id,$class_id);
	}
	
	public function uploadAttach($plan_id,$class_id){
		$error = SLanguage::tr('请选择文件','site.teacher');
		$errorcode = 100;
		$retlistAtt = course_api::listPlanAttach(array('classId'=>$class_id));
		if(!empty($retlistAtt->data)){
			$countAtt = count($retlistAtt->data);
		}else{
			$countAtt = 0;
		}
		if($countAtt>10){
			$error = SLanguage::tr('课件已满请删除后再上传','site.teacher');
			$errorcode = 300;
			$this->assign("errorcode",$errorcode);
			$this->assign("error",$error);
			$this->assign("plan_id",$plan_id);
			return $this->render("teacher/teacher.upload.data.html");
		}
		if(!empty($_POST["title"])){
			$upfilesNum = count($_POST["title"]);
			if($countAtt+$upfilesNum>10){
				$error = SLanguage::tr('课件最多上传10个','site.teacher');
				$errorcode = 300;
				$this->assign("errorcode",$errorcode);
				$this->assign("error",$error);
				$this->assign("plan_id",$plan_id);
				return $this->render("teacher/teacher.upload.data.html");
			}
		}
		if($_POST){
			if(empty($_POST["title"][0])){
				$error = SLanguage::tr('标题不能为空','site.teacher');
				$errorcode = 300;
				$this->assign("errorcode",$errorcode);
				$this->assign("error",$error);
				$this->assign("plan_id",$plan_id);
				return $this->render("teacher/teacher.upload.data.html");
			}
			if(empty($_POST["type"][0])){
				$error = SLanguage::tr('文件类型不正确','site.teacher');
				$errorcode = 300;
				$this->assign("errorcode",$errorcode);
				$this->assign("error",$error);
				$this->assign("plan_id",$plan_id);
				return $this->render("teacher/teacher.upload.data.html");
			}
			if(empty($_POST["thumb"][0])){
				$error = SLanguage::tr('缩略图不能为空','site.teacher');
				$errorcode = 300;
				$this->assign("errorcode",$errorcode);
				$this->assign("error",$error);
				$this->assign("plan_id",$plan_id);
				return $this->render("teacher/teacher.upload.data.html");
			}
			if(empty($_POST["attach"][0])){
				$error = SLanguage::tr('上传资料不能为空','site.teacher');
				$errorcode = 300;
				$this->assign("errorcode",$errorcode);
				$this->assign("error",$error);
				$this->assign("plan_id",$plan_id);
				return $this->render("teacher/teacher.upload.data.html");
			}
			$data = array();
			$timestamps = time();
			if(!empty($_POST["title"])){
				foreach($_POST["title"] as $k=>$v){
					$title=$_POST["title"][$k];
					$type=$_POST["type"][$k];
					$thumb=$_POST["thumb"][$k];
					$attach=$_POST["attach"][$k];

					$attArr = array(
						"title"=>$title,
						"order_no"=>"123",
						"type"=>$type,
						"thumb"=>$thumb,
						"attach"=>$attach,
						"fk_plan"=>$plan_id,
						"fk_user"=>$this->user['uid']
					);	
					$retaddAtt = course_api::addPlanAttach($class_id,$attArr);
				}
			}
			if($retaddAtt){
				$error= SLanguage::tr('上传成功请刷新页面','site.teacher');
				$errorcode = 200;
			}else{
				$error =SLanguage::tr('上传失败','site.teacher');
			}
		}
		$timeStamps = time();
		$this->assign("class_id",$class_id);
		$this->assign("plan_id",$plan_id);
		$this->assign("errorcode",$errorcode);
		$this->assign("error",$error);
		$this->assign("timeStamps",$timeStamps);
		return $this->render("teacher/teacher.upload.data.html");
	}
	
}
