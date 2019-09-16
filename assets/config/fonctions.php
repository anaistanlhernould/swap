<?php 

// AJOUTER UN MESSAGE FLASH 
function ajouterFlash(string$type, string$message) : void {
    $_SESSION['flash'][] = [
        'type' => $type, 
        'message' => $message, 
    ]; 
}

// RECUPERER LES MESSAGES FLASH 
function recupererFlash(): array{
    $messages = $_SESSION ['flash'] ?? []; 
    unset($_SESSION['flash']); 
    return $messages; 
} 

// RECUPERER UN MEMBRE PAR CRITERE
function getMembreBy(PDO $pdo, string $colonne, $valeur) : ?array
{
    $req = $pdo->prepare(sprintf(
        'SELECT *
        FROM membres
        WHERE %s = :valeur',
        $colonne
    ));
    $req->bindParam(':valeur', $valeur);
    $req->execute();

    $membre = $req->fetch(PDO::FETCH_ASSOC);
    return $membre ?: null;
}

// CONNAITRE SI UN UTILISATEUR EST CONNECTE 

function getMembre() : ?array
{
    return $_SESSION['utilisateur'] ?? null;
}

// CONNAITRE STATUT MEMBRE 

function role(int $role) : bool
{
    if (getMembre() === null) {
    return false;
    }

    return getMembre()['statut'] == $role;
}

// RECUPERER MEMBRE PAR ID 

function getMembreById(pdo $pdo, $id) : ?array{

    // VERIFICATION DE LA VALEUR DE $id 
    if(ctype_digit($id) == false){
        return null;
    }

    $req = $pdo->prepare(
        'SELECT * 
        FROM membres 
        WHERE id_membre = :id'
    ); 

    $req->bindParam(':id', $id, PDO::PARAM_INT); 
    $req->execute(); 

    $membre = $req->fetch(PDO::FETCH_ASSOC);
    return $membre ?: null; 
}

// RECUPERER l'ID D'UNE CATEGORIE 

function getCategorieId(PDO $pdo, string $categorie)
{
    $req = $pdo->prepare(
        'SELECT id_categorie
        FROM categories
        WHERE titre = :categorie'
    );
    $req->bindParam(':categorie', $categorie);
    $req->execute();
    $categories = $req->fetch(PDO::FETCH_ASSOC);

    return $categories['id_categorie'];
}

// RECUPERER LA LISTE DES ANNONCES 

function listeAnnonces(PDO $pdo) : array
{
    $req = $pdo->query(
        'SELECT *
        FROM annonces
        ORDER BY date_enregistrement DESC'
    );

    return $req->fetchAll(PDO::FETCH_ASSOC);
}

// RECUPERER LA LISTE DES CATEGORIES 

function listeCategories(PDO $pdo) : array
{
    $req = $pdo->query(
        'SELECT *
        FROM categories
        ORDER BY id_categorie ASC'
    );

    return $req->fetchAll(PDO::FETCH_ASSOC);
}

// RECUPERER LA LISTE DES MEMBRES 

function listeMembres(PDO $pdo) : array
{
    $req = $pdo->query(
        'SELECT *
        FROM membres
        ORDER BY date_enregistrement DESC'
    );

    return $req->fetchAll(PDO::FETCH_ASSOC);
}

// RECUPERATION UNE ANNONCE 
function getAnnonce(PDO $pdo, $id) : ?array
{
    if (!ctype_digit($id)) {
        return null;
    }

    $req = $pdo->prepare(
        'SELECT *
        FROM annonces
        WHERE id_annonce = :id'
    );

    $req->bindParam(':id', $id, PDO::PARAM_INT);
    $req->execute();

    $annonce = $req->fetch(PDO::FETCH_ASSOC);
    return $annonce ?: null;
}

// RECUPERER UNE PHOTO PAR CRITERE
function getPhotoBy(PDO $pdo, string $colonne, $valeur) : ?array
{
    $req = $pdo->prepare(sprintf(
        'SELECT *
        FROM membres
        WHERE %s = :valeur',
        $colonne
    ));
    $req->bindParam(':valeur', $valeur);
    $req->execute();

    $membre = $req->fetch(PDO::FETCH_ASSOC);
    return $membre ?: null;
}

// RECUPERER COMMENTAIRE BY ANNONCE 

function getCommentaireByAnnonce(PDO $pdo, $id) : array {
    $req = $pdo->prepare(
        'SELECT *
        FROM commentaires
        WHERE annonce_id = :id
        ORDER BY date_enregistrement DESC
        '        
    ); 

    $req->bindValue(':id', $id); 
    $req->execute(); 

    return $req->fetchAll(PDO::FETCH_ASSOC); 
}

