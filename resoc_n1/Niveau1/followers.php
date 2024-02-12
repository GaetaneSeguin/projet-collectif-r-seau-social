<?php
session_start();
$currentId = $_SESSION['connected_id'];
include './scripts/connexion.php';
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Mes abonnés </title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php include './templates/header.php' ?>
    <div id="wrapper">
        <?php
        $userId = intval($_GET['user_id']);
        ?>

        <aside>

            <?php
            $laQuestionEnSql = "SELECT * FROM `users` WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();
            ?>
            <img src="user.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>

                <p>Sur cette page vous trouverez la liste des personnes qui
                    suivent les messages de <?php echo $user['alias'] ?></p>





            </section>
        </aside>
        <main class='contacts'>
            <?php

            $userId = intval($_GET['user_id']);

            $laQuestionEnSql = "
                    SELECT users.*
                    FROM followers
                    LEFT JOIN users ON users.id=followers.following_user_id
                    WHERE followers.followed_user_id='$userId'
                    GROUP BY users.id
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);

            while ($post = $lesInformations->fetch_assoc()) {

            ?>

                <article>
                    <img src="user.jpg" alt="blason" />
                    <h3>
                        <a href="wall.php?user_id=<?php echo $post['id'] ?>"> <?php echo $post['alias'] ?> </a></time>
                    </h3>
                    <!-- <p><?php echo $post['id'] ?></p>   -->
                </article>

            <?php

            }
            ?>
        </main>
    </div>
</body>

</html>