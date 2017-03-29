<?php
/**
  *  by fanbin
  *  计算count的公用方法
  */
 
class utility_countbySphinx{
	
	public static function countSections($showData,$orgOwner=0){
	/*
	 *
	 * showData 是一个二维数组 里面包含course_id 
	 *  array(
	 *	 "course_id"=>"123";
	 *	)
	 * orgOwner 是机构所有者id
	 */

		//{{{ 获取course_id集合
		$courseidsArr = array();
		foreach($showData as $courselistk=>$courselistv){
			$courseidsArr[] = $courselistv["course_id"];
		}
		$courseids = implode(",",$courseidsArr);
		///}}}

		//获取当前用户的课程列表明
		/*中间层取数据**/
		//	{{{
		$fArray = array("course_id","title","create_time","desc");
		$qArray=array(
			'course_id'=>$courseids,
		);
        //change by zhangtaifeng 2015/09/21
        if($orgOwner>0){
			$aArray['user_id']=$orgOwner;
        }

		$obArray = array(
			'top'=>'desc',
		);
		$seekArr = array(
			"f"=>$fArray,
			"q"=>$qArray,
			"ob"=>$obArray,
			"p"=>1,
			"pl"=>500,
		);
		$retCourseData = seek_api::seekcourse($seekArr);
		if(!empty($retCourseData->data)){
			$courseData = $retCourseData->data;
			//	print_r($courseData);
			$countCourseSections = array();
			foreach($courseData as $courseDatak=>$courseDatav){
				if(!empty($courseDatav->section_count)){
					$countCourseSections[$courseDatav->course_id] = $courseDatav->section_count;
				}else{
					$countCourseSections[$courseDatav->course_id] = 0;
				}
			}
			/*在showData上面加上sections的count*/
			//	{{{
		
			foreach($showData as $showk=>$showv){
				foreach($countCourseSections as $countsecsk=>$countsecsv){
					if($showData[$showk]["course_id"] ==$countsecsk){
						$showData[$showk]["countsecs"] = $countsecsv;
					}
				}
			}
			//	}}}
		}
		return $showData;
		//}}}
	}
}
