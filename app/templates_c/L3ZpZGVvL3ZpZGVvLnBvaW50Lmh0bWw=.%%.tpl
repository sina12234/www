<!DOCTYPE html>
<html>
<head>
<title>视频打点-<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 首页 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/swiper.min.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/player.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/HTML5Player.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/swfobject.js'); ?>"></script>
<body>
<?php echo tpl_function_part("/site.main.usernav.home"); ?>
<section class="pd30">
	<div class="container">
		<div class="col-md-20 main-center">
			<p class="fb fs14" style="padding-left:18px">视频管理</p>
			<p style="padding-left:18px" id="course-name">课程名称</p>
			<!-- tab -->
			<div class="tab-main mb30">
                <div class="tab-hd fs14" id="tab-hd">
                    <a href="/video.point.VideoPart/" class="tab-hd-opt">内容剪辑</a>
                    <a href="/video.point.VideoPoint/" class="tab-hd-opt curr">打点注释</a>
                    <a href="/video.point.videocover/" class="tab-hd-opt">视频封面</a>
                </div>
				<a href="/video.point.videoUpload/" class="gray-btn fs14 c-fr" id="video-upload">重新上传</a>
            </div>
			<!-- 视频播放区 -->
            <div class="org-video-player col-md-15"  id="playerContent">
                <div id="player">视频播放区</div>
            </div>
			<!-- 剪辑列表 -->
            <div class="org-video-pointlist col-md-5" id="pointlist">
                <div class="ov-tit fs14 tac">视频注释</div>
				<div class="ov-pointlist-content">
					<!-- 没有数据 -->
					<div class="no-part tac">
						还没视频注释！
						<a href="javascript:void(0);" class="btn mt20" id="add-point">添加注释</a>
					</div>
					<!-- 剪辑列表 -->
					<ul class="ov-pointlist" id="part-list"></ul>
					<div class="ov-pointlist-btn tac p10">
						<p class="tal mb10">
							<a href="javascript:void(0);" class="theme-link" id="add-morepoint">+继续注释</a><span class="cGray">(最多保存20条注释内容)</span>
						</p>
					</div>

				</div>
            </div>
			<!-- 时间轴 -->
			<div class="org-video-timeline col-md-15 pd0" id="timeLine">
				<p>编辑时间轴</p>
				<div class="ov-timeline-base">

				</div>
				<ul class="ov-timeline-scale">
				</ul>
				<ul class="ov-timeline-point">
				</ul>
			</div>
		</div>
	</div>
</section>
<script type="template" id="pointlist-html">
	<% if(count != 0){ var currpoint='',key=1; %>
		<%result.forEach(function(item){ %>
		<% currpoint +='<i data-id="'+key+'" class="ov-timeline-currpoint" style="left:'+item.ptime/totalTime*100+'%"></i>'%>
		<li data-id="<%=key%>">
		<p class="mb5"><span class="point-time"><%=formatSeconds(item.ptime)%></span> <% if(item.pointStatus == 1){ %>被剪去不显示<% };%><i class="del-icon c-fr ml5"></i></p>
		<p class="point-info <% if(item.pointStatus == 1){ %>cGray<% };%>"><%=item.content%></p>
		<%if(item.thumbs==''){ %>
		<p class="c-fr point-img"><img src="/assets_v2/img/video-img.png" alt=""></p>
		<%}else{ %>
		<p class="c-fr point-img" style="background:url(<%=item.thumbs.thumbs%>);background-size:1000px;background-position:-<%= Math.round(item.thumbs.left*.52)%>px -<%= Math.round(item.thumbs.top*.52) %>px"></p>
		<%};%>
		</li>
		<% key++;});$('.ov-timeline-base').find('i').remove();$('.ov-timeline-base').append(currpoint);%>
	<% }; %>
</script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/ejs.min.js'); ?>"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="/assets_v2/js/ie8/ejs.ie8.js"></script>
<![endif]-->
<script src="<?php echo utility_cdn::js('/assets_v2/js/video.part.js'); ?>"></script>
<script>
var pointlistHtml=$('#pointlist-html').html();
var timeLines=$('#timeLine');
var currpoint='';
var pointlist=$('#pointlist').find('ul');
function savePoint(){
	var ptime,pcontent,_self;
	_self=pointlist.find('.point-input');
	ptime=formatTimes(_self.siblings('p').text());
	pcontent=_self.val();
	if($.trim(pcontent)==''){
		layer.msg('注释不能为空！');
		_this.siblings('input').css('border','1px solid red');
		return false;
	}else{
		$.ajax('/video.ajax.saveteacherpointajax/'+planId+'',{
			type:'post',
			data:{
				pid: planId,vid:vvid,content:pcontent,ptime:ptime,pseg:''
			},
			success:function (data) {
				data=JSON.parse(data);
				if(data.code==0){
					layer.msg('添加成功！');
					get();
					Player.play();
				}else if(data.code==5003){
					layer.msg(data.zh_msg);
				};
			}
		});
	}
}

