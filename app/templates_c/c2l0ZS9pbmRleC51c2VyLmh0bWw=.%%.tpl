<div class="container pos-rel">
	<div class="login-box">
	<!-- 登录前 -->
	<?php if(empty(SlightPHP\Tpl::$_tpl_vars["user"])){; ?>
		<div class="login-user-face mt30 tac">
			<p class="face-img">
				<img src="/assets_v2/img/no-login-img.png" alt="未登录">
			</p>
		</div>
		<p class="fs16 mt5 tac">Hi~您好</p>
		<p class="mt30">
			<a href="/site.main.login" class="btn col-xs-9 fs14">登录</a>
			<a href="/site.main.register" class="green-button col-xs-9 right fs14">注册</a>
		</p>
	<?php }else{; ?>
 		<div class="login-user-sign-sudio pos-rel c-fr tac fs12">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["signInfo"])){; ?>
				<div class="audio-has-living">已签到</div>
			<?php }else{; ?>
				<div class="audio-living" onclick="SignAuto(this)">
					<span class="sign-sudio-icon"></span>
					签到
				</div>
			<?php }; ?>
			<audio id="sign-audio" src="<?php echo utility_cdn::img('/assets_v2/js/audio/sign_audio.wav'); ?>"></audio>
			<em id="sign_add" class="sign_add pos-abs" style="display:none;"><img src="<?php echo utility_cdn::img('/assets_v2/img/exp-icon.png'); ?>">+2</em>
			<div style="display:none;">
				<div id="gift-total-num"></div>
				<div id="gift-add-num"></div>	
				<div id="gift-add"></div>	
			</div>
 		</div>
		<div class="login-user col-md-20 p0">
			<div class="login-user-face tac">
				<div class="face-img">
					<img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"]['large'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['large']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/photoView.jpg'); ?><?php }; ?>">
				</div>
			</div>
			<p class="fs16 mt5 tac"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"]['name'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['name']; ?><?php }; ?></p>
		</div>
		<!-- 机构和老师双重身份登录后 -->
		<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isAdmin"]) and !empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
		<ul class="login-user-stat fs14 mt10 pb20">
			<li class="col-md-6">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseCount"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["courseCount"]; ?><?php }else{; ?>0<?php }; ?><br>
			<a href="/teacher.course.teacherOfCourseList"><?php echo tpl_modifier_tr('我的课程','LearningCenter'); ?></a>
			</li>
			<li class="col-md-7">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["countStudent"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["countStudent"]; ?><?php }else{; ?>0<?php }; ?><br>
			<a href="/teacher.manage.student"><?php echo tpl_modifier_tr('我的学生','LearningCenter'); ?></a>
			</li>
			<li class="col-md-6">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["remainCourse"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["remainCourse"]; ?><?php }else{; ?>0<?php }; ?><br>
			<a href="/teacher.course.teacherOfCourseList?ut=2"><?php echo tpl_modifier_tr('进行中课程','LearningCenter'); ?></a>
			</li>
		</ul>
		<p class="mt10">
			<a href="/org" class="gray-btn col-xs-9">机构管理</a>
			<a href="teacher.course.timetable2" class="gray-btn col-xs-9 right">教学管理</a>
		</p>
		<!-- 机构登录后 -->
		<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["isAdmin"])){; ?>
		<ul class="login-user-stat fs14 mt10 pb20">
			<li class="col-xs-6">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["allCourse"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["allCourse"]; ?><?php }else{; ?>0<?php }; ?><br>
			<a href="/user.org.course"><?php echo tpl_modifier_tr('机构课程','LearningCenter'); ?></a>
			</li>
			<li class="col-xs-7">
			<b><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["allTeacher"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["allTeacher"]; ?><?php }else{; ?>0<?php }; ?></b><br>
			<a href="/org.teacher.list"><?php echo tpl_modifier_tr('机构教师','LearningCenter'); ?></a>
			</li>
			<li class="col-xs-6">
			<b><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["allStudent"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["allStudent"]; ?><?php }else{; ?>0<?php }; ?></b><br>
			<a href="/org.student.list"><?php echo tpl_modifier_tr('机构学生','LearningCenter'); ?></a>
			</li>
		</ul>
		<p class="mt10">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isAdmin"]) and empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
			<a href="/org" class="gray-btn col-xs-20">进入机构管理中心</a>
			<?php }else{; ?>
			<a href="teacher.course.teacherOfCourseList" class="btn col-xs-9">机构管理</a>
			<a href="/org" class="gray-btn col-xs-9 right">教学管理</a>
			<?php }; ?>
		</p>
		<!-- 教师登录后 -->
		<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
		<ul class="login-user-stat fs14 mt10 pb20">
			<li class="col-md-6">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseCount"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["courseCount"]; ?><?php }else{; ?>0<?php }; ?><br>
			<a href="/teacher.course.teacherOfCourseList"><?php echo tpl_modifier_tr('我的课程','LearningCenter'); ?></a>
			</li>
			<li class="col-md-7">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["countStudent"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["countStudent"]; ?><?php }else{; ?>0<?php }; ?><br>
			<a href="/teacher.manage.student"><?php echo tpl_modifier_tr('我的学生','LearningCenter'); ?></a>
			</li>
			<li class="col-md-6">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["remainCourse"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["remainCourse"]; ?><?php }else{; ?>0<?php }; ?><br>
			<a href="/teacher.course.teacherOfCourseList?ut=2"><?php echo tpl_modifier_tr('进行中课程','LearningCenter'); ?></a>
			</li>
		</ul>
		<p class="mt10">
			<a href="/teacher.course.teacherOfCourseList" class="col-xs-20 gray-btn">进入教学管理</a>
		</p>
		<!-- 学生登录后 -->
		<?php }else{; ?>
		<ul class="login-user-stat fs14 mt10 pb20">
			<li class="col-sm-6">
			<span id="user_score"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userLevel"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userLevel"]->score; ?><?php }else{; ?>0<?php }; ?></span><br>
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
		<p class="mt10">
			<a href="/student.main.growth" class="gray-btn col-xs-20">进入学习中心</a>
		</p>
		<?php }; ?>
	<?php }; ?>
		<p class="login-help">
			<a href="https://www.yunke.com/index.help" target="_blank" class="col-xs-9 tac"><i class="help-icon"></i> 使用帮助</a>
			<!-- <a href="/site.main.register" class="col-xs-9 right tac"><i class="all-icon"></i> 常用软件</a> -->
		</p>
	</div>
