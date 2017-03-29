/*
*Date:03-04-2016
*Time:10:45 Jquery.course.detail.js
*/

$(function() {
    $(".t-teacher-list .teacher-introduct").each(function() {
        var teacherIntroductLength = $(this).html().length;
        if(teacherIntroductLength > 21) {
            $(this).next(".t-teacher-detail").show();
        }
    })

//vip
    $('.vip-course-category').hover(function() {
        $(this).find('.vip-course-list').show();
    } ,function() {
        $(this).find('.vip-course-list').hide();
    })

    var classListCourseVideoTry = document.getElementById("video-try-icon"); //试看
    var classListCourseTaped = document.getElementById("course-video-icon"); //录播视频图标
    
    if(classListCourseVideoTry) {
        $(".course-service").show();
        $(".video-icon").show();
        $(".lesson-icon").hide();
        $(".course-time").removeClass("mt10");
    }

    if(classListCourseTaped) {
        $(".course-service").show();
        $(".video-icon").hide();
        $(".lesson-icon").show();
        $(".course-time").removeClass("mt10");
    }

    if(classListCourseVideoTry && classListCourseTaped) {
        $(".course-service").show();
        $(".lesson-icon").show();
        $(".video-icon").show();
        $(".course-time").removeClass("mt10");
    }

    $('.general-course-liBtn li').on('click',function() {
        var el = $(this).attr('name');
        $('html, body').animate({
            scrollTop: ($("#"+el).offset().top) - 40
        }, 10);
    });

    $(window).scroll(function(){
        var courserOutlineTop = $("#courserOutline").offset().top - 50;
        var courserSectionTop = $("#courserSection").offset().top - 40;
        var courserCommentTop = $("#courseDetailComment").offset().top - 20;
        var scroHeigth = $(this).scrollTop();

        if(scroHeigth >= courserCommentTop){
            $(".general-course-liBtn li").removeClass("curr");
            $(".float-course-liBtn li[name='courserComment']").addClass("curr");
        }else if(scroHeigth >= courserSectionTop){
            $(".general-course-liBtn li").removeClass("curr");
            $(".float-course-liBtn li[name='courserSection']").addClass("curr");
        }else if(scroHeigth >= courserOutlineTop){
            $(".general-course-liBtn li").removeClass("curr");
            $(".course-article-btn li[name='courserOutline']").addClass("curr");
            $(".float-course-liBtn li[name='courserOutline']").addClass("curr");
        }
    });

    $("#wrap-course-success").hide();

//文章课程
    $('.general-course-liBtn li').on('click',function() {
       $(this).addClass('curr').siblings().removeClass('curr');
    });

//浮动条
    $("#wrap-course-float").hide();
    $(window).scroll(function(){
        var scrollTop = $(document).scrollTop();
        if($(document).scrollTop()>350){
            $("#wrap-course-float").css({
                'display':'block',
                'top':scrollTop,
            });
        } else {
            $("#wrap-course-float").css('display','none');
        }
    
    })
    
        
    setTimeout(function() {
        if($(".t-student-list").find("li").length < 5) {
            $(".now-study-model").hide();
        }else {
            $(".now-study-model").show();
        }
    } ,2000)

})

//班级
function getClassData(){
    if(classList == "" || classList ==null) {
        return false;
    }else {
        var classListSixNameTpl = $('#classListSixNameTpl').html();
        var classListSixDataTpl = $('#classListSixDataTpl').html();
        var classListOneNameTpl = $('#classListOneNameTpl').html();
        var selectListNameTpl = $('#selectListNameTpl').html();
    }

    $('#classListSixName').html(Mustache.render(classListSixNameTpl, classList));
    $('#classListSixData').html(Mustache.render(classListSixDataTpl, classList));
    $('#classListOneName').html(Mustache.render(classListOneNameTpl, classList));
    $('#selectListName').html(Mustache.render(selectListNameTpl, classList));

    $('.detail-tab-btn li:first-child').addClass('curr');
    $('.detail-tab-content li:first-child').addClass('curr');

    $('.course-article-list ul:first-child').addClass('curr');
    $('.course-article-list ul:first-child').show().siblings().hide();

}
//正在学习
function getRegUserList() {
    $.post('/course/ajax/GetCourseRegUser', { cid: cid }, function (r) {
        if (r.code == 0) {
            var regUserListTpl = $('#regUserListTpl').html();
            $('.t-student-list').html(Mustache.render(regUserListTpl, r.result));
        }
    }, 'json');
}
