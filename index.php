<?php
    session_start();
    require ("util.php");
	require ('connexion.php');

	$bdd = connexionservermysql($server, $db, $login, $mdp);
	
	if(isset($_POST['btn_connexion'])){
        
        //Vérification des champs obligatoires
	 	if(	isset($_POST['saisie_mail']) && 
	 		!empty($_POST['saisie_mail']) &&
		 	isset($_POST['saisie_mdp']) && 
		 	!empty($_POST['saisie_mdp'])){

            //Protection des données saisies
			$saisie_mail = htmlspecialchars($_POST['saisie_mail']);
			$saisie_mdp = htmlspecialchars($_POST['saisie_mdp']);
			$type_utilisateur; 
			$pass_attendu;
			$verificationMail;
			
            //Requête admin
			$reqConnexionAdmin = $bdd->prepare('SELECT * FROM administrateur WHERE Login_admin = ?');
			$reqConnexionAdmin->execute(array($saisie_mail));
			foreach($reqConnexionAdmin as $resConnexion){
				$pass_attendu = $resConnexion['Mot_de_passe'];
				$type_utilisateur = 'administrateur';
			}

			//Requête entreprise
			$reqConnexionEntrp = $bdd->prepare('SELECT Mail, Mot_de_passe FROM entreprise WHERE Mail = ?');
			$reqConnexionEntrp ->execute(array($saisie_mail));
			foreach($reqConnexionEntrp as $resConnexion){
				$pass_attendu = $resConnexion['Mot_de_passe'];
				$type_utilisateur = 'entreprise';
			}

            //Requête étudiant
			$reqConnexionEtu = $bdd->prepare('SELECT Mail, Mot_de_passe, Mail_confirme FROM etudiant WHERE Mail = ?');
			$reqConnexionEtu ->execute(array($saisie_mail));
			foreach($reqConnexionEtu as $resConnexion){
				$verificationMail = $resConnexion['Mail_confirme'];
				$pass_attendu = $resConnexion['Mot_de_passe'];
				$type_utilisateur = 'étudiant';
			}
			
			if($type_utilisateur == 'étudiant'){
                //Si l'utilisateur est un étudiant, son adresse mail doit être validée
				if($verificationMail == 1){
					if(password_verify($saisie_mdp, $pass_attendu)){
                                        
                        //Enregistrement des variables SESSION pour les étudiants
						$_SESSION['userId'] = $saisie_mail;
						$_SESSION['userType'] = $type_utilisateur;
						$_SESSION['userNom'] = $resConnexion['Nom'];
						$_SESSION['userPrenom'] = $resConnexion['Prenom'];
						header('Location: accueil_connecte.php');
					}else{
						header('Location: index.php?err_connexion=MauvaisMailOuMdp');
					}
				}else{
					header('Location: index.php?err_connexion=MailNonVerife');
				}
			}elseif(password_verify($saisie_mdp, $pass_attendu)){
                               
                //Enregistrement des variables SESSION pour les entreprises et administrateurs
				$_SESSION['userId'] = $saisie_mail;
				$_SESSION['userType'] = $type_utilisateur;
				header('Location: accueil_connecte.php');
			}else{
				header('Location: index.php?err_connexion=MauvaisMailOuMdp');
			}
		}else{ 
			header('Location: index.php?err_connexion=ChampsVide');
		}

	}
?>
<!DOCTYPE HTML>
<html lang="fr">
	<head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="style.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <title>Accueil</title>
    </head>
    <body>

	
<?php
         	///Messages d'erreurs de saisie dans le formulaire
			if(isset($_GET['err_connexion'])){
				$erreur = htmlspecialchars($_GET['err_connexion']);
				
				switch($erreur){
					case 'MauvaisMailOuMdp';
?>
						<div>
							<strong>Erreur :</strong> Mot de passe ou mail incorrect.
						</div>
<?php
					break;
					case 'MailNonVerife';
?>
						<div>
							<strong>Erreur :</strong> L'adresse mail de ce compte n'est pas encore vérifiée.
						</div>
<?php
					break;
					case 'ChampsVide';
?>
						<div>
							<strong>Erreur :</strong> Merci de remplir tous les champs.
						</div>
<?php
					break;
				}
			}
		?>

		<div class="container-fluid">
			<div class="row">
				<div class="col text-center bg-dark col-xl-3 vh-100">
					<img class="mt-3" src="ressources/logoIUT.png" alt="logo iut" alt="Grapefruit slice atop a pile of other slices">
					<form method="post">
						<div class="d-flex justify-content-center">
							<div class="form-group mb-1">
								<input type="email" class="form-control form-control-lg mt-4" name="saisie_mail" placeholder="Adresse mail" style="width: 250px">
							</div>
						</div>
						<div class="d-flex justify-content-center">
							<div class="form-group">
								<input type="password" class="form-control form-control-lg mt-4" name="saisie_mdp" placeholder="Mot de passe" style="width: 250px">
							</div>
						</div>
						<div class="mt-4">
							<button type="submit" name="btn_connexion" class="btn btn-secondary">Se connecter</button>
						</div>
						<div class="mt-2">
			            	<a href="mdp_oublie1.php">Mot de passe oublié</a>
			            </div>
			            <div class="text-light mt-2">
							───── OU ─────
						</div>
						<div class="mt-3">
							<a href="inscription_etudiant.php" class="btn btn-danger btn-lg active" role="button" aria-pressed="true">S'inscrire</a>
						</div>
					</form>
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
						    	$row_creneau = $select_rdv->fetch(); ?>

						    	<tr class="trPlanning">

						    		<!-- On affiche le nom de l'entreprise -->
						    		<?php

						    		if ($row['nb_repr'] == 1) { ?>
						    			<td scope="row" class="tdPlanning tdNomEntreprise bg-dark text-white"><?php echo $row['NomEntr']; ?></td> <?php
						    		} else { ?>
						    			<td scope="row" rowspan="2" class="tdPlanning tdNomEntreprise bg-dark text-white"><?php echo $row['NomEntr']; ?></td> <?php
						    		}

						    		$i = 0;
						    		while ($i <= 15) {

						    			// compter
						    			$nb_creneau = 0;
						    			while ($row_creneau['Creneau'] === $array_heure[$i]) {
						    				$nb_creneau++;
						    				$row_creneau = $select_rdv->fetch();
						    			}

						    			// afficher
					    				if ($nb_creneau >= 1) { ?>
					    					<td class="tdPlanning fondGrisFonce"><span> X </span></td> <?php
					    				} else { 
					    					if ($i%2 != 0) { ?>
					    						<td class="tdPlanning fondGrisClair"></td> <?php
					    					} else { ?>
					    						<td class="tdPlanning"></td> <?php
					    					}
					    				}

					    				$i++;

					    			} ?>

					    		</tr> <?php

					    		if ($row['nb_repr'] == 2) { ?>

						    		<tr class="trPlanning"> <?php

						    			$select_rdv->execute(array($row['Id_entreprise']));
						    			$row_creneau = $select_rdv->fetch();

						    			$i = 0;
						    			while ($i <= 15) {

						    				$nb_creneau = 0;
						    				while ($row_creneau['Creneau'] === $array_heure[$i]) {
						    					$nb_creneau++;
						    					$row_creneau = $select_rdv->fetch();
						    				}

							    			if ($nb_creneau == 2) { ?>
							    				<td class="tdPlanning fondGrisFonce"><span> X </span></td> <?php
							    			} else {
							    				if ($i%2 != 0) { ?>
					    							<td class="tdPlanning fondGrisClair"></td> <?php
					    						} else { ?>
					    							<td class="tdPlanning"></td> <?php
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