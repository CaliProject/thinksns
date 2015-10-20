<?php

function getMasterCategories(){
    $list = D('help_category')->where('pid=0')->findAll();
    $helpList = array();
    foreach ($list as $k => $v){
        $helpList[$k]['title'] = $v['title'];
        $helpList[$k]['id'] = $v['help_category_id'];
    }
    return $helpList;
}

function getSubcategory($cid){
    $list = D('help_category')->where('pid='.$cid)->order('sort')->findAll();
    $helpList = array();
    foreach ($list as $k => $v){
        $helpList[$k]['title'] = '<a href="'.U('help/Index/index',array('cid'=>$v['help_category_id'])).'">'.$v['title'].'</a>';
        $helpList[$k]['cid'] = $v['help_category_id'];
    }
    return $helpList;
}

function hasSubcategory($cid){
    $res = D('help_category')->where('pid='.$cid)->findAll();
    if ($res){
        return true;
    } else {
        return false;
    }
}

function getParentCate($cid){
    $res = D('help_category')->where('help_category_id='.$cid)->find();
    $parent = D('help_category')->where('help_category_id='.$res['pid'])->find();
    $parentLink = '<a href="'.U('help/Index/index',array('cid'=>$res['pid'])).'">'.$parent['title'].'&nbsp;&gt;</a>';
    return $parentLink;
}
function getHelpListByCateId($cid){
    $list = D('help')->where('category_id='.$cid)->findAll();
    $helpList = array();
    
    foreach ($list as $k => $v){
        $helpList[$k]['title'] = '<a href="'.U('help/Index/detail',array('help_id'=>$v['help_id'])).'">'.$v['title'].'</a>';
        $helpList[$k]['content'] = $v['content'];
    }
    return $helpList;
}

function getContentList($cid){
    $list = D('help')->where('category_id='.$cid)->findAll();
    $contentList = array();
    
    foreach ($list as $k => $v){
        $contentList[$k]['title'] = '<a href="'.U('help/Index/detail',array('help_id'=>$v['help_id'])).'">'.$v['title'].'</a>';
    }
    return $contentList;
}