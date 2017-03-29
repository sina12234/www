<header class="hidden-xs hidden-sm">
    <div class="P_SiteNav">
        <div class="container">
        <div class="row">
            <div class="c-fl login">
                <span class="hidden-xs">您好，欢迎来到云课网！&nbsp;&nbsp;</span>
                <?php /*<a href="/" <?php if(SlightPHP\Tpl::$_tpl_vars["uri"]=='/'){; ?>class="cBlue"<?php }; ?>>云课首页</a>|<a href="/index.main.client" <?php if(SlightPHP\Tpl::$_tpl_vars["uri"]=='help'){; ?>class="cBlue"<?php }; ?>>客户端下载</a>*/?>
            </div>
            <div class="c-fr other">
                <?php if(empty(SlightPHP\Tpl::$_tpl_vars["user"])){; ?>
                <a href="/index.main.login" <?php if(SlightPHP\Tpl::$_tpl_vars["uri"]=='login'){; ?>class="cBlue"<?php }; ?>>登录</a>|<a href="/index.main.register" <?php if(SlightPHP\Tpl::$_tpl_vars["uri"]=='register'){; ?>class="cBlue"<?php }; ?>>注册</a>
                <?php }else{; ?>
                <ul class="user-menu">
                    <li class="user-menu-info">
                        <a href="#">
                            <span class="face">
                                <img src="<?php if(SlightPHP\Tpl::$_tpl_vars["user"]['large']){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['large']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/1.png'); ?><?php }; ?>">
                            </span>
                            <span id="chick-down-show"><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['name']; ?><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/index-down.png'); ?>"></span>
                        </a> <span class="c-fr">|</span>
                        <i class="jigou-dash"></i>
                        <div class="sub-menu" id="sub-menu">
                            <div class="sub-menu-c">
                                <a href="/index.user.info"><span class="platform-icon form-icon-1"></span>个人资料</a>
                                <?php if(SlightPHP\Tpl::$_tpl_vars["user"]['types']->teacher==1){; ?>
                                <a class=" hidden-lg hidden-md" href="/index.teacher.timetable"><span class="platform-icon form-icon-2"></span>教学管理</a>
                                <?php }; ?>
                                <a class=" hidden-lg hidden-md"  href="/index.growth.entry"><span class="platform-icon form-icon-3"></span>学习中心</a>
                                <a href="/index.student.order"><span class="platform-icon form-icon-4"></span>我的订单</a>
                                <a class="hidden-lg hidden-md" href="/index.user.message"><span class="platform-icon form-icon-5"></span>我的消息</a>
                                <a href="/index.user.password"><span class="platform-icon form-icon-6"></span>安全设置</a>
                                <a href="/index.main.logout"><span class="platform-icon form-icon-7"></span>退出</a>
                            </div>
                        </div>
                    </li>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["isAdmin"]==1){; ?>
                    <li class="hidden-sm hidden-xs jigou">
                        <a href="#">机构管理<img src="<?php echo utility_cdn::img('/assets_v2/img/platform/index-down.png'); ?>"></a> <span class="c-fr">|</span>
                        <i class="jigou-dash"></i>
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgList"])){; ?>
                        <div class="sub-menu">
                            <div class="sub-menu-c">
                                <?php foreach(SlightPHP\Tpl::$_tpl_vars["orgList"] as SlightPHP\Tpl::$_tpl_vars["org"]){; ?>
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["org"]->subdomain; ?>" target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["org"]->subname; ?></a>
                                <?php }; ?>
                            </div>
                        </div>
                        <?php }; ?>
                    </li>
                    <?php }; ?>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["user"]['types']->teacher==1){; ?>
                    <li class="hidden-sm hidden-xs">
                        <a href="/index.teacher.timetable">教学管理</a> |
                    </li>
                    <?php }; ?>
                    <li class="hidden-sm hidden-xs">
                        <a href="/index.growth.entry">学习中心</a> |
                    </li>
                    <li class="hidden-sm hidden-xs">
                        <a href="/index.user.message" >
                            <span class="msg-icon icon">
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["retMessagesNum"])){; ?>
                                <span class="msg-num"></span>
                                <?php }; ?>
                            </span>

                        </a>
                    </li>
                </ul>
                <?php }; ?>
            </div>
        </div>
    </div>
    </div>
