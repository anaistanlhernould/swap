DROP DATABASE IF EXISTS swap;
CREATE DATABASE swap CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE swap;

CREATE TABLE membres (
    id_membre INT(3) NOT NULL AUTO_INCREMENT,
    pseudo VARCHAR(20) NOT NULL,
    email VARCHAR (20) NOT NULL, 
    mdp VARCHAR (60) NOT NULL, 
    nom VARCHAR(20) NOT NULL, 
    prenom VARCHAR (20) NOT NULL, 
    telephone VARCHAR (20) NOT NULL, 
    civilite BOOLEAN NOT NULL,  
    statut INT (1) NOT NULL, 
    date_enregistrement DATETIME NOT NULL, 
        PRIMARY KEY (id_membre) 
) ENGINE=INNODB;

CREATE TABLE categories (
    id_categorie INT (3) NOT NULL AUTO_INCREMENT, 
    titre VARCHAR (255) NOT NULL, 
    motscles TEXT NOT NULL, 
        PRIMARY KEY (id_categorie)
) ENGINE=INNODB;

CREATE TABLE photos (
    id_photo INT (3) NOT NULL AUTO_INCREMENT, 
    img1 VARCHAR (255) NOT NULL, 
    img2 VARCHAR (255) NOT NULL, 
    img3 VARCHAR (255) NOT NULL, 
    img4 VARCHAR (255), 
    img5 VARCHAR (255), 
        PRIMARY KEY (id_photo)
) ENGINE=INNODB;

CREATE TABLE notes (
    id_note INT(3) NOT NULL AUTO_INCREMENT, 
    membre_id1 INT (3) DEFAULT NULL, 
    membre_id2 INT (3) DEFAULT NULL, 
    note INT (3) NOT NULL, 
    avis TEXT NOT NULL, 
    date_enregistrement DATETIME NOT NULL, 
        PRIMARY KEY (id_note), 
    CONSTRAINT fk_notes_membres_id1 
        FOREIGN KEY (membre_id1)
        REFERENCES membres(id_membre)
        ON DELETE CASCADE,
    CONSTRAINT fk_notes_membres_id2 
        FOREIGN KEY (membre_id2)
        REFERENCES membres(id_membre)
        ON DELETE CASCADE 
) ENGINE=INNODB;

CREATE TABLE commentaires (
    id_commentaire INT (3) NOT NULL AUTO_INCREMENT, 
    membre_id INT (3) DEFAULT NULL, 
    annonce_id INT (3) DEFAULT NULL, 
    commentaire TEXT NOT NULL, 
    date_enregistrement DATETIME, 
        PRIMARY KEY (id_commentaire), 
    CONSTRAINT fk_commentaires_membres
        FOREIGN KEY (membre_id)
        REFERENCES membres(id_membre)
        ON DELETE CASCADE,
    CONSTRAINT fk_commentaires_annonces
        FOREIGN KEY (annonce_id)
        REFERENCES annonces(id_annonce)
        ON DELETE CASCADE 
) ENGINE=INNODB;

CREATE TABLE annonces (
    id_annonce INT (3) NOT NULL AUTO_INCREMENT, 
    titre VARCHAR (255) NOT NULL, 
    description_courte VARCHAR (255) NOT NULL, 
    description_longue TEXT NOT NULL, 
    prix DECIMAL (10, 2) NOT NULL, 
    pays VARCHAR (255) NOT NULL, 
    ville VARCHAR (255) NOT NULL, 
    adresse VARCHAR (255) NOT NULL, 
    cp INT (5) NOT NULL, 
    membre_id INT (3) DEFAULT  NULL,
    photo_id INT (3) DEFAULT  NULL, 
    categorie_id INT (3) DEFAULT  NULL, 
    date_enregistrement DATETIME NOT NULL, 
        PRIMARY KEY (id_annonce), 
    CONSTRAINT fk_annonces_membres 
        FOREIGN KEY (membre_id)
        REFERENCES membres (id_membre)
        ON DELETE CASCADE, 
    CONSTRAINT fk_annonces_photos
        FOREIGN KEY (photo_id)
        REFERENCES photos (id_photo)
        ON DELETE SET NULL, 
    CONSTRAINT fk_annonces_categories 
        FOREIGN KEY (categorie_id)
        REFERENCES categories (id_categorie)
        ON DELETE CASCADE
) ENGINE=INNODB; 


INSERT INTO categories VALUES (NULL, 'Emploi', "Offres d'emploi");
