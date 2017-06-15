<?php
session_start();
include 'connect.php';

if (isset($_GET['t'],$_GET['id']) AND !empty($_GET['t']) AND !empty($_GET['id'])) {
	$getid = (int) $_GET['id'];
	$gett= (int) $_GET['t'];
	$id_membre = $_SESSION['id'];
	$check = "SELECT id FROM articles WHERE id = $getid";
	$req = mysqli_query($link,$check);

	if(mysqli_num_rows($req) == 1){
		if ($gett == 1) {
			$isLike = "SELECT id FROM likes WHERE id_article = $getid AND id_membre = $id_membre";
			$reqisLike = mysqli_query($link,$isLike);

			$remove = "DELETE FROM dislike WHERE id_article = $getid AND id_membre = $id_membre";
			$reqremovedislike = mysqli_query($link,$remove);

			if(mysqli_num_rows($reqisLike) == 1){
				$remove = "DELETE FROM likes WHERE id_article = $getid AND id_membre = $id_membre";
				$reqremovelike = mysqli_query($link,$remove);
			}else{
				$ins = "INSERT INTO likes (id_article, id_membre) VALUES ('$getid', '$id_membre')";
				$req = mysqli_query($link,$ins);
			}

			
		}elseif ($gett == 2) {
			$isDislike = "SELECT id FROM dislike WHERE id_article = $getid AND id_membre = $id_membre";
			$reqisLike = mysqli_query($link,$isDislike);

			$remove = "DELETE FROM likes WHERE id_article = $getid AND id_membre = $id_membre";
			$reqremovelike = mysqli_query($link,$remove);

			if(mysqli_num_rows($reqisLike) == 1){
				$remove = "DELETE FROM dislike WHERE id_article = $getid AND id_membre = $id_membre";
				$reqremovedislike = mysqli_query($link,$remove);
			}else{
				$ins = "INSERT INTO dislike (id_article, id_membre) VALUES ('$getid', '$id_membre')";
				$req = mysqli_query($link,$ins);
			}
		}

		header('location:'.$_SERVER['HTTP_REFERER']);
	}else{
		exit('Erreur fatale');
	}
}else{
	exit('Erreur fatale'); 
}
 ?>
