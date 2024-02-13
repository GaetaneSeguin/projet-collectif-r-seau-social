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

            <?php
            $query = "SELECT photo FROM photos WHERE user = '$currentId'";
            $lesInfos = $mysqli->query($query);
            $nomPhoto = $lesInfos->fetch_assoc();
            ?>

            <img src="./photos/<?php echo $nomPhoto['photo'] ?>" alt="Portrait de l'utilisateurice" />

            <section>
                <h3>Présentation</h3>
                <?php
                if ($currentId != $wallUserId) {
                    $laQuestionEnSql = "SELECT * FROM followers WHERE following_user_id= '$currentId' AND followed_user_id = '$wallUserId' ";
                    $lesInformations = $mysqli->query($laQuestionEnSql);
                    $follow = $lesInformations->fetch_assoc();
                    if (!$follow) {
                ?>
                        <form method="post" action="./scripts/abonnements.php?wall_id=<?php echo $wallUserId ?> ">
                            <button type="submit">S'abonner</button>
                        </form>
                    <?php
                    } else {
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
                    GROUP_CONCAT(tags.id, ':' ,tags.label) AS taglist 
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

                        <?php
                        $hastag = explode(",", $post['taglist']);
                        if (!empty($hastag[0])) {
                            foreach ($hastag as $tag) {
                                list($tagId, $label) = explode(':', $tag)
                        ?>
                                <a href="tags.php?tag_id=<?php echo $tagId ?>"> <?php echo  '#' . $label . " "  ?></a>
                        <?php
                            }
                        }
                        ?>
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

                            $postId = $mysqli->insert_id;

                            preg_match_all('/#\w+/', $postContent, $matches);
                            $tags = array_unique($matches[0]);

                            foreach ($tags as $tag) {
                                $tagName = substr($tag, 1);
                                $tagName = strtolower($tagName);

                                $selectTag = "SELECT * from tags where label = '$tagName'";
                                $selectTagResult = $mysqli->query($selectTag);

                                if ($selectTagResult->num_rows == 0) {
                                    $insertTag = ("INSERT into tags (label) values ('$tagName')");
                                    $insertTagResult = $mysqli->query($insertTag);
                                    if (!$insertTagResult) {
                                        echo "Impossible d'ajouter le tag: " . $mysqli->error;
                                    }
                                    $tagId = $mysqli->insert_id;
                                } else {
                                    $tagId = $selectTagResult->fetch_assoc()['id'];
                                }
                                $insertPostTag = "INSERT into posts_tags (post_id, tag_id) values ($postId, $tagId)";
                                $insertPostTagResult = $mysqli->query($insertPostTag);
                            };

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