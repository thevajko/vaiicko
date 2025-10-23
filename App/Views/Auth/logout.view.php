<?php

/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */

$view->setLayout('auth');
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5">
            You have logged out. <br>
            Again <a href="<?= App\Configuration::LOGIN_URL ?>">log in</a> or return <a
                    href="<?= $link->url("home.index") ?>">back</a> to the home page?
        </div>
    </div>
</div>
