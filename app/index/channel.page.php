<?php
class index_channel extends STpl{
	private $domain;
	private $user;
	private $week = array(
			1 => '星期一',
			2 => '星期二',
			3 => '星期三',
			4 => '星期四',
			5 => '星期五',
			6 => '星期六',
			7 => '星期日',
	);
    public function __construct(){
    	$this->user = user_api::loginedUser();    	
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
		$this->assign('domain', $this->domain);
	}
	public function pageList($inpath){
		$this->render('index/channel.html');

	}

	public function pageChannel($inPath){		
		$result = new stdClass;
        $result->code = "100";
        $banner = array();
        $thumbData = array();
        $banner['fk_channel'] = !empty($inPath['3']) ? $inPath['3'] : 0;
		$banner['fk_org'] = 0;
		if(empty($inPath['3'])){
			$result->code = -100;
			$result->msg  = "频道id参数缺失";
			return $result;
		}
		//获取展示类型
		//$Hot 			= user_organization::getOrgSetHotType(self::$orgOwner);
		$HotType		= !empty($HotType->hot_type) ? $HotType->hot_type : '4';
		if(!empty($HotType)){
			$result->HotType	= $HotType;
		}else{
			$result->HotType	= "";
		}
        //频道banner列表
		$info 				    = org_api::BannerList($banner);
        if(!empty($info)){
            foreach($info as $k => $v){
				$v->thumb = !empty($v->thumb) ? utility_cdn::file($v->thumb) : '';
                $thumbData[$v->type][] = $v;
            }
        }
        $result->channelBanner  = !empty($thumbData['1']) ? $thumbData['1'] : '';
        //频道课程模块列表
        $blockCondition         = array();
        $blockCondition['fk_channel']    = !empty($inPath['3']) ? $inPath['3'] : 0;
        $fk_user_owner = org_api::getPlatformBloclOwer($blockCondition['fk_channel']);
        $blockCondition['fk_user_owner'] = $fk_user_owner->fk_user_owner;
        $blockList               = org_api::getChannelBlockList($blockCondition);
        
		if(!empty($blockList)){
			$queryField = array(
									'first_cate'=>'fc',
									'second_cate'=>'sc',
									'third_cate'=>'tc',
									'course_type'=>'course_type',
									'attr_value_id' => 'vid',
								);

			$qcourse_id = '';
            foreach($blockList as $tk=>$tv){
				if(!empty($tv->course_ids)){
					$qcourse_id = $tv->course_ids;
				}
				$arr 	   = array();
				if(!empty($tv->query_arr)){
					$query = (array)$tv->query_arr;
					foreach($query as $a=>$b){
						if(!empty($queryField[$a])){
							$arr[$queryField[$a]] = $b ;
						}
					}
					$str = '';
					if(!empty($arr)){
						foreach($arr as $m=>$n){
							$str .= "&".$m."=".$n;
						}
						$tv->query= "/course.list?".$str;
					}
				}

				if(!empty($qcourse_id)&&$tv->course_ids){
					$qArr 	 = array("admin_status"=>1,"course_id"=>$qcourse_id);
					$seekArr = array(
							"f"  =>array(
								"course_id","title","subdomain","thumb_big","thumb_med",
								"thumb_sma","course_type","user_id",
								"public_type","fee_type","price","market_price",
								"max_user","min_user","user_total","status",
								"admin_status","system_status",'top','try','vv',"third_cate_name"
								),
							"q"  => $qArr,
							"p"  => 1,
							"pl" => 20,
							);
					$seekRet =	seek_api::seekcourse($seekArr);
					if(!empty($seekRet->data)){
                        foreach($seekRet->data as $rsk=>$rsv){
                            $rsv->thumb_big = !empty($rsv->thumb_big) ? utility_cdn::file($rsv->thumb_big) : '';
                            $rsv->thumb_med = !empty($rsv->thumb_med) ? utility_cdn::file($rsv->thumb_med) : '';
                            $rsv->thumb_sma = !empty($rsv->thumb_sma) ? utility_cdn::file($rsv->thumb_sma) : '';
                            $rsv->url 		= "/course.info.show/".$rsv->course_id;
							if(!empty($rsv->price)){
								$rsv->price	= number_format($rsv->price/100,2);
							}else{
								$rsv->price = 0;
							}
                        }
                        $blockList[$tk]->courses=$seekRet->data;
					}
				}
				//频道推荐图
				if($tv->type == 3){
					$con['fk_block']			= $tv->pk_block;
					$con['type']		    	= 2;
					$thumbInfo 					= org_api::BannerList($con);

					if(!empty($thumbInfo)){
						foreach($thumbInfo as $n){
						$n->thumb  = !empty($n->thumb) ? utility_cdn::file($n->thumb) : '';
						}
					}
					$blockList[$tk]->thumb_arr	= $thumbInfo;

				}

				$tv->thumb_left  =	!empty($tv->thumb_left) ? utility_cdn::file($tv->thumb_left) : '';
				$tv->thumb_right =	!empty($tv->thumb_right) ? utility_cdn::file($tv->thumb_right) : '';
            }
        }        
        $result->blockList       = !empty($blockList) ? $blockList : '';        
        return $result;
	}
}
