<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>高能100 - 查询结果</title>
		<?php echo tpl_function_part("/index.main.headeruser"); ?>
		<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/css/style.css'); ?>">
		<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
		<style>
			table{
				border-spacing: 0px; 
			}
			table th{
				border:1px solid #bcbcbc;
			}
			td{
				border:1px solid #bcbcbc;
			}
		</style>
	</head>
	<body>
		<div class="container-fluid" style="margin-top:70px;">
			<div class="row col-sm-12">
			</div>
				<label class="col-sm-1 control-label">共<?php echo SlightPHP\Tpl::$_tpl_vars["ret_seek"]->total; ?>条</label>
				<label class="col-sm-1 control-label">第<?php echo SlightPHP\Tpl::$_tpl_vars["ret_seek"]->page; ?>页</label>
				<label class="col-sm-1 control-label">每页<?php echo SlightPHP\Tpl::$_tpl_vars["ret_seek"]->pagelength; ?>个</label>
				<label class="col-sm-1 control-label">耗时<?php echo SlightPHP\Tpl::$_tpl_vars["ret_seek"]->time; ?>秒</label>
		</div>
		<table class="table table-bordered">
			<thead>
				<tr class="top" style="left:0px;top:0px;z-index: 1;background: azure;">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["f_array"])){; ?>
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["f_array"] as SlightPHP\Tpl::$_tpl_vars["fo"]){; ?>
							<th><?php echo SlightPHP\Tpl::$_tpl_vars["fo"]; ?></th>
						<?php }; ?>
					<?php }; ?>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseList"])){; ?>
				<?php foreach(SlightPHP\Tpl::$_tpl_vars["courseList"] as SlightPHP\Tpl::$_tpl_vars["co"]){; ?>
				<tr>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["f_array"] as SlightPHP\Tpl::$_tpl_vars["fv"]){; ?>
						<?php if(isset(SlightPHP\Tpl::$_tpl_vars["co"][SlightPHP\Tpl::$_tpl_vars["fv"]])){; ?>
							<?php if(is_array(SlightPHP\Tpl::$_tpl_vars["co"][SlightPHP\Tpl::$_tpl_vars["fv"]])){; ?>
								<td scope="row" nowrap="nowrap"><?php echo var_dump(SlightPHP\Tpl::$_tpl_vars["co"][SlightPHP\Tpl::$_tpl_vars["fv"]]); ?></td>
							<?php }else{; ?>
								<td scope="row"><?php echo SlightPHP\Tpl::$_tpl_vars["co"][SlightPHP\Tpl::$_tpl_vars["fv"]]; ?></td>
							<?php }; ?>
						<?php }else{; ?>
							<td scope="row"></td>
						<?php }; ?>
					<?php }; ?>
				</tr>
				<?php }; ?>
				<?php }; ?>
			</tbody>			
		</table>
	</body>
</html>
