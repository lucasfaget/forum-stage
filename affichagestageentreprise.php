<?php
require('connexion.php');
    //Récupérer et vérifier si identreprise n'est pas nul
    if(isset($_GET['identreprise'])) { //ou session entreprise

        $bdd = connexionservermysql($server, $db, $login, $mdp);

        //Requêtes pour récupérer les données concernant l'entreprise et les afficher sur le formulaire
        $sqlEnt = 'SELECT * FROM entreprise WHERE Id_entreprise = ?';
        $reqEnt = $bdd->prepare($sqlEnt);
        $reqEnt->execute(array($_GET['identreprise']));

        $dataEnt = $reqEnt->fetch();

        $nomEnt = $dataEnt['NomEntr'];
        $presentationEnt = $dataEnt['Presentation'];
        $logoEnt = $dataEnt['NomLogo'];
        $identreprise = $dataEnt['Id_entreprise'];

        $sqlRep = 'SELECT * FROM representant WHERE Id_entreprise = ?';
        $reqRep = $bdd->prepare($sqlRep);
        $reqRep->execute(array($_GET['identreprise']));

        while($dataRep = $reqRep->fetch()) {

            $nomRep1 = $dataRep['NomRepr'][0];
            $prenomRep1 = $dataRep['PrenomRepr'][0];
            $telephoneRep1 = $dataRep['Telephone'][0];
            $debutdispo1 = $dataRep['Debut_dispo'][0];
            $findispo1 = $dataRep['Fin_dispo'][0];
            $idrepresentant1 = $dataRep['Id_representant'][0];
            $nomRep2 = $dataRep['NomRepr'][1];
            $prenomRep2 = $dataRep['PrenomRepr'][1];
            $telephoneRep2 = $dataRep['Telephone'][1];
            $debutdispo2 = $dataRep['Debut_dispo'][1];
            $findispo2 = $dataRep['Fin_dispo'][1];
            $idrepresentant2 = $dataRep['Id_representant'][1];
            $identreprise = $dataEnt['Id_entreprise'];

        }

    }
?>

