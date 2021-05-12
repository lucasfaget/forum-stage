<?php
	include ("config.php");

	//Requêtes SQL visant à récupérer les informations de l'entreprise
	$reqFicheEntreprise = $link->prepare('SELECT *	FROM 
													entreprise 
												WHERE 
													entreprise.Id_entreprise = ?');

	$reqFicheEntreprise->execute(array($_POST['idEntreprise']));
	$data = $reqFicheEntreprise->fetch();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Forum Stage | <?php echo $data['NomEntr']; ?></title>
</head>

<body>
    <header>
<?php
    require("header.php");
?>        
    </header>

	<main>
		<p> <?php echo $data['NomEntr']; ?> </p>

		<p>Description :</p>

		<p> <?php echo $data['Presentation']; ?></p>

		<form action="./XXXXXXX" method="POST">
			<button class="boutonActionAjout" 
					type="submit" 
					name="ajouter" >
				<img src="./ressources/consulterStage.png">
			</button>
		</form>
	</main>

</body>
</html>