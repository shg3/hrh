<?php
include 'include/checkLogin.php';

// 変数受け取り
$userId=$_SESSION['userId'];
$name=$_SESSION['name'];
$title=$_POST['title'];
$maintext=$_POST['maintext'];

if(
	$userId=='' ||
	$name=='' ||
	$title=='' ||
	$maintext==''
){
	header('Location: index.php');
	exit();
}

// データベース接続
/*
$dsn='mysql:host=localhost; dbname=bnbnk_hrh; charset=utf8';
$user='bnbnk';
$dbpass='bnk_pass';
*/
$dsn='mysql:host=mysql1014.db.sakura.ne.jp; dbname=bnbnk_hrh; charset=utf8';
$user='bnbnk';
$dbpass='bnk_pass';

try{
	$db=new PDO($dsn, $user, $dbpass);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	// プリペアドステートメント
	$stmt=$db->prepare(
		"INSERT INTO posts (userId, name, title, maintext, date)
		VALUES (:userId, :name, :title, :maintext, now())"
	);

	// パラメータ割り当て
	$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':title', $title, PDO::PARAM_STR);
	$stmt->bindParam(':maintext', $maintext, PDO::PARAM_STR);

	// クエリ実行
	$stmt->execute();

	header('Location: index.php');
	exit();

}catch(PDOExeceptin $e){
	die ("エラー：".$e->getMessage());
}
?>
