<?php
session_start();
require 'inc/config.php';

$nom = $_POST['nom'] ?? '';
$date = $_POST['date_naissance'] ?? '';
$genre = $_POST['genre'] ?? '';
$ville = $_POST['ville'] ?? '';
$email = $_POST['email'] ?? '';
$mdp = $_POST['mdp'] ?? '';

if ($nom === '' || $date === '' || $genre === '' || $ville === '' || $email === '' || $mdp === '') {
    $_SESSION['erreur_inscription'] = "Veuillez remplir tous les champs.";
    header("Location: ../pages/inscription.php");
    exit;
}

$query_check = "SELECT * FROM exam_membre WHERE email = '$email'";
$result = mysqli_query($conn, $query_check);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['erreur_inscription'] = "Cette adresse email est déjà utilisée.";
    header("Location: ../pages/inscription.php");
    exit;
}

$query_insert = "INSERT INTO exam_membre (nom, date_naissance, genre, email, ville, mdp, image_profil)
                 VALUES ('$nom', '$date', '$genre', '$email', '$ville', '$mdp', NULL)";

if (mysqli_query($conn, $query_insert)) {
    header("Location: ./index.php");
    exit;
} else {
    $_SESSION['erreur_inscription'] = "Erreur lors de l'inscription.";
    header("Location: inscription.php");
    exit;
}
