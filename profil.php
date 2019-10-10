<?php

// CONFIGURATION 
require_once __DIR__ . '/assets/config/bootstrap.php';

// TRAITEMENT 
// REDIRECTION SI NON CONNECTE 

if (getMembre() === null)
    header('Location: index.php'); 


// Enregistrement des modifications 
if(isset($_POST['modifier_profil'])){
    if (!preg_match('#^[a-zA-Z.-]+$#', $_POST['nom'])){
        ajouterFlash('warning', 'Votre nom ne doit contenir que des lettres ainsi que les caractères . -');
    } elseif (!preg_match('#^[a-zA-Z.-]+$#', $_POST['prenom'])){
        ajouterFlash('warning', 'Votre prénom ne doit contenir que des lettres ainsi que les caractères . -');
    } elseif (!preg_match('#^[0-9+]+$#', $_POST['telephone'])){
        ajouterFlash('warning', 'Veuillez saisir un numéro de téléphone correcte');
    } else {
        $req = $pdo->prepare(
            'UPDATE membres SET             
            nom = :nom,
            prenom =:prenom,
            telephone =:telephone 
            WHERE id_membre = :id'
        ); 

        $req->bindParam(':nom', $_POST['nom']);
        $req->bindParam(':prenom', $_POST['prenom']);
        $req->bindParam(':telephone', $_POST['telephone']);
        $req->bindParam(':id', getMembre()['id_membre']);
        $req->execute(); 

        ajouterFlash('success', 'Vos modifications ont bien été prise en compte'); 
    }
}

// ACTIVITE MEMBRE 
// Nb d'annonce posté 
$req = $pdo->prepare(
    'SELECT COUNT(*) AS nb_annonce  
    FROM annonces 
    WHERE membre_id = :id'
); 
$req->bindParam(':id', getMembre()['id_membre']);
$req->execute(); 
$resultat_annonces = $req->fetch(PDO::FETCH_ASSOC);

// Nb d'avis recu 
$req = $pdo->prepare(
    'SELECT COUNT(*) AS nb_avis  
    FROM notes
    WHERE membre_id2 = :id'
); 
$req->bindParam(':id', getMembre()['id_membre']);
$req->execute(); 
$resultat_avis = $req->fetch(PDO::FETCH_ASSOC);

// Note
$req = $pdo->prepare(
    'SELECT ROUND(AVG(note),2/2) AS moyenne
    FROM notes
    WHERE membre_id2 = :id'
); 
$req->bindParam(':id', getMembre()['id_membre']);
$req->execute(); 
$resultat_notes = $req->fetch(PDO::FETCH_ASSOC);

// RECUPERATION ANNONCE + PAGINATION

$annoncesParPage = 3; 
$annoncesTotalesReq = $pdo->prepare('SELECT id_annonce FROM annonces WHERE membre_id= :id');
$annoncesTotalesReq->bindParam(':id', getMembre()['id_membre']);
$annoncesTotalesReq->execute(); 
$resultats_annonce = $annoncesTotalesReq->fetchAll(PDO::FETCH_ASSOC);

$annoncesTotales = $annoncesTotalesReq->rowCount(); 
$pagesTotales = ceil($annoncesTotales/$annoncesParPage); 

if(isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 ){
    $_GET['page'] = intval($_GET['page']); 
    $pageCourante = $_GET['page']; 
} else {
    $pageCourante = 1; 
}

$depart = ($pageCourante-1)*$annoncesParPage; 

