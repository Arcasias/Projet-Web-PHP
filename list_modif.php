<?php require_once 'include_connectBD.php'; ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	// <title><?php echo $_POST['subtype']; ?></title>
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

if(isset($_POST['sub'])) {
	$sub = $_POST['sub'];
	$_POST['subtype'] = $sub;
}
if(!isset($_POST['game']) && $_POST['subtype'] != 'Ajouter') {
		$_SESSION['requete'] = 1;
		header('Location: list.php');
} else {
	if($_POST['subtype'] == 'Ajouter') {
		echo '<h1>Ajouter un jeu</h1>';
	} else {
		$game = $_POST['game'];
		echo '<h1>'.$_POST['subtype'].' '.$game.'</h1>
		<script>console.log("Type de requete : '.$_POST['subtype'].' - Jeu à affecter : '.$game.'");</script>';
	}
}
?>
	<form method="post">
<?php
function jeu() {
	$_SESSION['requete'] = 2;
	header('Location: list.php');
}
if(isset($sub)) {
	$_POST['subtype'] = $sub;
	if($sub == 'Ajouter') {
		if(!empty($_POST['jeu']) && !empty($_POST['genre']) && !empty(['studio'])) {
			$jeu = $_POST['jeu'];
			$genre = $_POST['genre'];
			$studio = $_POST['studio'];
			if(empty($_POST['image'])) {
				$image = 'jeu.png';
			} else { $image = $_POST['image']; }
			
			$req = $bd->prepare('SELECT * FROM jeux WHERE jeu = :jeu');
			$req->execute(array(':jeu' => $jeu));
			$result = $req->fetch();
			
			if($jeu != $result['jeu']) {
				$req = $bd->prepare('INSERT INTO jeux(jeu, genre, studio, image) VALUES (:jeu, :genre, :studio, :image)');
				$req->execute(array(
					':jeu' => $jeu,
					':genre' => $genre,
					':studio' => $studio,
					':image' => $image
				));
				jeu();
			} else { echo '<p>Ce jeu existe déjà</p>'; $_SESSION['requete'] = 9; }
		} else { echo '<p>Veuillez remplir tous les champs</p>'; $_SESSION['requete'] = 9; }
	}
	else if($sub == 'Modifier') {
		$req = $bd->prepare('SELECT * FROM jeux WHERE jeu = :jeu');
		$req->execute(array(':jeu' => $game));
		$result = $req->fetch();
		
		$jeu = $result['jeu'];
		$genre = $result['genre'];
		$studio = $result['studio'];
		$image = $result['image'];
		
		if(!empty($_POST['jeu']) && $_POST['jeu'] != $game) {
			$req = $bd->prepare('SELECT * FROM jeux WHERE jeu = :jeu');
			$req->execute(array(':jeu' => $_POST['jeu']));
			$result = $req->fetch();
			
			if(empty($result['jeu'])) { $jeu = $_POST['jeu']; }
			else { echo '<p>Ce jeu existe déjà</p>'; $_SESSION['requete'] = 9; }
		}
		if(!empty($_POST['genre'])) { $genre = $_POST['genre']; }
		if(!empty($_POST['studio'])) { $studio = $_POST['studio']; }
		if(!empty($_POST['image'])) {
			if(file_exists('src/'.$_POST['image'])) {
				$image = $_POST['image']; 
			} else { $image = 'jeu.png'; echo '<p>Chemin de l\'image incorrect</p>'; $_SESSION['requete'] = 9;  }
		} 
		
		if($_SESSION['requete'] == 0) {
			$req = $bd->prepare('UPDATE jeux SET jeu = :jeu, image = :image, genre = :genre, studio = :studio WHERE jeu = :game');
			$req->execute(array(
				':jeu' => $jeu,
				':image' => $image,
				':genre' => $genre,
				':studio' => $studio,
				':game' => $game
			));			
			jeu();
		}
	}
	else if($sub == 'Supprimer') {		
		if(isset($_POST['del'])) {			
			$req = $bd->prepare('DELETE FROM jeux WHERE jeu = :jeu');
			$req->execute(array(':jeu' => $game));
			jeu();
		}
	}
}
if($_POST['subtype'] != 'Supprimer') {
	echo '
		<table>
			<tr><td class="lab">Nom :</td><td><input type="text" name="jeu" class="textbox"></td></tr>
			<tr><td class="lab">Genre :</td><td><input type="text" name="genre" class="textbox"></td></tr>
			<tr><td class="lab">studio :</td><td><input type="text" name="studio" class="textbox"></td></tr>
			<tr><td class="lab">Image :</td><td><input type="text" name="image" class="textbox"></td></tr>
		</table>
		<input type="submit" class="butt" value="Enregistrer">
	'; }
else {
	echo '
		<p>Voulez-vous vraiment supprimer ce jeu ?</p>
		<input type="submit" class="butt" name="del" value="Confirmer">	
	';}
	
echo '<input type="hidden" name="sub" value="'.$_POST['subtype'].'">';
if ($_POST['subtype'] != 'Ajouter') { echo '<input type="hidden" name="game" value="'.$_POST['game'].'">'; } 
?>
		<a href="list.php" class="butt">Retour</a>
		</form>
	</main>
	<footer>
		Un problème ? Des suggestions ? Contactez-moi par mail à l'adresse julmougenot@gmail.com ou sur mon <a href="https://www.facebook.com/julien.mougenot.3">Facebook</a>.
	</footer>
</body>
</html>