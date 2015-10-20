<?php 

class LeftNavMenuWidget extends Widget {
    
    /**
    * 模板渲染
    * @param array $data 相关数据
    * @return string 渲染入口
    */
    public function render($data){
        $template = 'menu';
        $var['cid'] = intval($data['cid']);
        $var['title'] = t($data['title']);
        $var['helpCategory'] = $data['helpCategory'];
        
        $content = $this->renderFile(dirname(__FILE__)."/".$template.".html", $var);
        return $content;
    }
    
}