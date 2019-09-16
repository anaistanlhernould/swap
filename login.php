<?php

// CONFIGURATION 
require_once __DIR__ . '/assets/config/bootstrap.php';

// TRAITEMENT 

/* Connexion */
if (isset($_POST['login'])) {
    // Récupérer l'utilisateur par son pseudo/email
    $req = $pdo->prepare(
        'SELECT *
        FROM membres
        WHERE
            pseudo = :pseudo
            OR email = :email'
    );
    $req->bindParam(':pseudo', $_POST['identifiant']);
    $req->bindParam(':email', $_POST['identifiant']);
    $req->execute();
    $utilisateur = $req->fetch(PDO::FETCH_ASSOC);

    if (!$utilisateur) {
        ajouterFlash('danger', 'Utilisateur inconnu.');

    } elseif (!password_verify($_POST['mdp'], $utilisateur['mdp'])) {
        ajouterFlash('danger', 'Mot de passe erroné.');

    } else {
        unset($utilisateur['mdp']);
        $_SESSION['utilisateur'] = $utilisateur;
        header('Location: index.php');
    }
}

/* Déconnexion */ 
if (isset($_GET['logout'])) {
    unset($_SESSION['utilisateur']);
    ajouterFlash('success', 'Vous avez bien été déconnecté.');
}

// AFFICHAGE 
$page_title ='SE CONNECTER';
$meta_description_content = ' Voici la page de connexion du site SWAP'; 
include __DIR__ .'/assets/includes/header.php';
?>

    <div class="container border mt-5 p-4 w-50">
        <h1>Je me connecte</h1>

        <?php include __DIR__ . '/assets/includes/msg_flash.php'; ?>

        <form action="login.php" method="post">
            <div class="form-group w-75">
                <label>Pseudo / email</label>
                <input type="text" name="identifiant" class="form-control" value="<?= $_POST['identifiant'] ?? '' ?>">
            </div>

            <div class="form-group w-75">
                <label>Mot de passe</label>
                <input type="password" name="mdp" class="form-control">
            </div>

            <input type="submit" name="login" class="btn btn-dark" value="Connexion">
        </form>
    </div>


<?php 
//inclusion du footer
include __DIR__ .'/assets/includes/footer.php';  