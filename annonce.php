<?php

// CONFIGURATION 
require_once __DIR__ . '/assets/config/bootstrap.php';

// TRAITEMENT 

// RECUPERATION DE L'ANNONCE
$publication = getAnnonce($pdo, $_GET['id'] ?? null); 
if($publication === null){
    ajouterFlash('warning', 'Annonce inconnue.'); 
    session_write_close(); 
    header('Location: index.php'); 
}

// RECUPERATION PHOTOS ANNONCE 
$req_photo = $pdo->prepare(
    'SELECT img1, img2, img3
    FROM photos p
    LEFT JOIN annonces a ON a.photo_id = p.id_photo
    WHERE p.id_photo = :id
    '
); 

$req_photo->bindParam(':id', $publication['photo_id']); 
$req_photo->execute(); 

$photo = $req_photo->fetch(PDO::FETCH_ASSOC);

// RECUPERATION AUTRES ANNONCES
$req_annonce = $pdo->prepare(
    'SELECT img1, id_annonce
    FROM photos p
    LEFT JOIN annonces a ON a.photo_id = p.id_photo
    LEFT JOIN categories c ON a.categorie_id = c.id_categorie
    WHERE c.id_categorie = :id AND a.id_annonce != :id_actuel
    LIMIT 4'
    ); 

$req_annonce->bindParam(':id', $publication['categorie_id']); 
$req_annonce->bindParam(':id_actuel', $publication['id_annonce']); 
$req_annonce->execute(); 

$autre_annonce = $req_annonce->fetchAll(PDO::FETCH_ASSOC);

// TRAITEMENT DU COMMENTAIRE
if (isset($_POST['commenter']) && getMembre() !== null) {
    if (empty($_POST['commentaire']) || strlen($_POST['commentaire']) > 800) {
        ajouterFlash('danger', 'Votre commentaire doit contenir entre 1 & 800 caractères.');

    } else {
        $req_commentaire = $pdo->prepare(
            'INSERT INTO commentaires (membre_id, annonce_id, commentaire, date_enregistrement)
            VALUES (:membre_id, :annonce_id, :commentaire, :date_enregistrement)'
        );

        $req_commentaire->bindValue(':membre_id', getMembre()['id_membre']);
        $req_commentaire->bindValue(':annonce_id', $publication['id_annonce']);
        $req_commentaire->bindValue(':commentaire', $_POST['commentaire']);
        $req_commentaire->bindValue(':date_enregistrement', (new DateTime())->format('Y-m-d H:i:s'));

        $req_commentaire->execute(); 
        ajouterFlash('success', 'Votre commentaire a bien été envoyé.');
    }
}

// TRAITEMENT DE LA NOTE 
if(isset($_POST['noter']) && getMembre()!== null) {
    if(empty($_POST['note'])){
        ajouterFlash('warning', 'Veuillez cocher une coche pour noter le vendeur');
    } elseif(empty($_POST['avis'])){
        ajouterFlash('warning', 'Veuillez saisir un avis');
    } else {
        $req_note = $pdo->prepare(
            'INSERT INTO notes (membre_id1, membre_id2, note, avis, date_enregistrement)
            VALUES (:membre_id1, :membre_id2, :note, :avis, :date_enregistrement)'
        );

        $req_note->bindParam(':membre_id1', getMembre()['id_membre']);
        $req_note->bindValue(':membre_id2',getMembreBy($pdo, 'id_membre', $publication['membre_id'])['id_membre']);
        $req_note->bindParam(':note', $_POST['note']); 
        $req_note->bindParam(':avis', $_POST['avis']); 
        $req_note->bindValue(':date_enregistrement', (new DateTime())->format('Y-m-d H:i:s'));


        $req_note->execute(); 
        ajouterFlash('success', 'Votre note a bien été enregistrée');
    }
}


// CALCUL MOYENNE NOTE 
// 5 membres les mieux notés 
$req = $pdo->prepare(
    'SELECT ROUND(AVG(note),2/2) AS moyenne
    FROM notes
    WHERE membre_id2 = :id_membre'
); 

$req->bindValue(':id_membre', getMembreBy($pdo, 'id_membre', $publication['membre_id'])['id_membre']);
$req->execute();

$resultat_note = $req->fetch(PDO::FETCH_ASSOC);

// VARIABLE ETOILE 
// etoile vide 
$etoile_vide = '<i class="far fa-star"></i>'; 
$demi_etoile = '<i class="fas fa-star-half-alt"></i>';
$etoile_pleine = '<i class="fas fa-star"></i>';

$arrondi = round($resultat_note['moyenne'],0,PHP_ROUND_HALF_DOWN);
$restant = 5 - ($arrondi); 


// AFFICHAGE 
$page_title ='Accueil';
$meta_description_content = ' Voici la page produit'; 
include __DIR__ .'/assets/includes/header.php';
?>

