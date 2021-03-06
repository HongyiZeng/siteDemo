<?php
session_start();
define('IN_TG', 'true');
define('SCRIPT', 'login');
//引入公共文件commom.inc.php
require dirname(__FILE__).'/includes/common.inc.php';
//判断登录状态
_login_state();
global $_system;
//开始处理登录状态
if ($_GET['action']=='login') {
	//防止恶意注册和跨站攻击
	if(!empty($_system['code'])){
		_check_code($_POST['code'],$_SESSION['code']);
	}
	//引入验证文件
	include ROOT_PATH.'includes/login.func.php';
	//接收数据
	$_clean=array();
	$_clean['username']=_check_username($_POST['username'],2,20);
	$_clean['password']=_check_password($_POST['password'],6);;
	$_clean['time']=_check_time($_POST['time']);
	//数据库验证
	if (!!$_rows=_fetch_array("SELECT tg_username,tg_uniqid,tg_level FROM tg_user WHERE tg_username='{$_clean['username']}' AND tg_password='{$_clean['password']}' AND tg_active='' LIMIT 1;")) {
		//登录成功后记录登录信息
		_query("UPDATE tg_user SET
							tg_last_time=NOW(),
							tg_last_ip='{$_SERVER["REMOTE_ADDR"]}',
							tg_login_count=tg_login_count+1
							WHERE tg_username='{$_rows['tg_username']}';
							");
		_setcookies($_rows['tg_username'],$_rows['tg_uniqid'],$_clean['time']);
		if ($_rows['tg_level']==1) {
			$_SESSION['admin']=$_rows['tg_username'];
		}
		_close();
		_location(null,'member.php');
	}else{
		_close();
		_location('用户名密码不正确！','login.php');
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<?php require ROOT_PATH.'includes/title.inc.php'; ?>
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/login.js"></script>
</head>
<body>
<?php require ROOT_PATH.'includes/header.inc.php'; ?>

<div id="login">
	<h2>登录</h2>
	<form method="post" name='login' action="login.php?action=login">
		<dl>
			<dt>请填写以下内容</dt>
			<dd>用&nbsp;&nbsp;户&nbsp;名：<input type="text" name="username" class="text"></dd>
			<dd>密&nbsp; &nbsp; &nbsp; &nbsp;码：<input type="password" name="password" class="text"></dd>
			<dd>保&nbsp; &nbsp; &nbsp; &nbsp;留：<input type="radio" name="time" value="0" checked="checked"> 不保留<input type="radio" name="time" value="1"> 一天<input type="radio" name="time" value="2"> 一周<input type="radio" name="time" value="3">一月</dd>
			<?php if(!empty($_system['code'])){?>
			<dd>验 &nbsp;证 码：<input type="text" name="code" class="text code"><img src="code.php" id="code"></dd>
			<?php }?>
			<dd><input type="submit" value="登录" class="button">
			<input type="button" value="注册" id="location" class="button location" onclick=window.location.href='register.php'></dd>
		</dl>
	</form>
</div>

<?php require ROOT_PATH.'includes/footer.inc.php'; ?>
</body>
</html>