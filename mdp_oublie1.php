<?php

    session_start();

    require 'config.php';

?><!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Mot de passe oublié</title>
    </head>
    <body>

            <p>Un lien vous sera envoyé pour récupérer votre mot de passe</p>
            <p>Merci de contacter le responsable du Forum Stage si le problème persiste</p>

            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <p>Adresse mail : <input type="texte" name="recup_mail" /></p>
                <p><input type="submit" name="recup_submit" value="Récupérer mon mot de passe" /></p>
            </form>

        <?php

            if (isset($_POST['recup_submit']) and isset($_POST['recup_mail'])) {

                if(!empty($_POST['recup_mail'])) {

                    $recup_mail = htmlspecialchars($_POST['recup_mail']);

                    // si l'utilisateur a bien rentré une adresse au format mail
                    if (filter_var($_POST['recup_mail'], FILTER_VALIDATE_EMAIL)) {

                        $recup_mail = htmlspecialchars($_POST['recup_mail']);
                        $count_etudiant = '0'; $count_entreprise = '0';

                        // on vérifie si le mail existe dans la base
                        $req_existmail_etudiant = $linkpdo->prepare('SELECT COUNT(*) AS count_etudiant FROM Etudiant WHERE Mail = ?');
                        $req_existmail_etudiant->execute(array($_POST['recup_mail']));
                        $data1 = $req_existmail_etudiant->fetch();
                        $count_etudiant = $data1['count_etudiant'];

                        $req_existmail_entreprise = $linkpdo->prepare('SELECT COUNT(*) AS count_entreprise FROM Entreprise WHERE Mail = ?');
                        $req_existmail_entreprise->execute(array($_POST['recup_mail']));
                        $data2 = $req_existmail_entreprise->fetch();
                        $count_entreprise = $data2['count_entreprise'];
                        
                        // si le mail existe
                        if ($count_etudiant === '1' or $count_entreprise === '1') {

                            // on regarde si le mail appartient à un étudiant ou à une entreprise et on stocke le resultat
                            if ($count_etudiant === '1')
                                $est_etudiant = 1;
                            else 
                                $est_etudiant = 0;

                            $_SESSION['recup_mail'] = $recup_mail;
                            $_SESSION['est_etudiant'] = $est_etudiant;

                            // on génère un code de récupération aléatoire
                            $recup_cle = "";
                            for ($i = 0 ; $i < 8 ; $i++) {
                                $recup_cle.=rand(0,9);
                            }
                            $recup_cle = md5($recup_cle);

                            // on ajoute la cle de récupération à la base de données
                            if ($est_etudiant == 1) {
                                $insert_cle = $linkpdo->prepare('UPDATE Etudiant SET Cle_recuperation = ? WHERE Mail = ?');
                                $insert_cle->execute(array($recup_cle, $recup_mail));
                            } else {
                                $insert_cle = $linkpdo->prepare('UPDATE Entreprise SET Cle_recuperation = ? WHERE Mail = ?');
                                $insert_cle->execute(array($recup_cle, $recup_mail));
                            }

                            $header="MIME-Version: 1.0\r\n";
                            $header.='From:forumstageiut.tk'."\n";
                            $header.='Content-Type:text/html; charset="utf-8"'."\n";
                            $header.='Content-Transfer-Encoding: 8bit';
                            $message = '
                            <html>
                                <head>
                                    <title>Récupération de mot de passe - forumstageiut.tk</title>
                                    <meta charset="utf-8" />
                                </head>
                                <body>
                                    <table align="center" rules="rows">
                                        <tr>
                                            <td>
                                                <h2 align="center">Réinitialisation de votre mot de passe</h2>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>Bonjour,</p>
                                                <p>Afin de réinitialiser votre mot de passe, veuillez cliquer sur le lien ci-dessous.</p>
                                                <p><a href="http://forumstageiut.tk/mdp_oublie2.php?key='.$recup_cle.'">Réinitialiser mon mot de passe</a></p>
                                                <p>Ceci est un mail automatique, merci de ne pas répondre</p>
                                            </td>
                                        </tr>
                                    </table>
                                </body>
                            </html>
                            ';

                            if (mail($recup_mail, "Récupération de mot de passe", $message, $header)) {

                                echo "Le mail a été envoyé avec succès !";

                            } else {
                                echo "Echec de l'envoi du mail";
                            }

                        } else {
                            $error = "Cette adresse mail n'est pas enregistrée";
                        }

                    } else {
                        $error = "Veuillez saisir une adresse mail valide";
                    }

                } else {
                    $error = "Veuillez entrer votre adresse mail";
                }

            }

            if (isset($error)) {
                echo '<span style="color:red">'.$error.'</span>';
            }

        ?>

    </body>
</html>
