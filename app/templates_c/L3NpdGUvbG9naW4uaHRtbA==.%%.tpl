<!DOCTYPE html>
<html>
<head>
<title><?php echo tpl_function_part('/site.main.orgname'); ?> - 用户登录 - 专业的K12在线教育平台</title>
<meta name="title" content="高能100 - 专业的K12在线教育平台 在线直播">
<meta name="keywords" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线 k12 小学数学 初中数学">
<meta name="description" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线直播">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<body>
<?php echo tpl_function_part("/site.main.nav3"); ?>
<section id="wrap-login" style="display:block;">
    <div class="login-bg">
        <div class="container">
            <div class="row">
                <div class="login-content pd0 col-sm-10 c-fr clearfix col-xs-20 col-md-6" style="float:right">
                    <ul class="login-content-btn fs14 clearfix tac">
                        <li class="col-sm-10 tec curr col-xs-10">账号密码登录</li>
                        <li class="col-sm-10 tec col-xs-10">手机动态码登录</li>
                    </ul>
                    <ul class="login-content-ct bor1px clearfix">
                        <li class="col-sm-20 mt20" style="display:block;">
                            <form novalidate action="/site.main.login?<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["url"])){; ?>url=<?php echo SlightPHP\Tpl::$_tpl_vars["url"]; ?><?php }; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cid"])){; ?>&cid=<?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?><?php }; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["clid"])){; ?>&clid=<?php echo SlightPHP\Tpl::$_tpl_vars["clid"]; ?><?php }; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["source"])){; ?>&source=<?php echo SlightPHP\Tpl::$_tpl_vars["source"]; ?><?php }; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["resellOrgId"])){; ?>&resellOrgId=<?php echo SlightPHP\Tpl::$_tpl_vars["resellOrgId"]; ?><?php }; ?>" method="post" autocomplete="off" id="x_submit_form">
                                <div class="pos-rel col-sm-20 pd0 mb20 mobileNum col-xs-20">
                                    <div class="c-fl mr10 category-mobile lg-category-mobile fs12 tec bor1px">
                                        +<span class="mobile-number verifyCode">86</span>
										<input type='hidden' name="areaId" value="86" />
                                        <i class="c-fr mt15 mr5"></i>
                                    </div>
                                    <dl class="fs14 pos-abs col-xs-20 col-sm-20 pd0 areaCountry bor1px" style="display:none;">
                                        <div class="col-sm-20 col-xs-20 clearfix fs12 areaCountryInfo-box pd0" id="internationCode"></div>
                                        <dd class="col-sm-20 col-xs-20 fs12 tec clearfix moreAreaCountry">
                                            更多国家
                                            <input type="hidden" name="cid" value="<?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>"/>
                                        </dd>
                                    </dl>
                                    <div class="pos-abs pd0" style="right:0px;left:90px;">
                                        <input type="text" autocomplete="off" placeholder="<?php echo tpl_modifier_tr('请输入手机号','site.login'); ?>" name="uname" id="x_uname" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["uname"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["uname"]; ?><?php }; ?>" class="bor1px col-sm-20 mobile-ipt col-xs-20 iptFocusBlur" />
                                        <input type='hidden' name="areaCode" value="86"/>
                                        <i class="mobile-icon pos-abs"></i>
                                    </div>
                                </div>
                                <div class="col-sm-20 pd0 mb20 pos-rel col-xs-20">
                                    <input placeholder="<?php echo tpl_modifier_tr('请输入密码','site.login'); ?>" type="password" name="password" id="x_pass" autocomplete="off" class="bor1px col-sm-20 password-ipt col-xs-20 iptFocusBlur"  />
                                    <i class="password-icon pos-abs"></i>
                                </div>
                                <div class="col-sm-20 pd0 mb20 col-xs-20">
                                    <label class="c-fl">
                                        <input style="vertical-align: middle;" type="checkbox" name="forever"/> <?php echo tpl_modifier_tr('7日内免登录','site.login'); ?>
                                    </label>
                                    <a class="c-fr" href="/site.main.forget1"><?php echo tpl_modifier_tr('忘记密码','site.login'); ?>?</a>
                                </div>
                                <div class="col-sm-20 pd0 mb20 col-xs-20">
                                    <input class="col-xs-20 login-btn" type="submit" value="<?php echo tpl_modifier_tr('登录','site.login'); ?>" name="submit"/>
                                </div>
                                <div class="col-sm-20 pd0 mb20 col-xs-20">
                                    <span class="c-fl mr10 mt20 fs14">
                                        <?php echo tpl_modifier_tr('其他方式登录','site.login'); ?>：
                                    </span>
                                    <a class="login snsi si_weixin c-fl mr10 mt10" href="/user.parterner.weixin/"></a>
                                    <a class="login snsi si_qq c-fl mt10" href="/user.parterner.qq"></a>
                                </div>
                                <div class="col-sm-20 pd0 mb20 col-xs-20">
                                    <div class="c-fr clearfix">
                                        <?php echo tpl_modifier_tr('没有账号','site.login'); ?>? <a class="lkblue" href="/site.main/register"><?php echo tpl_modifier_tr('立即注册','site.login'); ?>&gt;&gt;</a>
                                    </div>
                                </div>
                            </form>
                        </li>
                        <li class="col-sm-20 mt20">
                        <form id="smsForm" method="post" action="#" autocomplete="off">
                            <div class="pos-rel col-sm-20 pd0 mb20 col-xs-20">
                                <div class="c-fl mr10 category-mobile fs12 tec bor1px">
                                    +<span class="mobile-number smsNumCode">86</span>
                                    <i class="c-fr mt15 mr5"></i>
                                </div>
                                <dl class="fs14 pos-abs col-xs-20 col-sm-20 pd0 areaCountry bor1px" style="display:none;">
                                 <div class="col-sm-20 col-xs-20 clearfix areaCountryInfo-box fs12 pd0" id="smsInternationCode"></div>
                                    <dd class="col-sm-20 col-xs-20 fs12 tec clearfix smsMoreAreaCountry">
                                        更多国家
                                        <input type="hidden" name="cid" value="1"/>
                                    </dd>
                                </dl>
                                <div class="pos-abs pd0" style="left:90px;right:0px;">
                                    <input type="text" autocomplete="off" placeholder="<?php echo tpl_modifier_tr('请输入手机号','site.login'); ?>" name="mobile" id="x_uname" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["uname"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["uname"]; ?><?php }; ?>" class="bor1px col-sm-20 mobile-ipt col-xs-20 iptFocusBlur" />
									<input type="hidden" name="tagCode" value="1"/>
                                    <i class="mobile-icon pos-abs"></i>
                                </div>

                            </div>
							<div class="col-sm-20 pd0 mb20 col-xs-20">
                                    <input type="text" name="verifyCode" placeholder="请输入图片验证码" class="bor1px col-sm-14 col-xs-10 iptFocusBlur mobile-ipt">
                                    <img src="/captcha.gen" id="cap" class="col-xs-10 c-fr col-sm-6 pd0" height="40">
                            </div>
                            <div class="col-sm-20 pd0 mb20 col-xs-20">
                                <div class="pd0 pos-abs" style="left:0px;right:110px;">
                                    <input placeholder="<?php echo tpl_modifier_tr('请输入手机动态码','site.login'); ?>" type="text" name="smspassword" id="x_pass" autocomplete="off" class="bor1px col-sm-20 aassword-ipt  col-xs-20 iptFocusBlur" style="height:40px;"/>
                                </div>
                                <div class="col-sm-5 pd0 c-fr">
                                    <input type="button" value="获取验证码" id="get_verify_code" class="c-fr VerificatCodeBtn bor1px tec" >
                                </div>
                            </div>
                            <div class="col-sm-20 pd0 mb20 col-xs-20">
                                <label class="c-fl">
                                    <input style="vertical-align: middle;" type="checkbox" name="forever"/> <?php echo tpl_modifier_tr('7日内免登录','site.login'); ?>
                                </label>
                                <a class="c-fr" href="/site.main.forget1"><?php echo tpl_modifier_tr('忘记密码','site.login'); ?>?</a>
                            </div>
                            <div class="col-sm-20 pd0 mb20 col-xs-20">
                                <input class="col-xs-20 login-btn" id="mobileSmsLogin" type="submit" value="<?php echo tpl_modifier_tr('登录','site.login'); ?>" />
                            </div>
                            <div class="col-sm-20 pd0 mb20 col-xs-20">
                                <span class="c-fl mr10 mt20 fs14">
                                    <?php echo tpl_modifier_tr('其他方式登录','site.login'); ?>：
                                </span>
                                <a class="login snsi si_weixin c-fl mr10 mt10" href="/user.parterner.weixin/"></a>
                                <a class="login snsi si_qq c-fl mt10" href="/user.parterner.qq"></a>
                            </div>
                            <div class="col-sm-20 pd0 mb20 col-xs-20">
                                <div class="c-fr clearfix">
                                    <?php echo tpl_modifier_tr('没有账号','site.login'); ?>?  <a class="lkblue" href="/site.main/register"><?php echo tpl_modifier_tr('立即注册','site.login'); ?>&gt;&gt;</a>
                                </div>
                            </div>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script type="text/javascript">
    var user_ipo;
    var user_ids;
    $.post("/site.main.getInternationalCodeByInfo","",function(r){
        var content='';
        $(r.msg).each(function(k,v){
            content +='<dd class="col-sm-20 col-xs-20 clearfix areaCountryInfo"><span class="areaCity col-sm-15 col-xs-15 pd0 tel">'+v.name_en+'('+v.name+')</span><span class="areaCode col-sm-5 col-xs-5 pd0 ter">'+v.mobile_pre.substring(1)+'</span></dd>';
        });
        $("#internationCode").html(content);
    },"json");
    //动态短信验证
    $.post("/site.main.getInternationalCodeByInfo","",function(r){
        var content='';
        $(r.msg).each(function(k,v){
            content +='<dd class="col-sm-20 col-xs-20 clearfix areaCountryInfo"><span class="areaCity col-sm-15 col-xs-15 pd0 tel">'+v.name_en+'('+v.name+')</span><span class="areaCode col-sm-5 col-xs-5 pd0 ter">'+v.mobile_pre.substring(1)+'</span></dd>';
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

$(function() {
    $('#get_verify_code').click(function() {
        var smsNumCode = $.trim($(".smsNumCode").html());
        var smobile = $.trim($("input[name='mobile']").val());
        var numCodeAndMobile = "+"+smsNumCode+smobile;
		var verifyCode = $.trim($("input[name='verifyCode']").val());
        $.post("/user.main.getLoginSmsCode",{ mobile:numCodeAndMobile,verifyCode:verifyCode },function(r){
            if(r.code==-8){
                layer.msg(r.error);
				return false;
            }
			if(r.code==1){
                layer.msg(r.error);
				getVerificatCode();
            }
        },"json");
    })

    //点击更多
    $(".areaCountry").on("click" ,".moreAreaCountry" ,function() {
        $(".areaCountry").show();
        var cid = $.trim($("input[name=cid]").val());
        $.post("/site.main.getInternationalCodeByInfo",{ cid:cid },function(r){
            var moreCode = '';
            $(r.msg).each(function(k,v){
            moreCode+='<dd class="col-sm-20 col-xs-20 clearfix areaCountryInfo"><span class="areaCity col-sm-15 col-xs-15 tel pd0">'+v.name_en+'('+v.name+')</span><span class="areaCode col-sm-5 col-xs-5 pd0 ter">'+v.mobile_pre.substring(1)+'</span></dd>';
        });
        $("#internationCode").html(moreCode);
        $(".moreAreaCountry").hide();
        },"json");
    })

    //点击更多
    $(".areaCountry").on("click" ,".smsMoreAreaCountry" ,function() {
        $(".areaCountry").show();
        var cid = $.trim($("input[name=cid]").val());
        $.post("/site.main.getInternationalCodeByInfo",{ cid:cid },function(r){
            var moreCode = '';
            $(r.msg).each(function(k,v){
            moreCode+='<dd class="col-sm-20 col-xs-20 clearfix areaCountryInfo"><span class="areaCity col-sm-15 col-xs-15 pd0 tel">'+v.name_en+'('+v.name+')</span><span class="areaCode col-sm-5 col-xs-5 pd0 ter">'+v.mobile_pre.substring(1)+'</span></dd>';
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
    $(".category-mobile").on("click" ,function() {
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
	$("#cap").click(function(){
        $('input[name="verifyCode"]').val('');
        $("#cap").attr("src","/captcha.gen?"+Math.random());
    });
    $(".areaCountryInfo-box").on("click" ,".areaCountryInfo" ,function() {
		var area = $.trim($(this).find('.areaCode').text());
		$('.category-mobile input[name="areaId"]').val(area);
        $(".category-mobile").find(".mobile-number").text($(this).find(".areaCode").text());
        $(".areaCountry").hide();
    })
    // 提交验证代码
    $("#x_submit_form").submit(function(){
        var xUname = $.trim($("#x_uname").val())
        if(xUname =='' || xUname =="<?php echo tpl_modifier_tr('手机号','site.login'); ?>"){
            layer.msg('<?php echo tpl_modifier_tr('请输入手机号','site.login'); ?>');
            $("#x_uname").css("border","solid 1px #ffa81e");
            return false;
        }else{
           if(user_ids!=undefined){
                user_ipo = xUname +','+user_ids;
            }else{
                user_ipo = xUname;
            }
            if(window.localStorage){
                localStorage.user_id=user_ipo;
            }
            $(".x_btn_reg2").val("<?php echo tpl_modifier_tr('登录中','site.login'); ?>...").css('backgroundColor','#ffba4d');
            return true;
       }
    });

    $('.iptFocusBlur').focus(function(){
        $(this).css('border','solid 1px #ffa81e');
    });
    $('.iptFocusBlur').blur(function(){
        $(this).css('border','solid 1px #ddd');
    });

    //页面加载后获取焦点
    if(window.localStorage){
        user_ids=localStorage.user_id;
    }
	var error1 = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["error1"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["error1"]; ?><?php }; ?>";
     if(error1 !=''){
        layer.msg(error1);
        return false;
     }
     var error2 = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["error2"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["error2"]; ?><?php }; ?>";
     if(error2 !=''){
        layer.msg(error2);
        return false;
     }
     var error3 = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["error3"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["error3"]; ?><?php }; ?>";
     if(error3 !=''){
        layer.msg(error3);
        return false;
     }
    $("#smsForm").submit(function(){
        var smsNumCode = $.trim($(".smsNumCode").html());
        var smobile = $.trim($("input[name='mobile']").val());
        var numCodeAndMobile = "+"+smsNumCode+smobile;
        var tagCode = $.trim($("input[name='tagCode']").val());
        var password = $.trim($("input[name='smspassword']").val());
        var verifyCode = $.trim($("input[name='verifyCode']").val());
        if($("input[name='mobile']").val()==''){
            layer.msg('<?php echo tpl_modifier_tr('手机号不能为空','site.login'); ?>');
            return false;
        }else if($.trim($("input[name='verifyCode']").val())==''){
			 layer.msg('<?php echo tpl_modifier_tr('图片验证码不能为空','site.login'); ?>');
            return false;
		}else{
            $.post("/user.main.mobileSmsLogin",{ mobile:numCodeAndMobile,tagCode:tagCode,password:password,verifyCode:verifyCode },function(r){
                if(r.code=='100'){
                    location.href=r.data;
                }else{
                    layer.msg(r.error);
                }
            },"json");
        }
        return false;
    });

    $(".login").each(function(){
        $(this).attr("href", $(this).attr("href")+"?from="+location.href);
    })

});
</script>
