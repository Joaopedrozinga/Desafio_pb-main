<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$finder = new \OpenApi\SourceFinder(base_path('app'), [], '*.php');
$found = false;
foreach ($finder as $file) {
    if (str_contains($file->getRealPath(), 'AuthController')) {
        echo $file->getRealPath() . PHP_EOL;
        $found = true;
    }
}
echo $found ? 'Found' : 'NOT FOUND';
echo PHP_EOL;
