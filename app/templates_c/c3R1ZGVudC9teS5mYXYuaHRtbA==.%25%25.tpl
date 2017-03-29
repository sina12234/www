<!DOCTYPE html>
<html>
<head>
<title>我的收藏 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 我的收藏 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>"></script>
<script tyee="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/scorll.js'); ?>" ></script>
</head>
<body>
<?php echo tpl_function_part("/site.main.usernav.student"); ?>
<!-- mob nav -->
<div class="mob-nav hidden-lg hidden-md">
    <p class="swiper-wrapper" id="mob-nav">
        <a href="/student.main.growth" class="swiper-slide">我的首页</a>
        <a href="/student.course.mycourse" class="swiper-slide">我的课程</a>
        <a href="/student.order.myorder" class="swiper-slide">我的订单</a>
        <a href="/student.fav.myfav" class="swiper-slide active">我的收藏</a>
        <a href="/student.discount.mydiscount" class="swiper-slide">我的优惠券</a>
    </p>
</div>
<!--studentPortal start-->
<section class="pd30">
    <div class="container">
      <div class="row">
        <!-- leftmenu start-->
		<?php echo tpl_function_part("/user.main.menu.student.myfav"); ?>
        <!-- leftmenu end -->

       <!-- 右侧内容 -->
	<div class="right-main col-md-16 col-xs-20">
    <div class="content">
		<!-- 按钮切换 -->
			<div class="tab-main">
                <div class="tab-hd">
                	<a href="javascript:;" class="tab-hd-opt curr"><?php echo tpl_modifier_tr('收藏课程','LearningCenter'); ?></a>
                	<!--a href="javascript:;" class="tab-hd-opt"><?php echo tpl_modifier_tr('喜欢的老师','LearningCenter'); ?></a-->
                </div>
            </div>
		<!-- /按钮切换 -->
		<!-- 切换内容 -->
		<ul id="list" class="mt10">
			<li>
			<!--
				<p class="col-sm-12 col-xs-12 s-pdr32" style="text-align:right;">
					共收藏（<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['favCount'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['favCount']; ?><?php }else{; ?>0<?php }; ?>）个课程
				</p>
			-->
					<div class="cy-tab-content">
		                  <div class="litit fs14 hidden-xs mb20">
		                    <div class="col-md-9 col-sm-6"><?php echo tpl_modifier_tr('课程信息','LearningCenter'); ?></div>
		                    <div class="col-md-3 col-sm-5"><?php echo tpl_modifier_tr('金额','LearningCenter'); ?></div>
		                    <div class="col-md-4 col-sm-5"><?php echo tpl_modifier_tr('评价','LearningCenter'); ?></div>
		                    <div class="col-md-4 col-sm-4 pdl30"><?php echo tpl_modifier_tr('操作','LearningCenter'); ?></div>
		                  </div>
		            </div>
				<div class="list-c2">
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['data'])){; ?>
				<?php foreach(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['data'] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
				<div class="rt-colect-content clearfix">
					<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_url']; ?>" class="pic col-sm-5 col-md-5 col-xs-20 c-fl">
						<img src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['thumb_med']; ?>" width="100%" alt="">
                        <?php if(SlightPHP\Tpl::$_tpl_vars["v"]['course_type']==3){; ?>
                         <div class="taped-icon"><?php echo tpl_modifier_tr('线下','LearningCenter'); ?></div>
                        <?php }elseif((SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 2)){; ?>
						<div class="g-icon3"><?php echo tpl_modifier_tr('录播','LearningCenter'); ?></div>
						<?php }; ?>
					</a>
					<div class="c-fr col-md-15 col-sm-15 col-xs-20 s-pd0 pdl0">
						<div class="s-collect-content-tp">
							<div class="col-sm-6 col-md-6 col-xs-8 s-pd0 pdl0">
								<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_url']; ?>" class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['title']; ?></a>
								<p><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["v"]['cate_name'],'course.list'); ?> <?php echo SlightPHP\Tpl::$_tpl_vars["v"]['subject_name']; ?> | <?php echo tpl_modifier_tr('共','LearningCenter'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['section_count']; ?><?php echo tpl_modifier_tr('章','LearningCenter'); ?></p>
							</div>
							<div class="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['fee_class']; ?>" style="text-align:center;"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['fee_type']; ?></div>
							<div class="hidden-md col-md-7 col-sm-7 hidden-xs mt30">
								<div class="col-sm-17 s-my-collectcont ml10">
									<dl class="c-fl collect-pingfen">
										<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['scoreType']; ?>
									</dl>
									<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['score']; ?>
								</div>
								<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['commNum']; ?>
							</div>
							<a cid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_id']; ?>" cname="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['title']; ?>" class="cancle-collect-btn tec c-fr col-sm-4 col-xs-6 mt30"><?php echo tpl_modifier_tr('取消收藏','LearningCenter'); ?></a>
						</div>
						<div class="s-collect-content-ft">

							<span class="c-fr fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['admin_status']; ?></span>
						</div>
					</div>
				</div>
				<?php }; ?>
				<?php }else{; ?>
					<div class="my-collect-no-class col-md-20 col-sm-20 col-xs-20">
						<img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>" alt="">
						<p style="font-weight:bold;color:#666;"><?php echo tpl_modifier_tr('您还没有收藏的课程哦','LearningCenter'); ?>~!<?php echo tpl_modifier_tr('快去','LearningCenter'); ?><a href="/"><?php echo tpl_modifier_tr('首页','LearningCenter'); ?></a><?php echo tpl_modifier_tr('看看吧','LearningCenter'); ?></p>
					</div>
				<?php }; ?>
				</div>
			</li>
			<li style="display:none">
				<p class="col-sm-20 col-xs-20 s-pdr32" style="text-align:right;">共喜欢（8）位老师
				</p>
				<div class="list-c2">
				<!-- list -->
					<div class="collect-list-content like-list-content">
					<!-- rt -->
						<a href="#" class="pic col-sm-3 col-md-3 col-xs-3 c-fl">
							<img src="https://f3.gn100.com/4,054ec86eefa5" alt="">
						</a>
					<!-- /rt -->
					<!-- lt -->
						<div class="c-fr col-md-17 col-sm-17 col-xs-17 s-pd0 s-new-lh30">
							<div class="col-sm-6 col-md-6 col-xs-6">
								<h1 class="fs16">
									<a href="#" class="c-fl">张发英</a>
									<span class="c-fl col-sm-4 s-jigou fs12">高能100</span>
								</h1>
								<p>北京 5年教龄</p>
								<p>科目：数学</p>
								<p>毕业于北京师范大学</p>
							</div>
							<div class="col-sm-6 col-xs-6 col-md-6">
								<p class="fs14">
									<span class="fs16 new-price-sub">4.8分</span>&nbsp;
									<span class="new-price-sub">9</span>人评论
								</p>
								<p>2个课程&nbsp;476名学生</p>
							</div>
							<div class="col-sm-5 col-md-5 col-xs-5">
								<p class="fs14">最新开课</p>
								<p><a href="#" title="">奥数的秘密</a></p>
								<p><a href="#" title="">奥数的秘密</a></p>
								<p><a href="#" title="">奥数的秘密</a></p>
							</div>
						</div>
					<!-- /lt -->
					</div>
				<!-- /list -->
				</div>
			</li>
		</ul>
		<!-- 切换内容 -->
	</div>
