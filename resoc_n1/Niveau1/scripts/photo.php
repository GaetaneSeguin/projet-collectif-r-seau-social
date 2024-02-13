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
        var_dump($tmpName);

        $uniqueName = uniqid('', true);
        //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
        $file = $uniqueName . "." . $extension;
        //$file = 5f586bf96dcd38.73540086.jpg
        var_dump($file);

        move_uploaded_file($tmpName, '../photos/' . $file);

        $insertPhoto = "INSERT INTO photos (user, photo) VALUES (?, ?)";
        $insertStmt = $mysqli->prepare($insertPhoto);
        $insertStmt->bind_param("is", $currentId, $file);
        $insertStmt->execute();

        echo "Image enregistrée";
    } else {
        echo "Une erreur est survenue";
    }
}
