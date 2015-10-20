<?php
/**
 * 帮助中心后台配置
 * 分类管理
 * @author Cali <cali@calicastle.com>
 * @version TS4.1
 */
tsload(APPS_PATH.'/admin/Lib/Action/AdministratorAction.class.php');
class AdminAction extends AdministratorAction {
	private $_model_category;
	
    private function _initHelpListAdminMenu(){
        $this->pageTab[] = array('title'=>$this->pageTitle['index'],'tabHash'=>'index','url'=>U('help/Admin/index'));
		$this->pageTab[] = array('title'=>$this->pageTitle['helpList'],'tabHash'=>'helpList','url'=>U('help/Admin/helpList'));
    }
    /**
	 * 初始化，配置内容标题
	 * @return void
	 */
	public function _initialize(){
		// 管理标题项目
		$this->pageTitle['index'] = '分类管理';
		$this->pageTitle['helpList'] = '详细信息编辑';

        $this->_initHelpListAdminMenu();
		$this->_model_category = model('CategoryTree')->setTable('help_category');

		parent::_initialize();
	}

	/**
	 * 帮助中心分类配置页面
	 * @return void
	 */
	public function index(){
		$_GET['pid'] = intval($_GET['pid']);
		$treeData = $this->_model_category->getNetworkList();
        $delParam['app'] = 'help';
		$delParam['module'] = 'Help';
		$delParam['method'] = 'deleteAssociatedData';
		$this->displayTree($treeData, 'help_category', 2, $delParam);
	}

	/**
	 * 详细信息编辑页面
	 * @return void
	 */
	public function helpList(){
		$_REQUEST['tabHash'] = 'helpList';
        $this->_listpk = 'help_id';
        $this->pageButton[] = array('title'=>'添加帮助','onclick'=>"javascript:location.href='".U('help/Admin/addHelp',array('tabHash'=>'addHelp'))."';");
        $this->pageKeyList = array('help_id','title','category_name','DOACTION');
        $helpList = D('help')->getHashList($k = 'help_id', $v = 'title');
        $helpList[0] = L('PUBLIC_SYSTEMD_NOACCEPT');
        $this->opt['help_id'] = $helpList;
        
        $listData = D('Help','help')->getHelpList(20);
        $this->displayList($listData);
	}
    
    /**
	 * 添加帮助页面
	 * @return void
	 */
    public function addHelp(){
        $this->pageKeyList = array('title','category_id','content');
        $list = D('HelpCategory')->getSubHelpCate();
        $this->opt['category_id'] = $list;
        $this->savePostUrl = U('help/Admin/doAddHelp');
        $this->notEmpty = array('title','category_id');
        $this->onsubmit = 'admin.checkAddHelp(this)';
        $this->displayConfig();
    }
    
    /**
	 * 添加帮助动作
	 * @return void
	 */
    public function doAddHelp(){
        $data['title'] = t($_POST['title']);
        $data['category_id'] = intval($_POST['category_id']);
        $data['content'] = $_POST['content'];
        $data['status'] = 1;
        
        $res = D('Help','help')->add($data);
        if ($res){
            $this->assign('jumpUrl', U('help/Admin/helpList'));
            $this->success(L('PUBLIC_ADD_SUCCESS'));
        } else {
            $this->error(D('Help','help')->getLastError());
        }
    }
    
    /**
	 * 编辑帮助
	 * @return void
	 */
    public function editHelp(){
        $this->assign('pageTitle','编辑帮助内容');
        $this->pageKeyList = array('title','category_id','content');
        $list = D('HelpCategory')->getSubHelpCate();
        $this->opt['category_id'] = $list;
        
        $help_id = intval($_GET['help_id']);
        $data = D('help','help')->getHelpById($help_id);
        
        $this->savePostUrl = U('help/Admin/doSaveHelp',array('help_id'=>$help_id));
        $this->notEmpty = array('title','category_id');
        $this->onsubmit = 'admin.checkAddHelp(this)';
        
        $this->displayConfig($data);
    }
    
    /**
    * 保存帮助
    * @return void
    */
    public function doSaveHelp(){
        $help_id = intval($_GET['help_id']);
        $data['category_id'] = intval($_POST['category_id']);
        $data['title'] = t($_POST['title']);
        $data['content'] = $_POST['content'];

        $res = D('help')->where('help_id='.$help_id)->save($data);
        if ($res){
            $this->assign('jumpUrl',U('help/Admin/helpList'));
            $this->success(L('PUBLIC_SYSTEM_MODIFY_SUCCESS'));
        } else {
            $this->error(D('help')->getLastError());
        }
    }
    
    /**
    * 删除帮助
    * @return array 操作成功状态和提示信息
    */
    public function delHelp(){
        if (empty($_POST['help_id'])){
            $return['status'] = 0;
            $return['data'] = '';
            echo json_encode($return);
            exit();
        }
        $help_id = intval($_POST['help_id']);
        $result = D('help')->where('help_id='.$help_id)->delete();
        if ($result){
            $return['status'] = 1;
            $return['data'] = L('PUBLIC_ADMIN_OPRETING_SUCCESS');
        } else {
            $return['status'] = 0;
            $return['data'] = L('PUBLIC_ADMIN_OPRETING_ERROR');
        }
        echo json_encode($return);
        exit();
    }
    
}