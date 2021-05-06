<?php
require('config.php');
	
    /*
	Fait le lien avec la base de données du site.
	*/
	function connexionservermysql($bd_server, $db_bd, $bd_identifiant, $bd_mdp) {
   		try{
            $linkpdo = new PDO("mysql:host=$bd_server;dbname=$db_db", $bd_identifiant, $bd_mdp);
        }catch( Exception$e){
            die('Erreur : ' . $e->getMessage());
       	}
        return $linkpdo;
    }
        
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
	
?>