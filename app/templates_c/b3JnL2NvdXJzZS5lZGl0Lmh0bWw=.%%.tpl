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
<!-- tpInfo -->
<?php echo tpl_function_part("/org.course.managetop.".SlightPHP\Tpl::$_tpl_vars["courseId"]); ?>
<!-- tpInfo -->
<section class="pb30 divSelectFirstVal" id="divSelectFirstVal">
	<div class="container">
		<div class="row">
	<!-- bdy -->
		<section>
			<?php echo tpl_function_part("/org.course.managenav.".SlightPHP\Tpl::$_tpl_vars["courseId"]); ?>
			<div class="col-md-16 pr0">
				<div class="gn-base-ct clearfix">
					<section class="col-md-20 fs14 base-content form-horizontal">
						<h1 class="base-title mb20">基本信息</h1>
						<label class="mb20 clearfix">
							<div class="col-md-5 tar lh36"><em class="cRed">*</em>课程名称：</div>
							<div class="col-md-15">
								<input type="text" id="get-courseInfo-title" data-valid="isNonEmpty" data-error="课程不能为空" data-status="0" maxlength="50"  data-tip="请输入课程名称" class="col-md-10 required course-name" placeholder="请输入课程名称最多输入50个字符" />
							</div>
						</label>
						<div class="mb15 clearfix">
							<div class="col-md-5 tar"><em class="cRed">*</em>分类：</div>
							<div class="col-md-15">
								<div class="divselect divselect-32 col-md-4 mr20 p0">
									<cite>
										<span class="cite-icon"></span>
										<span class="cite-text" id="get-firstCateName">请选择</span>
									</cite>
									<dl id="get-cate"></dl>
								</div>
								<div class="divselect divselect-32 col-md-4 mr20 p0" style="display:none;" id="get-class-info">
									<cite>
										<span class="cite-icon"></span>
										<span class="cite-text" id="get-course-secondCate">请选择</span>
									</cite>
									<dl id="get-cate-class"></dl>
								</div>
								<div class="divselect divselect-32 col-md-4 mr20 p0" style="display:none;" id="get-category-info">
									<cite>
										<span class="cite-icon"></span>
										<span class="cite-text" id="get-course-thirdCate">请选择</span>
									</cite>
									<dl id="get-cate-name"></dl>
								</div>
							</div>
						</div>
						<div class="mb20 clearfix" style="display:none;" id="get-attr-category">
 							<div class="col-md-5 tar"><em class="cRed">*</em>科目：</div>
 							<div class="col-md-15">
 								<input class="hidden get-attrstatus" />
 								<input tpe="text"placeholder="点击选择科目" class="col-md-10 course-name update-cate-attr" id="slt-course-name" onclick="updateCateAttr(this)">
 							</div>
						</div>
						<div class="mb15 clearfix">
							<div class="col-md-5 tar"><em class="cRed">*</em>价格：</div>
							<div class="col-md-15 clearfix" id="pay-course-btn">
								<label class="pay-course c-fl mr20">
									<input type="radio" name="price" value="1" /> 付费
								</label>
								<label class="free-course c-fl">
									<input type="radio" name="price" value="0" /> 免费
								</label>
							</div>
							<input type="hidden" id="freeTypeOld" />
							<input type="text" onkeyup="if(isNaN(value))execCommand('undo')" class="price-course col-md-3 ml15 mt10" placeholder="价格（元）" style="display:none;" />
						</div>
						<div class="mb20 clearfix" id="get-parent-setIds" style="display:none;">
							<div class="col-md-5 tar">会员课程：</div>
							<div class="col-md-15">
								<div class="col-md-20 p0 mb10" id="select-member">
									<label class="c-fl mr20">
										<input type="radio" name="member" value="1" /> 是
									</label>
									<label class="c-fl">
										<input type="radio" name="member" value="0" /> 否
									</label>
								</div>
								<div class="col-md-12 member-bgf7 member-course p0" id="get-course-memberSet" style="display:none;"></div>
							</div>
						</div>
						<div class="mb20 clearfix">
							<div class="col-md-5 tar"><em class="cRed">*</em>讲师：</div>
							<div class="col-md-15 dropdown">
								<div class="dropdown-input t-dropdown-input col-md-14 clearfix" id="teachers-content">
									<div class="col-md-18 p0">
										<ul id="teacher-contents" class="teacher-content clearfix"></ul>
										<span class="add-teacher-title" style="color:#ccc">添加老师</span>
									</div>
									<div class="t-teach-icon mt5"></div>
								</div>
								<div class="col-md-14 fs12 p5 mt5" style="background:#ffffcd;">
									请添加本课程中需要授课的所有老师、班主任（支持多选）
								</div>
								<ul class="dropdown-cent update-dropdown-cent clearfix" style="width:436px;" id="org-teacher-list"></ul>
							</div>
						</div>
						<div class="mb20 clearfix">
							<div class="col-md-5 col-xs-8 tar">助教：</div>
							<div class="col-md-15">
								<div class="dropdown">
									<div class="dropdown-input t-dropdown-input col-md-14 clearfix" id="assistant-cent">
										<div class="col-md-18 col-xs-16 p0">
											<ul id="teacher-assistant-contents" class="teacher-content clearfix"></ul>
											<span class="add-teacher-title" style="color:#ccc">添加助教</span>
										</div>
										<div class="t-teach-icon mt5"></div>
									</div>
									<div class="col-md-14 fs12 p5 mt5" style="background:#ffffcd;">
										请添加本课程的助教（助教可对学生进行分组管理，支持多选）
									</div>
									<ul class="dropdown-cent add-dropdown-cent clearfix" style="width:550px;min-height:110px;" id="org-assistant-list"></ul>
								</div>
							</div>
						</div>
						<div class="mb20 clearfix">
							<div class="col-md-5 tar">标签：</div>
							<div class="col-md-15 dropdown">
								<label>
									<div class="dropdown-input label-dropdown-input update-dropdown-input col-md-14 p0 clearfix">
										<div id="label-content"></div>
										<input type="text" class="col-md-5 course-name course-name-ipt" style="margin-top:-3px;" maxlength="10" placeholder="添加标签" />
									</div>
								</label>
								<div class="dropdown-box" id="dropdown-box" style="left:15px;width:67%;">
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
                            <button class="fs14 mt30 green-button" id="update-course-info">保存</button>
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
 <div class="layer-teacherlist multiple-select-list" id="multiple-select-list" style="display:none;">
     <div class="lc-so-name mt20">
 			<div class="search-frame ml40">
 				<input name="keyword" class="search-input search-teacher-infos" type="text" value="" placeholder="搜索老师名称" />
                 <div class="search-box org-t-search-btn" id="subsearch" style="float:left;">
                     <span class="search-icon" onclick="orgSearchTeacher()"></span>
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
     <div class="col-xs-20 tac mt10">
 		<button class="mr20 green-button" id="course_add">确定</button>
 		<button class="gray-button" id="course_cel">取消</button>
     </div>
 </div>
 <!-- 选择老师 -->
 <!-- 选择助教 -->
