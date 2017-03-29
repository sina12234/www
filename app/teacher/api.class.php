<?php

class teacher_api
{
    /**
     * @desc get teacher course comment by teacherId
     *
     * @param $params ['teacherId' => 197]
     * @return array
     *
     * @author wen
     */
    public static function getTeacherCourseComment($params)
    {
        $data = [];
        !empty($params['teacherId']) && $data['teacherId'] = $params['teacherId'];
        !empty($params['page']) && $data['page'] = $params['page'];
        !empty($params['length']) && $data['length'] = $params['length'];
        !empty($params['sort']) && $data['sort'] = $params['sort'];

//        $res = utility_services::call('/comment/course/GetTeacherCourseComment', $data);
        $res = utility_services::call('/comment/course/GetTeacherScoreComment', $data);//获取老师的打分情况
        if (empty($res->result->data)) return [];
        $userInfo  = user_api::listUsersByUserIds($res->result->userIdArr);
        $userIdArr = [];
        if (!empty($userInfo->result)) {
            foreach ($userInfo->result as $v) {
                $userIdArr[$v->pk_user] = $v->pk_user;
                $users[$v->pk_user]     = [
                    'name'  => $v->name,
                    'thumb' => interface_func::imgUrl($v->thumb_med)
                ];
            }
        }

        $tCourseInfo = self::getTeacherCourseListInfoByCourseIdArr($res->result->courseIdArr);

//        $tPlanInfo = self::getTeacherPlanListInfo($res->result->courseIdArr);
        $tPlanInfo = self::getTeacherPlanListInfo($res->result->planIdArr);

        $userLevel = utility_services::call('/user/score/listByUidArr', ['userIdArr' => $userIdArr]);
        if (!empty($userLevel->result)) {
            foreach ($userLevel->result as $val) {
                $userLevelInfo[$val->fk_user] = [
                    'level' => $val->fk_level,
                    'title' => $val->title,
                    'score' => $val->score
                ];
            }
        }

        $list = [];
        foreach ($res->result->data as $v) {
            $list[] = [
                'userName'    => !empty($users[$v->fk_user]['name']) ? $users[$v->fk_user]['name'] : '',
                'thumbMed'    => !empty($users[$v->fk_user]['thumb']) ? $users[$v->fk_user]['thumb'] : '',
                'comment'     => !empty($v->comment) ? $v->comment : '',
                'commentId'     => !empty($v->pk_comment) ? $v->pk_comment : '',
                'courseTitle' => !empty($tCourseInfo[$v->fk_course]['title']) ? $tCourseInfo[$v->fk_course]['title'] : '',
                //'courseTitle' => !empty($tPlanInfo[$v->fk_plan]['courseTitle']) ? $tPlanInfo[$v->fk_plan]['courseTitle'] : '',
                'className'   => !empty($tPlanInfo[$v->fk_plan]['className']) ? $tPlanInfo[$v->fk_plan]['className'] : '',
                'sectionName' => !empty($tPlanInfo[$v->fk_plan]['sectionName']) ? $tPlanInfo[$v->fk_plan]['sectionName'] : '',
                'time'        => !empty($v->last_updated) ? $v->last_updated : '',
                'score'       => !empty($v->score) ? $v->score : '',
                'title'       => !empty($userLevelInfo[$v->fk_user]['title']) ? $userLevelInfo[$v->fk_user]['title'] : '书生',
                'userLevel'   => !empty($userLevelInfo[$v->fk_user]['level']) ? $userLevelInfo[$v->fk_user]['level'] : 1,
            ];
        }
        return array("list"=>$list,"totalPage"=>$res->result->totalPage);
    }
    /**
     * @param $courseIdArr
     * @return array
     * author wsn
     */
    public static function getTeacherCourseListInfoByCourseIdArr($courseIdArr)
    {
        if (count($courseIdArr) < 1) return [];

        $courseParams = [
            'q' => [
                //'admin_status' => 1,
                'course_id'    => implode(',', $courseIdArr),
                'deleted'      =>'-1,0'
            ],
            'f' => ['title', 'course_id']
        ];

        $courseList = seek_api::seekCourse($courseParams);

        if (empty($courseList->data)) return [];

        $data = [];
        foreach ($courseList->data as $v) {
            $data[$v->course_id] = ['title' => $v->title];
        }

        return $data;
    }
    /**
     * @desc get teacher course list info by courseIdArr
     *
     * @param $courseIdArr
     * @return array
     *
     * @author wen
     */
    public static function getTeacherCourseListInfo($courseIdArr)
    {
        if (count($courseIdArr) < 1) return [];

        $courseParams = [
            'q' => [
                'admin_status' => 1,
                'course_id'    => implode(',', $courseIdArr),
            ],
            'f' => ['title', 'course_id']
        ];

        $courseList = seek_api::seekCourse($courseParams);

        if (empty($courseList->data)) return [];

        $data = [];
        foreach ($courseList->data as $v) {
            $data[$v->course_id] = ['title' => $v->title];
        }

        return $data;
    }

