$(document).ready(function(e){
	var _addURL = "/comment.course.addcommentscore";
	var _getAscURL = "/comment.course.getcomments";
	var _getDescURL = "/comment.course.getcommentsdesc";
	var _input = $("#comment_input");
	var _score_1 = $("#score_1");
	var _score_all = $("#score_all");
	var _comment = $("#comment");
	var _comment_self = $("#comment_self");
	var _comment_list = [];
	var _comment_num = 0;
	var _leftButton = $("#comment_prev");
	var _rightButton = $("#comment_next");
	function setComment(){
		_comment.empty();
		for(i in _comment_list){
			var o = _comment_list[i];
			var t = String(o.last);
			_comment.append('<div class="courseComment clear"><div class="fl ccPhoto"><span class="clear"><img src="'+filecdn+o.user_thumb+'" width="40" height="40"  alt=""/></span><br /><span class="mt5 disblk fcg7 clear">'+o.user_name+'</span></div><div class="fl col-lg-8 mt5"><p class="fs14 lh22 clear">'+o.comment+'</p><p class="mt15 fcg9">已学完［第二节］</p></div><div class="fr mt10 mr30 fcg9">'+t.substr(0, t.indexOf(" "))+'</div></div>');
		}
	}
	function setScore(elem, score){
		var o = elem.find("[score="+score+"]");
		o.addClass("active-star");
		elem.attr("score", score);
		elem.attr("title", o.attr("title"));
		elem.find(".s_result").css('color','#ff711b').html(o.attr('title'));
	}
	function setAvgScore(extra){
		var t = extra;
		_score_1.find("[score_type]").each(function(i, elem){t+=parseInt($(this).attr("score"));});
		t /= 4;
		$("#avg_1").text("课程评价("+t.toFixed(1)+")");
	}
	function init(){
		_input.width("99%");
		if(commentInfo["comment"] && commentInfo["user"]){
			var o = commentInfo["comment"][0];
			var u = commentInfo["user"];
			_input.html(o["comment"]);
			_comment_self.find("img[thumb]").attr("src", u.small);
			_comment_self.find("span[username]").text(u.name);
			_comment_self.find("p[comment]").html(o.comment);
			var t = String(o.last);
			_comment_self.find("div[time]").text(t.substr(0, t.indexOf(" ")));
			_score_1.parent().hide();
		}else{
			_comment_self.hide();
			if(!commentInfo["user"]){
				_score_1.parent().hide();
			}
		}
		if(commentInfo["detail"].length){
			var o = commentInfo["detail"][0];
			setScore(_score_1.find("[score_type=desc_score]"), o.desc_score);
			setScore(_score_1.find("[score_type=explain_score]"), o.explain_score);
			setScore(_score_1.find("[score_type=service_score]"), o.service_score);
			setScore(_score_1.find("[score_type=student_score]"), o.student_score);
			setAvgScore(0);
		}
		if(commentInfo["total"].length){
			var o = commentInfo["total"][0];
			setScore(_score_all.find("[score_type=desc_score]"), parseInt(o.desc_score/o.total_user+0.5));
			setScore(_score_all.find("[score_type=explain_score]"), parseInt(o.explain_score/o.total_user+0.5));
			setScore(_score_all.find("[score_type=service_score]"), parseInt(o.service_score/o.total_user+0.5));
			setScore(_score_all.find("[score_type=student_score]"), parseInt(o.student_score/o.total_user+0.5));
			$("#score_avg").text(parseFloat(o.avg_score/o.total_user).toFixed(1));
			setScore($("#total_avg"), parseInt(o.avg_score/o.total_user+0.5));
		}
		if(commentInfo["comments"]){
			_comment_list = commentInfo["comments"];
			_comment_num = _comment_list.length
			setComment();
		}
		if(commentInfo["total_num"]){
			if(commentInfo["comment"]){
				$("#comment_total").text("学员评论("+(parseInt(commentInfo.total_num)+1)+")");
			}else{
				$("#comment_total").text("学员评论("+commentInfo.total_num+")");
			}
			if(commentInfo.total_num > 0 && commentInfo.total_num > _comment_num){
				_rightButton.removeClass("gray");
			}
		}else if(commentInfo["comment"]){
			$("#comment_total").text("学员评论(1)");
		}
	}
	init();
	function getScore(kind){
		return _score_1.find("[score_type="+kind+"]").attr("score");
	}
	function addComment(e){ console.log("click...\n");
		e.preventDefault();
		var t = _input.val().trim();
		if(!t){
			console.log("no input!\n");
			alert("评论不能为空！");
			return;
		}
		var request = {};
		request["student_score"] = getScore("student_score");
		request["desc_score"] = getScore("desc_score");
		request["explain_score"] = getScore("explain_score");
		request["service_score"] = getScore("service_score");console.log("post url=["+_addURL+"]");
		for(i in request){
			if(0 == request[i]){
				alert(_score_1.find("[score_type="+i+"]").prev().text()+"还没有打分呢！");
				return;
			}
		}
		request["comment"] = t;
		request["course_id"] = commentInfo["cid"];
		$("#comment_send").val("评论中...");
		$.post(_addURL, request, function(){location.reload()});
	}
	$("#comment_send").click(addComment);
	_input.keypress(function(e){
		if((10 == e.keyCode || 13 == e.keyCode) && e.ctrlKey){
			addComment();
		}
	});
	_comment_self.find("a").click(function(e){
		_score_1.parent().toggle("display");
	});
	
	function ascOk(data){ pyy = data;
		_comment_num -= _comment_list.length;
		_comment_list = data;
		if(_comment_num <= _comment_list.length){
			_leftButton.addClass("gray");
		}
		_rightButton.removeClass("gray");
		setComment();
	}
	_leftButton.click(function(e){
		if(_comment_num <= _comment_list.length){
			console.log("cannot click in prev");
			return;
		}
		var request = {"course_id":commentInfo["cid"], "num":commentInfo["limit"], "start":parseInt(_comment_list[0].comment_id)+1};
		$.post(_getAscURL, request, ascOk, "json");
	});
	function descOk(data){ pyy = data;
		_comment_num += data.length;
		_comment_list = data;
		_leftButton.removeClass("gray");
		if(_comment_num >= commentInfo.total_num){
			_rightButton.addClass("gray");
		}
		setComment();
	}
	_rightButton.click(function(e){
		if(_comment_num >= commentInfo.total_num){
			console.log("cannot click in next");
			return;
		}
		var request = {"course_id":commentInfo["cid"], "num":commentInfo["limit"], "max":_comment_list[_comment_list.length-1].comment_id};
		$.post(_getDescURL, request, descOk, "json");
	});
//<!--星级评价	
$(function(){
	
	_score_1.find('.star_ul a').hover(function(){
		var p = $(this).parent().closest("[score]");
		p.find("[score]").removeClass("active-star");
		$(this).addClass('active-star');
		p.find(".s_result").css('color','#ff711b').html($(this).attr('title'));
		setAvgScore($(this).attr("score")-p.attr("score"));
	},function(){
		$(this).removeClass('active-star');
		var p = $(this).parent().closest("[score]");
		p.find('.s_result').css('color','#ff711b').html(p.attr("title"));
		p.find("[score="+p.attr("score")+"]").addClass("active-star");
		setAvgScore(0);
	});
	
	_score_1.find('.star_ul a').click(function(e){
		e.preventDefault();
		var p = $(this).parent().closest("[score]");
		p.attr("score", $(this).attr("score"));
		p.attr("title", $(this).attr("title"));
		$(this).addClass('active-star');
	});
	
	/*$('.square_ul a').hover(function(){
		$(this).addClass('active-square');
		$(this).parents('.starbox').find('.s_result_square').css('color','#c00').html($(this).attr('title'))
	},function(){
		$(this).removeClass('active-square');
		$(this).parents('.starbox').find('.s_result_square').css('color','#333').html('&nbsp;')
	});
	
	$('.square_ul a').click(function(){
		alert($(this).parents('.starbox').find('.s_result_square').html());
	});*/
})
});
