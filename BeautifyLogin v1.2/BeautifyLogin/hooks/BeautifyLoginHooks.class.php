<?php

class BeautifyLoginHooks extends Hooks {
    
    private function checkAvailability(){
        if (APP_NAME == 'public' && MODULE_NAME == 'Passport' && ACTION_NAME == 'login'){
            return true;
        } else if (APP_NAME == 'public' && MODULE_NAME == 'Passport' && ACTION_NAME == 'index') {
            return true;
        } else {
            return false;
        }
    }
    
    public function core_display_tpl(){
        if (!$this->checkAvailability()) return;
        
        $config = $config?$config:model('AddonData')->lget('beautifyLogin');
        if (!$config['template']) $config['template'] = 1;
        if (!$config['color']) $config['color'] = "FFFFFF";
        switch($config['template']){
            case 1:
                // By Zue
                $templateFile = dirname(dirname(__FILE__)).'/html/zue/login.html';
                break;
            case 2:
                // By Cali
                $templateFile = dirname(dirname(__FILE__)).'/html/cali/login.html';
                break;
        }
        $siteConf = model( 'Xdata' )->get('admin_Config:site');
        $config['login_bg'] = getImageUrlByAttachId($siteConf['login_bg']);

        $config['_title'] = $siteConf['site_slogan'];
        $config['_keywords'] = $siteConf['site_header_keywords'];
        $config['_description'] = $siteConf['site_header_description'];
        
        $config['site']['site_slogan'] = $siteConf['site_slogan'];
        $config['site']['site_name'] = $siteConf['site_name'];
        $config['site']['logo'] = getSiteLogo($siteConf['site_logo']);
        $config['site']['sys_version'] = $siteConf['sys_version'];
        // 获取当前Js语言包
        $this->langJsList = setLangJavsScript();
        $config['langJsList'] = $this->langJsList;
        echo fetch($templateFile, $config, $param['charset'], $param['contentType']);
        exit;
    }
    
    public function saveBeautifyLoginConfig(){
        $data = array();
        $data['template'] = $_POST['template']?intval($_POST['template']):1;
        $data['color'] = $_POST['color']?$_POST['color']:"FFFFFF";
        $res = model('AddonData')->lput('beautifyLogin', $data);
        if ($res){
            $this->assign('jumpUrl',Addons::adminPage('beautifyLoginConfig'));
        } else {
            $this->error();
        }
    }
    
    public function beautifyLoginConfig(){
        $config = model('AddonData')->lget('beautifyLogin');
        $config['template'] = $config['template']?$config['template']:1;
        $config['color'] = $config['color']?$config['color']:"FFFFFF";
        $this->assign('config',$config);
        $this->display('admin_config');
    }
}