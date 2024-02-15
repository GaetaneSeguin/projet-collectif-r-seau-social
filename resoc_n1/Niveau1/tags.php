<?php
session_start();
$currentId = $_SESSION['connected_id'];
include './scripts/connexion.php';
include './templates/header.php'
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Les message par mot-clé</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <div id="wrapper">
        <?php

        $tagId = intval($_GET['tag_id']);
        ?>

        <aside>
            <?php

            $laQuestionEnSql = "SELECT * FROM tags WHERE id= '$tagId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $tag = $lesInformations->fetch_assoc();

            ?>

            <?php
            $query = "SELECT photo FROM photos WHERE user = '$currentId'";
            $lesInfos = $mysqli->query($query);
            $nomPhoto = $lesInfos->fetch_assoc();
            if (!isset($nomPhoto)) {
            ?>
                <img src="./photos/user.jpg" alt="" />

            <?php
            } else {
            ?>
                <img src="./photos/<?php echo $nomPhoto['photo'] ?>" alt="Portrait de l'utilisateurice" />
            <?php
            }
            ?>

            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez les derniers messages comportant
                    le mot-clé <?php echo $tag['label'] ?>
                    <!-- Rajouter un if si aucun mot clé n'est sélectionné : ne pas afficher -->
                </p>

            </section>
        </aside>

        <aside>
            <h2>Mots-clés</h2>
            <?php

            $laQuestionEnSql = "SELECT * FROM `tags` LIMIT 50";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            // Vérification
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
                exit();
            }


            while ($tag = $lesInformations->fetch_assoc()) {
            ?>

                <article>
                    <h3> <?php echo $tag['label'] ?></h3>
                    <!-- <p> <?php $tag['id'] ?></p> -->
                    <nav>
                        <a href="tags.php?tag_id=<?php echo $tag['id'] ?>">Messages</a>
                    </nav>
                </article>
            <?php } ?>
        </aside>

        <main>
            <?php

            $laQuestionEnSql = "
                    SELECT posts.content, users.id as user_id,
                    posts.created,posts.id,
                    users.alias as author_name,  
                    GROUP_CONCAT(tags.id, ':', tags.label) AS taglist 
                    FROM posts_tags as filter 
                    JOIN posts ON posts.id=filter.post_id
                    JOIN users ON users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    WHERE filter.tag_id ='$tagId' 
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
                        <p class="has-dropcap"><?php echo $post['content'] ?></p>
                    </div>
                    <footer>

                        <?php include './scripts/buttonLikes.php'; ?>
                        <?php
                        $hastag = explode(",", $post['taglist']);
                        foreach ($hastag as $tag) {
                            list($tagId, $label) = explode(':', $tag)
                        ?>
                            <a href="tags.php?tag_id=<?php echo $tagId ?>"> <?php echo  '#' . $label . " "  ?></a>
                        <?php
                        } ?>
                    </footer>
                </article>
            <?php
            } ?>


        </main>
    </div>
</body>

</html>