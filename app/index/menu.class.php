<?php
class index_menu{
    var $user;
	private $domain;
    public function __construct(){

		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
		//$this->assign('domain', $this->domain);

        $this->user = user_api::loginedUser();
        if(empty($this->user)){
            $this->redirect("/index.main.login");
        }
    }

    public static $instance;
    
    public static function instance(){
        if( ! isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function create_main_menu($cfg){
        if (empty($cfg)) {
            return '';  
        }
        $tpl = '';
        foreach ($cfg as $v) {
            $class="";
            $down="";
            if (isset($v['active']) && $v['active']) {
                $class='class="curr"';
                $down='<span class="arrow-down"></span>';
            }
            $tpl.='<li '.$class.'><a href="'.$v['url'].'"><span class="icon help-arrow-icon"></span><span class="t-fs c-fl"></span>';
            $tpl.='<span class="';
            if (!empty($v['icon_class'])) {
                $tpl .= $v['icon_class'];
            } else {
                $tpl .= 'icon-book';
            }
            $tpl .= '"></span>'.$v['title'];
            $tpl .= $down.'</a>';
            if (isset($v['submenu']) && is_array($v['submenu'])) {
                $tpl .= $this->create_sub_menu($v['submenu']);          
            }
            $tpl .= ' </li>';
        }
        return  $tpl;
    }
    
    private function create_sub_menu($submenu){
        if (empty($submenu)) {
            return '';  
        }
        $tpl = '<ul class="submenu">';
        foreach ($submenu as $v) {
            if (isset($v['active']) && $v['active']) {
               $class='active col-xs-6 col-sm-12'; 
            }else{
               $class='col-xs-6 col-sm-12'; 
            }
            $tpl .= '<li class="'.$class.'"><a href="'.$v['url'].'">';
            $tpl .= '<i class="icon-double-angle-right"></i> '.$v['title'].' </a></li>';
            if (isset($v['submenu']) && is_array($v['submenu'])) {
                $tpl .= $this->create_sub_menu($v['submenu']);          
            }
        }
        $tpl .= '</ul>';
        return $tpl;
    }

    public function create($type){
        if($type=='teacher'){
            $teacher = SConfig::getConfig(ROOT_CONFIG."/index.menu.conf","teacher");
            $menu=$teacher->menu;
        }else if($type=='student'){
            $student = SConfig::getConfig(ROOT_CONFIG."/index.menu.conf","student");
            $menu=$student->menu;
        }else{
            $default = SConfig::getConfig(ROOT_CONFIG."/index.menu.conf","default");
            $menu=$default->menu;
        }
        //$menu=array_merge($menu,$default->menu);
        if (empty($menu) || empty(SlightPHP::$zone) || empty(SlightPHP::$page)
          || empty(SlightPHP::$entry)) {
          return false;
        }
        $menu = json_decode(json_encode($menu), true);

       if (!isset($menu[0])) {
          $menu = array($menu);
        }
        $list = array();
        $uri = '/'.SlightPHP::$zone.'/'.SlightPHP::$page.'/'.SlightPHP::$entry;
        if ($this->format_cfg($menu) && $this->search_active_menu($menu, $uri, $list)) {
            $this->reset_active_menu($menu);
            $this->set_active_menu($menu, $list);
        }
        return $this->create_main_menu($menu);
    }

    private function format_cfg(&$cfg){
        if (empty($cfg)) {
            return false;   
        }
        foreach ($cfg as $k => $v) {
            if (isset($v['submenu']) &&
                !empty($v['submenu'])) {
                if (!isset($v['submenu'][0])) {
                    $cfg[$k]['submenu'] = array($v['submenu']); 
                }
                $this->format_cfg($cfg[$k]['submenu']);
            }
        }
        return true;
    }

    private function reset_active_menu(&$menu) {
        foreach ($menu as $k => $m) {
            if (isset($menu[$k]['active'])) {
                $menu[$k]['active'] = false;
            }
            if(!empty($menu[$k]['submenu'])) {
                $this->reset_active_menu($menu[$k]['submenu']);
            }
        }
    }

    private function set_active_menu(&$menu, &$list) {
        $menu[$list['current']]['active'] = true;
        if(!empty($list['next']) && !empty($menu[$list['current']]['submenu'])) {
            $this->set_active_menu($menu[$list['current']]['submenu'], $list['next']);
        }
    }

    private function search_active_menu(&$menu, $uri, &$list) {
        if (empty($menu) || empty($uri)) {
           return false;
        }
        foreach ($menu as $k => $m) {
            $is_find = false;
            /*if(!empty($m['route'])){
                if (is_array($m['route'])) {
                    $is_find = in_array($uri, $m['route']);
                } else {
                    $is_find = ($m['route'] == $uri);
                }
            } else {
                $is_find = ($m['url'] == $uri);
            }*/
            
            $url=str_replace('.','/',$m['url']);
            $arr1=explode('/',$url);
            $arr2=explode('/',$uri);
            if ($arr1[3]==$arr2[3]) {
                $list['current'] = $k;
                $list['next'] = array();
                return true;
            }
            if(!empty($m['submenu'])) {
                $list['current'] = $k;
                $list['next'] = array();
                if ($this->search_active_menu($menu[$k]['submenu'], $uri, $list['next']) === true) {
                   return true;
                }
            }
        }
    }

}

