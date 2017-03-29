/*jquery.course.info.js
*Date:10:00 11-09-2016
*/
$(function() {
	$('#course-info-show').on('click touchend','.select-member-category',function(){
 		$(this).find('dl').show();
 	});
	$(document).on("touchstart touchend click",function (event) {
 		var target_mem=$(event.target).parents(".select-member-category").length;
 		if(target_mem == 0){
 			$(".select-member-category").find('dl').hide();
 		}
     });
    mobileCourseImg();
	courseFixNav();
	if(classNum>=1) {
		selectClass(classId);
	}
	if(userId){
		getStudentTaskList();
		getNoteList();
		planCommentStatus();
	}
	courseShare();
	courseComment();
	//资料
	if($('.rt-course-ziliao').find('li').length > 2) {
		$('#course-download-more').show();
		$('.rt-course-ziliao').find('li:eq(0)').show();
		$('.rt-course-ziliao').find('li:eq(1)').show();
	}else{
		$('.rt-course-ziliao').find('li').show();
	}
	//教师
	if($('#rt-course-teacher').find('li').length > 2) {
		$('#course-more-teacher').show();
		$('#rt-course-teacher').find('li:eq(0)').show();
		$('#rt-course-teacher').find('li:eq(1)').show();
	}else{
		$('#rt-course-teacher').find('li').show();
	}

	if(type == 3) {
		$('#course-info-show').find('.course-address').show();
	}

	$('.rt-course-teacher').find('li').each(function() {
		var l = $(this).find('.course-teacher-infos').text().length;
		if(l < 30) {
			$(this).find('.course-teacher-detail').hide();
		}else{
			$(this).find('.course-teacher-detail').show();
		}
	})

	if($('#course-total-num').find('em').text() != 0) {
		$('#course-total-num').show();
	}

	if($('#planList').find('li').attr('type') == 3) {
		$('#planList').find('a').attr('href', 'javascript:;');
	}

	if($('.course-service-ft').find('li').length == 2) {
		$('.course-service-ft').css('marginLeft', '55px');
	}else{
		$('.course-service-ft').css('marginLeft', '25px');
	}

	if($(window).width() <= 768) {
		$('#course-mobile-fix').show();
		var loadClassId = $('body').attr('classid');
		$('.course-static-info').attr('href','/course.stat.GetStudentPlanStatByPid/'+loadClassId+'/'+userId);
	    var mySwiper = new Swiper('#nav-course',{
	        slidesPerView : 3,
	    });
	    var liWidth = $('#nav-course li').outerWidth();
	    var liIndex = $('#nav-course li').length;
	    $('#nav-course .course-ft-nav').css('width', liWidth*liIndex + 5);
        $('.course-introduce').find('img').css('width','100%');
	}else{
		$('#course-mobile-fix').hide();
	}

	$('#send-comment-info .comment-stars').find('dd').hover(function() {
		$(this).removeClass('stars-hollow').addClass('stars-solid');
		$(this).prevAll().removeClass('stars-hollow').addClass('stars-solid');
		$(this).nextAll().removeClass('stars-solid').addClass('stars-hollow');
		$(this).parents('.send-comment-info').find('.comment-score').text( $(this).attr('title'));
	}, function() {
	    var dataScore = $(this).parents('.send-comment-info').find('.comment-score').attr('score');
	    $(this).parents('.send-comment-info').find("dd").each(function(){
	        if($(this).attr('score') == dataScore){
	        	$(this).removeClass('stars-hollow').addClass('stars-solid');
	            $(this).prevAll().removeClass('stars-hollow').addClass('stars-solid');
	            $(this).nextAll().removeClass('stars-solid').addClass('stars-hollow');
				$(this).parents('.send-comment-info').find('.comment-score').attr('score', $(this).attr('score'));
				$(this).parents('.send-comment-info').find('.comment-score').text( $(this).attr('title'));
	        }
	    });
	});

	$('#send-comment-info .comment-stars').on('click', 'dd', function() {
		$(this).removeClass('stars-hollow').addClass('stars-solid');
		$(this).prevAll().removeClass('stars-hollow').addClass('stars-solid');
		$(this).nextAll().removeClass('stars-solid').addClass('stars-hollow');
		$(this).parents('.send-comment-info').find('.comment-score').attr('score', $(this).attr('score'));
		$(this).parents('.send-comment-info').find('.comment-score').text( $(this).attr('title'));
	});
});
window.onload = function() {
	courseNoteNum();
}
//多个班级tab
function selectClass(classId1, obj) {
	if(!courseUser) {
		if (!classList[classId1]['signUpStatus']) {//课程已报满
			$('.course-expired').show(); //课程报满
			$('.course-reg').hide();
			$('.course-comme').hide(); //进入课堂
			$('.course-start').hide(); //开始学习
			$('.continue-course').hide(); //继续学习
			$('.course-end').hide(); //课程结束
		} else { //
			if(!classList[classId1]['courseStatus']){
				$('.course-expired').hide(); //课程报满
				$('.course-reg').hide();
				$('.course-comme').hide(); //进入课堂
				$('.course-start').hide(); //开始学习
				$('.continue-course').hide(); //继续学习
				$('.course-end').show(); //课程结束
			}else {
				$('.course-reg').show();
				$('.course-expired').hide(); //课程报满
				$('.course-end').hide(); //课程结束
			}
		}
	}
	classId = classId1;
	var url_hash=window.location.hash.replace('#', '');
	$('#wrap-course-ft').find('.course-info-content').hide();
	$('#wrap-course-ft').find('.course-info-content:eq(1)').show();
	$('.course-ft-nav').find('li').removeClass('curr');

	if(!obj && url_hash && $('.course-ft-nav').find('li[status="'+url_hash+'"]').length>0){
		$('.course-ft-nav').find('li[status="'+url_hash+'"]').addClass('curr');
		$('#wrap-course-ft').find('.course-info-content:eq('+ (parseInt(url_hash)-1) +')').show().siblings().hide();
	}else{
		if(courseUser == true) { //已报名
			$('.course-ft-nav').find('li[status="2"]').addClass('curr');
		}else{ //未报名
			if($('#wrap-course-ft .course-introduce').find('.course-font').html() == '' || $('#wrap-course-ft .course-introduce').find('.course-font').html() == null) {
				$('.course-ft-nav').find('li[status="2"]').addClass('curr');
			}else{
				$('.course-ft-nav').find('li[status="1"]').addClass('curr');
				$('#wrap-course-ft').find('.course-info-content').hide();
				$('#wrap-course-ft').find('.course-info-content:eq(0)').show();
			}
		}
		$(obj).addClass('curr').siblings().removeClass('curr');
	}
	var t = $(obj).index();
	if(t == -1) {
		t = 0;
	}
	$('.sixCourse-classInfos').find('li:eq('+ t +')').show().siblings().hide();
	if(classId1 == '') {
		classId1 = $('.sixCourse-className').find('li:eq(0)').attr('classid');
	}
	$('body').attr('classid', $(obj).attr('classid'));
	var planListData = [];
	planListData['planList'] = planList && planList[classId] ? planList[classId] : [];
	var planListTpl = $('#planListTpl').html();
	var NoteChapterTpl = $('#NoteChapterTpl').html();
	var selectCommentTpl = $('#selectCommentTpl').html();
	$('#planList').html(Mustache.render(planListTpl, planListData));
	$('#note-chapter-num').html(Mustache.render(NoteChapterTpl, planListData));
	$('#note-chapter-num').find('li:eq(0)').before('<li onclick="getNoteList(this);" planId="">全部章节</li>');
	$('#planList').find('li').each(function() {
		var playTime = $(this).attr('playtime');
		var planUrl = $(this).find('a').attr('planUrl');
		if(playTime == '' || playTime == null || playTime==0) {
			$(this).find('a').attr('href', planUrl);
		}else{
			$(this).find('a').attr('href', planUrl + '?play_time=' + playTime);
		}
	})
	if(type == 2) {
		$('#planList').find('.course-long-time').hide();
		$('#planList').find('.course-plan-title').css('marginTop', '8px');
	}
	if(type == 3) {
		$('#planList').find('a').attr('href', 'javascript:;');
	}
	setTimeout(function() {
		$('.course-ft-nav').find('.chapter-num').text($('#planList').find('li').length);
		$('#note-chapter-num').find('li:eq(0)').addClass('curr');
	}, 300);
}

