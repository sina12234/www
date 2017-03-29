<!DOCTYPE html>
<html>
<head>
<title>我的课程 - 云课 - 专业的在线学习平台</title>
<meta name="title" content="我的课程 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/index.main.header"); ?>
<script tyee="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/scorll.js'); ?>" ></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
</head>
<body class="bgf7">
<?php echo tpl_function_part("/index.main.usernav/student"); ?>
<!--studentPortal start-->
<section class="pd20">
    <div class="container">
      <div class="row">
        <!-- leftmenu start-->
		<?php echo tpl_function_part("/index.main.menu/student"); ?>
        <!-- leftmenu end -->

        <!--right start-->
        <!-- 我的课表 -->
            <div class="right-main col-sm-20 col-md-16 col-xs-20" id="wrap-my-course">
            <!--mob-->
			<div class="mob-nav hidden-lg hidden-md swiper-container3">
				<ul class="swiper-wrapper" id="mob-nav">
					<li class="swiper-slide"><a href="/index.growth.entry">首页</a></li>
					<li class="swiper-slide"><a href="/index.student.course" class="active">我的课程</a></li>
					<li class="swiper-slide"><a href="/index.student.order">我的订单</a></li>
					<li class="swiper-slide"><a href="/index.student.fav">我的收藏</a></li>
					<li class="swiper-slide"><a href="/index.task.studentTaskListShow">我的作业</a></li>
					<li class="swiper-slide"><a href="/index.student.discount">我的优惠券</a></li>
				</ul>
			</div>
                <div class="content" style="overflow: hidden;">
                    <dl class="list-tab fs16 col-xs-20 col-sm-20">
                        <dd class="col-sm-4 col-md-4 col-xs-8">直播课表<i></i></dd>
                        <dd class="col-sm-5 col-md-4 col-xs-12">正在学习（<?php echo SlightPHP\Tpl::$_tpl_vars["course_count"]; ?>）<i></i></dd>
                    </dl>
                    <ul id="list">
            <!-- 直播课程 -->
                    <li id="planList">
                    <!-- list -->
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planlist"])){; ?>
						<?php foreach( SlightPHP\Tpl::$_tpl_vars["planlist"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                        <div class="student-course-month col-sm-17 hidden-xs"><i class="li-course-month col-sm-1 fs12"><?php echo SlightPHP\Tpl::$_tpl_vars["k"]; ?></i></div>
						<?php foreach( SlightPHP\Tpl::$_tpl_vars["v"] as SlightPHP\Tpl::$_tpl_vars["kk"]=>SlightPHP\Tpl::$_tpl_vars["vv"]){; ?>
                        <div class="my-course-contents col-sm-20 plan-list col-xs-20">
                            <div class="col-sm-3 col-xs-7 fs14 vertical-line">
                               	<?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->start_date; ?>
                                <span></span>
                                <i></i>
								<input type="hidden" name="start_day" value="<?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->start_day; ?>">
                            </div>
                            <div class="col-sm-17 col-xs-13 my-course-contents-rt">
                                <div class="col-sm-5 img-subject">
									<a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->subdomain; ?><?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->course_url; ?>" target="_blank">
                                    <img src="<?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->thumb_sma; ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->course_name; ?>" />
									</a>
                                </div>
                                <div class="col-sm-9 infor-subject">
                                    <h1 class="fs16">
										<a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->subdomain; ?><?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->course_url; ?>" target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->course_name; ?></a></h1>
                                    <p>
									<span><?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->class_name; ?></span> &nbsp;
									<span>讲师：<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->teacher_url; ?>" target="_blank">
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["vv"]->teacher_real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->teacher_real_name; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->teacher_name; ?><?php }; ?></a>
									</span></p>
                                    <p class="cGreen fs14"><?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->section_name; ?></p>
									<p>
										<span class="client-logo-name"><?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->org_name; ?></span>
									</p>
                                </div>
                                <div class="col-sm-6 infor-student-btn">
									<?php echo SlightPHP\Tpl::$_tpl_vars["vv"]->plan_button; ?>
                                </div>
                            </div>
                        </div>
						<?php }; ?>
						<?php }; ?>
                        <?php }else{; ?>
                            <div class="my-collect-no-class">
                                <img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>"  alt="">
                                <p>您还没有直播的课程哦~！ 快去<a href="/">首页</a>看看吧</p>
                            </div>
					<?php }; ?>
                    </li>
            <!-- /直播课程 -->
            <!-- 报名课程 -->
                    <li>

                        <div class="my-course-content col-sm-20 col-xs-20">
                            <!-- 搜索 -->
								<form class="hidden-xs" name="search" method="get" action="/index.student.course">
                                <div class="col-sm-4 my-course-search-tp" style="padding:0;">
                                    <div class="my-course-search">
										<input type="hidden" name="del" value="<?php echo SlightPHP\Tpl::$_tpl_vars["del"]; ?>" />
                                        <input placeholder="课程搜索" type="text" name="title" value="<?php echo SlightPHP\Tpl::$_tpl_vars["title"]; ?>" autocomplete="off" />
                                        <button class="col-sm-4" id="form_sub" disabled></button>
                                        <span class="search-delet-btn"></span>
                                    </div>
                                </div>
								</form>
                            <!-- /搜索 -->
                            <ul class="my-course-content-rt clearfix">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_course"])){; ?>
								<?php foreach(SlightPHP\Tpl::$_tpl_vars["user_course"] as SlightPHP\Tpl::$_tpl_vars["co"]){; ?>
                                <li class="col-sm-10 col-xs-10">
                                    <a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->subdomain; ?><?php echo SlightPHP\Tpl::$_tpl_vars["co"]->section_url; ?>" title="<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->title; ?>" target="_blank">
                                        <div class="col-sm-10 col-xs-20 pl0">
    										<img src="<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->thumb_sma; ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->title; ?>" />
											<?php if(SlightPHP\Tpl::$_tpl_vars["co"]->type == 3){; ?>
												<span class="lineclass-icon fs12">线下课</span>
											<?php }elseif((SlightPHP\Tpl::$_tpl_vars["co"]->type == 2)){; ?>
												<span class="taped-class fs12">录播课</span>
    										<?php }elseif((SlightPHP\Tpl::$_tpl_vars["co"]->status == 3)){; ?>
												<span class="end-class"></span>
                                            <?php }elseif((SlightPHP\Tpl::$_tpl_vars["co"]->section_status == 2)){; ?>
                                            	<span class="start-class"></span>
    										<?php }; ?>
                                        </div>
                                        <div class="col-sm-10 col-xs-20 my-course-details" >
                                            <span class="fs16 my-course-subname" title="<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->title; ?>"  target="_blank" ><?php echo SlightPHP\Tpl::$_tpl_vars["co"]->title; ?></span>
                                            <p><?php echo SlightPHP\Tpl::$_tpl_vars["co"]->class_name; ?> &nbsp;主讲：
											<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["co"]->teacher_info->real_name)){; ?>
												<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->teacher_info->real_name; ?>
											<?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["co"]->teacher_info->name))){; ?>
												<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->teacher_info->name; ?>
											<?php }; ?>
											</p>
                                            <p>共<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->section_count; ?>章   <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["co"]->section_name)){; ?><?php if(SlightPHP\Tpl::$_tpl_vars["co"]->section_show == 1){; ?>进度&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->section_name; ?><?php }; ?><?php }; ?></p>
                                            <!--<p><span class="<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->fee_class; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["co"]->fee_type; ?></span></p>-->
											<a href="javascript:void(0);" target='_blank'>
												<div class="fs14 col-sm-20 col-xs-20 mt5 client-logo pd0">
													<span class="client-logo-name c-fl">
														<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["co"]->org_info->subname)){; ?>
															<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->org_info->subname; ?>
														<?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["co"]->org_info->name))){; ?>
															<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->org_info->name; ?>
														<?php }; ?>
													</span>
												</div>
											</a>
										</div>
                                    </a>
                                </li>
								<?php }; ?>
                                <?php }else{; ?>
                                <div class="my-collect-no-class">
                                    <img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>"  alt="">
                                    <p>您还没有直播的课程哦~！ 快去<a href="/">首页</a>看看吧</p>
                                </div>
							<?php }; ?>
                            </ul>
                        </div>
						<div class="col-sm-20 pd0">
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
            </div>
