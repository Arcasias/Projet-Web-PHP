<?php
session_start();
session_destroy();
header('Location: index.php');
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Deconnexion</title>
	<script src="jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<header>
		<img src="src/header.png" href="index.php">
	</header>
	<main>
		<form>Déconnexion ...</form>
	</main>
</body>
</html>