    /**
     * @desc get teacher plan list info by courseIdArr
     *
     * @param $courseIdArr
     * @return array
     *
     * @author wen
     */
    public static function getTeacherPlanListInfo($planIdArr)
    {
        if (count($planIdArr) < 1) return [];

        $planParams = [
            'q' => [
                'plan_id' => implode(',', $planIdArr),
                'deleted'   =>'-1,0'
            ],
            'f' => ['class_name', 'section_name', 'plan_id', 'course_name']
        ];
        $planList = seek_api::seekPlan($planParams);
        if (empty($planList->data)) return [];

        $data = [];
        foreach ($planList->data as $v) {
            $data[$v->plan_id] = [
                'className'   => $v->class_name,
                'sectionName' => $v->section_name,
                'courseTitle' => $v->course_name
            ];
        }

        return $data;
    }

    public static function getBelongTagByGropId($groupId)
    {
        return utility_services::call('/tag/teacher/GetBelongTagByGropId/'.$groupId);
    }

    public static function getTagInTagId($data)
    {
        $params          = new stdclass;
        $params->tag_ids = $data['tag_id'];

        return utility_services::call('/tag/teacher/GetTagInTagId/', $params);
    }

    public static function getTagUserInUids($data)
    {
        $params          = new stdclass;
        $params->ids     = $data['ids'];
        $params->groupId = $data['groupId'];

        return utility_services::call('/tag/teacher/GetTagUserInUids/', $params);
    }

    /**
     * @desc get teacher info by teacher id
     *
     * @param $teacherId
     * @return array
     *
     * @author wen
     */
    public static function getTeacherInfo($teacherId)
    {
        if (!is_numeric($teacherId) || !(int)($teacherId)) return [];

        $param = [
            'q' => ['teacher_id' => $teacherId],
            'f' => [
                'teacher_id',
                'subject_id',
                'name',
                'real_name',
                'gender',
                'thumb_med',
                'thumb_big',
                'province',
                'city',
                'title',
                'college',
                'years',
                'diploma',
                'desc',
                'brief_desc',
                'subject',
                'grade',
                'course_count',
                'totaltime',
                'student_count',
                'avg_score',
                'comment',
                'student_score',
                'desc_score',
                'explain_score',
                'comment'
            ]
        ];

        $res = seek_api::seekTeacher($param);
        if (empty($res->data[0])) return [];
        $imgBanner = utility_services::call("/blog/teacher/getTeacherImgBanner/{$teacherId}");

        $res->data[0]->subject   = self::getSubjectNameV2($res->data[0]->subject);
        $res->data[0]->grade     = self::getGradeName($res->data[0]->grade);
        $res->data[0]->diploma   = !empty(teacher_major::$diploma[$res->data[0]->diploma])
            ? teacher_major::$diploma[$res->data[0]->diploma]
            : '';
        $res->data[0]->imgBanner = !empty($imgBanner->result->banner_img) ? $imgBanner->result->banner_img : '/assets_v2/img/banner_bg.jpg';

        $res->data[0]->real_name = !empty($res->data[0]->real_name)
                                    ? $res->data[0]->real_name
                                    : (!empty($res->data[0]->name) ? $res->data[0]->name : 'null');
        return $res->data[0];
    }

