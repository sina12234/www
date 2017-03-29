<!DOCTYPE html>
<html>
<head>
<title>会员专区 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 全部课程 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript">
var COOKIE_UID_NAME="<?php echo COOKIE_UID_NAME; ?>";
</script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/user.js'); ?>"></script>
</head>
<body>
<!-- hd -->
<?php echo tpl_function_part("/site.main.nav.member"); ?>
<!-- /hd -->
	<section id="y-open-member">
<!-- memberBg -->
		<div class="open-member-bg col-sm-20">
			<img src="<?php echo utility_cdn::img('/assets_v2/img/open-member-img.jpg'); ?>" alt="" class="visible-lg">
			<img src="<?php echo utility_cdn::img('/assets_v2/img/m-open-member-img.png'); ?>" alt="" class="visible-xs">
		</div>
<!-- /memberBg -->
<!-- list -->
		<div class="wrap-open-member">
			<div class="open-member-content container">
				<div class="row">
					<ul class="col-md-20">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["msetList"])){; ?>
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["msetList"] as SlightPHP\Tpl::$_tpl_vars["so"]){; ?>
						<li class="bor1px mt20 col-md-20 pd0 bgf">
							<div class="col-md-5 list-member-lt pt40 pdb30">
								<h1 class="fs24 tac"><?php echo SlightPHP\Tpl::$_tpl_vars["so"]->title; ?></h1>
								<h2 class="fs14 mt40">
									<span class="c-fl">包含课程数:<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->course_count; ?></span>
									<a href="/course.list?ms=<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->pk_member_set; ?>" target="_blank" title="" class="c-fr">查看课程范围></a>
								</h2>
							</div>
							<div class="col-md-12 pd0">
								<div class="tac mt30 cBlue_2 disPriceList">
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["so"]->price_30)){; ?>
									<span class="fs24 col-md-10 pd0">
										<em class="fs18">¥</em>
										<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->price_30/100; ?>
										<em class="fs18">/30天</em>
									</span>
									<?php }; ?>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["so"]->price_90)){; ?>
									<span class="fs24 col-md-10 pd0">
										<em class="fs18">¥</em>
										<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->price_90/100; ?>
										<em class="fs18">/90天</em>
									</span>
									<?php }; ?>
								</div>
								<div class="tac mt30 cBlue_2 disPriceList">
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["so"]->price_180)){; ?>
									<span class="fs24 col-md-10 pd0">
										<em class="fs18">¥</em>
										<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->price_180/100; ?>
										<em class="fs18">/180天</em>
									</span>
									<?php }; ?>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["so"]->price_360)){; ?>
									<span class="fs24 col-md-10 pd0">
										<em class="fs18">¥</em>
										<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->price_360/100; ?>
										<em class="fs18">/360天</em>
									</span>
									<?php }; ?>
								</div>
								<p class="fs14 cDark_9 mt15 col-xs-20 col-md-20">
									<span class="col-md-3 pd0 tac">会员描述：</span>
									<span class="col-md-17 pd0" title="<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->descript; ?>">
										<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->descript_part; ?>
									</span>
								</p>
							</div>
							<div class="col-md-3 tac">
							<?php if(SlightPHP\Tpl::$_tpl_vars["so"]->is_open == 0){; ?>
								<a href="javascript:;"   title="">
									<button class="btn open-member mt40 mb20" data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->pk_member_set; ?>">马上开通</button>
								</a>
							<?php }else{; ?>
								<a href="javascript:;" data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->pk_member_set; ?>" title="">
									<button  class="btn open-member mt40 mb20" data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->pk_member_set; ?>">立即续费</button>
								</a>
							<?php }; ?>
							</div>
						</li>
						<?php }; ?>
					<?php }; ?>
					</ul>
				</div>	
			</div>
		</div>	
<!-- /list -->
	</section>
	
	<script>
		function checkLogin() {
			var w,h;
			if($(window).width()>780){
				w='476px';
				h='360px';
			}else{
				w='90%';
				h='360px';
			}
			if (userApi.isWeiXin()) {
				location.href='/site.main.login/?url=//'+location.hostname+"/member/list";
			} else {
				// 微信直接报名登陆
				if (userApi.isLogin()) return true;
				if($(window).width()<780){
					location.href='/site.main.login/?url=//'+location.hostname+"/member/list";
				}else{
					layer.open({
						type: 2,
						title:false,
						area: [w, h],
						shadeClose: true,
						content: ['/layer.main.userLogin', 'no']
					});
					return false;
				}
			}
		}
		$(function() {
			$(".open-member").bind("click" ,function() {
				var w,h;
				var setId = $(this).attr('data-id');
				if (checkLogin()) {
					window.location.href = '/order.main.memberinfo/'+setId;
				}
			});

			$('.disPriceList').each(function() {
				var disPriceList = $(this).find('span').length;

				if(disPriceList == 1) {
					$(this).find('span').removeClass('col-md-10')
				}else {
					return false;
				}
			})
			
				
		});
	
	
	
	</script>
<!-- ft -->
<?php echo tpl_function_part("/site.main.footer"); ?>
<!-- /ft -->
</body>
</html>