function clickCourseInfo(obj) {
	var status = $(obj).attr('status');
	$('.course-ft-nav').find('li').each(function() {
		window.location.hash=status;
		var liStatus = $(this).attr('status');
		if(liStatus == status) {
			$(this).addClass('curr').siblings().removeClass('curr');
		}
	})
	$('#wrap-course-ft').find('.course-info-content:eq('+$(obj).index()+')').show().siblings().hide();
	if($('.course-center-contents').is(":hidden")) {
		$('body,html').animate({ 'scrollTop':404 }, 100);
	}else{
		$('body,html').animate({ 'scrollTop':482 }, 100);
	}
}
//显示更多
function courseMoreList(obj) {
	var status  = $(obj).attr('status');
	if(status == 1) {
		if($(obj).hasClass('active')) {
			$(obj).removeClass('active');
			$('.rt-course-ziliao').find('li').hide();
			$('.rt-course-ziliao').find('li:eq(0)').show();
			$('.rt-course-ziliao').find('li:eq(1)').show();
			$(obj).find('em').text('更多');
			$(obj).find('span').removeClass('more-up-icon').addClass('more-dowm-icon');
		}else{
			$(obj).addClass('active');
			$('.rt-course-ziliao').find('li').show();
			$(obj).find('em').text('收起');
			$(obj).find('span').removeClass('more-dowm-icon').addClass('more-up-icon');
		}
	}else{
		if($(obj).hasClass('active')) {
			$(obj).removeClass('active');
			$('.rt-course-teacher').find('li').hide();
			$('.rt-course-teacher').find('li:eq(0)').show();
			$('.rt-course-teacher').find('li:eq(1)').show();
			$(obj).find('em').text('更多');
			$(obj).find('span').removeClass('more-up-icon').addClass('more-dowm-icon');
		}else{
			$(obj).addClass('active');
			$('.rt-course-teacher').find('li').show();
			$(obj).find('em').text('收起');
			$(obj).find('span').removeClass('more-dowm-icon').addClass('more-up-icon');
		}
	}
}

