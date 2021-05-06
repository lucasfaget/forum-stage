<?php
	require 'config.php';

	if(isset($_GET['cleVerif'])){
		//Vérification
		$cleVerif = $_GET['cleVerif'];
		
		$req = $linkpdo->query("
			SELECT Mail_confirme, Cle_confirmation
			FROM Etudiant 
			WHERE Mail_confirme = 0 AND Cle_confirmation = '$cleVerif' LIMIT 5
		");
                

		//Valider l'adresse mail
		$update = $linkpdo->query("
                        UPDATE Etudiant SET Mail_confirme = 1
                        WHERE Cle_confirmation = '$cleVerif' LIMIT 1
		");
		if($update){
                        echo "Votre adresse mail a été vérifiée. Vous pouvez à présent vous authentifier.";
                }else{
			echo $linkpdo->error;
		}		
	}else{
		die("Erreur de vérification, votre adresse n'a pas pu être vérifiée...");
	}
?>