<?php

/** @var \App\Core\IAuthenticator $auth */

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5">
            Vitaj <?=$auth->getLoggedUserName() ?>!
        </div>
    </div>
</div>