<section class="layer-teacherlist multiple-select-list" id="assistant-select-list" style="display:none;">
    <div class="lc-so-name mt20">
            <div class="search-frame ml40">
                <input name="keyword" class="search-input ass-search-teacher-infos" type="text" value="" placeholder="搜索老师名称" />
                <div class="search-box org-t-search-btn" id="subsearch" style="float:left;">
                    <span class="search-icon" onclick="assistanSearchTeacher()"></span>
                    <div class="t-list-img clear-icon" id="s-delt-btn" style="display:none;">
                    </div>
                </div>
            </div>
    </div>
    <div class="lc-list mt15 ml40">
        <ul class="multiple-select mt10" id="assistant-multiple-left"></ul>
    </div>
    <div class="co-option mt15" id="co-option">
        <a href="javascript:;" id="assistant-add-btn">添加 》</a>
        <a href="javascript:;" class="mt10" id="assistant-del-btn">《 删除</a>
    </div>
    <div class="lc-list">
        <p class="fs14">已选择助教</p>
        <ul class="multiple-select mt10">
            <li class="defalut tac multiple-tip">还没有助教</li>
            <ul id="assistant-multiple-right"></ul>
        </ul>
    </div>
    <div class="col-xs-20 tac mt10 mb10">
        <button class="mr20 green-button" id="assistant_course_add">确定</button>
        <button class="gray-button" id="assistant_course_cel">取消</button>
    </div>
