<?php require_once 'include_connectBD.php'; ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Jeux</title>
	<script src="jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php echo '<script>menu = "jeux"</script>'; ?>
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
		<form method="post">
			<div id="searchbar">
				<div class="searchattr">Trier par :</div>
				<select name="tri" id="tri" class="searchattr">
				<option value="jeu">Nom</option>
				<option value="genre">Genre</option>
				<option value="studio">studio</option>
				</select>
				<img id="ascdesc" class="searchattr" src="src/downarrow.png" alt="">
				<input type="radio" id="radascdesc" name="radascdesc">
				<input type="submit" id="searchbutt" class="searchattr" value="rechercher">
			</div>
		</form>
		<form action="list_modif.php" method="post">
<?php
switch($_SESSION['requete']) {
	case 1:
		echo '<p>Veuillez sélectionner un jeu</p>';
		break;
	case 2:
		echo '<p>Opération effectuée avec succès</p>';
		break;
}$_SESSION['requete'] = 0;
?>
		<table>
<?php

if(isset($_POST['tri'])) {
	$recherche = $_POST['tri'];
} else $recherche = 'jeu';

if(isset($_POST['radascdesc'])) {
	if($_POST['radascdesc']) {
		$sorting = 'ASC';
	} else { $sorting = 'DESC'; }
} else { $sorting = 'ASC'; }

$req = $bd->query('SELECT * FROM jeux ORDER BY '.$recherche.' '.$sorting);
$id = 0;
while($result = $req->fetch()) {
	$note = $bd->prepare('SELECT * FROM notes, utilisateurs  WHERE utilisateurs.email = notes.email AND jeu = :jeu');
	$note->execute(array(':jeu' => $result['jeu']));
	$i = $avg = 0;
	while($cotes = $note->fetch()) {
		$avg += $cotes['note'];
		${'prenom'.$i} = $cotes['prenom'];
		${'nom'.$i} = $cotes['nom'];
		${'note'.$i} = $cotes['note'];
		${'comm'.$i} = $cotes['commentaire'];
		$i++;
	} if ($avg>0) { $avg = round($avg/$i, 1); }
	else { $avg = '--'; }
	
	echo '<tr><td class="game" id="g'.$id.'"><span><img src="src/'.$result['image'].'" class="gamepic"><div class="gamename">'.$result['jeu'].'
	<span class="gamenote">'.$avg.'/20</span></div></span>
	<div class="gameattr">'.$result['genre'].'</div>
	<div class="gameattr"> '.$result['studio'].'</div>
	<input type="radio" id="r'.$id.'" name="game" value="'.$result['jeu'].'">
	</td></tr><tr><td class="infocont"><div class="info" id="inf'.$id.'">';
	
	for($x=0; $x<$i; $x++) {
		echo '<div>'.${'prenom'.$x}.' '.${'nom'.$x}.' - '.${'note'.$x}.'/20</div>
			<div>'.${'comm'.$x}.'</div><hr>';
	}
	echo '</div></td></tr>';
	$id++; }
?>
		</table>
<?php
if($is_admin) {
	echo '
		<input type="submit" class="butt" name="subtype" value="Ajouter">
		<input type="submit" class="butt" name="subtype" value="Modifier">
		<input type="submit" class="butt" name="subtype" value="Supprimer">
	'; }
?>
		</form>
	</main>
	<footer>
		Un problème ? Des suggestions ? Contactez-moi par mail à l'adresse julmougenot@gmail.com ou sur mon <a href="https://www.facebook.com/julien.mougenot.3">Facebook</a>.
	</footer>
<script src="navBulle.js"></script>
</body>
<script>
selectedid = '';

$(window).ready(function() {
	$('#radascdesc').prop('checked', true);
});

$('#ascdesc').click( function(){
	if($('#radascdesc').is(":checked")) {
		$('#radascdesc').prop('checked', false);
		$('#ascdesc').attr('src', "src/uparrow.png");
	} else { 
		$('#radascdesc').prop('checked', true); 
		$('#ascdesc').attr('src', "src/downarrow.png");		
	}
});

$('.game').click(function() {
	$('.game').removeClass('gameselect');
	$('.info').removeClass('show');
	$('.gamepic').css({'height': '150px'});
	
	selectedid = $(this).attr("id").substring(1, 9);
	$('#r'+selectedid).prop('checked', true);
	$('#g'+selectedid).addClass('gameselect');
	$('#inf'+selectedid).addClass('show');
	$(this).find('.gamepic').css({'height': '170px'});
});

$('.game').mouseenter(function() {
	$(this).addClass('gameselect');
	$(this).find('.gamepic').css({'height': '170px'});
});

$('.game').mouseleave(function() {
	if($(this).attr("id") != 'g'+selectedid) {
		$(this).removeClass('gameselect');
		$(this).find('.gamepic').css({'height': '150px'});
	}	
});
</script>
<script src="navBulle.js"></script>
</html>