<div class="container pos-rel">
	<?php if(empty(SlightPHP\Tpl::$_tpl_vars["user"])){; ?>
	<!-- 登录前 -->
	<div class="login-box">
		<p class="login-date fs20"><?php echo tpl_modifier_tr('今天是','site.login'); ?> <?php echo SlightPHP\Tpl::$_tpl_vars["month"]; ?>-<?php echo SlightPHP\Tpl::$_tpl_vars["day"]; ?>  <?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["week"],'site.login'); ?></p>
		<form method="post" class="login-form" autocomplete="off" id="login-form">
			<input type="password" style="display:none;">
			<div class="pos-rel">
				<input type="text" name="uname" placeholder="<?php echo tpl_modifier_tr('请输入手机号','site.login'); ?>">
			</div>
			<div class="pos-rel">
				<input type="password" name="pass" placeholder="<?php echo tpl_modifier_tr('请输入密码','site.login'); ?>">
			</div>
			<div class="pos-rel">
				<?php /*<div class="c-fl">
					<label for="rem-id"><span class="check-box"></span><?php echo tpl_modifier_tr('下次自动登录','site.login'); ?></label>
					<input type="radio" id="rem-id" name="rem-id" style="display:none">
				</div>*/?>
				<a href="/site.main.forget1"><?php echo tpl_modifier_tr('忘记密码','site.login'); ?>？</a>
				<a href="/site.main.register" class="c-fr"><?php echo tpl_modifier_tr('立即注册','site.login'); ?></a>
			</div>
			<button type="submit"><?php echo tpl_modifier_tr('登录','site.login'); ?></button>
		</form>
		<div class="login-api">
			<p><?php echo tpl_modifier_tr('使用社交账号直接登录','site.login'); ?></p>
			<a href="/user/parterner/weixin?from=<?php echo SlightPHP\Tpl::$_tpl_vars["from"]; ?>" class=login-api-weixin></a>
			<a href="/user/parterner/qq?from=<?php echo SlightPHP\Tpl::$_tpl_vars["from"]; ?>" class=login-api-qq></a>
		</div>
	</div>
	<?php }else{; ?>
	<!-- 机构和老师双重身份登录后 -->
	<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isAdmin"]) and !empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
	<div class="login-box">
		<div class="login-user col-lg-20">
			<div class="col-md-14 col-md-offset-6">
				<a href="javascript:void(0)" class="login-user-face col-md-8"><img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"]['large'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['large']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/photoView.jpg'); ?><?php }; ?>"></a>
				<a href="javascript:void(0)" class="login-user-name fs14 col-md-10"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"]['name'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['name']; ?><?php }; ?></a>
				<?php /* <a href="https://www.yunke.com/index.rank.rule" target="_blank" class="level-icon<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userLevel"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userLevel"]->fk_level; ?><?php }else{; ?>1<?php }; ?>"></a>
				<a href="javascript:void(0)" class="login-user-val fs14 col-lg-15"><?php echo tpl_modifier_tr('经验值','LearningCenter'); ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userLevel"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userLevel"]->score; ?><?php }else{; ?>0<?php }; ?></a>*/?>

				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["signInfo"])){; ?>
				<a href="javascript:void(0)" class="login-user-signed"><?php echo tpl_modifier_tr('已签到','LearningCenter'); ?></a>
				<?php }else{; ?>
				<a href="javascript:void(0)" class="login-user-sign" id="StudentSign" onclick="SignAuto()"><?php echo tpl_modifier_tr('签到','LearningCenter'); ?></a>
				<?php }; ?>
				<audio id="sign-audio" src="<?php echo utility_cdn::img('/assets_v2/js/audio/sign_audio.wav'); ?>"></audio>
				<em id="sign_add"><img src="<?php echo utility_cdn::img('/assets_v2/img/exp-icon.png'); ?>">+2</em>
			</div>
		</div>
		<ul class="login-user-stat fs14">
			<li class="col-md-6">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["allClass"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["allClass"]; ?><?php }else{; ?>0<?php }; ?><br>
			<a href="teacher.course.timetable"><?php echo tpl_modifier_tr('我的班级','LearningCenter'); ?></a>
			</li>
			<li class="col-md-7">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["countStudent"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["countStudent"]; ?><?php }else{; ?>0<?php }; ?><br>
			<a href="/teacher.manage.student"><?php echo tpl_modifier_tr('我的学生','LearningCenter'); ?></a>
			</li>
			<li class="col-md-6">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["remainCourse"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["remainCourse"]; ?><?php }else{; ?>0<?php }; ?><br>
			<a href="teacher.course.timetable"><?php echo tpl_modifier_tr('进行中课程','LearningCenter'); ?></a>
			</li>
		</ul>
		<div class="login-user-expect fs14">
			<p>
			<span class="c-fl">
				<?php echo tpl_modifier_tr('待上直播课程','LearningCenter'); ?>
				<a href="teacher.course.timetable" class="cYellow"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["remainPlan"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["remainPlan"]; ?><?php }else{; ?>0<?php }; ?>
					<span style="color:#666"><?php echo tpl_modifier_tr('节','LearningCenter'); ?></span>
				</a>
			</span>
			<?php /*  <span class="c-fr">未备课 <span class="cYellow">30</span> 节</span></p>*/?>
		</div>
		<a href="/org" class="entry-org"><?php echo tpl_modifier_tr('机构管理','org'); ?></a>
		<a href="teacher.course.teacherOfCourseList" class="entry-teacher"><?php echo tpl_modifier_tr('教学管理','org'); ?></a>
	</div>
	<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["isAdmin"])){; ?>
	<!-- 机构登录后 -->
	<div class="login-box">
		<div class="login-user">
			<div class="col-md-14 col-lg-offset-6">
				<a href="javascript:void(0)" class="login-user-face col-md-8"><img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"]['large'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['large']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/photoView.jpg'); ?><?php }; ?>"></a>
				<a href="javascript:void(0)" class="login-user-name fs14 col-md-10"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"]['name'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['name']; ?><?php }; ?></a>
				<?php /*<a href="https://www.yunke.com/index.rank.rule" target="_blank" class="level-icon<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userLevel"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userLevel"]->fk_level; ?><?php }else{; ?>1<?php }; ?>"></a>
				<a href="javascript:void(0)" class="login-user-val fs14 col-lg-10"><?php echo tpl_modifier_tr('经验值','LearningCenter'); ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userLevel"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userLevel"]->score; ?><?php }else{; ?>0<?php }; ?></a>*/?>

				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["signInfo"])){; ?>
				<a href="javascript:void(0)" class="login-user-signed"><?php echo tpl_modifier_tr('已签到','LearningCenter'); ?></a>
				<?php }else{; ?>
				<a href="javascript:void(0)" class="login-user-sign" id="StudentSign" onclick="SignAuto()"><?php echo tpl_modifier_tr('签到','LearningCenter'); ?></a>
				<?php }; ?>
				<audio id="sign-audio" src="<?php echo utility_cdn::img('/assets_v2/js/audio/sign_audio.wav'); ?>"></audio>
				<em id="sign_add"><img src="<?php echo utility_cdn::img('/assets_v2/img/exp-icon.png'); ?>">+2</em>
			</div>
		</div>
		<ul class="login-user-stat fs14">
			<li class="col-md-6">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["allCourse"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["allCourse"]; ?><?php }else{; ?>0<?php }; ?><br>
			<a href="/user.org.course"><?php echo tpl_modifier_tr('机构课程','LearningCenter'); ?></a>
			</li>
			<li class="col-md-7">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["allTeacher"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["allTeacher"]; ?><?php }else{; ?>0<?php }; ?><br>
			<a href="/org.teacher.list"><?php echo tpl_modifier_tr('机构教师','LearningCenter'); ?></a>
			</li>
			<li class="col-md-6">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["allStudent"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["allStudent"]; ?><?php }else{; ?>0<?php }; ?><br>
			<a href="/org.student.list"><?php echo tpl_modifier_tr('机构学生','LearningCenter'); ?></a>
			</li>
		</ul>
		<div class="login-user-expect fs14">
			<p>
			<span class="c-fl">
				<?php echo tpl_modifier_tr('机构昨日新增订单','org'); ?>：
				<a href="/org" class="cYellow"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["yestodayOrder"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["yestodayOrder"]; ?><?php }else{; ?>0<?php }; ?></a>
			</span>
			<span class="c-fl">
				<?php echo tpl_modifier_tr('机构昨日新增收入','org'); ?>：
				<a href="/org" class="cYellow"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["yestodayOrder"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["yestodayOrder"]; ?><?php }else{; ?>0<?php }; ?></a>
			</span>
			</p>
		</div>
		<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isAdmin"]) and empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
		<a href="/org" class="entry-btn"><?php echo tpl_modifier_tr('进入机构管理中心','org'); ?></a>
		<?php }else{; ?>
		<a href="teacher.course.teacherOfCourseList" class="entry-org"><?php echo tpl_modifier_tr('机构管理','org'); ?></a>
		<a href="/org" class="entry-teacher"><?php echo tpl_modifier_tr('教学管理','org'); ?></a>
		<?php }; ?>
	</div>
	<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
	<!-- 教师登录后 -->
	<div class="login-box">
		<div class="login-user">
			<div class="col-md-14 col-lg-offset-6">
				<a href="javascript:void(0)" class="login-user-face col-md-8"><img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"]['large'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['large']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/photoView.jpg'); ?><?php }; ?>"></a>
				<a href="javascript:void(0)" class="login-user-name fs14 col-md-10"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"]['name'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['name']; ?><?php }; ?></a>
				<?php /*<a href="https://www.yunke.com/index.rank.rule" target="_blank" class="level-icon<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userLevel"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userLevel"]->fk_level; ?><?php }else{; ?>1<?php }; ?>"></a>
				<a href="javascript:void(0)" class="login-user-val fs14 col-lg-10"><?php echo tpl_modifier_tr('经验值','LearningCenter'); ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userLevel"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userLevel"]->score; ?><?php }else{; ?>0<?php }; ?></a>*/?>

				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["signInfo"])){; ?>
				<a href="javascript:void(0)" class="login-user-signed"><?php echo tpl_modifier_tr('已签到','LearningCenter'); ?></a>
				<?php }else{; ?>
				<a href="javascript:void(0)" class="login-user-sign" id="StudentSign" onclick="SignAuto()"><?php echo tpl_modifier_tr('签到','LearningCenter'); ?></a>
				<?php }; ?>

				<audio id="sign-audio" src="<?php echo utility_cdn::img('/assets_v2/js/audio/sign_audio.wav'); ?>"></audio>
				<em id="sign_add"><img src="<?php echo utility_cdn::img('/assets_v2/img/exp-icon.png'); ?>">+2</em>
			</div>
		</div>
		<ul class="login-user-stat fs14">
			<li class="col-md-6">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["allClass"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["allClass"]; ?><?php }else{; ?>0<?php }; ?><br>
			<a href="teacher.course.timetable"><?php echo tpl_modifier_tr('我的班级','LearningCenter'); ?></a>
			</li>
			<li class="col-md-7">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["countStudent"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["countStudent"]; ?><?php }else{; ?>0<?php }; ?><br>
			<a href="/teacher.manage.student"><?php echo tpl_modifier_tr('我的学生','LearningCenter'); ?></a>
			</li>
			<li class="col-md-6">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["remainCourse"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["remainCourse"]; ?><?php }else{; ?>0<?php }; ?><br>
			<a href="teacher.course.timetable"><?php echo tpl_modifier_tr('进行中课程','LearningCenter'); ?></a>
			</li>
		</ul>
		<div class="login-user-expect fs14">
			<p>
			<span class="c-fl">
				<?php echo tpl_modifier_tr('待上直播课程','LearningCenter'); ?>
				<a href="teacher.course.timetable" class="cYellow"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["remainPlan"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["remainPlan"]; ?><?php }else{; ?>0<?php }; ?>
					<span style="color:#666"><?php echo tpl_modifier_tr('节','LearningCenter'); ?></span>
				</a>
			</span>
			<?php /* <span class="c-fr">未备课 <span class="cYellow">30</span> 节</span></p>*/?>
		</div>
		<a href="/teacher.course.teacherOfCourseList" class="entry-btn"><?php echo tpl_modifier_tr('进入教学管理','LearningCenter'); ?></a>
	</div>
	<?php }else{; ?>
	<!-- 学生登录后 -->
	<div class="login-box">
		<div class="login-user">
			<div class="col-md-14 col-md-offset-6">
				<a href="javascript:void(0)" class="login-user-face col-md-8"><img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"]['large'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['large']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/photoView.jpg'); ?><?php }; ?>"></a>
				<a href="javascript:void(0)" class="login-user-name fs14 col-md-10"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"]['name'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['name']; ?><?php }; ?></a>
				<div class="col-sm-10">
					<a href="https://www.yunke.com/index.rank.rule" target="_blank" class="level-icon<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userLevel"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userLevel"]->fk_level; ?><?php }else{; ?>1<?php }; ?>"></a>
				</div>
				<?php /* <a href="javascript:void(0)" class="login-user-val fs14 col-md-10"><?php echo tpl_modifier_tr('经验值','LearningCenter'); ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userLevel"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userLevel"]->score; ?><?php }else{; ?>0<?php }; ?></a>*/?>
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["signInfo"])){; ?>
				<a href="javascript:void(0)" class="login-user-signed"><?php echo tpl_modifier_tr('已签到','LearningCenter'); ?></a>
				<?php }else{; ?>
				<a href="javascript:void(0)" class="login-user-sign" id="StudentSign" onclick="SignAuto()"><?php echo tpl_modifier_tr('签到','LearningCenter'); ?></a>
				<?php }; ?>
			</div>
			<audio id="sign-audio" src="<?php echo utility_cdn::img('/assets_v2/js/audio/sign_audio.wav'); ?>"></audio>
			<em id="sign_add"><img src="<?php echo utility_cdn::img('/assets_v2/img/exp-icon.png'); ?>">+2</em>
		</div>
		<ul class="login-user-stat fs14">
			<li class="col-sm-6">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userLevel"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userLevel"]->score; ?><?php }else{; ?>0<?php }; ?><br>
			<?php echo tpl_modifier_tr('经验值','LearningCenter'); ?>
			</li>
			<li class="col-sm-7">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["studyTime"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["studyTime"]; ?><?php }else{; ?>0<?php }; ?><br>
			<?php echo tpl_modifier_tr('学习时长','LearningCenter'); ?>
			</li>
			<li class="col-sm-6">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["studentCourse"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["studentCourse"]; ?><?php }else{; ?>0<?php }; ?><br>
			<?php echo tpl_modifier_tr('进行中课程','LearningCenter'); ?>
			</li>
		</ul>
		<div class="login-user-expect fs14">
			<p class="tac">
			<?php echo tpl_modifier_tr('今日待上直播课程','LearningCenter'); ?>
			<a href="student.course.mycourse" class="cYellow"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["studentPlan"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["studentPlan"]; ?><?php }else{; ?>0<?php }; ?></a>
			</p>
		</div>
		<a href="/student.main.growth" class="entry-btn"><?php echo tpl_modifier_tr('进入学习中心','LearningCenter'); ?></a>
	</div>
	<?php }; ?>
	<?php }; ?>
