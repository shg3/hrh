<?php
// データベースhrh, テーブルusers, データベースユーザーhrhuser
// userId INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
// name VARCHAR(255) NOT NULL,
// email VARCHAR(255) NOT NULL,
// password VARCHAR(255) NOT NULL,
// profile TEXT

session_start();

// idがあればindexへ
if(isset($_SESSION['userId'])){
	header('Location: index.php');
	exit();
}else if(isset($_POST['name']) && isset($_POST['password'])){
	// フォーム入力された時
	//データベース接続
	$dsn='mysql:host=localhost; dbname=hrh; charset=utf8';
	$user='hrhuser';
	$dbpass='password';

	try{
		// PDOクラス作成
		$db=new PDO($dsn, $user, $dbpass);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		print_r($db->errorInfo());
		// プリペアドステートメント
		$stmt=$db->prepare('SELECT * FROM users WHERE name=:name AND password=:password');
		// パラメータの割り当て
		$stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
		$stmt->bindParam(':password', sha1($_POST['password']), PDO::PARAM_STR);
		// クエリ実行
		$stmt->execute();

		if($row=$stmt->fetch()){
			// セッションidを格納
			$_SESSION['userId']=$row['userId'];
		 	$_SESSION['email']=$row['email'];
			$_SESSION['name']=$row['name'];
			$_SESSION['password']=$row['password'];
			$_SESSION['profile']=$row['profile'];
			// セッションハイジャック対策をしてログイン
			session_regenerate_id(true);
			header('Location: index.php');
			exit();

		}else{
			// 1レコードも取り出せなかった場合、ユーザ名とパスワードが間違っていた場合
			header('Location: login.php');
			exit();
		}

	}catch(PDOExeceptin $e){
		die('エラー：'.$e->getMessage());
	}
}else{
// 末尾で閉じる
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equivment="Content-Type" content="text/html; charset=UTF-8">
	<title>hrh：Login</title>
	<link rel="stylesheet" href="style.css" media="all">
</head>
<body>
<div class="loginbox">
	<div class="logoimg">
		<img src="sampleImg/logo.png">
	</div>
	<hr>
	<form action="login.php" method="POST" class="inputbox clearfix">
		<p>Name：<input type="text" name="name" class="inputbox"></p>
		<p>Password：<input type="text" name="password" class="inputbox"></p>
		<input type="submit" value="login" class="btns">
	</form>
</div>
<div class="to_acc">
	<p><a href="makeNewAccount.php">アカウントを作成する</a></p>
	<p class="copyright">Copyright &copy; hrh All Right Reserved.</p>
</div>
</body>
</html>

<?php
// ここ↓消すな
}
?>
