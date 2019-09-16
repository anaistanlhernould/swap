<?php

// CONFIGURATION 
require_once __DIR__ . '/assets/config/bootstrap.php';

// TRAITEMENT 

// Inscription d'un utilisateur 

if(isset($_POST['inscription'])){
    if(strlen($_POST['pseudo']) < 3 || strlen($_POST['pseudo']) > 20){
        ajouterFlash('warning', 'Votre pseudo doit contenir entre 3 et 20 caractères'); 
    } elseif (!preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['pseudo'])){
        ajouterFlash('warning', 'Votre pseudo ne doit contenir que des lettres et des chiffres ainsi que les caractères . - _ '); 
    } elseif (getMembreBy($pdo, 'pseudo', $_POST['pseudo']) !== null){
        ajouterFlash('warning', 'Ce pseudo est déja pris');
    } elseif (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$#', $_POST['mdp'])){
        ajouterFlash('danger', 'Votre mot de passe doit contenir au min. 8 caractères, 1 majuscule, 1 minuscule, et 1 caractère spécial');
    } elseif ($_POST['mdp'] !== $_POST['confirmation']){
        ajouterFlash('danger', 'Les mots de passe ne correspondent pas');
    } elseif (!preg_match('#^[a-zA-Z.-]+$#', $_POST['nom'])){
        ajouterFlash('warning', 'Votre nom ne doit contenir que des lettres ainsi que les caractères . -');
    } elseif (!preg_match('#^[a-zA-Z.-]+$#', $_POST['prenom'])){
        ajouterFlash('warning', 'Votre prénom ne doit contenir que des lettres ainsi que les caractères . -');
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        ajouterFlash('danger', 'Veuillez saisir une adresse email correcte'); 
    } elseif (getMembreBy($pdo, 'email', $_POST['email']) !== null){
        ajouterFlash('danger', 'Un compte existe déjà avec cette adresse email');
    } elseif (!preg_match('#^[0-9+]+$#', $_POST['telephone'])){
        ajouterFlash('warning', 'Veuillez saisir un numéro de téléphone correcte');
    } elseif($_POST['civilite'] == 'none'){
        ajouterFlash ('warning', 'Veuillez renseigner votre sexe'); 
    } else {

        $req = $pdo->prepare(
            'INSERT INTO membres (pseudo, mdp, nom, prenom, email, telephone, civilite, statut, date_enregistrement)
            VALUES (
                :pseudo, 
                :mdp, 
                :nom, 
                :prenom, 
                :email,
                :telephone, 
                :sexe, 
                :statut, 
                :date_enregistrement
            )'
        ); 

        $req->bindParam(':pseudo', $_POST['pseudo']);
            $mdp = password_hash($_POST['mdp'],PASSWORD_BCRYPT);
        $req->bindParam(':mdp', $mdp);
        $req->bindParam(':nom', $_POST['nom']);
        $req->bindParam(':prenom', $_POST['prenom']);
        $req->bindParam(':email', $_POST['email']);
        $req->bindParam(':telephone', $_POST['telephone']);
        $req->bindParam(':sexe', $_POST['civilite']);
        $req->bindValue(':statut', 0);
        $req->bindValue(':date_enregistrement', (new DateTime())->format('Y-m-d H:i:s'));

        $req->execute(); 

        unset($_POST); 
        ajouterFlash('success', 'Votre inscription a bien été prise en compte'); 
    }
}



// AFFICHAGE 
$page_title ='S\'INSCRIRE';
$meta_description_content = ' Voici la page d\'inscription du site SWAP'; 
include __DIR__ .'/assets/includes/header.php';
?>

<!-- FORMULAIRE INSCRIPTION -->
<div class="container border mt-4 p-4 w-50">
        <h1 class="text-center"> S'inscrire </h1>

        <!-- MSG FLASH -->
        <?php include __DIR__ . '/assets/includes/msg_flash.php'; ?> 

        <form action="inscription.php" method="post" class="ml-2 mt-4">
        <div class="form-group w-75">
            <label> Pseudo : </label>
            <input type="text" name="pseudo" class="form-control" value="<?= $_POST['pseudo'] ?? '' ?>">
        </div>

        <div class="form-group w-75">
            <label> Mot de passe : </label>
            <input type="password" name="mdp" class="form-control">
        </div>

        <div class="form-group w-75">
            <label> Confirmation du mot de passe :  </label>
            <input type="password" name="confirmation" class="form-control">
        </div>

        <div class="form-group w-75">
            <label> Nom : </label>
            <input type="text" name="nom" class="form-control" value="<?= $_POST['nom'] ?? '' ?>">
        </div>

        <div class="form-group w-75">
            <label> Prenom : </label>
            <input type="text" name="prenom" class="form-control" value="<?= $_POST['prenom'] ?? '' ?>">
        </div>

        <div class="form-group w-75">
            <label> Email  : </label>
            <input type="text" name="email" class="form-control" value="<?= $_POST['email'] ?? '' ?>">
        </div>

        <div class="form-group w-75">
            <label> Telephone  : </label>
            <input type="text" name="telephone" class="form-control" value="<?= $_POST['telephone'] ?? '' ?>">
        </div>

        <div class="form-group w-75">
            <label for="exampleFormControlSelect1"> Sexe : </label>
            <select class="form-control" id="exampleFormControlSelect1" name="civilite">

                <option value="none"
                <?php if (isset($_POST['civilite']) && $_POST['civilite'] == "none") echo 'selected="selected"';?> >
                ----------
                </option>

                <option value="0"
                <?php if (isset($_POST['civilite']) && $_POST['civilite'] == "0") echo 'selected="selected"';?>>
                Femme
                </option>

                <option value="1"
                <?php if (isset($_POST['civilite']) && $_POST['civilite'] == "1") echo 'selected="selected"';?>>
                Homme
                </option>
            </select>
        </div>

        <input type="submit" name="inscription" class="btn btn-dark mt-4" value="Inscription">
        </form>
    </div>


<?php 
//inclusion du footer
include __DIR__ .'/assets/includes/footer.php';    