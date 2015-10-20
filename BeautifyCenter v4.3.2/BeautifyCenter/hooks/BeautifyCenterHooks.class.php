<?php
class BeautifyCenterHooks extends Hooks{
    public function beautifyCenterConfig(){
        $config = model('AddonData')->lget('beautifyCenter');
        $config['position'] = !empty($config['position'])?$config['position']:2;
        $config['template'] = !empty($config['template'])?$config['template']:1;
        $config['sync_bg'] = empty($config['sync_bg'])?$config['sync_bg']:1;
        $config['consume_credit'] = empty($config['consume_credit'])?$config['consume_credit']:1;
        $config['consume_quantity'] = !empty($config['consume_quantity'])?$config['consume_quantity']:50;
        
        $this->assign('config',$config);
        $this->display('admin_config');
    }
    
    public function saveBeautifyCenterConfig(){
        $data = array();
        $data['position'] = $_POST['position']?$_POST['position']:2;
        $data['template'] = $_POST['template']?$_POST['template']:1;
        $data['sync_bg'] = $_POST['sync_bg']?1:0;
        $data['consume_credit'] = $_POST['consume_credit']=="on"?1:0;
        $data['consume_quantity'] = $_POST['consume_quantity']?$_POST['consume_quantity']:50;
        
        $_POST && $res = model('AddonData')->lput('beautifyCenter',$data);
        if ($res){
            $this->assign('jumpUrl',Addons::adminPage('beautifyCenterConfig'));
        } else {
            $this->error();
        }
    }
	private function checkPage(){
		if(APP_NAME == 'public' && MODULE_NAME == 'Profile' && ACTION_NAME =='index'){
		return true;
		}else if(APP_NAME == 'public' && MODULE_NAME == 'Profile' && ACTION_NAME =='data'){
			return true;
		}else if(APP_NAME == 'public' && MODULE_NAME == 'Profile' && ACTION_NAME =='following'){
			return true;
		}else if(APP_NAME == 'public' && MODULE_NAME == 'Profile' && ACTION_NAME =='follower'){
			return true;
		}else if(APP_NAME == 'public' && MODULE_NAME == 'Profile' && ACTION_NAME =='appList'){
			return true;
		}else if(APP_NAME == 'public' && MODULE_NAME == 'Profile' && ACTION_NAME =='feed'){
			return true;
		}else{
			return false;
		}
	}