</div>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.growthlayer.js'); ?>"></script>
<script type="text/javascript">
var _IsPlaying = false;//检测是否正在播放
function SignAuto(obj) {
	var signAudoObj = $(obj);
    var _Player = document.querySelector('#sign-audio');
    var uid = <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['uid']; ?><?php }else{; ?>0<?php }; ?>;
    if(navigator.userAgent.indexOf("MSIE")<0){
        if (_IsPlaying) {
            _Player.pause();
            _Player.src = '';
        } else {
            _Player.src = '/assets_v2/js/audio/sign_audio.wav';
            _Player.play();
        }
    }

    $.ajax({
    	url: 'student.main.signAjax',
    	type: 'post',
    	dataType: 'json',
    	data: { uid:uid },
    	success:function(ret) {
    		if(ret.code ==0) {
    			FlawerNum(ret.data.fk_level);
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
                signAudoObj.remove();
				$('.login-user-sign-sudio').append('<div class="audio-has-living">已签到</div>');
				$('#sign_add').html('<img src="<?php echo utility_cdn::img('/assets_v2/img/exp-icon.png'); ?>">+'+ret.data.add_score);
				$('#sign_add').show(function(){
				    $('#sign_add').animate({ top:'-45px' }).hide();
				    $('#user_score').text(parseInt($('#user_score').text())+parseInt(ret.data.add_score));
				});
    		}
    	}
    })
}
//鲜花总数
function FlawerSumNum(element,type){
    $.ajax({
        type:'post',
        url:'/user/gift/getStudentOrTeacherGiftSum',
        data:{ type:type},
        dataType:'json',
        success:function(data){
            if(data.code=='0'){
                element.html(data.gift_count);
            }
        }

    })
}
//奖励鲜花数
function FlawerNum(le){
    var giftTotalNum=$("#gift-total-num");
    var giftAddNum=$("#gift-add-num");
    var giftAdd=$("#gift-add");
    var type=1; //1:学生 2:教师
    $.ajax({
        type:'post',
        url:'/user/gift/getGiftSign',
        data:{ level:le},
        dataType:'json',
        success:function(data){
            if(data.code=='0'){
                giftAddNum.html(data.giftNum);
                FlawerSumNum(giftTotalNum, type);
            }
        }
    })
}
FlawerSumNum($("#gift-total-num"), 1);
</script>