    /**
     * @desc get teacher course by teacherId
     *
     * @param $tid
     * @param $owner
     * @param int $page
     * @param int $length
     * @return array
     *
     * @author wen
     */
    public static function getTeacherCourse($tid, $owner = 0, $page = 1, $length = 100)
    {
        $param = [
            'teacherId' => $tid,
            'orgOwner'  => $owner ?: 0,
            'page'      => $page,
            //'length'    => $length
        ];

        $courseList = utility_services::call('/common/api/ListsByUserClassId', $param);
        if (empty($courseList->result)) return [];

        $courseIdArr = [];
        foreach ($courseList->result as $v) {
            $courseIdArr[$v->fk_course] = $v->fk_course;
        }
        $courseIdStr = implode(',', $courseIdArr);


        $courseArr = array(
            'q'  => array(
                'course_id' => $courseIdStr,
                'status'    => '1,2,3',
                'admin_status' => 1
            ),
            'f'  => array(
                'course_id',
                'title',
                'fee_type',
                'start_time',
                'end_time',
                'comment',
                'subdomain',
                'org_subname',
                'course_type',
                'thumb_med',
                'price',
                'status',
                'avg_score',
                'user_id',
                'course_attr'
            ),
            'pl' => $length,
            'ob' => ['start_time' => 'desc'],
        );

        $courseList      = seek_api::seekCourse($courseArr);
        if (empty($courseList->data))
            return [];

        $liveSectionName = self::getLivingSectionName($courseIdArr);
        $data            = [];
        foreach ($courseList->data as $v) {
            $tPrice = $v->price / 100;
            if (!is_int($tPrice)) {
                $tPrice = number_format($v->price / 100, 2);
            }
            $data[]      = [
                'course_id'     => $v->course_id,
                'user_id'       => $v->user_id,
                'org_subname'   => $v->org_subname,
                'orgName'       => $v->org_subname,
                'title'         => $v->title,
                'course_type'   => $v->course_type,
                'major'         => self::getSubjectNameV2($v->course_attr),
                'start_time'    => !empty($liveSectionName['startTime'][$v->course_id])
                                    ? utility_tool::t($liveSectionName['startTime'][$v->course_id]) : '',
                'status'        => course_status::$status[$v->status],
                'fee_type'      => $v->fee_type ? $tPrice : '免费',
                'thumb'         => utility_cdn::file($v->thumb_med),
                'score'         => round($v->avg_score / 10),
                'comment'       => $v->comment,
                'domain'       => user_organization::course_domain($v->subdomain),
                'sectionName'   => !empty($liveSectionName['sectionName'][$v->course_id])
                                    ? $liveSectionName['sectionName'][$v->course_id] : ''
            ];

        }

        return $data;
    }

    public static function getLivingSectionName($courseIdArr)
    {
        $param = array(
            'q'  => array(
                'course_id' => implode(',', $courseIdArr),
                'status'    => '1,2',
            ),
            'f'  => array(
                'section_name',
                'start_time',
                'status',
                'course_id'
            ),
            'ob' => ['start_time' => 'desc'],
            'pl' => 500
        );

        $res  = seek_api::seekPlan($param);
        $data = [];

        if (!empty($res->data)) {
            foreach ($res->data as $v) {
                if ($v->status == 2) {
                    $data['sectionName'][$v->course_id] = $v->section_name;
                }
                if ($v->status == 1) {
                    $data['startTime'][$v->course_id] = $v->start_time;
                }
            }
        }

        return $data;
    }

