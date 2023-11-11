<?php

$layout = null; //TODO dat toto nastavenie layoutu do dokumentacie!
/** @var array $data */
/** @var \App\Core\HTTPException $exception */
$exception = $data["exception"];
?>

<h1><?= $exception->getCode() . " - " . $exception->getMessage() ?></h1>

<?php if ($data["showDetail"] && $exception->getCode() != 500) { ?>
    <?= get_class($exception) ?>: <strong><?= $exception->getMessage() ?></strong>
    in file <strong><?= $exception->getFile() ?></strong>
    at line <strong><?= $exception->getLine() ?></strong>
    <pre>Stack trace:<br><?= $exception->getTraceAsString() ?></pre>
<?php } ?>

<?php
while ($data["showDetail"] && $exception->getPrevious() != null) { ?>
    <?= get_class($exception->getPrevious()) ?>: <strong><?= $exception->getPrevious()->getMessage() ?></strong>
    in file <strong><?= $exception->getPrevious()->getFile() ?></strong>
    at line <strong><?= $exception->getPrevious()->getLine() ?></strong>
    <pre>Stack trace:<br><?= $exception->getPrevious()->getTraceAsString() ?></pre>
    <?php  $exception = $exception->getPrevious(); ?>
<?php } ?>
