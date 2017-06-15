<?php
session_start();
include 'connect.php';
if (empty($_SESSION['username'])) {
		header('location:index.php');
}
$edition = 0;

if (isset($_GET['edit']) AND !empty($_GET['edit'])) {
	$edition = 1;
	$edit_id = htmlspecialchars($_GET['edit']);
	$edit_article = "SELECT * FROM articles WHERE id = '$edit_id'";
	$res = mysqli_query($link,$edit_article);
	if (mysqli_num_rows($res) == 1) {
		$data = mysqli_fetch_array($res);
	}else{
		die("Erreur : l'article n'existe pas ...");
	}
}

if ($_SESSION['type'] == 1) {
	if (isset($_POST['article_titre'], $_POST['article_contenu'], $_POST['categorie'])) {
		if (!empty($_POST['article_titre']) AND !empty($_POST['article_contenu'])  AND !empty($_POST['categorie']) ) {

			$article_titre = $_POST['article_titre'];
			$article_contenu = $_POST['article_contenu'];
			$categorie = $_POST['categorie'];

			if ($edition == 0){
				$sql = "INSERT INTO articles (titre, contenu, date_time_publication,categorie) VALUES ('$article_titre','$article_contenu',now(),'$categorie')";
				mysqli_query($link, $sql) or die(mysqli_error($link));
				$lastid = mysqli_insert_id($link);
				if (exif_imagetype($_FILES['miniature']['tmp_name']) == 2){
					$path = 'miniatures/'.$lastid.'.jpg';
					move_uploaded_file($_FILES['miniature']['tmp_name'], $path);
				}else{
					$message = 'JPG ONLY';
				}
				
				#Récupère les mails dans la table newsletter
				$mail = "SELECT email FROM newsletter";
				$req = mysqli_query($link,$mail) or die(mysqli_error($link));
				
				#Boucle qui envera un message à tous les membres de la table
				while ($data = mysqli_fetch_assoc($req)) {
					$to = $data['email'].',';
					$titre = 'Nouvel article H1Z1';
					$message = "
					    <html lang='fr' style='box-sizing: border-box;font-family: sans-serif;'>
					    	<head>
					    		<meta charset ='utf-8'>
					    	</head>
					    	<body style='margin:0;padding:0;color:#fff;'>
					    		<div style='background-color:rgb(44,62,80);height: 100%;text-align:center;display:table;width: 100%;'>
					    			<div style='display: table-cell;vertical-align: middle;'>
							    		<p style='padding:20px;'>
							    			Vous vous êtes inscrit pour être informé de la sortie des nouveaux articles.<br>
											Justement ! Un nouvel article vient juste d'être posté sur le site.<br>
											Son titre : $article_titre<br>
											Si vous souhaitez voir l'article en question vous pouvez cliquer sur le lien ci-dessous, vous accederez au site !
							    		</p>
						    			<a href='http://localhost/php/index.php' target='_blank' style='background-color:rgb(24,188,156);padding: 15px 30px 15px 30px;border:1px solid rgb(24,188,156); border-radius:50px;text-decoration:none;color:#fff;'>Voir le site</a>
					    			</div>
					    		</div>
						    </body>
						</html>";
					$message = str_replace('\r\n' , '<br>', $message);
					$message = str_replace('\n' , '<br>', $message);
					$message = str_replace('\r' , '<br>', $message);
					$headers =  'MIME-Version: 1.0' . "\r\n"; 
				    $headers .= 'From: Your name <info@address.com>' . "\r\n";
				    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n"; 
				    mail($to, $titre,$message ,$headers);
					$message = 'Article envoyé';			
				}
			}else{
				$update = "UPDATE articles SET titre = '$article_titre', contenu = '$article_contenu', date_time_edition = NOW() WHERE id = $edit_id";
				$req = mysqli_query($link,$update) or die(mysqli_error($link));
				$message = 'Article update';
			}
		}else{
			$message = 'Veuillez remplir tous les champs';
		}
	}
}
 ?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Rédation | Edition</title>
	<script src="ckeditor/ckeditor.js"></script>
</head>
<body>
	<form action="" method="POST" enctype="multipart/form-data">
		<input type="text" placeholder="Titre de l'article" name="article_titre" value="<?php if($edition==1){?> <?=$data['titre']?> <?php } ?>" /><br>
		<textarea placeholder="Contenu de l'article" name="article_contenu" cols="80" rows="23" class="ckeditor"><?php if($edition==1){?><?=$data['contenu']?><?php } ?></textarea><br>
		<select name="categorie">
		   <option name="Info" value="Info">Info</option>
		   <option name="Patch" value="Patch">Patch</option>
		</select><br>
		<input type="file" name="miniature"> <br>
		<input type="submit" value="Envoyer l'article">
	</form>
	<br>
	<?php if (isset($message)) {echo $message;} ?>
</body>
</html>