    /**
     * @desc get Domain and OrgName by user id arr
     *
     * @param $uidArr
     * @return array
     *
     * @author wen
     */
    public static function getDomainOrgName($uidArr)
    {
        if (count($uidArr) < 1) return [];

        $domain  = user_api::getSubdomainByUidArr($uidArr);
        $orgName = user_organization::getOrgInfoByUidArr($uidArr);

        $data = [];
        if (!empty($orgName)) {
            foreach ($orgName as $org) {
                $data[$org->user_owner] = [
                    'name'    => $org->name,
                    'subname' => $org->subname
                ];
            }
        }

        if (!empty($domain->result->data->items)) {
            foreach ($domain->result->data->items as $v) {
                $data[$v->fk_user]['subdomain'] = '//'.user_organization::course_domain($v->subdomain);
            }
        }

        return $data;
    }

    /**
     * @desc get image list by teacher id
     *
     * @param $teacherId
     * @param int $page
     * @param int $length
     * @return array
     *
     * @author wen
     */
    public static function getImgList($teacherId, $page = 1, $length = 20)
    {
        if (!is_numeric($teacherId) || !(int)($teacherId)) return [];

        $params = [
            'teacherId' => $teacherId,
            'page'      => $page,
            'length'    => $length
        ];

        $res = utility_services::call('/blog/teacher/GetImgList', $params);

        if (empty($res->result)) return [];

        $data = [];
        foreach ($res->result as $v) {
            $data[] = [
                'imgId'     => $v->pk_teacher_img,
                'name'      => $v->image_name,
                'thumb_big' => interface_func::imgUrl($v->thumb_big),
                'thumb_med' => interface_func::imgUrl($v->thumb_med),
            ];
        }

        return $data;
    }

    /**
     * @desc get image info by image id
     *
     * @param $imgId
     * @return array
     *
     * @author wen
     */
    public static function getImgOne($imgId)
    {
        if (!is_numeric($imgId) || !(int)($imgId)) return [];

        $res = utility_services::call("/blog/teacher/GetImgOne/$imgId");

        if (empty($res->result)) return [];

        return $res->result;
    }

    /**
     * @desc get article detail by article id
     *
     * @param $articleId
     * @return array
     *
     * @author wen
     */
    public static function getArticleDetail($articleId)
    {
        if (!is_numeric($articleId) || !(int)($articleId)) return [];

        $res = utility_services::call("/blog/article/Row/$articleId");

        if (empty($res->result)) return [];

        return $res->result;
    }

    /**
     * @desc get article list by teacher id
     *
     * @param $teacherId
     * @param int $page
     * @param int $length
     * @return array
     *
     * @author wen
     */
    public static function getArticleListByTeacherId($teacherId, $page = 1, $length = 20)
    {
        if (!is_numeric($teacherId) || !(int)($teacherId)) return [];

        $params = [
            'teacherId' => $teacherId,
            'page'      => $page,
            'length'    => $length
        ];

        $res = utility_services::call('/blog/article/GetArticleList', $params);

        if (empty($res->result)) return [];

        $data = [];
        foreach ($res->result as $v) {
            $data[] = [
                'articleId' => $v->pk_article,
                'title'     => $v->title,
                'summary'   => $v->summary,
                'share'     => $v->share_num,
                'comment'   => $v->comment_num,
                'thumb'     => $v->thumb
            ];
        }

        return $data;
    }

    /**
     * @desc get all article list by teacher id
     *
     * @param $teacherId
     * @param $tagId
     * @param int $page
     * @param int $length
     * @return array
     *
     * @author wen
     */
    public static function getArticle($teacherId, $tagId = '', $draft = 0, $page = 1, $length = 20)
    {
        if (!is_numeric($teacherId) || !(int)($teacherId)) return [];

        $params = [
            'teacherId' => $teacherId,
            'tagId'     => $tagId,
            'draft'     => $draft,
            'page'      => $page,
            'length'    => $length
        ];

        $res = utility_services::call('/blog/article/lists', $params);
        if ($res->code) return [];

        return $res->result;
    }

