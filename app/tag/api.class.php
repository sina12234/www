<?php
class tag_api{
	public static function getTagByGroupId($group_id){
		$ret = utility_services::call("/tag/tag/getsubjectTag/$group_id");
		if(!empty($ret->items)){
			$tag_arr = array();
			foreach($ret->items as $to){
				$tag_arr[$to->pk_tag] = $to->name;
			}
			return $tag_arr;
		}else{
			return false;
		}
	}
    public static function getTagInfoByGroupId($groupId){
        $params           = new stdclass;
        $params->group_id   = $groupId;
		$ret = utility_services::call("/tag/tag/getTagInfoByGroupId/",$params);
        if (empty($ret->code)) {
            return $ret->result;
        } else {
            return false;
        }
    }
	public static function getRecentTag($params){
		$ret = utility_services::call("/user/organizationTag/GetOrgTagSort/",$params);
        if (!empty($ret)) {
            return $ret;
        } else {
            return false;
        }
    }
	public static function getUserSelectedCourseTag($params){
		$ret = utility_services::call("/user/organizationTag/getUserSelectedCourseTag/",$params);
        if (!empty($ret)) {
            return $ret;
        } else {
            return false;
        }
    }
}
