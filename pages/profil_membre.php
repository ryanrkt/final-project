<?php
require '../inc/fonction.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$id_membre = intval($_GET['id'] ?? 0);
if ($id_membre <= 0) {
    die("ID membre invalide.");
}

$membre = getMembreById($id_membre);
$emprunts = getEmpruntsByMembre($id_membre);
$objetsPerso = getObjetsByMembre($id_membre);

// Compter les objets retournés et leur état
$totalOK = 0;
$totalAbimee = 0;
foreach ($emprunts as $e) {
    if (isset($e['etat_objet'])) {
        if ($e['etat_objet'] === 'OK') $totalOK++;
        elseif ($e['etat_objet'] === 'Abimée') $totalAbimee++;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil de <?= htmlspecialchars($membre['nom'] ?? 'Inconnu') ?></title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <a href="liste_membres.php" class="btn btn-secondary mb-3">&larr; Retour</a>

    <h2>Profil de <?= htmlspecialchars($membre['nom'] ?? 'Inconnu') ?></h2>
    <p>Email : <?= htmlspecialchars($membre['email'] ?? '-') ?></p>

    <h4 class="mt-4">Objets appartenant à ce membre</h4>
    <?php if (empty($objetsPerso)): ?>
        <div class="alert alert-warning">Aucun objet enregistré pour ce membre.</div>
    <?php else: ?>
        <ul class="list-group mb-4">
            <?php foreach ($objetsPerso as $objet): ?>
                <li class="list-group-item"><?= htmlspecialchars($objet['nom_objet']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h4 class="mt-4">Statistiques des emprunts</h4>
    <p>Total objets rendus OK : <strong><?= $totalOK ?></strong></p>
    <p>Total objets abîmés : <strong><?= $totalAbimee ?></strong></p>

    <h4 class="mt-4">Historique des emprunts</h4>
    <?php if (empty($emprunts)): ?>
        <div class="alert alert-info">Aucun emprunt trouvé.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Objet</th>
                    <th>Propriétaire</th>
                    <th>Date emprunt</th>
                    <th>Date retour</th>
                    <th>État à la restitution</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($emprunts as $emprunt): ?>
                    <?php 
                        $objet = getObjetDetails($emprunt['id_objet']);
                        $proprietaire = getMembreById($objet['id_membre'] ?? 0);
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($objet['nom_objet'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($proprietaire['nom'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($emprunt['date_emprunt']) ?></td>
                        <td><?= htmlspecialchars($emprunt['date_retour'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($emprunt['etat_objet'] ?? '-') ?></td>
                        <td>
                            <?php if (empty($emprunt['etat_objet'])): ?>
                                <a href="etat.php?id=<?= $emprunt['id_emprunt'] ?>" 
                                   class="btn btn-sm btn-success"
                                   onclick="return confirm('Confirmez-vous le retour de cet objet ?');">
                                   Retourner
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Déjà retourné</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
