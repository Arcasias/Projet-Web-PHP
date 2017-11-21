<?php require_once 'include_connectBD.php'; ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Compte</title>
	<script src="jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php echo '<script>menu = "compte"</script>'; ?>
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
	<h1>Informations du compte</h1>
<?php 
if(!$is_admin) { echo '<p>Vous pouvez modifier vos informations en cliquant sur "modifier". Vous pouvez égalment supprimer votre propre compte en cliquant sur "supprimer".</p>'; }

switch($_SESSION['requete']) {
	case 1:
		echo '<form>Veuillez sélectionner un compte</form>';
		break;
	case 2:
		echo '<form>Operation réalisée avec succès</form>';
		break;
}$_SESSION['requete'] = 0;
?>
	<form action="account_modify.php" method="post">
		<table>
<?php	
if (!$is_admin) {
	
	$mdpl = '';
	for ($i = strlen($mdp); $i>0; $i--) { $mdpl = $mdpl.'•'; }
	
	echo '
		<tr><td class="lab">Email : </td><td class="lab">'.$email.'</td></tr>
		<tr><td class="lab">Prenom : </td><td class="lab">'.$prenom.'</td></tr>
		<tr><td class="lab">Nom : </td><td class="lab">'.$nom.'</td></tr>
		<tr><td class="lab">Mot de passe : </td><td class="lab" type="password">'.$mdpl.'</td></tr>
		<input type="hidden" name="cpte" value="'.$compte.'">
		';
}
else {
	$req = $bd->query('SELECT * FROM utilisateurs ORDER BY email ASC');
	$index = 0;
	echo '<tr><td class="colheader tablecell">Email</td>
		<td class="colheader">Prenom</td>
		<td class="colheader">Nom</td>
		<td class="colheader">Mot de passe</td></tr>';
	while ($result = $req->fetch()) {
		${'email'.$index} = $result['email'];
		${'prenom'.$index} = $result['prenom'];
		${'nom'.$index} = $result['nom'];
		${'mdp'.$index} = $result['mdp'];		
		
		echo '
			<tr class="select" id="row'.$index.'"><td>
			<input type="radio" name="cpte" id="r'.$index.'" value="'.${'email'.$index}.'">
			<label class="row" for="r'.$index.'">'.${'email'.$index}.'</label></td>
			<td><label class="row" for="r'.$index.'">'.${'prenom'.$index}.'</label></td>
			<td><label class="row" for="r'.$index.'">'.${'nom'.$index}.'</label></td>
			<td><label class="row" for="r'.$index.'">'.${'mdp'.$index}.'</td></tr>
		';
		$index++;
	}
}
?>
		</table>
		<?php if($is_admin) { echo '<a><input type="submit" class="butt" name="subtype" value="Ajouter"></a>'; } ?>
		<input type="submit" class="butt" name="subtype" value="Modifier">
		<input type="submit" class="butt" name="subtype" value="Supprimer">
	</form>
	</main>
	<footer>
		Un problème ? Des suggestions ? Contactez-moi par mail à l'adresse julmougenot@gmail.com ou sur mon <a href="https://www.facebook.com/julien.mougenot.3">Facebook</a>.
	</footer>
<script>
$('.row').click(function() {
	var x=0;
	$('.select').removeClass('selected');
	$('.row').removeClass('rowsel');
	
	var id = $(this).closest('.select').attr("id");
	$('#'+id).addClass('selected');
	$('#'+id+' > td > .row').addClass('rowsel');	
	$('#r'+id).prop('checked', true);
});

$('radio').change(function() {
	console.log($(this));
});
</script>
<script src="navBulle.js"></script>
</body>
</html>