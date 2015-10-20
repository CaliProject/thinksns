<?php
/**
 * 帮助中心
 * @author Cali <cali@calicastle.com>
 * @version TS4.1
 */
if (!defined('SITE_PATH')) exit();

return array(
	// 应用名称 [必填]
	'NAME'						=> '帮助中心',
	// 应用简介 [必填]
	'DESCRIPTION'				=> '给站点提供帮助中心来引导用户',
	// 托管类型 [必填]（0:本地应用，1:远程应用）
	'HOST_TYPE'					=> '0',
	// 前台入口 [必填]（格式：Action/act）
	'APP_ENTRY'					=> 'Index/index',
	// 应用图标 [必填]
	'ICON_URL'					=> SITE_URL . '/apps/help/Appinfo/icon_app.png',
	// 应用图标 [必填]
	'LARGE_ICON_URL'			=> SITE_URL . '/apps/help/Appinfo/icon_app_large.png',
	// 版本号 [必填]
	'VERSION_NUMBER'			=> '1.0',
	// 后台入口 [选填]
	'ADMIN_ENTRY'				=> 'help/Admin/index',
	// 统计入口 [选填]（格式：Model/method）
	'STATISTICS_ENTRY'			=> '',
	// 应用的主页 [选填]
	'HOMEPAGE_URL'				=> '',
	// 应用类型
	'CATEGORY'					=> '工具',
	// 发布日期
	'RELEASE_DATE'				=> '2015-08-31',
	// 最后更新日期
	'LAST_UPDATE_DATE'			=> '2015-09-01',
    'COMPANY_NAME'              => 'Cali 工作室',
	// 作者名 [必填]
	'AUTHOR_NAME'				=> 'Cali',
	// 作者Email [必填]
	'AUTHOR_EMAIL'				=> 'cali@calicastle.com',
	// 作者主页 [选填]
	'AUTHOR_HOMEPAGE_URL'		=> 'http://www.calicastle.com',
	// 贡献者姓名 [选填]
	'CONTRIBUTOR_NAMES'			=> 'Cali',
);