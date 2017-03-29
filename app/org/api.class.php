<?php

class org_api
{
	public static function setResult($data = '', $code = 0, $msg = ''){
		$ret = new stdclass;
		$ret->result = new stdclass;
		$ret->result->code = $code;
		$ret->result->data = $data;
		$ret->result->msg = $msg;
		return $ret;
	}
	public static function voteList($page,$length,$param=''){
		$ret = utility_services::call("/poll/vote/voteList/".$page."/".$length, $param);
		return $ret;
	}
	
	public static function voteOne($voteId){
		$ret = utility_services::call("/poll/vote/voteOne/".$voteId);
		return $ret;
	}
	
	public static function voteAdd($param){
		$ret = utility_services::call("/poll/vote/voteAdd/",$param);
		return $ret;
	}
	
	public static function optionAdd($param){
		$ret = utility_services::call("/poll/vote/optionAdd/",$param);
		return $ret;
	}
	
	public static function optionList($voteId){
		$ret = utility_services::call("/poll/vote/optioByVoteId/".$voteId);
		return $ret;
	}
	
	public static function userLogAdd($param){
		$ret = utility_services::call("/poll/vote/userLogAdd/",$param);
		return $ret;
	}
	
	public static function voteUpdate($param){
		$ret = utility_services::call("/poll/vote/voteUpdate/",$param);
		return $ret;
	}
	
	public static function userLogList($param,$page='',$length=''){
		$ret = utility_services::call("/poll/vote/userLogList/".$page."/".$length,$param);
		return $ret;
	}
	
	public static function customerServicesQqList($orgId){
		$ret = utility_services::call("/user/organization/customerServicesQqList/".$orgId);
		return $ret;
	}
	public static function addOrgCustomerInfo($param){
		$ret = utility_services::call("/user/organization/addOrgCustomerInfo/",$param);
		return $ret;
	}
	public static function updateOrgCustomerInfo($tid,$param){
		$ret = utility_services::call("/user/organization/updateOrgCustomerInfo/".$tid,$param);
		return $ret;
	}
	public static function delOrgCustomerInfo($tid){
		$ret = utility_services::call("/user/organization/delOrgCustomerInfo/".$tid);
		return $ret;
	}
	public static function getOrgCustomerInfo($param){
		$ret = utility_services::call("/user/organization/getOrgCustomerInfo/",$param);
		return $ret;
	}
    public static function getOrgTemplateMaxSort($owner){
        $ret=utility_services::call("/user/organization/getOrgTemplateMaxSort/$owner");
        if (!empty($ret->data)) {
            return $ret->data->sort;
        }
        return 0;
    }
	public static function getOrgCourseProjectInfo($templatesInfo,$plan1,$planinfo){
			$underCourse = array();
			/*if(!empty($templatesInfo)){
				foreach($templatesInfo as $x=>$y){
					if($y->course_type=='3'){
						$underCourse[$y->course_id] = $y;
					}
				}
			}
			$underCoursePlanInfoTmp = array();
			if(!empty($planinfo)){
				foreach($planinfo as $t=>$r){
					if(!empty($underCourse[$t])){
						$underCoursePlanInfoTmp[$t] = $planinfo[$t];
					}	
				}
			}
			*/
			$under = array();
			if(!empty($planinfo)){
				foreach($planinfo as $k=>$p){
					foreach($p as $j){
						foreach($j as $v){
							if($v->course_type=='3'){
								if(strtotime($v->start_time) >time()){
									$under[$k]['section_name'] = $v->section_name;
									$under[$k]['class_name'] = $v->class_name;
									$under[$k]['start_time'] = $v->start_time;
									break;
								}else{
									$under[$k]['section_name'] = "";
									$under[$k]['class_name'] = $v->class_name;
									$under[$k]['start_time'] = "已完结";
									
								}
							}
								
						}
					}
				}
			}
			foreach($plan1 as $k=>$v){
				foreach($v as $m=>$n){
						if(!empty($v[$m][3])){
							$lastSection = end($v[$m][3]);
							if(!empty($v[$m][1])){
								foreach($v[$m][1] as $a=>$b){			
									if(strtotime($lastSection->start_time) < strtotime($b->start_time)){
										$b->section_name = $b->section_name;
										if(!empty($v[$m][1][0])){
											$v[$m][1][0]->section_name = $b->section_name; 
											$v[$m][1][0]->start_time = $b->start_time;
										}
										break;
									}else{
										if(!empty($v[$m][1][0])){
											$v[$m][1][0]->section_name = '';
											$v[$m][1][0]->start_time = "待定";
										}
									}
								}
							}else{
								$v[$m][3][0]->section_name = "";
								$v[$m][3][0]->start_time = "课程完结";
							}
							
						}else{
							if(!empty($v[$m][1])){
								$lastSection = end($v[$m][1]);
								if(time() > strtotime($lastSection->start_time)){
									$v[$m][1][0]->section_name = $lastSection->section_name; 
									$v[$m][1][0]->start_time = $lastSection->start_time;
                                    if(!empty($under[$k])){
                                        $v[$m][1][0]->start_time = isset($under[$k]['start_time']) ? $under[$k]['start_time'] : '';
                                    }  
								}else{
									$lastSection = $v[$m][1][0];
									$v[$m][1][0]->section_name = $lastSection->section_name;
									$v[$m][1][0]->start_time = $lastSection->start_time;
                                    if(!empty($under[$k])){
                                        $v[$m][1][0]->section_name = isset($under[$k]['section_name']) ? $under[$k]['section_name'] : '';
                                        $v[$m][1][0]->start_time = isset($under[$k]['start_time']) ? $under[$k]['start_time'] : '';
                                        
                                    }
								}	
							}	
						}				
				}
			}
            $plan=array();
            if(!empty($plan1)){
                foreach($plan1 as $k=>$v){
                    $arr=array_chunk($v,2,true);
                    $plan[$k]=$arr[0]; 
                }
            }
			return $plan;
	}
	