    public static function getDraftList($teacherId, $page = 1, $length = 20)
    {
        if (!is_numeric($teacherId) || !(int)($teacherId)) return [];

        $params = [
            'teacherId' => $teacherId,
            'draft'     => true,
            'page'      => $page,
            'length'    => $length
        ];

        $res = utility_services::call('/blog/article/lists', $params);
        if ($res->code) return [];

        return $res->result;
    }

    /**
     * @desc get tag name total list by teacher id
     *
     * @param $teacherId
     * @return array
     *
     * @author wen
     */
    public static function getTagNameTotalList($teacherId)
    {
        if (!is_numeric($teacherId) || !(int)($teacherId)) return [];

        $res = utility_services::call("/blog/article/getTagNameTotalList/{$teacherId}");

        if (empty($res->result)) return [];

        return $res->result;
    }

    /**
     * @desc get article comment by article id
     *
     * @param $articleId
     * @param int $page
     * @param int $length
     * @return array
     *
     * @author wen
     */
    public static function getArticleComment($articleId, $page = 1, $length = 20)
    {
        if (!is_numeric($articleId) || !(int)($articleId)) return [];

        $params = [
            'articleId' => $articleId,
            'page'      => $page,
            'length'    => $length
        ];

        $res = utility_services::call('/blog/article/GetArticleComment', $params);

        if (empty($res->result)) return [];

        return $res->result;
    }

    /**
     * @desc get grade name
     *
     * @param $obj [two dimensional array]
     * @return string
     *
     * @author wen
     */
    public static function getGradeName($obj)
    {
        $grade = [];

        foreach ($obj as $v) {
            if (!empty($v->grade_id) && !in_array($v->grade_id, [1000, 2000, 3000])) {
                $grade[$v->grade_id] = SLanguage::tr($v->grade_name, 'course.list');
            }
        }

        return implode(',', $grade);
    }

    /**
     * @desc get subject name
     *
     * @param $obj [two dimensional array]
     * @return string
     *
     * @author wen
     */
    public static function getSubjectNameV2($obj)
    {
        $subject = [];
        if(!empty($obj)){
            foreach ($obj as $v) {
                if (!empty($v->subject_id)) {
                    $subject[$v->subject_id] = SLanguage::tr($v->subject_name, 'course.list');
                }
            }
        }

        return implode(',', $subject);
    }

    public static function getSubjectName($obj)
    {
        $subject = [];

        foreach ($obj as $v) {
            if ($v->attr_name_display == '科目') {
                foreach ($v->attr_value as $p) {
                    $subject[$p->attr_value_id] = SLanguage::tr($p->attr_value_name, 'course.list');
                }
            }
        }

        return implode(',', $subject);
    }

    /**
     * @desc check student whether collection teacher
     *
     * @param $uid
     * @param $teacherId
     * @return bool
     *
     * @author wen
     */
    public static function checkTeacherFav($uid, $teacherId)
    {
        if (!$uid || !$teacherId) return false;

        $data = [
            'userId'    => $uid,
            'teacherId' => $teacherId
        ];

        $res = utility_services::call('/blog/article/CheckTeacherFav', $data);

        return $res->code ? 0 : 1;
    }

    /**
     * @desc get teacher fav total num
     *
     * @param $tid
     * @return int
     *
     * @author wen
     */
    public static function getTeacherFavTotal($tid)
    {
        $res = utility_services::call("/blog/article/getTeacherFavTotal/{$tid}");

        return $res->code ? 0 : $res->result[0];
    }

