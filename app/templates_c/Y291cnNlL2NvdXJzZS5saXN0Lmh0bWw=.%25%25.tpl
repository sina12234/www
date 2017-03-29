<!DOCTYPE html>
<html>
<head>
<title>全部课程 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 全部课程 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
<script>
$(function() {
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

    $("#living").click(function() {
        if($(this).prop('checked')==true){
            window.location.href = "<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'course_type',1,SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>";
        }else{
            window.location.href = "<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'course_type',0,SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>";
        }
    });
    $("#video").click(function() {
        if($(this).prop('checked')==true){
            window.location.href = "<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'course_type',2,SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>";
        }else{
            window.location.href = "<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'course_type',0,SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>";
        }
    });
    $("#line").click(function() {
        if($(this).prop('checked')==true){
            window.location.href = "<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'course_type',3,SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>";
        }else{
            window.location.href = "<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'course_type',0,SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>";
        }
    });
});
</script>
<style>
	.bor1px-1{ border-right:none;border-top:none;border-left:none; }
</style>
<body>
<?php echo tpl_function_part("/site.main.nav.course"); ?>
<section style="z-index:2" class="pt20 bgf">
    <div class="container">
        <div class="row">
			<dl class="wrap-category-courses clear fs14">
				<dt>
					<a href="/course.list"><?php echo tpl_modifier_tr('全部课程','course.list'); ?></a> >
				</dt>
				<?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['firstCate'] != -1){; ?>
				<dd>
					<a href="/course.list" class="category-label-title tel">
						<?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["firstCateInfo"]->name_display,'course.list'); ?>
	          <span class="del-icon delMt8"></span>
					</a> >
				</dd>
				<?php }; ?>
				<?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['secondCate'] != -1){; ?>
					<dd>
						<a href="/course.list?fc=<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['firstCate']; ?>" class="category-label-title tel">
							<?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["secondCateInfo"]->name_display,'course.list'); ?>
							<span class="del-icon delMt8"></span>
						</a> >
					</dd>
				<?php }; ?>
				<?php if(SlightPHP\Tpl::$_tpl_vars["tg"] != -1){; ?>
					<dd>
						<a href="/course.list?fc=<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['firstCate']; ?>&sc=<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['secondCate']; ?>" class="category-label-title tel">
							<?php echo SlightPHP\Tpl::$_tpl_vars["selectedTagName"]; ?>
							<span class="del-icon delMt8"></span>
						</a>
					</dd>
				<?php }; ?>
			</dl>
			<div class="g-list g-course-list fs14">
				<dl class="clearfix tec bor1px-1">
					<dt><?php echo tpl_modifier_tr('分类','course.list'); ?> :</dt>
					<?php if(SlightPHP\Tpl::$_tpl_vars["showLevel"] == 1){; ?>
					<dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'fc','-1',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['firstCate'] == -1){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr('全部','course.list'); ?></a></dd>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["firstCateList"])){; ?>
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["firstCateList"] as SlightPHP\Tpl::$_tpl_vars["fk"]=>SlightPHP\Tpl::$_tpl_vars["fo"]){; ?>
							<dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'fc',SlightPHP\Tpl::$_tpl_vars["fk"],SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['firstCate'] == SlightPHP\Tpl::$_tpl_vars["fk"]){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["fo"],'course.list'); ?></a></dd>
						<?php }; ?>
						<?php }; ?>
					<?php }elseif((SlightPHP\Tpl::$_tpl_vars["showLevel"] == 2)){; ?>
					<dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'fc','-1',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['secondCate'] == -1){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr('全部','course.list'); ?></a></dd>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["secondCateList"])){; ?>
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["secondCateList"] as SlightPHP\Tpl::$_tpl_vars["sk"]=>SlightPHP\Tpl::$_tpl_vars["so"]){; ?>
							<dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sc',SlightPHP\Tpl::$_tpl_vars["sk"],SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['secondCate'] == SlightPHP\Tpl::$_tpl_vars["sk"]){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["so"],'course.list'); ?></a></dd>
						<?php }; ?>
						<?php }; ?>
					<?php }elseif((SlightPHP\Tpl::$_tpl_vars["showLevel"] == 3)){; ?>
					<dd><a href="<?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['thirdCate'] == -1){; ?><?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sc','-1',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?><?php }else{; ?><?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'tc','-1',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?><?php }; ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['thirdCate'] == -1){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr('全部','course.list'); ?></a></dd>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["thirdCateList"])){; ?>
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["thirdCateList"] as SlightPHP\Tpl::$_tpl_vars["tk"]=>SlightPHP\Tpl::$_tpl_vars["to"]){; ?>
							<dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'tc',SlightPHP\Tpl::$_tpl_vars["tk"],SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['thirdCate'] == SlightPHP\Tpl::$_tpl_vars["tk"]){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["to"],'course.list'); ?></a></dd>
						<?php }; ?>
						<?php }; ?>
					<?php }; ?>
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["attrValueList"])){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["attrValueList"] as SlightPHP\Tpl::$_tpl_vars["attr"]){; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["attr"]->attr_value)){; ?>
						<dl class="cond_content col-md-20 col-xs-20">
							<dt><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["attr"]->name_display,'course.list'); ?> :</dt>
							<dd>
								<a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'vid',SlightPHP\Tpl::$_tpl_vars["attr"]->attr_id.'|-1',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["attr"]->flag == 0){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr('全部','course.list'); ?></a>
							</dd>
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["attr"]->attr_value as SlightPHP\Tpl::$_tpl_vars["value"]){; ?>
							<dd>
								<a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'vid',SlightPHP\Tpl::$_tpl_vars["attr"]->attr_id.'|'.SlightPHP\Tpl::$_tpl_vars["value"]->attr_value_id,SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["value"]->checked == 1){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["value"]->value_name,'course.list'); ?></a>
							</dd>
							<?php }; ?>
						</dl>
						<?php }; ?>
					<?php }; ?>
					<?php }; ?>
					<div class="col-xs-5 hidden-lg hidden-md mb10 pd0  col-sm-9 col-sm-offset-2 col-sm-offset-lg-0">
        				<button class="course-scrool-btn pos-rel col-sm-4 mt5 c-fr">综合<i class="pos-abs"></i></button>
        			</div>
				</dl>

				<dl class="tec borTop clearfix">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["selected"])){; ?>
					<dt>
						<?php echo tpl_modifier_tr('标签','course.list'); ?> :
					</dt>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["selected"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
					<dd>
						<a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'tg',SlightPHP\Tpl::$_tpl_vars["v"]->pk_tag,SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tg"])&&SlightPHP\Tpl::$_tpl_vars["tg"]==SlightPHP\Tpl::$_tpl_vars["v"]->pk_tag){; ?>class="c-active"<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?></a>
					</dd>
					<?php }; ?>
					<?php }; ?>
				</dl>
			</div>

			<div class='list-scrool'>
				<div class='scrool-left'>
					<ul class="col-sm-20 col-md-10 pd0 col-xs-20 col-lg-14">
						<li class="<?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort'] == 'recomm_weight:desc'){; ?>x<?php }; ?> col-xs-20 pd0 col-sm-2 col-lg-2">
              <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','recomm_weight:desc',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>"><?php echo tpl_modifier_tr('综合排序','course.list'); ?></a>
            </li>
						<li class="<?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort'] == 'start_time:desc'){; ?>x<?php }; ?> col-xs-20 pd0 col-sm-2 col-lg-2">
              <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','start_time:desc',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>"><?php echo tpl_modifier_tr('最新上架','course.list'); ?></a>
            </li>
						<li class="<?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort'] == 'comment:desc'){; ?>x<?php }; ?> col-xs-20 pd0 col-sm-3 col-lg-2">
              <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','comment:desc',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>"><?php echo tpl_modifier_tr('评价最高','course.list'); ?></a>
            </li>
						<li class="<?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort'] == 'vv:desc'){; ?>x<?php }; ?> col-xs-20 pd0 col-sm-4 col-lg-2">
              <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','vv:desc',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>"><?php echo tpl_modifier_tr('观看最多','course.list'); ?></a>
            </li>
						<li class="<?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort'] == 'price:asc' or SlightPHP\Tpl::$_tpl_vars["pm"]['sort'] == 'price:desc'){; ?>x<?php }; ?> col-xs-20 pd0 col-sm-2 col-lg-2">
							<?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort']=='price:asc'){; ?>
							<a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','price:desc',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>">
							<span class="c-fl">
								<?php echo tpl_modifier_tr('价格','course.list'); ?>
							</span>
								<img src="<?php echo utility_cdn::img('/assets_v2/img/list-x.png'); ?>" style="float:left;margin-top:10px;">
							</a> <?php }elseif( SlightPHP\Tpl::$_tpl_vars["pm"]['sort']=='price:desc'){; ?>
							<a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','price:asc',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>">
							<span class="c-fl">
								<?php echo tpl_modifier_tr('价格','course.list'); ?>
							</span>
								<img src="<?php echo utility_cdn::img('/assets_v2/img/list-down.png'); ?>" style="float:left;margin-top:10px;">
							</a> <?php }else{; ?>
							<a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','price:desc',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>">
							<span class="c-fl">
								<?php echo tpl_modifier_tr('价格','course.list'); ?>
							</span>
								<img src="<?php echo utility_cdn::img('/assets_v2/img/list-up.png'); ?>" style="float:left;margin-top:10px;">
							</a> <?php }; ?>
						</li>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["memberSetList"])){; ?>
						<li class="col-xs-20 pd0 col-sm-3 col-lg-4 pos-rel pd0 vip-course-category col-md-6">
							<span class="vip-icon c-fl"></span>
							<?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['memberSet'] != -1){; ?>
								<?php echo SlightPHP\Tpl::$_tpl_vars["checkMember"]; ?>
							<?php }else{; ?>
								会员课程
							<?php }; ?>
							<dl class="col-md-20 pos-abs vip-course-list pd0">
								<?php if(SlightPHP\Tpl::$_tpl_vars["memberShow"] == 1){; ?>
									<?php foreach(SlightPHP\Tpl::$_tpl_vars["memberSetList"] as SlightPHP\Tpl::$_tpl_vars["mo"]){; ?>
									<dd class="col-md-20 pd0">
										<a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'ms',SlightPHP\Tpl::$_tpl_vars["mo"]->pk_member_set,SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>" ><?php echo SlightPHP\Tpl::$_tpl_vars["mo"]->title; ?></a>
									</dd>
									<?php }; ?>
								<?php }; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["pm"]['memberSet'] != -1)){; ?>
									<dd>
										<a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'ms','-1',SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']); ?>">返回全部课程</a>
									</dd>
								<?php }; ?>
							</dl>
						</li>
						<?php }; ?>
					</ul>
					<div class='scrool-right col-md-8 col-lg-6 col-sm-20 pd0 gn-scrool-right hidden-xs'>
						<label class='scrool-z c-fl'>
							<input type="checkbox" id="living" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['course_type']==1){; ?>checked<?php }; ?>>&nbsp;&nbsp;<?php echo tpl_modifier_tr('直播课','course.list'); ?>
						</label>
						<label class='scrool-z c-fl'>
							<input type="checkbox" id="video" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['course_type']==2){; ?>checked<?php }; ?>>&nbsp;&nbsp;<?php echo tpl_modifier_tr('录播课','course.list'); ?>
						</label>
						<label class='scrool-z c-fl'>
							<input type="checkbox" id="line" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['course_type']==3){; ?>checked<?php }; ?>>&nbsp;&nbsp;<?php echo tpl_modifier_tr('线下课','course.list'); ?>
						</label>

						<label class="foem-posi c-fl">
							<form id="ftime" action="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"]); ?>" method="post">
							<input type="text" class="form-control" id="datestart" name="start_time" readonly placeholder="<?php echo tpl_modifier_tr('开课时间','course.list'); ?>" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["pm"]['start_time'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['start_time']; ?><?php }; ?>">
							<img src="<?php echo utility_cdn::img('/assets_v2/img/list-rl.png'); ?>" style="margin-top:5px;float:right;margin-left:5px;">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["pm"]['start_time'])){; ?>
							<img src="<?php echo utility_cdn::img('/assets_v2/img/hide.png'); ?>" class="hide-img remove_date">
							<?php }; ?>
							</form>
						</label>
					</div>
				</div>
			</div>
    </div>
  </div>
