/*www.gn100.com07-08-2016
*jquery.create.course.js, Interface type file
*Name:Changyuan.liu(2016-07-08)
* Some questions are welcome to change
*/
var courseId = $('#grobal-course').attr('courseid');
function locationReload() {
	setTimeout(function() {
		location.reload();
	}, 500);
}
//上架课程
function setCourseAdminStatus(obj) {
	var courseId =  $(obj).attr('courseid');
	var adminStatus = $(obj).attr('adminStatus');
	var params = {
			courseId    : courseId,
			adminStatus : adminStatus
		};
		$.ajax({
			url: '/user/supCourseAjax/setCourseAdminStatus',
			type: 'post',
			dataType: 'json',
			data: JSON.stringify(params),
			success: function(r) {
				if(r.code == 0) {
					layer.msg(r.message);
					locationReload();
				}else {
					layer.msg(r.message);
				}
			}
		});
}
//分类列表
function getCate() {
	var params = {
			cateId : 0
		};
	var getCateTpl = $('#getCateTpl').html();
		$.ajax({
			url: '/user/courseAjax/getCate',
			type: 'post',
			dataType: 'json',
			data: JSON.stringify(params),
			success: function(r) {
				if(r.code == 0) {
					$('#get-cate').html(Mustache.render(getCateTpl, r.result));
				}else {
					return false;
				}
			}
		});
}
function getCateClass(obj, cateId) {
		$('#get-attr-category').hide();
		$(obj).parents('.divselect').find('.cite-text').attr('cateid', $(obj).attr('cateid'));
	var cateId = $(obj).attr('cateid');
	var params = {
			cateId : cateId
		};
	var getCateTpl = $('#getCateClassTpl').html();
		$.ajax({
			url: '/user/courseAjax/getCate',
			type: 'post',
			dataType: 'json',
			data: JSON.stringify(params),
			success: function(r) {
				if(r.code == 0) {
					$('#get-class-info').show();
					$('#get-course-secondCate').text('请选择');
					$('#get-category-info').hide();
					$('#add-secondCate').show();
					$('#add-secondCate').find('.cite-text').text('请选择');
					$('#add-thirdCate').hide();
					$('#get-cate-class').html(Mustache.render(getCateTpl, r.result));
				}
			}
		});
}
function getCateName(obj, cateId) {
	$(obj).parents('.divselect').find('.cite-text').attr('cateid', $(obj).attr('cateid'));
	var cateId = $(obj).attr('cateid');
	var params = {
			cateId : cateId
		};
	var getCateTpl = $('#getCateNameTpl').html();
		$.ajax({
			url: '/user/courseAjax/getCate',
			type: 'post',
			dataType: 'json',
			data: JSON.stringify(params),
			success: function(r) {
				if(r.code == 0) {
					$('#get-attr-category').hide();
					$('#slt-course-name').attr('dataid', '');
					$('#slt-course-name').val('');
					$('#get-category-info').show();
					$('#get-course-thirdCate').text('请选择');
					$('#add-thirdCate').show();
					$('#add-thirdCate').find('.cite-text').text('请选择');
					$('#get-cate-name').html(Mustache.render(getCateTpl, r.result));
					$('#get-attr-category').attr('data-flag', '1');
				}else{
					$('#get-attr-category').attr('data-flag', '0');
				}
			}
		});
}
function getCateAttr(obj, cateId) {
	var cateId;
		$(obj).parents('.divselect').find('.cite-text').attr('cateid', $(obj).attr('cateid'));
		$('#slt-course-name').attr('dataid', '');
		$('#slt-course-name').val('');
		cateId = $(obj).attr('cateid');
	var params = {
			cateId : cateId
		};
	var getAttrTpl = $('#getAttrTpl').html();
		$.ajax({
			url: '/user/courseAjax/getAttr',
			type: 'post',
			dataType: 'json',
			data: JSON.stringify(params),
			success: function(r) {
	       		if(r.code == 0) {
	       			$('#get-attr-category').show();
	       			$('#get-attr-category').attr('data-flag', '1');
	       			$('#get-attr').html(Mustache.render(getAttrTpl, r.result));
	       		}else {
	       			$('#get-attr-category').hide();
	       			$('#slt-course-name').attr('dataid', '0');
	       			$('#get-attr-category').attr('data-flag', '0');
	       			return false;
	       		}
			}
		})
}
//修改科目
function updateCateAttr(obj, cateId) {
	var cateId = $('#get-course-thirdCate').attr('cateid');
	var params = {
			cateId : cateId
		};
	var getAttrCategory = $('#get-attr-category');
	var getAttrTpl = $('#getAttrTpl').html();
		$.ajax({
			url: '/user/courseAjax/getAttr',
			type: 'post',
			dataType: 'json',
			data: JSON.stringify(params),
			success: function(r) {
	       		if(r.code == 0) {
                    getAttrCategory.show();
                    getAttrCategory.attr('data-flag', '1');
                    $('#get-attr').html(Mustache.render(getAttrTpl, r.result));
                    var dataId = $('#slt-course-name').attr('dataid');
                    var dataIdArray = dataId.split(',');
                    $('#get-attr').find('li').each(function () {
                        for (var i = 0, len = dataIdArray.length; i < len; i++) {
                            if (dataIdArray[i] == $(this).attr('attrid')) {
                                $(this).addClass('seleted');
                            }
                        }
                    });
                    if ($(window).width() < 768){
                        var w = '90%';
						var h = '330px';
                        layer.open({
                            type: 1,
                            title: ['科目'],
                            area: [w, h],
                            shadeClose: false,
                            content: $('#selet-course-name')
                        });
                }else{
                    layer.open({
                        type: 1,
                        title:['科目'],
                        area: ['522px', '330px'],
                        shadeClose: false,
                        content: $('#selet-course-name')
                    });
					}
	       		}else {
	       			getAttrCategory.hide();
	       			getAttrCategory.attr('data-flag', '0');
	       			return false;
	       		}
			}
		})
}
//标签
function getTag() {
	var params = {
			courseId : courseId
		};
	var getLastedTagTpl = $('#getLastedTagTpl').html();
	var getOftenTagTpl = $('#getOftenTagTpl').html();
		$.ajax({
			url: '/user/courseAjax/getTag',
			type: 'post',
			dataType: 'json',
			data:JSON.stringify(params),
			success: function(r) {
				if(r.code == 0){
					$('#lasted-tag').html(Mustache.render(getLastedTagTpl, r.result));
					$('#often-tag').html(Mustache.render(getOftenTagTpl, r.result));
				}else{
					$('#lasted-tag').html('还没有标签哦！');
					$('#often-tag').html('还没有标签哦！');
				}
			}
		})
}
//讲师
function orgTeacher() {
	var teacherIds;
	var courseId = $('#grobal-course').attr('courseid');
	var keywords = $('.search-teacher-infos').val();
	var copyType = $('#course-ischecked').attr('data-status');
	var teacherType = 1;
		if($('#org-teacher-list').attr('oldteacherarrid') == 0) {
			teacherIds = 0;
		}else {
			teacherIds = $('#org-teacher-list').attr('oldteacherarrid');
		}
	var params = {
		    page       : 1,
		    length     : 1000,
		    teacherIds : teacherIds,
		    keywords   : keywords,
			copyType   : copyType,
		    courseId   : courseId,
		    teacherType:teacherType
		};
	var orgTeacherTpl = $('#orgTeacherTpl').html();
	var checkedTeachersTpl = $('#checkedTeachersTpl').html();
		$.ajax({
			url: '/user/courseAjax/orgTeacher',
			type: 'post',
			dataType: 'json',
			data: JSON.stringify(params),
			success: function(r) {
				if(r.code == 0) {
					//根据实际的或者选择的老师来设置已经选中的老师情况
					r.result.checkedTeachers=[];
					$("#teacher-contents li").each(function(i,item){
						var t={"teacherId":parseInt($(item).attr("teacherid")), 'hasPlan':$(item).attr('hasplan'), "realName":$(item).attr("realname"), "thumbMed":$(item).attr("thumbmed")}
						r.result.checkedTeachers.push(t);
					});
					$('#multiple-right').html(Mustache.render(checkedTeachersTpl, r.result));
	       			$('#multiple-left').html(Mustache.render(orgTeacherTpl, r.result));
	       			$('.multiple-tip').hide();
	       			var orgTeacherId;
	       			$('#org-teacher-list').find('li').each(function() {
	       				orgTeacherId = $(this).attr('teacherid');
	       				$('#multiple-left').find('li').each(function() {
	       					if(orgTeacherId == $(this).attr('teacherid')) {
	       						$(this).addClass('selected');
	       					}
	       				})
	       			})
	       		}else {
	       			$('#multiple-left').html('<p class="fs12 tac">还没有老师哦！</p>');
	       			return false;
	       		}
			}
		})
}
function orgSearchTeacher() {
	var teacherIds;
	var courseId = $('#grobal-course').attr('courseid');
	var keywords = $('.search-teacher-infos').val();
	var copyType = $('#course-ischecked').attr('data-status');
		if($('#org-teacher-list').attr('oldteacherarrid') == 0) {
			teacherIds = 0;
		}else {
			teacherIds = $('#org-teacher-list').attr('oldteacherarrid');
		}
	var params = {
		    page       : 1,
		    length     : 1000,
		    teacherIds : teacherIds,
		    keywords   : keywords,
			copyType   : copyType,
		    courseId   : courseId
		};
	var orgTeacherTpl = $('#orgTeacherTpl').html();
	var checkedTeachersTpl = $('#checkedTeachersTpl').html();
		$.ajax({
			url: '/user/courseAjax/orgTeacher',
			type: 'post',
			dataType: 'json',
			data: JSON.stringify(params),
			success: function(r) {
				if(r.code == 0) {
					//根据实际的或者选择的老师来设置已经选中的老师情况
					r.result.checkedTeachers=[];
					$("#teacher-contents li").each(function(i,item){
						var t={"teacherId":parseInt($(item).attr("teacherid")), 'hasPlan':$(item).attr('hasplan'), "realName":$(item).attr("realnme"), "thumbMed":$(item).attr("thumbmed")}
						r.result.checkedTeachers.push(t);
					});
	       			$('#multiple-left').html(Mustache.render(orgTeacherTpl, r.result));
	       			$('.multiple-tip').hide();
	       			var orgTeacherId;
	       			$('#multiple-right').find('li').each(function() {
	       				orgTeacherId = $(this).attr('teacherid');
	       				$('#multiple-left').find('li').each(function() {
	       					if(orgTeacherId == $(this).attr('teacherid')) {
	       						$(this).addClass('selected');
	       						$(this).removeClass('select');
	       					}
	       				})
	       			})
	       		}else {
	       			$('#multiple-left,#assistant-multiple-left').html('<p class="fs12 tac">还没有老师哦！</p>');
	       			return false;
	       		}
			}
		})
}
//助教
function assistanArrTeacher() {
	var teacherIds;
	var courseId = $('#grobal-course').attr('courseid');
	var keywords = $('.ass-search-teacher-infos').val();
	var copyType = $('#course-ischecked').attr('data-status');
	var teacherType = 2;
		if($('#org-assistant-list').attr('oldassistanarr') == 0) {
			teacherIds = 0;
		}else {
			teacherIds = $('#org-assistant-list').attr('oldassistanarr');
		}
	var params = {
		    page       : 1,
		    length     : 1000,
		    teacherIds : teacherIds,
		    keywords   : keywords,
			copyType   : copyType,
		    courseId   : courseId,
		    teacherType:teacherType
		};
	var orgTeacherTpl = $('#orgTeacherTpl').html();
	var isGroupTeachersTpl = $('#isGroupTeachersTpl').html();
		$.ajax({
			url: '/user/courseAjax/orgTeacher',
			type: 'post',
			dataType: 'json',
			data: JSON.stringify(params),
			success: function(r) {
				if(r.code == 0) {
					//根据实际的或者选择的老师来设置已经选中的老师情况
					r.result.checkedTeachers=[];
					$("#teacher-assistant-contents li").each(function(i,item){
						var t={"teacherId":parseInt($(item).attr("teacherid")), 'isGroup':$(item).attr('isgroup'), "realName":$(item).attr("realname"), "thumbMed":$(item).attr("thumbmed")}
						r.result.checkedTeachers.push(t);
					});
	       			$('#assistant-multiple-left').html(Mustache.render(orgTeacherTpl, r.result));
	       			$('#assistant-multiple-right').html(Mustache.render(isGroupTeachersTpl , r.result));
	       			$('.multiple-tip').hide();
	       			var orgTeacherId;
	       			$('#org-assistant-list').find('li').each(function() {
	       				orgTeacherId = $(this).attr('teacherid');
	       				$('#assistant-multiple-left').find('li').each(function() {
	       					if(orgTeacherId == $(this).attr('teacherid')) {
	       						$(this).addClass('selected');
	       					}
	       				})
	       			})
	       		}else {
	       			$('#assistant-multiple-left').html('<p class="fs12 tac">还没有老师哦！</p>');
	       			return false;
	       		}
			}
		})
}
function assistanSearchTeacher() {
	var teacherIds;
	var courseId = $('#grobal-course').attr('courseid');
	var keywords = $('.ass-search-teacher-infos').val();
	var copyType = $('#course-ischecked').attr('data-status');
	var teacherType = 2;
		if($('#org-assistant-list').attr('oldassistanarr') == 0) {
			teacherIds = 0;
		}else {
			teacherIds = $('#org-assistant-list').attr('oldassistanarr');
		}
	var params = {
		    page       : 1,
		    length     : 1000,
		    teacherIds : teacherIds,
		    keywords   : keywords,
			copyType   : copyType,
		    courseId   : courseId,
		    teacherType:teacherType
		};
	var orgTeacherTpl = $('#orgTeacherTpl').html();
		$.ajax({
			url: '/user/courseAjax/orgTeacher',
			type: 'post',
			dataType: 'json',
			data: JSON.stringify(params),
			success: function(r) {
				if(r.code == 0) {
					//根据实际的或者选择的老师来设置已经选中的老师情况
					r.result.checkedTeachers=[];
					$("#teacher-assistant-contents li").each(function(i,item){
						var t={"teacherId":parseInt($(item).attr("teacherid")), 'isGroup':$(item).attr('isgroup'), "realName":$(item).attr("realname"), "thumbMed":$(item).attr("thumbmed")}
						r.result.checkedTeachers.push(t);
					});
	       			$('#assistant-multiple-left').html(Mustache.render(orgTeacherTpl, r.result));
	       			$('.multiple-tip').hide();
	       			var orgTeacherId;
	       			$('#org-assistant-list').find('li').each(function() {
	       				orgTeacherId = $(this).attr('teacherid');
	       				$('#assistant-multiple-left').find('li').each(function() {
	       					if(orgTeacherId == $(this).attr('teacherid')) {
	       						$(this).addClass('selected');
	       					}
	       				})
	       			})
	       		}else {
	       			$('#assistant-multiple-left').html('<p class="fs12 tac">还没有老师哦！</p>');
	       			return false;
	       		}
			}
		})
}
//地址
function getRegion() {
	var params= {
			regId : 0
		};
	var getRegionOneTpl = $("#getRegionOneTpl").html();
		$.ajax({
			url: '/user/supCourseAjax/GetRegion',
			type: 'post',
			dataType: 'json',
			data: JSON.stringify(params),
			success:function(r) {
				if(r.code == 0) {
					$('#get-address-levelOne').html(Mustache.render(getRegionOneTpl, r.result));
					$('#update-address-levelOne').html(Mustache.render(getRegionOneTpl, r.result));
				}
			}
		});
}
function getRegionTwo(obj) {
		$(obj).parents('.divselect').find('.cite-text').attr('regid', $(obj).attr('regid'));
	var regId = $(obj).attr('regid');
	var params = {
			regId : regId
		};
	var getRegionTwoTpl = $('#getRegionTwoTpl').html();
		$.ajax({
			url: '/user/supCourseAjax/GetRegion',
			type: 'post',
			dataType: 'json',
			data: JSON.stringify(params),
			success:function(r) {
				if(r.code == 0) {
					$('#get-course-address').show();
					$('#update-regLevelv1').show();
					$('#get-regLevelv1').show();
					$('#get-address-level1').text('请选择');
					$('#get-address-level1').attr('regid', '');
					$('#update-regLevelv2').hide();
					$('#get-regLevelv2').hide();
					$('#get-course-address').hide();
					$('#update-address-levelTwo').html(Mustache.render(getRegionTwoTpl, r.result));
					$('#get-address-levelTwo').html(Mustache.render(getRegionTwoTpl, r.result));
				}
			}
		});
}
function getRegionThree(obj) {
		$(obj).parents('.divselect').find('.cite-text').attr('regid', $(obj).attr('regid'));
	var regId = $(obj).attr('regid');
	var params = {
			regId : regId
		};
	var getRegionThreeTpl = $('#getRegionThreeTpl').html();
		$.ajax({
			url: '/user/supCourseAjax/GetRegion',
			type: 'post',
			dataType: 'json',
			data: JSON.stringify(params),
			success:function(r) {
				if(r.code == 0) {
					$('#get-regLevelv2').show();
					$('#update-regLevelv2').show();
					$('#get-address-level2').text('请选择');
					$('#get-address-level2').attr('regid', '');
					$('#update-address-levelThree').html(Mustache.render(getRegionThreeTpl, r.result));
					$('#get-address-levelThree').html(Mustache.render(getRegionThreeTpl, r.result));
				}else {
					$('#update-regLevelv2').hide();
					$('#get-regLevelv2').hide();
					$('#get-course-address').show();
				}
			}
		});
}
function getRegionLast(obj) {
	$('#get-course-address').show();
	$('#get-course-address').val('');
	$(obj).parents('.divselect').find('.cite-text').attr('regid', $(obj).attr('regid'));
	$('#get-regLevel2').find('.cite-text').attr('regid', $(obj).attr('regid'));
}
//班级名字
function classList() {
    var params = {
       		courseId : courseId
   		};
    var getClassListTpl = $('#getClassListTpl').html();
	    $.ajax({
	        url: '/user/supCourseAjax/ClassList',
	        type: 'post',
	        dataType: 'json',
	        data: JSON.stringify(params),
	        success:function(r) {
	            if(r.code == 0) {
	                $('#get-class-list').html(ejs.render(getClassListTpl, { data:r.result }));
	            }
	        }
	    });
}
//member
function orgMemberSet() {
	var getMemberSetTpl = $('#getMemberSetTpl').html();
		$.ajax({
			url: '/user/courseAjax/orgMemberSet',
			type: 'post',
			dataType: 'json',
			data: '',
			success:function(r) {
				if(r.code == 0) {
					$('#get-course-memberSet').html(Mustache.render(getMemberSetTpl, r.result));
				}
			}
		})
}
//添加基本信息
function addCourse() {
	var token = $('#grobal-course').attr('token');
	var title = $('#get-courseInfo-title').val();
	var firstCate = $('#get-firstCateName').attr('cateid');
	var secondCate = $('#get-course-secondCate').attr('cateid');
	var thirdCate = $('#get-course-thirdCate').attr('cateid');
	var attrValueIds = $('#slt-course-name').attr('dataid');
	var feeType = $('#pay-course-btn input[name="price"]:checked').val();
	var price;
		if($('#pay-course-btn input[name="price"]:checked').val() == 1) {
			if($('#add-course-price').val() < 1) {
				layer.msg('价格请输入大于1');
				return false;
			}else{
				price = $('#add-course-price').val();
			}
		}else {
			price = 0;
		};
	var isMember = $('#select-member input:radio[name="member"]:checked').val();
	var tagNameArr = [];
		$('.label-dropdown-input .dropdown-show-tab').each(function() {
			tagNameArr.push($.trim($(this).text()));
		});
	var setIds = [];
		$('.member-course input').each(function() {
			if($(this).is(':checked')) {
				setIds.push($(this).attr('memberid'));
			}
		});
	var teacherArrId =[];
		$('#org-teacher-list').find('.dropdown-cents').each(function() {
			teacherArrId.push($(this).attr('teacherid'));
		});
	var assistanTeacherArrId = [];
		$('#org-assistant-list').find('.dropdown-cents').each(function() {
			assistanTeacherArrId.push($(this).attr('teacherid'));
		});
	var flag = $('#get-attr-category').attr('data-flag');
	var params = {
			type          : type,
			courseAddToken: token,
			title         : title,
			firstCate     : firstCate,
			secondCate    : secondCate,
			thirdCate     : thirdCate,
			attrValueIds  : attrValueIds,
			feeType       : feeType,
			price         : price,
			setIds        : setIds,
			teacherArrId  : teacherArrId,
			assistanTeacherArrId: assistanTeacherArrId,
			isMember      : isMember,
			tagNameArr    : tagNameArr,
			flag          : flag
		};
		$.ajax({
			url: '/user/courseAjax/addCourse',
			type: 'post',
			dataType: 'json',
			data: JSON.stringify(params),
			success:function(r) {
				if(r.code == 0) {
					layer.msg(r.message);
				}else {
					$('#add-course-info-btn').attr('disabled', true);
					$('#add-course-info-btn').removeClass('green-button').addClass('gray-button');
					window.location.href="/org.course.SetDesc";
				}
			}
		});
}
//添加修改基本信息
function editCourse() {
    var type = $('#grobal-course').attr('type');
  	var token = $('#grobal-course').attr('token');
	var title = $('#get-courseInfo-title').val();
	var firstCate = $('#get-firstCateName').attr('cateid');
	var secondCate = $('#get-course-secondCate').attr('cateid');
	var thirdCate = $('#get-course-thirdCate').attr('cateid');
	var attrValueIds = $('#slt-course-name').attr('dataid');
	var price = $('.price-course').val();
	var feeType = $('#pay-course-btn input:radio[name="price"]:checked').val();
	var priceOld = $('.price-course').attr('data-price');
	var feeTypeOld = $('#freeTypeOld').val();
	var isPromote = $('#freeTypeOld').attr('promote');
	var isMember;
		if($('#grobal-course').attr('type') == 3) {
			isMember = 0;
		}else {
			isMember = $('#select-member input:radio[name="member"]:checked').val();
		}
	var teacherArrId = [], oldTeacherArrId = [];
		$('#org-teacher-list').find('.dropdown-cents').each(function() {
			teacherArrId.push($(this).attr('teacherid'));
		});
		oldTeacherId = $('#org-teacher-list').attr('oldteacherarrid');
		oldTeacherArrId.push(oldTeacherId);
	var assistanTeacherArrId = [], assistanOldTeacherArrId = [];
		$('#org-assistant-list').find('.dropdown-cents').each(function() {
			assistanTeacherArrId.push($(this).attr('teacherid'));
		});
		assistanOldTeacherArrId.push($('#org-assistant-list').attr('oldassistanarr'));
	var setIds = [];
		$('.member-course').find('input').each(function() {
			if($(this).is(':checked')) {
				setIds.push($(this).attr('memberid'));
			}
		});
	var tagNameArr =[], oldTagNameArr = [];
		$('#label-content').find('.dropdown-show-tab').each(function() {
			tagNameArr.push($.trim($(this).text()));
		});
		oldTagName = $('#label-content').attr('oldtagename');
		oldTagNameArr.push(oldTagName);
	var flag = $('#get-attr-category').attr('data-flag');
	var params = {
	        courseEditToken: token,
			type           : type,
			courseId       : courseId,
			title          : title,
			firstCate      : firstCate,
			secondCate     : secondCate,
			thirdCate      : thirdCate,
			isMember       : isMember,
			attrValueIds   : attrValueIds,
			price          : price,
			feeType        : feeType,
			feeTypeOld     : feeTypeOld,
			priceOld       : priceOld,
			isPromote      : isPromote,
			teacherArrId   : teacherArrId,
			oldTeacherArrId: oldTeacherArrId,
			assistanTeacherArrId : assistanTeacherArrId,
			assistanOldTeacherArrId: assistanOldTeacherArrId,
			tagNameArr     : tagNameArr,
			oldTagNameArr  : oldTagNameArr,
			setIds         : setIds,
			flag           : flag
		};
		$.ajax({
			url: '/user/courseAjax/editCourse',
			type: 'post',
			dataType: 'json',
			data: JSON.stringify(params),
			success:function(r) {
				if(r.code == 1) {
					$('#add-course-info-btn,#update-course-info').attr('disabled', true);
					$('#add-course-info-btn,#update-course-info').removeClass('green-button').addClass('gray-button');
					layer.msg('修改成功');
				}else {
					layer.msg(r.message);
				}
			}
		})
}
//基本信息读取数据
function courseInfo() {
	var courseId = $('#grobal-course').attr('courseid');
	var oldTeacherArrId = [], oldTagNameArr = [], assistanOldTeacherArrId = [];
	var orgGetTeacherTpl = $('#orgGetTeacherTpl').html();
	var getsetIdsTpl = $('#getsetIdsTpl').html();
	var getAttrArrTpl = $('#getAttrArrTpl').html();
	var getFirstCateNameTpl = $('#getFirstCateNameTpl').html();
	var getSecondCateNameTpl = $('#getSecondCateNameTpl').html();
	var getThirdCateNameTpl = $('#getThirdCateNameTpl').html();
	var getProvinceTpl = $('#getProvinceTpl').html();
	var getCityTpl = $('#getCityTpl').html();
	var getCountryTpl = $('#getCountryTpl').html();
	var assistanArrTpl = $('#assistanArrTpl').html();

	var params = {
			courseId : courseId
		};
		$.ajax({
			url: '/user/courseAjax/courseinfo',
			type: 'post',
			dataType: 'json',
			data: JSON.stringify(params),
			success:function(r) {
				if(r.code == 3002) {
					return false;
				}else {
					if(r.result.type == 1) { //直播课
						$('#get-parent-setIds').show();
						$('#get-course-memberSet').html(Mustache.render(getsetIdsTpl, r.result));
						if(r.result.isMember == 1) {
							$('.member-course').show();
							$('#select-member').find('input[value="1"]').attr('checked', true);
						}else {
							$('.member-course').hide();
							$('#select-member').find('input[value="0"]').attr('checked', true);
						}
						$('.member-course').find('input').each(function() {
							if($(this).attr('checkedid') == 0){
								$(this).attr('checked', false);
							}else {
								$(this).attr('checked', true);
								$('.member-course').show();
							}
						});
					}else if(r.result.type == 2) { //录播课
						$('#get-parent-setIds').show();
						$('#get-course-memberSet').html(Mustache.render(getsetIdsTpl, r.result));
						if(r.result.isMember == 1) {
							$('.member-course').show();
							$('#select-member').find('input[value="1"]').attr('checked', true);
						}else {
							$('.member-course').hide();
							$('#select-member').find('input[value="0"]').attr('checked', true);
						}
						$('.member-course').find('input').each(function() {
							if($(this).attr('checkedid') == 0){
								$(this).attr('checked', false);
							}else {
								$(this).attr('checked', true);
								$('.member-course').show();
							}
						});
					}else { //线下课
						$('#get-parent-setIds').hide();
					};

					if(r.result.feeType == 1) {
						$('.free-course input[type="radio"]').prop('checked', false);
						$('.pay-course input[type="radio"]').prop('checked', true);
						$('.price-course').show();
						$('.price-course').val(r.result.price);
					}else {
						$('.free-course input[type="radio"]').prop('checked', true);
						$('.pay-course input[type="radio"]').prop('checked', false);
						$('.price-course').hide();
						$('#get-parent-setIds').hide()
					}
					$('.price-course').attr('data-price',r.result.price);
					$('#grobal-course').attr('courseType', r.result.type);
					$('#get-courseInfo-title').val(r.result.title);
					$('#freeTypeOld').val(r.result.feeTypeOld);
					$('#freeTypeOld').attr('Promote', r.result.isPromote);
					$('#slt-course-name').attr('dataid', r.result.attrId);
					if(r.result.attrName == '') {
						$('#get-attr-category').hide();
						$('#get-attr-category').attr('data-flag', '0');
					}else {
						$('#slt-course-name').val(r.result.attrName);
						$('#get-attr-category').show();
						$('#get-attr-category').attr('data-flag', '1');
					}
					$('#get-class-info').show();
					$('#get-category-info').show();
					if(r.result.teacherArr == '') {
						$('#org-teacher-list').attr('oldteacherarrid', 0);
						$('#org-teacher-list').hide();
					}else {
						$('#org-teacher-list').hide();
						$('.add-teacher-title').hide();
						$('#org-teacher-list').html(Mustache.render(orgGetTeacherTpl, r.result));
						$('#teacher-contents').html(Mustache.render(orgGetTeacherTpl, r.result));
					}
					if(r.result.assistanArr == '' || r.result.assistanArr == null) {
						$('#org-assistant-list').attr('oldassistanArr', 0);
					}else{
						$('#org-assistant-list,#teacher-assistant-contents').html(Mustache.render(assistanArrTpl, r.result));
						$('#org-assistant-list').find('li').each(function() {
							assistanOldTeacherArrId.push($(this).attr('teacherid'));
						});
						$('#org-assistant-list').attr('oldassistanArr', assistanOldTeacherArrId);
					}
					$('#label-content').html(Mustache.render(getAttrArrTpl, r.result));
					$('#get-cate').html(Mustache.render(getFirstCateNameTpl, r.result));
					$('#get-cate-class').html(Mustache.render(getSecondCateNameTpl, r.result));
					$('#get-cate-name').html(Mustache.render(getThirdCateNameTpl, r.result));
					$('#get-firstCateName').text($('#get-cate').find('a[checkedid="1"]').text());
					$('#get-firstCateName').attr('cateid', $('#get-cate').find('a[checkedid="1"]').attr('cateid'));
					$('#get-course-secondCate').text($('#get-cate-class').find('a[checkedid="1"]').text());
					$('#get-course-secondCate').attr('cateid', $('#get-cate-class').find('a[checkedid=1]').attr('cateid'));
					$('#get-course-thirdCate').text($('#get-cate-name').find('a[checkedid="1"]').text());
					$('#get-course-thirdCate').attr('cateid', $('#get-cate-name').find('a[checkedid=1]').attr('cateid'));
					$('#org-teacher-list').find('li').each(function() {
						oldTeacherArrId.push($(this).attr('teacherid'));
					});
					$('#label-content').find('.dropdown-show-tab').each(function() {
						oldTagNameArr.push($.trim($(this).text()));
					});
					$('#label-content').attr('oldTageName', oldTagNameArr);
					$('#org-teacher-list').attr('oldTeacherArrId', oldTeacherArrId);
					$('#label-content').find('.dropdown-show-tab').each(function() {
						var olderTagName = $.trim($(this).text());
						$('#dropdown-box').find('.dropdown-tab').each(function() {
							if(olderTagName == $.trim($(this).text())){
								$(this).addClass('on');
							}
						})
					})
					if($('#label-content').find('.dropdown-show-tab').length >= 3) {
						$('.course-name-ipt').hide();
					}else{
						$('.course-name-ipt').show();
					}
				}
			}
		})
}
//复制课程
function getMaxCourseId(type) {
	var params = {
		courseType : type
	};
	$.ajax({
		url: '/user.courseAjax.GetMaxCourseId',
		type: 'post',
		dataType: 'json',
		data: JSON.stringify(params),
		success:function(r) {
			$('#grobal-course').attr('courseid', r);
		}
	});
}
//设置课程封面
function addSetCourseDesc(obj) {
	var addImgCourseId = $(obj).attr('courseid');
	var progress = $("#progress").attr('filepercent');
	var scope = $('#range-scope').val();
	var descript = um.getContent();
	var x = $('input[name="x"]').val();
	var y = $('input[name="y"]').val();
	var w = $('input[name="w"]').val();
	var h = $('input[name="h"]').val();
	var x2 = $('input[name="x2"]').val();
	var y2 = $('input[name="y2"]').val();
	var params = {
		courseId : addImgCourseId,
		scope    : scope,
		descript : descript,
		x        : x,
		y        : y,
		w        : w,
		h        : h,
		x2       : x2,
		y2       : y2
	};
	$.ajax({
		url: '/user/courseAjax/setCourseDetail',
		type: 'post',
		dataType: 'json',
		data: JSON.stringify(params),
		success:function(r) {
			if(r.code == 0){
				window.location.href="/org.course.AddPlan";
			}else{
				layer.msg(r.message);
			}
		}
	});
}
//保存课程图片
function setCourseImg() {
	var filePercent = $('#progress').attr('filepercent');
	var x = $('input[name="x"]').val();
	var y = $('input[name="y"]').val();
	var w = $('input[name="w"]').val();
	var h = $('input[name="h"]').val();
	var x2 = $('input[name="x2"]').val();
	var y2 = $('input[name="y2"]').val();
	var params = {
		courseId : courseId,
		       x : x,
		       y : y,
		       w : w,
		       h : h,
		       x2: x2,
		       y2: y2
		};

		if(filePercent != 100) {
			layer.msg('请上传课程图片');
		}else {
			$.ajax({
				url: '/user/courseAjax/SetCourseImg',
				type: 'post',
				dataType: 'json',
				data: JSON.stringify(params),
				success:function(r) {
					if(r.code ==0){
						$('#uploadImg').text('上传图片');
						layer.msg('保存成功');
						locationReload();
					}
				}
			});
		}
}
//确定文件上传
function uploadFile(obj) {
    var classId = $('#upload-files-btn').attr('classid');
	var courseId = $('#upload-files-btn').attr('courid');
    var typeArray = [], thumbArray = [], attachArray = [], titleArray = [], attachUrl;
        $('#file-list').find('.file-type').each(function() {
            typeArray.push($(this).val());
        });
        $('#file-list').find('.file-thumb').each(function() {
            thumbArray.push($(this).val());
        });
        $('#file-list').find('.file-attach').each(function() {
    		attachUrl = $(this).val();
    		attachUrl = attachUrl.split('/');
    		attachUrl = attachUrl[attachUrl.length - 1];
        	attachArray.push(attachUrl);
        });
        $('#file-list').find('.file-title').each(function() {
            titleArray.push($(this).val());
        });

    var params = {
	        classId : classId,
	        type    : typeArray,
	        title   : titleArray,
	        thumb   : thumbArray,
	        attach  : attachArray
    	};

        $.ajax({
            url: '/user/supCourseAjax/uploadFile',
            type: 'post',
            dataType: 'json',
            data: JSON.stringify(params),
            success: function(r) {
                if(r.code == -1) {
                    layer.msg(r.message);
                }else {
                    $('#select-upload-btn').show();
                    $('#request-upload-btn').hide();
					window.location.href="/user.teacher.filelist."+courseId+"."+classId;
                    //locationReload();
                }
            }
        });
}
//删除文件
function delPlanAtt(obj) {
    var classId = $(obj).attr('classid');
	var courseId = $('#upload-files-btn').attr('courid');
    var planAttId = $(obj).attr('planattid');
    var delPlanParent = $(obj).parents('.filename');
    var params = {
	        classId : classId,
	        planAttId : planAttId
   		};
	    $.ajax({
	        url: '/user/supCourseAjax/delPlanAtt',
	        type: 'post',
	        dataType: 'json',
	        data: JSON.stringify(params),
	        success:function(r) {
	            if(r.code == 0) {
	                delPlanParent.remove();
	            }else {
	                layer.msg(r.error);
	            }
	        }
	    });
	    if($('#get-base-file').find('.filename').length <= 1) {
			window.location.href="/user.teacher.filelist."+courseId+"."+classId;
	    	//locationReload();
	    }
}
/**填写新章节或修改章节
* errorCallback 返回错误的值
* 保存数据时候调用
* 判断addMorePlanInfo是否存在class
* plan.add中存在addMorePlanInfo
*/
function setPlan(errorCallback) {
	var type = $('#grobal-course').attr('type');
	var addMorePlanInfo = $('#addMorePlanInfo');
	if(!addMorePlanInfo.hasClass('addMorePlanInfo')) {
		var regLevel0 = $('#get-address-level0').attr('regid');
		var regLevel1 = $('#get-address-level1').attr('regid');
		var regLevel2 = $('#get-address-level2').attr('regid');
		var address = $('#get-course-address').val();
		var classId, className, classTeacher, classNum;
		var data = [], order_on, name, startTime, endTime, planId, teacherId, livePublicType, videoPublicType, videoTrialTime;
		classId = $('#section-class-name').attr('classid');
		className = $('#section-class-name').val();
		classTeacher = $('#update-section-class-teacher').find('.cite-text').attr('teacherid');
		classNum = $('#section-class-number').val();
		$('#wrap-plan-edit').find('li').each(function() {
			order_on = $(this).find('.global-sectId').attr('data-l');
			name = $(this).find('.update-sectionDesc').val();
            planId = $(this).find('.global-sectId').attr('planid');
			teacherId = $(this).find('.teacher-select').find('.cite-text').attr('teacherid');
			if(type == 1) {
				livePublicType = $(this).find('.livetype-text').attr('livetype');
				videoPublicType = $(this).find('.videotype-text').attr('videotype');
				videoTrialTime = $(this).find('.videotype-text').attr('videotime');
			}else if(type == 2){
				livePublicType = 0;
				videoPublicType = $(this).find('.videotype-text').attr('videotype');
				videoTrialTime = $(this).find('.videotype-text').attr('videotime');
			}else {
				livePublicType = 0;
				videoPublicType = 0;
				videoTrialTime = 0;
			}
			if(type !=2) {
				startTime = $(this).find('.update-startTime').val();
				endTime = $(this).find('.update-endTime').val();
			}else {
				startTime = 0;
				endTime = 0;
			}
			data.push({'order_on':order_on, 'name':name, 'startTime':startTime, 'endTime':endTime, 'planId':planId, 'teacherId':teacherId, 'livePublicType':livePublicType, 'videoPublicType':videoPublicType, 'videoTrialTime':videoTrialTime});
 		});
	}else {
		var regLevel0 = $('#get-address-level0').attr('regid');
		var regLevel1 = $('#get-address-level1').attr('regid');
		var regLevel2 = $('#get-address-level2').attr('regid');
		var address = $('#get-course-address').val();
		var classId, className, classTeacher, classNum;
		var data = [], order_on, name, startTime, endTime, planId, teacherId, livePublicType, videoPublicType, videoTrialTime;
		classId = $('#section-class-name').attr('classid');
		className = $('#section-class-name').val();
		classTeacher = $('#section-class-teacher-name').find('.cite-text').attr('teacherid');
		classNum = $('#section-class-number').val();
		$('#article-list').find('li.planList').each(function() {
			order_on = $(this).find('.global-sectId').attr('data-l');
			name = $.trim($(this).find('.update-sectionDesc').val());
            planId = $(this).find('.global-sectId').attr('planid');
			teacherId = $(this).find('.teacher-select').find('.cite-text').attr('teacherid');
			if(type == 1) {
				livePublicType = $(this).find('.livetype-text').attr('livetype');
				videoPublicType = $(this).find('.videotype-text').attr('videotype');
				videoTrialTime = $(this).find('.videotype-text').attr('videotime');
			}else if(type == 2){
				livePublicType = 0;
				videoPublicType = $(this).find('.videotype-text').attr('videotype');
				videoTrialTime = $(this).find('.videotype-text').attr('videotime');
			}else {
				livePublicType = 0;
				videoPublicType = 0;
				videoTrialTime = 0;
			}
			if(type !=2) {
				startTime = $(this).find('.update-startTime').val();
				endTime = $(this).find('.update-endTime').val();
			}else {
				startTime = 0;
				endTime = 0;
			}

			data.push({'order_on':order_on, 'planId':planId, 'name':name, 'startTime':startTime, 'endTime':endTime, 'teacherId':teacherId, 'livePublicType':livePublicType, 'videoPublicType':videoPublicType, 'videoTrialTime':videoTrialTime});
		});
	}
	var params = {
			courseId     : courseId,
			classId      : classId,
			className    : className,
			classTeacher : classTeacher,
			classNum     : classNum,
			regLevel0    : regLevel0,
			regLevel1    : regLevel1,
			regLevel2    : regLevel2,
			address      : address,
			data         : data
		};
		$.ajax({
			url: '/user/planAjax/setPlan',
			type: 'post',
			dataType: 'json',
			data: JSON.stringify(params),
			success:function(r) {
				if(r.code == 0) {
				//right
					if(addMorePlanInfo.hasClass('addMorePlanInfo')) {
						$('.plan-add-val').attr('classid', r.classId);
						$('.plan-add-val').attr('data-id', r.classId);
					}
					$('#grobal-course').attr('classId', r.classId);
					errorCallback(r.code);
					$('#update-plan-list,#add-course-plan-btn').attr('disabled', true);
					$('#update-plan-list,#add-course-plan-btn').removeClass('green-button').addClass('gray-button');
				}else {
					//error
					if(addMorePlanInfo.hasClass('addMorePlanInfo')) {
						$('.plan-add-val').attr('classid', r.classId);
						$('.plan-add-val').attr('data-id', r.classId);
					}
					$('#grobal-course').attr('classId', r.classId);
					errorCallback(r.code);
					layer.msg(r.message);
					$('#update-plan-list,#add-course-plan-btn').attr('disabled', false);
					$('#update-plan-list,#add-course-plan-btn').removeClass('gray-button').addClass('green-button');
				}
			}
		});
}
//teacher
function courseTeacher() {
	var courseTeacherTpl = $('#courseTeacherTpl').html();
	var params = {
	    	courseId : courseId
		};
		$.ajax({
		    url: '/user/supCourseAjax/CourseTeacher',
		    type: 'post',
		    dataType: 'json',
		    data: JSON.stringify(params),
		    success:function(r) {
		        if(r.code == 0) {
					$('#select-tutor-list').html(Mustache.render(courseTeacherTpl, r.result));
		            $('.select-teacher-list').html(Mustache.render(courseTeacherTpl, r.result));
		        }
		    }
		});
};
function selectCourseTeacher(obj) {
	$(obj).parents('.divselect').find('.cite-text').attr('teacherid', $(obj).attr('teacherid'));
	$(obj).parents('.divselect').find('.cite-text').text($.trim($(obj).text()));
};
(function() {
    $('#update-planEdit').on('click', '.live-select a', function() {
        $(this).parents('.live-select').attr('livetype', $(this).attr('livetype'));
        $(this).parents('.live-select').find('.livetype-text').text($(this).text());
    });

    $('#update-planEdit').on('click', '.video-select a', function() {
        $(this).parents('.video-select').attr('videotype', $(this).attr('videotype'));
        $(this).parents('.video-select').attr('videotime', $(this).attr('videotime'));
        $(this).parents('.video-select').find('.videotype-text').text($(this).text());
    });
    $('#selectTweekType').on('click', 'a', function() {
        $(this).parents('.divselect').find('.cite-text').text($.trim($(this).text()));
		$(this).parents('.divselect').find('.cite-text').attr('data-id', $(this).attr('data-id'));
		if($(this).attr('data-id') == 3) {
			$('#select-more-time').show();
			$('#select-addTime').show();
		}else {
			$('#select-more-time').hide();
			$('#select-addTime').hide();
			$('#select-addTime').html('');
		}
    })
    $('#selectLongType').on('click', 'a', function() {
        $(this).parents('.divselect').find('.cite-text').text($.trim($(this).text()));
		$(this).parents('.divselect').find('.cite-text').attr('data-id', $(this).attr('data-id'));
		if($(this).attr('data-id') == 7) {
			$('#select-more-minute').show();
		}else {
			$('#select-more-minute').hide();
			$('#select-more-minute').val('');
		}
    });
})();
//批量添加章节
function quickSetCourseInfo() {
	var type = $('#quicksetCourse-btn').attr('type');
	var data = [], params, classId, weekType, startTime, teacherId, longType, myLongTime, myTimes, name, orderName, videoPublicType, videoTrialTime;
	classId = $('#section-class-name').attr('classid');
	if(type == 2) {
		videoPublicType = 0;
		videoTrialTime = 0;
	}
	weekType = $('#selectTweekType').find('.cite-text').attr('data-id');
	if(weekType == 3) {
		myTimes = $('#select-more-time').attr('mytimes');
	}else {
		myTimes = 0;
	};
	startTime = $('.startime-plan-course').val();
	teacherId = $('.section-class-teacher-name').find('.cite-text').attr('teacherid');
	longType = $('#selectLongType').find('.cite-text').attr('data-id');
	if(longType == 7) {
		myLongTime = $('#select-more-minute').val();
	}else {
		myLongTime = 0;
	};
    if($('#plan-add-desc').val() == '') {
        layer.msg('章节内容为空');
        locationReload();
        return false;
    }
	orderName = $('#plan-add-desc').val().split("\n");
	for(var i = 0; i < orderName.length; i++) {
		if(orderName[i] == '') {

		}else {
			data.push({'name':orderName[i]})
		}
	};
	params = {
		courseId        : courseId,
		classId         : classId,
		weekType        : weekType,
		startTime       : startTime,
		videoPublicType : videoPublicType,
		videoTrialTime  : videoTrialTime,
		teacherId       : teacherId,
		longType        : longType,
		myLongTime      : myLongTime,
		myTimes         : myTimes,
		data            : data

	};
	setTimeout(function() {
	$.ajax({
		url: '/user/planAjax/QuicksetCourse',
		type: 'post',
		dataType: 'json',
		data: JSON.stringify(params),
		success:function(r) {
			if(r.code == 0) {
				$('#quicksetCourse-btn').attr('disabled', 'true');
				$('#quicksetCourse-btn').addClass('gray-button').removeClass('green-button');
				locationReload();
			}
		}
	});
	}, 300);
}
function quicksetCourse(obj) {
	if(type != 2) {
		if($('#plan-add-desc').val() == '') {
			layer.msg('请填写章节名称');
			$(obj).attr('disabled', false);
			return false;
		}else if($('.section-class-teacher-name').find('.cite-text').attr('teacherid') == '') {
			layer.msg('请选择老师');
			$(obj).attr('disabled', false);
			return false;
		}else if($('.startime-plan-course').val() == '') {
			layer.msg('请填写开课时间');
			$(obj).attr('disabled', false);
			return false;
		}else if($('#selectTweekType').find('.cite-text').attr('data-id') == ''){
			layer.msg('请选择循环方式');
			$(obj).attr('disabled', false);
			return false;
		}else if($('#selectLongType').find('.cite-text').attr('data-id') == '') {
			layer.msg('请选择课程时长');
			$(obj).attr('disabled', false);
			return false;
		}else {
			quickSetCourseInfo();
			$(obj).attr('disabled', 'true');
			$(obj).addClass('gray-button').removeClass('green-button');
		}
	}else {
		if($('#plan-add-desc').val() == '') {
			layer.msg('章节名称为空');
			$(obj).attr('disabled', false);
			return false;
		}else if($('.section-class-teacher-name').find('.cite-text').attr('teacherid') == '') {
			layer.msg('没有选择老师');
			$(obj).attr('disabled', false);
			return false;
		}else {
			quickSetCourseInfo();
			$(obj).attr('disabled', 'true');
			$(obj).addClass('gray-button').removeClass('green-button');
		}
	}
}
function quicksetCourseCancle() {
	locationReload();
	layer.closeAll();
}
//删除多个章节日期
function deltDate(obj) {
	var myTime = [];
	objParents = $(obj);
	objParents.remove();
    $('#select-addTime').find('.dropdown-show-tab').each(function() {
        myTime.push($(this).text());
    });
    $('#select-more-time').attr('myTimes', myTime.join(','));
}
//删除班级
function delClass(obj) {
	var classId, liCurr, classTxt;
	delClassBtn = $('#delClassBtn');
	if(!delClassBtn.hasClass('delClassBtn')) {
		classId = $('#get-class-list').find('li.curr').attr('classid');
		classTxt = $('#get-class-list').find('li.curr').text();
	}else {
		classId = $('#add-class-name').attr('classid');
		classTxt = $('#add-class-name').val();
	}
	var params = {
		courseId : courseId,
		classId  : classId
	};
	layer.confirm('<p class="p20 tac">确定删除'+classTxt+'班吗？</p>', {
	  btn: ['确定','取消'],
	  title:['删除班级']
	}, function(){
		$.ajax({
			url: '/user/planAjax/DelClass',
			type: 'post',
			dataType: 'json',
			data: JSON.stringify(params),
			success:function(r) {
				if(r.code == 0) {
					layer.msg(r.message);
					locationReload();
				}else {
					layer.msg(r.message);
					locationReload();
				}
			}
		});
	}, function(){
	  layer.closeAll();
	});
}
//click(even)
$(function() {
	$('.hidden').hide();
	$('.divselect').hover(function(){
		$(this).css('border', '1px solid #198fee');
	}, function(){
		$(this).css('border', '0px solid #ccc');
	});
	$('.search-teacher-infos').focus(function() {
	 	$('#t-delt-btn').show();
	});
    $('#t-delt-btn').click(function() {
    	var status = $(this).attr('status');
    	$('.search-teacher-infos').val('');
    	$(this).hide();
    	orgSearchTeacher();
    });
	$('.ass-search-teacher-infos').focus(function() {
	 	$('#s-delt-btn').show();
	});
    $('#s-delt-btn').click(function() {
    	var status = $(this).attr('status');
    	$('.ass-search-teacher-infos').val('');
    	$(this).hide();
    	assistanSearchTeacher();
    });

    $('#add-morse-typeTwo').on('click', '.divselect a', function() {
    	$(this).parents('.divselect').find('.cite-text').attr('time', $(this).attr('time'));
    	$(this).parents('.divselect').find('.cite-text').attr('type', $(this).attr('type'));
    });
	$('#article-list,#wrap-plan-edit').on('click', '.live-select a', function(){
		$(this).parents('.divselect').find('.cite-text').text($(this).text());
		$(this).parents('.divselect').find('.cite-text').attr('livetype', $(this).attr('livetype'));
	});
    $('#article-list,#wrap-plan-edit').on('click', '.video-select a', function(){
		$(this).parents('.divselect').find('.cite-text').text($(this).text());
		$(this).parents('.divselect').find('.cite-text').attr('videotime', $(this).attr('videotime'));
		$(this).parents('.divselect').find('.cite-text').attr('videotype', $(this).attr('videotype'));
	});
	if($('.course-load-img').find('img').attr('type') == 2) {
		$('#course-top-lubo-icon').show();
		$('#course-top-taped-icon').hide();
	}else if($('.course-load-img').find('img').attr('type') == 3){
		$('#course-top-taped-icon').show();
		$('#course-top-lubo-icon').hide();
	}else {
		$('#course-top-taped-icon').hide();
		$('#course-top-lubo-icon').hide();
	}
	$('input.price-course').blur(function() {
		if($(this).val() < 1) {
			$(this).val('');
			layer.msg('价格请输入大于1');
		}
	});
});
//科目
;(function(){
	var $selectParent = $('#slt-course-name');
	var $selectParentElement = $('#selet-course-name')
	var $selectChildren = $selectParentElement.find('.selet-course-name');
	var $selectReqBtn = $selectParentElement.find('.set-req-btn');
	var $selectCancleBtn = $selectParentElement.find('.set-more-cancle');

		$selectChildren.on('click', '.element-list', function() {
				if($(this).hasClass('seleted')) {
					$(this).removeClass('seleted');
				}else {
					$(this).addClass('seleted');
				var selectedAttrId = $(this).attr('attrid');
				var selectedAttrName = $(this).text();
				var $seletedLength = $('.selet-course-name').find('.seleted').length;
						if($seletedLength > 3) {
							$(this).removeClass('seleted');
							layer.msg('最多选择三个科目');
						}else {
							$(this).addClass('seleted');
						}
				}
		});

		$selectReqBtn.on('click' ,function() {
			var selectAttrNameArray = [];
			var selectAttrIdArray = [];
			$selectChildren.find('.element-list').each(function() {
				if($(this).hasClass('seleted')) {
					var selectAttrId = $(this).attr('attrid');
					var selectAttrName = $(this).text();
						selectAttrIdArray.push(selectAttrId);
						selectAttrNameArray.push(selectAttrName);
				}
			});

			var selectAttrDataId = selectAttrIdArray.join(',');
			var selectAttrDataName = selectAttrNameArray.join(',');
				$('#slt-course-name').val(selectAttrDataName);
				$('#slt-course-name').attr('dataId', selectAttrDataId);
			layer.closeAll();
		});
		$selectCancleBtn.on('click' ,function() {
			layer.closeAll();
		});
})()
//教师
;(function() {
	var $selectParentId = $('#multiple-select-list');
	var $selectAddChildren = $('#add-btn');
	var $selectDelChildren = $('#del-btn');
	var $selectMultipleRt = $('#multiple-right');
	var $selectMultipleLt = $('#multiple-left');
	var $selectCourseAdd = $('#course_add');
	var $selectCourseCancle = $('#course_cel');

		$selectMultipleLt.on('click', 'li:not(.selected)', function() {
	        if($(this).hasClass('select')){
	            $(this).removeClass('select');
	        }else{
	        	$(this).addClass('select');
	        	$selectMultipleLt.css('border','1px solid #ccc');
	        }
	        if($selectMultipleRt.find('li.select').length > 0){
	            $selectDelChildren.addClass('allow');
	        }else{
	            $selectDelChildren.removeClass('allow');
	        }
	        if($selectMultipleLt.find('li.select').length > 0 ){
	            $selectAddChildren.addClass('allow');
	        }else{
	            $selectAddChildren.removeClass('allow');
	        }
		});

		$selectMultipleRt.on('click', 'li:not(.selected)', function() {
			var hasplan = $(this).attr('hasplan');
			if(hasplan == 1) {
				$(this).find('span').text('有排课');
				$(this).find('span').show();
			}else{
		        if($(this).hasClass('select')){
		            $(this).removeClass('select');
		        }else{
		        	$(this).addClass('select');
		        }
			}
	        if($selectMultipleRt.find('li.select').length > 0){
	            $selectDelChildren.addClass('allow');
	        }else{
	            $selectDelChildren.removeClass('allow');
	        }
	        if($selectMultipleLt.find('li.select').length > 0 ){
	            $selectAddChildren.addClass('allow');
	        }else{
	            $selectAddChildren.removeClass('allow');
	        }
		});

		$selectAddChildren.click(function() {
	        if($(this).hasClass('allow')){
	            var cHtml='';
		            $selectMultipleLt.find('li.select').each(function() {
		                cHtml += '<li class="dropdown-cents" teacherId="'+$(this).attr('teacherId')+'" ';
						cHtml += ' thumbMed="'+$(this).attr("thumbMed")+'"'
						cHtml += ' realName="'+$(this).attr("realName")+'"'
		                cHtml += '>'+$(this).html()+'</li>';
						$(this).removeClass('select');
						$(this).addClass('selected');
		            });
		            $selectMultipleRt.append(cHtml);
		            $selectMultipleRt.find('.defalut').remove();
		            $('.multiple-tip').hide();
		            if($selectMultipleLt.find('li').length == 0){
		                $(this).removeClass('allow');
		            }
	        }else{
	            $selectMultipleLt.css('border','1px solid #ffa91e');
	        }
		})

		$selectDelChildren.click(function() {
	        if($(this).hasClass('allow')){
	            var cHtml='';
		            $selectMultipleRt.find('li.select').each(function() {
						var id = $(this).attr('teacherId');
		                cHtml += '<li class="dropdown-cents" teacherId="'+id+'">'+$(this).html()+'<div class="tab-delete"></div></li>';
		                $(this).remove();
						$selectMultipleLt.find('.selected').each(function() {
							if($(this).attr('data-id') == id){
								$(this).removeClass('selected');
							}
						})
		            });
		            if($selectMultipleRt.find('li').length == 0) {
		                $(this).removeClass('allow');
		                $selectMultipleRt.html('');
		                $('.multiple-tip').show();
		            }
		            $selectMultipleLt.find('li').removeClass('selected');
					$selectMultipleRt.find('li').each(function() {
						var rtTeacherId = $(this).attr('teacherid');
			            $selectMultipleLt.find('li').each(function() {
			            	if(rtTeacherId == $(this).attr('teacherid')) {
	            				$(this).addClass('selected');
	            			}
			            })
		            })
	        }else{
	            layer.msg('没有选择老师');
	        }
		})

		$selectCourseAdd.click(function() {
		/*
			if($('#multiple-right').find('.dropdown-cents').length >= 11) {
				layer.msg('最多选择十位老师');
				return false;
			}else {
				$('#org-teacher-list').html($selectMultipleRt.html());
				$('#teacher-contents').html($selectMultipleRt.html())
				$('#org-teacher-list').hide();
				$('.add-teacher-title').hide();
				$('#org-teacher-list').find('span').hide();
				$('#teacher-contents').find('span').hide();
				layer.closeAll();
				return false;
			}
		*/
				$('#org-teacher-list').html($selectMultipleRt.html());
				$('#teacher-contents').html($selectMultipleRt.html())
				$('#org-teacher-list').hide();
				$('.add-teacher-title').hide();
				$('#org-teacher-list').find('span').hide();
				$('#teacher-contents').find('span').hide();
				layer.closeAll();
				return false;
		})
		$selectCourseCancle.click(function() {
			layer.closeAll();
		})
})()
//助教
;(function() {
	var $assistantselectParentId = $('#assistant-select-list');
	var $assistantselectAddChildren = $('#assistant-add-btn');
	var $assistantselectDelChildren = $('#assistant-del-btn');
	var $assistantselectMultipleRt = $('#assistant-multiple-right');
	var $assistantselectMultipleLt = $('#assistant-multiple-left');
	var $assistantselectCourseAdd = $('#assistant_course_add');
	var $assistantselectCourseCancle = $('#assistant_course_cel');

		$assistantselectMultipleLt.on('click', 'li:not(.selected)', function() {
	        if($(this).hasClass('select')){
	            $(this).removeClass('select');
	        }else{
	        	$(this).addClass('select');
	        	$assistantselectMultipleLt.css('border','1px solid #ccc');
	        }
	        if($assistantselectMultipleRt.find('li.select').length > 0){
	            $assistantselectDelChildren.addClass('allow');
	        }else{
	            $assistantselectDelChildren.removeClass('allow');
	        }
	        if($assistantselectMultipleLt.find('li.select').length > 0 ){
	            $assistantselectAddChildren.addClass('allow');
	        }else{
	            $assistantselectAddChildren.removeClass('allow');
	        }
		});

		$assistantselectMultipleRt.on('click', 'li', function() {
			var isgroup = $(this).attr('isgroup');
			if(isgroup == 1) {
				$(this).find('span').text('有分组');
				$(this).find('span').show();
		        if($(this).hasClass('select')){
		            $(this).removeClass('select');
		        }else{
		        	$(this).addClass('select');
		        }
			}else{
				$(this).find('span').text('');
				$(this).find('span').hide();
		        if($(this).hasClass('select')){
		            $(this).removeClass('select');
		        }else{
		        	$(this).addClass('select');
		        }
			}
	        if($assistantselectMultipleRt.find('li.select').length > 0){
	            $assistantselectDelChildren.addClass('allow');
	        }else{
	            $assistantselectDelChildren.removeClass('allow');
	        }
	        if($assistantselectMultipleLt.find('li.select').length > 0 ){
	            $assistantselectAddChildren.addClass('allow');
	        }else{
	            $assistantselectAddChildren.removeClass('allow');
	        }
		});

		$assistantselectAddChildren.click(function() {
	        if($(this).hasClass('allow')){
	            var cHtml='';
		            $assistantselectMultipleLt.find('li.select').each(function() {
		                cHtml += '<li class="dropdown-cents" teacherId="'+$(this).attr('teacherId')+'" ';
						cHtml += ' thumbMed="'+$(this).attr("thumbMed")+'"'
						cHtml += ' realName="'+$(this).attr("realName")+'"'
		                cHtml += '>'+$(this).html()+'</li>';
						$(this).removeClass('select');
						$(this).addClass('selected');
		            });
		            $assistantselectMultipleRt.append(cHtml);
		            $assistantselectMultipleRt.find('.defalut').remove();
		            $('.multiple-tip').hide();
		            if($assistantselectMultipleLt.find('li').length == 0){
		                $(this).removeClass('allow');
		            }
	        }else{
	            $assistantselectMultipleLt.css('border','1px solid #ffa91e');
	        }
		})

		$assistantselectDelChildren.click(function() {
	        if($(this).hasClass('allow')){
	            var cHtml='';
		            $assistantselectMultipleRt.find('li.select').each(function() {
						var id = $(this).attr('teacherId');
		                cHtml += '<li class="dropdown-cents" teacherId="'+id+'">'+$(this).html()+'<div class="tab-delete"></div></li>';
		                $(this).remove();
						$assistantselectMultipleLt.find('.selected').each(function() {
							if($(this).attr('data-id') == id){
								$(this).removeClass('selected');
							}
						})
		            });
		            if($assistantselectMultipleRt.find('li').length == 0) {
		                $(this).removeClass('allow');
		                $assistantselectMultipleRt.html('');
		                $('.multiple-tip').show();
		            }
		            $assistantselectMultipleLt.find('li').removeClass('selected');
					$assistantselectMultipleRt.find('li').each(function() {
						var rtTeacherId = $(this).attr('teacherid');
			            $assistantselectMultipleLt.find('li').each(function() {
			            	if(rtTeacherId == $(this).attr('teacherid')) {
	            				$(this).addClass('selected');
	            			}
			            })
		            })
	        }else{
	            layer.msg('没有选择助教');
	        }
		})

		$assistantselectCourseAdd.click(function() {
			if($('#assistant-multiple-right').find('.dropdown-cents').length > 10) {
				layer.msg('最多选择十位助教');
				return false;
			}else {
				$('#org-assistant-list').html($assistantselectMultipleRt.html());
				$('#teacher-assistant-contents').html($assistantselectMultipleRt.html())
				$('#org-assistant-list').hide();
				$('.add-teacher-title').hide();
				$('#org-assistant-list').find('span').hide();
				$('#teacher-assistant-contents').find('span').hide();
				layer.closeAll();
				return false;
			}
		})
		$assistantselectCourseCancle.click(function() {
			layer.closeAll();
		})
})()

