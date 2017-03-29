<!DOCTYPE html>
<html>
<head>
<title>明星教师 - 云课 - 专业的在线学习平台</title>
<meta name="title" content="云课 - 明星教师 - 专业的在线学习平台">
<meta name="keywords" content="云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content=" 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/index.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/common.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
</head>
<body>
<!-- header -->
<?php echo tpl_function_part("/index.main.top/teacher"); ?>
<?php echo tpl_function_part("/index.main.nav/teacher"); ?>
<section class="mt20">
    <div class="container">
        <div class="filter-section fs14">
            <dl class="col-sm-20 col-md-20 col-lg-20 col-xs-20" >
                <dd>
                    <a data="-1" href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'grade','-1'); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['grade'] == -1){; ?>class="curr"<?php }; ?>>全阶段</a>
					<a data="4000" href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'grade',4000); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['grade'] == 4000){; ?>class="curr"<?php }; ?>>学前</a>
                    <a data="1000" href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'grade',1000); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['grade'] == 1000){; ?>class="curr"<?php }; ?>>小学</a>
                    <a data="2000" href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'grade',2000); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['grade'] == 2000){; ?>class="curr"<?php }; ?>>初中</a>
                    <a data="3000" href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'grade',3000); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['grade'] == 3000){; ?>class="curr"<?php }; ?>>高中</a>
                </dd>
            </dl>
            <dl class="mt10 col-sm-20 col-md-20 col-lg-20 col-xs-20">
                <dd>
                <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'cate','-1'); ?>"  <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['cate'] == -1){; ?>class="curr"<?php }; ?>>全科目</a>
                <?php foreach(SlightPHP\Tpl::$_tpl_vars["categorys"] as SlightPHP\Tpl::$_tpl_vars["ck"]=>SlightPHP\Tpl::$_tpl_vars["co"]){; ?>
                <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'cate',SlightPHP\Tpl::$_tpl_vars["ck"]); ?>"  <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['cate'] == SlightPHP\Tpl::$_tpl_vars["ck"]){; ?>class="curr"<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["co"]; ?></a>
                <?php }; ?>
                </dd>
            </dl>
            <?php /* <dl class="col-sm-6 col-md-20 col-lg-20 col-xs-6">
                <dd><a href="#" class="curr">全类型</a><a href="#">在线课</a><a href="#">线下课</a><a href="#">1对1</a></dd>
            </dl>*/?>
        </div></div>
    </section>
