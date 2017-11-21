<?php
session_start();

date_default_timezone_set('Europe/Brussels');
$hote='localhost';
$nomBD='projetphp';	
//Les 2 champs ci-dessous sont à reconfigurer en cas de changement de login/mdp sur phpMyAdmin
$user='root';	
$mdp='root';
	
try
{
	$bd = new PDO('mysql:host='.$hote.';dbname='.$nomBD.';charset=utf8', $user, $mdp);
	$bd->exec('SET NAMES utf8');
	$bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}

if(!isset($_SESSION['requete'])) { $_SESSION['requete'] = 0; }
if(isset($_SESSION['compte'])) {
	if($_SESSION['compte'] != '0') {
		$compte = $_SESSION['compte'];
		
		$req = $bd->prepare('SELECT * FROM utilisateurs WHERE email = :compte');
		$req->execute(array(':compte' => $compte));
		$result = $req->fetch();
		
		$is_logged = true;
		$is_admin = ($compte == 'admin' ? true : false);
		
		if($is_admin) { echo '<script>console.log("Connecté en tant qu\'adminstrateur");</script>'; }
		else { 			
			$prenom = $result['prenom'];
			$nom = $result['nom'];
			$email = $result['email'];
			$mdp = $result['mdp'];
			
			echo '<script>console.log("Connecté en tant qu\'utilisateur à l\'adresse \"'.$compte.'\"");</script>';
		}
	}	
	else {
		$is_logged = false;
		echo '<script>console.log("Déconnecté : '.$_SESSION['compte'].'");</script>';		
	}
}
else {
	$_SESSION['compte'] = 0;
	header('Location: index.php');
}
?>