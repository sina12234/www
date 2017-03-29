<!DOCTYPE html>
<html>
<head>
    <title>用户注册-云课网</title>
    <?php echo tpl_function_part("/index.main.header"); ?>
</head>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/common.js'); ?>"></script>
<body>
<?php echo tpl_function_part("/index.main.top"); ?>
<section id="wrap-login" style="display:block;">
    <div class="container">
        <div class="row">
            <div class="reg-left-img col-sm-10 hidden-xs col-md-11"><img width="100%" src="<?php echo utility_cdn::img('/assets_v2/img/reg-img.jpg'); ?>" alt="" class="row"></div>
            <div class="reg-form mt20 mb20 c-fr col-sm-10 col-xs-20 col-md-8">
                <div class="x_reg_inner_1" id="reg_ok" style="display:none">
                    <h1>恭喜您,注册成功</h1>
                    <div class='Forget-box' id="reg_ok" style="padding:10% 0px 15% 0px;">
                        <div class='box-img'>
                            <img src="<?php echo utility_cdn::img('/assets_v2/img/icg.png'); ?>">
                            <span>恭喜您注册成功！</span>
                        </div>
                        <div class='box-tz'>
                            <p>页面<span class='time'>3</span>秒自动登录.未跳转请点击<a href="/index.main.login">&nbsp;登录&nbsp;</a></p>
                        </div>
                    </div>
                </div>
                <form novalidate action="#" class="form col-sm-20 col-xs-20" id="form" >
                    <input type="password" style="display:none;">
                    <input type="hidden" id="voice" name="voice" value="1" />
                    <ul class="row">
                        <li class="fs18 pd-b20 tc">欢迎注册</li>
                        <li class="pos-rel col-sm-20 pd0">
                            <div class="c-fl category-mobile fs12 tac bor1px" style="line-height: 33px;">
                                +<span class="verifyNumCode">86</span>
                                <i class="c-fr mt15 mr5"></i>
                            </div>
                            <div class="col-sm-15 col-xs-14 col-md-16 ">
                                <input type="text" class="lh-35 fs14 form_text iptFocusBlur" name="mobile" placeholder="请输入手机号" id="mobile">
                                <i class="mobile-icon reg-mobile-icon pos-abs"></i>
                            </div>
                            <div class="cRed mobile-error-tip" style="  height: 55px;">&nbsp;</div>
                            <dl class="fs12 pos-abs col-sm-20 col-xs-20 pd0 areaCountry bor1px" style="display:none;top:35px;">
								<div id="internationCode"></div>
                                <dd class="col-sm-20 col-xs-20 tac clearfix moreAreaCountry">
                                    更多国家
                                </dd>
								<input type="hidden" name="cid" value="1"/>
                            </dl>
                        </li>
                        <li class="pos-rel">
                            <input type="text" class="col-sm-13 col-md-15 col-xs-13 lh-35 fs14 form_text iptFocusBlur" placeholder="请输入图片验证码" name="verify_code_img">
                            <a class="col-sm-5 col-xs-6 col-md-4 pd0">
                                <img style="padding:0 10px" id="cap" src="/captcha.gen" width="110" height="35"  alt=""/>
                            </a>
                            <div class="tips cRed col-sm-20 col-xs-20 verify_code_img-error-tip">&nbsp;</div>
                        </li>
                        <li class="pos-rel col-sm-20 pd0">
                            <input type="text" class="col-sm-13 col-xs-13 col-md-15 mr15 fs14 lh-35 form_text iptFocusBlur" placeholder="请输入手机验证码" name="verify_code">
                        <!--
                            <a class="verif lh-35 fs12" href="javascript:;" id="get_verify_code" onclick="getVerificatCode(this)">获取验证码</a>
                        -->
                            <input type="button" class="verif lh-35 fs12 register-but" id="get_verify_code" value="获取验证码" />
                            <div class="tips cRed col-sm-20 col-xs-20  verify_code-error-tip">&nbsp;</div>
                        </li>
                        <li class="picCode pos-rel col-sm-20 pd0" style="display:none;">
                        <li class="pos-rel col-sm-20 pd0">
                            <input type="text" class="col-sm-20 col-xs-20 lh-35 fs14 nameCode form_text iptFocusBlur" placeholder="请输入姓名" name="name" readonly onfocus="this.removeAttribute('readonly');"><!-- fake chrome autocomplete WEB-4804 -->
                            <div class="tips cRed name-error-tip" >&nbsp;</div>
                            <i class="user-icon pos-abs"></i>
                        </li>
                        <li  class="pos-rel col-sm-20 pd0">
                            <input type="password" class="col-sm-20 col-xs-20 fs14 lh-35 form_text iptFocusBlur" placeholder="请输入密码" name="password">
                            <i class="password-icon pos-abs" style="top:8px;"></i>
                        </li>
                        <?php if(isset(SlightPHP\Tpl::$_tpl_vars["pr"]) && SlightPHP\Tpl::$_tpl_vars["pr"]){; ?>
                        <input type="hidden" name="pr" value="<?php echo SlightPHP\Tpl::$_tpl_vars["pr"]; ?>">
                        <?php }; ?>
                        <?php if(isset(SlightPHP\Tpl::$_tpl_vars["url"]) && SlightPHP\Tpl::$_tpl_vars["url"]){; ?>
                        <input type="hidden" name="url" value="<?php echo SlightPHP\Tpl::$_tpl_vars["url"]; ?>">
                        <?php }; ?>
                        <li class="reg-invitation-box">
                            <label style="padding:0;">
                                <input type="checkbox" class="reg-invitation-box" name="inv-code" style="height:auto;margin-top:12px; " /> 邀请码（选填）
                            </label>
                        </li>
                        <li class="reg-invcode-box pos-rel col-sm-20 pd0">
                            <input type="text" id="pr_code" name="pr_code" placeholder="请输入邀请码" class="reg_invitation-code col-sm-20 col-xs-20 lh-35 fs14 form_text iptFocusBlur" />
                            <div class="cod-sm-20 cod-xs-20">&nbsp;</div>
                            <i class="user-icon2 pos-abs"></i>
                        </li>
                        <li>
                            <button class="col-sm-20 col-xs-20 blue-btn fs18" type="submit" id="get_verify_code_v">马上注册</button>
                        </li>
                        <li class="lh-30 pd-t5"><span class="cGray">注册即代表同意</span><a href="/index.main.privacy" class="cYellow" target="_blank">隐私条款</a></li>
                        <li><span class="c-fr fs14">已有账号？<a href="/index.main.login" class="cBlue">登录</a></span></li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- footer -->