	public function core_display_tpl(){
		if(!$this->checkPage()){
			return;
		}		
		$this->mid = $GLOBALS['ts']['mid'];
		$this->uid = $this->getUserUid();
		$user_info = model('User')->getUserInfo($this->uid);
		// 添加积分
		model ( 'Credit' )->setUserCredit ( $this->uid, 'space_access' );
		// 获取头部相关信息
		$data = $this->_top();
		// 获取用户_tab_menu信息
		$data['appArr'] = $this->_tab_menu();
		// 用户uid
		$data['uid'] = $this->uid;
		// 用户信息
		$data['user_info'][$this->uid] = $user_info;
		$data['initNums'] = model('Xdata')->getConfig('weibo_nums','feed');

		// 用户为空，则跳转用户不存在
		if (empty ( $user_info )) {
			$this->error ( L ( 'PUBLIC_USER_NOEXIST' ) );
		}
		// 判断隐私设置
		$userPrivacy = $this->privacy ( $this->uid );
		if ($userPrivacy ['space'] !== 1) {
			// 右边栏信息_sidebar
			$data['sidebar'] = $this->_sidebar();
			// 加载微博筛选信息
			$d ['feed_type'] = t ( $_REQUEST ['feed_type'] ) ? t ( $_REQUEST ['feed_type'] ) : '';
			$d ['feed_key'] = t ( $_REQUEST ['feed_key'] ) ? t ( $_REQUEST ['feed_key'] ) : '';
			$data['feed_type'] = $d['feed_type'];
			$data['feed_key'] = $d['feed_key'];
		} else {
			$data['sidebar']['user_info'] = $this->_assignUserInfo ( $this->uid );
		}
		$data['userPrivacy'] = $userPrivacy;
		$data['mid'] = $this->mid;
		$data['user'] = model('User')->getUserInfo( $this->mid );
		$data['site_top_nav'] = model('Navi')->getTopNav();
		$data['site_nav_apps'] = $GLOBALS['ts']['site_nav_apps'];
        $data['site_bottom_nav'] = model('Navi')->getBottomNav();
        $data['site_bottom_child_nav'] = model('Navi')->getBottomChildNav($GLOBALS['ts']['site_bottom_nav']);
		// seo
		$siteConf = model( 'Xdata' )->get('admin_Config:site');
		
		$seo = model ( 'Xdata' )->get ( "admin_Config:seo_user_profile" );
		$replace ['uname'] = $user_info ['uname'];
		if ($feed_id = model ( 'Feed' )->where ( 'uid=' . $this->uid )->order ( 'publish_time desc' )->limit ( 1 )->getField ( 'feed_id' )) {
			$replace ['lastFeed'] = D ( 'feed_data' )->where ( 'feed_id=' . $feed_id )->getField ( 'feed_content' );
		}
		$replaces = array_keys ( $replace );
		foreach ( $replaces as &$v ) {
			$v = "{" . $v . "}";
		}
		$seo ['title'] = str_replace ( $replaces, $replace, $seo ['title'] );
		$seo ['keywords'] = str_replace ( $replaces, $replace, $seo ['keywords'] );
		$seo ['des'] = str_replace ( $replaces, $replace, $seo ['des'] );
        $data['_title'] = !empty($seo['title'])?$seo['title']:$siteConf['site_slogan'];
        $data['_keywords'] = !empty($seo['keywords'])?$seo['keywords']:$siteConf['site_header_keywords'];
        $data['_description'] = !empty($seo['des'])?$seo['des']:$siteConf['site_header_description'];
        
        $data['site']['site_slogan'] = $siteConf['site_slogan'];
        $data['site']['site_name'] = $siteConf['site_name'];
        $data['site']['logo'] = getSiteLogo($siteConf['site_logo']);
        $data['site']['sys_version'] = $siteConf['sys_version'];
        // 获取当前Js语言包
        $this->langJsList = setLangJavsScript();
        $data['langJsList'] = $this->langJsList;
        // 显示模板
        if(ACTION_NAME == 'index'){
			$templateFile = dirname(dirname(__FILE__)).'/html/index.html';
		}else if(ACTION_NAME == 'follower'){
			$data['follower_list'] = $this->follower();
			$data['_title'] = $data['follower_list']['user_info'][$this->uid]['uname'].'的粉丝';
			$data['_keywords'] = $data['follower_list']['user_info'][$this->uid]['uname'].'的粉丝';
			$templateFile = dirname(dirname(__FILE__)).'/html/follower.html';
		}else if(ACTION_NAME == 'following'){
			$data['following_list'] = $this->following();
			$data['_title'] = $data['following_list']['user_info'][$this->uid]['uname'].'的关注';
			$data['_keywords'] = $data['following_list']['user_info'][$this->uid]['uname'].'的关注';
			$templateFile = dirname(dirname(__FILE__)).'/html/following.html';
		}else if(ACTION_NAME == 'appList'){
			$data['appList'] = $this->appList();
			$data['_title'] = $data['user_info'][$this->uid]['uname'].'的'.$data['appArr'][$data['appList']['type']];
			$data['_keywords'] = $data['user_info'][$this->uid]['uname'].'的'.$data['appArr'][$data['appList']['type']];
			$templateFile = dirname(dirname(__FILE__)).'/html/appList.html';
		}else if(ACTION_NAME == 'feed'){
			$data['feed'] = $this->feed();
			$data['_title'] = $data['user_info'][$this->uid]['uname'].'的微博';
			$data['_keywords'] = $data['user_info'][$this->uid]['uname'].'的微博';
			$templateFile = dirname(dirname(__FILE__)).'/html/feed.html';
		}else{
			$data['data'] = $this->data();
			$data['_title'] = $data['user_info'][$this->uid]['uname'].'的资料';
			$data['_keywords'] = $data['user_info'][$this->uid]['uname'].'的资料';
			$templateFile = dirname(dirname(__FILE__)).'/html/data.html';
		}
		echo fetch($templateFile, $data, $param['charset'], $param['contentType']);
        exit;
	}

	/**
	 * 个人主页头部数据
	 * 
	 * @return void
	 */
	public function _top() {
		// 获取用户组信息
		$userGroupData = model ( 'UserGroupLink' )->getUserGroupData ( $this->uid );
		// 获取用户积分信息
		$userCredit = model ( 'Credit' )->getUserCredit ( $this->uid );
		// 加载用户关注信息
		if($this->mid != $this->uid){
			$data['follow_state'] = $this->_assignFollowState ( $this->uid );
		}
		// 获取用户统计信息
		$userData = model ( 'UserData' )->getUserData ( $this->uid );
		// 获取个人空间所有封面
		$homeStyle = $this->getHomeStyle();
		// 获取个人空间的封面
		$beautifyCenter = $this->getUserHomeStyle( $this->uid );

		$data['userGroupData'] = $userGroupData;
		$data['beautifyCenter'] = $beautifyCenter;
		$data['homeStyle'] = $homeStyle;
		$data['userData'] = $userData;
		$data['userCredit'] = $userCredit;
        // User preferences
        $data['preferences'] = $config?$config:model('AddonData')->lget('beautifyCenter');
		return $data;
	}

	/**
	 * 个人主页标签导航
	 *
	 * @return void
	 */
	public function _tab_menu() {
		// 取全部APP信息
		$appList = model ( 'App' )->where ( 'status=1' )->field ( 'app_name' )->findAll ();
		foreach ( $appList as $app ) {
			$appName = strtolower ( $app ['app_name'] );
			$className = ucfirst ( $appName );
			
			$dao = D ( $className . 'Protocol', strtolower($className), false );
			if (method_exists ( $dao, 'profileContent' )) {
				$appArr [$appName] = L ( 'PUBLIC_APPNAME_' . $appName );
			}
			unset ( $dao );
		}
		return $appArr;
	}

