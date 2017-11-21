<?php require_once 'include_connectBD.php'; ?>

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
<?php
switch($_SESSION['requete']) {
	default :
		break;
				
	case 1:
		echo '<form>Veuillez remplir tous les champs</form>';
		break;
		
	case 2:
		echo '<form>Les mots de passe ne correspondent pas</form>';
		break;
		
	case 3:
		echo '<form>Le mot de passe doit contenir au moins 4 caractères</form>';
		break;
		
	case 4:
		echo '<form>Cette adresse mail est déjà utilisée</form>';
		break;

	case 5:
		header('signup_verif');
		break;
		
	case 6:
		echo '<form>Format incorrect. Attention : la plupart des caractères spéciaux ne sont pas pris en compte !</form>';
		break;
} $_SESSION['requete'] = 0;
?>
		<form action="signup_verif.php" method="post">
		<table>
			<tr><td class="lab"> Prénom : </td><td><input type="text" name="prenom" class="textbox"></td></tr>
			<tr><td class="lab"> Nom : </td><td><input type="text" name="nom" class="textbox"></td></tr>
			<tr><td class="lab"> Email : </td><td><input type="text" name="email" class="textbox"></td></tr>
			<tr><td class="lab"> Mot de passe : </td><td><input type="password" name="mdp" class="textbox"></td></tr>
			<tr><td class="lab"> Confirmez mot de passe : </td><td><input type="password" name="mdpv" class="textbox"></td></tr>
		</table>
		<input type="submit" class="butt" value="Créer un compte"><a href="index.php" class="butt">Retour à l'accueil</a>
		</form>
	</main>
</body>
</html>