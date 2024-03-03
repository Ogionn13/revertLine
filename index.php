<?php

use App\RevertLine;

require __DIR__.'/vendor/autoload.php';

$reverter = new RevertLine('Привет! Давно не виделись.');
$result = $reverter->revert();
echo $result; // Тевирп! Онвад ен ьсиледив.
