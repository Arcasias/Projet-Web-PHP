<?php require_once 'include_connectBD.php'; ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Modification</title>
	<script src="jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<header>
		<img src="src/header.png" href="index.php">
	</header>
	<main>
<?php
$_SESSION['requete'] = 0;

if (isset($_POST['sub'])) {
	$_POST['subtype'] = $_POST['sub'];
	$sub = $_POST['sub'];
}
if($_POST['subtype'] != 'Ajouter') {
	if(!isset($_POST['cpte'])) {
		$_SESSION['requete'] = 1;	
		header('Location: account.php');
	} else {		
		$cpte = $_POST['cpte']; 
		echo '<script>console.log("Type de requete : '.$_POST['subtype'].' - Compte a affecter : '.$cpte.'");</script>';
		
		if($is_admin) {
			$req = $bd->prepare('SELECT * FROM utilisateurs WHERE email = :compte');
			$req->execute(array(':compte' => $cpte));
			$result = $req->fetch();
			
			$prenom = $result['prenom'];
			$nom = $result['nom'];
			$email = $result['email'];
			$mdp = $result['mdp'];
		}
	}
}
function account() {
	$_SESSION['requete'] = 2;
	header('Location: account.php');
}
if (isset($sub)) {
	$_POST['subtype'] = $sub;
	
	if($sub == 'Ajouter') {
		if(!empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['mdp']) && !empty($_POST['mdpv'])) {
			if ($_POST['mdp'] == $_POST['mdpv']) {
				if (strlen($_POST['mdp'])>=4) {
					$req = $bd->prepare('SELECT * FROM utilisateurs WHERE email = :email');
					$req->execute(array(':email' => $_POST['email']));
					$result = $req->fetch();

					if (!empty($result)) { echo '<form>Cette adresse mail est déjà utilisée</form>'; }
					else {
						$req = $bd->prepare('INSERT INTO utilisateurs(prenom, nom, email, mdp, activ) VALUES(:prenom, :nom, :email, :mdp, :activ)');
						$req->execute(array(
							':prenom' => $_POST['prenom'],
							':nom' => $_POST['nom'],
							':email' => $_POST['email'],
							':mdp' => $_POST['mdp'],
							':activ' => "0"
						));
						account();
					}
				} else { echo '<form>Le mot de passe doit contenir au moins 4 caractères</form>'; }
			} else { echo '<form>Les mots de passe doivent être identiques</form>'; }
		} else { echo '<form>Veuillez remplir tous les champs</form>'; }	
	}
	
	if($sub == 'Modifier') {
		if(!empty($_POST['email']) && $_POST['email'] != $compte) {
			$req = $bd->prepare('SELECT * FROM utilisateurs WHERE email = :email');
			$req->execute(array(':email' => $_POST['email']));
			$result=$req->fetch();
			
			if (empty($result['email'])) { $email = $_POST['email']; }
			else { echo '<form>Cette adresse mail est déjà utilisée</form>'; $_SESSION['requete'] = 1; }		
		}
		if(!empty($_POST['prenom'])) { $prenom = $_POST['prenom']; }
		if(!empty($_POST['nom'])) { $nom = $_POST['nom']; }
		if(!empty($_POST['mdp'])) {
			if(!empty($_POST['mdpv'])) {
				if ($_POST['mdp'] == $_POST['mdpv']) {
					if(strlen($_POST['mdp'])>=4) {
						$mdp = $_POST['mdp'];
					}
					else { echo '<form>Le mot de passe doit contenir au moins 4 caractères</form>';  $_SESSION['requete'] = 1; }	
				} else { echo '<form>Les mots de passe doivent être identiques</form>';  $_SESSION['requete'] = 1; }
			} else { echo '<form>Veuillez confirmer votre mot de passe</form>'; $_SESSION['requete'] = 1; }
		}		
		if($_SESSION['requete'] == 0) {
			$req = $bd->prepare('UPDATE utilisateurs SET email = :email, prenom = :prenom, nom = :nom, mdp = :mdp WHERE email = :compte');
			$req->execute(array(
				':email' => $email,
				':prenom' => $prenom,
				':nom' => $nom,
				':mdp' => $mdp,
				':compte' => $cpte
			));
			if(!$is_admin) { $_SESSION['compte'] = $email; }
			account();
		}		
	}

	if($sub == 'Supprimer') {
		if(!empty($_POST['del'])) {
			if(strcasecmp($_POST['del'], 'EFFACER') == 0) {
				$req = $bd->prepare('DELETE FROM utilisateurs WHERE email = :compte');
				$req->execute(array(':compte' => $cpte));
				
				if(!$is_admin) { session_destroy(); header('Location: index.php'); }
				else { account(); }
			} else { echo '<form>Entrée incorrecte. Tapez "effacer" dans le champ ci-dessous</form>'; }
		} else { echo '<form>Veuillez remplir le champ</formm>'; }
	}
}
?>		
	<form action="account_modify" method="post">
<?php
if($_POST['subtype'] == 'Modifier') {
	echo '<h1>Modifier le compte '.$cpte.'</h1>';
}
if($_POST['subtype'] == 'Supprimer') {
	if(!$is_admin) { echo '
		<p> ATTENTION : cette action supprimera définitivement toutes vos informations sur ce site et il vous sera impossible de les récupérer.</p>
		<table><tr><td class="lab">Tapez "effacer" :</td><td><input type="text" name="del" class="textbox"></td></tr></table>
		<input type="submit" class="butt" value="Confirmer"><a href="account.php" class="butt">Retour</a>
	'; }
	else { echo '
		<p>Voulez-vous vraiment supprimer le compte '.$cpte.' ?</p>
		<input type="submit" class="butt" value="Effacer" name="del"><a href="account.php" class="butt">Retour</a>		
	';}
} else {
	echo '
		<table>
			<tr><td class="lab"> Prénom : </td><td><input type="text" name="prenom" class="textbox"></td></tr>
			<tr><td class="lab"> Nom : </td><td><input type="text" name="nom" class="textbox"></td></tr>
			<tr><td class="lab"> Email : </td><td><input type="text" name="email" class="textbox"></td></tr>
			<tr><td class="lab"> Mot de passe : </td><td><input type="password" name="mdp" class="textbox"></td></tr>
			<tr><td class="lab"> Confirmez mot de passe : </td><td><input type="password" name="mdpv" class="textbox"></td></tr>
		</table>
		<input type="submit" class="butt" value="Enregistrer"><a href="account.php" class="butt">Retour</a>
	';	
}
if ($_POST['subtype'] != 'Ajouter') { echo '<input type="hidden" name="cpte" value="'.$cpte.'">'; }
echo '<input type="hidden" name="sub" value="'.$_POST['subtype'].'">';
?>		
	</form>
	</main>
</body>
</html>