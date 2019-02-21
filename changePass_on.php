<?php
include 'include/checkLogin.php';

// セッション変数受け取り
$password_old=$_SESSION['password_old'];
$password_re=$_SESSION['password_re'];
// $quickpass_session=$_SESSION['quickpass'];
$quickpass_session=119; //テスト用のquickpass「119」

// 暗証番号が入力された場合
if(isset($_POST['quickpass'])){
	// POSTの受け取り
	$quickpass_post=$_POST['quickpass'];
	// 暗証番号が合っていればデーターベースに接続
	if($quickpass_post==$quickpass_session){
		//データベース接続
		/*
		$dsn='mysql:host=localhost; dbname=bnbnk_hrh; charset=utf8';
		$user='bnbnk';
		$dbpass='bnk_pass';
		*/
		$dsn='mysql:host=mysql1014.db.sakura.ne.jp; dbname=bnbnk_hrh; charset=utf8';
		$user='bnbnk';
		$dbpass='bnk_pass';

		try{
			$db= new PDO($dsn, $user, $dbpass);
			$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
			// usersテーブルのpasswordを更新するクエリ
			$stmt=$db->prepare('UPDATE users SET password=:password_re WHERE userId=:userId AND password=:password_old');
			$stmt->bindParam(':password_re', sha1($password_re), PDO::PARAM_STR);
			$stmt->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_INT);
			$stmt->bindParam(':password_old', sha1($password_old), PDO::PARAM_STR);
			$stmt->execute();
			// ログアウト
			header('Location: logout.php');
			exit();
		}catch(PDOExeception $e){
			echo 'エラー：'.$e->getMessage();
		}
	}else{ // 暗証番号が間違っていた場合はPOSTを破棄して再読み込み
		unset($_POST['quickpass']);
		header('Location: changePass_on.php');
		exit();
	}
}else{ //暗証番号が入力されていない場合
	// 末尾で閉じる
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equivment="Content-Type" content="text/html; charset=UTF-8">
	<title>hrh：アカウント新規作成-暗証番号入力</title>
	<link rel="stylesheet" href="style.css" media="all">
</head>
<body>
<div class="loginbox">
	<div class="logoimg">
		<img src="sampleImg/logo.png">
	</div>
	<hr>
	<p>メールに記載された暗証番号を入力してください。</p>
	<span>
		<p>Email：<?php echo $_SESSION['email']; ?></p>
	</span>
	<form action="changePass_on.php" method="POST" class="inputbox clearfix">
		<p>暗証番号：<input type="text" name="quickpass"></p>
		<input type="submit" value="make your account !" class="btns">
	</form>
	<?php
	if(isset($ng_messa)){
		echo '<p>'.$ng_messa.'</p>';
	}
	?>
</div>
<div class="to_acc">
	<p><a href="config.php">Configに戻る</a></p>
	<p class="copyright">Copyright &copy; hrh All Right Reserved.</p>
</div>
</body>
</html>

<?php //ここ消すな
 }
 ?>
