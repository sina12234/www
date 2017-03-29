<?php
ini_set("max_execution_time", 2400); // s 40 分钟
//修改此次的最大运行内存
ini_set("memory_limit", 1048576000*4); // Byte 1000 兆，即 4G
class org_channel extends STpl
{
	private $domain;
	private $orgOwner;
	private $orgInfo;
	private $orgId;

	function __construct()
	{
		$this->user = user_api::loginedUser();
		if (empty($this->user)) {
			$this->redirect("/site.main.login");
		}
		$domain_conf = SConfig::getConfig(ROOT_CONFIG . "/const.conf", "domain");
		$this->domain = $domain_conf->domain;
		$org = user_organization::subdomain();
		if (!empty($org)) {
			$this->orgOwner = $org->userId; //机构所有者id 以后会根据域名而列取
		} else {
			header('Location: https://www.' . $this->domain);
		}
		$this->orgInfo = user_organization::getOrgByOwner($this->orgOwner);
		$this->assign('is_pro', isset($this->orgInfo->is_pro) ? $this->orgInfo->is_pro : 0);
		$this->orgId = $this->orgInfo->oid;
		//判断管理员
		$isAdmin = user_api::isAdmin($this->orgOwner, $this->user['uid']);
		if ($isAdmin === false) {
			header('Location: //' . $org->subdomain . '.' . $this->domain);
		}
	}

	public function setResult($data = '', $code = 0, $msg = '')
	{
		$ret = new stdclass;
		$ret->result = new stdclass;
		$ret->result->code = $code;
		$ret->result->data = $data;
		$ret->result->msg = $msg;
		return $ret;
	}

	public function pageMenu($inPath)
	{
		$subnav = "";
		if (!empty($inPath[3])) {
			$subnav = $inPath[3];
		}
		$this->assign("subnav", $subnav);
		return $this->render("/org/menu.html");
	}

	

	/**
	 * 频道模块显示设置
	 */
	public function pageTemplate($inPath){	
		//临时模块中获取数据
		$blockInfo = org_api::getblockCheck($this->orgOwner);
		
		$condition = array("fk_org_resell" => $this->orgInfo->oid);
		$salesInfo = course_resell_api::getSalesCourse($page = 1, $length = 0, $condition);
		$resell = array();
		if (!empty($salesInfo)) {
			foreach ($salesInfo as $k => $v) {
				$resell[$v->fk_course] = $v;
			}
		}
		if (!empty($blockInfo)) {
			$valueNameStr = array();
			$cateIdArr = array();
			$valueIdArr = array();
			$num = $this->orgInfo->is_pro == 1 ? 8 : 4;

			foreach ($blockInfo as $tk => $tv) {
				if ($tv->recommend == 1) {
					$cateTemp = array('first_cate', 'second_cate', 'third_cate');
					$queryArr = array();
					foreach ($tv->query_arr as $qk => $qv) {
						if ($tv->query_arr->fee_type == "2") {
							$tv->query_arr->fee_type = "0,1";
						}
						if ($qk == 'grade_id' && $qv == 0) {
							continue;
						}
						if ($qk == 'subject_id' && $qv == 0) {
							continue;
						}
						if ($qk == 'attr_value_id') {
							$queryArr[$qk] = str_replace('|', ',', $qv);
							$attrValueArr[$tv->template_id] = explode('|', $qv);
						}
						if (in_array($qk, $cateTemp)) {
							$cateIdArr[] = $qv;
						}
					}

					if (!isset($tv->query_arr->course_type)) {
						$tv->query_arr->course_type = "1,2,3";
						$queryArr['course_type'] = $tv->query_arr->course_type;
					} else {
						$queryArr['course_type'] = $tv->query_arr->course_type;
					}
					if (isset($tv->query_arr->first_cate)) {
						$queryArr['first_cate'] = $tv->query_arr->first_cate;
					}
					if (isset($tv->query_arr->second_cate)) {
						$queryArr['second_cate'] = $tv->query_arr->second_cate;
					}
					if (isset($tv->query_arr->third_cate)) {
						$queryArr['third_cate'] = $tv->query_arr->third_cate;
					}

					$queryArr['fee_type'] = $tv->query_arr->fee_type;
					if (!empty($attrValueArr[$tv->template_id])) {
						$valueRet = course_api::getAttrValueByVidArr($attrValueArr[$tv->template_id]);
						if (!empty($valueRet)) {
							foreach ($valueRet as $vo) {
								$valueNameArt = SLanguage::tr($vo->name, "course.list");
							}
							$valueNameStr[$tv->template_id] = $valueNameArt;

						}
					}
					$queryArr['user_id'] = $this->orgOwner;
					$queryArr['admin_status'] = 1;
					//查询排序
					if (!empty($tv->order_by)) {
						$obArr = explode(':', $tv->order_by);
						$orderArr = array($obArr[0] => $obArr[1]);
					} else {
						$orderArr = array('create_time' => 'desc');
					}
					$seekArr = array(
						"f" => array(
							'course_id',
							'title',
							'thumb_big',
						),
						"q" => $queryArr,
						"ob" => $orderArr,
						"p" => 1,
						"pl" => $tv->row_count * $num,
					);

					$seekRet = seek_api::seekcourse($seekArr);
					$blockInfo[$tk]->courses = $seekRet->data;
					$cid = '';
					foreach ($seekRet->data as $a => $b) {
						$cid .= $b->course_id . ",";
					}
					$cid = rtrim($cid, ",");
					$blockInfo[$tk]->course_ids = $cid;
				}
				if ($tv->recommend == 2) {
					if (!empty($tv->course_ids)) {
						$seekArr = array(
							"f" => array(
								'course_id',
								'title',
								'thumb_big',
								'admin_status',
								'subdomain',
							),
							"q" => array('course_id' => $tv->course_ids),
							"p" => 1,
							"pl" => 30,
						);
						$seekRet = seek_api::seekcourse($seekArr);
						if (!empty($seekRet->data)) {
							//按照存的顺序取出来
							$data1 = array();
							$data2 = array();
							foreach ($seekRet->data as $sdk => $sdv) {
								$sdv->resell_status = !empty($resell[$sdv->course_id]->status) ? $resell[$sdv->course_id]->status : '';
								$sdv->restatus = !empty($resell[$sdv->course_id]->restatus) ? $resell[$sdv->course_id]->restatus : '';
								$data1[$sdv->course_id] = $sdv;
								if (strpos($sdv->subdomain, ".") === false) {
									$sdv->surl = "//" . $sdv->subdomain . "." . $this->domain . "/course.info.show/" . $sdv->course_id;
								} else {
									$sdv->surl = "//" . $sdv->subdomain . "/course.info.show/" . $sdv->course_id;
								}
							}
							
							$blockInfo[$tk]->courses = $data1;
						}
					} else {
						$blockInfo[$tk]->courses = '';
					}


				}
			}

			if (!empty($cateIdArr)) {
				$cateIdStr = implode(',', $cateIdArr);
				$cateRet = course_api::getCateByCidStr($cateIdStr);
				if (!empty($cateRet)) {
					foreach ($cateRet as $co) {
						$cateList[$co->pk_cate] = $co->name_display;
					}
					$this->assign('cateList', $cateList);
				}
			}
			$this->assign('valueNameStr', $valueNameStr);
		}
		$this->assign('blockInfo', $blockInfo);
		$this->assign('is_pro', isset($this->orgInfo->is_pro) ? $this->orgInfo->is_pro : 0);
		$this->render('org/template.html');
	}

	public function pageSystemSet($inPath)
	{
		$orgProfile = user_organization::orgAboutProfileInfo($this->orgOwner);
		if (!empty($orgProfile->scopes)) {
			$firstCateIdStr = $orgProfile->scopes;
		} else {
			$firstCateIdStr = 1;
		}
		$firstCateList = array();
		$firstCateList = course_api::getCateByCidStr($firstCateIdStr);
		if (count($firstCateList) == 1) {
			$secondCateList = course_api::getNodeCate($firstCateList[0]->pk_cate);
			$this->assign('secondCateList', $secondCateList);
		}
		if (!empty($inPath[3])) {
			$tid = $inPath[3];
			$act = 'edit';
			$template = user_organization::getOrgtemplateInfo($tid);
			$recommend = $template->recommend;
			if (!empty($template->query_arr->attr_value_id)) {
				$template->query_arr->attr_value_id = explode('|', $template->query_arr->attr_value_id);
				$this->assign('attrValueIds', json_encode($template->query_arr->attr_value_id));
			}
			if (!empty($secondCateList) && !empty($template->query_arr->second_cate)) {
				$thirdCateList = course_api::getNodeCate($template->query_arr->second_cate);
				$this->assign('thirdCateList', $thirdCateList);
			}
			if (!empty($thirdCateList) && !empty($template->query_arr->third_cate)) {
				$attrRet = course_api::getAttrAndValueByCateId($template->query_arr->third_cate);
				foreach ($attrRet as $ro) {
					if ($ro->name_display == '科目') {
						$attrList = $ro;
						foreach ($attrList->attr_value as &$ao) {
							if (!empty($template->query_arr->attr_value_id)) {
								if (in_array($ao->attr_value_id, $template->query_arr->attr_value_id)) {
									$ao->check = 'checked';
								} else {
									$ao->check = '';
								}
							} else {
								$ao->check = '';
							}
						}
						break;
					}
				}
				$this->assign('attrList', $attrList);
			}
			$this->assign('template', $template);
		} else {
			$recommend = 1;
			$act = 'add';
		}
		$this->assign('firstCateList', $firstCateList);
		$this->assign('firstCount', count($firstCateList));
		$this->assign('act', $act);
		$this->assign('recommend', $recommend);
		$this->render('org/iframe.systemset.html');
	}

