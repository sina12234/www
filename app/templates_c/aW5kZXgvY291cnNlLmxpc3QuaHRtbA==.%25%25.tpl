<!DOCTYPE html>
<html>
<head>
<title>云课 - 全部课程 - 专业的在线学习平台</title>
<meta name="title" content="云课 - 全部课程 - 专业的在线学习平台">
<meta name="keywords" content="云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content=" 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/index.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
</head>
<body>

<!-- header -->
<?php echo tpl_function_part("/index.main.top"); ?>
<?php echo tpl_function_part("/index.main.nav/course"); ?>
<section class="p10">
    <div class="container">
        <div class="allCourse">
                <!--面包屑部分-->
                <dl class="all_c">
                    <dt class=""> <a href="/index.course.list"> 全部课程 ></a></dt>
					<?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['firstCate'] != -1){; ?>
                    <dd class="all_c_one"><a href="/index.course.list?fc=<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['firstCate']; ?>">  <?php echo SlightPHP\Tpl::$_tpl_vars["firstCateInfo"]->name_display; ?>  ></a></dd>
					<?php }; ?>
					<?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['secondCate'] != -1){; ?>
                    <dd class="all_c_two"><a href="/index.course.list?fc=<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['firstCate']; ?>&sc=<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['secondCate']; ?>">  <?php echo SlightPHP\Tpl::$_tpl_vars["secondCateInfo"]->name_display; ?>  ></a></dd>
					<?php }; ?>
                </dl>
                <div class="condition col-md-20 clear">
                    <dl class="cond_bg col-md-20">
                        <dt>分类 :</dt>
						<?php if(SlightPHP\Tpl::$_tpl_vars["showLevel"] == 1){; ?>
						<dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'fc','-1',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['firstCate'] == -1){; ?>class="curr"<?php }; ?>>全部</a></dd>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["firstCateList"])){; ?>
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["firstCateList"] as SlightPHP\Tpl::$_tpl_vars["fk"]=>SlightPHP\Tpl::$_tpl_vars["fo"]){; ?>
								<dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'fc',SlightPHP\Tpl::$_tpl_vars["fk"],SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['firstCate'] == SlightPHP\Tpl::$_tpl_vars["fk"]){; ?>class="curr"<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["fo"]; ?></a></dd>
							<?php }; ?>
							<?php }; ?>
						<?php }elseif((SlightPHP\Tpl::$_tpl_vars["showLevel"] == 2)){; ?>
						<dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'fc','-1',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['secondCate'] == -1){; ?>class="curr"<?php }; ?>>全部</a></dd>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["secondCateList"])){; ?>
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["secondCateList"] as SlightPHP\Tpl::$_tpl_vars["sk"]=>SlightPHP\Tpl::$_tpl_vars["so"]){; ?>
								<dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sc',SlightPHP\Tpl::$_tpl_vars["sk"],SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['secondCate'] == SlightPHP\Tpl::$_tpl_vars["sk"]){; ?>class="curr"<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["so"]; ?></a></dd>
							<?php }; ?>
							<?php }; ?>
						<?php }elseif((SlightPHP\Tpl::$_tpl_vars["showLevel"] == 3)){; ?>
						<dd><a href="<?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['thirdCate'] == -1){; ?><?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sc','-1',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?><?php }else{; ?><?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'tc','-1',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?><?php }; ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['thirdCate'] == -1){; ?>class="curr"<?php }; ?>>全部</a></dd>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["thirdCateList"])){; ?>
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["thirdCateList"] as SlightPHP\Tpl::$_tpl_vars["tk"]=>SlightPHP\Tpl::$_tpl_vars["to"]){; ?>
								<dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'tc',SlightPHP\Tpl::$_tpl_vars["tk"],SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['thirdCate'] == SlightPHP\Tpl::$_tpl_vars["tk"]){; ?>class="curr"<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["to"]; ?></a></dd>
							<?php }; ?>
							<?php }; ?>
						<?php }; ?>  
                    </dl>
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["attrValueList"])){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["attrValueList"] as SlightPHP\Tpl::$_tpl_vars["attr"]){; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["attr"]->attr_value)){; ?>
						<dl class="cond_content col-md-20" style="border-bottom: 1px dashed #ccc;">
							<dt class=""><?php echo SlightPHP\Tpl::$_tpl_vars["attr"]->name_display; ?> :</dt>
							<dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'vid',SlightPHP\Tpl::$_tpl_vars["attr"]->attr_id.'|-1',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["attr"]->flag == 0){; ?>class="curr"<?php }; ?>>全部</a></dd>
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["attr"]->attr_value as SlightPHP\Tpl::$_tpl_vars["value"]){; ?>
							<dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'vid',SlightPHP\Tpl::$_tpl_vars["attr"]->attr_id.'|'.SlightPHP\Tpl::$_tpl_vars["value"]->attr_value_id,SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["value"]->checked == 1){; ?>class="curr"<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["value"]->value_name; ?></a></dd>
							<?php }; ?>
						</dl>
						<?php }; ?>
					<?php }; ?>
					<?php }; ?>
					<dl class="cond_content col-md-20">
                       <dt>类型 :</dt>
                        <dd>
                        <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'ct','-1',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>"  <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['course_type']==-1){; ?>class="curr"<?php }; ?>>全部</a>
                        <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'ct',1,SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['course_type'] == 1){; ?>class="curr"<?php }; ?>>直播课</a>
                        <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'ct',2,SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['course_type'] == 2){; ?>class="curr"<?php }; ?>>录播课</a>
						<a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'ct',3,SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['course_type'] == 3){; ?>class="curr"<?php }; ?>>线下课</a>
                        </dd>
                    </dl>
					
                </div>
            </div>
        </div>
