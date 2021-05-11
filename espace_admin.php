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
	<title>Espace administrateur | Forum Stage</title>
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
        <h1>Espace Administrateur</h1>
                
        <h2>Gestion des comptes</h2>
        <form action="./creer_compte_entreprise.php" method="POST">
        	<button type="submit"
        			name="creerCompteEntr">
        		Créer un compte entreprise
        	</button>
        </form>

        <form action="./verif_infos_entreprise.php" method="POST">
        	<button type="submit"
        			name="verifInfosEntr">
        		Vérifier les informations des entreprises
        	</button>
        </form>

        <form action="./liste_comptes_etudiants.php" method="POST">
        	<button type="submit"
        			name="listeComptesEtu">
        		Liste des comptes étudiants
        	</button>
        </form>

        <h2>Planning</h2>
        <form action="" method="POST">
        	<button type="submit"
        			name="exportPlanPDF">
        		Exporter le planning en PDF
        	</button>
        </form>

        <h2>Statistiques</h2>
		<form action="" method="POST">
        	<button type="submit"
        			name="exportPlanPDF">
        		Exporter les statistiques en CSV
        	</button>
        </form>
                
 <?php
    }else{
        echo "Merci de vous <a href='index.php'>connecter</a> pour voir cette page.";
    }
?>
    </main>
</body>
</html>