<?php
session_start();

require('connexionbdd.php');

// ajouter à la base de données si le bouton enregistrer a été appuyé et que tous les champs nécessaires sont remplis
if(isset($_POST['Enregistrer']) && !empty($_POST['intitulestage']) && !empty($_POST['dureestage']) && !empty($_POST['nombreetudiants']) && !empty($_POST['descriptionstage']) && !empty($_POST['motcles']) && !empty($_POST['competence1']) && !empty($_POST['entreprise'])){ //ajouter && isset($_GET['Id_entreprise'])
  
  $bdd = connexionservermysql($server, $db, $login, $mdp);

  // récupérer les valeurs 
  $intitulestage = $_POST['intitulestage'];
  $dureestage = $_POST['dureestage'];
  $nombreetudiants = $_POST['nombreetudiants'];
  $descriptionstage = $_POST['descriptionstage'];
  $motcles = $_POST['motcles'];
  $competence = $_POST['competence1'].' '.$_POST['competence2'].' '.$_POST['competence3'];
  $entreprise = $_POST['entreprise'];
  //$entreprise = $_GET['Id_entreprise'];

  // Requête pour insérer des données
  $sql = 'INSERT INTO Stage(Intitule, Description, Duree, Nombre_postes, Competence_requise, Mot_cles, Id_entreprise) VALUES (:intitule, :description, :duree, :nombrepostes, :competencerequise, :motcles, :entreprise)';
  $req = $bdd->prepare($sql);
  $exec = $req->execute(array('intitule'=>$intitulestage,'description'=>$descriptionstage, 'duree'=>$dureestage,'nombrepostes'=>$nombreetudiants, 'competencerequise'=>$competence,'motcles'=>$motcles, 'entreprise'=>$entreprise));

  // vérifier si la requête d'insertion a réussi et redirection
  if($exec){
    //echo "L'ajout a bien été enregistré.";
    header('Location: http://forumstage.atwebpages.com//affichagestage.php');
  }else{
    echo "Échec de l'ajout.";
  }
}

// Message d'erreur si ceratins champs nécessaires ne sont pas remplis
if(isset($_POST['Enregistrer']) && empty($_POST['intitulestage'])) {
  echo 'Le champ intitulé doit être rempli.<br/><br/>';
}

if(isset($_POST['Enregistrer']) && empty($_POST['dureestage'])) {
  echo 'Le champ durée doit être rempli.<br/><br/>';
}

if(isset($_POST['Enregistrer']) && empty($_POST['nombreetudiants'])) {
  echo 'Le champ nombre d\'étudiants doit être rempli.<br/><br/>';
}

if(isset($_POST['Enregistrer']) && empty($_POST['descriptionstage'])) {
  echo 'Le champ description doit être rempli.<br/><br/>';
}

if(isset($_POST['Enregistrer']) && empty($_POST['motcles'])) {
  echo 'Le champ mot-clés doit être rempli.<br/><br/>';
}

if(isset($_POST['Enregistrer']) && empty($_POST['competence1'])) {
  echo 'Au moins le premier champ compétences doit être rempli.';
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

      <label>Durée du stage : </label>
      <p><input type="text" placeholder="En semaine" name="dureestage"></p>

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