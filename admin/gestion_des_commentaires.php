<?php

// CONFIGURATION 
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';

// TRAITEMENT

// PAGINATION 
$commentairesParPage = 8; 
$commentairesTotalesReq = $pdo->query('SELECT id_commentaire FROM commentaires'); 
$commentairesTotales = $commentairesTotalesReq->rowCount(); 
$pagesTotales = ceil($commentairesTotales/$commentairesParPage); 

if(isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 ){
    $_GET['page'] = intval($_GET['page']); 
    $pageCourante = $_GET['page']; 
} else {
    $pageCourante = 1; 
}

$depart = ($pageCourante-1)*$commentairesParPage; 

$req = $pdo->query(
    'SELECT id_commentaire, pseudo, titre, commentaire, c.date_enregistrement, c.membre_id, c.annonce_id
    FROM commentaires c
    LEFT JOIN membres m ON c.membre_id = m.id_membre 
    LEFT JOIN annonces a ON c.annonce_id = a.id_annonce
    ORDER BY c.date_enregistrement DESC LIMIT '.$depart.','.$commentairesParPage);

$resultats = $req->fetchAll(PDO::FETCH_ASSOC);


// AFFICHAGE COMMENTAIRE 
$afficher_commentaire = getCommentairesById($pdo, $_GET['id'] ?? null);

// SUPPRESSION COMMENTAIRE 
if (isset($_POST['supprimer_commentaire'])){

        $req = $pdo->prepare(
            'DELETE
            FROM commentaires 
            WHERE id_commentaire = :id'
        ); 
        $req->bindParam(':id', $afficher_commentaire['id_commentaire'], PDO::PARAM_INT);
        $req->execute(); 

        unset($_POST); 
        ajouterFlash ('success', ' Le commentaire a été supprimé'); 
    }


// AFFICHAGE 
$page_title ='Gestion des commentaires';
include __DIR__ .'/../assets/includes/header_admin.php';
?>
<?php include __DIR__ . '/../assets/includes/msg_flash.php'; ?> 


    <h1 class="h1_page"> Gestion des commentaires </h1>
    <hr>

    <table class="table table-bordered text-center mt-5 table-sm tab_gest">
        <tr>
            <th> id_commentaire </th>
            <th> id_membre </th>
            <th> id_annonce </th>
            <th> commentaire </th>
            <th> date d'enregistrement </th>
            <th> actions </th>
        </tr>

        <?php foreach($resultats as $commentaires) : ?> 
            <tr>
                <td> <?= $commentaires['id_commentaire']?> </td>    
                <td> <?= $commentaires['membre_id'] . ' - ' .$commentaires['pseudo']?> </td>    

                <?php if(strlen($commentaires['annonce_id'] . ' - ' .$commentaires['titre']) > 20) : ?> 
                    <td> <?= substr(($commentaires['annonce_id'] . ' - ' .$commentaires['titre']), 0, 20) ?> ... </td>
                <?php else : ?>
                    <td> <?= $commentaires['annonce_id'] . ' - ' .$commentaires['titre'] ?> </td>
                <?php endif; ?>  

                <?php if(strlen($commentaires['commentaire']) > 20) : ?> 
                    <td> <?= substr($commentaires['commentaire'], 0, 20) ?> ... </td>
                <?php else : ?>
                    <td> <?= $commentaires['commentaire'] ?> </td>
                <?php endif; ?>  
                
                <td> <?= $commentaires['date_enregistrement'] ?> </td>    
                
                <td> 
                    <a href="gestion_des_commentaires.php?id=<?=$commentaires['id_commentaire']?>" class="bouton_lien"><i class="far fa-edit"></i></a>
                </td>

            </tr>
        <?php endforeach; ?>
    </table> 

    <div class="div_lien_pagination mb-5">
        <?php 
            for($i=1; $i<=$pagesTotales; $i++) {
                echo '<a class="lien_pagination" href="gestion_des_commentaires.php?page='.$i.'">'.$i.'</a>'; 
            }
        ?> 
    </div>

    <!-- Affichage commentaires -->
    <?php if ($afficher_commentaire == null) : ?>
            <p class="text-center"> Cliquer sur <i class="far fa-edit"></i> pour avoir le commentaire en détails </p>
    <?php else : ?> 
        <form action="gestion_des_commentaires.php?id=<?=$afficher_commentaire['id_commentaire']?> " method="post">
            <div class="mb-5 pl-5 mx-auto mt-5 bg-light div_affichage_commentaire">
                <p class="text-center">ID_COMMENTAIRE : <?=  $afficher_commentaire['id_commentaire']?> 
                </p><br>

                <div class="row justify-content-around ">
                    <div class="col-md-3 mr-5 ">
                        <p> <span class="text-uppercase text-black-50">Posté par :</span>  <?=$afficher_commentaire['pseudo'] ?? '' ?></p>
                        <p> <span class="text-uppercase text-black-50">Le : </span> <?=$afficher_commentaire['date_enregistrement'] ?? '' ?></p>
                        <p> <span class="text-uppercase text-black-50">Sur l'annonce : <br></span> <?=$afficher_commentaire['titre'] ?? '' ?></p>
                    </div>
                    <div class="ml-5 col-md-5">
                        <p> <span class="text-uppercase text-black-50">Le commentaire posté : </span> <br> <?=$afficher_commentaire['commentaire'] ?? '' ?></p>
                        <input type="submit" name="supprimer_commentaire" class="btn btn-danger mt-4 mb-5" value="Supprimer le commentaire">
                    </div>
                </div>
            </div>
        </form>
    <?php endif ; ?> 



<?php 
//inclusion du footer
include __DIR__ .'/../assets/includes/footer.php'; 
?>
