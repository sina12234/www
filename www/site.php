<?php
/**
 * sample to test
 *
 * http://localhost/samples/www/index.php/zone/default/entry/a/b/c
 * http://localhost/samples/www/index.php/zone-default-entry-a-b-c.html
 *
 */
require_once("global.php");

SlightPHP::setDebug(true);
SlightPHP::setAppDir(ROOT_APP);
SlightPHP::setDefaultZone("site");
SlightPHP::setDefaultPage("main");
SlightPHP::setDefaultEntry("entry");
SlightPHP::setSplitFlag("-_.");
SRoute::setConfigFile(ROOT_CONFIG."/route.conf");
if(($r=SlightPHP::run())===false){
	header('Location: /site.main.404');
}elseif(is_object($r) || is_array($r)){
	echo SJson::encode($r);
}else{
	echo($r);
}
