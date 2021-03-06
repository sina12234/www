
<head>
<title>安全设置 - 云课 - 专业的在线学习平台</title>
<meta name="title" content="安全设置 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">

    	 <?php echo tpl_function_part("/index.main.header"); ?>
</head>
<body>
    <?php echo tpl_function_part("/index.main.usernav/setting"); ?>
    <!--studentPortal start-->
    <section class="p20 container">
            <!-- leftmenu start-->
            <?php echo tpl_function_part("/index.main.menu"); ?>
            <!-- leftmenu end -->
            <!--right start-->
            <div class="col-lg-16 col-xs-20 col-md-16 col-sm-20">
                <!--con start-->
                <div class="right-content">
                    <h1 class="fs16 fob">修改密码</h1>
                    <div class="setting mt30">
                        <div class="stit fs4 hidden-xs hidden-md hidden-lg hidden-sm">
                            <i class="seticon"></i>
                            <strong class="ml10 fs18">修改密码</strong>
                            <span class="ml10 hidden-xs">为了保护你的账户和资金安全，建议设定安全性高的密码并定期更换。</span>
                        </div>
                            <form id="pass_form" name="pass_form" method="post" autocomplete="off">
                                <input type="password" style="display:none;">
                                <ul class="form fs14 mt40">
                                    <li class="pd-b20">
                                        <div class="label col-lg-4 text-right col-sm-7 col-md-5 col-xs-9">输入旧密码：</div>
                                        <div class="label-for col-lg-16 col-xs-11 row">
                                            <input type="password"  name="old_password" class="col-sm-20 col-lg-12 col-xs-20">
                                            <span class="old-tip col-xs-20 col-lg-7"></span>
                                        </div>
                                    </li>
                                    <li class="pd-b20">
                                        <div class="label col-lg-4 text-right col-sm-7 col-xs-9 col-md-5">新密码：</div>
                                        <div class="label-for col-lg-16 row col-xs-11">
                                            <input type="password" name="new_password" class="col-sm-20 col-lg-12 col-xs-20">
                                            <span class="new-tip col-xs-20 col-lg-7"></span>
                                        </div>
                                    </li>
                                    <li class="pd-b20">
                                        <div class="label col-lg-4 text-right col-sm-7 col-xs-9 col-md-5">确认新密码：</div>
                                        <div class="label-for col-lg-16 col-xs-11 row">
                                            <input type="password" name="re_password"  class="col-sm-20 col-lg-12 col-xs-20">
                                            <span class="re-tip col-xs-20 col-lg-7"></span>
                                        </div>
                                    </li>
                                    <li class="pd-b20">
                                        <div class="label col-lg-4 col-sm-7 col-xs-5 col-md-5">&nbsp;</div>
                                        <div class="label-for  col-lg-16 row">
                                            <input class="btn blue-btn" id="psd_save" disabled style="line-height:30px;border:0;padding:0 40px;background: #198fee;color:#fff;"  value="保存" type="button" />
                                        </div>
                                    </li>
                                    </ul>
                                </form>
                    </div>
                    <div class="clear"></div>
                </div>
                <!--con end-->
            </div>
            <!--right start-->
    </section>
    <!--studentPortal end-->
    <?php echo tpl_function_part("/index.main.footer"); ?>