function courseFixNav() {
    $(window).on("scroll", function () {
		var userNav = $('.usernav').height();
		var courseInfoTop = $('.course-info-top').height();
		var curseCnters = $('.course-center-contents').height();
		var totalHeigh;
		if($('.course-center-contents').attr('status') == 1) {
			totalHeight = userNav + courseInfoTop + 60;
		}else{
			totalHeight = userNav + courseInfoTop + curseCnters + 100;
		}
	    var sTop = $(window).scrollTop();
	    var sTop = parseInt(sTop);
	    if (sTop > totalHeight) {
	        if(!$("#course-fix-nav").is(":visible")) {
	            try{
	                $("#course-fix-nav").slideDown(100);
	            }catch(e) {
	                $("#course-fix-nav").show();
	            }
	        }
	    }else {
	        if($("#course-fix-nav").is(":visible")) {
	            try{
	                $("#course-fix-nav").slideUp(100);
	            }catch(e) {
	                $("#course-fix-nav").hide();
	            }
	        }
	    }
	});
}
//马上开通
function memberOpen(obj,sourceSubdomain){
	var setId = $(obj).attr('data-id');
	var source = 'memberset';
	if (checkLogin(source,setId)) {
		location.href = sourceSubdomain+'/order.main.memberinfo/'+setId;
	}
}

function openQQ(qq) {
	window.location.href = "tencent://message/?uin=" + qq + "&Site=gbgjs.com&Menu=yes";
}

function downloadCount(id){
	$.post('/course/stat/UpdateDownloadCount', { planAttachId:id }, function(r) {

	},'json');
}
//收藏
function likeCollect(obj) {
	var cid = $(obj).attr("cid");
	var val = $(obj).find('img').attr('alt');
	var source = 'like';
	var oldHtml = '<img width="18" height="15" src="/assets_v2/img/red-heart.png" alt="已收藏" /> 已收藏';
	var html = '<img width="18" height="15" src="/assets_v2/img/blank-heart.png" alt="收藏" /> 收藏';
	if(checkLogin(source)) {
		if(val == '收藏') {
			$.post("/course.info.checkfavajax",{ couid:cid } ,function(r) {
				if(r.error) {
					layer.msg(r.error);
				}else {
					$(obj).html(oldHtml);
				}
			},"json")
		}
		if(val == '已收藏') {
			$.post("/course.info.delFav" ,{ cid:cid } ,function(r) {
				if(r.error) {
					layer.msg(r.error);
				}else {
					$(obj).html(html);
				}
			},"json")
		}
	}
}
//教师对应的planId
function videoPlanId(obj) {
	var videoPlanId=$(obj).attr('data-planid');
	layer.open({
		type: 2,
		title:false,
		shadeClose: true,
		area: ['710px', '399px'],
		content:'/user.teacher.TeacherVideoPreview#'+videoPlanId
	});
}

