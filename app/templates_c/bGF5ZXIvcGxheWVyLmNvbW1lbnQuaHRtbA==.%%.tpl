<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>评论</title>
<meta name="title" content="高能100 - 专业的K12在线教育平台 在线直播">
<meta name="keywords" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线 k12 小学数学 初中数学">
<meta name="description" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线直播">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<meta name="renderer" content="webkit">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets_v2/css/bootstrap/bootstrap.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets_v2/css/player1.css'); ?>">
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/common/common.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/layer/layer.js'); ?>"></script>
<link rel="icon" type="image/x-icon" href="<?php echo utility_cdn::img('/assets_v2/img/favicon.ico'); ?>" />
<style>
.comment-layer{ float:left; padding:20px;background:#fff;height:245px;overflow-y:auto }
.comment-layer .rate{ float: left; padding: 20px 0 0; text-align: center; border-right:3px solid #ccc; }
.comment-layer .rate strong { font: 400 40px/20px arial; color: #000000; }
.comment-layer .percent{ float: left; padding: 0 10px; }
.comment-layer .percent dl{ float: left; padding: 0 0 10px 0; width: 100%; overflow: hidden; zoom: 1; }
.comment-layer .percent dt{ float: left; width: 73px; }
.comment-layer .percent dd{ float: left; color: #ff7800; }
.comment-layer .percent span.sel{ float: left; background: url(/assets_v2/img/star.png) -4px -33px no-repeat; }
.comment-layer .percent span{ float: left; width: 21px; height: 21px; background: url(/assets_v2/img/star.png) -4px -3px no-repeat; }
.comment-layer .comment-c{ float:left;border:1px solid #ccc;padding:10px 0; }
.comment-layer textarea{ height:100px }
.text-num{ position:absolute;bottom:5px;right:5px }
.comment-layer .comment-btn{ padding:0;text-align:center;padding-top:10px }
.comment-layer button{ width:75px;height:25px;line-height:20px;margin:0 10px;background:#ffa819;border:none;color:#fff; }
#sign_add{ top:50%;}
</style>
</head>
<body style="background:#000">
    <div class="comment-layer col-sm-20 col-xs-20" id="score_comment">
        <div id="tool"></div>
        <!--<div class="rate col-sm-4 col-xs-5">-->
            <!--<strong id="avg">5.0</strong>-->
            <!--<br><span class="fs14">综合评分</span>-->
        <!--</div>-->
        <div class="percent col-sm-17 col-xs-15 fs14" id="score_percent">
            <dl>
                <dt>综合评分：</dt>
                <dd id="avg_score">
                    <span class="sel" data-title="很差" data-score="1"></span><span class="sel" data-title="差" data-score="2"></span><span class="sel" data-title="还行" data-score="3"></span><span class="sel" data-title="满意" data-score="4"></span><span class="sel" data-title="很好" data-score="5"></span><i score_type="avg_score" data-score="5">很好</i>
                </dd>
            </dl>
            <!--<dl>-->
                <!--<dt>课程与描述相符：</dt>-->
                <!--<dd id="desc_score">-->
                    <!--<span class="sel" data-title="很差" data-score="1"></span><span class="sel" data-title="差" data-score="2"></span><span class="sel" data-title="还行" data-score="3"></span><span class="sel" data-title="满意" data-score="4"></span><span class="sel" data-title="很好" data-score="5"></span><i score_type="desc_score" data-score="5">很好</i>-->
                <!--</dd>-->
            <!--</dl>-->
            <!--<dl>-->
                <!--<dt>老师的讲解表达：</dt>-->
                <!--<dd id="explain_score">-->
                    <!--<span class="sel" data-title="很差" data-score="1"></span><span class="sel" data-title="差"  data-score="2"></span><span class="sel" data-title="还行"  data-score="3"></span><span class="sel" data-title="满意"  data-score="4"></span><span class="sel" data-title="很好"  data-score="5"></span><i score_type="explain_score" data-score="5">很好</i>-->
                <!--</dd>-->
            <!--</dl>-->
        </div>
        <div class="comment-c col-sm-20 col-xs-20">
          <textarea placeholder="写点什么吧，你的评价是老师前行的最大动力哟" id="comment_input" maxlength="100" class="col-sm-20 col-xs-20"></textarea>
          <span class="text-num">请输入<span id="num_in">100</span>字以内</span>
        </div>
        <div class="comment-btn col-sm-20 col-xs-20"><button id="comment_send">确定</button><button id="comment_cancel">取消</button></div>
    </div>
    <!--评论过-->
    <div class="comment-layer col-sm-20 col-xs-20" id="score_comment1">
        <!--<div class="rate col-sm-4 col-xs-5">-->
            <!--<strong id="avg1">5.0</strong>-->
            <!--<br><span class="fs14">综合评分</span>-->
        <!--</div>-->
        <div class="percent col-sm-17 col-xs-15 fs14" id="score_percent1">
            <dl>
                <dt>综合评分：</dt>
                <dd>
                    <span class="sel" data-title="很差" data-score="1"></span><span class="sel" data-title="差" data-score="2"></span><span class="sel" data-title="还行" data-score="3"></span><span class="sel" data-title="满意" data-score="4"></span><span class="sel" data-title="很好" data-score="5"></span><i score_type="student_score1" data-score="5">很好</i>
                </dd>
            </dl>
            <!--<dl>-->
                <!--<dt>课程与描述相符：</dt>-->
                <!--<dd>-->
                    <!--<span class="sel" data-title="很差" data-score="1"></span><span class="sel" data-title="差" data-score="2"></span><span class="sel" data-title="还行" data-score="3"></span><span class="sel" data-title="满意" data-score="4"></span><span class="sel" data-title="很好" data-score="5"></span><i score_type="desc_score1" data-score="5">很好</i>-->
                <!--</dd>-->
            <!--</dl>-->
            <!--<dl>-->
                <!--<dt>老师的讲解表达：</dt>-->
                <!--<dd>-->
                    <!--<span class="sel" data-title="很差" data-score="1"></span><span class="sel" data-title="差"  data-score="2"></span><span class="sel" data-title="还行"  data-score="3"></span><span class="sel" data-title="满意"  data-score="4"></span><span class="sel" data-title="很好"  data-score="5"></span><i score_type="explain_score1" data-score="5">很好</i>-->
                <!--</dd>-->
            <!--</dl>-->
        </div>
        <div class="col-sm-20 col-xs-20" id="comment_input1" style="height:90px">经评论了</div>
    </div>
</body>

</html>
<script type="text/javascript">
$(document).ready(function(e){

//    var _addURL = "/comment.course.addcommentscore";
    var _checkURL = "/comment/course/checkIsAddScore";
    var _addURL = "/comment/course/addscore";
    var _input = $("#comment_input");
    var _score = $("#score_comment");
    var planId=$.getUrlParam('planId');
    var course_id=$.getUrlParam('courseId');
    var request={};
    var commentStr = false;
    $.ajax({
        url:_checkURL,
        type:'post',
        data:{plan_id:planId,course_id:course_id},
        dataTYpe:'json',
        async:false,
        success:function(data){
            data=JSON.parse(data);
            data=data.result;
            if(data.code=="101"){
                commentStr = false
            }else{
                var r=data.data;
                request.comment=r.comment;
                request.score=r.score;
                request.course=r.fk_course;
                commentStr = true
            }
        }
    })

    var xindex=parent.layer.getFrameIndex(window.name);

    if(commentStr == null || !commentStr){
        $('#score_comment1').remove();
        $("#num_in").text(100-$("#comment_input").val().length)
        $('#comment_input').bind('focus keyup input paste',function(){
        var curLength=$(this).val().length;
        if(curLength>100){
            layer.msg("对不起，就这么多了");
            var textin=$("#comment_input").text().substr(0,100);
            $("#comment_input").text(textin);
        }else{
            $('#num_in').text(100-$(this).val().length)
        }
        });

    }else{
        $('#score_comment').remove();
        $('#comment_input1').html("已经评价：</br>"+request.comment+"");
        $('#comment_input1').after("<a href='/course.info.show/"+request.course+"' target='_blank' class='c-fr cBlue'>查看更多</a>");
//        $('#avg1').html(avgScore);
        $('#avg_score1').attr('data-score',request.score);
//        $('#desc_score1').html('data-score',descScore);
//        $('#explain_score1').html('data-score',explainScore);
            $('#score_percent1 dl:first span').each(function(){
                if ($(this).attr('data-score')==request.score){
                    $(this).prevAll().css("background-position", " -4px -33px");
                    $(this).nextAll().css("background-position", " -4px -3px");
                    $(this).siblings('i').html($(this).attr('data-title'));
                }
//                if (studentScore==0){
//                    $('#score_percent1 dl:first span').css("background-position", " -4px -3px");
//                    $(this).siblings('i').html('无');
//                }
            })
//        if(descScore != null){
//            $('#score_percent1 dl:eq(1) span').each(function(){
//                if ($(this).attr('data-score')==descScore){
//                    $(this).prevAll().css("background-position", " -4px -33px");
//                    $(this).nextAll().css("background-position", " -4px -3px");
//                    $(this).siblings('i').html($(this).attr('data-title'));
//                }
//                if (descScore==0){
//                    $('#score_percent1 dl:eq(1) span').css("background-position", " -4px -3px");
//                    $(this).siblings('i').html('无');
//                }
//            })
//        };
//        if(explainScore != null){
//            $('#score_percent1 dl:last span').each(function(){
//                if ($(this).attr('data-score')==explainScore){
//                    $(this).prevAll().css("background-position", " -4px -33px");
//                    $(this).nextAll().css("background-position", " -4px -3px");
//                    $(this).siblings('i').html($(this).attr('data-title'));
//                }
//                if (explainScore==0){
//                    $('#score_percent1 dl:last span').css("background-position", " -4px -3px");
//                    $(this).siblings('i').html('无');
//                }
//            })
//        }
    }




    function setAvgScore(extra){
        var t = extra;
        _score.find(".percent>dl>dd>i").each(function(i, elem){
            t+=parseInt($(this).attr("data-score"));
        });
        t /= 3;
        $("#avg").text(t.toFixed(1));
    }
    function getScore(kind){
        return _score.find("[score_type="+kind+"]").attr("data-score");
    }
    function addComment(e){
        e.preventDefault();
        var t = _input.val().replace(/(^\s*)|(\s*$)/g, "");
//        t=t.replace(/<\/?[^>]*>/g,'');
        if(!t){
            layer.msg("评论不能为空！");
            return false;
        }
        if(t.length<5){
            layer.msg("评论不能少于4个字哦");
            return;
        }
        var request = {};
        request["score"] = getScore("avg_score");
//        request["desc_score"] = getScore("desc_score");
//        request["explain_score"] = getScore("explain_score");
        request["user_teacher"] = <?php echo SlightPHP\Tpl::$_tpl_vars["teacherId"]; ?>;
        request["plan_id"] = <?php echo SlightPHP\Tpl::$_tpl_vars["planId"]; ?>;
        request["user_owner"] = <?php echo SlightPHP\Tpl::$_tpl_vars["userOwner"]; ?>;
        var avg = $("#avg").text();
    //  request["service_score"] = getScore("service_score");console.log("post url=["+_addURL+"]");
        for(i in request){
            if(0 == request[i]){
                layer.msg(_score.find("[score_type="+i+"]").prev().text()+"还没有打分呢！");
                return;
            }
        }
        request["comment"] = t;
        request["course_id"] = <?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>;

        $("#comment_send").val("评论中...");
        var commentSuc = function(r){
            setComment(request["student_score"],request["desc_score"],request["explain_score"],avg,request["comment"]);

            res = JSON.parse(r);
            if (res.code == 1) {
                var sign_add = "<span id='sign_add' class='sign-add'></span>";
                $("#tool").append(sign_add);
                $("#sign_add").html('<img src="<?php echo utility_cdn::img('/assets_v2/img/exp-icon.png'); ?>">+' + res.result.addScore);
                $("#sign_add").show();
                /*
                $("#sign_add").show(function () {
                    $("#sign_add").animate({top: "30%",  opactiy: "1"}, function () {
                        $("#sign_add").hide().animate({top: "35%"});
                    });
                });
                */

                if (res.result.upType) {
                    var types = '';
                    if (res.result.upType == 2) {
                        types = 'biggrowth';
                    }

                    if (res.result.upType == 1) {
                        types = 'smallgrowth';
                    }

                    $("body").GrowthLayer({
                        types: types,// five|smallgrowth|biggrowth
                        space: 5000, //时间间隔
                        auto: true, //自动关闭
                        growth: res.result.userLevel,// 等级,1,2,3
                        score: res.result.currentScore
                    });
                }
            }
            setTimeout(function() {
                layer.msg("感谢评价");
            }, 500)
            //  location.reload();
            return false;
        };

        $.post(_addURL, request, commentSuc);
        setTimeout(function(){
        parent.layer.close(xindex);
        },1000)
    }


    $("#comment_send").click(addComment);
    _input.keypress(function(e){
        if((10 == e.keycode || 13 == e.keycode) && e.ctrlkey){
            addComment();
        }
    });
    $("#comment_cancel").click(function(){
        setTimeout(function(){
        parent.layer.close(xindex);
        },1000)
    });

//星级评价
    $("#score_percent>dl>dd>span").click(function() {
        $(this).css("background-position", "-4px -33px");
        $(this).prevAll().css("background-position", " -4px -33px");
        $(this).nextAll().css("background-position", " -4px -3px");
        $(this).parent().find('i').html($(this).attr('data-title'));
        $(this).parent().find('i').attr("data-score",$(this).attr('data-score'));
        setAvgScore(0);
    });
    $("#score_percent>dl>dd>span").hover(function() {
        $(this).css("background-position", "-4px -33px");
        $(this).prevAll().css("background-position", " -4px -33px");
        $(this).nextAll().css("background-position", " -4px -3px");
        $(this).parent().find('i').html($(this).attr('data-title'));
        setAvgScore(0);
    },function(){
        var data_score=$(this).parent().find('i').attr("data-score");
        $(this).parent().find("span").each(function(){
            if($(this).attr("data-score") == data_score){
                $(this).prevAll().css("background-position", " -4px -33px");
                $(this).css("background-position", " -4px -33px");
                $(this).nextAll().css("background-position", " -4px -3px");
                $(this).parent().find('i').html($(this).attr('data-title'));
            }
        });
    });

});

    function setComment(score,avg,comment){
        var score_comment = $("#score_comment");
        var text_comment = $("#textComment");
        var student1 = $("#avg_score").find("[data-score="+score+"]");
//        var desc1 = $("#desc_score").find("[data-score="+dscore+"]");
//        var explain1 = $("#explain_score").find("[data-score="+escore+"]");
        student1.css("background-position","-4px -33px");
        student1.prevAll().css("background-position","-4px -33px");
        student1.nextAll().css("background-position","-4px -3px");
//        desc1.css("background-position","-4px -33px");
//        desc1.prevAll().css("background-position","-4px -33px");
//        desc1.nextAll().css("background-position","-4px -3px");
//        explain1.css("background-position","-4px -33px");
//        explain1.prevAll().css("background-position","-4px -33px");
//        explain1.nextAll().css("background-position","-4px -3px");
//        $("#avg").text(avg);
    }
</script>
