<?php
class org_settle extends STpl{
	private $domain;
	private $orgOwner;
	private $orgInfo;
	private $user;
	private $orgId;
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
		if(!empty($this->orgInfo->oid)){
	 			$this->orgId = $this->orgInfo->oid;
		}else{
			header('Location: https://www.'.$this->domain);	 
		}
        //判断管理员
        $isAdmin=user_api::isAdmin($this->orgOwner,$this->user['uid']);
        if($isAdmin===false){			
            header('Location: //'.$org->subdomain.'.'.$this->domain);
        }
	}
	public function pageEntry($inPath){
		$orgId = $this->orgId;	
		$uid = $this->user['uid'];
		if($uid == $this->orgOwner){
			$isAdmin = 1;
		}else{
			$isAdmin = 0;
		}
		$this->assign('isAdmin',$isAdmin);

		$today = date('Y-m-d H:i',time());
		$this->assign('today',$today);
        //收入查询
		$orgAccount = user_organization::getOrgAccountByOrgId($orgId);
		//获取月、周收入与订单数
		$weekStat = user_organization::getWeekStat($orgId);
		$monthStat = user_organization::getMonthStat($orgId);
		$this->assign('weekStat',$weekStat);
		$this->assign('monthStat',$monthStat);
        $this->assign('orgAccount',$orgAccount);
		//获取用户审核中的提现记录
		$withdrawLog = user_organization::getOrgAccountWithdrawList($orgId,1,0,'','',0);
		if(empty($withdrawLog->items) && !empty($orgAccount->withdraw)){
			$wflag = 1;
		}else{
			$wflag = 0;
		}
		if(!empty($withdrawLog->items)){
			$wlog = 1;
		}else{
			$wlog = 0;
		}
		$this->assign('wlog',$wlog);
		$this->assign('wflag',$wflag);
		//获取日、周、月百分比
		$dayPercent = user_organization::getDayPercent($orgId);
		$weekPercent = user_organization::getWeekPercent($orgId);
		$monthPercent = user_organization::getMonthPercent($orgId);
		$this->assign('dayPercent',$dayPercent);
		$this->assign('weekPercent',$weekPercent);
		$this->assign('monthPercent',$monthPercent);
		//获取银行卡信息
		$cardInfo = user_organization::getOrgCardByOrgId($orgId);
		if(!empty($cardInfo)){
			$cardInfo->card_no = '*************** '.substr($cardInfo->card_no,-4);
			$cardInfo->user = '*'.mb_substr($cardInfo->user,-1,1,'utf-8');
			$cardInfo->last_no = substr($cardInfo->card_no,-4);
		}
		//报表
		$chartsType = isset($_GET['charts_type'])?$_GET['charts_type']:'account';
		$xtype = isset($_GET['xtype'])?$_GET['xtype']:'day';
		$startDate = isset($_GET['start'])?$_GET['start']:'';
		$endDate = isset($_GET['end'])?$_GET['end']:'';
		$page = isset($_GET['page'])?$_GET['page']:1;
		$num = 20;
		if(empty($startDate) && empty($endDate)){
			$startDate = date('Y-m-d',strtotime('-7 days'));
			$endDate = date('Y-m-d',strtotime('-1 days'));
		}
		
		$xdata = $ydata = $list = array();	
		if($xtype == 'day'){
			$orgData = stat_api::getDayOrgStatByOwnerid($this->orgOwner,$startDate,$endDate);	
			if(!empty($startDate) && empty($endDate)){
				$endDate = $startDate;
			}
			if(empty($startDate) && !empty($endDate)){
				$startDate = $endDate; 
			}
			$lastDay = date('Y-m-d',strtotime('-1 days',strtotime($startDate)));
			$orgLastData = stat_api::getDayOrgStatByOwnerid($this->orgOwner,$lastDay,'');
			if(!empty($orgLastData)){
				$lastData['order_new'] = $orgLastData[0]->order_new;
				$lastData['income_new'] = $orgLastData[0]->income_new;
			}else{
				$lastData['order_new'] = 0;
				$lastData['income_new'] = 0;
			}
			$timeArr = $this->timeFormat($startDate, $endDate);
			if($chartsType == 'account'){
				$orgTemp = array();
				if(!empty($orgData)){
					foreach($orgData as $org){	
						$orgTemp[$org->pk_day] = $org->income_new; 
					}
				}
				foreach($timeArr as $to){
					$yesDay = date('Y-m-d',strtotime('-1 days', strtotime($to)));
					if(isset($orgTemp[$to])){
						$income = $orgTemp[$to];
					}else{
						$income = 0;
					}
					if($yesDay == $lastDay){
						$chaPercent = $this->settlePercent($income,$lastData['income_new']);
						$list[$to]['percent'] = $chaPercent['percent'];
						$list[$to]['status']  = $chaPercent['status'];
					}elseif(isset($orgTemp[$yesDay])){
						$chaPercent = $this->settlePercent($income,$orgTemp[$yesDay]);
						$list[$to]['percent'] = $chaPercent['percent'];
						$list[$to]['status']  = $chaPercent['status'];
					}else{
						$chaPercent = $this->settlePercent($income,0);
						$list[$to]['percent'] = $chaPercent['percent'];
						$list[$to]['status']  = $chaPercent['status'];
					}
					$listIncome = number_format(sprintf("%0.2f", floatval($income/100)), 2);
					$yincome = $income/100;
					$ydata[$to] = "'$yincome'";
					$list[$to]['income_all'] = $listIncome;
					$xdata[] = "'$to'";
				}	
			}elseif($chartsType == 'order'){
				$orgTemp = array();
				if(!empty($orgData)){
					foreach($orgData as $org){
						$orgTemp[$org->pk_day] = $org->order_new;
					}
				}
				foreach($timeArr as $to){
					$yesDay = date('Y-m-d',strtotime('-1 days', strtotime($to)));
					if(isset($orgTemp[$to])){
						$orderCount = $orgTemp[$to];
					}else{
						$orderCount = 0;
					}
					if($yesDay == $lastDay){
						$chaPercent = $this->settlePercent($orderCount,$lastData['order_new']);
						$list[$to]['percent'] = $chaPercent['percent'];
						$list[$to]['status']  = $chaPercent['status'];
					}elseif(isset($orgTemp[$yesDay])){
						$chaPercent = $this->settlePercent($orderCount,$orgTemp[$yesDay]);
						$list[$to]['percent'] = $chaPercent['percent'];
						$list[$to]['status']  = $chaPercent['status'];
					}else{
						$chaPercent = $this->settlePercent($orderCount,0);
						$list[$to]['percent'] = $chaPercent['percent'];
						$list[$to]['status']  = $chaPercent['status'];
					}
					$orderCount = number_format($orderCount);
					$ydata[$to] = $orderCount;
					$list[$to]['order_count'] = $orderCount;
					$xdata[] = "'$to'";
				}
			}				
		}elseif($xtype == 'month'){
			if(!empty($startDate) && empty($endDate)){
				$endDate = $startDate;
			}
			if(empty($startDate) && !empty($endDate)){
				$startDate = $endDate; 
			}
			$startYear = date('Y',strtotime($startDate));
			$startMonth = date('m',strtotime($startDate));
			$endYear = date('Y',strtotime($endDate));
			$endMonth = date('m',strtotime($endDate));
			if($startYear != $endYear){
				$startNew = $startYear.'-'.$startMonth.'-01';
				$dayCount = date('t',strtotime($endYear.'-'.$endMonth.'-01'));
				$endNew = date('Y-m-d',strtotime($endYear.'-'.$endMonth.'-01')+($dayCount-1)*24*3600);
			}elseif($startYear == $endYear){
				if($startMonth !=  $endMonth){
					$startNew = $startYear.'-'.$startMonth.'-01';
					$dayCount = date('t',strtotime($endYear.'-'.$endMonth.'-01'));
					$endNew = date('Y-m-d',strtotime($endYear.'-'.$endMonth.'-01')+($dayCount-1)*24*3600);
				}elseif($startMonth ==  $endMonth){
					$startNew = $startYear.'-'.$startMonth.'-01';
					$dayCount = date('t',strtotime($startNew));
					$endNew = date('Y-m-d',strtotime($startNew)+($dayCount-1)*24*3600);
				}
			}
			$orgData = stat_api::getDayOrgStatByOwnerid($this->orgOwner,$startNew,$endNew);	
			$currYear = date('Y',time());
			$lastMonth = date('Y-m',strtotime('-1 month',strtotime($startDate)));
			$lastMonthStart = $lastMonth.'-01';
			$dayCount = date('t',strtotime($lastMonthStart));
			$lastMonthEnd = date('Y-m-d',strtotime($lastMonthStart)+($dayCount-1)*24*3600);
			$lastMonthData = stat_api::getDayOrgStatOrderCountByDay($this->orgOwner,$lastMonthStart,$lastMonthEnd);
			if(!empty($lastMonthData)){
				$lastData['order_new'] = $lastMonthData->order_count;
				$lastData['income_new'] = $lastMonthData->income_all;
			}else{
				$lastData['order_new'] = 0;
				$lastData['income_new'] = 0;
			}
			$monArr = $this->monthFormat($startDate,$endDate);
			if($chartsType == 'account'){
				$orgTemp = array();
				if(!empty($orgData)){
					foreach($orgData as $org){
						$orgMonth = date('Y-m',strtotime($org->pk_day));
						if(!isset($orgTemp[$orgMonth])){
							$orgTemp[$orgMonth] = 0;
						}
						$orgTemp[$orgMonth] += $org->income_new;
					}
				}
				foreach($monArr as $mv){
					$ytime = strtotime($mv);
					$yesMonth = date('Y-m',strtotime('-1 month', $ytime));
					if(date('Y',$ytime) != $currYear ){
						$mkey = str_replace('-','年',date('Y-m',$ytime)).'月';
					}else{
						$mkey = date('m',$ytime).'月';
					}
					if(isset($orgTemp[$mv])){
						$income = $orgTemp[$mv];
					}else{
						$income = 0;
					}
					if($yesMonth == $lastMonth){
						$chaPercent = $this->settlePercent($income,$lastData['income_new']);
						$list[$mkey]['percent'] = $chaPercent['percent'];
						$list[$mkey]['status']  = $chaPercent['status'];
					}elseif(isset($orgTemp[$yesMonth])){
						$chaPercent = $this->settlePercent($income, $orgTemp[$yesMonth]);
						$list[$mkey]['percent'] = $chaPercent['percent'];
						$list[$mkey]['status']  = $chaPercent['status'];
					}else{
						$chaPercent = $this->settlePercent($income, 0);
						$list[$mkey]['percent'] = $chaPercent['percent'];
						$list[$mkey]['status']  = $chaPercent['status'];
					}
					$listIncome = number_format(sprintf("%0.2f", floatval($income/100)), 2);
					$yincome = $income/100;
					$ydata[$mkey] = "'$yincome'";
					$list[$mkey]['income_all'] = $listIncome; 
					$mstart = $mv.'-01';
					$mcount = date('t',strtotime($mstart));
					$mend = date('Y-m-d',strtotime($mstart)+($mcount-1)*24*3600);
					$list[$mkey]['start_date'] = $mstart;
					$list[$mkey]['end_date'] = $mend;
					$xdata[] = "'$mkey'"; 
				}
			}elseif($chartsType == 'order'){
				$orgTemp = array();
				if(!empty($orgData)){
					foreach($orgData as $org){
						$orgMonth = date('Y-m',strtotime($org->pk_day));
						if(!isset($orgTemp[$orgMonth])){
							$orgTemp[$orgMonth] = 0;
						}
						$orgTemp[$orgMonth] += $org->order_new;
					}
				}
				foreach($monArr as $mv){
					$ytime = strtotime($mv);
					$yesMonth = date('Y-m',strtotime('-1 month', $ytime));
					if(date('Y',$ytime) != $currYear ){
						$mkey = str_replace('-','年',date('Y-m',$ytime)).'月';
					}else{
						$mkey = date('m',$ytime).'月';
					}
					if(isset($orgTemp[$mv])){
						$orderCount = $orgTemp[$mv];
					}else{
						$orderCount = 0;
					}
					if($yesMonth == $lastMonth){
						$chaPercent = $this->settlePercent($orderCount,$lastData['order_new']);
						$list[$mkey]['percent'] = $chaPercent['percent'];
						$list[$mkey]['status']  = $chaPercent['status'];
					}elseif(isset($orgTemp[$yesMonth])){
						$chaPercent = $this->settlePercent($orderCount,$orgTemp[$yesMonth]);
						$list[$mkey]['percent'] = $chaPercent['percent'];
						$list[$mkey]['status']  = $chaPercent['status'];
					}else{
						$chaPercent = $this->settlePercent($orderCount,0);
						$list[$mkey]['percent'] = $chaPercent['percent'];
						$list[$mkey]['status']  = $chaPercent['status'];
					}
					$orderCount = number_format($orderCount);
					$ydata[$mkey] = $orderCount;
					$list[$mkey]['order_count'] = $orderCount; 
					$mstart = $mv.'-01';
					$mcount = date('t',strtotime($mstart));
					$mend = date('Y-m-d',strtotime($mstart)+($mcount-1)*24*3600);
					$list[$mkey]['start_date'] = $mstart;
					$list[$mkey]['end_date'] = $mend;
					$xdata[] = "'$mkey'"; 
				}
			}
		}
		$offet = $num*($page-1);
		$totalCount = count($list);
		$totalPage = ceil($totalCount/$num);
		$list = array_splice($list,$offet,$num);
		$path = '/org.settle';
		$pathUrl = utility_tool::getUrl($path);
		$exportUrl = utility_tool::getUrl('/phpexcel.settle.exportoverview');
		if($chartsType == 'account'){
			$chartsName = SLanguage::tr('总收入','org');
		}else{
			$chartsName = SLanguage::tr('销售订单','org');
		}
		$weekStart = date("n月j日",mktime(0,0,0,date("m"),date("d")-date("w")+1-14,date("Y")));
        $weekEnd   = date("n月j日",mktime(23,59,59,date("m"),date("d")-date("w")+1-7,date("Y")));
		$monthStart = date("n月",mktime(0, 0 , 0,date("m")-2,1,date("Y")));
        $monthEnd   = date("n月",mktime(23,59,59,date("m") ,0,date("Y")));
		$this->assign('weekStart',$weekStart);
		$this->assign('weekEnd',$weekEnd);
		$this->assign('monthStart',$monthStart);
		$this->assign('monthEnd',$monthEnd);
		$this->assign('exportUrl',$exportUrl);
		$this->assign('page',$page);
		$this->assign('num',$num);
		$this->assign('totalPage',$totalPage);
		$this->assign('list',$list);
		$this->assign('xdata',implode(',',$xdata));
		$this->assign('ydata',implode(',',$ydata));
		$this->assign('xtype',$xtype);
		$this->assign('start',$startDate);
		$this->assign('chartsType',$chartsType);
		$this->assign('chartsName',$chartsName);
		$this->assign('end',$endDate);
		$this->assign('path',$pathUrl);
		$this->assign('cardInfo',$cardInfo);
		return $this->render("org/settle.overview.html");
	}
	
	public function settlePercent($today,$last){
		$ret = array(
			'percent' => '----',
			'status'  => 0
		);
		if(empty($last) && !empty($today)){
			$ret['percent'] = '----';
			$ret['status']  = 0;
		}elseif(!empty($last) && empty($today)){
			$ret['percent'] = '100%';
			$ret['status']  = -1;
		}elseif(empty($last) && empty($today)){
			$ret['percent'] = '----';
			$ret['status']  = 0;
		}elseif(!empty($last) && !empty($today)){
			if($last > $today){
				$cha = $last - $today;
				$ret['percent'] = floor(($cha/$last)*100) . '%';
				$ret['status']  = -1;
			}elseif($last < $today){
				$cha = $today - $last;
				$ret['percent'] = floor(($cha/$last)*100) . '%';
				$ret['status']  = 1;
			}elseif($last == $today){
				$ret['percent'] = '0%';
				$ret['status']  = 0;
			}
		}
		return $ret;
	}
	
	
	public function timeFormat($begin, $end){
        $time = range(strtotime($begin), strtotime($end),24*60*60);
        return  array_map(create_function('$v', 'return date("Y-m-d", $v);'), $time);
    }
	
	public function monthFormat($begin, $end){
        $stime = strtotime($begin);
		$etime = strtotime($end);
		$currYear = date('Y',time());
		$monArr = array();
		$monArr[] = date('Y-m',$stime);
		while( ($stime = strtotime('+1 month', $stime)) <= $etime){
			$monArr[] = date('Y-m',$stime);
		}
        return $monArr;
    }
	
	public function pageWithdraw($inPath){
		$orgId = $this->orgId;
		$page = !empty($_GET['page'])?$_GET['page']:1;
		$length = 20;
		$createTime = !empty($_GET['start'])?$_GET['start']:'';
		$status = isset($_GET['status'])?$_GET['status']:'';
		if(!empty($createTime)){
			$stime = date('Y-m-d',strtotime($createTime)).' 00:00:00';
			$etime = date('Y-m-d',strtotime($createTime)).' 23:59:59';
		}else{
			$stime = '';
			$etime = '';
		}
		if($status == 2){
			$qstatus = '2,3';
		}else{
			$qstatus = $status;
		}
		$withdrawList = user_organization::getOrgAccountWithdrawList($orgId,$page,$length,$stime,$etime,$qstatus);	
		$year = date('Y',time());
		if(!empty($withdrawList->items)){
			foreach($withdrawList->items as &$wo){
				$wo->card_no = substr($wo->card_no,-4);
				$createYear = date('Y',strtotime($wo->create_time));
				if($createYear == $year){
					$wo->create_time = date('m/d H:i',strtotime($wo->create_time));
				}else{
					$wo->create_time = date('Y/m/d H:i',strtotime($wo->create_time));
				}
			}
		}
		$path = '/org.settle.withdraw';
		$pathUrl = utility_tool::getUrl($path);
		$exportUrl = utility_tool::getUrl('/phpexcel.settle.exportwithdrawlog');
		$this->assign('createTime',$createTime);
		$this->assign('num',$length);
		$this->assign('path',$pathUrl);
		$this->assign('status',$status);
		$this->assign('exportUrl',$exportUrl);
		$this->assign('withdrawList',$withdrawList);
        $this->render("org/settle.withdrawlist.html");
	}
   
	public function pageUpdateWithdrawAjax($inPath){
		$wid  = !empty($_POST['wid'])?$_POST['wid']:0;
		if(empty($wid)){
			$res = array('code' => -2,'msg'=>'参数错误');
			return json_encode($res); 
		}
		
		if(isset($_POST['status'])){
			$data['status'] = $_POST['status'];
			if($data['status'] == 3){
				$data['fk_user_submit'] = $this->user['uid'];
			}
		}
		if(isset($_POST['descript'])){
			$data['descript'] = strip_tags($_POST['descript']);
		}
		$updateRet = user_organization::updateOrgAccountWithdraw($wid,$data);
		if($updateRet !== false){
			$res = array('code' => 0,'msg'=>'修改成功');
		}else{
			$res = array('code' => -1,'msg'=>'修改失败');
		}
		return json_encode($res);
	}
	public function pageApplyWithdraw($inPath){
		$orgId = $this->orgId;
		$ownerInfo = user_api::getUser($this->orgOwner);
		$orgAccount = user_organization::getOrgAccountByOrgId($orgId);
		$flag = 0;
		$withdrawOld=0;
		if(!empty($orgAccount)){
			$withdraw = ($orgAccount->withdraw)/100;
			$withdrawOld = $withdraw;
			if($withdraw>1000) {
				$withdraw = $withdraw-1000;
				if ($withdraw > 10000) {
					$flag = 1;
				}
			}else{
				$withdraw = 0;
			}
		}else{
			$withdraw = 0;
		}
		$withdrawLog = user_organization::getOrgAccountWithdrawList($orgId,1,0,'','',0);
		if(!empty($withdrawLog->items) || empty($orgAccount->withdraw)){
			$this->redirect('/org.settle.withdraw');
			exit;
		}	
		$cardInfo = user_organization::getOrgCardByOrgId($orgId);
		if(!empty($cardInfo)){
			$cardInfo->last_no = substr($cardInfo->card_no,-4);
		}
		$uid = $this->user['uid'];
		if($uid == $this->orgOwner){
			$isAdmin = 1;
		}else{
			$isAdmin = 0;
		}
		$this->assign('isAdmin',$isAdmin);
		$this->assign('flag',$flag);
		$this->assign('cardInfo',$cardInfo);
		$this->assign('mobile',$ownerInfo->mobile);
		$this->assign('withdraw',$withdraw);
        $this->assign('orgAccount',$orgAccount);
		$this->assign('withdrawOld',$withdrawOld);
		$this->render("org/withdraw.apply.html");
	}
	
	public function pageApplyWithdrawAjax($inPath){
		$orgId = $this->orgId;
		$params = $_POST;
		$result=new stdclass;
		$orgAccount = user_organization::getOrgAccountByOrgId($orgId);
		$flag = 0;
		if(!empty($orgAccount)){
			$orgWithdraw = ($orgAccount->withdraw)/100;
			if($orgWithdraw>1000) {
				$orgWithdraw = $orgWithdraw-1000;

			}else{
				$orgWithdraw = 0;
			}
		}else{
			$orgWithdraw = 0;
		}
		if(empty($params['withdraw']) || $params['withdraw'] == '0.00'){
			$result->field="withdraw";
			$result->error=SLanguage::tr('请输入转出金额','org');
			return $result;
		}
		if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $params['withdraw'])){
			$result->field="withdraw";
			$result->error=SLanguage::tr('转出金额错误','org');
			return $result;
		}
		if($params['withdraw'] > $orgWithdraw || $params['withdraw'] > 50000){
			$result->field="withdraw";
			$result->error=SLanguage::tr('您的账户最多可提现','org').$orgWithdraw."元，".SLanguage::tr('每天最多转','org')."5万元 ";
			return $result;
		}
		$ownerInfo = user_api::getUser($this->orgOwner);
		if($params['mobile'] != $ownerInfo->mobile){
			$result->field="mobile";
			$result->error=SLanguage::tr('请输入机构创建者手机号','org');
			return $result;
		}
		$cardId = isset($params['cardId'])?$params['cardId']:'';
		if( empty($cardId) ){
			$result->field="cardId";
			$result->error=SLanguage::tr('请添加银行卡','org');
			return $result;
		}
		$mobileCode = str_replace(' ','',$params['mobileCode']);
        if(verify_api::verifyMobile($params['mobile'],$mobileCode)==false){
            $result->error=SLanguage::tr('手机验证码错误','org');
            $result->field="mobileCode";
            return $result;
        }
		$params['descript'] = strip_tags($params['descript']);
		if(!empty($params['descript'])){
			$res = utility_tool::check_string($params['descript'],10,1);
			if(!$res){
                $result->field = "descript";
                $result->error = SLanguage::tr('少于10个字','org');
                return $result;
            }
		}
		$params['withdraw_org'] = $orgWithdraw;
		$addRet = user_organization::addOrgWithdrawLog($this->user['uid'],$orgId,$params);	
		if(!empty($addRet)){
			return $result;
		}else{
			$result->error=SLanguage::tr('申请失败','org');
            $result->field="add";
            return $result;
		}
	}
	public function pageApplyWithdrawSuccess($inPath){
		$this->render('org/withdraw.apply.success.html');
	}
	public function pageCheckMobileCode(){
		$result=new stdclass;
		$mobile = $_POST['mobile'];
		$mobileCode = $_POST['code'];
        if(verify_api::verifyMobile($mobile,$mobileCode)==false){
            $result->error=SLanguage::tr('手机验证码错误','org');
            $result->field="mobileCode";
            return $result;
        }else{
			$result->code = 0;
			return $result;
		}
	}
	
	public function pageSendCode($inPath){
		$result=new stdclass;
		$mobile = $_POST['mobile'];
		$ownerInfo = user_api::getUser($this->orgOwner);
		if($mobile != $ownerInfo->mobile){
			$result->field="mobile";
			$result->error=SLanguage::tr('请输入机构创建者手机号','org');
			return $result;
		}
		$r = verify_api::sendMobileCode($mobile,$ret);
		if($r===false){
			$result->error=SLanguage::tr('发送验证码错误','org');
			$result->field="code";
		}else{
			$result->code = 0;
		}
		return $result;
	}
	public function pageBankCard($inPath){
		$orgId = $this->orgId;
		$uid = $this->user['uid'];
		if($uid != $this->orgOwner){
			$this->redirect('/org.settle');
			exit;
		}
		$uid = $this->user['uid'];
		if($uid != $this->orgOwner){
			$this->redirect('/org.settle');
			exit;
		}
		$card = SConfig::getConfig(ROOT_CONFIG."/bankcard.conf","card");
		if(!empty($card->card)){
			$cardList = $card->card;
		}else{
			$cardList = array();
		}
		$cardInfo = user_organization::getOrgCardByOrgId($orgId);
		if(!empty($cardInfo)){
			$this->redirect('/org.settle');
			exit;
		}
		
		$level0  = region_api::listRegion(0);
		$this->assign("level0",$level0);
		
		$ownerInfo = user_api::getUser($this->orgOwner);
		$this->assign('mobile',$ownerInfo->mobile);
		$this->assign('cardList',$cardList);
		$this->render('org/settle.bankcard.html');
	}
	
	public function pageAddCard($inPath){
		$orgId = $this->orgId;
		$params = $_POST;
		
		$data = array();
		$result=new stdclass;
		$flag = 0;
		$data['user'] = strip_tags($params['real_name']);
		if(preg_match("/[\x7f-\xff]/", $data['user'])){
			$length_res = utility_tool::check_string($data['user'], 30,1);
			if(!$length_res){
				$result->field = "real_name";
				$result->error = SLanguage::tr('真实姓名不能超过30个汉字','org');
				 return $result;
			}else{
				$flag = 1;
			}
		}
		if(preg_match("/[a-zA-Z\s]+$/", str_replace(' ','',$data['user']))){
        	$res = utility_tool::check_string($data['user'], 25,1);
			if(!$res){
                $result->field = "real_name";
                $result->error = SLanguage::tr('真实姓名不能超过25个英文字符','org');
                return $result;
            }else{
				$flag = 1;
			}
        }
		if($flag == 0){
			$result->field = "real_name";
            $result->error = SLanguage::tr('真实姓名输入格式不正确','org');
            return $result;
		}
		
		if( empty($params['bank']) ){
			$result->field="bank";
			$result->error=SLanguage::tr('请选择银行卡','org');
			return $result;
		}
		if($params['bank'] == '其他'){
			if(strip_tags($params['bank_name']) == ''){
				$result->field="bank_name";
				$result->error=SLanguage::tr('请填写银行卡名称','org');
				return $result;
				
			}else{
				$data['bank'] = strip_tags($params['bank_name']);
			}
		}else{
			$data['bank'] = $params['bank'];
		}
		
		$data['branch'] = strip_tags($params['branch']);
		if(empty($data['branch'])){
			$result->field="branch";
			$result->error=SLanguage::tr('请填写银行卡开户网点','org');
			return $result;
		}
		if(empty($params['card_no'])){
			$result->field="card_no";
			$result->error=SLanguage::tr('请填写银行卡号','org');
			return $result;
		}
		$data['card_no'] = str_replace(' ','',$params['card_no']);
		if(!utility_tool::check_int($data['card_no'],0,-1,false)){
			$result->field="card_no";
			$result->error=SLanguage::tr('请输入正确的银行卡号','org');
			return $result;
		}
		if(empty($params['mobile'])){
			$result->field="mobile";
			$result->error=SLanguage::tr('请输入手机号','org');
			return $result;
		}
		if(!utility_valid::mobile($params['mobile'])){
			$result->field="mobile";
			$result->error=SLanguage::tr('手机号格式错误','org');
			return $result;
		}
		$ownerInfo = user_api::getUser($this->orgOwner);
		if($params['mobile'] != $ownerInfo->mobile){
			$result->field="mobile";
			$result->error=SLanguage::tr('请输入机构创建者手机号','org');
			return $result;
		}

		$data['mobile'] = $params['mobile'];
		$mobileCode = str_replace(' ','',$params['mobileCode']);
        if(verify_api::verifyMobile($params['mobile'],$mobileCode)==false){
            $result->error=SLanguage::tr('手机验证码错误','org');
            $result->field="mobileCode";
            return $result;
        }
		$data['fk_org'] = $orgId;
		$data['create_time'] = date('Y-m-d H:i:s',time());	
		$data['region_level0'] = !empty($params['region_level0']) ? $params['region_level0'] : 0;
		$data['region_level1'] = !empty($params['region_level1']) ? $params['region_level1'] : 0;
		$data['region_level2'] = !empty($params['region_level2']) ? $params['region_level2'] : 0;
		
		$addRet = user_organization::addOrgCard($data);	
		if(!empty($addRet)){
			return $result;
		}else{
			$result->error="添加失败!";
            $result->field="add";
            return $result;
		}
	}
	
	//结算列表
	public function pageAccountList()
	{
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$length  = 20;
		$path = '/org/settle/accountlist?';
		
		if(!empty($_REQUEST['time'])){
			$params['time'] = $_REQUEST['time'];
            $path .= "&time={$params['time']}";
		}else{
			$params['time'] = '';
		}
		
		$params['orgId'] = $this->orgId;
		$accountClearRes = org_api::getClearByOrgId($params,$page,$length);
		$list = array();
		if(!empty($accountClearRes->data->items)){
			foreach($accountClearRes->data->items as $val){
				$newYear = date("Y");
				$createtYear = date("Y",strtotime($val->create_time));
				$endYear     = date("Y",strtotime($val->end_time));
				$list[] = [
					'clearId'    => $val->pk_clearing,
					'createTime' => ($newYear > $endYear) ? date('Y年m月d日',strtotime($val->create_time)): date('m月d日',strtotime($val->create_time)),
					'endTime'    => ($newYear > $endYear) ? date('Y年m月d日',strtotime($val->end_time)): date('m月d日',strtotime($val->end_time)),
					'orderCount' => $val->order_count, 
					'price_promote' => $val->resell_pay_price/100,
					'price_resell' => $val->resell_price/100,
					'course_price' => $val->course_price/100,
					'member_price' => $val->member_price/100,
					'price'      => $val->price/100,
					'isNew'      => !empty($val->isNew) ? 1 : 0,
					'tax_rate_price' => $val->tax_rate_price/100,
					'platform_rate_price' => $val->platform_rate_price/100,
					'gateway_rate_price' => $val->gateway_rate_price/100,
					'distribute_rate_price' => $val->distribute_rate_price/100,
				];
			}
		}

		if(!empty($accountClearRes->data)){
			$totalPage = $accountClearRes->data->totalPage;
		}else{
			$totalPage = 0;
		}
		
		//结算总数
		$orderGroup = array();
		$orderincome= array();
		$promotePrice= array();
		$resellPrice= array();
		$gatewayPrice = array();
		$platformPrice = array();
		$taxPrice = array();
		$distributePrice = array();
		$unAccount  = array();
		$orderContentParam = [
			//'contentOrderArr' => $contentOrderId,
			'clearingId' => 0,
			'orgId'=>$this->orgId,
			'status'=>2,
		];
		$orderRes = org_api::getOrderInfo($orderContentParam,0,0);
		if(!empty($orderRes->items)){
			foreach($orderRes->items as $val){
				$gatewayPrice[$val->fk_order] = $val->gateway_price;
				$platformPrice[$val->fk_order] = $val->platform_price;
				$taxPrice[$val->fk_order] = $val->tax_price;
				$distributePrice[$val->fk_order] = $val->distribute_price;
				$orderGroup[$val->fk_order]  = count($val->fk_order);
				if($val->promote_status==1){
					$orderincome[$val->fk_order] = $val->price_promote-($val->gateway_price+$val->platform_price+$val->tax_price+$val->distribute_price);
					$promotePrice[$val->fk_order] = $val->price-$val->price_promote;
				}else{
					$orderincome[$val->fk_order] = $val->price-($val->gateway_price+$val->platform_price+$val->tax_price+$val->distribute_price);
					$promotePrice[$val->fk_order]=0;
				}

			}
		}
		$orderContentParam = [
			//'contentOrderArr' => $contentOrderId,
			'clearingId' => 0,
			'orgId'=>$this->orgId,
			'resellOrgId'=>$this->orgId,
			'status'=>2,
		];
		$orderRes = org_api::getOrderInfo($orderContentParam,0,0);
		if(!empty($orderRes->items)){
			foreach($orderRes->items as $val){
				$gatewayPrice[$val->fk_order] = $val->gateway_price_resell;
				$platformPrice[$val->fk_order] = $val->platform_price_resell;
				$taxPrice[$val->fk_order] = $val->tax_price_resell;
				$resellPrice[$val->fk_order] = ($val->price-$val->price_promote)-($val->gateway_price_resell+$val->platform_price_resell+$val->tax_price_resell);
			}
		}
		$unAccount = [
			'orders' => array_sum($orderGroup),
			'income' => array_sum($orderincome)/100+array_sum($resellPrice)/100,
			'promotePrice'=>array_sum($promotePrice)/100,
			'resellPrice'=>array_sum($resellPrice)/100,
			'gatewayPrice'=>array_sum($gatewayPrice)/100,
			'platformPrice'=>array_sum($platformPrice)/100,
			'taxPrice'=>array_sum($taxPrice)/100,
			'distributePrice'=>array_sum($distributePrice)/100,
		];

		$unparams = ['status'=>2,'clearingId'=>0,'orgId'=>$this->orgId];
		$domainConf = SConfig::getConfig(ROOT_CONFIG."/const.conf","platform");
		$this->assign('platformUrl',$domainConf->platform);
		$this->assign('unparams',json_encode($unparams));
		$this->assign('list',$list);
		$this->assign('unAccount',$unAccount);
		$this->assign('totalPage',$totalPage);
		$this->assign('length',$length);
		$this->assign('createTime',$params['time']);
		$this->assign('path',$path);
		$this->assign('page',$page);
		$this->assign('time',$params['time']);
		$this->assign('orgId',$this->orgId);
	
		$this->render('org/settle.list.html');
	}
	
	//结算明细
	public function pageAccountInfo($inPath)
	{
		$orgId = $this->orgId;
		if(empty($inPath[3])) $this->redirect("/site.main.404");
		
		//结算总数
		$clearId = (int)$inPath[3];
		$clearRes = org_api::getClearByClaerId($clearId,$orgId);
		if(empty($clearRes->data)) $this->redirect("/site.main.404");
		
		$clearData = [
			'endTime'   => date('Y-m-d',strtotime($clearRes->data->end_time)),
			'price_promote' => $clearRes->data->resell_pay_price/100,
			'price_resell' => $clearRes->data->resell_price/100,
			'member_price' => $clearRes->data->member_price/100,
			'course_price' => $clearRes->data->course_price/100,
			'price' => $clearRes->data->price/100,
			'resell_pay_price' => $clearRes->data->resell_pay_price/100,
			'resell_price' => $clearRes->data->resell_price/100,
			'tax_rate_price'     => $clearRes->data->tax_rate_price/100,
			'platform_rate_price'     => $clearRes->data->platform_rate_price/100,
			'gateway_rate_price'     => $clearRes->data->gateway_rate_price/100,
			'distribute_rate_price'     => $clearRes->data->distribute_rate_price/100,
			'startTime' => date('Y-m-d',strtotime($clearRes->data->create_time)),
			'clearId'   => $clearId
		];
		
		$params = [
			'status'    => 2,
			'endTime'   => $clearRes->data->end_time,
			'startTime' => $clearRes->data->create_time,
			'clearingId'=>$clearId,
			'orgId'=>$orgId
		];
		$this->assign('clearData',$clearData);
		$this->assign('orgId',$orgId);
		$this->assign('params',json_encode($params));
		$this->render('org/settle.settled.html');
	}

	//退费记录
	public function pageRefund($inPath){
		$page = !empty($_GET["p"])?$_GET["p"]:1;
		$length = 10;
		$path = '/org/settle/refund?';
		if(!empty($_REQUEST['st'])){
			$params['time'][0] = $_REQUEST['st'];
			$path .= "&st={$params['time'][0]}";
		}else{
			$params['time'] = array();
		}
		if(!empty($_REQUEST['et'])){
			$params['time'][1] = $_REQUEST['et'];
			$path .= "&et={$params['time'][1]}";
		}else{
			$params['time'] = array();
		}
		if(isset($_REQUEST["status"])){
			$params["status"] = $_REQUEST["status"];
		}else{
			$params["status"] = 'all';
		}
		$params['orgId'] = $this->orgId;
		$orderRefund = order_api::getOrderRefundList($page,$length,$params);
		$this->assign('length', $length);
		$this->assign('page', $page);
		$this->assign('totalPage', $orderRefund["total"]);
		$this->assign('totalSize', $orderRefund["totalSize"]);
		$this->assign('list', $orderRefund["data"]);
		$this->assign('params', $params);
		$this->assign('path', $path);
		$this->render("org/settle.refund.html");
	}

}

