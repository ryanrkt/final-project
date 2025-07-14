<?php
require './inc/fonction.php';
session_start();
$erreur = $_SESSION['erreur_login'] ?? '';
unset($_SESSION['erreur_login']); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion</title>
  <link href="./assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body class="bg-light">

<div class="container">
  <div class="row justify-content-center align-items-center vh-100">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-body">
          <h3 class="text-center mb-4">Se connecter</h3>

          <?php if (!empty($erreur)): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($erreur) ?></div>
          <?php endif; ?>

          <form method="POST" action="./traitements/traitement_login.php">
            <div class="mb-3">
              <label for="email" class="form-label">Adresse email</label>
              <input type="email" class="form-control" id="email" name="email" required placeholder="ex: nom@email.com">
            </div>
            <div class="mb-3">
              <label for="mdp" class="form-label">Mot de passe</label>
              <input type="password" class="form-control" id="mdp" name="mdp" required>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Connexion</button>
            </div>
          </form>

          <p class="mt-3 text-center">
            Pas encore de compte ?
            <a href="./pages/inscription.php">S'inscrire</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
