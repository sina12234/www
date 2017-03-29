
<div class="th_banner" style="background: url('<?php echo SlightPHP\Tpl::$_tpl_vars["info"]->imgBanner; ?>') top center no-repeat">
    <!--banner换肤区域-->
    <div  class="container_teac container">
        <!--<img src="<?php echo SlightPHP\Tpl::$_tpl_vars["info"]->imgBanner; ?>"  class="banner_img"/>-->
        <?php if(SlightPHP\Tpl::$_tpl_vars["userType"] && (SlightPHP\Tpl::$_tpl_vars["uid"] == SlightPHP\Tpl::$_tpl_vars["tid"])){; ?>
        <a href="javascript:;" class="skin icon_pic" id="skin"></a>
        <?php }; ?>
        <div class="row col-md-20  th_pic tec">
            <div class="th_p col-md-8 col-md-offset-6 tac">
					<span class="pho_pic user-avatar">
						<img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->thumb_big)){; ?><?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["info"]->thumb_big); ?><?php }; ?>" />
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planid"])){; ?>
						<span class="video-box visible-lg" data-planid="<?php echo SlightPHP\Tpl::$_tpl_vars["planid"]; ?>">
							<span class="index-teac-vio pos-rel"></span>
							<span class="video-j pos-abs">视频简介</span>
							<span class="video-img"></span>
						</span>
					</span> 
					<?php }; ?>
				<h2 class="mt25">
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->real_name; ?><?php }; ?>
                    <em class="icon_pic <?php if(SlightPHP\Tpl::$_tpl_vars["info"]->gender == 2){; ?>icon_woman<?php }; ?> <?php if(SlightPHP\Tpl::$_tpl_vars["info"]->gender == 1){; ?>icon_man<?php }; ?>"></em>
                </h2>
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->brief_desc)){; ?>
                    <p class="mt20"><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->brief_desc; ?></p>
                <?php }else{; ?>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["uid"] == SlightPHP\Tpl::$_tpl_vars["tid"]){; ?>
                        <p class="mt20">一句话介绍下自己，让别人更了解你</p>
                    <?php }else{; ?>
                        <p class="mt20">还没有填写一句话简介</p>
                    <?php }; ?>
                <?php }; ?>
            </div>
        </div>
        <!--关注-->
        <?php if(SlightPHP\Tpl::$_tpl_vars["userType"] && (SlightPHP\Tpl::$_tpl_vars["uid"] == SlightPHP\Tpl::$_tpl_vars["tid"])){; ?>
            <?php if(SlightPHP\Tpl::$_tpl_vars["favTotal"]){; ?>
                <div class="col-md-6 col-md-offset-9 th_b_nav ">
                    <a href="#" class="b_nav1_h"><i class="icon_pic"></i><?php echo SlightPHP\Tpl::$_tpl_vars["favTotal"]; ?>个关注</a>
                </div>
            <?php }; ?>
        <?php }else{; ?>
        <!--喜欢-->
        <div class="col-md-6 col-md-offset-15 th_b_nav">
            <a href="javascript:;" class="<?php if(SlightPHP\Tpl::$_tpl_vars["isFav"]){; ?>b_nav1_h<?php }else{; ?>b_nav1<?php }; ?>" onclick="favAction(<?php echo SlightPHP\Tpl::$_tpl_vars["isFav"]; ?>)"><i class="icon_pic"></i><?php if(SlightPHP\Tpl::$_tpl_vars["isFav"]){; ?>已喜欢<?php }else{; ?>喜欢<?php }; ?></a>
             <a href="javascript:;" class="b_nav2"><i class="icon_pic"></i>发消息</a>
            <!-- <a href="#" class="b_nav3"><i class="icon_pic"></i>分享</a>-->
        </div>
        <?php }; ?>
    </div>
    <!--open-->
    <div class="skin_box" style="display: none">
        <div class="container skin_cent">
            <ul class="skin_bg">
                <li><em></em><img src="<?php echo utility_cdn::img('/assets_v2/img/skin1.jpg'); ?>"></li>
                <li><em></em><img src="<?php echo utility_cdn::img('/assets_v2/img/skin2.jpg'); ?>"></li>
                <li><em></em><img src="<?php echo utility_cdn::img('/assets_v2/img/skin3.jpg'); ?>"></li>
                <li><em></em><img src="<?php echo utility_cdn::img('/assets_v2/img/skin4.jpg'); ?>"></li>
                <li><em></em><img src="<?php echo utility_cdn::img('/assets_v2/img/skin5.jpg'); ?>"></li>
            </ul>
            <div class="clear">
                <div class="col-md-10 col-md-offset-8 s_choice">
                    <a class="preser" href="javascript:;">保存</a>
                    <a class="cancel" href="javascript:;">取消</a>
                </div>
            </div>
        </div>
    </div>
</div>