</section>
<!-- 列表 -->
<section class="p20">
    <div class="container">
        <div class="col-sm-20 col-lg-16 col-md-20 col-xs-20">
            <div class="row">
                <div class="sort-row fs14">
                    <ul class="sort clearfix">
                        <li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort'] == 'recomm_weight:desc'){; ?>class="curr"<?php }; ?>>
                            <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','recomm_weight:desc',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>">综合排序</a>
                        </li>
                        <li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort'] == 'create_time:desc'){; ?>class="curr"<?php }; ?>>
                            <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','create_time:desc',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>">最新建课</a>
                        </li>
                                     <!--li><a href="#">评分</a></li>
                                    <li><a href="#">观看最多</a></li-->
                        <li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort'] == 'comment:desc'){; ?>class="curr"<?php }; ?>>
                        <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','comment:desc',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>">评论</a>
                        </li>
                        <li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort'] == 'vv:desc'){; ?>class="curr"<?php }; ?>>
                        <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','vv:desc',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>">观看最多</a>
                        </li>
                                    <li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort_name'] == 'price' && SlightPHP\Tpl::$_tpl_vars["pm"]['fee_type'] != 0){; ?>class="curr"<?php }; ?> >
										<?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['fee_type'] == 0){; ?>
                                        <a href="javascript:void(0);" disabled="disabled" class="cGray">价格
                                            <span class="triangle">
                                                <span class="triangle-up c-fr"></span>
                                                <span class="triangle-down c-fr"></span>
                                            </span>
                                        </a>
										<?php }else{; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort'] == 'price:desc'){; ?>
                                        <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','price:asc',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>">价格
                                            <span class="triangle">
                                                <span class="triangle-down c-fr" style="margin-top:4px"></span>
                                            </span>
                                        </a>
                                        <?php }elseif((SlightPHP\Tpl::$_tpl_vars["pm"]['sort'] == 'price:asc')){; ?>
                                        <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','price:desc',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>">价格
                                            <span class="triangle">
                                                <span class="triangle-up c-fr" style="margin-top:4px"></span>
                                            </span>
                                        </a>
                                        <?php }else{; ?>
                                        <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','price:desc',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>">价格
                                            <span class="triangle">
                                                <span class="triangle-up c-fr"></span>
                                                <span class="triangle-down c-fr"></span>
                                            </span>
                                        </a>
                                        <?php }; ?>
										<?php }; ?>
                        </li>
                        <li class="hidden-xs"><input type="checkbox" name="ft" value="0" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['fee_type'] == 0){; ?>checked<?php }; ?>>只看免费课程</li>
                    </ul>
                                <div class="calendar hidden-xs">
                                    <form id="ftime" action="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"]); ?>" method="post">
                                        <div id="ftime-box" style="display:block;position: relative;">
                                            <input type="text" class="start-time" id="datestart" name="start_time" readonly placeholder="开课时间" value="<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']; ?>">
                                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["pm"]['start_time'])){; ?>
                                            <img src="<?php echo utility_cdn::img('/assets_v2/img/hide.png'); ?>" class="hide-img remove_date">
                                            <?php }; ?>
                                        </div>
                                        
                                        <?php /*<span class="click-show" style="cursor: pointer;">开课时间&nbsp;&nbsp;<img src="<?php echo utility_cdn::img('/assets_v2/img/platform/course-s.png'); ?>"></span>*/?>
                                    </form>
                                    <?php /*<span class="calendar-icon icon"></span>开课时间*/?>
                                </div>
                            </div>
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseList"])){; ?>
                            <div class="row">
                                <ul class="list">
                                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["courseList"] as SlightPHP\Tpl::$_tpl_vars["vo"]){; ?>
                                    <li class="col-sm-5 col-xs-10">
                                        <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->show_url; ?>" target="_blank">
                                            <p><img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["vo"]->thumb_med); ?>" alt="">
												<?php if(SlightPHP\Tpl::$_tpl_vars["vo"]->register == 0 && SlightPHP\Tpl::$_tpl_vars["vo"]->try == 1){; ?>
													<span class="linelookat-icon"></span>
												<?php }; ?>
												 <?php if(SlightPHP\Tpl::$_tpl_vars["vo"]->course_type==2){; ?>
												<span class="record-icon">录播</span>
												<?php if(SlightPHP\Tpl::$_tpl_vars["vo"]->register == 0 && SlightPHP\Tpl::$_tpl_vars["vo"]->try == 1){; ?>
													<span class="linelookat-icon"></span>
												<?php }; ?>
												<?php }elseif((SlightPHP\Tpl::$_tpl_vars["vo"]->course_type == 3)){; ?>
												<span class="lineclass-icon">线下</span>
												<?php if(SlightPHP\Tpl::$_tpl_vars["vo"]->register == 0 && SlightPHP\Tpl::$_tpl_vars["vo"]->try == 1){; ?>
													<span class="linelookat-icon"></span>
												<?php }; ?>
												<?php }; ?>
                                            </p>
                                            <div class="title fs14"><span><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->title; ?></span>
											
											</div>
                                            <div class="thumb">
                                                <span class="cost">
                                                    <?php if(SlightPHP\Tpl::$_tpl_vars["vo"]->fee_type==0){; ?>
                                                    <span class="cGreen fs14">免费&nbsp;&nbsp;</span>|&nbsp;&nbsp;
                                                    <?php }else{; ?>
                                                    <span class="cRed fs14">￥<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->price/100; ?>&nbsp;&nbsp;</span>|&nbsp;&nbsp;
                                                    <?php }; ?>
                                                    <?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->org_subname; ?>
                                                </span>
                                                <?php if(SlightPHP\Tpl::$_tpl_vars["vo"]->vv>0){; ?>
                                                <div class="num"><span class="g-icon11"></span><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->vv; ?></div>
                                                <?php }; ?>
                                            </div>
                                        </a>
                                    </li>
                                    <?php }; ?>
                                </ul></div>
                            <?php }else{; ?>
                            <div class="pos-rel no-result">
                                <div class="pos-abs no-result-tips cGray2">
                                    <p><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>" alt=""></p>
                                    <p class="fs16">没有找到符合您要求的课程，请调整一下条件再试试~</p>
                                    <p>你也可以点<a href="/index.course.list" class="cBlue">这里</a>返回默认条件！</p>
                                </div>
                            </div>
                            <?php }; ?></div>
                            <div class="row">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseList"])){; ?>
                            <div class="page-list fs16" id="course_page">
                            </div>
							<script>
								page("course_page","<?php echo SlightPHP\Tpl::$_tpl_vars["path_page"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['size']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['page']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['total']; ?>);
							</script>
							<?php }; ?>
                            </div>
                        </div>
                        <div class="right-tj col-sm-4 col-xs-20 visible-lg">
                            <div class="title-bar fs16"><span><?php if(isset(SlightPHP\Tpl::$_tpl_vars["hotrecomm_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["hotrecomm_name"]; ?><?php }; ?></span></div>
                            <ul class="list-recommend">
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["recomm_course"])){; ?>
                                <?php foreach(SlightPHP\Tpl::$_tpl_vars["recomm_course"] as SlightPHP\Tpl::$_tpl_vars["ro"]){; ?>
                                <li class="col-sm-20 col-xs-10">
                                    <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->show_url; ?>" target="_blank">
                                        <p><img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["ro"]->thumb_med); ?>" alt="">
										<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->register == 0 && SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
													<span class="linelookat-icon"></span>
												<?php }; ?>
												 <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->course_type==2){; ?>
												<span class="record-icon">录播</span>
												<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->register == 0 && SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
													<span class="linelookat-icon"></span>
												<?php }; ?>
												<?php }elseif((SlightPHP\Tpl::$_tpl_vars["ro"]->course_type == 3)){; ?>
												<span class="lineclass-icon">线下</span>
												<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->register == 0 && SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
													<span class="linelookat-icon"></span>
												<?php }; ?>
												<?php }; ?>
                                            </p>
                                        <div class="title fs14"><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?>
										</div>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["type_recomm"]=='user_total'&&SlightPHP\Tpl::$_tpl_vars["ro"]->user_total>=10){; ?>
                                                 <div class="thumb"><span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->user_total; ?></div>
												 <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_recomm"]=='remain_user'){; ?>
												 <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user=='0'){; ?><div class="thumb"><font color='red'>已报满</font></div><?php }; ?>
												 <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>0 && SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user<5){; ?><div class="num">剩余<span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user; ?></div>
												 <?php }; ?>
												 <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>5){; ?><div class="thumb"><span class="g-icon8"></span></div><?php }; ?>
												 <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_recomm"]=='vv'&&SlightPHP\Tpl::$_tpl_vars["ro"]->vv>=10){; ?>
												 <div class="thumb"><span class="g-icon11"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->vv; ?></div>
                                                 <?php }; ?>
                                    </a>
                                </li>
                <?php }; ?>
                <?php }; ?>
            </ul>
        </div>
    </div>
