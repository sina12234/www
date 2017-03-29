<!doctype html>
<html>
<head>
<script>
    console.log = function(){ };
</script>
<title><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["plan_info"]->title)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->title; ?><?php }; ?> - 在线教室 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="高能100 - 开始上课 - 专业的K12在线教育平台 在线直播">
<meta name="keywords" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线 k12 小学数学 初中数学">
<meta name="description" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线直播">
<?php echo tpl_function_part("/site.main.header"); ?>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets_v2/css/t-player.css'); ?>">
<style>
    div.option-list { cursor:pointer;}
</style>
<script type="text/javascript" src="//www.yunke.com/crossdomain.php"></script>
<script src="<?php echo utility_cdn::js('/assets/js/swfobject.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/player.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/HTML5Player.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets/js/json2.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/display_optimize.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/message_v2.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/course_start.js'); ?>"></script>
<script type="text/javascript">
var plan_id="<?php echo SlightPHP\Tpl::$_tpl_vars["plan_id"]; ?>";
var class_id="<?php echo SlightPHP\Tpl::$_tpl_vars["class_id"]; ?>";
			var planId = <?php echo SlightPHP\Tpl::$_tpl_vars["plan_id"]; ?>;
			var exams = <?php echo SlightPHP\Tpl::$_tpl_vars["exams"]; ?>;
			var courseId = <?php echo SlightPHP\Tpl::$_tpl_vars["course_id"]; ?>;
			var userId = <?php echo SlightPHP\Tpl::$_tpl_vars["user_id"]; ?>;
			var userToken = "<?php echo SlightPHP\Tpl::$_tpl_vars["user_token"]; ?>";
			var userFlag = userToken.substring(0, 5);
			var userThumb = "<?php echo SlightPHP\Tpl::$_tpl_vars["user_thumb"]; ?>";
			var filecdn = "<?php echo SlightPHP\Tpl::$_tpl_vars["filecdn"]; ?>";
			var chatPull = "<?php echo utility_cdn::chat_pull(); ?>";
			var chatWs = "<?php echo utility_cdn::chat_ws(); ?>";
			var chatPullSet = "<?php echo utility_cdn::chat_pullset(); ?>";
            var isLiving = <?php if(SlightPHP\Tpl::$_tpl_vars["plan_info"]->plan_status=="living"){; ?>true<?php }else{; ?>false<?php }; ?>;
			var teacherId = '<?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->uid; ?>';
			var teacherName = '<?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->name; ?>';
			var teacherThumb = '<?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->avatar->small; ?>';
			var feeType = <?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->fee_type; ?>;
			if("/" != filecdn[filecdn.length-1]){
				filecdn += "/";
			}
			var message ;
			$(document).ready(function(){
				var flashvars = {
					auto_play:true,
					video_muted:true,
					fullscreen_func:"FullScreen.toggle",
					plan_id:"<?php echo SlightPHP\Tpl::$_tpl_vars["plan_id"]; ?>"
				};

				var params = {
					menu: "false",
					scale: "noScale",
					allowFullscreen: "true",
					allowScriptAccess: "always",
					bgcolor: "",
					wmode:"opaque"
				};
				var attributes = {
					id:"player",
					name:"player"
				};
				swfobject.embedSWF(
					"<?php echo utility_cdn::swf('/assets/swf/Player.swf'); ?>",
					"player", "100%", "100%", "10.0.0",
					"<?php echo utility_cdn::img('/assets/swf/expressInstall.swf'); ?>",
					flashvars, params, attributes,function(r){
						if(r.success==false){
							H5Player.init(planId,"");
						}
					});
					message = planStart();
			});
			function start(){
				Player.startPlan(plan_id,function(r){
					if(r.code==0){
						$("#start-btn").hide();
						$("#continue-btn").hide();
						$("#stop-btn").show();
						$("#player").show();
						$("#player_2").hide();
						//message.startClass();
					}else{
						layer.msg(r.error);
					}
				});
			}
				function stop2(){
					layer.confirm('<p style="font-size:18px;color:#000;padding:20px">确定下课吗？点击后直播课结束</p>',
						{ btn:['确定','取消'],
							title:['下课提示','background:#198fee;color:#fff;height:40px;line-height:40px'] },
							function(index){ stop();message.request_eval("stop");layer.close(index) },function(){ }
					)
				}
				function stop(){
					Player.stopPlan(plan_id,function(r){
						if(r.code==0){
							$("#continue-btn").show();
							$("#stop-btn").hide();
						}else{
							layer.msg(r.error);
						}
					});
				}