	public static function addMsgTask($param){
		$ret = utility_services::call("/poll/vote/addMsgTask/",$param);
		return $ret;
	}
	public static function ajaxAddNoticeCategory($param){
		$ret = utility_services::call("/user/organization/ajaxAddNoticeCategory/",$param);
		return $ret;
	}
	public static function noticeCategoryList($param){
		$ret = utility_services::call("/user/organization/noticeCategoryList/",$param);
		return $ret;
	}
	public static function getCateNameInfo($param){
		$ret = utility_services::call("/user/organization/getCateNameInfo/",$param);
		return $ret;
	}
	public static function updateNoticeCate($param){
		$ret = utility_services::call("/user/organization/updateNoticeCate/",$param);
		return $ret;
	}
	public static function delnoticeCateInfo($param){
		$ret = utility_services::call("/user/organization/delnoticeCateInfo/",$param);
		return $ret;
	}
	public static function getOrgTagInfo($param){
		$ret = utility_services::call("/user/organization/getOrgTagInfo/",$param);
		return $ret;
	}
	public static function addOrgTagAjax($param){
		$ret = utility_services::call("/user/organizationTag/update/",$param);
		return $ret;
	}
	public static function addCourseTagBelongGroup($param){
		$ret = utility_services::call("/user/organizationTag/addCourseTagBelongGroup/",$param);
		return $ret;
	}
	public static function delMappingPlanByGidAndTidArr($param){
		$ret = utility_services::call("/user/organizationTag/delMappingPlanByGidAndTidArr/",$param);
		return $ret;
	}
	public static function getClearByOrgId($param,$page,$length){
		$ret = utility_services::call("/user/organizationAccountClearing/getClearByOrgId/".$page."/".$length,$param);
		return $ret;
	}
	public static function getClearByClaerId($clearId,$orgId){
		$ret = utility_services::call("/user/organizationAccountClearing/getClearByClaerId/".$clearId."/".$orgId);
		return $ret;
	}
	public static function getUserOrderContentList($param,$page='',$length=''){
		$ret = utility_services::call("/user/orgAccountOrderContent/getOrderContentList/".$page."/".$length,$param);
		return $ret;
	}
	public static function getOrderInfo($param,$page=1,$length=10){
		$ret = utility_services::call("/order/orderContent/orderInfo/".$page."/".$length,$param);
		return $ret;
	}
	public static function getorgmembersets($param){
		$ret = utility_services::call("/user/orgMemberSet/getorgmembersets/",$param);
		return $ret;
	}
	
