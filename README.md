# 前言
本文为原创作品，版权属于：[大卫科技 Blog][1]（转载请保留原出处）！
本文链接https://www.iyuu.cn/archives/88/

## 一、功能介绍
**Typecho互联登录插件，目前已支持15种第三方登录：QQ/腾讯微博/新浪微博/网易微博/人人网/360/豆瓣/Github/Google/Msn/点点/淘宝网/百度/开心网/搜狐。**

在原项目[TeConnect][2]的基础上，进行完全的二次开发、优化及修复。重点有：
 1. 重新设计数据表结构，删除原connect表，后续具有完美的扩展性及兼容性；
 2. 已开发支持15种第三方登录，后续可以支持更多……；
 3. 优化会员绑定逻辑，修复原项目登录状态下绑定错乱、重复绑定等Bug；
 4. 增加会员uuid机制，自动关联users数据表的uid字段，支持更多功能开发的可能；
 5. 优化解绑逻辑，和第三方资料更新逻辑等。

----------

## 二、插件下载
从Gitee仓库下载 https://gitee.com/ledc/TeConnect/repository/archive/master.zip
# 本插件已部署，欢迎体验：

[![bt_blue_76X24.png][3]](https://www.iyuu.cn/oauth?type=qq) | [![bt_white_76X24.png][4]](https://www.iyuu.cn/oauth?type=qq) | [![bt_blue_24X24.png][5]](https://www.iyuu.cn/oauth?type=qq) | [![bt_white_24X24.png][6]](https://www.iyuu.cn/oauth?type=qq) | [![bt_92X120.png][7]](https://www.iyuu.cn/oauth?type=qq)

----------

## 三、安装步骤
 1. 解压插件到`Plugins`目录下，把本插件目录下的`callback.php`文件拷贝到当前使用主题的根目录中；
 2. 在后台启用插件，并配置插件参数（方法见：参数配置 - 配置示例）；
 3. 在当前使用主题的适当位置添加`TeConnect_Plugin::show()`方法，代码：
   ```php
<?php TeConnect_Plugin::show(); ?>
   ```
 4. 在第三方平台设置网站回调域，注意区分http、https（方法见：参数配置 - 配置示例）。
  5. 如果您的主题开启了全站PJAX，需要把以下代码放入PJAX回调函数内：

```
/*PJAX时：来源页写入cookie*/
var exdate = new Date();
exdate.setDate(exdate.getDate() + 1);
document.cookie = "TeConnect_Referer=" + encodeURI(window.location.href) + "; expires=" + exdate.toGMTString() + "; path=/";
```

----------

## 三、参数配置
### 配置示例

名称 | 类型 | 配置示例 | 网站回调域
-|-|-|-
腾讯QQ | qq | qq:APP_KEY,APP_SECRET,腾讯QQ | https://127.0.0.1/oauth_callback?type=qq
腾讯微博 | tencent | tencent:APP_KEY,APP_SECRET,腾讯微博 | https://127.0.0.1/oauth_callback?type=tencent
新浪微博 | sina | sina:APP_KEY,APP_SECRET,新浪微博 | https://127.0.0.1/oauth_callback?type=sina
网易微博 | t163 | t163:APP_KEY,APP_SECRET,网易微博 | https://127.0.0.1/oauth_callback?type=t163
人人网 | renren | renren:APP_KEY,APP_SECRET,人人网 | https://127.0.0.1/oauth_callback?type=renren
360 | x360 | x360:APP_KEY,APP_SECRET,360 | https://127.0.0.1/oauth_callback?type=x360
豆瓣 | douban | douban:APP_KEY,APP_SECRET,豆瓣 | https://127.0.0.1/oauth_callback?type=douban
Github | github | github:APP_KEY,APP_SECRET,Github | https://127.0.0.1/oauth_callback?type=github
Google | google | google:APP_KEY,APP_SECRET,Google | https://127.0.0.1/oauth_callback?type=google
MSN | msn | msn:APP_KEY,APP_SECRET,MSN | https://127.0.0.1/oauth_callback?type=msn
点点 | diandian | diandian:APP_KEY,APP_SECRET,点点 | https://127.0.0.1/oauth_callback?type=diandian
淘宝网 | taobao | taobao:APP_KEY,APP_SECRET,淘宝网 | https://127.0.0.1/oauth_callback?type=taobao
百度 | baidu | baidu:APP_KEY,APP_SECRET,百度 | https://127.0.0.1/oauth_callback?type=baidu
开心网 | kaixin | kaixin:APP_KEY,APP_SECRET,开心网 | https://127.0.0.1/oauth_callback?type=kaixin
搜狐微博 | sohu | sohu:APP_KEY,APP_SECRET,搜狐微博 | https://127.0.0.1/oauth_callback?type=sohu

### 1：后台互联配置
具体格式为：`type:appid,appkey,title`，注释：
 - type：第三方登录帐号类型
 - appid：第三方开放平台申请的应用id
 - appkey：第三方开放平台申请的应用key
 - title：登录按钮的标题
在后台互联配置中，直接以文本形式填写，一行为一个帐号系统的参数；
为减少错误发生，您可以复制对应的`配置示例`，把`APP_KEY`和`APP_SECRET`改成您自己的参数就可以了！
例如：`qq:APP_KEY,APP_SECRET,腾讯QQ`
改成：`qq:101015836,547s87f8s7df7sd877ji75s78sdfd,腾讯QQ`
粘贴到后台`互联配置`，即完成了腾讯QQ登录的配置，其他类型同理！

### 2：网站回调域配置
您可以复制对应的`配置示例`，把`127.0.0.1`改成您的域名，填写到第三方开发平台的网站回调域设置中，即可完成配置！

以本博客`www.iyuu.cn`,设置新浪微博登录，为例：
复制：`https://127.0.0.1/oauth_callback?type=sina`
把`127.0.0.1`改成`www.iyuu.cn`，改好后：
`https://www.iyuu.cn/oauth_callback?type=sina`

----------

## 四、第三方账号绑定流程
### 1、方案选择
我参考了国内主流的几家互联网公司的第三方账号登录功能，发现主要分成两种设计方案；
一种是账号强绑，像京东、小米等，在第三方账号授权通过后，需要用户绑定自己的账号；
一种是今日头条、知乎，在第三方账号授权通过后，随机给用户生成一个账号或者调用第三方账号昵称，无需绑定账号，即可成功登录。
目前，`两种方案都支持`，您可以在`后台开启或关闭强制绑定`！

### 2、绑定流程一（未登录状态）
![绑定流程一（未登录状态）.png][8]
用户在登录界面点击第三方账号，授权通过后，我们获得用户第三方账号的OpenID，由此判断用户的第三方账号之前是否绑定过，如果绑定过则直接登录成功。
如果没有绑定过，则跳到账号绑定页面。账号绑定页面需要分成已有账号直接绑定，和没有账号，新注册账号进行绑定两种情况。
当用户已有账号时，通过输入账号密码校验身份，校验通过后即可绑定成功/登录成功。
当用户没有账号时，用户可通过注册新账号，注册成功后即可绑定成功/登录成功。

### 3、绑定流程二（登录状态）
在个人账号中心里提供绑定管理的功能和界面，在用户已经登录的情况下，可以直接绑定第三方账号，只要获得授权通过，即可绑定成功。

----------

## 五、第三方帐号解绑流程
在个人账号中心>绑定管理中，可以对已经绑定的第三方账号进行解绑操作。在这里需要注意，由于用户长期使用第三方账号登录，实际上是由第三方账号承担了提交账号和保护账号安全的工作，因此在解绑第三方账号时，我们需要提醒用户，解绑以后只能通过本平台账号密码方式来登录。最好是提示用户记住当前账号
![提示用户记住当前账号.jpg][9]
京东的解绑账号功能

另一方面，由于之前是由第三方账号“帮平台”做了账号安全的工作，因此在解绑账号的时候，我们需要考虑如何保护账号安全。因此可以在解绑的时候，对账号做一定的安全校验或安全保护。
我们最终定的方案是 当用户在解绑时，需要校验手机短信验证码，如果没有绑定手机，则提示用户先去绑定手机。
![第三方帐号解绑流程.png][10]
解绑流程

总结
第三方账号虽然是一个小功能，但是在设计过程中，我们要结合自身产品的特点来确定产品方案和产品流程。授权之后，是直接登录成功，还是绑定自己平台的账号，这是由自己产品特点决定。同时，对新增账号来说，如何设计用户账号的安全，也需要根据产品特点和安全策略来设计适合的产品流程。

----------

## 六、数据表结构（不开发，可以不看）
### typecho_oauth_user数据库表结构：

字段 | 类型 | 注释  
-|-|-
uid | int(10) unsigned NOT NULL COMMENT | 用户ID
access_token | varchar(255) NOT NULL COMMENT | 用户对应access_token
datetime | timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT | 最后登录
expires_in | int(10) unsigned NOT NULL DEFAULT '0' COMMENT | access_token过期时间戳
gender | tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT | 性别0未知,1男,2女
head_img | varchar(255) NOT NULL COMMENT | 头像
name | varchar(38) NOT NULL COMMENT | 名字
nickname | varchar(38) NOT NULL COMMENT | 第三方昵称
openid | char(50) NOT NULL COMMENT | 第三方平台的用户唯一标识
refresh_token | varchar(255) NOT NULL COMMENT | 刷新有效期token
type | char(32) NOT NULL COMMENT | 第三方平台的类型
uuid | int(10) unsigned NOT NULL COMMENT | 对应users表uid

![typecho_oauth_user表结构.png][11]

### 创建数据表SQL语句
```mysql
CREATE TABLE IF NOT EXISTS `typecho_oauth_user` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `access_token` varchar(255) NOT NULL COMMENT '用户对应access_token',
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后登录',
  `expires_in` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'access_token过期时间戳',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别0未知,1男,2女',
  `head_img` varchar(255) NOT NULL COMMENT '头像',
  `name` varchar(38) NOT NULL COMMENT '名字',
  `nickname` varchar(38) NOT NULL COMMENT '第三方昵称',
  `openid` char(50) NOT NULL COMMENT '第三方平台的用户唯一标识',
  `refresh_token` varchar(255) NOT NULL COMMENT '刷新有效期token',
  `type` char(32) NOT NULL COMMENT '第三方平台的类型',
  `uuid` int(10) unsigned NOT NULL COMMENT '对应users表uid',
  UNIQUE KEY `openid` (`openid`),
  KEY `uuid` (`uuid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
```

----------
## 项目仓库
码云：https://gitee.com/ledc/TeConnect


[1]: https://www.iyuu.cn
[2]: https://github.com/jiangmuzi/TeConnect
[3]: https://www.iyuu.cn/usr/uploads/2019/08/379738753.png
[4]: https://www.iyuu.cn/usr/uploads/2019/08/1686542734.png
[5]: https://www.iyuu.cn/usr/uploads/2019/08/2003267968.png
[6]: https://www.iyuu.cn/usr/uploads/2019/08/2078951516.png
[7]: https://www.iyuu.cn/usr/uploads/2019/08/1192845902.png
[8]: https://www.iyuu.cn/usr/uploads/2019/08/1331919342.png
[9]: https://www.iyuu.cn/usr/uploads/2019/08/3278948233.jpg
[10]: https://www.iyuu.cn/usr/uploads/2019/08/2385470498.png
[11]: https://www.iyuu.cn/usr/uploads/2019/08/3510776867.png