	/**
     * 获取系统封面
     * @return array
     */
    public function getHomeStyle()
    {
        $dirname = ADDON_PATH.'/plugin/BeautifyCenter/templates/images';
        // 封面名称、地址
        $filepath = ADDON_PATH.'/plugin/BeautifyCenter/Conf/config.php';
        $styleConf = include(ADDON_PATH.'/plugin/BeautifyCenter/Conf/config.php');
        foreach ($styleConf as $key => $value) {
			$data[$key] = array('id'=>$key,'name'=>$value,'thumb_url'=>ADDON_URL.'/plugin/BeautifyCenter/timthumb.php?w=100&h=70&src='.ADDON_URL.'/plugin/BeautifyCenter/templates/images/'.$key.'.jpg');
		}
		return $data;
    }
    /**
     * 获取用户个人空间封面
     * @return array
     */
    public function getUserHomeStyle($uid){
    	$uStyle = D('user_space_style')->where('uid = '.$uid)->find();
    	return $uStyle;
    }
    /**
     * 保存用户个人空间封面
     * @return int
     */
    public function beautifyCenterSave(){
    	$this->uid = $GLOBALS['ts']['mid'];
        // If using custom template
        $custom = $_REQUEST['custom'];
    	// 是否已经存在此用户封面图信息
    	$isSave = D('user_space_style')->where('uid = '.$this->uid)->find();    
        if ($custom == 1){
            $imgLeft = $_REQUEST['cutImgLeft'];
    		$imgTop = $_REQUEST['cutImgTop'];
    		// 截图、调整图片
    		$imageInfo = S('imageInfo');
    		$_FILES = S('imageFileInfo');
    		$srcSavePath = S('imgFilePath');
    		$imageType = S('imageType');
    		// 存储路径
    		$dir_path = 'data/upload/background'.$this->convertUidToPath($this->uid);
		    $savePath = SITE_PATH.'/'.$dir_path.'/userdefined';
            if(!file_exists($savePath)) {
                mkdir($savePath, 0777, true);
            }
            $filename = md5($_FILES['tmp_name'].$this->mid ).'.'.$imageType;
            if (strpos($imageInfo['mime'], 'jpeg') !== false) {  
		        $pic_obj = imagecreatefromjpeg($srcSavePath);  
		    } else if (strpos($imageInfo['mime'], 'gif') !== false) {  
		        $pic_obj = imagecreatefromgif($srcSavePath);  
		    } else if (strpos($imageInfo['mime'], 'png') !== false) {  
		        $pic_obj = imagecreatefrompng($srcSavePath);  
		    } else if (strpos($imageInfo['mime'], 'bmp') !== false) {  
		        $pic_obj = imagecreatefromwbmp($srcSavePath);  
		    }else {  
		        $result['status'] = 0;
                $result['info'] = '不是有效的图片类型';
                exit(json_encode($result));
		    }
		    // 原图宽高
		    $pic_width = imagesx($pic_obj);  
    		$pic_height = imagesy($pic_obj);

            // 封面图宽高 || 截图宽高
            $dst_widht = 980;
            $dst_height = 300;

            // 截图
            if (function_exists("imagecopyresampled")) {  
        		$new_img = imagecreatetruecolor($dst_widht,$dst_height);  
        		imagecopyresampled($new_img, $pic_obj, 0, 0, $imgLeft, $imgTop, $dst_widht, $dst_height, $dst_widht, $dst_height);  
    		} else {  
        		$new_img = imagecreate($dst_widht, $dst_height);  
        		imagecopyresized($new_img, $pic_obj, 0, 0, $imgLeft, $imgTop, $dst_widht, $dst_height, $dst_widht, $dst_height);  
    		}
    		// 生成图片，保存    		
            if($imageType == 'jpg'){
    			imagejpeg($new_img, $savePath.'/'.$filename);
    		}else if($imageType == 'gif'){
    			imagegif($new_img, $savePath.'/'.$filename);
    		}else if($imageType == 'png'){
    			imagepng($new_img, $savePath.'/'.$filename);
    		}else{
    			imagejpeg($new_img, $savePath.'/'.$filename);
    		}
    		
            $config = $config?$config:model('AddonData')->lget('beautifyCenter');
            $isConsume = $config['consume_credit']==1?true:false;
            $credit_amount = $config['consume_quantity'];
            $credit = model('Credit')->getUserCredit($this->uid);
            if ($isConsume){
                if($credit['credit']['score']['value'] > $credit_amount){
                    if(empty($isSave)){
                        $data['uid'] = $this->uid;
                        $data['background'] = 'background-image:url(http://'.$_SERVER['HTTP_HOST'].'/'.$dir_path.'/userdefined/'.$filename.')';
                        $data['full_background'] = stripcslashes($_REQUEST['full']);
                        model ( 'Credit' )->setUserCredit ( $this->uid, array('score'=>-$credit_amount) );
                        $res = D('user_space_style')->add($data);
                        if($res){
                            echo 1;
                        }
                    }else{
                        $options['where'] = 'uid = '.$this->uid;
                        $data['background'] = 'background-image:url(http://'.$_SERVER['HTTP_HOST'].'/'.$dir_path.'/userdefined/'.$filename.')';
                        $data['full_background'] = stripcslashes($_REQUEST['full']);

                        model ( 'Credit' )->setUserCredit ( $this->uid, array('score'=>-$credit_amount) );
                        $res = D('user_space_style')->save($data,$options);
                        if($res){
                            echo 1;
                        }
                    }
                }else{
                    echo 2;
                }
            } else {
                if(empty($isSave)){
                    $data['uid'] = $this->uid;
                    $data['background'] = 'background-image:url(http://'.$_SERVER['HTTP_HOST'].'/'.$dir_path.'/userdefined/'.$filename.')';
                    $data['full_background'] = stripcslashes($_REQUEST['full']);
                    $res = D('user_space_style')->add($data);
                    if($res){
                        echo 1;
                    }
                }else{
                    $options['where'] = 'uid = '.$this->uid;
                    $data['background'] = 'background-image:url(http://'.$_SERVER['HTTP_HOST'].'/'.$dir_path.'/userdefined/'.$filename.')';
                    $data['full_background'] = stripcslashes($_REQUEST['full']);
                    $res = D('user_space_style')->save($data,$options);
                    if($res){
                        echo 1;
                    }
                }
            }
    	}else{// if not custom
            if(empty($isSave)){
                $data['uid'] = $this->uid;
                // $data['background'] = preg_replace('/^"|"$/', '', $_REQUEST['thumb_url']);
                $data['background'] = stripcslashes($_REQUEST['thumb_url']);
                $data['full_background'] = stripcslashes($_REQUEST['full']);
                $res = D('user_space_style')->add($data);
                if($res){
                    echo 1;
                }
            }else{
                $options['where'] = 'uid = '.$this->uid;
                $data['background'] = stripcslashes($_REQUEST['thumb_url']);
                $data['full_background'] = stripcslashes($_REQUEST['full']);
                $res = D('user_space_style')->save($data,$options);
                if($res){
                    echo 1;
                }
            }
        }
    }

