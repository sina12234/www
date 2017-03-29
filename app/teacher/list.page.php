<?php
class teacher_list extends STpl{
	//年级
    private $grades=array(
		'4000'     =>  '学前',
		'1000'     =>  '小学',
		"2000"     =>  '初中',
		'3000'     =>  "高中",
    );
    public $orgOwner = 2;

    public function __construct()
    {
        $org = user_organization::subdomain();
        $org && $this->orgOwner = $org->userId;
   }
	/*
    public function pageEntry($inPath)
    {
		utility_cache::pageCache(600);
		$groupId= 1; //科目id
		//$major  = isset($inPath[3]) && isset($this->_major[$inPath[3]]) ? $inPath[3] : 0;
        //$page   = isset($inPath[4]) && (int)($inPath[4]) ? (int)($inPath[4]) : 1;
        //$length = isset($inPath[5]) && (int)($inPath[5]) ? (int)($inPath[5]) : 100;

		//机构信息
        $orgInfo = user_organization::getOrgByUid($this->orgOwner);
		$oid     = empty($orgInfo) ? 0 : $orgInfo->oid;
		
		//机构下的老师信息
        $data    = [
            'orderBy'   => 'is_star desc',
            'condition' => "fk_org={$oid} and status<>-1 and visiable>0",
        ];
        $res_teacher = user_organization::getTeacherList($oid, 1, 1000, $data);
		

		$tIdStr = '';
		if(!empty($res_teacher->data))
		{
			//$tIdArr = array_reduce($res_teacher->data, create_function('$v,$w', '$v[$w->fk_user]=$w->fk_user;return $v;'));
            foreach($res_teacher->data as $rtv){
                $tidArr[$rtv->fk_user]=$rtv->fk_user; 
            }
			$tIdStr = implode(',',$tidArr);
		}
		
		//t_mapping_tag_user关联的老师信息
		$mappingParams   = array('ids'=>$tIdStr,'groupId'=>$groupId);
		$res_mappingUser = teacher_api::getTagUserInUids($mappingParams);

		$tagIdArr        = array();
		if(!empty($res_mappingUser->data))
		{
			$tagIdArr = array_reduce($res_mappingUser->data, create_function('$v,$w', '$v[$w->fk_user][$w->fk_tag]=$w->fk_tag;return $v;'));
		}
		
		//tag信息
        $res_tag = teacher_api::getBelongTagByGropId($groupId);
		if(!empty($res_tag->data))
		{
			$tagNames = array_reduce($res_tag->data, create_function('$v,$w', '$v[$w->pk_tag]=$w->name;return $v;'));
		}
		
		//拼装tag数据
		$tag = array();
        $subject=array();
		foreach($tagIdArr as $key=>$val)
		{
			foreach($val as $k=>$v)
			{
				$tag[$k][$key] = $k;
				$subject[$k]   = $tagNames[$k];
			}
		}
		//拼装老师列表数据
		$teacher = array();
		if(!empty($res_teacher->data))
		{
			foreach($res_teacher->data as $k=>$v)
			{
                $desc='';
                if(!empty($v->desc)){
                    $desc=$v->desc;
                }
                if(!empty($v->desc)&&mb_strlen($v->desc,'utf-8')>20){
                    $desc=mb_substr($v->desc,0,20,'utf-8').'...';     
                }
                //现实真实姓名
				$teacher[$v->fk_user] = [
					'fk_user'   => $v->fk_user,
					'name'      => !empty($v->real_name)?$v->real_name:$v->name,
					'thumb_big' => utility_cdn::file($v->thumb_big),
					'title'     => !empty($v->title)?$v->title:'',
					'college'   => !empty($v->college)?$v->college:'',
					'desc'   => $desc,
				];
			}
		}

		$data    = array();
        
		if(!empty($teacher))
		{
			$data[0] = $teacher;
		}
		
		if(!empty($tag))
		{
			foreach($tag as $key=>$val)
			{
				foreach($val as $k=>$v)
				{
					$data[$key][$k] = [
					'fk_user'   => $k,
					'name'      => $teacher[$k]['name'],
					'thumb_big' => $teacher[$k]['thumb_big'],
					'title'     => $teacher[$k]['title'],
					'college'   => $teacher[$k]['college'],
					'desc'   => $teacher[$k]['desc'],
					];
				}
			}
		}
		$this->assign('subject',$subject);
        $this->assign('data', json_encode($data));
        $this->render("teacher/teacher.html");
    }
	*/
	/*public function pageEntry(){
		utility_cache::pageCache(600);
		$orgInfo = user_organization::getOrgByUid($this->orgOwner);
		$oid = !empty($orgInfo->oid) ? $orgInfo->oid : '';
		$params = [
            'q' => ['org_id' => $oid],
            'f' => [
				'teacher_id',
				'org_teacher',
                'subject_id',
                'name',
                'real_name',
				'thumb_big',
                'thumb_med',
                'city',
                'title',
                'college',
                'years',
                'diploma',
                'desc',
                'brief_desc',
                'subject',
                'student_count',
            ],
			'ob'=>['student_count' => 'desc'],
			'p'=>1,
			"pl"=>1000,
        ];
		$tData = seek_api::seekTeacher($params);
		$teacher = [];
		$tIdStr  = '';
		if(!empty($tData->data)){
			foreach($tData->data as $k=>$v)
			{
				foreach($v->org_teacher as $a=>$b){
					if($b->visiable=='1'&&$oid==$b->pk_org){
						$tidArr[$v->teacher_id]=$v->teacher_id;
						$desc='';
						if(!empty($v->desc)){
							$desc=$v->desc;
						}
						if(!empty($v->desc)&&mb_strlen($v->desc,'utf-8')>20){
							$desc=mb_substr($v->desc,0,30,'utf-8').'...';     
						}
						//现实真实姓名
						$teacher[$v->teacher_id] = [
							'fk_user'   => $v->teacher_id,
							'name'      => !empty($v->real_name)?$v->real_name:$v->name,
							'thumb_big' => utility_cdn::file($v->thumb_big),
							'title'     => !empty($v->title)?$v->title:'',
							'college'   => !empty($v->college)?$v->college:'',
							'desc'   => $desc,
							'planid' => 0
						];
					}
				}
			}
			if(!empty($tidArr)){
				$tIdStr = implode(',',$tidArr);
			}
		}
		//t_mapping_tag_user关联的老师信息
		$groupId = 1;
		$mappingParams   = ['ids'=>$tIdStr,'groupId'=>$groupId];
		$res_mappingUser = teacher_api::getTagUserInUids($mappingParams);

		$tagIdArr        = [];
		if(!empty($res_mappingUser->data))
		{
			$tagIdArr = array_reduce($res_mappingUser->data, create_function('$v,$w', '$v[$w->fk_user][$w->fk_tag]=$w->fk_tag;return $v;'));
		}
		
		//tag信息
        $res_tag = teacher_api::getBelongTagByGropId($groupId);
		if(!empty($res_tag->data))
		{
			$tagNames = array_reduce($res_tag->data, create_function('$v,$w', '$v[$w->pk_tag]=$w->name;return $v;'));
		}
		
		//拼装tag数据
		$tag = [];
        $subject = [];
		foreach($tagIdArr as $key=>$val)
		{
			foreach($val as $k=>$v)
			{
				$tag[$k][$key] = $k;
				$subject[$k]   = !empty($tagNames[$k]) ? $tagNames[$k] : '';
			}
		}
		$data    = [];
        

		if(!empty($tIdStr)){
        	$preview_video = user_api::getPreviewVideoByUid($tIdStr);
        	if (!empty($preview_video->result)) {        	  
	        	foreach ($preview_video->result as $key => $value) {
	        		if(!empty($value->planid)){
	        			$previewVideoArr[$value->uid] = $value->planid;
	        		}
	        	   	
	        	}
        	}   
        }


		if(!empty($teacher))
		{			
			foreach ($teacher as $key => &$previewTeacher) {
                $previewTeacher['planid'] = !empty($previewVideoArr[$key]) ? $previewVideoArr[$key] : 0;
            }  
            $data[0] = array_values($teacher);
		}

		if(!empty($tag))
		{
			foreach($tag as $key=>$val)
			{
				foreach($val as $k=>$v)
				{            	
					$data[$key][] = [
						'fk_user'   => $k,
						'name'      => $teacher[$k]['name'],
						'thumb_big' => $teacher[$k]['thumb_big'],
						'title'     => $teacher[$k]['title'],
						'college'   => $teacher[$k]['college'],
						'desc'   => $teacher[$k]['desc'],
						'planid' => !empty($previewVideoArr[$k]) ? $previewVideoArr[$k] : 0
					];
				}
			}
		}

		$this->assign('subject',$subject);
        $this->assign('data', json_encode($data));
		$this->render("teacher/teacher.html");
	}*/
    /*代课老师*/
    public function pageSupply($inPath){
        return $this->render("/teacher/teacher.supply.html");
    }