    /**
	 * 添加频道模块设置
	 */
	public function pageaddOrgblock($inPath){
		$title  	= !empty($_POST['title']) ? $_POST['title'] : '';
		$channelId  = !empty($_POST['channel_id']) ? $_POST['channel_id'] : 0;
		$type       = !empty($_POST['set_url_name']) ? $_POST['set_url_name'] : '2';
		$result 	= new stdClass;
		$picType    = isset($_POST['pic_type']) ? $_POST['pic_type'] : '0';
		if (empty($title)&&$type !=3) {
			$result->error = "标题不能为空";
			return $result;
		}
		
		$set_url    = !empty($_POST['set_url']) ? $_POST['set_url'] : '';
		$htype      = !empty($_POST['recommend-img']) ? $_POST['recommend-img'] : 0;
		if($type == 3){
			$htype = 3;
		}elseif(!empty($type)&&$picType==0){
			$htype = 0;
		}
		if (isset($type) && $type == '1') {
			if (empty($set_url)) {
				$result->error = "URL地址链接不能为空";
				return $result;
			}
			preg_match("/^[a-zA-z]+:\/\/[\w.]+(yunke.com|gn100.com)+[^\s]*$/", $set_url, $matches);
			if (empty($matches[0])) {
				$result->error = "请使用本机构或云课链接地址~";
				return $result;
			}
		}
		if (isset($type) && $type == '2') {
			$set_url = '';
		}
		if (($type !=3)&&strlen(iconv('utf-8', 'gb2312', $title)) > 16) {
			$result->error = "标题不能超过8个汉字或16个英文字符";
			return $result;
		}
		$rowCount       = !empty($_POST['rowCount']) ? (int)$_POST['rowCount'] : 1;
		$num            = $this->orgInfo->is_pro == 1 ? 8 : 4;
		$recommend      = !empty($_POST['recommend']) ? (int)$_POST['recommend'] : 2;
		
		//自动推荐
		$queryStr = '';
		$queryArr = array();
		$orderBy = 'create_time:desc';
		if ($recommend == 1) {
			$params = $_POST;
			$orderTemp = array(
				'crtdesc' => 'create_time:desc',
				'crtasc' => 'create_time:asc',
				'regdesc' => 'register:desc',
				'regasc' => 'register:asc'
			);
			$cateTemp = array('first_cate', 'second_cate', 'third_cate', 'course_type');
			foreach ($params as $pk => $pv) {
				if ($pk == 'attr_value_id' && !empty($pv)) {
					//$attrValueIds = implode('|',$pv);
					$queryStr .= $pk . ':' . $pv.',';
					$queryArr[$pk] = $pv;
				} elseif ($pk == 'fee_type') {
					$queryStr .= $pk . ':' . $pv . ',';
					$queryArr[$pk] = $pv;
				} elseif ($pk == 'order_by') {
					$orderBy = $orderTemp[$pv];
				} elseif (in_array($pk, $cateTemp) && !empty($pv)) {
					$queryStr .= $pk . ':' . $pv . ',';
					$queryArr[$pk] = $pv;
				}
			}
			$queryStr = rtrim($queryStr, ',');
			//判断此搜索条件下是否有课程
			$queryArr['user_id'] = $this->orgOwner;
			$queryArr['admin_status'] = 1;
			//查询排序
			if (isset($params['fee_type']) && $params['fee_type'] == 2) {
				$queryArr['fee_type'] = "0,1";
			}
			$obArr = explode(':', $orderBy);
			$orderArr = array($obArr[0] => $obArr[1]);
			$seekArr = array(
				"f" => array(
					'course_id',
				),
				"q" => $queryArr,
				"ob" => $orderArr,
				"p" => 1,
				"pl" => $rowCount * $num,
			);

			$seekRet = seek_api::seekcourse($seekArr);
			if (empty($seekRet->data)) {
				$result->error = "您还没有创建此类型的课程";
				return $result;
			}
		}
		//手动推荐
		$courseIds = '';
		if ($recommend == 2) {
			$rowCount = 0;
			$queryStr = '';
			$orderBy = '';
			$courseIds = '';

		}
		//$maxSort = org_api::getOrgTemplateMaxSort($this->orgOwner);
		$params = array(
			'title' => $title,
			'fk_user_owner' => $this->orgOwner,
			'row_count' => $rowCount,
			'recommend' => $recommend,
			'query_str' => $htype==3 ? "fee_type:2" : $queryStr,
			'order_by' => $orderBy,
			'sort' => 1,
			'course_ids' => $courseIds,
			'create_time' => date('Y-m-d H:i:s'),
			'last_updated' => date('Y-m-d H:i:s'),
			'set_url' => $set_url,
			"type" => $htype,
			"fk_channel"=>$channelId,
		);
		$r = org_api::addOrgblock($params);
		if (!empty($r)) {
			$result->status = "Success!";
			return $result;
		} else {
			$result->error = "添加失败!";
			return $result;
		}
	}

