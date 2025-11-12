<?php

/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Auth\AppUser $user */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div>
                Welcome, <strong><?= $user->getName() ?></strong>!<br><br>
                This part of the application is accessible only after logging in.
            </div>
        </div>
    </div>
</div>