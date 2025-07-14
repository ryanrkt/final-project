<?php
session_start();
require '../inc/fonction.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id_membre'])) {
    header("Location: ../ajouter_object.php?error=Connexion+requise");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom_objet']);
    $categorie = intval($_POST['id_categorie']);
    $membre = $_SESSION['id_membre'];
    $images = $_FILES['images'];

    if ($nom !== '' && $categorie > 0 && count($images['name']) > 0) {
        $conn = dbconnect();

        // Insertion objet
        $sql = "INSERT INTO exam_objet (nom_objet, id_categorie, id_membre) VALUES ('$nom', $categorie, $membre)";
        if (mysqli_query($conn, $sql)) {
            $id_objet = mysqli_insert_id($conn);

            // Dossier d’upload
            $uploadDir = '../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Upload d’images
            for ($i = 0; $i < count($images['name']); $i++) {
                if ($images['error'][$i] === 0) {
                    $tmpName = $images['tmp_name'][$i];
                    $extension = pathinfo($images['name'][$i], PATHINFO_EXTENSION);
                    $filename = uniqid('img_') . '.' . $extension;
                    $destination = $uploadDir . $filename;

                    if (move_uploaded_file($tmpName, $destination)) {
                        $q = "INSERT INTO exam_images_objet (id_objet, nom_image) VALUES ($id_objet, '$filename')";
                        mysqli_query($conn, $q);
                    }
                }
            }

            header("Location: ../ajouter_objet.php?success=1");
            exit;
        } else {
            $err = "Erreur base de données.";
        }
    } else {
        $err = "Champs invalides ou images manquantes.";
    }

    header("Location: ../ajouter_objet.php?error=" . urlencode($err));
    exit;
} else {
    header("Location: ../ajouter_objet.php");
    exit;
}
