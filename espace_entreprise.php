<?php
	session_start();
	require 'util.php';
	require 'connexion.php';
	
	//bouton déconnexion
	if(isset($_GET['action']) && !empty($_GET['action']) && $_GET['action'] == 'logout')
	{
		detruireSession();
		header('Location: index.php');
	}

    if(isset($_POST['enregistrerInfos']))
    {
    	//Si un fichier a été uploadé
    	if(isset($_FILES['file']))
    	{
    		//nom temporaire pour manipulation
        	$tmpName 	= $_FILES['file']['tmp_name'];
       	 	//nom du fichier
        	$name 		= $_FILES['file']['name'];
        	//taille du fichier
        	$size 		= $_FILES['file']['size'];
        	//0 si le fichier ne comporte pas d'erreur
        	$error 		= $_FILES['file']['error'];

        	//vérifier l'extension du fichier
        	$tabExtension = explode('.', $name);
        	$extension = strtolower(end($tabExtension));
        	//Tableau des extensions que l'on accepte
        	$extensions = ['jpg', 'png', 'jpeg'];

        	//controler la taille du fichier
        	//Taille max que l'on accepte
        	$maxSize = 500000;

        	if(in_array($extension, $extensions) && $size <= $maxSize && $error == 0){
            	//avoir un nom unique pour chaque fichier stocké
            	$uniqueName = uniqid('', true);
            	//uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            	$file = $uniqueName.".".$extension;
            	//$file = 5f586bf96dcd38.73540086.jpg
            	move_uploaded_file($tmpName, './ressources/logos/'.$file);

            	$bdd = connexionservermysql($server, $db, $login, $mdp);

            	$reqLogo = $bdd->prepare('	UPDATE entreprise
            								SET
            									entreprise.NomLogo = ?
            								WHERE
            									entreprise.Id_entreprise = ?');
            	$execLogo = $reqLogo->execute(array($file,
            										$_POST['idEntreprise']));

            	//Vérifier si la requête d'insertion a réussi et redirection
            	if($execLogo){
                	echo "L'image a bien été enregistré.";
            	}else{
                	echo "Échec de l'enregistrement de l'image.";
            	}

        	} elseif ($size > $maxSize) {
            	echo "La taille du fichier est trop grande.</br> Elle ne doit pas dépasser 500Ko.";
            //vérifier s'il y a une erreur dans le fichier
        	} elseif ($error != 0) {
            	echo "Le fichier contient une erreur.";
        	} else {
            	echo "L'extension du fichier du logo n'est pas correct.";
        	}
    	}

    	//On met à jour les informations relatives à l'entreprise
    	$bdd = connexionservermysql($server, $db, $login, $mdp);
    	$reqModifInfosEntr = $bdd->prepare('UPDATE entreprise 	
    										SET
    											entreprise.Presentation  = ?,
    										WHERE
    											entreprise.Id_entreprise = ?');

    	$reqModifInfosEntr->execute(array(	$_POST['presentationEntreprise'],
    										$_POST['idEntreprise']));

    	//Si les informations du premier représentant sont toutes saisies
    	if(	!empty($_POST['nomRepr1']) &&
    		!empty($_POST['prenomRepr1']) &&
    		!empty($_POST['telRepr1']) &&
    		!empty($_POST['debutDispoRepr1']) &&
    		!empty($_POST['finDispoRepr1']))
    	{
    		//On vérifie d'abord si le représentant 1 existe déjà
    		$reqVerifRepr1 = $bdd->prepare('SELECT * 	FROM 
    														representant, entreprise 
    													WHERE 
    														representant.Id_entreprise = entreprise.Id_entreprise
    													AND 
    														representant.Id_representant = ?');
    		$reqVerifRepr1->execute(array($_POST['idRepr1']));
    		$reprExiste1 = $reqVerifRepr1->fetch();

    		//Si le représentant 1 existe
    		if($reprExiste1)
    		{
    			//On met à jour ses informations
	    		$reqModifInfosRepr1 = $bdd->prepare('	UPDATE representant
	    												SET
	    													representant.NomRepr = ?,
	    													representant.PrenomRepr = ?,
	    													representant.Telephone = ?,
	    													representant.Debut_dispo = ?,
	    													representant.Fin_dispo = ?
	    												WHERE
	    													representant.Id_representant = ?');
	    		$reqModifInfosRepr1->execute(array(	$_POST['nomRepr1'], 
	    											$_POST['prenomRepr1'], 
	    											$_POST['telRepr1'], 
	    											$_POST['debutDispoRepr1'],
	    											$_POST['finDispoRepr1'],
	    											$_POST['idRepr1']));
    		}else{
    			//Sinon on le crée
    			$reqCreationRepr1 = $bdd->prepare('	INSERT INTO 
    													representant(	NomRepr, 
    																	PrenomRepr, 
    																	Telephone, 
    																	Debut_dispo, 
    																	Fin_dispo, 
    																	Id_entreprise)
    												VALUES
    																(	:nom,
    																	:prenom,
    																	:telephone,
    																	:debutDispo,
    																	:finDispo,
    																	:idEntr)');

    			$reqCreationRepr1->execute(array(	'nom'		=> $_POST['nomRepr1'], 
    												'prenom'	=> $_POST['prenomRepr1'], 
    												'telephone'	=> $_POST['telRepr1'], 
    												'debutDispo'=> $_POST['debutDispoRepr1'], 
    												'finDispo'	=> $_POST['finDispoRepr1'], 
    												'idEntr'	=> $_POST['idEntreprise']));
    		}
    	}

    	//Si les informations du second représentant sont toutes saisies
    	if(	!empty($_POST['nomRepr2']) &&
    		!empty($_POST['prenomRepr2']) &&
    		!empty($_POST['telRepr2']) &&
    		!empty($_POST['debutDispoRepr2']) &&
    		!empty($_POST['finDispoRepr2']))
    	{
    		//On vérifie d'abord si le représentant 1 existe déjà
    		$reqVerifRepr2 = $bdd->prepare('SELECT * 	FROM 
    														representant, entreprise 
    													WHERE 
    														representant.Id_entreprise = entreprise.Id_entreprise
    													AND 
    														representant.Id_representant = ?');
    		$reqVerifRepr2->execute(array($_POST['idRepr2']));
    		$reprExiste2 = $reqVerifRepr2->fetch();

    		//Si le représentant 2 existe
    		if($reprExiste2)
    		{
    			//On met à jour ses informations
	    		$reqModifInfosRepr2 = $bdd->prepare('	UPDATE representant
	    												SET
	    													representant.NomRepr = ?,
	    													representant.PrenomRepr = ?,
	    													representant.Telephone = ?,
	    													representant.Debut_dispo = ?,
	    													representant.Fin_dispo = ?
	    												WHERE
	    													representant.Id_representant = ?');
	    		$reqModifInfosRepr2->execute(array(	$_POST['nomRepr2'], 
	    											$_POST['prenomRepr2'], 
	    											$_POST['telRepr2'], 
	    											$_POST['debutDispoRepr2'],
	    											$_POST['finDispoRepr2'],
	    											$_POST['idRepr2']));
    		}else{
    			//Sinon on le crée
    			$reqCreationRepr2 = $bdd->prepare('	INSERT INTO 
    													representant(	NomRepr, 
    																	PrenomRepr, 
    																	Telephone, 
    																	Debut_dispo, 
    																	Fin_dispo, 
    																	Id_entreprise)
    												VALUES
    																(	:nom,
    																	:prenom,
    																	:telephone,
    																	:debutDispo,
    																	:finDispo,
    																	:idEntr)');
    			$reqCreationRepr2->execute(array(	'nom'		=> $_POST['nomRepr2'], 
    												'prenom'	=> $_POST['prenomRepr2'], 
    												'telephone'	=> $_POST['telRepr2'], 
    												'debutDispo'=> $_POST['debutDispoRepr2'], 
    												'finDispo'	=> $_POST['finDispoRepr2'], 
    												'idEntr'	=> $_POST['idEntreprise']));
    		}
    	}
    }
?>

<!DOCTYPE html>
<html>
<head>
	<title>Espace Entreprise</title>
        <meta charset="utf-8">
        <script src="https://kit.fontawesome.com/2dcde6ae9c.js" crossorigin="anonymous"></script>
        <script type='text/javascript'>
            function confirmationSuppression(id) {
	            // Afficher une fenêtre avec les boutons ok ou annuler
	            // si ok appuyer
	            if( confirm("Etes-vous sûr de vouloir supprimer ce stage ?")) {
	                // on fait la redirection vers la page qui effectue l'action
	            document.location.replace("supprimer_stage.php?idstage="+id+"");
	            }else {
	                // ceci annulera l'action par defaut du lien
	                return false;
	            }
            }
        </script>
</head>
<body>
	<header>
<?php
	require ("header.php");
?>
	</header>

	<main>
   		<h1>Votre Espace Entreprise</h1>

   		<p>Si vous étiez présent au forum l'année précédente, vos informations ont été conservées, vous pouvez les mettre à jour si besoin.</p>

<?php
		$bdd = connexionservermysql($server, $db, $login, $mdp);
		$reqListeRepresentants = $bdd->prepare('SELECT * 	FROM
														 		entreprise
														 	WHERE
														 		entreprise.Mail = ?');
		$reqListeRepresentants->execute(array($_SESSION['userId']));
		$data = $reqListeRepresentants->fetch();

?>
    	<form action="./espace_entreprise.php" method="POST" enctype="multipart/form-data">
    		<input 	type="hidden" 
    				name="idEntreprise"
    				value="<?php echo $data['Id_entreprise']; ?>">
    		<label>Présentation de l'entreprise :</label>
    		<textarea 	name="presentationEntreprise" 
    					class="presentation_entreprise"><?php echo $data['Presentation']; ?></textarea>
    		</br>
    		</br>
<?php
		echo "<img src='./ressources/logos/".$data['NomLogo']."' width='100px' height='100px'></br>";
?>
    		<label for="file">Ajouter un logo (.jpg, .jpeg ou .png) :</label>
            <input type="file" name="file">

<?php
		$reqListeRepresentants = $bdd->prepare('SELECT * 	FROM
														 		representant, entreprise
														 	WHERE
														 		entreprise.Id_entreprise = representant.Id_entreprise
														 	AND
														 		entreprise.Mail = ?');
		$reqListeRepresentants->execute(array($_SESSION['userId']));

?>
			</br>
			</br>
    		<label>Représentants de l'entreprise au forum :</label>
	    	<table>
	    		<tr>
	    			<td>Nom 			</td>
	    			<td>Prénom			</td>
	    			<td>Numéro Téléphone</td>
	    			<td>Début dispo 	</td>
	    			<td>Fin dispo 		</td>
	    		</tr>
<?php
	$nb = 1;
	while ($nb != 3) 
	{
		$data = $reqListeRepresentants->fetch()
?>
				<tr>
					<td>
						<input 	type="hidden" 
								name="idRepr<?php echo $nb ?>"
								value="<?php echo $data['Id_representant']; ?>">
						<input 	type="text" 
								name="nomRepr<?php echo $nb ?>"
								value="<?php echo $data['NomRepr']; ?>">		</td>
	    			<td>
						<input 	type="text" 
								name="prenomRepr<?php echo $nb ?>"
								value="<?php echo $data['PrenomRepr']; ?>"> 	</td>
	    			<td>
						<input 	type="text" 
								name="telRepr<?php echo $nb ?>"
								value="<?php echo $data['Telephone']; ?>"> 		</td>
	    			<td>
						<input 	type="text" 
								name="debutDispoRepr<?php echo $nb ?>"
								value="<?php echo $data['Debut_dispo']; ?>"> 	</td>
	    			<td>
						<input 	type="text" 
								name="finDispoRepr<?php echo $nb ?>"
								value="<?php echo $data['Fin_dispo']; ?>"> 		</td>
				</tr>
<?php
		$nb++;
	}
?>					
			</table>
			<button type="submit"
					name="enregistrerInfos">
				Enregistrer les informations
			</button>
		</form>

		<h3>Liste des stages proposés :</h3>
		<p>*Vous ne pourrez attribuer les différents postes qu'après le forum.</p>

		<table>
			<tr>
				<td>Intitulé du stage</td>
				<td>Nombre de postes</td>
				<td colspan="2">Actions</td>
			</tr>
<?php
	$bdd = connexionservermysql($server, $db, $login, $mdp);
	$reqListeStage = $bdd->prepare('SELECT * 	FROM 
													stage, entreprise
												WHERE
													stage.Id_entreprise = entreprise.Id_entreprise
												AND
													entreprise.Mail = ?');
	$reqListeStage->execute(array($_SESSION['userId']));

	while ($data = $reqListeStage->fetch()) 
	{
?>
			<tr>
				<td><?php echo $data['Intitule']; ?>		</td>
				<td><?php echo $data['Nombre_postes']; ?>	</td>
				<td>
					<form 	action="./modifier_stage"
							method="POST">
						<button type="submit"
								name="modifStage">
							Modif.
						</button>
					</form>
				</td>
				<td>
					<form 	action="./supprimer_stage"
							method="POST">
						<button type="submit"
								name="supprStage">
							Suppr.
						</button>
					</form>
				</td>
			</tr>
<?php
	}
?>
		</table>
		<form 	action="./ajouter_stage"
				method="POST">
			<button type="submit"
					name="ajoutStage">
				Ajouter un stage
			</button>
		</form>
	</main>		
</body>
</html>