<?php 

require_once __DIR__ . '/../config/bootstrap_admin.php';

$req = $pdo->prepare(
    'SELECT id_annonce, a.titre AS titre_annonces, description_courte, description_longue, prix, pays, ville, adresse, cp, pseudo, img1, img2, img3, c.titre AS titre_categories, a.date_enregistrement 
    FROM annonces a
    LEFT JOIN membres m ON a.membre_id = m.id_membre
    LEFT JOIN photos p ON a.photo_id = p.id_photo
    LEFT JOIN categories c ON a.categorie_id = c.id_categorie
    WHERE c.titre = :titre'
); 

$req->bindParam(':titre', $_POST['titre']); 
$req->execute(); 
$resultat = $req->fetchAll(PDO::FETCH_ASSOC);


$tab['resultat'] = '<table class="table table-bordered text-center mt-5 table-sm tab_gest">';
    $tab['resultat'] .= '<tr>';
        $tab['resultat'] .= '<th> id_annonce </th>';
        $tab['resultat'] .= '<th> titre </th>';
        $tab['resultat'] .= '<th> description courte </th>';
        $tab['resultat'] .= '<th> description longue </th>';
        $tab['resultat'] .= '<th> prix </th>';
        $tab['resultat'] .= '<th> photo </th>';
        $tab['resultat'] .= '<th> pays </th>';
        $tab['resultat'] .= '<th> ville </th>';
        $tab['resultat'] .= '<th> adresse </th>';
        $tab['resultat'] .= '<th> cp </th>';
        $tab['resultat'] .= '<th> membre </th>';
        $tab['resultat'] .= '<th> categorie </th>';
        $tab['resultat'] .= '<th> date d\'enregistrement </th>';
        $tab['resultat'] .= '<th> actions </th>';
    $tab['resultat'] .= '</tr>';
    
        foreach($resultat as $annonce) {
            $tab['resultat'] .= '<tr>';

                $tab['resultat'] .= '<td>'.$annonce['id_annonce'].'</td>';
                $tab['resultat'] .= '<td>'.$annonce['titre_annonces'].'</td>';

                if(strlen($annonce['description_courte']) > 20) {
                    $tab['resultat'] .= '<td>'.substr($annonce['description_courte'], 0, 20).'...</td>';
                } else {
                    $tab['resultat'] .= '<td>'.$annonce['description_courte'].'</td>';
                }

                if(strlen($annonce['description_longue']) > 20) {
                    $tab['resultat'] .= '<td>'.substr($annonce['description_longue'], 0, 20).'...</td>';
                } else {
                    $tab['resultat'] .= '<td>'.$annonce['description_longue'].'</td>';
                }

                $tab['resultat'] .= '<td>'.$annonce['prix'].'</td>';
                $tab['resultat'] .= '<td> <img src="../assets/img/'.$annonce['img1'].'" class="img_gestion_annonces"></td>';

                $tab['resultat'] .= '<td>'.$annonce['pays'].'</td>';
                $tab['resultat'] .= '<td>'.$annonce['ville'].'</td>';
                $tab['resultat'] .= '<td>'.$annonce['adresse'].'</td>';
                $tab['resultat'] .= '<td>'.$annonce['cp'].'</td>';
                $tab['resultat'] .= '<td>'.$annonce['pseudo'].'</td>';
                $tab['resultat'] .= '<td>'.$annonce['titre_categories'].'</td>';
                $tab['resultat'] .= '<td>'.$annonce['date_enregistrement'].'</td>';
                $tab['resultat'] .= '<td>';
                    $tab['resultat'] .= '<i class="fas fa-search"></i>';
                    $tab['resultat'] .= '<i class="far fa-edit"></i>';
                    $tab['resultat'] .= '<i class="fas fa-trash-alt"></i>';
                $tab['resultat'] .='</td>';

            $tab['resultat'] .= '</tr>';
        }

    $tab['resultat'] .= '</table>';


echo json_encode($tab); 
