<?php
class student_fav extends STpl
{
	private $user,$domain,$orgOwner;
	function __construct()
	{
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
		$this->assign('domain', $this->domain);

		$this->user = user_api::loginedUser();
		if(empty($this->user))
		{
			if(!empty($_SERVER['REQUEST_URI']))
			{
				$this->redirect("/site.main.login?url=".$_SERVER['REQUEST_URI']);
			}
			else
			{
				$this->redirect("/site.main.login");
			}
		}
        $org=user_organization::subdomain();
        if(!empty($org))
		{
            $this->orgOwner=$org->userId;
        }
		else
		{
            header('Location: https://www.'.$this->domain);
        }
	}

	public function pageMyfav()
	{
		$owner_id = $this->orgOwner;
		$uid = $this->user['uid'];
		//该学生所喜欢的课程
        $res_fav = user_api::listfav2(array('uid'=>$uid),1,100000);
		$courseArr  = array();
        $courseInfo = array();
		if(!empty($res_fav))
		{
		    foreach($res_fav as $v)
			{
				$courseArr[] = $v->course_id;
			}
		}

		if(!empty($courseArr))
		{
			$courseInfo = $this->getMyFavOfCourse($courseArr);
		}
		$this->assign('courseInfo', $courseInfo);
		$this->render('student/my.fav.html');
	}