function courseShare() {
    $(".course-share-infos").share({
        sPic    : sPic,
        sTitle  : sTitle,
	wxHref  : wx_href
    });
}

function sendCourseComment() {
	var planId = $('#select-comment-chapter').find('a:eq(0)').attr('planid');
	var w;
	var h;
	$('#send-comment-info').find('.cite-text').attr('planId', planId);
	if($(window).width() <= 768) {
		w = '90%';
		h = '340px';
		$('.comment-title-pc').hide();
		$('.comment-title-mobile').show();
	}else {
		w = '533px';
		h = '340px';
		$('.comment-title-pc').show();
		$('.comment-title-mobile').hide();
	}
	layer.open({
		type: 1,
		title:['评价'],
		shadeClose: true,
		area: [w, h],
		content:$('#send-comment-info'),
		cancel: function() {
			addScoreCancle();
		}
	});
}

function selectCommentChapter(obj) {
	var planId = $(obj).attr('planid');
	$(obj).parents('.divselect').find('.cite-text').attr('planId', planId);
	$(obj).parents('.divselect').find('dl').hide();
}

function addScore() {
	var score = $('#send-comment-info').find('.comment-score').attr('score');
	var planId = $('#send-comment-info').find('.cite-text').attr('planid');
	var comment = $('#send-comment-info').find('textarea ').val();
		comment = comment.replace(/<\/?[^>]*>/g,'');
		comment = comment.replace(/[ | ]*\n/g,'\n');
		comment = comment.replace(/\n[\s| | ]*\r/g,'\n');
	if(comment == '' || comment == null){
		layer.msg('评论内容不能为空');
		return false;
	}else if(comment.length > 100) {
		layer.msg('输入内容太多了');
		return false;
	}else if(planId == '' || planId == null) {
		return false;
	}else if(score == '' || score == null) {
		return false;
	}else{
		$.post('/comment/course/addscore', { course_id:cid, score:score, plan_id:planId, comment:comment }, function(r) {
			if(r.code == 1) {
				setTimeout(function() {
					$('.growth-score').show();
					$('.growth-score').animate({
						'bottom' : '230px'
					}, 1200);
					courseComment();
					planCommentStatus();
					addScoreCancle();
					layer.closeAll();
				}, 300)
			}
		}, 'json');
	}
}
//检测评论的课
function planCommentStatus() {
	var classId = $('body').attr('classid');
	var planCommentStatusTpl = $('#planCommentStatusTpl').html();
	$.get('/course/info/planCommentStatus', { courseId:cid, classId:classId }, function(r) {
		if(r.code ==0) {
			$('#select-comment-chapter').html(Mustache.render(planCommentStatusTpl, r.result));
			//1 -> 未评论，0 -> 已评论
			$('#select-comment-chapter').find('a[type="0"]').parent('dd').remove();
			var typeStatus = $('#select-comment-chapter').find('a[type="1"]');
			if(!typeStatus[0]) {
				$('#send-comment-info').find('.send-comment-info').hide();
				$('#send-comment-info').find('.course-comment-tip').show();
				if(isMemberRegType == false) {
					$('#send-comment-info').find('.course-comment-tip').text('学习有效期已失效，请重新参与课程');
				}else {
					$('#send-comment-info').find('.course-comment-tip').text('暂无可评价课时');
				}
			}else{
				$('#send-comment-info').find('.send-comment-info').show();
				$('#send-comment-info').find('.course-comment-tip').hide();
				$('#send-comment-info').find('.cite-text').text($('#select-comment-chapter').find('a[type="1"]:eq(0)').text());
				$('#send-comment-info').find('.cite-text').attr('planid', $('#select-comment-chapter').find('a[type="1"]:eq(0)').attr('planid'));
			}
		}else{
			layer.msg(r.errMsg);
		}
	}, 'json');
}

