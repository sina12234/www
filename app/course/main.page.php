<?php
class course_main extends STpl{
	function __construct(){
	}
	function pageEntry($inPath){
		return $this->render("teacher/teacher.html");
	}
	public function page404(){
		$this->render("site/Course.delete.html");
	}

}
