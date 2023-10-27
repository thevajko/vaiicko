<?php

$layout = 'auth';
/** @var \App\Core\LinkGenerator $link */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5">
            Odhlásili ste sa. <br>
            Znovu <a href="<?= \App\Config\Configuration::LOGIN_URL ?>">prihlásiť</a> alebo vrátiť sa <a
                    href="<?= $link->url("home.index") ?>">späť</a> na hlavnú stránku?
        </div>
    </div>
</div>
