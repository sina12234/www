<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>高能100 - 专业的K12在线教育平台</title>
<meta name="title" content="高能100 - 专业的K12在线教育平台 在线直播">
<meta name="keywords" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线 k12 小学数学 初中数学">
<meta name="description" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线直播">
<?php echo tpl_function_part("/site.main.header"); ?>
<style>
.course-name-list{
	display:none;
	position: absolute;
	top:27px;
	right:0;
	width:100%;
	border:1px solid #ccc;
	z-index:3;
	background:#fff;
	max-height:135px;
	overflow:auto;
}
.course-name-list li{
	float:left;
	width: 100%;
	overflow: hidden;
	line-height:30px;
	cursor: pointer;
	padding:0 5px;
	white-space: nowrap;
	text-overflow: ellipsis;
}
.course-name-list li:hover{
	background:#eee;
}
</style>
</head>
<body class="bgf">
	<div class="form-horizontal col-xs-20 pt30">
		<div class="col-xs-12 pd0">
			<input type="text" class="col-xs-20" placeholder="请输入课程名称" id="courseName">
			<i class="btn-clear" style="top:7px;right:5px"></i>
			<ul class="course-name-list" class="col-xs-12" id="courseNamelist">
			</ul>
		</div>
		<div class="col-xs-7 pd0 right">
			<select name="" class="col-xs-20 pd0" disabled="disabled" id="courseClass">
				<option value="0">班级</option>
			</select>
		</div>
		<div class="col-xs-20 tac pt30">
			<a href="#" class="btn" id="subBtn">确认</a>
		</div>
	</div>
</body>
<script>
$(function(){
	var courseValue,courseName=$('#courseName'),courseClass=$('#courseClass'),courseNamelist=$('#courseNamelist'),subBtn=$('#subBtn');
	var apiUrl='/task/publishTask/courseSearch';
	var last;
	courseName.on('input',function(){
		courseValue=$(this).val();
		clearTimeout(last);
		last = setTimeout(function(){
			$.ajax(apiUrl, {
				dataType: 'json',
				type: 'post',
				data: {
					title: courseValue
				},
				beforeSend: function () {
					courseNamelist.html('<p class="tac">正在查找...</p>');
					courseNamelist.show();
	    		},
				success: function (data) {
					var _html='',i=1;
					if(data.result.code == 200) {
						for(i in data.data){
							_html += '<li data-id="'+i+'">'+data.data[i]+'</li>';
						}
						courseNamelist.html(_html);
						courseNamelist.show();
					}else{
						layer.msg(data.result.msg,{ time:800});
						courseNamelist.removeAttr('data-id').hide();
					}
				}
			});
		}, 200);
	});

	courseNamelist.on('click','li',function(){
		var _text=$(this).text();
		var _id=$(this).attr('data-id');
		courseName.val(_text).attr('data-id',_id);
		getClass(_id)
		courseNamelist.hide();
	});

	function getClass(id){
		$.ajax('/task/publishTask/getCourseClass', {
			dataType: 'json',
			type: 'post',
			data: {
				course_id: id
			},
			success: function (data) {
				var result = data.result;
				if (result.code == 200) {
					var _html='<option value="0">班级</option>',i=1;
					var data = result.data.result;
					for(i in data){
						_html += '<option value="'+data[i].pk_class+'">'+data[i].name+'</option>';
					}
					courseClass.html(_html);
					courseClass.removeAttr('disabled');
				}
			}
		});
	};

	subBtn.click(function(){
		var _courseId=courseName.attr('data-id') ? courseName.attr('data-id'):0;
		var _classid=courseClass.find('option:selected').val();
		if(_courseId==0){
			layer.msg('请输入课程名称');
			return false;
		}
		if(_classid==0){
			layer.msg('请选择班级');
			return false;
		}
		top.location='/task.publishTask.teacherPublishTask?type=create&courseId='+_courseId+'&classId='+_classid;
	})
})
</script>
</html>
