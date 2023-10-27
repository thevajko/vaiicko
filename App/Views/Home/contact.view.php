<?php

/** @var Array $data */

/** @var \App\Core\LinkGenerator $link */
?>

<div class="row">
    <div class="col">
        <h3>Fakulta riadenia a informatiky</h3>
        <strong>Adresa</strong>: Univerzitná 8215/1, 010 26 Žilina<br>
        <strong>Tel. číslo</strong>: +421/41 513 4121<br>

        <strong>GPS</strong>: 49°12'6,4"N 18°45'42,6"E
    </div>
</div>
<div class="row mt-3">
    <div class="col">
        <iframe width="100%" height="300"
                src="https://www.openstreetmap.org/export/embed.html?bbox=18.747396469116214%2C49.193792384417996%2C18.776578903198246%2C49.210336337994846&amp;layer=mapnik&amp;marker=49.202065053033984%2C18.761987686157227"></iframe>
    </div>
</div>
<div class="row mt-3">
    <div class="col">
        <a href="<?= $link->url("home.index") ?>">Späť na hlavnú stránku</a>
    </div>
</div>