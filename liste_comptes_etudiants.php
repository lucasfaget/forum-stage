<?php
    session_start();
	require 'util.php';
    require 'connexion.php';
	
	//bouton déconnexion
	if(isset($_GET['action']) && !empty($_GET['action']) && $_GET['action'] == 'logout'){
		detruireSession();
		header('Location: index.php');
	}

	if (isset($_POST['supprCptesEtudiants'])) {
		
		$bdd = connexionservermysql($server, $db, $login, $mdp);
		$reqSupprCptesEtudiants = $bdd->prepare('DELETE FROM
															etudiant
														WHERE
															etudiant.Diplome = ?');
		$reqSupprCptesEtudiants->execute(array($_POST['diplomeASuppr']));
	}
?>
<!DOCTYPE HTML>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Liste des comptes étudiants | Forum Stage</title>
</head>
<body>
	<header>

<?php
	require ("header.php");
?>

	</header>
	<main>

<?php 
	if(	estConnecte() && 
		$_SESSION['userType'] == 'administrateur')
	{ 
?>            
        <h1>Liste des comptes Etudiants</h1>

        <h3>Suppression de comptes :</h3>

        <p>Sélectionnez un diplôme :</p>

        <form 	action="./liste_comptes_etudiants.php"
        		method="POST">
        	<select name="diplomeASuppr">
				<option>DUT Informatique - 1ère annee</option>
				<option>DUT Informatique - 2ème année</option>
				<option>DUT Informatique - Année spéciale</option>
				<option>Licence Pro - DQL</option>
				<option>Licence Pro - GTIDM</option>
			</select>
			<button type="submit"
					name="supprCptesEtudiants">
				Supprimer tous les comptes étudiants avec le diplôme sélectionné
			</button>
        </form>
<?php
    }
?>
    </main>
</body>
</html>