<?php
    session_start();
	require ("util.php");
	require ("connexion.php");
	
	if(isset($_POST['btn_valider_inscription'])){

		$bdd = connexionservermysql($server, $db, $login, $mdp);

		//Cryptage du mot de passe
		$mdp_admin = password_hash($_POST['saisie_mdp'], PASSWORD_BCRYPT);
						
		//Inscription de l'étudiant dans la base de données
		$reqInscription = $bdd->prepare('INSERT INTO 
													administrateur(Login_admin, Mot_de_passe)
												VALUES
													(:login, :mdp)');

		$reqInscription->execute(array(	'login' 	=> $_POST['saisie_login'],
										'mdp' 	=> $mdp_admin));
	}
?>
<!DOCTYPE HTML>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Inscription</title>
	</head>
	<body>
	
		<h1>Inscription administrateur</h1>

		<form action="./inscription_admin.php" method="post">			
			<p>
				<label>Login :</label> 
				<input type="text" name="saisie_login">
			</p>
			
			<p>
				<p>Mot de passe :</p>
				<input type="password" name="saisie_mdp">
			</p>

			<input type="submit" name="btn_valider_inscription" value="S'inscrire"><br>
		</form>
		
	</body>
</html>