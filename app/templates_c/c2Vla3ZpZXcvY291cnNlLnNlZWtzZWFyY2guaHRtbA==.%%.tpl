<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>高能100 - 查询</title>
		<?php echo tpl_function_part("/index.main.headeruser"); ?>
		<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/css/style.css'); ?>">
		<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery-1.11.1.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/layer/layer.js'); ?>"></script>
		<style>
			.form-control{
				width:400px;
				height:25px;
			}
		</style>
	</head>
	<body>
		<!-- banner -->
		<div class="container-fluid">
			<div class="row col-sm-12">
			</div>
			<label class="col-sm-1 control-label">请您输入查询条件</label>
		</div>
		<form action="/seek.course.seeklist" method="post" class="form-horizontal" target="_blank"> 
			<div class="form-group">
				<label class="col-sm-1 control-label">f</label>
				<div class="col-sm-4">
					<input name="f" type="text" class="form-control">
					<span style="color:#bcbcbc;">多个字段名以英文逗号分隔</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-1 control-label">q</label>
				<div class="col-sm-4">
					<input name="q" type="text" class="form-control">
					<span style="color:#bcbcbc;">条件格式:字段名:值 (多个值以逗号分隔，多个查询字段以一个空格分隔) 如：course_id:12,23 fee_type:1 </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-1 control-label">ob</label>
				<div class="col-sm-4">
					<input name="ob" type="text" class="form-control">
					<span style="color:#bcbcbc;">格式如：user_total:desc vv:desc(多个以一个空格分隔)</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-1 control-label">p</label>
				<div class="col-sm-4">
					<input name="p" type="text" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-1 control-label">pl</label>
				<div class="col-sm-4">
					<input name="pl" type="text" class="form-control">
					<span style="color:#bcbcbc;">最多显示500条</span>
				</div>
			</div>
			<div class="form-group" style="margin-top:20px;margin-bottom:20px;">
				<label class="col-sm-1 control-label">返回数据格式</label>
				<div class="col-sm-4">
					<input name="dataType" type="radio" value="1" >数组格式
					<input name="dataType" type="radio" value="2" >表格形式
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-4">
					<input  type="submit" class="btn btn-default" target="_blank">
				</div>
			</div>	
		</form>
	</body>
</html>
