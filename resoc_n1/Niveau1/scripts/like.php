<?php
session_start();
include 'connexion.php';
$userId =  $_SESSION['connected_id'];
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// Récupération de l'ID du post
$postId = isset($_POST['post_id']) ? intval($_POST['post_id']) : null;

echo "<pre>" . print_r("hey" . $postId, 1) . "</pre>";
echo "<pre>" . print_r($userId, 1) . "</pre>";

if ($userId && $postId) {

    // Vérifiez si l'utilisateur a déjà "liké" la publication
    $query = "SELECT id FROM likes WHERE user_id = ? AND post_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $userId, $postId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // L'utilisateur n'a pas encore "liké" cette publication, on ajoute le "like"
        $insertLike = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
        $insertStmt = $mysqli->prepare($insertLike);
        $insertStmt->bind_param("ii", $userId, $postId);
        $insertStmt->execute();
    } else if ($result->num_rows === 1) {
        // L'utilisateur a déjà "liké" cette publication, on retire le "like"
        $deleteQuery = "DELETE FROM likes WHERE user_id = ? AND post_id = ?";
        $deleteStmt = $mysqli->prepare($deleteQuery);
        $deleteStmt->bind_param("ii", $userId, $postId);
        $deleteStmt->execute();
    }
    // Redirection vers wall.php
    header("location:" .  $_SERVER['HTTP_REFERER']);
    exit();
} else {

    exit();
}

?>

<!--  -->