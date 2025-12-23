<?php
// Admin MongoDB Config
try {
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
} catch (MongoDB\Driver\Exception\Exception $e) {
    die("Error connecting to MongoDB: " . $e->getMessage());
}

$mongoDbName = "cs306_project";
$mongoCollectionName = "support_tickets";
$namespace = "$mongoDbName.$mongoCollectionName";
?>
