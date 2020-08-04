<?php if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}?>
<!DOCTYPE HTML>
<html class="no-js">
<head>
<meta charset="<?php $this->options->charset(); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
<meta name="renderer" content="webkit">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title><?php _e('完善帐号信息 - '); $this->options->title(); ?></title>

<link rel="shortcut icon" href="/favicon.ico" />

<!-- 使用url函数转换相关路径 -->
<link rel="stylesheet" href="//apps.bdimg.com/libs/fontawesome/4.2.0/css/font-awesome.min.css">
<script src="//apps.bdimg.com/libs/jquery/1.11.1/jquery.min.js"></script>
<style type="text/css">
	*{margin:0;padding:0;}
	*,*:before, *:after {border-box;-moz-box-sizing: border-box;box-sizing: border-box;}
	body {font-family: "Open Sans","Helvetica Neue",Helvetica,Arial,STHeiti,"Microsoft Yahei","SimSun",sans-serif;font-size: 14px;line-height:1.5;color: #333;background-color: #f3f3f3;}
	a {color: #008E59;text-decoration: none;}
	.wrapper{width:420px;margin:0 auto;}
	@media (max-width: 480px){.wrapper{width:auto;padding:0 10px;}}
	.header{margin: 50px 0 30px; text-align:center;}
	.login-wrap{padding:50px 20px;background-color:#fff;}
	.login-wrap h3{text-align: center;color: #777;  margin-bottom: 30px;font-size: 18px;}
	.login-wrap p{margin-top: 10px;margin-bottom: 15px;}
	.login-wrap p label{display: block;max-width: 100%;margin-bottom: 5px;font-weight: bold;}
	.login-wrap p input{display: block;width: 100%;height: 32px;padding: 6px 12px; font-size: 14px;line-height: 1.5;border: 1px solid #CCC;}
	button{display: block;width:100%;  font-size: 18px;line-height: 1.33;vertical-align: middle;border-radius: 3px;  border: none;  padding: 6px 12px;  cursor: pointer;white-space: nowrap;  background-color: #008E59;color:#fff;}
	button:hover{}
	.tabs{  margin-bottom: 0;padding-left: 0;list-style: none;  border-bottom: 1px solid #DDD;}
	.tabs:before, .tabs:after {content: " ";display: table;}
	.tabs:after {clear: both;}
	.tabs>li {float: left;margin-bottom: -1px;position: relative;display: block;}
	.tabs>li>a {position: relative;display: block;padding: 10px 15px;margin-right: 2px;line-height: 1.5; border: 1px solid rgba(0, 0, 0, 0);border-radius: 3px 3px 0 0;}
	.tabs>li>a:hover{border-color:#eee #eee #ddd;background-color:#eee;}
	.tabs>li.active>a{color: #555;background-color: #FFF; border: 1px solid #DDD; border-bottom-color: rgba(0, 0, 0, 0);cursor: default;}
	.tabs-panel{display:none;}
</style>
<!--[if lt IE 9]>
<script src="//cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
<script src="//cdn.staticfile.org/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->
</head>
<body>

<div class="wrapper">
	<div class="header">
		<h1><a id="logo" href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title() ?></a></h1>
		<p><?php $this->options->description();?></p>
	</div>
	<div class="login-wrap">
		<h3>完善帐号信息</h3>
		<ul class="tabs">
			<li><a href="#tab1">绑定新账号</a></li>
			<li><a href="#tab2">绑定已有账号</a></li>
		</ul>
		<form action="" method="POST">
			<div class="tabs-panel" id="tab1">
				<p>
				<label for="mail" class="required">昵称</label>
				<input type="text" name="screenName" value="<?php if (isset($this->auth['nickname'])) {
    echo $this->auth['nickname'];
}?>"  />
				</p>
				<p>
				<label for="mail" class="required">邮箱</label>
				<input type="text" name="mail"  />
				</p>
				<p><button name="do" value="reg">确定</button></p>
			</div>
			<div class="tabs-panel" id="tab2">
				<p>
				<label for="mail" class="required">用户名</label>
				<input type="text" name="name"  />
				</p>
				<p>
				<label for="mail" class="required">密码</label>
				<input type="password" name="password" />
				</p>
				<p><button name="do" value="bind">确定</button></p>
			</div>
			
		</form>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$('.tabs a').click(function(){
		showTabs(this);return;
	});
	function showTabs(tab){
		var that = $(tab),li = that.parent(),id = that.attr('href');
		if(li.hasClass('active')) return;
		li.addClass('active').siblings().removeClass('active');
		$('.tabs-panel').hide();
		$(id).show();
		return;
	}
	showTabs('.tabs > li:eq(0) > a');
})
</script>
</body>
</html>