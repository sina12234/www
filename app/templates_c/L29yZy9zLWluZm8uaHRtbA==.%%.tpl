<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>学生详情 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 学生详情 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<body>
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class="pd30">
    <div class="container ">
        <div class="row">
        <?php echo tpl_function_part("/org.main.menu.student"); ?>
        <div class="right-main col-sm-16">
    <div class="content">
        <div class="manage-path fs14"><a href="/org/student/list"><?php echo tpl_modifier_tr('返回','org'); ?></a>><span class="cGray"><?php echo tpl_modifier_tr('学生详情','org'); ?></span></div>
        <div class="t-data">
            <div class="face col-sm-6 col-md-4"><img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->avatar->large)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->avatar->large; ?><?php }; ?>" alt=""></div>
            <div class="info col-sm-14 fs14">
                <p class="account col-sm-20"><span class="label"><?php echo tpl_modifier_tr('姓名','org'); ?>：</span>
                    <span class="label-for">
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->profile->real_name)){; ?>
                        <?php echo SlightPHP\Tpl::$_tpl_vars["info"]->profile->real_name; ?>
                        <?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["info"]->name))){; ?>
                        <?php echo SlightPHP\Tpl::$_tpl_vars["info"]->name; ?>
                        <?php }; ?>
                    </span>
                </p>
                <ul class="col-sm-5 col-md-10">
                    <li>
                        <div class="label"><?php echo tpl_modifier_tr('年龄','org'); ?>：</div>
                        <div class="label-for"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->student->birthday)){; ?><?php echo utility_tool::getAge(SlightPHP\Tpl::$_tpl_vars["info"]->student->birthday); ?><?php }; ?><?php echo tpl_modifier_tr('岁','org'); ?></div>
                    </li>
                    <li>
                        <div class="label"><?php echo tpl_modifier_tr('手机','org'); ?>：</div>
                        <div class="label-for"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->mobile)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->mobile; ?><?php }; ?></div>
                    </li>
                    <li>
                        <div class="label"><?php echo tpl_modifier_tr('地区','org'); ?>：</div>
                        <div class="label-for"><?php echo SlightPHP\Tpl::$_tpl_vars["geo"]; ?></div>
                    </li>
                                <!--<li>
                                    <div class="label">学校：</div>
                                    <div class="label-for">背景一种</div>
                                    </li>-->
                    <li>
                        <div class="label"><?php echo tpl_modifier_tr('年级','org'); ?>：</div>
                        <div class="label-for"><?php echo SlightPHP\Tpl::$_tpl_vars["grade"]; ?></div>
                    </li>
                </ul>
                <ul class="col-sm-7 col-md-10">
                    <li>
                        <div class="label"><?php echo tpl_modifier_tr('最后登录时间','org'); ?>：</div>
                        <div class="label-for"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->student->last_login)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->student->last_login; ?><?php }; ?></div>
                    </li>
                    <!-- <li>
                      <span class="cLightgray">学时：</span>80 | <span class="cLightgray">报名课程：</span>80 | <span class="cLightgray">正在学习课程：</span>5
                    </li>
                    <li>
                        <span class="cLightgray">缺席：</span>80 | <span class="cLightgray">迟到：</span>80 | <span class="cLightgray">未交作业：</span>5
                    </li>
                    <li>
                        <div><span class="cLightgray">随堂测试：</span><span class="correct-icon"></span>正确率80% | <span class="yes-icon"></span>对8 | <span class="wrong-icon"></span>错8</div>
                    </li>-->
                </ul>
            </div>
            <div class="bar"><span><?php echo tpl_modifier_tr('上课列表','org'); ?></span><span class="c-fr"><?php echo tpl_modifier_tr('共','org'); ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list"])){; ?><?php echo count(SlightPHP\Tpl::$_tpl_vars["list"]); ?><?php }; ?><?php echo tpl_modifier_tr('个','org'); ?></span></div>
            <ul class="t-class-list">
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list"])){; ?>
                <?php foreach(SlightPHP\Tpl::$_tpl_vars["list"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                <li>
                    <div class="t-course col-sm-7 col-md-8">
                        <div class="item fs16"><a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_id']; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['title']; ?></a></div>
                        <div class="item cgray"><?php if(SlightPHP\Tpl::$_tpl_vars["v"]['price']){; ?><span class="cRed fs14">￥<?php echo number_format(SlightPHP\Tpl::$_tpl_vars["v"]['price']/100,2); ?><?php }else{; ?><span class="cGreen fs14"><?php echo tpl_modifier_tr('免费','org'); ?><?php }; ?></span> | <span class="cLightgray"><?php echo tpl_modifier_tr('共','org'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['section_count']; ?><?php echo tpl_modifier_tr('章','org'); ?></span></div>
                        <div class="item fs14 cDarkgray"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['start_time']; ?> <?php echo tpl_modifier_tr('至','org'); ?> <?php echo SlightPHP\Tpl::$_tpl_vars["v"]['end_time']; ?> <!--每周二 18:00--></div>
                    </div>
                    <div class="t-class col-sm-4 col-md-8">
                        <div class="item fs14"><?php echo tpl_modifier_tr('班级情况','org'); ?></div>
                        <div class="item fs14"><span class="num-icon"></span> <?php echo tpl_modifier_tr('学生','org'); ?> (<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['user_total']; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['max_user']; ?>)</div>
                    </div>
                    <div class="col-sm-1 cy-new-t-info col-md-2">
                        <a href='javascript:;' courseId=<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_id']; ?> classId=<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['class_id']; ?>  class='cy-t-class'><?php echo tpl_modifier_tr('调班','org'); ?></a>
                    </div>
                </li>
                <?php }; ?>
                <?php }; ?>
            </ul>
        </div>
    </div>
</div>
            <div class='clear'></div>
        </div>
    </div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
<script>

var lan = getCookie('language');
$(function(){
    $('.cy-t-class').on('click',function(){
        var cid = $(this).attr('courseId');
        var classId = $(this).attr('classId');
        var uid = <?php echo SlightPHP\Tpl::$_tpl_vars["uid"]; ?>;
         layer.open({
            type: 2,
            title: ['调班','color:#fff;background:#ffa81d'],
            area: ['560px','455px'],
            shadeClose: true,
            content: "/layer/main/changeClass/"+cid+"/"+uid+"/"+classId+"/"+lan
        });
    });
})

function getCookie(cookieName){
    var strCookie = document.cookie;
    var arrCookie = strCookie.split("; ");
    for(var i=0;i<arrCookie.length;i++){
        var arr = arrCookie[i].split("=");
        if(cookieName == arr[0]){
            return arr[1];
        }
    }
    return "";
}
</script>
</html>
