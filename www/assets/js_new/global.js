if(!window.console){
	window.console = {};
	if(!window.console.log){
		window.console.log = function(){
		};
	}
}
$(document).ready(function(){
	$("#href_login,#href_reg,#href_logout").click(function(){
		if(document.location.pathname!="/" && document.location.pathname.indexOf("/user/main")!==0){
			href = $(this).attr("href")+"?url="+document.location.pathname;
		}else{
			href = $(this).attr("href");
		}
		$(this).attr("href",href);
		return true;
	});
	$("#parterner .parterner").click(function(){
		if(window.open($(this).attr("href"),"_newtab","height=600,width=800")){
			return false;
		};
		return true;
	});
	/*
	$('a').on('touchend', function() {
		var linkToAffect = $(this);
		var linkToAffectHref = linkToAffect.attr('href');
		if(linkToAffect && linkToAffect!="" && linkToAffect!="#") window.location = linkToAffectHref;
	});
	*/
});
