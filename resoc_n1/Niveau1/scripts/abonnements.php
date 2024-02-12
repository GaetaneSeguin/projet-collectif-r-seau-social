<?php
session_start();
$currentId = $_SESSION['connected_id'];
include 'connexion.php';
$wallUserId=intval($_GET['wall_id']);

if ($currentId && $wallUserId) {

    // Vérifiez si l'utilisateur est déjà abonné à cette personne
    $query = "SELECT * FROM followers WHERE following_user_id = ? AND followed_user_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $currentId, $wallUserId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // L'utilisateur n'est pas encore abonné à cette personne
        $insertLike = "INSERT INTO followers (following_user_id, followed_user_id) VALUES (?, ?)";
        $insertStmt = $mysqli->prepare($insertLike);
        $insertStmt->bind_param("ii", $currentId, $wallUserId);
        $insertStmt->execute();
    } else if ($result->num_rows === 1) {
        // L'utilisateur est déjà abonné à cette personne
        $deleteQuery = "DELETE FROM followers WHERE following_user_id = ? AND followed_user_id = ?";
        $deleteStmt = $mysqli->prepare($deleteQuery);
        $deleteStmt->bind_param("ii", $currentId, $wallUserId);
        $deleteStmt->execute();
    }
    // Redirection vers wall.php
    header("location:" .  $_SERVER['HTTP_REFERER']);
    exit();
} else {

    exit();
}

?>

    