    /**
     * 保存临时图片
     * @return void
     */
	public function saveImageTemp()
    {
    	$this->mid = $GLOBALS['ts']['mid'];
        $imageInfo = getimagesize($_FILES['pic']['tmp_name']);
        // 图片信息设置缓存
        if(S('imageInfo') === false){
        	S('imageInfo',$imageInfo);
        }else{
        	$img_data = S('imageInfo');
        	if($img_data){
        		S('imageInfo',null);
        		S('imageInfo',$imageInfo);
        	}
        }
        if(S('imageFileInfo') === false){
        	S('imageFileInfo',$_FILES['pic']);
        }else{
        	$up_data = S('imageFileInfo');
        	if($up_data){
        		// 删除缓存，重新设置
        		S('imageFileInfo',null);
        		S('imageFileInfo',$_FILES['pic']);
        	}
        }
        // 图片大小
        $filesize = abs(filesize($_FILES['pic']['tmp_name']));
        $result = array();
        // 判断图片尺寸
        if($filesize > 1024*1024*2 || $_FILES['pic']['error'] > 0) {
            $result['status'] = 0;
            $result['info'] = '上传文件不能大于2MB';
        } else {
        	// 图片后缀
            $imageType = strtolower(substr($_FILES['pic']['name'],strrpos($_FILES['pic']['name'],'.')+1));
            if($imageType == "jpeg") {
                $imageType ='jpg';
            }
            if(!in_array($imageType,array('jpg','png','gif','bmp'))){
                $result['status'] = 0;
                $result['info'] = '不是有效的图片类型';
                exit(json_encode($result));
            }
            if(S('imageType') === false){
            	S('imageType',$imageType);
            }else{
            	S('imageType',null);
            	S('imageType',$imageType);
            }
            // 文件存储路径
            $dir_path = 'data/upload/background'.$this->convertUidToPath($this->mid);
		    $savePath = SITE_PATH.'/'.$dir_path;
            if(!file_exists($savePath)) {
                mkdir($savePath, 0777, true);
            }
            $filename = md5($_FILES['pic']['tmp_name'].$this->mid ).'.'.$imageType;
            /**
	         *	新增 | 修改
	         */
	        if(intval($imageInfo[0]) < 960 || intval($imageInfo[1]) < 340){
	        	// 目标宽高
	        	if(intval($imageInfo[0]) < 960 && intval($imageInfo[1]) < 340){
	        		$imgWidth = $imageInfo[0]+960;
	        		$scale = $imgWidth/$imageInfo[0];
	        		$imgHeight = (int)($imageInfo[1]*$scale);
	        		if($imgHeight < 340){
	        			$imgHeight = 340;
	        		}
	        	}else if(intval($imageInfo[0]) < 960){
	        		$imgWidth = 960;
		        	$scale = $imgWidth/$imageInfo[0];
		        	$imgHeight = (int)($imageInfo[1]*$scale);
		        	if($imgHeight < 340){
	        			$imgHeight = 340;
	        		}
	        	}else if(intval($imageInfo[1]) < 340){
	        		$imgHeight = 340;
	        		$scale = $imgHeight/$imageInfo[1];
	        		$imgWidth = (int)($imageInfo[0]*$scale);
	        		if($imgHeight < 340){
	        			$imgHeight = 340;
	        		}
	        	}
		        if (strpos($imageInfo['mime'], 'jpeg') !== false) {  
			        $pic_obj = imagecreatefromjpeg($_FILES['pic']['tmp_name']);  
			    } else if (strpos($imageInfo['mime'], 'gif') !== false) {  
			        $pic_obj = imagecreatefromgif($_FILES['pic']['tmp_name']);  
			    } else if (strpos($imageInfo['mime'], 'png') !== false) {  
			        $pic_obj = imagecreatefrompng($_FILES['pic']['tmp_name']);  
			    } else if (strpos($imageInfo['mime'], 'bmp') !== false) {  
			        $pic_obj = imagecreatefromwbmp($_FILES['pic']['tmp_name']);  
			    }else {  
			        $result['status'] = 0;
	                $result['info'] = '不是有效的图片类型';
	                exit(json_encode($result));
			    }	        	
			    if(function_exists("imagecopyresampled")) {  
	        		$dst_img = imagecreatetruecolor($imgWidth,$imgHeight);  
	        		imagecopyresampled($dst_img, $pic_obj, 0, 0, 0, 0, $imgWidth, $imgHeight, $imageInfo[0], $imageInfo[1]);  
	    		}else{  
	        		$dst_img = imagecreate($imgWidth, $imgHeight);  
	        		imagecopyresized($dst_img, $pic_obj, 0, 0, 0, 0, $imgWidth, $imgHeight, $imageInfo[0], $imageInfo[1]);  
	    		}
	    		if($imageType == 'jpg'){
    				imagejpeg($dst_img, $savePath.'/'.$filename);
	    		}else if($imageType == 'gif'){
	    			imagegif($dst_img, $savePath.'/'.$filename);
	    		}else if($imageType == 'png'){
	    			imagepng($dst_img, $savePath.'/'.$filename);
	    		}else{
	    			imagejpeg($dst_img, $savePath.'/'.$filename);
	    		}
	        }else{
	        	$moveUploadRes = move_uploaded_file($_FILES['pic']['tmp_name'], $savePath.'/'.$filename);
	        }
	        /**
	         *	新增 | 修改
	         */
         
            // 文件路径设置缓存(生成新图存放地址)
            if(S('imgFilePath') === false){
            	S('imgFilePath', $savePath.'/'.$filename);
            }else{
            	S('imgFilePath',null);
        		S('imgFilePath', $savePath.'/'.$filename);
            }
            $result['status'] = 1;
            $result['info'] = $dir_path.'/'.$filename;
        }
        exit(json_encode($result));
	}
    
