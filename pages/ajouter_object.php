<?php
session_start();
require '../inc/fonction.php';



$categories = getCategories();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un objet</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2 class="mb-4">Ajouter un objet</h2>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success">Objet ajouté avec succès.</div>
        <a href="liste_objets.php" class="btn btn-primary">Retour à la liste</a>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <form method="POST" action="../traitements/traitement_ajout.php" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nom_objet" class="form-label">Nom de l’objet</label>
            <input type="text" name="nom_objet" id="nom_objet" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="id_categorie" class="form-label">Catégorie</label>
            <select name="id_categorie" id="id_categorie" class="form-select" required>
                <option value="">-- Sélectionner une catégorie --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Images (vous pouvez en sélectionner plusieurs)</label>
            <input type="file" name="images[]" class="form-control" multiple required>
        </div>

        <button type="submit" class="btn btn-success">Ajouter</button>
    </form>
</div>
</body>
</html>
