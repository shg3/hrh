<?php
session_start();
$ng_messa="";
if(
	isset($_POST['new_name']) &&
	isset($_POST['new_email']) &&
	isset($_POST['new_password'])
){
	// 変数受け取り
	$new_name=$_POST['new_name'];
	$new_email=$_POST['new_email'];
	$new_password=$_POST['new_password'];

	//必須項目チェック
	if(
		preg_match('/^[\s　]{0,}$/u', $new_name) ||
		preg_match('/^[\s　]{0,}$/u', $new_email) ||
		preg_match('/^[\s　]{0,}$/u', $new_password)
	){
		// 空文字があった場合
		$ng_messa="※不正な値です。すべての項目を入力してください。";

	}else{
		// 適切な値だった場合
		// メール記載事項格納
		$quickpass=time()*rand(1,9);
		$mail_title='hrh新規アカウント作成：'.$new_name.'様';
		$mail_text='hrh新規アカウント作成のご案内になります。\n
		下記の暗証番号をURL記載ページにてご入力ください。\n
		暗証番号：'.$quickpass.'\n
		mna_on.php \n
		また、入力したパスワードは大切に保管してださい。\n
		password：'.$new_password;

		// メール送信
		mb_language("Japanese");
		mb_internal_encoding("UTF-8");
		if(mb_send_mail($new_email, $mail_title, $mail_text)){
			//送れた場合セッション変数を格納してONに移動
			$_SESSION['new_name']=$new_name;
			$_SESSION['new_email']=$new_email;
			$_SESSION['new_password']=$new_password;
			$_SESSION['quickpass']=$quickpass; // これで次のページで照合する。
			header('Location: makeNewAccount_on.php');
			exit();

		} else{
			//送れなかった場合
			 $ng_messa="※申し訳ありません、もう一度お送りください！";
		}
	}
}
?>
<html>
<head>
	<meta http-equivment="Content-Type" content="text/html; charset=UTF-8">
	<title>hrh：アカウント新規作成</title>
	<link rel="stylesheet" href="style.css" media="all">
</head>
<body>
<div class="loginbox">
	<div class="logoimg">
		<img src="ae/out/logo.png">
	</div>
	<hr>
	<p>新規アカウントを作成します。</p>
	<form action="makeNewAccount.php" method="POST" class="inputbox clearfix">
		<p>Name：<input type="text" name="new_name"></p>
		<p>E-mail：<input type="email" name="new_email"></p>
		<p>password：<input type="text" name="new_password"></p>
		<input type="submit" value="Receive E-mail !" class="btns">
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