</div>
<script>
$(function(){
    $("#login-form").submit(function(){
        if($("input[name=uname]").val()=='' || $("input[name=uname]").val()=="<?php echo tpl_modifier_tr('手机号','site.login'); ?>"){
            layer.msg("<?php echo tpl_modifier_tr('请输入手机号','site.login'); ?>");
            return false;
        }else if($("input[name=pass]").val()=='' || $("input[name=pass]").val()=="<?php echo tpl_modifier_tr('密码','site.login'); ?>"){
            layer.msg("<?php echo tpl_modifier_tr('请输入密码','site.login'); ?>");
            return false;
        }else{
            $.post("/site.main.loginajax",$("#login-form").serialize(),function(r){
                if(r.error){
                    layer.msg(r.error);
                }else{
                    location.href=r.url;
                }
            },"json");
            return false;
        }
    });

    $('input[name=uname],input[name=pass]').change(function(){
        if($(this).val()!=''){
            $(this).css("border","solid 1px #dfdfdf").next().html('&nbsp;')
        }
    });

    $('.tab-hd li').hover(function(){
        $(this).addClass('curr').siblings().removeClass('curr');
        $('#tab-list').find('.tab-list:eq('+$(this).index()+')').show().siblings().hide();
    },function(){ });

});

var _IsPlaying=false;
function SignAuto(){
    var _Player = document.querySelector('#sign-audio');
    
    var Defalut_Version = 8.0;
    var ua = navigator.userAgent.toLowerCase();
    var isIE = ua.indexOf("msie")>-1;
    var ieVersion;
    if(isIE){
        ieVersion =  ua.match(/msie ([\d.]+)/)[1];
        if(ieVersion <= Defalut_Version ){
        }else{
            if (_IsPlaying) {
            // 如果正在播放, 停止播放并停止读取此音乐文件
            _Player.pause();
            _Player.src = '';
            } else {
            _Player.src = '/assets_v2/js/audio/sign_audio.wav';
            _Player.play();
            }
        }
    }else{
            if (_IsPlaying) {
            // 如果正在播放, 停止播放并停止读取此音乐文件
            _Player.pause();
            _Player.src = '';
            } else {
            _Player.src = '/assets_v2/js/audio/sign_audio.wav';
            _Player.play();
            }
    
    }

    var uid = <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['uid']; ?><?php }else{; ?>0<?php }; ?>;
   	$.ajax({
        type:"post",
        url: 'student.main.signAjax',
        data:{ uid:uid },
        dataType:'json',
        success:function(ret){
            if(ret.code ==0){
            	setTimeout(function() {
            		window.location.reload();
            	} ,1000);
                if(ret.data.combo == 5){
                    $("body").GrowthLayer({
                        types:"five",// five|smallgrowth|biggrowth
                        space:3000, //时间间隔
                        auto: true, //自动关闭
                    });
                }
                if(ret.data.up_type == 1){
                     setTimeout(function(){
                     $("body").GrowthLayer({
                        types:"smallgrowth",
                        space:5000,
                        auto:true,
                        growth:ret.data.fk_level,
                        score:ret.data.score
                        })
                    },3000);
                }else if(ret.data.up_type == 2){
                    setTimeout(function(){
                        $("body").GrowthLayer({
                        types:"biggrowth",
                        space:5000,
                        auto:true,
                        growth:ret.data.fk_level,
                        score:ret.data.score
                        });
                    	window.location.reload();
                    },3000)

                }

                $('#sign-audo').animate({ height:"25px" },function(){
                	$('#sign-audio').before('<a href="javascript:void(0);" id="signed" style="color:#666;" class="login-user-signed">'+"<?php echo tpl_modifier_tr('今日已签到','LearningCenter'); ?>"+'</a>');
                	$('#sign-audo').height("50px").hide();
                });
            	$("#sign_add").html('<img src="<?php echo utility_cdn::img('/assets_v2/img/exp-icon.png'); ?>">+'+ret.data.add_score);
                $("#sign_add").show(function(){
                    $("#sign_add").animate({ top:"-20px",opactiy:"1" },function(){
                        $("#sign_add").hide().animate({ top:"-30px" });
                        $("#user_score").val(parseInt(<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userLevel"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userLevel"]->score; ?><?php }else{; ?>0<?php }; ?>)+parseInt(ret.data.add_score));
                	});
                });

    		}
    	}
    })
}
</script>
