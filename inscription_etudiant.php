<?php
    session_start();
	require ("util.php");
	require ("connexion.php");
	
	if(isset($_POST['btn_valider_inscription'])){
        
        //vérification des champs obligatoires
		if(isset($_POST['saisie_mail_etu']) && !empty($_POST['saisie_mail_etu']) &&
		 isset($_POST['saisie_prenom_etu']) && !empty($_POST['saisie_prenom_etu']) &&
		 isset($_POST['selection_diplome_etu']) && !empty($_POST['selection_diplome_etu']) &&
		 isset($_POST['saisie_mdp_etu']) && !empty($_POST['saisie_mdp_etu']) &&
		 isset($_POST['confirmation_mdp_etu']) && !empty($_POST['confirmation_mdp_etu'])){
			
                        //protection des données saisies
			$mail_etu = htmlspecialchars($_POST['saisie_mail_etu']);
			$nom_etu = htmlspecialchars($_POST['saisie_nom_etu']);
			$prenom_etu = htmlspecialchars($_POST['saisie_prenom_etu']);
			$diplome_etu = htmlspecialchars($_POST['selection_diplome_etu']);
			$mdp_etu = htmlspecialchars($_POST['saisie_mdp_etu']);
			$confirmation_mdp_etu = htmlspecialchars($_POST['confirmation_mdp_etu']);
			
			//concatenation de l'adresse mail
			$mail_etu = $mail_etu.'@etu.iut-tlse3.fr';

			$bdd = connexionservermysql($server, $db, $login, $mdp);

			//vérification d'un profil déjà existant en base de donnée
			$reqVerif = $bdd->prepare('SELECT Mail, Mot_de_passe FROM Etudiant WHERE Mail = ?');
			$reqVerif->execute(array($mail_etu));
			$resVerif = $reqVerif->fetch();
			$row = $reqVerif->rowCount();

			if($row == 0){
				if(motDePasseValide($mdp_etu)){
					if($mdp_etu == $confirmation_mdp_etu){
						
						//Générer une clé de vérification pour confirmer le mail
						$cleVerif = md5(time().$mail_etu);
						
						//Cryptage du mot de passe
						$mdp_etu = password_hash($mdp_etu, PASSWORD_BCRYPT);
						
						//Inscription de l'étudiant dans la base de données
						$reqInscription = $bdd->prepare('
							INSERT INTO etudiant(Mail, NomEtu, PrenomEtu, Mot_de_passe, Diplome, Cle_confirmation)
							VALUES(:mail, :nom, :prenom, :mdp, :diplome, :cle)');
						$reqInscription->execute(array(
							'mail' => $mail_etu,
							'nom' => $nom_etu,
							'prenom' => $prenom_etu,
							'mdp' => $mdp_etu,
							'diplome' => $diplome_etu,
                            'cle' => $cleVerif
						));
						
						//envoyer un mail de confirmation de l'adresse mail
						$objet = 'Vérification Adresse Mail';  
                                                $headers='MIME-Version: 1.0\r\n';
                                                $headers.='From:forumstageiut.tk'.'\n';
                                                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                                                $headers.='Content-Transfer-Encoding: 8bit';
                                                $message = "Merci de confirmer votre adresse avec ce lien : http://forumstageiut.tk/verification.php?cleVerif=$cleVerif";
						mail($mail_etu, $objet, $message, $headers);
                                                header('Location: inscription_etudiant.php?mail=mailEnvoye');
					}else 
						header('Location: inscription_etudiant.php?err_inscription=confirmationMdp');
				}else 
					header('Location: inscription_etudiant.php?err_inscription=conditionsMdp'); 
			}else
				header('Location: inscription_etudiant.php?err_inscription=utilisateurExisteDeja');
		}else 
			header('Location: inscription_etudiant.php?err_inscription=champsVides');
	}
?>
<!DOCTYPE HTML>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Inscription</title>
	</head>
	<body>
		
		<h1>Inscription</h1>
		
		<p>Vous êtes étudiant et c'est votre première visite ? 
		Merci de saisir vos informations personnelles, 
		tous les champs sont obligatoires.</p>
                
                <?php
                        //Message de confirmation
			if(isset($_GET['mail'])){
                                ?>
                                <div>
                                        <strong>Votre compte étudiant a bien été crée. Un mail vous a été envoyé.</strong>
                                </div>
                <?php
			}
			
		?>
                
		<?php
                        //Messages d'erreurs de saisie dans le formulaire
			if(isset($_GET['err_inscription'])){
				$erreur = htmlspecialchars($_GET['err_inscription']);
				
				switch($erreur){
					case 'confirmationMdp';
                                        ?>
						<div>
							<strong>Erreur</strong> les mots de passe saisis ne sont pas les mêmes
						</div>
					<?php
					break;
					case 'conditionsMdp';
					?>
						<div>
							<strong>Erreur</strong> ce mot de passe ne respecte pas les conditions demandées
						</div>
					<?php
					break;
					case 'utilisateurExisteDeja';
					?>
						<div>
							<strong>Erreur</strong> il existe déjà un compte avec cette adresse mail
						</div>
					<?php
					break;
					case 'champsVides';
					?>
						<div>
							<strong>Erreur</strong> vous devez remplir tous les champs
						</div>
					<?php
					break;
				}
			}
		?>
		<form method="post">
			<p>
				Entrez le début de votre adresse e-mail de l'iut 
				(aucune autre adresse n'est valide)  :<br>
				<input type="text" name="saisie_mail_etu" placeholder="prénom.nom">@etu.iut-tlse3.fr
			</p>
			
			<p>
				<label>Nom :</label> <input type="text" name="saisie_nom_etu" placeholder="Nom">
				<label>Prénom :</label> <input type="text" name="saisie_prenom_etu" placeholder="Prénom">
			</p>
			
			<p>
				<label>Selectionnez votre diplôme :</label> 
				<select name="selection_diplome_etu">
					<option>DUT Informatique - 1ère annee</option>
					<option>DUT Informatique - 2ème année</option>
					<option>DUT Informatique - Année spéciale</option>
					<option>Licence Pro - DQL</option>
					<option>Licence Pro - GTIDM</option>
				</select>
			</p>
			
			<p>
				<label>Choisissez un mot de passe contenant :<br>
				- 8 caractères minimum<br>
				- au moins 1 majuscule<br>
				- au moins 1 minuscule<br>
				- au moins 1 chiffre<br>
				- au moins 1 caractère spécial</label> 
				<input type="password" name="saisie_mdp_etu" placeholder="Mot de passe">
			</p>
			
			<p>
				<label>Entrez à nouveau votre mot de passe  :</label>
				<input type="password" name="confirmation_mdp_etu" placeholder="Confirmez le mot de passe">
			</p>
			
			<input type="submit" name="btn_valider_inscription" value="S'inscrire"><br>
			<p>──── OU ────</p>
			<a href="index.php"><p>Vous avez déjà un compte ? Connectez-vous</p></a>
		</form>
		
	</body>
</html>