<?php
class stat_api{
	public static function addUserStat($params){
		$ret = utility_services::call("/stat/statuser/addUserStat",$params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
	}
	
	public static function getUserPlanStatByPidArr($uid,$planIdArr){
		$ret = utility_services::call("/stat/userplan/getUserPlanStatByPidArr/$uid",$planIdArr);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

	public static function getUserPlanStatByPid($planId,$page=1,$length=50){
		$ret = utility_services::call("/stat/userplan/getUserPlanStatByPid/$planId/$page/$length",array());
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
    /*获取教师统计数据
     * @param $uid
     * @return array
     * @author ljj
     */
    public static function getTeacherStat($uid){
        $ret=utility_services::call("/stat/statteacher/getTeacherStat/$uid");
        if (!empty($ret->result)) {
            return $ret->result;
        }
        return false;
    }
	/*获取上课统计数据
     * @param $pidStr
     * @return array
     * @author Panda <longhoouan@gn100.com>
     */
	public static function getPlanStatByPidStr($pidStr){
		$params = array("pidStr"=>$pidStr);
		$ret=utility_services::call("/stat/statplan/getPlanStatByPidStr", $params);
		if (!empty($ret)) {
			return $ret;
		}
		return false;
	}
    /*获取机构学生统计数据
     * @param $owner,$uid
     * @return array
     * @author Panda <zhangtaifeng@gn100.com>
     */
    public static function getOrgStudentStat($owner,$uid){
        $params= new stdCLass;
        $ret=utility_services::call("/stat/statuser/getOrgStudentStat/$owner/$uid", $params);
        if (!empty($ret->data)) {
            return $ret->data;
        }
        return false;
    }
	
	public static function addPlanPhraseLog($data){
		$params = new stdCLass;
		$params->data = $data;
		$ret = utility_services::call("/stat/userplan/addPlanPhraseLog", $params);
		return $ret;
	}

	public static function getDayOrgStatByOwnerid($ownerId,$start,$end){
		$params = new stdCLass;
		$params->fk_user = $ownerId;
		$params->min_date = $start;
		$params->max_date = $end;
		$url = 'stat/dayuserorgstat/getDayOrgStatByOwnerid/';
		$ret = utility_services::call($url, $params);
		if(!empty($ret->result->data)){
			return $ret->result->data;
		}else{
			return false;
		}
	}
	public static function getDayOrgStatOrderCountByDay($ownerId,$start,$end){
		$params = new stdCLass;
		$params->fk_user = $ownerId;
		$params->min_date = $start;
		$params->max_date = $end;
		$url = 'stat/dayuserorgstat/getOrgOrderCountByDay/';
		$ret = utility_services::call($url, $params);
		if(!empty($ret->result->data)){
			return $ret->result->data;
		}else{
			return false;
		}
	}
	public static function getPlanPhraseLogByPid($pid,$uid=null,$planPhraseIdArr=array()){
		$params = new stdCLass;
		$params->pid = $pid;
		if(!empty($uid)){
			$params->uid = $uid;
		}
		if(!empty($planPhraseIdArr)){
			$params->planPhraseIdStr = implode(',',$planPhraseIdArr);
		}
		$ret = utility_services::call("/stat/userplan/getPlanPhraseLogByPid", $params);
		return $ret;
	}

	//获取班级备课出题答题统计
	public static function getClassExamStat($pid){
		$params = new stdCLass;
		$params->pid = $pid;
		$ret = utility_services::call("/stat/statexam/getClassExamStat", $params);
		return $ret;
	}

	//获取班级快速出题答题统计
	public static function getClassPhraseStat($pid){
		$params = new stdCLass;
		$params->pid = $pid;
		$ret = utility_services::call("/stat/statphrase/getClassPhraseStat", $params);
		return $ret;
	}

	public static function updateDownloadCount($planAttachId){
		$ret = utility_services::call("/course/planattach/updateDownloadCount/$planAttachId", array());
		return $ret;
	}
	
	//获取用户课程学习时间
	public static function getUserCourseStudyTime($uid,$cidArr){
		$params = new stdCLass;
		$params->uid = $uid;
		$params->cidArr = $cidArr;
		$ret = utility_services::call("/stat/statcourse/coursetotal", $params);
		return $ret->data;
	}

	public static function getClassPlanStatByPidArr($params){
		$ret = utility_services::call("/stat/userplan/getClassPlanStatByPidArr/",$params);
		if(!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

	public static function getClassUserStatByClassId($params){
		$ret = utility_services::call("stat/statClassUser/getClassUserStatByClassId/",$params);
		if(!empty($ret->result)){
			return $ret->result;
		}
		return false;
	}
}
?>
