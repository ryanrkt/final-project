<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require '../inc/fonction.php';

$email = $_POST['email'] ?? '';
$mdp = $_POST['mdp'] ?? '';
$conn=dbconnect();

if ($email === '' || $mdp === '') {
    $_SESSION['erreur_login'] = "Veuillez remplir tous les champs.";
    header('Location: login.php');
    exit;
}

// Recherche dans la base
$query = "SELECT * FROM exam_membre WHERE email = '$email' AND mdp = '$mdp'";
$result = mysqli_query($conn, $query);

if ($user = mysqli_fetch_assoc($result)) {
    $_SESSION['user'] = $user;
    header("Location: ../pages/accueil.php"); 
} else {
    $_SESSION['erreur_login'] = "Email ou mot de passe incorrect.";
    header("Location: ../index.php");
}
?>