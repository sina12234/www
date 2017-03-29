<!--header-->
<header class="usernav">
    <div class="container">
        <div class="row">
            <!--logo-->
            <div class="g-logo col-lg-3 col-md-3 col-xs-8">
                <a href="/">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgInfo"]->thumb_big&&SlightPHP\Tpl::$_tpl_vars["is_pro"]==0)){; ?>
                     <img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["orgInfo"]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["orgInfo"]->name; ?>">
					<?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["orgInfo"]->thumb_big)&&SlightPHP\Tpl::$_tpl_vars["is_pro"]==1)){; ?>
					<img src="<?php echo utility_cdn::img('/assets_v2/img/xiaowo_float_logo.png'); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["orgInfo"]->name; ?>">
                    <?php }else{; ?>
                    <img src="<?php echo utility_cdn::img('/assets_v2/img/logo.png'); ?>" alt="高能100" >
                    <?php }; ?>
                </a>
            </div>
            <!--login-->
            <div class="g-login" id="sitenav" style="float:right">
            </div>
            <?php if(SlightPHP\Tpl::$_tpl_vars["is_pro"]){; ?>
            <!--submenu-->
            <div class="sub-menu hidden-xs" id="sub-menu">
                <i class="sub-menu-icon"></i>
            </div>
            <?php }; ?>
            <!--nav-->
            <nav class="g-nav col-lg-9 col-md-10 col-xs-20 swiper-container3">
                <ul class="swiper-wrapper h100 pos-abs" id="headernav">
                  <?php if(SlightPHP\Tpl::$_tpl_vars["is_pro"]){; ?>
                    <?php echo tpl_function_part("/site/main/WoNav"); ?>
                    <?php if(isset(SlightPHP\Tpl::$_tpl_vars["wonav"]) && !empty(SlightPHP\Tpl::$_tpl_vars["wonav"])){; ?>
                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["wonav"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                    <li class="swiper-slide"><a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->url; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->title; ?></a></li>
                    <?php }; ?>
                    <?php }; ?>
                  <!--<li class="swiper-slide"><a href="/site.main.xiaowoappdownload" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="app"){; ?>class="selected"<?php }; ?>>下载APP</a></li>-->
                  <!--li class="swiper-slide"><a href="#">会员商城</a></li-->
                  <li class="swiper-slide"><a href="/student.course.mycourse">我的学习</a></li>
                  <?php }else{; ?>
                      <li class="swiper-slide"><a href="/" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="home"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('首页','site.header'); ?></a></li>
                  <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cateId"])){; ?>
	                <li class="swiper-slide"><a href="/course.list<?php echo SlightPHP\Tpl::$_tpl_vars["cateId"]; ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="course"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('全部课程','site.header'); ?></a></li>
	                <?php }else{; ?>
	                <li class="swiper-slide"><a href="/course.list" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="course"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('全部课程','site.header'); ?></a></li>
	                <?php }; ?>
	                <li class="swiper-slide"><a href="/teacher.list" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="teacher"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('教师团队','site.header'); ?></a></li>
	                <li class="swiper-slide"><a href="/activity.main.list" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="activity"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('学习资讯','site.header'); ?></a></li>
	                <li class="swiper-slide visible-sm visible-xs"><a href="/live.list" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="live"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('直播列表','site.header'); ?></a></li>
	                <li class="swiper-slide visible-sm visible-xs"><a href="/member.list" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="member"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('会员专区','site.header'); ?></a></li>
	                <li class="swiper-slide visible-sm visible-xs"><a href="/about" <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=="about"){; ?>class="selected"<?php }; ?>><?php echo tpl_modifier_tr('关于我们','site.header'); ?></a></li>
	                <li class="swiper-slide fs20 navmore hidden-xs hidden-sm">
	                    <a href="javascript:void(0)"></a>
	                    <p class="nav-list">
	                        <i class="nav-list-arrow"></i>
                            <a href="/live.list"><?php echo tpl_modifier_tr('直播列表','site.header'); ?></a>
	                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgmemberset"])){; ?>
	                        <a href="/member.list"><?php echo tpl_modifier_tr('会员专区','site.header'); ?></a>
	                        <?php }; ?>
	                        <a href="/about"><?php echo tpl_modifier_tr('关于我们','site.header'); ?></a>
	                    </p>
	                </li>
                  <?php }; ?>
                </ul>
            </nav>
            <!--搜索-->
            <div class="g-so mt15 mr15 hidden-xs hidden-sm hidden-md">
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
    var mySwiper = new Swiper('.mob-nav',{
        slidesPerView :4,});
    var mob_width = 0;
        mob_width=$("#mob-nav a").outerWidth();
    var mob_index=$("#mob-nav a").length;
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
function searchSubmit() {
	var url = '';
	var search_field = $("input[name='search_field']").val();
    <?php if(SlightPHP\Tpl::$_tpl_vars["is_pro"]){; ?>
		url = '/course.list.courseList?';
    <?php }else{; ?>
        url = '/course.list?';
    <?php }; ?>
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
		searchSubmit();
	});

	$("#search_form_clear").click(function() {
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
			searchSubmit();
		}
	})
});
</script>
