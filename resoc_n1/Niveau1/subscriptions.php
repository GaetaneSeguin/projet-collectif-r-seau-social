<?php
session_start();
$currentId = $_SESSION['connected_id'];
include './scripts/connexion.php';
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Mes abonnements</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php include './templates/header.php' ?>
    <div id="wrapper">
        <aside>
            <?php
            $userId = intval($_GET['user_id']);
            ?>
            <?php
            $laQuestionEnSql = "SELECT * FROM `users` WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();
            ?>
            <img src="user.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez la liste des personnes dont
                    <a href="wall.php?user_id=<?php echo $user['id'] ?>"> <?php echo $user['alias'] ?> </a>
                    suit les messages
                </p>

            </section>
        </aside>
        <main class='contacts'>
            <?php


            $laQuestionEnSql = "
                    SELECT users.* 
                    FROM followers 
                    LEFT JOIN users ON users.id=followers.followed_user_id 
                    WHERE followers.following_user_id='$userId'
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

                </article>

            <?php

            }
            ?>
        </main>
    </div>
</body>

</html>