	public static function getOrderList($params,$page=1,$length=10)
	{
		$data = array();
		if(!empty($params['orgId'])){
			$data['orgId'] = (int)$params['orgId'];
		}
		if(!empty($params['orderSn'])){
			$data['orderSn'] = $params['orderSn'];
		}
		if(!empty($params['price'])){
			$data['price'] = explode(',',$params['price']);
		}
		if(!empty($params['time'])){
			$data['time'] = explode(',',$params['time']);
		}
		if(!empty($params['isFavorable'])){
			$data['isFavorable'] = (int)$params['isFavorable'];
		}
		if(!empty($params['status'])){
			$data['status'] = (int)$params['status'];
		}
		if(!empty($params['userId'])){
			$data['userId'] = (int)$params['userId'];
		}
        if(!empty($params['orderType'])){
            $data['orderType'] = (int)$params['orderType'];
        }
		
		if(empty($data)) return false;
		
		$ret = utility_services::call("/order/info/list/".$page."/".$length,$data);
		return $ret;
	}
	public static function getOrgInfo($oid){
		$ret = utility_services::call("/user/organization/getOrgNameInfo/$oid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function woMenu(){
		 $cateConf 		= 	SConfig::getConfig(ROOT_CONFIG."/wo.left.menu.conf","menu");
		 $menu 			=	!empty($cateConf->menu) ? $cateConf->menu : '';
		 return $menu;
	 }
	//查看投票人数
	public static function getTenderer($params, $page, $length)
	{
		$option = array();
		if(!empty($params['voteId'])){
			$option['voteId'] = (int)$params['voteId'];
		}
		if(!empty($params['userId'])){
			$option['userId'] = (int)$params['userId'];
		}
		if(empty($option)) return $option;
		$logUserRes = utility_services::call("/poll/vote/userLogList/".$page."/".$length, $option);
		if(empty($logUserRes->items)) return array();
		
		$userIdArr = array();
		$voteTime  = array();
		foreach($logUserRes->items as $val){
			$userIdArr[$val->fk_user] = $val->fk_user;
			$voteTime[$val->fk_user]  = $val->last_updated;
		}
		
		//投票选项信息
		$resOption = org_api::optionList($option['voteId']);
		$orderNo = array();
		if(!empty($resOption->items)){
			foreach($resOption->items as $v){
				$orderNo[$v->pk_option] = $v->order_no;
			}
		}
		$userOption = array();
		if(!empty($orderNo)){
			foreach($logUserRes->items as $v){
				$userOption[$v->fk_user][] = $orderNo[$v->fk_option];
			}
		}
		$optioinId = array();
        if(!empty($userOption)){
            foreach($userOption as $k=>$v){
                $optionId[$k] = implode(',',$v);
            }
        }
		
		//用户信息
		$userRes = array();
		if(!empty($userIdArr)){
			$userRes = user_api::getUserInfoByUidArr(array('uidArr'=>$userIdArr));
			$shcoolName = array();
			if(!empty($userRes->data)){
				$schoolIdArr = array();
				foreach($userRes->data as $val){
					$schoolIdArr[$val->user_id] = $val->school_id;
				}
				//学校信息
				if(!empty($schoolIdArr)){
					$shcoolRes = region_api::scoolByRegionIdArr(array('regionIdArr'=>$schoolIdArr));
					if(!empty($shcoolRes->data)){
                        foreach($shcoolRes->data as $val){
							$shcoolName[$val->school_id] = $val->school_name;
						}
					}
				}
			}
		}
		
		$data = [
			'page'      => $logUserRes->page,
			'totalPage' => $logUserRes->totalPage,
			'totalSize' => $logUserRes->totalSize
		];
		//拼装数据
		foreach($userRes->data as $v){
			$data['data'][$v->user_id] = [
				'gender'   => ($v->gender==1) ? '男' : '女',
				'name'     => !empty($v->real_name) ? $v->real_name : (!empty($v->name) ? $v->name : ''),
				'thumbMed' => !empty($v->thumb_med) ? utility_cdn::file($v->thumb_med) : '',
				'school'   => !empty($shcoolName[$v->school_id]) ? $shcoolName[$v->school_id] : '',
				'age'      => !empty($v->birthday) ? utility_tool::getAge($v->birthday) : '',
				'optionId' => !empty($optionId[$v->user_id]) ? $optionId[$v->user_id] : '',
				'voteTime' => !empty($voteTime[$v->user_id]) ? date('y-m-d H:i',strtotime($voteTime[$v->user_id])) : '',
				'mobile'   => !empty($v->mobile) ? $v->mobile : '',
			];
		}
		
		return $data;
	}
	public static function getorgCateInfo($oid,$ownerId,$cateId=0){
		$result 		= new stdclass;
		$data['fk_org'] = !empty($oid) ? (int)$oid : 0;
		$catId			= !empty($cateId) ? $cateId : 0;
		$info 			= user_organization::getOrgCustomerCateList($data);
		$cateId			= !empty($info->cate_id) ? $info->cate_id : 0;
		$dataInfo 	  	= course_api::getCateByCidStr($cateId);
		$result->code 	= 200;
		$result->data 	= !empty($dataInfo) ? $dataInfo : '';
		return $result;
	}
	public static function getOrgCustomerCateList($oid,$ownerId,$cateId=0){
		$result 		= new stdclass;
		$data['fk_org'] = !empty($oid) ? (int)$oid : 0;
		$catId			= !empty($cateId) ? $cateId : 0;
		$info 			= user_organization::getOrgCustomerCateList($data);
		$cateInfo 		= course_api::getOrgValidCateIds($ownerId);
		$orgProfileInfo = user_organization::GetOrgProfileInfo($oid);
		//取机构设置的一级分类
		$scopes = explode(",",$orgProfileInfo['scopes']);
		$orgcate = array();
		$secondedArr = array();
		$cateFirst = array();
		foreach($cateInfo->lists as $k=>$v){
				if(!in_array($k,$scopes)){
					unset($cateInfo->lists->$k);
				    continue;
				} 
				$secondedArr[$k]=$k;
				foreach($v as $a=>$b){
					$secondedArr[$a] = $a;
				}
				$cateFirst[] = $k;
		}
		$diffArray = array_diff($scopes,$cateFirst);
		if($diffArray){
			foreach($diffArray as $v){
				$cateInfo->lists->$v=array();
			}
		}
		$cateName = '';
		if(!empty($secondedArr)){
			$secondInfo   = empty($diffArray) ? implode(",",$secondedArr):implode(",",array_merge($secondedArr,$diffArray));
			$cateName 	  = course_api::getCateByCidStr($secondInfo);
			foreach($cateName as $k=>$v){
				$cArr[$v->pk_cate] = $v;
			}
		}
		foreach($cateName as $k=>$v){
				$cArr[$v->pk_cate] = $v;
		}
		foreach($cateInfo->lists as $m=>$n){
			$orgcate[$m]['id'] = !empty($cArr[$m]->pk_cate) ? $cArr[$m]->pk_cate : '';
			$orgcate[$m]['name'] = !empty($cArr[$m]->name) ? $cArr[$m]->name : '';
			if($n){
				foreach($n as $x=>$y){
					$orgcate[$m]['seconde'][$x]['id']	 = !empty($cArr[$x]->pk_cate) ? $cArr[$x]->pk_cate : '';
					$orgcate[$m]['seconde'][$x]['name']  = !empty($cArr[$x]->name) ? $cArr[$x]->name : '';
				}
				$orgcate[$m]['id']  = !empty($cArr[$m]->pk_cate) ? $cArr[$m]->pk_cate : '';
				$orgcate[$m]['name']  = !empty($cArr[$m]->name) ? $cArr[$m]->name : '';
			}
		}
		$result->code = 200;
		$result->data = !empty($orgcate) ? $orgcate : '';
		return $result;
		
		
	}
	//增加分类
	public static function addCustomerCate($data){
		$info 				= array();
		$info['cate_id']    = !empty($data['cate_id']) ? $data['cate_id'] : '';
		$info['fk_org']  	= !empty($data['cate_id']) ? (int)$data['fk_org'] : 0;
		$result 			= user_organization::addCustomerCate($info);
		return !empty($result->result->code)&&$result->result->code > 0 ? self::setResult('', 200, "操作成功") : self::setResult('', -200, "操作失败");
	}
	
	//小沃  自定义导航
	public static function addNav($param){
		$ret = utility_services::call("/customnav/custom/addCustomNav/",$param);
		return $ret;
	}
	//修改  ModNav 
	
	public static function ModNav($param){
		$ret = utility_services::call("/customnav/custom/ModNav/",$param);
		return $ret;
	}

	//删除导航
	public static function delNav($param){
		$ret = utility_services::call("/customnav/custom/delNav/",$param);
		return $ret;
	}
	
	//SelNav
	public static function SelNav($param){
		$ret = utility_services::call("/customnav/custom/SelNav/",$param);
		return $ret;
	}
	//频道列表
	public static function channelList($oid){
		$ret = utility_services::call("/user/orgSetting/channelList/$oid");
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function addchannel($params){
		$ret = utility_services::call("/user/orgSetting/addchannel/",$params);
		return $ret;
	}
	public static function addChannelBanner($params){
		$ret = utility_services::call("/user/orgSetting/addChannelBanner/",$params);
		return $ret;
	}
	public static function bannerList($params){
		$ret = utility_services::call("/user/orgSetting/bannerList/",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function updateBanner($bid,$params){
		$ret = utility_services::call("/user/orgSetting/updateBanner/$bid",$params);
		return $ret;
	}
	public static function delBanner($bid,$params){
		$ret = utility_services::call("/user/orgSetting/delBanner/$bid",$params);
		return $ret;
	}
	public static function delXiaoWoOrgBanner($bid,$params){
		$ret = utility_services::call("/user/orgSetting/delXiaoWoOrgBanner/$bid",$params);
		return $ret;
	}
	public static function getblockCheck($params){
		$ret = utility_services::call("/user/orgSetting/getblockCheck/",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function addOrgblock($params){
		$ret = utility_services::call("/user/orgSetting/addOrgblock/",$params);
		return $ret;
	}
    public static function getChannelBlockList($params){
		$ret = utility_services::call("/user/orgSetting/getChannelBlockList/",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getPlatformBloclOwer($fk_channel){
		$ret = utility_services::call("/user/orgSetting/getPlatformBloclOwer/$fk_channel");		
		if(!empty($ret)){
			return $ret;
		}
		return false;
	}
	
	public static function getchannelOneInfo($params){
		$ret = utility_services::call("/user/orgSetting/getchannelOneInfo/",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function getBlockOneInfoCheck($params){
		$ret = utility_services::call("/user/orgSetting/getBlockOneInfoCheck/",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function DeleteBlock($params){
		$ret = utility_services::call("/user/orgSetting/DeleteBlock/",$params);
		return $ret;
	}
    public static function deleteChannel($params){
		$ret = utility_services::call("/user/orgSetting/deleteChannel/",$params);
		return $ret;
	}
    public static function deleteOrgBlock($params){
		$ret = utility_services::call("/user/orgSetting/deleteOrgBlock/",$params);
		return $ret;
	}
    public static function deleteBannerAndThumb($params){
		$ret = utility_services::call("/user/orgSetting/deleteBannerAndThumb/",$params);
		return $ret;
	}
	public static function updateOrgblock($params){
		$ret = utility_services::call("/user/orgSetting/updateOrgblock/",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function updateChannelThumbPic($params){
        $ret = utility_services::call("/user/orgSetting/updateChannelThumbPic/",$params);
        if(!empty($ret->result->code)&&$ret->result->code==100){
            return $ret->result->msg;
        }
        return false;
    }
    public static function getOrgChannelBlockInfo($params){
		$ret = utility_services::call("/user/orgSetting/getOrgChannelBlockInfo/",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
    public static function deleteOrgblockInfo($params){
		$ret = utility_services::call("/user/orgSetting/deleteOrgblockInfo/",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	public static function delChannelBannerMore($params){
		$ret = utility_services::call("/user/orgSetting/delChannelBannerMore/",$params);
		if(!empty($ret->result) && $ret->result->code==200){
			return true;
		}
		return false;
	}
    public static function getblockOneInfo($tid,$ownerId){
        $ret = utility_services::call("/user/orgSetting/getblockOneInfo/".$tid,$ownerId);
        if(!empty($ret->data)){
            return $ret->data;
        }
        return false;
    }
	public static function getBannerInfo($params){
        $ret = utility_services::call("/user/orgSetting/getBannerInfo/",$params);
        if(!empty($ret->data)){
            return $ret->data;
        }
        return false;
    }
    public static function addChannelBlockData($params){
        $ret = utility_services::call("/user/orgSetting/addChannelBlockData/",$params);
        if(!empty($ret->result->code)&&$ret->result->code==100){
            return $ret->result->msg;
        }
        return false;
    }
    public static function updateChannelBlockData($params){
        $ret = utility_services::call("/user/orgSetting/updateChannelBlockData/",$params);
        if(!empty($ret->result->code)&&$ret->result->code==100){
            return $ret->result->msg;
        }
        return false;
    }
    
    //客服列表
    public static function customerToolsList($params)
    {
	$ret = utility_services::call("/utility/customService/GetCsList/",$params);
	return $ret;
    }
    
    //添加客服
    public static function addCustomerTools($params)
    {
        $ret = utility_services::call("/utility/customService/AddCs/",$params);
	return $ret;
    }
    
    //get客服详情
    public static function getCustomerToolsInfo($pid,$orgid)
    {
        $ret = utility_services::call("/utility/customService/GetCsDetail/{$pid}/{$orgid}");
	return $ret;
    }
    
    //更新客服
    public static function updateCustomerTools($pid,$params)
    {
        $ret = utility_services::call("/utility/customService/UpdateCs/{$pid}",$params);
	return $ret;
    }
    
    //删除客服
    public static function delCustomerTools($pid,$orgid)
    {
        $ret = utility_services::call("/utility/customService/DelCs/{$pid}/{$orgid}");
	return $ret;
    }
    
    //根据机构或者课程id或者客服列表
    public static function getCusRelationList($params){
        $ret = utility_services::call("/utility/customService/GetCsRelationList/",$params);
	return $ret;
    }
    
    //绑定客服
    public static function bindCustomerInfo($params)
    {
        $ret = utility_services::call("/utility/customService/AddCsRelation/",$params);
	return $ret;
    }
    
    //解除绑定客服
    public static function unbindCustomerInfo($pid)
    {
        $ret = utility_services::call("/utility/customService/DelCsRelation/".$pid);
	return $ret;
    }
    
    public static function updatechannel($params){
        $ret = utility_services::call("/user/orgSetting/updatechannel/",$params);
        if(!empty($ret->result->code)&&$ret->result->code==200){
            return $ret->result->msg;
        }
        return false;
    }
	public static function addTeacherActivity($params){
        $ret = utility_services::call("/user/orgSetting/addTeacherActivity/",$params);
        if(!empty($ret->result->code)&&$ret->result->code==100){
            return $ret->result->msg;
        }
        return false;
    }
	public static function getteacherActivityOneOfInfo($params){ 
        $ret = utility_services::call("/user/orgSetting/getteacherActivityOneOfInfo/",$params);
        if(!empty($ret->data)){
            return $ret->data;
        }
        return false;
    }
	
	public static function setOrganization($data, $orgId, $ownerId=0){
		if(empty($data)) return false;
		$params = array();
		$params['data']['teacher_add_course'] = !empty($data['teacher_add_course']) ? (int)$data['teacher_add_course'] : 0;
		$params['orgId'] = !empty($orgId) ? (int)$orgId : 0;
		$params['ownerId'] = !empty($ownerId) ? (int)$ownerId : 0;
		
		$ret = utility_services::call("user.organization.SetOrganization",$params);
		if(!empty($ret->code)){
			return false;
		}
		return true;
	}
	public static function platformBlockOfData($blockList,$blockArrBlockId){
		$course_info1 	= self::getBlockCourseOrderInfo($blockList,$blockArrBlockId);
		$data 			= array();
		if($course_info1['is_custom']>0){
			foreach($course_info1['sort'] as $v){
				$am[$v->course_id] = $v->sort_id;
			}
			$data['list'] = self::getCourseList($course_info1['query_where'], $course_info1['r_order'],$course_info1['num'],$blockArrBlockId);
			
			foreach($data['list'] as $v){
				$v->sort = !empty($am[$v->courseId]) ? $am[$v->courseId] : '';
			}
			usort(
				$data['list'],
				function ($a, $b) {
					return ($a->sort <= $b->sort) ? -1 : 1;
				}
			);
		}else{
			$data['list'] = self::getCourseList($course_info1['query_where'], $course_info1['r_order'],$course_info1['num'],$blockArrBlockId);
		}
		$attrId 				= array(1=>"小学阶段",5=>"初中阶段",9=>"高中阶段");
		$data['attrName']   	= !empty($attrId[$blockArrBlockId]) ? $attrId[$blockArrBlockId] : '';
		$data['name'] 			= !empty($course_info1['name']) ? $course_info1['name'] : '';
		$data['id']				= $blockArrBlockId;
		$data['sc'] 			= !empty($course_info1['query_where']['second_cate']) ? $course_info1['query_where']['second_cate'] : '';
		return $data;
	}

	

	public static function getBlockCourseOrderInfo($blockList,$block_id){
			$data = array();
			$blockIn = !empty($blockList[$block_id]) ? $blockList[$block_id] : '';
			$data['num']= !empty($blockIn->total_count) ? $blockIn->total_count : '';
			$data['is_custom']= !empty($blockIn->is_custom) ? $blockIn->is_custom : 0;
			$data['name'] = !empty($blockIn->name) ? $blockIn->name : 0;
			if(!empty($blockIn)){
				$order_str= !empty($blockIn->order_str) ? $blockIn->order_str : '';
				$data['order_str_type'] =explode(":",$order_str);
				if(trim($data['order_str_type'][0])=="user_total"){
					$data['r_order']  = array('user_total'=>'desc');
				}elseif(trim($data['order_str_type'][0])=="remain_user"){
					$data['r_order']  = array('remain_user'=>'desc');
				}elseif(trim($data['order_str_type'][0])=="vv"){
					$data['r_order']  = array('vv'=>'desc');
				}else{
					$data['r_order']='';
				}
				if(!empty($blockIn->sort)){
					$data['sort']=$blockIn->sort;
				}
				if(isset($blockIn->is_custom) && $blockIn->is_custom==1){
					if(!empty($blockIn->course_arr)){
						$data['query_where'] = array("course_id"=>$blockIn->course_arr,"admin_status"=>1,"org_status"=>1);
					}else{
						$data['query_where'] = array("admin_status"=>1,"org_status"=>1);
					}
				}else{
					$data['query_where'] = array();
					if(!empty($blockIn->query_str)){
						$queryArr = explode(',',$blockIn->query_str);
						foreach($queryArr as $qo){
							$temp = explode(':',$qo);
							if($temp[0] == 'attr_value_id'){
								$data['query_where']['attr_value_id'] = str_replace('|',',',$temp[1]);
							}else{
								$data['query_where'][$temp[0]] = $temp[1];
							}
						}

					}
					$data['query_where']['admin_status'] = 1;
					$data['query_where']['org_status'] = 1;
				}
			}
			return $data;
	}

	public static function getCourseList($where, $order,$num='',$blockArrBlockId){
			$blockArr   = array(1,2,3,4,5,6,7,8,9,10,11,12);
			$block = index_api::getBlockInfo();
			$blockList = array();
			if(!empty($block)){
				foreach($block as $k=>$v){
					$blockList[$v->pk_block] = $v;
				}
			}
			$courseCondition 	= self::getBlockCourseOrderInfo($blockList,$blockArrBlockId);
			$recomm_con = array(
						"f"=>array(
							'course_id',
							'title',
							'thumb_med',
							'price',
							'course_type',
							'fee_type',
							'user_total',
							'remain_user',
							'start_time',
							'org_subname',
							'vv',
						 ),
						"q" => $where,
						"ob"=> $order,
						"p"=>1,
						"pl"=>$num,
					);
			$recomm_ret = seek_api::seekcourse($recomm_con);
			
			$courseInfo = array();
			if(!empty($recomm_ret) && !empty($recomm_ret->data)){
				foreach($recomm_ret->data as $k=>$v){
					$ret 		= new stdclass;
					$ret->courseId 		= !empty($v->course_id) ? $v->course_id : 0;
					$ret->title   		= !empty($v->title) ? $v->title : 0;
					$ret->imgurl   		= !empty($v->thumb_med) ? interface_func::imgUrl($v->thumb_med) : 0;
					$ret->price   		= !empty($v->price) ? $v->price : 0;
					$ret->courseType   	= !empty($v->course_type) ? $v->course_type : 0;
					$ret->feeType   	= !empty($v->fee_type) ? $v->fee_type : 0;
					$ret->start_time   	= !empty($v->start_time) ? $v->start_time : 0;
					$ret->orgSubname   	= !empty($v->org_subname) ? $v->org_subname : 0;
					if(!empty($courseCondition['order_str_type'][0])&&$courseCondition['order_str_type'][0]=="vv"){
						$ret->total 	= !empty($v->vv) ? $v->vv : 0;
						$ret->type      = 1;
					}
					if(!empty($courseCondition['order_str_type'][0])&&$courseCondition['order_str_type'][0]=="user_total"){
						$ret->total 	= !empty($v->user_total) ? $v->user_total : 0;
						$ret->type      = 2;
					}
					if(!empty($courseCondition['order_str_type'][0])&&$courseCondition['order_str_type'][0]=="remain_user"){
						$ret->total   	= !empty($v->remain_user) ? $v->remain_user : 0;
						$ret->type      = 3;
					}
					$courseInfo[$k] 	= $ret;
				}
				return $courseInfo;

			}else{
				return array();
			}
	}
	public static function getOrgCateInComeById($scopesArr){
        $ret = utility_services::call("/course/cate/GetOrgCateInComeById",$scopesArr);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
	}
	public static function getCateByCateIdArr($params){
        $ret = utility_services::call("/course/cate/GetCateByCidStr",$params);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
	}
	public static function getAttrByCateIdArr($cateIdArr){
    	$url = '/course/attr/getAttrByCateIdArr';
        $ret = utility_services::call($url,$cateIdArr);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
	}
	public static function addOrgCate($params){
        $ret = utility_services::call('/user/orgSetting/AddOrgCate',$params);
		if(isset($ret->code)&&$ret->code==0){
			return $ret->result;
		}
		return false;
	}
	/*
	 *将分类数据组合成树状结构
	 */
	public static function getCateTreeList($cateList,$lft=0,$rgt=0,$level=1){
		$return = array(); 
		if(!empty($cateList)){
			foreach($cateList as $cate){
				if(!empty($lft) && !empty($rgt)){
					if($cate->lft>$lft && $cate->rgt<$rgt && $cate->level-1 == $level){
						foreach($cateList as $k=>$subcate){
							if($subcate->lft>$cate->lft && $subcate->rgt<$cate->rgt && $subcate->level-1 == $cate->level){
								$cate->children = self::getCateTreeList($cateList,$cate->lft,$cate->rgt,$cate->level);	
								break;
							}
						}
						$return[] = $cate;
					}
				}else{
					if($cate->level == 1){
						foreach($cateList as $subcate){
							if($subcate->lft > $cate->lft && $subcate->rgt < $cate->rgt && $subcate->level-1 == $cate->level){
								$cate->children = self::getCateTreeList($cateList,$cate->lft,$cate->rgt,$cate->level);
								break;
							}
						}
						$return[] = $cate;
					}
				}
			}
		}
		return $return;
	}
	public static function getOrgCateSomeInfoById($scopesArr){
		$cateList 		= self::getOrgCateInComeById($scopesArr);
		$data			= array();
		if(!empty($cateList)){
			$data 		= self::getCateTreeList($cateList);
			return $data;
		}
		return false;
	}
	public static function getOrgExistCate($oid,$res=array()){
		$ret = utility_services::call('/user/orgSetting/GetOrgExistCate/'.$oid,$res);
		if(isset($ret->code)&&$ret->code==0){
			return $ret->result->items;
		}
		return false;
	}
	public static function delOrgCateById($params){
		$ret = utility_services::call('/user/orgSetting/DelOrgCateById/',$params);
		if(isset($ret->code)&&$ret->code==0){
			return $ret->result;
		}
		return false;
	}

	/**
	 * @param $org_id
	 * @param $subdomain
	 * @return string
	 */
	public static function addCustomNav($org_id,$subdomain){
		if(empty($org_id) || empty($subdomain) ) return interface_func::setMsg(3002);
		if(!intval($org_id)) return interface_func::setMsg(3002);
		$params = [
				['org_id'      => $org_id,
				'title'   	  => '教师',
				'url' 		  => 'http://'.$subdomain.'/teacher.list'],
				['org_id'      => $org_id,
				 'title'   	  => '资讯',
				  'url' 		  => 'http://'.$subdomain.'/activity.main.list'],
				['org_id'      => $org_id,
				 'title'   	  => '直播',
				  'url' 		  => 'http://'.$subdomain.'/live.list']
		];
		$data = ['org_id'=>(int)$org_id];
		$ret =utility_services::call('/customnav/custom/SelectCustom',$data);
		if($ret->code ==-1){
			foreach($params as $v){
				self::addNav($v);
			}
		}
		return $ret;
	}
}
?>
