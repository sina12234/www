<div class="hidden-xs col-sm-3 col-md-4 user-left-menu pd0">
    <ul class="left-menu">
        <li class=' nav-menu-box '  data-open="0" >
          <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='home'){; ?>active<?php }; ?>" href="/org">
              <span class="nav-icon nav-center"></span>
              <span class="nav-tag">
                    <?php echo tpl_modifier_tr('机构中心','org'); ?>
              </span>
          </a>
        </li>
        <li class=' nav-menu-box' >
            <a class="nav-menu open <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='setting' or SlightPHP\Tpl::$_tpl_vars["subnav"]=='template' or SlightPHP\Tpl::$_tpl_vars["subnav"]=='apptemplate' or SlightPHP\Tpl::$_tpl_vars["subnav"]=='channelSet'){; ?>active<?php }; ?>" data-open="1" >
                <span class="nav-icon nav-page-set"></span>
                <span class="nav-tag">
                    <?php echo tpl_modifier_tr('页面设置','org'); ?>
                </span>
                <span class="inner-level-icon"></span>
            </a>
            <ul class="second-level <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='setting' or SlightPHP\Tpl::$_tpl_vars["subnav"]=='template' or SlightPHP\Tpl::$_tpl_vars["subnav"]=='apptemplate' or SlightPHP\Tpl::$_tpl_vars["subnav"]=='channelSet'){; ?>show<?php }; ?>">
                <li class="second-level-menu">
                    <a href="/org.main.setting">
                        <span class="<?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='setting'){; ?>active<?php }; ?>">基础设置</span>
                    </a>
                </li>
                <li class="second-level-menu">
                    <a href="/org.main.template">
                        <span class="<?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='template'){; ?>active<?php }; ?>">首页模块</span>
                    </a>
                </li>
                <?php if(isset(SlightPHP\Tpl::$_tpl_vars["is_pro"])&&SlightPHP\Tpl::$_tpl_vars["is_pro"]==1){; ?>
                <li class="second-level-menu">
                    <a href="/org.channel.channelList">
                        <span class="<?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='channelSet'){; ?>active<?php }; ?>">频道设置</span>
                    </a>
                </li>
                <li class="second-level-menu">
                    <a href="/org.main.apptemplate">
                        <span class="<?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='apptemplate'){; ?>active<?php }; ?>">App设置</span>
                    </a>
                </li>
                <?php }; ?>
            </ul>
        </li>
        <li class="nav-menu-box">
            <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='course'){; ?>active<?php }; ?> " href="/user.org.course">
                <span class="nav-icon nav-course"></span>
                <span class="nav-tag">
                     <?php echo tpl_modifier_tr('课程管理','org'); ?>
                </span>
            </a>
        </li>
        <li class="nav-menu-box">
            <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='stat'){; ?>active<?php }; ?> " href="/org/stat/UserStatistical">
                <span class="nav-icon nav-stat"></span>
                <span class="nav-tag">
                     <?php echo tpl_modifier_tr('数据统计','org'); ?>
                </span>
            </a>
        </li>
        <li class=' nav-menu-box '  >
            <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='teacher'){; ?>active<?php }; ?>" href="/org.teacher.list">
                <span class="nav-icon nav-teacher"></span>
                <span class="nav-tag">
                     <?php echo tpl_modifier_tr('教师管理','org'); ?>
                </span>
            </a>
        </li>
        <li class=' nav-menu-box '  >
            <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='student'){; ?>active<?php }; ?>" href="/org.student.list">
                <span class="nav-icon nav-student"></span>
                <span class="nav-tag">
                     <?php echo tpl_modifier_tr('学生管理','org'); ?>
                </span>
            </a>
        </li>
        <li class=' nav-menu-box '  >
            <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='promote'){; ?>active<?php }; ?>" href="/course.promote.center">
                <span class="nav-icon nav-promote"></span>
                <span class="nav-tag">
                     <?php echo tpl_modifier_tr('云课推广','org'); ?>
                </span>
            </a>
        </li>
        <li class=' nav-menu-box '  >
            <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='discount'){; ?>active<?php }; ?>" href="/org.discount.listv2">
                <span class="nav-icon nav-discount"></span>
                <span class="nav-tag">
                     <?php echo tpl_modifier_tr('优惠管理','org'); ?>
                </span>
            </a>
        </li>
        <li class=' nav-menu-box '  >
            <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='order'){; ?>active<?php }; ?>" href="/org.main.order">
                <span class="nav-icon nav-order"></span>
                <span class="nav-tag">
                     <?php echo tpl_modifier_tr('订单管理','org'); ?>
                </span>
            </a>
        </li>
        <li class=' nav-menu-box '  >
            <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='settle'){; ?>active<?php }; ?>" href="/org.settle">
                <span class="nav-icon nav-settle"></span>
                <span class="nav-tag">
                     <?php echo tpl_modifier_tr('结算管理','org'); ?>
                </span>
            </a>
        </li>
        <?php /*<li><a href="#"><span class="menu-icon8"></span><?php echo tpl_modifier_tr('数据统计','org'); ?></a></li>*/?>
        <li class=' nav-menu-box '  >
            <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='customer'){; ?>active<?php }; ?>" href="/org.customerServices.list">
                <span class="nav-icon nav-service"></span>
                <span class="nav-tag">
                     <?php echo tpl_modifier_tr('客服设置','org'); ?>
                </span>
            </a>
        </li>
        <li class=' nav-menu-box '  >
            <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='info'){; ?>active<?php }; ?>" href="/org.main.info">
                <span class="nav-icon nav-info"></span>
                <span class="nav-tag">
                     <?php echo tpl_modifier_tr('资料信息','org'); ?>
                </span>
            </a>
        </li>
        <li class=' nav-menu-box '  >
            <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='vote'){; ?> active<?php }; ?>" href="/org.vote.list">
                <span class="nav-icon nav-vote"></span>
                <span class="nav-tag">
                     <?php echo tpl_modifier_tr('运营管理','org'); ?>
                </span>
            </a>
        </li>
        <li class=' nav-menu-box '  >
            <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='activeNotice'){; ?> active<?php }; ?>" href="/org.main.activeNoticeList">
                <span class="nav-icon nav-notice"></span>
                <span class="nav-tag">
                     <?php echo tpl_modifier_tr('学习资讯','org'); ?>
                </span>
            </a>
        </li>
        <li class=' nav-menu-box '  >
            <a class="nav-menu <?php if(SlightPHP\Tpl::$_tpl_vars["subnav"]=='member'){; ?>active<?php }; ?>" href="/org.member">
                <span class="nav-icon nav-member"></span>
                <span class="nav-tag">
                     <?php echo tpl_modifier_tr('会员管理','org'); ?>
                </span>
            </a>
        </li>
    </ul>
</div>
<script>
    $('.nav-menu-box').on('click','.nav-menu',function () {
        var open = $(this).attr('data-open');
        var initNav = $(this).parent().find('.second-level');
        if(!$(this).hasClass('active')){
            var href = initNav.find('.second-level-menu').eq(0).find('a').attr('href');
            location.href = href;
        }
        if(open){
            if($(this).hasClass('open')){
                $(this).find('.inner-level-icon').addClass('pull-up');
                $(this).removeClass('open');
                initNav.removeClass('show');
            }else{
                $(this).find('.inner-level-icon').removeClass('pull-up');
                $(this).addClass('open');
                initNav.addClass('show');
            }
        }
    })
</script>
