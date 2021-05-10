<?php
session_start();

	require('connexion.php');

	//Récupérer et vérifier si idstage n'est pas null
	if(isset($_GET['idstage'])) {

		$bdd = connexionservermysql($server, $db, $login, $mdp);

		$idstage = $_GET['idstage'];

		// Requête de suppression des données selon idstage
		$sql = 'DELETE FROM stage WHERE Id_stage = :idstage';

		$req = $bdd->prepare($sql);

		$exec = $req->execute(array('idstage'=>$idstage));

		// Vérifier la requête et redirection
		if($exec) {
			header('Location: affichagestageentreprise.php');
			echo "La suppression a bien été effectuée.";
		} else {
			echo "Échec de la suppression.";
		}
	}
?>