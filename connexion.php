<?php
require('config.php');
    
    function connexionservermysql($server, $db, $login, $mdp){
    	try {

        	$bdd = new PDO("mysql:host=$server;dbname=$db", $login, $mdp); 
    	}
   		catch (Exception $e) {

        	die('Erreur : ' . $e->getMessage()); 
    	}
    	return $bdd;
    }
?>