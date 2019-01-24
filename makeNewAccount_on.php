<?php
session_start();
// make_ac.phpにchecklogin.phpをincludeしない。
// このセッションはmakeNewAccount.phpから続くセッション。

// セッション変数受け取り
$new_name=$_SESSION['new_name'];
$new_email=$_SESSION['new_email'];
$new_password=$_SESSION['new_password'];
$quickpass_session=$_SESSION['quickpass'];
// $quickpass_session=119; //テスト用のquickpass「119」

// 暗証番号が入力された場合
if(isset($_POST['quickpass'])){
	// POST変数受け取り
	$quickpass_post=$_POST['quickpass'];
	// クイックパス照合
	if($quickpass_session==$quickpass_post){
	//データベース接続
	$dsn='mysql:host=localhost; dbname=hrh; charset=utf8';
	$user='hrhuser';
	$password='password';

	try{
		//PDOクラス作成
		$db= new PDO($dsn, $user, $password);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
		// プリペアドステートメント作成
		$stmt1=$db->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
		$stmt2=$db->prepare('SELECT * FROM users WHERE name=:name AND password=:password');
		// パラメータ割り当て
		$stmt1->bindParam(':name', $new_name, PDO::PARAM_STR);
		$stmt1->bindParam(':email', $new_email, PDO::PARAM_STR);
		$stmt1->bindParam(':password', sha1($new_password), PDO::PARAM_STR);
		$stmt2->bindParam(':name', $new_name, PDO::PARAM_STR);
		$stmt2->bindParam(':password', sha1($new_password), PDO::PARAM_STR);
		// クエリ実行
		$stmt1->execute();
		$stmt2->execute();

		if($row=$stmt2->fetch()){
			// セッション変数の格納・不要なものは消去
			$_SESSION['userId']=$row['userId'];
			$_SESSION['email']=$row['email'];
			$_SESSION['name']=$row['name'];
			$_SESSION['password']=$row['password'];
			$_SESSION['profile']=$row['profile'];
			unset($_SESSION['new_name']);
			unset($_SESSION['new_email']);
			unset($_SESSION['new_password']);
			unset($_SESSION['quickpass']);
			// セッションハイジャック対策してログイン
			session_regenerate_id(true);
			// ログイン
			header('Location: index.php');
			exit();

		}else{
			// 1レコードも取り出せなかった場合、ユーザ名とパスワードが間違っていた場合
			header('Location: login.php');
			exit();
		}

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
