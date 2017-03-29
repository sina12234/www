<!DOCTYPE html>
<html>

<head>
    <title>高能100 - 专业的K12在线教育平台</title>
    <meta name="title" content="高能100 - 专业的K12在线教育平台 在线直播">
    <meta name="keywords" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线 k12 小学数学 初中数学">
    <meta name="description" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线直播">
    <?php echo tpl_function_part("/index.main.header"); ?>
</head>

<body>
    <?php echo tpl_function_part("/index.main.top"); ?>
    <div class="container">
        <div class="row">
            <div class="logo col-sm-5"><a href="/"><img src="<?php echo utility_cdn::img('/assets_v2/img/logo.png'); ?>" alt="云课"></a></div>
        </div>
    </div>
    <section class="p20">
        <div class="container">
            <div class="row">
                <div class="col-sm-17">
                    <div class="forget-step col-sm-18">
                        <div class="col-sm-5 col-xs-5 step">
                            <p class="bg"></p>
                            <p class="num">1</p>
                            <p class="text">确认账号</p>
                        </div>
                        <div class="step col-sm-5 col-xs-5">
                            <p class="bg"></p>
                            <p class="num">2</p>
                            <p class="text">验证身份</p>
                        </div>
                        <div class="step col-sm-5 col-xs-5">
                            <p class="blue-bg"></p>
                            <p class="blue-num">3</p>
                            <p class="blue-text">设置新密码</p>
                        </div>
                        <div class="step col-sm-5 col-xs-5">
                            <p class="bg"></p>
                            <p class="num">4</p>
                            <p class="text">完成</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!-- 账号确认 -->
                    <div class="col-sm-20 col-md-10 col-lg-10 col-xs-20 p40 col-md-offset-5 col-lg-offset-5">
                        <form action="#" autocomplete="off" class="form col-sm-20" id="form">
                            <input type="password" style="display:none">
                            <ul class="row">
                                <li>
                                    <div class="label col-sm-6 col-md-6 col-lg-6 col-xs-6 text-right">新密码：</div>
                                    <div class="label-for col-sm-10 col-lg-14 col-md-14 col-xs-10 row">
                                        <input type="password" placeholder="请填写新密码" name="password" class="col-sm-20 lh-35">
                                        <div class="tips cRed col-sm-20">&nbsp;</div>
                                    </div>
                                </li>
                                <li>
                                    <div class="label col-sm-6 col-md-6 col-lg-6 col-xs-6 text-right">确认新密码：</div>
                                    <div class="label-for col-sm-10 col-lg-14 col-md-14 col-xs-14 row">
                                        <input type="password" placeholder="请填写确认新密码" name="password2" class="col-sm-20 lh-35">
                                        <div class="tips cRed col-sm-20">&nbsp;</div>
                                    </div>
                                </li>
                                <li>
                                    <div class="mt10 label-for col-sm-10 row col-sm-offset-6 col-lg-14 col-lg-offset-6 col-md-offset-6 col-md-14 col-xs-14 col-xs-offset-6"><button type="submit" class="btn col-sm-20i col-xs-15 blue-btn row fs14">确认</button></div>

                                </li>
                            </ul>
                        </form>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php echo tpl_function_part("/index.main.footer"); ?>
</body>
<script type="text/javascript">
$(function  () {
    $("input[type='password']").focus(function() {
        $(this).css("border","1px solid #ffa81d");
    });
    $("input[type='password']").change(function() {
        $(this).css("border","1px solid #ccc").nextAll('.tips').html("&nbsp");
    });
   $("input[type='password']").blur(function() {
        $(this).css("border","1px solid #ccc");
   });
   
    $("#form").submit(function(){
        if($("input[name=password]").val()==''){
            $("input[name=password]").focus().css("border","solid 1px #fc401b").nextAll('.tips').html("请输入密码");
            return false;
        }
        if($("input[name=password2]").val()==''){
            $("input[name=password2]").focus().css("border","solid 1px #fc401b").nextAll('.tips').html("请输入密码");
            return false;
        }
        $.post("/index.main.forget3Ajax",$("#form").serialize(),function(r){
            if(r.error){
                $("[name="+r.field+"]").focus().css("border","solid 1px #fc401b").nextAll('.tips').html(r.error);
                return false;
            }else{
                location="/index.main.forget4";
            }
        },"json");
            return false;
    })    
})
</script>
</html>
