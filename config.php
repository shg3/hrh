<?php
include 'include/checkLogin.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equivment="Content-Type" content="text/html; charset=UTF-8">
	<title>hrh：Config</title>
	<link rel="stylesheet" href="style.css" media="all">
</head>
<body>
<div id="wrap_config">
	<header id="header" class="clearfix">
		<div class="container">
			<p><img src="ae/out/logo.png" width="140" height="70" alt="logo"></p>
			</form>
			<ul class="clearfix">
				<li><p><a href="index.php">Home</a></p></li>
				<li><p><a href="#" target="_blank">List</a></p></li><!-- 未実装です -->
				<li><p><a href="#" target="_blank">Message</a></p></li><!-- 未実装です -->
				<li><p><a href="config.php">Config</a></p></li>
			</ul>
		</div>
	</header>
	<main id="main" class="clearfix">
		<div class="container clearfix">
		<article id="article_config">
			<h5>ユーザー設定を行います</h5>
			<hr>
				<form action="config_on.php" method="POST" enctype="multipart/form-data">
					<table id="table_config">
						<tr>
							<th>Name:</th>
							<td>
								<?php echo $_SESSION['name']; ?><br>
								<input type="text" name="user_name" placeholder="変更後Name">
							</td>
						</tr>
						<tr>
							<th>E-mail:</th>
							<td>
								<?php echo $_SESSION['email']; ?><br>
								<input type="email" name="user_email" placeholder="変更後E-mail">
							</td>
						</tr>
						<tr>
							<th>Profile:</th>
							<td>
								<?php echo nl2br($_SESSION['profile']); ?><br>
								<textarea name="user_profile" placeholder="Profileを入力してください"></textarea>
							</td>
						</tr>
						<tr>
							<th>Thumbnail:</th>
							<td>
								<input type="file" name="origin_img" id="thumbnail_uploader">
							</td>
						</tr>
						<tr>
							<th>Password:</th>
							<td>
								<a href="changePass.php">パスワードを変更する</a>
							</td>
						</tr>
					</table>
					<p><input type="submit" value="設定を反映させる" class="btns"></p>
					<p><small>※サムネイルが変更されなかった時はページをリロードしてみてください</small></p>
				</form>
		</article>

		<aside id="aside">
			<div id="user" class="clearfix">
					<a href="user.php">
						<?php
						$mythumbnail="thumbnail/".$_SESSION['userId']."_thumbnail.png";
						if(file_exists($mythumbnail)){
							echo '<img src="'.$mythumbnail.'"width="80" height="80" alt="ac_img">';
						}else{
							echo '<img src="ae/out/ac_img.png" width="80" height="80" alt="ac_img">';
						}
						?>
				<div id="user_text">
						<h4><?php echo $_SESSION['name']?></h4>
					</a>
						<p><?php echo nl2br($_SESSION['profile']); ?></p>
				</div>
			</div>
		</aside>
	</main>

	<footer id="footer">
		<div class="container">
			<p><a href="logout.php">ログアウト</a></p>
			<p class="copyright">Copyright &copy; bunbunbunko All Right Reserved.</p>
		</div>
	</footer>
</div>
</body>
</html>
