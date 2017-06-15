<?php
session_start();
include 'connect.php';
if (isset($_GET['id']) AND !empty($_GET['id'])) {
	$id_article = htmlspecialchars($_GET['id']);
	$article = "SELECT * FROM articles WHERE id = $id_article";
	$res = mysqli_query($link, $article);
	if($res && mysqli_num_rows($res) == 1){
		$article = mysqli_fetch_assoc($res);
		$titre = $article['titre'];
		$id = $article['id'];
		$contenu = $article['contenu'];

		$likes = "SELECT id FROM likes WHERE id_article = $id";
		$reqlike = mysqli_query($link, $likes);
		$likes = mysqli_num_rows($reqlike);

		$dislikes = "SELECT id FROM dislike WHERE id_article = $id";
		$reqdislike = mysqli_query($link, $dislikes);
		$dislikes = mysqli_num_rows($reqdislike);
	}else{
		header('location:index.php');
	}
}else{
	header('location:index.php');
}

if (empty($_SESSION['username'])) {
	$msg = "Vous devez être connecté pour poster un message";
}else if (isset($_POST['submit_commentaire'])) {
	if (isset($_POST['commentaire']) AND !empty($_POST['commentaire'])) {
		$id_article = htmlspecialchars($_GET['id']);
		$pseudo = $_SESSION['username'];
		$commentaire = mysqli_real_escape_string($link,$_POST['commentaire']);
		$sql = "INSERT INTO commentaire (pseudo,commentaires, id_article) VALUES ('$pseudo','$commentaire','$id_article')";
		mysqli_query($link,$sql) or die(mysqli_error($link));
		$msg = "<span style='color:green;'>Votre commentaire a bien été posté</span><br>";
	}else{
		$msg = "Vous devez rentrer un message";
	}
}
$show_commentaire = "SELECT * FROM commentaire WHERE id_article = '$id_article' ORDER BY id DESC";
$res = mysqli_query($link,$show_commentaire);

$count_commentaire = "SELECT COUNT(*) FROM commentaire WHERE id_article = '$id_article'";
$req_count = mysqli_query($link,$count_commentaire);
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title><?=$titre?></title>
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/article.css">
	<link rel="stylesheet" href="css/navbar.css">
	<link rel="stylesheet" href="css/footer.css">
