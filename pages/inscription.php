<?php
session_start();
$erreur = $_SESSION['erreur_inscription'] ?? '';
unset($_SESSION['erreur_inscription']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Inscription</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">

<div class="container">
  <div class="row justify-content-center align-items-center vh-100">
    <div class="col-md-8">
      <div class="card shadow">
        <div class="card-body">
          <h3 class="text-center mb-4">Créer un compte</h3>

          <?php if (!empty($erreur)): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($erreur) ?></div>
          <?php endif; ?>

          <form method="POST" action="traitement_inscription.php" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="nom" class="form-label">Nom complet</label>
                <input type="text" class="form-control" name="nom" id="nom" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="date_naissance" class="form-label">Date de naissance</label>
                <input type="date" class="form-control" name="date_naissance" id="date_naissance" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="genre" class="form-label">Genre</label>
                <select class="form-select" name="genre" id="genre" required>
                  <option value="">Choisir</option>
                  <option value="M">Homme</option>
                  <option value="F">Femme</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label for="ville" class="form-label">Ville</label>
                <input type="text" class="form-control" name="ville" id="ville" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" class="form-control" name="email" id="email" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="mdp" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" name="mdp" id="mdp" required>
              </div>
              
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-success">S'inscrire</button>
            </div>
          </form>

          <p class="mt-3 text-center">
            Déjà inscrit ? <a href="../index.php">Se connecter</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
