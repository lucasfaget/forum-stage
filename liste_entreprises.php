<?php
	include ("config.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Forum Stage | <?php echo $data['NomEntr']; ?></title>
</head>

<body>
	<header>
	
	</header>

	<main>
		<h2>Entreprises présentes</h2>

		<table class="tableau">
			<tr>
<?php
	//Requêtes SQL visant à récupérer la liste de toutes les entreprises
	$reqListeEntreprises = $link->prepare('SELECT *	FROM 
													entreprise'); 

	$reqListeEntreprises->execute();

	$nbEntreprise = 0;
	while ($data = $reqListeEntreprises->fetch()) {
		if ($nbEntreprise == 3) {
?>
			</tr>
			<tr>
<?php
			$nbEntreprise = 0;
		}
?>
				<td> 
					<div>
						<form action="./fiche_entreprise.php" method="POST">
							<?php echo "<img src='./ressources/logos/".$data['NomLogo']."' width='100px' height='100px'>"; ?>
							<?php echo $data['NomEntr']; ?>
							<input 	type="hidden" 
									name="idEntreprise"
									value="<?php echo $data['Id_entreprise']; ?>" >
							<input 	name="consulter"
									type="submit"
									value="consulter">
						</form>
					</div>
				</td>
<?php
		$nbEntreprise++;		
	}
?>
			</tr>
		</table>
	</main>
</body>
</html>

