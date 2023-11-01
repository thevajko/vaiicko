<?php

/** @var Array $data */

?>

<?php if (count($data['list']) > 0) : ?>
    <ul>
        <?php foreach ($data['list'] as $item) : ?>
            <li><?= $item ?></li>
        <?php endforeach ?>
    </ul>
<?php else : ?>
    V zozname nie sú žiadne položky.
<?php endif ?>