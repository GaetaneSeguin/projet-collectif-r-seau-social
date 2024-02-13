<?php
session_start();
$currentId = $_SESSION['connected_id'];
include './scripts/connexion.php';
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Actualités</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php include './templates/header.php' ?>
    <div id="wrapper">
        <aside>

            <?php
            $query = "SELECT photo FROM photos WHERE user = '$currentId'";
            $lesInfos = $mysqli->query($query);
            $nomPhoto = $lesInfos->fetch_assoc();
            ?>

            <img src="./photos/<?php echo $nomPhoto['photo'] ?>" alt="Portrait de l'utilisateurice" />

            <section>
                <h3>Présentation </h3>
                <p>Sur cette page vous trouverez les derniers messages de
                    tous les utilisateurices du site.</p>
            </section>
        </aside>
        <main>


            <?php

            //verification
            if ($mysqli->connect_errno) {
                echo "<article>";
                echo ("Échec de la connexion : " . $mysqli->connect_error);
                echo ("<p>Indice: Vérifiez les parametres de <code>new mysqli(...</code></p>");
                echo "</article>";
                exit();
            }


            $laQuestionEnSql = "
                    SELECT posts.id, posts.content,
                    posts.created,
                    users.alias as author_name,  
                    users.id as user_id,
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    LIMIT 10
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            // Vérification
            if (!$lesInformations) {
                echo "<article>";
                echo ("Échec de la requete : " . $mysqli->error);
                echo ("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                exit();
            }


            while ($post = $lesInformations->fetch_assoc()) {

            ?>
                <article>
                    <h3>
                        <time><?php echo $post['created'] ?></time>
                    </h3>
                    <address><a href="wall.php?user_id=<?php echo $post['user_id'] ?>"> <?php echo $post['author_name'] ?> </a></address>
                    <div>
                        <p><?php echo $post['content'] ?></p>
                    </div>
                    <footer>
                        <?php include './scripts/buttonLikes.php' ?>
                        <a href=""><?php $hastag = explode(",", $post['taglist']);
                                    foreach ($hastag as $tag)
                                        echo  '#' . $tag . " " ?></a>
                    </footer>
                </article>
            <?php

            }
            ?>

        </main>
    </div>
</body>

</html>