    public function pageTeacherVideoPreviewOrg($inPath){
        return $this->render("teachervideo/teacher.synopsis.html");
    }
	/*
	 *机构教师列表
	 */
	public function pageEntry($inPath){              
		utility_cache::pageCache(600);
		if(isset($_REQUEST['search_field'])){
			$_GET['search_field'] = utility_tool::unescape($_REQUEST['search_field']);
		}
		$orgInfo  = user_organization::getOrgByUid($this->orgOwner);
		$teachers = user_organization::listOrgUser($orgInfo->oid,$all=1,$star=-1,$page=1,1000);
		$oid 	 = !empty($orgInfo->oid) ? $orgInfo->oid : '';
 		$page    = isset($_REQUEST['page'])?(int)$_REQUEST['page']:1;
		$grade   = isset($_REQUEST['grade'])? intval($_REQUEST['grade']):'-1';
		$cate    = isset($_REQUEST['cate'])? intval($_REQUEST['cate']):'-1';
		$sort    = isset($_REQUEST['sort'])? addslashes($_REQUEST['sort']):'weight:desc';
		$search_field = isset($_REQUEST['search_field'])? addslashes($_GET['search_field']):'';
		$size    = 10;
		$tidArr=array();
		$mt = array();
        if(!empty($teachers)){
			foreach($teachers as $tv){
				$mt[$tv->user_id] = $tv;
				if($tv->visiable == 1){
					$tidArr[$tv->user_id]=$tv->user_id;
				}
				
			}
			$teacherIdStr = implode(",",$tidArr);
		}
		$group = SConfig::getConfig(ROOT_CONFIG."/group.conf","group");
		$subject_arr = tag_api::getTagByGroupId($group->subject);
        $f_array = array(
				"teacher_id","org_id","org_name","user_status","subject_id","subject","real_name","gender","desc","visiable",
				"name","title","thumb_big","thumb_med","thumb_sma","college","years","city","org_teacher",
				"course_count","student_count","avg_score","score_user_count","comment","weight","grade_id","grade"
            );
		//$f_name = 'name';
		if(!empty($search_field)){
			$q_array = array(
				'org_id'         => $oid,
				'teacher_id' 	 => $teacherIdStr,
                'teacher_status' => 1,
				'subject_id'     => $cate,
				'grade_id'       => $grade,
				'platform_status'=>1,
				'search_field'   => $search_field,
            );
		}else{
			$q_array = array(
				'org_id' 	     => $oid,
				'teacher_id' 	 => $teacherIdStr,
				//'course_count'=>'1,5000',
                'subject_id'     => $cate,
				'grade_id'       => $grade,
                'teacher_status' => 1,
				'platform_status'=>1,
				'search_field'   => $search_field,
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
		$t_array = array("teacher_id");
		$t_query = array("org_id"=>$oid,'platform_status'=>1);
		$seek_data = array(
                "f"=>$t_array,
                "q"=>$t_query,
                "p"=>1,
                "pl"=>10000,
            );
		$infoTeacher = seek_api::seekteacher($seek_data);
		$tidteacherArr = array();
		if(!empty($infoTeacher)){
			foreach($infoTeacher->data as $m=>$n){
				$tidteacherArr[] = $n->teacher_id;
			}
			$tdStr = implode(",",$tidteacherArr);
		}
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
						if(!empty($tt->pk_org) && ($tt->pk_org == $oid)){
							$to->visiable = $tt->visiable;
							//$oid_arr[$tt->pk_org] = $tt->pk_org;
							break;
						}
					}
				}
            	$tid_arr[$to->teacher_id] = $to->teacher_id;
            }
       	}
		
		
		
        $previewVideoArr = array();  
		$uidStr = '';
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
         
		//t_mapping_tag_user关联的老师信息
		$groupId = 1;
		$mappingParams   = ['ids'=>$tdStr,'groupId'=>$groupId];
		$res_mappingUser = teacher_api::getTagUserInUids($mappingParams);
		$subjects        = array();
		$tagIdArr        = [];
		if(!empty($res_mappingUser->data)){
			foreach($res_mappingUser->data as $key=>$val){
				if(!empty($val->name)){
					$subjects[$val->fk_tag] = $val;
				}
			}
		}
		if(!empty($teacherList)){
        	foreach($teacherList as $k=>&$to){
				$temp_subject = array();
				if(!empty($to->subject)){
					foreach($to->subject as $so){
						$temp_subject[] = $so->subject_name;
					}
				}
				if(!empty($v->desc)&&mb_strlen($v->desc,'utf-8')>20){
					   $to->desc   = mb_substr($to->desc,0,30,'utf-8').'...';
				}
				$to->subject_name = implode('、',$temp_subject);
				
				$to->years        = str_replace('年','',$to->years);
                $to->avg_score    = round($to->avg_score,2);
                //判断是否存在预览视频
                if(in_array($to->teacher_id , $previewVideoArr)){
                    $to->planid = array_search($to->teacher_id, $previewVideoArr);
                }else{
                    $to->planid = '';
                }  
			}
		}
		$cateinfoData = array(
                "f"=>$f_array,
                "q"=>$t_query,
                "ob"=>$ob_array,
                "p"=>1,
                "pl"=>10000,
            );
        $CateResult = seek_api::seekteacher($cateinfoData);
		$cateMt = array();
		if(!empty($CateResult->data)){
            foreach($CateResult->data as &$to){
				if(!empty($to->org_teacher)){
					foreach($to->org_teacher as $tt){
						if(!empty($tt->pk_org) && ($tt->pk_org == $oid)){
							$to->visiable = $tt->visiable;
							//$oid_arr[$tt->pk_org] = $tt->pk_org;
							break;
						}
					}
					
				}
            }
       	}
		if(!empty($CateResult->data)){
			foreach($CateResult->data as $vo){
				if(!empty($vo->subject)&&$vo->visiable !=0){
					foreach($vo->subject as $tt){
						$cateMt[$tt->subject_id] = $tt;
					}
				}
			}
		}
		ksort($cateMt);
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
		
        $pm=array(
                'page'=>$page,
				'size'=>$size,
                'total'=>$total,
                'count'=>$count,
                'grade'=>$grade,
                'cate'=>$cate,
				'sort'=>$sort,
            );
        $path='/teacher.list';
		$this->assign("grades",$this->grades);
		$this->assign("categorys",$cateMt);
		$this->assign("teacherList",$teacherList);
		$this->assign("path",$path);
		$this->assign("pm",$pm);
		return $this->render("teacher/teacher.html");
	}
	public function getOrgSubname($oid_arr){
        $org_info = array();
		$org_user = array();
        $org_ret  = user_organization::getOrgInfoByOidArr($oid_arr);
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
		$ret 			= new stdclass;
		$ret->org_info  = $org_info;
		$ret->org_user  = $org_user;
		return $ret;
	}
}
