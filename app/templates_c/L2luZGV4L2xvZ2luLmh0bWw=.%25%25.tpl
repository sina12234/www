<!DOCTYPE html>
<html>
<head>
<title>用户登录-云课网</title>
<?php echo tpl_function_part("/index.main.header"); ?>
</head>
<body>
<?php echo tpl_function_part("/index.main.top"); ?>
<div class="container mt20 mb20" style="display:block;">
    <div class="row">
        <section id="wrap-login">
            <div class="reg-left-img col-sm-9 hidden-xs col-md-12">
                <img class="c-fr row" src="<?php echo utility_cdn::img('/assets_v2/img/reg-img.jpg'); ?>" alt="">
            </div>
            <div class="login-content pd0 col-sm-10 c-fr mr15 clearfix col-md-7">
                <ul class="login-content-btn fs14 col-sm-20 pd0 col-xs-20 clearfix">
                    <li class="col-sm-10 col-xs-10 tec curr">账号登录</li>
                    <li class="col-sm-10 col-xs-10 tec">验证码登录</li>
                </ul>
                <ul class="login-content-ct bor1px clearfix col-xs-20 col-sm-20 pd0">
                    <li class="col-sm-20 col-xs-20 mt20" style="display:block;">
                        <form action="" id="yunkeSubmit" method="post" autocomplete="off">
                            <div class="pos-rel col-sm-20 col-xs-20 pd0 mb20">
                                <div class="c-fl mr10 category-mobile fs12 tec bor1px">
                                    +<span class="verifyCode">86</span>
                                    <i class="c-fr mt15 mr5"></i>
                                </div>
                                <div class="pos-rel col-sm-15 col-xs-13 pd0 login-fr col-md-15">
                                    <input type="text" name="uname" placeholder="请输入手机号" class="bor1px col-sm-20 col-xs-20 mobile-ipt iptFocusBlur" />
                                    <i class="mobile-icon pos-abs"></i>
                                </div>
                                <dl class="fs12 pos-abs col-sm-20 col-xs-20 pd0 areaCountry bor1px" style="display:none;">
									<div id="internationCode"></div>
                                    <dd class="col-sm-20 col-xs-20 tec clearfix moreAreaCountry">
                                        更多国家
                                    </dd>
									<input type="hidden" name="cid" value="1"/>
                                </dl>
                            </div>
                            <div class="col-sm-20 col-xs-20 pd0 mb20 pos-rel">
                                <input type="password" name="pass" placeholder="请输入密码" class="bor1px col-sm-20 col-xs-20 password-ipt iptFocusBlur"  />
                                <i class="password-icon pos-abs"></i>
                            </div>
                            <div class="col-sm-20 col-xs-20 pd0 mb20">
                                <label class="c-fl">
                                    <input style="vertical-align: middle;" type="checkbox" name="forever"/> <?php echo tpl_modifier_tr('7日内免登录','site.login'); ?>
                                </label>
                                <a class="c-fr" href="/index.main.forget1"><?php echo tpl_modifier_tr('忘记密码','site.login'); ?>?</a>
                            </div>
                            <div class="col-sm-20 col-xs-20 pd0 mb20">
                                <input class="col-xs-20 blue-btn" type="submit" value="<?php echo tpl_modifier_tr('登录','site.login'); ?>" />
                            </div>
                            <div class="col-sm-20 col-xs-20 pd0 mb20">
                                <span class="c-fl mr10 mt20 fs14">
                                    <?php echo tpl_modifier_tr('其他方式登录','site.login'); ?>：
                                </span>
                                <a class="login snsi si_weixin c-fl mr10 mt10" href="/user.parterner.weixin/"></a>
                                <a class="login snsi si_qq c-fl mt10" href="/user.parterner.qq"></a>
                            </div>
                            <div class="col-sm-20 col-xs-20 pd0 mb20">
                                <div class="c-fr clearfix">
                                    <?php echo tpl_modifier_tr('没有账号','site.login'); ?>? <a class="lkblue" href="/index.main.register"><?php echo tpl_modifier_tr('立即注册','site.login'); ?>&gt;&gt;</a>
                                </div>
                            </div>
                        </form>
                    </li>
                    <li class="col-sm-20 col-xs-20 mt20">
					<form action="" id="smsForm" method="post" autocomplete="off">
                        <div class="pos-rel col-sm-20 col-xs-20 pd0 mb20">
                            <div class="c-fl mr10 category-mobile fs12 tec bor1px">
                                +<span class="smsNumCode">86</span>
                                <i class="c-fr mt15 mr5"></i>
                            </div>
                            <div class="pos-rel col-sm-15 col-xs-13 login-fr pd0 col-md-15">
                                <input type="text" name="mobile" placeholder="请输入手机号" class="bor1px col-sm-20 col-xs-20 mobile-ipt iptFocusBlur" />
                                <i class="mobile-icon pos-abs"></i>
                            </div>
                            <dl class="fs12 pos-abs col-sm-20 col-xs-20 pd0 areaCountry bor1px" style="display:none;">
                               <div id="smsInternationCode"></div>
                                <dd class="col-sm-20 col-xs-20 tec clearfix smsMoreAreaCountry">
                                    更多国家
                                </dd>
								<input type="hidden" name="cid" value="1"/>
                            </dl>
                        </div>
						<div class="col-sm-20 col-xs-20 pd0 mb20 pos-rel">
                                <input type="text" name="verifyCode" class="col-sm-13 col-md-15 col-xs-13 mobile-ipt fs14 bor1px iptFocusBlur" placeholder="请输入图片验证码">
                                <img id="cap" src="/captcha.gen" class="col-md-5 col-xs-7" alt="">
                        </div>
                        <div class="col-sm-20 col-xs-20 pd0 mb20">
                            <div class="col-sm-15 col-xs-15 pd0 pos-rel">
                                <input type="text" name="password" class="bor1px col-sm-19 col-xs-17 password-ipt iptFocusBlur" placeholder="请输入手机验证码" />
                            </div>
                            <div class="col-sm-5 col-xs-5 pd0">
                                <input type="button" value="获取验证码" class="c-fr VerificatCodeBtn bor1px tec" id="get_verify_code">
                            </div>
                        </div>
                        <div class="col-sm-20 col-xs-20 pd0 mb20">
                            <label class="c-fl">
                                <input style="vertical-align: middle;" type="checkbox" name="forever"/> <?php echo tpl_modifier_tr('7日内免登录','site.login'); ?>
                            </label>
                            <a class="c-fr" href="/index.main.forget1"><?php echo tpl_modifier_tr('忘记密码','site.login'); ?>?</a>
                        </div>
                        <div class="col-sm-20 col-xs-20 pd0 mb20">
                            <input class="col-xs-20 col-xs-20 blue-btn" type="submit" id="mobileSmsLogin" value="<?php echo tpl_modifier_tr('登录','site.login'); ?>" />
                        </div>
                        <div class="col-sm-20 col-xs-20 pd0 mb20">
                            <span class="c-fl mr10 mt20 fs14">
                                <?php echo tpl_modifier_tr('其他方式登录','site.login'); ?>：
                            </span>
                            <a class="login mt10 snsi si_weixin c-fl mr10" href="/user.parterner.weixin/"></a>
                            <a class="login mt10 snsi si_qq c-fl" href="/user.parterner.qq"></a>
                        </div>
                        <div class="col-sm-20 col-xs-20 pd0 mb20">
                            <div class="c-fr clearfix">
                                <?php echo tpl_modifier_tr('没有账号','site.login'); ?>? <a class="lkblue" href="/index.main.register"><?php echo tpl_modifier_tr('立即注册','site.login'); ?>&gt;&gt;</a>
                            </div>
                        </div>
						</form>
                    </li>
                </ul>
            </div>
        </section>
    </div>
