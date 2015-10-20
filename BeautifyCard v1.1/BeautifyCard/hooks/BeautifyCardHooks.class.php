<?php

class BeautifyCardHooks extends Hooks {
    protected $uid = 0;
    protected $mid = 0;
    
    public function beautifyCardConfig(){
        $config = model('AddonData')->lget('beautifyCard');        
        $this->assign('config',$config);
        $this->display('admin_config');
    }
    
    public function saveBeautifyCardConfig(){
        $data = array();
        $data['showMedals'] = $_REQUEST['showMedals']?1:0;
        $data['isSync'] = $_REQUEST['isSync']?1:0;
        $data['color'] = $_REQUEST['color']?$_REQUEST['color']:2;
        
        $_POST && $res = model('AddonData')->lput('beautifyCard',$data);
        if($res){
            $this->assign('jumpUrl',Addons::adminPage('beautifyCardConfig'));
        } else {
            $this->error();
        }
    }
    
    private function checkAvailability(){
        if (APP_NAME == "public" && MODULE_NAME == "Index" && ACTION_NAME == "showFaceCard"){
            return true;
        } else {
            return false;
        }
    }
    
    private function getBeautifyCenter($uid){
        $bc = D('user_space_style')->where('uid = '.$uid)->find();
        return $bc;
    }
    
    public function core_display_tpl(){
        if (!$this->checkAvailability()) return;
        
        $this->uid = $_REQUEST['uid'];
        $this->mid = $GLOBALS['ts']['mid'];
        
        if (empty($_REQUEST['uid'])){
            exit(L('PUBLIC_WRONG_USER_INFO'));
        }
        $data['remarkHash'] = model('Follow')->getRemarkHash($GLOBALS['ts']['mid']);
        $data['follow_group_status'] = model('FollowGroup')->getGroupStatus($GLOBALS['ts']['mid'], $GLOBALS['ts']['uid']);
        $uid = intval($_REQUEST['uid']);
        $data['userInfo'] = model('User')->getUserInfo($uid);
        $data['userInfo']['groupData'] = model('UserGroupLink')->getUserGroupData($uid);
        $data['user_tag'] = model('Tag')->setAppName('User')->setAppTable('user')->getAppTags($uid);
        $data['union_state'] = model('Union')->getUnionState($this->mid, $uid);
        $data['follow_state'] = model('Follow')->getFollowState($this->mid, $uid);
        $department = model('Department')->getAllHash();
        $data['department'] = isset($department[$data['userInfo']['department_id']]) ? $department[$data['userInfo']['department_id']] : "";
        $count = model('UserData')->getUserData($uid);
        
        if (empty($count)){
            $count = array(
                    'following_count' => 0,
                    'follower_count' => 0,
                    'feed_count' => 0,
                    'favorite_count' => 0,
                    'unread_atme' => 0,
                    'weibo_count' => 0
            );
        }
        
        $data['count_info'] = $count;
        
        $profileSetting = D('UserProfileSetting')->where('type=2')->getHashList('field_id');
        $profile = model('UserProfile')->getUserProfile($uid);
        $data['profile'] = array();
        
        foreach ($profile as $key => $value){
            if (isset($profileSetting[$key])){
                $data['profile'][$profileSetting[$key]['field_key']] = array ('name'=>$profileSetting[$key]['field_name'],'value'=>$value['field_data']);
            }
        }
        
        if ($this->uid != $this->mid){
            $data['UserPrivacy'] = model('UserPrivacy')->getPrivacy($this->mid,$this->uid);
        }
        $verify = D('user_verified')->where('verified = 1 AND uid = '.$uid)->find();
        if($verify){
            $data['verifyInfo'] = $verify['info'];
        }
        $data['beautifyCenter'] = $this->getBeautifyCenter($this->uid);
        $data['config'] = $config?$config:model('AddonData')->lget('beautifyCard');
        $data['uid'] = $this->uid;
        $template = dirname(dirname(__FILE__)).'/html/showFaceCard.html';
        echo fetch($template, $data, $param['charset'], $param['contentType']);
        exit;
    }
}
?>