</section>
<!-- /选择助教 -->
<input class="hidden" updateId="0" type="<?php echo SlightPHP\Tpl::$_tpl_vars["type"]; ?>" token="<?php echo SlightPHP\Tpl::$_tpl_vars["token"]; ?>" courseId="<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>" id="grobal-course" />
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
<script id="getAttrArrTpl" type="text/template">
	<<#tagArr>>
		<div class="dropdown-show-tab p0 c-fl" tagid="<<tagId>>">
			<div class="left-side"></div>
			<div class="tab-delete"></div>
			<<tagName>>
        </div>
	<</tagArr>>
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
<script id="isGroupTeachersTpl" type="text/template">
	<<#checkedTeachers>>
		<li class="dropdown-cents" isGroup="<<isGroup>>" teacherId="<<teacherId>>" thumbMed="<<thumbMed>>" realName="<<realName>>">
			<img src="<<thumbMed>>" class="c-fl mr10" width="20" height="20" alt="" />
			<div class="tab-delete"></div><<realName>><span class="cBlue"></span>
		</li>
	<</checkedTeachers>>
</script>
<script id="assistanArrTpl" type="text/template">
	<<#assistanArr>>
		<li class="dropdown-cents" isGroup="<<isGroup>>" teacherId="<<teacherId>>" thumbMed="<<thumbMed>>" realName="<<teacherName>>">
			<img src="<<thumbMed>>" class="c-fl mr10" width="20" height="20" alt="" />
			<div class="tab-delete"></div><<teacherName>><span class="cBlue"></span>
		</li>
	<</assistanArr>>
</script>
<script id="orgGetTeacherTpl" type="text/template">
	<<#teacherArr>>
		<li class="dropdown-cents" hasPlan="<<hasPlan>>" teacherId="<<teacherId>>" thumbMed="<<thumbMed>>" realName="<<teacherName>>">
			<img src="<<thumbMed>>" class="c-fl mr10" width="20" height="20" alt="">
			<div class="tab-delete"></div><<teacherName>><span class="cBlue"></span>	
		</li>
	<</teacherArr>>
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
<script type="text/template" id="getsetIdsTpl">
	<<#setIds>>
		<label class="c-fl mr10">
			<input type="checkbox" checkedId="<<checked>>" memberId="<<memberId>>" /> <<memberName>>
		</label>
	<</setIds>>
</script>
<script type="text/javascript">
var token    = "<?php echo SlightPHP\Tpl::$_tpl_vars["token"]; ?>";
var courseId = <?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>;
var type = <?php echo SlightPHP\Tpl::$_tpl_vars["type"]; ?>;
$(function() {
	getTag();
	courseInfo();
	$('#update-course-info').click(function() {
		editCourse();
		locationReload();
	})
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
			$('.price-course').hide();
			$('#get-parent-setIds').hide();
		}
	});
 	$('#select-member input[type="radio"]').click(function() {
 		var type = $('#grobal-course').attr('coursetype');
 			if($(this).val() == 0) {
 				$('#get-course-memberSet').find('input').attr('checked', false);
 			}
 			if(type == 3){
 				$('#get-course-memberSet').hide();
 			}else {
		 		var isCheckedRadio = $(this).val();
		 		if(isCheckedRadio == 1) {
		 			$('#get-course-memberSet').show();
		 		}else {
		 			$('#get-course-memberSet').hide();
		 		}
 			}
 	});
	$.divselect(".divselect cite");
 //选择教师
 	$('#teachers-content').click(function() {
 		orgTeacher();
 		$('#multiple-right').html('');
 		layer.open({
 			type: 1,
 			title:['选择教师'],
 			area: ['765px', '510px'],
 			shadeClose: true,
 			content: $('#multiple-select-list')
 		});
 	});
 	$('#assistant-cent').click(function() {
 		assistanArrTeacher();
 		$('#assistant-multiple-right').html();
 		layer.open({
 			type: 1,
 			title:['选择助教'],
 			area: ['765px', '510px'],
 			shadeClose: true,
 			content: $('#assistant-select-list')
 		});
 	});
})
</script>