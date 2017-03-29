<?php
class activity_api{
/*
	之前的
 */
	public static function getScore($name,$numb,$idcd){
		$params=new stdclass;
		$params->name=$name;
		$params->numb=$numb;
		$params->idcd=$idcd;
		$ret = utility_services::call("/activity/2015/getscore",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
/*
	迎春杯
 */
	public static function getYCBScore($name){
		$params=new stdclass;
		$params->name=$name;
		$ret = utility_services::call("/activity/2015/getycbscore",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function findRow($name){
		$file = "/data/www/www/upload/tmp/score.jingying.csv";
		if(!is_file($file))return false;
		$data = file($file);
		foreach($data as $line){
			$tmp = explode(",",trim($line));
			if($tmp[0] == $name){return $tmp;}
		}
		return false;
	}
	public static function findTwRow($name,$mobi){
		$file = "/data/www/www/upload/tmp/score.tw.csv";
		if(!is_file($file))return false;
		$data = file($file);
		foreach($data as $line){
			$tmp = explode(",",trim($line));
			if($tmp[0] == $name && $tmp[1] == $mobi){return $tmp;}
		}
		return false;
	}
	/**成绩查询通用版本*/
	public static function queryScore($filename,$queryRow=array()){
		$file = "/data/www/www/upload/tmp/$filename";
		if(!is_file($file))return false;
		$data = file($file);
		foreach($data as $line){
			$tmp = explode(",",trim($line));
			$finded = false;
			foreach($queryRow as $index => $value){
				if(!isset($tmp[$index]) || $tmp[$index] != $value){$finded = false; break;}
				$finded = true;
			}
			if($finded === false){
				continue;
			}
			return $tmp;
		}
		return false;
	}
	public static function NoticeCommentList($params){
		$result = utility_services::call("/message/notice/NoticeCommentList",$params);
		if(!empty($result->result)){
			return $result->result;
		}
		return false;
	}
	public static function AddNoticeComment($params){
		$result = utility_services::call("/message/notice/AddNoticeComment",$params);
		if(!empty($result->result)){
			return $result->result;
		}
		return false;
	}
	public static function deleteNoticeComment($params){
		$result = utility_services::call("/message/notice/DeleteNoticeComment",$params);
		if(!empty($result->result)){
			return $result->result;
		}
		return false;
	}
	public static function getUsersListInfo($params){
		$result = utility_services::call("/user/organization/GetUsersListInfo",$params);
		if(!empty($result->result)){
			return $result->result;
		}
		return false;
	}
	public static function getNoticeCommentOneInfoById($params){
		$result = utility_services::call("/message/notice/GetNoticeCommentOneInfoById",$params);
		if(!empty($result->result)){
			return $result->result;
		}
		return false;
	}
}