    /**
     * 将用户的UID转换为三级路径
     * @param integer $uid 用户UID
     * @return string 用户路径
     */
    public function convertUidToPath($uid){
        // 静态缓存
        $sc = static_cache('avatar_uidpath_'.$uid);
        if(!empty($sc)) {
            return $sc;
        }
        $md5 = md5($uid);
        $sc = '/'.substr($md5, 0, 2).'/'.substr($md5, 2, 2).'/'.substr($md5, 4, 2);
        static_cache('avatar_uidpath_'.$uid, $sc);

        return $sc;
    }
    
    /**
	 * 批量获取用户uid与一群人fids的彼此关注状态
	 * 
	 * @param array $fids
	 *        	用户uid数组
	 * @return void
	 */
	private function _assignFollowState($fids = null) {
		// 批量获取与当前登录用户之间的关注状态
		$follow_state = model ( 'Follow' )->getFollowStateByFids ( $this->mid, $fids );
		return $follow_state;
	}

	/**
	 * 个人主页右侧
	 * 
	 * @return void
	 */
	public function _sidebar() {
		// 判断用户是否已认证
		$isverify = D ( 'user_verified' )->where ( 'verified=1 AND uid=' . $this->uid )->find ();
		if ($isverify) {
			$data['verifyInfo'] = $isverify ['info'] ;
		}
		// 加载用户标签信息
		$data['user_tag'] = $this->_assignUserTag ( array (
				$this->uid 
		) );
		// 加载关注列表
		$sidebar_following_list = model ( 'Follow' )->getFollowingList ( $this->uid, null, 12 );
		// 加载粉丝列表
		$sidebar_follower_list = model ( 'Follow' )->getFollowerList ( $this->uid, 12);
		// 加载用户信息
		$uids = array (
			$this->uid 
		);
		
		$followingfids = getSubByKey ( $sidebar_following_list ['data'], 'fid' );
		$followingfids && $uids = array_merge ( $uids, $followingfids );
		
		$followerfids = getSubByKey ( $sidebar_follower_list ['data'], 'fid' );
		$followerfids && $uids = array_merge ( $uids, $followerfids );
		
		$data['sidebar_following_list'] = $sidebar_following_list;
		$data['sidebar_follower_list'] = $sidebar_follower_list;

		$data['user_info'] = $this->_assignUserInfo ( $uids );
		return $data;
	}

