<?php
session_start();
include './scripts/connexion.php';
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Connexion</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="login.css" />
</head>

<body>
    <main>
        <?php
        if (isset($_GET['confirmationMessage'])) {
            $message = $_GET['confirmationMessage'];
        ?>
            <div id="confirmationMessage"> <?php echo $message ?> </div>;
        <?php
        }
        ?>
        <article>
            <h1>Connexion</h1>
            <?php $enCoursDeTraitement = isset($_POST['email']);
            if ($enCoursDeTraitement) {
                $emailAVerifier = $_POST['email'];
                $passwdAVerifier = $_POST['motpasse'];
                $emailAVerifier = $mysqli->real_escape_string($emailAVerifier);
                $passwdAVerifier = $mysqli->real_escape_string($passwdAVerifier);
                $passwdAVerifier = md5($passwdAVerifier);
                $lInstructionSql = "SELECT * "
                    . "FROM users "
                    . "WHERE "
                    . "email LIKE '" . $emailAVerifier . "'";
                $res = $mysqli->query($lInstructionSql);
                $user = $res->fetch_assoc();
                if (!$user or $user["password"] != $passwdAVerifier) {
                    echo "La connexion a échouée. ";
                } else {
                    echo "Votre connexion est un succès : " . $user['alias'] . ".";
                    unset($_SESSION['connected_id']);
                    $_SESSION['connected_id'] = $user['id'];
                    header('Location: wall.php?user_id=' . $user['id']);
                }
            }
            ?>
            <form action="login.php" method="post"> <label for='email'>E-Mail</label>
                <input type='email' name='email' placeholder="Entrez votre adresse e-mail">
                <label for='motpasse'>Mot de passe</label>
                <input type='password' name='motpasse'> <button class="btn-style" type='submit'>Connexion</button>
            </form>
            <p>
                Pas de compte?
                <a href='registration.php'>Inscrivez-vous.</a>
            </p>
        </article>
    </main>
    <script>
        setTimeout(function() {
            let confirmationMessage = document.getElementById('confirmationMessage');
            if (confirmationMessage) {
                confirmationMessage.style.display = 'none';
            }
        }, 3000);
    </script>
</body>

</html>