<!-- 头部框架 -->
<?php
if(!defined('IN_TG')){
	exit('Access Denied!');	
}
if(isset($_COOKIE['username'])){
// 	echo '
// 	<embed src="test.mp3" autostart="true" loop="true" hidden="true">';
}
?>
<script type="text/javascript" src="js/skin.js"></script>
<div id="header">
	<h1><p>多用户博客系统</p></h1>
	<?php
			if(isset($_COOKIE['username'])){
				echo '
				<img src="music\1.png" alt="Base64 encoded image" width="20" height="20" style="float:left"/>';
			}else {
				echo '<img src="music\2.png" alt="Base64 encoded image" width="20" height="20" style="float:left"/>';
			}
		?>
	<ul>
		<li><a href="index.php">首页</a></li>
		
		<?php
			if(isset($_COOKIE['username'])){
				echo "<li><a href='member.php'>".$_COOKIE['username'].'の个人中心</a>'.$_message_html.'</li>';
				echo "\n";
			}else {
				echo '<li><a href="register.php">注册</a></li>';
				echo "\n";
				echo "\t\t";
				echo '<li><a href="login.php"> 登录</a></li>';
				echo "\n";
			}
		?>
		<li><a href="blog.php">博友</a></li>
		<li><a href="photo.php">相册</a></li>
		<li class="skin" onmouseover="inskin()" onmouseout="outskin()">
			<a href="javascript:;">风格</a>
			<dl id="skin">
				<dd><a href="skin.php?id=1">1.一号皮肤</a></dd>
				<dd><a href="skin.php?id=2">2.二号皮肤</a></dd>
				<dd><a href="skin.php?id=3">3.三号皮肤</a></dd>
			</dl>
		</li>
		<?php
			if (isset($_COOKIE['username']) && isset($_SESSION['admin'])) {
				echo '<li><a href="manage.php" class="manage">管理</a> </li>';
			}
			if(isset($_COOKIE['username'])){
				echo "<li><a href='logout.php'>退出</a></li>";
			}
		?>
	</ul>
</div>