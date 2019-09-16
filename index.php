<?php

// CONFIGURATION 
require_once __DIR__ . '/assets/config/bootstrap.php';

// TRAITEMENT 
$req = $pdo->query(

    'SELECT id_annonce, titre, description_courte, prix, pseudo, img1
    FROM annonces a, photos p, membres m
    WHERE a.photo_id = p.id_photo
    AND a.membre_id = m.id_membre
    ORDER BY a.date_enregistrement DESC LIMIT 3'
); 
$resultats = $req->fetchAll(PDO::FETCH_ASSOC);


// AFFICHAGE 
$page_title ='Accueil';
$meta_description_content = ' Voici la page d\'accueil du site SWAP'; 
include __DIR__ .'/assets/includes/header.php';
?>



<div class="row mt-5">
    <div class="col-md-3 ml-5">
        <div class="form-group">
            <label for="list_categories_accueil">Catégorie</label>
            <select name="categories" id="list_categories_accueil" class="form-control form-control-sm">
                <option value="none"> Toutes les catégories </option>
                <?php foreach (listeCategories($pdo) as $categories) : ?> 
                <option value="<?= $categories['titre']?>"> <?= $categories ['titre'] ?> </option>
                <?php endforeach; ?> 
            </select>
        </div>

        <form>
            <div class="form-group">
                <label for="formControlRange"> Prix </label>
                <input type="range" class="form-control-range" id="formControlRange">
            </div>
        </form>
    </div>
    <div class="col-md-8 row ">
    <div class="form-group">
            <select class="form-control form-control-sm ml-5" id="exampleFormControlSelect1">
                <option> Trier par date (du plus récents aux plus anciens) </option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
            </select>
        </div>
        <?php foreach($resultats as $annonce) :  ?>
            <a href="annonce.php?id=<?=$annonce['id_annonce']?>">
                <div class="card mb-3 annonce_body ml-5">
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
        <?php endforeach; ?>
    </div>
</div>









<?php 
//inclusion du footer
include __DIR__ .'/assets/includes/footer.php';    
