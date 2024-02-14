<?php
session_start();
if (!isset($_SESSION['connected_id'])) {

    header('Location: login.php');
    exit();
}
$currentId = $_SESSION['connected_id'];
include './scripts/connexion.php';
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Flux</title>
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

            <?php
            $query = "SELECT photo FROM photos WHERE user = '$currentId'";
            $lesInfos = $mysqli->query($query);
            $nomPhoto = $lesInfos->fetch_assoc();
            ?>

            <img src="./photos/<?php echo $nomPhoto['photo'] ?>" alt="Portrait de l'utilisateurice" />

            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez tous les message des utilisateurices
                    auxquel est abonnée <a href="wall.php?user_id=<?php echo $user['id'] ?>"> <?php echo $user['alias'] ?> </a>
                    <!-- (n° <?php $userId ?>) -->
                </p>

            </section>
        </aside>
        <main>
            <?php

            $laQuestionEnSql = "
                    SELECT posts.id, posts.content,
                    posts.created,
                    users.alias as author_name,  
                    users.id as user_id,
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM followers 
                    JOIN users ON users.id=followers.followed_user_id
                    JOIN posts ON posts.user_id=users.id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE followers.following_user_id='$userId' 
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
                        <time datetime='2020-02-01 11:12:13'><?php echo $post['created'] ?></time>
                    </h3>
                    <address><a href="wall.php?user_id=<?php echo $post['user_id'] ?>"> <?php echo $post['author_name'] ?> </a></address>
                    <div>
                        <p class="has-dropcap"><?php echo '<span class="has-dropcap">' . $post['content'] ?></p>

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