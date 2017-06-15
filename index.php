<?php
session_start();

include 'connect.php';
	if (isset($_POST['email_newsletter']) AND !empty($_POST['email_newsletter'])) {
		$email_newsletter = $_POST['email_newsletter'];
		$sql = "INSERT INTO newsletter (email) VALUES ('$email_newsletter')";
		mysqli_query($link,$sql);
	}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>H1Z1 - News</title>
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/navbar.css">
	<link rel="stylesheet" href="css/footer.css">
	<script src="js/jquery.js"></script>
	<script src="js/loadmore.js"></script>
</head>
<body>
	<header>
		<section class="header">
			<div class="layout">
				<div class="nav_bar text-center">
					<div class="dropdown">
					  <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">
					  <span class="caret"></span></button>
					  <ul class="dropdown-menu">
					    <li class="container_link accueil">
							<a href="index.php" class="link_nav">Accueil</a>
						</li>
					    <li class="container_link">
							<a href="info.php" class="link_nav">Infos</a>
						</li>
					    <li class="container_link">
							<a href="patch.php" class="link_nav">Notes de mise à jour</a>
						</li>
					  </ul>
					</div>
					<nav>
						<img src="img/logo.png" alt="logo" title="logo" width="80px" height="auto">
						<ul class="container_nav">
							<li class="container_link accueil">
								<a href="index.php" class="link_nav">
								<img src="img/icon/all.png">
								Accueil
								</a>
							</li>
							<li class="container_link">
								<a href="info.php" class="link_nav">
								<img src="img/icon/infos.png">
								Infos
								</a>
							</li>
							<li class="container_link">
								<a href="patch.php" class="link_nav">
								<img src="img/icon/update.png">
								Notes de mise à jour
								</a>
							</li>
							<li class="container_link">
								<a href="https://www.reddit.com/r/kotk/" class="link_nav" target="_blank">
								<img src="img/icon/redditicon.png">
								Reddit
								</a>
							</li>
						</ul>
					</nav><!-- ./end nav-->
					<div>
						<ul>
							<?php if(isset($_SESSION['username'])): ?>
								<li class="deconnexion"><a href="deconnexion.php">Se deconnecter</a></li>
								<li><span class="user"><?php echo $_SESSION['username'];?></span></li>
							<?php else: ?>
								<li class="article_connexion"><a href="login.php">Se connecter</a></li>					
								<li class="article_connexion"><a href="inscription.php">S'inscrire</a></li>
							<?php endif; ?>
						</ul>
					</div>
				</div><!-- ./end navbar-->
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<h1>H1Z1 - News</h1>
						</div><!-- ./end col-md-12-->
					</div><!-- ./end row-->
				</div><!-- ./end container-->
			</div>
		</section><!-- ./end section class header-->
	</header>

	<div class="background-article">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="append_articles">

					</div><!-- ./end append_articles-->
				</div><!-- ./end col-md-12-->
			</div><!-- ./end rown-->
		</div><!-- ./end container-->
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center container_load">
					<input type="submit" id="submit_ajax" value="Plus d'articles" class="loadmore">
				</div>
			</div>
		</div>

	<!-- AJOUTER UN ARTICLE (UNIQUEMENT ADMIN)-->
	<?php
		if(isset($_SESSION['username'])){
		if ($_SESSION['type'] == 1) { ?>
			<div class="text-right">
				<a href="redaction.php" class="add_article">Ajouter un article</a>
			</div><!-- ./end text-right link-->
	</div><!-- ./end background article-->
	<?php  }}?>

	<!-- FOOTER -->
	<?php include 'footer.php' ?>

	<script src="js/bootstrap.min.js"></script>
</body>
</html>