</body>
</html>
<script type="text/javascript">
        $('#alert1').on('click', function(){
            layer.open({
                type: 2,
                area: ['720px', '515px'],
                shadeClose: true, //点击遮罩关闭
                content: 'photo-upload.html'
            });
        });
		$(function(){
			$("input[type='password']").focus(function() {
				$(this).css("border","1px solid #198fee");
			})
			$("input[type='password']").blur(function() {
				$(this).css("border","1px solid #d5d5d5");
			});

			$('input[name=old_password]').blur(function(){
				var opass = $(this).val();
				if( !opass ){
					$('.old-tip').html('<span class="tips-error"></span>请输入旧密码!');
					$('.old-tip').css('color','#f01400');
					$("input[name='old_password']").css("border","1px solid #f01400");
					return;
				}
				 $.ajax({
				 	type:'post',
				    url: '/student/security/verifyPsd',
				    data: { old_password:opass },
				    dataType: 'json',
				    success:function(data){
                    	if( data.result.code == 0){
							$('.old-tip').html('<span class="tips-right"></span>√');
							//$('.old-tip').html('<span class="tips-right"></span>'+data.result.msg);
							$('.old-tip').css('color','green');
							$("input[name='old_password']").css("border","1px solid #d5d5d5");
							$('.btn-bg').css('background','#ffa81e');
							$('#psd_save').prop('disabled',false)
                      	}else{
							$('.old-tip').html('<span class="tips-error"></span>'+data.result.msg);
							$('.old-tip').css('color','red');
							$("input[name='old_password']").css("border","1px solid red");
                       	}
                    }
                 });
			});

			$('input[name=new_password]').blur(function(){
				 var npass = $(this).val();
				 if( !npass ){
					$('.new-tip').html('<span class="tips-error"></span>请输入新密码!');
					$('.new-tip').css('color','red');
                     return;
				 }
				 if( npass.length < 6 || npass.length > 16 ) {
					$('.new-tip').html('<span class="tips-error"></span>密码不能少于6个，多于16个字符');
					$('.new-tip').css('color','red');
					return;
				 }
				$('.new-tip').html('<span class="tips-right"></span>√');
				$('.new-tip').css('color','green');
			});

			$('input[name=re_password]').blur(function(){
				var re_pass = $(this).val();
				var new_pass = $('input[name=new_password]').val();
				if(!re_pass){
					$('.re-tip').css('color','red');
					$('.re-tip').html('<span class="tips-error"></span>请输入确认密码!');
					return;
				}
				if( re_pass != new_pass ){
					$('.re-tip').html('<span class="tips-error"></span>确认密码与新密码不一致');
					$('.re-tip').css('color','red');
					return;
				}
				$('.re-tip').html('<span class="tips-right"></span>√');
				$('.re-tip').css('color','green');


			});
			$('#psd_save').click(function(){
				var params = {
					'old_password' : $('input[name=old_password]').val(),
					'new_password' : $('input[name=new_password]').val(),
					're_password'  : $('input[name=re_password]').val(),
				}

				if( !params.old_password ){
				    $('.old-tip').html('<span class="tips-error"></span>请输入旧密码!');
					$('.old-tip').css('color','red');
					return;
				}
				if( !params.new_password ){
				    $('.new-tip').html('<span class="tips-error"></span>请输入新密码!');
					$('.new-tip').css('color','red');
					return;
				}
				if(!params.re_password){
				    $('.re-tip').html('<span class="tips-error"></span>请输入确认密码!');
					$('.re-tip').css('color','red');
					return;
				}

				if( params.new_password != params.re_password ){
				    $('.re-tip').html('<span class="tips-error"></span>确认密码输入错误!');
					$('.re-tip').css('color','red');
					return;
				}
				$.ajax({
					type:'post',
					url: '/index.user.password',
					data: $('#pass_form').serialize(),
					dataType: 'json',
					success:function(data){
						if( data.result.code == 0){
							layer.msg(data.result.msg);
							location = location;
						}else{
							if(data.result.code == -1 ) {
				    			$('.old-tip').html('<span class="tips-error"></span>'+data.result.msg);
								$('.old-tip').css('color','red');
							}
							if(data.result.code == -2 ) {
                                $('.new-tip').html('<span class="tips-error"></span>'+data.result.msg);
								$('.new-tip').css('color','red');
							}
							if(data.result.code == -3 ) {
								$('.re-tip').html('<span class="tips-error"></span>'+data.result.msg);
							    $('.re-tip').css('color','red');
						    }
						}
					}
				});

			});

		});
</script>
