<?php 

class IndexAction extends Action {
    /**
    * 帮助中心首页
    * @return void
    */
    public function index(){
        // 添加样式
        $this->appCssList[] = 'help.css';
        // 获取帮助列表
        $helpCategory = model('CategoryTree')->setTable('help_category')->getCategoryList();
        $this->assign('helpCategory',$helpCategory);
        // 帮助分类选中
        $cid = intval($_GET['cid']);
        $index = false;
        if (empty($_GET['cid'])){
            $index = true;
        }
        $categoryIds = getSubByKey($helpCategory,'help_category_id');
        if (!in_array($cid,$categoryIds) && !empty($cid)){
            $this->error('您请求的帮助页面已不存在');
            return false;
        }
        $this->assign('isIndex',$index);
        $this->assign('cid',$cid);
       
        $masterCate = getMasterCategories();
        $this->assign('masterCate',$masterCate);
        
        $titleHash = model('CategoryTree')->setTable('help_category')->getCategoryHash();
        $title = empty($cid) ? "帮助中心首页" : $titleHash[$cid];
        $this->setTitle($title);
        $this->setKeywords($title);
        $this->setDescription(implode(',',getSubByKey($helpCategory,'title')));
        $this->display();
    }
    
    public function detail(){
        // 添加样式
        $this->appCssList[] = 'help.css';
        
        $help_id = $_GET['help_id']?intval($_GET['help_id']):0;
        
        $helpCategory = model('CategoryTree')->setTable('help_category')->getCategoryList();
        $this->assign('helpCategory',$helpCategory);
        
        $helpDetail = D('Help')->getHelpById($help_id);
        $this->assign('helpDetail',$helpDetail);
        
        $masterCate = getMasterCategories();
        $this->assign('masterCate',$masterCate);
        
        $this->setTitle($helpDetail['title']);
        $this->setKeywords($helpDetail['title']);
        $this->setDescription($helpDetail['title']);
        
        $this->display();
    }
}