	/**
	 * 根据指定应用和表获取指定用户的标签
	 * 
	 * @param
	 *        	array uids 用户uid数组
	 * @return void
	 */
	private function _assignUserTag($uids) {
		$user_tag = model ( 'Tag' )->setAppName ( 'User' )->setAppTable ( 'user' )->getAppTags ( $uids );
		return $user_tag ;
	}

	/**
	 * 批量获取用户的相关信息加载
	 * 
	 * @param string|array $uids
	 *        	用户ID
	 */
	private function _assignUserInfo($uids) {
		! is_array ( $uids ) && $uids = explode ( ',', $uids );
		$user_info = model ( 'User' )->getUserInfoByUids ( $uids );
		return $user_info;
	}
	/**
	 * 隐私设置
	 */
	public function privacy($uid) {
		if ($this->mid != $uid) {
			$privacy = model ( 'UserPrivacy' )->getPrivacy ( $this->mid, $uid );
			return $privacy;
		} else {
			return true;
		}
	}
	/**
	 * 获取用户uid
	 */
	public function getUserUid(){
		// 短域名判断
		if (! isset ( $_GET ['uid'] ) || empty ( $_GET ['uid'] )) {
			$this->uid = $GLOBALS['ts']['mid'];
		} elseif (is_numeric ( $_GET ['uid'] )) {
			$this->uid = intval ( $_GET ['uid'] );
		} else {
			$map ['domain'] = t ( $_GET ['uid'] );
			$this->uid = model ( 'User' )->where ( $map )->getField ( 'uid' );
		}
		return $this->uid;
	}
	/**
	 * 获取用户关注列表
	 * 
	 * @return void
	 */
	public function following() {
		$this->uid = $this->getUserUid();
		// 获取用户信息
		$user_info = model ( 'User' )->getUserInfo ( $this->uid );
		// 用户为空，则跳转用户不存在
		if (empty ( $user_info )) {
			$this->error ( L ( 'PUBLIC_USER_NOEXIST' ) );
		}
		// 判断隐私设置
		$data['userPrivacy'] = $this->privacy ( $this->uid );
		if ($data['userPrivacy'] ['space'] !== 1) {
			$data['following_list'] = model ( 'Follow' )->getFollowingList ( $this->uid, t ( $_GET ['gid'] ), 20 );
			$fids = getSubByKey ( $data['following_list']['data'], 'fid' );
			if ($fids) {
				$uids = array_merge ( $fids, array (
						$this->uid 
				) );
			} else {
				$uids = array (
						$this->uid 
				);
			}
			// 获取用户组信息
			$data['userGroupData'] = model ( 'UserGroupLink' )->getUserGroupData ( $uids );
			$data['follow_state'] = $this->_assignFollowState ( $uids );
			$data['user_info'] = $this->_assignUserInfo ( $uids );
			$data['UserProfile'] = $this->_assignUserProfile ( $uids );
			$data['user_tag'] = $this->_assignUserTag ( $uids );
			$data['user_count'] = $this->_assignUserCount ( $fids );
			// 关注分组
			if($this->mid == $this->uid){
				$data['follow_group'] = $this->_assignFollowGroup ( $fids );
			}
			return $data;
		} else {
			return $this->_assignUserInfo ( $this->uid );
		}
	}
	
	/**
	 * 获取用户粉丝列表
	 * 
	 * @return void
	 */
	public function follower() {
		$this->uid = $this->getUserUid();
		// 获取用户信息
		$user_info = model ( 'User' )->getUserInfo ( $this->uid );
		// 用户为空，则跳转用户不存在
		if (empty ( $user_info )) {
			$this->error ( L ( 'PUBLIC_USER_NOEXIST' ) );
		}
		// 判断隐私设置
		$data['userPrivacy'] = $this->privacy ( $this->uid );
		if ($data['userPrivacy'] ['space'] !== 1) {
			$data['follower_list'] = model ( 'Follow' )->getFollowerList ( $this->uid, 20 );
			$fids = getSubByKey ( $data['follower_list']['data'], 'fid' );
			if ($fids) {
				$uids = array_merge ( $fids, array (
						$this->uid 
				) );
			} else {
				$uids = array (
						$this->uid 
				);
			}
			// 获取用户用户组信息
			$data['userGroupData'] = model ( 'UserGroupLink' )->getUserGroupData ( $uids );
			$data['follow_state'] = $this->_assignFollowState ( $uids );
			$data['user_info'] = $this->_assignUserInfo ( $uids );
			$data['UserProfile'] = $this->_assignUserProfile ( $uids );
			$data['user_tag'] = $this->_assignUserTag ( $uids );
			$data['user_count'] = $this->_assignUserCount ( $fids );
			// 更新查看粉丝时间
			if ($this->uid == $this->mid) {
				$t = time () - intval ( $GLOBALS ['ts'] ['_userData'] ['view_follower_time'] ); // 避免服务器时间不一致
				model ( 'UserData' )->setUid ( $this->mid )->updateKey ( 'view_follower_time', $t, true );
			}
			return $data;
		} else {
			return $this->_assignUserInfo ( $this->uid );
		}
	}