	public function pageupdateOrgblock($inPath){
        $data               	= array();
		$data['pk_block']   	= !empty($_POST['block_id']) ? $_POST['block_id'] : 0;
		$data['fk_channel']     = !empty($_POST['channel_id']) ? $_POST['channel_id'] : 0;
		$data['fk_user_owner']  = $this->orgOwner;
		//判断能否修改
		$courseIds  = '';
		$picType    = isset($_POST['pic_type']) ? $_POST['pic_type'] : '0';
		$blockCheck = org_api::getBlockOneInfoCheck($data);
		$idArr = !empty($blockCheck->course_arr) ? $blockCheck->course_arr : '';
		$type = !empty($_POST['set_url_name']) ? $_POST['set_url_name'] : '2';
		
		if (!empty($idArr)) {
			$courseIds = implode(",", $idArr);
		}
		$result = new stdClass;
		if (empty($_POST['block_id'])) {
			$result->error = "block_id参数缺失";
			return $result;
		}
		if (empty($blockCheck) || $blockCheck->owner_id != $this->orgOwner) {
			$result->error = "频道课程模版不存在或无权限修改";
			return $result;
		}
		$title = !empty($_POST['title']) ? $_POST['title'] : '';
		if (empty($title)&&$type !=3) {
			$result->error = "标题不能为空";
			return $result;
		}
		if ($type !=3&&strlen(iconv('utf-8', 'gb2312', $title)) > 16) {
			$result->error = "标题不能超过8个汉字或16个英文字符";
			return $result;
		}
		
		$set_url = !empty($_POST['set_url']) ? $_POST['set_url'] : '';
		$htype = !empty($_POST['recommend-img']) ? $_POST['recommend-img'] : 0;
		$lthumb = '';
		$lturl = '';
		$rthumb = '';
		$rturl = '';
		if($type == 3){
			$htype = 3;
		}/*elseif(!empty($type)&&$picType==0){
			$htype = 0;
		}*/
		
		if ($htype == 1) {
			if (!empty($blockCheck->thumb_left)) {
				$lthumb = !empty($blockCheck->thumb_left) ? $blockCheck->thumb_left : '';
				$lturl = !empty($blockCheck->thumb_left_url) ? $blockCheck->thumb_left_url : '';
			}
			if (!empty($blockCheck->thumb_right)) {
				$lthumb = !empty($blockCheck->thumb_right) ? $blockCheck->thumb_right : '';
				$lturl = !empty($blockCheck->thumb_right_url) ? $blockCheck->thumb_right_url : '';
			}
		}
		if ($htype == 2) {
			if (!empty($blockCheck->thumb_left)) {
				$rthumb = !empty($blockCheck->thumb_left) ? $blockCheck->thumb_left : '';
				$rturl = !empty($blockCheck->thumb_left_url) ? $blockCheck->thumb_left_url : '';
			}
			if (!empty($blockCheck->thumb_right)) {
				$rthumb = !empty($blockCheck->thumb_right) ? $blockCheck->thumb_right : '';
				$rturl = !empty($blockCheck->thumb_right_url) ? $blockCheck->thumb_right_url : '';
			}
		}

		if (isset($type) && $type == '1') {
			if (empty($set_url)) {
				$result->error = "URL地址链接不能为空";
				return $result;
			}
			preg_match("/^[a-zA-z]+:\/\/[\w.]+(yunke.com|gn100.com)+[^\s]*$/", $set_url, $matches);
			if (empty($matches[0])) {
				$result->error = "请使用本机构或云课链接地址~";
				return $result;
			}
		}
		if (isset($type) && $type == '2') {
			$set_url = '';
		}
		$rowCount = !empty($_POST['rowCount']) ? (int)$_POST['rowCount'] : 1;
		$num = $this->orgInfo->is_pro == 1 ? 8 : 4;
		$recommend = !empty($_POST['recommend']) ? (int)$_POST['recommend'] : 2;
		
		//自动推荐
		$queryStr = '';
		$orderBy = 'create_time:desc';
		$queryArr = array();
		if ($recommend == 1) {
			$params = $_POST;
			$orderTemp = array(
				'crtdesc' => 'create_time:desc',
				'crtasc' => 'create_time:asc',
				'regdesc' => 'register:desc',
				'regasc' => 'register:asc'
			);
			$cateTemp = array('first_cate', 'second_cate', 'third_cate', 'course_type');
			foreach ($params as $pk => $pv) {
				if ($pk == 'attr_value_id' && !empty($pv)) {
					$queryStr .= $pk . ':' . $pv . ',';
					$queryArr[$pk] = $pv;
				} elseif ($pk == 'fee_type') {
					$queryStr .= $pk . ':' . $pv . ',';
					$queryArr[$pk] = $pv;
				} elseif ($pk == 'order_by') {
					$orderBy = $orderTemp[$pv];
				} elseif (in_array($pk, $cateTemp) && !empty($pv)) {
					$queryStr .= $pk . ':' . $pv . ',';
					$queryArr[$pk] = $pv;
				}
			}
			$queryStr = rtrim($queryStr, ',');
			//判断此搜索条件下是否有课程
			$queryArr['user_id'] = $this->orgOwner;
			$queryArr['admin_status'] = 1;
			if (isset($params['fee_type']) && $params['fee_type'] == 2) {
				$queryArr['fee_type'] = "0,1";
			}
        $obArr = explode(':', $orderBy);
        $orderArr = array($obArr[0] => $obArr[1]);
        $seekArr = array(
            "f" => array(
                'course_id',
            ),
            "q" => $queryArr,
            "ob" => $orderArr,
            "p" => 1,
            "pl" => $rowCount * $num,
        );
        $seekRet = seek_api::seekcourse($seekArr);
        if (empty($seekRet->data)) {
            $result->error = "您还没有创建此类型的课程";
            return $result;
        }
    }
    //手动推荐
    $params = array(
        'title' 		=> $title,
        'row_count' 	=> $rowCount,
        'recommend' 	=> $recommend,
        'query_str' 	=> $htype==3 ? "fee_type:2" : $queryStr,
        'order_by' 		=> $orderBy,
        'course_ids' 	=> $courseIds,
        'last_updated' 	=> date('Y-m-d H:i:s'),
        'set_url' 		=> $set_url,
        'type' 			=> $htype,
        'thumb_left' 	=> $lthumb,
        'thumb_left_url'=> $lturl,
        'thumb_right' 	=> $rthumb,
        'thumb_right_url'=> $rturl,
    );
	$params['pk_block']   		= !empty($_POST['block_id']) ? $_POST['block_id'] : 0;
	$params['fk_channel']     	= !empty($_POST['channel_id']) ? $_POST['channel_id'] : 0;
	$params['fk_user_owner']  	= $this->orgOwner;
    $info = org_api::updateOrgblock($params);
    if (!empty($info)) {
		$result->code = 200;
        $result->msg = "Success!";
        return $result;
    } else {
		$result->code = -100;
        $result->msg = "修改失败!";
        return $result;
    }
}

public function pageAddRowsAjax($inPath){
    $tid=!empty($_POST['tid'])?$_POST['tid']:0;
    //判断能否修改
    $template=user_organization::getOrgTemplateInfo($tid);
    $result=new stdClass;
    if(empty($template)||$template->owner_id!=$this->orgOwner){
        $result->error="模版不存在或无权限修改";
        return $result;
    }
    if($template->row_count>=4){
        $result->error="最多添加四行";
        return $result;
    }
    $queryArr=array();
    if($template->query_arr){
        foreach($template->query_arr as $qk=>$qv){
            if($qk=='grade_id'&&$qv==0){continue;}
            if($qk=='subject_id'&&$qv==0){continue;}
        $queryArr[$qk]=$qv;
        }
    }
    if($template->query_arr->fee_type=="2"){
        $template->query_arr->fee_type = "0,1";
    }
    $queryArr['fee_type'] = $template->query_arr->fee_type;
    $queryArr['user_id']=$this->orgOwner;
    $queryArr['admin_status']=1;
    $rowCount=$template->row_count+1;
    $seekArr = array(
        "f"=>array(
            'course_id', 
            'title',
            'thumb_big',
        ),
        "q" => $queryArr,
        "p"=>$rowCount,
        "pl"=>4,
    );
    
    $seekRet=seek_api::seekcourse($seekArr);
    $courses=$seekRet->data;
    if(empty($courses)){
        $result->error="课程数量不足，无法添加";
        return $result;
    }
    $params=array(
            'row_count'=>$rowCount,
            'last_updated'=>date('Y-m-d H:i:s'),
        );
    $r=user_organization::updateOrgTemplate($tid,$params);
    if(!empty($r)){
        foreach($courses as $ck=>$cv){
            $courses[$ck]->thumb_big=utility_cdn::file($cv->thumb_big);
        }
        return $courses;
    }else{
        $result->error="修改失败!";
        return $result;
    }
}
public function pageIframeTemplateCourse($inPath){
    $top=$inPath[3];
    $templateId=$inPath[4];
    $page = isset($_GET['page']) ? $_GET['page']:1;
    $length  = 50;
    $data = array();
    $data["type"] = array(1,2,3);//全部课程
    $data["admin_status"] = "1";
    $data["shelf"] = 1;
    $data["create_time"] = "desc";
    $courseRet = course_api::getCourselistByOid($page, $length, $this->orgOwner, $data);
    $courseList = !empty($courseRet->data) ? $courseRet->data : '';
    $this->assign('top', $top);
    $this->assign('courseList', $courseList);
    //分销课程获取
    $condition = array("con" => "t_course_promote.ver=t_course_resell.ver AND   t_course_promote.status=1  AND t_course_resell.status=1", "fk_org_resell" => $this->orgInfo->oid);
    $salesInfo = course_resell_api::getSalesCourse($page, $length, $condition);
    if (!empty($salesInfo)) {
        foreach ($salesInfo as $k => $v) {
            $course[$k] = $v->fk_course;
        }
        $idStr = implode(",", $course);
        $fields = array("course_id", "org_subname", "title", "create_time", "desc", "start_time", "thumb_big",
            "thumb_med", "thumb_sma", "is_promote", "org_id",
        );
        $query = [
            'course_id' => $idStr,
        ];
        $params = [
            "f" => $fields,
            "q" => $query,
            "p" => $page,
            "pl" => $length,
            "ob" => 'desc'
        ];
        $resCourse = seek_api::seekcourse($params);
    }

    $salseList = !empty($resCourse->data) ? $resCourse->data : '';
    $this->assign('salseList', $salseList);
    $this->assign('tid', $templateId);
    $this->render('org/iframe.template.course.html');
}

public function pageIframeTemplateCourseEdit($inPath)
{
    $top = $inPath[3];
    $templateId = $inPath[4];
    $courseId = !empty($inPath[5]) ? $inPath[5] : 0;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $length = 50;
    $data = array();
    $data["type"] = array(1, 2, 3);//全部课程
    $data["admin_status"] = "1";
    $data["shelf"] = 1;
    $data["create_time"] = "desc";
    $courseRet = course_api::getCourselistByOid($page, $length, $this->orgOwner, $data);
    $courseList = !empty($courseRet->data) ? $courseRet->data : '';
    $this->assign('top', $top);
    $this->assign('courseList', $courseList);
    $this->assign('courseId', $courseId);
    //分销课程获取
    $condition = array("con" => "t_course_promote.ver=t_course_resell.ver AND   t_course_promote.status=1  AND t_course_resell.status=1", "fk_org_resell" => $this->orgInfo->oid);
    $salesInfo = course_resell_api::getSalesCourse($page, $length, $condition);
    if (!empty($salesInfo)) {
        foreach ($salesInfo as $k => $v) {
            $course[$k] = $v->fk_course;
        }
        $idStr = implode(",", $course);
        $fields = array("course_id", "org_subname", "title", "create_time", "desc", "start_time", "thumb_big",
            "thumb_med", "thumb_sma", "is_promote", "org_id",
        );
        $query = [
            'course_id' => $idStr,
        ];
        $params = [
            "f" => $fields,
            "q" => $query,
            "p" => $page,
            "pl" => $length,
            "ob" => 'desc'
        ];
        $resCourse = seek_api::seekcourse($params);
    }
    $fields = ["course_id", "title", "is_promote", "org_id"];
    $query = ['course_id' => $courseId];
    $params = [
        "f" => $fields,
        "q" => $query,
        "p" => $page,
        "pl" => $length,
        "ob" => 'desc'
    ];
    $selected = seek_api::seekcourse($params);
    $salseList = !empty($resCourse->data) ? $resCourse->data : '';
    $this->assign('salseList', $salseList);
    $this->assign('selected', !empty($selected->data) ? $selected->data : '');
    $this->assign('tid', $templateId);
    $this->render('org/iframe.template.course.edit.html');
}


//删除手动模板中课程
public function pagedelTemplateOfCourse(){
    $result = new stdClass;
    $cid = !empty($_POST['cid']) ? (int)$_POST['cid'] : 0;
    $tid = !empty($_POST['tid']) ? (int)$_POST['tid'] : 0;
    $templates = user_organization::getOrgTemplateInfo($tid);
    if (empty($cid) || empty($tid)) {
        $result->error = "参数错误";
        return $result;
    }
    if (!empty($templates->course_arr) && in_array($cid, $templates->course_arr)) {
        $arr1 = [];
        $arr1[] = $cid;
        $courseIdArr = array_diff($templates->course_arr, $arr1);
        $courseStr = implode(",", $courseIdArr);
        $params = array(
            'row_count' => 0,
            'course_ids' => $courseStr,
            'last_updated' => date('Y-m-d H:i:s'),
        );
        $data = user_organization::updateOrgTemplate($_POST['tid'], $params);
        if ($data > 0) {
            $result->code = 200;
            $result->success = "删除成功";
            return $result;
        } else {
            $result->code = -200;
            $result->success = "删除失败";
            return $result;
        }
    }

}

public function pageAddChannelCourse($inPath){
    $result = new stdClass;
    if (empty($_POST['cid'])) {
        $result->error = "请选择课程";
        return $result;
    }elseif(empty($inPath[3])){
		 $result->error = "频道id参数缺失";
        return $result;
	}else {
        $con                    = array();
        $con['fk_channel']      = !empty($inPath[3]) ? (int)$inPath[3] : 0;
        $con['pk_block']	    = !empty($inPath[5]) ? (int)$inPath[5] : 0;
        $con['fk_user_owner']   = isset($this->orgInfo->user_owner_id) ? $this->orgInfo->user_owner_id : 0;
        $blockCheck				= org_api::getBlockOneInfoCheck($con);
        if (empty($blockCheck) || $blockCheck->owner_id != $this->orgOwner) {
            $result->error = "所修改的模块不存在或无权限修改";
            return $result;
        }
        if(!empty($inPath[4])&&$inPath[4]=="add"){
			if (!empty($blockCheck->course_arr)) {
				$courseIds 	= array_merge($blockCheck->course_arr, $_POST['cid']);
			} else {
				$courseIds = $_POST['cid'];
			}
			$cArr 			= array_unique($courseIds);
			$courseIds 		= implode(',', $cArr);
		}elseif(!empty($inPath[4])&&$inPath[4]=="edit"){
			$cids = implode(',', $blockCheck->course_arr);
            $courseIds = str_replace($_POST['tid'], implode(",", $_POST['cid']), $cids);
		}elseif(!empty($inPath[4])&&$inPath[4]=="remove"){
			$cids 		= $blockCheck->course_arr;
			$under 		= array_search($_POST['cid'],$cids);
			unset($cids[$under]);
			$courseIds  = implode(",",$cids);
		}
		
        if (!empty($blockCheck->course_arr) && count($blockCheck->course_arr) > 0) {
            $count = count($blockCheck->course_arr);
        } else {
            $count = 1;
        }
        //重新计算行数
        $rowCount  = ceil($count / 4);
        $params    = array(
            'row_count' 		=> $rowCount,
            'course_ids' 		=> $courseIds,
            'last_updated' 		=> date('Y-m-d H:i:s'),
			'pk_block'			=> !empty($inPath[5]) ? (int)$inPath[5] : 0,
			'fk_channel'		=> !empty($inPath[3]) ? (int)$inPath[3] : 0,
			'fk_user_owner'		=> isset($this->orgInfo->user_owner_id) ? $this->orgInfo->user_owner_id : 0,
        );
        $info 	   = org_api::updateOrgblock($params);
        if ($info) {
            $result->status = "Success!";
            return $result;
        } else {
            $result->error = "修改失败!";
            return $result;
        }
    }
}

//模版上移
public function pageMoveUpAjax($inPath)
{
    $result = new stdClass;
    if (empty($_POST['tid'])) {
        $result->error = "参数错误";
        return $result;
    } else {
        $tid = $_POST['tid'];
        $old = !empty($_POST['old']) ? $_POST['old'] : 0;
        $sort = !empty($_POST['sort']) ? (int)$_POST['sort'] : 0;
        if ($sort < 1) {
            $sort = 1;
        } else {
            $sort = $sort + 1;
        }
        //判断能否修改
        $template = user_organization::getOrgTemplateInfo($tid);
        if (empty($template) || $template->owner_id != $this->orgOwner) {
            $result->error = "模版不存在或无权限修改";
            return $result;
        }
        //设置新模版
        $newArr = array(
            'sort' => $sort - 1,
            'last_updated' => date('Y-m-d H:i:s'),
        );
        $r1 = user_organization::updateOrgTemplate($tid, $newArr);
        //修改老模版
        $oldArr = array(
            'sort' => $sort,
            'last_updated' => date('Y-m-d H:i:s'),
        );
        $r2 = user_organization::updateOrgTemplate($old, $oldArr);
        if (!empty($r1) && !empty($r2)) {
            $result->status = "Success!";
            return $result;
        } else {
            $result->error = "修改失败!";
            return $result;
        }
    }
}

//模版下移
public function pageMoveDownAjax($inPath)
{
    $result = new stdClass;
    if (empty($_POST['tid'])) {
        $result->error = "参数错误";
        return $result;
    } else {
        $tid = $_POST['tid'];
        $sort = !empty($_POST['sort']) ? $_POST['sort'] : 0;
        $old = !empty($_POST['old']) ? (int)$_POST['old'] : 0;
        if ($sort < 1) {
            $sort = 1;
        } else {
            $sort = $sort + 1;
        }
        //判断能否修改
        $template = user_organization::getOrgTemplateInfo($tid);
        if (empty($template) || $template->owner_id != $this->orgOwner) {
            $result->error = "模版不存在或无权限修改";
            return $result;
        }
        //设置新模版
        $newArr = array(
            'sort' => $sort + 1,
            'last_updated' => date('Y-m-d H:i:s'),
        );
        $r1 = user_organization::updateOrgTemplate($tid, $newArr);
        //修改老模版
        $oldArr = array(
            'sort' => $sort,
            'last_updated' => date('Y-m-d H:i:s'),
        );
        $r2 = user_organization::updateOrgTemplate($old, $oldArr);
        if (!empty($r1) && !empty($r2)) {
            $result->status = "Success!";
            return $result;
        } else {
            $result->error = "修改失败!";
            return $result;
        }
    }
}

//保存排序
public function pageSaveSortAjax($inPath)
{
    $result = new stdClass;
    if (empty($_POST['tid']) || empty($_POST['cids'])) {
        $result->error = "参数错误";
        return $result;
    } else {
        $tid = $_POST['tid'];
        $cids = trim($_POST['cids'], ',');
        //判断能否修改
        $template = user_organization::getOrgTemplateInfo($tid);
        if (empty($template) || $template->owner_id != $this->orgOwner) {
            $result->error = "模版不存在或无权限修改";
            return $result;
        }
        //保存排序
        $newArr = array(
            'course_ids' => $cids,
            'last_updated' => date('Y-m-d H:i:s'),
        );
        $r = user_organization::updateOrgTemplate($tid, $newArr);
        if (!empty($r)) {
            $result->status = "Success!";
            return $result;
        } else {
            $result->error = "修改失败!";
            return $result;
        }
    }
}
	
	
	/**
	 * 频道首页发布
	 * @param  int $oid
	 * @return array $result
	 * @author peng
	 */
	public function pagesetChannel($InPath){
		$result                 = new stdClass;
		$data                   = array();
		$info                   = '';
        $channelName            = !empty($_POST['channel-name']) ? $_POST['channel-name'] : '';
        $channelId              = !empty($_POST['channel_id'][0]) ? $_POST['channel_id'][0] : 0;
		$owerId                 = isset($this->orgInfo->user_owner_id) ? $this->orgInfo->user_owner_id : 0;
        $con                    = array();
        $con['fk_channel']      = $channelId;
        $con['fk_user_owner']   = $owerId;
		$updateArr   			=  array("pk_channel"=>$channelId,'fk_user_owner'=>$owerId,'name'=>$channelName);
		org_api::updatechannel($updateArr);
		//查询是否有banner和推荐图
		$arr				= array();
		$arr['fk_channel'] 	= !empty($inPath[3]) ? $inPath[3] : 0;
		$arr['fk_org'] 		= !empty($this->orgInfo->oid) ? $this->orgInfo->oid : 0;
		$thumb 				= org_api::BannerList($arr);
		if (empty($thumb)){
			$result->code = -100;
			$result->msg = "请添加幻灯片或推荐图";
			return $result;
		}
		if(!empty($_POST['block_id'])){
			foreach ($_POST['block_id'] as $k => $v) {
				$data[$k]['fk_block'] = isset($_POST['block_id'][$k]) ? $_POST['block_id'][$k] : '';
				$data[$k]['fk_user_owner'] = isset($this->orgInfo->user_owner_id) ? $this->orgInfo->user_owner_id : 0;
                $data[$k]['fk_channel'] = isset($_POST['channel_id'][$k]) ? $_POST['channel_id'][$k] : 0;
				$data[$k]['title'] = isset($_POST['title'][$k]) ? $_POST['title'][$k] : '';
				$data[$k]['row_count'] = isset($_POST['row_count'][$k]) ? $_POST['row_count'][$k] : '';
				$data[$k]['recommend'] = isset($_POST['recommend'][$k]) ? $_POST['recommend'][$k] : '';
				$data[$k]['query_str'] = isset($_POST['query_str'][$k]) ? $_POST['query_str'][$k] : '';
				$data[$k]['order_by'] = isset($_POST['order_by'][$k]) ? $_POST['order_by'][$k] : '';
				$data[$k]['course_ids'] = isset($_POST['course_ids'][$k]) ? $_POST['course_ids'][$k] : '';
				$data[$k]['sort'] = isset($_POST['sort'][$k]) ? $_POST['sort'][$k] : '';
				$data[$k]['create_time'] = isset($_POST['create_time'][$k]) ? $_POST['create_time'][$k] : '';
				$data[$k]['last_updated'] = date("Y-m-d H:i:s");
				$data[$k]['set_url'] = isset($_POST['set_url'][$k]) ? $_POST['set_url'][$k] : '';
				$data[$k]['type'] = isset($_POST['type'][$k]) ? $_POST['type'][$k] : '';
				$data[$k]['thumb_left'] = isset($_POST['thumb_left'][$k]) ? $_POST['thumb_left'][$k] : '';
				$data[$k]['thumb_right'] = isset($_POST['thumb_right'][$k]) ? $_POST['thumb_right'][$k] : '';
				$data[$k]['thumb_left_url'] = isset($_POST['thumb_left_url'][$k]) ? $_POST['thumb_left_url'][$k] : '';
				$data[$k]['thumb_right_url'] = isset($_POST['thumb_right_url'][$k]) ? $_POST['thumb_right_url'][$k] : '';
			}
			$blockId = array();
			$blockInfo = org_api::getOrgChannelBlockInfo($con);
			if (!empty($blockInfo)) {
				foreach ($blockInfo as $m => $n) {
					$blockId[] = $n->pk_block;
				}
			}
			$delInfo = array_diff($blockId, $_POST['block_id']);
			$idStr = implode(",", $delInfo);
			$idArr = array("fk_block" => $idStr,"fk_user_owner"=>$this->orgOwner);
			org_api::deleteOrgblockInfo($idArr);
			foreach ($data as $a => $b) {
				$blockData = org_api::getblockOneInfo($b['fk_block'], $b['fk_user_owner']);
				/*if (empty($b['course_ids'])) {
					$result->code = "-101";
					$result->error = "请在空模板中添加课程";
					return $result;
				}*/
				if (!empty($blockData)) {
					$info = org_api::updateChannelBlockData($b);
				} else {
					$info = org_api::addChannelBlockData($b);
				}
			}
		}
		if ($info || $thumb) {
			$result->code = 100;
			$result->msg = "发布成功";
			return $result;
		} else {
			$result->code = -100;
			$result->msg = "发布失败";
			return $result;
		}
	}

	
	//增加左右图
	public function pageaddCourseImg($inPath){
		$tid = !empty($inPath[3]) ? substr($inPath[3], 5) : 0;
		$template = user_organization::getOrgTemplateInfo($tid);
		$this->assign("tinfo", $template);
		return $this->render("/org/iframe.template.adimg.html");
	}
	
