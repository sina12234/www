<div class="col-sm-4 help-menu hidden-xs" id="help-menu-pc">
    <p class="help-bar fs16"><span class="icon help-arrow-icon"></span><span class="icon help-icon"></span>帮助中心</p>
    <ul class="nv1">
        <li <?php if(in_array(SlightPHP\Tpl::$_tpl_vars["type"],array('register','edit','unabletospeak'))){; ?>class="curr"<?php }; ?>>
            <p href="#" class="fs16"><span class="arrow-down"></span>常见问题</p>
            <ul class="sub-menu fs14">
                <li <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 'register'){; ?> class="curr"<?php }; ?>><a href="/index.help.register">注册和登录</a></li>
                <li <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 'edit'){; ?> class="curr"<?php }; ?>><a href="/index.help.edit">修改个人资料</a></li>
                <li <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 'unabletospeak'){; ?> class="curr"<?php }; ?>><a href="/index.help.unabletospeak">上课无法语音说话</a></li>
            </ul>
        </li>
        <li <?php if(in_array(SlightPHP\Tpl::$_tpl_vars["type"],array('howtopay','howtoclass','mycourse','coupon'))){; ?>class="curr"<?php }; ?>>
            <p href="#" class="fs16" id="up"><span class="arrow-down"></span>我是学生</p>
            <ul class="sub-menu fs14">
                <li <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 'howtopay'){; ?> class="curr"<?php }; ?>><a href="/index.help.howtopay">报名与支付</a></li>
                <li <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 'howtoclass'){; ?> class="curr"<?php }; ?>><a href="/index.help.howtoclass">如何上课</a></li>
                <li <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 'mycourse'){; ?> class="curr"<?php }; ?>><a href="/index.help.mycourse">我的课程</a></li>
                <li <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 'coupon'){; ?> class="curr"<?php }; ?>><a href="/index.help.coupon">优惠券</a></li>
            </ul>
        </li>
        <li <?php if(in_array(SlightPHP\Tpl::$_tpl_vars["type"],array('howtoteach','editinfo'))){; ?>class="curr"<?php }; ?>>
            <p href="#" class="fs16" id="up"><span class="arrow-down"></span>我是老师</p>
            <ul class="sub-menu fs14">
                <li <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 'howtoteach'){; ?> class="curr"<?php }; ?>><a href="/index.help.howtoteach">如何教课</a></li>
                <li <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 'editinfo'){; ?> class="curr"<?php }; ?>><a href="/index.help.editinfo">资料维护</a></li>
            </ul>
        </li>
        <li <?php if(in_array(SlightPHP\Tpl::$_tpl_vars["type"],array('join','indexmanage','coursemanage','personmanage','order'))){; ?>class="curr"<?php }; ?>>
            <p href="#" class="fs16" id="up"><span class="arrow-down"></span>我是管理员</p>
            <ul class="sub-menu fs14">
                <li <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 'join'){; ?> class="curr"<?php }; ?>><a href="/index.help.join">入驻云课</a></li>
                <li <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 'indexmanage'){; ?> class="curr"<?php }; ?>><a href="/index.help.indexmanage">首页维护</a></li>
                <li <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 'coursemanage'){; ?> class="curr"<?php }; ?>><a href="/index.help.coursemanage">课程管理</a></li>
                <li <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 'personmanage'){; ?> class="curr"<?php }; ?>><a href="/index.help.personmanage">老师和学生管理</a></li>
                <li <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 'order'){; ?> class="curr"<?php }; ?>><a href="/index.help.order">订单和优惠码</a></li>
                <li <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 'settle'){; ?> class="curr"<?php }; ?>><a href="/index.help.settle">结算管理</a></li>
				<li	<?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 'extension'){; ?> class="curr"<?php }; ?>><a href="/index.help.extension">推广规则</a></li>
            </ul>
        </li>
    </ul>
</div>
<script type="text/javascript">
$(function(){
	$(".help-menu .sub-menu").hide();
    //$(".help-menu .sub-menu").first().show();
	$(".nv1>li>p").click(function(){
		if($(this).parent().find(".sub-menu").css("display")=="none"){
			$(this).parents('.nv1').find(".sub-menu").slideUp(300);
            $(this).parent().find(".sub-menu").slideDown(300);
			$(this).parent().addClass('curr');
		}else{
			$(this).parent().find(".sub-menu").slideUp(300);
			$(this).parent().removeClass('curr');
		};
	});
	var url=document.location.href;
	var url1=url.split(".com");
	//console.log(url1[1]);
	$(".sub-menu a").each(function(){
		var sss=$(this).attr('href');
		var url = window.location.href;
        var loc = url.substring(url.lastIndexOf('/'), url.length);
      console.log(loc);
      if(sss===loc){
			$(this).parent().addClass('curr');
			$(this).closest('.sub-menu').show();
			//console.log($(this).parent().html());
			return false;
     }
     if(loc==="/index.help"){
        $(".help-menu .sub-menu:first").show();
     }
     if(loc==="/order"){
        $(".help-menu .sub-menu:last").show();
			$(".help-menu .sub-menu:last li:last").addClass('curr');
     }
	});
})
</script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.divselect.js'); ?>"></script>
<script>
var top_nav_img="<?php echo utility_cdn::img('/assets_v2/img/qrcode.jpg'); ?>";
</script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/top_nav.js'); ?>"></script>
<script>
$(function() {
    //$.divselect(".divselect cite");
});
</script>
