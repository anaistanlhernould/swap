<?php

// CONFIGURATION 
require_once __DIR__ . '/assets/config/bootstrap.php';

// TRAITEMENT 

$annoncesParPage = 3; 
$annoncesTotalesReq = $pdo->query('SELECT id_annonce FROM annonces'); 
$annoncesTotales = $annoncesTotalesReq->rowCount(); 
$pagesTotales = ceil($annoncesTotales/$annoncesParPage); 

if(isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 ){
    $_GET['page'] = intval($_GET['page']); 
    $pageCourante = $_GET['page']; 
} else {
    $pageCourante = 1; 
}

$depart = ($pageCourante-1)*$annoncesParPage; 

$req = $pdo->query(

    'SELECT id_annonce, titre, description_courte, prix, pseudo, img1
    FROM annonces a, photos p, membres m
    WHERE a.photo_id = p.id_photo
    AND a.membre_id = m.id_membre
    ORDER BY a.date_enregistrement DESC LIMIT '.$depart.','.$annoncesParPage); 
$resultats = $req->fetchAll(PDO::FETCH_ASSOC);


// AFFICHAGE 
$page_title ='Accueil';
$meta_description_content = ' Voici la page d\'accueil du site SWAP'; 
include __DIR__ .'/assets/includes/header.php';
?>

<h1 class="h1_page"> Bienvenue sur SWAP </h1>
<hr>

<div class="mt-5">
    <div class="col-6 mx-auto">
        <br>
        <?php foreach($resultats as $annonce) :  ?>
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
                                        <p class="card-text col-8"><small class="text-muted"><?= $annonce['pseudo'] ?></small></p>
                                        <p class="card-text col-4"><?= $annonce['prix']?> €</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="div_lien_pagination">
<?php 
    for($i=1; $i<=$pagesTotales; $i++) {
            echo '<a class="lien_pagination" href="index.php?page='.$i.'">'.$i.'</a>'; 
    }
?> 
</div>






<?php 
//inclusion du footer
include __DIR__ .'/assets/includes/footer.php';    
