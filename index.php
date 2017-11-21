<?php require_once 'include_connectBD.php'; ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Accueil</title>
	<script src="jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php echo '<script>menu = "accueil"</script>'; ?>
	<header>
		<img src="src/header.png" href="index.php">
	</header>
	<div id="bulle"></div>
	<nav class="menubar">
		<ul><li><a href="index.php" class="menu" id="accueil">Accueil</a></li>
			<?php
			if($is_logged) {
				echo('
				<li><a href="list.php" class="menu" id="jeux">Jeux</a></li>
				<li><a href="cote.php" class="menu" id="coter">Coter</a></li>
				<li><a href="account.php" class="menu" id="compte">Compte</a></li>
				<li><a href="logout.php" class="menu" id="deconnexion">Déconnexion</a></li>
				');
			}
			else {
				echo '
				<li><a href="login.php" class="menu" id="connexion">Connexion</a></li>
				<li><a href="signup.php" class="menu" id="connexion">Inscription</a></li>
				';						
			}
			?></ul>
	</nav>
	<main>	
		<h1>Bienvenue sur Game Rating</h1>
		<span><img src="src/gerultz.png" id="gerultz">Game Rating est un site sur lequel vous pouvez consulter les avis des joueurs sur vos jeux préférés et les noter vous-même ! 
		<?php
		if($is_logged) {
			echo 'Allez sur la page "Jeux" pour voir les notes et les avis des utilisateurs ou allez sur la page "Coter" afin de nous donner votre avis sur un jeu ! Vous pouvez également gérer votre compte depuis la page "Compte".';
		} else {
			echo 'Pour ce faire, il vous suffit de créer un compte ou de vous connecter si vous en avez un sur la page adéquate.';
		}?></span>
		<p>Tout le personnel de développement de Game Rating vous souhaite une agréable navigation !</p>
	</main>
	<footer>
		Un problème ? Des suggestions ? Contactez-moi par mail à l'adresse julmougenot@gmail.com ou sur mon <a href="https://www.facebook.com/julien.mougenot.3">Facebook</a>.
	</footer>
<script src="navBulle.js"></script>
</body>
</html>