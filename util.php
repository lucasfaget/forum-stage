<?php	     

	/*---------------------------------------------------------*/
	/*------------------ LISTE DES FONCTIONS ------------------*/
    /*---------------------------------------------------------*/

    /*
	Détruit les sessions en cours et deconnecte l'utilisateur.
	*/
	function detruireSession() : void
	{
		session_unset();
		session_destroy();
	}
	
	/*
	Vérifie si l'utilisateur est connecté.
	*/
	function estConnecte() : bool
	{
		if(isset($_SESSION['userId'])){
			return true;
		}else{
			return false;
		}
	}
	
	/*
	Vérifie si le mot de passe est assez fort.
		- 8 caractères minimum
		- au moins 1 majuscule et 1 minuscule
		- au moins 1 chiffre
		- au moins 1 caractère spécial
	*/
	function motDePasseValide(string $mdp_etu) : bool
	{
		if(strlen($mdp_etu)>=8 && strlen($mdp_etu)<=60 &&
		 !ctype_upper($mdp_etu) && !ctype_lower($mdp_etu) &&
		 preg_match("#[0-9]+#",$mdp_etu) && 
		 preg_match('/[\'^£€$%&*()}{@#~?><>,|=_+¬-]/', $mdp_etu)){
			return true;
		}else{
			return false;
		}
	}

	/*
	Génère un mot de passe aléatoire pour la création des comptes entreprise.
	*/
	function genererMdp($nbChar)
	{
    	return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCEFGHIJKLMNOPQRSTUVWXYZ0123456789@!%$&£'),1, $nbChar); 
    }

    /*
    Permet d'envoyer un mail à un étudiant en cas de mot de passe oublié.
    */
    function envoiMailRecupMdp($adresseMail, $objetMail, $recup_cle)
    {
    	$header="MIME-Version: 1.0\r\n";
        $header.='From:forumstageiut.tk'."\n";
        $header.='Content-Type:text/html; charset="utf-8"'."\n";
        $header.='Content-Transfer-Encoding: 8bit';
      
        // Génération du message de récupération de mot de passe
        $message = '
        <html>
            <head>
                <title>Récupération de mot de passe - forumstageiut.tk</title>
                <meta charset="utf-8" />
            </head>
            <body>
                <table align="center" rules="rows">
                    <tr>
                        <td>
                            <h2 align="center">Réinitialisation de votre mot de passe</h2>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>Bonjour,</p>
                            <p>Afin de réinitialiser votre mot de passe, veuillez cliquer sur le lien ci-dessous.</p>
                            <p><a href="http://forumstageiut.tk/mdp_oublie2.php?key='.$recup_cle.'">Réinitialiser mon mot de passe</a></p>
                            <p>Ceci est un mail automatique, merci de ne pas répondre</p>
                        </td>
                    </tr>
                </table>
            </body>
        </html>
        ';

        if (mail($adresseMail, $objetMail, $message, $header)) {

            echo "Le mail a été envoyé avec succès !";

        } else {

            echo "Echec de l'envoi du mail";
        }
    }

    /*
    Permet d'envoyer un mail à une entreprise pour lui transmettre son identifiant
    et son mot de passe provisoire. 
    */
    function envoiMailMdpProvisoireEntreprise($adresseMail, $objetMail, $mdpProvisoire)
    {
    	$header="MIME-Version: 1.0\r\n";
        $header.='From:forumstageiut.tk'."\n";
        $header.='Content-Type:text/html; charset="utf-8"'."\n";
        $header.='Content-Transfer-Encoding: 8bit';

        $message = '
        <html>
            <head>
                <title>Création de compte - forumstageiut.tk</title>
                <meta charset="utf-8" />
            </head>
            <body>
                <table align="center" rules="rows">
                    <tr>
                        <td>
                            <h2 align="center">Création de votre compte entreprise</h2>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>Bonjour,</p>
                            <p>Afin de pouvoir vous connecter à votre compte entreprise, vous trouverez vos informations de connexion ci-dessous :</p>
                            <p>Login : '.$adresseMail.'</p>
                            <p>Mot de passe (provisoire) : '.$mdpProvisoire.'</p>
                            <p>Attention : Le mot de passe est à modifier lors de votre première connexion.</p>
                            <p>Ceci est un mail automatique, merci de ne pas répondre</p>
                        </td>
                    </tr>
                </table>
            </body>
        </html>
        ';

        if (mail($adresseMail, $objetMail, $message, $header)) {
            echo "Le mail a été envoyé avec succès !";
        } else {
            echo "Echec de l'envoi du mail.";
        }
    }  
?>