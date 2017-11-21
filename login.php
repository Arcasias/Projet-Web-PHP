<?php require_once 'include_connectBD.php'; ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Connexion</title>
	<script src="jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<header>
		<img src="src/header.png" href="index.php">
	</header>
	<main>
<?php
if(!empty($_POST['email']) && !empty($_POST['mdp'])) {	
	$email = $_POST['email'];
	$mdp = $_POST['mdp'];
	
	$req = $bd->prepare('SELECT * FROM utilisateurs WHERE email = :email');
	$req->execute(array(':email' => $email));
	$result = $req->fetch();
	
	if (!empty($result['email'])) {
		if ($result['mdp'] == $mdp) {	
			$_SESSION['compte'] = $email;
			if($result['activ'] == 0) {		
				header('Location: index.php');
			}
			else {
				$_SESSION['requete']=5;
				header('Location: signup_verif.php');
			}
		} else { echo '<form>Email ou mot de passe incorrect</form>'; }
	} else{ echo '<form>Aucun compte n\'est lié à cet email</form>'; }	
} else if(isset($_POST['sub'])) { echo '<form>Veuillez remplir tous les champs</form>'; }
?>
		<form method="post">
		<table>
			<tr><td class="lab"> Email : </td><td><input type="text" name="email" class="textbox"></td></tr>
			<tr><td class="lab"> Mot de passe : </td><td><input type="password" name="mdp" class="textbox"></td></tr>
		</table>
		<input type="submit" class="butt" name="sub" value="Se connecter"><a href="index.php" class="butt">Retour à l'accueil</a>
		</form>

	
	</main>
</body>
</html>