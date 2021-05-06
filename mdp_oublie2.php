<?php

    session_start();

    require 'ecritureMDP.php';

    require 'config.php';

?><!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Mot de passe oublié</title>
    </head>
    <body>

        <?php

            if (isset($_GET['key']) and !empty($_GET['key'])) {

                $recup_cle = htmlspecialchars($_GET['key']);
                $count_etudiant = '0'; $count_entreprise = '0';

                if ($_SESSION['est_etudiant'] == 1) {
                    $verif_recup = $linkpdo->prepare('SELECT COUNT(*) AS count_etudiant FROM Etudiant WHERE Mail = ? AND Cle_recuperation = ?');
                    $verif_recup->execute(array($_SESSION['recup_mail'], $recup_cle));
                    $data = $verif_recup->fetch();
                    $count_etudiant = $data['count_etudiant'];
                } else {
                    $verif_recup = $linkpdo->prepare('SELECT COUNT(*) AS count_entreprise FROM Entreprise WHERE Mail = ? AND Cle_recuperation = ?');
                    $verif_recup->execute(array($_SESSION['recup_mail'], $recup_cle));
                    $data = $verif_recup->fetch();
                    $count_entreprise = $data['count_entreprise'];
                }

                if ($count_etudiant === '1' or $count_entreprise === '1') {

        ?>

                    <h1>Récupération de mot de passe</h1>

                    	<p>Merci de saisir un nouveau mot de passe pour votre compte</p>

                    	<form method="post">
                    		<p>Choisissez un nouveau mot de passe : <input type="text" name="mot_de_passe1" /></p>
                            <p>Entrez à nouveau votre mot de passe : <input type="text" name="mot_de_passe2" /></p>
                    		<p><input type="submit" name="change_submit" value="Enregistrer le nouveau mot de passe" /></p>
                    	</form>

        <?php

                    if (isset($_POST['change_submit'])) {

                        if (isset($_POST['mot_de_passe1']) and isset($_POST['mot_de_passe2'])) {

                            $mdp1 = htmlspecialchars($_POST['mot_de_passe1']);
                            $mdp2 = htmlspecialchars($_POST['mot_de_passe2']);

                            if (!empty($mdp1) and !empty($mdp2)) {

                                // si la saisie du mot de passe vérifie toutes les conditions et qu'ils sont identiques
                                // la fonction passwordOk est dans ecritureMDP.php
                                if (passwordOk($mdp1) and passwordOk($mdp2) and $mdp1 === $mdp2) {

                                    // on crypte le mdp
                                    $mdp = password_hash($mdp1, PASSWORD_BCRYPT);
                                    // on l'insère dans la base
                                    if ($_SESSION['est_etudiant'] == 1) {
                                        $insert_password = $linkpdo->prepare('UPDATE Etudiant SET Mot_de_passe = ? WHERE Mail = ?');
                                        $insert_password->execute(array($mdp, $_SESSION['recup_mail']));
                                        // on supprime la cle de récupération de la base de données
                                        $delete_cle_recup = $linkpdo->prepare('UPDATE Etudiant SET Cle_recuperation = ? WHERE Mail = ?');
                                        $delete_cle_recup->execute(array(0, $SESSION['recup_mail']));
                                        echo "Votre mot de passe a été correctement modifié";
                                        // rediriger vers la page de connexion
                                        header('Location: index.php');

                                    } else {
                                        $insert_password = $linkdpo->prepare('UPDATE Entreprise SET Mot_de_passe = ? WHERE Mail = ?');
                                        $insert_password->execute(array($mdp, $_SESSION['recup_mail']));
                                        // on supprime la cle de récupération de la base de données
                                        $delete_cle_recup = $linkpdo->prepare('UPDATE Entreprise SET Cle_recuperation = ? WHERE Mail = ?');
                                        $delete_cle_recup->execute(array(0, $SESSION['recup_mail']));
                                        echo "Votre mot de passe a été correctement modifié";
                                        // rediriger vers la page de connexion
                                        header('Location: index.php');
                                    }

                                } else {

                                    $error = "";

                                    if ($mdp1 != $mdp2)
                                        $error .= "Les mots de passe ne correspondent pas".'<br>';

                                    // les fonctions suivantes sont dans ecritureMDP.php
                                    if (!eight_char_at_least($mdp1) or !eight_char_at_least($mdp2))
                                        $error .= "Le mot de passe doit contenir au moins 8 caractères".'<br>';

                                    if (!contains_upper($mdp1) or !contains_upper($mdp2))
                                        $error .= "Le mot de passe doit contenir au moins une majuscule".'<br>';

                                    if (!contains_lower($mdp1) or !contains_lower($mdp2))
                                        $error .= "Le mot de passe doit contenir au moins une minuscule".'<br>';

                                    if (!contains_numeral($mdp1) or !contains_numeral($mdp2))
                                        $error .= "Le mot de passe doit contenir au moins un chiffre".'<br>';

                                    if (!contains_symbol($mdp1) or !contains_symbol($mdp2))
                                        $error .= "Le mot de passe doit contenir au moins un caractère spécial".'<br>';

                                    if (contains_space($mdp1) or contains_space($mdp2))
                                        $error .= "Le mot de passe ne doit pas contenir d'espace".'<br>';

                                }

                            } else {
                                $error = "Veuillez remplir tous les champs";
                            }

                        }

                    }

                    if (isset($error))
                        echo '<span style="color:red">'.$error.'</span>';

                } else {
                    echo "Ce lien n'existe pas";
                }

            } else {
                echo "Ce lien n'existe pas";
            }

        ?>

    </body>
</html>