<?php
require '../inc/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_objet = intval($_POST['id_objet'] ?? 0);
    $date_retour = trim($_POST['date_retour'] ?? '');
    $id_membre = $_SESSION['id_membre'] ?? 0;

    // Vérification que tous les champs sont bien remplis
    if ($id_objet > 0 && !empty($date_retour) && $id_membre > 0) {
        $conn = dbconnect();

        $sql = "INSERT INTO exam_emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES (?, ?, CURDATE(), ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "iis", $id_objet, $id_membre, $date_retour);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Redirection vers la liste
            header("Location: ../pages/accueil.php?success=1");
            exit();
        } else {
            echo "Erreur de préparation SQL.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
} else {
    echo "Méthode non autorisée.";
}
