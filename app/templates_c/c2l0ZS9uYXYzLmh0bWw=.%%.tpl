<!--header-->
<header class="usernav">
    <div class="container">
        <div class="row">
            <!--logo-->
            <a href="/" class="g-logo col-lg-4 col-md-3 col-xs-4">
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgInfo"]->thumb_big&&SlightPHP\Tpl::$_tpl_vars["is_pro"]==0)){; ?>
                <img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["orgInfo"]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["orgInfo"]->name; ?>">
				<?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["orgInfo"]->thumb_big)&&SlightPHP\Tpl::$_tpl_vars["is_pro"]==1)){; ?>
                <img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["orgInfo"]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["orgInfo"]->name; ?>">
                <?php }else{; ?>
                <img src="<?php echo utility_cdn::img('/assets_v2/img/logo.png'); ?>" alt="高能100" >
                <?php }; ?>
            </a>
			<div class="col-xs-3 hidden-lg">
				<div class="site-nav-menu" id="site-menu">
					<i class="menu-icon"></i>
				</div>
			</div>
			<!--  mobileNav  -->
			<section class="mobile-nav" id="mobile-nav" style="display:none;">
				<div class="container">
					<div class="row">
            <?php if(SlightPHP\Tpl::$_tpl_vars["is_pro"]){; ?>
						<ul class="clearfix ">
							<li class="col-xs-5 pd0"><a href="/course.list">全部课程</a></li>
							<?php echo tpl_function_part("/site/main/WoNav"); ?>
							<?php if(isset(SlightPHP\Tpl::$_tpl_vars["wonav"]) && !empty(SlightPHP\Tpl::$_tpl_vars["wonav"])){; ?>
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["wonav"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
							<li class="col-xs-5 pd0"><a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->url; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->title; ?></a></li>
							<?php }; ?>
							<?php }; ?>
              <!--<li class="col-xs-5 pd0"><a href="/site.main.WoDownload">APP下载</a></li>-->
							<li class="col-xs-5 pd0"><a href="/student.main.growth">我的学习</a></li>
						</ul>
            <?php }else{; ?>
            <ul>
  						<li class="col-xs-5 pd0"><a href="/" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="home"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('首页','site.header'); ?></a></li>
  						<?php if(SlightPHP\Tpl::$_tpl_vars["cateId"] > 0){; ?>
  						<li class="col-xs-5 pd0"><a href="/course.list?fc=<?php echo SlightPHP\Tpl::$_tpl_vars["cateId"]; ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="course"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('选课','site.header'); ?></a></li>
  						<?php }else{; ?>
  						<li class="col-xs-5 pd0"><a href="/course.list" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="course"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('选课','site.header'); ?></a></li>
  						<?php }; ?>
  						<li class="col-xs-5 pd0"><a href="/teacher.list" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="teacher"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('教师','site.header'); ?></a></li>
  						<li class="col-xs-5 pd0"><a href="/activity.main.list" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="activity"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('资讯','site.header'); ?></a></li>
  						<li class="visible-sm visible-xs col-xs-5 pd0"><a href="/live.list" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="live"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('直播','site.header'); ?></a></li>
  						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgmemberset"])){; ?>
  						<li class="visible-sm visible-xs col-xs-5 pd0"><a href="/member.list" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="member"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('会员','site.header'); ?></a></li>
  						<?php }; ?>
  						<!--<li class="visible-sm visible-xs col-xs-5 pd0"><a href="/about" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="about"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('关于我们','site.header'); ?></a></li>-->
  						<li class="fs20 hidden-sm hidden-md hidden-xs">
  						<a href="/live.list"  class="col-xs-5 pd0"><?php echo tpl_modifier_tr('直播','site.header'); ?></a>
  						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgmemberset"])){; ?>
  						<a href="/member.list" class="col-xs-5 pd0"><?php echo tpl_modifier_tr('会员','site.header'); ?></a>
  						<?php }; ?>
  						<!--<a href="/about" class="col-xs-5 pd0"><?php echo tpl_modifier_tr('关于我们','site.header'); ?></a>-->
  						</li>
  					  </ul>
                        <?php }; ?>
					</div>
				</div>
			</section>
			<!-- 搜索 -->
			<div class="m-sub-seach hidden-xs hidden-sm" id="m-sub-seach">
				<i class="m-sub-seach-icon"></i>
			</div>
			<!-- END -->
            <!--login-->
            <div class="g-login col-xs-4 col-lg-3" id="sitenav" style="float:right">
            </div>
			<div class="seach-input col-xs-20 hidden-lg hidden-md col-sm-20 pd0">
                <form id="search_form_wo">
                <?php if(isset(SlightPHP\Tpl::$_tpl_vars["searchField"]) && !empty(SlightPHP\Tpl::$_tpl_vars["searchField"])){; ?>
                <input type="text" class="c-fl fs14 col-xs-17 col-sm-18" autocomplete="off" name="search_field" maxlength="15" value="<?php echo SlightPHP\Tpl::$_tpl_vars["searchField"]; ?>" placeholder="课程名称，科目"/>
                <?php }else{; ?>
                <input type="text" class="c-fl fs14 col-xs-17 col-sm-18" autocomplete="off" name="search_field" maxlength="15" placeholder="课程名称，科目"/>
                <?php }; ?>
				<span class="btn-clear" id="search_form_hide"></span>
                <span id="search_id">搜索</span>
                </form>
            </div>
            <!--nav-->
            <nav class="g-nav col-lg-8 col-md-10 col-xs-20 swiper-container3 hidden-xs">
                <ul class="swiper-wrapper h100 pos-abs" id="headernav">
                  <?php if(SlightPHP\Tpl::$_tpl_vars["is_pro"]){; ?>
                    <?php echo tpl_function_part("/site/main/WoNav"); ?>
					           <li class="swiper-slide <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["flag_nav"])&&SlightPHP\Tpl::$_tpl_vars["flag_nav"]== "course"){; ?>curr<?php }; ?>">
                          <a href="/course.list">全部课程</a>
                      </li> 
                    <?php if(isset(SlightPHP\Tpl::$_tpl_vars["wonav"]) && !empty(SlightPHP\Tpl::$_tpl_vars["wonav"])){; ?>
                      <?php foreach(SlightPHP\Tpl::$_tpl_vars["wonav"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                        <li class="swiper-slide" style="display:none;">
                          <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->url; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->title; ?></a>
                        </li>
                      <?php }; ?>
                    <?php }; ?>
                  <!--<li class="swiper-slide"><a href="/site.main.xiaowoappdownload" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="app"){; ?>class="selected"<?php }; ?>>下载APP</a></li>-->
                  <!--li class="swiper-slide"><a href="#">会员商城</a></li-->
        				    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgInfo"]->oid)&&SlightPHP\Tpl::$_tpl_vars["orgInfo"]->oid ==842){; ?>
                          <!--<li class="swiper-slide"><a href="/site.main.WoDownload">APP下载</a></li>-->
        				    <?php }; ?>
                    <li class="swiper-slide <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["flag_nav"])&&SlightPHP\Tpl::$_tpl_vars["flag_nav"]== "mycourse"){; ?>curr<?php }; ?>">
                      <a href="/student.course.mycourse">我的学习</a>
                    </li>
                    <li class="swiper-slide pos-rel gn-senior-navmore-btn">
                      <a href="javascript:;">更多</a>
                      <dl class="gn-senior-navmore pos-abs" style="display:none;"></dl>
                    </li>
                    <script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.senior.nav.js'); ?>"></script>
                  <?php }else{; ?>
                  <li class="swiper-slide"><a href="/" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="home"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('首页','site.header'); ?></a></li>
                  <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cateId"])){; ?>
	                <li class="swiper-slide"><a href="/course.list<?php echo SlightPHP\Tpl::$_tpl_vars["cateId"]; ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="course"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('选课','site.header'); ?></a></li>
	                <?php }else{; ?>
	                <li class="swiper-slide"><a href="/course.list" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="course"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('选课','site.header'); ?></a></li>
	                <?php }; ?>
	                <li class="swiper-slide"><a href="/teacher.list" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="teacher"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('教师','site.header'); ?></a></li>
	                <li class="swiper-slide"><a href="/activity.main.list" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="activity"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('资讯','site.header'); ?></a></li>
	                <li class="swiper-slide visible-sm visible-xs"><a href="/live.list" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="live"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('直播','site.header'); ?></a></li>
	                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgmemberset"])){; ?>
				        	<li class="swiper-slide visible-sm visible-xs"><a href="/member.list" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="member"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('会员','site.header'); ?></a></li>
	                <?php }; ?>
					<!--<li class="swiper-slide visible-sm visible-xs"><a href="/about" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="about"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('关于我们','site.header'); ?></a></li>-->
	                <li class="swiper-slide fs20 hidden-xs hidden-sm">
                    <a href="/live.list" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="live"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('直播','site.header'); ?></a>
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgmemberset"])){; ?>
                    <a href="/member.list" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="member"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('会员','site.header'); ?></a>
                    <?php }; ?>
	                        <!--<a href="/about"><?php echo tpl_modifier_tr('关于我们','site.header'); ?></a>-->
	                </li>
                  <?php }; ?>
                </ul>
            </nav>
            <!--搜索-->
            <div class="g-so mt20 mr15 hidden-xs hidden-sm hidden-md">
                <form id="search_form" method="get" action="">
                <!--div class="div-select">
                    <cite>课程</cite>
                    <dl style="z-index:10;">
                        <dd class="se-dd"><a selectid='course' >课程</a></dd>
                        <dd class="se-dd"><a selectid='teacher'>老师</a></dd>
                    </dl>
                </div-->
                <?php if(isset(SlightPHP\Tpl::$_tpl_vars["searchField"]) && !empty(SlightPHP\Tpl::$_tpl_vars["searchField"])){; ?>
                <input type="text" class="c-fl fs14" autocomplete="off" name="search_field" maxlength="15" value="<?php echo SlightPHP\Tpl::$_tpl_vars["searchField"]; ?>" placeholder="课程名称，科目"/>
                <?php }else{; ?>
                <input type="text" class="c-fl fs14" autocomplete="off" name="search_field" maxlength="15" placeholder="课程名称，科目"/>
                <?php }; ?>
                <span class="btn-clear" id="search_form_clear"></span>
                <span class="btn-search" id="search"></span>
                </form>
            </div>
        </div>
    </div>
