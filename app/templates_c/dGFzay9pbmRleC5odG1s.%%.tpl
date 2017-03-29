<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>ImageEditer</title>
	<meta name="description" content="" />

	<script src="<?php echo utility_cdn::js('/assets_v2/js/swfobject.js'); ?>"></script>
	<script>
		var flashvars = {
		};
		var params = {
			menu: "false",
			scale: "noScale",
			allowFullscreen: "true",
			allowScriptAccess: "always",
			bgcolor: "",
			wmode: "direct" // can cause issues with FP settings & webcam
		};
		var attributes = {
			id:"ImageEditer"
		};
		
		swfobject.embedSWF(
			"<?php echo utility_cdn::swf('/assets/swf/ImageEditer.swf'); ?>",
			"altContent", "100%", "100%", "10.0.0", 
			"<?php echo utility_cdn::swf('/assets/swf/expressInstall.swf'); ?>",
			flashvars, params, attributes);
	</script>
	<style>
		html, body { height:100%; overflow:hidden; }
		body { margin:0; }
	</style>
</head>
<body>
	<div id="altContent">
		<h1>ImageEditer</h1>
		<p><a href="http://www.adobe.com/go/getflashplayer">Get Adobe Flash player</a></p>
	</div>
</body>


</html>