</div>
<?php echo tpl_function_part("/index.main.footer"); ?>
</body>
</html>
<script type="text/javascript">
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

    $(function(){

        $('#get_verify_code').click(function() {
            var smsNumCode = $(".smsNumCode").html();
            var smobile = $("input[name='mobile']").val();
            var verifyCode = $.trim($("input[name='verifyCode']").val());
            var numCodeAndMobile = "+"+smsNumCode+smobile;
            $.post("/index.main.getLoginSmsCode",{ mobile:numCodeAndMobile,verifyCode:verifyCode },function(r){
                if(r.code == -8){
                    layer.msg(r.error);
                    return false;
                }
                if(r.code==1){
                    layer.msg(r.error);
                    getVerificatCode();
                }
            },"json");
        })

        $.post("/index.main.getInternationalCodeByInfo","",function(r){
            var content='';
            $(r.msg).each(function(k,v){
                content +='<dd class="col-sm-20 col-xs-20 clearfix areaCountryInfo"><span class="areaCity c-fl">'+v.name_en+'('+v.name+')</span><span class="areaCode c-fr">'+v.mobile_pre.substring(1)+'</span></dd>';
            });
            $("#internationCode").html(content);
        },"json");
        
        $.post("/index.main.getInternationalCodeByInfo","",function(r){
            var content='';
            $(r.msg).each(function(k,v){
                content +='<dd class="col-sm-20 col-xs-20 clearfix areaCountryInfo"><span class="areaCity c-fl">'+v.name_en+'('+v.name+')</span><span class="areaCode c-fr">'+v.mobile_pre.substring(1)+'</span></dd>';
            });
            $("#smsInternationCode").html(content);
        },"json");
        
        $(".areaCountry").on("click" ,".moreAreaCountry" ,function() {
                $(".areaCountry").show();
                var cid = $("input[name=cid]").val();
                $.post("/index.main.getInternationalCodeByInfo",{ cid:cid },function(r){
                    var moreCode = '';
                    $(r.msg).each(function(k,v){
                    moreCode+='<dd class="col-sm-20 col-xs-20 clearfix areaCountryInfo"><span class="areaCity c-fl">'+v.name_en+'('+v.name+')</span><span class="areaCode c-fr">'+v.mobile_pre.substring(1)+'</span></dd>';
                });
                $("#internationCode").html(moreCode);
                $(".moreAreaCountry").hide();
                },"json");
        })
        
        $(".areaCountry").on("click" ,".smsMoreAreaCountry" ,function() {
                $(".areaCountry").show();
                var cid = $("input[name=cid]").val();
                $.post("/index.main.getInternationalCodeByInfo",{ cid:cid },function(r){
                    var moreCode = '';
                    $(r.msg).each(function(k,v){
                    moreCode+='<dd class="col-sm-20 col-xs-20 clearfix areaCountryInfo"><span class="areaCity c-fl">'+v.name_en+'('+v.name+')</span><span class="areaCode c-fr">'+v.mobile_pre.substring(1)+'</span></dd>';
                });
                $("#smsInternationCode").html(moreCode);
                $(".smsMoreAreaCountry").hide();
                },"json");
        })

    $(".login").each(function(){
        $(this).attr("href", $(this).attr("href")+"?from="+location.href);
    })
    
    $(".login-content-btn li").click(function() {
        $("input[type='password']").val("");
        $(this).addClass('curr').siblings().removeClass('curr');
        $('.login-content-ct li:eq(' + $(this).index() + ')').show().siblings().hide();
    })

    $(".areaCountry").on("click" ,".areaCountryInfo" ,function() {
        $(".category-mobile").find("span").html($(this).find(".areaCode").html());
        $(".areaCountry").hide();
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

    var user_ipo;
    var user_ids;
    $("input[type='tel']").focus(function(){ $(this).css("border","1px solid #ffa81d"); });
    $("input[type='tel']").blur(
            function(){
                $(this).css("border","1px solid #dfdfdf");
                var textVal=$.trim($(this).val());
                $(this).val(textVal);
            }
    );
	$("#cap").click(function(){
        $("#cap").attr("src","/captcha.gen?"+Math.random());
    });
    // 提交验证代码
    $("#yunkeSubmit").submit(function(){
		var smsNumCode = $(".smsNumCode").html();
		var uname	   = $("input[name='uname']").val();
		var cid		   = $("input[name='cid']").val();
		var pass		   = $("input[name='pass']").val();
        if($('input[name=uname]').val()==''){
            layer.msg("请输入手机号");
            return false;
        }else{
            //记录用户名
            if(user_ids!=undefined){
                user_ipo=$("#x_uname").val()+','+user_ids;
            }else{
                user_ipo=$("#x_uname").val();
            }
            /*if(window.localStorage){
                localStorage.user_id=user_ipo;
            }*/
            //store.set('user_id',user_ipo);
            $(".yunkeSubmit .btn").text("登录中...").css('backgroundColor','#3a9eee');
            $.post("/index.main.loginAjax",{ uname:uname,cid:cid,pass:pass,smsNumCode:smsNumCode },function(r){
                if(r.error){
                    layer.msg(r.error);
                    $("#yunkeSubmit .btn").text("登录");
                    return false;
                }else{
                    location.href=r.url;

                }
                },"json"); 
            return false;
        }
    });
    $('input[name=uname],input[name=pass]').blur(function(){
        if($(this).val()!=''){
            $(this).css("border","solid 1px #dfdfdf").next().html('&nbsp;')
        }
    });
    //页面加载后获取焦点
    try { 
        user_ids=store.get("user_id");
    } catch (e) { 

    }
    /*if(window.localStorage){
        user_ids=localStorage.user_id;
    }*/
    if(user_ids!=undefined){
        var n_num=user_ids.split(",");
        n_num= $.unique(n_num);
        if(n_num.length>3){
            n_num.length=3;
        }
        $("#x_uname").val(n_num[0]);
        $("#x_pass").focus();
        if(n_num.length>0){
            $.each(n_num,function(i,n){ $(".log_ipo").append("<li>"+n+"</li>") });
        }
    }else{
        $("#x_uname").focus();
    };
    $(".log_ipo li").on('click',function(){
        $("#x_uname").val($(this).text());
        $(".log_ipo").hide();
        $("#x_pass").focus();
    });
    //单击显示历史
    $("#x_uname").bind({
        click:function(){
            $(".log_ipo").show();
        },

        keyup:function(){
            $(".log_ipo").hide();
        }
    });
    //点击空白隐藏
    // class为hideEvent(需要隐藏的元素)
    //ckEvent(点击元素)
    $(document).bind('click',function(e){
        var e=e || window.event;
        var elem= e.target || e.srcElement;
        if(!($(elem).hasClass("ckEvent"))){
            $(".hideEvent").slideUp('fast');
        }else{
            return false;
        }
    })
        
    $("#smsForm").submit(function(){
        var smsNumCode = $(".smsNumCode").html();
        var smobile = $.trim($("input[name='mobile']").val());
        var numCodeAndMobile = "+"+smsNumCode+smobile;
        var tagCode = $("input[name='tagCode']").val();
        var password = $("input[name='password']").val();
		var verifyCode = $.trim($("input[name='verifyCode']").val());
        if($("input[name='mobile']").val()==''){
            layer.msg('<?php echo tpl_modifier_tr('手机号不能为空','site.login'); ?>');
            return false;
        }else if($.trim($("input[name='verifyCode']").val())==''){
			 layer.msg('<?php echo tpl_modifier_tr('图片验证码不能为空','site.login'); ?>');
            return false;
		}else{
            $.post("/index.main.mobileSmsLogin",{ mobile:numCodeAndMobile,tagCode:tagCode,password:password,verifyCode:verifyCode},function(r){
                if(r.code=='100'){
                    location.href=r.data;
                }else{
                    layer.msg(r.error);
                }
                
        
            },"json");
        }
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
