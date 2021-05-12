<?php
    session_start();
	require ("util.php");
    require ("connexion.php");
	
	//Bouton déconnexion <===== A METTRE DANS LE HEADER ?
	if(isset($_GET['action']) && !empty($_GET['action']) && $_GET['action'] == 'logout'){
		detruireSession();
		header('Location: index.php');
	}

	//Si un bouton de désinscription est appuyé, on supprime le créneau de la base
	if(isset($_POST['desinscrireStage'])){

		$bdd = connexionservermysql($server, $db, $login, $mdp);
		$reqDesinscrireCreneau = $bdd->prepare('DELETE 	FROM 
															reserver
														WHERE
															reserver.Id_stage = ?
														AND 
															reserver.Id_etudiant = ?');
		$reqDesinscrireCreneau->execute(array($_POST['idStage'], $_POST['idEtudiant']));
	}

	//Si le bouton de modification d'informations est appuyé, on met à jour la BDD
	if(isset($_POST['modifEtudiant'])){

		$bdd = connexionservermysql($server, $db, $login, $mdp);
		$reqModifEtudiant = $bdd->prepare('	UPDATE etudiant
											SET
												etudiant.Mail = ?,
												etudiant.NomEtu = ?,
												etudiant.PrenomEtu = ?,
												etudiant.Diplome = ?
											WHERE
												etudiant.Id_etudiant = ?');
		$reqModifEtudiant->execute(array(	$_POST['mailEtudiant'], 
											$_POST['nomEtudiant'], 
											$_POST['prenomEtudiant'], 
											$_POST['promoEtudiant'],
											$_POST['idEtudiant']));
	}
?>

<!DOCTYPE HTML>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Accueil | Forum Stage</title>
</head>
<body>
	<header>
<?php 
	require ("header.php");
?>
	</header>
<?php 
	if(estConnecte() && $_SESSION['userType'] == 'étudiant'){ 
?>
	<main>   

<?php
	$bdd = connexionservermysql($server, $db, $login, $mdp);
	$reqEspaceEtudiant = $bdd->prepare('SELECT * 	FROM 
														etudiant 
													WHERE 
														Mail = ?');
	$reqEspaceEtudiant->execute(array($_SESSION['userId']));
	$dataInfos = $reqEspaceEtudiant->fetch();
?>

    <h1>Votre Espace Etudiant</h1>

	<h3>Vos informations</h3>
	<form action="./espace_etudiant.php" method="POST">
		<input 	type="hidden" 
				name="idEtudiant"
				value="<?php echo $dataInfos['Id_etudiant']; ?>">

		<p>Nom :</p>
		<input 	type="text" 
				name="nomEtudiant"
				value="<?php echo $dataInfos['NomEtu']; ?>">

		<p>Prénom :</p>
		<input 	type="text" 
				name="prenomEtudiant"
				value="<?php echo $dataInfos['PrenomEtu']; ?>">

		<p>Adresse mail :</p>
		<input 	type="text" 
				name="mailEtudiant"
				value="<?php echo $dataInfos['Mail']; ?>">

		<p>Promotion :</p>
		<select name="promoEtudiant">
			<option selected value="<?php echo $dataInfos['Diplome']; ?>">
				<?php echo $dataInfos['Diplome']; ?>
			</option>
<?php
	if($dataInfos['Diplome'] != "DUT Informatique - 1ère année"){
?>
			<option>DUT Informatique - 1ère annee</option>
<?php
	}
	if($dataInfos['Diplome'] != "DUT Informatique - 2ème année"){
?>
			<option>DUT Informatique - 2ème année</option>
<?php
	}
	if($dataInfos['Diplome'] != "DUT Informatique - Année spéciale"){
?>
			<option>DUT Informatique - Année spéciale</option>
<?php
	}
	if($dataInfos['Diplome'] != "Licence Pro - DQL"){
?>
			<option>Licence Pro - DQL</option>
<?php
	}
	if($dataInfos['Diplome'] != "Licence Pro - GTIDM"){
?>
			<option>Licence Pro - GTIDM</option>
<?php
	}
?>
		</select>
		<button type="submit"
				name="modifEtudiant">
			Enregistrer les modifications
		</button>
	</form>

	<!------------------------------------------------------------------------------------------------>
<?php
	$reqListeStageEtu = $bdd->prepare('SELECT *	FROM 
													stage, reserver, etudiant, entreprise 
												WHERE
													entreprise.Id_entreprise = stage.Id_entreprise
												AND
													stage.Id_stage = reserver.Id_stage
												AND
													reserver.Id_etudiant = etudiant.Id_etudiant
												AND
													etudiant.Id_etudiant = ?');
	$reqListeStageEtu->execute(array($dataInfos['Id_etudiant']));
?>
	<p>Créneaux réservés :</p>

	<table>
		<tr>
			<td>Intitulé du stage</td>
			<td>Organisme</td>
			<td>Heures</td>
			<td>Se désinscrire</td>
		</tr>
<?php
	while ($dataStages = $reqListeStageEtu->fetch()) {
?>
		<tr>
			<td><?php echo $dataStages['Intitule']; ?>							</td>
			<td><?php echo $dataStages['NomEntr']; ?>							</td>
			<td><?php echo $dataStages['Creneau']; ?>							</td>
			<td>
				<form 	action="./espace_etudiant.php" 
						method="POST">
					<input 	type="hidden"
							name="idEtudiant"
							value="<?php echo $dataStages['Id_etudiant']; ?>">
					<input 	type="hidden" 
							name="idStage"
							value="<?php echo $dataStages['Id_stage']; ?>">
					<button type="submit" 
							name="desinscrireStage">
				 		Se désinscrire
				 	</button>
				</form>															</td>
		</tr>
<?php
	}
?>
	</table>  	        
<?php
    }else{
        echo "Merci de vous <a href='index.php'>connecter</a> pour accéder à cette page.";
    }
?>
	</main>
</body>
</html>