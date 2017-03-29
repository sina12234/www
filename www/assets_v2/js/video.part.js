var _add_part_btn = $('#add-part');
var _add_point_btn = $('#add-point');
var _partlist = $('#part-list');
var _pointlist = $('#pointlist');
var _add_morepart_btn = $('#add-morepart');
var _add_morepoint_btn = $('#add-morepoint');
var _no_part=$('.no-part');
var planId,pathname,pArr,vvid=0;
var totalTime=0;
var vvid=0;
var timeLine=$('#timeLine');
var VideoSegdata='';
var RegDataTime=/^[0-9][0-9]:[0-5][0-9]:[0-5][0-9]$/;
var timeLineWidth=timeLine.find('.ov-timeline-base').width();
var leftHandle=$('#left-handle');
var rightHandle=$('#right-handle');

function viodInit(){

	pathname = location.pathname;
	pArr=getPlanid(pathname);
	planId = pArr[pArr.length-2];
	$('#tab-hd>a').each(function(i){
		$(this).attr('href',$(this).attr('href')+pArr[pArr.length-2]+'/'+pArr[pArr.length-1]);
	});
	$('#video-upload').attr('href',$('#video-upload').attr('href')+pArr[pArr.length-2]+'/'+pArr[pArr.length-1]);
	$.ajax('/video.ajax.GetNameByPidAjax/'+planId+'',{
		dataType:'json',
		type:'post',
		async:false,
		data:{
			pid: planId,
		},
		success:function (data) {
			if(data.code==0){
				vvid = data.data.videoId;
				totalTime = data.data.totalTime;
				$('#course-name').html(data.data.courseName+' '+data.data.className+' '+data.data.planName);
			};
		},
		error:function () {
			layer.msg('网络君开小差了!');
		}
	});

	$.ajax('/video.ajax.GetVideoSegAjax/'+planId+'',{
		dataType:'json',
		type:'post',
		data:{
			vid: vvid,
		},
		success:function (data) {
			if(data.code==0){
				VideoSegdata=data.data;
				timeLineFun(totalTime);
			}
		},
		error:function () {
			layer.msg('网络君开小差了!');
		}
	});
};

function addPointFun(){
	// 添加片段
	_add_point_btn.click(function(){
		addPoint();
	});
	_add_morepoint_btn.click(function(){
		addPoint();
	});
}

function addPartFun(){
	// 添加片段
	_add_part_btn.click(function(){
		addPart();
	});
	_add_morepart_btn.click(function(){
		addPart();
	});
}
// 添加片段
function addPart(){
	var _add_part_html=$('<li><b>片段1：</b><input type="text" class="part-time" value="00:00:00" data-v="0">-<input type="text" class="part-time" value="00:00:10" data-v="0"><br><a href="javascript:void(0);" class="gray-btn c-fr mt5">取消</a><a href="javascript:void(0);" class="btn c-fr mt5 mr5">确定</a></li>');
	var _num=_partlist.find('li').length+1;

	if(_partlist.find('.part-time').length > 0){
		_partlist.find('.part-time').css('border-color','red');
		layer.msg('还有未保存的剪辑！');
		return false;
	};
	if(_num == 1){
		_add_part_html.attr('data-id',1);
		_partlist.append(_add_part_html);
		_no_part.hide();
		_partlist.siblings('.ov-partlist-btn').show();
		_add_part_html.find('.part-time:eq(1)').val(formatSeconds(totalTime));
		timeLine.find('.ov-timeline-base').find('p').remove();
		var _left=0,
			_width=timeLineWidth*(formatTimes(_partlist.find('.part-time:eq(1)').val())-formatTimes(_partlist.find('.part-time:eq(0)').val()))/totalTime;
		handleShow(_left,_width,1);
		timeLine.find('.ov-timeline-base').append('<p class="ov-timeline-bar" data-id="'+_num+'" style="z-index:3;left:'+_left+'px;width:'+_width+'px">P1</p>');
	}else if(_num >= 2 && _num <11){
		var lastPartTime=formatTimes(_partlist.find('li:last').find('span').text().split('-')[1])+1;
		if(lastPartTime>totalTime){
			layer.msg('已经剪辑完了！');
			return false;
		}
		_add_part_html.find('.part-time:eq(0)').val(formatSeconds(lastPartTime));
		_add_part_html.find('.part-time:eq(1)').val(formatSeconds(totalTime));

		_add_part_html.find('b').html('片段'+_num+'：');
		_add_part_html.attr('data-id',_num);
		var _left=timeLineWidth*lastPartTime/totalTime,
			_width=timeLineWidth*(totalTime-lastPartTime)/totalTime;
		handleShow(_left,_width,_num);
		_partlist.append(_add_part_html);
		timeLine.find('.ov-timeline-base').append('<p data-id="'+_num+'" class="ov-timeline-bar" style="z-index:3;left:'+_left+'px;width:'+_width+'px">P'+_num+'</p>');
	}else if(_num >11){
		layer.msg('最多只能添加10个');
		return false;
	};
};

