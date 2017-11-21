<?php
require_once 'include_connectBD.php';

function signup($x) {	
	$_SESSION['requete'] = $x;
	header('Location: signup.php');
}

if($_SESSION['requete'] != 5) {

	if(!empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['mdp']) && !empty($_POST['mdpv'])) {

		$prenom = $_POST['prenom'];
		$nom = $_POST['nom'];
		$email = $_POST['email'];
		$mdp = $_POST['mdp'];
		$mdpv = $_POST['mdpv'];

		if ($mdp == $mdpv) {
			if (strlen($mdp)>=4) {
				$req = $bd->prepare('SELECT * FROM utilisateurs WHERE email = :email');
				$req->execute(array(':email' => $email));
				$result = $req->fetch();

				if (!empty($result)) { signup(4); }
				
				$chars = '/[";:!?$(){}<>=+]/';
				$charmdp = '/[";:(){}<>=+]/';
				if(preg_match($chars, $email) 
					|| preg_match($chars, $nom)	
					|| preg_match($chars, $prenom)
					|| preg_match($charmdp, $mdp)) { signup(6); }

				$rng = rand(1000, 9999);
				echo $rng.'<br>';
				
				$req = $bd->prepare('INSERT INTO utilisateurs(prenom, nom, email, mdp, activ) VALUES(:prenom, :nom, :email, :mdp, :activ)');
				$req->execute(array(
					':prenom' => $prenom,
					':nom' => $nom,
					':email' => $email,
					':mdp' => $mdp,
					':activ' => $rng
				));

				/*
				mail($email, "Code d'activation", "Voici votre code d'activation : " . $rng);
				echo 'Un mail contenant un code de confirmation a été envoyé à l\'adresse ' . $email . '<br>';
				*/		
				
				$_SESSION['compte'] = $email;
			} else { signup(3); }
		} else { signup(2); }
	} else { signup(1); }
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Inscription</title>
	<script src="jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<header>
		<img src="src/header.png" href="index.php">
	</header>
	<main>
		<form action="account_created.php" method="post">
		<p>Veuillez activer votre compte en entrant le code envoyé par mail</p>
		<table> 
			<tr><td class="lab">Code : </td><td><input type="text" name="code" class="textbox"></td></tr>
		</table>
		<input type="submit" value="Confirmer" class="butt">
		</form>
	</main>
</body>
</html>