</header>
<section>
    <div class="container">
        <div class="row">
            <div class="logo col-sm-6 col-xs-8 hidden-md hidden-lg" id="mob-list-btn">
                <img src="<?php echo utility_cdn::img('/assets_v2/img/logo.png'); ?>" alt="云课">
                <span class="visible-xs visible-sm" id="mob_list"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/mob-list.png'); ?>" style="width:30px;height:30px"></span>
            </div>
            <div class="logo col-lg-4 col-md-5 hidden-sm hidden-xs">
                <a href="/index"><img src="<?php echo utility_cdn::img('/assets_v2/img/logo.png'); ?>" alt="云课"></a>
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"]['types']->teacher)&&SlightPHP\Tpl::$_tpl_vars["type"]=='teacher'){; ?>
                <a href="/index.teacher.timetable" class="c-fl fs18 hidden-xs hidden-sm" style="margin:11px 0 0 11px;">教学中心</a>
                <?php /*<a href="/index.teacherblog.entry/<?php echo SlightPHP\Tpl::$_tpl_vars["user"]['uid']; ?>" class="myhomepage">我的主页</a>*/?>
                <?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["user"]['types']->student)&&SlightPHP\Tpl::$_tpl_vars["type"]=='student'){; ?>
                <a href="/index.growth.entry" class="c-fl fs18 hidden-xs hidden-sm" style="margin:11px 0 0 11px;">学习中心</a>
                <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type"]=='message'){; ?>
                <a href="/index.user.message" class="c-fl fs18 hidden-xs hidden-sm" style="margin:11px 0 0 11px;">消息中心</a>
                <?php }else{; ?>
                <a href="/index.user.info" class="c-fl fs18 hidden-xs hidden-sm" style="margin:11px 0 0 11px;">基础设置</a>
                <?php }; ?>
                <a class="user-logo-tips hidden-xs hidden-sm" href="/index">返回云课首页</a>
            </div>
            <div class="mob-user col-sm-5 visible-xs visible-sm" id="mob-userlist-btn">
                <a href="javascript:void(0)">
                    <span class="face">
                        <img src="<?php if(SlightPHP\Tpl::$_tpl_vars["user"]['large']){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['large']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/1.png'); ?><?php }; ?>">
                    </span>
                    <span id="chick-down-show"><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['name']; ?>&nbsp;&nbsp;<img src="<?php echo utility_cdn::img('/assets_v2/img/platform/index-down.png'); ?>"></span>
                </a>
            </div>
            <div class="col-sm-10 col-lg-10 col-md-8 fs16 visible-lg">		  
                <ul class="user-nav">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"]['types']->teacher) && SlightPHP\Tpl::$_tpl_vars["type"]=='teacher'){; ?>
					<li class="col-lg-4 col-md-5">
						<a href="/index.growth.entry">学习中心</a>
					</li>
					<li class="col-lg-4 col-md-5">
						<a href="/index.user.message">消息中心</a>
					</li>
					<li class="col-lg-4 col-md-5">
						<a href="/index.user.info">基础设置</a>
					</li>
					<li class="col-lg-4 col-md-5">
						<a href="/index.teacherblog.entry/<?php echo SlightPHP\Tpl::$_tpl_vars["user"]['uid']; ?>" target="_blank">我的主页</a>
					</li>
					<?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["user"]['types']->teacher) && SlightPHP\Tpl::$_tpl_vars["type"]=='student')){; ?>
					<li class="col-lg-4 col-md-5">
						<a href="/index.teacher.timetable">教学中心</a>
					</li>
					<li class="col-lg-4 col-md-5">
						<a href="/index.user.message" >消息中心</a>
					</li>
					<li class="col-lg-4 col-md-5">
						<a href="/index.user.info">基础设置</a>
					</li>
					<?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["user"]['types']->student) && SlightPHP\Tpl::$_tpl_vars["type"]=='student')){; ?>
					<li class="col-lg-4 col-md-5">
						<a href="/index.user.message">消息中心</a>
					</li>
					<li class="col-lg-4 col-md-5">
						<a href="/index.user.info">基础设置</a>
					</li>
					<?php }elseif((SlightPHP\Tpl::$_tpl_vars["type"]=='message')){; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"]['types']->teacher)){; ?>
						<li class="col-lg-4 col-md-5">
							<a href="/index.teacher.timetable">教学中心</a>
						</li>
						<?php }; ?>
						<li class="col-lg-4 col-md-5">
							<a href="/index.growth.entry">学习中心</a>
						</li>
						<li class="col-lg-4 col-md-5">
							<a href="/index.user.info">基础设置</a>
						</li>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"]['types']->teacher)){; ?>
						<li class="col-lg-4 col-md-5">
							<a href="/index.teacherblog.entry/<?php echo SlightPHP\Tpl::$_tpl_vars["user"]['uid']; ?>" target="_blank">我的主页</a>
						</li>
						<?php }; ?>
					<?php }elseif((SlightPHP\Tpl::$_tpl_vars["type"] == 'setting')){; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"]['types']->teacher)){; ?>
						<li class="col-lg-4 col-md-5">
							<a href="/index.teacher.timetable">教学中心</a>
						</li>
						<?php }; ?>
						<li class="col-lg-4 col-md-5">
							<a href="/index.growth.entry">学习中心</a>
						</li>
						<li class="col-lg-4 col-md-5">
							<a href="/index.user.message" >消息中心</a>
						</li>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"]['types']->teacher)){; ?>
						<li class="col-lg-4 col-md-5">
							<a href="/index.teacherblog.entry/<?php echo SlightPHP\Tpl::$_tpl_vars["user"]['uid']; ?>" target="_blank">我的主页</a>
						</li>
						<?php }; ?>
					<?php }; ?>
                </ul>
            </div>
            <div class="col-sm-6 col-lg-6 col-md-7 c-fr hidden-sm hidden-xs">
                <div class="so col-sm-20 fs14" style="margin:35px 0 0 10px;">
                    <form id="search_form"  method="get" action="">
                        <div class="divselect fs14 select_border2px">
                            <cite>课程</cite>
                            <dl>
                                <dd class="se-dd"><a selectid='course' >课程</a></dd>
                                <dd class="se-dd"><a selectid='teacher'>老师</a></dd>
                            </dl>
                            <?php /* <input id='search_name' name="search_name" type="hidden" value="<?php echo SlightPHP\Tpl::$_tpl_vars["search_name"]; ?>" />*/?>
                        </div>
                        <div class="so-input col-sm-12">
                            <input type="text" id="s_val" name="search_field" value="<?php echo SlightPHP\Tpl::$_tpl_vars["search_field"]; ?>" placeholder="搜课程名称，科目试试吧">
                        </div>
                        <button id="search_btn" class="col-sm-3  fs18"></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="sub-menu" id="mob_submenu" style="display:none">
    <div class="sub-menu-c">
        <a href="/index.user.info"><span class="platform-icon form-icon-1"></span>基础资料</a>
        <?php if(SlightPHP\Tpl::$_tpl_vars["user"]['types']->teacher==1){; ?>
        <a href="/index.teacher.timetable"><span class="platform-icon form-icon-2"></span>教学管理</a>
        <?php }; ?>
        <a href="/index.growth.entry"><span class="platform-icon form-icon-3"></span>学习管理</a>
        <a href="/index.student.order"><span class="platform-icon form-icon-4"></span>我的订单</a>
        <a class="hidden-lg hidden-md" href="/index.user.message"><span class="platform-icon form-icon-5"></span>我的消息</a>
        <a href="/index.user.password"><span class="platform-icon form-icon-6"></span>安全设置</a>
        <a href="/index.main.logout"><span class="platform-icon form-icon-7"></span>退出</a>
    </div>
