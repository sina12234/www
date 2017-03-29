/**
 * app/templates/course/plan.play.v2.html 播放页已经不再引用此文件
 * 引用的此文件被合并到了 /assets_v2/js/player/module.js
 * 如有修改 请同时修改 /assets_v2/js/player/module.js 的部分
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
$(document).ready(function(e){
	var _addURL = "/comment/course/addscore";
	var _checkURL = "/comment/course/checkIsAddScore";
	var _getAscURL = "/comment.course.getcomments";
	var _getDescURL = "/comment.course.getcommentsdesc";
	var _input = $("#comment_input");
	var _input1 = $("#comment_input1");
	var _score_1 = $("#score_comment");
	var _score_11 = $("#score_comment1");
	var _score_all = $("#score_all");
	var _comment = $("#comment");
	var _comment_self = $("#comment_self");
	var _comment_list = [];
	var _comment_num = 0;
	var _leftButton = $("#comment_prev");
	var _rightButton = $("#comment_next");
	// function setAvgScore(extra){
	// 	var t = extra;
	// 	_score_1.find(".percent>dl>dd>i").each(function(i, elem){
	// 	t+=parseInt($(this).attr("data-score"));
	// 	//console.log(t);
	// 	});
	// 	t /= 3;
	// 	$("#avg_1").text(t.toFixed(1));
	// }
	// function setAvgScore1(extra){
	// 	var t = extra;
	// 	_score_11.find(".percent>dl>dd>i").each(function(i, elem){
	// 	t+=parseInt($(this).attr("data-score"));
	// 	//console.log(t);
	// 	});
	// 	t /= 3;
	// 	$("#avg_11").text(t.toFixed(1));
	// }
	function getScore(kind){
		return _score_1.find("[score_type="+kind+"]").attr("data-score");
	}
	function getScore1(kind){
		return _score_11.find("[score_type="+kind+"]").attr("data-score");
	}
	function addComment(e){ 
		e.preventDefault();
		var t = $.trim(_input.text());
		//t = t.replace(/<.*?>/g, "");
		//t = t.replace(/^((&nbsp;)|( )|(\u3000))*/g, "");
		//t = t.replace(/((&nbsp;)|( )|(\u3000))*$/g, "");
		if(!t){
			alert("评论不能为空！");
			return;
		}
		if(t.length<5){
			layer.msg("评论不能少于4个字哦");
			return;
		}
		var request = {};
		request["score"] = getScore("avg_score");
		request["user_teacher"] = teacherId;
		request["plan_id"] = planId;
		request["user_owner"] = userOwner;
		for(i in request){
			if(0 == request[i]){
				alert(_score_1.find("[score_type="+i+"]").prev().text()+"还没有打分呢！");
				return;
			}
		}
		request["comment"] = t;
		request["course_id"] = courseId;
		$("#comment_send").val("评论中...");
		var commentSuc = function(r){
			res = JSON.parse(r);
			if (res.code == 1) {
				var result = res.result;
				var data = res.result.data;
				setComment(data.score,data.comment,result.section,result.time);
				var sign_add = "<span id='sign_add' class='sign-add'></span>";
				$("#tool").append(sign_add);
				$("#sign_add").html('<img src="/assets_v2/img/exp-icon.png">+' + res.result.addScore);
				$("#sign_add").show(function () {
					$("#sign_add").animate({top: "40%", opactiy: "1"}, function () {
						$("#sign_add").hide().animate({top: "35%"});
					});
				});

				if (res.result.upType) {
					var types = '';
					if (res.result.upType == 2) {
						types = 'biggrowth';
					}

					if (res.result.upType == 1) {
						types = 'smallgrowth';
					}

					$("body").GrowthLayer({
						types: types,// five|smallgrowth|biggrowth
						space: 5000, //时间间隔
						auto: true, //自动关闭
						growth: res.result.userLevel,// 等级,1,2,3
						score: res.result.currentScore
					});
				}
				layer.msg("感谢评价");
				return false;
			}else if(res.code == 2043){
				layer.msg("已经评价");
				checkUserIsComment('living');
				return false;
			}
		}
		$.post(_addURL, request, commentSuc);
	}
	function addComment1(e){
		e.preventDefault();
		var t = $.trim(_input1.html());
		//t = t.replace(/<.*?>/g, "");
		//t = t.replace(/^((&nbsp;)|( )|(\u3000))*/g, "");
		//t = t.replace(/((&nbsp;)|( )|(\u3000))*$/g, "");
		if(!t){
			layer.msg("评论不能为空！");
			return;
		}
		if(t.length<5){
			layer.msg("评论不能少于4个字哦");
			return;
		}
		var request = {};
		request["score"] = getScore1("avg_score");
		request["user_teacher"] = teacherId;
		request["plan_id"] = planId;
		request["user_owner"] = userOwner;
		var avg = $("#avg_11").text();
		for(i in request){
			if(0 == request[i]){
				alert(_score_11.find("[score_type="+i+"]").prev().text()+"还没有打分呢！");
				return;
			}
		}
		request["comment"] = t;
		request["course_id"] = courseId;
		$("#comment_send").val("评论中...");
		var commentSuc = function(r){
			res = JSON.parse(r);
			if (res.code == 1) {
				var result = res.result;
				var data = res.result.data;
				setComment1(data.score,data.comment,result.section,result.time);
				var sign_add="<span id='sign_add' class='sign-add'></span>";
				$("#tool").append(sign_add);
				$("#sign_add").html('<img src="/assets_v2/img/exp-icon.png">+' + res.result.addScore);
				$("#sign_add").show(function(){

                    $("#sign_add").animate({ top:"40%",opactiy:"1" },function(){
                        $("#sign_add").hide().animate({ top:"35%" });
                    });
                });
				if (res.result.upType) {
					var types = '';
					if (res.result.upType == 2) {
						types = 'biggrowth';
					}

					if (res.result.upType == 1) {
						types = 'smallgrowth';
					}
					$("body").GrowthLayer({
						types: types,// five|smallgrowth|biggrowth
						space: 5000, //时间间隔
						auto: true, //自动关闭
						growth: res.result.userLevel,// 等级,1,2,3
						score: res.result.currentScore
					});
				}
				layer.msg("感谢评价");
				return false;
			}else if(res.code == 2043){
				layer.msg("已经评价");
				checkUserIsComment('finished');
				return false;
			}
		}
		$.post(_addURL, request, commentSuc);
	}
	$("#comment_send1").click(addComment1);
	_input1.keypress(function(e){
		if((10 == e.keycode || 13 == e.keycode) && e.ctrlkey){
			addComment1();
		}
	});
	$("#comment_send").click(addComment);
	_input1.keypress(function(e){
		if((10 == e.keycode || 13 == e.keycode) && e.ctrlkey){
			addComment();
		}
	});