<?php echo tpl_function_part("/index.main.footer"); ?>
</body>
</html>
<script>
    var wait=60;
    function getVerificatCode() {
        if (wait == 0) {
            $('.register-but').prop('disabled', false);
            $('.register-but').val("获取验证码");
            wait = 60;
        } else {
            $('.register-but').prop('disabled', true);
            $('.register-but').val("重新发送(" + wait + ")");
            wait--;
            setTimeout(function() {
                getVerificatCode()
            },1000)
        }
    }
$(function(){

    $(".areaCountry").on("click" ,".areaCountryInfo" ,function() {
        $(".category-mobile").find("span").html($(this).find(".areaCode").html());
        $(".areaCountry").hide();
    })

    $(".category-mobile").bind("click" ,function() {
        $(".areaCountry").show();
        return false;
    })

    $(".areaCountry").on("click" ,".moreAreaCountry" ,function() {
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

    $(".areaCountry").on("click" ,".moreAreaCountry" ,function() {
            $(".areaCountry").show();
            var cid = $.trim($("input[name=cid]").val());
            $.post("/index.main.getInternationalCodeByInfo",{ cid:cid },function(r){
                var moreCode = '';
                $(r.msg).each(function(k,v){
                moreCode+='<dd class="col-sm-20 col-xs-20 clearfix areaCountryInfo"><span class="areaCity c-fl">'+v.name_en+'('+v.name+')</span><span class="areaCode c-fr">'+v.mobile_pre.substring(1)+'</span></dd>';
            });
            $("#internationCode").html(moreCode);
            $(".moreAreaCountry").hide();
            },"json");
        })

        $.post("/index.main.getInternationalCodeByInfo","",function(r){
            var content='';
            $(r.msg).each(function(k,v){
                content +='<dd class="col-sm-20 col-xs-20 clearfix areaCountryInfo"><span class="areaCity c-fl">'+v.name_en+'('+v.name+')</span><span class="areaCode c-fr">'+v.mobile_pre.substring(1)+'</span></dd>';
            });
            $("#internationCode").html(content);

        },"json");

        var myIndex=0;//点击三次出现图片验证
        $(".reg-invitation-box").on('click',function() {
            if($("input[name='inv-code']").is(':checked')) {
                $(".reg-invcode-box").show();
            }else{
                $(".reg-invcode-box").hide();
            }
        });

        var i=0;
        var timer;
        $('#get_verify_code').click(function() {
            var verify_code_img = $.trim($("[name=verify_code_img]").val());
            var mobile = $.trim($("[name=mobile]").val());
            var verify_code_img = $.trim($("[name=verify_code_img]").val());
            if(mobile==''){
                layer.msg("手机号不能为空");
                $("[name=mobile]").focus();
                return false;
            }
            if(verify_code_img==''){
                layer.msg('<?php echo tpl_modifier_tr('图片验证码不能为空','site.login'); ?>');
                $("[name=verify_code_img]").focus().css("border","1px solid #02a1e5");
                return false;
            }
            var verifyNumCode = $.trim($(".verifyNumCode").html());
            var cmobile = $.trim($("input[name='mobile']").val());
            var numCodeAndMobile = "+"+verifyNumCode+cmobile;
            //if(ii>0)return false;
            i=60;
            $.post("/index.main.VerifyCodeNew",{ mobile:numCodeAndMobile,verify_code_img:verify_code_img },function(r){

                if(r.error){
                    if(r.error_code==-1){
                        layer.msg('<p><?php echo tpl_modifier_tr('手机号已注册','site.login'); ?></p>');
                        //$(".mobile-error-tip").html("<p>手机号已注册，请 <a href='/index.main.login'> 点击登录</a></p>").show();
                        $("[name='mobile']").css('border','1px solid #ffa81e');
                    }else if(r.code=='-8'){
                        layer.msg(r.error);
                        $("#cap").attr("src","/captcha.gen?"+Math.random());
                        $("[name="+r.field+"]").focus();
                    }else if(r.ok==1){
                        layer.msg(r.error);
                        getVerificatCode();
                    }else{
                        layer.msg(r.error);
                        return false;
                    }
                }
            },"json");
        })

        var i3 =3;
        var timer3;
        $("#form").submit(function(){
            var verify_code_img = $.trim($("[name=verify_code_img]").val());
            var mobile = $.trim($("[name=mobile]").val());
            if(mobile==''){
                layer.msg("手机号不能为空");
                $("[name=mobile]").focus();
                return false;
            }
            if(verify_code_img==''){
                    layer.msg('<?php echo tpl_modifier_tr('图片验证码不能为空','site.login'); ?>');
                    $("[name=verify_code_img]").focus().css("border","1px solid #02a1e5");
                    return false;
            }
            $.post("/index.main.registerajax",$("#form").serialize(),function(r){

                if(r.error){
                    layer.msg(r.error);
                    if(r.code=='-8'){
                        $("#cap").attr("src","/captcha.gen?"+Math.random());
                    }
                    return false;
                }
                $("#form").hide();
                $("#reg_ok").show();
                timer3 = setInterval(function(){
                    i3--
                    $(".time").html(i3)
                    if(i3==0){
                        clearInterval(timer3);
                        if(r.redirect_url){
                             location=r.redirect_url;
                        }else{
                             location="/";
                        }

                    }

                },1000);
            },"json");
            return false;
        });

        $('.iptFocusBlur').focus(function(){
            $(this).css('border','solid 1px #02a1e5');
        });
        $('.iptFocusBlur').blur(function(){
            $(this).css('border','solid 1px #ddd');
        });
});
</script>
