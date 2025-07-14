<?php
require "connection.php";

function getCategories($conn) {
    return mysqli_query($conn, "SELECT * FROM exam_categorie_objet ORDER BY nom_categorie ASC");
}
function getObjets($conn, $filtre = '') {
    $filtre = intval($filtre);
    
    $sql = "
    SELECT o.*, c.nom_categorie, m.nom AS proprietaire,
      (
        SELECT date_retour FROM exam_emprunt e 
        WHERE e.id_objet = o.id_objet 
        ORDER BY e.id_emprunt DESC LIMIT 1
      ) AS date_retour
    FROM exam_objet o
    JOIN exam_categorie_objet c ON o.id_categorie = c.id_categorie
    JOIN exam_membre m ON o.id_membre = m.id_membre
    ";

    if ($filtre > 0) {
        $sql .= " WHERE o.id_categorie = $filtre ";
    }

    $sql .= " ORDER BY o.nom_objet ASC";
    return mysqli_query($conn, $sql);
}


?>