<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}
/**
 * Typecho互联，支持15种第三方登录：QQ/腾讯微博/新浪微博/网易微博/人人网/360/豆瓣/Github/Google/Msn/点点/淘宝网/百度/开心网/搜狐
 *
 * @package TeConnect
 * @author 大卫科技Blog
 * @version 2.0
 * @link https://www.iyuu.cn
 *
 * 使用了麦当苗儿SDK http://topthink.com，适配修改为typecho
 */
class TeConnect_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        $info = self::installDb();

        //SNS帐号登录
        Helper::addRoute('oauth', '/oauth', 'TeConnect_Widget', 'oauth');
        Helper::addRoute('oauth_callback', '/oauth_callback', 'TeConnect_Widget', 'callback');

        return _t($info);
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate()
    {
        Helper::removeRoute('oauth');
        Helper::removeRoute('oauth_callback');
        //删除数据表
        return self::removeTable();
    }

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $config = require_once 'config.php';
        $text = $html ='';
        $text.= "互联配置示例 | 网站回调域 | 平台名称"."\r\n";
        $text.= "-|-|-"."\r\n";
        $num = 0;
        foreach ($config as $k => $v) {
            $num++;
            $type = strtolower(substr($k, 10));
            $text.= $type.':APP_KEY,APP_SECRET,'.$v['NAME'].' | '.$v['CALLBACK'].' | '.$v['NAME']."\r\n";
        }
        $html = Markdown::convert($text);

        //互联配置
        $connect = new Typecho_Widget_Helper_Form_Element_Textarea('connect', null, null, _t('互联配置'), _t('文本形式，一行一个账号系统配置，目前共支持'.$num.'种第三方登录！<br/>
                您可以复制对应的互联配置示例，把<strong class="warning">APP_KEY</strong>和<strong class="warning">APP_SECRET</strong>改成您申请的参数，粘贴到上方配置框。<br/>
                最后，复制对应的网站回调域，粘贴到第三方开发平台的网站回调域设置中。'.$html));
        $form->addInput($connect);

        //强制绑定
        $custom = new Typecho_Widget_Helper_Form_Element_Radio('custom', array(1=>_t('是'),0=>'否'), 0, _t('是否需要完善资料'), _t('用户使用社会化登录后，是否需要完善昵称、邮箱等信息；选择不需要完善资料则直接使用获取到的昵称。'));
        $form->addInput($custom);
    }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
    }

    /**
     * 安装数据库
     */
    public static function installDb()
    {
        try {
            return self::addTable();
        } catch (Typecho_Db_Exception $e) {
            if ('42S01' == $e->getCode()) {
                $msg = '数据表oauth_user已存在!';
                return $msg;
            }
        }
    }
    //添加数据表
    public static function addTable()
    {
        $db = Typecho_Db::get();
        $prefix = $db->getPrefix();
        if ("Pdo_Mysql" === $db->getAdapterName() || "Mysql" === $db->getAdapterName()) {
            $sql = "CREATE TABLE IF NOT EXISTS `{$prefix}oauth_user` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
                  `uuid` int(10) unsigned NOT NULL DEFAULT '0',
                  `type` char(32) NOT NULL,
                  `openid` char(50) NOT NULL,
                  `access_token` varchar(255) NOT NULL DEFAULT '0' COMMENT '用户对应access_token',
                  `expires_in` int(10) unsigned NOT NULL DEFAULT '0',
                  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后登录',
                  `name` varchar(38) NOT NULL DEFAULT '0',
                  `nickname` varchar(38) NOT NULL DEFAULT '0',
                  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别0未知,1男,2女',
                  `head_img` varchar(255) NOT NULL DEFAULT '0' COMMENT '头像',
                  `refresh_token` varchar(255) NOT NULL DEFAULT '0' COMMENT '刷新有效期token',
                  PRIMARY KEY (`id`),
                  KEY `uuid` (`uuid`),
                  KEY `uid` (`uid`),
                  KEY `type` (`type`),
                  KEY `openid` (`openid`)
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
            $db->query($sql);
        } else {
            throw new Typecho_Plugin_Exception(_t('对不起, 本插件仅支持MySQL数据库。'));
        }
        return "数据表oauth_user安装成功！";
    }
    //删除数据表
    public static function removeTable()
    {
        $db = Typecho_Db::get();
        $prefix = $db->getPrefix();
        try {
            $db->query("DROP TABLE `" . $prefix . "oauth_user`", Typecho_Db::WRITE);
        } catch (Typecho_Exception $e) {
            return "删除oauth_user表失败！";
        }
        return "删除oauth_user表成功！";
    }
    //在前端调用显示登录按钮
    public static function show($text = false)
    {
        if ($text) {
            //文本样式
            $format= '<a href="{url}" title="{title}">{title}</a>';
        } else {
            //登录按钮样式
            $format= '<a href="{url}"><img src="/usr/plugins/TeConnect/login_ico/{type}.png" alt="{type}-{title}" style="margin-top: 0.8em;"></a>';
        }

        $list = self::options();
        if (empty($list)) {
            return '';
        }
        $html = '';
        foreach ($list as $type=>$v) {
            $url = Typecho_Common::url('/oauth?type='.$type, Typecho_Widget::Widget('Widget_Options')->index);
            $html .= str_replace(
                array('{type}','{title}','{url}'),
                array($type,$v['title'],$url),
                $format
            );
        }
        echo $html;
    }
    //读取插件配置，返回数组
    public static function options($type='')
    {
        static $options = array();
        if (empty($options)) {
            $connect = Typecho_Widget::Widget('Widget_Options')->plugin('TeConnect')->connect;
            $connect = preg_split('/[;\r\n]+/', trim($connect, ",;\r\n"));
            foreach ($connect as $v) {
                $v = explode(':', $v);
                if (isset($v[1])) {
                    $tmp = explode(',', $v[1]);
                }
                if (isset($tmp[1])) {
                    $options[strtolower($v[0])] = array(
                        'id'=>trim($tmp[0]),
                        'key'=>trim($tmp[1]),
                        'title'=>isset($tmp[2]) ? $tmp[2] : $v[0]
                        );
                }
            }
        }
        return empty($type) ? $options : (isset($options[$type]) ? $options[$type] : array());
    }
}
