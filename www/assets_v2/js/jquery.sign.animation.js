/*
*Name:Changyuan.Liu
*Date:04-15 2016
*适用于点击签到动画
*创建Id:course_sign_add,引用函数并加参数赋值。
*/

function signAnimation(addScoreResult) {
	$('#course_sign_add').addClass('pos-abs');
    $('#course_sign_add').html('<img src="/assets_v2/img/exp-icon.png" alt="" />' + addScoreResult);
    $('#course_sign_add').show(function () {
        $('#course_sign_add').animate({
	        top: "25%",
	        left: "40%",
	        opactiy: "1"
	    }, function() {
            $('#course_sign_add').hide().animate({
            	top: "25%",
            	left: "40%"
            });
        });
    });

}
