<?php
	include ("config.php");
?>

<!DOCTYPE html>
<html>

<head>
	<title>Recherche</title>
</head>

	<body>
		<h1> Je recherche un stage</h1>

		<!-- Section de recherche -->
		<section>
			<form method="POST">
		<!-- Recherche par durée (valeur basse)--------------------------------------------------------->
				<p>Durée minimum</p>
				<select name="dureeBot">
					<option selected></option>

				<?php
				//Requêtes SQL visant à générer les valeurs pour les différents critères
				$reqDureeBot = $link->prepare('SELECT DISTINCT * FROM Stage ORDER BY Duree');
				$reqDureeBot->execute();
				
			//----Bloc recherche durée fourchette basse	
				while ($data = $reqDureeBot->fetch()) {
				?>
					<option value="<?php echo $data['Duree']; ?>"> <?php echo $data['Duree']; ?> semaine(s)</option>

				<?php
				}
				?>
				</select>
	
		<!-- Recherche par durée (valeur haute)--------------------------------------------------------------->
				<p>Durée maximum</p>
				<select name="dureeTop">
					<option selected></option>
		<?php
			//Requête SQL
			$reqDureeTop = $link->prepare('SELECT DISTINCT * FROM Stage ORDER BY Duree');
			$reqDureeTop->execute();	

			while($data = $reqDureeTop->fetch()) {
		?>
					<option value="<?php echo $data['Duree']; ?>"> <?php echo $data['Duree']; ?> semaine(s)</option>
		<?php
			}
		?>
				</select>

		<!-- Recherche par compétence--------------------------------------------------------------------------->
				<p>Compétence requise</p>
				<select name="competence">
					<option selected></option>
		<?php
			//Requête SQL
			$reqCompetence = $link->prepare('SELECT DISTINCT * FROM Stage');
			$reqCompetence->execute();

			while($data = $reqCompetence->fetch()) {
		?>
					<option> <?php echo $data['Competence_requise']; ?></option>
		<?php
			}
		?>
				</select>

		<!-- Recherche par nom d'organisme----------------------------------------------------------------------->
				<p>Nom de l'organisme</p>
				<select name="nomOrganisme">
					<option selected></option>
		<?php
			//Requête SQL
			$reqOrganisme = $link->prepare('SELECT DISTINCT * FROM Entreprise');
			$reqOrganisme->execute();

			while($data = $reqOrganisme->fetch()) {
		?>
					<option> <?php echo $data['NomEntr']; ?></option>
		<?php
			}
		?>
				</select>
				<input type="submit" name="rechercher" value="Rechercher"> 
			</form>
		</section>

		<?php

		if(isset($_POST['rechercher'])){	

		?>
			<section>
		<?php

			//Texte constituant la requête SQL qui sera incrémenté en fonction des critères choisis
			$texteRequete = 'SELECT DISTINCT entreprise.Id_entreprise, entreprise.NomEntr, stage.Id_stage, stage.Intitule, stage.Description, stage.Duree, stage.Competence_requise FROM entreprise, stage WHERE entreprise.Id_entreprise = stage.Id_entreprise';
			//Texte constituant la requête SQL permettant de connaitre le nombre de résultats relatifs à la recherche
			$texteRequeteNbStage = 'SELECT COUNT(*) AS nbstage FROM entreprise, stage WHERE entreprise.Id_entreprise = stage.Id_entreprise';

			//Texte constituant les différents critères sous forme de requête à concatener aux textes de base.
			$criteresRecherche = '';
			//Texte constituant les arguments qui seront concaténés dans le "array" pour le execute
			$parametresRecherche = array();
			//Compteur d'arguments permettant de décider à chaque critères s'il faut rajouter la mention ' AND ' avant le rajout du critère
			$nbArg = 0;

			//Gestion de l'existence d'un seul critère--------------------------------------
			if($_POST['dureeBot']     != "" || 
			   $_POST['dureeTop']     != "" || 
			   $_POST['competence']  != "" || 
			   $_POST['nomOrganisme'] != "" )
			{
				$texteRequete = $texteRequete.' AND ';
				$texteRequeteNbStage = $texteRequeteNbStage.' AND ';
			}

			//Gestion de la durée----------------------------------------------------------------
			
			//Si la durée minimale et la durée maximale sont renseignées
			if($_POST['dureeBot'] != "" && 
			   $_POST['dureeTop'] != "" )
			{
				$criteresRecherche = $criteresRecherche.'Duree BETWEEN ? AND ?';

				$parametresRecherche[] = $_POST['dureeBot'];
				$parametresRecherche[] = $_POST['dureeTop'];
				$nbArg++;
			}
			
			//Si seule la durée minimale est renseignée
			if($_POST['dureeBot'] != "" &&
			   $_POST['dureeTop'] == "" )
			{
				$criteresRecherche = $criteresRecherche.'Duree >= ?';

				$parametresRecherche[] = $_POST['dureeBot'];
				$nbArg++;
			}
			
			//Si seule la durée maximale est renseignée
			if($_POST['dureeTop'] != "" &&  
			   $_POST['dureeBot'] == "" )
			{
				$criteresRecherche = $criteresRecherche."Duree <= ?";

				$parametresRecherche[] = $_POST['dureeTop'];
				$nbArg++;
			}

			//Gestion des compétences-------------------------------------------------------------

			if($_POST['competence'] != "" && $nbArg > 0){

				$criteresRecherche = $criteresRecherche.' AND ';
			}

			if($_POST['competence'] != "")
			{
				$criteresRecherche = $criteresRecherche.'Competence_requise = ?';

				$parametresRecherche[] = $_POST['competence'];
				$nbArg++;
			}

			//Gestion des entreprises--------------------------------------------------------------
			if($_POST['nomOrganisme'] != "")
			{
				if($nbArg > 0)
				{
					$criteresRecherche = $criteresRecherche.' AND ';
				}

				$criteresRecherche = $criteresRecherche.'entreprise.NomEntr = ?';
				$parametresRecherche[] = $_POST['nomOrganisme'];
			}

				//Concaténation de la requête de base avec les différents critères
				$texteRequete = $texteRequete.$criteresRecherche;
				$texteRequeteNbStage = $texteRequeteNbStage.$criteresRecherche;

				//Préparation des requêtes SQL
				$reqRechercheStage = $link->prepare($texteRequete);
				$reqRechercheStage->execute($parametresRecherche);

				$reqNbStage = $link->prepare($texteRequeteNbStage);
				$reqNbStage->execute($parametresRecherche);

				$total = 0;
				while ($data = $reqNbStage->fetch()) {
					$total = $data['nbstage'];
				}
			?>
				<p><?php echo "$total" ?> stage(s) correspond(ent) à votre recherche.</p>
				<p>Trier par :</p>
				<form method="POST">
					<select>
						<option>Nombre de postes</option>
						<option>Durée du stage</option>
						<option>Entreprise</option>
					</select>
				</form>
		<?php
				//Affichage des résultats
				while ($data = $reqRechercheStage->fetch()) {
		?>
					<section>
						<h2><?php echo $data['Intitule'] ?></h2>
						<p>Organisme : <?php echo $data['NomEntr'] ?></p>
						<p>Offre : <?php echo $data['Description'] ?></p>
						<p>Durée : <?php echo $data['Duree']?> semaine(s)</p>

						<form action="./fiche_stage.php" method="POST">
							<input 	type="hidden" 
									name="idStage"
									value="<?php echo $data['Id_stage']; ?>">
							<input type="submit" name="consulter" value="Consulter">
						</form>
					</section>
		<?php

				}
		}
		?>
			</section>
	</body>
</html>