</script>
</head>
<body>
<div id="content">
<!-- left -->
    <div class="left-player" id="left-player">
        <div class="tab fs14">
            <div class="c-fl" style="width:90%" id="feeUser">
                <span>学生列表</span>
                <span>(<span id="online_num" class="cblue">0</span>/<span id="fee_num"><?php if(empty(SlightPHP\Tpl::$_tpl_vars["course_users"])){; ?>0<?php }else{; ?><?php echo count(SlightPHP\Tpl::$_tpl_vars["course_users"]); ?><?php }; ?></span>)</span>
			</div>
            <span id="search1" class="c-fr"><span class="so-icon icon" id="search"></span></span>
						<div id="search2" class="so-area" style="display:none;">
							<div class="so-area-c">
								<input type="text" id="so-input"><span class="so-icon icon" id="so"></span>
							</div>
						</div>
					</div>
					<div id="students_display" class="scrollbox clearfix">
						<div class="order-filter">
							<div class="divselect">
								<cite>全部学员</cite>
								<dl id="student_filter">
									<dd><a selectid="1">全部学员</a></dd>
									<dd><a selectid="2">未点名学员</a></dd>
									<dd><a selectid="3">已点名学员</a></dd>
								</dl>
							</div>
						</div>
						<ul id="students" data-student="act" class="Student-list">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course_users"])){; ?>
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["course_users"] as SlightPHP\Tpl::$_tpl_vars["_user"]){; ?>
							<li data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["_user"]->uid; ?>" data-name="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["_user"]->user_info->name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["_user"]->user_info->name; ?><?php }; ?>" data-token="" data-display="1" data-call="0" data-hand="0" data-good="0" class="gray" style="display:none;">
							<b data-index>1</b>
							<span data-reply class="face">
								<img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["_user"]->user_info->thumb_med)){; ?><?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["_user"]->user_info->thumb_med); ?><?php }; ?>">
							</span>
							<span class="name">
								<?php if(isset(SlightPHP\Tpl::$_tpl_vars["_user"]->user_info->student_name) && SlightPHP\Tpl::$_tpl_vars["_user"]->user_info->student_name){; ?>
									<?php echo SlightPHP\Tpl::$_tpl_vars["_user"]->user_info->student_name; ?>
								<?php }else{; ?>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["_user"]->user_info->name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["_user"]->user_info->name; ?><?php }; ?>
								<?php }; ?>
							</span>
                                                        
							<span class="num-praise">赞(<span data-good>0</span>)</span>
							<span class="name col-lg-10 name-Mleft">(<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["_user"]->user_info->mobile)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["_user"]->user_info->mobile; ?><?php }; ?>)</span>
							<div class="other">
								<span data-full="full" class="full-icon icon" style="display:none;"></span>
								<span data-full="wfull" class="w-full-icon icon" style="display:none;"></span>
								<span data-speech="speech" class="sound-icon icon" style="display:none;"></span>
								<span data-hand="hand" class="hand-icon icon" style="display:none;"></span>
								<span data-micro="succeed" class="m-phone-icon icon" style="display:none;"></span>
							</div>
							</li>
							<?php }; ?>
							<?php }; ?>
						</ul>
					</div>
					<div class="tab fs14" id="tourist">
						<span>游客</span>
						<span>(<span id="free_num" class="cblue">0</span>)</span>
						<span class="icon"></span>
					</div>
					<ul id="free_list" class="Student-list"></ul>
				</div>
				<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.divselect.js'); ?>"></script>
				<script>
					$(function() {
						$.divselect(".divselect cite");
					});
				</script>
				<!-- main -->
				<div id="main" class="player-box pos-rel">
					<div class="player-title fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->title; ?></div>
					<div class="box" id="box">
						<?php if(SlightPHP\Tpl::$_tpl_vars["plan_info"]->plan_status=="finished"){; ?>
						<div id="player"></div>
						<div id="player_2" class="tips-ready" style="display:none;"><img src="<?php echo utility_cdn::img('/assets_v2/img/ready.png'); ?>" alt=""></div>
						<?php }else{; ?>
						<div id="player" style="display:none;"></div>
						<div id="player_2" class="tips-ready"><img src="<?php echo utility_cdn::img('/assets_v2/img/ready.png'); ?>" alt=""></div>
						<?php }; ?>
					</div>
					<div class="handle-area" id="handle-area">
						<!-- 开始上课按钮 -->
                                                <?php if(SlightPHP\Tpl::$_tpl_vars["special_role"]=='mainTeacher'){; ?>
						<div class="h-a-start pos-rel">
							<div class="c pos-abs col-sm-7" id="start">
								<?php if(SlightPHP\Tpl::$_tpl_vars["plan_info"]->plan_status=="finished"){; ?>
								<button class="btn btn-red col-sm-15 fs16" id="continue-btn" onclick="start()">继续上课</button>
								<?php }else{; ?>
								<button class="btn btn-red col-sm-15 fs16" id="start-btn" onclick="start()">开始上课</button>
								<button class="btn btn-red col-sm-15 fs16" id="continue-btn" style="display:none;" onclick="start()">继续上课</button>
								<?php }; ?>
								<button class="btn btn-red col-sm-15 fs16" id="stop-btn" style="display:none;" onclick="stop2()">结束上课</button>
								<p class="tips cLightgray">必须点开始上课才有录播视频哦！</p>
							</div>
						</div>
                                                <?php }; ?>
						<div class="h-test pos-rel"><a id="microphone_all" href="#"><span class="icon m-phone-icon2"></span>集体测麦</a> <span>| </span><a id="call_all" href="#"><span class="icon call-icon"></span>集体点名</a></div>
						<div class="h-hall-test pos-rel"><a id="test" href="#" class="pos-abs"><span class="icon hall-test-icon"></span>随堂测试</a></div>
					</div>
				</div>
				<!-- right -->
				<div class="right-player" id="right-player">
					<div class="tab fs14">
						<span>讨论区</span>
					</div>
					<div class="chat-main" id="chat">
						<ul id="chat_list" data-student="act" style="overflow-y:auto; height:360px;"></ul>
						<a id="news" class="new-msg" data-action="toEnd" style="display: none;">
							<div class="angle"></div>
							<div class="chat-c"><span>99+</span>条新消息</div>
						</a>
					</div>
					<!-- 发表评论 -->
					<div class="tie-postform">
						<div class="tie-post-area">
							<div class="action-w">
								<div class="face-icon">
									<span><img id="show_pop" src="<?php echo utility_cdn::img('/assets_v2/img/icon2.png'); ?>" ></span>
									<div id="show" class='iconlist'>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/14.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/13.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/19.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/20.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/75.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/65.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/74.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/57.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/50.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/90.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/114.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/29.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/30.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/32.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/24.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/21.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/26.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/17.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/76.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/11.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/25.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/7.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/15.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/36.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/72.gif'); ?>"></a>
