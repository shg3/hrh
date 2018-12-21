<?php
include 'include/checkLogin.php';

// データベースhrh, テーブルpost, データベースユーザーhrhuser
// id INT NOT NULL,
// name VARCHAR(255) NOT NULL,
// postnum INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
// title VARCHAR(255) NOT NULL,
// maintext TEXT NOT NULL,
// date DATETIME NOT NULL
// テストユーザーtester,パス119

// ページ数ある場合
$num=20;
$page=0;
if(isset($_GET['page']) && $_GET['page']>0){
	$page=intval($_GET['page']) -1;
}

// データベース接続
$dsn='mysql:host=localhost; dbname=hrh; charset=utf8';
$user='hrhuser';
$dbpass='password';

try{
	// PDOクエリ
	$db=new PDO($dsn,$user,$dbpass);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	// プリペアドステートメント
	$stmt=$db->prepare(
		"SELECT * FROM post ORDER BY date DESC LIMIT :page, :num"
	);

	// パラメータ割り当て
	$page=$num*$page;
	$stmt->bindParam(':page', $page, PDO::PARAM_INT);
	$stmt->bindParam(':num', $num, PDO::PARAM_INT);

	//クエリ実行
	$stmt->execute();

}catch(PDOExeceptin $e){
	echo "エラー：".$e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equivment="Content-Type" content="text/html; charset=UTF-8">
	<title>hrh：Home</title>
	<link rel="stylesheet" href="style.css" media="all">
</head>
<body>
<div id="index_wrap" class="wrap">
	<header id="header" class="clearfix">
		<div class="container">
			<img src="ae/out/logo.png" width="140" height="70" alt="logo">
			<form action="search.php" method="POST">
				<input type="search" name="search" placeholder="🔍">
				<!--<input type="submit" value="検索" calss="btns">-->
			</form>
			<ul class="clearfix">
				<li><p><a href="index.php">Home</a></p></li>
				<li><p><a href="#">List</a></p></li>
				<li><p><a href="#">Message</a></p></li>
				<li><p><a href="config.php">Config</a></p></li>
			</ul>
	</div>
	</header>

	<main id="main" class="clearfix">
		<div class="container">
			<article id="article">
				<?php
				while($row=$stmt->fetch()):
				?>
				<div class="post clearfix">
						<?php
						echo '<a href="user.php?userpage='.$row['id'].'">'; // GET['userpage']で受けとる
						$thumbnail="thumbnail/".$row['id']."_thumbnail.png";
						if(file_exists($thumbnail)){
							echo '<img src="'.$thumbnail.'"width="40" height="40" alt="ac_img">';
						}else{
							echo '<img src="ae/out/ac_img.png" width="40" height="40" alt="ac_img">';
						}
						?>
						</a>
					<div class="post_text">
						<?php
						echo '<a href="user.php?userpage='.$row['id'].'">';
						?>
						<h4>
							<?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?>
							<span>
								(<?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?>/<?php echo $row['date'] ;?>)
							</span>
						</h4>
						</a>
						<p><?php echo nl2br(htmlspecialchars($row['maintext'], ENT_QUOTES, 'UTF-8'), false) ?></p>
						<form action="delete.php" method="POST">
							<input type="hidden" name="id" value="<?php echo $_SESSION['id']; ?>">
							<input type="hidden" name="postnum" value="<?php echo $row['postnum'] ?>">
							<?php if($row['id']==$_SESSION['id']){
								echo '<input type="submit" value="ー" class="btns">';
							}
							?>
						</form>
					</div>
				</div>
				<?php
				endwhile;

				// ページ数の表示
				try{
					// プリペアドステートメント
					$stmt=$db->prepare(
						'SELECT COUNT(*) FROM post'
					);

					// クエリ実行
					$stmt->execute();

				}catch(PDOExeception $e){
					echo "エラー：".$e->getMessage();
				}

				// postの件数を取得
				$comments=$stmt->fetchColumn();
				// ページ数を計算
				$max_page=ceil($comments/$num);
				?>
				<div id="pages">
					<?php
					echo '<p>';
					for ($i=1; $i<=$max_page;$i++){
						echo '<a href="index.php?page='.$i. ' ">'.$i.'</a>&nbsp;';
					}
					echo '</p>';
					?>
				</div>
			</article>

			<aside id="aside">
				<div id="user" class="clearfix">
					<a href="user.php">
						<?php
						$mythumbnail="thumbnail/".$_SESSION['id']."_thumbnail.png";
						if(file_exists($mythumbnail)){
							echo '<img src="'.$mythumbnail.'"width="80" height="80" alt="ac_img">';
						}else{
							echo '<img src="ae/out/ac_img.png" width="80" height="80" alt="ac_img">';
						}
						?>
						<div id="user_text">
							<h4><?php echo $_SESSION['name'];?></h4>
					</a>
							<p><?php echo nl2br($_SESSION['profile']);?></p>
					</div>
				</div>
				<div id="new">
					<form action="write.php" method="POST">
						<h4>new hrh</h4>
						<p><input type="text" name="title"></p>
						<p><textarea name="maintext"></textarea></p>
						<input type="hidden" name="name" value="<?php echo $_SESSION['name']; ?>">
						<input type="submit" value="＋" class="btns">
					</form>
				</div>
			</aside>
	</div>
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
