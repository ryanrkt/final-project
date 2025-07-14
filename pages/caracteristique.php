<?php
require '../inc/fonction.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$id_objet = $_GET['id'] ?? null;
if (!$id_objet) {
    die("Objet non spécifié.");
}

$objet = getObjetDetails($id_objet);
if (!$objet) {
    die("Objet introuvable.");
}

$images = getImagesObjet($id_objet);
$historique = getHistoriqueEmprunts($id_objet);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche de l’objet <?= htmlspecialchars($objet['nom_objet']) ?></title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <a href="accueil.php" class="btn btn-secondary mb-3">← Retour à la liste</a>

    <h2><?= htmlspecialchars($objet['nom_objet']) ?></h2>
    <p>
        <strong>Catégorie :</strong> <?= htmlspecialchars($objet['nom_categorie']) ?><br>
        <strong>Propriétaire :</strong> <?= htmlspecialchars($objet['proprietaire']) ?><br>
    </p>

    <h3>Images</h3>
    <?php if (count($images) > 0): ?>
        <img src="../uploads/<?= htmlspecialchars($images[0]['nom_image']) ?>" alt="Image principale" class="img-fluid mb-3" style="max-width:300px;">
        <div>
            <?php foreach ($images as $img): ?>
                <img src="../uploads/<?= htmlspecialchars($img['nom_image']) ?>" alt="Image secondaire" class="img-thumbnail me-2" style="width:100px;">
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucune image disponible.</p>
    <?php endif; ?>

    <h3>Historique des emprunts</h3>
    <?php if (count($historique) > 0): ?>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>Emprunteur</th>
                    <th>Date d’emprunt</th>
                    <th>Date de retour</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historique as $emprunt): ?>
                    <tr>
                        <td><?= htmlspecialchars($emprunt['emprunteur']) ?></td>
                        <td><?= htmlspecialchars($emprunt['date_emprunt']) ?></td>
                        <td><?= htmlspecialchars($emprunt['date_retour']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun emprunt enregistré pour cet objet.</p>
    <?php endif; ?>
</div>
</body>
</html>
