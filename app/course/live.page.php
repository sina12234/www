<?php
class course_live extends STpl{
	function __construct(){
	}
	public function pageEntry($inPath){
		return $this->render("course/live.html");
	}
	/**
	 *  详情页
	 */
	public function pageInfo($inPath){
		return $this->render("course/live.info.html");
	}
	/**
	 *  直播页
	 */
	public function pagePlay($inPath){
		return $this->render("course/live.play.html");
	}
}
