<?php
/**
 * Template Map Generator
 *
 * This script scans a directory for .tpl files and generates a template map file
 * The map associates template names (without .tpl extension) with their full paths.
 *
 * Usage: php generate-template-map.php
 */

// Configuration
$renderDir = 'back/Render'; // Directory to scan for templates
$outputFile = 'config/template-map.php'; // Output file path
$relativeTo = dirname(__DIR__); // Base directory for relative paths

// Ensure the config directory exists
$configDir = dirname($outputFile);
if (!is_dir($configDir)) {
    if (!mkdir($configDir, 0755, true)) {
        die("Failed to create directory: $configDir\n");
    }
    echo "Created directory: $configDir\n";
}

/**
 * Recursively scans directories for .tpl files
 *
 * @param string $directory Directory to scan
 * @param array &$templates Array to populate with found templates
 * @param string $relativeTo Base path for creating relative paths
 * @return void
 */
function scanForTemplates(string $directory, array &$templates, string $relativeTo): void
{
    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($items as $item) {
        if ($item->isFile() && $item->getExtension() === 'tpl') {
            $fullPath = $item->getPathname();
            $relativePath = str_replace('\\', '/', $fullPath);
            $templateName = basename($fullPath, '.tpl');
            $templates[$templateName] = $relativePath;
        }
    }
}

// Find all template files
$templates = [];
if (is_dir($renderDir)) {
    scanForTemplates($renderDir, $templates, $relativeTo);
    ksort($templates); // Sort templates by key
} else {
    die("Render directory not found: $renderDir\n");
}

// Generate the template map file
$content = "<?php\n\n/**\n * Generated template map\n * @generated " . date('Y-m-d H:i:s') . "\n */\n\nreturn [\n";
foreach ($templates as $name => $path) {
    $content .= "    '$name' => '$path',\n";
}
$content .= "];\n";

// Write the map to the output file
if (file_put_contents($outputFile, $content)) {
    echo "Template map generated successfully!\n";
    echo "Found " . count($templates) . " templates.\n";
    echo "Map saved to: $outputFile\n";
    echo "relativeTo to: $relativeTo\n";
} else {
    die("Failed to write to output file: $outputFile\n");
}
