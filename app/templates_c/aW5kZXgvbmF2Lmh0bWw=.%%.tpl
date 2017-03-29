<section>
    <div class="container">
        <div class="logo col-sm-2 col-xs-3 col-md-5 col-lg-4">
            <a href="/" title="云课"><img src="<?php echo utility_cdn::img('/assets_v2/img/logo.png'); ?>" alt="云课" class="hidden-xs hidden-sm"></a>
            <a href="/" title="云课"><img src="<?php echo utility_cdn::img('/assets_v2/img/yunkelogo.png'); ?>" alt="云课" class="hidden-lg hidden-md"></a>
        </div>
        <div class="so col-sm-13 col-xs-17 fs14 col-md-12 col-lg-10">
                <form id="search_form"  method="get" action="">
                    <div class="divselect select_border2px fs14">
                        <?php if(isset(SlightPHP\Tpl::$_tpl_vars["search_name"]) && SlightPHP\Tpl::$_tpl_vars["search_name"] =='course'){; ?>
                        <cite>课程</cite>
                        <?php }elseif((isset(SlightPHP\Tpl::$_tpl_vars["search_name"]) && SlightPHP\Tpl::$_tpl_vars["search_name"] == 'teacher')){; ?>
                        <cite>老师</cite>
                        <?php }elseif((SlightPHP\Tpl::$_tpl_vars["search_name"] == 'organization')){; ?>
                        <cite>学校</cite>
                        <?php }; ?>
                        <dl>
                            <dd class="se-dd"><a selectid='course' >课程</a></dd>
                            <dd class="se-dd"><a selectid='teacher'>老师</a></dd>
                            <dd class="se-dd"><a selectid='organization'>学校</a></dd>
                        </dl>
                    </div>
                    <?php /* <input id='search_name' name="search_name" type="hidden" value="<?php echo SlightPHP\Tpl::$_tpl_vars["search_name"]; ?>" />*/?>
                    <div class="so-input col-sm-11 col-xs-11 col-md-14 col-lg-15">
                        <input type="text" id="s_val" autocomplete="off" name="search_field" value="<?php echo SlightPHP\Tpl::$_tpl_vars["search_field"]; ?>" class="fs14 search-input"  placeholder="搜课程名称，科目试试吧">
                        <img id="s_cha" src="<?php echo utility_cdn::img('/assets_v2/img/hide.png'); ?>" alt="" />
                    </div>
                    <button id="search_btn" class="col-lg-2 col-md-2 col-sm-3 col-xs-3 fs16"></button>
                </form>
            </div>
            <?php /*<div class="so-hot fs14 cGray"><a href="#">小升初</a><a href="#">物理竞赛</a><a href="#">小升初</a></div>*/?>
        <div class="tel col-sm-5 hidden-xs col-md-5 col-lg-5">
            <div class="col-sm-16 c-fr"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/yunke-tel.png'); ?>" alt="客服电话"></div>
            <div class="icon tel-icon c-fr"></div>
        </div>
    </div>
</section>
<!-- nav -->
<section id="nav">
<div class="container">
    <div class="col-sm-4 categorys cWhite hidden-xs col-md-5 col-lg-4 hidden-sm" id="all_cate">
            <p><span class="icon list-icon fs16"></span><span class="fs16"><a href="/index.course.list">全部课程</a></span></p>
            <div class="categorys-list" id="cate_list">
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cateTree"])){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["cateTree"] as SlightPHP\Tpl::$_tpl_vars["m"]){; ?>
                    <ul class="categorys-menu pos-rel">
                        <li class="parent-menu fs16">
                            <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["m"]->url; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["m"]->grade; ?></a>
                        </li>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["m"]->children)){; ?>
                        <li class="parent-menu fs14">
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["m"]->children as SlightPHP\Tpl::$_tpl_vars["mm"]){; ?>
							<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["mm"]->url; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["mm"]->name; ?></a>
							<?php }; ?>
						</li>
						<?php }; ?>
                    <!-- sonmenu -->
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["m"]->children)){; ?>
                        <li class="categorys-menu-detail pos-abs">
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["m"]->children as SlightPHP\Tpl::$_tpl_vars["mm"]){; ?>
                            <dl class="fs14 col-sm-20 clearfix">
                                <dt class="tec col-sm-2 pd0">
                                    <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["mm"]->url; ?>" title=""><?php echo SlightPHP\Tpl::$_tpl_vars["mm"]->name; ?></a>
                                </dt>
                                <div class="pdr0 col-sm-18">
    								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["mm"]->attr)){; ?>
    								<?php foreach(SlightPHP\Tpl::$_tpl_vars["mm"]->attr as SlightPHP\Tpl::$_tpl_vars["ak"]=>SlightPHP\Tpl::$_tpl_vars["attr"]){; ?>
    									<dd class="tec">
    										<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["attr"]->url; ?>" title="<?php echo SlightPHP\Tpl::$_tpl_vars["attr"]->name; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["attr"]->name; ?></a>
    									</dd>
    									<?php if(SlightPHP\Tpl::$_tpl_vars["mm"]->attr_count != (SlightPHP\Tpl::$_tpl_vars["ak"]+1)){; ?>
    									<dd>|</dd>
    									<?php }; ?>
    								<?php }; ?>
    								<?php }; ?>
                                </div>
                            </dl>
							<?php }; ?>
                        </li>
						<?php }; ?>
                    <!-- /sonmenu -->
                    </ul>
				    <?php }; ?>
				<?php }; ?>
            </div>
        </div>
        <div class="col-sm-20 col-xs-20 fs16 col-md-15 col-lg-16 nav swiper-container4">
            <ul id="site_nav"class="swiper-wrapper">
                <?php echo tpl_function_part("/index/main/getplatformnav"); ?>
                <li class="visible-xs visible-sm swiper-slide"><a href="/index.course.list">全部课程</a></li>
                <?php if(isset(SlightPHP\Tpl::$_tpl_vars["wonav"]) && !empty(SlightPHP\Tpl::$_tpl_vars["wonav"])){; ?>
                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["wonav"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                    <li class="swiper-slide"><a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->url; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->title; ?><?php if(SlightPHP\Tpl::$_tpl_vars["v"]->title == '直播列表'){; ?><em class="new-icon"></em><?php }; ?></a></li>
                    <?php }; ?>
                <?php }; ?>
            </ul>
        </div>
    </div>
