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

// RECUPERER MEMBRE PAR ID 
function getCategorieById(pdo $pdo, $id) : ?array{

    // VERIFICATION DE LA VALEUR DE $id 
    if(ctype_digit($id) == false){
        return null;
    }

    $req = $pdo->prepare(
        'SELECT * 
        FROM categories 
        WHERE id_categorie = :id'
    ); 

    $req->bindParam(':id', $id, PDO::PARAM_INT); 
    $req->execute(); 

    $categorie = $req->fetch(PDO::FETCH_ASSOC);
    return $categorie ?: null; 
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

// RECUPERER COMMENTAIRE PAR ID 
function getCommentairesById(pdo $pdo, $id) : ?array{

    // VERIFICATION DE LA VALEUR DE $id 
    if(ctype_digit($id) == false){
        return null;
    }

    $req = $pdo->prepare(
        'SELECT pseudo, annonce_id, commentaire, pseudo, titre, c.date_enregistrement, id_commentaire
        FROM commentaires c
        LEFT JOIN membres m ON m.id_membre = c.membre_id
        LEFT JOIN annonces a ON a.id_annonce = c.annonce_id
        WHERE id_commentaire = :id'
    ); 

    $req->bindParam(':id', $id, PDO::PARAM_INT); 
    $req->execute(); 

    $commentaire = $req->fetch(PDO::FETCH_ASSOC);
    return $commentaire ?: null; 
}

// RECUPERER NOTES PAR ID 
function getNotesById(pdo $pdo, $id) : ?array{

    // VERIFICATION DE LA VALEUR DE $id 
    if(ctype_digit($id) == false){
        return null;
    }

    $req = $pdo->prepare(
        'SELECT id_note, pseudo, membre_id1, membre_id2, note, avis, n.date_enregistrement
        FROM notes n
        LEFT JOIN membres m ON m.id_membre = n.membre_id1 AND n.membre_id2
        WHERE id_note = :id'
    ); 

    $req->bindParam(':id', $id, PDO::PARAM_INT); 
    $req->execute(); 

    $note = $req->fetch(PDO::FETCH_ASSOC);
    return $note ?: null; 
}

// RECUPERER MEMBRE 1 POUR LES NOTES
function getMembre1ByNote($pdo, $id){
    $req = $pdo->prepare(
        'SELECT pseudo, membre_id1
        FROM notes n
        LEFT JOIN membres m ON n.membre_id1 = m.id_membre
        WHERE membre_id1 = :membre_id'
        );

    $req->bindValue(':membre_id', $id); 
    $req->execute(); 

    $resultats_membre1 = $req->fetch(PDO::FETCH_ASSOC);
    return $resultats_membre1 ?: null; 
}

// RECUPERER MEMBRE 2 POUR LES NOTES
function getMembre2ByNote($pdo, $id){
    $req = $pdo->prepare(
        'SELECT pseudo, membre_id2
        FROM notes n
        LEFT JOIN membres m ON n.membre_id2 = m.id_membre
        WHERE membre_id2 = :membre_id'
        );

    $req->bindValue(':membre_id', $id); 
    $req->execute(); 

    $resultats_membre2 = $req->fetch(PDO::FETCH_ASSOC);
    return $resultats_membre2 ?: null; 
}