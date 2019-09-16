<?php

// CONFIGURATION 
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';

// TRAITEMENT
$req = $pdo->query(
    'SELECT id_commentaire, pseudo, titre, commentaire, c.date_enregistrement, c.membre_id, c.annonce_id
    FROM commentaires c
    LEFT JOIN membres m ON c.membre_id = m.id_membre 
    LEFT JOIN annonces a ON c.annonce_id = a.id_annonce
    '
); 

$resultats = $req->fetchAll(PDO::FETCH_ASSOC);

// AFFICHAGE 
$page_title ='Gestion des annonces';
include __DIR__ .'/../assets/includes/header_admin.php';
?>

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
            <td> <?= $commentaires['annonce_id'] . ' - ' .$commentaires['titre'] ?> </td>    
            <td> <?= $commentaires['commentaire'] ?> </td>    
            <td> <?= $commentaires['date_enregistrement'] ?> </td>    
            
            <td> 
                <i class="fas fa-search"></i>
                <i class="fas fa-trash-alt"></i>
            </td>

        </tr>
    <?php endforeach; ?>


</table> 

<?php 
//inclusion du footer
include __DIR__ .'/../assets/includes/footer.php'; 