</div>
<div id="mob-bg" style="display:none;position:absolute;top:60px;right:0;bottom:0;left:0;background:rgba(0,0,0,.8);z-index:99"></div>
<ul id="mob-list">
    <li><a href="/index">首页</a></li>
    <li><a href="/index.course.list">全部课程</a></li>
    <li><a href="/index.teacher.list">明星教师</a></li>
    <li><a href="/index.plan.list">直播列表</a></li>
    <li><a href="/index.help">在线帮助</a></li>
    <?php if(SlightPHP\Tpl::$_tpl_vars["user"]['types']->teacher==1){; ?>
    <li><a href="/index.teacher.timetable">我的课程</a></li>
    <?php }else{; ?>
    <li><a href="/index.student.course">我的课程</a></li>
    <?php }; ?>
</ul>
<!--下拉效果展示列表-->
<!--<ul id="mob-user-list">-->
    <!--<li><a href="/index.user.info">基础设置</a></li>-->
    <!--<li><a href="/index.student.order">我的订单</a></li>-->
    <!--<li><a href="/index.growth.entry">学习中心</a></li>-->
    <!--<?php if(SlightPHP\Tpl::$_tpl_vars["user"]['types']->teacher==1){; ?>-->
    <!--<li><a href="/index.teacher.timetable">教学管理</a></li>-->
    <!--<?php }; ?>-->
    <!--<li><a href="/index.user.password">安全设置</a></li>-->
    <!--<li><a href="/index.user.message">我的消息</a></li>-->
    <!--<li><a href="/index.main.logout">退出</a></li>-->
