<?php
class activity_score extends STpl{
	/**
	 * 通用成绩查询
	 */
	function pageQuery($inPath){
		if(empty($inPath[3])){
			header("Location:/");
		}
		$id = $inPath[3];

		$title = !empty($_REQUEST['title']) ? strip_tags($_REQUEST['title']) : "成绩查询";
		$tips = !empty($_REQUEST['tips']) ? strip_tags($_REQUEST['tips']) : '';
		$img="";
		$this->assign("title",$title);
		$this->assign("tips",$tips);
		$this->assign("img",$img);
		$entry = str_replace("page","",__FUNCTION__);
		if(user_api::logined()==true){
			$user = user_api::loginedUser();
			$name = !empty($_REQUEST['name']) ? $_REQUEST['name'] : "";
			$mobi = !empty($_REQUEST['mobi']) ? $_REQUEST['mobi'] : "";
			$this->assign("name", $name);
			$this->assign("mobi", $mobi);
			$result= activity_api::queryScore("score.$id.csv",array(0=>$name));
			$error="";
			if(!empty($name)){
				if(empty($result)){
					$queryRow = array(
						array("name"=>"name","value"=>$name,"display"=>"姓名"),
						//array("name"=>"mobi","value"=>$mobi,"display"=>"手机号"),
					);
					$this->assign("queryRow",$queryRow);
					$error="您输入姓名查不到对应的数据";
					$this->assign("error", $error);
					return $this->render("activity/score.html");
				}else{
					if(!empty($result)){
						$ret = array("姓名"=>$result[0],"得分"=>$result[2]);
						$this->assign("ret", $ret);
						return $this->render("activity/score.result.html");
					}
				}
			}else{
				$queryRow = array(
					array("name"=>"name","value"=>$name,"display"=>"姓名"),
				);
				$this->assign("queryRow",$queryRow);
				return $this->render("activity/score.html");
			}
		}else{
			header("Location:https://xue.yunke.com/site.main.login?url=https%3A%2F%2Fxue.yunke.com%2Factivity.2015.$entry");
		}
	}
}
