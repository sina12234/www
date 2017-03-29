<!DOCTYPE html>
<html>

<head>
<title>我的收藏 - 云课 - 专业的在线学习平台</title>
<meta name="title" content="我的收藏 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/index.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
<script tyee="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/scorll.js'); ?>" ></script>
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
    <div class="col-md-16 s-my-collectcont">
    <!--mob-->
    <p class="mob-nav hidden-lg hidden-md">
    <a href="/index.growth.entry" class="col-sm-5 col-xs-4">首页</a>
    <a href="/index.student.course" class="col-xs-5 col-sm-5">我的课程</a>
    <a href="/index.student.order" class="col-xs-5 hidden-xs hidden-sm">我的订单</a>
    <a href="/index.student.fav" class="col-xs-5 col-sm-5">我的收藏</a>
    <a href="/index.student.discount" class="col-xs-5 col-sm-5">我的优惠券</a>
    </p>
        <div class="right-content">
			<!-- 按钮切换 -->
				<dl class="list-tab fs14 col-xs-12 col-sm-20 collect-btn">
					<dd class="col-sm-3 col-xs-20 curr">收藏课程<i></i></dd>
                    <!--
					<dd class="col-sm-3 col-xs-5">喜欢的老师<i></i></dd>
                    -->
				</dl>
			<!-- /按钮切换 -->
			<!-- 切换内容 -->
			<ul id="list">
				<li id = "isEnd">
				<!--  -->
					<div class="cy-tab-content">
		                  <div class="litit fs14 hidden-xs mb20">
		                    <div class="col-md-8 col-sm-6">课程信息</div>
		                    <div class="col-md-4 col-sm-5">金额</div>
		                    <div class="col-md-4 col-sm-5">评价</div>
		                    <div class="col-md-4 col-sm-4">操作</div>
		                  </div>
		            </div>

					<!--
					<p class="col-sm-20 col-xs-12 s-pdr32 lh30" style="text-align:right;">共收藏（<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['favCount'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['favCount']; ?><?php }else{; ?>0<?php }; ?>）个课程</p>
					-->
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['data'])){; ?>
				    <?php foreach(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['data'] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
					<div class="list-c2 scorll">
						<div class="collect-list-content">
							<div class="rt-colect-content col-xs-20">
								<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_url']; ?>" class="pic col-sm-4 col-xs-20 c-fl col-lg-4">
									<img src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['thumb_med']; ?>" alt="">
									<?php if(SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 3){; ?>
									<span class="lineclass-icon fs12">线下课</span>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 2)){; ?>
									<span class="record-icon fs12">录播课</span>
									<?php }; ?>
                                </a>
								<div class="c-fr col-md-16 col-sm-16 col-xs-20 s-pd0 col-lg-16">
									<div class="s-collect-content-tp col-xs-20">
										<div class="col-lg-6 intro col-sm-5 col-xs-8 s-pd0">
											<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_url']; ?>" class="fs16 s-pd0 col-sm-20"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['title']; ?></a>
											<p><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['cate_name']; ?>  <?php echo SlightPHP\Tpl::$_tpl_vars["v"]['subject_name']; ?> <?php /*| 共<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['section_count']; ?>章*/?></p>
										</div>
										<div style="text-align:center;" class="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['fee_class']; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['fee_type']; ?></div>
										<div class="col-sm-8 col-lg-7 col-xs-3 hidden-xs mt8">
											<div class="col-sm-17 s-my-collectcont ml10 col-xs-12 s-collect-start">
												<dl class="c-fl collect-pingfen">
													<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['scoreType']; ?>
												</dl>
												<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['score']; ?>
											</div>
											
										</div>
									<a cid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_id']; ?>" cname="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['title']; ?>" class="col-lg-4 col-sm-4 cancle-collect-btn mt30 col-xs-8">取消收藏</a>
									</div>
									<div class="s-collect-content-ft col-xs-20" >
										<div class="fs14 col-sm-8 col-xs-12 pdl0">
											<span class="client-logo-name"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['subdomain']; ?></span>
										</div>
										<div class="col-sm-7 col-xs-8 pd0 hidden-xs"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['commNum']; ?></div>
										<div class="col-sm-5 col-xs-8 fs16 pd0"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['admin_status']; ?></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php }; ?>
					<?php }else{; ?>
					<div class="my-collect-no-class c-fl col-lg-16 col-lg-offset-2 col-sm-20 col-md-20">
						<img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>" alt="">
						<p>还没有收藏的课程哦~！ 快去<a href="/">首页</a>看看吧</p>
					</div>
					<?php }; ?>

				</li>
				<!--喜欢的老师 ing-->
				<li>
					<p class="col-sm-20 col-xs-12 s-pdr32" style="text-align:right;">共喜欢（8）位老师
					</p>
					<div class="list-c2">
					<!-- list -->
						<div class="collect-list-content like-list-content">
						<!-- rt -->
							<a href="#" class="pic col-sm-4 col-xs-3 c-fl">
								<img src="https://f3.gn100.com/4,054ec86eefa5" alt="">
							</a>
						<!-- /rt -->
						<!-- lt -->
							<div class="c-fr col-md-16 col-xs-9 s-pd0 s-new-lh30">
								<div class="col-sm-8">
									<h1 class="fs16">
										<a href="#" class="c-fl">张发英</a>
										<span class="c-fl col-sm-6 s-jigou fs12">高能100</span>
									</h1>
									<p>北京 5年教龄</p>
									<p>科目：数学</p>
									<p>毕业于北京师范大学</p>
								</div>
								<div class="col-sm-6">
									<p class="fs14">
										<span class="fs16 new-price-sub">4.8分</span>&nbsp;
										<span class="new-price-sub">9</span>人评论
									</p>
									<p>2个课程&nbsp;476名学生</p>
								</div>
								<div class="col-sm-6">
									<p class="fs14">最新开课</p>
									<p><a href="#" title="">奥数的秘密</a></p>
									<p><a href="#" title="">奥数的秘密</a></p>
									<p><a href="#" title="">奥数的秘密</a></p>
								</div>
							</div>
						<!-- /lt -->
						</div>
					<!-- /list -->
						<div class="collect-list-content like-list-content">
						<!-- rt -->
							<a href="#" class="pic col-sm-4 col-xs-3 c-fl">
								<img src="https://f3.gn100.com/4,054ec86eefa5" alt="">
							</a>
						<!-- /rt -->
						<!-- lt -->
							<div class="c-fr col-md-16 col-xs-9 s-pd0 s-new-lh30">
								<div class="col-sm-8">
									<h1 class="fs16">
										<a href="#" class="c-fl">张发英</a>
										<span class="c-fl col-sm-6 s-jigou fs12">高能100</span>
									</h1>
									<p>北京 5年教龄</p>
									<p>科目：数学</p>
									<p>毕业于北京师范大学</p>
								</div>
								<div class="col-sm-6">
									<p class="fs14">
										<span class="fs16 new-price-sub">4.8分</span>&nbsp;
										<span class="new-price-sub">9</span>人评论
									</p>
									<p>2个课程&nbsp;476名学生</p>
								</div>
								<div class="col-sm-6">
									<p class="fs14">最新开课</p>
									<p><a href="#" title="">奥数的秘密</a></p>
									<p><a href="#" title="">奥数的秘密</a></p>
									<p><a href="#" title="">奥数的秘密</a></p>
								</div>
							</div>
						<!-- /lt -->
						</div>
						<div class="collect-list-content like-list-content">
						<!-- rt -->
							<a href="#" class="pic col-sm-4 col-xs-3 c-fl">
								<img src="https://f3.gn100.com/4,054ec86eefa5" alt="">
							</a>
						<!-- /rt -->
						<!-- lt -->
							<div class="c-fr col-md-16 col-xs-9 s-pd0 s-new-lh30">
								<div class="col-sm-8">
									<h1 class="fs16">
										<a href="#" class="c-fl">张发英</a>
										<span class="c-fl col-sm-6 s-jigou fs12">高能100</span>
									</h1>
									<p>北京 5年教龄</p>
									<p>科目：数学</p>
									<p>毕业于北京师范大学</p>
								</div>
								<div class="col-sm-6">
									<p class="fs14">
										<span class="fs16 new-price-sub">4.8分</span>&nbsp;
										<span class="new-price-sub">9</span>人评论
									</p>
									<p>2个课程&nbsp;476名学生</p>
								</div>
							</div>
						<!-- /lt -->
						</div>
					</div>
				</li>
				<!--喜欢的老师 end-->
			</ul>
			<!-- 切换内容 -->
		</div>
	</div>
