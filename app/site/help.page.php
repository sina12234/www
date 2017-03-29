<?php
class site_help extends STpl{

	public function __construct($inPath){
		utility_cache::pageCache(3600);
	}
	public function pageEntry($inPath){
		$this->assign('current','register');

		$this->render('site/help.register.html');
	}
	//如何登陆和注册
	public function  pageRegister($inPath){
		$this->assign('current','register');
		$this->render('site/help.register.html');
	}
	//修改个人资料
	public function  pageEdit($inPath){
		$this->assign('current','edit');
		$this->render('site/help.edit.html');
	}
	//上课无法语音说话
	public function  pageUnableToSpeak($inPath){
		$this->assign('current','unabletospeak');
		$this->render('site/help.unabletospeak.html');
	}


	//怎样支付
	public function  pageHowToPay($inPath){
		$this->assign('current','howtopay');
		$this->render('site/help.howtopay.html');
	}
	//怎样上课
	public function  pageHowToClass($inPath){
		$this->assign('current','howtoclass');
		$this->render('site/help.howtoclass.html');
	}
	//我的课程
	public function  pageMyCourse($inPath){
		$this->assign('current','mycourse');
		$this->render('site/help.mycourse.html');
	}
	//优惠券
	public function  pageCoupon($inPath){
		$this->assign('current','coupon');
		$this->render('site/help.coupon.html');
	}


	//怎样教课
	public function  pageHowToTeach($inPath){
		$this->assign('current','howtoteach');
		$this->render('site/help.howtoteach.html');
	}
	//资料维护
	public function  pageEditInfo($inPath){
		$this->assign('current','editinfo');
		$this->render('site/help.editinfo.html');
	}

	//入驻云课
	public function  pageJoin($inPath){
		$this->assign('current','join');
		$this->render('site/help.join.html');
	}
	//首页维护
	public function  pageIndexManage($inPath){
		$this->assign('current','indexmanage');
		$this->render('site/help.indexmanage.html');
	}
	//课程管理
	public function  pageCourseManage($inPath){
		$this->assign('current','coursemanage');
		$this->render('site/help.coursemanage.html');
	}
	//学生和老师管理
	public function  pagePersonManage($inPath){
		$this->assign('current','personmanage');
		$this->render('site/help.personmanage.html');
	}
	//订单
	public function  pageOrder($inPath){
		$this->assign('current','order');
		$this->render('site/help.order.html');
	}
	//结算管理
	public function  pageSettle($inPath){
		$this->assign('current','settle');
		$this->render('site/help.settle.html');
	}
	//推广规则
	public function  pageExtension($inPath){
		$this->assign('current','settle');
		$this->render('site/help.extension.html');
	}

	public function  pageMenu($inPath){
		$subnav = "";
		if (!empty($inPath[3])) {
			$subnav = $inPath[3];
		}

		$this->assign("subnav", $subnav);
		$this->render('site/help.menu.html');
	}

	public function pageFlash($inPath){
	    $this->render('site/help.flash.test.html');
	}


}
