<!DOCTYPE html>
<html>

    <head>
        <title>Stages</title>
    </head>

    <body>

        <h1>Votre espace étudiant</h1>

        <p>Créneaux reservés : </p>

    </body>

</html>

<?php
        require('util.php');

        $bdd = connexionservermysql($server, $db, $login, $mdp);

        $sql = 'SELECT stage.Intitule, entreprise.Nom, reserver.Creneau FROM stage, entreprise, reserver, etudiant WHERE stage.Id_stage = reserver.Id_stage AND etudiant.Id_etudiant = reserver.Id_etudiant AND entreprise.Id_entreprise = stage.Id_entreprise AND etudiant.Id_etudiant = ?';

        $req = $bdd->prepare($sql);

        //$req->execute(array($_GET['idstage']));

        $req->execute(array(1));

        echo "<table>";

            echo "<thead>";
                echo "<tr>";
                    echo "<th scope=\"col\">Intitulé du stage</th>";
                    echo "<th scope=\"col\">Organisme</th>";
                    echo "<th scope=\"col\">Heures</th>";
                    echo "<th scope=\"col\">Se désincrire</th>";
                echo "</tr>";
            echo "</thead>";

        while ($data = $req->fetch()) {

            $idstage = $data['Id_stage'];
            $idetudiant = $data['Id_etudiant'];
            $intitulestage = $data['stage.Intitule'];
            $nomentreprise = $data['entreprise.Nom'];
            $creneau = $data['reserver.Creneau'];

            echo "<tbody>";
                echo "<tr>";
                    echo "<td>".$intitulestage."</td>";
                    echo "<td>".$nomentreprise."</td>";
                    echo "<td>".$creneau."</td>";
                    echo "<td><a href=\"suppressionrdv.php?idstage=\".$idstage.\"&idetudiant=\".$idetudiant\"><img src=\"supprimer.png\" alt=\"logo supprimer\"/></a></td>";
                echo "</tr>";
            echo "</tbody>";
        }

        echo "</table>";

?>