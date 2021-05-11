<?php
    session_start();
	require 'util.php';
    require 'connexion.php';
	
	//bouton déconnexion
	if(isset($_GET['action']) && !empty($_GET['action']) && $_GET['action'] == 'logout'){
		detruireSession();
		header('Location: index.php');
	}

	if (isset($_POST['creerEntreprise'])) {

		$bdd = connexionservermysql($server, $db, $login, $mdp);

		//Cryptage du mot de passe
		$mdp_provisoire = password_hash($_POST['mdpProvisoire'], PASSWORD_BCRYPT);

		$reqCreerEntreprise = $bdd->prepare('INSERT INTO
														entreprise(Mot_de_passe, Mail, NomEntr)
													VALUES
														(:mdp, :mail, :nom)');

		$reqCreerEntreprise->execute(array(	'mdp' 	=> $mdp_provisoire,
											'mail' 	=> $_POST['mailEntreprise'],
											'nom' 	=> $_POST['nomEntreprise']));

		/*envoiMailMdpProvisoireEntreprise(	$_POST['mailEntreprise'], 
											"Création de compte - Forum Stage", 
											$_POST['mdpProvisoire']);*/
	}
?>
<!DOCTYPE HTML>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Créer compte entreprise | Forum Stage</title>
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
		<h1>Créer un compte entreprise</h1>

		<form action="./creer_compte_entreprise.php" method="POST">

			<label>Entrez le nom de l'entreprise :</label>
			<input 	type="text" 
					name="nomEntreprise">

			<label>Entrez l'adresse e-mail de l'entreprise :</label>
			<input 	type="text" 
					name="mailEntreprise">
<?php
    		$mdpProvisoire = genererMdp(8);
?>
			<input 	type="text" 
					name="mdpProvisoire"
					value="<?php echo $mdpProvisoire; ?>">

			<button type="submit"
					name="creerEntreprise">
				Créer le compte
			</button>
		</form>
<?php
	}
?>
	</main>
</body>
</html>