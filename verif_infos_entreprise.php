<?php
    session_start();
	require 'util.php';
    require 'connexion.php';
	
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
	<title>Vérification informations entreprise | Forum Stage</title>
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
        <h1>Vérifier les informations des entreprises</h1>

		<label>Sélectionnez une entreprise :</label>
		<form 	action="./verif_infos_entreprise.php"
				method="POST">
			<select name="idEntreprise">
<?php
	$bdd = connexionservermysql($server, $db, $login, $mdp);
	//Requête : Génération liste des entreprises inscrites
	$reqListeEntreprise = $bdd->prepare('SELECT * 	FROM 
														entreprise
													ORDER BY 
														NomEntr');
	$reqListeEntreprise->execute();

	//Tant que toutes les entreprises ne sont pas affichées
	while ($data = $reqListeEntreprise->fetch()) {
?>				
				<!-- On affiche le médecin à la suite de la liste -->
				<option value="<?php echo $data['Id_entreprise']; ?>">
					<?php echo $data['NomEntr']; ?>
				</option>
<?php	
		}		 
?>
			</select>
			<button type="submit"
					name="afficherInfosEntr">
				Afficher			
			</button>
		</form>
<?php
	}

	if(isset($_POST['afficherInfosEntr']))
	{
	//Requête : Affichage des informations selon l'entreprise sélectionnée
		$reqListeEntreprise = $bdd->prepare('SELECT * 	FROM 
															entreprise, representant
														WHERE
															entreprise.Id_entreprise = representant.Id_entreprise
														AND
															entreprise.Id_entreprise = ?');
		$reqListeEntreprise->execute(array($_POST['idEntreprise']));
		$data = $reqListeEntreprise->fetch();
?>
		<p>Présentation de l'entreprise :</p>
		<p><?php echo $data['Presentation']; ?></p>

		<p>Représentant(s) de l'entreprise au forum :</p>

<?php
		//Requête : Compter le nombre de représentant afin d'afficher le bon nombre d'encart
		$reqListeRepresentants = $bdd->prepare('SELECT *	FROM 
															entreprise, representant
														WHERE 
															entreprise.Id_entreprise = representant.Id_entreprise
														AND
															entreprise.Id_entreprise = ?');

		$reqListeRepresentants->execute(array($_POST['idEntreprise']));

		while ($data = $reqListeRepresentants->fetch()) 
		{		
?>
		<p>Représentant n° X</p>

		<label>Nom :</label>
		<input 	type="text" 
				name="nomRepresentant"
				readonly="readonly"
				value="<?php echo $data['NomRepr']; ?>">

		<label>Prénom :</label>
		<input 	type="text" 
				name="prenomRepresentant"
				readonly="readonly"
				value="<?php echo $data['PrenomRepr']; ?>">

		<label>Numéro de téléphone :</label>
		<input 	type="text" 
				name="numTelRepresentant"
				readonly="readonly"
				value="<?php echo $data['Telephone']; ?>">

		<label>Disponibilité :</label>
		<label>De :</label>
		<input 	type="text" 
				name="heureDispoMini"
				readonly="readonly"
				value="<?php echo $data['Debut_dispo']; ?>">
		<label>A :</label>
		<input 	type="text" 
				name="heureDispoMaxi"
				readonly="readonly"
				value="<?php echo $data['Fin_dispo']; ?>">
<?php
		}
	}
?>
	</main>
</body>
</html>