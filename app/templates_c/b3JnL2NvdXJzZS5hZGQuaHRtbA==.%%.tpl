<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>设置基本信息 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
	<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 创建课程 - 云课 - 专业的在线学习平台">
	<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网">
	<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
	<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<style type="text/css">
.dropdown-cent .tab-delete{ background: #f7f7f7;}
.dropdown-cents:hover .tab-delete{ background: #eee;}
.dropdown .dropdown-input{ min-height: 36px;height:auto;}
</style>
<body>
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<section class="pd30 divSelectFirstVal" id="divSelectFirstVal">
	<div class="container">
		<div class="row">
	<!-- bdy -->
		<section>
			<div class="col-md-20 pd0">
				<div class="gn-base-ct clearfix">
					<section class="col-md-20 fs14 base-content form-horizontal p0">
						<h1 class="base-title mb20">
							<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["sourceUrl"]; ?>" class="cGray">返回课程列表</a>
							> <a href="/org.course.type">课程类型</a>
							> 基本信息
						</h1>
						<h2 class="tac fs14 clearfix" style="width:260px;margin:0 auto 20px auto;">
							<span class="c-fl">设置基本信息</span>
							<button class="c-fr" id="course-ischecked" data-status="0">
								<img src="/assets_v2/img/copy-file-icon.jpg" class="mr5" alt="">
								<span>复制上次信息</span>
							</button>
						</h2>
						<label class="mb20 clearfix">
							<div class="col-md-5 col-xs-8 tar lh36"><em class="cRed">*</em>课程名称：</div>
							<div class="col-md-15 col-xs-12 row">
								<input type="text" data-valid="isNonEmpty" data-error="课程不能为空" data-status="0"  data-tip="请输入课程名称" maxlength="50" id="get-courseInfo-title" class="col-md-10 required course-name" placeholder="请输入课程名称最多输入50个字符" />
							</div>
						</label>
						<div class="mb15 clearfix">
							<div class="col-md-5 col-xs-8 tar"><em class="cRed">*</em>分类：</div>
							<div class="col-md-15 col-xs-12 row">
								<div class="divselect divselect-32 col-md-3 col-xs-20 mr20 p0" id="get-firstCate-btn">
									<cite>
										<span class="cite-icon"></span>
										<span class="cite-text" id="get-firstCateName">请选择</span>
									</cite>
									<dl id="get-cate"></dl>
								</div>
								<div class="divselect divselect-32 col-md-3 col-xs-20 mr20 p0" id="add-secondCate" style="display:none;">
									<cite>
										<span class="cite-icon"></span>
										<span class="cite-text" id="get-course-secondCate">请选择</span>
									</cite>
									<dl id="get-cate-class"></dl>
								</div>
								<div class="divselect divselect-32 col-md-3 col-xs-20 mr20 p0" id="add-thirdCate" style="display:none;">
									<cite>
										<span class="cite-icon"></span>
										<span class="cite-text" id="get-course-thirdCate">请选择</span>
									</cite>
									<dl id="get-cate-name"></dl>
								</div>
							</div>
						</div>
						<div class="mb20 clearfix" id="get-attr-category" style="display:none;">
 							<div class="col-md-5 col-xs-8 tar"><em class="cRed">*</em>科目：</div>
 							<div class="col-md-15 col-xs-12 row">
								<input class="hidden get-attrstatus" />
 								<input type="text" placeholder="点击选择科目" class="col-md-10 course-name" onclick="updateCateAttr(this)" id="slt-course-name">
 							</div>
						</div>
						<div class="mb15 clearfix">
							<div class="col-md-5 col-xs-8 tar"><em class="cRed">*</em>价格：</div>
							<div class="col-md-15 col-xs-12 clearfix row" id="pay-course-btn">
								<label class="pay-course c-fl mr20">
									<input type="radio" name="price" value="1" /> 付费
								</label>
								<label class="free-course c-fl">
									<input type="radio" name="price" checked value="0" /> 免费
								</label>
							</div>
							<input type="hidden" id="freeTypeOld" />
							<input type="text" id="add-course-price" onkeyup="if(isNaN(value))execCommand('undo')" class="price-course col-md-3 col-xs-10 ml15 mt10 row" placeholder="价格（元）" style="display:none;" />
						</div>
						<div class="mb20 clearfix" id="get-parent-setIds" style="display:none;">
							<div class="col-md-5 col-xs-8 tar">会员课程：</div>
							<div class="col-md-15 col-xs-12 row">
								<div class="col-md-20 p0 mb10 clearfix" id="select-member">
									<label class="c-fl mr20">
										<input type="radio" name="member" value="1" /> 是
									</label>
									<label class="c-fl">
										<input type="radio" name="member" checked value="0" /> 否
									</label>
								</div>
								<div class="col-md-10 member-bgf7 member-course p0 clearfix" id="get-course-memberSet" style="display:none;"></div>
							</div>
						</div>
						<div class="mb20 clearfix">
							<div class="col-md-5 col-xs-8 tar"><em class="cRed">*</em>讲师：</div>
							<div class="col-md-15 col-lg-10 col-xs-20">
								<div class="dropdown">
								<div class="dropdown-input t-dropdown-input clearfix" id="teachers-cent">
										<div class="col-md-18 col-xs-16 p0">
											<ul id="teacher-contents" class="teacher-content clearfix"></ul>
											<span class="add-teacher-title" style="color:#ccc">添加老师</span>
										</div>
										<div class="t-teach-icon mt5"></div>
									</div>
									<div class="col-md-20 fs12 p5 mt5" style="background:#ffffcd;">
										请添加本课程中需要授课的所有老师、助教及班主任（支持多选）
									</div>
									<ul class="dropdown-cent add-dropdown-cent clearfix" style="width:550px;min-height:110px;" id="org-teacher-list"></ul>
								</div>
							</div>
						</div>
						<div class="mb20 clearfix">
							<div class="col-md-5 col-xs-8 tar">标签：</div>
							<div class="col-md-10 col-xs-20 dropdown">
								<label>
									<div class="dropdown-input label-dropdown-input add-dropdown-input clearfix">
										<div id="label-content"></div>
										<input type="text" class="col-md-5 course-name course-name-ipt" style="margin-top:-3px;" maxlength="10" placeholder="添加标签" />
									</div>
								</label>
								<div class="dropdown-box" id="dropdown-box" style="left:15px;width:550px;">
									<div class="c-dropdown-box clearfix mb10"><span class="c-fl">最近使用：</span>
										<div id="lasted-tag"></div>
	                                </div>
	                                <div class="z-dropdown-box clearfix"><span class="c-fl">常用标签：</span>
	                                	<div id="often-tag"></div>
	                                </div>
								</div>
								<p class="fs12 col-md-20 cGray p0">
									每个标签限10个字符，输入标签名称后点击回车，最多添加3个标签
								</p>
							</div>
						</div>
						<div class="mb20 tac clearfix">
							<button class="fs14 mt30 green-button" courseId="<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>" id="add-course-info-btn">下一步</button>
						</div>
					</section>
				</div>
			</div>
		</section>
	<!-- /bdy -->
		</div>
	</div>
</section>
 <!-- 选择课程 -->
 <section id="selet-course-name" style="display: none;" class="layui-layer-wrap">
 	<ul class="selet-course-name clearfix" id="get-attr"></ul>
 	<p class="tac mt20 mb20 fs14 lGray">最多选择3个科目</p>
 	<div class="col-xs-20 fs14 tac">
 		<button class="set-req-btn green-button">确定</button>
 		<button class="set-more-cancle gray-button">取消</button>
 	</div>
 </section>
 <!-- /选择课程 -->
 <!-- 选择老师 -->
<section class="layer-teacherlist multiple-select-list" id="multiple-select-list" style="display:none;">
    <div class="lc-so-name mt20">
            <div class="search-frame ml40">
                <input name="keyword" class="search-input" id="search-teacher-infos" type="text" value="" placeholder="搜索老师名称" />
                <div class="search-box org-t-search-btn" id="subsearch" style="float:left;">
                    <span class="search-icon" onclick="orgTeacher2()"></span>
                    <div class="t-list-img clear-icon" id="t-delt-btn" style="display:none;">
                    </div>
                </div>
            </div>
    </div>
    <div class="lc-list mt15 ml40">
        <ul class="multiple-select mt10" id="multiple-left"></ul>
    </div>
    <div class="co-option mt15" id="co-option">
        <a href="javascript:;" id="add-btn">添加 》</a>
        <a href="javascript:;" class="mt10" id="del-btn">《 删除</a>
    </div>
    <div class="lc-list">
        <p class="fs14">已选择老师</p>
        <ul class="multiple-select mt10">
            <li class="defalut tac multiple-tip">还没有老师</li>
            <ul id="multiple-right"></ul>
        </ul>
    </div>
    <div class="col-xs-20 tac mt10 mb10">
        <button class="mr20 green-button" id="course_add">确定</button>
        <button class="gray-button" id="course_cel">取消</button>
    </div>
</section>
<!-- 选择老师 -->
<input class="hidden" token="<?php echo SlightPHP\Tpl::$_tpl_vars["token"]; ?>" courseId='<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>' type="<?php echo SlightPHP\Tpl::$_tpl_vars["type"]; ?>" id="grobal-course" />
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.validate.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.create.course.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/mustache.js'); ?>"></script>
<script id="getLastedTagTpl" type="text/template">
	<<#lasted>>
		<div class="dropdown-tab" selectedId="<<selected>>" tagId="<<tagId>>">
            <div class="left-side"></div>
            <div class="tab-add"></div>
            <<tagName>>
        </div>
	<</lasted>>
</script>
<script id="getOftenTagTpl" type="text/template">
	<<#often>>
		<div class="dropdown-tab" selectedId="<<selected>>" tagId="<<tagId>>">
            <div class="left-side"></div>
            <div class="tab-add"></div>
            <<tagName>>
        </div>
	<</often>>
</script>
<script type="text/template" id="getCateTpl">
	<<#cateList>>
		<dd>
			<a href="javascript:;" onclick="getCateClass(this, <<cateId>>)" cateId="<<cateId>>"><<name>></a>
		</dd>
	<</cateList>>
</script>
<script type="text/template" id="getCateClassTpl">
	<<#cateList>>
		<dd>
			<a href="javascript:;" onclick="getCateName(this, <<cateId>>)" cateId="<<cateId>>"><<name>></a>
		</dd>
	<</cateList>>
</script>
<script type="text/template" id="getCateNameTpl">
	<<#cateList>>
		<dd>
			<a href="javascript:;" onclick="getCateAttr(this, <<cateId>>)" cateId="<<cateId>>"><<name>></a>
		</dd>
	<</cateList>>
</script>
<script type="text/template" id="getFirstCateNameTpl">
	<<#firstCateName>>
		<dd>
			<a href="javascript:;" onclick="getCateClass(this, <<cateId>>)" checkedId=<<checked>> cateId="<<cateId>>"><<name>></a>
		</dd>
	<</firstCateName>>
</script>
<script type="text/template" id="getSecondCateNameTpl">
	<<#secondCateName>>
		<dd>
			<a href="javascript:;" onclick="getCateName(this, <<cateId>>)" checkedId=<<checked>> cateId="<<cateId>>"><<name>></a>
		</dd>
	<</secondCateName>>
</script>
<script type="text/template" id="getThirdCateNameTpl">
	<<#thirdCateName>>
		<dd>
			<a href="javascript:;" onclick="getCateAttr(this, <<cateId>>)" checkedId=<<checked>> cateId="<<cateId>>"><<name>></a>
		</dd>
	<</thirdCateName>>
</script>
<script id="getAttrTpl" type="text/template">
	<<#attr>>
		 <li class="element-list" attrId="<<attrId>>"><<name>><i class="seled-icon"></i></li>
	<</attr>>
</script>
<script id="orgTeacherTpl" type="text/template">
	<<#teachers>>
		<li class="dropdown-cents" teacherId="<<teacherId>>" thumbMed="<<thumbMed>>" realName="<<realName>>">
			<img src="<<thumbMed>>" class="c-fl mr10" width="20" height="20" alt="" />
			<div class="tab-delete"></div>
			<<realName>>
		</li>
	<</teachers>>
</script>
<script id="checkedTeachersTpl" type="text/template">
	<<#checkedTeachers>>
		<li class="dropdown-cents" hasPlan="<<hasPlan>>" teacherId="<<teacherId>>" thumbMed="<<thumbMed>>" realName="<<realName>>">
			<img src="<<thumbMed>>" class="c-fl mr10" width="20" height="20" alt="" />
			<div class="tab-delete"></div><<realName>><span class="cBlue"></span>
		</li>
	<</checkedTeachers>>
</script>
<script type="text/template" id="getMemberSetTpl">
<<#memberSet>>
	<label class="c-fl mr10" memberId="<<memberId>>">
		<input type="checkbox" memberId="<<memberId>>" /> <<title>>
	</label>
<</memberSet>>
</script>
<script id="orgGetTeacherTpl" type="text/template">
	<<#teacherArr>>
		<li class="dropdown-cents" hasPlan="<<hasPlan>>" teacherId="<<teacherId>>" thumbMed="<<thumbMed>>" realName="<<teacherName>>">
			<img src="<<thumbMed>>" class="c-fl mr10" width="20" height="20" alt="">
			<div class="tab-delete"></div><<teacherName>><span class="cBlue"></span>	
		</li>
	<</teacherArr>>
</script>
<script id="getAttrArrTpl" type="text/template">
	<<#tagArr>>
		<div class="dropdown-show-tab p0 c-fl" tagid="<<tagId>>">
			<div class="left-side"></div>
			<div class="tab-delete"></div>
			<<tagName>>
        </div>
	<</tagArr>>
</script>
<script type="text/template" id="getsetIdsTpl">
	<<#setIds>>
		<label class="c-fl mr10">
			<input type="checkbox" checkedId="<<checked>>" memberId="<<memberId>>" /> <<memberName>>
		</label>
	<</setIds>>
</script>
<script type="text/javascript">
var type =<?php echo SlightPHP\Tpl::$_tpl_vars["type"]; ?>;
var token = "<?php echo SlightPHP\Tpl::$_tpl_vars["token"]; ?>";
$(function() {
	var courseId = $('#grobal-course').attr('courseid');
	if(courseId == 0){
		var DropAddCent = $('.add-dropdown-cent');
			DropAddCent.on('click', '.tab-delete', function() {
				$(this).parents('.dropdown-cents').remove();
			});
		getCate();
		$('#add-course-info-btn').click(function(){
			addCourse();
		});
	}else {
		$('#org-teacher-list').removeClass('add-dropdown-cent').addClass('update-dropdown-cent');
		$('#add-secondCate').show();
		$('#add-thirdCate').show();
		courseInfo();
		$('#add-course-info-btn').click(function(){
			if($('#teacher-contents').html() == '') {
				layer.msg('请选择老师');
				return false;
			}else{
				editCourse();
				window.location.href="/org/course/SetDesc";
			}
		});
	}
	$('#course-ischecked').click(function() {
		$(this).attr('data-status', 1);
		$(this).find('span').text('复制成功');
		$(this).css("cursor", "default");
		$(this).attr('disabled', true);
		getMaxCourseId(type);
		setTimeout(function() {
			courseInfo();
			$('#add-secondCate').show();
			$('#add-thirdCate').show();
			$('.dropdown-cent').removeClass('update-dropdown-cent').addClass('add-dropdown-cent');
		}, 300);
		setTimeout(function() {
			if($('#get-courseInfo-title').val() != '') {
				$('#get-courseInfo-title').parent().find('.tips').hide();
				$('#get-courseInfo-title').parent().removeClass('error');
			}else {
				$('#get-courseInfo-title').parent().find('.tips').show();
			}
			$('#label-content').find('.dropdown-show-tab').attr('tagid', 0);
		}, 500);
	});
	$('#pay-course-btn input[type="radio"]').click(function() {
		var isCheckedRadio = $(this).val();
		if(isCheckedRadio == 1) {
			$('.price-course').show();
			$('#select-member').find('input').prop('checked', false);
			if(type == 3) {
				$('#get-parent-setIds').hide();
			}else {
				$('#get-parent-setIds').show();
			}
		}else {
			$('#get-parent-setIds').hide();
			$('.price-course').hide();
		}
	});
 	$('#select-member input[type="radio"]').click(function() {
		if($(this).val() == 0) {
			$('#get-course-memberSet').find('input').attr('checked', false);
		}
 		if(type == 3) {
 			$('#get-course-memberSet').hide();
 		}else {
	 		var isCheckedRadio = $(this).val();
	 		if(isCheckedRadio == 1) {
	 			orgMemberSet();
	 			$('#get-course-memberSet').show();
	 		}else {
	 			$('#get-course-memberSet').hide();
	 		}
 		}
 	});
	$.divselect(".divselect cite");
 	$('#teachers-cent').on('click', function() {
 		orgTeacher();
 		$('#multiple-right').html('');
        if ($(window).width() < 768){
 		    var w ='90%';
 		    var h = '90%';
            layer.open({
                type: 1,
                title:['选择教师'],
                area: [w, h],
                shadeClose: true,
                content: $('#multiple-select-list')
            });
            $(".layui-layer-page").css('overflow','hidden');
        }else{
            layer.open({
                type: 1,
                title:['选择教师'],
                area: ['765px', '510px'],
                shadeClose: true,
                content: $('#multiple-select-list')
            });
		}

 		setTimeout(function() {
 		if($('#course-ischecked').attr('data-status') == 1) {
 			$('#multiple-right').find('li').attr('hasplan' , 0);
 		}
 		}, 200);
 	});

 	$('#select-time-length').on('click', 'a', function() {
 		$(this).parents('#select-time-length').find('.cite-text').attr('time', $(this).attr('time'));
 		if($(this).attr('selectid') == 6) {
 			$('#add-time-txt').show();
 		}else {
 			$('#add-time-txt').hide();
 		}
 	});
});
</script>