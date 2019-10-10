<?php

// CONFIGURATION 
require_once __DIR__ . '/assets/config/bootstrap.php';

// TRAITEMENT 
if(isset($_POST['contact'])){
    // En-têtes additionnelles
    $headers = [
        'From'         => $_POST['email'], 
        'Reply-To'     => $_POST['email'], 
        'MIME-version' => '1.0', 
        'Content-Type' => 'text/html; charset = utf-8',
        'X-mailer'     => 'PHP/'.phpversion(),
    ];

    //Message au format HTML
    $message = "<h1> Nouveau Message recu ! </h1>"; 
    $message .= '<ul>'; 
    $message .= '<li> Expediteur: '.$_POST['email'].'<li>'; 
    $message .= '<li> Objet: '.$_POST['object'].'<li>'; 
    $message .= '</ul>'; 
    $message .= '<hr><p>' . nl2br(htmlspecialchars($_POST['message'])).'</p>'; 

    // mail() retourne un booleen (true si accepté pour la livraison)
    $envoi = mail('anais.tan.lhernould@gmail.com',$_POST['object'], $message, $headers); 

    if(empty($_POST['message'])){
        ajouterFlash('warning', 'Veuillez saisir un message');
    } elseif (empty($_POST['object'])){
        ajouterFlash('warning', 'Veuillez saisir un objet');
    } elseif($envoi) {
        ajouterFlash('success', 'Votre message a été envoyée');
    } else {
        ajouterFlash('danger', 'Une erreur est survenue');

    }
}

// AFFICHAGE 
$page_title ='Contact';
$meta_description_content = ' Voici la page contact'; 
include __DIR__ .'/assets/includes/header.php';
?>
<?php include __DIR__ . '/assets/includes/msg_flash.php'; ?> 

<h1 class="h1_page"> Contactez-nous </h1>
<hr>

<div class="mt-5 ml-5 mb-5" id="form_contact">
    <form class="mx-auto" action="" method="post">
        <label for="email"> Email : </label> 
        <input id="email" type="text" name="email" class="form-control w-50" value="<?= getMembre()['email']?>" >
        <br> <br>
        <label for="objet"> Objet </label>
        <input id="objet" class="form-control w-50" type="text" name="object">
        <br>
        <label> Message </label>
        <textarea class="form-control w-50" name="message"></textarea>
        <br>
        <input type="submit" class="btn btn-dark mb-5" name="contact">
    </form>
</div>


<?php 
include __DIR__ .'/assets/includes/footer.php';    