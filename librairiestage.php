<?php

require('connexion.php');

// ajouter à la base de données si le bouton enregistrer a été appuyé et que tous les camps sont remplis
if(isset($_POST['Enregistrer']) && !empty($_POST['intitulestage']) && !empty($_POST['dureestage']) && !empty($_POST['nombreetudiants']) && !empty($_POST['descriptionstage']) && !empty($_POST['motcles']) && !empty($_POST['competence1']) && !empty($_POST['competence2']) && !empty($_POST['competence3']) && !empty($_POST['entreprise'])){
  
  $bdd = connexionservermysql($server, $db, $login, $mdp);

  // récupérer les valeurs 
  $intitulestage = $_POST['intitulestage'];
  $dureestage = $_POST['dureestage'];
  $nombreetudiants = $_POST['nombreetudiants'];
  $descriptionstage = $_POST['descriptionstage'];
  $motcles = $_POST['motcles'];
  $competence = $_POST['competence1'].' '.$_POST['competence2'].' '.$_POST['competence3'];
  $entreprise = $_POST['entreprise'];

  // Requête mysql pour insérer des données
  $req = 'INSERT INTO stage(Intitule, Description, Duree, Nombre_postes, Competence_requise, Mot_cles, Id_entreprise) VALUES (:intitule, :description, :duree, :nombrepostes, :competencerequise, :motcles, :entreprise)';
  $res = $bdd->prepare($req);
  $exec = $res->execute(array('intitule'=>$intitulestage,'description'=>$descriptionstage, 'duree'=>$dureestage,'nombrepostes'=>$nombreetudiants, 'competencerequise'=>$competence,'motcles'=>$motcles, 'entreprise'=>$entreprise));

  // vérifier si la requête d'insertion a réussi
  if($exec){
    echo 'Données insérées';
  }else{
    echo "Échec de l'ajout";
  }

}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Ajouter stage</title>
  </head>
  <body>
    <h1>Ajout stage</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
      <label>Intitulé du stage : </label>
      <p><input type="text" name="intitulestage"></p>
      <label>Durée du stage (en semaine) : </label>
      <p><input type="text" name="dureestage"></p>
      <label>Nombre d'étudiants acceptés pour ce stage : </label>
      <p><input type="text" name="nombreetudiants"></p>
      <label>Description du stage : </label>
      <p><input type="text" name="descriptionstage"></p>
      <label>Mot-clés : </label>
      <p><input type="text" name="motcles"></p>
      <label>Compétences clés : </label>
      <p><input type="text" name="competence1"></p>
      <p><input type="text" name="competence2"></p>
      <p><input type="text" name="competence3"></p>
      <label>Entreprise : </label>
      <p><input type="text" name="entreprise"></p>
      <p><input type="submit" name="Enregistrer" value="Enregistrer les informations"></p>
    </form>
  </body>
</html>