</section>
<section style="z-index:1" class="pd30 bgf">
  <div class="container">
      <div class="row">
          <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseList"])){; ?>
          <ul>
          <?php foreach(SlightPHP\Tpl::$_tpl_vars["courseList"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
          <li class="col-sm-5 col-xs-10 course-list-item">
              <div class="u-cover">
                  <a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>" target="_blank">
                          <div class="u-cover-info">
                              <div class="pic">
                                  <img class="imgPic" src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["v"]->thumb_med); ?>">
                                  <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status==2){; ?>
                                  <div class="liveing-icon"><?php echo tpl_modifier_tr('正在上课','course.list'); ?></div>
                                  <?php }; ?>
             </div>
									<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->course_type==2){; ?>
                                    <div class="g-icon3 lessons-icon"><?php echo tpl_modifier_tr('录播','course.list'); ?></div>
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->try==1){; ?><i class="linelookat-icon"></i><?php }; ?>
                                    <?php }; ?>
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->course_type==3){; ?>
                                    <div class="taped-icon"><?php echo tpl_modifier_tr('线下','course.list'); ?></div>
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->try==1){; ?><i class="linelookat-icon"></i><?php }; ?>
                                    <?php }; ?>
                            </div>
                            <div class="tit fs14"><span><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->title; ?></span></div>

                        <!-- 课程分类-章数 -->
                            <div class="thumb fs12 clearfix">
                                <div class="cost">
                                    <span class="cGray">
										<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->third_cate_name)){; ?>
                                            <?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["v"]->third_cate_name,'course.list'); ?>&nbsp;
                                        <?php }; ?>
									</span>|
									<span class="cGray">
										<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id])){; ?>
										<?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id],'course.list'); ?>
										<?php }; ?>
									</span>
                                </div>
                                <div class="num">
                                   <span class="cGray"><?php echo tpl_modifier_tr('共','course.list'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->sectionNum; ?><?php echo tpl_modifier_tr('章','course.list'); ?></span>
                                </div>
                            </div>
                        <!-- /课程分类-章数 -->
                        <!-- 价格-报名状况 -->
                            <div class="thumb lh-thumb fs12 clearfix">
                                <div class="cost">
									<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->fee_type==0){; ?>
									<span class="cGreen"><?php echo tpl_modifier_tr('免费','course.list'); ?></span>
									<?php }elseif( isset(SlightPHP\Tpl::$_tpl_vars["v"]->fee->price)){; ?>
                                    <span class="cRed">
										<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->fee->price%100==0){; ?>
                                        ￥<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->fee->price/100; ?>
                                        <?php }else{; ?>
                                        ￥<?php echo number_format(SlightPHP\Tpl::$_tpl_vars["v"]->fee->price/100,2); ?>
                                        <?php }; ?>
									</span>
									<?php }else{; ?>
									<span class="cRed">
										<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->price%100==0){; ?>
                                        ￥<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->price/100; ?>
                                        <?php }else{; ?>
                                        ￥<?php echo number_format(SlightPHP\Tpl::$_tpl_vars["v"]->price/100,2); ?>
                                        <?php }; ?>
									</span>
									<?php }; ?>
                                </div>
                                <div class="num">
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["org_hot_type"]->hot_type)&&SlightPHP\Tpl::$_tpl_vars["org_hot_type"]->hot_type==1){; ?>
                                    <span class="num-icon"></span>
                                    <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_total; ?><?php echo tpl_modifier_tr('学生','course.list'); ?>
                                    <?php }; ?>
                                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["org_hot_type"]->hot_type)&&SlightPHP\Tpl::$_tpl_vars["org_hot_type"]->hot_type==2){; ?>
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->max_user-SlightPHP\Tpl::$_tpl_vars["v"]->user_total>5){; ?><span class="g-icon8"><?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->max_user-SlightPHP\Tpl::$_tpl_vars["v"]->user_total>0&&SlightPHP\Tpl::$_tpl_vars["v"]->max_user-SlightPHP\Tpl::$_tpl_vars["v"]->user_total<=5){; ?><font color='#009900' class="ter lh22"><?php echo tpl_modifier_tr('剩余','course.list'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->max_user - SlightPHP\Tpl::$_tpl_vars["v"]->user_total; ?><?php echo tpl_modifier_tr('个名额','course.list'); ?></font><?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->max_user-SlightPHP\Tpl::$_tpl_vars["v"]->user_total<=0){; ?></span><font class="ter lh22 cYellow"><?php echo tpl_modifier_tr('已报满','course.list'); ?></font><?php }; ?>
                                    <?php }; ?>
                                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["org_hot_type"]->hot_type)&&SlightPHP\Tpl::$_tpl_vars["org_hot_type"]->hot_type==3){; ?>
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->vv >=10){; ?><span class="g-icon11"></span><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->vv; ?><?php echo tpl_modifier_tr('次','course.list'); ?><?php }; ?>
                                    <?php }; ?>
                                    <span class="cGray"></span>
                                </div>
                            </div>
                        <!-- /价格-报名状况 -->
                        </a>
                </li>
                <?php }; ?>
                </ul>
                <?php }else{; ?>
                <div class='list-tu'>
                    <div class='list-img'>
                        <img src="<?php echo utility_cdn::img('/assets_v2/img/pet3.png'); ?>">
                        <div class='list-book'>
                            <span><?php echo tpl_modifier_tr('没找到符合您需求的课程','course.list'); ?>,<?php echo tpl_modifier_tr('请调整一下再试','course.list'); ?>~</span>
                            <p><?php echo tpl_modifier_tr('您可以戳','course.list'); ?><a href="/course.list"><b><?php echo tpl_modifier_tr('这里','course.list'); ?></b></a><?php echo tpl_modifier_tr('返回默认条件','course.list'); ?>~</p>
                        </div>
                    </div>
                </div>
                <?php }; ?>

        <div class="clear"></div>
        <div class="page-list" id="pagepage"></div>
		    <script>
			    page("pagepage","<?php echo SlightPHP\Tpl::$_tpl_vars["path_page"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['size']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['page']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['total']; ?>);
			</script>
    </div>
</div>
</section>
<div id="rightWindow"></div>
<script>$("#rightWindow").load("/org.rightWindow.rightWindow");</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script>
$(document).ready(function(){
    $(".g-list ul").find("li:eq(0)").after("<span class='c-fl hidden-xs hidden-sm filter-spn'></span>");

    $('.list-a').each(function(){
        if($(window).width()<800){
        var list_a_span=$(this).find('p>span:first');
        var list_a_sel=$(this).find('.list-sel');
        if(list_a_sel.text() !='全部'){
            list_a_span.html(list_a_sel.text());
        }
        }
    });
    $('.list-a').click(function(){
        if($(window).width()<800){
           $('.g-list ul').hide();
            $('.g-list ul:eq(' + $(this).index() + ')').show();
        };

    });
//综合
    if($(window).width() < 1024) {
    	$(".course-scrool-btn").click(function() {
    		if($(".list-scrool").hasClass('list-scrool-active')) {
    			$(".list-scrool").removeClass('list-scrool-active')
    			$(".list-scrool").hide();
    		}else {
    			$(".list-scrool").addClass('list-scrool-active');
    			$(".list-scrool").show();
    		}
    	})
    }
	if($(window).width() < 400){
		$(".list-scrool").hide();
	}

//vip
	$('.vip-course-category').hover(function() {
		$(this).find('.vip-course-list').show();
	} ,function() {
		$(this).find('.vip-course-list').hide();
	})

});
</script>
