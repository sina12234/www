<?php
class interface_config extends interface_base{
    /**
     * 个性化定制:地区列表
     */
    public function pageGetRegion(){
        $ipInfo = utility_ip::info(utility_ip::realIp());
        $region = SConfig::getConfig(ROOT_CONFIG."/app.region.conf",'default');

        $data = $region->reg;
        $flag = 1;
        foreach ($data as &$val){
            if(!empty($ipInfo->area_name) && $ipInfo->area_name == [$val->name]){
                $val->checked = 1;
                $flag = 0;
            }else{
                $val->checked = 0;
            }
        }
        $data[0]->id   = 0;
        $data[0]->name = '全国';
        $data[0]->checked = !empty($flag) ? 1 : 0;

        return $this->setData($data);
    }

    /**
     * 个性化定制:学习阶段
     */
    public function pageInterest(){
        $grade = SConfig::getConfig(ROOT_CONFIG."/app.grade.conf",'default');

        foreach ($grade->menu as $val){
            $data[] = [
                'id'   => 1,
                'name' => '学前/升学',
                'data' => $val
            ];
        }

        return $this->setData($data);
    }

    /**
     * 当前地区
     */
    public function pageGetipinfo(){
        $ipInfo = utility_ip::info(utility_ip::realIp());
        $region = SConfig::getConfig(ROOT_CONFIG."/app.region.conf",'default');

        $data = ['address' => '全国','addressId' => 0];
        foreach ($region->reg as $val){
            if(!empty($ipInfo->area_name) && $ipInfo->area_name == $val->name){
                $data = [
                    'address'   => $val->name,
                    'addressId' => $val->id
                ];
            }
        }

        return $this->setData($data);
    }
}