</header>
<script>
    $("#sitenav").load("/site.main.usernav2.<?php echo SlightPHP\Tpl::$_tpl_vars["subnav"]; ?>");
</script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/swiper.min.js'); ?>"></script>
<script>
//导航移动端
$(function(){
if ($(window).width() < 768) {
    var mySwiper = new Swiper('.swiper-container3',{
        slidesPerView : 3,});
    var li_width = 0;
        li_width=$("#headernav li").outerWidth();
    var li_index=$("#headernav li").length;
    $("#headernav").css('width',li_width*li_index);
    //mob-nav
    var mySwiper = new Swiper('.g-nav',{
        slidesPerView :3,});
		var li_width = 0;
			mob_width=$("#mob-nav li").outerWidth();
    var mob_index=$("#mob-nav li").length;
    $("#mob-nav").css('width',mob_width*mob_index);
}
//submenu
var subMenu = $('#sub-menu');
var categorysList=$('<div class="categorys-list fs14" id="categorys-list"></div>');
var categorysMenu='';
<?php if(SlightPHP\Tpl::$_tpl_vars["is_pro"]){; ?>
$.ajax('/site.main.woTopMenu',{
    type:'get',
    dataType:'json',
    success:function (data) {
        if(data.code == 100){
            var dataList = data.data;
            for(var i in dataList){
                categorysMenu += '<ul class="categorys-menu" id="submenu'+i+'"><li class="parent-menu fs16 col-xs-20"><a class="parent-menu-title" href="'+dataList[i].url+'">'+dataList[i].grade+'</a></li>';
                categorysMenu += '<li class="parent-menu">';
                for(var x in dataList[i].children){
                    categorysMenu += '<a href="'+dataList[i].children[x].url+'">'+dataList[i].children[x].name+'</a>';
                }
                categorysMenu += '</li></ul>';
                categorysList.html(categorysMenu);
            }
            subMenu.append(categorysList);
        } else {
            subMenu.hide();
        }
    }
})
<?php }; ?>
});
</script>
<script>
function searchSubmit(search_field) {
	var url = '';
    url 	= '/course.list?';
	if(search_field){
		url = url+'search_field='+escape(search_field);
	}
	window.location.href=url;
}
function searchSubmitId(search_field) {
	var url = '';
    url 	= '/course.list?';
	if(search_field){
		url = url+'search_field='+escape(search_field);
	}
	window.location.href=url;
}
$(function(){
	if($("input[name='search_field']").val() != ''){
		$('#search_form_clear').show();
	}else{
		$('#search_form_clear').hide();
	}
    $('#checkLogin').on('click',function(){
        if($(window).width()>768){
            w='476px';
            h='283px';
            layer.open({
                title:['<?php echo tpl_modifier_tr('登录并开始学习吧','site.header'); ?>','color:#fff;background:#ffa81d'],
                type: 2,
                area: [w,h],
                shadeClose: true,
                content: '/site.main.alertLogin?url=/student.course.mycourse&from='+encodeURIComponent(window.location)
            });
        }else{ location.href='/site.main.login?url=/student.course.mycourse';
        }
    });

    $.divselect(".divselect cite");

	$("#search").click(function(){
		var search_field = $("#search_form input[name='search_field']").val();
		searchSubmit(search_field);
	});
	//手机端搜索
	$('#search_id').click(function () {
		var search_field = $("#search_form_wo input[name='search_field']").val();
		searchSubmitId(search_field);
	})


	$("#search_form_clear").click(function() {
		$(this).hide();
		$("input[name='search_field']").val("");
		searchSubmit();
	})
	if($("input[name='search_field']").val() != ''){
		$('#search_form_hide').show();
	}else{
		$('#search_form_hide').hide();
	}
	if($("input[name='search_field']").val() != ''){
		$('.seach-input').show();
	}
	$("#search_form_hide").click(function() {
		$(this).hide();
		$("input[name='search_field']").val("");
		searchSubmit();
	})
	$("#search_form").submit(function (e) {
        e.preventDefault();
        return false;
    }).keydown(function(e){
		var e = e || event,
		keycode = e.which || e.keyCode;
		if (keycode==13) {
			var search_field = $(this).find("input[name='search_field']").val();
			searchSubmit(search_field);
		}
	})


});
		$('.m-sub-seach').click(function(){
				var menulist_hide = $('.seach-input');
				if(menulist_hide.css('display')=='none'){
					menulist_hide.css('display','block')
				}else{
					menulist_hide.css('display','none')
				}
		})

	$('#site-menu').click(function(){
		var mobileNav = $('#mobile-nav');
		mobileNav.toggle();
	})
</script>
