<?php

	session_start();

	require('connexionbdd.php');

	//Récupérer et vérifier si idstage n'est pas null
	if(isset($_GET['idstage'])) {

		$bdd = connexionservermysql($server, $db, $login, $mdp);

		$idstage = $_GET['idstage'];

		// Requête de suppression des données selon idstage
		$sql = 'DELETE FROM Stage WHERE Id_stage = :idstage';

		$req = $bdd->prepare($sql);

		$exec = $req->execute(array('idstage'=>$idstage));

		// Vérifier la requête et redirection
		if($exec) {
			echo "La suppression a bien été effectuée.";
			header('Location: affichagestage.php');
		} else {
			echo "Échec de la suppression.";
		}
	}
?>