<!-- 列表 -->
<section class="">
    <div class="container">
        <div class="sort-row fs14 hidden-xs">
            <ul class="sort">
                <li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort'] == 'weight:desc'){; ?>class="curr"<?php }; ?>>
					<a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','weight:desc'); ?>">综合排序</a>
				</li>
                <li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort'] == 'student_count:desc'){; ?>class="curr"<?php }; ?>>
               		<a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','student_count:desc'); ?>">人气</a>
			   </li>
               <li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort'] == 'avg_score:desc'){; ?>class="curr"<?php }; ?>>
                 	<a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','avg_score:desc'); ?>">评分</a>
               </li>
             <!--li><a href="#">价格<span class="triangle"><span class="triangle-up c-fr"></span><span class="triangle-down c-fr"></span></span></a></li-->
            </ul>
					<?php /*<div class="area col-sm-2 fs14">
                        <cite>城市</cite>
						<dl>
                            <dt class="interval-solid">猜你们在：<span>北京</span></dt>
                            <dd class="dashed-solid"><span class="c-fl">A</span>
							<p class="c-fl">
							<a href="#">安宁</a><a href="#">安庆</a><a href="#">安康</a>
							<a href="#">安康</a><a href="#">安康</a><a href="#">安康</a>
							</p>
							</dd>
                            <dd class="dashed-solid"><span class="c-fl">B</span><p class="c-fl"><a href="#">北京</a></p></dd>
                            <dd class="dashed-solid"><span class="c-fl">C</span><p class="c-fl"><a href="#">沧州</a><a href="#">长春</a></p></dd>
						</dl>
                    </div>*/?>
                </div>

				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacherList"])){; ?>
                <ul class="teacher-list" id="teacherList">
				<?php foreach(SlightPHP\Tpl::$_tpl_vars["teacherList"] as SlightPHP\Tpl::$_tpl_vars["to"]){; ?>
                    <li class="fs12">
                        <div class="col-sm-6 col-xs-6 col-lg-4 col-md-6">
							<div class="user-avatar">
								<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["to"]->show_url; ?>" target="_blank">
                                <img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["to"]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["to"]->name; ?>">
								</a>
							</div>
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["to"]->planid)){; ?>
                           <span class="video-box visible-lg" data-planid="<?php echo SlightPHP\Tpl::$_tpl_vars["to"]->planid; ?>">
								<span class="index-teac-vio pos-rel"></span>
								<span class="video-j pos-abs">视频简介</span>
                                <span class="video-img"></span>
                          </span>
                          <?php }; ?>

						  <?php /*  <a href="#" class="video-icon icon"></a>*/?>
						</div>
                        <div class="col-sm-8 col-xs-14 fs14 col-lg-7 col-md-9">
                            <p class="item fs20 col-xs-20">
							<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["to"]->show_url; ?>" target="_blank">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["to"]->real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["to"]->real_name; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["to"]->name; ?><?php }; ?>
							</a>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["to"]->org_subname)){; ?><span class="b-blue fs12"><?php echo SlightPHP\Tpl::$_tpl_vars["to"]->org_subname; ?></span><?php }; ?></p>
						   <p class="item cGray2  col-xs-20"><span class="icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["to"]->city; ?> <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["to"]->years)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["to"]->years; ?>年教龄<?php }; ?></p>
                           <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["to"]->subject_name)){; ?> <p class="item cGray2 col-sm-20 hidden-xs">科目：<?php echo SlightPHP\Tpl::$_tpl_vars["to"]->subject_name; ?></p><?php }; ?>
                           <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["to"]->college)){; ?> <p class="item cGray2 col-sm-20 hidden-xs">毕业于 <?php echo SlightPHP\Tpl::$_tpl_vars["to"]->college; ?></p><?php }; ?>
                        </div>
                        <div class="col-sm-6 fs14 col-lg-5 col-md-6 mobile-phone">
                            <p class="item cGray2 cg_fl col-xs-6 col-lg-20 col-sm-20 col-md-20">
                            <span class="fs26 cYellow  col-lg-10 col-sm-20 col-md-10">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["to"]->avg_score)){; ?>
                            <?php echo round(SlightPHP\Tpl::$_tpl_vars["to"]->avg_score,1) < 0 ? 0 : round(SlightPHP\Tpl::$_tpl_vars["to"]->avg_score,1); ?>分
                            <?php }else{; ?>
                            &nbsp;
                            <?php }; ?>
                            </span>

                            <p class="item cGray2 col-xs-6 col-lg-20 col-sm-20 col-md-20">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["to"]->score_user_count)){; ?><span class="cYellow  col-lg-10 col-sm-20 col-md-20"><?php echo SlightPHP\Tpl::$_tpl_vars["to"]->score_user_count; ?><b class="cGray" style="font-weight:none;">人评价</b></span></p><?php }; ?>

                            <p class="col-xs-12 col-lg-20 col-sm-20 col-md-20"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["to"]->course_count)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["to"]->course_count; ?>个课程&nbsp;<?php }; ?>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["to"]->student_count)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["to"]->student_count; ?>个学生<?php }; ?></p>
                        </div>
                      <?php /*  <div class="col-sm-4 fs14">
                            <p class="item cGray2"><span class="b-gray cGray c-fl"><span class="id-icon icon"></span><span class="c-fl hidden-xs">身份认证</span></span></p>
                            <p class="item cGray2"><span class="b-gray cGray c-fl"><span class="degrees-icon icon"></span><span class="hidden-xs">学历认证</span></p>
                            <p class="item cGray2 hidden-xs"><span class="bg-blue fs12">1对1</span> <span class="cYellow">￥26</span><span class="fs12">起</span></p>
                        </div>*/?>
                    </li>
					<?php }; ?>
                </ul>
				<?php }else{; ?>
                <div class="pos-rel no-result">
                	<div class="pos-abs no-result-tips cGray2">
                        <p><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>" alt=""></p>
                        <p class="fs16">没有找到符合您要求的明星教师，请调整一下条件再试试~</p>
                    	<p>你也可以点<a href="/index.teacher.list" class="cBlue">这里</a>返回默认条件！</p>
                    </div>
                </div>

				<?php }; ?>
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacherList"])){; ?>
                <div class="page-list fs14" id="teacher_page">
				</div>
				<script>
					page("teacher_page","<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"]); ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['size']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['page']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['total']; ?>);
				</script>
				<?php }; ?>
			</div>
        </section>
    <!-- footer -->
	<?php echo tpl_function_part('/index.main.footer'); ?>
</body>

<script>
var top_nav_img="<?php echo utility_cdn::img('/assets_v2/img/qrcode.jpg'); ?>";
</script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/top_nav.js'); ?>"></script>

</html>

<script>
$(function() {
    var teacherList=$('#teacherList');
    var planId;
    $(".so-input input[name='search_field']").attr("placeholder","搜老师名称，科目试试吧");
	teacherList.on('click','.video-box',function(){
        planId=$(this).attr('data-planid');
		layer.open({
			  type: 2,
			  title:false,
			  shadeClose: true,
			  area: ['710px', '399px'],
			  content:'/index.teacher.TeacherVideoPreview#'+planId  //iframe的url
		});
	})
})
</script>
