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

    <nav class="navbar navbar-expand-lg navbar-light bg-dark ">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto row">

                <li class="nav-item active">
                    <a class="nav-link text-light" href="index.php"> SWAP </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-light" href="#"> Qui sommes nous </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-light" href="#"> Contact </a>
                </li>
                
                <form class="form-inline my-5 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
                
                <?php if (getMembre() === null) : ?>
                    <li class="nav-item">
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
                        <a class="nav btn btn-light" href="annonce_ajout.php"> Déposer une annonce </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-user"></i> <?= getMembre()['pseudo'] ?> </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item text-light bg-dark" href="#">Profil</a>
                            <?php if (role(ROLE_ADMIN)) : ?>
                                <a class="dropdown-item text-light bg-dark" href="admin/gestion_des_annonces.php">Back-Office</a>
                            <?php endif; ?>
                            <a class="dropdown-item text-light bg-dark" href="login.php?logout">Déconnexion</a>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>