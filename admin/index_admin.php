<?php

// CONFIGURATION 
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';

// TRAITEMENT 
    // 5 membres les mieux notés 
    $req = $pdo->query(
        'SELECT pseudo, membre_id2, ROUND(AVG(note),2/2) AS moyenne, COUNT(*) AS sum_avis 
        FROM notes n
        LEFT JOIN membres m ON n.membre_id2 = m.id_membre
        GROUP BY membre_id2 
        ORDER BY moyenne 
        DESC LIMIT 5'
    ); 
    $resultat_notes = $req->fetchAll(PDO::FETCH_ASSOC);

    // 5 membres les plus actifs
    $req = $pdo->query(
        'SELECT pseudo, membre_id, COUNT(*) AS nb_annonce  
        FROM annonces a
        LEFT JOIN membres m ON a.membre_id = m.id_membre
        GROUP BY membre_id
        ORDER BY nb_annonce 
        DESC LIMIT 5'
    ); 
    $resultat_annonces = $req->fetchAll(PDO::FETCH_ASSOC);

    // 5 annonces les plus anciennes 
    $req = $pdo->query(
        'SELECT a.titre AS titre_annonce, img1, a.date_enregistrement AS date_annonce
        FROM annonces a
        LEFT JOIN photos p ON a.photo_id = p.id_photo
        ORDER BY a.date_enregistrement ASC LIMIT 5
        '
    ); 
    $resultat_anciens = $req->fetchAll(PDO::FETCH_ASSOC);

    // 5 catégories avec le plus d'annonces
    $req = $pdo->query(
        'SELECT c.titre, categorie_id, COUNT(*) AS nb_annonce  
        FROM annonces a
        LEFT JOIN categories c ON a.categorie_id = c.id_categorie
        GROUP BY categorie_id
        ORDER BY nb_annonce 
        DESC LIMIT 5'
    ); 
    $resultat_categorie = $req->fetchAll(PDO::FETCH_ASSOC);


// AFFICHAGE 
$page_title ='Statistiques';
include __DIR__ .'/../assets/includes/header_admin.php';
?>

    <h1 class="h1_page"> Statistiques </h1>
    <hr>

    <div class="row mt-5 mb-5">
        <div class="div_stat col-12 col-md ml-md-2">
            <h6 class="text-center uppercase"> 5 membres les mieux notés</h6>
            <hr>
            <?php foreach($resultat_notes as $mieux_notes ) : ?>
                <div class="row justify-content-between">
                    <p class="part1"><?= $mieux_notes['pseudo']?></p> 
                    <p class="part2"> note de <?= $mieux_notes['moyenne']?> / 5 basé sur <?= $mieux_notes['sum_avis']?> avis </p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="div_stat div_stat_rouge col-12 col-md ml-md-2">
            <h6 class="text-center uppercase"> 5 membres les plus actifs</h6>
            <hr>
            <?php foreach($resultat_annonces as $plus_actifs ) : ?>
                <div class="row justify-content-between">
                    <p class="part1"><?= $plus_actifs['pseudo']?></p> 
                    <p class="part2"> <?= $plus_actifs['nb_annonce']?> annonces  </p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="div_stat div_stat_marron col-12 col-md ml-md-2">
            <h6 class="text-center uppercase"> 5 catégories avec le plus d'annonces</h6>
            <hr>
            <?php foreach($resultat_categorie as $categorie ) : ?>
                <div class="row justify-content-between">
                    <p class="part1"><?= $categorie['titre']?></p> 
                    <p class="part2"> <?= $categorie['nb_annonce']?> annonces </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <h6 class="h6_annonces text-center uppercase mx-auto"> 5 annonces les plus anciennes</h6>
    <table class="tableau_anciennes_annonces">
        <?php foreach($resultat_anciens as $plus_anciens ) : ?>
            <tr class="">
                <td class="w-50 pl-4"><?= $plus_anciens['titre_annonce']?></td> 
                <td class="text-center w-25"> <?= $plus_anciens['date_annonce']?></td>
                <td class="text-right pr-4"><img src="../assets/img/<?= $plus_anciens['img1']?>"></td> 
            </tr>
        <?php endforeach; ?>
    </table>

<?php 
//inclusion du footer
include __DIR__ .'/../assets/includes/footer.php';    
?> 
<script src="../js/tri_categorie_bo_js.js"></script>