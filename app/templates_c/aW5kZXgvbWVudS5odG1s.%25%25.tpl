        <div class="user-left-menu col-lg-4 col-md-4 hidden-xs hidden-sm">
            <?php if(SlightPHP\Tpl::$_tpl_vars["type"]!='default'){; ?>
            <div class="user-info">
                <p class="face face-post-icon">
                    <a href="/index.user.uploadPic/1" class="face-fixed fs14"><em>点击修改头像</em></a>
                    <img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userInfo"]->avatar->large)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userInfo"]->avatar->large; ?><?php }else{; ?><?php echo utility_cdn::img(' /assets_v2/img/1.png'); ?><?php }; ?>" alt="" />
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"]) and (SlightPHP\Tpl::$_tpl_vars["type"]=='teacher')){; ?>
                    <span class="t-users-icon"><i>教师</i></span> 
                    <?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"]) and SlightPHP\Tpl::$_tpl_vars["type"]=='student'){; ?>
                    <span class="s-users-icon"><i>学生</i></span>
                    <?php }; ?>
                    <a href="/index.rank.rule" target="_blank" class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["userLevel"]; ?>" id="levelIconLeft"></a>
                </p>
                <p class="name fs18">
				<!--
                    span class="growth-user-name col-sm-15"><?php echo SlightPHP\Tpl::$_tpl_vars["userInfo"]->name; ?></span
                	span class="growth-schr-icon xiucai-icon1"></span
                -->
                        <?php echo SlightPHP\Tpl::$_tpl_vars["userInfo"]->name; ?>
                <!--
                   <span class="col-lg-8 col-md-10 fs12">
                   <?php if(SlightPHP\Tpl::$_tpl_vars["type"]=='student'){; ?>
                        <em class="s-student-tip-infos col-lg-20">学生</em>
                    <?php }else{; ?>
                        <em class="t-teacher-tip-infos col-lg-20">教师</em>
                    <?php }; ?>
                    </span>
                -->
				</p>
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["province"])){; ?>
                <p class="other col-sm-20">
                    <span class="col-lg-13 col-md-20 local-icon icon"><?php echo SlightPHP\Tpl::$_tpl_vars["province"]; ?> <?php echo SlightPHP\Tpl::$_tpl_vars["city"]; ?></span>
                    <a href="/index.user.info" class="col-lg-7 hidden-md c-fr" target="_blank" style="padding:0">
                        <span class="set-icon icon c-fl"></span>
                        <span class="c-fl">基础设置</span>
                    </a>
                </p>
                <?php }else{; ?>
                <p class="other cBlue hidden-xs hidden-sm col-lg-20 col-md-20">
                    <a href="/index.user.info">资料完善10%快去完善</a>
                </p>
                <?php }; ?>
            </div>
            <?php }; ?>
            
            <ul class="col-lg-20 fs16">
                <?php echo SlightPHP\Tpl::$_tpl_vars["menu"]; ?>
            </ul>
            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])&&SlightPHP\Tpl::$_tpl_vars["type"]=='student'){; ?>
            <a href="/index.teacher.timetable" class="c-fr" style="margin-top:10px"><span class="switch-icon icon"></span>切换教学中心</a>
            <?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])&&SlightPHP\Tpl::$_tpl_vars["type"]=='teacher'){; ?>
            <a href="/index.student.course" class="c-fr" style="margin-top:10px"><span class="switch-icon icon"></span>切换学习中心</a>
            <?php }; ?>
        </div>
