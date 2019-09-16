<?php

// CONFIGURATION 
require_once __DIR__ . '/assets/config/bootstrap.php';

// TRAITEMENT
/* Enregistrement de l'annonce */ 

if (isset($_POST['annonce_ajout'])){
    if (strlen($_POST['titre']) < 5 || strlen($_POST['titre']) > 255){
        ajouterFlash('warning', 'Le titre doit contenir entre 5 et 255 caractères'); 
    } elseif (strlen($_POST['description_courte']) < 5 ||strlen($_POST['description_courte']) > 255){
        ajouterFlash('warning', 'La description courte doit contenir entre 5 et 255 caractères'); 
    } elseif (empty($_POST['description_longue'])){
        ajouterFlash('warning', 'La description longue ne peut être vide');
    } elseif (!preg_match('#^\d+(,\d{1,2})?$#', $_POST['prix'])){
        ajouterFlash('warning', 'Le prix doit être indiquer en chiffre, et separé par une virgule pour les cts');
    } elseif($_POST['categorie'] == 'none'){
        ajouterFlash ('warning', 'Veuillez renseigner une catégorie pour votre annonce'); 
    } elseif (empty($_POST['ville'])) {
        ajouterFlash ('warning', 'Veuillez saisir une ville'); 
    } elseif (empty($_POST['adresse'])) {
        ajouterFlash ('warning', 'Veuillez saisir une adresse'); 
    } elseif (!preg_match('#^[0-9]{5}+$#', $_POST['cp'])) {
        ajouterFlash ('warning', 'Veuillez saisir un code postal valide'); 
    } elseif (($_FILES['img1']['error'] == UPLOAD_ERR_NO_FILE) || ($_FILES['img2']['error'] == UPLOAD_ERR_NO_FILE) || ($_FILES['img3']['error'] == UPLOAD_ERR_NO_FILE)){
        ajouterFlash('warning', 'Les photos 1, 2 et 3 sont obligatoires'); 
    } elseif (($_FILES['img1']['size'] < 12 || exif_imagetype($_FILES['img1']['tmp_name']) === false)) { 
        ajouterFlash('warning', 'Le fichier 1 envoyé n\'est pas une image');
    } elseif (($_FILES['img2']['size'] < 12 || exif_imagetype($_FILES['img2']['tmp_name']) === false)) { 
        ajouterFlash('warning', 'Le fichier 2 envoyé n\'est pas une image');
    } elseif (($_FILES['img3']['size'] < 12 || exif_imagetype($_FILES['img3']['tmp_name']) === false)) { 
        ajouterFlash('warning', 'Le fichier 3 envoyé n\'est pas une image');
    } else {
        $extension_img1 = pathinfo($_FILES['img1']['name'], PATHINFO_EXTENSION);
        $extension_img2 = pathinfo($_FILES['img2']['name'], PATHINFO_EXTENSION);
        $extension_img3 = pathinfo($_FILES['img3']['name'], PATHINFO_EXTENSION);
        $path = __DIR__ . '/assets/img';

        do {
            $filename1 = bin2hex(random_bytes(16));
            $complete_path_img1 = $path . '/' . $filename1 . '.' . $extension_img1;
        } while (file_exists($complete_path_img1));

        do {
            $filename2 = bin2hex(random_bytes(16));
            $complete_path_img2 = $path . '/' . $filename2 . '.' . $extension_img2;
        } while (file_exists($complete_path_img2));

        do {
            $filename3 = bin2hex(random_bytes(16));
            $complete_path_img3 = $path . '/' . $filename3 . '.' . $extension_img3;
        } while (file_exists($complete_path_img3));

        if (!move_uploaded_file($_FILES['img1']['tmp_name'], $complete_path_img1)) {
            ajouterFlash('danger', 'L\'image 1 n\'a pas pu être enregistrée.');
        } 
        if (!move_uploaded_file($_FILES['img2']['tmp_name'], $complete_path_img2)) {
            ajouterFlash('danger', 'L\'image 2 n\'a pas pu être enregistrée.');
        }    
        if (!move_uploaded_file($_FILES['img3']['tmp_name'], $complete_path_img3)) {
            ajouterFlash('danger', 'L\'image 3 n\'a pas pu être enregistrée.');

        } else {
        $req_photo = $pdo->prepare(
                'INSERT INTO photos (img1, img2, img3) VALUES (:img1, :img2, :img3)'
            );
            $req_photo->bindValue(':img1', $filename1 . '.' . $extension_img1);
            $req_photo->bindValue(':img2', $filename2 . '.' . $extension_img2);
            $req_photo->bindValue(':img3', $filename3 . '.' . $extension_img3);
            $req_photo->execute();
            $photos = $pdo->lastInsertId();

            $req_annonce = $pdo->prepare(
                'INSERT INTO annonces (titre, description_courte, description_longue, prix, categorie_id, photo_id, pays, ville, adresse, cp, date_enregistrement, membre_id)

                VALUES (:titre, :desc_courte, :desc_longue, :prix, :categorie_id, :photo_id, :pays, :ville, :adresse, :cp, :date_enregistrement, :membre_id)'                
            );
            $req_annonce->bindParam(':titre', $_POST['titre']); 
            $req_annonce->bindParam(':desc_courte', $_POST['description_courte']); 
            $req_annonce->bindParam(':desc_longue', $_POST['description_longue']); 
            $req_annonce->bindParam(':prix', $_POST['prix']); 
            $req_annonce->bindValue(':categorie_id', getCategorieId($pdo, $_POST['categorie'])); 
            $req_annonce->bindValue(':photo_id', $photos); 
            $req_annonce->bindParam(':pays', $_POST['pays']); 
            $req_annonce->bindParam(':ville', $_POST['ville']); 
            $req_annonce->bindParam(':adresse', $_POST['adresse']); 
            $req_annonce->bindParam(':cp', $_POST['cp']); 
            $req_annonce->bindValue(':date_enregistrement', (new DateTime())->format('Y-m-d H:i:s'));
            $req_annonce->bindValue(':membre_id', getMembre()['id_membre']); 
            $req_annonce->execute();

            unset($_POST); 
            ajouterFlash('success', 'Votre annonce a bien été publiée'); 
        }
    }   
}



