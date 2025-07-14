<?php
require '../inc/fonction.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Récupération des membres
$membres = getMembres();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des membres</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2 class="mb-4">Liste des membres</h2>

    <?php if (empty($membres)): ?>
        <div class="alert alert-warning text-center">
            Aucun membre trouvé.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($membres as $membre): ?>
                        <tr>
                            <td><?= htmlspecialchars($membre['id_membre']) ?></td>
                            <td><?= htmlspecialchars($membre['nom']) ?></td>
                            <td><?= htmlspecialchars($membre['email']) ?></td>
                            <td>
                                <a href="profil_membre.php?id=<?= $membre['id_membre'] ?>" class="btn btn-sm btn-primary">Voir profil</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
