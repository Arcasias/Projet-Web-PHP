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
	<main>	
<?php
if(isset($_POST['sub'])) {
	$sub = $_POST['sub'];
	$_POST['subtype'] = $sub;
}
if(!isset($_POST['getnote'])) {
	if($_POST['subtype'] != 'Ajouter') {
		$_SESSION['requete'] = 1;
		header('Location: cote.php');
	}
} else {
	$getemail = $_POST['getemail'];
	$getnote = $_POST['getnote'];
	$getjeu = $_POST['getjeu'];
	echo '<h1>'.$_POST['subtype'].' un commentaire</h1>
	<script>console.log("Type de requete : '.$_POST['subtype'].' - Le compte '.$getemail.' pour le jeu '.$getjeu.' avec la note '.$getnote.'");</script>';
}
?>
		<form method="post">
<?php
function cote() {
	$_SESSION['requete'] = 2;
	header('Location: cote.php');
}
if(isset($sub)) {
	$_POST['subtype'] = $sub;
	if($sub == 'Ajouter') {
		if(!empty($_POST['commentaire'])) {
			$email = $_POST['email'];
			$jeu = $_POST['jeu'];
			$note = $_POST['note'];
			$commentaire = $_POST['commentaire'];
			$comID = 0;
			
			$req = $bd->query('SELECT * FROM notes');
			while($result = $req->fetch()) {
				if($comID <= $result['comID']) { $comID = $result['comID'] + 1; }
			}
		
			$req = $bd->prepare('SELECT * FROM notes WHERE jeu = :jeu AND email = :email AND note = :note');
			$req->execute(array(
				':jeu' => $jeu, 
				':email' => $email, 
				':note' => $note, 
			));
			$result = $req->fetch();
			
			if($jeu == $result['jeu'] && $email == $result['email']) { echo '<p>Une note pour ce jeu existe déjà pour ce compte</p>'; $_SESSION['requete'] = 9; } 
			else { 
				$req = $bd->prepare('INSERT INTO notes(note, jeu, commentaire, email, comID) VALUES (:note, :jeu, :commentaire, :email, :comID)');
				$req->execute(array(
					':note' => $note,
					':jeu' => $jeu,
					':commentaire' => $commentaire,
					':email' => $email,
					':comID' => $comID
				));
				cote(); 
			}
		} else { echo '<p>Veuillez remplir tous les champs</p>'; $_SESSION['requete'] = 9; }
	}
	else if($sub == 'Modifier') {
		$req = $bd->prepare('SELECT * FROM notes WHERE jeu = :jeu AND email = :email AND note = :note');
		$req->execute(array(
			':jeu' => $getjeu, 
			':email' => $getemail, 
			':note' => $getnote, 
		));
		$result = $req->fetch();
		
		$email = $result['email'];
		$jeu = $result['jeu'];
		$note = $result['note'];
		$commentaire = $result['commentaire'];
		
		if(!empty($_POST['jeu'])) { $jeu = $_POST['jeu']; }
		if(!empty($_POST['email'])) { $email = $_POST['email']; }
		if(!empty($_POST['note'])) { $note = $_POST['note']; }
		if(!empty($_POST['commentaire'])) { $commentaire = $_POST['commentaire']; }
			
		$req = $bd->prepare('UPDATE notes SET jeu = :jeu, email = :email, note = :note, commentaire = :commentaire WHERE jeu = :getjeu AND email = :getemail AND note = :getnote');
		$req->execute(array(
			':jeu' => $jeu, 
			':email' => $email, 
			':note' => $note, 
			':commentaire' => $commentaire,
			':getjeu' => $getjeu,
			':getemail' => $getemail,
			':getnote' => $getnote,
		));			
		cote();
	}
	else if($sub == 'Supprimer') {		
		if(isset($_POST['del'])) {			
			$req = $bd->prepare('DELETE FROM notes WHERE jeu = :jeu AND email = :email');
			$req->execute(array(
				':jeu' => $getjeu, 
				':email' => $getemail
			));
			cote();
		}
	}	
}
if($_POST['subtype'] != 'Supprimer') {
	echo '<table>';
	if($_POST['subtype'] == 'Ajouter') {
		if($is_admin) { 
			echo'<tr><td class="lab"> Utilisateur : </td><td><select name="email" class="textbox"> ';
			$req = $bd->query('SELECT * FROM utilisateurs');
			while($result = $req->fetch()) {
				echo '<option value="'.$result['email'].'">'.$result['email'].'</option>';
			}echo '</select></td></tr>';
		}
		else { echo '<input type="hidden" name="email" value="'.$compte.'">'; }
		
		echo '<tr><td class="lab"> Jeu : </td>
				<td><select name="jeu" class="textbox"> ';

		$req = $bd->query('SELECT * FROM jeux');
		while($result = $req->fetch()) {
			echo '<option value="'.$result['jeu'].'">'.$result['jeu'].'</option>';
		}
		echo '</select></td></tr>';
	} else {
		$req = $bd->prepare('SELECT * FROM notes WHERE jeu = :jeu AND note = :note AND email = :email');
		$req->execute(array(
			':jeu' => $getjeu,
			':note' => $getnote,
			':email' => $getemail
		));
		$result = $req->fetch();
		echo '
		<input type="hidden" name="jeu" value="'.$result['jeu'].'">
		<input type="hidden" name="note" value="'.$result['note'].'">
		<input type="hidden" name="email" value="'.$result['email'].'">';
	}
	echo '<tr><td class="lab"> Cote : </td><td><select name="note" class="textbox">';
	for($i=0; $i<=20; $i++) {
		echo '<option value="'.$i.'">'.$i.'</option>';
	}
	echo '</select></td></tr>
			<tr><td class="lab"> Commentaire : </td><td><input type="text" name="commentaire" class="textbox"></td></tr>
		</table>
		<input type="submit" class="butt" value="Soumettre">';
} else {
	echo '
		<p>Voulez-vous vraiment supprimer cette note ?</p>
		<input type="submit" class="butt" name="del" value="Confirmer">';
} echo'<input type="hidden" name="sub" value="'.$_POST['subtype'].'">';
if ($_POST['subtype'] != 'Ajouter') { 
	echo '<input type="hidden" name="getnote" value="'.$_POST['getnote'].'">
	<input type="hidden" name="getemail" value="'.$_POST['getemail'].'">
	<input type="hidden" name="getjeu" value="'.$_POST['getjeu'].'">';
	
} 
?>
		<a href="cote.php" class="butt">Retour</a>
	</form>
	</main>
	<footer>
		Un problème ? Des suggestions ? Contactez-moi par mail à l'adresse julmougenot@gmail.com ou sur mon <a href="https://www.facebook.com/julien.mougenot.3">Facebook</a>.
	</footer>
</body>
</html>