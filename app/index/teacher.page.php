<?php
class index_teacher extends STpl{
    //年级
    private $grades=array(
		'4000'     =>  '学前',
		'1000'     =>  '小学',
		"2000"     =>  '初中',
		'3000'     =>  "高中",
    );

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
	private $domain;
	public function __construct(){
		if($_GET){
			foreach($_GET as $get_k	=> $get_v){
				$_GET[$get_k] = strip_tags($get_v);
			}
		}
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
		$this->assign('domain', $this->domain);
	}	
	public function pageList($inPath){              
		utility_cache::pageCache(600);
		if(isset($_REQUEST['search_field'])){
			$_GET['search_field'] = utility_tool::unescape($_REQUEST['search_field']);
		}
 		$page  = isset($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$grade = isset($_REQUEST['grade'])? intval($_REQUEST['grade']):'-1';
		$cate  = isset($_REQUEST['cate'])? intval($_REQUEST['cate']):'-1';
		$sort = isset($_REQUEST['sort'])? addslashes($_REQUEST['sort']):'weight:desc';
		$search_field = isset($_REQUEST['search_field'])? addslashes($_GET['search_field']):'';
		$size = 10;
		
		$group = SConfig::getConfig(ROOT_CONFIG."/group.conf","group");
		$subject_arr = tag_api::getTagByGroupId($group->subject);

        $f_array = array(
				"teacher_id","org_id","org_name","user_status","subject_id","subject","real_name",
				"name","title","thumb_big","thumb_med","thumb_sma","college","years","city","org_teacher",
				"course_count","student_count","avg_score","score_user_count","comment","weight","grade_id","grade"
            );
		$f_name = 'name';
		if(!empty($search_field)){
			$q_array = array(
                'teacher_status' => 1,
				'subject_id'  => $cate,
				'grade_id'  => $grade,
				'platform_status'=>1,
				'search_field' => $search_field,
            );
		}else{
			$q_array = array(
				'course_count'=>'1,5000',
                'subject_id'  => $cate,
				'grade_id'  => $grade,
                'teacher_status' => 1,
				'platform_status'=>1,
				'search_field' => $search_field,
            );
		}
       
        foreach($q_array as $k=>$v){
            if($v=='' || $v==-1 ){
                unset($q_array[$k]);
            }
        }
		
        $sort_arr=explode(':',$sort);
		if($sort_arr[0] == 'student_count'){
			$ob_array = array(
				$sort_arr[0]=>$sort_arr[1],
				'avg_score'=> 'desc',
			);
		}else{
        	$ob_array = array(
                $sort_arr[0]=>$sort_arr[1],
            );
		}
        $seek_arr = array(
                "f"=>$f_array,
                "q"=>$q_array,
                "ob"=>$ob_array,
                "p"=>$page,
                "pl"=>$size,
            );
        $ret_seek = seek_api::seekteacher($seek_arr);
		$teacherList = array();
		if(!empty($ret_seek->data)){
        	$teacherList = $ret_seek->data;            
		}
		
        $oid_arr = array();
        $org_name_arr = array();
        $tid_arr = array();
		$sublist = array(); 
		$org_ret = array(); 
        if(!empty($teacherList)){
            foreach($teacherList as &$to){
				if(!empty($to->org_teacher)){
					foreach($to->org_teacher as $tt){
						if($tt->teacher_status == 1 && $tt->visiable == 1 && !empty($tt->pk_org)){
							$to->show_org = $tt->pk_org;
							$oid_arr[$tt->pk_org] = $tt->pk_org;
							break;
						}
					}
				}
            	$tid_arr[$to->teacher_id] = $to->teacher_id;
            }
       	}
        $previewVideoArr = array();  
		if(!empty($tid_arr)){
			$uidStr = implode(',',$tid_arr);       
			$preview_video = user_api::getPreviewVideoByUid($uidStr);   
			if(!empty($preview_video->result)){
				foreach ($preview_video->result as $key => $value) {
					if(!empty($value->uid)){
					 $previewVideoArr[$value->planid] = $value->uid;
					}
				}
			}
		}
                    
        
		//获取机构信息
		if( !empty($oid_arr) ){			
			$org_ret = $this->getOrgSubname($oid_arr);				
			if(!empty($org_ret->org_user)){
				$sub_ret = user_api::getSubdomainByUidArr($org_ret->org_user);
       			if( $sub_ret->result->code == 0 && !empty($sub_ret->result->data->items)){
       				$subdomains = $sub_ret->result->data->items;
            		foreach($subdomains as $so){
            			$sublist[$so->fk_user] = $so->subdomain;
            		}
				}
			}
		}

		if(!empty($teacherList)){
        	foreach($teacherList as $k=>&$to){
				$to->org_subname = '';
				$to->fk_user_owner = 0;
				if(!empty($to->show_org)){
					$org_id = $to->show_org;
					if(!empty($org_ret) && !empty($org_ret->org_info[$org_id])){
						$to->org_subname = $org_ret->org_info[$org_id];
						$to->fk_user_owner = $org_ret->org_user[$org_id];
					}
				}
				$temp_subject = array();
				if(!empty($to->subject)){
					foreach($to->subject as $so){
						$temp_subject[] = $so->subject_name;
					}
				}
				$to->subject_name = implode('、',$temp_subject);
				if($sublist && !empty($sublist[$to->fk_user_owner])){
            		$to->show_url = "/index.teacherblog.entry/".$to->teacher_id;
				}else{
					$to->show_url = '//www.'.$this->domain;
				}
				$to->years = str_replace('年','',$to->years);
                
                //判断是否存在预览视频
                if(in_array($to->teacher_id , $previewVideoArr)){
                    $to->planid = array_search($to->teacher_id, $previewVideoArr);
                }else{
                    $to->planid = '';
                }  
			}
		}
		$total = 0;
		$count = 0;
		if( !empty($ret_seek->total) ){
        	$total=ceil($ret_seek->total/$ret_seek->pagelength);
        	$count=$ret_seek->total;
		}
		

		$all_arr = array(
                "f"=>array(
                        'subject_id',
                        'grade_id',
                    ),
                "q"=>array(
						//'org_id' => "1,10,14,15,35",
						'course_count'=>'1,5000',
                        'teacher_status' => 1,
						'visiable' => '1',
                    ),
                "ob"=>array(
                        'weight'=>'desc',
                        ),
                "p"=>1,
                "pl"=>10000,
            );
        $ret_all = seek_api::seekteacher($all_arr);
        if(!empty($ret_all)){
			$all=$ret_all->data;	
			$cateArr=array();
			foreach($all as $a){
				if(!empty($a->subject_id)){
					foreach($a->subject_id as $so){
						$cateArr[$so]=$so;
					}
				}
			}
			$subjects=array();
			if(!empty($cateArr)){
				foreach($subject_arr as $k2=>$v2){
					if(in_array($k2,$cateArr)){
						$subjects[$k2]=$v2;
					}
				}
			}
		}
		
		
        $pm=array(
                'page'=>$page,
				'size'=>$size,
                'total'=>$total,
                'count'=>$count,
                'grade'=>$grade,
                'cate'=>$cate,
				'sort'=>$sort,
            );
        $path='/index.teacher.list';
		$this->assign("grades",$this->grades);
		$this->assign("categorys",$subjects);
		$this->assign("teacherList",$teacherList);
		$this->assign("path",$path);
		$this->assign("pm",$pm);
		return $this->render("index/teacher.list.html");
	}
    public function pageEdit($inpath){
		//如果没有登陆到登陆界面
		$user = user_api::loginedUser();
		if(empty($user)){
			$this->redirect("index/main/login");
		}
		$uid = $user["uid"];
        $teacher = user_api::getTeacherInfo($uid);
		$group = SConfig::getConfig(ROOT_CONFIG."/group.conf","group");
		$tagRet=teacher_api::getTagUserInUids(array('ids'=>$uid,'groupId'=>$group->subject));
		if(!empty($teacher->good_subject)){
			$good_subject = explode(",",$teacher->good_subject);
		}else{
			$good_subject = !empty($teacher->major) ? (array)$teacher->major : '';
		}
		//从t_tag表获取科目标签
        $setArr = user_api::getsubjectTag($group->subject);
        $subject_arr= array();
        if(!empty($setArr)){
            foreach($setArr as $k=>$v){
                $subject_arr[$v->pk_tag]=isset($v->name) ? $v->name : ''; 
            }
        }
		$tagArr= array();
		if(!empty($tagRet->data)){
			foreach($tagRet->data as $v){
				$tagArr[] = !empty($v->fk_tag) ? $v->fk_tag : '';
			} 
		}
		$this->assign("subject_id",$good_subject);
        $this->assign("teacher",$teacher);
        $this->assign("good_subject",$tagRet);
		$this->assign('tagArr',$tagArr);
        $this->assign('setArr',$setArr);
        return $this->render("index/teacher.edit.html");
    }   
	public function pageUpdateAjax($inpath){
        $result=new stdclass;
		//如果没有登陆到登陆界面
		$user = user_api::loginedUser();
		if(empty($user)){
            $result->error="页面过期，请刷新后重试";
            return $result;
		}
		$uid = $user["uid"];
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
        $years=!empty($_POST['years'])?(int)$_POST['years']:0;
		
        if($years<0){
            $result->error="教龄只允许输入正数字";
            $result->field="years";
            return $result;
        }
		$major=!empty($_POST['good_subject'])? $_POST['good_subject']:0;
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
		$brief_desc=!empty($_POST['brief_desc'])?trim($_POST['brief_desc']):'';
        if(mb_strlen($brief_desc,'utf-8')>20){
            $result->error="一句话简介不能超过20个字";
            $result->field="college";
            return $result;
        }
        $desc=!empty($_POST['desc'])?$_POST['desc']:'';
        if(empty(trim($_POST['desc']))){
            $result->error="个人介绍不能为空";
            $result->field="desc";
            return $result;
        }
        $data=array(
                'college'=>$college,
                'title'=>$title,
                'years'=>$years,
                'diploma'=>$diploma,
				'scopes'=>$scopes,
                'good_subject'=>$major,
                'desc'=>$desc,
				'brief_desc'=>$brief_desc

            );
        $res1 = user_api::setTeacherInfo($uid,$data);
        //更新t_user表中的last_updated字段
        $res2=user_api::updateUser($uid,array('last_updated'=>date('Y-m-d H:i:s',time())));
        if($res1&&$res2){
            $result->status="Success!";
            return $result;
        }else{
            $result->error="修改失败!";
            return $result;
        }
    }
	public function pageTimetable($inPath){
		//如果没有登陆到登陆界面
		$user = user_api::loginedUser();
		if(!empty($user['mobile'])){ $mobile = $user["mobile"];}
		if(!empty($user['token'])){ $token = $user["token"];}
		if(empty($user)){
			$this->redirect("index.main.login");
		}
		$uid = $user["uid"];
		$this->assign("uid",$uid);
		if(empty($_REQUEST["starttime"])){
			$strstartTime = strtotime("today");
			$strstartTime = time();
		}else{
			$strstartTime = strtotime($_REQUEST["starttime"]);
		}
		if(!empty($_REQUEST["endstarttime"])){
			$endstrstartTime= strtotime($_REQUEST["endstarttime"]);
			$endstarttime = $endstrstartTime;
			$dataIn = array();
			$dataIn["endstart_time"]=$endstarttime;
			$dataend = array();
		//	$dataend["endstart_time"]=$endstarttime;
			$dataend["endstart_time"]=$strstartTime;
			$endstart = "628358400";
		}else{
			$endstarttime = "4099651200";
			$dataIn = array();
			$dataIn["endstart_time"]=$endstarttime;
			$dataend = array();
		//	$dataend["endstart_time"]=$endstarttime;
			$dataend["endstart_time"]=$strstartTime;
			$endstart = "628358400";
		}
		//筛选当天plan信息
		$starttime = $strstartTime;
	//	$starttime = "1418313600";
	//	$dataIn["endstart_time"]="1438790400";
		$dataIn["status"]="1";
	//	$dataend["endstart_time"]="4099651200";
		$dataend["status"]="3";

		$ret_plan_info_ret = $this->listPlan($uid,$starttime,$dataIn,$page = 1,$length=20);
		$ret_plan_info_ret_end = $this->listPlan($uid,$endstart,$dataend,$page = 1,$length=20);

		$this->assign("startTime",$starttime);	
		$this->assign("endstartTime",$endstarttime);	
		//print_r($ret_plan_info_ret_end);
		// 数据组装
		$showData = array();
		$showDataend = array();
		$todaystr = time();
		if(!empty($ret_plan_info_ret->data)){
			$showData = $this->gdata($ret_plan_info_ret);
		}
		if(!empty($ret_plan_info_ret_end->data)){
			$showDataend = $this->gdata($ret_plan_info_ret_end);
		}
		if(!empty($showData)){
			$showData = utility_countbySphinx::countsections($showData);
		}
		if(!empty($showDataend)){
			$showDataend = utility_countbySphinx::countsections($showDataend);
		}

		$config = SConfig::getConfig(ROOT_CONFIG."/version.conf",'default');
		$download = explode(',',$config->windows->download);
		$this->assign('host',$download[0]);//获取下载链接地址
		$this->assign("token",$token);
		$this->assign("mobile",$mobile);
		$this->assign("showData",$showData);	
		$this->assign("showDataend",$showDataend);	
		$JsonshowData = SJson::encode($showData);
		$this->assign("JsonshowData",$JsonshowData);	
		return $this->render("index/teacher.timetable.html");
	}
    public function pageTimeTableStartAjax($inPath){
		$user = user_api::loginedUser();
        $starttime = $_REQUEST["start_time"];
        $endstarttime = $_REQUEST["endstart_time"];
        $attruid = $_REQUEST["uid"];
        $page = $_REQUEST["page"];
//      print_r($page);
    //  $page = 10;
        $status = 1;
        $uid = $user["uid"];
        if(empty($attruid) || empty($starttime)){
            return $this->setAjaxResult("1000777","failed","not null");
        }
        if(!is_numeric($attruid) || !is_numeric($starttime)){
            return $this->setAjaxResult("1000777","failed"," data incorrect");
        }
        if(empty($_REQUEST["start_time"]) || !is_numeric($_REQUEST["start_time"])){
            //$starttime = strtotime("today");
            $starttime = time();
        }else{
            $starttime = $_REQUEST["start_time"];
        }

        if($attruid != $uid){
            return $this->setAjaxResult("100133","failed","uid is incorrect");
        }
        //{{{ 获取机构信息
        $ret_org_info = user_organization::getOrgByTeacher($uid);
        //}}}
        //{{{   筛选当天plan信息
        $dataend = array();
        $dataend["endstart_time"]=$endstarttime;
        $dataend["status"]="1";
        $ret_plan_info_ret = $this->listPlan($uid,$starttime,$dataend,$page,$length=20);
        //}}}
    //  print_r($ret_plan_info_ret);
        //{{{  数据组装
        $showData = array();
        $showDataend = array();
    //  $todaystr = $strtimeToday;
    //  $this->assign("todaystr",$todaystr);    
        $todaystr = time();
        if(!empty($ret_plan_info_ret->data)){
            $showData = $this->gdata($ret_plan_info_ret);
        }
        //}}}
//      print_r($ret_plan_info_ret);
        //{{{  数据组装
        if(!empty($showData)){
            $showData = utility_countbySphinx::countsections($showData);
        }
//      print_r($showData);
    //  $showData = utility_countbySphinx::countsections($showData);
        return $this->setAjaxResult("200","success",$showData);
        //}}}
    }
    public function pageTimeTableEndAjax($inPath){
		$user = user_api::loginedUser();
        $starttime = $_REQUEST["start_time"];
        $endstarttime = $_REQUEST["endstart_time"];
        $attruid = $_REQUEST["uid"];
        $page = $_REQUEST["page"];
//      print_r($page);
    //  $page = 10;
        $status = 3;
        $uid = $user["uid"];
        if(empty($attruid) || empty($starttime)){
            return $this->setAjaxResult("1000777","failed","not null");
        }
        if(!is_numeric($attruid) || !is_numeric($starttime)){
            return $this->setAjaxResult("1000777","failed"," data incorrect");
        }
        if(empty($_REQUEST["start_time"]) || !is_numeric($_REQUEST["start_time"])){
            //$starttime = strtotime("today");
            $starttime = $time();
        }else{
            $starttime = $_REQUEST["start_time"];
        }

        if($attruid != $uid){
            return $this->setAjaxResult("100133","failed","uid is incorrect");
        }
        //{{{ 获取机构信息
        $ret_org_info = user_organization::getOrgByTeacher($uid);
        //}}}
        //{{{   筛选当天plan信息
        $dataend = array();
    //  $dataend["endstart_time"]=$endstarttime;
        $dataend["endstart_time"]=$starttime+86400;
        $endstart = "628358400";
        $dataend["status"]="3";
        $ret_plan_info_ret = $this->listPlan($uid,$endstart,$dataend,$page,$length=20);
        //}}}
    //  print_r($ret_plan_info_ret);
        //{{{  数据组装
        $showData = array();
        $showDataend = array();
    //  $todaystr = $strtimeToday;
    //  $this->assign("todaystr",$todaystr);    
        $todaystr = time();
        if(!empty($ret_plan_info_ret->data)){
            $showData = $this->gdata($ret_plan_info_ret);
        }
        //}}}
//      print_r($ret_plan_info_ret);
        //{{{  数据组装
        if(!empty($showData)){
            $showData = utility_countbySphinx::countsections($showData);
        }
//      print_r($showData);
    //  $showData = utility_countbySphinx::countsections($showData);
		foreach($showData as &$v){
			if($v['status'] == 1){
				$v['plan_status'] = "<a href='javascript:void(0);' class='onclick_btn col-xs-20' onclick=checks_st({$v['plan_id']},{$v['class_id']},{$v['course_id']}) >开始上课</a>";
			}
		}
        return $this->setAjaxResult("200","success",$showData);
        //}}}
    }
	public function pagestudent($inpath){
		//如果没有登陆到登陆界面
		$user = user_api::loginedUser();
		if(empty($user)){
			$this->redirect("index/main/login");
		}
		//不是老师身份跳转
		if(empty($user['types']->teacher)){
			return $this->redirect("/index.growth.entry");
		}
		//  增加筛选条件
		$course_id = isset($_GET["course_id"]) ? $_GET["course_id"]:0;
		$class_id = isset($_GET["class_id"]) ? $_GET["class_id"]:0;
		$showTag = 1; //是否全显示学生
		if($class_id ==0 && $course_id==0){
			$showTag= 0;
		}
		$count_all = 0;
		$this->assign("course_id",$course_id);	
		$this->assign("class_id",$class_id);	
		$coursedata = array();
		$classdata = array();
		//判断是否是手机号
		$likemobile = 0;
		$likename = 0;
		if(!empty($_GET["sedata"])){
			$phonenumber = $_GET["sedata"];
			$this->assign("sedata",$phonenumber);	
			if(is_numeric($phonenumber)){
			//if(preg_match("/1[3458]{1}\d{9}$/",$phonenumber)){
				$likemobile = 1;
				$showTag =1;
			}else{
				$likename = 1;
				$showTag =1;
			}
		}
		//筛选出老师的class
		$classCond = array("user_id"=>"0","user_class_id"=>$user["uid"],"course_id"=>"0");
		$classList = course_api::classlistbycond($classCond);
		
		if(!empty($classList->data)){
			foreach($classList->data as $k=>$v){
				if($v->course_id !=0){
					$course[$v->course_id] = $v;
				}
			}
			//前端的显示配对class数组
			foreach($classList->data as $ck=>$cv){
				if(!empty($cv->name)){
					$classShowdata[$cv->class_id]=$cv;
				}
			}
			if($class_id==0){
				if(!empty($classShowdata)){
					$class_id = array_keys($classShowdata);
				}
			}
		
			$this->assign("classShowdata",$classShowdata);	
			//用于上面的下拉框
			
			/**班级列表*/	//课程为key 挂着班级list
			if(!empty($course)){
				foreach($course as $k=>$v){ 
					foreach($classList->data as $ck=>$cv){
						if($k==$cv->course_id){
							$classdata[$k][]=$cv;
						}
					}
				}
			}
			/**课程列表*/
			
			if(!empty($course)){
				foreach($course as $k=>$v){
					$coursedata[$k]["course_id"] = $v->course_id;
					$coursedata[$k]["title"] = $v->course_title;
				}
				foreach($course as $ck=>$cv){
					$coursedata[$ck]['user_total'] = 0;
					foreach($classList->data as $clk=>$clv){
						if($ck == $clv->course_id){
							$coursedata[$ck]['user_total'] = $coursedata[$ck]['user_total']+$clv->user_total;
						}
					}
				}
			}
			
		}
			$this->assign("coursedata",$coursedata);	
			$this->assign("classdata",$classdata);	
		//筛选出course的id 
		if(!empty($coursedata)){
			foreach($coursedata as $k=>$v){
				$courseids[] = $k;
			}
		}
		
			if(($course_id || $class_id)){
				if($showTag){
					$regdata = array(
						'course_ids'=>$course_id,
						'class_id'=>$class_id,
						//'uids'=>$list_reg_uid_arr2,
					);
					//获取courseid是这些的老师的报名学生
					$reglist = course_api::listRegistrationBycond($regdata);
				}else{
					$reglist = 0;
				}
			}else{
				$reglist = 0;
			}
		
		$user_ids_arr = array();
		//筛选出这批人的userid
		if(!empty($reglist->data)){
			foreach($reglist->data as $k=>$v){
				$user_ids_arr[$v->uid]=$v->uid;
			}
		}
		//根据userid  like查询
		//模糊查询部分
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
				'class_id'=>$class_id,
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
		$this->assign("ret",$ret);
		
		//全查部分数据需要进行真实姓名整合
		//改为真实姓名 
		if(!($likemobile && $likename)){
			if(!empty($ret->data)){
				foreach($ret->data as $k=>$v){
					if(!empty($v->user_info)){
						$uidArr[] = $v->user_info->user_id;
					}
				}
				if(!empty($uidArr)){
					$stuProfile = user_api::getUserProfileByUidArr($uidArr);
					if(!empty($stuProfile)){
						foreach($stuProfile as $stk=>$stv){
							$stuProfileArr[$stv->user_id] = $stv;
						}
					}

				}
			}
			//获取真实姓名
			if(!empty($stuProfileArr)){
				$realNameArr = $stuProfileArr;
			}else{
				$realNameArr = 0;
			}
			$this->assign("realNameArr",$realNameArr);	
		}
		$path = '/index.teacher.student';
		
		$this->assign("path",$path);	
		return $this->render("index/teacher.student.new1.html");
	}

	public function pageHelp($inPath){
		//如果没有登陆到登陆界面
		$user = user_api::loginedUser();
		if(empty($user)){
			$this->redirect("index/main/login");
		}
		$uid = $user["uid"];
		$token = live_auth::getPublishAuth($uid);
		if(empty($token)){
			//设置token
			$ret = live_auth::setPublishAuth($uid);
			if($ret){
				$token = live_auth::getPublishAuth($uid);
				$this->assign("token",$token);
			}
		}else{
			$this->assign("token",$token);
		}

        $config = SConfig::getConfig(ROOT_CONFIG."/version.conf",'default');
		
        $header = utility_net::isHTTPS() ? "https" : "http";
        $host = $header.'://'.$_SERVER['HTTP_HOST'];
		$download = explode(',',$config->windows->download);
		$download_url = $download[0];
		$this->assign("download_url",$download_url);
		return $this->render("index/teacher.help.html");
	}
	
	public function getOrgSubname($oid_arr){
        $org_info = array();
		$org_user = array();
        $org_ret = user_organization::getOrgInfoByOidArr($oid_arr);
        if( !empty($org_ret) ){
        	foreach($org_ret as $vo){
				$subname = '';
				if(!empty($vo->subname)){
					$subname = $vo->subname;
				}else{
					$subname = $vo->name;
				}
            	$org_info[$vo->pk_org] = $subname;
				$org_user[$vo->pk_org] = $vo->fk_user_owner;
            }
        }
		$ret = new stdclass;
		$ret->org_info  = $org_info;
		$ret->org_user = $org_user;
		return $ret;
	}



	public function listPlan($uid,$time,$data=array(),$page=0,$length=0){
		$plist = array(
			"allcourse"=>true, //筛选所有状态!= -1的课程
			"user_plan_id"=>$uid,
			"order_by"=>"desc",	
			"start_time"=>$time,
		);
		if(!empty($page)){
			$plist["page"] = $page;
		}
		if(!empty($length)){
			$plist["length"] = $length;
		}
		if(!empty($data["endstart_time"])){
			$plist["endstart_time"] = $data["endstart_time"];
		}
		if(isset($data["status"])){
			$plist["status"] = $data["status"];
		}
		$plist["type"] = 1;
		$ret_plan_info_ret = course_api::listPlan($plist);	
		return $ret_plan_info_ret;
	}
	public function gdata($ret_plan_info_ret){
		$showArr = array(
			"user_course"=>"org_info",	
			"plan_id"=>"plan_id",	
			"course_id"=>"course_id",	
			"class_id"=>"class_id",	
			"section_id"=>"section_id",	
			"section_name"=>"section_name",	
			"class_name"=>"class_name",	
			"title"=>"course_name",	
			"start_time"=>"start_time",	
			"course_type"=>"course_type",	
			"fee_type"=>"fee_type",	
			"price"=>"price",	
			"price_market"=>"price_market",	
			"status"=>"status",	
			"section_count"=>"section_count",	
			"user_total_class"=>"user_total_class",
			"max_user_class"=>"max_user_class",
		);
		$showData = array();
		$todaystr = strtotime("today");
		$todaystr = time();
		if(!empty($ret_plan_info_ret->data)){
			$retPdata = $ret_plan_info_ret->data;
			foreach($retPdata as $k=>$v){
				foreach($showArr as $showk=>$showv){
					if(isset($retPdata[$k]->$showk)){
						$showData[$k][$showv] = $retPdata[$k]->$showk;
					}
					if($retPdata[$k]->course_type==3){
						$showData[$k]["course_type"]='<span class="lineclass-icon fs12">线下课</span>';
					}elseif($retPdata[$k]->course_type==2){
						$showData[$k]["course_type"]='<span class="taped-class fs12">录播课</span>';
					}else{
						$showData[$k]["course_type"]='';
					}
					if($retPdata[$k]->fee_type==1){
						$showData[$k]["fee_info"] = $retPdata[$k]->price."元";
						$showData[$k]["fee_info_color"] = "cRed";
					}else{
						$showData[$k]["fee_info"] = "免费";
						$showData[$k]["fee_info_color"] = "cGreen";
					}
                    $todaystr1 = time();
                    $todaystr = strtotime(date("Y-m-d"));
                    $strtothisday = strtotime($retPdata[$k]->start_time);
                    $seconds1 = $strtothisday-$todaystr;
                    $domain='//'.user_organization::course_domain($retPdata[$k]->user_course->domain);
					if($retPdata[$k]->status==3){
                        $showData[$k]["plan_status"]='<a href="'.$domain."/video.point.videoPart"."/".$retPdata[$k]->plan_id.'/1" target="_blank"><button class="btn yellow-btn mt20 col-xs-12 col-md-15">视频管理</button></a>';
						$showData[$k]["lesson"] = "";
						$showData[$k]["lesson_link"] = "";
						$showData[$k]["plan_play"] = "继续上课";
                        $showData[$k]["plan_play_link"] = $domain."/course.plan.start"."/".$retPdata[$k]->plan_id; 
                        $showData[$k]["plan_manage"] = "";
                        $showData[$k]["plan_manage_link"] = "";

					}else{
                        $showData[$k]["plan_status"]='<a href="'.$domain."/course.plan.start"."/".$retPdata[$k]->plan_id.'" target="_blank"><button class="btn yellow-btn mt20 col-xs-12 col-md-15">开始上课</button></a>';
                        $showData[$k]["lesson"] = "备课";
                        $showData[$k]["lesson_link"] = $domain."/teacher.manage.plan"."/".$retPdata[$k]->plan_id;
                        $showData[$k]["plan_manage"] = "上传视频";
                        $showData[$k]["plan_manage_link"] = $domain."/video.point.videoUpload"."/".$retPdata[$k]->plan_id."/1";
                        //  $showData[$k]["lesson"] = "";
                        //  $showData[$k]["lesson_link"] = "";
                        if(($seconds1<86400) && ($seconds1>0)){
                            $showData[$k]["plan_status"]='<a href="'.$domain."/course.plan.start"."/".$retPdata[$k]->plan_id.'" target="_blank"><button class="btn yellow-btn mt20 col-xs-12 col-md-15">开始上课</button></a>';
                        }else{
                            $showData[$k]["plan_status"]='未开课';
                        }
                        if(strtotime($retPdata[$k]->start_time)<=time()){
							//修改 备课 上传
                            $showData[$k]["plan_status"]='<a href="'.$domain."/course.plan.start"."/".$retPdata[$k]->plan_id.'" target="_blank"><button class="btn yellow-btn mt20 col-xs-12 col-md-15">开始上课</button></a>';
                        //  $showData[$k]["plan_status"] = "未开课";
                        //  $showData[$k]["plan_link"] = "";

							$showData[$k]["lesson"] = "备课";
							$showData[$k]["lesson_link"] = $domain."/teacher.manage.plan"."/".$retPdata[$k]->plan_id;
							//$showData[$k]["plan_manage"] = "上传视频";
							//$showData[$k]["plan_manage_link"] = $domain."/video.point.videoUpload"."/".$retPdata[$k]->plan_id."/1";

                            //$showData[$k]["lesson"] = "";
                            //$showData[$k]["lesson_link"] = "";
                            $showData[$k]["plan_play"] = "上传视频";
                            $showData[$k]["plan_play_link"] = $domain."/video.point.videoUpload"."/".$retPdata[$k]->plan_id."/1";
                        }
					}
					$showData[$k]["mid"] = " | ";
					$showData[$k]["start_date"] = date("n月d H:i",strtotime($retPdata[$k]->start_time));
				    $showData[$k]["org_info_name"] = !empty($retPdata[$k]->user_course->subname)?$retPdata[$k]->user_course->subname:mb_substr($retPdata[$k]->user_course->name,0,6,'utf-8');
					$showData[$k]["play"] =$domain."/course.plan.play"."/".$retPdata[$k]->plan_id;
					if($retPdata[$k]->type_id==3){
						$showData[$k]["play"] = $domain."/course.info.show"."/".$retPdata[$k]->course_id;
						$showData[$k]["mid"] = "";
						$showData[$k]["plan_status"]='';
						//  $showData[$k]["plan_status"] = "未开课";
						//  $showData[$k]["plan_link"] = "";
						$showData[$k]["lesson"] = "查看详情";
						$showData[$k]["lesson_link"] = $domain."/course.info.show"."/".$retPdata[$k]->course_id;
						$showData[$k]["plan_manage"] = "";
						$showData[$k]["plan_manage_link"] = "";
						$showData[$k]["plan_play"] = "";
						$showData[$k]["plan_play_link"] = "";
					}
					$todaystr1 = time();
					$todaystr = strtotime(date("Y-m-d"));
					$strtothisday = strtotime($retPdata[$k]->start_time);
					$seconds1 = $strtothisday-$todaystr;
					if(($seconds1<86400) && ($seconds1>0)){
						$showData[$k]["start_date"] = date("今日 H:i",strtotime($retPdata[$k]->start_time));
					}else{
						$showData[$k]["start_date"] = date("n月d H:i",strtotime($retPdata[$k]->start_time));
					}
					$showData[$k]["thumb"] = utility_cdn::file($retPdata[$k]->thumb_small) ;
					//获取今天的课程开课时间
					$strtothisday = strtotime($retPdata[$k]->start_time);
					$seconds = $strtothisday-$todaystr1;
					if(($seconds<86400) && ($seconds>0)){
						$hours = floor($seconds/3600);
						$yushu  = $seconds%3600;
						$minutes = floor($yushu/60); if($hours>0){
							$showData[$k]["time_countdown"] = "距离开课时间:".$hours."小时".$minutes."分";
						}else{
							$showData[$k]["time_countdown"] = "距离开课时间:".$minutes."分";
						}
					}
				}
			}
		}
		return $showData;
	}
    public function setAjaxResult($code, $msg, $data=array()){
		return json_encode(
			array(
				'code' => $code,
				'msg'  => $msg,
				'data' => $data
			),
			JSON_UNESCAPED_UNICODE
		);
    }
    
    public function pageTeacherVideoPreview($inPath){
        return $this->render("teachervideo/teacher.profile.html");
    }
}