	public function pageupdateChannelThumbPic($inPath){
		//处理上传图片
		$result = new stdClass;
		$path = ROOT_WWW . "/upload/tmp";
		$thumb = !empty($_POST['pic_url']) ? $_POST['pic_url'] : '';
		if (!empty($thumb)) {
			preg_match("/^[a-zA-z]+:\/\/[\w.]+(yunke.com|gn100.com)+[^\s]*$/", $thumb, $matches);
			if (empty($matches[0])) {
				$result->code = -8;
				$result->error = "请使用本机构或云课链接地址~";
				return $result;
			}
		}
	    $filename = $path."/".$this->user['uid'].".org.jpg";
        if(is_file($filename)&&!empty($_REQUEST['w'])){
            list($width, $height, $type, $attr) = getimagesize($filename);
            $filename_dst = $path."/".$this->user['uid'].".dst.png";
            $targ_w = $_REQUEST['w'];
            $targ_h = $_REQUEST['h'];
            switch($type){
            case 1: $img_r = imagecreatefromgif($filename);break;
            case 2: $img_r = imagecreatefromjpeg($filename);break;
            default:
                $img_r = imagecreatefrompng($filename);
            }
            $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
            imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_REQUEST['w'],$_REQUEST['h']);
            $r = imagejpeg($dst_r, $filename_dst);
            if($r){
                $thumbnail = new SThumbnail($filename_dst, 230);
                $thumbnail->setMaxSize(230, 390);
                $filename_180 = utility_file::tempname("thumb");
                if($thumbnail->genFile($filename_180)===false){
                    $result->error = "不是有效果的图片";
                    return $result;
                };
                $file = utility_file::instance();
                $r1 = $file->upload($filename_180,user_api::getLoginUid(),"image","");
                if(empty($r1)){
                    $result->error = "图片处理失败";
                    return $result;
                }
                //图片尺寸都是一样的
                $_POST['thumb_small']=$r1->fid;
                $_POST['thumb_med']=$r1->fid;
                $_POST['thumb_big']=$r1->fid;
                unlink($filename);
                unlink($filename_dst);
                unlink($filename_180);
            }
        }
		if(isset($_POST['thumbType'])&&$_POST['thumbType']==2){
				if(!empty($_POST['thumb_big'])){
					$params = array(	"type"=>2,
										"thumb_right"=>!empty($_POST['thumb_big']) ? $_POST['thumb_big'] : '',
										"pk_block"=>$_POST['tpic'],
										"fk_user_owner"=> $this->orgOwner,
										"thumb_right_url"=>$thumb,
								);
				}else{
					$params = array(	"type"=>2,
										"pk_block"=>$_POST['tpic'],
										"fk_user_owner"=> $this->orgOwner,
										"thumb_right_url"=>$thumb,
								);
				}
		}elseif(isset($_POST['thumbType'])&&$_POST['thumbType']==1){
				if(!empty($_POST['thumb_big'])){
					$params = array(	"type"=>1,
										"thumb_left"=>!empty($_POST['thumb_big']) ? $_POST['thumb_big'] : '',
										"pk_block"=>$_POST['tpic'],
										"fk_user_owner"=>$this->orgOwner,
										"thumb_left_url"=>$thumb,
								);
				}else{
					$params = array(	"type"=>1,
										"pk_block"=>$_POST['tpic'],
										"fk_user_owner"=>$this->orgOwner,
										"thumb_left_url"=>$thumb,
								);
				}
				
		}
	
