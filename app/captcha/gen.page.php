<?php
class captcha_gen{
	public function pageEntry($inPath){
		$captcha = new SCaptcha;
		$captcha->CreateImage();
	}
}
