<?php
class verify_sms{
	public function pageRecive($inPath){
		$mobile = isset($_REQUEST['m'])?$_REQUEST['m']:"";
		$content = isset($_REQUEST['c'])?$_REQUEST['c']:"";
		error_log("Mobile:$mobile\tContent:$content\n",3,"/tmp/mobile.recive.log");
		print_r($inPath);
	}
}