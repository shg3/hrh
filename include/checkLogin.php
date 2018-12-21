<?php
session_start();
// idがあればindexへ
if(!isset($_SESSION['id'])){
	header('Location: login.php');
	exit();
}
?>