	/**
	 * 获取用户的档案信息和资料配置信息
	 * 
	 * @param
	 *        	mix uids 用户uid
	 * @return void
	 */
	private function _assignUserProfile($uids) {
		$data ['user_profile'] = model ( 'UserProfile' )->getUserProfileByUids ( $uids );
		$data ['user_profile_setting'] = model ( 'UserProfile' )->getUserProfileSetting ( array (
				'visiable' => 1 
		) );
		// 用户选择处理 uid->uname
		foreach ( $data ['user_profile_setting'] as $k => $v ) {
			if ($v ['form_type'] == 'selectUser') {
				$field_ids [] = $v ['field_id'];
			}
			if ($v ['form_type'] == 'selectDepart') {
				$field_departs [] = $v ['field_id'];
			}
		}
		foreach ( $data ['user_profile'] as $ku => &$uprofile ) {
			foreach ( $uprofile as $key => $val ) {
				if (in_array ( $val ['field_id'], $field_ids )) {
					$user_info = model ( 'User' )->getUserInfo ( $val ['field_data'] );
					$uprofile [$key] ['field_data'] = $user_info ['uname'];
				}
				if (in_array ( $val ['field_id'], $field_departs )) {
					$depart_info = model ( 'Department' )->getDepartment ( $val ['field_data'] );
					$uprofile [$key] ['field_data'] = $depart_info ['title'];
				}
			}
		}
		return $data;
	}
	/**
	 * 批量获取多个用户的统计数目
	 * 
	 * @param array $uids
	 *        	用户uid数组
	 * @return void
	 */
	private function _assignUserCount($uids) {
		$user_count = model ( 'UserData' )->getUserDataByUids ( $uids );
		return $user_count;
	}

	/**
	 * 调整分组列表
	 * 
	 * @param array $fids
	 *        	指定用户关注的用户列表
	 * @return void
	 */
	private function _assignFollowGroup($fids) {
		$follow_group_list = model ( 'FollowGroup' )->getGroupList ( $this->mid );
		// 调整分组列表
		if (! empty ( $follow_group_list )) {
			$group_count = count ( $follow_group_list );
			for($i = 0; $i < $group_count; $i ++) {
				if ($follow_group_list [$i] ['follow_group_id'] != $data ['gid']) {
					$follow_group_list [$i] ['title'] = (strlen ( $follow_group_list [$i] ['title'] ) + mb_strlen ( $follow_group_list [$i] ['title'], 'UTF8' )) / 2 > 8 ? getShort ( $follow_group_list [$i] ['title'], 3 ) . '...' : $follow_group_list [$i] ['title'];
				}
				if ($i < 2) {
					$data ['follow_group_list_1'] [] = $follow_group_list [$i];
				} else {
					if ($follow_group_list [$i] ['follow_group_id'] == $data ['gid']) {
						$data ['follow_group_list_1'] [2] = $follow_group_list [$i];
						continue;
					}
					$data ['follow_group_list_2'] [] = $follow_group_list [$i];
				}
			}
			if (empty ( $data ['follow_group_list_1'] [2] ) && ! empty ( $data ['follow_group_list_2'] [0] )) {
				$data ['follow_group_list_1'] [2] = $data ['follow_group_list_2'] [0];
				unset ( $data ['follow_group_list_2'] [0] );
			}
		}
		
		$data ['follow_group_status'] = model ( 'FollowGroup' )->getGroupStatusByFids ( $this->mid, $fids );
		return $data;
	}

