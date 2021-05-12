<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta 	charset="utf-8">
	<link 	rel="stylesheet" 
			type="text/css" 
			href="styleHeader.css">
</head>
<body>
	<header class="headerGlobal">

		<div class="retourAccueil">
			<!-- HEADER : COMMUN -->
			<a href="./accueil_connecte.php">
				<div class="logoHeader">
					<img src="./ressources/logoIUTPetit.png">
				</div>
			</a>
		</div>

		<div class="headerFondRouge">
			
			<div class="itemHeader">
				<a href="./accueil_connecte.php">	
					Planning
				</a>
			</div>

			<div class="itemHeader">
				<a href="./chercher_stage.php">
					Cherche un stage
				</a>
			</div>

			<div class="itemHeader">
				<a href="./liste_entreprises.php">
					Entreprises présentes
				</a>
			</div>
		</div>

		<div class="headerFondNoir">
<?php
	// HEADER : ADMINISTRATEUR
	if($_SESSION['userType'] == 'administrateur'){
?>
			<div>
				<a href="./espace_admin.php">
					<img src="./ressources/logoVotreEspace.png">
				</a>
			</div>

<?php
	// HEADER : ETUDIANT
	}elseif ($_SESSION['userType'] == 'étudiant') {
?>
			<div>
				<a href="./espace_etudiant.php">
					<img src="./ressources/logoVotreEspace.png">
				</a>
			</div>
<?php
	// HEADER : ENTREPRISE
	}elseif ($_SESSION['userType'] == 'entreprise') {
?>
			<div>
				<a href="./espace_entreprise.php">
					<img src="./ressources/logoVotreEspace.png">
				</a>
			</div>
<?php
	}
?>
			<div>
				<a href="./index.php">
					<img src="./ressources/logoDeconnexion.png">
				</a>
			</div>
		</div>
	</header>
</body>
</html>