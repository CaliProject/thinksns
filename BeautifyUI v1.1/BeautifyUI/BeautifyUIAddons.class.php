<?php

class BeautifyUIAddons extends SimpleAddons {
    protected $version = '1.0';
    protected $author = 'Cali';
    protected $site = 'http://ts.calicastle.com';
    protected $info = '全站 UI 美化样式，更改前台样式就是这么简单~, 妈妈再也不用担心 TS 升级啦';
    protected $pluginName = 'UI 美化';
    protected $tsVersion = '4.0';
    
    public function getHooksInfo(){        
        $this->apply("public_head","insertCss");
    }
    
    public function insertCss(){
        $data = model('AddonData')->lget('beautifyUI');
        if ($data['diy']==1){
            echo "<style>{$data['diy-css']}</style>";
        } else {
            $cssCode = $this->getCss($data);
            echo $cssCode;
        }
    }
    
    public function getCss($data){
        $css = "<style>
#header .header-wrap {
    box-shadow: 0 1px 15px rgba(0, 0, 0, 0.4); 
    background: #{$data['top_nav_bg']};
    border: none
}
/*
* 顶部『选中后』的导航菜单字体色与下边界
*/
#header .head-bd .nav .current a.app{
    color: #{$data['top_nav_a_current_color']};
    border-bottom: #{$data['top_nav_a_current_color']} 4px solid
}
/*
* 顶部导航菜单其他字体色
*/
#header .head-bd .nav li a {
    color: #{$data['top_nav_a_color']}
}
/*
* 顶部导航菜单的鼠标悬浮hover效果
*/
#header .head-bd .nav li a:hover {
    border-bottom: #{$data['top_nav_a_hover_color']} 4px solid
}
/*
* 顶部菜单右侧的图标去除分割线
*/
#header .head-bd .person ul li {
    border:none
} 

/*
* 页底背景色+字体色
*/
.footer-wrap {
    color: #{$data['bottom_nav_color']};
    background: #{$data['bottom_nav_bg']}
}
/*
* 页底导航菜单字体色
*/
.login-footer .foot a {
    color: #{$data['bottom_nav_a_color']}
}
/*
* 页底微博关注去边框+背景色
*/
.footer .attend-official dt a {
     background: #{$data['bottom_nav_weibo_bg']};
     border: none
}
/*
* 页底二维码去边框
*/
.footer .attend-official dd {
    border: none
}
/*
* 页顶搜索框样式
*/
#header .head-bd .search {
    background: transparent;
    border: none
}
.head-bd #mod-search .s-txt {
    background: #{$data['top_nav_search_bg']};
    color: #{$data['top_nav_search_color']};
    padding: 7px 18px 8px;
    border: none;
    border-radius: 20px;
    -webkit-border-radius: 20px;
    -moz-border-radius: 20px;
    -ms-border-radius: 20px;
    transition: all ease-in-out .3s;
    -webkit-transition: all ease-in-out .3s;
    -moz-transition: all ease-in-out .3s;
}
/*
* 页顶搜索框焦点状态
*/
.head-bd #mod-search .s-txt:focus {
    box-shadow: 0 0 15px 3px #{$data['top_nav_search_focus_color']}
}
</style>";
        return $css;
    }

    public function adminMenu(){
        return array('adminConfig'=>'UI 美化设置');
    }
    
    public function adminConfig(){
        $config = model('AddonData')->lget('beautifyUI');
        $this->assign('config',$config);
        $this->display('config');
    }
    
    public function saveBeautifyUIConfig($params){
        $params['diy'] = $_POST['diy']?intval($_POST['diy']):0;
        $params['diy-css'] = $_POST['diy-css']?$_POST['diy-css']:'';
        $params['top_nav_bg'] = $_POST['top_nav_bg']?$_POST['top_nav_bg']:'313131';
        $params['top_nav_a_color'] = $_POST['top_nav_a_color']?$_POST['top_nav_a_color']:'EEEEEE';
        $params['top_nav_a_current_color'] = $_POST['top_nav_a_current_color']?$_POST['top_nav_a_current_color']:'EEEE22';
        $params['top_nav_a_hover_color'] = $_POST['top_nav_a_hover_color']?$_POST['top_nav_a_hover_color']:'cccccc';
        $params['bottom_nav_bg'] = $_POST['bottom_nav_bg']?$_POST['bottom_nav_bg']:'313131';
        $params['bottom_nav_color'] = $_POST['bottom_nav_color']?$_POST['bottom_nav_color']:'eeeeee';
        $params['bottom_nav_a_color'] = $_POST['bottom_nav_a_color']?$_POST['bottom_nav_a_color']:'c2665d';
        $params['bottom_nav_weibo_bg'] = $_POST['bottom_nav_weibo_bg']?$_POST['bottom_nav_weibo_bg']:'c2c0af';
        $params['top_nav_search_bg'] = $_POST['top_nav_search_bg']?$_POST['top_nav_search_bg']:'313131';
        $params['top_nav_search_color'] = $_POST['top_nav_search_color']?$_POST['top_nav_search_color']:'ffffff';
        $params['top_nav_search_focus_color'] = $_POST['top_nav_search_focus_color']?$_POST['top_nav_search_focus_color']:'5d5d5d';
        $res = model('AddonData')->lput('beautifyUI',$params);
        return $res;
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