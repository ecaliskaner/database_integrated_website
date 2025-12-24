<?php include 'mongo_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="navbar-brand">🏠 Real Estate System</a>
        <div class="nav-links">
            <a href="index.php">Dashboard</a>
            <a href="support_list.php">Support</a>
        </div>
    </nav>

    <div class="container">
        <?php
        if (!isset($_GET['id'])) {
            echo "<div class='message error'>No ticket ID specified.</div>";
            exit;
        }

        $id = new MongoDB\BSON\ObjectId($_GET['id']);
        
        // Handle Comment Submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_comment = $_POST['comment'];
            // Prepend timestamp or user info if feasible, for now just text
            $formatted_comment = "[" . date('Y-m-d H:i') . "] User: " . $new_comment;
            
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->update(
                ['_id' => $id],
                ['$push' => ['comments' => $formatted_comment]]
            );
            $manager->executeBulkWrite($namespace, $bulk);
        }

        // Fetch Ticket
        $filter = ['_id' => $id];
        $query = new MongoDB\Driver\Query($filter);
        $cursor = $manager->executeQuery($namespace, $query);
        $tickets = $cursor->toArray();

        if (count($tickets) == 0) {
            echo "<div class='message error'>Ticket not found.</div>";
            exit;
        }
        $ticket = $tickets[0];
        ?>

        <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 2rem;">
            <!-- Left Column: Ticket Info & Comments -->
            <div>
                <div class="card" style="margin-bottom: 2rem;">
                    <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1rem;">
                        <h2 style="color: var(--primary-color);">Ticket #<?php echo substr($ticket->_id, -6); ?></h2>
                        <span style="color: var(--text-muted); font-size: 0.9rem;">Created on <?php echo $ticket->created_at; ?></span>
                    </div>
                    
                    <p style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.5rem;">
                        <?php echo nl2br(htmlspecialchars($ticket->message)); ?>
                    </p>

                    <div style="background-color: #F8FAFC; padding: 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color);">
                        <strong>Status: </strong>
                        <?php echo $ticket->status ? '<span style="color:var(--success-color); font-weight:bold;">Open</span>' : '<span style="color:var(--text-muted); font-weight:bold;">Resolved</span>'; ?>
                    </div>
                </div>

                <h3>Thread</h3>
                <div class="card">
                    <?php
                    if (isset($ticket->comments) && is_array($ticket->comments) && count($ticket->comments) > 0) {
                        foreach ($ticket->comments as $comment) {
                            echo "<div class='comment'>" . htmlspecialchars($comment) . "</div>";
                        }
                    } else {
                        echo "<p style='color:var(--text-muted); font-style:italic;'>No comments yet.</p>";
                    }
                    ?>
                </div>

                <?php if ($ticket->status): ?>
                <div class="card" style="margin-top: 2rem; border-top: 4px solid var(--primary-color);">
                    <h3>Add Reply</h3>
                    <form method="post">
                        <textarea name="comment" rows="3" required placeholder="Type your reply here..."></textarea>
                        <button type="submit" class="btn">Post Reply</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: User Info -->
            <div>
                <div class="card">
                    <h3>User Info</h3>
                    <p><strong>Username:</strong><br> <?php echo htmlspecialchars($ticket->username); ?></p>
                </div>
                <div style="margin-top: 1rem;">
                    <a href="support_list.php" class="btn" style="background-color: var(--secondary-color); width: 100%; text-align: center;">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
