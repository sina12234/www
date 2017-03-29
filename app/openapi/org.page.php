<?php

class openapi_org extends openapi_base{
    /**
     * 机构列表
     * -1. $provinceId: 支持省份筛选
     * -2. $sort: 综合排序(1000) 课程数排序(2000) 老师数排序(3000)
     * -3. $keywords 关键词搜索
     *
     * @param int $provinceId 省份id
     * @param int $sort 排序
     * @param string $keywords 搜索
     * @return object
     */
    public function pageList(){
        $page   = !empty($this->params->pn) ? (int)$this->params->pn : 1;
        $length = !empty($this->params->pl) ? (int)$this->params->pl : 20;
        $sort   = !empty($this->params->sort) ? (int)$this->params->sort : 1000;
        $keywords   = !empty($this->params->keywords) ? $this->params->keywords : '';
        $provinceId = !empty($this->params->provinceId) ? (int)$this->params->provinceId : 0;

        $sorgArr = [
            1000 => ['org_id'=>'desc'],
            2000 => ['course_count'=>'desc'],
            3000 => ['visiable_teacher_count'=>'desc']
        ];
        $params = [
            'q' => ['status'=>1],
            'f' => [
                'org_id','name','subname','subdomain','thumb_med','thumb_big','desc','province',
                'city','address','student_count','visiable_teacher_count','course_count'
            ],
            'p' => $page,
            'pl'=> $length,
            'ob'=> !empty($sortArr[$sort]) ? $sortArr[$sort] : array('org_id'=>'desc')
        ];
        if(!empty($provinceId)) $params['q']['province_id'] = $provinceId;
        if(!empty($keywords)) $params['q']['search_field']  = $keywords;

        $seekOrg = seek_api::seekOrg($params);
        if(empty($seekOrg->data)) self::returnData(0, array());

        foreach($seekOrg->data as &$val){
            $val->thumb_big = interface_func::imgUrl($val->thumb_big);
            $val->thumb_med = interface_func::imgUrl($val->thumb_med);
            $val->teacher_count = $val->visiable_teacher_count;
            unset($val->visiable_teacher_count);
        }
        $data = [
            'page'      => $seekOrg->page,
            'total'     => $seekOrg->total,
            'pageTotal' => ceil($seekOrg->total/$seekOrg->pagelength),
            'data'      => $seekOrg->data
        ];
        self::returnData(0, $data);
    }

    /**
     * 机构详情
     * @param int $orgId 机构id
     * @return object
     */
    public function pageInfo(){
        $orgId = !empty($this->params->orgId) ? (int)$this->params->orgId : 0;

        $params = [
            'q' => ['org_id'=>$orgId, 'status'=>1],
            'f' => [
                'org_id','name','subname','subdomain','thumb_med','thumb_big','desc','hotline','province',
                'city','address','visiable_teacher_count','course_count'
            ]
        ];
        $seekOrg = seek_api::seekOrg($params);
        if(empty($seekOrg->data)) self::retrunData(0, array());

        foreach($seekOrg->data as &$val){
            $val->thumb_big = interface_func::imgUrl($val->thumb_big);
            $val->thumb_med = interface_func::imgUrl($val->thumb_med);
            $val->tacher_count = $val->visiable_teacher_count;
            unset($val->visiable_teacher_count);
        }

        $data = $seekOrg->data[0];
        self::returnData(0, $data);
    }

}
