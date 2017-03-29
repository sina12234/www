<?php
class weixin_api{
	static $config;
	public static function getConfig(){
		if(!empty(self::$config)){
			return  self::$config;
		}
		$ret =utility_services::call("/weixin/auth/get");
		if(!empty($ret->data)){
			self::$config=$ret->data;
		}
		return self::$config;
	}
	public static function is_weixin(){
		if(empty($_SERVER['HTTP_USER_AGENT'])){
			return false;
		}
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
			return true;
		}
		return false;
	}
	public static function getJsOptions(){
		$conf = self::getConfig();
		$options =array();
		if(empty($conf)){
			return $options;
		}
		$url = (utility_net::isHTTPS()?"https":"http")."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$timestamp = time();
		$nonceStr = self::createNonceStr();

		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=".$conf->jsapi_ticket."&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

		$signature = sha1($string);

		$signPackage = array(
			"appId"     => $conf->appid,
			"nonceStr"  => $nonceStr,
			"timestamp" => $timestamp,
			"url"       => $url,
			"signature" => $signature,
			"rawString" => $string
		);
		return $signPackage; 
	}

	public static function getOptions(){
		$conf = self::getConfig();
		$options =array();
		if(empty($conf)){
			return $options;
		}
		$options['token']=$conf->token;
		$options['encodingaeskey']=$conf->encodingaeskey;
		$options['appid']=$conf->appid;
		$options['appsecret']=$conf->appsecret;
		$options['partnerid']=$conf->partnerid;
		$options['partnerkey']=$conf->partnerkey;
		$options['access_token']=$conf->access_token;
		$options['paysignkey']=$conf->paysignkey;
		return $options;
	}

	//{{{微信二维码支付
	public static function getQrcodeUrl($product_id){		
			$parameter=array("product_id"=>$product_id);
		   	$time_stamp = time();
			$config = self::getConfig();
		   	$parameter["appid"] = $config->appid;// WxPayConf_pub::APPID;//公众账号ID
		   	$parameter["mch_id"] = $config->partnerid;//WxPayConf_pub::MCHID;//商户号
		   	$parameter["time_stamp"] = "$time_stamp";//时间戳
		    $parameter["nonce_str"] = self::createNoncestr();//随机字符串
		    $parameter["sign"] = self::getSign($parameter,$config->paysignkey);//签名    		
			$bizString = self::formatBizQueryParaMap($parameter, false);
		    return "weixin://wxpay/bizpayurl?".$bizString;
		return self::_createLink(array("product_id"=>$product_id));
	}
	//}}}


	private static function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
	static function trimString($value)
	{
		$ret = null;
		if (null != $value) 
		{
			$ret = $value;
			if (strlen($ret) == 0) 
			{
				$ret = null;
			}
		}
		return $ret;
	}
	/**
	 * 	作用：格式化参数，签名过程需要使用
	 */
	static function formatBizQueryParaMap($paraMap, $urlencode)
	{
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
		    if($urlencode)
		    {
			   $v = urlencode($v);
			}
			//$buff .= strtolower($k) . "=" . $v . "&";
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar = '';
		if (strlen($buff) > 0) 
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	
	public static function checkSign($data){
		$tmpData = $data;
		unset($tmpData['sign']);
		$config = self::getConfig();
		if(!empty($data['appid'])){
			if($data['appid']=="wx6892c4434a905e28"){
				//微信App支付-云课的appid
				$sign = self::getSign($tmpData,"880d03b1f706290ae53912142d9dbc0a");//本地签名
			}elseif($data['appid']=="wx92d4da0a6b7c8b47"){
				//微信App支付-小沃的appid
				$sign = self::getSign($tmpData,"7dd0b412758935ce9798d6812cdd27c3");
			}else{
				//微信公众号支付的appid
				$sign = self::getSign($tmpData,$config->paysignkey);//本地签名
			}
			if ($data['sign'] == $sign) {
				return TRUE;
			}
		}
		return FALSE;
	}
	/**
	 * 	作用：生成签名
	 */
	public static function getSign($Obj,$key){
		foreach ($Obj as $k => $v){
			$Parameters[$k] = $v;
		}
		//签名步骤一：按字典序排序参数
		ksort($Parameters);
		$String = self::formatBizQueryParaMap($Parameters, false);
		//echo '【string1】'.$String.'</br>';
		//签名步骤二：在string后加入KEY
		$String = $String."&key=".$key;
		//echo "【string2】".$String."</br>";
		//签名步骤三：MD5加密
		$String = md5($String);
		//echo "【string3】 ".$String."</br>";
		//签名步骤四：所有字符转为大写
		$result_ = strtoupper($String);
		//echo "【result】 ".$result_."</br>";
		return $result_;
	}
	
	/**
	 * 	作用：array转xml
	 */
	public static function arrayToXml($arr){
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
        	 if (is_numeric($val))
        	 {
        	 	$xml.="<".$key.">".$val."</".$key.">"; 

        	 }
        	 else
        	 	$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
        }
        $xml.="</xml>";
        return $xml; 
    }
	
	/**
	 * 	作用：将xml转为array
	 */
	public static function xmlToArray($xml){		
        //将XML转为array        
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
		return $array_data;
	}
	public static function getJsParameters($order_info,$course_info){
		$config = self::getConfig();

		$parterner_uid="";
		if(!empty(utility_session::get()['parterner_uid'])){
			$parterner_uid=utility_session::get()['parterner_uid'];
		}
		$parter_info = user_api::getParterner(user_const::SOURCE_WEIXIN,$parterner_uid,user_api::getLoginUid());
		$openid="";
		if(!empty($parter_info->parterner_uinfo)){
			$tmp = SJson::decode($parter_info->parterner_uinfo);
			$openid = $tmp->openid;
		}
		$ret = array(
			"body"=>mb_strcut($course_info->title, 0, 120, "UTF-8"), //商品描述，最长128个字符
			"out_trade_no"=>$order_info->out_trade_id,
			"total_fee"=>$order_info->price*100,//变成分
			"notify_url"=>utility_services::url("weixin_pay", "course.buy.weixinReturn"),
			"trade_type"=>"JSAPI",
			"device_info"=>"WEB",
			"openid"=>"$openid",
			"spbill_create_ip"=>utility_ip::realIp(),
			"product_id"=>$order_info->out_trade_id,
		);
		$prepay_id = self::getPrepayId($ret,$error);
		$timeStamp = time();
		$returnParameters["appId"] = $config->appid;;//公众账号ID
		$returnParameters["timeStamp"] = "$timeStamp";
		$returnParameters["package"] = "prepay_id=$prepay_id";;
		$returnParameters["signType"] = "MD5";
		$returnParameters["nonceStr"] = self::createNoncestr();//随机字符串
		$returnParameters["paySign"] = self::getSign($returnParameters,$config->paysignkey);//签名
		$returnParameters["jsapi_ticket"] = $config->jsapi_ticket;
		return SJson::encode($returnParameters);
	}
	public static function createXml($returnParameters){
		$config = self::getConfig();
		$returnParameters["appid"] = $config->appid;;//公众账号ID
		$returnParameters["mch_id"] = $config->partnerid;//商户号
		$returnParameters["nonce_str"] = self::createNoncestr();//随机字符串
		$returnParameters["sign"] = self::getSign($returnParameters,$config->paysignkey);//签名
		return self::arrayToXml($returnParameters);
	}

	/**
	 * 	作用：以post方式提交xml到对应的接口url
	 */
	public static function postXmlCurl($xml,$url,$second=3){
        //初始化curl        
       	$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		//设置header
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//post提交方式
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		//运行curl
        $data = curl_exec($ch);
		curl_close($ch);
		//返回结果
		if($data){
			return $data;
		} else { 
			$error = curl_errno($ch);
			echo "curl出错，错误码:$error"."<br>"; 
			echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
			curl_close($ch);
			return false;
		}
	}

	/**
	 * 	作用：使用证书，以post方式提交xml到对应的接口url
	 */
    public static function postXmlSSLCurl($xml,$url,$second=30)
	{
		$ch = curl_init();
		//超时时间
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		//这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		//设置header
		curl_setopt($ch,CURLOPT_HEADER,FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
		//设置证书
		//使用证书：cert 与 key 分别属于两个.pem文件
		//默认格式为PEM，可以注释
		curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
		curl_setopt($ch,CURLOPT_SSLCERT, WxPayConf_pub::SSLCERT_PATH);
		//默认格式为PEM，可以注释
		curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
		curl_setopt($ch,CURLOPT_SSLKEY, WxPayConf_pub::SSLKEY_PATH);
		//post提交方式
		curl_setopt($ch,CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
		$data = curl_exec($ch);
		//返回结果
		if($data){
			curl_close($ch);
			return $data;
		}
		else { 
			$error = curl_errno($ch);
			echo "curl出错，错误码:$error"."<br>"; 
			echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
			curl_close($ch);
			return false;
		}
	}
	/**
	 * 	作用：post请求xml
	 */
    public static function postXml($url,$data){
	    $xml = self::createXml($data);
		return self::xmlToArray(self::postXmlCurl($xml,$url,3));
	}
	
	/**
	 * 	作用：使用证书post请求xml
	 */
    public static function postXmlSSL($url,$data){
	    $xml = self::createXml($data);
		return self::xmlToArray(self::postXmlSSLCurl($xml,$url,3));
	}

	/**
	 * 	作用：获取结果，默认不使用证书
	 */
    public static function getPrepayId($data,&$result=null){
		$result = self::postXml("https://api.mch.weixin.qq.com/pay/unifiedorder",$data);
		if(empty($result['prepay_id'])){
			self::my_log(__LINE__,var_export($data,true));
			self::my_log(__LINE__,var_export($result,true));
			return false;
		}
		$prepay_id = $result["prepay_id"];
		return $prepay_id;
	}
	public static function my_log($line,$msg=""){

		if(is_array($msg) || is_object($msg)){
			$msg = var_export($msg,true);
		}
		error_log('['.date("Y-m-d H:i:s")."] [".__FILE__."][{$line}] {$msg}\n", 3 ,"/tmp/course.buy.".date("Ymd").".log");
	}
	
	/**
	 * 	作用：打印数组
	 */
    public static function printErr($wording='',$err='')
	{
		print_r('<pre>');
		echo $wording."</br>";
		var_dump($err);
		print_r('</pre>');
	}
}