<!-- /右侧内容 -->
      </div>
    </div>
</section>
<!--studentPortal end-->
<?php echo tpl_function_part("/index.main.footer"); ?>
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
				<div class="c-fr col-md-16 col-xs-9 s-pd0">
					<div class="s-collect-content-tp">
						<div class="intro col-sm-5 col-xs-3 s-pd0">
                            <a href="<<course_url>>" class="fs16 s-pd0 col-sm-20"><<title>></a>
							<p><<grade_name>> <<cate_name>> | 共<<section_count>>章</p>
						</div>
						<div style="text-align:center;" class="<<fee_class>>"><<fee_type>></div>
						<div class="col-sm-6 col-xs-3 hidden-xs mt8">
							<div class="col-sm-20 col-xs-12 s-collect-start">
								<dl class="c-fl collect-pingfen">
								 <<&scoreType>>
								</dl>
							<<&score>>
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
	$('#list li').first().show();
	$('.list-tab>dd').click(function() {
		$(this).addClass('curr').siblings().removeClass('curr');
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
				$.post("/index.student.FavCourseAjax",{ page:page },function(r){
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

//if($(window).width()<768){
     //alert('1')
     //$('.layui-layer-dialog').css( "left","10" ) 
//};


	$(".collect-list-content .cancle-collect-btn").each(function() {
		$(this).click(function() {
			var cid   = $(this).attr("cid");
			var cname = $(this).attr("cname");
			layer.confirm("是否取消收藏《"+cname+"》课程", {
				btn:['确定','取消'],
				title:['取消收藏','color:#fff;background:#02a1e5']
			}, function(){
				$.post("/index.student.delFav", { "cid": cid }, function (r) {
                    if(r.error)
					{
                        layer.msg(r.error);
                    }else
					{
                        window.location.reload();
                    }
                }, "json");
			});
		});
	})

});
</script>
</html>