<nav class="th_nav tac col-xs-20">
    <div class="container swiper-container swiper-continer">
      <div class="swiper-ul-width swiper-wrapper">
        <li class="swiper-slide col-xs-4">
            <a <?php if(SlightPHP\Tpl::$_tpl_vars["nav"]=="entry"){; ?>class="nav_hover"<?php }; ?> href="/index/teacherblog/entry/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>">
                <?php if(SlightPHP\Tpl::$_tpl_vars["uid"] == SlightPHP\Tpl::$_tpl_vars["tid"]){; ?>我<?php }else{; ?><?php if(SlightPHP\Tpl::$_tpl_vars["info"]->gender == 1){; ?>他<?php }else{; ?>她<?php }; ?><?php }; ?>的首页
            </a>
        </li>
        <li class="swiper-slide col-xs-4"><a <?php if(SlightPHP\Tpl::$_tpl_vars["nav"]=="course"){; ?>class="nav_hover"<?php }; ?> href="/index/teacherblog/course/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>">主讲课程</a></li>
        <li class="swiper-slide col-xs-4"><a <?php if(SlightPHP\Tpl::$_tpl_vars["nav"]=="style"){; ?>class="nav_hover"<?php }; ?> href="/index/teacherblog/style/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>">教师相册</a></li>
        <li class="swiper-slide col-xs-4"><a <?php if(SlightPHP\Tpl::$_tpl_vars["nav"]=="article"){; ?>class="nav_hover"<?php }; ?> href="/index/teacherblog/article/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>">文章资料</a></li>
        <li class="swiper-slide col-xs-4"><a <?php if(SlightPHP\Tpl::$_tpl_vars["nav"]=="comment"){; ?>class="nav_hover"<?php }; ?> href="/index/teacherblog/comment/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>">学生评价</a></li>
      </div>
    </div>
</nav>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/swiper.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.user.messages.js'); ?>"></script>
<script>
//teacherChat
/*
function postChatCtrl() {
    var content = '很高兴能能和你聊天哦~';
    var userFrom = <?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>;
    $.post('/message/ajax/DirectChat', { userFrom:userFrom, content:content }, function(r) {
        if(r.code) {
            layer.msg(r.errMsg);
        }else {
            location.href = '/user.message';
        }
    },'json')
}
*/
//发信息
$(".b_nav2").click(
        function postChatCtrl() {
            var content = '很高兴能和你聊天哦~';
            var userFrom = <?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>;
            $.post('/message/ajax/DirectChat', { userFrom:userFrom, content:content }, function(r) {
                if(r.code) {
                    layer.msg(r.errMsg);
                }else {
                    location.href = '/index.user.message';
                }
            },'json')
        }
);
function favAction(m)
{
    var uid = <?php echo SlightPHP\Tpl::$_tpl_vars["uid"]; ?>;
    var tid = <?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>;
    var w,h;

    if($(window).width()>800){
        w='520px';
        h='420px';
    }else{
        w='90%';
        h='420px';
    }

    if (!uid) {
        if($(window).width()<768){
            location.href='/site.main.login/?url=//'+location.hostname+"/index.teacherblog.entry/"+tid;
        }else {
            layer.open({
                type: 2,
                title: false,
                area: [w, h],
                shadeClose: true,
                content: ['/index.main.alertLogin/addFavTeacher/' + tid, 'no']
            });
        }
        return false;
    }

    if (m) {
        $.post("/teacher/ajax/cancelFav", { uid: uid, tid: tid },
                function (d) {
                    if (d.code == 0) {
                        layer.msg('操作成功', { icon: 1 }, function () {
                            location.reload();
                        });
                    } else {
                        layer.msg('操作失败');
                    }
                }, 'json'
        );
    } else {
        $.post("/teacher/ajax/AddFav", { uid: uid, tid: tid },
                function (d) {
                    if (d.code == 0) {
                        layer.msg('操作成功', { icon: 1 }, function () {
                            location.reload();
                        });
                    } else {
                        layer.msg('操作失败');
                    }
                }, 'json'
        );
    }
};

$(function(){
		var planId;
		$('.video-box').click(function(){
			planId=$(this).attr('data-planid');
			layer.open({
				  type: 2,
				  title:false,
				  shadeClose: true,
				  area: ['710px', '399px'],
				  content:'/index.teacher.TeacherVideoPreview#'+planId  //iframe的url
			});
		})	
        //详细
        var sText= $.trim($(".intro").text());
        if(sText.length>=160){
            $(".details").show();
        }else{
            $(".details").hide();
        }

        $(".details").click(function(){
            if($(".intro").hasClass("furl")){
                $(".intro").css("height","52px").removeClass('furl');
                $(".details").html("【展开】");
            }else{
                $(".intro").css("height","auto").addClass('furl');
                $(".details").html("【收起】");
            }
        });

        $("#skin").click(function(){
            $(".skin_box").show();
        });
        //背景选择
        var src_img='';
        $(".skin_bg li").click(function(){
            var _this=$(this);
            _this.find("em").addClass("icon_pic");
            _this.siblings().find("em").removeClass("icon_pic");
            src_img=_this.find("img").attr("src");
            $(".th_banner").css("background-image",'url('+src_img+')');
        });
        //关闭弹层
        $(".s_choice .cancel").click(function(){
            $(".skin_box").hide();
            location.reload();
        });
        //保存请求
        $(".preser").click(function(){
            $.ajax({
                type: "POST",
                url: "/teacher/ajax/UpdateImgPath",
                data: { src:src_img, tid:<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?> },
                success: function(){
                    $(".skin_box").hide();
                },
                error:function(){
                    location.msg('保存失败');
                }
            });
        })
        if($(window).width()<760){
            var mySwiper = new Swiper('.swiper-container', {
                slidesPerView :2
                //autoplay: 1000,//可选选项，自动滑动
            })
            //alert(123213);
            var li_w=$(".swiper-ul-width li").outerWidth();
            var li_l=$(".swiper-ul-width li").length;
            var ul_w=li_w*li_l+40;
            $(".swiper-ul-width").css("width",ul_w+'px');
        }

   })
</script>
