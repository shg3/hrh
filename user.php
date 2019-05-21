<?php
include 'include/checkLogin.php';

// 変数受け取り
$userpage=$_GET['userpage'];

// ページ数ある場合
$num=20;
$page=0;
if(isset($_GET['page']) && $_GET['page']>0){
	$page=intval($_GET['page']) -1;
}

// データベース接続

$dsn='mysql:host=localhost; dbname=bnbnk_hrh; charset=utf8';
$user='bnbnk';
$dbpass='bnk_pass';

/*
$dsn='mysql:host=mysql1014.db.sakura.ne.jp; dbname=bnbnk_hrh; charset=utf8';
$user='bnbnk';
$dbpass='bnk_pass';
*/

try{
	// PDOクエリ
	$db=new PDO($dsn,$user,$dbpass);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	// プリペアドステートメント
	$stmt=$db->prepare(
		"SELECT * FROM posts WHERE userId=:userId ORDER BY date DESC LIMIT :page, :num"
	);

	// パラメータ割り当て
	$page=$num*$page;
	$stmt->bindParam(':page', $page, PDO::PARAM_INT);
	$stmt->bindParam(':num', $num, PDO::PARAM_INT);
	$stmt->bindParam(':userId', $userpage, PDO::PARAM_INT);

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
	<title>hrh：Config</title>
	<link rel="stylesheet" href="style.css" media="all">
</head>
<body>
<div class="wrap">
	<header id="header" class="clearfix">
		<div class="container">
			<img src="sampleImg/logo.png" width="140" height="70" alt="logo">
			<form action="search.php" method="POST">
				<input type="search" name="search" placeholder="🔍未実装">
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
		<div class="container">
			<article id="article">
				<?php
				while($row=$stmt->fetch()):
				?>
				<div class="post clearfix">
						<?php
						echo '<a href="user.php?userpage='.$row['userId'].'">'; // GET['userpage']で受けとる
						$thumbnail="thumbnail/".$row['userId']."_thumbnail.png";
						if(file_exists($thumbnail)){
							echo '<img src="'.$thumbnail.'"width="40" height="40" alt="noImg">';
						}else{
							echo '<img src="sampleImg/si_gray.png" width="40" height="40" alt="noImg">';
						}
						?>
						</a>
					<div class="post_text">
						<?php
						echo '<a href="user.php?userpage='.$row['userId'].'">';
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
							<input type="hidden" name="userId" value="<?php echo $_SESSION['userId']; ?>">
							<input type="hidden" name="id" value="<?php echo $row['id'] ?>">
							<?php if($row['userId']==$_SESSION['userId']){
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
						'SELECT COUNT(*) FROM posts'
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
					<?php
					echo '<a href="user.php?userpage='.$_SESSION['userId'].'">';
					$mythumbnail="thumbnail/".$_SESSION['userId']."_thumbnail.png";
					if(file_exists($mythumbnail)){
						echo '<img src="'.$mythumbnail.'"width="80" height="80" alt="noImg">';
					}else{
						echo '<img src="sampleImg/si_gray.png" width="80" height="80" alt="noImg">';
					}
					?>
					<div id="user_text">
						<h4><?php echo htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8');?></h4>
					</a>
						<p><?php echo nl2br(htmlspecialchars($_SESSION['profile'],ENT_QUOTES, 'UTF-8'));?></p>
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
			<p class="copyright">Copyright &copy; hrh All Right Reserved.</p>
		</div>
	</footer>
</div>
</body>
</html>
