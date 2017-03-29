/*
*高级机构PC端导航和适配
*Date:03-23-2017
*/
$(function() {
	if($(window).width() >= 768) {
	  	if($('#headernav').find('li').length > 5) {
	    	$('#headernav').find('li:eq(1)').show();
	    	$('#headernav').find('li:eq(2)').show();
	    	$('#headernav').find('li:eq(3)').show();
		    $('#headernav').find('li:eq(4)').hide();
		    $('#headernav').find('li:eq(5)').hide();
		    $('#headernav').find('li:eq(6)').hide();
		    $('#headernav').find('li:eq(7)').hide();
		    $('#headernav').find('li:eq(8)').hide();
		    $('#headernav').find('li').last().show();
		    var nav1 = $('#headernav').find('li:eq(4)').html();
		    var nav2 = $('#headernav').find('li:eq(5)').html();
		    var nav3 = $('#headernav').find('li:eq(6)').html();
		    var nav4 = $('#headernav').find('li:eq(7)').html();
		    var nav5 = $('#headernav').find('li:eq(8)').html();
		    if(nav1) {
		    	var ddHtml = '<dd>'+nav1+'</dd>';
		    }
		    if(nav2) {
		    	var ddHtml = '<dd>'+nav1+'</dd>'+'<dd>'+nav2+'</dd>';
		    }
		    if(nav3) {
		    	var ddHtml = '<dd>'+nav1+'</dd>'+'<dd>'+nav2+'</dd>'+'<dd>'+nav3+'</dd>';
		    }
		    if(nav4) {
		    	var ddHtml = '<dd>'+nav1+'</dd>'+'<dd>'+nav2+'</dd>'+'<dd>'+nav3+'</dd>'+'<dd>'+nav4+'</dd>';
		    }
		    if(nav5) {
		    	var ddHtml = '<dd>'+nav1+'</dd>'+'<dd>'+nav2+'</dd>'+'<dd>'+nav3+'</dd>'+'<dd>'+nav4+'</dd>'+'<dd>'+nav5+'</dd>';
		    }
		    $('.gn-senior-navmore').html(ddHtml);
		    $('.gn-senior-navmore a').each(function() {
		      	if($(this).text() == '更多') {
		        	$(this).remove();
		      	}
		    })
	  	}else{
	    	$('#headernav').find('li').show();
	    	$('#headernav').find('li').last().hide();
	  	} 

		$('.gn-senior-navmore-btn').hover(function() {
		  	$(this).find('.gn-senior-navmore').show();
		}, function() {
		  	$(this).find('.gn-senior-navmore').hide();
		})
	}else {
		$('#headernav').find('li').show();
		$('#headernav').find('li').last().hide();
	}
});