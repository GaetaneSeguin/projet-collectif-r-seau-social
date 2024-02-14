<?php
session_start();
$currentId = $_SESSION['connected_id'];
include 'connexion.php';


if (isset($_GET['post_id'])) {
    $postId = intval($_GET['post_id']);

    $deleteLikesQuery = "DELETE FROM likes WHERE post_id = ? ";
    $deleteLikesStmt = $mysqli->prepare($deleteLikesQuery);
    $deleteLikesStmt->bind_param("i", $postId);
    $deleteLikesStmt->execute();
    if ($deleteLikesStmt->affected_rows > 0) {
        echo "Les likes ont bien été supprimés";
    } else {
        echo "Erreur lors de la suppression des likes";
    }

    $deleteTagsQuery = "DELETE FROM posts_tags WHERE post_id = ? ";
    $deleteTagsStmt = $mysqli->prepare($deleteTagsQuery);
    $deleteTagsStmt->bind_param("i", $postId);
    $deleteTagsStmt->execute();
    if ($deleteTags->affected_rows > 0) {
        echo "Les tags ont bien été supprimés";
    } else {
        echo "Erreur lors de la suppression des tags";
    }

    $deletePostsQuery = "DELETE FROM posts WHERE id = ? ";
    $deletePostsStmt = $mysqli->prepare($deletePostsQuery);
    $deletePostsStmt->bind_param("i", $postId);
    $deletePostsStmt->execute();
    if ($deletePostsStmt->affected_rows > 0) {
        echo "Le post a bien été supprimé";
    } else {
        echo "Erreur lors de la suppression du post";
    }
}

header("location:" .  $_SERVER['HTTP_REFERER']);
exit();
?>
/**/