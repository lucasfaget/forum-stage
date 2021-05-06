<?php
	include ("config.php");
?>

<!DOCTYPE html>
<html>

<head>
	<title>Recherche</title>
</head>

<?php
	//Requêtes SQL visant à récupérer le titre du stage
	$reqFicheStage = $link->prepare('SELECT *	FROM 
													stage, entreprise, representant 
												WHERE 
													stage.Id_entreprise = entreprise.Id_entreprise 
												AND 
													stage.Id_stage = ?');

	$reqFicheStage->execute(array($_POST['idStage']));
	$data = $reqFicheStage->fetch();
?>

<body>

<header>
	
</header>

<main>
	<h1> <?php echo $data['Intitule']; ?></h1>

	<p>Organisme : <?php echo $data['NomEntr']; ?></p>
	<p><?php echo $data['Presentation']; ?></p>

	<p>Description : </p>
	<p><?php echo $data['Description']; ?> </p>

	<p>Compétence clé à développer : </p>
	<p><?php echo $data['Competence_requise']; ?> </p>

	<p>Durée : </p>
	<p><?php echo $data['Duree']; ?> </p>

	<p>Nombre de stagiaire(s) pour ce poste : </p>
	<p><?php echo $data['Nombre_postes']; ?> </p>

	<p>Représentant au Forum Stage : </p>
	<p><?php echo $data['NomRepr']." ".$data['PrenomRepr']; ?> </p>

	<form action="./XXXXXXXXXXX" method="POST">
		<input 	type="button"
				name="reserverCreneau"
				value="Réserver un créneau">
	</form>

</main>
	
</body>
