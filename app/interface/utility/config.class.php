<?php

class interface_utility_config {

    /**
     * @desc 快速进入课程分类卡片
     * @param int $orgId
     * @param int $ownerId
     * @param string $u ios/android
     * @return array
     */
    public static function orgQuickType($orgId, $ownerId, $u){
        $data = [];

        $result = org_api::getOrgCateInfo($orgId, $ownerId);
        if(empty($result->data)) return $data;
        
        $http = interface_func::httpHeader().':';
        $tmp = [
            '6' => $http.utility_cdn::img("assets_v2/interface/yunke/img/wo{$u}/01.png"),
            '7' => $http.utility_cdn::img("assets_v2/interface/yunke/img/wo{$u}/02.png"),
            '8' => $http.utility_cdn::img("assets_v2/interface/yunke/img/wo{$u}/03.png"),
            '9' => $http.utility_cdn::img("assets_v2/interface/yunke/img/wo{$u}/04.png"),
        ];

        foreach ($result->data as $val){
            $data[] = [
                'id'   => $val->pk_cate,
                'name' => $val->name,
                'img'  => !empty($tmp[$val->pk_cate]) ? $tmp[$val->pk_cate] : ''
            ];
        }

        return $data;
    }

    /**
     * @desc  个性化设置
     */
    public static function get(){}
}
