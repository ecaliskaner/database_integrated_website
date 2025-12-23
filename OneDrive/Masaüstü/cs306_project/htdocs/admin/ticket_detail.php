<?php include 'mongo_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Ticket Detail</title>
    <style> body { font-family: Arial, sans-serif; margin: 20px; } .comment { border-bottom: 1px solid #eee; padding: 5px 0; } </style>
</head>
<body>
    <h1>Manage Ticket</h1>
    <a href="index.php">Back to Dashboard</a>

    <?php
    if (!isset($_GET['id'])) {
        die("No ticket ID specified.");
    }

    $id = new MongoDB\BSON\ObjectId($_GET['id']);
    
    // Handle Actions
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['resolve'])) {
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->update(
                ['_id' => $id],
                ['$set' => ['status' => false]]
            );
            $manager->executeBulkWrite($namespace, $bulk);
            echo "<p style='color:green'>Ticket Resolved!</p>";
            // Redirect or refresh?
            // header("Location: index.php"); // Optional
        } elseif (isset($_POST['comment'])) {
            $new_comment = "Admin: " . $_POST['comment_text'];
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->update(
                ['_id' => $id],
                ['$push' => ['comments' => $new_comment]]
            );
            $manager->executeBulkWrite($namespace, $bulk);
        }
    }

    // Fetch Ticket
    $filter = ['_id' => $id];
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $manager->executeQuery($namespace, $query);
    $tickets = $cursor->toArray();

    if (count($tickets) == 0) {
        die("Ticket not found.");
    }
    $ticket = $tickets[0];
    ?>

    <div>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($ticket->username); ?></p>
        <p><strong>Created At:</strong> <?php echo $ticket->created_at; ?></p>
        <p><strong>Status:</strong> <?php echo $ticket->status ? 'Active' : 'Resolved'; ?></p>
        <p><strong>Message:</strong><br><?php echo nl2br(htmlspecialchars($ticket->message)); ?></p>
    </div>

    <h3>Comments</h3>
    <?php
    if (isset($ticket->comments) && is_array($ticket->comments)) {
        foreach ($ticket->comments as $comment) {
            echo "<div class='comment'>" . htmlspecialchars($comment) . "</div>";
        }
    } else {
        echo "<p>No comments yet.</p>";
    }
    ?>

    <?php if ($ticket->status): ?>
    <h3>Actions</h3>
    <form method="post">
        <textarea name="comment_text" rows="3" cols="40" placeholder="Add admin comment..."></textarea><br>
        <button type="submit" name="comment">Add Comment</button>
    </form>
    <br>
    <form method="post">
        <button type="submit" name="resolve" style="color:white; background-color:red;">Mark as Resolved</button>
    </form>
    <?php endif; ?>
</body>
</html>