function get(){
	$.ajax('/video.ajax.GetTeacherPointAjax/'+planId+'',{
		dataType:'json',
		type:'post',
		data:{
			pid: planId,
		},
		success:function (data) {
			if(data.code==0){
				if(data.count != 0){
					pointlist.html(ejs.render(pointlistHtml,{ count:data.count,result:data.result }));
					if(data.count >=20){
						$('#pointlist').find('.ov-pointlist-btn').hide();
					}else{
						$('#pointlist').find('.ov-pointlist-btn').show();
					};
					$('#pointlist').find('.no-part').hide();
					result=data.result;
					for(i=0;i<result.length;i++){
						currpoint +='<i class="ov-timeline-currpoint" style="left:'+result[i]+'%"></i>';
					}
				}else{
					$('#pointlist').find('.no-part').show();
					$('#pointlist').find('.ov-pointlist-btn').hide();
					pointlist.empty();
				}
			};
		},
		error:function () {
			layer.msg('网络君开小差了!');
		}
	});
}
$(document).ready(function(){
	viodInit();
	$(window).resize(function(){
		var w = $("#playerContent").innerWidth();
		var h = parseInt(w/16*9);
		var box_h=$(window).height()-120;
		if(h>box_h){
			h=box_h;
		}
		$("#playerContent").height(h);
		$('#pointlist .ov-pointlist-content').css('height',h-30);
	}).trigger("resize");
	//fix ie8 trigger (twice)
	setTimeout(function(){ $(window).trigger("resize");},50);
	$("#fullscreen").click(function(){
		FullScreen.toggle();
	});

	var flashvars = {
		auto_play:"true",
		stream_type:"高清",
		fullscreen_func:"FullScreen.toggle",
		error_func:"Player.error",
		plan_id:""+planId+"",
		direct:"false",
		original:"true",
		uiAnimation:"false"
	};
		var params = {
			menu: "true",
			scale: "noScale",
			allowFullscreen: "true",
			allowScriptAccess: "always",
			bgcolor: "",
			wmode:"opaque",

		};
		var attributes = {
			id:"player",
			name:"player"
		};
		swfobject.embedSWF(
			"<?php echo utility_cdn::swf('/assets/swf/Player.swf'); ?>",
			"player", "100%", "100%", "13.0.0",
			"<?php echo utility_cdn::swf('/assets/swf/expressInstall.swf'); ?>",
			flashvars, params, attributes,function(r){
				if(r.success==false){
					H5Player.init(planId,"");
				}
		});

		get();
		addPointFun();
		// VideoSeg(vvid);
		pointlist.on('click','.btn',function(){
			savePoint();
		});

		pointlist.on('click','.del-icon',function(){
			var ptime,pcontent;
			_this=$(this);
			ptime=formatTimes(_this.siblings('.point-time').text());
			$.ajax('/video.ajax.delteacherpointajax/',{
				type:'post',
				data:{
					pid: planId,vid:vvid,ptime:ptime
				},
				success:function (data) {
					data=JSON.parse(data);
					if(data.code==0){
						get();
						layer.msg('已经删除');
					};
				}
			});
		});
		pointlist.on('click','.gray-btn',function(){
			_this=$(this);
			_this.parent('li').remove();
			timeLines.find('i[data-id="'+_this.parent().attr('data-id')+'"]').remove();
			if($('#pointlist').find('li').length==0){
				$('#pointlist').find('.ov-pointlist-btn').hide();
				$('#pointlist').find('.no-part').show();
			}else{
				$('#pointlist').find('.ov-pointlist-btn').show();
			};
			Player.play();
		});
		timeLines.on('mouseover mouseout','i.ov-timeline-currpoint',function(event){
			var _self=$(this),
				_selfId=$(this).attr('data-id'),
				_tips=pointlist.find('li[data-id='+_selfId+']').find('.point-info ').text();
			if(event.type == "mouseover"){
				layer.tips(_tips,_self, {
					tips: [1,'#333'],
				});
			}else if(event.type == "mouseout"){

			}
		})

});
</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
