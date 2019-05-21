<?php
include 'include/checkLogin.php';

// 新旧のpasswordがPOSTされた場合
if(
	isset($_POST['email']) &&
	isset($_POST['password_old']) &&
	isset($_POST['password_re'])
){
	// 必須項目を諸々チェック
	if(
		$_POST['email']!==$_SESSION['email'] ||
		sha1($_POST['password_old'])!==$_SESSION['password'] ||
		preg_match('/^[\s ]{0,}$/u', $_POST['password_re'])
	){
		// NGだった場合はPOSTを破棄して再読み込み
		unset($_POST['email']);
		unset($_POST['password_old']);
		unset($_POST['password_re']);
		header('Location: changePass.php');
		exit();
	}else{
		// 適切な値だった場合は変数受け取り
		$email=$_POST['email'];
		$password_old=$_POST['password_old'];
		$password_re=$_POST['password_re'];

		// 暗証番号の生成と格納
		$quickpass=119;
		// $quickpass=time()*rand(1,9);

		//メールを送る準備
		$mail_title='hrhパスワード変更：'.$_SESSION['name'].'様';
		$mail_text='
		hrhパスワード変更のご案内になります。
		下記の暗証番号をURL記載ページにてご入力ください。
		暗証番号：'.$quickpass.'
		http://bnbnk.sakura.ne.jp/hrh/changePass_on.php
		また、入力したパスワードは大切に保管してださい。
		password：'.$password_old.'&nbsp→&nbsp'.$password_re;
		$header="From: bnbnk893@bnbnk.sakura.ne.jp";

		// メール送信
		mb_language("Japanese");
		mb_internal_encoding("UTF-8");
		if(mb_send_mail($email, $mail_title, $mail_text, $header)){
			//送れた場合はセッションIDに変数を格納してONに遷移
			$_SESSION['password_old']=$password_old;
			$_SESSION['password_re']=$password_re;
			$_SESSION['quickpass']=$quickpass;
			header('Location: changePass_on.php');
			exit();
		}else{
			//送れなかった場合はPOSTを破棄して再読み込み
			unset($_POST['email']);
			unset($_POST['password_old']);
			unset($_POST['password_re']);
			header('Location: changePass.php');
			exit();
		}
	}
}else{ // 新旧のpasswordがPOSTされていない場合
	// 末尾で閉じる
?>

<html>
<head>
	<meta http-equivment="Content-Type" content="text/html; charset=UTF-8">
	<title>hrh：パスワード変更</title>
	<link rel="stylesheet" href="style.css" media="all">
</head>
<body>
<div class="loginbox">
	<div class="logoimg">
		<img src="sampleImg/logo.png">
	</div>
	<hr>
	<p>パスワードを変更します。</p>
	<form action="changePass.php" method="POST" class="inputbox clearfix">
		<p>E-mail：<input type="email" name="email"></p>
		<p>現パスワード：<input type="text" name="password_old"></p>
		<p>新パスワード：<input type="text" name="password_re"></p>
		<input type="submit" value="Receive E-mail !" class="btns">
	</form>
	<?php
	if(isset($ng_messa)){
		echo '<p>'.$ng_messa.'</p>';
	}
	?>
</div>
<div class="to_acc">
	<p><a href="config.php">Configページに戻る</a></p>
	<p class="copyright">Copyright &copy; bunbunbunko All Right Reserved.</p>
</div>
</body>
</html>
<?php
}
	//ここ消すな
 ?>
