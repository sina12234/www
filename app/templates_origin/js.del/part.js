$(document).ready(function(){
	$(".part").each(function(i,item){
		$.ajax({url:$(this).attr("url"),context:this,success:function(data){
			$(this).html(data);
		}});
	});
});
