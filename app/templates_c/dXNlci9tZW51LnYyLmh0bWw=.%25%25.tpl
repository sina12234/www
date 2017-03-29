        <div class="user-left-menu pd0 col-md-4 hidden-xs hidden-sm">
			<?php if(!in_array(SlightPHP\Tpl::$_tpl_vars["type"],SlightPHP\Tpl::$_tpl_vars["infoMenuNoDisplay"])){; ?>
			<div class="user-info">
                <div class="u-l-face face-post-icon info-head ">
                    <a href="student.main.uploadPic/1" class="face-fixed fs14"><em><?php echo tpl_modifier_tr('点击修改头像','site.user'); ?></em></a>
					<img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userInfo"]->avatar->large)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userInfo"]->avatar->large; ?><?php }else{; ?><?php echo utility_cdn::img(' /assets_v2/img/1.png'); ?><?php }; ?>" alt=""> 
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"]) and (SlightPHP\Tpl::$_tpl_vars["type"]=='teacher' or SlightPHP\Tpl::$_tpl_vars["type"]=='default')){; ?>
                    <span class="t-users-icon"><i><?php echo tpl_modifier_tr('教师','site.user'); ?></i></span>
                    <?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"]) and SlightPHP\Tpl::$_tpl_vars["type"]=='student'){; ?>
                    <span class="s-users-icon"><i><?php echo tpl_modifier_tr('学生','site.user'); ?></i></span> 
                    <?php }; ?>
                    <a href="https://<?php echo SlightPHP\Tpl::$_tpl_vars["platform_url"]; ?>/index.rank.rule" target="_blank" class="level-icon level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["userLevel"]; ?>" id="levelIconLeft"></a>
                </div>
                <div class="name fs18">
                    <?php echo SlightPHP\Tpl::$_tpl_vars["userInfo"]->name; ?>
                </div>
                <div class="other col-sm-20">
                    <div class="local col-lg-12 col-md-20">
						<i class="local-icon"></i>
						<span class="c-fl"><?php echo SlightPHP\Tpl::$_tpl_vars["province"]; ?> <?php echo SlightPHP\Tpl::$_tpl_vars["city"]; ?></span>
					</div>
                    <a href="/student.main.infobase" class="col-lg-8 hidden-md orange-link set c-fr" target="_blank">
						<i class="set-icon"></i>
						<?php echo tpl_modifier_tr('基础设置','site.user'); ?>
					</a>
                </div>
				<div class="clear"></div>
            </div>
			<?php }; ?>

				<?php if(in_array(SlightPHP\Tpl::$_tpl_vars["css"],SlightPHP\Tpl::$_tpl_vars["menuArr"])){; ?>
				<ul class="left-menu">
					<li class=' nav-menu-box ' >
						<a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["css"]=='infobase'){; ?>active<?php }; ?>" href="/student.main.infobase">
							<span class="nav-icon nav-infobase"></span>
							<span class="nav-tag">
								<?php echo tpl_modifier_tr('个人资料','site.user'); ?>
							</span>
						</a>
					</li>
					<li class=' nav-menu-box ' >
						<a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["css"]=='infouploadpic'){; ?>active<?php }; ?>" href="/student.main.uploadPic/1">
							<span class="nav-icon nav-uploadPic"></span>
								<span class="nav-tag">
									<?php echo tpl_modifier_tr('修改头像','site.user'); ?>
								</span>
						</a>
					</li>
					<?php /*<li class=' nav-menu-box ' >
						<a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["css"]=='list'){; ?>active<?php }; ?>" href="/user.message">
							<span class="nav-icon nav-message"></span>
								<span class="nav-tag">
									<?php echo tpl_modifier_tr('我的消息','site.user'); ?>
								</span>
						</a>
					</li>*/?>
					<li class=' nav-menu-box ' >
						<a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["css"]=='infopassword'){; ?>active<?php }; ?>" href="/student.security.password">
							<span class="nav-icon nav-password"></span>
								<span class="nav-tag">
									<?php echo tpl_modifier_tr('安全设置','site.user'); ?>
								</span>
						</a>
					</li>
				</ul>
				<?php }elseif( in_array(SlightPHP\Tpl::$_tpl_vars["learnCss"],SlightPHP\Tpl::$_tpl_vars["teach"])){; ?>
				<ul class="left-menu">
					<?php if(isset(SlightPHP\Tpl::$_tpl_vars["is_pro"])&&SlightPHP\Tpl::$_tpl_vars["is_pro"]==0){; ?>
					<li class=' nav-menu-box ' >
						<a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["learnCss"]=='growth'){; ?>active<?php }; ?>" href="/student.main.growth">
							<span class="nav-icon nav-studentMain"></span>
							<span class="nav-tag">
								<?php echo tpl_modifier_tr('首页','LearningCenter'); ?>
							</span>
						</a>
					</li>
					<?php }; ?>
					<li class=' nav-menu-box ' >
						<a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["learnCss"]=='mycourse'){; ?>active<?php }; ?>" href="/student.course.mycourse">
							<span class="nav-icon nav-studentCourse"></span>
							<span class="nav-tag">
								<?php echo tpl_modifier_tr('我的课程','LearningCenter'); ?>
							</span>
						</a>
					</li>
					<li class=' nav-menu-box' >
						<a class="nav-menu  <?php if(SlightPHP\Tpl::$_tpl_vars["learnCss"]=='studentTaskListShow'){; ?>active<?php }; ?>" href="/task.commitTask.studentTaskListShow">
							<span class="nav-icon nav-studentWork"></span>
							<span class="nav-tag">
								<?php echo tpl_modifier_tr('我的作业','LearningCenter'); ?>
							</span>
						</a>
					</li>
					<li class=' nav-menu-box ' >
						<a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["learnCss"]=='myorder'){; ?>active<?php }; ?>" href="/student.order.myorder">
							<span class="nav-icon nav-studentOrder"></span>
							<span class="nav-tag">
								<?php echo tpl_modifier_tr('我的订单','LearningCenter'); ?>
							</span>
						</a>
					</li>
					<li class=' nav-menu-box ' >
						<a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["learnCss"]=='myfav'){; ?>active<?php }; ?>" href="/student.fav.myfav">
							<span class="nav-icon nav-studentFav"></span>
							<span class="nav-tag">
								<?php echo tpl_modifier_tr('我的收藏','LearningCenter'); ?>
							</span>
						</a>
					</li>
					<li class=' nav-menu-box ' >
						<a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["learnCss"]=='mydiscount'){; ?>active<?php }; ?>" href="/student.discount.mydiscount">
							<span class="nav-icon nav-studentDiscount"></span>
							<span class="nav-tag">
								<?php echo tpl_modifier_tr('我的优惠券','LearningCenter'); ?>
							</span>
						</a>
					</li>
				</ul>
				<?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])&&SlightPHP\Tpl::$_tpl_vars["type"]=='teacher')){; ?>
				<ul class="left-menu">
					<?php /*
					<li <?php if(SlightPHP\Tpl::$_tpl_vars["learnCss"]=="edit"){; ?>class="curr"<?php }; ?>><a href="/teacher.manage.edit"><span class="icon help-arrow-icon"></span><span class="t-fs c-fl"></span><span class="icon icon7 c-fl"></span><?php echo tpl_modifier_tr('教师资料','LearningCenter'); ?></a> </li>
					*/?>
                    <li class=' nav-menu-box ' >
                        <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["learnCss"]=='entry'){; ?>active<?php }; ?>" href="/teacher.detail.entry/<?php echo SlightPHP\Tpl::$_tpl_vars["userId"]; ?>">
                            <span class="nav-icon nav-teacherMain"></span>
							<span class="nav-tag">
								<?php echo tpl_modifier_tr('我的主页','LearningCenter'); ?>
							</span>
                        </a>
                    </li>
                    <li class=' nav-menu-box ' >
                        <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["learnCss"]=='newtimetable'){; ?>active<?php }; ?>" href="/teacher.course.timetable2">
                            <span class="nav-icon nav-teacherCourse"></span>
							<span class="nav-tag">
								<?php echo tpl_modifier_tr('课程表','LearningCenter'); ?>
							</span>
                        </a>
                    </li>
                    <li class=' nav-menu-box ' >
                        <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["learnCss"]=='teacherOfCourseList'){; ?>active<?php }; ?>" href="/teacher.course.teacherOfCourseList">
                            <span class="nav-icon nav-teacherList"></span>
							<span class="nav-tag">
								<?php echo tpl_modifier_tr('在教课程','LearningCenter'); ?>
							</span>
                        </a>
                    </li>
					<li class=' nav-menu-box' >
						<a class="nav-menu  <?php if(SlightPHP\Tpl::$_tpl_vars["learnCss"]=='teacherTaskList'){; ?>active<?php }; ?>" href="/task/publishTask/teacherTaskList">
							<span class="nav-icon nav-workList"></span>
							<span class="nav-tag">
								<?php echo tpl_modifier_tr('作业列表','LearningCenter'); ?>
							</span>
						</a>
					</li>
					<!--
					<li <?php if(SlightPHP\Tpl::$_tpl_vars["learnCss"]=="timetable"){; ?>class="curr"<?php }; ?>>
						<a href="/teacher.course.timetable">
							<span class="menu-list-side"></span>
							<span class="icon help-arrow-icon"></span>
							<span class="t-fs c-fl"></span>
							<span class="icon icon5 c-fl"></span>
							<?php echo tpl_modifier_tr('我的课程','LearningCenter'); ?>
							<span class="arrow-down"></span>
						</a>
					</li>
					-->
                    <li class=' nav-menu-box ' >
                        <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["learnCss"]=='student'){; ?>active<?php }; ?>" href="/teacher.manage.student">
                            <span class="nav-icon nav-teacherStudent"></span>
							<span class="nav-tag">
								<?php echo tpl_modifier_tr('我的学生','LearningCenter'); ?>
							</span>
                        </a>
                    </li>
                    <li class=' nav-menu-box ' >
                        <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["learnCss"]=='help'){; ?>active<?php }; ?>" href="/teacher.course.help">
                            <span class="nav-icon nav-teacherHelp"></span>
							<span class="nav-tag">
								<?php echo tpl_modifier_tr('如何上课','LearningCenter'); ?>
							</span>
                        </a>
                    </li>
				</ul>
				<?php }; ?>
				
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])&&SlightPHP\Tpl::$_tpl_vars["type"]=='student'){; ?>
                <a href="/teacher.course.teacherOfCourseList" class="c-fr orange-link" style="margin-top:20px">
                	<span class="switch-icon  icon"></span>
					<?php if(isset(SlightPHP\Tpl::$_tpl_vars["is_pro"])&&SlightPHP\Tpl::$_tpl_vars["is_pro"]==0){; ?>
                	<?php echo tpl_modifier_tr('切换教学中心','LearningCenter'); ?>
					<?php }; ?>
                </a>
                <?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])&&SlightPHP\Tpl::$_tpl_vars["type"]=='teacher'){; ?>
                <a href="/student.course.mycourse" class="c-fr orange-link" style="margin-top:20px">
                	<span class="switch-icon icon"></span>
                	<?php echo tpl_modifier_tr('切换学习中心','LearningCenter'); ?>
                </a>
                <?php }; ?>
        </div>
