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

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">

            <label>Présentation de l'entreprise : </label>
            <p><textarea name="presentation" rows="20" cols="100" pattern="[A-Za-z0-9' -#+]+" minlength="2" wrap="hard" spellcheck="true"></textarea></p>

            <?php
                require('connexion.php');

                $bdd = connexionservermysql($server, $db, $login, $mdp);

                // Requête d'affichage des stages selon identreprise
                $sqlAffLogo = 'SELECT * FROM entreprise'; //ajouter WHERE Id_entreprise = ?
                $reqAffLogo = $bdd->prepare($sqlAffLogo);
                $reqAffLogo->execute(); // mettre array('identreprise'=>$identreprise) dans execute
                while ($dataLogo = $reqAffLogo->fetch()) {
                    echo "<img src='./ressources/logos/".$dataLogo['NomLogo']."' width='100px' height='100px'></br>";
                }
            ?>

            <label for="file">Ajouter un logo (.jpg, .jpeg ou .png) :</label>
            <p><input type="file" name="file"></p>

            <p>Représentant de l'entreprise au forum :</p>

            <p>Représentant n°1</p>

            <label>Nom : </label>
            <p><input type="text" name="nom" pattern="[A-Za-z' -]+" minlength="2" maxlength="50"></p>

            <label>Prénom : </label>
            <p><input type="text" name="prenom" pattern="[A-Za-z' -]+" minlength="2" maxlength="50"></p>

            <label>Numéro de téléphone : </label>
            <p><input type="text" name="telephone" pattern="[0-9]+" minlength="10" maxlength="10"></p>

            <label>Disponibilité : </label>
            <p><input type="time" name="dispodebut" pattern="[0-9:]+" minlength="5" maxlength="8"></p>
            <p><input type="time" name="dispofin" pattern="[0-9:]+" minlength="5" maxlength="8"></p>

            <p>Représentant n°2</p>

            <label>Nom : </label>
            <p><input type="text" name="nom" pattern="[A-Za-z' -]+" minlength="2" maxlength="50"></p>

            <label>Prénom : </label>
            <p><input type="text" name="prenom" pattern="[A-Za-z' -]+" minlength="2" maxlength="50"></p>

            <label>Numéro de téléphone : </label>
            <p><input type="text" name="telephone" pattern="[0-9]+" minlength="10" maxlength="10"></p>

            <label>Disponibilité : </label>
            <p><input type="time" name="dispodebut" pattern="[0-9:]+" minlength="5" maxlength="8"></p>
            <p><input type="time" name="dispofin" pattern="[0-9:]+" minlength="5" maxlength="8"></p>

            <button type="submit" name="enregistrer"><i class="fas fa-2x fa-check-square"></i><span>Enregistrer les informations</span></button>
        </form>

        <?php
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