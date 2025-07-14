<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require '../inc/fonction.php';

$conn = dbconnect();

$email = trim($_POST['email'] ?? '');
$mdp = trim($_POST['mdp'] ?? '');

if ($email === '' || $mdp === '') {
    $_SESSION['erreur_login'] = "Veuillez remplir tous les champs.";
    header('Location: ../index.php');
    exit;
}

$email_safe = mysqli_real_escape_string($conn, $email);
$mdp_safe = mysqli_real_escape_string($conn, $mdp);

$query = "SELECT * FROM exam_membre WHERE email = '$email_safe' AND mdp = '$mdp_safe'";
$result = mysqli_query($conn, $query);

if ($user = mysqli_fetch_assoc($result)) {
    $_SESSION['user'] = $user;
    $_SESSION['id_membre'] = $user['id_membre']; // pour emprunt par exemple

    header("Location: ../pages/accueil.php");
    exit;
} else {
    $_SESSION['erreur_login'] = "Email ou mot de passe incorrect.";
    header("Location: ../index.php");
    exit;
}
