<?php if(SlightPHP\Tpl::$_tpl_vars["is_pro"]){; ?>
<footer class="index-foot col-xs-20">
	<div class="container">
		<div class="row">
			<div class="foot-tag col-lg-10 col-md-12 hidden-xs hidden-sm">
				<ul class="tag-list col-lg-6 col-md-7">
					<div class="tag-title">常见问题</div>
					<li class="tag">
						<a href="/site.help.register">注册和登录</a>
					</li>
					<li class="tag">
						<a href="/site.help.edit">修改基础资料</a>
					</li>
					<li class="tag">
						<a href="/site.help.unabletospeak">上课语音</a>
					</li>
				</ul>
				<ul class="tag-list col-lg-6 col-md-7">
					<div class="tag-title">如何上课</div>
					<li class="tag">
						<a href="/site.help.howtopay">报名与支付</a>
					</li>
					<li class="tag">
						<a href="/site.help.howtoclass">选择课程</a>
					</li>
					<li class="tag">
						<a href="/site.help.mycourse">查看课表</a>
					</li>
				</ul>
				<ul class="tag-list col-lg-6 col-md-6">
					<div class="tag-title">合作共赢</div>
					<!--<li class="tag">-->
						<!--<a href="/about">内容招募</a>-->
					<!--</li>-->
					<li class="tag">
						<a href="/about.main.business">商务合作</a>
					</li>
					<!--<li class="tag">-->
						<!--<a href="/about">技术合作</a>-->
					<!--</li>-->
				</ul>
			</div>
			<div class="foot-info col-lg-10 col-md-8 col-sm-20 col-xs-20 right ">
				<div class="project-info right">
					<div class="project-title hidden-xs hidden-sm">
						小沃学堂
					</div>
					<div class="project-tag">
						<span> <a href="/about.main.contact"> 联系我们</a></span>
						<span class="split">|</span>
						<span> <a href="/about"> 关于我们</a></span>
					</div>
					<div class="company-name">
						2016 中国联通小沃科技有限公司
					</div>
					<div class="company-regId">
						京ICP备14053191号
					</div>
				</div>
				<div class="QR-code right hidden-xs hidden-sm">
					<div class="img-box">
						<img src="<?php echo utility_cdn::img('/assets_v2/img/app_qrcode.png'); ?>" alt="">
					</div>
					<div class="cWhite img-dec">
						扫描下载APP
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
</footer>
<?php }else{; ?>
<footer>
<p class="fs14 cGray"><?php echo tpl_modifier_tr('课程版权归','site.footer'); ?> <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgInfo"]->subname)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["orgInfo"]->subname; ?><?php }else{; ?>高能壹佰<?php }; ?> <?php echo tpl_modifier_tr('所有','site.footer'); ?></p>
<div class="container mobile-footer">
<?php /*<a href="#">申请入驻</a> | <a href="#">服务协议</a>  | */?>
<a href="/about"><?php echo tpl_modifier_tr('关于','site.footer'); ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgInfo"]->subname)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["orgInfo"]->subname; ?><?php }else{; ?>高能壹佰<?php }; ?></a> |
<a href="/about.main.contact"><?php echo tpl_modifier_tr('联系我们','site.footer'); ?></a> |
<a href="https://<?php echo SlightPHP\Tpl::$_tpl_vars["platform_url"]; ?>/index.feedback" target="_blank"><?php echo tpl_modifier_tr('意见反馈','site.footer'); ?></a> |
<a href="https://<?php echo SlightPHP\Tpl::$_tpl_vars["platform_url"]; ?>/index.help" target="_blank"><?php echo tpl_modifier_tr('在线帮助','site.footer'); ?></a> |
<a href="https://<?php echo SlightPHP\Tpl::$_tpl_vars["platform_url"]; ?>/index" target="_blank"><?php echo tpl_modifier_tr('云课首页','site.footer'); ?></a>
<div style="display:" id="lang"><a class="lang" val="zh-cn">中文</a><a class="lang" val="en">English</a></div>
<script>
$(function(){
	var dCookie=document.cookie;
	if(dCookie.indexOf("language")>=0){
	var language=dCookie.substring(dCookie.indexOf("language"));
	var languageItem=language.substring(0,language.indexOf(";"));
	var languageValue=languageItem.substr(languageItem.indexOf("=")+1)
    $("#lang a").each(function(){
		if($(this).attr("val")==languageValue){
		$(this).addClass("lang-select");
            if($(this).attr("val")=="en"){
                $(".video-try-icon").removeClass("try-icon").addClass("en-try-icon");
                $("#en-upload-imgtip").show();//机构管理-首页维护
            }
        }
	})
	}else{
	$("footer .lang:first").addClass("lang-select");

    }
})
$("footer .lang").click(function(){
	var d=new Date();
	var expireDays=300;
	d.setTime(d.getTime()+expireDays*24*3600*1000);
	document.cookie = "language="+$(this).attr("val")+"; path=/; expires="+d.toGMTString();
	location.reload();
});
</script>
</div>
<p class="cGray">Copyright© 2016 <?php echo tpl_modifier_tr('北京高能壹佰教育科技有限公司',"site.footer"); ?></p>
<p class="cGray"><?php echo tpl_modifier_tr('京ICP备14053191号','site.footer'); ?>&nbsp;&nbsp;<?php echo tpl_modifier_tr('云课','site.footer'); ?></p>
<!--上课提醒-->
<script>
    wo.remindCard();
</script>
<div style="display:none">
<?php echo tpl_function_part('/site.main.statistic'); ?>
<!--<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1253775234'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s4.cnzz.com/stat.php%3Fid%3D1253775234' type='text/javascript'%3E%3C/script%3E"));</script>-->
</div>
</footer>
<?php }; ?>
