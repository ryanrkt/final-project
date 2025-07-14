<?php
require '../inc/fonction.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$id_emprunt = intval($_GET['id'] ?? 0);
if ($id_emprunt <= 0) {
    die("ID emprunt invalide.");
}

$conn = dbconnect();

// Récupérer les infos emprunt + objet pour affichage
$sql = "SELECT e.*, o.nom_objet FROM exam_emprunt e JOIN exam_objet o ON e.id_objet = o.id_objet WHERE e.id_emprunt = $id_emprunt LIMIT 1";
$res = mysqli_query($conn, $sql);
$emprunt = $res ? mysqli_fetch_assoc($res) : null;

if (!$emprunt) {
    die("Emprunt introuvable.");
}

// Traitement formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etat = $_POST['etat'] ?? '';
    if (!in_array($etat, ['OK', 'Abimée'])) {
        $error = "Veuillez sélectionner un état valide.";
    } else {
        $date_retour = date('Y-m-d');
        $etat_esc = mysqli_real_escape_string($conn, $etat);
        $sql_update = "UPDATE exam_emprunt SET etat_objet = '$etat_esc', date_retour = '$date_retour' WHERE id_emprunt = $id_emprunt";
        if (mysqli_query($conn, $sql_update)) {
            header("Location: profil_membre.php?id=" . intval($emprunt['id_membre']));
            exit;
        } else {
            $error = "Erreur lors de la mise à jour : " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Retour objet : <?= htmlspecialchars($emprunt['nom_objet']) ?></title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Retour de l'objet : <?= htmlspecialchars($emprunt['nom_objet']) ?></h2>
    <p>Date emprunt : <?= htmlspecialchars($emprunt['date_emprunt']) ?></p>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="mb-3">
            <label for="etat" class="form-label">État de l'objet au retour</label>
            <select name="etat" id="etat" class="form-select" required>
                <option value="">-- Choisir un état --</option>
                <option value="OK">OK</option>
                <option value="Abimée">Abimée</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Valider le retour</button>
        <a href="profil_membre.php?id=<?= intval($emprunt['id_membre']) ?>" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>