<?php /*
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/001.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/002.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/003.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/004.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/005.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/006.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/007.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/008.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/009.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/010.gif'); ?>"></a>

										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/012.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/016.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/018.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/022.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/023.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/027.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/027.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/031.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/033.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/034.gif'); ?>"></a>


										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/035.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/037.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/040.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/044.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/045.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/046.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/048.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/051.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/053.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/054.gif'); ?>"></a>

										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/055.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/056.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/059.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/061.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/063.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/064.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/066.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/070.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/071.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/073.gif'); ?>"></a>

										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/077.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/081.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/083.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/085.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/087.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/088.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/089.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/091.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/120.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/121.gif'); ?>"></a>

										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/125.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/129.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/150.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/170.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/171.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/172.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/173.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/174.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/175.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/176.gif'); ?>"></a>

										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/177.gif'); ?>"></a>
										<a><img show src="<?php echo utility_cdn::img('/assets/images/expression/178.gif'); ?>"></a>
*/?>
									</div>
								</div>
								<div id="pattern" class="gag" data-pattern="normal">
									<div data-pattern="normal">全部禁言</div>
									<div data-pattern="notalk" style="display:none;" class="cRed"><span class="gag-icon icon"></span><span class="c-fr">全部禁言</span></div>
								</div>
								<div id="pattern2" class="visual" data-pattern="normal">
									<div data-pattern="normal">仅老师可见</div>
									<div data-pattern="reply" style="display:none;" class="cRed"><span class="visual-icon icon"></span><span class="c-fr">仅老师可见</span></div>
								</div>
							</div>
							<div class="tie-textarea fs14 cgray">
								<div id="chat_input" contenteditable="true"></div>
							</div>
							<div class="action-issue">
								<button id="chat_send" title="按Enter键发送消息">发送</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</body>
</html>
<script src="<?php echo utility_cdn::js('/assets_v2/js/course_play_resize.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.gray.min.js'); ?>"></script>
<script>
$(document).ready(function(){
    //灰色头像调用
    $(".gray img").gray();
	playResize();
});
$(window).resize(function(){
    playResize();
});
</script>
