<div class="col-md-4 p0">
	<nav class="course-menu-nav user-left-menu">
		<ul class=" set-course-ltmenu fs14 tac">
			<?php if(SlightPHP\Tpl::$_tpl_vars["isAdmin"]==1){; ?>
			<li>
				<a href="/user.org.editcourse.<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>" class="orange-link <?php if(SlightPHP\Tpl::$_tpl_vars["isCheck"]==1){; ?>active<?php }; ?>" title="基本信息">基本信息</a>
			</li>
			<li>
				<a href="/user.org.setcourseimg.<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>" class="orange-link <?php if(SlightPHP\Tpl::$_tpl_vars["isCheck"]==2){; ?>active<?php }; ?>" title="课程图片">课程图片</a>
				<?php if(SlightPHP\Tpl::$_tpl_vars["isImg"]==0){; ?><span class="doubt-icon"></span><?php }; ?>
			</li>
			<li>
				<a href="/user.org.setcourseabstract.<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>" class="orange-link <?php if(SlightPHP\Tpl::$_tpl_vars["isCheck"]==3){; ?>active<?php }; ?>" title="课程简介">课程简介</a>
			</li>
			<?php }; ?>
			<li>
				<a href="/user.org.plan.<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>" class="orange-link <?php if(SlightPHP\Tpl::$_tpl_vars["isCheck"]==4){; ?>active<?php }; ?>" title="班级排课">班级排课</a>
			</li>
			<li>
				<a href="/user.teacher.filelist.<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>" class="orange-link <?php if(SlightPHP\Tpl::$_tpl_vars["isCheck"]==5){; ?>active<?php }; ?>" title="文件管理">文件管理</a>
			</li>
			<li>
				<a href="/user.teacher.studentlist.<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>" class="orange-link <?php if(SlightPHP\Tpl::$_tpl_vars["isCheck"]==6){; ?>active<?php }; ?>" title="学员管理">学员管理</a>
			</li>
			<li>
				<a href="/user.teacher.Statistics.<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>" class="orange-link <?php if(SlightPHP\Tpl::$_tpl_vars["isCheck"]==7){; ?>active<?php }; ?>" title="学习统计">学习统计</a>
			</li>
			<li>
				<a href="/comment.manage.CommentList.<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>" class="orange-link <?php if(SlightPHP\Tpl::$_tpl_vars["isCheck"]==8){; ?>active<?php }; ?>" title="评价管理">评价管理</a>
			</li>
            <li>
				<a href="/org.customerTools.courseCustomerList.<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>" class="orange-link <?php if(SlightPHP\Tpl::$_tpl_vars["isCheck"]==9){; ?>active<?php }; ?>" title="课程服务">课程服务</a>
			</li>
		</ul>
	</nav>
</div>