    /**
     * @desc update teacher banner img
     *
     * @param $tid
     * @param $imgPath
     * @return bool
     *
     * @author wen
     */
    public static function updateBannerImg($tid, $imgPath)
    {
        if (!(int)($tid) || !$imgPath) return false;

        $data = [
            'teacherId' => $tid,
            'imgPath'   => $imgPath
        ];

        $res = utility_services::call('/blog/teacher/UpdateBannerImg', $data);

        return $res->code ? false : true;
    }

    /**
     * @desc update teacher img name
     *
     * @param $tid
     * @param $imgName
     * @return bool
     *
     * @author wen
     */
    public static function updateTeacherImgName($tid, $imgName)
    {
        if (!(int)($tid) || !$imgName) return false;

        $data = [
            'teacherId' => $tid,
            'imgName'   => $imgName
        ];

        $res = utility_services::call('/blog/teacher/UpdateTeacherImgName', $data);

        return $res->code ? false : true;
    }

    /**
     * @desc add tag
     *
     * @param $uid
     * @param $name
     * @return bool|int
     *
     * @author wen
     */
    public static function addTag($uid, $name)
    {
        if (!$uid || !$name) return false;

        $data = ['userId' => $uid, 'tagName' => $name];
        $res  = utility_services::call("/blog/article/addTag", $data);

        return !empty($res->result->tagId) ? $res->result->tagId : 0;
    }

    /**
     * @desc add teacher img
     *
     * @param $data
     * @return bool
     *
     * @author wen
     */
    public static function addTeacherImg($data)
    {
        $res = utility_services::call("/blog/teacher/addTeacherImg", $data);

        return $res->code ? false : true;
    }

    public static function addMapTagArticle($data)
    {
        if (empty($data['userId']) || empty($data['tagId']) || empty($data['articleId']))
            return false;

        $data['status'] = isset($data['status']) && $data['status'] ? $data['status'] : 0;

        $res = utility_services::call("/blog/article/AddMapTagArticle", $data);

        return $res->code ? false : true;
    }

    /**
     * @desc update mapping tag article by article id and tag id
     *
     * @param $articleId
     * @param $tagId
     * @return bool
     *
     * @author wen
     */
    public static function updateMapTagArticle($articleId, $tagId)
    {
        if (!(int)($articleId) || !(int)($tagId))
            return false;

        $data = [
            'articleId' => $articleId,
            'tagId'     => $tagId
        ];

        $res = utility_services::call("/blog/article/updateMapTagArticle", $data);

        return $res->code ? false : true;
    }

    public static function delMapTagArticle($uid, $articleId, $tagId)
    {
        if (!(int)($articleId) || !(int)($tagId) || !(int)($uid))
            return false;

        $data = [
            'articleId' => $articleId,
            'tagId'     => $tagId,
            'uid'       => $uid
        ];

        $res = utility_services::call("/blog/article/delMapTagArticle", $data);

        return $res->code ? false : true;
    }

    /**
     * @desc add fav teacher
     *
     * @param $uid
     * @param $teacherId
     * @return bool
     */
    public static function addFavTeacher($uid, $teacherId)
    {
        $data = [
            'userId'    => $uid,
            'teacherId' => $teacherId
        ];

        $res = utility_services::call('/blog/article/AddFavTeacher', $data);

        return !$res->code;
    }

    public static function cancelFavTeacher($uid, $teacherId)
    {
        $data = [
            'userId'    => $uid,
            'teacherId' => $teacherId
        ];

        $res = utility_services::call('/blog/article/CancelFav', $data);

        return !$res->code;
    }
	/*
	 *@desc 老师下课程数
	 *@params $userId $ownerId
	 */
	public static function teacherCourseNum($userId=0,$courseIdArr,$data=array()){
		$data = array();
		$classListShow = course_api::courselikelist(0,$courseIdArr,$data=array());
		if(empty($classListShow->data)) return $data;
		foreach($classListShow->data as $k=>$v){
			$numData[$v->type][] = $v;
		}
		
		$data = [
			'livingNum' => !empty($numData[1]) ? count($numData[1]) : 0,
			'recordNum' => !empty($numData[2]) ? count($numData[2]) : 0,
			'underNum'  => !empty($numData[3]) ? count($numData[3]) : 0,
			'total'     => !empty($classListShow->pageSize) ? $classListShow->pageSize : 0
		];
		
		return $data;
	}

