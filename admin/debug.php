<?php
// Debug script to help locate connection.php file
echo "<h2>File Structure Debug</h2>";
echo "<p><strong>Current Directory:</strong> " . getcwd() . "</p>";

// Check current directory files
echo "<h3>Files in Current Directory:</h3>";
$files = scandir('.');
echo "<ul>";
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        $type = is_dir($file) ? '[DIR]' : '[FILE]';
        echo "<li>$type $file</li>";
    }
}
echo "</ul>";

// Check parent directory
echo "<h3>Files in Parent Directory:</h3>";
if (file_exists('../')) {
    $parentFiles = scandir('../');
    echo "<ul>";
    foreach ($parentFiles as $file) {
        if ($file != '.' && $file != '..') {
            $type = is_dir('../' . $file) ? '[DIR]' : '[FILE]';
            echo "<li>$type $file</li>";
        }
    }
    echo "</ul>";
}

// Check common subdirectories
$commonDirs = ['includes', 'config', 'db', 'database'];
foreach ($commonDirs as $dir) {
    if (is_dir($dir)) {
        echo "<h3>Files in $dir Directory:</h3>";
        $dirFiles = scandir($dir);
        echo "<ul>";
        foreach ($dirFiles as $file) {
            if ($file != '.' && $file != '..') {
                $type = is_dir($dir . '/' . $file) ? '[DIR]' : '[FILE]';
                echo "<li>$type $file</li>";
            }
        }
        echo "</ul>";
    }
}

// Test different possible paths for connection.php
echo "<h3>Connection.php Search Results:</h3>";
$possiblePaths = [
    'connection.php',
    '../connection.php',
    'config/connection.php',
    'includes/connection.php',
    'db/connection.php',
    'database/connection.php'
];

echo "<ul>";
foreach ($possiblePaths as $path) {
    $exists = file_exists($path) ? "✅ FOUND" : "❌ NOT FOUND";
    echo "<li>$path - $exists</li>";
}
echo "</ul>";
?>