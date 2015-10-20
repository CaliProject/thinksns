<?php
class BeautifyCardAddons extends NormalAddons {
    protected $version = "1.0";
    protected $author = "Cali";
    protected $info = "美化名片的插件，若想开启自定义名片背景请结合 BeautifyCenter 插件使用，否则将无效";
    protected $pluginName = "名片美化";
    protected $tsVersion = "4.0";
    protected $site = "http://ts.calicastle.com";
    
    public function getHooksInfo(){
        $hooks['list'] = array('BeautifyCardHooks');
        return $hooks;
    }
    
    public function adminMenu(){
        return array('beautifyCardConfig'=>'名片美化设置');
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