function handleShow(left,width,dataid){
	timeLine.find('.ov-timeline-opacity,.ov-timeline-handle').show();
	leftHandle.css({"left":left-6}).attr('data-id',dataid);
	rightHandle.css({"left":width+left-6}).attr('data-id',dataid);
	timeLine.find('.ov-timeline-bar[data-id!='+dataid+']').css({"z-index":0});
	timeLine.find('.ov-timeline-bar[data-id ='+dataid+']').css({"z-index":3});
}
function handleHide(){
	timeLine.find('.ov-timeline-opacity,.ov-timeline-handle').hide();
}

var dragging = false;
var iX,lX,rX;
$("#left-handle,#right-handle").mousedown(function(e) {
	dragging = true;
	iX = e.clientX - this.offsetLeft;
	this.setCapture && this.setCapture();
	handleId=$(this).attr('id');
	return false;
});
document.onmousemove = function(e) {
	lX='';rX='';
	if (dragging) {
		var e = e || window.event;
		var oX = e.clientX - iX;
		if(oX > 0 && oX < timeLineWidth){
			$(".ov-timeline-handle[id="+handleId+"]").css({"left":oX + "px"});
		}else if(oX <=0){
			$(".ov-timeline-handle[id="+handleId+"]").css({"left":"-5px"});
			leftTime=0;
		}else if(oX >=timeLineWidth){
			rightTime=totalTime;
			$(".ov-timeline-handle[id="+handleId+"]").css({"left":timeLineWidth-5+"px"});
		}
		lX=parseInt(leftHandle.css('left'));
		rX=parseInt(rightHandle.css('left'));
		if(lX <= rX){
			leftTime= oX<=0 ? 0:parseInt(totalTime)*(lX+10)/timeLineWidth;
			rightTime= oX >=timeLineWidth ?totalTime:totalTime*(rX+6)/timeLineWidth;
			_partlist.find('.part-time:eq(0)').val(formatSeconds(leftTime));
			_partlist.find('.part-time:eq(1)').val(formatSeconds(rightTime));
			timeLine.find('.ov-timeline-bar[data-id="'+leftHandle.attr('data-id')+'"]').css({"left":lX+6+'px',"width":rX-lX+'px'});
		}else{
			leftTime=oX<=0 ? 0:parseInt(totalTime)*(rX+10)/timeLineWidth;
			rightTime=oX >=timeLineWidth ?totalTime:parseInt(totalTime)*(lX+6)/timeLineWidth;
			_partlist.find('.part-time:eq(0)').val(formatSeconds(leftTime));
			_partlist.find('.part-time:eq(1)').val(formatSeconds(rightTime));
			timeLine.find('.ov-timeline-bar[data-id="'+leftHandle.attr('data-id')+'"]').css({"left":rX+6+'px',"width":lX-rX+'px'});
		}

		return false;
	}
};
$(document).mouseup(function(e) {
	dragging = false;
	e.cancelBubble = true;
})

// 添加点评
function addPoint(){
	var _add_point_html=$('<li><p class="mb5"><span class="point-time">00:00:00</span></p><input type="text" maxlength="20" class="point-input" value="" onkeydown="javascript:if (event.keyCode==13){ savePoint(); }"><a href="javascript:void(0);" class="gray-btn c-fr mt5">取消</a><a href="javascript:void(0);" class="btn c-fr mt5 mr5">确定</a></li>');
	var _num=_pointlist.find('li').length+1;
	if(_pointlist.find('input').length > 0){
		_pointlist.find('input').css('border-color','red');
		layer.msg('还有未保存的剪辑！');
		return false;
	};
	// console.log(getInfo());
	if (getInfo() == 0) {
		layer.msg('视频未开始，不能添加注释。');
		return false;
	}
	if(_num == 1){
		timeLine.find('.ov-timeline-base').append('<i data-id="'+_num+'" class="ov-timeline-currpoint" style="left:'+getInfo()/totalTime*100+'%"></i>');
		_add_point_html.find('.point-time').html(formatSeconds(getInfo()));
		_pointlist.find('ul').append(_add_point_html);
		_no_part.hide();
		_pointlist.find('.ov-pointlist-btn').show();
		_pointlist.find('.point-input').focus();

	}else if(_num >= 1 && _num < 21){
		_add_point_html.find('.point-time').html(formatSeconds(getInfo()));
		_add_point_html.attr('data-id',_num);
		timeLine.find('.ov-timeline-base').append('<i data-id="'+_num+'" class="ov-timeline-currpoint" style="left:'+getInfo()/totalTime*100+'%"></i>');
		_pointlist.find('ul').append(_add_point_html);
		_pointlist.find('.ov-pointlist-btn').hide();
		_pointlist.find('.point-input').focus();
	}else if(_num >21){
		layer.msg('最多只能添加20个');
		_pointlist.find('.ov-pointlist-btn').hide();
		return false;
	};
};

