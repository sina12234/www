<?php
date_default_timezone_set("Asia/Shanghai");
define("ROOT",				dirname(__FILE__)."/../");
define("ROOT_WWW",			ROOT."/www");
define("ROOT_APP",			ROOT."/app");
define("ROOT_CONFIG",		ROOT."/config");
define("ROOT_LIBS",			ROOT."/libs");
define("ROOT_SLIGHTPHP",	ROOT."/framework");
define("ROOT_LOCALE",		ROOT."/locale");
require_once(ROOT_SLIGHTPHP."/vendor/autoload.php");
//{{{
spl_autoload_register(function($class){
	$file = SlightPHP::$appDir."/".str_replace("_","/",$class).".class.php";
	if(file_exists($file)) return require_once($file);
});
//}}}

//{{{ WEB-2990
SLanguage::setLanguageDir(ROOT_LOCALE);
SLanguage::$defaultLocale="zh";
if(!empty($_COOKIE['language'])){
	SLanguage::setLocale($_COOKIE['language']);
}
//}}}
if(is_file("local.php")){
	require_once("local.php");
}
