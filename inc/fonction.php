<?php
require 'connection.php';

// Récupérer toutes les catégories
function getCategories() {
    $conn = dbconnect();
    $sql = "SELECT id_categorie, nom_categorie FROM exam_categorie_objet ORDER BY nom_categorie";
    $res = mysqli_query($conn, $sql);
    $categories = [];
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $categories[] = $row;
        }
    }
    return $categories;
}

// Récupérer les objets, optionnellement filtrés par catégorie
function getObjets($filtre = '') {
    $conn = dbconnect();
    $filtre = intval($filtre);

    $sql = "
        SELECT o.id_objet, o.nom_objet, c.nom_categorie, m.nom AS proprietaire,
          (SELECT MAX(date_retour) FROM exam_emprunt e WHERE e.id_objet = o.id_objet AND e.date_retour >= CURDATE()) AS date_retour
        FROM exam_objet o
        JOIN exam_categorie_objet c ON o.id_categorie = c.id_categorie
        JOIN exam_membre m ON o.id_membre = m.id_membre
    ";

    if ($filtre > 0) {
        $sql .= " WHERE o.id_categorie = $filtre ";
    }

    $sql .= " ORDER BY o.nom_objet";

    $res = mysqli_query($conn, $sql);
    $objets = [];
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $objets[] = $row;
        }
    }
    return $objets;
}

// Récupérer les détails d'un objet
function getObjetDetails($id_objet) {
    $conn = dbconnect();
    $id_objet = intval($id_objet);

    $sql = "
        SELECT o.*, c.nom_categorie, m.nom AS proprietaire
        FROM exam_objet o
        JOIN exam_categorie_objet c ON o.id_categorie = c.id_categorie
        JOIN exam_membre m ON o.id_membre = m.id_membre
        WHERE o.id_objet = $id_objet
        LIMIT 1
    ";

    $res = mysqli_query($conn, $sql);
    return $res ? mysqli_fetch_assoc($res) : null;
}

// Récupérer les images d'un objet
function getImagesObjet($id_objet) {
    $conn = dbconnect();
    $id_objet = intval($id_objet);

    $sql = "SELECT * FROM exam_images_objet WHERE id_objet = $id_objet";
    $res = mysqli_query($conn, $sql);
    $images = [];
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $images[] = $row;
        }
    }
    return $images;
}

// Récupérer l'historique des emprunts d'un objet
function getHistoriqueEmprunts($id_objet) {
    $conn = dbconnect();
    $id_objet = intval($id_objet);

    $sql = "
        SELECT e.*, m.nom AS emprunteur
        FROM exam_emprunt e
        JOIN exam_membre m ON e.id_membre = m.id_membre
        WHERE e.id_objet = $id_objet
        ORDER BY e.date_emprunt DESC
    ";

    $res = mysqli_query($conn, $sql);
    $historique = [];
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $historique[] = $row;
        }
    }
    return $historique;
}
?>
