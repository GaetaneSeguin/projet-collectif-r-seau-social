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

        <aside>

            <?php
            $laQuestionEnSql = "SELECT * FROM `users` WHERE id= '$currentId' ";
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

                <p>Sur cette page vous trouverez la liste des personnes qui
                    suivent les messages de <?php echo $user['alias'] ?></p>
            </section>
        </aside>

        <main class='contacts'>
            <?php
            $laQuestionEnSql = "
                    SELECT users.*
                    FROM followers
                    LEFT JOIN users ON users.id=followers.following_user_id
                    WHERE followers.followed_user_id='$currentId'
                    GROUP BY users.id
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);

            while ($post = $lesInformations->fetch_assoc()) {
            ?>

                <article>
                    <?php
                    $postId = $post['id'];
                    $selectPhoto = "SELECT photo FROM photos WHERE user='$postId'";
                    $RecupInfos = $mysqli->query($selectPhoto);
                    $nomPhoto = $RecupInfos->fetch_assoc();
                    ?>

                    <img src="./photos/<?php echo $nomPhoto['photo'] ?>" alt="Portrait de l'utilisateurice" />

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