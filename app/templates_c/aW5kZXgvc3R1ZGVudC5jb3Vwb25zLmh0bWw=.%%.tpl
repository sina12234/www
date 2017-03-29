<!DOCTYPE html>
<html>

<head>
<title>我的优惠券 - 云课 - 专业的在线学习平台</title>
<meta name="title" content="我的课程 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">

    <?php echo tpl_function_part("/index.main.header"); ?>
	 <script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>"></script>
	 <link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
	<script tyee="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/scorll.js'); ?>" ></script>
	<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
</head>
<body class="bgf7">
   <?php echo tpl_function_part("/index.main.usernav/student"); ?>
    <!--studentPortal start-->
    <section class="p20">
    <div class="container">
      <div class="row">
        <!-- leftmenu start-->
		<?php echo tpl_function_part("/index.main.menu/student"); ?>
        <!-- leftmenu end -->

        <!-- 右侧内容 -->

		<div class="col-lg-16 col-md-15">
		<!--mob-->
    <p class="mob-nav hidden-lg hidden-md">
        <a href="/index.growth.entry" class="col-xs-4">首页</a>
        <a href="/index.student.course" class="col-xs-5">我的课程</a>
        <a href="/index.student.order" class="col-xs-5 hidden-xs hidden-sm">我的订单</a>
        <a href="/index.student.fav" class="col-xs-5">我的收藏</a>
        <a href="/index.student.discount" class="col-xs-5">我的优惠券</a>
    </p>
			<div class="right-content">
			<h1 class="fs16 fob c-fl">我的优惠券</h1>
            <div class="list-search">
					<form action='/index.student.discount' method='post'>
						<button class="blue-btn" id="subsearch"></button>
						<input name="keyword" id="search" type="text" value="<?php echo SlightPHP\Tpl::$_tpl_vars["code"]; ?>" placeholder="查询优惠码">
						<i class="discount-delt-btn"></i>
					</form>
				</div>
				<ul class="list-coupons list-coupons2 p20 fs14">
					<li>
						<p class="col-lg-3 col-sm-4 hidden-xs">优惠券名称</p>
						<p class="col-lg-3 col-sm-3 col-xs-5 hidden-xs">优惠方式</p>
						<p class="col-lg-2 col-sm-3 col-xs-9">优惠码</p>
						<p class="col-lg-4 col-sm-4 col-xs-11">到期时间</p>
						<p class="col-lg-4 col-sm-3 hidden-xs">机构名称</p>
                        <p class="col-lg-4 col-sm-3 hidden-xs">使用状态</p>
					</li>
				   <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["data"]['data'])){; ?>
                   <?php foreach(SlightPHP\Tpl::$_tpl_vars["data"]['data'] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
						<li>
							<p class="col-lg-3 col-sm-4 hidden-xs"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['name']; ?></p>
							<p class="col-lg-3 col-xs-5 col-sm-3 hidden-xs"><?php if(1==SlightPHP\Tpl::$_tpl_vars["v"]['discount_type']){; ?>满<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['min_fee']/100; ?>减<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['discount_value']/100; ?><?php }else{; ?>满<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['min_fee']/100; ?>打<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['discount_value']/10; ?>折<?php }; ?></p>
							<p class="col-lg-2 col-xs-9 col-sm-3"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['discount_code']; ?></p>
							<p class="col-lg-4 col-sm-4 col-xs-11"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['endtime']; ?></p>
                            <p class="col-lg-4 col-sm-3 hidden-xs"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['owner_name']; ?></p>
                            <p class="col-lg-4 col-sm-3 hidden-xs"><?php if(SlightPHP\Tpl::$_tpl_vars["v"]['used_num']<0){; ?>未使用<?php }else{; ?>已使用<?php }; ?></p>
						</li>
				   <?php }; ?>
				   <?php }else{; ?>
				   <li>
						<div class="row">
							<div class="col-md-20 col-sm-20 col-sm-offset-0 tc course-schedule mt15">
								<div class="col-md-20 col-xs-20 mt15 fs14 tac">
									<img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>" class="c-fl col-xs-offset-9" />
									<p class="col-sm-20 col-xs-20" style="border:0;">您还没有使用的优惠券哦!</p>
								</div>
							</div>
						</div>
				   </li>
				   <?php }; ?>
				</ul>
						<div class="my-coupons-box c-fr fs14" id="my-coupons-box">
							优惠券使用须知：
							<dl class="my-coupons-tip mt10"><i></i>
								<dt>优惠规则：</dt>
								<dd>1.每位学员每次报名仅限使用一张优惠券；</dd>
								<dd>2.优惠券设有效期，超过有效期则自动失效；</dd>
								<dd>如果报名时没有使用优惠券，则视作自动放弃，优惠部分金额不返还；</dd>
							</dl>
						</div>
				<!-- 分页 -->
				<div class="page-list fs14">
					<div class="page-list" id="pagepage"></div>
				<!-- 分页 end -->
				</div>

		</div>

		<!-- 右侧内容 -->
      </div>
    </div>
	</section>
    <!--studentPortal end-->
	<?php echo tpl_function_part("/index.main.footer"); ?>

</body>
<script>
page("pagepage","<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["num"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['page']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['total']; ?>);
$(function() {

	$('#my-coupons-box').hover(function() {
		$(this).find(".my-coupons-tip").toggle();
	})
	
	var keyword = $("#search").val();
	if(keyword != ''){
		$(".discount-delt-btn").show();
	}
	$(".discount-delt-btn").click(function(){
		$("#search").val('');
		$(".discount-delt-btn").hide();
		location=location;
	});

});
</script>
</html>
