<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$r = Illuminate\Http\Request::create('/dashboard/koordinat?zoom=13&neLat=5.3&neLng=97.3&swLat=5.0&swLng=97.0', 'GET');
try {
    $response = $kernel->handle($r);
    echo $response->getContent();
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}