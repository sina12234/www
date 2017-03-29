$(document).ready(function(){
	$("#parterner .parterner").click(function(){
		if(window.open($(this).attr("href"),"_newtab","height=600,width=800")){
			return false;
		};
		return true;
	});
});
