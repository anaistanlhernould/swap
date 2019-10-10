<!doctype html>
<html lang="fr">
    <head>
    <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <!-- CDN Fontawesome-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.min.css" integrity="sha256-zmfNZmXoNWBMemUOo1XUGFfc0ihGGLYdgtJS3KCr/l0=" crossorigin="anonymous" />

        <!-- page Style CSS -->
        <link rel="stylesheet" href="../style/style.css"> 

        <title> <?= $page_title ?> | BACK-OFFICE SWAP </title>
    </head>
<body>

    <nav class="navbar navbar-expand-lg navbar navbar-light" style="background-color: #e3f2fd;">
    <a class="navbar-brand" href="../index.php">Retour à SWAP</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
            <a class="nav-link" href="../admin/index_admin.php"> Tableau de bord </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            GESTION
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="../admin/gestion_des_annonces.php">Gestion des annonces</a>
                <a class="dropdown-item" href="../admin/gestion_des_categories.php">Gestion des catégories</a>
                <a class="dropdown-item" href="../admin/gestion_des_membres.php">Gestion des membres</a>
                <a class="dropdown-item" href="../admin/gestion_des_commentaires.php">Gestion des commentaires</a>
                <a class="dropdown-item" href="../admin/gestion_des_notes.php">Gestion des notes</a>
            </div>
        </li>

        </ul>

    </div>
    </nav>

<main>