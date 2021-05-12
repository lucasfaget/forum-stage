<?php
	session_start();
	require ("connexion.php");
	require ("util.php");

	$bdd = connexionservermysql($server, $db, $login, $mdp);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <title>Accueil | Forum Stage</title>
    </head>
    <body>
    <header>
<?php
    require("./header.php");
?>        
    </header>
    <form 	action="./espace_etudiant.php"
    		method="POST">
    	<button type="submit"
    			name="goProfil">
 			Profil
    	</button>

    </form>
    	

		<div class="container-fluid">
			<div class="row">
				<div class="col bg-dark col-xl-3 vh-100">
					<br><div class="text-center">
						<span class="text-light">Informations sur les réservations</span>
					</div><br>
					<ul class="text-light">
  						<li><strong>3 créneaux </strong><span>réservés au maximum</span></li>
					    <li><strong>Une demi-heure </strong><span>par entretien</span></li>
					    <li><strong>L'heure </strong><span>de votre entretien vous sera <strong>attribuée automatiquement</strong></span></li>
					    <li><span>Si un créneau avant le votre se libère, </span><strong>votre entretien sera avancé </strong><span>et vous serez notifié du changement</span></li>
					    <li><span>Vous pouvez vous </span><strong>désinscrire </strong><span>d'un créneau</span></li>
					</ul>
				</div>

				<?php

		    	$array_heure = array(0 => '08:00:00', 1 => '08:30:00', 2 => '09:00:00', 3 => '09:30:00', 4 => '10:30:00', 5 => '11:00:00', 6 => '11:30:00', 7 => '12:00:00', 8 => '13:00:00', 9 => '13:30:00', 10 => '14:00:00', 11 => '14:30:00', 12 => '15:00:00', 13  => '16:00:00', 14 => '16:30:00', 15 => '17:00:00');

		    	$select_etp = $bdd->prepare('SELECT Etp.Id_entreprise, Etp.NomEntr, count(Repr.Id_representant) AS nb_repr FROM entreprise Etp, representant Repr WHERE Etp.Id_entreprise = Repr.Id_entreprise GROUP BY Etp.NomEntr');
		    	$select_etp->execute();

		    	$select_rdv = $bdd->prepare('SELECT concat(E.NomEtu, " ", substr(E.PrenomEtu,1,1), ".") AS NomP, R.Creneau FROM etudiant E, reserver R, stage S, entreprise Etp WHERE E.Id_etudiant = R.Id_etudiant AND R.Id_stage = S.Id_stage AND S.Id_entreprise = Etp.Id_entreprise AND Etp.Id_entreprise = ? ORDER BY R.Creneau'); ?>

			    <div class="col text-center col-xl-9">
			    	<div class="text-center">
			    		<h1>Planning du forum stage</h1>
			    	</div>
			    	<table class="tablePlanning">
						<thead>
					    	<tr>
					    		<th class="thPlanning" scope="col"></th>
					    		<th class="thPlanning" scope="col">08:00</th>
					    		<th class="thPlanning" scope="col"><span class="fondGrisClair">08:30</span></th>
					    		<th class="thPlanning" scope="col">09:00</th>
					    		<th class="thPlanning" scope="col"><span class="fondGrisClair">09:30</span></th>
					    		<th class="thPlanning" scope="col">10:30</th>
					    		<th class="thPlanning" scope="col"><span class="fondGrisClair">11:00</span></th>
					    		<th class="thPlanning" scope="col">11:30</th>
					    		<th class="thPlanning" scope="col"><span class="fondGrisClair">12:00</span></th>
					    		<th class="thPlanning" scope="col">13:00</th>
					    		<th class="thPlanning" scope="col"><span class="fondGrisClair">13:30</span></th>
					    		<th class="thPlanning" scope="col">14:00</th>
					    		<th class="thPlanning" scope="col"><span class="fondGrisClair">14:30</span></th>
					    		<th class="thPlanning" scope="col">15:00</th>
					    		<th class="thPlanning" scope="col"><span class="fondGrisClair">16:00</span></th>
					    		<th class="thPlanning" scope="col">16:30</th>
					    		<th class="thPlanning" scope="col"><span class="fondGrisClair">17:00</span></th>
					    	</tr>
					    </thead>
					    <tbody>

					    	<?php
					    	// pour toutes les entreprises
					    	while ($row = $select_etp->fetch()) {

					    		// on récupère toutes les réservations de l'entreprise
						    	$select_rdv->execute(array($row['Id_entreprise']));
						    	$row_creneau = $select_rdv->fetch();

						    	$select_dispo_repr = $bdd->prepare('SELECT Debut_dispo, Fin_dispo FROM representant WHERE Id_entreprise = ?');
								$select_dispo_repr->execute(array($row['Id_entreprise']));
								$row_repr = $select_dispo_repr->fetch(); ?>

						    	<tr>

						    		<!-- On affiche le nom de l'entreprise -->
						    		<?php

						    		if ($row['nb_repr'] == 1) { ?>
						    			<td scope="row" class="tdPlanning tdNomEntreprise bg-dark text-white"><?php echo $row['NomEntr']; ?></td> <?php
						    		} else { ?>
						    			<td scope="row" rowspan="2" class="tdPlanning tdNomEntreprise bg-dark text-white"><?php echo $row['NomEntr']; ?></td> <?php
						    		}

						    		$i = 0;
						    		while ($i <= 15) {

						    			$nb_creneau = 0;
					    				$array_nomP = array("", "");
					    				while ($row_creneau['Creneau'] === $array_heure[$i]) {
					    					$array_nomP[$nb_creneau] = $row_creneau['NomP'];
					    					$nb_creneau++;
					    					$row_creneau = $select_rdv->fetch();
					    				}

					    				if ($nb_creneau >= 1) { ?>
					    					<td class="tdPlanning"><span><?php echo $array_nomP[0]; ?></span></td> <?php
					    				} else {
											if ($array_heure[$i] >= $row_repr['Debut_dispo'] and $array_heure[$i] < $row_repr['Fin_dispo']) { ?>
					    						<td class="tdPlanning tdDisponible"></td> <?php
					    					} else { ?>
					    						<td class="tdPlanning tdNonDisponible"></td> <?php
					    					}
					    				}

					    				$i++;

					    			} ?>

					    		</tr> <?php

					    		if ($row['nb_repr'] == 2) {

					    			$row_repr = $select_dispo_repr->fetch(); ?>

						    		<tr> <?php

						    			$select_rdv->execute(array($row['Id_entreprise']));
						    			$row_creneau = $select_rdv->fetch();

						    			$i = 0;
						    			while ($i <= 15) {

						    				$nb_creneau = 0;
						    				$array_nomP = array("", "");
						    				while ($row_creneau['Creneau'] === $array_heure[$i]) {
						    					$array_nomP[$nb_creneau] = $row_creneau['NomP'];
						    					$nb_creneau++;
						    					$row_creneau = $select_rdv->fetch();
						    				}

							    			if ($nb_creneau == 2) { ?>
					    						<td class="tdPlanning"><span><?php echo $array_nomP[1]; ?></span></td> <?php
					    				} else {
											if ($array_heure[$i] >= $row_repr['Debut_dispo'] and $array_heure[$i] < $row_repr['Fin_dispo']) { ?>
					    						<td class="tdPlanning tdDisponible"></td> <?php
					    					} else { ?>
					    						<td class="tdPlanning tdNonDisponible"></td> <?php
					    					}
					    				}

									    	$i++;

								    	} ?>
						
							    	</tr> <?php

						    	} ?>

						    	<tr class="separationLigne">

							    </tr> <?php

					      	} ?>

						</tbody>
						<tfoot>
					    	<tr>
					    		<th class="thPlanning" scope="col"></th>
					    		<th class="thPlanning" scope="col">08:00</th>
					    		<th class="thPlanning" scope="col"><span class="fondGrisClair">08:30</span></th>
					    		<th class="thPlanning" scope="col">09:00</th>
					    		<th class="thPlanning" scope="col"><span class="fondGrisClair">09:30</span></th>
					    		<th class="thPlanning" scope="col">10:30</th>
					    		<th class="thPlanning" scope="col"><span class="fondGrisClair">11:00</span></th>
					    		<th class="thPlanning" scope="col">11:30</th>
					    		<th class="thPlanning" scope="col"><span class="fondGrisClair">12:00</span></th>
					    		<th class="thPlanning" scope="col">13:00</th>
					    		<th class="thPlanning" scope="col"><span class="fondGrisClair">13:30</span></th>
					    		<th class="thPlanning" scope="col">14:00</th>
					    		<th class="thPlanning" scope="col"><span class="fondGrisClair">14:30</span></th>
					    		<th class="thPlanning" scope="col">15:00</th>
					    		<th class="thPlanning" scope="col"><span class="fondGrisClair">16:00</span></th>
					    		<th class="thPlanning" scope="col">16:30</th>
					    		<th class="thPlanning" scope="col"><span class="fondGrisClair">17:00</span></th>
					    	</tr>
					    </tfoot>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>