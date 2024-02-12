<?php
session_start();
$currentId = $_SESSION['connected_id'];
ob_start();
include './scripts/connexion.php';
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Mur</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php include './templates/header.php' ?>

    <div id="wrapper">
        <?php


        $wallUserId = intval($_GET['user_id']);
        ?>


        <aside>
            <?php

            $laQuestionEnSql = "SELECT * FROM users WHERE id= '$wallUserId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();

            ?>
            <img src="user.jpg" alt="Portrait de l'utilisateurice" />
            <section>
                <h3>Présentation</h3>
                <?php
                if ($currentId != $wallUserId) {
                    $laQuestionEnSql = "SELECT * FROM followers WHERE following_user_id= '$currentId' AND followed_user_id = '$wallUserId' ";
                    $lesInformations = $mysqli->query($laQuestionEnSql);
                    $follow = $lesInformations->fetch_assoc();
                    if (!$follow){
                    ?>
                        <form method="post" action="./scripts/abonnements.php?wall_id=<?php echo $wallUserId ?> ">
                        <button type="submit">S'abonner</button>
                        </form>
                    <?php
                    }else {
                    ?>
                        <form method="post" action="./scripts/abonnements.php?wall_id=<?php echo $wallUserId ?> ">
                        <button type="submit">Se désabonner</button>
                        </form>
                    <?php
                }
            }
            ?>
            <p>Sur cette page vous trouverez tous les messages de l'utilisateurice : <a href="wall.php?user_id=<?php echo $user['id'] ?>"> <?php echo $user['alias'] ?> </a>
            </p>

            </section>
        </aside>
        <main>
            <?php


            $laQuestionEnSql = "
                    SELECT posts.id, posts.content, posts.created, users.alias as author_name,
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$wallUserId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC
                    ";


            $lesInformations = $mysqli->query($laQuestionEnSql);

            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }


            while ($post = $lesInformations->fetch_assoc()) {
            ?>
                <article>
                    <h3>
                        <time><?php echo $post['created'] ?></time>
                    </h3>
                    <address>par <?php echo $post['author_name'] ?></address>
                    <div>
                        <p><?php echo $post['content'] ?></p>
                    </div>
                    <footer>

                        <?php include './scripts/buttonLikes.php' ?>

                        <a href=""><?php
                                    $hastag = explode(",", $post['taglist']);
                                    foreach ($hastag as $tag)
                                        echo  '#' . $tag . " " ?></a>

                    </footer>
                </article>

            <?php
            }
            if ($wallUserId == $currentId) {
            ?>
                <article>
                    <h2>Poster un message</h2>
                    <?php





                    $enCoursDeTraitement = isset($_POST['message']);
                    if ($enCoursDeTraitement) {


                        $postContent = $_POST['message'];

                        $currentId = intval($mysqli->real_escape_string($currentId));
                        $postContent = $mysqli->real_escape_string($postContent);

                        $laQuestionEnSql = "INSERT INTO posts (user_id, content, created, parent_id) VALUES ($currentId, '$postContent', NOW(), NULL);";

                        // Etape 5 : execution
                        $ok = $mysqli->query($laQuestionEnSql);
                        if (!$ok) {
                            echo "Impossible d'ajouter le message: " . $mysqli->error;
                        } else {
                            header("Refresh:0");
                        }
                    }

                    ob_end_flush()

                    ?>
                    <form action="wall.php?user_id=<?php echo $wallUserId ?>" method="post">
                        <dl>
                            <dt><label for='message'>Message</label></dt>
                            <dd><textarea name='message'></textarea></dd>
                        </dl>
                        <input type='submit'>
                    </form>
                </article>
            <?php

            }
            ?>


        </main>
    </div>
</body>

</html>