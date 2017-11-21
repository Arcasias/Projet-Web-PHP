<?php require_once 'include_connectBD.php'; ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Coter</title>
	<script src="jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php echo '<script>menu = "coter"</script>'; ?>
	<header>
		<img src="src/header.png" href="index.php">
	</header>
	<div id="bulle"></div>
	<nav class="menubar">
		<ul><li><a href="index.php" class="menu" id="accueil">Accueil</a></li>
			<li><a href="list.php" class="menu" id="jeux">Jeux</a></li>
			<li><a href="cote.php" class="menu" id="coter">Coter</a></li>
			<li><a href="account.php" class="menu" id="compte">Compte</a></li>
			<li><a href="logout.php" class="menu" id="deconnexion">Déconnexion</a></li></ul>
	</nav>
	<main>	
	<form action="cote_modif.php" method="post">
<?php
switch($_SESSION['requete']) {
	case 1:
		echo '<p>Veuillez sélectionner une note</p>';
		break;
	case 2:
		echo '<p>Opération effectuée avec succès</p>';
		break;
}$_SESSION['requete'] = 0;
?>
		<table>
<?php

if($is_admin) {
	$req = $bd->query('SELECT * FROM notes');
} else {
	$req = $bd->prepare('SELECT * FROM notes WHERE email = :email ORDER BY email ASC');
	$req->execute(array(':email' => $compte));
}
	
$index = 0;

while($result = $req->fetch()) {	
	echo '<tr><td class="game" id="c'.$index.'"><span><div class="gamename">'.$result['jeu'].'
	<span class="gamenote">'.$result['note'].'/20</span></div></span>
	<div class="gameattr">'.$result['email'].'</div>
	<div class="gameattr"> '.$result['commentaire'].'</div>
	<input type="radio" class="r'.$index.'" name="getnote" value="'.$result['note'].'">
	<input type="radio" class="r'.$index.'" name="getemail" value="'.$result['email'].'">
	<input type="radio" class="r'.$index.'" name="getjeu" value="'.$result['jeu'].'">';
	$index++; 
}
?>
		</table>
		<input type="submit" class="butt" name="subtype" value="Ajouter">
		<input type="submit" class="butt" name="subtype" value="Modifier">
		<input type="submit" class="butt" name="subtype" value="Supprimer">
	</form>
	</main>
	<footer>
		Un problème ? Des suggestions ? Contactez-moi par mail à l'adresse julmougenot@gmail.com ou sur mon <a href="https://www.facebook.com/julien.mougenot.3">Facebook</a>.
	</footer>
<script src="navBulle.js"></script>
</body>
<script>
selectedid = '';

$('.game').click(function() {
	$('.game').removeClass('gameselect');
	$('.info').removeClass('show');
	$('.gamepic').css({'height': '150px'});
	
	selectedid = $(this).attr("id").substring(1, 9);
	$('.r'+selectedid).prop('checked', true);
	$('#c'+selectedid).addClass('gameselect');
	$('#inf'+selectedid).addClass('show');
	$(this).find('.gamepic').css({'height': '170px'});
});

$('.game').mouseenter(function() {
	$(this).addClass('gameselect');
	$(this).find('.gamepic').css({'height': '170px'});
});

$('.game').mouseleave(function() {
	if($(this).attr("id") != 'c'+selectedid) {
		$(this).removeClass('gameselect');
		$(this).find('.gamepic').css({'height': '150px'});
	}	
});
</script>
</html>