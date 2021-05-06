<?php
        session_start();
	require 'util.php';
	
	//bouton déconnexion
	if(isset($_GET['action']) && !empty($_GET['action']) && $_GET['action'] == 'logout'){
		detruireSession();
		header('Location: index.php');
	}
	
?>
<!DOCTYPE HTML>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Accueil | Forum Stage</title>
	</head>
	<body>	
		<h1>Page d'accueil</h1>

		<!--L'utilisateur doit être connecté pour voir cette page-->
		<?php if(estConnecte()): ?>

                <!--Message temporaire pour la démo-->
		<p> Bienvenue, vous vous êtes connecté avec <?= htmlspecialchars($_SESSION['userId']) ?>.
		Votre type de compte est <?= htmlspecialchars($_SESSION['userType']) ?>.</p>

		<!--Boutons de navigation temporaires (ils seront dans le header-->
		<nav>
			<ul>
				<li><a href="accueil_connecte.php">Planning</a></li>
				<li><a href="chercher_stage.php">Chercher un stage</a></li>
				<li><a href="liste_entreprises.php">Entreprises présentes</a></li>
				<?php if($_SESSION['userType'] == 'entreprise'):?>
				<li><a href="espace_entreprise.php">Votre espace</a></li>
				<?php elseif($_SESSION['userType'] == 'étudiant'):?>
				<li><a href="espace_etudiant.php">Votre espace</a></li>
				<?php elseif($_SESSION['userType'] == 'administrateur'):?>
				<li><a href="espace_admin.php">Votre espace</a></li>
                                <?php endif;?>
                                <li><a href="accueil_connecte.php?action=logout">Se déconnecter</a></li>
			</ul>
		</nav>
                <?php
                else:
                       echo "Merci de vous <a href='index.php'>connecter</a> pour voir cette page.";
                endif; ?>
	</body>
</html>