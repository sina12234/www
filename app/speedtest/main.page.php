<?php
class speedtest_main extends STpl{

	public function pageEntry($inPath){
		return $this->render("speedtest/main.html");
	}
}
