<?php

class interface_utility_banner {

    /**
     * @version xiaowo 1.0
     * @desc  轮播图和专题列表
     * @param int $orgId
     * @param int $width
     * @return array
     */
    public static function orgList($orgId, $width){
        $data = [
            'ad'      => [],
            'special' => []
        ];

        $bannerReg = user_organization::xiaowoOrgList(array('fk_org'=>$orgId));
        if(empty($bannerReg)) return $data;

        foreach($bannerReg as $val){
            if($val->types == 1){
                $data['ad'][] = [
                    'name'   => $val->title,
                    'imgurl' => ($width <= 720) ? interface_func::imgUrl($val->thumb_app) : interface_func::imgUrl($val->thumb_ipad),
                    'url'    => $val->url
                ];
            }elseif($val->types == 2){
                $data['special'][] = [
                    'specialId'   => $val->pk_banner,
                    'specialName' => $val->title,
                    'specialImg'  => ($width <= 720) ? interface_func::imgUrl($val->thumb_app) : interface_func::imgUrl($val->thumb_ipad),
                    'specialUlr'  => $val->url
                ];
            }
        }

        return $data;
    }
}
