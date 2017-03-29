<?php
class utility_test{
	public function pageEntry($inPath){
		   $a = utility_file::instance();
		   $r = $a->upload("/tmp/xx.log");
		   if($r!==false){
		   print_r($r);
		   }else{
		   var_dump($r);
		   }
		   print_r($a);
		   print_r($inPath);
	}
	public function pageip($inPath){
		//$r = utility_ip::info("61.139.2.69");
		$r = utility_ip::info("1032520261");
		print_r($r);
	}
}