function addScoreCancle() {
	$('#send-comment-info').find('.comment-stars').find('dd').removeClass('stars-hollow').addClass('stars-solid');
	$('#send-comment-info').find('textarea').val('');
	$('.growth-score').hide();
	layer.closeAll();
}
function selectScore(obj) {
	var score = $(obj).attr('score');
	$('#select-score').find('.cite-text').attr('score', score);
	$(obj).parents('.divselect').find('dl').hide();
	courseComment()
}
function selectTime(obj) {
	var time = $(obj).attr('time');
	$('#select-time').find('.cite-text').attr('time', time);
	$(obj).parents('.divselect').find('dl').hide();
	courseComment();
}
function courseComment(obt, courseId, curr) {
	var me;
	if($('#checked-me-comment').is(':checked')) {
		me = 'me';
	}
	var courseCommentTpl = $('#courseCommentTpl').html();
	var html = '<li class="course-no-data" style="background:#fff;border:0;padding:0;">'
		html += '<span class="course-blank-comment-icon c-fl mr10"></span>'
		html += '<div class="mt5">当前没有评论哦~</div>'
		html += '</li>';
	var page = curr || 1;
	var time  = $('#select-time').find('.cite-text').attr('time');
	var score = $('#select-score').find('.cite-text').attr('score');
	$.get('/course/info/CommentAjax', { courseId:cid, me:me, page:page, time:time, score:score }, function(r) {
		if(r.code ==0) {
			$('#course-comment-list').html(Mustache.render(courseCommentTpl, r.result));
		    laypage({
		      cont: $("#course-comment-page"),
		      pages: r.result.totalPage,
		      curr: curr || 1,
		      groups: 3,
		      prev : false,
		      next : false,
		      jump: function(obj, first){
		        if(!first){
		          	courseComment(obt, courseId, obj.curr);
					if($('.course-center-contents').is(":hidden")) {
						$('body,html').animate({ 'scrollTop':404 }, 100);
					}else{
						$('body,html').animate({ 'scrollTop':482 }, 100);
					}
		        }
		      }
		    });
            if(r.result.totalSize != 0) {
            	$('.course-nav-menu').find('.comment-num').attr('num', r.result.totalSize);
            	$('.course-nav-menu').find('.comment-num').text('('+ r.result.totalSize +')');
            	$('.course-nav-menu').find('.comment-num').parents('li').show();
            }else{
            	$('.course-nav-menu').find('.comment-num').text('');
            	$('.course-nav-menu').find('.comment-num').parents('li').hide();
            }
			$('#course-comment-list').find('li').each(function() {
				var imgSrc = $(this).find('img').attr('src');
				var liUserId = $(this).find('.user-del-comment').attr('userid');
				var score = $(this).find('dl').attr('studentscore');
				var liReplayContents = $(this).find('.course-user-replay').attr('replay-contents');
				if(liUserId == userId) {
					$(this).find('.user-del-comment').show();
				}
				if(liReplayContents == '' || liReplayContents == null) {
					$(this).find('.course-user-replay').hide();
				}
				if(imgSrc == '' || imgSrc == null) {
					$(this).find('img').attr('/assets_v2/img/photo-1.jpg');
				}
				if(0 < score && score <= 1) {
					$(this).find('dd:eq(0)').removeClass('stars-hollow').addClass('stars-solid');
				}else if(1 < score && score <= 2) {
					$(this).find('dd:eq(0)').removeClass('stars-hollow').addClass('stars-solid');
					$(this).find('dd:eq(1)').removeClass('stars-hollow').addClass('stars-solid');
				}else if(2 < score && score <= 3){
					$(this).find('dd:eq(0)').removeClass('stars-hollow').addClass('stars-solid');
					$(this).find('dd:eq(1)').removeClass('stars-hollow').addClass('stars-solid');
					$(this).find('dd:eq(2)').removeClass('stars-hollow').addClass('stars-solid');
				}else if(3 < score && score <= 4) {
					$(this).find('dd:eq(0)').removeClass('stars-hollow').addClass('stars-solid');
					$(this).find('dd:eq(1)').removeClass('stars-hollow').addClass('stars-solid');
					$(this).find('dd:eq(2)').removeClass('stars-hollow').addClass('stars-solid');
					$(this).find('dd:eq(3)').removeClass('stars-hollow').addClass('stars-solid');
				}else if(4 < score && score <= 5) {
					$(this).find('dd').removeClass('stars-hollow').addClass('stars-solid');
				}else{
					return;
				}
			});
		}else{
			$('#course-comment-list').html(html);
			$("#course-comment-page").hide();
			$('.course-nav-menu').find('.comment-num').text('');
			$('.course-nav-menu').find('.comment-num').hide();
		}
	}, 'json');
}

