<?php 

class HelpCategoryModel extends Model {
    protected $tableName = 'help_category';
    protected $fields = array(
        1=>'help_category_id',
        2=>'title'
    );
    
    public function getAllHelpCate($map){
        $list = $this->where($map)->findAll();
        $temp = array();
        
        foreach ($list as $v) {
            $temp[$v['help_category_id']] = $v['title'];
        }
        return $temp;
    }
    
    public function getSubHelpCate(){
        $list = $this->where('pid!=0')->findAll();
        $temp = array();
        
        foreach ($list as $v){
            $temp[$v['help_category_id']] = $v['title'];
        }
        return $temp;
    }
}