	function appList() {
		// 获取用户信息
		$this->uid = $this->getUserUid();
		$user_info = model ( 'User' )->getUserInfo ( $this->uid );
		// 用户为空，则跳转用户不存在
		if (empty ( $user_info )) {
			$this->error ( L ( 'PUBLIC_USER_NOEXIST' ) );
		}
		$appArr = $this->_tab_menu();
		$data['user_info'] = $this->_assignUserInfo ( $this->uid );
		$type = t ( $_GET ['type'] );
		if (! isset ( $appArr [$type] )) {
			$this->error ( '参数出错！！' );
		}
		$data['type'] = $type;
		$className = ucfirst ( $type ) . 'Protocol';
		$content = D ( $className, $type )->profileContent ( $this->uid );
		if(empty($content)){
			$content = '暂无内容';
		}
		$data['content'] = $content;
		// 判断隐私设置
		$userPrivacy = $this->privacy ( $this->uid );
		if ($userPrivacy ['space'] !== 1) {
			$this->_sidebar ();
			// 档案类型
			$data['ProfileType'] = model ( 'UserProfile' )->getCategoryList ();
			// $this->assign ( 'ProfileType', $ProfileType );
			// 个人资料
			$data['user_profile'] = $this->_assignUserProfile ( $this->uid );
			// 获取用户职业信息
			$userCategory = model ( 'UserCategory' )->getRelatedUserInfo ( $this->uid );
			if (! empty ( $userCategory )) {
				foreach ( $userCategory as $value ) {
					$user_category .= '<a href="#" class="link btn-cancel"><span>' . $value ['title'] . '</span></a>&nbsp;&nbsp;';
				}
			}
			$data['user_category'] = $user_category;
			$data['userPrivacy'] = $userPrivacy;
			return $data;
		} else {
			return $this->_assignUserInfo ( $this->uid );
		}
	}
	/**
	 * 获取指定用户的某条动态
	 * 
	 * @return void
	 */
	public function feed() {
		
		$feed_id = intval ( $_GET ['feed_id'] );
		
		if (empty ( $feed_id )) {
			$this->error ( L ( 'PUBLIC_INFO_ALREADY_DELETE_TIPS' ) );
		}
	
		//获取微博信息
		$feedInfo = model ( 'Feed' )->get ( $feed_id );

		if (!$feedInfo){
			$this->error ( '该微博不存在或已被删除' );
			exit();
		}
			
		if ($feedInfo ['is_audit'] == '0' && $feedInfo ['uid'] != $this->mid) {
			$this->error ( '此微博正在审核' );
			exit();
		}

		if ($feedInfo ['is_del'] == '1') {
			$this->error ( L ( 'PUBLIC_NO_RELATE_WEIBO' ) );
			exit();
		}

		// 获取用户信息
		$user_info = model ( 'User' )->getUserInfo ( $feedInfo['uid'] );
		// 赞功能
		$diggArr = model ( 'FeedDigg' )->checkIsDigg ( $feed_id, $this->mid );
		$data['diggArr'] = $diggArr;
		// 判断隐私设置
		$userPrivacy = $this->privacy ( $this->uid );
		$data['userPrivacy'] = $userPrivacy;
		if ($userPrivacy ['space'] !== 1) {	
			$weiboSet = model ( 'Xdata' )->get ( 'admin_Config:feed' );
			$a ['initNums'] = $weiboSet ['weibo_nums'];
			$a ['weibo_type'] = $weiboSet ['weibo_type'];
			$a ['weibo_premission'] = $weiboSet ['weibo_premission'];
			switch ($feedInfo ['app']) {
				case 'weiba' :
					$feedInfo ['from'] = getFromClient ( 0, $feedInfo ['app'], '微吧' );
					break;
				default :
					$feedInfo ['from'] = getFromClient ( $from, $feedInfo ['app'] );
					break;
			}
			$data['check'] = $a;
			$data['feedInfo'] = $feedInfo;
			return $data;
		} else {
			return $this->_assignUserInfo ( $this->uid );
		}
	}

	/**
	 * 获取用户详细资料
	 * 
	 * @return void
	 */
	public function data() {
		if (! CheckPermission ( 'core_normal', 'read_data' ) && $this->uid != $this->mid) {
			$this->error ( '对不起，您没有权限浏览该内容!' );
		}
		// 获取用户信息
		$user_info = model ( 'User' )->getUserInfo ( $this->uid );
		// 用户为空，则跳转用户不存在
		if (empty ( $user_info )) {
			$this->error ( L ( 'PUBLIC_USER_NOEXIST' ) );
		}
		// 判断隐私设置
		$userPrivacy = $this->privacy ( $this->uid );
		if ($userPrivacy ['space'] !== 1) {
			$this->_sidebar ();
			// 档案类型
			$ProfileType = model ( 'UserProfile' )->getCategoryList ();
			// 个人资料
			$data['user_profile'] = $this->_assignUserProfile ( $this->uid );
			// 获取用户职业信息
			$userCategory = model ( 'UserCategory' )->getRelatedUserInfo ( $this->uid );
			if (! empty ( $userCategory )) {
				foreach ( $userCategory as $value ) {
					$user_category .= '<a href="#" class="link btn-cancel"><span>' . $value ['title'] . '</span></a>&nbsp;&nbsp;';
				}
			}
			$data['ProfileType'] = $ProfileType;
			$data['userPrivacy'] = $userPrivacy;
			$data['userCategory'] = $userCategory;
			return $data;
		}
	}
    
    public function showFaceCard(){
        
    }
}
?>