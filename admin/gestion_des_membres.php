<?php

// CONFIGURATION 
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';

// TRAITEMENT 
$modifier_membre = getMembreById($pdo, $_GET['id'] ?? null);

// MODIFICATION DU MEMBRE
if(isset($_POST['mettre_jour'])){
    if (($modifier_membre['id_membre']) == null){
        ajouterFlash ('warning', 'Aucun membre n\'a été selectionné');
    } elseif (!preg_match('#^[a-zA-Z.-]+$#', $_POST['nom'])){
        ajouterFlash('warning', 'Le nom ne doit contenir que des lettres ainsi que les caractères . -'); 
    } elseif (!preg_match('#^[a-zA-Z.-]+$#', $_POST['prenom'])){
        ajouterFlash('warning', 'Le prénom ne doit contenir que des lettres ainsi que les caractères . -');
    } elseif (!preg_match('#^[0-9+]+$#', $_POST['telephone'])){
        ajouterFlash('warning', 'Veuillez saisir un numéro de téléphone correcte');
    } elseif($_POST['civilite'] == 'none'){
        ajouterFlash ('warning', 'Veuillez renseigner votre sexe'); 
    } elseif($_POST['statut'] == 'none'){
        ajouterFlash ('warning', 'Veuillez renseigner un statut'); 
    } else {

        $req = $pdo->prepare(
            'UPDATE membres SET 
            nom = :nom,
            prenom =:prenom, 
            telephone =:telephone, 
            civilite = :sexe, 
            statut =:statut
            WHERE id_membre = :id'
        ); 

        $req->bindParam(':nom', $_POST['nom']);
        $req->bindParam(':prenom', $_POST['prenom']);
        $req->bindParam(':telephone', $_POST['telephone']);
        $req->bindParam(':sexe', $_POST['civilite']);
        $req->bindValue(':statut', $_POST['statut']);
        $req->bindParam(':id', $modifier_membre['id_membre'], PDO::PARAM_INT);

        $req->execute(); 
        ajouterFlash('success', 'Le membre a été modifiée'); 
    }
}

// SUPPRESSION MEMBRE 
if (isset($_POST['supprimer_membre'])){
    if (($modifier_membre['id_membre']) == null){
        ajouterFlash ('warning', 'Aucun membre n\'a été selectionné');
    } else {
        $req = $pdo->prepare(
            'DELETE
            FROM membres 
            WHERE id_membre = :id'
        ); 
        $req->bindParam(':id', $modifier_membre['id_membre'], PDO::PARAM_INT);
        $req->execute(); 

        ajouterFlash ('success', ' Le membre a été supprimé'); 
    }
}

// AFFICHAGE 
$page_title ='Gestion des membres';
include __DIR__ .'/../assets/includes/header_admin.php';
?>
<?php include __DIR__ . '/../assets/includes/msg_flash.php'; ?> 

    <h1 class="h1_page"> Gestion des membres </h1>
    <hr>

    <table class="table table-bordered text-center mt-5 table-sm tab_gest">
        <tr>
            <th> id_membre </th>
            <th> pseudo </th>
            <th> nom </th>
            <th> prenom </th>
            <th> email </th>
            <th> telephone </th>
            <th> civilite </th>
            <th> statut </th>
            <th> date_enregistrement </th>
            <th> action </th>
        </tr>

        <?php foreach(listeMembres($pdo) as $membres) : ?> 
            <tr>
                <td> <?= $membres['id_membre']?> </td>    
                <td> <?= $membres['pseudo']?> </td>    
                <td> <?= $membres['nom']?> </td>
                <td> <?= $membres['prenom']?> </td>
                <td> <?= $membres['email']?> </td>
                <td> <?= $membres['telephone']?> </td>
                <?php if (($membres['civilite']) == 0) : ?> 
                    <td> Femme </td>
                <?php else : ?> 
                    <td> Hommes </td>
                <?php endif ?> 
                <?php if (($membres['statut']) == 0) : ?> 
                    <td> membre </td>
                <?php else : ?> 
                    <td> admin </td>
                <?php endif ?> 
                <td> <?= $membres['date_enregistrement']?> </td>
                
                <td> 
                    <a href="gestion_des_membres.php?id=<?=$membres['id_membre']?>" class="bouton_lien"><i class="far fa-edit"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    
    <!-- Modification des informations du membres -->
    <form action="gestion_des_membres.php?id=<?=$modifier_membre['id_membre']?>" method="post" >
        <div class="row ml-5 mt-5">
            <div class="col-md-6">
                <div class="form-group w-75">
                    <label> Email  : </label>
                    <input type="text" name="email" class="form-control" value="<?= $modifier_membre['email'] ?? '' ?>" readonly>
                </div>
                <div class="form-group w-75">
                    <label> Pseudo : </label>
                    <input type="text" name="pseudo" class="form-control" value="<?= $modifier_membre['pseudo'] ?? '' ?>" readonly>
                </div>
                <div class="form-group w-75">
                    <label> Nom : </label>
                    <input type="text" name="nom" class="form-control" value="<?= $modifier_membre['nom']?? '' ?>">
                </div>
                <div class="form-group w-75">
                    <label> Prenom : </label>
                    <input type="text" name="prenom" class="form-control" value="<?= $modifier_membre['prenom'] ?? '' ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group w-75">
                    <label> Telephone  : </label>
                    <input type="text" name="telephone" class="form-control" value="<?= $modifier_membre['telephone'] ?? '' ?>">
                </div>

                <div class="form-group w-75">
                    <label for="exampleFormControlSelect1"> Sexe : </label>
                    <select class="form-control" id="exampleFormControlSelect1" name="civilite">
                        <option value="none"
                        <?php if (isset($modifier_membre['civilite']) && $modifier_membre['civilite'] == "none") echo 'selected="selected"';?> >
                        ----------
                        </option>

                        <option value="0"
                        <?php if (isset($modifier_membre['civilite']) && $modifier_membre['civilite'] == "0") echo 'selected="selected"';?>>
                        Femme
                        </option>

                        <option value="1"
                        <?php if (isset($modifier_membre['civilite']) && $modifier_membre['civilite'] == "1") echo 'selected="selected"';?>>
                        Homme
                        </option>
                    </select>
                </div>
                <div class="form-group w-75">
                    <label for="exampleFormControlSelect1"> Statut : </label>
                    <select class="form-control" id="exampleFormControlSelect1" name="statut">
                        <option value="none"
                        <?php if (isset($modifier_membre['statut']) && $modifier_membre['statut'] == "none") echo 'selected="selected"';?> >
                        ----------
                        </option>

                        <option value="0"
                        <?php if (isset($modifier_membre['statut']) && $modifier_membre['statut'] == "0") echo 'selected="selected"';?>>
                        Membre
                        </option>

                        <option value="1"
                        <?php if (isset($modifier_membre['statut']) && $modifier_membre['statut'] == "1") echo 'selected="selected"';?>>
                        Admin
                        </option>
                    </select>
                </div>
            </div>
                <input type="submit" name="mettre_jour" class="btn btn-dark ml-3 mt-4 mb-5" value="Mettre à jour">
                <input type="submit" name="supprimer_membre" class="btn btn-danger ml-3 mt-4 mb-5" value="Supprimer le membre">
            </div>
        </div>
    </form>

<?php 
//inclusion du footer
include __DIR__ .'/../assets/includes/footer.php';   