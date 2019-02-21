<?php
session_start();
// makeNewAccount_on.phpにchecklogin.phpをincludeしない。
// このセッションはmakeNewAccount.phpから続くセッション。

// セッション変数受け取り
$new_name=$_SESSION['new_name'];
$new_email=$_SESSION['new_email'];
$new_password=$_SESSION['new_password'];
// $quickpass_session=119; //テスト用のquickpass「119」

// このページから暗証番号がPOSTされた場合
if(isset($_POST['quickpass'])){
	// 暗証番号が合っていた場合
	if($_SESSION['quickpass']==$_POST['quickpass']){
		// データベース接続
		/*
		$dsn='mysql:host=localhost; dbname=bnbnk_hrh; charset=utf8';
		$user='bnbnk';
		$password='bnk_pass';
		*/
		$dsn='mysql:host=mysql1014.db.sakura.ne.jp; dbname=bnbnk_hrh; charset=utf8';
		$user='bnbnk';
		$dbpass='bnk_pass';

		try{
			$db= new PDO($dsn, $user, $dbpass);
			$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
			//新ユーザー情報をDB入力するクエリ
			$stmt1=$db->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
			$stmt1->bindParam(':name', $new_name, PDO::PARAM_STR);
			$stmt1->bindParam(':email', $new_email, PDO::PARAM_STR);
			$stmt1->bindParam(':password', sha1($new_password), PDO::PARAM_STR);
			$stmt1->execute();
			//ページ遷移するためにユーザー情報をDBから取り出すクエリ
			$stmt2=$db->prepare('SELECT * FROM users WHERE name=:name AND password=:password');
			$stmt2->bindParam(':name', $new_name, PDO::PARAM_STR);
			$stmt2->bindParam(':password', sha1($new_password), PDO::PARAM_STR);
			$stmt2->execute();

			if($row=$stmt2->fetch()){
				// セッション変数の格納
				$_SESSION['userId']=$row['userId'];
				$_SESSION['email']=$row['email'];
				$_SESSION['name']=$row['name'];
				$_SESSION['password']=$row['password'];
				$_SESSION['profile']=$row['profile'];
				// 不要なものは消去
				unset($_SESSION['new_name']);
				unset($_SESSION['new_email']);
				unset($_SESSION['new_password']);
				unset($_SESSION['quickpass']);
				// セッションハイジャック対策してログイン
				session_regenerate_id(true);
				header('Location: index.php');
				exit();
			}else{
				// 1レコードも取り出せなかった場合
				// ユーザ名とパスワードが間違っていた場合
				unset($_POST['quickpass']);
				header('Location: makeNewAccount.php');
				exit();
			}
		}catch(PDOExeception $e){
			echo 'エラー：'.$e->getMessage();
		}
	}else{ // 暗証番号が間違っていた場合
		unset($_POST['quickpass']);
		header('Location: makeNewAccount.php');
		exit();
	}
}else{ //暗証番号がPOSTされていなかった場合
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
		<p>Name：<?php echo $new_name; ?></p>
		<p>Email：<?php echo $new_email; ?></p>
	</span>
	<form action="makeNewAccount_on.php" method="POST" class="inputbox clearfix">
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
	<p><a href="login.php">ログインページに戻る</a></p>
	<p class="copyright">Copyright &copy; bunbunbunko All Right Reserved.</p>
</div>
</body>
</html>
<?php
}
?>