    public static function teacherCourseNumV2($userId, $ownerId = 0){
        $data = array();
        if(empty($userId)) return $data;
        $classParam = [
            'user_id'       => $ownerId,
            'user_class_id' => $userId
        ];
        $classList = course_api::classlistbycond($classParam);
        if(empty($classList)) return $data;

        foreach($classList->data as $val){
            $courseIdArr[$val->course_id] = $val->course_id;
        }
        $sArray = [
            "user_id"           =>$ownerId,
            "user_class_id" =>$userId,
            "course_ids"    =>$courseIdArr,
        ];
        $classListShow = course_api::classListByCourseIds($sArray,$page=1,$length=0);
        if(empty($classListShow->data)) return $data;
        
        foreach($classListShow->data as $k=>$v){
            $numData[$v->course_type][] = $v;
        }

        $data = [
            'livingNum' => !empty($numData[1]) ? count($numData[1]) : 0,
            'recordNum' => !empty($numData[2]) ? count($numData[2]) : 0,
            'underNum'  => !empty($numData[3]) ? count($numData[3]) : 0,
            'total'     => !empty($classListShow->data) ? count($classListShow->data) : 0
        ];

        return $data;
    }

    /*
     * @desc 老师下的课程
     */
    public static function teacherCourse($teacherId=0, $page=1, $length=20, $orgId=0){
        $data   = array();
        $result = course_api::getcourseteacher(array('teacherId'=>$teacherId), $page, $length);
        if(empty($result->items)) return $data;

        foreach($result->items as $val){
            $courseIdArr[$val->fk_course] = $val->fk_course;
        }

        $courseIds = implode(',', $courseIdArr);
        $params = [
            'q' => ['course_id'=>$courseIds],
            'f' => [
                'course_id','thumb_med','title','price','course_type','user_total','org_subname'
            ],
            'p' => $page,
            'pl'=> $length,
            'ob'=> ['start_time'=>'desc']
        ];
        if(!empty($orgId)){
            $params['q']['expression'] = "@resell_org_id =".$orgId." | @org_id=".$orgId;
        }
        $seekCourse = seek_api::seekCourse($params);
        if(empty($seekCourse->data)) return $data;

        return $seekCourse;
    }
	public static function getTeacherCourseInfoById($teacherId, $page=1, $length=0){	
		$res = utility_services::call('course/teacher/GetTeacherCourseInfoById/'.$teacherId.'/'.$page.'/'.$length);
		if (!empty($res->result->items)&&$res->code==0){
			return $res->result->items;
		}
		
		return false;
	}

    public static function getCourseByTeacherId($data, $page=1, $length=0){
        $params = array();
        if(!empty($data['teacherId'])){
            $params['teacherId'] = (int)$data['teacherId'];
        }
        if(!empty($data['isAssistant'])){
            $params['is_assistant'] = (int)$data['isAssistant'];
        }
        if(empty($params)) return false;

		$res = utility_services::call('course/teacher/getcoursebyteacherid/'.$page.'/'.$length, $params);
		if (!empty($res->result->items)&&$res->code==0){
			return $res->result->items;
		}

        return false;
    }
	
	public static function getNumCourseByTeacherId($teacherId){
		if(empty($teacherId)) return [];

		$url = '/course/teacher/getNumCourseByTeacherId/'.$teacherId;
        $res = interface_func::requestApi($url);
        if (!empty($res['code'])) return [];

        return $res['result'];
	}
	
	
}
