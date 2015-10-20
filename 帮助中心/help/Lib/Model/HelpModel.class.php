<?php 
class HelpModel extends Model {
    protected $tableName = 'help';
    protected $error = '';
    protected $fields = array (
        0 => 'help_id',
        1 => 'content',
        2 => 'category_id',
        3 => 'title',
        '_autoinc' => true,
        '_pk' => 'help_id'
    );
    
    public function getHelpList ($limit = 20) {
        $list = $this->order('help_id asc')->findPage($limit);
        $helpCategory = D('help_category')->findAll();

        foreach ($list['data'] as $k => $v){
            $list['data'][$k]['title'] = '<a target="_blank" href="'.U('help/Index/detail',array(
                'help_id' => $v['help_id']
            )).'">'.$v['title'].'</a>';
            $list['data'][$k]['category_name'] = D('help_category')->where('help_category_id='.$v['category_id'])->getField('title');
            $list['data'][$k]['DOACTION'] = '<a href="'.U('help/Admin/editHelp',array(
                'help_id' => $v['help_id'],
                'tabHash' => 'editHelp'
            )).'">编辑</a>&nbsp;&nbsp;<a onclick="admin.delHelp('.$v['help_id'].');" href="javascript:void(0)">删除</a>';
        }
        return $list;
    }
    
    public function getHelpListFront ($cid){
        $list = $this->where('category_id='.$cid)->findAll();
        $helpList = array();
        foreach ($list as $k => $v){
            $helpList[$k]['title'] = '<a href="'.U('help/Index/detail',array(
                'help_id' => $v['help_id']
            )).'">'.$v['title'].'</a>';
        }
        return $helpList;
    }
    
    public function getSubcategory($cid){
        $list = D('help_category')->where('pid='.$cid)->findAll();
        $helpList = array();
        foreach ($list as $k => $v){
            $helpList[$k]['title'] = '<a href="'.U('help/Index/index',array('cid'=>$v['help_category_id'])).'">'.$v['title'].'</a>';
        }
        return $helpList;
    }
    /**
	 * 删除分类关联信息
	 * @param integer $cid 分类ID
	 * @return boolean 是否删除成功
	 */
	public function deleteAssociatedData ($cid) {
		if (empty($cid)) {
			return false;
		}
		// 删除分类下的数据
		$map['pid'] = $cid;
		D('help_category')->where($map)->delete();

		return true;
	}
    
    public function getHelpById ($help_id){
        $help = $this->where('help_id='.$help_id)->find();
        return $help;
    }
    
    public function getLastError() {
		return $this->error;
	}
}