<?php
class BeautifyCenterAddons extends NormalAddons{
	protected $version = "4.3.1";
	protected $author  = "Cali";
	protected $site    = "http://www.calicastle.com";
	protected $info    = "原作Geek微动力，我重新加工编辑后完美适配 TS4.0，可联系 cali@calicastle.com";
    protected $pluginName = "个人主页美化";
    protected $tsVersion = '4.0';

    public function getHooksInfo()
    {
        $hooks['list']=array('BeautifyCenterHooks');
        return $hooks;
    }

    public function adminMenu()
    {
	    return array('beautifyCenterConfig'=>"个人主页美化插件设置");
    }

    public function start()
    {
        return true;
    }

    public function install()
    {
        // 插入数据表
        $db_prefix = C('DB_PREFIX');
        $sql = "CREATE TABLE IF NOT EXISTS `{$db_prefix}user_space_style` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `uid` int(11) unsigned NOT NULL,
                `background` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                `full_background` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        D()->execute($sql);
        return true;
    }

    /**
     * 卸载插件
     * @return boolean 是否卸载成功
     */
    public function uninstall()
    {
        // 卸载数据表
        $db_prefix = C('DB_PREFIX');
        $sql = "DROP TABLE `{$db_prefix}user_space_style`;";
        D()->execute($sql);
        // 卸载addons数据
        $sql1 = "DELETE FROM `{$db_prefix}addons` WHERE `name` = 'BeautifyCenter';";
        D()->execute($sql1);
        return true;
    }
}
