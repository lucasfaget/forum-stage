<?php

	require 'connexion.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="style.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <title>Fiche stage</title>
    </head>
    <body>

    	<?php

    	$bdd = connexionservermysql($server, $db, $login, $mdp);

    	$array_heure = array(0 => '08:00:00', 1 => '08:30:00', 2 => '09:00:00', 3 => '09:30:00', 4 => '10:30:00', 5 => '11:00:00', 6 => '11:30:00', 7 => '12:00:00', 8 => '13:00:00', 9 => '13:30:00', 10 => '14:00:00', 11 => '14:30:00', 12 => '15:00:00', 13  => '16:00:00', 14 => '16:30:00', 15 => '17:00:00');

    	if (isset($_POST['submit_reserver'])) {

    		// il faut impérativement que $id_stage correspond à $id_entreprise
    		$id_etudiant = 7; // affecter la variable de session qui stocke l'id de l'étudiant
    		$id_entreprise = 4;
    		$id_stage = 7;

    		// On récupère le nombre de créneaux réservés par l'étudiant
    		$select_rdv_etu = $bdd->prepare('SELECT Id_etudiant FROM reserver WHERE Id_etudiant = ?');
    		$select_rdv_etu->execute(array($id_etudiant));
    		$result = $select_rdv_etu->fetchAll();

    		// On vérifie que l'étudiant n'a pas déjà 3 créneaux réservés
    		var_dump(count($result));

    		if (count($result) < 3) {

    			// l'étudiant ne peut pas réserver un second créneau pour un même stage
    			$select_rdv_etu_stage = $bdd->prepare('SELECT Id_Etudiant FROM reserver WHERE Id_etudiant = ? AND Id_stage = ?');
    			$select_rdv_etu_stage->execute(array($id_etudiant, $id_stage));
    			$result = $select_rdv_etu_stage->fetchAll();

    			if (count($result) == 0) {

		    		// On recherche dans la base les créneaux et leur nombre pour l'entreprise en question
		    		$select_creneau = $bdd->prepare('SELECT R.Creneau, count(*) AS nb_creneau_reserve FROM reserver R, stage S, entreprise Etp WHERE R.Id_stage = S.Id_stage AND S.Id_entreprise = Etp.Id_entreprise AND Etp.Id_entreprise = ? GROUP BY R.Creneau ORDER BY R.Creneau;');
		    		$select_creneau->execute(array($id_entreprise));
		    		$row_creneau = $select_creneau->fetch();
		    		
		    		// On crée le tableau contenant le nombre de réservation par créneau
		    		$array_creneau = array_flip($array_heure);
		    		foreach ($array_creneau as $key => $value) {
		    	
		    			if ($key === $row_creneau['Creneau']) {
		    				$array_creneau[$key] = intval($row_creneau['nb_creneau_reserve']);
		    				$row_creneau = $select_creneau->fetch();
		    			} else {
		    				$array_creneau[$key] = 0;
		    			}

		    		}

		    		var_dump($array_creneau);

					$select_dispo_repr = $bdd->prepare('SELECT Debut_dispo, Fin_dispo FROM representant WHERE Id_entreprise = ?');
					$select_dispo_repr->execute(array($id_entreprise));
					$result_dispo = $select_dispo_repr->fetchAll();

					// On récupère le nombre de représentants
					$nb_repr = count($result_dispo);
					var_dump($nb_repr);
					$creneau_trouve = false;
					$creneau_dispo;

					// On cherche le plus petit créneau d'un représentant
					// si 2 représentants
					if ($nb_repr == 2) {
						$debut_creneau_min = min($result_dispo[0]['Debut_dispo'], $result_dispo[1]['Debut_dispo']);
						$debut_creneau_max = date('H:i:s', strtotime(max($result_dispo[0]['Fin_dispo'], $result_dispo[1]['Fin_dispo'])) - 30*60);
						var_dump($debut_creneau_min);
						var_dump($debut_creneau_max);

					// si 1 représentant
					} else {
						$debut_creneau_min = $result_dispo[0]['Debut_dispo'];
						$debut_creneau_max = date('H:i:s', strtotime($result_dispo[0]['Fin_dispo']) - 30*60);
						var_dump($debut_creneau_min);
						var_dump($debut_creneau_max);

					}

					// on récupère le numéro du premier créneau réservable dans array_heure
					$key_min = array_search($debut_creneau_min, $array_heure);
					var_dump($key_min);

					if ($nb_repr == 1) {

						$key = $key_min;

						while ($array_creneau[$array_heure[$key]] == 1) {
							$key++;
						}

						// On vérifie que le representant est dispo sur le créneau suivante le dernier créneau réservé
						if ($array_heure[$key] <= $debut_creneau_max and $key <= 15) {
							$creneau_trouve = true;
							$creneau_dispo = $array_heure[$key];
						}

					} else {

						$key = $key_min;

						// tant qu'au moins un créneau a a été réservé
						var_dump($array_creneau[$array_heure[$key]]);
						while ($array_creneau[$array_heure[$key]] >= 1) {

							if ($array_creneau[$array_heure[$key]] == 1) {

								echo 1;

								$est_dispo_repr1 = $array_heure[$key] >= $result_dispo[0]['Debut_dispo'] and $array_heure[$key] < $result_dispo[0]['Fin_dispo'];
								$est_dispo_repr2 = $array_heure[$key] >= $result_dispo[1]['Debut_dispo'] and $array_heure[$key] < $result_dispo[1]['Fin_dispo'];
								var_dump($est_dispo_repr1);
								var_dump($est_dispo_repr2);

								var_dump($est_dispo_repr1 and $est_dispo_repr2);

								if ($est_dispo_repr1 and $est_dispo_repr2) {
									$creneau_trouve = true;
									$creneau_dispo = $array_heure[$key];
								}

							}

							$key++;

						}

						if ($creneau_trouve == false) {

							echo 2;

							$est_dispo_repr1 = $array_heure[$key] >= $result_dispo[0]['Debut_dispo'] and $array_heure[$key] < $result_dispo[0]['Fin_dispo'];
							$est_dispo_repr2 = $array_heure[$key] >= $result_dispo[1]['Debut_dispo'] and $array_heure[$key] < $result_dispo[1]['Fin_dispo'];
							var_dump($est_dispo_repr1);
							var_dump($est_dispo_repr2);

							var_dump($est_dispo_repr1 or $est_dispo_repr2);

							if ($est_dispo_repr1 or $est_dispo_repr2) {
								$creneau_trouve = true;
								$creneau_dispo = $array_heure[$key];
							}

						}

					}

					if ($creneau_trouve == true) {

						$insert_rdv = $bdd->prepare('INSERT INTO reserver VALUES (?,?,?)');
						$insert_rdv->execute(array($id_etudiant, $id_stage, $creneau_dispo));

						if ($insert_rdv) {
							$success = "le créneau de ".$creneau_dispo." a été réservé !";
						} else {
							$danger = "Erreur lors de la réservation";
						}

					} else {

						$warning = "Aucun créneau n'est disponible pour cette entreprise";

					}

				} else {

					$warning = "Vous avez déjà réserver un créneau pour ce stage. Veuillez consulter votre planning";

				}

	    	} else {

	    		$warning = "Le nombre de réservations possible est limité à 3 et vous avez déjà 3 créneaux réservés.";

	    	}

    	} ?>

    	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<button type="submit" class="btn btn-danger" name="submit_reserver">Réserver<br>un créneau</button>
		</form>
		<?php if (isset($warning)) { ?>
		<div class="alert alert-warning mt-3" role="alert">
            <?php echo $warning; ?>
        </div>
        <?php } ?>
        <?php if (isset($success)) { ?>
		<div class="alert alert-success mt-3" role="alert">
            <?php echo $success; ?>
        </div>
        <?php } ?>
        <?php if (isset($danger)) { ?>
		<div class="alert alert-danger mt-3" role="alert">
            <?php echo $danger; ?>
        </div>
        <?php } ?>

	</body>
</html>