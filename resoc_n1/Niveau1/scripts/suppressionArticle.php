<?php
session_start();
$currentId = $_SESSION['connected_id'];
include 'connexion.php';


if (isset($_GET['post_id'])) {
    $postId = intval($_GET['post_id']);

    $deleteQuery = "DELETE FROM likes WHERE post_id = ? ";
    $deleteStmt = $mysqli->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $postId);
    $deleteStmt->execute();
    if ($deleteStmt->affected_rows > 0) {
        echo "Les likes ont bien été supprimés";
    } else {
        echo "Erreur lors de la suppression des likes";
    }

    $deleteQueryTag = "DELETE FROM posts_tags WHERE post_id = ? ";
    $deleteTags = $mysqli->prepare($deleteQueryTag);
    $deleteTags->bind_param("i", $postId);
    $deleteTags->execute();
    if ($deleteTags->affected_rows > 0) {
        echo "Les tags ont bien été supprimés";
    } else {
        echo "Erreur lors de la suppression des tags";
    }

    $deleteQueryPost = "DELETE FROM posts WHERE id = ? ";
    $deletePosts = $mysqli->prepare($deleteQueryPost);
    $deletePosts->bind_param("i", $postId);
    $deletePosts->execute();
    if ($deletePost->affected_rows > 0) {
        echo "Le post a bien été supprimé";
    } else {
        echo "Erreur lors de la suppression du post";
    }
}

header("location:" .  $_SERVER['HTTP_REFERER']);
exit();
?>
/**/