$req = $pdo->prepare(

    'SELECT id_annonce, titre, description_courte, prix, img1
    FROM annonces a
    LEFT JOIN photos p ON a.photo_id = p.id_photo 
    WHERE membre_id = :id
    ORDER BY a.date_enregistrement DESC LIMIT '.$depart.','.$annoncesParPage);

$req->bindParam(':id', getMembre()['id_membre']);
$req->execute(); 
$resultats_annonce = $req->fetchAll(PDO::FETCH_ASSOC);


// AFFICHAGE 
$page_title ='Mon profil';
$meta_description_content = ' La page profil du site SWAP'; 
include __DIR__ .'/assets/includes/header.php';
?>

<?php include __DIR__ . '/assets/includes/msg_flash.php'; ?> 

<h1 class="h1_page"> Mon Profil </h1>
<hr>

<div class="row ml-5 mt-5 mb-5">
    <div class="col-4 colonne_profil text-light ">
    <h4 class="text-dark ml-3 mb-3"> Mes informations </h4>

        <div class="bg-info p-4">
            <p> Pseudo : <?= getMembre()['pseudo'] ?></p>
            <?php if (getMembre()['civilite'] == 0) :?>
                <p> Civilité : Madame/Mademoiselle</p>
            <?php else : ?> 
                <p> Civilité : Monsieur</p>
            <?php endif ; ?> 
            <p> Nom : <?= getMembre()['nom'] ?></p>
            <p> Prénom : <?= getMembre()['prenom'] ?></p>
            <p> Email : <?= getMembre()['email'] ?></p>
            <p> Téléphone : <?= getMembre()['telephone'] ?></p>
            <p> Date d'inscription : <?= getMembre()['date_enregistrement'] ?></p>

            <?php if (role(ROLE_ADMIN)) : ?>
                Statut : ADMIN
            <br><br>
            <?php endif;?>

            <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#exampleModal">
                Modifier ses informations
            </button>

            <h5 class="mt-5"> Mon activité : </h5>
            
            <span> Nombre d'annonces postées : </span>
            <p class="w-50"><?php echo $resultat_annonces['nb_annonce']?> annonces </p>
            
            <span> Nombre d'avis reçus : </span>
            <p class="w-50"><?php echo $resultat_avis['nb_avis']?> avis </p>
            
            <span> Ma note : </span>
            <p class="w-50"><?php echo $resultat_notes['moyenne']?> / 5 </p> 


            <!-- Modal modifications d'infos -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-dark" id="exampleModalLabel">Modifier mes informations</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <span class="dark text-muted"> Pseudo : <?= getMembre()['pseudo'] ?></span><br>
                            <span class="dark text-muted"> Email : <?= getMembre()['email'] ?></span><br>
                            <small class="text-muted"> * Vous n'avez pas la possibilité de modifier votre pseudo et votre email</small>
                            <br><br>
                            <form action="profil.php" method="post">
                                <label for="nom" class="dark"> Nom : </label>
                                <input type="text" value="<?= getMembre()['nom'] ?>" name="nom" id="nom" class="form-control">
                                <br>
                                <label for="prenom" class="dark"> Prénom : </label>
                                <input type="text" value="<?= getMembre()['prenom'] ?>" name="prenom" id="prenom" class="form-control">
                                <br>
                                <label for="telephone" class="dark"> Téléphone : </label>
                                <input type="text" value="<?= getMembre()['telephone'] ?>" name="telephone" id="telephone" class="form-control">
                                <br>
                                <input type="submit" name="modifier_profil" class="btn btn-dark mt-4" value="Enregistrer mes modifications">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6">
        <h4 class="ml-3 mb-3"> Mes annonces </h4>
        <?php foreach($resultats_annonce as $annonce) :  ?>
            <div>
                <a href="annonce.php?id=<?=$annonce['id_annonce']?>">
                    <div class="card mb-3 annonce_body">
                        <div class="row no-gutters">
                            <div class="col-md-4">
                                <img src="assets/img/<?= $annonce['img1']?>" class="img_gestion_accueil">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"> <?= $annonce['titre'] ?> </h5>
                                    <p class="card-text"><?= $annonce['description_courte'] ?></p>
                                    <div class="row">
                                        <p class="card-text col-4"><?= $annonce['prix']?> €</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                </a>
                <button type="button" class="btn btn-dark mt-4" data-toggle="modal" data-target="#exampleModalScrollable">
                Voir les commentaires
                </button>
                <!-- Voir les commentaires -->
                    <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalScrollableTitle">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <?php foreach (getCommentaireByAnnonce($pdo, $annonce['id_annonce']) as $commentaire) : ?> 
                                        <p class="membre_commentaire ml-5"> 
                                        <?= getMembreBy($pdo, 'id_membre', $commentaire['membre_id'])['pseudo']; ?>
                                        Le : <?= (new DateTime($commentaire['date_enregistrement']))->format('d/m/Y')?>
                                        </p>
                                        <p class=" ml-5" style="font-size:0.8em;"> <?= $commentaire ['commentaire'] ?></p>
                                    <?php endforeach; ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
    <div class="div_lien_pagination">
        <?php 
            for($i=1; $i<=$pagesTotales; $i++) {
                    echo '<a class="lien_pagination" href="profil.php?page='.$i.'">'.$i.'</a>'; 
            }
        ?> 
    </div>
</div>


<?php 
//inclusion du footer
include __DIR__ .'/assets/includes/footer.php';    