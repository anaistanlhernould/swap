<?php

// CONFIGURATION 
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';

// TRAITEMENT 

$req = $pdo->query(
    'SELECT id_annonce, a.titre AS titre_annonces, description_courte, description_longue, prix, pays, ville, adresse, cp, pseudo, img1, img2, img3, c.titre AS titre_categories, a.date_enregistrement 
    FROM annonces a
    LEFT JOIN membres m ON a.membre_id = m.id_membre
    LEFT JOIN photos p ON a.photo_id = p.id_photo
    LEFT JOIN categories c ON a.categorie_id = c.id_categorie
    '
); 

$resultats = $req->fetchAll(PDO::FETCH_ASSOC);


// AFFICHAGE 
$page_title ='Gestion des annonces';
include __DIR__ .'/../assets/includes/header_admin.php';
?>

<h1 class="h1_page"> Gestion des annonces </h1>
<hr>

<select name="categories" id="list_categories" class="form-control form-control-sm">
    <option value="none"> Trier par catégorie </option>
    <?php foreach (listeCategories($pdo) as $categories) : ?> 
    <option value="<?= $categories['titre']?>"> <?= $categories ['titre'] ?> </option>
    <?php endforeach; ?> 
</select>

<div id="resultat">
    <table class="table table-bordered text-center mt-5 table-sm tab_gest">
        <tr>
            <th> id_annonce </th>
            <th> titre </th>
            <th> description courte </th>
            <th> description longue </th>
            <th> prix </th>
            <th> photo </th>
            <th> pays </th>
            <th> ville </th>
            <th> adresse </th>
            <th> cp </th>
            <th> membre </th>
            <th> categorie </th>
            <th> date d'enregistrement </th>
            <th> actions </th>
        </tr>
        <?php foreach($resultats as $annonce) :  ?>
            <tr>
                <td> <?= $annonce['id_annonce'] ?> </td>
                <td> <?= $annonce['titre_annonces'] ?> </td>

            <?php if(strlen($annonce['description_courte']) > 20) : ?> 
                <td> <?= substr($annonce['description_courte'], 0, 20) ?> ... </td>
                <?php else : ?>
                <td> <?= $annonce['description_courte'] ?> </td>
            <?php endif; ?>

            <?php if(strlen($annonce['description_longue']) > 20) : ?> 
                <td> <?= substr($annonce['description_longue'], 0, 20) ?> ... </td>
                <?php else : ?>
                <td> <?= $annonce['description_longue'] ?> </td>
            <?php endif; ?>

                <td> <?= $annonce['prix'] ?> </td>
                <td> <img src="../assets/img/<?= $annonce['img1']?>" class="img_gestion_annonces"> </td>
                <td> <?= $annonce['pays'] ?> </td>
                <td> <?= $annonce['ville'] ?> </td>
                <td> <?= $annonce['adresse'] ?> </td>
                <td> <?= $annonce['cp'] ?> </td>
                <td> <?= $annonce['pseudo'] ?> </td>
                <td> <?= $annonce['titre_categories'] ?> </td>
                <td> <?= $annonce['date_enregistrement'] ?> </td>
                <td> 
                    <i class="fas fa-search"></i>
                    <i class="far fa-edit"></i>
                    <i class="fas fa-trash-alt"></i>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php 
//inclusion du footer
include __DIR__ .'/../assets/includes/footer.php';    
?> 
<script src="../js/tri_categorie_bo_js.js"></script>