<!--</ul>-->
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.so.js'); ?>"></script>
<script type="text/javascript">
$(function(){
    $.divselect(".divselect cite");
	function search_submit(){
        var url = '';
        var search_name  = $('cite').text();
        if(search_name == '课程'){
            url = '/index.course.list';
        }else if(search_name == '老师'){
            url = '/index.teacher.list';
        }
        $('#search_form').attr('action', url);
        $('#search_form').submit();
	}
    $('#search_btn').click(function(){
		search_submit();
    });

	var old_se_name = $('cite').text();
    var oSearchVal = $("input[name='search_field']").val();
    //搜索框判断有无值
    if(oSearchVal != "") {
        $(".so-input img").show();
    }else{
        $(".so-input img").hide();
    }

	$('.se-dd a').click(function(){
		var s_val = $('#s_val').val();
		var now_se_name = $(this).text();
		$('cite').text(now_se_name);
		if(now_se_name != old_se_name && s_val !=''){
			search_submit();
		}
	});
    var cate_t;
    $("#all_cate").hover(function(){
        clearTimeout(cate_t);
        $("#cate_list").animate({ height:"360px" });
    },function(){
        call_t=setTimeout(function(){ $("#cate_list").animate({ height:"0px" }) },2000);
    });
    var doc_height=$(document).height();
    $("#mob-list-btn").click(function(){
        $("#mob-bg").show();
        if($("#mob-list").height()=="300"){
            $("#mob-list,#mob-bg").animate({ height:"0px" });
        }else{
            $("#mob-list").animate({ height:"300px" });
            $("#mob-bg").animate({ height:doc_height });
        }
        $("#mob-user-list").animate({ height:"0px" });
    });
    $("#mob-userlist-btn").click(function(){
//        下拉效果展开列表
//        $("#mob-bg").show();
//        if($("#mob-user-list").height()=="335"){
//            $("#mob-user-list,#mob-bg").animate({ height:"0px" });
//        }else{
//            $("#mob-user-list").animate({ height:"335px" });
//            $("#mob-bg").animate({ height:doc_height });
//        }
//        $("#mob-list").animate({ height:"0px" });
        $("#mob_submenu").toggle();
    });
    $("#mob-bg").click(function(){
        $("#mob-list,#mob-user-list").animate({ height:"0px" });
        $("#mob-bg").hide();
    });
    if($('.jigou .sub-menu-c').find('a').length >= 6) {
        $('.jigou').find('.sub-menu').addClass('org-sub-menu');
    }
});
</script>
