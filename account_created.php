<?php
require_once 'include_connectBD.php';

$activ = $result['activ'];

if ($result['email'] == $compte) {
	if($activ == $_POST['code']) {		
		$_SESSION['requete'] = 0;	
		$req = $bd->prepare('UPDATE utilisateurs SET activ = 0 WHERE email = :email');
		$req->execute( array( ':email' => $compte ));			
	}
	else {
		$_SESSION['requete'] = 5;
		header('Location: signup_verif.php');
	}			
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Finalisation</title>
	<script src="jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<header>
		<img src="src/header.png" href="index.php">
	</header>
	<main>
		<form action="index.php">
		<div>Felicitation ! Votre compte est activé !</div>
		<input type="submit" value="Retour à l'accueil" class="butt">
		</form>
	</main>
</body>
</html>
