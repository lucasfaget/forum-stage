<?php
session_start();

	require('connexion.php');

	//Récupérer et vérifier si idstage n'est pas null
	if(isset($_GET['idstage'])) {

		$bdd = connexionservermysql($server, $db, $login, $mdp);

		//Requêtes pour récupérer les données concernant idstage et les afficher sur le formulaire
        $sql1 = 'SELECT * FROM stage WHERE Id_stage = ?';

        $req1 = $bdd->prepare($sql1);

		$req1->execute(array($_GET['idstage']));

		$data = $req1->fetch();

		$intitulestage = $data['Intitule'];
 		$dureestage = $data['Duree'];
 		$nombreetudiants = $data['Nombre_postes'];
 		$descriptionstage = $data['Description'];
 		$competences = $data['Competence_requise'];
		$idstage= $_GET['idstage'];

	}

	// Vérifier que tous les champs soit remplis et que le bouton enregistrer ne soit pas null
	if(isset($_POST['Enregistrer']) && !empty($_POST['intitulestage']) && !empty($_POST['dureestage']) && !empty($_POST['nombreetudiants']) && !empty($_POST['descriptionstage']) && !empty($_POST['competences'])) {

		$bdd = connexionservermysql($server, $db, $login, $mdp);

		$intitulestage = $_POST['intitulestage'];
		$dureestage = $_POST['dureestage'];
		$nombreetudiants = $_POST['nombreetudiants'];
		$descriptionstage = $_POST['descriptionstage'];
		$competences = $_POST['competences'];
		$idstage = $_POST['idstage'];

		// Requête de mise à jour des données
		$sql2 = 'UPDATE stage SET Intitule = :intitule, Description = :description, Duree = :duree, Nombre_postes = :nombrepostes, Competence_requise = :competencerequise WHERE Id_stage = :idstage';

		$req2 = $bdd->prepare($sql2);

		$exec = $req2->execute(array('intitule'=>$intitulestage, 'description'=>$descriptionstage, 'duree'=>$dureestage, 'nombrepostes'=>$nombreetudiants, 'competencerequise'=>$competences, 'idstage'=>$idstage));

		// Vérifier la requête et redirection
		if($exec) {
			header('Location: affichagestageentreprise.php');
			echo "La modification a bien été enregistrée.";
		} else {
			echo "Échec de la modification.";
		}
	}
?>

<!DOCTYPE html>
<html>

  <head>
    <title>Modifier stage</title>
    <meta charset="utf-8">
    <script src="https://kit.fontawesome.com/2dcde6ae9c.js" crossorigin="anonymous"></script>
  </head>

  <body>

    <h1>Modifier stage</h1>

    <form action="<?php echo "modifier_stage.php?idstage=".$idstage; ?>" method="POST">

    	<input type='hidden' name='idstage' value="<?php echo $idstage; ?>">

      	<label>Intitulé du stage : </label>
      	<p><input type="text" name="intitulestage" value="<?php echo $intitulestage; ?>" pattern="[A-Za-z0-9' -#+]+" minlength="2" maxlength="50"></p>

      	<label>Durée du stage (en semaine) : </label>
      	<p><input type="text" name="dureestage" value="<?php echo $dureestage; ?>" pattern="[0-9]+" minlength="1" maxlength="3"></p>

      	<label>Nombre d'étudiants acceptés pour ce stage : </label>
      	<p><input type="text" name="nombreetudiants" value="<?php echo $nombreetudiants; ?>" pattern="[1-9]+" minlength="1" maxlength="1"></p>

      	<label>Description du stage : </label>
      	<p><input type="text" name="descriptionstage" value="<?php echo $descriptionstage; ?>" pattern="[A-Za-z0-9' -#+]+" minlength="2" maxlength="50"></p>

      	<label>Compétences clés : </label>
      	<p><input type="text" name="competences" value="<?php echo $competences; ?>" pattern="[A-Za-z0-9' -#+]+" minlength="2" maxlength="50"></p>

      	<button type="submit" name="Enregistrer"><i class="fas fa-2x fa-check-square"></i><span>Valider</span></button>
	  	<a href="affichagestageentreprise.php"><i class="fas fa-2x fa-window-close"></i><span>Annuler</span></a>

    </form>
    <?php
		// Message d'erreur si ceratins champs nécessaires ne sont pas remplis
		if(isset($_POST['Enregistrer']) && empty($_POST['intitulestage'])) {
			echo 'Le champ intitulé doit être rempli.<br/><br/>';
		}

		if(isset($_POST['Enregistrer']) && empty($_POST['dureestage'])) {
	  		echo 'Le champ durée doit être rempli.<br/><br/>';
		}

		if(isset($_POST['Enregistrer']) && empty($_POST['nombreetudiants'])) {
	  		echo 'Le champ nombre d\'étudiants doit être rempli.<br/><br/>';
		}

		if(isset($_POST['Enregistrer']) && empty($_POST['descriptionstage'])) {
	  		echo 'Le champ description doit être rempli.<br/><br/>';
		}

		if(isset($_POST['Enregistrer']) && empty($_POST['competences'])) {
	  		echo 'Le champ compétences doit être rempli.';
		}
    ?>

  </body>

</html>