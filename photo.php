<?php 
session_start();
define('IN_TG', 'true');
define('SCRIPT', 'photo');
//引入公共文件commom.inc.php
require dirname(__FILE__).'/includes/common.inc.php';
//删除目录
if ($_GET['action']=='delete' && isset($_GET['id'])) {
	if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		//删除目录
		//取得删除目录的信息
		if (!!$_rows=_fetch_array("SELECT tg_dir FROM tg_dir WHERE tg_id='{$_GET['id']}' LIMIT 1;")) {
			$_html=array();
			$_html['dir']=$_rows['tg_dir'];
			$_html=_html($_html);
			//3.删除磁盘的目录
			if (file_exists($_html['dir'])) {
				//1.删除目录里的数据库图片
				_query("DELETE FROM tg_photo WHERE tg_sid='{$_GET['id']}'");
				//2.删除目录的数据库
				_query("DELETE FROM tg_dir WHERE tg_id='{$_GET['id']}'");
				if(_remove_Dir($_html['dir'])){
					_close();
					_location('目录删除成功','photo.php');
				}
			}else{
				_close();
				_alert_back('目录删除失败');
			}
		}else{
			_alert_back('不存在此目录');
		}
	}else{
		_alert_back('非法登录');
	}
}
//读取相册数据
//分页模块
//第一个参数获取总用户数，第二个参数指定每页的用户数量
global $_pagenum,$_pagesize,$_system;
_page("SELECT tg_id FROM tg_dir;",$_system['photo']);
//从数据库提取数据获取结果集
//每次从新取结果集，而不是从新执行SQL语句
$_result=_query("SELECT tg_id,tg_name,tg_type,tg_face FROM tg_dir ORDER BY tg_date DESC LIMIT $_pagenum,$_pagesize");
?>
<!DOCTYPE html>
<html>
<head>
<?php require ROOT_PATH.'includes/title.inc.php'; ?>
<script type="text/javascript" src="js/confirm.js"></script>
</head>
<body>
<?php require ROOT_PATH.'includes/header.inc.php'; ?>

<div id="photo">
	<h2>相册列表</h2>
	<?php 
		$_html=array();
		while(!!$_rows=_fetch_array_list($_result)){
			$_html['id']=$_rows['tg_id'];
			$_html['name']=$_rows['tg_name'];
			$_html['type']=$_rows['tg_type'];
			$_html['face']=$_rows['tg_face'];
			$_html=_html($_html);
			if (empty($_html['type'])) {
				$_html['type_html']='(公开)';
			}else{
				$_html['type_html']='(私密)';
			}
			if (empty($_html['face'])) {
				$_html['face_html']='';
			}else{
				$_html['face_html']='<img src="'.$_html['face'].'" alt="'.$_html['name'].'">';
			}
			//统计相册里的图片数量
			$_html['photo']=_fetch_array("SELECT COUNT(*) AS count FROM tg_photo WHERE tg_sid='{$_html['id']}';");
	?>
	<dl>
		<dt><a href="photo_show?id=<?php echo $_html['id']?>"><?php echo $_html['face_html']?></a></dt>
		<dd><a href="photo_show.php?id=<?php echo $_html['id']?>"><?php echo $_html['name']?>(共<?php echo $_html['photo']['count']?>张图片)<?php echo $_html['type_html']?></a></dd>
		<?php if(isset($_SESSION['admin']) && isset($_COOKIE['username'])){?>
		<dd>[<a href="photo_modify_dir.php?id=<?php echo $_html['id']?>">修改</a>] [<a onclick="return check_del();" href="photo.php?action=delete&id=<?php echo $_html['id']?>">删除</a>][<a href="photo_view_history.php?id=<?php echo $_html['id']?>">浏览历史</a>]</dd>
		<?php }?>
	</dl>
	<?php }?>
	<?php if(isset($_SESSION['admin']) && isset($_COOKIE['username'])){?>
	<p><a href="photo_add_dir.php">添加目录</a></p>
	<?php }?>
</div>

<?php require ROOT_PATH.'includes/footer.inc.php'; ?>
</body>
</html>