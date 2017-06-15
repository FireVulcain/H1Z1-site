<?php
session_start();
include 'connect.php';
$user_id = $_GET['id'];
$token = $_GET['token'];

$sql = "SELECT * FROM users WHERE id = $user_id";
$req =mysqli_query($link, $sql);
$user = mysqli_fetch_assoc($req);
if($user && $user['confirmation_token'] == $token){
	$sql= "UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = $user_id";
	mysqli_query($link,$sql);
    $erreur = "Votre compte a bien été validé, vous pouvez vous connecter";
}else{
	header('location:index.php');
}
if (isset($_POST['formconnexion'])) {
    $username = htmlspecialchars($_POST['username']);
    $salt = "rowsdslklvvndffkjndfkjndfskjdfgdkjngfjkdfngdjk";
    $mdpconnect = sha1($_POST['mdpconnect']).$salt;
    if (!empty($_POST['username']) && !empty($_POST['mdpconnect'])) {
        $reqmdp = "SELECT * FROM users WHERE  username = '$username' AND password = '$mdpconnect' AND confirmed_at IS NOT NULL";
            $res = mysqli_query($link, $reqmdp);
            if($res && mysqli_num_rows($res) == 1){

                $data = mysqli_fetch_assoc($res);

                $_SESSION['id'] = $data['id'];
                $_SESSION['username'] = $data['username'];
                $_SESSION['password'] = $data['password'];
                $_SESSION['type'] = $data['type'];
                header('location:index.php');
            }else{
                $erreur = "Vous vous êtes trompé dans vos identifants";
            }
    }else{
        $erreur = "Tous les champs doivent être complétés";
    }
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Connexion tChat</title>
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
                    <input class="form-control" type="text" name="username" autocomplete="off" value="<?php if(isset($username)) {echo $username;} ?>">    
                </div>            
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label for="password">MOT DE PASSE</label>
                   <input name="mdpconnect" class="form-control" type="password">             
                </div>            
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <input type="submit" class="btn btn-default regbutton" name="formconnexion" value="Connexion">
                </div>   
            </div> 
            <div class="row">
                <div class="col-md-6">
                    <a href="inscription.php">Pas encore de compte ?</a>
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