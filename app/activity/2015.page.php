<?php
class activity_2015 extends STpl{
	function __construct(){
	}
/**
	华杯赛
 */
	function pageEntry($inPath){
        if(user_api::logined()==true){
			$user = user_api::loginedUser();
			$name = !empty($_REQUEST['name']) ? $_REQUEST['name'] : $user['name'];
			$numb = !empty($_REQUEST['numb']) ? $_REQUEST['numb'] : "";
			$idcd = !empty($_REQUEST['idcd']) ? $_REQUEST['idcd'] : "";
			$this->assign("name", $name);
			$this->assign("numb", $numb);
			$this->assign("idcd", $idcd);
			$ret  =activity_api::getScore($name,$numb,$idcd);
			$error="";
			if(!empty($_REQUEST) && empty($ret)){
				$error="您输入身份证或手机号查不到对应的数据";
			}else{
				if(!empty($ret)){
					$this->assign("ret", $ret);
					return $this->render("activity/2015.result.html");
				}
			}
			$this->assign("error", $error);
			return $this->render("activity/2015.html");
		}else{
			header("Location:https://hnhbs0731.yunke.com/site.main.login?url=https%3A%2F%2Fwww.yunke.com%2Factivity.2015");
		}
	}
/**
	迎春杯 
 */
	function pageYCB($inPath){
        if(user_api::logined()==true){
			$user = user_api::loginedUser();
			$name = !empty($_REQUEST['name']) ? $_REQUEST['name'] : $user['name'];
			$this->assign("name", $name);
			$ret  =activity_api::getYCBScore($name);
			$error="";
			if(!empty($_REQUEST) && empty($ret)){
				$error="您输入姓名查不到对应的数据";
			}else{
				if(!empty($ret)){
					$this->assign("ret", $ret);
					return $this->render("activity/2015.ycb.result.html");
				}
			}
			$this->assign("error", $error);
			return $this->render("activity/2015.ycb.html");
		}else{
			header("Location:https://ycb2016.yunke.com/site.main.login?url=https%3A%2F%2Fwww.yunke.com%2Factivity.2015.ycb");
		}
	}
	/** 
		华杯精英选拔赛成绩查询 
		**/
	function pageTWJingying($inPath){
		$title="华杯精英选拔赛成绩查询";
		$img="";
		$this->assign("title",$title);
		$this->assign("img",$img);
        if(user_api::logined()==true){
			$user = user_api::loginedUser();
			$name = !empty($_REQUEST['name']) ? $_REQUEST['name'] : $user['name'];
			$this->assign("name", $name);
			$error="";
			$ret= activity_api::findRow($name);
			if(!empty($_REQUEST) && empty($ret)){
				$error="您输入的姓名查不到对应的成绩";
			}else{
				if(!empty($ret)){
					$this->assign("ret", $ret);
					return $this->render("activity/2015.jingying.result.html");
				}
			}
			$this->assign("error", $error);
			return $this->render("activity/2015.jingying.html");
		}else{
			header("Location:https://xue.yunke.com/site.main.login?url=https%3A%2F%2Fwww.yunke.com%2Factivity.2015.twjingying");
		}
	}
/**
	拓维杯复赛
 */
	function pageTW($inPath){
		$title="拓维杯复赛成绩查询";
		$img="";
		$this->assign("title",$title);
		$this->assign("img",$img);
        if(user_api::logined()==true){
		//if(true){
			$user = user_api::loginedUser();
			$name = !empty($_REQUEST['name']) ? $_REQUEST['name'] : $user['name'];
			$mobi = !empty($_REQUEST['mobi']) ? $_REQUEST['mobi'] : "";
			$this->assign("name", $name);
			$this->assign("mobi", $mobi);
			$ret= activity_api::findTwRow($name,$mobi);
			$error="";
			if(!empty($_REQUEST) && empty($ret)){
				$error="您输入姓名和手机号查不到对应的数据";
			}else{
				if(!empty($ret)){
					$this->assign("ret", $ret);
					return $this->render("activity/2015.tw.result.html");
				}
			}
			$this->assign("error", $error);
			return $this->render("activity/2015.tw.html");
		}else{
			header("Location:https://xue.yunke.com/site.main.login?url=https%3A%2F%2Fxue.yunke.com%2Factivity.2015.tw");
		}
	}
	function pageQuery1206($inPath){
		$title="拓维12月6日考试成绩查询";
		$tips ='如有疑问请联系客服<span class="red">0731--85137468</span>';
		$img="";
		$this->assign("title",$title);
		$this->assign("tips",$tips);
		$this->assign("img",$img);
		$entry = str_replace("page","",__FUNCTION__);
        if(user_api::logined()==true){
			$user = user_api::loginedUser();
			$name = !empty($_REQUEST['name']) ? $_REQUEST['name'] : $user['name'];
			$mobi = !empty($_REQUEST['mobi']) ? $_REQUEST['mobi'] : "";
			$this->assign("name", $name);
			$this->assign("mobi", $mobi);
			$result= activity_api::queryScore("score.1206.csv",array(1=>$name,3=>$mobi));
			$error="";
			if(!empty($name) && !empty($mobi)){
				if(empty($result)){
					$queryRow = array(
						array("name"=>"name","value"=>$name,"display"=>"姓名"),
						array("name"=>"mobi","value"=>$mobi,"display"=>"手机号"),
					);
					$this->assign("queryRow",$queryRow);
					$error="您输入姓名和手机号查不到对应的数据";
					$this->assign("error", $error);
					return $this->render("activity/score.html");
				}else{
					if(!empty($result)){
						$ret = array("姓名"=>$result[1],"语文"=>$result[4],"数学"=>$result[5],"总分"=>$result[6]);
						$this->assign("ret", $ret);
						return $this->render("activity/score.result.html");
					}
				}
			}else{
				$queryRow = array(
					array("name"=>"name","value"=>$name,"display"=>"姓名"),
					array("name"=>"mobi","value"=>"","display"=>"手机号"),
				);
				$this->assign("queryRow",$queryRow);
				return $this->render("activity/score.html");
			}
		}else{
			header("Location:https://xue.yunke.com/site.main.login?url=https%3A%2F%2Fxue.yunke.com%2Factivity.2015.$entry");
		}
	}
	function pageQuery1226($inPath){
		$entry = str_replace("page","",__FUNCTION__);
		$title="拓维结业考试成绩查询";
		$tips ='如有疑问请联系客服<span class="red">0731-84863382</span>';
		$img="/assets_v2/img/activity/activity.score.1226.jpg";
		$this->assign("title",$title);
		$this->assign("tips",$tips);
		$this->assign("img",$img);
        if(user_api::logined()==true){
			$user = user_api::loginedUser();
			$name = !empty($_REQUEST['name']) ? $_REQUEST['name'] : $user['name'];
			$numb = !empty($_REQUEST['numb']) ? $_REQUEST['numb'] : "";
			$result= activity_api::queryScore("score.1226.csv",array(2=>$name,1=>$numb));
			$error="";
			if(!empty($name) && !empty($numb)){
				if(empty($result)){
					$queryRow = array(
						array("name"=>"name","value"=>$name,"display"=>"姓名"),
						array("name"=>"numb","value"=>$numb,"display"=>"考号"),
					);
					$this->assign("queryRow",$queryRow);
					$error="您输入姓名和考号查不到对应的数据";
					$this->assign("error", $error);
					return $this->render("activity/score.html");
				}else{
					if(!empty($result)){
						$ret = array("姓名"=>$result[2],"语文"=>$result[10],"数学"=>$result[11],"总分"=>$result[12]);
						$this->assign("ret", $ret);
						return $this->render("activity/score.result.html");
					}
				}
			}else{
				$queryRow = array(
					array("name"=>"name","value"=>$name,"display"=>"姓名"),
					array("name"=>"numb","value"=>"","display"=>"考号"),
				);
				$this->assign("queryRow",$queryRow);
				return $this->render("activity/score.html");
			}
		}else{
			header("Location:https://xue.yunke.com/site.main.login?url=https%3A%2F%2Fxue.yunke.com%2Factivity.2015.$entry");
		}
	}
}