<!-- /我的课表 -->
        <!--right start-->
      </div>
    </div></section>
	<script>
//mob-nav滚动
        if($(window).width()<760){
            var mySwiper = new Swiper('.mob-nav', {
                slidesPerView :3
                    //autoplay: 1000,//可选选项，自动滑动
            })
            var li_w=$("#mob-nav li").outerWidth();
            var li_l=$("#mob-nav li").length;
            var ul_w=li_w*li_l+40;
            $("#mob-nav").css("width",ul_w+'px');
        }
	</script>
    <!--studentPortal end-->
	<?php echo tpl_function_part("/index.main.footer"); ?>
    <script>
$(document).ready(function() {
        $('.list-tab>dd').click(function() {
            $(this).addClass('curr').siblings().removeClass('curr');
            $('#list>li:eq(' + $(this).index() + ')').show().siblings().hide();
        })
		var search_cname = '<?php echo SlightPHP\Tpl::$_tpl_vars["title"]; ?>';
		var search_del = <?php echo SlightPHP\Tpl::$_tpl_vars["del"]; ?>;
		var page = <?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>;
		var size = <?php echo SlightPHP\Tpl::$_tpl_vars["size"]; ?>;
		if(search_cname != '' || search_del == 1 || page > 1 || size >0 ){
			$('.list-tab>dd:eq(1)').addClass('curr').siblings().removeClass('curr');
			$('#list>li:eq(1)').show().siblings().hide();
		}else{
       		$('#list li').first().show();
			$('.list-tab>dd:eq(0)').addClass('curr').siblings().removeClass('curr');
		}
		if(search_cname != '' ){
			$('.search-delet-btn').show();
		}else{
			$('.search-delet-btn').hide();
		}
        $(".my-course-search input[name='title']").keyup(function() {
            $("#form_sub").removeAttr("disabled");
        });
		$('#form_sub').click(function(){
			$('input[name=del]').val(0);
			$('form[name=search]').submit();
		});
		$('.search-delet-btn').click(function(){
			$('input[name=title]').val('');
			$('input[name=del]').val(1);
			$('form[name=search]').submit();
		});

		var p = 2;
		function getData(page,last_month){
			p++;
			$.ajax({
				type:"post",
				url: '/index.student.getPlanAjax',
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
				complete:function(){

				},
				error: function(ret){
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
				if(last_month != i){
                	planListTpl += '<div class="student-course-month col-sm-17 hidden-xs"><i class="li-course-month col-sm-1 fs12">'+i+'</i></div>';
				}
				for(var j in data[i]){
					if(last_day == data[i][j].start_day){
						data[i][j].start_date = data[i][j].start_hour;
					}
                    planListTpl += '<div class="my-course-contents col-sm-20 plan-list">'+
                           '<div class="col-sm-3 col-xs-7 fs14 vertical-line">'+data[i][j].start_date+'<span></span><i></i>'+
								'<input type="hidden" name="start_day" value="'+data[i][j].start_day+'">'+
                            '</div>'+
                           ' <div class="col-sm-17 col-xs-13 my-course-contents-rt">'+
                                '<div class="col-sm-5 img-subject">'+
								'<a href="'+data[i][j].course_url+'" target="_blank">'+
                                   ' <img src="'+data[i][j].thumb_sma+'" alt="'+data[i][j].course_name+'" /></a>'+
                                '</div>'+
                                '<div class="col-sm-9 infor-subject">'+
                                    '<h1 class="fs16">'+
										'<a href="'+data[i][j].course_url+'" target="_blank">'+data[i][j].course_name+'</a></h1>'+
                                    '<p>'+
									'<span>'+data[i][j].class_name+'</span> &nbsp;'+
									'<span>讲师：<a href="'+data[i][j].teacher_url+'" target="_blank">'+data[i][j].teacher_name+'</a>'+
									'</span></p>'+
                                    '<p>'+data[i][j].section_name+'</p>'+
									'<p>'+
									'<span class="client-logo-name">'+data[i][j].org_name+'</span>'+
									'</p>'+
                                '</div>'+
                                '<div class="col-sm-6 hidden-xs hidden-sm hidden-md infor-student-btn">'+data[i][j].plan_button+'</div>'+
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
                	closeBtn:true,
                	area: ['610px', '630px'],
                	shadeClose: true, //点击遮罩关闭
                	content: '/index.student.stat/'+pid
            	});
  			})
  		})


   });
	</script>
</body>
</html>
