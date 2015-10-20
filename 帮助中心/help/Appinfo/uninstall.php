<?php
if (!defined('SITE_PATH')) exit();

$db_prefix = C('DB_PREFIX');
// 卸载数据SQL数组
$sql = array(
	"DROP TABLE IF EXISTS `{$db_prefix}help`;",
	"DROP TABLE IF EXISTS `{$db_prefix}help_category`;",
);
model('CategoryTree')->setTable('help_category')->remakeTreeCache();
// 执行SQL
foreach($sql as $v) {
	D('')->execute($v);
}