function delComment(obj) {
    var planId = $(obj).attr('planid');
    var cli= $(obj).parents('li');
    layer.confirm('删除此条评论？', {
      btn: ['确定','取消'],
      closeBtn: 0,
      title:['删除评论']
    }, function() {
        $.post("/comment/course/DelComment", { courseId:cid ,planId:planId }, function(r) {
            if(r.code==0){
                cli.remove();
                layer.msg('删除成功');
                planCommentStatus();
                courseComment();
            }else{
                layer.msg(r.errMsg);
            }
        },"json");
    }, function(){
        layer.closeAll();
    });
}

function getStudentTaskList(curr) {
	var page = curr || 1;
	var html = '<li class="course-no-data" style="background:#fff;">'
		html += '<span class="course-blank-task-icon c-fl mr10"></span>'
		html += '<div class="mt5">老师还没有布置作业哦~</div>'
		html += '</li>';
	var levelone = '<dl class="mt5 hidden-xs">'
		levelone += '<dd class="red-flower-icon"></dd>'
		levelone += '</dl>';
	var leveltwo = '<dl class="mt5 hidden-xs">'
		leveltwo += '<dd class="red-flower-icon"></dd>'
		leveltwo += '<dd class="red-flower-icon"></dd>'
		leveltwo += '</dl>';
	var levelthree = '<dl class="mt5 hidden-xs">'
		levelthree += '<dd class="red-flower-icon"></dd>'
		levelthree += '<dd class="red-flower-icon"></dd>'
		levelthree += '<dd class="red-flower-icon"></dd>'
		levelthree += '</dl>';
	var levelFour = '<dl class="mt5 hidden-xs">'
		levelFour += '<dd class="red-flower-icon"></dd>'
		levelFour += '<dd class="red-flower-icon"></dd>'
		levelFour += '<dd class="red-flower-icon"></dd>'
		levelFour += '<dd class="red-flower-icon"></dd>'
		levelFour += '</dl>';
	var levelFive = '<dl class="mt5 hidden-xs">'
		levelFive += '<dd class="red-flower-icon"></dd>'
		levelFive += '<dd class="red-flower-icon"></dd>'
		levelFive += '<dd class="red-flower-icon"></dd>'
		levelFive += '<dd class="red-flower-icon"></dd>'
		levelFive += '<dd class="red-flower-icon"></dd>'
		levelFive += '</dl>';
	var courseTaskTpl = $('#courseTaskTpl').html();
	var classId = $('body').attr('classid');
	$.post('/task/commitTask/GetStudentTaskList', { classId:classId, uId:userId, page:page }, function(r) {
		if(r.code == 0) {
			if(r.data.data == '' || r.data.data == null) {
				$('#course-task-list').html(html);
				$('#course-task-page').hide();
				$('.course-nav-menu').find('.task-num').text('');
				$('.course-nav-menu').find('.task-num').hide();
			}else{
				$('#course-task-list').html(Mustache.render(courseTaskTpl, r.data));
	            laypage({
	                cont: $("#course-task-page"),
	                pages: r.data.page.totalPage,
	                curr: curr || 1,
	                groups: 3,
			      	prev : false,
			      	next : false,
	                jump: function(obj, first){
	                    if(!first){
	                        getStudentTaskList(obj.curr);
							if($('.course-center-contents').is(":hidden")) {
								$('body,html').animate({ 'scrollTop':404 }, 100);
							}else{
								$('body,html').animate({ 'scrollTop':482 }, 100);
							}
	                    }
	                }
	            });
	            if(r.data.page.totalPage != 0) {
	            	$('.course-nav-menu').find('.task-num').text('('+ r.data.page.total +')');
	            	$('.course-nav-menu').find('.task-num').show();
	            }else{
	            	$('.course-nav-menu').find('.task-num').text('');
	            	$('.course-nav-menu').find('.task-num').hide();
	            }
	            $('#course-task-list').find('.couse-task-status').each(function() {
	            	var status = $(this).attr('data-status');
	            	var level = $(this).attr('data-level'); // 2 == 已批改 存在level
	            	var url = $(this).attr('data-url');
	            	if(status == 1) { //待批改 -> 已提交
	            		if($(window).width() < 768) {
	            			$(this).html('<span class="mt10">已提交</span>');
	            		}else {
	            			$(this).html('<span class="mt10 mr25">已提交</span>');
	            		}
	            	}else if(status == 2) { //已批改 -> 查看
	            		if($(window).width() < 768) {
							if(level == 1) {
								$(this).html('<span class="course-task-you hidden-md hidden-lg hidden-sm"></span><p class="tar"><a href="'+url+'">查看</a></p>');
		            		}else if(level == 2) {
		            			$(this).html('<span class="course-task-liang hidden-md hidden-lg hidden-sm"></span><p class="tar"><a href="'+url+'">查看</a></p>');
		            		}else if(level == 3) {
		            			$(this).html('<span class="course-task-zhong hidden-md hidden-lg hidden-sm"></span><p class="tar"><a href="'+url+'">查看</a></p>');
		            		}else if(level == 4) {
		            			$(this).html('<span class="course-task-cha hidden-md hidden-lg hidden-sm"></span><p class="tar"><a href="'+url+'">查看</a></p>');
		            		}else if(level == 5) {
		            			$(this).html('<span class="course-task-jiaocha hidden-md hidden-lg hidden-sm"></span><p class="tar"><a href="'+url+'">查看</a></p>');
		            		}else{
		            			$(this).html('<p class="tar"><a href="'+url+'">查看</a></p>');
		            		}
	            		}else{
							if(level == 1) {
								$(this).html(levelone+'<p class="tar"><a class="mr30" href="'+url+'">查看</a></p>');
		            		}else if(level == 2) {
		            			$(this).html(leveltwo+'<p class="tar"><a class="mr30" href="'+url+'">查看</a></p>');
		            		}else if(level == 3) {
		            			$(this).html(levelthree+'<p class="tar"><a class="mr30" href="'+url+'">查看</a></p>');
		            		}else if(level == 4) {
		            			$(this).html(levelFour+'<p class="tar"><a class="mr30" href="'+url+'">查看</a></p>');
		            		}else if(level == 5) {
		            			$(this).html(levelFive+'<p class="tar"><a class="mr30" href="'+url+'">查看</a></p>');
		            		}else{
		            			$(this).html('<p class="tar"><a class="mr30" href="'+url+'">查看</a></p>');
		            		}
	            		}
	            	}else if(status == 4) { //未提交 -> 写作业
	            		$(this).html('<a href="'+url+'" class="course-home-work">写作业</a>');
	            	}else{
	            		return;
	            	}
	            })
			}
		}else{
			$('#course-task-list').html(html);
			$('#course-task-page').hide();
			$('.course-nav-menu').find('.task-num').text('');
			$('.course-nav-menu').find('.task-num').hide();
		}
	}, 'json');
}

