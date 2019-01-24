<?php
include 'include/checkLogin.php';
// セッション変数受け取り
$password_old=$_SESSION['password_old'];
$password_re=$_SESSION['password_re'];
// $quickpass_session=$_SESSION['quickpass'];
$quickpass_session=119; //テスト用のquickpass「119」

// 暗証番号が入力された場合
if(isset($_POST['quickpass'])){
	// POST変数受け取り
	$quickpass_post=$_POST['quickpass'];
	// クイックパス照合
	if($quickpass_post==$quickpass_session){
		//データベース接続
		$dsn='mysql:host=localhost; dbname=hrh; charset=utf8';
		$user='hrhuser';
		$password='password';

		try{
			//PDOクラス作成
			$db= new PDO($dsn, $user, $password);
			$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
			// プリペアドステートメント作成
			$stmt=$db->prepare(
				'UPDATE users SET password=:password_re WHERE user=:user AND password=:password_old'
			);
			// パラメータ割り当て
			$stmt->bindParam(':password_re', sha1($password_re), PDO::PARAM_STR);
			$stmt->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_INT);
			$stmt->bindParam(':password_old', sha1($password_old), PDO::PARAM_STR);
			// クエリ実行
			$stmt->execute();
			//

			// ログアウト
			header('Location: logout.php');
			exit();

		}catch(PDOExeception $e){
			echo 'エラー：'.$e->getMessage();
		}
	}else{
		// 暗証番号が間違っていた場合
		$ng_messa="もう一度暗証番号を入力してください。";
	}
}
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
		<img src="ae/out/logo.png">
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
