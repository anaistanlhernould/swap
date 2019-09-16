<?php

// CONFIGURATION 
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';

// TRAITEMENT 
 $resultat = $pdo->query("SELECT * FROM categories");    

// Enregistrement d'une categorie 

if(isset($_POST['enregistrer'])){
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
                <i class="fas fa-search"></i>
                <i class="far fa-edit"></i>
                <i class="fas fa-trash-alt"></i>
            </td>

        </tr>
        <?php endforeach; ?>
    </table>

    <div id="form_categorie" class="mt-5" >
        <form action="gestion_des_categories.php" method="post" >

        <div class="form-group w-75">
            <label> Titre de la categorie</label>
            <input type="text" name="titre" class="form-control" value="<?= $_POST['titre'] ?? '' ?>">
        </div>

        <div class="form-group w-75">
            <label> Mots clés </label>
            <textarea name="mots_cles" class="form-control"><?= $_POST['mots_cles'] ?? '' ?></textarea>
        </div>

        <input type="submit" name="enregistrer" class="btn btn-dark" value="Enregistrer">
        </form>
    </div>

<?php 
//inclusion du footer
include __DIR__ .'/../assets/includes/footer.php';    
