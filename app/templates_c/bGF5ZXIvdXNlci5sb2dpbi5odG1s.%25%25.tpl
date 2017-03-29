<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>高能100 - 专业的K12在线教育平台</title>
<meta name="title" content="高能100 - 专业的K12在线教育平台 在线直播">
<meta name="keywords" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线 k12 小学数学 初中数学">
<meta name="description" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线直播">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<body>
<section id="wrap-login">
    <div class="col-xs-20 pd0">
        <ul class="login-content-btn fs14 clearfix">
            <li class="col-xs-10 tec curr">账号登录</li>
            <li class="col-xs-10 tec">验证码登录</li>
        </ul>
        <ul class="login-content-ct clearfix">
            <li class="col-xs-20 mt20" style="display:block;">
                <form novalidate  method="post" autocomplete="off" id="login_form">
                    <div class="pos-rel col-xs-20 pd0 mb20 mobileNum">
                        <div class="c-fl mr10 category-mobile fs12 tec bor1px">
                            +<span class="mobile-number verifyCode">86</span>
                            <i class="c-fr mt15 mr5"></i>
                        </div>
                        <dl class="fs14 pos-abs col-xs-20 pd0 areaCountry bor1px" style="display:none;">
                            <div class="col-xs-20 clearfix fs12 areaCountryInfo-box pd0" id="internationCode"></div>
                            <dd class="col-xs-20 fs12 tec clearfix moreAreaCountry">
                                更多国家
                                <input type="hidden" name="cid" value="1"/>
                            </dd>
                        </dl>
                        <div class="pos-rel col-xs-16 c-fr pd0">
                            <input type="tel" autocomplete="off" placeholder="<?php echo tpl_modifier_tr('请输入手机号','site.login'); ?>" name="uname" id="user_name" class="bor1px col-xs-20 mobile-ipt iptFocusBlur" />
                            <input type='hidden' name="areaCode" value="86"/>
                            <i class="mobile-icon pos-abs"></i>
                        </div>
                    </div>

                    <div class="col-xs-20 red mb10" style="display:block;"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["error2"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["error2"]; ?><?php }; ?></div>

                    <div class="col-xs-20 pd0  pos-rel">
                        <input placeholder="<?php echo tpl_modifier_tr('请输入密码','site.login'); ?>" type="password" name="password" id="x_pass" autocomplete="off" class="bor1px col-xs-20 password-ipt iptFocusBlur"  />
                        <i class="password-icon pos-abs"></i>
                    </div>
					<div class="x_error_left red col-xs-20 mb10" style="display:block;"></div>
                    <div class="col-xs-20 pd0 mb10">
                        <label class="c-fl">
                            <input style="vertical-align: middle;" type="checkbox" name="forever"/> <?php echo tpl_modifier_tr('7日内免登录','site.login'); ?>
                        </label>
                        <a class="c-fr closePassword" href="/site.main.forget1" target="_blank"><?php echo tpl_modifier_tr('忘记密码','site.login'); ?>?</a>
                    </div>
                    <div class="col-xs-20 pd0 mb10">
                        <input class="col-xs-20 x_btn_reg2" type="submit" value="<?php echo tpl_modifier_tr('登录','site.login'); ?>" />
                    </div>
                    <div class="col-xs-20 pd0 mb10">
                        <span class="c-fl mr10 mt10 fs14">
                            <?php echo tpl_modifier_tr('其他方式登录','site.login'); ?>：
                        </span>
                        <a class="login snsi si_weixin c-fl mr10" target="_blank" href="/user.parterner.weixin/"></a>
                        <a class="login snsi si_qq c-fl" target="_blank" href="/user.parterner.qq"></a>
                    </div>
                    <div class="col-xs-20 pd0 mb10">
                        <div class="c-fr clearfix">
                            <?php echo tpl_modifier_tr('没有账号','site.login'); ?>? <a class="lkblue"  href="/site.main/register" target="_blank"><?php echo tpl_modifier_tr('立即注册','site.login'); ?>&gt;&gt;</a>
                        </div>
                    </div>
                </form>
            </li>
            <li class="col-xs-20 mt20">
                <form id="smsForm" method="post" action="#" autocomplete="off">
                    <div class="pos-rel col-xs-20 pd0 mb20">
                        <div class="c-fl mr10 category-mobile fs12 tec bor1px">
                            +<span class="mobile-number smsNumCode">86</span>
                            <i class="c-fr mt15 mr5"></i>
                        </div>
                        <dl class="fs14 pos-abs col-xs-20 pd0 areaCountry bor1px" style="display:none;">
                         <div class="col-xs-20 clearfix areaCountryInfo-box fs12 pd0" id="smsInternationCode"></div>
                            <dd class="col-xs-20 fs12 tec clearfix smsMoreAreaCountry">
                                更多国家
                                <input type="hidden" name="cid" value="1"/>
                            </dd>
                        </dl>
                        <div class="pos-rel col-xs-16 c-fr pd0">
                            <input type="text" autocomplete="off" placeholder="<?php echo tpl_modifier_tr('请输入手机号','site.login'); ?>" name="mobile" id="x_uname"  class="bor1px col-xs-20 mobile-ipt iptFocusBlur" />
                            <input type="hidden" name="tagCode" value="1"/>
                            <i class="mobile-icon pos-abs"></i>
                        </div>
                    </div>
					<div class="col-sm-20 pd0 mb20 col-xs-20">
                        <input type="text" name="verifyCode" placeholder="请输入图片验证码" class="bor1px col-xs-15 iptFocusBlur mobile-ipt">
                        <img src="/captcha.gen" id="cap" class="col-xs-5 c-fr pd0">
                    </div>
                    <div class="col-xs-20 pd0 mb20">
                        <div class="col-xs-15 pd0 pos-rel">
                            <input placeholder="<?php echo tpl_modifier_tr('请输入手机验证码','site.login'); ?>" type="text" name="smspassword" id="x_pass" autocomplete="off" class="bor1px col-xs-20 password-ipt iptFocusBlur"/>
                        </div>
                        <div class="col-xs-5 pd0">
                            <input type="button" value="获取验证码" id="get_verify_code" class="c-fr VerificatCodeBtn bor1px tec">
                        </div>
						<div class="x_error_right red col-xs-20" style="display:block;"></div>
                    </div>
                    <div class="col-xs-20 pd0 mb10">
                        <label class="c-fl">
                            <input style="vertical-align: middle;" type="checkbox" name="forever"/> <?php echo tpl_modifier_tr('7日内免登录','site.login'); ?>
                        </label>
                        <a class="c-fr closePassword" href="/site.main.forget1" target="_blank"><?php echo tpl_modifier_tr('忘记密码','site.login'); ?>?</a>
                    </div>
                    <div class="col-xs-20 pd0 mb10">
                        <input class="col-xs-20 x_btn_reg2" id="mobileSmsLogin" type="submit" value="<?php echo tpl_modifier_tr('登录','site.login'); ?>" />
                    </div>
                    <div class="col-xs-20 pd0 mb10">
                        <span class="c-fl mr10 mt10 fs14">
                            <?php echo tpl_modifier_tr('其他方式登录','site.login'); ?>：
                        </span>
                        <a class="login snsi si_weixin c-fl mr10" target="_blank" href="/user.parterner.weixin/"></a>
                        <a class="login snsi si_qq c-fl" target="_blank" href="/user.parterner.qq"></a>
                    </div>
                    <div class="col-xs-20 pd0 mb10">
                        <div class="c-fr clearfix">
                            <?php echo tpl_modifier_tr('没有账号','site.login'); ?>? <a class="lkblue" href="/site.main/register" target="_blank"><?php echo tpl_modifier_tr('立即注册','site.login'); ?>&gt;&gt;</a>
                        </div>
                    </div>
                    </form>
                </li>
        </ul>
    </div>
