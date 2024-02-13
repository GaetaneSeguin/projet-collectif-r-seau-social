<?php
session_start();
$currentId = $_SESSION['connected_id'];
include 'connexion.php';
$postId = intval($_GET['post_id']);
var_dump($currentId);
var_dump($postId);
$deleteQuery = "DELETE FROM posts WHERE user_id = ? AND id = ?";
$deleteStmt = $mysqli->prepare($deleteQuery);
$deleteStmt->bind_param("ii", $currentId, $postId);
$deleteStmt->execute();

header("location:" .  $_SERVER['HTTP_REFERER']);
exit();
?>