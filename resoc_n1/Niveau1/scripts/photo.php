<?php
session_start();
$currentId = $_SESSION['connected_id'];
include 'connexion.php';

if (isset($_FILES['file'])) {
    $tmpName = $_FILES['file']['tmp_name'];
    $name = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $error = $_FILES['file']['error'];

    $tabExtension = explode('.', $name);
    $extension = strtolower(end($tabExtension));

    $extensions = ['jpg', 'png', 'jpeg', 'gif'];
    // $maxSize = 400000;

    if (in_array($extension, $extensions)) {

        // Vérifiez si l'utilisateur a déjà une photo
        $query = "SELECT photo FROM photos WHERE user = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $currentId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            // L'utilisateur n'a pas encore de photo
            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName . "." . $extension;
            //$file = 5f586bf96dcd38.73540086.jpg

            move_uploaded_file($tmpName, '../photos/' . $file);

            $insertPhoto = "INSERT INTO photos (user, photo) VALUES (?, ?)";
            $insertStmt = $mysqli->prepare($insertPhoto);
            $insertStmt->bind_param("is", $currentId, $file);
            $insertStmt->execute();

            header("location:" . $_SERVER['HTTP_REFERER']);
        } else if ($result->num_rows === 1) {
            // L'utilisateur a déjà une photo
            $deleteQuery = "DELETE FROM photos WHERE user = ?";
            $deleteStmt = $mysqli->prepare($deleteQuery);
            $deleteStmt->bind_param("i", $currentId);
            $deleteStmt->execute();

            $uniqueName = uniqid('', true);
            $file = $uniqueName . "." . $extension;

            move_uploaded_file($tmpName, '../photos/' . $file);

            $insertPhoto = "INSERT INTO photos (user, photo) VALUES (?, ?)";
            $insertStmt = $mysqli->prepare($insertPhoto);
            $insertStmt->bind_param("is", $currentId, $file);
            $insertStmt->execute();

            header("location:" . $_SERVER['HTTP_REFERER']);
        }
    } else {
        echo "Une erreur est survenue";
    }
}