</section>
<!-- footer -->
<?php echo tpl_function_part("/index.main.footer"); ?>
<!-- footer -->
</body>
<style type="text/css">
</style>
</html>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
<script>
var top_nav_img="<?php echo utility_cdn::img('/assets_v2/img/qrcode.jpg'); ?>";
</script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/top_nav.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>"></script>
<script>
$(function() {
    $("#filter-section dd").find("a:eq(0)").after("<span class='c-fl hidden-xs hidden-sm filter-spn'></span>");

    $('.filter-section>dl').each(function(){
     if($(window).width()<800){
     var list_a_span=$(this).find('dt>span.c-fl');
    var list_a_sel=$(this).find('dd>.curr');
    if(list_a_sel.text() !='全部'){
    list_a_span.html(list_a_sel.text());
     }
     }
 });
   $('.filter-section>dl>dt').click(
        function(){
            if($(window).width()<800){
                $('.filter-section>dl>dd').hide();
                $(this).parent().find('dd').show();
            }
        }
    )
    var start_time = "<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']; ?>";
    $("#datestart").datetimepicker({
        timepicker:false,
        format: 'Y-m-d',
    });

    $('#datestart').change(function(){
        $('#ftime').submit();
    });
    $('.remove_date').click(function(){
        window.location.href = "<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"]); ?>";
    });

    $('input[name=ft]').click(function(){
        var url = '';
        if($(this).prop('checked') == true){
            if(start_time){
                url = "<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'ft',0,SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>"
            }else{
                url = "<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'ft',0); ?>";
            }
        }else{
            if(start_time){
                url = "<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'ft',-1,SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>"
            }else{
                url = "<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'ft',-1); ?>"
            }
        }
        window.location.href = url;
    });


   <?php /* $("#ftime .click-show").click(function(){
        $(this).hide();
        $("#ftime #ftime-box").show();
    })*/?>

});
</script>
