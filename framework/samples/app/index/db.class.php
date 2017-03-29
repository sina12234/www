<?php
/**
 * db samples code
 **/
class index_db{
	private $_dbConfig;
	private $_zone;
	function __construct($zone="index"){
		$this->_zone = $zone;
		$this->_db = new SDb;
		$this->_db->useConfig("index","main");
		$this->_db->init($this->_dbConfig);
	}
	
	/**
	 * ���ӻ��ּ�¼
	 */
	function addScore($username,$score){
		return $this->_db->insert("p_score",array("username"=>$username,"score"=>$score));
	}
}
?>
