<?php

class interface_courseApi
{
    public static function getTeacherStudent($cid, $classId)
    {
        if (!utility_tool::check_int($cid)) return false;

        if (!utility_tool::check_int($classId)) return false;

        $courseUser = course_api::listRegistration(
            [
                'course_id' => $cid,
                'class_id' => $classId,
            ]
        );

        $data = [];
        if (!empty($courseUser->data)) {
            foreach($courseUser->data as $v) {
                $data[] = [
                    'uid' => $v->uid,
                    'name' => !empty($v->user_info->student_name)
                        ? $v->user_info->student_name
                        : !empty($v->user_info->name)
                            ? $v->user_info->name
                            : SLanguage::tr('未设置', 'message'),
                    'thumb' => !empty($v->user_info->thumb_med)
                        ? interface_func::imgUrl($v->user_info->thumb_med)
                        : ''
                ];
            }
        }

        return $data;
    }

    public static function getDomainUrl($data)
    {
		$ownerId = 0;
        if (!empty($data['courseId'])) {
			$params = new stdclass();
			$params->courseId = $data['courseId'];
			$res = task_api::getcourseplan($params);
			$ownerId = !empty($res->data) ? $res->data[0]->fk_user : 0;
        }
		
		if (!empty($data['planId'])) {
            $res = course_api::getplan($data['planId']);
			$ownerId = !empty($res) ? $res->user_id : 0;
        }

        $header = utility_net::isHTTPS() ? "https://" : "http://";
        $url = '';

        if (!empty($ownerId)) {
            $domainInfo =user_organization::getSubdomainByUid($ownerId);
            if (!empty($domainInfo->subdomain)) {
                $url = $header.user_organization::course_domain($domainInfo->subdomain);
            }
        }

        return $url;

    }

    public static function getCourseBasic($cid)
    {
        $url = '/course/info/GetCourseBasic/'.$cid;
        $res = interface_func::requestApi($url);
        if (!empty($res['code'])) return [];

        return $res['result'];
    }
	public static function getUnipayStatus($params)
    {
		$ret = utility_services::call("/order/feeorder/GetOrderOneInfoByOrderId",$params);
        return $ret;
    }
}