	public function pageFavCourseAjax()
    {
        $page = !empty($_POST['page'])?$_POST['page']:1;
        $uid = $this->user['uid'];
        $res_fav = user_api::listfav2(array('uid'=>$uid),1,100000);
		$courseArr  = array();
		if(!empty($res_fav))
		{
		    foreach($res_fav as $v)
			{
				$courseArr[] = $v->course_id;
			}
		}
        $courseFav = $this->getFavCourse($uid,$courseArr,$page,15);
		$courseFav = array_values($courseFav);
        if(!empty($courseFav))
        {
            return $this->setAjaxResult(0,'success',$courseFav);
        }else
        {
            return $this->setAjaxResult(-2,'not find data');
        }
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

	 /*
	  * 获取课程信息
	  * @params $uid 学生id $owner_id 机构id $courseArr $page $length
	  * @author zhengtianlong
	  */
	  private function getFavCourse($uid,$owner_id,$courseArr,$page=1,$length=15)
	  {
		$params = [
			'q'  => ['course_id'=> implode(',', $courseArr),'user_id'=>$owner_id],
			'f'  => [ "course_id","title","thumb_med","admin_status","course_type",
					  "user_id","fee_type","price","status","start_time",
					  "end_time","comment","third_cate_name","course_attr","org_subname","subdomain"
					],
			'p'  => $page,
			'ob' => ['start_time'=>'desc'],
			'pl' => $length
		 ];
		$ret_seek = course_api::seekcourse($params);
        $data = array();
		if(!empty($ret_seek->data))
		{
			
				$data['favCount'] = $ret_seek->total;
				$str = '';
				foreach($ret_seek->data as $val)
				{
					$data['data'][$val->course_id] = [
						'course_id'     => $val->course_id,
						'course_type'     => $val->course_type,
						'title'         => $val->title,
						'thumb_med'     => utility_cdn::file($val->thumb_med),
						'start_time'    => date('m月 H:i',strtotime($val->start_time)),
						'subject_name'  => teacher_api::getSubjectNameV2($val->course_attr),
						'cate_name'     => $val->third_cate_name,
						'score'         => '',
						'commNum'       => '',
						'scoreType'     => '',
						//'section_count' => count($val->section_id)
					];
					
//					$score = $this->getScore($val->course_id);
					$score = $this->courseScore($val->course_id);//获取平均分
					for($i=1;$i<=5;$i++)
					{
						if($score>0 && $i<=$score)
						{
							$str.="<dd class='collect-pingfen-solid'></dd>";
						}else
						{
							$str.="<dd></dd>";
						}
						
					}
					$data['data'][$val->course_id]['scoreType'] = $str;
					$data['data'][$val->course_id]['score'] = "<span class='c-fr fs14 collect-pf'>".$score." ".SLanguage::tr('分','LearningCenter')."</span>";
					$str = '';
					$data['data'][$val->course_id]['course_url'] = '/course.info.show/'.$val->course_id;
					if($val->comment>0)
					{
						$data['data'][$val->course_id]['commNum'] = "<div class='col-sm-18  col-xs-12 collect-pl tar col-lg-6 pd0 c-fl ml20'><a href='".$data['data'][$val->course_id]['course_url']."'>".$val->comment." ".SLanguage::tr('个评论','LearningCenter')."</a></div>";
					}
					$data['data'][$val->course_id]['subdomain'] = !empty($val->org_subname) ? $val->org_subname : '';
					if( $val->fee_type == '' ||$val->fee_type == 0 )
					{
						$val->fee_type = 0;
						$data['data'][$val->course_id]['fee_type'] = SLanguage::tr(course_status::$feeType[$val->fee_type],"LearningCenter");
						$data['data'][$val->course_id]['fee_class'] = 'col-sm-3 col-xs-5 c-fl cGreen fs16 mt30';
					}else
					{
						$data['data'][$val->course_id]['fee_type'] = '￥'.$val->price/100;
						$data['data'][$val->course_id]['fee_class'] = 'col-sm-3 col-xs-5 c-fl fs16 cRed mt30';
					}
					
					if($val->admin_status == 1)
					{
						$data['data'][$val->course_id]['admin_status'] = '';
					}else
					{
						$data['data'][$val->course_id]['admin_status'] = '该课程已下架';
					}
				}
			
		}
		return $data;
	  }


		public function courseScore($cid){
			$ret = comment_api::courseAverage($cid);
			$score = 0;
			if($ret){
				if(empty($ret->total_user)){ 
					$score = 0;
				}else{
					$score = round($ret->score/$ret->total_user);
				}
			} 	
			return $score;
		}

	 /*
	  * 机构名称
	  * @params $uidArr
	  * @author zhengtianlong
	  */
	  private function getDomain($uidArr)
	  {
		if(empty($uidArr)) return false;

		$sub_ret = user_api::getSubdomainByUidArr($uidArr);
		$org_ret = user_organization::getOrgInfoByUidArr($uidArr);
        $data = array();
		if(!empty($org_ret))
		{
			foreach($org_ret as $ov)
			{
				$data[$ov->user_owner] = [
					'name'    => $ov->name,
					'subname' => $ov->subname
				];
			}
		}
		
		if(!empty($sub_ret->result->data->items))
		{
			foreach($sub_ret->result->data->items as $val)
			{
				$data[$val->fk_user]['subdomain'] = $val->subdomain;
			}
		}
		return $data;
	  }

	 /*
	  * 评分
	  * @author zhengtianlong
	  */
	  private function getScore($cid)
	  {
		$res = comment_api::getTotal(array('course_id'=>$cid));
		$avg_score = 0;
		if(!empty($res))
		{
			$avg_score = (int)ceil($res[0]->avg_score/$res[0]->total_user);
		}
		return $avg_score;
	  }
	  
	  /*
	 * 取消收藏
	 * @author zhengtianlong
	 */
	public function pageDelFav()
    {
        $result = new stdclass;
        if (empty($_POST['cid'])) 
		{
            $result->error = "参数错误";
            return $result;
        }
		
        $param = array(
            'uid' => $this->user['uid'],
            'cid' => $_POST['cid']
        );
		
        $res = user_api::delFav($param);

        if (!empty($res) && $res->code == 0) 
		{
            return true;
        } else 
		{
            $result->error = "删除失败";
            return $result;
        }
    }
	
	/*
	  * 获取收藏的课程信息
	  * @params array $courseArr
	  * @return array $data
	  */
	  private function getMyFavOfCourse($courseArr,$page=1,$length=15)
	  {
		$params = [
			'q'  => ['course_id'=> implode(',', $courseArr)],
			'f'  => [ "course_id","title","thumb_med","admin_status","course_type","user_id","fee_type","price","status","start_time","end_time","comment","third_cate_name","course_attr","org_subname","subdomain"
					],
			'p'  => $page,
			'ob' => ['start_time'=>'desc'],
			'pl' => $length
		 ];
		$ret_seek = course_api::seekcourse($params);
        $data = array();
		if(!empty($ret_seek->data))
		{
				$data['favCount'] = $ret_seek->total;
				$str = '';
				foreach($ret_seek->data as $val)
				{
					$data['data'][$val->course_id] = [
						'course_id'     => $val->course_id,
						'course_type'     => $val->course_type,
						'title'         => $val->title,
						'thumb_med'     => utility_cdn::file($val->thumb_med),
						'start_time'    => date('m月 H:i',strtotime($val->start_time)),
						'subject_name'  => teacher_api::getSubjectNameV2($val->course_attr),
						'cate_name'     => $val->third_cate_name,
						'score'         => '',
						'commNum'       => '',
						'scoreType'     => ''
					];
					
					$score = $this->courseScore($val->course_id);//获取平均分
					for($i=1;$i<=5;$i++)
					{
						if($score>0 && $i<=$score)
						{
							$str.="<dd class='collect-pingfen-solid'></dd>";
						}else
						{
							$str.="<dd></dd>";
						}
						
					}
					$data['data'][$val->course_id]['scoreType'] = $str;
					$data['data'][$val->course_id]['score'] = "<span class='c-fr fs14 collect-pf'>".$score." ".SLanguage::tr('分','LearningCenter')."</span>";
					$str = '';
					$data['data'][$val->course_id]['course_url'] = '/course.info.show/'.$val->course_id;
					if($val->comment>0)
					{
						$data['data'][$val->course_id]['commNum'] = "<div class='col-sm-18  col-xs-12 collect-pl tar col-lg-6 pd0 c-fl ml20 mt5'><a href='".$data['data'][$val->course_id]['course_url']."'>".$val->comment." ".SLanguage::tr('个评论','LearningCenter')."</a></div>";
					}
					$data['data'][$val->course_id]['subdomain'] = !empty($val->subdomain) ? $val->subdomain : '';
					if( $val->fee_type == '' ||$val->fee_type == 0 )
					{
						$val->fee_type = 0;
						$data['data'][$val->course_id]['fee_type'] = SLanguage::tr(course_status::$feeType[$val->fee_type],"LearningCenter");
						$data['data'][$val->course_id]['fee_class'] = 'col-sm-3 col-xs-5 c-fl cGreen fs16 mt30';
					}else
					{
						$data['data'][$val->course_id]['fee_type'] = '￥'.$val->price/100;
						$data['data'][$val->course_id]['fee_class'] = 'col-sm-3 col-xs-5 c-fl fs16 cRed mt30';
					}
					
					if($val->admin_status == 1)
					{
						$data['data'][$val->course_id]['admin_status'] = '';
					}else
					{
						$data['data'][$val->course_id]['admin_status'] = '该课程已下架';
					}
				}
		}
					return $data;
	  }

}

?>
