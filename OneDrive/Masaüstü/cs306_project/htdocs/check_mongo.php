<?php
echo "<h1>MongoDB Setup Diagnostic</h1>";

// 1. Check if extension is loaded
if (extension_loaded("mongodb")) {
    echo "<p style='color:green'><strong>SUCCESS: MongoDB extension is LOADED!</strong></p>";
    $m = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    echo "Manager class found.";
} else {
    echo "<p style='color:red'><strong>ERROR: MongoDB extension is NOT loaded.</strong></p>";
}

// 2. showing correct php.ini
echo "<h2>Configuration File</h2>";
$ini_path = php_ini_loaded_file();
echo "Your loaded php.ini file is: <strong>" . ($ini_path ? $ini_path : "NONE") . "</strong><br>";
echo "<em>(You must edit THIS specific file and add 'extension=mongodb')</em>";

// 3. Extension Directory
echo "<h2>Extension Directory</h2>";
$ext_dir = ini_get('extension_dir');
echo "Your extension directory is: <strong>" . $ext_dir . "</strong><br>";
echo "<em>(You must copy the 'php_mongodb.dll' file into THIS folder)</em>";

// 4. PHP Version Info
echo "<h2>PHP Version</h2>";
echo "PHP version: " . phpversion() . "<br>";
echo "Architecture: " . (PHP_INT_SIZE * 8) . "-bit<br>";
echo "Thread Safety: " . (ZEND_THREAD_SAFE ? "Enabled (TS)" : "Disabled (NTS)") . "<br>";
?>
