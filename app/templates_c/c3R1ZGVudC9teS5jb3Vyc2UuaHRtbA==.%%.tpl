<!DOCTYPE html>
<html>
<head>
<title>我的课程 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 我的课程 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/scorll.js'); ?>" ></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
</head>
<style>
#list>li:nth-child(1) { display: none;}
</style>
<body>
<!--header-->
<?php echo tpl_function_part("/site.main.nav3"); ?>
<!-- mob nav -->
<div class="g-nav hidden-lg hidden-md">
	<ul class="swiper-wrapper" id="mob-nav">
		<li class="swiper-slide"><a href="/student.main.growth">我的首页</a></li>
		<li class="swiper-slide"><a href="/student.course.mycourse" class="selected">我的课程</a></li>
		<li class="swiper-slide"><a href="/task.commitTask.studentTaskListShow">我的作业</a></li>
		<li class="swiper-slide"><a href="/student.order.myorder">我的订单</a></li>
		<li class="swiper-slide"><a href="/student.fav.myfav">我的收藏</a></li>
		<li class="swiper-slide"><a href="/student.discount.mydiscount">我的优惠券</a></li>
	</ul>
</div>
<section class="org-section">
    <div class="container">
        <div class="row">
            <!-- leftmenu start-->
		    <?php echo tpl_function_part("/user.main.menu.student.mycourse"); ?>
            <!-- leftmenu end -->
	        <!-- 我的课表 -->
            <div class="right-main col-md-16 col-xs-20" id="sc-wrap-my-course">
                <div class="tab-main">
                    <div class="tab-hd fs14" id="tab-hd">
                        <a href="javascript:;" class="tab-hd-opt"><?php echo tpl_modifier_tr('直播课表','LearningCenter'); ?></a>
                        <a href="javascript:;" class="tab-hd-opt curr"><?php echo tpl_modifier_tr('正在学习','LearningCenter'); ?>（<?php echo SlightPHP\Tpl::$_tpl_vars["course_count"]; ?>）</a>
                    </div>
                </div>
                <ul id="list" class="course-ct mt10">
            	<!-- 直播课程 -->
                    <li id="planList">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planlist"])){; ?>
						<?php foreach( SlightPHP\Tpl::$_tpl_vars["planlist"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                        <div class="student-course-month col-sm-17 hidden-xs">
                        	<i class="li-course-month col-sm-1 fs12"><?php echo SlightPHP\Tpl::$_tpl_vars["k"]; ?></i>
                        </div>
						<?php foreach( SlightPHP\Tpl::$_tpl_vars["v"] as SlightPHP\Tpl::$_tpl_vars["kk"]=>SlightPHP\Tpl::$_tpl_vars["vv"]){; ?>
                        <div class="my-course-contents col-sm-20 plan-list col-xs-20 pd0">
                            <div class="col-sm-3 col-xs-7 fs14 vertical-line">
                               	<?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->start_date; ?>
                                <span></span>
                                <i></i>
								<input type="hidden" name="start_day" value="<?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->start_day; ?>">
                            </div>
                            <div class="col-sm-17 col-xs-13 my-course-contents-rt p15">
                                <div class="col-sm-5">
									<a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->org_url; ?><?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->course_url; ?>" target="_blank">
                                    	<img src="<?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->thumb_sma; ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->course_name; ?>" />
                                    </a>
                                </div>
                                <div class="col-sm-9 infor-subject">
                                    <h1 class="fs16">
										<a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->org_url; ?><?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->course_url; ?>" target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->course_name; ?></a>
                                    </h1>
                                    <p>
									   <span><?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->class_name; ?></span> &nbsp;
    									<span><?php echo tpl_modifier_tr('讲师','LearningCenter'); ?>：<a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->org_url; ?><?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->teacher_url; ?>" target="_blank">
    									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["vv"]->teacher_real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->teacher_real_name; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->teacher_name; ?><?php }; ?></a>
    									</span>
                                    </p>
                                    <p><?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->section_name; ?></p>
									<div class="fs14 col-sm-20 col-xs-20">
										<a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->org_url; ?>" target='_blank'>
											<span class="c-fl client-logo-name">
												<?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->org_name; ?>
											</span>
										</a>
									</div>
                                </div>
                                <div class="col-sm-6 col-xs-12 infor-student-btn">
									<?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->plan_button; ?>
                                </div>
                            </div>
                        </div>
						<?php }; ?>
						<?php }; ?>
                        <?php }else{; ?>
                        <div class="my-collect-no-class c-fl col-lg-offset-0 col-xs-offset-0 col-sm-offset-0 col-sm-20 col-md-20 col-lg-20 col-xs-20">
                            <img class="mt40" src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>"  alt="" />
                            <p style="font-weight:bold;color:#666;"><?php echo tpl_modifier_tr('您还没有直播的课程哦','LearningCenter'); ?>~! <?php echo tpl_modifier_tr('快去','LearningCenter'); ?><a href="/"><?php echo tpl_modifier_tr('首页','LearningCenter'); ?></a><?php echo tpl_modifier_tr('看看吧','LearningCenter'); ?></p>
                        </div>
					<?php }; ?>
                    </li>
            <!-- /直播课程 -->
            <!-- 报名课程 -->
                    <li style="display:block;">
                        <div class="my-course-content col-sm-20 pd0">
                            <!-- 搜索 -->
								<form name="search" method="get" action="/student.course.mycourse">
                                <div class="right search-frame hidden-xs" style="padding:0;">
                                    <input placeholder="<?php echo tpl_modifier_tr('课程搜索','LearningCenter'); ?>" id="search-title" autocomplete="off" type="text" name="title" class="search-input" value="<?php echo SlightPHP\Tpl::$_tpl_vars["title"]; ?>"/>
                                    <div class="search-box">
                                        <span class="search-icon" id="form_sub" ></span>
                                        <div class="clear-icon search-delete-btn"></div>
                                    </div>
                                    <input type="hidden" name="del" value="<?php echo SlightPHP\Tpl::$_tpl_vars["del"]; ?>" />
                                </div>
								</form>
                            <!-- /搜索 -->
                            <ul class="my-course-content-rt col-sm-20 col-xs-20 pd0">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_course"])){; ?>
								<?php foreach(SlightPHP\Tpl::$_tpl_vars["user_course"] as SlightPHP\Tpl::$_tpl_vars["co"]){; ?>
                                <li class="col-sm-10 col-xs-10 my-course-item">
                                	<a href="//<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["co"]->subdomain)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["co"]->subdomain; ?><?php }; ?><?php echo SlightPHP\Tpl::$_tpl_vars["co"]->section_url; ?>" title="<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->title; ?>" target="_blank">
	                                    <div class="col-sm-10 col-xs-20 course-img col-md-10">
											<img src="<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->thumb_sma; ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->title; ?>" />
											<?php if(SlightPHP\Tpl::$_tpl_vars["co"]->type == 3){; ?>
	                                    	<span class="taped-icon"><?php echo tpl_modifier_tr('线下','LearningCenter'); ?></span>
                                            <?php }; ?>
											<?php if(SlightPHP\Tpl::$_tpl_vars["co"]->type == 2){; ?>
	                                    	<span class="g-icon3"><?php echo tpl_modifier_tr('录播','LearningCenter'); ?></span>
											<?php }elseif((SlightPHP\Tpl::$_tpl_vars["co"]->status == 3)){; ?>
	                                        	<span></span>
											<?php }elseif((SlightPHP\Tpl::$_tpl_vars["co"]->section_status == 2)){; ?>
	                                        	<span class="start-class"><?php echo tpl_modifier_tr('正在上课','LearningCenter'); ?></span>
											<?php }; ?>
											<!--课程来源-->
	                                    </div>
	                                    <div class="col-sm-10 col-xs-20">
	                                        <span class="fs16 my-course-subname" title="<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->title; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["co"]->title; ?></span>
	                                        <p class="hidden-xs"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["co"]->class_name,'site.index'); ?> &nbsp;<?php echo tpl_modifier_tr('主讲','LearningCenter'); ?>：
											<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["co"]->teacher_info->real_name)){; ?>
												<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->teacher_info->real_name; ?>
											<?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["co"]->teacher_info->name))){; ?>
												<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->teacher_info->name; ?>
											<?php }; ?>
											</p>
	                                        <p><?php echo tpl_modifier_tr('共','LearningCenter'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["co"]->section_count; ?><?php echo tpl_modifier_tr('章','LearningCenter'); ?>   <?php if(SlightPHP\Tpl::$_tpl_vars["co"]->section_show == 1){; ?><?php echo tpl_modifier_tr('进度','LearningCenter'); ?>&nbsp;<?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["co"]->section_name,'site.index'); ?><?php }; ?></p>
	                                        <p class="hidden-xs"><span class="<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->fee_class; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["co"]->fee_type; ?></span></p>
											<a class="col-lg-20 pd0" href="//<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["co"]->subdomain)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["co"]->subdomain; ?><?php }; ?>" target='_blank'>
											<span class="c-fl client-logo-name">
											<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["co"]->org_info->subname)){; ?>
												<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->org_info->subname; ?>
											<?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["co"]->org_info->name))){; ?>
												<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->org_info->name; ?>
											<?php }; ?>
											</span>
											</a>
										</div>
	                                    </a>
										<div class="fs14 col-sm-20 col-xs-20">
										</div>
                                </li>
								<?php }; ?>
                                <?php }else{; ?>
                                <div class="my-collect-no-class c-fl col-lg-offset-0 col-xs-offset-0 col-sm-offset-0 col-sm-20 col-md-20 col-lg-20 col-xs-20">
                                    <img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>" alt="">
                                    <p style="font-weight:bold;color:#666;"><?php echo tpl_modifier_tr('您还没有报名的课程哦 快去','LearningCenter'); ?><a href="/"><?php echo tpl_modifier_tr('首页','LearningCenter'); ?></a><?php echo tpl_modifier_tr('看看吧','LearningCenter'); ?></p>
                                </div>
							<?php }; ?>
                            </ul>
                        </div>
						<div class="col-sm-20 col-xs-20">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_course"])){; ?>
							<div class="page-list" id="course_page">
							</div>
							<?php }; ?>
						</div>
						<script>
							$(document).ready(function() {
								page("course_page","<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["num"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["total_page"]; ?>);
							});
						</script>
                    </li>
            <!-- /报名课程 -->
                    </ul>
            </div>
	<!-- /我的课表 -->
        </div>
    </div>
</section>
<div id="rightWindow"></div>
<script>$("#rightWindow").load("/org.rightWindow.rightWindow");</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script>
  	$(document).ready(function() {
        $('#tab-hd a').click(function() {
            $(this).addClass('curr').siblings().removeClass('curr');
            $('#list>li:eq(' + $(this).index() + ')').show().siblings().hide();
        })
		var search_cname = '<?php echo SlightPHP\Tpl::$_tpl_vars["title"]; ?>';
		var page = <?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>;
		var size = <?php echo SlightPHP\Tpl::$_tpl_vars["size"]; ?>;

		var search_del = <?php echo SlightPHP\Tpl::$_tpl_vars["del"]; ?>;

		if( search_cname != '' || search_del == 1 || page > 1 || size >0 ){

			$('.list-tab>dd:eq(1)').addClass('curr').siblings().removeClass('curr');

			$('#list>li:eq(1)').show().siblings().hide();
		}else{
       		//$('#list li').first().show();
			$('.list-tab>dd:eq(0)').addClass('curr').siblings().removeClass('curr');
		}
		if(search_cname != '' ){
			$('.search-delete-btn').show();
		}else{
			$('.search-delete-btn').hide();
		}

		$('#form_sub').click(function(){
            var searchVal = $.trim($(this).parents('.search-frame').find('input[name=title]').val());
            if(searchVal==''){
                return false;
            }
			$('input[name=del]').val(0);
			$('form[name=search]').submit();
		});
        $('#search-title').on('keyup',function (event) {
            if(event.keyCode == 13){
                var searchVal = $.trim($(this).val());
                if(searchVal==''){
                    return false;
                }
                $('input[name=del]').val(0);
                $('form[name=search]').submit();
            }
        })
		$('.search-delete-btn').click(function(){
			$('input[name=title]').val('');
			$('input[name=del]').val(1);
			$('form[name=search]').submit();
		});
		var p = 2;
		function getData(page,last_month){
			p++;
			$.ajax({
				type:"post",
				url: '/student.course.getPlanAjax',
				data:{ page:page },
				dataType:'json',
				success:function(ret){
					conList = $('#planList .plan-list:last')[0];
					arrList.push(conList);
					if(ret.code == -1){
						$(window).unbind('scroll');
					}else if(ret.code == 0){
						insertDiv(ret.data,last_month);
					}
				},
				complete:function() {
				},
				error: function(ret) {
					p--;
					conList = $("#planList .plan-list:last")[0];
					arrList.push(conList);
				}
			});
		}
		function insertDiv(data,last_month){
			var last_day = $('input[name=start_day]').last().val();
			var planListTpl = '';
			for(var i in data){

				if( last_month != i ){
					planListTpl += '<div class="student-course-month col-sm-10 hidden-xs"><i class="li-course-month col-sm-1 fs12">'+i+'</i></div>';
				}
				for(var j in data[i]){
					if(last_day == data[i][j].start_day){
						data[i][j].start_date = data[i][j].start_hour;
					}
                    planListTpl += '<div class="my-course-contents col-sm-12 plan-list col-xs-12">'+
                           '<div class="col-sm-2 fs14 vertical-line col-xs-4">'+data[i][j].start_date+'<span></span><i></i>'+
								'<input type="hidden" name="start_day" value="'+data[i][j].start_day+'">'+
                            '</div>'+
                           ' <div class="col-sm-10 my-course-contents-rt col-xs-8">'+
                                '<div class="col-sm-3">'+
									'<a href="'+data[i][j].course_url+'" target="_blank">'+
                                   ' <img src="'+data[i][j].thumb_sma+'" alt="" /></a>'+
                                '</div>'+
                                '<div class="col-sm-4 infor-subject">'+
                                    '<h1 class="fs16">'+
										'<a href="'+data[i][j].course_url+'"  target="_blank">'+data[i][j].course_name+'</a></h1>'+
                                    '<p>'+
									'<span>'+data[i][j].class_name+'</span> &nbsp;'+
									'<span><?php echo tpl_modifier_tr('讲师','LearningCenter'); ?>：<a href="'+data[i][j].teacher_url+'" target="_blank">'+data[i][j].teacher_name+'</a>'+
									'</span></p>'+
                                    '<p>'+data[i][j].section_name+'</p>'+
                                '</div>'+
                                '<div class="col-sm-4 infor-student-btn">'+data[i][j].plan_button+'</div>'+
                            '</div>'+
                        '</div>';
				}
			}
		  	$('#planList').append(planListTpl);
		}
		var scrollHandler = function(){
			var prec = getClient();
			jiance(arrList, prec,function(){
				var last_month = $('.li-course-month').last().text();
				getData(p,last_month);
			});
		}
		var arrList = [];
		var conList = $('#planList .plan-list:last')[0];
		arrList.push(conList);
		$(window).scroll(scrollHandler);
  		$(".class-stat a").each(function() {
  			$(this).click(function() {
				var pid = $(this).attr('data-pid');
            	layer.open({
                	type: 2,
                	title:false,
                	closeBtn:2,
                	area: ['610px', '630px'],
                	shadeClose: true, //点击遮罩关闭
                	content: '/student.course.stat/'+pid
            	});
  			})
  		})
   });
</script>
