<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<link rel="stylesheet" href="css/double_check.css">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<body>
<div>
<ul>
	<li>
		<p>Supprimer l'article ?</p>
	</li>
	<li class="link">
		<a href="supprimer.php?id=<?=$_GET['id']?>">
			<svg class="check" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 26" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 26 26" width="30px" height="30px">
	  			<path  style="fill:#386600;" d="m.3,14c-0.2-0.2-0.3-0.5-0.3-0.7s0.1-0.5 0.3-0.7l1.4-1.4c0.4-0.4 1-0.4 1.4,0l.1,.1 5.5,5.9c0.2,0.2 0.5,0.2 0.7,0l13.4-13.9h0.1v-8.88178e-16c0.4-0.4 1-0.4 1.4,0l1.4,1.4c0.4,0.4 0.4,1 0,1.4l0,0-16,16.6c-0.2,0.2-0.4,0.3-0.7,0.3-0.3,0-0.5-0.1-0.7-0.3l-7.8-8.4-.2-.3z"/>
			</svg>
		</a>
	</li>
	<li class="link">
		<a href="index.php">
			<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			 viewBox="0 0 28 28" style="enable-background:new 0 0 28 28;" xml:space="preserve" width="30px" height="30px">
				<polygon style="fill:#D80027;" points="28,22.398 19.594,14 28,5.602 22.398,0 14,8.402 5.598,0 0,5.602 8.398,14 0,22.398 
						5.598,28 14,19.598 22.398,28"/>
			</svg>
		</a>
	</li>
</ul>
</div>
</body>
</html>