function getPlanid(s){
	var re = new RegExp("\/","g");
	var arr = s.match(re);
	if(arr.length < 3){
		return s.split('.');
	}else{
		return s.split('/');
	}
};
function timeLineFun(totalTime){
	var lihtml=0;
	var timehtml=0;
	var currTime;
	if(totalTime % 9==0){
		currTime=Math.floor(totalTime/9);
		for(i=0;i<9;i++){
			if(lihtml==0){
				lihtml = '<li style="width:'+Math.floor(timeLineWidth/9)+'px"></li>';
			}else{
				lihtml = lihtml+'<li style="width:'+Math.floor(timeLineWidth/9)+'px"></li>';
			}
		};
		for(i=0;i<10;i++){
			if(i==0){
				timehtml = '<li style="width:'+Math.floor(timeLineWidth/9)+'px">00:00:00</li>';
			}else if(i==9){
				timehtml = timehtml+'<li style="width:'+Math.floor(timeLineWidth/9)+'px">'+formatSeconds(totalTime)+'</li>';
			}else{
				timehtml = timehtml+'<li style="width:'+Math.floor(timeLineWidth/9)+'px">'+formatSeconds(currTime*i)+'</li>';
			}
		}
	}else{
		currTime=Math.floor(totalTime/9);
		for(i=0;i<9;i++){
			if(lihtml==0){
				lihtml = '<li style="width:'+Math.floor(timeLineWidth/9)+'px"></li>';
			}else{
				lihtml = lihtml+'<li style="width:'+Math.floor(timeLineWidth/9)+'px"></li>';
			}
		};
		for(i=0;i<10;i++){
			if(i==0){
				timehtml = '<li style="width:'+Math.floor(timeLineWidth/9)+'px">00:00:00</li>';
			}else if(i==9){
				timehtml = timehtml+'<li style="width:'+Math.floor(timeLineWidth/9)+'px">'+formatSeconds(totalTime)+'</li>';
			}else{
				timehtml = timehtml+'<li style="width:'+Math.floor(timeLineWidth/9)+'px">'+formatSeconds(currTime*i)+'</li>';
			}
		};
	}
	timeLine.find('ul.ov-timeline-scale').append(lihtml);
	timeLine.find('ul.ov-timeline-point').append(timehtml);

	var timelineBar='';
	if(VideoSegdata==''){
		timelineBar='<p class="ov-timeline-bar" style="width:100%;"></p>';
	}else{
		for(var i=0;i<VideoSegdata.length;i++){
			timelineBar +='<p data-id="'+(i+1)+'" class="ov-timeline-bar" style="width:'+timeLineWidth*(VideoSegdata[i][1]-VideoSegdata[i][0])/totalTime+'px;left:'+timeLineWidth*(VideoSegdata[i][0])/totalTime+'px">P'+(i+1)+'</p>';
		}
	}
	timeLine.find('.ov-timeline-base').append(timelineBar);

}

function formatTimes(t){
	var items = t.split(":");
	if(3 == items.length){
		return parseInt(items[0])*3600 + parseInt(items[1])*60 + parseInt(items[2]);
	}else if(2 == items.length){
		return parseInt(items[0])*60 + parseInt(items[1]);
	}else if(1 == items.length){
		return parseInt(items[0]);
	}else{
		return 0;
	}
}
function formatSeconds(value) {
    var theTime = parseInt(value);// 秒
    var theTime1 = 0;// 分
    var theTime2 = 0;// 小时
	if(isNaN(theTime)){
		theTime=0;
	}
    if(theTime >= 60) {
        theTime1 = parseInt(theTime/60);
        theTime = parseInt(theTime%60);
        if(theTime1 >= 60) {
            theTime2 = parseInt(theTime1/60);
            theTime1 = parseInt(theTime1%60);
        }
    }
    var result = ""+parseInt(theTime);
	if(result>=0 && result<10){
		result = "0"+result;
	}
    if(theTime1 > 0 && theTime1<10) {
        result = "0"+parseInt(theTime1)+":"+result;
    }else if(theTime1 > 9){
		result = parseInt(theTime1)+":"+result;
	}else{
		result = "00:"+result;
	}
    if(theTime2 >= 0  && theTime2<10) {
        result = "0"+parseInt(theTime2)+":"+result;
	}else if(theTime1 > 9){
		result = parseInt(theTime2)+":"+result;
    }else{
		result = "00:"+result;
	}
    return result;
}
function getInfo(){
	var currTime,info;
	info=Player.info() || 0;
	Player.pause();
	currTime=info.currentTime.toString();
	currTime=currTime.substring(0,currTime.indexOf('.'));
	return currTime;
}
