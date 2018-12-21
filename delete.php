<?php
include 'include/checkLogin.php';

$id=intval($_POST['id']);
$postnum=intval($_POST['postnum']);

if($id=='' && $postnum==''){
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
		"DELETE FROM post WHERE id=:id AND postnum=:postnum"
	);

	// パラメータ割り当て
	$stmt->bindParam(':id', $id, PDO::PARAM_STR);
	$stmt->bindParam(':postnum', $postnum, PDO::PARAM_STR);

	//クエリ実行
	$stmt->execute();

}catch(PDOException $e){
	echo 'エラー：'.$e->getMesage();
}

header('Location: index.php');
exit();
?>