<!DOCTYPE html>
<html lang=fr>

    <head>
        <title>Espace Entreprise</title>
        <meta charset="utf-8">
        <script src="https://kit.fontawesome.com/2dcde6ae9c.js" crossorigin="anonymous"></script>
        <script type='text/javascript'>
            function confirmationSuppression(id) {
            // Afficher une fenêtre avec les boutons ok ou annuler
            // si ok appuyer
            if( confirm("Etes-vous sûr de vouloir supprimer ce stage ?")) {
                // on fait la redirection vers la page qui effectue l'action
            document.location.replace("supprimer_stage.php?idstage="+id+"");
            }else {
                // ceci annulera l'action par defaut du lien
                return false;
            }
            }
        </script>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <h1>Votre Espace Entreprise</h1>

        <p>Si vous étiez présent au forum l'année précédente vos information ont été conservées, vous pouvez les mettre à jour si besoin.</p>

        <h2>Vos informations</h2>

        <form action="<<?php echo "affichagestagentreprise.php?identreprise=".$identreprise; ?>" method="POST" enctype="multipart/form-data">

            <input type='hidden' name='identreprise' value="<?php echo $identreprise; ?>">

            <label>Présentation de l'entreprise : </label>
            <p><textarea name="presentation" rows="20" cols="100" pattern="[A-Za-z0-9' -#+]+" minlength="2" wrap="hard" spellcheck="true" value="<?php echo $presentationEnt; ?>"></textarea></p>

            <?php

                $bdd = connexionservermysql($server, $db, $login, $mdp);

                // Requête d'affichage des stages selon identreprise
                $sqlAffLogo = 'SELECT * FROM entreprise'; //ajouter WHERE Id_entreprise = ?
                $reqAffLogo = $bdd->prepare($sqlAffLogo);
                $reqAffLogo->execute(); // mettre array('identreprise'=>$identreprise) dans execute
                while ($dataLogo = $reqAffLogo->fetch()) {
                    echo "<img src='./ressources/logos/".$dataLogo['NomLogo']."' width='100px' height='100px'></br>";
                }
                echo "$nomEnt";
            ?>

            <label for="file">Ajouter un logo (.jpg, .jpeg ou .png) :</label>
            <p><input type="file" name="file"></p>

            <p>Représentant de l'entreprise au forum :</p>

            <p>Représentant n°1</p>

            <input type='hidden' name='idrepresentant1' value="<?php echo $idrepresentant1; ?>">

            <label>Nom : </label>
            <p><input type="text" name="nomrep1" pattern="[A-Za-z' -]+" minlength="2" maxlength="50" value="<?php echo $nomRep1; ?>"></p>

            <label>Prénom : </label>
            <p><input type="text" name="prenomrep1" pattern="[A-Za-z' -]+" minlength="2" maxlength="50" value="<?php echo $prenomRep1; ?>"></p>

            <label>Numéro de téléphone : </label>
            <p><input type="text" name="telephonerep1" pattern="[0-9]+" minlength="10" maxlength="10" value="<?php echo $telephoneRep1; ?>"></p>

            <label>Disponibilité : </label>
            <p><input type="time" name="dispodebutrep1" pattern="[0-9:]+" minlength="5" maxlength="8" value="<?php echo $debutdispo1; ?>"></p>
            <p><input type="time" name="dispofinrep1" pattern="[0-9:]+" minlength="5" maxlength="8" value="<?php echo $findispo1; ?>"></p>

            <p>Représentant n°2</p>

            <input type='hidden' name='idrepresentant2' value="<?php echo $idrepresentant2; ?>">

            <label>Nom : </label>
            <p><input type="text" name="nomrep2" pattern="[A-Za-z' -]+" minlength="2" maxlength="50" value="<?php echo $nomRep2; ?>"></p>

            <label>Prénom : </label>
            <p><input type="text" name="prenomrep2" pattern="[A-Za-z' -]+" minlength="2" maxlength="50" value="<?php echo $prenomRep2; ?>"></p>

            <label>Numéro de téléphone : </label>
            <p><input type="text" name="telephonerep2" pattern="[0-9]+" minlength="10" maxlength="10" value="<?php echo $telephoneRep2; ?>"></p>

            <label>Disponibilité : </label>
            <p><input type="time" name="dispodebutrep2" pattern="[0-9:]+" minlength="5" maxlength="8" value="<?php echo $debutdispo2; ?>"></p>
            <p><input type="time" name="dispofinrep2" pattern="[0-9:]+" minlength="5" maxlength="8" value="<?php echo $findispo2; ?>"></p>

            <button type="submit" name="enregistrer"><i class="fas fa-2x fa-check-square"></i><span>Enregistrer les informations</span></button>
        </form>

        <?php
        if ($idrepresentant1 != null && $idrepresentant2 != null) {
            // Vérifier que tous les champs soit remplis et que le bouton enregistrer ne soit pas null
            if(isset($_POST['enregistrer']) && !empty($_POST['presentation']) && !empty($_POST['nomrep1']) && !empty($_POST['prenomrep1']) && !empty($_POST['telephonerep1']) && !empty($_POST['dispodebutrep1']) && !empty($_POST['dispofinrep1'])) {

                $bdd = connexionservermysql($server, $db, $login, $mdp);

                $presentation = $_POST['presentation'];
                $nomrep1 = $_POST['nomrep1'];
                $prenomrep1 = $_POST['prenomrep1'];
                $telephonerep1 = $_POST['telephonerep1'];
                $dispodebutrep1 = $_POST['dispodebutrep1'];
                $dispofinrep1 = $_POST['dispofinrep1'];
                $idrepresentant1 = $_POST['idrepresentant1'];
                $nomrep2 = $_POST['nomrep2'];
                $prenomrep2 = $_POST['prenomrep2'];
                $telephonerep2 = $_POST['telephonerep2'];
                $dispodebutrep2 = $_POST['dispodebutrep2'];
                $dispofinrep2 = $_POST['dispofinrep2'];
                $idrepresentant2 = $_POST['idrepresentant2'];
                $identreprise = $_POST['identreprise'];

                // Requête de mise à jour des données
                $sqlEnt = 'UPDATE entreprise SET Presentation = :presentation WHERE Id_entreprise = :identreprise';

                $reqEnt = $bdd->prepare($sqlEnt);

                $execEnt = $reqEnt->execute(array('presentation'=>$presentation, 'identreprise'=>$identreprise));

                $sqlRep1 = 'UPDATE representant SET NomRepr = :nom, PrenomRepr = :prenom, Telephone = :telephone, Debut_dispo = :debutdispo, Fin_dispo = :findispo WHERE Id_entreprise = :identreprise AND Id_representant = :idrepresentant';

                $reqRep1 = $bdd->prepare($sqlRep1);

                $execRep1 = $reqRep1->execute(array('nom'=>$nomrep1, 'prenom'=>$prenomrep1, 'telephone'=>$telephonerep1, 'debutdispo'=>$dispodebutrep1, 'findispo'=>$dispofinrep1, 'identreprise'=>$identreprise, 'idrepresentant'=>$idrepresentant1));

                $execRep2 = $reqRep1->execute(array('nom'=>$nomrep2, 'prenom'=>$prenomrep2, 'telephone'=>$telephonerep2, 'debutdispo'=>$dispodebutrep2, 'findispo'=>$dispofinrep2, 'identreprise'=>$identreprise, 'idrepresentant'=>$idrepresentant2));

                // Vérifier la requête et redirection
                if($execRep2 && $execRep1) {
                    echo "La modification a bien été enregistrée.";
                } else {
                    echo "Échec de la modification.";
                }
            }
        } elseif ($idrepresentant1 == null && $idrepresentant2 == null) {
            // Vérifier que tous les champs soit remplis et que le bouton enregistrer ne soit pas null
            if(isset($_POST['enregistrer']) && !empty($_POST['presentation']) && !empty($_POST['nomrep1']) && !empty($_POST['prenomrep1']) && !empty($_POST['telephonerep1']) && !empty($_POST['dispodebutrep1']) && !empty($_POST['dispofinrep1'])) {

                $bdd = connexionservermysql($server, $db, $login, $mdp);

                $presentation = $_POST['presentation'];
                $nomrep1 = $_POST['nomrep1'];
                $prenomrep1 = $_POST['prenomrep1'];
                $telephonerep1 = $_POST['telephonerep1'];
                $dispodebutrep1 = $_POST['dispodebutrep1'];
                $dispofinrep1 = $_POST['dispofinrep1'];
                $idrepresentant1 = $_POST['idrepresentant1'];
                $nomrep2 = $_POST['nomrep2'];
                $prenomrep2 = $_POST['prenomrep2'];
                $telephonerep2 = $_POST['telephonerep2'];
                $dispodebutrep2 = $_POST['dispodebutrep2'];
                $dispofinrep2 = $_POST['dispofinrep2'];
                $idrepresentant2 = $_POST['idrepresentant2'];
                $identreprise = $_POST['identreprise'];

                // Requête de mise à jour des données
                $sqlEnt = 'UPDATE entreprise SET Presentation = :presentation WHERE Id_entreprise = :identreprise';

                $reqEnt = $bdd->prepare($sqlEnt);

                $execEnt = $reqEnt->execute(array('presentation'=>$presentation, 'identreprise'=>$identreprise));

                $sqlRep1 = 'INSERT INTO representant(NomRepr, PrenomRepr, Telephone, Debut_dispo, Fin_dispo, Id_entreprise) VALUES (:nom, :prenom, :telephone, :debutdispo, :findispo, :identreprise)';

                $reqRep1 = $bdd->prepare($sqlRep1);

                $execRep1 = $reqRep1->execute(array('nom'=>$nomrep1, 'prenom'=>$prenomrep1, 'telephone'=>$telephonerep1, 'debutdispo'=>$dispodebutrep1, 'findispo'=>$dispofinrep1, 'identreprise'=>$identreprise));

                $execRep2 = $reqRep1->execute(array('nom'=>$nomrep2, 'prenom'=>$prenomrep2, 'telephone'=>$telephonerep2, 'debutdispo'=>$dispodebutrep2, 'findispo'=>$dispofinrep2, 'identreprise'=>$identreprise));

                // Vérifier la requête et redirection
                if($execRep2 && $execRep1) {
                    echo "Les données ont bien été enregistrées.";
                } else {
                    echo "Échec de l'enregistrement.";
                }
            }
        }

        //uploader un fichier dans un dossier
        if(isset($_FILES['file'])){
            //variables créés par PHP
            //nom temporaire pour manipulation
            $tmpName = $_FILES['file']['tmp_name'];
            //nom du fichier
            $name = $_FILES['file']['name'];
            //taille du fichier
            $size = $_FILES['file']['size'];
            //0 si le fichier ne comporte pas d'erreur
            $error = $_FILES['file']['error'];

            //vérifier l'extension du fichier
            $tabExtension = explode('.', $name);
            $extension = strtolower(end($tabExtension));
            //Tableau des extensions que l'on accepte
            $extensions = ['jpg', 'png', 'jpeg'];

            //controler la taille du fichier
            //Taille max que l'on accepte
            $maxSize = 500000;

            if(in_array($extension, $extensions) && $size <= $maxSize && $error == 0){
                //avoir un nom unique pour chaque fichier stocké
                $uniqueName = uniqid('', true);
                //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
                $file = $uniqueName.".".$extension;
                //$file = 5f586bf96dcd38.73540086.jpg
                move_uploaded_file($tmpName, './ressources/logos/'.$file);

                $sqlLogo = 'INSERT INTO entreprise(NomLogo) VALUES (?)';
                $reqLogo = $bdd->prepare($sqlLogo);
                $execLogo = $reqLogo->execute(array($file));

                // vérifier si la requête d'insertion a réussi et redirection
                if($execLogo){
                    echo "L'image a bien été enregistré.";
                }else{
                    echo "Échec de l'enregistrement de l'image.";
                }

            } elseif ($size > $maxSize) {
                echo "La taille du fichier est trop grande.</br> Elle ne doit pas dépasser 500Ko.";
            //vérifier s'il y a une erreur dans le fichier
            } elseif ($error != 0) {
                echo "Le fichier contient une erreur.";
            } else {
                echo "L'extension du fichier du logo n'est pas correct.";
            }

        }
        ?>

        <h2>Stages</h2>

        <p>Liste des stages proposés : </p>

        <p>*Vous ne pourrez attribuer les différents postes qu'après le forum.</p>




