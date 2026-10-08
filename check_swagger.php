<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

error_reporting(0);

// Test with a minimal file
$testFile = __DIR__ . '/test_swagger.php';
file_put_contents($testFile, '<?php
/**
 * @OA\Info(title="Test API", version="1.0.0")
 */
class TestClass {}
');

// Try with Generator and StaticAnalyser explicitly
$generator = new \OpenApi\Generator();
$generator->setAnalyser(new \OpenApi\Analysers\StaticAnalyser());

$finder = new \OpenApi\SourceFinder($testFile, [], '*.php');
$openapi = $generator->generate($finder);

if (is_object($openapi->info)) {
    echo "Generator+StaticAnalyser Info: " . $openapi->info->title . "\n";
} else {
    echo "Generator+StaticAnalyser Info type: " . gettype($openapi->info) . "\n";
    echo "Info value: " . var_export($openapi->info, true) . "\n";
}

unlink($testFile);
echo "Done\n";
