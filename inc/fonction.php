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

// Fonction avancée pour récupérer des objets selon plusieurs filtres
function getObjetsAvancee($filtreCategorie = '', $nom = '', $disponible = false) {
    $conn = dbconnect();

    $conditions = [];

    if (!empty($filtreCategorie)) {
        $filtreCategorie = intval($filtreCategorie);
        $conditions[] = "o.id_categorie = $filtreCategorie";
    }

    if (!empty($nom)) {
        $nom = mysqli_real_escape_string($conn, $nom);
        $conditions[] = "o.nom_objet LIKE '%$nom%'";
    }

    if ($disponible) {
        $conditions[] = "NOT EXISTS (
            SELECT 1 FROM exam_emprunt e 
            WHERE e.id_objet = o.id_objet 
              AND e.date_retour >= CURDATE()
        )";
    }

    $sql = "
        SELECT o.id_objet, o.nom_objet, c.nom_categorie, m.nom AS proprietaire,
          (SELECT MAX(date_retour) FROM exam_emprunt e WHERE e.id_objet = o.id_objet AND e.date_retour >= CURDATE()) AS date_retour
        FROM exam_objet o
        JOIN exam_categorie_objet c ON o.id_categorie = c.id_categorie
        JOIN exam_membre m ON o.id_membre = m.id_membre
    ";

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
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

// Récupérer tous les membres
function getMembres() {
    $conn = dbconnect();

    $sql = "SELECT id_membre, nom, email FROM exam_membre ORDER BY nom";
    $result = mysqli_query($conn, $sql);

    $membres = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $membres[] = $row;
        }
    }

    return $membres;
}

// Récupérer un membre par son ID
function getMembreById($id_membre) {
    $conn = dbconnect();
    $id_membre = intval($id_membre);

    $sql = "SELECT id_membre, nom, email FROM exam_membre WHERE id_membre = $id_membre LIMIT 1";
    $res = mysqli_query($conn, $sql);
    return $res ? mysqli_fetch_assoc($res) : null;
}

// Récupérer les emprunts d'un membre
function getEmpruntsByMembre($id_membre) {
    $conn = dbconnect();
    $id_membre = intval($id_membre);

    $sql = "
        SELECT e.*
        FROM exam_emprunt e
        WHERE e.id_membre = $id_membre
        ORDER BY e.date_emprunt DESC
    ";

    $res = mysqli_query($conn, $sql);
    $emprunts = [];
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $emprunts[] = $row;
        }
    }
    return $emprunts;
}

// Récupérer les objets appartenant à un membre
function getObjetsByMembre($id_membre) {
    $conn = dbconnect();
    $id_membre = intval($id_membre);

    $sql = "SELECT id_objet, nom_objet FROM exam_objet WHERE id_membre = $id_membre ORDER BY nom_objet";
    $res = mysqli_query($conn, $sql);

    $objets = [];
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $objets[] = $row;
        }
    }
    return $objets;
}

// Récupérer un objet par son ID (utile pour emprunts)
function getObjetEmprunt($id_objet) {
    $conn = dbconnect();
    $id_objet = intval($id_objet);

    $sql = "SELECT id_objet, nom_objet, id_membre FROM exam_objet WHERE id_objet = $id_objet LIMIT 1";
    $res = mysqli_query($conn, $sql);
    
    return $res ? mysqli_fetch_assoc($res) : null;
}

// Récupérer l'id du propriétaire d'un objet
function getIdMembreByObjet($id_objet) {
    $conn = dbconnect();
    $id_objet = intval($id_objet);

    $sql = "SELECT id_membre FROM exam_objet WHERE id_objet = $id_objet LIMIT 1";
    $res = mysqli_query($conn, $sql);

    if ($res && $row = mysqli_fetch_assoc($res)) {
        return $row['id_membre'];
    }

    return null;
}
?>
