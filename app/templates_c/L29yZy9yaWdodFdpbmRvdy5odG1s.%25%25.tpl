<!--右侧导航-->
<div class="wo-top" id="wo-top">
    <ul>
        <?php if(isset(SlightPHP\Tpl::$_tpl_vars["customerData"]->weima)&&!empty(SlightPHP\Tpl::$_tpl_vars["customerData"]->weima)){; ?>
        <li class="QR-code-li">
            <span class="QR-code icon"></span>
            <div class="QR-code-box" >
                <div class="QR-tag">
                    扫描下载APP
                </div>
                <?php foreach(SlightPHP\Tpl::$_tpl_vars["customerData"]->weima as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                <div class="QR-box">
                    <img src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_value; ?>" alt="">
                </div>
                <?php }; ?>
            </div>
        </li>
        <?php }; ?>
        <?php if(isset(SlightPHP\Tpl::$_tpl_vars["qqCustomerInfo"])&&!empty(SlightPHP\Tpl::$_tpl_vars["qqCustomerInfo"])){; ?>
        <li class="info-li">
            <span class="QQ icon"></span>
            <div class="info-box">
                <?php foreach(SlightPHP\Tpl::$_tpl_vars["qqCustomerInfo"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                <a onclick="openQQ('<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_value; ?>')"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_name; ?>:<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_value; ?></a> <br>
                <?php }; ?>
            </div>
            <!--<?php if(isset(SlightPHP\Tpl::$_tpl_vars["v"]->qqStatus)&&SlightPHP\Tpl::$_tpl_vars["v"]->qqStatus=='0'){; ?>-->
            <!--<li class="off-line" style="display:none\9"><a class="length_sl" onclick="openQQ('<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_value; ?>')"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_name; ?></a>-->
            <!--</li>-->
            <!--<?php }elseif((isset(SlightPHP\Tpl::$_tpl_vars["v"]->qqStatus)&&SlightPHP\Tpl::$_tpl_vars["v"]->qqStatus=='1')){; ?>-->
            <!--<li style="display:none\9"><a  class="length_sl" onclick="openQQ('<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_value; ?>')"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_name; ?></a></li>-->
            <!--<?php }; ?>-->

        </li>
        <?php }; ?>
        <?php if(isset(SlightPHP\Tpl::$_tpl_vars["customerData"]->qqun)&&!empty(SlightPHP\Tpl::$_tpl_vars["customerData"]->qqun)){; ?>
        <li class="info-li">
            <span class="QQ-qun icon"></span>
            <div class="info-box">
                <?php foreach(SlightPHP\Tpl::$_tpl_vars["customerData"]->qqun as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                <a target="_blank" href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_code; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_name; ?>:<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_value; ?></a> <br/>
                <?php }; ?>
            </div>
        </li>
        <?php }; ?>
        <?php if(isset(SlightPHP\Tpl::$_tpl_vars["customerData"]->tel)&&!empty(SlightPHP\Tpl::$_tpl_vars["customerData"]->tel)){; ?>
        <li class="info-li">
            <span class="advice icon"></span>
            <div class="info-box">
            <?php foreach(SlightPHP\Tpl::$_tpl_vars["customerData"]->tel as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->type_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_name; ?>:<?php }; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_value; ?> <br/>
            <?php }; ?>
            </div>
        </li>
        <?php }; ?>
        <li id="back-top">
            <span class="back-top icon"></span>
        </li>
    </ul>
</div>
<script>
function openQQ(qq) {
    window.location.href = "tencent://message/?uin=" + qq + "&Site=gbgjs.com&Menu=yes";
}
if ($(window).width() > 768) {
    $(window).scroll(function() {
        if ($(document).scrollTop() > 250) {
            $("#wo-top").css("display", 'block');
        } else {
            $("#wo-top").css("display", 'none');
        }
    });
    $("#back-top").click(function(){
        $('html,body').animate({ scrollTop: '0px' },'slow');
    });
}
</script>
<!-- 上课提醒
<section class="class-reminder hidden-xs">
    <div class="reminder-ct reminder-ct-one fs14">
        <span class="btn-clear clear-reminder-btn"></span>
        <h6>上课提醒:</h6>
        <p>
            小学三年级数学课程经典奥数
        </p>
        <p>
            第三章 <span style="color:#f01400;">8：00</span> 正在上课
        </p>
        <h5 class="tec mt10">
            <a href="" title="" class="reminder-comin">进入课堂</a>
        </h5>
    </div>
</section>
<section class="class-reminder hidden-xs">
    <div class="reminder-ct reminder-ct-two fs14">
        <span class="btn-clear clear-reminder-btn"></span>
        <h6>上课提醒:</h6>
        <p>
            小学三年级数学课程经典奥数
        </p>
        <p>
            第三章 <span style="color:#f01400;">8：00</span> 正在上课
        </p>
        <h5 class="tec mt10">
            <a href="" title="" class="reminder-comin">进入课堂</a>
        </h5>
    </div>
</section>
<section class="class-reminder hidden-xs">
    <div class="reminder-ct reminder-ct-three fs14">
        <span class="btn-clear clear-reminder-btn"></span>
        <h6>上课提醒:</h6>
        <p>
            小学三年级数学课程经典奥数
        </p>
        <p>
            第三章 <span style="color:#f01400;">8：00</span> 正在上课
        </p>
        <h5 class="tec mt10">
            <a href="" title="" class="reminder-comin">进入课堂</a>
        </h5>
    </div>
</section>
<section class="class-reminder hidden-xs">
    <div class="reminder-ct reminder-ct-four fs14">
        <span class="btn-clear clear-reminder-btn"></span>
        <h6>上课提醒:</h6>
        <p>
            小学三年级数学课程经典奥数
        </p>
        <p>
            第三章 <span style="color:#f01400;">8：00</span> 正在上课
        </p>
        <h5 class="tec mt10">
            <a href="" title="" class="reminder-comin">进入课堂</a>
        </h5>
    </div>
</section>
<section class="class-reminder hidden-xs">
    <div class="reminder-ct reminder-ct-five fs14">
        <span class="btn-clear clear-reminder-btn"></span>
        <h6>上课提醒:</h6>
        <p>
            小学三年级数学课程经典奥数
        </p>
        <p>
            第三章 <span style="color:#f01400;">8：00</span> 正在上课
        </p>
        <h5 class="tec mt10">
            <a href="" title="" class="reminder-comin">进入课堂</a>
        </h5>
    </div>
</section>
 /上课提醒 -->
<script type="text/javascript">
$(function() {
    //上课提醒
    $('.clear-reminder-btn').click(function() {
        $(this).parents('.class-reminder').hide();
    })
})
</script>
