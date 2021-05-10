<?php
	require ("connexion.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="style.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <title>Accueil</title>
    </head>
    <body>
    <form 	action="./espace_etudiant.php"
    		method="POST">
    	<button type="submit"
    			name="goProfil">
 			Profil
    	</button>

    </form>
    	

<?php

    	$array_heure = array(0 => '08:00:00', 1 => '08:30:00', 2 => '09:00:00', 3 => '09:30:00', 4 => '10:30:00', 5 => '11:00:00', 6 => '11:30:00', 7 => '12:00:00', 8 => '13:00:00', 9 => '13:30:00', 10 => '14:00:00', 11 => '14:30:00', 12 => '15:00:00', 13  => '16:00:00', 14 => '16:30:00', 15 => '17:00:00');

    	$bdd = connexionservermysql($server, $db, $login, $mdp);
    	
    	$select_etp = $bdd->prepare('SELECT Etp.NomEntr FROM entreprise Etp');
    	$select_etp->execute();

    	$select_rdv = $bdd->prepare('SELECT concat(E.NomEtu, " ", substr(E.PrenomEtu,1,1), ".") AS NomP, R.Creneau AS Heure FROM etudiant E, reserver R, stage S, entreprise Etp WHERE E.Id_etudiant = R.Id_etudiant AND R.Id_stage = S.Id_stage AND S.Id_entreprise = Etp.Id_entreprise AND Etp.NomEntr = ? ORDER BY R.Creneau'); ?>

    	<div class="table-responsive">
	    	<table class="table">
				<thead>
			    	<tr>
			    		<th scope="col"></th>
			    		<th scope="col">08:00</th>
			    		<th scope="col">08:30</th>
			    		<th scope="col">09:00</th>
			    		<th scope="col">09:30</th>
			    		<th scope="col">10:30</th>
			    		<th scope="col">11:00</th>
			    		<th scope="col">11:30</th>
			    		<th scope="col">12:00</th>
			    		<th scope="col">13:00</th>
			    		<th scope="col">13:30</th>
			    		<th scope="col">14:00</th>
			    		<th scope="col">14:30</th>
			    		<th scope="col">15:00</th>
			    		<th scope="col">16:00</th>
			    		<th scope="col">16:30</th>
			    		<th scope="col">17:00</th>
			    	</tr>
			    </thead>
			    <tbody>

			    	<?php
			    	// pour toutes les entreprises
			    	while ($row = $select_etp->fetch()) {

			    		// on récupère toutes les réservations de l'entreprise
				    	$select_rdv->execute(array($row['NomEntr'])) ?>
				    	<tr>

				    		<!-- On affiche le nom de l'entreprise -->
				    		<th scope="row"><?php echo $row['NomEntr']; ?></th> <?php

				    		// On récupère la première réservation
				    		$col = $select_rdv->fetch();
				    		$i = 0;

				    		while ($i <= 15) { ?>

				    			<td> <?php

						    		// On affiche les réservations du créneau
						    		while ($col['Heure'] === $array_heure[$i]) {
						    			
						    			echo $col['NomP']."<br>";
						    			$col = $select_rdv->fetch();

						    		} ?>

						    	</td> <?php

						    	$i++;

					    	} ?>
			
				    	</tr>

			      	<?php } ?>

				</tbody>
			</table>
		</div>

	</body>
</html>