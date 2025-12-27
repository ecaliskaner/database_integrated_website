<?php include 'mongo_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Support Ticket</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="navbar-brand"> Real Estate System</a>
        <div class="nav-links">
            <a href="index.php">Dashboard</a>
            <a href="support_list.php">Support</a>
        </div>
    </nav>

    <div class="container">
        <div class="card" style="max-width: 600px; margin: 0 auto;">
            <header class="page-header">
                <h1 class="page-title">Create Ticket</h1>
                <p class="page-subtitle">Describe your issue and we'll get back to you</p>
            </header>

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
                    echo "<div class='message success'>Ticket created successfully! Redirecting...</div>";
                    echo "<meta http-equiv='refresh' content='2;url=support_list.php'>";
                } catch (MongoDB\Driver\Exception\Exception $e) {
                    echo "<div class='message error'>Error: " . $e->getMessage() . "</div>";
                }
            }
            ?>

            <form method="post">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required placeholder="Your Name">
                </div>
                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message" rows="6" required placeholder="Describe your issue detailedly..."></textarea>
                </div>
                <button type="submit" class="btn btn-block">Submit Ticket</button>
            </form>
        </div>
    </div>
</body>
</html>
