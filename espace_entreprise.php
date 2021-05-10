<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css" />
        <title>Fiche entreprise</title>
    </head>

    <body>
    
    	<header>

    		<img src="logo-iut-58px.png" alt="logo iut"/>

    		<div class="menu">

    		</div>

    		<nav>
    			<ul>
    				<li><a>Planning</a></li>
    				<li><a>Chercher un stage</a></li>
    				<li><a>Entreprise présentes</a></li>
    				<li><a class="lastItem">Votre espace</a></li>
    			</ul>
    		</nav>

    	</header>

    	<div class="titre">
    		<h1>Votre Espace Entreprise</h1><br>
    		Si vous étiez présent au forum l'année précédente, vos informations<br>
    		ont été conservées, vous pouvez les mettre à jour si besoin.
    	</div><br>

    	<hr>

    	<br><div class="infos_entreprise">
    		<form>

    			<strong>Présentation de l'entreprise :</strong><textarea name="pres_etp" class="presentation_entreprise"></textarea>

    			<strong>Ajouter un logo</strong> (jpg ou png) :<br><input type="text" name="logo_etp" value="image.png"/><br><br>

    			<strong>Représentants de l'entreprise au forum :</strong>
    			<div class="representant">
	    			<div class="representant1">
	    				<p>Représentant n°1</p><br>
	    				Nom :<br><input type="text" name="nom1"/><br>
	    				Prénom :<br><input type="text" name="prenom1"/><br>
	    				Numéro de téléphone :<br><input type="text" name="tel1"/><br>
	    				Disponibilités :<br><input type="text" name="dispo1"/>
	    			</div>

	    			<div class="representant2">
	    				<p>Représentant n°2</p><br>
	    				Nom :<br><input type="text" name="nom2"/><br>
	    				Prénom :<br><input type="text" name="prenom2"/><br>
	    				Numéro de téléphone :<br><input type="text" name="tel2"/><br>
	    				Disponibilités :<br><input type="text" name="dispo2"/>
	    			</div>

    			</div>
    			<div class="enregistrer">
    				<input type="submit" value="Enregistrer les informations" class="enregistrer"/>
    			</div>
    		
    	</div><br><br>

    	<hr>

    	<br>

    </body>
</html>




<!-- A VERIFIER CI DESSOUS ------------------------->
<?php
        session_start();
	require 'util.php';
        require 'config.php';
	
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
		<title>Votre Espace | Forum Stage</title>
	</head>
	<body>
		<!--L'utilisateur doit être connecté pour voir cette page-->
		<?php if(estConnecte() && $_SESSION['userType'] == 'entreprise'): ?>
                
                <h1>Espace Entreprise</h1>

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
                
                <hr>
		<h3>Présentation de l'entreprise</h3>
		<p>Contenu à venir....</p>
		
		<hr>
		<h3>Liste des stages proposés</h3>
		<p>*Vous ne pourrez attribuer les différents postes qu'après le forum.</p>
                
                <?php
                else:
                       echo "Merci de vous <a href='index.php'>connecter</a> pour voir cette page.";
                endif; ?>
	</body>
</html>