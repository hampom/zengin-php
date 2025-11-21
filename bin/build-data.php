<?php

declare(strict_types=1);

/**
 * Build data file from source-data submodule
 * Merges banks.json with branch data from branches/ directory
 * Convert to PHP array for better performance
 */

$sourceDir = __DIR__ . '/../source-data/data';
$banksFile = $sourceDir . '/banks.json';
$branchesDir = $sourceDir . '/branches';
$outputDir = __DIR__ . '/../src/Data';
$outputFile = $outputDir . '/banks.php';

echo "Building data files...\n";

if (!file_exists($banksFile)) {
    echo "Banks data not found at: {$banksFile}\n";
    exit(0);
}

if (!is_dir($branchesDir)) {
    echo "Branches directory not found at: {$branchesDir}\n";
    exit(0);
}

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

echo "Reading banks.json...\n";
$banksJson = file_get_contents($banksFile);
$banks = json_decode($banksJson, true);
$banks = array_values($banks);

if (!is_array($banks)) {
    echo "Failed to parse banks.json\n";
    exit(1);
}

echo "Found " . count($banks) . " banks\n";

echo "Reading branch data...\n";
$branchCount = 0;

foreach ($banks as &$bankData) {
    $branchFile = $branchesDir . '/' . $bankData['code'] . '.json';
    
    if (file_exists($branchFile)) {
        $branchJson = file_get_contents($branchFile);
        $branches = json_decode($branchJson, true);
        
        if (is_array($branches)) {
            $bankData['branches'] = array_values($branches);
            $branchCount += count($branches);
        } else {
            $bankData['branches'] = [];
        }
    } else {
        $bankData['branches'] = [];
    }
}
unset($bankData);

echo "Found " . $branchCount . " branches total\n";

// Generate PHP file
echo "Generating PHP file...\n";
$phpCode = "<?php\n\n";
$phpCode .= "declare(strict_types=1);\n\n";
$phpCode .= "/**\n";
$phpCode .= " * Bank data\n";
$phpCode .= " * Auto-generated from zengin-code/source-data\n";
$phpCode .= " * Generated at: " . date('Y-m-d H:i:s') . "\n";
$phpCode .= " * Banks: " . count($banks) . "\n";
$phpCode .= " * Branches: " . $branchCount . "\n";
$phpCode .= " */\n\n";
$phpCode .= "return " . var_export($banks, true) . ";\n";

if (file_put_contents($outputFile, $phpCode) === false) {
    echo "Failed to write output file\n";
    exit(1);
}

echo "Data built successfully!\n";
echo " Output: {$outputFile}\n";
echo "   Size: " . number_format(filesize($outputFile)) . " bytes\n";

exit(0);