</head>
<body>
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
			<ul class="container_nav">
				<li class="container_link accueil">
					<a href="index.php" class="link_nav">
					<img src="img/icon/all.png">
					Accueil
					</a>
				</li>
				<li class="container_link">
					<a href="#" class="link_nav">
					<img src="img/icon/infos.png">
					Infos
					</a>
				</li>
				<li class="container_link">
					<a href="#" class="link_nav">
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
	<header class="header" style="background-image: url(miniatures/<?= $id?>.jpg);">
		<div class="container layout">
			<div class="row">
				<div class="col-md-12 text-center titre">
					<h1><?= $titre ?></h1>
				</div><!-- ./end col-md-12-->
			</div><!-- ./end row-->
		</div><!-- ./end container-->
	</header>
	<div class="container">
		<div class="row">
			<div class="col-md-8 contenu_article">
				<p><?= $contenu ?></p>
			</div><!-- ./end col-md-12-->
			<?php if (!empty($_SESSION['username'])) { ?> 
			<div class="col-md-12 text-right">
				<a href="action.php?t=1&id=<?= $id?>">J'aime</a> : (<?= $likes ?>)<br>
				<a href="action.php?t=2&id=<?= $id?>">Je n'aime pas</a> : (<?= $dislikes?>)
			</div>
			<?php } ?>
		</div><!-- ./end row-->
	</div><!-- ./end container-->
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<!-- ESPACE COMMENTAIRES -->
				<br><br>
				<h2>
				<?php
				$data = mysqli_fetch_array($req_count);
				echo $data[0];
				?>
				Commentaire(s) :</h2><br><br>
				<form action="" method="POST">
					<textarea name="commentaire" cols="100" rows="10" class="textarea_style" placeholder="Votre commentaire..."></textarea><br><br>
					<input type="submit" value="Envoyer" name="submit_commentaire" class="btn btn-success">
				</form>
				<?php if (isset($msg)) {echo $msg;} ?>
				<br>
				<?php  while($data = mysqli_fetch_assoc($res)){?>
				<div class="comment">
					<div>
						<b><?= $data['pseudo']?>:</b><br><?=$data['commentaires'];?><br>
					</div>
				</div>
				<?php } ?>
			</div>
		</div><!-- ./end row-->
	</div><!-- ./end container-->
	<hr>
	<footer>
		<section class="footer">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center">
						<h3>INSCRIVEZ-VOUS POUR ETRE INFORMÉ</h3>
						<p>
							Inscrivez-vous maintenant pour obtenir des infos sur la sortie d'article ainsi que les nouvelles fonctionnalités que peut offrir <br> <b>H1Z1: King of the Kill.</b>
						</p>
						<input type="email" placeholder="Votre mail">
						<input type="submit" value="Inscrivez-vous" class="submit_newsletter">
					</div><!-- ./end col-md-12-->
				</div><!-- ./end row-->
			</div><!-- ./end container-->
			<div class="social_network">
				<div class="container text-center">
					<div class="row">
						<div class="col-md-12">
							<ul>
								<li>
									<a href="https://www.facebook.com/H1Z1KotK" target="_blank">
										<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
										viewBox="0 0 288.861 288.861" style="enable-background:new 0 0 288.861 288.861;" xml:space="preserve">
											<g>
												<path class="facebook" d="M167.172,288.861h-62.16V159.347H70.769v-59.48h34.242v-33.4C105.011,35.804,124.195,0,178.284,0
													c19.068,0,33.066,1.787,33.651,1.864l5.739,0.746l-1.382,55.663l-6.324-0.058c-0.013,0-14.223-0.135-29.724-0.135
													c-11.536,0-13.066,2.847-13.066,14.171v27.629h50.913l-2.821,59.48h-48.086v129.501H167.172z M117.858,276.007h36.453V146.5h48.677
													l1.607-33.779h-50.284V72.238c0-13.368,3.078-27.025,25.919-27.025c9.178,0,17.899,0.045,23.509,0.09l0.778-31.292
													c-5.675-0.508-15.116-1.157-26.247-1.157c-44.544,0-60.419,27.693-60.419,53.613v46.254H83.61V146.5h34.242v129.507H117.858z" fill="#FFFFFF"/>
											</g>
											</g>
										</svg>
									</a>
								</li>
								<li>
									<a href="https://twitter.com/h1z1kotk" target="_blank">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 511.271 511.271" style="enable-background:new 0 0 511.271 511.271;" xml:space="preserve" width="512px" height="512px">
											<g>
												<g>
													<path class="twitter" d="M508.342,94.243c-2.603-2.603-6.942-3.471-10.414-2.603l-17.356,6.075c10.414-12.149,17.356-25.166,21.695-37.315    c1.736-4.339,0.868-7.81-1.736-10.414c-2.603-2.603-6.942-3.471-10.414-1.736c-24.298,10.414-45.125,19.092-62.481,24.298    c0,0.868-0.868,0-1.736,0c-13.885-7.81-47.729-25.166-72.027-25.166c-61.614,0.868-111.078,52.936-111.078,116.285v3.471    c-90.251-17.356-139.715-43.39-193.519-99.797L40.6,58.663l-5.207,10.414c-29.505,56.407-8.678,107.607,25.166,142.319    c-15.62-2.603-26.034-7.81-35.58-15.62c-3.471-2.603-7.81-3.471-12.149-0.868c-3.471,1.736-5.207,6.942-4.339,11.281    c12.149,40.786,42.522,73.763,75.498,93.722c-15.62,0-28.637-1.736-41.654-10.414c-3.471-1.736-8.678-1.736-12.149,0.868    s-5.207,6.942-3.471,11.281c15.62,44.258,45.993,67.688,94.59,73.763c-25.166,14.753-58.142,26.902-109.342,27.77    c-5.207,0-9.546,3.471-11.281,7.81c-1.736,5.207,0,9.546,3.471,13.017c31.241,25.166,100.664,39.919,186.576,39.919    c152.732,0,277.695-136.244,277.695-303.729v-2.603c19.092-9.546,34.712-27.77,42.522-52.936    C511.813,101.185,510.945,96.846,508.342,94.243z M456.274,143.707l-5.207,1.736v14.753    c0,157.939-117.153,286.373-260.339,286.373c-78.97,0-131.905-13.017-160.542-26.902c59.878-4.339,94.59-23.431,121.492-44.258    l21.695-15.62h-26.034c-49.464,0-79.837-13.885-97.193-46.861c15.62,5.207,32.108,5.207,50.332,4.339    c6.942-0.868,13.885-0.868,20.827-0.868l2.603-17.356c-32.976-9.546-72.027-39.051-91.119-78.969    c17.356,7.81,36.447,9.546,53.803,9.546h26.902L91.8,213.999c-18.224-13.017-72.027-59.01-45.993-124.963    c55.539,54.671,108.475,79.837,203.932,97.193l10.414,1.736v-24.298c0-53.803,41.654-98.061,93.722-98.929    c19.959-0.868,52.936,17.356,62.481,22.563c5.207,2.603,10.414,3.471,15.62,1.736c13.017-4.339,28.637-10.414,45.993-17.356    c-7.81,13.017-18.224,25.166-32.108,36.448c-3.471,2.603-4.339,7.81-2.603,12.149c1.736,4.339,6.942,6.075,11.281,4.339    l33.844-11.281C482.308,124.616,472.762,137.633,456.274,143.707z" fill="#FFFFFF"/>
												</g>
											</g>
										</svg>
									</a>
								</li>
								<li>
									<a href="https://www.reddit.com/r/kotk/" target="_blank">
										<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 									viewBox="0 0 429.709 429.709" style="enable-background:new 0 0 429.709 429.709;" xml:space="preserve">
											<path style="fill:#FFFFFF;" d="M429.709,196.618c0-29.803-24.16-53.962-53.963-53.962c-14.926,0-28.41,6.085-38.176,15.881
												c-30.177-19.463-68.73-31.866-111.072-33.801c0.026-17.978,8.078-34.737,22.104-45.989c14.051-11.271,32.198-15.492,49.775-11.588
												l2.414,0.536c-0.024,0.605-0.091,1.198-0.091,1.809c0,24.878,20.168,45.046,45.046,45.046s45.046-20.168,45.046-45.046
												c0-24.879-20.168-45.046-45.046-45.046c-15.997,0-30.01,8.362-38.002,20.929l-4.317-0.959c-24.51-5.446-49.807,0.442-69.395,16.156
												c-19.564,15.695-30.792,39.074-30.818,64.152c-42.332,1.934-80.878,14.331-111.052,33.785c-9.767-9.798-23.271-15.866-38.2-15.866
												C24.16,142.656,0,166.815,0,196.618c0,20.765,11.75,38.755,28.946,47.776c-1.306,6.68-1.993,13.51-1.993,20.462
												c0,77.538,84.126,140.395,187.901,140.395s187.901-62.857,187.901-140.395c0-6.948-0.687-13.775-1.991-20.452
												C417.961,235.381,429.709,217.385,429.709,196.618z M345.746,47.743c12,0,21.762,9.762,21.762,21.762
												c0,11.999-9.762,21.761-21.762,21.761s-21.762-9.762-21.762-21.761C323.984,57.505,333.747,47.743,345.746,47.743z M23.284,196.618
												c0-16.916,13.762-30.678,30.678-30.678c7.245,0,13.895,2.538,19.142,6.758c-16.412,14.08-29.118,30.631-37.007,48.804
												C28.349,215.937,23.284,206.868,23.284,196.618z M333.784,345.477c-31.492,23.53-73.729,36.489-118.929,36.489
												s-87.437-12.959-118.929-36.489c-29.462-22.013-45.688-50.645-45.688-80.621c0-29.977,16.226-58.609,45.688-80.622
												c31.492-23.53,73.729-36.489,118.929-36.489s87.437,12.959,118.929,36.489c29.462,22.013,45.688,50.645,45.688,80.622
												C379.471,294.832,363.246,323.464,333.784,345.477z M393.605,221.488c-7.891-18.17-20.596-34.716-37.005-48.794
												c5.247-4.22,11.901-6.754,19.147-6.754c16.916,0,30.678,13.762,30.678,30.678C406.424,206.867,401.353,215.925,393.605,221.488z"/>
											<g>
												<circle class="reddit" style="fill:#FFFFFF;" cx="146.22" cy="232.07" r="24.57"/>
												<circle class="reddit" style="fill:#FFFFFF;" cx="283.48" cy="232.07" r="24.57"/>
											</g>
											<path style="fill:#FFFFFF;" d="M273.079,291.773c-17.32,15.78-39.773,24.47-63.224,24.47c-26.332,0-50.729-10.612-68.696-29.881
												c-4.384-4.704-11.751-4.96-16.454-0.575c-4.703,4.384-4.96,11.752-0.575,16.454c22.095,23.695,53.341,37.285,85.726,37.285
												c29.266,0,57.288-10.847,78.905-30.543c4.752-4.33,5.096-11.694,0.765-16.446C285.197,287.788,277.838,287.44,273.079,291.773z"/>
											<g>
											</g>
										</svg>
									</a>
								</li>
								<li>
									<a href="https://www.twitch.tv/h1z1kotk" target="_blank">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 52 52" style="enable-background:new 0 0 52 52;" xml:space="preserve" width="512px" height="512px">
											<g>
												<g>
													<path class="twitch" d="M9.5,37h10v5c0,0.404,0.244,0.77,0.617,0.924C20.241,42.976,20.371,43,20.5,43c0.26,0,0.516-0.102,0.707-0.293L26.914,37    H38c0.265,0,0.52-0.105,0.707-0.293l6.5-6.5C45.395,30.02,45.5,29.766,45.5,29.5V6c0-0.553-0.448-1-1-1h-35c-0.552,0-1,0.447-1,1    v30C8.5,36.553,8.948,37,9.5,37z M10.5,7h33v22.086L37.586,35H26.5c-0.265,0-0.52,0.105-0.707,0.293L21.5,39.586V36    c0-0.553-0.448-1-1-1h-10V7z" fill="#FFFFFF"/>
													<path class="twitch" d="M50.5,0H5C4.644,0,4.315,0.189,4.136,0.496l-3.5,6C0.547,6.649,0.5,6.823,0.5,7v37c0,0.553,0.448,1,1,1h11v6    c0,0.553,0.448,1,1,1h7c0.247,0,0.485-0.092,0.669-0.257L28.662,45H38c0.251,0,0.493-0.095,0.677-0.264l12.5-11.5    c0.206-0.189,0.323-0.457,0.323-0.736V1C51.5,0.447,51.052,0,50.5,0z M49.5,32.062L37.61,43h-9.332    c-0.247,0-0.485,0.092-0.669,0.257L20.116,50H14.5v-6c0-0.553-0.448-1-1-1h-11V7.271L5.574,2H49.5V32.062z" fill="#FFFFFF"/>
													<path class="twitch" d="M21.5,28h4c0.552,0,1-0.447,1-1V14c0-0.553-0.448-1-1-1h-4c-0.552,0-1,0.447-1,1v13C20.5,27.553,20.948,28,21.5,28z     M22.5,15h2v11h-2V15z" fill="#FFFFFF"/>
													<path class="twitch" d="M31.5,28h4c0.552,0,1-0.447,1-1V14c0-0.553-0.448-1-1-1h-4c-0.552,0-1,0.447-1,1v13C30.5,27.553,30.948,28,31.5,28z     M32.5,15h2v11h-2V15z" fill="#FFFFFF"/>
												</g>
											</g>	
										</svg>
									</a>
								</li>
								<li>
									<a href="https://www.youtube.com/user/h1z1thegame" target="_blank">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 511.147 511.147" style="enable-background:new 0 0 511.147 511.147;" xml:space="preserve" width="512px" height="512px">
											<g transform="translate(1 1)">
												<g>
													<g>
														<path class="youtube" d="M505.027,146.2c0-1.707-5.12-39.253-21.333-57.173c-19.627-21.333-40.96-23.893-52.053-24.747h-3.413     c-69.12-5.12-172.373-5.973-174.08-5.973c-0.853,0-104.96,0.853-173.227,7.68h-2.56C66.413,66.84,45.08,69.4,26.307,90.733     C9.24,108.653,4.12,147.053,4.12,148.76c0,0-5.12,43.52-5.12,87.04v40.107c0,42.667,5.12,86.187,5.12,87.04     c0,1.707,5.12,39.253,21.333,57.173c17.92,20.48,39.253,23.04,52.907,24.747c2.56,0,4.267,0,6.827,0.853     c39.253,3.413,163.84,5.12,168.96,5.12c0.853,0,104.96,0,174.08-7.68h2.56c11.947-0.853,33.28-3.413,52.053-24.747     c17.067-17.92,22.187-56.32,22.187-58.027c0,0,5.12-43.52,5.12-87.04V233.24C510.147,190.573,505.027,147.053,505.027,146.2z      M493.08,275.053c0,42.667-5.12,85.333-5.12,85.333c-1.707,9.387-6.827,36.693-17.92,48.64     c-14.507,17.067-31.573,18.773-40.96,19.627h-2.56c-68.267,5.12-171.52,5.12-172.373,5.12s-128.853-1.707-166.4-5.973     c-2.56,0-5.12-0.853-7.68-0.853c-11.947-0.853-29.013-3.413-42.667-18.773c-10.24-11.093-16.213-38.4-17.067-47.787     c0-0.853-5.12-43.52-5.12-85.333v-40.107c0-42.667,5.12-85.333,5.12-85.333c0.853-9.387,6.827-36.693,17.067-48.64     C52.76,83.907,68.973,82.2,79.213,81.347h2.56c68.267-5.12,171.52-5.973,172.373-5.973s104.107,0.853,172.373,5.973h3.413     c9.387,0.853,26.453,2.56,40.96,19.627c10.24,11.947,15.36,39.253,17.067,48.64c0.853,1.707,5.12,44.373,5.12,85.333V275.053z" fill="#FFFFFF"/>
														<path class="youtube" d="M360.813,255.427l-162.133-94.72c-2.56-1.707-5.973-1.707-8.533,0s-4.267,4.267-4.267,7.68V353.56     c0,3.413,1.707,5.973,4.267,7.68c1.707,0.853,2.56,0.853,4.267,0.853s2.56,0,4.267-0.853l162.133-90.453     c2.56-1.707,4.267-4.267,4.267-7.68S363.373,257.133,360.813,255.427z M202.947,339.053v-156.16l136.533,80.213L202.947,339.053z     " fill="#FFFFFF"/>
													</g>
												</g>
											</g>
									</a>
								</li>
							</ul>
						</div><!-- ./end div col-md-12-->
					</div><!-- ./end div row-->
				</div><!-- ./end div container-->
			</div><!-- ./end social_network-->
		</section><!-- ./end section class footer-->
	</footer>
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>