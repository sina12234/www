
            <div class="c-fr other">
                <?php if(empty(SlightPHP\Tpl::$_tpl_vars["user"])){; ?>
                <a href="/index.main.login" <?php if(SlightPHP\Tpl::$_tpl_vars["uri"]=='login'){; ?>class="cBlue"<?php }; ?>>登录</a>|<a href="/index.main.register" <?php if(SlightPHP\Tpl::$_tpl_vars["uri"]=='register'){; ?>class="cBlue"<?php }; ?>>注册</a>
                <?php }else{; ?>
                <ul class="user-menu">
                    <li class="user-menu-info hidden-sm hidden-xs">
                        <a href="#">
                            <span class="face">
                                <img src="<?php if(SlightPHP\Tpl::$_tpl_vars["user"]['large']){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['large']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/1.png'); ?><?php }; ?>">
                            </span>
                            <span id="chick-down-show"><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['name']; ?><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/index-down.png'); ?>"></span>
                        </a> <span class="c-fr">|</span>
                        <div class="sub-menu" id="sub-menu">
                            <div class="sub-menu-c">
                                <a href="/index.user.info"><span class="platform-icon form-icon-1"></span>基础资料</a>
                                <a href="/index.student.order"><span class="platform-icon form-icon-4"></span>我的订单</a>
                                <a class="hidden-lg hidden-md" href="/index.user.message"><span class="platform-icon form-icon-5"></span>我的消息</a>
                                <a href="/index.user.password"><span class="platform-icon form-icon-6"></span>安全设置</a>
                                <a href="/index.main.logout"><span class="platform-icon form-icon-7"></span>退出</a>
                            </div>
                        </div>
                    </li>
                    <li class="user-menu-info visible-sm visible-xs" id="mob_userlist">
                        <a href="javascript:void(0)">
                            <span class="face">
                                <img src="<?php if(SlightPHP\Tpl::$_tpl_vars["user"]['large']){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['large']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/1.png'); ?><?php }; ?>">
                            </span>
                            <span id="chick-down-show"><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['name']; ?><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/index-down.png'); ?>"></span>
                        </a> <span class="c-fr">|</span>
                        <div class="sub-menu" id="mob_submenu" style="display:none">
                            <div class="sub-menu-c">
                                <a href="/index.user.info"><span class="platform-icon form-icon-1"></span>基础资料</a>
                                <?php if(SlightPHP\Tpl::$_tpl_vars["user"]['types']->teacher==1){; ?>
                                <a href="/index.teacher.timetable"><span class="platform-icon form-icon-2"></span>教学管理</a>
                                <?php }; ?>
                                <a href="/index.growth.entry"><span class="platform-icon form-icon-3"></span>学习管理</a>
                                <a href="/index.student.order"><span class="platform-icon form-icon-4"></span>我的订单</a>
                                <a class="hidden-lg hidden-md" href="/index.user.message"><span class="platform-icon form-icon-5"></span>我的消息</a>
                                <a href="/index.user.password"><span class="platform-icon form-icon-6"></span>安全设置</a>
                                <a href="/index.main.logout"><span class="platform-icon form-icon-7"></span>退出</a>
                            </div>
                        </div>
                        </li>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["isAdmin"]==1){; ?>
                    <li class="hidden-sm hidden-xs jigou">
                        <a href="#">机构管理<img src="<?php echo utility_cdn::img('/assets_v2/img/platform/index-down.png'); ?>"></a> <span class="c-fr">|</span>
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgList"])){; ?>
                        <div class="sub-menu">
                            <div class="sub-menu-c">
                                <?php foreach(SlightPHP\Tpl::$_tpl_vars["orgList"] as SlightPHP\Tpl::$_tpl_vars["org"]){; ?>
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["org"]->subdomain; ?>" target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["org"]->subname; ?></a>
                                <?php }; ?>
                            </div>
                        </div>
                        <?php }; ?>
                    </li>
                    <?php }; ?>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["user"]['types']->teacher==1){; ?>
                    <li class="hidden-sm hidden-xs">
                        <a href="/index.teacher.timetable">教学管理</a> |
                    </li>
                    <?php }; ?>
                    <li class="hidden-sm hidden-xs">
                        <a href="/index.growth.entry">学习中心</a> |
                    </li>
                    <li class="hidden-sm hidden-xs">
                        <a href="/index.user.message" >
                            <span class="msg-icon icon">
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["retMessagesNum"]) && SlightPHP\Tpl::$_tpl_vars["retMessagesNum"] !=0){; ?>
                                <span class="msg-num"></span>
                                <?php }; ?>
                            </span>

                        </a>
                    </li>
                </ul>
                <?php }; ?>
            </div>
            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"])&&!empty(SlightPHP\Tpl::$_tpl_vars["successTips"])){; ?>
            <div class="tp-lg-success-tip hidden-xs">
                <div class="tp-lg-success-tip-bg">
                    <div class="container">
                        <div class="row">
                            <div class="tp-sub-menu-c">
                                <a href="/index.user.info" class="user-base-info" style="color:#fff;">
                                    <span class="platform-icon form-icon-1"></span>基础资料
                                </a>
                                <?php if(SlightPHP\Tpl::$_tpl_vars["user"]['types']->teacher==1){; ?>
                                <a class=" hidden-lg hidden-md" href="/index.teacher.timetable"><span class="platform-icon form-icon-2"></span>教学管理</a>
                                <?php }; ?>
                                <a class=" hidden-lg hidden-md"  href="/index.growth.entry"><span class="platform-icon form-icon-3"></span>学习中心</a>
                                <a href="/index.student.order"><span class="platform-icon form-icon-4"></span>我的订单</a>
                                <a class="hidden-lg hidden-md" href="/index.user.message"><span class="platform-icon form-icon-5"></span>我的消息</a>
                                <a href="/index.user.password"><span class="platform-icon form-icon-6"></span>安全设置</a>
                                <a href="/index.main.logout"><span class="platform-icon form-icon-7"></span>退出</a>
                                <div class="gray-hide-tip"></div>
                            </div>
                            <div class="tp-lg-success-tip-w365">
                                <a href="/index.user.info" class="user-info-btn"></a>
                                <a href="javascript:;" class="lg-close-btn"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $(function() {
                    //弹出层
                    $("body").css("overflow-y","hidden");
                    $(".tp-lg-success-tip-bg .lg-close-btn").click(function() {
                        $(".tp-lg-success-tip").hide();
                        $("body").css("overflow-y","auto");
                    });
                })
            </script>
			<?php }; ?>
<script type="text/javascript">
$(function() {
//mob menu
$("#mob_userlist").click(function(){
    if($("#mob_submenu").is(":hidden")){
        $("#mob_submenu").show();
        $("#mob_userlist").addClass("curr");
        $("#mob_userlist").removeClass("user-menu-info");
    }else{
        $("#mob_submenu").hide();
        $("#mob_userlist").removeClass("curr");
    }
})
});
</script>