</section>
<script type="text/javascript"> 
    var user_ipo;
    var user_ids;
    $.post("/site.main.getInternationalCodeByInfo","",function(r){
        var content='';
        $(r.msg).each(function(k,v){
            content +='<dd class="col-xs-20 clearfix areaCountryInfo"><span class="areaCity col-xs-15 pd0 tel">'+v.name_en+'('+v.name+')</span><span class="areaCode col-xs-5 pd0 ter">'+v.mobile_pre.substring(1)+'</span></dd>';
        });
        $("#internationCode").html(content);
        
    },"json");
    
    //动态短信验证
    $.post("/site.main.getInternationalCodeByInfo","",function(r){
        var content='';
        $(r.msg).each(function(k,v){
            content +='<dd class="col-xs-20 clearfix areaCountryInfo"><span class="areaCity col-xs-15 pd0 tel">'+v.name_en+'('+v.name+')</span><span class="areaCode col-xs-5 pd0 ter">'+v.mobile_pre.substring(1)+'</span></dd>';
        });
        $("#smsInternationCode").html(content);
        
    },"json");


    var wait=60; 
    function getVerificatCode() {
        if (wait == 0) { 
            $('.VerificatCodeBtn').prop('disabled', false);
            $('.VerificatCodeBtn').val("获取验证码");
            wait = 60; 
        } else { 
            $('.VerificatCodeBtn').prop('disabled', true);
            $('.VerificatCodeBtn').val("重新发送(" + wait + ")");
            wait--; 
            setTimeout(function() { 
                getVerificatCode() 
            },1000) 
        } 
    }     

