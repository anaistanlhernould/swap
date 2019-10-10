<?php

// CONFIGURATION 
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';

// TRAITEMENT 
$resultat = $pdo->query("SELECT * FROM categories");    
$modifier_categorie = getCategorieById($pdo, $_GET['id'] ?? null); 

// ENREGISTREMENT D'UNE CATEGORIE 
if(isset($_POST['enregistrer_categorie'])){
    if(empty($_POST['titre'])){
        ajouterFlash('warning', 'Le titre ne peut pas être vide'); 
    } elseif (strlen($_POST['titre']) > 255){
        ajouterFlash('danger', 'Le titre peut au maximum contenir 255 mots'); 
    } elseif (empty($_POST['mots_cles'])){
        ajouterFlash('warning', 'Veuillez saisir des mots-clés'); 
    } else {
        $req = $pdo->prepare(
            'INSERT INTO categories(titre, motscles) VALUES (:titre, :mots_cles)'
        ); 
        $req->bindParam(':titre', $_POST['titre']); 
        $req->bindParam(':mots_cles', $_POST['mots_cles']); 

        $req->execute(); 

        unset($_POST); 
        ajouterFlash('success', 'La catégorie a bien été enregistrée');
    }
}

// MODFICATION D'UNE CATEGORIE  
if(isset($_POST['modifier_categorie'])){
    if (strlen($_POST['titre_modifier']) > 255){
        ajouterFlash('danger', 'Le titre peut au maximum contenir 255 mots'); 
    } else {
        $req = $pdo->prepare(
            'UPDATE categories SET             
            titre = :titre,
            motscles =:motscles 
            WHERE id_categorie = :id'
        ); 
        $req->bindParam(':titre', $_POST['titre_modifier']); 
        $req->bindParam(':motscles', $_POST['mots_cles_modifier']); 
        $req->bindParam(':id', $modifier_categorie['id_categorie']);
        $req->execute(); 

        unset($_POST); 
        ajouterFlash('success', 'La catégorie a bien modifiée');
    }
}

// SUPPRESSION CATEGORIE 
if (isset($_POST['supprimer_categorie'])){

        $req = $pdo->prepare(
            'DELETE
            FROM categories 
            WHERE id_categorie = :id'
        ); 
        $req->bindParam(':id', $modifier_categorie['id_categorie'], PDO::PARAM_INT);
        $req->execute();

        unset($_POST); 
        ajouterFlash ('success', ' La categorie a été supprimée'); 
    
}

// AFFICHAGE 
$page_title ='Gestion des categories';
include __DIR__ .'/../assets/includes/header_admin.php';
?>

<?php include __DIR__ . '/../assets/includes/msg_flash.php'; ?> 

    <h1 class="h1_page"> Gestion des catégories </h1>
    <hr>

    <table class="table table-bordered text-center mt-5 table-sm tab_gest">
        <tr>
            <?php for ($i=0; $i < $resultat->columnCount(); $i++) : ?> 
                <th> <?= $resultat->getColumnMeta($i)["name"] ?> </th>
            <?php endfor; ?>
            <th> <span>Actions</span>  </th>
        </tr>
        <?php foreach(listeCategories($pdo) as $categories) : ?> 
            <tr>
                <td> <?= $categories['id_categorie']?> </td>    
                <td> <?= $categories['titre']?> </td>    
                <td> <?= $categories['motscles']?> </td>
                <td> 
                    <a href="gestion_des_categories.php?id=<?=$categories['id_categorie']?>" class="bouton_lien" id="modifier_categorie"><i class="far fa-edit"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div id="form_categorie" class="mt-5" >
        <?php if($modifier_categorie == false) : ?> 
            <form action="gestion_des_categories.php" method="post" >
        <?php else : ?> 
            <form action="gestion_des_categories.php?id=<?=$modifier_categorie['id_categorie']?>" method="post" >
        <?php endif; ?> 

        <div class="form-group w-75">
            <label> Titre de la categorie</label>
            <?php if($modifier_categorie == false) : ?> 
                <input type="text" name="titre" class="form-control" value="<?= $_POST['titre'] ?? '' ?>">
            <?php else : ?> 
                <input type="text" name="titre_modifier" class="form-control" value="<?= $modifier_categorie['titre'] ?? '' ?>">
            <?php endif; ?> 
        </div>

        <div class="form-group w-75">
            <label> Mots clés </label>
            <?php if($modifier_categorie == false) : ?> 
                <textarea name="mots_cles" class="form-control"><?= $_POST['mots_cles'] ?? '' ?></textarea>
            <?php else : ?> 
                <textarea name="mots_cles_modifier" class="form-control"><?= $modifier_categorie['motscles'] ?? '' ?></textarea>
            <?php endif; ?> 
        </div>

            <?php if($modifier_categorie == false) : ?> 
                <input type="submit" name="enregistrer_categorie" class="btn btn-dark" value="Enregistrer">
            <?php else : ?> 
                <input type="submit" name="modifier_categorie" class="btn btn-dark" value="Modifier la catégorie">
                <input type="submit" name="supprimer_categorie" class="btn btn-danger" value="Supprimer la catégorie">
            <?php endif; ?> 

        </form>
    </div>

<?php 
//inclusion du footer
include __DIR__ .'/../assets/includes/footer.php';    
