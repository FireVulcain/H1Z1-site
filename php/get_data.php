<?php 
session_start();
	include '../connect.php';
	if (isset($_POST['offset']) && isset($_POST['limit'])) {
		$limit = $_POST['limit'];
		$offset = $_POST['offset'];

		$sql = "SELECT * FROM articles ORDER BY date_time_publication DESC LIMIT {$limit} OFFSET {$offset} ";
		$data = mysqli_query($link,$sql);

		while ($row = mysqli_fetch_array($data)) {?>
		<?php 
			#Conversion date 
			$mysqldate = $row['date_time_publication'];
			$phpdate = strtotime( $mysqldate );
			$mysqldate = date( 'd-m-Y', $phpdate );
		?>
		<!-- Affichages articles dans la BDD-->
			<div class="trois_articles">
				<a href="article.php?id=<?= $row['id']?>" class="article_link">
		 			<img src="miniatures/<?= $row['id']?>.jpg" class="miniatures"/>
		 		</a>
		 		<p class="date"><?= $mysqldate ?></p>
		 		<h2>
			 		<a href="article.php?id=<?= $row['id']?>" class="article_link">
			 			<?= $row['titre']?>
			 		</a>
		 		</h2>
		 		<?php
				if(isset($_SESSION['username'])){
					if ($_SESSION['type'] == 1) { ?>
					 	<a href="redaction.php?edit=<?=$row['id']?>">Modifier</a> |
					 	<a href="double_validation.php?id=<?=$row['id']?>">Supprimer</a>
				<?php }} ?>
		 		<div class="intro_article">
		 			<?= $row['contenu'] ?>
		 		</div>
		 	</div><!-- ./end class trois_articles-->
		 	<hr>
		<?php } }?>