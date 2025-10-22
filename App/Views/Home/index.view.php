<?php

/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col mt-5">
            <div class="text-center">
                <h2>Vaííčko MVC FW</h2>
                <h3>Version <?= App\Configuration::FW_VERSION ?></h3>
                <img src="<?= $link->asset('images/vaiicko_logo.png') ?>" alt="Framework Logo">
                <p>
                    Congratulations, you have successfully installed and run the framework
                    <strong>Vaííčko</strong> <?= App\Configuration::FW_VERSION ?>!<br>
                    We hope that you will create a great application using this framework.<br>
                </p>
                <p>
                    This simple framework was created for teaching purposes and to better understand how the MVC
                    architecture works.<br>
                    It is intended for students of the subject <em>web application development</em>, but not only
                    for them.
                </p>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col text-center">
            <h4>Authors</h4>
            <div>
                <a href="mailto:Patrik.Hrkut@fri.uniza.sk">doc. Ing. Patrik Hrkút, PhD.</a><br>
                <a href="mailto:Michal.Duracik@fri.uniza.sk">Ing. Michal Ďuračík, PhD.</a><br>
                <a href="mailto:Matej.Mesko@fri.uniza.sk">Ing. Matej Meško, PhD.</a><br><br>
                &copy; 2020-<?= date('Y') ?> University of Žilina, Faculty of Management Science and Informatics,
                Department of Software Technologies
            </div>
        </div>
    </div>
</div>
