<?php

class index_rank extends STpl{
	var $user;
	var $domain;
	public function __construct(){
		$this->user = user_api::loginedUser();
		
		$domainConf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domainConf->domain;
		$this->assign('domain', $this->domain);
	}	
	
	public function pageEntry(){
		
		//获取成长值
		$userLevel = array();
		$userInfo = array();
		$uid = 0;
		if(!empty($this->user['uid'])){
			$uid = $this->user['uid'];
			$userLevel = user_api::getUserLevel($uid);	
			if(!$userLevel){
				$userLevel = new stdclass;
				$userLevel->score = 0;
				$userLevel->fk_level= 1;
				$userLevel->title = '书生1';
				$userLevel->fk_user = $uid;
			}
			$userInfo = user_api::getUser($uid);
		}

		$weekRank = array();
		$monthRank = array();
		$allRank = array();
		//周排行榜
		$weekStart = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-date("w")+1,date("Y")));
		$weekEnd = date("Y-m-d",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y")));
		$weekRank = user_api::getUserRankByDate($weekStart,$weekEnd,1,25);

		//月排行榜
		$timestamp = time();
		$mdays=date('t',$timestamp);
		$monthStart = date('Y-m-01',$timestamp);
		$monthEnd = date('Y-m-'.$mdays,$timestamp);
		$monthRank = user_api::getUserRankByDate($monthStart,$monthEnd,1,25);

		//获取总排行榜
		$allRank = user_api::getAllUserRank(1,25);
		$allUserCount = user_api::getAllUserCount();
		if(empty($allUserCount)){
			$allUserCount = 0;
		}

		$weekSort     = 0;
		$monthSort    = 0;
		$allSort      = 0;
		$weekPercent  = 0;
		$monthPercent = 0;
		$allPercent   = 0;
		$weekRankHead = array();
		$weekRankLeft = array();
		$weekRankRight = array();
		$monthRankHead = array();
		$monthRankLeft = array();
		$monthRankRight = array();
		$allRankHead = array();
		$allRankLeft = array();
		$allRankRight = array();
		if(!empty($weekRank)){
			if(!empty($uid)){
				$weekSort = user_api::getUserSortByDate($uid,$weekStart,$weekEnd);
			}
			$weekRankHead  = array_splice($weekRank,0,3);
			$weekRankLeft  = array_splice($weekRank,0,11);
			$weekRankRight = array_splice($weekRank,0,11);
		}
		if(!empty($monthRank)){
			if(!empty($uid)){
				$monthSort = user_api::getUserSortByDate($uid,$monthStart,$monthEnd);
			}
			$monthRankHead  = array_splice($monthRank,0,3);
			$monthRankLeft  = array_splice($monthRank,0,11);
			$monthRankRight = array_splice($monthRank,0,11);
		}
		if(!empty($allRank)){
			if(!empty($uid)){
				$allSort = user_api::getUserAllSortByUid($uid);
			}
			$allRankHead  = array_splice($allRank,0,3);
			$allRankLeft  = array_splice($allRank,0,11);		
			$allRankRight = array_splice($allRank,0,11);
		}


		if(!empty($uid)){	
			if(!empty($weekSort)){
				$lessCount = $allUserCount-$weekSort+1;
				$weekPercent = floor(($lessCount/$allUserCount)*1000)/10 . '%';
			}
			if(!empty($monthSort)){
				$lessCount = $allUserCount-$monthSort+1;
				$monthPercent = floor(($lessCount/$allUserCount)*1000)/10 . '%';
			}
			if(!empty($allSort)){
				$lessCount = $allUserCount-$allSort+1;
				$allPercent = floor(($lessCount/$allUserCount)*1000)/10 . '%';
			}
		}
		$this->assign('uid',$uid);
		$this->assign('weekSort',$weekSort);
		$this->assign('monthSort',$monthSort);
		$this->assign('allSort',$allSort);
		$this->assign('weekPercent',$weekPercent);
		$this->assign('monthPercent',$monthPercent);
		$this->assign('allPercent',$allPercent);
		$this->assign('weekRankHead',$weekRankHead);
		$this->assign('weekRankLeft',$weekRankLeft);
		$this->assign('weekRankRight',$weekRankRight);
		$this->assign('monthRankHead',$monthRankHead);
		$this->assign('monthRankLeft',$monthRankLeft);
		$this->assign('monthRankRight',$monthRankRight);
		$this->assign('allRankHead',$allRankHead);
		$this->assign('allRankLeft', $allRankLeft);
		$this->assign('allRankRight', $allRankRight);
		$this->assign('userLevel', $userLevel);
		$this->assign('userInfo', $userInfo);
		$this->render('index/student.rank.html');
	}
	public function pageRule(){
		$this->render('index/student.rule.html');
	}	
	
	
	
	
	
}
	
