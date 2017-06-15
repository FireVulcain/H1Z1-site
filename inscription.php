<?php 
session_start();
include 'connect.php';
include 'functions.php';
if (!empty($_POST)) {
  $errors = array();
  $username = htmlspecialchars($_POST['username']);
  $email = $_POST['email'];
  $salt = "rowsdslklvvndffkjndfkjndfskjdfgdkjngfjkdfngdjk";
  $mdp = sha1($_POST['password']).$salt;
  $mdp2 = sha1($_POST['password2']).$salt;
  $token = str_random(60);
  if (empty($username) || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $errors = "Votre pseudo n'est pas valide";
    $erreur = "Votre pseudo n'est pas valide (alphanumérique)";
  }else{
    $reqpseudo = "SELECT id FROM users WHERE username = '". $username."'";
    $res =mysqli_query($link, $reqpseudo);
    if($res && mysqli_num_rows($res) > 0){
      $errors['username'] = 'Ce pseudo est déjà pris';
      $erreur = 'Ce pseudo est déjà pris';
    }
  }

  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Votre email n'est pas valide";
  } else{
    $reqemail = "SELECT id FROM users WHERE email = '". $email."'";
    $res =mysqli_query($link, $reqemail);
    if($res && mysqli_num_rows($res) > 0){
      $errors['email'] = 'Cet email est déjà utilisé pour un autre compte';
      $erreur = 'Cet email est déjà utilisé pour un autre compte';
    }
  }

  if (empty($mdp) || $mdp != $mdp2) {
    $errors['password'] = "Vous devez rentrer un mot de passe valide";
    $erreur = "Vous devez rentrer un mot de passe valide";
  }

  if (empty($errors)) {
    extract($_POST);
    $sql = "INSERT INTO users SET username ='$username',password ='$mdp',email ='$email', confirmation_token ='$token', type = 2";
    mysqli_query($link, $sql);
    $lastid = mysqli_insert_id($link);
    $headers =  'MIME-Version: 1.0' . "\r\n"; 
    $headers .= 'From: Your name <info@address.com>' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 
    mail($_POST['email'], 'Confirmation de votre compte', "Afin de valider votre compte merci de cliquez sur ce lien\n\nhttp://localhost/php/confirm.php?id=$lastid&token=$token",$headers);
    $erreur = "Vous avez recu un mail de validation";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Inscription tChat</title>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/register.css">
</head>
<body>
	<div class="container-fluid">
		<form action="" method="POST" class="register-form"> 
	      	<div class="row">      
	           	<div class="col-md-4">
	              	<label for="firstName">PSEUDO</label>
	               <input class="form-control" type="text" id="pseudo" name="username" autocomplete="off" value="<?php if(isset($pseudo)) {echo $pseudo;} ?>">    
	           	</div>            
	      	</div>
	      	<div class="row">
	            <div class="col-md-4">
	                <label for="email">EMAIL</label>
	                <input name="email" class="form-control" type="email">             
	            </div>            
        	</div>
	      	<div class="row">
	           	<div class="col-md-4">
	              	<label for="password">MOT DE PASSE</label>
	               <input name="password" class="form-control" type="password" id="mdp">             
	           	</div>            
	     	</div>
	      	<div class="row">
	           	<div class="col-md-6">
	              	<label for="password">CONFIRME MOT DE PASSE</label>
	               <input name="password2" class="form-control" type="password" id="mdp2" >             
	           	</div>            
	      	</div>
	      	<hr>
	      	<div class="row">
	           	<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
	           		<input type="submit" class="btn btn-default regbutton" name="forminscription" value="S'enregistrer">
	        	</div>   
	      	</div>
	      	<div class="row">
                <div class="col-md-6">
                    <a href="index.php">Revenir à l'accueil</a>
                </div>   
            </div> 
	      	<p class="message">
		    	<?php 
		    		if(isset($erreur)){
		    			echo $erreur;
		    		}
		     	?>       
	      	</p>   
	    </form>
	</div>

</body>
</html>