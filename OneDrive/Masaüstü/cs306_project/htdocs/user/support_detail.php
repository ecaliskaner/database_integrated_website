<?php include 'mongo_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket Details</title>
    <style> body { font-family: Arial, sans-serif; margin: 20px; } .comment { border-bottom: 1px solid #eee; padding: 5px 0; } </style>
</head>
<body>
    <h1>Ticket Details</h1>
    <a href="support_list.php">Back to List</a>

    <?php
    if (!isset($_GET['id'])) {
        die("No ticket ID specified.");
    }

    $id = new MongoDB\BSON\ObjectId($_GET['id']);
    
    // Handle Comment Submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_comment = $_POST['comment'];
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            ['_id' => $id],
            ['$push' => ['comments' => $new_comment]]
        );
        $manager->executeBulkWrite($namespace, $bulk);
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
    <h3>Add Comment</h3>
    <form method="post">
        <textarea name="comment" rows="3" cols="40" required></textarea><br>
        <button type="submit">Add Comment</button>
    </form>
    <?php endif; ?>
</body>
</html>
