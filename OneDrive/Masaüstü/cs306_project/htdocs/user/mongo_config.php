<?php
require_once __DIR__ . '/vendor/autoload.php'; // If using composer, but user might not have it.
// Instructions say: "You are expexted to use MongoDB\Driver\Manager class"
// So I will use the driver directly without composer autoload if possible, or assume extension is loaded.

try {
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
} catch (MongoDB\Driver\Exception\Exception $e) {
    die("Error connecting to MongoDB: " . $e->getMessage());
}

$mongoDbName = "cs306_project";
$mongoCollectionName = "support_tickets";
$namespace = "$mongoDbName.$mongoCollectionName";
?>
