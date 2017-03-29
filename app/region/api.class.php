<?php
class region_api{
	public static function listRegion($parent_region_id=0){
		$params=array("parent_region_id"=>$parent_region_id);
		$ret = utility_services::call("/region/main/listregion",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}else{
			return false;
		}
	}

    public static function listRegion2($pid='',$level='')
    {
      if(isset($pid))
      {
        $params = ['parent_region_id'=>$pid];
      }
      if(isset($level))
      {
        $params = ['level'=>$level];
      }
      $ret = utility_services::call("/region/main/listregion",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}else{
			return false;
		}
    }

	public static function listSchool($region_id,$school_type=1){
		$params=array("region_id"=>$region_id,"school_type"=>$school_type);
		$ret = utility_services::call("/region/main/listSchool",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}else{
			return false;
		}
	}
	
	public static function scoolByRegionIdArr($params){
		$ret = utility_services::call("/region/main/scoolByRegionIdArr",$params);
		return $ret;
	}
}
