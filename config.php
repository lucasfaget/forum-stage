<?php

	/* ----A UTILISER SUR LE SITE d'HERGEMENT -------
	$server = 'fdb28.awardspace.net';
	$db     = '3788709_forumstage';
	$login  = '3788709_forumstage';
	$mdp    = '4frHtZ+S46M:WArK';
	
	try{

		$link = new PDO("mysql:host=$server;dbname=$db", $login, $mdp);

	}catch( Exception$e){

		die('Erreur : ' . $e->getMessage());
	}
	--------------------------------------------------*/


	/* ----A UTILISER EN LOCALHOST ------------------ */
	$server = 'localhost';
	$login  = 'root';
	$mdp    = '';
	$db     = 'forum_stage';

		try {

			$link = new PDO("mysql:host=$server;dbname=$db", $login, $mdp);

		} catch(Exception $e){

			die('erreur='.$e->getMessage());
		}
?>