// AFFICHAGE 
$page_title ='Ajouter votre annonce';
$meta_description_content = ' Voici la page pour ajouter une annonce sur le site SWAP'; 
include __DIR__ .'/assets/includes/header.php';
?>

<h1 class="text-center mt-3"> Ajouter votre annonce </h1>

<!-- MSG FLASH -->
<?php include __DIR__ . '/assets/includes/msg_flash.php'; ?> 

<?php if (getMembre() === null): ?>
<?=  ajouterFlash('warning', 'Vous devez être connecté pour poster une annonce') ?> 

<?php else : ?> 

<form action="annonce_ajout.php" method="post" class="ml-5 mt-5 mr-1" enctype="multipart/form-data">
    <div class="form-row">
        <div class="col-md-6 mt-4">
            <div class="form-group w-75">
                <label for="titre">Titre de l'annonce</label>
                <input type="text" name="titre" id ="titre" class="form-control" value="<?= $_POST['titre'] ?? '' ?>">
            </div>
            <div class="form-group w-75">
                <label for="description_courte">Description courte de l'annonce</label>
                <textarea name="description_courte" id="description_courte" class="form-control" rows="3"><?= $_POST['description_courte'] ?? '' ?> </textarea> 
            </div>
            <div class="form-group w-75">
                <label for="description_longue">Description longue de l'annonce</label>
                <textarea type="text" name="description_longue" id="description_longue" class="form-control" rows="5"><?= $_POST['description_longue'] ?? '' ?> </textarea>
            </div>
            <div class="form-group w-75">
                <label for="prix">Prix</label>
                <input type="text" name="prix" id="prix" class="form-control" value="<?= $_POST['prix'] ?? '' ?>">
            </div>
        </div>

        <div class="col-md-6 mt-4"> 
        <div class="form-group w-75">
                <label for="categorie"> Categories : </label>
                <select class="form-control" id="categorie" name="categorie">
                    <option value="none"> ---------- </option>
                    <?php foreach (listeCategories($pdo) as $titre) : ?>
                        <option value="<?= $titre['titre'] ?>"> <?= $titre['titre'] ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <p> Les photos 1, 2 et 3 sont obligatoires </p>
            <div class="form-row ">
                <div class="col-md-2">
                    <p> Photo 1 :</p>
                    <label for="img1" class="carre_photo"><i class="fas fa-camera"></i></label>
                    <input type="file" name="img1"  id="img1" class="input_photo">
                </div>
                <div class="col-md-2">
                    <p> Photo 2 :</p>
                    <label for="img2" class="carre_photo"><i class="fas fa-camera"></i></label>
                    <input type="file" name="img2"  id="img2" class="input_photo">
                </div>
                <div class="col-md-2">
                <p> Photo 3 :</p>
                    <label for="img3" class="carre_photo"><i class="fas fa-camera"></i></label>
                    <input type="file" name="img3" id="img3" class="input_photo">
                </div>
            </div>
            <div class="form-group w-75">
                <label for="pays"> Pays : </label>
                <select class="form-control" id="pays" name="pays">
                    <option value="france"> France </option>
                </select>
            </div>
            <div class="form-group w-75">
                <label for="ville">Ville </label>
                <input type="text" name="ville" id="ville" class="form-control" value="<?= $_POST['ville'] ?? '' ?>">
            </div>
            <div class="form-group w-75">
                <label for="adresse">Adresse </label>
                <input type="text" name="adresse" id="adresse" class="form-control" value="<?= $_POST['adresse'] ?? '' ?>">
            </div>
            <div class="form-group w-75">
                <label for ="cp">Code postal </label>
                <input type="text" name="cp" id="cp" class="form-control" value="<?= $_POST['cp'] ?? '' ?>">
            </div>
        </div>
    </div>
    <input type="submit" name="annonce_ajout" value="Publier mon annonce" class="btn btn-dark mt-3">

</form>

<?php endif; ?>


<?php 
//inclusion du footer
include __DIR__ .'/assets/includes/footer.php';    