;(function () {
    var dropdown = $('.dropdown');
    var dropdownInput = dropdown.find('.dropdown-input');
    var dropdownUpdateInput = dropdown.find('.label-dropdown-input');
    var dropdownBox = dropdown.find('.dropdown-box');

//旧标签点击填充
	dropdownBox.on('click', '.dropdown-tab', function() {
		var thisTag = $(this);
		var lastedTagId = thisTag.attr('tagid');
		var lastedSelectedId = thisTag.attr('selectedid');
		var dropdownTabTxt = thisTag.text(),lastedHtml;
		var olderTagName = $('#label-content').find('.dropdown-show-tab').text();
        var labelContentLength = $('#label-content').find('.dropdown-show-tab').length;
            lastedHtml = '<div class="dropdown-show-tab p0 c-fl" selectedId='+lastedSelectedId+' tagId='+ lastedTagId +'>'
            lastedHtml += '<div class="left-side"></div>'
            lastedHtml += '<div class="tab-delete"></div>'
            lastedHtml += dropdownTabTxt
            lastedHtml += '</div>';

            if(labelContentLength < 3 ) {
				dropdownBox.find('.dropdown-tab').each(function() {
					var thisFindTagId = $(this).attr('tagid');
					if(thisFindTagId == lastedTagId) {
						$(this).addClass('on');
					}
				});
				$(this).addClass('on');
                $('.course-name-ipt').val('');
                $('#label-content').append(lastedHtml);
            }else {
                layer.msg('最多添加三项');
                $(this).removeClass('on');
                $('.course-name-ipt').val('');
                $('.course-name-ipt').hide();
                return false;
            }

        	if(labelContentLength == 2 ){
            	$('.course-name-ipt').val('');
            	$('.course-name-ipt').hide();
        	}

	});
//回车键生成标签
    dropdown.on('keydown', '.course-name-ipt', function(e) {
        var e = e || event,
            keycode = e.which || e.keyCode,addHtml;
        if (keycode==32 || keycode==13 || (event.shiftKey && event.keyCode==13)) {
            var tagName = $.trim($(this).val());
            if(tagName==''){
                layer.msg('标签不能为空');
                return false;
            }else {
	            addHtml = '<div class="dropdown-show-tab p0 c-fl" tagid="0">'
	            addHtml += '<div class="left-side"></div>'
	            addHtml += '<div class="tab-delete"></div>'
	            addHtml += tagName
	            addHtml += '</div>';

                var labelContentLength = $('#label-content').find('.dropdown-show-tab').length;
            	var IptTagName;

            	$('#dropdown-box').find('.dropdown-tab').each(function() {
        			var thatTagName = $.trim($(this).text());
            			if(thatTagName == tagName) {
            				$(this).addClass('on');
            			}
            	});
            	$('#label-content').find('.dropdown-show-tab').each(function() {
            		IptTagName = $.trim($(this).text());
            	});

            	if(IptTagName == tagName) {
            		layer.msg('标签重复');
            		$('.course-name-ipt').val('');
            		$('.course-name-ipt').show();
            		return false;
            	}else {
            		$('#label-content').append(addHtml);
            	}

            	if(labelContentLength < 2 ) {
                	$('.course-name-ipt').val('');
            	}else{
            		$('.course-name-ipt').val('');
            		$('.course-name-ipt').hide();
            	}
            }
        }
    });
//删除标签
    dropdownUpdateInput.on('click','.tab-delete',function () {
        var thisIptDelTagName = $.trim($(this).parents(".dropdown-show-tab").text());
        var delTagParents = $(this).parents('.dropdown-show-tab');
        var tagId = $(this).parents('.dropdown-show-tab').attr('tagid');
        var selectedId = $(this).parents('.dropdown-show-tab').attr('selectedid');
        if(tagId == 0) {
        	delTagParents.remove();
    		$('.course-name-ipt').val('');
    		$('.course-name-ipt').show();
        }else if(selectedId == 0) {
    		$('.course-name-ipt').show();
        	delTagParents.remove();
			dropdownBox.find('.dropdown-tab').each(function() {
        		var thisDelTagName = $.trim($(this).text());
	        		if(thisDelTagName == thisIptDelTagName) {
	        			$(this).removeClass('on');
	        		}
        	});
        }else {
	        var params = {
	        		courseId : courseId,
	        		tagId    : tagId
	        	};
			$.ajax({
				url: '/user/courseAjax/delCourseTag',
				type: 'post',
				dataType: 'json',
				data: JSON.stringify(params),
				success:function(r) {
					if(r.code == 0) {
			    		$('.course-name-ipt').show();
			        	delTagParents.remove();
						dropdownBox.find('.dropdown-tab').each(function() {
			        		var thisDelTagName = $.trim($(this).text());
				        		if(thisDelTagName == thisIptDelTagName) {
				        			$(this).removeClass('on');
				        		}
			        	});
					}else {
						layer.msg(r.message);
					}
				}
			});
        }
    });

    dropdownInput.on('click',function (event) {
        event.stopPropagation();
		if($('#add-course-info-btn').attr('courseid') == 0){
			$(this).parents(".dropdown").find('.dropdown-box').hide();
		}else if(!$('#add-course-info-btn').attr('courseid')){
			$(this).parents(".dropdown").find('.dropdown-box').attr('data-show','true').css('display','block');
		}
    });
    $(document).on('click',function (event) {
        if(event.target.className.indexOf('dropdown-tab')== -1){
            $('.dropdown-box[data-show=true]').css('display','none');
        }
    });

    setTimeout(function() {
	    //延迟加载 ->下方标签与之前标签比较是否存在
	    $('#label-content').find('.dropdown-show-tab').each(function() {
	    	var labelText = $.trim($(this).text());
		    	$('#dropdown-box').find('.dropdown-tab').each(function() {
		    		if($.trim($(this).text()) == labelText) {
		    			$(this).addClass('on');
		    		}
		    	})
	    })
		if($('#org-teacher-list').find('.dropdown-cents').length == 0) {
			$('#org-teacher-list').hide();
		}else {
			$('#org-teacher-list').hide();
		}
    }, 500);

})()

;(function() {
//删除无上传图片提示
	$('.close-btn-tip').click(function() {
		$(this).parents('.course-hd-tip').remove();
	});
//无图片替换Img
	var courseImgAttr = $('.course-load-img').find('img').attr('src');
		if(!courseImgAttr) {
			$('.course-load-img').find('img').attr('src', '/assets_v2/img/course-load-img.jpg');
		}
})()