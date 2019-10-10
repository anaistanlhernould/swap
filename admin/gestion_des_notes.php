<?php

// CONFIGURATION 
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';

// TRAITEMENT
// PAGINATION 
$notesParPage = 8; 
$notesTotalesReq = $pdo->query('SELECT id_note FROM notes'); 
$notesTotales = $notesTotalesReq->rowCount(); 
$pagesTotales = ceil($notesTotales/$notesParPage); 

if(isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 ){
    $_GET['page'] = intval($_GET['page']); 
    $pageCourante = $_GET['page']; 
} else {
    $pageCourante = 1; 
}

$depart = ($pageCourante-1)*$notesParPage; 

    $req = $pdo->query(
        'SELECT id_note, m.pseudo, note, avis, n.date_enregistrement, membre_id1, membre_id2
        FROM notes n
        LEFT JOIN membres m ON n.membre_id2 = m.id_membre
        ORDER BY n.date_enregistrement DESC LIMIT '.$depart.','.$notesParPage
        );

$resultats = $req->fetchAll(PDO::FETCH_ASSOC);

// VARIABLE ETOILE 
$etoile_vide = '<i class="far fa-star"></i>'; 
$demi_etoile = '<i class="fas fa-star-half-alt"></i>';
$etoile_pleine = '<i class="fas fa-star"></i>';

// AFFICHAGE NOTE 
$afficher_note = getNotesById($pdo, $_GET['id'] ?? null); 

// SUPPRESSION NOTE 
if (isset($_POST['supprimer_note'])){

    $req = $pdo->prepare(
        'DELETE
        FROM notes 
        WHERE id_note = :id'
    ); 
    $req->bindParam(':id', $afficher_note['id_note'], PDO::PARAM_INT);
    $req->execute(); 

    unset($_POST); 
    ajouterFlash ('success', ' La note a été supprimée'); 
}


// AFFICHAGE 
$page_title ='Gestion des notes';
include __DIR__ .'/../assets/includes/header_admin.php';
?>
<?php include __DIR__ . '/../assets/includes/msg_flash.php'; ?> 

    <h1 class="h1_page"> Gestion des notes </h1>
    <hr>

    <table class="table table-bordered text-center mt-5 table-sm tab_gest">
        <tr>
            <th> id note </th>
            <th> id_membre 1 (le noteur)</th>
            <th> id_membre 2 (le noté)</th>
            <th> note </th>
            <th> avis </th>
            <th> date enregistrement </th>
            <th> actions </th>
        </tr>

        <?php foreach($resultats as $notes) : ?> 
            <tr>
                <td> <?= $notes['id_note']?> </td>  
                <td> <?= getMembre1ByNote($pdo, $notes['membre_id1'])['pseudo']?></td>  
                <td> <?= getMembre1ByNote($pdo, $notes['membre_id2'])['pseudo']?> </td>    
                <td> 
                    <?php 
                        $note = $notes['note'];
                        $restant = 5 - ($note); 
                        for ($i = 0; $i < $note; $i++){
                            echo($etoile_pleine);
                        }
                        for ($i = 0; $i < $restant; $i++){
                            echo($etoile_vide);
                        } 
                    ?> 
                </td>    
                <td> <?= $notes['avis'] ?> </td>    
                <td> <?= $notes['date_enregistrement'] ?> </td>    
                <td> 
                    <a href="gestion_des_notes.php?id=<?=$notes['id_note']?>" class="bouton_lien"><i class="far fa-edit"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table> 

    <div class="div_lien_pagination mb-5">
        <?php 
            for($i=1; $i<=$pagesTotales; $i++) {
                echo '<a class="lien_pagination" href="gestion_des_notes.php?page='.$i.'">'.$i.'</a>'; 
            }
        ?> 
    </div>

    <!-- Affichage notes -->
    <?php if ($afficher_note == null) : ?>
        <p class="text-center"> Cliquer sur <i class="far fa-edit"></i> pour avoir la note en détails </p>
    <?php else : ?> 
        <form action="gestion_des_notes.php?id=<?=$afficher_note['id_note']?> " method="post">
            <div class="mb-5 pl-5 mx-auto mt-5 bg-light div_affichage_commentaire">
                <p class="text-center">ID_NOTE : <?=  $afficher_note['id_note']?> 
                </p>
                <br>

                <div class="row justify-content-around ">
                    <div class="col-md-3 mr-5 ">
                        <p> <span class="text-uppercase text-black-50">L'acheteur :</span> <?= getMembre1ByNote($pdo, $afficher_note['membre_id1'])['pseudo']?></p>
                        <p> <span class="text-uppercase text-black-50">Le vendeur : </span> <?= getMembre2ByNote($pdo, $afficher_note['membre_id2'])['pseudo']?></p>
                        <p> <span class="text-uppercase text-black-50">Le : </span> <?=$afficher_note['date_enregistrement'] ?? '' ?></p>
                    </div>
                    <div class="ml-5 col-md-5">
                        <p> <span class="text-uppercase text-black-50">La note :</span> 
                            <?php 
                                $note = $afficher_note['note'];
                                $restant = 5 - ($note); 
                                for ($i = 0; $i < $note; $i++){
                                    echo($etoile_pleine);
                                }
                                for ($i = 0; $i < $restant; $i++){
                                    echo($etoile_vide);
                                } 
                            ?> 
                        </p>
                        <p> <span class="text-uppercase text-black-50">L'avis : </span> 
                        <?=$afficher_note['avis'] ?? '' ?></p>
                        <input type="submit" name="supprimer_note" class="btn btn-danger mt-3 mb-5" value="Supprimer la note">
                    </div>
                </div>
            </div>
        </form>
    <?php endif ; ?> 

<?php 
//inclusion du footer
include __DIR__ .'/../assets/includes/footer.php'; 