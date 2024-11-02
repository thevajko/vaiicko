<?php

/** @var \App\Core\IAuthenticator $auth */ ?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div>
                Welcome, <strong><?= $auth->getLoggedUserName() ?></strong>!<br><br>
                This part of the application is accessible only after logging in.
            </div>
        </div>
    </div>
</div>