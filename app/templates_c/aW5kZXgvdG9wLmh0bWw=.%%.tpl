<!-- header -->
<header>
    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"]) && !empty(SlightPHP\Tpl::$_tpl_vars["closeTips"])){; ?>
    <div class="w100-pre hidden-xs hidden-sm col-lg-20 col-md-20">
        <div class="tp-lg-success-tip-tp container">
            <div class="w292 c-fl">
                <a href="/index.user.info">快点击这里完善资料，可以让老师更快的认识你。</a>
            </div>
            <a href="javascript:;" class="tp-lg-suess-delt-btn c-fr"></a>
        </div>
    </div>
    <?php }; ?>
    <div class="P_SiteNav">
        <div class="container">
        <div class="row">
            <div class="c-fl login">
                <span class="hidden-xs">您好，欢迎来到云课网！&nbsp;&nbsp;</span>
                <a href="/">云课首页</a> |
                <span id="xplat_url" class="hidden-xs hidden-sm">
				</span>
				<a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.yunke.android" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["uri"])){; ?><?php if(SlightPHP\Tpl::$_tpl_vars["uri"]=='help'){; ?>class="cBlue"<?php }; ?><?php }; ?>>APP下载</a>
            </div>
				<div id="topnav">
				</div>
            <script type="text/javascript">
				$("#topnav").load("/index.main.topNav");
				$(document).ready(function(){
					if(document.referrer.indexOf("www.talkwebedu.com")!=-1){
					$("#xplat_url").html(' <a target="_blank" data="https://www.talkwebedu.com">拓维教育</a> | <a target="_blank" data="https://www.talkwebedu.com/Index/jumpFor?jumpUrl=https://www.yuncelian.com/3rd/login/xplatform">云测练</a> | <a target="_blank" data="https://www.talkwebedu.com/Index/jumpFor?jumpUrl=http://www.yunbaobei.com/login/xplatform">云宝贝</a> | <a target="_blank" data="https://www.talkwebedu.com/Index/jumpFor?jumpUrl=http://www.yunxiaoyuan.com/userAccount/login">云校园</a> | ');
						var url="/user.parterner.xplatformJump?url=";
						$("#xplat_url a").each(function(){
							$(this).attr("href",url+escape($(this).attr("data")));
						});
					}
				});
            </script>
        </div>
    </div>
    </div>
</header>
<script type="text/javascript">
$(function() {
    //top
    $(".w100-pre .tp-lg-suess-delt-btn").click(function() {
        $.post( "index.main.CloseTips", function(r) {
            $(".w100-pre").hide();
        });
    })
    //mob menu
});
</script>