<?php include __DIR__ . '/assets/includes/msg_flash.php'; ?> 


<div class="row mt-4">
    <h5 class=" ml-5 col-md-9"> <?= htmlspecialchars($publication['titre']) ?? ''; ?> </h5>
    <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#exampleModal" > Contacter <?= getMembreBy($pdo, 'id_membre', $publication['membre_id'])['prenom']; ?></button>
</div>
<hr>

<!-- Modal pour contact -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Infos Vendeur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p> Téléphone : <?= getMembreBy($pdo, 'id_membre', $publication['membre_id'])['telephone']?></p>
                    <p> Email : <?= getMembreBy($pdo, 'id_membre', $publication['membre_id'])['email']?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<div class="row">
    <div class="col-md-6 ml-5">
        <img src="assets/img/<?= $photo['img1']?>" class="img1_annonce">
        <div class="row ml-5 mt-3">
            <img src="assets/img/<?= $photo['img2']?>" class="img_annonce">
            <img src="assets/img/<?= $photo['img3']?>" class="img_annonce ml-3">
        </div>
    </div>

    <div class="col-md-5">
        <p>Description</p>
        <p class="description"> <?= nl2br(htmlspecialchars($publication['description_courte'])) ?? ''; ?> </p>
        <br>
        <p class="description"> <?= nl2br(htmlspecialchars($publication['description_longue'])) ?? ''; ?> </p>
    </div>
</div>

<div class="row mt-5 ml-5">

    <p class="col"><small class="text-muted"><i class="fas fa-calendar-alt"></i> Date publication : <?= (new DateTime($publication['date_enregistrement']))->format('d/m/Y')?></small></p>

    <p class="col"><small class="text-muted"><?= getMembreBy($pdo, 'id_membre', $publication['membre_id'])['pseudo']; ?></small>
        <?php 
            for ($i = 0; $i < $arrondi; $i++){
                echo($etoile_pleine);
            }
            for ($i = 0; $i < $restant; $i++){
                echo($etoile_vide);
            }
        ?>
    </p>



    <p class=" col"><small class="text-muted"><?= $publication['prix'] ?> €</small></p>

</div>

<hr>
    <div class="row ml-5">
        <a type="button" data-toggle="modal" data-target="#exampleModalCenter" class="col-md-10" ><small class="text-muted"> Déposer un commentaire ou un avis</small></a>
    </div>
<hr>

<!-- Affichage commentaire -->
<?php foreach (getCommentaireByAnnonce($pdo, $publication['id_annonce']) as $commentaire) : ?> 
    <p class="membre_commentaire ml-5"> 
        <?= getMembreBy($pdo, 'id_membre', $commentaire['membre_id'])['pseudo']; ?>
        Le : <?= (new DateTime($commentaire['date_enregistrement']))->format('d/m/Y')?>
    </p>
    <p class=" ml-5" style="font-size:0.8em;"> <?= $commentaire ['commentaire'] ?></p>
<?php endforeach; ?>

<!-- Ajout commentaires et notes -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Déposez un commentaire ou une note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form action="annonce.php?id=<?=$publication['id_annonce']?>" method="post">
                        <div class="form-group">
                            <label>Votre commentaire</label>
                            <textarea name="commentaire" class="form-control"></textarea>
                        </div>
                    <input type="submit" name="commenter" value="Commenter" class="btn btn-dark">
                    </form>
                </div>
                <div class="modal-body">
                    <form action="annonce.php?id=<?=$publication['id_annonce']?>" method="post">
                    <label>Votre note </label>
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="note" id="1" value="1">
                            <label class="form-check-label" for="1">1</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="note" id="2" value="2">
                            <label class="form-check-label" for="2">2</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="note" id="3" value="3">
                            <label class="form-check-label" for="3">3</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="note" id="4" value="4">
                            <label class="form-check-label" for="4">4</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="note" id="5" value="5">
                            <label class="form-check-label" for="5">5</label>
                        </div>
                        <div class="form-group">
                            <label>Votre avis</label>
                            <textarea name="avis" class="form-control"></textarea>
                        </div>

                    </div>
                    <input type="submit" name="noter" value="Noter" class="btn btn-dark">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<h6 class="ml-5 mt-5"> Autres annonces</h6>
<hr>
<br>

<!-- Autres annonces -->

<div class="row ml-5">
    <?php foreach($autre_annonce as $annonce) :  ?>
        <div class="col">
            <a href="annonce.php?id=<?= $annonce['id_annonce']?>">
                <img src="assets/img/<?= $annonce['img1']?>" class="img1_autre_annonce">
            </a>
        </div>
    <?php endforeach; ?>
</div>

<hr>
    <div class="row ml-5">
        <a href="index.php" class="col-md-2"><small class="text-muted"> Retour vers les annonces</small></a>
    </div>
<hr>




<?php 
include __DIR__ .'/assets/includes/footer.php';    
