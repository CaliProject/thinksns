<?php

class BeautifyIndexAddons extends NormalAddons {
    protected $version = '1.0';
    protected $site = 'http://ts.calicastle.com';
    protected $author = 'Cali';
    protected $info = '切换全站背景与站点美化';
    protected $tsVersion = '4.0';
    protected $pluginName = '站点美化';
    
    public function getHooksInfo(){
        $hooks['list'] = array('BeautifyIndexHooks');
        return $hooks;
    }
    
    public function start(){
        return true;
    }
    
    public function adminMenu(){
        return false;
    }
    
    public function install(){
        return true;
    }
    
    public function uninstall(){
        return true;
    }
}