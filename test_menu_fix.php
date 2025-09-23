<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing menu system...\n";

try {
    $menu = \App\Helpers\MenuHelper::getByName('primary');
    echo "Menu items: " . count($menu) . "\n";
    
    foreach ($menu as $item) {
        echo "- " . $item['title'] . " -> " . $item['url'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\nTest completed.\n";

