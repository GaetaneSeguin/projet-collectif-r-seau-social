<?php
session_start();
$currentId = $_SESSION['connected_id'];
include './scripts/connexion.php'; ?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Inscription</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="login.css" />
</head>

<body>
    <main>
        <article>
            <h1>Inscription</h1>
            <?php $enCoursDeTraitement = isset($_POST['email']);
            if ($enCoursDeTraitement) {
                $new_email = $_POST['email'];
                $new_alias = $_POST['pseudo'];
                $new_passwd = $_POST['motpasse'];
                $new_email = $mysqli->real_escape_string($new_email);
                $new_alias = $mysqli->real_escape_string($new_alias);
                $new_passwd = $mysqli->real_escape_string($new_passwd);
                $new_passwd = md5($new_passwd);
                $lInstructionSql = "INSERT INTO users (id, email, password, alias) "
                    . "VALUES (NULL, "
                    . "'" . $new_email . "', "
                    . "'" . $new_passwd . "', "
                    . "'" . $new_alias . "'"
                    . ");";
                $ok = $mysqli->query($lInstructionSql);
                if (!$ok) {
                    echo "L'inscription a échouée : " . $mysqli->error;
                } else {
                    $message =  "Votre inscription est un succès " . $new_alias;                    // Redirection vers une autre page
                    header('Location: login.php?confirmationMessage=' . $message);
                    exit();
                }
            }
            ?>
            <form action="registration.php" method="post"> <label for='pseudo'>Pseudo</label>
                <input type='text' name='pseudo'>
                <label for='email'>E-Mail</label>
                <input type='email' name='email'>
                <label for='motpasse'>Mot de passe</label>
                <input type='password' name='motpasse'>
                </dl>
                <button class="btn-style" type='submit'>S'inscrire</button>
            </form>
        </article>
    </main>
    </div>
</body>

</html>