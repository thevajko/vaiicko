<?php

$layout = null;

/** @var array $data */
/** @var \App\Core\Http\HttpException $exception */
$exception = $data["exception"];
?>

<h1><?= $exception->getCode() . " - " . $exception->getMessage() ?></h1>

<?php
while ($data["showDetail"] && $exception->getPrevious() != null) { ?>
    <?= get_class($exception->getPrevious()) ?>: <strong><?= $exception->getPrevious()->getMessage() ?></strong>
    in file <strong><?= $exception->getPrevious()->getFile() ?></strong>
    at line <strong><?= $exception->getPrevious()->getLine() ?></strong>
    <pre>Stack trace:<br><?= $exception->getPrevious()->getTraceAsString() ?></pre>
    <?php $exception = $exception->getPrevious(); ?>
<?php } ?>
