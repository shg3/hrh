<?php
include 'include/checkLogin.php';

/* (1)サムネイルがアップされていたら縮小画像を保存
----------------------------------------*/
if(
	isset($_FILES['origin_img']) &&
	is_uploaded_file($_FILES['origin_img']['tmp_name'])
){
	// 画像の変数格納
	/* 現状は受け取ったものはすべてpngにして上書き保存する方針
	$img_name_jpg=$_SESSION['userId'].'_'.date('ymdHis').'_'.mt_rand(11,99).'.jpg';
	$img_name_png=$_SESSION['userId'].'_'.date('ymdHis').'_'.mt_rand(11,99).'.png';
	$img_name_gif=$_SESSION['userId'].'_'.date('ymdHis').'_'.mt_rand(11,99).'.gif';
	$img_name_jpg=$_SESSION['userId'].'_thumbnail.jpg';
	$img_name_gif=$_SESSION['userId'].'_thumbnail.gif';*/
	$img_name_png=$_SESSION['userId'].'_thumbnail.png';

	// 元画像の縦横サイズを取得
	list($width, $height)=getimagesize($_FILES['origin_img']['tmp_name']);

	// サムネイル縦横幅の計算と設定
	$new_width=250; //定数
	$rate=$new_width/$width;
	$new_height=$rate*$height;

	// キャンバス作成
	$canvas=imagecreatetruecolor($new_width, $new_height);

	// 画像保存
	switch(exif_imagetype($_FILES['origin_img']['tmp_name'])){
		// jpeg
		case IMAGETYPE_JPEG:
		$new_img=imagecreatefromjpeg($_FILES['origin_img']['tmp_name']); // 元画像作成
		imagecopyresampled($canvas,$new_img,0,0,0,0,$new_width,$new_height,$width,$height); //新画像作成
		imagepng($canvas,'thumbnail/'.$img_name_png);
		break;
		// png
		case IMAGETYPE_PNG:
		$new_img=imagecreatefrompng($_FILES['origin_img']['tmp_name']); // 元画像作成
		imagecopyresampled($canvas,$new_img,0,0,0,0,$new_width,$new_height,$width,$height); //新画像作成
		imagepng($canvas,'thumbnail/'.$img_name_png);
		break;
		// gif
		case IMAGETYPE_GIF:
		$new_img=imagecreatefromgif($_FILES['origin_img']['tmp_name']); // 元画像作成
		imagecopyresampled($canvas,$new_img,0,0,0,0,$new_width,$new_height,$width,$height); //新画像作成
		imagepng($canvas,'thumbnail/'.$img_name_png);
		break;
		// それ以外
		default:
		exit();
	}
	imagedestroy($new_img);
	imagedestroy($canvas);
}


/* (2)テキスト情報を更新
----------------------------------------*/
if(
	// 何か一つでもPOSTされていた場合
	isset($_POST['user_name']) ||
	isset($_POST['user_email']) ||
	isset($_POST['user_profile'])
){
	// 変数受け取り
	$user_name=$_POST['user_name'];
	$user_email=$_POST['user_email'];
	$user_profile=$_POST['user_profile'];

	// データベース接続
	
	$dsn='mysql:host=localhost; dbname=bnbnk_hrh; charset=utf8';
	$user='bnbnk';
	$dbpass='bnk_pass';

	/*
	$dsn='mysql:host=mysql1014.db.sakura.ne.jp; dbname=bnbnk_hrh; charset=utf8';
	$user='bnbnk';
	$dbpass='bnk_pass';
	*/
	try{
		// PDOクラス作成
		$db=new PDO($dsn, $user, $dbpass);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		print_r($db->errorInfo());
		// 分岐プリペアドステートメントの実行
		if(!($user_name=='' || preg_match('/^[\s　]{1,}$/u',$user_name))){
			// 名前が変更された場合はpostテーブルもアップデートする
			$stmt1=$db->prepare('UPDATE users SET name=:name WHERE userId=:userId');
			$stmt2=$db->prepare('UPDATE posts SET name=:name WHERE userId=:userId');
			$stmt1->bindParam(':name', $user_name, PDO::PARAM_STR);
			$stmt1->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_INT);
			$stmt2->bindParam(':name', $user_name, PDO::PARAM_STR);
			$stmt2->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_INT);
			$stmt1->execute();
			$stmt2->execute();
			$_SESSION['name']=$user_name;
		}
		if(!($user_email=='' || preg_match('/^[\s　]{1,}$/u',$user_email))){
			$stmt=$db->prepare('UPDATE users SET email=:email WHERE userId=:userId');
			$stmt->bindParam(':email', $user_email, PDO::PARAM_STR);
			$stmt->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_INT);
			$stmt->execute();
			$_SESSION['email']=$user_email;
		}
		if(!$user_profile==''){
			$stmt=$db->prepare('UPDATE users SET profile=:profile WHERE userId=:userId');
			$stmt->bindParam(':profile', $user_profile, PDO::PARAM_STR);
			$stmt->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_INT);
			$stmt->execute();
			$_SESSION['profile']=$user_profile;
		}
		header('location: config.php');
		exit();

	}catch(PDOExeception $e){
		echo 'エラー：'.$e->getMessage();
	}
}else{
	// POST全部が空だった場合
	header('Location: config.php');
	exit();
}
?>
