<?php
include 'include/checkLogin.php';
$ng_messa="";
if(
	isset($_POST['email']) &&
	isset($_POST['password_old']) &&
	isset($_POST['password_re'])
){
	// 変数受け取り
	$email=$_POST['email'];
	$password_old=$_POST['password_old'];
	$password_re=$_POST['password_re'];

	// 必須項目のチェック
	if(
		$email!==$_SESSION['email'] ||
		sha1($password_old)!==$_SESSION['password'] ||
		preg_match('/^[\s ]{0,}$/u', $password_re)
	){
		header('Location: changePass.php');
		$ng_messa="不正な値です。すべての項目を入力してください。";
	}else{
		// クイックパス格納
		$quickpass=time()*rand(1,9);

		// メール本文
		$mail_title='hrhパスワード変更：'.$_SESSION['name'].'様';
		$mail_text='hrhパスワード変更のご案内になります。\n
		下記の暗証番号をURL記載ページにてご入力ください。\n
		暗証番号：'.$quickpass.'\n
		changepass.php \n
		また、入力したパスワードは大切に保管してださい。\n
		password：'.$password_old.'&nbsp→&nbsp'.$password_re;

		// メール送信
		mb_language("Japanese");
		mb_internal_encoding("UTF-8");
		if(mb_send_mail($email, $mail_title, $mail_text)){
			//送れた場合セッションIDに変数を格納してONに移動
			$_SESSION['password_old']=$password_old;
			$_SESSION['password_re']=$password_re;
			$_SESSION['quickpass']=$quickpass; // これで次のページで照合する。
			header('Location: changePass_on.php');
			exit();
		}else{
			//送れなかった場合
			$ng_messa="※申し訳ありません、もう一度お送りください！";
		}
	}
}
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