function getNoteList(obt, planId, classId, curr) {
	var classId = $('body').attr('classid');
	var planId = $(obt).attr('planid');
	var page = curr || 1;
	var html = '<div class="course-no-data" style="background:#fff;padding:0;">'
		html += '<span class="course-blank-note-icon c-fl mr10"></span>'
		html += '<div class="mt5">你还没有记过笔记哦~</div>'
		html += '</div>';
	var courseNoteTpl = $('#courseNoteTpl').html();
	$(obt).addClass('curr').siblings().removeClass('curr');
	$.post('/course/note/GetNoteListByPlanIdAndClassId', { fkUser:userId, classId:classId, planId:planId, page:page }, function(r) {
		if(r.code == 0) {
			if(planId == undefined) {
				$('.note-num').attr('num', r.data.totalSize);
			}
            if(r.data.items == '' || r.data.items == null) {
				$('#course-note-page').hide();
				$('#course-note-list').html(html);
            }else{
				$('#course-note-list').html(Mustache.render(courseNoteTpl, r.data));
				$('#course-note-list').find('li').each(function() { //isMemberRegType == false;error 无连接
					var url = $(this).find('.course-time-info').attr('data-url');
					var planTime = $(this).find('.course-time-info').attr('data-time');
					var planPlayTime = $(this).find('.course-time-info').attr('data-plan-time');
					var planHidden = $(this).find('.course-time-info').attr('data-hidden');
					if(planPlayTime != 'error' || isMemberRegType == true) {
						$(this).find('.course-time-info').attr('href', url+'?play_time='+planPlayTime);
					}
					if(planPlayTime == 'error') {
						$(this).find('.course-time-info').hide();
					}
					if(planHidden == 'true') { //hidden字段返回true表示这个视频被隐藏了  不显示跳转
						$(this).find('.course-time-info').attr('href', 'javascript:;');
						$(this).find('.course-time-info').hide();
					}
					if($(this).find('.note-title').attr('data-content') == '' || $(this).find('.note-title').attr('data-content') == null) {
						$(this).find('.note-title').text('重点');
					}
				});
	            laypage({
	                cont: $("#course-note-page"),
	                pages: r.data.totalPage,
	                curr: curr || 1,
	                groups: 3,
		      		prev : false,
		      		next : false,
	                jump: function(obj, first){
	                    if(!first){
	                        getNoteList(obt, planId, classId, obj.curr);
							if($('.course-center-contents').is(":hidden")) {
								$('body,html').animate({ 'scrollTop':404 }, 100);
							}else{
								$('body,html').animate({ 'scrollTop':482 }, 100);
							}
	                    }
	                }
	            });
            }
		}else{
			$('#course-note-page').hide();
			$('#course-note-list').html(html);
			$('.note-num').attr('num', 0);
		}
	}, 'json');
}

