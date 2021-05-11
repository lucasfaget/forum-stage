<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta 	charset="utf-8">
	<link 	rel="stylesheet" 
			type="text/css" 
			href="">
</head>
<body>
	<header class="headerGlobal">

		<!-- HEADER : COMMUN -->
		<div>
			<a href="./accueil_connecte.php">Planning</a>
		</div>

		<div>
			<a href="./chercher_stage.php">Cherche un stage</a>
		</div>

		<div>
			<a href="./liste_entreprises.php">Entreprises présentes</a>
		</div>

<?php
	// HEADER : ADMINISTRATEUR
	if($_SESSION['userType'] == 'administrateur'){
?>
		<div>
			<a href="./espace_admin.php">Votre Espace</a>
		</div>

<?php
	// HEADER : ETUDIANT
	}elseif ($_SESSION['userType'] == 'étudiant') {
?>
		<div>
			<a href="./espace_etudiant.php">Votre Espace</a>
		</div>
<?php
	// HEADER : ENTREPRISE
	}elseif ($_SESSION['userType'] == 'entreprise') {
?>
		<div>
			<a href="./espace_entreprise.php">Votre Espace</a>
		</div>
<?php
	}
?>
		<div>
			<a href="./index.php">Se déconnecter</a>
		</div>
	</header>
</body>
</html>