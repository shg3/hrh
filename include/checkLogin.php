<?php
session_start();
// idがあればindexへ
if(!isset($_SESSION['userId'])){
	header('Location: login.php');
	exit();
}
?>
