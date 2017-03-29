<!DOCTYPE html>
<html>
<head>
    <title>教师团队 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
    <meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 教师团队 - 云课 - 专业的在线学习平台">
    <meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
    <meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
    <?php echo tpl_function_part("/site.main.header"); ?>
</head>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
<body>
<?php echo tpl_function_part("/site.main.nav.teacher"); ?>
<div class="bgf container mt20 mb20">
<section class="subject">
    <div class="container">
        <div class="row">
        <ul class="pull-left pb10">
            <li>科目: </li>
            <!--<li><p href=""><img src="<?php echo utility_cdn::img('/assets/images/steachx.png'); ?>" alt=""></p></li>-->
            <div class="pull_rgt">
				<li id="major_0" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['cate'] == -1){; ?>class="qb"<?php }; ?>>
					<a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'cate','-1'); ?>"><?php echo tpl_modifier_tr('全部','course.list'); ?></a>
				</li>
				<?php foreach(SlightPHP\Tpl::$_tpl_vars["categorys"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
				<li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['cate'] == SlightPHP\Tpl::$_tpl_vars["v"]->subject_id){; ?>class="qb"<?php }; ?>>
					<a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'cate',SlightPHP\Tpl::$_tpl_vars["v"]->subject_id); ?>" ><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["v"]->subject_name,'course.list'); ?></a>
				</li>
				<?php }; ?>
			</div>
        </ul>
        <ul class="pull-left">
            <li> 阶段:</li>
            <!--<li><p href=""><img src="<?php echo utility_cdn::img('/assets/images/steachx.png'); ?>" alt=""></p></li>-->
            <div class="pull_rgt">
			<li id="major_0" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['grade'] == -1){; ?>class="qb"<?php }; ?>>
                <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'grade','-1'); ?>"><?php echo tpl_modifier_tr('全部','course.list'); ?></a>
            </li>
            <?php foreach(SlightPHP\Tpl::$_tpl_vars["grades"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
            <li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['grade'] == SlightPHP\Tpl::$_tpl_vars["k"]){; ?>class="qb"<?php }; ?>>
                <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'grade',SlightPHP\Tpl::$_tpl_vars["k"]); ?>"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["v"],'course.list'); ?></a>
            </li>
            <?php }; ?>
			</div>
        </ul>
        </div>
    </div>
</section>

<section class="pdb30">
    <div class="container wrap-box">
        <div class="row wrap-ul mr0" id='row'>
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacherList"])){; ?>
			<?php foreach(SlightPHP\Tpl::$_tpl_vars["teacherList"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
			
            <div class="col-sm-10 col-xs-20 teacher-list">
                <div class="t-lector">
                    <div class="pic t-lector-pic col-sm-6 col-xs-6">
						<a href="/teacher/detail/entry/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->teacher_id; ?>" target="blank" class="is_tacher">
                            <img class="imgPic" src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["v"]->thumb_big); ?>">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->planid)){; ?>
							<a target="_blank" class="is_video hidden-xs">
                            <span class="video-icon" data-planid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->planid; ?>"><span class="video-icon-span"></span>视频介绍</span>
							</a>
							<?php }; ?>
                        </a>
                    </div>
                    <a href="/teacher/detail/entry/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->teacher_id; ?>" class="tea-item col-xs-14 col-sm-14" target="_blank">
                        <div class="u-name fs20">
                            <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->real_name; ?>
                            <span class="sex-icon-<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->gender == 1){; ?>m<?php }elseif((SlightPHP\Tpl::$_tpl_vars["v"]->gender == 2)){; ?>w<?php }; ?>"></span>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->years)){; ?>
                            <span class="u-note"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->years; ?>年教龄</span>
							<?php }; ?>
                        </div>
                        <ul class="u-score">
                            <li class=" ">评分 <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->avg_score; ?></li>
                            <li class="">课程 <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_count; ?></li>
                            <li class="">学生 <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->student_count; ?></li>
                        </ul>
                        <ul class="u-course">
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["v"]->subject as SlightPHP\Tpl::$_tpl_vars["key"]=>SlightPHP\Tpl::$_tpl_vars["val"]){; ?>
                            <li class=""><?php echo SlightPHP\Tpl::$_tpl_vars["val"]->subject_name; ?></li>
							<?php }; ?>
                        </ul>
                        <div class="college fs14">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->desc)){; ?>
                            <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->desc; ?>
							<?php }else{; ?>
							暂无老师介绍
							<?php }; ?>
                        </div>
                    </a>
                </div>
            </div>
			
			<?php }; ?>
			<?php }else{; ?> 
			<div class="col-xs-20 fs14 tac" style="padding-top:60px;padding-bottom:60px;display:block;">
            <img src="/assets_v2/img/platform/pet3.png">
            <p>还没有老师哦</p>
			</div>
			<?php }; ?>
        </div>
		<div class="col-sm-20">
            <div class="page-list" id="pagepage">
            </div>
        </div>
        
    </div>
</section>
</div>
<div id="rightWindow"></div>
<script>$("#rightWindow").load("/org.rightWindow.rightWindownew");</script>
<script>
page("pagepage","<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['size']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['page']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['total']; ?>);
</script>
<?php echo tpl_function_part("site.main.footer"); ?>
</body>
</html>
<script>//控制底部脚注
$(function() {
    if(screen.availWidth > 768) {
        //screen.availWidth浏览器可用宽度
        var oWrapBox = $(".wrap-box");
        var oListLength = $(".wrap-ul .lector-list").length;
        if(oListLength <= 6) {
            //oWrapBox.css( "height" , "720px" );
        }else{
            oWrapBox.css( "height" , "auto" );
        }
    }
	
	
	
	
	
  //  getData(0);
    if($(window).width()>800){
        if($(window).height()>=$(document).height()){
            oWrapBox.height($(window).height()-70-145);
        }
    }
    var planId;
    $('.is_video').click(function(){
        planId=$(this).find('.video-icon').attr('data-planid');
        layer.open({
            type: 2,
            title:false,
            shadeClose: true,
            area: ['710px', '399px'],
            content:'/user.teacher.TeacherVideoPreview#'+planId  //iframe的url
        });
    })
});

//var subjectClone = $('.subject').clone(true);
//console.log(subjectClone)
//subjectClone.hide().appendTo($('body'));
//subjectClone.css({
//    'position':'fixed',
//    'background':'#000',
//    'top':0,
//    'right':0
//}).find('li,a').css('color','#fff');
//window.onscroll = function(){
//    var t = $(window).scrollTop();
//    console.log(t);
//    if(t > 100){
//        subjectClone.show();
//    }else{
//        subjectClone.hide();
//    }
//}
</script>


