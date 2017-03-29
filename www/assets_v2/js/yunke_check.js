(function(){
   var ykPlugin;
   function ykPluginInit(){
	   var ua = window.navigator.userAgent;
	   if(ua.indexOf('Chrome/') !== -1 && parseInt(ua.substr(ua.indexOf("Chrome/")+7 , 2)) >= 43)
		   return;

	   if( ykPlugin ) return; //create only once.
		   var div = document.createElement("yunke");
		   div.innerHTML = "<object id=\"ykplugin0\" type=\"application/x-ykagent\" width=\"0\" height=\"0\"></object>";
		   document.body.appendChild(div);
		   ykPlugin = document.getElementById('ykplugin0');
   }

   function ykPluginExec(str){
	   ykPluginInit();
	   try{
		   return ykPlugin.exec("start","desktop",str);
	   }catch(ex){
		   return false;
	   }
   }

   function httpGet(theUrl){
	   var xmlHttp;
	   try{
		   var xmlHttp;
		   // Firefox, Opera 8.0+, Safari
		   if(window.XDomainRequest )
			   xmlHttp = new XDomainRequest();
		   else
			   xmlHttp = new XMLHttpRequest();

		   xmlHttp.open( "GET", theUrl, false );
	   }catch (e){
		   // Internet Explorer
		   try{
			   xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
			   xmlHttp.open( "GET", theUrl, false );
		   }catch (e){
			   try{
				   xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
				   xmlHttp.open( "GET", theUrl, false );
			   }catch(e){
				   return "";
			   }
		   }
	   }
	   try{
		   xmlHttp.send( null );
		   return xmlHttp.responseText;
	   }catch(ex){
		   return "";
	   }
   }

   var errornum = 0;
   var errortime = 0;
   function jsonp(config , from) {
	   var nowTime = (new Date()).valueOf();
	   if(errornum >= 3 && errortime != 0 && (nowTime - errortime) <= 600000 && from == 'player'){
		   return false;
	   }
	   if(!config || !config.url){
		   return false;
	   }
	   var timeout = config.time || 3000;
	   var url = config.url;
	   var jQueryAjax = function(){
		   jQuery.ajax({
			   type : "get",
			   url : url,
			   dataType : "jsonp",
			   data:{},
			   processData:true,
			   timeout: timeout,
			   success : function(json){
				   var d = eval("("+json+")");
				   if(d && d.iku){
					   if(typeof(config.success) == 'function'){
						   config.success(d.iku);
					   }
				   }else{
					   if(typeof(config.fail) == 'function'){
						   config.fail();
					   }
				   }
			   },
			   error:function(XMLHttpRequest, textStatus, errorThrown){
				   if(typeof(config.fail) == 'function'){
					   config.fail();
				   }
				   errornum += 1;
				   errortime = (new Date()).valueOf();
				   return false;
			   }
		   });
	   }
	   jQueryAjax();
   }
   function on_error(scene){
	   ret_str = "http://www.yunke.com";
	   if (scene){
		   ret_str += scene;
	   }
	   return ret_str;
   }

   function ykExecuteForIE(str_cmd,scene,on_suc,on_fail){
	   var timeout_test = 10000;
	   jsonp({
	   url: 'http://127.0.0.1:58891/teacher_client/?command=check',
	   success: function(d){
		   //数据处理
		jsonp({
			   url:"http://127.0.0.1:58891/teacher_client/?command=launch",
			   success: function(d){
				 //数据处理
				 on_suc(d);
			   },
			   time: timeout_test,
			   fail: function(){
				 //错误处理
				 on_fail(on_error(scene));
			   }
			 });
	   },
	   time: timeout_test,
	   fail: function(){
		 //错误处理
		 jsonp({
			   url: 'http://127.0.0.1:58891/teacher_client/?command=check',
			   success: function(d){
				 //数据处理
				 jsonp({
					   url:"http://127.0.0.1:58891/teacher_client/?command=launch",
					   success: function(d){
						 //数据处理
						  on_suc(d);
					   },
					   time: timeout_test,
					   fail: function(){
						 //错误处理
						 on_fail(on_error(scene));
					   }
					 });
			   },
			   time: timeout_test,
			   fail: function(){
				 //错误处理
				 on_fail(on_error(scene));
			   }
			 });
	   }
	 },'page');
   }

   //scene 用于汇报日志和区分渠道下载
   function yunkeExecute( b, scene, on_suc, on_fail){
	   b += scene;
	   b += "|";

	   var ret_str = "ok";
	   var ua = window.navigator.userAgent;
	   if( !(ua.indexOf('Chrome/') !== -1 && parseInt(ua.substr(ua.indexOf("Chrome/")+7 , 2)) >= 43) ){
			   var step_flag = ykPluginExec(b);
			   if(step_flag && step_flag != "false"){
				   return ret_str;
			   }
	   }

	   var num = Date.parse(new Date());


	   var yk_ret = httpGet("http://127.0.0.1:58891/teacher_client/?command=check");
	   if(yk_ret == "iku"){
		   var command_line = "http://127.0.0.1:58891/teacher_client/?command=launch";
		   ret_str = httpGet(command_line);
	   }else{
		   yk_ret = httpGet("http://127.0.0.1:58891/teacher_client/?command=check");
		   if(yk_ret == "iku"){
			   var command_line = "http://127.0.0.1:58891/teacher_client/?command=launch";
			   ret_str = httpGet(command_line);
		   }else{
			   ret_str = "http://www.yunke.com";
			   if (scene){
				   ret_str += scene;
			   }else{
				   ret_str = "";
			   }
		   }
	   }
	   return ret_str;
   }
   window.yunkeExecute = yunkeExecute; // 对外接口
})();