$(function  () {

    $('#get_verify_code').click(function() {
        var smsNumCode = $(".smsNumCode").html();
        var smobile = $("input[name='mobile']").val();
        var numCodeAndMobile = "+"+smsNumCode+smobile;
		var verifyCode = $.trim($("input[name='verifyCode']").val());
        $.post("/user.main.getLoginSmsCode",{ mobile:numCodeAndMobile,verifyCode:verifyCode },function(r){
            if(r.code == -8){
                layer.msg(r.error);
                return false;
            }
            if(r.code == 1){
                layer.msg(r.error);
                getVerificatCode();
            }
        },"json");
    })

    $(".areaCountry").on("click" ,".moreAreaCountry" ,function() {
        $(".areaCountry").show();
        var cid = $("input[name=cid]").val();
        $.post("/site.main.getInternationalCodeByInfo",{ cid:cid },function(r){
            var moreCode = '';
            $(r.msg).each(function(k,v){
            moreCode+='<dd class="col-xs-20 clearfix areaCountryInfo"><span class="areaCity col-xs-15 tel pd0">'+v.name_en+'('+v.name+')</span><span class="areaCode col-xs-5 pd0 ter">'+v.mobile_pre.substring(1)+'</span></dd>';
        });
        $("#internationCode").html(moreCode);
        $(".moreAreaCountry").hide();
        },"json");
    })

    //点击更多
    $(".areaCountry").on("click" ,".smsMoreAreaCountry" ,function() {
        $(".areaCountry").show();
        var cid = $("input[name=cid]").val();
        $.post("/site.main.getInternationalCodeByInfo",{ cid:cid },function(r){
            var moreCode = '';
            $(r.msg).each(function(k,v){
            moreCode+='<dd class="col-xs-20 clearfix areaCountryInfo"><span class="areaCity col-xs-15 pd0 tel">'+v.name_en+'('+v.name+')</span><span class="areaCode col-xs-5 pd0 ter">'+v.mobile_pre.substring(1)+'</span></dd>';
        });
        $("#smsInternationCode").html(moreCode);
        $(".smsMoreAreaCountry").hide();
        },"json");
    })

    $(".login-content-btn li").click(function() {
        $("input[type='password']").val("");
        $(this).addClass('curr').siblings().removeClass('curr');
        $('.login-content-ct li:eq(' + $(this).index() + ')').show().siblings().hide();
    })

    $(".category-mobile").bind("click" ,function() {
        $(".areaCountry").show();
        return false;
    })

    $(".areaCountry").on("click" ,".moreAreaCountry ,.smsMoreAreaCountry" ,function() {
        $(".areaCountry").show();
        return false;
    })

    $(document).click(function() {
        $(".areaCountry").hide();
    })

    $(".areaCountryInfo-box").on("click" ,".areaCountryInfo" ,function() {
        $(".category-mobile").find(".mobile-number").html($(this).find(".areaCode").html());
        $(".areaCountry").hide();
    })


	$("#login_form").submit(function(){
            if($("#user_name").val()=='' || $("#user_name").val()=="手机号"){
                $(".x_error_left").html("<?php echo tpl_modifier_tr('请输入手机号','site.login'); ?>");
                return false;
            }
            if($("#x_pass").val()=='' || $("#x_pass").val()=="密码"){
                $(".x_error_left").html("<?php echo tpl_modifier_tr('密码不能为空','site.login'); ?>");
                return false;
            }

            $.post("/layer.main.loginAjax/<?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["num"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["source"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["setId"]; ?>",$("#login_form").serialize(),function(r){
                if (r.code == 0) {
                    parent.location.reload();
				} else if (r.code == 100){
                    parent.location.href = "/order.main.memberinfo/"+r.data;
                } else if(r.code > 0){
                    $(".x_error_left").html(r.message);
                    return false;
                } else if (r.code == -2) {
                    var index = layer.load(2);
                    $.post("/course/pay/check", {
                                classId: r.data,
                                cid: <?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>
                            }, function (r) {
                                layer.close(index);
                                if (r.code == 0) {
                                    var resellOrgId = <?php echo SlightPHP\Tpl::$_tpl_vars["resellOrgId"]; ?>;
                                    if(resellOrgId>0){
                                    parent.location.href = "/order.main.buy/course/<?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>/"+resellOrgId+"";}else{
                                        parent.location.href = "/order.main.buy/course/<?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>";
                                    }
                                } else {
                                    //layer.msg(r.errMsg, function(){
                                         parent.location.reload();
                                    //});
                                }
                            }, "json"
                    );
                } else if (r.code == -3 || r.code == -4) {
                    parent.location.reload();
                } else if (r.code == -6){
                    layer.msg(r.message, function(){
                        parent.location.reload();
                    });
                }else {
                    layer.msg('<?php echo tpl_modifier_tr('未知错误','site.login'); ?>', function(){
                        parent.location.reload();
                    });
                }
            },"json");
            return false;
    })
    
    

    $('.iptFocusBlur').focus(function(){
        $(this).css("border","solid 1px #ffa81e");
    });
    $('.iptFocusBlur').blur(function(){
        $(this).css("border","solid 1px #ddd");
    });

    if(window.localStorage){
        user_ids=localStorage.user_id;
    }
	$("#cap").click(function(){
        $("#cap").attr("src","/captcha.gen?"+Math.random());
    });
    $("#smsForm").submit(function(){
        var smsNumCode = $(".smsNumCode").html();
        var smobile = $("input[name='mobile']").val();
        var numCodeAndMobile = "+"+smsNumCode+smobile;
        var tagCode = $("input[name='tagCode']").val();
        var password = $("input[name='smspassword']").val();
        var verifyCode = $.trim($("input[name='verifyCode']").val());
        if($("input[name='mobile']").val()==''){
			$(".x_error_right").html("<?php echo tpl_modifier_tr('手机号不能为空','site.login'); ?>");
            return false;
        }else if($("input[name='smspassword']").val()==''){
			$(".x_error_right").html("<?php echo tpl_modifier_tr('验证码不能为空','site.login'); ?>");
            return false;
        }else{
            $.post("/user.main.mobileSmsLogin",{ mobile:numCodeAndMobile,tagCode:tagCode,password:password,verifyCode:verifyCode },function(r){
                if(r.code==99 || r.code ==100){
					parent.location.reload();
                }else{
					$(".x_error_right").html(r.error);
                }
            },"json");
        }
        return false;
    });

    $(".login").each(function(){
        $(this).attr("href", $(this).attr("href")+"?from="+location.href);
    })

    $(".lkblue,.closePassword").click(function() {
        parent.location.reload();
    })
    var isChrome = window.navigator.userAgent.indexOf("Chrome") !== -1;
    if(isChrome) {
        setTimeout(function () {
            $("#x_pass").val("");
            $("#x_pass").val("");
        }, 100);
    }
});
</script>
</body>
</html>
