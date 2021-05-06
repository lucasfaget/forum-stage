<?php

	session_start();

	require('connexionbdd.php');

	//Récupérer et vérifier si idstage n'est pas null
	if(isset($_GET['idstage'])) {

		$bdd = connexionservermysql($server, $db, $login, $mdp);

		//Requêtes pour récupérer les données concernant idstage et les afficher sur le formulaire
        $sql1 = 'SELECT * FROM Stage WHERE Id_stage = ?';

        $req1 = $bdd->prepare($sql1);

		$req1->execute(array($_GET['idstage']));

		$data = $req1->fetch();

		$intitulestage = $data['Intitule'];
 		$dureestage = $data['Duree'];
 		$nombreetudiants = $data['Nombre_postes'];
 		$descriptionstage = $data['Description'];
 		$motcles = $data['Mot_cles'];
 		$competences = $data['Competence_requise'];
		$idstage= $_GET['idstage'];

	}

	// Vérifier que tous les champs soit remplis et que le bouton enregistrer ne soit pas null
	if(isset($_POST['Enregistrer']) && !empty($_POST['intitulestage']) && !empty($_POST['dureestage']) && !empty($_POST['nombreetudiants']) && !empty($_POST['descriptionstage']) && !empty($_POST['motcles']) && !empty($_POST['competences'])) {

		$bdd = connexionservermysql($server, $db, $login, $mdp);

		$intitulestage = $_POST['intitulestage'];
		$dureestage = $_POST['dureestage'];
		$nombreetudiants = $_POST['nombreetudiants'];
		$descriptionstage = $_POST['descriptionstage'];
		$motcles = $_POST['motcles'];
		$competences = $_POST['competences'];
		$idstage = $_POST['idstage'];

		// Requête de mise à jour des données
		$sql2 = 'UPDATE Stage SET Intitule = :intitule, Description = :description, Duree = :duree, Nombre_postes = :nombrepostes, Competence_requise = :competencerequise, Mot_cles = :motcles WHERE Id_stage = :idstage';

		$req2 = $bdd->prepare($sql2);

		$exec = $req2->execute(array('intitule'=>$intitulestage, 'description'=>$descriptionstage, 'duree'=>$dureestage, 'nombrepostes'=>$nombreetudiants, 'competencerequise'=>$competences, 'motcles'=>$motcles, 'idstage'=>$idstage));

		// Vérifier la requête et redirection
		if($exec) {
			echo "La modification a bien été enregistrée.";
			header('Location: affichagestage.php');
			//exit();
		} else {
			echo "Échec de la modification.";
		}

	}

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
  		echo 'Le champ description doitêtre rempli.<br/><br/>';
	}

	if(isset($_POST['Enregistrer']) && empty($_POST['motcles'])) {
  		echo 'Le champ mot-clés doit être rempli.<br/><br/>';
	}

	if(isset($_POST['Enregistrer']) && empty($_POST['competences'])) {
  		echo 'Le champ compétences doit être rempli.';
	}
?>

<!DOCTYPE html>
<html>

  <head>
    <title>Modifier stage</title>
  </head>

  <body>

    <h1>Modifier stage</h1>

    <form action="<?php echo "modificationstage.php?idstage=".$idstage; ?>" method="POST">

    	<input type='hidden' name='idstage' value="<?php echo $idstage; ?>">

      	<label>Intitulé du stage : </label>
      	<p><input type="text" name="intitulestage" value="<?php echo $intitulestage; ?>"></p>

      	<label>Durée du stage (en semaine) : </label>
      	<p><input type="text" name="dureestage" value="<?php echo $dureestage; ?>"></p>

      	<label>Nombre d'étudiants acceptés pour ce stage : </label>
      	<p><input type="text" name="nombreetudiants" value="<?php echo $nombreetudiants; ?>"></p>

      	<label>Description du stage : </label>
      	<p><input type="text" name="descriptionstage" value="<?php echo $descriptionstage; ?>"></p>

      	<label>Mot-clés : </label>
      	<p><input type="text" name="motcles" value="<?php echo $motcles; ?>"></p>

      	<label>Compétences clés : </label>
      	<p><input type="text" name="competences" value="<?php echo $competences; ?>"></p>

      	<p><input type="submit" name="Enregistrer" value="Enregistrer les modifications"></p>

    </form>

  </body>

</html>