//<!--星级评价
$(function(){

$("#score_percent>dl>dd>span").click(function() {
    $(this).css("background-position", "-4px -33px");
    $(this).prevAll().css("background-position", " -4px -33px");
    $(this).nextAll().css("background-position", " -4px -3px");
    $(this).parent().find('i').html($(this).attr('data-title'));
    $(this).parent().find('i').attr("data-score",$(this).attr('data-score'));
    // setAvgScore(0);
});
$("#score_percent1>dl>dd>span").click(function() {
    $(this).css("background-position", "-4px -33px");
    $(this).prevAll().css("background-position", " -4px -33px");
    $(this).nextAll().css("background-position", " -4px -3px");
    $(this).parent().find('i').html($(this).attr('data-title'));
    $(this).parent().find('i').attr("data-score",$(this).attr('data-score'));
    // setAvgScore1(0);
});
$("#score_percent>dl>dd>span").hover(function() {
    $(this).css("background-position", "-4px -33px");
    $(this).prevAll().css("background-position", " -4px -33px");
    $(this).nextAll().css("background-position", " -4px -3px");
    $(this).parent().find('i').html($(this).attr('data-title'));
    // setAvgScore(0);
},function(){
    var data_score=$(this).parent().find('i').attr("data-score");
    $(this).parent().find("span").each(function(){
        if($(this).attr("data-score") == data_score){
            $(this).prevAll().css("background-position", " -4px -33px");
            $(this).css("background-position", " -4px -33px");
            $(this).nextAll().css("background-position", " -4px -3px");
            $(this).parent().find('i').html($(this).attr('data-title'));
        }
    });
});
$("#score_percent1>dl>dd>span").hover(function() {
    $(this).css("background-position", "-4px -33px");
    $(this).prevAll().css("background-position", " -4px -33px");
    $(this).nextAll().css("background-position", " -4px -3px");
    $(this).parent().find('i').html($(this).attr('data-title'));
    // setAvgScore1(0);
},function(){
    var data_score=$(this).parent().find('i').attr("data-score");
    $(this).parent().find("span").each(function(){
        if($(this).attr("data-score") == data_score){
            $(this).prevAll().css("background-position", " -4px -33px");
            $(this).css("background-position", " -4px -33px");
            $(this).nextAll().css("background-position", " -4px -3px");
            $(this).parent().find('i').html($(this).attr('data-title'));
        }
    });
});



})
});
	function setComment(score,comment,section,create_time){
		if(create_time){
			create_time=create_time.split(' ')[0];
		}
		var ret_score_comment = $("#ret_score_comment");
		var score_comment = $("#score_comment");
		var text_comment = $("#textComment");
		var ret_text_comment = $("#ret_textComment");
		var ret_comment_input = $("#ret_comment_input");
		var student1 = $("#avg_score").find("[data-score="+score+"]");
		var course = $("#ret_course");
		var time = $("#ret_time");
		var scoreObj= $("#ret_score_score");
		$("#bar-title").hide();
		ret_score_comment.show();
		score_comment.hide();
		text_comment.hide();
		ret_text_comment.show();
		student1.css("background-position","-4px -33px");
		student1.prevAll().css("background-position","-4px -33px");
		student1.nextAll().css("background-position","-4px -3px");
		$("#ret_avg_1").text(score);
		$("#ret_avg_12").text(score);
		ret_comment_input.text(comment);
		course.text(section);
		time.text(create_time);
		scoreObj.html('('+score+'分)');

	//	student.find("[data-score=5]").prevAll().css("background-position","-4px -33px");
	}
	function setComment1(score,comment,section,create_time){
		if(create_time){
			create_time=create_time.split(' ')[0];
		}
		var ret_score_comment = $("#ret_score_comment1");
		var score_comment = $("#score_comment1");
		var text_comment = $("#textComment1");
		var ret_text_comment = $("#ret_textComment1");
		var ret_comment_input = $("#ret_comment_input1");
		var student1 = $("#avg_score1").find("[data-score="+score+"]");
		var course = $("#ret_course1");
		var time = $("#ret_time1");
		var scoreObj = $("#ret_score_score1");
		$("#bar-title1").hide();
		ret_score_comment.show();
		score_comment.hide();
		text_comment.hide();
		ret_text_comment.show();
		student1.css("background-position","-4px -33px");
		student1.prevAll().css("background-position","-4px -33px");
		student1.nextAll().css("background-position","-4px -3px");
		$("#ret_avg_11").text(score);
		$("#ret_avg_111").text(score);
		ret_comment_input.html(comment);
		course.text(section);
		time.text(create_time);
		scoreObj.html('('+score+'分)');

	//	student.find("[data-score=5]").prevAll().css("background-position","-4px -33px");
	}
	function checkUserIsComment(type) {
		$.post('/comment/course/CheckISAddScore', {course_id: courseId, plan_id: planId},
			function (d) {
				if (type == 'living') {
					if (d.result.code == 0) {
						// 已评论
						setComment(d.result.data.score, d.result.data.comment, d.section, d.result.data.create_time);
						$('#ret_score_comment').show();
						$('#ret_textComment').show();
					} else {
						$('#score_comment').show();
						$('#textComment').show();
					}
				}

				if (type == 'finished') {
					if (d.result.code == 0) {
						// 已评论：0  未评论：101
						setComment1(d.result.data.score, d.result.data.comment, d.section, d.result.data.create_time);
						$('#ret_score_comment1').show();
						$('#ret_textComment1').show();
					} else {
						$('#score_comment1').show();
						$('#textComment1').show();
					}
				}
			}, 'json'
		);
	}
