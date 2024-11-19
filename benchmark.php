<?php

require __DIR__ . 'file.php';

use GeoIp2\Database\Reader;

srand(0);

$reader = new Reader('file.php');
$count = 500000;
$startTime = microtime(true);
for ($i = 0; $i < $count; ++$i) {
    $ip = long2ip(rand(0, 2 ** 32 - 1));

    try {
        $t = $reader->city($ip);
    } catch (\GeoIp2\Exception\AddressNotFoundException $e) {
    }
    if ($i % 10000 === 0) {
        echo $i . 'file.php' . $ip . "\n";
    }
}
$endTime = microtime(true);

$duration = $endTime - $startTime;
echo 'file.php' . $count / $duration . "\n";