</div>
<!-- /右侧内容 -->
      </div>
    </div>
</section>
<!--studentPortal end-->
<div id="rightWindow"></div>
<script>$("#rightWindow").load("/org.rightWindow.rightWindow");</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/mustache.js'); ?>"></script>
<script id='courseListisend' type='text/template'>
<<#data>>
	<div class="list-c2 scorll">
		<div class="collect-list-content">
			<div class="rt-colect-content">
				<a href="<<course_url>>" class="pic col-sm-4 col-xs-3 c-fl">
					<img src="<<thumb_med>>" alt="">
				</a>
				<div class="c-fr col-md-9 col-xs-9 s-pd0">
					<div class="s-collect-content-tp">
						<div class="intro col-sm-3 col-lg-3 col-xs-3 col-md-4 s-pd0">
                            <a href="<<course_url>>" class="fs16"><<title>></a>
							<p><<grade_name>> <<cate_name>> | 共<<section_count>>章</p>
						</div>
						<div style="text-align:center;" class="<<fee_class>>"><<fee_type>></div>
						<div class="hidden-md col-sm-5 col-xs-3 hidden-xs">
							<div class="col-sm-12 col-xs-12">
								<dl class="c-fl collect-pingfen">
									<<&scoreType>>
								</dl>
								<<&score>>
								<<&sc>>
							</div>
								<<&commNum>>
						</div>
					</div>
					<div class="s-collect-content-ft" >
						<span class="c-fl fs14"><<subdomain>></span>
						<span class="c-fr fs16 s-pdr32"><<admin_status>></span>
					</div>
				</div>
			</div>
		</div>
	</div>
<</data>>
</script>
<script>
$(function() {
    $('#list li').css('display','block');
    $('#list li').last().hide();
    $('.org-slide>dd').click(function() {
        $(this).addClass('slide-active').siblings().removeClass('slide-active');
        $('#list>li:eq(' + $(this).index() + ')').show().siblings().hide();
    });
	var page = 1;
	var arrend = [];
	var loadend = true;
	var domisend = $(".scorll:last")[0];
    arrend.push(domisend);

	$(window).scroll(function(){
		var tops = $(window).scrollTop();
		var contents = $(window).height();
		var prec1 = getClient();
		jiance(arrend,prec1,function(){
			if(loadend)
			{
				page++;
				$.post("/student.fav.FavCourseAjax",{ page:page },function(r){
					if(r.code == 0)
					{
						var courseListTpl = $('#courseListisend').html();
						var courseList1   = Mustache.render(courseListTpl, r);
						$('#isEnd').append(courseList1);
						domisend = $(".scorll:last")[0];
						arrend.push(domisend);
					}else
					{
						return false;
					}
				},'json');
			}
		});
	});

	$(".cancle-collect-btn").click(function() {
			var cid   = $(this).attr("cid");
			var cname = $(this).attr("cname");
			layer.confirm("<?php echo tpl_modifier_tr('是否取消收藏','LearningCenter'); ?>《"+cname+"》<?php echo tpl_modifier_tr('课程','LearningCenter'); ?>", {
				btn: ['<?php echo tpl_modifier_tr("确定","LearningCenter"); ?>','<?php echo tpl_modifier_tr("取消","LearningCenter"); ?>'],
				title:['<?php echo tpl_modifier_tr("取消收藏","LearningCenter"); ?>','color:#fff;background:#ffa81d']
			}, function(){
				$.post("/student.fav.delFav", { "cid": cid }, function (r) {
                    if(r.error)
					{
                        layer.msg(r.error);
                    }else
					{
						window.location.href=window.location.href;
                        //window.location.reload();
                    }
                }, "json");
			});
	});
});
</script>
</html>
