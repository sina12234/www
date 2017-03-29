<?php
class index_help extends STpl{

	public function __construct($inPath){
		//utility_cache::pageCache(3600);
	}
	public function pageEntry($inPath){
		$this->assign('current','register');
		
		$this->render('index/help.register.html');
	}
	//如何登陆和注册
	public function  pageRegister($inPath){
		$this->assign('current','register');
		$this->render('index/help.register.html');
	}
	//修改个人资料
	public function  pageEdit($inPath){
		$this->assign('current','edit');
		$this->render('index/help.edit.html');
	}
	//上课无法语音说话
	public function  pageUnableToSpeak($inPath){
		$this->assign('current','unabletospeak');
		$this->render('index/help.unabletospeak.html');
	}


	//怎样支付
	public function  pageHowToPay($inPath){
		$this->assign('current','howtopay');
		$this->render('index/help.howtopay.html');
	}
	//怎样上课
	public function  pageHowToClass($inPath){
		$this->assign('current','howtoclass');
		$this->render('index/help.howtoclass.html');
	}
	//我的课程
	public function  pageMyCourse($inPath){
		$this->assign('current','mycourse');
		$this->render('index/help.mycourse.html');
	}
	//优惠券
	public function  pageCoupon($inPath){
		$this->assign('current','coupon');
		$this->render('index/help.coupon.html');
	}


	//怎样教课
	public function  pageHowToTeach($inPath){
		$this->assign('current','howtoteach');
		$this->render('index/help.howtoteach.html');
	}
	//资料维护
	public function  pageEditInfo($inPath){
		$this->assign('current','editinfo');
		$this->render('index/help.editinfo.html');
	}

	//入驻云课
	public function  pageJoin($inPath){
		$this->assign('current','join');
		$this->render('index/help.join.html');
	}
	//首页维护
	public function  pageIndexManage($inPath){
		$this->assign('current','indexmanage');
		$this->render('index/help.indexmanage.html');
	}
	//课程管理
	public function  pageCourseManage($inPath){
		$this->assign('current','coursemanage');
		$this->render('index/help.coursemanage.html');
	}
	//学生和老师管理
	public function  pagePersonManage($inPath){
		$this->assign('current','personmanage');
		$this->render('index/help.personmanage.html');
	}
	//订单
	public function  pageOrder($inPath){
		$this->assign('current','order');
		$this->render('index/help.order.html');
	}
	//结算管理
	public function  pageSettle($inPath){
		$this->assign('current','settle');
		$this->render('index/help.settle.html');
	}
	//推广规则
	public function  pageExtension($inPath){
		$this->assign('current','settle');
		$this->render('index/help.extension.html');
	}
	//分销课程
	public function  pageDistribution($inPath){
		$this->assign('current','distribution');
		$this->render('index/help.distribution.html');
	}
	public function  pageMenu($inPath){
		$uri = $_SERVER['REQUEST_URI'];
		$tmp = explode('.', $uri);
		$type = isset($tmp[2]) ? $tmp[2] : 'register';

		$this->assign('type',$type);
		$this->render('index/help.menu.html');
	}
	public function pageFlash($inPath){
        $this->render('index/help.flash.test.html');
    }


}