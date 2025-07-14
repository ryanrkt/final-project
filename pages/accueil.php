<?php
require '../inc/fonction.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = dbconnect();
$filtre = $_GET['cat'] ?? '';

$categories = getCategories($conn);
$objets = getObjets($conn, $filtre);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Liste des objets</title>
  <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">

<div class="container mt-4">
  <h2 class="mb-4">Liste des objets</h2>

  <!-- Filtre catégories -->
  <form method="GET" class="mb-4">
    <div class="row g-2 align-items-center">
      <div class="col-auto">
        <label for="cat" class="form-label">Filtrer par catégorie :</label>
      </div>
      <div class="col-auto">
        <select name="cat" id="cat" class="form-select" onchange="this.form.submit()">
          <option value="">-- Toutes les catégories --</option>
          <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
            <option value="<?= $cat['id_categorie'] ?>" <?= ($filtre == $cat['id_categorie']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat['nom_categorie']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
    </div>
  </form>

  <!-- Liste des objets -->
  <div class="row">
    <?php while ($objet = mysqli_fetch_assoc($objets)): ?>
      <div class="col-md-4 mb-4">
     <div class="card h-100 custom-card">

          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($objet['nom_objet']) ?></h5>
            <p class="card-text">
              <strong>Catégorie :</strong> <?= htmlspecialchars($objet['nom_categorie']) ?><br>
              <strong>Propriétaire :</strong> <?= htmlspecialchars($objet['proprietaire']) ?><br>
              <?php if (!empty($objet['date_retour']) && $objet['date_retour'] >= date('Y-m-d')): ?>
                <span class="badge bg-danger">Emprunté jusqu'au <?= $objet['date_retour'] ?></span>
              <?php else: ?>
                <span class="badge bg-success">Disponible</span>
              <?php endif; ?>
            </p>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

</body>
</html>
