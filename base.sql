

-- DROP si nécessaire
DROP TABLE IF EXISTS exam_emprunt, exam_images_objet, exam_objet, exam_categorie_objet, exam_membre;

-- TABLE MEMBRE
CREATE TABLE exam_membre (
  id_membre INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100),
  date_naissance DATE,
  genre VARCHAR(10),
  email VARCHAR(100),
  ville VARCHAR(100),
  mdp VARCHAR(255),
  image_profil VARCHAR(255)
);

-- Données test - 4 membres
INSERT INTO exam_membre (nom, date_naissance, genre, email, ville, mdp, image_profil) VALUES
('Alice', '1995-04-10', 'F', 'alice@example.com', 'Tana', '123', ''),
('Bob', '1990-01-15', 'M', 'bob@example.com', 'Majunga', '123', ''),
('Claire', '2000-06-20', 'F', 'claire@example.com', 'Fianarantsoa', '123', ''),
('David', '1988-11-02', 'M', 'david@example.com', 'Tamatave', '123', '');

-- TABLE CATEGORIE
CREATE TABLE exam_categorie_objet (
  id_categorie INT AUTO_INCREMENT PRIMARY KEY,
  nom_categorie VARCHAR(100)
);

-- 4 catégories
INSERT INTO exam_categorie_objet (nom_categorie) VALUES
('Esthétique'), ('Bricolage'), ('Mécanique'), ('Cuisine');

-- TABLE OBJET
CREATE TABLE exam_objet (
  id_objet INT AUTO_INCREMENT PRIMARY KEY,
  nom_objet VARCHAR(100),
  id_categorie INT,
  id_membre INT,
  FOREIGN KEY (id_categorie) REFERENCES exam_categorie_objet(id_categorie),
  FOREIGN KEY (id_membre) REFERENCES exam_membre(id_membre)
);

-- 40 objets : 10 par membre répartis sur 4 catégories
INSERT INTO exam_objet (nom_objet, id_categorie, id_membre) VALUES
-- Alice
('Lisseur', 1, 1), ('Miroir', 1, 1), ('Tournevis', 2, 1), ('Scie', 2, 1), ('Cric', 3, 1),
('Pompe à vélo', 3, 1), ('Mixeur', 4, 1), ('Four', 4, 1), ('Pinceau', 1, 1), ('Perceuse', 2, 1),
-- Bob
('Peigne', 1, 2), ('Marteau', 2, 2), ('Jack', 3, 2), ('Plaque de cuisson', 4, 2), ('Fer à lisser', 1, 2),
('Clé à molette', 2, 2), ('Tournevis cruciforme', 2, 2), ('Rouleau peinture', 2, 2), ('Friteuse', 4, 2), ('Balance cuisine', 4, 2),
-- Claire
('Crayon sourcil', 1, 3), ('Rouge à lèvres', 1, 3), ('Perceuse sans fil', 2, 3), ('Compresseur', 3, 3), ('Micro-onde', 4, 3),
('Spatule', 4, 3), ('Casserole', 4, 3), ('Mallette outils', 2, 3), ('Lime', 1, 3), ('Chauffe-eau', 3, 3),
-- David
('Tondeuse', 1, 4), ('Clé plate', 2, 4), ('Pneu', 3, 4), ('Cafetière', 4, 4), ('Batteur', 4, 4),
('Couteau cuisine', 4, 4), ('Tournevis plat', 2, 4), ('Vernis', 1, 4), ('Cric hydraulique', 3, 4), ('Brosse', 1, 4);

-- TABLE IMAGES OBJET (optionnel)
CREATE TABLE exam_images_objet (
  id_image INT AUTO_INCREMENT PRIMARY KEY,
  id_objet INT,
  nom_image VARCHAR(255),
  FOREIGN KEY (id_objet) REFERENCES exam_objet(id_objet)
);

-- TABLE EMPRUNT
CREATE TABLE exam_emprunt (
  id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
  id_objet INT,
  id_membre INT,
  date_emprunt DATE,
  date_retour DATE,
  FOREIGN KEY (id_objet) REFERENCES exam_objet(id_objet),
  FOREIGN KEY (id_membre) REFERENCES exam_membre(id_membre)
);

-- 10 emprunts
INSERT INTO exam_emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(1, 2, '2025-07-10', '2025-07-14'),
(5, 3, '2025-07-05', '2025-07-15'),
(12, 1, '2025-07-07', '2025-07-18'),
(20, 3, '2025-07-01', '2025-07-13'),
(25, 4, '2025-07-09', '2025-07-20'),
(30, 1, '2025-07-10', '2025-07-25'),
(33, 2, '2025-07-12', '2025-07-15'),
(36, 3, '2025-07-08', '2025-07-22'),
(38, 4, '2025-07-11', '2025-07-17'),
(40, 1, '2025-07-05', '2025-07-16');
