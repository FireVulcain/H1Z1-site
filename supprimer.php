<?php
session_start();
include 'connect.php'; 
if ($_SESSION['type'] == 1) {
	if (isset($_GET['id']) AND !empty($_GET['id'])) {

		$edit_id = htmlspecialchars($_GET['id']);

		$suppr = "DELETE FROM articles WHERE id = '$edit_id'";
		mysqli_query($link, $suppr);
		header('location:index.php');
	}
}else{
	header('location:index.php');
}
?>