<?php

require_once "vendor/autoload.php";

$calculator = new \App\Calculator('123456789012', '000AAA02');

echo $calculator->getPolicyPrice();
echo PHP_EOL;
