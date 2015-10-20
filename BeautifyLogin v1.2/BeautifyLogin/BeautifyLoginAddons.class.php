<?php

class BeautifyLoginAddons extends NormalAddons {
    protected $pluginName = '登录美化';
    protected $author = 'Cali';
    protected $site = 'http://ts.calicastle.com';
    protected $info = '美化登录界面，多种模板可选';
    protected $version = '1.2';
    protected $tsVersion = '4.0';
    
    public function getHooksInfo(){
        $hooks['list'] = array('BeautifyLoginHooks');
        return $hooks;
    }
    
    public function adminMenu(){
        return array('beautifyLoginConfig'=>'登录美化设置');
    }
    
    public function start(){
        return true;
    }
    
    public function install(){
        return true;
    }
    
    public function uninstall(){
        return true;
    }
}