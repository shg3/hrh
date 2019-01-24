<?php
include 'include/checkLogin.php';

$userId=intval($_POST['userId']);
$id=intval($_POST['id']);

if($userId=='' && $id==''){
	header('Location: index.php');
}

// データベース接続
$dsn='mysql:host=localhost; dbname=hrh; charset=utf8';
$user='hrhuser';
$dbpass='password';

try{
	// PDOクエリ
	$db=new PDO($dsn,$user,$dbpass);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);

	// プリペアドステートメント
	$stmt=$db->prepare(
		"DELETE FROM posts WHERE userId=:userId AND id=:id"
	);

	// パラメータ割り当て
	$stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_STR);

	//クエリ実行
	$stmt->execute();

}catch(PDOException $e){
	echo 'エラー：'.$e->getMesage();
}

header('Location: index.php');
exit();
?>