</section>
<style type="text/css">
#cate_list li{ line-height:33px;}#cate_list{ display:none;}
</style>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.so.js'); ?>"></script>
<script type="text/javascript"  src="<?php echo utility_cdn::js('/assets_v2/js/swiper.min.js'); ?>"></script>
<script type="text/javascript">
;(function() {
    if ($(window).width() < 768) {
        var mySwiper = new Swiper('.swiper-container4',{
        slidesPerView : 4,});
        var li_width = 0;
        li_width=$("#site_nav li").outerWidth();
        var li_index=$("#site_nav li").length;
        $("#site_nav").css('width',li_width*li_index);
    }
})()
$(function(){
    var siteNav = $("#site_nav");
    var winHref = window.location.pathname;
    siteNav.find("li").each(function(){
        var _this=$(this);
        var _this_href = _this.find('a').attr("href");
        _this_href = _this_href.substr(_this_href.lastIndexOf('/'));
        if(winHref == _this_href){
            _this.addClass("curr");
        }
    });
    $.divselect(".divselect cite");
	function search_submit(){
        var url = '';
        var search_name  = $('cite').text();
		var oSearchVal = $("input[name='search_field']").val();
        if(search_name == '课程'){
            url = '/index.course.list';
        }else if(search_name == '老师'){
            url = '/index.teacher.list';
        }else if(search_name == '学校'){
            url = '/index.org.list';
        }
		 window.location.href=url+"?search_field="+escape(oSearchVal);
	}
    $('#search_btn').click(function(){
		search_submit();
		return false;
    });

	var old_se_name = $('cite').text();
    var oSearchVal = $("input[name='search_field']").val();
    //搜索框判断有无值
    if(oSearchVal != "") {
        $(".so-input img").show();
    }else{
        $(".so-input img").hide();
    }

	$('#s_cha').click(function(){
    	$("input[name='search_field']").val('');
        $(".so-input img").hide();
		search_submit();
		return false;
	});
	$('.se-dd a').click(function(){
		var s_val = $('#s_val').val();
		var now_se_name = $(this).text();
		$('cite').text(now_se_name);
		if(now_se_name != old_se_name && s_val !=''){
			search_submit();
			return false;
		}
        if($(".divselect cite").text() == "课程") {
            $("input[name='search_field']").attr("placeholder","搜课程名称，科目试试吧");
        }else if($(".divselect cite").text() == "老师"){
            $("input[name='search_field']").attr("placeholder","搜教师名称，科目试试吧");
        }else if($(".divselect cite").text() == "学校"){
            $("input[name='search_field']").attr("placeholder","搜学校名称，简介试试吧");
        }
	});


    $("#all_cate").hover(function(){
        $("#cate_list").show();
    },function(){
        $("#cate_list").hide();
    })
    /*
    $("#all_cate").hover(function(){
        clearTimeout(cate_t);
        $("#cate_list").animate({ height:"360px" });
    },function(){
        call_t=setTimeout(function(){ $("#cate_list").animate({ height:"0px" }) },2000);
    })
    */

    $(".categorys-list ul").hover(function() {
        $(this).find(".categorys-menu-detail").show();
        $(this).find(".parent-menu-title").css("color","#0477c0");
    },function(){
        $(this).find(".categorys-menu-detail").hide();
        $(this).find(".parent-menu-title").css("color","#fff");
    })

});
</script>
