<?php include 'mongo_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Support Ticket</title>
    <style> body { font-family: Arial, sans-serif; margin: 20px; } .message { margin: 10px 0; padding: 10px; border: 1px solid #ccc; background: #f9f9f9; } </style>
</head>
<body>
    <h1>Create Support Ticket</h1>
    <a href="index.php">Back to Home</a> | <a href="support_list.php">Back to Ticket List</a>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $message = $_POST['message'];
        
        $bulk = new MongoDB\Driver\BulkWrite;
        $doc = [
            'username' => $username,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s'),
            'status' => true,
            'comments' => []
        ];
        $bulk->insert($doc);
        
        try {
            $manager->executeBulkWrite($namespace, $bulk);
            echo "<div class='message' style='color:green'>Ticket created successfully!</div>";
        } catch (MongoDB\Driver\Exception\Exception $e) {
            echo "<div class='message' style='color:red'>Error: " . $e->getMessage() . "</div>";
        }
    }
    ?>

    <form method="post">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>
        <label>Message:</label><br>
        <textarea name="message" rows="5" cols="40" required></textarea><br><br>
        <button type="submit">Submit Ticket</button>
    </form>
</body>
</html>
