<?php

class interface_version extends interface_base
{
     /*
      * 版本升级
      * @author zhengtianlong
      */
    public function pagePc()
    {
        $config = SConfig::getConfig(ROOT_CONFIG."/version.conf",'default');
        $header = utility_net::isHTTPS() ? "https" : "http";
        $host = $header.'://'.$_SERVER['HTTP_HOST'];
		$download = explode(',',$config->windows->download);
		$download[0] = $host.$download[0];
		$data['windows'] = [
			'version'     => explode(',',$config->windows->version),
			'description' => $config->windows->description,
			'min_version' => explode(',',$config->windows->min_version),
			'download'    => $download
		];
		
		foreach($config->windows->dependency as $k=>$v)
		{
			$data['windows']['dependency'][$k] = $v;
		}
		
        return $this->setData($data);
    }
	
	public function pageAndroid()
	{
		$this->v(['version'=>1000]);
		$version = $this->paramsInfo['params']['version'];
		$data=self::getVersionInfo("android",$version);
		return $this->setData($data);
	}
	public function pageXiaoWo()
	{
		$this->v(['version'=>1000]);
		$version = $this->paramsInfo['params']['version'];
		$dowType = !empty($this->paramsInfo['dinfo']['ch']) ? $this->paramsInfo['dinfo']['ch'] : 'xiaowo';
		$data=self::getVersionInfo("xiaowo",$version,$dowType);
		return $this->setData($data);
	}
	public function getVersionInfo($versionType,$version,$dowType='xiaowo'){
		if(empty($versionType) || empty($version)){
			return array();
		}
		$config = SConfig::getConfig(ROOT_CONFIG."/version.conf",'default');
		$header = utility_net::isHTTPS() ? "https" : "http";
        $host = $header.'://'.$_SERVER['HTTP_HOST'];
		
		foreach($config->$versionType->download as $k=>$v){
			$data['windows']['dependency'][$k] = $v;
		}
		
		$data = [
			'force'       => ($version<$config->$versionType->minVersion)?1:0,
			'versionCode' => $config->$versionType->versionCode,
			'versionName' => $config->$versionType->versionName,
			'description' => $config->$versionType->description
		];
		if($dowType=='threeparty' && !empty($config->$versionType->three_download->release)){
			$data['download']['release'] = $config->$versionType->three_download->release;
		}else{
			$data['download']['release'] = $host.$config->$versionType->download->release;
			$data['download']['debug']   = !empty($config->$versionType->download->debug) ? $config->$versionType->download->debug : '';
		}
		return $data;
	}
}
