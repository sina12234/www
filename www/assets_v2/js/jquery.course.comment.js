/*
*评论模块
*/
$(function(){
	$.divselect(".divselect cite");
	$('.course-comment-info').on('click', '.reply-comment-btn', function() {
		$(this).parents('li').find('.reply-course-comment').show();
	});
	$('.course-comment-info').on('click', '.cancle-reply-comment', function() {
		$(this).parents('.reply-course-comment').hide();
		$(this).parents('.reply-course-comment').find('.student-replay-comment').val('');
	});
	citeTextVal();
});
function selectScore(obj) {
	var searchScore = $.trim($('#search-score').find('.cite-text').text()); //评分
	var searchTime = $.trim($('#search-time').find('.cite-text').text()); //时间
	var score = $(obj).attr('score');
	var localUrl = window.location.pathname;
	if(searchTime == '全部时间') {
		if(score == 'all') {
			window.location = localUrl+'?score=all&time=all';
		}else{
			window.location = localUrl+'?score='+score+'&time=all';
		}
	}else if(searchTime == '最近一周') {
		if(score == 'all') {
			window.location = localUrl+'?score=all&time=week';
		}else{
			window.location = localUrl+'?score='+score+'&time=week';
		}
	}else {
		if(score == 'all') {
			window.location = localUrl+'?score=all&time=month';
		}else{
			window.location = localUrl+'?score='+score+'&time=month';
		}
	}
}
function selectTime(obj) {
	var searchScore = $.trim($('#search-score').find('.cite-text').text()); //评分
	var searchTime = $.trim($('#search-time').find('.cite-text').text()); //时间
	var time = $(obj).attr('time');
	var localUrl = window.location.pathname;
	if(searchScore == '全部评分') {
		if(time == 'all') {
			window.location = localUrl+'?score=all&time=all';
		}else if(time == 'week') {
			window.location = localUrl+'?score=all&time=week';
		}else {
			window.location = localUrl+'?score=all&time=month';
		}
	}else if(searchScore == '5分') {
		if(time == 'all') {
			window.location = localUrl+'?score=5&time=all';
		}else if(time == 'week') {
			window.location = localUrl+'?score=5&time=week';
		}else {
			window.location = localUrl+'?score=5&time=month';
		}
	}else if(searchScore == '4分') {
		if(time == 'all') {
			window.location = localUrl+'?score=4&time=all';
		}else if(time == 'week') {
			window.location = localUrl+'?score=4&time=week';
		}else {
			window.location = localUrl+'?score=4&time=month';
		}
	}else if(searchScore == '3分') {
		if(time == 'all') {
			window.location = localUrl+'?score=3&time=all';
		}else if(time == 'week') {
			window.location = localUrl+'?score=3&time=week';
		}else {
			window.location = localUrl+'?score=3&time=month';
		}
	}else if(searchScore == '2分') {
		if(time == 'all') {
			window.location = localUrl+'?score=2&time=all';
		}else if(time == 'week') {
			window.location = localUrl+'?score=2&time=week';
		}else {
			window.location = localUrl+'?score=2&time=month';
		}
	}else{
		if(time == 'all') {
			window.location = localUrl+'?score=1&time=all';
		}else if(time == 'week') {
			window.location = localUrl+'?score=1&time=week';
		}else {
			window.location = localUrl+'?score=1&time=month';
		}
	}
}
function InsertCommentReplay(obj) {
	var InsertObj = $(obj);
	var commentId = InsertObj.attr('commentid');
	var contents = InsertObj.parents('.reply-course-comment').find('.student-replay-comment').val();
		contents = contents.replace(/<\/?[^>]*>/g,'');
		contents = contents.replace(/[ | ]*\n/g,'\n');
		contents = contents.replace(/\n[\s| | ]*\r/g,'\n');
	if(contents == '') {
		layer.msg('请输入评论内容');
	}else if(contents.length > 100){
		layer.msg('输入内容太多了');
		return false;
	}else {
		$.post('/comment/manage/InsertCommentReplay', { commentId:commentId, contents:contents }, function(r) {
			if(r.code == 0) {
				var html = '<div class="col-md-3"></div><div class="col-md-17 bgf7 clearfix mb10 p10"><div class="col-md-16 p0">';
					html += '<span class="cBlue mr10">['+r.manage_name+'老师回复]</span>'+r.contents+'</div>';
					html += '<div class="col-md-4 tar p0">';
					html += '<p>'+r.time+'&nbsp;&nbsp;</p>';
					html += '<a href="javascript:;" replayid='+r.result+' onclick="deleteCommentReplay(this)" class="cBlue">删除回复&nbsp;&nbsp;</a>';
					html += '</div>';
				InsertObj.parents('li').find('.reply-comment-btn').hide();
				InsertObj.parents('li').find('.teacher-replay-infos').html(html);
				InsertObj.parents('li').find('.reply-course-comment').hide();
			}else {
				layer.msg(r.errMsg);
			}
		},"json");	
	}
}
function deleteCommentReplay(obj) {
	var pk_replay = $(obj).attr('replayid');
	layer.confirm('确定删除该评论吗？', {
	  btn: ['确定','取消'],
	  title:['删除评论']
	}, function(){
		$.post('/comment/manage/DeleteCommentReplay', { pk_replay:pk_replay }, function(r) {
			if(r.code == 0) {
				layer.msg('删除成功');
				location.reload();
				layer.closeAll();
			}else {
				layer.msg(r.message);
			}
		}, "json");
	}, function(){
	  	layer.closeAll();
	});
}
function citeTextVal() {
	var urlLink = location.search;
	var URLParams = new Array(); 
	var aParams = document.location.search.substr(1).split('&'); 
	for (i=0, len = aParams.length; i < len; i++) { 
		var aParam = aParams[i].split('='); 
		URLParams[aParam[0]] = aParam[1]; 
	} 
	if(urlLink == '?score=all&time=all') {
		$('#search-time').find('.cite-text').text('全部时间');
		$('#search-score').find('.cite-text').text('全部评分');
	}else if(urlLink == '?score=5&time=all'){
		$('#search-time').find('.cite-text').text('全部时间');
		$('#search-score').find('.cite-text').text('5分');
	}else if(urlLink == '?score=5&time=week') {
		$('#search-time').find('.cite-text').text('最近一周');
		$('#search-score').find('.cite-text').text('5分');
	}else if(urlLink == '?score=5&time=month') {
		$('#search-time').find('.cite-text').text('最近一个月');
		$('#search-score').find('.cite-text').text('5分');
	}else if(urlLink == '?score=4&time=all'){
		$('#search-time').find('.cite-text').text('全部时间');
		$('#search-score').find('.cite-text').text('4分');
	}else if(urlLink == '?score=4&time=week') {
		$('#search-time').find('.cite-text').text('最近一周');
		$('#search-score').find('.cite-text').text('4分');
	}else if(urlLink == '?score=4&time=month') {
		$('#search-time').find('.cite-text').text('最近一个月');
		$('#search-score').find('.cite-text').text('4分');
	}else if(urlLink == '?score=3&time=all'){
		$('#search-time').find('.cite-text').text('全部时间');
		$('#search-score').find('.cite-text').text('3分');
	}else if(urlLink == '?score=3&time=week') {
		$('#search-time').find('.cite-text').text('最近一周');
		$('#search-score').find('.cite-text').text('3分');
	}else if(urlLink == '?score=3&time=month') {
		$('#search-time').find('.cite-text').text('最近一个月');
		$('#search-score').find('.cite-text').text('3分');
	}else if(urlLink == '?score=2&time=all'){
		$('#search-time').find('.cite-text').text('全部时间');
		$('#search-score').find('.cite-text').text('2分');
	}else if(urlLink == '?score=2&time=week') {
		$('#search-time').find('.cite-text').text('最近一周');
		$('#search-score').find('.cite-text').text('2分');
	}else if(urlLink == '?score=2&time=month') {
		$('#search-time').find('.cite-text').text('最近一个月');
		$('#search-score').find('.cite-text').text('2分');
	}else if(urlLink == '?score=1&time=all'){
		$('#search-time').find('.cite-text').text('全部时间');
		$('#search-score').find('.cite-text').text('1分');
	}else if(urlLink == '?score=1&time=week') {
		$('#search-time').find('.cite-text').text('最近一周');
		$('#search-score').find('.cite-text').text('1分');
	}else if(urlLink == '?score=1&time=month') {
		$('#search-time').find('.cite-text').text('最近一个月');
		$('#search-score').find('.cite-text').text('1分');
	}else if(urlLink == '?score=all&time=week'){
		$('#search-time').find('.cite-text').text('最近一周');
		$('#search-score').find('.cite-text').text('全部评分');
	}else if(urlLink == '?score=all&time=month'){
		$('#search-time').find('.cite-text').text('最近一个月');
		$('#search-score').find('.cite-text').text('全部评分');
	}else {
		$('#search-time').find('.cite-text').text('全部时间');
		$('#search-score').find('.cite-text').text('全部评分');
	}
	if(URLParams['score']) {
		if(URLParams['score'] == 'all'){
			$('#search-score').find('.cite-text').text('全部评分');
		}else {
			$('#search-score').find('.cite-text').text(URLParams['score']+'分');
		}
	}
	if(URLParams['time']) {
		if(URLParams['score'] == 'all'){
			$('#search-score').find('.cite-text').text('全部评分');
		}else {
			$('#search-score').find('.cite-text').text(URLParams['score']+'分');
		}
	}
}