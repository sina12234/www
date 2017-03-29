<!DOCTYPE html>
<html>
<head>
<title>教师团队 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 教师团队 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<script type="text/javascript">
window.onscroll = function(){
var t = $(window).scrollTop();
if(t > 100){
    $('.subject').css({
        'position':'fixed',
        'background':'#000',
        'top':0,
    'right':0
    });
    $('.subject').find('a').css('color','#fff');
}else{
    $('.subject').css({
        'position':'relative',
        'background':'',
        'top':'0px',
        'opacity':'0.7'
    });
    $('.subject').find('a').css('color','#000');
 }
 }
</script>
<body>
<?php echo tpl_function_part("/site.main.nav.teacher"); ?>
<section class="subject bgf">
    <div class="container">
        <ul class="row">
            <li><p href=""><img src="<?php echo utility_cdn::img('/assets/images/steachx.png'); ?>" alt=""></p></li>
            <li id="major_0">
				<a href="javascript:void(0);" onclick="getData(0)"><?php echo tpl_modifier_tr('全部','course.list'); ?></a>
			</li>
            <?php foreach(SlightPHP\Tpl::$_tpl_vars["subject"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
            <li id="major_<?php echo SlightPHP\Tpl::$_tpl_vars["k"]; ?>">
                <a href="javascript:void(0);" onclick="getData(<?php echo SlightPHP\Tpl::$_tpl_vars["k"]; ?>)"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["v"],'course.list'); ?></a>
            </li>
            <?php }; ?>
        </ul>
    </div>
</section>

<section class="pdb30 bgf">
    <div class="container wrap-box">
       <div class="row wrap-ul" id='row'></div>
    </div>
</section>
<div id="rightWindow"></div>
<script>$("#rightWindow").load("/org.rightWindow.rightWindow");</script>
<?php echo tpl_function_part("site.main.footer"); ?>
</body>
</html>
<script>//控制底部脚注
$(function() {
  if(screen.availWidth > 768) {
  //screen.availWidth浏览器可用宽度
    var oWrapBox = $(".wrap-box");
    var oListLength = $(".wrap-ul .lector-list").length;
    if(oListLength <= 6) {
      //oWrapBox.css( "height" , "720px" );
    }else{
      oWrapBox.css( "height" , "auto" );
    }
  }
    getData(0);
    if($(window).width()>800){
        if($(window).height()>=$(document).height()){
            oWrapBox.height($(window).height()-70-145);
        }
    }
});
function getData(k)
{
    $("#major_"+k).addClass('qb').siblings().removeClass('qb');
    var data = eval(<?php echo SlightPHP\Tpl::$_tpl_vars["data"]; ?>);
    var html = '';
    for(var i in data)
    {
        if(i == k)
        {
            for(var j in data[i])
            {
                html+="<div class='col-sm-4 col-xs-10 lector-list'>";
                html+='<div class="u-lector">';
                html+="<a href='/teacher/detail/entry/"+data[i][j].fk_user+"' target='_blank'>";
                html+="<div class='pic u-lector-pic'>";
                html+="<img class='imgPic' src="+data[i][j].thumb_big+">";
                html+="</div>";
                html+="<div class='u-name fs16 tac'>"+data[i][j].name+"</div>";
                if(data[i][j].title!='')
                {
                    html+="<div class='fs14 u-title'>"+data[i][j].title+"</div>";
                }else{
                    html+="<div class='fs14 u-title'>TA还没有头衔</div>";
                }
                if(data[i][j].desc!=''){
                    html+="<div class='college fs12'>"+data[i][j].desc+"</div>";
                }else{
                    html+='<div class="college fs12"><p class="tac">还未完善简介哦！</div>';
                }
                html+="</a>";
                html+="</div>";
                html+="</div>";
            }
        }
    }
    $("#row").html(html);
}
</script>
