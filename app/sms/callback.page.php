<?php
class sms_callback extends STpl
{
	public function yunpian($inPath)
	{
		if (!empty($_POST)) {
			return sms_api::yunpianCallback($_POST);
		}
		return false;
	}
}
?>