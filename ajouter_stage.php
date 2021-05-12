<?php
session_start();

require('connexion.php');

// ajouter à la base de données si le bouton enregistrer a été appuyé et que tous les champs nécessaires sont remplis
if(isset($_POST['Enregistrer']) && !empty($_POST['intitulestage']) && !empty($_POST['dureestage']) && !empty($_POST['nombreetudiants']) && !empty($_POST['descriptionstage']) && !empty($_POST['competence']) && !empty($_POST['entreprise'])){ //ajouter && isset($_GET['Id_entreprise']) ou session
  
  $bdd = connexionservermysql($server, $db, $login, $mdp);

  // récupérer les valeurs 
  $intitulestage = $_POST['intitulestage'];
  $dureestage = $_POST['dureestage'];
  $nombreetudiants = $_POST['nombreetudiants'];
  $descriptionstage = $_POST['descriptionstage'];
  $competence = $_POST['competence'];
  $entreprise = $_POST['entreprise'];
  //$entreprise = $_GET['Id_entreprise']; ou session

  // Requête pour insérer des données
  $sql = 'INSERT INTO stage(Intitule, Description, Duree, Nombre_postes, Competence_requise, Id_entreprise) VALUES (:intitule, :description, :duree, :nombrepostes, :competencerequise, :entreprise)';
  $req = $bdd->prepare($sql);
  $exec = $req->execute(array('intitule'=>$intitulestage,'description'=>$descriptionstage, 'duree'=>$dureestage,'nombrepostes'=>$nombreetudiants, 'competencerequise'=>$competence, 'entreprise'=>$entreprise));

  // vérifier si la requête d'insertion a réussi et redirection
  if($exec){
    header('Location: affichagestageentreprise.php');
    echo "L'ajout a bien été enregistré.";
  }else{
    echo "Échec de l'ajout.";
  }
}
?>

<!DOCTYPE html>
<html lang=fr>

  <head>
    <title>Ajouter stage</title>
    <meta charset="utf-8">
    <script src="https://kit.fontawesome.com/2dcde6ae9c.js" crossorigin="anonymous"></script>
  </head>

  <body>

    <header>
<?php
    require("header.php");
?>        
    </header>

    <h1>Ajout stage</h1>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

      <label>Intitulé du stage : </label>
      <p><input type="text" name="intitulestage" pattern="[A-Za-z0-9' -#+]+" minlength="2" maxlength="50"></p>

      <label>Durée du stage : </label>
      <p><input type="text" placeholder="En semaine" name="dureestage" pattern="[0-9]+" minlength="1" maxlength="3"></p>

      <label>Nombre d'étudiants acceptés pour ce stage : </label>
      <p><input type="text" name="nombreetudiants" pattern="[1-9]+" minlength="1" maxlength="1"></p>

      <label>Description du stage : </label>
      <p><input type="text" name="descriptionstage" pattern="[A-Za-z0-9' -#+]+" minlength="2" maxlength="50"></p>

      <label>Compétences clés : </label>
      <p><input type="text" name="competence" pattern="[A-Za-z0-9' -#+]+" minlength="2" maxlength="50"></p>

      <label>Entreprise : </label>
      <p><input type="text" name="entreprise"></p>
      <!--A remplace par ça sauf si session à supprimer <input type='hidden' name='idstage' value="<?php //echo $entreprise; ?>">-->

      <button type="submit" name="Enregistrer"><i class="fas fa-2x fa-check-square"></i><span>Valider</span></button>
      <a href="affichagestageentreprise.php"><i class="fas fa-2x fa-window-close"></i><span>Annuler</span></a>

    </form>
    <?php
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

      if(isset($_POST['Enregistrer']) && empty($_POST['competence'])) {
        echo 'Le champ compétences doit être rempli.';
      }
    ?>

  </body>

</html>