function courseNoteUpdate(obj) {
	var text = $(obj).parents('li').find('.note-title').text();
	$(obj).parents('li').find('textarea').val(text);
	$(obj).parents('li').find('.course-note-update').show();
}

function courseNoteDel(obj) {
	$(obj).parents('li').find('textarea').val('');
	$(obj).parents('li').find('.course-note-update').hide();
}

function courseDelNote(obj) {
	var li = $(obj).parents('li');
	var noteId = $(obj).attr('noteid');
	var planId = $(obj).attr('planid');
	layer.confirm('确定删除笔记？', {
	  btn: ['确定','取消'],
	  title:['删除笔记']
	}, function(){
		$.post('/course.note.DelNote', { note_id:noteId, plan_id:planId, }, function(r) {
			if(r.code == 200) {
				li.remove();
				getNoteList();
				courseNoteNum();
				$('#note-chapter-num').find('li').removeClass('curr');
				$('#note-chapter-num').find('li:eq(0)').addClass('curr');
				layer.closeAll();
			}else{
				layer.msg('删除失败');
			}
		}, 'json');
	}, function(){
		layer.closeAll();
	});
}

function courseNoteNum() {
	setTimeout(function() {
		getNoteList();
		var  l = $('.note-num').attr('num');
		if(l != 0) {
	    	$('.course-nav-menu').find('.note-num').text('('+ l +')');
	    	$('.course-nav-menu').find('.note-num').show();
	    	$('#note-chapter-num').show();
	    }else{
	    	$('.course-nav-menu').find('.note-num').hide();
	    	$('#note-chapter-num').hide();
	    }
	}, 500);
}

function courseAddNote(obj) {
	var noteId = $(obj).attr('noteid');
	var planId = $(obj).attr('planid');
	var content = $(obj).parents('li').find('textarea').val();
		content = content.replace(/<\/?[^>]*>/g,'');
		content = content.replace(/[ | ]*\n/g,'\n');
		content = content.replace(/\n[\s| | ]*\r/g,'\n');
	if(content == '' || content == null) {
		layer.msg('输入内容为空');
		return false;
	}else if(content.length > 100) {
		layer.msg('输入内容太多了');
		return false;
	}else if(planId == '' || planId == null) {
		return false;
	}else if(noteId == '' || noteId == null) {
		return false;
	}else{
		$.post('/course.note.updateNote', { note_id:noteId, plan_id:planId, content:content }, function(r) {
			if(r.code == 200) {
				layer.closeAll();
				getNoteList();
			}else{
				layer.msg('修改失败');
			}
		}, 'json');
	}
}

function mobileCourseImg() {
	if($(window).width() <= 768 && courseUser == true) {
		$('#course-info-show').find('img.course-info-img').attr('src', '');
		$('#course-info-show').find('img.course-info-img').attr('src', '/assets_v2/img/course-mobile-img.jpg');
		$('#course-info-show').find('.course-progress-info').find('span').hide();
		$('#course-info-show').find('.course-progress-info').css({
			'color' : '#fff',
			'fontSize':'16px'
		})
	}
}
