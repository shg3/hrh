<?php
// データベースhrh, テーブルposts, データベースユーザーhrhuser
// id INT NOT NULL,
// name VARCHAR(255) KEY NOT NULL,
// title VARCHAR(255) NOT NULL,
// maintext text NOT NULL,
// date DATETIME NOT NULL
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
$dsn='mysql:host=localhost; dbname=hrh; charset=utf8';
$user='hrhuser';
$dbpass='password';

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
