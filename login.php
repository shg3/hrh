<?php
session_start();

// idがセットされていた場合
if(isset($_SESSION['userId'])){
	header('Location: index.php');
	exit();
}else if( // idがセットされていなかった場合で、nameとpasswordがPOSTされた場合
	isset($_POST['name']) && isset($_POST['password'])){
	//データベース接続

	$dsn='mysql:host=localhost; dbname=bnbnk_hrh; charset=utf8';
	$user='bnbnk';
	$dbpass='bnk_pass';

	/*
	$dsn='mysql:host=mysql1014.db.sakura.ne.jp; dbname=bnbnk_hrh; charset=utf8';
	$user='bnbnk';
	$dbpass='bnk_pass';
	*/


	try{
		$db=new PDO($dsn, $user, $dbpass);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		// ページ遷移のためにDBから情報を取り出すクエリ
		$stmt=$db->prepare('SELECT * FROM users WHERE name=:name AND password=:password');
		$stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
		$stmt->bindParam(':password', sha1($_POST['password']), PDO::PARAM_STR);
		$stmt->execute();
		// 格納
		if($row=$stmt->fetch()){
			$_SESSION['userId']=$row['userId'];
		 	$_SESSION['email']=$row['email'];
			$_SESSION['name']=$row['name'];
			$_SESSION['password']=$row['password'];
			$_SESSION['profile']=$row['profile'];
			// セッションハイジャック対策をしてログイン
			session_regenerate_id(true);
			header('Location: index.php');
			exit();
		}else{ // 1レコードも取り出せなかった場合、ユーザ名とパスワードが間違っていた場合はPOSTを破棄して再読み込み
			unset($_POST['name']);
			unset($_POST['password']);
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

/*
データベースbnbnk_hrh, テーブルusers, データベースユーザーbnbnk, 接続パスbnk_pass
下記ターミナルコピペ

cd /Applications/XAMPP/bin;
./mysql -u root;
CREATE DATABASE bnbnk_hrh;
USE bnbnk_hrh;

// テーブル作成
CREATE TABLE users(
userId INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
name VARCHAR(255) NOT NULL,
email VARCHAR(255) NOT NULL,
password VARCHAR(255) NOT NULL,
profile TEXT
)DEFAULT CHARACTER SET=utf8;

// ユーザー追加
GRANT ALL ON bnbnk_hrh.* to 'bnbnk'@'localhost' IDENTIFIED BY 'bnk_pass';

// ユーザーで再ログイン
exit;
./mysql -u bnbnk -p;
bnk_pass
USE bnbnk_hrh;
SELECT * FROM users;

// テスト用にテーブル消すとき
DROP TABLE users;
DROP DATABASE bnbnk_hrh;

// 本番環境のホスト名：mysql1014.db.sakura.ne.jp

// テーブルの結合
SELECT
	posts.userId,
	posts.name,
	posts.id,
	posts.title,
	posts.maintext,
	posts.date,
	users.email,
	users.password,
	users.profile
FROM
	users RIGHT OUTER JOIN posts
ON
	posts.userId = users.userId;
*/
?>
