<!DOCTYPE html>
<html>

    <head>
        <title>Stages</title>
    </head>

    <body>

        <h1>Stages</h1>

        <p>Liste des stages proposés : </p>

        <p>*Vous ne pourrez attribuer les différents postes qu'après le forum.</p>

    </body>

</html>

<?php
        require('util.php');

        // Ajouter if(isset($_GET['identreprise'])) (ou SESSION entreprise) {

        $bdd = connexionservermysql($server, $db, $login, $mdp);

        // Requête d'affichage des stages selon identreprise
        $sql = 'SELECT * FROM stage'; //ajouter WHERE Id_entreprise = ?
        $req = $bdd->prepare($sql);
        $req->execute(); // mettre array('identreprise'=>$identreprise) dans execute

        // Ajout en tête colonne
        echo "<table>";

            echo "<thead>";
                echo "<tr>";
                    echo "<th scope=\"col\">Numéro du stage</th>";
                    echo "<th scope=\"col\">Intitulé du stage</th>";
                    echo "<th scope=\"col\">Nombre de postes</th>";
                    echo "<th scope=\"col\">Attribuer le(s) poste(s)*</th>";
                    echo "<th scope=\"col\"></th>";
                    echo "<th scope=\"col\"></th>";
                echo "</tr>";
            echo "</thead>";

        //Récuperer toutes les lignes des stages selon identreprise et les mettre dans le tableaux
        while ($data = $req->fetch()) {

            $idstage = $data['Id_stage'];
            $intitulestage = $data['Intitule'];
            $nbpostesstage = $data['Nombre_postes'];

            echo "<tbody>";
                echo "<tr>";
                    echo "<td>".$idstage."</td>";
                    echo "<td>".$intitulestage."</td>";
                    echo "<td>".$nbpostesstage."</td>";
                    echo "<td><input type=text list=candidat>
                                <datalist id=candidat>
                                    <option> Candidat 1
                                    <option> Candidat 2
                                </datalist></td>";
                    echo "<td><a href=\"modificationstage.php?idstage=".$idstage."\"><img src=\"modifier.png\" alt=\"logo modifier\"/></a></td>";
                    echo "<td><a href=\"suppressionstage.php?idstage=".$idstage."\"><img src=\"supprimer.png\" alt=\"logo supprimer\"/></a></td>";
                echo "</tr>";
            echo "</tbody>";
        }

        echo "</table>";

        //Ajout bouton pour ajouter stage avec redirection
        echo "<a href=\"ajoutstage.php\"><img src=\"ajouter.png\" alt=\"logo ajouter\"/></a>";

        //Ajouter }
?>