		$params['fk_channel'] = !empty($inPath[3]) ? $inPath[3] : 0;
		$picInfo 			  = org_api::updateChannelThumbPic($params);
		if($picInfo){
			$result->code = 100;
			$result->msg = "success";
		} else {
			$result->code = -100;
			$result->msg = "fialed";
		}
		return $result;
	}

	//获取列表
	public function pagexiaowoOrgList()
	{
		$result = new stdClass;
		$data = array();
		$data['fk_org'] = !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$info = user_organization::xiaowoOrgList($data);
		$result->code = 100;
		$result->data = !empty($info) ? $info : '';
		return $result;
	}

	//获取单条
	public function pagexiaowoOrgOneInfo()
	{
		$result = new stdClass;
		$data = array();
		$data['fk_org'] = !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$data['pk_banner'] = !empty($_POST['banner_id']) ? (int)$_POST['banner_id'] : 0;
		$info = user_organization::xiaowoOrgOneInfo($data);
		return !empty($info) ? $this->setResult($info, 200, "查询成功") : $this->setResult('', -200, "查询为空");
	}

	//增加banner
	public function pageaddXiaowoOrg()
	{
		$data 				= array();
		$data['fk_org'] 	= !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$data['types'] 		= !empty($_POST['type']) ? (int)$_POST['type'] : 1;
		if ($data['types'] == 1) {
			$data['thumb_app'] = !empty($_POST['thumb_app']) ? $_POST['thumb_app'] : '';
			$data['url_app'] = !empty($_POST['url_app']) ? $_POST['url_app'] : '';
		} elseif ($data['types'] == 2) {
			$data['thumb_ipad'] = !empty($_POST['thumb_ipad']) ? $_POST['thumb_ipad'] : '';
			$data['url_ipad'] = !empty($_POST['url_ipad']) ? $_POST['url_ipad'] : '';
		}
		$result = user_organization::addXiaowoOrg($data);
		return !empty($result) ? $this->setResult($result, 200, "操作成功") : $this->setResult($result, -200, "操作失败");
	}

	//修改banner
	public function pageupdateXiaowoOrgBanner()
	{
		$data 				= array();
		$bid 				= !empty($_POST['banner_id']) ? (int)$_POST['banner_id'] : 2;
		$data['fk_org'] 	= !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$data['types'] 		= !empty($_POST['type']) ? (int)$_POST['type'] : 0;
		$data['thumb_app'] 	= !empty($_POST['thumb_app']) ? $_POST['thumb_app'] : '';
		$data['url_app'] 	= !empty($_POST['url_app']) ? $_POST['url_app'] : '';
		$data['thumb_ipad'] = !empty($_POST['thumb_ipad']) ? $_POST['thumb_ipad'] : 'maomao';
		$data['url_ipad'] 	= !empty($_POST['url_ipad']) ? $_POST['url_ipad'] : 'nnbb';
		$result = user_organization::updateXiaowoOrgBanner($bid, $data);
		return !empty($result) ? $this->setResult($result, 200, "操作成功") : $this->setResult($result, -200, "操作失败");
	}
	//频道设置页面
	public function pagechannelSet($inPath){
		$data 					= array();
		$data['fk_channel'] 	= !empty($inPath[3]) ? $inPath[3] : 0;
		$data['fk_org'] 		= !empty($this->orgInfo->oid) ? $this->orgInfo->oid : 0;
		//临时模块中获取数据
		$con 					= array();
        $thumb                  = array();
		$con['pk_channel'] 	    = !empty($inPath[3]) ? $inPath[3] : 0;
		$con['fk_user_owner'] 	= $this->orgOwner;
		$dataInfo 				= org_api::getchannelOneInfo($con);
		
		if(empty($dataInfo)){
			return $this->redirect("/org.channel.channellist");
		}
		
		$blockInfo 				= org_api::getblockCheck($con);
		//频道banner显示
		$info 					= org_api::BannerList($data);
		if(!empty($info)){
			foreach($info as $k=>$v){
				$thumb[$v->type][] = $v;
			}
		}
		$this->assign("dataInfo",$dataInfo);
		$this->assign("bannerList",!empty($thumb[1]) ? $thumb[1] : '');
		$this->assign("recommendThumb",!empty($thumb[2]) ? $thumb[2] : '');
		$this->assign("channel_id",$con['pk_channel']);
		$condition = array("fk_org_resell" => $this->orgInfo->oid);
		$salesInfo = course_resell_api::getSalesCourse($page = 1, $length = 0, $condition);
		$resell = array();
		if (!empty($salesInfo)) {
			foreach ($salesInfo as $k => $v) {
				$resell[$v->fk_course] = $v;
			}
		}
		if (!empty($blockInfo)) {
			$valueNameStr = array();
			$cateIdArr = array();
			$valueIdArr = array();
			$num = $this->orgInfo->is_pro == 1 ? 8 : 4;
			foreach ($blockInfo as $tk => $tv) {
				if ($tv->recommend == 1) {
					$cateTemp = array('first_cate', 'second_cate', 'third_cate');
					$queryArr = array();
					if(!empty($tv->query_arr)){
                            foreach ($tv->query_arr as $qk => $qv) {
                            if ($tv->query_arr->fee_type == "2") {
                                $tv->query_arr->fee_type = "0,1";
                            }
                            if ($qk == 'grade_id' && $qv == 0) {
                                continue;
                            }
                            if ($qk == 'subject_id' && $qv == 0) {
                                continue;
                            }
                            if ($qk == 'attr_value_id') {
                                $queryArr[$qk] = str_replace('|', ',', $qv);
                                $attrValueArr[$tv->block_id] = explode('|', $qv);
                            }
                            if (in_array($qk, $cateTemp)) {
                                $cateIdArr[] = $qv;
                            }
                        }

                        if (!isset($tv->query_arr->course_type)) {
                            $tv->query_arr->course_type = "1,2,3";
                            $queryArr['course_type'] = $tv->query_arr->course_type;
                        } else {
                            $queryArr['course_type'] = $tv->query_arr->course_type;
                        }
                        if (isset($tv->query_arr->first_cate)) {
                            $queryArr['first_cate'] = $tv->query_arr->first_cate;
                        }
                        if (isset($tv->query_arr->second_cate)) {
                            $queryArr['second_cate'] = $tv->query_arr->second_cate;
                        }
                        if (isset($tv->query_arr->third_cate)) {
                            $queryArr['third_cate'] = $tv->query_arr->third_cate;
                        }
                        $queryArr['fee_type'] = $tv->query_arr->fee_type;
                    }
					if (!empty($attrValueArr[$tv->block_id])) {
						$valueRet = course_api::getAttrValueByVidArr($attrValueArr[$tv->block_id]);
						if (!empty($valueRet)) {
							foreach ($valueRet as $vo) {
								$valueNameArt = SLanguage::tr($vo->name, "course.list");
							}
							$valueNameStr[$tv->block_id] = $valueNameArt;

						}
					}
					$queryArr['user_id'] = $this->orgOwner;
					$queryArr['admin_status'] = 1;
					//查询排序
					if (!empty($tv->order_by)) {
						$obArr = explode(':', $tv->order_by);
						$orderArr = array($obArr[0] => $obArr[1]);
					} else {
						$orderArr = array('create_time' => 'desc');
					}
					$seekArr = array(
						"f" => array(
							'course_id',
							'title',
							'thumb_big',
						),
						"q" => $queryArr,
						"ob" => $orderArr,
						"p" => 1,
						"pl" => $tv->row_count * $num,
					);

					$seekRet = seek_api::seekcourse($seekArr);
					$blockInfo[$tk]->courses = $seekRet->data;
					$cid = '';
					foreach ($seekRet->data as $a => $b) {
						$cid .= $b->course_id . ",";
					}
					$cid = rtrim($cid, ",");
					$blockInfo[$tk]->course_ids = $cid;
				}
				if ($tv->recommend == 2) {
					if (!empty($tv->course_ids)) {
						$seekArr = array(
							"f" => array(
								'course_id',
								'title',
								'thumb_big',
								'admin_status',
								'subdomain',
							),
							"q" => array('course_id' => $tv->course_ids),
							"p" => 1,
							"pl" => 30,
						);
						$seekRet = seek_api::seekcourse($seekArr);
						
						if (!empty($seekRet->data)) {
							//按照存的顺序取出来
							$data1 = array();
							$data2 = array();
							foreach ($seekRet->data as $sdk => $sdv) {
								$sdv->resell_status = !empty($resell[$sdv->course_id]->status) ? $resell[$sdv->course_id]->status : '';
								$sdv->restatus = !empty($resell[$sdv->course_id]->restatus) ? $resell[$sdv->course_id]->restatus : '';
								$data1[$sdv->course_id] = $sdv;
								if (strpos($sdv->subdomain, ".") === false) {
									$sdv->surl = "//" . $sdv->subdomain . "." . $this->domain . "/course.info.show/" . $sdv->course_id;
								} else {
									$sdv->surl = "//" . $sdv->subdomain . "/course.info.show/" . $sdv->course_id;
								}
							}
							foreach($tv->course_arr as $tcv){
                                if(!empty($data1[$tcv])){
                                    $data2[]=$data1[$tcv];
                                }
                            } 
							$blockInfo[$tk]->courses = $data2;
						}
					} else {
						$blockInfo[$tk]->courses = '';
					}


				}
				if($tv->type==3){
					$con['fk_block']		= $tv->block_id;
					$con['type']		    = 2;
					$thumbInfo 				= org_api::BannerList($con);
					$blockInfo[$tk]->thumb_arr=  $thumbInfo;
					
				}
			}
			if (!empty($cateIdArr)) {
				$cateIdStr = implode(',', $cateIdArr);
				$cateRet = course_api::getCateByCidStr($cateIdStr);
				if (!empty($cateRet)) {
					foreach ($cateRet as $co) {
						$cateList[$co->pk_cate] = $co->name_display;
					}
					$this->assign('cateList', $cateList);
				}
			}
			$this->assign('valueNameStr', $valueNameStr);
		}
		$this->assign('blockInfo', !empty($blockInfo) ? $blockInfo : '');
		$this->assign('is_pro', isset($this->orgInfo->is_pro) ? $this->orgInfo->is_pro : 0);
		return $this->render('org/channel.set.html');
	}
	/**
	 * 频道模块单条获取
	 */
	 public function pagegetBlockOneInfoCheck(){
		$con 					= array();
		$con['pk_block']		= !empty($_POST['tid']) ? $_POST['tid'] : 0;
		$con['fk_channel'] 	    = 1;
		$con['fk_user_owner'] 	= $this->orgOwner;
		$blockOne 				= org_api::getBlockOneInfoCheck($con);
		return !empty($blockOne) ? $blockOne : '';
	 }
    /**
	 * 频道列表页
	 */
	public function pagechannelList(){
        $result 	= new stdClass;
		$oid 		= !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$infoList 	=  org_api::channelList($oid);
        $da         =  user_organization::getOrgByUid($this->orgInfo->user_owner_id);
        $orgData    = user_organization::subdomain();
        if(!empty($infoList)){
            foreach($infoList as $k=>$v){
                if($v->fk_user == $orgData->userId){
                    $v->url       = $orgData->subdomain."/site.main.channelIndex/$v->pk_channel";
                    $v->real_name = '';
                }
                $v->real_name = $this->user['real_name'];
                
            }
        }
        $this->assign('channelList', !empty($infoList) ? $infoList : '');
		return $this->render('org/channel.list.html');
	}
	public function pagelist(){
		$result 	= new stdClass;
		$oid 		= !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$infoList 	=  org_api::channelList($oid);
		$data 		= !empty($infoList) ? $infoList : '';
		return $this->setResult($data,!empty($infoList) ? 200 : -100,!empty($infoList) ? "查询成功" : "数据为空");
	}
	public function pagegetchannelOneInfo(){
		$result 			= new stdClass;
		$data 				= array();
		$data['fk_org']		= !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$data['pk_channel'] = !empty($_POST['tid']) ? $_POST['tid'] : 0;
		$dataInfo 	= org_api::getchannelOneInfo($data);
		$data 		= !empty($infoList) ? $infoList : '';
		//return $this->setResult($data,!empty($infoList) ? 200 : -100,!empty($infoList) ? "查询成功" : "数据为空");
	}
	//增加channel
	public function pageaddChannel($inPath){
		$result 			= new stdClass;
		$data 	    		= array();
		$data['fk_org'] 	= !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$data['fk_user'] 	= $this->orgOwner;
		$num 				= org_api::channelList($data['fk_org']);
		if(!empty($num)&&count($num)>=20){
			$result->code  	= -200;
			$result->msg    = "只能添加20条";
			return $result;
		}elseif(empty($inPath[3])){
			$infoList 		= org_api::addChannel($data);
			$result->code   = !empty($infoList->result->code) ? (int)$infoList->result->code : 0;
			$result->msg    = "成功";
			$result->lastid = !empty($infoList->result->msg) ? (int)$infoList->result->msg : 0;;
			return $result;
		}
		
	}
	//修改频道名字
	public function pageaddChannelName($inPath){
		$result 			= new stdClass;
		$data 	    		= array();
		$updateArr   		=  array(   'pk_channel'	=> $inPath[3],
										'fk_user_owner' => $this->orgOwner,
										'name'			=> !empty($_POST['channelName']) ? $_POST['channelName'] : ''
								);
		$info 				= org_api::updatechannel($updateArr);
		if($info){
			$result->code   = 200;
			$result->msg    = "成功";
			return $result;
		}else{
			$result->code   = -200;
			$result->msg    = "失败";
			return $result;
		}
		
	}
    //删除频道
    public function pagedeleteChannel(){
		$result 			    = new stdClass;
        $data                   = array();
        $data['fk_user_owner'] 	= !empty($this->orgOwner) ? (int)$this->orgOwner : 0;
        $data['fk_org']		    = !empty($this->orgInfo->oid) ? (int)$this->orgInfo->oid : 0;
		$data['fk_channel']     = !empty($_POST['channel_id']) ? $_POST['channel_id'] : 0;
        //channel表
		$dataInfo 	            = org_api::getchannelOneInfo($data);
        $del                    = org_api::deleteChannel($data);
        if(isset($del->result->code)&&$del->result->code==200){
            //删除block_check表数据
            $blockCheck = org_api::DeleteBlock($data);
			//block表数据
            $block      = org_api::deleteOrgBlock($data);
             if(isset($block->result->code)&&$block->result->code==200){
                 $data['type']    = "1,2";
				 //删除channel_banner表,幻灯片和推荐图
                 $channelBanner   = org_api::deleteBannerAndThumb($data);
             }
            $result->msg = "删除成功";
			$result->code= 200;
			return $result;
        }else{
			$result->msg = "删除失败";
			$result->code = -100;
			return $result;
		}
        
	}
	/*
	 *增加修改频道banner
	 */
	public function pageaddChannelBanner($inPath){
		//type:  1:幻灯片,2:推荐图
		$result 			= new stdClass;
		$data 				= array();
		$data['fk_channel'] = !empty($_POST['channelId']) ? $_POST['channelId'] : 0;
		$data['fk_user'] 	= !empty($this->orgOwner) ? $this->orgOwner : 0;
		$data['fk_org'] 	= !empty($this->orgInfo->oid) ? $this->orgInfo->oid : 0;
		$data['thumb']		= !empty($_POST['thumb']) ? $_POST['thumb'] : '';
		$data['url']		= !empty($_POST['url']) ? $_POST['url'] : '';
		$data['type'] 		= !empty($_POST['type']) ? $_POST['type'] : 1;
		$data['rgb']		= !empty($_POST['color_rgb']) ? $_POST['color_rgb'] : '';
		$bannerId			= !empty($_POST['banner_id']) ? $_POST['banner_id'] : '';
		if(empty($_POST['channelId'])){
			$result->error  = "频道id参数缺失";
			$result->code   = -100;
			return $result;
		}
		if(!empty($bannerId)){
			$info 			= org_api::updateBanner($bannerId,$data);
		}else{
			$info 			= org_api::addChannelBanner($data);
		}
		return $this->setResult($info->result->data,$info->result->code,$info->result->msg);
	}
	
	//获取频道banner列表
	public function pageBannerList($inPath){
		$data 				= array();
		$data['fk_channel'] = 1;
		$data['fk_org'] 	= !empty($this->orgInfo->oid) ? $this->orgInfo->oid : 0;
		$data['type'] 		= 1;
		$info 				= org_api::BannerList($data);
		return $this->setResult($info,!empty($info) ? 200 : -200,!empty($info) ? "查询成功" : "数据为空");
	}
	/**
	 * 删除banner
	 */
	public function pagedelBanner($inPath){
		$result 			= new stdClass;
		$data 				= array();
		$data['fk_channel'] = !empty($_POST['channelId']) ? $_POST['channelId'] : 0;;
		$data['fk_org'] 	= !empty($this->orgInfo->oid) ? $this->orgInfo->oid : 0;
		$data['fk_user'] 	= !empty($this->orgOwner) ? $this->orgOwner : 0;
		$data['type'] 		= !empty($_POST['type']) ? $_POST['type'] : 1;
		$bid				= !empty($_POST['bannerId']) ? $_POST['bannerId'] : 0;
		$info 				= org_api::delBanner($bid,$data);
		$result 			= $info;
		return $result;
	}
	
	/**
	 * 删除频道模块
	 */
	public function pageDeleteBlock($inPath){
		$result = new stdClass;
		if (empty($_POST['tid'])) {
			$result->error = "参数错误";
			return $result;
		}
		$con 					= array();
		$con['pk_block']		= !empty($_POST['tid']) ? $_POST['tid'] : 0;
		$con['fk_channel'] 	    = !empty($inPath[3]) ? $inPath[3] : 0;
		$con['fk_user_owner'] 	= $this->orgOwner;
		$blockOne 				= org_api::getBlockOneInfoCheck($con);
		if (empty($blockOne)) {
			$result->msg 		= "获取失败";
			$result->code 		= -100;
			return $result;
		}
		$delInfo = org_api::DeleteBlock($con);
		if(isset($delInfo->result->code)&&$delInfo->result->code >0){
			$con['type'] 		= 2;
			$channelBannerInfo  = org_api::getBannerInfo($con);
			if(!empty($channelBannerInfo)){
				$delData 		= org_api::delChannelBannerMore($con);
			}
			$result->msg 		= "删除成功";
			$result->code   	= 200;
			return $result;
		}else{
			$result->msg    	= "删除失败";
			$result->code   	= -100;
			return $result;
		}
	}
    //添加频道模块
	public function pageiframeBlockChannel($inPath){
		$orgProfile = user_organization::orgAboutProfileInfo($this->orgOwner);
		if (!empty($orgProfile->scopes)) {
			$firstCateIdStr = $orgProfile->scopes;
		} else {
			$firstCateIdStr = 1;
		}
		$firstCateList = array();
		$firstCateList = course_api::getCateByCidStr($firstCateIdStr);
		if (count($firstCateList) == 1) {
			$secondCateList = course_api::getNodeCate($firstCateList[0]->pk_cate);
			$this->assign('secondCateList', $secondCateList);
		}
		if (!empty($inPath[3])&&$inPath[3]=='edit') {
			
			$act = 'edit';
            $condition = array();
            $condition['pk_block'] = $inPath[4];
            $condition['fk_channel'] = $inPath[5];
			$blockCheck = org_api::getBlockOneInfoCheck($condition);
			$recommend = $blockCheck->recommend;
			if (!empty($blockCheck->query_arr->attr_value_id)) {
				$blockCheck->query_arr->attr_value_id = explode('|', $blockCheck->query_arr->attr_value_id);
				$this->assign('attrValueIds', json_encode($blockCheck->query_arr->attr_value_id));
			}
			if (!empty($secondCateList) && !empty($blockCheck->query_arr->second_cate)) {
				$thirdCateList = course_api::getNodeCate($blockCheck->query_arr->second_cate);
				$this->assign('thirdCateList', $thirdCateList);
			}
			if (!empty($thirdCateList) && !empty($blockCheck->query_arr->third_cate)) {
				$attrRet = course_api::getAttrAndValueByCateId($blockCheck->query_arr->third_cate);
				foreach ($attrRet as $ro) {
					if ($ro->name_display == '科目') {
						$attrList = $ro;
						foreach ($attrList->attr_value as &$ao) {
							if (!empty($blockCheck->query_arr->attr_value_id)) {
								if (in_array($ao->attr_value_id, $blockCheck->query_arr->attr_value_id)) {
									$ao->check = 'checked';
								} else {
									$ao->check = '';
								}
							} else {
								$ao->check = '';
							}
						}
						break;
					}
				}
				$this->assign('attrList', $attrList);
			}
            $this->assign('channel_id', $inPath[5]);
            $this->assign('block_id', $inPath[4]);
			$this->assign('template', $blockCheck);
		} elseif(!empty($inPath[3])&&$inPath[3]=='add') {
			$recommend = 1;
			$act = 'add';
            $this->assign('channel_id', $inPath[4]);
		}
		$this->assign('firstCateList', $firstCateList);
		$this->assign('firstCount', count($firstCateList));
		$this->assign('act', $act);
		$this->assign('recommend', $recommend);
		return $this->render('org/iframe.channel.html');
	}
	public function pageiframeChannelAddImg($inPath){
		$data			  	= array();
		$data['fk_channel'] = !empty($inPath[5]) ? $inPath[5] : 0;
		$data['pk_block']   = !empty($inPath[3]) ? substr($inPath[3], 5) : 0;
		$blockCheck         = org_api::getBlockOneInfoCheck($data);
		$this->assign("tinfo", $blockCheck);
		$this->assign("channel_id", !empty($inPath[5]) ? $inPath[5] : 0);
		return $this->render('org/iframe.channel.addimg.html');
	}
	/*频道推荐图*/
	public function pageiframeChannelAddRecommend($inPath){
		$data      			= array();
		$data['pk_block'] 	= !empty($inPath[4]) ? $inPath[4] : 0;
		$data['fk_channel'] = !empty($inPath[3]) ? $inPath[3] : 0;
		$bannerId			= !empty($inPath[8]) ? $inPath[8] : 0;
		$blockCheck         = org_api::getBlockOneInfoCheck($data);
		$con 				= array();
		$con['pk_banner']	= $bannerId;
		$channelInfo  		= org_api::getBannerInfo($con);
		$this->assign("tinfo", $blockCheck);
		$this->assign("channelInfo", $channelInfo);
		$this->assign("banner_id", $bannerId);
		$this->assign("channel_id", $data['fk_channel']);
		return $this->render('org/iframe.channel.thumb.html');
	}
	/*频道课程添加*/
	public function pageIframeChannelBlockCourse($inPath){
		$channelId		= !empty($inPath[3]) ? $inPath[3] : 0;
		$blockId		= !empty($inPath[4]) ? $inPath[4] : 0;
		$page 			= isset($_GET['page']) ? $_GET['page']:1;
		$length  		= 50;
		$data 			= array();
		$data["type"] 	= array(1,2,3);//全部课程
		$data["admin_status"] = "1";
		$data["shelf"] 		  = 1;
		$data["create_time"]  = "desc";
		$courseRet			  = course_api::getCourselistByOid($page, $length, $this->orgOwner, $data);
		$courseList 		  = !empty($courseRet->data) ? $courseRet->data : '';
		$this->assign('channel_id', $channelId);
		$this->assign('bid', isset($inPath[7]) ? $inPath[7] : 0);
		$this->assign('courseList', $courseList);
		$this->assign('subType', !empty($inPath[5]) ? $inPath[5] : '');
		//分销课程获取
		$condition = array("con" => "t_course_promote.ver=t_course_resell.ver AND   t_course_promote.status=1  AND t_course_resell.status=1", "fk_org_resell" => $this->orgInfo->oid);
		$salesInfo = course_resell_api::getSalesCourse($page, $length, $condition);
		if (!empty($salesInfo)) {
			foreach ($salesInfo as $k => $v) {
				$course[$k] = $v->fk_course;
			}
			$idStr = implode(",", $course);
			$fields = array("course_id", "org_subname", "title", "create_time", "desc", "start_time", "thumb_big",
				"thumb_med", "thumb_sma", "is_promote", "org_id",
			);
			$query = [
				'course_id' => $idStr,
			];
			$params = [
				"f" => $fields,
				"q" => $query,
				"p" => $page,
				"pl" => $length,
				"ob" => 'desc'
			];
			$resCourse = seek_api::seekcourse($params);
		}
		$salseList = !empty($resCourse->data) ? $resCourse->data : '';
		$this->assign('salseList', $salseList);
		$this->assign('bid', $blockId);
		$this->render('org/iframe.channelcouse.html');
	}
	/*频道课程修改*/
	public function pageIframeChannelBlockCourseEdit($inPath){
		$channelId		= $inPath[3];
		$blockId		= $inPath[4];
		$page 			= isset($_GET['page']) ? $_GET['page']:1;
		$length  		= 200;
		$data 			= array();
		$data["type"] 	= array(1,2,3);//全部课程
		$data["admin_status"] = "1";
		$data["shelf"] 		  = 1;
		$data["create_time"]  = "desc";
		$courseRet			  = course_api::getCourselistByOid($page, $length, $this->orgOwner, $data);
		$courseList 		  = !empty($courseRet->data) ? $courseRet->data : '';
		$this->assign('channel_id', $channelId);
		$this->assign('bid', isset($inPath[7]) ? $inPath[7] : 0);
		$this->assign('courseList', $courseList);
		$this->assign('subType', !empty($inPath[5]) ? $inPath[5] : '');
		//分销课程获取
		$condition = array("con" => "t_course_promote.ver=t_course_resell.ver AND   t_course_promote.status=1  AND t_course_resell.status=1", "fk_org_resell" => $this->orgInfo->oid);
		$salesInfo = course_resell_api::getSalesCourse($page, $length, $condition);
		if (!empty($salesInfo)) {
			foreach ($salesInfo as $k => $v) {
				$course[$k] = $v->fk_course;
			}
			$idStr = implode(",", $course);
			$fields = array("course_id", "org_subname", "title", "create_time", "desc", "start_time", "thumb_big",
				"thumb_med", "thumb_sma", "is_promote", "org_id",
			);
			$query = [
				'course_id' => $idStr,
			];
			$params = [
				"f" => $fields,
				"q" => $query,
				"p" => $page,
				"pl" => $length,
				"ob" => 'desc'
			];
			$resCourse = seek_api::seekcourse($params);
		}
		$salseList = !empty($resCourse->data) ? $resCourse->data : '';
		$this->assign('salseList', $salseList);
		$this->assign('tid', $blockId);
		$this->assign('bid', isset($inPath[7]) ? $inPath[7] : 0);
		$this->render('org/iframe.channelcouse.edit.html');
	}
	//添加推荐图
	public function pageupdateChannelRecommendPic($inPath){
		$result = new stdClass;
		$path = ROOT_WWW . "/upload/tmp";
		$thumb = !empty($_POST['pic_url']) ? $_POST['pic_url'] : '';
		if (!empty($thumb)) {
			preg_match("/^[a-zA-z]+:\/\/[\w.]+(yunke.com|gn100.com)+[^\s]*$/", $thumb, $matches);
			if (empty($matches[0])) {
				$result->code = -8;
				$result->error = "请使用本机构或云课链接地址~";
				//return $result;
				return $this->setResult("",$result->code,$result->error);
			}
		}
	    $filename = $path."/".$this->user['uid'].".org.jpg";
        if(is_file($filename)&&!empty($_REQUEST['w'])){
            list($width, $height, $type, $attr) = getimagesize($filename);
            $filename_dst = $path."/".$this->user['uid'].".dst.png";
            $targ_w = $_REQUEST['w'];
            $targ_h = $_REQUEST['h'];
            switch($type){
            case 1: $img_r = imagecreatefromgif($filename);break;
            case 2: $img_r = imagecreatefromjpeg($filename);break;
            default:
                $img_r = imagecreatefrompng($filename);
            }
            $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
            imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_REQUEST['w'],$_REQUEST['h']);
            $r = imagejpeg($dst_r, $filename_dst);
            if($r){
                $thumbnail = new SThumbnail($filename_dst, 285);
                $thumbnail->setMaxSize(285, 152);
                $filename_180 = utility_file::tempname("thumb");
                if($thumbnail->genFile($filename_180)===false){
                    $result->error = "不是有效果的图片";
                    return $result;
                };
                $file = utility_file::instance();
                $r1 = $file->upload($filename_180,user_api::getLoginUid(),"image","");
                if(empty($r1)){
                    $result->error = "图片处理失败";
                    return $result;
                }
                //图片尺寸都是一样的
                $_POST['thumb_small']=$r1->fid;
                $_POST['thumb_med']=$r1->fid;
                $_POST['thumb_big']=$r1->fid;
                unlink($filename);
                unlink($filename_dst);
                unlink($filename_180);
            }
        }
		
		$data 				= array();
		$data['fk_channel'] = !empty($_POST['channel_id']) ? $_POST['channel_id'] : 0;
		$data['fk_block']   = !empty($_POST['tpic']) ? $_POST['tpic'] : 0;
		$data['fk_user'] 	= !empty($this->orgOwner) ? $this->orgOwner : 0;
		$data['fk_org'] 	= !empty($this->orgInfo->oid) ? $this->orgInfo->oid : 0;
		$data['thumb']		= !empty($_POST['thumb_big']) ? $_POST['thumb_big'] : '';
		$data['url']		= !empty($_POST['pic_url']) ? $_POST['pic_url'] : '';
		$data['type'] 		= !empty($_POST['thumbType']) ? $_POST['thumbType'] : 2;
		$bannerId			= !empty($_POST['banner_id']) ? $_POST['banner_id'] : '';
		if(!empty($bannerId)){
			$info 			= org_api::updateBanner($bannerId,$data);
		}else{
			$info 			= org_api::addChannelBanner($data);
		}
		return $this->setResult($info->result->data,$info->result->code,$info->result->msg);
		
	}

}
