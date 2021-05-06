<?php
        session_start();
	require 'util.php';
	require 'config.php';
	
	
	if(isset($_POST['btn_connexion'])){
        
                //vérification des champs obligatoires
	 	if(isset($_POST['saisie_mail']) && !empty($_POST['saisie_mail']) &&
		 isset($_POST['saisie_mdp']) && !empty($_POST['saisie_mdp'])){
			
                        //protection des données saisies
			$saisie_mail = htmlspecialchars($_POST['saisie_mail']);
			$saisie_mdp = htmlspecialchars($_POST['saisie_mdp']);
			$type_utilisateur; 
			$pass_attendu;
			$verificationMail;
			
                        //Requête admin
			$reqConnexionAdmin = $linkpdo->prepare('SELECT * FROM Administrateur WHERE Login_admin = ?');
			$reqConnexionAdmin->execute(array($saisie_mail));
			foreach($reqConnexionAdmin as $resConnexion){
				$pass_attendu = $resConnexion['Mot_de_passe'];
				$type_utilisateur = 'administrateur';
			}
			//Requête entreprise
			$reqConnexionEntrp = $linkpdo->prepare('SELECT Mail, Mot_de_passe FROM Entreprise WHERE Mail = ?');
			$reqConnexionEntrp ->execute(array($saisie_mail));
			foreach($reqConnexionEntrp as $resConnexion){
				$pass_attendu = $resConnexion['Mot_de_passe'];
				$type_utilisateur = 'entreprise';
			}
                        //Requête étudiant
			$reqConnexionEtu = $linkpdo->prepare('SELECT Mail, Mot_de_passe, Mail_confirme FROM Etudiant WHERE Mail = ?');
			$reqConnexionEtu ->execute(array($saisie_mail));
			foreach($reqConnexionEtu as $resConnexion){
				$verificationMail = $resConnexion['Mail_confirme'];
				$pass_attendu = $resConnexion['Mot_de_passe'];
				$type_utilisateur = 'étudiant';
			}
			
			if($type_utilisateur == 'étudiant'){
                                //l'utilisateur est un étudiant, son adresse mail doit être validée
				if($verificationMail == 1){
					if(password_verify($saisie_mdp, $pass_attendu)){
                                        
                                                //enregistrement des variables SESSION pour les étudiant
						$_SESSION['userId'] = $saisie_mail;
						$_SESSION['userType'] = $type_utilisateur;
						$_SESSION['userNom'] = $resConnexion['Nom'];
						$_SESSION['userPrénom'] = $resConnexion['Prenom'];
						header('Location: accueil_connecte.php');
					}else
						header('Location: index.php?err_connexion=MauvaisMailOuMdp');
				}else
					header('Location: index.php?err_connexion=MailNonVerife');
			}elseif(password_verify($saisie_mdp, $pass_attendu)){
                               
                               //enregistrement des variables SESSION pour les entreprises et administrateurs
				$_SESSION['userId'] = $saisie_mail;
				$_SESSION['userType'] = $type_utilisateur;
				header('Location: accueil_connecte.php');
			}else 
				header('Location: index.php?err_connexion=MauvaisMailOuMdp');
		}else 
			header('Location: index.php?err_connexion=ChampsVide');
	}
?>
<!DOCTYPE HTML>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Accueil | Forum Stage</title>
	</head>
	<body>	
		<h1>Page d'accueil</h1>
	
		<?php
                        ///Messages d'erreurs de saisie dans le formulaire
			if(isset($_GET['err_connexion'])){
				$erreur = htmlspecialchars($_GET['err_connexion']);
				
				switch($erreur){
					case 'MauvaisMailOuMdp';
                                        ?>
						<div>
							<strong>Erreur</strong> mot de passe ou mail incorrect
						</div>
					<?php
					break;
					case 'MailNonVerife';
                                        ?>
						<div>
							<strong>Erreur</strong> l'adresse mail de ce compte n'est pas encore vérifiée
						</div>
					<?php
					break;
					case 'ChampsVide';
                                        ?>
						<div>
							<strong>Erreur</strong> merci de remplir tous les champs
						</div>
					<?php
					break;
				}
			}
		?>
		
		<form method="post">
			<input type="text" name="saisie_mail" placeholder="Adresse mail"><br>
			<input type="password" name="saisie_mdp" placeholder="Mot de passe"><br>
			<input type="submit" name="btn_connexion" value="Se connecter"><br>
                        <a href="mdp_oublie1.php"><p>Mot de passe oublié</p></a>
			<p>──── OU ────</p>
			<a href="inscription_etudiant.php"><p>S'inscrire</p></a>
		</form>
	</body>
</html>