<?php
        // Ajouter if(isset($_GET['identreprise'])) (ou SESSION entreprise) {

        // Requête d'affichage des stages selon identreprise
        $sql = 'SELECT * FROM stage ORDER BY Intitule'; //ajouter WHERE Id_entreprise = ?
        $req = $bdd->prepare($sql);
        $req->execute(); // mettre array('identreprise'=>$identreprise) dans execute

        // Ajout en tête colonne tableau
        echo "<table>";

            echo "<thead>";
                echo "<tr>";
                    echo "<th scope=\"col\">Intitulé du stage</th>";
                    echo "<th scope=\"col\">Nombre de postes</th>";
                    echo "<th scope=\"col\">Attribuer le(s) poste(s)*</th>";
                    echo "<th scope=\"col\">Modifier</th>";
                    echo "<th scope=\"col\">Supprimer</th>";
                echo "</tr>";
            echo "</thead>";

        //Récuperer toutes les lignes des stages selon identreprise et les mettre dans le tableau
        while ($data = $req->fetch()) {

            $idstage = $data['Id_stage'];
            $intitulestage = $data['Intitule'];
            $nbpostesstage = $data['Nombre_postes'];

            echo "<tbody>";
                echo "<tr>";
                    echo "<td>".$intitulestage."</td>";
                    echo "<td>".$nbpostesstage."</td>"; ?>
                    <td><input type=text list=candidat>
                                <datalist id=candidat>
                                    <?php
                                //$bdd = connexionservermysql($server, $db, $login, $mdp);
                                $sqlCreneau = 'SELECT * FROM reserver WHERE Id_stage = ? ';
                                $reqIdStage = $bdd->prepare($sqlCreneau);
                                $reqIdStage->execute(array($idstage));
                                while($dataIdStage = $reqIdStage->fetch()) {
                                    echo $dataIdStage['Id_etudiant'];
?>
                                    <option><?php $dataIdStage['Id_etudiant']; } ?></option>
                                </datalist></td>
                    <?php
                    echo "<td><a href=\"modifier_stage.php?idstage=".$idstage."\"><i class=\"far fa-2x fa-edit\"></i></a></td>";
                    echo "<td><i class=\"fas fa-2x fa-trash-alt\" onclick=\"confirmationSuppression(".$idstage.")\"></i></td>";
                echo "</tr>";
            echo "</tbody>";
        }
        echo "</table>";

        //Ajout bouton pour ajouter stage avec redirection
        echo "<a href=\"ajouter_stage.php\"><i class=\"fas fa-2x fa-plus-square\"></i><span> Nouveau stage</span></a>";

        //Ajouter }
?>
    </body>

</html>