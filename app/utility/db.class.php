<?php
/**
 *@author lijingjuan
 */
class school_db{

	public static function InitDB($dbname="db_utility",$dbtype="main") {
		redis_api::useConfig($dbname);
		$db = new SDb();
		$db->useConfig($dbname, $dbtype);
		return $db;
	}

	public static function getschoolList($page = null,$params,$length =null){
		$db = self::InitDB("db_utility","query");
		$table=array("t_region_school");

		if($page){$db->setPage($page);}
		if($length){$db->setLimit($length);}
		$condition = '';
		$item ='';
		$orderby = array("pk_school"=>"ASC");
		if(isset($params->school_type)){
			$condition  .= 'school_type='.$params->school_type;
		}
		if(isset($params->region)){
			$condition .='fk_region='.$params->region;
		}
		if(!empty($params->school_name)){
			$condition .="school_name LIKE %'".$params->school_name."%'";
		}
		$v = $db->select($table,$condition,$item,"",$orderby);
		return $v;
	}


	public static function addBanner($data){
		$db = self::InitDB('db_platform');
		$table = array('t_platform_banner');
		return $db->insert($table,$data);
	}

	public static function updateBanner($banner_id,$data){
        $db = self::InitDB('db_platform');
        $table = array('t_platform_banner');
		$condition = array('pk_banner' => $banner_id);
		return $db->update($table, $condition, $data);
 	}

	public static function delBanner($banner_id){
        $db = self::InitDB('db_platform');
        $table = array('t_platform_banner');
		$condition = array('pk_banner' => $banner_id);
 		return $db->delete($table,$condition);
    }

	public static function getBannerByid($banner_id){
        $db = self::InitDB('db_platform','query');
        $table = array('t_platform_banner');
		$condition = array('pk_banner' => $banner_id);
 		return $db->selectOne($table,$condition);
	}
	
	public static function getShowBannerByType($type){
        $db = self::InitDB('db_platform','query');
        $table = array('t_platform_banner');
		$condition = array('status' => 1,'type'=>$type);
		$orderby = array('order_no'=>'asc');
 		return $db->select($table,$condition,'','',$orderby);
	}
	
	public static function getBannerByType($type){
        $db = self::InitDB('db_platform','query');
        $table = array('t_platform_banner');
		$condition = "status <> -1 AND type = $type";
		$orderby = array('order_no'=>'asc');
 		return $db->select($table,$condition,'','',$orderby);
	}
	
	public static function platformBlockList(){
		$db = self::InitDB('db_platform','query');
		$table = array('t_platform_block');
		$condition= array("status"=>1);
		return $db->select($table,$condition,'','','');

	}
	public static function getBlockByInfo($block_id){
        $db = self::InitDB('db_platform','query');
        $table = array('t_platform_block');
		$condition = array('pk_block' => $block_id);
 		return $db->selectOne($table,$condition);
	}
	public static function getBlockContent($block_id){
        $db = self::InitDB('db_platform','query');
        $table = array('t_platform_block_content');
		$condition = array('fk_block' => $block_id);
 		return $db->select($table,$condition,'','',array("sort"=>"asc"),'');
	}
	
	public static function updatesetting($block_id,$data){
        $db = self::InitDB('db_platform');
        $table = array('t_platform_block');
		$condition = array('pk_block' => $block_id);
		return $db->update($table, $condition, $data);
 	}
	public static function addBlockContent($data){
		$db = self::InitDB('db_platform');
		$table = array('t_platform_block_content');
		return $db->insert($table,$data);
	}
	public static function delBlockContent($block_id){
        $db = self::InitDB('db_platform');
        $table = array('t_platform_block_content');
		$condition = array('fk_block' => $block_id);
 		return $db->delete($table,$condition);
    }
}
