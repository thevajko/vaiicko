<?php

/** @var \Framework\Http\HttpException $exception */
/** @var bool $showDetail */
/** @var \Framework\Support\View $view */

$view->setLayout(null);

?>

<h1><?= e($exception->getCode() . " - " . $exception->getMessage()) ?></h1>

<?php
if ($showDetail && $exception->getCode() != 500) :
    ?>
    <?= e(get_class($exception)) ?>: <strong><?= e($exception->getMessage()) ?></strong>
    in file <strong><?= e($exception->getFile()) ?></strong>
    at line <strong><?= e($exception->getLine()) ?></strong>
    <pre>Stack trace:<br><?= e($exception->getTraceAsString()) ?></pre>
<?php endif; ?>

<?php
while ($showDetail && $exception->getPrevious() != null) { ?>
    <?= e(get_class($exception->getPrevious())) ?>: <strong><?= e($exception->getPrevious()->getMessage()) ?></strong>
    in file <strong><?= e($exception->getPrevious()->getFile()) ?></strong>
    at line <strong><?= e($exception->getPrevious()->getLine()) ?></strong>
    <pre>Stack trace:<br><?= e($exception->getPrevious()->getTraceAsString()) ?></pre>
    <?php $exception = $exception->getPrevious(); ?>
<?php } ?>
