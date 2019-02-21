<?php
session_start();
// 新しいアカウント情報がPOSTされた場合
if(
	isset($_POST['new_name']) &&
	isset($_POST['new_email']) &&
	isset($_POST['new_password'])
){
	// POSTされたものが空白文字か空だった場合
	if(
		preg_match("/^[\s　]{0,}$/u", $_POST['new_name']) ||
		preg_match("/^[\s　]{0,}$/u", $_POST['new_email']) ||
		preg_match("/^[\s　]{0,}$/u", $_POST['new_password']) ||
		$_POST['new_name']=='' ||
		$_POST['new_email']=='' ||
		$_POST['new_password']
	){
		// POSTを消去して再読み込み
		// unset($_POST['new_name']);
		// unset($_POST['new_email']);
		// unset($_POST['new_password']);
		// header('Location: makeNewAccount.php');
		// exit();
		echo "不正な値";
	}

	// 適切な文字列が入力された場合
	// POSTされた変数の受け取り
	$new_name=$_POST['new_name'];
	$new_email=$_POST['new_email'];
	$new_password=$_POST['new_password'];

	// メールを送る準備
	$quickpass=119;
	// $quickpass=time()*rand(1,9);
	$mail_title='hrh新規アカウント作成：'.$new_name.'様';
	$mail_text='
	hrh新規アカウント作成のご案内になります。
	下記の暗証番号をURL記載ページにてご入力ください。
	暗証番号：'.$quickpass.'
	http://bnbnk.sakura.ne.jp/hrh/makeNewAccount_on.php
	また、入力したパスワードは大切に保管してださい。
	password：'.$new_password;
	$header="From: bnbnk893@bnbnk.sakura.ne.jp";

	// メール送信
	mb_language("Japanese");
	mb_internal_encoding("UTF-8");
	if(mb_send_mail($new_email, $mail_title, $mail_text, $header)){
		//送れた場合セッション変数を格納してONに移動
		$_SESSION['new_name']=$new_name;
		$_SESSION['new_email']=$new_email;
		$_SESSION['new_password']=$new_password;
		$_SESSION['quickpass']=$quickpass; // これで次のページで照合する。
		header('Location: makeNewAccount_on.php');
		exit();
	}else{
		//送れなかった場合はPOSTを破棄して再読み込み
		unset($_POST['new_name']);
		unset($_POST['new_email']);
		unset($_POST['new_password']);
		header('Location: makeNewAccount_on.php');
		exit();
	}
}else{ //新しいアカウント情報がPOSTされていなかった場合
		// 末尾で閉じる

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equivment="Content-Type" content="text/html; charset=UTF-8">
	<title>hrh：アカウント新規作成</title>
	<link rel="stylesheet" href="style.css" media="all">
</head>
<body>
<div class="loginbox">
	<div class="logoimg">
		<img src="sampleImg/logo.png">
	</div>
	<hr>
	<p>新規アカウントを作成します。</p>
	<form action="makeNewAccount.php" method="POST" class="inputbox clearfix">
		<p>Name:　<input type="text" name="new_name"></p>
		<p>E-mail:　<input type="email" name="new_email"></p>
		<p>password:　<input type="text" name="new_password"></p>
		<input type="submit" value="Receive E-mail !" class="btns">
	</form>
</div>
<div class="to_acc">
	<p><a href="login.php">ログインページに戻る</a></p>
	<p class="copyright">Copyright &copy; hrh All Right Reserved.</p>
</div>
</body>
</html>

<?php
}
?>
