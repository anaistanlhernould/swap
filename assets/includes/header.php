<!doctype html>
<html lang="fr">
    <head>
    <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content = "<?= $meta_description_content ?>">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <!-- CDN Fontawesome-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.min.css" integrity="sha256-zmfNZmXoNWBMemUOo1XUGFfc0ihGGLYdgtJS3KCr/l0=" crossorigin="anonymous" />

        <!-- page Style CSS -->
        <link rel="stylesheet" href="style/style.css"> 

        <title> <?= $page_title ?> | SWAP </title>
    </head>
<body>

<!-- MENU NAVIGATION -->

    <nav class="navbar navbar-expand-lg navbar-light bg-dark pl-5">
        <a class="navbar-brand text-light" href="index.php">SWAP</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon text-light"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link text-light" href="qui_sommes_nous.php"> Qui sommes nous </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-light" href="contact.php"> Contact </a>
                </li>
            </ul>
            <form class="form-inline mr-5 my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
            <?php if (getMembre() === null) : ?>
                    <li class="nav-item ">
                        <a class="nav btn btn-light" href="login.php"> Déposer une annonce </a>
                    </li>
                    <li class="nav-item dropdown bg-dark">
                        <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i> Espace membre </a>

                        <div class="dropdown-menu bg-dark" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item text-light bg-dark" href="inscription.php">Inscription</a>
                            <a class="dropdown-item text-light bg-dark" href="login.php">Connexion</a>
                        </div>
                    </li>

                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav btn btn-light pl-2" href="annonce_ajout.php"> Déposer une annonce </a>
                    </li>
                    <li class="nav-item dropdown ml-5 mr-5">
                        <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-user"></i> <?= getMembre()['pseudo'] ?> </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item text-light bg-dark" href="profil.php">Profil</a>
                            <?php if (role(ROLE_ADMIN)) : ?>
                                <a class="dropdown-item text-light bg-dark" href="admin/index_admin.php">Back-Office</a>
                            <?php endif; ?>
                            <a class="dropdown-item text-light bg-dark" href="login.php?logout">Déconnexion</a>
                        </div>
                    </li>
                <?php endif; ?>
        </div>
    </nav>

<main>