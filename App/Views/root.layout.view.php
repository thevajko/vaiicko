<?php

/** @var string $contentHTML */
/** @var \App\Core\IAuthenticator $auth */
/** @var \App\Core\LinkGenerator $link */
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <title><?= \App\Config\Configuration::APP_NAME ?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="public/css/styl.css">
    <script src="public/js/script.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $link->url("home.index")?>">
            <img src="public/images/logos/mf100_Logo_30x30.png" width="30" height="30" class="d-inline-block align-top" alt="Logo of MF100">
            Malofatranská stovka
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a href="<?= $link->url("home.index")?>" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="<?= $link->url("home.podakovanie")?>" class="nav-link">Poďakovanie</a></li>
                <li class="nav-item"><a href="<?= $link->url("home.rocnik10")?>" class="nav-link">10.ročník</a></li>
                <?php if (!$auth->isLogged()) : ?>
                <li class="nav-item"><a href="<?= $link->url("auth.registracia")?>" class="nav-link">Registrácia</a></li>
                <li class="nav-item"><a href="<?= \App\Config\Configuration::LOGIN_URL ?>" class="nav-link">Prihlásenie</a></li>
                <?php endif;?>
            </ul>
            <form class="d-flex float-right">
                <input id="searchBox" class="form-control me-2" type="search" placeholder="Vyhľadajte" aria-label="Search">
                <button id="searchButton" class="btn btn-outline-success" type="submit">Hľadaj</button>
            </form>
        </div>
    </div>
</nav>
<div class="container-fluid mt-3">
    <div class="web-content">
        <?= $contentHTML ?>
    </div>
</div>
</body>
</html>
