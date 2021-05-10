<!DOCTYPE html>
<html lang=fr>

    <head>
        <title>Stages</title>
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

        <h1>Stages</h1>

        <p>Liste des stages proposés : </p>

        <p>*Vous ne pourrez attribuer les différents postes qu'après le forum.</p>

    </body>

</html>

<?php
        require('connexion.php');

        // Ajouter if(isset($_GET['identreprise'])) (ou SESSION entreprise) {

        $bdd = connexionservermysql($server, $db, $login, $mdp);

        // Requête d'affichage des stages selon identreprise
        $sql = 'SELECT * FROM stage ORDER BY Intitule'; //ajouter WHERE Id_entreprise = ?
        $req = $bdd->prepare($sql);
        $req->execute(); // mettre array('identreprise'=>$identreprise) dans execute

        // Ajout en tête colonne
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

        //Récuperer toutes les lignes des stages selon identreprise et les mettre dans le tableaux
        while ($data = $req->fetch()) {

            $idstage = $data['Id_stage'];
            $intitulestage = $data['Intitule'];
            $nbpostesstage = $data['Nombre_postes'];

            echo "<tbody>";
                echo "<tr>";
                    echo "<td>".$intitulestage."</td>";
                    echo "<td>".$nbpostesstage."</td>";
                    echo "<td><input type=text list=candidat>
                                <datalist id=candidat>
                                    <option> Candidat 1
                                    <option> Candidat 2
                                </datalist></td>";
                    echo "<td><a href=\"modifier_stage.php?idstage=".$idstage."\"><i class=\"far fa-2x fa-edit\"></i></a></td>";
                    echo "<td><i class=\"fas fa-2x fa-trash-alt\" onclick=\"confirmationSuppression(".$idstage.")\"></i></td>";
                echo "</tr>";
            echo "</tbody>";
        }
        echo "</table>";

        //Ajout bouton pour ajouter stage avec redirection
        echo "<a href=\"ajoutpatient.php\"><i class=\"fas fa-2x fa-user-plus\"></i><span> Nouveau stage</span></a>";

        //Ajouter }
?>