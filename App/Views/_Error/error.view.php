<?php
$layout = null; //TODO dat toto nastavenie layoutu do dokumentacie!
/** @var array $data */
/** @var \App\Core\HTTPException $exception */
$exception = $data["exception"];
?>

<h1><?=$exception->getCode() . " - " . $exception->getMessage()?></h1>


<?php while ($data["showDetail"] && $exception->getPrevious() != null) { ?>
    <?=get_class($exception->getPrevious())?>: <?=$exception->getPrevious()->getMessage()?>
    <pre>Stack trace:<br><?=$exception->getPrevious()->getTraceAsString() ?></pre>
    <?php $exception = $exception->getPrevious(); ?>
<?php } ?>
