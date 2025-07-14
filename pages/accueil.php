<?php
require '../inc/fonction.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$filtre = $_GET['cat'] ?? '';
$nom = trim($_GET['nom'] ?? '');
$disponible = isset($_GET['disponible']) && $_GET['disponible'] == '1';

$categories = getCategories();

if ($nom !== '' || $disponible) {
    $objets = getObjetsAvancee($filtre, $nom, $disponible);
} else {
    $objets = getObjets($filtre);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des objets</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2 class="mb-3">Liste des objets</h2>
    
    <!-- Bouton ajouter un objet -->
    <div class="mb-4 text-end">
        <a href="ajouter_object.php" class="btn btn-success">Ajouter un objet</a>
    </div>

    <!-- Formulaire de recherche -->
    <form method="GET" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="cat" class="form-label">Catégorie</label>
                <select name="cat" id="cat" class="form-select">
                    <option value="">-- Toutes les catégories --</option>
                    <?php foreach ($categories as $catOption): ?>
                        <option value="<?= $catOption['id_categorie'] ?>" <?= ($filtre == $catOption['id_categorie']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($catOption['nom_categorie']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label for="nom" class="form-label">Nom de l’objet</label>
                <input type="text" name="nom" id="nom" class="form-control" value="<?= htmlspecialchars($nom) ?>" placeholder="Rechercher par nom">
            </div>

            <div class="col-md-2 form-check mt-4">
                <input type="checkbox" name="disponible" id="disponible" class="form-check-input" value="1" <?= $disponible ? 'checked' : '' ?>>
                <label for="disponible" class="form-check-label">Disponible uniquement</label>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Rechercher</button>
            </div>
        </div>
    </form>

    <!-- Liste des objets -->
    <div class="row">
        <?php if (empty($objets)): ?>
            <p class="text-center">Aucun objet trouvé.</p>
        <?php else: ?>
            <?php foreach ($objets as $objet): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 custom-card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($objet['nom_objet']) ?></h5>
                            <p class="card-text">
                                <strong>Catégorie :</strong> <?= htmlspecialchars($objet['nom_categorie']) ?><br>
                                <strong>Propriétaire :</strong> <?= htmlspecialchars($objet['proprietaire']) ?><br>

                                <?php if (!empty($objet['date_retour']) && $objet['date_retour'] >= date('Y-m-d')): ?>
                                    <span class="badge bg-danger">Emprunté </span>
                                <?php else: ?>
                                    <span class="badge bg-success">Disponible</span>
                                <?php endif; ?>
                            </p>

                            <!-- Bouton emprunter seulement si dispo -->
                            <?php if (empty($objet['date_retour']) || $objet['date_retour'] < date('Y-m-d')): ?>
                                <form action="../traitements/traitement_emprunt.php" method="POST" class="mt-3">
                                    <input type="hidden" name="id_objet" value="<?= $objet['id_objet'] ?>">
                                    <div class="mb-2">
                                        <label for="date_retour_<?= $objet['id_objet'] ?>" class="form-label">Date de retour</label>
                                        <input type="date" name="date_retour" id="date_retour_<?= $objet['id_objet'] ?>" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm w-100">Emprunter</button>
                                </form>
                            <?php else: ?>
                                <div class="text-muted mt-2">Disponible le : <?= htmlspecialchars($objet['date_retour']) ?></div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
