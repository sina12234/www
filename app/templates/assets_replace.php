<?php
die();
$filename = "./user/org.info.html";
$filename = './index/student.course.detail.html';
//replace($filename);
//exit;
$sh = exec("grep assets . -Ril |grep -v 'php'",$out);
print_r($out);
foreach($out as $filename){
replace($filename);
}
exit;
function replace($filename){
	$ft = file_get_contents($filename);
	//$ft_r = preg_match_all('/(\()([^"^\'^\(]*?assets.+?)(\))/i', $ft,$_m,PREG_SET_ORDER);
	//$ft = (preg_replace_callback('/(\()([^"^\'^\(]*?assets.+?)(\))/i',"replace_c",$ft));
	$ft = (preg_replace_callback('/(["])([^"]*?assets.+?)(\1)/i',"replace_c",$ft));
	file_put_contents($filename,$ft);
}
function replace_c($match){
		print_r($match);
		if(stripos($match[0],'utility_cdn')!==false){
			return $match[0];
		}else{
			//补上cdn
			$ct=0;
			$ret = preg_replace_callback('/(.*?)([^\'\{\}\("]+?assets.+?)([\'"\{\}\) ]+)(.*)/i', function($match_2){
				//print_r($match_2);
				//echo "\n";
				//删除最前面的.,或者补上/
				$len = strlen($match_2[2]);
				$i=0;
				for($i=0;$i<$len;$i++){
					if($match_2[2]{$i}==".")$i++;
					else break;

				}
				$str = substr($match_2[2],$i);
				$info = pathinfo($str);
				$ret="";
				if(empty($info['extension'])){
					//发现错误了
					global $filename;
					echo "\n".$filename."\n";
					print_r($match_2);
					print_r($str);
					return $match_2[0];
				}
				if($info['extension']=="js"){
					$ret="{utility_cdn::js('".$str."')}";
				}elseif($info['extension']=="css"){
					$ret="{utility_cdn::css('".$str."')}";
				}else{
					$ret="{utility_cdn::img('".$str."')}";
				}
				//echo $ret;
				//echo "\n";
				return $match_2[1].$ret.$match_2[3].$match_2[4];
			},$match[0],-1,$ct);
			//print_r($match);
			return $ret;
		}
		//print_r($match);
		//return $match[0];
		//$mt = preg_replace_callback('/([\'\{"])([^\'\{\}"]+?assets.+?)\1/i', function($match_2){
		//	echo "!";
		//	print_r($match_2);
		//	exit;
		//	print_r($match_2[0]);
		//},$match[2],-1,$ct);  
		//if($ct>0){
		//	return $mt;
		//}else{
		//	echo "!!";
		//	print_r($match);
		//	exit;
		//	return $match[0];
		//}
	}
exit;
//匹配assets
