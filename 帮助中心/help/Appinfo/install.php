<?php
if (!defined('SITE_PATH')) exit();

header('Content-Type: text/html; charset=utf-8');

$db_prefix = C('DB_PREFIX');
$sql = array(
    "DROP TABLE IF EXISTS `{$db_prefix}help_category`;",
    "CREATE TABLE `{$db_prefix}help_category` (
  `help_category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '分类名称',
  `pid` int(11) NOT NULL COMMENT '所属分类',
  `sort` int(11) NOT NULL COMMENT '排序位置',
  `ext` text,
  PRIMARY KEY (`help_category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;",
    "DROP TABLE IF EXISTS `{$db_prefix}help`;",
    "CREATE TABLE `{$db_prefix}help` (
  `help_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` longtext COMMENT '储存的 text/html',
  `category_id` int(11) NOT NULL COMMENT '所属分类id',
  `title` varchar(100) NOT NULL COMMENT '帮助标题',
  PRIMARY KEY (`help_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;",
    "INSERT INTO `{$db_prefix}system_config` (`list`, `key`, `value`, `mtime`)
VALUES
	('pageKey', 'help_Admin_addHelp', 'a:6:{s:3:\"key\";a:3:{s:5:\"title\";s:5:\"title\";s:11:\"category_id\";s:11:\"category_id\";s:7:\"content\";s:7:\"content\";}s:8:\"key_name\";a:3:{s:5:\"title\";s:12:\"帮助标题\";s:11:\"category_id\";s:12:\"所属类别\";s:7:\"content\";s:6:\"内容\";}s:8:\"key_type\";a:3:{s:5:\"title\";s:4:\"text\";s:11:\"category_id\";s:6:\"select\";s:7:\"content\";s:6:\"editor\";}s:11:\"key_default\";a:3:{s:5:\"title\";s:0:\"\";s:11:\"category_id\";s:0:\"\";s:7:\"content\";s:0:\"\";}s:9:\"key_tishi\";a:3:{s:5:\"title\";s:0:\"\";s:11:\"category_id\";s:0:\"\";s:7:\"content\";s:0:\"\";}s:14:\"key_javascript\";a:3:{s:5:\"title\";s:0:\"\";s:11:\"category_id\";s:0:\"\";s:7:\"content\";s:0:\"\";}}', '2015-08-31 20:45:20'),
	('pageKey', 'help_Admin_editHelp', 'a:6:{s:3:\"key\";a:3:{s:5:\"title\";s:5:\"title\";s:11:\"category_id\";s:11:\"category_id\";s:7:\"content\";s:7:\"content\";}s:8:\"key_name\";a:3:{s:5:\"title\";s:12:\"帮助标题\";s:11:\"category_id\";s:12:\"所属类别\";s:7:\"content\";s:12:\"页面内容\";}s:8:\"key_type\";a:3:{s:5:\"title\";s:4:\"text\";s:11:\"category_id\";s:6:\"select\";s:7:\"content\";s:6:\"editor\";}s:11:\"key_default\";a:3:{s:5:\"title\";s:0:\"\";s:11:\"category_id\";s:0:\"\";s:7:\"content\";s:0:\"\";}s:9:\"key_tishi\";a:3:{s:5:\"title\";s:0:\"\";s:11:\"category_id\";s:0:\"\";s:7:\"content\";s:0:\"\";}s:14:\"key_javascript\";a:3:{s:5:\"title\";s:0:\"\";s:11:\"category_id\";s:0:\"\";s:7:\"content\";s:0:\"\";}}', '2015-08-31 20:46:38'),
	('pageKey', 'help_Admin_helpList', 'a:4:{s:3:\"key\";a:4:{s:7:\"help_id\";s:7:\"help_id\";s:5:\"title\";s:5:\"title\";s:13:\"category_name\";s:13:\"category_name\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:4:{s:7:\"help_id\";s:2:\"ID\";s:5:\"title\";s:12:\"帮助标题\";s:13:\"category_name\";s:12:\"所属分类\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:4:{s:7:\"help_id\";s:1:\"0\";s:5:\"title\";s:1:\"0\";s:13:\"category_name\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:4:{s:7:\"help_id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:13:\"category_name\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2015-09-01 19:44:42');
",
);

foreach ($sql as $value){
    $res = D('')->execute($value);
    // Error handler
//    if(!empty($res)) {
//        echo $res['error_code'];
//        echo '<br />';
//        echo $res['error_sql'];
//        // Remove
//        include_once(APPS_PATH.'/people/Appinfo/uninstall.php');
//        exit;
//    }
}