<?php

class BeautifyIndexHooks extends Hooks {
    protected $mid = 0;

    public function body_bg(){
        $data = array();
        $data['beautifyCenter'] = $this->getUserHomeStyle();
        echo "<style>#weiba-top .app-tab-menu li a{padding: 10px;border-radius: 8px;-webkit-order-radius: 8px;-moz-order-radius: 8px}.line-b-animate,.right-wrap,.diy-share,.post-circle,.weibaList,.post-type,.find-type,.mod-user dl,.channel-box,#search-boxs,.feed-nav,.weiba-info,.weiba-box,.feed_list,.channel-banner,#channel_nav,.people-list-l,.people-list-r,.channel-list dl,.flashNews,.feed_lists a.notes{border-radius: 8px;-webkit-border-radius: 8px;-moz-border-radius: 8px;}.sign-in,.sign-in-h{border-top-right-radius: 8px;-webkit-border-top-right-radius: 8px;-moz-border-top-right-radius: 8px}a.top_stick:link, a.top_stick:visited{background: url(".SITE_URL."/addons/plugin/BeautifyIndex/image/backtotop.png) no-repeat 0 0; width: 35px; height: 65px;transition: background ease-in-out .3s;-webkit-transition: background ease-in-out .3s;-moz-transition: background ease-in-out .3s;-ms-transition: background ease-in-out .3s}a.top_stick:hover{background-position: 0px -65px}.diy-share .input_tips{transition: box-shadow ease-in .2s;-webkit-transition: box-shadow ease-in .2s;-moz-transition: box-shadow ease-in .2s}.diy-share .input_tips:focus{box-shadow:0 0 5px rgba(50,50,0,0.5)}.post-type{margin-bottom: 10px}.post-circle-bg{border-top-left-radius: 8px;border-top-right-radius:8px;-webkit-border-top-left-radius: 8px;-webkit-border-top-right-radius:8px;-moz-border-top-left-radius: 8px;-moz-border-top-right-radius:8px;}.feed_comment{border-bottom-left-radius: 8px;border-bottom-right-radius:8px;-webkit-border-bottom-left-radius: 8px;-webkit-border-bottom-right-radius:8px;-moz-border-bottom-left-radius: 8px;-moz-border-bottom-right-radius:8px}.mod-user img{border-radius:8px 8px 0 0;-webkit-border-radius:8px 8px 0 0;-moz-border-radius:8px 8px 0 0;}.channel-list .box{border-radius:0 0 8px 8px;-webkit-border-radius:0 0 8px 8px;-moz-border-radius:0 0 8px 8px}.channel-neworhot{background: rgba(255, 255, 255, 0.7);padding: 20px 0;border-radius: 8px;-webkit-border-radius: 8px;-moz-border-radius: 8px}.app-title{padding: 20px;border-radius: 8px;background: rgba(255, 255, 255, 0.8);margin-bottom: 20px}#asiddsade{background:#FFF}.feed_list{margin: 10px 0}.feed_lists a.notes{padding-bottom:20px !important}</style><script type='text/javascript'>$('#body-bg').attr('style','".$data['beautifyCenter']['full_background']."')</script>";
    }
    
    private function getUserHomeStyle() {
        $this->mid = $GLOBALS['ts']['mid'];
        $bc = D('user_space_style')->where('uid = '.$this->mid)->find();
        return $bc;
    }
}