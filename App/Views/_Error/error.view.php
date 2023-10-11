<?php
$layout = null; //TODO dat toto nastavenie layoutu do dokumentacie!
$exception = $data["exception"];
?>

<h1><?php echo $exception->getCode() . " - " . $exception->getMessage() ?></h1>
<pre>
<?php
//TODO doplnit do configu zobrazenie stack trace pokial je povoleny debug
echo $exception->toString()
?>
</pre>
