<?php 
	#Connexion à la BDD
	$link = mysqli_connect('localhost', 'root', '', 'articles');
	mysqli_set_charset($link, 'utf-8');
 ?>